<?php

use Illuminate\Support\Facades\Route;

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
Route::get('/home','HomeController@index');
Route::get('/util','HomeController@util');
Route::get('/ptcare','HomeController@ptcare');
Route::get('/primary_care','HomeController@primary_care');
Route::get('/clinic','HomeController@clinic');
Route::get('/dialysis','HomeController@dialysis');
Route::get('/implant','HomeController@implant');
Route::get('/khealth','HomeController@khealth');
Route::get('/warehouse','HomeController@warehouse');
Route::post('/sessionUnit','HomeController@changeSessionUnit');
Route::get('/login','SessionController@create')->name('login');
Route::get('/loginappt','SessionController@create2')->name('login2');
Route::get('/qrcode','SessionController@qrcode');
Route::post('/qrcode','SessionController@qrcode_prereg');
Route::post('/login','SessionController@store');
Route::get('/logout','SessionController@destroy')->name('logout');
Route::get('/mobile','HomeController@mobile')->name('mobile');

/// Utility function ///
Route::get('/util/getcompid','UtilController@getcompid');
Route::get('/util/getpadlen','UtilController@getpadlen');
Route::get('/util/get_value_default','UtilController@get_value_default');
Route::get('/util/get_table_default','UtilController@get_table_default');
Route::get('/util/save_table_default','UtilController@defaultSetter');
Route::get('/util/input_check','UtilController@input_check'); //jgn guna
Route::get('/util/mycard_read','util\MycardController@get_data');
Route::get('/util/mycard_read','util\MycardController@get_data');
Route::get('/mykadFP','util\MycardController@mykadFP');
Route::post('/mykadfp_store','util\MycardController@mykadfp_store');
Route::get('/get_mykad_local','util\MycardController@get_mykad_local');
Route::get('/save_mykad_local','util\MycardController@save_mykad_local');
Route::post('/save_mykad_local','util\MycardController@save_mykad_local');

/// announcement thingy ///
Route::get('/announcement/generate','setup\AnnouncementController@generate');

//// nocsrf page ///
// Route::get('/menu_maintenance','setup\MenuMaintenanceController@show');
// Route::get('/menu_maintenance/table','setup\MenuMaintenanceController@table');
Route::post('/nocsrf','setup\nocsrfController@form');

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
Route::get('/user_profile','setup\UserMaintenanceController@show_profile');
Route::get('/user_maintenance/table','setup\UserMaintenanceController@table');
Route::post('/user_maintenance/form','setup\UserMaintenanceController@form');
Route::get('/user_maintenance/showExcel','setup\UserMaintenanceController@showExcel');
Route::get('/user_maintenance/showpdf','setup\UserMaintenanceController@showpdf');

//// sysparam ////
Route::get('/sysparam_bed_status','SysparamController@sysparam_bed_status');
Route::get('/sysparam_stat','SysparamController@sysparam_stat');
Route::get('/sysparam_triage_color','SysparamController@sysparam_triage_color');
Route::get('/sysparam_triage_color_chk','SysparamController@sysparam_triage_color_chk');
Route::get('/sysparam_recstatus','SysparamController@sysparam_recstatus');

///////////////file setup//////////////////////////////////////////////////

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

//// icd setup page ///
Route::get('/icd','setup\icdController@show');
Route::get('/icd/table','setup\icdController@table');
Route::post('/icd/form','setup\icdController@form');

//// mma setup page ///
Route::get('/mma','setup\mmaController@show');
Route::get('/mma/table','setup\mmaController@table');
Route::post('/mma/form','setup\mmaController@form');
Route::post('/mmaDetail/form','setup\mmaDetailController@form');

//// mmamaintenance setup page ///
Route::get('/mmamaintenance','setup\mmamaintenanceController@show');
Route::get('/mmamaintenance/table','setup\mmamaintenanceController@table');
Route::post('/mmamaintenance/form','setup\mmamaintenanceController@form');
Route::post('/mmamaintenanceDetail/form','setup\mmamaintenanceDetailController@form');

//// speciality setup page ///
Route::get('/speciality','setup\SpecialityController@show');
Route::get('/speciality/table','setup\SpecialityController@table');
Route::post('/speciality/form','setup\SpecialityController@form');

//// Occupation setup page ///
Route::get('/area','setup\AreaController@show');
Route::get('/area/table','setup\AreaController@table');
Route::post('/area/form','setup\AreaController@form');

//// Charge master setup page ///
Route::get('/chargemaster','setup\ChargeMasterController@show');
Route::get('/chargemaster/table','setup\ChargeMasterController@table');
Route::post('/chargemaster/form','setup\ChargeMasterController@form');
Route::get('/chargemaster/form','setup\ChargeMasterController@form');
Route::get('/chargemaster/chgpricelatest','setup\ChargeMasterController@chgpricelatest');
Route::post('/chargemasterDetail/form','setup\ChargeMasterDetailController@form');
Route::get('/chargemaster/showExcel','setup\ChargeMasterController@showExcel');
Route::get('/chargemaster/showpdf','setup\ChargeMasterController@showpdf');
Route::get('/chargemaster/showExcelPkg','setup\ChargeMasterController@showExcelPkg');
Route::get('/chargemaster/showpdfPkg','setup\ChargeMasterController@showpdfPkg');

//// Charge class setup page ///
Route::get('/chargeclass','setup\ChargeClassController@show');
Route::get('/chargeclass/table','setup\ChargeClassController@table');
Route::post('/chargeclass/form','setup\ChargeClassController@form');
Route::get('/chargeclass/form','setup\ChargeClassController@form');
Route::post('/chargeclassDetail/form','setup\ChargeClassDetailController@form');

//// Charge type setup page ///
Route::get('/chargetype','setup\ChargeTypeController@show');
Route::get('/chargetype/table','setup\ChargeTypeController@table');
Route::post('/chargetype/form','setup\ChargeTypeController@form');
Route::get('/chargetype/form','setup\ChargeTypeController@form');
Route::post('/chargetypeDetail/form','setup\ChargeTypeDetailController@form');

//// Charge group setup page ///
Route::get('/chargegroup','setup\ChargeGroupController@show');
Route::get('/chargegroup/table','setup\ChargeGroupController@table');
Route::post('/chargegroup/form','setup\ChargeGroupController@form');
Route::get('/chargegroup/form','setup\ChargeGroupController@form');
Route::post('/chargegroupDetail/form','setup\ChargeGroupDetailController@form');

//// taxmast setup ///
Route::get('/taxmast','setup\TaxMastController@show');
Route::get('/taxmast/table','setup\TaxMastController@table');
Route::post('/taxmast/form','setup\TaxMastController@form');

//// Dosage setup ///
Route::get('/dosage','setup\DosageController@show');
Route::get('/dosage/table','setup\DosageController@table');
Route::post('/dosage/form','setup\DosageController@form');

//// Frequency setup ///
Route::get('/frequency','setup\FrequencyController@show');
Route::get('/frequency/table','setup\FrequencyController@table');
Route::post('/frequency/form','setup\FrequencyController@form');

//// Instruction setup ///
Route::get('/instruction','setup\InstructionController@show');
Route::get('/instruction/table','setup\InstructionController@table');
Route::post('/instruction/form','setup\InstructionController@form');

//// Compcode setup page ///
Route::get('/compcode','setup\CompcodeController@show');
Route::get('/compcode/table','setup\CompcodeController@table');
Route::post('/compcode/form','setup\CompcodeController@form');

//// Doctor setup page ///
Route::get('/doctor','setup\DoctorController@show');
Route::get('/doctor/table','setup\DoctorController@table');
Route::post('/doctor/form','setup\DoctorController@form');
Route::post('/doctorContribution/form','setup\DoctorContributionController@form');

//// computer id setup page ///
Route::get('/computerid','setup\ComputeridController@show');
Route::get('/computerid/table','setup\ComputeridController@table');
Route::post('/computerid/form','setup\ComputeridController@form');

//// receipt AR setup page ///
Route::get('/receipt','finance\ReceiptController@show');
Route::get('/receipt/table','finance\ReceiptController@table');
Route::post('/receipt/form','finance\ReceiptController@form');
Route::get('/receipt/showpdf','finance\ReceiptController@showpdf');

//// Receipt Transaction AR - report sales ///
Route::get('/ReceiptAR_Report','finance\ReceiptAR_ReportController@show');
Route::get('/ReceiptAR_Report/table','finance\ReceiptAR_ReportController@table');
Route::post('/ReceiptAR_Report/form','finance\ReceiptAR_ReportController@form');
Route::get('/ReceiptAR_Report/showExcel','finance\ReceiptAR_ReportController@showExcel');

//// refund AR setup page ///
Route::get('/refund','finance\RefundController@show');
Route::get('/refund/table','finance\RefundController@table');
Route::post('/refund/form','finance\RefundController@form');
Route::get('/refund/showpdf','finance\RefundController@showpdf');

//// doctor_maintenance setup page ///
Route::get('/doctor_maintenance','hisdb\DoctorMaintenanceController@show');
Route::get('/doctor_maintenance/table','hisdb\DoctorMaintenanceController@table');
Route::post('/doctor_maintenance/form','hisdb\DoctorMaintenanceController@form');
Route::post('/doctor_maintenance/save_session','hisdb\DoctorMaintenanceController@save_session');
Route::post('/doctor_maintenance/save_bgleave','hisdb\DoctorMaintenanceController@save_bgleave');
Route::post('/doctor_maintenance/save_colorph','hisdb\DoctorMaintenanceController@save_colorph');

//// rsc_maintenance setup page ///
Route::get('/rsc_maintenance','hisdb\rscMaintenanceController@show');
Route::get('/rsc_maintenance/table','hisdb\rscMaintenanceController@table');
Route::post('/rsc_maintenance/form','hisdb\rscMaintenanceController@form');
Route::post('/rsc_maintenance/save_session','hisdb\rscMaintenanceController@save_session');
Route::post('/rsc_maintenance/save_bgleave','hisdb\rscMaintenanceController@save_bgleave');
Route::post('/rsc_maintenance/save_colorph','hisdb\rscMaintenanceController@save_colorph');

//// ot_maintenance setup page ///
Route::get('/ot_maintenance','hisdb\otMaintenanceController@show');
Route::get('/ot_maintenance/table','hisdb\otMaintenanceController@table');
Route::post('/ot_maintenance/form','hisdb\otMaintenanceController@form');
Route::post('/ot_maintenance/save_session','hisdb\otMaintenanceController@save_session');
Route::post('/ot_maintenance/save_bgleave','hisdb\otMaintenanceController@save_bgleave');
Route::post('/ot_maintenance/save_colorph','hisdb\otMaintenanceController@save_colorph');

//// OT Management page ////
Route::get('/otmanagement','hisdb\OTManagementController@index');
Route::get('/otmanagement/table','hisdb\OTManagementController@table');
Route::post('/otmanagement/form','hisdb\OTManagementController@form');
// Route::post('/otmanagement_transaction_save', "hisdb\OTManagementController@transaction_save");

//// OT Management_div page ////
Route::get('/otmanagement_div','hisdb\OTManagement_divController@show');
Route::get('/otmanagement_div/table','hisdb\OTManagement_divController@table');
Route::post('/otmanagement_div/form','hisdb\OTManagement_divController@form');

//// Preoperative page ////
Route::get('/preoperative','hisdb\PreoperativeController@show');
Route::get('/preoperative/table','hisdb\PreoperativeController@table');
Route::post('/preoperative/form','hisdb\PreoperativeController@form');
Route::get('/preoperative/get_entry','hisdb\PreoperativeController@get_entry');

//// Preoperative (daycare)page ////
Route::get('/preoperativeDC','hisdb\PreoperativeDCController@show');
Route::get('/preoperativeDC/table','hisdb\PreoperativeDCController@table');
Route::post('/preoperativeDC/form','hisdb\PreoperativeDCController@form');
Route::get('/preoperativeDC/get_entry','hisdb\PreoperativeDCController@get_entry');

