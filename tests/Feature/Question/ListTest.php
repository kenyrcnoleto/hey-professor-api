<?php

use App\Models\{Question, User};
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\getJson;

test('it should be able to list only a published a question', function () {

    //Arrange
    Sanctum::actingAs(User::factory()->create());

    $published = Question::factory()->published()->create();
    $draft     = Question::factory()->draft()->create();

    //act
    $request = getJson(route('questions.index'))
                ->assertOk();

    //assert

    $request->assertJsonFragment([

        'id'         => $published->id,
        'question'   => $published->question,
        'status'     => $published->status,
        'created_by' => [
            'id'   => $published->user->id,
            'name' => $published->user->name,
        ],
        'created_at' => $published->created_at->format('Y-m-d h:i:s'),
        'updated_at' => $published->updated_at->format('Y-m-d h:i:s'),
        //TODO: add like and unlike count
    ])->assertJsonMissing([
        //'id' =>  $draft->id,
        'question' => $draft->question,
    ]);
});

test('it shoul be ablet to search for a question', function () {
    Sanctum::actingAs(User::factory()->create());

    $first = Question::factory()->published()->create([
        'question' => 'First question?',
    ]);
    $second = Question::factory()->published()->create([
        'question' => 'Second question?',
    ]);

    getJson(route('questions.index', ['q' => 'first']))
        ->assertOk()
        ->assertJsonFragment([
            //'id'       => $first->id,
            'question' => $first->question,
        ])->assertJsonMissing([
            //'id'       => $second->id,
            'question' => $second->question,
        ]);

    getJson(route('questions.index', ['q' => 'second']))
        ->assertOk()
        ->assertJsonFragment([
            //'id'       => $second->id,
            'question' => $second->question,
        ])->assertJsonMissing([
            //'id'       => $first->id,
            'question' => $first->question,
        ]);
});
