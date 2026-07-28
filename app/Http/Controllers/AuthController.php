<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Papar Login Page
    public function showLogin()
    {
        return view('auth.login');
    }

    // Papar Register Page
    public function showRegister()
    {
        return view('auth.register');
    }

    // Register User
    public function register(Request $request)
    {
       $request->validate([
    'name' => 'required|string|max:255',
    'email' => 'required|email|unique:users',
    'location' => 'required|string',
    'password' => 'required|min:6|confirmed',
]);

       User::create([
    'name' => $request->name,
    'email' => $request->email,
    'location' => $request->location,
    'password' => Hash::make($request->password),
]);

        return redirect('/login')->with('success', 'Registration Successful!');
    }

    // Login User
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        // Attempt Admin Login First
        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        // Attempt User Login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect('/dashboard');
        }

        return back()->with('error', 'Invalid Email or Password');
    }

    // Logout User
    public function logout(Request $request)
    {
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect('/login');
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login');
    }
}