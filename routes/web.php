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

Route::view('/', 'welcome');

// Auth Routes...
Auth::routes(['register' => false, 'verify' => true]);

// Subscription Routes...
Route::view('subscribe', 'auth.subscribe');
Route::post('subscribe', 'Auth\SubscribeController')->name('subscribe');

Route::get('home', 'HomeController@index')->name('home');

Route::middleware('auth')->group(function () {
    // Users
    Route::put('/users/{user}/personal-info-update', 'Users\UpdatePersonalInfoController')->name('users.personal-info-update');
    Route::put('/users/{user}/password-update', 'Users\UpdatePasswordController')->name('users.password-update');

    // Permissions
    Route::apiResource('permissions', 'PermissionController', ['only' => ['index']]);

    // Teams
    Route::view('settings/account', 'settings.account')->name('settings.account');
    Route::view('settings/team', 'settings.team')->name('settings.team');
    Route::apiResource('teams', 'TeamController', ['except' => ['store']]);
    Route::get('teams/{team}/switch', 'Teams\SwitchTeamController')->name('teams.switch');

    // Users
    Route::get('users/stop-impersonating', 'Admin\Users\ImpersonationController@stopImpersonating')
        ->name('users.stop-impersonating');

    // Speeches
    Route::get('speeches/get-from-conference', 'Speeches\GetSpeechesFromConferenceController')
        ->name('speeches.get-from-conference');
    Route::post('speeches/import-all', 'Speeches\ImportAllController')
        ->name('speeches.import-all');
    Route::resource('speeches', 'SpeechController');

    Route::get('users', 'UserController@index')->name('users.index');

    // Admin
    Route::middleware('admin')->group(function () {
        // Admin Routes
        Route::name('admin.')->prefix('admin')->namespace('Admin')->group(function () {
            // Teams
            Route::apiResource('teams', 'TeamController', ['only' => ['index', 'show']]);

            // Users
            Route::get('users/{user}/impersonate', 'Users\ImpersonationController@impersonate')
            ->name('users.impersonate');
            Route::put('users/{user}/update-permissions', 'Users\UpdatePermissionsController')
            ->name('users.update-permissions');
        });

        // Users
        Route::get('users/roles', 'Users\GetRolesController')->name('users.roles');
        Route::post('users/bulk-destroy', 'UserController@bulkDestroy')->name('users.bulk-destroy');
        Route::resource('users', 'UserController', ['except' => ['index']]);
    });
});
