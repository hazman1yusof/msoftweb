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
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class InvoiceAPExport implements FromCollection,WithEvents,WithHeadings,WithColumnWidths
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
            'compcode','source','trantype','auditno','actdate','suppcode','document','deptcode'
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
            'H' => 50,   
            'I' => 10,          
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

                // merge cells for full-width
                // $event->sheet->mergeCells('A5:I5');
                // $event->sheet->mergeCells('A6:I6');

                // assign cell values
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
