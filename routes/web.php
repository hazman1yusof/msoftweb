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
Route::get('/util/get_value_default','UtilController@get_value_default');
Route::get('/util/get_table_default','UtilController@get_table_default');
Route::get('/util/input_check','UtilController@input_check'); //jgn guna

//// menu mainatenance page ///
Route::get('/menu_maintenance','MenuMaintenanceController@show');
Route::get('/menu_maintenance/table','MenuMaintenanceController@table');
Route::post('/menu_maintenance/form','MenuMaintenanceController@form');

//// group mainatenance page ///
Route::get('/group_maintenance','GroupMaintenanceController@show');
Route::get('/group_maintenance/table','GroupMaintenanceController@table');
Route::post('/group_maintenance/form','GroupMaintenanceController@form');

//// user mainatenance page ///
Route::get('/user_maintenance','UserMaintenanceController@show');
Route::get('/user_maintenance/table','UserMaintenanceController@table');
Route::post('/user_maintenance/form','UserMaintenanceController@form');

//// Religion setup page ///
Route::get('/religion','ReligionController@show');
Route::get('/religion/table','ReligionController@table');
Route::post('/religion/form','ReligionController@form');

//// Religion setup page ///
Route::get('/race','RaceController@show');
Route::get('/race/table','RaceController@table');
Route::post('/race/form','RaceController@form');

