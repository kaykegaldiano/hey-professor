<?php

use App\Models\Question;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\delete;

it('can destroy a question', function () {
    $user = User::factory()->create();
    $question = Question::factory()
        ->for($user, 'createdBy')
        ->create(['draft' => true]);

    actingAs($user);

    delete(route('question.destroy', $question))
        ->assertRedirect();

    assertDatabaseMissing('questions', ['id' => $question->id]);
});

it('makes sure only the person who has created the question can destroy it', function () {
    $rightUser = User::factory()->create();
    $wrongUser = User::factory()->create();
    $question = Question::factory()->create(['draft' => true, 'created_by' => $rightUser->id]);

    actingAs($wrongUser);

    delete(route('question.destroy', $question))
        ->assertForbidden();

    actingAs($rightUser);

    delete(route('question.destroy', $question))
        ->assertRedirect();
});
