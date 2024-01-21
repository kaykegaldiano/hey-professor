<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\RedirectResponse;

class QuestionController extends Controller
{
    public function store(): RedirectResponse
    {
        $validated = request()->validate([
            'question' => ['required', 'min:10'],
        ]);

        Question::create($validated);

        return to_route('dashboard');
    }
}
