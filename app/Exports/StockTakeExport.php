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

class StockTakeExport implements FromView, WithEvents, WithColumnWidths
{
    
    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function __construct($recno)
    {
        $this->recno = $recno;
        
        $this->comp = DB::table('sysdb.company')
            ->where('compcode','=',session('compcode'))
            ->first();

        
        $this->phd = DB::table('material.phycnthd')
            ->where('compcode','=',session('compcode'))
            ->where('recno','=',$recno)
            ->first();
    }
    
    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 15,
            'C' => 50,
            'D' => 15,
            'E' => 15,
            'F' => 15,
            'G' => 15,
            'H' => 15,
            'I' => 15,
            'J' => 15,
            'K' => 15,
        ];
    }
    
    public function view(): View
    {
        $recno = $this->recno;

        $dept = DB::table('sysdb.department')
                    ->where('compcode',session('compcode'))
                    ->where('deptcode',$this->phd->srcdept)
                    ->first();

        $unit = $dept->sector;

        $phycntdt = DB::table('material.phycntdt AS pdt')
            ->select('pdt.idno','pdt.compcode','pdt.srcdept','pdt.phycntdate','pdt.phycnttime','pdt.lineno_','pdt.itemcode','pdt.uomcode','pdt.adduser','pdt.adddate','pdt.upduser','pdt.upddate','pdt.unitcost','pdt.phyqty','pdt.thyqty','pdt.recno','pdt.expdate','pdt.updtime','pdt.stktime','pdt.frzdate','pdt.frztime','pdt.dspqty','pdt.batchno','p.description')
            ->leftJoin('material.product as p', function($join){
                        $join = $join->on('p.itemcode', '=', 'pdt.itemcode')
                                     ->on('p.uomcode', '=', 'pdt.uomcode')
                                     ->where('p.unit','=',session('unit'))
                                     ->where('p.compcode','=',session('compcode'));
                    })
            ->where('pdt.compcode','=',session('compcode'))
            ->where('pdt.recno','=',$recno)
            ->get();
        
        return view('material.stockCount.stockCount_excel',compact('phycntdt'));
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getPageSetup()->setPaperSize(9);//A4
                
                $event->sheet->getHeaderFooter()->setOddHeader('&C'.$this->comp->name."\nSTOCK COUNT"."\n".sprintf('FROM ITEM %s TO ITEM %s',$this->phd->itemfrom, $this->phd->itemto).'&L'.'PRINTED BY : '.session('username')."\nPAGE : &P/&N".'&R'.'PRINTED DATE : '.Carbon::now("Asia/Kuala_Lumpur")->format('d-m-Y')."\n".'PRINTED TIME : '.Carbon::now("Asia/Kuala_Lumpur")->format('H:i'));
                
                $event->sheet->getPageMargins()->setTop(1);
                
                $event->sheet->getPageSetup()->setRowsToRepeatAtTop([2,2]);
                $event->sheet->getStyle('A:H')->getAlignment()->setWrapText(true);
                $event->sheet->getPageSetup()->setFitToWidth(1);
                $event->sheet->getPageSetup()->setFitToHeight(0);
            },
        ];
    }
    
}
