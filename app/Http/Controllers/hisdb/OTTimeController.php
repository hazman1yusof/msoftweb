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
                        'pcr_result' => $request->pcr_result,
                        'pcr_date' => $request->pcr_date,
                        'callpt_time' => $request->callpt_time,
                        'callpt_date' => $request->callpt_date,
                        'ppkward_time' => $request->ppkward_time,
                        'ppkward_date' => $request->ppkward_date,
                        'reception_time' => $request->reception_time,
                        'reception_date' => $request->reception_date,
                        'patientOT_time' => $request->patientOT_time,
                        'patientOT_date' => $request->patientOT_date,
                        'incisionstart' => $request->incisionstart,
                        'incisionend' => $request->incisionend,
                        'ptOut_time' => $request->ptOut_time,
                        'ptOut_date' => $request->ptOut_date,
                        'wardCall_time' => $request->wardCall_time,
                        'wardCall_date' => $request->wardCall_date,
                        'ptWard_time' => $request->ptWard_time,
                        'ptWard_date' => $request->ptWard_date,
                        'scrubperson' => $request->scrubperson,
                        'ga_nurse' => $request->ga_nurse,
                        'circltg_person' => $request->circltg_person,
                        'hlthcare_asst' => $request->hlthcare_asst,
                        'otCleanedBy' => $request->otCleanedBy,
                        'remarks' => $request->remarks,
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
            
            DB::table('nursing.ottime')
                ->where('mrn','=',$request->mrn_ottime)
                ->where('episno','=',$request->episno_ottime)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'pcr_result' => $request->pcr_result,
                    'pcr_date' => $request->pcr_date,
                    'callpt_time' => $request->callpt_time,
                    'callpt_date' => $request->callpt_date,
                    'ppkward_time' => $request->ppkward_time,
                    'ppkward_date' => $request->ppkward_date,
                    'reception_time' => $request->reception_time,
                    'reception_date' => $request->reception_date,
                    'patientOT_time' => $request->patientOT_time,
                    'patientOT_date' => $request->patientOT_date,
                    'incisionstart' => $request->incisionstart,
                    'incisionend' => $request->incisionend,
                    'ptOut_time' => $request->ptOut_time,
                    'ptOut_date' => $request->ptOut_date,
                    'wardCall_time' => $request->wardCall_time,
                    'wardCall_date' => $request->wardCall_date,
                    'ptWard_time' => $request->ptWard_time,
                    'ptWard_date' => $request->ptWard_date,
                    'scrubperson' => $request->scrubperson,
                    'ga_nurse' => $request->ga_nurse,
                    'circltg_person' => $request->circltg_person,
                    'hlthcare_asst' => $request->hlthcare_asst,
                    'otCleanedBy' => $request->otCleanedBy,
                    'remarks' => $request->remarks,
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