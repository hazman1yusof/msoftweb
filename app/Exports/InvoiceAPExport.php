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
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use DateTime;
use Carbon\Carbon;

class InvoiceAPExport implements FromCollection, WithEvents, WithHeadings, WithColumnWidths, WithMapping, WithColumnFormatting
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
                        ->select('compcode','source','trantype','auditno','actdate','suppcode','document','deptcode')
                        ->whereBetween('actdate',[$this->datefr,$this->dateto])
                        ->get();

        return $apacthdr;
    }

    public function headings(): array
    {
        return [
            'Compcode','Source','Trantype','Auditno','Actdate','Suppcode','Document','Deptcode'
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 10,    
            'C' => 10,
            'D' => 10,   
            'E' => 20,
            'F' => 30,   
            'G' => 30,
            'H' => 30,          
        ];
    }

    public function columnFormats(): array
    {
        return [
          
            'A1' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }

    public function map($date): array
    {
        return [
            //$date = Carbon::now();
            //$date->toDateString();
            Date::dateTimeToExcel($date->Carbon::now())
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
                
                // $dateTimeNow = time();
                // $excelDateValue = \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel( $dateTimeNow );
                // // Set cell A6 with the Excel date/time value
                // $event->sheet->setCellValue(
                //     'A1',
                //     $excelDateValue
                // );
                // // Set the number format mask so that the excel timestamp will be displayed as a human-readable date/time
                // $event->sheet->getStyle('A1')
                //     ->getNumberFormat()
                //     ->setFormatCode(
                //         \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_DATETIME
                //     );

                // assign cell values
                $event->sheet->setCellValue('A1','PRINTED DATE:', );
                $event->sheet->setCellValue('A2','PRINTED TIME:',);
                $event->sheet->setCellValue('A3','PRINTED BY:',);
                $event->sheet->setCellValue('F1','INVOICE AP REPORT');
                $event->sheet->setCellValue('F2',sprintf('FROM DATE %s TO DATE %s',$this->datefr, $this->dateto));
                $event->sheet->setCellValue('H1',$this->comp->name);
                $event->sheet->setCellValue('H2',$this->comp->address1);
                $event->sheet->setCellValue('H3',$this->comp->address2);
                $event->sheet->setCellValue('H4',$this->comp->address3);
                $event->sheet->setCellValue('H5',$this->comp->address4);

                // assign cell styles
                $event->sheet->getStyle('F1:F2')->applyFromArray($style_header);
                $event->sheet->getStyle('H1:H5')->applyFromArray($style_address);

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
