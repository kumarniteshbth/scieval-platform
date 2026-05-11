<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\Result;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers     = User::where('role', 'student')->count();
        $totalQuizzes   = Quiz::count();
        $totalAttempts  = QuizAttempt::where('status', 'completed')->count();
        $totalQuestions = \App\Models\Question::count();

        // Recent attempts
        $recentAttempts = QuizAttempt::with(['user', 'quiz.category', 'result'])
            ->where('status', 'completed')
            ->latest()
            ->take(8)
            ->get();

        // Daily attempts last 7 days
        $dailyAttempts = QuizAttempt::where('status', 'completed')
            ->where('submitted_at', '>=', now()->subDays(7))
            ->selectRaw('DATE(submitted_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Score distribution
        $results = Result::all();
        $scoreDistribution = [
            $results->whereBetween('score_percentage', [0, 40])->count(),
            $results->whereBetween('score_percentage', [41, 60])->count(),
            $results->whereBetween('score_percentage', [61, 80])->count(),
            $results->whereBetween('score_percentage', [81, 100])->count(),
        ];

        // Category performance
        $categoryStats = Category::with(['quizzes.attempts.result'])
            ->where('is_active', true)
            ->get()
            ->map(function ($cat) {
                $allResults = $cat->quizzes->flatMap(fn($q) => $q->attempts->filter(fn($a) => $a->result)->map(fn($a) => $a->result));
                $cat->avg_score      = $allResults->count() ? round($allResults->avg('score_percentage')) : 0;
                $cat->attempts_count = $cat->quizzes->sum(fn($q) => $q->attempts->where('status', 'completed')->count());
                return $cat;
            });

        return view('admin.dashboard', compact(
            'totalUsers', 'totalQuizzes', 'totalAttempts', 'totalQuestions',
            'recentAttempts', 'dailyAttempts', 'scoreDistribution', 'categoryStats'
        ));
    }
}
