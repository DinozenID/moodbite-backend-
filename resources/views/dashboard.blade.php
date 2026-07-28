@extends('layouts.app')

@section('content')

<div class="container py-5">

    <div class="text-center mb-5">

        <h1 class="fw-bold">

            Welcome Back,
            {{ Auth::user()->name }} 👋

        </h1>

        <p class="text-muted">

            Let's find the perfect meal for you today.

        </p>

    </div>

</div>

@endsection

<div class="card shadow-lg border-0 rounded-4 p-4 mt-4">

<h3 class="fw-bold mb-4">

😊 How are you feeling today?

</h3>

</div>

<div class="row g-3">
    @php
        $moods = [
            'Happy' => '😊',
            'Tired' => '😴',
            'Sad' => '😢',
            'Excited' => '😍',
            'Angry' => '😡',
            'Stressed' => '😰'
        ];
    @endphp

    @foreach($moods as $mood => $emoji)
    <div class="col-md-4">
        <button type="button" class="btn btn-light w-100 p-3 mood-btn shadow-sm" data-mood="{{ $mood }}">
            {{ $emoji }} {{ $mood }}
        </button>
    </div>
    @endforeach
</div>

<form id="recommendForm" method="POST" action="{{ route('recommend') }}" class="d-none">
    @csrf
    <input type="hidden" name="mood" id="moodInput">
    <input type="hidden" name="latitude" id="latInput">
    <input type="hidden" name="longitude" id="lonInput">
</form>

<div class="modal fade" id="loadingModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center p-5">
                <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; h-3rem;">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <h5 class="fw-bold">Finding the perfect spot...</h5>
                <p class="text-muted mb-0">Locating you and crunching the recommendations.</p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const moodBtns = document.querySelectorAll('.mood-btn');
    const form = document.getElementById('recommendForm');
    const moodInput = document.getElementById('moodInput');
    const latInput = document.getElementById('latInput');
    const lonInput = document.getElementById('lonInput');
    
    // We assume bootstrap is loaded based on the layout
    let loadingModal = null;
    if (typeof bootstrap !== 'undefined') {
        loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
    }

    moodBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const selectedMood = this.getAttribute('data-mood');
            moodInput.value = selectedMood;

            if (loadingModal) loadingModal.show();

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        latInput.value = position.coords.latitude;
                        lonInput.value = position.coords.longitude;
                        form.submit();
                    },
                    function(error) {
                        alert("Please allow location access to find restaurants near you.");
                        if (loadingModal) loadingModal.hide();
                    },
                    { enableHighAccuracy: true, timeout: 5000 }
                );
            } else {
                alert("Geolocation is not supported by this browser.");
                if (loadingModal) loadingModal.hide();
            }
        });
    });
});
</script>