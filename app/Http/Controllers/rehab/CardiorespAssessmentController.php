<?php

namespace App\Http\Controllers\rehab;

use Illuminate\Http\Request;
use stdClass;
use App\User;
use DB;
use Carbon\Carbon;
use Auth;
use Session;
use App\Http\Controllers\defaultController;

class CardiorespAssessmentController extends defaultController
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function show(Request $request)
    {
        return view('rehab.cardiorespAssessment');
    }
    
    public function form(Request $request)
    {
        DB::enableQueryLog();
        switch($request->action){
            case 'save_table_cardiorespAssessment':
                switch($request->oper){
                    case 'add':
                        return $this->add_cardiorespAssessment($request);
                    case 'edit':
                        return $this->edit_cardiorespAssessment($request);
                    default:
                        return 'error happen..';
                }
            
            case 'get_table_cardiorespAssessment':
                return $this->get_table_cardiorespAssessment($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function table(Request $request)
    {
        switch($request->action){
            case 'get_datetime_cardiorespAssessment':
                return $this->get_datetime_cardiorespAssessment($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function add_cardiorespAssessment(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $cardiorespassessment = DB::table('hisdb.phy_cardiorespassessment')
                                    ->where('compcode','=',session('compcode'))
                                    ->where('mrn','=',$request->mrn)
                                    ->where('episno','=',$request->episno)
                                    ->where('entereddate','=',$request->entereddate);
            
            if($cardiorespassessment->exists()){
                // throw new \Exception('Date already exist.', 500);
                return response('Date already exist.');
            }
            
            DB::table('hisdb.phy_cardiorespassessment')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn,
                    'episno' => $request->episno,
                    'entereddate' => $request->entereddate,
                    'subjectiveAssessmt' => $request->subjectiveAssessmt,
                    'objectiveAssessmt' => $request->objectiveAssessmt,
                    'analysis' => $request->analysis,
                    'intervention' => $request->intervention,
                    'homeEducation' => $request->homeEducation,
                    'evaluation' => $request->evaluation,
                    'review' => $request->review,
                    'additionalNotes' => $request->additionalNotes,
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
    
    public function edit_cardiorespAssessment(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $cardiorespassessment = DB::table('hisdb.phy_cardiorespassessment')
                                    ->where('compcode','=',session('compcode'))
                                    ->where('mrn','=',$request->mrn)
                                    ->where('episno','=',$request->episno)
                                    ->where('entereddate','=',$request->entereddate);
            
            if(!empty($request->idno_cardiorespAssessment)){
                if($cardiorespassessment->exists()){
                    if($cardiorespassessment->first()->idno != $request->idno_cardiorespAssessment){
                        // throw new \Exception('Date already exist.', 500);
                        return response('Date already exist.');
                    }
                }
                
                DB::table('hisdb.phy_cardiorespassessment')
                    ->where('idno','=',$request->idno_cardiorespAssessment)
                    // ->where('mrn','=',$request->mrn)
                    // ->where('episno','=',$request->episno)
                    // ->where('compcode','=',session('compcode'))
                    ->update([
                        'entereddate' => $request->entereddate,
                        'subjectiveAssessmt' => $request->subjectiveAssessmt,
                        'objectiveAssessmt' => $request->objectiveAssessmt,
                        'analysis' => $request->analysis,
                        'intervention' => $request->intervention,
                        'homeEducation' => $request->homeEducation,
                        'evaluation' => $request->evaluation,
                        'review' => $request->review,
                        'additionalNotes' => $request->additionalNotes,
                        'upduser'  => session('username'),
                        'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
            }else{
                if($cardiorespassessment->exists()){
                    // throw new \Exception('Date already exist.', 500);
                    return response('Date already exist.');
                }
                
                DB::table('hisdb.phy_cardiorespassessment')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'entereddate' => $request->entereddate,
                        'subjectiveAssessmt' => $request->subjectiveAssessmt,
                        'objectiveAssessmt' => $request->objectiveAssessmt,
                        'analysis' => $request->analysis,
                        'intervention' => $request->intervention,
                        'homeEducation' => $request->homeEducation,
                        'evaluation' => $request->evaluation,
                        'review' => $request->review,
                        'additionalNotes' => $request->additionalNotes,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
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
    
    public function get_table_cardiorespAssessment(Request $request){
        
        $cardiorespassessment_obj = DB::table('hisdb.phy_cardiorespassessment')
                                    ->where('compcode','=',session('compcode'))
                                    ->where('idno','=',$request->idno);
                                    // ->where('mrn','=',$request->mrn)
                                    // ->where('episno','=',$request->episno);
        
        $responce = new stdClass();
        
        if($cardiorespassessment_obj->exists()){
            $cardiorespassessment_obj = $cardiorespassessment_obj->first();
            $responce->cardiorespassessment = $cardiorespassessment_obj;
        }
        
        return json_encode($responce);
        
    }
    
    public function get_datetime_cardiorespAssessment(Request $request){
        
        $responce = new stdClass();
        
        $cardiorespassessment_obj = DB::table('hisdb.phy_cardiorespassessment')
                                    ->where('compcode','=',session('compcode'))
                                    ->where('mrn','=',$request->mrn)
                                    ->where('episno','=',$request->episno);
        
        if($cardiorespassessment_obj->exists()){
            $cardiorespassessment_obj = $cardiorespassessment_obj->get();
            
            $data = [];
            
            foreach($cardiorespassessment_obj as $key => $value){
                $date['idno'] = $value->idno;
                $date['mrn'] = $value->mrn;
                $date['episno'] = $value->episno;
                if(!empty($value->entereddate)){
                    $date['entereddate'] =  Carbon::createFromFormat('Y-m-d', $value->entereddate)->format('d-m-Y');
                }else{
                    $date['entereddate'] =  '-';
                }
                $date['dt'] = $value->entereddate; // for sorting
                $date['adduser'] = $value->adduser;
                
                array_push($data,$date);
            }
            
            $responce->data = $data;
        }else{
            $responce->data = [];
        }
        
        return json_encode($responce);
        
    }
    
}