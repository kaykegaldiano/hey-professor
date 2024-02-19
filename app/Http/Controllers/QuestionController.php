<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class QuestionController extends Controller
{
    public function store(): RedirectResponse
    {
        request()->validate([
            'question' => [
                'required',
                'min:10',
                function (string $attribute, mixed $value, \Closure $fail) {
                    if (! str_ends_with($value, '?')) {
                        $fail('Are you sure that this is a question? It is missing the question mark in the end.');
                    }
                },
            ],
        ]);

        auth()->user()->questions()->create([
            'question' => request()->question,
            'draft' => true,
        ]);

        return to_route('dashboard');
    }
}
