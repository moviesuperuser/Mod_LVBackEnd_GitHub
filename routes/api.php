<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
URL::forceScheme('https');

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
Route::group(
  [
    'middleware' => 'api',
    'namespace'  => 'App\Http\Controllers',
    'prefix'     => 'auth',
  ],
  function ($router) {
    Route::post('login', 'AuthController@login')->name('login');
    Route::post('register', 'AuthController@register')->name('register');
    Route::post('logout', 'AuthController@logout')->name('logout');
  }
);
Route::group(
  [
    'middleware' => 'api',
    'namespace'  => 'App\Http\Controllers',
    'prefix'     => 'movie',
  ],
  function ($router) {
    Route::get('ShowMovieDetail/{slug}', 'MovieController@ShowMovieDetail')->name('ShowMovieDetail');
    Route::get('showMovie', 'MovieController@showMovie')->name('showMovie');
    Route::post('editMovie', 'MovieController@editMovie')->name('editMovie');
  }
);
Route::group(
  [
    'middleware' => 'api',
    'namespace'  => 'App\Http\Controllers',
    'prefix'     => 'genre',
  ],
  function ($router) {
    // Route::get('ShowMovieDetail/{slug}', 'MovieController@ShowMovieDetail')->name('ShowMovieDetail');
    Route::get('showGenre', 'GenreController@showGenre')->name('showGenre');
    Route::post('editGenre', 'GenreController@editGenre')->name('editGenre');
    Route::get('deleteGenre', 'GenreController@deleteGenre')->name('deleteGenre');
    Route::post('createGenre', 'GenreController@createGenre')->name('createGenre');
  }
);
Route::group(
  [
    'middleware' => 'api',
    'namespace'  => 'App\Http\Controllers',
    'prefix'     => 'collection',
  ],
  function ($router) {
    // Route::get('ShowMovieDetail/{slug}', 'MovieController@ShowMovieDetail')->name('ShowMovieDetail');
    Route::get('showCollection', 'CollectionController@showCollection')->name('showCollection');
    Route::post('editCollection', 'CollectionController@editCollection')->name('editCollection');
    Route::get('deleteCollection', 'CollectionController@deleteCollection')->name('deleteCollection');
    Route::post('createCollection', 'CollectionController@createCollection')->name('createCollection');
    
  }
);