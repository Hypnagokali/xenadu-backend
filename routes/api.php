<?php
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

Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function () {
    Route::post('login', 'LoginController@authenticate');
    Route::post('logout', 'LogoutController@destroyAll');

    Route::get('me', 'MeController@getUser');
});

Route::group(['prefix' => 'prospect/week', 'namespace' => 'Prospective'], function () {
    // Create
    Route::post('goal', 'WeeklyGoalsController@createGoal');
    // Update
    Route::post('goal/{id}', 'WeeklyGoalsController@updateGoal');
    // Delete
    Route::post('goal/{id}/delete', 'WeeklyGoalsController@deleteGoal');
    // Read
    Route::get('{week_name}/goals', 'WeeklyGoalsController@showGoals');
});
