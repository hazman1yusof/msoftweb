<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class DebitNoteDetailController extends defaultController
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
        $table = DB::table('hisdb.chgmast')
                    ->where('compcode','=',session('compcode'))
                    ->where('recstatus','<>','DELETE')
                    ->orderBy('idno','desc');
        switch ($request->filterVal[2]) {
            case 'PRICE1':
                $cp_fld = 'amt1';
                break;
            case 'PRICE2':
                $cp_fld = 'amt2';
                break;
            case 'PRICE3':
                $cp_fld = 'amt2';
                break;
            default:
                $cp_fld = 'costprice';
                break;
        }

        if(!empty($request->searchCol)){
            $table = $table->where($request->searchCol[0],'LIKE',$request->searchVal[0]);
        }

        $paginate = $table->paginate($request->rows);
        $rows = $paginate->items();

        foreach ($rows as $key => $value) {
            $chgprice_obj = DB::table('hisdb.chgprice as cp')
                ->select($cp_fld,'cp.optax','tm.rate')
                ->leftJoin('hisdb.taxmast as tm', 'cp.optax', '=', 'tm.taxcode')
                ->where('cp.compcode', '=', session('compcode'))
                ->where('cp.chgcode', '=', $value->chgcode)
                ->whereDate('cp.effdate', '<=', Carbon::now('Asia/Kuala_Lumpur'))
                ->orderBy('cp.effdate','desc');


            if($chgprice_obj->exists()){
                $chgprice_obj = $chgprice_obj->first();
                switch ($request->filterVal[2]) {
                    case 'PRICE1':
                        $rows[$key]->price = $chgprice_obj->amt1;
                        break;
                    case 'PRICE2':
                        $rows[$key]->price = $chgprice_obj->amt2;
                        break;
                    case 'PRICE3':
                        $rows[$key]->price = $chgprice_obj->amt3;
                        break;
                    default:
                        $rows[$key]->price = $chgprice_obj->costprice;
                        break;
                }
                $rows[$key]->taxcode = $chgprice_obj->optax;
                $rows[$key]->rate = $chgprice_obj->rate;
            }

            if($value->invflag == '1'){
                $stockloc_obj = DB::table('material.stockloc')
                        ->where('compcode', '=', session('compcode'))
                        ->where('itemcode', '=', $value->chgcode)
                        ->where('year', '=', Carbon::now('Asia/Kuala_Lumpur')->year);

                if($stockloc_obj->exists()){
                    $stockloc_obj = $stockloc_obj->first();
                    $rows[$key]->qtyonhand = $stockloc_obj->qtyonhand;
                }
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

            $dbacthdr = DB::table('debtor.dbacthdr')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','=',$source)
                    ->where('trantype','=',$trantype)
                    ->where('auditno','=',$auditno);

            $dbacthdr_obj = $dbacthdr->first();

            ///2. insert detail
            DB::table('debtor.dbactdtl')
                ->insert([
                    'compcode' => session('compcode'),
                    'source' => $source,
                    'trantype' => $trantype,
                    'auditno' => $auditno,
                    'lineno_' => 1,
                    'entrydate'=> $entrydate,
                    'document'  => $document,
                    'reference'  => $reference,
                    'amount' => $request->amount,
                    'stat' => $stat,
                    'mrn' => (!empty($dbacthdr_obj->mrn))?$dbacthdr_obj->mrn:null,
                    'episno' => (!empty($dbacthdr_obj->episno))?$dbacthdr_obj->episno:null,
                    'billno' => $billno,
                    'paymode' => $paymode,
                    'allocauditno' => $allocauditno,
                    'alloclineno' => $alloclineno,
                    'alloctnauditno' => $alloctnauditno,
                    'alloctnlineno' => $alloctnlineno,
                    'grnno' => $grnno,
                    'dorecno' => $dorecno,
                    'category' => $category,
                    'deptcode' => $deptcode,
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'recstatus' => 'OPEN',
                    'GSTCode' => $request->GSTCode,
                    'AmtB4GST' => floatval($request->AmtB4GST),
                    'unit' => $request->unit,
                    'lastuser' => session('username'), 
                    'lastupdate' => Carbon::now("Asia/Kuala_Lumpur") 
                ]);

            ///3. calculate total amount from detail
            $totalAmount = DB::table('debtor.dbactdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','=',$source)
                    ->where('trantype','=',$trantype)
                    ->where('auditno','=',$auditno)
                    ->where('recstatus','!=','DELETE')
                    ->sum('amount');

            ///4. then update to header
            
            $dbacthdr->update([
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
            DB::table('debtor.dbactdtl')
                ->where('compcode','=',session('compcode'))
                ->where('idno','=',$request->idno)
                //->where('lineno_','=',$request->lineno_)
                ->update([
                    'billno'=> strtoupper($request->billno), 
                    'document'=> strtoupper($request->document), 
                    'unitprice'=> $request->unitprice,
                    'deptcode'=> $request->deptcode,
                    'qtyrequest'=> $request->qtyrequest, 
                    'qtyonhand'=> $request->qtyonhand, 
                    'unitprice'=> $request->unitprice,
                    'percbilltype'=> $request->percbilltype, 
                    'amtbilltype'=> $request->amtbilltype, 
                    'amount'=> $request->amount,
                    'AmtB4GST' => $request->AmtB4GST, 
                    'upduser'=> session('username'), 
                    'upddate'=> Carbon::now("Asia/Kuala_Lumpur"), 
                    // 'remarks'=> strtoupper($request->remarks),
                    // 'unit' => session('unit')
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
            DB::table('debtor.dbactdtl')
                ->where('compcode','=',session('compcode'))
                ->where('idno','=',$request->idno)
                ->where('lineno_','=',$request->lineno_)
                ->delete();

            ///2. recalculate total amount
            $totalAmount = DB::table('debtor.dbactdtl')
                ->where('compcode','=',session('compcode'))
                ->where('idno','=',$request->idno)
                ->where('recstatus','!=','DELETE')
                ->sum('totamount');

            //calculate tot gst from detail
            $tot_gst = DB::table('debtor.dbactdtl')
                ->where('compcode','=',session('compcode'))
                ->where('idno','=',$request->idno)
                ->where('recstatus','!=','DELETE')
                ->sum('amtslstax');

            ///3. update total amount to header
            DB::table('debtor.dbactdtl')
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

