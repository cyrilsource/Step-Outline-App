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

/*
* Segment Api
*/
//tasks list
Route::get('tasks', 'TaskController@index');

//show one task
Route::get('task/{id}', 'TaskController@show');

//create new task
Route::post('task/{count}/{type}', 'TaskController@store');

//update order
Route::put('tasks', 'TaskController@updateOrder');

//edit a task
Route::put('task/{id}', 'TaskController@update');

//delete a task
Route::delete('task/{id}', 'TaskController@destroy');

//search a task
Route::get('findTask/{query?}', 'TaskController@search');


/*
* Color api
*/
//colors list
Route::get('colors', 'ColorController@index');

//show one task
Route::get('color/{id}', 'ColorController@show');

//create a new color
Route::post('color', 'ColorController@store');

//edit a color
Route::put('color/{id}', 'ColorController@update');

//delete a color
Route::delete('color/{id}', 'ColorController@destroy');


