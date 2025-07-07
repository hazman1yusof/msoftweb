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

class OTTimeController extends defaultController
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function show(Request $request)
    {
        return view('hisdb.ottime.ottime');
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
            case 'save_table_ottime':
                switch($request->oper){
                    case 'add':
                        return $this->add($request);
                    case 'edit':
                        return $this->edit($request);
                    default:
                        return 'error happen..';
                }
            
            case 'get_table_ottime':
                return $this->get_table_ottime($request);
            
            default:
                return 'error happen..';
        }
    }

    public function add(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.ottime')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_ottime,
                        'episno' => $request->episno_ottime,
                        'ottimeDate' => $request->ottimeDate,
                        'case' => $request->case,
                        'dept' => $request->dept,
                        'vendor' => $request->vendor,
                        'arrive_time' => $request->arrive_time,
                        'arrive_date' => $request->arrive_date,
                        'in_time' => $request->in_time,
                        'in_date' => $request->in_date,
                        'start_time' => $request->start_time,
                        'start_date' => $request->start_date,
                        'end_time' => $request->end_time,
                        'end_date' => $request->end_date,
                        'recovery_time' => $request->recovery_time,
                        'recovery_date' => $request->recovery_date,
                        'depart_time' => $request->depart_time,
                        'depart_date' => $request->depart_date,
                        'type_anaesth' => $request->type_anaesth,
                        'anaesth' => $request->anaesth,
                        'diagnosis' => $request->diagnosis,
                        'procedure' => $request->procedure,
                        'scrubperson' => $request->scrubperson,
                        'gaNurse' => $request->gaNurse,
                        'circulateperson' => $request->circulateperson,
                        'remarks' => $request->remarks,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur"),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur"),
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

    public function edit(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.ottime')
                ->where('mrn','=',$request->mrn_ottime)
                ->where('episno','=',$request->episno_ottime)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'ottimeDate' => $request->ottimeDate,
                    'case' => $request->case,
                    'dept' => $request->dept,
                    'vendor' => $request->vendor,
                    'arrive_time' => $request->arrive_time,
                    'arrive_date' => $request->arrive_date,
                    'in_time' => $request->in_time,
                    'in_date' => $request->in_date,
                    'start_time' => $request->start_time,
                    'start_date' => $request->start_date,
                    'end_time' => $request->end_time,
                    'end_date' => $request->end_date,
                    'recovery_time' => $request->recovery_time,
                    'recovery_date' => $request->recovery_date,
                    'depart_time' => $request->depart_time,
                    'depart_date' => $request->depart_date,
                    'type_anaesth' => $request->type_anaesth,
                    'anaesth' => $request->anaesth,
                    'diagnosis' => $request->diagnosis,
                    'procedure' => $request->procedure,
                    'scrubperson' => $request->scrubperson,
                    'gaNurse' => $request->gaNurse,
                    'circulateperson' => $request->circulateperson,
                    'remarks' => $request->remarks,
                    'upduser'  => session('username'),
                    'upddate'  => Carbon::now("Asia/Kuala_Lumpur"),
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur"),
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
    
    public function add_lama(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.ottime')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_ottime,
                        'episno' => $request->episno_ottime,
                        'pcrResult' => $request->pcrResult,
                        'pcrDate' => $request->pcrDate,
                        'callPtTime' => $request->callPtTime,
                        'callPtDate' => $request->callPtDate,
                        'ppkWardTime' => $request->ppkWardTime,
                        'ppkWardDate' => $request->ppkWardDate,
                        'receptionTime' => $request->receptionTime,
                        'receptionDate' => $request->receptionDate,
                        'patientOTtime' => $request->patientOTtime,
                        'patientOTdate' => $request->patientOTdate,
                        'incisionstart' => $request->incisionstart,
                        'incisionend' => $request->incisionend,
                        'ptOutTime' => $request->ptOutTime,
                        'ptOutDate' => $request->ptOutDate,
                        'wardCallTime' => $request->wardCallTime,
                        'wardCallDate' => $request->wardCallDate,
                        'ptWardTime' => $request->ptWardTime,
                        'ptWardDate' => $request->ptWardDate,
                        'scrubperson' => $request->scrubperson,
                        'gaNurse' => $request->gaNurse,
                        'circulateperson' => $request->circulateperson,
                        'hlthcareAsst' => $request->hlthcareAsst,
                        'otCleanedBy' => $request->otCleanedBy,
                        'remarks' => $request->remarks,
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
            
            DB::table('nursing.ottime')
                ->where('mrn','=',$request->mrn_ottime)
                ->where('episno','=',$request->episno_ottime)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'pcrResult' => $request->pcrResult,
                    'pcrDate' => $request->pcrDate,
                    'callPtTime' => $request->callPtTime,
                    'callPtDate' => $request->callPtDate,
                    'ppkWardTime' => $request->ppkWardTime,
                    'ppkWardDate' => $request->ppkWardDate,
                    'receptionTime' => $request->receptionTime,
                    'receptionDate' => $request->receptionDate,
                    'patientOTtime' => $request->patientOTtime,
                    'patientOTdate' => $request->patientOTdate,
                    'incisionstart' => $request->incisionstart,
                    'incisionend' => $request->incisionend,
                    'ptOutTime' => $request->ptOutTime,
                    'ptOutDate' => $request->ptOutDate,
                    'wardCallTime' => $request->wardCallTime,
                    'wardCallDate' => $request->wardCallDate,
                    'ptWardTime' => $request->ptWardTime,
                    'ptWardDate' => $request->ptWardDate,
                    'scrubperson' => $request->scrubperson,
                    'gaNurse' => $request->gaNurse,
                    'circulateperson' => $request->circulateperson,
                    'hlthcareAsst' => $request->hlthcareAsst,
                    'otCleanedBy' => $request->otCleanedBy,
                    'remarks' => $request->remarks,
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
    
    public function get_table_ottime(Request $request){
        
        $ottime_obj = DB::table('nursing.ottime')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$request->mrn)
                    ->where('episno','=',$request->episno);
        
        $responce = new stdClass();
        
        if($ottime_obj->exists()){
            $ottime_obj = $ottime_obj->first();
            $responce->ottime = $ottime_obj;
        }
        
        return json_encode($responce);
        
    }
    
}