<?php

use App\Models\{Question, User};
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\{assertDatabaseHas, postJson};

test('it should be able to like a question', function () {
    $user = Sanctum::actingAs(User::factory()->create());

    $question = Question::factory()->published()->create();

    postJson(route('question.vote', [
        'question' => $question,
        'vote'     => 'like',
    ]))->assertNoContent();

    expect($question)
        ->votes()->count()->toBe(1);

    assertDatabaseHas('votes', [
        'question_id' => $question->id,
        'user_id'     => $user->id,
        'like'        => true,
    ]);
});

test('it should be able to unlike a question', function () {
    $user = Sanctum::actingAs(User::factory()->create());

    $question = Question::factory()->published()->create();

    postJson(route('question.vote', [
        'question' => $question,
        'vote'     => 'unlike',
    ]))->assertNoContent();

    expect($question)
        ->votes()->count()->toBe(1);

    assertDatabaseHas('votes', [
        'question_id' => $question->id,
        'user_id'     => $user->id,
        'unlike'      => true,
    ]);
});

test('it should guarentee that only the words like and unlike are been used to vote', function ($vote, $status) {
    $user = Sanctum::actingAs(User::factory()->create());

    $question = Question::factory()->published()->create();

    postJson(route('question.vote', [
        'question' => $question,
        'vote'     => $vote,
    ]))->assertStatus($status);

})->with([
    'like'    => ['like', 204],
    'unlike'  => ['unlike', 204],
    'invalid' => ['invalid', 422],
]);
