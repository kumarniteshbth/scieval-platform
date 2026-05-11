<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index(Request $request)
    {
        $query = Quiz::with('category')->withCount('questions');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        if ($request->filled('difficulty')) {
            $query->where('difficulty', $request->difficulty);
        }

        $quizzes    = $query->latest()->paginate(15);
        $categories = Category::all();
        return view('admin.quizzes.index', compact('quizzes', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.quizzes.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id'   => 'required|exists:categories,id',
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
            'difficulty'    => 'required|in:easy,medium,hard',
            'time_limit'    => 'required|integer|min:1|max:120',
            'passing_score' => 'required|integer|min:1|max:100',
            'is_active'     => 'boolean',
        ]);

        $validated['is_active']  = $request->has('is_active');
        $validated['created_by'] = auth()->id();

        Quiz::create($validated);
        return redirect()->route('admin.quizzes.index')
            ->with('success', 'Quiz created successfully.');
    }

    public function edit(Quiz $quiz)
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.quizzes.edit', compact('quiz', 'categories'));
    }

    public function update(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'category_id'   => 'required|exists:categories,id',
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
            'difficulty'    => 'required|in:easy,medium,hard',
            'time_limit'    => 'required|integer|min:1|max:120',
            'passing_score' => 'required|integer|min:1|max:100',
            'is_active'     => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $quiz->update($validated);

        return redirect()->route('admin.quizzes.index')
            ->with('success', 'Quiz updated successfully.');
    }

    public function destroy(Quiz $quiz)
    {
        $quiz->delete();
        return redirect()->route('admin.quizzes.index')
            ->with('success', 'Quiz deleted.');
    }

    public function show(Quiz $quiz) { abort(404); }
}
