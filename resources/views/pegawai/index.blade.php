@extends('layouts.app')

@section('title', 'Riwayat Presensi')

@section('content')
<div class="container mx-auto px-4">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Riwayat Presensi</h1>
        <p class="text-gray-600">Lihat riwayat presensi Anda berdasarkan periode waktu</p>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6 card-hover border border-gray-100">
        <div class="flex flex-col md:flex-row gap-4 items-end">
            <div class="flex-1">
                <label for="bulan" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-calendar-alt mr-2 text-purple-600"></i>Bulan
                </label>
                <select id="bulan" name="bulan" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all bg-white">
                    <option value="">-- Pilih Bulan --</option>
                    <option value="1" {{ request('bulan') == 1 ? 'selected' : '' }}>Januari</option>
                    <option value="2" {{ request('bulan') == 2 ? 'selected' : '' }}>Februari</option>
                    <option value="3" {{ request('bulan') == 3 ? 'selected' : '' }}>Maret</option>
                    <option value="4" {{ request('bulan') == 4 ? 'selected' : '' }}>April</option>
                    <option value="5" {{ request('bulan') == 5 ? 'selected' : '' }}>Mei</option>
                    <option value="6" {{ request('bulan') == 6 ? 'selected' : '' }}>Juni</option>
                    <option value="7" {{ request('bulan') == 7 ? 'selected' : '' }}>Juli</option>
                    <option value="8" {{ request('bulan') == 8 ? 'selected' : '' }}>Agustus</option>
                    <option value="9" {{ request('bulan') == 9 ? 'selected' : '' }}>September</option>
                    <option value="10" {{ request('bulan') == 10 ? 'selected' : '' }}>Oktober</option>
                    <option value="11" {{ request('bulan') == 11 ? 'selected' : '' }}>November</option>
                    <option value="12" {{ request('bulan') == 12 ? 'selected' : '' }}>Desember</option>
                </select>
            </div>
            
            <div class="flex-1">
                <label for="tahun" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-calendar mr-2 text-purple-600"></i>Tahun
                </label>
                <select id="tahun" name="tahun" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all bg-white">
                    <option value="">-- Pilih Tahun --</option>
                    @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                        <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            
            <div>
                <button onclick="filterPresensi()" class="w-full md:w-auto px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-500 text-white font-semibold rounded-xl hover:from-purple-700 hover:to-pink-600 focus:ring-4 focus:ring-purple-300 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
            </div>
        </div>
    </div>

    <!-- Alert Message -->
    @if($message = session('message'))
        <div class="mb-6 p-4 rounded-xl {{ $message['type'] === 'success' ? 'bg-green-50 border-l-4 border-green-500 text-green-700' : 'bg-red-50 border-l-4 border-red-500 text-red-700' }}">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas {{ $message['type'] === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle' }}"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium">{{ $message['text'] }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Data Presensi Table -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">
                <i class="fas fa-clock mr-2 text-purple-600"></i>Data Presensi
                @if(request('bulan') && request('tahun'))
                    <span class="text-sm font-normal text-gray-500 ml-2">
                        - {{ ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'][request('bulan') - 1] }} {{ request('tahun') }}
                    </span>
                @endif
            </h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-purple-50 to-pink-50">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-calendar-day mr-2"></i>Tanggal
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-sign-in-alt mr-2"></i>Jam Datang
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-sign-out-alt mr-2"></i>Jam Pulang
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-info-circle mr-2"></i>Status
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="presensiTable">
                    @if(isset($presensi) && $presensi->count() > 0)
                        @foreach($presensi as $item)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ \Carbon\Carbon::parse($item->tanggal)->format('d F Y') }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($item->tanggal)->format('l') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-2"></i>{{ $item->jam_datang ?? '-' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($item->jam_pulang)
                                        <div class="flex items-center">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                <i class="fas fa-check-circle mr-2"></i>{{ $item->jam_pulang }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                            <i class="fas fa-minus-circle mr-2"></i>Belum pulang
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($item->jam_datang && $item->jam_pulang)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-emerald-100 text-emerald-800">
                                            <i class="fas fa-check-double mr-2"></i>Hadir Lengkap
                                        </span>
                                    @elseif($item->jam_datang)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-hourglass-half mr-2"></i>Dalam Proses
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-times-circle mr-2"></i>Tidak Hadir
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @elseif(request('bulan') && request('tahun'))
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <i class="fas fa-calendar-times text-3xl text-gray-400"></i>
                                    </div>
                                    <p class="text-gray-500 text-lg">Tidak ada data presensi untuk periode ini</p>
                                    <p class="text-gray-400 text-sm mt-2">Silakan pilih periode lain</p>
                                </div>
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mb-4 floating">
                                        <i class="fas fa-filter text-3xl text-purple-500"></i>
                                    </div>
                                    <p class="text-gray-500 text-lg">Pilih Bulan dan Tahun</p>
                                    <p class="text-gray-400 text-sm mt-2">Untuk melihat riwayat presensi Anda</p>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(isset($presensi) && $presensi instanceof \Illuminate\Pagination\LengthAwarePaginator && $presensi->hasPages())
            <div class="bg-white px-6 py-4 border-t border-gray-200">
                {{ $presensi->links() }}
            </div>
        @endif
    </div>

    <!-- Summary Cards (Optional) -->
    @if(isset($presensi) && $presensi->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
            <div class="bg-white rounded-2xl shadow-lg p-6 card-hover border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Hari Kerja</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1">{{ $presensi->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-calendar-check text-2xl text-purple-600"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl shadow-lg p-6 card-hover border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Tepat Waktu</p>
                        <p class="text-3xl font-bold text-green-600 mt-1">
                            {{ $presensi->whereNotNull('jam_datang')->where('jam_datang', '<=', '08:00:00')->count() }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-clock text-2xl text-green-600"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl shadow-lg p-6 card-hover border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Terlambat</p>
                        <p class="text-3xl font-bold text-red-600 mt-1">
                            {{ $presensi->whereNotNull('jam_datang')->where('jam_datang', '>', '08:00:00')->count() }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-2xl text-red-600"></i>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    function filterPresensi() {
        const bulan = document.getElementById('bulan').value;
        const tahun = document.getElementById('tahun').value;
        
        if (!bulan || !tahun) {
            alert('Silakan pilih bulan dan tahun terlebih dahulu!');
            return;
        }
        
        // Submit form with filters
        const url = new URL(window.location.href);
        url.searchParams.set('bulan', bulan);
        url.searchParams.set('tahun', tahun);
        window.location.href = url.toString();
    }
</script>
@endsection