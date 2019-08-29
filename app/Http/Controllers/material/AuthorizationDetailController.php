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

            ///1. insert detail
            DB::table('material.authdtl')
                ->insert([
                    'compcode' => session('compcode'),
                    'trantype' => $request->dtl_trantype,
                    'deptcode' => $request->dtl_deptcode,
                    'authorid' => $request->authorid,
                    'recstatus' => $request->dtl_recstatus,
                    'cando' => $request->dtl_cando,
                    'minlimit' => $request->dtl_minlimit,
                    'maxlimit' => $request->dtl_maxlimit,
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
                ->where('authorid','=',$request->authorid)
                ->update([
                    'trantype' => $request->dtl_trantype,
                    'deptcode' => $request->dtl_deptcode,
                    'recstatus' => $request->dtl_recstatus,
                    'cando' => $request->dtl_cando,
                    'minlimit' => $request->dtl_minlimit,
                    'maxlimit' => $request->dtl_maxlimit,
                    'upduser' => session('username'), 
                    'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
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

       
            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }

        
    }

}

