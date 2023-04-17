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

class OTManagementController extends defaultController
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function table(Request $request)
    {
        switch($request->action){
            case 'get_table_operRecList':
                return $this->get_table_operRecList($request);
            
            // event stuff
            case 'operRecList_event':
                return $this->operRecList_event($request);
                
            default:
                return 'error happen..';
        }
    }
    
    public function form(Request $request)
    {
        switch($request->action){
            
        }
    }
    
    public function index(Request $request){
        
        // dd(Auth::user());
        
        // $navbar = $this->navbar();
        
        // $emergency = DB::table('hisdb.episode')
        //                 ->whereMonth('reg_date', '=', now()->month)
        //                 ->whereYear('reg_date', '=', now()->year)
        //                 ->get();
        
        // $events = $this->getEvent($emergency);
        
        // if(!empty($request->username)){
        //     $user = DB::table('users')
        //             ->where('username','=',$request->username);
        //     if($user->exists()){
        //         $user = User::where('username',$request->username);
        //         Auth::login($user->first());
        //     }
        // }
        return view('hisdb.otmanagement.otmanagement');
        
    }
    
    public function get_table_operRecList($request){
        
        $table_apptbook = DB::table('hisdb.apptbook')
                    ->select(['apptbook.idno','apptbook.compcode','apptbook.mrn','apptbook.pat_name','apptbook.Type','apptbook.episno','apptbook.ot_room',
                    'apptbook.surgery_date','apptbook.op_unit','apptbook.oper_type','apptbook.oper_status']);
                
                $table_apptbook = $table_apptbook->leftJoin('nursing.otmanage', function($join) use ($request){
                        $join = $join->on('otmanage.mrn', '=', 'apptbook.mrn');
                        // $join = $join->where(
                        //         function($query){
                        //             return $query
                        //                     ->whereNull('episode.episstatus')
                        //                     ->orWhere('episode.episstatus','!=','C');
                        //         }
                        // );
                });
                
                $table_apptbook = $table_apptbook->where('apptbook.compcode','=',session('compcode'))
                                                ->where('apptbook.Type','=','OT')
                                                ->where('apptbook.surgery_date' ,'=', $request->filterVal[0]);
                
                if(!empty($request->sidx)){
                    $table_apptbook = $table_apptbook->orderBy($request->sidx, $request->sord);
                }else{
                    $table_apptbook = $table_apptbook->orderBy('apptbook.idno', 'desc');
                }
        
        //////////paginate//////////
        $paginate = $table_apptbook->paginate($request->rows);
        
        $responce = new stdClass();
        $responce->page = $paginate->currentPage();
        $responce->total = $paginate->lastPage();
        $responce->records = $paginate->total();
        $responce->rows = $paginate->items();
        $responce->sql = $table_apptbook->toSql();
        $responce->sql_bind = $table_apptbook->getBindings();
        return json_encode($responce);
        
    }
    
    public function getEvent($obj){
        
        $events = [];
        
        for ($i=1; $i <= 31; $i++) {
            $days = 0;
            $apptdateto;
            foreach ($obj as $key => $value) {
                $day = Carbon::createFromFormat('Y-m-d',$value->apptdateto);
                if($day->day == $i){
                    $apptdateto = $value->apptdateto;
                    $days++;
                }
            }
            if($days != 0){
                $event = new stdClass();
                $event->title = $days.' patients';
                $event->start = $apptdateto;
                array_push($events, $event);
            }
        }
        
        return $events;
    
    }
    
    public function add(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            // $queries = DB::getQueryLog();
            // dump($queries);
            
            DB::commit();
        
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }

    public function edit(Request $request){
        
        DB::beginTransaction();
        
        try {
            
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
    
    public function operRecList_event(Request $request){
        
        $apptbook = DB::table('hisdb.apptbook')
                        ->where('compcode','=',session('compcode'))
                        ->whereRaw(
                            "(apptdateto >= ? AND apptdateto <= ?)",
                            [
                                $request->start,
                                $request->end
                            ])
                        ->where('Type','=','OT')
                        // ->whereIn('episode.episstatus', [null,'C','B'])
                        // ->whereNull('episode.episstatus')
                        // ->orWhere('episode.episstatus','!=','C')
                        // ->where(
                        //         function($query){
                        //             return $query
                        //                     ->whereNull('episode.episstatus')
                        //                     ->orWhere('episode.episstatus','!=','C');
                        //         }
                        // )
                        ->get();
        
        return $events = $this->getEvent($apptbook);
        
    }
    
}
