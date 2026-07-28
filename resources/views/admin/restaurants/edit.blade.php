@extends('admin.layouts.app')

@section('title', 'Edit Restaurant')
@section('header', 'Edit Restaurant')

@section('content')
<div class="glass-panel rounded-2xl overflow-hidden max-w-4xl mx-auto">
    <div class="p-6 border-b border-slate-700/50 bg-slate-800/30">
        <h2 class="text-lg font-semibold text-slate-100">Edit Restaurant Details</h2>
        <p class="text-sm text-slate-400 mt-1">Update the restaurant's information and geolocation.</p>
    </div>
    
    <div class="p-6">
        @if ($errors->any())
            <div class="bg-red-500/20 border border-red-500/50 text-red-300 p-4 rounded-lg mb-6">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.restaurants.update', $restaurant->id ?? $restaurant->restaurant_id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Name -->
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-sm font-medium text-slate-300 mb-2">Restaurant Name <span class="text-red-400">*</span></label>
                    <input type="text" name="restaurant_name" value="{{ old('restaurant_name', $restaurant->restaurant_name ?? $restaurant->name) }}" required
                           class="w-full bg-slate-800/50 border border-slate-600 rounded-lg px-4 py-2 text-slate-100 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors">
                </div>

                <!-- Contact -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Contact Number</label>
                    <input type="text" name="contact_number" value="{{ old('contact_number', $restaurant->contact_number) }}"
                           class="w-full bg-slate-800/50 border border-slate-600 rounded-lg px-4 py-2 text-slate-100 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors">
                </div>

                <!-- Address -->
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-sm font-medium text-slate-300 mb-2">Address <span class="text-red-400">*</span></label>
                    <textarea name="address" rows="3" required
                              class="w-full bg-slate-800/50 border border-slate-600 rounded-lg px-4 py-2 text-slate-100 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors">{{ old('address', $restaurant->address) }}</textarea>
                </div>
            </div>

            <!-- Geolocation Section -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-300 mb-2">Location <span class="text-red-400">*</span></label>
                <p class="text-xs text-slate-400 mb-3">Click on the map to pin the restaurant's location.</p>
                
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs text-slate-400 mb-1">Latitude</label>
                        <input type="text" id="latitude" name="latitude" value="{{ old('latitude', $restaurant->latitude) }}" readonly required
                               class="w-full bg-slate-900 border border-slate-600 rounded-lg px-3 py-2 text-slate-300 text-sm opacity-70">
                    </div>
                    <div>
                        <label class="block text-xs text-slate-400 mb-1">Longitude</label>
                        <input type="text" id="longitude" name="longitude" value="{{ old('longitude', $restaurant->longitude) }}" readonly required
                               class="w-full bg-slate-900 border border-slate-600 rounded-lg px-3 py-2 text-slate-300 text-sm opacity-70">
                    </div>
                </div>

                <div id="map" class="w-full h-80 rounded-lg border border-slate-600 z-10"></div>
            </div>

            <div class="flex justify-end gap-3 border-t border-slate-700/50 pt-6">
                <a href="{{ route('admin.restaurants.index') }}" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-slate-200 text-sm font-medium rounded-lg transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors flex items-center">
                    <i data-lucide="save" class="w-4 h-4 mr-2"></i> Update Restaurant
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let latInput = document.getElementById('latitude');
        let lngInput = document.getElementById('longitude');

        let initialLat = latInput.value ? parseFloat(latInput.value) : 3.1390;
        let initialLng = lngInput.value ? parseFloat(lngInput.value) : 101.6869;
        
        let map = L.map('map').setView([initialLat, initialLng], 15);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        let marker = null;

        if (latInput.value && lngInput.value) {
            marker = L.marker([initialLat, initialLng]).addTo(map);
        }

        map.on('click', function(e) {
            let lat = e.latlng.lat;
            let lng = e.latlng.lng;

            if (marker) {
                map.removeLayer(marker);
            }
            marker = L.marker([lat, lng]).addTo(map);
            
            latInput.value = lat.toFixed(6);
            lngInput.value = lng.toFixed(6);
        });
        
        setTimeout(function(){ map.invalidateSize()}, 100);
    });
</script>
@endsection
