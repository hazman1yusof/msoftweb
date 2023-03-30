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
        $apacthdr = DB::table('finance.apacthdr as h')
                    ->select('h.auditno as AUDITNO','h.actdate as ACTDATE','h.suppcode as SUPPCODE','s.name as NAME','h.document as DOCUMENT','h.deptcode as DEPTCODE')
                    ->leftJoin('material.supplier as s', 's.SuppCode', '=', 'h.suppcode')
                    ->whereBetween('h.actdate',[$this->datefr,$this->dateto])
                    ->get();

        return $apacthdr;
    }

    public function headings(): array
    {
        return [
            'AUDITNO','ACTDATE','SUPPCODE','NAME','DOCUMENT','DEPTCODE'
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 10,    
            'C' => 10,
            'D' => 30,
            'E' => 30, 
            'F' => 20,
            'G' => 20,
        ];
    }

    public function columnFormats(): array
    {
        return [
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

                $style_datetime = [
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_LEFT
                    ]
                ];

                $style_columnheader = [
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER
                    ]
                ];

                // at row 1, insert 2 rows
                $event->sheet->insertNewRowBefore(1, 6);

                // assign cell values
                $event->sheet->setCellValue('A1','PRINTED DATE:');
                $event->sheet->setCellValue('B1', Carbon::now("Asia/Kuala_Lumpur")->format('d-m-Y'));
                $event->sheet->setCellValue('A2','PRINTED TIME:',);
                $event->sheet->setCellValue('B2', Carbon::now("Asia/Kuala_Lumpur")->format('H:i'));
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
                $event->sheet->getStyle('A7:F7')->applyFromArray($style_columnheader);
            },
        ];
    }
}
