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


describe('validation rules', function () {
    test('question::required', function () {
        $user =  User::factory()->create();
        $question =  Question::factory()->create(['user_id' => $user->id]);

    // actingAs($user);

    Sanctum::actingAs($user);

    putJson(route('questions.update', $question), [
        'question' => ''
    ])
    ->assertJsonValidationErrors([
        'question'  => 'required',
    ]);


    })->only();
    test('question::ending with question mark', function () {
            $user =  User::factory()->create();

        // actingAs($user);

        Sanctum::actingAs($user);

        postJson(route('questions.store', [
            'question' => 'Question without a question mark'
        ]))
        ->assertJsonValidationErrors([
            'question'  => 'The question should end withe quetion mark (?).',
        ]);
    });

    test('question::min caracters should be 10', function () {
            $user =  User::factory()->create();

        // actingAs($user);

        Sanctum::actingAs($user);

        postJson(route('questions.store', [
            'question' => 'Question?'
        ]))
        ->assertJsonValidationErrors([
            'question'  => 'The question field must be at least 10 characters.',
        ]);
    });

    test('question::should be unique', function () {

        $user =  User::factory()->create();
        Question::factory()->create(['question' => 'Loren ipsun teste?',
                                    'user_id' => $user->id,
                                    'status' => 'draft'
                                ]);

        // actingAs($user);

        Sanctum::actingAs($user);

        postJson(route('questions.store', [
            'question' => 'Loren ipsun teste?'
        ]))
        ->assertJsonValidationErrors([
            'question'  => 'The question has already been taken.',
        ]);
    });
});
