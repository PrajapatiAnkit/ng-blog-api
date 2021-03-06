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
header('Access-Control-Allow-Origin:  *');
header('Access-Control-Allow-Methods:  POST, GET, OPTIONS, PUT,PATCH, DELETE');
header('Access-Control-Allow-Headers:  Content-Type, X-Auth-Token, x-xsrf-token, Origin, Authorization');

Route::get('/', function (Request $request) {
    \Illuminate\Support\Facades\Artisan::call('storage:link');
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
        Route::get('my-posts', 'PostController@getMyPosts');
        Route::post('save', 'PostController@savePost');
        Route::get('detail/{postId}', 'PostController@getPostDetail');
        Route::get('info/{postId}', 'PostController@getPostInfo');
        Route::post('change-status', 'PostController@changeStatus');
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
        Route::post('update', 'AuthController@updateProfile');
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
    /**
     * Categories
     */
    Route::group(['prefix' => 'categories'], function () {
        Route::get('/', 'CategoryController@index');
    });
});
