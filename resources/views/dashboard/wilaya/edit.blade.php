@extends('layouts/contentNavbarLayout')

@section('title', __('app.edit-wilaya'))

@section('vendor-style')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
  #map {
    width: 100%;
    aspect-ratio: 1;
    border-radius: 0.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    cursor: crosshair;
  }
  .leaflet-container {
    font-family: inherit;
  }
  .location-info-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1.25rem;
    border-radius: 0.5rem;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
  }
  .location-info-card .info-item {
    display: flex;
    align-items: center;
    margin-bottom: 0.5rem;
  }
  .location-info-card .info-item:last-child {
    margin-bottom: 0;
  }
  .location-info-card .info-label {
    font-weight: 600;
    margin-right: 0.5rem;
    min-width: 90px;
  }
  .location-info-card .info-value {
    font-family: 'Courier New', monospace;
    background: rgba(255,255,255,0.2);
    padding: 0.25rem 0.75rem;
    border-radius: 0.25rem;
    font-size: 0.9rem;
  }
  .name-input-group {
    transition: all 0.3s ease;
  }
  .name-input-group:hover {
    transform: translateY(-2px);
  }
  .form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
  }
</style>
@endsection

@section('content')
  <!-- Header Section -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="fw-bold mb-1">{{ __('app.edit-wilaya') }}</h4>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('app.dashboard') }}</a></li>
          <li class="breadcrumb-item"><a href="{{ route('wilayas.index') }}">{{ __('app.wilayas') }}</a></li>
          <li class="breadcrumb-item active">{{ __('app.edit') }}</li>
        </ol>
      </nav>
    </div>
    <div>
      <a href="{{ route('wilayas.index') }}" class="btn btn-label-secondary me-2">
        <i class="bx bx-arrow-back me-1"></i>{{ __('app.back') }}
      </a>
      <button type="submit" form="wilaya-form" class="btn btn-primary">
        <i class="bx bx-check me-1"></i>{{ __('app.send') }}
      </button>
    </div>
  </div>

  <form action="{{ route('wilayas.update', $wilaya->id) }}" method="POST" id="wilaya-form">
    @csrf
    @method('PATCH')
    <div class="row">
      <!-- Left Column -->
      <div class="col-xl-7 col-lg-7 mb-4">
        <div class="d-flex flex-column h-100">
          <!-- Name Inputs -->
          <div class="card mb-3 flex-grow-1">
            {{-- <div class="card-header d-flex align-items-center">
              <i class='bx bx-edit-alt me-2'></i>
              <h5 class="mb-0">{{ __('app.wilaya_details') ?? 'Wilaya Details' }}</h5>
            </div> --}}
            <div class="card-body">
              @php
                $locales = config('app.available_locales', ['ar', 'en', 'fr']);
                $localeLabels = [
                  'ar' => 'العربية',
                  'en' => 'English',
                  'fr' => 'Français',
                ];
              @endphp

              @foreach($locales as $locale)
                <div class="mb-3 name-input-group">
                  <label for="name_{{ $locale }}" class="form-label">
                    {{ __('app.name') }} ({{ $localeLabels[$locale] ?? ucfirst($locale) }})
                  </label>
                  <input 
                    type="text" 
                    name="name[{{ $locale }}]" 
                    class="form-control @error("name.{$locale}") is-invalid @enderror" 
                    id="name_{{ $locale }}"
                    placeholder="{{ __('app.enter_name') ?? 'Enter name' }} {{ strtolower($localeLabels[$locale]) }}"
                    value="{{ old("name.{$locale}", $wilaya->translate('name', $locale) ?? '') }}" 
                    required>
                  @error("name.{$locale}")
                    <span class="invalid-feedback">{{ $message }}</span>
                  @enderror
                </div>
              @endforeach
            </div>
          </div>

          <!-- Location Info -->
          <div id="location-info" class="location-info-card flex-shrink-0">
            <div class="info-item">
              <span class="info-label">{{ __('app.latitude') }}:</span>
              <span class="info-value" id="display-latitude">{{ number_format($wilaya->latitude ?? 0, 6) }}</span>
            </div>
            <div class="info-item">
              <span class="info-label">{{ __('app.longitude') }}:</span>
              <span class="info-value" id="display-longitude">{{ number_format($wilaya->longitude ?? 0, 6) }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Column - Map Section -->
      <div class="col-xl-5 col-lg-5 mb-4">
        <div class="card h-100">
          {{-- <div class="card-header d-flex align-items-center">
            <i class='bx bx-map me-2'></i>
            <h5 class="mb-0">{{ __('app.location') ?? 'Location' }}</h5>
          </div> --}}
          <div class="card-body">
            <div id="map"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Hidden inputs for coordinates -->
    <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', $wilaya->latitude ?? '') }}" required>
    <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $wilaya->longitude ?? '') }}" required>
  </form>
@endsection

@section('vendor-script')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
@endsection

@section('page-script')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Default center for Algeria (Algiers)
    const defaultLat = 36.7538;
    const defaultLng = 3.0588;
    
    // Get existing values from the database
    const existingLat = parseFloat(document.getElementById('latitude').value) || defaultLat;
    const existingLng = parseFloat(document.getElementById('longitude').value) || defaultLng;
    
    // Initialize map centered on existing location
    const map = L.map('map').setView([existingLat, existingLng], existingLat === defaultLat ? 6 : 12);
    
    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
      maxZoom: 19
    }).addTo(map);
    
    // Custom marker icon
    const customIcon = L.divIcon({
      className: 'custom-marker',
      html: '<div style="background: #667eea; width: 30px; height: 30px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3);"></div>',
      iconSize: [30, 30],
      iconAnchor: [15, 15]
    });
    
    // Create marker
    let marker = null;
    
    // Show location info
    const displayLat = document.getElementById('display-latitude');
    const displayLng = document.getElementById('display-longitude');
    
    // Add marker with existing location
    if (document.getElementById('latitude').value && document.getElementById('longitude').value) {
      marker = L.marker([existingLat, existingLng], {
        draggable: true,
        icon: customIcon
      }).addTo(map);
      
      marker.on('dragend', function(e) {
        const position = marker.getLatLng();
        updateCoordinates(position.lat, position.lng);
      });
    }
    
    // Add click event to map
    map.on('click', function(e) {
      const lat = e.latlng.lat;
      const lng = e.latlng.lng;
      
      if (marker) {
        map.removeLayer(marker);
      }
      
      marker = L.marker([lat, lng], {
        draggable: true,
        icon: customIcon
      }).addTo(map);
      
      updateCoordinates(lat, lng);
      
      // Smooth zoom to clicked location
      if (map.getZoom() < 12) {
        map.flyTo([lat, lng], 12, {
          animate: true,
          duration: 1
        });
      }
      
      marker.on('dragend', function(e) {
        const position = marker.getLatLng();
        updateCoordinates(position.lat, position.lng);
      });
    });
    
    // Update coordinates in input fields and display
    function updateCoordinates(lat, lng) {
      document.getElementById('latitude').value = lat.toFixed(8);
      document.getElementById('longitude').value = lng.toFixed(8);
      updateLocationDisplay(lat, lng);
    }
    
    function updateLocationDisplay(lat, lng) {
      displayLat.textContent = lat.toFixed(6);
      displayLng.textContent = lng.toFixed(6);
    }
    
    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
      const lat = document.getElementById('latitude').value;
      const lng = document.getElementById('longitude').value;
      
      if (!lat || !lng) {
        e.preventDefault();
        alert('{{ __("app.please_select_location") ?? "Please select a location on the map" }}');
        return false;
      }
    });
  });
</script>
@endsection