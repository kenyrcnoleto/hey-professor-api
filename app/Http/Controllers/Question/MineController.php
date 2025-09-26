<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuestionResource;
use App\Models\Question;
use Illuminate\Database\Eloquent\Builder;
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

        // $question = Question::query()
        //     ->whereUserId(auth()->id())
        $question = user()
            ->questions()
            ->when(
                $status === 'archived',
                fn (Builder $query) => $query->onlyTrashed(),
                fn (Builder $query) => $query->where('status', '=', $status),
            )
            // ->withTrashed()
            ->get();

        return QuestionResource::collection($question);
    }
}
