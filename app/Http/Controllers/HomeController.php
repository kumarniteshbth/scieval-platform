<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\QuizAttempt;
use App\Models\Quiz;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        $categories    = Category::withCount('quizzes')->where('is_active', true)->get();
        $totalUsers    = User::where('role', 'student')->count();
        $totalQuizzes  = Quiz::where('is_active', true)->count();
        $totalAttempts = QuizAttempt::where('status', 'completed')->count();

        return view('home', compact('categories', 'totalUsers', 'totalQuizzes', 'totalAttempts'));
    }
}
