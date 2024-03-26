<?php

use App\Models\Question;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertNotSoftDeleted;
use function Pest\Laravel\assertSoftDeleted;
use function Pest\Laravel\patch;

it('should be able to archive a question', function () {
    $user = User::factory()->create();
    $question = Question::factory()
        ->for($user, 'createdBy')
        ->create(['draft' => true]);

    actingAs($user);

    patch(route('question.archive', $question))
        ->assertRedirect();

    assertSoftDeleted('questions', ['id' => $question->id]);

    $question->refresh();

    expect($question)->deleted_at->not->toBeNull();
});

it('makes sure only the person who has created the question can archive it', function () {
    $rightUser = User::factory()->create();
    $wrongUser = User::factory()->create();
    $question = Question::factory()->create(['draft' => true, 'created_by' => $rightUser->id]);

    actingAs($wrongUser);

    patch(route('question.archive', $question))
        ->assertForbidden();

    actingAs($rightUser);

    patch(route('question.archive', $question))
        ->assertRedirect();
});

it('should be able to restore an archived question', function () {
    $user = User::factory()->create();
    $question = Question::factory()
        ->for($user, 'createdBy')
        ->create(['draft' => true, 'deleted_at' => now()]);

    actingAs($user);

    $response = patch(route('question.restore', $question))
        ->assertRedirect();

    assertNotSoftDeleted('questions', ['id' => $question->id]);

    $question->refresh();

    expect($question)->deleted_at->toBeNull();
});
