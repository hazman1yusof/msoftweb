<?php

namespace App\Http\Controllers\material;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use DB;
use Carbon\Carbon;
use App\Exports\ItemEnquiryExport;
use stdClass;
use Maatwebsite\Excel\Facades\Excel;

class ItemEnquiryController extends defaultController
{   

    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
        $this->duplicateCode = "Code";
    }

    public function show(Request $request)
    {   
        $lastperiod = DB::table('sysdb.period')
                        ->where('compcode',session('compcode'))
                        ->orderBy('idno','desc')
                        ->first();
        // $this->detailMovement($request);
        return view('material.itemInquiry.itemInquiry',compact('lastperiod'));
    }

    public function form(Request $request)
    {  
        switch($request->oper){
            case 'add':
                return $this->defaultAdd($request);
            case 'edit':
                return $this->defaultEdit($request);
            case 'del':
                return $this->defaultDel($request);
            case 'detailMovement':
                return $this->detailMovement($request);
            default:
                return 'error happen..';
        }
    }

    public function table(Request $request)
    {   
        switch($request->action){
            case 'dialogForm_SalesOrder':
                return $this->dialogForm_SalesOrder($request);
            case 'open_detail':
                return $this->open_detail($request);
            case 'print_excel':
                return $this->print_excel($request);
            case 'scheduler':
                return $this->scheduler($request);
            case 'scheduler1':
                return $this->scheduler1($request);
            case 'scheduler2':
                return $this->scheduler2($request);
            case 'scheduler3':
                return $this->scheduler3($request);
            default:
                return 'error happen..';
        }
    }

    public function detailMovement(Request $request){
        //yg ni yg keluar kot
        $det_mov_deptcode = DB::table('material.ivtxndt as d')
                ->select('d.adddate','d.trandate','d.trantype','d.deptcode','d.txnqty','d.recno','d.lineno_', 'd.upduser', 'd.updtime', 'h.docno', 'd.uomcoderecv', 'd.uomcode','d.adduser', 'd.netprice', 'd.amount', 'h.trantime','t.crdbfl', 't.description','d.sndrcv','d.upddate as sortdate')
                ->leftJoin('material.ivtxnhd as h', function($join){
                        $join = $join->on('h.recno', '=', 'd.recno')
                                     ->on('h.trantype', '=', 'd.trantype')
                                     ->on('h.txndept', '=', 'd.deptcode')
                                     ->where('h.compcode','=',session('compcode'));
                    })
                ->leftJoin('material.ivtxntype as t', function($join){
                        $join = $join->on('t.trantype', '=', 'd.trantype')
                                     ->where('t.compcode','=',session('compcode'));
                    })
                ->where('d.compcode','=',session('compcode'))
                ->where('d.itemcode','=',$request->itemcode)
                ->where('d.deptcode','=',$request->deptcode)
                ->where('d.uomcode','=',$request->uomcode)
                ->where('d.trandate','>=',$request->trandate_from)
                ->where('d.trandate','<=',$request->trandate_to)
                // ->where('d.amount','!=',0)
                ->orderBy('d.adddate', 'asc')
                // ->orderBy('h.trantime', 'desc')
                ->get();

        $det_mov_deptcode = $det_mov_deptcode->each(function ($item, $key) {
            if(empty($item->amount)){
                $item->amount = 0.00;
            }
            $item->det_mov = 'deptcode';
            $item->mrn = '-';
            $item->episno = '-';
        });

        //yg ni ivdspdt
        $det_mov_deptcode_ivdspdt = DB::table('material.ivdspdt as d')
            ->select('d.adddate','d.trandate','d.trantype','d.issdept as deptcode','d.txnqty', 'd.upduser','d.recno as d_recno','d.lineno_', 'd.updtime', 'd.recno as docno', 'd.uomcode', 'd.uomcode','d.adduser', 'd.netprice', 'd.amount', 'd.updtime as trantime','t.crdbfl', 't.description', 'd.mrn', 'd.episno','b.billno as recno','h.debtorcode as sndrcv','d.trandate as sortdate')
            ->leftJoin('material.ivtxntype as t', function($join){
                    $join = $join->on('t.trantype', '=', 'd.trantype')
                                 ->where('t.compcode','=',session('compcode'));
                })
            ->leftJoin('debtor.billsum as b', function($join){
                    $join = $join->where('b.source', 'PB')
                                 ->where('b.trantype', 'IN')
                                 ->on('b.auditno', 'd.recno')
                                 ->where('b.compcode','=',session('compcode'));
                })
            ->leftJoin('debtor.dbacthdr as h', function($join){
                    $join = $join->where('h.compcode','=',session('compcode'))
                                 ->where('h.source', 'PB')
                                 ->where('h.trantype', 'IN')
                                 ->on('h.auditno', 'b.billno');
                })
            ->where('d.compcode','=',session('compcode'))
            ->where('d.itemcode','=',$request->itemcode)
            ->where('d.issdept','=',$request->deptcode)
            ->where('d.uomcode','=',$request->uomcode)
            ->where('d.trandate','>=',$request->trandate_from)
            ->where('d.trandate','<=',$request->trandate_to)
            // ->where('d.amount','!=',0)
            ->orderBy('d.adddate', 'asc')
            // ->orderBy('d.updtime', 'desc')
            ->get();

        $det_mov_deptcode_ivdspdt = $det_mov_deptcode_ivdspdt->each(function ($item, $key) {
            if(empty($item->amount)){
                $item->amount = 0.00;
            }
            $item->det_mov = 'deptcode';
            $item->sortdate = $item->trandate.' '.$item->trantime;
            // $item->sndrcv = '-';
        });


        $merged = $det_mov_deptcode->merge($det_mov_deptcode_ivdspdt);
        

        //yg ni masuk kot
        $det_mov_sndrcv = DB::table('material.ivtxndt as d')
                ->select('d.adddate','d.trandate','d.trantype','d.deptcode','d.txnqty','d.lineno_','d.recno', 'd.upduser', 'd.updtime', 'h.docno', 'd.uomcoderecv', 'd.uomcode','d.adduser', 'd.netprice', 'd.amount', 'h.trantime','t.crdbfl', 't.description','d.sndrcv','d.upddate as sortdate')
                ->leftJoin('material.ivtxnhd as h', function($join){
                        $join = $join->on('h.recno', '=', 'd.recno')
                                     ->on('h.trantype', '=', 'd.trantype')
                                     ->on('h.txndept', '=', 'd.deptcode')
                                     ->where('h.compcode','=',session('compcode'));
                    })
                ->leftJoin('material.ivtxntype as t', function($join){
                        $join = $join->on('t.trantype', '=', 'd.trantype')
                                     ->where('t.compcode','=',session('compcode'));
                    })
                ->where('d.compcode','=',session('compcode'))
                ->where('d.itemcode','=',$request->itemcode)
                ->where('d.sndrcv','=',$request->deptcode)
                ->where('d.uomcoderecv','=',$request->uomcode)
                ->where('d.trandate','>=',$request->trandate_from)
                ->where('d.trandate','<=',$request->trandate_to)
                // ->where('d.amount','!=',0)
                ->orderBy('d.trandate', 'asc')
                // ->orderBy('h.trantime', 'desc')
                ->get();

        $det_mov_sndrcv = $det_mov_sndrcv->each(function ($item, $key) {
            if(empty($item->amount)){
                $item->amount = 0.00;
            }
            $item->det_mov = 'sndrcv';
            $item->mrn = '-';
            $item->episno = '-';
            if($item->uomcode != $item->uomcoderecv){

                //1. amik convfactor
                $convfactor_obj = DB::table('material.uom')
                    ->select('convfactor')
                    ->where('uomcode','=',$item->uomcoderecv)
                    ->where('compcode','=',session('compcode'))
                    ->first();
                $convfactor_uomcoderecv = $convfactor_obj->convfactor;

                $convfactor_obj = DB::table('material.uom')
                    ->select('convfactor')
                    ->where('uomcode','=',$item->uomcode)
                    ->where('compcode','=',session('compcode'))
                    ->first();
                $convfactor_uomcodetrdept = $convfactor_obj->convfactor;

                //2. tukar txnqty dgn netprice berdasarkan convfactor
                $txnqty = $item->txnqty * ($convfactor_uomcodetrdept / $convfactor_uomcoderecv);
                $item->txnqty = $txnqty;
            }
        });

        $merged = $merged->merge($det_mov_sndrcv);
        // $merged = $merged->sortBy(function($col){
        //                 return $col;
        //             })->values()->all();

        $merged = $merged->sortBy('sortdate')->values()->all();

        $responce = new stdClass();
        $responce->rows = $merged;
        // dd($merged);

        return json_encode($responce);
    }

    public function print_excel(Request $request){

        return Excel::download(new ItemEnquiryExport($request->itemcode,$request->deptcode,$request->uomcode,$request->trandate_from,$request->trandate_to), 'ItemEnquiryExport.xlsx');
    }

    public function dialogForm_SalesOrder(Request $request){
        $billsum = DB::table('debtor.billsum as bs')
                        ->where('bs.compcode',session('compcode'))
                        ->where('bs.source','PB')
                        ->where('bs.trantype','IN')
                        ->where('bs.auditno',$request->docno);

        $billno = $billsum->first()->billno;

        $dbacthdr = DB::table('debtor.dbacthdr as db')
                        ->where('db.compcode',session('compcode'))
                        ->where('db.source','PB')
                        ->where('db.trantype','IN')
                        ->where('db.auditno',$billno);

        $billsum_array = DB::table('debtor.billsum as bs')
                        ->select('bs.idno','bs.compcode','bs.source','bs.trantype','bs.auditno','bs.quantity','bs.amount','bs.outamt','bs.taxamt','bs.totamt','bs.mrn','bs.episno','bs.paymode','bs.cardno','bs.debtortype','bs.debtorcode','bs.invno','bs.billno','bs.lineno_','bs.rowno','bs.billtype','bs.chgclass','bs.classlevel','bs.chggroup','bs.lastuser','bs.lastupdate','bs.invcode','bs.seqno','bs.discamt','bs.docref','bs.uom','bs.uom_recv','bs.recstatus','bs.unitprice','bs.taxcode','bs.billtypeperct','bs.billtypeamt','bs.totamount','bs.qtyonhand','cm.description')
                        ->leftJoin('hisdb.chgmast as cm', function($join) use ($request){
                            $join = $join->on('cm.chgcode', '=', 'bs.chggroup')
                                         ->on('cm.uom', '=', 'bs.uom')
                                         ->where('cm.compcode','=',session('compcode'));
                        })
                        ->where('bs.compcode',session('compcode'))
                        ->where('bs.source','PB')
                        ->where('bs.trantype','IN')
                        ->where('bs.billno',$billno);

        $responce = new stdClass();
        $responce->dbacthdr = $dbacthdr->first();
        $responce->billsum_array = $billsum_array->get();

        return json_encode($responce);
    }

    public function open_detail(Request $request){
        $trantype = $request->trantype;
        $recno = $request->recno;

        switch ($trantype) {
            case 'PHY':
                $header = DB::table('material.phycnthd')
                                ->where('compcode',session('compcode'))
                                ->where('recno',$recno)
                                ->first();

                return view('material.stockCount.stockCount_dtl',compact('header'));
            case 'GRT':
                return view('material.goodReturn.goodReturn_dtl',compact('trantype','recno'));
            case 'GRN':
                return view('material.deliveryOrder.deliveryOrder_dtl',compact('trantype','recno'));
            case 'DS':
                return view('finance.SalesOrder.SalesOrder_dtl',compact('trantype','recno'));
            default:
                return view('finance.inventoryTransaction.inventoryTransaction_dtl',compact('trantype','recno'));
        }
    }

    public function scheduler(Request $request){
        $purdept = DB::table('sysdb.department')
                        ->select('deptcode')
                        ->where('compcode',session('compcode'))
                        ->where('purdept',1)
                        ->get();

        return view('material.itemInquiry.scheduler',compact('purdept'));
    }

    public function scheduler1(Request $request){
        DB::beginTransaction();

        try {

            $deptcode=$request->deptcode;
            if(empty($deptcode)){
                dd('no deptcode');
            }
            $year=intval($request->year);
            if(empty($year)){
                dd('no year');
            }

            //betulkan stockloc
            for ($i = 1; $i <= 12; $i++) {
                $period = $i;
                $day_start = Carbon::createFromFormat('Y-m-d',$year.'-'.$period.'-01')->startOfMonth()->format('Y-m-d');
                $day_end = Carbon::createFromFormat('Y-m-d',$year.'-'.$period.'-01')->endOfMonth()->format('Y-m-d');

                $stockloc = DB::table('material.stockloc as s')
                            ->select('s.compcode','s.deptcode','s.itemcode','s.uomcode','s.bincode','s.rackno','s.year','s.openbalqty','s.openbalval','s.netmvqty1','s.netmvqty2','s.netmvqty3','s.netmvqty4','s.netmvqty5','s.netmvqty6','s.netmvqty7','s.netmvqty8','s.netmvqty9','s.netmvqty10','s.netmvqty11','s.netmvqty12','s.netmvval1','s.netmvval2','s.netmvval3','s.netmvval4','s.netmvval5','s.netmvval6','s.netmvval7','s.netmvval8','s.netmvval9','s.netmvval10','s.netmvval11','s.netmvval12','s.stocktxntype','s.disptype','s.qtyonhand','s.minqty','s.maxqty','s.reordlevel','s.reordqty','s.lastissdate','s.frozen','s.adduser','s.adddate','s.upduser','s.upddate','s.cntdocno','s.fix_uom','s.locavgcs','s.lstfrzdt','s.lstfrztm','s.frzqty','s.recstatus','s.deluser','s.deldate','s.computerid','s.ipaddress','s.lastcomputerid','s.lastipaddress','s.unit')
                            ->where('s.compcode',session('compcode'))
                            // ->where('itemcode',$itemcode)
                            ->join('material.product as p', function($join) use ($request){
                                $join = $join->on('p.itemcode', '=', 's.itemcode')
                                                ->on('p.uomcode','=','s.uomcode')
                                                ->where('p.recstatus','=','ACTIVE')
                                                ->where('p.compcode',session('compcode'));
                            })
                            ->where('s.deptcode',$deptcode)
                            ->where('s.year',$year)
                            ->orderBy('s.idno', 'DESC')
                            ->get();

                $x = 1;
                foreach ($stockloc as $key => $value) {
                    $value_array = (array)$value;

                    // $product = DB::table('material.product')
                    //                 ->where('compcode','9B')
                    //                 ->where('itemcode',$obj->itemcode)
                    //                 ->where('uomcode',$obj->uomcode)
                    //                 ->first();

                    $ivdspdt = DB::table('material.ivdspdt')
                                ->where('compcode',session('compcode'))
                                ->where('itemcode',$value->itemcode)
                                ->where('uomcode',$value->uomcode)
                                ->where('issdept',$value->deptcode)
                                ->where('trandate','>=',$day_start)
                                ->where('trandate','<=',$day_end)
                                ->sum('txnqty');
                    $minus = $ivdspdt;

                    $ivtxndt = DB::table('material.ivtxndt')
                                ->where('compcode',session('compcode'))
                                ->where('itemcode',$value->itemcode)
                                ->where('uomcode',$value->uomcode)
                                ->where('deptcode',$value->deptcode)
                                ->where('trandate','>=',$day_start)
                                ->where('trandate','<=',$day_end)
                                ->get();

                    $add = 0;
                    $add2 = 0;
                    foreach ($ivtxndt as $key => $value) {
                        $ivtxntype = DB::table('material.ivtxntype')
                                            ->where('compcode',session('compcode'))
                                            ->where('trantype',$value->trantype)
                                            ->first();

                        $crdbfl = $ivtxntype->crdbfl;

                        if(strtoupper($crdbfl) == 'IN'){
                            $add = $add + $value->txnqty;
                            $add2 = $add2 + $value->amount;
                        }else{
                            $add = $add - $value->txnqty;
                            $add2 = $add2 - $value->amount;
                        }
                    }

                    $all = $add - $minus;

                    $ivdspdt2 = DB::table('material.ivdspdt')
                                ->where('compcode',session('compcode'))
                                ->where('itemcode',$value->itemcode)
                                ->where('uomcode',$value->uomcode)
                                ->where('trandate','>=',$day_start)
                                ->where('trandate','<=',$day_end)
                                ->sum('amount');
                    $minus2 = $ivdspdt2;

                    $all2 = $add2 - $minus2;
                    $all2 = round($all2,2);

                    if(!$this->floatEquals($value_array['netmvqty'.$period], $all) || !$this->floatEquals($value_array['netmvval'.$period], $all2)){

                        dump($x.'. '.$value->itemcode.' -> SAVED netmvqty'.$period.' => '.$value_array['netmvqty'.$period].' -> SAVED netmvval'.$period.' => '.$value_array['netmvval'.$period] );
                        dump($x.'. '.$value->itemcode.' -> REAL netmvqty'.$period.' => '.$all.' -> REAL netmvval => '.$all2);
                        $x++;

                        if(intval($period) > 5){
                            $updarr = [
                                    'netmvqty'.$period => $all,
                                    'netmvval'.$period => $all2
                                ];
                        }else{
                            $updarr = [
                                    'netmvqty'.$period => $all,
                                    // 'netmvval'.$period => $all2
                                ];
                        }

                        DB::table('material.stockloc')
                                ->where('compcode',session('compcode'))
                                ->where('itemcode',$value->itemcode)
                                ->where('deptcode',$value->deptcode)
                                ->where('year',$year)
                                ->update($updarr);
                    }

                    // dump($value_array['netmvqty'.$period] != $all);
                    // dump('netmvval'.$period.' => '.$all2);
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }  
    }

    public function scheduler2(Request $request){
        DB::beginTransaction();

        try {

            $deptcode=$request->deptcode;
            if(empty($deptcode)){
                dd('no deptcode');
            }
            $year=intval($request->year);
            if(empty($year)){
                dd('no year');
            }

            $stockloc = DB::table('material.stockloc as s')
                        ->select('s.compcode','s.deptcode','s.itemcode','s.uomcode','s.bincode','s.rackno','s.year','s.openbalqty','s.openbalval','s.netmvqty1','s.netmvqty2','s.netmvqty3','s.netmvqty4','s.netmvqty5','s.netmvqty6','s.netmvqty7','s.netmvqty8','s.netmvqty9','s.netmvqty10','s.netmvqty11','s.netmvqty12','s.netmvval1','s.netmvval2','s.netmvval3','s.netmvval4','s.netmvval5','s.netmvval6','s.netmvval7','s.netmvval8','s.netmvval9','s.netmvval10','s.netmvval11','s.netmvval12','s.stocktxntype','s.disptype','s.qtyonhand','s.minqty','s.maxqty','s.reordlevel','s.reordqty','s.lastissdate','s.frozen','s.adduser','s.adddate','s.upduser','s.upddate','s.cntdocno','s.fix_uom','s.locavgcs','s.lstfrzdt','s.lstfrztm','s.frzqty','s.recstatus','s.deluser','s.deldate','s.computerid','s.ipaddress','s.lastcomputerid','s.lastipaddress','s.unit')
                        ->where('s.compcode',session('compcode'))
                        // ->where('itemcode',$itemcode)
                        ->join('material.product as p', function($join){
                            $join = $join->on('p.itemcode', '=', 's.itemcode')
                                            ->on('p.uomcode','=','s.uomcode')
                                            ->where('p.recstatus','=','ACTIVE')
                                            ->where('p.compcode',session('compcode'));
                        })
                        // ->where('s.itemcode','KW-NIMENRIX')
                        ->where('s.deptcode',$deptcode)
                        ->where('s.year',$year)
                        ->orderBy('s.idno', 'DESC')
                        ->get();

            //betulkan qtyonhand
            $x=1;
            foreach ($stockloc as $obj) {
                $qtyonhand = $obj->qtyonhand;
                $real_qtyonhand = $obj->openbalqty + $obj->netmvqty1 + $obj->netmvqty2 + $obj->netmvqty3 + $obj->netmvqty4 + $obj->netmvqty5 + $obj->netmvqty6 + $obj->netmvqty7 + $obj->netmvqty8 + $obj->netmvqty9 + $obj->netmvqty10 + $obj->netmvqty11 + $obj->netmvqty12;
                if($qtyonhand != $real_qtyonhand){
                    dump($x.'. '.$obj->itemcode.' => '.$qtyonhand.' vs Real '.$real_qtyonhand);
                    $x++;

                    DB::table('material.stockloc')
                            ->where('compcode',session('compcode'))
                            ->where('itemcode',$obj->itemcode)
                            ->where('uomcode',$obj->uomcode)
                            ->where('deptcode',$deptcode)
                            ->where('year',$year)
                            ->update([
                                'qtyonhand' => $real_qtyonhand
                            ]);

                    DB::table('material.product')
                            ->where('compcode',session('compcode'))
                            ->where('itemcode',$obj->itemcode)
                            ->where('uomcode',$obj->uomcode)
                            ->update([
                                'qtyonhand' => $real_qtyonhand
                            ]);
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }  
    }

    public function scheduler3(Request $request){
        DB::beginTransaction();

        try {

            $deptcode=$request->deptcode;
            if(empty($deptcode)){
                dd('no deptcode');
            }
            $year=intval($request->year);
            if(empty($year)){
                dd('no year');
            }

            $stockloc = DB::table('material.stockloc as s')
                        ->select('s.compcode','s.deptcode','s.itemcode','s.uomcode','s.bincode','s.rackno','s.year','s.openbalqty','s.openbalval','s.netmvqty1','s.netmvqty2','s.netmvqty3','s.netmvqty4','s.netmvqty5','s.netmvqty6','s.netmvqty7','s.netmvqty8','s.netmvqty9','s.netmvqty10','s.netmvqty11','s.netmvqty12','s.netmvval1','s.netmvval2','s.netmvval3','s.netmvval4','s.netmvval5','s.netmvval6','s.netmvval7','s.netmvval8','s.netmvval9','s.netmvval10','s.netmvval11','s.netmvval12','s.stocktxntype','s.disptype','s.qtyonhand','s.minqty','s.maxqty','s.reordlevel','s.reordqty','s.lastissdate','s.frozen','s.adduser','s.adddate','s.upduser','s.upddate','s.cntdocno','s.fix_uom','s.locavgcs','s.lstfrzdt','s.lstfrztm','s.frzqty','s.recstatus','s.deluser','s.deldate','s.computerid','s.ipaddress','s.lastcomputerid','s.lastipaddress','s.unit','e.idno as e_idno')
                        ->where('s.compcode',session('compcode'))
                        // ->where('itemcode',$itemcode)
                        ->join('material.product as p', function($join){
                            $join = $join->on('p.itemcode', '=', 's.itemcode')
                                            ->on('p.uomcode','=','s.uomcode')
                                            ->where('p.recstatus','=','ACTIVE')
                                            ->where('p.compcode',session('compcode'));
                        })
                        ->leftJoin('material.stockexp as e', function($join){
                            $join = $join->on('e.itemcode', '=', 's.itemcode')
                                            ->on('e.uomcode','=','s.uomcode');
                        })
                        // ->where('s.itemcode','KW-NIMENRIX')
                        ->where('s.deptcode',$deptcode)
                        ->where('s.year',$year)
                        ->orderBy('s.idno', 'DESC')
                        ->get();

            //betulkan stockexp
            $x = 1;
            foreach ($stockloc as $obj) {

                if(!empty($obj->e_idno)){
                    $stockexp = DB::table('material.stockexp')
                                ->where('itemcode',$obj->itemcode)
                                ->where('compcode',session('compcode'))
                                ->where('deptcode',$deptcode);

                    $balqty = $stockexp->sum('balqty');
                    $qtyonhand = $obj->qtyonhand;

                    if($balqty != $qtyonhand){
                        dump($x.'. '.$obj->itemcode.' -> stockexp sum: '.$balqty.' ----- stockloc: '.$qtyonhand);
                        $x++;

                        //1.
                        if($qtyonhand>$balqty){
                            $var = $qtyonhand - $balqty;

                            $stockexp_chg = DB::table('material.stockexp')
                                                ->where('compcode','9B')
                                                ->where('itemcode',$obj->itemcode)
                                                ->where('deptcode',$deptcode)
                                                ->orderBy('idno','desc')
                                                ->first();

                            DB::table('material.stockexp')
                                        ->where('idno',$stockexp_chg->idno)
                                        ->where('compcode','9B')
                                        ->where('itemcode',$obj->itemcode)
                                        ->update([
                                            'balqty' => $stockexp_chg->balqty + $var
                                        ]);

                            $chg = $stockexp_chg->balqty + $var;
                            dump('change stockexp '.$obj->itemcode.' to '.$chg);

                        }else if($qtyonhand<$balqty){
                            $stockexp_chg = DB::table('material.stockexp')
                                                ->where('compcode','9B')
                                                ->where('itemcode',$obj->itemcode)
                                                ->where('deptcode',$deptcode)
                                                ->orderBy('idno','desc')
                                                ->get();

                            $baki = $qtyonhand;
                            $zerorise = 0;
                            foreach ($stockexp_chg as $obj) {
                                $baki = $baki - $obj->balqty;
                                if($zerorise == 1){
                                    DB::table('material.stockexp')
                                        ->where('idno',$obj->idno)
                                        ->where('compcode','9B')
                                        ->where('itemcode',$obj->itemcode)
                                        ->update([
                                            'balqty' => 0
                                        ]);
                                    dump('change stockexp '.$obj->itemcode.' to 0');
                                }else{
                                    if($baki == 0){
                                        $zerorise = 1;
                                        // DB::table('material.stockexp')
                                        //     ->where('idno',$obj->idno)
                                        //     ->where('compcode','9B')
                                        //     ->where('itemcode',$itemcode)
                                        //     ->update([
                                        //         'balqty' => 0
                                        //     ]);

                                        // continue;
                                    }else if($baki > 0){
                                        // DB::table('material.stockexp')
                                        //     ->where('idno',$obj->idno)
                                        //     ->where('compcode','9B')
                                        //     ->where('itemcode',$itemcode)
                                        //     ->update([
                                        //         'balqty' => 0
                                        //     ]);
                                    }else if($baki < 0){
                                        DB::table('material.stockexp')
                                            ->where('idno',$obj->idno)
                                            ->where('compcode','9B')
                                            ->where('itemcode',$obj->itemcode)
                                            ->update([
                                                'balqty' => $baki + $obj->balqty
                                            ]);
                                        $chg = $baki + $obj->balqty;
                                        dump('change stockexp '.$obj->itemcode.' to '.$chg);
                                        $zerorise = 1;
                                    }
                                }
                            }
                        }
                    }
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            report($e);

            dd('Error'.$e);
        }  
    }
}