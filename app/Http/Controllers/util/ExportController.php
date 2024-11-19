<?php

namespace App\Http\Controllers\util;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\csv\dbacthdr_csv;
use App\Exports\csv\billdet_csv;
use App\Exports\csv\billsum_csv;
use App\Exports\csv\ivtxnhd_csv;
use App\Exports\csv\ivtxndt_csv;
use App\Exports\csv\delordhd_csv;
use App\Exports\csv\delorddt_csv;
use App\Exports\csv\apacthdr_csv;

use App\Jobs\SendEmailPR;
use App\Mail\sendmaildefault;

class ExportController extends defaultController
{   
	public function __construct(){

    }
    public function table(Request $request){  
        switch($request->action){
            case 'export_dbacthdr':
                return $this->export_dbacthdr($request);
            case 'export_billdet':
                return $this->export_billdet($request);
            case 'export_billsum':
                return $this->export_billsum($request);
            case 'export_ivtxnhd':
                return $this->export_ivtxnhd($request);
            case 'export_ivtxndt':
                return $this->export_ivtxndt($request);
            case 'export_delordhd':
                return $this->export_delordhd($request);
            case 'export_delorddt':
                return $this->export_delorddt($request);
            case 'export_apacthdr':
                return $this->export_apacthdr($request);
            default:
                return 'error happen..';
        }
    }

    public function export_dbacthdr(Request $request){
        return Excel::download(new dbacthdr_csv($request), 'dbacthdr_csv.csv');
    }

    public function export_billdet(Request $request){
        return Excel::download(new billdet_csv($request), 'billdet_csv.csv');
    }

    public function export_billsum(Request $request){
        return Excel::download(new billsum_csv($request), 'billsum_csv.csv');
    }

    public function export_ivtxnhd(Request $request){
        return Excel::download(new ivtxnhd_csv($request), 'ivtxnhd_csv.csv');
    }

    public function export_ivtxndt(Request $request){
        return Excel::download(new ivtxndt_csv($request), 'ivtxndt_csv.csv');
    }

    public function export_delordhd(Request $request){
        return Excel::download(new delordhd_csv($request), 'delordhd_csv.csv');
    }

    public function export_delorddt(Request $request){
        return Excel::download(new delorddt_csv($request), 'delorddt_csv.csv');
    }

    public function export_apacthdr(Request $request){
        return Excel::download(new apacthdr_csv($request), 'apacthdr_csv.csv');
    }
}