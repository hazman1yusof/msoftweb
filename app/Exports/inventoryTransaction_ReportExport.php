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

class inventoryTransaction_ReportExport implements FromView, WithEvents, WithColumnWidths
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
            'B' => 10,
            'C' => 10,
            'D' => 15,
            'E' => 15,
            'F' => 35,
            'G' => 15,
            'H' => 15,
            'I' => 15,
            'J' => 15,
            'K' => 10,
            'L' => 10,
            'M' => 10,
            'N' => 15,
            'O' => 15,
            'P' => 15,
        ];
    }
    
    public function view(): View
    {
        $datefr = Carbon::parse($this->datefr)->format('Y-m-d');
        $dateto = Carbon::parse($this->dateto)->format('Y-m-d');
      
        $ivtxn = DB::table('material.ivtmphd as h')
                    ->select('h.idno', 'h.compcode', 'h.recno', 'h.trandate', 'h.docno', 'h.txndept', 'h.sndrcv', 'h.sndrcvtype', 'h.recstatus', 'd.recno', 'd.itemcode', 'd.uomcode', 'd.qtyonhand', 'd.uomcoderecv', 'd.qtyonhandrecv', 'd.txnqty', 'd.qtyrequest', 'd.netprice', 'd.expdate', 'd.batchno', 'd.amount', 'p.description')
                    ->join('material.ivtmpdt as d', function($join){
                        $join = $join->on('d.recno', '=', 'h.recno')
                                    ->where('d.compcode', '=', session('compcode'))
                                    ->where('d.unit', '=', session('unit'))
                                    ->where('d.recstatus', '=', 'OPEN');
                    })
                    ->join('material.product as p', function($join){
                        $join = $join->on('p.itemcode', '=', 'd.itemcode')
                                    ->on('p.uomcode', '=', 'd.uomcode')
                                    ->where('p.compcode', '=', session('compcode'))
                                    ->where('p.unit', '=', session('unit'))
                                    ->where('p.recstatus', '=', 'ACTIVE');
                    })
                    ->where('h.compcode',session('compcode'))
                    ->where('h.unit',session('unit'))
                    ->where('h.recstatus', '=', 'OPEN')
                    ->whereBetween('h.trandate', [$datefr, $dateto])
                    ->orderBy('h.trandate', 'ASC')
                    ->get();
                    // dd($ivtxn);

        return view('material.inventoryTransaction_Report.inventoryTransaction_Report_excel',compact('ivtxn'));
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getPageSetup()->setPaperSize(9);//A4
                
                $event->sheet->getHeaderFooter()->setOddHeader('&C'.$this->comp->name."\nSTOCK IN TRANSIT REPORT"."\n"
                .sprintf('FROM DATE %s TO DATE %s',Carbon::parse($this->datefr)->format('d-m-Y'), Carbon::parse($this->dateto)->format('d-m-Y'))."\n"
                .'&L'
                .'PRINTED BY : '.session('username')
                ."\nPAGE : &P/&N"
                .'&R'.'PRINTED DATE : '.Carbon::now("Asia/Kuala_Lumpur")->format('d-m-Y')
                ."\n".'PRINTED TIME : '.Carbon::now("Asia/Kuala_Lumpur")->format('H:i'));
                
                $event->sheet->getPageMargins()->setTop(1);
                
                $event->sheet->getPageSetup()->setRowsToRepeatAtTop([1,1]);
                $event->sheet->getStyle('A:P')->getAlignment()->setWrapText(true);
                $event->sheet->getPageSetup()->setFitToWidth(1);
                $event->sheet->getPageSetup()->setFitToHeight(0);
            },
        ];
    }
}
