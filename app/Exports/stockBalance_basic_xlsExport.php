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

class stockBalance_basic_xlsExport implements FromView, WithEvents, WithColumnWidths, WithColumnFormatting
{
    
    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function __construct($unit_from,$unit_to,$dept_from,$dept_to,$item_from,$item_to,$year,$period,$zero_delete)
    {

        $this->unit_from = $unit_from;
        $this->unit_to = $unit_to;
        $this->dept_from = $dept_from;
        $this->dept_to = $dept_to;
        $this->item_from = $item_from;
        $this->item_to = $item_to;
        $this->year = $year;
        $this->period = $period;
        $this->break_loop=[];
        $this->zero_delete=$zero_delete;

        $this->comp = DB::table('sysdb.company')
            ->where('compcode','=',session('compcode'))
            ->first();
    }

    public function columnFormats(): array
    {
        return [
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'H' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'J' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
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
            'L' => 15,
            'M' => 15,
            'N' => 15,
            'O' => 15,
        ];
    }
    
    public function view(): View
    {
        $unit_from = $this->unit_from;
        if(empty($unit_from)){
            $unit_from = '%';
        }
        $unit_to = $this->unit_to;
        $dept_from = $this->dept_from;
        if(empty($dept_from)){
            $dept_from = '%';
        }
        $dept_to = $this->dept_to;
        $item_from = $this->item_from;
        if(empty($item_from)){
            $item_from = '%';
        }
        $item_to = $this->item_to;
        $year = $this->year;
        $period = $this->period;
        $zero_delete = $this->zero_delete;

        // $deptcode = DB::table('material.stockloc as s')
        //                 ->select('s.deptcode','d.description')
        //                 ->join('sysdb.department as d', function($join){
        //                     $join = $join->on('d.deptcode', '=', 's.deptcode');
        //                     $join = $join->where('d.compcode', '=', session('compcode'));
        //                 })
        //                 ->where('s.compcode',session('compcode'))
        //                 // ->where('s.unit',session('unit'))
        //                 ->whereBetween('s.unit',[$unit_from,$unit_to.'%'])
        //                 ->whereBetween('s.deptcode',[$dept_from,$dept_to.'%'])
        //                 ->where('s.year', '=', $year)
        //                 ->distinct('s.deptcode')
        //                 ->get('deptcode','description');

        $array_report = [];
        $break_loop = [];
        $isi_array = [];
        $loop = 0;
        // foreach ($deptcode as $dept) {

        $stockloc = DB::table('material.stockloc as s')
                        ->select('s.unit','p.description','s.idno','s.compcode','s.deptcode','s.itemcode','s.uomcode','s.bincode','s.rackno','s.year','s.openbalqty','s.openbalval','s.netmvqty1','s.netmvqty2','s.netmvqty3','s.netmvqty4','s.netmvqty5','s.netmvqty6','s.netmvqty7','s.netmvqty8','s.netmvqty9','s.netmvqty10','s.netmvqty11','s.netmvqty12','s.netmvval1','s.netmvval2','s.netmvval3','s.netmvval4','s.netmvval5','s.netmvval6','s.netmvval7','s.netmvval8','s.netmvval9','s.netmvval10','s.netmvval11','s.netmvval12','s.stocktxntype','s.disptype','s.qtyonhand','s.minqty','s.maxqty','s.reordlevel','s.reordqty','s.lastissdate','s.frozen','s.adduser','s.adddate','s.upduser','s.upddate','s.cntdocno','s.fix_uom','s.locavgcs','s.lstfrzdt','s.lstfrztm','s.frzqty','s.recstatus','s.deluser','s.deldate','s.computerid','s.ipaddress','s.lastcomputerid','s.lastipaddress','s.unit','d.description as dept_desc','sc.description as unit_desc')
                        ->join('material.product as p', function($join){
                                $join = $join->on('p.itemcode', '=', 's.itemcode');
                                $join = $join->on('p.uomcode', '=', 's.uomcode');
                                $join = $join->where('p.recstatus', '=', 'ACTIVE');
                                $join = $join->where('p.compcode', '=', session('compcode'));
                                $join = $join->where('p.groupcode', '=', 'STOCK');
                                $join = $join->on('p.unit', '=', 's.unit');
                            })
                        ->leftjoin('sysdb.department as d', function($join){
                            $join = $join->on('d.deptcode', '=', 's.deptcode');
                            // $join = $join->on('d.unit', '=', 's.unit');
                            $join = $join->where('d.compcode', '=', session('compcode'));
                        })
                        ->leftjoin('sysdb.sector as sc', function($join){
                            $join = $join->on('sc.sectorcode', '=', 's.unit');
                            // $join = $join->on('d.unit', '=', 's.unit');
                            $join = $join->where('sc.compcode', '=', session('compcode'));
                        });
        $stockloc = $stockloc
                    ->where('s.compcode',session('compcode'))
                    ->where('s.stocktxntype','TR')
                    ->whereBetween('s.unit',[$unit_from,$unit_to.'%'])
                    ->whereBetween('s.deptcode',[$dept_from,$dept_to.'%'])
                    ->whereBetween('s.itemcode',[$item_from,$item_to.'%']);

        // if(strtolower($unit_from)=='khealth'){
        //     $stockloc = $stockloc->join('material.stockexp as se', function($join){
        //                     $join = $join->on('se.itemcode', '=', 's.itemcode');
        //                     $join = $join->on('se.deptcode', '=', 's.deptcode');
        //                     $join = $join->on('se.uomcode', '=', 's.uomcode');
        //                     $join = $join->where('se.compcode', '=', session('compcode'));
        //                     // $join = $join->where('se.unit', '=', session('unit'));
        //                     // $join = $join->on('se.year', '=', 's.year');
        //                 });
        // }

        $stockloc = $stockloc->where('s.compcode',session('compcode'))
                    ->where('s.year', '=', $year)
                    ->orderBy('s.deptcode', 'ASC')
                    ->orderBy('s.itemcode', 'ASC')
                    ->get();

        $isi = 0;
        foreach ($stockloc as $obj) {
            $loop = $loop + 1;
            $isi = $isi + 1;
            $obj->unit = strtoupper($obj->unit);
            $obj->deptcode = strtoupper($obj->deptcode);

            $array_obj = (array)$obj;
            $get_bal = $this->get_bal($array_obj,$period);
            $obj->open_balqty = $get_bal->open_balqty;
            $obj->open_balval = $get_bal->open_balval;
            $obj->close_balqty = $get_bal->close_balqty;
            $obj->close_balval = $get_bal->close_balval;
            $obj->netmvqty = $get_bal->netmvqty;
            $obj->netmvval = $get_bal->netmvval;

            // $get_ivtxndt = $this->get_ivtxndt($obj,$period,$year);
            // $obj->grn_qty = $get_ivtxndt->grn_qty;
            // $obj->tr_qty = $get_ivtxndt->tr_qty;
            // $obj->wof_qty = $get_ivtxndt->wof_qty;
            // $obj->ai_qty = $get_ivtxndt->ai_qty;
            // $obj->ao_qty = $get_ivtxndt->ao_qty;
            // $obj->phy_qty = $get_ivtxndt->phy_qty;

            // $get_ivdspdt = $this->get_ivdspdt($obj,$period,$year);
            // $obj->ds_qty = $get_ivdspdt->ds_qty;

            // $totmv = floatval($get_ivtxndt->grn_qty)-floatval($get_ivdspdt->ds_qty)-floatval($get_ivtxndt->tr_qty)+floatval($get_ivtxndt->wof_qty)+floatval($get_ivtxndt->ai_qty)-floatval($get_ivtxndt->ao_qty)+floatval($get_ivtxndt->phy_qty);
            // $oth_qty = floatval($get_bal->close_balqty) - floatval($get_bal->open_balqty) - floatval($totmv);
            // $obj->oth_qty = $oth_qty;
            if($zero_delete == 1){
                if(empty((float)$obj->open_balqty) && empty((float)$obj->open_balval) && empty((float)$obj->close_balqty) && empty((float)$obj->close_balval) && empty((float)$obj->netmvqty) && empty((float)$obj->netmvval)){
                    continue;
                }else{
                    array_push($array_report, $obj);
                }
            }else{
                array_push($array_report, $obj);
            }

        }
        array_push($isi_array, $isi);
        $loop = $loop + 4;
        array_push($break_loop, $loop);

        $unit = $stockloc->unique('unit');
        // dump($unit);
        $deptcode = $stockloc->unique('deptcode');
        // dd($deptcode);

        // dd($array_report);

        $this->break_loop = $break_loop;
        
        return view('material.stockBalance.stockBalance_basic_excel',compact('unit','deptcode','array_report','period'));
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                foreach ($this->break_loop as $value) {
                    $event->sheet->setBreak('A'.$value, \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);
                }

                $event->sheet->getPageSetup()->setPaperSize(9);//A4
                
                $event->sheet->getHeaderFooter()->setOddHeader('&C'.$this->comp->name."\nITEM LIST"."\n".sprintf('FROM ITEM %s TO ITEM %s',$this->item_from, $this->item_to).'&L'.'PRINTED BY : '.session('username')."\nPAGE : &P/&N".'&R'.'PRINTED DATE : '.Carbon::now("Asia/Kuala_Lumpur")->format('d-m-Y')."\n".'PRINTED TIME : '.Carbon::now("Asia/Kuala_Lumpur")->format('H:i'));
                
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
        $close_balqty = $array_obj['openbalqty'];
        $open_balval = $array_obj['openbalval'];
        $close_balval = $array_obj['openbalval'];
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
        $responce->netmvqty = $array_obj['netmvqty'.$period];
        $responce->netmvval = $array_obj['netmvval'.$period];
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
                    case 'PHYCNT':
                        $phy_qty = $phy_qty + $obj->txnqty;
                        break;
                }
            }
        }

        $ivtxndt_sndrcv = DB::table('material.ivtxndt')
                            ->where('compcode',session('compcode'))
                            ->where('itemcode',$obj->itemcode)
                            ->where('uomcoderecv',$obj->uomcode)
                            ->where('sndrcv',$obj->deptcode)
                            ->whereMonth('trandate', '=', $period)
                            ->whereYear('trandate', '=', $year);

        if($ivtxndt_sndrcv->exists()){
            foreach ($ivtxndt->get() as $obj) {
                switch ($obj->trantype) {
                    case 'TR':
                        $tr_qty = $tr_qty - $obj->txnqty;
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

    public static function getQueries($builder){
        $addSlashes = str_replace('?', "'?'", $builder->toSql());
        return vsprintf(str_replace('?', '%s', $addSlashes), $builder->getBindings());
    }

    
}
