<?php

use Illuminate\Support\Facades\Route;

// Auth routes
Route::post('auth/register', 'App\Http\Controllers\AuthController@register');
Route::post('auth/login', 'App\Http\Controllers\AuthController@login');
Route::middleware('auth')->post('auth/logout', 'App\Http\Controllers\AuthController@logout');

// Users routes
Route::get('users', 'App\Http\Controllers\UserController@index');
Route::get('users/{id}', 'App\Http\Controllers\UserController@show');
Route::middleware('admin')->post('users', 'App\Http\Controllers\UserController@store');
Route::middleware('auth')->patch('users', 'App\Http\Controllers\UserController@update');
Route::middleware('admin')->delete('users/{id}', 'App\Http\Controllers\UserController@destroy');
Route::middleware('auth')->post('users/avatar', 'App\Http\Controllers\UserController@avatar');
