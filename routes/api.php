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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//tasks list
Route::get('tasks', 'TaskController@index');

//show one task
Route::get('task/{id}', 'TaskController@show');

//create new task
Route::post('task/{count}', 'TaskController@store');

//update order
Route::put('tasks', 'TaskController@updateOrder');

//edit a task
Route::put('task/{id}', 'TaskController@update');

//delete a task
Route::delete('task/{id}', 'TaskController@destroy');

//search a task
Route::get('findTask/{query?}', 'TaskController@search');
