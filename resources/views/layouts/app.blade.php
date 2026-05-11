<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SciEval') — Scientific Literacy Platform</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; background: #080c1a; color: #e2e8f0; }
        .font-display { font-family: 'Space Grotesk', sans-serif; }
        .sidebar { background: #0d1224; border-right: 1px solid rgba(255,255,255,0.08); width: 260px; min-height: 100vh; position: fixed; top: 0; left: 0; z-index: 40; }
        .main-content { margin-left: 260px; min-height: 100vh; }
        .topbar { background: rgba(13,18,36,0.95); backdrop-filter: blur(20px); border-bottom: 1px solid rgba(255,255,255,0.08); position: sticky; top: 0; z-index: 30; }
        .nav-item { transition: all 0.2s; border-radius: 10px; }
        .nav-item:hover, .nav-item.active { background: rgba(0,210,255,0.1); color: #00d2ff; }
        .nav-item.active { border-left: 3px solid #00d2ff; }
        .card { background: #0d1224; border: 1px solid rgba(255,255,255,0.08); border-radius: 16px; }
        .card-gradient { background: linear-gradient(135deg, #0d1224, #111827); border: 1px solid rgba(255,255,255,0.08); border-radius: 16px; }
        .text-gradient { background: linear-gradient(135deg, #60efff 0%, #00ff87 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .badge-cyan { background: rgba(0,210,255,0.15); border: 1px solid rgba(0,210,255,0.3); color: #00d2ff; }
        .badge-green { background: rgba(0,255,135,0.15); border: 1px solid rgba(0,255,135,0.3); color: #00ff87; }
        .badge-purple { background: rgba(139,92,246,0.15); border: 1px solid rgba(139,92,246,0.3); color: #a78bfa; }
        .badge-yellow { background: rgba(251,191,36,0.15); border: 1px solid rgba(251,191,36,0.3); color: #fbbf24; }
        .badge-red { background: rgba(248,113,113,0.15); border: 1px solid rgba(248,113,113,0.3); color: #f87171; }
        .btn-primary { background: linear-gradient(135deg, #00d2ff 0%, #3a7bd5 100%); color: white; transition: all 0.2s; }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 8px 25px rgba(0,210,255,0.3); }
        .btn-danger { background: linear-gradient(135deg, #f87171, #ef4444); color: white; transition: all 0.2s; }
        .btn-danger:hover { transform: translateY(-1px); }
        .input-field { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.15); color: #e2e8f0; border-radius: 10px; transition: all 0.2s; }
        .input-field:focus { border-color: #00d2ff; outline: none; background: rgba(0,210,255,0.05); }
        .input-field option { background: #0d1224; }
        .table-row:hover { background: rgba(255,255,255,0.03); }
        .progress-bar { height: 8px; background: rgba(255,255,255,0.1); border-radius: 4px; overflow: hidden; }
        .progress-fill { height: 100%; border-radius: 4px; background: linear-gradient(90deg, #00d2ff, #00ff87); }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .main-content { margin-left: 0; }
        }
        .flash-success { background: rgba(0,255,135,0.1); border: 1px solid rgba(0,255,135,0.3); color: #00ff87; }
        .flash-error { background: rgba(248,113,113,0.1); border: 1px solid rgba(248,113,113,0.3); color: #f87171; }
        .flash-warning { background: rgba(251,191,36,0.1); border: 1px solid rgba(251,191,36,0.3); color: #fbbf24; }
    </style>
    @stack('styles')
</head>
<body>
    {{-- Sidebar --}}
    <aside class="sidebar flex flex-col" id="sidebar">
        {{-- Logo --}}
        <div class="p-6 border-b border-white/08">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-cyan-400 to-emerald-400 flex items-center justify-center font-bold text-gray-900 font-display">SE</div>
                <div>
                    <div class="font-display font-bold text-white text-lg leading-tight">SciEval</div>
                    <div class="text-xs text-gray-500">Science Evaluation Platform</div>
                </div>
            </a>
        </div>

        {{-- User Info --}}
        <div class="p-4 border-b border-white/08">
            <div class="flex items-center gap-3 p-3 rounded-xl bg-white/05">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center font-bold text-white text-sm">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="font-medium text-white text-sm truncate">{{ auth()->user()->name }}</div>
                    <div class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</div>
                </div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 p-4 space-y-1">
            @if(auth()->user()->isAdmin())
            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-3 py-2 mb-1">Admin Panel</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-item flex items-center gap-3 px-3 py-2.5 text-gray-300 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span>🖥️</span><span class="text-sm font-medium">Admin Dashboard</span>
            </a>
            <a href="{{ route('admin.users.index') }}" class="nav-item flex items-center gap-3 px-3 py-2.5 text-gray-300 {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                <span>👥</span><span class="text-sm font-medium">Manage Users</span>
            </a>
            <a href="{{ route('admin.quizzes.index') }}" class="nav-item flex items-center gap-3 px-3 py-2.5 text-gray-300 {{ request()->routeIs('admin.quizzes*') ? 'active' : '' }}">
                <span>📋</span><span class="text-sm font-medium">Manage Quizzes</span>
            </a>
            <a href="{{ route('admin.questions.index') }}" class="nav-item flex items-center gap-3 px-3 py-2.5 text-gray-300 {{ request()->routeIs('admin.questions*') ? 'active' : '' }}">
                <span>❓</span><span class="text-sm font-medium">Manage Questions</span>
            </a>
            <a href="{{ route('admin.categories.index') }}" class="nav-item flex items-center gap-3 px-3 py-2.5 text-gray-300 {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
                <span>🏷️</span><span class="text-sm font-medium">Categories</span>
            </a>
            <a href="{{ route('admin.results.index') }}" class="nav-item flex items-center gap-3 px-3 py-2.5 text-gray-300 {{ request()->routeIs('admin.results*') ? 'active' : '' }}">
                <span>📊</span><span class="text-sm font-medium">All Results</span>
            </a>
            <div class="border-t border-white/08 my-2"></div>
            @endif

            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-3 py-2 mb-1">Student</div>
            <a href="{{ route('dashboard') }}" class="nav-item flex items-center gap-3 px-3 py-2.5 text-gray-300 {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span>🏠</span><span class="text-sm font-medium">Dashboard</span>
            </a>
            <a href="{{ route('quiz.index') }}" class="nav-item flex items-center gap-3 px-3 py-2.5 text-gray-300 {{ request()->routeIs('quiz*') ? 'active' : '' }}">
                <span>🧪</span><span class="text-sm font-medium">Take Quiz</span>
            </a>
            <a href="{{ route('results.history') }}" class="nav-item flex items-center gap-3 px-3 py-2.5 text-gray-300 {{ request()->routeIs('results*') ? 'active' : '' }}">
                <span>📈</span><span class="text-sm font-medium">My Results</span>
            </a>
            <a href="{{ route('leaderboard') }}" class="nav-item flex items-center gap-3 px-3 py-2.5 text-gray-300 {{ request()->routeIs('leaderboard') ? 'active' : '' }}">
                <span>🏆</span><span class="text-sm font-medium">Leaderboard</span>
            </a>
            <a href="{{ route('profile.edit') }}" class="nav-item flex items-center gap-3 px-3 py-2.5 text-gray-300 {{ request()->routeIs('profile*') ? 'active' : '' }}">
                <span>👤</span><span class="text-sm font-medium">My Profile</span>
            </a>
        </nav>

        {{-- Logout --}}
        <div class="p-4 border-t border-white/08">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 text-gray-400 hover:text-red-400 nav-item transition-colors">
                    <span>🚪</span><span class="text-sm font-medium">Logout</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- Main Content --}}
    <div class="main-content flex flex-col">
        {{-- Topbar --}}
        <header class="topbar px-6 py-4 flex items-center justify-between">
            <div>
                <h1 class="font-display font-bold text-white text-xl">@yield('page-title', 'Dashboard')</h1>
                <p class="text-gray-400 text-sm">@yield('page-subtitle', '')</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="text-sm text-gray-400">{{ now()->format('D, d M Y') }}</div>
            </div>
        </header>

        {{-- Flash Messages --}}
        <div class="px-6 pt-4">
            @if(session('success'))
            <div class="flash-success rounded-xl px-4 py-3 mb-4 flex items-center gap-3">
                <span>✅</span> {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="flash-error rounded-xl px-4 py-3 mb-4 flex items-center gap-3">
                <span>❌</span> {{ session('error') }}
            </div>
            @endif
            @if(session('warning'))
            <div class="flash-warning rounded-xl px-4 py-3 mb-4 flex items-center gap-3">
                <span>⚠️</span> {{ session('warning') }}
            </div>
            @endif
            @if($errors->any())
            <div class="flash-error rounded-xl px-4 py-3 mb-4">
                <div class="flex items-center gap-2 mb-2"><span>❌</span><strong>Please fix the following errors:</strong></div>
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                    <li class="text-sm">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>

        {{-- Page Content --}}
        <main class="flex-1 p-6">
            @yield('content')
        </main>

        {{-- Footer --}}
        <footer class="px-6 py-4 border-t border-white/08 text-center text-gray-500 text-xs">
            © {{ date('Y') }} SciEval — Scientific Temper Evaluation Platform | Laravel 12 MVC Project
        </footer>
    </div>

    @stack('scripts')
</body>
</html>