//// Operating Team page ////
Route::get('/oper_team','hisdb\OperTeamController@show');
Route::get('/oper_team/table','hisdb\OperTeamController@table');
Route::post('/oper_team/form','hisdb\OperTeamController@form');

//// OT Swab page ////
Route::get('/otswab','hisdb\OTSwabController@show');
Route::get('/otswab/table','hisdb\OTSwabController@table');
Route::post('/otswab/form','hisdb\OTSwabController@form');

//// OT Time page ////
Route::get('/ottime','hisdb\OTTimeController@show');
Route::get('/ottime/table','hisdb\OTTimeController@table');
Route::post('/ottime/form','hisdb\OTTimeController@form');

//// OT Discharge page ////
Route::get('/otdischarge','hisdb\OTDischargeController@show');
Route::get('/otdischarge/table','hisdb\OTDischargeController@table');
Route::post('/otdischarge/form','hisdb\OTDischargeController@form');

//// Endoscopy Notes page ////
Route::get('/endoscopyNotes','hisdb\EndoscopyNotesController@show');
Route::get('/endoscopyNotes/table','hisdb\EndoscopyNotesController@table');
Route::post('/endoscopyNotes/form','hisdb\EndoscopyNotesController@form');

//// OT Status setup page ///
Route::get('/ot_status','hisdb\OTStatusController@show');
Route::get('/ot_status/table','hisdb\OTStatusController@table');
Route::post('/ot_status/form','hisdb\OTStatusController@form');
Route::get('/ot_status/form','hisdb\OTStatusController@form');
// Route::post('/otstatusdetail/form','hisdb\OTStatusDetailController@form');

//// OT Type setup page ///
Route::get('/ot_type','hisdb\OTTypeController@show');
Route::get('/ot_type/table','hisdb\OTTypeController@table');
Route::post('/ot_type/form','hisdb\OTTypeController@form');
Route::get('/ot_type/form','hisdb\OTTypeController@form');
// Route::post('/ot_typedetail/form','hisdb\OTTypeDetailController@form');

//// Admission Source setup page ///
Route::get('/admissrc','setup\AdmisSrcController@show');
Route::get('/admissrc/table','setup\AdmisSrcController@table');
Route::post('/admissrc/form','setup\AdmisSrcController@form');

//// Case Type setup page ///
Route::get('/casetype','setup\CaseTypeController@show');
Route::get('/casetype/table','setup\CaseTypeController@table');
Route::post('/casetype/form','setup\CaseTypeController@form');

//// Episode Type setup page ///
Route::get('/episodetype','setup\EpisodeTypeController@show');
Route::get('/episodetype/table','setup\EpisodeTypeController@table');
Route::post('/episodetype/form','setup\EpisodeTypeController@form');

//// Discharge Destination setup page ///
Route::get('/dischargedestination','setup\DischargeDestinationController@show');
Route::get('/dischargedestination/table','setup\DischargeDestinationController@table');
Route::post('/dischargedestination/form','setup\DischargeDestinationController@form');

// //// ID Type setup page ///
Route::get('/idtype','setup\IDTypeController@show');
Route::get('/idtype/table','setup\IDTypeController@table');
Route::post('/idtype/form','setup\IDTypeController@form');

// //// Address Type setup page ///
Route::get('/addresstype','setup\AddressTypeController@show');
Route::get('/addresstype/table','setup\AddressTypeController@table');
Route::post('/addresstype/form','setup\AddressTypeController@form');

//// Country setup page ///
Route::get('/country','setup\CountryController@show');
Route::get('/country/table','setup\CountryController@table');
Route::post('/country/form','setup\CountryController@form');

//// State setup page ///
Route::get('/state','setup\StateController@show');
Route::get('/state/table','setup\StateController@table');
Route::post('/state/form','setup\StateController@form');

//// Postcode setup page ///
Route::get('/postcode','setup\PostcodeController@show');
Route::get('/postcode/table','setup\PostcodeController@table');
Route::post('/postcode/form','setup\PostcodeController@form');

//// Citizen setup page ///
Route::get('/citizen','setup\CitizenController@show');
Route::get('/citizen/table','setup\CitizenController@table');
Route::post('/citizen/form','setup\CitizenController@form');

//// Ward setup page ///
Route::get('/ward','setup\WardController@show');
Route::get('/ward/table','setup\WardController@table');
Route::post('/ward/form','setup\WardController@form');

//// Bed Type setup page ///
Route::get('/bedtype','setup\BedTypeController@show');
Route::get('/bedtype/table','setup\BedTypeController@table');
Route::post('/bedtype/form','setup\BedTypeController@form');

//// Bed setup page ///
Route::get('/bed','setup\BedController@show');
Route::get('/bed/table','setup\BedController@table');
Route::post('/bed/form','setup\BedController@form');

//// Bed Management setup page ///
Route::get('/bedmanagement','setup\BedManagementController@show');
Route::get('/bedmanagement/table','setup\BedManagementController@table');
Route::post('/bedmanagement/form','setup\BedManagementController@form');
Route::get('/bedmanagement/statistic','setup\BedManagementController@statistic');

////////////////////////////////inventory setup///////////////////////////////////////////////////////////

//// quotation setup page ///
Route::get('/quotation','material\QuotationController@show');
Route::get('/quotation/table','material\QuotationController@table');
Route::post('/quotation/form','material\QuotationController@form');

//// inventory Request setup page ///
Route::get('/inventoryRequest','material\InventoryRequestController@show');
Route::get('/inventoryRequest/table','material\InventoryRequestController@table');
Route::post('/inventoryRequest/form','material\InventoryRequestController@form');
Route::get('/inventoryRequest/form','material\InventoryRequestController@form');
Route::post('/inventoryRequestDetail/form','material\InventoryRequestDetailController@form');
Route::get('/inventoryRequest/showExcel','material\InventoryRequestController@showExcel');
Route::get('/inventoryRequest/showpdf','material\InventoryRequestController@showpdf');

//// inventory Transaction setup page ///
Route::get('/inventoryTransaction','material\InventoryTransactionController@show');
Route::get('/inventoryTransaction/table','material\InventoryTransactionController@table');
Route::post('/inventoryTransaction/form','material\InventoryTransactionController@form');
Route::get('/inventoryTransaction/form','material\InventoryTransactionController@form');
Route::post('/inventoryTransactionDetail/form','material\InventoryTransactionDetailController@form');
Route::get('/inventoryTransaction/showpdf','material\InventoryTransactionController@showpdf');
Route::get('/tui_tuo_report','material\InventoryTransactionController@tui_tuo_report_show');

//// repack setup page ///
Route::get('/repack','material\RepackController@show');
Route::get('/repack/table','material\RepackController@table');
Route::post('/repack/form','material\RepackController@form');
Route::get('/repack/form','material\RepackController@form');
Route::post('/repackDetail/form','material\RepackDetailController@form');
Route::get('/repackDetail/table','material\RepackDetailController@table');
Route::get('/repack/showpdf','material\RepackController@showpdf');
Route::get('/repack/showExcel','material\RepackController@showExcel');

//// purchase Request setup page ///
Route::get('/purchaseRequest','material\PurchaseRequestController@show');
Route::get('/purchaseRequest_mobile','material\PurchaseRequestController@show_mobile');
Route::get('/purchaseRequest/table','material\PurchaseRequestController@table');
Route::post('/purchaseRequest/form','material\PurchaseRequestController@form');
Route::get('/purchaseRequest/form','material\PurchaseRequestController@form');
Route::get('/purchaseRequest/showpdf','material\PurchaseRequestController@showpdf');
Route::post('/purchaseRequestDetail/form','material\PurchaseRequestDetailController@form');
Route::get('/purchaseRequestDetail/table','material\PurchaseRequestDetailController@table');

//// purchase Order setup page ///
Route::get('/purchaseOrder','material\PurchaseOrderController@show');
Route::get('/purchaseOrder_mobile','material\purchaseOrderController@show_mobile');
Route::get('/purchaseOrder/table','material\PurchaseOrderController@table');
Route::post('/purchaseOrder/form','material\PurchaseOrderController@form');
Route::get('/purchaseOrder/form','material\PurchaseOrderController@form');
Route::get('/purchaseOrder/showpdf','material\PurchaseOrderController@showpdf');
Route::post('/purchaseOrderDetail/form','material\PurchaseOrderDetailController@form');
Route::get('/purchaseOrderDetail/table','material\PurchaseOrderDetailController@table');

//// stock freeze ///
Route::get('/stockFreeze','material\StockFreezeController@show');
Route::get('/stockFreeze/table','material\StockFreezeController@table');
Route::post('/stockFreeze/form','material\StockFreezeController@form');
Route::get('/stockFreeze/form','material\StockFreezeController@form');
Route::get('/stockFreeze/showpdf','material\StockFreezeController@showpdf');
Route::post('/stockFreezeDetail/form','material\StockFreezeDetailController@form');
Route::get('/stockFreezeDetail/table','material\StockFreezeDetailController@table');

//// stock count ///
Route::get('/stockCount','material\StockCountController@show');
Route::get('/stockCount/table','material\StockCountController@table');
Route::post('/stockCount/form','material\StockCountController@form');
Route::get('/stockCount/form','material\StockCountController@form');
Route::get('/stockCount/showpdf','material\StockCountController@showpdf');
Route::get('/stockCount/showExcel','material\StockCountController@showExcel');
Route::post('/stockCountDetail/form','material\StockCountDetailController@form');
Route::get('/stockCountDetail/table','material\StockCountDetailController@table');

//// delivery Order setup page ///
Route::get('/deliveryOrder','material\DeliveryOrderController@show');
Route::get('/deliveryOrder/table','material\DeliveryOrderController@table');
Route::post('/deliveryOrder/form','material\DeliveryOrderController@form');
Route::get('/deliveryOrder/form','material\DeliveryOrderController@form');
Route::get('/deliveryOrder/showpdf','material\DeliveryOrderController@showpdf');
Route::post('/deliveryOrderDetail/form','material\DeliveryOrderDetailController@form');
Route::get('/deliveryOrderDetail/table','material\DeliveryOrderDetailController@table');
Route::get('/DO_posted_report','material\DeliveryOrderController@DO_posted_report_show');

//// good Return setup page ///
Route::get('/goodReturn','material\GoodReturnController@show');
Route::get('/goodReturn/table','material\GoodReturnController@table');
Route::post('/goodReturn/form','material\GoodReturnController@form');
Route::get('/goodReturn/form','material\GoodReturnController@form');
Route::post('/goodReturnDetail/form','material\GoodReturnDetailController@form');
Route::get('/goodReturn/showpdf','material\GoodReturnController@showpdf');

//// sequence material setup ///
Route::get('/sequence','material\SequenceController@show');
Route::get('/sequence/table','material\SequenceController@table');
Route::post('/sequence/form','material\SequenceController@form');

//// categoryINV material setup ///
Route::get('/categoryinv','material\CategoryInvController@show');
Route::get('/categoryinv/table','material\CategoryInvController@table');
Route::post('/categoryinv/form','material\CategoryInvController@form');

/////////////////////////////////////PROCUREMENT REPORT/////////////////////////////
//// PR Listing report ///
Route::get('/PRListing','material\PRListingController@show');
Route::get('/PRListing/table','material\PRListingController@table');
Route::post('/PRListing/form','material\PRListingController@form');
Route::get('/PRListing/showExcel','material\PRListingController@showExcel');
Route::get('/PRListing/showpdf','material\PRListingController@showpdf');

//// PO Listing report ///
Route::get('/POListing','material\POListingController@show');
Route::get('/POListing/table','material\POListingController@table');
Route::post('/POListing/form','material\POListingController@form');
Route::get('/POListing/showExcel','material\POListingController@showExcel');
Route::get('/POListing/showpdf','material\POListingController@showpdf');

