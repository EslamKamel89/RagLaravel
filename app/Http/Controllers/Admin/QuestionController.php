<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller {
    public function index(Request $request) {
        $filter = $request->get('filter', null);

        $questions = Question::with('category')
            ->when($filter, function ($query, $f) {
                return $query->where('question', 'like', "%{$f}%")
                    ->orWhere('answer', 'like', "%{$f}%")
                    ->orWhere('keywords', 'like', "%{$f}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.questions.index', compact('questions', 'filter'));
    }

    public function create() {
        $categories = Category::pluck('name', 'id');

        return view('admin.questions.create', compact('categories'));
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'question' => ['required', 'string'],
            'answer' => ['required', 'string'],
            'keywords' => ['nullable', 'string'],
        ]);

        Question::create($validated);

        return redirect()
            ->route('questions.index')
            ->with('success', 'Question created successfully.');
    }

    public function edit(Question $question) {
        $categories = Category::pluck('name', 'id');

        return view('admin.questions.edit', compact('question', 'categories'));
    }

    public function update(Request $request, Question $question) {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'question' => ['required', 'string'],
            'answer' => ['required', 'string'],
            'keywords' => ['nullable', 'string'],
        ]);

        $question->update($validated);

        return redirect()
            ->route('questions.index')
            ->with('success', 'Question updated successfully.');
    }

    public function destroy(Question $question) {
        $question->delete();

        return redirect()
            ->route('questions.index')
            ->with('success', 'Question deleted successfully.');
    }
}
