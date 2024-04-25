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

class ReportFormatExport implements FromView, WithEvents, WithColumnWidths
{
    
    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function __construct($rptname)
    {
        $this->rptname = $rptname;
        
        $this->comp = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();
    }
    
    public function columnWidths(): array
    {
        return [
            'A' => 8,
            'B' => 15,
            'C' => 15,
            'D' => 20,
            'E' => 10,
            'F' => 30,
            'G' => 15,
            'H' => 20,
            'I' => 20,
            'J' => 15,
        ];
    }
    
    public function view(): View
    {
        $rptname = $this->rptname;
        
        $glrpthdr = DB::table('finance.glrpthdr')
                    ->where('compcode', '=', session('compcode'))
                    ->where('rptname', '=', $rptname)
                    ->first();
        
        $glrptfmt = DB::table('finance.glrptfmt')
                    ->where('compcode', '=', session('compcode'))
                    ->where('rptname', '=', $rptname)
                    ->orderBy('lineno_', 'ASC')
                    ->get();
        
        return view('finance.GL.reportFormat.reportFormat_excel', compact('glrpthdr', 'glrptfmt'));
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event){
                $event->sheet->getPageSetup()->setPaperSize(9); // A4
                
                $event->sheet->getHeaderFooter()->setOddHeader('&C'.$this->comp->name."\nREPORT FORMAT"."\n"
                .'&L'
                .'PRINTED BY : '.session('username')
                ."\nPAGE : &P/&N"
                .'&R'.'PRINTED DATE : '.Carbon::now("Asia/Kuala_Lumpur")->format('d-m-Y')
                ."\n".'PRINTED TIME : '.Carbon::now("Asia/Kuala_Lumpur")->format('H:i'));
                
                $event->sheet->getPageMargins()->setTop(1);
                
                $event->sheet->getPageSetup()->setRowsToRepeatAtTop([1,1]);
                $event->sheet->getStyle('A:J')->getAlignment()->setWrapText(true);
                $event->sheet->getPageSetup()->setFitToWidth(1);
                $event->sheet->getPageSetup()->setFitToHeight(0);
            },
        ];
    }
    
}
