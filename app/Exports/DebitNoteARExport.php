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
use DateTime;
use Carbon\Carbon;

class DebitNoteARExport implements FromCollection, WithEvents, WithHeadings, WithColumnWidths, WithColumnFormatting
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
        $payer = DB::table('debtor.dbacthdr')
                        ->select('compcode','payercode','amount','entrydate')
                        ->whereBetween('entrydate',[$this->datefr,$this->dateto])
                        ->get();

        return $payer;
    }

    public function headings(): array
    {
        return [
            'compcode','payercode','amount','entrydate'
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 15,    
            'C' => 40,
            'D' => 15,          
        ];
    }

    public function columnFormats(): array
    {
        return [

           'D' => NumberFormat::FORMAT_DATE_DDMMYYYY,

          
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

                // merge cells for full-width
                // $event->sheet->mergeCells('A5:I5');
                // $event->sheet->mergeCells('A6:I6');

                ///// assign cell values
                $event->sheet->setCellValue('A1','PRINTED DATE :');
                $event->sheet->setCellValue('B1', Carbon::now("Asia/Kuala_Lumpur")->format('d-m-Y'));
                $event->sheet->setCellValue('A2','PRINTED TIME :');
                $event->sheet->setCellValue('B2', Carbon::now("Asia/Kuala_Lumpur")->format('H:i'));
                $event->sheet->setCellValue('A3','PRINTED BY :');
                $event->sheet->setCellValue('B3', session('username'));
                $event->sheet->setCellValue('C1','DEBIT NOTE AR REPORT');
                $event->sheet->setCellValue('C2',sprintf('FROM DATE %s TO DATE %s',$this->datefr, $this->dateto));
                $event->sheet->setCellValue('F1',$this->comp->name);
                $event->sheet->setCellValue('F2',$this->comp->address1);
                $event->sheet->setCellValue('F3',$this->comp->address2);
                $event->sheet->setCellValue('F4',$this->comp->address3);
                $event->sheet->setCellValue('F5',$this->comp->address4);
                $event->sheet->setCellValue('A7','COMPCODE');
                $event->sheet->setCellValue('B7','PAYER CODE');
                $event->sheet->setCellValue('C7','AMOUNT');
                $event->sheet->setCellValue('D7','ENTRY DATE');
                //Date::dateTimeToExcel($invoice->created_at);

                ///// assign cell styles
                $event->sheet->getStyle('A1:A3')->applyFromArray($style_datetime);
                $event->sheet->getStyle('C1:C2')->applyFromArray($style_header);
                $event->sheet->getStyle('F1:F5')->applyFromArray($style_address);
                $event->sheet->getStyle('A7:I7')->applyFromArray($style_columnheader);

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
