<?php

namespace App\Exports;

use DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use DateTime;
use Carbon\Carbon;
use stdClass;

class ARAgeingCollE_Report_job implements FromView, ShouldQueue, WithEvents
{
    
    use Exportable;
    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function __construct($request)
    {
        $filename = 'ARAgeingCollection '.Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d g:i A').'.xlsx';

        $bytes = random_bytes(20);
        $process = bin2hex($bytes).'.xlsx';

        $username = ($request->username)?$request->username:'-';
        $compcode = ($request->compcode)?$request->compcode:'9B';
        $date_from = $request->date_from;
        $date_to = $request->date_to;
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

        $this->date_from = Carbon::parse($date_from)->format('Y-m-d');
        $this->date_to = Carbon::parse($date_to)->format('Y-m-d');
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
    }

    public function columnFormats(): array
    {
    }
    
    public function columnWidths(): array
    {
    }
    
    public function view(): View
    {
        $username = $this->username;
        $compcode = $this->compcode;
        $date_from = $this->date_from;
        $date_to = $this->date_to;
        $debtorcode_from = $this->debtorcode_from;
        $debtorcode_to = $this->debtorcode_to;

        $groupOne = $this->groupOne;
        $groupTwo = $this->groupTwo;
        $groupThree = $this->groupThree;
        $groupFour = $this->groupFour;
        $groupFive = $this->groupFive;
        $groupSix = $this->groupSix;
        $groupby = $this->groupby;

        $grouping = $this->grouping;

        $idno_job_queue = $this->start_job_queue('ARAgeingColl');

        $debtormast = DB::table('debtor.debtormast as dm')
                        ->select('dh.idno', 'dh.source', 'dh.trantype', 'dh.auditno', 'dh.lineno_', 'dh.amount', 'dh.outamount', 'dh.recstatus', 'dh.entrydate', 'dh.entrytime', 'dh.entryuser', 'dh.reference', 'dh.recptno', 'dh.paymode', 'dh.tillcode', 'dh.tillno', 'dh.debtortype', 'dh.debtorcode', 'dh.payercode', 'dh.billdebtor', 'dh.remark', 'dh.mrn', 'dh.episno', 'dh.authno', 'dh.expdate', 'dh.adddate', 'dh.adduser', 'dh.upddate', 'dh.upduser', 'dh.deldate', 'dh.deluser', 'dh.epistype', 'dh.cbflag', 'dh.conversion', 'dh.payername', 'dh.hdrtype', 'dh.currency', 'dh.rate', 'dh.unit', 'dh.invno', 'dh.paytype', 'dh.bankcharges', 'dh.RCCASHbalance', 'dh.RCOSbalance', 'dh.RCFinalbalance', 'dh.PymtDescription', 'dh.orderno', 'dh.ponum', 'dh.podate', 'dh.termdays', 'dh.termmode', 'dh.deptcode', 'dh.posteddate', 'dh.approvedby', 'dh.approveddate', 'pm.Name as pm_name','dm.debtortype','dm.name','st.description as unit_desc')
                        ->join('debtor.dbacthdr as dh', function($join) use ($date_from,$date_to,$compcode){
                            $join = $join->on('dh.debtorcode', '=', 'dm.debtorcode')
                                         ->whereDate('dh.posteddate', '>=', $date_from)
                                         ->whereDate('dh.posteddate', '<=', $date_to)
                                         ->whereIn('dh.trantype', ['RC','RD'])
                                         ->where('dh.recstatus', 'POSTED')
                                         // ->where('dh.auditno','5209785')
                                         ->where('dh.compcode', '=', $compcode);
                        })
                        ->join('sysdb.sector as st', function($join) use ($compcode){
                            $join = $join->on('st.sectorcode', '=', 'dh.unit')
                                         ->where('st.compcode', '=', $compcode);
                        })
                        ->leftJoin('hisdb.pat_mast as pm', function($join) use ($compcode){
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

        $array_report_1 = [];
        $array_report_2 = [];
        foreach ($debtormast as $key => $value){
            $value->remark = '';
            $value->doc_no = '';
            $value->newamt = $value->amount;
            $value->group = '';
            $value->group_type = 1;
            $value->days = '';
            $value->link_idno = '';

            $hdr_amount = $value->amount;
            $punallocamt = $value->amount;

            $dballoc = DB::table('debtor.dballoc as da')
                            ->where('da.compcode', '=', $compcode)
                            ->where('da.recstatus', '=', "POSTED")
                            // ->where('da.debtorcode', '=', $value->debtorcode)
                            ->where('da.docsource', '=', $value->source)
                            ->where('da.doctrantype', '=', $value->trantype)
                            ->where('da.docauditno', '=', $value->auditno)
                            ->whereDate('da.allocdate', '<=', $date_to)
                            ->get();

            foreach ($dballoc as $obj_dballoc) {

                $punallocamt = $punallocamt - $obj_dballoc->amount;

                $ref_db = DB::table('debtor.debtormast as dm')
                            ->select('dh.idno', 'dh.source', 'dh.trantype', 'dh.auditno', 'dh.lineno_', 'dh.amount', 'dh.outamount', 'dh.recstatus', 'dh.entrydate', 'dh.entrytime', 'dh.entryuser', 'dh.reference', 'dh.recptno', 'dh.paymode', 'dh.tillcode', 'dh.tillno', 'dh.debtortype', 'dh.debtorcode', 'dh.payercode', 'dh.billdebtor', 'dh.remark', 'dh.mrn', 'dh.episno', 'dh.authno', 'dh.expdate', 'dh.adddate', 'dh.adduser', 'dh.upddate', 'dh.upduser', 'dh.deldate', 'dh.deluser', 'dh.epistype', 'dh.cbflag', 'dh.conversion', 'dh.payername', 'dh.hdrtype', 'dh.currency', 'dh.rate', 'dh.unit', 'dh.invno', 'dh.paytype', 'dh.bankcharges', 'dh.RCCASHbalance', 'dh.RCOSbalance', 'dh.RCFinalbalance', 'dh.PymtDescription', 'dh.orderno', 'dh.ponum', 'dh.podate', 'dh.termdays', 'dh.termmode', 'dh.deptcode', 'dh.posteddate', 'dh.approvedby', 'dh.approveddate', 'pm.Name as pm_name','dm.debtortype','dm.name','st.description as unit_desc')
                            ->join('debtor.dbacthdr as dh', function($join) use ($compcode,$obj_dballoc){
                                $join = $join->on('dh.debtorcode', '=', 'dm.debtorcode')
                                            ->where('dh.source', '=', $obj_dballoc->refsource)
                                            ->where('dh.trantype', '=', $obj_dballoc->reftrantype)
                                            ->where('dh.auditno', '=', $obj_dballoc->refauditno)
                                            ->where('dh.recstatus', 'POSTED')
                                            ->where('dh.compcode', '=', $compcode);
                            })
                            ->join('sysdb.sector as st', function($join) use ($compcode){
                                $join = $join->on('st.sectorcode', '=', 'dh.unit')
                                             ->where('st.compcode', '=', $compcode);
                            })
                            ->leftJoin('hisdb.pat_mast as pm', function($join) use ($compcode){
                                $join = $join->on('pm.NewMrn', '=', 'dh.mrn')
                                             ->where('pm.compcode', '=', $compcode);
                            })
                            ->where('dm.compcode', '=', $compcode)
                            ->get();

                foreach ($ref_db as $obj_ref_db) {
                    $datetime1 = new DateTime($date_to);
                    $datetime2 = new DateTime($obj_ref_db->posteddate);
                
                    $interval = $datetime1->diff($datetime2);
                    $days = $interval->format('%a');
                    $obj_ref_db->remark = $obj_ref_db->remark;
                    $obj_ref_db->doc_no = $obj_ref_db->invno;
                    $obj_ref_db->newamt = $obj_ref_db->amount;
                    $obj_ref_db->group = $this->assign_grouping($grouping,$days);
                    $obj_ref_db->group_type = 2;
                    $obj_ref_db->days = $days;
                    $obj_ref_db->punallocamt = '';
                    $obj_ref_db->link_idno = $value->idno;

                    array_push($array_report_2, $obj_ref_db);
                }
            }
            $value->punallocamt = $punallocamt;
            array_push($array_report_1, $value);
        }

        // dd($array_report_1);

        $this->store_to_db($array_report_1,$array_report_2,$idno_job_queue);

        $this->stop_job_queue($idno_job_queue);
    }
    
    public function registerEvents(): array
    {
        return [
           
        ];
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

    public function store_to_db($array_report_1,$array_report_2,$idno_job_queue){
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
