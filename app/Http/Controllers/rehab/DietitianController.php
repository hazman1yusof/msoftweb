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

class DietitianController extends defaultController
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function show(Request $request)
    {
        return view('rehab.dietitian.dietitian');
    }
    
    public function form(Request $request)
    {
        DB::enableQueryLog();
        switch($request->action){
            case 'save_table_dietitian':
                switch($request->oper){
                    case 'add':
                        return $this->add_dietitian($request);
                    case 'edit':
                        return $this->edit_dietitian($request);
                    default:
                        return 'error happen..';
                }
            
            case 'get_table_dietitian':
                return $this->get_table_dietitian($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function table(Request $request)
    {
        switch($request->action){
            case 'get_datetime_dietitian':
                return $this->get_datetime_dietitian($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function add_dietitian(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $dietitian = DB::table('hisdb.dietitian')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno)
                        ->where('entereddate','=',$request->entereddate);
            
            if($dietitian->exists()){
                // throw new \Exception('Date already exist.', 500);
                return response('Date already exist.');
            }
            
            DB::table('hisdb.dietitian')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn,
                    'episno' => $request->episno,
                    'entereddate' => $request->entereddate,
                    'notes' => $request->notes,
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
    
    public function edit_dietitian(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $dietitian = DB::table('hisdb.dietitian')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno)
                        ->where('entereddate','=',$request->entereddate);
            
            if(!empty($request->idno_dietitian)){
                if($dietitian->exists()){
                    if($dietitian->first()->idno != $request->idno_dietitian){
                        // throw new \Exception('Date already exist.', 500);
                        return response('Date already exist.');
                    }
                }
                
                DB::table('hisdb.dietitian')
                    ->where('idno','=',$request->idno_dietitian)
                    // ->where('mrn','=',$request->mrn)
                    // ->where('episno','=',$request->episno)
                    // ->where('compcode','=',session('compcode'))
                    ->update([
                        'entereddate' => $request->entereddate,
                        'notes' => $request->notes,
                        'upduser'  => session('username'),
                        'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
            }else{
                if($dietitian->exists()){
                    // throw new \Exception('Date already exist.', 500);
                    return response('Date already exist.');
                }
                
                DB::table('hisdb.dietitian')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'entereddate' => $request->entereddate,
                        'notes' => $request->notes,
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
    
    public function get_table_dietitian(Request $request){
        
        $dietitian_obj = DB::table('hisdb.dietitian')
                        ->where('compcode','=',session('compcode'))
                        ->where('idno','=',$request->idno);
                        // ->where('mrn','=',$request->mrn)
                        // ->where('episno','=',$request->episno);
        
        $responce = new stdClass();
        
        if($dietitian_obj->exists()){
            $dietitian_obj = $dietitian_obj->first();
            $responce->dietitian = $dietitian_obj;
        }
        
        return json_encode($responce);
        
    }
    
    public function get_datetime_dietitian(Request $request){
        
        $responce = new stdClass();
        
        $dietitian_obj = DB::table('hisdb.dietitian')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno);
        
        if($dietitian_obj->exists()){
            $dietitian_obj = $dietitian_obj->get();
            
            $data = [];
            
            foreach($dietitian_obj as $key => $value){
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
    
    public function dietitian_chart(Request $request){
        
        $mrn = $request->mrn;
        $episno = $request->episno;
        $entereddate = $request->entereddate;
        if(!$mrn || !$episno || !$entereddate){
            abort(404);
        }
        
        $dietitian = DB::table('hisdb.dietitian as dt')
                    ->select('dt.idno','dt.compcode','dt.mrn','dt.episno','dt.entereddate','dt.notes','dt.adduser','dt.adddate','dt.upduser','dt.upddate','dt.lastuser','dt.lastupdate','dt.computerid','pm.Name','pm.Newic')
                    ->leftjoin('hisdb.pat_mast as pm', function ($join){
                        $join = $join->on('pm.MRN','=','dt.mrn');
                        // $join = $join->on('pm.Episno','=','dt.episno');
                        $join = $join->where('pm.CompCode','=',session('compcode'));
                    })
                    ->where('dt.compcode','=',session('compcode'))
                    ->where('dt.mrn','=',$mrn)
                    ->where('dt.episno','=',$episno)
                    ->where('dt.entereddate','=',$entereddate)
                    ->first();
        // dd($dietitian);
        
        $company = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();
        
        return view('rehab.dietitian.dietitianChart_pdfmake',compact('dietitian','company'));
        
    }
    
}