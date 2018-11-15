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

Route::name('api.')->middleware('auth:api')->group(function () {
    // Minutes Resource
    Route::resource('minutes', 'Api\MinutesController');

    // Recipes Routes
    Route::post('recipes/destroy-bulk', 'Api\RecipesController@destroyBulk')->name('recipes.destroy-bulk');
    Route::resource('recipes', 'Api\RecipesController', ['except' => ['edit', 'create']]);

    // Users Routes
    Route::post('users/{user}/restore', 'UserController@restore')->name('users.restore');
    Route::delete('users/{user}/force', 'UserController@forceDestroy')->name('users.force-destroy');
    Route::post('users/bulk-destroy', 'UserController@bulkDestroy')->name('users.bulk-destroy');
    Route::post('users/{user}', 'UserController@update')->name('users.update');
    Route::get('users/suggestions', 'UserController@suggestions')->name('users.suggestions');
    Route::resource('users', 'UserController', ['only' => ['store', 'destroy', 'index', 'show']]);

    // Topics Routes
    Route::post('topics/distribute', 'Topics\DistributeTopicsController')->name('topics.distribute');
    Route::post('topics/bulk-destroy', 'Api\TopicController@bulkDestroy')->name('topics.bulk-destroy');
    Route::get('topics/agenda', 'Api\TopicController@agenda')->name('topics.agenda');
    Route::resource('topics', 'Api\TopicController', ['except' => ['show']]);
});
