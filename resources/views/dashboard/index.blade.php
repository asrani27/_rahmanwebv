@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Welcome Section -->
    <div class="glass-effect rounded-2xl shadow-xl p-8 mb-8 border border-white/20">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Sistem Informasi Aplikasi Presensi Pegawai
                    Pemerintah Provinsi Kalimantan Selatan</h1>
                <p class="text-gray-600 text-lg">Selamat Datang, {{ auth()->user()->name }}! 👋 Kelola data kehadiran
                    pegawai dengan mudah dan efisien.</p>
            </div>
            <div class="hidden md:block">
                <div
                    class="w-20 h-20 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center shadow-lg floating">
                    <span class="text-white font-bold text-2xl">{{ strtoupper(substr(auth()->user()->name, 0, 1))
                        }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
        <!-- Users Card -->
        @if(auth()->user()->role === 'admin')
        <div class="card-hover bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Users</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalUsers }}</p>
                    <p class="text-xs text-blue-600 mt-2">
                        <i class="fas fa-users"></i> Total Pengguna
                    </p>
                </div>
                <div
                    class="w-14 h-14 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-user text-white text-xl"></i>
                </div>
            </div>
        </div>
        @endif

        <!-- Pegawai Card -->
        <div class="card-hover bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Pegawai</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalPegawai }}</p>
                    <p class="text-xs text-green-600 mt-2">
                        <i class="fas fa-users"></i> Total Pegawai
                    </p>
                </div>
                <div
                    class="w-14 h-14 bg-gradient-to-r from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-users text-white text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Quick Actions -->
        <div class="glass-effect rounded-2xl shadow-xl p-6 border border-white/20">
            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-bolt text-purple-600 mr-3"></i>
                Aksi Cepat
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                @if(auth()->user()->role === 'admin')
                <a href="{{ route('users.index') }}"
                    class="group p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl hover:from-blue-100 hover:to-blue-200 transition-all duration-300 hover:scale-105 border border-blue-100">
                    <i
                        class="fas fa-user text-2xl text-blue-600 mb-2 group-hover:scale-110 transition-transform"></i>
                    <p class="text-sm font-medium text-gray-700">Manajemen User</p>
                </a>
                @endif

                <a href="{{ route('pegawai.index') }}"
                    class="group p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-xl hover:from-green-100 hover:to-green-200 transition-all duration-300 hover:scale-105 border border-green-100">
                    <i
                        class="fas fa-users text-2xl text-green-600 mb-2 group-hover:scale-110 transition-transform"></i>
                    <p class="text-sm font-medium text-gray-700">Data Pegawai</p>
                </a>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="glass-effect rounded-2xl shadow-xl p-6 border border-white/20">
            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-history text-purple-600 mr-3"></i>
                Aktivitas Terbaru
            </h3>
            <div class="space-y-4">
                <div
                    class="flex items-start space-x-3 p-3 bg-gradient-to-r from-purple-50 to-pink-50 rounded-lg border border-purple-100">
                    <div class="w-2 h-2 bg-purple-500 rounded-full mt-2"></div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-800">Selamat datang!</p>
                        <p class="text-xs text-gray-500">Login ke sistem</p>
                        <p class="text-xs text-gray-400 mt-1">Baru saja</p>
                    </div>
                </div>
                <div class="flex items-center justify-center py-8">
                    <div class="text-center">
                        <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
                        <p class="text-sm text-gray-500">Belum ada aktivitas lain</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
