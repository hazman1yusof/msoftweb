<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use App\Exports\APAgeingDtlExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Response;

class APAgeingDtl_ReportController extends defaultController
{   

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        return view('finance.AP.APAgeingDtl_Report.APAgeingDtl_Report');
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

    public function job_queue(Request $request){
        $responce = new stdClass();

        $table_ = DB::table('sysdb.job_queue')
                        ->where('compcode', session('compcode'))
                        ->where('page', 'APAgeing')
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
            $filename = 'APAgeingDetail '.Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d g:i A').'.xlsx';
        }else{
            $filename = 'APAgeingSummary '.Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d g:i A').'.xlsx';
        }

        $bytes = random_bytes(20);
        $process = bin2hex($bytes).'.xlsx';

        (new APAgeingDtlExport($process,$filename,$request->type,$request->date,$request->suppcode_from,$request->suppcode_to,$request->groupOne,$request->groupTwo,$request->groupThree,$request->groupFour,$request->groupFive,$request->groupSix))->store($process, \config('get_config.ATTACHMENT_UPLOAD'));

        return back();

        // return Excel::download(new APAgeingDtlExport($request->type,$request->date,$request->suppcode_from,$request->suppcode_to,$request->groupOne,$request->groupTwo,$request->groupThree,$request->groupFour,$request->groupFive,$request->groupSix), $filename);
    }
    
    public function showpdf(Request $request){
        $date = Carbon::parse($request->date)->format('Y-m-d');
        $suppcode_from = $request->suppcode_from;
        if(empty($request->suppcode_from)){
            $suppcode_from = '%';
        }
        $suppcode_to = $request->suppcode_to;

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

        $apacthdr = DB::table('finance.apacthdr as ap')
                    ->select('ap.suppcode','ap.source','ap.trantype','ap.auditno','ap.amount','ap.postdate','ap.remarks','ap.document','su.name','su.suppgroup','sg.description','ap.unit')
                    ->join('material.supplier as su', function($join) {
                        $join = $join->on('su.SuppCode', '=', 'ap.suppcode');
                        $join = $join->where('su.compcode', '=', session('compcode'));
                    })
                    ->leftjoin('material.suppgroup as sg', function($join) {
                        $join = $join->on('sg.suppgroup', '=', 'su.suppgroup');
                        $join = $join->where('sg.compcode', '=', session('compcode'));
                    })
                    ->where('ap.source', '=', 'AP')
                    ->where('ap.trantype', '=', 'IN')
                    ->where('ap.compcode','=',session('compcode'))
                    // ->where('ap.unit',session('unit'))
                    ->where('ap.recstatus', '=', 'POSTED')
                    ->whereDate('ap.postdate', '<=', $date)
                    ->whereBetween('su.suppcode', [$suppcode_from, $suppcode_to.'%'])
                    ->orderBy('ap.suppcode', 'ASC')
                    ->get();

        $array_report = [];

        foreach ($apacthdr as $key => $value){
            $value->newamt = 0;

            $hdr_amount = $value->amount;
            
            // to calculate interval (days)
            $datetime1 = new DateTime($date);
            $datetime2 = new DateTime($value->postdate);
            
            $interval = $datetime1->diff($datetime2);
            $days = $interval->format('%a');
            $value->group = $this->assign_grouping($grouping,$days);
            $value->days = $days;
            
            $alloc_sum = DB::table('finance.apalloc')
                    ->where('compcode', '=', session('compcode'))
                    ->where('suppcode', '=', $value->suppcode)
                    ->where('refsource', '=', $value->source)
                    ->where('reftrantype', '=', $value->trantype)
                    ->where('refauditno', '=', $value->auditno)
                    ->where('recstatus', '=', "POSTED")
                    ->whereDate('allocdate', '<=', $date)
                    ->sum('allocamount');

            $newamt = $hdr_amount - $alloc_sum;

            if(floatval($newamt) != 0.00){
                $value->newamt = $newamt;
                array_push($array_report, $value);
            }
        }
        
        // dd($array_report);

        $suppgroup = collect($array_report)->unique('suppgroup');
        $suppcode = collect($array_report)->unique('suppcode');
        
        $title = "AP AGEING DETAILS";
        
        $company = DB::table('sysdb.company')
            ->where('compcode','=',session('compcode'))
            ->first();

        return view('finance.AP.APAgeingDtl_Report.APAgeingDtl_Report_pdfmake',compact('suppgroup','suppcode','array_report','title','company','grouping'));
        
    }

    public function calc_bal($obj){
        $balance = 0;
        foreach ($obj->get() as $key => $value){
            
            switch ($value->trantype) {
                 case 'IN': //dr
                    $balance = $balance + floatval($value->amount);
                    break;
                case 'DN': //dr
                    $balance = $balance + floatval($value->amount);
                    break;
                case 'CN': //cr
                    $balance = $balance - floatval($value->amount);
                    break;
                case 'PV': //cr
                    $balance = $balance - floatval($value->amount);
                    break;
                case 'PD': //cr
                    $balance = $balance - floatval($value->amount);
                    break;
                default:
                    // code...
                    break;
            }
        }

        return $balance;
    }

    public function form(Request $request){   
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