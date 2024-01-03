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

class stockSheet_xlsExport implements FromView, WithEvents, WithColumnWidths
{
    
    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function __construct($dept_from,$dept_to,$item_from,$item_to,$year,$period)
    {
        $this->dept_from = $dept_from;
        $this->dept_to = $dept_to;
        $this->item_from = $item_from;
        $this->item_to = $item_to;
        $this->year = $year;
        $this->period = $period;

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
            'F' => 100,
        ];
    }
    
    public function view(): View
    {
        $dept_from = $this->dept_from;
        $dept_to = $this->dept_to;
        $item_from = $this->item_from;
        $item_to = $this->item_to;
        $year = $this->year;
        $period = $this->period;

        $stockloc = DB::table('material.stockloc as s')
                        ->select('p.description','s.idno','s.compcode','s.deptcode','s.itemcode','s.uomcode','s.bincode','s.rackno','s.year','s.openbalqty','s.openbalval','s.netmvqty1','s.netmvqty2','s.netmvqty3','s.netmvqty4','s.netmvqty5','s.netmvqty6','s.netmvqty7','s.netmvqty8','s.netmvqty9','s.netmvqty10','s.netmvqty11','s.netmvqty12','s.netmvval1','s.netmvval2','s.netmvval3','s.netmvval4','s.netmvval5','s.netmvval6','s.netmvval7','s.netmvval8','s.netmvval9','s.netmvval10','s.netmvval11','s.netmvval12','s.stocktxntype','s.disptype','s.qtyonhand','s.minqty','s.maxqty','s.reordlevel','s.reordqty','s.lastissdate','s.frozen','s.adduser','s.adddate','s.upduser','s.upddate','s.cntdocno','s.fix_uom','s.locavgcs','s.lstfrzdt','s.lstfrztm','s.frzqty','s.recstatus','s.deluser','s.deldate','s.computerid','s.ipaddress','s.lastcomputerid','s.lastipaddress','s.unit')
                        ->join('material.product as p', function($join){
                                $join = $join->on('p.itemcode', '=', 's.itemcode');
                                $join = $join->on('p.uomcode', '=', 's.uomcode');
                                $join = $join->where('p.compcode', '=', session('compcode'));
                                $join = $join->where('p.unit', '=', session('unit'));
                            })
                        ->where('s.compcode',session('compcode'))
                        ->where('s.unit',session('unit'))
                        ->whereIn('s.deptcode',[$dept_from,$dept_to])
                        ->whereBetween('s.itemcode',[$item_from,$item_to])
                        ->where('s.year', '=', $year)
                        ->orderBy('s.itemcode', 'DESC')
                        ->get();

        foreach ($stockloc as $obj) {
            $array_obj = (array)$obj;


            $get_bal = $this->get_bal($array_obj,$period);
            $obj->open_balqty = $get_bal->open_balqty;
            $obj->open_balval = $get_bal->open_balval;
            $obj->close_balqty = $get_bal->close_balqty;
            $obj->close_balval = $get_bal->close_balval;
        }
        
        return view('material.stockBalance.stockSheet_excel',compact('stockloc'));
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getPageSetup()->setPaperSize(9);//A4
                
                $event->sheet->getHeaderFooter()->setOddHeader('&C'.$this->comp->name."\nSTOCK SHEET"."\n".sprintf('FROM ITEM %s TO ITEM %s',$this->item_from, $this->item_to).'&L'.'PRINTED BY : '.session('username')."\nPAGE : &P/&N".'&R'.'PRINTED DATE : '.Carbon::now("Asia/Kuala_Lumpur")->format('d-m-Y')."\n".'PRINTED TIME : '.Carbon::now("Asia/Kuala_Lumpur")->format('H:i'));
                
                $event->sheet->getPageMargins()->setTop(1);
                
                $event->sheet->getPageSetup()->setRowsToRepeatAtTop([2,2]);
                $event->sheet->getStyle('A:H')->getAlignment()->setWrapText(true);
                $event->sheet->getPageSetup()->setFitToWidth(1);
                $event->sheet->getPageSetup()->setFitToHeight(0);
            },
        ];
    }
    
    public function get_bal($array_obj,$period){
        $open_balqty = $array_obj['openbalqty'];
        $close_balqty = 0;
        $open_balval = $array_obj['openbalval'];
        $close_balval = 0;
        $until = intval($period) - 1;

        for ($from = 1; $from <= $until; $from++) { 
            $open_balqty = $open_balqty + $array_obj['netmvqty'.$from];
            $open_balval = $open_balval + $array_obj['netmvval'.$from];
        }

        for ($from = 1; $from <= intval($period); $from++) { 
            $close_balqty = $close_balqty + $array_obj['netmvqty'.$from];
            $close_balval = $close_balval + $array_obj['netmvval'.$from];
        }

        $responce = new stdClass();
        $responce->open_balqty = $open_balqty;
        $responce->open_balval = $open_balval;
        $responce->close_balqty = $close_balqty;
        $responce->close_balval = $close_balval;
        return $responce;
    }

    public function get_ivtxndt($obj,$period,$year){
        $grn_qty=0;
        $tr_qty=0;
        $wof_qty=0;
        $ai_qty=0;
        $ao_qty=0;
        $phy_qty=0;

        $ivtxndt = DB::table('material.ivtxndt')
                    ->where('compcode',session('compcode'))
                    ->where('itemcode',$obj->itemcode)
                    ->where('uomcode',$obj->uomcode)
                    ->where('deptcode',$obj->deptcode)
                    ->whereMonth('trandate', '=', $period)
                    ->whereYear('trandate', '=', $year);

        if($ivtxndt->exists()){
            foreach ($ivtxndt->get() as $obj) {
                switch ($obj->trantype) {
                    case 'GRN':
                        $grn_qty = $grn_qty + $obj->txnqty;
                        break;
                    case 'TR':
                        $tr_qty = $tr_qty + $obj->txnqty;
                        break;
                    case 'WOF':
                        $wof_qty = $wof_qty + $obj->txnqty;
                        break;
                    case 'AI':
                        $ai_qty = $ai_qty + $obj->txnqty;
                        break;
                    case 'AO':
                        $ao_qty = $ao_qty + $obj->txnqty;
                        break;
                    case 'PHY':
                        $phy_qty = $phy_qty + $obj->txnqty;
                        break;
                }
            }
        }

        $responce = new stdClass();
        $responce->grn_qty = $grn_qty;
        $responce->tr_qty = $tr_qty;
        $responce->wof_qty = $wof_qty;
        $responce->ai_qty = $ai_qty;
        $responce->ao_qty = $ao_qty;
        $responce->phy_qty = $phy_qty;
        return $responce;
    }

    public function get_ivdspdt($obj,$period,$year){
        $ds_qty=0;

        $ivdspdt = DB::table('material.ivdspdt')
                    ->where('compcode',session('compcode'))
                    ->where('itemcode',$obj->itemcode)
                    ->where('uomcode',$obj->uomcode)
                    ->where('reqdept',$obj->deptcode)
                    ->whereMonth('trandate', '=', $period)
                    ->whereYear('trandate', '=', $year);

        if($ivdspdt->exists()){
            foreach ($ivdspdt->get() as $obj) {
                switch ($obj->trantype) {
                    case 'DS':
                        $ds_qty = $ds_qty + $obj->txnqty;
                        break;
                }
            }
        }

        $responce = new stdClass();
        $responce->ds_qty = $ds_qty;
        return $responce;
    }
    
}
