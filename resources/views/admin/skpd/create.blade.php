@extends('layouts.app')

@section('title', 'Tambah SKPD')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page Header -->
    <div class="glass-effect rounded-2xl shadow-xl p-6 mb-8 border border-white/20">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('admin.skpd.index') }}" 
                   class="mr-4 inline-flex items-center justify-center w-10 h-10 bg-white rounded-lg text-gray-600 hover:bg-gray-50 transition-colors">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 mb-2">Tambah SKPD</h1>
                    <p class="text-gray-600">Tambahkan data Satuan Kerja Perangkat Daerah baru</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="glass-effect rounded-2xl shadow-xl p-8 border border-white/20">
        <form action="{{ route('admin.skpd.store') }}" method="POST">
            @csrf

            <!-- Error Messages -->
            @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-circle text-red-500 mr-3 mt-0.5"></i>
                    <div class="flex-1">
                        <p class="font-medium text-red-700 mb-2">Terjadi kesalahan:</p>
                        <ul class="list-disc list-inside text-sm text-red-600 space-y-1">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            <div class="space-y-6">
                <!-- Kode SKPD -->
                <div>
                    <label for="kode" class="block text-sm font-bold text-gray-700 mb-2">
                        <i class="fas fa-code mr-2 text-purple-600"></i>
                        Kode SKPD
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="kode" 
                           name="kode" 
                           value="{{ old('kode') }}"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all"
                           placeholder="Masukkan kode (tanpa spasi)"
                           oninput="this.value = this.value.replace(/\s/g, '')"
                           required>
                    <p class="mt-1 text-xs text-gray-500">Kode tidak boleh mengandung spasi</p>
                    @error('kode')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nama SKPD -->
                <div>
                    <label for="nama" class="block text-sm font-bold text-gray-700 mb-2">
                        <i class="fas fa-building mr-2 text-purple-600"></i>
                        Nama SKPD
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="nama" 
                           name="nama" 
                           value="{{ old('nama') }}"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all"
                           placeholder="Masukkan nama SKPD"
                           required>
                    @error('nama')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="flex items-center justify-end space-x-4">
                    <a href="{{ route('admin.skpd.index') }}" 
                       class="inline-flex items-center px-6 py-3 bg-white border border-gray-200 text-gray-700 rounded-xl font-medium hover:bg-gray-50 transition-colors">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-xl font-medium hover:shadow-lg hover:scale-105 transition-all duration-300">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Data
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection