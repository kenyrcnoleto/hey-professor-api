<?php

use App\Models\{Question, User};
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\{assertNotSoftDeleted, assertSoftDeleted, deleteJson};

test('should be able to archive a question', function () {
    $user = User::factory()->create();

    $question = Question::factory()->for($user)->create();

    // dd($question->toArray());

    Sanctum::actingAs($user);

    deleteJson(route('questions.archive', $question))
        ->assertNoContent(); //return 204

    assertSoftDeleted('questions', ['id' => $question->id]);

});

test('it should be able to archive a question', function () {
    $user  = User::factory()->create();
    $user2 = User::factory()->create();

    $question = Question::factory()->for($user)->create();

    // dd($question->toArray());

    Sanctum::actingAs($user2);

    deleteJson(route('questions.archive', $question))
        ->assertForbidden(); //return 204

    assertNotSoftDeleted('questions', ['id' => $question->id]);
});
