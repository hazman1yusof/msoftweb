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

class delorddt_csv implements FromView
{
    
    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function __construct($request){
        $this->from = $request->from;
        $this->to = $request->to;
    }
    
    public function view(): View{

        $table = DB::table('material.delordhd')
                    ->where('compcode','9B')
                    ->where('recstatus','POSTED');

        if(!empty($this->from)){
                $table = $table->whereDate('postdate','>=',$this->from)
                                ->whereDate('postdate','<=',$this->to);
        }
                    
        $table = $table->get();

        $collection = collect([]);

        foreach ($table as $key => $value) {
            $delorddt = DB::table('material.delorddt')
                        ->where('compcode','9B')
                        ->where('recno',$value->recno)
                        ->get();

            $collection = $collection->concat($delorddt);
        }

        return view('other.csv.delorddt',compact('collection'));
    }
}
