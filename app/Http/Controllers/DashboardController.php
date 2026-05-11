<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Recent completed attempts with result
        $recentAttempts = QuizAttempt::with(['quiz.category', 'result'])
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->latest()
            ->take(6)
            ->get();

        // Stats
        $totalAttempts = $recentAttempts->count();
        $completedWithResult = QuizAttempt::with('result')
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->get();

        $scoresWithResult = $completedWithResult->filter(fn($a) => $a->result);
        $avgScore  = $scoresWithResult->count() ? round($scoresWithResult->avg(fn($a) => $a->result->score_percentage)) : 0;
        $bestScore = $scoresWithResult->count() ? $scoresWithResult->max(fn($a) => $a->result->score_percentage) : 0;
        $passedCount = $scoresWithResult->filter(fn($a) => $a->result->passed)->count();

        // Scientific temper sub-scores (average across all results)
        $results = $scoresWithResult->map(fn($a) => $a->result);
        $subScores = [
            'logical_thinking'  => $results->count() ? round($results->avg('logical_thinking_score')) : 0,
            'evidence_reasoning'=> $results->count() ? round($results->avg('evidence_reasoning_score')) : 0,
            'myth_vs_fact'      => $results->count() ? round($results->avg('myth_vs_fact_score')) : 0,
            'problem_solving'   => $results->count() ? round($results->avg('problem_solving_score')) : 0,
        ];

        // Literacy level
        $literacyLevel = $this->getLiteracyLevel($avgScore);

        // Recommended quizzes
        $triedIds = QuizAttempt::where('user_id', $user->id)->pluck('quiz_id');
        $recommendations = Quiz::with('category')
            ->where('is_active', true)
            ->whereNotIn('id', $triedIds)
            ->inRandomOrder()
            ->take(3)
            ->get();
        if ($recommendations->isEmpty()) {
            $recommendations = Quiz::with('category')->where('is_active', true)->inRandomOrder()->take(3)->get();
        }

        return view('dashboard', compact(
            'user', 'recentAttempts', 'totalAttempts', 'avgScore',
            'bestScore', 'passedCount', 'subScores', 'literacyLevel', 'recommendations'
        ));
    }

    private function getLiteracyLevel(int $score): string
    {
        return match(true) {
            $score >= 90 => '🌟 Science Expert',
            $score >= 80 => '⭐ Science Proficient',
            $score >= 70 => '📚 Science Literate',
            $score >= 60 => '🔬 Science Aware',
            $score >= 40 => '🌱 Science Beginner',
            default      => '🔭 Getting Started',
        };
    }
}
