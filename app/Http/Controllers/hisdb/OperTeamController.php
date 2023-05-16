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
        
        // $preop_obj = DB::table('nursing.preop')
        //             ->where('compcode','=',session('compcode'))
        //             ->where('mrn','=',$request->mrn)
        //             ->where('episno','=',$request->episno);
                    
        // $responce = new stdClass();
        
        // if($preop_obj->exists()){
        //     $preop_obj = $preop_obj->first();
        //     $responce->preop = $preop_obj;
        // }
        
        // return json_encode($responce);
    
    }
    
}