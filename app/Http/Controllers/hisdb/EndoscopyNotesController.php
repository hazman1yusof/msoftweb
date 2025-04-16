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

class EndoscopyNotesController extends defaultController
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function show(Request $request)
    {
        return view('hisdb.endoscopyNotes.endoscopyNotes');
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
            case 'save_table_endoscopyStomach':
                switch($request->oper){
                    case 'add':
                        return $this->add_endoscopyStomach($request);
                    case 'edit':
                        return $this->edit_endoscopyStomach($request);
                    default:
                        return 'error happen..';
                }
            
            case 'save_table_endoscopyIntestine':
                switch($request->oper){
                    case 'add':
                        return $this->add_endoscopyIntestine($request);
                    case 'edit':
                        return $this->edit_endoscopyIntestine($request);
                    default:
                        return 'error happen..';
                }
            
            case 'get_table_endoscopyStomach':
                return $this->get_table_endoscopyStomach($request);
            
            case 'get_table_endoscopyIntestine':
                return $this->get_table_endoscopyIntestine($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function add_endoscopyStomach(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.endoscopystomach')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'referredBy' => strtoupper($request->referredBy),
                        'endoscopist' => strtoupper($request->endoscopist),
                        'previousScopy' => $request->previousScopy,
                        'complaints' => $request->complaints,
                        'oesophagus' => $request->oesophagus,
                        'stomach' => $request->stomach,
                        'duodenum' => $request->duodenum,
                        'remarks' => $request->remarks,
                        'treatment' => $request->treatment,
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
    
    public function edit_endoscopyStomach(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.endoscopystomach')
                ->where('mrn','=',$request->mrn)
                ->where('episno','=',$request->episno)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'referredBy' => strtoupper($request->referredBy),
                    'endoscopist' => strtoupper($request->endoscopist),
                    'previousScopy' => $request->previousScopy,
                    'complaints' => $request->complaints,
                    'oesophagus' => $request->oesophagus,
                    'stomach' => $request->stomach,
                    'duodenum' => $request->duodenum,
                    'remarks' => $request->remarks,
                    'treatment' => $request->treatment,
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
    
    public function add_endoscopyIntestine(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.endoscopyintestine')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'indication' => $request->indication,
                        'perRectum' => $request->perRectum,
                        'otherIllness' => $request->otherIllness,
                        'HBsAGpositive' => $request->HBsAGpositive,
                        'HBsAGnegative' => $request->HBsAGnegative,
                        'HBsAGnotknow' => $request->HBsAGnotknow,
                        'refDoctor' => strtoupper($request->refDoctor),
                        'refDoctorDate' => $request->refDoctorDate,
                        'instruments' => $request->instruments,
                        'serialNo' => $request->serialNo,
                        'medication' => $request->medication,
                        'endosFindings' => $request->endosFindings,
                        'biopsy' => $request->biopsy,
                        'otherProcedure' => $request->otherProcedure,
                        'endosImpression' => $request->endosImpression,
                        'remarks' => $request->remarks,
                        'endoscopistName' => strtoupper($request->endoscopistName),
                        'endoscopistDate' => $request->endoscopistDate,
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
    
    public function edit_endoscopyIntestine(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.endoscopyintestine')
                ->where('mrn','=',$request->mrn)
                ->where('episno','=',$request->episno)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'indication' => $request->indication,
                    'perRectum' => $request->perRectum,
                    'otherIllness' => $request->otherIllness,
                    'HBsAGpositive' => $request->HBsAGpositive,
                    'HBsAGnegative' => $request->HBsAGnegative,
                    'HBsAGnotknow' => $request->HBsAGnotknow,
                    'refDoctor' => strtoupper($request->refDoctor),
                    'refDoctorDate' => $request->refDoctorDate,
                    'instruments' => $request->instruments,
                    'serialNo' => $request->serialNo,
                    'medication' => $request->medication,
                    'endosFindings' => $request->endosFindings,
                    'biopsy' => $request->biopsy,
                    'otherProcedure' => $request->otherProcedure,
                    'endosImpression' => $request->endosImpression,
                    'remarks' => $request->remarks,
                    'endoscopistName' => strtoupper($request->endoscopistName),
                    'endoscopistDate' => $request->endoscopistDate,
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
    
    public function get_table_endoscopyStomach(Request $request){
        
        $endoscopyStomach_obj = DB::table('nursing.endoscopystomach')
                                ->where('compcode','=',session('compcode'))
                                ->where('mrn','=',$request->mrn)
                                ->where('episno','=',$request->episno);
        
        $responce = new stdClass();
        
        if($endoscopyStomach_obj->exists()){
            $endoscopyStomach_obj = $endoscopyStomach_obj->first();
            $responce->endoscopyStomach = $endoscopyStomach_obj;
        }
        
        return json_encode($responce);
        
    }
    
    public function get_table_endoscopyIntestine(Request $request){
        
        $endoscopyintestine_obj = DB::table('nursing.endoscopyintestine')
                                ->where('compcode','=',session('compcode'))
                                ->where('mrn','=',$request->mrn)
                                ->where('episno','=',$request->episno);
        
        $responce = new stdClass();
        
        if($endoscopyintestine_obj->exists()){
            $endoscopyintestine_obj = $endoscopyintestine_obj->first();
            $responce->endoscopyintestine = $endoscopyintestine_obj;
        }
        
        return json_encode($responce);
        
    }
    
}