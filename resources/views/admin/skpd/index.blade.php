@extends('layouts.app')

@section('title', 'Manajemen SKPD')

@section('content')
<div class="mx-auto">
    <!-- Page Header -->
    <div class="glass-effect rounded-2xl shadow-xl p-6 mb-8 border border-white/20">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 mb-2">Manajemen SKPD</h1>
                <p class="text-gray-600">Kelola data Satuan Kerja Perangkat Daerah</p>
            </div>
            <a href="{{ route('admin.skpd.create') }}"
                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-xl font-medium hover:shadow-lg hover:scale-105 transition-all duration-300">
                <i class="fas fa-plus mr-2"></i>
                Tambah SKPD
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4 flex items-center">
        <i class="fas fa-check-circle text-green-500 mr-3"></i>
        <p class="text-green-700 font-medium">{{ session('success') }}</p>
    </div>
    @endif

    <!-- Data Table -->
    <div class="glass-effect rounded-2xl shadow-xl border border-white/20 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-purple-50 to-pink-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">No</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Kode
                            SKPD</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Nama
                            SKPD</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Tanggal
                            Dibuat</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($skpds as $index => $skpd)
                    <tr class="hover:bg-purple-50/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-medium text-gray-800">{{ ($skpds->currentPage() - 1) * $skpds->perPage() +
                                $index + 1 }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                {{ $skpd->kode }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div
                                    class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-building text-white"></i>
                                </div>
                                <span class="font-medium text-gray-800">{{ $skpd->nama }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $skpd->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route('admin.skpd.edit', $skpd) }}"
                                    class="inline-flex items-center px-3 py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors"
                                    title="Edit SKPD">
                                    <i class="fas fa-edit text-sm"></i>
                                </a>
                                @if($skpd->user_id === null)
                                    <form action="{{ route('admin.skpd.create-user', $skpd) }}" method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin membuat user untuk SKPD ini? Password default: adminskpd')">
                                        @csrf
                                        <button type="submit"
                                            class="inline-flex items-center px-3 py-2 bg-green-50 text-green-600 rounded-lg hover:bg-green-100 transition-colors"
                                            title="Create User">
                                            <i class="fas fa-user-plus text-sm"></i>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.skpd.reset-password', $skpd) }}" method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin mereset password user SKPD ini? Password akan direset ke: adminskpd')">
                                        @csrf
                                        <button type="submit"
                                            class="inline-flex items-center px-3 py-2 bg-yellow-50 text-yellow-600 rounded-lg hover:bg-yellow-100 transition-colors"
                                            title="Reset Password">
                                            <i class="fas fa-key text-sm"></i>
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('admin.skpd.destroy', $skpd) }}" method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus data SKPD ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center px-3 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors"
                                        title="Hapus SKPD">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-building text-6xl text-gray-300 mb-4"></i>
                                <p class="text-gray-500 text-lg">Belum ada data SKPD</p>
                                <p class="text-gray-400 text-sm mt-1">Klik tombol Tambah SKPD untuk memulai</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($skpds->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $skpds->links() }}
        </div>
        @endif
    </div>
</div>
@endsection