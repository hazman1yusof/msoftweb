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
                return $this->PurchaseOrderDetail($request);
            default:
                return 'error happen..';
        }
    }
    
    public function PurchaseOrderDetail(Request $request){
        $table = DB::table('material.purorddt AS podt')
                ->select('podt.compcode', 'podt.recno', 'podt.lineno_', 'podt.suppcode', 'podt.purdate','podt.pricecode', 'podt.itemcode', 'p.description','podt.uomcode','podt.pouom','podt.qtyorder','podt.qtydelivered','podt.qtyoutstand','podt.qtyrequest', 'podt.perslstax', 'podt.unitprice', 'podt.taxcode', 'podt.perdisc', 'podt.amtdisc','podt.amtslstax as tot_gst','podt.netunitprice','podt.totamount','podt.amount','podt.rem_but AS remarks_button','podt.remarks', 'podt.unit', 't.rate')
                ->leftJoin('material.productmaster AS p', function($join) use ($request){
                    $join = $join->on("podt.itemcode", '=', 'p.itemcode');    
                })
                ->leftJoin('hisdb.taxmast AS t', function($join) use ($request){
                    $join = $join->on("podt.taxcode", '=', 't.taxcode');    
                })
                ->where('podt.recno','=',$request->filterVal[0])
                ->where('podt.compcode','=',session('compcode'))
                ->where('podt.recstatus','<>','DELETE')
                ->orderBy('podt.idno','desc');

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
        // dd($paginate->items());

        $responce = new stdClass();
        $responce->page = $paginate->currentPage();
        $responce->total = $paginate->lastPage();
        $responce->records = $paginate->total();
        $responce->rows = $paginate->items();
        $responce->sql = $table->toSql();
        $responce->sql_bind = $table->getBindings();
        $responce->sql_query = $this->getQueries($table);

        return json_encode($responce);
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

            $purordhd = DB::table("material.purordhd")
                            ->where('idno','=',$request->idno)
                            ->where('compcode','=','DD');

            if($purordhd->exists()){
                $purordno = $this->request_no('PO',$purordhd->first()->prdept);
                $recno = $this->recno('PUR','PO');

                DB::table("material.purordhd")
                    ->where('idno','=',$request->idno)
                    ->update([
                        'purordno' => $purordno,
                        'recno' => $recno,
                        'compcode' => session('compcode'),
                    ]);
            }

            //check unique
            if($request->pricecode == 'MS'){
                $duplicate = DB::table('material.purreqdt')
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$recno)
                    ->where('itemcode','=',strtoupper($request->itemcode))
                    ->where('uomcode','=',strtoupper($request->uomcode))
                    ->where('pouom','=',strtoupper($request->pouom))
                    ->exists();

                if($duplicate){
                    throw new \Exception("Duplicate item and uom of itemcode: ".strtoupper($request->itemcode));
                }

            }

            $has_prodmaster =  DB::table('material.productmaster')
                ->where('compcode','=',session('compcode'))
                ->where('itemcode','=',strtoupper($request->itemcode))
                ->exists();

            
            if(!$has_prodmaster){
                throw new \Exception("Itemcode ".strtoupper($request->itemcode)." doesnt have productmaster");
            }
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
                    'purordno' => $purordno,
                    'pricecode' => strtoupper($request->pricecode), 
                    'itemcode'=> strtoupper($request->itemcode), 
                    'uomcode'=> strtoupper($request->uomcode), 
                    'pouom'=> strtoupper($request->pouom), 
                    'suppcode' => strtoupper($request->suppcode),
                    'purdate' => $request->purdate,
                    'qtyorder' => $request->qtyorder,
                    'qtydelivered' => $request->qtydelivered,
                    'qtyoutstand' => $request->qtyorder,
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
                    'remarks'=> $request->remarks,
                    'unit' => session('unit')

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



            $responce = new stdClass();
            $responce->totalAmount = $totalAmount;
            $responce->recno = $recno;
            $responce->purordno = $purordno;

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
            DB::table('material.purorddt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->where('lineno_','=',$request->lineno_)
                ->update([
                    'pricecode' => strtoupper($request->pricecode),
                    'itemcode' => strtoupper($request->itemcode),
                    'uomcode' => strtoupper($request->uomcode),
                    'pouom' => strtoupper($request->pouom),
                    'suppcode' => strtoupper($request->suppcode),
                    'purdate' => $request->purdate,
                    'qtyorder' => $request->qtyorder,
                    'qtydelivered' => $request->qtydelivered,
                    'qtyoutstand' => $request->qtyorder,
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
                    'remarks' => $request->remarks,
                    'prdept' => strtoupper($request->prdept),
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

            $this->check_incompleted($request->recno);
            
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
                ///1. update detail

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
                    throw new \Exception("Duplicate item and uom of itemcode: ".strtoupper($request->itemcode));
                }

                if(!$has_prodmaster){
                    throw new \Exception("Itemcode ".strtoupper($request->itemcode)." doesnt have productmaster");
                }

                DB::table('material.purorddt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->where('lineno_','=',$value['lineno_'])
                ->update([
                    'pricecode' => strtoupper($value['pricecode']),
                    'itemcode' => strtoupper($value['itemcode']),
                    'uomcode' => strtoupper($value['uomcode']),
                    'pouom' => strtoupper($value['pouom']),
                    'suppcode' => strtoupper($request->suppcode),
                    'purdate' => $request->purdate,
                    'qtyorder' => $value['qtyorder'],
                    // 'qtydelivered' => $value['qtydelivered'],
                    'qtyoutstand' => $value['qtyoutstand'],
                    'unitprice' => $value['unitprice'],
                    'taxcode' => strtoupper($value['taxcode']),
                    'perdisc' => $value['perdisc'],
                    'amtdisc' => $value['amtdisc'],
                    'amtslstax' => $value['tot_gst'],
                    'netunitprice' => $value['netunitprice'],
                    'amount' => $value['amount'],
                    'totamount' => $value['totamount'],
                    'adduser' => session('username'), 
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"),  
                    'recstatus' => 'OPEN', 
                    'remarks'=> $value['remarks'],
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

            $this->check_incompleted($request->recno);
            
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

            $this->check_incompleted($request->recno);

            echo $totalAmount;

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }

        
    }

    function check_incompleted($recno){

        $incompleted = false;
        $purorddt_null = DB::table('material.purorddt')
                            ->where('compcode','=',session('compcode'))
                            ->where('recno','=',$recno)
                            ->where('recstatus','<>','DELETE')
                            ->where(function ($purorddt_null){
                                $purorddt_null
                                        ->whereNull('unitprice')
                                        ->orWhereNull('pouom'); 
                            });
        $purorddt_empty = DB::table('material.purorddt')
                            ->where('compcode','=',session('compcode'))
                            ->where('recno','=',$recno)
                            ->where('recstatus','<>','DELETE')
                            ->where(function ($purorddt_empty){
                                $purorddt_empty
                                    ->where('unitprice','=','0.00')
                                    ->orWhere('pouom','=','');   
                            });

        if($purorddt_null->exists() || $purorddt_empty->exists()){
            $incompleted = true;
        }

        if($incompleted){
            DB::table('material.purordhd')
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$recno)
                    ->update([
                        'recstatus' => 'INCOMPLETED'
                    ]);
        }else{
            DB::table('material.purordhd')
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$recno)
                    ->update([
                        'recstatus' => 'OPEN'
                    ]);
        }
    }

    public function delete_dd(Request $request){
        DB::table('material.purordhd')
                ->where('idno','=',$request->idno)
                ->where('compcode','=','DD')
                ->delete();
    }

}

