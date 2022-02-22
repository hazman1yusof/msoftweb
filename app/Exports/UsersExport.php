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

class UsersExport implements FromCollection,ShouldAutoSize,WithDrawings,WithHeadings,WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public $results;

    public function __construct($datefr,$dateto)
    {
        $this->datefr = $datefr;
        $this->dateto = $dateto;
    }

    public function collection()
    {
        $dbacthdr = DB::table('debtor.dbacthdr')
                        ->select('compcode','source','trantype','auditno','entrydate','debtorcode','payercode','remark','deptcode')
                        ->whereBetween('entrydate',[$this->datefr,$this->dateto])
                        ->get();


        $this->results = $dbacthdr->toArray();

        return $dbacthdr;
    }

    public function headings(): array
    {
        return [
            'compcode','source','trantype','auditno','entrydate','debtorcode','payercode','remark','deptcode'
        ];
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('This is my logo');
        $drawing->setPath(public_path('/img/logo.jpg'));
        $drawing->setHeight(80);
        $drawing->setCoordinates('E1');

        return $drawing;
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function(BeforeSheet $event) {
                // set up a style array for cell formatting
                $style_text_center = [
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER
                    ]
                ];

                // at row 1, insert 2 rows
                $event->sheet->insertNewRowBefore(1, 7);

                // merge cells for full-width
                $event->sheet->mergeCells('A5:I5');
                $event->sheet->mergeCells('A6:I6');

                // assign cell values
                $event->sheet->setCellValue('A5','Top Triggers Report');
                $event->sheet->setCellValue('A6','SECURITY CLASSIFICATION - UNCLASSIFIED [Generator: Admin]');

                // assign cell styles
                $event->sheet->getStyle('A1:A2')->applyFromArray($style_text_center);
            },
        ];
    }


}
