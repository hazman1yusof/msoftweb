<?php

namespace App\Http\Controllers\material;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class PurchaseOrderDetailController extends defaultController
{   
    var $gltranAmount;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function form(Request $request)
    {   
        switch($request->oper){
            case 'add':
                // dd('asd');
                return $this->add($request);
            case 'edit':
                return $this->edit($request);

            case 'edit_all':

               /* if($purordhd->purordno != 0){
                    // return 'edit all srcdocno !=0';
                    return $this->edit_all_from_PO($request);
                }else{
                    // return 'edit all biasa';*/
                    return $this->edit_all($request);
               // }    
            case 'del':
                return $this->del($request);
            default:
                return 'error happen..';
        }
    }

    public function chgDate($date){
        if(!empty($date)){
            $newstr=explode("/", $date);
            return $newstr[2].'-'.$newstr[1].'-'.$newstr[0];
        }else{
            return 'NULL';
        }
    }

    public function add(Request $request){

        $recno = $request->recno;
        $suppcode = $request->suppcode;
        $purdate = $request->purdate;
        $prdept = $request->prdept;
        $purordno = $request->purordno;

        DB::beginTransaction();

        try {
            ////1. calculate lineno_ by recno
            $sqlln = DB::table('material.purorddt')->select('lineno_')
                        ->where('compcode','=',session('compcode'))
                        ->where('recno','=',$recno)
                        ->count('lineno_');

            $li=intval($sqlln)+1;

            ///2. insert detail
            DB::table('material.purorddt')
                ->insert([
                    'compcode' => session('compcode'),
                    'recno' => $recno,
                    'lineno_' => $li,
                    'prdept' => $request->prdept,
                    'purordno' => $request->purordno,
                    'pricecode' => $request->pricecode,
                    'itemcode' => $request->itemcode,
                    'uomcode' => $request->uomcode,
                    'pouom' => $request->pouom,
                    'suppcode' => $request->suppcode,
                    'purdate' => $request->purdate,
                    'qtyorder' => $request->qtyorder,
                    'qtydelivered' => $request->qtydelivered,
                    // 'qtyoutstand' => 0,
                    'unitprice' => $request->unitprice,
                    'taxcode' => $request->taxcode,
                    'perdisc' => $request->perdisc,
                    'amtdisc' => $request->amtdisc,
                    'amtslstax' => $request->tot_gst,
                    'netunitprice' => $request->netunitprice,
                    'amount' => $request->amount,
                    'totamount' => $request->totamount, 
                    'adduser' => session('username'), 
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                    'recstatus' => 'OPEN', 
                    'remarks' => $request->remarks,
                    'unit' => session('unit'),
                    'prdept' => $request->prdept,
                    'purordno' => $request->purordno,

                ]);

            ///3. calculate total amount from detail
            $totalAmount = DB::table('material.purorddt')
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$recno)
                    ->where('recstatus','!=','DELETE')
                    ->sum('totamount');

            //calculate tot gst from detail
            $tot_gst = DB::table('material.purorddt')
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$recno)
                    ->where('recstatus','!=','DELETE')
                    ->sum('amtslstax');

            // $authorise = DB::table('material.authorisedtl')
            //     ->where('compcode','=',session('compcode'))
            //     ->where('trantype','=','PO')
            //     ->where('limitamt','>=',$totalAmount)
            //     ->orderBy('limitamt', 'asc')
            //     ->first();

