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
        $table = DB::table('material.stockloc AS s')
                    ->join('material.product AS p', function($join) use ($request){
                            $join = $join->on('s.itemcode','=','p.itemcode');
                        })
                    ->where('s.compcode','=',session('compcode'))
                    ->where('s.recstatus','<>','DELETE')
                    ->orderBy('s.idno','desc');

        if(!empty($request->searchCol)){
             $table = $table->where('p.'.$request->searchCol[0],'LIKE',$request->searchVal[0]);
        }

        $paginate = $table->paginate($request->rows);
        $rows = $paginate->items();


        foreach ($rows as $key => $value) {
            $price_obj = DB::table('hisdb.chgprice')
                        ->where('compcode', '=', session('compcode'))
                        ->where('chgcode', '=', $value->itemcode)
                        ->whereDate('effdate', '<=', Carbon::now('Asia/Kuala_Lumpur'))
                        ->orderBy('effdate','desc');

            if($price_obj->exists()){
                $price_obj = $price_obj->first();
                $rows[$key]->price = $price_obj->amt1;
            }
        }

        //////////paginate/////////
        // $paginate = $table->paginate($request->rows);

        $responce = new stdClass();
        $responce->page = $paginate->currentPage();
        $responce->total = $paginate->lastPage();
        $responce->records = $paginate->total();
        $responce->rows = $paginate->items();
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
            ////1. calculate lineno_ by recno
            $sqlln = DB::table('debtor.billsum')->select('lineno_')
                        ->where('compcode','=',session('compcode'))
                        ->where('source','=',$source)
                        ->where('trantype','=',$trantype)
                        ->where('auditno','=',$auditno)
                        ->count('lineno_');

            $li=intval($sqlln)+1;

            ///2. insert detail
            DB::table('debtor.billsum')
                ->insert([
                    'compcode' => session('compcode'),
                    'source' => $source,
                    'trantype' => $trantype,
                    'auditno' => $auditno,
                    'lineno_' => $li,
                    'uom' => strtoupper($request->uomcode),
                    'unitprice' => $request->unitprice,
                    'quantity' => $request->qtyrequest,
                    'amount' => $request->amount,
                    'discamt' => $request->discamt,
                    'lastuser' => session('username'), 
                    'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"), 
                    'recstatus' => 'OPEN'
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


            ///1. update detail
            DB::table('hisdb.billdet')
                ->where('compcode','=',session('compcode'))
                ->where('idno','=',$request->idno)
                //->where('lineno_','=',$request->lineno_)
                ->update([
                    'itemcode'=> strtoupper($request->itemcode), 
                    'uomcode'=> strtoupper($request->uomcode), 
                    'unitprice'=> $request->unitprice,
                    'qtyrequest'=> $request->qtyrequest, 
                    'qtyonhand'=> $request->qtyonhand, 
                    'unitprice'=> $request->unitprice,
                    'percbilltype'=> $request->percbilltype, 
                    'amtbilltype'=> $request->amtbilltype, 
                    'amount'=> $request->amount, 
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
            DB::table('hisdb.billdet')
                ->where('compcode','=',session('compcode'))
                ->where('idno','=',$request->idno)
                ->where('lineno_','=',$request->lineno_)
                ->delete();

            ///2. recalculate total amount
            $totalAmount = DB::table('hisdb.billdet')
                ->where('compcode','=',session('compcode'))
                ->where('idno','=',$request->idno)
                ->where('recstatus','!=','DELETE')
                ->sum('totamount');

            //calculate tot gst from detail
            $tot_gst = DB::table('hisdb.billdet')
                ->where('compcode','=',session('compcode'))
                ->where('idno','=',$request->idno)
                ->where('recstatus','!=','DELETE')
                ->sum('amtslstax');

            ///3. update total amount to header
            DB::table('hisdb.billdet')
                ->where('compcode','=',session('compcode'))
                ->where('idno','=',$request->idno)
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

}

