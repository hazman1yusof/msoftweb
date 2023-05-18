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
    
    public function add(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.otpreop')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_preoperative,
                        'episno' => $request->episno_preoperative,
                        'pat_id' => $request->pat_id,
                        'use2iden' => $request->use2iden,
                        'pat_ward' => $request->pat_ward,
                        'pat_ot' => $request->pat_ot,
                        'pat_remark' => $request->pat_remark,
                        'cons_surgery' => $request->cons_surgery,
                        'cons_anaes' => $request->cons_anaes,
                        'cons_trans' => $request->cons_trans,
                        'cons_photo' => $request->cons_photo,
                        'check_form' => $request->check_form,
                        'check_pat' => $request->check_pat,
                        'check_list' => $request->check_list,
                        'cons_ward' => $request->cons_ward,
                        'cons_ot' => $request->cons_ot,
                        'cons_remark' => $request->cons_remark,
                        'check_side_left' => $request->check_side_left,
                        'check_side_right' => $request->check_side_right,
                        'check_side_na' => $request->check_side_na,
                        'check_side_ward' => $request->check_side_ward,
                        'check_side_ot' => $request->check_side_ot,
                        'check_side_remark' => $request->check_side_remark,
                        'side_op_mark' => $request->side_op_mark,
                        'side_op_na' => $request->side_op_na,
                        'side_op_ward' => $request->side_op_ward,
                        'side_op_ot' => $request->side_op_ot,
                        'side_op_remark' => $request->side_op_remark,
                        'lastmeal_date' => $request->lastmeal_date,
                        'lastmeal_time' => $request->lastmeal_time,
                        'lastmeal_ward' => $request->lastmeal_ward,
                        'lastmeal_ot' => $request->lastmeal_ot,
                        'lastmeal_remark' => $request->lastmeal_remark,
                        'check_item_na' => $request->check_item_na,
                        'check_item_ward' => $request->check_item_ward,
                        'check_item_ot' => $request->check_item_ot,
                        'check_item_remark' => $request->check_item_remark,
                        'allergies' => $request->allergies,
                        'allergies_ward' => $request->allergies_ward,
                        'allergies_ot' => $request->allergies_ot,
                        'allergies_remark' => $request->allergies_remark,
                        'implant_avlblt' => $request->implant_avlblt,
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
                        'bp_sys1' => $request->bp_sys1,
                        'bp_dias' => $request->bp_dias,
                        'pulse' => $request->pulse,
                        'temperature' => $request->temperature,
                        'vs_ward' => $request->vs_ward,
                        'vs_ot' => $request->vs_ot,
                        'vs_remark' => $request->vs_remark,
                        'others_na' => $request->others_na,
                        'others_ward' => $request->others_ward,
                        'others_ot' => $request->others_ot,
                        'others_remark' => $request->others_remark,
                        'imprtnt_issues' => $request->imprtnt_issues,
                        'info_temperature' => $request->info_temperature,
                        'info_humidity' => $request->info_humidity,
                        'info_otroom' => $request->info_otroom,
                        'info_anaesthetist' => $request->info_anaesthetist,
                        'info_surgeon' => $request->info_surgeon,
                        'info_asstsurgeon' => $request->info_asstsurgeon,
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
            
            DB::table('nursing.otpreop')
                ->where('mrn','=',$request->mrn_preoperative)
                ->where('episno','=',$request->episno_preoperative)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'pat_id' => $request->pat_id,
                    'use2iden' => $request->use2iden,
                    'pat_ward' => $request->pat_ward,
                    'pat_ot' => $request->pat_ot,
                    'pat_remark' => $request->pat_remark,
                    'cons_surgery' => $request->cons_surgery,
                    'cons_anaes' => $request->cons_anaes,
                    'cons_trans' => $request->cons_trans,
                    'cons_photo' => $request->cons_photo,
                    'check_form' => $request->check_form,
                    'check_pat' => $request->check_pat,
                    'check_list' => $request->check_list,
                    'cons_ward' => $request->cons_ward,
                    'cons_ot' => $request->cons_ot,
                    'cons_remark' => $request->cons_remark,
                    'check_side_left' => $request->check_side_left,
                    'check_side_right' => $request->check_side_right,
                    'check_side_na' => $request->check_side_na,
                    'check_side_ward' => $request->check_side_ward,
                    'check_side_ot' => $request->check_side_ot,
                    'check_side_remark' => $request->check_side_remark,
                    'side_op_mark' => $request->side_op_mark,
                    'side_op_na' => $request->side_op_na,
                    'side_op_ward' => $request->side_op_ward,
                    'side_op_ot' => $request->side_op_ot,
                    'side_op_remark' => $request->side_op_remark,
                    'lastmeal_date' => $request->lastmeal_date,
                    'lastmeal_time' => $request->lastmeal_time,
                    'lastmeal_ward' => $request->lastmeal_ward,
                    'lastmeal_ot' => $request->lastmeal_ot,
                    'lastmeal_remark' => $request->lastmeal_remark,
                    'check_item_na' => $request->check_item_na,
                    'check_item_ward' => $request->check_item_ward,
                    'check_item_ot' => $request->check_item_ot,
                    'check_item_remark' => $request->check_item_remark,
                    'allergies' => $request->allergies,
                    'allergies_ward' => $request->allergies_ward,
                    'allergies_ot' => $request->allergies_ot,
                    'allergies_remark' => $request->allergies_remark,
                    'implant_avlblt' => $request->implant_avlblt,
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
                    'bp_sys1' => $request->bp_sys1,
                    'bp_dias' => $request->bp_dias,
                    'pulse' => $request->pulse,
                    'temperature' => $request->temperature,
                    'vs_ward' => $request->vs_ward,
                    'vs_ot' => $request->vs_ot,
                    'vs_remark' => $request->vs_remark,
                    'others_na' => $request->others_na,
                    'others_ward' => $request->others_ward,
                    'others_ot' => $request->others_ot,
                    'others_remark' => $request->others_remark,
                    'imprtnt_issues' => $request->imprtnt_issues,
                    'info_temperature' => $request->info_temperature,
                    'info_humidity' => $request->info_humidity,
                    'info_otroom' => $request->info_otroom,
                    'info_anaesthetist' => $request->info_anaesthetist,
                    'info_surgeon' => $request->info_surgeon,
                    'info_asstsurgeon' => $request->info_asstsurgeon,
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
    
    public function get_table_preoperative(Request $request){
        
        $preop_obj = DB::table('nursing.otpreop as ot')
                    ->select([
                        'ot.idno','ot.compcode','ot.mrn','ot.episno','ot.pat_id','ot.use2iden','ot.pat_ward','ot.pat_ot','ot.pat_remark','ot.cons_surgery',
                        'ot.cons_anaes','ot.cons_trans','ot.cons_photo','ot.check_form','ot.check_pat','ot.check_list','ot.cons_ward','ot.cons_ot','ot.cons_remark',
                        'ot.check_side_left','ot.check_side_right','ot.check_side_na','ot.check_side_ward','ot.check_side_ot','ot.check_side_remark','ot.side_op_mark',
                        'ot.side_op_na','ot.side_op_ward','ot.side_op_ot','ot.side_op_remark','ot.lastmeal_date','ot.lastmeal_time','ot.lastmeal_ward','ot.lastmeal_ot',
                        'ot.lastmeal_remark','ot.check_item_na','ot.check_item_ward','ot.check_item_ot','ot.check_item_remark','ot.allergies','ot.allergies_ward',
                        'ot.allergies_ot','ot.allergies_remark','ot.implant_avlblt','ot.implant_ward','ot.implant_ot','ot.implant_remark','ot.premed_na',
                        'ot.premed_ward','ot.premed_ot','ot.premed_remark','ot.blood_na','ot.blood_ward','ot.blood_ot','ot.blood_remark','ot.casenotes_na',
                        'ot.casenotes_ward','ot.casenotes_ot','ot.casenotes_remark','ot.oldnotes_na','ot.oldnotes_ward','ot.oldnotes_ot','ot.oldnotes_remark',
                        'ot.imaging_na','ot.imaging_ward','ot.imaging_ot','ot.imaging_remark','ot.bp_sys1','ot.bp_dias','ot.pulse','ot.temperature','ot.vs_ward',
                        'ot.vs_ot','ot.vs_remark','ot.others_na','ot.others_ward','ot.others_ot','ot.others_remark','ot.imprtnt_issues','ot.info_temperature',
                        'ot.info_humidity','ot.info_otroom','ot.info_anaesthetist','ot.info_surgeon','ot.info_asstsurgeon','ot.adduser','ot.adddate','ot.upduser',
                        'ot.upddate','d1.doctorname as desc_anaesthetist','d2.doctorname as desc_surgeon','d3.doctorname as desc_asstsurgeon'
                    ])
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
        
        $responce = new stdClass();
        
        if($preop_obj->exists()){
            $preop_obj = $preop_obj->first();
            $responce->preop = $preop_obj;
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