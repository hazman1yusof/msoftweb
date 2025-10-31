<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use App\Exports\uninvgrn_Export;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Response;
use Symfony\Component\Process\Process;

class UnInvGRNController extends defaultController
{
    public $page = 'uninvgrn';

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

        return view('finance.uninvgrn.uninvgrn',compact('jobdone'));
    }

    public function table(Request $request){ 
        switch($request->action){
            case 'job_queue':
                return $this->job_queue($request);
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
                    // return $this->phpProcess($request);
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

        $url='http://192.168.0.13:8443/msoftweb/public/uninvgrn/table?action=process&dateFrom='.$request->dateFrom.'&dateTo='.$request->dateTo.'&username='.session('username').'&compcode='.session('compcode');

        $response = $client->request('GET', $url, [
          'headers' => [
            'accept' => 'application/json',
          ],
        ]);
    }

    public function process(Request $request){
        $data = [
            'DATA1' => [
                'username' => ($request->username)?$request->username:'-',
                'compcode' => ($request->compcode)?$request->compcode:'9B',
                'dateFrom' => $request->dateFrom,
                'dateTo' => $request->dateTo,
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

        $path = \config('get_config.EXEC_PATH').'\\uninvgrn.ini';
        file_put_contents($path, $iniString);

        if($this->block_if_job_pending()){
            return response()->json([
                'status' => 'Other job still pending'
            ]);
        }else{
            // Path to your Python script
            $scriptPath = \config('get_config.EXEC_PATH').'\\uninvgrn.py'; // double backslashes for Windows paths
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
                        ->where('page', $this->page)
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
        $page = $this->page;

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
                        ->where('idno', $request->idno)
                        ->first();

        return Excel::download(new uninvgrn_Export($job_queue->idno,$job_queue->date,$job_queue->date_to), $job_queue->filename);
    }

    public function phpProcess(Request $request){
        DB::beginTransaction();
        
        try{

            $this->page = 'uninvgrn';
            $this->fromdate = $request->dateFrom;
            $this->todate = $request->dateTo;
            $this->username = ($request->username)?$request->username:'-';
            $this->compcode = ($request->compcode)?$request->compcode:'9B';
            $this->filename = 'uninvgrn_Export.xlsx';

            $idno_job_queue = $this->start_job_queue();

            $do_grn = DB::table('material.delordhd as do')
                            ->select('do.idno','do.compcode','do.recno','do.prdept','do.trantype','do.docno','do.delordno','do.invoiceno','do.suppcode','do.srcdocno','do.po_recno','do.deldept','do.totamount','do.deliverydate','do.trandate','do.trantime','do.recstatus','do.remarks','do.reqdept','do.postdate','sp.name')
                            ->join('material.supplier as sp', function($join){
                                $join = $join->where('sp.compcode', '=', session('compcode'))
                                            ->on('sp.suppcode','do.suppcode');
                            })
                            ->where('do.compcode',session('compcode'))
                            // ->whereDate('do.trandate','>=',$this->fromdate)
                            ->whereDate('do.trandate','<=',$this->todate)
                            ->where('do.trantype','GRN')
                            ->where('do.recstatus','POSTED')
                            ->get();

            foreach ($do_grn as $obj) {

                $grn_amt = $obj->totamount;

                $do_grt = DB::table('material.delordhd')
                            ->where('compcode',session('compcode'))
                            ->where('po_recno',$obj->recno)
                            ->where('trantype','GRT')
                            ->where('recstatus','POSTED');

                if($do_grt->exists()){
                    $grt_amt = $do_grt->first()->totamount;
                }else{
                    $grt_amt = 0;
                }

                $invoice = DB::table('finance.apacthdr as aph')
                            ->select('apd.amount','aph.postdate')
                            ->join('finance.apactdtl as apd', function($join) use ($obj){
                                $join = $join->where('apd.compcode', '=', session('compcode'))
                                            ->where('apd.document',$obj->delordno)
                                            ->on('apd.source','aph.source')
                                            ->on('apd.trantype','aph.trantype')
                                            ->on('apd.auditno','aph.auditno');
                            })
                            ->where('aph.document',$obj->invoiceno)
                            ->where('aph.compcode',session('compcode'))
                            ->where('aph.recstatus','POSTED')
                            ->where('aph.source','AP')
                            ->where('aph.trantype','IN');

                if($invoice->exists()){
                    $invoice_amt = $invoice->first()->amount;
                    $inv_postdate = $invoice->first()->postdate;
                }else{
                    $invoice_amt = 0;
                    $inv_postdate = null;
                }

                $total_bal = $grn_amt - $grt_amt - $invoice_amt;
                if(round($total_bal, 2) != 0.00){
                    $pono = '-';
                    if(!empty($obj->srcdocno)){
                     $pono = $obj->reqdept.'-'.str_pad($obj->srcdocno, 7, '0', STR_PAD_LEFT);
                    }
                    DB::table('finance.uninvgrn')
                            ->insert([
                                'compcode' => $this->compcode,
                                'username' => $this->username,
                                'job_id' => $idno_job_queue,
                                'recno' => $obj->recno,
                                'grnno' => $obj->reqdept.'-'.str_pad($obj->docno, 7, '0', STR_PAD_LEFT),
                                'trandate' => $obj->trandate,
                                'pono' => $pono,
                                'delordno' => $obj->delordno,
                                'deldept' => $obj->deldept,
                                'grn_amt' => $grn_amt,
                                'grt_amt' => $grt_amt,
                                'invoice_amt' => $invoice_amt,
                                'total_bal' => $total_bal,
                                'suppcode' => $obj->suppcode,
                                'suppname' => $obj->name,
                                'invoiceno' => $obj->invoiceno,
                                'inv_postdate' => $inv_postdate,
                            ]);
                }
            }

            $this->stop_job_queue($idno_job_queue);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            
            return response($e, 500);
        }
    }

    public function start_job_queue(){

        $idno_job_queue = DB::table('sysdb.job_queue')
                            ->insertGetId([
                                'compcode' => $this->compcode,
                                'page' => $this->page,
                                'filename' => $this->filename,
                                // 'process' => $this->process,
                                'adduser' => $this->username,
                                'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                                'status' => 'PENDING',
                                // 'remarks' => 'AR Ageing '.$this->type.' as of '.$this->date.', debtortype: '.$this->debtortype.', debtorcode from:"'.$this->debtorcode_from.'" to "'.$this->debtorcode_to.'"',
                                // 'type' => $this->type,
                                'date' => $this->fromdate,
                                'date_to' => $this->todate,
                                // 'debtortype' => $this->debtortype,
                                // 'debtorcode_from' => $this->debtorcode_from,
                                // 'debtorcode_to' => $this->debtorcode_to,
                                // 'groupOne' => $this->groupOne,
                                // 'groupTwo' => $this->groupTwo,
                                // 'groupThree' => $this->groupThree,
                                // 'groupFour' => $this->groupFour,
                                // 'groupFive' => $this->groupFive,
                                // 'groupSix' => $this->groupSix,
                                // 'groupby' => $this->groupby
                            ]);

        return $idno_job_queue;
    }

    public function stop_job_queue($idno_job_queue){
        DB::table('sysdb.job_queue')
                ->where('idno',$idno_job_queue)
                ->update([
                    'finishdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'status' => 'DONE'
                ]);
    }
}