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
    $request->assertRedirect();
    assertDatabaseCount('questions', 1);
    assertDatabaseHas('questions', [
        'question' => str_repeat('*', 260).'?',
    ]);
});

it('should create as a draft all the time', function () {
    // Arrange
    $user = User::factory()->create();
    actingAs($user);

    // Act
    post(route('question.store'), [
        'question' => str_repeat('*', 260).'?',
    ]);

    // Assert
    assertDatabaseHas('questions', [
        'question' => str_repeat('*', 260).'?',
        'draft' => true,
    ]);
});

it('checks if ends with question mark', function () {
    // Arrange
    $user = User::factory()->create();
    actingAs($user);

    // Act
    $request = post(route('question.store'), [
        'question' => str_repeat('*', 10),
    ]);

    // Assert
    $request->assertSessionHasErrors(['question' => 'Are you sure that this is a question? It is missing the question mark in the end.']);
    assertDatabaseCount('questions', 0);
});

it(' has at least 10 characters', function () {
    // Arrange
    $user = User::factory()->create();
    actingAs($user);

    // Act
    $request = post(route('question.store'), [
        'question' => str_repeat('*', 8).'?',
    ]);

    // Assert
    $request->assertSessionHasErrors(['question' => __('validation.min.string', ['attribute' => 'question', 'min' => 10])]);
    assertDatabaseCount('questions', 0);
});

test('only authenticated users can create questions', function () {
    post(route('question.store'), [
        'question' => str_repeat('9', 10).'?',
    ])
        ->assertRedirect(route('login'));
});
