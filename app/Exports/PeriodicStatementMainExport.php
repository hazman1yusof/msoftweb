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
use App\Exports\financialReportExport_bs;
use App\Exports\financialReportExport_bsnote;
use DateTime;
use Carbon\Carbon;
use stdClass;

class financialReportExport_bs_main implements WithMultipleSheets
{
    
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;

    public function __construct($idno)
    {
        $job_queue = DB::table('sysdb.job_queue')
                        ->where('idno',$idno)
                        ->first();
        
        $this->job_id = $idno;
        $this->suppcode_from = $job_queue->debtorcode_from;
        $this->suppcode_to = $job_queue->debtorcode_to;
        $this->fromdate = $job_queue->date;
        $this->todate = $job_queue->date_to;
    }

    public function sheets(): array
    {
        $monthFrom = Carbon::parse($this->fromdate)->format('F Y');
        $monthTo = Carbon::parse($this->todate)->format('F Y');

        $array_report = DB::table('jobs.periodicStatement')
                            ->where('job_id',$this->job_id);

        if($array_report->exists()){
            $array_report = $array_report->get();
            $array_first = $array_report->first();

            $positive = $array_first->amount_positive;
            $negative = $array_first->amount_negative;
            $openbalance = $positive - $negative;

        }else{
            $array_report = $array_report->get();
            $openbalance = 0;

        }

        $suppcode = collect($array_report)->unique('suppcode');

        $sheets = [];

        $sheets[0] = new financialReportExport_bs($this->monthfrom,$this->monthto,$this->yearfrom,$this->yearto,$this->reporttype);
        $sheets[1] = new financialReportExport_bsnote($this->monthfrom,$this->monthto,$this->yearfrom,$this->yearto,$this->reporttype);

        return $sheets;
    }
    
}
