@extends('layouts.app')

@section('title', 'Lokasi Presensi')

@section('content')
<div class=" mx-auto">
    <!-- Header -->
    <div class="glass-effect rounded-2xl shadow-xl p-6 mb-6 border border-white/20">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 mb-2">Lokasi Presensi</h1>
                <p class="text-gray-600">Daftar lokasi presensi untuk SKPD: {{ $skpd->nama }}</p>
            </div>
            <a href="{{ route('skpd.lokasi.create') }}" 
               class="bg-gradient-to-r from-teal-600 to-cyan-600 text-white px-6 py-3 rounded-lg hover:from-teal-700 hover:to-cyan-700 transition-all duration-300 flex items-center space-x-2 hover:scale-105">
                <i class="fas fa-plus"></i>
                <span>Tambah Lokasi</span>
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

    <!-- Lokasi Table -->
    <div class="glass-effect rounded-2xl shadow-xl border border-white/20 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-teal-50 to-cyan-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">No</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Nama Lokasi</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Latitude</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Longitude</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Radius</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Jumlah Pegawai</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lokasis as $index => $lokasi)
                    <tr class="hover:bg-teal-50/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-medium text-gray-800">{{ ($lokasis->currentPage() - 1) * $lokasis->perPage() + $index + 1 }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-r from-teal-500 to-cyan-500 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-map-marker-alt text-white"></i>
                                </div>
                                <span class="font-medium text-gray-800">{{ $lokasi->nama }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $lokasi->lat }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $lokasi->long }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-teal-100 text-teal-800">
                                {{ $lokasi->radius }} meter
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-cyan-100 text-cyan-800">
                                {{ $lokasi->pegawais->count() }} pegawai
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route('skpd.lokasi.edit', $lokasi) }}" 
                                   class="inline-flex items-center px-3 py-1.5 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors text-sm font-medium">
                                    <i class="fas fa-edit mr-1"></i>
                                    Edit
                                </a>
                                <form action="{{ route('skpd.lokasi.destroy', $lokasi) }}" method="POST" 
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus lokasi presensi ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center px-3 py-1.5 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors text-sm font-medium">
                                        <i class="fas fa-trash mr-1"></i>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-map-marker-alt text-6xl text-gray-300 mb-4"></i>
                                <p class="text-gray-500 text-lg">Belum ada lokasi presensi</p>
                                <p class="text-gray-400 text-sm mt-1">Klik tombol "Tambah Lokasi" untuk menambahkan lokasi presensi</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($lokasis->hasPages())
        <div class="mt-6 flex justify-center">
            {{ $lokasis->links() }}
        </div>
        @endif
    </div>
</div>
@endsection