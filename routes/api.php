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

Route::get('/user', function (Request $request)
{
    return $request->user();
})->middleware('auth:api');

Route::group([
    'prefix' => '/v1',
], function ()
{
    //--------------------------------------------------------|
    //                  Authentication routes                 |
    //--------------------------------------------------------|
    Route::post('/login', 'Api\Auth\V1\LoginController@login');
    Route::get('/logout', 'Api\Auth\V1\LoginController@logout');
    Route::post('/register', 'Api\Auth\V1\RegisterController@register');
    Route::post('password/email', 'Api\Auth\V1\ForgotPasswordController@sendResetLinkEmail');

    //--------------------------------------------------------|
    //          Social media authentication routes            |
    //--------------------------------------------------------|
    Route::post('/social/{provider}', 'Api\Auth\V1\SocialMediaController@handleProviderCallback')
        ->where(['provider' => 'facebook']);

    //--------------------------------------------------------|
    //                        Suwar APIs                      |
    //--------------------------------------------------------|
    Route::group([
        'prefix'     => '/suwar',
        'middleware' => [
            'auth:api',
        ],
    ], function ()
    {
        Route::get('/', 'Api\V1\SuwarController@index');
        Route::get('/{model}', 'Api\V1\SuwarController@show');
    });
});
