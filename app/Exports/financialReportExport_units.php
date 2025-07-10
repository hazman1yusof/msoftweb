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
use App\Exports\financialReportExport_units_sheets;
use DateTime;
use Carbon\Carbon;
use stdClass;

class financialReportExport_units implements WithMultipleSheets
{
    
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;

    public function __construct($monthfrom,$monthto,$yearfrom,$yearto,$reporttype)
    {
        // $this->year = '2025';
        $this->monthfrom = $monthfrom;
        $this->monthto = $monthto;
        $this->monthfrom_name = Carbon::create()->month($monthfrom)->format('F');
        $this->monthto_name = Carbon::create()->month($monthto)->format('F');
        $this->yearfrom = $yearfrom;
        $this->yearto = $yearto;
        $this->rptname = $reporttype;

        // $this->comp = DB::table('sysdb.company')
        //     ->where('compcode','=',session('compcode'))
        //     ->first();

        // $array_open = [];
        // $array_month = [];
        // $array_month_tot = [];
        // $array_month_name = [];

        // for ($i=0; $i < $monthfrom; $i++) { 
        //     array_push($array_open,$i);
        // }

        // for ($i=$monthfrom; $i <= $monthto; $i++) { 
        //     array_push($array_month,$i);
        //     array_push($array_month_tot,'0.00');
        //     array_push($array_month_name,Carbon::create()->month($i)->format('F'));
        // }

        // $this->array_open = $array_open;
        // $this->array_month = $array_month;
        // $this->array_month_tot = $array_month_tot;
        // $this->array_month_name = $array_month_name;
    }

    public function sheets(): array
    {

        $department = DB::table('sysdb.department')
                            ->where('compcode',session('compcode'))
                            ->where('deptcode','POLISA')
                            ->get();

        // dump($department->where('sector',"MRS"));

        // dd($department);

        $sector = $department->unique('sector');

        // dd($sector);

        $sheets = [];
        $x = 0;
        foreach ($sector as $key => $value){
            $sector = $department->where('sector',$value->sector);
            $sheets[$x] = new financialReportExport_units_sheets($this->monthfrom,$this->monthto,$this->yearfrom,$this->yearto,$this->rptname,$sector,$value->description);
            $x++;
        }

        return $sheets;
    }
    
    // public function view(): View{
    //     $monthfrom = intval($this->monthfrom);
    //     $monthto = intval($this->monthto);
    //     $yearfrom = $this->yearfrom;
    //     $yearto = $this->yearto;
    //     $rptname = $this->rptname;

    //     $array_open = $this->array_open;
    //     $array_month = $this->array_month;
    //     $array_month_tot = $this->array_month_tot;
    //     $array_month_name = $this->array_month_name;

    //     $glrptfmt = DB::table('finance.glrptfmt')
    //                 ->where('compcode',session('compcode'))
    //                 ->where('rptname',$rptname)
    //                 // ->limit(5)
    //                 ->orderBy('lineno_')
    //                 ->get();

    //     $alphabet = range('A', 'Z');
    //     $excel_data = [];
    //     foreach ($glrptfmt as $key_rpt => $obj_rpt) {
    //         $arr_rpt = (array)$obj_rpt;
    //         if($obj_rpt->rowdef == 'H'){
    //             array_push($excel_data,$arr_rpt);
    //         }else if($obj_rpt->rowdef == 'S'){
    //             array_push($excel_data,$arr_rpt);
    //         }else if($obj_rpt->rowdef == 'T' || $obj_rpt->rowdef == 'T0'){
    //             $formula = explode(',', $arr_rpt['formula']);

    //             $tot_arr = [];
    //             $tot_arr['ytd'] = 0;
    //             foreach ($array_month as $value) {
    //                 $tot_arr['sum_desc'.$value] = 0;
    //             }
    //             foreach ($excel_data as $key => $value) {
    //                 if($value['rowdef'] == 'D' && intval($value['lineno_'])>=$formula[0] && intval($value['lineno_'])<=$formula[1]){
    //                     foreach ($array_month as $month) {
    //                         $tot_arr['sum_desc'.$month] = $tot_arr['sum_desc'.$month] + $value['tot_actamount'.$month];
    //                     }
    //                     $tot_arr['ytd'] = $tot_arr['ytd'] + $value['tot_ytd'];
    //                 }else{
    //                     continue;
    //                 }
    //             }
    //             $arr_rpt['tot_arr'] = $tot_arr;
                
    //             array_push($excel_data,$arr_rpt);
    //         }else if($obj_rpt->rowdef == 'D'){
    //             $glcondtl = DB::table('finance.glcondtl')
    //                         ->where('compcode',session('compcode'))
    //                         ->where('code',$obj_rpt->code)
    //                         ->get();

    //             foreach ($array_month as $value) {
    //                 $arr_rpt['tot_actamount'.$value] = 0.00;
    //                 $arr_rpt['tot_ytd'] = 0.00;
    //             }

    //             foreach ($glcondtl as $key_con => $obj_con) {
    //                 $arr_con = (array)$obj_con;
    //                 $glmasdtl = DB::table('finance.glmasdtl as gldt')
    //                         ->select('gldt.glaccount','gldt.openbalance','gldt.actamount1','gldt.actamount2','gldt.actamount3','gldt.actamount4','gldt.actamount5','gldt.actamount6','gldt.actamount7','gldt.actamount8','gldt.actamount9','gldt.actamount10','gldt.actamount11','gldt.actamount12')
    //                         ->where('gldt.glaccount','>=',$obj_con->acctfr)
    //                         ->where('gldt.glaccount','<=',$obj_con->acctto);

