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
            // Extract text without emoji
            selectedMood = this.innerText.replace(/[\u{1F600}-\u{1F64F}\u{1F300}-\u{1F5FF}\u{1F680}-\u{1F6FF}\u{1F700}-\u{1F77F}\u{1F780}-\u{1F7FF}\u{1F800}-\u{1F8FF}\u{1F900}-\u{1F9FF}\u{1FA00}-\u{1FA6F}\u{1FA70}-\u{1FAFF}\u{2600}-\u{26FF}\u{2700}-\u{27BF}\u{1F1E6}-\u{1F1FF}\u{1F191}-\u{1F251}\u{1F004}\u{1F0CF}\u{1F170}-\u{1F171}\u{1F17E}-\u{1F17F}\u{1F18E}\u{3030}\u{2B50}\u{2B55}\u{2934}-\u{2935}\u{2B05}-\u{2B07}\u{2B1B}-\u{2B1C}\u{3297}\u{3299}\u{303D}\u{00A9}\u{00AE}\u{2122}]/gu, '').trim();
        });
    });

    // Handle Food Preference Selection
    document.querySelectorAll('.food-card:not(.budget-card)').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.food-card:not(.budget-card)').forEach(b => b.classList.remove('selected-card'));
            this.classList.add('selected-card');
            selectedPreference = this.innerText.replace(/[\u{1F300}-\u{1F9FF}\u{2600}-\u{26FF}]/gu, '').trim();
        });
    });

    // Handle Budget Selection
    document.querySelectorAll('.budget-card').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.budget-card').forEach(b => b.classList.remove('selected-card'));
            this.classList.add('selected-card');
            selectedBudget = this.innerText.replace(/[\u{1F4B5}\u{1F4B8}\u{1F4B0}\u{1F4B3}\u{1F4B4}\u{1F4B6}\u{1F4B7}\u{1F4B9}\u{1F4BA}\u{1F4BB}\u{1F4BC}\u{1F4BD}\u{1F4BE}\u{1F4BF}\u{1F4C0}\u{1F4C1}\u{1F4C2}\u{1F4C3}\u{1F4C4}\u{1F4C5}\u{1F4C6}\u{1F4C7}\u{1F4C8}\u{1F4C9}\u{1F4CA}\u{1F4CB}\u{1F4CC}\u{1F4CD}\u{1F4CE}\u{1F4CF}\u{1F4D0}\u{1F4D1}\u{1F4D2}\u{1F4D3}\u{1F4D4}\u{1F4D5}\u{1F4D6}\u{1F4D7}\u{1F4D8}\u{1F4D9}\u{1F4DA}\u{1F4DB}\u{1F4DC}\u{1F4DD}\u{1F4DE}\u{1F4DF}\u{1F4E0}\u{1F4E1}\u{1F4E2}\u{1F4E3}\u{1F4E4}\u{1F4E5}\u{1F4E6}\u{1F4E7}\u{1F4E8}\u{1F4E9}\u{1F4EA}\u{1F4EB}\u{1F4EC}\u{1F4ED}\u{1F4EE}\u{1F4EF}\u{1F4F0}\u{1F4F1}\u{1F4F2}\u{1F4F3}\u{1F4F4}\u{1F4F5}\u{1F4F6}\u{1F4F7}\u{1F4F8}\u{1F4F9}\u{1F4FA}\u{1F4FB}\u{1F4FC}\u{1F4FD}\u{1F4FE}\u{1F4FF}\u{1F500}-\u{1F53D}\u{1F53E}-\u{1F5FF}\u{1F600}-\u{1F64F}\u{1F680}-\u{1F6FF}\u{2600}-\u{26FF}\u{2700}-\u{27BF}\u{1F900}-\u{1F9FF}\u{1FA00}-\u{1FAFF}\u{1F300}-\u{1F5FF}]/gu, '').trim();
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
