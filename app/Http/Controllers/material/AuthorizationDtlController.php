<?php

namespace App\Http\Controllers\material;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class AuthorizationDtlController extends defaultController // DONT DELETE THIS CONTROLLER, ITS FOR WARNING AT DAHSBOARD
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

        $queuepv = DB::table('finance.queuepv as qpv')
                    ->select('qpv.trantype','prdtl.authorid','apact.auditno','apact.suppcode','supp.Name','apact.actdate','apact.recstatus','apact.amount','apact.adduser')
                    ->join('finance.permissiondtl as prdtl', function($join) use ($request){
                        $join = $join
                            ->where('prdtl.compcode',session('compcode'))
                            ->where('prdtl.authorid',session('username'))
                            ->where('prdtl.trantype','PV')
                            ->where('prdtl.cando','ACTIVE')
                            ->on('prdtl.recstatus','qpv.trantype');
                    })
                    ->join('finance.apacthdr as apact', function($join) use ($request){
                        $join = $join
                            ->where('apact.compcode',session('compcode'))
                            ->on('apact.auditno','qpv.recno')
                            ->on('apact.recstatus','qpv.recstatus')
                            ->where(function ($query) {
                                $query
                                    ->on('apact.amount','>=','prdtl.minlimit')
                                    ->on('apact.amount','<', 'prdtl.maxlimit');
                            });
                    })
                    ->join('material.supplier as supp', function($join) use ($request){
                        $join = $join
                            ->where('supp.compcode',session('compcode'))
                            ->on('supp.suppcode','apact.suppcode');
                    })
                    ->where('qpv.compcode',session('compcode'))
                    ->where('qpv.trantype','<>','DONE')
                    ->get();

        $queuepv_reject = DB::table('finance.queuepv as qpv')
                    ->select('qpv.trantype','apact.adduser','apact.auditno','apact.suppcode','supp.Name','apact.actdate','apact.recstatus','apact.amount','apact.adduser','apact.cancelby','apact.canceldate')
                    ->join('finance.apacthdr as apact', function($join) use ($request){
                        $join = $join
                            ->where('apact.compcode',session('compcode'))
                            ->on('apact.auditno','qpv.recno')
                            ->on('apact.recstatus','qpv.recstatus');
                    })
                    ->join('material.supplier as supp', function($join) use ($request){
                        $join = $join
                            ->where('supp.compcode',session('compcode'))
                            ->on('supp.suppcode','apact.suppcode');
                    })
                    ->where('qpv.compcode',session('compcode'))
                    ->where('qpv.trantype','REOPEN')
                    ->get();

        $queuepv = $queuepv->merge($queuepv_reject);

        $queuepd = DB::table('finance.queuepd as qpd')
                    ->select('qpd.trantype','prdtl.authorid','apact.auditno','apact.suppcode','supp.Name','apact.actdate','apact.recstatus','apact.amount','apact.adduser')
                    ->join('finance.permissiondtl as prdtl', function($join) use ($request){
                        $join = $join
                            ->where('prdtl.compcode',session('compcode'))
                            ->where('prdtl.authorid',session('username'))
                            ->where('prdtl.trantype','PV')
                            ->where('prdtl.cando','ACTIVE')
                            ->on('prdtl.recstatus','qpd.trantype');
                    })
                    ->join('finance.apacthdr as apact', function($join) use ($request){
                        $join = $join
                            ->where('apact.compcode',session('compcode'))
                            ->on('apact.auditno','qpd.recno')
                            ->on('apact.recstatus','qpd.recstatus')
                            ->where(function ($query) {
                                $query
                                    ->on('apact.amount','>=','prdtl.minlimit')
                                    ->on('apact.amount','<', 'prdtl.maxlimit');
                            });
                    })
                    ->join('material.supplier as supp', function($join) use ($request){
                        $join = $join
                            ->where('supp.compcode',session('compcode'))
                            ->on('supp.suppcode','apact.suppcode');
                    })
                    ->where('qpd.compcode',session('compcode'))
                    ->where('qpd.trantype','<>','DONE')
                    ->get();

        $queuepd_reject = DB::table('finance.queuepd as qpd')
                    ->select('qpd.trantype','apact.adduser','apact.auditno','apact.suppcode','supp.Name','apact.actdate','apact.recstatus','apact.amount','apact.adduser')
                    ->join('finance.apacthdr as apact', function($join) use ($request){
                        $join = $join
                            ->where('apact.compcode',session('compcode'))
                            ->on('apact.auditno','qpd.recno')
                            ->on('apact.recstatus','qpd.recstatus');
                    })
                    ->join('material.supplier as supp', function($join) use ($request){
                        $join = $join
                            ->where('supp.compcode',session('compcode'))
                            ->on('supp.suppcode','apact.suppcode');
                    })
                    ->where('qpd.compcode',session('compcode'))
                    ->where('qpd.trantype','REOPEN')
                    ->get();

        $queuepd = $queuepd->merge($queuepd_reject);


        $responce = new stdClass();
        $responce->queuepr = $queuepr;
        $responce->queuepo = $queuepo;
        $responce->queuepv = $queuepv;
        $responce->queuepd = $queuepd;

        return json_encode($responce);
    }
}