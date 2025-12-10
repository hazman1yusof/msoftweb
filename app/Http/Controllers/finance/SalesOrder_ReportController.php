<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use DB;
use Carbon\Carbon;
use App\Exports\SalesOrderExport;
use Maatwebsite\Excel\Facades\Excel;

class SalesOrder_ReportController extends defaultController
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

        if($request->type == 'invoice'){
            $default_customer = 'UKMSC';
            return view('finance.SalesOrder_Report.SalesOrder_Report_invoice',compact('default_customer'));
        }

        $comp = DB::table('sysdb.company')->where('compcode','=',session('compcode'))->first();
        return view('finance.SalesOrder_Report.SalesOrder_Report',[
                'company_name' => $comp->name
        ]);
    }

    public function table(Request $request)
    {   
        switch($request->action){
            case 'sale_invoices_pdf':
                return $this->sale_invoices_pdf($request);
            default:
                return 'error happen..';
        }
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
        return Excel::download(new SalesOrderExport($request->datefr,$request->dateto,$request->deptcode,$request->scope), 'SalesOrderReport.xlsx');
    }

    public function showpdf(Request $request){
        $datefr = Carbon::parse($request->datefr)->format('Y-m-d H:i:s');
        $dateto = Carbon::parse($request->dateto)->format('Y-m-d H:i:s');
        $deptcode = $request->deptcode;
        
        $dbacthdr = DB::table('debtor.dbacthdr as dh', 'debtor.debtormast as dm')
                    ->select('dh.invno', 'dh.posteddate', 'dh.deptcode', 'dh.amount', 'dm.debtorcode as dm_debtorcode', 'dm.name as debtorname')
                    ->leftJoin('debtor.debtormast as dm', function($join){
                        $join = $join->on('dm.debtorcode', '=', 'dh.debtorcode')
                                    ->where('dm.compcode', '=', session('compcode'));
                    })
                    ->where('dh.compcode','=',session('compcode'))
                    ->where('dh.source','=','PB')
                    ->where('dh.recstatus','=', 'POSTED')
                    ->where('dh.trantype', '=', 'IN');
                    if(!empty($deptcode)){
                        $dbacthdr = $dbacthdr
                                    ->where('dh.deptcode', '=', $deptcode);
                    }
                    $dbacthdr = $dbacthdr->whereBetween('dh.posteddate', [$datefr, $dateto])
                    ->get();
        
        $totalAmount = $dbacthdr->sum('amount');

        $title = "SALES";
        
        $company = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();
        
        return view('finance.SalesOrder_Report.SalesOrder_Report_pdfmake',compact('dbacthdr','totalAmount', 'company', 'title'));
    }

    public function sale_invoices_pdf(Request $request){
        $datefr = Carbon::parse($request->datefr)->format('Y-m-d');
        $dateto = Carbon::parse($request->dateto)->format('Y-m-d');
        $deptcode = $request->deptcode;
        $debtorcode = $request->debtorcode;

        $dbacthdr_all = DB::table('debtor.dbacthdr as h')
            ->select('h.source','h.trantype','h.epistype','h.compcode', 'h.idno', 'h.auditno', 'h.lineno_', 'h.amount as hdr_amount', 'h.outamount as hdr_outamount', 'h.recstatus', 'h.debtortype', 'h.debtorcode', 'h.mrn', 'h.invno', 'h.ponum', 'h.podate', 'h.deptcode', 'h.entrydate','h.hdrtype','h.LHDNStatus',
            'm.debtorcode as debt_debtcode', 'm.name as debt_name', 'm.address1 as cust_address1', 'm.address2 as cust_address2', 'm.address3 as cust_address3', 'm.address4 as cust_address4', 'm.creditterm as crterm','m.billtype as billtype','dt.debtortycode as dt_debtortycode', 'dt.description as dt_description','bt.description as bt_desc','pm.Name as pm_name','pm.address1 as pm_address1','pm.address2 as pm_address2','pm.address3 as pm_address3','pm.postcode as pm_postcode','h.doctorcode','dc.doctorname','h.remark','m.debtortype as m_debtortype',
            'b.compcode', 'b.idno', 'b.mrn', 'b.billno', 'b.lineno_', 'b.chgclass', 'b.chggroup', 'b.description', 'b.uom', 'b.quantity', 'b.amount', 'b.outamt', 'b.taxamt', 'b.unitprice', 'b.taxcode', 'b.discamt','b.uom as uom_desc', 'ms.description as chgmast_desc')
            ->leftJoin('debtor.debtormast as m', function($join) use ($request){
                $join = $join->on("m.debtorcode", '=', 'h.debtorcode');    
                $join = $join->where("m.compcode", '=', session('compcode'));
            })
            ->leftJoin('debtor.debtortype as dt', function($join) use ($request){
                $join = $join->on("dt.debtortycode", '=', 'm.debtortype');    
                $join = $join->where("dt.compcode", '=', session('compcode'));
            })
            ->leftJoin('hisdb.billtymst as bt', function($join) use ($request){
                $join = $join->on("bt.billtype", '=', 'h.hdrtype');    
                $join = $join->where("bt.compcode", '=', session('compcode'));
            })
            ->leftJoin('hisdb.pat_mast as pm', function($join) use ($request){
                $join = $join->on("pm.newmrn", '=', 'h.mrn');    
                $join = $join->where("pm.compcode", '=', session('compcode'));
            })
            ->leftJoin('hisdb.doctor as dc', function($join) use ($request){
                $join = $join->on("dc.doctorcode", '=', 'h.doctorcode');    
                $join = $join->where("dc.compcode", '=', session('compcode'));
            })
            ->join('debtor.billsum AS b', function($join) use ($request){
                $join = $join->on("b.source", '=', 'h.source');    
                $join = $join->on("b.trantype", '=', 'h.trantype');    
                $join = $join->on("b.billno", '=', 'h.auditno');    
                $join = $join->where("b.compcode", '=', session('compcode'));
            })
            ->leftJoin('hisdb.chgmast as ms', function($join) use ($request){
                $join = $join->on('b.chggroup', '=', 'ms.chgcode');
                $join = $join->on('b.uom', '=', 'ms.uom');
                $join = $join->where('ms.compcode', '=', session('compcode'));
                // $join = $join->where('m.unit', '=', session('unit'));
            })
            ->whereDate('h.posteddate','>=',$datefr)
            ->whereDate('h.posteddate','<=',$dateto)
            ->where('h.deptcode',$deptcode)
            ->where('h.debtorcode',$debtorcode)
            ->whereNotNull('h.invno')
            ->where('h.recstatus','POSTED')
            ->orderBy('h.posteddate', 'ASC')
            // dd($this->getQueries($dbacthdr));

            ->get();

        $invno_arr = $dbacthdr_all->unique('invno');

        foreach ($invno_arr as $obj) {
            $totamount_expld = explode(".", (float)$obj->hdr_amount);

            $totamt_bm_rm = $this->convertNumberToWordBM($totamount_expld[0])." RINGGIT ";
            $totamt_bm = $totamt_bm_rm." SAHAJA";

            if(count($totamount_expld) > 1){
                $totamt_bm_sen = $this->convertNumberToWordBM($totamount_expld[1])." SEN";
                $totamt_bm = $totamt_bm_rm.$totamt_bm_sen." SAHAJA";
            }
            $obj->totamt_bm = $totamt_bm;
        }

        $company = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();
        
        // $pdf = PDF::loadView('finance.SalesOrder.SalesOrder_pdf',compact('dbacthdr','billsum','totamt_bm','company', 'title'));
    
        // return $pdf->stream();
        
        return view('finance.SalesOrder.SalesOrder_invoices_pdfmake',compact('invno_arr','dbacthdr_all','company'));
    }
}