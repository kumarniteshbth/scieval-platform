<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Quizzes — browse & take
    Route::get('/quizzes', [QuizController::class, 'index'])->name('quiz.index');
    Route::get('/quizzes/{quiz}', [QuizController::class, 'show'])->name('quiz.show');
    Route::get('/quizzes/{quiz}/start', [QuizController::class, 'start'])->name('quiz.start');
    Route::post('/attempts/{attempt}/answer/{question}', [QuizController::class, 'answer'])->name('quiz.answer');
    Route::post('/attempts/{attempt}/submit', [QuizController::class, 'submit'])->name('quiz.submit');

    // Results
    Route::get('/results', [ResultController::class, 'history'])->name('results.history');
    Route::get('/results/{attempt}', [ResultController::class, 'show'])->name('results.show');
    Route::get('/results/{attempt}/pdf', [ResultController::class, 'pdf'])->name('results.pdf');

    // Leaderboard
    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // Users CRUD
    Route::resource('users', Admin\UserController::class);

    // Categories CRUD
    Route::resource('categories', Admin\CategoryController::class);

    // Quizzes CRUD
    Route::resource('quizzes', Admin\QuizController::class);

    // Questions CRUD
    Route::resource('questions', Admin\QuestionController::class);

    // Results view
    Route::get('results', [Admin\ResultController::class, 'index'])->name('results.index');
    Route::get('results/{result}', [Admin\ResultController::class, 'show'])->name('results.show');
});

require __DIR__.'/auth.php';