//// DO Listing report ///
Route::get('/DOListing','material\DOListingController@show');
Route::get('/DOListing/table','material\DOListingController@table');
Route::post('/DOListing/form','material\DOListingController@form');
Route::get('/DOListing/showExcel','material\DOListingController@showExcel');
Route::get('/DOListing/showpdf','material\DOListingController@showpdf');

//// GRT Listing report ///
Route::get('/GRTListing','material\GRTListingController@show');
Route::get('/GRTListing/table','material\GRTListingController@table');
Route::post('/GRTListing/form','material\GRTListingController@form');
Route::get('/GRTListing/showExcel','material\GRTListingController@showExcel');
Route::get('/GRTListing/showpdf','material\GRTListingController@showpdf');

////////////////////////////////INVENTORY REPORT/////////////////////////////////

//// stock balance report ///
Route::get('/stockBalance','material\stockBalanceController@show');
Route::get('/stockBalance/report','material\stockBalanceController@report');

//// stock balance report ///
Route::get('/yearEnd','material\YearEndController@yearEnd');
Route::get('/yearEndProcess','material\YearEndController@yearEndProcess');
Route::post('/yearEnd/form','material\YearEndController@form');
Route::get('/yearEnd/table','material\YearEndController@table');

//// itemmov report ///
Route::get('/ItemMovReport','material\ItemMovReportController@show');
Route::get('/ItemMovReport/pdf','material\ItemMovReportController@pdf');
Route::get('/ItemMovReport/excel','material\ItemMovReportController@excel');

//// avgcost_vs_currcost report ///
Route::get('/avgcost_vs_currcost','material\avgcost_vs_currcostController@show');
Route::get('/avgcost_vs_currcost/table','material\avgcost_vs_currcostController@table');
Route::post('/avgcost_vs_currcost/form','material\avgcost_vs_currcostController@form');
Route::get('/avgcost_vs_currcost/showExcel','material\avgcost_vs_currcostController@showExcel');
Route::get('/avgcost_vs_currcost/showpdf','material\avgcost_vs_currcostController@showpdf');

//// deptItemList report ///
Route::get('/deptItemList','material\deptItemListController@show');
Route::get('/deptItemList/table','material\deptItemListController@table');
Route::post('/deptItemList/form','material\deptItemListController@form');
Route::get('/deptItemList/showExcel','material\deptItemListController@showExcel');
Route::get('/deptItemList/showpdf','material\deptItemListController@showpdf');

//// stockExpiry report ///
Route::get('/stockExpiry','material\stockExpiryController@show');
Route::get('/stockExpiry/table','material\stockExpiryController@table');
Route::post('/stockExpiry/form','material\stockExpiryController@form');
Route::get('/stockExpiry/showExcel','material\stockExpiryController@showExcel');
Route::get('/stockExpiry/showpdf','material\stockExpiryController@showpdf');

//// inventoryRequest report ///
Route::get('/inventoryRequest_Report','material\inventoryRequest_ReportController@show');
Route::get('/inventoryRequest_Report/table','material\inventoryRequest_ReportController@table');
Route::post('/inventoryRequest_Report/form','material\inventoryRequest_ReportController@form');
Route::get('/inventoryRequest_Report/showExcel','material\inventoryRequest_ReportController@showExcel');
Route::get('/inventoryRequest_Report/showpdf','material\inventoryRequest_ReportController@showpdf');

//// stock in transit (ivtxn) report ///
Route::get('/inventoryTransaction_Report','material\inventoryTransaction_ReportController@show');
Route::get('/inventoryTransaction_Report/table','material\inventoryTransaction_ReportController@table');
Route::post('/inventoryTransaction_Report/form','material\inventoryTransaction_ReportController@form');
Route::get('/inventoryTransaction_Report/showExcel','material\inventoryTransaction_ReportController@showExcel');
Route::get('/inventoryTransaction_Report/showpdf','material\inventoryTransaction_ReportController@showpdf');

//// Item's Latest Price Listing report ///
Route::get('/itemLatestPriceListing','material\itemLatestPriceListingController@show');
Route::get('/itemLatestPriceListing/table','material\itemLatestPriceListingController@table');
Route::post('/itemLatestPriceListing/form','material\itemLatestPriceListingController@form');
Route::get('/itemLatestPriceListing/showExcel','material\itemLatestPriceListingController@showExcel');
Route::get('/itemLatestPriceListing/showpdf','material\itemLatestPriceListingController@showpdf');

//// Non-Stock Listing report ///
Route::get('/nonStockListing','material\nonStockListingController@show');
Route::get('/nonStockListing/table','material\nonStockListingController@table');
Route::post('/nonStockListing/form','material\nonStockListingController@form');
Route::get('/nonStockListing/showExcel','material\nonStockListingController@showExcel');
Route::get('/nonStockListing/showpdf','material\nonStockListingController@showpdf');

///////////////////////////FINANCE SETUP///////////////////////////////////////////////////////////
//// invoice AP setup page ///
Route::get('/invoiceAP','finance\InvoiceAPController@show');
Route::get('/invoiceAP/table','finance\InvoiceAPController@table');
Route::post('/invoiceAP/form','finance\InvoiceAPController@form');
Route::get('/invoiceAP/form','finance\InvoiceAPController@form');
Route::post('/invoiceAPDetail/form','finance\InvoiceAPDetailController@form');
Route::post('/invoiceAPDetail/form2','finance\InvoiceAPDetailController@form');

//// invoice AP - report  ///
Route::get('/invoiceAP_Report','finance\InvoiceAP_ReportController@show');
Route::get('/invoiceAP_Report/table','finance\InvoiceAP_ReportController@table');
Route::post('/invoiceAP_Report/form','finance\InvoiceAP_ReportController@form');
Route::get('/invoiceAP_Report/showExcel','finance\InvoiceAP_ReportController@showExcel');


//// attachment_upload  ///
Route::get('/attachment_upload','util\attachment_uploadController@page');
Route::get('/attachment_upload/table','util\attachment_uploadController@table');
Route::post('/attachment_upload/form','util\attachment_uploadController@form');
Route::get('/attachment_upload/thumbnail/{folder}/{image_path}','util\attachment_uploadController@thumbnail');
Route::get('/attachment_download/{folder}/{image_path}','util\attachment_uploadController@download');

//// Finance - Quotation page ///
Route::get('/Quotation_SO','finance\Quotation_SO_Controller@show');
Route::get('/Quotation_SO/table','finance\Quotation_SO_Controller@table');
Route::post('/Quotation_SO/form','finance\Quotation_SO_Controller@form');
Route::get('/Quotation_SO/form','finance\Quotation_SO_Controller@form');
Route::post('/Quotation_SO_Detail/form','finance\Quotation_SO_DetailController@form');
Route::get('/Quotation_SO_Detail/table','finance\Quotation_SO_DetailController@table');
Route::get('/Quotation_SO/showpdf','finance\Quotation_SO_Controller@showpdf');

//// Finance - SalesOrder page ///
Route::get('/SalesOrder','finance\SalesOrderController@show');
Route::get('/SalesOrder_mobile','finance\SalesOrderController@show_mobile');
Route::get('/SalesOrder/table','finance\SalesOrderController@table');
Route::post('/SalesOrder/form','finance\SalesOrderController@form');
Route::get('/SalesOrder/form','finance\SalesOrderController@form');
Route::post('/SalesOrderDetail/form','finance\SalesOrderDetailController@form');
Route::get('/SalesOrderDetail/table','finance\SalesOrderDetailController@table');
Route::get('/SalesOrder/showpdf','finance\SalesOrderController@showpdf');

//// Finance - Point of Sales page ///
Route::get('/PointOfSales','finance\PointOfSalesController@show');
Route::get('/PointOfSales/table','finance\PointOfSalesController@table');
Route::post('/PointOfSales/form','finance\PointOfSalesController@form');
Route::get('/PointOfSales/form','finance\PointOfSalesController@form');
Route::post('/PointOfSalesDetail/form','finance\PointOfSalesDetailController@form');
Route::get('/PointOfSalesDetail/table','finance\PointOfSalesDetailController@table');
Route::get('/PointOfSales/showpdf','finance\PointOfSalesController@showpdf');

//// Finance - report sales ///
Route::get('/SalesOrder_Report','finance\SalesOrder_ReportController@show');
Route::get('/SalesOrder_Report/table','finance\SalesOrder_ReportController@table');
Route::post('/SalesOrder_Report/form','finance\SalesOrder_ReportController@form');
Route::get('/SalesOrder_Report/showExcel','finance\SalesOrder_ReportController@showExcel');
Route::get('/SalesOrder_Report/showpdf','finance\SalesOrder_ReportController@showpdf');

//// Finance - report sales by item ///
Route::get('/SalesItem_Report','finance\SalesItem_ReportController@show');
Route::get('/SalesItem_Report/table','finance\SalesItem_ReportController@table');
Route::post('/SalesItem_Report/form','finance\SalesItem_ReportController@form');
Route::get('/SalesItem_Report/showExcel','finance\SalesItem_ReportController@showExcel');
Route::get('/SalesItem_Report/showpdf','finance\SalesItem_ReportController@showpdf');

//// Delivery Department material setup ///
Route::get('/deliveryDept','material\DeliveryDeptController@show');
Route::get('/deliveryDept/table','material\DeliveryDeptController@table');
Route::post('/deliveryDept/form','material\DeliveryDeptController@form');

//// Transaction Type material setup ///
Route::get('/tranType','material\TranTypeController@show');
Route::get('/tranType/table','material\TranTypeController@table');
Route::post('/tranType/form','material\TranTypeController@form');

//// PO Type material setup ///
Route::get('/potype','material\POTypeController@show');
Route::get('/potype/table','material\POTypeController@table');
Route::post('/potype/form','material\POTypeController@form');

//// Authorization material setup ///
Route::get('/authorization','material\AuthorizationController@show');
Route::get('/authorization/table','material\AuthorizationController@table');
Route::post('/authorization/form','material\AuthorizationController@form');
Route::get('/authorization/form','material\authorizationDetailController@form');
Route::post('/authorizationDetail/form','material\authorizationDetailController@form');

//// Authorization Detail ///
Route::get('/authorizationDtl','material\AuthorizationDtlController@show');
Route::get('/authorizationDtl/table','material\AuthorizationDtlController@table');
Route::post('/authorizationDtl/form','material\authorizationDetailController@form');

//// Permission finance setup ///
Route::get('/permission','finance\PermissionController@show');
Route::get('/permission/table','finance\PermissionController@table');
Route::post('/permission/form','finance\PermissionController@form');
Route::get('/permission/form','finance\PermissionDetailController@form');
Route::post('/permissionDetail/form','finance\PermissionDetailController@form');

//// UOM material setup ///
Route::get('/uom','material\UomController@show');
Route::get('/uom/table','material\UomController@table');
Route::post('/uom/form','material\UomController@form');

//// Price Source setup ///
Route::get('/priceSource','material\PriceSourceController@show');
Route::get('/priceSource/table','material\PriceSourceController@table');
Route::post('/priceSource/form','material\PriceSourceController@form');

//// Supplier Group setup ///
Route::get('/suppgroup','material\SuppgroupController@show');
Route::get('/suppgroup/table','material\SuppgroupController@table');
Route::post('/suppgroup/form','material\SuppgroupController@form');

//// Product Master setup ///
Route::get('/productMaster','material\ProductMasterController@show');
Route::get('/productMaster/table','material\ProductMasterController@table');
Route::post('/productMaster/form','material\ProductMasterController@form');

//// Supplier setup ///
Route::get('/supplier','material\SupplierController@show');
Route::get('/supplier/table','material\SupplierController@table');
Route::post('/supplier/form','material\SupplierController@form');
Route::get('/supplier/showpdf','material\SupplierController@showpdf');
Route::get('/supplier/showExcel','material\SupplierController@showExcel');

/// Supplier - report  ///
Route::get('/supplier_Report','material\Supplier_ReportController@show');
Route::get('/supplier_Report/table','material\Supplier_ReportController@table');
Route::post('/supplier_Report/form','material\Supplier_ReportController@form');
Route::get('/supplier_Report/showExcel','material\Supplier_ReportController@showExcel');

