<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RankingsController;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\TeachersController;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

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
    Route::get('', [StudentsController::class, 'all']);
    Route::get('{id?}', [StudentsController::class, 'get']);

    Route::post('', [StudentsController::class, 'create']);
    Route::put('{id}', [StudentsController::class, 'update']);

    Route::delete('{id}', [StudentsController::class, 'delete']);
});

Route::prefix('teacher')->group(function () {
    Route::get('', [TeachersController::class, 'all']);
    Route::get('{id?}', [TeachersController::class, 'get']);

    Route::post('', [TeachersController::class, 'create']);
    Route::put('{id}', [TeachersController::class, 'update']);

    Route::delete('{id}', [TeachersController::class, 'delete']);
});

Route::prefix('ranking')->group(function () {
    Route::get('', [RankingsController::class, 'all']);
    Route::get('{id}', [RankingsController::class, 'get']);

    Route::post('', [RankingsController::class, 'create']);
    Route::put('{id}', [RankingsController::class, 'update']);

    Route::delete('{id}', [RankingsController::class, 'delete']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn(Request $request) => $request->user());
});
