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

class apactdtl_csv implements FromView
{
    
    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function __construct($request){
        $this->from = $request->from;
        $this->to = $request->to;
    }
    
    public function view(): View{

        $table = DB::table('finance.apacthdr as ahdr')
                    ->select('adtl.idno','adtl.compcode','adtl.source','adtl.trantype','adtl.auditno','adtl.lineno_','adtl.entrydate','adtl.document','adtl.reference','adtl.amount','adtl.stat','adtl.mrn','adtl.episno','adtl.billno','adtl.paymode','adtl.allocauditno','adtl.alloclineno','adtl.alloctnauditno','adtl.alloctnlineno','adtl.lastuser','adtl.lastupdate','adtl.grnno','adtl.dorecno','adtl.category','adtl.deptcode','adtl.adduser','adtl.adddate','adtl.recstatus','adtl.upduser','adtl.upddate','adtl.deluser','adtl.deldate','adtl.GSTCode','adtl.AmtB4GST','adtl.unit','adtl.taxamt')
                    ->join('finance.apactdtl as adtl', function($join){
                        $join = $join->on('adtl.source', '=', 'ahdr.source')
                                    ->on('adtl.trantype', '=', 'ahdr.trantype')
                                    ->on('adtl.auditno', '=', 'ahdr.auditno')
                                    ->where('adtl.compcode','=',session('compcode'));
                    })
                    ->where('ahdr.recstatus','POSTED')
                    ->where('ahdr.source','AP')
                    ->where('ahdr.trantype','IN')
                    ->where('ahdr.compcode','9B');

        if(!empty($this->from)){
                $table = $table->whereDate('ahdr.postdate','>=',$this->from)
                                ->whereDate('ahdr.postdate','<=',$this->to);
        }
                    
        $table = $table->get();

        return view('other.csv.apactdtl',compact('table'));
    }
}