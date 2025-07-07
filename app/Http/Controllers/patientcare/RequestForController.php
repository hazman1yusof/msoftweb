<?php

namespace App\Http\Controllers\patientcare;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;
use PDF;

class RequestForController extends defaultController
{
    
    var $table;
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function show(Request $request)
    {
        return view('patientcare.requestfor');
    }
    
    public function table(Request $request)
    {
        switch($request->action){
            case 'get_table_otbook':
                return $this->get_table_otbook($request);
            case 'get_table_radClinic':
                return $this->get_table_radClinic($request);
            case 'get_table_mri':
                return $this->get_table_mri($request);
            case 'get_table_physio':
                return $this->get_table_physio($request);
            case 'get_table_dressing':
                return $this->get_table_dressing($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function form(Request $request)
    {
        DB::enableQueryLog();
        
        switch($request->action){
            case 'save_otbook':
                switch($request->oper){
                    case 'add':
                        return $this->add_otbook($request);
                    case 'edit':
                        return $this->edit_otbook($request);
                    default:
                        return 'error happen..';
                }
            
            case 'save_radClinic':
                switch($request->oper){
                    case 'add':
                        return $this->add_radClinic($request);
                    case 'edit':
                        return $this->edit_radClinic($request);
                    default:
                        return 'error happen..';
                }
            
            case 'save_mri':
                switch($request->oper){
                    case 'add':
                        return $this->add_mri($request);
                    case 'edit':
                        return $this->edit_mri($request);
                    default:
                        return 'error happen..';
                }
            
            case 'accept_mri':
                return $this->accept_mri($request);
            
            case 'save_physio':
                switch($request->oper){
                    case 'add':
                        return $this->add_physio($request);
                    case 'edit':
                        return $this->edit_physio($request);
                    default:
                        return 'error happen..';
                }
            
            case 'save_dressing':
                switch($request->oper){
                    case 'add':
                        return $this->add_dressing($request);
                    case 'edit':
                        return $this->edit_dressing($request);
                    default:
                        return 'error happen..';
                }
            
            default:
                return 'error happen..';
        }
    }
    
    public function add_otbook(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('hisdb.pat_otbook')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn,
                    'episno' => $request->episno,
                    'iPesakit' => $request->iPesakit,
                    'req_type' => $request->req_type,
                    'op_date' => $request->op_date,
                    'oper_type' => $request->oper_type,
                    'adm_type' => $request->adm_type,
                    'anaesthetist' => $request->anaesthetist,
                    'diagnosis' => $request->ot_diagnosis,
                    'diagnosedby' => strtoupper($request->ot_diagnosedby),
                    'remarks' => $request->ot_remarks,
                    'doctorname'  => strtoupper($request->ot_doctorname),
                    'adduser'  => strtoupper($request->ot_lastuser),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastuser' => strtoupper($request->ot_lastuser),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'computerid' => session('computerid'),
                ]);
            
            if($request->req_type == 'WARD'){
                DB::table('hisdb.episode')
                    ->where('mrn','=',$request->mrn)
                    ->where('episno','=',$request->episno)
                    ->where('compcode','=',session('compcode'))
                    ->update([
                        'ward' => $request->ReqFor_ward,
                        'bed' => $request->ReqFor_bed,
                        'bedtype' => $request->ReqFor_bedtype,
                        'room' => $request->ReqFor_room,
                        'epistycode' => 'IP',
                        // 'reff_ed' => '1',
                        'lastuser'  => strtoupper($request->ot_lastuser),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
                
                DB::table('hisdb.queue') 
                    ->where('mrn','=',$request->mrn)
                    ->where('episno','=',$request->episno)
                    ->where('compcode','=',session('compcode'))
                    ->update([
                        'bed' => $request->ReqFor_bed,
                        'bedtype' => $request->ReqFor_bedtype,
                        'room' => $request->ReqFor_room,
                        'epistycode' => 'IP',
                        'chggroup' => 'IP',
                        'lastuser'  => strtoupper($request->ot_lastuser),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
                
                $episode = DB::table('hisdb.episode')
                            ->where('compcode',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno)
                            ->first();
                
                $patmast = DB::table('hisdb.pat_mast')
                            ->where('compcode',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->first();
                
                $bed_obj = DB::table('hisdb.bed')
                            ->where('compcode','=',session('compcode'))
                            ->where('bednum','=',$request->ReqFor_bed);
                
                if($bed_obj->exists()){
                    DB::table('hisdb.bedalloc')
                        ->insert([  
                            'mrn' => $request->mrn,
                            'episno' => $request->episno,
                            'name' => $patmast->Name,
                            'astatus' => "OCCUPIED",
                            'ward' =>  $request->ReqFor_ward,
                            'room' =>  $request->ReqFor_room,
                            'bednum' =>  $request->ReqFor_bed,
                            'asdate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'astime' => Carbon::now("Asia/Kuala_Lumpur"),
                            'compcode' => session('compcode'),
                            'adduser' => strtoupper(session('username')),
                            'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'computerid' => session('computerid')
                        ]);
                    
                    DB::table('hisdb.bed')
                        ->where('compcode','=',session('compcode'))
                        ->where('bednum','=',$request->ReqFor_bed)
                        ->update([
                            'occup' => "OCCUPIED",
                            'mrn' => $request->mrn,
                            'episno' => $request->episno,
                            'name' => $patmast->Name,
                            'admdoctor' => $episode->admdoctor,
                            'upduser' => strtoupper(session('username')),
                            'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'computerid' => session('computerid'),
                            'newic' => $patmast->Newic
                        ]);
                }
            }else if($request->req_type == 'OT'){
                // DB::table('hisdb.pat_otbook')
                //     ->insert([
                //         'compcode' => session('compcode'),
                //         'mrn' => $request->mrn,
                //         'episno' => $request->episno,
                //         'iPesakit' => $request->iPesakit,
                //         'req_type' => $request->req_type,
                //         'op_date' => $request->op_date,
                //         'oper_type' => $request->oper_type,
                //         'adm_type' => $request->adm_type,
                //         'anaesthetist' => $request->anaesthetist,
                //         'diagnosis' => $request->ot_diagnosis,
                //         'diagnosedby' => strtoupper($request->ot_diagnosedby),
                //         'remarks' => $request->ot_remarks,
                //         'doctorname'  => strtoupper($request->ot_doctorname),
                //         'adduser'  => strtoupper($request->ot_lastuser),
                //         'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                //         'lastuser' => strtoupper($request->ot_lastuser),
                //         'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                //         'computerid' => session('computerid'),
                //     ]);
            }
            
            if(!empty($request->iPesakit)){
                $pat_mast = DB::table('hisdb.pat_mast')
                            ->where('CompCode',session('compcode'))
                            ->where('MRN',$request->mrn)
                            ->first();
                
                if($pat_mast->iPesakit != $request->iPesakit){
                    DB::table('hisdb.pat_mast')
                        ->where('CompCode',session('compcode'))
                        ->where('MRN',$request->mrn)
                        ->update([
                            'iPesakit' => $request->iPesakit,
                        ]);
                }
            }
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function edit_otbook(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $pat_otbook = DB::table('hisdb.pat_otbook')
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno)
                        ->where('compcode','=',session('compcode'));
            
            if($pat_otbook->exists()){
                $pat_otbook
                    ->update([
                        'iPesakit' => $request->iPesakit,
                        'req_type' => $request->req_type,
                        'op_date' => $request->op_date,
                        'oper_type' => $request->oper_type,
                        'adm_type' => $request->adm_type,
                        'anaesthetist' => $request->anaesthetist,
                        'diagnosis' => $request->ot_diagnosis,
                        'diagnosedby' => strtoupper($request->ot_diagnosedby),
                        'remarks' => $request->ot_remarks,
                        'doctorname'  => strtoupper($request->ot_doctorname),
                        'upduser'  => strtoupper($request->ot_lastuser),
                        'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser' => strtoupper($request->ot_lastuser),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
            }else{
                DB::table('hisdb.pat_otbook')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'iPesakit' => $request->iPesakit,
                        'req_type' => $request->req_type,
                        'op_date' => $request->op_date,
                        'oper_type' => $request->oper_type,
                        'adm_type' => $request->adm_type,
                        'anaesthetist' => $request->anaesthetist,
                        'diagnosis' => $request->ot_diagnosis,
                        'diagnosedby' => strtoupper($request->ot_diagnosedby),
                        'remarks' => $request->ot_remarks,
                        'doctorname'  => strtoupper($request->ot_doctorname),
                        'adduser'  => strtoupper($request->ot_lastuser),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser' => strtoupper($request->ot_lastuser),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
            }
            
            DB::table('hisdb.episode')
                ->where('mrn','=',$request->mrn)
                ->where('episno','=',$request->episno)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'reff_ed' => '1',
                    'lastuser'  => strtoupper($request->ot_lastuser),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                ]);
            
            if(!empty($request->iPesakit)){
                $pat_mast = DB::table('hisdb.pat_mast')
                            ->where('CompCode',session('compcode'))
                            ->where('MRN',$request->mrn)
                            ->first();
                
                if($pat_mast->iPesakit != $request->iPesakit){
                    DB::table('hisdb.pat_mast')
                        ->where('CompCode',session('compcode'))
                        ->where('MRN',$request->mrn)
                        ->update([
                            'iPesakit' => $request->iPesakit,
                        ]);
                }
            }
            
            $queries = DB::getQueryLog();
            // dump($queries);
            
            DB::commit();
            
            $responce = new stdClass();
            
            return json_encode($responce);
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function get_table_otbook(Request $request){
        
        $pat_otbook_bed_obj = DB::table('hisdb.bed')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno);
        
        $pat_otbook_obj = DB::table('hisdb.pat_otbook')
                        ->select('idno','compcode','mrn','episno','iPesakit as p_iPesakit','req_type','op_date','oper_type','adm_type','anaesthetist','diagnosis as ot_diagnosis','diagnosedby as ot_diagnosedby','remarks as ot_remarks','doctorname as ot_doctorname','adduser','adddate','upduser','upddate','lastuser as ot_lastuser','lastupdate','computerid')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno);
        
        $nurshandover_obj = DB::table('nursing.nurshandover')
                            ->select('bpsys_stand as vs_bp_sys1','bpdias_stand as vs_bp_dias2','spo2 as vs_spo','hr as vs_pulse','gxt as vs_gxt','temp_ as vs_temperature','weight as vs_weight','height as vs_height','respiration as vs_respiration')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno)
                            ->where('epistycode','=','OP');
        
        $nurshistory_obj = DB::table('nursing.nurshistory')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn);
        
        $patmast_obj = DB::table('hisdb.pat_mast')
                        ->select('iPesakit')
                        ->where('compcode',session('compcode'))
                        ->where('mrn','=',$request->mrn);
        
        $responce = new stdClass();
        
        if($pat_otbook_bed_obj->exists()){
            $pat_otbook_bed_obj = $pat_otbook_bed_obj->first();
            $responce->pat_otbook_bed = $pat_otbook_bed_obj;
        }
        
        if($pat_otbook_obj->exists()){
            $pat_otbook_obj = $pat_otbook_obj->first();
            $responce->pat_otbook = $pat_otbook_obj;
        }
        
        if($nurshandover_obj->exists()){
            $nurshandover_obj = $nurshandover_obj->first();
            $responce->nurshandover = $nurshandover_obj;
        }
        
        if($nurshistory_obj->exists()){
            $nurshistory_obj = $nurshistory_obj->first();
            $responce->nurshistory = $nurshistory_obj;
        }
        
        if($patmast_obj->exists()){
            $patmast_obj = $patmast_obj->first();
            
            $iPesakit = $patmast_obj->iPesakit;
            $responce->iPesakit = $iPesakit;
        }
        
        return json_encode($responce);
        
    }
    
    public function add_radClinic(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $episode = DB::table('hisdb.episode')
                        ->select('newcaseP','newcaseNP','followupP','followupNP')
                        ->where('compcode',session('compcode'))
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno);
            
            if($episode->exists()){
                $episode_obj = $episode->first();
                
                if(!empty($episode_obj->newcaseP) || !empty($episode_obj->newcaseNP)){
                    if($request->rad_pregnant == 1){
                        $episode
                            ->update([
                                'newcaseP' => 1,
                                'newcaseNP' => null,
                                'reff_rad' => '1',
                                'lastuser'  => session('username'),
                                'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                            ]);
                    }else if($request->rad_pregnant == 0){
                        $episode
                            ->update([
                                'newcaseP' => null,
                                'newcaseNP' => 1,
                                'reff_rad' => '1',
                                'lastuser'  => session('username'),
                                'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                            ]);
                    }
                }else if(!empty($episode_obj->followupP) || !empty($episode_obj->followupNP)){
                    if($request->rad_pregnant == 1){
                        $episode
                            ->update([
                                'followupP' => 1,
                                'followupNP' => null,
                                'reff_rad' => '1',
                                'lastuser'  => session('username'),
                                'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                            ]);
                    }else if($request->rad_pregnant == 0){
                        $episode
                            ->update([
                                'followupP' => null,
                                'followupNP' => 1,
                                'reff_rad' => '1',
                                'lastuser'  => session('username'),
                                'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                            ]);
                    }
                }
            }
            
            // $nursassessment = DB::table('nursing.nursassessment')
            //                 ->where('mrn','=',$request->mrn)
            //                 ->where('episno','=',$request->episno)
            //                 ->where('compcode','=',session('compcode'));
            
            // if($nursassessment->exists()){
            //     $nursassessment
            //         ->update([
            //             'vs_weight' => $request->rad_weight,
            //             'lastuser'  => session('username'),
            //             'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
            //         ]);
            // }
            
            // $pathealth = DB::table('hisdb.pathealth')
            //             ->where('mrn','=',$request->mrn)
            //             ->where('episno','=',$request->episno)
            //             ->where('recorddate','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate)->format('Y-m-d'))
            //             ->where('recordtime','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate)->format('H:i:s'))
            //             ->where('compcode','=',session('compcode'));
            
            // if($pathealth->exists()){
            //     $pathealth
            //         ->update([
            //             'weight' => $request->rad_weight,
            //             'lastuser'  => session('username'),
            //             'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
            //         ]);
            // }
            
            $pathistory = DB::table('hisdb.pathistory')
                        ->where('mrn','=',$request->mrn)
                        // ->where('recorddate','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate))
                        ->where('compcode','=',session('compcode'));
            
            if($pathistory->exists()){
                $pathistory
                    ->update([
                        'allergyh' => $request->rad_allergy,
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }
            
            $pat_radiology = DB::table('hisdb.pat_radiology')
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno)
                            ->where('compcode','=',session('compcode'));
            
            if($pat_radiology->exists()){
                $pat_radiology
                    ->update([
                        'iPesakit' => $request->iPesakit,
                        'weight' => $request->rad_weight,
                        'pt_condition' => $request->pt_condition,
                        'LMP' => $request->LMP,
                        'xray' => $request->xray,
                        'xray_date' => $request->xray_date,
                        'xray_remark' => $request->xray_remark,
                        'mri' => $request->mri,
                        'mri_date' => $request->mri_date,
                        'mri_remark' => $request->mri_remark,
                        'angio' => $request->angio,
                        'angio_date' => $request->angio_date,
                        'angio_remark' => $request->angio_remark,
                        'ultrasound' => $request->ultrasound,
                        'ultrasound_date' => $request->ultrasound_date,
                        'ultrasound_remark' => $request->ultrasound_remark,
                        'ct' => $request->ct,
                        'ct_date' => $request->ct_date,
                        'ct_remark' => $request->ct_remark,
                        'fluroscopy' => $request->fluroscopy,
                        'fluroscopy_date' => $request->fluroscopy_date,
                        'fluroscopy_remark' => $request->fluroscopy_remark,
                        'mammogram' => $request->mammogram,
                        'mammogram_date' => $request->mammogram_date,
                        'mammogram_remark' => $request->mammogram_remark,
                        'bmd' => $request->bmd,
                        'bmd_date' => $request->bmd_date,
                        'bmd_remark' => $request->bmd_remark,
                        'clinicaldata' => $request->clinicaldata,
                        'doctorname'  => strtoupper($request->radClinic_doctorname),
                        'rad_note' => $request->rad_note,
                        'radiologist'  => strtoupper($request->radClinic_radiologist),
                        'upduser'  => session('username'),
                        'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser' => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
            }else{
                DB::table('hisdb.pat_radiology')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'iPesakit' => $request->iPesakit,
                        'weight' => $request->rad_weight,
                        'pt_condition' => $request->pt_condition,
                        'LMP' => $request->LMP,
                        'xray' => $request->xray,
                        'xray_date' => $request->xray_date,
                        'xray_remark' => $request->xray_remark,
                        'mri' => $request->mri,
                        'mri_date' => $request->mri_date,
                        'mri_remark' => $request->mri_remark,
                        'angio' => $request->angio,
                        'angio_date' => $request->angio_date,
                        'angio_remark' => $request->angio_remark,
                        'ultrasound' => $request->ultrasound,
                        'ultrasound_date' => $request->ultrasound_date,
                        'ultrasound_remark' => $request->ultrasound_remark,
                        'ct' => $request->ct,
                        'ct_date' => $request->ct_date,
                        'ct_remark' => $request->ct_remark,
                        'fluroscopy' => $request->fluroscopy,
                        'fluroscopy_date' => $request->fluroscopy_date,
                        'fluroscopy_remark' => $request->fluroscopy_remark,
                        'mammogram' => $request->mammogram,
                        'mammogram_date' => $request->mammogram_date,
                        'mammogram_remark' => $request->mammogram_remark,
                        'bmd' => $request->bmd,
                        'bmd_date' => $request->bmd_date,
                        'bmd_remark' => $request->bmd_remark,
                        'clinicaldata' => $request->clinicaldata,
                        'doctorname'  => strtoupper($request->radClinic_doctorname),
                        'rad_note' => $request->rad_note,
                        'radiologist'  => strtoupper($request->radClinic_radiologist),
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser' => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
            }
            
            if(!empty($request->iPesakit)){
                $pat_mast = DB::table('hisdb.pat_mast')
                            ->where('CompCode',session('compcode'))
                            ->where('MRN',$request->mrn)
                            ->first();
                
                if($pat_mast->iPesakit != $request->iPesakit){
                    DB::table('hisdb.pat_mast')
                        ->where('CompCode',session('compcode'))
                        ->where('MRN',$request->mrn)
                        ->update([
                            'iPesakit' => $request->iPesakit,
                        ]);
                }
            }
            
            // DB::table('hisdb.episode')
            //     ->where('mrn','=',$request->mrn)
            //     ->where('episno','=',$request->episno)
            //     ->where('compcode','=',session('compcode'))
            //     ->update([
            //         'reff_rad' => '1',
            //         'lastuser'  => session('username'),
            //         'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
            //     ]);
            
            // $pat_mri = DB::table('hisdb.pat_mri')
            //             ->where('mrn','=',$request->mrn)
            //             ->where('episno','=',$request->episno)
            //             ->where('compcode','=',session('compcode'));
            
            // if($pat_mri->exists()){
            //     $pat_mri
            //         ->update([
            //             'radiologist' => session('username'),
            //         ]);
            // }
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function edit_radClinic(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $episode = DB::table('hisdb.episode')
                        ->select('newcaseP','newcaseNP','followupP','followupNP')
                        ->where('compcode',session('compcode'))
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno);
            
            if($episode->exists()){
                $episode_obj = $episode->first();
                
                if(!empty($episode_obj->newcaseP) || !empty($episode_obj->newcaseNP)){
                    if($request->rad_pregnant == 1){
                        $episode
                            ->update([
                                'newcaseP' => 1,
                                'newcaseNP' => null,
                                'reff_rad' => '1',
                                'lastuser'  => session('username'),
                                'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                            ]);
                    }else if($request->rad_pregnant == 0){
                        $episode
                            ->update([
                                'newcaseP' => null,
                                'newcaseNP' => 1,
                                'reff_rad' => '1',
                                'lastuser'  => session('username'),
                                'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                            ]);
                    }
                }else if(!empty($episode_obj->followupP) || !empty($episode_obj->followupNP)){
                    if($request->rad_pregnant == 1){
                        $episode
                            ->update([
                                'followupP' => 1,
                                'followupNP' => null,
                                'reff_rad' => '1',
                                'lastuser'  => session('username'),
                                'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                            ]);
                    }else if($request->rad_pregnant == 0){
                        $episode
                            ->update([
                                'followupP' => null,
                                'followupNP' => 1,
                                'reff_rad' => '1',
                                'lastuser'  => session('username'),
                                'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                            ]);
                    }
                }
            }
            
            // $nursassessment = DB::table('nursing.nursassessment')
            //                 ->where('mrn','=',$request->mrn)
            //                 ->where('episno','=',$request->episno)
            //                 ->where('compcode','=',session('compcode'));
            
            // if($nursassessment->exists()){
            //     $nursassessment
            //         ->update([
            //             'vs_weight' => $request->rad_weight,
            //             'lastuser'  => session('username'),
            //             'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
            //         ]);
            // }
            
            // $pathealth = DB::table('hisdb.pathealth')
            //             ->where('mrn','=',$request->mrn)
            //             ->where('episno','=',$request->episno)
            //             ->where('recorddate','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate)->format('Y-m-d'))
            //             ->where('recordtime','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate)->format('H:i:s'))
            //             ->where('compcode','=',session('compcode'));
            
            // if($pathealth->exists()){
            //     $pathealth
            //         ->update([
            //             'weight' => $request->rad_weight,
            //             'lastuser'  => session('username'),
            //             'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
            //         ]);
            // }
            
            $pathistory = DB::table('hisdb.pathistory')
                        ->where('mrn','=',$request->mrn)
                        // ->where('recorddate','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate))
                        ->where('compcode','=',session('compcode'));
            
            if($pathistory->exists()){
                $pathistory
                    ->update([
                        'allergyh' => $request->rad_allergy,
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }
            
            $pat_radiology = DB::table('hisdb.pat_radiology')
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno)
                            ->where('compcode','=',session('compcode'));
            
            if($pat_radiology->exists()){
                $pat_radiology
                    ->update([
                        'iPesakit' => $request->iPesakit,
                        'weight' => $request->rad_weight,
                        'pt_condition' => $request->pt_condition,
                        'LMP' => $request->LMP,
                        'xray' => $request->xray,
                        'xray_date' => $request->xray_date,
                        'xray_remark' => $request->xray_remark,
                        'mri' => $request->mri,
                        'mri_date' => $request->mri_date,
                        'mri_remark' => $request->mri_remark,
                        'angio' => $request->angio,
                        'angio_date' => $request->angio_date,
                        'angio_remark' => $request->angio_remark,
                        'ultrasound' => $request->ultrasound,
                        'ultrasound_date' => $request->ultrasound_date,
                        'ultrasound_remark' => $request->ultrasound_remark,
                        'ct' => $request->ct,
                        'ct_date' => $request->ct_date,
                        'ct_remark' => $request->ct_remark,
                        'fluroscopy' => $request->fluroscopy,
                        'fluroscopy_date' => $request->fluroscopy_date,
                        'fluroscopy_remark' => $request->fluroscopy_remark,
                        'mammogram' => $request->mammogram,
                        'mammogram_date' => $request->mammogram_date,
                        'mammogram_remark' => $request->mammogram_remark,
                        'bmd' => $request->bmd,
                        'bmd_date' => $request->bmd_date,
                        'bmd_remark' => $request->bmd_remark,
                        'clinicaldata' => $request->clinicaldata,
                        'doctorname'  => strtoupper($request->radClinic_doctorname),
                        'rad_note' => $request->rad_note,
                        'radiologist'  => strtoupper($request->radClinic_radiologist),
                        'upduser'  => session('username'),
                        'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser' => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
            }else{
                DB::table('hisdb.pat_radiology')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'iPesakit' => $request->iPesakit,
                        'weight' => $request->rad_weight,
                        'pt_condition' => $request->pt_condition,
                        'LMP' => $request->LMP,
                        'xray' => $request->xray,
                        'xray_date' => $request->xray_date,
                        'xray_remark' => $request->xray_remark,
                        'mri' => $request->mri,
                        'mri_date' => $request->mri_date,
                        'mri_remark' => $request->mri_remark,
                        'angio' => $request->angio,
                        'angio_date' => $request->angio_date,
                        'angio_remark' => $request->angio_remark,
                        'ultrasound' => $request->ultrasound,
                        'ultrasound_date' => $request->ultrasound_date,
                        'ultrasound_remark' => $request->ultrasound_remark,
                        'ct' => $request->ct,
                        'ct_date' => $request->ct_date,
                        'ct_remark' => $request->ct_remark,
                        'fluroscopy' => $request->fluroscopy,
                        'fluroscopy_date' => $request->fluroscopy_date,
                        'fluroscopy_remark' => $request->fluroscopy_remark,
                        'mammogram' => $request->mammogram,
                        'mammogram_date' => $request->mammogram_date,
                        'mammogram_remark' => $request->mammogram_remark,
                        'bmd' => $request->bmd,
                        'bmd_date' => $request->bmd_date,
                        'bmd_remark' => $request->bmd_remark,
                        'clinicaldata' => $request->clinicaldata,
                        'doctorname'  => strtoupper($request->radClinic_doctorname),
                        'rad_note' => $request->rad_note,
                        'radiologist'  => strtoupper($request->radClinic_radiologist),
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser' => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
            }
            
            if(!empty($request->iPesakit)){
                $pat_mast = DB::table('hisdb.pat_mast')
                            ->where('CompCode',session('compcode'))
                            ->where('MRN',$request->mrn)
                            ->first();
                
                if($pat_mast->iPesakit != $request->iPesakit){
                    DB::table('hisdb.pat_mast')
                        ->where('CompCode',session('compcode'))
                        ->where('MRN',$request->mrn)
                        ->update([
                            'iPesakit' => $request->iPesakit,
                        ]);
                }
            }
            
            // DB::table('hisdb.episode')
            //     ->where('mrn','=',$request->mrn)
            //     ->where('episno','=',$request->episno)
            //     ->where('compcode','=',session('compcode'))
            //     ->update([
            //         'reff_rad' => '1',
            //         'lastuser'  => session('username'),
            //         'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
            //     ]);
            
            // $pat_mri = DB::table('hisdb.pat_mri')
            //             ->where('mrn','=',$request->mrn)
            //             ->where('episno','=',$request->episno)
            //             ->where('compcode','=',session('compcode'));
            
            // if($pat_mri->exists()){
            //     $pat_mri
            //         ->update([
            //             'radiologist' => session('username'),
            //         ]);
            // }
            
            $queries = DB::getQueryLog();
            // dump($queries);
            
            DB::commit();
            
            $responce = new stdClass();
            
            return json_encode($responce);
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function get_table_radClinic(Request $request){
        
        $pat_radiology_obj = DB::table('hisdb.pat_radiology as pr')
                            ->select('pr.compcode','pr.mrn','pr.episno','pr.iPesakit as pr_iPesakit','pr.weight as rad_weight','pr.pt_condition','pr.LMP','pr.xray','pr.xray_date','pr.xray_remark','pr.mri','pr.mri_date','pr.mri_remark','pr.angio','pr.angio_date','pr.angio_remark','pr.ultrasound','pr.ultrasound_date','pr.ultrasound_remark','pr.ct','pr.ct_date','pr.ct_remark','pr.fluroscopy','pr.fluroscopy_date','pr.fluroscopy_remark','pr.mammogram','pr.mammogram_date','pr.mammogram_remark','pr.bmd','pr.bmd_date','pr.bmd_remark','pr.clinicaldata','pr.doctorname as radClinic_doctorname','pr.rad_note','pr.radiologist as radClinic_radiologist','pr.adduser','pr.adddate','pr.upduser','pr.upddate','pr.lastuser as radClinic_lastuser','pr.lastupdate','pr.computerid')
                            // ->leftJoin('nursing.nursassessment as na', function ($join) use ($request){
                            //     $join = $join->on('na.mrn', '=', 'pr.mrn')
                            //                 ->on('na.episno', '=', 'pr.episno')
                            //                 ->where('na.compcode','=',session('compcode'));
                            // })
                            // ->leftJoin('nursing.nurshistory as nh', function ($join) use ($request){
                            //     $join = $join->on('nh.mrn', '=', 'pr.mrn')
                            //                 ->where('nh.compcode','=',session('compcode'));
                            // })
                            ->where('pr.compcode','=',session('compcode'))
                            ->where('pr.mrn','=',$request->mrn)
                            ->where('pr.episno','=',$request->episno);
        
        $episode = DB::table('hisdb.episode')
                    ->where('compcode',session('compcode'))
                    ->where('mrn','=',$request->mrn)
                    ->where('episno','=',$request->episno);
        
        // if(!empty($request->recorddate) && $request->recorddate != '-'){
        //     $pathealth_obj = DB::table('hisdb.pathealth')
        //                     ->select('weight')
        //                     ->where('compcode','=',session('compcode'))
        //                     ->where('recorddate','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate)->format('Y-m-d'))
        //                     ->where('recordtime','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate)->format('H:i:s'))
        //                     ->where('mrn','=',$request->mrn)
        //                     ->where('episno','=',$request->episno);
        // }
        
        $pathistory_obj = DB::table('hisdb.pathistory')
                        ->select('allergyh')
                        ->where('compcode','=',session('compcode'))
                        // ->where('recorddate','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate))
                        ->where('mrn','=',$request->mrn);
        
        // $nursassessment_obj = DB::table('nursing.nursassessment')
        //                     ->select('vs_weight')
        //                     ->where('compcode','=',session('compcode'))
        //                     ->where('mrn','=',$request->mrn)
        //                     ->where('episno','=',$request->episno);
        
        $patmast_obj = DB::table('hisdb.pat_mast')
                        ->select('iPesakit')
                        ->where('compcode',session('compcode'))
                        ->where('mrn','=',$request->mrn);
        
        $responce = new stdClass();
        
        if($pat_radiology_obj->exists()){
            $pat_radiology_obj = $pat_radiology_obj->first();
            $responce->pat_radiology = $pat_radiology_obj;
        }
        
        if($episode->exists()){
            $episode = $episode->first();
            if($episode->newcaseP == 1 || $episode->followupP == 1){
                // $pregnant = 1;
                $responce->pregnant = 1;
            }else{
                // $pregnant = 0;
                $responce->pregnant = 0;
            }
        }
        
        // if(!empty($request->recorddate) && $request->recorddate != '-'){
        //     if($pathealth_obj->exists()){
        //         $pathealth_obj = $pathealth_obj->first();
                
        //         $rad_weight = $pathealth_obj->weight;
        //         $responce->rad_weight = $rad_weight;
        //     }
        // }
        
        if($pathistory_obj->exists()){
            $pathistory_obj = $pathistory_obj->first();
            
            $rad_allergy = $pathistory_obj->allergyh;
            $responce->rad_allergy = $rad_allergy;
        }
        
        // if($nursassessment_obj->exists()){
        //     $nursassessment_obj = $nursassessment_obj->first();
            
        //     $rad_weight = $nursassessment_obj->vs_weight;
        //     $responce->rad_weight = $rad_weight;
        // }
        
        if($patmast_obj->exists()){
            $patmast_obj = $patmast_obj->first();
            
            $iPesakit = $patmast_obj->iPesakit;
            $responce->iPesakit = $iPesakit;
        }
        
        return json_encode($responce);
        
    }
    
    public function add_mri(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            // $pathealth = DB::table('hisdb.pathealth')
            //             ->where('mrn','=',$request->mrn)
            //             ->where('episno','=',$request->episno)
            //             ->where('recorddate','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate)->format('Y-m-d'))
            //             ->where('recordtime','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate)->format('H:i:s'))
            //             ->where('compcode','=',session('compcode'));
            
            // if($pathealth->exists()){
            //     $pathealth
            //         ->update([
            //             'weight' => $request->mri_weight,
            //             'lastuser'  => session('username'),
            //             'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
            //         ]);
            // }
            
            $nursassessment = DB::table('nursing.nursassessment')
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno)
                            ->where('compcode','=',session('compcode'));
            
            if($nursassessment->exists()){
                $nursassessment
                    ->update([
                        'vs_weight' => $request->mri_weight,
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }
            
            $pat_mri = DB::table('hisdb.pat_mri')
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno)
                        ->where('compcode','=',session('compcode'));
            
            if($pat_mri->exists()){
                $pat_mri
                    ->update([
                        'weight' => $request->mri_weight,
                        'entereddate' => $request->mri_entereddate,
                        'cardiacpacemaker' => $request->cardiacpacemaker,
                        'pros_valve' => $request->pros_valve,
                        'prosvalve_rmk' => $request->prosvalve_rmk,
                        'intraocular' => $request->intraocular,
                        'cochlear_imp' => $request->cochlear_imp,
                        'neurotransm' => $request->neurotransm,
                        'bonegrowth' => $request->bonegrowth,
                        'druginfuse' => $request->druginfuse,
                        'surg_clips' => $request->surg_clips,
                        'jointlimb_pros' => $request->jointlimb_pros,
                        'shrapnel' => $request->shrapnel,
                        'oper_3mth' => $request->oper_3mth,
                        'oper3mth_remark' => $request->oper3mth_remark,
                        'prev_mri' => $request->prev_mri,
                        'claustrophobia' => $request->claustrophobia,
                        'dental_imp' => $request->dental_imp,
                        'frmgnetic_imp' => $request->frmgnetic_imp,
                        'pregnancy' => $request->pregnancy,
                        'allergy_drug' => $request->allergy_drug,
                        'bloodurea' => $request->bloodurea,
                        'serum_creatinine' => $request->serum_creatinine,
                        'doctorname' => strtoupper($request->mri_doctorname),
                        'radiologist' => strtoupper($request->mri_radiologist),
                        'radiographer' => strtoupper($request->radiographer),
                        // 'staffnurse' => $request->staffnurse,
                        'upduser'  => strtoupper($request->mri_lastuser),
                        'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser' => strtoupper($request->mri_lastuser),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
            }else{
                DB::table('hisdb.pat_mri')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'weight' => $request->mri_weight,
                        'entereddate' => $request->mri_entereddate,
                        'cardiacpacemaker' => $request->cardiacpacemaker,
                        'pros_valve' => $request->pros_valve,
                        'prosvalve_rmk' => $request->prosvalve_rmk,
                        'intraocular' => $request->intraocular,
                        'cochlear_imp' => $request->cochlear_imp,
                        'neurotransm' => $request->neurotransm,
                        'bonegrowth' => $request->bonegrowth,
                        'druginfuse' => $request->druginfuse,
                        'surg_clips' => $request->surg_clips,
                        'jointlimb_pros' => $request->jointlimb_pros,
                        'shrapnel' => $request->shrapnel,
                        'oper_3mth' => $request->oper_3mth,
                        'oper3mth_remark' => $request->oper3mth_remark,
                        'prev_mri' => $request->prev_mri,
                        'claustrophobia' => $request->claustrophobia,
                        'dental_imp' => $request->dental_imp,
                        'frmgnetic_imp' => $request->frmgnetic_imp,
                        'pregnancy' => $request->pregnancy,
                        'allergy_drug' => $request->allergy_drug,
                        'bloodurea' => $request->bloodurea,
                        'serum_creatinine' => $request->serum_creatinine,
                        'doctorname' => strtoupper($request->mri_doctorname),
                        'radiologist' => strtoupper($request->mri_radiologist),
                        'radiographer' => strtoupper($request->radiographer),
                        // 'staffnurse' => $request->staffnurse,
                        'adduser'  => strtoupper($request->mri_lastuser),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser' => strtoupper($request->mri_lastuser),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
            }
            
            DB::table('hisdb.episode')
                ->where('mrn','=',$request->mrn)
                ->where('episno','=',$request->episno)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'reff_rad' => '1',
                    'lastuser'  => strtoupper($request->mri_lastuser),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function edit_mri(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            // $pathealth = DB::table('hisdb.pathealth')
            //             ->where('mrn','=',$request->mrn)
            //             ->where('episno','=',$request->episno)
            //             ->where('recorddate','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate)->format('Y-m-d'))
            //             ->where('recordtime','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate)->format('H:i:s'))
            //             ->where('compcode','=',session('compcode'));
            
            // if($pathealth->exists()){
            //     $pathealth
            //         ->update([
            //             'weight' => $request->mri_weight,
            //             'lastuser'  => session('username'),
            //             'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
            //         ]);
            // }
            
            $nursassessment = DB::table('nursing.nursassessment')
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno)
                            ->where('compcode','=',session('compcode'));
            
            if($nursassessment->exists()){
                $nursassessment
                    ->update([
                        'vs_weight' => $request->mri_weight,
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }
            
            $pat_mri = DB::table('hisdb.pat_mri')
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno)
                        ->where('compcode','=',session('compcode'));
            
            if($pat_mri->exists()){
                $pat_mri
                    ->update([
                        'weight' => $request->mri_weight,
                        'entereddate' => $request->mri_entereddate,
                        'cardiacpacemaker' => $request->cardiacpacemaker,
                        'pros_valve' => $request->pros_valve,
                        'prosvalve_rmk' => $request->prosvalve_rmk,
                        'intraocular' => $request->intraocular,
                        'cochlear_imp' => $request->cochlear_imp,
                        'neurotransm' => $request->neurotransm,
                        'bonegrowth' => $request->bonegrowth,
                        'druginfuse' => $request->druginfuse,
                        'surg_clips' => $request->surg_clips,
                        'jointlimb_pros' => $request->jointlimb_pros,
                        'shrapnel' => $request->shrapnel,
                        'oper_3mth' => $request->oper_3mth,
                        'oper3mth_remark' => $request->oper3mth_remark,
                        'prev_mri' => $request->prev_mri,
                        'claustrophobia' => $request->claustrophobia,
                        'dental_imp' => $request->dental_imp,
                        'frmgnetic_imp' => $request->frmgnetic_imp,
                        'pregnancy' => $request->pregnancy,
                        'allergy_drug' => $request->allergy_drug,
                        'bloodurea' => $request->bloodurea,
                        'serum_creatinine' => $request->serum_creatinine,
                        'doctorname' => strtoupper($request->mri_doctorname),
                        'radiologist' => strtoupper($request->mri_radiologist),
                        'radiographer' => strtoupper($request->radiographer),
                        // 'staffnurse' => $request->staffnurse,
                        'upduser'  => strtoupper($request->mri_lastuser),
                        'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser' => strtoupper($request->mri_lastuser),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
            }else{
                DB::table('hisdb.pat_mri')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'weight' => $request->mri_weight,
                        'entereddate' => $request->mri_entereddate,
                        'cardiacpacemaker' => $request->cardiacpacemaker,
                        'pros_valve' => $request->pros_valve,
                        'prosvalve_rmk' => $request->prosvalve_rmk,
                        'intraocular' => $request->intraocular,
                        'cochlear_imp' => $request->cochlear_imp,
                        'neurotransm' => $request->neurotransm,
                        'bonegrowth' => $request->bonegrowth,
                        'druginfuse' => $request->druginfuse,
                        'surg_clips' => $request->surg_clips,
                        'jointlimb_pros' => $request->jointlimb_pros,
                        'shrapnel' => $request->shrapnel,
                        'oper_3mth' => $request->oper_3mth,
                        'oper3mth_remark' => $request->oper3mth_remark,
                        'prev_mri' => $request->prev_mri,
                        'claustrophobia' => $request->claustrophobia,
                        'dental_imp' => $request->dental_imp,
                        'frmgnetic_imp' => $request->frmgnetic_imp,
                        'pregnancy' => $request->pregnancy,
                        'allergy_drug' => $request->allergy_drug,
                        'bloodurea' => $request->bloodurea,
                        'serum_creatinine' => $request->serum_creatinine,
                        'doctorname' => strtoupper($request->mri_doctorname),
                        'radiologist' => strtoupper($request->mri_radiologist),
                        'radiographer' => strtoupper($request->radiographer),
                        // 'staffnurse' => $request->staffnurse,
                        'adduser'  => strtoupper($request->mri_lastuser),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser' => strtoupper($request->mri_lastuser),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
            }
            
            DB::table('hisdb.episode')
                ->where('mrn','=',$request->mrn)
                ->where('episno','=',$request->episno)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'reff_rad' => '1',
                    'lastuser'  => strtoupper($request->mri_lastuser),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                ]);
            
            $queries = DB::getQueryLog();
            // dump($queries);
            
            DB::commit();
            
            $responce = new stdClass();
            
            return json_encode($responce);
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function accept_mri(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $pat_mri = DB::table('hisdb.pat_mri')
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno)
                        ->where('compcode','=',session('compcode'));
            
            if($pat_mri->exists()){
                $pat_mri
                    ->update([
                        'radiographer' => session('username'),
                        // 'upduser'  => session('username'),
                        // 'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        // 'lastuser' => session('username'),
                        // 'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        // 'computerid' => session('computerid'),
                    ]);
            }
            
            $queries = DB::getQueryLog();
            // dump($queries);
            
            DB::commit();
            
            $responce = new stdClass();
            
            return json_encode($responce);
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function get_table_mri(Request $request){
        
        $pat_mri_obj = DB::table('hisdb.pat_mri')
                        ->select('compcode','mrn','episno','weight as mri_weight','entereddate as mri_entereddate','cardiacpacemaker','pros_valve','prosvalve_rmk','intraocular','cochlear_imp','neurotransm','bonegrowth','druginfuse','surg_clips','jointlimb_pros','shrapnel','oper_3mth','oper3mth_remark','prev_mri','claustrophobia','dental_imp','frmgnetic_imp','pregnancy','allergy_drug','bloodurea','serum_creatinine','doctorname as mri_doctorname','radiologist as mri_radiologist','radiographer','staffnurse','adduser','adddate','upduser','upddate','lastuser as mri_lastuser','lastupdate','computerid')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno);
        
        // if(!empty($request->recorddate) && $request->recorddate != '-'){
        //     $pathealth_obj = DB::table('hisdb.pathealth')
        //                     ->select('weight')
        //                     ->where('compcode','=',session('compcode'))
        //                     ->where('recorddate','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate)->format('Y-m-d'))
        //                     ->where('recordtime','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate)->format('H:i:s'))
        //                     ->where('mrn','=',$request->mrn)
        //                     ->where('episno','=',$request->episno);
        // }
        
        // $nursassessment_obj = DB::table('nursing.nursassessment')
        //                     ->select('vs_weight')
        //                     ->where('compcode','=',session('compcode'))
        //                     ->where('mrn','=',$request->mrn)
        //                     ->where('episno','=',$request->episno);
        
        $responce = new stdClass();
        
        if($pat_mri_obj->exists()){
            $pat_mri_obj = $pat_mri_obj->first();
            $responce->pat_mri = $pat_mri_obj;
        }
        
        // if(!empty($request->recorddate) && $request->recorddate != '-'){
        //     if($pathealth_obj->exists()){
        //         $pathealth_obj = $pathealth_obj->first();
                
        //         $mri_weight = $pathealth_obj->weight;
        //         $responce->mri_weight = $mri_weight;
        //     }
        // }
        
        // if($nursassessment_obj->exists()){
        //     $nursassessment_obj = $nursassessment_obj->first();
            
        //     $mri_weight = $nursassessment_obj->vs_weight;
        //     $responce->mri_weight = $mri_weight;
        // }
        
        return json_encode($responce);
        
    }
    
    public function add_physio(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $pat_physio = DB::table('hisdb.pat_physio')
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno)
                        ->where('compcode','=',session('compcode'));
            
            if($pat_physio->exists()){
                $pat_physio
                    ->update([
                        'req_date' => $request->req_date,
                        'clinic_diag' => $request->clinic_diag,
                        'findings' => $request->findings,
                        // 'treatment' => $request->phy_treatment,
                        'tr_physio' => $request->tr_physio,
                        'tr_occuptherapy' => $request->tr_occuptherapy,
                        'tr_respiphysio' => $request->tr_respiphysio,
                        'tr_neuro' => $request->tr_neuro,
                        'tr_splint' => $request->tr_splint,
                        'remarks' => $request->remarks,
                        'doctorname' => strtoupper($request->phy_doctorname),
                        'upduser'  => strtoupper($request->phy_lastuser),
                        'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser' => strtoupper($request->phy_lastuser),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
            }else{
                DB::table('hisdb.pat_physio')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'req_date' => $request->req_date,
                        'clinic_diag' => $request->clinic_diag,
                        'findings' => $request->findings,
                        // 'treatment' => $request->phy_treatment,
                        'tr_physio' => $request->tr_physio,
                        'tr_occuptherapy' => $request->tr_occuptherapy,
                        'tr_respiphysio' => $request->tr_respiphysio,
                        'tr_neuro' => $request->tr_neuro,
                        'tr_splint' => $request->tr_splint,
                        'remarks' => $request->remarks,
                        'doctorname' => strtoupper($request->phy_doctorname),
                        'adduser'  => strtoupper($request->phy_lastuser),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser' => strtoupper($request->phy_lastuser),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
            }
            
            DB::table('hisdb.episode')
                ->where('mrn','=',$request->mrn)
                ->where('episno','=',$request->episno)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'reff_physio' => '1',
                    'lastuser'  => strtoupper($request->phy_lastuser),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function edit_physio(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $pat_physio = DB::table('hisdb.pat_physio')
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno)
                        ->where('compcode','=',session('compcode'));
            
            if($pat_physio->exists()){
                $pat_physio
                    ->update([
                        'req_date' => $request->req_date,
                        'clinic_diag' => $request->clinic_diag,
                        'findings' => $request->findings,
                        // 'treatment' => $request->phy_treatment,
                        'tr_physio' => $request->tr_physio,
                        'tr_occuptherapy' => $request->tr_occuptherapy,
                        'tr_respiphysio' => $request->tr_respiphysio,
                        'tr_neuro' => $request->tr_neuro,
                        'tr_splint' => $request->tr_splint,
                        'remarks' => $request->remarks,
                        'doctorname' => strtoupper($request->phy_doctorname),
                        'upduser'  => strtoupper($request->phy_lastuser),
                        'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser' => strtoupper($request->phy_lastuser),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
            }else{
                DB::table('hisdb.pat_physio')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'req_date' => $request->req_date,
                        'clinic_diag' => $request->clinic_diag,
                        'findings' => $request->findings,
                        // 'treatment' => $request->phy_treatment,
                        'tr_physio' => $request->tr_physio,
                        'tr_occuptherapy' => $request->tr_occuptherapy,
                        'tr_respiphysio' => $request->tr_respiphysio,
                        'tr_neuro' => $request->tr_neuro,
                        'tr_splint' => $request->tr_splint,
                        'remarks' => $request->remarks,
                        'doctorname' => strtoupper($request->phy_doctorname),
                        'adduser'  => strtoupper($request->phy_lastuser),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser' => strtoupper($request->phy_lastuser),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
            }
            
            DB::table('hisdb.episode')
                ->where('mrn','=',$request->mrn)
                ->where('episno','=',$request->episno)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'reff_physio' => '1',
                    'lastuser'  => strtoupper($request->phy_lastuser),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                ]);
            
            $queries = DB::getQueryLog();
            // dump($queries);
            
            DB::commit();
            
            $responce = new stdClass();
            
            return json_encode($responce);
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function get_table_physio(Request $request){
        
        $pat_physio_obj = DB::table('hisdb.pat_physio')
                        ->select('compcode','mrn','episno','req_date','clinic_diag','findings','treatment as phy_treatment','tr_physio','tr_occuptherapy','tr_respiphysio','tr_neuro','tr_splint','remarks','doctorname as phy_doctorname','adduser','adddate','upduser','upddate','lastuser as phy_lastuser','lastupdate','computerid')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno);
        
        $responce = new stdClass();
        
        if($pat_physio_obj->exists()){
            $pat_physio_obj = $pat_physio_obj->first();
            $responce->pat_physio = $pat_physio_obj;
        }
        
        return json_encode($responce);
        
    }
    
    public function add_dressing(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('hisdb.pat_dressing')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn,
                    'episno' => $request->episno,
                    'od_dressing' => $request->od_dressing,
                    'bd_dressing' => $request->bd_dressing,
                    'eod_dressing' => $request->eod_dressing,
                    'others_dressing' => $request->others_dressing,
                    'others_name' => $request->others_name,
                    'solution' => $request->solution,
                    'doctorname' => strtoupper($request->dressing_doctorname),
                    'adduser'  => strtoupper($request->dressing_lastuser),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastuser' => strtoupper($request->dressing_lastuser),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'computerid' => session('computerid'),
                ]);
            
            DB::table('hisdb.episode')
                ->where('mrn','=',$request->mrn)
                ->where('episno','=',$request->episno)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'reff_ed' => '1',
                    'lastuser'  => strtoupper($request->dressing_lastuser),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function edit_dressing(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $pat_dressing = DB::table('hisdb.pat_dressing')
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno)
                            ->where('compcode','=',session('compcode'));
            
            if($pat_dressing->exists()){
                $pat_dressing
                    ->update([
                        'od_dressing' => $request->od_dressing,
                        'bd_dressing' => $request->bd_dressing,
                        'eod_dressing' => $request->eod_dressing,
                        'others_dressing' => $request->others_dressing,
                        'others_name' => $request->others_name,
                        'solution' => $request->solution,
                        'doctorname' => strtoupper($request->dressing_doctorname),
                        'upduser'  => strtoupper($request->dressing_lastuser),
                        'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser' => strtoupper($request->dressing_lastuser),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
            }else{
                DB::table('hisdb.pat_dressing')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'od_dressing' => $request->od_dressing,
                        'bd_dressing' => $request->bd_dressing,
                        'eod_dressing' => $request->eod_dressing,
                        'others_dressing' => $request->others_dressing,
                        'others_name' => $request->others_name,
                        'solution' => $request->solution,
                        'doctorname' => strtoupper($request->dressing_doctorname),
                        'adduser'  => strtoupper($request->dressing_lastuser),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser' => strtoupper($request->dressing_lastuser),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
            }
            
            DB::table('hisdb.episode')
                ->where('mrn','=',$request->mrn)
                ->where('episno','=',$request->episno)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'reff_ed' => '1',
                    'lastuser'  => strtoupper($request->dressing_lastuser),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                ]);
            
            $queries = DB::getQueryLog();
            // dump($queries);
            
            DB::commit();
            
            $responce = new stdClass();
            
            return json_encode($responce);
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function get_table_dressing(Request $request){
        
        $pat_dressing_obj = DB::table('hisdb.pat_dressing')
                            ->select('compcode','mrn','episno','od_dressing','bd_dressing','eod_dressing','others_dressing','others_name','solution','doctorname as dressing_doctorname','adduser','adddate','upduser','upddate','lastuser as dressing_lastuser','lastupdate','computerid')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno);
        
        $responce = new stdClass();
        
        if($pat_dressing_obj->exists()){
            $pat_dressing_obj = $pat_dressing_obj->first();
            $responce->pat_dressing = $pat_dressing_obj;
        }
        
        return json_encode($responce);
        
    }
    
}