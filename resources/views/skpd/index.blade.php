@extends('layouts.app')

@section('title', 'Dashboard SKPD')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="glass-effect rounded-2xl shadow-xl p-6 mb-6 border border-white/20">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Dashboard SKPD</h1>
            <p class="text-gray-600">Lihat data presensi pegawai SKPD: {{ $skpd->nama }}</p>
        </div>
    </div>

    <!-- Filter Tanggal -->
    <div class="glass-effect rounded-2xl shadow-xl p-6 mb-6 border border-white/20">
        <form action="{{ route('skpd.dashboard') }}" method="GET" class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-[250px]">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-calendar-alt mr-1"></i> Tanggal Presensi
                </label>
                <input type="date" name="tanggal" value="{{ request('tanggal') ?? old('tanggal') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all"
                       required>
            </div>
            <div>
                <button type="submit" 
                        class="bg-gradient-to-r from-teal-600 to-cyan-600 text-white px-6 py-2 rounded-lg hover:from-teal-700 hover:to-cyan-700 transition-all duration-300 hover:scale-105 flex items-center">
                    <i class="fas fa-search mr-2"></i>
                    Tampilkan Data
                </button>
            </div>
        </form>
    </div>

    <!-- Data Presensi -->
    @if(request('tanggal'))
    <div class="glass-effect rounded-2xl shadow-xl p-6 border border-white/20">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-800">
                <i class="fas fa-clipboard-list mr-2 text-teal-600"></i>
                Presensi Tanggal: {{ \Carbon\Carbon::parse(request('tanggal'))->format('d/m/Y') }}
            </h2>
            <span class="px-3 py-1 bg-teal-100 text-teal-800 rounded-full text-sm font-medium">
                Total: {{ $presensi->count() }} Pegawai
            </span>
        </div>

        @if($presensi->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-teal-50 to-cyan-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">NO</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">NIK</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">NAMA</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">JAM DATANG</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">JAM PULANG</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($presensi as $index => $item)
                    <tr class="hover:bg-teal-50/50 transition-colors border-b border-gray-100">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-medium text-gray-800">{{ $index + 1 }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-mono text-sm text-gray-800">{{ $item->nik }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-r from-teal-500 to-cyan-500 rounded-lg flex items-center justify-center mr-3">
                                    <span class="text-white font-bold">{{ strtoupper(substr($item->nama, 0, 1)) }}</span>
                                </div>
                                <span class="font-medium text-gray-800">{{ $item->nama }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($item->jam_datang)
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 flex items-center w-fit">
                                <i class="fas fa-sign-in-alt mr-1"></i>
                                {{ \Carbon\Carbon::parse($item->jam_datang)->format('H:i') }}
                            </span>
                            @else
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 flex items-center w-fit">
                                <i class="fas fa-times-circle mr-1"></i>
                                Belum Hadir
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($item->jam_pulang)
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 flex items-center w-fit">
                                <i class="fas fa-sign-out-alt mr-1"></i>
                                {{ \Carbon\Carbon::parse($item->jam_pulang)->format('H:i') }}
                            </span>
                            @else
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 flex items-center w-fit">
                                <i class="fas fa-minus-circle mr-1"></i>
                                Belum Pulang
                            </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-12">
            <i class="fas fa-clipboard-list text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 text-lg">Tidak ada data presensi pada tanggal ini</p>
            <p class="text-gray-400 text-sm mt-1">Pilih tanggal lain untuk melihat data presensi</p>
        </div>
        @endif
    </div>
    @else
    <div class="glass-effect rounded-2xl shadow-xl p-12 border border-white/20 text-center">
        <i class="fas fa-calendar-check text-6xl text-teal-300 mb-6"></i>
        <h3 class="text-xl font-semibold text-gray-800 mb-2">Pilih Tanggal Presensi</h3>
        <p class="text-gray-600">Silakan pilih tanggal di atas untuk melihat data presensi pegawai</p>
    </div>
    @endif
</div>
@endsection