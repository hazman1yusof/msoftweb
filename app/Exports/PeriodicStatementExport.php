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

class PeriodicStatementExport implements FromView, WithEvents, WithColumnWidths, WithColumnFormatting
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
        $this->suppcode_from = $job_queue->debtorcode_from;
        $this->suppcode_to = $job_queue->debtorcode_to;
        $this->fromdate = $job_queue->date;
        $this->todate = $job_queue->date_to;

        $this->comp = DB::table('sysdb.company')
            ->where('compcode','=',session('compcode'))
            ->first();
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_NUMBER,
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
            'D' => 55,
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

        $monthFrom = Carbon::parse($this->fromdate)->format('F Y');
        $monthTo = Carbon::parse($this->todate)->format('F Y');
        $datenow = Carbon::now("Asia/Kuala_Lumpur")->format('d-m-Y');

        $array_report = DB::table('jobs.periodicStatement')
                            ->where('job_id',$this->job_id);

        if($array_report->exists()){
            $array_report = $array_report->get();
            $array_first = $array_report->first();

            $positive = $array_first->amount_positive;
            $negative = $array_first->amount_negative;
            $openbalance = $positive - $negative;

        }else{
            $array_report = $array_report->get();
            $openbalance = 0;

        }

        $suppcode = collect($array_report)->unique('suppcode');
        
        $company = DB::table('sysdb.company')
                    ->where('compcode', '=', session('compcode'))
                    ->first();

        // dd($array_report);

        return view('finance.AR.statement.PeriodicStatement',compact('suppcode', 'array_report','openbalance','company','datenow','monthFrom','monthTo'));

    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // foreach ($this->break_loop as $value) {
                //     $event->sheet->setBreak('A'.$value, \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);
                // }
                
                $event->sheet->getPageSetup()->setPaperSize(9);//A4
                
                // $event->sheet->getHeaderFooter()->setOddHeader('&C'.$this->comp->name."\nAR AGEING DETAILS"."\n"
                // .sprintf('DATE %s',Carbon::parse($this->date)->format('d-m-Y'))
                // .sprintf('FROM %s TO %s',$this->debtorcode_from, $this->debtorcode_to)
                // .'&L'
                // .'PRINTED BY : '.session('username')
                // ."\nPAGE : &P/&N"
                // .'&R'.'PRINTED DATE : '.Carbon::now("Asia/Kuala_Lumpur")->format('d-m-Y')
                // ."\n".'PRINTED TIME : '.Carbon::now("Asia/Kuala_Lumpur")->format('H:i'));
                
                $event->sheet->getPageMargins()->setTop(1);
                
                $event->sheet->getPageSetup()->setRowsToRepeatAtTop([1,1]);
                $event->sheet->getStyle('A:H')->getAlignment()->setWrapText(true);
                $event->sheet->getPageSetup()->setFitToWidth(1);
                $event->sheet->getPageSetup()->setFitToHeight(0);
            },
        ];
    }
}
