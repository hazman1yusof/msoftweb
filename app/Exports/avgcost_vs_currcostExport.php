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

class avgcost_vs_currcostExport implements FromView, WithEvents, WithColumnWidths
{
    
    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function __construct($item_from,$item_to)
    {
        $this->item_from = $item_from;
        $this->item_to = $item_to;

        $this->comp = DB::table('sysdb.company')
            ->where('compcode','=',session('compcode'))
            ->first();
    }
    
    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 50,
            'C' => 15,
            'D' => 15,
            'E' => 15,
            'F' => 15,
            'G' => 15,
            'H' => 15,
            'I' => 15,
            'J' => 50,
        ];
    }
    
    public function view(): View
    {
        $item_from = $this->item_from;
        if(empty($item_from)){
            $item_from = '%';
        }
        $item_to = $this->item_to;
      
        $product = DB::table('material.product as p')
                ->select('p.idno','p.compcode','p.itemcode','p.recstatus', 'p.suppcode', 'p.unit', 'p.description', 'p.uomcode', 'p.qtyonhand', 'p.avgcost', 'p.currprice', 'p.groupcode', 'p.productcat', 'p.chgflag', 's.SuppCode', 's.Name')
                ->leftJoin('material.supplier as s', function($join){
                    $join = $join->on('s.SuppCode', '=', 'p.suppcode')
                                ->where('s.compcode', '=', session('compcode'));
                })
                ->where('p.compcode','=',session('compcode'))
                ->where('p.recstatus', '=', 'ACTIVE')
                ->whereBetween('p.itemcode', [$item_from, $item_to])
                ->orderBy('p.itemcode', 'ASC')
                ->get();
        
        return view('material.avgcost_vs_currcost.avgcost_vs_currcost_excel',compact('product'));
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getPageSetup()->setPaperSize(9);//A4
                
                $event->sheet->getHeaderFooter()->setOddHeader('&C'.$this->comp->name."\nAVERAGE COST VS CURRENT COST"."\n".sprintf('FROM ITEM CODE %s TO ITEM CODE %s',$this->item_from, $this->item_to).'&L'.'PRINTED BY : '.session('username')."\nPAGE : &P/&N".'&R'.'PRINTED DATE : '.Carbon::now("Asia/Kuala_Lumpur")->format('d-m-Y')."\n".'PRINTED TIME : '.Carbon::now("Asia/Kuala_Lumpur")->format('H:i'));
                
                $event->sheet->getPageMargins()->setTop(1);
                
                $event->sheet->getPageSetup()->setRowsToRepeatAtTop([1,1]);
                $event->sheet->getStyle('A:E')->getAlignment()->setWrapText(true);
                $event->sheet->getPageSetup()->setFitToWidth(1);
                $event->sheet->getPageSetup()->setFitToHeight(0);
            },
        ];
    }
}
