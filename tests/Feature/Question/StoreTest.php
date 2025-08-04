<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;
use PhpParser\Node\Expr\PostDec;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\postJson;
use function Pest\Laravel\assertDatabaseHas;

test('it should be albeto store a new question', function () {
    $user =  User::factory()->create();

    // actingAs($user);

    Sanctum::actingAs($user);

    postJson(route('questions.store', [
        'question' => 'Loren ipsun teste?'
    ]))->assertSuccessful();

    assertDatabaseHas('questions', [
        'user_id'  => $user->id,
        'question' => 'Loren ipsun teste?'
    ]);

});
