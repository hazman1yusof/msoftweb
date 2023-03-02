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
        
        $table = DB::table('debtor.dbactdtl')
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
        
        DB::beginTransaction();
        
        try {
            
            //check utk gst
            $gstcode_obj = $this->check_gstcode($request);
            
            $source = 'PB';
            $trantype = 'DN';
            $auditno = $request->auditno;
            // dd($auditno);
            
            $dbacthdr = DB::table('debtor.dbacthdr')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','=',$source)
                    ->where('trantype','=',$trantype)
                    ->where('auditno','=',$auditno);
            
            $dbacthdr_obj = $dbacthdr->first();
            
            $dbactdtl = DB::table('debtor.dbactdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','=',$source)
                    ->where('trantype','=',$trantype)
                    ->where('auditno','=',$auditno);
            
            if($dbactdtl->exists()){
                $count = $dbactdtl->count();
                $lineno_ = $count + 1;
                $dbactdtl_obj = $dbactdtl->first();
            }else{
                $lineno_ = 1;
            }
            
            ///2. insert detail
            DB::table('debtor.dbactdtl')
                ->insert([
                    'compcode' => session('compcode'),
                    'source' => $source,
                    'trantype' => $trantype,
                    'auditno' => $auditno,
                    'lineno_' => $lineno_,
                    'entrydate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'mrn' => (!empty($dbacthdr_obj->mrn))?$dbacthdr_obj->mrn:null,
                    'episno' => (!empty($dbacthdr_obj->episno))?$dbacthdr_obj->episno:null,
                    'deptcode' => strtoupper($request->deptcode),
                    'document'  => strtoupper($request->document),
                    'GSTCode' => strtoupper($request->GSTCode),
                    'AmtB4GST' => floatval($gstcode_obj->AmtB4GST),
                    'tot_gst' => floatval($gstcode_obj->tot_gst),
                    'amount' => floatval($gstcode_obj->amount),
                    // 'amtslstax' => $tot_gst,
                    // 'category' => strtoupper($request->category),
                    // 'dorecno' => strtoupper($request->dorecno),
                    'paymode' => strtoupper($request->paymode),
                    'grnno' => strtoupper($request->grnno),
                    'recstatus' => 'OPEN',
                    'unit' => strtoupper($request->unit),
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
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
                    'outamount' => $totalAmount,
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
            
            $gstcode_obj = $this->check_gstcode($request);
            
            ///1. update detail
            DB::table('debtor.dbactdtl')
                ->where('compcode','=',session('compcode'))
                ->where('idno','=',$request->idno)
                ->update([
                    'deptcode' => strtoupper($request->deptcode),
                    'document'=> strtoupper($request->document),
                    'GSTCode'=> strtoupper($request->GSTCode),
                    'AmtB4GST' => $gstcode_obj->AmtB4GST,
                    'tot_gst' => $gstcode_obj->tot_gst,
                    'amount'=> $gstcode_obj->amount,
                    // 'category' => strtoupper($request->category),
                    // 'amtslstax' => $tot_gst,
                    'upduser'=> session('username'), 
                    'upddate'=> Carbon::now("Asia/Kuala_Lumpur"), 
                    'unit' => session('unit')
                ]);
            
            ///2. recalculate total amount
            $totalAmount = DB::table('debtor.dbactdtl')
                ->where('compcode','=',session('compcode'))
                ->where('source','=','PB')
                ->where('trantype','=','DN')
                ->where('auditno','=',$request->auditno)
                ->where('recstatus','!=','DELETE')
                ->sum('amount');
            
            ///3. update total amount to header
            DB::table('debtor.dbacthdr')
                ->where('compcode','=',session('compcode'))
                ->where('source','=','PB')
                ->where('trantype','=','DN')
                ->where('auditno','=',$request->auditno)
                ->update([
                    'amount' => $totalAmount
                ]);
            
            echo $totalAmount;
            
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
            
            foreach ($request->dataobj as $key => $value) {
                
                $gstcode_obj = $this->check_gstcode2($value);
                
                ///1. update detail
                DB::table('debtor.dbactdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('idno','=',$value['idno'])
                    ->update([
                        'deptcode' => $value['deptcode'],
                        'document' => strtoupper($value['document']),
                        'GSTCode' => strtoupper($value['GSTCode']),
                        'AmtB4GST' => $gstcode_obj->AmtB4GST,
                        'tot_gst' => $gstcode_obj->tot_gst,
                        'amount' => $gstcode_obj->amount,
                        // 'category' => $value['category'],
                        'upduser' => session('username'), 
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                    ]);
                    
                ///2. recalculate total amount
                $totalAmount = DB::table('debtor.dbactdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','=','PB')
                    ->where('trantype','=','DN')
                    ->where('auditno','=',$request->auditno)
                    ->where('recstatus','!=','DELETE')
                    ->sum('amount');
                    
                ///3. update total amount to header
                DB::table('debtor.dbacthdr')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','=','PB')
                    ->where('trantype','=','DN')
                    ->where('auditno','=',$request->auditno)
                    ->update([
                        'amount' => $totalAmount,
                        'outamount' => $totalAmount,
                    ]);
                
            }
            
            DB::commit();
            return response($totalAmount,200);
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error'.$e, 500);
            
        }
        
    }

    public function del(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            ///1. update detail
            DB::table('debtor.dbactdtl')
                ->where('compcode','=',session('compcode'))
                ->where('idno','=',$request->idno)
                ->delete();
                
            ///2. recalculate total amount
            $totalAmount = DB::table('debtor.dbactdtl')
                ->where('compcode','=',session('compcode'))
                ->where('source','=',$request->source)
                ->where('trantype','=',$request->trantype)
                ->where('auditno','=',$request->auditno)
                ->where('recstatus','!=','DELETE')
                ->sum('amount');
                
            if(empty($totalAmount)){
                $totalAmount = 0.00;
            }
            
            ///3. update total amount to header
            DB::table('debtor.dbacthdr')
                ->where('compcode','=',session('compcode'))
                ->where('source','=',$request->source)
                ->where('trantype','=',$request->trantype)
                ->where('auditno','=',$request->auditno)
                ->update([
                    'amount' => $totalAmount
                ]);
                
            echo $totalAmount;
            
            DB::commit();
            return response($totalAmount,200);
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
        
    }

    public function check_gstcode(Request $request){
        $gstcode = DB::table('hisdb.taxmast')
                    ->where('compcode',session('compcode'))
                    ->where('taxtype','OUTPUT')
                    ->where('taxcode',$request->GSTCode);
        
        if(!$gstcode->exists()){
            throw new \Exception('Tax Code '.$request->GSTCode.' doesnt exist', 500);
        }
        
        $gstcode_ = $gstcode->first();
        
        $rate = floatval($gstcode_->rate);
        $AmtB4GST = floatval($request->AmtB4GST);
        $tot_gst = $AmtB4GST * $rate / 100;
        $amount = $AmtB4GST + $tot_gst;
        
        $responce = new stdClass();
        $responce->rate = $rate;
        $responce->AmtB4GST = $AmtB4GST;
        $responce->tot_gst = $tot_gst;
        $responce->amount = $amount;
        
        return $responce;
    }

    public function check_gstcode2($value){
        $gstcode = DB::table('hisdb.taxmast')
                    ->where('compcode',session('compcode'))
                    ->where('taxtype','OUTPUT')
                    ->where('taxcode',$value['GSTCode']);
        
        if(!$gstcode->exists()){
            throw new \Exception('Tax Code '.$value['GSTCode'].' doesnt exist', 500);
        }
        
        $gstcode_ = $gstcode->first();
        
        $rate = floatval($gstcode_->rate);
        $AmtB4GST = floatval($value['AmtB4GST']);
        $tot_gst = $AmtB4GST * $rate / 100;
        $amount = $AmtB4GST + $tot_gst;
        
        $responce = new stdClass();
        $responce->rate = $rate;
        $responce->AmtB4GST = $AmtB4GST;
        $responce->tot_gst = $tot_gst;
        $responce->amount = $amount;
        
        return $responce;
    }
    
}

