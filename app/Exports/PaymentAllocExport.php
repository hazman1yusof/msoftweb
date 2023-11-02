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

class PaymentAllocExport implements FromView, WithEvents, WithColumnWidths
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
            'B' => 15,
            'C' => 20,
            'D' => 15,
            'E' => 20,
            'F' => 15,
            'G' => 10,
            'H' => 15,
            'I' => 15,
            'J' => 15,
            'K' => 20,
        ];
    }
    
    public function view(): View
    {
        $datefr = Carbon::parse($this->datefr)->format('Y-m-d H:i:s');
        $dateto = Carbon::parse($this->dateto)->format('Y-m-d H:i:s');
        
        $dballoc = DB::table('debtor.dballoc as da', 'debtor.dbacthdr as dh', 'debtor.dbacthdr as dc', 'debtor.debtormast as dm')
                    ->select('da.doctrantype', 'da.allocdate', 'da.recptno as da_recptno', 'da.refauditno', 'da.amount as allocamount', 'da.debtorcode', 'dh.entrydate as doc_entrydate', 'dh.recptno as dh_recptno', 'dh.reference', 'dh.amount as dh_amount', 'dc.entrydate as ref_entrydate', 'dm.debtorcode as dm_debtorcode', 'dm.name as debtorname')
                    ->leftJoin('debtor.dbacthdr as dh', function($join){
                        $join = $join->on('dh.source', '=', 'da.docsource')
                                    ->on('dh.trantype', '=', 'da.doctrantype')
                                    ->on('dh.auditno', '=', 'da.docauditno');
                    })
                    ->leftJoin('debtor.dbacthdr as dc', function($join){
                        $join = $join->on('dc.source', '=', 'da.refsource')
                                    ->on('dc.trantype', '=', 'da.reftrantype')
                                    ->on('dc.auditno', '=', 'da.refauditno');
                    })
                    ->leftJoin('debtor.debtormast as dm', function($join){
                        $join = $join->on('dm.debtorcode', '=', 'da.payercode')
                                    ->where('dm.compcode', '=', session('compcode'));
                    })
                    ->where('da.compcode','=',session('compcode'))
                    ->where('da.recstatus','=',"POSTED")
                    ->whereIn('da.doctrantype',['RD','RC'])
                    ->whereBetween('da.allocdate', [$datefr, $dateto])
                    // ->whereBetween('dh.entrydate', [$datefr, $dateto])
                    ->get();
        // dd($dballoc);
        
        $this->dballoc_len=$dballoc->count();
        
        $title = "PAYMENT ALLOCATION LISTING";
        
        $company = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();
        
        return view('finance.AR.paymentAlloc_Report.paymentAlloc_Report_excel',compact('dballoc', 'title', 'company'));
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
                    $event->sheet->setCellValue('E'.(1+$loop_page),'PRINTED : '.Carbon::now("Asia/Kuala_Lumpur")->format('d-m-Y H:i'));
                    $event->sheet->setCellValue('C'.(2+$loop_page),'PAYMENT ALLOCATION LISTING');
                    $event->sheet->setCellValue('C'.(3+$loop_page), sprintf('FROM DATE %s TO DATE %s',$this->datefr, $this->dateto));
                    $event->sheet->setCellValue('E'.(2+$loop_page),'PAGE : '.$curpage.' / '.$totpage);
                    
                    $event->sheet->setCellValue('A'.(5+$loop_page),'TRX TYPE');
                    $event->sheet->setCellValue('B'.(5+$loop_page),'RECEIPT DATE');
                    $event->sheet->setCellValue('C'.(5+$loop_page),'ALLOCATION DATE');
                    $event->sheet->setCellValue('D'.(5+$loop_page),'RECEIPT NO');
                    $event->sheet->setCellValue('E'.(5+$loop_page),'PAYMENT DETAILS');
                    $event->sheet->setCellValue('F'.(5+$loop_page),'RECEIPT AMT');
                    $event->sheet->setCellValue('G'.(5+$loop_page),'BILL NO');
                    $event->sheet->setCellValue('H'.(5+$loop_page),'BILL DATE');
                    $event->sheet->setCellValue('I'.(5+$loop_page),'ALLOCATED AMT');
                    $event->sheet->setCellValue('J'.(5+$loop_page),'DEBTOR CODE');
                    $event->sheet->setCellValue('K'.(5+$loop_page),'NAME');
                    
                    ///// assign cell styles
                    $event->sheet->getStyle('A'.(1+$loop_page).':A'.(3+$loop_page))->applyFromArray($style_subheader);
                    $event->sheet->getStyle('E'.(1+$loop_page).':E'.(3+$loop_page))->applyFromArray($style_subheader);
                    $event->sheet->getStyle('C'.(1+$loop_page).':C'.(3+$loop_page))->applyFromArray($style_header);
                    $event->sheet->getStyle('A'.(5+$loop_page).':K'.(5+$loop_page))->applyFromArray($style_columnheader);
                    
                    $curpage++;
                    $loop_page+=50;
                }

                // next table
                
                // $aftercol = 7+3+$this->dballoc_len;
                
                // $event->sheet->insertNewRowBefore($aftercol, 7);
                ///// assign cell values
                // $event->sheet->setCellValue('A'.$aftercol,'PRINTED DATE :');
                // $event->sheet->setCellValue('B'.$aftercol, Carbon::now("Asia/Kuala_Lumpur")->format('d-m-Y'));
                // $event->sheet->setCellValue('A'.($aftercol+1),'PRINTED TIME :');
                // $event->sheet->setCellValue('B'.($aftercol+1), Carbon::now("Asia/Kuala_Lumpur")->format('H:i'));
                // $event->sheet->setCellValue('A'.($aftercol+2),'PRINTED BY :');
                // $event->sheet->setCellValue('B'.($aftercol+2), session('username'));
                // $event->sheet->setCellValue('C'.($aftercol),'REFUND LISTING');
                // $event->sheet->setCellValue('C'.($aftercol+1), sprintf('FROM DATE %s TO DATE %s',$this->datefr, $this->dateto));
                // $event->sheet->setCellValue('F'.$aftercol,$this->comp->name);
                // $event->sheet->setCellValue('F'.($aftercol+1),$this->comp->address1);
                // $event->sheet->setCellValue('F'.($aftercol+2),$this->comp->address2);
                // $event->sheet->setCellValue('F'.($aftercol+3),$this->comp->address3);
                // $event->sheet->setCellValue('F'.($aftercol+4),$this->comp->address4);
                // $event->sheet->setCellValue('A'.($aftercol+6),'DATE');
                // $event->sheet->setCellValue('B'.($aftercol+6),'CASH');
                // $event->sheet->setCellValue('C'.($aftercol+6),'CARD');
                // $event->sheet->setCellValue('D'.($aftercol+6),'CHEQUE');
                // $event->sheet->setCellValue('E'.($aftercol+6),'TOTAL');
                
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
