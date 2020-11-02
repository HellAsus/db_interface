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

/* Route::get('check', 'ClientController@check');
Route::get('log', 'LogController@getLogs');
Route::post('pcr', 'PermissionController@createRole');
Route::post('pcp', 'PermissionController@createPermission');
Route::post('pdr', 'PermissionController@delRole');
Route::post('pdp', 'PermissionController@delPermission');
Route::post('pgip', 'PermissionController@givePermission');
Route::get('pgr', 'PermissionController@getRoles');
Route::get('user', 'UserController@getUsers');
Route::post('cuser', 'UserController@createUsers');
Route::delete('duser/{id}', 'UserController@deleteUsers');  */


Route::group(['middleware' => 'api','prefix' => 'auth'],function () {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');

});

 Route::group(['middleware' => 'auth:api'], function(){
    Route::group(['middleware' => ['role:admin']], function () {
        Route::get('log', 'LogController@getLogs');
        Route::post('pcr', 'PermissionController@createRole');
        Route::post('pcp', 'PermissionController@createPermission');
        Route::post('pdr', 'PermissionController@delRole');
        Route::post('pdp', 'PermissionController@delPermission');
        Route::post('pgip', 'PermissionController@givePermission');
        Route::get('pgr', 'PermissionController@getRoles');
        Route::get('user', 'UserController@getUsers');
        Route::post('cuser', 'UserController@createUsers');
        Route::delete('duser/{id}', 'UserController@deleteUsers');
    });
    
    Route::get('check', 'ClientController@check');
    Route::post('check', 'ClientController@check');
    
  }); 
