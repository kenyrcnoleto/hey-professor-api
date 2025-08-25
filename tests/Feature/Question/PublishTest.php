<?php

use App\Models\{Question, User};
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\{assertDatabaseHas, putJson};

test('should be able to publish a question', function () {
    $user = User::factory()->create();

    $question = Question::factory()->for($user)->create(['status' => 'draft']);

    Sanctum::actingAs($user);

    putJson(route('questions.publish', $question))
        ->assertNoContent(); //return 204

    assertDatabaseHas('questions', ['id' => $question->id, 'status' => 'published']);

});

test('it should allow only the creator can publish a question', function () {
    $user  = User::factory()->create();
    $user2 = User::factory()->create();

    $question = Question::factory()->for($user)->create(['status' => 'draft']);

    Sanctum::actingAs($user2);

    putJson(route('questions.publish', $question))
    ->assertForbidden(); //return 204

    assertDatabaseHas('questions', ['id' => $question->id, 'status' => 'draft']);
});

test('it should only publish when the question is on status draft', function () {
    $user  = User::factory()->create();
    $user2 = User::factory()->create();

    $question = Question::factory()->for($user)->create(['status' => 'not-published']);

    Sanctum::actingAs($user2);

    putJson(route('questions.publish', $question))
    ->assertNotFound(); //return 404

    assertDatabaseHas('questions', ['id' => $question->id, 'status' => 'not-published']);
});
