<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('home', 'HomeController@index')->name('home');
    Route::view('atas', 'minutes.index')->name('minutes.index');
    // Route::get('minutes', 'Web\MinutesController@index')->name('minutes.index');
    Route::get('atas/next', 'Web\MinutesController@next')->name('minutes.next');
    Route::get('atas/prev', 'Web\MinutesController@prev')->name('minutes.prev');
    Route::get('atas/next-form', 'Web\MinutesController@nextForm')->name('minutes.next-form');
    Route::get('atas/prev-form', 'Web\MinutesController@prevForm')->name('minutes.prev-form');
    Route::get('atas/{minute}', 'Web\MinutesController@show')->name('minutes.show');
    Route::get('atas/{minute}/form', 'Web\MinutesController@form')->name('minutes.form');

    // Recipes
    Route::get('receitas', 'Web\RecipesController@index')->name('recipes.index');

    // Topics
    Route::view('topics', 'topics.agenda')->name('topics.index');

    // Admin
    Route::prefix('admin')->group(function () {
        // Users
        Route::view('users', 'admin.users.index')->name('admin.users.index');
        Route::view('users/new', 'admin.users.new')->name('admin.users.new');
        Route::get('users/{user}/edit', 'UserController@edit')->name('admin.users.edit');

        // Topics
        Route::view('topics', 'admin.topics.index')->name('admin.topics.index');
        Route::view('topics/new', 'admin.topics.new')->name('admin.topics.new');
        Route::get('topics/{topic}/edit', 'TopicController@edit')->name('admin.topics.edit');
    });
});
