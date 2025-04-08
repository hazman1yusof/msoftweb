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
                            ->where('idno','=',$request->h_idno)
                            ->where('compcode','=','DD');

            if($ivtmphd->exists()){
                $docno = $this->request_no($request->trantype, $request->txndept);
                $recno = $this->recno('IV','IT');

                DB::table("material.ivtmphd")
                    ->where('idno','=',$request->h_idno)
                    ->update([
                        'docno' => $docno,
                        'recno' => $recno,
                        'compcode' => session('compcode'),
                    ]);
            }

            if(empty($recno)){
                $docno = $this->request_no($request->trantype, $request->txndept);
                $recno = $this->recno('IV','IT');

                DB::table("material.ivtmphd")
                    ->where('idno','=',$request->h_idno)
                    ->update([
                        'docno' => $docno,
                        'recno' => $recno,
                        'compcode' => session('compcode'),
                    ]);
            }
            $ivtmphd = DB::table("material.ivtmphd")
                            ->where('idno','=',$request->h_idno)
                            ->first();

            $txndept = $ivtmphd->txndept;
            $department = DB::table('sysdb.department')
                            ->where('compcode',session('compcode'))
                            ->where('deptcode',$txndept)
                            ->first();
            $unit_ = $department->sector;


            ////1. calculate lineno_ by recno
            $sqlln = DB::table('material.ivtmpdt')->select('lineno_')
                        ->where('compcode','=',session('compcode'))
                        ->where('recno','=',$recno)
                        ->max('lineno_');

            $li=intval($sqlln)+1;

            //2.1 check ada stockloc ke tak kat sndrcv

            // if(empty($request->txnqty)){
            //     throw new \Exception('Quantity cant be 0, delete the row first');
            // }

            $product = DB::table('material.product')
                    // ->where('unit','=',session('unit'))
                    ->where('compcode','=',session('compcode'))
                    ->where('ItemCode','=',$request->itemcode)
                    ->where('UomCode','=',$request->uomcode);

            if(!$product->exists()){
                throw new \Exception('product doesnt exists $request->itemcode , $request->uomcode');
            }

            if(strtoupper($ivtmphd->trantype) == 'TUI'){
                $stockloc_obj = DB::table('material.StockLoc')
                        ->where('StockLoc.unit','=',$unit_)
                        ->where('StockLoc.CompCode','=',session('compcode'))
                        ->where('StockLoc.DeptCode','=',$ivtmphd->txndept)
                        ->where('StockLoc.ItemCode','=',$request->itemcode)
                        ->where('StockLoc.Year','=', defaultController::toYear($request->trandate))
                        ->where('StockLoc.UomCode','=',$request->uomcode);

                if(!$stockloc_obj->exists()){
                    throw new \Exception('stockloc doesnt exists $request->itemcode , $request->uomcode');
                }
            }else if(strtoupper($ivtmphd->trantype) == 'TUO'){
                $stockloc_obj = DB::table('material.StockLoc')
                        ->where('StockLoc.unit','=',$unit_)
                        ->where('StockLoc.CompCode','=',session('compcode'))
                        ->where('StockLoc.DeptCode','=',$ivtmphd->txndept)
                        ->where('StockLoc.ItemCode','=',$request->itemcode)
                        ->where('StockLoc.Year','=', defaultController::toYear($request->trandate))
                        ->where('StockLoc.UomCode','=',$request->uomcode);

                if(!$stockloc_obj->exists()){
                    throw new \Exception('stockloc doesnt exists $request->itemcode , $request->uomcode');
                }
                $stockloc_first = $stockloc_obj->first();

                // if($request->txnqty > $stockloc_first->qtyonhand){
                //     throw new \Exception('Quantity not enough at Stock location, $ivtmphd->txndept - $request->itemcode - $request->uomcode');
                // }
                    
                $chgprice_obj = DB::table('hisdb.chgprice as cp')
                    ->where('cp.compcode', '=', session('compcode'))
                    ->where('cp.chgcode', '=', $request->itemcode)
                    ->where('cp.uom', '=', $request->uomcode)
                    ->whereDate('cp.effdate', '<=', Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d'))
                    ->orderBy('cp.effdate','desc');

                if($chgprice_obj->exists()){
                    $chgprice_obj = $chgprice_obj->first();
                    $netprice_tuo = $chgprice_obj->costprice;
                }else{
                    $netprice_tuo = $request->netprice;
                }

            }else{
                $issuetype = DB::table('material.ivtxntype')->select('isstype')
                                ->where('compcode','=',session('compcode'))
                                ->where('trantype','=',$request->trantype)
                                ->first();

                if(strtoupper($issuetype->isstype) == 'TRANSFER'){
                    $stockloc_obj = DB::table('material.StockLoc')
                            ->where('StockLoc.unit','=',$unit_)
                            ->where('StockLoc.CompCode','=',session('compcode'))
                            ->where('StockLoc.DeptCode','=',$request->sndrcv)
                            ->where('StockLoc.ItemCode','=',$request->itemcode)
                            ->where('StockLoc.Year','=', defaultController::toYear($request->trandate))
                            ->where('StockLoc.UomCode','=',$request->uomcoderecv);

                    if(!$stockloc_obj->exists()){
                        throw new \Exception('stockloc doesnt exists');
                    }
                }
            }

            $add_array = [
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
                    'unit' => $unit_, 
                    'adduser' => session('username'), 
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                    'expdate'=> $this->turn_date($request->expdate,'d/m/Y'),  
                    'batchno' => $request->batchno, 
                    'recstatus' => 'OPEN', 
                    'remarks' => $request->remarks
                ];

            // if(strtoupper($ivtmphd->trantype) == 'TUO'){
            //     $add_array['netprice'] = $netprice_tuo;
            //     $add_array['amount'] = $netprice_tuo * $request->txnqty;
            // }

            ///2. insert detailF
            DB::table('material.ivtmpdt')
                ->insert($add_array);

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

            return response($e->getMessage(), 500);
        }
    }

    public function edit(Request $request){

        DB::beginTransaction();

        try {
            $request->expdate = $this->null_date($request->expdate);

            $ivtmphd = DB::table('material.ivtmphd')
                            ->where('unit',session('unit'))
                            ->where('compcode','=',session('compcode'))
                            ->where('recno','=',$request->recno)
                            ->first();

            $year_ = defaultController::toYear($ivtmphd->trandate);

            $product = DB::table('material.product')
                        ->where('unit','=',session('unit'))
                        ->where('compcode','=',session('compcode'))
                        ->where('ItemCode','=',$request->itemcode)
                        ->where('UomCode','=',$request->uomcode);

            if(!$product->exists()){
                throw new \Exception('product doesnt exists '.$request->itemcode.' , '.$request->uomcode);
            }

            if(strtoupper($ivtmphd->trantype) == 'TUI'){
                $stockloc_obj = DB::table('material.StockLoc')
                        ->where('StockLoc.unit','=',session('unit'))
                        ->where('StockLoc.CompCode','=',session('compcode'))
                        ->where('StockLoc.DeptCode','=',$ivtmphd->txndept)
                        ->where('StockLoc.ItemCode','=',$request->itemcode)
                        ->where('StockLoc.Year','=', $year_)
                        ->where('StockLoc.UomCode','=',$request->uomcode);

                if(!$stockloc_obj->exists()){
                    throw new \Exception('stockloc doesnt exists'.$request->itemcode.' , '.$request->uomcode);
                }
            }else if(strtoupper($ivtmphd->trantype) == 'TUO'){
                $stockloc_obj = DB::table('material.StockLoc')
                        ->where('StockLoc.unit','=',session('unit'))
                        ->where('StockLoc.CompCode','=',session('compcode'))
                        ->where('StockLoc.DeptCode','=',$ivtmphd->txndept)
                        ->where('StockLoc.ItemCode','=',$request->itemcode)
                        ->where('StockLoc.Year','=', $year_)
                        ->where('StockLoc.UomCode','=',$request->uomcode);

                if(!$stockloc_obj->exists()){
                    throw new \Exception('stockloc doesnt exists'.$request->itemcode.' , '.$request->uomcode);
                }
                $stockloc_first = $stockloc_obj->first();

                // if($stockloc_first->qtyonhand >= 0 && $value['txnqty'] > $stockloc_first->qtyonhand){
                //     throw new \Exception("Quantity not enough at Stock location, ".$ivtmphd->txndept." - ".$value['itemcode']." - ".$value['uomcode']);
                // }
                
                $chgprice_obj = DB::table('hisdb.chgprice as cp')
                    ->where('cp.compcode', '=', session('compcode'))
                    ->where('cp.chgcode', '=', $request->itemcode)
                    ->where('cp.uom', '=', $request->uomcode)
                    ->whereDate('cp.effdate', '<=', Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d'))
                    ->orderBy('cp.effdate','desc');

                if($chgprice_obj->exists()){
                    $chgprice_obj = $chgprice_obj->first();
                    $netprice_tuo = $chgprice_obj->costprice;
                }else{
                    $netprice_tuo = $value['netprice'];
                }

                // if($value['qtyrequest'] < $value['txnqty']){
                //     throw new \Exception("Quantity transaction greater than qtyrequest, ".$ivtmphd->txndept." - ".$value['itemcode']." - ".$value['uomcode']);
                // }
            }else{
                $issuetype = DB::table('material.ivtxntype')->select('isstype')
                                ->where('compcode','=',session('compcode'))
                                ->where('trantype','=',$ivtmphd->trantype)
                                ->first();

                if(strtoupper($issuetype->isstype) == 'TRANSFER'){
                    $stockloc_obj = DB::table('material.StockLoc')
                            ->where('StockLoc.unit','=',session('unit'))
                            ->where('StockLoc.CompCode','=',session('compcode'))
                            ->where('StockLoc.DeptCode','=',$ivtmphd->sndrcv)
                            ->where('StockLoc.ItemCode','=',$request->itemcode)
                            ->where('StockLoc.Year','=', $year_)
                            ->where('StockLoc.UomCode','=',$request->uomcoderecv);

                    if(!$stockloc_obj->exists()){
                        throw new \Exception('stockloc doesnt exists');
                    }
                }
            }

            ///1. update detail
            DB::table('material.ivtmpdt')
                ->where('compcode','=',session('compcode'))
                ->where('idno','=',$request->idno)
                ->update([
                    // 'itemcode' => $request->itemcode,
                    // 'uomcode' => $request->uomcode,
                    'txnqty' => $request->txnqty,
                    'netprice' => $request->netprice,
                    // 'productcat' => $request->productcat,
                    // 'qtyonhand' => $request->qtyonhand,
                    // 'uomcoderecv'=> $request->uomcoderecv,
                    // 'qtyonhandrecv'=> $request->qtyonhandrecv,
                    'amount' => $request->amount,
                    'upduser' => session('username'), 
                    'upduser' => Carbon::now("Asia/Kuala_Lumpur"), 
                    'expdate'=> $this->turn_date($request->expdate,'d/m/Y'),  
                    'batchno' => $request->batchno, 
                    // 'recstatus' => 'OPEN', 
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
            
            $responce = new stdClass();
            $responce->totalAmount = $totalAmount;
            $responce->recno = $request->recno;
            // $responce->docno = $docno;

            return json_encode($responce);

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

            $year_ = defaultController::toYear($ivtmphd->trandate);

            foreach ($request->dataobj as $key => $value) {

                // if(empty($value['txnqty'])){
                //     throw new \Exception('Quantity cant be 0, delete the row first');
                // }

                $product = DB::table('material.product')
                        ->where('unit','=',session('unit'))
                        ->where('compcode','=',session('compcode'))
                        ->where('ItemCode','=',$value['itemcode'])
                        ->where('UomCode','=',$value['uomcode']);

                if(!$product->exists()){
                    throw new \Exception('product doesnt exists '.$value['itemcode'].' , '.$value['uomcode']);
                }

                if(strtoupper($ivtmphd->trantype) == 'TUI'){
                    $stockloc_obj = DB::table('material.StockLoc')
                            ->where('StockLoc.unit','=',session('unit'))
                            ->where('StockLoc.CompCode','=',session('compcode'))
                            ->where('StockLoc.DeptCode','=',$ivtmphd->txndept)
                            ->where('StockLoc.ItemCode','=',$value['itemcode'])
                            ->where('StockLoc.Year','=', $year_)
                            ->where('StockLoc.UomCode','=',$value['uomcode']);

                    if(!$stockloc_obj->exists()){
                        throw new \Exception('stockloc doesnt exists'.$value['itemcode'].' , '.$value['uomcode']);
                    }
                }else if(strtoupper($ivtmphd->trantype) == 'TUO'){
                    $stockloc_obj = DB::table('material.StockLoc')
                            ->where('StockLoc.unit','=',session('unit'))
                            ->where('StockLoc.CompCode','=',session('compcode'))
                            ->where('StockLoc.DeptCode','=',$ivtmphd->txndept)
                            ->where('StockLoc.ItemCode','=',$value['itemcode'])
                            ->where('StockLoc.Year','=', $year_)
                            ->where('StockLoc.UomCode','=',$value['uomcode']);

                    if(!$stockloc_obj->exists()){
                        throw new \Exception('stockloc doesnt exists'.$value['itemcode'].' , '.$value['uomcode']);
                    }
                    $stockloc_first = $stockloc_obj->first();

                    // if($stockloc_first->qtyonhand >= 0 && $value['txnqty'] > $stockloc_first->qtyonhand){
                    //     throw new \Exception("Quantity not enough at Stock location, ".$ivtmphd->txndept." - ".$value['itemcode']." - ".$value['uomcode']);
                    // }
                    
                    $chgprice_obj = DB::table('hisdb.chgprice as cp')
                        ->where('cp.compcode', '=', session('compcode'))
                        ->where('cp.chgcode', '=', $value['itemcode'])
                        ->where('cp.uom', '=', $value['uomcode'])
                        ->whereDate('cp.effdate', '<=', Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d'))
                        ->orderBy('cp.effdate','desc');

                    if($chgprice_obj->exists()){
                        $chgprice_obj = $chgprice_obj->first();
                        $netprice_tuo = $chgprice_obj->costprice;
                    }else{
                        $netprice_tuo = $value['netprice'];
                    }

                    // if($value['qtyrequest'] < $value['txnqty']){
                    //     throw new \Exception("Quantity transaction greater than qtyrequest, ".$ivtmphd->txndept." - ".$value['itemcode']." - ".$value['uomcode']);
                    // }
                }else{
                    $issuetype = DB::table('material.ivtxntype')->select('isstype')
                                    ->where('compcode','=',session('compcode'))
                                    ->where('trantype','=',$ivtmphd->trantype)
                                    ->first();

                    if(strtoupper($issuetype->isstype) == 'TRANSFER'){
                        $stockloc_obj = DB::table('material.StockLoc')
                                ->where('StockLoc.unit','=',session('unit'))
                                ->where('StockLoc.CompCode','=',session('compcode'))
                                ->where('StockLoc.DeptCode','=',$ivtmphd->sndrcv)
                                ->where('StockLoc.ItemCode','=',$value['itemcode'])
                                ->where('StockLoc.Year','=', $year_)
                                ->where('StockLoc.UomCode','=',$value['uomcoderecv']);

                        if(!$stockloc_obj->exists()){
                            throw new \Exception('stockloc doesnt exists');
                        }
                    }
                }

                $upd_array = [
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
                        'remarks'=> $value['remarks'],
                        'adduser' => session('username'), 
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                        'expdate'=> $this->turn_date($value['expdate']),  
                        'batchno' => strtoupper($value['batchno']), 
                        'recstatus' => 'OPEN'
                    ];

                if(strtoupper($ivtmphd->trantype) == 'TUO'){
                    $upd_array['netprice'] = $netprice_tuo;
                    $upd_array['amount'] = $netprice_tuo * $value['txnqty'];
                }

                ///1. update detail
                DB::table('material.ivtmpdt')
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$request->recno)
                    ->where('lineno_','=',$value['lineno_'])
                    ->update($upd_array);

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

