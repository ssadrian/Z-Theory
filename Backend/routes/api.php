<?php

use App\{
    Http\Controllers\AssignmentController,
    Http\Controllers\AuthController,
    Http\Controllers\RankingsController,
    Http\Controllers\StudentsController,
    Http\Controllers\TeachersController,
    Http\Controllers\EvaluationController,
    Http\Controllers\EvaluationHistoryController
};
use Illuminate\{Support\Facades\Request, Support\Facades\Route};

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

Route::middleware('auth:sanctum')
    ->apiResource('student', StudentsController::class);

Route::prefix('student')->group(function () {
    Route::get('{studentId}/logout', [AuthController::class, 'logoutStudent']);
    Route::post('login', [AuthController::class, 'loginStudent']);
    Route::post('register', [StudentsController::class, 'store']);

    Route::post('password', [StudentsController::class, 'changePassword']);
    Route::post('give', [EvaluationController::class, 'store']);
});

Route::apiResource('evaluation', EvaluationController::class)
    ->only([ 'store', 'destroy']);

Route::apiResource('evaluation_history', EvaluationHistoryController::class)
    ->except([ 'update', 'destroy' ]);
Route::prefix('evaluation_history')->group(function () {
    Route::get('for_teacher/{teacherId}', [EvaluationHistoryController::class, 'forTeacher']);
});

Route::apiResource('teacher', TeachersController::class);
Route::prefix('teacher')->group(function () {
    Route::get('{teacherId}/logout', [AuthController::class, 'logoutTeacher']);
    Route::post('login', [AuthController::class, 'loginTeacher']);
    Route::post('register', [TeachersController::class, 'store']);

    Route::post('password', [TeachersController::class, 'changePassword']);
});

Route::apiResource('ranking', RankingsController::class);
Route::prefix('ranking')->group(function () {
    Route::get('for/{studentId}', [RankingsController::class, 'forStudent']);
    Route::get('created_by/{teacherId}', [RankingsController::class, 'createdBy']);
    Route::get('queues/for/{teacherId}', [RankingsController::class, 'queuesForTeacher']);

    Route::post('assign/{studentId}', [RankingsController::class, 'assignStudent']);
    Route::post('accept/{studentId}', [RankingsController::class, 'acceptStudent']);
    Route::post('decline/{studentId}', [RankingsController::class, 'declineStudent']);

    Route::put('{code}/for/{studentId}', [RankingsController::class, 'updateForStudent']);
});

Route::apiResource('assignment', AssignmentController::class);
Route::prefix('assignment')->group(function () {
    Route::get('creator/{teacherId}', [AssignmentController::class, 'createdBy']);
    Route::get('{id}/remove/ranking/{rankCode}', [AssignmentController::class, 'removeFromRanking']);

    Route::post('assign/ranking/{rankCode}', [AssignmentController::class, 'assignToRanking']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn(Request $request) => $request->user());
});
