@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="row min-vh-100">

        <!-- LEFT -->

        <div class="col-lg-6 d-none d-lg-flex align-items-center justify-content-center">

            <div class="left-section">

                   <img
        src="{{ asset('assets/images/logo.png') }}"
        width="200"
        height="150"
        class="mb-4">

    <h1 class="display-4 fw-bold">

                    Good Food,

                    <span class="text-success">

                        Good Mood!

                    </span>

                </h1>

                <p class="lead text-muted mt-3">

                    Let MoodBite recommend delicious food
                    based on your mood,
                    preferences and budget.

                </p>

            </div>

        </div>

        <!-- RIGHT -->

        <div class="col-lg-6 d-flex align-items-center justify-content-center">

            <div class="login-card">

   <h2 class="fw-bold mb-2">
    Create Account ✨
</h2>

<p class="text-muted mb-4">
    Join MoodBite and discover your perfect meal
</p>

    <form method="POST" action="/register">

    @csrf

    <div class="mb-3">

        <label class="form-label">Full Name</label>

        <input
            type="text"
            name="name"
            class="form-control form-control-lg"
            placeholder="Enter your full name"
            required>

    </div>

    <div class="mb-3">

        <label class="form-label">Email Address</label>

        <input
            type="email"
            name="email"
            class="form-control form-control-lg"
            placeholder="Enter your email"
            required>

    </div>

    <div class="mb-3">

    <label class="form-label">State</label>

    <select
        name="location"
        class="form-control form-control-lg"
        required>

        <option value="">-- Select State --</option>

        <option value="Johor">Johor</option>
        <option value="Kedah">Kedah</option>
        <option value="Kelantan">Kelantan</option>
        <option value="Melaka">Melaka</option>
        <option value="Negeri Sembilan">Negeri Sembilan</option>
        <option value="Pahang">Pahang</option>
        <option value="Perak">Perak</option>
        <option value="Perlis">Perlis</option>
        <option value="Pulau Pinang">Pulau Pinang</option>
        <option value="Sabah">Sabah</option>
        <option value="Sarawak">Sarawak</option>
        <option value="Selangor">Selangor</option>
        <option value="Terengganu">Terengganu</option>
        <option value="W.P. Kuala Lumpur">W.P. Kuala Lumpur</option>
        <option value="W.P. Labuan">W.P. Labuan</option>
        <option value="W.P. Putrajaya">W.P. Putrajaya</option>

    </select>

</div>

    <div class="mb-3">

        <label class="form-label">Password</label>

        <input
            type="password"
            name="password"
            class="form-control form-control-lg"
            placeholder="Create a password"
            required>

    </div>

    <div class="mb-4">

        <label class="form-label">Confirm Password</label>

        <input
            type="password"
            name="password_confirmation"
            class="form-control form-control-lg"
            placeholder="Confirm your password"
            required>

    </div>



    <button
        type="submit"
        class="btn btn-success w-100 btn-lg">

        Create Account

    </button>

</form>

<div class="text-center mt-4">

    <p class="mb-0">

        Already have an account?

        <a href="/login" class="text-success text-decoration-none fw-semibold">
            Sign In
        </a>

    </p>

</div>

</div>

@endsection