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
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Shared\Date;
//use Maatwebsite\Excel\Concerns\WithMapping;, WithMapping
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use DateTime;
use Carbon\Carbon;

class InvoiceAPExport implements FromCollection, WithEvents, WithHeadings, WithColumnWidths, WithColumnFormatting
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct($datefr,$dateto)
    {
        
        $this->datefr = $datefr;
        $this->dateto = $dateto;

        $this->comp = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();
    }

    public function collection()
    {
        $apacthdr = DB::table('finance.apacthdr')
                        ->select('auditno','actdate','suppcode','document','deptcode')
                        ->whereBetween('actdate',[$this->datefr,$this->dateto])
                        ->get();

        return $apacthdr;
    }

    public function headings(): array
    {
        return [
            'Auditno','Actdate','Suppcode','Document','Deptcode'
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 10,    
            'C' => 30,
            'D' => 20,
            'E' => 30, 
            'D' => 20,
               
        ];
    }

    // public function map($apacthdr): array
    // {
    //     return [
           
    //       //  Date::dateTimeToExcel($apacthdr->Carbon::now("Asia/Kuala_Lumpur")),
    //     ];
    // }

    public function columnFormats(): array
    {
        return [
            
            //  'B1' => 'dd-mm-yyyy',
           // 'F2' => NumberFormat::FORMAT_DATE_DDMMYYYY,
           'B' => NumberFormat::FORMAT_DATE_DDMMYYYY,

          
        ];
    }


    public function registerEvents(): array
    {


        return [
            AfterSheet::class => function(AfterSheet $event) {
                // set up a style array for cell formatting
                $style_header = [
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER
                    ]
                ];

                $style_address = [
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_RIGHT
                    ]
                ];

                // at row 1, insert 2 rows
                $event->sheet->insertNewRowBefore(1, 6);

                // assign cell values
                $event->sheet->setCellValue('A1','PRINTED DATE:');
                $event->sheet->setCellValue('B1', Carbon::now("Asia/Kuala_Lumpur"));
                $event->sheet->setCellValue('A2','PRINTED TIME:',);
                $event->sheet->setCellValue('A3','PRINTED BY:');
                $event->sheet->setCellValue('B3', session('username'));
                $event->sheet->setCellValue('D1','INVOICE AP REPORT');
                $event->sheet->setCellValue('D2',sprintf('FROM DATE %s TO DATE %s',$this->datefr, $this->dateto));
                $event->sheet->setCellValue('G1',$this->comp->name);
                $event->sheet->setCellValue('G2',$this->comp->address1);
                $event->sheet->setCellValue('G3',$this->comp->address2);
                $event->sheet->setCellValue('G4',$this->comp->address3);
                $event->sheet->setCellValue('G5',$this->comp->address4);

                // assign cell styles
                $event->sheet->getStyle('D1:D2')->applyFromArray($style_header);
                $event->sheet->getStyle('G1:G5')->applyFromArray($style_address);

                // $drawing = new Drawing();
                // $drawing->setName('Logo');
                // $drawing->setDescription('This is my logo');
                // $drawing->setPath(public_path('/img/logo.jpg'));
                // $drawing->setHeight(80);
                // $drawing->setCoordinates('E1');
                // $drawing->setOffsetX(40);
                // $drawing->setWorksheet($event->sheet->getDelegate());

            },
        ];
    }


}
