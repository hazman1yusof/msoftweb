<?php

namespace App\Http\Controllers\material;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class InventoryTransactionDetailController extends defaultController
{   
    var $gltranAmount;
    var $srcdocno;

    public function __construct()
    {
        $this->middleware('auth');
    }

     public function form(Request $request)
    {   

        DB::enableQueryLog();
        // return $this->request_no('GRN','2FL');
        switch($request->oper){
            case 'add': 
                return $this->add($request);
            case 'edit':
                return $this->edit($request);
            case 'edit_all':
                return $this->edit_all($request);
            case 'del':
                return $this->del($request);
            case 'delete_dd':
                return $this->delete_dd($request);
            default:
                return 'error happen..';
        }
    }
    public function get_draccno($itemcode){
        $query = DB::table('material.category')
                ->select('category.stockacct')
                ->join('material.product', 'category.catcode', '=', 'product.productcat')
                ->where('product.itemcode','=',$itemcode)
                ->first();
        
        return $query->stockacct;
    }

    public function get_drccode($txndept){
        $query = DB::table('sysdb.department')
                ->select('costcode')
                ->where('compcode','=',session('compcode'))
                ->where('deptcode','=',$txndept)
                ->first();
        
        return $query->costcode;
    }

    public function get_craccno(){
        $query = DB::table('sysdb.sysparam')
                ->select('pvalue2')
                ->where('compcode','=',session('compcode'))
                ->where('source','=','AP')
                ->where('trantype','=','ACC')
                ->first();
        
        return $query->pvalue2;
    }

    public function get_crccode(){
        $query = DB::table('sysdb.sysparam')
                ->select('pvalue1')
                ->where('compcode','=',session('compcode'))
                ->where('source','=','AP')
                ->where('trantype','=','ACC')
                ->first();
        
        return $query->pvalue1;
    }

    public function chgDate($date){
        if(!empty($date)){
            $newstr=explode("/", $date);
            return $newstr[2].'-'.$newstr[1].'-'.$newstr[0];
        }else{
            return null;
        }
    }

    public function add(Request $request){
        DB::beginTransaction();

        try {
            $recno = $request->recno;
            $docno = $request->docno;
            $request->expdate = $this->null_date($request->expdate);

            $ivtmphd = DB::table("material.ivtmphd")
                            ->where('idno','=',$request->idno)
                            ->where('compcode','=','DD');

            if($ivtmphd->exists()){

                $docno = $this->request_no($request->trantype, $request->txndept);
                $recno = $this->recno('IV','IT');

                DB::table("material.ivtmphd")
                    ->where('idno','=',$request->idno)
                    ->update([
                        'docno' => $docno,
                        'recno' => $recno,
                        'compcode' => session('compcode'),
                    ]);
            }


            ////1. calculate lineno_ by recno
            $sqlln = DB::table('material.ivtmpdt')->select('lineno_')
                        ->where('compcode','=',session('compcode'))
                        ->where('recno','=',$recno)
                        ->count('lineno_');

            $li=intval($sqlln)+1;

            //2.1 check ada stockloc ke tak kat sndrcv
            $issuetype = DB::table('material.ivtxntype')->select('isstype')
                            ->where('compcode','=',session('compcode'))
                            ->where('trantype','=',$request->trantype)
                            ->first();

            if(strtoupper($issuetype->isstype) == 'TRANSFER'){
                $stockloc_obj = DB::table('material.StockLoc')
                        ->where('StockLoc.CompCode','=',session('compcode'))
                        ->where('StockLoc.DeptCode','=',$request->sndrcv)
                        ->where('StockLoc.ItemCode','=',$request->itemcode)
                        ->where('StockLoc.Year','=', defaultController::toYear($request->trandate))
                        ->where('StockLoc.UomCode','=',$request->uomcoderecv);

                if(!$stockloc_obj->exists()){
                    throw new \Exception('stockloc doesnt exists');
                }
            }

            ///2. insert detail
            DB::table('material.ivtmpdt')
                ->insert([
                    'compcode' => session('compcode'),
                    'recno' => $recno,
                    'lineno_' => $li,
                    'itemcode' => strtoupper($request->itemcode),
                    'uomcode' => strtoupper($request->uomcode),
                    'txnqty' => $request->txnqty,
                    'netprice' => $request->netprice,
                    'productcat' => $request->productcat,
                    'qtyonhand' => $request->qtyonhand,
                    'uomcoderecv'=> $request->uomcoderecv,
                    'qtyonhandrecv'=> $request->qtyonhandrecv,
                    'amount' => $request->amount,
                    'unit' => session('unit'), 
                    'adduser' => session('username'), 
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                    'expdate'=> $this->turn_date($request->expdate,'d/m/Y'),  
                    'batchno' => $request->batchno, 
                    'recstatus' => 'OPEN', 
                    'remarks' => $request->remarks
                ]);

            ///3. calculate total amount from detail
            $totalAmount = DB::table('material.ivtmpdt')
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$recno)
                    ->where('recstatus','!=','DELETE')
                    ->sum('amount');

       
            ///4. then update to header
            DB::table('material.ivtmphd')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$recno)
                ->update([
                    'amount' => $totalAmount
                  
                ]);
            DB::commit();

