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
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use DateTime;
use Carbon\Carbon;
use stdClass;

class check_do_stockconsign implements FromView, WithEvents, WithColumnWidths, WithColumnFormatting, ShouldAutoSize, WithTitle
{
    
    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function __construct($deldept,$delorddt,$type,$_20010042,$_20010044)
    {
        $this->type = $type;
        $this->deldept = $deldept;
        $this->delorddt = $delorddt;
        $this->_20010042 = $_20010042;
        $this->_20010044 = $_20010044;
    }

    public function title(): string
    {
        if($this->type == 'main'){
            return 'MAIN';
        }else{
            return $this->deldept;
        }
    }

    public function columnFormats(): array
    {
        if($this->type == 'main'){
            return [
                'B' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
                'C' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            ];
        }else{
            return [
                'I' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            ];
        }
    }
    
    public function columnWidths(): array
    {
        if($this->type == 'main'){
            return [
                'A' => 15,
                'B' => 40,
                'C' => 40,
                'D' => 15,
                'E' => 15,
                'F' => 15,
                'G' => 15,
                'H' => 15,
                'I' => 15,
            ];
        }else{
            return [
                'A' => 15,
                'B' => 15,
                'C' => 15,
                'D' => 15,
                'E' => 15,
                'F' => 15,
                'G' => 15,
                'H' => 15,
                'I' => 15,
            ];
        }
    }
    
    public function view(): View{

        $type = $this->type;
        $deldept = $this->deldept;
        $delorddt = $this->delorddt;
        $_20010042 = $this->_20010042;
        $_20010044 = $this->_20010044;

        if($type == 'main'){
            return view('material.deliveryOrder.check_do_stockconsign_1',compact('delorddt','_20010042','_20010044'));
        }

        $excel_data = [];
        foreach ($delorddt as $obj) {
            if($deldept == $obj->deldept){
                array_push($excel_data,$obj);
            }
        }

        return view('material.deliveryOrder.check_do_stockconsign',compact('excel_data','_20010042','_20010044'));
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {        
                $event->sheet->getPageSetup()->setPaperSize(9);//A4
                
                $event->sheet->getHeaderFooter()->setOddHeader("&C\ncheck_do_stockconsign"
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

    public function getQueries($builder){
        $addSlashes = str_replace('?', "'?'", $builder->toSql());
        return vsprintf(str_replace('?', '%s', $addSlashes), $builder->getBindings());
    }
}
