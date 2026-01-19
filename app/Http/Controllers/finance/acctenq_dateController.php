<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use DB;
use stdClass;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\acctenq_dateExport;
use App\Exports\acctenq_dateExport_2;
use Symfony\Component\Process\Process;

class acctenq_dateController extends defaultController
{   

    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function show(Request $request)
    {   

        $last_job = DB::table('sysdb.job_queue')
                        ->where('compcode', session('compcode'))
                        ->where('page', 'acctenq_date')
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

        return view('finance.GL.acctenq_date.acctenq_date',compact('jobdone'));
    }

    public function table(Request $request){
        switch($request->action){
            case 'getdata';
                return $this->getdata($request);
            case 'openprint';
                return $this->openprint($request);
            case 'get_auditno_forsrc';
                return $this->get_auditno_forsrc($request);
            case 'download':
                return $this->download($request);
            case 'print':
                return $this->print($request);
            case 'process':
                return $this->process($request);
            case 'check_running_process':
                return $this->check_running_process($request);
        }
    }

    public function form(Request $request){   
        switch($request->action){
            case 'processLink':
                return $this->process_pyserver($request);
                // $PYTHON_PATH = \config('get_config.PYTHON_PATH');
                // if($PYTHON_PATH != null){ // pastikan msserver sahaja xde python_path
                //     return $this->process($request);
                // }else{
                //     return $this->processLink($request);
                // }
            default:
                return 'error happen..';
        }
    }

    public function processLink(Request $request){
        $client = new \GuzzleHttp\Client();
        $PYTHON_SERVER = \config('get_config.PYTHON_SERVER');

        $url = $PYTHON_SERVER.'/msoftweb/public/acctenq_date/table?action=process&glaccount='.$request->glaccount.'&fromdate='.$request->fromdate.'&todate='.$request->todate.'&username='.session('username').'&compcode='.session('compcode');

        $response = $client->request('GET', $url, [
          'headers' => [
            'accept' => 'application/json',
          ],
        ]);
    }

