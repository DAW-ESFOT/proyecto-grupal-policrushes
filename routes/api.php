<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
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

//business
Route::get('users', 'UserController@index'); 
Route::get('users/{article}', 'UserController@show'); 
Route::post('users', 'UserController@store');
Route::put('users/{article}', 'UserController@update'); 
Route::delete('users/{article}', 'UserController@delete');
