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

class SalesOrderExport implements FromCollection,WithEvents,WithHeadings,WithColumnWidths, WithColumnFormatting
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
        $dbacthdr = DB::table('debtor.dbacthdr')
                        ->select('compcode','source','trantype','auditno','entrydate','debtorcode','payercode','remark','deptcode')
                        ->whereBetween('entrydate',[$this->datefr,$this->dateto])
                        ->get();

        return $dbacthdr;
    }

    public function headings(): array
    {
        return [
            'compcode','source','trantype','auditno','entrydate','deptcode','debtorcode','payercode','remark'
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 10,    
            'C' => 10,
            'D' => 8,   
            'E' => 12,
            'F' => 12,   
            'G' => 12,
            'H' => 12,   
            'I' => 50,          
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
                $event->sheet->setCellValue('F1','SALES ORDER REPORT');
                $event->sheet->setCellValue('F2',sprintf('FROM DATE %s TO DATE %s',$this->datefr, $this->dateto));
                $event->sheet->setCellValue('I1',$this->comp->name);
                $event->sheet->setCellValue('I2',$this->comp->address1);
                $event->sheet->setCellValue('I3',$this->comp->address2);
                $event->sheet->setCellValue('I4',$this->comp->address3);
                $event->sheet->setCellValue('I5',$this->comp->address4);

                //Date::dateTimeToExcel($invoice->created_at);

                ///// assign cell styles
                $event->sheet->getStyle('A1:A3')->applyFromArray($style_datetime);
                $event->sheet->getStyle('F1:F2')->applyFromArray($style_header);
                $event->sheet->getStyle('I1:I5')->applyFromArray($style_address);

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
