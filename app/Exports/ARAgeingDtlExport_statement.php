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
// use Illuminate\Contracts\Queue\ShouldQueue;
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

class ARAgeingDtlExport_statement implements FromView, WithEvents, WithColumnWidths, WithColumnFormatting
{
    
    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function __construct($idno)
    {

        $job_queue = DB::table('sysdb.job_queue')
                        ->where('idno',$idno)
                        ->first();
        
        $this->job_id = $idno;
        $this->process = $job_queue->process;
        $this->filename = $job_queue->filename;
        $this->type = $job_queue->type;
        $this->date = Carbon::parse($job_queue->date)->format('Y-m-d');
        $this->debtortype = $job_queue->debtortype;
        $this->debtorcode_from = $job_queue->debtorcode_from;
        $this->debtorcode_to = $job_queue->debtorcode_to;

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
        if($this->type == 'detail'){
            return [
                'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
                'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
                'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
                'H' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
                'I' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
                'J' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
                'K' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
                'L' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            ];
        }else if($this->type == 'summary'){
            return [
                'C' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
                'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
                'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
                'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
                'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
                'H' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
                'I' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
                'J' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
                'K' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
                'L' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            ];
        }
    }
    
    public function columnWidths(): array
    {
        return [
            'A' => 22,
            'B' => 65,
            'C' => 15,
            'D' => 15,
            'E' => 15,
            'F' => 15,
            'G' => 15,
            'H' => 15,
            'I' => 15,
            'J' => 15,
            'K' => 15,
            'L' => 15,
            'M' => 15,
            'N' => 15,
        ];
    }
    
    public function view(): View
    {

        $type = $this->type;
        $date = $this->date;
        $firstDay = Carbon::parse($this->date)->startOfMonth()->format('Y-m-d');
        $date_asof = Carbon::parse($this->date)->format('d-m-Y');
        $datenow = Carbon::now("Asia/Kuala_Lumpur")->format('d-m-Y');
        $debtortype = $this->debtortype;
        $debtorcode_from = strtoupper($this->debtorcode_from);
        $debtorcode_to = strtoupper($this->debtorcode_to);
        $grouping = $this->grouping;
        $grouping_tot = $this->grouping_tot;

        $array_report = DB::table('debtor.ARAgeing')
                            ->where('job_id',$this->job_id)
                            ->orderBy('posteddate', 'asc')
                            ->get();

        $debtormast = collect($array_report)->unique('debtorcode');

        $comp_name = $this->comp->name;
        $date_at = Carbon::createFromFormat('Y-m-d',$this->date)->format('d-m-Y');

        $db_rc = DB::table('debtor.dbacthdr as db1')
                    ->select('db1.source as db1_source','db1.trantype as db1_trantype','db1.auditno as db1_auditno','db1.lineno_ as db1_lineno_','db1.amount as db1_amount','db1.outamount as db1_outamount','db1.recstatus as db1_recstatus','db1.reference as db1_reference','db1.recptno as db1_recptno','db1.paymode as db1_paymode','db1.tillcode as db1_tillcode','db1.tillno as db1_tillno','db1.debtortype as db1_debtortype','db1.debtorcode as db1_debtorcode','db1.payercode as db1_payercode','db1.billdebtor as db1_billdebtor','db1.remark as db1_remark','db1.mrn as db1_mrn','db1.episno as db1_episno','db1.epistype as db1_epistype','db1.cbflag as db1_cbflag','db1.conversion as db1_conversion','db1.payername as db1_payername','db1.currency as db1_currency','db1.rate as db1_rate','db1.unit as db1_unit','db1.invno as db1_invno','db1.orderno as db1_orderno','db1.ponum as db1_ponum','db1.podate as db1_podate','db1.termdays as db1_termdays','db1.termmode as db1_termmode','db1.deptcode as db1_deptcode','db1.posteddate as db1_posteddate','db2.source as db2_source','db2.trantype as db2_trantype','db2.auditno as db2_auditno','db2.lineno_ as db2_lineno_','db2.amount as db2_amount','db2.outamount as db2_outamount','db2.recstatus as db2_recstatus','db2.reference as db2_reference','db2.recptno as db2_recptno','db2.paymode as db2_paymode','db2.tillcode as db2_tillcode','db2.tillno as db2_tillno','db2.debtortype as db2_debtortype','db2.debtorcode as db2_debtorcode','db2.payercode as db2_payercode','db2.billdebtor as db2_billdebtor','db2.remark as db2_remark','db2.mrn as db2_mrn','db2.episno as db2_episno','db2.epistype as db2_epistype','db2.cbflag as db2_cbflag','db2.conversion as db2_conversion','db2.payername as db2_payername','db2.currency as db2_currency','db2.rate as db2_rate','db2.unit as db2_unit','db2.invno as db2_invno','db2.orderno as db2_orderno','db2.ponum as db2_ponum','db2.podate as db2_podate','db2.termdays as db2_termdays','db2.termmode as db2_termmode','db2.deptcode as db2_deptcode','db2.posteddate as db2_posteddate','da.docsource as da_docsource','da.doctrantype as da_doctrantype','da.docauditno as da_docauditno','da.refsource as da_refsource','da.reftrantype as da_reftrantype','da.refauditno as da_refauditno','da.amount as da_allocamount','pm.Name as pm_name')
                    ->join('debtor.dballoc as da', function($join) {
                        $join = $join->on('da.docsource', '=', 'db1.source')
                                     ->on('da.doctrantype', '=', 'db1.trantype')
                                     ->on('da.docauditno', '=', 'db1.auditno')
                                     ->where('da.compcode', '=', session('compcode'));
                    })
                    ->join('debtor.dbacthdr as db2', function($join) {
                        $join = $join->on('db2.source', '=', 'da.refsource')
                                     ->on('db2.trantype', '=', 'da.reftrantype')
                                     ->on('db2.auditno', '=', 'da.refauditno')
                                     ->where('db2.compcode', '=', session('compcode'));
                    })
                    ->leftJoin('hisdb.pat_mast as pm', function($join){
                        $join = $join->on('pm.NewMrn', '=', 'db2.mrn')
                                     // ->where('pm.NewMrn', '<>', '')
                                     ->where('pm.compcode', '=', session('compcode'));
                    })
                    ->where('db1.compcode','=',session('compcode'))
                    ->where('db1.source','PB')
                    ->where('db1.trantype','RC')
                    ->whereBetween('db1.debtorcode', [$debtorcode_from,$debtorcode_to.'%'])
                    ->where('db1.recstatus','POSTED')
                    ->whereDate('db1.posteddate', '<=', $date)
                    ->whereDate('db1.posteddate', '>=', $firstDay)
                    ->get();

        $db_rc_main = $db_rc->unique('db1_auditno');

        $title = "STATEMENT LISTING";
        
        $company = DB::table('sysdb.company')
                    ->where('compcode', '=', session('compcode'))
                    ->first();

        return view('finance.AR.arenquiry.ARStatementListingExport_excel_statement', compact('debtormast','db_rc_main','db_rc','array_report','grouping','grouping_tot','title','company','date_asof','datenow'));

    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // foreach ($this->break_loop as $value) {
                //     $event->sheet->setBreak('A'.$value, \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);
                // }
                
                $event->sheet->getPageSetup()->setPaperSize(9);//A4
                
                $event->sheet->getHeaderFooter()->setOddHeader('&C'.$this->comp->name."\nAR AGEING DETAILS"."\n"
                .sprintf('DATE %s',Carbon::parse($this->date)->format('d-m-Y'))
                .sprintf('FROM %s TO %s',$this->debtorcode_from, $this->debtorcode_to)
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
}
