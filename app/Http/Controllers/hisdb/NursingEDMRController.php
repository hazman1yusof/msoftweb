<?php

namespace App\Http\Controllers\hisdb;

use Illuminate\Http\Request;
use stdClass;
use DB;
use Carbon\Carbon;
use App\Http\Controllers\defaultController;

class NursingEDMRController extends defaultController
{
    
    var $table;
    var $duplicateCode;
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function show(Request $request)
    {
        return view('hisdb.nursingED_MR.nursingED_MR');
    }
    
    public function form(Request $request)
    {
        DB::enableQueryLog();
        switch($request->action){
            case 'get_table_triageED':
                return $this->get_table_triageED($request);
            default:
                return 'error happen..';
        }
    }
    
    public function get_table_triageED(Request $request){
        
        // $location = $this->get_location($request->mrn_tiED,$request->episno_tiED);
        // dd($location);
        
        $triage_obj = DB::table('nursing.nursassessment')
                    ->where('compcode','=',session('compcode'))
                    ->where('location','=','ED')
                    ->where('mrn','=',$request->mrn)
                    ->where('episno','=',$request->episno);
        
        $triage_gen_obj = DB::table('nursing.nursassessgen')
                        ->where('compcode','=',session('compcode'))
                        ->where('location','=','ED')
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno);
        
        // $triage_exm_obj = DB::table('nursing.nurassesexam')
        //                 ->where('compcode','=',session('compcode'))
        //                 ->where('mrn','=',$request->mrn)
        //                 ->where('episno','=',$request->episno);
        
        $triage_regdate_obj = DB::table('hisdb.episode')
                            ->select('reg_date')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno);
        
        $triage_nurshistory_obj = DB::table('nursing.nurshistory')
                                ->where('compcode','=',session('compcode'))
                                ->where('mrn','=',$request->mrn);
        
        $responce = new stdClass();
        
        if($triage_obj->exists()){
            $triage_obj = $triage_obj->first();
            $responce->triage = $triage_obj;
        }
        
        if($triage_gen_obj->exists()){
            $triage_gen_obj = $triage_gen_obj->first();
            $responce->triage_gen = $triage_gen_obj;
        }
        
        // if($triage_exm_obj->exists()){
        //     $triage_exm_obj = $triage_exm_obj->get()->toArray();
        //     $responce->triage_exm = $triage_exm_obj;
        // }
        
        if($triage_regdate_obj->exists()){
            $triage_regdate_obj = $triage_regdate_obj->first();
            $responce->triage_regdate = $triage_regdate_obj;
        }
        
        if($triage_nurshistory_obj->exists()){
            $triage_nurshistory_obj = $triage_nurshistory_obj->first();
            $responce->triage_nurshistory = $triage_nurshistory_obj;
        }
        
        return json_encode($responce);
        
    }
    
    public function get_location($mrn,$episno){
        
        $epistype = DB::table('hisdb.episode')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$mrn)
                    ->where('episno','=',$episno);
        
        if($epistype->exists()){
            $epistype = $epistype->first();
            $epistype = $epistype->epistycode;
        }
        
        if($epistype == 'IP' || $epistype == 'OP' ){
            $location = 'WARD';
        }else{
            $location = 'TRIAGE';
        }
        
        return $location;
        
    }
    
}