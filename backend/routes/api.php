<?php

use App\Http\Controllers\API\AppointmentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ConsultationController;
use App\Http\Controllers\API\PublicController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/departments', [PublicController::class, 'getDepartments']);
Route::get('/doctors', [PublicController::class, 'getDoctors']);
Route::get('/doctors/{id}', [PublicController::class, 'getDoctorDetail']);
Route::get('/doctors/{id}/slots', [PublicController::class, 'getDoctorSlots']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/appointments', [AppointmentController::class, 'store']);
    Route::get('/my-appointments', [AppointmentController::class, 'getMyAppointments']);
    Route::post('/appointments/{id}/cancel', [AppointmentController::class, 'cancel']);
    Route::get('/doctor/appointments', [AppointmentController::class, 'getDoctorAppointments']);
    Route::patch('/appointments/{id}/status', [AppointmentController::class, 'updateStatus']);
    Route::post('/appointments/{id}/consultation', [ConsultationController::class, 'store']);
});
