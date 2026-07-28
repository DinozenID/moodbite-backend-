@extends('layouts.app')

@section('content')
<div class="container py-5">
    
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            
            <div class="text-center mb-5">
                <a href="{{ url('/dashboard') }}" class="btn btn-outline-secondary btn-sm mb-3">
                    <i class="bi bi-arrow-left"></i> Back to Dashboard
                </a>
                <h2 class="fw-bold">Top 5 Restaurants for You! 🎉</h2>
                <p class="text-muted">You selected <strong>{{ $selectedFood }}</strong> while feeling <strong>{{ $moodName }}</strong>. Here are the best spots nearby:</p>
            </div>

            @if($restaurants->isEmpty())
                <div class="alert alert-warning text-center">
                    Oops! We couldn't find any restaurants matching your criteria nearby.
                </div>
            @else
                <div class="row">
                    @foreach($restaurants as $index => $restaurant)
                    <div class="col-12 mb-4">
                        <div class="card shadow-sm border-0 rounded-4 overflow-hidden {{ $index === 0 ? 'border-success border-2' : '' }}">
                            @if($index === 0)
                                <div class="bg-success text-white text-center py-2 fw-bold">
                                    🌟 Top Pick! 🌟
                                </div>
                            @endif
                            
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <span class="badge bg-warning text-dark mb-2 px-3 py-2 rounded-pill">
                                            <i class="bi bi-star-fill text-danger"></i> {{ $restaurant->rating }} ({{ $restaurant->user_ratings_total }} reviews)
                                        </span>
                                        <h4 class="fw-bold mb-1">{{ $restaurant->restaurant_name }}</h4>
                                        <p class="text-muted mb-0">
                                            <i class="bi bi-geo-alt-fill text-danger me-1"></i> 
                                            {{ number_format($restaurant->distance, 1) }} km away
                                        </p>
                                    </div>
                                    <div class="text-end text-success fs-5 fw-bold">
                                        #{{ $index + 1 }}
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="mb-1 small"><i class="bi bi-map me-2 text-primary"></i> {{ $restaurant->address ?? 'Address not available' }}</p>
                                    </div>
                                    <a href="https://www.google.com/maps/dir/?api=1&origin={{ $userLat }},{{ $userLon }}&destination={{ $restaurant->latitude }},{{ $restaurant->longitude }}&destination_place_id={{ $restaurant->place_id }}" 
                                       target="_blank" 
                                       class="btn btn-primary rounded-pill shadow-sm">
                                        <i class="bi bi-navigation me-1"></i> Go
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</div>
@endsection
