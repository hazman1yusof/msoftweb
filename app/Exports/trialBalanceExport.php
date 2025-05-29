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

class trialBalanceExport implements FromView, WithEvents, WithColumnWidths,ShouldAutoSize
{
    
    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function __construct($monthfrom,$monthto,$yearfrom,$yearto,$acctfrom,$acctto)
    {
        
        $this->monthfrom = $monthfrom;
        $this->monthto = $monthto;
        $this->monthfrom_name = Carbon::create()->month($monthfrom)->format('F');
        $this->monthto_name = Carbon::create()->month($monthto)->format('F');
        $this->yearfrom = $yearfrom;
        $this->yearto = $yearto;
        $this->acctfrom = $acctfrom;
        if(empty($acctto)){
            $this->acctto = '%';
        }
        $this->acctto = $acctto;

        $this->comp = DB::table('sysdb.company')
            ->where('compcode','=',session('compcode'))
            ->first();

        $array_open = [];
        $array_month = [];
        $array_month_tot = [];
        $array_month_name = [];

        for ($i=0; $i < $monthfrom; $i++) { 
            array_push($array_open,$i);
        }

        for ($i=$monthfrom; $i <= $monthto; $i++) { 
            array_push($array_month,$i);
            array_push($array_month_tot,'0.00');
            array_push($array_month_name,Carbon::create()->month($i)->format('F'));
        }

        $this->array_open = $array_open;
        $this->array_month = $array_month;
        $this->array_month_tot = $array_month_tot;
        $this->array_month_name = $array_month_name;
    }
    
    public function columnWidths(): array
    {
        $alphabet = range('A', 'Z');
        $array_month = $this->array_month;
        $width_ = [
            'A' => 12,
            'B' => 5,
            'C' => 40,
            'D' => 17,
        ];

        $index=4; 
        foreach ($array_month as $key => $value) {
            $width_[$alphabet[$index]] = 17;
            $index++;
            $width_[$alphabet[$index]] = 17;
            $index++;
        }
        $width_[$alphabet[$index]] = 17;

        return $width_;

    }
    
    public function view(): View{
        $monthfrom = intval($this->monthfrom);
        $monthto = intval($this->monthto);
        $yearfrom = $this->yearfrom;
        $yearto = $this->yearto;
        $acctfrom = $this->acctfrom;
        $acctto = $this->acctto;

        $array_open = $this->array_open;
        $array_month = $this->array_month;
        $array_month_tot = $this->array_month_tot;
        $array_month_name = $this->array_month_name;

        // $glmasdtl = DB::table('finance.glmasref as glrf')
        //                 ->select('glrf.glaccno','glrf.description','glrf.accgroup','gldt.glaccount','gldt.openbalance','gldt.actamount1','gldt.actamount2','gldt.actamount3','gldt.actamount4','gldt.actamount5','gldt.actamount6','gldt.actamount7','gldt.actamount8','gldt.actamount9','gldt.actamount10','gldt.actamount11','gldt.actamount12')
        //                 ->leftJoin('finance.glmasdtl as gldt', function($join) use ($yearfrom,$yearto){
        //                     $join = $join->where('gldt.compcode', session('compcode'))
        //                                  ->on('gldt.glaccount','=','glrf.glaccno')
        //                                  ->where('gldt.year', $yearfrom);
        //                                  // ->whereBetween('gldt.year', [$yearfrom,$yearto]);
        //                 })
        //                 ->where('glrf.compcode',session('compcode'))
        //                 ->get();

        $glmasref_ = DB::table('finance.glmasref as glrf')
                        ->select('glrf.glaccno','glrf.description','glrf.accgroup')
                        ->where('glrf.compcode',session('compcode'))
                        ->where('glrf.accgroup','<>','H')
                        ->orderBy('glrf.glaccno')
                        // ->where('glrf.glaccno',10010001)
                        ->get();

        // dd($glmasref_);

        $glmasref = [];
        $totall_openbalance = 0;
        $totall_ytd = 0;
        foreach ($glmasref_ as $key_glrf => $obj_glrf) {
            $arr_glrf = (array) $obj_glrf;
            $glmasdtl = DB::table('finance.glmasdtl as gldt')
                            ->select('gldt.glaccount','gldt.openbalance','gldt.actamount1','gldt.actamount2','gldt.actamount3','gldt.actamount4','gldt.actamount5','gldt.actamount6','gldt.actamount7','gldt.actamount8','gldt.actamount9','gldt.actamount10','gldt.actamount11','gldt.actamount12')
                            ->where('gldt.glaccount','=',$obj_glrf->glaccno)
                            ->where('gldt.year', $yearfrom)
                            ->where('gldt.compcode',session('compcode'))
                            ->get();

            $balance = 0;
            foreach ($glmasdtl as $obj_gldt) {
                $arr_gldt = (array) $obj_gldt;
                foreach ($array_open as $value) {
                    if($value == 0){
                        $balance = $balance + floatval($arr_gldt['openbalance']);
                    }else{
                        $balance = $balance + floatval($arr_gldt['actamount'.$value]);
                    }
                }
            }
            $arr_glrf['tot_openbalance'] = $balance;
            $skip_counter=0;
            $totall_openbalance = $totall_openbalance + $balance;
            if(floatval($balance) != 0){
                $skip_counter++;
            }

            foreach ($array_month as $value) {
                $arr_glrf['tot_actamount'.$value] = 0.00;
            }

            foreach ($glmasdtl as $obj_gldt) {
                $arr_gldt = (array) $obj_gldt;
                foreach ($array_month as $index => $value) {
                    $arr_glrf['tot_actamount'.$value] = $arr_glrf['tot_actamount'.$value] + $arr_gldt['actamount'.$value];
                }
            }

            $ytd = $balance;
            foreach ($array_month as $value) {
                $ytd = $ytd + $arr_glrf['tot_actamount'.$value];
                if(floatval($arr_glrf['tot_actamount'.$value]) != 0){
                    $skip_counter++;
                }
            }
            $arr_glrf['tot_ytd'] = $ytd;
            if($skip_counter>0){
                $arr_glrf['skip'] = 0;
            }else{
                $arr_glrf['skip'] = 1;
            }
            $totall_ytd = $totall_ytd + $ytd;

            // foreach ($array_month as $value) {
            //     $arr_glrf['tot_actamount'.$value] = $glmasdtl->sum('gldt.actamount'.$value);
            // }
            array_push($glmasref,$arr_glrf);
        }

        // dd($glmasref);

        // $glmasref_coll = collect($glmasdtl)->unique('glaccno');
        // $glmasref = [];
        // foreach ($glmasref_coll as $key_glrf => $obj_glrf) {
        //     $obj_glrf = $this->init_object($obj_glrf,$array_month);
        //     foreach ($glmasdtl as $obj_gldt) {
        //         if($obj_glrf->glaccno == $obj_gldt->glaccount){
        //             $obj_glrf = $this->get_openbalance($obj_glrf,$obj_gldt,$array_open);
        //             $obj_glrf = $this->get_array_month($obj_glrf,$obj_gldt,$array_month);
        //         }
        //         $obj_glrf = $this->get_ytd($obj_glrf,$array_month);
        //     }
        //     array_push($glmasref,(array)$obj_glrf);
        // }
        $alphabet = range('A', 'Z');

        $title1 = strtoupper($this->comp->name);
        $title2 = 'TRIAL BALANCE';
        $title3 = 'FROM '.$this->monthfrom_name.' '.$this->yearfrom.' TO '.$this->monthto_name.' '.$this->yearto;
        $title4 = 'PRINTED BY: '.session('username').' on '.Carbon::now("Asia/Kuala_Lumpur")->format('d-m-Y H:i');

        return view('finance.GL.trialBalance.trialBalance_excel',compact('glmasref','array_month','array_month_name','alphabet','title1','title2','title3','title4'));
    }
    
