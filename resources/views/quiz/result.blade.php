@extends('layouts.app')

@section('title', 'Quiz Result')
@section('page-title', 'Quiz Result 📊')
@section('page-subtitle', $attempt->quiz->title)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Result Hero --}}
    <div class="card p-8 text-center bg-gradient-to-br
        @if($result->score_percentage >= 80) from-emerald-900/30 to-green-900/20 border-emerald-500/30
        @elseif($result->score_percentage >= 60) from-cyan-900/30 to-blue-900/20 border-cyan-500/30
        @else from-red-900/30 to-rose-900/20 border-red-500/30 @endif">

        <div class="text-6xl mb-4">
            @if($result->score_percentage >= 80) 🏆
            @elseif($result->score_percentage >= 60) ✅
            @else 📚 @endif
        </div>

        <h2 class="font-display font-bold text-white text-3xl mb-2">
            @if($result->score_percentage >= 80) Excellent!
            @elseif($result->score_percentage >= 60) Good Job!
            @else Keep Practicing! @endif
        </h2>

        <div class="font-display font-black text-7xl mb-2
            @if($result->score_percentage >= 80) text-emerald-400
            @elseif($result->score_percentage >= 60) text-cyan-400
            @else text-red-400 @endif">
            {{ $result->score_percentage }}%
        </div>

        <p class="text-gray-400 mb-6">{{ $result->score }} / {{ $result->total_questions }} correct answers</p>

        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full
            @if($result->passed) badge-green @else badge-red @endif text-sm font-semibold">
            {{ $result->passed ? '✅ PASSED' : '❌ FAILED' }}
            · Passing score: {{ $attempt->quiz->passing_score }}%
        </div>

        <div class="text-gray-400 text-sm mt-4">
            Completed in: {{ gmdate('i:s', $result->time_taken ?? 0) }} minutes
        </div>
    </div>

    {{-- Scientific Temper Scores --}}
    <div class="grid md:grid-cols-2 gap-6">
        <div class="card p-6">
            <h3 class="font-display font-bold text-white text-lg mb-6">🧠 Scientific Temper Analysis</h3>
            <div class="space-y-4">
                @foreach([
                    ['Logical Thinking', $result->logical_thinking_score, 'bg-cyan-400'],
                    ['Evidence Reasoning', $result->evidence_reasoning_score, 'bg-emerald-400'],
                    ['Myth vs Fact', $result->myth_vs_fact_score, 'bg-purple-400'],
                    ['Problem Solving', $result->problem_solving_score, 'bg-yellow-400'],
                ] as $skill)
                @php $score = $skill[1] ?? 0; @endphp
                <div>
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-gray-300">{{ $skill[0] }}</span>
                        <span class="font-bold text-white">{{ $score }}%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ $score }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="card p-6">
            <h3 class="font-display font-bold text-white text-lg mb-6">📋 Performance Summary</h3>
            <div class="space-y-3">
                <div class="flex justify-between py-2 border-b border-white/05">
                    <span class="text-gray-400">Overall Score</span>
                    <span class="text-white font-bold">{{ $result->score_percentage }}%</span>
                </div>
                <div class="flex justify-between py-2 border-b border-white/05">
                    <span class="text-gray-400">Correct Answers</span>
                    <span class="text-emerald-400 font-bold">{{ $result->score }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-white/05">
                    <span class="text-gray-400">Wrong Answers</span>
                    <span class="text-red-400 font-bold">{{ $result->total_questions - $result->score }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-white/05">
                    <span class="text-gray-400">Literacy Level</span>
                    <span class="font-bold text-cyan-400">{{ $result->literacy_level }}</span>
                </div>
                <div class="flex justify-between py-2">
                    <span class="text-gray-400">Status</span>
                    <span class="font-bold {{ $result->passed ? 'text-emerald-400' : 'text-red-400' }}">
                        {{ $result->passed ? 'PASSED' : 'FAILED' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Answer Review --}}
    <div class="card p-6">
        <h3 class="font-display font-bold text-white text-lg mb-6">🔍 Answer Review</h3>
        <div class="space-y-4">
            @foreach($answers as $i => $answer)
            <div class="p-4 rounded-xl {{ $answer->is_correct ? 'bg-emerald-500/10 border border-emerald-500/20' : 'bg-red-500/10 border border-red-500/20' }}">
                <div class="flex items-start gap-3">
                    <span class="text-lg mt-0.5">{{ $answer->is_correct ? '✅' : '❌' }}</span>
                    <div class="flex-1">
                        <div class="text-white font-medium mb-2">{{ $i + 1 }}. {{ $answer->question->question_text }}</div>
                        @if($answer->selectedOption)
                        <div class="text-sm {{ $answer->is_correct ? 'text-emerald-400' : 'text-red-400' }} mb-1">
                            Your answer: {{ $answer->selectedOption->option_text }}
                        </div>
                        @else
                        <div class="text-sm text-gray-500 mb-1">Not answered</div>
                        @endif
                        @if(!$answer->is_correct && $answer->question->correctOption)
                        <div class="text-sm text-emerald-400">
                            Correct: {{ $answer->question->correctOption->option_text }}
                        </div>
                        @endif
                        @if($answer->question->explanation)
                        <div class="text-xs text-gray-400 mt-2 bg-white/05 rounded-lg p-2">
                            💡 {{ $answer->question->explanation }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Actions --}}
    <div class="flex flex-wrap gap-4">
        <a href="{{ route('results.pdf', $attempt->id) }}" class="btn-primary flex items-center gap-2 px-6 py-3 rounded-xl font-semibold">
            📄 Download PDF Report
        </a>
        <a href="{{ route('quiz.start', $attempt->quiz_id) }}" class="px-6 py-3 rounded-xl border border-cyan-500/30 text-cyan-400 hover:border-cyan-400 transition-colors font-semibold">
            🔄 Retake Quiz
        </a>
        <a href="{{ route('quiz.index') }}" class="px-6 py-3 rounded-xl border border-white/10 text-gray-300 hover:border-white/20 transition-colors font-semibold">
            ← Browse Quizzes
        </a>
        <a href="{{ route('dashboard') }}" class="px-6 py-3 rounded-xl border border-white/10 text-gray-300 hover:border-white/20 transition-colors font-semibold">
            🏠 Dashboard
        </a>
    </div>

</div>
@endsection
