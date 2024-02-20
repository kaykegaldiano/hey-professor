<?php

use App\Models\Question;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\put;

it('should update the question in the database', function () {
    $user = User::factory()->create();
    $question = Question::factory()->for($user, 'createdBy')->create(['draft' => true]);

    actingAs($user);

    put(route('question.update', $question), [
        'question' => 'Was the question updated?',
    ])
        ->assertRedirect();

    $question->refresh();

    expect($question)
        ->question
        ->toBe('Was the question updated?');
});
