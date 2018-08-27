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
});
