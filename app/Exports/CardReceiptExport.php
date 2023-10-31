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

class CardReceiptExport implements FromView, WithEvents, WithColumnWidths
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
            'A' => 25,
            'B' => 25,
            'C' => 40,
            'D' => 25,
            'E' => 25,
            'F' => 25,
            'G' => 25,
            'H' => 25,
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
                    ->select('dh.idno', 'dh.compcode', 'dh.source', 'dh.trantype', 'dh.auditno', 'dh.lineno_', 'dh.amount', 'dh.outamount', 'dh.recstatus', 'dh.entrydate','dh.entrytime', 'dh.entryuser', 'dh.reference', 'dh.recptno', 'dh.paymode', 'dh.tillcode', 'dh.tillno', 'dh.debtorcode', 'dh.payercode', 'dh.billdebtor', 'dh.remark', 'dh.mrn', 'dh.episno', 'dh.authno', 'dh.expdate', 'dh.adddate', 'dh.adduser', 'dh.upddate', 'dh.upduser', 'dh.epistype', 'dh.cbflag', 'dh.conversion', 'dh.payername', 'dh.hdrtype', 'dh.currency', 'dh.rate', 'dh.unit', 'dh.invno', 'dh.paytype', 'dh.bankcharges', 'dh.RCCASHbalance', 'dh.RCOSbalance', 'dh.RCFinalbalance', 'dh.PymtDescription', 'dh.posteddate', 'dm.debtortype as dm_debtortype', 'dt.description as dt_description')
                    ->leftJoin('debtor.debtormast as dm', function($join){
                        $join = $join->on('dm.debtorcode', '=', 'dh.payercode')
                                    ->where('dm.compcode', '=', session('compcode'));
                    })
                    ->leftJoin('debtor.debtortype as dt', function($join){
                        $join = $join->on('dt.debtortycode', '=', 'dm.debtortype')
                                    ->where('dt.compcode', '=', session('compcode'));
                    })
                    ->where('dh.compcode','=',session('compcode'))
                    ->where('dh.paytype', '=', '#F_TAB-CARD')
                    ->whereIn('dh.trantype',['RD','RC'])
                    ->whereBetween('dh.entrydate', [$datefr, $dateto])
                    ->orderBy('dh.entrydate','ASC')
                    ->get();
        // dd($dbacthdr);
        
        $this->dbacthdr_len=$dbacthdr->count();
        
        $paymode = DB::table('debtor.dbacthdr as dh')
                    ->select('dh.paymode') 
                    ->where('dh.compcode','=',session('compcode'))
                    ->where('dh.paytype', '=', '#F_TAB-CARD')
                    ->whereIn('dh.trantype',['RD','RC'])
                    ->whereBetween('dh.entrydate', [$datefr, $dateto])
                    ->distinct('dh.paymode');
        $paymode = $paymode->get(['dh.paymode']);
        
        $totalAmount = $dbacthdr->sum('amount');
        
        $totamount_expld = explode(".", (float)$totalAmount);
        
        $totamt_eng_rm = $this->convertNumberToWordENG($totamount_expld[0])."";
        $totamt_eng = $totamt_eng_rm." ONLY";
        
        if(count($totamount_expld) > 1){
            $totamt_eng_sen = $this->convertNumberToWordENG($totamount_expld[1])." CENT";
            $totamt_eng = $totamt_eng_rm.$totamt_eng_sen." ONLY";
        }
        
        return view('finance.AR.cardReceipt_Report.cardReceipt_Report_excel',compact('dbacthdr','paymode','totamt_eng','totalAmount'));
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // set up a style array for cell formatting
                $style_header = [
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER
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
                
                // at row 1, insert 2 rows
                $event->sheet->insertNewRowBefore(1, 7);
                
                ///// assign cell values
                $event->sheet->setCellValue('A1','PRINTED DATE :');
                $event->sheet->setCellValue('B1', Carbon::now("Asia/Kuala_Lumpur")->format('d-m-Y'));
                $event->sheet->setCellValue('A2','PRINTED TIME :');
                $event->sheet->setCellValue('B2', Carbon::now("Asia/Kuala_Lumpur")->format('H:i'));
                $event->sheet->setCellValue('A3','PRINTED BY :');
                $event->sheet->setCellValue('B3', session('username'));
                $event->sheet->setCellValue('D1','CARD RECEIPT LISTING');
                $event->sheet->setCellValue('D2', sprintf('FROM DATE %s TO DATE %s',$this->datefr, $this->dateto));
                $event->sheet->setCellValue('G1',$this->comp->name);
                $event->sheet->setCellValue('G2',$this->comp->address1);
                $event->sheet->setCellValue('G3',$this->comp->address2);
                $event->sheet->setCellValue('G4',$this->comp->address3);
                $event->sheet->setCellValue('G5',$this->comp->address4);
                // $event->sheet->setCellValue('A7','RECEIPT NO');
                // $event->sheet->setCellValue('B7','RECEIPT DATE');
                // $event->sheet->setCellValue('C7','AMOUNT');
                // $event->sheet->setCellValue('D7','CARD NO');
                // $event->sheet->setCellValue('E7','EXPIRY DATE');
                // $event->sheet->setCellValue('F7','AUTHORISATION NO');
                // $event->sheet->setCellValue('G7','PAYER');
                
                ///// assign cell styles
                $event->sheet->getStyle('A1:A3')->applyFromArray($style_datetime);
                $event->sheet->getStyle('D1:D2')->applyFromArray($style_header);
                $event->sheet->getStyle('G1:G5')->applyFromArray($style_address);
                // $event->sheet->getStyle('A7:G7')->applyFromArray($style_columnheader);
                
                // next table
                
                // $aftercol = 7+3+$this->dbacthdr_len;
                
                // $event->sheet->insertNewRowBefore($aftercol, 7);
                ///// assign cell values
                // $event->sheet->setCellValue('A'.$aftercol,'PRINTED DATE :');
                // $event->sheet->setCellValue('B'.$aftercol, Carbon::now("Asia/Kuala_Lumpur")->format('d-m-Y'));
                // $event->sheet->setCellValue('A'.($aftercol+1),'PRINTED TIME :');
                // $event->sheet->setCellValue('B'.($aftercol+1), Carbon::now("Asia/Kuala_Lumpur")->format('H:i'));
                // $event->sheet->setCellValue('A'.($aftercol+2),'PRINTED BY :');
                // $event->sheet->setCellValue('B'.($aftercol+2), session('username'));
                // $event->sheet->setCellValue('C'.($aftercol),'CARD RECEIPT LISTING');
                // $event->sheet->setCellValue('C'.($aftercol+1), sprintf('FROM DATE %s TO DATE %s',$this->datefr, $this->dateto));
                // $event->sheet->setCellValue('F'.$aftercol,$this->comp->name);
                // $event->sheet->setCellValue('F'.($aftercol+1),$this->comp->address1);
                // $event->sheet->setCellValue('F'.($aftercol+2),$this->comp->address2);
                // $event->sheet->setCellValue('F'.($aftercol+3),$this->comp->address3);
                // $event->sheet->setCellValue('F'.($aftercol+4),$this->comp->address4);
                // $event->sheet->setCellValue('A'.($aftercol+6),'RECEIPT NO');
                // $event->sheet->setCellValue('B'.($aftercol+6),'RECEIPT DATE');
                // $event->sheet->setCellValue('C'.($aftercol+6),'AMOUNT');
                // $event->sheet->setCellValue('D'.($aftercol+6),'CARD NO');
                // $event->sheet->setCellValue('E'.($aftercol+6),'EXPIRY DATE');
                // $event->sheet->setCellValue('F'.($aftercol+6),'AUTHORISATION NO');
                // $event->sheet->setCellValue('G'.($aftercol+6),'PAYER');
                
                ///// assign cell styles
                // $event->sheet->getStyle('A'.$aftercol.':A'.$aftercol)->applyFromArray($style_datetime);
                // $event->sheet->getStyle('C'.$aftercol.':C'.($aftercol+1))->applyFromArray($style_header);
                // $event->sheet->getStyle('F'.($aftercol+6).':F'.($aftercol+4))->applyFromArray($style_address);
                // $event->sheet->getStyle('A'.$aftercol.':H'.($aftercol+6))->applyFromArray($style_columnheader);
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
