<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => 'api'], function ($router) {

    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
    Route::post('forgotPassword', 'AuthController@forgotPassword');
    Route::post('resetUserPassword', 'AuthController@resetUserPassword');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');

    Route::group(['prefix' => 'users', 'middleware' => 'auth:api'], function ($router) {
        Route::get('', 'UsersController@all');
        Route::get('/{id}', 'UsersController@getById');
        Route::post('create', 'UsersController@create');
        Route::put('update/{userId}', 'UsersController@update');
        Route::delete('delete/{userId}', 'UsersController@delete');
    });

    Route::group(['prefix' => 'tasks', 'middleware' => 'auth:api'], function ($router) {
        Route::get('items', 'TasksListsItemsController@all');
        Route::get('items/{id}', 'TasksListsItemsController@getById');
        Route::get('items/getByHash/{hash}', 'TasksListsItemsController@getByHash');
        Route::post('items/create', 'TasksListsItemsController@create');
        Route::put('items/update/{hash}', 'TasksListsItemsController@update');
        Route::delete('items/delete/{hash}', 'TasksListsItemsController@delete');

        Route::get('', 'TasksListsController@all');
        Route::get('/{id}', 'TasksListsController@getById');
        Route::get('/getByHash/{hash}', 'TasksListsController@getByHash');
        Route::post('create', 'TasksListsController@create');
        Route::put('update/{hash}', 'TasksListsController@update');
        Route::delete('delete/{hash}', 'TasksListsController@delete');
    });

});