//// Stock Loc Enquiry ///
Route::get('/stocklocEnquiry','material\StocklocEnquiryController@show');
Route::get('/stocklocEnquiry/table','material\StocklocEnquiryController@table');
Route::post('/stocklocEnquiry/form','material\StocklocEnquiryController@form');

//// Item Enquiry /// Class=Pharmacy
Route::get('/itemEnquiry','material\ItemEnquiryController@show');
Route::get('/itemEnquiry/table','material\ItemEnquiryController@table');
Route::post('/itemEnquiry/form','material\ItemEnquiryController@form');
Route::get('/itemEnquiry/form','material\ItemEnquiryController@form');
Route::get('/itemEnquiry/showExcel','material\ItemEnquiryController@showExcel');

//// Stock Location ///
Route::get('/stockloc','material\StocklocController@show');
Route::get('/stockloc/table','material\StocklocController@table');
Route::post('/stockloc/form','material\StocklocController@form');\

//// Product ///
Route::get('/product','material\ProductController@show');
Route::get('/product/table','material\ProductController@table');
Route::post('/product/form','material\ProductController@form');

/////////// appointment resource - doctor setup page ////////////////////////////////////
Route::get('/apptrsc','hisdb\AppointmentController@show');
Route::get('/apptrsc/table','hisdb\AppointmentController@table');
Route::post('/apptrsc/form','hisdb\AppointmentController@form');
Route::get('/apptrsc/getEvent','hisdb\AppointmentController@getEvent');
Route::post('/apptrsc/addEvent','hisdb\AppointmentController@addEvent');
Route::post('/apptrsc/editEvent','hisdb\AppointmentController@editEvent');
Route::post('/apptrsc/delEvent','hisdb\AppointmentController@delEvent');

/////////// appointment resource - resource setup page ////////////////////////////////////
Route::get('/apptrsc_rsc','hisdb\Appointment_rscController@show');
Route::get('/apptrsc_rsc_iframe','hisdb\Appointment_rscController@apptrsc_rsc_iframe');
Route::get('/wardbook_iframe','hisdb\Appointment_rscController@wardbook_iframe');
Route::get('/apptrsc_rsc/table','hisdb\Appointment_rscController@table');
Route::post('/apptrsc_rsc/form','hisdb\Appointment_rscController@form');
Route::get('/apptrsc_rsc/getEvent','hisdb\Appointment_rscController@getEvent');
Route::post('/apptrsc_rsc/addEvent','hisdb\Appointment_rscController@addEvent');
Route::post('/apptrsc_rsc/editEvent','hisdb\Appointment_rscController@editEvent');
Route::post('/apptrsc_rsc/delEvent','hisdb\Appointment_rscController@delEvent');

//////////////////////////////finance setup///////////////////////////////////////

//// debtortype ////
Route::get('/debtortype','finance\DebtortypeController@show');
Route::get('/debtortype/table','finance\DebtortypeController@table');
Route::post('/debtortype/form','finance\DebtortypeController@form');

//// GL Enquiry ///
Route::get('/glenquiry','finance\GlenquiryController@show');
Route::get('/glenquiry/table','finance\GlenquiryController@table');
Route::post('/glenquiry/form','finance\GlenquiryController@form');

//// GL Enquiry by Date ///
Route::get('/acctenq_date','finance\acctenq_dateController@show');
Route::get('/acctenq_date/table','finance\acctenq_dateController@table');
Route::post('/acctenq_date/form','finance\acctenq_dateController@form');

//// Reprint Bill ///
Route::get('/reprintBill','finance\ReprintBillController@show');
Route::get('/reprintBill/table','finance\ReprintBillController@table');
Route::post('/reprintBill/form','finance\ReprintBillController@form');

//// Reprint Bill ///
Route::get('/einvoice','finance\einvoiceController@show');
Route::get('/einvoice/table','finance\einvoiceController@table');
Route::post('/einvoice/form','finance\einvoiceController@form');

//// Department setup ///
Route::get('/department','finance\DepartmentController@show');
Route::get('/department/table','finance\DepartmentController@table');
Route::post('/department/form','finance\DepartmentController@form');

//// Sector setup page ///
Route::get('/region','finance\SectionController@show');
Route::get('/region/table','finance\SectionController@table');
Route::post('/region/form','finance\SectionController@form');

//// Unit setup page ///
Route::get('/unit','finance\UnitController@show');
Route::get('/unit/table','finance\UnitController@table');
Route::post('/unit/form','finance\UnitController@form');

//// costcenter setup ///
Route::get('/costcenter','finance\CostcenterController@show');
Route::get('/costcenter/table','finance\CostcenterController@table');
Route::post('/costcenter/form','finance\CostcenterController@form');

//// costcenter - report  ///
Route::get('/costcenter_Report','finance\Costcenter_ReportController@show');
Route::get('/costcenter_Report/table','finance\Costcenter_ReportController@table');
Route::post('/costcenter_Report/form','finance\Costcenter_ReportController@form');
Route::get('/costcenter_Report/showExcel','finance\Costcenter_ReportController@showExcel');

//// GlMaster setup ///
Route::get('/glmaster','finance\GlmasterController@show');
Route::get('/glmaster/table','finance\GlmasterController@table');
Route::post('/glmaster/form','finance\GlmasterController@form');

//// Journal Entry ///
Route::get('/journalEntry','finance\JournalEntryController@show');
Route::get('/journalEntry/table','finance\JournalEntryController@table');
Route::post('/journalEntry/form','finance\JournalEntryController@form');
Route::post('/journalEntryDetail/form','finance\JournalEntryDetailController@form');
Route::get('/journalEntryDetail/table','finance\JournalEntryDetailController@table');

//// Chart Account ///
Route::get('/chartAccount','finance\ChartAccountController@show');
Route::get('/chartAccount/table','finance\ChartAccountController@table');
Route::post('/chartAccount/form','finance\ChartAccountController@form');

//// financialReport setup ///
Route::get('/financialReport','finance\financialReportController@show');
Route::get('/financialReport/table','finance\financialReportController@table');
Route::post('/financialReport/form','finance\financialReportController@form');

//// Trial Balance setup ///
Route::get('/trialBalance','finance\TrialBalanceController@show');
Route::get('/trialBalance/table','finance\TrialBalanceController@table');
Route::post('/trialBalance/form','finance\TrialBalanceController@form');

//// Glmaster - report  ///
Route::get('/glmaster_Report','finance\Glmaster_ReportController@show');
Route::get('/glmaster_Report/table','finance\Glmaster_ReportController@table');
Route::post('/glmaster_Report/form','finance\Glmaster_ReportController@form');
Route::get('/glmaster_Report/showExcel','finance\Glmaster_ReportController@showExcel');

//// Consolidation Acc setup ///
Route::get('/consolidationAcc','finance\ConsolidationAccController@show');
Route::get('/consolidationAcc/table','finance\ConsolidationAccController@table');
Route::post('/consolidationAcc/form','finance\ConsolidationAccController@form');
Route::get('/consolidationAcc/form','finance\ConsolidationAccController@form');
Route::post('/consolidationAccDtl/form','finance\ConsolidationAccDtlController@form');
Route::get('/consolidationAccDtl/table','finance\ConsolidationAccDtlController@table');

//// Consolidation Cost Center setup ///
Route::get('/consolidationCostCenter','finance\ConsolidationCostCenterController@show');
Route::get('/consolidationCostCenter/table','finance\ConsolidationCostCenterController@table');
Route::post('/consolidationCostCenter/form','finance\ConsolidationCostCenterController@form');
Route::get('/consolidationCostCenter/form','finance\ConsolidationCostCenterController@form');
Route::post('/consolidationCostCenterDtl/form','finance\ConsolidationCostCenterControllerDtl@form');
Route::get('/consolidationCostCenterDtl/table','finance\ConsolidationCostCenterControllerDtl@table');

//// Report Format ///
Route::get('/reportFormat','finance\ReportFormatController@show');
Route::get('/reportFormat/table','finance\ReportFormatController@table');
Route::post('/reportFormat/form','finance\ReportFormatController@form');
Route::get('/reportFormat/form','finance\ReportFormatController@form');
Route::post('/reportFormatDetail/form','finance\ReportFormatDetailController@form');
Route::get('/reportFormatDetail/table','finance\ReportFormatDetailController@table');
Route::get('/reportFormat/showExcel','finance\ReportFormatController@showExcel');
Route::get('/reportFormat/showpdf','finance\ReportFormatController@showpdf');

//// period setup ///
Route::get('/period','finance\PeriodController@show');
Route::get('/period/table','finance\PeriodController@table');
Route::post('/period/form','finance\PeriodController@form');

//// Debtor Master setup ///
Route::get('/debtorMaster','finance\DebtorMasterController@show');
Route::get('/debtorMaster/table','finance\DebtorMasterController@table');
Route::post('/debtorMaster/form','finance\DebtorMasterController@form');
Route::get('/debtorMaster/showExcel','finance\DebtorMasterController@showExcel');
Route::get('/debtorMaster/showpdf','finance\DebtorMasterController@showpdf');

//// Debtor Master - report sales ///
Route::get('/DebtorMaster_Report','finance\DebtorMaster_ReportController@show');
Route::get('/DebtorMaster_Report/table','finance\DebtorMaster_ReportController@table');
Route::post('/DebtorMaster_Report/form','finance\DebtorMaster_ReportController@form');
Route::get('/DebtorMaster_Report/showExcel','finance\DebtorMaster_ReportController@showExcel');

//// Deposit Type setup ///
Route::get('/depositType','finance\DepositTypeController@show');
Route::get('/depositType/table','finance\DepositTypeController@table');
Route::post('/depositType/form','finance\DepositTypeController@form');

//// Payment Mode setup ///
Route::get('/paymentMode','finance\PaymentModeController@show');
Route::get('/paymentMode/table','finance\PaymentModeController@table');
Route::post('/paymentMode/form','finance\PaymentModeController@form');

//// categoryFIN Mode setup ///
Route::get('/categoryfin','finance\CategoryFinController@show');
Route::get('/categoryfin/table','finance\CategoryFinController@table');
Route::post('/categoryfin/form','finance\CategoryFinController@form');

//// Debit Note Category AR Mode setup ///
Route::get('/DebitNoteCategory','finance\DebitNoteCategoryController@show');
Route::get('/DebitNoteCategory/table','finance\DebitNoteCategoryController@table');
Route::post('/DebitNoteCategory/form','finance\DebitNoteCategoryController@form');

//// Debit Note Category AR - report sales ///
Route::get('/DebitNoteAR_Report','finance\DebitNoteAR_ReportController@show');
Route::get('/DebitNoteAR_Report/table','finance\DebitNoteAR_ReportController@table');
Route::post('/DebitNoteAR_Report/form','finance\DebitNoteAR_ReportController@form');
Route::get('/DebitNoteAR_Report/showExcel','finance\DebitNoteAR_ReportController@showExcel');

//// Bank setup ///
Route::get('/bank','finance\BankController@show');
Route::get('/bank/table','finance\BankController@table');
Route::post('/bank/form','finance\BankController@form');

//// cheque list setup ///
Route::get('/cheqlist','finance\CheqListController@show');
Route::get('/cheqlist/table','finance\CheqListController@table');
Route::get('/cheqlistDetail/form','finance\CheqListController@form');
Route::post('/cheqlistDetail/form','finance\CheqListController@form');

//// cheque register setup ///
Route::get('/cheqreg','finance\CheqRegController@show');
Route::get('/cheqreg/table','finance\CheqRegController@table');
Route::get('/cheqregDetail/form','finance\CheqRegController@form');
Route::post('/cheqregDetail/form','finance\CheqRegController@form');

//// Bank Transfer ///
Route::get('/bankTransfer','finance\BankTransferController@show');
Route::get('/bankTransfer/table','finance\BankTransferController@table');
Route::post('/bankTransfer/form','finance\BankTransferController@form');

