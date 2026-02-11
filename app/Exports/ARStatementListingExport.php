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

class ARStatementListingExport implements FromView, WithEvents, WithColumnWidths, WithColumnFormatting
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

        $this->groupOne = 30;
        $this->groupTwo = 60;
        $this->groupThree = 90;
        $this->groupFour = 120;
        // $this->groupFive = $groupFive;
        // $this->groupSix = $groupSix;

        $this->grouping = [];
        $this->grouping_tot = [];
        $this->grouping[0] = 0;
        $this->grouping_tot[0] = 0;
        if(!empty($this->groupOne)){
            $this->grouping[1] = $this->groupOne;
            $this->grouping_tot[1] = 0;
        }
        if(!empty($this->groupTwo)){
            $this->grouping[2] = $this->groupTwo;
            $this->grouping_tot[2] = 0;
        }
        if(!empty($this->groupThree)){
            $this->grouping[3] = $this->groupThree;
            $this->grouping_tot[3] = 0;
        }
        if(!empty($this->groupFour)){
            $this->grouping[4] = $this->groupFour;
            $this->grouping_tot[4] = 0;
        }
        // if(!empty($this->groupFive)){
        //     $this->grouping[5] = $this->groupFive;
        // }
        // if(!empty($this->groupSix)){
        //     $this->grouping[6] = $this->groupSix;
        // }
        
        $this->comp = DB::table('sysdb.company')
                    ->where('compcode', '=' ,session('compcode'))
                    ->first();
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            // 'C' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }
    
    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 15,
            'C' => 15,
            'D' => 15,
            'E' => 35,
            'F' => 15,
            'G' => 15,
            'H' => 15,
            'I' => 18,
            'J' => 15,
        ];
    }
    
    public function view(): View
    {
        // $datefr = Carbon::parse($this->datefr)->format('Y-m-d');
        $date = Carbon::parse($this->dateto)->format('Y-m-d');
        $firstDay = Carbon::parse($this->dateto)->startOfMonth()->format('Y-m-d');
        $date_asof = Carbon::parse($this->dateto)->format('d-m-Y');
        $debtorcode_from = $this->debtorcode_from;
        if(empty($this->debtorcode_from)){
            $debtorcode_from = '%';
        }
        $debtorcode_to = $this->debtorcode_to;
        $grouping = $this->grouping;
        $grouping_tot = $this->grouping_tot;
        
        $debtormast = DB::table('debtor.debtormast as dm')
                        ->select('dh.idno', 'dh.source', 'dh.trantype', 'dh.auditno', 'dh.lineno_', 'dh.amount', 'dh.outamount', 'dh.recstatus', 'dh.entrydate', 'dh.entrytime', 'dh.entryuser', 'dh.reference', 'dh.reference as real_reference', 'dh.recptno', 'dh.paymode', 'dh.tillcode', 'dh.tillno', 'dh.debtortype', 'dh.debtorcode', 'dh.payercode', 'dh.billdebtor', 'dh.remark', 'dh.mrn', 'dh.episno', 'dh.authno', 'dh.expdate', 'dh.adddate', 'dh.adduser', 'dh.upddate', 'dh.upduser', 'dh.deldate', 'dh.deluser', 'dh.epistype', 'dh.cbflag', 'dh.conversion', 'dh.payername', 'dh.hdrtype', 'dh.currency', 'dh.rate', 'dh.unit', 'dh.invno', 'dh.paytype', 'dh.bankcharges', 'dh.RCCASHbalance', 'dh.RCOSbalance', 'dh.RCFinalbalance', 'dh.PymtDescription', 'dh.orderno', 'dh.ponum', 'dh.podate', 'dh.termdays', 'dh.termmode', 'dh.deptcode', 'dh.posteddate', 'dh.approvedby', 'dh.approveddate', 'pm.Name as pm_name','dm.debtortype','dm.name','dm.address1','dm.address2','dm.address3','dm.address4','dm.creditterm','dm.creditlimit','dh.datesend')
                        // ->join('debtor.debtortype as dt', function($join){
                        //     $join = $join->on('dt.debtortycode', '=', 'dm.debtortype')
                        //                  ->where('dt.compcode', '=', session('compcode'));
                        // })
                        ->join('debtor.dbacthdr as dh', function($join) use ($date){
                            $join = $join->on('dh.debtorcode', '=', 'dm.debtorcode')
                                         // ->where('dh.auditno', '5213940')
                                         ->whereDate('dh.posteddate', '<=', $date)
                                         ->where('dh.compcode', '=', session('compcode'))
                                         ->where('dh.recstatus', '=', 'POSTED');
                        })->leftJoin('hisdb.pat_mast as pm', function($join){
                            $join = $join->on('pm.NewMrn', '=', 'dh.mrn')
                                         // ->where('pm.NewMrn', '<>', '')
                                         ->where('pm.compcode', '=', session('compcode'));
                        })
                        ->where('dm.compcode', '=', session('compcode'));

                        if($debtorcode_from != $debtorcode_to){
                            $debtormast = $debtormast->whereBetween('dm.debtorcode', [$debtorcode_from,$debtorcode_to.'%']);
                        }else{
                            $debtormast = $debtormast->where('dm.debtorcode', $debtorcode_from);
                        }

                        $debtormast = $debtormast
                                        ->orderBy('dm.debtorcode', 'ASC')
                                        ->get();

        // dd($this->getQueries($debtormast));

        $db_rc = DB::table('debtor.dbacthdr as db1')
                    ->select('db1.source as db1_source','db1.trantype as db1_trantype','db1.auditno as db1_auditno','db1.lineno_ as db1_lineno_','db1.amount as db1_amount','db1.outamount as db1_outamount','db1.recstatus as db1_recstatus','db1.reference as db1_reference','db1.recptno as db1_recptno','db1.paymode as db1_paymode','db1.tillcode as db1_tillcode','db1.tillno as db1_tillno','db1.debtortype as db1_debtortype','db1.debtorcode as db1_debtorcode','db1.payercode as db1_payercode','db1.billdebtor as db1_billdebtor','db1.remark as db1_remark','db1.mrn as db1_mrn','db1.episno as db1_episno','db1.epistype as db1_epistype','db1.cbflag as db1_cbflag','db1.conversion as db1_conversion','db1.payername as db1_payername','db1.currency as db1_currency','db1.rate as db1_rate','db1.unit as db1_unit','db1.invno as db1_invno','db1.orderno as db1_orderno','db1.ponum as db1_ponum','db1.podate as db1_podate','db1.termdays as db1_termdays','db1.termmode as db1_termmode','db1.deptcode as db1_deptcode','db1.posteddate as db1_posteddate','db2.source as db2_source','db2.trantype as db2_trantype','db2.auditno as db2_auditno','db2.lineno_ as db2_lineno_','db2.amount as db2_amount','db2.outamount as db2_outamount','db2.recstatus as db2_recstatus','db2.reference as db2_reference','db2.recptno as db2_recptno','db2.paymode as db2_paymode','db2.tillcode as db2_tillcode','db2.tillno as db2_tillno','db2.debtortype as db2_debtortype','db2.debtorcode as db2_debtorcode','db2.payercode as db2_payercode','db2.billdebtor as db2_billdebtor','db2.remark as db2_remark','db2.mrn as db2_mrn','db2.episno as db2_episno','db2.epistype as db2_epistype','db2.cbflag as db2_cbflag','db2.conversion as db2_conversion','db2.payername as db2_payername','db2.currency as db2_currency','db2.rate as db2_rate','db2.unit as db2_unit','db2.invno as db2_invno','db2.orderno as db2_orderno','db2.ponum as db2_ponum','db2.podate as db2_podate','db2.termdays as db2_termdays','db2.termmode as db2_termmode','db2.deptcode as db2_deptcode','db2.posteddate as db2_posteddate','da.docsource as da_docsource','da.doctrantype as da_doctrantype','da.docauditno as da_docauditno','da.refsource as da_refsource','da.reftrantype as da_reftrantype','da.refauditno as da_refauditno','da.amount as da_allocamount','pm.Name as pm_name')
                    ->join('debtor.dballoc as da', function($join) {
                        $join = $join->on('da.docsource', '=', 'db1.source')
                                     ->on('da.doctrantype', '=', 'db1.trantype')
                                     ->on('da.docauditno', '=', 'db1.auditno')
                                     ->where('da.compcode', '=', session('compcode'));
                    })
                    ->join('debtor.dbacthdr as db2', function($join) {
                        $join = $join->on('db2.source', '=', 'da.refsource')
                                     ->on('db2.trantype', '=', 'da.reftrantype')
                                     ->on('db2.auditno', '=', 'da.refauditno')
                                     ->where('db2.compcode', '=', session('compcode'));
                    })
                    ->leftJoin('hisdb.pat_mast as pm', function($join){
                        $join = $join->on('pm.NewMrn', '=', 'db2.mrn')
                                     // ->where('pm.NewMrn', '<>', '')
                                     ->where('pm.compcode', '=', session('compcode'));
                    })
                    ->where('db1.compcode','=',session('compcode'))
                    ->where('db1.source','PB')
                    ->where('db1.trantype','RC')
                    ->whereBetween('db1.debtorcode', [$debtorcode_from,$debtorcode_to.'%'])
                    ->where('db1.recstatus','POSTED')
                    ->whereDate('db1.posteddate', '<=', $date)
                    ->whereDate('db1.posteddate', '>=', $firstDay)
                    ->get();

        $db_rc_main = $db_rc->unique('db1_auditno');

        // dd($db_rc_main);
        
        $array_report = [];

        foreach ($debtormast as $key => $value){
            $value->remark = '';
            $value->doc_no = '';
            $value->newamt = 0;

            $hdr_amount = $value->amount;
            
            // to calculate interval (days)
            $datetime1 = new DateTime($date);
            $datetime2 = new DateTime($value->posteddate);
            
            $interval = $datetime1->diff($datetime2);
            $days = $interval->format('%a');
            $value->group = $this->assign_grouping($grouping,$days);
            $value->days = $days;
            
            if($value->trantype == 'IN' || $value->trantype =='DN') {
                $alloc_sum = DB::table('debtor.dballoc as da')
                        ->where('da.compcode', '=', session('compcode'))
                        ->where('da.recstatus', '=', "POSTED")
                        // ->where('da.debtorcode', '=', $value->debtorcode)
                        ->where('da.refsource', '=', $value->source)
                        ->where('da.reftrantype', '=', $value->trantype)
                        ->where('da.refauditno', '=', $value->auditno)
                        // ->where('da.reflineno', '=', $value->lineno_)
                        ->whereDate('da.allocdate', '<=', $date)

                // dd($this->getQueries($alloc_sum));


                        ->sum('da.amount');
                
                $newamt = $hdr_amount - $alloc_sum;
            }else{
                $doc_sum = DB::table('debtor.dballoc as da')
                        ->where('da.compcode', '=', session('compcode'))
                        ->where('da.recstatus', '=', "POSTED")
                        // ->where('da.debtorcode', '=', $value->debtorcode)
                        ->where('da.docsource', '=', $value->source)
                        ->where('da.doctrantype', '=', $value->trantype)
                        ->where('da.docauditno', '=', $value->auditno)
                        ->whereDate('da.allocdate', '<=', $date)

                // dump($this->getQueries($doc_sum));

                        ->sum('da.amount');
                
                $ref_sum = DB::table('debtor.dballoc as da')
                        ->where('da.compcode', '=', session('compcode'))
                        ->where('da.recstatus', '=', "POSTED")
                        // ->where('da.debtorcode', '=', $value->debtorcode)
                        ->where('da.refsource', '=', $value->source)
                        ->where('da.reftrantype', '=', $value->trantype)
                        ->where('da.refauditno', '=', $value->auditno)
                        ->whereDate('da.allocdate', '<=', $date)

                // dd($this->getQueries($ref_sum));

                        ->sum('da.amount');
                
                $newamt = -($hdr_amount - $doc_sum - $ref_sum);
            }
            
            switch ($value->trantype) {
                case 'IN':
                    if($value->mrn == '0' || $value->mrn == ''){
                        if(!empty($value->payername)){
                            $value->reference = $value->payername;
                        }
                    }else{
                        $value->reference = str_replace('`', '', $value->pm_name);
                    }
                    $value->doc_no = $value->trantype.'/'.str_pad($value->invno, 7, "0", STR_PAD_LEFT);
                    $value->amount_dr = $newamt;
                    $value->newamt = $newamt;
                    if(floatval($newamt) != 0.00){
                        array_push($array_report, $value);
                    }
                    break;
                case 'DN':
                    $value->reference = $value->reference;
                    $value->doc_no = $value->trantype.'/'.str_pad($value->auditno, 7, "0", STR_PAD_LEFT);
                    $value->amount_dr = $newamt;
                    $value->newamt = $newamt;
                    if(floatval($newamt) != 0.00){
                        array_push($array_report, $value);
                    }
                    break;
                case 'BC':
                    // $value->remark
                    $value->doc_no = $value->trantype.'/'.str_pad($value->auditno, 7, "0", STR_PAD_LEFT);
                    $value->amount_dr = $newamt;
                    $value->newamt = $newamt;
                    if(floatval($newamt) != 0.00){
                        array_push($array_report, $value);
                    }
                    break;
                case 'RF':
                    if($value->mrn == '0' || $value->mrn == ''){
                        // $value->reference = $value->remark;
                        $value->reference = $value->reference;
                    }else{
                        $value->reference = str_replace('`', '', $value->pm_name);
                    }
                    $value->doc_no = $value->recptno;
                    $value->amount_dr = $newamt;
                    $value->newamt = $newamt;
                    if(floatval($newamt) != 0.00){
                        array_push($array_report, $value);
                    }
                    break;
                case 'CN':
                    $value->remark = $value->remark;
                    $value->doc_no = $value->trantype.'/'.str_pad($value->auditno, 7, "0", STR_PAD_LEFT);
                    $value->amount_cr = $newamt;
                    $value->newamt = $newamt;
                    if(floatval($newamt) != 0.00){
                        array_push($array_report, $value);
                    }
                    break;
                case 'RC':
                    $value->remark = $value->remark;
                    $value->doc_no = $value->recptno;
                    $value->reference = $value->recptno;
                    $value->amount_cr = $newamt;
                    $value->newamt = $newamt;
                    if(floatval($newamt) != 0.00){
                        array_push($array_report, $value);
                    }
                    break;
                case 'RD':
                    $value->remark = $value->remark;
                    $value->doc_no = $value->recptno;
                    $value->reference = $value->recptno;
                    $value->amount_cr = $newamt;
                    $value->newamt = $newamt;
                    if(floatval($newamt) != 0.00){
                        array_push($array_report, $value);
                    }
                    break;
                case 'RT':
                    // $value->remark
                    $value->doc_no = $value->trantype.'/'.str_pad($value->auditno, 7, "0", STR_PAD_LEFT);
                    $value->amount_cr = $newamt;
                    $value->newamt = $newamt;
                    if(floatval($newamt) != 0.00){
                        array_push($array_report, $value);
                    }
                    break;
                default:
                    // code...
                    break;
            }

            $grouping_tot[$value->group] = $grouping_tot[$value->group] + $newamt;
        }
        
        $title = "STATEMENT LISTING";
        
        $company = DB::table('sysdb.company')
                    ->where('compcode', '=', session('compcode'))
                    ->first();

        $debtormast = collect($array_report)->unique('debtorcode');
        $datenow = Carbon::now("Asia/Kuala_Lumpur")->format('d-m-Y');
        // dd($array_report);
        
        // $totamount_expld = explode(".", (float)$totalAmount);
        
        // $totamt_eng_rm = $this->convertNumberToWordENG($totamount_expld[0])."";
        // $totamt_eng = $totamt_eng_rm." ONLY";
        
        // if(count($totamount_expld) > 1){
        //     $totamt_eng_sen = $this->convertNumberToWordENG($totamount_expld[1])." CENT";
        //     $totamt_eng = $totamt_eng_rm.$totamt_eng_sen." ONLY";
        // }

        
        return view('finance.AR.arenquiry.ARStatementListingExport_excel', compact('debtormast','db_rc_main','db_rc','array_report','grouping','grouping_tot','title','company','date_asof','datenow'));
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event){
                foreach($this->break_loop as $value){
                    $event->sheet->setBreak('A'.$value, \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);
                }
                
                $event->sheet->getPageSetup()->setPaperSize(9); // A4
                
                // $event->sheet->getHeaderFooter()->setOddHeader('&C'.$this->comp->name."\nSTATEMENT LISTING"."\n".sprintf('DATE ',$this->dateto).'&L'.'PRINTED BY : '.session('username')."\nPAGE : &P/&N".'&R'.'PRINTED DATE : '.Carbon::now("Asia/Kuala_Lumpur")->format('d-m-Y')."\n".'PRINTED TIME : '.Carbon::now("Asia/Kuala_Lumpur")->format('H:i'));
                
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

    public function assign_grouping($grouping,$days){
        $group = 0;

        foreach ($grouping as $key => $value) {
            if(!empty($value) && $days >= intval($value)){
                $group = $key;
            }
        }

        return $group;
    }
    
}
