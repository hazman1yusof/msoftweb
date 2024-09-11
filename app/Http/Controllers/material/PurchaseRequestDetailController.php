<?php

namespace App\Http\Controllers\material;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class PurchaseRequestDetailController extends defaultController
{   
    var $gltranAmount;
    var $purreqno;

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

               /* if($purordhd->purordno != 0){
                    // return 'edit all srcdocno !=0';
                    return $this->edit_all_from_PO($request);
                }else{
                    // return 'edit all biasa';*/
                    return $this->edit_all($request);
               // }    
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
                return $this->get_table_dtl($request);
            case 'get_table_dialog_itemcode':
                return $this->get_table_dialog_itemcode($request);
            default:
                return 'error happen..';
        }
    }

    public function get_table_dtl(Request $request){
        if(empty($request->filterVal[0])){
            $responce = new stdClass();
            $responce->page = 0;
            $responce->total = 0;
            $responce->records = 0;
            $responce->rows = [];
            $responce->sql = 0;
            $responce->sql_bind = 0;

            return json_encode($responce);
        }

        $table = DB::table('material.purreqdt as prdt')
                    ->select('prdt.compcode', 'prdt.recno', 'prdt.lineno_', 'prdt.pricecode', 'prdt.itemcode', 'p.description', 'prdt.uomcode', 'prdt.pouom', 'prdt.qtyrequest' , 'prdt.qtybalance', 'prdt.qtyapproved', 'prdt.unitprice', 'prdt.taxcode', 'prdt.perdisc', 'prdt.amtdisc', 'prdt.amtslstax as tot_gst','prdt.netunitprice', 'prdt.totamount','prdt.amount', 'prdt.rem_but AS remarks_button', 'prdt.remarks', 'prdt.recstatus', 'prdt.unit', 't.rate')
                    ->leftJoin('material.productmaster AS p', function($join) use ($request){
                        $join = $join->on("prdt.itemcode", '=', 'p.itemcode');
                        $join = $join->where("p.compcode", '=', session('compcode'));
                    })
                    ->leftJoin('hisdb.taxmast AS t', function($join) use ($request){
                        $join = $join->on("prdt.taxcode", '=', 't.taxcode');    
                    })
                    ->where('prdt.recno','=',$request->filterVal[0])
                    ->where('prdt.compcode','=',session('compcode'))
                    ->where('prdt.recstatus','<>','DELETE')
                    ->orderBy('prdt.idno','desc');

        //////////paginate/////////
        $paginate = $table->paginate($request->rows);

        foreach ($paginate->items() as $key => $value) {//ini baru
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

    public function get_table_dialog_itemcode(Request $request){
        
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
        try {

            $purreqhd = DB::table("material.purreqhd")
                            ->where('idno','=',$request->idno)
                            ->where('compcode','=','DD');

            if($purreqhd->exists()){
                $purreqno = $this->request_no('PR', $purreqhd->first()->reqdept);
                $recno = $this->recno('PUR','PR');

                $purreqhd = DB::table("material.purreqhd")
                                ->where('idno','=',$request->idno)
                                ->update([
                                    'purreqno' => $purreqno,
                                    'recno' => $recno,
                                    'compcode' => session('compcode'),
                                ]);
            }else{

                $purreqno = $request->purreqno;
                $recno = $request->recno;

                if($purreqno == 0 || $recno == 0){

                    $purreqno = $this->request_no('PR', $request->reqdept);
                    $recno = $this->recno('PUR','PR');

                    $purreqhd = DB::table("material.purreqhd")
                                    ->where('idno','=',$request->idno)
                                    ->update([
                                        'purreqno' => $purreqno,
                                        'recno' => $recno,
                                        'compcode' => session('compcode'),
                                    ]);
                }

            }

            $purreqhd = DB::table("material.purreqhd")
                            ->where('idno','=',$request->idno)
                            ->first();

            //$suppcode = $request->suppcode;
            $purreqdt = $request->purreqdt;
            $reqdept = $request->reqdept;
            $duplicate = false;

            //check unique
            if($request->pricecode == 'MS'){
                $duplicate = DB::table('material.purreqdt')
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$recno)
                    ->where('itemcode','=',strtoupper($request->itemcode))
                    ->where('uomcode','=',strtoupper($request->uomcode))
                    ->where('pouom','=',strtoupper($request->pouom))
                    ->exists();
            }

            $has_prodmaster =  DB::table('material.productmaster')
                ->where('compcode','=',session('compcode'))
                ->where('itemcode','=',strtoupper($request->itemcode))
                ->exists();
        
            if($duplicate && $request->pricecode == 'MS'){
                throw new \Exception("Duplicate item and uom of itemcode: ".strtoupper($request->itemcode));
            }

            if(!$has_prodmaster){
                throw new \Exception("Itemcode ".strtoupper($request->itemcode)." doesnt have productmaster");
            }

            //check correct groupcode
            $prtype = $purreqhd->prtype;
            $reqdept_unit = DB::table('sysdb.department')
                            ->where('compcode',session('compcode'))
                            ->where('deptcode',$purreqhd->reqdept)
                            ->first();

            $reqdept_unit = $reqdept_unit->sector;

            if($prtype == 'Stock'){
                $product = DB::table('material.stockloc as s')
                            ->leftJoin('material.product AS p', function($join) use ($reqdept_unit){
                                $join = $join->on("p.itemcode", '=', 's.itemcode');
                                $join = $join->on("p.uomcode", '=', 's.uomcode');
                                $join = $join->where("p.unit", '=', $reqdept_unit);
                                $join = $join->where("p.compcode", '=', session('compcode'));
                            })
                            ->where('s.unit','=',$reqdept_unit)
                            ->where('s.compcode','=',session('compcode'))
                            ->where('s.year','=',Carbon::now("Asia/Kuala_Lumpur")->year)
                            ->where('s.deptcode','=',$purreqhd->reqdept)
                            ->where('s.itemcode','=',$request->itemcode)
                            ->where('s.uomcode','=',$request->uomcode)
                            ->whereIn('p.groupcode',['STOCK','CONSIGNMENT']);
                            
                if(!$product->exists()){
                    throw new \Exception("Itemcode $request->itemcode - $request->uomcode - $purreqhd->reqdept , doesnt have stockloc or product");
                }

            }else if($prtype == 'Asset'){
                $product = DB::table('material.product AS p')
                            ->where('p.compcode','=',session('compcode'))
                            ->where('p.itemcode','=',$request->itemcode)
                            ->where('p.uomcode','=',$request->uomcode)
                            ->whereIn('p.groupcode',['ASSET']);
            }else{
                $product = DB::table('material.product AS p')
                            ->where('p.compcode','=',session('compcode'))
                            ->where('p.itemcode','=',$request->itemcode)
                            ->where('p.uomcode','=',$request->uomcode)
                            ->whereIn('p.groupcode',['OTHERS']);
            }

            if(!$product->exists()){
                throw new \Exception("Itemcode $request->itemcode - $request->uomcode , doesnt have product");
            }

            ////1. calculate lineno_ by recno
            $sqlln = DB::table('material.purreqdt')->select('lineno_')
                        ->where('compcode','=',session('compcode'))
                        ->where('recno','=',$recno)
                        ->max('lineno_');

            $li=intval($sqlln)+1;

            ///2. insert detail
            DB::table('material.purreqdt')
                ->insert([
                    'compcode' => session('compcode'),
                    'purreqno' => $purreqno,
                    'recno' => $recno,
                    'lineno_' => $li,
                    'pricecode' => strtoupper($request->pricecode),
                    'itemcode' => strtoupper($request->itemcode),
                    'uomcode' => strtoupper($request->uomcode),
                    'pouom' => strtoupper($request->pouom),
                   // 'suppcode' => $request->suppcode,
                    'reqdept' => strtoupper($request->reqdept),
                    'qtyrequest' => $request->qtyrequest,
                    'qtyapproved' => 0,
                    'qtybalance' => $request->qtyrequest,
                    'unitprice' => $request->unitprice,
                    'taxcode' => strtoupper($request->taxcode),
                    'perdisc' => $request->perdisc,
                    'amtdisc' => $request->amtdisc,
                    'amtslstax' => $request->tot_gst,
                    'netunitprice' => $request->netunitprice,
                    'amount' => $request->amount,
                    'totamount' => $request->totamount,
                    'adduser' => session('username'), 
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                    'recstatus' => 'OPEN', 
                    'remarks' => strtoupper($request->remarks),
                    'unit' => session('unit')
                ]);

            ///3. calculate total amount from detail
            $totalAmount = DB::table('material.purreqdt')
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$recno)
                    ->where('recstatus','!=','DELETE')
                    ->sum('totamount');

            //calculate tot gst from detail
            $tot_gst = DB::table('material.purreqdt')
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$recno)
                    ->where('recstatus','!=','DELETE')
                    ->sum('amtslstax');

            ///4. then update to header
            DB::table('material.purreqhd')
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
            $responce->purreqno = $purreqno;

            DB::commit();

            return json_encode($responce);

        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function edit(Request $request){

        DB::beginTransaction();

        try {


            ///1. update detail
            DB::table('material.purreqdt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->where('lineno_','=',$request->lineno_)
                ->update([
                    'pricecode' => strtoupper($request->pricecode), 
                    'itemcode'=> strtoupper($request->itemcode), 
                    'uomcode'=> strtoupper($request->uomcode), 
                    'pouom'=> strtoupper($request->pouom), 
                    'qtyrequest'=> $request->qtyrequest, 
                    'qtyapproved' => 0,
                    'qtybalance' => $request->qtyrequest,
                    'unitprice'=> $request->unitprice,
                    'taxcode'=> $request->taxcode, 
                    'perdisc'=> $request->perdisc, 
                    'amtdisc'=> $request->amtdisc, 
                    'amtslstax'=> $request->tot_gst, 
                    'netunitprice'=> $request->netunitprice, 
                    'amount'=> $request->amount, 
                    'totamount'=> $request->totamount, 
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
                        // 'pricecode' => strtoupper($value['pricecode']), 
                        // 'itemcode'=> strtoupper($value['itemcode']), 
                        // 'uomcode'=> strtoupper($value['uomcode']), 
                        'pouom'=> strtoupper($value['pouom']), 
                        'qtyrequest'=> strtoupper($value['qtyrequest']),  
                        'qtyapproved' => 0,
                        'qtybalance' => $request->qtyrequest,
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
            DB::table('material.purreqdt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->where('lineno_','=',$request->lineno_)
                ->delete();

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

    public function delete_dd(Request $request){
        DB::table('material.purreqhd')
                ->where('idno','=',$request->idno)
                ->where('compcode','=','DD')
                ->delete();
    }

}

