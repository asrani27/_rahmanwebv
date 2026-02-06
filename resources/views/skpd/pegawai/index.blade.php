@extends('layouts.app')

@section('title', 'Data Pegawai')

@section('content')
<div class=" mx-auto">
    <!-- Header -->
    <div class="glass-effect rounded-2xl shadow-xl p-6 mb-6 border border-white/20">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 mb-2">Data Pegawai</h1>
                <p class="text-gray-600">Daftar pegawai SKPD: {{ $skpd->nama }}</p>
            </div>
            <a href="{{ route('skpd.pegawai.create') }}" 
               class="bg-gradient-to-r from-green-600 to-teal-600 text-white px-6 py-3 rounded-lg hover:from-green-700 hover:to-teal-700 transition-all duration-300 flex items-center space-x-2 hover:scale-105">
                <i class="fas fa-plus"></i>
                <span>Tambah Pegawai</span>
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

    <!-- Pegawai Table -->
    <div class="glass-effect rounded-2xl shadow-xl border border-white/20 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-green-50 to-teal-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">No</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">NIK</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Tanggal Lahir</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Jenis Kelamin</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Telepon</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Status User</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pegawais as $index => $pegawai)
                    <tr class="hover:bg-green-50/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-medium text-gray-800">{{ ($pegawais->currentPage() - 1) * $pegawais->perPage() + $index + 1 }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-mono text-sm text-gray-800">{{ $pegawai->nik }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-teal-500 rounded-lg flex items-center justify-center mr-3">
                                    <span class="text-white font-bold">{{ strtoupper(substr($pegawai->nama, 0, 1)) }}</span>
                                </div>
                                <span class="font-medium text-gray-800">{{ $pegawai->nama }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $pegawai->tgl_lahir->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 rounded-full text-xs font-medium
                                @if($pegawai->jkel === 'L') bg-blue-100 text-blue-800
                                @else bg-pink-100 text-pink-800 @endif">
                                {{ $pegawai->jkel === 'L' ? 'Laki-laki' : 'Perempuan' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $pegawai->telp }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($pegawai->user)
                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 flex items-center w-fit">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Ada User
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 flex items-center w-fit">
                                    <i class="fas fa-times-circle mr-1"></i>
                                    Belum Ada User
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center space-x-2">
                                @if(!$pegawai->user)
                                <form action="{{ route('skpd.pegawai.createUser', $pegawai) }}" method="POST" 
                                      onsubmit="return confirm('Buat user login untuk pegawai ini? Password default: pegawai');">
                                    @csrf
                                    <button type="submit" 
                                            class="inline-flex items-center px-3 py-1.5 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors text-sm font-medium">
                                        <i class="fas fa-user-plus mr-1"></i>
                                        Buat User
                                    </button>
                                </form>
                                @else
                                <form action="{{ route('skpd.pegawai.resetPassword', $pegawai) }}" method="POST" 
                                      onsubmit="return confirm('Reset password user menjadi: pegawai?');">
                                    @csrf
                                    <button type="submit" 
                                            class="inline-flex items-center px-3 py-1.5 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors text-sm font-medium">
                                        <i class="fas fa-key mr-1"></i>
                                        Reset Password
                                    </button>
                                </form>
                                @endif
                                <a href="{{ route('skpd.pegawai.edit', $pegawai) }}" 
                                   class="inline-flex items-center px-3 py-1.5 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors text-sm font-medium">
                                    <i class="fas fa-edit mr-1"></i>
                                    Edit
                                </a>
                                <form action="{{ route('skpd.pegawai.destroy', $pegawai) }}" method="POST" 
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pegawai ini?');">
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
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-users text-6xl text-gray-300 mb-4"></i>
                                <p class="text-gray-500 text-lg">Belum ada data pegawai</p>
                                <p class="text-gray-400 text-sm mt-1">Data pegawai akan ditampilkan di sini</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($pegawais->hasPages())
        <div class="mt-6 flex justify-center">
            {{ $pegawais->links() }}
        </div>
        @endif
    </div>
</div>
@endsection