            ///4. then update to header
            DB::table('material.purordhd')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$recno)
                ->update([
                    'totamount' => $totalAmount, 
                    'subamount'=> $totalAmount, 
                    'TaxAmt' => $tot_gst,
                    // 'authpersonid' => $authorise->authorid
                ]);


            echo $totalAmount;

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }
    }

    public function edit(Request $request){

        DB::beginTransaction();

        try {

            ///1. update detail
            DB::table('material.purorddt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->where('lineno_','=',$request->lineno_)
                ->update([
                    'pricecode' => $request->pricecode,
                    'itemcode' => $request->itemcode,
                    'uomcode' => $request->uomcode,
                    'pouom' => $request->pouom,
                    'suppcode' => $request->suppcode,
                    'purdate' => $request->purdate,
                    'qtyorder' => $request->qtyorder,
                    'qtydelivered' => $request->qtydelivered,
                    'unitprice' => $request->unitprice,
                    'taxcode' => $request->taxcode,
                    'perdisc' => $request->perdisc,
                    'amtdisc' => $request->amtdisc,
                    'amtslstax' => $request->tot_gst,
                    'netunitprice' => $request->netunitprice,
                    'amount' => $request->amount,
                    'totamount' => $request->totamount, 
                    'adduser' => session('username'), 
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"),  
                    'recstatus' => 'OPEN', 
                    'remarks' => $request->remarks,
                    'prdept' => $request->prdept,
                    'purordno' => $request->purordno,
                ]);

            ///2. recalculate total amount
            $totalAmount = DB::table('material.purorddt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->where('recstatus','!=','DELETE')
                ->sum('totamount');

            //calculate tot gst from detail
            $tot_gst = DB::table('material.purorddt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->where('recstatus','!=','DELETE')
                ->sum('amtslstax');

            ///3. update total amount to header
            DB::table('material.purordhd')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->update([
                    'totamount' => $totalAmount, 
                    'subamount'=> $totalAmount, 
                    'TaxAmt' => $tot_gst
                ]);
            
            echo $totalAmount;

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }

    }

    public function edit_all(Request $request){

        DB::beginTransaction();

        try {

            foreach ($request->dataobj as $key => $value) {
                ///1. update detail
                DB::table('material.purorddt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->where('lineno_','=',$value['lineno_'])
                ->update([
                    'pricecode' => $value['pricecode'],
                    'itemcode' => $value['itemcode'],
                    'uomcode' => $value['uomcode'],
                    'pouom' => $value['pouom'],
                    'suppcode' => $request->suppcode,
                    'purdate' => $request->purdate,
                    'qtyorder' => $value['qtyorder'],
                    'qtydelivered' => $value['qtydelivered'],
                    'unitprice' => $value['unitprice'],
                    'taxcode' => $value['taxcode'],
                    'perdisc' => $value['perdisc'],
                    'amtdisc' => $value['amtdisc'],
                    'amtslstax' => $value['tot_gst'],
                    'netunitprice' => $value['netunitprice'],
                    'amount' => $value['amount'],
                    'totamount' => $value['totamount'],
                    'adduser' => session('username'), 
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"),  
                    'recstatus' => 'OPEN', 
                    'remarks' => $value['remarks'],
                    'prdept' => $request->prdept,
                    'purordno' => $request->purordno,
                ]);
            }
            
            ///2. recalculate total amount
            $totalAmount = DB::table('material.purorddt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->where('recstatus','!=','DELETE')
                ->sum('totamount');

            //calculate tot gst from detail
            $tot_gst = DB::table('material.purorddt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->where('recstatus','!=','DELETE')
                ->sum('amtslstax');

            ///3. update total amount to header
            DB::table('material.purordhd')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->update([
                    'totamount' => $totalAmount, 
                    'subamount'=> $totalAmount, 
                    'TaxAmt' => $tot_gst
                ]);
            
            echo $totalAmount;

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }

    }

    public function del(Request $request){

        DB::beginTransaction();

        try {

            ///1. update detail
            DB::table('material.purorddt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->where('lineno_','=',$request->lineno_)
                ->delete();

            ///2. recalculate total amount
            $totalAmount = DB::table('material.purorddt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->where('recstatus','!=','DELETE')
                ->sum('totamount');

            //calculate tot gst from detail
            $tot_gst = DB::table('material.purorddt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->where('recstatus','!=','DELETE')
                ->sum('amtslstax');

            ///3. update total amount to header
            DB::table('material.purordhd')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->update([
                    'totamount' => $totalAmount, 
                    'subamount'=> $totalAmount, 
                    'TaxAmt' => $tot_gst
                ]);

            echo $totalAmount;

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }

        
    }

}

