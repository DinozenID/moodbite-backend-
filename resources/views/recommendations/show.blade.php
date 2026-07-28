@extends('layouts.app')

@section('content')
<div class="container py-5">
    
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            
            <div class="text-center mb-4">
                <a href="{{ url('/dashboard') }}" class="btn btn-outline-secondary btn-sm mb-3">
                    <i class="bi bi-arrow-left"></i> Back to Dashboard
                </a>
                <h2 class="fw-bold">We found the perfect spot! 🎉</h2>
                <p class="text-muted">Since you're feeling <strong>{{ $moodName }}</strong>, how about this?</p>
            </div>

            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <!-- Food Image Placeholder -->
                <div class="bg-light p-5 text-center border-bottom">
                    <i class="bi bi-shop text-primary opacity-50" style="font-size: 5rem;"></i>
                </div>
                
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <span class="badge bg-primary bg-opacity-10 text-primary mb-2 px-3 py-2 rounded-pill">
                                {{ $food->food_category ?? 'Recommended' }}
                            </span>
                            <h3 class="fw-bold mb-1">{{ $food->restaurant_name }}</h3>
                            <p class="text-muted mb-0">
                                <i class="bi bi-geo-alt-fill text-danger me-1"></i> 
                                {{ number_format($food->distance, 1) }} km away
                            </p>
                        </div>
                        <div class="text-end">
                            <div class="fs-4 fw-bold text-success">RM {{ number_format($food->price ?? 0, 2) }}</div>
                            <div class="text-muted small">Estimated</div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <h5 class="fw-bold mb-3">Suggested Dish</h5>
                    <div class="d-flex align-items-center mb-4 p-3 bg-light rounded-3">
                        <div class="me-3">
                            <div class="bg-white p-2 rounded shadow-sm">
                                <i class="bi bi-cup-hot text-warning fs-4"></i>
                            </div>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">{{ $food->food_name ?? $food->name }}</h6>
                            <p class="text-muted small mb-0">{{ $food->food_description ?? 'A delicious treat to match your mood.' }}</p>
                        </div>
                    </div>

                    <h5 class="fw-bold mb-3">Location & Contact</h5>
                    <p class="mb-2"><i class="bi bi-map me-2 text-primary"></i> {{ $food->address ?? 'Address not available' }}</p>
                    <p class="mb-4"><i class="bi bi-telephone me-2 text-primary"></i> {{ $food->contact_number ?? 'Contact not available' }}</p>

                    <div class="d-grid gap-2 mt-4">
                        <a href="https://www.google.com/maps/dir/?api=1&origin={{ $userLat }},{{ $userLon }}&destination={{ $food->latitude }},{{ $food->longitude }}" 
                           target="_blank" 
                           class="btn btn-primary btn-lg fw-bold rounded-pill shadow-sm">
                            <i class="bi bi-navigation me-2"></i> Get Directions
                        </a>
                        <a href="{{ url('/dashboard') }}" class="btn btn-light btn-lg fw-bold rounded-pill text-muted mt-2">
                            Try Another Mood
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
