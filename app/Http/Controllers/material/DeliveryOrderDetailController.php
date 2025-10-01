<?php

namespace App\Http\Controllers\material;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class DeliveryOrderDetailController extends defaultController
{   
    var $gltranAmount;
    var $srcdocno;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function form(Request $request)
    {   
        // return $this->request_no('GRN','2FL');
        // $delordhd = DB::table('material.delordhd')
        //     ->where('compcode','=',session('compcode'))
        //     ->where('recno','=',$request->recno)
        //     ->first();
        // $this->srcdocno = $delordhd->srcdocno;

        switch($request->oper){
            case 'add':
                return $this->add($request);
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

    public function table(Request $request)
    {   
        switch($request->action){
            case 'get_table_dtl':
                // dd('asd');
                return $this->DeliveryOrderDetail($request);
            default:
                return 'error happen..';
        }
    }

    public function DeliveryOrderDetail(Request $request){
        $table = DB::table('material.delorddt AS dodt')
                ->select('dodt.compcode','dodt.recno','dodt.lineno_','dodt.pricecode','dodt.itemcode','p.description','dodt.uomcode','dodt.pouom', 'dodt.suppcode','dodt.trandate','dodt.deldept','dodt.deliverydate','dodt.qtyorder','dodt.qtydelivered', 'dodt.qtyoutstand','dodt.unitprice','dodt.taxcode', 'dodt.perdisc','dodt.amtdisc','dodt.amtslstax as tot_gst','dodt.netunitprice','dodt.totamount', 'dodt.amount', 'dodt.expdate','dodt.batchno','dodt.polineno','dodt.rem_but AS remarks_button','dodt.remarks', 'dodt.unit','t.rate','dodt.idno','dodt.kkmappno')
                ->leftJoin('material.productmaster AS p', function($join) use ($request){
                    $join = $join->on("dodt.itemcode", '=', 'p.itemcode');    
                    $join = $join->where("p.compcode", '=', session('compcode'));    
                })
                ->leftJoin('hisdb.taxmast AS t', function($join) use ($request){
                    $join = $join->on("dodt.taxcode", '=', 't.taxcode');    
                    $join = $join->where("t.compcode", '=', session('compcode'));
                })
                ->where('dodt.recno','=',$request->filterVal[0])
                ->where('dodt.compcode','=',session('compcode'))
                ->where('dodt.recstatus','<>','DELETE')
                ->orderBy('dodt.idno','desc');

        //////////paginate/////////
        $paginate = $table->paginate($request->rows);

        foreach ($paginate->items() as $key => $value) {
            $value->remarks_show = $value->remarks;
            if(mb_strlen($value->remarks)>120){

                $time = time() + $key;

                $value->remarks_show = mb_substr($value->remarks_show,0,120).'<span id="dots_'.$time.'" style="display: inline;">...</span><span id="more_'.$time.'" style="display: none;">'.mb_substr($value->remarks_show,120).'</span><a id="moreBtn_'.$time.'" style="color: #337ab7 !important;" >Read more</a>';

                $value->callback_param = [
                    'dots_'.$time,'more_'.$time,'moreBtn_'.$time
                ];
            }
            
        }

        $responce = new stdClass();
        $responce->page = $paginate->currentPage();
        $responce->total = $paginate->lastPage();
        $responce->records = $paginate->total();
        $responce->rows = $paginate->items();
        $responce->sql = $table->toSql();
        $responce->sql_bind = $table->getBindings();

        return json_encode($responce);
    }

    public function get_draccno($itemcode,$pricecode){
        $product = DB::table('material.product')->where('itemcode','=',$itemcode)->first();

        if($pricecode == 'MS' && strtoupper($product->groupcode) == 'ASSET'){
            $query = DB::table('finance.facode')
                    ->select('facode.glassetccode')
                    ->join('material.product', 'facode.assetcode', '=', 'product.productcat')
                    ->where('product.itemcode','=',$itemcode)
                    ->first();
            
            return $query->glassetccode;

        }else if($pricecode == 'MS' && strtoupper($product->groupcode) == 'OTHERS'){
            $query = DB::table('material.category')
                    ->select('category.expacct')
                    ->join('material.product', 'category.catcode', '=', 'product.productcat')
                    ->where('product.itemcode','=',$itemcode)
                    ->first();
            
            return $query->expacct;
        }else{

            $query = DB::table('material.category')
                    ->select('category.stockacct')
                    ->join('material.product', 'category.catcode', '=', 'product.productcat')
                    ->where('product.itemcode','=',$itemcode)
                    ->first();
            
            return $query->stockacct;
        }
    }

    public function get_drccode($deldept){
        $query = DB::table('sysdb.department')
                ->select('costcode')
                ->where('compcode','=',session('compcode'))
                ->where('deptcode','=',$deldept)
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

    public function get_productcat($itemcode){
        $query = DB::table('material.product')
                ->select('productcat')
                ->where('compcode','=',session('compcode'))
                ->where('itemcode','=',$itemcode)
                ->first();
        
        return $query->productcat;
    }

    public function chgDate($date){
        if(!empty($date)){
            $newstr=explode("/", $date);
            return $newstr[2].'-'.$newstr[1].'-'.$newstr[0];
        }else{
            return '0000-00-00';
        }
    }

    public function add(Request $request){

        DB::beginTransaction();

        try{

            // if($request->pricecode == 'MS'){
            //     //check unique
            //     $duplicate = DB::table('material.purreqdt')
            //         ->where('compcode','=',session('compcode'))
            //         ->where('itemcode','=',strtoupper($request->itemcode))
            //         ->where('uomcode','=',strtoupper($request->uomcode))
            //         ->where('pouom','=',strtoupper($request->pouom))
            //         ->exists();

            //     if($duplicate){
            //         throw new \Exception("Duplicate itemcode and uom");
            //     }
            // }

            $recno = $request->recno;
            
            $delordhd = DB::table("material.delordhd")
                            ->where('idno','=',$request->idno)
                            ->where('compcode','=','DD');

            if($delordhd->exists()){
                $delordno = $this->request_no('DO',$delordhd->first()->prdept);
                $recno = $this->recno('PUR','DO');

                $unique_recno = DB::table('material.delordhd')
                                ->where('compcode',session('compcode'))
                                ->where('recno',$recno)
                                ->where('trantype','GRN');

                if($unique_recno->exists()){
                    throw new \Exception("delordhd already exists");
                }

                DB::table("material.delordhd")
                    ->where('idno','=',$request->idno)
                    ->update([
                        'docno' => $delordno,
                        'recno' => $recno,
                        'compcode' => session('compcode'),
                    ]);
            }else if(empty($recno)){
                $delordno = $this->request_no('DO',$request->prdept);
                $recno = $this->recno('PUR','DO');

                DB::table("material.delordhd")
                    ->where('idno','=',$request->idno)
                    ->update([
                        'docno' => $delordno,
                        'recno' => $recno,
                        'compcode' => session('compcode'),
                    ]);
            }else{
                $delordhd = DB::table("material.delordhd")
                            ->where('idno','=',$request->idno)
                            ->where('compcode','=', session('compcode'))
                            ->first();
                $delordno =  $delordhd->docno;
            }

            if($request->pricecode == 'IV' && $request->pricecode == 'BO'){
                $product = DB::table('material.product')
                            ->where('compcode',session('compcode'))
                            ->where('unit',session('unit'))
                            ->where('itemcode',$request->itemcode)
                            ->where('uomcode',$request->uomcode);

                if(!$product->exists()){
                    $product_no_unit = DB::table('material.product')
                                    ->where('compcode',session('compcode'))
                                    ->where('itemcode',$request->itemcode)
                                    ->where('uomcode',$request->uomcode);

                    if($product_no_unit->exists()){
                        $this->make_new_product_lain_unit($product_no_unit->first(),$request->deldept);
                    }else{
                        throw new \Exception("The item: ".$request->itemcode.' UOM '.$request->uomcode.' not exist!');
                    }
                }

                $product = $product->first();

                if($product->expdtflg == 1 && empty($request->expdate)) {
                    throw new \Exception("The item: ".$request->itemcode.' UOM '.$request->uomcode.' need to supply Expiry Date!');
                }

                $stockloc = DB::table('material.stockloc')
                            ->where('compcode',session('compcode'))
                            ->where('unit',session('unit'))
                            ->where('itemcode',$request->itemcode)
                            ->where('uomcode',$request->uomcode)
                            ->where('deptcode',$request->deldept)
                            ->where('year',$this->toYear($request->deliverydate));

                if(!$stockloc->exists()) {
                    $stockloc_no_dept = DB::table('material.stockloc')
                                        ->where('compcode',session('compcode'))
                                        // ->where('unit',session('unit'))
                                        ->where('itemcode',$request->itemcode)
                                        ->where('uomcode',$request->uomcode)
                                        // ->where('deptcode',$request->deldept)
                                        ->where('year',$this->toYear($request->deliverydate));

                    if($stockloc_no_dept->exists()){
                        $this->make_new_stockloc_lain_dept($stockloc_no_dept->first(),$request->deldept);
                    }else{
                        throw new \Exception("The item: ".$request->itemcode.' UOM '.$request->uomcode.' doesnt have stock location!');
                    }
                }
                // else{
                //     $this->make_new_stockloc();
                // }
            }

            if($request->pricecode == 'BO'){
                $request->unitprice = 0;
                $request->perdisc = 0;
                $request->amtdisc = 0;
                $request->tot_gst = 0;
                $request->netunitprice = 0;
                $request->amount = 0;
                $request->totamount = 0;
            }

            $draccno = $this->get_draccno($request->itemcode,$request->pricecode);
            $drccode = $this->get_drccode($request->deldept);
            $craccno = $this->get_craccno();
            $crccode = $this->get_crccode();
            $productcat = $this->get_productcat($request->itemcode);

            $suppcode = $request->suppcode;
            $trandate = $request->trandate;
            $deldept = $request->deldept;
            $deliverydate = $request->deliverydate;

            ////1. calculate lineno_ by recno
            $sqlln = DB::table('material.delorddt')->select('lineno_')
                        ->where('compcode','=',session('compcode'))
                        ->where('recno','=',$recno)
                        ->max('lineno_');

            $li=intval($sqlln)+1;

            ///2. insert detail
            DB::table('material.delorddt')
                ->insert([
                    'compcode' => session('compcode'),
                    'recno' => $recno,
                    'lineno_' => $li,
                    'pricecode' => strtoupper($request->pricecode), 
                    'itemcode'=> strtoupper($request->itemcode), 
                    'uomcode'=> strtoupper($request->uomcode), 
                    'pouom'=> strtoupper($request->pouom), 
                    'suppcode' => strtoupper($request->suppcode),
                    'trandate' => $request->trandate,
                    'deldept' => strtoupper($request->deldept),
                    'deliverydate' => $request->deliverydate,
                    'qtyorder' => $request->qtyorder,
                    'qtydelivered' => $request->qtydelivered,
                    'unitprice' => $request->unitprice,
                    'taxcode' => strtoupper($request->taxcode),
                    'perdisc' => $request->perdisc,
                    'amtdisc' => $request->amtdisc,
                    'amtslstax' => $request->tot_gst,
                    'netunitprice' => $request->netunitprice,
                    'amount' => $request->amount,
                    'totamount' => $request->totamount,
                    'draccno' => $draccno,
                    'drccode' => $drccode,
                    'craccno' => $craccno,
                    'crccode' => $crccode, 
                    'productcat' => $productcat,
                    'adduser' => session('username'), 
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                    'expdate' => $this->chgDate($request->expdate), 
                    'batchno' => strtoupper($request->batchno), 
                    'kkmappno' => strtoupper($request->kkmappno), 
                    'recstatus' => 'OPEN', 
                    'remarks'=> strtoupper($request->remarks),
                    'unit' => session('unit'),
                    'qtytag' => 0
                ]);

            ///3. calculate total amount from detail
            $totalAmount = DB::table('material.delorddt')
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$recno)
                    ->where('recstatus','!=','DELETE')
                    ->sum('totamount');

            //calculate tot gst from detail
            $tot_gst = DB::table('material.delorddt')
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$recno)
                    ->where('recstatus','!=','DELETE')
                    ->sum('amtslstax');

            ///4. then update to header
            DB::table('material.delordhd')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$recno)
                ->update([
                    'totamount' => $totalAmount, 
                    'subamount'=> $totalAmount - $tot_gst, 
                    'TaxAmt' => $tot_gst
                ]);

            $responce = new stdClass();
            $responce->totalAmount = $totalAmount;
            $responce->recno = $recno;
            $responce->delordno = $delordno;

            DB::commit();
            
            return json_encode($responce);
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function edit(Request $request){//xguna, guna edit_all

        DB::beginTransaction();

        try {

            // if($request->pricecode == 'MS'){
            //     //check unique
            //     $duplicate = DB::table('material.purreqdt')
            //         ->where('compcode','=',session('compcode'))
            //         ->where('itemcode','=',strtoupper($request->itemcode))
            //         ->where('uomcode','=',strtoupper($request->uomcode))
            //         ->where('pouom','=',strtoupper($request->pouom))
            //         ->exists();

            //     if($duplicate){
            //         throw new \Exception("Duplicate itemcode and uom");
            //     }
            // }

            if($request->pricecode == 'IV'){
                $product = DB::table('material.product')
                            ->where('compcode',session('compcode'))
                            ->where('unit',session('unit'))
                            ->where('itemcode',$request->itemcode)
                            ->where('uomcode',$request->uomcode);

                if(!$product->exists()){
                    throw new \Exception("The item: ".$request->itemcode.' UOM '.$request->uomcode.' not exist!');
                }

                $product = $product->first();

                if($product->expdtflg == 1 && empty($request->expdate)) {
                    throw new \Exception("The item: ".$request->itemcode.' UOM '.$request->uomcode.' need to supply Expiry Date!');
                }

                $stockloc = DB::table('material.stockloc')
                            ->where('compcode',session('compcode'))
                            ->where('unit',session('unit'))
                            ->where('itemcode',$request->itemcode)
                            ->where('uomcode',$request->uomcode)
                            ->where('deptcode',$request->deldept)
                            ->where('year',$this->toYear($request->deliverydate));

                if(!$stockloc->exists()) {
                    throw new \Exception("The item: ".$request->itemcode.' UOM '.$request->uomcode.' doesnt have stock location!');
                }
            }

            ///1. update detail
            DB::table('material.delorddt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->where('lineno_','=',$request->lineno_)
                ->update([
                    'pricecode' => strtoupper($request->pricecode), 
                    'itemcode'=> strtoupper($request->itemcode), 
                    'uomcode'=> strtoupper($request->uomcode), 
                    'pouom'=> strtoupper($request->pouom), 
                    'qtyorder'=> $request->qtyorder, 
                    'qtydelivered'=> $request->qtydelivered, 
                    'unitprice'=> $request->unitprice,
                    'taxcode'=> strtoupper($request->taxcode), 
                    'perdisc'=> $request->perdisc, 
                    'amtdisc'=> $request->amtdisc, 
                    'amtslstax'=> $request->tot_gst, 
                    'netunitprice'=> $request->netunitprice, 
                    'amount'=> $request->amount, 
                    'totamount'=> $request->totamount, 
                    'upduser'=> session('username'), 
                    'upddate'=> Carbon::now("Asia/Kuala_Lumpur"), 
                    'expdate'=> $this->chgDate($request->expdate),  
                    'batchno'=> strtoupper($request->batchno), 
                    'kkmappno' => strtoupper($request->kkmappno), 
                    'remarks'=> strtoupper($request->remarks),
                    'unit' => session('unit')
                ]);

            ///2. recalculate total amount
            $totalAmount = DB::table('material.delorddt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->where('recstatus','!=','DELETE')
                ->sum('totamount');

            //calculate tot gst from detail
            $tot_gst = DB::table('material.delorddt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->where('recstatus','!=','DELETE')
                ->sum('amtslstax');

            ///3. update total amount to header
            DB::table('material.delordhd')
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

            return response($e->getMessage(), 500);
        }
    }

    public function edit_all(Request $request){

        DB::beginTransaction();

        try {

            $do_hd = DB::table('material.delordhd')
                        ->where('compcode',session('compcode'))
                        ->where('recno', '=', $request->recno)
                        ->first();

            foreach ($request->dataobj as $key => $value) {

                if($value['pricecode'] == 'IV' || $value['pricecode'] == 'BO'){
                    $product = DB::table('material.product')
                                ->where('compcode',session('compcode'))
                                // ->where('unit',session('unit'))
                                ->where('itemcode',$value['itemcode'])
                                ->where('uomcode',$value['uomcode']);

                    if(!$product->exists()){
                        throw new \Exception("The item: ".$value['itemcode'].' UOM '.$value['uomcode'].' not exist!');
                    }

                    $product = $product->first();

                    if($product->expdtflg == 1 && empty($value['expdate']) && $value['qtydelivered'] > 0) {
                        throw new \Exception("The item: ".$value['itemcode'].' UOM '.$value['uomcode'].' need to supply Expiry Date!');
                    }

                    $stockloc = DB::table('material.stockloc')
                                ->where('compcode',session('compcode'))
                                // ->where('unit',session('unit'))
                                ->where('itemcode',$value['itemcode'])
                                ->where('uomcode',$value['uomcode'])
                                ->where('deptcode',$request->deldept)
                                ->where('year',$this->toYear($do_hd->deliverydate));

                    if(!$stockloc->exists()) {
                        $stockloc_no_dept = DB::table('material.stockloc')
                                            ->where('compcode',session('compcode'))
                                            // ->where('unit',session('unit'))
                                            ->where('itemcode',$value['itemcode'])
                                            ->where('uomcode',$value['uomcode'])
                                            // ->where('deptcode',$request->deldept)
                                            ->where('year',$this->toYear($do_hd->deliverydate));

                        if($stockloc_no_dept->exists()){
                            $this->make_new_stockloc_lain_dept($stockloc_no_dept->first(),$request->deldept);
                        }else{
                            throw new \Exception("The item: ".$value['itemcode'].' UOM '.$value['uomcode'].' doesnt have stock location!');
                        }
                    }
                }

                if($value['pricecode'] == 'BO'){
                    $value['unitprice'] = 0;
                    $value['perdisc'] = 0;
                    $value['amtdisc'] = 0;
                    $value['tot_gst'] = 0;
                    $value['netunitprice'] = 0;
                    $value['amount'] = 0;
                    $value['totamount'] = 0;
                }

                ///1. update detail
                DB::table('material.delorddt')
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$request->recno)
                    ->where('lineno_','=',$value['lineno_'])
                    ->update([
                        'pricecode' => strtoupper($value['pricecode']), 
                        'itemcode'=> strtoupper($value['itemcode']), 
                        'uomcode'=> strtoupper($value['uomcode']), 
                        'pouom'=> strtoupper($value['pouom']), 
                        'qtyorder'=> $value['qtyorder'], 
                        'qtydelivered'=> $value['qtydelivered'], 
                        'unitprice'=> $value['unitprice'],
                        'taxcode'=> strtoupper($value['taxcode']), 
                        'perdisc'=> $value['perdisc'], 
                        'amtdisc'=> $value['amtdisc'], 
                        'amtslstax'=> $value['tot_gst'], 
                        'netunitprice'=> $value['netunitprice'], 
                        'amount'=> $value['amount'], 
                        'totamount'=> $value['totamount'], 
                        'upduser'=> session('username'), 
                        'upddate'=> Carbon::now("Asia/Kuala_Lumpur"), 
                        'expdate'=> $this->chgDate($value['expdate']),  
                        'batchno'=> strtoupper($value['batchno']),
                        'kkmappno'=> strtoupper($value['kkmappno']),
                        'remarks'=> strtoupper($value['remarks']),
                        'unit' => session('unit')
                    ]);
            }
            
            ///2. recalculate total amount
            $totalAmount = DB::table('material.delorddt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->where('recstatus','!=','DELETE')
                ->sum('totamount');

            //calculate tot gst from detail
            $tot_gst = DB::table('material.delorddt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->where('recstatus','!=','DELETE')
                ->sum('amtslstax');

            ///3. update total amount to header
            DB::table('material.delordhd')
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

            return response($e->getMessage(), 500);
        }
    }

    public function del(Request $request){

        DB::beginTransaction();

        try {

            ///1. update detail
            DB::table('material.delorddt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->where('lineno_','=',$request->lineno_)
                ->delete();

            ///2. recalculate total amount
            $totalAmount = DB::table('material.delorddt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->where('recstatus','!=','DELETE')
                ->sum('totamount');

            //calculate tot gst from detail
            $tot_gst = DB::table('material.delorddt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->where('recstatus','!=','DELETE')
                ->sum('amtslstax');

            ///3. update total amount to header
            DB::table('material.delordhd')
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

    public function edit_from_PO(Request $request){

        DB::beginTransaction();

        try {

            ///1. update detail
            DB::table('material.delorddt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->where('lineno_','=',$request->lineno_)
                ->update([
                    'pricecode' => strtoupper($request->pricecode), 
                    'itemcode'=> strtoupper($request->itemcode), 
                    'uomcode'=> strtoupper($request->uomcode), 
                    'pouom'=> strtoupper($request->pouom), 
                    'qtyorder'=> $request->qtyorder, 
                    'qtydelivered'=> $request->qtydelivered, 
                    'unitprice'=> $request->unitprice,
                    'taxcode'=> strtoupper($request->taxcode), 
                    'perdisc'=> $request->perdisc, 
                    'amtdisc'=> $request->amtdisc, 
                    'amtslstax'=> $request->tot_gst, 
                    'netunitprice'=> $request->netunitprice, 
                    'amount'=> $request->amount, 
                    'totamount'=> $request->totamount, 
                    'upduser'=> session('username'), 
                    'upddate'=> Carbon::now("Asia/Kuala_Lumpur"), 
                    'expdate'=> $this->chgDate($request->expdate),  
                    'batchno'=> $request->batchno, 
                    'remarks'=> strtoupper($request->remarks),
                    'recstatus' => 'OPEN', 
                   // 'unit'=> $request->unit
                ]);

            ///2. recalculate total amount
            $totalAmount = DB::table('material.delorddt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->where('recstatus','!=','DELETE')
                ->sum('totamount');

            //calculate tot gst from detail
            $tot_gst = DB::table('material.delorddt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->where('recstatus','!=','DELETE')
                ->sum('amtslstax');

            ///3. update total amount to header
            DB::table('material.delordhd')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->update([
                    'totamount' => $totalAmount, 
                    'subamount'=> $totalAmount, 
                    'TaxAmt' => $tot_gst
                ]);

            ///4. cari recno dkt podt
            $purordhd = DB::table('material.purordhd')
                ->where('compcode','=',session('compcode'))
                ->where('purordno','=',$this->srcdocno)
                ->first();
            $po_recno = $purordhd->recno;

            ///5. amik old qtydelivered / qtyorder dkt qtyrequest
            $podt_obj = DB::table('material.purorddt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$po_recno)
                ->where('lineno_','=',$request->lineno_);

            $podt_obj_lama = $podt_obj->first();

            ///6. check dan bagi error kalu exceed quantity order

                //step 1. cari header yang ada srcdocno ni
            $delordhd_obj = DB::table('material.delordhd')
                ->where('compcode','=',session('compcode'))
                ->where('srcdocno','=',$this->srcdocno);

            $total_qtydeliverd_do = 0;
            
            if($delordhd_obj->exists()){

                $delorhd_all = $delordhd_obj->get();

                //step 2. dapatkan dia punya qtydelivered melalui lineno yg sama, pastu jumlahkan, jumlah ni qtydelivered yang blom post lagi
                foreach ($delorhd_all as $value_hd) {
                    $delorddt_obj = DB::table('material.delorddt')
                        ->where('recno','=',$value_hd->recno)
                        ->where('compcode','=',session('compcode'))
                        ->where('lineno_','=',$request->lineno_);

                    if($delorddt_obj->exists()){
                        $delorddt_data = $delorddt_obj->first();
                        $total_qtydeliverd_do = $total_qtydeliverd_do + $delorddt_data->qtydelivered;
                    }
                }
            }
                //step 3. jumlah_qtydelivered = qtydelivered yang dah post + qtydelivered yang blom post
            $jumlah_qtydelivered = $podt_obj_lama->qtydelivered + $total_qtydeliverd_do;

                //step 4. kalu melebihi qtyorder, rollback
            if($jumlah_qtydelivered > $podt_obj_lama->qtyorder){
                DB::rollback();

                return response('Error: Quantity delivered exceed quantity order', 500)
                  ->header('Content-Type', 'text/plain');
            }

                //step 5. update qtyoutstand
            $qtyoutstand = $podt_obj_lama->qtyorder - $jumlah_qtydelivered;

            DB::table('material.delorddt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->where('lineno_','=',$request->lineno_)
                ->update([
                    'qtyoutstand' => $qtyoutstand, 
                ]);
            
            echo $totalAmount;

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }

    }

    public function make_new_product_lain_unit($product,$deldept){
        DB::table('material.product')
            ->insert([
                'compcode' => $product->compcode,
                'unit' => session('unit'),
                'itemcode' => $product->itemcode,
                'description' => $product->description,
                'uomcode' => $product->uomcode,
                'groupcode' => $product->groupcode,
                'productcat' => $product->productcat,
                'suppcode' => $product->suppcode,
                'avgcost' => $product->avgcost,
                'actavgcost' => $product->actavgcost,
                'currprice' => $product->currprice,
                // 'qtyonhand' => $product->,
                // 'bonqty' => $product->,
                // 'rpkitem' => $product->,
                'minqty' => $product->minqty,
                'maxqty' => $product->maxqty,
                'reordlevel' => $product->reordlevel,
                'reordqty' => $product->reordqty,
                'adduser' => 'system',
                'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                'upduser' => 'system',
                'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                'recstatus' => 'ACTIVE',
                'chgflag' => $product->chgflag,
                'subcatcode' => $product->subcatcode,
                'expdtflg' => $product->expdtflg,
                'mstore' => $product->mstore,
                'costmargin' => $product->costmargin,
                'pouom' => $product->pouom,
                'reuse' => $product->reuse,
                'trqty' => $product->trqty,
                'deactivedate' => $product->deactivedate,
                'tagging' => $product->tagging,
                'itemtype' => $product->itemtype,
                'generic' => $product->generic,
                // 'deluser' => $product->,
                // 'deldate' => $product->,
                'Consignment' => $product->Consignment,
                'Class' => $product->Class,
                'TaxCode' => $product->TaxCode,
                'computerid' => session('computerid'),
                // 'ipaddress' => $product->,
                // 'lastcomputerid' => $product->,
                // 'lastipaddress' => $product->,
                // 'cm_uom' => $product->,
                // 'cm_invflag' => $product->,
                // 'cm_packqty' => $product->,
                // 'cm_druggrcode' => $product->,
                // 'cm_subgroup' => $product->,
                // 'cm_stockcode' => $product->,
                // 'cm_chgclass' => $product->,
                // 'cm_chggroup' => $product->,
                // 'cm_chgtype' => $product->,
                // 'cm_invgroup' => $product->,
            ]);

            DB::table('material.stockloc')
                ->insert([
                    'compcode' => session('compcode'),
                    'deptcode' => $deldept,
                    'itemcode' => $product->itemcode,
                    'uomcode' => $product->uomcode,
                    // 'bincode' => $stockloc->,
                    // 'rackno' => $stockloc->,
                    'year' => Carbon::now("Asia/Kuala_Lumpur")->format('Y'),
                    // 'openbalqty' => $stockloc->,
                    // 'openbalval' => $stockloc->,
                    // 'netmvqty1' => $stockloc->,
                    // 'netmvqty2' => $stockloc->,
                    // 'netmvqty3' => $stockloc->,
                    // 'netmvqty4' => $stockloc->,
                    // 'netmvqty5' => $stockloc->,
                    // 'netmvqty6' => $stockloc->,
                    // 'netmvqty7' => $stockloc->,
                    // 'netmvqty8' => $stockloc->,
                    // 'netmvqty9' => $stockloc->,
                    // 'netmvqty10' => $stockloc->,
                    // 'netmvqty11' => $stockloc->,
                    // 'netmvqty12' => $stockloc->,
                    // 'netmvval1' => $stockloc->,
                    // 'netmvval2' => $stockloc->,
                    // 'netmvval3' => $stockloc->,
                    // 'netmvval4' => $stockloc->,
                    // 'netmvval5' => $stockloc->,
                    // 'netmvval6' => $stockloc->,
                    // 'netmvval7' => $stockloc->,
                    // 'netmvval8' => $stockloc->,
                    // 'netmvval9' => $stockloc->,
                    // 'netmvval10' => $stockloc->,
                    // 'netmvval11' => $stockloc->,
                    // 'netmvval12' => $stockloc->,
                    'stocktxntype' => 'TR',
                    'disptype' => 'DS',
                    // 'qtyonhand' => $stockloc->,
                    // 'minqty' => $stockloc->minqty,
                    // 'maxqty' => $stockloc->maxqty,
                    // 'reordlevel' => $stockloc->reordlevel,
                    // 'reordqty' => $stockloc->reordqty,
                    // 'lastissdate' => $stockloc->,
                    // 'frozen' => $stockloc->,
                    'adduser' => 'system/it',
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'upduser' => 'system/it',
                    'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    // 'cntdocno' => $stockloc->,
                    // 'fix_uom' => $stockloc->,
                    // 'locavgcs' => $stockloc->,
                    // 'lstfrzdt' => $stockloc->,
                    // 'lstfrztm' => $stockloc->,
                    // 'frzqty' => $stockloc->,
                    'recstatus' => 'AC',
                    // 'deluser' => $stockloc->,
                    // 'deldate' => $stockloc->,
                    'computerid' => session('computerid'),
                    // 'ipaddress' => $stockloc->,
                    // 'lastcomputerid' => $stockloc->,
                    // 'lastipaddress' => $stockloc->,
                    'unit' => session('unit'),
                ]);
    }

    public function make_new_stockloc_lain_dept($stockloc,$deldept){
        DB::table('material.stockloc')
            ->insert([
                'compcode' => session('compcode'),
                'deptcode' => $deldept,
                'itemcode' => $stockloc->itemcode,
                'uomcode' => $stockloc->uomcode,
                // 'bincode' => $stockloc->,
                // 'rackno' => $stockloc->,
                'year' => $stockloc->year,
                // 'openbalqty' => $stockloc->,
                // 'openbalval' => $stockloc->,
                // 'netmvqty1' => $stockloc->,
                // 'netmvqty2' => $stockloc->,
                // 'netmvqty3' => $stockloc->,
                // 'netmvqty4' => $stockloc->,
                // 'netmvqty5' => $stockloc->,
                // 'netmvqty6' => $stockloc->,
                // 'netmvqty7' => $stockloc->,
                // 'netmvqty8' => $stockloc->,
                // 'netmvqty9' => $stockloc->,
                // 'netmvqty10' => $stockloc->,
                // 'netmvqty11' => $stockloc->,
                // 'netmvqty12' => $stockloc->,
                // 'netmvval1' => $stockloc->,
                // 'netmvval2' => $stockloc->,
                // 'netmvval3' => $stockloc->,
                // 'netmvval4' => $stockloc->,
                // 'netmvval5' => $stockloc->,
                // 'netmvval6' => $stockloc->,
                // 'netmvval7' => $stockloc->,
                // 'netmvval8' => $stockloc->,
                // 'netmvval9' => $stockloc->,
                // 'netmvval10' => $stockloc->,
                // 'netmvval11' => $stockloc->,
                // 'netmvval12' => $stockloc->,
                'stocktxntype' => $stockloc->stocktxntype,
                'disptype' => $stockloc->disptype,
                // 'qtyonhand' => $stockloc->,
                'minqty' => $stockloc->minqty,
                'maxqty' => $stockloc->maxqty,
                'reordlevel' => $stockloc->reordlevel,
                'reordqty' => $stockloc->reordqty,
                // 'lastissdate' => $stockloc->,
                // 'frozen' => $stockloc->,
                'adduser' => 'system/it',
                'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                'upduser' => 'system/it',
                'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                // 'cntdocno' => $stockloc->,
                // 'fix_uom' => $stockloc->,
                // 'locavgcs' => $stockloc->,
                // 'lstfrzdt' => $stockloc->,
                // 'lstfrztm' => $stockloc->,
                // 'frzqty' => $stockloc->,
                'recstatus' => 'ACTIVE',
                // 'deluser' => $stockloc->,
                // 'deldate' => $stockloc->,
                'computerid' => session('computerid'),
                // 'ipaddress' => $stockloc->,
                // 'lastcomputerid' => $stockloc->,
                // 'lastipaddress' => $stockloc->,
                'unit' => session('unit'),
            ]);
    }

    public function delete_dd(Request $request){
        DB::table('material.delordhd')
                ->where('idno','=',$request->idno)
                ->where('compcode','=','DD')
                ->delete();
    }

}

