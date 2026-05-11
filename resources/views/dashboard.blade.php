@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Welcome back, ' . auth()->user()->name . '! 👋')
@section('page-subtitle', 'Here is your scientific literacy overview')

@section('content')
<div class="space-y-6">

    {{-- Level Badge --}}
    <div class="card p-6 bg-gradient-to-r from-cyan-900/30 to-emerald-900/30 border-cyan-500/30">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-cyan-400 to-emerald-400 flex items-center justify-center text-2xl font-bold text-gray-900">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div>
                    <div class="text-white font-display font-bold text-xl">{{ auth()->user()->name }}</div>
                    <div class="text-gray-400 text-sm">{{ auth()->user()->institution ?? 'Student' }}</div>
                    <div class="mt-1">
                        <span class="badge-cyan px-3 py-1 rounded-full text-xs font-semibold">
                            {{ $literacyLevel }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="text-right">
                <div class="text-gray-400 text-sm">Overall Score</div>
                <div class="font-display font-bold text-4xl text-gradient">{{ $avgScore }}%</div>
                <div class="text-gray-400 text-sm">{{ $totalAttempts }} attempts</div>
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="card p-5">
            <div class="text-3xl mb-2">🎯</div>
            <div class="font-display font-bold text-2xl text-white">{{ $totalAttempts }}</div>
            <div class="text-gray-400 text-sm mt-1">Total Quizzes</div>
        </div>
        <div class="card p-5">
            <div class="text-3xl mb-2">✅</div>
            <div class="font-display font-bold text-2xl text-emerald-400">{{ $passedCount }}</div>
            <div class="text-gray-400 text-sm mt-1">Passed</div>
        </div>
        <div class="card p-5">
            <div class="text-3xl mb-2">📈</div>
            <div class="font-display font-bold text-2xl text-cyan-400">{{ $avgScore }}%</div>
            <div class="text-gray-400 text-sm mt-1">Avg Score</div>
        </div>
        <div class="card p-5">
            <div class="text-3xl mb-2">🏆</div>
            <div class="font-display font-bold text-2xl text-yellow-400">{{ $bestScore }}%</div>
            <div class="text-gray-400 text-sm mt-1">Best Score</div>
        </div>
    </div>

    {{-- Charts + Sub-scores --}}
    <div class="grid lg:grid-cols-2 gap-6">
        {{-- Scientific Temper Radar --}}
        <div class="card p-6">
            <h3 class="font-display font-bold text-white text-lg mb-4">📊 Scientific Temper Analysis</h3>
            @if($subScores['logical_thinking'] > 0 || $subScores['evidence_reasoning'] > 0)
            <canvas id="radarChart" height="250"></canvas>
            @else
            <div class="text-center py-12 text-gray-400">
                <div class="text-5xl mb-4">🧪</div>
                <p>Take your first quiz to see your analysis!</p>
                <a href="{{ route('quiz.index') }}" class="btn-primary inline-block mt-4 px-6 py-2 rounded-lg text-sm font-semibold">Start a Quiz</a>
            </div>
            @endif
        </div>

        {{-- Score History --}}
        <div class="card p-6">
            <h3 class="font-display font-bold text-white text-lg mb-4">📈 Score History</h3>
            @if($recentAttempts->count() > 0)
            <canvas id="lineChart" height="250"></canvas>
            @else
            <div class="text-center py-12 text-gray-400">
                <div class="text-5xl mb-4">📊</div>
                <p>No quiz history yet.</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Sub-Scores + Recent Attempts --}}
    <div class="grid lg:grid-cols-2 gap-6">
        {{-- Scientific Temper Sub-scores --}}
        <div class="card p-6">
            <h3 class="font-display font-bold text-white text-lg mb-6">🧠 Scientific Temper Breakdown</h3>
            <div class="space-y-4">
                @foreach([
                    ['Logical Thinking', $subScores['logical_thinking'] ?? 0, 'bg-cyan-400'],
                    ['Evidence-Based Reasoning', $subScores['evidence_reasoning'] ?? 0, 'bg-emerald-400'],
                    ['Myth vs Fact', $subScores['myth_vs_fact'] ?? 0, 'bg-purple-400'],
                    ['Problem Solving', $subScores['problem_solving'] ?? 0, 'bg-yellow-400'],
                ] as $skill)
                <div>
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-gray-300">{{ $skill[0] }}</span>
                        <span class="font-bold text-white">{{ $skill[1] }}%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill {{ $skill[2] }}" style="width: {{ $skill[1] }}%; background: none;" class="{{ $skill[2] }}"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Recent Attempts --}}
        <div class="card p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-display font-bold text-white text-lg">🕐 Recent Attempts</h3>
                <a href="{{ route('results.history') }}" class="text-cyan-400 hover:text-cyan-300 text-sm font-medium transition-colors">View All →</a>
            </div>
            @if($recentAttempts->count() > 0)
            <div class="space-y-3">
                @foreach($recentAttempts as $attempt)
                <div class="flex items-center gap-3 p-3 rounded-xl bg-white/03 hover:bg-white/05 transition-colors">
                    <div class="text-2xl">{{ $attempt->quiz->category->icon ?? '🔬' }}</div>
                    <div class="flex-1 min-w-0">
                        <div class="text-white text-sm font-medium truncate">{{ $attempt->quiz->title }}</div>
                        <div class="text-gray-500 text-xs">{{ $attempt->created_at->diffForHumans() }}</div>
                    </div>
                    <div class="text-right">
                        @if($attempt->result)
                        <div class="font-bold text-sm {{ $attempt->result->score_percentage >= 60 ? 'text-emerald-400' : 'text-red-400' }}">
                            {{ $attempt->result->score_percentage }}%
                        </div>
                        @else
                        <span class="badge-yellow px-2 py-0.5 rounded text-xs">In Progress</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8 text-gray-400">
                <p class="text-sm">No attempts yet. Take your first quiz!</p>
                <a href="{{ route('quiz.index') }}" class="btn-primary inline-block mt-4 px-6 py-2 rounded-lg text-sm font-semibold">Start Now</a>
            </div>
            @endif
        </div>
    </div>

    {{-- Recommended Topics --}}
    @if(!empty($recommendations))
    <div class="card p-6">
        <h3 class="font-display font-bold text-white text-lg mb-4">💡 Recommended for You</h3>
        <div class="grid md:grid-cols-3 gap-4">
            @foreach($recommendations as $rec)
            <a href="{{ route('quiz.show', $rec->id) }}" class="block p-4 rounded-xl bg-gradient-to-br from-cyan-900/20 to-blue-900/20 border border-cyan-500/20 hover:border-cyan-500/40 transition-all group">
                <div class="text-2xl mb-2">{{ $rec->category->icon ?? '🔬' }}</div>
                <div class="font-medium text-white text-sm group-hover:text-cyan-400 transition-colors">{{ $rec->title }}</div>
                <div class="text-gray-400 text-xs mt-1">{{ $rec->category->name }} · {{ ucfirst($rec->difficulty) }}</div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
    @if($subScores['logical_thinking'] > 0 || $subScores['evidence_reasoning'] > 0)
    // Radar Chart
    const radarCtx = document.getElementById('radarChart').getContext('2d');
    new Chart(radarCtx, {
        type: 'radar',
        data: {
            labels: ['Logical Thinking', 'Evidence Reasoning', 'Myth vs Fact', 'Problem Solving'],
            datasets: [{
                label: 'Your Scores',
                data: [
                    {{ $subScores['logical_thinking'] ?? 0 }},
                    {{ $subScores['evidence_reasoning'] ?? 0 }},
                    {{ $subScores['myth_vs_fact'] ?? 0 }},
                    {{ $subScores['problem_solving'] ?? 0 }}
                ],
                borderColor: '#00d2ff',
                backgroundColor: 'rgba(0,210,255,0.15)',
                borderWidth: 2,
                pointBackgroundColor: '#00d2ff',
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { labels: { color: '#9ca3af' } } },
            scales: {
                r: {
                    min: 0, max: 100,
                    ticks: { color: '#6b7280', backdropColor: 'transparent', stepSize: 25 },
                    grid: { color: 'rgba(255,255,255,0.08)' },
                    pointLabels: { color: '#d1d5db', font: { size: 11 } }
                }
            }
        }
    });
    @endif

    @if($recentAttempts->count() > 0)
    // Line Chart
    const lineCtx = document.getElementById('lineChart').getContext('2d');
    new Chart(lineCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($recentAttempts->reverse()->map(fn($a) => $a->created_at->format('d M'))->values()) !!},
            datasets: [{
                label: 'Score %',
                data: {!! json_encode($recentAttempts->reverse()->map(fn($a) => $a->result ? $a->result->score_percentage : null)->values()) !!},
                borderColor: '#00d2ff',
                backgroundColor: 'rgba(0,210,255,0.1)',
                borderWidth: 2,
                pointBackgroundColor: '#00d2ff',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { labels: { color: '#9ca3af' } } },
            scales: {
                y: { min: 0, max: 100, ticks: { color: '#6b7280' }, grid: { color: 'rgba(255,255,255,0.05)' } },
                x: { ticks: { color: '#6b7280' }, grid: { color: 'rgba(255,255,255,0.05)' } }
            }
        }
    });
    @endif
</script>
@endpush
