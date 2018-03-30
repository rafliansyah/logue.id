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
Route::post('account/registrasi', 'Api\Auth\RegisterController@register');
Route::post('account/activate', 'Api\Auth\RegisterController@verifyUserCode');

Route::post('account/login', 'Api\Auth\AuthController@login');
Route::post('account/recover', 'Api\Auth\AuthController@recover');

Route::get('account/userProfile/{id}', 'Api\Auth\UserProfileController@userProfileEdit');
Route::post('account/userProfile/{id}', 'Api\Auth\UserProfileController@userProfileUpdate');

Route::post('account/createLomba', 'Lomba\LombaController@createLomba');


Route::group(['middleware' => ['jwt.auth']], function() {
    Route::get('logout', 'AuthController@logout');
});
