@extends('layouts.app')

@section('title', 'Manajemen Pegawai')

@section('content')
<div class=" mx-auto">
    <!-- Header -->
    <div class="glass-effect rounded-2xl shadow-xl p-6 mb-6 border border-white/20">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 mb-2">Manajemen Pegawai</h1>
                <p class="text-gray-600">Kelola data pegawai sistem</p>
            </div>
            <a href="{{ route('admin.pegawai.create') }}"
                class="bg-gradient-to-r from-purple-600 to-pink-600 text-white px-6 py-3 rounded-lg hover:from-purple-700 hover:to-pink-700 transition-all duration-300 flex items-center space-x-2 hover:scale-105">
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
                <thead class="bg-gradient-to-r from-purple-50 to-pink-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">No</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">NIK
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Nama
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Tanggal
                            Lahir</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Jenis
                            Kelamin</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">SKPD
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Telepon
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pegawais as $index => $pegawai)
                    <tr class="hover:bg-purple-50/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-medium text-gray-800">{{ ($pegawais->currentPage() - 1) *
                                $pegawais->perPage() + $index + 1 }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-mono text-sm text-gray-800">{{ $pegawai->nik }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div
                                    class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg flex items-center justify-center mr-3">
                                    <span class="text-white font-bold">{{ strtoupper(substr($pegawai->nama, 0, 1))
                                        }}</span>
                                </div>
                                <span class="font-medium text-gray-800">{{ $pegawai->nama }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{
                            $pegawai->tgl_lahir->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 rounded-full text-xs font-medium
                                @if($pegawai->jkel === 'L') bg-blue-100 text-blue-800
                                @else bg-pink-100 text-pink-800 @endif">
                                {{ $pegawai->jkel === 'L' ? 'Laki-laki' : 'Perempuan' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            @if($pegawai->skpd)
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                {{ $pegawai->skpd->nama }}
                            </span>
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $pegawai->telp }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route('admin.pegawai.edit', $pegawai->id) }}"
                                    class="inline-flex items-center px-3 py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors"
                                    title="Edit Pegawai">
                                    <i class="fas fa-edit text-sm"></i>
                                </a>
                                @if($pegawai->user_id === null)
                                <form action="{{ route('admin.pegawai.create-user', $pegawai->id) }}" method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin ingin membuat user untuk pegawai ini? Password default: pegawai')">
                                    @csrf
                                    <button type="submit"
                                        class="inline-flex items-center px-3 py-2 bg-green-50 text-green-600 rounded-lg hover:bg-green-100 transition-colors"
                                        title="Create User">
                                        <i class="fas fa-user-plus text-sm"></i>
                                    </button>
                                </form>
                                @else
                                <form action="{{ route('admin.pegawai.reset-password', $pegawai->id) }}" method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin ingin mereset password user pegawai ini? Password akan direset ke: pegawai')">
                                    @csrf
                                    <button type="submit"
                                        class="inline-flex items-center px-3 py-2 bg-yellow-50 text-yellow-600 rounded-lg hover:bg-yellow-100 transition-colors"
                                        title="Reset Password">
                                        <i class="fas fa-key text-sm"></i>
                                    </button>
                                </form>
                                @endif
                                <form action="{{ route('admin.pegawai.destroy', $pegawai->id) }}" method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pegawai ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center px-3 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors"
                                        title="Hapus Pegawai">
                                        <i class="fas fa-trash text-sm"></i>
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
                                <p class="text-gray-400 text-sm mt-1">Klik tombol Tambah Pegawai untuk memulai</p>
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