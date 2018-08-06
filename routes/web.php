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
    Route::get('atas/{minute}', 'Web\MinutesController@show')->name('minutes.show');
});
