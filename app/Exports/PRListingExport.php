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

class PRListingExport implements FromView, WithEvents, WithColumnWidths
{
    
    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function __construct($datefr,$dateto,$Status)
    {
        $this->datefr = $datefr;
        $this->dateto = $dateto;
        $this->Status = $Status;

        $this->comp = DB::table('sysdb.company')
            ->where('compcode','=',session('compcode'))
            ->first();
    }
    
    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 10,
            'C' => 10,
            'D' => 15,
            'E' => 15,
            'F' => 35,
            'G' => 10,
            'H' => 15,
            'I' => 35,
            'J' => 10,
            'K' => 10,
            'L' => 10,
            'M' => 15,
            'N' => 15,
            'O' => 15,
            'P' => 15,
            'Q' => 15,
            'R' => 15,
            'S' => 15,
            'T' => 15,
        ];
    }
    
    public function view(): View
    {
        $datefr = Carbon::parse($this->datefr)->format('Y-m-d');
        $dateto = Carbon::parse($this->dateto)->format('Y-m-d');
        $Status = $this->Status;
      
        if ($Status == 'ALL'){
            $PRListing = DB::table('material.purreqhd as h')
                ->select('h.idno', 'h.compcode', 'h.recno', 'h.prdept', 'h.reqdept', 'h.purreqno', 'h.purreqdt', 'h.suppcode', 's.name as supp_name', 'h.recstatus', 'h.requestby', 'h.requestdate', 'h.supportby', 'h.supportdate', 'h.verifiedby', 'h.verifieddate', 'h.approvedby', 'h.approveddate', 'h.cancelby', 'h.canceldate', 'd.compcode', 'd.recno', 'd.lineno_', 'd.pricecode', 'd.itemcode', 'p.description', 'd.uomcode', 'd.pouom', 'd.qtyrequest', 'd.qtybalance', 'd.unitprice', 'd.taxcode', 'd.perdisc', 'd.amtdisc', 'd.amtslstax as tot_gst','d.netunitprice', 'd.totamount','d.amount', 'd.recstatus', 'd.unit')                
                ->join('material.purreqdt as d', function($join){
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
                ->join('material.supplier as s', function($join){
                    $join = $join->on('s.SuppCode', '=', 'h.suppcode')
                                ->where('s.compcode', '=', session('compcode'))
                                ->where('s.recstatus', '=', 'ACTIVE');
                })
                ->where('h.compcode',session('compcode'))
                ->where('h.unit',session('unit'))
                ->where('h.recstatus', '!=', 'DELETE')
                ->whereBetween('h.purreqdt', [$datefr, $dateto])
                ->orderBy('h.purreqdt', 'ASC')
                ->get();
        //dd($PRListing);
        } else {
            $PRListing = DB::table('material.purreqhd as h')
                ->select('h.idno', 'h.compcode', 'h.recno', 'h.prdept', 'h.reqdept', 'h.purreqno', 'h.purreqdt', 'h.suppcode', 's.name as supp_name', 'h.recstatus', 'h.requestby', 'h.requestdate', 'h.supportby', 'h.supportdate', 'h.verifiedby', 'h.verifieddate', 'h.approvedby', 'h.approveddate', 'h.cancelby', 'h.canceldate', 'd.compcode', 'd.recno', 'd.lineno_', 'd.pricecode', 'd.itemcode', 'p.description', 'd.uomcode', 'd.pouom', 'd.qtyrequest', 'd.qtybalance', 'd.unitprice', 'd.taxcode', 'd.perdisc', 'd.amtdisc', 'd.amtslstax as tot_gst','d.netunitprice', 'd.totamount','d.amount', 'd.recstatus', 'd.unit')               
                ->join('material.purreqdt as d', function($join){
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
                ->join('material.supplier as s', function($join){
                    $join = $join->on('s.SuppCode', '=', 'h.suppcode')
                                ->where('s.compcode', '=', session('compcode'))
                                ->where('s.recstatus', '=', 'ACTIVE');
                })
                ->where('h.compcode',session('compcode'))
                ->where('h.unit',session('unit'))
                ->where('h.recstatus', '=', $Status)
                ->whereBetween('h.purreqdt', [$datefr, $dateto])
                ->orderBy('h.purreqdt', 'ASC')
                ->get();
        //dd($PRListing);
        }
        
        return view('material.PRListing.PRListing_excel',compact('PRListing'));
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getPageSetup()->setPaperSize(9);//A4
                
                $event->sheet->getHeaderFooter()->setOddHeader('&C'.$this->comp->name."\nPR LISTING"."\n"
                .sprintf('FROM DATE %s TO DATE %s',Carbon::parse($this->datefr)->format('d-m-Y'), Carbon::parse($this->dateto)->format('d-m-Y'))."\n"
                .'&L'
                .'PRINTED BY : '.session('username')
                ."\nPAGE : &P/&N"
                .'&R'.'PRINTED DATE : '.Carbon::now("Asia/Kuala_Lumpur")->format('d-m-Y')
                ."\n".'PRINTED TIME : '.Carbon::now("Asia/Kuala_Lumpur")->format('H:i'));
                
                $event->sheet->getPageMargins()->setTop(1);
                
                $event->sheet->getPageSetup()->setRowsToRepeatAtTop([1,1]);
                $event->sheet->getStyle('A:R')->getAlignment()->setWrapText(true);
                $event->sheet->getPageSetup()->setFitToWidth(1);
                $event->sheet->getPageSetup()->setFitToHeight(0);
            },
        ];
    }
}
