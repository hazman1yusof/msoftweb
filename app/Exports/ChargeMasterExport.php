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

class ChargeMasterExport implements FromView, WithEvents, WithColumnWidths
{
    
    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function __construct($chggroup_from,$chggroup_to,$chgcode_from,$chgcode_to)
    {
        
        $this->chggroup_from = $chggroup_from;
        if(empty($chggroup_from)){
            $this->chggroup_from = '%';
        }
        $this->chggroup_to = $chggroup_to;

        $this->chgcode_from = $chgcode_from;
        if(empty($chgcode_from)){
            $this->chgcode_from = '%';
        }
        $this->chgcode_to = $chgcode_to;

        $this->comp = DB::table('sysdb.company')
            ->where('compcode','=',session('compcode'))
            ->first();
    }
    
    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 40,
            'C' => 15,
            'D' => 15,
            'E' => 15,
            'F' => 15,
            'G' => 15,
            'H' => 15,
            'I' => 15,
        ];
    }
    
    public function view(): View
    {
        $chggroup_from = $this->chggroup_from;
        $chggroup_to = $this->chggroup_to;
        $chgcode_from = $this->chgcode_from;
        $chgcode_to = $this->chgcode_to;

        $chgmast = DB::table('hisdb.chgmast as cm')
                ->select('cm.idno', 'cm.compcode', 'cm.unit', 'cm.chgcode', 'cm.description', 'cm.uom as uom_cm', 'cm.packqty', 'cm.recstatus', 'cm.chgtype', 'cm.chggroup', 'cm.chgclass', 'cp.idno as cp_idno','cp.uom as uom_cp','cp.amt1', 'cp.effdate', 'cp.amt2', 'cp.amt3', 'cp.costprice', 'ct.description as ct_desc', 'cg.grpcode', 'cg.description as cg_desc', 'p.uomcode as uom_p')
                ->join('hisdb.chgprice as cp', function($join) {
                    $join = $join->on('cp.chgcode', '=', 'cm.chgcode')
                                ->on('cp.uom', '=', 'cm.uom')
                                ->where('cp.effdate', '<=', Carbon::now("Asia/Kuala_Lumpur"))
                                ->where('cp.compcode', '=', session('compcode'))
                                ->where('cp.recstatus', '=', 'ACTIVE');
                })
                ->join('hisdb.chgtype as ct', function($join) {
                    $join = $join->on('ct.chgtype', '=', 'cm.chgtype')
                                ->where('ct.compcode', '=', session('compcode'))
                                ->where('ct.recstatus', '=', 'ACTIVE');
                })
                ->join('hisdb.chggroup as cg', function($join) {
                    $join = $join->on('cg.grpcode', '=', 'cm.chggroup')
                                ->where('cg.compcode', '=', session('compcode'))
                                ->where('cg.recstatus', '=', 'ACTIVE');
                })
                ->join('material.product AS p', function($join) {
                    $join = $join->on('p.uomcode', '=', 'cm.uom')
                                ->on('p.itemcode', '=', 'cm.chgcode')
                                ->where('p.compcode', '=', session('compcode'));
                })
                ->where('cm.compcode','=',session('compcode'))
                ->where('cm.recstatus', '=', 'ACTIVE')
                ->whereBetween('cm.chggroup', [$chggroup_from, $chggroup_to.'%'])
                ->whereBetween('cm.chgcode', [$chgcode_from, $chgcode_to.'%'])
                ->orderBy('cp.idno','DESC')
                ->get();

        $array_report = [];

        $chgcode_ = null;
        foreach ($chgmast as $key => $value){
            if($chgcode_ == $value->chgcode){
                $chgprice_obj = DB::table('hisdb.chgprice as cp')
                        ->select('cp.chgcode','cp.idno as cp_idno','cp.uom as uom_cp','cp.amt1', 'cp.effdate', 'cp.amt2', 'cp.amt3', 'cp.costprice')
                        ->where('cp.compcode', '=', session('compcode'))
                        ->where('cp.chgcode', '=', $value->chgcode)
                        ->where('cp.uom', '=', $value->uom_cm)
                        ->whereDate('cp.effdate', '<=', Carbon::now("Asia/Kuala_Lumpur"))
                        ->orderBy('cp.effdate','desc');

                $chgprice_obj = $chgprice_obj->first();

                if($value->chgcode == $chgprice_obj->chgcode && $value->cp_idno != $chgprice_obj->cp_idno){
                    // unset($chgmast[$key]);
                    continue;
                }
            }
            $chgcode_=$value->chgcode;
            array_push($array_report, $value);

        }

        $chggroup = collect($array_report)->unique('chggroup');
        $chgtype = collect($array_report)->unique('chgtype');

        return view('setup.chargemaster.chargemaster_excel',compact('header', 'chggroup', 'chgtype', 'array_report'));
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {        
                $event->sheet->getPageSetup()->setPaperSize(9);//A4
                
                $event->sheet->getHeaderFooter()->setOddHeader('&C'.$this->comp->name."\nAP CHARGE PRICE LIST"."\n"
                .sprintf('FROM CHARGE GROUP %s',Carbon::parse($this->date)->format('d-m-Y'))."\n"
                .sprintf('FROM %s TO %s',$this->chggroup_from, $this->chggroup_to)
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
}
