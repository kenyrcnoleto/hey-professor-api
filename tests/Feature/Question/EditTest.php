<?php

use App\Models\Question;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

test('it should be able to update a question', function () {
    $user =  User::factory()->create();

    $question =  Question::factory()->create(['user_id' => $user->id]);

    // actingAs($user);

    Sanctum::actingAs($user);

    putJson(route('questions.update', $question), [
        'question'  => 'Updating question testing?',
    ])->assertSuccessful();

    assertDatabaseHas('questions', [
        'id'        => $question->id,
        'user_id'  => $user->id,
        'question' => 'Updating question testing?'
    ]);

});
