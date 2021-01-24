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

//business
Route::post('register', 'App\Http\Controllers\UserController@register');
Route::post('login', 'App\Http\Controllers\UserController@authenticate');

Route::group(['middleware' => ['jwt.verify']], function () {
    Route::get('user', 'App\Http\Controllers\UserController@getAuthenticatedUser');
    Route::get('movie-genders', 'App\Http\Controllers\MovieGenderController@index');
    Route::get('movie-genders/{gender}', 'App\Http\Controllers\MovieGenderController@show');
    Route::get('user/{user}/image', 'App\Http\Controllers\UserController@image');

    Route::post('movie-genders', 'App\Http\Controllers\MovieGenderController@store');
    Route::put('movie-genders/{gender}', 'App\Http\Controllers\MovieGenderController@update');
    Route::delete('movie-genders/{gender}', 'App\Http\Controllers\MovieGenderController@delete');

    Route::get('music-genders', 'App\Http\Controllers\MusicGenderController@index');
    Route::get('music-genders/{gender}', 'App\Http\Controllers\MusicGenderController@show');
    Route::post('music-genders', 'App\Http\Controllers\MusicGenderController@store');
    Route::put('music-genders/{gender}', 'App\Http\Controllers\MusicGenderController@update');
    Route::delete('music-genders/{gender}', 'App\Http\Controllers\MusicGenderController@delete');

    Route::get('matches', 'App\Http\Controllers\MatchController@index');
    Route::get('matches/{match}', 'App\Http\Controllers\MatchController@show');
    Route::post('matches', 'App\Http\Controllers\MatchController@store');
    Route::put('matches/{match}', 'App\Http\Controllers\MatchController@update');
    Route::delete('matches/{match}', 'App\Http\Controllers\MatchController@delete');

    Route::get('messages', 'App\Http\Controllers\MessageController@index');
    Route::get('messages/{message}', 'App\Http\Controllers\MessageController@show');
    Route::post('messages', 'App\Http\Controllers\MessageController@store');
    Route::put('messages/{message}', 'App\Http\Controllers\MessageController@update');
    Route::delete('messages/{message}', 'App\Http\Controllers\MessageController@delete');

    Route::get('chats', 'App\Http\Controllers\ChatController@index');
    Route::get('chats/{chat}', 'App\Http\Controllers\ChatController@show');
    Route::post('chats', 'App\Http\Controllers\ChatController@store');
    Route::put('chats/{chat}', 'App\Http\Controllers\ChatController@update');
    Route::delete('chats/{chat}', 'App\Http\Controllers\ChatController@delete');

    Route::get('favorites', 'App\Http\Controllers\FavoriteController@index');
    Route::get('favorites/{favorite}', 'App\Http\Controllers\FavoriteController@show');
    Route::post('favorites', 'App\Http\Controllers\FavoriteController@store');
    Route::put('favorites/{favorite}', 'App\Http\Controllers\FavoriteController@update');
    Route::delete('favorites/{favorite}', 'App\Http\Controllers\FavoriteController@delete');
});




