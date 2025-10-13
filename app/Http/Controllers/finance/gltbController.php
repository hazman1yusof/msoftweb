<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use Response;
use App\Jobs\gltb_jobs;
use Symfony\Component\Process\Process;

class  gltbController extends defaultController
{   

    public function __construct(){
        // $this->middleware('auth');
    }

    public function show(Request $request){   

        $process_ = 'true';
        $last_job = DB::table('sysdb.job_queue')
                        ->where('compcode', session('compcode'))
                        ->where('page', 'gltb')
                        ->orderBy('idno', 'desc');

        if(!$last_job->exists()){
            $process_ = 'false';
        }else{
            $last_job = $last_job->first();

            if($last_job->status != 'DONE'){
                $process_ = 'true';
            }else{
                $process_ = 'false';
            }
        }

        return view('other.gltb.gltb',compact('process_'));
    }

    public function table(Request $request){ 
        switch($request->action){
            case 'check_gltb_process':
                return $this->check_gltb_process($request);
            case 'process':
                return $this->process($request);
                // return $this->gltb_run_cr($request);
            default:
                return 'error happen..';
        }
    }

    public function form(Request $request){   
        switch($request->action){
            case 'processLink':

                $month = $request->month;
                $year = $request->year;

                $date = Carbon::createFromDate($year, $month, 1);
                $limit = Carbon::createFromDate('2025', 4, 1);

                if ($date->lte($limit)) {
                    return 'month equal or last than april 2025';
                }else{
                    $PYTHON_PATH = \config('get_config.PYTHON_PATH');
                    if($PYTHON_PATH != null){
                        return $this->process($request);;
                    }else{
                        return $this->processLink($request);
                    }
                }
            default:
                return 'error happen..';
        }
    }

    public function processLink(Request $request){
        $client = new \GuzzleHttp\Client();

        $url='http://192.168.0.13:8443/msoftweb/public/gltb/table?action=process&month='.$request->month.'&year='.$request->year.'&username='.session('username').'&compcode='.session('compcode');

        $response = $client->request('GET', $url, [
          'headers' => [
            'accept' => 'application/json',
          ],
        ]);
    }

    public function check_gltb_process(Request $request){

        $responce = new stdClass();

        $last_job = DB::table('sysdb.job_queue')
                        ->where('compcode', session('compcode'))
                        ->where('page', 'gltb')
                        ->orderBy('idno', 'desc');

        if(!$last_job->exists()){
            $responce->jobdone = 'true';
            return json_encode($responce);
        }

        $last_job = $last_job->first();
        $responce->status = $last_job->status;
        $responce->datefr = $last_job->adddate;
        $responce->dateto = $last_job->finishdate;
        $responce->period = '-';
        $responce->year = '-';

        if(!empty($last_job->date)){
            list($year, $month, $day) = explode("-", $last_job->date);

            $responce->period = $month;
            $responce->year = $year;
        }

        if($last_job->status != 'DONE'){
            $responce->jobdone = 'false';
        }else{
            $responce->jobdone = 'true';
        }
        return json_encode($responce);
    }

    public function process(Request $request){
        $data = [
            'DATA1' => [
                'username' => ($request->username)?$request->username:'-',
                'compcode' => ($request->compcode)?$request->compcode:'9B',
                'period' => $request->month,
                'year' => $request->year
            ]
        ];

        $iniString = '';
        foreach ($data as $section => $settings) {
            $iniString .= "[$section]\n";
            foreach ($settings as $key => $value) {
                $iniString .= "$key=$value\n";
            }
            $iniString .= "\n";
        }

        $path = \config('get_config.EXEC_PATH').'\\gltb.ini';
        file_put_contents($path, $iniString);

        if($this->block_if_job_pending()){
            return response()->json([
                'status' => 'Other job still pending'
            ]);
        }else{
            // Path to your Python script
            $scriptPath = \config('get_config.EXEC_PATH').'\\gltb.py'; // double backslashes for Windows paths
            $pythonPath = \config('get_config.PYTHON_PATH');

            // Create a process (use 'python' on Windows)
            $process = new Process([$pythonPath, $scriptPath]);

            // Don’t wait for it
            $process->setTimeout(null);

            // Force detached mode on Windows
            $process->setOptions(['create_new_console' => true]);

            $process->start();

            return response()->json([
                'status' => 'Python script started in background (Windows)'
            ]);
        }
    }

    public function block_if_job_pending(){
        $last_job = DB::table('sysdb.job_queue')
                        ->where('compcode', session('compcode'))
                        ->where('page', 'gltb')
                        ->orderBy('idno', 'desc');

        if(!$last_job->exists()){
            return false;
        }

        $last_job = $last_job->first();
        if($last_job->status != 'DONE'){
            return true;
        }

        return false;
    }
}