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
use Maatwebsite\Excel\Concerns\FromQuery;
use DateTime;
use Carbon\Carbon;

class PaymentVoucherExport implements  WithEvents, WithHeadings, WithColumnWidths, WithColumnFormatting, FromQuery, FromCollection
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
                        ->select('auditno', 'trantype', 'actdate','suppcode','document')
                        ->whereBetween('actdate',[$this->datefr,$this->dateto])
                        ->get();

        return $apacthdr;
    }

    public function query()
    {

        $apacthdr_obj = DB::table('finance.apacthdr')
                    ->select('apacthdr.auditno', 'apacthdr.trantype', 'apacthdr.actdate', 'apacthdr.suppcode', 'supplier.Name', 'apacthdr.document')
                    ->whereBetween('apacthdr.actdate',[$this->datefr,$this->dateto])
                    ->leftJoin('material.supplier', function($join){
                        $join = $join->on('supplier.SuppCode', '=', 'apacthdr.suppcode');
                        $join = $join->on('supplier.compcode', '=', 'apacthdr.compcode');
                    })
                    ->where('apacthdr.compcode','=',session('compcode'))
                    ->where('apacthdr.trantype','=','PV')
                    ->orWhere('apacthdr.trantype','=','PD')
                    ->orderBy('apacthdr.auditno','DESC');

        return $apacthdr_obj;
    }


    public function headings(): array
    {
        return [
            'Auditno','TT', 'Actdate','Suppcode', 'Name', 'Document', 
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 10,    
            'C' => 15,
            'D' => 15,
            'E' => 40, 
            'D' => 15,
               
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
                $event->sheet->setCellValue('B1', Carbon::now("Asia/Kuala_Lumpur")->format('d-m-Y'));
                $event->sheet->setCellValue('A2','PRINTED TIME:',);
                $event->sheet->setCellValue('B2', Carbon::now("Asia/Kuala_Lumpur")->format('H:i'));
                $event->sheet->setCellValue('A3','PRINTED BY:');
                $event->sheet->setCellValue('B3', session('username'));
                $event->sheet->setCellValue('D1','PAYMENT VOUCHER REPORT');
                $event->sheet->setCellValue('D2',sprintf('FROM DATE %s TO DATE %s',$this->datefr, $this->dateto));
                $event->sheet->setCellValue('G1',$this->comp->name);
                $event->sheet->setCellValue('G2',$this->comp->address1);
                $event->sheet->setCellValue('G3',$this->comp->address2);
                $event->sheet->setCellValue('G4',$this->comp->address3);
                $event->sheet->setCellValue('G5',$this->comp->address4);

                // assign cell styles
                $event->sheet->getStyle('D1:D2')->applyFromArray($style_header);
                $event->sheet->getStyle('G1:G5')->applyFromArray($style_address);

            },
        ];
    }


}
