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

class tui_tuo_report_Export implements FromView, WithEvents, WithColumnWidths, WithColumnFormatting
{
    
    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function __construct($datefr,$dateto)
    {

        $this->datefr = $datefr;
        $this->dateto = $dateto;
        $this->comp = DB::table('sysdb.company')
            ->where('compcode','=',session('compcode'))
            ->first();
    }

    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }
    
    public function columnWidths(): array
    {
        return [
            'A' => 40,
            'B' => 20,
            'C' => 40,
            'D' => 40,
            'E' => 15,
            'F' => 15,
        ];
    }
    
    public function view(): View{
        
        $datefr = Carbon::parse($this->datefr)->format('Y-m-d');
        $dateto = Carbon::parse($this->dateto)->format('Y-m-d');

        $ivtmphd = DB::table('material.ivtmphd as iv_hd')
                    ->select('iv_hd.recno','iv_hd.source','iv_hd.reference','iv_hd.txndept','iv_hd.trantype','iv_hd.docno','iv_hd.srcdocno','iv_hd.sndrcvtype','iv_hd.sndrcv','iv_hd.trandate','iv_hd.ivreqno','iv_hd.amount as header_amt','iv_hd.respersonid','iv_hd.remarks','iv_hd.recstatus','iv_hd.postedby','iv_hd.postdate','iv_hd.unit','iv_hd.adduser','iv_dt.recno','iv_dt.lineno_','iv_dt.ivreqno','iv_dt.reqlineno','iv_dt.reqdept','iv_dt.itemcode','iv_dt.uomcode','iv_dt.uomcoderecv','iv_dt.txnqty','iv_dt.qtyonhandrecv','iv_dt.netprice','iv_dt.productcat','iv_dt.remarks','iv_dt.qtyonhand','iv_dt.batchno','iv_dt.amount','iv_dt.recstatus','iv_dt.unit','iv_dt.qtyrequest','iv_dt.adduser as adduser_dt','sp_fr.description as txndept_desc','sp_to.description as sndrcv_desc','pr.description as itemcode_desc')
                    ->whereBetween('iv_hd.trandate', [$datefr, $dateto])
                    ->leftjoin('material.ivtmpdt as iv_dt', function($join) {
                        $join = $join->on('iv_dt.recno', '=', 'iv_hd.recno');
                        $join = $join->where('iv_dt.recstatus', '!=', 'DELETE');
                        $join = $join->where('iv_dt.compcode', '=', session('compcode'));
                    })
                    ->leftjoin('material.product as pr', function($join) {
                        $join = $join->on('pr.itemcode', '=', 'iv_dt.itemcode');
                        $join = $join->on('pr.uomcode', '=', 'iv_dt.uomcode');
                        $join = $join->where('pr.compcode', '=', session('compcode'));
                    })
                    ->leftjoin('sysdb.department as sp_fr', function($join) {
                        $join = $join->on('sp_fr.deptcode', '=', 'iv_hd.txndept');
                        $join = $join->where('sp_fr.compcode', '=', session('compcode'));
                    })
                    ->leftjoin('sysdb.department as sp_to', function($join) {
                        $join = $join->on('sp_to.deptcode', '=', 'iv_hd.sndrcv');
                        $join = $join->where('sp_to.compcode', '=', session('compcode'));
                    })
                    ->where('iv_hd.compcode','=',session('compcode'))
                    // ->where('ap.unit',session('unit'))
                    ->where('iv_hd.recstatus', '=', 'POSTED')
                    ->where('iv_hd.txndept', '=', session('deptcode'))
                    ->orWhere(function ($ivtmphd){
                        $ivtmphd
                            ->where('iv_hd.trantype', '=', 'TUI')
                            ->where('iv_hd.trantype', '=', 'TUO');
                    })
                    ->orderBy('iv_hd.recno', 'ASC')
                    ->get();

        // dd($this->getQueries($ivtmphd));

        $iv_hd = $ivtmphd->unique('recno');

        return view('material.inventoryTransaction.tui_tuo_report_Export',compact('iv_hd', 'ivtmphd'));
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {        
                $event->sheet->getPageSetup()->setPaperSize(9);//A4
                
                $event->sheet->getHeaderFooter()->setOddHeader('&C'.$this->comp->name."\nTUI - TUO POSTED LISTING"
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

    public static function getQueries($builder){
        $addSlashes = str_replace('?', "'?'", $builder->toSql());
        return vsprintf(str_replace('?', '%s', $addSlashes), $builder->getBindings());
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
