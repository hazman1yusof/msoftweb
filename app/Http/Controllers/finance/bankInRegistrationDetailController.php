<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class bankInRegistrationDetailController extends defaultController
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

    public function table(Request $request)
    {   
        DB::enableQueryLog();
        switch($request->action){
            case 'maintable':
                if($request->edit == 'true'){
                    return $this->edittable($request);
                }else{
                    return $this->maintable($request);
                }
            default:
                return 'error happen..';
        }
    }

    public function edittable(Request $request){
        $apacthdr = DB::table('finance.apacthdr')
                        // ->where('compcode',session('compcode'))
                        ->where('idno',$request->idno)
                        ->first();

        switch ($apacthdr->paymode) {
            case 'CASH':
                $table = $this->dbacthdr_paymode_cash($apacthdr,'CASH');
                break;
            case 'CHEQUE':
                $table = $this->dbacthdr_paymode_cash($apacthdr,'CHEQUE');
                break;
            case 'CARD':
                $table = $this->dbacthdr_paymode_card($apacthdr);
                break;
        }

        $paginate = $table->paginate($request->rows);

        //////////paginate/////////

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

    public function dbacthdr_paymode_cash($apacthdr){
        $table = DB::table('debtor.dbacthdr as db')
                    ->select('db.idno','db.compcode','db.source','db.trantype as reftype','db.auditno as allocauditno','db.lineno_','db.idno as lineno_','db.amount as refamount','db.outamount','db.recstatus','db.entrydate','db.entrytime','db.entryuser','db.reference as rereference','db.recptno as refrecptno','db.paymode as refpaymode','db.tillcode','db.tillno','db.debtortype','db.debtorcode','db.payercode','db.billdebtor','db.remark','db.mrn','db.episno','db.authno','db.expdate','db.adddate','db.adduser','db.upddate','db.upduser','db.deldate','db.deluser','db.epistype','db.cbflag','db.conversion','db.payername','db.hdrtype','db.currency','db.rate','db.unit','db.invno','db.paytype','db.bankcharges','db.RCCASHbalance','db.RCOSbalance','db.RCFinalbalance','db.PymtDescription','db.orderno','db.ponum','db.podate','db.termdays','db.termmode','db.deptcode','db.posteddate as refdocdate','db.approvedby','db.approveddate','db.approved_remark','db.unallocated','db.datesend','db.quoteno','db.preparedby','db.prepareddate','db.cancelby','db.canceldate','db.cancelled_remark','db.pointofsales','db.doctorcode','db.LHDNStatus','db.LHDNSubID','db.LHDNCodeNo','db.LHDNDocID','db.LHDNSubBy')
                    ->where('db.compcode',session('compcode'))
                    ->where('db.source','PB')
                    ->whereIn('db.trantype',['RC','RD'])
                    ->where('db.posteddate','<=',$apacthdr->postdate)
                    ->where('db.paymode',$apacthdr->paymode)
                    ->where('db.cbflag',0);

        return $table;
    }

    public function dbacthdr_paymode_card($apacthdr){
        $table = DB::table('debtor.dbacthdr as db')
                    ->select('db.idno','db.compcode','db.source','db.trantype','db.auditno','db.lineno_','db.amount','db.outamount','db.recstatus','db.entrydate','db.entrytime','db.entryuser','db.reference','db.recptno','db.paymode','db.tillcode','db.tillno','db.debtortype','db.debtorcode','db.payercode','db.billdebtor','db.remark','db.mrn','db.episno','db.authno','db.expdate','db.adddate','db.adduser','db.upddate','db.upduser','db.deldate','db.deluser','db.epistype','db.cbflag','db.conversion','db.payername','db.hdrtype','db.currency','db.rate','db.unit','db.invno','db.paytype','db.bankcharges','db.RCCASHbalance','db.RCOSbalance','db.RCFinalbalance','db.PymtDescription','db.orderno','db.ponum','db.podate','db.termdays','db.termmode','db.deptcode','db.posteddate','db.approvedby','db.approveddate','db.approved_remark','db.unallocated','db.datesend','db.quoteno','db.preparedby','db.prepareddate','db.cancelby','db.canceldate','db.cancelled_remark','db.pointofsales','db.doctorcode','db.LHDNStatus','db.LHDNSubID','db.LHDNCodeNo','db.LHDNDocID','db.LHDNSubBy')
                    ->join('debtor.paymode as py', function($join) use ($apacthdr){
                            $join = $join
                                ->where('py.compcode',session('compcode'))
                                ->where('py.source','AR')
                                ->where('py.paytype','CARD')
                                ->on('py.paymode','db.paymode');
                        })
                    ->leftjoin('finance.cardcent as cc', function($join) use ($apacthdr){
                            $join = $join
                                ->where('cc.compcode',session('compcode'))
                                ->where('py.cardcode','AR')
                                ->where('py.paytype','CARD')
                                ->on('cc.cardcode','py.cardcent');
                        })
                    ->where('db.compcode',session('compcode'))
                    ->where('db.source','PB')
                    ->whereIn('db.trantype',['RC','RD'])
                    ->where('db.posteddate','<=',$apacthdr->postdate)
                    ->where('db.paymode',$apacthdr->paymode)
                    ->where('db.cbflag',0);

        return $table;
    }

    public function maintable(Request $request){
        dd('hi');
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

            $auditno = $request->auditno;

            ////1. calculate lineno_ by auditno
            $sqlln = DB::table('finance.apactdtl')->select('lineno_')
                        ->where('compcode','=',session('compcode'))
                        ->where('source','=','CM')
                        ->where('trantype','=','DP')
                        ->where('auditno','=',$auditno)
                        ->count('lineno_');

            $li=intval($sqlln)+1;

            ///2. insert detail
            DB::table('finance.apactdtl')
                ->insert([
                    'compcode' => session('compcode'),
                    'auditno' => $auditno,
                    'lineno_' => $li,
                    'source' => 'CM',
                    'trantype' => 'DP',
                    'document' => strtoupper($request->document),
                    'amount' => floatval($gstcode_obj->amount),
                    'taxamt' => floatval($gstcode_obj->tot_gst),
                    'GSTCode' => strtoupper($request->GSTCode),
                    'AmtB4GST' => floatval($gstcode_obj->AmtB4GST),
                    'category' => strtoupper($request->category),
                    'deptcode' => strtoupper($request->deptcode),
                    'adduser' => session('username'), 
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                    'recstatus' => 'OPEN',
                    'unit' => session('unit')
                ]);

            ///3. calculate total amount from detail
            $totalAmount = DB::table('finance.apactdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('auditno','=',$auditno)
                    ->where('source','=','CM')
                    ->where('trantype','=','DP')
                    ->where('recstatus','!=','DELETE')
                    ->sum('amount');

       
            ///4. then update to header
            DB::table('finance.apacthdr')
                ->where('compcode','=',session('compcode'))
                ->where('auditno','=',$auditno)
                ->where('source','=','CM')
                ->where('trantype','=','DP')
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

            ///1. update detail
            DB::table('finance.apactdtl')
                ->where('compcode','=',session('compcode'))
                ->where('auditno','=',$request->auditno)
                ->where('lineno_','=',$request->lineno_)
                ->update([
                    'source' => 'CM',
                    'trantype' => 'DP',
                    'document' => strtoupper($request->document),
                    'amount' => $request->amount,
                    'taxamt' => $request->tot_gst,
                    'GSTCode' => $request->GSTCode,
                    'AmtB4GST' => $request->AmtB4GST,
                    'category' => $request->category,
                    'deptcode' => $request->deptcode,
                    'adduser' => session('username'), 
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                    'recstatus' => 'OPEN',
                    'unit' => session('unit')
                ]);

            ///2. recalculate total amount
            $totalAmount = DB::table('finance.apactdtl')
                ->where('compcode','=',session('compcode'))
                ->where('source','=','CM')
                ->where('trantype','=','DP')
                ->where('auditno','=',$request->auditno)
                ->where('recstatus','!=','DELETE')
                ->sum('amount');

            ///3. update total amount to header
            DB::table('finance.apacthdr')
                ->where('compcode','=',session('compcode'))
                ->where('auditno','=',$request->auditno)
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

    public function del(Request $request){

        DB::beginTransaction();

        try {

            ///1. update detail
            DB::table('finance.apactdtl')
                ->where('compcode','=',session('compcode'))
                ->where('source','=','CM')
                ->where('trantype','=','DP')
                ->where('auditno','=',$request->auditno)
                ->where('lineno_','=',$request->lineno_)
                ->delete();

            ///2. recalculate total amount
            $totalAmount = DB::table('finance.apactdtl')
                ->where('compcode','=',session('compcode'))
                ->where('source','=','CM')
                ->where('trantype','=','DP')
                ->where('auditno','=',$request->auditno)
                ->where('recstatus','!=','DELETE')
                ->sum('amount');

          
            ///3. update total amount to header
            DB::table('finance.apacthdr')
                ->where('compcode','=',session('compcode'))
                ->where('source','=','CM')
                ->where('trantype','=','DP')
                ->where('auditno','=',$request->auditno)
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
                        'document' => strtoupper($value['document']),
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
                    ->where('auditno','=',$request->auditno)
                    ->where('source','=','CM')
                    ->where('trantype','=','DP')
                    ->where('recstatus','!=','DELETE')
                    ->sum('amount');

                ///3. update total amount to header
                DB::table('finance.apacthdr')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','=','CM')
                    ->where('trantype','=','DP')
                    ->where('auditno','=',$request->auditno)
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
        
        $tot_gst_real = $request->tot_gst;
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

        $tot_gst_real = floatval($value['tot_gst']);
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

