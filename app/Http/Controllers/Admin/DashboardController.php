<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\Feedback;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $metrics = [
            'total_users' => User::count(),
            'total_restaurants' => Restaurant::count(),
            'total_feedbacks' => Feedback::count(),
        ];

        return view('admin.dashboard', compact('metrics'));
    }
}
