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

class OTDischargeController extends defaultController
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function show(Request $request)
    {
        return view('hisdb.otdischarge.otdischarge');
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
            case 'save_table_otdischarge':
                switch($request->oper){
                    case 'add':
                        return $this->add($request);
                    case 'edit':
                        return $this->edit($request);
                    default:
                        return 'error happen..';
                }
                
            case 'get_table_otdischarge':
                return $this->get_table_otdischarge($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function add_lama(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.otdischarge')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn_otdischarge,
                    'episno' => $request->episno_otdischarge,
                    'patID' => $request->patID,
                    'use2iden' => $request->use2iden,
                    'pat_ot' => $request->pat_ot,
                    'pat_ward' => $request->pat_ward,
                    'pat_remark' => $request->pat_remark,
                    'consciousAlert' => $request->consciousAlert,
                    'consciousDrowsy' => $request->consciousDrowsy,
                    'consciousIntubated' => $request->consciousIntubated,
                    'consciouslvl_ot' => $request->consciouslvl_ot,
                    'consciouslvl_ward' => $request->consciouslvl_ward,
                    'consciouslvl_remark' => $request->consciouslvl_remark,
                    'vitalsign_ot' => $request->vitalsign_ot,
                    'vitalsign_ward' => $request->vitalsign_ward,
                    'vitalsign_remark' => $request->vitalsign_remark,
                    'checksite_ot' => $request->checksite_ot,
                    'checksite_ward' => $request->checksite_ward,
                    'checksite_remark' => $request->checksite_remark,
                    'checkdrains_ot' => $request->checkdrains_ot,
                    'checkdrains_ward' => $request->checkdrains_ward,
                    'checkdrains_remark' => $request->checkdrains_remark,
                    'checkiv_ot' => $request->checkiv_ot,
                    'checkiv_ward' => $request->checkiv_ward,
                    'checkiv_remark' => $request->checkiv_remark,
                    'blood_ot' => $request->blood_ot,
                    'blood_ward' => $request->blood_ward,
                    'blood_remark' => $request->blood_remark,
                    'specimen_ot' => $request->specimen_ot,
                    'specimen_ward' => $request->specimen_ward,
                    'specimen_remark' => $request->specimen_remark,
                    'casenotes' => $request->casenotes,
                    'otherdocs' => $request->otherdocs,
                    'gaform' => $request->gaform,
                    'oldnotes' => $request->oldnotes,
                    'opernotes' => $request->opernotes,
                    'docs_ward' => $request->docs_ward,
                    'docs_ot' => $request->docs_ot,
                    'docs_remark' => $request->docs_remark,
                    'imgstudies_ot' => $request->imgstudies_ot,
                    'imgstudies_ward' => $request->imgstudies_ward,
                    'imgstudies_remark' => $request->imgstudies_remark,
                    'painrelief_ot' => $request->painrelief_ot,
                    'painrelief_ward' => $request->painrelief_ward,
                    'painrelief_remark' => $request->painrelief_remark,
                    'others_ot' => $request->others_ot,
                    'others_ward' => $request->others_ward,
                    'others_remark' => $request->others_remark,
                    'arterial_ot' => $request->arterial_ot,
                    'arterial_ward' => $request->arterial_ward,
                    'arterial_remark' => $request->arterial_remark,
                    'pcapump_ot' => $request->pcapump_ot,
                    'pcapump_ward' => $request->pcapump_ward,
                    'pcapump_remark' => $request->pcapump_remark,
                    'addmore1' => $request->addmore1,
                    'addmore1_ot' => $request->addmore1_ot,
                    'addmore1_ward' => $request->addmore1_ward,
                    'addmore1_remark' => $request->addmore1_remark,
                    'addmore2' => $request->addmore2,
                    'addmore2_ot' => $request->addmore2_ot,
                    'addmore2_ward' => $request->addmore2_ward,
                    'addmore2_remark' => $request->addmore2_remark,
                    'addmore3' => $request->addmore3,
                    'addmore3_ot' => $request->addmore3_ot,
                    'addmore3_ward' => $request->addmore3_ward,
                    'addmore3_remark' => $request->addmore3_remark,
                    'addmore4' => $request->addmore4,
                    'addmore4_ot' => $request->addmore4_ot,
                    'addmore4_ward' => $request->addmore4_ward,
                    'addmore4_remark' => $request->addmore4_remark,
                    'addmore5' => $request->addmore5,
                    'addmore5_ot' => $request->addmore5_ot,
                    'addmore5_ward' => $request->addmore5_ward,
                    'addmore5_remark' => $request->addmore5_remark,
                    'addmore6' => $request->addmore6,
                    'addmore6_ot' => $request->addmore6_ot,
                    'addmore6_ward' => $request->addmore6_ward,
                    'addmore6_remark' => $request->addmore6_remark,
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
            
            DB::table('nursing.otdischarge')
                ->where('mrn','=',$request->mrn_otdischarge)
                ->where('episno','=',$request->episno_otdischarge)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'patID' => $request->patID,
                    'use2iden' => $request->use2iden,
                    'pat_ot' => $request->pat_ot,
                    'pat_ward' => $request->pat_ward,
                    'pat_remark' => $request->pat_remark,
                    'consciousAlert' => $request->consciousAlert,
                    'consciousDrowsy' => $request->consciousDrowsy,
                    'consciousIntubated' => $request->consciousIntubated,
                    'consciouslvl_ot' => $request->consciouslvl_ot,
                    'consciouslvl_ward' => $request->consciouslvl_ward,
                    'consciouslvl_remark' => $request->consciouslvl_remark,
                    'vitalsign_ot' => $request->vitalsign_ot,
                    'vitalsign_ward' => $request->vitalsign_ward,
                    'vitalsign_remark' => $request->vitalsign_remark,
                    'checksite_ot' => $request->checksite_ot,
                    'checksite_ward' => $request->checksite_ward,
                    'checksite_remark' => $request->checksite_remark,
                    'checkdrains_ot' => $request->checkdrains_ot,
                    'checkdrains_ward' => $request->checkdrains_ward,
                    'checkdrains_remark' => $request->checkdrains_remark,
                    'checkiv_ot' => $request->checkiv_ot,
                    'checkiv_ward' => $request->checkiv_ward,
                    'checkiv_remark' => $request->checkiv_remark,
                    'blood_ot' => $request->blood_ot,
                    'blood_ward' => $request->blood_ward,
                    'blood_remark' => $request->blood_remark,
                    'specimen_ot' => $request->specimen_ot,
                    'specimen_ward' => $request->specimen_ward,
                    'specimen_remark' => $request->specimen_remark,
                    'casenotes' => $request->casenotes,
                    'otherdocs' => $request->otherdocs,
                    'gaform' => $request->gaform,
                    'oldnotes' => $request->oldnotes,
                    'opernotes' => $request->opernotes,
                    'docs_ward' => $request->docs_ward,
                    'docs_ot' => $request->docs_ot,
                    'docs_remark' => $request->docs_remark,
                    'imgstudies_ot' => $request->imgstudies_ot,
                    'imgstudies_ward' => $request->imgstudies_ward,
                    'imgstudies_remark' => $request->imgstudies_remark,
                    'painrelief_ot' => $request->painrelief_ot,
                    'painrelief_ward' => $request->painrelief_ward,
                    'painrelief_remark' => $request->painrelief_remark,
                    'others_ot' => $request->others_ot,
                    'others_ward' => $request->others_ward,
                    'others_remark' => $request->others_remark,
                    'arterial_ot' => $request->arterial_ot,
                    'arterial_ward' => $request->arterial_ward,
                    'arterial_remark' => $request->arterial_remark,
                    'pcapump_ot' => $request->pcapump_ot,
                    'pcapump_ward' => $request->pcapump_ward,
                    'pcapump_remark' => $request->pcapump_remark,
                    'addmore1' => $request->addmore1,
                    'addmore1_ot' => $request->addmore1_ot,
                    'addmore1_ward' => $request->addmore1_ward,
                    'addmore1_remark' => $request->addmore1_remark,
                    'addmore2' => $request->addmore2,
                    'addmore2_ot' => $request->addmore2_ot,
                    'addmore2_ward' => $request->addmore2_ward,
                    'addmore2_remark' => $request->addmore2_remark,
                    'addmore3' => $request->addmore3,
                    'addmore3_ot' => $request->addmore3_ot,
                    'addmore3_ward' => $request->addmore3_ward,
                    'addmore3_remark' => $request->addmore3_remark,
                    'addmore4' => $request->addmore4,
                    'addmore4_ot' => $request->addmore4_ot,
                    'addmore4_ward' => $request->addmore4_ward,
                    'addmore4_remark' => $request->addmore4_remark,
                    'addmore5' => $request->addmore5,
                    'addmore5_ot' => $request->addmore5_ot,
                    'addmore5_ward' => $request->addmore5_ward,
                    'addmore5_remark' => $request->addmore5_remark,
                    'addmore6' => $request->addmore6,
                    'addmore6_ot' => $request->addmore6_ot,
                    'addmore6_ward' => $request->addmore6_ward,
                    'addmore6_remark' => $request->addmore6_remark,
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
            
            DB::table('nursing.otdischarge')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn_otdischarge,
                    'episno' => $request->episno_otdischarge,
                    'iPesakit' => $request->iPesakit,
                    'patName' => $request->patName,
                    'identitytag' => $request->identitytag,
                    'pat_checked' => $request->pat_checked,
                    'pat_remark' => $request->pat_remark,
                    'consciousAlert' => $request->consciousAlert,
                    'consciousDrowsy' => $request->consciousDrowsy,
                    'consciousIntubated' => $request->consciousIntubated,
                    'consciouslvl_checked' => $request->consciouslvl_checked,
                    'consciouslvl_remark' => $request->consciouslvl_remark,
                    'vitalsign_checked' => $request->vitalsign_checked,
                    'bpsys1' => $request->bpsys1,
                    'bpdias' => $request->bpdias,
                    'painscore' => $request->painscore,
                    'checksite_checked' => $request->checksite_checked,
                    'checksite_remark' => $request->checksite_remark,
                    'checkdrains_checked' => $request->checkdrains_checked,
                    'checkdrains_remark' => $request->checkdrains_remark,
                    'checkiv_checked' => $request->checkiv_checked,
                    'checkiv_remark' => $request->checkiv_remark,
                    'blood_checked' => $request->blood_checked,
                    'blood_remark' => $request->blood_remark,
                    'specimen_checked' => $request->specimen_checked,
                    'specimen_remark' => $request->specimen_remark,
                    'casenotes' => $request->casenotes,
                    'oldnotes' => $request->oldnotes,
                    'xrays' => $request->xrays,
                    'opernotes' => $request->opernotes,
                    'gaform' => $request->gaform,
                    'docs_checked' => $request->docs_checked,
                    'docs_remark' => $request->docs_remark,
                    'painrelief_checked' => $request->painrelief_checked,
                    'painrelief_remark' => $request->painrelief_remark,
                    'addmore1' => $request->addmore1,
                    'addmore1_checked' => $request->addmore1_checked,
                    'addmore1_remark' => $request->addmore1_remark,
                    'addmore2' => $request->addmore2,
                    'addmore2_checked' => $request->addmore2_checked,
                    'addmore2_remark' => $request->addmore2_remark,
                    'addmore3' => $request->addmore3,
                    'addmore3_checked' => $request->addmore3_checked,
                    'addmore3_remark' => $request->addmore3_remark,
                    'addmore4' => $request->addmore4,
                    'addmore4_checked' => $request->addmore4_checked,
                    'addmore4_remark' => $request->addmore4_remark,
                    'addmore5' => $request->addmore5,
                    'addmore5_checked' => $request->addmore5_checked,
                    'addmore5_remark' => $request->addmore5_remark,
                    'addmore6' => $request->addmore6,
                    'addmore6_checked' => $request->addmore6_checked,
                    'addmore6_remark' => $request->addmore6_remark,
                    'otNurse' => $request->otNurse,
                    'wardNurse' => $request->wardNurse,
                    'entereddate' => $request->entereddate,
                    'enteredtime' => $request->enteredtime,
                    'adduser'  => session('username'),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'computerid' => session('computerid'),
                ]);
            
            if(!empty($request->iPesakit)){
                $pat_mast = DB::table('hisdb.pat_mast')
                            ->where('CompCode',session('compcode'))
                            ->where('MRN',$request->mrn_otdischarge)
                            ->first();
                
                if($pat_mast->iPesakit != $request->iPesakit){
                    DB::table('hisdb.pat_mast')
                        ->where('CompCode',session('compcode'))
                        ->where('MRN',$request->mrn_otdischarge)
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
            
            DB::table('nursing.otdischarge')
                ->where('mrn','=',$request->mrn_otdischarge)
                ->where('episno','=',$request->episno_otdischarge)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'iPesakit' => $request->iPesakit,
                    'patName' => $request->patName,
                    'identitytag' => $request->identitytag,
                    'pat_checked' => $request->pat_checked,
                    'pat_remark' => $request->pat_remark,
                    'consciousAlert' => $request->consciousAlert,
                    'consciousDrowsy' => $request->consciousDrowsy,
                    'consciousIntubated' => $request->consciousIntubated,
                    'consciouslvl_checked' => $request->consciouslvl_checked,
                    'consciouslvl_remark' => $request->consciouslvl_remark,
                    'vitalsign_checked' => $request->vitalsign_checked,
                    'bpsys1' => $request->bpsys1,
                    'bpdias' => $request->bpdias,
                    'painscore' => $request->painscore,
                    'checksite_checked' => $request->checksite_checked,
                    'checksite_remark' => $request->checksite_remark,
                    'checkdrains_checked' => $request->checkdrains_checked,
                    'checkdrains_remark' => $request->checkdrains_remark,
                    'checkiv_checked' => $request->checkiv_checked,
                    'checkiv_remark' => $request->checkiv_remark,
                    'blood_checked' => $request->blood_checked,
                    'blood_remark' => $request->blood_remark,
                    'specimen_checked' => $request->specimen_checked,
                    'specimen_remark' => $request->specimen_remark,
                    'casenotes' => $request->casenotes,
                    'oldnotes' => $request->oldnotes,
                    'xrays' => $request->xrays,
                    'opernotes' => $request->opernotes,
                    'gaform' => $request->gaform,
                    'docs_checked' => $request->docs_checked,
                    'docs_remark' => $request->docs_remark,
                    'painrelief_checked' => $request->painrelief_checked,
                    'painrelief_remark' => $request->painrelief_remark,
                    'addmore1' => $request->addmore1,
                    'addmore1_checked' => $request->addmore1_checked,
                    'addmore1_remark' => $request->addmore1_remark,
                    'addmore2' => $request->addmore2,
                    'addmore2_checked' => $request->addmore2_checked,
                    'addmore2_remark' => $request->addmore2_remark,
                    'addmore3' => $request->addmore3,
                    'addmore3_checked' => $request->addmore3_checked,
                    'addmore3_remark' => $request->addmore3_remark,
                    'addmore4' => $request->addmore4,
                    'addmore4_checked' => $request->addmore4_checked,
                    'addmore4_remark' => $request->addmore4_remark,
                    'addmore5' => $request->addmore5,
                    'addmore5_checked' => $request->addmore5_checked,
                    'addmore5_remark' => $request->addmore5_remark,
                    'addmore6' => $request->addmore6,
                    'addmore6_checked' => $request->addmore6_checked,
                    'addmore6_remark' => $request->addmore6_remark,
                    'otNurse' => $request->otNurse,
                    'wardNurse' => $request->wardNurse,
                    'entereddate' => $request->entereddate,
                    'enteredtime' => $request->enteredtime,
                    'upduser'  => session('username'),
                    'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'computerid' => session('computerid'),
                ]);
            
            if(!empty($request->iPesakit)){
                $pat_mast = DB::table('hisdb.pat_mast')
                            ->where('CompCode',session('compcode'))
                            ->where('MRN',$request->mrn_otdischarge)
                            ->first();
                
                if($pat_mast->iPesakit != $request->iPesakit){
                    DB::table('hisdb.pat_mast')
                        ->where('CompCode',session('compcode'))
                        ->where('MRN',$request->mrn_otdischarge)
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
    
    public function get_table_otdischarge(Request $request){
        
        $otdischarge_obj = DB::table('nursing.otdischarge')
                            ->select('idno','compcode','mrn','episno','iPesakit as i_Pesakit','patName','identitytag','patID','use2iden','pat_ot','pat_ward','pat_checked','pat_remark','consciousAlert','consciousDrowsy','consciousIntubated','consciouslvl_ot','consciouslvl_ward','consciouslvl_checked','consciouslvl_remark','vitalsign_ot','vitalsign_ward','vitalsign_checked','bpsys1','bpdias','painscore','vitalsign_remark','checksite_ot','checksite_ward','checksite_checked','checksite_remark','checkdrains_ot','checkdrains_ward','checkdrains_checked','checkdrains_remark','checkiv_ot','checkiv_ward','checkiv_checked','checkiv_remark','blood_ot','blood_ward','blood_checked','blood_remark','specimen_ot','specimen_ward','specimen_checked','specimen_remark','casenotes','otherdocs','gaform','oldnotes','opernotes','xrays','docs_ward','docs_ot','docs_checked','docs_remark','imgstudies_ot','imgstudies_ward','imgstudies_remark','painrelief_ot','painrelief_ward','painrelief_checked','painrelief_remark','others_ot','others_ward','others_remark','arterial_ot','arterial_ward','arterial_remark','pcapump_ot','pcapump_ward','pcapump_remark','addmore1','addmore1_ot','addmore1_ward','addmore1_checked','addmore1_remark','addmore2','addmore2_ot','addmore2_ward','addmore2_checked','addmore2_remark','addmore3','addmore3_ot','addmore3_ward','addmore3_checked','addmore3_remark','addmore4','addmore4_ot','addmore4_ward','addmore4_checked','addmore4_remark','addmore5','addmore5_ot','addmore5_ward','addmore5_checked','addmore5_remark','addmore6','addmore6_ot','addmore6_ward','addmore6_checked','addmore6_remark','otNurse','wardNurse','entereddate','enteredtime','adduser','adddate','upduser','upddate','lastuser','lastupdate','computerid')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno);
        
        $patmast_obj = DB::table('hisdb.pat_mast')
                        ->select('iPesakit')
                        ->where('compcode',session('compcode'))
                        ->where('mrn','=',$request->mrn);
        
        $responce = new stdClass();
        
        if($otdischarge_obj->exists()){
            $otdischarge_obj = $otdischarge_obj->first();
            $responce->otdischarge = $otdischarge_obj;
        }
        
        if($patmast_obj->exists()){
            $patmast_obj = $patmast_obj->first();
            
            $iPesakit = $patmast_obj->iPesakit;
            $responce->iPesakit = $iPesakit;
        }
        
        return json_encode($responce);
        
    }
    
}