@extends('layouts.app')

@section('title', 'Tambah Pegawai')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="glass-effect rounded-2xl shadow-xl p-6 mb-6 border border-white/20">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 mb-2">Tambah Pegawai Baru</h1>
                <p class="text-gray-600">Tambahkan data pegawai baru ke sistem</p>
            </div>
            <a href="{{ route('admin.pegawai.index') }}" 
               class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition-all duration-300 flex items-center space-x-2 hover:scale-105">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="glass-effect rounded-2xl shadow-xl p-6 border border-white/20">
        <form action="{{ route('admin.pegawai.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">NIK</label>
                    <input type="text" name="nik" value="{{ old('nik') }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                           placeholder="Masukkan 16 digit NIK" maxlength="16" minlength="16"
                           pattern="[0-9]{16}" title="NIK harus 16 digit angka">
                    @error('nik')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                    <input type="text" name="nama" value="{{ old('nama') }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                           placeholder="Masukkan nama lengkap">
                    @error('nama')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir</label>
                    <input type="date" name="tgl_lahir" value="{{ old('tgl_lahir') }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                    @error('tgl_lahir')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin</label>
                    <select name="jkel" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="L" {{ old('jkel') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jkel') === 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jkel')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">SKPD</label>
                    <select name="skpd_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                        <option value="">Pilih SKPD</option>
                        @foreach($skpds as $skpd)
                            <option value="{{ $skpd->id }}" {{ old('skpd_id') == $skpd->id ? 'selected' : '' }}>
                                {{ $skpd->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('skpd_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Telepon</label>
                    <input type="tel" name="telp" value="{{ old('telp') }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                           placeholder="Masukkan nomor telepon">
                    @error('telp')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                    <textarea name="alamat" rows="3" required
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                              placeholder="Masukkan alamat lengkap">{{ old('alamat') }}</textarea>
                    @error('alamat')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('admin.pegawai.index') }}" 
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-lg hover:from-purple-700 hover:to-pink-700 transition-all duration-300 hover:scale-105">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Pegawai
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
