<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use stdClass;
use App\Http\Controllers\defaultController;
use DB;
use DateTime;
use Carbon\Carbon;

class ReminderController extends defaultController
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
                    ->where('trantype','remind1');

        if($sysparam->exists()){
            $comment_ = $sysparam->first()->comment_;
        }else{
            $comment_ = "";
        }

        return view('finance.AR.reminder.reminder',compact('comment_'));
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
                        ->where('trantype',$request->trantype);

            if($sysparam->exists()){
                DB::table('sysdb.sysparam')
                        ->where('compcode',session('compcode'))
                        ->where('source','AR')
                        ->where('trantype',$request->trantype)
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
                            'trantype' => $request->trantype,
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
        $trantype = $request->trantype;
        if(!$trantype){
            abort(404);
        }

        $grouping = [0,30,60,90,120];
        $grouping_tot = [0,0,0,0,0];
        $debtorcode = $request->debtorcode;
        $date = $request->date;
        $days_greater = $request->days;
        $comment_ = '';
        $sysparam = DB::table('sysdb.sysparam')
                    ->where('compcode',session('compcode'))
                    ->where('source','AR')
                    ->where('trantype',$request->trantype);

        if($sysparam->exists()){
            $comment_ = $sysparam->first()->comment_;
        }

        $debtormast = DB::table('debtor.debtormast as dm')
                        ->select('dh.idno', 'dh.source', 'dh.trantype', 'dh.auditno', 'dh.lineno_', 'dh.amount', 'dh.outamount', 'dh.recstatus', 'dh.entrydate', 'dh.entrytime', 'dh.entryuser', 'dh.reference', 'dh.reference as real_reference', 'dh.recptno', 'dh.paymode', 'dh.tillcode', 'dh.tillno', 'dh.debtortype', 'dh.debtorcode', 'dh.payercode', 'dh.billdebtor', 'dh.remark', 'dh.mrn', 'dh.episno', 'dh.authno', 'dh.expdate', 'dh.adddate', 'dh.adduser', 'dh.upddate', 'dh.upduser', 'dh.deldate', 'dh.deluser', 'dh.epistype', 'dh.cbflag', 'dh.conversion', 'dh.payername', 'dh.hdrtype', 'dh.currency', 'dh.rate', 'dh.unit', 'dh.invno', 'dh.paytype', 'dh.bankcharges', 'dh.RCCASHbalance', 'dh.RCOSbalance', 'dh.RCFinalbalance', 'dh.PymtDescription', 'dh.orderno', 'dh.ponum', 'dh.podate', 'dh.termdays', 'dh.termmode', 'dh.deptcode', 'dh.posteddate', 'dh.approvedby', 'dh.approveddate','dm.debtortype','dm.debtorcode','dm.name','dm.address1','dm.address2','dm.address3','dm.address4','dm.creditterm','dm.creditlimit','dm.contact','dh.datesend', 'pm.Name as pm_name')
                        ->join('debtor.dbacthdr as dh', function($join) use ($date){
                            $join = $join->on('dh.debtorcode', '=', 'dm.debtorcode')
                                         ->whereDate('dh.posteddate', '<=', $date)
                                         ->where('dh.compcode', '=', session('compcode'))
                                         ->where('dh.recstatus', '=', 'POSTED');
                        })->leftJoin('hisdb.pat_mast as pm', function($join){
                            $join = $join->on('pm.NewMrn', '=', 'dh.mrn')
                                         ->where('pm.compcode', '=', session('compcode'));
                        })
                        ->where('dm.compcode', '=', session('compcode'))
                        ->where('dm.debtorcode', $debtorcode);

        if(!$debtormast->exists()){
            abort(403, 'Debtor doesnt have any activity');
        }

        $debtormast = $debtormast->get();

        $array_report = [];

        $days_greater_tot = 0;
        foreach ($debtormast as $key => $value){
            $value->remark = '';
            $value->doc_no = '';
            $value->newamt = 0;

            $hdr_amount = $value->amount;
            
            // to calculate interval (days)
            $datetime1 = new DateTime($date);
            $datetime2 = new DateTime($value->posteddate);
            
            $interval = $datetime1->diff($datetime2);
            $days = $interval->format('%a');
            $value->group = $this->assign_grouping($grouping,$days);
            $value->days = $days;
            
            if($value->trantype == 'IN' || $value->trantype =='DN') {
                $alloc_sum = DB::table('debtor.dballoc as da')
                        ->where('da.compcode', '=', session('compcode'))
                        ->where('da.recstatus', '=', "POSTED")
                        // ->where('da.debtorcode', '=', $value->debtorcode)
                        ->where('da.refsource', '=', $value->source)
                        ->where('da.reftrantype', '=', $value->trantype)
                        ->where('da.refauditno', '=', $value->auditno)
                        // ->where('da.reflineno', '=', $value->lineno_)
                        ->whereDate('da.allocdate', '<=', $date)

                // dd($this->getQueries($alloc_sum));


                        ->sum('da.amount');
                
                $newamt = $hdr_amount - $alloc_sum;
            }else{
                $doc_sum = DB::table('debtor.dballoc as da')
                        ->where('da.compcode', '=', session('compcode'))
                        ->where('da.recstatus', '=', "POSTED")
                        // ->where('da.debtorcode', '=', $value->debtorcode)
                        ->where('da.docsource', '=', $value->source)
                        ->where('da.doctrantype', '=', $value->trantype)
                        ->where('da.docauditno', '=', $value->auditno)
                        ->whereDate('da.allocdate', '<=', $date)
                        ->sum('da.amount');
                
                $ref_sum = DB::table('debtor.dballoc as da')
                        ->where('da.compcode', '=', session('compcode'))
                        ->where('da.recstatus', '=', "POSTED")
                        // ->where('da.debtorcode', '=', $value->debtorcode)
                        ->where('da.refsource', '=', $value->source)
                        ->where('da.reftrantype', '=', $value->trantype)
                        ->where('da.refauditno', '=', $value->auditno)
                        ->whereDate('da.allocdate', '<=', $date)
                        ->sum('da.amount');
                
                $newamt = -($hdr_amount - $doc_sum - $ref_sum);
            }
            
            switch ($value->trantype) {
                case 'IN':
                    if($value->mrn == '0' || $value->mrn == ''){
                        if(!empty($value->payername)){
                            $value->reference = $value->payername;
                        }
                    }else{
                        $value->reference = str_replace('`', '', $value->pm_name);
                    }
                    $value->doc_no = $value->trantype.'/'.str_pad($value->invno, 7, "0", STR_PAD_LEFT);
                    $value->amount_dr = $newamt;
                    $value->newamt = $newamt;
                    if(floatval($newamt) != 0.00){
                        array_push($array_report, $value);
                    }
                    break;
                case 'DN':
                    $value->reference = $value->reference;
                    $value->doc_no = $value->trantype.'/'.str_pad($value->auditno, 7, "0", STR_PAD_LEFT);
                    $value->amount_dr = $newamt;
                    $value->newamt = $newamt;
                    if(floatval($newamt) != 0.00){
                        array_push($array_report, $value);
                    }
                    break;
                case 'BC':
                    // $value->remark
                    $value->doc_no = $value->trantype.'/'.str_pad($value->auditno, 7, "0", STR_PAD_LEFT);
                    $value->amount_dr = $newamt;
                    $value->newamt = $newamt;
                    if(floatval($newamt) != 0.00){
                        array_push($array_report, $value);
                    }
                    break;
                case 'RF':
                    if($value->mrn == '0' || $value->mrn == ''){
                        // $value->reference = $value->remark;
                        $value->reference = $value->reference;
                    }else{
                        $value->reference = str_replace('`', '', $value->pm_name);
                    }
                    $value->doc_no = $value->recptno;
                    $value->amount_dr = $newamt;
                    $value->newamt = $newamt;
                    if(floatval($newamt) != 0.00){
                        array_push($array_report, $value);
                    }
                    break;
                case 'CN':
                    $value->remark = $value->remark;
                    $value->doc_no = $value->trantype.'/'.str_pad($value->auditno, 7, "0", STR_PAD_LEFT);
                    $value->amount_cr = $newamt;
                    $value->newamt = $newamt;
                    if(floatval($newamt) != 0.00){
                        array_push($array_report, $value);
                    }
                    break;
                case 'RC':
                    $value->remark = $value->remark;
                    $value->doc_no = $value->recptno;
                    $value->reference = $value->recptno;
                    $value->amount_cr = $newamt;
                    $value->newamt = $newamt;
                    if(floatval($newamt) != 0.00){
                        array_push($array_report, $value);
                    }
                    break;
                case 'RD':
                    $value->remark = $value->remark;
                    $value->doc_no = $value->recptno;
                    $value->reference = $value->recptno;
                    $value->amount_cr = $newamt;
                    $value->newamt = $newamt;
                    if(floatval($newamt) != 0.00){
                        array_push($array_report, $value);
                    }
                    break;
                case 'RT':
                    // $value->remark
                    $value->doc_no = $value->trantype.'/'.str_pad($value->auditno, 7, "0", STR_PAD_LEFT);
                    $value->amount_cr = $newamt;
                    $value->newamt = $newamt;
                    if(floatval($newamt) != 0.00){
                        array_push($array_report, $value);
                    }
                    break;
                default:
                    // code...
                    break;
            }

            $grouping_tot[$value->group] = $grouping_tot[$value->group] + $newamt;

            if($days > $days_greater){
                $days_greater_tot = $days_greater_tot + $newamt;
            }
        }

        $currentDate = Carbon::now("Asia/Kuala_Lumpur")->format('d F Y');
        $debtormast = $debtormast->unique('debtorcode')[0];

        // dd($array_report);

        return view('finance.AR.reminder.reminder_pdfmake',compact('array_report','debtormast','grouping','days_greater_tot','grouping_tot','currentDate','comment_'));
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