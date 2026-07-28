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

@endsection
