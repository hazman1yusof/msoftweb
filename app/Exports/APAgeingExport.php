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

class APAgeingExport implements FromView, WithEvents, WithColumnWidths
{
    
    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function __construct($suppcode_from,$suppcode_to,$datefr,$dateto)
    {
        $this->suppcode_from = $suppcode_from;
        $this->suppcode_to = $suppcode_to;
        $this->datefr = $datefr;
        $this->dateto = $dateto;
        $this->break_loop=[];

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
        if(empty($this->suppcode_from)){
            $suppcode_from = '%';
        }
        $suppcode_to = $this->suppcode_to;
        $datefr = Carbon::parse($this->datefr)->format('Y-m-d');
        $dateto = Carbon::parse($this->dateto)->format('Y-m-d');

        $years = range(Carbon::parse($this->datefr)->format('Y'), Carbon::parse($this->dateto)->format('Y'));

        $supp_code = DB::table('finance.apacthdr as ap')
                    ->select('ap.suppcode', 'su.Name AS supplier_name','su.Addr1 AS Addr1','su.Addr2 AS Addr2', 'su.Addr3 AS Addr3', 'su.Addr4 AS Addr4')
                    ->join('material.supplier as su', function($join) {
                        $join = $join->on('su.SuppCode', '=', 'ap.suppcode');
                        $join = $join->where('su.compcode', '=', session('compcode'));
                    })
                    ->where('ap.compcode','=',session('compcode'))
                    ->where('ap.unit',session('unit'))
                    ->where('ap.recstatus', '=', 'POSTED')
                    ->whereBetween('su.SuppCode', [$suppcode_from, $suppcode_to.'%'])
                    ->whereBetween('ap.postdate', [$datefr, $dateto])
                    ->orderBy('ap.suppcode', 'ASC')
                    ->distinct('ap.suppcode');

        $supp_code = $supp_code->get(['ap.suppcode', 'su.supplier_name', 'su.Addr1', 'su.Addr2', 'su.Addr3', 'su.Addr4']);

        $array_report = [];
        $years_bal_all = [];
        $break_loop = [];
        $loop = 0;
        foreach ($supp_code as $key => $value){
            $years_bal = [];
            $calc_openbal = DB::table('finance.apacthdr as ap') 
                    ->where('ap.compcode',session('compcode'))
                    ->where('ap.unit',session('unit'))
                    ->where('ap.recstatus', '=', 'POSTED')
                    ->where('ap.suppcode', $value->suppcode)
                    ->whereYear('ap.postdate', '<', Carbon::parse($this->datefr)->format('Y'));

            $openbalb4 = $this->calc_bal($calc_openbal);

            foreach ($years as $year) {
                $apacthdr = DB::table('finance.apacthdr as ap')
                            ->where('ap.compcode', '=', session('compcode'))
                            ->where('ap.unit',session('unit'))
                            ->where('ap.recstatus', '=', 'POSTED')
                            ->where('ap.suppcode', $value->suppcode)
                            ->whereYear('ap.postdate', $year);
            
                $balance = $this->calc_bal($apacthdr);
                $total_bal = $balance + $openbalb4;
                array_push($years_bal,$total_bal);
                $openbalb4 = $total_bal;
            }
            array_push($array_report, $value);
            array_push($years_bal_all,$years_bal);

        }
        
        $this->break_loop = $break_loop;

        return view('finance.AP.APAgeing_Report.APAgeing_Report_excel',compact('years','years_bal_all','supp_code','array_report'));
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                foreach ($this->break_loop as $value) {
                    $event->sheet->setBreak('A'.$value, \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);
                }
                
                $event->sheet->getPageSetup()->setPaperSize(9);//A4
                
                $event->sheet->getHeaderFooter()->setOddHeader('&C'.$this->comp->name."\nAP AGEING"."\n"
                .sprintf('FROM DATE %s TO DATE %s',Carbon::parse($this->datefr)->format('d-m-Y'), Carbon::parse($this->dateto)->format('d-m-Y'))."\n"
                .sprintf('FROM %s TO %s',$this->suppcode_from, $this->suppcode_to)
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
}
