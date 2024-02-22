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
    
    public function __construct($suppcode_from,$suppcode_to,$date_ag)
    {
        $this->suppcode_from = $suppcode_from;
        $this->suppcode_to = $suppcode_to;
        $this->date_ag = $date_ag;

        $this->comp = DB::table('sysdb.company')
            ->where('compcode','=',session('compcode'))
            ->first();
    }
    
    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 50,
            'C' => 15,
            'D' => 13,
            'E' => 13,
            'F' => 13,
            'G' => 13,
            'H' => 13,
            'I' => 13,
            'J' => 13,
            'K' => 10,
        ];
    }
    
    public function view(): View
    {
        $suppcode_from = $this->suppcode_from;
        if(empty($this->suppcode_from)){
            $suppcode_from = '%';
        }
        $suppcode_to = $this->suppcode_to;
        $date_ag = Carbon::parse($this->date_ag)->format('Y-m-d');

        $supp_group = DB::table('finance.apacthdr as ap')
                ->select('ap.suppgroup', 'sg.description AS sg_desc')
                ->join('material.suppgroup as sg', function($join){
                    $join = $join->on('sg.suppgroup', '=', 'ap.suppgroup');
                    $join = $join->where('sg.compcode', '=', session('compcode'));
                })
                ->where('ap.compcode','=',session('compcode'))
                ->where('ap.unit',session('unit'))
                ->where('ap.recstatus', '=', 'POSTED')
                ->whereDate('ap.postdate', '<=', $date_ag)
                ->whereBetween('ap.suppcode', [$suppcode_from, $suppcode_to.'%'])
                ->orderBy('ap.suppgroup', 'ASC')
                ->distinct('ap.suppgroup');

        $supp_group = $supp_group->get(['ap.suppgroup','sg.sg_desc']);

        $supp_code = DB::table('finance.apacthdr as ap')
                    ->select('ap.suppcode', 'su.Name AS supplier_name', 'ap.suppgroup')
                    ->join('material.supplier as su', function($join){
                        $join = $join->on('su.SuppCode', '=', 'ap.suppcode');
                        $join = $join->where('su.compcode', '=', session('compcode'));
                    })
                    ->where('ap.compcode','=',session('compcode'))
                    ->where('ap.unit',session('unit'))
                    ->where('ap.recstatus', '=', 'POSTED')
                    ->whereDate('ap.postdate', '<=', $date_ag)
                    ->whereBetween('ap.suppcode', [$suppcode_from, $suppcode_to.'%'])
                    ->orderBy('ap.suppcode', 'ASC')
                    ->distinct('ap.suppcode');

        $supp_code = $supp_code->get(['ap.suppcode','su.supplier_name', 'ap.suppgroup']);

        $array_report = [];

        foreach ($supp_code as $key => $value){
            $apacthdr = DB::table('finance.apacthdr as ap')
                    ->select('ap.compcode','ap.auditno','ap.trantype','ap.doctype','ap.suppcode','ap.suppgroup','su.Name AS supplier_name', 'ap.actdate','ap.document','ap.cheqno','ap.deptcode','ap.amount','ap.outamount','ap.recstatus','ap.payto','ap.recdate','ap.postdate','ap.postuser','ap.category','ap.remarks','ap.adduser','ap.adddate','ap.upduser','ap.upddate','ap.source','ap.idno','ap.unit','ap.pvno','ap.paymode','ap.bankcode','ap.unallocated')
                    ->join('material.supplier as su', function($join){
                        $join = $join->on('su.SuppCode', '=', 'ap.suppcode');
                        $join = $join->where('su.compcode', '=', session('compcode'));
                    })
                    ->where('ap.compcode',session('compcode'))
                    ->where('ap.unit',session('unit'))
                    ->where('ap.recstatus', '=', "POSTED")
                    ->where('ap.suppcode','=',$value->suppcode)
                    ->whereDate('ap.postdate', '<=', $date_ag)
                    ->orderBy('ap.postdate','ASC')
                    // ->where('ap.outamount','>',0)
                    ->get();

            //dd($apacthdr);

            $value->docno = '';
            $value->outamt = 0;
            
            foreach ($apacthdr as $key => $value){
                $apacthdramt = $value->amount;
                //dd($apacthdramt);
                // if($value->trantype == 'IN' || $value->trantype == 'DN') {
                    $apalloc = DB::table('finance.apalloc as al')
                        ->where('al.compcode','=',session('compcode'))
                        ->where('al.docsource','=',$value->source)
                        ->where('al.doctrantype','=',$value->trantype)
                        ->where('al.docauditno','=',$value->auditno)
                        ->where('al.recstatus','=',"POSTED")
                        ->where('al.suppcode','=',$value->suppcode)
                        ->whereDate('al.allocdate', '<=', $date_ag)
                        ->sum('al.allocamount');

                    //dd($apalloc);
                    //calculate o/s amount hdr - allocamt
                    $outamt = Floatval($apacthdramt) - Floatval($apalloc);
                    // dd($apacthdramt);

                // } else {
                //     $apalloc = DB::table('finance.apalloc as al')
                //         ->where('al.compcode','=',session('compcode'))
                //         ->where('al.docsource','=',$value->source)
                //         ->where('al.doctrantype','=',$value->trantype)
                //         ->where('al.docauditno','=',$value->auditno)
                //         ->where('al.recstatus','=',"POSTED")
                //         ->where('al.suppcode','=',$value->suppcode)
                //         ->whereDate('al.allocdate', '<=', $date_ag)
                //         ->sum('al.allocamount');

                //         //calculate o/s amount hdr - allocamt
                //         $outamt = -(Floatval($apacthdramt) - Floatval($apalloc));
                //         //dd($outamt);
                // }
                
                switch ($value->trantype) {
                    case 'IN': //dr
                        $value->docno = $value->document;
                        $value->outamt = $outamt;
                        array_push($array_report, $value);
                        break;
                    case 'DN': //dr
                        $value->docno = $value->document;
                        $value->outamt = $outamt;
                        array_push($array_report, $value);
                        break;
                    case 'CN': //cr
                        $value->docno = $value->document;
                        $value->outamt = $outamt;
                        array_push($array_report, $value);
                        break;
                    case 'PV': //cr
                        $value->docno = str_pad($value->pvno, 5, "0", STR_PAD_LEFT);
                        $value->outamt = $outamt;
                        array_push($array_report, $value);
                        break;
                    default:
                        // code...
                        break;
                }
            }
        }
        return view('finance.AP.APAgeingDtl_Report.APAgeingDtl_Report_excel',compact('array_report', 'supp_group', 'supp_code', 'apacthdr', 'apalloc', 'outamt'));
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {        
                $event->sheet->getPageSetup()->setPaperSize(9);//A4
                
                $event->sheet->getHeaderFooter()->setOddHeader('&C'.$this->comp->name."\nAP AGEING DETAILS"."\n"
                .sprintf('FROM DATE %s',Carbon::parse($this->date_ag)->format('d-m-Y'))."\n"
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
