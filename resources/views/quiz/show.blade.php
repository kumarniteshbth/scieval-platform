@extends('layouts.app')

@section('title', $quiz->title)
@section('page-title', $quiz->title)
@section('page-subtitle', 'Review quiz details before starting')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    {{-- Quiz Info Card --}}
    <div class="card p-8">
        <div class="flex items-center gap-4 mb-6">
            <div class="text-5xl">{{ $quiz->category->icon ?? '🔬' }}</div>
            <div>
                <h1 class="font-display font-bold text-white text-2xl">{{ $quiz->title }}</h1>
                <div class="flex items-center gap-3 mt-2">
                    <span class="badge-cyan px-3 py-1 rounded-full text-xs font-medium">{{ $quiz->category->name }}</span>
                    <span class="text-xs px-3 py-1 rounded-full font-medium
                        @if($quiz->difficulty === 'easy') badge-green
                        @elseif($quiz->difficulty === 'medium') badge-yellow
                        @else badge-red @endif">
                        {{ ucfirst($quiz->difficulty) }}
                    </span>
                </div>
            </div>
        </div>

        <p class="text-gray-300 text-base leading-relaxed mb-8">{{ $quiz->description }}</p>

        <div class="grid grid-cols-3 gap-4 mb-8">
            <div class="text-center p-4 rounded-xl bg-white/05">
                <div class="text-2xl font-display font-bold text-cyan-400">{{ $quiz->questions->count() }}</div>
                <div class="text-gray-400 text-xs mt-1">Questions</div>
            </div>
            <div class="text-center p-4 rounded-xl bg-white/05">
                <div class="text-2xl font-display font-bold text-yellow-400">{{ $quiz->time_limit }}</div>
                <div class="text-gray-400 text-xs mt-1">Minutes</div>
            </div>
            <div class="text-center p-4 rounded-xl bg-white/05">
                <div class="text-2xl font-display font-bold text-purple-400">{{ $quiz->passing_score }}%</div>
                <div class="text-gray-400 text-xs mt-1">Pass Mark</div>
            </div>
        </div>

        <div class="bg-yellow-500/10 border border-yellow-500/30 rounded-xl p-4 mb-8">
            <div class="flex items-start gap-3">
                <span class="text-yellow-400 text-xl">⚠️</span>
                <div>
                    <div class="font-semibold text-yellow-400 mb-1">Before you start:</div>
                    <ul class="text-gray-300 text-sm space-y-1">
                        <li>• The timer starts as soon as you click "Start Quiz"</li>
                        <li>• You can navigate between questions freely</li>
                        <li>• Unanswered questions will be counted as incorrect</li>
                        <li>• Your session is saved — you can resume if disconnected</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="flex gap-4">
            <a href="{{ route('quiz.start', $quiz->id) }}" class="btn-primary flex-1 text-center py-4 rounded-xl text-lg font-display font-bold">
                🚀 Start Quiz
            </a>
            <a href="{{ route('quiz.index') }}" class="px-6 py-4 rounded-xl border border-white/10 text-gray-300 hover:border-white/20 transition-colors text-center">
                ← Back
            </a>
        </div>
    </div>

    {{-- Previous Attempts --}}
    @if($previousAttempts->count() > 0)
    <div class="card p-6">
        <h3 class="font-display font-bold text-white text-lg mb-4">📜 Previous Attempts</h3>
        <div class="space-y-3">
            @foreach($previousAttempts as $attempt)
            <div class="flex items-center justify-between p-3 rounded-xl bg-white/03">
                <div>
                    <div class="text-white text-sm font-medium">Attempt #{{ $loop->iteration }}</div>
                    <div class="text-gray-500 text-xs">{{ $attempt->created_at->format('d M Y, H:i') }}</div>
                </div>
                <div class="flex items-center gap-3">
                    @if($attempt->result)
                    <span class="font-bold text-lg {{ $attempt->result->score_percentage >= 60 ? 'text-emerald-400' : 'text-red-400' }}">
                        {{ $attempt->result->score_percentage }}%
                    </span>
                    <a href="{{ route('results.show', $attempt->id) }}" class="badge-cyan px-3 py-1 rounded-full text-xs font-medium hover:opacity-80">View</a>
                    @else
                    <span class="badge-yellow px-2 py-1 rounded text-xs">Incomplete</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection
