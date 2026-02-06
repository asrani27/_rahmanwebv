@extends('layouts.app')

@section('title', 'Profil SKPD')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="glass-effect rounded-2xl shadow-xl p-6 mb-6 border border-white/20">
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Profil SKPD</h1>
        <p class="text-gray-600">Kelola data profil dan keamanan akun</p>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            <span>{{ session('success') }}</span>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
        <div class="flex items-center">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <span>{{ session('error') }}</span>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Biodata Form -->
        <div class="glass-effect rounded-2xl shadow-xl p-6 border border-white/20">
            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-user-circle text-purple-600 mr-3"></i>
                Data Biodata
            </h3>
            <form action="{{ route('skpd.update.profil') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama SKPD</label>
                        <input type="text" name="nama" value="{{ $skpd->nama }}" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kode SKPD</label>
                        <input type="text" name="kode" value="{{ $skpd->kode }}" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                        <p class="text-xs text-gray-500 mt-1">Kode SKPD tanpa spasi (akan digunakan sebagai username)</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                        <input type="text" value="{{ strtolower($skpd->kode) }}" disabled
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 bg-gray-100 text-gray-500">
                    </div>
                    <button type="submit"
                        class="w-full bg-gradient-to-r from-purple-600 to-pink-600 text-white px-6 py-3 rounded-lg hover:from-purple-700 hover:to-pink-700 transition-all duration-300 flex items-center justify-center space-x-2">
                        <i class="fas fa-save"></i>
                        <span>Simpan Biodata</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Password Form -->
        <div class="glass-effect rounded-2xl shadow-xl p-6 border border-white/20">
            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-lock text-purple-600 mr-3"></i>
                Ganti Password
            </h3>
            <form action="{{ route('skpd.update.password') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Password Saat Ini</label>
                        <input type="password" name="current_password" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                        @error('current_password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                        <input type="password" name="password" required minlength="8"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                        <p class="text-xs text-gray-500 mt-1">Minimal 8 karakter</p>
                        @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                    </div>
                    <button type="submit"
                        class="w-full bg-gradient-to-r from-purple-600 to-pink-600 text-white px-6 py-3 rounded-lg hover:from-purple-700 hover:to-pink-700 transition-all duration-300 flex items-center justify-center space-x-2">
                        <i class="fas fa-key"></i>
                        <span>Update Password</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection