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

class UnInvGRNController extends defaultController
{
    public var $page = 'uninvgrn';

    public function __construct()
    {
        // $this->middleware('auth');
    }
    
    public function show(Request $request){

        $last_job = DB::table('sysdb.job_queue')
                        ->where('compcode', session('compcode'))
                        ->where('page', $this->page)
                        ->orderBy('idno', 'desc');

        if(!$last_job->exists()){
            $jobdone = 'true';
        }else{
            $last_job = $last_job->first();
            if($last_job->status != 'DONE'){
                $jobdone = 'false';
            }else{
                $jobdone = 'true';
            }
        }

        return view('finance.AR.uninvgrn.uninvgrn',compact('jobdone'));
    }

    public function table(Request $request){ 
        switch($request->action){
            case 'download':
                return $this->download($request);
            case 'check_running_process':
                return $this->check_running_process($request);
            case 'process':
                return $this->process($request);
            case 'phpProcess':
                return $this->phpProcess($request);
            default:
                return 'error happen..';
        }
    }
    
    public function form(Request $request){
        switch($request->action){
            case 'processLink':
                $PYTHON_PATH = \config('get_config.PYTHON_PATH');
                if($PYTHON_PATH != null){ // pastikan msserver sahaja xde python_path
                    return $this->process($request);
                }else{
                    return $this->processLink($request);
                }
            default:
                return 'error happen..';
        }
    }

    public function processLink(Request $request){
        $client = new \GuzzleHttp\Client();

        $url='http://192.168.0.13:8443/msoftweb/public/uninvgrn/table?action=process&fromdate='.$request->fromdate.'&todate='.$request->todate.'&username='.session('username').'&compcode='.session('compcode');

        $response = $client->request('GET', $url, [
          'headers' => [
            'accept' => 'application/json',
          ],
        ]);
    }

    public function check_running_process(Request $request){

        $responce = new stdClass();

        $last_job = DB::table('sysdb.job_queue')
                        ->where('compcode', session('compcode'))
                        ->where('page', $this->page)
                        ->orderBy('idno', 'desc');

        if(!$last_job->exists()){
            $responce->jobdone = 'true';
            return json_encode($responce);
        }

        $last_job = $last_job->first();
        $responce->status = $last_job->status;
        $responce->datefr = $last_job->adddate;
        $responce->dateto = $last_job->finishdate;

        if($last_job->status != 'DONE'){
            $responce->jobdone = 'false';
        }else{
            $responce->jobdone = 'true';
        }
        return json_encode($responce);
    }

    public function job_queue(Request $request){
        $responce = new stdClass();
        $page = 'uninvgrn';

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
                        ->where('compcode', session('compcode'))
                        ->where('page', 'uninvgrn')
                        ->where('status', 'DONE')
                        ->orderBy('idno', 'desc')
                        ->first();

        return Excel::download(new uninvgrn_Export_2($job_queue->idno,$job_queue->type,$job_queue->date,$job_queue->date_to), 'uninvgrn_Export.xlsx');
    }

    public function phpProcess(Request $request){
        DB::table()
    }
}