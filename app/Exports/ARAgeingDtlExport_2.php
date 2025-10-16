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

class ARAgeingDtlExport_2 implements FromView, WithEvents, WithColumnWidths, WithColumnFormatting
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

        $this->groupOne = $job_queue->groupOne;
        $this->groupTwo = $job_queue->groupTwo;
        $this->groupThree = $job_queue->groupThree;
        $this->groupFour = $job_queue->groupFour;
        $this->groupFive = $job_queue->groupFive;
        $this->groupSix = $job_queue->groupSix;
        $this->groupby = $job_queue->groupby;

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
        $debtortype = $this->debtortype;
        $debtorcode_from = strtoupper($this->debtorcode_from);
        $debtorcode_to = strtoupper($this->debtorcode_to);
        $grouping = $this->grouping;
        $groupby = $this->groupby;

        $array_report = DB::table('debtor.ARAgeing')
                            ->where('job_id',$this->job_id)
                            ->orderBy('posteddate', 'asc')
                            ->get();

        if($groupby == 'debtortype'){
            $debtortype = $array_report->unique('debtortycode');
            $debtorcode = $array_report->unique('debtorcode');

        }else if($groupby == 'unit'){
            $debtortype = $array_report->unique('unit');
            $debtorcode = $array_report->unique(function ($item) {
                return $item->debtorcode . '-' . $item->unit;
            })->values();

        }

        $comp_name = $this->comp->name;
        $date_at = Carbon::createFromFormat('Y-m-d',$this->date)->format('d-m-Y');

        // dd($array_report);

        if($groupby == 'debtortype'){
            if($this->type == 'detail'){
                return view('finance.AR.ARAgeingDtl_Report.ARAgeingDtl_Report_excel',compact('debtortype','debtorcode','array_report','grouping','date','date_at','comp_name','type','groupby'));
            }else if($this->type == 'summary'){
                return view('finance.AR.ARAgeingDtl_Report.ARAgeingDtl_Report_excel_summ',compact('debtortype','debtorcode','array_report','grouping','date','date_at','comp_name','type','groupby'));
            }
        }else if($groupby == 'unit'){
            if($this->type == 'detail'){
                return view('finance.AR.ARAgeingDtl_Report.ARAgeingDtl_Report_excel_unit',compact('debtortype','debtorcode','array_report','grouping','date','date_at','comp_name','type','groupby'));
            }else if($this->type == 'summary'){
                return view('finance.AR.ARAgeingDtl_Report.ARAgeingDtl_Report_excel_unit_summ',compact('debtortype','debtorcode','array_report','grouping','date','date_at','comp_name','type','groupby'));
            }
        }

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
