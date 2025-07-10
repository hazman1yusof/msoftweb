<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use App\Exports\fareportExport;
use Maatwebsite\Excel\Facades\Excel;

class assetreportController extends defaultController
{   
    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        return view('finance.FA.assetreport.assetreport');
    }

    public function form(Request $request){   
    }

    public function table(Request $request)
    {  
        switch($request->action){
            case 'fareport':
                return $this->fareport($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function fareport(Request $request){
        return Excel::download(new fareportExport($request->datefrom,$request->catfr,$request->catto), 'fareportExport.xlsx');
    }
}
