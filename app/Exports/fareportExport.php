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

class fareportExport implements FromView, WithEvents, WithColumnWidths, WithColumnFormatting
{
    
    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function __construct($datefrom,$catfr,$catto)
    {
        $this->datefrom = $datefrom;
        $this->catfr = $catfr;
        $this->catto = $catto;

        $this->comp = DB::table('sysdb.company')
                    ->where('compcode', '=' ,session('compcode'))
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
            'A' => 20,
            'B' => 70,
            'C' => 15,
            'D' => 15,
            'E' => 15,
            'F' => 15,
        ];
    }
    
    public function view(): View
    {
        $datefrom = Carbon::parse($this->datefrom)->format('Y-m-d');
        $fdoydate = Carbon::parse($datefrom)->startOfYear()->format('Y-m-d');
        $company = $this->comp;
        $catfr = $this->catfr;
        $catto = $this->catto;
        if(empty($this->catto)){
            $catto = '%';
        }
        
        $faregister = DB::table('finance.faregister as fa')
                    ->select('fa.compcode','fa.assetcode','fa.assettype','fa.assetno','fa.assetlineno','fa.description','fa.serialno','fa.lotno','fa.casisno','fa.engineno','fa.deptcode','fa.loccode','fa.suppcode','fa.purordno','fa.delordno','fa.delorddate','fa.dolineno','fa.itemcode','fa.invno','fa.invdate','fa.purdate','fa.purprice','fa.origcost','fa.insval','fa.qty','fa.startdepdate','fa.currentcost','fa.lstytddep','fa.cuytddep','fa.recstatus','fa.individualtag','fa.statdate','fa.trantype','fa.trandate','fa.lstdepdate','fa.nprefid','fa.adduser','fa.adddate','fa.upduser','fa.upddate','fa.regtype','fa.nbv','fa.method','fa.residualvalue','fa.currdeptcode','fa.currloccode','fa.condition','fa.expdate','fa.brand','fa.model','fa.equipmentname','fa.trackingno','fa.bem_no','fa.ppmschedule','fa.lastcomputerid','fc.description as fc_desc','fc.rate')
                    ->leftJoin('finance.facode as fc', function($join){
                        $join = $join->where('fc.compcode', '=', session('compcode'))
                                     ->on('fc.assetcode', '=', 'fa.assetcode');
                    })
                    ->where('fa.compcode', '=', session('compcode'));

        if($catfr == $catto){
            $faregister = $faregister->where('fa.assetcode',$catto);
        }else{
            $faregister = $faregister->whereBetween('fa.assetcode',[$catfr,$catto]);
        }
            $faregister = $faregister
                                    ->orderBy('fa.assetcode', 'ASC')
                                    ->get();

        foreach ($faregister as $obj) {

            $obj->dispcost = 0.00;
            if($obj->recstatus == 'DEACTIVE'){
                if($obj->trantype == 'WOF' || $obj->trantype == 'DIS'){
                    $obj->dispcost = $obj->origcost;
                }
            }

            $obj->closecost = $obj->origcost - $obj->dispcost;

            $opendepr = DB::table('finance.fatran')
                            ->where('compcode',session('compcode'))
                            ->where('trantype','DEP')
                            ->where('assetno',$obj->assetno)
                            ->whereDate('trandate','<',$fdoydate)
                            ->sum('amount');

            $adddepr = DB::table('finance.fatran')
                            ->where('compcode',session('compcode'))
                            ->where('trantype','DEP')
                            ->where('assetno',$obj->assetno)
                            ->whereDate('trandate','>=',$fdoydate)
                            ->whereDate('trandate','<=',$datefrom)
                            ->sum('amount');

            $obj->opendepr = $opendepr;
            $obj->adddepr = $adddepr;
            $obj->dispdepr = 0.00;
            $obj->closedepr = $opendepr + $adddepr - 0.00;
            $obj->nbvamt = $obj->closecost - $obj->closedepr;
        }
        
        $title = "FIXED ASSET LISTING BY CATEGORY";
        $datefr_desc = Carbon::parse($datefrom)->format('d/m/Y');
        
        $assetcode = $faregister->unique('assetcode');
        
        return view('finance.FA.assetreport.assetreport_excel', compact('faregister','assetcode','datefr_desc','title','company','fdoydate'));
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                foreach ($this->break_loop as $value) {
                    $event->sheet->setBreak('A'.$value, \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);
                }
                
                $event->sheet->getPageSetup()->setPaperSize(9);//A4
                
                $event->sheet->getHeaderFooter()->setOddHeader('&C'.$this->comp->name."\nAR AGEING SUMMARY"."\n".sprintf('FROM DATE %s TO DATE %s',$this->datefr, $this->dateto).'&L'.'PRINTED BY : '.session('username')."\nPAGE : &P/&N".'&R'.'PRINTED DATE : '.Carbon::now("Asia/Kuala_Lumpur")->format('d-m-Y')."\n".'PRINTED TIME : '.Carbon::now("Asia/Kuala_Lumpur")->format('H:i'));
                
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
    
    public function calc_bal($obj){
        $balance = 0;
        
        foreach ($obj->get() as $key => $value){
            switch ($value->trantype) {
                case 'IN':
                    $balance = $balance + floatval($value->amount);
                    break;
                case 'DN':
                    $balance = $balance + floatval($value->amount);
                    break;
                case 'BC':
                    $balance = $balance + floatval($value->amount);
                    break;
                case 'RF':
                    $balance = $balance + floatval($value->amount);
                    break;
                case 'CN':
                    $balance = $balance - floatval($value->amount);
                    break;
                case 'RC':
                    $balance = $balance - floatval($value->amount);
                    break;
                case 'RD':
                    $balance = $balance - floatval($value->amount);
                    break;
                case 'RT':
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
