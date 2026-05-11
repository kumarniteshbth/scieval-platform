@extends('layouts.app')

@section('title', isset($question) ? 'Edit Question' : 'Add Question')
@section('page-title', isset($question) ? 'Edit Question ✏️' : 'Add New Question ➕')
@section('page-subtitle', 'Create or update a quiz question with 4 options')

@section('content')
<div class="max-w-3xl mx-auto">
    <form method="POST" action="{{ isset($question) ? route('admin.questions.update', $question->id) : route('admin.questions.store') }}" class="space-y-6">
        @csrf
        @if(isset($question)) @method('PUT') @endif

        <div class="card p-6 space-y-5">
            <h3 class="font-display font-bold text-white text-lg border-b border-white/08 pb-4">Question Details</h3>

            {{-- Quiz --}}
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Quiz *</label>
                <select name="quiz_id" required class="input-field w-full px-4 py-3">
                    <option value="">Select Quiz</option>
                    @foreach($quizzes as $quiz)
                    <option value="{{ $quiz->id }}" {{ (old('quiz_id', $question->quiz_id ?? '') == $quiz->id) ? 'selected' : '' }}>
                        {{ $quiz->category->name ?? '' }} — {{ $quiz->title }}
                    </option>
                    @endforeach
                </select>
                @error('quiz_id') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Question Text --}}
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Question Text *</label>
                <textarea name="question_text" rows="3" required
                    class="input-field w-full px-4 py-3 resize-none"
                    placeholder="Enter the question here...">{{ old('question_text', $question->question_text ?? '') }}</textarea>
                @error('question_text') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Difficulty + Temper Category --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Difficulty *</label>
                    <select name="difficulty" required class="input-field w-full px-4 py-3">
                        @foreach(['easy', 'medium', 'hard'] as $d)
                        <option value="{{ $d }}" {{ old('difficulty', $question->difficulty ?? 'medium') == $d ? 'selected' : '' }}>{{ ucfirst($d) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Temper Category *</label>
                    <select name="temper_category" required class="input-field w-full px-4 py-3">
                        <option value="logical_thinking" {{ old('temper_category', $question->temper_category ?? '') == 'logical_thinking' ? 'selected' : '' }}>Logical Thinking</option>
                        <option value="evidence_reasoning" {{ old('temper_category', $question->temper_category ?? '') == 'evidence_reasoning' ? 'selected' : '' }}>Evidence Reasoning</option>
                        <option value="myth_vs_fact" {{ old('temper_category', $question->temper_category ?? '') == 'myth_vs_fact' ? 'selected' : '' }}>Myth vs Fact</option>
                        <option value="problem_solving" {{ old('temper_category', $question->temper_category ?? '') == 'problem_solving' ? 'selected' : '' }}>Problem Solving</option>
                    </select>
                </div>
            </div>

            {{-- Explanation --}}
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Explanation (shown after answer)</label>
                <textarea name="explanation" rows="2"
                    class="input-field w-full px-4 py-3 resize-none"
                    placeholder="Explain why the correct answer is correct...">{{ old('explanation', $question->explanation ?? '') }}</textarea>
            </div>
        </div>

        {{-- Options --}}
        <div class="card p-6">
            <h3 class="font-display font-bold text-white text-lg border-b border-white/08 pb-4 mb-5">Answer Options</h3>
            <div class="space-y-3">
                @php $opts = isset($question) ? $question->options->values() : collect(); @endphp
                @for($i = 0; $i < 4; $i++)
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-white/08 flex items-center justify-center font-bold text-gray-400 flex-shrink-0">{{ chr(65+$i) }}</div>
                    <input type="text" name="options[{{ $i }}][text]"
                        value="{{ old("options.$i.text", $opts->get($i)?->option_text ?? '') }}"
                        required placeholder="Option {{ chr(65+$i) }}"
                        class="input-field flex-1 px-4 py-3 text-sm">
                    <label class="flex items-center gap-2 text-sm text-gray-300 cursor-pointer whitespace-nowrap">
                        <input type="radio" name="correct_option" value="{{ $i }}"
                            class="accent-cyan-400"
                            {{ old('correct_option', $opts->search(fn($o) => $o->is_correct) ?: 0) == $i ? 'checked' : '' }}>
                        Correct
                    </label>
                </div>
                @endfor
                @error('correct_option') <p class="text-red-400 text-xs mt-2">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex gap-4">
            <button type="submit" class="btn-primary flex-1 py-3 rounded-xl font-display font-bold text-lg">
                {{ isset($question) ? '💾 Update Question' : '➕ Add Question' }}
            </button>
            <a href="{{ route('admin.questions.index') }}" class="px-6 py-3 rounded-xl border border-white/10 text-gray-300 hover:border-white/20 transition-colors font-semibold">Cancel</a>
        </div>
    </form>
</div>
@endsection
