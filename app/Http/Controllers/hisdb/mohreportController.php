<?php

namespace App\Http\Controllers\hisdb;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Auth;
use Carbon\Carbon;
use Response;

class mohreportController extends defaultController
{   

    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {  
        return view('hisdb.mohreport.mohreport');
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
        switch ($request->reportno) {
            case 1:
                $filename = 'PS101.xlsx';
                break;
            case 2:
                $filename = 'PS102.xlsx';
                break;
            case 3:
                $filename = 'PS202.xlsx';
                break;
            case 4:
                $filename = 'PS203.xlsx';
                break;
            
            default:
                dd('error');
                break;
        }

        $file_path = public_path()."\\assets\\mohreport\\".$filename;

        return Response::download($file_path,$filename);
    }

}
