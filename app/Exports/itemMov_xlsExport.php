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

class itemMov_xlsExport implements FromView, WithEvents, WithColumnWidths
{
    
    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function __construct($type,$dept_from,$dept_to,$date_from,$date_to)
    {
        $this->type = $type;
        if($this->type == 'fast'){
            $this->title = 'Fast Moving Item';
        }else{
            $this->title = 'Slow Moving Item';
        }

        $this->dept_from = $dept_from;
        $this->dept_to = $dept_to;
        $this->date_from = $date_from;
        $this->date_to = $date_to;

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
        ];
    }
    
    public function view(): View
    {
        $type = $this->type;
        $dept_from = $this->dept_from;
        $dept_to = $this->dept_to;
        $date_from = $this->date_from;
        $date_to = $this->date_to;

        $stockloc = DB::table('material.stockloc as s')
                        ->select('p.description','s.idno','s.compcode','s.deptcode','s.itemcode','s.uomcode','s.bincode','s.rackno','s.year','s.openbalqty','s.openbalval','s.netmvqty1','s.netmvqty2','s.netmvqty3','s.netmvqty4','s.netmvqty5','s.netmvqty6','s.netmvqty7','s.netmvqty8','s.netmvqty9','s.netmvqty10','s.netmvqty11','s.netmvqty12','s.netmvval1','s.netmvval2','s.netmvval3','s.netmvval4','s.netmvval5','s.netmvval6','s.netmvval7','s.netmvval8','s.netmvval9','s.netmvval10','s.netmvval11','s.netmvval12','s.stocktxntype','s.disptype','s.qtyonhand','s.minqty','s.maxqty','s.reordlevel','s.reordqty','s.lastissdate','s.frozen','s.adduser','s.adddate','s.upduser','s.upddate','s.cntdocno','s.fix_uom','s.locavgcs','s.lstfrzdt','s.lstfrztm','s.frzqty','s.recstatus','s.deluser','s.deldate','s.computerid','s.ipaddress','s.lastcomputerid','s.lastipaddress','s.unit')
                        ->join('material.product as p', function($join){
                                $join = $join->on('p.itemcode', '=', 's.itemcode');
                                // $join = $join->on('p.uomcode', '=', 's.uomcode');
                                $join = $join->where('p.compcode', '=', session('compcode'));
                                // $join = $join->where('p.unit', '=', session('unit'));
                            })
                        ->where('s.compcode',session('compcode'));
                        // ->where('s.unit',session('unit'));

        $stockloc = $stockloc->where('s.deptcode',$dept_from);

        $stockloc = $stockloc->where('s.year', $this->toYear($date_to));

        $ivdspdt_array=[];
        foreach ($stockloc->get() as $key => $value) {

            $ivdspdt = DB::table('material.ivdspdt as ivdt')
                        ->where('ivdt.issdept',$value->deptcode)
                        ->where('ivdt.itemcode',$value->itemcode)
                        ->where('ivdt.uomcode',$value->uomcode)
                        ->where('ivdt.compcode',session('compcode'))
                        ->whereDate('trandate', '>=', $date_from)
                        ->whereDate('trandate', '<=', $date_to);

            if(!$ivdspdt->exists()){
                continue;
            }else{

                $array_obj = (array)$value;
                $get_bal = $this->get_bal($array_obj,$this->toMonth($date_to));
                $qtyonhand = $get_bal->close_balqty;
                $qtyonhandval = $get_bal->close_balval;

                $disp_qty = 0;
                $disp_cost = 0;
                $disp_saleamt = 0;

                foreach ($ivdspdt->get() as $key_ivdspdt => $value_ivdspdt){
                    $disp_qty = floatval($disp_qty) + floatval($value_ivdspdt->txnqty);
                    $disp_cost = floatval($disp_cost) + floatval($value_ivdspdt->amount);
                    $disp_saleamt = floatval($disp_saleamt) + floatval($value_ivdspdt->saleamt);
                }

                $topush= [
                    'itemcode' => $value->itemcode,
                    'description' => $value->description,
                    'uomcode' => $value->uomcode,
                    'qtyonhand' => $qtyonhand,
                    'qtyonhandval' => $qtyonhandval,
                    'disp_qty' => $disp_qty,
                    'disp_cost' => $disp_cost,
                    'disp_saleamt' => $disp_saleamt,
                ];

                array_push($ivdspdt_array,$topush);
            }
        }

        if($type == 'fast'){
            usort($ivdspdt_array, function($a, $b){
                return floatval($a['disp_qty']) < floatval($b['disp_qty']);
            });
        }else{
            usort($ivdspdt_array, function($a, $b){
                return floatval($a['disp_qty']) > floatval($b['disp_qty']);
            });
        }

        // dd($ivdspdt_array);
        
        return view('material.itemMovReport.itemMovFast_excel',compact('ivdspdt_array'));
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getPageSetup()->setPaperSize(9);//A4
                
                $event->sheet->getHeaderFooter()->setOddHeader('&C'.$this->comp->name."\n".$this->title."\n".sprintf('FROM DATE %s TO DATE %s',$this->date_from, $this->date_to).'&L'.'PRINTED BY : '.session('username')."\nPAGE : &P/&N".'&R'.'PRINTED DATE : '.Carbon::now("Asia/Kuala_Lumpur")->format('d-m-Y')."\n".'PRINTED TIME : '.Carbon::now("Asia/Kuala_Lumpur")->format('H:i'));
                
                $event->sheet->getPageMargins()->setTop(1);
                
                $event->sheet->getPageSetup()->setRowsToRepeatAtTop([2,2]);
                $event->sheet->getStyle('A:H')->getAlignment()->setWrapText(true);
                $event->sheet->getPageSetup()->setFitToWidth(1);
                $event->sheet->getPageSetup()->setFitToHeight(0);
            },
        ];
    }

    public static function toYear($date){
        $carbon = new Carbon($date);
        return $carbon->year;
    }

    public static function toMonth($date){
        $carbon = new Carbon($date);
        return $carbon->month;
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
    
}
