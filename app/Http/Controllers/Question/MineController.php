<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuestionResource;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MineController extends Controller
{
    public function __invoke()
    {
        $status = request()->status;

        Validator::validate(
            ['status' => $status],
            ['status' => ['required', 'in:draft,published,archived']],
        );
        // dd($status);
        // request()->validate([
        //     'status'    =>  ['required', 'in:draft,published,archived'],
        // ]);

        $question = Question::query()
            ->whereUserId(auth()->id())
            ->where('status', '=', $status)
            ->get();

        return QuestionResource::collection($question);
    }
}
