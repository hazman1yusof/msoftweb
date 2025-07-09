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

class OperTeamController extends defaultController
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function show(Request $request)
    {
        return view('hisdb.oper_team.oper_team');
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
            case 'save_table_oper_team':
                switch($request->oper){
                    case 'add':
                        return $this->add($request);
                    case 'edit':
                        return $this->edit($request);
                    default:
                        return 'error happen..';
                }
                
            case 'get_table_oper_team':
                return $this->get_table_oper_team($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function add_lama(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.otteam')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn_oper_team,
                    'episno' => $request->episno_oper_team,
                    'confirmedPt' => $request->confirmedPt,
                    'opSite_mark' => $request->opSite_mark,
                    'opSite_na' => $request->opSite_na,
                    'machine_check' => $request->machine_check,
                    'machine_na' => $request->machine_na,
                    'monitor_on' => $request->monitor_on,
                    'monitor_na' => $request->monitor_na,
                    'allergy_remark' => $request->allergy_remark,
                    'ptAllergy' => $request->ptAllergy,
                    'difficultAirway' => $request->difficultAirway,
                    'difficultAirway_na' => $request->difficultAirway_na,
                    'gxmgsh' => $request->gxmgsh,
                    'gxmgsh_na' => $request->gxmgsh_na,
                    'ivAccess' => $request->ivAccess,
                    'apparatus_check' => $request->apparatus_check,
                    'otTable_check' => $request->otTable_check,
                    'whiteboard' => $request->whiteboard,
                    'introTeam' => $request->introTeam,
                    'bsi_confirmedPt' => $request->bsi_confirmedPt,
                    'antibioProphy' => $request->antibioProphy,
                    'displayImg' => $request->displayImg,
                    'briefSurgeon' => $request->briefSurgeon,
                    'anaesthReview' => $request->anaesthReview,
                    'scrubnurseReview' => $request->scrubnurseReview,
                    'perfusionistReview' => $request->perfusionistReview,
                    'surgeonStart' => $request->surgeonStart,
                    'periodicUpdate' => $request->periodicUpdate,
                    'shoutOut' => $request->shoutOut,
                    'preclosure' => $request->preclosure,
                    'finalProcedure' => $request->finalProcedure,
                    'finalCount' => $request->finalCount,
                    'specimenlabel' => $request->specimenlabel,
                    'specimenlabel_na' => $request->specimenlabel_na,
                    'issuesAddressed' => $request->issuesAddressed,
                    'specialinstruction' => $request->specialinstruction,
                    'relative_remark' => $request->relative_remark,
                    'informRelative' => $request->informRelative,
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
            
            DB::table('nursing.otteam')
                ->where('mrn','=',$request->mrn_oper_team)
                ->where('episno','=',$request->episno_oper_team)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'confirmedPt' => $request->confirmedPt,
                    'opSite_mark' => $request->opSite_mark,
                    'opSite_na' => $request->opSite_na,
                    'machine_check' => $request->machine_check,
                    'machine_na' => $request->machine_na,
                    'monitor_on' => $request->monitor_on,
                    'monitor_na' => $request->monitor_na,
                    'allergy_remark' => $request->allergy_remark,
                    'ptAllergy' => $request->ptAllergy,
                    'difficultAirway' => $request->difficultAirway,
                    'difficultAirway_na' => $request->difficultAirway_na,
                    'gxmgsh' => $request->gxmgsh,
                    'gxmgsh_na' => $request->gxmgsh_na,
                    'ivAccess' => $request->ivAccess,
                    'apparatus_check' => $request->apparatus_check,
                    'otTable_check' => $request->otTable_check,
                    'whiteboard' => $request->whiteboard,
                    'introTeam' => $request->introTeam,
                    'bsi_confirmedPt' => $request->bsi_confirmedPt,
                    'antibioProphy' => $request->antibioProphy,
                    'displayImg' => $request->displayImg,
                    'briefSurgeon' => $request->briefSurgeon,
                    'anaesthReview' => $request->anaesthReview,
                    'scrubnurseReview' => $request->scrubnurseReview,
                    'perfusionistReview' => $request->perfusionistReview,
                    'surgeonStart' => $request->surgeonStart,
                    'periodicUpdate' => $request->periodicUpdate,
                    'shoutOut' => $request->shoutOut,
                    'preclosure' => $request->preclosure,
                    'finalProcedure' => $request->finalProcedure,
                    'finalCount' => $request->finalCount,
                    'specimenlabel' => $request->specimenlabel,
                    'specimenlabel_na' => $request->specimenlabel_na,
                    'issuesAddressed' => $request->issuesAddressed,
                    'specialinstruction' => $request->specialinstruction,
                    'relative_remark' => $request->relative_remark,
                    'informRelative' => $request->informRelative,
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
            
            DB::table('nursing.otteam')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn_oper_team,
                    'episno' => $request->episno_oper_team,
                    'iPesakit' => $request->iPesakit,
                    // BEFORE INDUCTION OF ANAESTHESIA
                    'confirmedPt' => $request->confirmedPt,
                    'opSite_mark' => $request->opSite_mark,
                    'opSite_na' => $request->opSite_na,
                    'machine_check' => $request->machine_check,
                    'pulseoximeter' => $request->pulseoximeter,
                    'ptAllergy' => $request->ptAllergy,
                    'difficultAirway' => $request->difficultAirway,
                    'bloodloss' => $request->bloodloss,
                    'ivAccess' => $request->ivAccess,
                    // BEFORE SKIN INCISION
                    'whiteboard' => $request->whiteboard,
                    'introTeam' => $request->introTeam,
                    'bsi_confirmedPt' => $request->bsi_confirmedPt,
                    'antibioProphy' => $request->antibioProphy,
                    'antibioProphy_na' => $request->antibioProphy_na,
                    'displayImg_na' => $request->displayImg_na,
                    'displayImg' => $request->displayImg,
                    'briefSurgeon' => $request->briefSurgeon,
                    'surgeonReview_remark' => $request->surgeonReview_remark,
                    'anaesthReview' => $request->anaesthReview,
                    'scrubnurseReview' => $request->scrubnurseReview,
                    // DURING PROCEDURE
                    'procedure_hdr' => $request->procedure_hdr,
                    'checkin' => $request->checkin,
                    'periodicUpdate' => $request->periodicUpdate,
                    'shoutOut' => $request->shoutOut,
                    'preclosure' => $request->preclosure,
                    // BEFORE PATIENT LEAVES OPERATING ROOM
                    'finalProcedure' => $request->finalProcedure,
                    'finalCount' => $request->finalCount,
                    'specimenlabel' => $request->specimenlabel,
                    'issuesAddressed' => $request->issuesAddressed,
                    'specialinstruction' => $request->specialinstruction,
                    'informRelative' => $request->informRelative,
                    'coordinator' => $request->coordinator,
                    'adduser'  => session('username'),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'computerid' => session('computerid'),
                ]);
            
            if(!empty($request->iPesakit)){
                $pat_mast = DB::table('hisdb.pat_mast')
                            ->where('CompCode',session('compcode'))
                            ->where('MRN',$request->mrn_oper_team)
                            ->first();
                
                if($pat_mast->iPesakit != $request->iPesakit){
                    DB::table('hisdb.pat_mast')
                        ->where('CompCode',session('compcode'))
                        ->where('MRN',$request->mrn_oper_team)
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
            
            DB::table('nursing.otteam')
                ->where('mrn','=',$request->mrn_oper_team)
                ->where('episno','=',$request->episno_oper_team)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'iPesakit' => $request->iPesakit,
                    // BEFORE INDUCTION OF ANAESTHESIA
                    'confirmedPt' => $request->confirmedPt,
                    'opSite_mark' => $request->opSite_mark,
                    'opSite_na' => $request->opSite_na,
                    'machine_check' => $request->machine_check,
                    'pulseoximeter' => $request->pulseoximeter,
                    'ptAllergy' => $request->ptAllergy,
                    'difficultAirway' => $request->difficultAirway,
                    'bloodloss' => $request->bloodloss,
                    'ivAccess' => $request->ivAccess,
                    // BEFORE SKIN INCISION
                    'whiteboard' => $request->whiteboard,
                    'introTeam' => $request->introTeam,
                    'bsi_confirmedPt' => $request->bsi_confirmedPt,
                    'antibioProphy' => $request->antibioProphy,
                    'antibioProphy_na' => $request->antibioProphy_na,
                    'displayImg_na' => $request->displayImg_na,
                    'displayImg' => $request->displayImg,
                    'briefSurgeon' => $request->briefSurgeon,
                    'surgeonReview_remark' => $request->surgeonReview_remark,
                    'anaesthReview' => $request->anaesthReview,
                    'scrubnurseReview' => $request->scrubnurseReview,
                    // DURING PROCEDURE
                    'procedure_hdr' => $request->procedure_hdr,
                    'checkin' => $request->checkin,
                    'periodicUpdate' => $request->periodicUpdate,
                    'shoutOut' => $request->shoutOut,
                    'preclosure' => $request->preclosure,
                    // BEFORE PATIENT LEAVES OPERATING ROOM
                    'finalProcedure' => $request->finalProcedure,
                    'finalCount' => $request->finalCount,
                    'specimenlabel' => $request->specimenlabel,
                    'issuesAddressed' => $request->issuesAddressed,
                    'specialinstruction' => $request->specialinstruction,
                    'informRelative' => $request->informRelative,
                    'coordinator' => $request->coordinator,
                    'upduser'  => session('username'),
                    'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'computerid' => session('computerid'),
                ]);
            
            if(!empty($request->iPesakit)){
                $pat_mast = DB::table('hisdb.pat_mast')
                            ->where('CompCode',session('compcode'))
                            ->where('MRN',$request->mrn_oper_team)
                            ->first();
                
                if($pat_mast->iPesakit != $request->iPesakit){
                    DB::table('hisdb.pat_mast')
                        ->where('CompCode',session('compcode'))
                        ->where('MRN',$request->mrn_oper_team)
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
    
    public function get_table_oper_team(Request $request){
        
        $otteam_obj = DB::table('nursing.otteam')
                    ->select('idno','compcode','mrn','episno','iPesakit as i_Pesakit','confirmedPt','opSite_mark','opSite_na','machine_check','machine_na','pulseoximeter','monitor_on','monitor_na','allergy_remark','ptAllergy','difficultAirway','difficultAirway_na','bloodloss','gxmgsh','gxmgsh_na','ivAccess','apparatus_check','otTable_check','whiteboard','introTeam','bsi_confirmedPt','antibioProphy','antibioProphy_na','displayImg','displayImg_na','briefSurgeon','surgeonReview_remark','anaesthReview','scrubnurseReview','perfusionistReview','surgeonStart','procedure_hdr','checkin','periodicUpdate','shoutOut','preclosure','finalProcedure','finalCount','specimenlabel','specimenlabel_na','issuesAddressed','specialinstruction','relative_remark','informRelative','coordinator','adduser','adddate','upduser','upddate','lastuser','lastupdate','computerid')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$request->mrn)
                    ->where('episno','=',$request->episno);
        
        $patmast_obj = DB::table('hisdb.pat_mast')
                        ->select('iPesakit')
                        ->where('compcode',session('compcode'))
                        ->where('mrn','=',$request->mrn);
        
        $responce = new stdClass();
        
        if($otteam_obj->exists()){
            $otteam_obj = $otteam_obj->first();
            $responce->otteam = $otteam_obj;
        }
        
        if($patmast_obj->exists()){
            $patmast_obj = $patmast_obj->first();
            
            $iPesakit = $patmast_obj->iPesakit;
            $responce->iPesakit = $iPesakit;
        }
        
        return json_encode($responce);
        
    }
    
}