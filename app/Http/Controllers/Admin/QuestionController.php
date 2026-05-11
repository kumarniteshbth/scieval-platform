<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Option;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        $query = Question::with(['quiz.category', 'options']);

        if ($request->filled('category')) {
            $query->whereHas('quiz', fn($q) => $q->where('category_id', $request->category));
        }
        if ($request->filled('search')) {
            $query->where('question_text', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('difficulty')) {
            $query->where('difficulty', $request->difficulty);
        }

        $questions  = $query->latest()->paginate(15);
        $categories = Category::where('is_active', true)->get();

        return view('admin.questions.index', compact('questions', 'categories'));
    }

    public function create()
    {
        $quizzes = Quiz::with('category')->where('is_active', true)->get();
        return view('admin.questions.create', compact('quizzes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'quiz_id'         => 'required|exists:quizzes,id',
            'question_text'   => 'required|string|max:1000',
            'explanation'     => 'nullable|string',
            'difficulty'      => 'required|in:easy,medium,hard',
            'temper_category' => 'required|in:logical_thinking,evidence_reasoning,myth_vs_fact,problem_solving',
            'options'         => 'required|array|size:4',
            'options.*.text'  => 'required|string|max:500',
            'correct_option'  => 'required|integer|min:0|max:3',
        ]);

        $question = Question::create([
            'quiz_id'         => $request->quiz_id,
            'question_text'   => $request->question_text,
            'explanation'     => $request->explanation,
            'difficulty'      => $request->difficulty,
            'temper_category' => $request->temper_category,
        ]);

        foreach ($request->options as $index => $option) {
            Option::create([
                'question_id' => $question->id,
                'option_text' => $option['text'],
                'is_correct'  => ($index == $request->correct_option),
                'order'       => $index,
            ]);
        }

        return redirect()->route('admin.questions.index')
            ->with('success', 'Question added successfully!');
    }

    public function edit(Question $question)
    {
        $question->load('options');
        $quizzes = Quiz::with('category')->get();
        return view('admin.questions.edit', compact('question', 'quizzes'));
    }

    public function update(Request $request, Question $question)
    {
        $request->validate([
            'quiz_id'         => 'required|exists:quizzes,id',
            'question_text'   => 'required|string|max:1000',
            'explanation'     => 'nullable|string',
            'difficulty'      => 'required|in:easy,medium,hard',
            'temper_category' => 'required|in:logical_thinking,evidence_reasoning,myth_vs_fact,problem_solving',
            'options'         => 'required|array|size:4',
            'options.*.text'  => 'required|string|max:500',
            'correct_option'  => 'required|integer|min:0|max:3',
        ]);

        $question->update([
            'quiz_id'         => $request->quiz_id,
            'question_text'   => $request->question_text,
            'explanation'     => $request->explanation,
            'difficulty'      => $request->difficulty,
            'temper_category' => $request->temper_category,
        ]);

        // Rebuild options
        $question->options()->delete();
        foreach ($request->options as $index => $option) {
            Option::create([
                'question_id' => $question->id,
                'option_text' => $option['text'],
                'is_correct'  => ($index == $request->correct_option),
                'order'       => $index,
            ]);
        }

        return redirect()->route('admin.questions.index')
            ->with('success', 'Question updated successfully!');
    }

    public function destroy(Question $question)
    {
        $question->delete();
        return redirect()->route('admin.questions.index')
            ->with('success', 'Question deleted.');
    }

    public function show(Question $question) { abort(404); }
}