//// Bank Enquiry ///
Route::get('/bankEnquiry','finance\BankEnquiryController@show');
Route::get('/bankEnquiry/table','finance\BankEnquiryController@table');
Route::post('/bankEnquiry/form','finance\BankEnquiryController@form');

//// Cash Management Enquiry ///
Route::get('/CMEnquiry','finance\CMEnquiryController@show');
Route::get('/CMEnquiry/table','finance\CMEnquiryController@table');
Route::post('/CMEnquiry/form','finance\CMEnquiryController@form');

//// Direct Payment ///
Route::get('/directPayment','finance\DirectPaymentController@show');
Route::get('/directPayment/table','finance\DirectPaymentController@table');
Route::post('/directPayment/form','finance\DirectPaymentController@form');
Route::get('/directPayment/form','finance\DirectPaymentController@form');
Route::post('/directPaymentDetail/form','finance\DirectPaymentDetailController@form');
Route::get('/directPaymentDetail/table','finance\DirectPaymentDetailController@table');
Route::get('/directPayment/showpdf','finance\DirectPaymentController@showpdf');

//// Direct Payment ///
Route::get('/bankRecon','finance\bankReconController@show');
Route::get('/bankRecon/table','finance\bankReconController@table');
Route::post('/bankRecon/form','finance\bankReconController@form');
Route::post('/bankReconDetail/form','finance\bankReconDetailController@form');
Route::get('/bankReconDetail/table','finance\bankReconDetailController@table');

//// bankInRegistration ///
Route::get('/bankInRegistration','finance\bankInRegistrationController@show');
Route::get('/bankInRegistration/table','finance\bankInRegistrationController@table');
Route::post('/bankInRegistration/form','finance\bankInRegistrationController@form');
Route::post('/bankInRegistrationDetail/form','finance\bankInRegistrationDetailController@form');
Route::get('/bankInRegistrationDetail/table','finance\bankInRegistrationDetailController@table');

//// Tr to bank ///
Route::get('/trtobank','finance\trtobankController@show');
Route::get('/trtobank/table','finance\trtobankController@table');
Route::post('/trtobank/form','finance\trtobankController@form');
// Route::post('/bankInRegistrationDetail/form','finance\bankInRegistrationDetailController@form');
// Route::get('/bankInRegistrationDetail/table','finance\bankInRegistrationDetailController@table');

//// Credit Debit Transaction ///
Route::get('/creditDebitTrans','finance\CreditDebitTransController@show');
Route::get('/creditDebitTrans/table','finance\CreditDebitTransController@table');
Route::post('/creditDebitTrans/form','finance\CreditDebitTransController@form');
Route::get('/creditDebitTrans/form','finance\CreditDebitTransController@form');
Route::post('/creditDebitTransDetail/form','finance\CreditDebitTransDetailController@form');
Route::get('/creditDebitTransDetail/table','finance\CreditDebitTransDetailController@table');
Route::get('/creditDebitTrans/showpdf','finance\CreditDebitTransController@showpdf');

//// Payment Voucher Transaction ///
Route::get('/paymentVoucher','finance\PaymentVoucherController@show');
Route::get('/paymentVoucher_mobile','finance\PaymentVoucherController@show_mobile');
Route::get('/paymentVoucher/table','finance\PaymentVoucherController@table');
Route::post('/paymentVoucher/form','finance\PaymentVoucherController@form');
Route::get('/paymentVoucher/form','finance\PaymentVoucherController@form');
Route::get('/paymentVoucher/showpdf','finance\PaymentVoucherController@showpdf');

//// payment voucher - report  ///
Route::get('/paymentVoucher_Report','finance\PaymentVoucher_ReportController@show');
Route::get('/paymentVoucher_Report/table','finance\PaymentVoucher_ReportController@table');
Route::post('/paymentVoucher_Report/form','finance\PaymentVoucher_ReportController@form');
Route::get('/paymentVoucher_Report/showExcel','finance\PaymentVoucher_ReportController@showExcel');

//// Manual Allocation Transaction AP///
Route::get('/manualAlloc','finance\ManualAllocController@show');
Route::get('/manualAlloc/table','finance\ManualAllocController@table');
Route::post('/manualAlloc/form','finance\ManualAllocController@form');
Route::get('/manualAlloc/form','finance\ManualAllocController@form');

//// Credit Note ///
Route::get('/creditNote','finance\CreditNoteController@show');
Route::get('/creditNote/table','finance\CreditNoteController@table');
Route::post('/creditNote/form','finance\CreditNoteController@form');
Route::get('/creditNote/form','finance\CreditNoteController@form');
Route::post('/CreditNoteAPDetail/form','finance\CreditNoteDetailController@form');
Route::get('/CreditNoteAPDetail/table','finance\CreditNoteDetailController@table');
Route::get('/creditNote/showpdf','finance\CreditNoteController@showpdf');

/// Credit Note AP - report  ///
Route::get('/creditNote_Report','finance\CreditNote_ReportController@show');
Route::get('/creditNote_Report/table','finance\CreditNote_ReportController@table');
Route::post('/creditNote_Report/form','finance\CreditNote_ReportController@form');
Route::get('/creditNote_Report/showExcel','finance\CreditNote_ReportController@showExcel');

//// Debit Note AP ///
Route::get('/debitNoteAP','finance\DebitNoteAPController@show');
Route::get('/debitNoteAP/table','finance\DebitNoteAPController@table');
Route::post('/debitNoteAP/form','finance\DebitNoteAPController@form');
Route::get('/debitNoteAP/form','finance\DebitNoteAPController@form');
Route::post('/DebitNoteAPDetail/form','finance\DebitNoteAPDetailController@form');
Route::get('/DebitNoteAPDetail/table','finance\DebitNoteAPDetailController@table');
Route::get('/debitNoteAP/showpdf','finance\DebitNoteAPController@showpdf');

//// Debit Note AP - report  ///
Route::get('/debitNoteAP_Report','finance\DebitNoteAP_ReportController@show');
Route::get('/debitNoteAP_Report/table','finance\DebitNoteAP_ReportController@table');
Route::post('/debitNoteAP_Report/form','finance\DebitNoteAP_ReportController@form');
Route::get('/debitNoteAP_Report/showExcel','finance\DebitNoteAP_ReportController@showExcel');

//// Finance - Debit Note AR page ///
Route::get('/DebitNote','finance\DebitNoteController@show');
Route::get('/DebitNote/table','finance\DebitNoteController@table');
Route::post('/DebitNote/form','finance\DebitNoteController@form');
Route::get('/DebitNote/form','finance\DebitNoteController@form');
Route::post('/DebitNoteDetail/form','finance\DebitNoteDetailController@form');
Route::get('/DebitNoteDetail/table','finance\DebitNoteDetailController@table');
Route::get('/DebitNote/showpdf','finance\DebitNoteController@showpdf');

//// Doctor Contribution setup page ///
Route::get('/contribution','finance\ContributionController@show');
Route::get('/contribution/table','finance\ContributionController@table');
Route::post('/contribution/form','finance\ContributionController@form');
Route::get('/contribution/form','finance\ContributionController@form');

/// Doctor Contribution - report  ///
Route::get('/contribution_Report','finance\Contribution_ReportController@show');
Route::get('/contribution_Report/table','finance\Contribution_ReportController@table');
Route::post('/contribution_Report/form','finance\Contribution_ReportController@form');
Route::get('/contribution_Report/showExcel','finance\Contribution_ReportController@showExcel');

//// Finance - Credit Note AR page ///
Route::get('/CreditNoteAR','finance\CreditNoteARController@show');
Route::get('/CreditNoteAR/table','finance\CreditNoteARController@table');
Route::post('/CreditNoteAR/form','finance\CreditNoteARController@form');
Route::get('/CreditNoteAR/form','finance\CreditNoteARController@form');
Route::post('/CreditNoteARDetail/form','finance\CreditNoteARDetailController@form');
Route::get('/CreditNoteARDetail/table','finance\CreditNoteARDetailController@table');
Route::get('/CreditNoteAR/showpdf','finance\CreditNoteARController@showpdf');

//// Credit Note Category AR - report sales ///
Route::get('/CreditNoteAR_Report','finance\CreditNoteAR_ReportController@show');
Route::get('/CreditNoteAR_Report/table','finance\CreditNoteAR_ReportController@table');
Route::post('/CreditNoteAR_Report/form','finance\CreditNoteAR_ReportController@form');
Route::get('/CreditNoteAR_Report/showExcel','finance\CreditNoteAR_ReportController@showExcel');

//// Cancellation
Route::get('/cancellation','finance\CancellationController@show');
Route::get('/cancellation/table','finance\CancellationController@table');
Route::post('/cancellation/form','finance\CancellationController@form');
Route::get('/cancellation/form','finance\CancellationController@form');

//// AP Enquiry
Route::get('/apenquiry','finance\APEnquiryController@show');
Route::get('/apenquiry/table','finance\APEnquiryController@table');
Route::post('/apenquiry/form','finance\APEnquiryController@form');
Route::get('/apenquiry/form','finance\APEnquiryController@form');
Route::get('/apenquiry/showExcel','finance\APEnquiryController@showExcel');
Route::get('/apenquiry/showpdf','finance\APEnquiryController@showpdf');

//// AR Enquiry
Route::get('/arenquiry','finance\arenquiryController@show');
Route::get('/arenquiry/table','finance\arenquiryController@table');
Route::post('/arenquiry/form','finance\arenquiryController@form');
Route::get('/arenquiry/form','finance\arenquiryController@form');
Route::get('/arenquiry/showExcel','finance\arenquiryController@showExcel');
Route::get('/arenquiry/showpdf','finance\arenquiryController@showpdf');

//// CC Update
Route::get('/ccupdate','finance\ccupdateController@show');
Route::get('/ccupdate/table','finance\ccupdateController@table');
Route::post('/ccupdate/form','finance\ccupdateController@form');
Route::get('/ccupdate/form','finance\ccupdateController@form');
Route::get('/ccupdate/showExcel','finance\ccupdateController@showExcel');
Route::get('/ccupdate/showpdf','finance\ccupdateController@showpdf');

//// Till Enquiry
Route::get('/tillenquiry','finance\TillEnquiryController@show');
Route::get('/tillenquiry/table','finance\TillEnquiryController@table');
Route::post('/tillenquiry/form','finance\TillEnquiryController@form');
Route::get('/tillenquiry/form','finance\TillEnquiryController@form');
Route::get('/tillenquiry/showpdf','finance\TillEnquiryController@showpdf');

//// Dr contrib
Route::get('/drcontrib','finance\drcontribController@show');
Route::get('/drcontrib/table','finance\drcontribController@table');
Route::post('/drcontrib/form','finance\drcontribController@form');

//// Summary Receipt Listing Daily -- Report
Route::get('/SummaryRcptListing_Report','finance\SummaryRcptListing_ReportController@show');
Route::get('/SummaryRcptListing_Report/table','finance\SummaryRcptListing_ReportController@table');
Route::post('/SummaryRcptListing_Report/form','finance\SummaryRcptListing_ReportController@form');
Route::get('/SummaryRcptListing_Report/showExcel','finance\SummaryRcptListing_ReportController@showExcel');
Route::get('/SummaryRcptListing_Report/showpdf','finance\SummaryRcptListing_ReportController@showpdf');

//// Summary Receipt Listing Detail -- Report
Route::get('/SummaryRcptListingDtl_Report','finance\SummaryRcptListingDtl_ReportController@show');
Route::get('/SummaryRcptListingDtl_Report/table','finance\SummaryRcptListingDtl_ReportController@table');
Route::post('/SummaryRcptListingDtl_Report/form','finance\SummaryRcptListingDtl_ReportController@form');
Route::get('/SummaryRcptListingDtl_Report/showExcel','finance\SummaryRcptListingDtl_ReportController@showExcel');
Route::get('/SummaryRcptListingDtl_Report/showpdf','finance\SummaryRcptListingDtl_ReportController@showpdf');

