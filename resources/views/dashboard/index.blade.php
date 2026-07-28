@extends('layouts.app')

@section('content')

<div class="container py-5">

    <div class="row">

        <div class="col-lg-3">

            <div class="sidebar">

                <img src="{{ asset('assets/images/logo.png') }}" width="120">
                <hr>
                    <a href="#" class="menu active">🏠 Dashboard</a>
                    <a href="#" class="menu">🍽 Food Recommendation</a>
                    <a href="#" class="menu">🕘 History</a>
                    <a href="#" class="menu">👤 Profile</a>
                    <a href="#" class="menu">💬 Feedback</a>
                    <a href="/logout" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="menu text-danger">

                        Logout

                    </a>

                    <form id="logout-form" method="POST" action="/logout">

                        @csrf

                    </form>

                </div>

                    </div>

                        <div class="col-lg-9">

                            <div class="dashboard-card">

                                <div class="hero-section">

                                    <div>

                                        <span class="hero-badge">

                                            🍃 AI Food Recommendation

                                        </span>

                                        <h1 class="hero-title">

                                            Welcome Back,

                                            <span>{{ $user->name }}</span> 👋

                                        </h1>

                                        <p class="hero-subtitle">

                                            Ready to discover delicious food based on your mood today?

                                        </p>

                                    </div>

                                </div>

                            </div>

                            <div class="dashboard-card mt-4">

                                <h4 class="mb-4">😊 How are you feeling today?</h4>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <button class="mood-card mood-select">
                                            😊
                                            Happy
                                        </button>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <button class="mood-card mood-select">
                                            😴
                                            Tired
                                        </button>

                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <button class="mood-card mood-select">
                                            😍
                                            Excited
                                        </button>
                                    </div>
                                    <div class="col-md-4 mb-3">

                                        <button class="mood-card mood-select">

                                            😡

                                            Angry

                                        </button>

                                    </div>
                                    <div class="col-md-4 mb-3">

                                        <button class="mood-card mood-select">

                                            😢

                                            Sad

                                        </button>

                                    </div>
                                    <div class="col-md-4 mb-3">

                                        <button class="mood-card mood-select">

                                            😰

                                            Stressed

                                        </button>

                                    </div>
                                </div>

                            </div>

                            <div class="dashboard-card mt-4">

                                    <h4 class="mb-4">

                                        🍜 Choose Food Preference

                                    </h4>
                                    <div class="row">
                                            <div class="col-md-4 mb-3">

                                                <button class="food-card">

                                                🍛

                                                Malay

                                                </button>

                                            </div>
                                            <div class="col-md-4 mb-3">

                                                <button class="food-card">

                                                🍕

                                                Western

                                                </button>

                                            </div>
                                            <div class="col-md-4 mb-3">

                                                <button class="food-card">

                                                🍣

                                                Japanese

                                                </button>

                                            </div>
                                            <div class="col-md-4 mb-3">

                                                <button class="food-card">

                                                🥟

                                                Chinese

                                                </button>

                                            </div>
                                            <div class="col-md-4 mb-3">

                                                <button class="food-card">

                                                🍜

                                                Korean

                                                </button>

                                            </div>
                                            <div class="col-md-4 mb-3">

                                                <button class="food-card">

                                                🍔

                                                Fast Food

                                                </button>

                                            </div>

                                    </div>

                            </div>


                            <div class="dashboard-card mt-4 ">

                                <h4 class="mb-4">
                                    💰 Select Your Budget
                                </h4>

                                <div class="row">

                                    <div class="col-md-3 mb-3">

                                        <button class="food-card budget-card">

                                        💵 RM10

                                        </button>

                                    </div>

                                    <div class="col-md-3 mb-3">

                                        <button class="food-card budget-card">

                                        💵 RM20

                                        </button>

                                    </div>

                                    <div class="col-md-3 mb-3">

                                        <button class="food-card budget-card">

                                        💵 RM30

                                        </button>

                                    </div>

                                    <div class="col-md-3 mb-3">

                                        <button class="food-card budget-card">

                                        💎 RM50+

                                        </button>

                                    </div>

                                </div>

                            </div>

                            <div class="text-center mt-5">

                                 <button id="generateBtn" class="generate-btn">

                                    ✨ Generate Recommendation

                                </button>

                            </div>

                            <div id="recommendationResult" class="mt-5 d-none">

                                <div class="recommendation-card">

                                    <h3>🤖 AI Recommendation</h3>

                                    <p class="text-muted">

                                        Generating your personalised food recommendation...

                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div> <!-- col-lg-9 -->

    </div> <!-- row -->

</div> <!-- container -->

