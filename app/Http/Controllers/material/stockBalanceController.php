<?php

namespace App\Http\Controllers\material;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use PDF;
use App\Exports\stockBalance_xlsExport;
use App\Exports\stockBalance_basic_xlsExport;
use App\Exports\stockSheet_xlsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class stockBalanceController extends defaultController
{   
    var $gltranAmount;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        return view('material.stockBalance.stockBalance');
    }

    public function report(Request $request)
    {   
        DB::enableQueryLog();
        switch($request->action){
            case 'stockBalance_pdf_basic':
                return $this->stockBalance_pdf_basic($request);
            case 'stockBalance_xls_basic':
                return $this->stockBalance_xls_basic($request);
            case 'stockBalance_pdf_ttype':
                return $this->stockBalance_pdf_ttype($request);
            case 'stockBalance_xls_ttype':
                return $this->stockBalance_xls_ttype($request);
            case 'stockSheet_pdf':
                return $this->stockSheet_pdf($request);
            case 'stockSheet_xls':
                return $this->stockSheet_xls($request);
            default:
                return 'error happen..';
        }
    }
    
    public function stockBalance_pdf_ttype(Request $request){
        $validator = Validator::make($request->all(), [
            'dept_to' => 'required',
            'item_to' => 'required',
            'year' => 'required',
            'period' => 'required',
        ]);

        if($validator->fails()){
            abort(404);
        }
        $unit_from = $request->unit_from;
        if(empty($unit_from)){
            $unit_from = '%';
        }
        $unit_to = $request->unit_to;

        $dept_from = $request->dept_from;
        if(empty($dept_from)){
            $dept_from = '%';
        }
        $dept_to = $request->dept_to;
        $item_from = $request->item_from;
        if(empty($item_from)){
            $item_from = '%';
        }
        $item_to = $request->item_to;
        $year = $request->year;
        $period = $request->period;

        // $deptcode = DB::table('material.stockloc as s')
        //                 ->select('s.deptcode','d.description')
        //                 ->join('sysdb.department as d', function($join){
        //                     $join = $join->on('d.deptcode', '=', 's.deptcode');
        //                     $join = $join->where('d.compcode', '=', session('compcode'));
        //                 })
        //                 ->where('s.compcode',session('compcode'))
        //                 // ->where('s.unit',session('unit'))
        //                 ->whereBetween('s.deptcode',[$dept_from,$dept_to.'%'])
        //                 ->where('s.year', '=', $year)
        //                 ->distinct('s.deptcode')
        //                 ->get('deptcode','description');
     
        $stockloc = DB::table('material.stockloc as s')
                        ->select('s.unit','p.description','s.idno','s.compcode','s.deptcode','s.itemcode','s.uomcode','s.bincode','s.rackno','s.year','s.openbalqty','s.openbalval','s.netmvqty1','s.netmvqty2','s.netmvqty3','s.netmvqty4','s.netmvqty5','s.netmvqty6','s.netmvqty7','s.netmvqty8','s.netmvqty9','s.netmvqty10','s.netmvqty11','s.netmvqty12','s.netmvval1','s.netmvval2','s.netmvval3','s.netmvval4','s.netmvval5','s.netmvval6','s.netmvval7','s.netmvval8','s.netmvval9','s.netmvval10','s.netmvval11','s.netmvval12','s.stocktxntype','s.disptype','s.qtyonhand','s.minqty','s.maxqty','s.reordlevel','s.reordqty','s.lastissdate','s.frozen','s.adduser','s.adddate','s.upduser','s.upddate','s.cntdocno','s.fix_uom','s.locavgcs','s.lstfrzdt','s.lstfrztm','s.frzqty','s.recstatus','s.deluser','s.deldate','s.computerid','s.ipaddress','s.lastcomputerid','s.lastipaddress','s.unit','d.description as dept_desc','sc.description as unit_desc')
                        ->join('material.product as p', function($join){
                                $join = $join->on('p.itemcode', '=', 's.itemcode');
                                $join = $join->on('p.uomcode', '=', 's.uomcode');
                                $join = $join->where('p.compcode', '=', session('compcode'));
                                $join = $join->on('p.unit', '=', 's.unit');
                            })
                        ->join('sysdb.department as d', function($join){
                            $join = $join->on('d.deptcode', '=', 's.deptcode');
                            // $join = $join->on('d.unit', '=', 's.unit');
                            $join = $join->where('d.compcode', '=', session('compcode'));
                        })
                        ->join('sysdb.sector as sc', function($join){
                            $join = $join->on('sc.sectorcode', '=', 's.unit');
                            // $join = $join->on('d.unit', '=', 's.unit');
                            $join = $join->where('sc.compcode', '=', session('compcode'));
                        })
                        ->where('s.compcode',session('compcode'))
                        ->whereBetween('s.unit',[$unit_from,$unit_to.'%'])
                        ->whereBetween('s.deptcode',[$dept_from,$dept_to.'%'])
                        ->whereBetween('s.itemcode',[$item_from,$item_to.'%'])
                        ->where('s.year', '=', $year)
                        ->orderBy('s.deptcode', 'ASC')
                        ->orderBy('s.itemcode', 'ASC')
                        ->get();

        foreach ($stockloc as $obj) {
            $array_obj = (array)$obj;


            $get_bal = $this->get_bal($array_obj,$period);
            $obj->open_balqty = $get_bal->open_balqty;
            $obj->open_balval = $get_bal->open_balval;
            $obj->close_balqty = $get_bal->close_balqty;
            $obj->close_balval = $get_bal->close_balval;

            $get_ivtxndt = $this->get_ivtxndt($obj,$period,$year);
            $obj->grn_qty = $get_ivtxndt->grn_qty;
            $obj->tr_qty = $get_ivtxndt->tr_qty;
            $obj->wof_qty = $get_ivtxndt->wof_qty;
            $obj->ai_qty = $get_ivtxndt->ai_qty;
            $obj->ao_qty = $get_ivtxndt->ao_qty;
            $obj->phy_qty = $get_ivtxndt->phy_qty;

            $get_ivdspdt = $this->get_ivdspdt($obj,$period,$year);
            $obj->ds_qty = $get_ivdspdt->ds_qty;

            $totmv = floatval($get_ivtxndt->grn_qty)-floatval($get_ivdspdt->ds_qty)-floatval($get_ivtxndt->tr_qty)+floatval($get_ivtxndt->wof_qty)+floatval($get_ivtxndt->ai_qty)-floatval($get_ivtxndt->ao_qty)+floatval($get_ivtxndt->phy_qty);
            $oth_qty = floatval($get_bal->close_balqty) - floatval($get_bal->open_balqty) - floatval($totmv);
            $obj->oth_qty = $oth_qty;

        }

        $unit = $stockloc->unique('unit');
        $deptcode = $stockloc->unique('deptcode');
        
        $company = DB::table('sysdb.company')
            ->where('compcode','=',session('compcode'))
            ->first();

        $header = new stdClass();
        $header->printby = session('username');
        $header->deptfrom = $request->dept_from;
        $header->deptto = $dept_to;
        $header->itemfrom = $request->item_from;
        $header->itemto = $item_to;
        $header->year = $year;
        $header->period = $period;
        if(empty($request->zero_delete)){
            $zero_delete = 1;
        }else{
            $zero_delete = 0;
        }

        return view('material.stockBalance.stockBalance_pdfmake',compact('stockloc','company','header','deptcode','unit'));
    }

    public function stockBalance_xls_ttype(Request $request){
        $validator = Validator::make($request->all(), [
            'dept_to' => 'required',
            'item_to' => 'required',
            'year' => 'required',
            'period' => 'required',
        ]);

        if($validator->fails()){
            abort(404);
        }

        $unit_from = $request->unit_from;
        $unit_to = $request->unit_to;
        $dept_from = $request->dept_from;
        $dept_to = $request->dept_to;
        $item_from = $request->item_from;
        $item_to = $request->item_to;
        $year = $request->year;
        $period = $request->period;
        if(empty($request->zero_delete)){
            $zero_delete = 1;
        }else{
            $zero_delete = 0;
        }

        return Excel::download(new stockBalance_xlsExport($unit_from,$unit_to,$dept_from,$dept_to,$item_from,$item_to,$year,$period,$zero_delete), 'Item List.xlsx');
    }

    public function stockBalance_xls_basic(Request $request){
        $validator = Validator::make($request->all(), [
            'dept_to' => 'required',
            'item_to' => 'required',
            'year' => 'required',
            'period' => 'required',
        ]);

        if($validator->fails()){
            abort(404);
        }

        $unit_from = $request->unit_from;
        $unit_to = $request->unit_to;
        $dept_from = $request->dept_from;
        $dept_to = $request->dept_to;
        $item_from = $request->item_from;
        $item_to = $request->item_to;
        $year = $request->year;
        $period = $request->period;
        if(empty($request->zero_delete)){
            $zero_delete = 1;
        }else{
            $zero_delete = 0;
        }

        return Excel::download(new stockBalance_basic_xlsExport($unit_from,$unit_to,$dept_from,$dept_to,$item_from,$item_to,$year,$period,$zero_delete), 'Item List.xlsx');
    }

    public function stockSheet_xls(Request $request){
        $validator = Validator::make($request->all(), [
            'dept_to' => 'required',
            'item_to' => 'required',
            'year' => 'required',
            'period' => 'required',
        ]);

        if($validator->fails()){
            abort(404);
        }

        $unit_from = $request->unit_from;
        $unit_to = $request->unit_to;
        $dept_from = $request->dept_from;
        $dept_to = $request->dept_to;
        $item_from = $request->item_from;
        $item_to = $request->item_to;
        $year = $request->year;
        $period = $request->period;
        if(empty($request->zero_delete)){
            $zero_delete = 1;
        }else{
            $zero_delete = 0;
        }
        
        return Excel::download(new stockSheet_xlsExport($unit_from,$unit_to,$dept_from,$dept_to,$item_from,$item_to,$year,$period,$zero_delete), 'stockSheet.xlsx');
    }

    public function stockSheet_pdf(Request $request){
        $validator = Validator::make($request->all(), [
            'dept_to' => 'required',
            'item_to' => 'required',
            'year' => 'required',
            'period' => 'required',
        ]);

        if($validator->fails()){
            abort(404);
        }

        $zero_delete = $request->zero_delete;
        $unit_from = $request->unit_from;
        if(empty($unit_from)){
            $unit_from = '%';
        }
        $unit_to = $request->unit_to;
        if(empty($unit_to)){
            $unit_to = '%';
        }
        $dept_from = $request->dept_from;
        if(empty($dept_from)){
            $dept_from = '%';
        }
        $dept_to = $request->dept_to;
        $item_from = $request->item_from;
        if(empty($item_from)){
            $item_from = '%';
        }
        $item_to = $request->item_to;
        $year = $request->year;
        $period = $request->period;
        
        $array_report = [];
        $break_loop = [];
        $isi_array = [];
        $loop = 0;

        // $deptcode = DB::table('material.stockloc as s')
        //                 ->select('s.deptcode','d.description')
        //                 ->join('sysdb.department as d', function($join){
        //                     $join = $join->on('d.deptcode', '=', 's.deptcode');
        //                     $join = $join->where('d.compcode', '=', session('compcode'));
        //                 })
        //                 ->where('s.compcode',session('compcode'))
        //                 // ->where('s.unit',session('unit'))
        //                 ->whereBetween('s.deptcode',[$dept_from,$dept_to.'%'])
        //                 ->where('s.year', '=', $year)
        //                 ->distinct('s.deptcode')
        //                 ->get('deptcode','description');

        $stockloc = DB::table('material.stockloc as s')
                        ->select('s.unit','p.description','s.idno','s.compcode','s.deptcode','s.itemcode','s.uomcode','s.bincode','s.rackno','s.year','s.openbalqty','s.openbalval','s.netmvqty1','s.netmvqty2','s.netmvqty3','s.netmvqty4','s.netmvqty5','s.netmvqty6','s.netmvqty7','s.netmvqty8','s.netmvqty9','s.netmvqty10','s.netmvqty11','s.netmvqty12','s.netmvval1','s.netmvval2','s.netmvval3','s.netmvval4','s.netmvval5','s.netmvval6','s.netmvval7','s.netmvval8','s.netmvval9','s.netmvval10','s.netmvval11','s.netmvval12','s.stocktxntype','s.disptype','s.qtyonhand','s.minqty','s.maxqty','s.reordlevel','s.reordqty','s.lastissdate','s.frozen','s.adduser','s.adddate','s.upduser','s.upddate','s.cntdocno','s.fix_uom','s.locavgcs','s.lstfrzdt','s.lstfrztm','s.frzqty','s.recstatus','s.deluser','s.deldate','s.computerid','s.ipaddress','s.lastcomputerid','s.lastipaddress','s.unit','d.description as dept_desc','sc.description as unit_desc')
                        ->join('material.product as p', function($join){
                                $join = $join->on('p.itemcode', '=', 's.itemcode');
                                // $join = $join->on('p.uomcode', '=', 's.uomcode');
                                $join = $join->where('p.recstatus', '=', 'ACTIVE');
                                $join = $join->where('p.compcode', '=', session('compcode'));
                                $join = $join->where('p.groupcode', '=', 'STOCK');
                                $join = $join->on('p.unit', '=', 's.unit');
                            })
                        ->leftjoin('sysdb.department as d', function($join){
                            $join = $join->on('d.deptcode', '=', 's.deptcode');
                            // $join = $join->on('d.unit', '=', 's.unit');
                            $join = $join->where('d.compcode', '=', session('compcode'));
                        })
                        ->leftjoin('sysdb.sector as sc', function($join){
                            $join = $join->on('sc.sectorcode', '=', 's.unit');
                            // $join = $join->on('d.unit', '=', 's.unit');
                            $join = $join->where('sc.compcode', '=', session('compcode'));
                        });
        $stockloc = $stockloc
                    ->where('s.compcode',session('compcode'))
                    ->where('s.stocktxntype','TR')
                    ->whereBetween('s.unit',[$unit_from,$unit_to.'%'])
                    ->whereBetween('s.deptcode',[$dept_from,$dept_to.'%'])
                    ->whereBetween('s.itemcode',[$item_from,$item_to.'%']);

        // if(strtolower($unit_from)=='khealth'){
        //     $stockloc = $stockloc->join('material.stockexp as se', function($join){
        //                     $join = $join->on('se.itemcode', '=', 's.itemcode');
        //                     $join = $join->on('se.deptcode', '=', 's.deptcode');
        //                     $join = $join->on('se.uomcode', '=', 's.uomcode');
        //                     $join = $join->where('se.compcode', '=', session('compcode'));
        //                     // $join = $join->where('se.unit', '=', session('unit'));
        //                     // $join = $join->on('se.year', '=', 's.year');
        //                 });
        // }

        $stockloc = $stockloc->where('s.compcode',session('compcode'))
                    ->where('s.year', '=', $year)
                    ->orderBy('s.deptcode', 'ASC')
                    ->orderBy('s.itemcode', 'ASC')
                    ->get();        

        $isi = 0;
        foreach ($stockloc as $obj) {
            $loop = $loop + 1;
            $isi = $isi + 1;
            $obj->unit = strtoupper($obj->unit);
            $obj->deptcode = strtoupper($obj->deptcode);

            $array_obj = (array)$obj;
            $get_bal = $this->get_bal($array_obj,$period);
            $obj->open_balqty = $get_bal->open_balqty;
            $obj->open_balval = $get_bal->open_balval;
            $obj->close_balqty = $get_bal->close_balqty;
            $obj->close_balval = $get_bal->close_balval;
            $obj->netmvqty = $get_bal->netmvqty;
            $obj->netmvval = $get_bal->netmvval;

            // $get_ivtxndt = $this->get_ivtxndt($obj,$period,$year);
            // $obj->grn_qty = $get_ivtxndt->grn_qty;
            // $obj->tr_qty = $get_ivtxndt->tr_qty;
            // $obj->wof_qty = $get_ivtxndt->wof_qty;
            // $obj->ai_qty = $get_ivtxndt->ai_qty;
            // $obj->ao_qty = $get_ivtxndt->ao_qty;
            // $obj->phy_qty = $get_ivtxndt->phy_qty;

            // $get_ivdspdt = $this->get_ivdspdt($obj,$period,$year);
            // $obj->ds_qty = $get_ivdspdt->ds_qty;

            // $totmv = floatval($get_ivtxndt->grn_qty)-floatval($get_ivdspdt->ds_qty)-floatval($get_ivtxndt->tr_qty)+floatval($get_ivtxndt->wof_qty)+floatval($get_ivtxndt->ai_qty)-floatval($get_ivtxndt->ao_qty)+floatval($get_ivtxndt->phy_qty);
            // $oth_qty = floatval($get_bal->close_balqty) - floatval($get_bal->open_balqty) - floatval($totmv);
            // $obj->oth_qty = $oth_qty;
            if($zero_delete == 1){
                if(empty((float)$obj->open_balqty) && empty((float)$obj->open_balval) && empty((float)$obj->close_balqty) && empty((float)$obj->close_balval) && empty((float)$obj->netmvqty) && empty((float)$obj->netmvval)){
                    continue;
                }else{
                    array_push($array_report, $obj);
                }
            }else{
                array_push($array_report, $obj);
            }
        }
        array_push($isi_array, $isi);
        $loop = $loop + 4;
        array_push($break_loop, $loop);

        $unit = $stockloc->unique('unit');
        // dump($unit);
        $deptcode = $stockloc->unique('deptcode');
        
        $company = DB::table('sysdb.company')
            ->where('compcode','=',session('compcode'))
            ->first();

        $header = new stdClass();
        $header->printby = session('username');
        $header->deptfrom = $request->dept_from;
        $header->deptto = $dept_to;
        $header->itemfrom = $request->item_from;
        $header->itemto = $item_to;
        $header->year = $year;
        $header->period = $period;

        return view('material.stockBalance.stockSheet_pdf_pdfmake',compact('stockloc','company','header','deptcode'));
    }
    
    public function get_bal($array_obj,$period){
        $open_balqty = $array_obj['openbalqty'];
        $close_balqty = $array_obj['openbalqty'];
        $open_balval = $array_obj['openbalval'];
        $close_balval = $array_obj['openbalval'];
        $until = intval($period) - 1;

        for ($from = 1; $from <= $until; $from++) { 
            $open_balqty = $open_balqty + $array_obj['netmvqty'.$from];
            $open_balval = round($open_balval, 2) + round($array_obj['netmvval'.$from], 2);
        }

        for ($from = 1; $from <= intval($period); $from++) { 
            $close_balqty = $close_balqty + $array_obj['netmvqty'.$from];
            $close_balval = round($close_balval, 2) + round($array_obj['netmvval'.$from], 2);
        }

        $responce = new stdClass();
        $responce->open_balqty = $open_balqty;
        $responce->open_balval = $open_balval;
        $responce->close_balqty = $close_balqty;
        $responce->close_balval = $close_balval;
        $responce->netmvqty = $array_obj['netmvqty'.$period];
        $responce->netmvval = round($array_obj['netmvval'.$period], 2);
        return $responce;
    }

    public function get_ivtxndt($obj,$period,$year){
        $grn_qty=0;
        $tr_qty=0;
        $wof_qty=0;
        $ai_qty=0;
        $ao_qty=0;
        $phy_qty=0;

        $ivtxndt = DB::table('material.ivtxndt')
                    ->where('compcode',session('compcode'))
                    ->where('itemcode',$obj->itemcode)
                    ->where('uomcode',$obj->uomcode)
                    ->where('deptcode',$obj->deptcode)
                    ->whereMonth('trandate', '=', $period)
                    ->whereYear('trandate', '=', $year);

        if($ivtxndt->exists()){
            foreach ($ivtxndt->get() as $obj) {
                switch ($obj->trantype) {
                    case 'GRN':
                        $grn_qty = $grn_qty + $obj->txnqty;
                        break;
                    case 'TR':
                        $tr_qty = $tr_qty + $obj->txnqty;
                        break;
                    case 'WOF':
                        $wof_qty = $wof_qty + $obj->txnqty;
                        break;
                    case 'AI':
                        $ai_qty = $ai_qty + $obj->txnqty;
                        break;
                    case 'AO':
                        $ao_qty = $ao_qty + $obj->txnqty;
                        break;
                    case 'PHYCNT':
                        $phy_qty = $phy_qty + $obj->txnqty;
                        break;
                }
            }
        }

        $ivtxndt_sndrcv = DB::table('material.ivtxndt')
                            ->where('compcode',session('compcode'))
                            ->where('itemcode',$obj->itemcode)
                            ->where('uomcoderecv',$obj->uomcode)
                            ->where('sndrcv',$obj->deptcode)
                            ->whereMonth('trandate', '=', $period)
                            ->whereYear('trandate', '=', $year);

        if($ivtxndt_sndrcv->exists()){
            foreach ($ivtxndt->get() as $obj) {
                switch ($obj->trantype) {
                    case 'TR':
                        $tr_qty = $tr_qty - $obj->txnqty;
                        break;
                }
            }
        }

        $responce = new stdClass();
        $responce->grn_qty = $grn_qty;
        $responce->tr_qty = $tr_qty;
        $responce->wof_qty = $wof_qty;
        $responce->ai_qty = $ai_qty;
        $responce->ao_qty = $ao_qty;
        $responce->phy_qty = $phy_qty;
        return $responce;
    }

    public function get_ivdspdt($obj,$period,$year){
        $ds_qty=0;

        $ivdspdt = DB::table('material.ivdspdt')
                    ->where('compcode',session('compcode'))
                    ->where('itemcode',$obj->itemcode)
                    ->where('uomcode',$obj->uomcode)
                    ->where('reqdept',$obj->deptcode)
                    ->whereMonth('trandate', '=', $period)
                    ->whereYear('trandate', '=', $year);

        if($ivdspdt->exists()){
            foreach ($ivdspdt->get() as $obj) {
                switch ($obj->trantype) {
                    case 'DS':
                        $ds_qty = $ds_qty + $obj->txnqty;
                        break;
                }
            }
        }

        $responce = new stdClass();
        $responce->ds_qty = $ds_qty;
        return $responce;
    }
}

