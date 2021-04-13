<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function (Request $request) {
    return "Api";
});


Route::group(['prefix' => 'auth'],function () {
    Route::post('login','AuthController@login');
    Route::post('register','AuthController@register');
    Route::get('logout', 'AuthController@logout');
});
/**
 * Authenticated routes
 */
Route::group(['middleware' => ['auth']], function () {
    /**
     * Posts
     */
    Route::group(['prefix' => 'posts'],function () {
        Route::get('/', 'PostController@index');
        Route::post('save', 'PostController@savePost');
        Route::get('detail/{postId}', 'PostController@getPostDetail');
    });
    /**
     * Comments
     */
    Route::group(['prefix' => 'comments'],function () {
        Route::get('/{postId}', 'CommentController@index');
        Route::post('add', 'CommentController@addComment');
    });
    /**
     * Profile
     */
    Route::group(['prefix' => 'profile'],function () {
        Route::get('/', 'AuthController@profile');
        Route::patch('update', 'AuthController@updateProfile');
    });
    /**
     * Dashboard
     */
    Route::group(['prefix' => 'dashboard'],function () {
        Route::get('/', 'DashboardController@index');
    });
    Route::group(['prefix' => 'favorites'],function () {
        Route::post('mark', 'PostController@markFavorite');
        Route::get('mine', 'PostController@getFavoritePosts');
    });
});
