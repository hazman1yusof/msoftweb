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
                    ->where('billno','=',$request->billno)
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
                            // $table->Where($searchCol_array[$key],'like',$request->searchVal[$key]);
                            $table->Where('cm.'.$searchCol_array[$key],'like',$request->searchVal[$key]);
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
                    // $table->orwhere($searchCol_array[$key],'like', $request->searchVal2[$key]);
                    $table->orwhere('cm.'.$searchCol_array[$key],'like', $request->searchVal2[$key]);
                }
            });

            if(count($searchCol_array)>2){
                $table = $table->where(function($table) use ($searchCol_array, $request){
                    foreach ($searchCol_array as $key => $value) {
                        if($key<=1) continue;
                        // $table->orwhere($searchCol_array[$key],'like', $request->searchVal2[$key]);
                        $table->orwhere('cm.'.$searchCol_array[$key],'like', $request->searchVal2[$key]);
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

        $recno = $this->recno('OE','IN');

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
                    'auditno' => $recno, //->OE IN
                    'billno' => $auditno,
                    // 'idno' => $recno, //autogen
                    'compcode' => session('compcode'),
                    'source' => $source,
                    'trantype' => $trantype,
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
                            ->where('id', '=', $insertGetId)
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
                        ->where('recno','=',$billsum_obj->auditno);

                    if($ivdspdt->exists()){
                        $this->updivdspdt($billsum_obj,$request,$dbacthdr);
                        $this->updgltran($ivdspdt->first()->idno,$request,$dbacthdr);
                    }else{
                        $ivdspdt_idno = $this->crtivdspdt($billsum_obj,$request,$dbacthdr);
                        $this->crtgltran($ivdspdt_idno,$request,$dbacthdr);
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

    public function edit_all(Request $request){

        DB::beginTransaction();

        try {

            $source = $request->source;
            $trantype = $request->trantype;
            $auditno = $request->auditno;

            $dbacthdr = DB::table('debtor.dbacthdr')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','=',$source)
                    ->where('trantype','=',$trantype)
                    ->where('auditno','=',$auditno);

            $dbacthdr = $dbacthdr->first();


            foreach ($request->dataobj as $key => $value) {

                $billsum = DB::table('debtor.billsum')
                            ->where('compcode','=',session('compcode'))
                            ->where('source','=',$source)
                            ->where('trantype','=',$trantype)
                            ->where('auditno','=',$auditno)
                            ->where('lineno_','=',$value['lineno_']);

                ///2. update detail
                $billsum
                    ->update([
                        'unitprice' => $value['unitprice'],
                        'quantity' => $value['quantity'],
                        'amount' => $value['amount'],
                        'discamt' => floatval($value['discamt']),
                        'taxamt' => floatval($value['taxamt']),
                        'lastuser' => session('username'), 
                        'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                        // 'billtypeperct' => $value['billtypeperct'],
                        // 'billtypeamt' => $value['billtypeamt'],
                    ]);

                $billsum_obj = $billsum->first();

                $product = DB::table('material.product')
                        ->where('compcode','=',session('compcode'))
                        ->where('uomcode','=',$value['uom'])
                        ->where('itemcode','=',$value['chggroup']);

                if($product->exists()){
                    $product = $product->first();
                    // if($product->groupcode == 'Stock'){

                        $stockloc = DB::table('material.stockloc')
                                ->where('compcode','=',session('compcode'))
                                ->where('uomcode','=',$value['uom'])
                                ->where('itemcode','=',$value['chggroup'])
                                ->where('deptcode','=',$dbacthdr->deptcode)
                                ->where('year','=',Carbon::now("Asia/Kuala_Lumpur")->year);

                        if($stockloc->exists()){
                            $stockloc = $stockloc->first();
                        }else{
                            throw new \Exception("Stockloc not exists for item: ".$value['chggroup']." dept: ".$dbacthdr->deptcode." uom: ".$value['uom'],500);
                        }

                        $ivdspdt = DB::table('material.ivdspdt')
                            ->where('compcode','=',session('compcode'))
                            ->where('recno','=',$billsum_obj->auditno);

                        if($ivdspdt->exists()){
                            $this->updivdspdt($billsum_obj,$request,$dbacthdr);
                            $this->updgltran($ivdspdt->first()->idno,$request,$dbacthdr);
                        }else{
                            $ivdspdt_idno = $this->crtivdspdt($billsum_obj,$request,$dbacthdr);
                            $this->crtgltran($ivdspdt_idno,$request,$dbacthdr);
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
            }

            DB::commit();

            $responce = new stdClass();
            $responce->totalAmount = $totalAmount;

            return json_encode($responce);

        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }

    }

    public function del(Request $request){

        DB::beginTransaction();

        try {

            $source = $request->source;
            $trantype = $request->trantype;
            $auditno = $request->auditno;
            $idno = $request->idno;

            $dbacthdr = DB::table('debtor.dbacthdr')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','=',$source)
                    ->where('trantype','=',$trantype)
                    ->where('auditno','=',$auditno);

            $dbacthdr = $dbacthdr->first();

            $billsum = DB::table('debtor.billsum')
                            ->where('idno','=',$idno);

            $billsum_obj = $billsum->first();
            
            $this->delivdspdt($billsum_obj,$request,$dbacthdr);

            $billsum->update([
                        'quantity' => 0,
                        'lastuser' => session('username'), 
                        'lastupdate' => Carbon::now("Asia/Kuala_Lumpur")
                    ]);

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

            DB::commit();

            $responce = new stdClass();
            $responce->totalAmount = $totalAmount;

            return json_encode($responce);

        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
        
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
            $curr_netprice = $product->first()->avgcost;
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

            $sumqtyonhand = DB::table('material.stockloc')
                                ->select(DB::raw('SUM(qtyonhand) AS sum_qtyonhand'))
                                ->where('compcode','=',session('compcode'))
                                ->where('unit','=',session('unit'))
                                ->where('uomcode','=',$request->uom)
                                ->where('itemcode','=',$request->chggroup)
                                ->where('year','=',Carbon::now("Asia/Kuala_Lumpur")->year)
                                ->first();

            $product
                ->update([
                    'qtyonhand' => $sumqtyonhand->sum_qtyonhand,
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
            'recno' => $billsum_obj->auditno,//OE IN
            'lineno_' => 1,
            'itemcode' => $billsum_obj->chggroup,
            'uomcode' => $billsum_obj->uom,
            'txnqty' => $billsum_obj->quantity,
            'adduser' => session('username'),
            'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
            'netprice' => $billsum_obj->unitprice,
            'productcat' => $product->first()->productcat,
            'isudept' => $dbacthdr->deptcode,
            'reqdept' => $dbacthdr->deptcode,
            'amount' => floatval(floatval($curr_netprice) * floatval($curr_quan)),
            'trantype' => 'DS',
            'trandate' => Carbon::now("Asia/Kuala_Lumpur"),
            'trxaudno' => $billsum_obj->auditno,
            'mrn' => $this->givenullifempty($dbacthdr->mrn),
            'episno' => $this->givenullifempty($dbacthdr->episno),
            'updtime' => Carbon::now("Asia/Kuala_Lumpur")
        ];


        $insertGetId = DB::table('material.ivdspdt')
                            ->insertGetId($ivdspdt_arr);

        return $insertGetId;

        
    }

    public function updivdspdt($billsum_obj,Request $request,$dbacthdr){

        $ivdspdt_lama = DB::table('material.ivdspdt')
            ->where('compcode','=',session('compcode'))
            ->where('recno','=',$billsum_obj->idno);

        $product = DB::table('material.product')
            ->where('compcode','=',session('compcode'))
            ->where('unit','=',session('unit'))
            ->where('uomcode','=',$billsum_obj->uom)
            ->where('itemcode','=',$billsum_obj->chggroup);

        $stockloc = DB::table('material.stockloc')
            ->where('compcode','=',session('compcode'))
            ->where('unit','=',session('unit'))
            ->where('uomcode','=',$billsum_obj->uom)
            ->where('itemcode','=',$billsum_obj->chggroup)
            ->where('deptcode','=',$dbacthdr->deptcode)
            ->where('year','=',Carbon::now("Asia/Kuala_Lumpur")->year);

        if($stockloc->exists()){

            $prev_netprice = $product->first()->avgcost; 
            $prev_quan = $ivdspdt_lama->first()->txnqty;
            $curr_netprice = $product->first()->avgcost;
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

            $sumqtyonhand = DB::table('material.stockloc')
                                ->select(DB::raw('SUM(qtyonhand) AS sum_qtyonhand'))
                                ->where('compcode','=',session('compcode'))
                                ->where('unit','=',session('unit'))
                                ->where('uomcode','=',$request->uom)
                                ->where('itemcode','=',$request->chggroup)
                                ->where('year','=',Carbon::now("Asia/Kuala_Lumpur")->year)
                                ->first();

            $product
                ->update([
                    'qtyonhand' => $sumqtyonhand->sum_qtyonhand,
                ]);

            //4. tolak expdate, kalu ada batchno
            $expdate_obj = DB::table('material.stockexp')
                ->where('Year','=',defaultController::toYear($dbacthdr->entrydate))
                ->where('DeptCode','=',$dbacthdr->deptcode)
                ->where('ItemCode','=',$billsum_obj->chggroup)
                ->where('UomCode','=',$billsum_obj->uom)
                ->orderBy('expdate', 'asc');

            if($expdate_obj->exists()){
                $expdate_first = $expdate_obj->first();
                $txnqty_ = $curr_quan;
                $balqty = floatval($expdate_first->balqty) + floatval($prev_quan) - floatval($curr_quan);
                $expdate_obj
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
            'amount' => floatval(floatval($curr_netprice) * floatval($curr_quan)),
            'updtime' => Carbon::now("Asia/Kuala_Lumpur")
        ];

        DB::table('material.ivdspdt')
            ->where('compcode','=',session('compcode'))
            ->where('recno','=',$billsum_obj->idno)
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

    public function delivdspdt($billsum_obj,Request $request,$dbacthdr){

        $ivdspdt_lama = DB::table('material.ivdspdt')
            ->where('compcode','=',session('compcode'))
            ->where('recno','=',$billsum_obj->idno);

        $product = DB::table('material.product')
            ->where('compcode','=',session('compcode'))
            ->where('unit','=',session('unit'))
            ->where('uomcode','=',$billsum_obj->uom)
            ->where('itemcode','=',$billsum_obj->chggroup);

        $stockloc = DB::table('material.stockloc')
            ->where('compcode','=',session('compcode'))
            ->where('unit','=',session('unit'))
            ->where('uomcode','=',$billsum_obj->uom)
            ->where('itemcode','=',$billsum_obj->chggroup)
            ->where('deptcode','=',$dbacthdr->deptcode)
            ->where('year','=',Carbon::now("Asia/Kuala_Lumpur")->year);

        if($stockloc->exists()){

            $prev_netprice = $ivdspdt_lama->first()->netprice; 
            $prev_quan = $ivdspdt_lama->first()->txnqty;
            $curr_netprice = $billsum_obj->unitprice;
            $curr_quan = $billsum_obj->quantity;
            $qoh_quan = $stockloc->first()->qtyonhand;
            $new_qoh = floatval($qoh_quan) + floatval($prev_quan);

            $stockloc_first = $stockloc->first();
            $stockloc_arr = (array)$stockloc_first;

            $month = defaultController::toMonth($dbacthdr->entrydate);
            $NetMvQty = floatval($stockloc_arr['netmvqty'.$month]) + floatval($prev_quan);
            $NetMvVal = floatval($stockloc_arr['netmvval'.$month]) + floatval(floatval($prev_netprice) * floatval($prev_quan));

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
                ->where('ItemCode','=',$billsum_obj->chggroup)
                ->where('UomCode','=',$billsum_obj->uom)
                ->orderBy('expdate', 'asc');

            if($expdate_obj->exists()){
                $expdate_first = $expdate_obj->first();
                $txnqty_ = $curr_quan;
                $balqty = floatval($expdate_first->balqty) + floatval($prev_quan);
                $expdate_obj
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

        DB::table('material.ivdspdt')
            ->where('compcode','=',session('compcode'))
            ->where('recno','=',$billsum_obj->idno)
            ->update([
                'txnqty' => 0,
                'upduser' => session('username'),
                'updddate' => Carbon::now("Asia/Kuala_Lumpur"),
                'updtime' => Carbon::now("Asia/Kuala_Lumpur")
            ]);

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

    public function crtgltran($ivdspdt_idno,Request $request,$dbacthdr){
        $yearperiod = $this->getyearperiod(Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d'));

        $ivdspdt = DB::table('material.ivdspdt')
                        ->where('idno','=',$ivdspdt_idno)
                        ->first();

        //tengok product category
        $product_obj = DB::table('material.product')
            ->where('compcode','=', session('compcode'))
            ->where('unit','=', session('unit'))
            ->where('itemcode','=', $ivdspdt->itemcode)
            ->first();

        $row_dept = DB::table('sysdb.department')
            ->select('costcode')
            ->where('compcode','=',session('compcode'))
            ->where('deptcode','=',$dbacthdr->deptcode)
            ->first();
        //utk debit accountcode
        $row_cat = DB::table('material.category')
            ->select('stockacct','cosacct')
            ->where('compcode','=',session('compcode'))
            ->where('catcode','=',$product_obj->productcat)
            ->first();

        $drcostcode = $row_dept->costcode;
        $dracc = $row_cat->cosacct;
        $crcostcode = $row_dept->costcode;
        $cracc = $row_cat->stockacct;

        //1. buat gltran
        DB::table('finance.gltran')
            ->insert([
                'compcode' => session('compcode'),
                'adduser' => session('username'),
                'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                'auditno' => $ivdspdt->recno,
                'lineno_' => 1,
                'source' => 'IV', //kalau stock 'IV', lain dari stock 'DO'
                'trantype' => 'DS',
                'reference' => $ivdspdt->uomcode,
                'description' => $ivdspdt->itemcode, 
                'postdate' => Carbon::now("Asia/Kuala_Lumpur"),
                'year' => $yearperiod->year,
                'period' => $yearperiod->period,
                'drcostcode' => $drcostcode,
                'dracc' => $dracc,
                'crcostcode' => $crcostcode,
                'cracc' => $cracc,
                'amount' => $ivdspdt->amount
            ]);

        //2. check glmastdtl utk debit, kalu ada update kalu xde create
        $gltranAmount =  $this->isGltranExist($drcostcode,$dracc,$yearperiod->year,$yearperiod->period);

        if($gltranAmount!==false){
            DB::table('finance.glmasdtl')
                ->where('compcode','=',session('compcode'))
                ->where('costcode','=',$drcostcode)
                ->where('glaccount','=',$dracc)
                ->where('year','=',$yearperiod->year)
                ->update([
                    'upduser' => session('username'),
                    'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'actamount'.$yearperiod->period => $ivdspdt->amount + $gltranAmount,
                    'recstatus' => 'ACTIVE'
                ]);
        }else{
            DB::table('finance.glmasdtl')
                ->insert([
                    'compcode' => session('compcode'),
                    'costcode' => $drcostcode,
                    'glaccount' => $dracc,
                    'year' => $yearperiod->year,
                    'actamount'.$yearperiod->period => $ivdspdt->amount,
                    'adduser' => session('username'),
                    'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'recstatus' => 'ACTIVE'
                ]);
        }

        //3. check glmastdtl utk credit pulak, kalu ada update kalu xde create
        $gltranAmount = defaultController::isGltranExist_($crcostcode,$cracc,$yearperiod->year,$yearperiod->period);

        if($gltranAmount!==false){
            DB::table('finance.glmasdtl')
                ->where('compcode','=',session('compcode'))
                ->where('costcode','=',$crcostcode)
                ->where('glaccount','=',$cracc)
                ->where('year','=',$yearperiod->year)
                ->update([
                    'upduser' => session('username'),
                    'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'actamount'.$yearperiod->period => $gltranAmount - $ivdspdt->amount,
                    'recstatus' => 'ACTIVE'
                ]);
        }else{
            DB::table('finance.glmasdtl')
                ->insert([
                    'compcode' => session('compcode'),
                    'costcode' => $crcostcode,
                    'glaccount' => $cracc,
                    'year' => $yearperiod->year,
                    'actamount'.$yearperiod->period => -$ivdspdt->amount,
                    'adduser' => session('username'),
                    'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'recstatus' => 'ACTIVE'
                ]);
        }
    }

    public function updgltran($ivdspdt_idno,Request $request,$dbacthdr){
        $ivdspdt = DB::table('material.ivdspdt')
                        ->where('idno','=',$ivdspdt_idno)
                        ->first();

        $gltran = DB::table('finance.gltran')
                    ->where('compcode','=',session('compcode'))
                    ->where('auditno','=',$ivdspdt->recno);

        if($gltran->exists){
            $gltran_first = $gltran->first();

            $OldAmount = $gltran_first->amount;
            $yearperiod = $this->getyearperiod($ivdspdt->trandate);
            $drcostcode = $gltran_first->drcostcode;
            $dracc = $gltran_first->dracc;
            $crcostcode = $gltran_first->crcostcode;
            $cracc = $gltran_first->cracc;

            $gltran->update([
                'amount' => $ivdspdt->amount
            ]);

            //2. check glmastdtl utk debit, kalu ada update kalu xde create
            $gltranAmount =  $this->isGltranExist($drcostcode,$dracc,$yearperiod->year,$yearperiod->period);

            if($gltranAmount!==false){
                DB::table('finance.glmasdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('costcode','=',$drcostcode)
                    ->where('glaccount','=',$dracc)
                    ->where('year','=',$yearperiod->year)
                    ->update([
                        'upduser' => session('username'),
                        'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                        'actamount'.$yearperiod->period => $gltranAmount - $OldAmount + $ivdspdt->amount,
                        'recstatus' => 'ACTIVE'
                    ]);
            }else{
                DB::table('finance.glmasdtl')
                    ->insert([
                        'compcode' => session('compcode'),
                        'costcode' => $drcostcode,
                        'glaccount' => $dracc,
                        'year' => $yearperiod->year,
                        'actamount'.$yearperiod->period => $ivdspdt->amount,
                        'adduser' => session('username'),
                        'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                        'recstatus' => 'ACTIVE'
                    ]);
            }

            //3. check glmastdtl utk credit pulak, kalu ada update kalu xde create
            $gltranAmount = defaultController::isGltranExist_($crcostcode,$cracc,$yearperiod->year,$yearperiod->period);

            if($gltranAmount!==false){
                DB::table('finance.glmasdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('costcode','=',$crcostcode)
                    ->where('glaccount','=',$cracc)
                    ->where('year','=',$yearperiod->year)
                    ->update([
                        'upduser' => session('username'),
                        'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                        'actamount'.$yearperiod->period => $gltranAmount + $OldAmount - $ivdspdt->amount,
                        'recstatus' => 'ACTIVE'
                    ]);
            }else{
                DB::table('finance.glmasdtl')
                    ->insert([
                        'compcode' => session('compcode'),
                        'costcode' => $crcostcode,
                        'glaccount' => $cracc,
                        'year' => $yearperiod->year,
                        'actamount'.$yearperiod->period => -$ivdspdt->amount,
                        'adduser' => session('username'),
                        'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                        'recstatus' => 'ACTIVE'
                    ]);
            }

        }else{
            throw new \Exception("Gltran doesnt exists");
        }
    }

}

