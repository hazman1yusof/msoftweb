<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use DB;
use DateTime;
use stdClass;
use Carbon\Carbon;
use App\Exports\SalesItemExport;
use App\Exports\SalesCatExport;
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
        if($request->scope=='CAT'){
            return Excel::download(new SalesCatExport($request->datefr,$request->dateto,$request->deptcode), 'SalesByCategoryExport.xlsx');
        }else{
            return Excel::download(new SalesItemExport($request->datefr,$request->dateto,$request->deptcode), 'SalesItemExport.xlsx');
        }
    }
    
    public function showpdf(Request $request){
        
        $datefr = Carbon::parse($request->datefr)->format('Y-m-d');
        $dateto = Carbon::parse($request->dateto)->format('Y-m-d');
        $deptcode = $request->deptcode;
        
        $dbacthdr = DB::table('debtor.dbacthdr as d')
                    ->select('d.debtorcode', 'dm.name AS dm_desc', 'd.invno','b.idno', 'b.compcode', 'b.trxdate', 'b.chgcode', 'b.quantity', 'b.amount', 'b.invno', 'b.taxamount', 'c.description AS cm_desc', 'd.trantype','d.source','d.debtorcode AS debtorcode','p.avgcost as costprice')
                    ->leftJoin('debtor.debtormast as dm', function($join) use ($request){
                        $join = $join->on('dm.debtorcode', '=', 'd.debtorcode')
                                    ->where('dm.compcode', '=', session('compcode'));
                    })
                    ->join('hisdb.billdet as b', function($join) use ($request){
                        $join = $join->on('b.invno', '=', 'd.invno')
                                    ->where('b.compcode', '=', session('compcode'));
                    })
                    ->leftJoin('material.product as p', function($join){
                        $join = $join->on('p.itemcode', '=', 'b.chgcode')
                                    ->where('p.compcode', '=', session('compcode'));
                    })
                    ->leftJoin('hisdb.chgmast as c', function($join) use ($request){
                        $join = $join->on('c.chgcode', '=', 'b.chgcode')
                                    ->on('c.uom', '=', 'b.uom')
                                    ->where('c.unit', '=', session('unit'))
                                    ->where('c.compcode', '=', session('compcode'));
                    })
                    ->where('d.compcode','=',session('compcode'))
                    ->where('d.source', '=', 'PB')
                    ->where('d.trantype', '=', 'IN')
                    ->where('d.recstatus', '=', 'POSTED');
                    if(!empty($deptcode)){
                        $dbacthdr = $dbacthdr
                                    ->where('d.deptcode', '=', $deptcode);
                    }
                    $dbacthdr = $dbacthdr->where('d.amount','!=','0')
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

        foreach ($dbacthdr as $obj) {
            if($obj->costprice == ''){
                $obj->costprice = 0.00;
            }

            $obj->costprice = $obj->costprice * $obj->quantity;
            // $chgprice_obj = DB::table('hisdb.chgprice as cp')
            //     ->where('cp.compcode', '=', session('compcode'))
            //     ->where('cp.chgcode', '=', $obj->chgcode)
            //     // ->where('cp.uom', '=', $value->uom)
            //     ->whereDate('cp.effdate', '<=', Carbon::now("Asia/Kuala_Lumpur"))
            //     ->orderBy('cp.effdate','desc');

            // if($chgprice_obj->exists()){
            //     $chgprice_obj = $chgprice_obj->first();
            //     $obj->costprice = $chgprice_obj->costprice * $obj->quantity;
            // }else{
            //     $obj->costprice = 0.00;
            // }
        }

        // dd($dbacthdr);
       
        $company = DB::table('sysdb.company')
            ->where('compcode','=',session('compcode'))
            ->first();

        $header = new stdClass();
        $header->printby = session('username');
        $header->datefrom = Carbon::parse($request->datefr)->format('d-m-Y');
        $header->dateto = Carbon::parse($request->dateto)->format('d-m-Y');
        $header->compname = $company->name;

        return view('finance.SalesItem_Report.SalesItem_Report_pdfmake',compact('dbacthdr','invno_array','header'));
        
    }

}