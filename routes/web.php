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

Route::get('/','HomeController@index')->name('home');
Route::get('/login','SessionController@create')->name('login');
Route::post('/login','SessionController@store');
Route::get('/logout','SessionController@destroy')->name('logout');


/// Utility function ///
Route::get('/util/getcompid','UtilController@getcompid');
Route::get('/util/getpadlen','UtilController@getpadlen');


//// menu mainatenance page ///
Route::get('/menu_maintenance','MenuMaintenanceController@show');
Route::get('/menu_maintenance/table','MenuMaintenanceController@table');
Route::post('/menu_maintenance/form','MenuMaintenanceController@form');


//// Religion setup page ///
Route::get('/religion','ReligionController@show');
Route::get('/religion/table','ReligionController@table');
Route::post('/religion/form','ReligionController@form');
