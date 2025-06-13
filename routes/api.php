<?php

use App\Http\Controllers\v1\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('v1/login', [AuthController::class, 'login'])->name('auth.login');

