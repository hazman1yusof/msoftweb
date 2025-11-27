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

        $todaydate = Carbon::now();                // current date/time
        $twoWeeksBefore = $todaydate->copy()->subWeeks(2);

        $queuepr = DB::table('material.queuepr as qpr')
                    ->select('qpr.trantype','adtl.authorid','prhd.recno','prhd.reqdept','prhd.purreqno','prhd.purreqdt','prhd.recstatus','prhd.totamount','prhd.adduser')
                    ->join('material.authdtl as adtl', function($join) use ($request){
                        $join = $join
                            ->where('adtl.compcode',session('compcode'))
                            ->where('adtl.authorid',session('username'))
                            ->where('adtl.trantype','PR')
                            ->where('adtl.cando','ACTIVE')
                            ->on('adtl.prtype','qpr.prtype')
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
                                    ->on('prhd.totamount','<=', 'adtl.maxlimit');
                            });;
                    })
                    ->where('qpr.compcode',session('compcode'))
                    ->where('qpr.trantype','<>','DONE')
                    ->get();

        
                    // dd($this->getQueries($queuepr));

        $queuepr_reject = DB::table('material.queuepr as qpr')
                    ->select('qpr.trantype','prhd.recno','prhd.reqdept','prhd.cancelby','prhd.canceldate','prhd.recstatus','prhd.totamount','prhd.adduser')
                    ->join('material.purreqhd as prhd', function($join) use ($request, $twoWeeksBefore, $todaydate){
                        $join = $join
                            ->whereBetween('canceldate', [$twoWeeksBefore, $todaydate])
                            ->where('prhd.compcode',session('compcode'))
                            ->on('prhd.recno','qpr.recno')
                            ->on('prhd.recstatus','qpr.recstatus');
                    })
                    ->where('qpr.AuthorisedID',session('username'))
                    ->where('qpr.compcode',session('compcode'))
                    ->where('qpr.trantype','REOPEN')
                    ->get();

        $queuepr = $queuepr->merge($queuepr_reject);

        $queuepo = DB::table('material.queuepo as qpo')
                    ->select('qpo.trantype','adtl.authorid','pohd.recno','pohd.prdept','pohd.purordno','pohd.purdate','pohd.recstatus','pohd.totamount','pohd.adduser')
                    ->join('material.authdtl as adtl', function($join) use ($request){
                        $join = $join
                            ->where('adtl.compcode',session('compcode'))
                            ->where('adtl.authorid',session('username'))
                            ->where('adtl.trantype','PO')
                            ->where('adtl.cando','ACTIVE')
                            ->on('adtl.prtype','qpo.prtype')
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
                                    ->on('pohd.totamount','<=', 'adtl.maxlimit');
                            });;
                    })
                    ->where('qpo.compcode',session('compcode'))
                    ->where('qpo.trantype','<>','DONE')
                    ->get();
        // dd($this->getQueries($queuepo));

        $queuepo_reject = DB::table('material.queuepo as qpo')
                    ->select('qpo.trantype','pohd.recno','pohd.prdept','pohd.cancelby','pohd.canceldate','pohd.recstatus','pohd.totamount','pohd.adduser')
                    ->join('material.purordhd as pohd', function($join) use ($request, $twoWeeksBefore, $todaydate){
                        $join = $join
                            ->whereBetween('canceldate', [$twoWeeksBefore, $todaydate])
                            ->where('pohd.compcode',session('compcode'))
                            ->on('pohd.recno','qpo.recno')
                            ->on('pohd.recstatus','qpo.recstatus');
                    })
                    ->where('qpo.AuthorisedID',session('username'))
                    ->where('qpo.compcode',session('compcode'))
                    ->where('qpo.trantype','REOPEN')
                    ->get();

        $queuepo = $queuepo->merge($queuepo_reject);

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
                            ->where('apact.source','AP')
                            ->where('apact.trantype','PV')
                            ->on('apact.auditno','qpv.recno')
                            ->on('apact.recstatus','qpv.recstatus')
                            ->where(function ($query) {
                                $query
                                    ->on('apact.amount','>=','prdtl.minlimit')
                                    ->on('apact.amount','<=', 'prdtl.maxlimit');
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

        // dd($this->getQueries($queuepv));

        $queuepv_reject = DB::table('finance.queuepv as qpv')
                    ->select('qpv.trantype','apact.adduser','apact.auditno','apact.suppcode','supp.Name','apact.actdate','apact.recstatus','apact.amount','apact.adduser','apact.cancelby','apact.canceldate')
                    ->join('finance.apacthdr as apact', function($join) use ($request, $twoWeeksBefore, $todaydate){
                        $join = $join
                            ->whereBetween('canceldate', [$twoWeeksBefore, $todaydate])
                            ->where('apact.compcode',session('compcode'))
                            ->where('apact.source','AP')
                            ->where('apact.trantype','PV')
                            ->on('apact.auditno','qpv.recno')
                            ->on('apact.recstatus','qpv.recstatus');
                    })
                    ->join('material.supplier as supp', function($join) use ($request){
                        $join = $join
                            ->where('supp.compcode',session('compcode'))
                            ->on('supp.suppcode','apact.suppcode');
                    })
                    ->where('qpv.AuthorisedID',session('username'))
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
                            ->where('prdtl.trantype','PD')
                            ->where('prdtl.cando','ACTIVE')
                            ->on('prdtl.recstatus','qpd.trantype');
                    })
                    ->join('finance.apacthdr as apact', function($join) use ($request){
                        $join = $join
                            ->where('apact.compcode',session('compcode'))
                            ->where('apact.source','AP')
                            ->where('apact.trantype','PD')
                            ->on('apact.auditno','qpd.recno')
                            ->on('apact.recstatus','qpd.recstatus')
                            ->where(function ($query) {
                                $query
                                    ->on('apact.amount','>=','prdtl.minlimit')
                                    ->on('apact.amount','<=', 'prdtl.maxlimit');
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
                    ->select('qpd.trantype','apact.adduser','apact.auditno','apact.suppcode','supp.Name','apact.actdate','apact.recstatus','apact.amount','apact.adduser','apact.cancelby','apact.canceldate')
                    ->join('finance.apacthdr as apact', function($join) use ($request, $twoWeeksBefore, $todaydate){
                        $join = $join
                            ->where('apact.compcode',session('compcode'))
                            ->where('apact.source','AP')
                            ->where('apact.trantype','PD')
                            ->whereBetween('canceldate', [$twoWeeksBefore, $todaydate])
                            ->on('apact.auditno','qpd.recno')
                            ->on('apact.recstatus','qpd.recstatus');
                    })
                    ->join('material.supplier as supp', function($join) use ($request){
                        $join = $join
                            ->where('supp.compcode',session('compcode'))
                            ->on('supp.suppcode','apact.suppcode');
                    })
                    ->where('qpd.AuthorisedID',session('username'))
                    ->where('qpd.compcode',session('compcode'))
                    ->where('qpd.trantype','REOPEN')
                    ->get();

        $queuepd = $queuepd->merge($queuepd_reject);

        $queuedp = DB::table('finance.queuedp as qdp')
                    ->select('qdp.trantype','prdtl.authorid','apact.auditno','apact.suppcode','supp.Name','apact.actdate','apact.recstatus','apact.amount','apact.adduser')
                    ->join('finance.permissiondtl as prdtl', function($join) use ($request){
                        $join = $join
                            ->where('prdtl.compcode',session('compcode'))
                            ->where('prdtl.authorid',session('username'))
                            ->where('prdtl.trantype','DP')
                            ->where('prdtl.cando','ACTIVE')
                            ->on('prdtl.recstatus','qdp.trantype');
                    })
                    ->join('finance.apacthdr as apact', function($join) use ($request){
                        $join = $join
                            ->where('apact.compcode',session('compcode'))
                            ->where('apact.source','CM')
                            ->where('apact.trantype','DP')
                            ->on('apact.auditno','qdp.recno')
                            ->on('apact.recstatus','qdp.recstatus')
                            ->where(function ($query) {
                                $query
                                    ->on('apact.amount','>=','prdtl.minlimit')
                                    ->on('apact.amount','<=', 'prdtl.maxlimit');
                            });
                    })
                    ->join('material.supplier as supp', function($join) use ($request){
                        $join = $join
                            ->where('supp.compcode',session('compcode'))
                            ->on('supp.suppcode','apact.suppcode');
                    })
                    ->where('qdp.compcode',session('compcode'))
                    ->where('qdp.trantype','<>','DONE')
                    ->get();

        $queuedp_reject = DB::table('finance.queuepd as qdp')
                    ->select('qdp.trantype','apact.adduser','apact.auditno','apact.suppcode','supp.Name','apact.actdate','apact.recstatus','apact.amount','apact.adduser','apact.cancelby','apact.canceldate')
                    ->join('finance.apacthdr as apact', function($join) use ($request, $twoWeeksBefore, $todaydate){
                        $join = $join
                            ->where('apact.compcode',session('compcode'))
                            ->where('apact.source','AP')
                            ->where('apact.trantype','DP')
                            ->whereBetween('canceldate', [$twoWeeksBefore, $todaydate])
                            ->on('apact.auditno','qdp.recno')
                            ->on('apact.recstatus','qdp.recstatus');
                    })
                    ->join('material.supplier as supp', function($join) use ($request){
                        $join = $join
                            ->where('supp.compcode',session('compcode'))
                            ->on('supp.suppcode','apact.suppcode');
                    })
                    ->where('qdp.AuthorisedID',session('username'))
                    ->where('qdp.compcode',session('compcode'))
                    ->where('qdp.trantype','REOPEN')
                    ->get();

        $queuedp = $queuedp->merge($queuedp_reject);

        $queueso = DB::table('finance.queueso as qso')
                    ->select('qso.trantype','prdtl.authorid','dbact.auditno','dbact.payercode','dbm.name','dbact.adddate','dbact.recstatus','dbact.amount','dbact.adduser')
                    ->join('finance.permissiondtl as prdtl', function($join) use ($request){
                        $join = $join
                            ->where('prdtl.compcode',session('compcode'))
                            ->where('prdtl.authorid',session('username'))
                            ->where('prdtl.trantype','SO')
                            ->where('prdtl.cando','ACTIVE')
                            ->on('prdtl.recstatus','qso.trantype');
                    })
                    ->join('debtor.dbacthdr as dbact', function($join) use ($request){
                        $join = $join
                            ->where('dbact.compcode',session('compcode'))
                            ->where('dbact.source','PB')
                            ->where('dbact.trantype','IN')
                            ->on('dbact.auditno','qso.recno')
                            ->on('dbact.recstatus','qso.recstatus')
                            ->where(function ($query) {
                                $query
                                    ->on('dbact.amount','>=','prdtl.minlimit')
                                    ->on('dbact.amount','<=', 'prdtl.maxlimit');
                            });
                    })
                    ->join('debtor.debtormast as dbm', function($join) use ($request){
                        $join = $join
                            ->where('dbm.compcode',session('compcode'))
                            ->on('dbm.debtorcode','dbact.payercode');
                    })
                    ->where('qso.compcode',session('compcode'))
                    ->where('qso.trantype','<>','DONE')
                    ->get();
            // dd($this->getQueries($queueso));

                    // 

        // $queueso_reject = DB::table('finance.queueso as qso')
        //             ->select('qso.trantype','dbact.adduser','dbact.auditno','dbact.payercode','dbm.name','dbact.adddate','dbact.recstatus','dbact.amount','dbact.adduser','dbact.cancelby','dbact.canceldate')
        //             ->join('debtor.dbacthdr as dbact', function($join) use ($request){
        //                 $join = $join
        //                     ->where('dbact.compcode',session('compcode'))
        //                     ->where('dbact.source','PB')
        //                     ->where('dbact.trantype','IN')
        //                     ->on('dbact.auditno','qso.recno')
        //                     ->on('dbact.recstatus','qso.recstatus');
        //             })
        //             ->join('debtor.debtormast as dbm', function($join) use ($request){
        //                 $join = $join
        //                     ->where('dbm.compcode',session('compcode'))
        //                     ->on('dbm.debtorcode','dbact.payercode');
        //             })
        //             ->where('qso.AuthorisedID',session('username'))
        //             ->where('qso.compcode',session('compcode'))
        //             ->where('qso.trantype','REOPEN')
        //             ->get();

        // $queueso = $queueso->merge($queueso_reject);

        $queueiv = DB::table('material.queueiv as qiv')
                    ->select('qiv.trantype','adtl.authorid','ivhd.recno','ivhd.txndept as deptcode','dept.description as deptcode_desc','ivhd.adddate','ivhd.recstatus','ivhd.amount','ivhd.adduser')
                    ->join('material.authdtl as adtl', function($join) use ($request){
                        $join = $join
                            ->where('adtl.compcode',session('compcode'))
                            ->where('adtl.authorid',session('username'))
                            ->where('adtl.trantype','IV')
                            ->where('adtl.cando','ACTIVE')
                            ->on('adtl.recstatus','qiv.trantype');
                            // ->where(function ($query) {
                            //     $query->on('adtl.deptcode','qpo.deptcode')
                            //           ->orWhere('adtl.deptcode', 'ALL');
                            // });
                    })
                    ->join('material.ivtmphd as ivhd', function($join) use ($request){
                        $join = $join
                            ->where('ivhd.compcode',session('compcode'))
                            ->on('ivhd.recno','qiv.recno')
                            ->on('ivhd.recstatus','qiv.recstatus')
                            ->where(function ($query) {
                                $query
                                    ->on('ivhd.amount','>=','adtl.minlimit')
                                    ->on('ivhd.amount','<=', 'adtl.maxlimit');
                            });
                    })
                    ->join('sysdb.department as dept', function($join) use ($request){
                        $join = $join
                            ->where('dept.compcode',session('compcode'))
                            ->on('dept.deptcode','ivhd.txndept');
                    })
                    ->where('qiv.compcode',session('compcode'))
                    ->where('qiv.trantype','<>','DONE')
                    ->get();

        // dd($this->getQueries($queueiv));

        $queueiv_reject = DB::table('material.queueiv as qiv')
                    ->select('qiv.trantype','ivhd.adduser','ivhd.recno','ivhd.txndept as deptcode','dept.description as deptcode_desc','ivhd.adddate','ivhd.recstatus','ivhd.amount','ivhd.adduser','ivhd.cancelby','ivhd.canceldate')
                    ->join('material.ivtmphd as ivhd', function($join) use ($request, $twoWeeksBefore, $todaydate){
                        $join = $join
                            ->where('ivhd.compcode',session('compcode'))
                            ->on('ivhd.recno','qiv.recno')
                            ->on('ivhd.recstatus','qiv.recstatus')
                            ->whereBetween('canceldate', [$twoWeeksBefore, $todaydate]);
                    })
                    ->join('sysdb.department as dept', function($join) use ($request){
                        $join = $join
                            ->where('dept.compcode',session('compcode'))
                            ->on('dept.deptcode','ivhd.txndept');
                    })
                    ->where('qiv.AuthorisedID',session('username'))
                    ->where('qiv.compcode',session('compcode'))
                    ->where('qiv.trantype','REOPEN')
                    ->get();

        $queueiv = $queueiv->merge($queueiv_reject);

        $ivreq_posted = DB::table('material.ivreqhd as ivreq')
                    ->select('ivreq.reqdept as dept', 'ivreq.recno', 'ivreq.postedby')
                    // ->join('sysdb.department as dept', function($join) use ($request){
                    //     $join = $join
                    //         ->where('dept.compcode',session('compcode'))
                    //         ->on('dept.deptcode','ivreq.reqdept');
                    // })
                    ->where('ivreq.reqtodept',session('deptcode'))
                    ->where('ivreq.compcode',session('compcode'))
                    ->where('ivreq.recstatus','POSTED')
                    ->get();
        // dd($this->getQueries($ivreq_posted));

        $responce = new stdClass();
        $responce->queuepr = $queuepr;
        $responce->queueprv2 = $queuepr->groupBy('trantype');
        $responce->queuepo = $queuepo;
        $responce->queuepov2 = $queuepo->groupBy('trantype');
        $responce->queuepv = $queuepv;
        $responce->queuepvv2 = $queuepv->groupBy('trantype');
        $responce->queuepd = $queuepd;
        $responce->queuepdv2 = $queuepd->groupBy('trantype');
        $responce->queuedp = $queuedp;
        $responce->queuedpv2 = $queuedp->groupBy('trantype');
        $responce->queueso = $queueso;
        $responce->queueiv = $queueiv;
        $responce->ivreq = $ivreq_posted;

        return json_encode($responce);
    }
}