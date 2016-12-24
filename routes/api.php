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
        'prefix' => '/recitations',
    ], function ()
    {
        Route::group([
            'middleware' => [
                'auth:api',
            ],
        ], function ()
        {
            Route::post('/', 'Api\V1\RecitationController@store');
            Route::get('/following', 'Api\V1\RecitationController@following');
            Route::get('/latest', 'Api\V1\RecitationController@latest');
            Route::get('/popular', 'Api\V1\RecitationController@popular');
            Route::post('/search', 'Api\V1\RecitationController@search');
            Route::get('/list/{model?}', 'Api\V1\RecitationController@recitations');
            Route::post('/{model}', 'Api\V1\RecitationController@update')->middleware([
                \App\Http\Middleware\DisabledRecitation::class,
                \App\Http\Middleware\IsOwner::class,
            ]);
            Route::get('/{model}', 'Api\V1\RecitationController@show')->middleware([
                \App\Http\Middleware\DisabledRecitation::class,
                \App\Http\Middleware\IsAllowedToSeePrivate::class . ':api',
            ]);
            Route::delete('/{model}', 'Api\V1\RecitationController@destroy')->middleware([
                \App\Http\Middleware\DisabledRecitation::class,
                \App\Http\Middleware\IsOwner::class,
            ]);
        });

        Route::post('/{model}/listen', 'Api\V1\RecitationController@listen')->middleware([
            \App\Http\Middleware\DisabledRecitation::class,
        ]);
    });

    //--------------------------------------------------------|
    //                      Comments APIs                     |
    //--------------------------------------------------------|
    Route::group([
        'middleware' => [
            'auth:api',
        ],
    ], function ()
    {
        Route::get('/recitations/{model}/comments', 'Api\V1\CommentController@index')
            ->middleware([
                \App\Http\Middleware\DisabledRecitation::class,
                \App\Http\Middleware\IsAllowedToSeePrivate::class . ':api',
            ]);
        Route::post('/recitations/{model}/comments', 'Api\V1\CommentController@store')
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
        'middleware' => [
            'auth:api',
        ],
    ], function ()
    {
        Route::post('/profiles/', 'Api\V1\ProfileController@update');
        Route::get('/profiles/{model?}', 'Api\V1\ProfileController@show');
        Route::post('/profiles/{model}/avatar', 'Api\V1\ProfileController@uploadAvatar');
        Route::delete('/profiles/{model}/avatar', 'Api\V1\ProfileController@deleteAvatar');
        Route::get('/users/search', 'Api\V1\ProfileController@search');
    });

    //--------------------------------------------------------|
    //                     Follow APIs                     |
    //--------------------------------------------------------|
    Route::group([
        'middleware' => [
            'auth:api',
        ],
    ], function ()
    {
        Route::post('/follows/{model}', 'Api\V1\FollowersController@follow');
        Route::delete('/follows/{model}', 'Api\V1\FollowersController@unfollow');
        Route::delete('/follows/{model}/follower', 'Api\V1\FollowersController@deleteFollower');
        Route::get('/profiles/{model}/followers', 'Api\V1\FollowersController@followers');
        Route::get('/profiles/{model}/followers/pending', 'Api\V1\FollowersController@pending');
        Route::get('/profiles/{model}/following', 'Api\V1\FollowersController@following');
    });

    //--------------------------------------------------------|
    //                     Favorites APIs                     |
    //--------------------------------------------------------|
    Route::group([
        'middleware' => [
            'auth:api',
        ],
    ], function ()
    {
        Route::post('/favorites/{model}', 'Api\V1\FavoritesController@favorite');
        Route::delete('/favorites/{model}', 'Api\V1\FavoritesController@unfavorite');
        Route::get('/profiles/{model}/favorites', 'Api\V1\FavoritesController@favorites');
    });

    //--------------------------------------------------------|
    //                      Reports APIs                      |
    //--------------------------------------------------------|
    Route::group([
        'middleware' => [
            'auth:api',
        ],
    ], function ()
    {
        Route::post('/{type}/{model}/report', 'Api\V1\ReportController@store')
            ->where([
                'type' => implode('|', array_keys(\App\Report::AVAILABLE_TYPES)),
            ]);
    });

    //--------------------------------------------------------|
    //                      Assists APIs                      |
    //--------------------------------------------------------|
    Route::group([
        'prefix'     => '/assists',
        'middleware' => [
            'auth:api',
        ],
    ], function ()
    {
        Route::get('/countries', 'Api\V1\AssistsController@countries');
        Route::get('/languages', 'Api\V1\AssistsController@languages');
        Route::get('/narrations', 'Api\V1\AssistsController@narrations');
        Route::get('/reasons/{type}', 'Api\V1\AssistsController@reasons')
            ->where([
                'type' => implode('|', array_keys(\App\Report::AVAILABLE_TYPES)),
            ]);
    });
});
