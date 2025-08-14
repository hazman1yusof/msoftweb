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

class stockExpiryExport implements FromView, WithEvents, WithColumnWidths
{
    
    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function __construct($item_from,$item_to,$datefr,$dateto)
    {
        $this->item_from = $item_from;
        $this->item_to = $item_to;
        $this->datefr = $datefr;
        $this->dateto = $dateto;

        $this->comp = DB::table('sysdb.company')
            ->where('compcode','=',session('compcode'))
            ->first();
    }
    
    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 15,
            'C' => 15,
            'D' => 50,
            'E' => 15,
            'F' => 15,
            'G' => 15,
            'H' => 15,
        ];
    }
    
    public function view(): View
    {
        $item_from = $this->item_from;
        if(empty($item_from)){
            $item_from = '%';
        }
        $item_to = $this->item_to;
        $datefr = Carbon::parse($this->datefr)->format('Y-m-d');
        $dateto = Carbon::parse($this->dateto)->format('Y-m-d');
      
        $stockexp = DB::table('material.stockexp as e')
                ->select('e.idno', 'e.compcode', 'e.deptcode', 'e.itemcode', 'e.uomcode', 'e.expdate', 'e.unit', 'p.uomcode as p_uom', 'p.description as p_desc', 'p.avgcost', 'p.currprice', 'e.balqty', 'e.batchno')
                ->join('material.product as p', function($join){
                    $join = $join->on('p.itemcode', '=', 'e.itemcode')
                                ->on('p.uomcode', '=', 'e.uomcode')
                                ->where('p.compcode', '=', session('compcode'))
                                ->where('p.unit', '=', session('unit'))
                                ->where('p.recstatus', '=', 'ACTIVE');
                })
                ->where('e.balqty','!=','0')
                ->where('e.compcode',session('compcode'))
                ->where('e.unit',session('unit'))
                ->where('e.year', '=', Carbon::now("Asia/Kuala_Lumpur")->format('Y'))
                ->whereBetween('e.itemcode',[$item_from,$item_to.'%'])
                ->whereBetween('e.expdate', [$datefr, $dateto])
                ->orderBy('e.itemcode', 'ASC')
                ->get();
        
        return view('material.stockExpiry.stockExpiry_excel',compact('stockexp'));
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getPageSetup()->setPaperSize(9);//A4
                
                $event->sheet->getHeaderFooter()->setOddHeader('&C'.$this->comp->name."\nSTOCK EXPIRY"."\n"
                .sprintf('FROM DATE %s TO DATE %s',Carbon::parse($this->datefr)->format('d-m-Y'), Carbon::parse($this->dateto)->format('d-m-Y'))."\n"
                .sprintf('FROM %s TO %s',$this->item_from, $this->item_to)
                .'&L'
                .'PRINTED BY : '.session('username')
                ."\nPAGE : &P/&N"
                .'&R'.'PRINTED DATE : '.Carbon::now("Asia/Kuala_Lumpur")->format('d-m-Y')
                ."\n".'PRINTED TIME : '.Carbon::now("Asia/Kuala_Lumpur")->format('H:i'));
                
                $event->sheet->getPageMargins()->setTop(1);
                
                $event->sheet->getPageSetup()->setRowsToRepeatAtTop([1,1]);
                $event->sheet->getStyle('A:E')->getAlignment()->setWrapText(true);
                $event->sheet->getPageSetup()->setFitToWidth(1);
                $event->sheet->getPageSetup()->setFitToHeight(0);
            },
        ];
    }
}
