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


// ------------------- User Controller ---------------

 Route::post('create-user', 'UserController@createUser');

 Route::post('user-login', 'UserController@userLogin');

//  ----------------- Auth Tokens --------------

Route::group(['middleware' => 'auth:api'], function () {

    Route::get('user-detail', 'UserController@userDetail');
    
});