//// Card Receipt Listing -- Report
Route::get('/cardReceipt_Report','finance\cardReceipt_ReportController@show');
Route::get('/cardReceipt_Report/table','finance\cardReceipt_ReportController@table');
Route::post('/cardReceipt_Report/form','finance\cardReceipt_ReportController@form');
Route::get('/cardReceipt_Report/showExcel','finance\cardReceipt_ReportController@showExcel');
Route::get('/cardReceipt_Report/showpdf','finance\cardReceipt_ReportController@showpdf');

//// Cash Receipt Listing -- Report
Route::get('/cashReceipt_Report','finance\cashReceipt_ReportController@show');
Route::get('/cashReceipt_Report/table','finance\cashReceipt_ReportController@table');
Route::post('/cashReceipt_Report/form','finance\cashReceipt_ReportController@form');
Route::get('/cashReceipt_Report/showExcel','finance\cashReceipt_ReportController@showExcel');
Route::get('/cashReceipt_Report/showpdf','finance\cashReceipt_ReportController@showpdf');

//// Cheque Receipt Listing -- Report
Route::get('/chequeReceipt_Report','finance\chequeReceipt_ReportController@show');
Route::get('/chequeReceipt_Report/table','finance\chequeReceipt_ReportController@table');
Route::post('/chequeReceipt_Report/form','finance\chequeReceipt_ReportController@form');
Route::get('/chequeReceipt_Report/showExcel','finance\chequeReceipt_ReportController@showExcel');
Route::get('/chequeReceipt_Report/showpdf','finance\chequeReceipt_ReportController@showpdf');

//// Auto Debit Listing -- Report
Route::get('/bankReceipt_Report','finance\bankReceipt_ReportController@show');
Route::get('/bankReceipt_Report/table','finance\bankReceipt_ReportController@table');
Route::post('/bankReceipt_Report/form','finance\bankReceipt_ReportController@form');
Route::get('/bankReceipt_Report/showExcel','finance\bankReceipt_ReportController@showExcel');
Route::get('/bankReceipt_Report/showpdf','finance\bankReceipt_ReportController@showpdf');

//// Daily Bill And Collection -- Report
Route::get('/DailyBillCollection_Report','finance\DailyBillCollection_ReportController@show');
Route::get('/DailyBillCollection_Report/table','finance\DailyBillCollection_ReportController@table');
Route::post('/DailyBillCollection_Report/form','finance\DailyBillCollection_ReportController@form');
Route::get('/DailyBillCollection_Report/showExcel','finance\DailyBillCollection_ReportController@showExcel');
Route::get('/DailyBillCollection_Report/showpdf','finance\DailyBillCollection_ReportController@showpdf');

//// Refund Listing -- Report
Route::get('/refundListing_Report','finance\refundListing_ReportController@show');
Route::get('/refundListing_Report/table','finance\refundListing_ReportController@table');
Route::post('/refundListing_Report/form','finance\refundListing_ReportController@form');
Route::get('/refundListing_Report/showExcel','finance\refundListing_ReportController@showExcel');
Route::get('/refundListing_Report/showpdf','finance\refundListing_ReportController@showpdf');

//// Payment Allocation -- Report
Route::get('/paymentAlloc_Report','finance\paymentAlloc_ReportController@show');
Route::get('/paymentAlloc_Report/table','finance\paymentAlloc_ReportController@table');
Route::post('/paymentAlloc_Report/form','finance\paymentAlloc_ReportController@form');
Route::get('/paymentAlloc_Report/showExcel','finance\paymentAlloc_ReportController@showExcel');
Route::get('/paymentAlloc_Report/showpdf','finance\paymentAlloc_ReportController@showpdf');

//// AR Ageing -- Report
Route::get('/ARAgeing_Report','finance\ARAgeing_ReportController@show');
Route::get('/ARAgeing_Report/table','finance\ARAgeing_ReportController@table');
Route::post('/ARAgeing_Report/form','finance\ARAgeing_ReportController@form');
Route::get('/ARAgeing_Report/showExcel','finance\ARAgeing_ReportController@showExcel');
Route::get('/ARAgeing_Report/showpdf','finance\ARAgeing_ReportController@showpdf');

//// AR Ageing Details -- Report
Route::get('/ARAgeingDtl_Report','finance\ARAgeingDtl_ReportController@show');
Route::get('/ARAgeingDtl_Report/table','finance\ARAgeingDtl_ReportController@table');
Route::post('/ARAgeingDtl_Report/form','finance\ARAgeingDtl_ReportController@form');
Route::get('/ARAgeingDtl_Report/showExcel','finance\ARAgeingDtl_ReportController@showExcel');
Route::get('/ARAgeingDtl_Report/showpdf','finance\ARAgeingDtl_ReportController@showpdf');

//// Debtor List -- Report
Route::get('/DebtorList_Report','finance\DebtorList_ReportController@show');
Route::get('/DebtorList_Report/table','finance\DebtorList_ReportController@table');
Route::post('/DebtorList_Report/form','finance\DebtorList_ReportController@form');
Route::get('/DebtorList_Report/summaryExcel','finance\DebtorList_ReportController@summaryExcel');
Route::get('/DebtorList_Report/summarypdf','finance\DebtorList_ReportController@summarypdf');
Route::get('/DebtorList_Report/dtlExcel','finance\DebtorList_ReportController@dtlExcel');
Route::get('/DebtorList_Report/dtlpdf','finance\DebtorList_ReportController@dtlpdf');

//// Deposits Received -- Report
Route::get('/depositRcv_Report','finance\depositRcv_ReportController@show');
Route::get('/depositRcv_Report/table','finance\depositRcv_ReportController@table');
Route::post('/depositRcv_Report/form','finance\depositRcv_ReportController@form');
Route::get('/depositRcv_Report/showExcel','finance\depositRcv_ReportController@showExcel');
Route::get('/depositRcv_Report/showpdf','finance\depositRcv_ReportController@showpdf');

//// Sales Listing -- Report
Route::get('/SalesListing_Report','finance\SalesListing_ReportController@show');
Route::get('/SalesListing_Report/table','finance\SalesListing_ReportController@table');
Route::post('/SalesListing_Report/form','finance\SalesListing_ReportController@form');
Route::get('/SalesListing_Report/showExcel','finance\SalesListing_ReportController@showExcel');
Route::get('/SalesListing_Report/showpdf','finance\SalesListing_ReportController@showpdf');

//// Claim Batch Listing -- Report
Route::get('/ClaimBatchList_Report','finance\ClaimBatchList_ReportController@show');
Route::get('/ClaimBatchList_Report/table','finance\ClaimBatchList_ReportController@table');
Route::post('/ClaimBatchList_Report/form','finance\ClaimBatchList_ReportController@form');
Route::get('/ClaimBatchList_Report/showExcel','finance\ClaimBatchList_ReportController@showExcel');
Route::get('/ClaimBatchList_Report/showpdf','finance\ClaimBatchList_ReportController@showpdf');
Route::get('/ClaimBatchList_Report/report','finance\ClaimBatchList_ReportController@report');

//// New Debtor Created -- Report
Route::get('/NewDebtor_Report','finance\NewDebtor_ReportController@show');
Route::get('/NewDebtor_Report/table','finance\NewDebtor_ReportController@table');
Route::post('/NewDebtor_Report/form','finance\NewDebtor_ReportController@form');
Route::get('/NewDebtor_Report/showExcel','finance\NewDebtor_ReportController@showExcel');
Route::get('/NewDebtor_Report/showpdf','finance\NewDebtor_ReportController@showpdf');

//// AP Summary -- Report
Route::get('/APSummary_Report','finance\APSummary_ReportController@show');
Route::get('/APSummary_Report/table','finance\APSummary_ReportController@table');
Route::post('/APSummary_Report/form','finance\APSummary_ReportController@form');
Route::get('/APSummary_Report/showExcel','finance\APSummary_ReportController@showExcel');
Route::get('/APSummary_Report/showpdf','finance\APSummary_ReportController@showpdf');

//// AP Ageing Summary -- Report
Route::get('/APAgeing_Report','finance\APAgeing_ReportController@show');
Route::get('/APAgeing_Report/table','finance\APAgeing_ReportController@table');
Route::post('/APAgeing_Report/form','finance\APAgeing_ReportController@form');
Route::get('/APAgeing_Report/showExcel','finance\APAgeing_ReportController@showExcel');
Route::get('/APAgeing_Report/showpdf','finance\APAgeing_ReportController@showpdf');

//// AP Ageing Details -- Report
Route::get('/APAgeingDtl_Report','finance\APAgeingDtl_ReportController@show');
Route::get('/APAgeingDtl_Report/table','finance\APAgeingDtl_ReportController@table');
Route::post('/APAgeingDtl_Report/form','finance\APAgeingDtl_ReportController@form');
Route::get('/APAgeingDtl_Report/showExcel','finance\APAgeingDtl_ReportController@showExcel');
Route::get('/APAgeingDtl_Report/showpdf','finance\APAgeingDtl_ReportController@showpdf');

//// Supp List -- Report
Route::get('/SuppList_Report','finance\SuppList_ReportController@show');
Route::get('/SuppList_Report/table','finance\SuppList_ReportController@table');
Route::post('/SuppList_Report/form','finance\SuppList_ReportController@form');
Route::get('/SuppList_Report/summaryExcel','finance\SuppList_ReportController@summaryExcel');
Route::get('/SuppList_Report/summarypdf','finance\SuppList_ReportController@summarypdf');
Route::get('/SuppList_Report/dtlExcel','finance\SuppList_ReportController@dtlExcel');
Route::get('/SuppList_Report/dtlpdf','finance\SuppList_ReportController@dtlpdf');

////////////////patient mgt setup/////////////////////////////////////////

//// pat_mast registration ////
Route::get('/pat_mast','hisdb\PatmastController@show');
Route::get('/pat_mast/get_entry','hisdb\PatmastController@get_entry');
Route::get('/pat_mast/table','hisdb\PatmastController@table');
Route::post('/pat_mast/post_entry','hisdb\PatmastController@post_entry');
Route::post('/pat_mast/save_patient','hisdb\PatmastController@save_patient');
Route::post('/pat_mast/save_episode','hisdb\PatmastController@save_episode');
Route::post('/pat_mast/save_adm','hisdb\PatmastController@save_adm');
Route::post('/pat_mast/save_gl','hisdb\PatmastController@save_gl');
Route::post('/pat_mast/new_occup_form','hisdb\PatmastController@new_occup_form');
Route::post('/pat_mast/new_title_form','hisdb\PatmastController@new_title_form');
Route::post('/pat_mast/new_areacode_form','hisdb\PatmastController@new_areacode_form');
Route::post('/pat_mast/new_relationship_form','hisdb\PatmastController@new_relationship_form');
Route::post('/pat_mast/auto_save','hisdb\PatmastController@auto_save');
Route::get('/pat_mast/patlabel','hisdb\PatmastController@patlabel');


Route::post('/episode/save_doc','hisdb\PatmastController@save_doc');
Route::post('/episode/save_bed','hisdb\PatmastController@save_bed');
Route::post('/episode/save_nok','hisdb\PatmastController@save_nok');
Route::post('/episode/save_emr','hisdb\PatmastController@save_emr');
Route::get('/episode/get_episode_by_mrn','hisdb\PatmastController@get_episode_by_mrn');
Route::get('/get_preepis_data','hisdb\PatmastController@get_preepis_data');
Route::get('/preregister','hisdb\PreregisterController@show');
Route::post('/prereg','hisdb\PreregisterController@prereg');

//// Emergency setup page ///
Route::get('/emergency','hisdb\EmergencyController@show');
Route::get('/emergency/table','hisdb\EmergencyController@table');
Route::post('/emergency/form','hisdb\EmergencyController@form');

