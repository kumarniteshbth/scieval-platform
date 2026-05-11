<?php

namespace App\Http\Controllers;

use App\Models\User;

class LeaderboardController extends Controller
{
    public function index()
    {
        // Build leaderboard: users with at least 1 completed attempt, ranked by avg score
        $topUsers = User::where('role', 'student')
            ->whereHas('quizAttempts', fn($q) => $q->where('status', 'completed'))
            ->get()
            ->map(function ($user) {
                $attempts    = $user->quizAttempts()->where('status', 'completed')->with('result')->get();
                $withResults = $attempts->filter(fn($a) => $a->result);
                $user->avg_score  = $withResults->count() ? round($withResults->avg(fn($a) => $a->result->score_percentage)) : 0;
                $user->best_score = $withResults->count() ? $withResults->max(fn($a) => $a->result->score_percentage) : 0;
                $user->quiz_count = $attempts->count();
                return $user;
            })
            ->sortByDesc('avg_score')
            ->values()
            ->take(20);

        // Current user's rank
        $userRank = null;
        foreach ($topUsers as $i => $u) {
            if ($u->id === auth()->id()) {
                $userRank = $i + 1;
                break;
            }
        }

        return view('leaderboard', compact('topUsers', 'userRank'));
    }
}
