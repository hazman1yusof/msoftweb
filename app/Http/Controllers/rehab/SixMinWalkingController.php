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
                    'lapCounter' => $request->lapCounter,
                    // 'patName' => $request->patName,
                    // 'walk' => $request->walk,
                    // 'techID' => $request->techID,
                    'entereddate' => $request->entereddate,
                    // 'gender' => $request->gender,
                    // 'age' => $request->age,
                    // 'race' => $request->race,
                    // 'heightFT' => $request->heightFT,
                    // 'heightIN' => $request->heightIN,
                    'heightCM' => $request->heightCM,
                    // 'weightLBS' => $request->weightLBS,
                    'weightKG' => $request->weightKG,
                    'bpsys1' => $request->bpsys1,
                    'bpdias2' => $request->bpdias2,
                    // 'medsDose' => $request->medsDose,
                    // 'medsTime' => $request->medsTime,
                    // 'suppOxygen' => $request->suppOxygen,
                    // 'oxygenFlow' => $request->oxygenFlow,
                    // 'oxygenType' => $request->oxygenType,
                    'baselineTime' => $request->baselineTime,
                    'endTestTime' => $request->endTestTime,
                    'baselineHR' => $request->baselineHR,
                    'endTestHR' => $request->endTestHR,
                    'baselineBorgScale' => $request->baselineBorgScale,
                    'endTestBorgScale' => $request->endTestBorgScale,
                    // 'baselineDyspnea' => $request->baselineDyspnea,
                    // 'endTestDyspnea' => $request->endTestDyspnea,
                    // 'baselineFatigue' => $request->baselineFatigue,
                    // 'endTestFatigue' => $request->endTestFatigue,
                    'baselineSpO2' => $request->baselineSpO2,
                    'endTestSpO2' => $request->endTestSpO2,
                    'stopPaused' => $request->stopPaused,
                    'reason' => $request->reason,
                    'othSymptoms' => $request->othSymptoms,
                    // 'lapsNo' => $request->lapsNo,
                    // 'partialLaps' => $request->partialLaps,
                    // 'lapsTot' => $request->lapsTot,
                    'totDistance' => $request->totDistance,
                    // 'predictDistance' => $request->predictDistance,
                    // 'percentPredicted' => $request->percentPredicted,
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
                        'lapCounter' => $request->lapCounter,
                        // 'patName' => $request->patName,
                        // 'walk' => $request->walk,
                        // 'techID' => $request->techID,
                        'entereddate' => $request->entereddate,
                        // 'gender' => $request->gender,
                        // 'age' => $request->age,
                        // 'race' => $request->race,
                        // 'heightFT' => $request->heightFT,
                        // 'heightIN' => $request->heightIN,
                        'heightCM' => $request->heightCM,
                        // 'weightLBS' => $request->weightLBS,
                        'weightKG' => $request->weightKG,
                        'bpsys1' => $request->bpsys1,
                        'bpdias2' => $request->bpdias2,
                        // 'medsDose' => $request->medsDose,
                        // 'medsTime' => $request->medsTime,
                        // 'suppOxygen' => $request->suppOxygen,
                        // 'oxygenFlow' => $request->oxygenFlow,
                        // 'oxygenType' => $request->oxygenType,
                        'baselineTime' => $request->baselineTime,
                        'endTestTime' => $request->endTestTime,
                        'baselineHR' => $request->baselineHR,
                        'endTestHR' => $request->endTestHR,
                        'baselineBorgScale' => $request->baselineBorgScale,
                        'endTestBorgScale' => $request->endTestBorgScale,
                        // 'baselineDyspnea' => $request->baselineDyspnea,
                        // 'endTestDyspnea' => $request->endTestDyspnea,
                        // 'baselineFatigue' => $request->baselineFatigue,
                        // 'endTestFatigue' => $request->endTestFatigue,
                        'baselineSpO2' => $request->baselineSpO2,
                        'endTestSpO2' => $request->endTestSpO2,
                        'stopPaused' => $request->stopPaused,
                        'reason' => $request->reason,
                        'othSymptoms' => $request->othSymptoms,
                        // 'lapsNo' => $request->lapsNo,
                        // 'partialLaps' => $request->partialLaps,
                        // 'lapsTot' => $request->lapsTot,
                        'totDistance' => $request->totDistance,
                        // 'predictDistance' => $request->predictDistance,
                        // 'percentPredicted' => $request->percentPredicted,
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
                        'lapCounter' => $request->lapCounter,
                        // 'patName' => $request->patName,
                        // 'walk' => $request->walk,
                        // 'techID' => $request->techID,
                        'entereddate' => $request->entereddate,
                        // 'gender' => $request->gender,
                        // 'age' => $request->age,
                        // 'race' => $request->race,
                        // 'heightFT' => $request->heightFT,
                        // 'heightIN' => $request->heightIN,
                        'heightCM' => $request->heightCM,
                        // 'weightLBS' => $request->weightLBS,
                        'weightKG' => $request->weightKG,
                        'bpsys1' => $request->bpsys1,
                        'bpdias2' => $request->bpdias2,
                        // 'medsDose' => $request->medsDose,
                        // 'medsTime' => $request->medsTime,
                        // 'suppOxygen' => $request->suppOxygen,
                        // 'oxygenFlow' => $request->oxygenFlow,
                        // 'oxygenType' => $request->oxygenType,
                        'baselineTime' => $request->baselineTime,
                        'endTestTime' => $request->endTestTime,
                        'baselineHR' => $request->baselineHR,
                        'endTestHR' => $request->endTestHR,
                        'baselineBorgScale' => $request->baselineBorgScale,
                        'endTestBorgScale' => $request->endTestBorgScale,
                        // 'baselineDyspnea' => $request->baselineDyspnea,
                        // 'endTestDyspnea' => $request->endTestDyspnea,
                        // 'baselineFatigue' => $request->baselineFatigue,
                        // 'endTestFatigue' => $request->endTestFatigue,
                        'baselineSpO2' => $request->baselineSpO2,
                        'endTestSpO2' => $request->endTestSpO2,
                        'stopPaused' => $request->stopPaused,
                        'reason' => $request->reason,
                        'othSymptoms' => $request->othSymptoms,
                        // 'lapsNo' => $request->lapsNo,
                        // 'partialLaps' => $request->partialLaps,
                        // 'lapsTot' => $request->lapsTot,
                        'totDistance' => $request->totDistance,
                        // 'predictDistance' => $request->predictDistance,
                        // 'percentPredicted' => $request->percentPredicted,
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
    
    public function sixminwalking_chart(Request $request){
        
        $mrn = $request->mrn;
        $episno = $request->episno;
        $entereddate = $request->entereddate;
        $age = $request->age;
        if(!$mrn || !$episno || !$entereddate){
            abort(404);
        }
        
        $sixminwalk = DB::table('hisdb.phy_sixminwalk as s')
                    ->select('s.idno','s.compcode','s.mrn','s.episno','s.lapCounter','s.patName','s.walk','s.techID','s.entereddate','s.gender','s.age','s.race','s.heightFT','s.heightIN','s.heightCM','s.weightLBS','s.weightKG','s.bpsys1','s.bpdias2','s.medsDose','s.medsTime','s.suppOxygen','s.oxygenFlow','s.oxygenType','s.baselineTime','s.endTestTime','s.baselineHR','s.endTestHR','s.baselineBorgScale','s.endTestBorgScale','s.baselineDyspnea','s.endTestDyspnea','s.baselineFatigue','s.endTestFatigue','s.baselineSpO2','s.endTestSpO2','s.stopPaused','s.reason','s.othSymptoms','s.lapsNo','s.partialLaps','s.lapsTot','s.totDistance','s.predictDistance','s.percentPredicted','s.comments','s.adduser','s.adddate','s.upduser','s.upddate','s.lastuser','s.lastupdate','s.computerid','pm.Name','pm.Newic','pm.Sex','rc.Description as raceDesc')
                    ->leftjoin('hisdb.pat_mast as pm', function ($join){
                        $join = $join->on('pm.MRN','=','s.mrn');
                        $join = $join->on('pm.Episno','=','s.episno');
                        $join = $join->where('pm.compcode','=',session('compcode'));
                    })
                    ->leftjoin('hisdb.racecode as rc', function ($join){
                        $join = $join->on('rc.Code','=','pm.RaceCode');
                        $join = $join->where('rc.compcode','=',session('compcode'));
                    })
                    ->where('s.compcode','=',session('compcode'))
                    ->where('s.mrn','=',$mrn)
                    ->where('s.episno','=',$episno)
                    ->where('s.entereddate','=',$entereddate)
                    ->first();
        // dd($sixminwalk);
        
        $company = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();
        
        return view('rehab.sixMinWalkingChart_pdfmake',compact('age','sixminwalk','company'));
        
    }
    
}