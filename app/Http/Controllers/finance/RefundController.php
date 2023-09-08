<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;

class RefundController extends defaultController
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
        return view('finance.AR.Refund.refund');
    }

    public function table(Request $request)
    {   
        switch($request->action){
            case 'get_debtorcode_outamount':
                return $this->get_debtorcode_outamount($request);
            case 'refund_allo_table':
                return $this->refund_allo_table($request);
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
                            ->leftjoin('debtor.paymode as paycard', function($join) use ($request){
                                $join = $join->on('paycard.paymode', '=', 'dbacthdr.paymode')
                                            ->where('paycard.compcode','=',session('compcode'))
                                            ->where('paycard.source','=','AR')
                                            ->where('paycard.paytype','=','CARD');
                            })
                            ->leftjoin('debtor.paymode as paybank', function($join) use ($request){
                                $join = $join->on('paybank.paymode', '=', 'dbacthdr.paymode')
                                            ->where('paybank.compcode','=',session('compcode'))
                                            ->where('paybank.source','=','AR')
                                            ->where('paybank.paytype','=','BANK');
                            })
                            ->where('dbacthdr.tillcode',$tilldetl->tillcode)
                            ->where('dbacthdr.tillno',$tilldetl->tillno)
                            ->where('dbacthdr.compcode',session('compcode'))
                            ->where('dbacthdr.trantype','RF');
            
            if(!empty($request->filterCol)){
                foreach ($request->filterCol as $key => $value) {
                    $pieces = explode(".", $request->filterVal[$key], 2);
                    if($pieces[0] == 'session'){
                        $table = $table->where($request->filterCol[$key],'=',session($pieces[1]));
                    }else if($pieces[0] == '<>'){
                        $table = $table->where($request->filterCol[$key],'<>',$pieces[1]);
                    }else if($pieces[0] == '>'){
                        $table = $table->where($request->filterCol[$key],'>',$pieces[1]);
                    }else if($pieces[0] == '>='){
                        $table = $table->where($request->filterCol[$key],'>=',$pieces[1]);
                    }else if($pieces[0] == '<'){
                        $table = $table->where($request->filterCol[$key],'<',$pieces[1]);
                    }else if($pieces[0] == '<='){
                        $table = $table->where($request->filterCol[$key],'<=',$pieces[1]);
                    }else if($pieces[0] == 'on'){
                        $table = $table->whereColumn($request->filterCol[$key],$pieces[1]);
                    }else if($pieces[0] == 'null'){
                        $table = $table->whereNull($request->filterCol[$key]);
                    }else if($pieces[0] == 'raw'){
                        $table = $table->where($request->filterCol[$key],'=',DB::raw($pieces[1]));
                    }else{
                        $table = $table->where($request->filterCol[$key],'=',$request->filterVal[$key]);
                    }
                }
            }

            if(!empty($request->fromdate)){
                $table = $table->where('dbacthdr.entrydate','>=',$request->fromdate);
                $table = $table->where('dbacthdr.entrydate','<=',$request->todate);
            }
        
            if(!empty($request->searchCol)){
                if(!empty($request->fixPost)){
                    $searchCol_array = $this->fixPost3($request->searchCol);
                }else{
                    $searchCol_array = $request->searchCol;
                }
                
                $count = array_count_values($searchCol_array);
                // dump($request->searchCol);

                foreach ($count as $key => $value) {
                    $occur_ar = $this->index_of_occurance($key,$searchCol_array);

                    $table = $table->where(function ($table) use ($request,$searchCol_array,$occur_ar) {
                        foreach ($searchCol_array as $key => $value) {
                            $found = array_search($key,$occur_ar);
                            if($found !== false){
                                $table->Where($searchCol_array[$key],'like',$request->searchVal[$key]);
                            }
                        }
                    });
                }
            }

            if(!empty($request->sidx)){

                $pieces = explode(", ", $request->sidx .' '. $request->sord);

                if(count($pieces)==1){
                    $table = $table->orderBy($request->sidx, $request->sord);
                }else{
                    foreach ($pieces as $key => $value) {
                        $value_ = substr_replace($value,"db.",0,strpos($value,"_")+1);
                        $pieces_inside = explode(" ", $value_);
                        $table = $table->orderBy($pieces_inside[0], $pieces_inside[1]);
                    }
                }
            }else{
                $table = $table->orderBy('db.idno','DESC');
            }
            
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

    public function refund_allo_table(Request $request){

        $table = DB::table('debtor.dbacthdr')
                        ->where('dbacthdr.payercode',$request->payercode)
                        ->where('dbacthdr.compcode',session('compcode'))
                        ->where('dbacthdr.outamount','>',0)
                        ->where('dbacthdr.source','PB')
                        ->whereIn('dbacthdr.trantype',['RD','RC']);
       // dd($request->payercode);
        $paginate = $table->paginate($request->rows);

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

    public function add(Request $request){
        DB::beginTransaction();

        try{

            $auditno = $this->defaultSysparam('PB','RF');

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

                $lastrefundno = $this->defaultTill($till_obj->tillcode,'lastrefundno');

                $tillcode = $till_obj->tillcode;
                $tillno = $tilldetl->first()->tillno;
                $refundno = $till_obj->tillcode.'-'.str_pad($lastrefundno, 9, "0", STR_PAD_LEFT);

            }else{
                throw new \Exception("User dont have till");
            }

            $outamount_ = floatval($request->dbacthdr_amount) - floatval($request->dbacthdr_allocamt);

            $paymode_ = $this->paymode_chg($request->dbacthdr_paytype,$request->dbacthdr_paymode);

            $array_insert = [
                'compcode' => session('compcode'),
                'unit' => session('unit'),
                'adduser' => session('username'),
                'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                'recstatus' => 'ACTIVE',
                'source' => 'PB',
                'trantype' => 'RF',
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
                'outamount' => $outamount_,  
                'remark' => $request->dbacthdr_remark,  
                'tillcode' => $tillcode,  
                'tillno' => $tillno,  
                'recptno' => $refundno,     
            ];

            $latestidno = DB::table('debtor.dbacthdr')
                                ->insertGetId($array_insert);

            $refund_first = DB::table('debtor.dbacthdr')
                                ->where('idno',$latestidno)
                                ->first();

            $amt_paid = 0;
            foreach ($request->allo as $key => $value) {
                $receipt = DB::table('debtor.dbacthdr')
                            ->where('compcode',session('compcode'))
                            ->where('source','PB')
                            ->whereIn('trantype',['RD','RC'])
                            ->where('payercode',$request->dbacthdr_payercode)
                            ->where('auditno',$value['obj']['auditno'])
                            ->where('outamount','>',0);

                if($receipt->exists()){

                    $receipt_first = $receipt->first();
                    
                    $receipt->update([
                        'outamount' => $value['obj']['amtbal']
                    ]);

                    $amt_paid+=floatval($value['obj']['amtpaid']);

                }else{
                    throw new \Exception("Error no Receipt");
                }

                $auditno = $this->defaultSysparam('AR','AL');

                DB::table('debtor.dballoc')
                        ->insert([
                            'compcode' => session('compcode'),
                            'source' => 'AR',
                            'trantype' => 'AL',
                            'auditno' => $auditno,
                            'lineno_' => intval($key)+1,
                            'docsource' => $refund_first->source,
                            'doctrantype' => $refund_first->trantype,
                            'docauditno' => $refund_first->auditno,
                            'refsource' => $receipt_first->source,
                            'reftrantype' => $receipt_first->trantype,
                            'refauditno' => $receipt_first->auditno,
                            'refamount' => $receipt_first->amount,
                            'reflineno' => $receipt_first->lineno_,
                            'recptno' => $refund_first->recptno,
                            'mrn' => $refund_first->mrn,
                            'episno' => $refund_first->episno,
                            'allocsts' => 'ACTIVE',
                            'amount' => floatval($value['obj']['amtpaid']),
                            'tillcode' => $refund_first->tillcode,
                            'debtortype' => $this->get_debtortype($refund_first->payercode),
                            'debtorcode' => $refund_first->payercode,
                            'payercode' => $refund_first->payercode,
                            'paymode' => $refund_first->paymode,
                            'allocdate' => Carbon::now("Asia/Kuala_Lumpur"),
                            // 'remark' => 'Allocation '.$refund_first->source,
                            'balance' => $value['obj']['amtbal'],
                            'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'adduser' => session('username'),
                            'recstatus' => 'ACTIVE'
                        ]);
            }

            // if($amt_paid > 0){
                
            //     $refund = DB::table('debtor.dbacthdr')
            //                             ->where('idno',$latestidno);

            //     if($refund->exists()){

            //         $refund_first = $refund->first();
                    
            //         $out_amt = floatval($refund_first->outamount) - floatval($amt_paid);

            //         $receipt->update([
            //             'outamount' => $out_amt
            //         ]);
            //     }
            // }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage().$e, 500);
        }
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

    public function get_debtortype($debtorcode){
        $debtormast = DB::table('debtor.debtormast')
                            ->where('compcode',session('compcode'))
                            ->where('debtorcode',$debtorcode);

        if($debtormast->exists()){
            $debtormast_ = $debtormast->first();
            return $debtormast_->debtortype;
        }else{
            return null;
        }

    }
}