//// pat_mgmt Current Patient ///
Route::get('/currentPt','hisdb\CurrentPatientController@show');
Route::get('/currentPt/get_entry','hisdb\CurrentPatientController@get_entry');
Route::post('/currentPt/table','hisdb\CurrentPatientController@table');

//// pat_enq registration ////
Route::get('/pat_enq','hisdb\PatEnqController@show');
Route::get('/pat_enq/table','hisdb\PatEnqController@table');
Route::post('/pat_enq/form','hisdb\PatEnqController@form');

//// Nursing ED (Triage Info) page ///
Route::get('/nursingED','hisdb\NursingEDController@show');
Route::get('/nursingED/table','hisdb\NursingEDController@table');
Route::post('/nursingED/form','hisdb\NursingEDController@form');

//// Nursing (Triage Info) page ///
Route::get('/nursing','hisdb\NursingController@show');
Route::get('/nursing/table','hisdb\NursingController@table');
Route::post('/nursing/form','hisdb\NursingController@form');

//// Antenatal page ///
Route::get('/antenatal','hisdb\AntenatalController@show');
Route::get('/antenatal/table','hisdb\AntenatalController@table');
Route::post('/antenatal/form','hisdb\AntenatalController@form');
Route::get('/antenatal_chart','hisdb\AntenatalController@chart');

//// Paediatric page ///
Route::get('/paediatric','hisdb\PaediatricController@show');
Route::get('/paediatric/table','hisdb\PaediatricController@table');
Route::post('/paediatric/form','hisdb\PaediatricController@form');

//// Ward Panel page ///
Route::get('/wardpanel','hisdb\WardPanelController@show');
Route::get('/wardpanel/table','hisdb\WardPanelController@table');
Route::post('/wardpanel/form','hisdb\WardPanelController@form');

//// Nursing Action Planpage ///
Route::get('/nursingActionPlan','hisdb\NursingActionPlanController@show');
Route::get('/nursingActionPlan/table','hisdb\NursingActionPlanController@table');
Route::post('/nursingActionPlan/form','hisdb\NursingActionPlanController@form');
Route::get('/nursingActionPlan/treatment_chart','hisdb\NursingActionPlanController@treatment_chart');
Route::get('/nursingActionPlan/observation_chart','hisdb\NursingActionPlanController@observation_chart');
Route::get('/nursingActionPlan/feeding_chart','hisdb\NursingActionPlanController@feeding_chart');
Route::get('/nursingActionPlan/imgDiag_chart','hisdb\NursingActionPlanController@imgDiag_chart');
Route::get('/nursingActionPlan/bloodTrans_chart','hisdb\NursingActionPlanController@bloodTrans_chart');
Route::get('/nursingActionPlan/exams_chart','hisdb\NursingActionPlanController@exams_chart');
Route::get('/nursingActionPlan/procedure_chart','hisdb\NursingActionPlanController@procedure_chart');


//// Nursing Note page ///
Route::get('/nursingnote','hisdb\NursingNoteController@show');
Route::get('/nursingnote/table','hisdb\NursingNoteController@table');
Route::post('/nursingnote/form','hisdb\NursingNoteController@form');
Route::get('/nursingnote/invChart_chart','hisdb\InvChartController@invChart_chart');
Route::get('/nursingnote/fitchart_chart','hisdb\NursingNoteController@fitchart_chart');
Route::get('/nursingnote/circulation_chart','hisdb\NursingNoteController@circulation_chart');
Route::get('/nursingnote/slidingScale_chart','hisdb\NursingNoteController@slidingScale_chart');
Route::get('/nursingnote/othersChart_chart','hisdb\NursingNoteController@othersChart_chart');
Route::get('/nursingnote/bladder_chart','hisdb\NursingNoteController@bladder_chart');
Route::post('/morsefallscale/form','hisdb\MorseFallScaleController@form');
Route::get('/morsefallscale/table','hisdb\MorseFallScaleController@table');
Route::post('/thrombophlebitis/form','hisdb\ThrombophlebitisController@form');
Route::get('/thrombophlebitis/table','hisdb\ThrombophlebitisController@table');

//// Client Progress Note (Doctor Note) page ///
Route::get('/clientprogressnote','hisdb\ClientProgressNoteController@show');
Route::get('/clientprogressnote/table','hisdb\ClientProgressNoteController@table');
Route::post('/clientprogressnote/form','hisdb\ClientProgressNoteController@form');

//// Client Progress Note (Doctor Note (Referral)) page ///
Route::get('/clientprogressnoteref','hisdb\ClientProgressNoteRefController@show');
Route::get('/clientprogressnoteref/table','hisdb\ClientProgressNoteRefController@table');
Route::post('/clientprogressnoteref/form','hisdb\ClientProgressNoteRefController@form');

//// Doctor Note page ///
Route::get('/doctornote','hisdb\DoctorNoteController@show');
Route::get('/bpgraph','hisdb\DoctorNoteController@bpgraph');
Route::get('/iograph','hisdb\DoctorNoteController@iograph');
Route::get('/doctornote/table','hisdb\DoctorNoteController@table');
Route::post('/doctornote/form','hisdb\DoctorNoteController@form');
Route::get('/doctornote/showpdf','hisdb\DoctorNoteController@showpdf');
Route::get('/doctornote/otbook_chart','hisdb\DoctorNoteController@otbook_chart');
Route::get('/doctornote/radClinic_chart','hisdb\DoctorNoteController@radClinic_chart');
Route::get('/doctornote/mri_chart','hisdb\DoctorNoteController@mri_chart');
Route::get('/doctornote/physio_chart','hisdb\DoctorNoteController@physio_chart');
Route::get('/doctornote/dressing_chart','hisdb\DoctorNoteController@dressing_chart');

//// Request For page ///
Route::get('/requestfor','hisdb\RequestForController@show');
Route::get('/requestfor/table','hisdb\RequestForController@table');
Route::post('/requestfor/form','hisdb\RequestForController@form');
Route::get('/requestfor/showpdf','hisdb\RequestForController@showpdf');

//// Admission Handover page ///
Route::get('/admhandover','hisdb\AdmHandoverController@show');
Route::get('/admhandover/table','hisdb\AdmHandoverController@table');
Route::post('/admhandover/form','hisdb\AdmHandoverController@form');
Route::get('/admhandover/showpdf','hisdb\AdmHandoverController@showpdf');

//// Dietetic Care Notes page ///
Route::get('/dieteticCareNotes','hisdb\DieteticCareNotesController@show');
Route::get('/dieteticCareNotes/table','hisdb\DieteticCareNotesController@table');
Route::post('/dieteticCareNotes/form','hisdb\DieteticCareNotesController@form');

//// Diet Order page ///
Route::get('/dietorder','hisdb\DietOrderController@show');
Route::get('/dietorder/table','hisdb\DietOrderController@table');
Route::post('/dietorder/form','hisdb\DietOrderController@form');

//// Discharge Summary page ///
Route::get('/dischgsummary','hisdb\DischgSummaryController@show');
Route::get('/dischgsummary/table','hisdb\DischgSummaryController@table');
Route::post('/dischgsummary/form','hisdb\DischgSummaryController@form');

//// Order Communication page ///
Route::get('/ordcom','hisdb\OrdcomController@show');
Route::get('/ordcom/table','hisdb\OrdcomController@table');
Route::post('/ordcom/form','hisdb\OrdcomController@form');

//// Discharge page IP ///
Route::get('/discharge','hisdb\DischargeController@show');
Route::get('/discharge/table','hisdb\DischargeController@table');
Route::post('/discharge/form','hisdb\DischargeController@form');
Route::get('/discharge/showpdf','hisdb\DischargeController@showpdf');

//// Discharge page OP///
Route::get('/endConsult','hisdb\EndConsultController@show');
Route::get('/endConsult/table','hisdb\EndConsultController@table');
Route::post('/endConsult/form','hisdb\EndConsultController@form');

//// Diagnose page ///
Route::post('/diagnose','util\DiagnoseController@post');
Route::get('/diagnosedel','util\DiagnoseController@test');

//// Case Note page ////
Route::get('/casenote','hisdb\CaseNoteController@show');
Route::get('/casenote/get_entry','hisdb\CaseNoteController@get_entry');
Route::post('/casenote/post_entry','hisdb\CaseNoteController@post_entry');
Route::post('/casenote/save_patient','hisdb\CaseNoteController@save_patient');
Route::post('/casenote/save_episode','hisdb\CaseNoteController@save_episode');
Route::post('/casenote/save_adm','hisdb\CaseNoteController@save_adm');
Route::post('/casenote/save_gl','hisdb\CaseNoteController@save_gl');
Route::post('/casenote/new_occup_form','hisdb\CaseNoteController@new_occup_form');
Route::post('/casenote/new_title_form','hisdb\CaseNoteController@new_title_form');
Route::post('/casenote/new_areacode_form','hisdb\CaseNoteController@new_areacode_form');
Route::post('/casenote/new_relationship_form','hisdb\CaseNoteController@new_relationship_form');

//// IP REG FORM ////
Route::get('/RegFormIP/RegFormIP_pdf','hisdb\RegFormIPController@RegFormIP_pdf');

//// PRESCRIPTION FORM ////
Route::get('/PrescriptionForm/PrescriptionForm_pdf','hisdb\PrescriptionFormController@PrescriptionForm_pdf');

///////////////////Fixed Asset setup////////////////////////////////////

//// Fixed Asset Location setup page ///
Route::get('/location','finance\LocationController@show');
Route::get('/location/table','finance\LocationController@table');
Route::post('/location/form','finance\LocationController@form');

//// Fixed Asset assettype setup page ///
Route::get('/assettype','finance\assettypeController@show');
Route::get('/assettype/table','finance\assettypeController@table');
Route::post('/assettype/form','finance\assettypeController@form');

/// assetcategory ///
Route::get('/assetcategory','finance\assetcategoryController@show');
Route::get('/assetcategory/table','finance\assetcategoryController@table');
Route::post('/assetcategory/form','finance\assetcategoryController@form');

/// Fixed Asset Enquiry /// 
Route::get('/assetenquiry','finance\assetenquiryController@show');
Route::get('/assetenquiry/table','finance\assetenquiryController@table');
Route::post('/assetenquiry/form','finance\assetenquiryController@form');
Route::get('/assetenquiry/showpdf','finance\assetenquiryController@showpdf');

/// Fixed Asset Enquiry Detail/// 
Route::get('/assetenquiryDtl2','finance\assetenquiryDtl2Controller@show');
Route::get('/assetenquiryDtl2/table','finance\assetenquiryDtl2Controller@table');
Route::post('/assetenquiryDtl2/form','finance\assetenquiryDtl2Controller@form');

/// Asset Transfer /// 
Route::get('/assettransfer2','finance\assettransfer2Controller@show');
Route::get('/assettransfer2/table','finance\assettransfer2Controller@table');
Route::post('/assettransfer2/form','finance\assettransfer2Controller@form');

/// Asset Transfer /// 
Route::get('/assettransfer','finance\assettransferController@show');
Route::get('/assettransfer/table','finance\assettransferController@table');
Route::post('/assettransfer/form','finance\assettransferController@form');

/// Asset Transfer /// 
Route::get('/assetWriteOff','finance\assetWriteOffController@show');
Route::get('/assetWriteOff/table','finance\assetWriteOffController@table');
Route::post('/assetWriteOff/form','finance\assetWriteOffController@form');

/// Register setup /// 
Route::get('/assetregister','finance\assetregisterController@show');
Route::get('/assetregister/table','finance\assetregisterController@table');
Route::post('/assetregister/form','finance\assetregisterController@form'); 

//// facontrol ///
Route::get('/facontrol','finance\facontrolController@show');
Route::get('/facontrol/table','finance\facontrolController@table');
Route::post('/facontrol/form','finance\facontrolController@form');

//// till ///
Route::get('/till','finance\tillController@show');
Route::get('/till_close','finance\tillController@till_close');
Route::get('/till/table','finance\tillController@table');
Route::post('/till/form','finance\tillController@form');

