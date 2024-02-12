<?php

use App\Models\Question;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\post;

it('shoud be able to like a question', function () {
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
