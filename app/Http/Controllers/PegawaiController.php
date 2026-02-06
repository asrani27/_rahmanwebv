<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Presensi;
use App\Models\Skpd;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PegawaiController extends Controller
{
    /**
     * Display a listing of the pegawai.
     */
    public function index()
    {
        $pegawais = Pegawai::with('skpd')->latest()->paginate(10);
        return view('admin.pegawai.index', compact('pegawais'));
    }

    /**
     * Show the form for creating a new pegawai.
     */
    public function create()
    {
        $skpds = Skpd::all();
        return view('admin.pegawai.create', compact('skpds'));
    }

    /**
     * Store a newly created pegawai in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nik' => ['required', 'string', 'size:16', 'regex:/^[0-9]+$/', 'unique:pegawai,nik'],
            'nama' => ['required', 'string', 'max:255'],
            'tgl_lahir' => ['required', 'date'],
            'jkel' => ['required', 'in:L,P'],
            'skpd_id' => ['required', 'exists:skpd,id'],
            'telp' => ['required', 'string', 'max:20'],
            'alamat' => ['required', 'string'],
        ]);

        Pegawai::create([
            'nik' => $request->nik,
            'nama' => $request->nama,
            'tgl_lahir' => $request->tgl_lahir,
            'jkel' => $request->jkel,
            'skpd_id' => $request->skpd_id,
            'telp' => $request->telp,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('admin.pegawai.index')
            ->with('success', 'Data pegawai berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified pegawai.
     */
    public function edit(Pegawai $pegawai)
    {
        $skpds = Skpd::all();
        return view('admin.pegawai.edit', compact('pegawai', 'skpds'));
    }

    /**
     * Update the specified pegawai in storage.
     */
    public function update(Request $request, Pegawai $pegawai)
    {
        $request->validate([
            'nik' => ['required', 'string', 'size:16', 'regex:/^[0-9]+$/', 'unique:pegawai,nik,'.$pegawai->id],
            'nama' => ['required', 'string', 'max:255'],
            'tgl_lahir' => ['required', 'date'],
            'jkel' => ['required', 'in:L,P'],
            'skpd_id' => ['required', 'exists:skpd,id'],
            'telp' => ['required', 'string', 'max:20'],
            'alamat' => ['required', 'string'],
        ]);

        $pegawai->update([
            'nik' => $request->nik,
            'nama' => $request->nama,
            'tgl_lahir' => $request->tgl_lahir,
            'jkel' => $request->jkel,
            'skpd_id' => $request->skpd_id,
            'telp' => $request->telp,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('admin.pegawai.index')
            ->with('success', 'Data pegawai berhasil diperbarui!');
    }

    /**
     * Remove the specified pegawai from storage.
     */
    public function destroy(Pegawai $pegawai)
    {
        $pegawai->delete();

        return redirect()->route('admin.pegawai.index')
            ->with('success', 'Data pegawai berhasil dihapus!');
    }

    /**
     * Display pegawai dashboard with presensi history.
     */
    public function dashboard(Request $request)
    {
        // Get authenticated user's pegawai data
        $user = auth()->user();
        $pegawai = Pegawai::where('user_id', $user->id)->first();

        if (!$pegawai) {
            return redirect()->route('dashboard')
                ->with('message', [
                    'type' => 'error',
                    'text' => 'Data pegawai tidak ditemukan!'
                ]);
        }

        $presensi = collect(); // Initialize empty collection

        // If month and year are selected, filter presensi data
        if ($request->filled('bulan') && $request->filled('tahun')) {
            $bulan = $request->input('bulan');
            $tahun = $request->input('tahun');

            // Query presensi data based on filters using NIK
            $presensi = Presensi::byNik($pegawai->nik)
                ->month($bulan)
                ->year($tahun)
                ->orderBy('tanggal', 'desc')
                ->paginate(20);
        }

        return view('pegawai.index', compact('pegawai', 'presensi'));
    }

    /**
     * Display pegawai profile.
     */
    public function profil()
    {
        // Get authenticated user's pegawai data
        $user = auth()->user();
        $pegawai = Pegawai::where('user_id', $user->id)->with('skpd')->first();

        if (!$pegawai) {
            return redirect()->route('dashboard')
                ->with('message', [
                    'type' => 'error',
                    'text' => 'Data pegawai tidak ditemukan!'
                ]);
        }

        return view('pegawai.profil', compact('pegawai', 'user'));
    }

    /**
     * Update pegawai biodata.
     */
    public function updateBiodata(Request $request)
    {
        // Get authenticated user's pegawai data
        $user = auth()->user();
        $pegawai = Pegawai::where('user_id', $user->id)->first();

        if (!$pegawai) {
            return back()->with('error', 'Data pegawai tidak ditemukan!');
        }

        $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'tgl_lahir' => ['required', 'date'],
            'jkel' => ['required', 'in:L,P'],
            'telp' => ['required', 'string', 'max:20'],
            'alamat' => ['required', 'string'],
        ]);

        $pegawai->update([
            'nama' => $request->nama,
            'tgl_lahir' => $request->tgl_lahir,
            'jkel' => $request->jkel,
            'telp' => $request->telp,
            'alamat' => $request->alamat,
        ]);

        // Update user name as well
        $user->update([
            'name' => $request->nama,
        ]);

        return back()->with('success', 'Biodata berhasil diperbarui!');
    }

    /**
     * Update pegawai password.
     */
    public function updatePassword(Request $request)
    {
        // Get authenticated user
        $user = auth()->user();

        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Password saat ini tidak sesuai'
            ])->withInput();
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password berhasil diperbarui!');
    }

    /**
     * Display list of pegawai for SKPD users.
     */
    public function skpdIndex()
    {
        // Get authenticated SKPD user
        $user = auth()->user();
        
        // Find SKPD associated with this user
        $skpd = Skpd::where('user_id', $user->id)->first();
        
        if (!$skpd) {
            return back()->with('error', 'Data SKPD tidak ditemukan!');
        }

        // Get all pegawai belonging to this SKPD
        $pegawais = Pegawai::where('skpd_id', $skpd->id)
            ->with('skpd')
            ->latest()
            ->paginate(10);

        return view('skpd.pegawai.index', compact('pegawais', 'skpd'));
    }

    /**
     * Show the form for creating a new pegawai for SKPD.
     */
    public function skpdCreate()
    {
        // Get authenticated SKPD user
        $user = auth()->user();
        
        // Find SKPD associated with this user
        $skpd = Skpd::where('user_id', $user->id)->first();
        
        if (!$skpd) {
            return back()->with('error', 'Data SKPD tidak ditemukan!');
        }

        return view('skpd.pegawai.create', compact('skpd'));
    }

    /**
     * Store a newly created pegawai in storage for SKPD.
     */
    public function skpdStore(Request $request)
    {
        // Get authenticated SKPD user
        $user = auth()->user();
        
        // Find SKPD associated with this user
        $skpd = Skpd::where('user_id', $user->id)->first();
        
        if (!$skpd) {
            return back()->with('error', 'Data SKPD tidak ditemukan!');
        }

        $request->validate([
            'nik' => ['required', 'string', 'size:16', 'regex:/^[0-9]+$/', 'unique:pegawai,nik'],
            'nama' => ['required', 'string', 'max:255'],
            'tgl_lahir' => ['required', 'date'],
            'jkel' => ['required', 'in:L,P'],
            'telp' => ['required', 'string', 'max:20'],
            'alamat' => ['required', 'string'],
        ]);

        // Create pegawai with locked skpd_id
        Pegawai::create([
            'nik' => $request->nik,
            'nama' => $request->nama,
            'tgl_lahir' => $request->tgl_lahir,
            'jkel' => $request->jkel,
            'skpd_id' => $skpd->id, // Locked to the logged-in SKPD's ID
            'telp' => $request->telp,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('skpd.pegawai.index')
            ->with('success', 'Data pegawai berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified pegawai for SKPD.
     */
    public function skpdEdit(Pegawai $pegawai)
    {
        // Get authenticated SKPD user
        $user = auth()->user();
        
        // Find SKPD associated with this user
        $skpd = Skpd::where('user_id', $user->id)->first();
        
        if (!$skpd) {
            return back()->with('error', 'Data SKPD tidak ditemukan!');
        }

        // Verify that the pegawai belongs to this SKPD
        if ($pegawai->skpd_id !== $skpd->id) {
            return redirect()->route('skpd.pegawai.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit pegawai ini!');
        }

        return view('skpd.pegawai.edit', compact('pegawai', 'skpd'));
    }

    /**
     * Update the specified pegawai in storage for SKPD.
     */
    public function skpdUpdate(Request $request, Pegawai $pegawai)
    {
        // Get authenticated SKPD user
        $user = auth()->user();
        
        // Find SKPD associated with this user
        $skpd = Skpd::where('user_id', $user->id)->first();
        
        if (!$skpd) {
            return back()->with('error', 'Data SKPD tidak ditemukan!');
        }

        // Verify that the pegawai belongs to this SKPD
        if ($pegawai->skpd_id !== $skpd->id) {
            return redirect()->route('skpd.pegawai.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit pegawai ini!');
        }

        $request->validate([
            'nik' => ['required', 'string', 'size:16', 'regex:/^[0-9]+$/', 'unique:pegawai,nik,'.$pegawai->id],
            'nama' => ['required', 'string', 'max:255'],
            'tgl_lahir' => ['required', 'date'],
            'jkel' => ['required', 'in:L,P'],
            'telp' => ['required', 'string', 'max:20'],
            'alamat' => ['required', 'string'],
        ]);

        // Update pegawai with locked skpd_id
        $pegawai->update([
            'nik' => $request->nik,
            'nama' => $request->nama,
            'tgl_lahir' => $request->tgl_lahir,
            'jkel' => $request->jkel,
            'skpd_id' => $skpd->id, // Ensure skpd_id remains locked
            'telp' => $request->telp,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('skpd.pegawai.index')
            ->with('success', 'Data pegawai berhasil diperbarui!');
    }

    /**
     * Remove the specified pegawai from storage for SKPD.
     */
    public function skpdDestroy(Pegawai $pegawai)
    {
        // Get authenticated SKPD user
        $user = auth()->user();
        
        // Find SKPD associated with this user
        $skpd = Skpd::where('user_id', $user->id)->first();
        
        if (!$skpd) {
            return back()->with('error', 'Data SKPD tidak ditemukan!');
        }

        // Verify that the pegawai belongs to this SKPD
        if ($pegawai->skpd_id !== $skpd->id) {
            return redirect()->route('skpd.pegawai.index')
                ->with('error', 'Anda tidak memiliki akses untuk menghapus pegawai ini!');
        }

        $pegawai->delete();

        return redirect()->route('skpd.pegawai.index')
            ->with('success', 'Data pegawai berhasil dihapus!');
    }

    /**
     * Create user for the specified pegawai for SKPD.
     */
    public function createUser(Pegawai $pegawai)
    {
        // Get authenticated SKPD user
        $user = auth()->user();
        
        // Find SKPD associated with this user
        $skpd = Skpd::where('user_id', $user->id)->first();
        
        if (!$skpd) {
            return back()->with('error', 'Data SKPD tidak ditemukan!');
        }

        // Verify that the pegawai belongs to this SKPD
        if ($pegawai->skpd_id !== $skpd->id) {
            return redirect()->route('skpd.pegawai.index')
                ->with('error', 'Anda tidak memiliki akses untuk pegawai ini!');
        }

        // Check if user already exists
        if ($pegawai->user_id !== null) {
            return redirect()->route('skpd.pegawai.index')
                ->with('error', 'Pegawai ini sudah memiliki user!');
        }

        // Use NIK as username
        $username = $pegawai->nik;

        // Create user with default credentials
        $userPegawai = User::create([
            'name' => $pegawai->nama,
            'username' => $username,
            'password' => Hash::make('pegawai'),
            'role' => 'pegawai',
        ]);

        // Update Pegawai with user_id
        $pegawai->update([
            'user_id' => $userPegawai->id,
        ]);

        return redirect()->route('skpd.pegawai.index')
            ->with('success', 'User pegawai berhasil dibuat! Username: ' . $username . ', Password: pegawai');
    }

    /**
     * Reset password for pegawai user for SKPD.
     */
    public function resetPassword(Pegawai $pegawai)
    {
        // Get authenticated SKPD user
        $user = auth()->user();
        
        // Find SKPD associated with this user
        $skpd = Skpd::where('user_id', $user->id)->first();
        
        if (!$skpd) {
            return back()->with('error', 'Data SKPD tidak ditemukan!');
        }

        // Verify that the pegawai belongs to this SKPD
        if ($pegawai->skpd_id !== $skpd->id) {
            return redirect()->route('skpd.pegawai.index')
                ->with('error', 'Anda tidak memiliki akses untuk pegawai ini!');
        }

        // Check if user exists
        if ($pegawai->user_id === null) {
            return redirect()->route('skpd.pegawai.index')
                ->with('error', 'Pegawai ini belum memiliki user!');
        }

        // Reset password to default
        $pegawai->user->update([
            'password' => Hash::make('pegawai'),
        ]);

        return redirect()->route('skpd.pegawai.index')
            ->with('success', 'Password user pegawai berhasil direset! Password baru: pegawai');
    }
}
