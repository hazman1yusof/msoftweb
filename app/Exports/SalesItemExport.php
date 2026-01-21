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

class SalesItemExport implements FromView, WithEvents, WithColumnWidths, WithColumnFormatting
{
    
    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function __construct($datefr,$dateto,$deptcode)
    {
        $this->datefr = $datefr;
        $this->dateto = $dateto;
        $this->deptcode = $deptcode;
        $this->dbacthdr_len=0;
        
        $this->comp = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_NUMBER,
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
            'B' => 18,
            'C' => 12,
            'D' => 40,
            'E' => 10,
            'F' => 13,
            'G' => 13,
            'H' => 13,
            'I' => 13,
        ];
    }
    
    public function view(): View
    {
        $datefr = Carbon::parse($this->datefr)->format('Y-m-d');
        $dateto = Carbon::parse($this->dateto)->format('Y-m-d');
        $deptcode = $this->deptcode;
        
        // $billdet = DB::table('hisdb.billdet as b', 'hisdb.chgmast as c', 'debtor.dbacthdr as d')
        //             ->select('b.idno', 'b.compcode', 'b.trxdate', 'b.chgcode', 'b.quantity', 'b.amount', 'b.invno', 'b.taxamount', 'c.description AS cm_desc', 'd.trantype','d.source','d.debtorcode AS debtorcode' )
        //             ->leftJoin('hisdb.chgmast as c', function($join){
        //                 $join = $join->on('c.chgcode', '=', 'b.chgcode')
        //                             ->where('c.compcode', '=', session('compcode'));
        //             })
        //             ->join('debtor.dbacthdr as d', function($join){
        //                 $join = $join->on('d.invno', '=', 'b.invno')
        //                             ->where('d.compcode', '=', session('compcode'))
        //                             ->where('d.source', '=', 'PB')
        //                             ->where('d.trantype', '=', 'IN');
        //             })
        //             ->where('b.compcode','=',session('compcode'))
        //             ->where('b.recstatus','=','POSTED')
        //             ->where('b.amount','!=','0')
        //             ->whereBetween('b.trxdate', [$datefr, $dateto])
        //             ->orderBy('b.trxdate','ASC')
        //             ->get();

        // dd($billdet);
        
        $dbacthdr = DB::table('debtor.dbacthdr as d')
                    ->select('d.debtorcode', 'dm.name AS dm_desc', 'd.invno','d.mrn','b.idno', 'b.compcode', 'b.trxdate', 'b.chgcode', 'b.quantity', 'b.amount', 'b.invno', 'b.taxamount', 'c.description AS cm_desc', 'd.trantype','d.source','d.debtorcode AS debtorcode','pm.Name as pm_name','p.avgcost as costprice','c.chgtype')
                    ->leftJoin('debtor.debtormast as dm', function($join){
                        $join = $join->on('dm.debtorcode', '=', 'd.debtorcode')
                                    ->where('dm.compcode', '=', session('compcode'));
                    })
                    ->join('hisdb.chargetrx as b', function($join){
                        $join = $join->on('b.invno', '=', 'd.invno')
                                    ->where('b.compcode', '=', session('compcode'));
                    })
                    ->leftJoin('material.product as p', function($join){
                        $join = $join->on('p.itemcode', '=', 'b.chgcode')
                                    ->where('p.compcode', '=', session('compcode'));
                    })
                    ->leftJoin('hisdb.chgmast as c', function($join){
                        $join = $join->on('c.chgcode', '=', 'b.chgcode')
                                    ->on('c.uom', '=', 'b.uom')
                                    // ->where('c.unit', '=', session('unit'))
                                    ->where('c.compcode', '=', session('compcode'));
                    })
                    ->leftJoin('hisdb.pat_mast as pm', function($join){
                        $join = $join->on("pm.newmrn", '=', 'd.mrn');    
                        $join = $join->where("pm.compcode", '=', session('compcode'));
                    })
                    ->where('d.compcode','=',session('compcode'))
                    ->where('d.source', '=', 'PB')
                    ->where('d.trantype', '=', 'IN')
                    ->where('d.recstatus', '=', 'POSTED');
                    if(!empty($deptcode)){
                        $dbacthdr = $dbacthdr
                                    ->where('d.deptcode', '=', $deptcode);
                    }
                    $dbacthdr = $dbacthdr->where('d.amount','!=','0')
                    ->orderBy('d.debtorcode','DESC')
                    ->orderBy('d.invno','DESC')
                    ->whereBetween('b.trxdate', [$datefr, $dateto])
                    ->get();
                    
        // dd($this->getQueries($dbacthdr));

        $invno_array = [];
        foreach ($dbacthdr as $obj) {
            if(!in_array($obj->invno, $invno_array)){
                array_push($invno_array, $obj->invno);
            }
        }

        foreach ($dbacthdr as $obj) {
            if($obj->costprice == ''){
                $obj->costprice = 0.00;
            }

            $obj->costprice = $obj->costprice * $obj->quantity;
            // $chgprice_obj = DB::table('hisdb.chgprice as cp')
            //     ->where('cp.compcode', '=', session('compcode'))
            //     ->where('cp.chgcode', '=', $obj->chgcode)
            //     // ->where('cp.uom', '=', $value->uom)
            //     ->whereDate('cp.effdate', '<=', Carbon::now("Asia/Kuala_Lumpur"))
            //     ->orderBy('cp.effdate','desc');

            // if($chgprice_obj->exists()){
            //     $chgprice_obj = $chgprice_obj->first();
            //     $obj->costprice = $chgprice_obj->costprice * $obj->quantity;
            // }else{
            //     $obj->costprice = 0.00;
            // }
        }
        
        // $dbacthdr = $dbacthdr->get(['dh.debtorcode']);
        
        // $totalAmount = $billdet->sum('amount');
        
        // $totamount_expld = explode(".", (float)$totalAmount);
        
        // $totamt_eng_rm = $this->convertNumberToWordENG($totamount_expld[0])."";
        // $totamt_eng = $totamt_eng_rm." ONLY";
        
        // if(count($totamount_expld) > 1){
        //     $totamt_eng_sen = $this->convertNumberToWordENG($totamount_expld[1])." CENT";
        //     $totamt_eng = $totamt_eng_rm.$totamt_eng_sen." ONLY";
        // }
        $title1 = 'Sales by Item Report for dept: '.$deptcode;
        $title2 = 'Date From '.$datefr.' To '.$dateto;
        
        return view('finance.SalesItem_Report.SalesItem_Report_excel',compact('dbacthdr','invno_array','title1','title2'));
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getPageSetup()->setPaperSize(9);//A4
                
                $event->sheet->getHeaderFooter()->setOddHeader('&C'.$this->comp->name."\nSALES BY ITEM"."\n".sprintf('FROM DATE %s TO DATE %s',Carbon::parse($this->datefr)->format('d-m-Y'), Carbon::parse($this->dateto)->format('d-m-Y')).'&L'.'PRINTED BY : '.session('username')."\nPAGE : &P/&N".'&R'.'PRINTED DATE : '.Carbon::now("Asia/Kuala_Lumpur")->format('d-m-Y')."\n".'PRINTED TIME : '.Carbon::now("Asia/Kuala_Lumpur")->format('H:i'));
                
                $event->sheet->getPageMargins()->setTop(1);
                
                $event->sheet->getPageSetup()->setRowsToRepeatAtTop([1,1]);
                $event->sheet->getStyle('A:G')->getAlignment()->setWrapText(true);
                $event->sheet->getPageSetup()->setFitToWidth(1);
                $event->sheet->getPageSetup()->setFitToHeight(0);
            },
        ];
    }
    
    public function convertNumberToWordENG($num = false)
    {
        $num = str_replace(array(',', ' '), '' , trim($num));
        if(! $num) {
            return false;
        }
        $num = (int) $num;
        $words = array();
        $list1 = array('', 'ONE', 'TWO', 'THREE', 'FOUR', 'FIVE', 'SIX', 'SEVEN', 'EIGHT', 'NINE', 'TEN', 'ELEVEN',
            'TWELVE', 'THIRTEEN', 'FOURTEEN', 'FIFTEEN', 'SIXTEEN', 'SEVENTEEN', 'EIGHTEEN', 'NINETEEN'
        );
        $list2 = array('', 'TENTH', 'TWENTY', 'THIRTY', 'FORTY', 'FIFTY', 'SIXTY', 'SEVENTY', 'EIGHTY', 'NINETY', 'HUNDRED');
        $list3 = array('', 'THOUSAND', 'MILLION', 'BILLION', 'TRILLION', 'quadrillion', 'quintillion', 'sextillion', 'septillion',
            'octillion', 'nonillion', 'decillion', 'undecillion', 'duodecillion', 'tredecillion', 'quattuordecillion',
            'quindecillion', 'sexdecillion', 'septendecillion', 'octodecillion', 'novemdecillion', 'vigintillion'
        );
        $num_length = strlen($num);
        $levels = (int) (($num_length + 2) / 3);
        $max_length = $levels * 3;
        $num = substr('00' . $num, -$max_length);
        $num_levels = str_split($num, 3);
        for ($i = 0; $i < count($num_levels); $i++) {
            $levels--;
            $hundreds = (int) ($num_levels[$i] / 100);
            $hundreds = ($hundreds ? '' .$list1[$hundreds].' HUNDRED' .' ' : '');
            $tens = (int) ($num_levels[$i] % 100);
            $singles = '';
            if ( $tens < 20 ) {
                $tens = ($tens ? '' . $list1[$tens] .' ' : '' );
            } else {
                $tens = (int)($tens / 10);
                $tens = '' . $list2[$tens] . ' ';
                $singles = (int) ($num_levels[$i] % 10);
                $singles = '' . $list1[$singles] . ' ';
            }
            $words[] = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_levels[$i] ) ) ? '' . $list3[$levels] .' ' : '' );
        } //end for loop
        $commas = count($words);
        if ($commas > 1) {
            $commas = $commas - 1;
        }
        return implode(' ', $words);
    }
    
    public static function getQueries($builder){
        $addSlashes = str_replace('?', "'?'", $builder->toSql());
        return vsprintf(str_replace('?', '%s', $addSlashes), $builder->getBindings());
    }
}
