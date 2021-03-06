<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;


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

//Register
Route::post('register', 'App\Http\Controllers\UserController@register');

//Login
Route::post('login', 'App\Http\Controllers\UserController@authenticate');

Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
        ? response()->json(['status' => __($status)], 200)
        : response()->json(['status' => __($status)], 400);
})->middleware('guest')->name('password.email');

Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:6|confirmed',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) use ($request) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->save();

            $user->setRememberToken(Str::random(60));

            event(new PasswordReset($user));
        }
    );

    return $status == Password::PASSWORD_RESET
        ? response()->json(['status' => __($status)], 200)
        : response()->json(['status' => __($status)], 400);
});

//Logout
Route::post('logout', 'App\Http\Controllers\UserController@logout');

//Check credentials
Route::post('check', 'App\Http\Controllers\UserController@checkCredentials');

Route::group(['middleware' => ['jwt.verify']], function () {
    Route::get('user', 'App\Http\Controllers\UserController@getAuthenticatedUser');
    Route::get('user/matches', 'App\Http\Controllers\UserController@matches');
    Route::post('user/pick', 'App\Http\Controllers\UserController@pick');
    Route::get('user/compatibles', 'App\Http\Controllers\UserController@getCompatibles');
    Route::post('user/uploadphoto', 'App\Http\Controllers\UserController@uploadPhoto');
    Route::get('user/image', 'App\Http\Controllers\UserController@image');
    Route::get('session', 'App\Http\Controllers\UserController@activeSession');

    //Movie Genders
    Route::get('movie-genders', 'App\Http\Controllers\MovieGenderController@index');
    Route::get('movie-genders/{gender}', 'App\Http\Controllers\MovieGenderController@show');
    Route::get('user/{user}/image', 'App\Http\Controllers\UserController@image');

    Route::post('movie-genders', 'App\Http\Controllers\MovieGenderController@store');
    Route::put('movie-genders/{gender}', 'App\Http\Controllers\MovieGenderController@update');
    Route::delete('movie-genders/{gender}', 'App\Http\Controllers\MovieGenderController@delete');

    //Music Genders
    Route::get('music-genders', 'App\Http\Controllers\MusicGenderController@index');
    Route::get('music-genders/{gender}', 'App\Http\Controllers\MusicGenderController@show');
    Route::post('music-genders', 'App\Http\Controllers\MusicGenderController@store');
    Route::put('music-genders/{gender}', 'App\Http\Controllers\MusicGenderController@update');
    Route::delete('music-genders/{gender}', 'App\Http\Controllers\MusicGenderController@delete');

    //Matches
    Route::get('matches', 'App\Http\Controllers\MatchController@index');
    Route::get('matches/{match}', 'App\Http\Controllers\MatchController@show');
    Route::post('matches', 'App\Http\Controllers\MatchController@store');
    Route::put('matches/{match}', 'App\Http\Controllers\MatchController@update');
    Route::delete('matches/{match}', 'App\Http\Controllers\MatchController@delete');

    //Messages
    Route::get('messages', 'App\Http\Controllers\MessageController@index');
    Route::get('messages/{message}', 'App\Http\Controllers\MessageController@show');
    Route::post('messages', 'App\Http\Controllers\MessageController@store');
    Route::put('messages/{message}', 'App\Http\Controllers\MessageController@update');
    Route::delete('messages/{message}', 'App\Http\Controllers\MessageController@delete');

    //Chats
    Route::get('chats', 'App\Http\Controllers\ChatController@index');
    Route::get('chats/{chat}', 'App\Http\Controllers\ChatController@show');
    Route::post('chats', 'App\Http\Controllers\ChatController@store');
    Route::put('chats/{chat}', 'App\Http\Controllers\ChatController@update');
    Route::delete('chats/{chat}', 'App\Http\Controllers\ChatController@delete');

    //Favorites
    Route::get('favorites', 'App\Http\Controllers\FavoriteController@index');
    Route::get('favorites/{favorite}', 'App\Http\Controllers\FavoriteController@show');
    Route::post('favorites', 'App\Http\Controllers\FavoriteController@store');
    Route::put('favorites/{favorite}', 'App\Http\Controllers\FavoriteController@update');
    Route::delete('favorites/{favorite}', 'App\Http\Controllers\FavoriteController@delete');
});




