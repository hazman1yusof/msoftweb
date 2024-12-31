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
use App\Exports\csv\product_csv;
use App\Exports\csv\ivdspdt_csv;
use App\Exports\csv\dballoc_csv;
use App\Exports\csv\apalloc_csv;
use App\Exports\csv\apactdtl_csv;
use App\Exports\csv\stockloc_csv;
use App\Exports\csv\stockexp_csv;

use App\Jobs\SendEmailPR;
use App\Mail\sendmaildefault;

class ExportController extends defaultController
{   
	public function __construct(){

    }

    public function show(Request $request){   
        return view('setup.export_csv.export_csv');
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
            case 'export_product':
                return $this->export_product($request);
            case 'export_ivdspdt':
                return $this->export_ivdspdt($request);
            case 'export_dballoc':
                return $this->export_dballoc($request);
            case 'export_apalloc':
                return $this->export_apalloc($request);
            case 'export_apactdtl':
                return $this->export_apactdtl($request);
            case 'export_stockloc':
                return $this->export_stockloc($request);    
            case 'export_stockexp':
                return $this->export_stockexp($request);
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

    public function export_product(Request $request){
        return Excel::download(new product_csv($request), 'product_csv.csv');
    }

    public function export_ivdspdt(Request $request){
        return Excel::download(new ivdspdt_csv($request), 'ivdspdt_csv.csv');
    }

    public function export_dballoc(Request $request){
        return Excel::download(new dballoc_csv($request), 'dballoc_csv.csv');
    }

    public function export_apalloc(Request $request){
        return Excel::download(new apalloc_csv($request), 'apalloc_csv.csv');
    }

    public function export_apactdtl(Request $request){
        return Excel::download(new apactdtl_csv($request), 'apactdtl_csv.csv');
    }

    public function export_stockloc(Request $request){
        return Excel::download(new stockloc_csv($request), 'stockloc_csv.csv');
    }

    public function export_stockexp(Request $request){
        return Excel::download(new stockexp_csv($request), 'stockexp_csv.csv');
    }
}