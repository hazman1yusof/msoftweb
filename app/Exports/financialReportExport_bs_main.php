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
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\financialReportExport_bs;
use App\Exports\financialReportExport_bsnote;
use DateTime;
use Carbon\Carbon;
use stdClass;

class financialReportExport_bs_main implements WithMultipleSheets
{
    
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;

    public function __construct($monthfrom,$monthto,$yearfrom,$yearto,$reporttype)
    {
        $this->monthfrom = $monthfrom;
        $this->monthto = $monthto;
        $this->yearfrom = $yearfrom;
        $this->yearto = $yearto;
        $this->reporttype = $reporttype;
    }

    public function sheets(): array
    {
        $sheets = [];

        $sheets[0] = new financialReportExport_bs($this->monthfrom,$this->monthto,$this->yearfrom,$this->yearto,$this->reporttype);
        $sheets[1] = new financialReportExport_bsnote($this->monthfrom,$this->monthto,$this->yearfrom,$this->yearto,$this->reporttype);

        return $sheets;
    }
    
}
