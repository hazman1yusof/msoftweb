<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use stdClass;
use App\Http\Controllers\defaultController;
use DB;
use DateTime;
use Carbon\Carbon;

class ClaimBatchController extends defaultController
{   

    var $table;
    var $duplicateCode;
    var $auditno;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   

        $sysparam = DB::table('sysdb.sysparam')
                    ->where('compcode',session('compcode'))
                    ->where('source','AR')
                    ->where('trantype','CLAIM');

        if($sysparam->exists()){
            $comment_ = $sysparam->first()->comment_;
        }else{
            $comment_ = "";
        }

        return view('finance.AR.claimBatch.claimBatch',compact('comment_'));
    }

    public function table(Request $request)
    {   
        switch($request->action){
            case 'get_comment':
                return $this->get_comment($request);
            case 'print':
                return $this->print($request);
            default:
                return 'error happen..';
        }
    }

    public function form(Request $request)
    {   
        switch($request->oper){
            // case 'add':
            //     return $this->add($request);
            case 'edit':
                return $this->edit($request);
            default:
                return 'error happen..';
        }
    }

    public function edit(Request $request){
        DB::beginTransaction();

        try {

            $sysparam = DB::table('sysdb.sysparam')
                        ->where('compcode',session('compcode'))
                        ->where('source','AR')
                        ->where('trantype','CLAIM');

            if($sysparam->exists()){
                DB::table('sysdb.sysparam')
                        ->where('compcode',session('compcode'))
                        ->where('source','AR')
                        ->where('trantype','CLAIM')
                        ->update([
                            'comment_' => $request->comment_,
                            'lastuser' => session('username'),
                            'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                        ]);
            }else{
                DB::table('sysdb.sysparam')
                        ->insert([
                            'compcode' => session('compcode'),
                            'source' => 'AR',
                            'trantype' => 'CLAIM',
                            'comment_' => $request->comment_,
                            'lastuser' => session('username'),
                            'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'effectivedate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'recstatus' => 'ACTIVE',
                        ]);
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response('Error DB rollback!'.$e, 500);
        }
    }

    public function get_comment(Request $request){

        $responce = new stdClass();
        $responce->comment_ = '';

        $sysparam = DB::table('sysdb.sysparam')
                    ->where('compcode',session('compcode'))
                    ->where('source','AR')
                    ->where('trantype',$request->trantype);

        if($sysparam->exists()){
            $responce->comment_ = $sysparam->first()->comment_;
        }

        return json_encode($responce, JSON_INVALID_UTF8_SUBSTITUTE); 
    }

    public function print(Request $request){
        $debtorcode = $request->debtorcode;
        $datefr = $request->datefr;
        $dateto = $request->dateto;
        $datesend = $request->datesend;
        $comment_ = '';
        $sysparam = DB::table('sysdb.sysparam')
                    ->where('compcode',session('compcode'))
                    ->where('source','AR')
                    ->where('trantype','CLAIM');

        if($sysparam->exists()){
            $comment_ = $sysparam->first()->comment_;
        }

        $comment2_ = '';
        $sysparam = DB::table('sysdb.sysparam')
                    ->where('compcode',session('compcode'))
                    ->where('source','AR')
                    ->where('trantype','CLAIM2');

        if($sysparam->exists()){
            $comment2_ = $sysparam->first()->comment_;
        }

        $dbacthdr = DB::table('debtor.dbacthdr as dh')
                    ->select('dh.idno','dh.compcode','dh.source','dh.trantype','dh.auditno','dh.lineno_','dh.amount','dh.outamount','dh.recstatus','dh.entrydate','dh.entrytime','dh.entryuser','dh.reference','dh.recptno','dh.paymode','dh.tillcode','dh.tillno','dh.debtortype','dh.debtorcode','dh.payercode','dh.billdebtor','dh.remark','dh.mrn','dh.episno','dh.authno','dh.expdate','dh.adddate','dh.adduser','dh.upddate','dh.upduser','dh.deldate','dh.deluser','dh.epistype','dh.cbflag','dh.conversion','dh.payername','dh.hdrtype','dh.currency','dh.rate','dh.unit','dh.invno','dh.paytype','dh.bankcharges','dh.RCCASHbalance','dh.RCOSbalance','dh.RCFinalbalance','dh.PymtDescription','dh.orderno','dh.ponum','dh.podate','dh.termdays','dh.termmode','dh.deptcode','dh.posteddate','dh.approvedby','dh.approveddate','dh.approved_remark','dh.unallocated','dh.datesend','dh.quoteno','dh.preparedby','dh.prepareddate','dh.cancelby','dh.canceldate','dh.cancelled_remark','dh.pointofsales','dh.doctorcode','dh.LHDNStatus','dh.LHDNSubID','dh.LHDNCodeNo','dh.LHDNDocID','dh.LHDNSubBy','dh.category','dh.categorydept','pm.Name','pm.Staffid','gr.name as gr_name','epayr.refno','eps.reg_date','dm.name as debtorname','dm.address1','dm.address2','dm.address3','dm.address4','dm.creditterm','dm.creditlimit','dm.contact')
                    ->leftJoin('hisdb.pat_mast as pm', function($join){
                            $join = $join->where('pm.compcode', '=', session('compcode'))
                                        ->on('pm.NewMrn', '=', 'dh.mrn');
                        })
                    ->leftJoin('debtor.debtormast as dm', function($join){
                            $join = $join->where('dm.compcode', '=', session('compcode'))
                                        ->on('dm.debtorcode', '=', 'dh.payercode');
                        })
                    ->leftJoin('hisdb.epispayer as epayr', function($join){
                            $join = $join->where('epayr.compcode', '=', session('compcode'))
                                        ->on('epayr.mrn', '=', 'dh.mrn')
                                        ->on('epayr.episno', '=', 'dh.episno')
                                        ->on('epayr.payercode', '=', 'dh.payercode');
                        })
                    ->leftJoin('hisdb.episode as eps', function($join){
                            $join = $join->where('eps.compcode', '=', session('compcode'))
                                        ->on('eps.mrn', '=', 'dh.mrn')
                                        ->on('eps.episno', '=', 'dh.episno');
                        })
                    ->leftJoin('hisdb.guarantee as gr', function($join){
                            $join = $join->where('gr.compcode', '=', session('compcode'))
                                        ->on('gr.mrn', '=', 'dh.mrn')
                                        ->on('gr.episno', '=', 'dh.episno')
                                        ->where('gr.lineno_', '1');
                        })
                    ->where('dh.compcode', '=', session('compcode'))
                    ->where('dh.recstatus', '=', 'POSTED')
                    ->where('dh.source', '=', 'PB')
                    ->where('dh.trantype', '=', 'IN')
                    ->where('dh.payercode', '=', $debtorcode)
                    ->whereDate('dh.posteddate', '>=', $datefr)
                    ->whereDate('dh.posteddate', '<=', $dateto)
                    ->whereNotNull('dh.mrn')
                    ->whereNotNull('dh.episno')
                    ->get();

        $totamount = 0;
        foreach ($dbacthdr as $obj_q1) {
            $totamount = $totamount + $obj_q1->amount;
            $q2 = DB::table('debtor.billtrack as bt')
                    ->where('bt.compcode', '=', session('compcode'))
                    ->where('bt.trxcode', '=', 'STD')
                    ->where('bt.source', '=', 'PB')
                    ->where('bt.trantype', '=', 'IN')
                    ->where('bt.auditno', '=', $obj_q1->auditno);

            DB::table('debtor.dbacthdr')
                    ->where('compcode', '=', session('compcode'))
                    ->where('source', '=', 'PB')
                    ->where('trantype', '=', 'IN')
                    ->where('auditno', '=', $obj_q1->auditno)
                    ->where('lineno_', '=', $obj_q1->lineno_)
                    ->update([
                        'datesend' => $datesend
                    ]);

            if($q2->exists()){
                DB::table('debtor.billtrack')
                    ->where('compcode', '=', session('compcode'))
                    ->where('trxcode', '=', 'STD')
                    ->where('source', '=', 'PB')
                    ->where('trantype', '=', 'IN')
                    ->where('auditno', '=', $obj_q1->auditno)
                    ->update([
                        'trxdate' => $datesend,
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'recstatus' => 'ACTIVE',
                        'patientname' => $obj_q1->Name,
                        'staffno' => $obj_q1->Staffid,
                        'lastcomputerid' => session('computerid'),
                    ]);
            }else{
                DB::table('debtor.billtrack')
                    ->insert([
                        'compcode' => session('compcode'),
                        'source' => $obj_q1->source,
                        'trantype' => $obj_q1->trantype,
                        'auditno' => $obj_q1->auditno,
                        'lineno_' => $obj_q1->lineno_,
                        'trxcode' => 'STD',
                        'trxdate' => $datesend,
                        'adduser' => session('username'),
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'recstatus' => 'ACTIVE',
                        'patientname' => $obj_q1->Name,
                        'staffno' => $obj_q1->Staffid,
                        'computerid' => session('computerid'),
                        'lastcomputerid' => session('computerid'),
                    ]);
            }
        }

        $company = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();

        $currentDate = Carbon::now("Asia/Kuala_Lumpur")->format('d F Y');
        $debtormast = $dbacthdr->unique('debtorcode')[0];

        return view('finance.AR.claimBatch.claimBatch_pdfmake',compact('debtormast','dbacthdr','currentDate','comment_','comment2_','datesend','totamount','company'));
    }

    public function assign_grouping($grouping,$days){
        $group = 0;

        foreach ($grouping as $key => $value) {
            if(!empty($value) && $days >= intval($value)){
                $group = $key;
            }
        }

        return $group;
    }
}