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
            
            case 'addJqgrid_edit':
                return $this->edit_jqgrid($request);
            
            case 'addJqgrid_delete':
                return $this->del_jqgrid($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function add_lama(Request $request){
        
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
                    'supplemntryset' => $request->supplemntryset,
                    'issuesOccured' => $request->issuesOccured,
                    'actualOper' => $request->actualOper,
                    'specimenSent' => $request->specimenSent,
                    'scrubNurse1' => $request->scrubNurse1,
                    'scrubNurse1Start' => $request->scrubNurse1Start,
                    'scrubNurse1End' => $request->scrubNurse1End,
                    'scrubNurse2' => $request->scrubNurse2,
                    'scrubNurse2Start' => $request->scrubNurse2Start,
                    'scrubNurse2End' => $request->scrubNurse2End,
                    'scrubNurse3' => $request->scrubNurse3,
                    'scrubNurse3Start' => $request->scrubNurse3Start,
                    'scrubNurse3End' => $request->scrubNurse3End,
                    'circulateNurse1' => $request->circulateNurse1,
                    'circulateNurse1Start' => $request->circulateNurse1Start,
                    'circulateNurse1End' => $request->circulateNurse1End,
                    'circulateNurse2' => $request->circulateNurse2,
                    'circulateNurse2Start' => $request->circulateNurse2Start,
                    'circulateNurse2End' => $request->circulateNurse2End,
                    'circulateNurse3' => $request->circulateNurse3,
                    'circulateNurse3Start' => $request->circulateNurse3Start,
                    'circulateNurse3End' => $request->circulateNurse3End,
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
    
    public function edit_lama(Request $request){
        
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
                    'supplemntryset' => $request->supplemntryset,
                    'issuesOccured' => $request->issuesOccured,
                    'actualOper' => $request->actualOper,
                    'specimenSent' => $request->specimenSent,
                    'scrubNurse1' => $request->scrubNurse1,
                    'scrubNurse1Start' => $request->scrubNurse1Start,
                    'scrubNurse1End' => $request->scrubNurse1End,
                    'scrubNurse2' => $request->scrubNurse2,
                    'scrubNurse2Start' => $request->scrubNurse2Start,
                    'scrubNurse2End' => $request->scrubNurse2End,
                    'scrubNurse3' => $request->scrubNurse3,
                    'scrubNurse3Start' => $request->scrubNurse3Start,
                    'scrubNurse3End' => $request->scrubNurse3End,
                    'circulateNurse1' => $request->circulateNurse1,
                    'circulateNurse1Start' => $request->circulateNurse1Start,
                    'circulateNurse1End' => $request->circulateNurse1End,
                    'circulateNurse2' => $request->circulateNurse2,
                    'circulateNurse2Start' => $request->circulateNurse2Start,
                    'circulateNurse2End' => $request->circulateNurse2End,
                    'circulateNurse3' => $request->circulateNurse3,
                    'circulateNurse3Start' => $request->circulateNurse3Start,
                    'circulateNurse3End' => $request->circulateNurse3End,
                    'upduser'  => session('username'),
                    'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'computerid' => session('computerid'),
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
    
    public function add(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.otswab')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn_otswab,
                    'episno' => $request->episno_otswab,
                    'iPesakit' => $request->iPesakit,
                    'basicset' => $request->basicset,
                    'supplemntryset' => $request->supplemntryset,
                    'actualOper' => $request->actualOper,
                    'specimenSent' => $request->specimenSent,
                    'issuesOccured' => $request->issuesOccured,
                    'scrubNurse1' => $request->scrubNurse1,
                    'scrubNurse2' => $request->scrubNurse2,
                    'circulateNurse1' => $request->circulateNurse1,
                    'adduser'  => session('username'),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'computerid' => session('computerid'),
                ]);
            
            if(!empty($request->iPesakit)){
                $pat_mast = DB::table('hisdb.pat_mast')
                            ->where('CompCode',session('compcode'))
                            ->where('MRN',$request->mrn_otswab)
                            ->first();
                
                if($pat_mast->iPesakit != $request->iPesakit){
                    DB::table('hisdb.pat_mast')
                        ->where('CompCode',session('compcode'))
                        ->where('MRN',$request->mrn_otswab)
                        ->update([
                            'iPesakit' => $request->iPesakit,
                        ]);
                }
            }
            
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
                    'iPesakit' => $request->iPesakit,
                    'basicset' => $request->basicset,
                    'supplemntryset' => $request->supplemntryset,
                    'actualOper' => $request->actualOper,
                    'specimenSent' => $request->specimenSent,
                    'issuesOccured' => $request->issuesOccured,
                    'scrubNurse1' => $request->scrubNurse1,
                    'scrubNurse2' => $request->scrubNurse2,
                    'circulateNurse1' => $request->circulateNurse1,
                    'upduser'  => session('username'),
                    'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'computerid' => session('computerid'),
                ]);
            
            if(!empty($request->iPesakit)){
                $pat_mast = DB::table('hisdb.pat_mast')
                            ->where('CompCode',session('compcode'))
                            ->where('MRN',$request->mrn_otswab)
                            ->first();
                
                if($pat_mast->iPesakit != $request->iPesakit){
                    DB::table('hisdb.pat_mast')
                        ->where('CompCode',session('compcode'))
                        ->where('MRN',$request->mrn_otswab)
                        ->update([
                            'iPesakit' => $request->iPesakit,
                        ]);
                }
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
    
    public function get_table_otswab(Request $request){
        
        $otswab_obj = DB::table('nursing.otswab')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$request->mrn)
                    ->where('episno','=',$request->episno);
        
        $patmast_obj = DB::table('hisdb.pat_mast')
                        ->select('iPesakit')
                        ->where('compcode',session('compcode'))
                        ->where('mrn','=',$request->mrn);
        
        $responce = new stdClass();
        
        if($otswab_obj->exists()){
            $otswab_obj = $otswab_obj->first();
            $responce->otswab = $otswab_obj;
        }
        
        if($patmast_obj->exists()){
            $patmast_obj = $patmast_obj->first();
            
            $iPesakit = $patmast_obj->iPesakit;
            $responce->iPesakit = $iPesakit;
        }
        
        return json_encode($responce);
    
    }
    
    // public function get_grid_otswab(Request $request){
        
    //     $table = DB::table('nursing.otswab')
    //                 ->select('idno','compcode','mrn','episno','items','countInitial','add1','count1st','add2','count2nd','add3','countFinal','adduser','adddate')
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
            
            DB::table('nursing.otswab_sets')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn,
                    'episno' => $request->episno,
                    // 'iPesakit' => $request->iPesakit,
                    'items' => $request->items,
                    'countInitial' => $request->countInitial,
                    'add1' => $request->add1,
                    'add2' => $request->add2,
                    'add3' => $request->add3,
                    'add4' => $request->add4,
                    'count1st' => $request->count1st,
                    'add5' => $request->add5,
                    'add6' => $request->add6,
                    'add7' => $request->add7,
                    'add8' => $request->add8,
                    'count2nd' => $request->count2nd,
                    'add9' => $request->add9,
                    'add10' => $request->add10,
                    'add11' => $request->add11,
                    'add12' => $request->add12,
                    'countFinal' => $request->countFinal,
                    'adduser'  => session('username'),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'computerid' => session('computerid'),
                ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
    
    }
    
    public function edit_jqgrid(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.otswab_sets')
                ->where('idno','=',$request->idno)
                ->where('compcode','=',session('compcode'))
                ->update([
                    // 'iPesakit' => $request->iPesakit,
                    'items' => $request->items,
                    'countInitial' => $request->countInitial,
                    'add1' => $request->add1,
                    'add2' => $request->add2,
                    'add3' => $request->add3,
                    'add4' => $request->add4,
                    'count1st' => $request->count1st,
                    'add5' => $request->add5,
                    'add6' => $request->add6,
                    'add7' => $request->add7,
                    'add8' => $request->add8,
                    'count2nd' => $request->count2nd,
                    'add9' => $request->add9,
                    'add10' => $request->add10,
                    'add11' => $request->add11,
                    'add12' => $request->add12,
                    'countFinal' => $request->countFinal,
                    'upduser'  => session('username'),
                    'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'computerid' => session('computerid'),
                ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
    
    }
    
    public function del_jqgrid(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.otswab_sets')
                ->where('idno','=',$request->idno)
                ->where('compcode','=',session('compcode'))
                ->delete();
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
    
    }
    
}