    //                 if($obj_rpt->costcodefr!=null){
    //                     $glmasdtl = $glmasdtl->where('gldt.costcode','>=',$obj_rpt->costcodefr);
    //                 }

    //                 if($obj_rpt->costcodeto!=null){
    //                     $glmasdtl = $glmasdtl->where('gldt.costcode','<=',$obj_rpt->costcodeto);
    //                 }

    //                 $glmasdtl = $glmasdtl
    //                         ->where('gldt.year','>=', $yearfrom)
    //                         ->where('gldt.year','<=', $yearto)
    //                         ->where('gldt.compcode',session('compcode'))
    //                         ->get();

    //                 foreach ($array_month as $value) {
    //                     $arr_con['tot_actamount'.$value] = 0.00;
    //                 }

    //                 foreach($glmasdtl as $key_dtl => $obj_dtl){
    //                     $arr_dtl = (array) $obj_dtl;
    //                     foreach ($array_month as $value) {
    //                         $arr_con['tot_actamount'.$value] = $arr_con['tot_actamount'.$value] + $arr_dtl['actamount'.$value];
    //                     }
    //                 }

    //                 // dd($arr_con);1glcondtl
    //                 foreach ($array_month as $value) {
    //                     $arr_rpt['tot_actamount'.$value] = $arr_rpt['tot_actamount'.$value] + $arr_con['tot_actamount'.$value];
    //                 }
    //             }
    //             // dd($arr_rpt);//1glrpt
    //             foreach ($array_month as $value) {
    //                 $arr_rpt['tot_ytd'] = $arr_rpt['tot_ytd'] + $arr_rpt['tot_actamount'.$value];
    //             }
    //             array_push($excel_data,$arr_rpt);
    //         }
    //     }
    //     // dump($excel_map);
    //     // dd($excel_data);

    //     $alphabet = range('A', 'Z');

    //     $title1 = strtoupper($this->comp->name);
    //     $title2 = 'FINANCIAL REPORT';
    //     $title3 = 'FROM '.$this->monthfrom_name.' '.$this->yearfrom.' TO '.$this->monthto_name.' '.$this->yearto;
    //     $title4 = 'PRINTED BY: '.session('username').' on '.Carbon::now("Asia/Kuala_Lumpur")->format('d-m-Y H:i');

    //     return view('finance.GL.financialReport.financialReport_excel',compact('excel_data','array_month','array_month_name','alphabet','title1','title2','title3','title4'));
    // }
    
    // public function registerEvents(): array{
    //     return [
    //         AfterSheet::class => function(AfterSheet $event) {
                
    //             $event->sheet->getPageSetup()->setPaperSize(9);//A4
                
    //             $event->sheet->getHeaderFooter()->setOddHeader('&C'.$this->comp->name."\nFINANCIAL REPORT"."\n"
    //             .sprintf('FROM %s %s TO %s %s',$this->monthfrom_name, $this->yearfrom,$this->monthto_name, $this->yearto)
    //             .'&L'
    //             .'PRINTED BY : '.session('username')
    //             ."\nPAGE : &P/&N"
    //             .'&R'.'PRINTED DATE : '.Carbon::now("Asia/Kuala_Lumpur")->format('d-m-Y')
    //             ."\n".'PRINTED TIME : '.Carbon::now("Asia/Kuala_Lumpur")->format('H:i'));
                
    //             $alphabet = range('A', 'Z');

    //             $event->sheet->getPageMargins()->setTop(1);

    //             $array_month = $this->array_month;

    //             $event->sheet->getStyle('D')
    //                 ->getNumberFormat()
    //                 ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

    //             $index=1; 
    //             foreach ($array_month as $key => $value) {
    //                 $event->sheet->getStyle($alphabet[$index])
    //                     ->getNumberFormat()
    //                     ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    //                 $index++;
    //             }
    //             $event->sheet->getStyle($alphabet[$index])
    //                 ->getNumberFormat()
    //                 ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                
    //             // $event->sheet->getPageSetup()->setRowsToRepeatAtTop([1,1]);
    //             // $event->sheet->getStyle('A:H')->getAlignment()->setWrapText(true);
    //             // $event->sheet->getPageSetup()->setFitToWidth(1);
    //             // $event->sheet->getPageSetup()->setFitToHeight(0);
    //         },
    //     ];
    // }

    // public function calc_bal($obj){
    //     $balance = 0;
    //     foreach ($obj->get() as $key => $value){
            
    //         switch ($value->trantype) {
    //              case 'IN': //dr
    //                 $balance = $balance + floatval($value->amount);
    //                 break;
    //             case 'DN': //dr
    //                 $balance = $balance + floatval($value->amount);
    //                 break;
    //             case 'CN': //cr
    //                 $balance = $balance - floatval($value->amount);
    //                 break;
    //             case 'PV': //cr
    //                 $balance = $balance - floatval($value->amount);
    //                 break;
    //             case 'PD': //cr
    //                 $balance = $balance - floatval($value->amount);
    //                 break;
    //             default:
    //                 // code...
    //                 break;
    //         }
    //     }

    //     return $balance;
    // }

    // public function assign_grouping($grouping,$days){
    //     $group = 0;

    //     foreach ($grouping as $key => $value) {
    //         if(!empty($value) && $days >= intval($value)){
    //             $group = $key;
    //         }
    //     }

    //     return $group;
    // }

    // public static function getQueries($builder){
    //     $addSlashes = str_replace('?', "'?'", $builder->toSql());
    //     return vsprintf(str_replace('?', '%s', $addSlashes), $builder->getBindings());
    // }
}
