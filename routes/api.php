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

    Route::get('miuser', 'App\Http\Controllers\UserController@getAuthenticatedUser');
    Route::get('user', 'App\Http\Controllers\UserController@index');
    Route::get('user/{user}', 'App\Http\Controllers\UserController@show');

    Route::get('user/{user}/movie-genders', 'App\Http\Controllers\MovieGenderController@index');
    //Route::get('user/{user}/movie-genders/{gender}', 'App\Http\Controllers\MovieGenderController@show');
    Route::post('user/{user}/movie-genders', 'App\Http\Controllers\MovieGenderController@store');
    Route::put('user/{user}/movie-genders/{gender}', 'App\Http\Controllers\MovieGenderController@update');
    Route::delete('user/{user}/movie-genders/{gender}', 'App\Http\Controllers\MovieGenderController@delete');

    Route::get('user/{user}/music-genders', 'App\Http\Controllers\MusicGenderController@index');
    Route::get('user/{user}/music-genders/{gender}', 'App\Http\Controllers\MusicGenderController@show');
    Route::post('user/{user}/music-genders', 'App\Http\Controllers\MusicGenderController@store');
    Route::put('user/{user}/music-genders/{gender}', 'App\Http\Controllers\MusicGenderController@update');
    Route::delete('user/{user}/music-genders/{gender}', 'App\Http\Controllers\MusicGenderController@delete');

    Route::get('miuser/matches', 'App\Http\Controllers\MatchController@index');
    Route::get('miuser/matches/{match}', 'App\Http\Controllers\MatchController@show');
    Route::post('miuser/matches', 'App\Http\Controllers\MatchController@store');
    //Route::put('miuser/matches/{match}', 'App\Http\Controllers\MatchController@update');
    Route::delete('miuser/matches/{match}', 'App\Http\Controllers\MatchController@delete');

    Route::get('miuser/chats/{chat}/messages', 'App\Http\Controllers\MessageController@index');
    //Route::get('miuser/chats/{chat}/messages/{message}', 'App\Http\Controllers\MessageController@show');
    Route::post('miuser/chats/{chat}/messages', 'App\Http\Controllers\MessageController@store');
    //Route::put('miuser/miuser/chats/{chat}/messages/{message}', 'App\Http\Controllers\MessageController@update');
    //Route::delete('miuser/miuser/chats/{chat}/messages/{message}', 'App\Http\Controllers\MessageController@delete');

    Route::get('miuser/chats', 'App\Http\Controllers\ChatController@index');
    Route::get('miuser/chats/{chat}', 'App\Http\Controllers\ChatController@show');
    Route::post('miuser/chats', 'App\Http\Controllers\ChatController@store');
    //Route::put('miuser/chats/{chat}', 'App\Http\Controllers\ChatController@update');
    Route::delete('miuser/chats/{chat}', 'App\Http\Controllers\ChatController@delete');

    Route::get('miuser/favorites', 'App\Http\Controllers\FavoriteController@index');
    Route::get('miuser/favorites/{favorite}', 'App\Http\Controllers\FavoriteController@show');
    Route::post('miuser/favorites', 'App\Http\Controllers\FavoriteController@store');
    //Route::put('miuser/favorites/{favorite}', 'App\Http\Controllers\FavoriteController@update');
    Route::delete('miuser/favorites/{favorite}', 'App\Http\Controllers\FavoriteController@delete');
});




