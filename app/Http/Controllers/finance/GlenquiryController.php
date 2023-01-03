<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use stdClass;
use App\Http\Controllers\defaultController;
use DB;

class GlenquiryController extends defaultController
{   

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        return view('finance.GL.glmasdtl.glmasdtl');
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
            case 'dialogForm_paymentVoucher':
                return $this->dialogForm_paymentVoucher($request);
            case 'getdata':
                return $this->getdata($request);
            default:
                return 'error happen..';
        }
    }

    public function dialogForm_paymentVoucher(Request $request){
         $table = DB::table('finance.apacthdr')
                    ->select(
                        'apacthdr.auditno','apacthdr.trantype','apacthdr.doctype','apacthdr.suppcode','apacthdr.actdate','apacthdr.document','apacthdr.cheqno','apacthdr.deptcode','apacthdr.amount','apacthdr.outamount','apacthdr.recstatus','apacthdr.payto','apacthdr.recdate','apacthdr.category','apacthdr.remarks','apacthdr.adduser','apacthdr.adddate','apacthdr.upduser','apacthdr.upddate','apacthdr.source','apacthdr.idno','apacthdr.unit','apacthdr.pvno','apacthdr.paymode','apacthdr.bankcode','paymode.description as paymode_desc','bank.bankname as bankcode_desc','supp_suppcode.Name as suppcode_desc','supp_payto.Name as payto_desc'
                    )->leftJoin('debtor.paymode', function($join) use ($request){
                        $join = $join->on('paymode.paymode', '=', 'apacthdr.paymode')
                                        ->where('paymode.compcode','=',session('compcode'))
                                        ->where('paymode.source','=','AP');
                    })->leftJoin('finance.bank', function($join) use ($request){
                        $join = $join->on('bank.bankcode', '=', 'apacthdr.bankcode')
                                        ->where('bank.compcode','=',session('compcode'));
                    })->leftJoin('material.supplier as supp_suppcode', function($join) use ($request){
                        $join = $join->on('supp_suppcode.suppcode', '=', 'apacthdr.suppcode')
                                        ->where('supp_suppcode.compcode','=',session('compcode'));
                    })->leftJoin('material.supplier as supp_payto', function($join) use ($request){
                        $join = $join->on('supp_payto.suppcode', '=', 'apacthdr.payto')
                                        ->where('supp_payto.compcode','=',session('compcode'));
                    })
                    ->where('apacthdr.compcode',session('compcode'))
                    ->where('apacthdr.source',$request->source)
                    ->where('apacthdr.trantype',$request->trantype)
                    ->where('apacthdr.auditno',$request->auditno);

        $responce = new stdClass();
        $responce->rows = $table->get();
        $responce->sql_query = $this->getQueries($table);

        return json_encode($responce);
    }

    public function getdata(Request $request){

        $responce = new stdClass();

        if(empty($request->costcode)){
            $responce->data = [];
            return json_encode($responce);
        }

        $table_dr = DB::table('finance.gltran')
                    ->select(DB::raw("'open' as open"),DB::raw("'' as cramount"),'gltran.source','gltran.trantype','gltran.auditno','gltran.postdate','gltran.description','gltran.reference','gltran.dracc as acccode','gltran.amount as dramount','glmasref.description as acctname')
                    ->leftJoin('finance.glmasref', function($join) use ($request){
                        $join = $join->on('glmasref.glaccno', '=', 'gltran.dracc')
                                        ->where('glmasref.compcode','=',session('compcode'));
                    })
                    ->where('gltran.compcode',session('compcode'))
                    ->where('gltran.drcostcode',$request->costcode)
                    ->where('gltran.dracc',$request->acc)
                    ->where('gltran.year',$request->year)
                    ->where('gltran.period',$request->period)
                    ->get();

        $table_cr = DB::table('finance.gltran')
                    ->select(DB::raw("'open' as open"),DB::raw("'' as dramount"),'gltran.source','gltran.trantype','gltran.auditno','gltran.postdate','gltran.description','gltran.reference','gltran.cracc as acccode','gltran.amount as cramount','glmasref.description as acctname')
                    ->leftJoin('finance.glmasref', function($join) use ($request){
                        $join = $join->on('glmasref.glaccno', '=', 'gltran.cracc')
                                        ->where('glmasref.compcode','=',session('compcode'));
                    })
                    ->where('gltran.compcode',session('compcode'))
                    ->where('gltran.crcostcode',$request->costcode)
                    ->where('gltran.cracc',$request->acc)
                    ->where('gltran.year',$request->year)
                    ->where('gltran.period',$request->period)
                    ->get();

        $table_merge = $table_dr->merge($table_cr);

        $responce->data = $table_merge;
        return json_encode($responce);

    }
}