<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Result;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::where('is_active', true)->get();

        $results = Result::with(['attempt.user', 'attempt.quiz.category'])
            ->when($request->search, fn($q) => $q->whereHas('attempt.user', fn($u) => $u->where('name', 'like', "%{$request->search}%")))
            ->when($request->category, fn($q) => $q->whereHas('attempt.quiz', fn($quiz) => $quiz->where('category_id', $request->category)))
            ->latest()
            ->paginate(20);

        return view('admin.results.index', compact('results', 'categories'));
    }

    public function show(Result $result)
    {
        $result->load(['attempt.user', 'attempt.quiz.category', 'attempt.answers.question', 'attempt.answers.selectedOption']);
        return view('admin.results.show', compact('result'));
    }
}
