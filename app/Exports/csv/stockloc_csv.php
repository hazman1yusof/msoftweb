<?php

namespace App\Exports\csv;

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
use DateTime;
use Carbon\Carbon;
use stdClass;

class stockloc_csv implements FromView
{
    
    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function __construct($request){
        $this->from = $request->from;
        $this->to = $request->to;
        $this->deptcode = $request->deptcode;
    }
    
    public function view(): View{

        $year = Carbon::createFromFormat('Y-m-d', $this->to)->year;

        $table = DB::table('material.stockloc')
                    // ->where('deptcode',$this->deptcode)
                    ->where('year',$year)
                    ->where('compcode','9B');

        // if(!empty($this->from)){
        //         $table = $table->whereDate('postdate','>=',$this->from)
        //                         ->whereDate('postdate','<=',$this->to);
        // }
                    
        $table = $table->get();

        return view('other.csv.stockloc',compact('table'));
    }
}
