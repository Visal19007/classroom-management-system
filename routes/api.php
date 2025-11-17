<?php

use App\Http\Controllers\Api\ClassroomController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\GradeController;
use App\Http\Controllers\Api\NoticeController;
use Illuminate\Support\Facades\Route;

// Route::middleware('auth:sanctum')->group(function(){
    Route::apiResource('classrooms', ClassroomController::class);
    Route::apiResource('students', StudentController::class);
    Route::post('students/{student}/enroll',[StudentController::class,'enroll']);
    Route::post('students/{student}/unenroll',[StudentController::class,'unenroll']);

    Route::post('attendance/bulk',[AttendanceController::class,'storeBulk']);
    Route::get('attendance/class/{classroom}/{date?}',[AttendanceController::class,'getByClassAndDate']);
    Route::get('attendance/report/{classroom}',[AttendanceController::class,'report']);

    Route::post('grades',[GradeController::class,'storeOrUpdate']);
    Route::get('grades/class/{classroom}',[GradeController::class,'getClassGradebook']);

    Route::apiResource('notices', NoticeController::class)->only(['index','store','show','destroy']);
// });

