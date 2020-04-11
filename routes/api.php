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
Route::get('failed', 'Api\AuthController@failed')->name('failed');
Route::get('email_verified/{token}', 'Api\AuthController@email_verified')->name('email_verified');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix('v1')->group(function(){
    Route::post('login', 'Api\AuthController@login');
    Route::post('register', 'Api\AuthController@register');
    Route::group(['middleware' => 'auth:api'], function(){
        Route::post('getUser', 'Api\AuthController@getUser');
        //profile user
        Route::get('profile-user/{encode}', 'Api\profile_user@index_profile_user');
        Route::post('create-profile-user', 'Api\profile_user@create_profile_user');
        Route::post('update-profile-user', 'Api\profile_user@update_profile_user');
        Route::post('delete-profile-user/{encode}', 'Api\profile_user@delete_user_profile');
        //newsfeed
        Route::get('newsfeed/{id_user}', 'Api\NewsfeedController@index_newsfeed');
        Route::post('create-newsfeed', 'Api\NewsfeedController@create_newsfeed');
        Route::post('update-newsfeed', 'Api\NewsfeedController@update_newsfeed');
        Route::post('delete-newsfeed', 'Api\NewsfeedController@delete_newsfeed');
    });
});