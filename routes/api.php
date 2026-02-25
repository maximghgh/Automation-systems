<?php

use App\Http\Controllers\Api\CallControllers;
use App\Http\Controllers\Api\OrderController;
use Illuminate\Support\Facades\Route;

Route::post('/form-request', [CallControllers::class, 'call']);
Route::post('/order-request', [OrderController::class, 'submitForm']);
