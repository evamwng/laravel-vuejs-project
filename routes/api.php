<?php

use Illuminate\Support\Facades\Route;

    Route::post('/register', 'App\Http\Controllers\Auth\RegisterController@register');
    Route::post('/login', 'App\Http\Controllers\Auth\LoginController@login');

    Route::get('/socialite/{provider}', 'App\Http\Controllers\Auth\SocialiteController@redirectToProvider');
    Route::get('/socialite/{provider}/callback', 'App\Http\Controllers\Auth\SocialiteController@handleProviderCallback');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', 'App\Http\Controllers\Auth\LoginController@logout');
        Route::resource('tasks', 'App\Http\Controllers\TasksController');
        Route::post('/tasks/search', ['as' => 'search', 'uses' => 'App\Http\Controllers\TasksController@search']);
        Route::post('/tasks/complete', ['as' => 'complete', 'uses' => 'App\Http\Controllers\TasksController@complete']);
        Route::post('/tasks/pin', ['as' => 'pinned', 'uses' => 'App\Http\Controllers\TasksController@pinned']);
        Route::get('/tasks/delete/{id}', ['as' => 'delete', 'uses' => 'App\Http\Controllers\TasksController@destroy']);
        Route::post('/tasks/update', ['as' => 'update', 'uses' => 'App\Http\Controllers\TasksController@update']);
    });
