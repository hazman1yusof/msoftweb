<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use DB;
use DateTime;
use Carbon\Carbon;
use App\Exports\SalesItemExport;
use Maatwebsite\Excel\Facades\Excel;

class SalesItem_ReportController extends defaultController
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
        return view('finance.SalesItem_Report.SalesItem_Report',[
                'company_name' => $comp->name
        ]);
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
            case 'depreciation':
                return $this->depreciation($request);
            default:
                return 'error happen..';
        }
    }
    
    public function showExcel(Request $request){
        return Excel::download(new SalesItemExport($request->datefr,$request->dateto), 'SalesItemExport.xlsx');
    }
    
    public function showpdf(Request $request){
        
        $datefr = Carbon::parse($request->datefr)->format('Y-m-d');
        $dateto = Carbon::parse($request->dateto)->format('Y-m-d');
        
        $dbacthdr = DB::table('debtor.dbacthdr as d')
                    ->select('d.debtorcode', 'dm.name AS dm_desc', 'd.invno','b.idno', 'b.compcode', 'b.trxdate', 'b.chgcode', 'b.quantity', 'b.amount', 'b.invno', 'b.taxamount', 'c.description AS cm_desc', 'd.trantype','d.source','d.debtorcode AS debtorcode')
                    ->leftJoin('debtor.debtormast as dm', function($join) use ($request){
                        $join = $join->on('dm.debtorcode', '=', 'd.debtorcode')
                                    ->where('dm.compcode', '=', session('compcode'));
                    })
                    ->join('hisdb.billdet as b', function($join) use ($request){
                        $join = $join->on('b.invno', '=', 'd.invno')
                                    ->where('b.compcode', '=', session('compcode'));
                    })
                    ->leftJoin('hisdb.chgmast as c', function($join) use ($request){
                        $join = $join->on('c.chgcode', '=', 'b.chgcode')
                                    ->where('c.compcode', '=', session('compcode'));
                    })
                    ->where('d.compcode','=',session('compcode'))
                    ->where('d.source', '=', 'PB')
                    ->where('d.trantype', '=', 'IN')
                    ->where('d.recstatus', '=', 'POSTED')
                    ->where('d.amount','!=','0')
                    ->orderBy('d.debtorcode','DESC')
                    ->orderBy('d.invno','DESC')
                    ->whereBetween('b.trxdate', [$datefr, $dateto])
                    ->get();

        $invno_array = [];
        foreach ($dbacthdr as $obj) {
            if(!in_array($obj->invno, $invno_array)){
                array_push($invno_array, $obj->invno);
            }
        }
        
       //$totalAmount = $dbacthdr->sum('amount');
        
        // $totamount_expld = explode(".", (float)$totalAmount);
        
        // $totamt_eng_rm = $this->convertNumberToWordENG($totamount_expld[0])."";
        // $totamt_eng = $totamt_eng_rm." ONLY";
        
        // if(count($totamount_expld) > 1){
        //     $totamt_eng_sen = $this->convertNumberToWordENG($totamount_expld[1])." CENT";
        //     $totamt_eng = $totamt_eng_rm.$totamt_eng_sen." ONLY";
        // }
        
        return view('finance.SalesItem_Report.SalesItem_Report_pdfmake',compact('dbacthdr','invno_array'));
        
    }

}