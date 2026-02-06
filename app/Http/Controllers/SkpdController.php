<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use App\Models\Skpd;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SkpdController extends Controller
{
    /**
     * Display a listing of the SKPD.
     */
    public function index()
    {
        // Only admin can view SKPD
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $skpds = Skpd::latest()->paginate(10);
        return view('admin.skpd.index', compact('skpds'));
    }

    /**
     * Show the form for creating a new SKPD.
     */
    public function create()
    {
        // Only admin can create SKPD
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        return view('admin.skpd.create');
    }

    /**
     * Store a newly created SKPD in storage.
     */
    public function store(Request $request)
    {
        // Only admin can create SKPD
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'kode' => ['required', 'string', 'max:50', 'regex:/^[^\s]+$/', 'unique:skpd,kode'],
            'nama' => ['required', 'string', 'max:255'],
        ]);

        Skpd::create([
            'kode' => $request->kode,
            'nama' => $request->nama,
        ]);

        return redirect()->route('admin.skpd.index')
            ->with('success', 'Data SKPD berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified SKPD.
     */
    public function edit(Skpd $skpd)
    {
        // Only admin can edit SKPD
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        return view('admin.skpd.edit', compact('skpd'));
    }

    /**
     * Update the specified SKPD in storage.
     */
    public function update(Request $request, Skpd $skpd)
    {
        // Only admin can update SKPD
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'kode' => ['required', 'string', 'max:50', 'regex:/^[^\s]+$/', 'unique:skpd,kode,' . $skpd->id],
            'nama' => ['required', 'string', 'max:255'],
        ]);

        $skpd->update([
            'kode' => $request->kode,
            'nama' => $request->nama,
        ]);

        return redirect()->route('admin.skpd.index')
            ->with('success', 'Data SKPD berhasil diperbarui!');
    }

    /**
     * Remove the specified SKPD from storage.
     */
    public function destroy(Skpd $skpd)
    {
        // Only admin can delete SKPD
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $skpd->delete();

        return redirect()->route('admin.skpd.index')
            ->with('success', 'Data SKPD berhasil dihapus!');
    }

    /**
     * Create user for the specified SKPD.
     */
    public function createUser(Skpd $skpd)
    {
        // Only admin can create user for SKPD
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        // Check if user already exists
        if ($skpd->user_id !== null) {
            return redirect()->route('admin.skpd.index')
                ->with('error', 'SKPD ini sudah memiliki user!');
        }

        // Generate username from SKPD code (lowercase)
        $username = strtolower($skpd->kode);

        // Create user with default credentials
        $user = User::create([
            'name' => $skpd->nama,
            'username' => $username,
            'password' => Hash::make('adminskpd'),
            'role' => 'skpd',
        ]);

        // Update SKPD with user_id
        $skpd->update([
            'user_id' => $user->id,
        ]);

        return redirect()->route('admin.skpd.index')
            ->with('success', 'User SKPD berhasil dibuat! Username: ' . $username . ', Password: adminskpd');
    }

    /**
     * Reset password for SKPD user.
     */
    public function resetPassword(Skpd $skpd)
    {
        // Only admin can reset password
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        // Check if user exists
        if ($skpd->user_id === null) {
            return redirect()->route('admin.skpd.index')
                ->with('error', 'SKPD ini belum memiliki user!');
        }

        // Reset password to default
        $skpd->user->update([
            'password' => Hash::make('adminskpd'),
        ]);

        return redirect()->route('admin.skpd.index')
            ->with('success', 'Password user SKPD berhasil direset! Password baru: adminskpd');
    }

    /**
     * Display SKPD profile and allow editing.
     */
    public function profil()
    {
        // Get authenticated SKPD user
        $user = auth()->user();
        
        // Find SKPD associated with this user
        $skpd = Skpd::where('user_id', $user->id)->first();
        
        if (!$skpd) {
            return back()->with('error', 'Data SKPD tidak ditemukan!');
        }

        return view('skpd.profil', compact('skpd', 'user'));
    }

    /**
     * Update SKPD profile.
     */
    public function updateProfil(Request $request)
    {
        $user = auth()->user();
        $skpd = Skpd::where('user_id', $user->id)->first();
        
        if (!$skpd) {
            return back()->with('error', 'Data SKPD tidak ditemukan!');
        }

        $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'kode' => ['required', 'string', 'max:50', 'regex:/^[^\s]+$/', 'unique:skpd,kode,' . $skpd->id],
        ]);

        // Update SKPD data
        $skpd->update([
            'nama' => $request->nama,
            'kode' => $request->kode,
        ]);

        // Update user name as well
        $user->update([
            'name' => $request->nama,
        ]);

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Update SKPD password.
     */
    public function updatePassword(Request $request)
    {
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
     * Display SKPD dashboard with presensi data.
     */
    public function dashboard(Request $request)
    {
        // Get authenticated SKPD user
        $user = auth()->user();
        
        // Find SKPD associated with this user
        $skpd = Skpd::where('user_id', $user->id)->first();
        
        if (!$skpd) {
            return back()->with('error', 'Data SKPD tidak ditemukan!');
        }

        $presensi = collect(); // Initialize empty collection

        // If tanggal is selected, filter presensi data
        if ($request->filled('tanggal')) {
            $tanggal = $request->input('tanggal');

            // Query presensi data for this SKPD on the selected date using join to avoid collation issues
            $presensi = Presensi::select('presensi.*')
                ->join('pegawai', function($join) {
                    $join->on(DB::raw('presensi.nik'), '=', DB::raw('pegawai.nik'));
                })
                ->whereDate('presensi.tanggal', $tanggal)
                ->where('pegawai.skpd_id', $skpd->id)
                ->with('pegawai')
                ->orderBy('presensi.jam_datang', 'desc')
                ->get();
        }

        return view('skpd.index', compact('skpd', 'presensi'));
    }
}
