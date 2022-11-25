<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;

class ReceiptController extends defaultController
{   

    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
        $this->duplicateCode = "Code";
    }

    public function show(Request $request)
    {   
        return view('finance.AR.receipt.receipt');
    }

    public function table(Request $request)
    {   
        switch($request->action){
            case 'get_debtorcode_outamount':
                return $this->get_debtorcode_outamount($request);
            case 'maintable':
                return $this->maintable($request);
            default:
                return 'error happen..';
        }
    }

    public function form(Request $request)
    {   
        switch($request->oper){
            case 'add':
                return $this->add($request);
            case 'edit':
                // return $this->defaultEdit($request);
            case 'del':
                // return $this->defaultDel($request);
            case 'allocate':
                return $this->allocate($request);
            default:
                return 'error happen..';
        }
    }

    public function add(Request $request){
        DB::beginTransaction();

        try{

            $auditno = $this->defaultSysparam('PB',$request->dbacthdr_trantype);

            $till = DB::table('debtor.till')
                            ->where('compcode',session('compcode'))
                            ->where('tillstatus','O')
                            ->where('lastuser',session('username'));

            if($till->exists()){

                $till_obj = $till->first();

                $tilldetl = DB::table('debtor.tilldetl')
                            ->where('compcode',session('compcode'))
                            ->where('cashier',$till_obj->lastuser)
                            ->where('opendate','=',$till_obj->upddate);

                $lastrcnumber = $this->defaultTill($till_obj->tillcode,'lastrcnumber');

                $tillcode = $till_obj->tillcode;
                $tillno = $tilldetl->first()->tillno;
                $recptno = $till_obj->tillcode.str_pad($lastrcnumber, 9, "0", STR_PAD_LEFT);

            }else{
                throw new \Exception("User dont have till");
            }

            $paymode_ = $this->paymode_chg($request->dbacthdr_paytype,$request->dbacthdr_paymode);

            $array_insert = [
                'compcode' => session('compcode'),
                'unit' => session('unit'),
                'adduser' => session('username'),
                'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                'entrydate' => Carbon::now("Asia/Kuala_Lumpur"),
                'entrytime' => Carbon::now("Asia/Kuala_Lumpur"),
                'reference' => $paymode_.' - '.$recptno,
                'entryuser' => session('username'),
                'recstatus' => 'ACTIVE',
                'source' => 'PB',
                'trantype' => $request->dbacthdr_trantype,
                'auditno' => $auditno,
                'lineno_' => $request->dbacthdr_lineno_,
                'currency' => $request->dbacthdr_currency,
                'debtortype' => $request->dbacthdr_debtortype,
                'PymtDescription' => $request->dbacthdr_PymtDescription,
                'payercode' => $request->dbacthdr_payercode,
                'debtorcode' => $request->dbacthdr_payercode,
                'payername' => $request->dbacthdr_payername,
                'paytype' => $request->dbacthdr_paytype,
                'paymode' => $paymode_,
                'amount' => $request->dbacthdr_amount,  
                'outamount' => $request->dbacthdr_amount,  
                'remark' => $request->dbacthdr_remark,  
                'tillcode' => $tillcode,  
                'tillno' => $tillno,  
                'recptno' => $recptno,     
            ];

            if($request->dbacthdr_trantype == "RD"){
                $array_insert_RD = [
                    'hdrtype' => $request->dbacthdr_hdrtype,
                    'mrn' => $request->dbacthdr_mrn,
                    'episno' => $request->dbacthdr_episno
                ];


                $array_insert = array_merge($array_insert, $array_insert_RD);
            }else{
                $array_insert_RC = [
                    'hdrtype' => 'RC',
                ];
                $array_insert = array_merge($array_insert, $array_insert_RC);
            }

            DB::table('debtor.dbacthdr')
                        ->insert($array_insert);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage().$e, 500);
        }
    }

    public function maintable(Request $request){

        $tilldetl = DB::table('debtor.tilldetl')
                        ->where('compcode',session('compcode'))
                        ->where('cashier',session('username'))
                        ->whereNull('closedate');

        if($tilldetl->exists()){
            $tilldetl = $tilldetl->first();

            $table = DB::table('debtor.dbacthdr')
                            ->select($this->fixPost($request->field,"_"))
                            ->leftjoin('hisdb.pat_mast', function($join) use ($request){
                                $join = $join->on('pat_mast.MRN', '=', 'dbacthdr.mrn')
                                            ->where('pat_mast.compcode','=',session('compcode'));
                            })
                            ->where('dbacthdr.tillcode',$tilldetl->tillcode)
                            ->where('dbacthdr.tillno',$tilldetl->tillno)
                            ->where('dbacthdr.compcode',session('compcode'))
                            ->whereIn('dbacthdr.trantype',['RC','RD']);

            $paginate = $table->paginate($request->rows);

            $responce = new stdClass();
            $responce->page = $paginate->currentPage();
            $responce->total = $paginate->lastPage();
            $responce->records = $paginate->total();
            $responce->rows = $paginate->items();
            $responce->sql = $table->toSql();
            $responce->sql_bind = $table->getBindings();
            $responce->sql_query = $this->getQueries($table);
        }

        return json_encode($responce);  
    }

    public function get_debtorcode_outamount(Request $request){
        $dbacthdr = DB::table('debtor.dbacthdr')
                        ->where('compcode',session('compcode'))
                        ->where('payercode',$request->payercode)
                        ->where('source','PB')
                        ->where('recstatus','POSTED')
                        ->where('outamount','>',0)
                        ->whereIn('trantype',['DN','IN']);



        $responce = new stdClass();


        if($dbacthdr->exists()){
            $responce->result = 'true';
            $responce->outamount = $dbacthdr->sum('dbacthdr.outamount');
        }else{
            $responce->result = 'false';
        }


        return json_encode($responce);
    }

    public function allocate(Request $request){
        DB::beginTransaction();

        try{

            $amt_paid = 0;
            foreach ($request->allo as $key => $value) {
                $allo = DB::table('debtor.dbacthdr')
                            ->where('compcode',session('compcode'))
                            ->where('source','PB')
                            ->whereIn('trantype',['IN','DN'])
                            ->where('debtorcode',$request->debtorcode)
                            ->where('auditno',$value['obj']['auditno'])
                            ->where('outamount','>',0);

                if($allo->exists()){
                    $allo->update([
                        'outamount' => $value['obj']['amtbal']
                    ]);

                    $amt_paid+=floatval($value['obj']['amtpaid']);
                }
            }

            if($amt_paid > 0){
                
                $receipt = DB::table('debtor.dbacthdr')
                            ->where('compcode',session('compcode'))
                            ->where('source','PB')
                            ->where('trantype','RC')
                            ->where('payercode',$request->payercode)
                            ->where('auditno',$request->auditno);

                if($receipt->exists()){

                    $receipt_first = $receipt->first();
                    
                    $out_amt = floatval($receipt_first->outamount) - floatval($amt_paid);

                    $receipt->update([
                        'outamount' => $out_amt
                    ]);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage().$e, 500);
        }
    }

    public function paymode_chg($paytype,$paymode){
        $paytype_ = '';
        $mode = false;
        switch (strtolower($paytype)) {
            case '#f_tab-cash':
                $paytype_ = 'Cash';
                break;
            case '#f_tab-card':
                $paytype_ = 'Card';
                $mode = true;
                break;
            case '#f_tab-cheque':
                $paytype_ = 'Cheque';
                break;
            case '#f_tab-debit':
                $paytype_ = 'Bank';
                $mode = true;
                break;
            case '#f_tab-forex':
                $paytype_ = 'Forex';
                break;
        }

        if($paytype_ != ''){

            $paymode_db = DB::table('debtor.paymode')
                            ->where('compcode',session('compcode'))
                            ->where('source','AR')
                            ->where('paytype',$paytype_);

            if($mode == true){
                $paymode_db = $paymode_db->where('paymode',$paymode);
            }

            if(!$paymode_db->exists()){
                throw new \Exception("No Paymode");
            }

            $paymode_first  = $paymode_db->first();

            return $paymode_first->paymode;

        }else{
            throw new \Exception("Error paytype");
        }
    }
}

