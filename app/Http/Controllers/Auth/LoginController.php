<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        // $data = request()->validate([
        //     'email' =>  ['required', 'email'],
        //     'password'  =>  ['required'],
        // ]);
        // return response()->json($request->all());

        if (!auth()->attempt($request->only('email', 'password'))) {

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);

        }

        $request->session()->regenerate();

        return response()->noContent();
    }
}
