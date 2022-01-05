<?php

namespace App\Http\Controllers\setup;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class mmamaintenanceDetailController extends defaultController
{   
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function form(Request $request)
    {   
        switch($request->oper){
            case 'add':
                return $this->add($request);

            case 'edit_all':
                return $this->edit_all($request);

            case 'del':
                return $this->del($request);

            default:
                return 'error happen..';
        }
    }

    public function add(Request $request){
        DB::beginTransaction();

        try {

            $sqlln = DB::table('hisdb.mmaprice')->select('lineno_')
                        ->where('compcode','=',session('compcode'))
                        ->where('mmacode','=',$request->mmacode)
                        ->count('lineno_');

            $li=intval($sqlln)+1;

            if($request->action == 'save_table_default'){ //ada yang save from form , ada yang save inline
                $effectdate = $request->effectdate;
            }else{
                $effectdate = $this->turn_date($request->effectdate);
            }

            $mmacode = DB::table('hisdb.mmamaster')
                        ->where('compcode','=',session('compcode'))
                        ->where('mmacode','=',$request->mmacode);

            if(!$mmacode->exists()){
                throw new \Exception('MMA Code not exist', 500);
            }

            DB::table('hisdb.mmaprice')
                ->insert([
                    'lineno_' => $li,
                    'compcode' => session('compcode'),
                    'mmacode' => $request->mmacode,
                    'effectdate' => $effectdate,
                    'version' => $request->version,
                    'mmaconsult' => $request->mmaconsult,
                    'mmasurgeon' => $request->mmasurgeon,
                    'mmaanaes' => $request->mmaanaes,
                    'feesconsult' => $request->feesconsult,
                    'feessurgeon' => $request->feessurgeon,
                    'feesanaes' => $request->feesanaes,
                    //'recstatus' => 'ACTIVE',
                    //'unit' => session('unit'),
                    'adduser' => session('username'), 
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'lastuser' => session('username'), 
                    'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function edit_all(Request $request){
        DB::beginTransaction();

        try {

            foreach ($request->dataobj as $key => $value) {
               
                ///1. update detail
                DB::table('hisdb.mmaprice')
                    ->where('compcode','=',session('compcode'))
                    ->where('idno','=',$value['idno'])
                    ->update([
                        'effectdate' => $this->turn_date($value['effectdate']),
                        'mmaconsult' => $value['mmaconsult'],
                        'mmasurgeon' => $value['mmasurgeon'],
                        'mmaanaes' => $value['mmaanaes'],
                        'feesconsult' => $value['feesconsult'],
                        'feessurgeon' => $value['feessurgeon'],
                        'feesanaes' => $value['feesanaes'],
                        'lastuser' => session('username'), 
                        'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    ]);
            }      

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function del(Request $request){
        DB::beginTransaction();

        try {

            ///1. update detail
            DB::table('hisdb.mmaprice')
                ->where('compcode','=',session('compcode'))
                ->where('idno','=',$request->idno)
                ->update([
                    'deluser' => session('username'),
                    'deldate' => Carbon::now("Asia/Kuala_Lumpur"),
                    //'recstatus' => 'DEACTIVE',
                ]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }
}

