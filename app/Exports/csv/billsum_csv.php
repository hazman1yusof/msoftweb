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

class billsum_csv implements FromView
{
    
    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function __construct($request){
        $this->from = $request->from;
        $this->to = $request->to;
    }
    
    public function view(): View{

        $table = DB::table('debtor.dbacthdr')
                    ->where('compcode','9B')
                    ->where('source','PB')
                    ->where('trantype','IN')
                    ->where('recstatus','POSTED');

        if(!empty($this->from)){
                $table = $table->whereDate('posteddate','>=',$this->from)
                                ->whereDate('posteddate','<=',$this->to);
        }
                    
        $table = $table->get();

        $collection = collect([]);

        foreach ($table as $key => $value) {
            $billsum = DB::table('debtor.billsum')
                        ->where('compcode','9B')
                        ->where('invno',$value->invno)
                        ->get();

            $collection = $collection->concat($billsum);
        }

        return view('other.csv.billsum',compact('collection'));
    }
}