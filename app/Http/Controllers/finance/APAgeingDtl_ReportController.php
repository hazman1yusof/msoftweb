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

    public function showExcel(Request $request){
        return Excel::download(new APAgeingDtlExport($request->suppcode_from,$request->suppcode_to,$request->date_ag), 'APAgeingDtl.xlsx');
    }
    
    public function showpdf(Request $request){

        $date_ag = Carbon::parse($request->date_ag)->format('Y-m-d');
        $suppcode_from = $request->suppcode_from;
        if(empty($request->suppcode_from)){
            $suppcode_from = '%';
        }
        $suppcode_to = $request->suppcode_to;

        $supp_group = DB::table('finance.apacthdr as ap')
                    ->select('ap.suppgroup', 'sg.description AS sg_desc')
                    ->join('material.suppgroup as sg', function($join){
                        $join = $join->on('sg.suppgroup', '=', 'ap.suppgroup');
                        $join = $join->where('sg.compcode', '=', session('compcode'));
                    })
                    ->where('ap.compcode','=',session('compcode'))
                    ->where('ap.unit',session('unit'))
                    ->where('ap.recstatus', '=', 'POSTED')
                    ->whereDate('ap.postdate', '<=', $date_ag)
                    ->whereBetween('ap.suppcode', [$suppcode_from, $suppcode_to.'%'])
                    ->orderBy('ap.suppgroup', 'ASC')
                    ->distinct('ap.suppgroup');
    
        $supp_group = $supp_group->get(['ap.suppgroup','sg.sg_desc']);

        //dd($supp_group);

        $supp_code = DB::table('finance.apacthdr as ap')
                    ->select('ap.suppcode', 'su.Name AS supplier_name', 'ap.suppgroup')
                    ->join('material.supplier as su', function($join){
                        $join = $join->on('su.SuppCode', '=', 'ap.suppcode');
                        $join = $join->where('su.compcode', '=', session('compcode'));
                    })
                    ->where('ap.compcode','=',session('compcode'))
                    ->where('ap.unit',session('unit'))
                    ->where('ap.recstatus', '=', 'POSTED')
                    ->whereDate('ap.postdate', '<=', $date_ag)
                    ->whereBetween('ap.suppcode', [$suppcode_from, $suppcode_to.'%'])
                    ->orderBy('ap.suppcode', 'ASC')
                    ->distinct('ap.suppcode');

        $supp_code = $supp_code->get(['ap.suppcode','su.supplier_name', 'ap.suppgroup']);

        //dd($supp_code);

        $array_report = [];

        foreach ($supp_code as $key => $value){
            $apacthdr = DB::table('finance.apacthdr as ap')
                    ->select('ap.compcode','ap.auditno','ap.trantype','ap.doctype','ap.suppcode','ap.suppgroup','su.Name AS supplier_name', 'ap.actdate','ap.document','ap.cheqno','ap.deptcode','ap.amount','ap.outamount','ap.recstatus','ap.payto','ap.recdate','ap.postdate','ap.postuser','ap.category','ap.remarks','ap.adduser','ap.adddate','ap.upduser','ap.upddate','ap.source','ap.idno','ap.unit','ap.pvno','ap.paymode','ap.bankcode','ap.unallocated')
                    ->join('material.supplier as su', function($join){
                        $join = $join->on('su.SuppCode', '=', 'ap.suppcode');
                        $join = $join->where('su.compcode', '=', session('compcode'));
                    })
                    ->where('ap.compcode',session('compcode'))
                    ->where('ap.unit',session('unit'))
                    ->where('ap.recstatus', '=', "POSTED")
                    ->where('ap.suppcode','=',$value->suppcode)
                    ->whereDate('ap.postdate', '<=', $date_ag)
                    ->orderBy('ap.postdate','ASC')
                    // ->where('ap.outamount','>',0)
                    ->get();

            //dd($apacthdr);

            $value->docno = '';
            $value->outamt = 0;
            
            foreach ($apacthdr as $key => $value){
                $apacthdramt = $value->amount;
                //dd($apacthdramt);
                // if($value->trantype == 'IN' || $value->trantype == 'DN') {
                    $apalloc = DB::table('finance.apalloc as al')
                        ->where('al.compcode','=',session('compcode'))
                        ->where('al.docsource','=',$value->source)
                        ->where('al.doctrantype','=',$value->trantype)
                        ->where('al.docauditno','=',$value->auditno)
                        ->where('al.recstatus','=',"POSTED")
                        ->where('al.suppcode','=',$value->suppcode)
                        ->whereDate('al.allocdate', '<=', $date_ag)
                        ->sum('al.allocamount');

                    //dd($apalloc);
                    //calculate o/s amount hdr - allocamt
                    $outamt = Floatval($apacthdramt) - Floatval($apalloc);
                    // dd($apacthdramt);

                // } else {
                //     $apalloc = DB::table('finance.apalloc as al')
                //         ->where('al.compcode','=',session('compcode'))
                //         ->where('al.docsource','=',$value->source)
                //         ->where('al.doctrantype','=',$value->trantype)
                //         ->where('al.docauditno','=',$value->auditno)
                //         ->where('al.recstatus','=',"POSTED")
                //         ->where('al.suppcode','=',$value->suppcode)
                //         ->whereDate('al.allocdate', '<=', $date_ag)
                //         ->sum('al.allocamount');

                //         //calculate o/s amount hdr - allocamt
                //         $outamt = -(Floatval($apacthdramt) - Floatval($apalloc));
                //         //dd($outamt);
                // }
                
                switch ($value->trantype) {
                    case 'IN': //dr
                        $value->docno = $value->document;
                        $value->outamt = $outamt;
                        array_push($array_report, $value);
                        break;
                    case 'DN': //dr
                        $value->docno = $value->document;
                        $value->outamt = $outamt;
                        array_push($array_report, $value);
                        break;
                    case 'CN': //cr
                        $value->docno = $value->document;
                        $value->outamt = $outamt;
                        array_push($array_report, $value);
                        break;
                    case 'PV': //cr
                        $value->docno = str_pad($value->pvno, 5, "0", STR_PAD_LEFT);
                        $value->outamt = $outamt;
                        array_push($array_report, $value);
                        break;
                    default:
                        // code...
                        break;
                }
     
            }

        }
        // dd($array_report);

        ///calculate ageing days
        // $date_ag = Carbon::parse($request->date_ag)->format('Y-m-d');
        // $postdate = $request->postdate;
        // $datetime1 = new DateTime($date_ag);
        // $datetime2 = new DateTime($postdate);
        // $interval = $datetime1->diff($datetime2);
        // $days = $interval->format('%a');

        // dd($days);
        
        $company = DB::table('sysdb.company')
            ->where('compcode','=',session('compcode'))
            ->first();

        $header = new stdClass();
        $header->printby = session('username');
        $header->date_ag = Carbon::parse($request->date_ag)->format('d-m-Y');
        $header->suppcode_from = $request->suppcode_from;
        $header->suppcode_to = $request->suppcode_to;
        $header->compname = $company->name;

        return view('finance.AP.APAgeingDtl_Report.APAgeingDtl_Report_pdfmake',compact('array_report','header', 'supp_group', 'supp_code', 'apacthdr', 'apalloc', 'outamt'));
        
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
}