Route::get('/gltb','finance\gltbController@show');
Route::get('/gltb/table','finance\gltbController@table');
Route::post('/gltb/form','finance\gltbController@form');

// //// facontrol2 ///
// Route::get('/facontrol2','finance\facontrolController2@show');
// Route::get('/facontrol2/table','finance\facontrolController2@table');
// Route::post('/facontrol2/form','finance\facontrolController2@form');

//// fadepricate ///
Route::get('/fadepricate','finance\fadepricateController@show');
Route::get('/fadepricate/table','finance\fadepricateController@table');
Route::post('/fadepricate/form','finance\fadepricateController@form');


Route::get('/preview','hisdb\ReviewController@review');
Route::get('/upload','hisdb\ReviewController@upload');

//change carousel image to small thumbnail size
Route::get('/thumbnail/{folder}/{image_path}','hisdb\ReviewController@thumbnail');

//download file patient enquiry
Route::get('/download/{folder}/{image_path}','hisdb\ReviewController@download');

/// Test route /// 
Route::get('/test','util\TestController@show');
Route::get('/test/table','util\TestController@table');
Route::post('/test/form','util\TestController@form');
Route::get('/export_csv','util\ExportController@show');
Route::get('/export_csv/table','util\ExportController@table');

Route::get('/barcode','util\BarcodeController@show');
Route::post('/barcode/form','util\BarcodeController@form');
Route::post('/barcode/print','util\BarcodeController@print');

Route::get('/num2words','util\num2wordsController@show');
Route::post('/num2words/form','util\num2wordsController@form');

/// webserice route /// 
Route::get('/webservice','util\WebserviceController@page');
Route::get('/webservice/table','util\WebserviceController@table');
Route::post('/webservice/form','util\WebserviceController@form');

//dari ptcare

//// sysparam ////
Route::get('/ptcare_sysparam_triage_color','patientcare\SysparamController@sysparam_triage_color');
Route::get('/ptcare_sysparam_triage_color_chk','patientcare\SysparamController@sysparam_triage_color_chk');

Route::get('/ptcare_prescription', "patientcare\PrescriptionController@index");
Route::get('/ptcare_prescription/{id}', "patientcare\PrescriptionController@detail");

Route::get('/ptcare_emergency','patientcare\EmergencyController@index');

Route::get('/ptcare_dashboard','patientcare\eisController@dashboard');
Route::get('/ptcare_eis','patientcare\eisController@show')->name('eis');
Route::get('/ptcare_reveis','patientcare\eisController@reveis')->name('reveis');
Route::get('/ptcare_pivot_get', 'patientcare\eisController@table');

Route::get('/ptcare_doctornote','patientcare\DoctornoteController@index');
Route::get('/ptcare_doctornote/table','patientcare\DoctornoteController@table');
Route::post('/ptcare_doctornote/form','patientcare\DoctornoteController@form');
Route::post('/ptcare_doctornote_transaction_save', "patientcare\DoctornoteController@transaction_save");

Route::get('/ptcare_requestfor','patientcare\RequestForController@show');
Route::get('/ptcare_requestfor/table','patientcare\RequestForController@table');
Route::post('/ptcare_requestfor/form','patientcare\RequestForController@form');

Route::get('/ptcare_admhandover','patientcare\AdmHandoverController@show');
Route::get('/ptcare_admhandover/table','patientcare\AdmHandoverController@table');
Route::post('/ptcare_admhandover/form','patientcare\AdmHandoverController@form');

Route::get('/ptcare_dieteticCareNotes','patientcare\DieteticCareNotesController@show');
Route::get('/ptcare_dieteticCareNotes/table','patientcare\DieteticCareNotesController@table');
Route::post('/ptcare_dieteticCareNotes/form','patientcare\DieteticCareNotesController@form');

Route::get('/ptcare_phys','patientcare\physioController@show');
Route::get('/ptcare_phys/table','patientcare\physioController@table');
Route::post('/ptcare_phys/form','patientcare\physioController@form');

Route::get('/ptcare_nursing','patientcare\NursingController@show');
Route::get('/ptcare_nursing/table','patientcare\NursingController@table');
Route::post('/ptcare_nursing/form','patientcare\NursingController@form');

Route::get('/ptcare_nursingAppt','patientcare\NursingApptController@show');
Route::get('/ptcare_nursingAppt/table','patientcare\NursingApptController@table');
Route::post('/ptcare_nursingAppt/form','patientcare\NursingApptController@form');

Route::get('/ptcare_preview','patientcare\PreviewController@preview');
Route::get('/ptcare_preview/data','patientcare\PreviewController@previewdata');
Route::get('/ptcare_localpreview','patientcare\WebserviceController@localpreview');

Route::get('/ptcare_nursingnote','patientcare\NursingNoteController@show');
Route::get('/ptcare_nursingnote/table','patientcare\NursingNoteController@table');
Route::post('/ptcare_nursingnote/form','patientcare\NursingNoteController@form');

Route::get('/ptcare_thumbnail/{folder}/{image_path}','patientcare\PreviewController@thumbnail');

Route::get('/ptcare_previewvideo/{id}','patientcare\PreviewController@previewvideo');

Route::get('/ptcare_upload','patientcare\PreviewController@upload');
Route::post('/ptcare_upload','patientcare\PreviewController@form');

//dari rehab

Route::get('/rehab','rehab\RehabController@index');
Route::get('/rehab/table','rehab\RehabController@table');
Route::post('/rehab/form','rehab\RehabController@form');
// Route::post('/ptcare_doctornote_transaction_save', "rehab\RehabController@transaction_save");

//// Physiotherapy page ////
Route::get('/sixMinWalking','rehab\SixMinWalkingController@show');
Route::get('/sixMinWalking/table','rehab\SixMinWalkingController@table');
Route::post('/sixMinWalking/form','rehab\SixMinWalkingController@form');

Route::get('/bergBalanceTest','rehab\BergBalanceTestController@show');
Route::get('/bergBalanceTest/table','rehab\BergBalanceTestController@table');
Route::post('/bergBalanceTest/form','rehab\BergBalanceTestController@form');

Route::get('/oswestryQuest','rehab\OswestryQuestController@show');
Route::get('/oswestryQuest/table','rehab\OswestryQuestController@table');
Route::post('/oswestryQuest/form','rehab\OswestryQuestController@form');

Route::get('/posturalAssessment','rehab\PosturalAssessmentController@show');
Route::get('/posturalAssessment/table','rehab\PosturalAssessmentController@table');
Route::post('/posturalAssessment/form','rehab\PosturalAssessmentController@form');

Route::get('/neuroAssessment','rehab\NeuroAssessmentController@show');
Route::get('/neuroAssessment/table','rehab\NeuroAssessmentController@table');
Route::post('/neuroAssessment/form','rehab\NeuroAssessmentController@form');

Route::get('/motorScale','rehab\MotorScaleController@show');
Route::get('/motorScale/table','rehab\MotorScaleController@table');
Route::post('/motorScale/form','rehab\MotorScaleController@form');

Route::get('/spinalCord','rehab\SpinalCordController@show');
Route::get('/spinalCord/table','rehab\SpinalCordController@table');
Route::post('/spinalCord/form','rehab\SpinalCordController@form');

/// occuptherapy
Route::get('/occupTherapy','rehab\OccupTherapyController@show');
Route::get('/occupTherapy/table','rehab\OccupTherapyController@table');
Route::post('/occupTherapy/form','rehab\OccupTherapyController@form');

Route::get('/occupTherapy_cognitive/table','rehab\OccupTherapyCognitiveController@table');
Route::post('/occupTherapy_cognitive/form','rehab\OccupTherapyCognitiveController@form');

Route::get('/occupTherapy_barthel/table','rehab\OccupTherapyBarthelController@table');
Route::post('/occupTherapy_barthel/form','rehab\OccupTherapyBarthelController@form');

Route::get('/occupTherapy_upperExtremity/table','rehab\OccupTherapyUpperExtremityController@table');
Route::post('/occupTherapy_upperExtremity/form','rehab\OccupTherapyUpperExtremityController@form');

Route::get('/occupTherapy_notes/table','rehab\OccupTherapyNotesController@table');
Route::post('/occupTherapy_notes/form','rehab\OccupTherapyNotesController@form');

//dari appointment

Route::get('/appointment','appointment\AppointmentController@index');
Route::get('/appointment/table','appointment\AppointmentController@table');
Route::post('/appointment/form','appointment\AppointmentController@form');

//dari dialysis

Route::get('/dialysis_pat_mast','dialysis\PatmastController@show');
Route::get('/dialysis_pat_mast/get_entry','dialysis\PatmastController@get_entry');
Route::get('/dialysis_pat_mast/post_entry','dialysis\PatmastController@post_entry');
Route::post('/dialysis_pat_mast/post_entry','dialysis\PatmastController@post_entry');
Route::post('/dialysis_pat_mast/save_patient','dialysis\PatmastController@save_patient');
Route::post('/dialysis_pat_mast/save_episode','dialysis\PatmastController@save_episode');
Route::post('/dialysis_pat_mast/save_adm','dialysis\PatmastController@save_adm');
Route::post('/dialysis_pat_mast/save_gl','dialysis\PatmastController@save_gl');
Route::post('/dialysis_pat_mast/new_occup_form','dialysis\PatmastController@new_occup_form');
Route::post('/dialysis_pat_mast/new_title_form','dialysis\PatmastController@new_title_form');
Route::post('/dialysis_pat_mast/new_areacode_form','dialysis\PatmastController@new_areacode_form');
Route::post('/dialysis_pat_mast/new_relationship_form','dialysis\PatmastController@new_relationship_form');
Route::post('/dialysis_pat_mast/auto_save','dialysis\PatmastController@auto_save');

Route::get('/dialysis_nursing','dialysis\NursingController@show');
Route::get('/dialysis_nursing/table','dialysis\NursingController@table');
Route::post('/dialysis_nursing/form','dialysis\NursingController@form');

Route::get('/dialysis_doctornote','dialysis\DoctornoteController@index');
Route::get('/dialysis_doctornote/table','dialysis\DoctornoteController@table');
Route::post('/dialysis_doctornote/form','dialysis\DoctornoteController@form');
Route::post('/dialysis_doctornote_transaction_save', "dialysis\DoctornoteController@transaction_save");

Route::get('/dialysis_dialysis','dialysis\DialysisController@index');
Route::get('/dialysis_dialysis/table','dialysis\DialysisController@table');
Route::post('/dialysis_dialysis/form','dialysis\DialysisController@form');
Route::get('/dialysis_dialysis_event','dialysis\DialysisController@dialysis_event');
Route::post('/dialysis_change_status', "dialysis\DialysisController@change_status");
Route::post('/dialysis_save_dialysis', "dialysis\DialysisController@save_dialysis");
Route::post('/dialysis_save_dialysis_completed', "dialysis\DialysisController@save_dialysis_completed");
Route::post('/dialysis_save_epis_dialysis', "dialysis\DialysisController@save_epis_dialysis");
Route::get('/dialysis_get_data_dialysis', "dialysis\DialysisController@get_data_dialysis");
Route::post('/dialysis_dialysis_transaction_save', "dialysis\DialysisController@dialysis_transaction_save");
Route::get('/dialysis_check_pt_mode', "dialysis\DialysisController@check_pt_mode");
Route::get('/dialysis_verifyuser_dialysis', "dialysis\DialysisController@verifyuser_dialysis");
Route::get('/dialysis_verifyuser_admin_dialysis', "dialysis\DialysisController@verifyuser_admin_dialysis");

Route::get('/dialysis_bloodtest/table', "dialysis\DialysisController@bloodtesttable");

Route::get('/dialysis_enquiry','dialysis\enquiryController@show');
Route::get('/dialysis_enquiry/table','dialysis\enquiryController@table');

Route::get('/dialysis_enquiry_order','dialysis\enquiryController@show_order');
Route::get('/dialysis_enquiry_order/table','dialysis\enquiryController@table');