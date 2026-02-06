<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        // If user is already logged in, redirect to appropriate dashboard
        if (Auth::check()) {
            // Redirect based on user role
            if (Auth::user()->role == 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif (Auth::user()->role == 'skpd') {
                return redirect()->route('skpd.dashboard');
            }
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required',
        ]);

        // Find user by username
        $user = \App\Models\User::where('username', $credentials['username'])->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            Auth::login($user);
            $request->session()->regenerate();

            // Redirect based on user role
            if (Auth::user()->role == 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif (Auth::user()->role == 'skpd') {
                return redirect()->route('skpd.dashboard');
            } elseif (Auth::user()->role == 'pegawai') {
                return redirect()->route('pegawai.dashboard');
            }

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    /**
     * API Login for Pegawai (Mobile App)
     */
    public function apiLogin(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Find user by username
        $user = \App\Models\User::where('username', $credentials['username'])->first();

        // Check if user exists and password is correct
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Username atau password salah',
            ], 401);
        }

        // Check if user role is pegawai
        if ($user->role !== 'pegawai') {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Hanya pegawai yang dapat login melalui aplikasi mobile',
            ], 403);
        }

        // Get pegawai data
        $pegawai = \App\Models\Pegawai::where('user_id', $user->id)->first();

        if (!$pegawai) {
            return response()->json([
                'success' => false,
                'message' => 'Data pegawai tidak ditemukan',
            ], 404);
        }

        // Generate API token (simple approach - for production consider using Laravel Sanctum)
        $token = hash('sha256', $user->id . time() . rand());

        // Store token in user model (you may want to add api_token column to users table)
        // For now, we'll use a simpler approach with session-based token
        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'role' => $user->role,
                ],
                'pegawai' => [
                    'id' => $pegawai->id,
                    'nik' => $pegawai->nik,
                    'nama' => $pegawai->nama,
                    'tgl_lahir' => $pegawai->tgl_lahir,
                    'jkel' => $pegawai->jkel,
                    'telp' => $pegawai->telp,
                    'alamat' => $pegawai->alamat,
                    'skpd_id' => $pegawai->skpd_id,
                ],
                'skpd' => $pegawai->skpd ? [
                    'id' => $pegawai->skpd->id,
                    'nama' => $pegawai->skpd->nama,
                ] : null,
            ],
        ], 200);
    }

    /**
     * API Logout for Pegawai (Mobile App)
     */
    public function apiLogout(Request $request)
    {
        // In a real implementation with Laravel Sanctum or token-based auth,
        // you would revoke the token here
        
        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil',
        ], 200);
    }

    /**
     * API Get Pegawai Profile
     */
    public function apiProfile(Request $request)
    {
        // For now, we'll accept username as parameter
        // In production, use proper token-based authentication
        $username = $request->query('username');

        if (!$username) {
            return response()->json([
                'success' => false,
                'message' => 'Username diperlukan',
            ], 400);
        }

        $user = \App\Models\User::where('username', $username)->first();

        if (!$user || $user->role !== 'pegawai') {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan atau bukan pegawai',
            ], 404);
        }

        $pegawai = \App\Models\Pegawai::where('user_id', $user->id)->with('skpd', 'lokasis')->first();

        if (!$pegawai) {
            return response()->json([
                'success' => false,
                'message' => 'Data pegawai tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data pegawai berhasil diambil',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'role' => $user->role,
                ],
                'pegawai' => [
                    'id' => $pegawai->id,
                    'nik' => $pegawai->nik,
                    'nama' => $pegawai->nama,
                    'tgl_lahir' => $pegawai->tgl_lahir,
                    'jkel' => $pegawai->jkel,
                    'telp' => $pegawai->telp,
                    'alamat' => $pegawai->alamat,
                    'skpd_id' => $pegawai->skpd_id,
                ],
                'skpd' => $pegawai->skpd ? [
                    'id' => $pegawai->skpd->id,
                    'nama' => $pegawai->skpd->nama,
                ] : null,
                'lokasis' => $pegawai->lokasis->map(function ($lokasi) {
                    return [
                        'id' => $lokasi->id,
                        'nama' => $lokasi->nama,
                        'latitude' => $lokasi->latitude,
                        'longitude' => $lokasi->longitude,
                        'radius' => $lokasi->radius,
                    ];
                }),
            ],
        ], 200);
    }
}
