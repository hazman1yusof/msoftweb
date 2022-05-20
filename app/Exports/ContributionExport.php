<?php

namespace App\Exports;
use App\Exports\Sheets\ContributionExportSheet;

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
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use DateTime;
use Carbon\Carbon;

class ContributionExport implements WithMultipleSheets
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;

    public function __construct()
    {
                
    }

    public function sheets(): array
    {
        $sheets = [];
        $drcode = DB::table('debtor.drcontrib')
                        ->select('drcontrib.drcode', 'doctor.doctorname', 'drcontrib.chgcode')
                        ->leftJoin('hisdb.doctor', function($join){
                            $join = $join->on('doctor.doctorcode', '=', 'drcontrib.drcode');
                            $join = $join->on('doctor.compcode', '=', 'drcontrib.compcode');
                        })
                        ->where('drcontrib.compcode','=',session('compcode'))
                        ->get();

        foreach ($drcode as $key => $value) {
            $sheets[] = new ContributionExportSheet($value->drcode);
        }
        return $sheets;
    }


}
