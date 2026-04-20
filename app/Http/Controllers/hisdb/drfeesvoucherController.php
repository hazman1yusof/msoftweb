<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Auth;
use Carbon\Carbon;
use Response;

class drfeesvoucherController extends defaultController
{   

    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {  
        return view('hisdb.drfeesvoucher.drfeesvoucher');
    }

    public function table(Request $request)
    {   
        switch($request->action){
            case 'download_report':
                return $this->download_report($request);
                
            default:
                return 'error happen..';
        }
    }

    public function download_report(Request $request){
        
        $filename = 'drfeesvoucher.xlsx';
        
        $file_path = public_path()."\\assets\\mohreport\\".$filename;

        return Response::download($file_path,$filename);
    }

}
