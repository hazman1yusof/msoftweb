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
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Exportable;
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

class Unallocated_receiptExport implements FromView, ShouldQueue, WithEvents, WithColumnWidths, WithColumnFormatting
{

    use Exportable;
    
    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function __construct($process,$page,$filename,$date,$unit)
    {
        
        $this->process = $process;
        $this->filename = $filename;
        $this->page = $page;
        $this->date = Carbon::parse($date)->format('Y-m-d');
        $this->unit = $unit;

        $this->comp = DB::table('sysdb.company')
            ->where('compcode','=',session('compcode'))
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
            'A' => 15,
            'B' => 20,
            'C' => 15,
            'D' => 65,
            'E' => 15,
            'F' => 15,
            'G' => 15,
        ];
    }
    
    public function view(): View
    {
        $idno_job_queue = $this->start_job_queue($this->page);

        $date = $this->date;
        $unit = $this->unit;

        $dbacthdr = DB::table('debtor.dbacthdr as db')
                        ->select('db.idno','db.compcode','db.source','db.trantype','db.auditno','db.lineno_','db.amount','db.outamount','db.recstatus','db.entrydate','db.entrytime','db.entryuser','db.reference','db.recptno','db.paymode','db.tillcode','db.tillno','db.debtortype','db.debtorcode','db.payercode','db.billdebtor','db.remark','db.mrn','db.episno','db.authno','db.expdate','db.adddate','db.adduser','db.upddate','db.upduser','db.deldate','db.deluser','db.epistype','db.cbflag','db.conversion','db.payername','db.hdrtype','db.currency','db.rate','db.unit','db.invno','db.paytype','db.bankcharges','db.RCCASHbalance','db.RCOSbalance','db.RCFinalbalance','db.PymtDescription','db.orderno','db.ponum','db.podate','db.termdays','db.termmode','db.deptcode','db.posteddate','db.approvedby','db.approveddate','db.approved_remark','db.unallocated','db.datesend','db.quoteno','db.preparedby','db.prepareddate','db.cancelby','db.canceldate','db.cancelled_remark','db.pointofsales','db.doctorcode','db.LHDNStatus','db.LHDNSubID','db.LHDNCodeNo','db.LHDNDocID','db.LHDNSubBy','db.category','db.categorydept','dm.name as dm_name')
                        ->join('debtor.debtormast as dm', function($join){
                            $join = $join->on('dm.debtorcode', '=', 'db.debtorcode')
                                         ->where('dm.compcode', '=', session('compcode'));
                        })
                        ->where('db.compcode', '=', session('compcode'))
                        ->where('db.source', 'PB')
                        ->where('db.trantype', 'RC')
                        ->whereDate('db.posteddate','<=',$date)
                        ->orderBy('db.posteddate', 'ASC');

        if($unit != 'ALL'){
            $dbacthdr = $dbacthdr->where('db.unit', $unit);
        }

        $dbacthdr = $dbacthdr->get();

        foreach ($dbacthdr as $obj){
            $pamt = $obj->amount;

            $doc_sum = DB::table('debtor.dballoc')
                            ->where('compcode', '=', session('compcode'))
                            ->where('recstatus', '=', 'POSTED')
                            ->where('docsource', '=', $obj->source)
                            ->where('doctrantype', '=', $obj->trantype)
                            ->where('docauditno', '=', $obj->auditno)
                            ->whereDate('allocdate','<=',$date)
                            ->sum('amount');

            $ref_sum = DB::table('debtor.dballoc')
                            ->where('compcode', '=', session('compcode'))
                            ->where('recstatus', '=', 'POSTED')
                            ->where('refsource', '=', $obj->source)
                            ->where('reftrantype', '=', $obj->trantype)
                            ->where('refauditno', '=', $obj->auditno)
                            ->whereDate('allocdate','<=',$date)
                            ->sum('amount');

            $pamt = $pamt - $doc_sum - $ref_sum;
            $obj->pamt = $pamt;
        }

        // dd($dbacthdr);

        // $debtortype = collect($array_report)->unique('debtortycode');
        // $debtorcode = collect($array_report)->unique('debtorcode');

        $comp_name = $this->comp->name;
        $date_at = Carbon::createFromFormat('Y-m-d',$this->date)->format('d-m-Y');

        $this->stop_job_queue($idno_job_queue);

        return view('finance.AR.unallocated_receipt.unallocated_receipt_excel_summ',compact('dbacthdr','date_at','comp_name'));

    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // foreach ($this->break_loop as $value) {
                //     $event->sheet->setBreak('A'.$value, \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);
                // }
                
                $event->sheet->getPageSetup()->setPaperSize(9);//A4
                
                $event->sheet->getHeaderFooter()->setOddHeader('&C'.$this->comp->name."\nUNALLOCATED RECEIPT"."\n"
                .sprintf('DATE %s',Carbon::parse($this->date)->format('d-m-Y'))
                .'&L'
                .'PRINTED BY : '.session('username')
                ."\nPAGE : &P/&N"
                .'&R'.'PRINTED DATE : '.Carbon::now("Asia/Kuala_Lumpur")->format('d-m-Y')
                ."\n".'PRINTED TIME : '.Carbon::now("Asia/Kuala_Lumpur")->format('H:i'));
                
                $event->sheet->getPageMargins()->setTop(1);
                
                $event->sheet->getPageSetup()->setRowsToRepeatAtTop([1,1]);
                $event->sheet->getStyle('A:H')->getAlignment()->setWrapText(true);
                $event->sheet->getPageSetup()->setFitToWidth(1);
                $event->sheet->getPageSetup()->setFitToHeight(0);
            },
        ];
    }

    public function calc_bal($obj){
        $balance = 0;
        foreach ($obj->get() as $key => $value){
            
            switch ($value->trantype) {
                 case 'IN': //dr
                    $balance = $balance + floatval($value->amount);
                    break;
                case 'DN': //dr
                    $balance = $balance + floatval($value->amount);
                    break;
                case 'CN': //cr
                    $balance = $balance - floatval($value->amount);
                    break;
                case 'PV': //cr
                    $balance = $balance - floatval($value->amount);
                    break;
                case 'PD': //cr
                    $balance = $balance - floatval($value->amount);
                    break;
                default:
                    // code...
                    break;
            }
        }

        return $balance;
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

    public function start_job_queue($page){
        $idno_job_queue = DB::table('sysdb.job_queue')
                            ->insertGetId([
                                'compcode' => session('compcode'),
                                'page' => $page,
                                'filename' => $this->filename,
                                'process' => $this->process,
                                'adduser' => session('username'),
                                'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                                'status' => 'PENDING',
                            ]);

        return $idno_job_queue;
    }

    public function stop_job_queue($idno_job_queue){
        DB::table('sysdb.job_queue')
                ->where('idno',$idno_job_queue)
                ->update([
                    'finishdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'status' => 'DONE'
                ]);
    }
}
