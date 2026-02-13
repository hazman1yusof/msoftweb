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
use stdClass;

class check_cbtran_xde implements FromView, WithEvents, WithColumnWidths
{
    
    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function __construct($year,$period)
    {
        $this->year = $year;
        $this->period = $period;
    }
    
    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 15,
            'C' => 15,
            'D' => 15,
            'E' => 15,
            'F' => 15,
            'G' => 15,
            'H' => 15,
        ];
    }
    
    public function view(): View
    {
        $day_start = Carbon::createFromFormat('Y-m-d',$this->year.'-'.$this->period.'-01')->startOfMonth()->format('Y-m-d');
        $day_end = Carbon::createFromFormat('Y-m-d',$this->year.'-'.$this->period.'-01')->endOfMonth()->format('Y-m-d');

        $cbtran = DB::table('finance.cbtran as cb')
                        ->where('cb.compcode',session('compcode'))
                        ->where('cb.source','PB')
                        ->where('cb.trantype','RC')
                        ->where('cb.year',$this->year)
                        ->where('cb.period',$this->period)
                        ->whereDate('cb.postdate','>=',$day_start)
                        ->whereDate('cb.postdate','<=',$day_end)
                        ->get()
                        ->pluck('auditno')->toArray();

        $dbacthdr = DB::table('debtor.dbacthdr as db')
                        ->select('db.compcode','db.source','db.trantype','db.auditno','db.lineno_','db.amount','db.outamount','db.recstatus','db.entrydate','db.entrytime','db.entryuser','db.reference','db.recptno','db.paymode','db.tillcode','db.tillno','db.debtortype','db.debtorcode','db.payercode','db.billdebtor','db.remark','db.mrn','db.episno','db.authno','db.expdate','db.adddate','db.adduser','db.upddate','db.upduser','db.deldate','db.deluser','db.epistype','db.cbflag','db.conversion','db.payername','db.hdrtype','db.currency','db.rate','db.unit','db.invno','db.paytype','db.bankcharges','db.RCCASHbalance','db.RCOSbalance','db.RCFinalbalance','db.PymtDescription','db.orderno','db.ponum','db.podate','db.termdays','db.termmode','db.deptcode','db.posteddate','db.approvedby','db.approveddate','db.approved_remark','db.unallocated','db.datesend','db.quoteno','db.preparedby','db.prepareddate','db.cancelby','db.canceldate','db.cancelled_remark','db.pointofsales','db.doctorcode','db.LHDNStatus','db.LHDNSubID','db.LHDNCodeNo','db.LHDNDocID','db.LHDNSubBy','db.category')
                        ->join('debtor.paymode as p', function($join){
                            $join = $join
                                        ->where('p.compcode',session('compcode'))
                                        ->on('p.paymode','db.paymode')
                                        ->where('p.source','AR')
                                        ->where('p.paytype','BANK');
                        })
                        ->where('db.compcode',session('compcode'))
                        ->where('db.source','PB')
                        ->where('db.trantype','RC')
                        ->where('db.recstatus','POSTED')
                        ->whereDate('db.posteddate','>=',$day_start)
                        ->whereDate('db.posteddate','<=',$day_end)
                        ->whereNotIn('db.auditno',$cbtran)
                        ->get();

        return view('test.check_cbtran_xde_excel',compact('dbacthdr'));
    }
    
    public function registerEvents(): array
    {
        return [
        ];
    }
}
