<?php

use App\Models\Question;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\put;

it('can publish a question', function () {
    $user = User::factory()->create();
    $question = Question::factory()
        ->for($user, 'createdBy')
        ->create(['draft' => true]);

    actingAs($user);

    put(route('question.publish', $question))
        ->assertRedirect();

    $question->refresh();

    expect($question)
        ->draft
        ->toBeFalse();
});

it('makes sure only the person who has created the question can publish it', function () {
    $rightUser = User::factory()->create();
    $wrongUser = User::factory()->create();
    $question = Question::factory()->create(['draft' => true, 'created_by' => $rightUser->id]);

    actingAs($wrongUser);

    put(route('question.publish', $question))
        ->assertForbidden();

    actingAs($rightUser);

    put(route('question.publish', $question))
        ->assertRedirect();
});
