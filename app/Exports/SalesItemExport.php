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

class SalesItemExport implements FromView, WithEvents, WithColumnWidths
{
    
    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function __construct($datefr,$dateto)
    {
        $this->datefr = $datefr;
        $this->dateto = $dateto;
        $this->dbacthdr_len=0;
        
        $this->comp = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();
    }
    
    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 13,
            'C' => 35,
            'D' => 10,
            'E' => 12,
           
        ];
    }
    
    public function view(): View
    {
        $datefr = Carbon::parse($this->datefr)->format('Y-m-d');
        $dateto = Carbon::parse($this->dateto)->format('Y-m-d');
        
        $billdet = DB::table('hisdb.billdet as b', 'hisdb.chgmast as c', 'debtor.dbacthdr as d')
                    ->select('b.idno', 'b.compcode', 'b.trxdate', 'b.chgcode', 'b.quantity', 'b.amount', 'b.invno', 'c.description AS cm_desc', 'd.debtorcode AS debtorcode' )
                    ->leftJoin('hisdb.chgmast as c', function($join){
                        $join = $join->on('c.chgcode', '=', 'b.chgcode')
                                    ->where('c.compcode', '=', session('compcode'));
                    })
                    ->leftJoin('debtor.dbacthdr as d', function($join){
                        $join = $join->on('d.invno', '=', 'b.invno')
                                    ->where('d.compcode', '=', session('compcode'));
                    })
                    ->where('b.compcode','=',session('compcode'))
                    ->where('b.recstatus','=','POSTED')
                    ->where('b.amount','!=','0')
                    ->whereBetween('b.trxdate', [$datefr, $dateto])
                    ->orderBy('b.trxdate','ASC')
                    ->get();
        
        $dbacthdr = DB::table('debtor.dbacthdr as dh', 'debtor.debtormast as dm')
                    ->select('dh.debtorcode', 'dm.name AS dm_desc') 
                    ->leftJoin('debtor.debtormast as dm', function($join){
                        $join = $join->on('dm.debtorcode', '=', 'dh.debtorcode')
                                    ->where('dm.compcode', '=', session('compcode'));
                    })
                    ->where('dh.compcode','=',session('compcode'))
                    ->where('dh.trantype', '=', 'IN')
                    ->where('dh.recstatus', '=', 'POSTED')
                    ->where('dh.amount','!=','0')
                    ->whereBetween('dh.entrydate', [$datefr, $dateto])
                    ->distinct('dh.debtorcode');
        
        $dbacthdr = $dbacthdr->get(['dh.debtorcode']);
        
        $totalAmount = $billdet->sum('amount');
        
        $totamount_expld = explode(".", (float)$totalAmount);
        
        $totamt_eng_rm = $this->convertNumberToWordENG($totamount_expld[0])."";
        $totamt_eng = $totamt_eng_rm." ONLY";
        
        if(count($totamount_expld) > 1){
            $totamt_eng_sen = $this->convertNumberToWordENG($totamount_expld[1])." CENT";
            $totamt_eng = $totamt_eng_rm.$totamt_eng_sen." ONLY";
        }
        
        return view('finance.SalesItem_Report.SalesItem_Report_excel',compact('billdet','dbacthdr','totamt_eng','totalAmount'));
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
    
}
