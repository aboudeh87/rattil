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
        Route::get('/following', 'Api\V1\RecitationController@following');
        Route::get('/latest', 'Api\V1\RecitationController@latest');
        Route::get('/popular', 'Api\V1\RecitationController@popular');
        Route::post('/search', 'Api\V1\RecitationController@search');
        Route::get('/list/{model?}', 'Api\V1\RecitationController@recitations');
        Route::post('/{model}', 'Api\V1\RecitationController@update');
        Route::get('/{model}', 'Api\V1\RecitationController@show')->middleware([
            \App\Http\Middleware\DisabledRecitation::class,
            \App\Http\Middleware\IsAllowedToSeePrivate::class . ':api',
        ]);
    });

    //--------------------------------------------------------|
    //                      Comments APIs                     |
    //--------------------------------------------------------|
    Route::group([
        'middleware' => [
//            'auth:api',
        ],
    ], function ()
    {
        Route::post('/recitations/{model}/comment', 'Api\V1\CommentController@store')
            ->middleware([
                \App\Http\Middleware\DisabledRecitation::class,
                \App\Http\Middleware\IsAllowedToSeePrivate::class . ':api',
            ]);

        Route::delete('/comments/{model}', 'Api\V1\CommentController@destroy')
            ->middleware([
                \App\Http\Middleware\IsOwner::class . ':model,api',
            ]);
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
        'middleware' => [
//            'auth:api',
        ],
    ], function ()
    {
        Route::post('/follows/{model}', 'Api\V1\FollowersController@follow');
        Route::delete('/follows/{model}', 'Api\V1\FollowersController@unfollow');
        Route::delete('/follows/{model}/follower', 'Api\V1\FollowersController@deleteFollower');
        Route::get('/profiles/{model}/followers', 'Api\V1\FollowersController@followers');
        Route::get('/profiles/{model}/following', 'Api\V1\FollowersController@following');
    });

    //--------------------------------------------------------|
    //                     Favorites APIs                     |
    //--------------------------------------------------------|
    Route::group([
        'middleware' => [
//            'auth:api',
        ],
    ], function ()
    {
        Route::post('/favorites/{model}', 'Api\V1\FavoritesController@favorite');
        Route::delete('/favorites/{model}', 'Api\V1\FavoritesController@unfavorite');
        Route::get('/profiles/{model}/favorites', 'Api\V1\FavoritesController@favorites');
    });
});
