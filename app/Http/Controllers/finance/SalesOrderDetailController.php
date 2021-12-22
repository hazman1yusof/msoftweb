<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class SalesOrderDetailController extends defaultController
{   

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
                return $this->edit_all($request);

            case 'del':
                return $this->del($request);

            default:
                return 'error happen..';
        }
    }

    public function table(Request $request)
    {   
        switch($request->action){
            case 'get_table_dtl':
                return $this->get_table_dtl($request);
            case 'get_itemcode_price':
                return $this->get_itemcode_price($request);
            default:
                return 'error happen..';
        }
    }

    public function get_table_dtl(Request $request){
        $table = DB::table('debtor.billsum')
                    ->where('source','=',$request->source)
                    ->where('trantype','=',$request->trantype)
                    ->where('auditno','=',$request->auditno)
                    ->where('compcode','=',session('compcode'))
                    ->where('recstatus','<>','DELETE')
                    ->orderBy('idno','desc');

        //////////paginate/////////
        $paginate = $table->paginate($request->rows);

        $responce = new stdClass();
        $responce->page = $paginate->currentPage();
        $responce->total = $paginate->lastPage();
        $responce->records = $paginate->total();
        $responce->rows = $paginate->items();
        $responce->sql = $table->toSql();
        $responce->sql_bind = $table->getBindings();

        return json_encode($responce);
    }
   
    public function get_itemcode_price(Request $request){
        $deptcode = $request->filterVal[0];
        $priceuse = $request->filterVal[1];

        switch ($priceuse) {
            case 'PRICE1':
                $cp_fld = 'amt1';
                break;
            case 'PRICE2':
                $cp_fld = 'amt2';
                break;
            case 'PRICE3':
                $cp_fld = 'amt3';
                break;
            default:
                $cp_fld = 'costprice';
                break;
        }

        $table = DB::table('hisdb.chgmast as cm')
                        ->select('cm.chgcode as chgcode','cm.invflag as invflag','cm.description as description', 'cm.uom as uom', 'st.qtyonhand','cp.optax as taxcode','tm.rate', 'cp.idno','cp.'.$cp_fld.' as price')
                        ->where('cm.compcode','=',session('compcode'))
                        ->where('cm.recstatus','<>','DELETE');

        $table = $table->join('hisdb.chgprice as cp', function($join) use ($request,$cp_fld){
                            $join = $join->where('cp.compcode', '=', session('compcode'));
                            $join = $join->on('cp.chgcode', '=', 'cm.chgcode');
                            $join = $join->on('cp.uom', '=', 'cm.uom');
                            $join = $join->where('cp.'.$cp_fld,'<>',0.0000);
                            $join = $join->where('cp.effdate', '<=', Carbon::now('Asia/Kuala_Lumpur'));
                        });

        $table = $table->join('material.stockloc as st', function($join) use ($deptcode){
                            $join = $join->where('st.compcode', '=', session('compcode'));
                            $join = $join->where('st.unit', '=', session('unit'));
                            $join = $join->on('st.itemcode', '=', 'cm.chgcode');
                            $join = $join->on('st.uomcode', '=', 'cm.uom');
                            $join = $join->where('st.deptcode', '=', $deptcode);
                            $join = $join->where('st.year', '=', Carbon::now('Asia/Kuala_Lumpur')->year);
                        });

        $table = $table->join('hisdb.taxmast as tm', function($join){
                            $join = $join->where('cp.compcode', '=', session('compcode'));
                            $join = $join->on('cp.optax', '=', 'tm.taxcode');
                        });

        if(!empty($request->searchCol)){
            $searchCol_array = $request->searchCol;

            $count = array_count_values($searchCol_array);

            foreach ($count as $key => $value) {
                $occur_ar = $this->index_of_occurance($key,$searchCol_array);

                $table = $table->orWhere(function ($table) use ($request,$searchCol_array,$occur_ar) {
                    foreach ($searchCol_array as $key => $value) {
                        $found = array_search($key,$occur_ar);
                        if($found !== false){
                            $table->Where($searchCol_array[$key],'like',$request->searchVal[$key]);
                            // $table->Where('cm.'.$searchCol_array[$key],'like',$request->searchVal[$key]);
                        }
                    }
                });
            }
        }

        if(!empty($request->searchCol2)){
            $searchCol_array = $request->searchCol2;
            $table = $table->where(function($table) use ($searchCol_array, $request){
                foreach ($searchCol_array as $key => $value) {
                    if($key>1) break;
                    $table->orwhere($searchCol_array[$key],'like', $request->searchVal2[$key]);
                    // $table->orwhere('cm.'.$searchCol_array[$key],'like', $request->searchVal2[$key]);
                }
            });

            if(count($searchCol_array)>2){
                $table = $table->where(function($table) use ($searchCol_array, $request){
                    foreach ($searchCol_array as $key => $value) {
                        if($key<=1) continue;
                        $table->orwhere($searchCol_array[$key],'like', $request->searchVal2[$key]);
                        // $table->orwhere('cm.'.$searchCol_array[$key],'like', $request->searchVal2[$key]);
                    }
                });
            }
        }

        if(!empty($request->sidx)){

            if(!empty($request->fixPost)){
                $request->sidx = substr_replace($request->sidx, ".", strpos($request->sidx, "_"), strlen("."));
            }
            
            $pieces = explode(", ", $request->sidx .' '. $request->sord);
            if(count($pieces)==1){
                $table = $table->orderBy($request->sidx, $request->sord);
            }else{
                for ($i = sizeof($pieces)-1; $i >= 0 ; $i--) {
                    $pieces_inside = explode(" ", $pieces[$i]);
                    $table = $table->orderBy($pieces_inside[0], $pieces_inside[1]);
                }
            }
        }else{
            $table = $table->orderBy('cm.idno','desc');
        }

        $paginate = $table->paginate($request->rows);
        // dd($paginate);
        $rows = $paginate->items();

        foreach ($rows as $key => $value) {
            $chgprice_obj = DB::table('hisdb.chgprice as cp')
                ->select('cp.idno',$cp_fld,'cp.optax','tm.rate','cp.chgcode')
                ->leftJoin('hisdb.taxmast as tm', 'cp.optax', '=', 'tm.taxcode')
                ->where('cp.compcode', '=', session('compcode'))
                ->where('cp.chgcode', '=', $value->chgcode)
                ->where('cp.uom', '=', $value->uom)
                ->whereDate('cp.effdate', '<=', Carbon::now('Asia/Kuala_Lumpur'))
                ->orderBy('cp.effdate','desc');

            if($chgprice_obj->exists()){
                $chgprice_obj = $chgprice_obj->first();

                if($value->chgcode == $chgprice_obj->chgcode && $value->idno != $chgprice_obj->idno){
                    unset($rows[$key]);
                    continue;
                }

                // switch ($request->filterVal[2]) {
                //     case 'PRICE1':
                //         $rows[$key]->price = $chgprice_obj->amt1;
                //         break;
                //     case 'PRICE2':
                //         $rows[$key]->price = $chgprice_obj->amt2;
                //         break;
                //     case 'PRICE3':
                //         $rows[$key]->price = $chgprice_obj->amt3;
                //         break;
                //     default:
                //         $rows[$key]->price = $chgprice_obj->costprice;
                //         break;
                // }
                // $rows[$key]->taxcode = $chgprice_obj->optax;
                // $rows[$key]->rate = $chgprice_obj->rate;
            }
        }

        $rows = array_values($rows);

        //////////paginate/////////
        // $paginate = $table->paginate($request->rows);

        $responce = new stdClass();
        $responce->page = $paginate->currentPage();
        $responce->total = $paginate->lastPage();
        $responce->records = $paginate->total();
        // $responce->rows = $paginate->items();
        $responce->rows = $rows;
        $responce->sql = $table->toSql();
        $responce->sql_bind = $table->getBindings();

        return json_encode($responce);
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

        $source = $request->source;
        $trantype = $request->trantype;
        $auditno = $request->auditno;

        DB::beginTransaction();
        
        try {

            $dbacthdr = DB::table('debtor.dbacthdr')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','=',$source)
                    ->where('trantype','=',$trantype)
                    ->where('auditno','=',$auditno);

            $dbacthdr = $dbacthdr->first();

            ////1. calculate lineno_ by recno
            $sqlln = DB::table('debtor.billsum')->select('lineno_')
                        ->where('compcode','=',session('compcode'))
                        ->where('source','=',$source)
                        ->where('trantype','=',$trantype)
                        ->where('auditno','=',$auditno)
                        ->count('lineno_');

            $li=intval($sqlln)+1;

            ///2. insert detail
            $insertGetId = DB::table('debtor.billsum')
                ->insertGetId([
                    'compcode' => session('compcode'),
                    'source' => $source,
                    'trantype' => $trantype,
                    'auditno' => $auditno,
                    'chggroup' => $request->chggroup,
                    'description' => $request->description,
                    'lineno_' => $li,
                    'mrn' => (!empty($dbacthdr->mrn))?$dbacthdr->mrn:null,
                    'episno' => (!empty($dbacthdr->episno))?$dbacthdr->episno:null,
                    'uom' => $request->uom,
                    'taxcode' => $request->taxcode,
                    'unitprice' => $request->unitprice,
                    'quantity' => $request->quantity,
                    'amount' => $request->amount,
                    'discamt' => floatval($request->discamt),
                    'taxamt' => floatval($request->taxamt),
                    'lastuser' => session('username'), 
                    'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"), 
                    'recstatus' => 'OPEN',
                    'taxcode' => $request->taxcode,
                    'billtypeperct' => $request->billtypeperct,
                    'billtypeamt' => $request->billtypeamt,
                ]);

            $billsum_obj = db::table('debtor.billsum')
                            ->where('idno', '=', $insertGetId)
                            ->first();

            $product = DB::table('material.product')
                    ->where('compcode','=',session('compcode'))
                    ->where('uomcode','=',$request->uom)
                    ->where('itemcode','=',$request->chggroup);

            if($product->exists()){
                $product = $product->first();
                // if($product->groupcode == 'Stock'){

                    $stockloc = DB::table('material.stockloc')
                            ->where('compcode','=',session('compcode'))
                            ->where('uomcode','=',$request->uom)
                            ->where('itemcode','=',$request->chggroup)
                            ->where('deptcode','=',$dbacthdr->deptcode)
                            ->where('year','=',Carbon::now("Asia/Kuala_Lumpur")->year);

                    if($stockloc->exists()){
                        $stockloc = $stockloc->first();
                    }else{
                        throw new \Exception("Stockloc not exists for item: ".$billsum_obj->chggroup." dept: ".$dbacthdr->deptcode." uom: ".$billsum_obj->uom,500);
                    }

                    $ivdspdt = DB::table('material.ivdspdt')
                        ->where('compcode','=',session('compcode'))
                        ->where('recno','=',$billsum_obj->idno);

                    if($ivdspdt->exists()){
                        $this->updivdspdt($billsum_obj,$request,$dbacthdr);
                    }else{
                        $this->crtivdspdt($billsum_obj,$request,$dbacthdr);
                    }

                // }
            }

            ///3. calculate total amount from detail
            $totalAmount = DB::table('debtor.billsum')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','=',$source)
                    ->where('trantype','=',$trantype)
                    ->where('auditno','=',$auditno)
                    ->where('recstatus','!=','DELETE')
                    ->sum('amount');

            ///4. then update to header
            

            DB::table('debtor.dbacthdr')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','=',$source)
                    ->where('trantype','=',$trantype)
                    ->where('auditno','=',$auditno)
                    ->update([
                        'amount' => $totalAmount,
                    ]);

            echo $totalAmount;

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function edit(Request $request){

        DB::beginTransaction();

        try {

           // $invno = $this->recno('PB','INV'); buat lepas posted

            ///1. update detail
            DB::table('hisdb.billdet')
                ->where('compcode','=',session('compcode'))
                ->where('idno','=',$request->idno)
                //->where('lineno_','=',$request->lineno_)
                ->update([
                    'itemcode'=> strtoupper($request->itemcode), 
                    'uomcode'=> strtoupper($request->uomcode), 
                    'taxcode'=> strtoupper($request->taxcode), 
                    'unitprice'=> $request->unitprice,
                    'qtyrequest'=> $request->qtyrequest, 
                    'qtyonhand'=> $request->qtyonhand, 
                    'unitprice'=> $request->unitprice,
                    'percbilltype'=> $request->percbilltype, 
                    'amtbilltype'=> $request->amtbilltype, 
                    'amount'=> $request->amount,
                    'taxamt' => $request->taxamt, 
                    'upduser'=> session('username'), 
                    'upddate'=> Carbon::now("Asia/Kuala_Lumpur"), 
                    'remarks'=> strtoupper($request->remarks),
                    'unit' => session('unit')
                ]);

            ///2. recalculate total amount
            $totalAmount = DB::table('material.purreqdt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->where('recstatus','!=','DELETE')
                ->sum('totamount');

            //calculate tot gst from detail
            $tot_gst = DB::table('material.purreqdt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->where('recstatus','!=','DELETE')
                ->sum('amtslstax');

            ///3. update total amount to header
            DB::table('material.purreqhd')
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

            foreach ($request->dataobj as $key => $value) {
                //check unique
                $duplicate = DB::table('material.purreqdt')
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$request->recno)
                    ->where('lineno_','!=',$value['lineno_'])
                    ->where('itemcode','=',strtoupper($value['itemcode']))
                    ->where('uomcode','=',strtoupper($value['uomcode']))
                    ->where('pouom','=',strtoupper($value['pouom']))
                    ->exists();

                $has_prodmaster =  DB::table('material.productmaster')
                    ->where('compcode','=',session('compcode'))
                    ->where('itemcode','=',strtoupper($value['itemcode']))
                    ->exists();

                if($duplicate && $value['pricecode'] == 'MS'){
                    throw new \Exception("Duplicate item and uom of itemcode: ".strtoupper($value['itemcode']));
                }

                if(!$has_prodmaster){
                    throw new \Exception("Itemcode ".strtoupper($value['itemcode'])." doesnt have productmaster");
                }

                ///1. update detail
                DB::table('material.purreqdt')
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$request->recno)
                    ->where('lineno_','=',$value['lineno_'])
                    ->update([
                        'pricecode' => strtoupper($value['pricecode']), 
                        'itemcode'=> strtoupper($value['itemcode']), 
                        'uomcode'=> strtoupper($value['uomcode']), 
                        'pouom'=> strtoupper($value['pouom']), 
                        'qtyrequest'=> strtoupper($value['qtyrequest']),  
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
                        'remarks'=> strtoupper($value['remarks']),
                        'unit' => session('unit')
                    ]);
            }
            
            ///2. recalculate total amount
            $totalAmount = DB::table('material.purreqdt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->where('recstatus','!=','DELETE')
                ->sum('totamount');

            //calculate tot gst from detail
            $tot_gst = DB::table('material.purreqdt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->where('recstatus','!=','DELETE')
                ->sum('amtslstax');

            ///3. update total amount to header
            DB::table('material.purreqhd')
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
            DB::table('debtor.billsum')
                ->where('compcode','=',session('compcode'))
                ->where('idno','=',$request->idno)
                ->delete();

            ///2. recalculate total amount
            $amount = DB::table('debtor.billsum')
                ->where('compcode','=',session('compcode'))
                ->where('auditno','=',$request->auditno)
                ->where('recstatus','!=','DELETE')
                ->sum('amount');

            // //calculate tot gst from detail
            // $tot_gst = DB::table('debtor.billsum')
            //     ->where('compcode','=',session('compcode'))
            //     ->where('auditno','=',$request->auditno)
            //     ->where('recstatus','!=','DELETE')
            //     ->sum('amtslstax');

            ///3. update total amount to header
            DB::table('debtor.dbacthdr')
                ->where('compcode','=',session('compcode'))
                ->where('auditno','=',$request->auditno)
                ->where('source','=','PB')
                ->where('trantype','=','IN')
                ->update([
                    'amount' => $amount, 
                ]);

            echo $amount;

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
        
    }

    public function updivdspdt($billsum_obj,Request $request,$dbacthdr){

        $ivdspdt_lama = DB::table('material.ivdspdt')
            ->where('idno','=',$idno);

        $product = DB::table('material.product')
            ->where('compcode','=',session('compcode'))
            ->where('unit','=',session('unit'))
            ->where('uomcode','=',$request->uom)
            ->where('itemcode','=',$request->chggroup);

        $stockloc = DB::table('material.stockloc')
            ->where('compcode','=',session('compcode'))
            ->where('unit','=',session('unit'))
            ->where('uomcode','=',$request->uom)
            ->where('itemcode','=',$request->chggroup)
            ->where('deptcode','=',$dbacthdr->deptcode)
            ->where('year','=',Carbon::now("Asia/Kuala_Lumpur")->year);

        if($stockloc->exists()){

            $prev_netprice = $ivdspdt_lama->first()->netprice; 
            $prev_quan = $ivdspdt_lama->first()->txnqty;
            $curr_netprice = $billsum_obj->unitprice;
            $curr_quan = $billsum_obj->quantity;
            $qoh_quan = $stockloc->first()->qtyonhand;
            $new_qoh = floatval($qoh_quan) + floatval($prev_quan) - floatval($curr_quan);

            $stockloc_first = $stockloc->first();
            $stockloc_arr = (array)$stockloc_first;

            $month = defaultController::toMonth($dbacthdr->entrydate);
            $NetMvQty = floatval($stockloc_arr['netmvqty'.$month]) + floatval($prev_quan) - floatval($curr_quan);
            $NetMvVal = floatval($stockloc_arr['netmvval'.$month]) + floatval(floatval($prev_netprice) * floatval($prev_quan)) - floatval(floatval($curr_netprice) * floatval($curr_quan));

            $stockloc
                ->update([
                    'QtyOnHand' => $new_qoh,
                    'NetMvQty'.$month => $NetMvQty, 
                    'NetMvVal'.$month => $NetMvVal
                ]);

            $product
                ->update([
                    'qtyonhand' => $new_qoh,
                ]);

            //4. tolak expdate, kalu ada batchno
            $expdate_obj = DB::table('material.stockexp')
                ->where('Year','=',defaultController::toYear($dbacthdr->entrydate))
                ->where('DeptCode','=',$dbacthdr->deptcode)
                ->where('ItemCode','=',$request->chggroup)
                ->where('UomCode','=',$request->uom)
                ->orderBy('expdate', 'asc');

            if($expdate_obj->exists()){
                $expdate_first = $expdate_obj->first();
                $txnqty_ = $curr_quan;
                $balqty = floatval($expdate_first->balqty) + floatval($prev_quan) - floatval($curr_quan);
                $expdate_first
                        ->update([
                            'balqty' => $balqty
                        ]);

                // $balqty = 0;
                // foreach ($expdate_get as $value2) {
                //     $balqty = $value2->balqty;
                //     if($txnqty_-$balqty>0){
                //         $txnqty_ = $txnqty_-$balqty;
                //         DB::table('material.stockexp')
                //             ->where('idno','=',$value2->idno)
                //             ->update([
                //                 'balqty' => '0'
                //             ]);
                //     }else{
                //         $balqty = $balqty-$txnqty_;
                //         DB::table('material.stockexp')
                //             ->where('idno','=',$value2->idno)
                //             ->update([
                //                 'balqty' => $balqty
                //             ]);
                //         break;
                //     }
                // }

            }else{
                throw new \Exception("No stockexp");
            }

        }

        $ivdspdt_arr = [
            'txnqty' => $billsum_obj->quantity,
            'upduser' => session('username'),
            'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
            'netprice' => $billsum_obj->unitprice,
            'saleamt' => $billsum_obj->amount,
            'updtime' => Carbon::now("Asia/Kuala_Lumpur")
        ];

        DB::table('material.ivdspdt')
            ->where('idno','=',$idno)
            ->update($ivdspdt_arr);

        // $ivtxntype = DB::table('material.ivtxntype')
        //                 ->where('compcode','=', session('compcode'))
        //                 ->where('trantype','=', $stockloc->disptype)
        //                 ->first();

        // if($ivtxntype->updamt = 1){
        //     $category = DB::table('material.category')
        //                 ->where('compcode','=', session('compcode'))
        //                 ->where('catcode','=', $product->productcat)
        //                 ->first();

        //     $department = DB::table('sysdb.departmet')
        //                 ->where('compcode','=', session('compcode'))
        //                 ->where('deptcode','=',$dbacthdr->deptcode)
        //                 ->first();

        //     $ivdspdt_arr['DrCcode'] = $department->costcode;
        //     $ivdspdt_arr['DrAccNo'] = $category->cosacct;
        //     $ivdspdt_arr['CrCcode'] = $department->costcode;
        //     $ivdspdt_arr['CrAccNo'] = $category->stockacct;

        //     $glinface_arr = [
        //         'compcode' => session('compcode'),
        //         'Source' => 'IV',
        //         'TranType' => $stockloc->disptype,
        //         'AuditNo' => $insertGetId,
        //         'LineNo' => 1,
        //         'Reference' => $dbacthdr->deptcode.' - '.$billsum_obj->chggroup,
        //         'IdNo' => $dbacthdr->mrn.' - '.$dbacthdr->episno,
        //         'Description' => 'Posted from Online-Dispensing',
        //         'Amount' => $billsum_obj->amount,
        //         'PostDate' => $dbacthdr->entrydate,
        //         'OprType' => 'A',
        //         'LastUser' => session('username'),
        //         'LastUpdate' => Carbon::now("Asia/Kuala_Lumpur")
        //     ];

        //     if($ivtxntype->crdbfl = 'OUT'){
        //         $glinface_arr['DrCcode'] = $department->costcode;
        //         $glinface_arr['DrAccNo'] = $category->cosacct;
        //         $glinface_arr['CrCcode'] = $department->costcode;
        //         $glinface_arr['CrAccNo'] = $category->stockacct;
        //     }

        //     DB::table('finance.glinface')
        //         ->insert($glinface_arr);
        // }
    }

    public function crtivdspdt($billsum_obj,Request $request,$dbacthdr){

        $product = DB::table('material.product')
            ->where('compcode','=',session('compcode'))
            ->where('unit','=',session('unit'))
            ->where('uomcode','=',$request->uom)
            ->where('itemcode','=',$request->chggroup);

        $stockloc = DB::table('material.stockloc')
            ->where('compcode','=',session('compcode'))
            ->where('unit','=',session('unit'))
            ->where('uomcode','=',$request->uom)
            ->where('itemcode','=',$request->chggroup)
            ->where('deptcode','=',$dbacthdr->deptcode)
            ->where('year','=',Carbon::now("Asia/Kuala_Lumpur")->year);

        if($stockloc->exists()){
            $curr_netprice = $billsum_obj->unitprice;
            $curr_quan = $billsum_obj->quantity;
            $qoh_quan = $stockloc->first()->qtyonhand;
            $new_qoh = floatval($qoh_quan) - floatval($curr_quan);

            $stockloc_first = $stockloc->first();
            $stockloc_arr = (array)$stockloc_first;

            $month = defaultController::toMonth($dbacthdr->entrydate);
            $NetMvQty = floatval($stockloc_arr['netmvqty'.$month]) - floatval($curr_quan);
            $NetMvVal = floatval($stockloc_arr['netmvval'.$month]) - floatval(floatval($curr_netprice) * floatval($curr_quan));

            $stockloc
                ->update([
                    'QtyOnHand' => $new_qoh,
                    'NetMvQty'.$month => $NetMvQty, 
                    'NetMvVal'.$month => $NetMvVal
                ]);

            $product
                ->update([
                    'qtyonhand' => $new_qoh,
                ]);


            //4. tolak expdate, kalu ada batchno
            $expdate_obj = DB::table('material.stockexp')
                ->where('Year','=',defaultController::toYear($dbacthdr->entrydate))
                ->where('DeptCode','=',$dbacthdr->deptcode)
                ->where('ItemCode','=',$request->chggroup)
                ->where('UomCode','=',$request->uom)
                ->orderBy('expdate', 'asc');

            if($expdate_obj->exists()){
                $expdate_get = $expdate_obj->get();
                $txnqty_ = $curr_quan;
                $balqty = 0;
                foreach ($expdate_get as $value2) {
                    $balqty = $value2->balqty;
                    if($txnqty_-$balqty>0){
                        $txnqty_ = $txnqty_-$balqty;
                        DB::table('material.stockexp')
                            ->where('idno','=',$value2->idno)
                            ->update([
                                'balqty' => '0'
                            ]);
                    }else{
                        $balqty = $balqty-$txnqty_;
                        DB::table('material.stockexp')
                            ->where('idno','=',$value2->idno)
                            ->update([
                                'balqty' => $balqty
                            ]);
                        break;
                    }
                }

            }else{
                throw new \Exception("No stockexp");
            }


        }

        $ivdspdt_arr = [
            'compcode' => session('compcode'),
            'recno' => $billsum_obj->idno,
            'lineno_' => $billsum_obj->lineno_,
            'itemcode' => $billsum_obj->chggroup,
            'txnqty' => $billsum_obj->quantity,
            'adduser' => session('username'),
            'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
            'netprice' => $billsum_obj->unitprice,
            'productcat' => $product->first()->productcat,
            'reqdept' => $dbacthdr->deptcode,
            'saleamt' => $billsum_obj->amount,
            'trantype' => $billsum_obj->trantype,
            'trandate' => $dbacthdr->entrydate,
            'trxaudno' => $billsum_obj->idno,
            'mrn' => $this->givenullifempty($dbacthdr->mrn),
            'episno' => $this->givenullifempty($dbacthdr->episno),
            'updtime' => Carbon::now("Asia/Kuala_Lumpur")
        ];


        DB::table('material.ivdspdt')
                ->insert($ivdspdt_arr);

        // $ivtxntype = DB::table('material.ivtxntype')
        //                 ->where('compcode','=', session('compcode'))
        //                 ->where('trantype','=', $stockloc->disptype)
        //                 ->first();

        // if($ivtxntype->updamt = 1){
        //     $category = DB::table('material.category')
        //                 ->where('compcode','=', session('compcode'))
        //                 ->where('catcode','=', $product->productcat)
        //                 ->first();

        //     $department = DB::table('sysdb.departmet')
        //                 ->where('compcode','=', session('compcode'))
        //                 ->where('deptcode','=',$dbacthdr->deptcode)
        //                 ->first();

        //     $ivdspdt_arr['DrCcode'] = $department->costcode;
        //     $ivdspdt_arr['DrAccNo'] = $category->cosacct;
        //     $ivdspdt_arr['CrCcode'] = $department->costcode;
        //     $ivdspdt_arr['CrAccNo'] = $category->stockacct;

        //     $glinface_arr = [
        //         'compcode' => session('compcode'),
        //         'Source' => 'IV',
        //         'TranType' => $stockloc->disptype,
        //         'AuditNo' => $insertGetId,
        //         'LineNo' => 1,
        //         'Reference' => $dbacthdr->deptcode.' - '.$billsum_obj->chggroup,
        //         'IdNo' => $dbacthdr->mrn.' - '.$dbacthdr->episno,
        //         'Description' => 'Posted from Online-Dispensing',
        //         'Amount' => $billsum_obj->amount,
        //         'PostDate' => $dbacthdr->entrydate,
        //         'OprType' => 'A',
        //         'LastUser' => session('username'),
        //         'LastUpdate' => Carbon::now("Asia/Kuala_Lumpur")
        //     ];

        //     if($ivtxntype->crdbfl = 'OUT'){
        //         $glinface_arr['DrCcode'] = $department->costcode;
        //         $glinface_arr['DrAccNo'] = $category->cosacct;
        //         $glinface_arr['CrCcode'] = $department->costcode;
        //         $glinface_arr['CrAccNo'] = $category->stockacct;
        //     }

        //     DB::table('finance.glinface')
        //         ->insert($glinface_arr);
        // }
    }

}

