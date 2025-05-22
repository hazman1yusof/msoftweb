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

class ARStatementListingExport implements FromView, WithEvents, WithColumnWidths
{
    
    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function __construct($debtorcode_from,$debtorcode_to,$datefr,$dateto)
    {
        $this->debtorcode_from = $debtorcode_from;
        $this->debtorcode_to = $debtorcode_to;
        // $this->datefr = $datefr;
        $this->dateto = $dateto;
        $this->dbacthdr_len = 0;
        $this->break_loop = [];
        
        $this->comp = DB::table('sysdb.company')
                    ->where('compcode', '=' ,session('compcode'))
                    ->first();
    }
    
    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 15,
            'C' => 12,
            'D' => 25,
            'E' => 15,
            'F' => 15,
            'G' => 15,
        ];
    }
    
    public function view(): View
    {
        // $datefr = Carbon::parse($this->datefr)->format('Y-m-d');
        $dateto = Carbon::parse($this->dateto)->format('Y-m-d');
        $debtorcode_from = $this->debtorcode_from;
        if(empty($this->debtorcode_from)){
            $debtorcode_from = '%';
        }
        $debtorcode_to = $this->debtorcode_to;
        
        $debtormast = DB::table('debtor.dbacthdr as dh')
                    ->select('dh.debtorcode', 'dm.debtorcode', 'dm.name', 'dm.address1', 'dm.address2', 'dm.address3', 'dm.address4')
                    ->leftJoin('debtor.debtormast as dm', function($join){
                        $join = $join->on('dm.debtorcode', '=', 'dh.debtorcode')
                                    ->where('dm.compcode', '=', session('compcode'));
                    })
                    ->where('dh.compcode', '=', session('compcode'))
                    ->whereIn('dh.recstatus', ['POSTED','ACTIVE'])
                    ->whereBetween('dh.debtorcode',[$debtorcode_from,$debtorcode_to.'%'])
                    ->whereDate('dh.posteddate', '<=', $dateto)
                    ->orderBy('dm.debtorcode', 'ASC')
                    ->distinct('dm.debtorcode');
        
        $debtormast = $debtormast->get(['dm.debtorcode', 'dm.name', 'dm.address1', 'dm.address2', 'dm.address3', 'dm.address4']);
        
        $array_report = [];
        $break_loop = [];
        $loop = 0;
        foreach($debtormast as $key => $value){
            $dbacthdr = DB::table('debtor.dbacthdr as dh')
                        ->select('dh.idno', 'dh.source', 'dh.trantype', 'pm.Name', 'dh.auditno', 'dh.lineno_', 'dh.amount', 'dh.outamount', 'dh.recstatus', 'dh.entrydate', 'dh.entrytime', 'dh.entryuser', 'dh.reference', 'dh.recptno', 'dh.paymode', 'dh.tillcode', 'dh.tillno', 'dh.debtortype', 'dh.debtorcode', 'dh.payercode', 'dh.billdebtor', 'dh.remark', 'dh.mrn', 'dh.episno', 'dh.authno', 'dh.expdate', 'dh.adddate', 'dh.adduser', 'dh.upddate', 'dh.upduser', 'dh.deldate', 'dh.deluser', 'dh.epistype', 'dh.cbflag', 'dh.conversion', 'dh.payername', 'dh.hdrtype', 'dh.currency', 'dh.rate', 'dh.unit', 'dh.invno', 'dh.paytype', 'dh.bankcharges', 'dh.RCCASHbalance', 'dh.RCOSbalance', 'dh.RCFinalbalance', 'dh.PymtDescription', 'dh.orderno', 'dh.ponum', 'dh.podate', 'dh.termdays', 'dh.termmode', 'dh.deptcode', 'dh.posteddate', 'dh.approvedby', 'dh.approveddate', 'dh.datesend')
                        ->leftJoin('hisdb.pat_mast as pm', function ($join){
                            $join = $join->on('pm.NewMRN', '=', 'dh.mrn')
                                        ->where('pm.compcode', '=', session('compcode'));
                        })
                        ->where('dh.compcode', '=', session('compcode'))
                        ->whereIn('dh.recstatus', ['POSTED','ACTIVE'])
                        ->where('debtorcode',$value->debtorcode)
                        ->whereDate('dh.posteddate', '<=', $dateto)
                        ->orderBy('dh.posteddate', 'ASC')
                        ->get();
            
            // $calc_openbal = DB::table('debtor.dbacthdr as dh')
            //                 ->where('dh.compcode', '=', session('compcode'))
            //                 ->whereIn('dh.recstatus', ['POSTED','ACTIVE'])
            //                 ->where('dh.debtorcode', '=', $value->debtorcode)
            //                 ->whereDate('dh.posteddate', '<', $datefr);
            
            // $openbal = $this->calc_openbal($calc_openbal);
            // $value->openbal = $openbal;
            
            // $value->datesend = '';
            $value->reference = '';
            $value->amount_dr = 0;
            $value->amount_cr = 0;
            $balance = 0;
            foreach($dbacthdr as $key => $value){
                $loop = $loop + 1;
                switch($value->trantype){
                    case 'IN':
                        // $value->datesend = $value->datesend;
                        if($value->mrn == '0' || $value->mrn == ''){
                            $value->reference = $value->remark;
                        }else{
                            $value->reference = $value->Name;
                        }
                        $value->amount_dr = $value->amount;
                        $balance = $balance + floatval($value->amount);
                        $value->balance = $balance;
                        array_push($array_report, $value);
                        break;
                    case 'DN':
                        $value->reference = $value->remark;
                        $value->amount_dr = $value->amount;
                        $balance = $balance + floatval($value->amount);
                        $value->balance = $balance;
                        array_push($array_report, $value);
                        break;
                    case 'BC':
                        // $value->reference
                        $value->amount_dr = $value->amount;
                        $balance = $balance + floatval($value->amount);
                        $value->balance = $balance;
                        array_push($array_report, $value);
                        break;
                    case 'RF':
                        if($value->mrn == '0' || $value->mrn == ''){
                            $value->reference = $value->remark;
                        }else{
                            $value->reference = $value->Name;
                        }
                        $value->amount_dr = $value->amount;
                        $balance = $balance + floatval($value->amount);
                        $value->balance = $balance;
                        array_push($array_report, $value);
                        break;
                    case 'CN':
                        $value->reference = $value->remark;
                        $value->amount_cr = $value->amount;
                        $balance = $balance - floatval($value->amount);
                        $value->balance = $balance;
                        array_push($array_report, $value);
                        break;
                    case 'RC':
                        $value->reference = $value->recptno;
                        $value->amount_cr = $value->amount;
                        $balance = $balance - floatval($value->amount);
                        $value->balance = $balance;
                        array_push($array_report, $value);
                        break;
                    case 'RD':
                        $value->reference = $value->recptno;
                        $value->amount_cr = $value->amount;
                        $balance = $balance - floatval($value->amount);
                        $value->balance = $balance;
                        array_push($array_report, $value);
                        break;
                    case 'RT':
                        // $value->reference
                        $value->amount_cr = $value->amount;
                        $balance = $balance - floatval($value->amount);
                        $value->balance = $balance;
                        array_push($array_report, $value);
                        break;
                    default:
                        // code...
                        break;
                }
            }
            $loop = $loop + 9;
            array_push($break_loop, $loop);
        }
        
        $this->break_loop = $break_loop;
        
        $title = "STATEMENT LISTING";
        
        $company = DB::table('sysdb.company')
                    ->where('compcode', '=', session('compcode'))
                    ->first();
        
        // $totamount_expld = explode(".", (float)$totalAmount);
        
        // $totamt_eng_rm = $this->convertNumberToWordENG($totamount_expld[0])."";
        // $totamt_eng = $totamt_eng_rm." ONLY";
        
        // if(count($totamount_expld) > 1){
        //     $totamt_eng_sen = $this->convertNumberToWordENG($totamount_expld[1])." CENT";
        //     $totamt_eng = $totamt_eng_rm.$totamt_eng_sen." ONLY";
        // }
        
        return view('finance.AR.arenquiry.ARStatementListingExport_excel', compact('debtormast','array_report','title','company'));
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event){
                foreach($this->break_loop as $value){
                    $event->sheet->setBreak('A'.$value, \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);
                }
                
                $event->sheet->getPageSetup()->setPaperSize(9); // A4
                
                $event->sheet->getHeaderFooter()->setOddHeader('&C'.$this->comp->name."\nSTATEMENT LISTING"."\n".sprintf('DATE ',$this->dateto).'&L'.'PRINTED BY : '.session('username')."\nPAGE : &P/&N".'&R'.'PRINTED DATE : '.Carbon::now("Asia/Kuala_Lumpur")->format('d-m-Y')."\n".'PRINTED TIME : '.Carbon::now("Asia/Kuala_Lumpur")->format('H:i'));
                
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
        } //end for loop
        $commas = count($words);
        if($commas > 1){
            $commas = $commas - 1;
        }
        return implode(' ', $words);
    }
    
    public function getQueries($builder){
        $addSlashes = str_replace('?', "'?'", $builder->toSql());
        return vsprintf(str_replace('?', '%s', $addSlashes), $builder->getBindings());
    }
    
    public function calc_openbal($obj){
        $balance = 0;
        
        foreach($obj->get() as $key => $value){
            switch($value->trantype){
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
