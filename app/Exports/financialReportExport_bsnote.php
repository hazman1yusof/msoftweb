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

class financialReportExport_bsnote implements FromView, WithEvents, WithColumnWidths,ShouldAutoSize, WithColumnFormatting, WithTitle
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

        $this->skipytd = DB::table('sysdb.sysparam')
                        ->where('compcode',session('compcode'))
                        ->where('source','GL')
                        ->where('trantype','CLOSE_STOCK')
                        ->first();
    }



    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }

    public function title(): string
    {
        return 'Note to the Accounts';
    }
    
    public function columnWidths(): array
    {
        $alphabet = range('A', 'Z');
        $array_month = $this->array_month;
        $width_ = [
            'A' => 4,
            'B' => 15,
            'C' => 42,
            'D' => 20,
            'E' => 20,
            'F' => 20,
        ];

        return $width_;

    }
    
    public function view(): View{
        $monthfrom = intval($this->monthfrom);
        $yearfrom = $this->yearfrom;
        $rptname = $this->rptname;
        $yearperiod = $this->getyearperiod($this->date);

        $currmonth = intval($yearperiod->period);
        $lastmonth = $currmonth - 1;

        $array_month = $this->array_month;
        $array_month_name = $this->array_month_name;
        $skipytd = $this->skipytd->pvalue1;

        $glrptfmt = DB::table('finance.glrptfmt as gr')
                    ->select('gr.rptname','gr.rowdef','gr.code','gr.description','gr.revsign','gc.lineno_','gc.acctfr','gc.acctto','gr.note')
                    ->leftJoin('finance.glcondtl as gc', function($join){
                        $join = $join->on('gc.code', '=', 'gr.code')
                                ->where('gc.compcode','=',session('compcode'));
                    })
                    ->where('gr.compcode',session('compcode'))
                    ->where('gr.rptname',$rptname)
                    ->where('gr.rowdef','D')
                    ->where('gr.note','>',0)
                    // ->offset(7)
                    // ->limit(3)
                    ->orderBy('gr.note')
                    ->get();

        // dd($glrptfmt);

        $excel_data = [];
        foreach ($glrptfmt as $obj) {
            $glmasdtl = DB::table('finance.glmasdtl as gldt')
                            ->select('gldt.glaccount','gldt.costcode','gldt.year','gldt.openbalance','gldt.actamount1','gldt.actamount2','gldt.actamount3','gldt.actamount4','gldt.actamount5','gldt.actamount6','gldt.actamount7','gldt.actamount8','gldt.actamount9','gldt.actamount10','gldt.actamount11','gldt.actamount12','glms.description')
                            ->leftJoin('finance.glmasref as glms', function($join){
                                $join = $join->on('glms.glaccno', '=', 'gldt.glaccount')
                                        ->where('glms.compcode','=',session('compcode'));
                            })
                            ->where('gldt.year',$yearperiod->year)
                            ->where('gldt.compcode',session('compcode'))
                            ->whereIn('gldt.glaccount',range($obj->acctfr, $obj->acctto))
                            ->get();

            foreach ($glmasdtl as $objgl) {
                $objgl->revsign = $obj->revsign;
                $objgl->code = $obj->code;
                $arrgl = (array)$objgl;
                $pytd = $arrgl['openbalance'];

                if($lastmonth == 0){
                    $plastmonth = $arrgl['openbalance'];
                }else{
                    $plastmonth = $arrgl['openbalance'];
                    for ($i=1; $i <= $lastmonth; $i++) { 
                        $plastmonth = $plastmonth + $arrgl['actamount'.$i];
                    }
                }

                for ($i=1; $i <= $currmonth; $i++) { 
                    $pytd = $pytd + $arrgl['actamount'.$i];
                }

                $objgl->note = $obj->note;
                $objgl->pytd = $pytd;
                $objgl->plastmonth = $plastmonth;
                $objgl->pcurrmonth = $arrgl['actamount'.$currmonth];

                //untuk close stock sahaja
                if($objgl->code == $skipytd){
                    $objgl->plastmonth = 0;
                    $objgl->pcurrmonth = $arrgl['actamount'.$currmonth];
                    $objgl->pytd = $objgl->pcurrmonth;
                }

                if(strtoupper($obj->revsign) == 'Y'){
                    $objgl->pytd = $objgl->pytd * -1;
                    $objgl->plastmonth = $objgl->plastmonth * -1;
                    $objgl->pcurrmonth = $objgl->pcurrmonth * -1;
                }

                array_push($excel_data,$objgl);
            }
        }

        $excel_data = collect($excel_data);

        $excel_data = $excel_data
                        ->groupBy('glaccount','costcode')
                        ->map(function ($items) {
                            return (object) [
                                'glaccount' => $items->first()->glaccount,
                                'description'   => $items->first()->description,
                                'note' => $items->first()->note,
                                'plastmonth' => $items->sum('plastmonth'),
                                'pcurrmonth' => $items->sum('pcurrmonth'),
                                'pytd' => $items->sum('pytd'),
                            ];
                        })
                        ->values();

        $glrptfmt = $glrptfmt->unique('code');
        // dump($excel_map);
        // dd($excel_data);

        $alphabet = range('A', 'Z');

        $title1 = strtoupper($this->comp->name);
        $title2 = 'NOTE TO THE ACCOUNTS';
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

                $event->sheet->getStyle('D')
                    ->getNumberFormat()
                    ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $event->sheet->getStyle('E')
                    ->getNumberFormat()
                    ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $event->sheet->getStyle('F')
                    ->getNumberFormat()
                    ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                // $event->sheet->getPageSetup()->setRowsToRepeatAtTop([1,1]);
                $event->sheet->getStyle('D:F')->getAlignment()->setWrapText(true);
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
