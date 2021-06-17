<?php

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

Route::post('login', 'PassportController@login');
Route::post('register', 'PassportController@register');

Route::get('team-list', 'TeamController@getTeamsListData');
Route::get('team', 'TeamController@getTeamData');

Route::get('player-list', 'PlayerController@getPlayersListData');
Route::get('player', 'PlayerController@getPlayerData');

Route::get('team-players', 'TeamController@getTeamPlayersListData');

Route::middleware('auth:api')->group(function () {
    Route::get('user', 'PassportController@details');

    //TEAM ROUTES
    Route::post('team', 'TeamController@addTeamData')->middleware('role:'.env('SUPER_ADMIN').','.env('SUB_ADMIN'));
    Route::put('team', 'TeamController@updateTeamData')->middleware('role:'.env('SUPER_ADMIN').','.env('SUB_ADMIN'));
    Route::delete('team', 'TeamController@deleteTeamData')->middleware('role:'.env('SUPER_ADMIN').','.env('SUB_ADMIN'));

    //Player ROUTES
    Route::post('player', 'PlayerController@addPlayerData')->middleware('role:'.env('SUPER_ADMIN').','.env('SUB_ADMIN'));
    Route::put('player', 'PlayerController@updatePlayerData')->middleware('role:'.env('SUPER_ADMIN').','.env('SUB_ADMIN'));
    Route::delete('player', 'PlayerController@deletePlayerData')->middleware('role:'.env('SUPER_ADMIN').','.env('SUB_ADMIN'));
    
});