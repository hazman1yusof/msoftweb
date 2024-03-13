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

class APAgeingDtlExport implements FromView, WithEvents, WithColumnWidths
{
    
    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function __construct($date,$suppcode_from,$suppcode_to,$groupOne,$groupTwo,$groupThree,$groupFour,$groupFive,$groupSix)
    {
        
        $this->date = Carbon::parse($date)->format('Y-m-d');
        $this->suppcode_from = $suppcode_from;
        if(empty($suppcode_from)){
            $this->suppcode_from = '%';
        }
        $this->suppcode_to = $suppcode_to;

        $this->groupOne = $groupOne;
        $this->groupTwo = $groupTwo;
        $this->groupThree = $groupThree;
        $this->groupFour = $groupFour;
        $this->groupFive = $groupFive;
        $this->groupSix = $groupSix;

        $this->grouping = [];
        $this->grouping[0] = 0;
        if(!empty($this->groupOne)){
            $this->grouping[1] = $this->groupOne;
        }
        if(!empty($this->groupTwo)){
            $this->grouping[2] = $this->groupTwo;
        }
        if(!empty($this->groupThree)){
            $this->grouping[3] = $this->groupThree;
        }
        if(!empty($this->groupFour)){
            $this->grouping[4] = $this->groupFour;
        }
        if(!empty($this->groupFive)){
            $this->grouping[5] = $this->groupFive;
        }
        if(!empty($this->groupSix)){
            $this->grouping[6] = $this->groupSix;
        }

        $this->comp = DB::table('sysdb.company')
            ->where('compcode','=',session('compcode'))
            ->first();
    }
    
    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 40,
            'C' => 15,
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
        ];
    }
    
    public function view(): View
    {
        $date = $this->date;
        $suppcode_from = $this->suppcode_from;
        $suppcode_to = $this->suppcode_to;
        $grouping = $this->grouping;

        $apacthdr = DB::table('finance.apacthdr as ap')
                    ->select('ap.suppcode','ap.source','ap.trantype','ap.auditno','ap.amount','ap.postdate','ap.remarks','ap.document','su.name','su.suppgroup','sg.description','ap.unit')
                    ->join('material.supplier as su', function($join) {
                        $join = $join->on('su.SuppCode', '=', 'ap.suppcode');
                        $join = $join->where('su.compcode', '=', session('compcode'));
                    })
                    ->leftjoin('material.suppgroup as sg', function($join) {
                        $join = $join->on('sg.suppgroup', '=', 'su.suppgroup');
                        $join = $join->where('sg.compcode', '=', session('compcode'));
                    })
                    ->where('ap.compcode','=',session('compcode'))
                    // ->where('ap.unit',session('unit'))
                    ->where('ap.recstatus', '=', 'POSTED')
                    ->whereDate('ap.postdate', '<=', $date)
                    ->whereBetween('su.suppcode', [$suppcode_from, $suppcode_to.'%'])
                    ->orderBy('ap.suppcode', 'ASC')
                    ->get();

        $array_report = [];

        foreach ($apacthdr as $key => $value){
            $value->newamt = 0;

            $hdr_amount = $value->amount;
            
            // to calculate interval (days)
            $datetime1 = new DateTime($date);
            $datetime2 = new DateTime($value->postdate);
            
            $interval = $datetime1->diff($datetime2);
            $days = $interval->format('%a');
            $value->group = $this->assign_grouping($grouping,$days);
            $value->days = $days;
            
            $alloc_sum = DB::table('finance.apalloc')
                    ->where('compcode', '=', session('compcode'))
                    ->where('suppcode', '=', $value->suppcode)
                    ->where('refsource', '=', $value->source)
                    ->where('reftrantype', '=', $value->trantype)
                    ->where('refauditno', '=', $value->auditno)
                    ->where('recstatus', '=', "POSTED")
                    ->whereDate('allocdate', '<=', $date)
                    ->sum('allocamount');

            $newamt = $hdr_amount - $alloc_sum;

            if(floatval($newamt) != 0.00){
                $value->newamt = $newamt;
                array_push($array_report, $value);
            }
        }

        // dd($grouping);

        $suppgroup = collect($array_report)->unique('suppgroup');
        $suppcode = collect($array_report)->unique('suppcode');

        return view('finance.AP.APAgeingDtl_Report.APAgeingDtl_Report_excel',compact('array_report', 'suppgroup', 'suppcode', 'array_report', 'grouping'));
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {        
                $event->sheet->getPageSetup()->setPaperSize(9);//A4
                
                $event->sheet->getHeaderFooter()->setOddHeader('&C'.$this->comp->name."\nAP AGEING DETAILS"."\n"
                .sprintf('FROM DATE %s',Carbon::parse($this->date)->format('d-m-Y'))."\n"
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
