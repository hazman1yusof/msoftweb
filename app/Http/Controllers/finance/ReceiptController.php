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
                $tilldeptcode = $till_obj->dept;
               // dd($tilldeptcode);
                $tillno = $tilldetl->first()->tillno;
                $recptno = $till_obj->tillcode.'-'.str_pad($lastrcnumber, 9, "0", STR_PAD_LEFT);

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
                'posteddate' => Carbon::now("Asia/Kuala_Lumpur"),
                'reference' => strtoupper($request->dbacthdr_reference),
                'authno' => strtoupper($request->dbacthdr_authno),
                'expdate' => \Carbon\Carbon::parse($request->dbacthdr_expdate)->endOfMonth()->toDateString(),
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
                'remark' => strtoupper($request->dbacthdr_remark),  
                'tillcode' => $tillcode,  
                'tillno' => $tillno,  
                'recptno' => $recptno,     
                'deptcode' => $tilldeptcode, 
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

            $receipt = DB::table('debtor.dbacthdr')
                            ->where('compcode',session('compcode'))
                            ->where('source','PB')
                            ->where('trantype','RC')
                            ->where('payercode',$request->payercode)
                            ->where('auditno',$request->auditno);

            if($receipt->exists()){
                $receipt_first = $receipt->first();
            }else{
                throw new \Exception("Error no receipt");
            }

            $amt_paid = 0;
            foreach ($request->allo as $key => $value) {
                $invoice = DB::table('debtor.dbacthdr')
                            ->where('compcode',session('compcode'))
                            ->where('source','PB')
                            ->whereIn('trantype',['IN','DN'])
                            ->where('debtorcode',$request->debtorcode)
                            ->where('auditno',$value['obj']['auditno'])
                            ->where('outamount','>',0);

                if($invoice->exists()){

                    $invoice_first = $invoice->first();
                    
                    $invoice->update([
                        'outamount' => $value['obj']['amtbal']
                    ]);

                    $amt_paid+=floatval($value['obj']['amtpaid']);

                }else{
                    throw new \Exception("Error no Invoice");
                }


                $auditno = $this->defaultSysparam('AR','AL');

                DB::table('debtor.dballoc')
                        ->insert([
                            'compcode' => session('compcode'),
                            'source' => 'AR',
                            'trantype' => 'AL',
                            'auditno' => $auditno,
                            'lineno_' => intval($key)+1,
                            'docsource' => $receipt_first->source,
                            'doctrantype' => $receipt_first->trantype,
                            'docauditno' => $receipt_first->auditno,
                            'refsource' => $invoice_first->source,
                            'reftrantype' => $invoice_first->trantype,
                            'refauditno' => $invoice_first->auditno,
                            'refamount' => $invoice_first->amount,
                            'reflineno' => $invoice_first->lineno_,
                            'recptno' => $receipt_first->recptno,
                            'mrn' => $receipt_first->mrn,
                            'episno' => $receipt_first->episno,
                            'allocsts' => 'ACTIVE',
                            'amount' => floatval($value['obj']['amtpaid']),
                            'tillcode' => $receipt_first->tillcode,
                            'debtortype' => $this->get_debtortype($invoice_first->payercode),
                            'debtorcode' => $invoice_first->payercode,
                            'payercode' => $receipt_first->payercode,
                            'paymode' => $receipt_first->paymode,
                            'allocdate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'remark' => 'Allocation '.$receipt_first->source,
                            'balance' => $value['obj']['amtbal'],
                            'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'adduser' => session('username'),
                            'recstatus' => 'POSTED'
                        ]);
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
    
    public function showpdf(Request $request){
        
        $auditno = $request->auditno;
        if(!$auditno){
            abort(404);
        }
        
        $tilldetl = DB::table('debtor.tilldetl')
                    ->where('compcode',session('compcode'))
                    ->where('cashier',session('username'))
                    ->whereNull('closedate')
                    ->first();
        
        // dd($tilldetl);
        
        // if($tilldetl->exists()){
        //     $tilldetl = $tilldetl->first();
            
        //     $dbacthdr = DB::table('debtor.dbacthdr')
        //                 ->select($this->fixPost($request->field,"_"))
        //                 ->leftjoin('hisdb.pat_mast', function($join) use ($request){
        //                     $join = $join->on('pat_mast.MRN', '=', 'dbacthdr.mrn')
        //                                 ->where('pat_mast.compcode','=',session('compcode'));
        //                 })
        //                 ->where('dbacthdr.tillcode',$tilldetl->tillcode)
        //                 ->where('dbacthdr.tillno',$tilldetl->tillno)
        //                 ->where('dbacthdr.compcode',session('compcode'))
        //                 ->whereIn('dbacthdr.trantype',['RC','RD']);
        // }
        
        $dbacthdr = DB::table('debtor.dbacthdr as d', 'hisdb.pat_mast as p')
                    ->select('d.idno', 'd.compcode', 'd.source', 'd.trantype', 'd.auditno', 'd.lineno_', 'd.amount', 'd.outamount', 'd.recstatus', 'd.entrydate', 'd.entrytime', 'd.entryuser', 'd.reference', 'd.recptno', 'd.paymode', 'd.tillcode', 'd.tillno', 'd.debtortype', 'd.payercode', 'd.billdebtor', 'd.remark', 'd.mrn', 'd.episno', 'd.authno', 'd.expdate', 'd.adddate', 'd.epistype', 'd.cbflag', 'd.conversion', 'd.payername', 'd.hdrtype', 'd.currency', 'd.rate', 'd.unit', 'd.invno', 'd.paytype', 'd.bankcharges', 'd.RCCASHbalance', 'd.RCOSbalance', 'd.RCFinalbalance', 'd.PymtDescription', 'd.posteddate', 'p.Name', 'p.Newic')
                    ->leftjoin('hisdb.pat_mast as p', function($join) use ($request){
                        $join = $join->on('p.MRN', '=', 'd.mrn')
                                    ->where('p.compcode','=',session('compcode'));
                    })
                    ->where('auditno','=',$auditno)
                    ->first();
        
        // if ($dbacthdr->recstatus == "ACTIVE") {
        //     $title = "DRAFT";
        // } elseif ($dbacthdr->recstatus == "POSTED"){
        //     $title = "RECEIPT";
        // }
        
        $title = "OFFICIAL RECEIPT";
        
        $dballoc = DB::table('debtor.dballoc as a', 'debtor.debtormast as m')
                    ->select('a.compcode', 'a.source', 'a.trantype', 'a.auditno', 'a.lineno_', 'a.docsource', 'a.doctrantype', 'a.docauditno', 'a.refsource', 'a.reftrantype', 'a.refauditno', 'a.refamount', 'a.reflineno', 'a.recptno', 'a.mrn', 'a.episno', 'a.allocsts', 'a.amount', 'a.tillcode', 'a.debtortype', 'a.debtorcode', 'a.payercode', 'a.paymode', 'a.allocdate', 'a.remark', 'a.balance', 'a.recstatus', 'm.debtorcode', 'm.name')
                    ->leftjoin('debtor.debtormast as m', function($join) use ($request){
                        $join = $join->on('m.debtorcode', '=', 'a.debtorcode')
                                    ->where('m.compcode','=',session('compcode'));
                    })
                    ->where('a.docauditno','=',$auditno)
                    ->where('a.recstatus', '!=', 'CANCELLED')
                    ->get();
        
        $company = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();
        
        $totamount_expld = explode(".", (float)$dbacthdr->amount);
        
        // $totamt_bm_rm = $this->convertNumberToWordBM($totamount_expld[0])." RINGGIT ";
        // $totamt_bm = $totamt_bm_rm." SAHAJA";
        
        // if(count($totamount_expld) > 1){
        //     $totamt_bm_sen = $this->convertNumberToWordBM($totamount_expld[1])." SEN";
        //     $totamt_bm = $totamt_bm_rm.$totamt_bm_sen." SAHAJA";
        // }
        
        $totamt_eng_rm = $this->convertNumberToWordENG($totamount_expld[0])."";
        $totamt_eng = $totamt_eng_rm." ONLY";
        
        if(count($totamount_expld) > 1){
            $totamt_eng_sen = $this->convertNumberToWordENG($totamount_expld[1])." CENT";
            $totamt_eng = $totamt_eng_rm.$totamt_eng_sen." ONLY";
        }
        
        return view('finance.AR.receipt.receipt_pdfmake',compact('tilldetl','dbacthdr','title','dballoc','company','totamt_eng'));
        
        // if(empty($request->type)){
        //     $pdf = PDF::loadView('finance.AP.paymentVoucher.paymentVoucher_pdf',compact('apacthdr','apalloc','totamt_eng','company', 'title'));
        //     return $pdf->stream();
        //     return view('finance.AP.paymentVoucher.paymentVoucher_pdf',compact('apacthdr','apalloc','totamt_eng','company', 'title'));
        // } else {
        //     return view('finance.AP.paymentVoucher.paymentVoucher_pdfmake',compact('apacthdr','apalloc','totamt_eng','company', 'title'));
        // }
        
    }

}

