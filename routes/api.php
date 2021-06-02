<?php

use Illuminate\Support\Facades\Route;

// Auth routes
Route::post('auth/register', 'App\Http\Controllers\AuthController@register');
Route::post('auth/login', 'App\Http\Controllers\AuthController@login');
Route::middleware('auth')->post('auth/logout', 'App\Http\Controllers\AuthController@logout');
