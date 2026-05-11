@extends('layouts.app')

@section('title', isset($quiz) ? 'Edit Quiz' : 'Create Quiz')
@section('page-title', isset($quiz) ? 'Edit Quiz ✏️' : 'Create New Quiz ➕')

@section('content')
<div class="max-w-2xl mx-auto">
    <form method="POST" action="{{ isset($quiz) ? route('admin.quizzes.update', $quiz->id) : route('admin.quizzes.store') }}" class="space-y-6">
        @csrf
        @if(isset($quiz)) @method('PUT') @endif

        <div class="card p-6 space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Quiz Title *</label>
                <input type="text" name="title" value="{{ old('title', $quiz->title ?? '') }}" required
                    class="input-field w-full px-4 py-3" placeholder="e.g. Fundamentals of Physics">
                @error('title') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Description *</label>
                <textarea name="description" rows="3" required class="input-field w-full px-4 py-3 resize-none"
                    placeholder="Brief description of what this quiz covers">{{ old('description', $quiz->description ?? '') }}</textarea>
                @error('description') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Category *</label>
                    <select name="category_id" required class="input-field w-full px-4 py-3">
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $quiz->category_id ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->icon }} {{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Difficulty *</label>
                    <select name="difficulty" required class="input-field w-full px-4 py-3">
                        @foreach(['easy', 'medium', 'hard'] as $d)
                        <option value="{{ $d }}" {{ old('difficulty', $quiz->difficulty ?? 'medium') == $d ? 'selected' : '' }}>{{ ucfirst($d) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Time Limit (minutes) *</label>
                    <input type="number" name="time_limit" value="{{ old('time_limit', $quiz->time_limit ?? 15) }}" min="5" max="120" required
                        class="input-field w-full px-4 py-3">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Passing Score (%) *</label>
                    <input type="number" name="passing_score" value="{{ old('passing_score', $quiz->passing_score ?? 60) }}" min="1" max="100" required
                        class="input-field w-full px-4 py-3">
                </div>
            </div>
            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_active" id="is_active" value="1"
                    {{ old('is_active', $quiz->is_active ?? true) ? 'checked' : '' }} class="w-4 h-4 accent-cyan-400">
                <label for="is_active" class="text-gray-300 text-sm font-medium">Active (visible to students)</label>
            </div>
        </div>

        <div class="flex gap-4">
            <button type="submit" class="btn-primary flex-1 py-3 rounded-xl font-display font-bold">
                {{ isset($quiz) ? '💾 Update Quiz' : '➕ Create Quiz' }}
            </button>
            <a href="{{ route('admin.quizzes.index') }}" class="px-6 py-3 rounded-xl border border-white/10 text-gray-300 hover:border-white/20 transition-colors font-semibold">Cancel</a>
        </div>
    </form>
</div>
@endsection
