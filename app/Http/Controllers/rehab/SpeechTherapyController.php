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

class SpeechTherapyController extends defaultController
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function show(Request $request)
    {
        return view('rehab.speechTherapy.speechTherapy');
    }
    
    public function form(Request $request)
    {
        DB::enableQueryLog();
        switch($request->action){
            case 'save_table_speechTherapy':
                switch($request->oper){
                    case 'add':
                        return $this->add_speechTherapy($request);
                    case 'edit':
                        return $this->edit_speechTherapy($request);
                    default:
                        return 'error happen..';
                }
            
            case 'get_table_speechTherapy':
                return $this->get_table_speechTherapy($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function table(Request $request)
    {
        switch($request->action){
            case 'get_datetime_speechTherapy':
                return $this->get_datetime_speechTherapy($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function add_speechTherapy(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $speechtherapy = DB::table('hisdb.speechtherapy')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno)
                            ->where('entereddate','=',$request->entereddate);
            
            if($speechtherapy->exists()){
                // throw new \Exception('Date already exist.', 500);
                return response('Date already exist.');
            }
            
            DB::table('hisdb.speechtherapy')
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
    
    public function edit_speechTherapy(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $speechtherapy = DB::table('hisdb.speechtherapy')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno)
                            ->where('entereddate','=',$request->entereddate);
            
            if(!empty($request->idno_speechTherapy)){
                if($speechtherapy->exists()){
                    if($speechtherapy->first()->idno != $request->idno_speechTherapy){
                        // throw new \Exception('Date already exist.', 500);
                        return response('Date already exist.');
                    }
                }
                
                DB::table('hisdb.speechtherapy')
                    ->where('idno','=',$request->idno_speechTherapy)
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
                if($speechtherapy->exists()){
                    // throw new \Exception('Date already exist.', 500);
                    return response('Date already exist.');
                }
                
                DB::table('hisdb.speechtherapy')
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
    
    public function get_table_speechTherapy(Request $request){
        
        $speechtherapy_obj = DB::table('hisdb.speechtherapy')
                            ->where('compcode','=',session('compcode'))
                            ->where('idno','=',$request->idno);
                            // ->where('mrn','=',$request->mrn)
                            // ->where('episno','=',$request->episno);
        
        $responce = new stdClass();
        
        if($speechtherapy_obj->exists()){
            $speechtherapy_obj = $speechtherapy_obj->first();
            $responce->speechtherapy = $speechtherapy_obj;
        }
        
        return json_encode($responce);
        
    }
    
    public function get_datetime_speechTherapy(Request $request){
        
        $responce = new stdClass();
        
        $speechtherapy_obj = DB::table('hisdb.speechtherapy')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno);
        
        if($speechtherapy_obj->exists()){
            $speechtherapy_obj = $speechtherapy_obj->get();
            
            $data = [];
            
            foreach($speechtherapy_obj as $key => $value){
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
    
    public function speechtherapy_chart(Request $request){
        
        $mrn = $request->mrn;
        $episno = $request->episno;
        $entereddate = $request->entereddate;
        if(!$mrn || !$episno || !$entereddate){
            abort(404);
        }
        
        $speechtherapy = DB::table('hisdb.speechtherapy as nr')
                        ->select('nr.idno','nr.compcode','nr.mrn','nr.episno','nr.entereddate','nr.notes','nr.adduser','nr.adddate','nr.upduser','nr.upddate','nr.lastuser','nr.lastupdate','nr.computerid','pm.Name','pm.Newic')
                        ->leftjoin('hisdb.pat_mast as pm', function ($join){
                            $join = $join->on('pm.MRN','=','nr.mrn');
                            // $join = $join->on('pm.Episno','=','nr.episno');
                            $join = $join->where('pm.CompCode','=',session('compcode'));
                        })
                        ->where('nr.compcode','=',session('compcode'))
                        ->where('nr.mrn','=',$mrn)
                        ->where('nr.episno','=',$episno)
                        ->where('nr.entereddate','=',$entereddate)
                        ->first();
        // dd($speechtherapy);
        
        $company = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();
        
        return view('rehab.speechTherapy.speechTherapyChart_pdfmake',compact('speechtherapy','company'));
        
    }
    
}