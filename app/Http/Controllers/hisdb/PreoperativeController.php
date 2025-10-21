<?php

namespace App\Http\Controllers\hisdb;

use Illuminate\Http\Request;
use stdClass;
use App\User;
use DB;
use Carbon\Carbon;
use Auth;
use Session;
use App\Http\Controllers\defaultController;

class PreoperativeController extends defaultController
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function show(Request $request)
    {
        return view('hisdb.preoperative.preoperative');
    }
    
    public function table(Request $request)
    {
        switch($request->action){
            default:
                return 'error happen..';
        }
    }
    
    public function form(Request $request)
    {
        DB::enableQueryLog();
        switch($request->action){
            case 'save_table_preoperative':
                switch($request->oper){
                    case 'add':
                        return $this->add($request);
                    case 'edit':
                        return $this->edit($request);
                    default:
                        return 'error happen..';
                }
                
            case 'get_table_preoperative':
                return $this->get_table_preoperative($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function add_lama(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.otpreop')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn_preoperative,
                    'episno' => $request->episno_preoperative,
                    'patID' => $request->patID,
                    'use2iden' => $request->use2iden,
                    'pat_ward' => $request->pat_ward,
                    'pat_ot' => $request->pat_ot,
                    'pat_remark' => $request->pat_remark,
                    'consentSurgery' => $request->consentSurgery,
                    'consentAnaesth' => $request->consentAnaesth,
                    'consentTransf' => $request->consentTransf,
                    'consentPhoto' => $request->consentPhoto,
                    'checkForm' => $request->checkForm,
                    'checkPat' => $request->checkPat,
                    'checkList' => $request->checkList,
                    'consent_ward' => $request->consent_ward,
                    'consent_ot' => $request->consent_ot,
                    'consent_remark' => $request->consent_remark,
                    'checkSide_left' => $request->checkSide_left,
                    'checkSide_right' => $request->checkSide_right,
                    'checkSide_na' => $request->checkSide_na,
                    'checkSide_ward' => $request->checkSide_ward,
                    'checkSide_ot' => $request->checkSide_ot,
                    'checkSide_remark' => $request->checkSide_remark,
                    'opSite_mark' => $request->opSite_mark,
                    'opSite_na' => $request->opSite_na,
                    'opSite_ward' => $request->opSite_ward,
                    'opSite_ot' => $request->opSite_ot,
                    'opSite_remark' => $request->opSite_remark,
                    'lastmeal_date' => $request->lastmeal_date,
                    'lastmeal_time' => $request->lastmeal_time,
                    'lastmeal_ward' => $request->lastmeal_ward,
                    'lastmeal_ot' => $request->lastmeal_ot,
                    'lastmeal_remark' => $request->lastmeal_remark,
                    'checkItem_na' => $request->checkItem_na,
                    'checkItem_ward' => $request->checkItem_ward,
                    'checkItem_ot' => $request->checkItem_ot,
                    'checkItem_remark' => $request->checkItem_remark,
                    'allergies' => $request->allergies,
                    'allergies_ward' => $request->allergies_ward,
                    'allergies_ot' => $request->allergies_ot,
                    'allergies_remark' => $request->allergies_remark,
                    'implantAvailable' => $request->implantAvailable,
                    'implant_ward' => $request->implant_ward,
                    'implant_ot' => $request->implant_ot,
                    'implant_remark' => $request->implant_remark,
                    'premed_na' => $request->premed_na,
                    'premed_ward' => $request->premed_ward,
                    'premed_ot' => $request->premed_ot,
                    'premed_remark' => $request->premed_remark,
                    'blood_na' => $request->blood_na,
                    'blood_ward' => $request->blood_ward,
                    'blood_ot' => $request->blood_ot,
                    'blood_remark' => $request->blood_remark,
                    'casenotes_na' => $request->casenotes_na,
                    'casenotes_ward' => $request->casenotes_ward,
                    'casenotes_ot' => $request->casenotes_ot,
                    'casenotes_remark' => $request->casenotes_remark,
                    'oldnotes_na' => $request->oldnotes_na,
                    'oldnotes_ward' => $request->oldnotes_ward,
                    'oldnotes_ot' => $request->oldnotes_ot,
                    'oldnotes_remark' => $request->oldnotes_remark,
                    'imaging_na' => $request->imaging_na,
                    'imaging_ward' => $request->imaging_ward,
                    'imaging_ot' => $request->imaging_ot,
                    'imaging_remark' => $request->imaging_remark,
                    'bpsys1' => $request->bpsys1,
                    'bpdias' => $request->bpdias,
                    'pulse' => $request->pulse,
                    'temperature' => $request->temperature,
                    'vs_ward' => $request->vs_ward,
                    'vs_ot' => $request->vs_ot,
                    'vs_remark' => $request->vs_remark,
                    'others_na' => $request->others_na,
                    'others_ward' => $request->others_ward,
                    'others_ot' => $request->others_ot,
                    'others_remark' => $request->others_remark,
                    'importantIssues' => $request->importantIssues,
                    'info_temperature' => $request->info_temperature,
                    'info_humidity' => $request->info_humidity,
                    'info_otroom' => $request->info_otroom,
                    'info_anaesthetist' => $request->info_anaesthetist,
                    'info_surgeon' => $request->info_surgeon,
                    'info_asstsurgeon' => $request->info_asstsurgeon,
                    'adduser'  => session('username'),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'computerid' => session('computerid'),
                ]);
            
            DB::commit();
            
            $responce = new stdClass();
            
            return json_encode($responce);
        
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function edit_lama(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.otpreop')
                ->where('mrn','=',$request->mrn_preoperative)
                ->where('episno','=',$request->episno_preoperative)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'patID' => $request->patID,
                    'use2iden' => $request->use2iden,
                    'pat_ward' => $request->pat_ward,
                    'pat_ot' => $request->pat_ot,
                    'pat_remark' => $request->pat_remark,
                    'consentSurgery' => $request->consentSurgery,
                    'consentAnaesth' => $request->consentAnaesth,
                    'consentTransf' => $request->consentTransf,
                    'consentPhoto' => $request->consentPhoto,
                    'checkForm' => $request->checkForm,
                    'checkPat' => $request->checkPat,
                    'checkList' => $request->checkList,
                    'consent_ward' => $request->consent_ward,
                    'consent_ot' => $request->consent_ot,
                    'consent_remark' => $request->consent_remark,
                    'checkSide_left' => $request->checkSide_left,
                    'checkSide_right' => $request->checkSide_right,
                    'checkSide_na' => $request->checkSide_na,
                    'checkSide_ward' => $request->checkSide_ward,
                    'checkSide_ot' => $request->checkSide_ot,
                    'checkSide_remark' => $request->checkSide_remark,
                    'opSite_mark' => $request->opSite_mark,
                    'opSite_na' => $request->opSite_na,
                    'opSite_ward' => $request->opSite_ward,
                    'opSite_ot' => $request->opSite_ot,
                    'opSite_remark' => $request->opSite_remark,
                    'lastmeal_date' => $request->lastmeal_date,
                    'lastmeal_time' => $request->lastmeal_time,
                    'lastmeal_ward' => $request->lastmeal_ward,
                    'lastmeal_ot' => $request->lastmeal_ot,
                    'lastmeal_remark' => $request->lastmeal_remark,
                    'checkItem_na' => $request->checkItem_na,
                    'checkItem_ward' => $request->checkItem_ward,
                    'checkItem_ot' => $request->checkItem_ot,
                    'checkItem_remark' => $request->checkItem_remark,
                    'allergies' => $request->allergies,
                    'allergies_ward' => $request->allergies_ward,
                    'allergies_ot' => $request->allergies_ot,
                    'allergies_remark' => $request->allergies_remark,
                    'implantAvailable' => $request->implantAvailable,
                    'implant_ward' => $request->implant_ward,
                    'implant_ot' => $request->implant_ot,
                    'implant_remark' => $request->implant_remark,
                    'premed_na' => $request->premed_na,
                    'premed_ward' => $request->premed_ward,
                    'premed_ot' => $request->premed_ot,
                    'premed_remark' => $request->premed_remark,
                    'blood_na' => $request->blood_na,
                    'blood_ward' => $request->blood_ward,
                    'blood_ot' => $request->blood_ot,
                    'blood_remark' => $request->blood_remark,
                    'casenotes_na' => $request->casenotes_na,
                    'casenotes_ward' => $request->casenotes_ward,
                    'casenotes_ot' => $request->casenotes_ot,
                    'casenotes_remark' => $request->casenotes_remark,
                    'oldnotes_na' => $request->oldnotes_na,
                    'oldnotes_ward' => $request->oldnotes_ward,
                    'oldnotes_ot' => $request->oldnotes_ot,
                    'oldnotes_remark' => $request->oldnotes_remark,
                    'imaging_na' => $request->imaging_na,
                    'imaging_ward' => $request->imaging_ward,
                    'imaging_ot' => $request->imaging_ot,
                    'imaging_remark' => $request->imaging_remark,
                    'bpsys1' => $request->bpsys1,
                    'bpdias' => $request->bpdias,
                    'pulse' => $request->pulse,
                    'temperature' => $request->temperature,
                    'vs_ward' => $request->vs_ward,
                    'vs_ot' => $request->vs_ot,
                    'vs_remark' => $request->vs_remark,
                    'others_na' => $request->others_na,
                    'others_ward' => $request->others_ward,
                    'others_ot' => $request->others_ot,
                    'others_remark' => $request->others_remark,
                    'importantIssues' => $request->importantIssues,
                    'info_temperature' => $request->info_temperature,
                    'info_humidity' => $request->info_humidity,
                    'info_otroom' => $request->info_otroom,
                    'info_anaesthetist' => $request->info_anaesthetist,
                    'info_surgeon' => $request->info_surgeon,
                    'info_asstsurgeon' => $request->info_asstsurgeon,
                    'upduser'  => session('username'),
                    'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'computerid' => session('computerid'),
                ]);
            
            // $queries = DB::getQueryLog();
            // dump($queries);
            
            DB::commit();
            
            $responce = new stdClass();
            
            return json_encode($responce);
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function add(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.otpreop')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn_preoperative,
                    'episno' => $request->episno_preoperative,
                    // PATIENT PROFILE
                    'iPesakit' => $request->iPesakit,
                    // 'regno' => $request->regno,
                    'diagnosis' => $request->diagnosis,
                    'operation' => $request->operation,
                    'checkby' => $request->checkby,
                    'entereddate' => $request->entereddate,
                    'contactperson' => $request->contactperson,
                    // PRE-TRANSFER CHECK
                    'patName' => $request->patName,
                    'identitytag' => $request->identitytag,
                    'pat_ward' => $request->pat_ward,
                    'pat_ot' => $request->pat_ot,
                    'pat_remark' => $request->pat_remark,
                    'consentSurgery' => $request->consentSurgery,
                    'consentAnaesth' => $request->consentAnaesth,
                    'consentTransf' => $request->consentTransf,
                    'consent_ward' => $request->consent_ward,
                    'consent_ot' => $request->consent_ot,
                    'consent_remark' => $request->consent_remark,
                    'checkSide_left' => $request->checkSide_left,
                    'checkSide_right' => $request->checkSide_right,
                    'checkSide_na' => $request->checkSide_na,
                    'checkSide_ward' => $request->checkSide_ward,
                    'checkSide_ot' => $request->checkSide_ot,
                    'checkSide_remark' => $request->checkSide_remark,
                    'opSite_mark' => $request->opSite_mark,
                    // 'opSite_na' => $request->opSite_na,
                    'opSite_ward' => $request->opSite_ward,
                    'opSite_ot' => $request->opSite_ot,
                    'opSite_remark' => $request->opSite_remark,
                    'lastmeal_date' => $request->lastmeal_date,
                    'lastmeal_time' => $request->lastmeal_time,
                    'lastmeal_ward' => $request->lastmeal_ward,
                    'lastmeal_ot' => $request->lastmeal_ot,
                    'lastmeal_remark' => $request->lastmeal_remark,
                    'checkItem_ward' => $request->checkItem_ward,
                    'checkItem_ot' => $request->checkItem_ot,
                    'checkItem_remark' => $request->checkItem_remark,
                    'premed_ward' => $request->premed_ward,
                    'premed_ot' => $request->premed_ot,
                    'premed_remark' => $request->premed_remark,
                    'blood_ward' => $request->blood_ward,
                    'blood_ot' => $request->blood_ot,
                    'blood_remark' => $request->blood_remark,
                    'casenotes' => $request->casenotes,
                    'oldnotes' => $request->oldnotes,
                    'xrays' => $request->xrays,
                    'casenotes_ward' => $request->casenotes_ward,
                    'casenotes_ot' => $request->casenotes_ot,
                    'casenotes_remark' => $request->casenotes_remark,
                    'bpsys1' => $request->bpsys1,
                    'bpdias' => $request->bpdias,
                    'pulse' => $request->pulse,
                    'vs_ward' => $request->vs_ward,
                    'vs_ot' => $request->vs_ot,
                    'vs_remark' => $request->vs_remark,
                    'preopvisit_ward' => $request->preopvisit_ward,
                    'preopvisit_ot' => $request->preopvisit_ot,
                    'preopvisit_remark' => $request->preopvisit_remark,
                    'wardnurse' => $request->wardnurse,
                    'otnurse' => $request->otnurse,
                    'lmp' => $request->lmp,
                    // INFORMATION ON OPERATING ROOM
                    'info_otroom' => $request->info_otroom,
                    'info_anaesthetist' => $request->info_anaesthetist,
                    'info_surgeon' => $request->info_surgeon,
                    'starttime' => $request->starttime,
                    'endtime' => $request->endtime,
                    'adduser'  => session('username'),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'computerid' => session('computerid'),
                ]);
            
            if(!empty($request->iPesakit)){
                $pat_mast = DB::table('hisdb.pat_mast')
                            ->where('CompCode',session('compcode'))
                            ->where('MRN',$request->mrn_preoperative)
                            ->first();
                
                if($pat_mast->iPesakit != $request->iPesakit){
                    DB::table('hisdb.pat_mast')
                        ->where('CompCode',session('compcode'))
                        ->where('MRN',$request->mrn_preoperative)
                        ->update([
                            'iPesakit' => $request->iPesakit,
                        ]);
                }
            }
            
            DB::commit();
            
            $responce = new stdClass();
            
            return json_encode($responce);
        
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function edit(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.otpreop')
                ->where('mrn','=',$request->mrn_preoperative)
                ->where('episno','=',$request->episno_preoperative)
                ->where('compcode','=',session('compcode'))
                ->update([
                    // PATIENT PROFILE
                    'iPesakit' => $request->iPesakit,
                    // 'regno' => $request->regno,
                    'diagnosis' => $request->diagnosis,
                    'operation' => $request->operation,
                    'checkby' => $request->checkby,
                    'entereddate' => $request->entereddate,
                    'contactperson' => $request->contactperson,
                    // PRE-TRANSFER CHECK
                    'patName' => $request->patName,
                    'identitytag' => $request->identitytag,
                    'pat_ward' => $request->pat_ward,
                    'pat_ot' => $request->pat_ot,
                    'pat_remark' => $request->pat_remark,
                    'consentSurgery' => $request->consentSurgery,
                    'consentAnaesth' => $request->consentAnaesth,
                    'consentTransf' => $request->consentTransf,
                    'consent_ward' => $request->consent_ward,
                    'consent_ot' => $request->consent_ot,
                    'consent_remark' => $request->consent_remark,
                    'checkSide_left' => $request->checkSide_left,
                    'checkSide_right' => $request->checkSide_right,
                    'checkSide_na' => $request->checkSide_na,
                    'checkSide_ward' => $request->checkSide_ward,
                    'checkSide_ot' => $request->checkSide_ot,
                    'checkSide_remark' => $request->checkSide_remark,
                    'opSite_mark' => $request->opSite_mark,
                    // 'opSite_na' => $request->opSite_na,
                    'opSite_ward' => $request->opSite_ward,
                    'opSite_ot' => $request->opSite_ot,
                    'opSite_remark' => $request->opSite_remark,
                    'lastmeal_date' => $request->lastmeal_date,
                    'lastmeal_time' => $request->lastmeal_time,
                    'lastmeal_ward' => $request->lastmeal_ward,
                    'lastmeal_ot' => $request->lastmeal_ot,
                    'lastmeal_remark' => $request->lastmeal_remark,
                    'checkItem_ward' => $request->checkItem_ward,
                    'checkItem_ot' => $request->checkItem_ot,
                    'checkItem_remark' => $request->checkItem_remark,
                    'premed_ward' => $request->premed_ward,
                    'premed_ot' => $request->premed_ot,
                    'premed_remark' => $request->premed_remark,
                    'blood_ward' => $request->blood_ward,
                    'blood_ot' => $request->blood_ot,
                    'blood_remark' => $request->blood_remark,
                    'casenotes' => $request->casenotes,
                    'oldnotes' => $request->oldnotes,
                    'xrays' => $request->xrays,
                    'casenotes_ward' => $request->casenotes_ward,
                    'casenotes_ot' => $request->casenotes_ot,
                    'casenotes_remark' => $request->casenotes_remark,
                    'bpsys1' => $request->bpsys1,
                    'bpdias' => $request->bpdias,
                    'pulse' => $request->pulse,
                    'vs_ward' => $request->vs_ward,
                    'vs_ot' => $request->vs_ot,
                    'vs_remark' => $request->vs_remark,
                    'preopvisit_ward' => $request->preopvisit_ward,
                    'preopvisit_ot' => $request->preopvisit_ot,
                    'preopvisit_remark' => $request->preopvisit_remark,
                    'wardnurse' => $request->wardnurse,
                    'otnurse' => $request->otnurse,
                    'lmp' => $request->lmp,
                    // INFORMATION ON OPERATING ROOM
                    'info_otroom' => $request->info_otroom,
                    'info_anaesthetist' => $request->info_anaesthetist,
                    'info_surgeon' => $request->info_surgeon,
                    'starttime' => $request->starttime,
                    'endtime' => $request->endtime,
                    'upduser'  => session('username'),
                    'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'computerid' => session('computerid'),
                ]);
            
            if(!empty($request->iPesakit)){
                $pat_mast = DB::table('hisdb.pat_mast')
                            ->where('CompCode',session('compcode'))
                            ->where('MRN',$request->mrn_preoperative)
                            ->first();
                
                if($pat_mast->iPesakit != $request->iPesakit){
                    DB::table('hisdb.pat_mast')
                        ->where('CompCode',session('compcode'))
                        ->where('MRN',$request->mrn_preoperative)
                        ->update([
                            'iPesakit' => $request->iPesakit,
                        ]);
                }
            }
            
            // $queries = DB::getQueryLog();
            // dump($queries);
            
            DB::commit();
            
            $responce = new stdClass();
            
            return json_encode($responce);
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function get_table_preoperative(Request $request){
        
        $preop_obj = DB::table('nursing.otpreop as ot')
                    ->select(['ot.idno','ot.compcode','ot.mrn','ot.episno','ot.iPesakit as i_Pesakit','ot.regno','ot.diagnosis','ot.operation','ot.checkby','ot.entereddate','ot.contactperson','ot.patName','ot.identitytag','ot.patID','ot.use2iden','ot.pat_ward','ot.pat_ot','ot.pat_remark','ot.consentSurgery','ot.consentAnaesth','ot.consentTransf','ot.consentPhoto','ot.checkForm','ot.checkPat','ot.checkList','ot.consent_ward','ot.consent_ot','ot.consent_remark','ot.checkSide_left','ot.checkSide_right','ot.checkSide_na','ot.checkSide_ward','ot.checkSide_ot','ot.checkSide_remark','ot.opSite_mark','ot.opSite_na','ot.opSite_ward','ot.opSite_ot','ot.opSite_remark','ot.lastmeal_date','ot.lastmeal_time','ot.lastmeal_ward','ot.lastmeal_ot','ot.lastmeal_remark','ot.checkItem_na','ot.checkItem_ward','ot.checkItem_ot','ot.checkItem_remark','ot.allergies','ot.allergies_ward','ot.allergies_ot','ot.allergies_remark','ot.implantAvailable','ot.implant_ward','ot.implant_ot','ot.implant_remark','ot.premed_na','ot.premed_ward','ot.premed_ot','ot.premed_remark','ot.blood_na','ot.blood_ward','ot.blood_ot','ot.blood_remark','ot.casenotes','ot.casenotes_na','ot.casenotes_ward','ot.casenotes_ot','ot.casenotes_remark','ot.oldnotes','ot.oldnotes_na','ot.oldnotes_ward','ot.oldnotes_ot','ot.oldnotes_remark','ot.xrays','ot.imaging_na','ot.imaging_ward','ot.imaging_ot','ot.imaging_remark','ot.bpsys1','ot.bpdias','ot.pulse','ot.temperature','ot.vs_ward','ot.vs_ot','ot.vs_remark','ot.others_na','ot.others_ward','ot.others_ot','ot.others_remark','ot.preopvisit_ward','ot.preopvisit_ot','ot.preopvisit_remark','ot.wardnurse','ot.otnurse','ot.lmp','ot.importantIssues','ot.info_temperature','ot.info_humidity','ot.info_otroom','ot.info_anaesthetist','ot.info_surgeon','ot.info_asstsurgeon','ot.starttime','ot.endtime','ot.adduser','ot.adddate','ot.upduser','ot.upddate','ot.lastuser','ot.lastupdate','ot.computerid','d1.doctorname as desc_anaesthetist','d2.doctorname as desc_surgeon','d3.doctorname as desc_asstsurgeon'])
                    ->leftJoin('hisdb.doctor AS d1', function($join) use ($request){
                        $join = $join->on('d1.doctorcode', '=', 'ot.info_anaesthetist')
                                        ->where('d1.compcode','=',session('compcode'));
                    })
                    ->leftJoin('hisdb.doctor AS d2', function($join) use ($request){
                        $join = $join->on('d2.doctorcode', '=', 'ot.info_surgeon')
                                        ->where('d2.compcode','=',session('compcode'));
                    })
                    ->leftJoin('hisdb.doctor as d3', function($join) use ($request){
                        $join = $join->on('d3.doctorcode', '=', 'ot.info_asstsurgeon')
                                        ->where('d3.compcode','=',session('compcode'));
                    })
                    ->where('ot.compcode','=',session('compcode'))
                    ->where('ot.mrn','=',$request->mrn)
                    ->where('ot.episno','=',$request->episno);
        
        $patmast_obj = DB::table('hisdb.pat_mast')
                        ->select('iPesakit')
                        ->where('compcode',session('compcode'))
                        ->where('mrn','=',$request->mrn);
        
        $responce = new stdClass();
        
        if($preop_obj->exists()){
            $preop_obj = $preop_obj->first();
            $responce->preop = $preop_obj;
        }
        
        if($patmast_obj->exists()){
            $patmast_obj = $patmast_obj->first();
            
            $iPesakit = $patmast_obj->iPesakit;
            $responce->iPesakit = $iPesakit;
        }
        
        return json_encode($responce);
        
    }
    
    public function get_entry(Request $request)
    {
        $responce = new stdClass();
        
        switch ($request->action) {
            case 'get_reg_doctor':
                $data = DB::table('hisdb.doctor')
                        ->select('doctorcode as code','doctorname as description')
                        ->where('recstatus','=','ACTIVE')
                        ->where('compcode','=',session('compcode'));
                
                if(!empty($request->search)){
                    $data = $data->where('description','=',$request->search)->first();
                }else{
                    $data = $data->get();
                }
                break;
            
            default:
                $data = 'nothing';
                break;
        }
        
        $responce->data = $data;
        return json_encode($responce);
    }
    
}