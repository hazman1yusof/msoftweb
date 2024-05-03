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

class RepackExport implements FromView, WithEvents, WithColumnWidths
{
    
    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function __construct($recno)
    {
        $this->recno = $recno;
        
        $this->comp = DB::table('sysdb.company')
            ->where('compcode','=',session('compcode'))
            ->first();
    }
    
    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 15,
            'C' => 20,
            'D' => 10,
            'E' => 10,
            'F' => 15,
            'G' => 15,

        ];
    }
    
    public function view(): View
    {
        $recno = $this->recno;

        $repackhd = DB::table('material.repackhd as hd')
            ->select('hd.idno','hd.compcode','hd.recno','hd.deptcode','hd.newitemcode','hd.docno','hd.trandate','hd.trantime','hd.outqty','hd.uomcode','hd.adduser','hd.adddate','hd.upduser','hd.upddate','hd.avgcost','hd.amount','hd.recstatus','p.description as hd_desc')
            ->leftJoin('material.product as p', function($join){
                $join = $join->on('p.itemcode', '=', 'hd.newitemcode')
                             ->on('p.uomcode', '=', 'hd.uomcode')
                             ->where('p.compcode','=',session('compcode'));
            })
            ->where('hd.compcode','=',session('compcode'))
            ->where('hd.recno','=',$recno)
            ->first();

        $repackdt = DB::table('material.repackdt AS dt')
            ->select('dt.idno','dt.compcode','dt.recno','dt.deptcode','dt.olditemcode','dt.lineno_','dt.uomcode','dt.inpqty','dt.adduser','dt.adddate','dt.upduser','dt.upddate','dt.avgcost','dt.amount','p.description as dt_desc')
            ->leftJoin('material.product as p', function($join){
                        $join = $join->on('p.itemcode', '=', 'dt.olditemcode')
                                    ->on('p.uomcode', '=', 'dt.uomcode')
                                    ->where('p.compcode','=',session('compcode'));
                    })
            ->where('dt.compcode','=',session('compcode'))
            ->where('dt.recno','=',$recno)
            ->get();
        
        return view('material.repack.repack_excel',compact('repackhd','repackdt'));
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getPageSetup()->setPaperSize(9);//A4
                
                $event->sheet->getHeaderFooter()->setOddHeader('&C'.$this->comp->name."\nREPACK"."\n"
                .'&L'
                .'PRINTED BY : '.session('username')
                ."\nPAGE : &P/&N"
                .'&R'.'PRINTED DATE : '.Carbon::now("Asia/Kuala_Lumpur")->format('d-m-Y')
                ."\n".'PRINTED TIME : '.Carbon::now("Asia/Kuala_Lumpur")->format('H:i'));
                
                $event->sheet->getPageMargins()->setTop(1);
                
                $event->sheet->getPageSetup()->setRowsToRepeatAtTop([2,2]);
                $event->sheet->getStyle('A:H')->getAlignment()->setWrapText(true);
                $event->sheet->getPageSetup()->setFitToWidth(1);
                $event->sheet->getPageSetup()->setFitToHeight(0);
            },
        ];
    }
    
}
