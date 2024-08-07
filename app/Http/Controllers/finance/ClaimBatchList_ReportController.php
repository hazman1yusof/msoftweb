<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use PDF;
use App\Exports\ClaimBatchListExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class ClaimBatchList_ReportController extends defaultController
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function show(Request $request)
    {
        return view('finance.AR.ClaimBatchList_Report.ClaimBatchList_Report');
    }
    
    public function form(Request $request)
    {
        DB::enableQueryLog();
        switch($request->oper){
            case 'edit':
                return $this->edit($request);
            default:
                return 'error happen..';
        }
    }
    
    public function table(Request $request)
    {
        DB::enableQueryLog();
        switch($request->action){
            case 'get_table':
                return $this->get_table($request);
            default:
                return 'error happen..';
        }
    }
    
    public function report(Request $request)
    {
        DB::enableQueryLog();
        switch($request->action){
            case 'ClaimBatchList_pdf':
                return $this->ClaimBatchList_pdf($request);
            case 'ClaimBatchList_xls':
                return $this->ClaimBatchList_xls($request);
            default:
                return 'error happen..';
        }
    }
    
    public function edit(Request $request){
        
        DB::beginTransaction();
        
        try{
            
            $sysparam1 = DB::table('sysdb.sysparam')
                        ->where('compcode','=',session('compcode'))
                        ->where('source','=','AR')
                        ->where('trantype','=','CL');
            
            if(!$sysparam1->exists()){
                DB::table('sysdb.sysparam')
                    ->insert([
                        'compcode' => session('compcode'),
                        'source' => 'AR',
                        'trantype' => 'CL',
                        'description' => 'coverletter',
                        'comment_' => $request->content,
                        'pvalue1' => $request->title,
                        'pvalue2' => $request->sign_off,
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }else{
                $sysparam1
                    ->update([
                        'comment_' => $request->content,
                        'pvalue1' => $request->title,
                        'pvalue2' => $request->sign_off,
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }
            
            $sysparam2 = DB::table('sysdb.sysparam')
                        ->where('compcode','=',session('compcode'))
                        ->where('source','=','AR')
                        ->where('trantype','=','PIC');
            
            if(!$sysparam2->exists()){
                DB::table('sysdb.sysparam')
                    ->insert([
                        'compcode' => session('compcode'),
                        'source' => 'AR',
                        'trantype' => 'PIC',
                        'pvalue1' => $request->officer,
                        'pvalue2' => $request->designation,
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }else{
                $sysparam2
                    ->update([
                        'pvalue1' => $request->officer,
                        'pvalue2' => $request->designation,
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }
            
            DB::commit();
            
        }catch(\Exception $e){
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function get_table(Request $request){
        
        $sysparam1_obj = DB::table('sysdb.sysparam')
                        ->select('comment_ as content','pvalue1 as title','pvalue2 as sign_off')
                        ->where('compcode','=',session('compcode'))
                        ->where('source','=','AR')
                        ->where('trantype','=','CL');
        
        $sysparam2_obj = DB::table('sysdb.sysparam')
                        ->select('pvalue1 as officer','pvalue2 as designation')
                        ->where('compcode','=',session('compcode'))
                        ->where('source','=','AR')
                        ->where('trantype','=','PIC');
        
        $responce = new stdClass();
        
        if($sysparam1_obj->exists()){
            $sysparam1_obj = $sysparam1_obj->first();
            $responce->sysparam1 = $sysparam1_obj;
        }
        
        if($sysparam2_obj->exists()){
            $sysparam2_obj = $sysparam2_obj->first();
            $responce->sysparam2 = $sysparam2_obj;
        }
        
        return json_encode($responce);
        
    }
    
    public function ClaimBatchList_pdf(Request $request){
        $validator = Validator::make($request->all(), [
            'date1' => 'required',
            'debtorcode_to' => 'required',
        ]);
        
        if($validator->fails()){
            abort(404);
        }
        
        $date1 = $request->date1;
        $epis_type = $request->epis_type;
        $debtorcode_to = $request->debtorcode_to;
        $title = $request->title;
        $content = $request->content;
        $sign_off = $request->sign_off;
        $officer = $request->officer;
        $designation = $request->designation;
        
        // $sysparam1_obj = DB::table('sysdb.sysparam')
        //                 ->select('comment_ as content','pvalue1 as title','pvalue2 as sign_off')
        //                 ->where('compcode','=',session('compcode'))
        //                 ->where('source','=','AR')
        //                 ->where('trantype','=','CL')
        //                 ->first();
        
        // $sysparam2_obj = DB::table('sysdb.sysparam')
        //                 ->select('pvalue1 as officer','pvalue2 as designation')
        //                 ->where('compcode','=',session('compcode'))
        //                 ->where('source','=','AR')
        //                 ->where('trantype','=','PIC')
        //                 ->first();
        
        $debtormast = DB::table('debtor.debtormast')
                    ->where('compcode','=',session('compcode'))
                    ->where('debtorcode','=',$debtorcode_to)
                    ->first();
        
        $company = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();
        
        $header = new stdClass();
        $header->printby = session('username');
        $header->date1 = $date1;
        $header->epis_type = $epis_type;
        $header->debtorcode_to = $debtorcode_to;

        $datefr = Carbon::parse($request->date1)->startOfMonth()->format('Y-m-d');
        $dateto = Carbon::parse($request->date1)->format('Y-m-d');
        
        $debtormast_obj = DB::table('debtor.dbacthdr as dh')
                    ->select('dh.debtorcode', 'dm.debtorcode', 'dm.name', 'dm.address1', 'dm.address2', 'dm.address3', 'dm.address4')
                    ->leftJoin('debtor.debtormast as dm', function ($join){
                        $join = $join->on('dm.debtorcode', '=', 'dh.debtorcode')
                                    ->where('dm.compcode', '=', session('compcode'));
                    })
                    ->where('dh.compcode', '=', session('compcode'))
                    ->whereIn('dh.recstatus', ['POSTED','ACTIVE'])
                    ->where('dh.debtorcode',$debtorcode_to)
                    ->whereBetween('dh.posteddate', [$datefr, $dateto])
                    ->orderBy('dm.debtorcode', 'ASC')
                    ->distinct('dm.debtorcode');
        
        $debtormast_obj = $debtormast_obj->get(['dm.debtorcode', 'dm.name', 'dm.address1', 'dm.address2', 'dm.address3', 'dm.address4']);
        
        $array_report = [];
        foreach($debtormast_obj as $key => $value){
            $dbacthdr = DB::table('debtor.dbacthdr as dh')
                        ->select('dh.idno', 'dh.source', 'dh.trantype', 'pm.Name', 'dh.auditno', 'dh.lineno_', 'dh.amount', 'dh.outamount', 'dh.recstatus', 'dh.entrydate', 'dh.entrytime', 'dh.entryuser', 'dh.reference', 'dh.recptno', 'dh.paymode', 'dh.tillcode', 'dh.tillno', 'dh.debtortype', 'dh.debtorcode', 'dh.payercode', 'dh.billdebtor', 'dh.remark', 'dh.mrn', 'dh.episno', 'dh.authno', 'dh.expdate', 'dh.adddate', 'dh.adduser', 'dh.upddate', 'dh.upduser', 'dh.deldate', 'dh.deluser', 'dh.epistype', 'dh.cbflag', 'dh.conversion', 'dh.payername', 'dh.hdrtype', 'dh.currency', 'dh.rate', 'dh.unit', 'dh.invno', 'dh.paytype', 'dh.bankcharges', 'dh.RCCASHbalance', 'dh.RCOSbalance', 'dh.RCFinalbalance', 'dh.PymtDescription', 'dh.orderno', 'dh.ponum', 'dh.podate', 'dh.termdays', 'dh.termmode', 'dh.deptcode', 'dh.posteddate', 'dh.approvedby', 'dh.approveddate', 'dh.datesend')
                        ->leftJoin('hisdb.pat_mast as pm', function ($join){
                            $join = $join->on('pm.MRN', '=', 'dh.mrn')
                                        ->where('pm.compcode', '=', session('compcode'));
                        })
                        ->where('dh.compcode', '=', session('compcode'))
                        ->whereIn('dh.recstatus', ['POSTED','ACTIVE'])
                        ->where('debtorcode',$value->debtorcode)
                        ->whereBetween('dh.posteddate', [$datefr, $dateto])
                        ->orderBy('dh.posteddate', 'ASC')
                        ->get();
            
            $calc_openbal = DB::table('debtor.dbacthdr as dh')
                            ->where('dh.compcode', '=', session('compcode'))
                            ->whereIn('dh.recstatus', ['POSTED','ACTIVE'])
                            ->where('dh.debtorcode', '=', $value->debtorcode)
                            ->whereDate('dh.posteddate', '<', $datefr);
            
            $openbal = $this->calc_openbal($calc_openbal);
            $value->openbal = $openbal;
            
            // $value->datesend = '';
            $value->reference = '';
            $value->amount_dr = 0;
            $value->amount_cr = 0;
            $balance = $openbal;
            foreach($dbacthdr as $key => $value){
                switch($value->trantype){
                    case 'IN':
                        // $value->datesend = $value->datesend;
                        if($value->mrn == '0' || $value->mrn == ''){
                            $value->reference = $value->remark;
                        }else{
                            $value->reference = $value->Name;
                        }
                        $value->amount_dr = $value->amount;
                        $balance = $balance + floatval($value->amount);
                        $value->balance = $balance;
                        array_push($array_report, $value);
                        break;
                    case 'DN':
                        $value->reference = $value->remark;
                        $value->amount_dr = $value->amount;
                        $balance = $balance + floatval($value->amount);
                        $value->balance = $balance;
                        array_push($array_report, $value);
                        break;
                    case 'BC':
                        // $value->reference
                        $value->amount_dr = $value->amount;
                        $balance = $balance + floatval($value->amount);
                        $value->balance = $balance;
                        array_push($array_report, $value);
                        break;
                    case 'RF':
                        if($value->mrn == '0' || $value->mrn == ''){
                            $value->reference = $value->remark;
                        }else{
                            $value->reference = $value->Name;
                        }
                        $value->amount_dr = $value->amount;
                        $balance = $balance + floatval($value->amount);
                        $value->balance = $balance;
                        array_push($array_report, $value);
                        break;
                    case 'CN':
                        $value->reference = $value->remark;
                        $value->amount_cr = $value->amount;
                        $balance = $balance - floatval($value->amount);
                        $value->balance = $balance;
                        array_push($array_report, $value);
                        break;
                    case 'RC':
                        $value->reference = $value->recptno;
                        $value->amount_cr = $value->amount;
                        $balance = $balance - floatval($value->amount);
                        $value->balance = $balance;
                        array_push($array_report, $value);
                        break;
                    case 'RD':
                        $value->reference = $value->recptno;
                        $value->amount_cr = $value->amount;
                        $balance = $balance - floatval($value->amount);
                        $value->balance = $balance;
                        array_push($array_report, $value);
                        break;
                    case 'RT':
                        // $value->reference
                        $value->amount_cr = $value->amount;
                        $balance = $balance - floatval($value->amount);
                        $value->balance = $balance;
                        array_push($array_report, $value);
                        break;
                    default:
                        // code...
                        break;
                }
            }
        }
        
        return view('finance.AR.ClaimBatchList_Report.ClaimBatchList_Report_pdfmake',compact('date1','epis_type','title','content','sign_off','officer','designation','debtormast','company','header','array_report','debtormast_obj'));
    }
    
    public function ClaimBatchList_xls(Request $request){
        $validator = Validator::make($request->all(), [
            'date1' => 'required',
            'debtorcode_to' => 'required',
        ]);
        
        if($validator->fails()){
            abort(404);
        }
        
        $date1 = $request->date1;
        $epis_type = $request->epis_type;
        $debtorcode_to = $request->debtorcode_to;
        $title = $request->title;
        $content = $request->content;
        $sign_off = $request->sign_off;
        $officer = $request->officer;
        $designation = $request->designation;
        
        return Excel::download(new ClaimBatchListExport($date1,$epis_type,$debtorcode_to,$title,$content,$sign_off,$officer,$designation), 'Claim Batch Listing.xlsx');
    }

    public function calc_openbal($obj){
        
        $balance = 0;
        
        foreach($obj->get() as $key => $value){
            switch($value->trantype){
                case 'IN':
                    $balance = $balance + floatval($value->amount);
                    break;
                case 'DN':
                    $balance = $balance + floatval($value->amount);
                    break;
                case 'BC':
                    $balance = $balance + floatval($value->amount);
                    break;
                case 'RF':
                    $balance = $balance + floatval($value->amount);
                    break;
                case 'CN':
                    $balance = $balance - floatval($value->amount);
                    break;
                case 'RC':
                    $balance = $balance - floatval($value->amount);
                    break;
                case 'RD':
                    $balance = $balance - floatval($value->amount);
                    break;
                case 'RT':
                    $balance = $balance - floatval($value->amount);
                    break;
                default:
                    // code...
                    break;
            }
        }
        
        return $balance;
    }
    
}

