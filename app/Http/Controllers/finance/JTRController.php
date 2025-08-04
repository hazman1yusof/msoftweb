<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;

class JTRController extends defaultController
{   

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        return view('finance.CM.jtr.jtr');
    }

    public function table(Request $request)
    {   
        switch($request->action){
            default:
                return 'error happen..';
        }
    }

    public function form(Request $request)
    {   
        switch($request->oper){
            case 'posted':
                return $this->posted($request);
            case 'cancel':
                return $this->cancel($request);
            default:
                return 'error happen..';
        }
    }

    public function posted(Request $request){
        $yearmonth = explode('-', $request->yearmonth);

        $dept = $request->deptcode;

        if($dept == 'IMP'){
            $dept='IMP';
            $unit='IMP';
        }else if($dept == 'FKWSTR'){
            $dept='FKWSTR';
            $unit="W'HOUSE";
        }else if($dept == 'KHEALTH'){
            $dept='KHEALTH';
            $unit='KHEALTH';
        }else{
            dd('wrong dept');
        }

        $year = $yearmonth[0];
        $month = intval($yearmonth[1]);

        DB::beginTransaction();

        try {
            $request_no = $this->request_no('JTR',$dept);
            $recno = $this->recno('IV','IT');

            DB::table("material.ivtxnhd")
                        ->insert([
                            'compcode' => session('compcode'),
                            'recno' => $recno,
                            'source' => 'IV',
                            // 'reference' => ,
                            'txndept' => $dept,
                            'trantype' => 'JTR',
                            'docno' => $request_no,
                            // 'srcdocno' => ,
                            // 'sndrcvtype' => ,
                            // 'sndrcv' => ,
                            'trandate' => $year.'-'.$month.'-31',
                            // 'datesupret' => ,
                            // 'dateactret' => ,
                            'trantime' => Carbon::now("Asia/Kuala_Lumpur"),
                            // 'ivreqno' => ,
                            // 'amount' => ,
                            // 'respersonid' => ,
                            // 'remarks' => ,
                            'recstatus' => 'POSTED',
                            'adduser' => 'system',
                            'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                            // 'upduser' => ,
                            // 'upddate' => ,
                            // 'updtime' => ,
                            // 'postedby' => 'system',
                            // 'postdate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'unit' => $unit,
                            // 'requestby' => ,
                            // 'requestdate' => ,
                            // 'supportby' => ,
                            // 'supportdate' => ,
                            // 'support_remark' => ,
                            // 'verifiedby' => ,
                            // 'verifieddate' => ,
                            // 'verified_remark' => ,
                            // 'approvedby' => ,
                            // 'approveddate' => ,
                            // 'approved_remark' => ,
                            // 'cancelby' => ,
                            // 'canceldate' => ,
                            // 'cancelled_remark' => ,
                        ]);

            $stockloc = DB::table('material.stockloc as s')
                            ->select('s.idno','s.compcode','s.deptcode','s.itemcode','s.uomcode','s.bincode','s.rackno','s.year','s.openbalqty','s.openbalval','s.netmvqty1','s.netmvqty2','s.netmvqty3','s.netmvqty4','s.netmvqty5','s.netmvqty6','s.netmvqty7','s.netmvqty8','s.netmvqty9','s.netmvqty10','s.netmvqty11','s.netmvqty12','s.netmvval1','s.netmvval2','s.netmvval3','s.netmvval4','s.netmvval5','s.netmvval6','s.netmvval7','s.netmvval8','s.netmvval9','s.netmvval10','s.netmvval11','s.netmvval12','s.stocktxntype','s.disptype','s.qtyonhand','s.minqty','s.maxqty','s.reordlevel','s.reordqty','s.lastissdate','s.frozen','s.adduser','s.adddate','s.upduser','s.upddate','s.cntdocno','s.fix_uom','s.locavgcs','s.lstfrzdt','s.lstfrztm','s.frzqty','s.recstatus','s.deluser','s.deldate','s.computerid','s.ipaddress','s.lastcomputerid','s.lastipaddress','s.unit','p.avgcost')
                            ->join('material.product as p', function($join){
                                $join = $join->on('p.itemcode', '=', 's.itemcode')
                                              ->where('p.avgcost','!=',0)
                                              ->where('p.compcode',session('compcode'));
                            })
                            ->where('s.compcode',session('compcode'))
                            ->where('s.deptcode',$dept)
                            ->where('s.year',$year)
                            // ->where('s.itemcode','KW000158')
                            ->get();

            $x=0;
            foreach ($stockloc as $obj) {
                $array_obj = (array)$obj;

                $get_bal = $this->get_bal($array_obj,$month);
                // dd($get_bal);
                $variance = floatval($get_bal->variance);

                if($variance != 0){
                    $x = $x + 1;
                    DB::table('material.ivtxndt')
                            ->insert([
                                'compcode' => session('compcode'), 
                                'recno' => $recno, 
                                'lineno_' => $x, 
                                'itemcode' => $obj->itemcode, 
                                'uomcode' => $obj->uomcode,
                                // 'uomcoderecv' => $value->uomcoderecv,  
                                'txnqty' => 0, 
                                'netprice' => round($variance, 2), 
                                'adduser' => 'system', 
                                'adddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                                // 'upduser' => $value->upduser, 
                                // 'upddate' => $value->upddate, 
                                'TranType' => 'JTR',
                                'deptcode'  => $dept,
                                // 'productcat' => $productcat, 
                                // 'draccno' => $draccno, 
                                // 'drccode' => $drccode, 
                                // 'craccno' => $craccno, 
                                // 'crccode' => $crccode, 
                                // 'expdate' => $value->expdate, 
                                // 'qtyonhand' => $value->qtyonhand,
                                // 'qtyonhandrecv' => $value->qtyonhandrecv,  
                                // 'batchno' => $value->batchno, 
                                'amount' => round($variance, 2), 
                                'trandate' => $year.'-'.$month.'-31',
                                // 'sndrcv' => $ivtmphd->sndrcv,
                                'unit' => $dept,
                            ]);

                    $NetMvVal = $array_obj['netmvval'.$month] - $variance;//crdbfl out

                    DB::table('material.StockLoc')
                                    // ->where('StockLoc.unit','=',$unit_)
                                    ->where('StockLoc.CompCode','=',session('compcode'))
                                    ->where('StockLoc.idno','=',$obj->idno)
                                    // ->where('StockLoc.DeptCode','=',$value->srcdept)
                                    // ->where('StockLoc.ItemCode','=',$value->itemcode)
                                    // ->where('StockLoc.Year','=', defaultController::toYear($phycntdate))
                                    // ->where('StockLoc.UomCode','=',$value->uomcode)
                                    ->update([
                                        // 'QtyOnHand' => $QtyOnHand,
                                        // 'NetMvQty'.$month => $NetMvQty, 
                                        'NetMvVal'.$month => $NetMvVal
                                    ]);
                }
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();

            dd('Error'.$e);
        }
    }

    public function cancel(Request $request){
        $recno = $request->recno;

        DB::beginTransaction();

        try {

            $ivtxnhd =  DB::table("material.ivtxnhd")
                            ->where('compcode',session('compcode'))
                            ->where('recno',$recno)
                            ->where('source','IV')
                            ->where('trantype','JTR')
                            ->first();

            $trandate = $ivtxnhd->trandate;
            $yearmonth = explode('-', $trandate);
            $year = $yearmonth[0];
            $month = intval($yearmonth[1]);
            $dept = $ivtxnhd->txndept;

            $ivtxndt = DB::table('material.ivtxndt')
                        ->where('compcode',session('compcode'))
                        ->where('recno',$recno)
                        ->where('TranType','JTR')
                        ->get();

            foreach ($ivtxndt as $obj) {
                $stockloc = DB::table('material.stockloc')
                            ->where('compcode','9B')
                            ->where('deptcode',$dept)
                            ->where('itemcode',$obj->itemcode)
                            ->where('uomcode',$obj->uomcode)
                            ->where('year',$year)
                            ->get();

                $stockloc = (array)$stockloc[0];

                $variance = $obj->netprice;
                $NetMvVal = $stockloc['netmvval'.$month] + $variance;
                DB::table('material.stockloc')
                            ->where('compcode','9B')
                            ->where('deptcode',$dept)
                            ->where('itemcode',$obj->itemcode)
                            ->where('uomcode',$obj->uomcode)
                            ->where('year',$year)
                            ->update([
                                'NetMvVal'.$month => $NetMvVal
                            ]);
            }

            DB::table("material.ivtxnhd")
                    ->where('compcode',session('compcode'))
                    ->where('recno',$recno)
                    ->where('source','IV')
                    ->where('trantype','JTR')
                    ->update([
                        'recstatus' => 'CANCELLED',
                        'compcode' => 'CC'
                    ]);

             DB::table('material.ivtxndt')
                    ->where('compcode',session('compcode'))
                    ->where('recno',$recno)
                    ->where('TranType','JTR')
                    ->update([
                        'recstatus' => 'CANCELLED',
                        'compcode' => 'CC'
                    ]);

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();

            dd('Error'.$e);
        }
    }

    public function get_bal($array_obj,$period){
        $open_balqty = $array_obj['openbalqty'];
        $close_balqty = $array_obj['openbalqty'];
        $open_balval = $array_obj['openbalval'];
        $close_balval = $array_obj['openbalval'];
        $until = intval($period) - 1;

        for ($from = 1; $from <= $until; $from++) { 
            $open_balqty = $open_balqty + $array_obj['netmvqty'.$from];
            $open_balval = $open_balval + $array_obj['netmvval'.$from];
        }

        for ($from = 1; $from <= intval($period); $from++) { 
            $close_balqty = $close_balqty + $array_obj['netmvqty'.$from];
            $close_balval = $close_balval + $array_obj['netmvval'.$from];
        }

        $actual_balval = $array_obj['avgcost'] * $close_balqty;

        $responce = new stdClass();
        $responce->open_balqty = $open_balqty;
        $responce->open_balval = $open_balval;
        $responce->close_balqty = $close_balqty;
        $responce->close_balval = $close_balval;
        $responce->avgcost = $array_obj['avgcost'];
        $responce->actua_balval = $actual_balval;
        $responce->variance =  $close_balval - $actual_balval;
        return $responce;
    }
}
