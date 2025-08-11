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

class MotorScaleController extends defaultController
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function show(Request $request)
    {
        return view('rehab.motorScale');
    }
    
    public function form(Request $request)
    {
        DB::enableQueryLog();
        switch($request->action){
            case 'save_table_motorScale':
                switch($request->oper){
                    case 'add':
                        return $this->add_motorScale($request);
                    case 'edit':
                        return $this->edit_motorScale($request);
                    default:
                        return 'error happen..';
                }
            
            case 'get_table_motorScale':
                return $this->get_table_motorScale($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function table(Request $request)
    {
        switch($request->action){
            case 'get_datetime_motorScale':
                return $this->get_datetime_motorScale($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function add_motorScale(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $motorscale = DB::table('hisdb.phy_motorscale')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno)
                        ->where('entereddate','=',$request->entereddate);
            
            if($motorscale->exists()){
                // throw new \Exception('Date already exist.', 500);
                return response('Date already exist.');
            }
            
            DB::table('hisdb.phy_motorscale')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn,
                    'episno' => $request->episno,
                    'entereddate' => $request->entereddate,
                    'sideLie' => $request->sideLie,
                    'sitOverBed' => $request->sitOverBed,
                    'balancedSit' => $request->balancedSit,
                    'sitToStand' => $request->sitToStand,
                    'walking' => $request->walking,
                    'upperArmFunc' => $request->upperArmFunc,
                    'advHandActvt' => $request->advHandActvt,
                    'generalTonus' => $request->generalTonus,
                    'movementScore' => $request->movementScore,
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
    
    public function edit_motorScale(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $motorscale = DB::table('hisdb.phy_motorscale')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno)
                        ->where('entereddate','=',$request->entereddate);
            
            if(!empty($request->idno_motorScale)){
                if($motorscale->exists()){
                    if($motorscale->first()->idno != $request->idno_motorScale){
                        // throw new \Exception('Date already exist.', 500);
                        return response('Date already exist.');
                    }
                }
                
                DB::table('hisdb.phy_motorscale')
                    ->where('idno','=',$request->idno_motorScale)
                    // ->where('mrn','=',$request->mrn)
                    // ->where('episno','=',$request->episno)
                    // ->where('compcode','=',session('compcode'))
                    ->update([
                        'entereddate' => $request->entereddate,
                        'sideLie' => $request->sideLie,
                        'sitOverBed' => $request->sitOverBed,
                        'balancedSit' => $request->balancedSit,
                        'sitToStand' => $request->sitToStand,
                        'walking' => $request->walking,
                        'upperArmFunc' => $request->upperArmFunc,
                        'advHandActvt' => $request->advHandActvt,
                        'generalTonus' => $request->generalTonus,
                        'movementScore' => $request->movementScore,
                        'comments' => $request->comments,
                        'upduser'  => session('username'),
                        'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
            }else{
                if($motorscale->exists()){
                    // throw new \Exception('Date already exist.', 500);
                    return response('Date already exist.');
                }
                
                DB::table('hisdb.phy_motorscale')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'entereddate' => $request->entereddate,
                        'sideLie' => $request->sideLie,
                        'sitOverBed' => $request->sitOverBed,
                        'balancedSit' => $request->balancedSit,
                        'sitToStand' => $request->sitToStand,
                        'walking' => $request->walking,
                        'upperArmFunc' => $request->upperArmFunc,
                        'advHandActvt' => $request->advHandActvt,
                        'generalTonus' => $request->generalTonus,
                        'movementScore' => $request->movementScore,
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
    
    public function get_table_motorScale(Request $request){
        
        $motorscale_obj = DB::table('hisdb.phy_motorscale')
                        ->where('compcode','=',session('compcode'))
                        ->where('idno','=',$request->idno);
                        // ->where('mrn','=',$request->mrn)
                        // ->where('episno','=',$request->episno);
        
        $responce = new stdClass();
        
        if($motorscale_obj->exists()){
            $motorscale_obj = $motorscale_obj->first();
            $responce->motorscale = $motorscale_obj;
        }
        
        return json_encode($responce);
        
    }
    
    public function get_datetime_motorScale(Request $request){
        
        $responce = new stdClass();
        
        $motorscale_obj = DB::table('hisdb.phy_motorscale')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno);
        
        if($motorscale_obj->exists()){
            $motorscale_obj = $motorscale_obj->get();
            
            $data = [];
            
            foreach($motorscale_obj as $key => $value){
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
    
    public function motorscale_chart(Request $request){
        
        $mrn = $request->mrn;
        $episno = $request->episno;
        $entereddate = $request->entereddate;
        if(!$mrn || !$episno || !$entereddate){
            abort(404);
        }
        
        $motorscale = DB::table('hisdb.phy_motorscale as ms')
                    ->select('ms.idno','ms.compcode','ms.mrn','ms.episno','ms.entereddate','ms.sideLie','ms.sitOverBed','ms.balancedSit','ms.sitToStand','ms.walking','ms.upperArmFunc','ms.advHandActvt','ms.generalTonus','ms.movementScore','ms.comments','ms.adduser','ms.adddate','ms.upduser','ms.upddate','ms.lastuser','ms.lastupdate','ms.computerid','pm.Name','pm.Newic')
                    ->leftjoin('hisdb.pat_mast as pm', function ($join){
                        $join = $join->on('pm.MRN','=','ms.mrn');
                        $join = $join->on('pm.Episno','=','ms.episno');
                        $join = $join->where('pm.compcode','=',session('compcode'));
                    })
                    ->where('ms.compcode','=',session('compcode'))
                    ->where('ms.mrn','=',$mrn)
                    ->where('ms.episno','=',$episno)
                    ->where('ms.entereddate','=',$entereddate)
                    ->first();
        // dd($motorscale);
        
        $company = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();
        
        return view('rehab.motorScaleChart_pdfmake',compact('motorscale','company'));
        
    }
    
}