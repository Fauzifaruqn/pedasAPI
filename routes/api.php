<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1'], function () {
    Route::resource('keluhan', 'KeluhanController',[
        'except' => ['create','edit']
    ]);

    Route::resource('materi', 'MateriController',[
        'except' => ['create','edit']
    ]);

    Route::resource('keluhan/registration', 'RegisterController', [
        'only' => ['store','destroy']
    ]);

    Route::post('/login', [
        'uses' => 'AuthController@login'
    ]);

    Route::post('/register',[
        'uses' => 'AuthController@register'
    ]);

        // Route::post('login', 'Api\AuthController@login');
        // Route::post('register', 'Api\AuthController@register');
        Route::group(['middleware' => 'auth:api'], function(){
            Route::post('getUser', 'AuthController@getUser');
        });
});
