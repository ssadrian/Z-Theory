<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RankingsController;
use App\Http\Controllers\StudentController;
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
    Route::post('/student', [StudentsController::class, 'store']);
    Route::post('/teacher', [TeachersController::class, 'store']);
});

Route::apiResource('student', StudentsController::class);
Route::prefix('student')->group(function () {
    Route::post('password', [StudentsController::class, 'changePassword']);
});

Route::apiResource('teacher', TeachersController::class);
Route::prefix('teacher')->group(function () {
    Route::post('password', [TeachersController::class, 'changePassword']);
});

Route::apiResource('ranking', RankingsController::class);
Route::prefix('ranking')->group(function () {
    Route::get('for/{id}', [RankingsController::class, 'forStudent']);

    Route::post('created_by', [RankingsController::class, 'createdBy']);
    Route::post('assign', [RankingsController::class, 'assignStudent']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn(Request $request) => $request->user());
});