    public function check_running_process(Request $request){

        $responce = new stdClass();
        $job_id = $request->job_id;

        $last_job = DB::table('sysdb.job_queue')
                        ->where('idno', $job_id)
                        ->where('compcode', session('compcode'))
                        ->where('page', 'acctenq_date')
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

    public function process_pyserver(Request $request){

        $username = ($request->username)?$request->username:'-';
        $compcode = ($request->compcode)?$request->compcode:'9B';
        $glaccount = $request->glaccount;
        $fromdate = $request->fromdate;
        $todate = $request->todate;
        $pyserver = '192.168.0.11';

        $job_id = $this->start_job_queue($glaccount,$fromdate,$todate);


        $client = new \GuzzleHttp\Client();

        $url = 'http://localhost:5000/api/acctenqdate?glaccount='.$request->glaccount.'&fromdate='.$request->fromdate.'&todate='.$request->todate.'&username='.session('username').'&compcode='.session('compcode').'&job_id='.$job_id.'&host='.$pyserver;

        $response = $client->request('GET', $url, [
          'headers' => [
            'accept' => 'application/json',
          ],
        ]);

        $responce = new stdClass();
        $responce->job_id = $job_id;
        return json_encode($responce);
    }

    public function process(Request $request){
        $data = [
            'DATA1' => [
                'username' => ($request->username)?$request->username:'-',
                'compcode' => ($request->compcode)?$request->compcode:'9B',
                'glaccount' => $request->glaccount,
                'fromdate' => $request->fromdate,
                'todate' => $request->todate,
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

        $path = \config('get_config.EXEC_PATH').'\\acctenq_date.ini';
        file_put_contents($path, $iniString);

        if($this->block_if_job_pending()){
            return response()->json([
                'status' => 'Other job still pending'
            ]);
        }else{
            // Path to your Python script
            $scriptPath = \config('get_config.EXEC_PATH').'\\acctenq_date.py'; // double backslashes for Windows paths
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
                        ->where('page', 'acctenq_date')
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

    public function getdata(Request $request){

        $responce = new stdClass();
        if(empty($request->glaccount)){
            $responce->data = [];
            return json_encode($responce);
        }

        if(!empty($request->order[0])){
            $sortid = intval($request->order[0]['column']);
            $sortdata = 'gl.'.$request->columns[$sortid]['data'];

            if($sortdata == 'gl.cramount' || $sortdata == 'gl.dramount'){
                $sortdata = 'gl.amount';
            }elseif ($sortdata == 'gl.open') {
                $sortdata = 'gl.postdate';
            }

            $sortdir = $request->order[0]['dir'];
        }else{
            $sortdata = 'gl.postdate';
            $sortdir = 'asc';
        }

        $table_ = DB::table('finance.gltran as gl')
                        ->select('gl.id','gl.source','gl.trantype','gl.auditno','gl.postdate','gl.description','gl.reference','gl.cracc','gl.dracc','gl.amount','glcr.description as acctname_cr','gldr.description as acctname_dr')
                        ->where(function($table_) use ($request){
                            $table_->orwhere('gl.dracc','=', $request->glaccount);
                            $table_->orwhere('gl.cracc','=', $request->glaccount);
                        })
                        ->leftJoin('finance.glmasref as glcr', function($join) use ($request){
                            $join = $join->on('glcr.glaccno', '=', 'gl.cracc')
                                            ->where('glcr.compcode','=',session('compcode'));
                        })
                        ->leftJoin('finance.glmasref as gldr', function($join) use ($request){
                            $join = $join->on('gldr.glaccno', '=', 'gl.dracc')
                                            ->where('gldr.compcode','=',session('compcode'));
                        })
                        ->where('gl.amount','!=','0')
                        ->where('gl.compcode', session('compcode'))
                        ->where('gl.postdate', '>=', $request->fromdate)
                        ->where('gl.postdate', '<=', $request->todate)
                        ->orderBy($sortdata, $sortdir);

        $count = $table_->count();
        $table = $table_
                    ->offset($request->start)
                    ->limit($request->length)->get();

        $same_acc = [];
        foreach ($table as $key => $value) {
            $value->open = "<i class='fa fa-folder-open-o' </i>";
            $value->print = "<i class='fa fa-print' </i>";
            if($value->dracc == $request->glaccount){
                $value->acccode = $value->cracc;
                $value->cramount = 0;
                $value->dramount = $value->amount;
                $value->acctname = $value->acctname_cr;
            }else{
                $value->acccode = $value->dracc;
                $value->cramount = $value->amount;
                $value->dramount = 0;
                $value->acctname = $value->acctname_dr;
            }

            switch ($value->source) {
                case 'OE':
                    $data = $this->oe_data($value);
                    break;
                case 'PB':
                    $data = $this->pb_data($value);
                    break;
                case 'AP':
                    $data = $this->ap_data($value);
                    break;
                case 'CM':
                    $data = $this->cm_data($value);
                    break;
                default:
                    $data = $this->oth_data($value);
                    break;
            }

            if(!empty($data)){
                $value->desc_ = $data->desc;
            //     $value->reference = $data->refe;
            }else{
                $value->desc_ = ' ';
            }

            if($value->dracc == $value->cracc){
                array_push($same_acc, clone $value);
            }
        }

        foreach ($same_acc as $obj) {
            $obj->cramount = $obj->amount;
            $obj->dramount = 0;
            $table = $table->merge([$obj]);
        }

        $responce->data = $table;
        $responce->recordsTotal = $count;
        $responce->recordsFiltered = $count;
        return json_encode($responce);
    }

    public function get_auditno_forsrc(Request $request){

        if($request->source == 'PB' && $request->trantype == 'IN'){
            $dbacthdr = DB::table('debtor.dbacthdr')
                            ->where('compcode',session('compcode'))
                            ->where('source',$request->source)
                            ->where('trantype',$request->trantype)
                            ->where('invno',$request->auditno)
                            ->first();

            $responce = new stdClass();
            $responce->dbacthdr = $dbacthdr;

            return json_encode($responce);
        }
    }

    public function openprint(Request $request){
        $gltran = DB::table('finance.gltran')
                    ->where('compcode',session('compcode'))
                    ->where('id',$request->id)
                    ->first();


        switch ($gltran->source) {
            case 'OE':
                $url = $this->oe($gltran);
                break;
            case 'PB':
                $url = $this->pb($gltran);
                break;
            case 'AP':
                $url = $this->ap($gltran);
                break;
            case 'CM':
                $url = $this->cm($gltran);
                break;
            
            default:
                $url = $this->oth($gltran);
                break;
        }

        $responce = new stdClass();
        $responce->url = $url;

        return json_encode($responce);
    }

    public function oe($gltran){
        $billsum = DB::table('debtor.billsum')
                        ->where('compcode',session('compcode'))
                        ->where('auditno',$gltran->auditno)
                        ->first();

        $dbacthdr = DB::table('debtor.dbacthdr')
                        ->where('compcode',session('compcode'))
                        ->where('source','=',$billsum->source)
                        ->where('trantype','=',$billsum->trantype)
                        ->where('auditno','=',$billsum->billno)
                        ->first();

        $url = './SalesOrder/showpdf?idno='.$dbacthdr->idno;

        return $url;
    }

    public function pb($gltran){

        if($gltran->trantype == 'IN'){

            $dbacthdr = DB::table('debtor.dbacthdr')
                            ->where('compcode',session('compcode'))
                            ->where('source','=','PB')
                            ->where('trantype','=','IN')
                            ->where('auditno','=',$gltran->auditno)
                            ->first();

            return './SalesOrder/showpdf?idno='.$dbacthdr->idno;
        }else if($gltran->trantype == 'DN'){

            return './DebitNote/showpdf?auditno='.$gltran->auditno;

        }else if($gltran->trantype == 'CN'){

            return './CreditNoteAR/showpdf?auditno='.$gltran->auditno;

        }else if($gltran->trantype == 'RC'){

            $dbacthdr = DB::table('debtor.dbacthdr')
                            ->where('compcode',session('compcode'))
                            ->where('source','=','PB')
                            ->where('trantype','=','RC')
                            ->where('auditno','=',$gltran->auditno)
                            ->first();

            return './receipt/showpdf?auditno='.$dbacthdr->idno;
        }else if($gltran->trantype == 'RD'){

            $dbacthdr = DB::table('debtor.dbacthdr')
                            ->where('compcode',session('compcode'))
                            ->where('source','=','PB')
                            ->where('trantype','=','RD')
                            ->where('auditno','=',$gltran->auditno)
                            ->first();

            return './receipt/showpdf?auditno='.$dbacthdr->idno;
        }else if($gltran->trantype == 'RF'){

            $dbacthdr = DB::table('debtor.dbacthdr')
                            ->where('compcode',session('compcode'))
                            ->where('source','=','PB')
                            ->where('trantype','=','RF')
                            ->where('auditno','=',$gltran->auditno)
                            ->first();

            return './receipt/showpdf?auditno='.$dbacthdr->idno;
        }
    }

    public function ap($gltran){

        if($gltran->trantype == 'IN'){

        }else if($gltran->trantype == 'DN'){

        }else if($gltran->trantype == 'CN'){
            
        }else if($gltran->trantype == 'PV'){

            // $apacthdr = DB::table('finance.apacthdr')
            //                 ->where('compcode',session('compcode'))
            //                 ->where('source','=','AP')
            //                 ->where('trantype','=','PV')
            //                 ->where('auditno','=',$gltran->auditno)
            //                 ->first();

            return './paymentVoucher/showpdf?auditno='.$gltran->auditno.'&trantype=PV';
        }else if($gltran->trantype == 'PD'){

            // $apacthdr = DB::table('finance.apacthdr')
            //                 ->where('compcode',session('compcode'))
            //                 ->where('source','=','AP')
            //                 ->where('trantype','=','PD')
            //                 ->where('auditno','=',$gltran->auditno)
            //                 ->first();

            return './paymentVoucher/showpdf?auditno='.$gltran->auditno.'&trantype=PD';
        }
    }

    public function cm($gltran){

        if($gltran->trantype == 'CA'){

            // $apacthdr = DB::table('finance.apacthdr')
            //                 ->where('compcode',session('compcode'))
            //                 ->where('source','=','CM')
            //                 ->where('trantype','=','CA')
            //                 ->where('auditno','=',$gltran->auditno)
            //                 ->first();

            return './creditDebitTrans/showpdf?auditno='.$gltran->auditno;

        }else if($gltran->trantype == 'DA'){

            return './creditDebitTrans/showpdf?auditno='.$gltran->auditno;
        }else if($gltran->trantype == 'BS'){
            
        }else if($gltran->trantype == 'BD'){
            
        }else if($gltran->trantype == 'BQ'){
            
        }else if($gltran->trantype == 'FT'){
            
        }else if($gltran->trantype == 'DP'){
            
        }
    }

    public function oth($gltran){

        if($gltran->source == 'DO' && $gltran->trantype == 'GRN'){

            return './deliveryOrder/showpdf?recno='.$gltran->auditno;
        }else if($gltran->source == 'IV' && $gltran->trantype == 'GRN'){

            return './deliveryOrder/showpdf?recno='.$gltran->auditno;
        }
    }

    public function oe_data($obj){
        $billsum = DB::table('debtor.billsum as bs')
                        ->select('bs.chggroup','ch.description')
                        ->leftJoin('hisdb.chgmast as ch', function($join){
                            $join = $join->on('ch.chgcode', '=', 'bs.chggroup')
                                            ->where('ch.compcode','=',session('compcode'));
                        })
                        ->where('bs.compcode',session('compcode'))
                        ->where('bs.auditno',$obj->auditno)
                        ->first();

        $responce = new stdClass();
        $responce->desc = $billsum->description;
        $responce->refe = 'INV-'.$obj->reference;

        return $responce;
    }

    public function pb_data($obj){
        $responce = new stdClass();
        $responce->desc = '';

        if($obj->trantype == 'IN'){

            $dbacthdr = DB::table('debtor.dbacthdr as dbh')
                            ->select('dbh.payercode','dbm.name')
                            ->leftJoin('debtor.debtormast as dbm', function($join){
                                $join = $join->on('dbm.debtorcode', '=', 'dbh.payercode')
                                                ->where('dbm.compcode','=',session('compcode'));
                            })
                            ->where('dbh.compcode',session('compcode'))
                            ->where('dbh.source','=','PB')
                            ->where('dbh.trantype','=','IN')
                            ->where('dbh.auditno','=',$obj->auditno);
                            
            if($dbacthdr->exists()){
                $dbacthdr = $dbacthdr->first();
                $obj->description = $dbacthdr->payercode;
                $responce->desc = $dbacthdr->name;
            }

            $obj->reference = str_pad($obj->auditno, 7, "0", STR_PAD_LEFT);
            return $responce;

        }else if($obj->trantype == 'DN'){
            $dbacthdr = DB::table('debtor.dbacthdr as dbh')
                            ->select('dbh.payercode','dbm.name')
                            ->leftJoin('debtor.debtormast as dbm', function($join){
                                $join = $join->on('dbm.debtorcode', '=', 'dbh.payercode')
                                                ->where('dbm.compcode','=',session('compcode'));
                            })
                            ->where('dbh.compcode',session('compcode'))
                            ->where('dbh.source','=','PB')
                            ->where('dbh.trantype','=','DN')
                            ->where('dbh.auditno','=',$obj->auditno);

            if($dbacthdr->exists()){
                $dbacthdr = $dbacthdr->first();
                $obj->description = $dbacthdr->payercode;
                $responce->desc = $dbacthdr->name;
            }

            $obj->reference = 'DN-'.str_pad($obj->auditno, 7, "0", STR_PAD_LEFT);
            return $responce;

        }else if($obj->trantype == 'CN'){
            $dbacthdr = DB::table('debtor.dbacthdr as dbh')
                            ->select('dbh.payercode','dbm.name')
                            ->leftJoin('debtor.debtormast as dbm', function($join){
                                $join = $join->on('dbm.debtorcode', '=', 'dbh.payercode')
                                                ->where('dbm.compcode','=',session('compcode'));
                            })
                            ->where('dbh.compcode',session('compcode'))
                            ->where('dbh.source','=','PB')
                            ->where('dbh.trantype','=','CN')
                            ->where('dbh.auditno','=',$obj->auditno);

            if($dbacthdr->exists()){
                $dbacthdr = $dbacthdr->first();
                $obj->description = $dbacthdr->payercode;
                $responce->desc = $dbacthdr->name;
            }

            $obj->reference = 'CN-'.str_pad($obj->auditno, 7, "0", STR_PAD_LEFT);
            return $responce;

        }else if($obj->trantype == 'RC'){
            $dbacthdr = DB::table('debtor.dbacthdr as dbh')
                            ->select('dbh.payercode','dbm.name','dbh.recptno')
                            ->leftJoin('debtor.debtormast as dbm', function($join){
                                $join = $join->on('dbm.debtorcode', '=', 'dbh.payercode')
                                                ->where('dbm.compcode','=',session('compcode'));
                            })
                            ->where('dbh.compcode',session('compcode'))
                            ->where('dbh.source','=','PB')
                            ->where('dbh.trantype','=','RC')
                            ->where('dbh.auditno','=',$obj->auditno);

            if($dbacthdr->exists()){
                $dbacthdr = $dbacthdr->first();
                $obj->description = $dbacthdr->payercode;
                $responce->desc = $dbacthdr->name;
                $obj->reference = $dbacthdr->recptno;
            }

            return $responce;

        }else if($obj->trantype == 'RD'){
            $dbacthdr = DB::table('debtor.dbacthdr as dbh')
                            ->select('dbh.payercode','dbm.name','dbh.recptno')
                            ->leftJoin('debtor.debtormast as dbm', function($join){
                                $join = $join->on('dbm.debtorcode', '=', 'dbh.payercode')
                                                ->where('dbm.compcode','=',session('compcode'));
                            })
                            ->where('dbh.compcode',session('compcode'))
                            ->where('dbh.source','=','PB')
                            ->where('dbh.trantype','=','RD')
                            ->where('dbh.auditno','=',$obj->auditno);

            if($dbacthdr->exists()){
                $dbacthdr = $dbacthdr->first();
                $obj->description = $dbacthdr->payercode;
                $responce->desc = $dbacthdr->name;
                $obj->reference = $dbacthdr->recptno;
            }

            return $responce;

        }else if($obj->trantype == 'RF'){
            $dbacthdr = DB::table('debtor.dbacthdr as dbh')
                            ->select('dbh.payercode','dbm.name','dbh.recptno')
                            ->leftJoin('debtor.debtormast as dbm', function($join){
                                $join = $join->on('dbm.debtorcode', '=', 'dbh.payercode')
                                                ->where('dbm.compcode','=',session('compcode'));
                            })
                            ->where('dbh.compcode',session('compcode'))
                            ->where('dbh.source','=','PB')
                            ->where('dbh.trantype','=','RF')
                            ->where('dbh.auditno','=',$obj->auditno);

            if($dbacthdr->exists()){
                $dbacthdr = $dbacthdr->first();
                $obj->description = $dbacthdr->payercode;
                $responce->desc = $dbacthdr->name;
                $obj->reference = $dbacthdr->recptno;
            }

            return $responce;

        }
    }
    
    public function ap_data($obj){
        return 0;
    }
    
    public function cm_data($obj){
        return 0;
    }
    
    public function oth_data($obj){
        $responce = new stdClass();

        // $exp1 = explode('</br>', $obj->description);
        // $exp2 = explode(' ', $obj->reference);

        $obj->description = $obj->description;
        $responce->desc = $obj->description;
        $responce->refe = $obj->reference;

        return $responce;
    }

    public function print(Request $request){
        $glaccount = $request->glaccount;
        if(empty($glaccount)){
            abort(404);
        }
        return Excel::download(new acctenq_dateExport($request->glaccount,$request->fromdate,$request->todate), 'acctenq_dateExport.xlsx');
    }

    public function download(Request $request){
        $job_queue = DB::table('sysdb.job_queue')
                        ->where('idno', $request->job_id)
                        ->where('compcode', session('compcode'))
                        ->where('page', 'acctenq_date')
                        ->where('status', 'DONE')
                        ->orderBy('idno', 'desc')
                        ->first();

        return Excel::download(new acctenq_dateExport_2($job_queue->idno,$job_queue->type,$job_queue->date,$job_queue->date_to), 'GLAccount_'.$job_queue->type.'.xlsx');
    }

    public function start_job_queue($glaccount,$fromdate,$todate){
        $idno = DB::table('sysdb.job_queue')
                ->insertGetId([
                    'compcode' => session('compcode'),
                    'page' => 'acctenq_date',
                    'filename' => 'Account Enq '.$glaccount,
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'status' => 'PENDING',
                    'remarks' => 'acctenq_date for account '.$glaccount.' from '.$fromdate.' to '.$todate,
                    'type' => $glaccount,
                    'date' => $fromdate,
                    'date_to' => $todate,
                ]);

        return $idno;
    }
}