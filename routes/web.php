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
Route::any('{query}', 
  function() { return view('index'); })
  ->where('query', '.*');

//Route::get('/login', function () {return view('/auth/login');});

/* Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout');

Route::group(['middleware' => 'auth'], function(){
    Route::get('/{vue_capture?}', function () {
        return view('index');
      })->where('vue_capture', '[\/\w\.-]*');
}); 
 */


