<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class DebitNoteAPDetailController extends defaultController
{   

    public function __construct()
    {
        $this->middleware('auth');
    }

     public function form(Request $request)
    {   
        DB::enableQueryLog();
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

    public function table(Request $request)
    {   
        switch($request->action){
            case 'get_table_dtl':
                return $this->get_table_dtl($request);
            default:
                return 'error happen..';
        }
    }

    public function get_table_dtl(Request $request){
        $table = DB::table('finance.apactdtl as apdt')
                    ->select('apdt.compcode','apdt.source','apdt.reference','apdt.trantype','apdt.auditno','apdt.lineno_','apdt.deptcode','apdt.category','apdt.document', 'apdt.AmtB4GST', 'apdt.GSTCode', 'apdt.amount', 'apdt.taxamt AS tot_gst', 'apdt.dorecno', 'apdt.grnno','apdt.idno')
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
            return '0000-00-00';
        }
    }

    public function add(Request $request){
        DB::beginTransaction();

        try {

            //check utk gst
            $gstcode_obj = $this->check_gstcode($request);
            
            $apacthdr = DB::table("finance.apacthdr")
                ->where('idno','=',$request->idno)
                ->first();

            $auditno = $apacthdr->auditno;

            ////1. calculate lineno_ by auditno
            $sqlln = DB::table('finance.apactdtl')
                        ->select('lineno_')
                        ->where('compcode','=',session('compcode'))
                        ->where('source','=','AP')
                        ->where('trantype','=','DN')
                        ->where('auditno','=',$auditno)
                        ->count('lineno_');

            $li=intval($sqlln)+1;
            
              ///2. insert detail
            DB::table('finance.apactdtl')
              ->insert([
                  'compcode' => session('compcode'),
                  'auditno' => $auditno,
                  'lineno_' => $li,
                  'source' => 'AP',
                  'trantype' => 'DN',
                  'amount' => floatval($gstcode_obj->amount),
                  'GSTCode' => strtoupper($request->GSTCode),
                  'AmtB4GST' => floatval($gstcode_obj->AmtB4GST),
                  'taxamt' => floatval($gstcode_obj->tot_gst),
                  'deptcode' => strtoupper($request->deptcode),
                  'category' => strtoupper($request->category),
                  'adduser' => session('username'), 
                  'adddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                  'recstatus' => 'OPEN',
                  'unit' => session('unit')
              ]);

          ///3. calculate total amount from detail
          $totalAmount = DB::table('finance.apactdtl')
                  ->where('compcode','=',session('compcode'))
                  ->where('auditno','=',$auditno)
                  ->where('source','=','AP')
                  ->where('trantype','=','DN')
                  ->where('recstatus','!=','DELETE')
                  ->sum('amount');

          ///4. then update to header
          DB::table('finance.apacthdr')
              ->where('compcode','=',session('compcode'))
              ->where('idno','=',$request->idno)
              ->update([
                  'amount' => $totalAmount,
                  'outamount' => $totalAmount
              ]);
          DB::commit();

          return response($totalAmount,200);
        
        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }
    }

    public function edit(Request $request){

        DB::beginTransaction();

        try {

            ///1. update detail
            DB::table('finance.apactdtl')
                ->where('compcode','=',session('compcode'))
                ->where('auditno','=',$request->auditno)
                ->where('lineno_','=',$request->lineno_)
                ->update([
                    'source' => 'AP',
                    'trantype' => 'DN',
                    'amount' => $request->amount,
                    'GSTCode' => $request->GSTCode,
                    'AmtB4GST' => $request->AmtB4GST,
                    'taxamt' => $request->tot_gst,
                    'deptcode' => $request->deptcode,
                    'category' => $request->category,
                    'adduser' => session('username'), 
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                    'recstatus' => 'OPEN',
                    'unit' => session('unit')
                ]);

            ///2. recalculate total amount
            $totalAmount = DB::table('finance.apactdtl')
                ->where('compcode','=',session('compcode'))
                ->where('source','=','AP')
                ->where('trantype','=','DN')
                ->where('auditno','=',$request->auditno)
                ->where('recstatus','!=','DELETE')
                ->sum('amount');

            ///3. update total amount to header
            DB::table('finance.apacthdr')
                ->where('compcode','=',session('compcode'))
                ->where('auditno','=',$request->auditno)
                ->update([
                    'amount' => $totalAmount,
                    'outamount' => $totalAmount
                ]);

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
            DB::table('finance.apactdtl')
                ->where('compcode','=',session('compcode'))
                ->where('source','=','AP')
                ->where('trantype','=','DN')
                ->where('auditno','=',$request->auditno)
                ->where('lineno_','=',$request->lineno_)
                ->delete();

            ///2. recalculate total amount
            $totalAmount = DB::table('finance.apactdtl')
                ->where('compcode','=',session('compcode'))
                ->where('source','=','AP')
                ->where('trantype','=','DN')
                ->where('auditno','=',$request->auditno)
                ->where('recstatus','!=','DELETE')
                ->sum('amount');

          
            ///3. update total amount to header
            DB::table('finance.apacthdr')
                ->where('compcode','=',session('compcode'))
                ->where('source','=','AP')
                ->where('trantype','=','DN')
                ->where('auditno','=',$request->auditno)
                ->update([
                    'amount' => $totalAmount,
                    'outamount' => $totalAmount
                  
                ]);

            DB::commit();

            return response($totalAmount,200);

        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }
        
    }

    public function edit_all(Request $request){

        DB::beginTransaction();

        try {

            foreach ($request->dataobj as $key => $value) {

                $gstcode_obj = $this->check_gstcode2($value);

                ///1. update detail
                DB::table('finance.apactdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('idno','=',$value['idno'])
                    ->update([
                        'deptcode' => strtoupper($value['deptcode']),
                        'category' => strtoupper($value['category']),
                        'GSTCode' => strtoupper($value['GSTCode']),
                        'AmtB4GST' => $gstcode_obj->AmtB4GST,
                        'taxamt' => $gstcode_obj->tot_gst,
                        'amount' => $gstcode_obj->amount,
                        'upduser' => session('username'), 
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                       
                    ]);

                ///2. recalculate total amount
                $totalAmount = DB::table('finance.apactdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','=','AP')
                    ->where('trantype','=','DN')
                    ->where('auditno','=',$request->auditno)
                    ->where('recstatus','!=','DELETE')
                    ->sum('amount');

                ///3. update total amount to header
                DB::table('finance.apacthdr')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','=','AP')
                    ->where('trantype','=','DN')
                    ->where('auditno','=',$request->auditno)
                    ->update([
                        'amount' => $totalAmount, 
                        'outamount' => $totalAmount
                    ]);
            }

            DB::commit();
            return response($totalAmount,200);

        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }

    }

    public function check_gstcode(Request $request){
        $gstcode = DB::table('hisdb.taxmast')
                    ->where('compcode',session('compcode'))
                    ->where('taxtype','Input')
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
                    ->where('taxtype','Input')
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

    public function delete_dd(Request $request){
        DB::table('finance.apacthdr')
                ->where('idno','=',$request->idno)
                ->where('compcode','=','DD')
                ->delete();
    }

}

