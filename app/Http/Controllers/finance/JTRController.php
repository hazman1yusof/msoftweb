<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;

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

        $dept = $request->dept;
        $year = $yearmonth[0];
        $month = $yearmonth[1];

        DB::beginTransaction();

        try {
            $request_no = $this->request_no('JTR',$dept);
            $recno = $this->recno('IV','IT');

            DB::table("material.ivtxnhd")
                        ->insert([
                            'compcode' => '9B',
                            'recno' => $recno,
                            'source' => 'IV',
                            // 'reference' => ,
                            'txndept' => $dept,
                            'trantype' => 'JTR',
                            'docno' => $request_no,
                            // 'srcdocno' => ,
                            // 'sndrcvtype' => ,
                            // 'sndrcv' => ,
                            'trandate' => Carbon::now("Asia/Kuala_Lumpur"),
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
                                              ->where('p.compcode','9B');
                            })
                            ->where('s.compcode','9B')
                            ->where('s.deptcode',$dept)
                            ->where('s.year',$year)
                            // ->where('s.itemcode','KW001303')
                            ->get();

            $x=0;
            foreach ($stockloc as $obj) {
                $array_obj = (array)$obj;

                $get_bal = $this->get_bal($array_obj,$month);
                // dump($get_bal);
                $variance = floatval($get_bal->variance);

                if($variance != 0){
                    $x = $x + 1;
                    DB::table('material.ivtxndt')
                            ->insert([
                                'compcode' => '9B', 
                                'recno' => $recno, 
                                'lineno_' => $x, 
                                'itemcode' => $obj->itemcode, 
                                'uomcode' => $obj->uomcode,
                                // 'uomcoderecv' => $value->uomcoderecv,  
                                'txnqty' => 0, 
                                'netprice' => $variance, 
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
                                // 'amount' => $value->amount, 
                                'trandate' => Carbon::now("Asia/Kuala_Lumpur"),
                                // 'sndrcv' => $ivtmphd->sndrcv,
                                'unit' => $dept,
                            ]);

                    $NetMvVal = $array_obj['netmvval'.$month] + $variance;
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

            // DB::commit();

        } catch (Exception $e) {
            DB::rollback();

            dd('Error'.$e);
        }
    }
}
