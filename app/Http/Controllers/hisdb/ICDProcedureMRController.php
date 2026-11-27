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

class ICDProcedureMRController extends defaultController
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function show(Request $request){
        return view('hisdb.icdprocedure_MR.icdprocedure_MR');
    }
    
    public function table(Request $request)
    {
        switch($request->action){
            case 'get_table_finalDiag':
                return $this->get_table_finalDiag($request);
            
            case 'get_table_externalCause':
                return $this->get_table_externalCause($request);
            
            case 'get_table_underCause':
                return $this->get_table_underCause($request);
            
            case 'get_table_procedure':
                return $this->get_table_procedure($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function form(Request $request)
    {
        switch($request->action){
            case 'finalDiag_save':
                return $this->add_FinalDiag($request);

            case 'finalDiag_edit':
                return $this->edit_FinalDiag($request);

            case 'finalDiag_del':
                return $this->del_FinalDiag($request);

            case 'externalCause_save':
                return $this->add_externalCause($request);

            case 'externalCause_edit':
                return $this->edit_externalCause($request);

            case 'externalCause_del':
                return $this->del_externalCause($request);

            case 'underCause_save':
                return $this->add_underCause($request);

            case 'underCause_edit':
                return $this->edit_underCause($request);

            case 'underCause_del':
                return $this->del_underCause($request);

            case 'procedure_save':
                return $this->add_procedure($request);

            case 'procedure_edit':
                return $this->edit_procedure($request);

            case 'procedure_del':
                return $this->del_procedure($request);
            
            default:
                return 'error happen..';
        }
    }

    ////////////////Final Diagnosis////////////////
    
    public function add_FinalDiag(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $diagtab_obj = DB::table('hisdb.diagtab')
                            ->where('icdcode','=',$request->icdcode);
                            // ->where('compcode','=',session('compcode'));
            
            if(!$diagtab_obj->exists()){
                DB::table('hisdb.diagtab')
                    ->insert([
                        // 'compcode' => session('compcode'),
                        'icdcode' => $request->icdcode,
                        'type' => 'icd-11',
                        'description' => $request->description,
                        'recstatus' => 'ACTIVE',
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur"),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
            }else{
                DB::table('hisdb.episdiag')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'icdcode' => $request->icdcode,
                        'type' => 'DIAG',
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur"),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        // 'computerid' => session('computerid'),
                    ]);
            }
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
        
    }

    public function edit_FinalDiag(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('hisdb.episdiag')
                ->where('seq','=',$request->seq)
                ->update([
                    'icdcode' => $request->icdcode,
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }

    public function del_FinalDiag(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            // $diagtab_diag = DB::table('hisdb.episdiag')
            //             ->where('seq','=',$request->seq)
            //             ->where('compcode','=',session('compcode'))
            //             ->first();

            // if($diagtab_diag->adduser !== session('username')){
            //     throw new \Exception("You are not authorized to delete this data.",500);
            // } else{
            //     DB::table('hisdb.episdiag')
            //         ->where('compcode','=',session('compcode'))
            //         ->where('seq','=',$request->seq)
            //         ->delete();
            // }

            DB::table('hisdb.episdiag')
                    ->where('compcode','=',session('compcode'))
                    ->where('seq','=',$request->seq)
                    ->delete();
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
        
    }

    ////////////////External Cause////////////////

    public function add_externalCause(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $diagtab_obj = DB::table('hisdb.diagtab')
                            ->where('icdcode','=',$request->icdcode);
                            // ->where('compcode','=',session('compcode'));
            
            if(!$diagtab_obj->exists()){
                DB::table('hisdb.diagtab')
                    ->insert([
                        // 'compcode' => session('compcode'),
                        'icdcode' => $request->icdcode,
                        'type' => 'icd-11',
                        'description' => $request->description,
                        'recstatus' => 'ACTIVE',
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur"),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
            }else{
                DB::table('hisdb.episdiag')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'icdcode' => $request->icdcode,
                        'type' => 'ECAUSE',
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur"),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        // 'computerid' => session('computerid'),
                    ]);
            }
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
        
    }

    public function edit_externalCause(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('hisdb.episdiag')
                ->where('seq','=',$request->seq)
                ->update([
                    'icdcode' => $request->icdcode,
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }

    public function del_externalCause(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            // $diagtab_diag = DB::table('hisdb.episdiag')
            //             ->where('seq','=',$request->seq)
            //             ->where('compcode','=',session('compcode'))
            //             ->first();

            // if($diagtab_diag->adduser !== session('username')){
            //     throw new \Exception("You are not authorized to delete this data.",500);
            // } else{
            //     DB::table('hisdb.episdiag')
            //         ->where('compcode','=',session('compcode'))
            //         ->where('seq','=',$request->seq)
            //         ->delete();
            // }

            DB::table('hisdb.episdiag')
                    ->where('compcode','=',session('compcode'))
                    ->where('seq','=',$request->seq)
                    ->delete();
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
        
    }
    
    ////////////////Underlying Cause////////////////

    public function add_underCause(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $diagtab_obj = DB::table('hisdb.diagtab')
                            ->where('icdcode','=',$request->icdcode);
                            // ->where('compcode','=',session('compcode'));
            
            if(!$diagtab_obj->exists()){
                DB::table('hisdb.diagtab')
                    ->insert([
                        // 'compcode' => session('compcode'),
                        'icdcode' => $request->icdcode,
                        'type' => 'icd-11',
                        'description' => $request->description,
                        'recstatus' => 'ACTIVE',
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur"),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
            }else{
                DB::table('hisdb.episdiag')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'icdcode' => $request->icdcode,
                        'type' => 'UCAUSE',
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur"),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        // 'computerid' => session('computerid'),
                    ]);
            }
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
        
    }

    public function edit_underCause(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('hisdb.episdiag')
                ->where('seq','=',$request->seq)
                ->update([
                    'icdcode' => $request->icdcode,
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }

    public function del_underCause(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            // $diagtab_diag = DB::table('hisdb.episdiag')
            //             ->where('seq','=',$request->seq)
            //             ->where('compcode','=',session('compcode'))
            //             ->first();

            // if($diagtab_diag->adduser !== session('username')){
            //     throw new \Exception("You are not authorized to delete this data.",500);
            // } else{
            //     DB::table('hisdb.episdiag')
            //         ->where('compcode','=',session('compcode'))
            //         ->where('seq','=',$request->seq)
            //         ->delete();
            // }

            DB::table('hisdb.episdiag')
                    ->where('compcode','=',session('compcode'))
                    ->where('seq','=',$request->seq)
                    ->delete();
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
        
    }
    
    ////////////////Procedure////////////////

    public function add_procedure(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $diagtab_obj = DB::table('hisdb.diagtab')
                            ->where('icdcode','=',$request->icdcode);
                            // ->where('compcode','=',session('compcode'));
            
            if(!$diagtab_obj->exists()){
                DB::table('hisdb.diagtab')
                    ->insert([
                        // 'compcode' => session('compcode'),
                        'icdcode' => $request->icdcode,
                        'type' => 'icd-11',
                        'description' => $request->description,
                        'recstatus' => 'ACTIVE',
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur"),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
            }else{
                DB::table('hisdb.episdiag')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'icdcode' => $request->icdcode,
                        'type' => 'PROC',
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur"),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        // 'computerid' => session('computerid'),
                    ]);
            }
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
        
    }

    public function edit_procedure(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('hisdb.episdiag')
                ->where('seq','=',$request->seq)
                ->update([
                    'icdcode' => $request->icdcode,
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }

    public function del_procedure(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            // $diagtab_diag = DB::table('hisdb.episdiag')
            //             ->where('seq','=',$request->seq)
            //             ->where('compcode','=',session('compcode'))
            //             ->first();

            // if($diagtab_diag->adduser !== session('username')){
            //     throw new \Exception("You are not authorized to delete this data.",500);
            // } else{
            //     DB::table('hisdb.episdiag')
            //         ->where('compcode','=',session('compcode'))
            //         ->where('seq','=',$request->seq)
            //         ->delete();
            // }

            DB::table('hisdb.episdiag')
                    ->where('compcode','=',session('compcode'))
                    ->where('seq','=',$request->seq)
                    ->delete();
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
        
    }

    public function get_table_finalDiag(Request $request){
        
        $table = DB::table('hisdb.episdiag')
                ->leftJoin('hisdb.diagtab', function($join) use ($request){
                    $join = $join->on('diagtab.icdcode','=','episdiag.icdcode');
                })
                ->where('episdiag.mrn',$request->mrn)
                ->where('episdiag.episno',$request->episno)
                ->where('episdiag.type',"DIAG");
        
        $table = $table->orderBy($request->sidx, $request->sord);
        
        //////////paginate//////////
        $paginate = $table->paginate($request->rows);
        
        $responce = new stdClass();
        $responce->page = $paginate->currentPage();
        $responce->total = $paginate->lastPage();
        $responce->records = $paginate->total();
        $responce->rows = $paginate->items();
        $responce->sql = $table->toSql();
        $responce->sql_bind = $table->getBindings();
        
        return json_encode($responce);
        
    }
    
    public function get_table_externalCause(Request $request){
        
        $table = DB::table('hisdb.episdiag')
                ->leftJoin('hisdb.diagtab', function($join) use ($request){
                    $join = $join->on('diagtab.icdcode','=','episdiag.icdcode');
                })
                ->where('episdiag.mrn',$request->mrn)
                ->where('episdiag.episno',$request->episno)
                ->where('episdiag.type',"ECAUSE");
        
        $table = $table->orderBy($request->sidx, $request->sord);
        
        //////////paginate//////////
        $paginate = $table->paginate($request->rows);
        
        $responce = new stdClass();
        $responce->page = $paginate->currentPage();
        $responce->total = $paginate->lastPage();
        $responce->records = $paginate->total();
        $responce->rows = $paginate->items();
        $responce->sql = $table->toSql();
        $responce->sql_bind = $table->getBindings();
        
        return json_encode($responce);
        
    }
    
    public function get_table_underCause(Request $request){
        
        $table = DB::table('hisdb.episdiag')
                ->leftJoin('hisdb.diagtab', function($join) use ($request){
                    $join = $join->on('diagtab.icdcode','=','episdiag.icdcode');
                })
                ->where('episdiag.mrn',$request->mrn)
                ->where('episdiag.episno',$request->episno)
                ->where('episdiag.type',"UCAUSE");
        
        $table = $table->orderBy($request->sidx, $request->sord);
        
        //////////paginate//////////
        $paginate = $table->paginate($request->rows);
        
        $responce = new stdClass();
        $responce->page = $paginate->currentPage();
        $responce->total = $paginate->lastPage();
        $responce->records = $paginate->total();
        $responce->rows = $paginate->items();
        $responce->sql = $table->toSql();
        $responce->sql_bind = $table->getBindings();
        
        return json_encode($responce);
        
    }
    
    public function get_table_procedure(Request $request){
        
        $table = DB::table('hisdb.episdiag')
                ->leftJoin('hisdb.diagtab', function($join) use ($request){
                    $join = $join->on('diagtab.icdcode','=','episdiag.icdcode');
                })
                ->where('episdiag.mrn',$request->mrn)
                ->where('episdiag.episno',$request->episno)
                ->where('episdiag.type',"PROC");
        
        $table = $table->orderBy($request->sidx, $request->sord);
        
        //////////paginate//////////
        $paginate = $table->paginate($request->rows);
        
        $responce = new stdClass();
        $responce->page = $paginate->currentPage();
        $responce->total = $paginate->lastPage();
        $responce->records = $paginate->total();
        $responce->rows = $paginate->items();
        $responce->sql = $table->toSql();
        $responce->sql_bind = $table->getBindings();
        
        return json_encode($responce);
        
    }
    
}