@extends('layouts.app')

@section('title', 'Manage Questions')
@section('page-title', 'Manage Questions ❓')
@section('page-subtitle', 'Add, edit and organize quiz questions')

@section('content')
<div class="space-y-6">

    {{-- Actions + Filter --}}
    <div class="flex flex-wrap gap-3 items-center justify-between">
        <div class="flex flex-wrap gap-3">
            <form method="GET" class="flex gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search questions..."
                    class="input-field px-4 py-2 text-sm w-64">
                <select name="category" class="input-field px-4 py-2 text-sm">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                <select name="difficulty" class="input-field px-4 py-2 text-sm">
                    <option value="">All Levels</option>
                    <option value="easy" {{ request('difficulty') == 'easy' ? 'selected' : '' }}>Easy</option>
                    <option value="medium" {{ request('difficulty') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="hard" {{ request('difficulty') == 'hard' ? 'selected' : '' }}>Hard</option>
                </select>
                <button type="submit" class="btn-primary px-5 py-2 rounded-lg text-sm font-semibold">Filter</button>
            </form>
        </div>
        <a href="{{ route('admin.questions.create') }}" class="btn-primary px-5 py-2.5 rounded-xl text-sm font-semibold">+ Add Question</a>
    </div>

    {{-- Questions List --}}
    <div class="space-y-3">
        @forelse($questions as $question)
        <div class="card p-5 hover:border-white/15 transition-colors">
            <div class="flex items-start gap-4">
                <div class="flex-1">
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <span class="badge-cyan px-2 py-0.5 rounded text-xs font-medium">{{ $question->quiz->category->name ?? 'N/A' }}</span>
                        <span class="text-xs px-2 py-0.5 rounded font-medium
                            @if($question->difficulty === 'easy') badge-green
                            @elseif($question->difficulty === 'medium') badge-yellow
                            @else badge-red @endif">
                            {{ ucfirst($question->difficulty) }}
                        </span>
                        @if($question->temper_category)
                        <span class="badge-purple px-2 py-0.5 rounded text-xs font-medium">{{ $question->temperLabel() }}</span>
                        @endif
                        <span class="text-xs text-gray-500">Quiz: {{ $question->quiz->title ?? '—' }}</span>
                    </div>
                    <p class="text-white text-sm font-medium mb-3">{{ $question->question_text }}</p>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($question->options as $i => $option)
                        <div class="flex items-center gap-2 text-xs {{ $option->is_correct ? 'text-emerald-400' : 'text-gray-400' }}">
                            <span class="w-5 h-5 rounded flex items-center justify-center {{ $option->is_correct ? 'bg-emerald-500/20' : 'bg-white/05' }} flex-shrink-0 font-bold">{{ chr(65+$i) }}</span>
                            <span class="truncate">{{ $option->option_text }}</span>
                            @if($option->is_correct) <span>✓</span> @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="flex flex-col gap-2">
                    <a href="{{ route('admin.questions.edit', $question->id) }}" class="badge-yellow px-3 py-1.5 rounded-lg text-xs font-medium hover:opacity-80 text-center">Edit</a>
                    <form method="POST" action="{{ route('admin.questions.destroy', $question->id) }}" onsubmit="return confirm('Delete this question?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="badge-red px-3 py-1.5 rounded-lg text-xs font-medium hover:opacity-80 w-full">Delete</button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="card p-16 text-center">
            <div class="text-6xl mb-4">❓</div>
            <h3 class="font-display font-bold text-white text-xl mb-2">No Questions Found</h3>
            <a href="{{ route('admin.questions.create') }}" class="btn-primary inline-block mt-4 px-6 py-2.5 rounded-xl text-sm font-semibold">+ Add First Question</a>
        </div>
        @endforelse
    </div>

    <div>{{ $questions->links() }}</div>

</div>
@endsection
