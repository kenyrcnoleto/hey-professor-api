<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuestionResource;
use App\Models\Question;
use Illuminate\Database\Eloquent\Builder;

class IndexController extends Controller
{
    public function __invoke()
    {
        $search = request()->q;

        $questions = Question::query()
                //->where('status', '=', 'published')
                ->published()
                //->when($search, fn(Builder $query) => $query->where('question', 'like', '%' . $search . '%'))
                ->search($search)
                ->get();

        return QuestionResource::collection($questions);
    }
}
