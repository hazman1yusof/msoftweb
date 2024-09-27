<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class InvoiceAPDetailController extends defaultController
{   

    public function __construct()
    {
        $this->middleware('auth');
    }

     public function form(Request $request)
    {   
        DB::enableQueryLog();
        if($request->action == 'invoiceAPDetail_save'){
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
        }else if($request->action == 'InvoiceAPDetail_save_oth'){
            switch($request->oper){
                case 'add': 
                    return $this->add_oth($request);
                // case 'edit':
                //     return $this->edit($request);
                case 'edit_all':
                    return $this->edit_all_oth($request);
                case 'del':
                    return $this->del_oth($request);
                default:
                    return 'error happen..';
            }
        }
    }

    public function table(Request $request){   
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

    public function add(Request $request){//invoiceAPDetail_save
        DB::beginTransaction();

        try {

            $auditno = $request->auditno;
            
            $apacthdr = DB::table("finance.apacthdr")
                            ->where('idno','=',$request->idno)
                            ->where('compcode','=','DD');

            if($apacthdr->exists()){
                $auditno = $this->recno($apacthdr->first()->source, $apacthdr->first()->trantype);

                DB::table("finance.apacthdr")
                    ->where('idno','=',$request->idno)
                    ->update([
                        'auditno' => $auditno,
                        'compcode' => session('compcode'),
                    ]);
            }

            if(empty($auditno)){
                $auditno = $this->recno($apacthdr->first()->source, $apacthdr->first()->trantype);

                DB::table("finance.apacthdr")
                    ->where('idno','=',$request->idno)
                    ->update([
                        'auditno' => $auditno,
                        'compcode' => session('compcode'),
                    ]);
            }

            $apacthdr = DB::table("finance.apacthdr")
                        ->where('idno','=',$request->idno)
                        ->first();

            ////1. calculate lineno_ by auditno
            $apactdtl = DB::table('finance.apactdtl')
                        ->select('lineno_')
                        ->where('compcode','=',session('compcode'))
                        ->where('auditno','=',$auditno);

            if($apactdtl->exists()){
                $apactdtl = $apactdtl->orderBy('idno', 'DESC')->first();

                $li=intval($apactdtl->lineno_)+1;
            }else{
                $li=1;
            }
            
            $this->check_dotexists($request->document,$apacthdr);

            $this->check_doinvnoexists($request->document,$apacthdr);

            $this->check_dotrandate($request->document,$apacthdr);

            $this->check_duplicatedo($request->document,$apacthdr);

            $suppcode = $apacthdr->suppcode;
            $grnno = $request->grnno;
            $postdate = $apacthdr->postdate;
            $delordno = $request->document;

            // $delordhd = DB::table('material.delordhd')
            //         ->select('delordno','srcdocno','docno','deliverydate','subamount','taxclaimable','TaxAmt','recno','suppcode', 'prdept')
            //         ->where('compcode','=',session('compcode'))
            //         ->where('delordno','=',$request->document)
            //         ->first();

            $table = DB::table('material.delordhd')
                    ->select('delordno','srcdocno','docno','deliverydate','subamount as amount','taxclaimable','TaxAmt','recno','suppcode', 'prdept')
                    ->where('compcode','=',session('compcode'))
                    ->where('suppcode','=',$suppcode)
                    ->where('docno','=',$grnno)
                    ->where('delordno','=',$delordno)
                    ->where('trantype','=','GRN')
                    ->where('recstatus','=','POSTED')
                    ->whereDate('trandate','<=',$postdate)
                    ->whereNull('invoiceno');

            if(!$table->exists()){
                throw new \Exception("No DO.. check postdate need to be greater than DO trandate");
            }

            $delordhd = $table->first();

            $accum_grt_amt = DB::table('material.delordhd')
                            ->where('compcode',session('compcode'))
                            ->where('suppcode','=',$suppcode)
                            ->where('srcdocno','=',$grnno)
                            ->where('delordno','=',$delordno)
                            ->where('trantype','=','GRT')
                            ->where('recstatus','=','POSTED')
                            ->whereNull('invoiceno')
                            ->whereDate('trandate','<=',$postdate)
                            ->sum('subamount');
            $amount_invoice = $delordhd->amount - $accum_grt_amt;

            ///2. insert detail
            DB::table('finance.apactdtl')
                ->insert([
                    'compcode' => session('compcode'),
                    'auditno' => $auditno,
                    'lineno_' => $li,
                    'source' => 'AP',
                    'trantype' => 'IN',
                    'document' => strtoupper($request->document),
                    'reference' => strtoupper($delordhd->srcdocno),
                    'amount' => $amount_invoice,
                    'GSTCode' => $delordhd->taxclaimable,
                    'AmtB4GST' => $delordhd->TaxAmt,
                    'dorecno' => $delordhd->recno,
                    'grnno' => $delordhd->docno,
                    'deptcode' => $delordhd->prdept,
                    'adduser' => session('username'), 
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                    'recstatus' => 'OPEN',
                    'unit' => session('unit')
                ]);

            ///3. calculate total amount from detail
            $totalAmount = DB::table('finance.apactdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('auditno','=',$auditno)
                    ->where('recstatus','!=','DELETE')
                    ->sum('amount');

       
            ///4. then update to header
            DB::table('finance.apacthdr')
                ->where('compcode','=',session('compcode'))
                ->where('auditno','=',$auditno)
                ->update([
                    'amount' => $totalAmount,
                    'outamount' => $totalAmount
                ]);

            DB::table('material.delordhd')
                ->where('compcode','=',session('compcode'))
                ->where('recstatus','=','POSTED')
                ->where('delordno','=',strtoupper($request->document))
                ->update(['invoiceno'=>$apacthdr->document]);


            DB::commit();

            $responce = new stdClass();
            $responce->totalAmount = $totalAmount;
            $responce->auditno = $auditno;
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
            DB::table('finance.apactdtl')
                ->where('compcode','=',session('compcode'))
                ->where('auditno','=',$request->auditno)
                ->where('lineno_','=',$request->lineno_)
                ->update([
                    'compcode' => session('compcode'),
                    'source' => 'AP',
                    'trantype' => 'IN',
                    'document' => strtoupper($request->document),
                    'reference' => strtoupper($request->reference),
                    'amount' => $request->amount,
                    'GSTCode' => $request->GSTCode,
                    'AmtB4GST' => $request->AmtB4GST,
                    'dorecno' => $request->dorecno,
                    'grnno'=> $request->grnno,
                    'deptcode' => $request->prdept,
                    'adduser' => session('username'), 
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                    'recstatus' => 'OPEN',
                    'unit' => session('unit')
                ]);

            ///2. recalculate total amount
            $totalAmount = DB::table('finance.apactdtl')
                ->where('compcode','=',session('compcode'))
                ->where('auditno','=',$request->auditno)
                ->where('recstatus','!=','DELETE')
                ->sum('amount');

            ///3. update total amount to header
            // DB::table('finance.apacthdr')
            //     ->where('compcode','=',session('compcode'))
            //     ->where('auditno','=',$request->auditno)
            //     ->update([
            //         'outamount' => $totalAmount
            //     ]);

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
            DB::table('finance.apactdtl')
                ->where('compcode','=',session('compcode'))
                ->where('source','AP')
                ->where('trantype','IN')
                ->where('auditno','=',$request->auditno)
                ->where('lineno_','=',$request->lineno_)
                ->delete();

            ///2. recalculate total amount
            $totalAmount = DB::table('finance.apactdtl')
                ->where('compcode','=',session('compcode'))
                ->where('source','AP')
                ->where('trantype','IN')
                ->where('auditno','=',$request->auditno)
                ->where('recstatus','!=','DELETE')
                ->sum('amount');

          
            //3. update total amount to header
            DB::table('finance.apacthdr')
                ->where('compcode','=',session('compcode'))
                ->where('source','AP')
                ->where('trantype','IN')
                ->where('auditno','=',$request->auditno)
                ->update([
                    'amount' => $totalAmount,
                    'outamount' => $totalAmount
                ]);

            DB::table('material.delordhd')
                ->where('compcode','=',session('compcode'))
                ->where('recstatus','=','POSTED')
                ->where('delordno','=',$request->document)
                ->update(['invoiceno'=>null]);

            DB::commit();


            $responce = new stdClass();
            $responce->totalAmount = $totalAmount;

            return json_encode($responce);

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
                DB::table('finance.apactdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','AP')
                    ->where('trantype','IN')
                    ->where('auditno','=',$request->auditno)
                    ->where('lineno_','=',$value['lineno_'])
                    ->update([
                        'document' => strtoupper($value['itemcode']),
                        'reference' => strtoupper($value['reference']),
                        'amount' => $value['amount'],
                        'dorecno' => $value['dorecno'],
                        'grnno' => $value['grnno'],
                        'deptcode' => $value['deptcode'],
                        'upduser' => session('username'), 
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                       
                    ]);

                ///2. recalculate total amount
                $totalAmount = DB::table('finance.apactdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','AP')
                    ->where('trantype','IN')
                    ->where('auditno','=',$request->auditno)
                    ->where('recstatus','!=','DELETE')
                    ->sum('amount');

                ///3. update total amount to header
                DB::table('finance.apactdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','AP')
                    ->where('trantype','IN')
                    ->where('auditno','=',$request->auditno)
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

    public function add_oth(Request $request){
        DB::beginTransaction();

        try {
            $auditno = $request->auditno;
            
            $apacthdr = DB::table("finance.apacthdr")
                            ->where('idno','=',$request->idno)
                            ->where('compcode','=','DD');

            if($apacthdr->exists()){
                $auditno = $this->recno('AP','IN');

                DB::table("finance.apacthdr")
                    ->where('idno','=',$request->idno)
                    ->update([
                        'auditno' => $auditno,
                        'compcode' => session('compcode'),
                    ]);
            }

            if(empty($auditno)){
                $auditno = $this->recno('AP','IN');

                DB::table("finance.apacthdr")
                    ->where('idno','=',$request->idno)
                    ->update([
                        'auditno' => $auditno,
                        'compcode' => session('compcode'),
                    ]);
            }

            $apacthdr = DB::table("finance.apacthdr")
                        ->where('idno','=',$request->idno)
                        ->first();

            $sqlln = DB::table('finance.apactdtl')
                ->select('lineno_')
                ->where('compcode','=',session('compcode'))
                ->where('source','=','AP')
                ->where('trantype','=','IN')
                ->where('auditno','=',$auditno)
                ->max('lineno_');

            $li=intval($sqlln)+1;

            $gstcode_obj = $this->check_gstcode($request);
            
              ///2. insert detail
            DB::table('finance.apactdtl')
              ->insert([
                  'compcode' => session('compcode'),
                  'auditno' => $auditno,
                  'lineno_' => $li,
                  'source' => 'AP',
                  'trantype' => 'IN',
                  'amount' => floatval($gstcode_obj->amount),
                  'GSTCode' => strtoupper($request->GSTCode),
                  'AmtB4GST' => floatval($gstcode_obj->AmtB4GST),
                  'taxamt' => floatval($gstcode_obj->tot_gst),
                  'deptcode' => strtoupper($request->deptcode),
                  'category' => strtoupper($apacthdr->category),
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
                  ->where('trantype','=','IN')
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

        $responce = new stdClass();
        $responce->totalAmount = $totalAmount;
        $responce->auditno = $auditno;
        return json_encode($responce);
            
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function del_oth(Request $request){
        DB::beginTransaction();

        try {

            ///1. update detail
            DB::table('finance.apactdtl')
                ->where('compcode','=',session('compcode'))
                ->where('source','AP')
                ->where('trantype','IN')
                ->where('auditno','=',$request->auditno)
                ->where('lineno_','=',$request->lineno_)
                ->delete();

            ///2. recalculate total amount
            $totalAmount = DB::table('finance.apactdtl')
                ->where('compcode','=',session('compcode'))
                ->where('source','AP')
                ->where('trantype','IN')
                ->where('auditno','=',$request->auditno)
                ->where('recstatus','!=','DELETE')
                ->sum('amount');

          
            //3. update total amount to header
            DB::table('finance.apacthdr')
                ->where('compcode','=',session('compcode'))
                ->where('source','AP')
                ->where('trantype','IN')
                ->where('auditno','=',$request->auditno)
                ->update([
                    'amount' => $totalAmount,
                    'outamount' => $totalAmount
                  
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

    public function edit_all_oth(Request $request){
        DB::beginTransaction();

        try {

            foreach ($request->dataobj as $key => $value) {

                $gstcode_obj = $this->check_gstcode2($value);

                ///1. update detail
                DB::table('finance.apactdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','AP')
                    ->where('trantype','IN')
                    ->where('auditno','=',$request->auditno)
                    ->where('lineno_','=',$value['lineno_'])
                    ->update([
                        'GSTCode' => strtoupper($value['GSTCode']),
                        'AmtB4GST' => floatval($gstcode_obj->AmtB4GST),
                        'taxamt' => floatval($gstcode_obj->tot_gst),
                        'amount' => floatval($gstcode_obj->amount),
                        'deptcode' => $value['deptcode'],
                        'upduser' => session('username'), 
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                    ]);

                ///2. recalculate total amount
                $totalAmount = DB::table('finance.apactdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','AP')
                    ->where('trantype','IN')
                    ->where('auditno','=',$request->auditno)
                    ->where('recstatus','!=','DELETE')
                    ->sum('amount');

                ///3. update total amount to header
                DB::table('finance.apacthdr')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','AP')
                    ->where('trantype','IN')
                    ->where('auditno','=',$request->auditno)
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

    public function check_dotrandate($delordno,$apacthdr){
        $delordhd = DB::table('material.delordhd')
                    ->where('compcode','=',session('compcode'))
                    ->where('delordno','=',$delordno)
                    ->where('suppcode','=',$apacthdr->suppcode)
                    ->first();

        if($delordhd->trandate > $apacthdr->postdate){
            throw new \Exception("DO date greater than Invoice date");
        }
    }

    public function check_dotexists($delordno,$apacthdr){
        $delordhd = DB::table('material.delordhd')
                    ->where('compcode','=',session('compcode'))
                    ->where('delordno','=',$delordno)
                    ->where('suppcode','=',$apacthdr->suppcode);

        if(!$delordhd->exists()){
            throw new \Exception("Delivery Order Doesnt exists");
        }
    }

    public function check_duplicatedo($delordno,$apacthdr){
        $delordhd = DB::table('finance.apactdtl as d')
                        ->where('d.compcode','=',session('compcode'))
                        ->where('d.document','=',$delordno)
                        ->join('finance.apacthdr as h', function($join) use ($delordno,$apacthdr){
                            $join = $join->on('h.source', '=', 'd.source');
                            $join = $join->on('h.trantype', '=', 'd.trantype');
                            $join = $join->on('h.auditno', '=', 'd.auditno');
                            $join = $join->where('h.suppcode', '=', $apacthdr->suppcode);
                            $join = $join->where('h.recstatus', '<>', 'CANCELLED');
                            $join = $join->on('h.compcode','=','d.compcode');
                        });

        if($delordhd->exists()){
            throw new \Exception("Delivery Order Duplicate");
        }
    }

    public function check_doinvnoexists($delordno,$apacthdr){
        $delordhd = DB::table('material.delordhd')
                    ->where('compcode','=',session('compcode'))
                    ->where('delordno','=',$delordno)
                    ->where('suppcode','=',$apacthdr->suppcode)
                    ->whereNotNull('invoiceno');

        if($delordhd->exists()){
            throw new \Exception("Delivery Order already have invoice");
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
        
        $tot_gst_real = 0;
        $tot_gst_rate = $AmtB4GST * $rate / 100;

        if($tot_gst_real == $tot_gst_rate || $tot_gst_real==0){
            $amount = $AmtB4GST + $tot_gst_rate;
            $tot_gst = $tot_gst_rate;
        }else{
            $amount = $AmtB4GST + $tot_gst_real;
            $tot_gst = $tot_gst_real;
        }

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

        $tot_gst_real = 0;
        $tot_gst_rate = $AmtB4GST * $rate / 100;

        if($tot_gst_real == $tot_gst_rate || $tot_gst_real==0){
            $amount = $AmtB4GST + $tot_gst_rate;
            $tot_gst = $tot_gst_rate;
        }else{
            $amount = $AmtB4GST + $tot_gst_real;
            $tot_gst = $tot_gst_real;
        }

        $responce = new stdClass();
        $responce->rate = $rate;
        $responce->AmtB4GST = $AmtB4GST;
        $responce->tot_gst = $tot_gst;
        $responce->amount = $amount;

        return $responce;
    }

}

