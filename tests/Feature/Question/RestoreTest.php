<?php

use App\Models\{Question, User};
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\{assertNotSoftDeleted, assertSoftDeleted, putJson};

test('should be able to restore a question', function () {
    $user = User::factory()->create();

    $question = Question::factory()->for($user)->create();

    $question->delete();

    // dd($question->toArray());
    assertSoftDeleted('questions', ['id' => $question->id]);

    Sanctum::actingAs($user);

    putJson(route('questions.restore', $question))
        ->assertNoContent(); //return 204

    assertNotSoftDeleted('questions', ['id' => $question->id]);

});

test('it should allow only the creator can restore a question', function () {
    $user  = User::factory()->create();
    $user2 = User::factory()->create();

    $question = Question::factory()->for($user)->create();

    $question->delete();

    // dd($question->toArray());

    Sanctum::actingAs($user2);

    putJson(route('questions.restore', $question))
    ->assertForbidden(); //return 204

    assertSoftDeleted('questions', ['id' => $question->id]);
});

test('it should only restore when the question is deleted', function () {
    $user  = User::factory()->create();
    $user2 = User::factory()->create();

    $question = Question::factory()->for($user)->create();

    // dd($question->toArray());

    Sanctum::actingAs($user2);

    putJson(route('questions.restore', $question))
    ->assertNotFound(); //return 404

    assertNotSoftDeleted('questions', ['id' => $question->id]);
});