            $responce = new stdClass();
            $responce->totalAmount = $totalAmount;
            $responce->recno = $recno;
            $responce->docno = $docno;

            return json_encode($responce);

        } catch (\Exception $e) {
            DB::rollback();

            return response($e, 500);
        }
    }

    public function edit(Request $request){

        DB::beginTransaction();

        try {
            $request->expdate = $this->null_date($request->expdate);

            ///1. update detail
            DB::table('material.ivtmpdt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->where('lineno_','=',$request->lineno_)
                ->update([
                    'itemcode' => $request->itemcode,
                    'uomcode' => $request->uomcode,
                    'txnqty' => $request->txnqty,
                    'netprice' => $request->netprice,
                    'productcat' => $request->productcat,
                    'qtyonhand' => $request->qtyonhand,
                    'uomcoderecv'=> $request->uomcoderecv,
                    'qtyonhandrecv'=> $request->qtyonhandrecv,
                    'amount' => $request->amount,
                    'adduser' => session('username'), 
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                    'expdate'=> $this->turn_date($request->expdate,'d/m/Y'),  
                    'batchno' => $request->batchno, 
                    'recstatus' => 'OPEN', 
                    'remarks' => $request->remarks
                ]);

            ///2. recalculate total amount
            $totalAmount = DB::table('material.ivtmpdt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->where('recstatus','!=','DELETE')
                ->sum('amount');

            ///3. update total amount to header
            DB::table('material.ivtmphd')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->update([
                    'amount' => $totalAmount, 
                ]);

            DB::commit();
            return response($totalAmount,200);

        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }

    }

    public function edit_all(Request $request){

        DB::beginTransaction();

        try {
            $request->expdate = $this->null_date($request->expdate);


            $ivtmphd = DB::table('material.ivtmphd')
                            ->where('unit',session('unit'))
                            ->where('compcode','=',session('compcode'))
                            ->where('recno','=',$request->recno)
                            ->first();

            foreach ($request->dataobj as $key => $value) {

                // $stockloc_ = DB::table('material.stockloc as st')
                //                 ->select('st.itemcode','p.description','st.qtyonhand','st.uomcode','st.maxqty','p.avgcost','u.convfactor')
                //                 ->leftJoin('material.product as p', function($join) use ($request){
                //                     $join = $join->on('p.itemcode', '=', 'st.itemcode')
                //                             ->on('p.uomcode', '=', 'st.uomcode')
                //                             ->where('p.compcode','=',session('compcode'))
                //                             ->where('p.unit','=',session('unit'));
                //                 })
                //                 ->leftJoin('material.uom as u', function($join) use ($request){
                //                     $join = $join->on('p.uomcode', '=', 'st.uomcode')
                //                             ->where('u.compcode','=',session('compcode'));
                //                 })
                //                 ->where('st.itemcode',$value['itemcode'])
                //                 ->where('st.uomcode',$value['uomcode'])
                //                 ->where('st.unit',session('unit'))
                //                 ->where('st.year',Carbon::now("Asia/Kuala_Lumpur")->format('Y'))
                //                 ->where('st.deptcode',$ivtmphd->txndept);

                // if(!empty($ivtmphd->sndrcv)){
                //     $stockloc_rcv = DB::table('material.stockloc as st')
                //                     ->select('st.itemcode','p.description','st.qtyonhand','st.uomcode','st.maxqty','p.avgcost','u.convfactor')
                //                     ->leftJoin('material.product as p', function($join) use ($request){
                //                         $join = $join->on('p.itemcode', '=', 'st.itemcode')
                //                                 ->on('p.uomcode', '=', 'st.uomcode')
                //                                 ->where('p.compcode','=',session('compcode'))
                //                                 ->where('p.unit','=',session('unit'));
                //                     })
                //                     ->leftJoin('material.uom as u', function($join) use ($request){
                //                         $join = $join->on('p.uomcode', '=', 'st.uomcode')
                //                                 ->where('u.compcode','=',session('compcode'));
                //                     })
                //                     ->where('st.itemcode',$value['itemcode'])
                //                     ->where('st.uomcode',$value['uomcoderecv'])
                //                     ->where('st.compcode',session('compcode'))
                //                     ->where('st.unit',session('unit'))
                //                     ->where('st.year',Carbon::now("Asia/Kuala_Lumpur")->format('Y'))
                //                     ->where('st.deptcode',$ivtmphd->sndrcv);
                // }

                ///1. update detail
                DB::table('material.ivtmpdt')
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$request->recno)
                    ->where('lineno_','=',$value['lineno_'])
                    ->update([
                        'itemcode' => strtoupper($value['itemcode']),
                        'uomcode' => strtoupper($value['uomcode']),
                        'txnqty' => $value['txnqty'],
                        //'reqdept'=>$request->reqdept,
                        // 'ivreqno'=>$request->ivreqno,
                        // 'reqlineno'=>$request->lineno_,
                        'netprice' => $value['netprice'],
                        // 'productcat' => $value['productcat'],
                        'qtyonhand' => $value['qtyonhand'],
                        'uomcoderecv'=> strtoupper($value['uomcoderecv']),
                        'qtyonhandrecv'=> $value['qtyonhandrecv'],
                        'amount' => $value['amount'],
                        'adduser' => session('username'), 
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                        'expdate'=> $this->turn_date($value['expdate']),  
                        'batchno' => strtoupper($value['batchno']), 
                        'recstatus' => 'OPEN'
                    ]);

                ///2. recalculate total amount
                $totalAmount = DB::table('material.ivtmpdt')
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$request->recno)
                    ->where('recstatus','!=','DELETE')
                    ->sum('amount');

                ///3. update total amount to header
                DB::table('material.ivtmphd')
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$request->recno)
                    ->update([
                        'amount' => $totalAmount, 
                    ]);
            }

            

            DB::commit();
            return response($totalAmount,200);

        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }

    }


    public function del(Request $request){

        DB::beginTransaction();

        try {

            ///1. update detail
            DB::table('material.ivtmpdt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->where('lineno_','=',$request->lineno_)
                ->update([ 
                    'deluser' => session('username'), 
                    'deldate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'recstatus' => 'DELETE'
                ]);

            ///2. recalculate total amount
            $totalAmount = DB::table('material.ivtmpdt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->where('recstatus','!=','DELETE')
                ->sum('amount');

           
            ///3. update total amount to header
            DB::table('material.ivtmphd')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->update([
                    'amount' => $totalAmount,  
                   
                ]);

            DB::commit();

            return response($totalAmount,200);

        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
        
    }

    public function edit_from_SR(Request $request){

        DB::beginTransaction();

        try {

            ///1. update detail
            DB::table('material.ivtmpdt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->where('lineno_','=',$request->lineno_)
                ->update([
                    'itemcode' => strtoupper($request->itemcode),
                    'uomcode' => strtoupper($request->uomcode),
                    'txnqty' => $request->txnqty,
                    'netprice' => $request->netprice,
                    'productcat' => $request->productcat,
                    'qtyonhand' => $request->qtyonhand,
                    'uomcoderecv'=> strtoupper($request->uomcoderecv),
                    'qtyonhandrecv'=> $request->qtyonhandrecv,
                    'amount' => $request->amount,
                    'adduser' => session('username'), 
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                    'expdate'=> $request->expdate,  
                    'batchno' => strtoupper($request->batchno), 
                    'recstatus' => 'OPEN', 
                    'remarks' => $request->remarks
                ]);

            ///2. recalculate total amount
            $totalAmount = DB::table('material.ivtmpdt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->where('recstatus','!=','DELETE')
                ->sum('amount');

            ///3. update total amount to header
            DB::table('material.ivtmpdt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->update([
                    'amount' => $totalAmount,
                ]);

    
            
            echo $totalAmount;

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }

    }

    
    public function delete_dd(Request $request){
        DB::table('material.ivtmphd')
                ->where('idno','=',$request->idno)
                ->where('compcode','=','DD')
                ->delete();
    }


}

