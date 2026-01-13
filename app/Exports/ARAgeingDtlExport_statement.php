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
        return [
            'B' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            // 'C' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }
    
    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 15,
            'C' => 15,
            'D' => 15,
            'E' => 35,
            'F' => 15,
            'G' => 15,
            'H' => 15,
            'I' => 18,
            'J' => 15,
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

        $db_rc = DB::table('debtor.statement')
                            ->where('job_id',$this->job_id)
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
