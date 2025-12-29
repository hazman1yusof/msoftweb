<?php

namespace App\Http\Controllers\patientcare;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;
use PDF;

class AdmHandoverApptController extends defaultController
{
    
    var $table;
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function show(Request $request)
    {
        return view('patientcare.admhandoverAppt');
    }
    
    public function form(Request $request)
    {
        DB::enableQueryLog();
        switch($request->action){
            
            case 'save_table_admHandoverAppt':
            
                switch($request->oper){
                    case 'add':
                        return $this->add($request);
                    case 'edit':
                        return $this->edit($request);
                    default:
                        return 'error happen..';
                }

            case 'get_table_admhandoverAppt':
                return $this->get_table_admhandoverAppt($request);
        }
    }

    public function add(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.admhandover')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn_admHandover,
                    'episno' => $request->episno_admHandover,
                    'dateofadm' => $request->dateofadm,
                    'reasonadm' => $request->reasonadm,
                    'type' => $request->type,
                    'weights' => $request->vs_weight,
                    'rtkpcr' => $request->rtkpcr,
                    'rtkpcr_remark' => $request->rtkpcr_remark,
                    'bloodinv' => $request->bloodinv,
                    'bloodinv_remark' => $request->bloodinv_remark,
                    'branula' => $request->branula,
                    'branula_remark' => $request->branula_remark,
                    'scan' => $request->scan,
                    'scan_remark' => $request->scan_remark,
                    'insurance' => $request->insurance,
                    'insurance_remark' => $request->insurance_remark,
                    'medication' => $request->medication,
                    'medication_remark' => $request->medication_remark,
                    'consent' => $request->consent,
                    'consent_remark' => $request->consent_remark,
                    'smoking' => $request->smoking,
                    'smoking_remark' => $request->smoking_remark,
                    'nbm' => $request->nbm,
                    'nbm_remark' => $request->nbm_remark,
                    'report' => $request->report,
                    'adduser'  => session('username'),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur"),
                    'passoverby' => $request->passoverby,
                    'takeoverby' => $request->takeoverby,
                ]);

            DB::table('nursing.nurshistory')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn_admHandover,
                    'medicalhistory' => $request->medicalhistory,
                    'surgicalhistory' => $request->surgicalhistory,
                    // 'familymedicalhist' => $request->familymedicalhist,
                    'allergydrugs' => $request->allergydrugs,
                    'drugs_remarks' => $request->drugs_remarks,
                    'allergyplaster' => $request->allergyplaster,
                    'plaster_remarks' => $request->plaster_remarks,
                    'allergyfood' => $request->allergyfood,
                    'food_remarks' => $request->food_remarks,
                    'allergyenvironment' => $request->allergyenvironment,
                    'environment_remarks' => $request->environment_remarks,
                    'allergyothers' => $request->allergyothers,
                    'others_remarks' => $request->others_remarks,
                    'allergyunknown' => $request->allergyunknown,
                    'unknown_remarks' => $request->unknown_remarks,
                    'allergynone' => $request->allergynone,
                    'none_remarks' => $request->none_remarks,
                    'adduser'  => session('username'),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur"),
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur"),
                ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function edit(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $admhandover = DB::table('nursing.admhandover')
                        ->where('mrn','=',$request->mrn_admHandover)
                        ->where('episno','=',$request->episno_admHandover)
                        ->where('compcode','=',session('compcode'));
            
            if(!$admhandover->exists()){
                DB::table('nursing.admhandover')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_admHandover,
                        'episno' => $request->episno_admHandover,
                        'dateofadm' => $request->dateofadm,
                        'reasonadm' => $request->reasonadm,
                        'type' => $request->type,
                        'weights' => $request->vs_weight,
                        'diagnosis' => $request->diagnosis,
                        'rtkpcr' => $request->rtkpcr,
                        'rtkpcr_remark' => $request->rtkpcr_remark,
                        'bloodinv' => $request->bloodinv,
                        'bloodinv_remark' => $request->bloodinv_remark,
                        'branula' => $request->branula,
                        'branula_remark' => $request->branula_remark,
                        'scan' => $request->scan,
                        'scan_remark' => $request->scan_remark,
                        'insurance' => $request->insurance,
                        'insurance_remark' => $request->insurance_remark,
                        'medication' => $request->medication,
                        'medication_remark' => $request->medication_remark,
                        'consent' => $request->consent,
                        'consent_remark' => $request->consent_remark,
                        'smoking' => $request->smoking,
                        'smoking_remark' => $request->smoking_remark,
                        'nbm' => $request->nbm,
                        'nbm_remark' => $request->nbm_remark,
                        'report' => $request->report,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur"),
                        'passoverby' => $request->passoverby,
                        'takeoverby' => $request->takeoverby,
                    ]);
            }else{
                $admhandover
                ->update([
                        'dateofadm' => $request->dateofadm,
                        'reasonadm' => $request->reasonadm,
                        'type' => $request->type,
                        'weights' => $request->vs_weight,
                        'diagnosis' => $request->diagnosis,
                        'rtkpcr' => $request->rtkpcr,
                        'rtkpcr_remark' => $request->rtkpcr_remark,
                        'bloodinv' => $request->bloodinv,
                        'bloodinv_remark' => $request->bloodinv_remark,
                        'branula' => $request->branula,
                        'branula_remark' => $request->branula_remark,
                        'scan' => $request->scan,
                        'scan_remark' => $request->scan_remark,
                        'insurance' => $request->insurance,
                        'insurance_remark' => $request->insurance_remark,
                        'medication' => $request->medication,
                        'medication_remark' => $request->medication_remark,
                        'consent' => $request->consent,
                        'consent_remark' => $request->consent_remark,
                        'smoking' => $request->smoking,
                        'smoking_remark' => $request->smoking_remark,
                        'nbm' => $request->nbm,
                        'nbm_remark' => $request->nbm_remark,
                        'report' => $request->report,
                        'passoverby' => $request->passoverby,
                        'takeoverby' => $request->takeoverby,
                    ]);
            }

            $nurshistory = DB::table('nursing.nurshistory')
                ->where('mrn','=',$request->mrn_admHandover)
                ->where('compcode','=',session('compcode'));
            
            if(!$nurshistory->exists()){
                DB::table('nursing.nurshistory')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_admHandover,
                        'medicalhistory' => $request->medicalhistory,
                        'surgicalhistory' => $request->surgicalhistory,
                        // 'familymedicalhist' => $request->familymedicalhist,
                        'allergydrugs' => $request->allergydrugs,
                        'drugs_remarks' => $request->drugs_remarks,
                        'allergyplaster' => $request->allergyplaster,
                        'plaster_remarks' => $request->plaster_remarks,
                        'allergyfood' => $request->allergyfood,
                        'food_remarks' => $request->food_remarks,
                        'allergyenvironment' => $request->allergyenvironment,
                        'environment_remarks' => $request->environment_remarks,
                        'allergyothers' => $request->allergyothers,
                        'others_remarks' => $request->others_remarks,
                        'allergyunknown' => $request->allergyunknown,
                        'unknown_remarks' => $request->unknown_remarks,
                        'allergynone' => $request->allergynone,
                        'none_remarks' => $request->none_remarks,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur"),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur"),
                    ]);
            }else{
                $nurshistory
                    ->update([
                        'medicalhistory' => $request->medicalhistory,
                        'surgicalhistory' => $request->surgicalhistory,
                        // 'familymedicalhist' => $request->familymedicalhist,
                        'allergydrugs' => $request->allergydrugs,
                        'drugs_remarks' => $request->drugs_remarks,
                        'allergyplaster' => $request->allergyplaster,
                        'plaster_remarks' => $request->plaster_remarks,
                        'allergyfood' => $request->allergyfood,
                        'food_remarks' => $request->food_remarks,
                        'allergyenvironment' => $request->allergyenvironment,
                        'environment_remarks' => $request->environment_remarks,
                        'allergyothers' => $request->allergyothers,
                        'others_remarks' => $request->others_remarks,
                        'allergyunknown' => $request->allergyunknown,
                        'unknown_remarks' => $request->unknown_remarks,
                        'allergynone' => $request->allergynone,
                        'none_remarks' => $request->none_remarks,
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur"),
                    ]);
            }
            
            $queries = DB::getQueryLog();
            // dump($queries);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }

    public function get_table_admhandoverAppt(Request $request){
        
        $admhandover_obj = DB::table('nursing.admhandover')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$request->mrn)
                    ->where('episno','=',$request->episno);
        
        $episode_obj = DB::table('hisdb.episode')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$request->mrn)
                    ->where('episno','=',$request->episno);
    
        $nurshistory_obj = DB::table('nursing.nurshistory')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$request->mrn);

        $nursassessment_obj = DB::table('nursing.nursassessment')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$request->mrn)
                    ->where('episno','=',$request->episno);
        
        $pathealth_obj = DB::table('hisdb.pathealth')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$request->mrn)
                    ->where('episno','=',$request->episno);

        $responce = new stdClass();
        
        if($admhandover_obj->exists()){
            $admhandover_obj = $admhandover_obj->first();
            $responce->admhandover = $admhandover_obj;
        }
        
        if($episode_obj->exists()){
            $episode_obj = $episode_obj->first();
            $responce->episode = $episode_obj;
        }

        if($nurshistory_obj->exists()){
            $nurshistory_obj = $nurshistory_obj->first();
            $responce->nurshistory = $nurshistory_obj;
        }

        if($nursassessment_obj->exists()){
            $nursassessment_obj = $nursassessment_obj->first();
            $responce->nursassessment = $nursassessment_obj;
        }

        if($pathealth_obj->exists()){
            $pathealth_obj = $pathealth_obj->first();
            $responce->pathealth = $pathealth_obj;
        }
        
        return json_encode($responce);
        
    }
    
    public function showpdf(Request $request){

        $mrn = $request->mrn_admHandover;
        $episno = $request->episno_admHandover;

        $admhandover = DB::table('nursing.admhandover as adm')
                    ->select('adm.mrn','adm.episno','adm.dateofadm','adm.reasonadm','adm.type','adm.rtkpcr','adm.rtkpcr_remark','adm.bloodinv','adm.bloodinv_remark','adm.branula','adm.branula_remark','adm.scan','adm.scan_remark','adm.insurance','adm.insurance_remark','adm.medication','adm.medication_remark','adm.consent','adm.consent_remark','adm.smoking','adm.smoking_remark','adm.nbm','adm.nbm_remark','adm.report','adm.adduser','adm.adddate','nh.medicalhistory','nh.surgicalhistory','nh.familymedicalhist','nh.allergydrugs','nh.drugs_remarks','nh.allergyplaster','nh.plaster_remarks','nh.allergyfood','nh.food_remarks','nh.allergyenvironment','nh.environment_remarks','nh.allergyothers','nh.others_remarks','nh.allergyunknown','nh.unknown_remarks','nh.allergynone','nh.none_remarks','na.vs_weight','na.diagnosis','pm.Name','pm.Newic','e.admdoctor','d.doctorname')
                    ->leftjoin('nursing.nurshistory as nh', function($join) {
                        $join = $join->on('nh.mrn', '=', 'adm.mrn');
                        $join = $join->where('nh.compcode', '=', session('compcode'));
                    })
                    ->leftjoin('nursing.nursassessment as na', function($join) {
                        $join = $join->on('na.mrn', '=', 'adm.mrn');
                        $join = $join->on('na.episno', '=', 'adm.episno');
                        $join = $join->where('na.compcode', '=', session('compcode'));
                    })
                    ->leftjoin('hisdb.pat_mast as pm', function($join) {
                        $join = $join->on('pm.MRN', '=', 'adm.mrn');
                        $join = $join->on('pm.Episno', '=', 'adm.episno');
                        $join = $join->where('pm.compcode', '=', session('compcode'));
                    })
                    ->leftjoin('hisdb.episode as e', function($join) {
                        $join = $join->on('e.mrn', '=', 'adm.mrn');
                        $join = $join->on('e.episno', '=', 'adm.episno');
                        $join = $join->where('e.compcode', '=', session('compcode'));
                    })
                    ->leftjoin('hisdb.doctor as d', function($join) {
                        $join = $join->on('d.doctorcode', '=', 'e.admdoctor');
                        $join = $join->where('d.compcode', '=', session('compcode'));
                    })
                    ->where('adm.compcode','=',session('compcode'))
                    ->where('adm.mrn','=',$mrn)
                    ->where('adm.episno','=',$episno)
                    ->first();
        
        $company = DB::table('sysdb.company')
            ->where('compcode','=',session('compcode'))
            ->first();

        return view('hisdb.admhandover.admhandover_pdfmake',compact('admhandover'));
        
    }
}