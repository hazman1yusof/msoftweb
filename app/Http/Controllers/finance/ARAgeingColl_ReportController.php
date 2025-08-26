<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use App\Exports\ARAgeingCollExport;
use App\Exports\ARAgeingCollE_Report_job;
use Maatwebsite\Excel\Facades\Excel;

class ARAgeingColl_ReportController extends defaultController
{   

    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        return view('finance.AR.ARAgeingColl_Report.ARAgeingColl_Report');
    }

    public function table(Request $request){ 
        switch($request->action){
            case 'job_queue':
                return $this->job_queue($request);
            case 'download':
                return $this->download($request);
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
                return $this->process_excel_link($request);
                // return $this->process_excel($request);
            default:
                return 'error happen..';
        }
    }

    public function download(Request $request){
        $job_queue = DB::table('sysdb.job_queue')
                        ->where('idno',$request->idno)
                        ->first();

        return Excel::download(new ARAgeingCollExport($request->idno), $job_queue->filename);
    }

    public function job_queue(Request $request){
        $responce = new stdClass();

        $table_ = DB::table('sysdb.job_queue')
                        ->where('compcode', session('compcode'))
                        ->where('page', 'ARAgeingColl')
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

    public function process_excel_link(Request $request){
        $client = new \GuzzleHttp\Client();

        $url='http://192.168.0.13:8443/msoftweb/public/ARAgeingColl_Report/table?action=process_excel&debtorcode_from='.$request->debtorcode_from.'&debtorcode_to='.$request->debtorcode_to.'&date_from='.$request->date_from.'&date_to='.$request->date_to.'&groupOne='.$request->groupOne.'&groupTwo='.$request->groupTwo.'&groupThree='.$request->groupThree.'&groupFour='.$request->groupFour.'&groupFive='.$request->groupFive.'&groupSix='.$request->groupSix.'&groupby='.$request->groupby.'&username='.session('username').'&compcode='.session('compcode');

        $response = $client->request('GET', $url, [
          'headers' => [
            'accept' => 'application/json',
          ],
        ]);
    }

    public function process_excel(Request $request){
        (new ARAgeingCollE_Report_job($request))->store('test.xlsx', \config('get_config.ATTACHMENT_UPLOAD'));
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
                                'remarks' => 'AR Ageing Collection as of '.$this->date_from.', debtorcode from:"'.$this->debtorcode_from.'" to "'.$this->debtorcode_to.'"',
                                'type' => '-',
                                'date' => $this->date_from,
                                'date_to' => $this->date_to,
                                'debtortype' => '-',
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
                    // 'debtortycode' => $obj->debtortycode,
                    // 'description' => $obj->description,
                    'name' => $obj->name,
                    'unit_desc' => $obj->unit_desc,
                    'doc_no' => $obj->doc_no,
                    'newamt' => $obj->newamt,
                    'group' => $obj->group,
                    'group_type' => $obj->group_type,
                    'punallocamt' => $obj->punallocamt,
                    'link_idno' => $obj->link_idno
                ]);
        }
    }
}