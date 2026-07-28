<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Moodbite</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #0f172a; /* Slate 900 */
            color: #f8fafc; /* Slate 50 */
        }
        .glass-panel {
            background: rgba(30, 41, 59, 0.7); /* Slate 800 with opacity */
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .sidebar-link {
            transition: all 0.2s ease;
        }
        .sidebar-link:hover, .sidebar-link.active {
            background: rgba(99, 102, 241, 0.15); /* Indigo 500 */
            color: #818cf8; /* Indigo 400 */
            border-right: 3px solid #818cf8;
        }
    </style>
</head>
<body class="flex h-screen overflow-hidden antialiased selection:bg-indigo-500/30">

    <!-- Sidebar -->
    <aside class="w-64 glass-panel border-r border-slate-700/50 flex flex-col hidden md:flex z-20">
        <div class="h-16 flex items-center px-6 border-b border-slate-700/50">
            <div class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-indigo-400 to-purple-400">
                Moodbite
            </div>
            <span class="ml-2 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wider text-indigo-300 bg-indigo-500/20 rounded-full">Admin</span>
        </div>
        
        <nav class="flex-1 overflow-y-auto py-4 space-y-1">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link flex items-center px-6 py-3 text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'active' : 'text-slate-400' }}">
                <i data-lucide="layout-dashboard" class="w-5 h-5 mr-3"></i>
                Dashboard
            </a>
            
            <div class="px-6 pt-4 pb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Management</div>
            
            <a href="{{ route('admin.users.index') }}" class="sidebar-link flex items-center px-6 py-3 text-sm font-medium {{ request()->routeIs('admin.users.*') ? 'active' : 'text-slate-400' }}">
                <i data-lucide="users" class="w-5 h-5 mr-3"></i>
                Users
            </a>
            <a href="{{ route('admin.restaurants.index') }}" class="sidebar-link flex items-center px-6 py-3 text-sm font-medium {{ request()->routeIs('admin.restaurants.*') ? 'active' : 'text-slate-400' }}">
                <i data-lucide="store" class="w-5 h-5 mr-3"></i>
                Restaurants
            </a>
            <a href="{{ route('admin.foods.index') }}" class="sidebar-link flex items-center px-6 py-3 text-sm font-medium {{ request()->routeIs('admin.foods.*') ? 'active' : 'text-slate-400' }}">
                <i data-lucide="pizza" class="w-5 h-5 mr-3"></i>
                Foods
            </a>
            <a href="{{ route('admin.moods.index') }}" class="sidebar-link flex items-center px-6 py-3 text-sm font-medium {{ request()->routeIs('admin.moods.*') ? 'active' : 'text-slate-400' }}">
                <i data-lucide="smile" class="w-5 h-5 mr-3"></i>
                Moods
            </a>
            <a href="{{ route('admin.recommendations.index') }}" class="sidebar-link flex items-center px-6 py-3 text-sm font-medium {{ request()->routeIs('admin.recommendations.*') ? 'active' : 'text-slate-400' }}">
                <i data-lucide="thumbs-up" class="w-5 h-5 mr-3"></i>
                Recommendations
            </a>
            
            <div class="px-6 pt-4 pb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Insights</div>
            
            <a href="{{ route('admin.feedbacks.index') }}" class="sidebar-link flex items-center px-6 py-3 text-sm font-medium {{ request()->routeIs('admin.feedbacks.*') ? 'active' : 'text-slate-400' }}">
                <i data-lucide="message-square" class="w-5 h-5 mr-3"></i>
                Feedback
            </a>
            <a href="{{ route('admin.histories.index') }}" class="sidebar-link flex items-center px-6 py-3 text-sm font-medium {{ request()->routeIs('admin.histories.*') ? 'active' : 'text-slate-400' }}">
                <i data-lucide="history" class="w-5 h-5 mr-3"></i>
                Histories
            </a>
        </nav>
        
        <div class="p-4 border-t border-slate-700/50">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center px-4 py-2 text-sm font-medium text-slate-300 bg-slate-800/50 border border-slate-700 hover:bg-slate-700 hover:text-white rounded-lg transition-colors">
                    <i data-lucide="log-out" class="w-4 h-4 mr-2"></i>
                    Sign Out
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-screen overflow-hidden relative">
        <!-- Top Header -->
        <header class="h-16 glass-panel border-b border-slate-700/50 flex items-center justify-between px-6 z-10">
            <div class="flex items-center md:hidden">
                <button class="text-slate-400 hover:text-white">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
            </div>
            
            <div class="flex-1 md:flex-none">
                <h1 class="text-lg font-semibold text-slate-100 hidden md:block">@yield('header')</h1>
            </div>

            <div class="flex items-center space-x-4">
                <div class="relative">
                    <button class="flex items-center text-sm font-medium text-slate-300 hover:text-white focus:outline-none transition-colors">
                        <img class="h-8 w-8 rounded-full bg-slate-800 object-cover border border-slate-600" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::guard('admin')->user()->username ?? 'Admin') }}&background=6366f1&color=fff" alt="Admin avatar">
                        <span class="ml-2 hidden sm:block">{{ Auth::guard('admin')->user()->username ?? 'Administrator' }}</span>
                        <i data-lucide="chevron-down" class="w-4 h-4 ml-1 text-slate-500"></i>
                    </button>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <div class="flex-1 overflow-y-auto p-6 relative">
            
            <!-- Abstract background blobs -->
            <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
                <div class="absolute -top-[20%] -left-[10%] w-[50%] h-[50%] rounded-full bg-indigo-900/20 blur-[100px]"></div>
                <div class="absolute top-[40%] -right-[10%] w-[40%] h-[40%] rounded-full bg-purple-900/20 blur-[100px]"></div>
            </div>
            
            @if(session('success'))
                <div class="mb-6 px-4 py-3 rounded-lg border border-emerald-500/30 bg-emerald-500/10 text-emerald-400 flex items-center">
                    <i data-lucide="check-circle" class="w-5 h-5 mr-3"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 px-4 py-3 rounded-lg border border-rose-500/30 bg-rose-500/10 text-rose-400 flex items-center">
                    <i data-lucide="alert-circle" class="w-5 h-5 mr-3"></i>
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
            
        </div>
    </main>

    <script>
        lucide.createIcons();
    </script>
    @stack('scripts')
</body>
</html>
