<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Option;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\AttemptAnswer;
use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class QuizController extends Controller
{
    /**
     * Browse all quizzes with filters.
     */
    public function index(Request $request)
    {
        $categories = Category::where('is_active', true)->get();

        $quizzes = Quiz::with(['category', 'questions', 'attempts'])
            ->where('is_active', true)
            ->when($request->category, fn($q) => $q->where('category_id', $request->category))
            ->when($request->difficulty, fn($q) => $q->where('difficulty', $request->difficulty))
            ->paginate(9);

        return view('quiz.index', compact('quizzes', 'categories'));
    }

    /**
     * Show quiz detail/preview page.
     */
    public function show(Quiz $quiz)
    {
        $quiz->load(['category', 'questions']);

        $previousAttempts = QuizAttempt::with('result')
            ->where('user_id', Auth::id())
            ->where('quiz_id', $quiz->id)
            ->where('status', 'completed')
            ->latest()
            ->take(5)
            ->get();

        return view('quiz.show', compact('quiz', 'previousAttempts'));
    }

    /**
     * Start or resume a quiz attempt.
     */
    public function start(Quiz $quiz)
    {
        $user = Auth::user();

        // Check for existing in-progress attempt
        $attempt = QuizAttempt::where('user_id', $user->id)
            ->where('quiz_id', $quiz->id)
            ->where('status', 'in_progress')
            ->latest()
            ->first();

        // Create new attempt if none
        if (!$attempt) {
            $attempt = QuizAttempt::create([
                'user_id'     => $user->id,
                'quiz_id'     => $quiz->id,
                'status'      => 'in_progress',
                'started_at'  => now(),
                'expires_at'  => now()->addMinutes($quiz->time_limit),
                'current_question_index' => 0,
            ]);
        }

        return $this->showQuestion($attempt, $attempt->current_question_index ?? 0);
    }

    /**
     * Handle answer submission and navigation.
     */
    public function answer(Request $request, QuizAttempt $attempt, $questionId)
    {
        $this->authorize_attempt($attempt);

        // Save answer if option selected
        if ($request->option_id && !$request->auto_save) {
            AttemptAnswer::updateOrCreate(
                ['attempt_id' => $attempt->id, 'question_id' => $questionId],
                [
                    'selected_option_id' => $request->option_id,
                    'is_correct' => Option::find($request->option_id)?->is_correct ?? false,
                ]
            );
        }

        // Handle navigation
        $totalQuestions = $attempt->quiz->questions->count();
        $currentIndex   = (int)$request->current_index;

        if ($request->navigate_to !== null) {
            $nextIndex = (int)$request->navigate_to;
        } elseif ($request->direction === 'prev') {
            $nextIndex = max(0, $currentIndex - 1);
        } else {
            $nextIndex = min($totalQuestions - 1, $currentIndex + 1);
        }

        $attempt->update(['current_question_index' => $nextIndex]);
        return $this->showQuestion($attempt, $nextIndex);
    }

    /**
     * Submit the quiz and calculate results.
     */
    public function submit(Request $request, QuizAttempt $attempt)
    {
        $this->authorize_attempt($attempt);

        // Save last answer if provided
        if ($request->option_id) {
            AttemptAnswer::updateOrCreate(
                ['attempt_id' => $attempt->id, 'question_id' => $request->question_id],
                [
                    'selected_option_id' => $request->option_id,
                    'is_correct' => Option::find($request->option_id)?->is_correct ?? false,
                ]
            );
        }

        // Mark attempt as completed
        $attempt->update([
            'status'       => 'completed',
            'submitted_at' => now(),
        ]);

        // Calculate scores
        $questions = $attempt->quiz->questions()->with(['options', 'attemptAnswers' => fn($q) => $q->where('attempt_id', $attempt->id)])->get();
        $answers   = $attempt->answers()->with(['question.options', 'selectedOption'])->get();

        $totalQuestions = $questions->count();
        $correctCount   = $answers->where('is_correct', true)->count();
        $scorePercentage = $totalQuestions > 0 ? round(($correctCount / $totalQuestions) * 100) : 0;
        $passed = $scorePercentage >= $attempt->quiz->passing_score;

        // Calculate temper sub-scores
        $subScores = $this->calculateTemperScores($answers);

        // Calculate time taken
        $timeTaken = $attempt->started_at ? now()->diffInSeconds($attempt->started_at) : 0;

        // Save result
        $result = Result::create([
            'attempt_id'               => $attempt->id,
            'user_id'                  => $attempt->user_id,
            'score'                    => $correctCount,
            'total_questions'          => $totalQuestions,
            'score_percentage'         => $scorePercentage,
            'passed'                   => $passed,
            'time_taken'               => $timeTaken,
            'literacy_level'           => $this->getLiteracyLevel($scorePercentage),
            'logical_thinking_score'   => $subScores['logical_thinking'],
            'evidence_reasoning_score' => $subScores['evidence_reasoning'],
            'myth_vs_fact_score'       => $subScores['myth_vs_fact'],
            'problem_solving_score'    => $subScores['problem_solving'],
            'feedback'                 => $this->generateFeedback($scorePercentage),
        ]);

        return redirect()->route('results.show', $attempt->id)
            ->with('success', 'Quiz submitted successfully!');
    }

    /**
     * Render quiz take view for a specific question.
     */
    private function showQuestion(QuizAttempt $attempt, int $index)
    {
        $quiz      = $attempt->quiz()->with(['category', 'questions.options'])->first();
        $questions = $quiz->questions()->with('options')->get();

        if ($questions->isEmpty()) {
            return redirect()->route('quiz.index')->with('error', 'This quiz has no questions yet.');
        }

        $currentIndex  = max(0, min($index, $questions->count() - 1));
        $totalQuestions = $questions->count();
        $question      = $questions->get($currentIndex);

        // Answered question IDs for nav
        $answeredMap       = $attempt->answers()->pluck('question_id')->toArray();
        $answeredQuestions = [];
        foreach ($questions as $i => $q) {
            if (in_array($q->id, $answeredMap)) {
                $answeredQuestions[] = $i;
            }
        }
        $answeredCount = count($answeredQuestions);

        // Previously selected option
        $selectedOption = $attempt->answers()->where('question_id', $question->id)->value('selected_option_id');

        // Time remaining
        $timeLeft = $attempt->expires_at
            ? max(0, now()->diffInSeconds($attempt->expires_at, false))
            : ($quiz->time_limit * 60);

        return view('quiz.take', compact(
            'quiz', 'attempt', 'question', 'questions',
            'currentIndex', 'totalQuestions',
            'answeredQuestions', 'answeredCount', 'selectedOption', 'timeLeft'
        ));
    }

    private function calculateTemperScores($answers): array
    {
        $categories = ['logical_thinking', 'evidence_reasoning', 'myth_vs_fact', 'problem_solving'];
        $scores     = [];

        foreach ($categories as $cat) {
            $catAnswers = $answers->filter(fn($a) => $a->question->temper_category === $cat);
            if ($catAnswers->count() > 0) {
                $scores[$cat] = round(($catAnswers->where('is_correct', true)->count() / $catAnswers->count()) * 100);
            } else {
                $scores[$cat] = 0;
            }
        }

        return $scores;
    }

    private function getLiteracyLevel(int $score): string
    {
        return match(true) {
            $score >= 90 => 'Science Expert',
            $score >= 80 => 'Science Proficient',
            $score >= 70 => 'Science Literate',
            $score >= 60 => 'Science Aware',
            $score >= 40 => 'Science Beginner',
            default      => 'Getting Started',
        };
    }

    private function generateFeedback(int $score): string
    {
        return match(true) {
            $score >= 90 => 'Outstanding performance! You demonstrate exceptional scientific temper.',
            $score >= 80 => 'Excellent! You have a strong scientific literacy foundation.',
            $score >= 70 => 'Good job! Continue building your scientific reasoning skills.',
            $score >= 60 => 'Passed! Focus on evidence-based thinking to improve further.',
            default      => 'Keep practicing! Review the explanations to strengthen your scientific temper.',
        };
    }

    private function authorize_attempt(QuizAttempt $attempt): void
    {
        if ($attempt->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this quiz attempt.');
        }
    }
}
