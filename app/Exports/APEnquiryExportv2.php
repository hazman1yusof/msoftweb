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

class ApEnquiryExportv2 implements FromView, ShouldQueue, WithEvents, WithColumnWidths, WithColumnFormatting
{
    
    use Exportable;
    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function __construct($date,$suppcode_from,$suppcode_to)
    {
        
        $this->date = Carbon::parse($date)->format('Y-m-d');
        $this->suppcode_from = $suppcode_from;
        if(empty($suppcode_from)){
            $this->suppcode_from = '%';
        }
        $this->suppcode_to = $suppcode_to;

        $this->groupOne = 30;
        $this->groupTwo = 60;
        $this->groupThree = 90;
        $this->groupFour = 120;

        $this->grouping = [];
        $this->grouping_tot = [];
        $this->grouping[0] = 0;
        $this->grouping_tot[0] = 0;
        if(!empty($this->groupOne)){
            $this->grouping[1] = $this->groupOne;
            $this->grouping_tot[1] = 0;
        }
        if(!empty($this->groupTwo)){
            $this->grouping[2] = $this->groupTwo;
            $this->grouping_tot[2] = 0;
        }
        if(!empty($this->groupThree)){
            $this->grouping[3] = $this->groupThree;
            $this->grouping_tot[3] = 0;
        }
        if(!empty($this->groupFour)){
            $this->grouping[4] = $this->groupFour;
            $this->grouping_tot[4] = 0;
        }

        $this->comp = DB::table('sysdb.company')
            ->where('compcode','=',session('compcode'))
            ->first();
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_TEXT,
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'H' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'I' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'J' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }
    
    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 15,
            'C' => 35,
            'D' => 35,
            'E' => 15,
            'F' => 15,
            'G' => 15,
            'H' => 15,
            'I' => 18,
            'J' => 15,
        ];
    }
    
    public function view(): View
    {

        $date = $this->date;
        $firstDay = Carbon::parse($this->date)->startOfMonth()->format('Y-m-d');
        $date_asof = Carbon::parse($this->date)->format('d-m-Y');
        $suppcode_from = $this->suppcode_from;
        $suppcode_to = $this->suppcode_to;
        $grouping = $this->grouping;
        $grouping_tot = $this->grouping_tot;

        $apacthdr = DB::table('finance.apacthdr as ap')
                    ->select('ap.suppcode','ap.source','ap.trantype','ap.auditno','ap.amount','ap.postdate','ap.remarks','ap.document','su.name','su.suppgroup','sg.description','ap.unit','su.addr1','su.addr2','su.addr3','su.addr4','ap.reference')
                    ->join('material.supplier as su', function($join) {
                        $join = $join->on('su.SuppCode', '=', 'ap.suppcode');
                        $join = $join->where('su.compcode', '=', session('compcode'));
                        $join = $join->whereNotIn('su.suppgroup', ['ukmh','sa']);
                    })
                    ->leftjoin('material.suppgroup as sg', function($join) {
                        $join = $join->on('sg.suppgroup', '=', 'su.suppgroup');
                        $join = $join->where('sg.compcode', '=', session('compcode'));
                    })
                    ->where('ap.trantype','!=',['PD'])
                    ->where('ap.compcode','=',session('compcode'))
                    // ->where('ap.unit',session('unit'))
                    ->where('ap.recstatus', '=', 'POSTED')
                    ->whereDate('ap.postdate', '<=', $date)
                    ->whereBetween('su.suppcode', [$suppcode_from, $suppcode_to.'%'])
                    ->orderBy('ap.suppcode', 'ASC')
                    ->get();

        $ap_pv = DB::table('finance.apacthdr as ap1')
                    ->select('ap1.compcode as ap1_compcode','ap1.source as ap1_source','ap1.trantype as ap1_trantype','ap1.doctype as ap1_doctype','ap1.auditno as ap1_auditno','ap1.document as ap1_document','ap1.suppcode as ap1_suppcode','ap1.payto as ap1_payto','ap1.suppgroup as ap1_suppgroup','ap1.bankcode as ap1_bankcode','ap1.paymode as ap1_paymode','ap1.cheqno as ap1_cheqno','ap1.cheqdate as ap1_cheqdate','ap1.actdate as ap1_actdate','ap1.recdate as ap1_recdate','ap1.category as ap1_category','ap1.amount as ap1_amount','ap1.outamount as ap1_outamount','ap1.remarks as ap1_remarks','ap1.postflag as ap1_postflag','ap1.doctorflag as ap1_doctorflag','ap1.stat as ap1_stat','ap1.entryuser as ap1_entryuser','ap1.entrytime as ap1_entrytime','ap1.upduser as ap1_upduser','ap1.upddate as ap1_upddate','ap1.conversion as ap1_conversion','ap1.srcfrom as ap1_srcfrom','ap1.srcto as ap1_srcto','ap1.deptcode as ap1_deptcode','ap1.reconflg as ap1_reconflg','ap1.effectdatefr as ap1_effectdatefr','ap1.effectdateto as ap1_effectdateto','ap1.frequency as ap1_frequency','ap1.refsource as ap1_refsource','ap1.reftrantype as ap1_reftrantype','ap1.refauditno as ap1_refauditno','ap1.pvno as ap1_pvno','ap1.entrydate as ap1_entrydate','ap1.recstatus as ap1_recstatus','ap1.adduser as ap1_adduser','ap1.adddate as ap1_adddate','ap1.reference as ap1_reference','ap1.TaxClaimable as ap1_TaxClaimable','ap1.unit as ap1_unit','ap1.allocdate as ap1_allocdate','ap1.postuser as ap1_postuser','ap1.postdate as ap1_postdate','ap1.unallocated as ap1_unallocated','ap1.requestby as ap1_requestby','ap1.requestdate as ap1_requestdate','ap1.request_remark as ap1_request_remark','ap1.supportby as ap1_supportby','ap1.supportdate as ap1_supportdate','ap1.support_remark as ap1_support_remark','ap1.verifiedby as ap1_verifiedby','ap1.verifieddate as ap1_verifieddate','ap1.verified_remark as ap1_verified_remark','ap1.approvedby as ap1_approvedby','ap1.approveddate as ap1_approveddate','ap1.approved_remark as ap1_approved_remark','ap1.cancelby as ap1_cancelby','ap1.canceldate as ap1_canceldate','ap1.cancelled_remark as ap1_cancelled_remark','ap1.bankaccno as ap1_bankaccno','ap1.commamt as ap1_commamt','ap1.totBankinAmt as ap1_totBankinAmt','ap2.compcode as ap2_compcode','ap2.source as ap2_source','ap2.trantype as ap2_trantype','ap2.doctype as ap2_doctype','ap2.auditno as ap2_auditno','ap2.document as ap2_document','ap2.suppcode as ap2_suppcode','ap2.payto as ap2_payto','ap2.suppgroup as ap2_suppgroup','ap2.bankcode as ap2_bankcode','ap2.paymode as ap2_paymode','ap2.cheqno as ap2_cheqno','ap2.cheqdate as ap2_cheqdate','ap2.actdate as ap2_actdate','ap2.recdate as ap2_recdate','ap2.category as ap2_category','ap2.amount as ap2_amount','ap2.outamount as ap2_outamount','ap2.remarks as ap2_remarks','ap2.postflag as ap2_postflag','ap2.doctorflag as ap2_doctorflag','ap2.stat as ap2_stat','ap2.entryuser as ap2_entryuser','ap2.entrytime as ap2_entrytime','ap2.upduser as ap2_upduser','ap2.upddate as ap2_upddate','ap2.conversion as ap2_conversion','ap2.srcfrom as ap2_srcfrom','ap2.srcto as ap2_srcto','ap2.deptcode as ap2_deptcode','ap2.reconflg as ap2_reconflg','ap2.effectdatefr as ap2_effectdatefr','ap2.effectdateto as ap2_effectdateto','ap2.frequency as ap2_frequency','ap2.refsource as ap2_refsource','ap2.reftrantype as ap2_reftrantype','ap2.refauditno as ap2_refauditno','ap2.pvno as ap2_pvno','ap2.entrydate as ap2_entrydate','ap2.recstatus as ap2_recstatus','ap2.adduser as ap2_adduser','ap2.adddate as ap2_adddate','ap2.reference as ap2_reference','ap2.TaxClaimable as ap2_TaxClaimable','ap2.unit as ap2_unit','ap2.allocdate as ap2_allocdate','ap2.postuser as ap2_postuser','ap2.postdate as ap2_postdate','ap2.unallocated as ap2_unallocated','ap2.requestby as ap2_requestby','ap2.requestdate as ap2_requestdate','ap2.request_remark as ap2_request_remark','ap2.supportby as ap2_supportby','ap2.supportdate as ap2_supportdate','ap2.support_remark as ap2_support_remark','ap2.verifiedby as ap2_verifiedby','ap2.verifieddate as ap2_verifieddate','ap2.verified_remark as ap2_verified_remark','ap2.approvedby as ap2_approvedby','ap2.approveddate as ap2_approveddate','ap2.approved_remark as ap2_approved_remark','ap2.cancelby as ap2_cancelby','ap2.canceldate as ap2_canceldate','ap2.cancelled_remark as ap2_cancelled_remark','ap2.bankaccno as ap2_bankaccno','ap2.commamt as ap2_commamt','ap2.totBankinAmt as ap2_totBankinAmt','al.docsource as aldocsource','al.doctrantype as aldoctrantype','al.docauditno as aldocauditno','al.refsource as alrefsource','al.reftrantype as alreftrantype','al.refauditno as alrefauditno')
                    ->leftjoin('finance.apalloc as al', function($join) {
                        $join = $join->on('al.docsource', '=', 'ap1.source')
                                     ->on('al.doctrantype', '=', 'ap1.trantype')
                                     ->on('al.docauditno', '=', 'ap1.auditno')
                                     ->where('al.compcode', '=', session('compcode'));
                    })
                    ->leftjoin('finance.apacthdr as ap2', function($join) {
                        $join = $join->on('ap2.source', '=', 'al.refsource')
                                     ->on('ap2.trantype', '=', 'al.reftrantype')
                                     ->on('ap2.auditno', '=', 'al.refauditno')
                                     ->where('ap2.compcode', '=', session('compcode'));
                    })
                    ->where('ap1.compcode','=',session('compcode'))
                    ->where('ap1.source','AP')
                    ->where('ap1.trantype','PV')
                    ->whereBetween('ap1.payto', [$suppcode_from, $suppcode_to.'%'])
                    ->where('ap1.recstatus','APPROVED')
                    ->whereDate('ap1.postdate', '<=', $date)
                    ->whereDate('ap1.postdate', '>=', $firstDay)
                    ->get();

        $ap_pv_main = $ap_pv->unique('ap1_auditno');


        // dd($ap_pv_main);
        // dd($ap_pv);

        // dd($this->getQueries($ap_pv));

        $array_report = [];

        foreach ($apacthdr as $key => $value){
            $value->newamt = 0;

            $hdr_amount = $value->amount;
            
            // to calculate interval (days)
            $datetime1 = new DateTime($date);
            $datetime2 = new DateTime($value->postdate);
            
            $interval = $datetime1->diff($datetime2);
            $days = $interval->format('%a');
            $value->group = $this->assign_grouping($grouping,$days);
            $value->days = $days;
            $value->auditno_ = $value->source.'-'.$value->trantype.'-'.str_pad($value->auditno, 7, "0", STR_PAD_LEFT);;
            
            $alloc_sum = DB::table('finance.apalloc')
                    ->where('compcode', '=', session('compcode'))
                    ->where('suppcode', '=', $value->suppcode)
                    ->where('refsource', '=', $value->source)
                    ->where('reftrantype', '=', $value->trantype)
                    ->where('refauditno', '=', $value->auditno)
                    ->where('recstatus', '=', "POSTED")
                    ->whereDate('allocdate', '<=', $date)
                    ->sum('allocamount');

            $alloc_sum2 = DB::table('finance.apalloc')
                    ->where('compcode', '=', session('compcode'))
                    ->where('suppcode', '=', $value->suppcode)
                    ->where('docsource', '=', $value->source)
                    ->where('doctrantype', '=', $value->trantype)
                    ->where('docauditno', '=', $value->auditno)
                    ->where('recstatus', '=', "POSTED")
                    ->whereDate('allocdate', '<=', $date)
                    ->sum('allocamount');

            $newamt = $hdr_amount - $alloc_sum - $alloc_sum2;

            if(in_array($value->trantype, ['CN','PV'])){
                $newamt = $newamt * -1;
            }

            if(round($newamt,2) != 0.00){
                $value->newamt = $newamt;
                array_push($array_report, $value);
            }

            $grouping_tot[$value->group] = $grouping_tot[$value->group] + $newamt;
        }

        // dd($grouping);
        $title = "STATEMENT LISTING";
        $suppcode = collect($array_report)->unique('suppcode');

        $date_at = Carbon::createFromFormat('Y-m-d',$this->date)->format('d-m-Y');
        $datenow = Carbon::now("Asia/Kuala_Lumpur")->format('d-m-Y');
        
        $company = DB::table('sysdb.company')
                    ->where('compcode', '=', session('compcode'))
                    ->first();

        // dd($array_report);

        return view('finance.AP.APAgeingDtl_Report.APAgeingDtl_Report_excel_stmt',compact('suppcode', 'array_report','ap_pv','ap_pv_main','grouping','grouping_tot','title','company','date_asof','datenow'));
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {        
                $event->sheet->getPageSetup()->setPaperSize(9);//A4
                
                $event->sheet->getHeaderFooter()->setOddHeader('&C'.$this->comp->name."\nAP AGEING DETAILS"."\n"
                .sprintf('FROM DATE %s',Carbon::parse($this->date)->format('d-m-Y'))."\n"
                .sprintf('FROM %s TO %s',$this->suppcode_from, $this->suppcode_to)
                .'&L'
                .'PRINTED BY : '.session('username')
                ."\nPAGE : &P/&N"
                .'&R'.'PRINTED DATE : '.Carbon::now("Asia/Kuala_Lumpur")->format('d-m-Y')
                ."\n".'PRINTED TIME : '.Carbon::now("Asia/Kuala_Lumpur")->format('H:i'));
                
                $event->sheet->getPageMargins()->setTop(1);
                
                $event->sheet->getPageSetup()->setRowsToRepeatAtTop([1,1]);
                $event->sheet->getStyle('A:H')->getAlignment()->setWrapText(true);
                $event->sheet->getPageSetup()->setFitToWidth(1);
                $event->sheet->getPageSetup()->setFitToHeight(0);
            },
        ];
    }

    public function calc_bal($obj){
        $balance = 0;
        foreach ($obj->get() as $key => $value){
            
            switch ($value->trantype) {
                 case 'IN': //dr
                    $balance = $balance + floatval($value->amount);
                    break;
                case 'DN': //dr
                    $balance = $balance + floatval($value->amount);
                    break;
                case 'CN': //cr
                    $balance = $balance - floatval($value->amount);
                    break;
                case 'PV': //cr
                    $balance = $balance - floatval($value->amount);
                    break;
                case 'PD': //cr
                    $balance = $balance - floatval($value->amount);
                    break;
                default:
                    // code...
                    break;
            }
        }

        return $balance;
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

    public static function getQueries($builder){
        $addSlashes = str_replace('?', "'?'", $builder->toSql());
        return vsprintf(str_replace('?', '%s', $addSlashes), $builder->getBindings());
    }
}
