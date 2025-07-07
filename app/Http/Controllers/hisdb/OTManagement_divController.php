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

class OTManagement_divController extends defaultController
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function show(Request $request)
    {
        return view('hisdb.otmanagement.otmanagement_div');
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
            case 'save_table_otmgmt_div':
            
                switch($request->oper){
                    case 'add':
                        return $this->add($request);
                    case 'edit':
                        return $this->edit($request);
                    default:
                        return 'error happen..';
                }
                
            case 'get_table_otmanage':
                return $this->get_table_otmanage($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function add(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.otmanage')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_otmgmt_div,
                        'episno' => $request->episno_otmgmt_div,
                        'admdate' => $request->admdate,
                        'admtime' => $request->admtime,
                        'ward' => $request->ward,
                        'operdate' => $request->operdate,
                        'timestarted' => $request->timestarted,
                        'timeended' => $request->timeended,
                        'hoursutilized' => $request->hoursutilized,
                        // 'serialno' => $request->serialno,
                        'natureoper' => $request->natureoper,
                        'specimen' => $request->specimen,
                        'finding' => $request->finding,
                        'plan' => $request->plan,
                        'diagnosis' => $request->diagnosis,
                        'electiveemgc' => $request->electiveemgc,
                        'classification' => $request->classification,
                        'anaesthtype' => $request->anaesthtype,
                        'otno' => $request->otno,
                        'surgeon' => $request->surgeon,
                        'anaest' => $request->anaest,
                        'scrubnurse' => $request->scrubnurse,
                        'consultant' => $request->consultant,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur"),
                        'e_yes' => $request->e_yes,
                        'e_no' => $request->e_no,
                        'general' => $request->general,
                        'local' => $request->local,
                        'spinal' => $request->spinal,
                        'other' => $request->other,
                    ]);
            
            DB::table('hisdb.apptbook')
                ->where('mrn','=',$request->mrn_otmgmt_div)
                ->where('Type','=','OT')
                ->where('compcode','=',session('compcode'))
                ->update([
                    'apptdatefr'  => $request->operdate,
                    'apptdateto'  => $request->operdate,
                    'start'       => $request->operdate.' '.$request->timestarted,
                    'end'         => $request->operdate.' '.$request->timeended,
                    'surgery_date'=> $request->operdate,
                    // 'oper_status' => strtoupper($request->oper_status),
                    // 'procedure' => $request->procedure,
                    'diagnosis' => $request->diagnosis,
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur"),
                ]);

            DB::table('nursing.ottime')
                ->where('mrn','=',$request->mrn_otmgmt_div)
                ->where('episno','=', $request->episno_otmgmt_div)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'procedure' => $request->natureoper,
                    'diagnosis' => $request->diagnosis,
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur"),
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
            
            $otmanage = DB::table('nursing.otmanage')
                        ->where('mrn','=',$request->mrn_otmgmt_div)
                        ->where('episno','=',$request->episno_otmgmt_div)
                        ->where('compcode','=',session('compcode'));
            
            if(!$otmanage->exists()){
                DB::table('nursing.otmanage')
                        ->insert([
                            'compcode' => session('compcode'),
                            'mrn' => $request->mrn_otmgmt_div,
                            'episno' => $request->episno_otmgmt_div,
                            'admdate' => $request->admdate,
                            'admtime' => $request->admtime,
                            'ward' => $request->ward,
                            'operdate' => $request->operdate,
                            'timestarted' => $request->timestarted,
                            'timeended' => $request->timeended,
                            'hoursutilized' => $request->hoursutilized,
                            // 'serialno' => $request->serialno,
                            'natureoper' => $request->natureoper,
                            'specimen' => $request->specimen,
                            'finding' => $request->finding,
                            'plan' => $request->plan,
                            'diagnosis' => $request->diagnosis,
                            'electiveemgc' => $request->electiveemgc,
                            'classification' => $request->classification,
                            'anaesthtype' => $request->anaesthtype,
                            'otno' => $request->otno,
                            'surgeon' => $request->surgeon,
                            'anaest' => $request->anaest,
                            'scrubnurse' => $request->scrubnurse,
                            'consultant' => $request->consultant,
                            'adduser'  => session('username'),
                            'adddate'  => Carbon::now("Asia/Kuala_Lumpur"),
                            'e_yes' => $request->e_yes,
                            'e_no' => $request->e_no,
                            'general' => $request->general,
                            'local' => $request->local,
                            'spinal' => $request->spinal,
                            'other' => $request->other,
                        ]);
                
                DB::table('hisdb.apptbook')
                    ->where('mrn','=',$request->mrn_otmgmt_div)
                    ->where('Type','=','OT')
                    ->where('compcode','=',session('compcode'))
                    ->update([
                        'apptdatefr'  => $request->operdate,
                        'apptdateto'  => $request->operdate,
                        'start'       => $request->operdate.' '.$request->timestarted,
                        'end'         => $request->operdate.' '.$request->timeended,
                        'surgery_date'=> $request->operdate,
                        // 'oper_status' => strtoupper($request->oper_status),
                        // 'procedure' => $request->procedure,
                        'diagnosis' => $request->diagnosis,
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur"),
                    ]);

                DB::table('nursing.ottime')
                    ->where('mrn','=',$request->mrn_otmgmt_div)
                    ->where('episno','=', $request->episno_otmgmt_div)
                    ->where('compcode','=',session('compcode'))
                    ->update([
                        'procedure' => $request->natureoper,
                        'diagnosis' => $request->diagnosis,
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur"),
                    ]);

            }else{
                $otmanage
                    ->update([
                        'admdate' => $request->admdate,
                        'admtime' => $request->admtime,
                        'ward' => $request->ward,
                        'operdate' => $request->operdate,
                        'timestarted' => $request->timestarted,
                        'timeended' => $request->timeended,
                        'hoursutilized' => $request->hoursutilized,
                        // 'serialno' => $request->serialno,
                        'natureoper' => $request->natureoper,
                        'specimen' => $request->specimen,
                        'finding' => $request->finding,
                        'plan' => $request->plan,
                        'diagnosis' => $request->diagnosis,
                        'electiveemgc' => $request->electiveemgc,
                        'classification' => $request->classification,
                        'anaesthtype' => $request->anaesthtype,
                        'otno' => $request->otno,
                        'surgeon' => $request->surgeon,
                        'anaest' => $request->anaest,
                        'scrubnurse' => $request->scrubnurse,
                        'consultant' => $request->consultant,
                        'upduser'  => session('username'),
                        'upddate'  => Carbon::now("Asia/Kuala_Lumpur"),
                        'e_yes' => $request->e_yes,
                        'e_no' => $request->e_no,
                        'general' => $request->general,
                        'local' => $request->local,
                        'spinal' => $request->spinal,
                        'other' => $request->other,
                    ]);
                
                DB::table('hisdb.apptbook')
                    ->where('mrn','=',$request->mrn_otmgmt_div)
                    ->where('Type','=','OT')
                    ->where('compcode','=',session('compcode'))
                    ->update([
                        'apptdatefr'  => $request->operdate,
                        'apptdateto'  => $request->operdate,
                        'start'       => $request->operdate.' '.$request->timestarted,
                        'end'         => $request->operdate.' '.$request->timeended,
                        'surgery_date'=> $request->operdate,
                        // 'oper_status' => strtoupper($request->oper_status),
                        // 'procedure' => $request->procedure,
                        'diagnosis' => $request->diagnosis,
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur"),
                    ]);

                DB::table('nursing.ottime')
                    ->where('mrn','=',$request->mrn_otmgmt_div)
                    ->where('episno','=', $request->episno_otmgmt_div)
                    ->where('compcode','=',session('compcode'))
                    ->update([
                        'procedure' => $request->natureoper,
                        'diagnosis' => $request->diagnosis,
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur"),
                    ]);
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
    
    public function get_table_otmanage(Request $request){
        
        $otmanage_obj = DB::table('nursing.otmanage')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$request->mrn)
                    ->where('episno','=',$request->episno);
        
        $apptbook_obj = DB::table('hisdb.apptbook')
                    ->select('apptdatefr as operdate','start','end','oper_status','procedure','diagnosis')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$request->mrn);
                    // ->where('episno','=',$request->episno);
                    
        $episode_obj = DB::table('hisdb.episode')
                    ->select('reg_date as admdate', 'reg_time as admtime')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$request->mrn)
                    ->where('episno','=',$request->episno);
        
        $responce = new stdClass();
        
        if($otmanage_obj->exists()){
            $otmanage_obj = $otmanage_obj->first();
            $responce->otmanage = $otmanage_obj;
        }
        
        if($apptbook_obj->exists()){
            $apptbook_obj = $apptbook_obj->first();
            $responce->apptbook = $apptbook_obj;
        }
        
        if($episode_obj->exists()){
            $episode_obj = $episode_obj->first();
            $responce->episode = $episode_obj;
        }
        
        $start = date('H:i', strtotime($responce->apptbook->start));
        $responce->start = $start;
        
        $end = date('H:i', strtotime($responce->apptbook->end));
        $responce->end = $end;
        
        return json_encode($responce);
    
    }
    
}