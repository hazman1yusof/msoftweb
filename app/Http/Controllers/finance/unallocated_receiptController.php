<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use App\Exports\Unallocated_receiptExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Response;

class unallocated_receiptController extends defaultController
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
        return view('finance.AR.unallocated_receipt.unallocated_receipt');
    }

    public function table(Request $request){ 
        switch($request->action){
            case 'job_queue':
                return $this->job_queue($request);
            case 'download':
                return $this->download($request);
            default:
                return 'error happen..';
        }
    }
    
    public function form(Request $request)
    {
        switch($request->action){
            // case 'add':
            //     return $this->defaultAdd($request);
            // case 'edit':
            //     return $this->defaultEdit($request);
            // case 'del':
            //     return $this->defaultDel($request);
            case 'showExcel':
                return $this->showExcel($request);
            default:
                return 'error happen..';
        }
    }

    public function job_queue(Request $request){
        $responce = new stdClass();

        if($request->ttype == 'RC'){
            $page = 'unallocated_receipt';
        }else{
            $page = 'unallocated_deposit';
        }

        $table_ = DB::table('sysdb.job_queue')
                        ->where('compcode', session('compcode'))
                        ->where('page', $page)
                        ->orderBy('idno','desc');

        $count = $table_->count();
        $table = $table_
                    ->offset($request->start)
                    ->limit($request->length)->get();

        foreach ($table as $key => $value) {
            $value->download = " ";
        }

        $responce->data = $table;
        $responce->recordsTotal = $count;
        $responce->recordsFiltered = $count;
        return json_encode($responce);
    }

    public function download(Request $request){
        $job_queue = DB::table('sysdb.job_queue')
                        ->where('idno', $request->idno)
                        ->first();

        $attachment_path = \config('get_config.ATTACHMENT_PATH');

        $file = $attachment_path."\\uploads\\".$job_queue->process;
        // dump($file);
        return Response::download($file,$job_queue->filename);
    }
    
    public function showExcel(Request $request){

        $filename = 'Unallocated_receipt '.Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d g:i A').'.xlsx';

        if($request->ttype == 'RC'){
            $page = 'unallocated_receipt';
        }else{
            $page = 'unallocated_deposit';
        }

        $bytes = random_bytes(20);
        $process = bin2hex($bytes).'.xlsx';

        (new Unallocated_receiptExport($process,$page,$filename,$request->date,$request->unit))->store($process, \config('get_config.ATTACHMENT_UPLOAD'));

    }
}