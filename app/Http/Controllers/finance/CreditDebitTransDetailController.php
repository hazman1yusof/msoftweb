<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class CreditDebitTransDetailController extends defaultController
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

    public function add(Request $request){
        DB::beginTransaction();
        try {
           
            $auditno = $request->query('auditno');

            ////1. calculate lineno_ by auditno
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
                    'source' => 'CM',
                    'trantype' => strtoupper($request->trantype),
                    'category' => $request->category,
                    'deptcode' => strtoupper($request->deptcode),
                    'document' => strtoupper($request->document),
                    'amount' => $request->amount,
                    'taxamt' => $request->tot_gst,
                    'GSTCode' => $request->GSTCode,
                    'AmtB4GST' => $request->AmtB4GST,
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
                    'compcode' => session('compcode'),
                    'source' => 'CM',
                    'trantype' => $trantype,
                    'category' => $request->category,
                    'deptcode' => strtoupper($request->deptcode),
                    'document' => strtoupper($request->document),
                    'amount' => $request->amount,
                    'taxamt' => $request->tot_gst,
                    'GSTCode' => $request->GSTCode,
                    'AmtB4GST' => $request->AmtB4GST,
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


    public function edit_all(Request $request){

        DB::beginTransaction();

        try {

            foreach ($request->dataobj as $key => $value) {

                ///1. update detail
                DB::table('finance.apactdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('idno','=',$value['idno'])
                    ->update([
                        'document' => strtoupper($value['document']),
                        'amount' => $value['amount'],
                        'AmtB4GST' => $value['AmtB4GST'],
                        'taxamt' => $value['tot_gst'],
                        'category' => $value['category'],
                        'deptcode' => strtoupper($value['deptcode']),
                        'upduser' => session('username'), 
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                       
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

            return response('Error'.$e, 500);
        }

    }

}

