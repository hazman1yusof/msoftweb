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

class DailyBillCollectionExport implements FromView, WithEvents, WithColumnWidths, WithColumnFormatting
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
    
    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'H' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'I' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'J' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'K' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'L' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 12,
            'B' => 12,
            'C' => 15,
            'D' => 25,
            'E' => 13,
            'F' => 10,
            'G' => 10,
            'H' => 10,
            'I' => 10,
            'J' => 10,
            'K' => 10,
            'L' => 15,
            'M' => 15,
        ];
    }
    
    public function view(): View
    {
        $datefr = Carbon::parse($this->datefr)->format('Y-m-d');
        $dateto = Carbon::parse($this->dateto)->format('Y-m-d');
        
        $dbacthdr = DB::table('debtor.dbacthdr as dh')
                    ->select('dh.idno', 'dh.source', 'dh.trantype', 'dh.auditno', 'dh.lineno_', 'dh.amount', 'dh.outamount', 'dh.recstatus', 'dh.entrydate', 'dh.entrytime', 'dh.entryuser', 'dh.reference', 'dh.paymode as dh_paymode', 'dh.tillcode', 'dh.tillno', 'dh.debtortype', 'dh.debtorcode', 'dh.payercode', 'dh.billdebtor', 'dh.remark', 'dh.mrn', 'dh.episno', 'dh.authno', 'dh.expdate', 'dh.adddate', 'dh.adduser', 'dh.upddate', 'dh.upduser', 'dh.deldate', 'dh.deluser', 'dh.epistype', 'dh.cbflag', 'dh.conversion', 'dh.payername', 'dh.hdrtype', 'dh.currency', 'dh.rate', 'dh.unit', 'dh.invno', 'dh.paytype', 'dh.bankcharges', 'dh.RCCASHbalance', 'dh.RCOSbalance', 'dh.RCFinalbalance', 'dh.PymtDescription', 'dh.orderno', 'dh.ponum', 'dh.podate', 'dh.termdays', 'dh.termmode', 'dh.deptcode', 'dh.posteddate', 'dh.approvedby', 'dh.approveddate', 'dm.debtorcode as dm_debtorcode', 'dm.name as dm_name','db.paymode','db.amount as db_amount','db.recptno','pm.paytype as pm_paytype','db.docsource','db.doctrantype','db.docauditno','dh2.amount as amount_dh2','dh2.recptno as recptno_dh2','pmt.name as pmt_name')
                    ->leftJoin('debtor.debtormast as dm', function($join){
                        $join = $join->on('dm.debtorcode', '=', 'dh.payercode')
                                    ->where('dm.compcode', '=', session('compcode'));
                    })->leftJoin('hisdb.pat_mast as pmt', function($join){
                        $join = $join->where('pmt.compcode', '=', session('compcode'))
                                    ->on('pmt.newmrn', '=', 'dh.mrn');
                    })->leftJoin('debtor.dballoc as db', function($join) use ($datefr, $dateto){
                        $join = $join->where('db.compcode', '=', session('compcode'))
                                    ->on('db.refsource', '=', 'dh.source')
                                    ->on('db.reftrantype', '=', 'dh.trantype')
                                    ->on('db.refauditno', '=', 'dh.auditno')
                                    ->whereDate('db.allocdate', '>=', $datefr)
                                    ->whereDate('db.allocdate', '<=', $dateto)
                                    ->where('db.recstatus', '=', 'POSTED');
                                    // ->whereBetween('db.allocdate', [$datefr, $dateto]);
                    })->leftJoin('debtor.dbacthdr as dh2', function($join) use ($datefr, $dateto){
                        $join = $join->where('dh2.compcode', '=', session('compcode'))
                                    ->on('dh2.source', '=', 'db.docsource')
                                    ->on('dh2.trantype', '=', 'db.doctrantype')
                                    ->on('dh2.auditno', '=', 'db.docauditno')
                                    ->whereDate('dh2.posteddate', '>=', $datefr)
                                    ->whereDate('dh2.posteddate', '<=', $dateto)
                                    ->where('dh2.recstatus', '=', 'POSTED');
                                    // ->whereBetween('db.allocdate', [$datefr, $dateto]);
                    })->leftJoin('debtor.paymode as pm', function($join){
                        $join = $join->on('pm.paymode', '=', 'db.paymode')
                                    ->where('pm.source', '=', 'AR')
                                    ->where('pm.compcode', '=', session('compcode'));
                    })
                    ->where('dh.compcode','=',session('compcode'))
                    ->where('dh.source', '=', 'PB')
                    ->where('dh.trantype', '=', 'IN')
                    ->where('dh.recstatus', '=', 'POSTED')
                    ->whereDate('dh.posteddate', '>=', $datefr)
                    ->whereDate('dh.posteddate', '<=', $dateto)
                    // ->whereBetween('dh.posteddate', [$datefr, $dateto])
                    ->orderBy('dh.posteddate','ASC')
                    ->get();

        // dd($dbacthdr);

        $main_db = collect($dbacthdr)->unique(function ($item) {
            return $item->source.$item->trantype.$item->auditno;
        });

        $dbacthdr_arex = [];
        $dbacthdr_ex = DB::table('debtor.dbacthdr as dh')
                        ->select('dh.idno', 'dh.source', 'dh.trantype', 'dh.auditno', 'dh.lineno_', 'dh.amount', 'dh.outamount', 'dh.recstatus', 'dh.entrydate', 'dh.entrytime', 'dh.entryuser', 'dh.reference', 'dh.paymode as dh_paymode', 'dh.tillcode', 'dh.tillno', 'dh.debtortype', 'dh.debtorcode', 'dh.payercode', 'dh.billdebtor', 'dh.remark', 'dh.mrn', 'dh.episno', 'dh.authno', 'dh.expdate', 'dh.adddate', 'dh.adduser', 'dh.upddate', 'dh.upduser', 'dh.deldate', 'dh.deluser', 'dh.epistype', 'dh.cbflag', 'dh.conversion', 'dh.payername', 'dh.hdrtype', 'dh.currency', 'dh.rate', 'dh.unit', 'dh.invno', 'dh.paytype', 'dh.bankcharges', 'dh.RCCASHbalance', 'dh.RCOSbalance', 'dh.RCFinalbalance', 'dh.PymtDescription', 'dh.orderno', 'dh.ponum', 'dh.podate', 'dh.termdays', 'dh.termmode', 'dh.deptcode', 'dh.posteddate', 'dh.approvedby', 'dh.approveddate', 'dm.debtorcode as dm_debtorcode', 'dm.name as dm_name','pm.paytype as pm_paytype','dh.recptno')
                        ->leftJoin('debtor.debtormast as dm', function($join){
                            $join = $join->on('dm.debtorcode', '=', 'dh.payercode')
                                        ->where('dm.compcode', '=', session('compcode'));
                        })->leftJoin('hisdb.pat_mast as pmt', function($join){
                            $join = $join->where('pmt.compcode', '=', session('compcode'))
                                        ->on('pmt.newmrn', '=', 'dh.mrn');
                        })->leftJoin('debtor.paymode as pm', function($join){
                            $join = $join->on('pm.paymode', '=', 'dh.paymode')
                                        ->where('pm.source', '=', 'AR')
                                        ->where('pm.compcode', '=', session('compcode'));
                        })
                        ->where('dh.compcode', '=', session('compcode'))
                        ->where('dh.source', '=', 'PB')
                        ->whereIn('dh.trantype', ['RC','RD','CN'])
                        ->whereDate('dh.posteddate', '>=', $datefr)
                        ->whereDate('dh.posteddate', '<=', $dateto)
                        ->where('dh.recstatus', '=', 'POSTED')
                        ->get();

        foreach ($dbacthdr_ex as $obj) {
                $dballoc_sum = DB::table('debtor.dballoc as db')
                                // ->select('db.source','db.trantype','db.auditno','db.lineno_','db.docsource','db.doctrantype','db.docauditno','db.refsource','db.reftrantype','db.refauditno','db.refamount','db.reflineno','db.recptno','db.mrn','db.episno','db.allocsts','db.amount','db.outamount','db.tillcode','db.debtortype','db.debtorcode','db.payercode','db.paymode','db.allocdate','db.remark','pm.paytype as pm_paytype')
                                // ->leftJoin('debtor.paymode as pm', function($join){
                                //         $join = $join->on('pm.paymode', '=', 'db.paymode')
                                //                     ->where('pm.source', '=', 'AR')
                                //                     ->where('pm.compcode', '=', session('compcode'));
                                //     })
                                ->where('db.compcode',session('compcode'))
                                ->where('db.docsource',$obj->source)
                                ->where('db.doctrantype',$obj->trantype)
                                ->where('db.docauditno',$obj->auditno)
                                ->whereDate('db.allocdate', '<=', $dateto)
                                ->where('db.recstatus', '=', 'POSTED')
                                ->sum('amount');

                $amount_minus = $obj->amount - $dballoc_sum;

                if($amount_minus > 0){
                    $obj->amount_minus = $amount_minus;
                    array_push($dbacthdr_arex, $obj);
                }

        }

        // dd($dbacthdr_arex);

        // foreach ($main_db_ex as $obj_m) {
        //     if
        // }

        // if(count($dbacthdr_ex_ar) > 0){
        //     $main_db_ex = collect($dbacthdr_ex_ar)->unique(function ($item) {
        //         return $item->source.$item->trantype.$item->auditno;
        //     });
        // }

        // dd($dbacthdr);
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
        
        return view('finance.AR.DailyBillCollection_Report.DailyBillCollection_Report_excel',compact('main_db','dbacthdr','dbacthdr_arex','title','company'));
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
