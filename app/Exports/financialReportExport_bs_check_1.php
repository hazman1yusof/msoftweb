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

class financialReportExport_bs_check_1 implements FromView, WithEvents, WithColumnWidths,ShouldAutoSize, WithColumnFormatting
{
    
    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function __construct($month,$year)
    {
        
        $this->month = $month;
        $this->year = $year;

        $this->comp = DB::table('sysdb.company')
            ->where('compcode','=',session('compcode'))
            ->first();
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }
    
    public function columnWidths(): array
    {
        $width_ = [
            'A' => 20,
            'B' => 20,
            'C' => 20,
            'D' => 20,
        ];

        return $width_;

    }
    
    public function view(): View{
        $month = intval($this->month);
        $year = $this->year;

        $glmasref = DB::table('finance.glmasref as gmr')
                        ->select('gmd.compcode','gmd.costcode','gmd.glaccount','gmd.year','gmd.openbalance','gmd.actamount1','gmd.actamount2','gmd.actamount3','gmd.actamount4','gmd.actamount5','gmd.actamount6','gmd.actamount7','gmd.actamount8','gmd.actamount9','gmd.actamount10','gmd.actamount11','gmd.actamount12','gmd.bdgamount1','gmd.bdgamount2','gmd.bdgamount3','gmd.bdgamount4','gmd.bdgamount5','gmd.bdgamount6','gmd.bdgamount7','gmd.bdgamount8','gmd.bdgamount9','gmd.bdgamount10','gmd.bdgamount11','gmd.bdgamount12','gmd.foramount1','gmd.foramount2','gmd.foramount3','gmd.foramount4','gmd.foramount5','gmd.foramount6','gmd.foramount7','gmd.foramount8','gmd.foramount9','gmd.foramount10','gmd.foramount11','gmd.foramount12','gmd.adduser','gmd.adddate','gmd.upduser','gmd.upddate','gmd.deluser','gmd.deldate','gmd.recstatus','gmd.idno')
                        ->leftJoin('finance.glmasdtl as gmd', function($join) use ($year){
                            $join = $join->on('gmd.glaccount','gmr.glaccno')
                                         ->where('gmd.year',$year)
                                         ->where('gmd.compcode','=',session('compcode'));
                        })
                        ->where('gmr.compcode',session('compcode'))
                        ->whereIn('gmr.acttype',['A','L'])
                        ->get();

        foreach ($glmasref as $obj) {
            $arrvalue = (array)$obj;
            $pbalance=0;

            for($x=1;$x<=$month;$x++){
                $pbalance = $pbalance + $arrvalue['actamount'.$x];
            }
            $obj->pbalance = $pbalance + $arrvalue['openbalance'];
        }

        $glrptfmt = DB::table('finance.glrptfmt as gr')
                    ->select('gr.rptname','gr.rowdef','gr.code','gr.description','gr.revsign','gc.lineno_','gc.acctfr','gc.acctto')
                    ->leftJoin('finance.glcondtl as gc', function($join){
                        $join = $join->on('gc.code', '=', 'gr.code')
                                ->where('gc.compcode','=',session('compcode'));
                    })
                    ->where('gr.compcode',session('compcode'))
                    ->where('gr.rptname','BSHEET')
                    ->where('gr.rowdef','D')
                    ->orderBy('gr.lineno_')
                    ->get();

        $excel_data = [];
        foreach ($glrptfmt as $obj) {
            $glmasdtl2 = DB::table('finance.glmasdtl as gldt')
                            ->select('gldt.compcode','gldt.costcode','gldt.glaccount','gldt.year','gldt.openbalance','gldt.actamount1','gldt.actamount2','gldt.actamount3','gldt.actamount4','gldt.actamount5','gldt.actamount6','gldt.actamount7','gldt.actamount8','gldt.actamount9','gldt.actamount10','gldt.actamount11','gldt.actamount12')
                            ->where('gldt.year',$year)
                            ->where('gldt.compcode',session('compcode'))
                            ->whereIn('gldt.glaccount',range($obj->acctfr, $obj->acctto))
                            ->get();

            foreach ($glmasdtl2 as $objgl) {
                $objgl->code = $obj->code;
                $arrgl = (array)$objgl;
                $pytd = $arrgl['openbalance'];

                for ($i=1; $i <= $month; $i++) { 
                    $pytd = $pytd + $arrgl['actamount'.$i];
                }

                $objgl->pytd = $pytd;

                array_push($excel_data,$objgl);
            }
        }
        $excel_data = collect($excel_data);
        // $excel_data = $excel_data->unique('glaccount');

        return view('finance.GL.financialReport.financialReportExport_bs_check_1',compact('month','year','glmasref','excel_data'));
    }
    
    public function registerEvents(): array{
        return [
            AfterSheet::class => function(AfterSheet $event) {
                
                $event->sheet->getPageSetup()->setPaperSize(9);//A4
                
                $event->sheet->getHeaderFooter()->setOddHeader('&C'.$this->comp->name."\nFINANCIAL REPORT Balance Sheet"."\n"
                .sprintf('FROM %s YEAR %s ',$this->month, $this->year)
                .'&L'
                .'PRINTED BY : '.session('username')
                ."\nPAGE : &P/&N"
                .'&R'.'PRINTED DATE : '.Carbon::now("Asia/Kuala_Lumpur")->format('d-m-Y')
                ."\n".'PRINTED TIME : '.Carbon::now("Asia/Kuala_Lumpur")->format('H:i'));


                $event->sheet->getPageMargins()->setTop(1);
            },
        ];
    }

    public static function getQueries($builder){
        $addSlashes = str_replace('?', "'?'", $builder->toSql());
        return vsprintf(str_replace('?', '%s', $addSlashes), $builder->getBindings());
    }
}
