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

class ItemEnquiryExport implements FromView, WithEvents, WithColumnWidths, WithColumnFormatting
{
    
    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function __construct($itemcode,$deptcode,$uomcode,$trandate_from,$trandate_to)
    {
        $this->itemcode = $itemcode;
        $this->deptcode = $deptcode;
        $this->uomcode = $uomcode;
        $this->trandate_from = $trandate_from;
        $this->trandate_to = $trandate_to;
        
        $this->comp = DB::table('sysdb.company')
                    ->where('compcode', '=' ,session('compcode'))
                    ->first();
    }

    public function columnFormats(): array
    {
        return [
            'H' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'I' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }
    
    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 15,
            'C' => 25,
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
        // $datefr = Carbon::parse($this->datefr)->format('Y-m-d');

        $itemcode = $this->itemcode;
        $deptcode = $this->deptcode;
        $uomcode = $this->uomcode;
        $trandate_from = $this->trandate_from;
        $trandate_to = $this->trandate_to;

        $product = DB::table('material.product')
                        ->where('compcode',session('compcode'))
                        ->where('itemcode',$itemcode)
                        ->where('uomcode',$uomcode)
                        ->first();

        $stockloc = DB::table('material.stockloc')
                        ->where('compcode',session('compcode'))
                        ->where('itemcode',$itemcode)
                        ->where('uomcode',$uomcode)
                        ->where('deptcode',$deptcode)
                        ->first();

        $stockloc_ar = (array)$stockloc;

        $month = Carbon::parse($trandate_from)->format('n');
        $open_balqty = $stockloc_ar['netmvqty'.$month];
        $open_balval = $stockloc_ar['netmvval'.$month];
        $avgcost = $product->avgcost;

        $department = DB::table('sysdb.department')
                        ->where('compcode',session('compcode'))
                        ->where('deptcode',$deptcode)
                        ->first();

        $det_mov_deptcode = DB::table('material.ivtxndt as d')
                ->select('d.adddate','d.trandate','d.trantype','d.deptcode','d.txnqty','d.recno','d.lineno_', 'd.upduser', 'd.updtime', 'h.docno', 'd.uomcoderecv', 'd.uomcode','d.adduser', 'd.netprice', 'd.amount', 'h.trantime','t.crdbfl', 't.description','d.sndrcv')
                ->leftJoin('material.ivtxnhd as h', function($join){
                        $join = $join->on('h.recno', '=', 'd.recno')
                                     ->on('h.trantype', '=', 'd.trantype')
                                     ->on('h.txndept', '=', 'd.deptcode')
                                     ->where('h.compcode','=',session('compcode'));
                    })
                ->leftJoin('material.ivtxntype as t', function($join){
                        $join = $join->on('t.trantype', '=', 'd.trantype')
                                     ->where('t.compcode','=',session('compcode'));
                    })
                ->where('d.compcode','=',session('compcode'))
                ->where('d.itemcode','=',$itemcode)
                ->where('d.deptcode','=',$deptcode)
                ->where('d.uomcode','=',$uomcode)
                ->whereDate('d.trandate','>=',$trandate_from)
                ->whereDate('d.trandate','<=',$trandate_to)
                // ->where('d.amount','!=',0)
                ->orderBy('d.adddate', 'asc')
                // ->orderBy('h.trantime', 'desc')
                ->get();

        $det_mov_deptcode = $det_mov_deptcode->each(function ($item, $key) {
            if(empty($item->amount)){
                $item->amount = 0.00;
            }
            $item->det_mov = 'deptcode';
            $item->mrn = '-';
            $item->episno = '-';
        });

        //yg ni ivdspdt
        $det_mov_deptcode_ivdspdt = DB::table('material.ivdspdt as d')
            ->select('d.adddate','d.trandate','d.trantype','d.reqdept as deptcode','d.txnqty', 'd.upduser','d.recno','d.lineno_', 'd.updtime', 'd.recno as docno', 'd.uomcode', 'd.uomcode','d.adduser', 'd.netprice', 'd.amount', 'd.updtime as trantime','t.crdbfl', 't.description', 'd.mrn', 'd.episno')
            ->leftJoin('material.ivtxntype as t', function($join){
                    $join = $join->on('t.trantype', '=', 'd.trantype')
                                 ->where('t.compcode','=',session('compcode'));
                })
            ->where('d.compcode','=',session('compcode'))
            ->where('d.itemcode','=',$itemcode)
            ->where('d.reqdept','=',$deptcode)
            ->where('d.uomcode','=',$uomcode)
            ->whereDate('d.trandate','>=',$trandate_from)
            ->whereDate('d.trandate','<=',$trandate_to)
            // ->where('d.amount','!=',0)
            ->orderBy('d.adddate', 'asc')
            // ->orderBy('d.updtime', 'desc')
            ->get();

        $det_mov_deptcode_ivdspdt = $det_mov_deptcode_ivdspdt->each(function ($item, $key) {
            if(empty($item->amount)){
                $item->amount = 0.00;
            }
            $item->det_mov = 'deptcode';
            $item->sndrcv = '-';
        });


        $merged = $det_mov_deptcode->merge($det_mov_deptcode_ivdspdt);
        

        //yg ni masuk kot
        $det_mov_sndrcv = DB::table('material.ivtxndt as d')
                ->select('d.adddate','d.trandate','d.trantype','d.deptcode','d.txnqty','d.lineno_','d.recno', 'd.upduser', 'd.updtime', 'h.docno', 'd.uomcoderecv', 'd.uomcode','d.adduser', 'd.netprice', 'd.amount', 'h.trantime','t.crdbfl', 't.description','d.sndrcv')
                ->leftJoin('material.ivtxnhd as h', function($join){
                        $join = $join->on('h.recno', '=', 'd.recno')
                                     ->on('h.trantype', '=', 'd.trantype')
                                     ->on('h.txndept', '=', 'd.deptcode')
                                     ->where('h.compcode','=',session('compcode'));
                    })
                ->leftJoin('material.ivtxntype as t', function($join){
                        $join = $join->on('t.trantype', '=', 'd.trantype')
                                     ->where('t.compcode','=',session('compcode'));
                    })
                ->where('d.compcode','=',session('compcode'))
                ->where('d.itemcode','=',$itemcode)
                ->where('d.sndrcv','=',$deptcode)
                ->where('d.uomcoderecv','=',$uomcode)
                ->whereDate('d.trandate','>=',$trandate_from)
                ->whereDate('d.trandate','<=',$trandate_to)
                // ->where('d.amount','!=',0)
                ->orderBy('d.trandate', 'asc')
                // ->orderBy('h.trantime', 'desc')
                ->get();

        $det_mov_sndrcv = $det_mov_sndrcv->each(function ($item, $key) {
            if(empty($item->amount)){
                $item->amount = 0.00;
            }
            $item->det_mov = 'sndrcv';
            $item->mrn = '-';
            $item->episno = '-';
            if($item->uomcode != $item->uomcoderecv){

                //1. amik convfactor
                $convfactor_obj = DB::table('material.uom')
                    ->select('convfactor')
                    ->where('uomcode','=',$item->uomcoderecv)
                    ->where('compcode','=',session('compcode'))
                    ->first();
                $convfactor_uomcoderecv = $convfactor_obj->convfactor;

                $convfactor_obj = DB::table('material.uom')
                    ->select('convfactor')
                    ->where('uomcode','=',$item->uomcode)
                    ->where('compcode','=',session('compcode'))
                    ->first();
                $convfactor_uomcodetrdept = $convfactor_obj->convfactor;

                //2. tukar txnqty dgn netprice berdasarkan convfactor
                $txnqty = $item->txnqty * ($convfactor_uomcodetrdept / $convfactor_uomcoderecv);
                $item->txnqty = $txnqty;
            }
        });

        $merged = $merged->merge($det_mov_sndrcv);
        // dd($merged);

        $company = DB::table('sysdb.company')
                    ->where('compcode', '=', session('compcode'))
                    ->first();

        $datefr = Carbon::parse($trandate_from)->format('d-m-Y');
        $dateto = Carbon::parse($trandate_to)->format('d-m-Y');
        
        return view('material.itemInquiry.itemInquiryExport_excel', compact('product','stockloc','department','open_balqty','open_balval','avgcost','datefr','dateto','merged','company'));
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event){
                
                $event->sheet->getPageSetup()->setPaperSize(9); // A4
                
                // $event->sheet->getHeaderFooter()->setOddHeader('&C'.$this->comp->name."\nSTATEMENT LISTING"."\n".sprintf('DATE ',$this->dateto).'&L'.'PRINTED BY : '.session('username')."\nPAGE : &P/&N".'&R'.'PRINTED DATE : '.Carbon::now("Asia/Kuala_Lumpur")->format('d-m-Y')."\n".'PRINTED TIME : '.Carbon::now("Asia/Kuala_Lumpur")->format('H:i'));
                
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
