@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('page-title', 'Admin Dashboard 🖥️')
@section('page-subtitle', 'Platform overview and analytics')

@section('content')
<div class="space-y-6">

    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach([
            ['👥', $totalUsers, 'Total Users', 'text-cyan-400', 'from-cyan-900/30 to-blue-900/20', 'border-cyan-500/20'],
            ['📋', $totalQuizzes, 'Total Quizzes', 'text-emerald-400', 'from-emerald-900/30 to-teal-900/20', 'border-emerald-500/20'],
            ['🎯', $totalAttempts, 'Total Attempts', 'text-purple-400', 'from-purple-900/30 to-pink-900/20', 'border-purple-500/20'],
            ['❓', $totalQuestions, 'Total Questions', 'text-yellow-400', 'from-yellow-900/30 to-orange-900/20', 'border-yellow-500/20'],
        ] as $stat)
        <div class="card p-5 bg-gradient-to-br {{ $stat[4] }} border {{ $stat[5] }}">
            <div class="text-3xl mb-3">{{ $stat[0] }}</div>
            <div class="font-display font-bold text-3xl {{ $stat[2] }}">{{ number_format($stat[1]) }}</div>
            <div class="text-gray-400 text-sm mt-1">{{ $stat[2] }}</div>
        </div>
        @endforeach
    </div>

    {{-- Charts Row --}}
    <div class="grid lg:grid-cols-2 gap-6">
        {{-- Attempts Over Time --}}
        <div class="card p-6">
            <h3 class="font-display font-bold text-white text-lg mb-4">📈 Daily Attempts (Last 7 Days)</h3>
            <canvas id="attemptsChart" height="200"></canvas>
        </div>

        {{-- Score Distribution --}}
        <div class="card p-6">
            <h3 class="font-display font-bold text-white text-lg mb-4">🎯 Score Distribution</h3>
            <canvas id="scoreChart" height="200"></canvas>
        </div>
    </div>

    {{-- Category Performance + Recent Activity --}}
    <div class="grid lg:grid-cols-2 gap-6">
        <div class="card p-6">
            <h3 class="font-display font-bold text-white text-lg mb-4">🏷️ Category Performance</h3>
            <div class="space-y-3">
                @foreach($categoryStats as $cat)
                <div class="flex items-center gap-3">
                    <div class="w-8 text-xl">{{ $cat->icon }}</div>
                    <div class="flex-1">
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-300">{{ $cat->name }}</span>
                            <span class="text-white font-bold">{{ $cat->avg_score ?? 0 }}%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ $cat->avg_score ?? 0 }}%"></div>
                        </div>
                    </div>
                    <div class="text-xs text-gray-500">{{ $cat->attempts_count }} attempts</div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="card p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-display font-bold text-white text-lg">🕐 Recent Activity</h3>
                <a href="{{ route('admin.results.index') }}" class="text-cyan-400 text-sm">View All →</a>
            </div>
            <div class="space-y-3">
                @foreach($recentAttempts as $attempt)
                <div class="flex items-center gap-3 p-3 rounded-xl bg-white/03">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold text-xs">
                        {{ strtoupper(substr($attempt->user->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-white text-sm truncate">{{ $attempt->user->name }}</div>
                        <div class="text-gray-500 text-xs truncate">{{ $attempt->quiz->title }}</div>
                    </div>
                    <div class="text-right">
                        @if($attempt->result)
                        <div class="{{ $attempt->result->score_percentage >= 60 ? 'text-emerald-400' : 'text-red-400' }} font-bold text-sm">{{ $attempt->result->score_percentage }}%</div>
                        @endif
                        <div class="text-gray-500 text-xs">{{ $attempt->created_at->diffForHumans() }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="card p-6">
        <h3 class="font-display font-bold text-white text-lg mb-4">⚡ Quick Actions</h3>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.questions.create') }}" class="btn-primary px-5 py-2.5 rounded-xl text-sm font-semibold">+ Add Question</a>
            <a href="{{ route('admin.quizzes.create') }}" class="btn-primary px-5 py-2.5 rounded-xl text-sm font-semibold">+ Add Quiz</a>
            <a href="{{ route('admin.categories.create') }}" class="btn-primary px-5 py-2.5 rounded-xl text-sm font-semibold">+ Add Category</a>
            <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 rounded-xl text-sm font-semibold border border-white/10 text-gray-300 hover:border-white/20 transition-colors">👥 Manage Users</a>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
// Attempts chart
const attCtx = document.getElementById('attemptsChart').getContext('2d');
new Chart(attCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($dailyAttempts->pluck('date')) !!},
        datasets: [{
            label: 'Attempts',
            data: {!! json_encode($dailyAttempts->pluck('count')) !!},
            backgroundColor: 'rgba(0,210,255,0.4)',
            borderColor: '#00d2ff',
            borderWidth: 1,
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { labels: { color: '#9ca3af' } } },
        scales: {
            y: { ticks: { color: '#6b7280' }, grid: { color: 'rgba(255,255,255,0.05)' } },
            x: { ticks: { color: '#6b7280' }, grid: { display: false } }
        }
    }
});

// Score distribution chart
const scoreCtx = document.getElementById('scoreChart').getContext('2d');
new Chart(scoreCtx, {
    type: 'doughnut',
    data: {
        labels: ['0-40%', '41-60%', '61-80%', '81-100%'],
        datasets: [{
            data: {!! json_encode($scoreDistribution) !!},
            backgroundColor: ['rgba(248,113,113,0.7)', 'rgba(251,191,36,0.7)', 'rgba(0,210,255,0.7)', 'rgba(0,255,135,0.7)'],
            borderColor: ['#f87171', '#fbbf24', '#00d2ff', '#00ff87'],
            borderWidth: 2,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { labels: { color: '#9ca3af' }, position: 'bottom' }
        }
    }
});
</script>
@endpush
