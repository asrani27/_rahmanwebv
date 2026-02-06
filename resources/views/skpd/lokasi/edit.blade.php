@extends('layouts.app')

@section('title', 'Edit Lokasi')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    var map;
    var marker;
    var circle;

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize map centered on existing location
        const initialLat = {{ $lokasi->lat }};
        const initialLng = {{ $lokasi->long }};
        const initialRadius = {{ $lokasi->radius }};

        map = L.map('map').setView([initialLat, initialLng], 16);

        // Add tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Add initial marker and circle
        marker = L.marker([initialLat, initialLng]).addTo(map);
        
        circle = L.circle([initialLat, initialLng], {
            color: '#00d4aa',
            fillColor: '#00d4aa',
            fillOpacity: 0.2,
            radius: initialRadius
        }).addTo(map);

        // Fit map to show the circle
        map.fitBounds(circle.getBounds());

        // Add click event to map
        map.on('click', function(e) {
            updateLocation(e.latlng);
        });

        // Update location function
        function updateLocation(latlng) {
            // Update input fields
            document.getElementById('lat').value = latlng.lat.toFixed(6);
            document.getElementById('long').value = latlng.lng.toFixed(6);

            // Remove existing marker and circle
            if (marker) {
                map.removeLayer(marker);
            }
            if (circle) {
                map.removeLayer(circle);
            }

            // Add new marker
            marker = L.marker(latlng).addTo(map);

            // Add radius circle
            updateCircle();
        }

        // Update circle when radius changes
        document.getElementById('radius').addEventListener('change', updateCircle);
        document.getElementById('radius').addEventListener('input', updateCircle);

        function updateCircle() {
            if (circle) {
                map.removeLayer(circle);
            }

            const lat = parseFloat(document.getElementById('lat').value);
            const lng = parseFloat(document.getElementById('long').value);
            const radius = parseFloat(document.getElementById('radius').value);

            if (!isNaN(lat) && !isNaN(lng) && !isNaN(radius)) {
                circle = L.circle([lat, lng], {
                    color: '#00d4aa',
                    fillColor: '#00d4aa',
                    fillOpacity: 0.2,
                    radius: radius
                }).addTo(map);

                // Fit map to show the circle
                map.fitBounds(circle.getBounds());
            }
        }
    });
</script>
@endpush

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="glass-effect rounded-2xl shadow-xl p-6 mb-6 border border-white/20">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 mb-2">Edit Lokasi Presensi</h1>
                <p class="text-gray-600">Edit lokasi presensi untuk SKPD: {{ $skpd->nama }}</p>
            </div>
            <a href="{{ route('skpd.lokasi.index') }}" 
               class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition-all duration-300 flex items-center space-x-2 hover:scale-105">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    <!-- Error Message -->
    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
        <div class="flex items-center">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <span>{{ session('error') }}</span>
        </div>
    </div>
    @endif

    <!-- Form -->
    <div class="glass-effect rounded-2xl shadow-xl p-6 border border-white/20">
        <form action="{{ route('skpd.lokasi.update', $lokasi) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lokasi</label>
                    <input type="text" name="nama" value="{{ old('nama', $lokasi->nama) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all"
                           placeholder="Masukkan nama lokasi">
                    @error('nama')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Latitude</label>
                    <input type="text" name="lat" id="lat" value="{{ old('lat', $lokasi->lat) }}" required step="any"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all"
                           placeholder="Contoh: -3.319543" readonly>
                    <p class="mt-1 text-xs text-gray-500">Klik pada peta untuk mengubah koordinat</p>
                    @error('lat')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Longitude</label>
                    <input type="text" name="long" id="long" value="{{ old('long', $lokasi->long) }}" required step="any"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all"
                           placeholder="Contoh: 114.590112" readonly>
                    <p class="mt-1 text-xs text-gray-500">Klik pada peta untuk mengubah koordinat</p>
                    @error('long')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-map-marked-alt mr-1"></i> Pilih Lokasi di Peta
                    </label>
                    <div id="map" style="height: 400px; border-radius: 12px; border: 2px solid #e5e7eb;"></div>
                    <p class="mt-2 text-xs text-gray-500">Klik pada peta untuk mengubah lokasi presensi. Lingkaran akan menunjukkan radius presensi.</p>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Radius (meter)</label>
                    <input type="number" name="radius" id="radius" value="{{ old('radius', $lokasi->radius) }}" required min="1"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all"
                           placeholder="Masukkan radius dalam meter">
                    <p class="mt-1 text-xs text-gray-500">Radius maksimal jarak untuk presensi dari lokasi ini. Lingkaran di peta akan otomatis berubah sesuai radius.</p>
                    @error('radius')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-users mr-1"></i> Pegawai yang Boleh Presensi di Lokasi Ini
                    </label>
                    <div class="border border-gray-300 rounded-lg p-4 max-h-60 overflow-y-auto">
                        @if($allPegawais->count() > 0)
                            <div class="space-y-2">
                                @foreach($allPegawais as $pegawai)
                                <label class="flex items-center p-2 hover:bg-gray-50 rounded cursor-pointer">
                                    <input type="checkbox" name="pegawai_ids[]" value="{{ $pegawai->id }}"
                                           class="w-4 h-4 text-teal-600 border-gray-300 rounded focus:ring-teal-500"
                                           {{ $assignedPegawais->contains($pegawai) ? 'checked' : '' }}>
                                    <span class="ml-3 text-sm text-gray-700">{{ $pegawai->nama }} ({{ $pegawai->nik }})</span>
                                </label>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-sm">Belum ada pegawai di SKPD ini.</p>
                        @endif
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Centang pegawai yang diizinkan melakukan presensi di lokasi ini</p>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('skpd.lokasi.index') }}" 
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-gradient-to-r from-teal-600 to-cyan-600 text-white rounded-lg hover:from-teal-700 hover:to-cyan-700 transition-all duration-300 hover:scale-105">
                    <i class="fas fa-save mr-2"></i>
                    Update Lokasi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection