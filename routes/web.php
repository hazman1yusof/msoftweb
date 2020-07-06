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
Route::post('/sessionUnit','HomeController@changeSessionUnit');
Route::get('/login','SessionController@create')->name('login');
Route::get('/loginappt','SessionController@create2')->name('login2');
Route::post('/login','SessionController@store');
Route::get('/logout','SessionController@destroy')->name('logout');

/// Utility function ///
Route::get('/util/getcompid','UtilController@getcompid');
Route::get('/util/getpadlen','UtilController@getpadlen');
Route::get('/util/get_value_default','UtilController@get_value_default');
Route::get('/util/get_table_default','UtilController@get_table_default');
Route::get('/util/save_table_default','UtilController@defaultSetter');
Route::get('/util/input_check','UtilController@input_check'); //jgn guna
Route::get('/util/mycard_read','util\MycardController@get_data');

/// announcement thingy ///
Route::get('/announcement/generate','setup\AnnouncementController@generate');

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

//// sysparam ////
Route::get('/sysparam_bed_status','SysparamController@sysparam_bed_status');
Route::get('/sysparam_stat','SysparamController@sysparam_stat');
Route::get('/sysparam_triage_color','SysparamController@sysparam_triage_color');
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
Route::post('/doctor_maintenance/save_colorph','hisdb\DoctorMaintenanceController@save_colorph');

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

//// inventory Transaction setup page ///
Route::get('/inventoryTransaction','material\InventoryTransactionController@show');
Route::get('/inventoryTransaction/table','material\InventoryTransactionController@table');
Route::post('/inventoryTransaction/form','material\InventoryTransactionController@form');
Route::get('/inventoryTransaction/form','material\InventoryTransactionController@form');
Route::post('/inventoryTransactionDetail/form','material\InventoryTransactionDetailController@form');

//// purchase Request setup page ///
Route::get('/purchaseRequest','material\PurchaseRequestController@show');
Route::get('/purchaseRequest/table','material\PurchaseRequestController@table');
Route::post('/purchaseRequest/form','material\PurchaseRequestController@form');
Route::get('/purchaseRequest/form','material\PurchaseRequestController@form');
Route::get('/purchaseRequest/showpdf','material\PurchaseRequestController@showpdf');
Route::post('/purchaseRequestDetail/form','material\PurchaseRequestDetailController@form');
Route::get('/purchaseRequestDetail/table','material\PurchaseRequestDetailController@table');

//// purchase Order setup page ///
Route::get('/purchaseOrder','material\PurchaseOrderController@show');
Route::get('/purchaseOrder/table','material\PurchaseOrderController@table');
Route::post('/purchaseOrder/form','material\PurchaseOrderController@form');
Route::get('/purchaseOrder/form','material\PurchaseOrderController@form');
Route::post('/purchaseOrderDetail/form','material\PurchaseOrderDetailController@form');
Route::get('/purchaseOrderDetail/table','material\PurchaseOrderDetailController@table');

//// delivery Order setup page ///
Route::get('/deliveryOrder','material\DeliveryOrderController@show');
Route::get('/deliveryOrder/table','material\DeliveryOrderController@table');
Route::post('/deliveryOrder/form','material\DeliveryOrderController@form');
Route::get('/deliveryOrder/form','material\DeliveryOrderController@form');
Route::post('/deliveryOrderDetail/form','material\DeliveryOrderDetailController@form');
Route::get('/deliveryOrderDetail/table','material\DeliveryOrderDetailController@table');

//// good Return setup page ///
Route::get('/goodReturn','material\GoodReturnController@show');
Route::get('/goodReturn/table','material\GoodReturnController@table');
Route::post('/goodReturn/form','material\GoodReturnController@form');
Route::get('/goodReturn/form','material\GoodReturnController@form');
Route::post('/goodReturnDetail/form','material\GoodReturnDetailController@form');

//// sequence material setup ///
Route::get('/sequence','material\SequenceController@show');
Route::get('/sequence/table','material\SequenceController@table');
Route::post('/sequence/form','material\SequenceController@form');

//// categoryINV material setup ///
Route::get('/categoryinv','material\CategoryInvController@show');
Route::get('/categoryinv/table','material\CategoryInvController@table');
Route::post('/categoryinv/form','material\CategoryInvController@form');

//// invoice AP setup page ///
Route::get('/invoiceAP','finance\InvoiceAPController@show');
Route::get('/invoiceAP/table','finance\InvoiceAPController@table');
Route::post('/invoiceAP/form','finance\InvoiceAPController@form');
Route::get('/invoiceAP/form','finance\InvoiceAPController@form');
Route::post('/invoiceAPDetail/form','finance\InvoiceAPDetailController@form');

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

//// Stock Loc Enquiry ///
Route::get('/stocklocEnquiry','material\StocklocEnquiryController@show');
Route::get('/stocklocEnquiry/table','material\StocklocEnquiryController@table');
Route::post('/stocklocEnquiry/form','material\StocklocEnquiryController@form');

//// Item Enquiry /// Class=Pharmacy
Route::get('/itemEnquiry','material\ItemEnquiryController@show');
Route::get('/itemEnquiry/table','material\ItemEnquiryController@table');
Route::post('/itemEnquiry/form','material\ItemEnquiryController@form');
Route::get('/itemEnquiry/form','material\ItemEnquiryController@form');

//// Stock Location ///
Route::get('/stockloc','material\StocklocController@show');
Route::get('/stockloc/table','material\StocklocController@table');
Route::post('/stockloc/form','material\StocklocController@form');\

//// Product ///
Route::get('/product','material\ProductController@show');
Route::get('/product/table','material\ProductController@table');
Route::post('/product/form','material\ProductController@form');

/////////// appointment resource setup page ////////////////////////////////////
Route::get('/apptrsc','hisdb\AppointmentController@show');
Route::get('/apptrsc/table','hisdb\AppointmentController@table');
Route::post('/apptrsc/form','hisdb\AppointmentController@form');
Route::get('/apptrsc/getEvent','hisdb\AppointmentController@getEvent');
Route::post('/apptrsc/addEvent','hisdb\AppointmentController@addEvent');
Route::post('/apptrsc/editEvent','hisdb\AppointmentController@editEvent');
Route::post('/apptrsc/delEvent','hisdb\AppointmentController@delEvent');

//////////////////////////////finance setup///////////////////////////////////////

//// debtortype ////
Route::get('/debtortype','finance\debtortypeController@show');
Route::get('/debtortype/table','finance\debtortypeController@table');
Route::post('/debtortype/form','finance\debtortypeController@form');

//// GL Enquiry ///
Route::get('/glenquiry','finance\GlenquiryController@show');
Route::get('/glenquiry/table','finance\GlenquiryController@table');
Route::post('/glenquiry/form','finance\GlenquiryController@form');

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

//// GlMaster setup ///
Route::get('/glmaster','finance\GlmasterController@show');
Route::get('/glmaster/table','finance\GlmasterController@table');
Route::post('/glmaster/form','finance\GlmasterController@form');

//// period setup ///
Route::get('/period','finance\PeriodController@show');
Route::get('/period/table','finance\PeriodController@table');
Route::post('/period/form','finance\PeriodController@form');

//// Debtor Master setup ///
Route::get('/debtorMaster','finance\DebtorMasterController@show');
Route::get('/debtorMaster/table','finance\DebtorMasterController@table');
Route::post('/debtorMaster/form','finance\DebtorMasterController@form');

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

//// Bank setup ///
Route::get('/bankEnquiry','finance\BankEnquiryController@show');
Route::get('/bankEnquiry/table','finance\BankEnquiryController@table');
Route::post('/bankEnquiry/form','finance\BankEnquiryController@form');

//// Direct Payment ///
Route::get('/directPayment','finance\DirectPaymentController@show');
Route::get('/directPayment/table','finance\DirectPaymentController@table');
Route::post('/directPayment/form','finance\DirectPaymentController@form');
Route::get('/directPayment/form','finance\DirectPaymentController@form');
Route::post('/directPaymentDetail/form','finance\DirectPaymentDetailController@form');

//// Credit Debit Transaction ///
Route::get('/creditDebitTrans','finance\CreditDebitTransController@show');
Route::get('/creditDebitTrans/table','finance\CreditDebitTransController@table');
Route::post('/creditDebitTrans/form','finance\CreditDebitTransController@form');
Route::get('/creditDebitTrans/form','finance\CreditDebitTransController@form');
Route::post('/creditDebitTransDetail/form','finance\CreditDebitTransDetailController@form');

//// Payment Voucher Transaction ///
Route::get('/paymentVoucher','finance\PaymentVoucherController@show');
Route::get('/paymentVoucher/table','finance\PaymentVoucherController@table');
Route::post('/paymentVoucher/form','finance\PaymentVoucherController@form');
Route::get('/paymentVoucher/form','finance\PaymentVoucherController@form');
Route::post('/paymentVoucherDetail/form','finance\PaymentVoucherDetailController@form');

////////////////patient mgt setup/////////////////////////////////////////

//// pat_mast registration ////
Route::get('/pat_mast','hisdb\PatmastController@show');
Route::get('/pat_mast/get_entry','hisdb\PatmastController@get_entry');
Route::post('/pat_mast/post_entry','hisdb\PatmastController@post_entry');
Route::post('/pat_mast/save_patient','hisdb\PatmastController@save_patient');
Route::post('/pat_mast/save_episode','hisdb\PatmastController@save_episode');
Route::post('/pat_mast/save_adm','hisdb\PatmastController@save_adm');
Route::post('/pat_mast/save_gl','hisdb\PatmastController@save_gl');
Route::post('/pat_mast/new_occup_form','hisdb\PatmastController@new_occup_form');
Route::post('/pat_mast/new_title_form','hisdb\PatmastController@new_title_form');
Route::post('/pat_mast/new_areacode_form','hisdb\PatmastController@new_areacode_form');
Route::post('/pat_mast/new_relationship_form','hisdb\PatmastController@new_relationship_form');


Route::post('/episode/save_doc','hisdb\PatmastController@save_doc');
Route::post('/episode/save_bed','hisdb\PatmastController@save_bed');
Route::post('/episode/save_nok','hisdb\PatmastController@save_nok');
Route::post('/episode/save_emr','hisdb\PatmastController@save_emr');
Route::get('/episode/get_episode_by_mrn','hisdb\PatmastController@get_episode_by_mrn');


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
Route::get('/pat_enq/get_entry','hisdb\PatEnqController@get_entry');
Route::post('/pat_enq/post_entry','hisdb\PatEnqController@post_entry');
Route::post('/pat_enq/save_patient','hisdb\PatEnqController@save_patient');

//// Nursing page ///
Route::get('/nursing','hisdb\NursingController@show');
Route::get('/nursing/table','hisdb\NursingController@table');
Route::post('/nursing/form','hisdb\NursingController@form');

//// Ward Panel page ///
Route::get('/wardpanel','hisdb\WardPanelController@show');
Route::get('/wardpanel/table','hisdb\WardPanelController@table');
Route::post('/wardpanel/form','hisdb\WardPanelController@form');

//// Diet Order page ///
Route::get('/dietorder','hisdb\DietOrderController@show');
Route::get('/dietorder/table','hisdb\DietOrderController@table');
Route::post('/dietorder/form','hisdb\DietOrderController@form');

//// Discharge Summary page ///
Route::get('/dischgsummary','hisdb\DischgSummaryController@show');
Route::get('/dischgsummary/table','hisdb\DischgSummaryController@table');
Route::post('/dischgsummary/form','hisdb\DischgSummaryController@form');

///////////////////fixed asset setup////////////////////////////////////

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

/// Fixed Asset Inquiry /// 
Route::get('/assetenquiry','finance\assetenquiryController@show');
Route::get('/assetenquiry/table','finance\assetenquiryController@table');
Route::post('/assetenquiry/form','finance\assetenquiryController@form');

/// Asset Transfer /// 
Route::get('/assettransfer','finance\assettransferController@show');
Route::get('/assettransfer/table','finance\assettransferController@table');
Route::post('/assettransfer/form','finance\assettransferController@form');

/// Register setup /// 
Route::get('/assetregister','finance\assetregisterController@show');
Route::get('/assetregister/table','finance\assetregisterController@table');
Route::post('/assetregister/form','finance\assetregisterController@form'); 

//// facontrol ///
Route::get('/facontrol','finance\facontrolController@show');
Route::get('/facontrol/table','finance\facontrolController@table');
Route::post('/facontrol/form','finance\facontrolController@form');

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
Route::post('/pat_enq/form','hisdb\ReviewController@form');

//change carousel image to small thumbnail size
Route::get('/thumbnail/{folder}/{image_path}','hisdb\ReviewController@thumbnail');

//download file patient enquiry
Route::get('/download/{folder}/{image_path}','hisdb\ReviewController@download');

/// Test route /// 
Route::get('/test','util\TestController@show');
Route::get('/test2','util\TestController@show2');
Route::get('/testpdf','util\TestController@pdf');
Route::get('/testpdf2','util\TestController@pdf2');
Route::post('/test/form','util\TestController@form'); 
Route::get('/testcalander','util\TestController@testcalander');
Route::get('/test_grid','util\TestController@test_grid');

Route::get('/test_email','util\TestController@show_email');
Route::post('/test_email_send','util\TestController@send_email');

Route::get('/barcode','util\BarcodeController@show');
Route::post('/barcode/form','util\BarcodeController@form');
Route::post('/barcode/print','util\BarcodeController@print');

Route::get('/num2words','util\num2wordsController@show');
Route::post('/num2words/form','util\num2wordsController@form');