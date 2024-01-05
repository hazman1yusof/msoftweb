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

class DailyBillCollectionExport implements FromView, WithEvents, WithColumnWidths
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
            'A' => 12,
            'B' => 15,
            'C' => 25,
            'D' => 13,
            'E' => 10,
            'F' => 10,
            'G' => 10,
            'H' => 10,
            'I' => 10,
            'J' => 15,
            'K' => 15,
        ];
    }
    
    public function view(): View
    {
        $datefr = Carbon::parse($this->datefr)->format('Y-m-d');
        $dateto = Carbon::parse($this->dateto)->format('Y-m-d');
        
        $dbacthdr = DB::table('debtor.dbacthdr as dh', 'debtor.debtormast as dm')
                    ->select('dh.idno', 'dh.source', 'dh.trantype', 'dh.auditno', 'dh.lineno_', 'dh.amount', 'dh.outamount', 'dh.recstatus', 'dh.entrydate', 'dh.entrytime', 'dh.entryuser', 'dh.reference', 'dh.recptno', 'dh.paymode', 'dh.tillcode', 'dh.tillno', 'dh.debtortype', 'dh.debtorcode', 'dh.payercode', 'dh.billdebtor', 'dh.remark', 'dh.mrn', 'dh.episno', 'dh.authno', 'dh.expdate', 'dh.adddate', 'dh.adduser', 'dh.upddate', 'dh.upduser', 'dh.deldate', 'dh.deluser', 'dh.epistype', 'dh.cbflag', 'dh.conversion', 'dh.payername', 'dh.hdrtype', 'dh.currency', 'dh.rate', 'dh.unit', 'dh.invno', 'dh.paytype', 'dh.bankcharges', 'dh.RCCASHbalance', 'dh.RCOSbalance', 'dh.RCFinalbalance', 'dh.PymtDescription', 'dh.orderno', 'dh.ponum', 'dh.podate', 'dh.termdays', 'dh.termmode', 'dh.deptcode', 'dh.posteddate', 'dh.approvedby', 'dh.approveddate', 'dm.debtorcode as dm_debtorcode', 'dm.name as dm_name')
                    ->leftJoin('debtor.debtormast as dm', function($join){
                        $join = $join->on('dm.debtorcode', '=', 'dh.payercode')
                                    ->where('dm.compcode', '=', session('compcode'));
                    })
                    ->where('dh.compcode','=',session('compcode'))
                    ->where('dh.source', '=', 'PB')
                    ->where('dh.trantype', '=', 'IN')
                    ->whereBetween('dh.posteddate', [$datefr, $dateto])
                    ->orderBy('dh.posteddate','ASC')
                    ->get();
        
        $array_report = [];
        foreach ($dbacthdr as $key => $value){
            $value->card_amount = 0;
            $value->cheque_amount = 0;
            $value->cash_amount = 0;
            $value->tt_amount = 0;
            $value->cn_amount = 0;
            array_push($array_report, $value);
            
            $dbacthdr_ex = DB::table('debtor.dbacthdr as dh', 'debtor.debtormast as dm')
                            ->select('dh.idno', 'dh.source', 'dh.trantype', 'dh.auditno', 'dh.lineno_', 'dh.amount', 'dh.outamount', 'dh.recstatus', 'dh.entrydate', 'dh.entrytime', 'dh.entryuser', 'dh.reference', 'dh.recptno', 'dh.paymode', 'dh.tillcode', 'dh.tillno', 'dh.debtortype', 'dh.debtorcode', 'dh.payercode', 'dh.billdebtor', 'dh.remark', 'dh.mrn', 'dh.episno', 'dh.authno', 'dh.expdate', 'dh.adddate', 'dh.adduser', 'dh.upddate', 'dh.upduser', 'dh.deldate', 'dh.deluser', 'dh.epistype', 'dh.cbflag', 'dh.conversion', 'dh.payername', 'dh.hdrtype', 'dh.currency', 'dh.rate', 'dh.unit', 'dh.invno', 'dh.paytype', 'dh.bankcharges', 'dh.RCCASHbalance', 'dh.RCOSbalance', 'dh.RCFinalbalance', 'dh.PymtDescription', 'dh.orderno', 'dh.ponum', 'dh.podate', 'dh.termdays', 'dh.termmode', 'dh.deptcode', 'dh.posteddate', 'dh.approvedby', 'dh.approveddate', 'dm.debtorcode as dm_debtorcode', 'dm.name as dm_name')
                            ->leftJoin('debtor.debtormast as dm', function($join){
                                $join = $join->on('dm.debtorcode', '=', 'dh.payercode')
                                            ->where('dm.compcode', '=', session('compcode'));
                            })
                            ->where('dh.payercode', '=', $value->payercode)
                            ->where('dh.entrydate', '=', $value->entrydate)
                            ->where('dh.compcode','=',session('compcode'))
                            ->where('dh.source', '=', 'PB')
                            ->whereIn('dh.trantype',['RC','RD','CN']);
            
            if($dbacthdr_ex->exists()){
                foreach ($dbacthdr_ex->get() as $dbacthdr_exkey => $dbacthdr_exvalue) {
                    $dbacthdr_exvalue->card_amount = 0;
                    $dbacthdr_exvalue->cheque_amount = 0;
                    $dbacthdr_exvalue->cash_amount = 0;
                    $dbacthdr_exvalue->tt_amount = 0;
                    $dbacthdr_exvalue->cn_amount = 0;
                    switch ($dbacthdr_exvalue->trantype) {
                        case 'CN':
                            $dbacthdr_exvalue->cn_amount = $dbacthdr_exvalue->amount;
                            array_push($array_report, $dbacthdr_exvalue);
                            break;
                        case 'RC':
                            switch ($dbacthdr_exvalue->paytype) {
                                case '#F_TAB-CARD':
                                    $dbacthdr_exvalue->card_amount = $dbacthdr_exvalue->amount;
                                    // $dbacthdr_exvalue->amount = $dbacthdr_exvalue->amount + $dbacthdr_exvalue->outamount;
                                    break;
                                case '#F_TAB-CHEQUE':
                                    $dbacthdr_exvalue->cheque_amount = $dbacthdr_exvalue->amount;
                                    // $dbacthdr_exvalue->amount = $dbacthdr_exvalue->amount + $dbacthdr_exvalue->outamount;
                                    break;
                                case '#F_TAB-CASH':
                                    $dbacthdr_exvalue->cash_amount = $dbacthdr_exvalue->amount;
                                    // $dbacthdr_exvalue->amount = $dbacthdr_exvalue->amount + $dbacthdr_exvalue->outamount;
                                    break;
                                case '#F_TAB-DEBIT':
                                    $dbacthdr_exvalue->tt_amount = $dbacthdr_exvalue->amount;
                                    // $dbacthdr_exvalue->amount = $dbacthdr_exvalue->amount + $dbacthdr_exvalue->outamount;
                                    break;
                            }
                            array_push($array_report, $dbacthdr_exvalue);
                            break;
                        case 'RD':
                            switch ($dbacthdr_exvalue->paytype) {
                                case '#F_TAB-CARD':
                                    $dbacthdr_exvalue->card_amount = $dbacthdr_exvalue->amount;
                                    // $dbacthdr_exvalue->amount = $dbacthdr_exvalue->amount + $dbacthdr_exvalue->outamount;
                                    break;
                                case '#F_TAB-CHEQUE':
                                    $dbacthdr_exvalue->cheque_amount = $dbacthdr_exvalue->amount;
                                    // $dbacthdr_exvalue->amount = $dbacthdr_exvalue->amount + $dbacthdr_exvalue->outamount;
                                    break;
                                case '#F_TAB-CASH':
                                    $dbacthdr_exvalue->cash_amount = $dbacthdr_exvalue->amount;
                                    // $dbacthdr_exvalue->amount = $dbacthdr_exvalue->amount + $dbacthdr_exvalue->outamount;
                                    break;
                                case '#F_TAB-DEBIT':
                                    $dbacthdr_exvalue->tt_amount = $dbacthdr_exvalue->amount;
                                    // $dbacthdr_exvalue->amount = $dbacthdr_exvalue->amount + $dbacthdr_exvalue->outamount;
                                    break;
                            }
                            array_push($array_report, $dbacthdr_exvalue);
                            break;
                        default:
                            // code...
                            break;
                    }
                }
            }
        }
        
        $title = "DAILY BILL AND COLLECTION";
        
        $company = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();
        
        // $totamount_expld = explode(".", (float)$totalAmount);
        
        // $totamt_eng_rm = $this->convertNumberToWordENG($totamount_expld[0])."";
        // $totamt_eng = $totamt_eng_rm." ONLY";
        
        // if(count($totamount_expld) > 1){
        //     $totamt_eng_sen = $this->convertNumberToWordENG($totamount_expld[1])." CENT";
        //     $totamt_eng = $totamt_eng_rm.$totamt_eng_sen." ONLY";
        // }
        
        return view('finance.AR.DailyBillCollection_Report.DailyBillCollection_Report_excel',compact('array_report','title','company'));
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getPageSetup()->setPaperSize(9);//A4
                
                $event->sheet->getHeaderFooter()->setOddHeader('&C'.$this->comp->name."\nDAILY BILL AND COLLECTION"."\n".sprintf('FROM DATE %s TO DATE %s',$this->datefr, $this->dateto).'&L'.'PRINTED BY : '.session('username')."\nPAGE : &P/&N".'&R'.'PRINTED DATE : '.Carbon::now("Asia/Kuala_Lumpur")->format('d-m-Y')."\n".'PRINTED TIME : '.Carbon::now("Asia/Kuala_Lumpur")->format('H:i'));
                
                $event->sheet->getPageMargins()->setTop(1);
                
                $event->sheet->getPageSetup()->setRowsToRepeatAtTop([1,1]);
                $event->sheet->getStyle('A:H')->getAlignment()->setWrapText(true);
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
    
    public function getQueries($builder){
        $addSlashes = str_replace('?', "'?'", $builder->toSql());
        return vsprintf(str_replace('?', '%s', $addSlashes), $builder->getBindings());
    }
    
}
