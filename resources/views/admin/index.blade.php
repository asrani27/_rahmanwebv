@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<!-- Page Header -->
<div class="mb-8">
    <h2 class="text-3xl font-bold text-gray-800">Dashboard Admin</h2>
    <p class="text-gray-600 mt-2">Selamat datang, {{ auth()->user()->name }}! Berikut adalah ringkasan sistem.</p>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total SKPD -->
    <div class="bg-white rounded-2xl shadow-lg p-6 card-hover border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Total SKPD</p>
                <h3 class="text-3xl font-bold text-gray-800">{{ App\Models\User::where('role', 'skpd')->count() }}</h3>
            </div>
            <div
                class="w-14 h-14 rounded-xl bg-gradient-to-br from-indigo-500 to-blue-600 flex items-center justify-center shadow-lg">
                <i class="fas fa-building text-white text-2xl"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-sm">
            <span class="text-indigo-500 font-medium"><i class="fas fa-building mr-1"></i>Organisasi</span>
            <span class="text-gray-400 ml-2">Terdaftar</span>
        </div>
    </div>

    <!-- Total Pegawai -->
    <div class="bg-white rounded-2xl shadow-lg p-6 card-hover border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Total Pegawai</p>
                <h3 class="text-3xl font-bold text-gray-800">{{ App\Models\Pegawai::count() }}</h3>
            </div>
            <div
                class="w-14 h-14 rounded-xl bg-gradient-to-br from-pink-500 to-rose-500 flex items-center justify-center shadow-lg">
                <i class="fas fa-users text-white text-2xl"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-sm">
            <span class="text-pink-500 font-medium"><i class="fas fa-users mr-1"></i>Data</span>
            <span class="text-gray-400 ml-2">Terdaftar</span>
        </div>
    </div>

    <!-- Lokasi Presensi -->
    <div class="bg-white rounded-2xl shadow-lg p-6 card-hover border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Lokasi Presensi</p>
                <h3 class="text-3xl font-bold text-gray-800">{{ App\Models\User::where('role', 'skpd')->count() }}</h3>
            </div>
            <div
                class="w-14 h-14 rounded-xl bg-gradient-to-br from-red-500 to-pink-500 flex items-center justify-center shadow-lg">
                <i class="fas fa-map-marker-alt text-white text-2xl"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-sm">
            <span class="text-red-500 font-medium"><i class="fas fa-map-pin mr-1"></i>Titik</span>
            <span class="text-gray-400 ml-2">Terdaftar</span>
        </div>
    </div>

    <!-- Total Tim Audit -->
    <div class="bg-white rounded-2xl shadow-lg p-6 card-hover border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Tim Audit</p>
                <h3 class="text-3xl font-bold text-gray-800"></h3>
            </div>
            <div
                class="w-14 h-14 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-500 flex items-center justify-center shadow-lg">
                <i class="fas fa-user-tie text-white text-2xl"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-sm">
            <span class="text-blue-500 font-medium"><i class="fas fa-clipboard-check mr-1"></i>Tersedia</span>
            <span class="text-gray-400 ml-2">Tim aktif</span>
        </div>
    </div>
</div>

@endsection