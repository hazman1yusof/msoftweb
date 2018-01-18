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

//// Race setup page ///
Route::get('/race','RaceController@show');
Route::get('/race/table','RaceController@table');
Route::post('/race/form','RaceController@form');

//// Salutation setup page ///
Route::get('/salutation','SalutationController@show');
Route::get('/salutation/table','SalutationController@table');
Route::post('/salutation/form','SalutationController@form');

//// relationship setup page ///
Route::get('/relationship','RelationshipController@show');
Route::get('/relationship/table','RelationshipController@table');
Route::post('/relationship/form','RelationshipController@form');

//// billtype setup page ///
Route::get('/billtype','BilltypeController@show');
Route::get('/billtype/table','BilltypeController@table');
Route::post('/billtype/form','BilltypeController@form');

//// marital setup page ///
Route::get('/marital','MaritalController@show');
Route::get('/marital/table','MaritalController@table');
Route::post('/marital/form','MaritalController@form');

//// bloodgroup setup page ///
Route::get('/bloodGroup','BloodGroupController@show');
Route::get('/bloodGroup/table','BloodGroupController@table');
Route::post('/bloodGroup/form','BloodGroupController@form');

//// citizen setup page ///
Route::get('/citizen','CitizenController@show');
Route::get('/citizen/table','CitizenController@table');
Route::post('/citizen/form','CitizenController@form');

//// discipline setup page ///
Route::get('/discipline','DisciplineController@show');
Route::get('/discipline/table','DisciplineController@table');
Route::post('/discipline/form','DisciplineController@form');

//// doctorStatus setup page ///
Route::get('/doctorStatus','DoctorStatusController@show');
Route::get('/doctorStatus/table','DoctorStatusController@table');
Route::post('/doctorStatus/form','DoctorStatusController@form');

//// language setup page ///
Route::get('/language','LanguageController@show');
Route::get('/language/table','LanguageController@table');
Route::post('/language/form','LanguageController@form');

//// Occupation setup page ///
Route::get('/occupation','OccupationController@show');
Route::get('/occupation/table','OccupationController@table');
Route::post('/occupation/form','OccupationController@form');

//// speciality setup page ///
Route::get('/speciality','SpecialityController@show');
Route::get('/speciality/table','SpecialityController@table');
Route::post('/speciality/form','SpecialityController@form');

//// Occupation setup page ///
Route::get('/area','AreaController@show');
Route::get('/area/table','AreaController@table');
Route::post('/area/form','AreaController@form');
