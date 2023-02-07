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


            $delordhd = DB::table('material.delordhd')
                    ->select('delordno','srcdocno','docno','deliverydate','subamount','taxclaimable','TaxAmt','recno','suppcode', 'prdept')
                    ->where('compcode','=',session('compcode'))
                    ->where('delordno','=',$request->document)
                    ->first();

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
                    'amount' => $delordhd->subamount,
                    'GSTCode' => $delordhd->taxclaimable,
                    'AmtB4GST' => $delordhd->TaxAmt,
                    'dorecno' => $delordhd->recno,
                    'grnno' => $delordhd->docno,
                    'deptcode' => $delordhd->deptcode,
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
            // DB::table('finance.apacthdr')
            //     ->where('compcode','=',session('compcode'))
            //     ->where('auditno','=',$auditno)
            //     ->update([
            //         'outamount' => $totalAmount
            //     ]);

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
                    'deptcode' => $request->deptcode,
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
                ->where('auditno','=',$request->auditno)
                ->where('lineno_','=',$request->lineno_)
                ->delete();

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

            DB::table('material.delordhd')
                ->where('compcode','=',session('compcode'))
                ->where('recstatus','=','POSTED')
                ->where('delordno','=',strtoupper($request->document))
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
                    ->where('auditno','=',$request->auditno)
                    ->where('lineno_','=',$value['lineno_'])
                    ->update([
                        'document' => strtoupper($value['itemcode']),
                        'reference' => strtoupper($value['reference']),
                        'amount' => $value['amount'],
                        'dorecno' => $value['dorecno'],
                        'grnno' => $value['grnno'],
                        'deptcode' => $value['deptcode'],
                        'adduser' => session('username'), 
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                       
                    ]);

                ///2. recalculate total amount
                $totalAmount = DB::table('finance.apactdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('auditno','=',$request->auditno)
                    ->where('recstatus','!=','DELETE')
                    ->sum('amount');

                ///3. update total amount to header
                DB::table('finance.apactdtl')
                    ->where('compcode','=',session('compcode'))
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

        if($delordhd->trandate > $apacthdr->recdate){
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

}

