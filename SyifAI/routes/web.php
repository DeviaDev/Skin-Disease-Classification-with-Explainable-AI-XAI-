<?php

use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\SkinDetectorController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SkinDetectorController::class, 'home'])->name('home');
Route::post('/predict', [SkinDetectorController::class, 'predict'])->name('skin-detector.predict');

Route::get('/template', [SkinDetectorController::class, 'template'])->name('template');
Route::get('/detection', [SkinDetectorController::class, 'detection'])->name('detection');
Route::get('/diseases', [SkinDetectorController::class, 'diseases'])->name('diseases');


Route::post('/feedback', [FeedbackController::class,'store'])
    ->name('feedback.store');