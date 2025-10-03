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

class print_implant_patientExport implements FromView, WithEvents, WithColumnWidths, WithColumnFormatting
{
    
    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function __construct()
    {
        $this->comp = DB::table('sysdb.company')
            ->where('compcode','=',session('compcode'))
            ->first();
    }

    public function columnFormats(): array
    {
        return [
            // 'C' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            // 'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }
    
    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 30,
            'C' => 20,
            'D' => 50,
            'E' => 50,
            'F' => 50,
            'G' => 10,
            'H' => 10,
        ];
    }
    
    public function view(): View{
        
        $pat_mast = DB::table('hisdb.pat_mast as pm')
                        ->select('pm.idno','pm.CompCode','pm.MRN','pm.Episno','pm.Name','pm.Address1','pm.Address2','pm.Address3','pm.Postcode','pm.citycode','pm.AreaCode','pm.StateCode','pm.CountryCode','pm.telh','pm.telhp','pm.Newic','pm.NewMrn')
                        ->where('pm.compcode',session('compcode'))
                        ->orderBy('pm.idno','DESC')
                        ->get();

        return view('finance.GL.einvoice.implant_patmast_excel',compact('pat_mast'));
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {        
                $event->sheet->getPageSetup()->setPaperSize(9);//A4
                
                $event->sheet->getHeaderFooter()->setOddHeader('&C'.$this->comp->name."\nImplant Patient LISTING"
                .'&L'
                .'PRINTED BY : '.session('username')
                ."\nPAGE : &P/&N"
                .'&R'.'PRINTED DATE : '.Carbon::now("Asia/Kuala_Lumpur")->format('d-m-Y')
                ."\n".'PRINTED TIME : '.Carbon::now("Asia/Kuala_Lumpur")->format('H:i'));
                
                $event->sheet->getPageMargins()->setTop(1);
                
                $event->sheet->getPageSetup()->setRowsToRepeatAtTop([1,1]);
                // $event->sheet->getStyle('A:H')->getAlignment()->setWrapText(true);
                // $event->sheet->getStyle('A1:A10')->getNumberFormat()->setFormatCode('0');
                $event->sheet->getPageSetup()->setFitToWidth(1);
                $event->sheet->getPageSetup()->setFitToHeight(0);
            },
        ];
    }
}
