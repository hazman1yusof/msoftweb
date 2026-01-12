<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use App\Exports\ARAgeingDtlExport;
use App\Exports\ARAgeingDtlExport_2;
use App\Exports\ARAgeingDtlExport_statement;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;
use Response;
use Symfony\Component\Process\Process;
// use App\Jobs\ARAgeingDtlProcess;

class ARAgeingDtl_ReportController extends defaultController
{
    
    var $table;
    var $duplicateCode;
    var $auditno;
    
    public function __construct()
    {
        // $this->middleware('auth');
    }
    
    public function show(Request $request)
    {
        $comp = DB::table('sysdb.company')->where('compcode','=',session('compcode'))->first();
        $scope = '0';
        if($request->scope == 'statement'){
            $scope = '1';
        }
        return view('finance.AR.ARAgeingDtl_Report.ARAgeingDtl_Report',[
            'company_name' => $comp->name,
            'scope' => $scope
        ]);
    }

    public function table(Request $request){ 
        switch($request->action){
            case 'job_queue':
                return $this->job_queue($request);
            case 'download':
                return $this->download2($request);
            case 'download_statement':
                return $this->download_statement($request);
            case 'process_excel':
                return $this->process_excel($request);
            default:
                return 'error happen..';
        }
    }
    
    public function form(Request $request)
    {
        switch($request->action){
            case 'showExcel':
                $PYTHON_PATH = \config('get_config.PYTHON_PATH');
                if($PYTHON_PATH != null){
                    return $this->process_excel($request);;
                }else{
                    return $this->process_excel_link($request);
                }
            default:
                return 'error happen..';
        }
    }

