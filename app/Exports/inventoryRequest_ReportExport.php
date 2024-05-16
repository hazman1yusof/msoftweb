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

class inventoryRequest_ReportExport implements FromView, WithEvents, WithColumnWidths
{
    
    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function __construct($datefr,$dateto)
    {
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
            'D' => 15,
            'E' => 40,
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
        $datefr = Carbon::parse($this->datefr)->format('Y-m-d');
        $dateto = Carbon::parse($this->dateto)->format('Y-m-d');
      
        $ivrequest = DB::table('material.ivreqhd as h')
                ->select('h.idno', 'h.compcode', 'h.recno as h_recno', 'h.reqdt', 'h.ivreqno', 'h.reqdept', 'h.reqtodept', 'h.recstatus', 'd.recno as d_recno','d.itemcode', 'd.uomcode', 'd.pouom', 'd.qohconfirm', 'd.qtyrequest', 'd.qtybalance', 'd.qtytxn', 'd.netprice', 'd.ivreqno', 'd.reqdept', 'p.description','s.maxqty', 's.qtyonhand')
                ->join('material.ivreqdt as d', function($join){
                    $join = $join->on('d.recno', '=', 'h.recno')
                                ->where('d.compcode', '=', session('compcode'))
                                ->where('d.unit', '=', session('unit'))
                                ->where('d.recstatus', '!=', 'DELETE');
                })
                ->join('material.product as p', function($join){
                    $join = $join->on('p.itemcode', '=', 'd.itemcode')
                                ->on('p.uomcode', '=', 'd.uomcode')
                                ->where('p.compcode', '=', session('compcode'))
                                ->where('p.unit', '=', session('unit'))
                                ->where('p.recstatus', '=', 'ACTIVE');
                })
                ->join('material.stockloc as s', function($join){
                    $join = $join->on('s.itemcode', '=', 'p.itemcode')
                                ->on('s.uomcode', '=', 'p.uomcode')
                                ->on('s.deptcode', '=', 'd.reqdept')
                                ->where('s.compcode', '=', session('compcode'))
                                ->where('s.year', '=', Carbon::now("Asia/Kuala_Lumpur")->format('Y'))
                                ->where('s.unit', '=', session('unit'));
                })
                ->where('h.compcode',session('compcode'))
                ->where('h.unit',session('unit'))
                ->whereBetween('h.reqdt', [$datefr, $dateto])
                ->orderBy('h.reqdt', 'ASC')
                ->get();
        // dd($ivrequest);
        return view('material.inventoryRequest_Report.inventoryRequest_Report_excel',compact('ivrequest'));
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getPageSetup()->setPaperSize(9);//A4
                
                $event->sheet->getHeaderFooter()->setOddHeader('&C'.$this->comp->name."\nINVENTORY REQUEST REPORT"."\n"
                .sprintf('FROM DATE %s TO DATE %s',Carbon::parse($this->datefr)->format('d-m-Y'), Carbon::parse($this->dateto)->format('d-m-Y'))."\n"
                .'&L'
                .'PRINTED BY : '.session('username')
                ."\nPAGE : &P/&N"
                .'&R'.'PRINTED DATE : '.Carbon::now("Asia/Kuala_Lumpur")->format('d-m-Y')
                ."\n".'PRINTED TIME : '.Carbon::now("Asia/Kuala_Lumpur")->format('H:i'));
                
                $event->sheet->getPageMargins()->setTop(1);
                
                $event->sheet->getPageSetup()->setRowsToRepeatAtTop([1,1]);
                $event->sheet->getStyle('A:N')->getAlignment()->setWrapText(true);
                $event->sheet->getPageSetup()->setFitToWidth(1);
                $event->sheet->getPageSetup()->setFitToHeight(0);
            },
        ];
    }
}
