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

class invoiceListingsExport implements FromView, WithEvents, WithColumnWidths, WithColumnFormatting
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

        $this->comp = DB::table('sysdb.company')
            ->where('compcode','=',session('compcode'))
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
            'A' => 12,
            'B' => 12,
            'C' => 15,
            'D' => 15,
            'E' => 15,
            'F' => 40,
            'G' => 15,
            'H' => 15,
            'I' => 15,
            'J' => 15,
            'K' => 15,
            'L' => 15,
            'M' => 15,
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

        $apacthdr = DB::table('finance.apacthdr as ap')
                    ->select('ap.source','ap.trantype','ap.auditno','ap.document','ap.suppcode','ap.payto','ap.suppgroup','ap.bankcode','ap.paymode','ap.cheqno','ap.cheqdate','ap.actdate','ap.recdate','ap.category','ap.amount','ap.outamount','ap.remarks','ap.postflag','ap.conversion','ap.srcfrom','ap.srcto','ap.deptcode','ap.reconflg','ap.effectdatefr','ap.effectdateto','ap.frequency','ap.refsource','ap.reftrantype','ap.refauditno','ap.pvno','ap.entrydate','ap.recstatus','ap.adduser','ap.adddate','ap.reference','ap.TaxClaimable','ap.unit','ap.allocdate','ap.postuser','ap.postdate','apd.document as apd_document','apd.reference as apd_reference','apd.grnno','apd.dorecno','apd.deptcode as po_deptcode','po.purdate','su.Name')
                    ->join('material.supplier as su', function($join) {
                        $join = $join->on('su.SuppCode', '=', 'ap.suppcode');
                        $join = $join->where('su.compcode', '=', session('compcode'));
                    })
                    ->leftJoin('finance.apactdtl as apd', function($join) {
                        $join = $join->where('apd.compcode', session('compcode'))
                                    ->on('apd.source','ap.source')
                                    ->on('apd.trantype','ap.trantype')
                                    ->on('apd.auditno','ap.auditno')
                                    ->where('apd.recstatus','!=','DELETE');
                    })
                    ->leftJoin('material.purordhd as po', function($join) {
                        $join = $join->where('po.compcode', session('compcode'))
                                    ->on('po.prdept','apd.deptcode')
                                    ->on('po.purordno','apd.reference');
                    })
                    ->where('ap.compcode','=',session('compcode'))
                    ->where('ap.source','AP')
                    ->where('ap.trantype','IN')
                    ->where('ap.recstatus', '=', 'POSTED')
                    ->where('ap.postdate', '>=', $datefr)
                    ->where('ap.postdate', '<=', $dateto);

        if($this->suppcode_from == $this->suppcode_to){
            $apacthdr = $apacthdr->where('ap.suppcode', $suppcode_from);
        }else if($this->suppcode_from == '' && $this->suppcode_to == 'ZZZ'){

        }else{
            $apacthdr = $apacthdr->whereBetween('ap.suppcode', [$suppcode_from, $suppcode_to.'%']);
        }

        $apacthdr = $apacthdr->orderBy('ap.postdate', 'ASC')
                    ->get();

        $supplier = $apacthdr->unique('suppcode');

        $datefr2 = Carbon::parse($this->datefr)->format('d-m-Y');
        $dateto2 = Carbon::parse($this->dateto)->format('d-m-Y');

        $title1 = $this->comp->name;
        $title2 = 'Invoice & Debit Note Listing';
        $title3 = 'From '.$datefr2.' to '.$dateto2;

        // dd($supplier);
        
        //$this->break_loop = $break_loop;

        return view('finance.AP.invoiceAP.invoiceListingsExport',compact('apacthdr','supplier','title1','title2','title3'));
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // foreach ($this->break_loop as $value) {
                //     $event->sheet->setBreak('A'.$value, \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);
                // }
                
                $event->sheet->getPageSetup()->setPaperSize(9);//A4
                
                $event->sheet->getHeaderFooter()->setOddHeader('&C'.$this->comp->name."\nAP AGEING SUMMARY"."\n"
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
