@extends('layouts.app')

@section('title', 'My Results')
@section('page-title', 'My Results 📈')
@section('page-subtitle', 'Your complete quiz attempt history')

@section('content')
<div class="space-y-6">

    {{-- Stats Summary --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="card p-5">
            <div class="text-2xl mb-2">🎯</div>
            <div class="font-display font-bold text-2xl text-white">{{ $attempts->total() }}</div>
            <div class="text-gray-400 text-sm mt-1">Total Attempts</div>
        </div>
        <div class="card p-5">
            <div class="text-2xl mb-2">✅</div>
            <div class="font-display font-bold text-2xl text-emerald-400">{{ $passedCount }}</div>
            <div class="text-gray-400 text-sm mt-1">Passed</div>
        </div>
        <div class="card p-5">
            <div class="text-2xl mb-2">📊</div>
            <div class="font-display font-bold text-2xl text-cyan-400">{{ $avgScore }}%</div>
            <div class="text-gray-400 text-sm mt-1">Avg Score</div>
        </div>
        <div class="card p-5">
            <div class="text-2xl mb-2">🏆</div>
            <div class="font-display font-bold text-2xl text-yellow-400">{{ $bestScore }}%</div>
            <div class="text-gray-400 text-sm mt-1">Best Score</div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="card p-4">
        <form method="GET" class="flex flex-wrap gap-3">
            <select name="category" class="input-field px-4 py-2 text-sm">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            <select name="status" class="input-field px-4 py-2 text-sm">
                <option value="">All Status</option>
                <option value="passed" {{ request('status') == 'passed' ? 'selected' : '' }}>✅ Passed</option>
                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>❌ Failed</option>
            </select>
            <button type="submit" class="btn-primary px-6 py-2 rounded-lg text-sm font-semibold">Filter</button>
        </form>
    </div>

    {{-- Results Table --}}
    <div class="card overflow-hidden">
        <div class="p-6 border-b border-white/08">
            <h3 class="font-display font-bold text-white text-lg">Attempt History</h3>
        </div>
        @if($attempts->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-white/08">
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase">#</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Quiz</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Score</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attempts as $i => $attempt)
                    <tr class="table-row border-b border-white/05">
                        <td class="px-6 py-4 text-gray-400 text-sm">{{ $attempts->firstItem() + $i }}</td>
                        <td class="px-6 py-4">
                            <div class="text-white text-sm font-medium">{{ $attempt->quiz->title }}</div>
                            <div class="text-gray-500 text-xs">{{ ucfirst($attempt->quiz->difficulty) }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="badge-cyan px-2 py-1 rounded-full text-xs font-medium">
                                {{ $attempt->quiz->category->icon }} {{ $attempt->quiz->category->name }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($attempt->result)
                            <span class="font-bold text-lg {{ $attempt->result->score_percentage >= 60 ? 'text-emerald-400' : 'text-red-400' }}">
                                {{ $attempt->result->score_percentage }}%
                            </span>
                            @else
                            <span class="text-gray-500 text-sm">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($attempt->result)
                            <span class="{{ $attempt->result->passed ? 'badge-green' : 'badge-red' }} px-2 py-1 rounded-full text-xs font-medium">
                                {{ $attempt->result->passed ? 'Passed' : 'Failed' }}
                            </span>
                            @else
                            <span class="badge-yellow px-2 py-1 rounded-full text-xs font-medium">Incomplete</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-400 text-sm">{{ $attempt->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4">
                            @if($attempt->result)
                            <a href="{{ route('results.show', $attempt->id) }}" class="badge-cyan px-3 py-1.5 rounded-lg text-xs font-medium hover:opacity-80 transition-opacity">
                                View →
                            </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-6">{{ $attempts->links() }}</div>
        @else
        <div class="p-16 text-center">
            <div class="text-6xl mb-4">📊</div>
            <h3 class="font-display font-bold text-white text-xl mb-2">No Results Yet</h3>
            <p class="text-gray-400 mb-6">Take your first quiz to see your results here!</p>
            <a href="{{ route('quiz.index') }}" class="btn-primary inline-block px-6 py-3 rounded-xl font-semibold">Browse Quizzes</a>
        </div>
        @endif
    </div>

</div>
@endsection
