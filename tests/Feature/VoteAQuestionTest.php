<?php

use App\Models\Question;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\post;

it('should be able to like a question', function () {
    // Arrange
    $user = User::factory()->create();
    $question = Question::factory()->create();

    actingAs($user);

    // Act
    post(route('question.like', $question))
        ->assertRedirect();

    // Assert
    assertDatabaseHas('votes', [
        'question_id' => $question->id,
        'user_id' => $user->id,
        'like' => 1,
        'unlike' => 0,
    ]);
});

it('should not be able to like a question twice', function () {
    // Arrange
    $user = User::factory()->create();
    $question = Question::factory()->create();

    actingAs($user);

    // Act
    post(route('question.like', $question));
    post(route('question.like', $question));
    post(route('question.like', $question));
    post(route('question.like', $question));

    // Assert
    expect($user->votes()->where('question_id', $question->id)->get())
        ->toHaveCount(1);
});

it('should be able to unlike a question', function () {
    // Arrange
    $user = User::factory()->create();
    $question = Question::factory()->create();

    actingAs($user);

    // Act
    post(route('question.unlike', $question))
        ->assertRedirect();

    // Assert
    assertDatabaseHas('votes', [
        'question_id' => $question->id,
        'user_id' => $user->id,
        'like' => 0,
        'unlike' => 1,
    ]);
});

it('should not be able to unlike a question twice', function () {
    // Arrange
    $user = User::factory()->create();
    $question = Question::factory()->create();

    actingAs($user);

    // Act
    post(route('question.unlike', $question));
    post(route('question.unlike', $question));
    post(route('question.unlike', $question));
    post(route('question.unlike', $question));

    // Assert
    expect($user->votes()->where('question_id', $question->id)->get())
        ->toHaveCount(1);
});
