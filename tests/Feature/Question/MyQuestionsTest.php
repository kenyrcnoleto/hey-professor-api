<?php

use App\Models\{Question, User};
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\getJson;

test('it should list only questions that the logged user has been created :: published', function () {
    $user = User::factory()->create();

    Sanctum::actingAs($user);
    $userQuestion        = Question::factory()->published()->for($user)->create();
    $anotherUserQuestion = Question::factory()->published()->create();

    //dd($userQuestion);
    //my-questions/{status}

    $request = getJson(route('my-questions', ['status' => 'published']))
        ->assertOk();

    $request->assertJsonFragment([

        'id'         => $userQuestion->id,
        'question'   => $userQuestion->question,
        'status'     => $userQuestion->status,
        'created_by' => [
            'id'   => $userQuestion->user->id,
            'name' => $userQuestion->user->name,
        ],
        'created_at' => $userQuestion->created_at->format('Y-m-d h:i:s'),
        'updated_at' => $userQuestion->updated_at->format('Y-m-d h:i:s'),
        //TODO: add like and unlike count
    ])->assertJsonMissing([
        //'id' =>  $draft->id,
        'question' => $anotherUserQuestion->question,
    ]);

});
