<?php

namespace App\Http\Controllers\material;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class AuthorizationDtlController extends defaultController
{   

    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
        $this->duplicateCode = "Code";
    }

    public function show(Request $request)
    {   
        return view('material.AuthorizationDetail.authorizationDtl');
    }

    public function form(Request $request)
    {  
        switch($request->oper){
            case 'add':
                return $this->defaultAdd($request);
            case 'edit':
                return $this->defaultEdit($request);
            case 'del':
                return $this->defaultDel($request);
            default:
                return 'error happen..';
        }
    }

    public function table(Request $request)
    {  
        switch($request->action){
            case 'get_authdtl_alert':
                return $this->get_authdtl_alert($request);
            default:
                return 'error happen..';
        }
    }

    public function get_authdtl_alert (Request $request){
        $queuepr = DB::table('material.queuepr as qpr')
                    ->select('qpr.trantype','adtl.authorid','prhd.recno','prhd.reqdept','prhd.purreqno','prhd.purreqdt','prhd.recstatus','prhd.totamount','prhd.adduser')
                    ->join('material.authdtl as adtl', function($join) use ($request){
                        $join = $join
                            ->where('adtl.compcode',session('compcode'))
                            ->where('adtl.authorid',session('username'))
                            ->where('adtl.trantype','PR')
                            ->where('adtl.cando','ACTIVE')
                            ->on('adtl.recstatus','qpr.trantype')
                            ->where(function ($query) {
                                $query->on('adtl.deptcode','qpr.deptcode')
                                      ->orWhere('adtl.deptcode', 'ALL');
                            });
                    })
                    ->join('material.purreqhd as prhd', function($join) use ($request){
                        $join = $join
                            ->where('prhd.compcode',session('compcode'))
                            ->on('prhd.recno','qpr.recno')
                            ->on('prhd.recstatus','qpr.recstatus')
                            ->where(function ($query) {
                                $query
                                    ->on('prhd.totamount','>=','adtl.minlimit')
                                    ->on('prhd.totamount','<', 'adtl.maxlimit');
                            });;
                    })
                    ->where('qpr.compcode',session('compcode'))
                    ->where('qpr.trantype','<>','DONE')
                    ->get();

            $queuepo = DB::table('material.queuepo as qpo')
                    ->select('qpo.trantype','adtl.authorid','pohd.recno','pohd.prdept','pohd.purordno','pohd.purdate','pohd.recstatus','pohd.totamount','pohd.adduser')
                    ->join('material.authdtl as adtl', function($join) use ($request){
                        $join = $join
                            ->where('adtl.compcode',session('compcode'))
                            ->where('adtl.authorid',session('username'))
                            ->where('adtl.trantype','PO')
                            ->where('adtl.cando','ACTIVE')
                            ->on('adtl.recstatus','qpo.trantype')
                            ->where(function ($query) {
                                $query->on('adtl.deptcode','qpo.deptcode')
                                      ->orWhere('adtl.deptcode', 'ALL');
                            });
                    })
                    ->join('material.purordhd as pohd', function($join) use ($request){
                        $join = $join
                            ->where('pohd.compcode',session('compcode'))
                            ->on('pohd.recno','qpo.recno')
                            ->on('pohd.recstatus','qpo.recstatus')
                            ->where(function ($query) {
                                $query
                                    ->on('pohd.totamount','>=','adtl.minlimit')
                                    ->on('pohd.totamount','<', 'adtl.maxlimit');
                            });;
                    })
                    ->where('qpo.compcode',session('compcode'))
                    ->where('qpo.trantype','<>','DONE')
                    ->get();


        $responce = new stdClass();
        $responce->queuepr = $queuepr;
        $responce->queuepo = $queuepo;

        return json_encode($responce);
    }
}