    public function job_queue(Request $request){
        $responce = new stdClass();

        $table_ = DB::table('sysdb.job_queue')
                        ->where('compcode', session('compcode'))
                        ->where('page', 'ARAgeing')
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

    public function download2(Request $request){
        $job_queue = DB::table('sysdb.job_queue')
                        ->where('idno',$request->idno)
                        ->first();

        return Excel::download(new ARAgeingDtlExport_2($request->idno), $job_queue->filename);
    }

    public function download_statement(Request $request){
        $job_queue = DB::table('sysdb.job_queue')
                        ->where('idno',$request->idno)
                        ->first();

        return Excel::download(new ARAgeingDtlExport_statement($request->idno), $job_queue->filename);
    }
    
    public function showExcel(Request $request){

        if($request->type == 'detail'){
            $filename = 'ARAgeingDetail '.Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d g:i A').'.xlsx';
        }else{
            $filename = 'ARAgeingSummary '.Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d g:i A').'.xlsx';
        }

        $bytes = random_bytes(20);
        $process = bin2hex($bytes).'.xlsx';

        (new ARAgeingDtlExport($process,$filename,$request->type,$request->date,$request->debtortype,$request->debtorcode_from,$request->debtorcode_to,$request->groupOne,$request->groupTwo,$request->groupThree,$request->groupFour,$request->groupFive,$request->groupSix,$request->groupby))->store($process, \config('get_config.ATTACHMENT_UPLOAD'));

        // (new InvoicesExport)->queue('invoices.xlsx');

        // return back();

        // return Excel::download(new ARAgeingDtlExport($request->type,$request->date,$request->debtortype,$request->debtorcode_from,$request->debtorcode_to,$request->groupOne,$request->groupTwo,$request->groupThree,$request->groupFour,$request->groupFive,$request->groupSix), $filename);
    }

    public function process_excel_link(Request $request){
        $client = new \GuzzleHttp\Client();
        $PYTHON_SERVER = \config('get_config.PYTHON_SERVER');

        $url = $PYTHON_SERVER.'/msoftweb/public/ARAgeingDtl_Report/table?action=process_excel&type='.$request->type.'&debtortype='.$request->debtortype.'&debtorcode_from='.$request->debtorcode_from.'&debtorcode_to='.$request->debtorcode_to.'&date='.$request->date.'&groupOne='.$request->groupOne.'&groupTwo='.$request->groupTwo.'&groupThree='.$request->groupThree.'&groupFour='.$request->groupFour.'&groupFive='.$request->groupFive.'&groupSix='.$request->groupSix.'&groupby='.$request->groupby.'&username='.session('username').'&compcode='.session('compcode');

        $response = $client->request('GET', $url, [
          'headers' => [
            'accept' => 'application/json',
          ],
        ]);
    }

    public function process_excel(Request $request){
        $data = [
            'DATA1' => [
                'username' => ($request->username)?$request->username:'-',
                'compcode' => ($request->compcode)?$request->compcode:'9B',
                'type' => $request->type,
                'date' => $request->date,
                'debtortype' => $request->debtortype,
                'debtorcode_from' => $request->debtorcode_from,
                'debtorcode_to' => $request->debtorcode_to,
                'groupOne' => $request->groupOne,
                'groupTwo' => $request->groupTwo,
                'groupThree' => $request->groupThree,
                'groupFour' => $request->groupFour,
                'groupFive' => $request->groupFive,
                'groupSix' => $request->groupSix,
                'groupby' => $request->groupby,
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

        $path = \config('get_config.EXEC_PATH').'\\arageing.ini';
        file_put_contents($path, $iniString);

        $compcode=($request->compcode)?$request->compcode:'9B';
        if($this->block_if_job_pending($compcode)){
            return response()->json([
                'status' => 'Other job still pending'
            ]);
        }else{
            // Path to your Python script
            $scriptPath = \config('get_config.EXEC_PATH').'\\arageing.py'; // double backslashes for Windows paths
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

    public function process_excel_lama(Request $request){

        if($request->type == 'detail'){
            $filename = 'ARAgeingDetail '.Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d g:i A').'.xlsx';
        }else{
            $filename = 'ARAgeingSummary '.Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d g:i A').'.xlsx';
        }

        $bytes = random_bytes(20);
        $process = bin2hex($bytes).'.xlsx';

        $username = ($request->username)?$request->username:'-';
        $compcode = ($request->compcode)?$request->compcode:'9B';
        $type = $request->type;
        $date = $request->date;
        $debtortype = $request->debtortype;
        $debtorcode_from = strtoupper($request->debtorcode_from);
        $debtorcode_to = strtoupper($request->debtorcode_to);

        $groupOne = $request->groupOne;
        $groupTwo = $request->groupTwo;
        $groupThree = $request->groupThree;
        $groupFour = $request->groupFour;
        $groupFive = $request->groupFive;
        $groupSix = $request->groupSix;
        $groupby = $request->groupby;

        $grouping = [];
        $grouping[0] = 0;
        if(!empty($groupOne)){
            $grouping[1] = $groupOne;
        }
        if(!empty($groupTwo)){
            $grouping[2] = $groupTwo;
        }
        if(!empty($groupThree)){
            $grouping[3] = $groupThree;
        }
        if(!empty($groupFour)){
            $grouping[4] = $groupFour;
        }
        if(!empty($groupFive)){
            $grouping[5] = $groupFive;
        }
        if(!empty($groupSix)){
            $grouping[6] = $groupSix;
        }

        $this->username = $username;
        $this->compcode = $compcode;
        $this->process = $process;
        $this->filename = $filename;
        $this->type = $type;
        $this->date = Carbon::parse($date)->format('Y-m-d');
        $this->debtortype = $debtortype;
        $this->debtorcode_from = $debtorcode_from;
        if(empty($debtorcode_from)){
            $this->debtorcode_from = '%';
        }
        $this->debtorcode_to = $debtorcode_to;

        $this->groupOne = $groupOne;
        $this->groupTwo = $groupTwo;
        $this->groupThree = $groupThree;
        $this->groupFour = $groupFour;
        $this->groupFive = $groupFive;
        $this->groupSix = $groupSix;
        $this->groupby = $groupby;

        $this->grouping = [];
        $this->grouping[0] = 0;
        if(!empty($this->groupOne)){
            $this->grouping[1] = $this->groupOne;
        }
        if(!empty($this->groupTwo)){
            $this->grouping[2] = $this->groupTwo;
        }
        if(!empty($this->groupThree)){
            $this->grouping[3] = $this->groupThree;
        }
        if(!empty($this->groupFour)){
            $this->grouping[4] = $this->groupFour;
        }
        if(!empty($this->groupFive)){
            $this->grouping[5] = $this->groupFive;
        }
        if(!empty($this->groupSix)){
            $this->grouping[6] = $this->groupSix;
        }

        $idno_job_queue = $this->start_job_queue('ARAgeing');

        $debtormast = DB::table('debtor.debtormast as dm')
                        ->select('dh.idno', 'dh.source', 'dh.trantype', 'dh.auditno', 'dh.lineno_', 'dh.amount', 'dh.outamount', 'dh.recstatus', 'dh.entrydate', 'dh.entrytime', 'dh.entryuser', 'dh.reference', 'dh.recptno', 'dh.paymode', 'dh.tillcode', 'dh.tillno', 'dh.debtortype', 'dh.debtorcode', 'dh.payercode', 'dh.billdebtor', 'dh.remark', 'dh.mrn', 'dh.episno', 'dh.authno', 'dh.expdate', 'dh.adddate', 'dh.adduser', 'dh.upddate', 'dh.upduser', 'dh.deldate', 'dh.deluser', 'dh.epistype', 'dh.cbflag', 'dh.conversion', 'dh.payername', 'dh.hdrtype', 'dh.currency', 'dh.rate', 'dh.unit', 'dh.invno', 'dh.paytype', 'dh.bankcharges', 'dh.RCCASHbalance', 'dh.RCOSbalance', 'dh.RCFinalbalance', 'dh.PymtDescription', 'dh.orderno', 'dh.ponum', 'dh.podate', 'dh.termdays', 'dh.termmode', 'dh.deptcode', 'dh.posteddate', 'dh.approvedby', 'dh.approveddate', 'pm.Name as pm_name','dm.debtortype','dt.debtortycode','dt.description','dm.name','st.description as unit_desc')
                        ->join('debtor.debtortype as dt', function($join) use ($debtortype,$compcode){
                            $join = $join->on('dt.debtortycode', '=', 'dm.debtortype')
                                         ->where('dt.compcode', '=', $compcode);
                            if(strtoupper($debtortype)!='ALL'){
                                $join = $join->where('dt.debtortycode',$debtortype);
                            }
                        })
                        ->join('debtor.dbacthdr as dh', function($join) use ($date,$compcode){
                            $join = $join->on('dh.debtorcode', '=', 'dm.debtorcode')
                                         ->whereDate('dh.posteddate', '<=', $date)
                                         ->where('dh.recstatus', 'POSTED')
                                         ->where('dh.compcode', '=', $compcode);
                        })
                        ->join('sysdb.sector as st', function($join) use ($date,$compcode){
                            $join = $join->on('st.sectorcode', '=', 'dh.unit')
                                         ->where('st.compcode', '=', $compcode);
                        })->leftJoin('hisdb.pat_mast as pm', function($join) use ($compcode){
                            $join = $join->on('pm.NewMrn', '=', 'dh.mrn')
                                         ->where('pm.compcode', '=', $compcode);
                        })
                        ->where('dm.compcode', '=', $compcode);

                        if($debtorcode_from == $debtorcode_to){
                            $debtormast = $debtormast->where('dm.debtorcode',$debtorcode_from);
                        }else if(empty($debtorcode_from) && $debtorcode_to == 'ZZZ'){

                        }else{
                            $debtormast = $debtormast->whereBetween('dm.debtorcode', [$debtorcode_from,$debtorcode_to.'%']);
                        }

                        $debtormast = $debtormast
                            ->orderBy('dm.debtorcode', 'ASC')
                            ->get();

        $array_report = [];

        foreach ($debtormast as $key => $value){
            $value->remark = '';
            $value->doc_no = '';
            $value->newamt = 0;

            $hdr_amount = $value->amount;
            
            // to calculate interval (days)
            $datetime1 = new DateTime($date);
            $datetime2 = new DateTime($value->posteddate);
            
            $interval = $datetime1->diff($datetime2);
            $days = $interval->format('%a');
            $value->group = $this->assign_grouping($grouping,$days);
            $value->days = $days;
            
            if($value->trantype == 'IN' || $value->trantype =='DN') {
                $alloc_sum = DB::table('debtor.dballoc as da')
                        ->where('da.compcode', '=', $compcode)
                        ->where('da.recstatus', '=', "POSTED")
                        // ->where('da.debtorcode', '=', $value->debtorcode)
                        ->where('da.refsource', '=', $value->source)
                        ->where('da.reftrantype', '=', $value->trantype)
                        ->where('da.refauditno', '=', $value->auditno)
                        ->where('da.reflineno', '=', $value->lineno_)
                        ->whereDate('da.allocdate', '<=', $date)
                        ->sum('da.amount');
                
                $newamt = $hdr_amount - $alloc_sum;
            }else{
                $doc_sum = DB::table('debtor.dballoc as da')
                        ->where('da.compcode', '=', $compcode)
                        ->where('da.recstatus', '=', "POSTED")
                        // ->where('da.debtorcode', '=', $value->debtorcode)
                        ->where('da.docsource', '=', $value->source)
                        ->where('da.doctrantype', '=', $value->trantype)
                        ->where('da.docauditno', '=', $value->auditno)
                        ->whereDate('da.allocdate', '<=', $date)
                        ->sum('da.amount');
                
                $ref_sum = DB::table('debtor.dballoc as da')
                        ->where('da.compcode', '=', $compcode)
                        ->where('da.recstatus', '=', "POSTED")
                        // ->where('da.debtorcode', '=', $value->debtorcode)
                        ->where('da.refsource', '=', $value->source)
                        ->where('da.reftrantype', '=', $value->trantype)
                        ->where('da.refauditno', '=', $value->auditno)
                        ->whereDate('da.allocdate', '<=', $date)
                        ->sum('da.amount');
                
                $newamt = -($hdr_amount - $doc_sum - $ref_sum);
            }
            
            switch ($value->trantype) {
                case 'IN':
                    if($value->mrn == '0' || $value->mrn == ''){
                        $value->remark = $value->remark;
                    }else{
                        $value->remark = $value->pm_name;
                    }

                    if(!empty($value->invno)){
                        $value->doc_no = $value->trantype.'/'.str_pad($value->invno, 7, "0", STR_PAD_LEFT);
                    }else{
                        $value->doc_no = $value->trantype.'/'.str_pad($value->auditno, 7, "0", STR_PAD_LEFT);
                    }

                    $value->newamt = $newamt;
                    if(floatval($newamt) != 0.00){
                        array_push($array_report, $value);
                    }
                    break;
                case 'DN':
                    $value->remark = $value->remark;
                    $value->doc_no = $value->trantype.'/'.str_pad($value->auditno, 7, "0", STR_PAD_LEFT);
                    $value->newamt = $newamt;
                    if(floatval($newamt) != 0.00){
                        array_push($array_report, $value);
                    }
                    break;
                case 'BC':
                    // $value->remark
                    $value->doc_no = $value->trantype.'/'.str_pad($value->auditno, 7, "0", STR_PAD_LEFT);
                    $value->newamt = $newamt;
                    if(floatval($newamt) != 0.00){
                        array_push($array_report, $value);
                    }
                    break;
                case 'RF':
                    $value->remark = $value->remark;
                    $value->doc_no = $value->recptno;
                    $value->newamt = $newamt;
                    if(floatval($newamt) != 0.00){
                        array_push($array_report, $value);
                    }
                    break;
                case 'CN':
                    $value->remark = $value->remark;
                    $value->doc_no = $value->trantype.'/'.str_pad($value->auditno, 7, "0", STR_PAD_LEFT);
                    $value->newamt = $newamt;
                    if(floatval($newamt) != 0.00){
                        array_push($array_report, $value);
                    }
                    break;
                case 'RC':
                    $value->remark = $value->remark;
                    $value->doc_no = $value->recptno;
                    $value->newamt = $newamt;
                    if(floatval($newamt) != 0.00){
                        array_push($array_report, $value);
                    }
                    break;
                case 'RD':
                    $value->remark = $value->remark;
                    $value->doc_no = $value->recptno;
                    $value->newamt = $newamt;
                    if(floatval($newamt) != 0.00){
                        array_push($array_report, $value);
                    }
                    break;
                case 'RT':
                    // $value->remark
                    $value->doc_no = $value->trantype.'/'.str_pad($value->auditno, 7, "0", STR_PAD_LEFT);
                    $value->newamt = $newamt;
                    if(floatval($newamt) != 0.00){
                        array_push($array_report, $value);
                    }
                    break;
                default:
                    // code...
                    break;
            }            
        }

        $this->store_to_db($array_report,$idno_job_queue);

        $this->stop_job_queue($idno_job_queue);
    }

    public function assign_grouping($grouping,$days){
        $group = 0;

        foreach ($grouping as $key => $value) {
            if(!empty($value) && $days >= intval($value)){
                $group = $key;
            }
        }

        return $group;
    }

    public function start_job_queue($page){

        $idno_job_queue = DB::table('sysdb.job_queue')
                            ->insertGetId([
                                'compcode' => $this->compcode,
                                'page' => $page,
                                'filename' => $this->filename,
                                'process' => $this->process,
                                'adduser' => $this->username,
                                'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                                'status' => 'PENDING',
                                'remarks' => 'AR Ageing '.$this->type.' as of '.$this->date.', debtortype: '.$this->debtortype.', debtorcode from:"'.$this->debtorcode_from.'" to "'.$this->debtorcode_to.'"',
                                'type' => $this->type,
                                'date' => $this->date,
                                'debtortype' => $this->debtortype,
                                'debtorcode_from' => $this->debtorcode_from,
                                'debtorcode_to' => $this->debtorcode_to,
                                'groupOne' => $this->groupOne,
                                'groupTwo' => $this->groupTwo,
                                'groupThree' => $this->groupThree,
                                'groupFour' => $this->groupFour,
                                'groupFive' => $this->groupFive,
                                'groupSix' => $this->groupSix,
                                'groupby' => $this->groupby
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

    public function store_to_db($array_report,$idno_job_queue){
        foreach ($array_report as $obj){
            DB::table('debtor.ARAgeing')
                ->insert([
                    'job_id' => $idno_job_queue,
                    'idno' => $obj->idno,
                    'source' => $obj->source,
                    'trantype' => $obj->trantype,
                    'auditno' => $obj->auditno,
                    'lineno_' => $obj->lineno_,
                    'amount' => $obj->amount,
                    'outamount' => $obj->outamount,
                    'recstatus' => $obj->recstatus,
                    'entrydate' => $obj->entrydate,
                    'entrytime' => $obj->entrytime,
                    'entryuser' => $obj->entryuser,
                    'reference' => $obj->reference,
                    'recptno' => $obj->recptno,
                    'paymode' => $obj->paymode,
                    'tillcode' => $obj->tillcode,
                    'tillno' => $obj->tillno,
                    'debtortype' => $obj->debtortype,
                    'debtorcode' => $obj->debtorcode,
                    'payercode' => $obj->payercode,
                    'billdebtor' => $obj->billdebtor,
                    'remark' => $obj->remark,
                    'mrn' => $obj->mrn,
                    'episno' => $obj->episno,
                    'authno' => $obj->authno,
                    'expdate' => $obj->expdate,
                    'adddate' => $obj->adddate,
                    'adduser' => $obj->adduser,
                    'upddate' => $obj->upddate,
                    'upduser' => $obj->upduser,
                    'deldate' => $obj->deldate,
                    'deluser' => $obj->deluser,
                    'epistype' => $obj->epistype,
                    'cbflag' => $obj->cbflag,
                    'conversion' => $obj->conversion,
                    'payername' => $obj->payername,
                    'hdrtype' => $obj->hdrtype,
                    'currency' => $obj->currency,
                    'rate' => $obj->rate,
                    'unit' => $obj->unit,
                    'invno' => $obj->invno,
                    'paytype' => $obj->paytype,
                    'bankcharges' => $obj->bankcharges,
                    'RCCASHbalance' => $obj->RCCASHbalance,
                    'RCOSbalance' => $obj->RCOSbalance,
                    'RCFinalbalance' => $obj->RCFinalbalance,
                    'PymtDescription' => $obj->PymtDescription,
                    'orderno' => $obj->orderno,
                    'ponum' => $obj->ponum,
                    'podate' => $obj->podate,
                    'termdays' => $obj->termdays,
                    'termmode' => $obj->termmode,
                    'deptcode' => $obj->deptcode,
                    'posteddate' => $obj->posteddate,
                    'approvedby' => $obj->approvedby,
                    'approveddate' => $obj->approveddate,
                    'pm_name' => $obj->pm_name,
                    'debtortycode' => $obj->debtortycode,
                    'description' => $obj->description,
                    'name' => $obj->name,
                    'unit_desc' => $obj->unit_desc,
                    'doc_no' => $obj->doc_no,
                    'newamt' => $obj->newamt,
                    'group' => $obj->group
                ]);
        }
    }

    public function block_if_job_pending($compcode){
        $last_job = DB::table('sysdb.job_queue')
                        ->where('compcode', $compcode)
                        ->where('page', 'ARAgeing')
                        ->orderBy('idno', 'desc')
                        ->first();

        if($last_job->status != 'DONE'){
            return true;
        }

        return false;
    }
}