<?php

use App\Models\{Question, User};
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\{assertDatabaseMissing, deleteJson};

test('should be able to delete a question', function () {
    $user = User::factory()->create();

    $question = Question::factory()->for($user)->create();

    // dd($question->toArray());

    Sanctum::actingAs($user);

    deleteJson(route('questions.delete', $question))
        ->assertNoContent(); //return 204

    assertDatabaseMissing('questions', ['id' => $question->id]);

})->only();
