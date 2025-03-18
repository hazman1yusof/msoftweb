<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Auth;
use Carbon\Carbon;
use DateTime;


class bankInRegistrationController extends defaultController
{   

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   

        $unit = DB::table('sysdb.sector')
                    ->where('compcode',session('compcode'))
                    ->get();

        return view('finance.CM.bankInRegistration.bankInRegistration',compact('unit'));
    }

    public function form(Request $request)
    {   
        switch($request->oper){
            case 'add':
                return $this->add($request);
            case 'edit':
                return $this->edit($request);
            case 'del':
                return $this->del($request);
            case 'posted':
                return $this->posted($request);
            default:
                return 'error happen..';
        }
    }

    public function table(Request $request)
    {   
        DB::enableQueryLog();
        switch($request->action){
            case 'maintable':
                return $this->maintable($request);
            default:
                return 'error happen..';
        }
    }

    public function maintable(Request $request){

        $table = DB::table('finance.apacthdr AS ap')
                    ->select(
                        'ap.compcode AS compcode',
                        'ap.auditno AS auditno',
                        'ap.trantype AS trantype',
                        'ap.doctype AS doctype',
                        'ap.suppcode AS suppcode',
                        'ap.actdate AS actdate',
                        'ap.document AS document',
                        'ap.cheqno AS cheqno',
                        'ap.deptcode AS deptcode',
                        'ap.amount AS amount',
                        'ap.outamount AS outamount',
                        'ap.recstatus AS recstatus',
                        'ap.payto AS payto',
                        'su.name AS supplier_name', 
                        'ap.recdate AS recdate',
                        'ap.category AS category',
                        'ap.remarks AS remarks',
                        'ap.adduser AS adduser',
                        'ap.adddate AS adddate',
                        'ap.upduser AS upduser',
                        'ap.upddate AS upddate',
                        'ap.source AS source',
                        'ap.idno AS idno',
                        'ap.unit AS unit',
                        'ap.pvno AS pvno',
                        'ap.paymode AS paymode',
                        'ap.bankcode AS bankcode',
                        'ap.TaxClaimable AS TaxClaimable',
                    )
                    ->leftJoin('material.supplier as su', 'su.SuppCode', '=', 'ap.payto')
                    ->where('ap.compcode','=', session('compcode'))
                    ->where('ap.source','=', 'CM')
                    ->where('ap.trantype', '=','DP');

        if(!empty($request->filterCol)){
            $table = $table->where($request->filterCol[0],'=',$request->filterVal[0]);
        }

        if(!empty($request->filterdate)){
            $table = $table->where('ap.actdate','>=',$request->filterdate[0]);
            $table = $table->where('ap.actdate','<=',$request->filterdate[1]);
        }

        if(!empty($request->searchCol)){
            if($request->searchCol[0] == 'bankcode'){
                $table = $table->Where(function ($table) use ($request) {
                        $table->Where('ap.bankcode','like',$request->searchVal[0]);
                    });
            } else if($request->searchCol[0] == 'payto'){
                $table = $table->Where(function ($table) use ($request) {
                        $table->Where('ap.payto','like',$request->searchVal[0]);
                    });
            }else if($request->searchCol[0] == 'auditno'){
                $table = $table->Where(function ($table) use ($request) {
                        $table->Where('ap.auditno','like',$request->searchVal[0]);
                    });
            }else{
                $table = $table->Where(function ($table) use ($request) {
                        $table->Where($request->searchCol[0],'like',$request->searchVal[0]);
                    });
            }
            
        }

        if(!empty($request->sidx)){

            $pieces = explode(", ", $request->sidx .' '. $request->sord);

            if(count($pieces)==1){
                $table = $table->orderBy($request->sidx, $request->sord);
            }else{
                foreach ($pieces as $key => $value) {
                    // $value_ = substr_replace($value,"ap.",0,strpos($value,"_")+1);
                    $value_ = 'ap.'.$value;
                    $pieces_inside = explode(" ", $value_);
                    $table = $table->orderBy($pieces_inside[0], $pieces_inside[1]);
                }
            }
        }else{
            $table = $table->orderBy('ap.idno','DESC');
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

    public function add(Request $request){
        DB::beginTransaction();

        try{

            $auditno = $this->defaultSysparam('CM','DP');
            // $pvno = $this->defaultSysparam('HIS','PV');
            $amount = 0;

            $idno = DB::table('finance.apacthdr')
                    ->insertGetId([
                        'auditno' => $auditno,
                        'bankcode' => strtoupper($request->bankcode),
                        'payto' => strtoupper($request->payto),
                        'actdate' => $request->actdate,
                        'amount' => $amount,
                        'paymode' => strtoupper($request->paymode),
                        'cheqno' => $request->cheqno,
                        'remarks' => strtoupper($request->remarks),
                        'TaxClaimable' => $request->TaxClaimable,
                        // 'pvno' => $pvno,
                        'cheqdate' => $request->cheqdate,
                        'source' => 'CM',
                        'trantype' => 'DP',
                        'compcode' => session('compcode'),
                        'unit' => session('unit'),
                        'adduser' => session('username'),
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'recstatus' => 'OPEN'
                    ]);

            if($request->paymode == 'TT'){
                $last_tt = $this->defaultSysparam('CM','TT');
                $array_insert['cheqno'] = $last_tt;
            }

            $responce = new stdClass();
            $responce->auditno = $auditno;
            $responce->pvno = '';
            $responce->idno = $idno;
            $responce->amount = 0;

            echo json_encode($responce);

            DB::commit();
        
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage().$e, 500);
        }
    }

    public function posted(Request $request){

        DB::beginTransaction();

        try {

            foreach ($request->idno_array as $idno){
                $apacthdr = DB::table('finance.apacthdr')
                            ->where('idno','=',$idno);

                $apacthdr_get = $apacthdr->first();
                $yearperiod = $this->getyearperiod($apacthdr_get->actdate);
                $pvno = $this->defaultSysparam('HIS','PV');

                //1st step add cbtran credit
                DB::table('finance.cbtran')
                    ->insert([  
                        'compcode' => $apacthdr_get->compcode, 
                        'bankcode' => $apacthdr_get->bankcode, 
                        'source' => $apacthdr_get->source, 
                        'trantype' => $apacthdr_get->trantype, 
                        'auditno' => $apacthdr_get->auditno, 
                        'postdate' => $apacthdr_get->actdate, 
                        'year' => $yearperiod->year, 
                        'period' => $yearperiod->period, 
                        'cheqno' => $apacthdr_get->cheqno, 
                        'amount' => -$apacthdr_get->amount, 
                        'remarks' => strtoupper($apacthdr_get->remarks), 
                        'upduser' => session('username'), 
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                        'reference' => 'Pay To :'. ' ' .$apacthdr_get->payto  .' '. $apacthdr_get->remarks, 
                        'recstatus' => 'ACTIVE' 
                    ]);

                //1st step, 2nd phase, update bank detaild
                if($this->isCBtranExist($apacthdr_get->bankcode,$yearperiod->year,$yearperiod->period)){

                    $totamt = $this->getCbtranTotamt($apacthdr_get->bankcode,$yearperiod->year,$yearperiod->period);

                    DB::table('finance.bankdtl')
                        ->where('compcode','=',session('compcode'))
                        ->where('year','=',$yearperiod->year)
                        ->where('bankcode','=',$apacthdr_get->bankcode)
                        ->update([
                            "actamount".$yearperiod->period => $totamt->amount
                        ]);

                }else{
                    $totamt = $this->getCbtranTotamt($apacthdr_get->bankcode,$yearperiod->year,$yearperiod->period);

                    DB::table('finance.bankdtl')
                            ->insert([
                                'compcode' => session('compcode'),
                                'bankcode' => $apacthdr_get->bankcode,
                                'year' => $yearperiod->year,
                                'actamount'.$yearperiod->period => $totamt->amount,
                                'upduser' => session('username'),
                                'upddate' => Carbon::now("Asia/Kuala_Lumpur"),

                            ]);
                }

                //2nd step step add gltran   
                $queryDP_obj = DB::table('finance.apacthdr')
                        ->select ('apactdtl.compcode', 'apactdtl.source', 'apactdtl.trantype', 'apactdtl.auditno', 'apactdtl.lineno_', 'apactdtl.document', 'apacthdr.remarks', 'apactdtl.deptcode', 'apactdtl.category', 'apacthdr.bankcode', 'apactdtl.amount', 'apacthdr.actdate', 'apactdtl.AmtB4GST')
                        ->join('finance.apactdtl', function($join) use ($request){
                            $join = $join->on('apactdtl.auditno', '=', 'apacthdr.auditno');
                            $join = $join->on('apactdtl.compcode', '=', 'apacthdr.compcode');
                            $join = $join->on('apactdtl.source', '=', 'apacthdr.source');
                            $join = $join->on('apactdtl.trantype', '=', 'apacthdr.trantype');
                        })
                        ->where('apactdtl.compcode', '=', session('compcode'))
                        ->where('apactdtl.source', '=', $apacthdr_get->source)
                        ->where('apactdtl.trantype', '=', $apacthdr_get->trantype)
                        ->where('apactdtl.auditno', '=', $apacthdr_get->auditno)
                        ->get();

                if($apacthdr_get->TaxClaimable == "Claimable" ){
                    $gst = $this->getTax('TX', 'BS');
                }else{
                    $gst = $this->getTax('TX', 'PNL');
                }

                foreach ($queryDP_obj as $key => $apactdtl) {
                    $creditbank = $this->getGLcode($apacthdr_get->bankcode);
                    $debitbank = $this->getDept($apactdtl->deptcode);
                    $debitbank2 = $this->getCat($apactdtl->category);
                    
                    DB::table('finance.gltran')
                                ->insert([
                                    'compcode' => session('compcode'),
                                    'auditno' => $apactdtl->auditno,
                                    'lineno_' => $apactdtl->lineno_,
                                    'source' => $apactdtl->source,
                                    'trantype' => $apactdtl->trantype,
                                    'reference' => $apacthdr_get->cheqno,
                                    'description' => strtoupper($apacthdr_get->remarks),
                                    'year' => $yearperiod->year,
                                    'period' => $yearperiod->period,
                                    'drcostcode' => $debitbank->costcode,
                                    'crcostcode' => $creditbank->glccode,
                                    'dracc' => $debitbank2->expacct,
                                    'cracc' => $creditbank->glaccno,
                                    'amount' => $apactdtl->AmtB4GST,
                                    'postdate' => $apacthdr_get->actdate,
                                    'adduser' => session('username'),
                                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                                ]);

                    if($this->isGltranExist($debitbank->costcode,$debitbank2->expacct,$yearperiod->year,$yearperiod->period)){
                        DB::table('finance.glmasdtl')
                            ->where('compcode','=',session('compcode'))
                            ->where('costcode','=',$debitbank->costcode)
                            ->where('glaccount','=',$debitbank2->expacct)
                            ->where('year','=',$yearperiod->year)
                            ->update([
                                'upduser' => session('username'),
                                'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                                'actamount'.$yearperiod->period => $this->gltranAmount + $apactdtl->AmtB4GST,
                                'recstatus' => 'ACTIVE'
                            ]);
                    }else{
                        DB::table('finance.glmasdtl')
                            ->insert([
                                'compcode' => session('compcode'),
                                'costcode' => $debitbank->costcode,
                                'glaccount' => $debitbank2->expacct,
                                'year' => $yearperiod->year,
                                "actamount".$yearperiod->period => -$apactdtl->AmtB4GST,
                                'adduser' => session('username'),
                                'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                                'recstatus' => 'ACTIVE'
                            ]);
                    }
                
                    /////////////for gst/////////////////////////////////
                    $amountgst = floatval($apactdtl->amount) - floatval($apactdtl->AmtB4GST);

                    if($amountgst > 0.00){
                        DB::table('finance.gltran')
                            ->insert([
                                'compcode' => session('compcode'),
                                'auditno' => $apactdtl->auditno,
                                'lineno_' => $apactdtl->lineno_,
                                'source' => $apactdtl->source,
                                'trantype' => $apactdtl->trantype,
                                'reference' => $apacthdr_get->cheqno,
                                'description' => strtoupper($apacthdr_get->remarks),
                                'year' => $yearperiod->year,
                                'period' => $yearperiod->period,
                                'drcostcode' => $gst->pvalue1,
                                'crcostcode' => $creditbank->glccode,
                                'dracc' => $gst->pvalue2,
                                'cracc' => $creditbank->glaccno,
                                'amount' => $amountgst,
                                // 'idno' => $idno,
                                'postdate' => $apacthdr_get->actdate,
                                'adduser' => session('username'),
                                'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                            ]);
                        
                        if($this->isGltranExist($gst->pvalue1,$gst->pvalue2,$yearperiod->year,$yearperiod->period)){
                            DB::table('finance.glmasdtl')
                                ->where('compcode','=',session('compcode'))
                                ->where('costcode','=',$gst->pvalue1)
                                ->where('glaccount','=',$gst->pvalue2)
                                ->where('year','=',$yearperiod->year)
                                ->update([
                                    'upduser' => session('username'),
                                    'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                                    'actamount'.$yearperiod->period => $this->gltranAmount + $amountgst,
                                    'recstatus' => 'ACTIVE'
                                ]);
                        }else{
                            DB::table('finance.glmasdtl')
                                ->insert([
                                    'compcode' => session('compcode'),
                                    'costcode' => $gst->pvalue1,
                                    'glaccount' => $gst->pvalue2,
                                    'year' => $yearperiod->year,
                                    "actamount".$yearperiod->period => -$amountgst,
                                    'adduser' => session('username'),
                                    'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                                    'recstatus' => 'ACTIVE'
                                ]);
                        }

                        //3th step add glmasdtl untuk bankcode

                        //creditbank glmastdtl
                        if($this->isGltranExist($creditbank->glccode,$creditbank->glaccno,$yearperiod->year,$yearperiod->period)){
                            DB::table('finance.glmasdtl')
                                ->where('compcode','=',session('compcode'))
                                ->where('costcode','=',$creditbank->glccode)
                                ->where('glaccount','=',$creditbank->glaccno)
                                ->where('year','=',$yearperiod->year)
                                ->update([
                                    'upduser' => session('username'),
                                    'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                                    'actamount'.$yearperiod->period => $this->gltranAmount - $amountgst,
                                    'recstatus' => 'ACTIVE'
                                ]);
                        }else{
                            DB::table('finance.glmasdtl')
                                ->insert([
                                    'compcode' => session('compcode'),
                                    'costcode' => $creditbank->glccode,
                                    'glaccount' => $creditbank->glaccno,
                                    'year' => $yearperiod->year,
                                    "actamount".$yearperiod->period => -$amountgst,
                                    'adduser' => session('username'),
                                    'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                                    'recstatus' => 'ACTIVE'
                                ]);
                        }
                    }
                }
                
                //step update stat at cheqtran   //->dah buat waktu add, kalu buat waktu posted nanti
                // if($this->isPaytypeCheque($apacthdr_get->paymode == "Cheque")){
                //     DB::table('finance.chqtran')
                //         ->where('compcode','=',session('compcode'))
                //         ->where('bankcode','=',$apacthdr_get->bankcode)
                //         ->where('cheqno','=',$apacthdr_get->cheqno)
                //         ->update([
                //             'upduser' => session('username'),
                //             'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                //             'cheqdate' => $apacthdr_get->cheqdate,
                //             'amount' => $apacthdr_get->amount,
                //             'remarks' => strtoupper($apacthdr_get->remarks),
                //             'recstatus' => 'ISSUED',
                //             'auditno' => $apacthdr_get->auditno,
                //             'trantype' => $apacthdr_get->trantype,
                //             'source' => $apacthdr_get->source,
                            
                //         ]);
                // } 

                if($apacthdr_get->paymode == 'CHEQUE'){
                    $chqtran =  DB::table('finance.chqtran')
                                ->where('compcode','=',session('compcode'))
                                ->where('bankcode','=',$apacthdr_get->bankcode)
                                ->where('cheqno','=',$apacthdr_get->cheqno)
                                ->where('recstatus','OPEN');

                    if(!$chqtran->exists()){
                        throw new \Exception("Cheque Error, try again..");
                    }

                    DB::table('finance.chqtran')
                            ->where('compcode','=',session('compcode'))
                            ->where('bankcode','=',$apacthdr_get->bankcode)
                            ->where('cheqno','=',$apacthdr_get->cheqno)
                            ->where('recstatus','OPEN')
                            ->update([
                                'upduser' => session('username'),
                                'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                                'cheqdate' => Carbon::now('Asia/Kuala_Lumpur'),
                                'amount' => $apacthdr_get->amount,
                                'remarks' => strtoupper($apacthdr_get->remarks),
                                'recstatus' => 'ISSUED',
                                'auditno' => $apacthdr_get->auditno,
                                'trantype' => $apacthdr_get->trantype,
                                'source' => $apacthdr_get->source,
                                'payto' => $apacthdr_get->payto,
                            ]);
                }

                //5th step change status to posted
                $apacthdr->update([
                    'pvno' => $pvno,
                    'recstatus' => 'POSTED'
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }
    }

    public function isCBtranExist($bankcode,$year,$period){

        $cbtran = DB::table('finance.bankdtl')
                ->where('compcode','=',session('compcode'))
                ->where('year','=',$year)
                ->where('bankcode','=',$bankcode);

        if($cbtran->exists()){
            $cbtran_get = (array)$cbtran->first();
            $this->cbtranAmount = $cbtran_get["actamount".$period];
        }

        return $cbtran->exists();
    }

    public function getGLcode($bankcode){
        $bank = DB::table('finance.bank')
                    ->where('compcode','=',session('compcode'))
                    ->where('bankcode','=',$bankcode)
                    ->first();

        $responce = new stdClass();
        $responce->glccode = $bank->glccode;
        $responce->glaccno = $bank->glaccno;
        return $responce;

    }   

    public function getDept($deptcode){
        $dept = DB::table('sysdb.department')
                ->where('compcode','=',session('compcode'))
                ->where('deptcode','=',$deptcode)
                ->first();

        $responce = new stdClass();
        $responce->costcode = $dept->costcode;
        return $responce;

    }

    public function getCat($catcode){
        $dept = DB::table('material.category')
                ->where('compcode','=',session('compcode'))
                ->where('catcode','=',$catcode)
                ->where('source','=',"CR")
                ->first();

        $responce = new stdClass();
        $responce->expacct = $dept->expacct;
        return $responce;

    }


    public function isPaytypeCheque($paymode){
        $paytype = DB::table('debtor.paymode')
                ->where('compcode', '=', session('compcode'))
                ->where('paymode', '=', $paymode)
                ->where('source', '=', "CM")
                ->where('paytype', '=', "Cheque")
                ->first();
        
        $responce = new stdClass();
        $responce->paytype = $paytype->paytype;
        return $responce;
    }

    public function getCbtranTotamt($bankcode, $year, $period){
        $cbtranamt = DB::table('finance.cbtran')
                    ->where('compcode', '=', session('compcode'))
                    ->where('bankcode', '=', $bankcode)
                    ->where('year', '=', $year)
                    ->where('period', '=', $period)
                  /*  ->first();*/
                    ->sum('amount');
                
        $responce = new stdClass();
        $responce->amount = $cbtranamt;
        
        return $responce;
    }

    public function getTax ($source, $trantype){
        $tax = DB::table('sysdb.sysparam')
                ->where('compcode', '=', session('compcode'))
                ->where('source', '=', $source)
                ->where('trantype', '=', $trantype)
                ->first();

        $responce = new stdClass();
        $responce->pvalue1 = $tax->pvalue1;
        $responce->pvalue2 = $tax->pvalue2;
        return $responce;         
    }

    public function edit(Request $request){

        $table = DB::table("finance.apacthdr");

        $array_update = [
            'bankcode' => strtoupper($request->bankcode),
            'payto' => strtoupper($request->payto),
            'actdate' => $request->actdate,
            'paymode' => strtoupper($request->paymode),
            'cheqno' => $request->cheqno,
            'remarks' => strtoupper($request->remarks),
            'TaxClaimable' => $request->TaxClaimable,
            'cheqdate' => $request->cheqdate,
            'unit' => session('unit'),
            'compcode' => session('compcode'),
            'upduser' => session('username'),
            'upddate' => Carbon::now("Asia/Kuala_Lumpur")
        ];

        try {
            //////////where//////////
            $table = $table->where('idno','=',$request->idno);
            $table->update($array_update);

            $responce = new stdClass();
            $responce->auditno = $request->auditno;
            $responce->idno = $request->idno;
            echo json_encode($responce);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }
    }

    public function del(Request $request){
        DB::beginTransaction();

        try{

            DB::table('finance.apacthdr')
                    ->where('idno','=',$request->idno)
                    ->update([
                        'recstatus' => 'DEACTIVE' ,
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                    ]);

            DB::commit();
        
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage().$e, 500);
        }
    }

    public function showpdf(Request $request){
        $auditno = $request->auditno;
        if(empty($auditno)){
            abort(404);
        }

        $apacthdr = DB::table('finance.apacthdr as h', 'material.supplier as m', 'finance.bank as b')
            ->select('h.compcode', 'h.auditno', 'h.trantype', 'h.source','h.doctype', 'h.pvno', 'h.suppcode', 'm.Name as suppname', 'm.Addr1 as addr1', 'm.Addr2 as addr2', 'm.Addr3 as addr3', 'm.TelNo as telno', 'h.actdate', 'h.document', 'h.deptcode', 'h.amount', 'h.outamount', 'h.recstatus', 'h.payto', 'h.category', 'h.remarks', 'h.paymode', 'h.bankcode', 'h.cheqno','b.bankname', 'b.bankaccount as bankaccno')
            ->leftJoin('material.supplier as m', 'h.payto', '=', 'm.suppcode')
            ->leftJoin('finance.bank as b', 'h.bankcode', '=', 'b.bankcode')
            ->where('h.auditno','=',$auditno)
            ->first();
            // dd($apacthdr);

        $apactdtl = DB::table('finance.apactdtl as d', 'finance.apacthdr as h', 'material.category as c')
            ->select('d.compcode','d.source','d.trantype','d.auditno','d.lineno_','d.deptcode','d.category','d.document', 'd.AmtB4GST', 'd.GSTCode', 'd.taxamt AS tot_gst', 'd.amount', 'd.dorecno', 'd.grnno', 'd.idno','d.adddate', 'h.auditno', 'h.remarks AS remarks', 'c.description as desc')
            // ->leftJoin('finance.apacthdr as h', 'd.auditno', '=', 'h.auditno')
            // ->leftJoin('material.category as c', 'd.category', '=', 'c.catcode')
            ->leftJoin('finance.apacthdr as h', function($join) use ($request){
                        $join = $join->on('d.auditno', '=', 'h.auditno')
                                    ->where('h.source', '=', 'CM')
                                    ->where('h.trantype', '=', 'DP')
                                    ->where('h.compcode','=',session('compcode'));
                    })
            ->leftJoin('material.category as c', function($join) use ($request){
                        $join = $join->on('d.category', '=', 'c.catcode')
                                ->where('c.compcode','=',session('compcode'));
                    })
            ->where('d.auditno','=',$auditno)
            ->where('d.compcode','=',session('compcode'))
            ->where('d.recstatus','!=','DELETE')
            ->where('d.source','=','CM')
            ->where('d.trantype', '=','DP')
            ->get();

        $company = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();

        if ($apacthdr->recstatus == "OPEN") {
            $title = "DRAFT";
        } elseif ($apacthdr->recstatus == "POSTED"){
            $title = "DIRECT PAYMENT";
        }
            
        $totamount_expld = explode(".", (float)$apacthdr->amount);
        
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

        return view('finance.CM.directPayment.directPayment_pdfmake',compact('apacthdr','apactdtl','totamt_eng','company', 'title'));

    }
}
