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

class ivtxndt_csv implements FromView
{
    
    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function __construct($request){
        $this->from = $request->from;
        $this->to = $request->to;
    }
    
    public function view(): View{

        $table = DB::table('material.ivtxnhd')
                    ->where('compcode','9B');

        if(!empty($this->from)){
                $table = $table->whereDate('trandate','>=',$this->from)
                                ->whereDate('trandate','<=',$this->to);
        }
                    
        $table = $table->get();

        $collection = collect([]);

        foreach ($table as $key => $value) {
            $ivtxndt = DB::table('material.ivtxndt as iv')
                        ->select('iv.idno','iv.compcode','iv.recno','iv.lineno_','iv.itemcode','iv.uomcode','iv.uomcoderecv','iv.txnqty','iv.netprice','iv.adduser','iv.adddate','iv.upduser','iv.upddate','iv.productcat','iv.draccno','iv.drccode','iv.craccno','iv.crccode','iv.updtime','iv.expdate','iv.remarks','iv.qtyonhand','iv.qtyonhandrecv','iv.batchno','iv.amount','iv.trandate','iv.trantype','iv.deptcode','iv.gstamount','iv.totamount','iv.recstatus','iv.reopen','iv.unit','iv.sndrcv','do.pouom','do.qtydelivered','do.unitprice')
                        ->leftJoin('material.delorddt as do', function($join){
                            $join = $join->on('do.recno', '=', 'iv.recno')
                                        ->on('do.lineno_', '=', 'iv.lineno_')
                                        ->where('do.compcode', '=', '9B');
                        })
                        ->where('iv.compcode','9B')
                        ->where('iv.trantype',$value->trantype)
                        ->where('iv.recno',$value->recno)
                        ->get();

            $collection = $collection->concat($ivtxndt);
        }

        return view('other.csv.ivtxndt',compact('collection'));
    }
}
