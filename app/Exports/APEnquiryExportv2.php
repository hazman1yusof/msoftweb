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
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Exportable;
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

class ApEnquiryExportv2 implements FromView, ShouldQueue, WithEvents, WithColumnWidths, WithColumnFormatting
{
    
    use Exportable;
    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function __construct($date,$suppcode_from,$suppcode_to)
    {
        
        $this->date = Carbon::parse($date)->format('Y-m-d');
        $this->suppcode_from = $suppcode_from;
        if(empty($suppcode_from)){
            $this->suppcode_from = '%';
        }
        $this->suppcode_to = $suppcode_to;

        $this->groupOne = 30;
        $this->groupTwo = 60;
        $this->groupThree = 90;
        $this->groupFour = 120;

        $this->grouping = [];
        $this->grouping_tot = [];
        $this->grouping[0] = 0;
        $this->grouping_tot[0] = 0;
        if(!empty($this->groupOne)){
            $this->grouping[1] = $this->groupOne;
            $this->grouping_tot[1] = 0;
        }
        if(!empty($this->groupTwo)){
            $this->grouping[2] = $this->groupTwo;
            $this->grouping_tot[2] = 0;
        }
        if(!empty($this->groupThree)){
            $this->grouping[3] = $this->groupThree;
            $this->grouping_tot[3] = 0;
        }
        if(!empty($this->groupFour)){
            $this->grouping[4] = $this->groupFour;
            $this->grouping_tot[4] = 0;
        }

        $this->comp = DB::table('sysdb.company')
            ->where('compcode','=',session('compcode'))
            ->first();
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_TEXT,
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'H' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'I' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'J' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }
    
    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 15,
            'C' => 35,
            'D' => 35,
            'E' => 15,
            'F' => 15,
            'G' => 15,
            'H' => 15,
            'I' => 18,
            'J' => 15,
        ];
    }
    
    public function view(): View
    {

        $date = $this->date;
        $date_asof = Carbon::parse($this->date)->format('d-m-Y');
        $suppcode_from = $this->suppcode_from;
        $suppcode_to = $this->suppcode_to;
        $grouping = $this->grouping;
        $grouping_tot = $this->grouping_tot;

        $apacthdr = DB::table('finance.apacthdr as ap')
                    ->select('ap.suppcode','ap.source','ap.trantype','ap.auditno','ap.amount','ap.postdate','ap.remarks','ap.document','su.name','su.suppgroup','sg.description','ap.unit','su.addr1','su.addr2','su.addr3','su.addr4','ap.reference')
                    ->join('material.supplier as su', function($join) {
                        $join = $join->on('su.SuppCode', '=', 'ap.suppcode');
                        $join = $join->where('su.compcode', '=', session('compcode'));
                        $join = $join->whereNotIn('su.suppgroup', ['ukmh','sa']);
                    })
                    ->leftjoin('material.suppgroup as sg', function($join) {
                        $join = $join->on('sg.suppgroup', '=', 'su.suppgroup');
                        $join = $join->where('sg.compcode', '=', session('compcode'));
                    })
                    ->where('ap.trantype','!=',['PD'])
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
            $value->auditno_ = $value->source.'-'.$value->trantype.'-'.str_pad($value->auditno, 7, "0", STR_PAD_LEFT);;
            
            $alloc_sum = DB::table('finance.apalloc')
                    ->where('compcode', '=', session('compcode'))
                    ->where('suppcode', '=', $value->suppcode)
                    ->where('refsource', '=', $value->source)
                    ->where('reftrantype', '=', $value->trantype)
                    ->where('refauditno', '=', $value->auditno)
                    ->where('recstatus', '=', "POSTED")
                    ->whereDate('allocdate', '<=', $date)
                    ->sum('allocamount');

            $alloc_sum2 = DB::table('finance.apalloc')
                    ->where('compcode', '=', session('compcode'))
                    ->where('suppcode', '=', $value->suppcode)
                    ->where('docsource', '=', $value->source)
                    ->where('doctrantype', '=', $value->trantype)
                    ->where('docauditno', '=', $value->auditno)
                    ->where('recstatus', '=', "POSTED")
                    ->whereDate('allocdate', '<=', $date)
                    ->sum('allocamount');

            $newamt = $hdr_amount - $alloc_sum - $alloc_sum2;

            if(in_array($value->trantype, ['CN','PV'])){
                $newamt = $newamt * -1;
            }

            if(round($newamt,2) != 0.00){
                $value->newamt = $newamt;
                array_push($array_report, $value);
            }

            $grouping_tot[$value->group] = $grouping_tot[$value->group] + $newamt;
        }

        // dd($grouping);
        $title = "STATEMENT LISTING";
        $suppcode = collect($array_report)->unique('suppcode');

        $date_at = Carbon::createFromFormat('Y-m-d',$this->date)->format('d-m-Y');
        $datenow = Carbon::now("Asia/Kuala_Lumpur")->format('d-m-Y');
        
        $company = DB::table('sysdb.company')
                    ->where('compcode', '=', session('compcode'))
                    ->first();

        // dd($array_report);

        return view('finance.AP.APAgeingDtl_Report.APAgeingDtl_Report_excel_stmt',compact('suppcode', 'array_report', 'grouping','grouping_tot','title','company','date_asof','datenow'));
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
