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

class ChequeReceiptExport implements FromView, WithEvents, WithColumnWidths
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
            'A' => 12,
            'B' => 12,
            'C' => 10,
            'D' => 14,
            'E' => 12,
            'F' => 12,
            'G' => 12,
            'H' => 12,
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

        $dbacthdr = DB::table('debtor.dbacthdr as dh', 'debtor.debtormast as dm', 'debtor.debtortype as dt')
                    ->select('dh.idno', 'dh.compcode', 'dh.source', 'dh.trantype', 'dh.auditno', 'dh.lineno_', 'dh.amount', 'dh.outamount', 
                    'dh.recstatus', 'dh.entrydate','dh.entrytime', 'dh.entryuser', 'dh.reference', 'dh.recptno', 'dh.paymode', 'dh.tillcode', 'dh.tillno', 'dh.debtorcode', 'dh.payercode', 'dh.billdebtor', 'dh.remark', 'dh.mrn', 'dh.episno', 'dh.authno', 'dh.expdate', 'dh.adddate', 'dh.adduser', 'dh.upddate', 'dh.upduser', 'dh.epistype', 'dh.cbflag', 'dh.conversion', 'dh.payername', 'dh.hdrtype', 'dh.currency', 'dh.rate', 'dh.unit', 'dh.invno', 'dh.paytype', 'dh.bankcharges', 'dh.RCCASHbalance', 'dh.RCOSbalance', 'dh.RCFinalbalance', 'dh.PymtDescription', 'dh.posteddate', 'dm.debtortype as dm_debtortype', 'dt.description as dt_description')
                    ->leftJoin('debtor.debtormast as dm', function($join){
                        $join = $join->on('dm.debtorcode', '=', 'dh.payercode')
                                    ->where('dm.compcode', '=', session('compcode'));
                    })
                    ->leftJoin('debtor.debtortype as dt', function($join) {
                        $join = $join->on('dt.debtortycode', '=', 'dm.debtortype')
                                    ->where('dt.compcode', '=', session('compcode'));
                    })
                    ->where('dh.compcode','=',session('compcode'))
                    ->where('dh.paytype', '=', '#F_TAB-CHEQUE')
                    ->whereIn('dh.trantype',['RD','RC'])
                    ->whereBetween('dh.entrydate', [$datefr, $dateto])
                    ->orderBy('dh.entrydate','ASC')
                    ->get();
        // dd($dbacthdr);

        $this->dbacthdr_len=$dbacthdr->count();

        $totalAmount = $dbacthdr->sum('amount');
        
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
                        // ->where('db.tillcode',$this->tillcode)
                        // ->where('db.tillno',$this->tillno)
                        ->whereIn('db.trantype',['RD','RC'])
                        ->join('debtor.paymode as pm', function($join){
                            $join = $join->on('pm.paymode', '=', 'db.paymode')
                                        ->where('pm.source','AR')
                                        ->where('pm.paytype','CASH')
                                        ->where('pm.compcode',session('compcode'));
                        })
                        ->whereBetween('db.entrydate', [$datefr, $dateto])
                        ->sum('amount');

            
            $sum_chq = DB::table('debtor.dbacthdr as db')
                        ->where('db.compcode',session('compcode'))
                        // ->where('db.tillcode',$this->tillcode)
                        // ->where('db.tillno',$this->tillno)
                        ->whereIn('db.trantype',['RD','RC'])
                        ->join('debtor.paymode as pm', function($join){
                            $join = $join->on('pm.paymode', '=', 'db.paymode')
                                        ->where('pm.source','AR')
                                        ->where('pm.paytype','CHEQUE')
                                        ->where('pm.compcode',session('compcode'));
                        })
                        ->whereBetween('db.entrydate', [$datefr, $dateto])
                        ->sum('amount');
            
            $sum_card = DB::table('debtor.dbacthdr as db')
                        ->where('db.compcode',session('compcode'))
                        // ->where('db.tillcode',$this->tillcode)
                        // ->where('db.tillno',$this->tillno)
                        ->whereIn('db.trantype',['RD','RC'])
                        ->join('debtor.paymode as pm', function($join){
                            $join = $join->on('pm.paymode', '=', 'db.paymode')
                                        ->where('pm.source','AR')
                                        ->where('pm.paytype','CARD')
                                        ->where('pm.compcode',session('compcode'));
                        })
                        ->whereBetween('db.entrydate', [$datefr, $dateto])
                        ->sum('amount');
            
            $sum_bank = DB::table('debtor.dbacthdr as db')
                        ->where('db.compcode',session('compcode'))
                        // ->where('db.tillcode',$this->tillcode)
                        // ->where('db.tillno',$this->tillno)
                        ->whereIn('db.trantype',['RD','RC'])
                        ->join('debtor.paymode as pm', function($join){
                            $join = $join->on('pm.paymode', '=', 'db.paymode')
                                        ->where('pm.source','AR')
                                        ->where('pm.paytype','BANK')
                                        ->where('pm.compcode',session('compcode'));
                        })
                        ->whereBetween('db.entrydate', [$datefr, $dateto])
                        ->sum('amount');
            
            $sum_all = DB::table('debtor.dbacthdr as db')
                        ->where('db.compcode',session('compcode'))
                        // ->where('db.tillcode',$this->tillcode)
                        // ->where('db.tillno',$this->tillno)
                        ->whereIn('db.trantype',['RD','RC'])
                        ->whereBetween('db.entrydate', [$datefr, $dateto])
                        ->sum('amount');
            
            $sum_cash_ref = DB::table('debtor.dbacthdr as db')
                            ->where('db.compcode',session('compcode'))
                            // ->where('db.tillcode',$this->tillcode)
                            // ->where('db.tillno',$this->tillno)
                            ->whereIn('db.trantype',['RF'])
                            ->join('debtor.paymode as pm', function($join){
                                $join = $join->on('pm.paymode', '=', 'db.paymode')
                                            ->where('pm.source','AR')
                                            ->where('pm.paytype','CASH')
                                            ->where('pm.compcode',session('compcode'));
                            })
                            ->whereBetween('db.entrydate', [$datefr, $dateto])
                            ->sum('amount');
            
            $sum_chq_ref = DB::table('debtor.dbacthdr as db')
                            ->where('db.compcode',session('compcode'))
                            // ->where('db.tillcode',$this->tillcode)
                            // ->where('db.tillno',$this->tillno)
                            ->whereIn('db.trantype',['RF'])
                            ->join('debtor.paymode as pm', function($join){
                                $join = $join->on('pm.paymode', '=', 'db.paymode')
                                            ->where('pm.source','AR')
                                            ->where('pm.paytype','CHEQUE')
                                            ->where('pm.compcode',session('compcode'));
                            })
                            ->whereBetween('db.entrydate', [$datefr, $dateto])
                            ->sum('amount');
            
            $sum_card_ref = DB::table('debtor.dbacthdr as db')
                            ->where('db.compcode',session('compcode'))
                            // ->where('db.tillcode',$this->tillcode)
                            // ->where('db.tillno',$this->tillno)
                            ->whereIn('db.trantype',['RF'])
                            ->join('debtor.paymode as pm', function($join){
                                $join = $join->on('pm.paymode', '=', 'db.paymode')
                                            ->where('pm.source','AR')
                                            ->where('pm.paytype','CARD')
                                            ->where('pm.compcode',session('compcode'));
                            })
                            ->whereBetween('db.entrydate', [$datefr, $dateto])
                            ->sum('amount');
            
            $sum_bank_ref = DB::table('debtor.dbacthdr as db')
                            ->where('db.compcode',session('compcode'))
                            // ->where('db.tillcode',$this->tillcode)
                            // ->where('db.tillno',$this->tillno)
                            ->whereIn('db.trantype',['RF'])
                            ->join('debtor.paymode as pm', function($join){
                                $join = $join->on('pm.paymode', '=', 'db.paymode')
                                            ->where('pm.source','AR')
                                            ->where('pm.paytype','BANK')
                                            ->where('pm.compcode',session('compcode'));
                            })
                            ->whereBetween('db.entrydate', [$datefr, $dateto])
                            ->sum('amount');
            
            $sum_all_ref = DB::table('debtor.dbacthdr as db')
                            ->where('db.compcode',session('compcode'))
                            // ->where('db.tillcode',$this->tillcode)
                            // ->where('db.tillno',$this->tillno)
                            ->whereIn('db.trantype',['RF'])
                            ->whereBetween('db.entrydate', [$datefr, $dateto])
                            ->sum('amount');
            
            $total_card_rcrd = $sum_card + $sum_bank;
            $total_card_rf = $sum_card_ref + $sum_bank_ref;
            
            $grandtotal_cash = $sum_cash - $sum_cash_ref;
            $grandtotal_card = $total_card_rcrd - $total_card_rf;
            $grandtotal_chq = $sum_chq - $sum_chq_ref;
            $grandtotal_all = $sum_all - $sum_all_ref;

        }
        
        $title = "CHEQUE LISTING";
        
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

        return view('finance.AR.chequeReceipt_Report.chequeReceipt_Report_excel',compact('dbacthdr','totalAmount','sum_cash','sum_chq','sum_card','sum_bank','sum_all','sum_cash_ref','sum_chq_ref','sum_card_ref','sum_bank_ref','sum_all_ref','grandtotal_cash','grandtotal_card', 'grandtotal_chq', 'grandtotal_all','title','company','totamt_eng'));
        
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // set up a style array for cell formatting
                $totrow = $event->sheet->getHighestRow();

                $style_header = [
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER
                    ]
                ];

                $style_subheader = [
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_LEFT
                    ]
                ];

                $style_address = [
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_RIGHT
                    ]
                ];

                $style_right = [
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_RIGHT
                    ]
                ];
                
                $style_datetime = [
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_LEFT
                    ]
                ];
                
                $style_columnheader = [
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER
                    ]
                ];
                
                $totpage = ceil($totrow/45);

                $curpage=1;
                $loop_page=0;
                while ($totrow > 0){
                    $totrow=$totrow-45;
                    $event->sheet->insertNewRowBefore(1+$loop_page, 5);
                
                    ///// assign cell values
                    $event->sheet->setCellValue('C'.(1+$loop_page),$this->comp->name);
                    $event->sheet->setCellValue('A'.(1+$loop_page),'PRINTED BY : '.session('username'));
                    $event->sheet->setCellValue('E'.(1+$loop_page),'PRINTED :'.Carbon::now("Asia/Kuala_Lumpur")->format('d-m-Y H:i'));
                    $event->sheet->setCellValue('C'.(2+$loop_page),'CHEQUE LISTING');
                    $event->sheet->setCellValue('C'.(3+$loop_page), sprintf('FROM DATE %s TO DATE %s',$this->datefr, $this->dateto));
                    $event->sheet->setCellValue('E'.(2+$loop_page),'PAGE : '.$curpage.' / '.$totpage);

                    $event->sheet->setCellValue('A'.(5+$loop_page),'RECEIPT DATE');
                    $event->sheet->setCellValue('B'.(5+$loop_page),'PAYER CODE');
                    $event->sheet->setCellValue('C'.(5+$loop_page),'AMOUNT');
                    $event->sheet->setCellValue('D'.(5+$loop_page),'PAYER');
                    $event->sheet->setCellValue('E'.(5+$loop_page),'FC');
                    $event->sheet->setCellValue('F'.(5+$loop_page),'MODE');
                    $event->sheet->setCellValue('G'.(5+$loop_page),'REFERENCE');
                    $event->sheet->setCellValue('H'.(5+$loop_page),'RECEIPT NO');

                    
                    ///// assign cell styles
                    $event->sheet->getStyle('A'.(1+$loop_page).':A'.(3+$loop_page))->applyFromArray($style_subheader);
                    $event->sheet->getStyle('E'.(1+$loop_page).':E'.(3+$loop_page))->applyFromArray($style_subheader);
                    $event->sheet->getStyle('C'.(1+$loop_page).':C'.(3+$loop_page))->applyFromArray($style_header);
                    $event->sheet->getStyle('A'.(5+$loop_page).':H'.(5+$loop_page))->applyFromArray($style_columnheader);

                    $curpage++;
                    $loop_page+=50;
                }
              
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
