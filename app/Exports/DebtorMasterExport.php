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

class DebtorMasterExport implements FromCollection,WithEvents,WithHeadings,WithColumnWidths, WithColumnFormatting, ShouldAutoSize
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

    public function collection()
    {
        $debtormast = DB::table('debtor.debtormast')
                        ->select([
                            'compcode','debtorcode','name',
                            DB::raw('CONCAT_WS("\n",debtormast.address1, debtormast.address2, debtormast.address3, debtormast.address4) AS cust_address')
                        ])
                        ->get();
        return $debtormast;
        
    }

    public function headings(): array
    {
        return [
            'compcode','debtorcode','name','cust_address'
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 15,    
            'C' => 30,
            'D' => 35,   
            'E' => 20,
            'F' => 20,   
            'G' => 40,         
            'H' => 40,      
        ];
    }

    public function columnFormats(): array
    {
        return [

           //'B' => NumberFormat::FORMAT_DATE_DDMMYYYY,    
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

                $style_address_header = [
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_RIGHT
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
                $event->sheet->setCellValue('D1','DEBTOR MASTER REPORT');
               // $event->sheet->setCellValue('D2',sprintf('FROM DATE %s TO DATE %s',$this->datefr, $this->dateto));
                $event->sheet->setCellValue('G1',$this->comp->name);
                $event->sheet->setCellValue('G2',$this->comp->address1);
                $event->sheet->setCellValue('G3',$this->comp->address2);
                $event->sheet->setCellValue('G4',$this->comp->address3);
                $event->sheet->setCellValue('G5',$this->comp->address4);
                $event->sheet->setCellValue('A7','COMPCODE');
                $event->sheet->setCellValue('B7','DEBTOR CODE');
                $event->sheet->setCellValue('C7','NAME');
                $event->sheet->setCellValue('D7','CUSTOMER ADDRESS');

                ///// assign cell styles
                $event->sheet->getStyle('D1:D2')->applyFromArray($style_header);
                $event->sheet->getStyle('G1:G5')->applyFromArray($style_address_header);
                $event->sheet->getStyle('A7:G7')->applyFromArray($style_columnheader);

                ////// wraptext 
                $event->sheet->getStyle('D:G')->getAlignment()->setWrapText(true);
                $event->sheet->getStyle('C')->getAlignment()->setWrapText(true);

                ////// merge cells for address
                //$event->sheet->mergeCells('D8:G8');


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