    public function registerEvents(): array{
        return [
            AfterSheet::class => function(AfterSheet $event) {
                
                $event->sheet->getPageSetup()->setPaperSize(9);//A4
                
                $event->sheet->getHeaderFooter()->setOddHeader('&C'.$this->comp->name."\nTRIAL BALANCE"."\n"
                .sprintf('FROM %s %s TO %s %s',$this->monthfrom_name, $this->yearfrom,$this->monthto_name, $this->yearto)
                .'&L'
                .'PRINTED BY : '.session('username')
                ."\nPAGE : &P/&N"
                .'&R'.'PRINTED DATE : '.Carbon::now("Asia/Kuala_Lumpur")->format('d-m-Y')
                ."\n".'PRINTED TIME : '.Carbon::now("Asia/Kuala_Lumpur")->format('H:i'));
                
                $alphabet = range('A', 'Z');

                $event->sheet->getPageMargins()->setTop(1);

                $array_month = $this->array_month;

                $event->sheet->getStyle('D')
                    ->getNumberFormat()
                    ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

                $index=4; 
                foreach ($array_month as $key => $value) {
                    $event->sheet->getStyle($alphabet[$index])
                        ->getNumberFormat()
                        ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $index++;
                    $event->sheet->getStyle($alphabet[$index])
                        ->getNumberFormat()
                        ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $index++;
                }
                $event->sheet->getStyle($alphabet[$index])
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

    public function init_object($obj_glrf,$array_month){
        $arr_glrf = (array) $obj_glrf;
        $arr_glrf['tot_openbalance'] = 0.00;
        $arr_glrf['tot_ytd'] = 0.00;
        foreach ($array_month as $value) {
            $arr_glrf['tot_actamount'.$value] = 0.00;
        }
        return json_decode(json_encode($arr_glrf));
    }

    public function get_openbalance($obj_glrf,$obj_gldt,$array_open){
        $arr_gldt = (array) $obj_gldt;
        $balance = floatval($obj_glrf->tot_openbalance);
        foreach ($array_open as $value) {
            if($value == 0){
                $balance = $balance + floatval($arr_gldt['openbalance']);
            }else{
                $balance = $balance + floatval($arr_gldt['actamount'.$value]);
            }
        }
        $obj_glrf->tot_openbalance = $balance;
        return $obj_glrf;
    }

    public function get_array_month($obj_glrf,$obj_gldt,$array_month){
        $arr_glrf = (array) $obj_glrf;
        $arr_gldt = (array) $obj_gldt;
        foreach ($array_month as $value) {
            $arr_glrf['tot_actamount'.$value] = floatval($arr_glrf['tot_actamount'.$value]) + floatval($arr_gldt['actamount'.$value]);
        }
        return json_decode(json_encode($arr_glrf));
    }

    public function get_ytd($obj_glrf,$array_month){
        $arr_glrf = (array) $obj_glrf;
        $ytd = floatval($arr_glrf['tot_openbalance']);
        foreach ($array_month as $value) {
            $ytd = floatval($ytd) + floatval($arr_glrf['tot_actamount'.$value]);
        }
        $arr_glrf['tot_ytd'] = $ytd;
        return json_decode(json_encode($arr_glrf));
    }
}
