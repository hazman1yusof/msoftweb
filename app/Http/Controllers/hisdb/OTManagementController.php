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
            case 'edit_header_ot':
                return $this->edit_header_ot($request);
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
        
        $otstatus = DB::table('hisdb.otstatus')
                    ->select('code','description')
                    ->where('compcode','=',session('compcode'))
                    ->get();
        
        return view('hisdb.otmanagement.otmanagement',compact('otstatus'));
        
    }
    
    public function get_table_operRecList($request){
        
        $episode = DB::table('hisdb.episode')
                        ->select('episode.episno', 'episode.ward')
                        ->leftJoin('hisdb.apptbook', 'apptbook.mrn', '=', 'episode.mrn')
                        ->where('episode.compcode','=',session('compcode'))
                        ->where('episode.reg_date','<=','apptbook.surgery_date')
                        ->first();
        
        dd($episode);
        
        $table_apptbook = DB::table('hisdb.apptbook')
                    ->select(['apptbook.idno','apptbook.compcode','apptbook.icnum','apptbook.mrn','apptbook.pat_name','apptbook.Type','apptbook.episno',
                    'apptbook.ot_room','apptbook.surgery_date','apptbook.op_unit','apptbook.oper_type','apptbook.oper_status','apptbook.procedure as appt_prcdure',
                    'apptbook.diagnosis as appt_diag','apptbook.height','apptbook.weight','pat_mast.Name','pat_mast.MRN','pat_mast.Episno','pat_mast.Newic',
                    'pat_mast.Sex','pat_mast.DOB','pat_mast.RaceCode','pat_mast.Religion','pat_mast.OccupCode','pat_mast.Citizencode','pat_mast.AreaCode',
                    'apptresrc.resourcecode','apptresrc.description as ot_description','discipline.code','discipline.description as unit_description',
                    'otmanage.diagnosis as ot_diag','otmanage.procedure as ot_prcdure']);
                
                $table_apptbook = $table_apptbook->leftJoin('hisdb.pat_mast', function($join) use ($request){
                        $join = $join->on('pat_mast.MRN', '=', 'apptbook.mrn')
                                    ->on('pat_mast.compcode', '=', 'apptbook.compcode');
                })
                ->leftJoin('hisdb.apptresrc', function($join) use ($request){
                    $join = $join->on('apptresrc.resourcecode', '=', 'apptbook.ot_room');
                })
                ->leftJoin('hisdb.discipline', function($join) use ($request){
                    $join = $join->on('discipline.code', '=', 'apptbook.op_unit');
                })
                ->leftJoin('nursing.otmanage', function($join) use ($request){
                    $join = $join->on('otmanage.mrn', '=', 'apptbook.mrn')
                                ->on('otmanage.compcode', '=', 'apptbook.compcode');
                });
                
                $table_apptbook = $table_apptbook->where('apptbook.compcode','=',session('compcode'))
                                                ->where('apptbook.Type','=','OT')
                                                ->where('apptbook.apptdateto' ,'=', $request->filterVal[0]);
                
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
    
    public function edit_header_ot(Request $request){
        
        DB::table('hisdb.apptbook')
            ->where('idno',$request->idno)
            ->update([
                'op_unit' => $request->op_unit,
                'oper_type' => $request->oper_type,
                'oper_status' => $request->oper_status,
                'height' => $request->height,
                'weight' => $request->weight,
            ]);
    
    }
    
}
