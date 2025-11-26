<?php

use App\Http\Controllers\API\AttendanceController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ClassroomController;
use App\Http\Controllers\API\LessonController;
use App\Http\Controllers\API\PingController;
use App\Http\Controllers\API\ProgressController;
use App\Http\Controllers\API\RecommendationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Swagger documentation route
Route::get('/documentation', function () {
    return view('vendor.l5-swagger.index');
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::apiResource('classrooms', ClassroomController::class);
    Route::get('classrooms/{classroom}/lessons', [LessonController::class, 'getLessonsByClassroom']);
    Route::apiResource('lessons', LessonController::class);
    Route::post('progress/update', [ProgressController::class, 'update']);
    Route::get('progress/{userId}', [ProgressController::class, 'getByUser']);
    Route::post('attendance/checkin', [AttendanceController::class, 'checkin']);
    Route::get('attendance/{classroomId}/{userId}', [AttendanceController::class, 'getByClassroomAndUser']);
    Route::get('recommendation/{userId}', [RecommendationController::class, 'getRecommendations']);
});

Route::get('/ping', [PingController::class, 'index']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
