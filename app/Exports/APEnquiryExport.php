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

class APEnquiryExport implements FromView, WithEvents, WithColumnWidths
{
    
    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function __construct($suppcode_from,$suppcode_to,$datefrom,$dateto)
    {
        $this->suppcode_from = $suppcode_from;
        $this->suppcode_to = $suppcode_to;
        $this->datefrom = $datefrom;
        $this->dateto = $dateto;

        $this->comp = DB::table('sysdb.company')
            ->where('compcode','=',session('compcode'))
            ->first();
    }
    
    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 17,
            'C' => 40,
            'D' => 15,
            'E' => 15,
            'F' => 15,
            'G' => 15,
            'H' => 15,
        ];
    }
    
    public function view(): View
    {
        $suppcode_from = $this->suppcode_from;
        $suppcode_to = $this->suppcode_to;
        $datefrom = Carbon::parse($this->datefrom)->format('Y-m-d');
        $dateto = Carbon::parse($this->dateto)->format('Y-m-d');

        if(strtoupper($suppcode_from) != 'ZZZ' || strtoupper($suppcode_from) != 'ZZZ'){
            $apacthdr = DB::table('finance.apacthdr as ap')
                        ->select('ap.compcode','ap.auditno','ap.trantype','ap.doctype','ap.suppcode','su.name AS supplier_name','ap.actdate','ap.document','ap.cheqno','ap.deptcode','ap.amount','ap.outamount','ap.recstatus','ap.payto','ap.recdate','ap.postdate','ap.postuser','ap.category','ap.remarks','ap.adduser','ap.adddate','ap.upduser','ap.upddate','ap.source','ap.idno','ap.unit','ap.pvno','ap.paymode','ap.bankcode','ap.unallocated')
                        ->join('material.supplier as su', function($join){
                            $join = $join->on('su.SuppCode', '=', 'ap.suppcode');
                            $join = $join->where('su.compcode', '=', session('compcode'));
                        })
                        ->where('ap.compcode',session('compcode'))
                        ->where('ap.unit',session('unit'))
                        ->where('ap.recstatus', '=', 'POSTED')
                        ->whereBetween('ap.postdate', [$datefrom, $dateto])
                        ->whereBetween('ap.suppcode',[$suppcode_from,$suppcode_to])
                        ->orderBy('ap.postdate','ASC')
                        ->get();

            $calc_openbal = DB::table('finance.apacthdr as ap')
                        ->join('material.supplier as su', function($join){
                            $join = $join->on('su.SuppCode', '=', 'ap.suppcode');
                            $join = $join->where('su.compcode', '=', session('compcode'));
                        })
                        ->where('ap.compcode',session('compcode'))
                        ->where('ap.unit',session('unit'))
                        ->where('ap.recstatus', '=', 'POSTED')
                        ->whereDate('ap.postdate', '<',$datefrom)
                        ->where('ap.suppcode','<',$suppcode_from);

            $openbal = $this->calc_openbal($calc_openbal); 

        }else{
            $apacthdr = DB::table('finance.apacthdr as ap')
                        ->select('ap.compcode','ap.auditno','ap.trantype','ap.doctype','ap.suppcode','su.name AS supplier_name','ap.actdate','ap.document','ap.cheqno','ap.deptcode','ap.amount','ap.outamount','ap.recstatus','ap.payto','ap.recdate','ap.postdate','ap.postuser','ap.category','ap.remarks','ap.adduser','ap.adddate','ap.upduser','ap.upddate','ap.source','ap.idno','ap.unit','ap.pvno','ap.paymode','ap.bankcode','ap.unallocated')
                        ->join('material.supplier as su', function($join){
                            $join = $join->on('su.SuppCode', '=', 'ap.suppcode');
                            $join = $join->where('su.compcode', '=', session('compcode'));
                        })
                        ->where('ap.compcode',session('compcode'))
                        ->where('ap.unit',session('unit'))
                        ->where('ap.recstatus', '=', 'POSTED')
                        ->whereBetween('ap.postdate', [$datefrom, $dateto])
                        ->orderBy('ap.postdate','ASC')
                        ->get();

            $calc_openbal = DB::table('finance.apacthdr as ap')
                        ->join('material.supplier as su', function($join){
                            $join = $join->on('su.SuppCode', '=', 'ap.suppcode');
                            $join = $join->where('su.compcode', '=', session('compcode'));
                        })
                        ->where('ap.compcode',session('compcode'))
                        ->where('ap.unit',session('unit'))
                        ->where('ap.recstatus', '=', 'POSTED')
                        ->whereDate('ap.postdate', '<',$datefrom);

            $openbal = $this->calc_openbal($calc_openbal); 
        }

        $array_report = [];
        $balance = $openbal;
        foreach ($apacthdr as $key => $value){
            $value->amount_dr = 0;
            $value->amount_cr = 0;
            
            switch ($value->trantype) {
                case 'IN': //dr
                    $value->amount_dr = $value->amount;
                    $balance = $balance + floatval($value->amount);
                    $value->balance = $balance;
                    array_push($array_report, $value);
                    break;
                case 'DN': //dr
                    $value->amount_dr = $value->amount;
                    $balance = $balance + floatval($value->amount);
                    $value->balance = $balance;
                    array_push($array_report, $value);
                    break;
                case 'CN': //cr
                    $value->amount_cr = $value->amount;
                    $balance = $balance - floatval($value->amount);
                    $value->balance = $balance;
                    array_push($array_report, $value);
                    break;
                case 'PV': //cr
                    $value->amount_cr = $value->amount;
                    $balance = $balance - floatval($value->amount);
                    $value->balance = $balance;
                    array_push($array_report, $value);
                    break;
                case 'PD': //cr
                    $value->amount_cr = $value->amount;
                    $balance = $balance - floatval($value->amount);
                    $value->balance = $balance;
                    array_push($array_report, $value);
                    break;
                default:
                    // code...
                    break;
            }

        }

        return view('finance.AP.apenquiry.apenquiry_excel',compact('apacthdr', 'openbal'));
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getPageSetup()->setPaperSize(9);//A4
                
                $event->sheet->getHeaderFooter()->setOddHeader('&C'.$this->comp->name."\nSTATEMENT"."\n"
                .sprintf('FROM DATE %s TO DATE %s',Carbon::parse($this->datefrom)->format('d-m-Y'), Carbon::parse($this->dateto)->format('d-m-Y'))."\n"
                .sprintf('FROM %s TO %s',$this->suppcode_from, $this->suppcode_to)
                .'&L'
                .'PRINTED BY : '.session('username')
                ."\nPAGE : &P/&N"
                .'&R'.'PRINTED DATE : '.Carbon::now("Asia/Kuala_Lumpur")->format('d-m-Y')
                ."\n".'PRINTED TIME : '.Carbon::now("Asia/Kuala_Lumpur")->format('H:i'));
                
                $event->sheet->getPageMargins()->setTop(1);
                
                $event->sheet->getPageSetup()->setRowsToRepeatAtTop([2,2]);
                $event->sheet->getStyle('A:H')->getAlignment()->setWrapText(true);
                $event->sheet->getPageSetup()->setFitToWidth(1);
                $event->sheet->getPageSetup()->setFitToHeight(0);
            },
        ];
    }

    public function calc_openbal($obj){
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
}
