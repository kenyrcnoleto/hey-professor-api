<?php

use App\Models\{Question, User};
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\{actingAs, assertDatabaseHas, postJson};

test('it should be albeto store a new question', function () {
    $user = User::factory()->create();

    // actingAs($user);

    Sanctum::actingAs($user);

    postJson(route('questions.store', [
        'question' => 'Loren ipsun teste?',
    ]))->assertSuccessful();

    assertDatabaseHas('questions', [
        'user_id'  => $user->id,
        'question' => 'Loren ipsun teste?',
    ]);

});

test('with the create of the question,we nedde to make sure that it creates with status _draft_ ', function () {
    $user = User::factory()->create();

    // actingAs($user);

    Sanctum::actingAs($user);

    postJson(route('questions.store', [
        'question' => 'Loren ipsun teste?',
    ]))->assertSuccessful();

    assertDatabaseHas('questions', [
        'user_id'  => $user->id,
        'status'   => 'draft',
        'question' => 'Loren ipsun teste?',
    ]);

});

describe('validation rules', function () {
    test('question::required', function () {
        $user = User::factory()->create();

        // actingAs($user);

        Sanctum::actingAs($user);

        postJson(route('questions.store', []))
        ->assertJsonValidationErrors([
            'question' => 'required',
        ]);

    });
    test('question::ending with question mark', function () {
        $user = User::factory()->create();

        // actingAs($user);

        Sanctum::actingAs($user);

        postJson(route('questions.store', [
            'question' => 'Question without a question mark',
        ]))
        ->assertJsonValidationErrors([
            'question' => 'The question should end withe quetion mark (?).',
        ]);
    });

    test('question::min caracters should be 10', function () {
        $user = User::factory()->create();

        // actingAs($user);

        Sanctum::actingAs($user);

        postJson(route('questions.store', [
            'question' => 'Question?',
        ]))
        ->assertJsonValidationErrors([
            'question' => 'The question field must be at least 10 characters.',
        ]);
    });

    test('question::should be unique', function () {

        $user = User::factory()->create();
        Question::factory()->create(['question' => 'Loren ipsun teste?',
            'user_id'                           => $user->id,
            'status'                            => 'draft',
        ]);

        // actingAs($user);

        Sanctum::actingAs($user);

        postJson(route('questions.store', [
            'question' => 'Loren ipsun teste?',
        ]))
        ->assertJsonValidationErrors([
            'question' => 'The question has already been taken.',
        ]);
    });
});

test('after creating we should return a stutus 201 with the created question', function () {
    $user = User::factory()->create();

    // actingAs($user);

    Sanctum::actingAs($user);

    $request = postJson(route('questions.store', [
        'question' => 'Loren ipsun teste?',
    ]))
    ->assertCreated();

    //dd($request->json());

    $question = Question::latest()->first();

    $request->assertJson([
        'data' => [

            'id'         => $question->id,
            'question'   => $question->question,
            'status'     => $question->status,
            'created_by' => [
                'id'   => $user->id,
                'name' => $user->name,
            ],
            'created_at' => $question->created_at->format('Y-m-d h:i:s'),
            'updated_at' => $question->updated_at->format('Y-m-d h:i:s'),
        ],
    ]);
});
