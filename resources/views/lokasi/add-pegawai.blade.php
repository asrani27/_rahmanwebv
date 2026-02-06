@extends('layouts.app')

@section('title', 'Tambah Pegawai ke Lokasi')

@section('content')
<div class="container mx-auto">
    <!-- Header -->
    <div class="glass-effect rounded-2xl shadow-xl p-6 mb-6 border border-white/20">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 mb-2">Tambah Pegawai ke Lokasi</h1>
                <p class="text-gray-600">Lokasi: {{ $lokasi->nama }}</p>
            </div>
            <a href="{{ route('admin.lokasi.index') }}"
                class="bg-gray-100 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-200 transition-all duration-300 flex items-center space-x-2">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            <span>{{ session('success') }}</span>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Form to Add Pegawai -->
        <div class="glass-effect rounded-2xl shadow-xl p-6 border border-white/20">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-user-plus text-green-600 mr-2"></i>
                Tambah Pegawai
            </h2>

            <form action="{{ route('admin.lokasi.store-pegawai', $lokasi->id) }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Pegawai</label>
                    @if($availablePegawais->count() > 0)
                    <div class="max-h-96 overflow-y-auto border border-gray-200 rounded-lg p-4 space-y-2">
                        @foreach($availablePegawais as $pegawai)
                        <label class="flex items-center p-3 hover:bg-gray-50 rounded-lg cursor-pointer transition-colors">
                            <input type="checkbox" name="pegawai_ids[]" value="{{ $pegawai->id }}"
                                class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">{{ $pegawai->nama }}</p>
                                <p class="text-xs text-gray-500">
                                    @if($pegawai->skpd)
                                    {{ $pegawai->skpd->nama }}
                                    @else
                                    Tidak ada SKPD
                                    @endif
                                </p>
                            </div>
                        </label>
                        @endforeach
                    </div>
                    @else
                    <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-lg">
                        <p class="text-sm">
                            <i class="fas fa-info-circle mr-1"></i>
                            Tidak ada pegawai tersedia. Semua pegawai sudah ditambahkan ke lokasi ini.
                        </p>
                    </div>
                    @endif
                </div>

                @if($availablePegawais->count() > 0)
                <button type="submit"
                    class="w-full bg-gradient-to-r from-green-600 to-teal-600 text-white px-6 py-3 rounded-lg hover:from-green-700 hover:to-teal-700 transition-all duration-300 flex items-center justify-center space-x-2">
                    <i class="fas fa-plus"></i>
                    <span>Tambah Pegawai yang Dipilih</span>
                </button>
                @endif
            </form>
        </div>

        <!-- List of Assigned Pegawai -->
        <div class="glass-effect rounded-2xl shadow-xl p-6 border border-white/20">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-users text-purple-600 mr-2"></i>
                Pegawai yang Ditambahkan
                <span class="ml-2 bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded-full">
                    {{ $assignedPegawais->count() }}
                </span>
            </h2>

            @if($assignedPegawais->count() > 0)
            <div class="max-h-96 overflow-y-auto space-y-3">
                @foreach($assignedPegawais as $pegawai)
                <div class="flex items-center justify-between p-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-lg border border-purple-100">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-user text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $pegawai->nama }}</p>
                            <p class="text-xs text-gray-500">
                                @if($pegawai->skpd)
                                {{ $pegawai->skpd->nama }}
                                @else
                                Tidak ada SKPD
                                @endif
                            </p>
                        </div>
                    </div>
                    <form action="{{ route('admin.lokasi.remove-pegawai', [$lokasi->id, $pegawai->id]) }}" method="POST"
                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus pegawai ini dari lokasi?')">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center px-3 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors text-sm"
                            title="Hapus Pegawai">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-12">
                <i class="fas fa-users text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">Belum ada pegawai yang ditambahkan</p>
                <p class="text-gray-400 text-sm mt-1">Pilih pegawai dari daftar di sebelah kiri</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection