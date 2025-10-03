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

class BergBalanceTestController extends defaultController
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function show(Request $request)
    {
        return view('rehab.bergBalanceTest');
    }
    
    public function form(Request $request)
    {
        DB::enableQueryLog();
        switch($request->action){
            case 'save_table_bergBalanceTest':
                switch($request->oper){
                    case 'add':
                        return $this->add_bergBalanceTest($request);
                    case 'edit':
                        return $this->edit_bergBalanceTest($request);
                    default:
                        return 'error happen..';
                }
            
            case 'get_table_bergBalanceTest':
                return $this->get_table_bergBalanceTest($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function table(Request $request)
    {
        switch($request->action){
            case 'get_datetime_bergBalanceTest':
                return $this->get_datetime_bergBalanceTest($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function add_bergBalanceTest(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $bergtest = DB::table('hisdb.phy_bergtest')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno)
                        ->where('entereddate','=',$request->entereddate);
            
            if($bergtest->exists()){
                // throw new \Exception('Date already exist.', 500);
                return response('Date already exist.');
            }
            
            DB::table('hisdb.phy_bergtest')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn,
                    'episno' => $request->episno,
                    'entereddate' => $request->entereddate,
                    'sitToStand' => $request->sitToStand,
                    'standUnsupported' => $request->standUnsupported,
                    'sitBackUnsupported' => $request->sitBackUnsupported,
                    'standToSit' => $request->standToSit,
                    'transfer' => $request->transfer,
                    'standEyesClosed' => $request->standEyesClosed,
                    'standFeetTogether' => $request->standFeetTogether,
                    'reachForward' => $request->reachForward,
                    'pickUpObject' => $request->pickUpObject,
                    'turnToLookBehind' => $request->turnToLookBehind,
                    'turn360' => $request->turn360,
                    'placeFootOnStep' => $request->placeFootOnStep,
                    'oneFootInFront' => $request->oneFootInFront,
                    'standOneLeg' => $request->standOneLeg,
                    'totalScore' => $request->totalScore,
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
    
    public function edit_bergBalanceTest(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $bergtest = DB::table('hisdb.phy_bergtest')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno)
                        ->where('entereddate','=',$request->entereddate);
            
            if(!empty($request->idno_bergBalanceTest)){
                if($bergtest->exists()){
                    if($bergtest->first()->idno != $request->idno_bergBalanceTest){
                        // throw new \Exception('Date already exist.', 500);
                        return response('Date already exist.');
                    }
                }
                
                DB::table('hisdb.phy_bergtest')
                    ->where('idno','=',$request->idno_bergBalanceTest)
                    // ->where('mrn','=',$request->mrn)
                    // ->where('episno','=',$request->episno)
                    // ->where('compcode','=',session('compcode'))
                    ->update([
                        'entereddate' => $request->entereddate,
                        'sitToStand' => $request->sitToStand,
                        'standUnsupported' => $request->standUnsupported,
                        'sitBackUnsupported' => $request->sitBackUnsupported,
                        'standToSit' => $request->standToSit,
                        'transfer' => $request->transfer,
                        'standEyesClosed' => $request->standEyesClosed,
                        'standFeetTogether' => $request->standFeetTogether,
                        'reachForward' => $request->reachForward,
                        'pickUpObject' => $request->pickUpObject,
                        'turnToLookBehind' => $request->turnToLookBehind,
                        'turn360' => $request->turn360,
                        'placeFootOnStep' => $request->placeFootOnStep,
                        'oneFootInFront' => $request->oneFootInFront,
                        'standOneLeg' => $request->standOneLeg,
                        'totalScore' => $request->totalScore,
                        'upduser'  => session('username'),
                        'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
            }else{
                if($bergtest->exists()){
                    // throw new \Exception('Date already exist.', 500);
                    return response('Date already exist.');
                }
                
                DB::table('hisdb.phy_bergtest')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'entereddate' => $request->entereddate,
                        'sitToStand' => $request->sitToStand,
                        'standUnsupported' => $request->standUnsupported,
                        'sitBackUnsupported' => $request->sitBackUnsupported,
                        'standToSit' => $request->standToSit,
                        'transfer' => $request->transfer,
                        'standEyesClosed' => $request->standEyesClosed,
                        'standFeetTogether' => $request->standFeetTogether,
                        'reachForward' => $request->reachForward,
                        'pickUpObject' => $request->pickUpObject,
                        'turnToLookBehind' => $request->turnToLookBehind,
                        'turn360' => $request->turn360,
                        'placeFootOnStep' => $request->placeFootOnStep,
                        'oneFootInFront' => $request->oneFootInFront,
                        'standOneLeg' => $request->standOneLeg,
                        'totalScore' => $request->totalScore,
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
    
    public function get_table_bergBalanceTest(Request $request){
        
        $bergtest_obj = DB::table('hisdb.phy_bergtest')
                        ->where('compcode','=',session('compcode'))
                        ->where('idno','=',$request->idno);
                        // ->where('mrn','=',$request->mrn)
                        // ->where('episno','=',$request->episno);
        
        $responce = new stdClass();
        
        if($bergtest_obj->exists()){
            $bergtest_obj = $bergtest_obj->first();
            $responce->bergtest = $bergtest_obj;
        }
        
        return json_encode($responce);
        
    }
    
    public function get_datetime_bergBalanceTest(Request $request){
        
        $responce = new stdClass();
        
        $bergtest_obj = DB::table('hisdb.phy_bergtest')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno);
        
        if($bergtest_obj->exists()){
            $bergtest_obj = $bergtest_obj->get();
            
            $data = [];
            
            foreach($bergtest_obj as $key => $value){
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
    
    public function bergbalancetest_chart(Request $request){
        
        $mrn = $request->mrn;
        $episno = $request->episno;
        $entereddate = $request->entereddate;
        if(!$mrn || !$episno || !$entereddate){
            abort(404);
        }
        
        $bergtest = DB::table('hisdb.phy_bergtest as b')
                    ->select('b.idno','b.compcode','b.mrn','b.episno','b.entereddate','b.sitToStand','b.standUnsupported','b.sitBackUnsupported','b.standToSit','b.transfer','b.standEyesClosed','b.standFeetTogether','b.reachForward','b.pickUpObject','b.turnToLookBehind','b.turn360','b.placeFootOnStep','b.oneFootInFront','b.standOneLeg','b.totalScore','b.adduser','b.adddate','b.upduser','b.upddate','b.lastuser','b.lastupdate','b.computerid','pm.Name','pm.Newic')
                    ->leftjoin('hisdb.pat_mast as pm', function ($join){
                        $join = $join->on('pm.MRN','=','b.mrn');
                        // $join = $join->on('pm.Episno','=','b.episno');
                        $join = $join->where('pm.compcode','=',session('compcode'));
                    })
                    ->where('b.compcode','=',session('compcode'))
                    ->where('b.mrn','=',$mrn)
                    ->where('b.episno','=',$episno)
                    ->where('b.entereddate','=',$entereddate)
                    ->first();
        // dd($bergtest);
        
        $company = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();
        
        return view('rehab.bergBalanceTestChart_pdfmake',compact('bergtest','company'));
        
    }
    
}