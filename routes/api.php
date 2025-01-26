<?php
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\EnrollmentController;

// Course endpoints
Route::get('/courses', [CourseController::class, 'index']);
Route::get('/courses/{course}', [CourseController::class, 'show']);

// Enrollment endpoints  
Route::get('/enrollments', [EnrollmentController::class, 'index']);
Route::get('/enrollments/{enrollment}', [EnrollmentController::class, 'show']);