<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;
use App\Models\Skpd;
use App\Models\Pegawai;
use Illuminate\Http\Request;

class LokasiController extends Controller
{
    /**
     * Display a listing of the lokasi.
     */
    public function index()
    {
        $lokasis = Lokasi::with('skpd')->latest()->paginate(10);
        return view('lokasi.index', compact('lokasis'));
    }

    /**
     * Show the form for creating a new lokasi.
     */
    public function create()
    {
        $skpds = Skpd::all();
        return view('lokasi.create', compact('skpds'));
    }

    /**
     * Store a newly created lokasi in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'skpd_id' => ['required', 'exists:skpd,id'],
            'nama' => ['required', 'string', 'max:255'],
            'lat' => ['required', 'numeric'],
            'long' => ['required', 'numeric'],
            'radius' => ['required', 'numeric', 'min:1'],
        ]);

        Lokasi::create([
            'skpd_id' => $request->skpd_id,
            'nama' => $request->nama,
            'lat' => $request->lat,
            'long' => $request->long,
            'radius' => $request->radius ?? 100,
        ]);

        return redirect()->route('admin.lokasi.index')
            ->with('success', 'Data lokasi berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified lokasi.
     */
    public function edit(Lokasi $lokasi)
    {
        $skpds = Skpd::all();
        return view('lokasi.edit', compact('lokasi', 'skpds'));
    }

    /**
     * Update the specified lokasi in storage.
     */
    public function update(Request $request, Lokasi $lokasi)
    {
        $request->validate([
            'skpd_id' => ['required', 'exists:skpd,id'],
            'nama' => ['required', 'string', 'max:255'],
            'lat' => ['required', 'numeric'],
            'long' => ['required', 'numeric'],
            'radius' => ['required', 'numeric', 'min:1'],
        ]);

        $lokasi->update([
            'skpd_id' => $request->skpd_id,
            'nama' => $request->nama,
            'lat' => $request->lat,
            'long' => $request->long,
            'radius' => $request->radius ?? 100,
        ]);

        return redirect()->route('admin.lokasi.index')
            ->with('success', 'Data lokasi berhasil diperbarui!');
    }

    /**
     * Remove the specified lokasi from storage.
     */
    public function destroy(Lokasi $lokasi)
    {
        $lokasi->delete();

        return redirect()->route('admin.lokasi.index')
            ->with('success', 'Data lokasi berhasil dihapus!');
    }

    /**
     * Show the form to add pegawai to lokasi.
     */
    public function addPegawai(Lokasi $lokasi)
    {
        // Get all pegawais that are not already assigned to this lokasi
        $availablePegawais = Pegawai::whereDoesntHave('lokasis', function ($query) use ($lokasi) {
            $query->where('lokasi_id', $lokasi->id);
        })->get();

        $assignedPegawais = $lokasi->pegawais;

        return view('lokasi.add-pegawai', compact('lokasi', 'availablePegawais', 'assignedPegawais'));
    }

    /**
     * Store pegawai to lokasi.
     */
    public function storePegawai(Request $request, Lokasi $lokasi)
    {
        $request->validate([
            'pegawai_ids' => ['required', 'array'],
            'pegawai_ids.*' => ['exists:pegawai,id'],
        ]);

        $lokasi->pegawais()->attach($request->pegawai_ids);

        return redirect()->route('admin.lokasi.add-pegawai', $lokasi->id)
            ->with('success', 'Pegawai berhasil ditambahkan ke lokasi!');
    }

    /**
     * Remove pegawai from lokasi.
     */
    public function removePegawai(Lokasi $lokasi, Pegawai $pegawai)
    {
        $lokasi->pegawais()->detach($pegawai->id);

        return redirect()->route('admin.lokasi.add-pegawai', $lokasi->id)
            ->with('success', 'Pegawai berhasil dihapus dari lokasi!');
    }

