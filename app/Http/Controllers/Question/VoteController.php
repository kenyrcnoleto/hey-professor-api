<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Models\Question;

class VoteController extends Controller
{
    public function __invoke(Question $question, string $vote)
    {
        // dd(compact('question', 'vote'));

        $question->votes()
            ->create([
                'user_id' => auth()->id(),
                $vote     => 1,
            ]);

        return response()->noContent();
    }
}
