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

class acctenq_dateExport_2 implements FromView, WithEvents, WithColumnWidths, WithColumnFormatting
{
    
    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function __construct($job_id,$glaccount,$fromdate,$todate)
    {   
        $this->job_id = $job_id;
        $this->glaccount = $glaccount;
        $this->fromdate = $fromdate;
        $this->todate = $todate;
        $this->comp = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();
    }

    public function columnFormats(): array
    {
        return [
            'I' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'J' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'K' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }
    
    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 10,
            'C' => 15,
            'D' => 15,
            'E' => 15,
            'F' => 15,
            'G' => 18,
            'H' => 60,
            'I' => 18,
            'J' => 18,
            'K' => 18,
            'L' => 18,
        ];
    }
    
    public function view(): View
    {
        $glaccount = $this->glaccount;
        $fromdate = $this->fromdate;
        $todate = $this->todate;
        $compname = $this->comp->name;

        $yearperiod = $this->getyearperiod($fromdate);
        $month = $yearperiod->period - 1;
        $year = $yearperiod->year;

        $glmasdtl = DB::table('finance.glmasdtl')
                        ->where('compcode',session('compcode'))
                        ->where('year',$year)
                        ->where('glaccount',$glaccount)
                        ->get();

        if($month < 1){
            $firstDay = Carbon::create($year, 1, 1)->startOfDay()->format('Y-m-d');
            $month_ = [];
        }else{
            $firstDay = Carbon::create($year, $month, 1)->startOfDay()->format('Y-m-d');
            $month_ = range(1,$month);
        }

        // $openbal = 0;
        $total_openbal = 0;
        // dump($month);
        // dd($glmasdtl);
        foreach ($glmasdtl as $obj) {
            $obj_gl = (array)$obj;
            // $openbal = $obj_gl['openbalance'];
            $total_openbal = $total_openbal + $obj_gl['openbalance'];
            foreach ($month_ as $key_m => $value_m) {
                $key_m_r = $key_m + 1;
                // $month_[$key_m] = $value_m + $obj_gl['actamount'.$key_m_r];
                $total_openbal = $total_openbal + $obj_gl['actamount'.$key_m_r];
            }
        }
        // dump($month_);
        // dump($openbal);
        // dd($total_openbal);

        $table = DB::table('finance.acctenq_date')
                            ->where('job_id',$this->job_id)
                            ->orderBy('postdate','ASC')
                            ->get();

                            // dd($table);

        return view('finance.GL.acctenq_date.acctenq_dateExcel', compact('table','glaccount','compname','fromdate','todate','total_openbal','firstDay'));
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event){
                $event->sheet->getPageSetup()->setPaperSize(9); // A4
                
                $event->sheet->getHeaderFooter()->setOddHeader('&C'.$this->comp->name."\Bank Recon"."\n"
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

    public function getyearperiod($date){
        $period = DB::table('sysdb.period')
            ->where('compcode','=',session('compcode'))
            ->get();

        $seldate = new DateTime($date);

        foreach ($period as $value) {
            $arrvalue = (array)$value;

            $year= $value->year;
            $period=0;

            for($x=1;$x<=12;$x++){
                $period = $x;

                $datefr = new DateTime($arrvalue['datefr'.$x]);
                $dateto = new DateTime($arrvalue['dateto'.$x]);
                $status = $arrvalue['periodstatus'.$x];
                if (($datefr <= $seldate) &&  ($dateto >= $seldate)){
                    $responce = new stdClass();
                    $responce->year = $year;
                    $responce->period = $period;
                    $responce->status = $status;
                    $responce->datefr = $arrvalue['datefr'.$x];
                    $responce->dateto = $arrvalue['dateto'.$x];
                    return $responce;
                }
            }
        }
    }
    
}
