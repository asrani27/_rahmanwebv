@extends('layouts.app')

@section('title', 'Edit Lokasi Presensi')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map {
        height: 400px;
        width: 100%;
        border-radius: 8px;
        border: 2px solid #e5e7eb;
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-6">
        <a href="{{ route('admin.lokasi.index') }}"
            class="text-purple-600 hover:text-purple-800 flex items-center mb-4">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Edit Lokasi Presensi</h1>
        <p class="text-gray-600">Edit data lokasi presensi</p>
    </div>

    <!-- Form -->
    <div class="glass-effect rounded-2xl shadow-xl p-6 border border-white/20">
        <form action="{{ route('admin.lokasi.update', $lokasi->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Error Messages -->
            @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-circle mr-2 mt-1"></i>
                    <div class="flex-1">
                        <p class="font-bold mb-1">Ada kesalahan dalam input:</p>
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- SKPD -->
                <div>
                    <label class="block text-gray-700 font-medium mb-2">
                        SKPD <span class="text-red-500">*</span>
                    </label>
                    <select name="skpd_id" id="skpd_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('skpd_id') border-red-500 @enderror">
                        <option value="">Pilih SKPD</option>
                        @foreach($skpds as $skpd)
                        <option value="{{ $skpd->id }}" {{ old('skpd_id', $lokasi->skpd_id) == $skpd->id ? 'selected' :
                            '' }}>
                            {{ $skpd->nama }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Nama -->
                <div>
                    <label class="block text-gray-700 font-medium mb-2">
                        Nama Lokasi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama', $lokasi->nama) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('nama') border-red-500 @enderror"
                        placeholder="Contoh: Kantor Walikota">
                </div>

                <!-- Radius -->
                <div>
                    <label class="block text-gray-700 font-medium mb-2">
                        Radius (meter) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="radius" id="radius" value="{{ old('radius', $lokasi->radius) }}" min="1"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('radius') border-red-500 @enderror"
                        placeholder="100">
                </div>
            </div>

            <!-- Map Section -->
            <div class="mb-6">
                <label class="block text-gray-700 font-medium mb-2">
                    Pilih Lokasi di Peta <span class="text-red-500">*</span>
                </label>
                <p class="text-gray-500 text-sm mb-3">Klik pada peta untuk menandai lokasi presensi atau geser marker
                </p>
                <div id="map"></div>
            </div>

            <!-- Latitude & Longitude (Readonly - auto-filled from map) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">
                        Latitude <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="lat" id="lat" value="{{ old('lat', $lokasi->lat) }}" readonly
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('lat') border-red-500 @enderror"
                        placeholder="Klik pada peta">
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">
                        Longitude <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="long" id="long" value="{{ old('long', $lokasi->long) }}" readonly
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('long') border-red-500 @enderror"
                        placeholder="Klik pada peta">
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('admin.lokasi.index') }}"
                    class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit"
                    class="bg-gradient-to-r from-purple-600 to-pink-600 text-white px-6 py-2 rounded-lg hover:from-purple-700 hover:to-pink-700 transition-all duration-300 hover:scale-105">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // Get current lat/lng values
    var currentLat = parseFloat('{{ old('lat', $lokasi->lat) }}');
    var currentLng = parseFloat('{{ old('long', $lokasi->long) }}');

    // Initialize map centered on current location or Banjarmasin with maximum zoom
    var map = L.map('map').setView([currentLat || -3.3167, currentLng || 114.5900], currentLat && currentLng ? 15 : 13);

    // Add Google Satellite layer
    L.tileLayer('https://mt1.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
        attribution: '&copy; Google Maps',
        maxZoom: 20
    }).addTo(map);

    // Marker variable
    var marker = null;

    // Function to add/update marker
    function addMarker(lat, lng) {
        // Remove existing marker if any
        if (marker !== null) {
            map.removeLayer(marker);
        }

        // Add new marker
        marker = L.marker([lat, lng], {
            draggable: true
        }).addTo(map);

        // Update lat/lng inputs when marker is dragged
        marker.on('dragend', function(event) {
            var position = marker.getLatLng();
            document.getElementById('lat').value = position.lat.toFixed(6);
            document.getElementById('long').value = position.lng.toFixed(6);
        });
    }

    // Add initial marker
    if (!isNaN(currentLat) && !isNaN(currentLng)) {
        addMarker(currentLat, currentLng);
    }

    // Click event on map
    map.on('click', function(e) {
        var lat = e.latlng.lat;
        var lng = e.latlng.lng;

        // Update inputs
        document.getElementById('lat').value = lat.toFixed(6);
        document.getElementById('long').value = lng.toFixed(6);

        // Add marker
        addMarker(lat, lng);
    });
</script>
@endpush