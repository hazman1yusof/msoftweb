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

class check_do_stockconsign_main implements WithMultipleSheets
{
    
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;

    public function __construct($period,$year)
    {
        $this->period = $period;
        $this->year = $year;
    }

    public function sheets(): array
    {
        $period = $this->period;
        $year = $this->year;
        $_20010042 = 0;
        $_20010044 = 0;
        $sheets = [];

        $day_start = Carbon::createFromFormat('Y-m-d','2025-'.$period.'-01')->startOfMonth()->format('Y-m-d');
        $day_end = Carbon::createFromFormat('Y-m-d','2025-'.$period.'-01')->endOfMonth()->format('Y-m-d');

        $delorddt = DB::table('material.delorddt as dt')
                        ->select('dt.recno','dt.lineno_','dt.pricecode','dt.itemcode','dt.uomcode','dt.pouom','dt.suppcode','dt.trandate','dh.deldept','dt.deliverydate','dt.qtytag','dt.unitprice','dt.amtdisc','dt.perdisc','dt.prortdisc','dt.amtslstax','dt.perslstax','dt.netunitprice','dt.remarks','dt.qtyorder','dt.qtydelivered','dt.qtyoutstand','dt.productcat','dt.draccno','dt.drccode','dt.craccno','dt.crccode','dt.source','dt.updtime','dt.polineno','dt.itemmargin','dt.amount','dt.deluser','dt.deldate','dt.recstatus','dt.taxcode','dt.totamount','dt.qtyreturned','dh.postdate','dh.trantype','dh.docno')
                        ->where('dt.compcode',session('compcode'))
                        ->where('dt.recstatus','!=','CANCELLED')
                        ->where('dt.recstatus','!=','DELETE')
                        ->join('material.delordhd as dh', function($join) use ($day_start,$day_end){
                            $join = $join->on('dh.recno', '=', 'dt.recno')
                                          ->where('dh.recstatus','POSTED')
                                          ->whereDate('dh.postdate','>=',$day_start)
                                          ->whereDate('dh.postdate','<=',$day_end)
                                          // ->whereIn('dh.deldept',['IMP','KHEALTH','FKWSTR'])
                                          ->where('dh.compcode',session('compcode'));
                        })
                        ->get();

        $deldept = collect($delorddt)->unique('deldept');
        foreach ($deldept as $obj_d) {
            $obj_d->_20010042 = 0;
            $obj_d->_20010044 = 0;
        }

        foreach ($delorddt as $obj) {
            $product_obj = DB::table('material.product')
                    ->where('compcode','=', '9b')
                    // ->where('unit','=', $unit_)
                    ->where('itemcode','=', $obj->itemcode)
                    ->first();

            if(strtoupper($product_obj->groupcode) == "STOCK" || strtoupper($product_obj->groupcode) == "OTHERS" || strtoupper($product_obj->groupcode) == "CONSIGNMENT" ){
                $row_dept = DB::table('sysdb.department')
                    ->select('costcode')
                    ->where('compcode','=',session('compcode'))
                    ->where('deptcode','=',$obj->deldept)
                    ->first();
                //utk debit accountcode
                $row_cat = DB::table('material.category')
                    ->where('compcode','=',session('compcode'))
                    ->where('catcode','=',$product_obj->productcat)
                    ->first();

                $drcostcode = $row_dept->costcode;
                if(strtoupper($product_obj->groupcode) == "STOCK"){
                    $dracc = '20010042';
                }else{
                    $dracc = $row_cat->stockacct;
                }

                if(strtoupper($product_obj->groupcode) == "CONSIGNMENT"){
                    $dracc = $row_cat->ConsignAcct;
                }

            }else if(strtoupper($product_obj->groupcode) == "ASSET"){
                $facode = DB::table('finance.facode')
                    ->where('compcode','=', session('compcode'))
                    ->where('assetcode','=', $product_obj->productcat)
                    ->first();

                $drcostcode = $facode->glassetccode;
                $dracc = $facode->glasset;

            }else{
                throw new \Exception("Item at delorddt doesn't have groupcode at table product");
            }

            if(strtoupper($product_obj->groupcode) == "STOCK"){
                $source_ = 'IV';
            }else if(strtoupper($product_obj->groupcode) == "CONSIGNMENT"){
                $source_ = 'DO';
            }else{
                $source_ = 'DO';
            }

            //utk credit costcode dgn accountocde
            $row_sysparam = DB::table('sysdb.sysparam')
                ->select('pvalue1','pvalue2')
                ->where('compcode','=',session('compcode'))
                ->where('source','=','AP')
                ->where('trantype','=','ACC')
                ->first();

            $crcostcode = $drcostcode; //crcc sama dg drcc
            $cracc = $row_sysparam->pvalue2;

            if($obj->trantype == 'GRT'){
                $amount = -$obj->amount;
            }else{
                $amount = $obj->amount;
            }

            if($dracc == '20010042'){
                $_20010042 = $_20010042 + round($amount, 2);
                foreach ($deldept as $key_d => $obj_d) {
                    if($obj->deldept == $obj_d->deldept){
                        $obj_d->_20010042 = $obj_d->_20010042 + round($amount, 2);
                    }
                }
            }else if($dracc == '20010044'){
                $_20010044 = $_20010044 + round($amount, 2);
                foreach ($deldept as $key_d => $obj_d) {
                    if($obj->deldept == $obj_d->deldept){
                        $obj_d->_20010044 = $obj_d->_20010044 + round($amount, 2);
                    }
                }
            }

            $obj->newamt = round($amount, 2);
            $obj->drcostcode = $drcostcode;
            $obj->dracc = $dracc;
            $obj->crcostcode = $crcostcode;
            $obj->cracc = $cracc;
            $obj->source = $source_;
        }

        $deldept = collect($delorddt)->unique('deldept');
        $sheets[0] = new check_do_stockconsign('-',$deldept,'main',$_20010042,$_20010044);

        foreach ($deldept as $key => $obj_d) {
            $sheets[$key+1] = new check_do_stockconsign($obj_d->deldept,$delorddt,'sheet',$obj_d->_20010042,$obj_d->_20010044);
        }

        // DB::table('finance.gltran')
        //             ->insert([
        //                 'compcode' => '9b',
        //                 'adduser' => 'system_ar96',
        //                 'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
        //                 'auditno' => $obj->recno,
        //                 'lineno_' => $obj->lineno_,
        //                 'source' => $source_, //kalau stock 'IV', lain dari stock 'DO'
        //                 'trantype' => $obj->trantype,
        //                 'reference' => $obj->deldept .' '. str_pad($obj->docno,7,"0",STR_PAD_LEFT),
        //                 'description' => $obj->itemcode, 
        //                 'postdate' => $obj->postdate,
        //                 'year' => '2025',
        //                 'period' => $period,
        //                 'drcostcode' => $drcostcode,
        //                 'dracc' => $dracc,
        //                 'crcostcode' => $crcostcode,
        //                 'cracc' => $cracc,
        //                 'amount' => round($amount, 2),
        //                 'idno' => $obj->deldept .' '. $obj->docno
        //             ]);

        // $sheets[1] = new financialReportExport_bsnote($this->monthfrom,$this->monthto,$this->yearfrom,$this->yearto,$this->reporttype);

        return $sheets;
    }
    
}
