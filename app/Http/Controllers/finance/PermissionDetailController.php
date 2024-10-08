<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class PermissionDetailController extends defaultController
{   
    var $gltranAmount;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function form(Request $request)
    {   
        DB::enableQueryLog();
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
            ///1. check duplicate

            if(!empty($request->dtl_authorid)){
                $authorid_ = $request->dtl_authorid;
            }else{
                $authorid_ = $request->authorid;
            }

            // if($request->dtl_deptcode == 'ALL' || $request->dtl_deptcode == 'all'){
            //     $duplicate = DB::table('finance.permissiondtl')
            //                 ->where('compcode','=',session('compcode'))
            //                 ->where('authorid','=',$authorid_)
            //                 ->where('trantype','=',$request->dtl_trantype)
            //                 ->where('recstatus','=',$request->dtl_recstatus)
            //                 ->where('cando','!=','DEACTIVE')
            //                 ->exists();
            // }else{
                $duplicate = DB::table('finance.permissiondtl')
                            ->where('compcode','=',session('compcode'))
                            ->where('authorid','=',$authorid_)
                            ->where('trantype','=',$request->dtl_trantype)
                            ->whereIn('deptcode', [$request->dtl_deptcode, "ALL", "all"])
                            ->where('recstatus','=',$request->dtl_recstatus)
                            ->exists();
            // }

            // if(!$duplicate){
            //     $duplicate2 = DB::table('finance.permissiondtl')
            //                 ->where('compcode','=',session('compcode'))
            //                 ->where('authorid','!=',$authorid_)
            //                 ->where('maxlimit','=',$request->dtl_maxlimit)
            //                 ->where('trantype','=',$request->dtl_trantype)
            //                 ->where('deptcode','=',$request->dtl_deptcode)
            //                 ->where('recstatus','=',$request->dtl_recstatus);

            // }else{
            //     throw new \Exception("Duplicate entry", 500);
            // }

            // if(!$duplicate2->exists()){


                ///2. insert detail
                DB::table('finance.permissiondtl')
                    ->insert([
                        'compcode' => session('compcode'),
                        'trantype' => strtoupper($request->dtl_trantype),
                        'deptcode' => strtoupper($request->dtl_deptcode),
                        'authorid' => $authorid_,
                        'recstatus' => strtoupper($request->dtl_recstatus),
                        'cando' => strtoupper($request->dtl_cando),
                        'minlimit' => $request->dtl_minlimit,
                        'maxlimit' => $request->dtl_maxlimit,
                        'adduser' => session('username'), 
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    ]);
            // }else{
            //     $first_get = $duplicate2->first();

            //     throw new \Exception("Permission Entry has been entered by ".$first_get->authorid, 500);
            // }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function edit(Request $request){

        DB::beginTransaction();

        try {
            if(!empty($request->dtl_authorid)){
                $authorid_ = $request->dtl_authorid;
            }else{
                $authorid_ = $request->authorid;
            }

            $permissiondtl = DB::table('finance.permissiondtl')
                        ->where('compcode','=',session('compcode'))
                        ->where('idno','=',$request->dtl_idno)
                        ->where('authorid','=',$authorid_);

            $permissiondtl_get = $permissiondtl->first();

            if(empty($request->dtl_cando)){
                $request->dtl_cando = $permissiondtl_get->cando;
            }

            $duplicate = false;

            if($request->dtl_deptcode == 'ALL' || $request->dtl_deptcode == 'all'){
                $duplicate = DB::table('finance.permissiondtl')
                            ->where('compcode','=',session('compcode'))
                            ->where('authorid','=',$authorid_)
                            ->where('deptcode','!=',$permissiondtl_get->deptcode)
                            ->where('trantype','=',$request->dtl_trantype)
                            ->where('recstatus','=',$request->dtl_recstatus)
                            ->where('cando','=','ACTIVE')
                            ->exists();

            }else if($permissiondtl_get->deptcode=='all' || $permissiondtl_get->deptcode=='ALL'){
                $duplicate = DB::table('finance.permissiondtl')
                            ->where('compcode','=',session('compcode'))
                            ->where('authorid','=',$authorid_)
                            ->where('trantype','=',$request->dtl_trantype)
                            ->where('deptcode','==',$request->deptcode)
                            ->where('recstatus','=',$request->dtl_recstatus)
                            ->exists();
            }  

            if($duplicate){
                throw new \Exception("Duplicate entry", 500);
            }

            // $duplicate2 = DB::table('finance.permissiondtl')
            //             ->where('compcode','=',session('compcode'))
            //             ->where('authorid','!=',$authorid_)
            //             ->where('maxlimit','=',$request->dtl_maxlimit)
            //             ->where('trantype','=',$request->dtl_trantype)
            //             ->where('deptcode','=',$request->dtl_deptcode)
            //             ->where('recstatus','=',$request->dtl_recstatus);

            // if($duplicate2->exists()){
            //     $first_get = $duplicate2->first();
            //     throw new \Exception("Permission Entry has been entered by ".$first_get->authorid, 500);
            // }

            ///1. update detail
            $permissiondtl
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

            return response($e->getMessage(), 500);
        }

    }

    public function edit_all(Request $request){

        DB::beginTransaction();

        try {

            foreach ($request->dataobj as $key => $value) {
                ///1. update detail
                DB::table('finance.permissiondtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('idno','=',$value['idno'])
                    ->update([
                        'trantype' => $value['trantype'],
                        'deptcode' => $value['deptcode'],
                        'recstatus' => $value['recstatus'],
                        'cando' => $value['cando'],
                        'minlimit' => $value['minlimit'],
                        'maxlimit' => $value['maxlimit'],
                        'upduser' => session('username'), 
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
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
            DB::table('finance.permissiondtl')
                ->where('compcode','=',session('compcode'))
                ->where('idno','=',$request->idno)
                ->delete();

       
            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }

        
    }

}

