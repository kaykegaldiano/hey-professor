<?php

use function Pest\Laravel\artisan;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\assertSoftDeleted;

it('should prune records deleted more than one month', function () {
    $question = \App\Models\Question::factory()->create(['deleted_at' => now()->subMonths(2)]);
    assertSoftDeleted('questions', ['id' => $question->id]);

    artisan('model:prune');

    assertDatabaseMissing('questions', ['id' => $question->id]);
});
