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

class SixMinWalkingController extends defaultController
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function show(Request $request)
    {
        return view('rehab.sixMinWalking');
    }
    
    public function form(Request $request)
    {
        DB::enableQueryLog();
        switch($request->action){
            case 'save_table_sixMinWalking':
                switch($request->oper){
                    case 'add':
                        return $this->add_sixMinWalking($request);
                    case 'edit':
                        return $this->edit_sixMinWalking($request);
                    default:
                        return 'error happen..';
                }
            
            case 'get_table_sixMinWalking':
                return $this->get_table_sixMinWalking($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function table(Request $request)
    {
        switch($request->action){
            case 'get_datetime_sixMinWalking':
                return $this->get_datetime_sixMinWalking($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function add_sixMinWalking(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $sixminwalk = DB::table('hisdb.phy_sixminwalk')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno)
                        ->where('entereddate','=',$request->entereddate);
            
            if($sixminwalk->exists()){
                // throw new \Exception('Date already exist.', 500);
                return response('Date already exist.');
            }
            
            DB::table('hisdb.phy_sixminwalk')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn,
                    'episno' => $request->episno,
                    'lapCounter1' => $request->lapCounter1,
                    'lapCounter2' => $request->lapCounter2,
                    'lapCounter3' => $request->lapCounter3,
                    'lapCounter4' => $request->lapCounter4,
                    'lapCounter5' => $request->lapCounter5,
                    'lapCounter6' => $request->lapCounter6,
                    'lapCounter7' => $request->lapCounter7,
                    'lapCounter8' => $request->lapCounter8,
                    'lapCounter9' => $request->lapCounter9,
                    'lapCounter10' => $request->lapCounter10,
                    'lapCounter11' => $request->lapCounter11,
                    'lapCounter12' => $request->lapCounter12,
                    'lapCounter13' => $request->lapCounter13,
                    'lapCounter14' => $request->lapCounter14,
                    'lapCounter15' => $request->lapCounter15,
                    // 'patName' => $request->patName,
                    'walk' => $request->walk,
                    'techID' => $request->techID,
                    'entereddate' => $request->entereddate,
                    // 'gender' => $request->gender,
                    // 'age' => $request->age,
                    // 'race' => $request->race,
                    'heightFT' => $request->heightFT,
                    'heightIN' => $request->heightIN,
                    'heightMETERS' => $request->heightMETERS,
                    'weightLBS' => $request->weightLBS,
                    'weightKG' => $request->weightKG,
                    'bpsys1' => $request->bpsys1,
                    'bpdias2' => $request->bpdias2,
                    'medsDose' => $request->medsDose,
                    'medsTime' => $request->medsTime,
                    'suppOxygen' => $request->suppOxygen,
                    'oxygenFlow' => $request->oxygenFlow,
                    'oxygenType' => $request->oxygenType,
                    'baselineTime' => $request->baselineTime,
                    'endTestTime' => $request->endTestTime,
                    'baselineHR' => $request->baselineHR,
                    'endTestHR' => $request->endTestHR,
                    'baselineDyspnea' => $request->baselineDyspnea,
                    'endTestDyspnea' => $request->endTestDyspnea,
                    'baselineFatigue' => $request->baselineFatigue,
                    'endTestFatigue' => $request->endTestFatigue,
                    'baselineSpO2' => $request->baselineSpO2,
                    'endTestSpO2' => $request->endTestSpO2,
                    'stopPaused' => $request->stopPaused,
                    'reason' => $request->reason,
                    'othSymptoms' => $request->othSymptoms,
                    'lapsNo' => $request->lapsNo,
                    'partialLaps' => $request->partialLaps,
                    'lapsTot' => $request->lapsTot,
                    'totDistance' => $request->totDistance,
                    'perfDistance' => $request->perfDistance,
                    'percentPredicted' => $request->percentPredicted,
                    'comments' => $request->comments,
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
    
    public function edit_sixMinWalking(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $sixminwalk = DB::table('hisdb.phy_sixminwalk')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno)
                        ->where('entereddate','=',$request->entereddate);
            
            if(!empty($request->idno_sixMinWalking)){
                if($sixminwalk->exists()){
                    if($sixminwalk->first()->idno != $request->idno_sixMinWalking){
                        // throw new \Exception('Date already exist.', 500);
                        return response('Date already exist.');
                    }
                }
                
                DB::table('hisdb.phy_sixminwalk')
                    ->where('idno','=',$request->idno_sixMinWalking)
                    // ->where('mrn','=',$request->mrn)
                    // ->where('episno','=',$request->episno)
                    // ->where('compcode','=',session('compcode'))
                    ->update([
                        'lapCounter1' => $request->lapCounter1,
                        'lapCounter2' => $request->lapCounter2,
                        'lapCounter3' => $request->lapCounter3,
                        'lapCounter4' => $request->lapCounter4,
                        'lapCounter5' => $request->lapCounter5,
                        'lapCounter6' => $request->lapCounter6,
                        'lapCounter7' => $request->lapCounter7,
                        'lapCounter8' => $request->lapCounter8,
                        'lapCounter9' => $request->lapCounter9,
                        'lapCounter10' => $request->lapCounter10,
                        'lapCounter11' => $request->lapCounter11,
                        'lapCounter12' => $request->lapCounter12,
                        'lapCounter13' => $request->lapCounter13,
                        'lapCounter14' => $request->lapCounter14,
                        'lapCounter15' => $request->lapCounter15,
                        // 'patName' => $request->patName,
                        'walk' => $request->walk,
                        'techID' => $request->techID,
                        'entereddate' => $request->entereddate,
                        // 'gender' => $request->gender,
                        // 'age' => $request->age,
                        // 'race' => $request->race,
                        'heightFT' => $request->heightFT,
                        'heightIN' => $request->heightIN,
                        'heightMETERS' => $request->heightMETERS,
                        'weightLBS' => $request->weightLBS,
                        'weightKG' => $request->weightKG,
                        'bpsys1' => $request->bpsys1,
                        'bpdias2' => $request->bpdias2,
                        'medsDose' => $request->medsDose,
                        'medsTime' => $request->medsTime,
                        'suppOxygen' => $request->suppOxygen,
                        'oxygenFlow' => $request->oxygenFlow,
                        'oxygenType' => $request->oxygenType,
                        'baselineTime' => $request->baselineTime,
                        'endTestTime' => $request->endTestTime,
                        'baselineHR' => $request->baselineHR,
                        'endTestHR' => $request->endTestHR,
                        'baselineDyspnea' => $request->baselineDyspnea,
                        'endTestDyspnea' => $request->endTestDyspnea,
                        'baselineFatigue' => $request->baselineFatigue,
                        'endTestFatigue' => $request->endTestFatigue,
                        'baselineSpO2' => $request->baselineSpO2,
                        'endTestSpO2' => $request->endTestSpO2,
                        'stopPaused' => $request->stopPaused,
                        'reason' => $request->reason,
                        'othSymptoms' => $request->othSymptoms,
                        'lapsNo' => $request->lapsNo,
                        'partialLaps' => $request->partialLaps,
                        'lapsTot' => $request->lapsTot,
                        'totDistance' => $request->totDistance,
                        'perfDistance' => $request->perfDistance,
                        'percentPredicted' => $request->percentPredicted,
                        'comments' => $request->comments,
                        'upduser'  => session('username'),
                        'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
            }else{
                if($sixminwalk->exists()){
                    // throw new \Exception('Date already exist.', 500);
                    return response('Date already exist.');
                }
                
                DB::table('hisdb.phy_sixminwalk')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'lapCounter1' => $request->lapCounter1,
                        'lapCounter2' => $request->lapCounter2,
                        'lapCounter3' => $request->lapCounter3,
                        'lapCounter4' => $request->lapCounter4,
                        'lapCounter5' => $request->lapCounter5,
                        'lapCounter6' => $request->lapCounter6,
                        'lapCounter7' => $request->lapCounter7,
                        'lapCounter8' => $request->lapCounter8,
                        'lapCounter9' => $request->lapCounter9,
                        'lapCounter10' => $request->lapCounter10,
                        'lapCounter11' => $request->lapCounter11,
                        'lapCounter12' => $request->lapCounter12,
                        'lapCounter13' => $request->lapCounter13,
                        'lapCounter14' => $request->lapCounter14,
                        'lapCounter15' => $request->lapCounter15,
                        // 'patName' => $request->patName,
                        'walk' => $request->walk,
                        'techID' => $request->techID,
                        'entereddate' => $request->entereddate,
                        // 'gender' => $request->gender,
                        // 'age' => $request->age,
                        // 'race' => $request->race,
                        'heightFT' => $request->heightFT,
                        'heightIN' => $request->heightIN,
                        'heightMETERS' => $request->heightMETERS,
                        'weightLBS' => $request->weightLBS,
                        'weightKG' => $request->weightKG,
                        'bpsys1' => $request->bpsys1,
                        'bpdias2' => $request->bpdias2,
                        'medsDose' => $request->medsDose,
                        'medsTime' => $request->medsTime,
                        'suppOxygen' => $request->suppOxygen,
                        'oxygenFlow' => $request->oxygenFlow,
                        'oxygenType' => $request->oxygenType,
                        'baselineTime' => $request->baselineTime,
                        'endTestTime' => $request->endTestTime,
                        'baselineHR' => $request->baselineHR,
                        'endTestHR' => $request->endTestHR,
                        'baselineDyspnea' => $request->baselineDyspnea,
                        'endTestDyspnea' => $request->endTestDyspnea,
                        'baselineFatigue' => $request->baselineFatigue,
                        'endTestFatigue' => $request->endTestFatigue,
                        'baselineSpO2' => $request->baselineSpO2,
                        'endTestSpO2' => $request->endTestSpO2,
                        'stopPaused' => $request->stopPaused,
                        'reason' => $request->reason,
                        'othSymptoms' => $request->othSymptoms,
                        'lapsNo' => $request->lapsNo,
                        'partialLaps' => $request->partialLaps,
                        'lapsTot' => $request->lapsTot,
                        'totDistance' => $request->totDistance,
                        'perfDistance' => $request->perfDistance,
                        'percentPredicted' => $request->percentPredicted,
                        'comments' => $request->comments,
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
    
    public function get_table_sixMinWalking(Request $request){
        
        $sixminwalk_obj = DB::table('hisdb.phy_sixminwalk')
                        ->where('compcode','=',session('compcode'))
                        ->where('idno','=',$request->idno);
                        // ->where('mrn','=',$request->mrn)
                        // ->where('episno','=',$request->episno);
        
        $patmast_obj = DB::table('hisdb.pat_mast')
                        ->where('compcode',session('compcode'))
                        ->where('mrn','=',$request->mrn);
        
        $responce = new stdClass();
        
        if($sixminwalk_obj->exists()){
            $sixminwalk_obj = $sixminwalk_obj->first();
            $responce->sixminwalk = $sixminwalk_obj;
        }
        
        if($patmast_obj->exists()){
            $patmast_obj = $patmast_obj->first();
            
            $patName = $patmast_obj->Name;
            $responce->patName = $patName;
            
            $gender = $patmast_obj->Sex;
            $responce->gender = $gender;
        }
        
        return json_encode($responce);
        
    }
    
    public function get_datetime_sixMinWalking(Request $request){
        
        $responce = new stdClass();
        
        $sixminwalk_obj = DB::table('hisdb.phy_sixminwalk')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno);
        
        if($sixminwalk_obj->exists()){
            $sixminwalk_obj = $sixminwalk_obj->get();
            
            $data = [];
            
            foreach($sixminwalk_obj as $key => $value){
                $date['idno'] = $value->idno;
                $date['mrn'] = $value->mrn;
                $date['episno'] = $value->episno;
                if(!empty($value->entereddate)){
                    $date['entereddate'] =  Carbon::createFromFormat('Y-m-d', $value->entereddate)->format('d-m-Y');
                }else{
                    $date['entereddate'] =  '-';
                }
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