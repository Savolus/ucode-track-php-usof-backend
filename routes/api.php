<?php

use Illuminate\Support\Facades\Route;

// Auth routes
Route::post('auth/register', 'App\Http\Controllers\AuthController@register');
Route::post('auth/login', 'App\Http\Controllers\AuthController@login');
Route::middleware('auth')->post('auth/logout', 'App\Http\Controllers\AuthController@logout');

// Users routes
Route::get('users', 'App\Http\Controllers\UserController@index');
Route::get('users/{id}', 'App\Http\Controllers\UserController@show');
Route::get('users/{id}/avatar', 'App\Http\Controllers\UserController@avatar_get');
Route::middleware('admin')->post('users', 'App\Http\Controllers\UserController@store');
Route::middleware('auth')->post('users/avatar', 'App\Http\Controllers\UserController@avatar_create');
Route::middleware('auth')->patch('users', 'App\Http\Controllers\UserController@update');
Route::middleware('admin')->delete('users/{id}', 'App\Http\Controllers\UserController@destroy');

// Posts routes
Route::get('posts', 'App\Http\Controllers\PostController@index');
Route::get('posts/{id}', 'App\Http\Controllers\PostController@show');
Route::middleware('auth')->post('posts', 'App\Http\Controllers\PostController@store');
Route::middleware('auth')->patch('posts/{id}', 'App\Http\Controllers\PostController@update');
Route::middleware('auth')->delete('posts/{id}', 'App\Http\Controllers\PostController@destroy');

// Categories routes
Route::get('categories', 'App\Http\Controllers\CategoryController@index');
Route::get('categories/{id}', 'App\Http\Controllers\CategoryController@show');
Route::get('categories/{id}/posts', 'App\Http\Controllers\CategoryController@posts');
Route::middleware('admin')->post('categories', 'App\Http\Controllers\CategoryController@store');
Route::middleware('admin')->patch('categories/{id}', 'App\Http\Controllers\CategoryController@update');
Route::middleware('admin')->delete('categories/{id}', 'App\Http\Controllers\CategoryController@destroy');
