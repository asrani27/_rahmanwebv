@extends('layouts.app')

@section('title', 'Laporan')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="glass-effect rounded-2xl shadow-xl p-6 mb-6 border border-white/20">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 mb-2">Laporan</h1>
                <p class="text-gray-600">Generate laporan dalam format PDF</p>
            </div>
        </div>
    </div>

    <!-- Form Laporan -->
    <div class="glass-effect rounded-2xl shadow-xl p-6 border border-white/20">
        <!-- Laporan Pegawai -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-users text-purple-600 mr-3"></i>
                Laporan Pegawai
            </h3>
            <div class="bg-gray-50 rounded-xl p-6">
                <p class="text-gray-600 mb-4">
                    Cetak laporan berisi data pegawai dengan format: NO - NAMA - SKPD
                </p>
                <form action="{{ route('admin.laporan.export.pdf') }}" method="GET" target="_blank">
                    <input type="hidden" name="type" value="pegawai">
                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-blue-600 text-white font-semibold rounded-lg hover:from-purple-700 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        <i class="fas fa-print mr-2"></i>
                        Cetak Pegawai
                    </button>
                </form>
            </div>
        </div>

        <!-- Laporan Lokasi Presensi -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-map-marker-alt text-purple-600 mr-3"></i>
                Laporan Lokasi Presensi
            </h3>
            <div class="bg-gray-50 rounded-xl p-6">
                <p class="text-gray-600 mb-4">
                    Cetak laporan berisi data lokasi presensi dengan format: NO - NAMA LOKASI - SKPD - LATITUDE - LONGITUDE
                </p>
                <form action="{{ route('admin.laporan.export.pdf') }}" method="GET" target="_blank">
                    <input type="hidden" name="type" value="lokasi">
                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-blue-600 text-white font-semibold rounded-lg hover:from-purple-700 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        <i class="fas fa-print mr-2"></i>
                        Cetak Lokasi Presensi
                    </button>
                </form>
            </div>
        </div>

        <!-- Laporan Presensi Datang per Pegawai -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-clock text-purple-600 mr-3"></i>
                Laporan Presensi Datang per Pegawai
            </h3>
            <div class="bg-gray-50 rounded-xl p-6">
                <p class="text-gray-600 mb-4">
                    Cetak laporan presensi datang pegawai berdasarkan bulan dan tahun
                </p>
                <form action="{{ route('admin.laporan.export.pdf') }}" method="GET" target="_blank">
                    <input type="hidden" name="type" value="presensi">
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
                            <select name="bulan" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" required>
                                <option value="">Pilih Bulan</option>
                                <option value="1">Januari</option>
                                <option value="2">Februari</option>
                                <option value="3">Maret</option>
                                <option value="4">April</option>
                                <option value="5">Mei</option>
                                <option value="6">Juni</option>
                                <option value="7">Juli</option>
                                <option value="8">Agustus</option>
                                <option value="9">September</option>
                                <option value="10">Oktober</option>
                                <option value="11">November</option>
                                <option value="12">Desember</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                            <select name="tahun" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" required>
                                <option value="">Pilih Tahun</option>
                                @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pegawai</label>
                            <select name="pegawai_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" required>
                                <option value="">Pilih Pegawai</option>
                                @foreach(\App\Models\Pegawai::with('skpd')->orderBy('nama')->get() as $p)
                                    <option value="{{ $p->id }}">{{ $p->nama }} - {{ $p->skpd->nama ?? '-' }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-blue-600 text-white font-semibold rounded-lg hover:from-purple-700 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        <i class="fas fa-print mr-2"></i>
                        Cetak Presensi
                    </button>
                </form>
            </div>
        </div>

        <!-- Laporan Presensi Pulang per Pegawai -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-sign-out-alt text-purple-600 mr-3"></i>
                Laporan Presensi Pulang per Pegawai
            </h3>
            <div class="bg-gray-50 rounded-xl p-6">
                <p class="text-gray-600 mb-4">
                    Cetak laporan presensi pulang pegawai berdasarkan bulan dan tahun
                </p>
                <form action="{{ route('admin.laporan.export.pdf') }}" method="GET" target="_blank">
                    <input type="hidden" name="type" value="presensi_pulang">
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
                            <select name="bulan" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" required>
                                <option value="">Pilih Bulan</option>
                                <option value="1">Januari</option>
                                <option value="2">Februari</option>
                                <option value="3">Maret</option>
                                <option value="4">April</option>
                                <option value="5">Mei</option>
                                <option value="6">Juni</option>
                                <option value="7">Juli</option>
                                <option value="8">Agustus</option>
                                <option value="9">September</option>
                                <option value="10">Oktober</option>
                                <option value="11">November</option>
                                <option value="12">Desember</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                            <select name="tahun" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" required>
                                <option value="">Pilih Tahun</option>
                                @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pegawai</label>
                            <select name="pegawai_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" required>
                                <option value="">Pilih Pegawai</option>
                                @foreach(\App\Models\Pegawai::with('skpd')->orderBy('nama')->get() as $p)
                                    <option value="{{ $p->id }}">{{ $p->nama }} - {{ $p->skpd->nama ?? '-' }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-blue-600 text-white font-semibold rounded-lg hover:from-purple-700 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        <i class="fas fa-print mr-2"></i>
                        Cetak Presensi Pulang
                    </button>
                </form>
            </div>
        </div>

        <!-- Laporan Kegiatan Pegawai -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-calendar-check text-purple-600 mr-3"></i>
                Laporan Kegiatan Pegawai
            </h3>
            <div class="bg-gray-50 rounded-xl p-6">
                <p class="text-gray-600 mb-4">
                    Cetak laporan kegiatan pegawai berdasarkan bulan dan tahun
                </p>
                <form action="{{ route('admin.laporan.export.pdf') }}" method="GET" target="_blank">
                    <input type="hidden" name="type" value="kegiatan">
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
                            <select name="bulan" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" required>
                                <option value="">Pilih Bulan</option>
                                <option value="1">Januari</option>
                                <option value="2">Februari</option>
                                <option value="3">Maret</option>
                                <option value="4">April</option>
                                <option value="5">Mei</option>
                                <option value="6">Juni</option>
                                <option value="7">Juli</option>
                                <option value="8">Agustus</option>
                                <option value="9">September</option>
                                <option value="10">Oktober</option>
                                <option value="11">November</option>
                                <option value="12">Desember</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                            <select name="tahun" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" required>
                                <option value="">Pilih Tahun</option>
                                @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pegawai</label>
                            <select name="pegawai_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" required>
                                <option value="">Pilih Pegawai</option>
                                @foreach(\App\Models\Pegawai::with('skpd')->orderBy('nama')->get() as $p)
                                    <option value="{{ $p->id }}">{{ $p->nama }} - {{ $p->skpd->nama ?? '-' }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-blue-600 text-white font-semibold rounded-lg hover:from-purple-700 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        <i class="fas fa-print mr-2"></i>
                        Cetak Kegiatan
                    </button>
                </form>
            </div>
        </div>

        <!-- Laporan Presensi Harian -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-calendar-day text-purple-600 mr-3"></i>
                Laporan Presensi Harian
            </h3>
            <div class="bg-gray-50 rounded-xl p-6">
                <p class="text-gray-600 mb-4">
                    Cetak laporan presensi harian per SKPD
                </p>
                <form action="{{ route('admin.laporan.export.pdf') }}" method="GET" target="_blank">
                    <input type="hidden" name="type" value="presensi_harian">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">SKPD</label>
                            <select name="skpd_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" required>
                                <option value="">Pilih SKPD</option>
                                @foreach(\App\Models\Skpd::orderBy('nama')->get() as $s)
                                    <option value="{{ $s->id }}">{{ $s->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                            <input type="date" name="tanggal" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" required>
                        </div>
                    </div>
                    
                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-blue-600 text-white font-semibold rounded-lg hover:from-purple-700 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        <i class="fas fa-print mr-2"></i>
                        Cetak Presensi Harian
                    </button>
                </form>
            </div>
        </div>

        <!-- Laporan Presensi Bulanan -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-calendar-alt text-purple-600 mr-3"></i>
                Laporan Presensi Bulanan
            </h3>
            <div class="bg-gray-50 rounded-xl p-6">
                <p class="text-gray-600 mb-4">
                    Cetak laporan presensi bulanan per SKPD
                </p>
                <form action="{{ route('admin.laporan.export.pdf') }}" method="GET" target="_blank">
                    <input type="hidden" name="type" value="presensi_bulanan">
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">SKPD</label>
                            <select name="skpd_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" required>
                                <option value="">Pilih SKPD</option>
                                @foreach(\App\Models\Skpd::orderBy('nama')->get() as $s)
                                    <option value="{{ $s->id }}">{{ $s->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
                            <select name="bulan" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" required>
                                <option value="">Pilih Bulan</option>
                                <option value="1">Januari</option>
                                <option value="2">Februari</option>
                                <option value="3">Maret</option>
                                <option value="4">April</option>
                                <option value="5">Mei</option>
                                <option value="6">Juni</option>
                                <option value="7">Juli</option>
                                <option value="8">Agustus</option>
                                <option value="9">September</option>
                                <option value="10">Oktober</option>
                                <option value="11">November</option>
                                <option value="12">Desember</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                            <select name="tahun" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" required>
                                <option value="">Pilih Tahun</option>
                                @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    
                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-blue-600 text-white font-semibold rounded-lg hover:from-purple-700 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        <i class="fas fa-print mr-2"></i>
                        Cetak Presensi Bulanan
                    </button>
                </form>
            </div>
        </div>

        <!-- Laporan Presensi Tahunan -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-calendar text-purple-600 mr-3"></i>
                Laporan Presensi Tahunan
            </h3>
            <div class="bg-gray-50 rounded-xl p-6">
                <p class="text-gray-600 mb-4">
                    Cetak laporan presensi tahunan per SKPD
                </p>
                <form action="{{ route('admin.laporan.export.pdf') }}" method="GET" target="_blank">
                    <input type="hidden" name="type" value="presensi_tahunan">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">SKPD</label>
                            <select name="skpd_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" required>
                                <option value="">Pilih SKPD</option>
                                @foreach(\App\Models\Skpd::orderBy('nama')->get() as $s)
                                    <option value="{{ $s->id }}">{{ $s->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                            <select name="tahun" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" required>
                                <option value="">Pilih Tahun</option>
                                @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    
                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-blue-600 text-white font-semibold rounded-lg hover:from-purple-700 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        <i class="fas fa-print mr-2"></i>
                        Cetak Presensi Tahunan
                    </button>
                </form>
            </div>
        </div>
        
    </div>

</div>
@endsection