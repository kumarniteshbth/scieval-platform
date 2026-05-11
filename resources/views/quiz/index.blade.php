@extends('layouts.app')

@section('title', 'Quiz Library')
@section('page-title', 'Quiz Library 🧪')
@section('page-subtitle', 'Choose a quiz to evaluate your scientific literacy')

@section('content')
<div class="space-y-6">

    {{-- Filter Bar --}}
    <div class="card p-4">
        <form method="GET" action="{{ route('quiz.index') }}" class="flex flex-wrap gap-3">
            <select name="category" class="input-field px-4 py-2 text-sm">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->icon }} {{ $cat->name }}</option>
                @endforeach
            </select>
            <select name="difficulty" class="input-field px-4 py-2 text-sm">
                <option value="">All Difficulties</option>
                <option value="easy" {{ request('difficulty') == 'easy' ? 'selected' : '' }}>🟢 Easy</option>
                <option value="medium" {{ request('difficulty') == 'medium' ? 'selected' : '' }}>🟡 Medium</option>
                <option value="hard" {{ request('difficulty') == 'hard' ? 'selected' : '' }}>🔴 Hard</option>
            </select>
            <button type="submit" class="btn-primary px-6 py-2 rounded-lg text-sm font-semibold">Filter</button>
            <a href="{{ route('quiz.index') }}" class="px-4 py-2 rounded-lg text-sm text-gray-400 hover:text-white border border-white/10 hover:border-white/20 transition-colors">Reset</a>
        </form>
    </div>

    {{-- Categories Quick Filter --}}
    <div class="flex gap-3 overflow-x-auto pb-2">
        <a href="{{ route('quiz.index') }}" class="flex-shrink-0 badge-cyan px-4 py-2 rounded-full text-sm font-medium hover:opacity-80 transition-opacity">All</a>
        @foreach($categories as $cat)
        <a href="{{ route('quiz.index', ['category' => $cat->id]) }}" class="flex-shrink-0 px-4 py-2 rounded-full text-sm font-medium border border-white/10 text-gray-300 hover:border-cyan-500/40 hover:text-cyan-400 transition-all flex-shrink-0">
            {{ $cat->icon }} {{ $cat->name }}
        </a>
        @endforeach
    </div>

    {{-- Quiz Grid --}}
    @if($quizzes->count() > 0)
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($quizzes as $quiz)
        <div class="card p-6 hover:border-cyan-500/30 transition-all group">
            <div class="flex items-start justify-between mb-4">
                <div class="text-3xl">{{ $quiz->category->icon ?? '🔬' }}</div>
                <span class="text-xs px-3 py-1 rounded-full font-medium
                    @if($quiz->difficulty === 'easy') badge-green
                    @elseif($quiz->difficulty === 'medium') badge-yellow
                    @else badge-red @endif">
                    {{ ucfirst($quiz->difficulty) }}
                </span>
            </div>
            <h3 class="font-display font-bold text-white text-lg mb-2 group-hover:text-cyan-400 transition-colors">{{ $quiz->title }}</h3>
            <p class="text-gray-400 text-sm mb-4 leading-relaxed">{{ Str::limit($quiz->description, 90) }}</p>

            <div class="flex items-center gap-4 text-xs text-gray-500 mb-4">
                <span>❓ {{ $quiz->questions->count() }} Questions</span>
                <span>⏱️ {{ $quiz->time_limit }} min</span>
                <span>🏷️ {{ $quiz->category->name }}</span>
            </div>

            @php $userAttempt = $quiz->attempts()->where('user_id', auth()->id())->latest()->first(); @endphp
            @if($userAttempt && $userAttempt->result)
            <div class="flex items-center justify-between">
                <span class="text-xs text-gray-500">Best: <span class="{{ $userAttempt->result->score_percentage >= 60 ? 'text-emerald-400' : 'text-red-400' }} font-bold">{{ $userAttempt->result->score_percentage }}%</span></span>
                <a href="{{ route('quiz.start', $quiz->id) }}" class="btn-primary px-4 py-2 rounded-lg text-sm font-semibold">Retake</a>
            </div>
            @else
            <a href="{{ route('quiz.show', $quiz->id) }}" class="btn-primary w-full block text-center py-2.5 rounded-lg text-sm font-semibold">
                Start Quiz →
            </a>
            @endif
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div>{{ $quizzes->links() }}</div>
    @else
    <div class="card p-16 text-center">
        <div class="text-6xl mb-4">🔭</div>
        <h3 class="font-display font-bold text-white text-xl mb-2">No quizzes found</h3>
        <p class="text-gray-400">Try a different category or difficulty filter</p>
        <a href="{{ route('quiz.index') }}" class="btn-primary inline-block mt-6 px-6 py-2.5 rounded-lg text-sm font-semibold">View All Quizzes</a>
    </div>
    @endif

</div>
@endsection
