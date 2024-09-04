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
            //     $duplicate = DB::table('material.authdtl')
            //                 ->where('compcode','=',session('compcode'))
            //                 ->where('authorid','=',$authorid_)
            //                 ->where('trantype','=',$request->dtl_trantype)
            //                 ->where('recstatus','=',$request->dtl_recstatus)
            //                 ->where('cando','!=','DEACTIVE')
            //                 ->exists();
            // }else{
                $duplicate = DB::table('material.authdtl')
                            ->where('compcode','=',session('compcode'))
                            ->where('authorid','=',$authorid_)
                            ->where('trantype','=',$request->dtl_trantype)
                            ->whereIn('deptcode', [$request->dtl_deptcode, "ALL", "all"])
                            ->where('recstatus','=',$request->dtl_recstatus)
                            ->where('prtype','=',$request->dtl_prtype)
                            ->exists();
            // }

            if($duplicate){
                throw new \Exception("Duplicate entry", 500);
            }

            // if(!$duplicate){
            //     $duplicate2 = DB::table('material.authdtl')
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
                DB::table('material.authdtl')
                    ->insert([
                        'compcode' => session('compcode'),
                        'trantype' => strtoupper($request->dtl_trantype),
                        'deptcode' => strtoupper($request->dtl_deptcode),
                        'authorid' => $authorid_,
                        'recstatus' => strtoupper($request->dtl_recstatus),
                        'prtype' => strtoupper($request->dtl_prtype),
                        'cando' => strtoupper($request->dtl_cando),
                        'minlimit' => $request->dtl_minlimit,
                        'maxlimit' => $request->dtl_maxlimit,
                        'adduser' => session('username'), 
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    ]);
            // }else{
            //     $first_get = $duplicate2->first();

            //     throw new \Exception("Authorise Entry has been entered by ".$first_get->authorid, 500);
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

            $authdtl = DB::table('material.authdtl')
                        ->where('compcode','=',session('compcode'))
                        ->where('idno','=',$request->dtl_idno)
                        ->where('authorid','=',$authorid_);

            $authdtl_get = $authdtl->first();

            if(empty($request->dtl_cando)){
                $request->dtl_cando = $authdtl_get->cando;
            }

            $duplicate = false;

            if($request->dtl_deptcode == 'ALL' || $request->dtl_deptcode == 'all'){
                $duplicate = DB::table('material.authdtl')
                            ->where('compcode','=',session('compcode'))
                            ->where('authorid','=',$authorid_)
                            ->where('deptcode','!=',$authdtl_get->deptcode)
                            ->where('trantype','=',$request->dtl_trantype)
                            ->where('recstatus','=',$request->dtl_recstatus)
                            ->where('prtype','=',$request->dtl_prtype)
                            ->where('cando','=','ACTIVE')
                            ->exists();

            }else if($authdtl_get->deptcode=='all' || $authdtl_get->deptcode=='ALL'){
                $duplicate = DB::table('material.authdtl')
                            ->where('compcode','=',session('compcode'))
                            ->where('authorid','=',$authorid_)
                            ->where('trantype','=',$request->dtl_trantype)
                            ->where('deptcode','==',$request->deptcode)
                            ->where('recstatus','=',$request->dtl_recstatus)
                            ->where('prtype','=',$request->dtl_prtype)
                            ->exists();
            }  

            if($duplicate){
                throw new \Exception("Duplicate entry", 500);
            }

            // $duplicate2 = DB::table('material.authdtl')
            //             ->where('compcode','=',session('compcode'))
            //             ->where('authorid','!=',$authorid_)
            //             ->where('maxlimit','=',$request->dtl_maxlimit)
            //             ->where('trantype','=',$request->dtl_trantype)
            //             ->where('deptcode','=',$request->dtl_deptcode)
            //             ->where('recstatus','=',$request->dtl_recstatus)
            //             ->where('prtype','=',$request->dtl_prtype);

            // if($duplicate2->exists()){
            //     $first_get = $duplicate2->first();
            //     throw new \Exception("Authorise Entry has been entered by ".$first_get->authorid, 500);
            // }

            ///1. update detail
            $authdtl
                ->update([
                    'trantype' => strtoupper($request->dtl_trantype),
                    'deptcode' => strtoupper($request->dtl_deptcode),
                    'recstatus' => strtoupper($request->dtl_recstatus),
                    'prtype' => strtoupper($request->dtl_prtype),
                    'cando' => strtoupper($request->dtl_cando),
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
                DB::table('material.authdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('idno','=',$value['idno'])
                    ->update([
                        // 'trantype' => $value['trantype'],
                        // 'deptcode' => $value['deptcode'],
                        // 'recstatus' => $value['recstatus'],
                        // 'prtype' => strtoupper($request->dtl_prtype),
                        // 'cando' => $value['cando'],
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
            DB::table('material.authdtl')
                ->where('compcode','=',session('compcode'))
                ->where('idno','=',$request->idno)
                ->delete();
                // ->update([ 
                //     'deluser' => session('username'), 
                //     'deldate' => Carbon::now("Asia/Kuala_Lumpur"),
                //     'cando' => 'DEACTIVE'
                // ]);

       
            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }

        
    }

}

