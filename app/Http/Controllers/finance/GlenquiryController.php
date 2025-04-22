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
            case 'dialogForm_SalesOrder':
                return $this->dialogForm_SalesOrder($request);
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

    public function dialogForm_SalesOrder(Request $request){
        $billsum = DB::table('debtor.billsum as bs')
                        ->where('bs.compcode',session('compcode'))
                        ->where('bs.source','PB')
                        ->where('bs.trantype','IN')
                        ->where('bs.auditno',$request->auditno);

        $billno = $billsum->first()->billno;

        $dbacthdr = DB::table('debtor.dbacthdr as db')
                        ->select('db.idno','db.compcode','db.source','db.trantype','db.auditno','db.lineno_','db.amount','db.outamount','db.recstatus','db.entrydate','db.entrytime','db.entryuser','db.reference','db.recptno','db.paymode','db.tillcode','db.tillno','db.debtortype','db.debtorcode','db.payercode','db.billdebtor','db.remark','db.mrn','db.episno','db.authno','db.expdate','db.adddate','db.adduser','db.upddate','db.upduser','db.deldate','db.deluser','db.epistype','db.cbflag','db.conversion','db.payername','db.hdrtype','db.currency','db.rate','db.unit','db.invno','db.paytype','db.bankcharges','db.RCCASHbalance','db.RCOSbalance','db.RCFinalbalance','db.PymtDescription','db.orderno','db.ponum','db.podate','db.termdays','db.termmode','db.deptcode','db.posteddate','db.approvedby','db.approveddate','db.unallocated','btm.description as hdrtype_desc','dept.description as deptcode_desc','dbm.name as debtorcode_desc')
                        ->leftJoin('hisdb.billtymst as btm', function($join) use ($request){
                            $join = $join->on('btm.billtype', '=', 'db.hdrtype')
                                            ->where('btm.compcode','=',session('compcode'));
                        })->leftJoin('sysdb.department as dept', function($join) use ($request){
                            $join = $join->on('dept.deptcode', '=', 'db.deptcode')
                                            ->where('dept.compcode','=',session('compcode'));
                        })->leftJoin('debtor.debtormast as dbm', function($join) use ($request){
                            $join = $join->on('dbm.debtorcode', '=', 'db.debtorcode')
                                            ->where('dbm.compcode','=',session('compcode'));
                        })
                        ->where('db.compcode',session('compcode'))
                        ->where('db.source','PB')
                        ->where('db.trantype','IN')
                        ->where('db.auditno',$billno);

        $billsum_array = DB::table('debtor.billsum as bs')
                        ->select('bs.idno','bs.compcode','bs.source','bs.trantype','bs.auditno','bs.quantity','bs.amount','bs.outamt','bs.taxamt','bs.totamt','bs.mrn','bs.episno','bs.paymode','bs.cardno','bs.debtortype','bs.debtorcode','bs.invno','bs.billno','bs.lineno_','bs.rowno','bs.billtype','bs.chgclass','bs.classlevel','bs.chggroup','bs.lastuser','bs.lastupdate','bs.invcode','bs.seqno','bs.discamt','bs.docref','bs.uom','bs.uom_recv','bs.recstatus','bs.unitprice','bs.taxcode','bs.billtypeperct','bs.billtypeamt','bs.totamount','bs.qtyonhand','cm.description','ivdt.netprice')
                        ->leftJoin('hisdb.chgmast as cm', function($join) use ($request){
                            $join = $join->on('cm.chgcode', '=', 'bs.chggroup')
                                         ->on('cm.uom', '=', 'bs.uom')
                                         ->where('cm.compcode','=',session('compcode'));
                        })->leftJoin('material.ivdspdt as ivdt', function($join) use ($request){
                            $join = $join->where('ivdt.trantype', '=', 'DS')
                                         ->on('ivdt.recno', '=', 'bs.auditno')
                                         ->on('ivdt.itemcode', '=', 'bs.chggroup')
                                         ->on('ivdt.uomcode', '=', 'bs.uom')
                                         ->where('ivdt.compcode','=',session('compcode'));
                        })
                        ->where('bs.compcode',session('compcode'))
                        ->where('bs.source','PB')
                        ->where('bs.trantype','IN')
                        ->where('bs.billno',$billno);

        $responce = new stdClass();
        $responce->dbacthdr = $dbacthdr->first();
        $responce->billsum_array = $billsum_array->get();

        return json_encode($responce);
    }

    public function getdata(Request $request){

        $responce = new stdClass();

        if(empty($request->costcode)){
            $responce->data = [];
            return json_encode($responce);
        }

        $table_ = DB::table('finance.gltran')
                    ->select(DB::raw("'open' as open"),'gltran.source','gltran.trantype','gltran.auditno','gltran.postdate','gltran.description','gltran.reference','gltran.cracc','gltran.dracc','gltran.amount','glcr.description as acctname_cr','gldr.description as acctname_dr','gltran.id')
                    ->leftJoin('finance.glmasref as glcr', function($join) use ($request){
                        $join = $join->on('glcr.glaccno', '=', 'gltran.cracc')
                                        ->where('glcr.compcode','=',session('compcode'));
                    })
                    ->leftJoin('finance.glmasref as gldr', function($join) use ($request){
                        $join = $join->on('gldr.glaccno', '=', 'gltran.dracc')
                                        ->where('gldr.compcode','=',session('compcode'));
                    })
                    ->where('gltran.compcode',session('compcode'))
                    ->where('gltran.year',$request->year)
                    ->where('gltran.period',$request->period)
                    ->where('gltran.crcostcode',$request->costcode)
                    ->where('gltran.cracc',$request->acc)
                    ->orWhere(function ($table) use ($request) {
                        $table
                        ->where('gltran.compcode',session('compcode'))
                        ->where('gltran.year',$request->year)
                        ->where('gltran.period',$request->period)
                        ->where('gltran.drcostcode',$request->costcode)
                        ->where('gltran.dracc',$request->acc);
                    })
                    ->orderBy('gltran.id','desc');

        $count = $table_->count();
        $table = $table_
                    ->offset($request->start)
                    ->limit($request->length)->get();

        foreach ($table as $key => $value) {
            if(strtoupper($value->cracc) == strtoupper($request->acc)){
                $value->acccode = $value->dracc;
                $value->dramount = '';
                $value->cramount = $value->amount;
                $value->acctname = $value->acctname_dr;
            }else{
                $value->acccode = $value->cracc;
                $value->dramount = $value->amount;
                $value->cramount = '';
                $value->acctname = $value->acctname_cr;
            }
        }

        // $table_cr = DB::table('finance.gltran')
        //             ->select(DB::raw("'open' as open"),DB::raw("'' as dramount"),'gltran.source','gltran.trantype','gltran.auditno','gltran.postdate','gltran.description','gltran.reference','gltran.dracc as acccode','gltran.amount as cramount','glmasref.description as acctname','gltran.id')
        //             ->leftJoin('finance.glmasref', function($join) use ($request){
        //                 $join = $join->on('glmasref.glaccno', '=', 'gltran.dracc')
        //                                 ->where('glmasref.compcode','=',session('compcode'));
        //             })
        //             ->where('gltran.compcode',session('compcode'))
        //             ->where('gltran.crcostcode',$request->costcode)
        //             ->where('gltran.cracc',$request->acc)
        //             ->where('gltran.year',$request->year)
        //             ->where('gltran.period',$request->period)
        //             ->get();

        // $table_merge = $table_dr->merge($table_cr);

        $responce->data = $table;
        $responce->recordsTotal = $count;
        $responce->recordsFiltered = $count;
        return json_encode($responce);

    }
}