<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CoordinatesController;

Route::get('/v1/home', [CoordinatesController::class, 'get']);
Route::post('/v1/home', [CoordinatesController::class, 'post']);
Route::delete('/v1/home', [CoordinatesController::class, 'delete']);
