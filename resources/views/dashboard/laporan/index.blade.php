@extends('layouts.app')

@section('title', 'Laporan')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="glass-effect rounded-2xl shadow-xl p-6 mb-6 border border-white/20">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 mb-2">Laporan</h1>
                <p class="text-gray-600">Generate laporan audit dalam format PDF</p>
            </div>
        </div>
    </div>

    <!-- Form Laporan -->
    <div class="glass-effect rounded-2xl shadow-xl p-6 border border-white/20">
        <div class="text-center py-12">
            <i class="fas fa-file-pdf text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Fitur Laporan Tidak Tersedia</h3>
            <p class="text-gray-500 max-w-md mx-auto">
                Modul laporan sedang dalam pengembangan. Silakan gunakan fitur lain yang tersedia di aplikasi.
            </p>
        </div>
    </div>

</div>
@endsection
