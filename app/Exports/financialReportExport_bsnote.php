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

class financialReportExport_bsnote implements FromView, WithEvents, WithColumnWidths,ShouldAutoSize, WithTitle
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

    public function title(): string
    {
        return 'Note';
    }
    
    public function columnWidths(): array
    {
        $alphabet = range('A', 'Z');
        $array_month = $this->array_month;
        $width_ = [
            'A' => 45,
            'B' => 12,
            'C' => 18,
            'D' => 18,
        ];

        return $width_;

    }
    
    public function view(): View{
        $monthfrom = intval($this->monthfrom);
        $yearfrom = $this->yearfrom;
        $rptname = $this->rptname;

        $array_month = $this->array_month;
        $array_month_name = $this->array_month_name;

        $CLOSESTK = DB::table('sysdb.sysparam')
                    ->where('compcode',session('compcode'))
                    ->where('trantype','CLOSESTK')
                    ->where('source','GL')
                    ->first();

        $glrptfmt = DB::table('finance.glrptfmt')
                    ->where('compcode',session('compcode'))
                    ->where('rptname',$rptname)
                    // ->offset(7)
                    // ->limit(3)
                    ->orderBy('lineno_')
                    ->get();

        $alphabet = range('A', 'Z');
        $excel_data = [];
        foreach ($glrptfmt as $key_rpt => $obj_rpt) {
            $arr_rpt = (array)$obj_rpt;
            if($obj_rpt->rowdef == 'H'){
                array_push($excel_data,$arr_rpt);
            }else if($obj_rpt->rowdef == 'S'){
                array_push($excel_data,$arr_rpt);
            }else if($obj_rpt->rowdef == 'T' || $obj_rpt->rowdef == 'T0'){
                $formula = explode(',', $arr_rpt['formula']);

                $tot_arr = [];
                $tot_arr['curr_month'] = 0.00;
                $tot_arr['last_month'] = 0.00;

                foreach ($excel_data as $key => $value) {
                    if($value['rowdef'] == 'D' && intval($value['lineno_'])>=$formula[0] && intval($value['lineno_'])<=$formula[1]){
                        $tot_arr['curr_month'] = $tot_arr['curr_month'] + $value['curr_month'];
                        $tot_arr['last_month'] = $tot_arr['last_month'] + $value['last_month'];
                    }else{
                        continue;
                    }
                }
                $arr_rpt['tot_arr'] = $tot_arr;
                
                array_push($excel_data,$arr_rpt);
            }else if($obj_rpt->rowdef == 'D'){
                $glcondtl = DB::table('finance.glcondtl')
                            ->where('compcode',session('compcode'))
                            ->where('code',$obj_rpt->code)
                            ->get();

                $arr_rpt['openbalance'] = 0.00;
                foreach ($array_month as $value) {
                    $arr_rpt['tot_actamount'.$value] = 0.00;
                }

                foreach ($glcondtl as $key_con => $obj_con) {
                    $arr_con = (array)$obj_con;
                    $glmasdtl = DB::table('finance.glmasdtl as gldt')
                            ->select('gldt.glaccount','gldt.openbalance','gldt.actamount1','gldt.actamount2','gldt.actamount3','gldt.actamount4','gldt.actamount5','gldt.actamount6','gldt.actamount7','gldt.actamount8','gldt.actamount9','gldt.actamount10','gldt.actamount11','gldt.actamount12')
                            ->where('gldt.glaccount','>=',$obj_con->acctfr)
                            ->where('gldt.glaccount','<=',$obj_con->acctto);

                    if($obj_rpt->costcodefr!=null){
                        $glmasdtl = $glmasdtl->where('gldt.costcode','>=',$obj_rpt->costcodefr);
                    }

                    if($obj_rpt->costcodeto!=null){
                        $glmasdtl = $glmasdtl->where('gldt.costcode','<=',$obj_rpt->costcodeto);
                    }

                    $glmasdtl = $glmasdtl
                            ->where('gldt.year','=', $yearfrom)
                            ->where('gldt.compcode',session('compcode'))
                            ->get();

                    $arr_con['openbalance'] = 0.00;
                    foreach ($array_month as $value) {
                        $arr_con['tot_actamount'.$value] = 0.00;
                    }

                    foreach($glmasdtl as $key_dtl => $obj_dtl){
                        $arr_dtl = (array) $obj_dtl;
                        foreach ($array_month as $value) {
                            $arr_con['tot_actamount'.$value] = $arr_con['tot_actamount'.$value] + $arr_dtl['actamount'.$value];
                        }
                        $arr_con['openbalance'] = $arr_con['openbalance'] + $arr_dtl['openbalance'];
                    }

                    // dd($arr_con);//1glcondtl
                    foreach ($array_month as $value) {
                        $arr_rpt['tot_actamount'.$value] = $arr_rpt['tot_actamount'.$value] + $arr_con['tot_actamount'.$value];
                    }
                    $arr_rpt['openbalance'] = $arr_rpt['openbalance'] + $arr_con['openbalance'];
                }
                // dd($arr_rpt);//1arr_rpt


                if($obj_rpt->code == $CLOSESTK->pvalue1){

                    $arr_rpt['curr_month'] = $arr_rpt['tot_actamount'.$monthfrom];

                    if($monthfrom-1 == 0){
                        $arr_rpt['last_month'] = 0;
                    }else{
                        $arr_rpt['last_month'] = $arr_rpt['tot_actamount'.($monthfrom-1)];
                    }
                }else{

                    $arr_rpt['curr_month'] = $arr_rpt['openbalance'];
                    $arr_rpt['last_month'] = $arr_rpt['openbalance'];

                    for ($i=1; $i <= $monthfrom; $i++) { 
                        $arr_rpt['curr_month'] = $arr_rpt['curr_month'] + $arr_rpt['tot_actamount'.$i];
                    }

                    for ($i=1; $i < $monthfrom; $i++) { 
                        $arr_rpt['last_month'] = $arr_rpt['last_month'] + $arr_rpt['tot_actamount'.$i];
                    }
                }

                array_push($excel_data,$arr_rpt);
            }
        }
        // dump($excel_map);
        // dd($excel_data);

        $alphabet = range('A', 'Z');

        $title1 = strtoupper($this->comp->name);
        $title2 = 'FINANCIAL REPORT Balance Sheet';
        $title3 = 'MONTH '.$this->monthfrom_name.' YEAR '.$this->yearfrom;
        $title4 = 'PRINTED BY: '.session('username').' on '.Carbon::now("Asia/Kuala_Lumpur")->format('d-m-Y H:i');

        return view('finance.GL.financialReport.financialReport_bs_excel',compact('excel_data','array_month','title1','title2','title3','title4'));
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
}
