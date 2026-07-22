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

class DieteticCareNotesController extends defaultController
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function show(Request $request)
    {
        return view('hisdb.dieteticCareNotes.dieteticCareNotes');
    }

    public function table(Request $request)
    {
        switch($request->action){
            case 'get_table_datetimeDietNote':
                return $this->get_table_datetimeDietNote($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function form(Request $request)
    {
        DB::enableQueryLog();
        switch($request->action){
            case 'save_table_dieteticCareNotes':
                switch($request->oper){
                    case 'add':
                        return $this->add($request);
                    case 'edit':
                        return $this->edit($request);
                    default:
                        return 'error happen..';
                }
            
            case 'get_table_dieteticCareNotes':
                return $this->get_table_dieteticCareNotes($request);
            
            default:
                return 'error happen..';
        }
    }

    public function get_table_datetimeDietNote(Request $request){
        
        $responce = new stdClass();
        
        $dietetic_obj = DB::table('hisdb.patdietncase')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno);
        
        if($dietetic_obj->exists()){
            $dietetic_obj = $dietetic_obj->get();
            
            $data = [];
            
            foreach($dietetic_obj as $key => $value){
                if(!empty($value->datetaken)){
                    $date['datetaken'] =  Carbon::createFromFormat('Y-m-d', $value->datetaken)->format('d-m-Y');
                }else{
                    $date['datetaken'] =  '-';
                }
                $date['idno'] = $value->idno;
                $date['mrn'] = $value->mrn;
                $date['episno'] = $value->episno;
                $date['timetaken'] = $value->timetaken;
                $date['adduser'] = $value->adduser;
                
                array_push($data,$date);
            }
            
            $responce->data = $data;
        }else{
            $responce->data = [];
        }
        
        return json_encode($responce);
        
    }
    
    public function add(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('hisdb.patdietncase')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'datetaken' => $request->datetaken,
                        'timetaken' => $request->timetaken,
                        'progress' => $request->progress,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur"),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur"),
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
    
    public function edit(Request $request){
        
        DB::beginTransaction();
        
        try {

            $patdietncase = DB::table('hisdb.patdietncase')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno)
                            ->where('datetaken','=',$request->datetaken);
        
            if(!empty($request->idno_dieteticCareNotes)){
                DB::table('hisdb.patdietncase')                    
                ->where('idno','=',$request->idno_dieteticCareNotes)
                    ->update([
                        'datetaken' => $request->datetaken,
                        'timetaken' => $request->timetaken,
                        'progress' => $request->progress,
                        'upduser'  => session('username'),
                        'upddate'  => Carbon::now("Asia/Kuala_Lumpur"),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur"),
                        'lastcomputerid' => session('computerid'),
                    ]);
            }else{

                if($patdietncase->exists()){
                    return response('Date already exist.');
                }

                DB::table('hisdb.patdietncase')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'datetaken' => $request->datetaken,
                        'timetaken' => $request->timetaken,
                        'progress' => $request->progress,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur"),
                        'computerid' => session('computerid'),
                    ]);
            }

            $queries = DB::getQueryLog();
            
            DB::commit();

            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function get_table_dieteticCareNotes (Request $request){
        
        $patdietncase_obj = DB::table('hisdb.patdietncase')
                        ->where('compcode','=',session('compcode'))
                        ->where('idno','=',$request->idno);
        
        $responce = new stdClass();
        
        if($patdietncase_obj->exists()){
            $patdietncase_obj = $patdietncase_obj->first();
            $date = Carbon::createFromFormat('Y-m-d', $patdietncase_obj->datetaken)->format('Y-m-d');

            $responce->patdietncase = $patdietncase_obj;
            $responce->date = $date;
        }
        
        return json_encode($responce);
        
    }

}