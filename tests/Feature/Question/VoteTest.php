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
