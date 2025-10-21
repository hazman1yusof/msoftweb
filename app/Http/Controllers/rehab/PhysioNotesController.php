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

class PhysioNotesController extends defaultController
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function show(Request $request)
    {
        return view('rehab.physioNotes');
    }
    
    public function form(Request $request)
    {
        DB::enableQueryLog();
        switch($request->action){
            case 'save_table_physioNotes':
                switch($request->oper){
                    case 'add':
                        return $this->add_physioNotes($request);
                    case 'edit':
                        return $this->edit_physioNotes($request);
                    default:
                        return 'error happen..';
                }
            
            case 'get_table_physioNotes':
                return $this->get_table_physioNotes($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function table(Request $request)
    {
        switch($request->action){
            case 'get_datetime_physioNotes':
                return $this->get_datetime_physioNotes($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function add_physioNotes(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $notes = DB::table('hisdb.phy_notes')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$request->mrn)
                    ->where('episno','=',$request->episno)
                    ->where('entereddate','=',$request->entereddate);
            
            if($notes->exists()){
                // throw new \Exception('Date already exist.', 500);
                return response('Date already exist.');
            }
            
            DB::table('hisdb.phy_notes')
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
    
    public function edit_physioNotes(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $notes = DB::table('hisdb.phy_notes')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$request->mrn)
                    ->where('episno','=',$request->episno)
                    ->where('entereddate','=',$request->entereddate);
            
            if(!empty($request->idno_physioNotes)){
                if($notes->exists()){
                    if($notes->first()->idno != $request->idno_physioNotes){
                        // throw new \Exception('Date already exist.', 500);
                        return response('Date already exist.');
                    }
                }
                
                DB::table('hisdb.phy_notes')
                    ->where('idno','=',$request->idno_physioNotes)
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
                if($notes->exists()){
                    // throw new \Exception('Date already exist.', 500);
                    return response('Date already exist.');
                }
                
                DB::table('hisdb.phy_notes')
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
    
    public function get_table_physioNotes(Request $request){
        
        $notes_obj = DB::table('hisdb.phy_notes')
                    ->where('compcode','=',session('compcode'))
                    ->where('idno','=',$request->idno);
                    // ->where('mrn','=',$request->mrn)
                    // ->where('episno','=',$request->episno);
        
        $responce = new stdClass();
        
        if($notes_obj->exists()){
            $notes_obj = $notes_obj->first();
            $responce->notes = $notes_obj;
        }
        
        return json_encode($responce);
        
    }
    
    public function get_datetime_physioNotes(Request $request){
        
        $responce = new stdClass();
        
        $notes_obj = DB::table('hisdb.phy_notes')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$request->mrn)
                    ->where('episno','=',$request->episno);
        
        if($notes_obj->exists()){
            $notes_obj = $notes_obj->get();
            
            $data = [];
            
            foreach($notes_obj as $key => $value){
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
    
    public function physionotes_chart(Request $request){
        
        $mrn = $request->mrn;
        $episno = $request->episno;
        $entereddate = $request->entereddate;
        if(!$mrn || !$episno || !$entereddate){
            abort(404);
        }
        
        $notes = DB::table('hisdb.phy_notes as nt')
                ->select('nt.idno','nt.compcode','nt.mrn','nt.episno','nt.entereddate','nt.notes','nt.adduser','nt.adddate','nt.upduser','nt.upddate','nt.lastuser','nt.lastupdate','nt.computerid','pm.Name','pm.Newic')
                ->leftjoin('hisdb.pat_mast as pm', function ($join){
                    $join = $join->on('pm.MRN','=','nt.mrn');
                    // $join = $join->on('pm.Episno','=','nt.episno');
                    $join = $join->where('pm.CompCode','=',session('compcode'));
                })
                ->where('nt.compcode','=',session('compcode'))
                ->where('nt.mrn','=',$mrn)
                ->where('nt.episno','=',$episno)
                ->where('nt.entereddate','=',$entereddate)
                ->first();
        // dd($notes);
        
        $company = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();
        
        return view('rehab.physioNotesChart_pdfmake',compact('notes','company'));
        
    }
    
}