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

class GRTListingExport implements FromView, WithEvents, WithColumnWidths, WithColumnFormatting
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

    public function columnFormats(): array
    {
        return [
            'O' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'P' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'Q' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'S' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'T' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'U' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'V' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }
    
    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 10,
            'C' => 10,
            'D' => 15,
            'E' => 13,
            'F' => 15,
            'G' => 15,
            'H' => 35,
            'I' => 15,
            'J' => 15,
            'K' => 15,
            'L' => 35,
            'M' => 10,
            'N' => 10,
            'O' => 10,
            'P' => 10,
            'Q' => 15,
            'R' => 10,
            'S' => 15,
            'T' => 15,
            'U' => 15,
            'V' => 15,
            'W' => 15,
            'X' => 15,
            'Y' => 15,
            'Z' => 15,

        ];
    }
    
    public function view(): View
    {
        $datefr = Carbon::parse($this->datefr)->format('Y-m-d');
        $dateto = Carbon::parse($this->dateto)->format('Y-m-d');
        $Status = $this->Status;
      
        if ($Status == 'ALL'){

            $GRTListing = DB::table('material.delordhd as h')
                        ->select('h.idno', 'h.compcode', 'h.recno', 'h.prdept', 'h.deldept', 'h.delordno', 'h.reqdept', 'h.trandate', 'h.suppcode', 's.name as supp_name', 'h.srcdocno', 'h.invoiceno', 'h.totamount', 'h.docno', 'h.recstatus','d.compcode','d.recno','d.lineno_','d.pricecode','d.itemcode','p.description','d.uomcode','d.pouom', 'd.suppcode','d.trandate','d.deldept','d.deliverydate','d.qtyorder','d.qtydelivered', 'd.qtyreturned','d.unitprice','d.taxcode', 'd.perdisc','d.amtdisc','d.amtslstax as tot_gst','d.netunitprice','d.totamount', 'd.amount', 'd.expdate','d.batchno', 'd.unit','d.idno', 'd.recstatus', 'do.srcdocno as do_srcdocno', 'do.trantype', 'do.prdept')
                        ->leftjoin('material.delordhd as do', function($join){
                            $join = $join->on('do.prdept', '=', 'h.prdept')
                                        ->on('do.docno', '=','h.srcdocno')
                                        ->where('do.trantype', '=', 'GRN')
                                        ->where('do.compcode',session('compcode'));
                        })
                        ->leftjoin('material.delorddt as d', function($join){
                            $join = $join->on('d.recno', '=', 'h.recno')
                                        ->where('d.compcode', '=', session('compcode'))
                                        ->where('d.unit', '=', session('unit'))
                                        ->where('d.recstatus', '!=', 'DELETE');
                        })
                        ->leftjoin('material.product as p', function($join){
                            $join = $join->on('p.itemcode', '=', 'd.itemcode')
                                        ->on('p.uomcode', '=', 'd.uomcode')
                                        ->where('p.compcode', '=', session('compcode'))
                                        ->where('p.unit', '=', session('unit'))
                                        ->where('p.recstatus', '=', 'ACTIVE');
                        })
                        ->leftjoin('material.supplier as s', function($join){
                            $join = $join->on('s.SuppCode', '=', 'h.suppcode')
                                        ->where('s.compcode', '=', session('compcode'))
                                        ->where('s.recstatus', '=', 'ACTIVE');
                        })
                        ->where('h.compcode',session('compcode'))
                        ->where('h.unit',session('unit'))
                        ->where('h.recstatus', '!=', 'DELETE')
                        ->where('h.trantype', '=', 'GRT')
                        ->whereBetween('h.trandate', [$datefr, $dateto])
                        ->orderBy('h.trandate', 'ASC')
                        ->get();

        } else {

            $GRTListing = DB::table('material.delordhd as h')
                        ->select('h.idno', 'h.compcode', 'h.recno', 'h.prdept', 'h.deldept', 'h.delordno', 'h.reqdept', 'h.trandate', 'h.suppcode', 's.name as supp_name', 'h.srcdocno', 'h.invoiceno', 'h.totamount', 'h.docno', 'h.recstatus','d.compcode','d.recno','d.lineno_','d.pricecode','d.itemcode','p.description','d.uomcode','d.pouom', 'd.suppcode','d.trandate','d.deldept','d.deliverydate','d.qtyorder','d.qtydelivered', 'd.qtyreturned','d.unitprice','d.taxcode', 'd.perdisc','d.amtdisc','d.amtslstax as tot_gst','d.netunitprice','d.totamount', 'd.amount', 'd.expdate','d.batchno', 'd.unit','d.idno', 'd.recstatus', 'do.srcdocno as do_srcdocno', 'do.trantype', 'do.prdept')
                        ->leftjoin('material.delordhd as do', function($join){
                            $join = $join->on('do.prdept', '=', 'h.prdept')
                                        ->on('do.docno', '=','h.srcdocno')
                                        ->where('do.trantype', '=', 'GRN')
                                        ->where('do.compcode',session('compcode'));
                        })
                        ->leftjoin('material.delorddt as d', function($join){
                            $join = $join->on('d.recno', '=', 'h.recno')
                                        ->where('d.compcode', '=', session('compcode'))
                                        ->where('d.unit', '=', session('unit'))
                                        ->where('d.recstatus', '!=', 'DELETE');
                        })
                        ->leftjoin('material.product as p', function($join){
                            $join = $join->on('p.itemcode', '=', 'd.itemcode')
                                        ->on('p.uomcode', '=', 'd.uomcode')
                                        ->where('p.compcode', '=', session('compcode'))
                                        ->where('p.unit', '=', session('unit'))
                                        ->where('p.recstatus', '=', 'ACTIVE');
                        })
                        ->leftjoin('material.supplier as s', function($join){
                            $join = $join->on('s.SuppCode', '=', 'h.suppcode')
                                        ->where('s.compcode', '=', session('compcode'))
                                        ->where('s.recstatus', '=', 'ACTIVE');
                        })
                        ->where('h.compcode',session('compcode'))
                        ->where('h.unit',session('unit'))
                        ->where('h.recstatus','=', $Status)
                        ->where('h.trantype', '=', 'GRT')
                        ->whereBetween('h.trandate', [$datefr, $dateto])
                        ->orderBy('h.trandate', 'ASC')
                        ->get();
        }

        return view('material.GRTListing.GRTListing_excel',compact('GRTListing'));
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getPageSetup()->setPaperSize(9);//A4
                
                $event->sheet->getHeaderFooter()->setOddHeader('&C'.$this->comp->name."\nGRT LISTING"."\n"
                .sprintf('FROM DATE %s TO DATE %s',Carbon::parse($this->datefr)->format('d-m-Y'), Carbon::parse($this->dateto)->format('d-m-Y'))."\n"
                .'&L'
                .'PRINTED BY : '.session('username')
                ."\nPAGE : &P/&N"
                .'&R'.'PRINTED DATE : '.Carbon::now("Asia/Kuala_Lumpur")->format('d-m-Y')
                ."\n".'PRINTED TIME : '.Carbon::now("Asia/Kuala_Lumpur")->format('H:i'));
                
                $event->sheet->getPageMargins()->setTop(1);
                
                $event->sheet->getPageSetup()->setRowsToRepeatAtTop([1,1]);
                $event->sheet->getStyle('A:Z')->getAlignment()->setWrapText(true);
                $event->sheet->getPageSetup()->setFitToWidth(1);
                $event->sheet->getPageSetup()->setFitToHeight(0);
            },
        ];
    }
}
