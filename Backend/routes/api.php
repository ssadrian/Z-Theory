<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RankingsController;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\TeachersController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('login')->group(function () {
    Route::post('/student', [AuthController::class, 'loginStudent']);
    Route::post('/teacher', [AuthController::class, 'loginTeacher']);
});

Route::prefix('register')->group(function () {
    Route::post('/student', [StudentsController::class, 'create']);
    Route::post('/teacher', [TeachersController::class, 'create']);
});

Route::prefix('student')->group(function () {
    Route::get('/all', [StudentsController::class, 'all']);
    Route::get('', [StudentsController::class, 'get']);

    Route::post('', [StudentsController::class, 'create']);
    Route::put('', [StudentsController::class, 'update']);

    Route::delete('', [StudentsController::class, 'delete']);
});

Route::prefix('teacher')->group(function () {
    Route::get('/all', [TeachersController::class, 'all']);
    Route::get('', [TeachersController::class, 'get']);

    Route::post('', [TeachersController::class, 'create']);
    Route::put('', [TeachersController::class, 'update']);

    Route::delete('', [TeachersController::class, 'delete']);
});

Route::prefix('ranking')->group(function () {
    Route::get('/all', [RankingsController::class, 'all']);
    Route::get('', [RankingsController::class, 'get']);

    Route::post('', [RankingsController::class, 'create']);
    Route::put('', [RankingsController::class, 'update']);

    Route::delete('', [RankingsController::class, 'delete']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn(Request $request) => $request->user());
});
