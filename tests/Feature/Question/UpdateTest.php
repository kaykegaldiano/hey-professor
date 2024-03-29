<?php

use App\Models\Question;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\put;

it('should update the question in the database', function () {
    $user = User::factory()->create();
    $question = Question::factory()->for($user, 'createdBy')->create(['draft' => true]);

    actingAs($user);

    put(route('question.update', $question), [
        'question' => 'Was the question updated?',
    ])
        ->assertRedirect(route('question.index'));

    $question->refresh();

    expect($question)
        ->question
        ->toBe('Was the question updated?');
});

it('should make sure that only questions with status DRAFT can be updated', function () {
    $user = User::factory()->create();
    $notDraftQuestion = Question::factory()->for($user, 'createdBy')->create(['draft' => false]);
    $draftQuestion = Question::factory()->for($user, 'createdBy')->create(['draft' => true]);

    actingAs($user);

    put(route('question.update', $notDraftQuestion))->assertForbidden();
    put(route('question.update', $draftQuestion), ['question' => 'Was the question updated?'])->assertRedirect();
});

it('makes sure only the person who has created the question can update it', function () {
    $rightUser = User::factory()->create();
    $wrongUser = User::factory()->create();
    $question = Question::factory()->create(['draft' => true, 'created_by' => $rightUser->id]);

    actingAs($wrongUser);
    put(route('question.update', $question))->assertForbidden();

    actingAs($rightUser);
    put(route('question.update', $question), ['question' => 'Was the question updated?'])->assertRedirect();
});

it('can update a question bigger than 255 characters', function () {
    // Arrange
    $user = User::factory()->create();
    $question = Question::factory()->for($user, 'createdBy')->create(['draft' => true]);

    actingAs($user);

    // Act
    $request = put(route('question.update', $question), [
        'question' => str_repeat('*', 260).'?',
    ]);

    // Assert
    $request->assertRedirect();
    assertDatabaseCount('questions', 1);
    assertDatabaseHas('questions', [
        'question' => str_repeat('*', 260).'?',
    ]);
});

it('checks if ends with question mark', function () {
    // Arrange
    $user = User::factory()->create();
    $question = Question::factory()->for($user, 'createdBy')->create(['draft' => true]);

    actingAs($user);

    // Act
    $request = put(route('question.update', $question), [
        'question' => str_repeat('*', 10),
    ]);

    // Assert
    $request->assertSessionHasErrors(['question' => 'Are you sure that this is a question? It is missing the question mark in the end.']);
    assertDatabaseHas('questions', ['question' => $question->question]);
});

it('has at least 10 characters', function () {
    // Arrange
    $user = User::factory()->create();
    $question = Question::factory()->for($user, 'createdBy')->create(['draft' => true]);

    actingAs($user);

    // Act
    $request = put(route('question.update', $question), [
        'question' => str_repeat('*', 8).'?',
    ]);

    // Assert
    $request->assertSessionHasErrors(['question' => __('validation.min.string', ['attribute' => 'question', 'min' => 10])]);
    assertDatabaseHas('questions', ['question' => $question->question]);
});
