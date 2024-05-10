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

class deptItemListExport implements FromView, WithEvents, WithColumnWidths
{
    
    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function __construct($dept_from,$dept_to)
    {
        $this->dept_from = $dept_from;
        $this->dept_to = $dept_to;

        $this->comp = DB::table('sysdb.company')
            ->where('compcode','=',session('compcode'))
            ->first();
    }
    
    public function columnWidths(): array
    {
        return [
            'A' => 15,
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
        $dept_from = $this->dept_from;
        if(empty($dept_from)){
            $dept_from = '%';
        }
        $dept_to = $this->dept_to;
      
        $stockloc = DB::table('material.stockloc as s')
                ->select('s.idno','s.deptcode','s.itemcode','p.description','s.uomcode','s.qtyonhand','s.stocktxntype','s.minqty','s.maxqty','s.reordlevel','s.reordqty','s.disptype', 's.recstatus')
                ->join('material.product as p', function($join){
                    $join = $join->on('p.itemcode', '=', 's.itemcode')
                                ->on('p.uomcode', '=', 's.uomcode')
                                ->where('p.compcode', '=', session('compcode'))
                                ->where('p.unit', '=', session('unit'))
                                ->where('p.recstatus', '=', 'ACTIVE');
                })
                ->where('s.compcode',session('compcode'))
                ->where('s.unit',session('unit'))
                // ->where('s.recstatus', '=', 'ACTIVE')
                ->where('s.year', '=', Carbon::now("Asia/Kuala_Lumpur"))
                ->whereBetween('s.deptcode',[$dept_from,$dept_to.'%'])
                ->orderBy('s.deptcode', 'ASC')
                ->get();
        
        return view('material.deptItemList.deptItemList_excel',compact('stockloc'));
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getPageSetup()->setPaperSize(9);//A4
                
                $event->sheet->getHeaderFooter()->setOddHeader('&C'.$this->comp->name."\nDEPARTMENTAL ITEM LIST"."\n".sprintf('FROM DEPT CODE %s TO DEPT CODE %s',$this->dept_from, $this->dept_to).'&L'.'PRINTED BY : '.session('username')."\nPAGE : &P/&N".'&R'.'PRINTED DATE : '.Carbon::now("Asia/Kuala_Lumpur")->format('d-m-Y')."\n".'PRINTED TIME : '.Carbon::now("Asia/Kuala_Lumpur")->format('H:i'));
                
                $event->sheet->getPageMargins()->setTop(1);
                
                $event->sheet->getPageSetup()->setRowsToRepeatAtTop([1,1]);
                $event->sheet->getStyle('A:E')->getAlignment()->setWrapText(true);
                $event->sheet->getPageSetup()->setFitToWidth(1);
                $event->sheet->getPageSetup()->setFitToHeight(0);
            },
        ];
    }
}
