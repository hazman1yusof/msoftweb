<?php

namespace App\Http\Controllers\finance;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Auth;
use Carbon\Carbon;
use PDF;
use App\Exports\DebtorMasterExport;
use Maatwebsite\Excel\Facades\Excel;

class DebtorMasterController extends defaultController
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function show(Request $request)
    {
        return view('finance.AR.debtorMaster.debtorMaster');
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
    
    public function showExcel(Request $request){
        return Excel::download(new DebtorMasterExport($request->compcode), 'DebtorMaster.xlsx');
    }
    
    public function showpdf(Request $request){
        
        $compcode = $request->compcode;
        
        $debtormast = DB::table('debtor.debtormast')
                    ->where('compcode', '=', session('compcode'))
                    ->orderBy('idno', 'ASC')
                    ->get();
        
        $company = DB::table('sysdb.company')
                    ->where('compcode', '=', session('compcode'))
                    ->first();
        
        $header = new stdClass();
        $header->printby = session('username');
        $header->compname = $company->name;
        
        return view('finance.AR.debtorMaster.debtorMaster_pdfmake', compact('debtormast', 'header'));
        
    }
    
}
