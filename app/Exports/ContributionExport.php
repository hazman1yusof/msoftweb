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
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use DateTime;
use Carbon\Carbon;

class ContributionExport implements FromCollection, WithEvents, WithHeadings, WithColumnWidths, WithColumnFormatting
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
        $drcontrib = DB::table('debtor.drcontrib')
                        ->select('drcontrib.drcode', 'doctor.doctorname', 'drcontrib.chgcode')
                        ->leftJoin('hisdb.doctor', function($join){
                            $join = $join->on('doctor.doctorcode', '=', 'drcontrib.drcode');
                            $join = $join->on('doctor.compcode', '=', 'drcontrib.compcode');
                        })
                        ->where('drcontrib.compcode','=',session('compcode'))
                        ->get();
                        
        return $drcontrib;
    }

    public function headings(): array
    {
        return [
            'Doctor Code','Doctor Name','Charge Code'
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 40,    
            'C' => 35,
            'D' => 30,
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
         //  'B' => NumberFormat::FORMAT_DATE_DDMMYYYY,
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

                $style_address1 = [
            
                    'alignment' => [
                        'wrapText' => true
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
                $event->sheet->setCellValue('C1','DOCTOR CONTRIBUTION');
                $event->sheet->setCellValue('G1',$this->comp->name);
                $event->sheet->setCellValue('G2',$this->comp->address1);
                $event->sheet->setCellValue('G3',$this->comp->address2);
                $event->sheet->setCellValue('G4',$this->comp->address3);
                $event->sheet->setCellValue('G5',$this->comp->address4);

                // assign cell styles
                $event->sheet->getStyle('C1:C2')->applyFromArray($style_header);
                $event->sheet->getStyle('G1:G5')->applyFromArray($style_address);
                $event->sheet->getStyle('C:D')->getAlignment()->setWrapText(true);

            },
        ];
    }


}
