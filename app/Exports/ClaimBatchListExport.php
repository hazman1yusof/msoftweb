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

class ClaimBatchListExport implements FromView, WithEvents, WithColumnWidths
{
    
    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function __construct($date1,$epis_type,$debtorcode_to,$title,$content,$officer,$designation)
    {
        $this->date1 = $date1;
        $this->epis_type = $epis_type;
        $this->debtorcode_to = $debtorcode_to;
        $this->title = $title;
        $this->content = $content;
        $this->officer = $officer;
        $this->designation = $designation;
        $this->dbacthdr_len = 0;
        
        $this->comp = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();
    }
    
    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 15,
            'C' => 30,
            'D' => 18,
            'E' => 15,
            'F' => 15,
            'G' => 10,
            'H' => 15,
        ];
    }
    
    public function view(): View
    {
        $date1 = $this->date1;
        $epis_type = $this->epis_type;
        $debtorcode_to = $this->debtorcode_to;
        $title = $this->title;
        $content = $this->content;
        $officer = $this->officer;
        $designation = $this->designation;
        
        // $sysparam1_obj = DB::table('sysdb.sysparam')
        //                 ->select('description as title','pvalue1 as content')
        //                 ->where('compcode','=',session('compcode'))
        //                 ->where('source','=','AR')
        //                 ->where('trantype','=','coverletter')
        //                 ->first();
        
        // $sysparam2_obj = DB::table('sysdb.sysparam')
        //                 ->select('pvalue1 as officer','pvalue2 as designation')
        //                 ->where('compcode','=',session('compcode'))
        //                 ->where('source','=','AR')
        //                 ->where('trantype','=','PIC')
        //                 ->first();
        
        $debtormast = DB::table('debtor.debtormast')
                    ->where('compcode','=',session('compcode'))
                    ->where('debtorcode','=',$debtorcode_to)
                    ->first();
        
        return view('finance.AR.ClaimBatchList_Report.ClaimBatchList_Report_excel',compact('date1','epis_type','title','content','officer','designation','debtormast'));
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event){
                $event->sheet->getPageSetup()->setPaperSize(9); // A4
                
                $event->sheet->getHeaderFooter()->setOddHeader('&C'.$this->comp->name."\nCLAIM BATCH LISTING"."\n"
                // .sprintf('FROM DATE %s TO DATE %s',$this->datefr, $this->dateto)
                .'&L'
                .'PRINTED BY : '.session('username')
                ."\nPAGE : &P/&N"
                .'&R'.'PRINTED DATE : '.Carbon::now("Asia/Kuala_Lumpur")->format('d-m-Y')
                ."\n".'PRINTED TIME : '.Carbon::now("Asia/Kuala_Lumpur")->format('H:i'));
                
                $event->sheet->getPageMargins()->setTop(1);
                
                $event->sheet->getPageSetup()->setRowsToRepeatAtTop([2,2]);
                $event->sheet->getStyle('A:I')->getAlignment()->setWrapText(true);
                $event->sheet->getPageSetup()->setFitToWidth(1);
                $event->sheet->getPageSetup()->setFitToHeight(0);
            },
        ];
    }
    
    public function convertNumberToWordENG($num = false)
    {
        $num = str_replace(array(',', ' '), '' , trim($num));
        if(! $num){
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
        for($i = 0; $i < count($num_levels); $i++){
            $levels--;
            $hundreds = (int) ($num_levels[$i] / 100);
            $hundreds = ($hundreds ? '' .$list1[$hundreds].' HUNDRED' .' ' : '');
            $tens = (int) ($num_levels[$i] % 100);
            $singles = '';
            if($tens < 20){
                $tens = ($tens ? '' . $list1[$tens] .' ' : '' );
            }else{
                $tens = (int)($tens / 10);
                $tens = '' . $list2[$tens] . ' ';
                $singles = (int) ($num_levels[$i] % 10);
                $singles = '' . $list1[$singles] . ' ';
            }
            $words[] = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_levels[$i] ) ) ? '' . $list3[$levels] .' ' : '' );
        } // end for loop
        $commas = count($words);
        if($commas > 1){
            $commas = $commas - 1;
        }
        return implode(' ', $words);
    }
    
}
