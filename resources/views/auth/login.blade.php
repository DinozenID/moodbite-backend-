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

            <<div class="login-card">

    <h2 class="fw-bold mb-2">
        Welcome Back 👋
    </h2>

    <p class="text-muted mb-4">
        Sign in to continue to MoodBite
    </p>

    <form method="POST" action="/login">

    @csrf

        <div class="mb-3">

            <label class="form-label">

                Email Address

            </label>

          <input
type="email"
name="email"
class="form-control form-control-lg"
placeholder="Enter your email"
required>

        </div>

        <div class="mb-3">

            <label class="form-label">

                Password

            </label>

          <input
type="password"
name="password"
class="form-control form-control-lg"
placeholder="Enter your password"
required>

        </div>

        <div class="text-end mb-4">

            <a href="#"
            class="text-success">

                Forgot Password?

            </a>

        </div>

        <button
        type="submit"
        class="btn btn-success w-100 btn-lg">

            Sign In

        </button>

    </form>

</div>

        </div>

    </div>

</div>

@endsection