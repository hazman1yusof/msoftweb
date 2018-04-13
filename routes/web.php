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
Route::get('/util/save_table_default','UtilController@defaultSetter');
Route::get('/util/input_check','UtilController@input_check'); //jgn guna

//// menu mainatenance page ///
Route::get('/menu_maintenance','setup\MenuMaintenanceController@show');
Route::get('/menu_maintenance/table','setup\MenuMaintenanceController@table');
Route::post('/menu_maintenance/form','setup\MenuMaintenanceController@form');

//// group mainatenance page ///
Route::get('/group_maintenance','setup\GroupMaintenanceController@show');
Route::get('/group_maintenance/table','setup\GroupMaintenanceController@table');
Route::post('/group_maintenance/form','setup\GroupMaintenanceController@form');

//// user mainatenance page ///
Route::get('/user_maintenance','setup\UserMaintenanceController@show');
Route::get('/user_maintenance/table','setup\UserMaintenanceController@table');
Route::post('/user_maintenance/form','setup\UserMaintenanceController@form');

//// Religion setup page ///
Route::get('/religion','setup\ReligionController@show');
Route::get('/religion/table','setup\ReligionController@table');
Route::post('/religion/form','setup\ReligionController@form');

//// Race setup page ///
Route::get('/race','setup\RaceController@show');
Route::get('/race/table','setup\RaceController@table');
Route::post('/race/form','setup\RaceController@form');

//// Salutation setup page ///
Route::get('/salutation','setup\SalutationController@show');
Route::get('/salutation/table','setup\SalutationController@table');
Route::post('/salutation/form','setup\SalutationController@form');

//// relationship setup page ///
Route::get('/relationship','setup\RelationshipController@show');
Route::get('/relationship/table','setup\RelationshipController@table');
Route::post('/relationship/form','setup\RelationshipController@form');


//// billtype setup page ///
Route::get('/billtype','setup\BilltypeController@show');
Route::get('/billtype/table','setup\BilltypeController@table');
Route::post('/billtype/form','setup\BilltypeController@form');

//// marital setup page ///
Route::get('/marital','setup\MaritalController@show');
Route::get('/marital/table','setup\MaritalController@table');
Route::post('/marital/form','setup\MaritalController@form');

//// bloodgroup setup page ///
Route::get('/bloodGroup','setup\BloodGroupController@show');
Route::get('/bloodGroup/table','setup\BloodGroupController@table');
Route::post('/bloodGroup/form','setup\BloodGroupController@form');

//// citizen setup page ///
Route::get('/citizen','setup\CitizenController@show');
Route::get('/citizen/table','setup\CitizenController@table');
Route::post('/citizen/form','setup\CitizenController@form');

//// discipline setup page ///
Route::get('/discipline','setup\DisciplineController@show');
Route::get('/discipline/table','setup\DisciplineController@table');
Route::post('/discipline/form','setup\DisciplineController@form');

//// doctorStatus setup page ///
Route::get('/doctorStatus','setup\DoctorStatusController@show');
Route::get('/doctorStatus/table','setup\DoctorStatusController@table');
Route::post('/doctorStatus/form','setup\DoctorStatusController@form');

//// language setup page ///
Route::get('/language','setup\LanguageController@show');
Route::get('/language/table','setup\LanguageController@table');
Route::post('/language/form','setup\LanguageController@form');

//// Occupation setup page ///
Route::get('/occupation','setup\OccupationController@show');
Route::get('/occupation/table','setup\OccupationController@table');
Route::post('/occupation/form','setup\OccupationController@form');

//// speciality setup page ///
Route::get('/speciality','setup\SpecialityController@show');
Route::get('/speciality/table','setup\SpecialityController@table');
Route::post('/speciality/form','setup\SpecialityController@form');

//// Occupation setup page ///
Route::get('/area','setup\AreaController@show');
Route::get('/area/table','setup\AreaController@table');
Route::post('/area/form','setup\AreaController@form');

//// Compcode setup page ///
Route::get('/compcode','setup\CompcodeController@show');
Route::get('/compcode/table','setup\CompcodeController@table');
Route::post('/compcode/form','setup\CompcodeController@form');

//// Doctor setup page ///
Route::get('/doctor','setup\DoctorController@show');
Route::get('/doctor/table','setup\DoctorController@table');
Route::post('/doctor/form','setup\DoctorController@form');

//// Doctor setup page ///
Route::get('/receipt','finance\ReceiptController@show');
Route::get('/receipt/table','finance\ReceiptController@table');
Route::post('/receipt/form','finance\ReceiptController@form');

//// doctor_maintenance setup page ///
Route::get('/doctor_maintenance','hisdb\DoctorMaintenanceController@show');
Route::get('/doctor_maintenance/table','hisdb\DoctorMaintenanceController@table');
Route::post('/doctor_maintenance/form','hisdb\DoctorMaintenanceController@form');
Route::post('/doctor_maintenance/save_session','hisdb\DoctorMaintenanceController@save_session');
Route::post('/doctor_maintenance/save_bgleave','hisdb\DoctorMaintenanceController@save_bgleave');

//// purchase Order setup page ///
Route::get('/purchaseOrder','material\PurchaseOrderController@show');
Route::get('/purchaseOrder/table','material\PurchaseOrderController@table');
Route::post('/purchaseOrder/form','material\PurchaseOrderController@form');

//// delivery Order setup page ///
Route::get('/deliveryOrder','material\DeliveryOrderController@show');
Route::get('/deliveryOrder/table','material\DeliveryOrderController@table');
Route::post('/deliveryOrder/form','material\DeliveryOrderController@form');
Route::get('/deliveryOrder/form','material\DeliveryOrderController@form');
Route::post('/deliveryOrderDetail/form','material\DeliveryOrderDetailController@form');

//// appointment resource setup page ///
Route::get('/apptrsc','hisdb\AppointmentController@show');
Route::get('/apptrsc/table','hisdb\AppointmentController@table');
Route::post('/apptrsc/form','hisdb\AppointmentController@form');
Route::get('/apptrsc/getEvent','hisdb\AppointmentController@getEvent');
Route::post('/apptrsc/addEvent','hisdb\AppointmentController@addEvent');
Route::post('/apptrsc/editEvent','hisdb\AppointmentController@editEvent');
Route::post('/apptrsc/delEvent','hisdb\AppointmentController@delEvent');

//// debtortype ////
Route::get('/debtortype','finance\debtortypeController@show');
Route::get('/debtortype/table','finance\debtortypeController@table');
Route::post('/debtortype/form','finance\debtortypeController@form');

//// pat_mast registration ////
Route::get('/pat_mast','hisdb\PatmastController@show');
Route::get('/pat_mast/get_entry','hisdb\PatmastController@get_entry');
Route::post('/pat_mast/post_entry','hisdb\PatmastController@post_entry');
Route::post('/pat_mast/save_patient','hisdb\PatmastController@save_patient');

//// Emergency setup page ///
Route::get('/emergency','hisdb\EmergencyController@show');
Route::get('/emergency/table','hisdb\EmergencyController@table');
Route::post('/emergency/form','hisdb\EmergencyController@form');

//// Fixed Asset Location setup page ///
Route::get('/location','finance\LocationController@show');
Route::get('/location/table','finance\LocationController@table');
Route::post('/location/form','finance\LocationController@form');

//// Fixed Asset assettype setup page ///
Route::get('/assettype','finance\assettypeController@show');
Route::get('/assettype/table','finance\assettypeController@table');
Route::post('/assettype/form','finance\assettypeController@form');

