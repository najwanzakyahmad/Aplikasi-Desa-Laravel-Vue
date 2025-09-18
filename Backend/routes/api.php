<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::apiResource('user', UserController::class);
Route::get('user/all/paginated', [UserController::class, 'getAllPaginated']);
