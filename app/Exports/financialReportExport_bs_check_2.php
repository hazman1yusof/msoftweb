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
use stdClass;

class financialReportExport_bs_check_2 implements FromView, WithEvents, WithColumnWidths,ShouldAutoSize, WithColumnFormatting, WithTitle
{
    
    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function __construct($monthfrom,$monthto,$yearfrom,$yearto,$reporttype)
    {
        
        $this->monthfrom = $monthfrom;
        $this->monthfrom_name = Carbon::create()->month($monthfrom)->format('F');
        $this->yearfrom = $yearfrom;
        $this->rptname = $reporttype;

        $this->date = Carbon::create($yearfrom, $monthfrom, 1)->format('Y-m-d');

        $this->comp = DB::table('sysdb.company')
            ->where('compcode','=',session('compcode'))
            ->first();

        $array_month = [];
        $array_month_name = [];

        for ($i=1; $i <= $monthfrom; $i++) { 
            array_push($array_month,$i);
            array_push($array_month_name,Carbon::create()->month($i)->format('F'));
        }

        $this->array_month = $array_month;
        $this->array_month_name = $array_month_name;
    }



    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }

    public function title(): string
    {
        return 'Note';
    }
    
    public function columnWidths(): array
    {
        $alphabet = range('A', 'Z');
        $array_month = $this->array_month;
        $width_ = [
            'A' => 15,
            'B' => 42,
            'C' => 15,
            'D' => 15,
        ];

        return $width_;

    }
    
    public function view(): View{
        $monthfrom = intval($this->monthfrom);
        $yearfrom = $this->yearfrom;
        $rptname = $this->rptname;
        $yearperiod = $this->getyearperiod($this->date);

        $array_month = $this->array_month;
        $array_month_name = $this->array_month_name;

        $glrptfmt = DB::table('finance.glrptfmt as gr')
                    ->select('gr.rptname','gr.rowdef','gr.code','gr.description','gr.revsign','gc.lineno_','gc.acctfr','gc.acctto')
                    ->leftJoin('finance.glcondtl as gc', function($join){
                        $join = $join->on('gc.code', '=', 'gr.code')
                                ->where('gc.compcode','=',session('compcode'));
                    })
                    ->where('gr.compcode',session('compcode'))
                    ->where('gr.rptname',$rptname)
                    ->where('gr.rowdef','D')
                    // ->offset(7)
                    // ->limit(3)
                    ->orderBy('gr.lineno_')
                    ->get();

        // dd($glrptfmt);

        $excel_data = [];
        foreach ($glrptfmt as $obj) {
            $glmasdtl = DB::table('finance.glmasdtl as gldt')
                            ->select('gldt.glaccount','gldt.year','gldt.openbalance','gldt.actamount1','gldt.actamount2','gldt.actamount3','gldt.actamount4','gldt.actamount5','gldt.actamount6','gldt.actamount7','gldt.actamount8','gldt.actamount9','gldt.actamount10','gldt.actamount11','gldt.actamount12','glms.description')
                            ->leftJoin('finance.glmasref as glms', function($join){
                                $join = $join->on('glms.glaccno', '=', 'gldt.glaccount')
                                        ->where('glms.compcode','=',session('compcode'));
                            })
                            ->where('gldt.year',$yearperiod->year)
                            ->where('gldt.compcode',session('compcode'))
                            ->whereIn('gldt.glaccount',range($obj->acctfr, $obj->acctto))
                            ->get();

            foreach ($glmasdtl as $objgl) {
                $objgl->code = $obj->code;
                $arrgl = (array)$objgl;
                $pytd = $arrgl['openbalance'];

                for ($i=1; $i <= $yearperiod->period; $i++) { 
                    $pytd = $pytd + $arrgl['actamount'.$i];
                }

                $objgl->pytd = $pytd;

                array_push($excel_data,$objgl);
            }
        }

        $glrptfmt = $glrptfmt->unique('code');
        // dump($excel_map);
        // dd($excel_data);

        $alphabet = range('A', 'Z');

        $title1 = strtoupper($this->comp->name);
        $title2 = 'FINANCIAL REPORT Balance Sheet';
        $title3 = 'MONTH '.$this->monthfrom_name.' YEAR '.$this->yearfrom;
        $title4 = 'PRINTED BY: '.session('username').' on '.Carbon::now("Asia/Kuala_Lumpur")->format('d-m-Y H:i');

        return view('finance.GL.financialReport.financialReport_bsnote_excel',compact('glrptfmt','excel_data','array_month','title1','title2','title3','title4'));
    }
    
    public function registerEvents(): array{
        return [
            AfterSheet::class => function(AfterSheet $event) {
                
                $event->sheet->getPageSetup()->setPaperSize(9);//A4
                
                $event->sheet->getHeaderFooter()->setOddHeader('&C'.$this->comp->name."\nFINANCIAL REPORT Balance Sheet"."\n"
                .sprintf('FROM %s YEAR %s ',$this->monthfrom_name, $this->yearfrom)
                .'&L'
                .'PRINTED BY : '.session('username')
                ."\nPAGE : &P/&N"
                .'&R'.'PRINTED DATE : '.Carbon::now("Asia/Kuala_Lumpur")->format('d-m-Y')
                ."\n".'PRINTED TIME : '.Carbon::now("Asia/Kuala_Lumpur")->format('H:i'));


                $event->sheet->getPageMargins()->setTop(1);

                $array_month = $this->array_month;

                $event->sheet->getStyle('C')
                    ->getNumberFormat()
                    ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $event->sheet->getStyle('D')
                    ->getNumberFormat()
                    ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                // $event->sheet->getPageSetup()->setRowsToRepeatAtTop([1,1]);
                // $event->sheet->getStyle('A:H')->getAlignment()->setWrapText(true);
                // $event->sheet->getPageSetup()->setFitToWidth(1);
                // $event->sheet->getPageSetup()->setFitToHeight(0);
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

    public static function getQueries($builder){
        $addSlashes = str_replace('?', "'?'", $builder->toSql());
        return vsprintf(str_replace('?', '%s', $addSlashes), $builder->getBindings());
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
