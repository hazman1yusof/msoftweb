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
                        'serialno' => $request->serialno,
                        'recstatus' => $request->recstatus,
                        'natureoper' => $request->natureoper,
                        'specimen' => $request->specimen,
                        'remarks' => $request->remarks,
                        'procedure' => $request->procedure,
                        'diagnosis' => $request->diagnosis,
                        'electiveemgc' => $request->electiveemgc,
                        'classification' => $request->classification,
                        'anaesthtype' => $request->anaesthtype,
                        'otno' => $request->otno,
                        'firstscrubnrs' => $request->firstscrubnrs,
                        'secondscrubnrs' => $request->secondscrubnrs,
                        'gaassistant' => $request->gaassistant,
                        'circulator' => $request->circulator,
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
            
            DB::table('nursing.otmanage')
                ->where('mrn','=',$request->mrn_otmgmt_div)
                ->where('episno','=',$request->episno_otmgmt_div)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'admdate' => $request->admdate,
                    'admtime' => $request->admtime,
                    'ward' => $request->ward,
                    'operdate' => $request->operdate,
                    'timestarted' => $request->timestarted,
                    'timeended' => $request->timeended,
                    'hoursutilized' => $request->hoursutilized,
                    'serialno' => $request->serialno,
                    'recstatus' => $request->recstatus,
                    'natureoper' => $request->natureoper,
                    'specimen' => $request->specimen,
                    'remarks' => $request->remarks,
                    'procedure' => $request->procedure,
                    'diagnosis' => $request->diagnosis,
                    'electiveemgc' => $request->electiveemgc,
                    'classification' => $request->classification,
                    'anaesthtype' => $request->anaesthtype,
                    'otno' => $request->otno,
                    'firstscrubnrs' => $request->firstscrubnrs,
                    'secondscrubnrs' => $request->secondscrubnrs,
                    'gaassistant' => $request->gaassistant,
                    'circulator' => $request->circulator,
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
    
    public function get_table_otmanage(Request $request){
        
        $otmanage_obj = DB::table('nursing.otmanage')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$request->mrn)
                    ->where('episno','=',$request->episno);
                    
        $responce = new stdClass();
        
        if($otmanage_obj->exists()){
            $otmanage_obj = $otmanage_obj->first();
            $responce->otmanage = $otmanage_obj;
        }
        
        return json_encode($responce);
    
    }
    
}