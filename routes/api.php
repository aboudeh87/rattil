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

    //--------------------------------------------------------|
    //                    Recitations APIs                    |
    //--------------------------------------------------------|
    Route::group([
        'prefix'     => '/recitations',
        'middleware' => [
//            'auth:api',
        ],
    ], function ()
    {
        Route::post('/', 'Api\V1\RecitationController@store');
        Route::get('/my', 'Api\V1\RecitationController@myRecitation');
        Route::get('/following', 'Api\V1\RecitationController@following');
        Route::get('/latest', 'Api\V1\RecitationController@latest');
        Route::get('/popular', 'Api\V1\RecitationController@popular');
        Route::post('/search', 'Api\V1\RecitationController@search');
        Route::get('/{model}', 'Api\V1\RecitationController@show');
    });

    //--------------------------------------------------------|
    //                      Profiles APIs                     |
    //--------------------------------------------------------|
    Route::group([
        'prefix'     => '/profiles',
        'middleware' => [
//            'auth:api',
        ],
    ], function ()
    {
        Route::post('/', 'Api\V1\ProfileController@update');
        Route::get('/{model?}', 'Api\V1\ProfileController@show');
        Route::post('/{model}/avatar', 'Api\V1\ProfileController@uploadAvatar');
        Route::delete('/{model}/avatar', 'Api\V1\ProfileController@uploadAvatar');
    });

    //--------------------------------------------------------|
    //                     Follow APIs                     |
    //--------------------------------------------------------|
    Route::group([
        'prefix'     => '/follows',
        'middleware' => [
//            'auth:api',
        ],
    ], function ()
    {
        Route::get('/{model}/followers', 'Api\V1\FollowersController@followers');
        Route::get('/{model}/following', 'Api\V1\FollowersController@following');
    });
});
