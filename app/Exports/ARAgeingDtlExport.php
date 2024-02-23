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

class ARAgeingDtlExport implements FromView, WithEvents, WithColumnWidths
{
    
    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function __construct($debtorcode_from,$debtorcode_to,$date,$groupOne,$groupTwo,$groupThree,$groupFour,$groupFive,$groupSix)
    {
        $this->debtorcode_from = $debtorcode_from;
        $this->debtorcode_to = $debtorcode_to;
        $this->date = $date;
        $this->groupOne = $groupOne;
        $this->groupTwo = $groupTwo;
        $this->groupThree = $groupThree;
        $this->groupFour = $groupFour;
        $this->groupFive = $groupFive;
        $this->groupSix = $groupSix;
        
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
            'B' => 25,
            'C' => 20,
            'D' => 12,
            'E' => 12,
            'F' => 12,
            'G' => 12,
            'H' => 12,
            'I' => 12,
            'J' => 12,
            'K' => 12,
        ];
    }
    
    public function view(): View
    {
        $date = Carbon::parse($this->date)->format('Y-m-d');
        $debtorcode_from = $this->debtorcode_from;
        if(empty($this->debtorcode_from)){
            $debtorcode_from = '%';
        }
        $debtorcode_to = $this->debtorcode_to;
        $groupOne = $this->groupOne;
        $groupTwo = $this->groupTwo;
        $groupThree = $this->groupThree;
        $groupFour = $this->groupFour;
        $groupFive = $this->groupFive;
        $groupSix = $this->groupSix;
        
        $debtortype = DB::table('debtor.dbacthdr as dh')
                    // ->select('dh.debtorcode', 'dh.posteddate', 'dm.debtortype', 'dm.debtorcode', 'dm.name', 'dt.debtortycode', 'dt.description')
                    ->select('dt.debtortycode', 'dt.description')
                    ->leftJoin('debtor.debtormast as dm', function($join){
                        $join = $join->on('dm.debtorcode', '=', 'dh.debtorcode')
                                    ->where('dm.compcode', '=', session('compcode'));
                    })
                    ->leftJoin('debtor.debtortype as dt', function($join){
                        $join = $join->on('dt.debtortycode', '=', 'dm.debtortype')
                                    ->where('dt.compcode', '=', session('compcode'));
                    })
                    ->where('dh.compcode', '=', session('compcode'))
                    ->whereIn('dh.recstatus', ['POSTED','ACTIVE'])
                    ->whereBetween('dh.debtorcode', [$debtorcode_from,$debtorcode_to.'%'])
                    ->where('dh.outamount','>',0)
                    ->whereDate('dh.posteddate', '<=', $date)
                    ->orderBy('dt.debtortycode', 'ASC')
                    ->distinct('dt.debtortycode');
        
        $debtortype = $debtortype->get(['dt.debtortycode', 'dt.description']);
        
        $debtorcode = DB::table('debtor.dbacthdr as dh')
                    ->select('dm.debtortype', 'dm.debtorcode', 'dm.name')
                    ->leftJoin('debtor.debtormast as dm', function($join){
                        $join = $join->on('dm.debtorcode', '=', 'dh.debtorcode')
                                    ->where('dm.compcode', '=', session('compcode'));
                    })
                    ->where('dh.compcode', '=', session('compcode'))
                    ->whereIn('dh.recstatus', ['POSTED','ACTIVE'])
                    ->whereBetween('dh.debtorcode', [$debtorcode_from,$debtorcode_to.'%'])
                    ->where('dh.outamount','>',0)
                    ->whereDate('dh.posteddate', '<=', $date)
                    ->orderBy('dm.debtorcode', 'ASC')
                    ->distinct('dm.debtorcode');
        
        $debtorcode = $debtorcode->get(['dm.debtortype', 'dm.debtorcode', 'dm.name']);
        
        $array_report = [];
        foreach ($debtorcode as $key => $value){
            $dbacthdr = DB::table('debtor.dbacthdr as dh')
                        ->select('dh.idno', 'dh.source', 'dh.trantype', 'dh.auditno', 'dh.lineno_', 'dh.amount', 'dh.outamount', 'dh.recstatus', 'dh.entrydate', 'dh.entrytime', 'dh.entryuser', 'dh.reference', 'dh.recptno', 'dh.paymode', 'dh.tillcode', 'dh.tillno', 'dh.debtortype', 'dh.debtorcode', 'dh.payercode', 'dh.billdebtor', 'dh.remark', 'dh.mrn', 'dh.episno', 'dh.authno', 'dh.expdate', 'dh.adddate', 'dh.adduser', 'dh.upddate', 'dh.upduser', 'dh.deldate', 'dh.deluser', 'dh.epistype', 'dh.cbflag', 'dh.conversion', 'dh.payername', 'dh.hdrtype', 'dh.currency', 'dh.rate', 'dh.unit', 'dh.invno', 'dh.paytype', 'dh.bankcharges', 'dh.RCCASHbalance', 'dh.RCOSbalance', 'dh.RCFinalbalance', 'dh.PymtDescription', 'dh.orderno', 'dh.ponum', 'dh.podate', 'dh.termdays', 'dh.termmode', 'dh.deptcode', 'dh.posteddate', 'dh.approvedby', 'dh.approveddate', 'pm.Name')
                        ->leftJoin('hisdb.pat_mast as pm', function($join){
                            $join = $join->on('pm.MRN', '=', 'dh.mrn')
                                        ->where('pm.compcode', '=', session('compcode'));
                        })
                        ->where('dh.compcode', '=', session('compcode'))
                        ->whereIn('dh.recstatus', ['POSTED','ACTIVE'])
                        ->where('dh.debtorcode', $value->debtorcode)
                        ->where('dh.outamount','>',0)
                        ->whereDate('dh.posteddate', '<=', $date)
                        ->orderBy('dh.posteddate', 'ASC')
                        ->get();
            
            $value->remark = '';
            $value->doc_no = '';
            $value->groupOne = 0;
            $value->groupTwo = 0;
            $value->groupThree = 0;
            $value->groupFour = 0;
            $value->groupFive = 0;
            $value->groupSix = 0;
            $value->newamt = 0;
            foreach ($dbacthdr as $key => $value){
                $hdr_amount = $value->amount;
                
                // to calculate interval (days)
                $datetime1 = new DateTime($date);
                $datetime2 = new DateTime($value->posteddate);
                
                $interval = $datetime1->diff($datetime2);
                $days = $interval->format('%a');
                
                if($value->trantype == 'IN' || $value->trantype =='DN') {
                    $alloc_sum = DB::table('debtor.dballoc as da')
                            ->where('da.compcode', '=', session('compcode'))
                            ->where('da.debtorcode', '=', $value->debtorcode)
                            ->where('da.refsource', '=', $value->source)
                            ->where('da.reftrantype', '=', $value->trantype)
                            ->where('da.refauditno', '=', $value->auditno)
                            ->where('da.recstatus', '=', "POSTED")
                            ->whereDate('da.allocdate', '<=', $date)
                            ->sum('da.amount');
                    
                    $newamt = $hdr_amount - $alloc_sum;
                }else{
                    $doc_sum = DB::table('debtor.dballoc as da')
                            ->where('da.compcode', '=', session('compcode'))
                            ->where('da.debtorcode', '=', $value->debtorcode)
                            ->where('da.docsource', '=', $value->source)
                            ->where('da.doctrantype', '=', $value->trantype)
                            ->where('da.docauditno', '=', $value->auditno)
                            ->where('da.recstatus', '=', "POSTED")
                            ->whereDate('da.allocdate', '<=', $date)
                            ->sum('da.amount');
                    
                    $ref_sum = DB::table('debtor.dballoc as da')
                            ->where('da.compcode', '=', session('compcode'))
                            ->where('da.debtorcode', '=', $value->debtorcode)
                            ->where('da.refsource', '=', $value->source)
                            ->where('da.reftrantype', '=', $value->trantype)
                            ->where('da.refauditno', '=', $value->auditno)
                            ->where('da.recstatus', '=', "POSTED")
                            ->whereDate('da.allocdate', '<=', $date)
                            ->sum('da.amount');
                    
                    $newamt = -($hdr_amount - $doc_sum - $ref_sum);
                }
                
                // $calc_ageing = $this->calc_ageing($newamt,$days,$groupOne,$groupTwo,$groupThree,$groupFour,$groupFive,$groupSix);
                
                switch ($value->trantype) {
                    case 'IN':
                        if($value->mrn == '0' || $value->mrn == ''){
                            $value->remark = $value->remark;
                        }else{
                            $value->remark = $value->Name;
                        }
                        $value->doc_no = $value->trantype.'/'.str_pad($value->auditno, 5, "0", STR_PAD_LEFT);
                        $value->newamt = $newamt;
                        
                        array_push($array_report, $value);
                        break;
                    case 'DN':
                        $value->remark = $value->remark;
                        $value->doc_no = $value->trantype.'/'.str_pad($value->auditno, 5, "0", STR_PAD_LEFT);
                        $value->newamt = $newamt;
                        
                        array_push($array_report, $value);
                        break;
                    case 'BC':
                        // $value->remark
                        $value->doc_no = $value->trantype.'/'.str_pad($value->auditno, 5, "0", STR_PAD_LEFT);
                        $value->newamt = $newamt;
                        
                        array_push($array_report, $value);
                        break;
                    case 'RF':
                        $value->remark = $value->remark;
                        $value->doc_no = $value->recptno;
                        $value->newamt = $newamt;
                        
                        array_push($array_report, $value);
                        break;
                    case 'CN':
                        $value->remark = $value->remark;
                        $value->doc_no = $value->trantype.'/'.str_pad($value->auditno, 5, "0", STR_PAD_LEFT);
                        $value->newamt = $newamt;
                        
                        array_push($array_report, $value);
                        break;
                    case 'RC':
                        $value->remark = $value->remark;
                        $value->doc_no = $value->recptno;
                        $value->newamt = $newamt;
                        
                        array_push($array_report, $value);
                        break;
                    case 'RD':
                        $value->remark = $value->remark;
                        $value->doc_no = $value->recptno;
                        $value->newamt = $newamt;
                        
                        array_push($array_report, $value);
                        break;
                    case 'RT':
                        // $value->remark
                        $value->doc_no = $value->trantype.'/'.str_pad($value->auditno, 5, "0", STR_PAD_LEFT);
                        $value->newamt = $newamt;
                        
                        array_push($array_report, $value);
                        break;
                    default:
                        // code...
                        break;
                }
            }
        }
        
        // dd($array_report);
        
        $title = "AR AGEING DETAILS";
        
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
        
        return view('finance.AR.ARAgeingDtl_Report.ARAgeingDtl_Report_excel', compact('debtortype','debtorcode','array_report','title','company'));
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                foreach ($this->break_loop as $value) {
                    $event->sheet->setBreak('A'.$value, \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);
                }
                
                $event->sheet->getPageSetup()->setPaperSize(9);//A4
                
                $event->sheet->getHeaderFooter()->setOddHeader('&C'.$this->comp->name."\nAR AGEING DETAILS"."\n".sprintf('AS AT %s',$this->date).'&L'.'PRINTED BY : '.session('username')."\nPAGE : &P/&N".'&R'.'PRINTED DATE : '.Carbon::now("Asia/Kuala_Lumpur")->format('d-m-Y')."\n".'PRINTED TIME : '.Carbon::now("Asia/Kuala_Lumpur")->format('H:i'));
                
                $event->sheet->getPageMargins()->setTop(1);
                
                $event->sheet->getPageSetup()->setRowsToRepeatAtTop([1,1]);
                $event->sheet->getStyle('A:K')->getAlignment()->setWrapText(true);
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
    
    public function calc_ageing($newamt,$days,$groupOne,$groupTwo,$groupThree,$groupFour,$groupFive,$groupSix){
        $groupOne = range(1, $groupOne);
        $groupOne_last = $groupOne[count($groupOne) - 1];
        $groupOne_text = '1 - '.$groupOne_last.' days';
        
        $groupTwo_first = $groupOne_last + 1;
        $groupTwo = range($groupTwo_first, $groupTwo);
        $groupTwo_last = $groupTwo[count($groupTwo) - 1];
        $groupTwo_text = $groupTwo_first.' - '.$groupTwo_last.' days';
        
        $groupThree_first = $groupTwo_last + 1;
        $groupThree = range($groupThree_first, $groupThree);
        $groupThree_last = $groupThree[count($groupThree) - 1];
        $groupThree_text = $groupThree_first.' - '.$groupThree_last.' days';
        
        $groupFour_first = $groupThree_last + 1;
        $groupFour = range($groupFour_first, $groupFour);
        $groupFour_last = $groupFour[count($groupFour) - 1];
        $groupFour_text = $groupFour_first.' - '.$groupFour_last.' days';
        
        $groupFive_first = $groupFour_last + 1;
        $groupFive = range($groupFive_first, $groupFive);
        $groupFive_last = $groupFive[count($groupFive) - 1];
        $groupFive_text = $groupFive_first.' - '.$groupFive_last.' days';
        
        $groupSix_first = $groupFive_last + 1;
        $groupSix = range($groupSix_first, $groupSix);
        $groupSix_last = $groupSix[count($groupSix) - 1];
        $groupSix_text = '> '.$groupFive_last.' days';
        
        // dd($groupOne_text,$groupTwo_text,$groupThree_text,$groupFour_text,$groupFive_text,$groupSix_text);
    }
    
}
