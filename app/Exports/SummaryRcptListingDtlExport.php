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

class SummaryRcptListingDtlExport implements FromView, WithEvents, WithColumnWidths
{
    
    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function __construct($datefr,$dateto,$tillcode,$tillno)
    {
        $this->datefr = $datefr;
        $this->dateto = $dateto;
        $this->tillcode = $tillcode;
        $this->tillno = $tillno;
        $this->dbacthdr_len=0;
        
        $this->comp = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();
    }
    
    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 15,
            'C' => 15,
            'D' => 15,
            'E' => 15,
        ];
    }
    
    public function view(): View
    {
        $datefr = Carbon::parse($this->datefr)->format('Y-m-d');
        $dateto = Carbon::parse($this->dateto)->format('Y-m-d');
        
        $tilldetl = DB::table('debtor.tilldetl')
                    ->where('compcode',session('compcode'))
                    ->where('tillcode',$this->tillcode)
                    ->where('tillno',$this->tillno)
                    ->first();
        
        $dbacthdr = DB::table('debtor.dbacthdr as dh', 'debtor.debtormast as dm', 'debtor.debtortype as dt','debtor.tilldetl as dl')
                ->select(
                    'dh.tillcode',  'dh.posteddate', 'dl.cashier as cashier', 'dh.tillno',
                        DB::raw("SUM(case when dh.paytype = '#F_TAB-CASH' then dh.amount else 0 end) as cash"),
                        DB::raw("SUM(case when dh.paytype = '#F_TAB-CARD' then dh.amount else 0 end) as card"),
                        DB::raw("SUM(case when dh.paytype = '#F_TAB-CHEQUE' then dh.amount else 0 end) as cheque"),
                        DB::raw("SUM(case when dh.paytype = '#F_TAB-DEBIT' then dh.amount else 0 end) as autodebit"),
                    )
                ->leftJoin('debtor.debtormast as dm', function($join){
                    $join = $join->on('dm.debtorcode', '=', 'dh.payercode')
                                ->where('dm.compcode', '=', session('compcode'));
                })
                ->leftJoin('debtor.debtortype as dt', function($join){
                    $join = $join->on('dt.debtortycode', '=', 'dm.debtortype')
                                ->where('dt.compcode', '=', session('compcode'));
                })
                ->leftJoin('debtor.tilldetl as dl', function($join){
                                $join = $join->on('dl.tillno', '=', 'dh.tillno')
                                            ->where('dl.compcode', '=', session('compcode'));
                            })
                ->where('dh.compcode','=',session('compcode'))
                ->where('dh.recstatus','POSTED')
                ->whereIn('dh.trantype',['RD','RC'])
                ->groupBy('dh.tillcode', 'dh.posteddate','dl.cashier', 'dh.tillno')
                ->whereBetween('dh.posteddate', [$datefr, $dateto])
                ->get();
        
        $totalAmount = $dbacthdr->sum('amount');
        
        $dbacthdr_rf = DB::table('debtor.dbacthdr as dh', 'debtor.debtormast as dm', 'debtor.debtortype as dt','debtor.tilldetl as dl')
                ->select(
                    'dh.tillcode',  'dh.posteddate', 'dl.cashier as cashier', 'dh.tillno',
                        DB::raw("SUM(case when dh.paytype = '#F_TAB-CASH' then dh.amount else 0 end) as cash"),
                        DB::raw("SUM(case when dh.paytype = '#F_TAB-CARD' then dh.amount else 0 end) as card"),
                        DB::raw("SUM(case when dh.paytype = '#F_TAB-CHEQUE' then dh.amount else 0 end) as cheque"),
                        DB::raw("SUM(case when dh.paytype = '#F_TAB-DEBIT' then dh.amount else 0 end) as autodebit"),
                    )
                ->leftJoin('debtor.debtormast as dm', function($join){
                    $join = $join->on('dm.debtorcode', '=', 'dh.payercode')
                                ->where('dm.compcode', '=', session('compcode'));
                })
                ->leftJoin('debtor.debtortype as dt', function($join){
                    $join = $join->on('dt.debtortycode', '=', 'dm.debtortype')
                                ->where('dt.compcode', '=', session('compcode'));
                })
                ->leftJoin('debtor.tilldetl as dl', function($join){
                                $join = $join->on('dl.tillno', '=', 'dh.tillno')
                                            ->where('dl.compcode', '=', session('compcode'));
                            })
                ->where('dh.compcode','=',session('compcode'))
                ->where('dh.recstatus','POSTED')
                ->whereIn('dh.trantype',['RF'])
                ->groupBy('dh.tillcode', 'dh.posteddate','dl.cashier', 'dh.tillno')
                ->whereBetween('dh.posteddate', [$datefr, $dateto])
                ->get();
        
        $db_dbacthdr = DB::table('debtor.dbacthdr as db')
                    ->where('db.compcode',session('compcode'))
                    ->where('db.tillcode',$this->tillcode)
                    ->where('db.tillno',$this->tillno)
                    ->join('debtor.paymode as pm', function($join){
                        $join = $join->on('pm.paymode', '=', 'db.paymode')
                                    ->where('pm.source','AR')
                                    ->where('pm.compcode',session('compcode'));
                    });
        
        if($db_dbacthdr->exists()){
            $sum_cash = DB::table('debtor.dbacthdr as db')
                        ->where('db.compcode',session('compcode'))
                        ->where('db.recstatus','POSTED')
                        // ->where('db.tillcode',$this->tillcode)
                        // ->where('db.tillno',$this->tillno)
                        ->whereIn('db.trantype',['RD','RC'])
                        ->join('debtor.paymode as pm', function($join){
                            $join = $join->on('pm.paymode', '=', 'db.paymode')
                                        ->where('pm.source','AR')
                                        ->where('pm.paytype','CASH')
                                        ->where('pm.compcode',session('compcode'));
                        })
                        ->whereBetween('db.posteddate', [$datefr, $dateto])
                        ->sum('amount');
            
            $sum_chq = DB::table('debtor.dbacthdr as db')
                        ->where('db.compcode',session('compcode'))
                        ->where('db.recstatus','POSTED')
                        // ->where('db.tillcode',$this->tillcode)
                        // ->where('db.tillno',$this->tillno)
                        ->whereIn('db.trantype',['RD','RC'])
                        ->join('debtor.paymode as pm', function($join){
                            $join = $join->on('pm.paymode', '=', 'db.paymode')
                                        ->where('pm.source','AR')
                                        ->where('pm.paytype','CHEQUE')
                                        ->where('pm.compcode',session('compcode'));
                        })
                        ->whereBetween('db.posteddate', [$datefr, $dateto])
                        ->sum('amount');
            
            $sum_card = DB::table('debtor.dbacthdr as db')
                        ->where('db.compcode',session('compcode'))
                        ->where('db.recstatus','POSTED')
                        // ->where('db.tillcode',$this->tillcode)
                        // ->where('db.tillno',$this->tillno)
                        ->whereIn('db.trantype',['RD','RC'])
                        ->join('debtor.paymode as pm', function($join){
                            $join = $join->on('pm.paymode', '=', 'db.paymode')
                                        ->where('pm.source','AR')
                                        ->where('pm.paytype','CARD')
                                        ->where('pm.compcode',session('compcode'));
                        })
                        ->whereBetween('db.posteddate', [$datefr, $dateto])
                        ->sum('amount');
            
            $sum_bank = DB::table('debtor.dbacthdr as db')
                        ->where('db.compcode',session('compcode'))
                        ->where('db.recstatus','POSTED')
                        // ->where('db.tillcode',$this->tillcode)
                        // ->where('db.tillno',$this->tillno)
                        ->whereIn('db.trantype',['RD','RC'])
                        ->join('debtor.paymode as pm', function($join){
                            $join = $join->on('pm.paymode', '=', 'db.paymode')
                                        ->where('pm.source','AR')
                                        ->where('pm.paytype','BANK')
                                        ->where('pm.compcode',session('compcode'));
                        })
                        ->whereBetween('db.posteddate', [$datefr, $dateto])
                        ->sum('amount');
            
            $sum_all = DB::table('debtor.dbacthdr as db')
                        ->where('db.compcode',session('compcode'))
                        ->where('db.recstatus','POSTED')
                        // ->where('db.tillcode',$this->tillcode)
                        // ->where('db.tillno',$this->tillno)
                        ->whereIn('db.trantype',['RD','RC'])
                        ->whereBetween('db.posteddate', [$datefr, $dateto])
                        ->sum('amount');
            
            $sum_cash_ref = DB::table('debtor.dbacthdr as db')
                            ->where('db.compcode',session('compcode'))
                            ->where('db.recstatus','POSTED')
                            // ->where('db.tillcode',$this->tillcode)
                            // ->where('db.tillno',$this->tillno)
                            ->whereIn('db.trantype',['RF'])
                            ->join('debtor.paymode as pm', function($join){
                                $join = $join->on('pm.paymode', '=', 'db.paymode')
                                            ->where('pm.source','AR')
                                            ->where('pm.paytype','CASH')
                                            ->where('pm.compcode',session('compcode'));
                            })
                            ->whereBetween('db.posteddate', [$datefr, $dateto])
                            ->sum('amount');
            
            $sum_chq_ref = DB::table('debtor.dbacthdr as db')
                            ->where('db.compcode',session('compcode'))
                            ->where('db.recstatus','POSTED')
                            // ->where('db.tillcode',$this->tillcode)
                            // ->where('db.tillno',$this->tillno)
                            ->whereIn('db.trantype',['RF'])
                            ->join('debtor.paymode as pm', function($join){
                                $join = $join->on('pm.paymode', '=', 'db.paymode')
                                            ->where('pm.source','AR')
                                            ->where('pm.paytype','CHEQUE')
                                            ->where('pm.compcode',session('compcode'));
                            })
                            ->whereBetween('db.posteddate', [$datefr, $dateto])
                            ->sum('amount');
            
            $sum_card_ref = DB::table('debtor.dbacthdr as db')
                            ->where('db.compcode',session('compcode'))
                            ->where('db.recstatus','POSTED')
                            // ->where('db.tillcode',$this->tillcode)
                            // ->where('db.tillno',$this->tillno)
                            ->whereIn('db.trantype',['RF'])
                            ->join('debtor.paymode as pm', function($join){
                                $join = $join->on('pm.paymode', '=', 'db.paymode')
                                            ->where('pm.source','AR')
                                            ->where('pm.paytype','CARD')
                                            ->where('pm.compcode',session('compcode'));
                            })
                            ->whereBetween('db.posteddate', [$datefr, $dateto])
                            ->sum('amount');
            
            $sum_bank_ref = DB::table('debtor.dbacthdr as db')
                            ->where('db.compcode',session('compcode'))
                            ->where('db.recstatus','POSTED')
                            // ->where('db.tillcode',$this->tillcode)
                            // ->where('db.tillno',$this->tillno)
                            ->whereIn('db.trantype',['RF'])
                            ->join('debtor.paymode as pm', function($join){
                                $join = $join->on('pm.paymode', '=', 'db.paymode')
                                            ->where('pm.source','AR')
                                            ->where('pm.paytype','BANK')
                                            ->where('pm.compcode',session('compcode'));
                            })
                            ->whereBetween('db.posteddate', [$datefr, $dateto])
                            ->sum('amount');
            
            $sum_all_ref = DB::table('debtor.dbacthdr as db')
                            ->where('db.compcode',session('compcode'))
                            ->where('db.recstatus','POSTED')
                            // ->where('db.tillcode',$this->tillcode)
                            // ->where('db.tillno',$this->tillno)
                            ->whereIn('db.trantype',['RF'])
                            ->whereBetween('db.posteddate', [$datefr, $dateto])
                            ->sum('amount');
            
            $grandtotal_cash = $sum_cash - $sum_cash_ref;
            $grandtotal_card = $sum_card - $sum_card_ref;
            $grandtotal_chq = $sum_chq - $sum_chq_ref;
            $grandtotal_bank = $sum_bank - $sum_bank_ref;
            $grandtotal_all = $sum_all - $sum_all_ref;
        }
        
        $title = "SUMMARY RECEIPT LISTING DETAIL";
        
        $title2 = "REFUND LISTING";
        
        $company = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();
        
        $totamount_expld = explode(".", (float)$totalAmount);
        
        $totamt_eng_rm = $this->convertNumberToWordENG($totamount_expld[0])."";
        $totamt_eng = $totamt_eng_rm." ONLY";
        
        if(count($totamount_expld) > 1){
            $totamt_eng_sen = $this->convertNumberToWordENG($totamount_expld[1])." CENT";
            $totamt_eng = $totamt_eng_rm.$totamt_eng_sen." ONLY";
        }
        
        return view('finance.AR.SummaryRcptListingDtl_Report.SummaryRcptListingDtl_Report_excel',compact('dbacthdr','dbacthdr_rf','totalAmount','sum_cash','sum_chq','sum_card','sum_bank','sum_all','sum_cash_ref','sum_chq_ref','sum_card_ref','sum_bank_ref','sum_all_ref','grandtotal_cash','grandtotal_card', 'grandtotal_chq', 'grandtotal_bank','grandtotal_all','title', 'title2','company','totamt_eng'));
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getPageSetup()->setPaperSize(9);//A4
                
                $event->sheet->getHeaderFooter()->setOddHeader('&C'.$this->comp->name."\nSUMMARY RECEIPT LISTING DETAIL"."\n".sprintf('FROM DATE %s TO DATE %s',$this->datefr, $this->dateto).'&L'.'PRINTED BY : '.session('username')."\nPAGE : &P/&N".'&R'.'PRINTED DATE : '.Carbon::now("Asia/Kuala_Lumpur")->format('d-m-Y')."\n".'PRINTED TIME : '.Carbon::now("Asia/Kuala_Lumpur")->format('H:i'));
                
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
    
}
