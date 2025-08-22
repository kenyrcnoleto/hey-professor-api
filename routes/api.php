<?php

use App\Http\Controllers\Question;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::get('/users', function () {
//     return User::all();
// });

Route::get('/', function () {
    return response()->json('Bem vindo a API Hey-Professor');
});

//region Authecnticated
Route::middleware('auth:sanctum')->group(function () {

    //region Questions
    Route::post('questions', Question\StoreController::class)->name('questions.store');
    Route::put('questions/{question}', Question\UpdateController::class)->name('questions.update');
    Route::delete('questions/{question}', Question\DeleteController::class)->name('questions.delete');
    Route::delete('questions/{question}/archive', Question\ArchiveController::class)->name('questions.archive');

    //endregion
});
//endregion
