<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

use Illuminate\Support\Facades\Auth;

Route::get('/', function ()
{
    return view('welcome');
});

Auth::routes();

//--------------------------------------------------------|
//           Social Media Authentication routes           |
//--------------------------------------------------------|
Route::get('/social/{provider}', 'Auth\SocialMediaController@redirectToProvider');
Route::get('/social/{provider}/callback', 'Auth\SocialMediaController@handleProviderCallback');

Route::get('/home', 'HomeController@index');
