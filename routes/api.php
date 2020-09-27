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
    Route::post('refresh', 'RefreshController@refreshJwtToken');

    Route::get('me', 'MeController@getUser');
});

Route::group(['prefix' => 'users', 'namespace' => 'User'], function () {
    // all users
    Route::get('/', 'UserController@findAllUsers');
});

Route::group(['prefix' => 'user', 'namespace' => 'User'], function () {

    // is friend?
    Route::get('{userId}/state', 'UserController@isFriend');

    // add a friend
    Route::post('{userId}/add', 'UserController@addFriend');

    // accept friendship request
    Route::post('{userId}/accept', 'UserController@acceptFriendship');

    // deny friendship request

    // unfriend a friendship

    // get all friends
    Route::get('friends', 'UserController@findAllFriends');
    // get all friendship requests (to user and from user)

    // test route
    Route::get('{userId}/hello', 'UserController@hello');
});

Route::group(['prefix' => 'prospect/week', 'namespace' => 'Prospective'], function () {
    // Test
    Route::post('goal/test/{id}', 'WeeklyGoalsController@updateCW');
    // Create
    Route::post('goal', 'WeeklyGoalsController@createGoal');
    // Update
    Route::post('goal/{id}', 'WeeklyGoalsController@updateGoal');
    // set to Done
    Route::post('goal/{id}/state/{state}', 'WeeklyGoalsController@setState');
    // Delete
    Route::post('goal/{id}/delete', 'WeeklyGoalsController@deleteGoal');
    // Read
    Route::get('{week_name}/goals', 'WeeklyGoalsController@showGoals');
});
