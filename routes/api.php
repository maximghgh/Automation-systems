<?php
use App\Http\Controllers\Api\CallControllers;

Route::post('/form-request', [CallControllers::class, 'call']);
