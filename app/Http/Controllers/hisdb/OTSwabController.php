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

class OTSwabController extends defaultController
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function show(Request $request)
    {
        return view('hisdb.otswab.otswab');
    }
    
    public function table(Request $request)
    {
        switch($request->action){
            // case 'get_grid_otswab':
            //     return $this->get_grid_otswab($request);
            default:
                return 'error happen..';
        }
    }
    
    public function form(Request $request)
    {
        DB::enableQueryLog();
        switch($request->action){
            case 'save_table_otswab':
            
                switch($request->oper){
                    case 'add':
                        return $this->add($request);
                    case 'edit':
                        return $this->edit($request);
                    default:
                        return 'error happen..';
                }
                
            case 'get_table_otswab':
                return $this->get_table_otswab($request);
            
            case 'addJqgrid_save':
                return $this->add_jqgrid($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function add(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.otswab')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_otswab,
                        'episno' => $request->episno_otswab,
                        'startdate' => $request->startdate,
                        'starttime' => $request->starttime,
                        'enddate' => $request->enddate,
                        'endtime' => $request->endtime,
                        'basicset' => $request->basicset,
                        'spplmtryset' => $request->spplmtryset,
                        'issue_occur' => $request->issue_occur,
                        'actual_oper' => $request->actual_oper,
                        'specimensent' => $request->specimensent,
                        'scrubnurse1' => $request->scrubnurse1,
                        'scrubnrs1_start' => $request->scrubnrs1_start,
                        'scrubnrs1_end' => $request->scrubnrs1_end,
                        'scrubnurse2' => $request->scrubnurse2,
                        'scrubnrs2_start' => $request->scrubnrs2_start,
                        'scrubnrs2_end' => $request->scrubnrs2_end,
                        'scrubnurse3' => $request->scrubnurse3,
                        'scrubnrs3_start' => $request->scrubnrs3_start,
                        'scrubnrs3_end' => $request->scrubnrs3_end,
                        'circltnurse1' => $request->circltnurse1,
                        'circltnrs1_start' => $request->circltnrs1_start,
                        'circltnrs1_end' => $request->circltnrs1_end,
                        'circltnurse2' => $request->circltnurse2,
                        'circltnrs2_start' => $request->circltnrs2_start,
                        'circltnrs2_end' => $request->circltnrs2_end,
                        'circltnurse3' => $request->circltnurse3,
                        'circltnrs3_start' => $request->circltnrs3_start,
                        'circltnrs3_end' => $request->circltnrs3_end,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
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
            
            DB::table('nursing.otswab')
                ->where('mrn','=',$request->mrn_otswab)
                ->where('episno','=',$request->episno_otswab)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'startdate' => $request->startdate,
                    'starttime' => $request->starttime,
                    'enddate' => $request->enddate,
                    'endtime' => $request->endtime,
                    'basicset' => $request->basicset,
                    'spplmtryset' => $request->spplmtryset,
                    'issue_occur' => $request->issue_occur,
                    'actual_oper' => $request->actual_oper,
                    'specimensent' => $request->specimensent,
                    'scrubnurse1' => $request->scrubnurse1,
                    'scrubnrs1_start' => $request->scrubnrs1_start,
                    'scrubnrs1_end' => $request->scrubnrs1_end,
                    'scrubnurse2' => $request->scrubnurse2,
                    'scrubnrs2_start' => $request->scrubnrs2_start,
                    'scrubnrs2_end' => $request->scrubnrs2_end,
                    'scrubnurse3' => $request->scrubnurse3,
                    'scrubnrs3_start' => $request->scrubnrs3_start,
                    'scrubnrs3_end' => $request->scrubnrs3_end,
                    'circltnurse1' => $request->circltnurse1,
                    'circltnrs1_start' => $request->circltnrs1_start,
                    'circltnrs1_end' => $request->circltnrs1_end,
                    'circltnurse2' => $request->circltnurse2,
                    'circltnrs2_start' => $request->circltnrs2_start,
                    'circltnrs2_end' => $request->circltnrs2_end,
                    'circltnurse3' => $request->circltnurse3,
                    'circltnrs3_start' => $request->circltnrs3_start,
                    'circltnrs3_end' => $request->circltnrs3_end,
                    'upduser'  => session('username'),
                    'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                ]);
            
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
    
    public function get_table_otswab(Request $request){
        
        $otswab_obj = DB::table('nursing.otswab')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$request->mrn)
                    ->where('episno','=',$request->episno);
                    
        $responce = new stdClass();
        
        if($otswab_obj->exists()){
            $otswab_obj = $otswab_obj->first();
            $responce->otswab = $otswab_obj;
        }
        
        return json_encode($responce);
    
    }
    
    // public function get_grid_otswab(Request $request){
        
    //     $table = DB::table('nursing.otswab')
    //                 ->select('idno','compcode','mrn','episno','items','count_initial','add_1','count_1st','add_2','count_2nd','add_3','count_final','adduser','adddate')
    //                 ->where('compcode','=',session('compcode'))
    //                 ->where('mrn','=',$request->mrn)
    //                 ->where('episno','=',$request->episno);
        
    //     /////////////////paginate/////////////////
    //     $paginate = $table->paginate($request->rows);
        
    //     $responce = new stdClass();
    //     $responce->page = $paginate->currentPage();
    //     $responce->total = $paginate->lastPage();
    //     $responce->records = $paginate->total();
    //     $responce->rows = $paginate->items();
    //     $responce->sql = $table->toSql();
    //     $responce->sql_bind = $table->getBindings();
    //     $responce->sql_query = $this->getQueries($table);
        
    //     return json_encode($responce);
        
    // }
    
    public function add_jqgrid(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $otswab = DB::table('nursing.otswab')
                        ->where('mrn','=',$request->mrn_otswab)
                        ->where('episno','=',$request->episno_otswab)
                        ->where('compcode','=',session('compcode'));
            
            if(!$otswab->exists()){
                
                DB::table('nursing.otswab')
                        ->insert([
                            'compcode' => session('compcode'),
                            'mrn' => $request->mrn_otswab,
                            'episno' => $request->episno_otswab,
                            'items' => $request->items,
                            'count_initial' => $request->count_initial,
                            'add_1' => $request->add_1,
                            'count_1st' => $request->count_1st,
                            'add_2' => $request->add_2,
                            'count_2nd' => $request->count_2nd,
                            'add_3' => $request->add_3,
                            'count_final' => $request->count_final,
                            'adduser'  => session('username'),
                            'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        ]);
                
            }else{
                
                $otswab
                    ->update([
                        'items' => $request->items,
                        'count_initial' => $request->count_initial,
                        'add_1' => $request->add_1,
                        'count_1st' => $request->count_1st,
                        'add_2' => $request->add_2,
                        'count_2nd' => $request->count_2nd,
                        'add_3' => $request->add_3,
                        'count_final' => $request->count_final,
                        'upduser'  => session('username'),
                        'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
                    
            }
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
    
    }
    
}