<!-- Hidden form for Geolocation Submission -->
<form id="recommendForm" method="POST" action="{{ route('ai.recommend') }}" class="d-none">
    @csrf
    <input type="hidden" name="mood" id="submitMood">
    <input type="hidden" name="selected_food" id="submitFood">
    <input type="hidden" name="latitude" id="submitLat">
    <input type="hidden" name="longitude" id="submitLon">
</form>

<style>
    .selected-card {
        border: 2px solid #2ecc71 !important;
        background-color: #e8f8f5 !important;
    }
    .ai-food-item {
        cursor: pointer;
        transition: transform 0.2s;
    }
    .ai-food-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let selectedMood = '';
    let selectedPreference = '';
    let selectedBudget = '';

    // Handle Mood Selection
    document.querySelectorAll('.mood-select').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.mood-select').forEach(b => b.classList.remove('selected-card'));
            this.classList.add('selected-card');
            // Extract text without emoji (keep only standard characters)
            selectedMood = this.innerText.replace(/[^\x20-\x7E]/g, '').trim();
        });
    });

    // Handle Food Preference Selection
    document.querySelectorAll('.food-card:not(.budget-card)').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.food-card:not(.budget-card)').forEach(b => b.classList.remove('selected-card'));
            this.classList.add('selected-card');
            selectedPreference = this.innerText.replace(/[^\x20-\x7E]/g, '').trim();
        });
    });

    // Handle Budget Selection
    document.querySelectorAll('.budget-card').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.budget-card').forEach(b => b.classList.remove('selected-card'));
            this.classList.add('selected-card');
            selectedBudget = this.innerText.replace(/[^\x20-\x7E]/g, '').trim();
        });
    });

    const generateBtn = document.getElementById('generateBtn');
    const resultDiv = document.getElementById('recommendationResult');

    generateBtn.addEventListener('click', function() {
        if (!selectedMood || !selectedPreference || !selectedBudget) {
            alert('Please select your Mood, Food Preference, and Budget first!');
            return;
        }

        // Show loading state
        resultDiv.classList.remove('d-none');
        resultDiv.innerHTML = `
            <div class="recommendation-card text-center p-5">
                <div class="spinner-border text-success mb-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <h3>🤖 AI Recommendation</h3>
                <p class="text-muted">Generating your personalised food recommendation...</p>
            </div>
        `;

        fetch('{{ route('ai.generate') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                mood: selectedMood,
                preference: selectedPreference,
                budget: selectedBudget
            })
        })
        .then(response => response.json())
        .then(data => {
            let html = `
                <div class="recommendation-card p-4">
                    <h3 class="mb-4">🤖 AI Recommended Foods</h3>
                    <p class="text-muted mb-4">Click on a food to find Top 5 nearby restaurants!</p>
                    <div class="row">
            `;
            
            data.forEach(food => {
                html += `
                    <div class="col-md-4 mb-3">
                        <div class="card h-100 ai-food-item shadow-sm border-success border-opacity-50" data-food-name="${food.name}">
                            <div class="card-body">
                                <h5 class="card-title fw-bold text-success">${food.name}</h5>
                                <p class="card-text text-muted small">${food.description}</p>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            html += `</div></div>`;
            resultDiv.innerHTML = html;

            // Attach click listeners to new food cards
            document.querySelectorAll('.ai-food-item').forEach(item => {
                item.addEventListener('click', function() {
                    const foodName = this.getAttribute('data-food-name');
                    
                    if (navigator.geolocation) {
                        // Change UI to loading
                        this.innerHTML = '<div class="card-body text-center"><div class="spinner-border text-success" role="status"></div><p class="mt-2 mb-0">Finding nearby restaurants...</p></div>';
                        
                        navigator.geolocation.getCurrentPosition(
                            function(position) {
                                document.getElementById('submitMood').value = selectedMood;
                                document.getElementById('submitFood').value = foodName;
                                document.getElementById('submitLat').value = position.coords.latitude;
                                document.getElementById('submitLon').value = position.coords.longitude;
                                document.getElementById('recommendForm').submit();
                            },
                            function(error) {
                                alert("Please allow location access to find restaurants near you.");
                            },
                            { enableHighAccuracy: true, timeout: 5000 }
                        );
                    } else {
                        alert("Geolocation is not supported by this browser.");
                    }
                });
            });
        })
        .catch(error => {
            console.error('Error:', error);
            resultDiv.innerHTML = `
                <div class="recommendation-card text-center p-5">
                    <h3 class="text-danger">⚠️ Error</h3>
                    <p class="text-muted">Something went wrong. Please try again later.</p>
                </div>
            `;
        });
    });
});
</script>

@endsection
