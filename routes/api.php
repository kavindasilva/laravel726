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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('students', 'ItemController@getAll');
Route::get('students/{id}', 'ItemController@getStudent');
Route::post('students', 'ItemController@createStudent');
Route::put('students/{id}', 'ItemController@updateStudent');
Route::delete('students/{id}','ItemController@deleteStudent');
