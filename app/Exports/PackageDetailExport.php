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

class PackageDetailExport implements FromView, WithEvents, WithColumnWidths
{
    
    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function __construct($pkgcodePkg,$effectdate)
    {
        
        $this->pkgcodePkg = $pkgcodePkg;
        $this->effectdate = $effectdate;

        $this->comp = DB::table('sysdb.company')
            ->where('compcode','=',session('compcode'))
            ->first();
    }
    
    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 100,
            'C' => 15,
        ];
    }
    
    public function view(): View
    {
        $pkgcodePkg = $this->pkgcodePkg;
        $effectdate = Carbon::createFromFormat('d/m/Y', $this->effectdate)->format('Y-m-d');

        //dd($effectdate);
        $chgmast = DB::table('hisdb.chgmast')
                    ->select('compcode', 'chgcode', 'description')
                    ->where('compcode','=',session('compcode'))
                    ->where('chgcode', '=', $pkgcodePkg)
                    ->where('recstatus', '=', 'ACTIVE')
                    ->first();
       //dd($chgmast);
        $pkgdet = DB::table('hisdb.pkgdet as pd')
                ->select('pd.idno', 'pd.compcode', 'pd.pkgcode', 'pd.recstatus', 'pd.effectdate', 'pd.quantity', 'pd.chgcode', 'cp.effdate', 'cm.description as cc_desc')
                ->join('hisdb.chgprice as cp', function($join) {
                    $join = $join->on('cp.chgcode', '=', 'pd.pkgcode')
                                ->on('cp.effdate', '=', 'pd.effectdate')
                                ->where('cp.compcode', '=', session('compcode'))
                                ->where('cp.recstatus', '=', 'ACTIVE');
                })
                ->join('hisdb.chgmast as cm', function($join) {
                    $join = $join->on('cm.chgcode', '=', 'pd.chgcode')
                                ->where('cm.compcode', '=', session('compcode'))
                                ->where('cm.recstatus', '=', 'ACTIVE');
                })
                ->where('pd.compcode','=',session('compcode'))
                ->where('pd.pkgcode', '=', $pkgcodePkg)
                ->where('pd.effectdate', '=', $effectdate)
                ->where('pd.recstatus', '=', 'ACTIVE')
                ->orderBy('pd.idno','DESC')
                ->get();
        
        //dd($pkgdet);

        return view('setup.chargemaster.pkgDetail_excel',compact('pkgdet', 'chgmast'));
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {        
                $event->sheet->getPageSetup()->setPaperSize(9);//A4
                
                $event->sheet->getHeaderFooter()->setOddHeader('&C'.$this->comp->name."\nPACKAGE DEALS LISTING"."\n"
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
