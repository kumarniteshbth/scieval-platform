<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $quiz->title }} — SciEval Quiz</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #080c1a; color: #e2e8f0; min-height: 100vh; }
        .font-display { font-family: 'Space Grotesk', sans-serif; }
        .quiz-container { max-width: 800px; margin: 0 auto; padding: 20px; }
        .option-btn {
            background: rgba(255,255,255,0.05);
            border: 2px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            padding: 16px 20px;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 12px;
            text-align: left;
            width: 100%;
            color: #e2e8f0;
            font-size: 0.95rem;
        }
        .option-btn:hover { border-color: rgba(0,210,255,0.4); background: rgba(0,210,255,0.05); }
        .option-btn.selected { border-color: #00d2ff; background: rgba(0,210,255,0.15); }
        .option-letter { width: 32px; height: 32px; border-radius: 8px; background: rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.85rem; flex-shrink: 0; }
        .option-btn.selected .option-letter { background: #00d2ff; color: #080c1a; }
        #timer-box {
            background: rgba(0,210,255,0.1);
            border: 1px solid rgba(0,210,255,0.3);
            border-radius: 12px;
            padding: 8px 16px;
        }
        #timer-box.warning { background: rgba(251,191,36,0.15); border-color: rgba(251,191,36,0.4); }
        #timer-box.danger { background: rgba(248,113,113,0.15); border-color: rgba(248,113,113,0.4); animation: blink 1s infinite; }
        @keyframes blink { 50% { opacity: 0.7; } }
        .q-dot { width: 28px; height: 28px; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: 600; cursor: pointer; transition: all 0.2s; }
        .q-dot.current { background: #00d2ff; color: #080c1a; }
        .q-dot.answered { background: rgba(0,255,135,0.3); border: 1px solid rgba(0,255,135,0.5); color: #00ff87; }
        .q-dot.unanswered { background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.1); color: #6b7280; }
        .btn-nav { padding: 12px 24px; border-radius: 10px; font-weight: 600; font-size: 0.9rem; transition: all 0.2s; }
        .btn-next { background: linear-gradient(135deg, #00d2ff, #3a7bd5); color: white; }
        .btn-next:hover { transform: translateY(-1px); box-shadow: 0 8px 20px rgba(0,210,255,0.3); }
        .btn-prev { background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.1); color: #9ca3af; }
        .btn-prev:hover { background: rgba(255,255,255,0.12); color: white; }
        .btn-submit { background: linear-gradient(135deg, #00ff87, #00d2ff); color: #080c1a; }
        .btn-submit:hover { transform: translateY(-1px); box-shadow: 0 8px 20px rgba(0,255,135,0.3); }
    </style>
</head>
<body>
    <div class="quiz-container py-8">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <div class="text-gray-400 text-sm">{{ $quiz->category->icon }} {{ $quiz->category->name }}</div>
                <h1 class="font-display font-bold text-white text-xl">{{ $quiz->title }}</h1>
            </div>
            <div id="timer-box" class="text-center">
                <div class="text-xs text-gray-400 mb-0.5">Time Left</div>
                <div id="timer" class="font-display font-bold text-xl text-cyan-400">{{ sprintf('%02d:%02d', floor($timeLeft / 60), $timeLeft % 60) }}</div>
            </div>
        </div>

        {{-- Progress --}}
        <div class="mb-6">
            <div class="flex justify-between text-xs text-gray-400 mb-2">
                <span>Question {{ $currentIndex + 1 }} of {{ $totalQuestions }}</span>
                <span>{{ round((($answeredCount) / $totalQuestions) * 100) }}% Answered</span>
            </div>
            <div class="h-2 bg-white/10 rounded-full overflow-hidden">
                <div class="h-full rounded-full bg-gradient-to-r from-cyan-500 to-emerald-500 transition-all duration-500"
                     style="width: {{ ($answeredCount / $totalQuestions) * 100 }}%"></div>
            </div>
        </div>

        {{-- Question Nav Dots --}}
        <div class="flex flex-wrap gap-2 mb-6 p-4 rounded-xl bg-white/03">
            @for($i = 0; $i < $totalQuestions; $i++)
            <button
                onclick="navigateTo({{ $i }})"
                class="q-dot {{ $i == $currentIndex ? 'current' : (in_array($i, $answeredQuestions) ? 'answered' : 'unanswered') }}">
                {{ $i + 1 }}
            </button>
            @endfor
        </div>

        {{-- Question Card --}}
        <form method="POST" action="{{ route('quiz.answer', [$attempt->id, $question->id]) }}" id="question-form">
            @csrf
            <input type="hidden" name="current_index" value="{{ $currentIndex }}">

            <div class="bg-[#0d1224] border border-white/08 rounded-2xl p-8 mb-6">
                <div class="flex items-center gap-3 mb-6">
                    <span class="badge-cyan px-3 py-1 rounded-full text-xs font-medium">
                        {{ ucfirst($question->temper_category ?? 'general') }}
                    </span>
                    <span class="text-xs text-gray-500">{{ ucfirst($question->difficulty ?? $quiz->difficulty) }}</span>
                </div>

                <h2 class="font-display font-bold text-white text-xl mb-8 leading-relaxed">
                    {{ $currentIndex + 1 }}. {{ $question->question_text }}
                </h2>

                <div class="space-y-3">
                    @foreach($question->options as $i => $option)
                    <button type="button" class="option-btn {{ $selectedOption == $option->id ? 'selected' : '' }}"
                        onclick="selectOption(this, {{ $option->id }})">
                        <span class="option-letter">{{ chr(65 + $i) }}</span>
                        <span>{{ $option->option_text }}</span>
                    </button>
                    @endforeach
                </div>
                <input type="hidden" name="option_id" id="selected-option" value="{{ $selectedOption ?? '' }}">
            </div>

            {{-- Navigation --}}
            <div class="flex items-center justify-between gap-4">
                @if($currentIndex > 0)
                <button type="submit" name="direction" value="prev" class="btn-nav btn-prev">← Previous</button>
                @else
                <div></div>
                @endif

                <div class="flex gap-3">
                    @if($currentIndex < $totalQuestions - 1)
                    <button type="submit" name="direction" value="next" class="btn-nav btn-next">Next Question →</button>
                    @else
                    <button type="button" onclick="confirmSubmit()" class="btn-nav btn-submit">✅ Submit Quiz</button>
                    @endif
                </div>
            </div>
        </form>

        {{-- Hidden submit form — MUST be outside #question-form (nested forms are invalid HTML) --}}
        <form id="submit-form" method="POST" action="{{ route('quiz.submit', $attempt->id) }}" style="display:none">
            @csrf
        </form>

    </div>

    {{-- Submit Confirmation Modal --}}
    <div id="submit-modal" class="fixed inset-0 bg-black/70 flex items-center justify-center z-50 hidden">
        <div class="bg-[#0d1224] border border-white/15 rounded-2xl p-8 max-w-md w-full mx-4">
            <div class="text-4xl text-center mb-4">🎯</div>
            <h3 class="font-display font-bold text-white text-xl text-center mb-2">Submit Quiz?</h3>
            <p class="text-gray-400 text-sm text-center mb-6">
                You've answered <strong class="text-white">{{ $answeredCount }}</strong> of <strong class="text-white">{{ $totalQuestions }}</strong> questions.
                Unanswered questions will be marked as incorrect.
            </p>
            <div class="flex gap-4">
                <button type="button" onclick="document.getElementById('submit-modal').classList.add('hidden')"
                    class="flex-1 py-3 rounded-xl border border-white/10 text-gray-300 hover:border-white/20 transition-colors font-semibold">
                    Keep Going
                </button>
                <button type="button" onclick="submitQuizNow()"
                    class="flex-1 py-3 rounded-xl btn-submit font-display font-bold">
                    Submit Now
                </button>
            </div>
        </div>
    </div>

    <script>
        // Timer
        let timeLeft = {{ $timeLeft }};
        const timerEl = document.getElementById('timer');
        const timerBox = document.getElementById('timer-box');

        function updateTimer() {
            const m = Math.floor(timeLeft / 60);
            const s = timeLeft % 60;
            timerEl.textContent = String(m).padStart(2,'0') + ':' + String(s).padStart(2,'0');

            if (timeLeft <= 60) {
                timerBox.className = 'danger text-center';
                timerEl.style.color = '#f87171';
            } else if (timeLeft <= 180) {
                timerBox.className = 'warning text-center';
                timerEl.style.color = '#fbbf24';
            }

            if (timeLeft <= 0) {
                document.getElementById('submit-form').submit();
                return;
            }
            timeLeft--;
        }

        setInterval(updateTimer, 1000);

        // Option selection
        function selectOption(btn, optionId) {
            document.querySelectorAll('.option-btn').forEach(b => b.classList.remove('selected'));
            btn.classList.add('selected');
            document.getElementById('selected-option').value = optionId;
        }

        // Navigate to question
        function navigateTo(index) {
            const form = document.getElementById('question-form');
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'navigate_to';
            input.value = index;
            form.appendChild(input);
            form.submit();
        }

        // Confirm submit — show modal
        function confirmSubmit() {
            document.getElementById('submit-modal').classList.remove('hidden');
        }

        // Final submit — use explicit form submit on the standalone #submit-form
        function submitQuizNow() {
            const form = document.getElementById('submit-form');
            if (form) {
                form.submit();
            } else {
                console.error('submit-form not found!');
            }
        }

        // Auto-save every 30 seconds
        setInterval(() => {
            const form = document.getElementById('question-form');
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'auto_save';
            input.value = '1';
            form.appendChild(input);
            form.submit();
        }, 30000);
    </script>
</body>
</html>
