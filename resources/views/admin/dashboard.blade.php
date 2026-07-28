@extends('admin.layouts.app')

@section('title', 'Overview')
@section('header', 'Dashboard Overview')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Stat Card 1 -->
    <div class="glass-panel rounded-2xl p-6 relative overflow-hidden group">
        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
            <i data-lucide="users" class="w-24 h-24 text-indigo-400"></i>
        </div>
        <div class="relative z-10">
            <h3 class="text-slate-400 text-sm font-medium mb-1">Total Users</h3>
            <div class="text-4xl font-bold text-white mb-2">{{ $metrics['total_users'] ?? 0 }}</div>
            <div class="text-xs text-emerald-400 flex items-center">
                <i data-lucide="trending-up" class="w-3 h-3 mr-1"></i>
                <span>Active platform users</span>
            </div>
        </div>
    </div>

    <!-- Stat Card 2 -->
    <div class="glass-panel rounded-2xl p-6 relative overflow-hidden group">
        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
            <i data-lucide="store" class="w-24 h-24 text-purple-400"></i>
        </div>
        <div class="relative z-10">
            <h3 class="text-slate-400 text-sm font-medium mb-1">Restaurants</h3>
            <div class="text-4xl font-bold text-white mb-2">{{ $metrics['total_restaurants'] ?? 0 }}</div>
            <div class="text-xs text-emerald-400 flex items-center">
                <i data-lucide="trending-up" class="w-3 h-3 mr-1"></i>
                <span>Registered partners</span>
            </div>
        </div>
    </div>

    <!-- Stat Card 3 -->
    <div class="glass-panel rounded-2xl p-6 relative overflow-hidden group">
        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
            <i data-lucide="message-square" class="w-24 h-24 text-pink-400"></i>
        </div>
        <div class="relative z-10">
            <h3 class="text-slate-400 text-sm font-medium mb-1">Feedback Received</h3>
            <div class="text-4xl font-bold text-white mb-2">{{ $metrics['total_feedbacks'] ?? 0 }}</div>
            <div class="text-xs text-slate-400 flex items-center">
                <i data-lucide="activity" class="w-3 h-3 mr-1"></i>
                <span>User reviews & ratings</span>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Activity Placeholder -->
    <div class="glass-panel rounded-2xl p-6">
        <h3 class="text-lg font-semibold text-slate-100 mb-4 border-b border-slate-700/50 pb-2 flex items-center">
            <i data-lucide="clock" class="w-5 h-5 mr-2 text-indigo-400"></i>
            Recent Activity
        </h3>
        <div class="space-y-4">
            <p class="text-slate-400 text-sm text-center py-8">Activity feed will be displayed here.</p>
        </div>
    </div>

    <!-- Quick Actions Placeholder -->
    <div class="glass-panel rounded-2xl p-6">
        <h3 class="text-lg font-semibold text-slate-100 mb-4 border-b border-slate-700/50 pb-2 flex items-center">
            <i data-lucide="zap" class="w-5 h-5 mr-2 text-yellow-400"></i>
            Quick Actions
        </h3>
        <div class="grid grid-cols-2 gap-4">
            <a href="{{ route('admin.users.index') }}" class="flex flex-col items-center justify-center p-4 rounded-xl bg-slate-800/50 border border-slate-700 hover:bg-slate-700 transition-colors group">
                <div class="w-10 h-10 rounded-full bg-indigo-500/20 text-indigo-400 flex items-center justify-center mb-2 group-hover:bg-indigo-500 group-hover:text-white transition-colors">
                    <i data-lucide="user-plus" class="w-5 h-5"></i>
                </div>
                <span class="text-sm font-medium text-slate-300 group-hover:text-white">Manage Users</span>
            </a>
            <a href="{{ route('admin.restaurants.index') }}" class="flex flex-col items-center justify-center p-4 rounded-xl bg-slate-800/50 border border-slate-700 hover:bg-slate-700 transition-colors group">
                <div class="w-10 h-10 rounded-full bg-purple-500/20 text-purple-400 flex items-center justify-center mb-2 group-hover:bg-purple-500 group-hover:text-white transition-colors">
                    <i data-lucide="store" class="w-5 h-5"></i>
                </div>
                <span class="text-sm font-medium text-slate-300 group-hover:text-white">Manage Stores</span>
            </a>
        </div>
    </div>
</div>
@endsection
