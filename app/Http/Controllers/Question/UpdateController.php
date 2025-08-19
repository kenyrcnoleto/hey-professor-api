<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Http\Requests\Question\UpdateRequest;
use App\Http\Resources\QuestionResource;
use App\Models\Question;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(UpdateRequest $request, Question $question)
    {
        //dd($question);
        //$this->authorize('update', $question);

        $question->question = $request->question;
        $question->save();

        return QuestionResource::make($question);
    }
}