    /**
     * Display locations for the authenticated SKPD.
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

        // Get locations for this SKPD
        $lokasis = Lokasi::where('skpd_id', $skpd->id)
            ->latest()
            ->paginate(10);

        return view('skpd.lokasi.index', compact('lokasis', 'skpd'));
    }

    /**
     * Show the form for creating a new lokasi for SKPD.
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

        // Get pegawais belonging to this SKPD
        $pegawais = Pegawai::where('skpd_id', $skpd->id)->get();

        return view('skpd.lokasi.create', compact('skpd', 'pegawais'));
    }

    /**
     * Store a newly created lokasi in storage for SKPD.
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
            'nama' => ['required', 'string', 'max:255'],
            'lat' => ['required', 'numeric'],
            'long' => ['required', 'numeric'],
            'radius' => ['required', 'numeric', 'min:1'],
            'pegawai_ids' => ['nullable', 'array'],
            'pegawai_ids.*' => ['exists:pegawai,id'],
        ]);

        // Create lokasi with locked skpd_id
        $lokasi = Lokasi::create([
            'skpd_id' => $skpd->id, // Locked to the logged-in SKPD's ID
            'nama' => $request->nama,
            'lat' => $request->lat,
            'long' => $request->long,
            'radius' => $request->radius ?? 100,
        ]);

        // Attach pegawais to the lokasi
        if ($request->has('pegawai_ids')) {
            $lokasi->pegawais()->attach($request->pegawai_ids);
        }

        return redirect()->route('skpd.lokasi.index')
            ->with('success', 'Data lokasi berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified lokasi for SKPD.
     */
    public function skpdEdit(Lokasi $lokasi)
    {
        // Get authenticated SKPD user
        $user = auth()->user();
        
        // Find SKPD associated with this user
        $skpd = Skpd::where('user_id', $user->id)->first();
        
        if (!$skpd) {
            return back()->with('error', 'Data SKPD tidak ditemukan!');
        }

        // Verify that the lokasi belongs to this SKPD
        if ($lokasi->skpd_id !== $skpd->id) {
            return redirect()->route('skpd.lokasi.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit lokasi ini!');
        }

        // Get all pegawais belonging to this SKPD
        $allPegawais = Pegawai::where('skpd_id', $skpd->id)->get();
        // Get pegawais already assigned to this lokasi
        $assignedPegawais = $lokasi->pegawais;

        return view('skpd.lokasi.edit', compact('lokasi', 'skpd', 'allPegawais', 'assignedPegawais'));
    }

    /**
     * Update the specified lokasi in storage for SKPD.
     */
    public function skpdUpdate(Request $request, Lokasi $lokasi)
    {
        // Get authenticated SKPD user
        $user = auth()->user();
        
        // Find SKPD associated with this user
        $skpd = Skpd::where('user_id', $user->id)->first();
        
        if (!$skpd) {
            return back()->with('error', 'Data SKPD tidak ditemukan!');
        }

        // Verify that the lokasi belongs to this SKPD
        if ($lokasi->skpd_id !== $skpd->id) {
            return redirect()->route('skpd.lokasi.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit lokasi ini!');
        }

        $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'lat' => ['required', 'numeric'],
            'long' => ['required', 'numeric'],
            'radius' => ['required', 'numeric', 'min:1'],
            'pegawai_ids' => ['nullable', 'array'],
            'pegawai_ids.*' => ['exists:pegawai,id'],
        ]);

        // Update lokasi with locked skpd_id
        $lokasi->update([
            'skpd_id' => $skpd->id, // Ensure skpd_id remains locked
            'nama' => $request->nama,
            'lat' => $request->lat,
            'long' => $request->long,
            'radius' => $request->radius ?? 100,
        ]);

        // Sync pegawais to the lokasi
        if ($request->has('pegawai_ids')) {
            $lokasi->pegawais()->sync($request->pegawai_ids);
        } else {
            $lokasi->pegawais()->detach();
        }

        return redirect()->route('skpd.lokasi.index')
            ->with('success', 'Data lokasi berhasil diperbarui!');
    }

    /**
     * Remove the specified lokasi from storage for SKPD.
     */
    public function skpdDestroy(Lokasi $lokasi)
    {
        // Get authenticated SKPD user
        $user = auth()->user();
        
        // Find SKPD associated with this user
        $skpd = Skpd::where('user_id', $user->id)->first();
        
        if (!$skpd) {
            return back()->with('error', 'Data SKPD tidak ditemukan!');
        }

        // Verify that the lokasi belongs to this SKPD
        if ($lokasi->skpd_id !== $skpd->id) {
            return redirect()->route('skpd.lokasi.index')
                ->with('error', 'Anda tidak memiliki akses untuk menghapus lokasi ini!');
        }

        $lokasi->delete();

        return redirect()->route('skpd.lokasi.index')
            ->with('success', 'Data lokasi berhasil dihapus!');
    }
}
