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

Route::post('register', 'UserController@register');
Route::post('login', 'UserController@authenticate');
Route::get('open', 'ItemController@getAll');

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('user', 'UserController@getAuthenticatedUser');
    Route::get('closed', 'DataController@closed');
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('item', 'ItemController@getAll');
Route::get('item/{id}', 'ItemController@getById');
Route::post('item', 'ItemController@addNew');
Route::put('item/{id}', 'ItemController@updateItem');
Route::patch('item/{id}', 'ItemController@updateItem');
Route::delete('item/{id}','ItemController@deleteItem');

Route::get('bill', 'BillController@getAll');
Route::get('bill/{id}', 'BillController@getById');
Route::post('bill', 'BillController@addNew');
Route::put('bill/{id}', 'BillController@updateItem');
Route::patch('bill/{id}', 'BillController@updateItem');
Route::delete('bill/{id}','BillController@deleteItem');
