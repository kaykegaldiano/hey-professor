<?php

use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\post;

it('can create a new question bigger than 255 characters', function () {
    // Arrange
    $user = User::factory()->create();
    actingAs($user);

    // Act
    $request = post(route('question.store'), [
        'question' => str_repeat('*', 260).'?',
    ]);

    // Assert
    $request->assertRedirect(route('dashboard'));
    assertDatabaseCount('questions', 1);
    assertDatabaseHas('questions', [
        'question' => str_repeat('*', 260).'?',
    ]);
});

it('checks if ends with question mark', function () {

});

it(' has at least 10 characters', function () {

});
