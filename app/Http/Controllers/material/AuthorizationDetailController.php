<?php

namespace App\Http\Controllers\material;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class AuthorizationDetailController extends defaultController
{   
    var $gltranAmount;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function form(Request $request)
    {   
        switch($request->oper){
            case 'add':
                return $this->add($request);
            case 'edit':
                return $this->edit($request);
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
            ////1. calculate lineno_ by idno
            $sqlln = DB::table('material.authdtl')->select('lineno_')
                        ->where('dtl_compcode','=',session('compcode'))
                        ->where('dtl_idno','=',$idno)
                        ->count('dtl_lineno_');

            $li=intval($sqlln)+1;

            ///2. insert detail
            DB::table('material.authdtl')
                ->insert([
                    'dtl_compcode' => session('compcode'),
                    'dtl_idno' => $idno,
                    'dtl_lineno_' => $li,
                    'dtl_trantype' => $request->trantype,
                    'dtl_deptcode' => $request->deptcode,
                    'dtl_id' => $request->authorid,
                    'dtl_recstatus' => $request->recstatus,
                    'dtl_cando' => $request->cando,
                    'dtl_minlimit' => $request->minlimit,
                    'dtl_maxlimit' => $request->maxlimit,
                ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }
    }

    public function edit(Request $request){

        DB::beginTransaction();

        try {

            ///1. update detail
            DB::table('material.authdtl')
                ->where('compcode','=',session('compcode'))
                ->where('idno','=',$request->idno)
                ->where('lineno_','=',$request->lineno_)
                ->update([
                    'compcode' => session('compcode'),
                    'idno' => $idno,
                    'lineno_' => $li,
                    'trantype' => $request->trantype,
                    'deptcode' => $request->deptcode,
                    'id' => $request->authorid,
                    'recstatus' => $request->recstatus,
                    'cando' => $request->cando,
                    'minlimit' => $request->minlimit,
                    'maxlimit' => $request->maxlimit,
                ]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }

    }

    public function edit_all(Request $request){

        DB::beginTransaction();

        try {

            foreach ($request->dataobj as $key => $value) {
                ///1. update detail
                DB::table('material.authdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('idno','=',$request->idno)
                    ->where('lineno_','=',$value['lineno_'])
                    ->update([
                        'trantype' => $value['trantype'],
                        'deptcode' => $value['deptcode'],
                        'id' => $value['id'],
                        'recstatus' => $value['recstatus'],
                        'cando' => $value['cando'],
                        'minlimit' => $value['minlimit'],
                        'maxlimit' => $value['maxlimit'],
                    ]);
            }
         

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }

    }

    public function del(Request $request){

        DB::beginTransaction();

        try {

            ///1. update detail
            DB::table('material.authdtl')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->where('lineno_','=',$request->lineno_)
                ->update([ 
                    'deluser' => session('username'), 
                    'deldate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'recstatus' => 'DELETE'
                ]);

            ///2. recalculate total amount
            $totalAmount = DB::table('material.authdtl')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->where('recstatus','!=','DELETE')
                ->sum('totamount');

            //calculate tot gst from detail
            $tot_gst = DB::table('material.authdtl')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$recno)
                ->where('recstatus','!=','DELETE')
                ->sum('amtslstax');

            ///3. update total amount to header
            DB::table('material.delordhd')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$recno)
                ->update([
                    'totamount' => $totalAmount, 
                    'subamount'=> $totalAmount, 
                    'TaxAmt' => $tot_gst
                ]);

            echo $totalAmount;

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }

        
    }

}

