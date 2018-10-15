<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class InvoiceAPDetailController extends defaultController
{   
    var $gltranAmount;
    var $srcdocno;

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

                if($request->srcdocno != 0){
                    // return 'edit all srcdocno !=0';
                    return $this->edit_all_from_PO($request);
                }else{
                    // return 'edit all biasa';
                    return $this->edit_all($request);
                }

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

    public function add(Request $request){
        DB::beginTransaction();

        try {
           
            $auditno = $request->auditno;
            ////1. calculate lineno_ by recno
            $sqlln = DB::table('finance.apactdtl')->select('lineno_')
                        ->where('compcode','=',session('compcode'))
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
                    'trantype' => 'IN',
                    'document' => $request->document,
                    'reference' => $request->reference,
                    'amount' => $request->amount,
                    'GSTCode' => $request->GSTCode,
                    'AmtB4GST' => $request->AmtB4GST,
                    'dorecno' => $request->dorecno,
                    'grnno'=> $request->grnno,
                    'adduser' => session('username'), 
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                    'recstatus' => 'OPEN'
                ]);

            ///3. calculate total amount from detail
            $totalAmount = DB::table('finance.apactdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('auditno','=',$auditno)
                    ->where('recstatus','!=','DELETE')
                    ->sum('amount');

       
            ///4. then update to header
            DB::table('finance.apactdtl')
                ->where('compcode','=',session('compcode'))
                ->where('auditno','=',$auditno)
                ->update([
                    'amount' => $totalAmount
                  
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
            $request->expdate = $this->null_date($request->expdate);

            ///1. update detail
            DB::table('finance.apactdtl')
                ->where('compcode','=',session('compcode'))
                ->where('auditno','=',$request->auditno)
                ->where('lineno_','=',$request->lineno_)
                ->update([
                    'compcode' => session('compcode'),
                    'auditno' => $auditno,
                    'lineno_' => $li,
                    'source' => 'AP',
                    'trantype' => 'IN',
                    'document' => $request->document,
                    'reference' => $request->reference,
                    'amount' => $request->amount,
                    'GSTCode' => $request->GSTCode,
                    'AmtB4GST' => $request->AmtB4GST,
                    'dorecno' => $request->dorecno,
                    'grnno'=> $request->grnno,
                    'adduser' => session('username'), 
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                    'recstatus' => 'OPEN'
                ]);

            ///2. recalculate total amount
            $totalAmount = DB::table('material.ivtmpdt')
                ->where('compcode','=',session('compcode'))
                ->where('auditno','=',$request->auditno)
                ->where('recstatus','!=','DELETE')
                ->sum('amount');

            ///3. update total amount to header
            DB::table('material.ivtmphd')
                ->where('compcode','=',session('compcode'))
                ->where('auditno','=',$request->auditno)
                ->update([
                    'amount' => $totalAmount, 
                ]);

            DB::commit();
            return response($totalAmount,200);

        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }

    }

   /* public function del(Request $request){

        DB::beginTransaction();

        try {

            ///1. update detail
            DB::table('material.ivtmpdt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->where('lineno_','=',$request->lineno_)
                ->update([ 
                    'deluser' => session('username'), 
                    'deldate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'recstatus' => 'DELETE'
                ]);

            ///2. recalculate total amount
            $totalAmount = DB::table('material.ivtmpdt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->where('recstatus','!=','DELETE')
                ->sum('amount');

           
            ///3. update total amount to header
            DB::table('material.ivtmphd')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->update([
                    'amount' => $totalAmount,  
                   
                ]);

            DB::commit();

            return response($totalAmount,200);

        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }
        
    }*/

    public function edit_from_PO(Request $request){

        DB::beginTransaction();

        try {

            ///1. update detail
            DB::table('material.delorddt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->where('lineno_','=',$request->lineno_)
                ->update([
                    'pricecode' => $request->pricecode, 
                    'itemcode'=> $request->itemcode, 
                    'uomcode'=> $request->uomcode, 
                    'pouom'=> $request->pouom, 
                    'qtyorder'=> $request->qtyorder, 
                    'qtydelivered'=> $request->qtydelivered, 
                    'unitprice'=> $request->unitprice,
                    'taxcode'=> $request->taxcode, 
                    'perdisc'=> $request->perdisc, 
                    'amtdisc'=> $request->amtdisc, 
                    'amtslstax'=> $request->tot_gst, 
                    'netunitprice'=> $request->netunitprice, 
                    'amount'=> $request->amount, 
                    //'totamount'=> $request->totamount, 
                    'upduser'=> session('username'), 
                    'upddate'=> Carbon::now("Asia/Kuala_Lumpur"), 
                    'expdate'=> $this->chgDate($request->expdate),  
                    'batchno'=> $request->batchno, 
                    'remarks'=> $request->remarks
                ]);

            ///2. recalculate total amount
           /* $totalAmount = DB::table('material.delorddt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->where('recstatus','!=','DELETE')
                ->sum('amount');

            //calculate tot gst from detail
            $tot_gst = DB::table('material.delorddt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->where('recstatus','!=','DELETE')
                ->sum('amtslstax');

            ///3. update total amount to header
            DB::table('material.delordhd')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->update([
                    'totamount' => $totalAmount, 
                    'subamount'=> $totalAmount, 
                    'TaxAmt' => $tot_gst
                ]);*/

            ///4. cari recno dkt podt
            $purordhd = DB::table('material.purordhd')
                ->where('compcode','=',session('compcode'))
                ->where('purordno','=',$this->srcdocno)
                ->first();
            $po_recno = $purordhd->recno;

            ///5. amik old qtydelivered / qtyorder dkt qtyrequest
            $podt_obj = DB::table('material.purorddt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$po_recno)
                ->where('lineno_','=',$request->lineno_);
            $podt_obj_lama = $podt_obj->first();

            ///6. check dan bagi error kalu exceed quantity order

                //step 1. cari header yang ada srcdocno ni
            $delordhd_obj = DB::table('material.delordhd')
                ->where('compcode','=',session('compcode'))
                ->where('srcdocno','=',$this->srcdocno);

            if($delordhd_obj->exists()){
                $total_qtydeliverd_do = 0;

                $delorhd_all = $delordhd_obj->get();

                //step 2. dapatkan dia punya qtydelivered melalui lineno yg sama, pastu jumlahkan, jumlah ni qtydelivered yang blom post lagi
                foreach ($delorhd_all as $value_hd) {
                    $delorddt_obj = DB::table('material.delorddt')
                        ->where('recno','=',$value_hd->recno)
                        ->where('compcode','=',session('compcode'))
                        ->where('lineno_','=',$request->lineno_);

                    if($delorddt_obj->exists()){
                        $delorddt_data = $delorddt_obj->first();
                        $total_qtydeliverd_do = $total_qtydeliverd_do + $delorddt_data->qtydelivered;
                    }
                }
            }

                //step 3. jumlah_qtydelivered = qtydelivered yang dah post + qtydelivered yang blom post
            $jumlah_qtydelivered = $podt_obj_lama->qtydelivered + $total_qtydeliverd_do;

                //step 4. kalu melebihi qtyorder, rollback
            if($jumlah_qtydelivered > $podt_obj_lama->qtyorder){
                DB::rollback();

                return response('Error: Quantity delivered exceed quantity order', 500)
                  ->header('Content-Type', 'text/plain');
            }

                //step 5. update qtyoutstand
            $qtyoutstand = $podt_obj_lama->qtyorder - $jumlah_qtydelivered;

            DB::table('material.delorddt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->where('lineno_','=',$request->lineno_)
                ->update([
                    'qtyoutstand' => $qtyoutstand, 
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

                ///1. update detail
                DB::table('material.ivtmpdt')
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$request->recno)
                    ->where('lineno_','=',$value['lineno_'])
                    ->update([
                        'itemcode' => $value['itemcode'],
                        'uomcode' => $value['uomcode'],
                        'txnqty' => $value['txnqty'],
                        'netprice' => $value['netprice'],
                        // 'productcat' => $value['productcat'],
                        'qtyonhand' => $value['qtyonhand'],
                        'uomcoderecv'=> $value['uomcoderecv'],
                        'qtyonhandrecv'=> $value['qtyonhandrecv'],
                        'amount' => $value['amount'],
                        'adduser' => session('username'), 
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                        'expdate' => $value['expdate'],
                        'batchno' => $value['batchno'], 
                        'recstatus' => 'OPEN', 
                        // 'remarks' => $value['remarks']
                    ]);

                ///2. recalculate total amount
                $totalAmount = DB::table('material.ivtmpdt')
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$request->recno)
                    ->where('recstatus','!=','DELETE')
                    ->sum('amount');

                ///3. update total amount to header
                DB::table('material.ivtmphd')
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$request->recno)
                    ->update([
                        'amount' => $totalAmount, 
                    ]);
            }

            DB::commit();
            return response($totalAmount,200);

        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }

    }

}

