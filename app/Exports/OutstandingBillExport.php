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
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;

use DateTime;
use Carbon\Carbon;

class OutstandingBillExport implements FromView, WithEvents, WithColumnWidths, WithColumnFormatting, WithTitle
{
    
    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function __construct($datefr,$dateto,$type)
    {
        $this->datefr = $datefr;
        $this->dateto = $dateto;
        $this->type = $type;
        
        $this->comp = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();
    }
    
    public function columnFormats(): array
    {
        return [
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'H' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'I' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'J' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'K' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }

    public function title(): string
    {
        if($type == 1){
            return "SELF PAID";
        }else{
            return "PANEL";
        }
    }

    public function columnWidths(): array
    {
        return [
            'A' => 12,
            'B' => 25,
            'C' => 12,
            'D' => 12,
            'E' => 12,
            'F' => 12,
            'G' => 15,
            'H' => 15,
            'I' => 15,
            'J' => 15,
            'K' => 15,
            'L' => 15,
            'M' => 15,
        ];
    }
    
    public function view(): View
    {
        $datefr = Carbon::parse($this->datefr)->format('Y-m-d');
        $dateto = Carbon::parse($this->dateto)->format('Y-m-d');
        $type = $this->type;
        
        $dbacthdr1 = DB::table('debtor.dbacthdr as dh')
                    ->select('dh.idno','dh.compcode','dh.source','dh.trantype','dh.auditno','dh.lineno_','dh.amount','dh.outamount','dh.recstatus','dh.entrydate','dh.entrytime','dh.entryuser','dh.reference','dh.recptno','dh.paymode','dh.tillcode','dh.tillno','dh.debtortype','dh.debtorcode','dh.payercode','dh.billdebtor','dh.remark','dh.mrn','dh.episno','dh.authno','dh.expdate','dh.adddate','dh.adduser','dh.upddate','dh.upduser','dh.deldate','dh.deluser','dh.epistype','dh.cbflag','dh.conversion','dh.payername','dh.hdrtype','dh.currency','dh.rate','dh.unit','dh.invno','dh.paytype','dh.bankcharges','dh.RCCASHbalance','dh.RCOSbalance','dh.RCFinalbalance','dh.PymtDescription','dh.orderno','dh.ponum','dh.podate','dh.termdays','dh.termmode','dh.deptcode','dh.posteddate','dh.approvedby','dh.approveddate','dh.approved_remark','dh.unallocated','dh.datesend','dh.quoteno','dh.preparedby','dh.prepareddate','dh.cancelby','dh.canceldate','dh.cancelled_remark','dh.pointofsales','dh.doctorcode','dh.LHDNStatus','dh.LHDNSubID','dh.LHDNCodeNo','dh.LHDNDocID','dh.LHDNSubBy','dh.category','dh.categorydept','dm.name as debtor_name','pmt.Name as patient_name','bs.chgclass','bs.outamt','bs.billno')
                    ->leftJoin('debtor.debtormast as dm', function($join){
                        $join = $join->on('dm.debtorcode', '=', 'dh.payercode')
                                    ->where('dm.compcode', '=', session('compcode'));
                    })->leftJoin('hisdb.pat_mast as pmt', function($join){
                        $join = $join->where('pmt.compcode', '=', session('compcode'))
                                    ->on('pmt.newmrn', '=', 'dh.mrn');
                    })->leftJoin('debtor.billsum as bs', function($join){
                        $join = $join->where('bs.compcode', '=', session('compcode'))
                                    ->on('bs.source', '=', 'dh.source')
                                    ->on('bs.trantype', '=', 'dh.trantype')
                                    ->on('bs.invno', '=', 'dh.invno')
                                    ->on('bs.debtortype', '=', 'dh.debtortype')
                                    ->on('bs.debtorcode', '=', 'dh.payercode')
                                    ->on('bs.lineno_', '=', 'dh.lineno_');
                    })
                    ->where('dh.compcode','=',session('compcode'))
                    ->where('dh.source', '=', 'PB')
                    ->where('dh.trantype', '=', 'IN')
                    ->where('dh.recstatus', '=', 'POSTED')
                    ->whereDate('dh.posteddate', '>=', $datefr)
                    ->whereDate('dh.posteddate', '<=', $dateto)
                    ->whereNotNull('dh.debtortype');

        if($type == 1){
            $dbacthdr1 = $dbacthdr1
                    ->whereIn('dh.debtortype', ['PT','PR']);
        }else{
            $dbacthdr1 = $dbacthdr1
                    ->whereNotIn('dh.debtortype', ['PT','PR']);
        }

        $dbacthdr1 = $dbacthdr1
                    ->orderBy('dh.posteddate','ASC')
                    ->get();

        $dbacthdr1_unq = $dbacthdr1->unique('invno');

        $toth = 0;
        $totc = 0;
        $tott = 0;
        foreach ($dbacthdr1_unq as $db1u_obj) {
            $toth = 0;
            $totc = 0;
            foreach ($dbacthdr1 as $db1_obj) {
                if($db1_obj->invno == $db1u_obj->invno && $db1_obj->debtorcode == $db1u_obj->debtorcode){
                    if($db1_obj->chgclass == 'H'){
                        $toth = $toth + $db1_obj->outamt;
                        $totc = $totc + $db1_obj->outamt;
                    }
                }
            }
            $db1u_obj->toth = $toth;
            $db1u_obj->totc = $totc;
            $db1u_obj->tott = $tott;
        }
        
        $company = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();
        
        return view('finance.AR.OutstandingBill_Report.OutstandingBill_Report_excel',compact('dbacthdr1_unq','company'));
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
