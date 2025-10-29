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

class do_posted_report_Export implements FromView, WithEvents, WithColumnWidths, WithColumnFormatting
{
    
    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function __construct($datefr,$dateto,$dept_from,$dept_to,$recstatus)
    {

        $this->datefr = $datefr;
        $this->dateto = $dateto;
        $this->dept_from = $dept_from;
        $this->dept_to = $dept_to;
        $this->recstatus = $recstatus;
        $this->comp = DB::table('sysdb.company')
            ->where('compcode','=',session('compcode'))
            ->first();
    }

    public function columnFormats(): array
    {
        return [
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }
    
    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 20,
            'C' => 40,
            'D' => 20,
            'E' => 15,
            'F' => 20,
            'G' => 20,
            'H' => 20,
            'I' => 20,
        ];
    }
    
    public function view(): View{
        
        $datefr = Carbon::parse($this->datefr)->format('Y-m-d');
        $dateto = Carbon::parse($this->dateto)->format('Y-m-d');
        $dept_from = $this->dept_from;
        $dept_to = $this->dept_to;
        $recstatus = $this->recstatus;

        $delordhd = DB::table('material.delordhd as do_hd')
                    ->select('do_hd.idno','do_hd.compcode','do_hd.recno','do_hd.prdept','do_hd.trantype','do_hd.docno','do_hd.delordno','do_hd.invoiceno','do_hd.suppcode','do_hd.srcdocno','do_hd.deldept','do_hd.subamount','do_hd.amtdisc','do_hd.perdisc','do_hd.totamount','do_hd.deliverydate','do_hd.trandate','do_hd.trantime','do_hd.respersonid','do_hd.checkpersonid','do_hd.checkdate','do_hd.postedby','do_hd.recstatus','do_hd.remarks','do_hd.adduser','do_hd.adddate','do_hd.upduser','do_hd.upddate','do_hd.reason','do_hd.rtnflg','do_hd.reqdept','do_hd.credcode','do_hd.impflg','do_hd.allocdate','do_hd.postdate','do_hd.deluser','do_hd.taxclaimable','do_hd.TaxAmt','do_hd.prortdisc','do_hd.cancelby','do_hd.canceldate','do_hd.reopenby','do_hd.reopendate','do_hd.unit','do_hd.postflag','su.Name as suppcode_desc','dp.description as deldept_desc','do_dt.lineno_','do_dt.pricecode','do_dt.itemcode','pr.description as itemcode_desc','do_dt.uomcode','do_dt.amount','do_dt.pouom','do_dt.unitprice','do_dt.remarks','do_dt.expdate','do_dt.batchno','do_dt.qtydelivered')
                    ->leftjoin('material.delorddt as do_dt', function($join) use ($recstatus){
                        $join = $join->where('do_dt.compcode', '=', session('compcode'));
                        $join = $join->where('do_dt.recstatus', '=', $recstatus);
                        $join = $join->on('do_dt.recno', '=', 'do_hd.recno');
                    })
                    ->leftjoin('material.supplier as su', function($join) {
                        $join = $join->where('su.compcode', '=', session('compcode'));
                        $join = $join->on('su.SuppCode', '=', 'do_hd.suppcode');
                    })
                    ->leftjoin('material.product as pr', function($join) {
                        $join = $join->where('pr.compcode', '=', session('compcode'));
                        $join = $join->on('pr.itemcode', '=', 'do_dt.itemcode');
                        $join = $join->on('pr.uomcode', '=', 'do_dt.uomcode');
                    })
                    ->leftjoin('sysdb.department as dp', function($join) {
                        $join = $join->where('dp.compcode', '=', session('compcode'));
                        $join = $join->on('dp.deptcode', '=', 'do_hd.deldept');
                    });

        if(strtoupper($this->dept_from) == 'ZZZ' && strtoupper($this->dept_to) == 'ZZZ'){

        }else{
            $delordhd = $delordhd->whereBetween('do_hd.deldept',[$dept_from,$dept_to]);
        }      

        $delordhd = $delordhd
                    ->whereDate('do_hd.trandate','>=',$datefr)
                    ->whereDate('do_hd.trandate','<=',$dateto)
                    ->where('do_hd.compcode','=',session('compcode'))
                    // ->where('ap.unit',session('unit'))
                    ->where('do_hd.trantype', '=', 'GRN');

        if($recstatus == 'ALL'){
            $delordhd = $delordhd
                    ->orderBy('do_hd.idno', 'DESC')
                    ->orderBy('do_dt.idno', 'DESC')
                    ->get();

        }else{
            $delordhd = $delordhd
                    ->where('do_hd.recstatus', '=', $recstatus)
                    ->orderBy('do_hd.idno', 'DESC')
                    ->orderBy('do_dt.idno', 'DESC')
                    ->get();

        }

        // dd($this->getQueries($delordhd));

        $do_hd = $delordhd->unique('recno');

        return view('material.deliveryOrder.do_posted_report_excel',compact('do_hd', 'delordhd'));
    }

    public function getQueries($builder){
        $addSlashes = str_replace('?', "'?'", $builder->toSql());
        return vsprintf(str_replace('?', '%s', $addSlashes), $builder->getBindings());
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {        
                $event->sheet->getPageSetup()->setPaperSize(9);//A4
                
                $event->sheet->getHeaderFooter()->setOddHeader('&C'.$this->comp->name."\nDELIVERY ORDER POSTED LISTING"
                .'&L'
                .'PRINTED BY : '.session('username')
                ."\nPAGE : &P/&N"
                .'&R'.'PRINTED DATE : '.Carbon::now("Asia/Kuala_Lumpur")->format('d-m-Y')
                ."\n".'PRINTED TIME : '.Carbon::now("Asia/Kuala_Lumpur")->format('H:i'));
                
                $event->sheet->getPageMargins()->setTop(1);
                
                $event->sheet->getPageSetup()->setRowsToRepeatAtTop([1,1]);
                $event->sheet->getStyle('A:H')->getAlignment()->setWrapText(true);
                $event->sheet->getPageSetup()->setFitToWidth(1);
                $event->sheet->getPageSetup()->setFitToHeight(0);
            },
        ];
    }

    public function calc_bal($obj){
        $balance = 0;
        foreach ($obj->get() as $key => $value){
            
            switch ($value->trantype) {
                 case 'IN': //dr
                    $balance = $balance + floatval($value->amount);
                    break;
                case 'DN': //dr
                    $balance = $balance + floatval($value->amount);
                    break;
                case 'CN': //cr
                    $balance = $balance - floatval($value->amount);
                    break;
                case 'PV': //cr
                    $balance = $balance - floatval($value->amount);
                    break;
                case 'PD': //cr
                    $balance = $balance - floatval($value->amount);
                    break;
                default:
                    // code...
                    break;
            }
        }

        return $balance;
    }

    public function assign_grouping($grouping,$days){
        $group = 0;

        foreach ($grouping as $key => $value) {
            if(!empty($value) && $days >= intval($value)){
                $group = $key;
            }
        }

        return $group;
    }
}
