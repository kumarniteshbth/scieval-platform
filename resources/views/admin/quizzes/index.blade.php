@extends('layouts.app')

@section('title', 'Manage Quizzes')
@section('page-title', 'Manage Quizzes 📋')
@section('page-subtitle', 'Create and manage all platform quizzes')

@section('content')
<div class="space-y-6">

    <div class="flex flex-wrap gap-3 items-center justify-between">
        <form method="GET" class="flex gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search quizzes..."
                class="input-field px-4 py-2 text-sm w-64">
            <select name="category" class="input-field px-4 py-2 text-sm">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-primary px-5 py-2 rounded-lg text-sm font-semibold">Filter</button>
        </form>
        <a href="{{ route('admin.quizzes.create') }}" class="btn-primary px-5 py-2.5 rounded-xl text-sm font-semibold">+ Add Quiz</a>
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($quizzes as $quiz)
        <div class="card p-5 hover:border-white/15 transition-colors">
            <div class="flex items-start justify-between mb-3">
                <div class="text-3xl">{{ $quiz->category->icon ?? '🔬' }}</div>
                <span class="text-xs px-2 py-1 rounded-full font-medium
                    @if($quiz->difficulty === 'easy') badge-green
                    @elseif($quiz->difficulty === 'medium') badge-yellow
                    @else badge-red @endif">
                    {{ ucfirst($quiz->difficulty) }}
                </span>
            </div>
            <h3 class="font-display font-bold text-white text-base mb-1">{{ $quiz->title }}</h3>
            <p class="text-gray-400 text-xs mb-3">{{ Str::limit($quiz->description, 70) }}</p>
            <div class="flex gap-3 text-xs text-gray-500 mb-4">
                <span>❓ {{ $quiz->questions->count() }} Qs</span>
                <span>⏱️ {{ $quiz->time_limit }}m</span>
                <span>🎯 {{ $quiz->passing_score }}% pass</span>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.quizzes.edit', $quiz->id) }}" class="flex-1 text-center badge-yellow py-1.5 rounded-lg text-xs font-medium hover:opacity-80">Edit</a>
                <form method="POST" action="{{ route('admin.quizzes.destroy', $quiz->id) }}" onsubmit="return confirm('Delete this quiz?')" class="flex-1">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full badge-red py-1.5 rounded-lg text-xs font-medium hover:opacity-80">Delete</button>
                </form>
            </div>
        </div>
        @empty
        <div class="col-span-3 card p-16 text-center">
            <div class="text-6xl mb-4">📋</div>
            <h3 class="font-display font-bold text-white text-xl mb-2">No Quizzes Yet</h3>
            <a href="{{ route('admin.quizzes.create') }}" class="btn-primary inline-block mt-4 px-6 py-2.5 rounded-xl text-sm font-semibold">+ Create First Quiz</a>
        </div>
        @endforelse
    </div>
    <div>{{ $quizzes->links() }}</div>
</div>
@endsection
