<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\Request;

class RestoreController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(int $id)
    {

        //Forma de verificar todos os retornos API - Symfony\Component\HttpFoundation\Response

        $question = Question::onlyTrashed()->findOrFail($id);
        // dd($question);

        $this->authorize('restore', $question);

        $question->restore();

        return response()->noContent();
    }
}
