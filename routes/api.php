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

    // get all friends
    Route::get('friends', 'UserController@findAllFriends');

    // get all pending friendship requests (from user)
    Route::get('pendingfromme', 'UserController@findPendingRequestsFromMe');

    Route::get('pendingtome', 'UserController@findPendingRequestsToMe');

    // get all users who are not my friends and where is no outstanding request

    //find a user
    Route::get('{userId}', 'UserController@findUserById');

    // TEST for reward points
    Route::get('{userId}/points', 'UserController@rewardPoints');

    // is friend?
    Route::get('{userId}/state', 'UserController@isFriend');

    // add a friend
    Route::post('{userId}/add', 'UserController@addFriend');

    // accept friendship request
    Route::post('{userId}/accept', 'UserController@acceptFriendship');

    // deny friendship request
    Route::post('{userId}/deny', 'UserController@denyFriendship');

    // unfriend a friendship
    Route::post('{userId}/unfriend', 'UserController@deleteFriendship');

    /*
    * Goal Monitor Controller
    *
    */

    Route::get('{userId}/monitor/goals', 'GoalMonitorController@findGoalsFromUser');
    Route::get('{userId}/monitor/goals/{goalId}/comments', 'GoalMonitorController@commentsByUserIdAndGoalId');
    Route::post('{userId}/monitor/goals/{goalId}/comment/send', 'GoalMonitorController@commentSave');

    Route::post('{userId}/push/{goalId}', 'UserController@pushGoalFromUser');

});

Route::group(['prefix' => 'user', 'namespace' => 'User'], function () {

        // get all friends
    Route::get('friends', 'UserController@findAllFriends');

    // get all pending friendship requests (from user)
    Route::get('pendingfromme', 'UserController@findPendingRequestsFromMe');

    Route::get('pendingtome', 'UserController@findPendingRequestsToMe');

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
