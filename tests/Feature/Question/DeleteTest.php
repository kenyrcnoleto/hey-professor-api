<?php

use App\Models\{Question, User};
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\{assertDatabaseHas, assertDatabaseMissing, deleteJson};

test('should be able to delete a question', function () {
    $user = User::factory()->create();

    $question = Question::factory()->for($user)->create();

    // dd($question->toArray());

    Sanctum::actingAs($user);

    deleteJson(route('questions.delete', $question))
        ->assertNoContent(); //return 204

    assertDatabaseMissing('questions', ['id' => $question->id]);

});

test('it should be able to delete a question', function () {
    $user  = User::factory()->create();
    $user2 = User::factory()->create();

    $question = Question::factory()->for($user)->create();

    // dd($question->toArray());

    Sanctum::actingAs($user2);

    deleteJson(route('questions.delete', $question))
        ->assertForbidden(); //return 204

    assertDatabaseHas('questions', ['id' => $question->id]);
});
