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

class PsychotherapyController extends defaultController
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function show(Request $request)
    {
        return view('rehab.psychotherapy.psychotherapy');
    }
    
    public function form(Request $request)
    {
        DB::enableQueryLog();
        switch($request->action){
            case 'save_table_psychotherapy':
                switch($request->oper){
                    case 'add':
                        return $this->add_psychotherapy($request);
                    case 'edit':
                        return $this->edit_psychotherapy($request);
                    default:
                        return 'error happen..';
                }
            
            case 'get_table_psychotherapy':
                return $this->get_table_psychotherapy($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function table(Request $request)
    {
        switch($request->action){
            case 'get_datetime_psychotherapy':
                return $this->get_datetime_psychotherapy($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function add_psychotherapy(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $psychotherapy = DB::table('hisdb.psychotherapy')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno)
                            ->where('entereddate','=',$request->entereddate)
                            ->where('enteredtime','=',$request->enteredtime);
            
            // if($psychotherapy->exists()){
            //     // throw new \Exception('Date already exist.', 500);
            //     return response('Date already exist.');
            // }
            
            DB::table('hisdb.psychotherapy')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn,
                    'episno' => $request->episno,
                    'entereddate' => $request->entereddate,
                    'enteredtime' => Carbon::now("Asia/Kuala_Lumpur"),
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
    
    public function edit_psychotherapy(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $psychotherapy = DB::table('hisdb.psychotherapy')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno)
                            ->where('entereddate','=',$request->entereddate);
            
            if(!empty($request->idno_psychotherapy)){
                if($psychotherapy->exists()){
                    if($psychotherapy->first()->idno != $request->idno_psychotherapy){
                        // throw new \Exception('Date already exist.', 500);
                        return response('Date already exist.');
                    }
                }
                
                DB::table('hisdb.psychotherapy')
                    ->where('idno','=',$request->idno_psychotherapy)
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
                if($psychotherapy->exists()){
                    // throw new \Exception('Date already exist.', 500);
                    return response('Date already exist.');
                }
                
                DB::table('hisdb.psychotherapy')
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
    
    public function get_table_psychotherapy(Request $request){
        
        $psychotherapy_obj = DB::table('hisdb.psychotherapy')
                            ->where('compcode','=',session('compcode'))
                            ->where('idno','=',$request->idno);
                            // ->where('mrn','=',$request->mrn)
                            // ->where('episno','=',$request->episno);
        
        $responce = new stdClass();
        
        if($psychotherapy_obj->exists()){
            $psychotherapy_obj = $psychotherapy_obj->first();
            $responce->psychotherapy = $psychotherapy_obj;
        }
        
        return json_encode($responce);
        
    }
    
    public function get_datetime_psychotherapy(Request $request){
        
        $responce = new stdClass();
        
        $psychotherapy_obj = DB::table('hisdb.psychotherapy')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn);
                            // ->where('episno','=',$request->episno);
        
        if($psychotherapy_obj->exists()){
            $psychotherapy_obj = $psychotherapy_obj->get();
            
            $data = [];
            
            foreach($psychotherapy_obj as $key => $value){
                $date['idno'] = $value->idno;
                $date['mrn'] = $value->mrn;
                $date['episno'] = $value->episno;
                if(!empty($value->entereddate)){
                    $date['entereddate'] =  Carbon::createFromFormat('Y-m-d', $value->entereddate)->format('d-m-Y');
                }else{
                    $date['entereddate'] =  '-';
                }
                // $date['dt'] = $value->entereddate; // for sorting
                if(!empty($value->entereddate)){ // for sorting
                    $date['dt'] =  Carbon::createFromFormat('Y-m-d', $value->entereddate)->format('d-m-Y').' '.$value->enteredtime;
                }else{
                    $date['dt'] =  '-';
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
    
    public function psychotherapy_chart(Request $request){
        
        $mrn = $request->mrn;
        $episno = $request->episno;
        $entereddate = $request->entereddate;
        if(!$mrn || !$episno || !$entereddate){
            abort(404);
        }
        
        $psychotherapy = DB::table('hisdb.psychotherapy as pt')
                        ->select('pt.idno','pt.compcode','pt.mrn','pt.episno','pt.entereddate','pt.enteredtime','pt.notes','pt.adduser','pt.adddate','pt.upduser','pt.upddate','pt.lastuser','pt.lastupdate','pt.computerid','pm.Name','pm.Newic')
                        ->leftjoin('hisdb.pat_mast as pm', function ($join){
                            $join = $join->on('pm.MRN','=','pt.mrn');
                            // $join = $join->on('pm.Episno','=','pt.episno');
                            $join = $join->where('pm.CompCode','=',session('compcode'));
                        })
                        ->where('pt.compcode','=',session('compcode'))
                        ->where('pt.mrn','=',$mrn)
                        ->where('pt.episno','=',$episno)
                        ->where('pt.entereddate','=',$entereddate)
                        ->where('pt.enteredtime','=',$request->enteredtime)
                        ->first();
        // dd($psychotherapy);
        
        $company = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();
        
        return view('rehab.psychotherapy.psychotherapyChart_pdfmake',compact('psychotherapy','company'));
        
    }
    
}