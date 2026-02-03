<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;
use Response;
use Symfony\Component\Process\Process;
// use App\Jobs\ARAgeingDtlProcess;

class StatementController extends defaultController
{
    
    public function __construct()
    {
        // $this->middleware('auth');
    }
    
    public function show(Request $request)
    {
        return view('finance.AR.statement.statement');
    }

    public function table(Request $request){ 
        switch($request->action){
            case 'process_pyserver':
                return $this->process_pyserver($request);
            case 'check_running_process':
                return $this->check_running_process($request);
            default:
                return 'error happen..';
        }
    }
    
    public function form(Request $request)
    {
        switch($request->action){
            default:
                return 'error happen..';
        }
    }

    public function process_pyserver(Request $request){

        $username = session('username');
        $compcode = session('compcode');
        $suppcode_from = $request->supp_from;
        $suppcode_to = $request->supp_to;
        $fromdate = $request->fromdate;
        $todate = $request->todate;

        $firstDay = Carbon::createFromFormat('Y-m', $fromdate)->startOfMonth()->format('Y-m-d');
        $lastDay  = Carbon::createFromFormat('Y-m', $todate)->endOfMonth()->format('Y-m-d');

        $pyserver = \config('get_config.DB_HOST_PYSERVER');

        $job_id = $this->start_job_queue($suppcode_from,$suppcode_to,$firstDay,$lastDay);

        $client = new \GuzzleHttp\Client();

        $url = 'http://localhost:5000/api/periodicStatement?suppcode_from='.$suppcode_from.'&suppcode_to='.$suppcode_to.'&fromdate='.$firstDay.'&todate='.$lastDay.'&username='.$username.'&compcode='.$compcode.'&job_id='.$job_id.'&host='.$pyserver;

        $response = $client->request('GET', $url, [
          'headers' => [
            'accept' => 'application/json',
          ],
        ]);

        $responce = new stdClass();
        $responce->job_id = $job_id;
        return json_encode($responce);
    }

    public function start_job_queue($suppcode_from,$suppcode_to,$fromdate,$todate){
        $idno = DB::table('sysdb.job_queue')
                ->insertGetId([
                    'compcode' => session('compcode'),
                    'page' => 'periodicStatement',
                    'filename' => 'Periodic Statement '.$suppcode_from.'.xlsx',
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'status' => 'PENDING',
                    'remarks' => 'Periodic Statement for Supplier from '.$suppcode_from.' to '.$suppcode_to.' from '.$fromdate.' to '.$todate,
                    'type' => $suppcode_from,
                    'date' => $fromdate,
                    'date_to' => $todate,
                    'debtorcode_from' => $suppcode_from,
                    'debtorcode_to' => $suppcode_to
                ]);

        return $idno;
    }

    public function check_running_process(Request $request){

        $responce = new stdClass();
        $job_id = $request->job_id;

        $last_job = DB::table('sysdb.job_queue')
                        ->where('idno', $job_id)
                        ->where('compcode', session('compcode'))
                        ->where('page', 'periodicStatement')
                        ->orderBy('idno', 'desc');

        if(!$last_job->exists()){
            $responce->jobdone = 'false';
            $responce->status = 'notfound';
            return json_encode($responce);
        }

        $last_job = $last_job->first();
        $responce->status = $last_job->status;
        $responce->datefr = $last_job->adddate;
        $responce->dateto = $last_job->finishdate;
        $responce->type = $last_job->type;

        if($last_job->status != 'DONE'){
            $responce->jobdone = 'false';
        }else{
            $responce->jobdone = 'true';
        }
        return json_encode($responce);
    }

    public function download_excel(Request $request){
        $job_queue = DB::table('sysdb.job_queue')
                        ->where('idno', $request->job_id)
                        ->where('compcode', session('compcode'))
                        ->where('page', 'periodicStatement')
                        ->where('status', 'DONE')
                        ->orderBy('idno', 'desc')
                        ->first();
                        dd($job_queue);

        // return Excel::download(new invoiceListingsExport(
        //     $job_queue->idno,
        //     $job_queue->debtorcode_from,
        //     $job_queue->debtorcode_to,
        //     $job_queue->date,
        //     $job_queue->date_to,
        //     $job_queue->debtortype
        // ), $job_queue->filename);  
    }
}