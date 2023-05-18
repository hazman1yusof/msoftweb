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
    
    public function add(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.otteam')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_oper_team,
                        'episno' => $request->episno_oper_team,
                        'confirmed_pt' => $request->confirmed_pt,
                        'op_site_mark' => $request->op_site_mark,
                        'op_site_na' => $request->op_site_na,
                        'machine_chck' => $request->machine_chck,
                        'machine_na' => $request->machine_na,
                        'monitor_on' => $request->monitor_on,
                        'monitor_na' => $request->monitor_na,
                        'allergy_remark' => $request->allergy_remark,
                        'pt_allergy' => $request->pt_allergy,
                        'diff_airway' => $request->diff_airway,
                        'diff_airway_na' => $request->diff_airway_na,
                        'gxm_gsh' => $request->gxm_gsh,
                        'gxm_gsh_na' => $request->gxm_gsh_na,
                        'iv_access' => $request->iv_access,
                        'apparatus_chck' => $request->apparatus_chck,
                        'ottable_chck' => $request->ottable_chck,
                        'board_surgeon' => $request->board_surgeon,
                        'intro_team' => $request->intro_team,
                        'bsi_confirmed_pt' => $request->bsi_confirmed_pt,
                        'antibio_prophy' => $request->antibio_prophy,
                        'display_img' => $request->display_img,
                        'brief_surgeon' => $request->brief_surgeon,
                        'anaesth_review' => $request->anaesth_review,
                        'scrubnrse_review' => $request->scrubnrse_review,
                        'pfusion_review' => $request->pfusion_review,
                        'surgeon_start' => $request->surgeon_start,
                        'periodic_upd' => $request->periodic_upd,
                        'shout_out' => $request->shout_out,
                        'pre_disclsre' => $request->pre_disclsre,
                        'final_procdre' => $request->final_procdre,
                        'final_count' => $request->final_count,
                        'specimen_lbl' => $request->specimen_lbl,
                        'specimen_lbl_na' => $request->specimen_lbl_na,
                        'incident_addr' => $request->incident_addr,
                        'postop_instr' => $request->postop_instr,
                        'relative_remark' => $request->relative_remark,
                        'inform_relative' => $request->inform_relative,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            
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
                    'confirmed_pt' => $request->confirmed_pt,
                    'op_site_mark' => $request->op_site_mark,
                    'op_site_na' => $request->op_site_na,
                    'machine_chck' => $request->machine_chck,
                    'machine_na' => $request->machine_na,
                    'monitor_on' => $request->monitor_on,
                    'monitor_na' => $request->monitor_na,
                    'allergy_remark' => $request->allergy_remark,
                    'pt_allergy' => $request->pt_allergy,
                    'diff_airway' => $request->diff_airway,
                    'diff_airway_na' => $request->diff_airway_na,
                    'gxm_gsh' => $request->gxm_gsh,
                    'gxm_gsh_na' => $request->gxm_gsh_na,
                    'iv_access' => $request->iv_access,
                    'apparatus_chck' => $request->apparatus_chck,
                    'ottable_chck' => $request->ottable_chck,
                    'board_surgeon' => $request->board_surgeon,
                    'intro_team' => $request->intro_team,
                    'bsi_confirmed_pt' => $request->bsi_confirmed_pt,
                    'antibio_prophy' => $request->antibio_prophy,
                    'display_img' => $request->display_img,
                    'brief_surgeon' => $request->brief_surgeon,
                    'anaesth_review' => $request->anaesth_review,
                    'scrubnrse_review' => $request->scrubnrse_review,
                    'pfusion_review' => $request->pfusion_review,
                    'surgeon_start' => $request->surgeon_start,
                    'periodic_upd' => $request->periodic_upd,
                    'shout_out' => $request->shout_out,
                    'pre_disclsre' => $request->pre_disclsre,
                    'final_procdre' => $request->final_procdre,
                    'final_count' => $request->final_count,
                    'specimen_lbl' => $request->specimen_lbl,
                    'specimen_lbl_na' => $request->specimen_lbl_na,
                    'incident_addr' => $request->incident_addr,
                    'postop_instr' => $request->postop_instr,
                    'relative_remark' => $request->relative_remark,
                    'inform_relative' => $request->inform_relative,
                    'upduser'  => session('username'),
                    'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
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
    
    public function get_table_oper_team(Request $request){
        
        $otteam_obj = DB::table('nursing.otteam')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$request->mrn)
                    ->where('episno','=',$request->episno);
                    
        $responce = new stdClass();
        
        if($otteam_obj->exists()){
            $otteam_obj = $otteam_obj->first();
            $responce->otteam = $otteam_obj;
        }
        
        return json_encode($responce);
    
    }
    
}