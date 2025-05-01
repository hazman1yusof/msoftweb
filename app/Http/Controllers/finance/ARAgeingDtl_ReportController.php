<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use App\Exports\ARAgeingDtlExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Response;

class ARAgeingDtl_ReportController extends defaultController
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
        $comp = DB::table('sysdb.company')->where('compcode','=',session('compcode'))->first();
        return view('finance.AR.ARAgeingDtl_Report.ARAgeingDtl_Report',[
            'company_name' => $comp->name
        ]);
    }

    public function table(Request $request){ 
        switch($request->action){
            case 'job_queue':
                return $this->job_queue($request);
            case 'download':
                return $this->download($request);
            default:
                return 'error happen..';
        }
    }
    
    public function form(Request $request)
    {
        switch($request->action){
            // case 'add':
            //     return $this->defaultAdd($request);
            // case 'edit':
            //     return $this->defaultEdit($request);
            // case 'del':
            //     return $this->defaultDel($request);
            case 'showExcel':
                return $this->showExcel($request);
            default:
                return 'error happen..';
        }
    }

    public function job_queue(Request $request){
        $responce = new stdClass();

        $table_ = DB::table('sysdb.job_queue')
                        ->where('compcode', session('compcode'))
                        ->where('page', 'ARAgeing')
                        ->orderBy('idno','desc');

        $count = $table_->count();
        $table = $table_
                    ->offset($request->start)
                    ->limit($request->length)->get();

        foreach ($table as $key => $value) {
            $value->download = " ";
        }

        $responce->data = $table;
        $responce->recordsTotal = $count;
        $responce->recordsFiltered = $count;
        return json_encode($responce);
    }

    public function download(Request $request){
        $job_queue = DB::table('sysdb.job_queue')
                        ->where('idno', $request->idno)
                        ->first();

        $attachment_path = \config('get_config.ATTACHMENT_PATH');

        $file = $attachment_path."\\uploads\\".$job_queue->process;
        // dump($file);
        return Response::download($file,$job_queue->filename);
    }
    
    public function showExcel(Request $request){

        if($request->type == 'detail'){
            $filename = 'ARAgeingDetail '.Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d g:i A').'.xlsx';
        }else{
            $filename = 'ARAgeingSummary '.Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d g:i A').'.xlsx';
        }

        $bytes = random_bytes(20);
        $process = bin2hex($bytes).'.xlsx';

        (new ARAgeingDtlExport($process,$filename,$request->type,$request->date,$request->debtortype,$request->debtorcode_from,$request->debtorcode_to,$request->groupOne,$request->groupTwo,$request->groupThree,$request->groupFour,$request->groupFive,$request->groupSix))->store($process, \config('get_config.ATTACHMENT_UPLOAD'));

        // (new InvoicesExport)->queue('invoices.xlsx');

        // return back();

        // return Excel::download(new ARAgeingDtlExport($request->type,$request->date,$request->debtortype,$request->debtorcode_from,$request->debtorcode_to,$request->groupOne,$request->groupTwo,$request->groupThree,$request->groupFour,$request->groupFive,$request->groupSix), $filename);
    }
    
    public function showpdf(Request $request){
        
        $date = Carbon::parse($request->date)->format('Y-m-d');
        $date_title = Carbon::parse($request->date)->format('d-m-Y');
        $debtortype = $request->debtortype;
        $debtorcode_from = $request->debtorcode_from;
        if(empty($request->debtorcode_from)){
            $debtorcode_from = '%';
        }
        $debtorcode_to = $request->debtorcode_to;

        $groupOne = $request->groupOne;
        $groupTwo = $request->groupTwo;
        $groupThree = $request->groupThree;
        $groupFour = $request->groupFour;
        $groupFive = $request->groupFive;
        $groupSix = $request->groupSix;

        $grouping = [];
        $grouping[0] = 0;
        if(!empty($groupOne)){
            $grouping[1] = $groupOne;
        }
        if(!empty($groupTwo)){
            $grouping[2] = $groupTwo;
        }
        if(!empty($groupThree)){
            $grouping[3] = $groupThree;
        }
        if(!empty($groupFour)){
            $grouping[4] = $groupFour;
        }
        if(!empty($groupFive)){
            $grouping[5] = $groupFive;
        }
        if(!empty($groupSix)){
            $grouping[6] = $groupSix;
        }
        
        $debtormast = DB::table('debtor.debtormast as dm')
                        ->select('dh.idno', 'dh.source', 'dh.trantype', 'dh.auditno', 'dh.lineno_', 'dh.amount', 'dh.outamount', 'dh.recstatus', 'dh.entrydate', 'dh.entrytime', 'dh.entryuser', 'dh.reference', 'dh.recptno', 'dh.paymode', 'dh.tillcode', 'dh.tillno', 'dh.debtortype', 'dh.debtorcode', 'dh.payercode', 'dh.billdebtor', 'dh.remark', 'dh.mrn', 'dh.episno', 'dh.authno', 'dh.expdate', 'dh.adddate', 'dh.adduser', 'dh.upddate', 'dh.upduser', 'dh.deldate', 'dh.deluser', 'dh.epistype', 'dh.cbflag', 'dh.conversion', 'dh.payername', 'dh.hdrtype', 'dh.currency', 'dh.rate', 'dh.unit', 'dh.invno', 'dh.paytype', 'dh.bankcharges', 'dh.RCCASHbalance', 'dh.RCOSbalance', 'dh.RCFinalbalance', 'dh.PymtDescription', 'dh.orderno', 'dh.ponum', 'dh.podate', 'dh.termdays', 'dh.termmode', 'dh.deptcode', 'dh.posteddate', 'dh.approvedby', 'dh.approveddate', 'pm.Name as pm_name','dm.debtortype','dt.debtortycode','dt.description','dm.name')
                        ->join('debtor.debtortype as dt', function($join) use ($debtortype){
                            $join = $join->on('dt.debtortycode', '=', 'dm.debtortype')
                                         ->where('dt.compcode', '=', session('compcode'));
                            if(strtoupper($debtortype)!='ALL'){
                                $join = $join->where('dt.debtortycode',$debtortype);
                            }
                        })
                        ->join('debtor.dbacthdr as dh', function($join) use ($date){
                            $join = $join->on('dh.debtorcode', '=', 'dm.debtorcode')
                                         ->whereDate('dh.posteddate', '<=', $date)
                                         ->where('dh.compcode', '=', session('compcode'));
                        })->leftJoin('hisdb.pat_mast as pm', function($join){
                            $join = $join->on('pm.MRN', '=', 'dh.mrn')
                                         ->where('pm.compcode', '=', session('compcode'));
                        })
                        ->where('dm.compcode', '=', session('compcode'))
                        ->whereBetween('dm.debtorcode', [$debtorcode_from,$debtorcode_to.'%'])
                        ->orderBy('dm.debtorcode', 'ASC')
                        ->get();

        $array_report = [];

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
                        ->where('da.debtorcode', '=', $value->debtorcode)
                        ->where('da.refsource', '=', $value->source)
                        ->where('da.reftrantype', '=', $value->trantype)
                        ->where('da.refauditno', '=', $value->auditno)
                        ->where('da.recstatus', '=', "POSTED")
                        ->whereDate('da.allocdate', '<=', $date)
                        ->sum('da.amount');
                
                $newamt = $hdr_amount - $alloc_sum;
            }else{
                $doc_sum = DB::table('debtor.dballoc as da')
                        ->where('da.compcode', '=', session('compcode'))
                        ->where('da.debtorcode', '=', $value->debtorcode)
                        ->where('da.docsource', '=', $value->source)
                        ->where('da.doctrantype', '=', $value->trantype)
                        ->where('da.docauditno', '=', $value->auditno)
                        ->where('da.recstatus', '=', "POSTED")
                        ->whereDate('da.allocdate', '<=', $date)
                        ->sum('da.amount');
                
                $ref_sum = DB::table('debtor.dballoc as da')
                        ->where('da.compcode', '=', session('compcode'))
                        ->where('da.debtorcode', '=', $value->debtorcode)
                        ->where('da.refsource', '=', $value->source)
                        ->where('da.reftrantype', '=', $value->trantype)
                        ->where('da.refauditno', '=', $value->auditno)
                        ->where('da.recstatus', '=', "POSTED")
                        ->whereDate('da.allocdate', '<=', $date)
                        ->sum('da.amount');
                
                $newamt = -($hdr_amount - $doc_sum - $ref_sum);
            }
            
            switch ($value->trantype) {
                case 'IN':
                    if($value->mrn == '0' || $value->mrn == ''){
                        $value->remark = $value->remark;
                    }else{
                        $value->remark = $value->pm_name;
                    }
                    $value->doc_no = $value->trantype.'/'.str_pad($value->invno, 5, "0", STR_PAD_LEFT);
                    $value->newamt = $newamt;
                    if(floatval($newamt) != 0.00){
                        array_push($array_report, $value);
                    }
                    break;
                case 'DN':
                    $value->remark = $value->remark;
                    $value->doc_no = $value->trantype.'/'.str_pad($value->auditno, 5, "0", STR_PAD_LEFT);
                    $value->newamt = $newamt;
                    if(floatval($newamt) != 0.00){
                        array_push($array_report, $value);
                    }
                    break;
                case 'BC':
                    // $value->remark
                    $value->doc_no = $value->trantype.'/'.str_pad($value->auditno, 5, "0", STR_PAD_LEFT);
                    $value->newamt = $newamt;
                    if(floatval($newamt) != 0.00){
                        array_push($array_report, $value);
                    }
                    break;
                case 'RF':
                    $value->remark = $value->remark;
                    $value->doc_no = $value->recptno;
                    $value->newamt = $newamt;
                    if(floatval($newamt) != 0.00){
                        array_push($array_report, $value);
                    }
                    break;
                case 'CN':
                    $value->remark = $value->remark;
                    $value->doc_no = $value->trantype.'/'.str_pad($value->auditno, 5, "0", STR_PAD_LEFT);
                    $value->newamt = $newamt;
                    if(floatval($newamt) != 0.00){
                        array_push($array_report, $value);
                    }
                    break;
                case 'RC':
                    $value->remark = $value->remark;
                    $value->doc_no = $value->recptno;
                    $value->newamt = $newamt;
                    if(floatval($newamt) != 0.00){
                        array_push($array_report, $value);
                    }
                    break;
                case 'RD':
                    $value->remark = $value->remark;
                    $value->doc_no = $value->recptno;
                    $value->newamt = $newamt;
                    if(floatval($newamt) != 0.00){
                        array_push($array_report, $value);
                    }
                    break;
                case 'RT':
                    // $value->remark
                    $value->doc_no = $value->trantype.'/'.str_pad($value->auditno, 5, "0", STR_PAD_LEFT);
                    $value->newamt = $newamt;
                    if(floatval($newamt) != 0.00){
                        array_push($array_report, $value);
                    }
                    break;
                default:
                    // code...
                    break;
            }
            
        }
        
        // dd($array_report);

        $debtortype = collect($array_report)->unique('debtortycode');
        $debtorcode = collect($array_report)->unique('debtorcode');
        
        $title = "AR AGEING DETAILS as at ".$date_title;
        
        $company = DB::table('sysdb.company')
                    ->where('compcode', '=', session('compcode'))
                    ->first();
        
        return view('finance.AR.ARAgeingDtl_Report.ARAgeingDtl_Report_pdfmake', compact('debtortype','debtorcode','array_report','title','company','grouping'));
        
    }
    
    public function calc_ageing($newamt,$days,$groupOne,$groupTwo,$groupThree,$groupFour,$groupFive,$groupSix){
        $groupOne = range(1, $groupOne);
        $groupOne_last = $groupOne[count($groupOne) - 1];
        $groupOne_text = '1 - '.$groupOne_last.' days';
        
        $groupTwo_first = $groupOne_last + 1;
        $groupTwo = range($groupTwo_first, $groupTwo);
        $groupTwo_last = $groupTwo[count($groupTwo) - 1];
        $groupTwo_text = $groupTwo_first.' - '.$groupTwo_last.' days';
        
        $groupThree_first = $groupTwo_last + 1;
        $groupThree = range($groupThree_first, $groupThree);
        $groupThree_last = $groupThree[count($groupThree) - 1];
        $groupThree_text = $groupThree_first.' - '.$groupThree_last.' days';
        
        $groupFour_first = $groupThree_last + 1;
        $groupFour = range($groupFour_first, $groupFour);
        $groupFour_last = $groupFour[count($groupFour) - 1];
        $groupFour_text = $groupFour_first.' - '.$groupFour_last.' days';
        
        $groupFive_first = $groupFour_last + 1;
        $groupFive = range($groupFive_first, $groupFive);
        $groupFive_last = $groupFive[count($groupFive) - 1];
        $groupFive_text = $groupFive_first.' - '.$groupFive_last.' days';
        
        $groupSix_first = $groupFive_last + 1;
        $groupSix = range($groupSix_first, $groupSix);
        $groupSix_last = $groupSix[count($groupSix) - 1];
        $groupSix_text = '> '.$groupFive_last.' days';
        
        // dd($groupOne_text,$groupTwo_text,$groupThree_text,$groupFour_text,$groupFive_text,$groupSix_text);
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