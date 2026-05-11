<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\QuizAttempt;
use App\Models\Result;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class ResultController extends Controller
{
    /**
     * Show result detail for an attempt.
     */
    public function show(QuizAttempt $attempt)
    {
        if ($attempt->user_id !== Auth::id()) {
            abort(403);
        }

        $result = $attempt->result;
        if (!$result) {
            return redirect()->route('results.history')->with('error', 'No result found for this attempt.');
        }

        $answers = $attempt->answers()->with([
            'question.options',
            'selectedOption',
            'question' => fn($q) => $q->with('options'),
        ])->get();

        return view('quiz.result', compact('attempt', 'result', 'answers'));
    }

    /**
     * Quiz attempt history for the authenticated user.
     */
    public function history()
    {
        $user = Auth::user();

        $attempts = QuizAttempt::with(['quiz.category', 'result'])
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->latest()
            ->paginate(15);

        $categories   = Category::where('is_active', true)->get();
        $scoresWithResult = QuizAttempt::with('result')
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->get()
            ->filter(fn($a) => $a->result);

        $avgScore    = $scoresWithResult->count() ? round($scoresWithResult->avg(fn($a) => $a->result->score_percentage)) : 0;
        $bestScore   = $scoresWithResult->count() ? $scoresWithResult->max(fn($a) => $a->result->score_percentage) : 0;
        $passedCount = $scoresWithResult->filter(fn($a) => $a->result->passed)->count();

        return view('results.history', compact('attempts', 'categories', 'avgScore', 'bestScore', 'passedCount'));
    }

    /**
     * Download result as PDF.
     */
    public function pdf(QuizAttempt $attempt)
    {
        if ($attempt->user_id !== Auth::id()) {
            abort(403);
        }

        $result  = $attempt->result;
        $answers = $attempt->answers()->with(['question', 'selectedOption'])->get();

        $pdf = Pdf::loadView('results.pdf', compact('attempt', 'result', 'answers'));
        return $pdf->download('scieval-result-' . $attempt->id . '.pdf');
    }
}
