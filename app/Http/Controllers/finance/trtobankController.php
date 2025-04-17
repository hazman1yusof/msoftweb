<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Auth;
use Carbon\Carbon;
use DateTime;


class trtobankController extends defaultController
{   
    var $cbtranAmount;
    var $gltranAmount;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   

        $unit = DB::table('sysdb.sector')
                    ->where('compcode',session('compcode'))
                    ->get();

        return view('finance.CM.trtobank.trtobank',compact('unit'));
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
        switch($request->action){
            case 'maintable':
                return $this->maintable($request);
            default:
                return 'error happen..';
        }
    }

    public function maintable(Request $request){
        $scope = $request->scope;
        $table = DB::table('finance.apacthdr AS ap')
                    ->select(
                        'ap.idno','ap.compcode','ap.source','ap.trantype','ap.doctype','ap.auditno','ap.document','ap.suppcode','ap.payto','ap.suppgroup','ap.bankcode','ap.paymode','ap.cheqno','ap.cheqdate','ap.actdate','ap.recdate','ap.category','ap.amount','ap.outamount','ap.remarks','ap.postflag','ap.doctorflag','ap.stat','ap.entryuser','ap.entrytime','ap.upduser','ap.upddate','ap.conversion','ap.srcfrom','ap.srcto','ap.deptcode','ap.reconflg','ap.effectdatefr','ap.effectdateto','ap.frequency','ap.refsource','ap.reftrantype','ap.refauditno','ap.pvno','ap.entrydate','ap.recstatus','ap.adduser','ap.adddate','ap.reference','ap.TaxClaimable','ap.unit','ap.allocdate','ap.postuser','ap.postdate','ap.unallocated','ap.requestby','ap.requestdate','ap.request_remark','ap.supportby','ap.supportdate','ap.support_remark','ap.verifiedby','ap.verifieddate','ap.verified_remark','ap.approvedby','ap.approveddate','ap.approved_remark','ap.cancelby','ap.canceldate','ap.cancelled_remark','ap.bankaccno','ap.commamt','ap.totBankinAmt','su.name','ba.bankname','su2.name as payto_desc'
                    )
                    ->leftJoin('material.supplier as su', function($join) use ($request){
                        $join = $join->on('su.SuppCode', '=', 'ap.suppcode');
                        $join = $join->where('su.compcode', '=', session('compcode'));
                    })
                    ->leftJoin('finance.bank as ba', function($join) use ($request){
                        $join = $join->on('ba.bankcode', '=', 'ap.bankcode');
                        $join = $join->where('ba.compcode', '=', session('compcode'));
                    })
                    ->leftJoin('material.supplier as su2', function($join) use ($request){
                        $join = $join->on('su2.suppcode', '=', 'ap.payto');
                        $join = $join->where('su2.compcode', '=', session('compcode'));
                    })
                    ->where('ap.source','AP')
                    ->where('ap.trantype','PD')
                    ->where('ap.compcode','=', session('compcode'))
                    ->where('ap.outamount','>',0)
                    ->where('ap.recstatus','APPROVED');

        // if(!empty($request->filterVal) && in_array('PD',$request->filterVal)){
        //     $table = $table->where('ap.trantype','PD');
        // }else{
        //     $table = $table->whereIn('ap.trantype',['PD','PV']);
        // }

        if(!empty($request->filterCol)){
            $table = $table->where($request->filterCol[0],'=',$request->filterVal[0]);
        }

        if(!empty($request->WhereInCol[0])){
            foreach ($request->WhereInCol as $key => $value) {
                // $sr = substr(strstr($value,'.'),1);
                $table = $table->whereIn($value,$request->WhereInVal[$key]);
            }
        }

        if(!empty($request->filterdate)){
            $table = $table->where('ap.actdate','>',$request->filterdate[0]);
            $table = $table->where('ap.actdate','<',$request->filterdate[1]);
        }

        if(!empty($request->searchCol)){
            if($request->searchCol[0] == 'name'){
                $table = $table->Where(function ($table) use ($request) {
                        $table->Where('su.name','like',$request->searchVal[0]);
                    });
            }else{
                $table = $table->Where(function ($table) use ($request) {
                        $table->Where('ap.'.$request->searchCol[0],'like',$request->searchVal[0]);
                    });
            }
        }

        if(!empty($request->sidx)){

            $pieces = explode(", ", $request->sidx .' '. $request->sord);

            if(count($pieces)==1){
                $table = $table->orderBy($request->sidx, $request->sord);
            }else{
                foreach ($pieces as $key => $value) {
                    $value_ = substr_replace($value,"ap.",0,strpos($value,"_")+1);
                    $pieces_inside = explode(" ", $value_);
                    $table = $table->orderBy($pieces_inside[0], $pieces_inside[1]);
                }
            }
        }else{
            $table = $table->orderBy('ap.idno','DESC');
        }


        $paginate = $table->paginate($request->rows);

        foreach ($paginate->items() as $key => $value) {
            // $apactdtl = DB::table('finance.apactdtl')
            //             ->where('source','=',$value->apacthdr_source)
            //             ->where('trantype','=',$value->apacthdr_trantype)
            //             ->where('auditno','=',$value->apacthdr_auditno);

            // if($apactdtl->exists()){
            //     $value->apactdtl_outamt = $apactdtl->sum('amount');
            // }else{
            //     $value->apactdtl_outamt = $value->apacthdr_outamount;
            // }

            // $apalloc = DB::table('finance.apalloc')
            //             ->select('allocdate')
            //             ->where('refsource','=',$value->apacthdr_source)
            //             ->where('reftrantype','=',$value->apacthdr_trantype)
            //             ->where('refauditno','=',$value->apacthdr_auditno)
            //             ->where('recstatus','!=','CANCELLED')
            //             ->orderBy('idno', 'desc');

            // if($apalloc->exists()){
            //     $value->apalloc_allocdate = $apalloc->first()->allocdate;
            // }else{
            //     $value->apalloc_allocdate = '';
            // }
        }

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

            // $auditno = $this->defaultSysparam('CM','DP');
            // $pvno = $this->defaultSysparam('HIS','PV');
            $compcode='DD';

            switch(strtoupper($request->paymode)){
                case 'CASH':
                    $source = 'CM';
                    $trantype = 'BS';
                    $payto = $request->payer1;
                    break;
                case 'CARD':
                    $source = 'CM';
                    $trantype = 'BD';
                    $payto = $request->payer2;
                    break;
                case 'CHEQUE':
                    $source = 'CM';
                    $trantype = 'BQ';
                    $payto = $request->payer1;
                    break;
            }

            $idno = DB::table('finance.apacthdr')
                    ->insertGetId([
                        'compcode' => $compcode,
                        'source' => $source,
                        'trantype' => $trantype,
                        // 'doctype' => 
                        // 'auditno' => 
                        // 'document' => 
                        // 'suppcode' => 
                        'payto' => $payto,
                        // 'suppgroup' => 
                        'bankcode' => $request->bankcode,
                        'paymode' => $request->paymode,
                        // 'cheqno' => 
                        // 'cheqdate' => 
                        'actdate' => $request->postdate,
                        // 'recdate' => 
                        // 'category' => 
                        'amount' => $request->amount,
                        'outamount' => $request->amount,
                        // 'remarks' => 
                        // 'postflag' => 
                        // 'doctorflag' => 
                        // 'stat' => 
                        'entryuser' => session('username'),
                        'entrytime' => Carbon::now("Asia/Kuala_Lumpur"),
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        // 'conversion' => 
                        // 'srcfrom' => 
                        // 'srcto' => 
                        // 'deptcode' => 
                        // 'reconflg' => 
                        // 'effectdatefr' => 
                        // 'effectdateto' => 
                        // 'frequency' => 
                        // 'refsource' => 
                        // 'reftrantype' => 
                        // 'refauditno' => 
                        // 'pvno' => 
                        'entrydate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'recstatus' => 'OPEN',
                        'adduser' => session('username'),
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'reference' => $request->reference,
                        // 'TaxClaimable' => 
                        'unit' => session('unit'),
                        // 'allocdate' => 
                        'postuser' => session('username'),
                        'postdate' => $request->postdate
                        // 'unallocated' => 
                        // 'requestby' => 
                        // 'requestdate' => 
                        // 'request_remark' => 
                        // 'supportby' => 
                        // 'supportdate' => 
                        // 'support_remark' => 
                        // 'verifiedby' => 
                        // 'verifieddate' => 
                        // 'verified_remark' => 
                        // 'approvedby' => 
                        // 'approveddate' => 
                        // 'approved_remark' => 
                        // 'cancelby' => 
                        // 'canceldate' => 
                        // 'cancelled_remark' => 
                        // 'bankaccno' => 
                    ]);

            $responce = new stdClass();
            $responce->auditno = '';
            $responce->pvno = '';
            $responce->idno = $idno;
            $responce->amount = $request->amount;

            echo json_encode($responce);

            DB::commit();
        
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage().$e, 500);
        }
    }

    public function edit(Request $request){
        $apacthdr = DB::table('finance.apacthdr')
                        // ->where('compcode',session('compcode'))
                        ->where('idno',$request->idno)
                        ->first();

        switch(strtoupper($apacthdr->paymode)){
            case 'CASH':
                $source = 'CM';
                $trantype = 'BS';
                $payto = $request->payer1;
                break;
            case 'CARD':
                $source = 'CM';
                $trantype = 'BD';
                $payto = $request->payer2;
                break;
            case 'CHEQUE':
                $source = 'CM';
                $trantype = 'BQ';
                $payto = $request->payer1;
                break;
        }

        $table = DB::table("finance.apacthdr");

        $array_update = [
            // 'source' => $source,
            // 'trantype' => $trantype,
            // 'payto' => $payto,
            // 'bankcode' => $bankcode,
            // 'paymode' => $request->paymode,
            'reference' => $request->reference,
            'amount' => $request->amount,
            'outamount' => $request->amount,
            'commamt' => $request->commamt,
            'upduser' => session('username'),
            'upddate' => Carbon::now("Asia/Kuala_Lumpur")
            // 'postdate' => $request->postdate
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

            return response($e, 500);
        }
    }

    public function posted(Request $request){

        DB::beginTransaction();

        try {

            $auditno = $this->recno('CM','DA');

            $PD_aphdr = DB::table('finance.apacthdr')
                            ->where('compcode',session('compcode'))
                            ->where('idno',$request->idno)
                            ->first();

            if(Carbon::parse($request->postdate)->lt(Carbon::parse($PD_aphdr->postdate))){
                throw new \Exception('Post Date cant be lower than PD postdate', 500);
            }

            $supplier = DB::table('material.supplier')
                            ->where('compcode',session('compcode'))
                            ->where('SuppCode',$PD_aphdr->suppcode)
                            ->first();

            $sysparam = DB::table('sysdb.sysparam')
                            ->where('compcode',session('compcode'))
                            ->where('source','AP')
                            ->where('trantype','ADV')
                            ->first();

            $category = DB::table('material.category')
                            ->where('compcode',session('compcode'))
                            ->where('source','CR')
                            ->where('catcode',$sysparam->pvalue1)
                            ->first();

            $yearperiod = $this->getyearperiod($request->postdate);

            $idno = DB::table('finance.apacthdr')
                    ->insertGetId([
                        'compcode' => session('compcode'),
                        'source' => 'CM',
                        'trantype' => 'DA',
                        'doctype' => $PD_aphdr->doctype,
                        'auditno' => $auditno,
                        'document' => $PD_aphdr->document,
                        'suppcode' => $PD_aphdr->suppcode,
                        'payto' => $PD_aphdr->payto,
                        'suppgroup' => $PD_aphdr->suppgroup,
                        'bankcode' => $PD_aphdr->bankcode,
                        'paymode' => $PD_aphdr->paymode,
                        'cheqno' => $PD_aphdr->cheqno,
                        'cheqdate' => $PD_aphdr->cheqdate,
                        'actdate' => $request->postdate,
                        'recdate' => $PD_aphdr->recdate,
                        'category' => $category->catcode,
                        'amount' => $PD_aphdr->outamount,
                        'outamount' => 0,
                        'remarks' => $request->reason,
                        'postflag' => $PD_aphdr->postflag,
                        'doctorflag' => $PD_aphdr->doctorflag,
                        'stat' => $PD_aphdr->stat,
                        'entryuser' => $PD_aphdr->entryuser,
                        'entrytime' => $PD_aphdr->entrytime,
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'conversion' => $PD_aphdr->conversion,
                        'srcfrom' => $PD_aphdr->srcfrom,
                        'srcto' => $PD_aphdr->srcto,
                        'deptcode' => $PD_aphdr->deptcode,
                        'reconflg' => $PD_aphdr->reconflg,
                        'effectdatefr' => $PD_aphdr->effectdatefr,
                        'effectdateto' => $PD_aphdr->effectdateto,
                        'frequency' => $PD_aphdr->frequency,
                        'refsource' => $PD_aphdr->refsource,
                        'reftrantype' => $PD_aphdr->reftrantype,
                        'refauditno' => $PD_aphdr->refauditno,
                        'pvno' => $PD_aphdr->pvno,
                        'entrydate' => $PD_aphdr->entrydate,
                        'recstatus' => $PD_aphdr->recstatus,
                        'adduser' => session('username'),
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'reference' => $PD_aphdr->reference,
                        'TaxClaimable' => $PD_aphdr->TaxClaimable,
                        'unit' => $PD_aphdr->unit,
                        'allocdate' => $PD_aphdr->allocdate,
                        'postuser' => $PD_aphdr->postuser,
                        'postdate' => $request->postdate,
                        'unallocated' => $PD_aphdr->unallocated,
                        'requestby' => $PD_aphdr->requestby,
                        'requestdate' => $PD_aphdr->requestdate,
                        'request_remark' => $PD_aphdr->remarks,
                        'supportby' => $PD_aphdr->supportby,
                        'supportdate' => $PD_aphdr->supportdate,
                        'support_remark' => $PD_aphdr->support_remark,
                        'verifiedby' => $PD_aphdr->verifiedby,
                        'verifieddate' => $PD_aphdr->verifieddate,
                        'verified_remark' => $PD_aphdr->verified_remark,
                        'approvedby' => $PD_aphdr->approvedby,
                        'approveddate' => $PD_aphdr->approveddate,
                        'approved_remark' => $PD_aphdr->approved_remark,
                        'cancelby' => $PD_aphdr->cancelby,
                        'canceldate' => $PD_aphdr->canceldate,
                        'cancelled_remark' => $PD_aphdr->cancelled_remark,
                        'bankaccno' => $PD_aphdr->bankaccno,
                        'commamt' => $PD_aphdr->commamt,
                        'totBankinAmt' => $PD_aphdr->totBankinAmt,
                    ]);

            DB::table('finance.apactdtl')
                    ->insert([
                        'compcode' => session('compcode'),
                        'source' => 'CM',
                        'trantype' => 'DA',
                        'auditno' => $auditno,
                        'lineno_' => 1,
                        'entrydate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'document' => $category->catcode,
                        'reference' => session('deptcode'),
                        'amount' => $PD_aphdr->outamount,
                        'stat' => 'A',
                        // 'mrn' => ,
                        // 'episno' => ,
                        // 'billno' => ,
                        'paymode' => $PD_aphdr->paymode,
                        'allocauditno' => $PD_aphdr->auditno,
                        'alloclineno' => 1,
                        'alloctnauditno' => $auditno,
                        'alloctnlineno' => 1,
                        'lastuser' => session('username'),
                        'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                        // 'grnno' => ,
                        // 'dorecno' => ,
                        'category' => $PD_aphdr->category,
                        'deptcode' => session('deptcode'),
                        'adduser' => session('username'),
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'recstatus' => 'ACTIVE',
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        // 'deluser' => ,
                        // 'deldate' => ,
                        // 'GSTCode' => ,
                        // 'AmtB4GST' => ,
                        'unit' => session('unit'),
                        // 'taxamt' => ,
                    ]);

            DB::table('finance.cbtran')
                    ->insert([  
                        'compcode' => session('compcode'), 
                        'bankcode' => $PD_aphdr->bankcode, 
                        'source' => 'CM', 
                        'trantype' => 'DA', 
                        'auditno' => $auditno, 
                        'postdate' => $request->postdate, 
                        'year' => $yearperiod->year, 
                        'period' => $yearperiod->period, 
                        'cheqno' => $PD_aphdr->cheqno, 
                        'amount' => $PD_aphdr->outamount, 
                        'remarks' => $request->reason, 
                        'upduser' => session('username'), 
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                        'reference' => session('deptcode'),
                        'recstatus' => 'ACTIVE' 
                    ]);

            if($this->isCBtranExist($PD_aphdr->bankcode,$yearperiod->year,$yearperiod->period)){

                DB::table('finance.bankdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('year','=',$yearperiod->year)
                    ->where('bankcode','=',$PD_aphdr->bankcode)
                    ->update([
                        "actamount".$yearperiod->period => $this->cbtranAmount + $PD_aphdr->outamount
                    ]);
            }else{

                DB::table('finance.bankdtl')
                        ->insert([
                            'compcode' => session('compcode'),
                            'bankcode' => $PD_aphdr->bankcode,
                            'year' => $yearperiod->year,
                            'actamount'.$yearperiod->period => $PD_aphdr->outamount,
                            'upduser' => session('username'),
                            'upddate' => Carbon::now("Asia/Kuala_Lumpur"),

                        ]);
            }

            $bank = DB::table('finance.bank')
                            ->where('compcode',session('compcode'))
                            ->where('bankcode',$PD_aphdr->bankcode)
                            ->first();

            $department = DB::table('sysdb.department')
                            ->where('compcode',session('compcode'))
                            ->where('deptcode',session('deptcode'))
                            ->first();

            $drcostcode = $bank->glccode;
            $dracc = $bank->glaccno;
            $crcostcode = $department->costcode;
            $cracc = $category->expacct;

            DB::table('finance.gltran')
                    ->insert([
                        'compcode' => session('compcode'),
                        'adduser' => session('username'),
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'auditno' => $auditno,
                        'lineno_' => 1,
                        'source' => 'CM',
                        'trantype' => 'DA',
                        'reference' => session('deptcode'),
                        'description' => $request->reason, 
                        'postdate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'year' => $yearperiod->year,
                        'period' => $yearperiod->period,
                        'drcostcode' => $drcostcode,
                        'dracc' => $dracc,
                        'crcostcode' => $crcostcode,
                        'cracc' => $cracc,
                        'amount' => $PD_aphdr->outamount
                    ]);

            $gltranAmount =  defaultController::isGltranExist_($drcostcode,$dracc,$yearperiod->year,$yearperiod->period);

            if($gltranAmount!==false){
                DB::table('finance.glmasdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('costcode','=',$drcostcode)
                    ->where('glaccount','=',$dracc)
                    ->where('year','=',$yearperiod->year)
                    ->update([
                        'upduser' => session('username'),
                        'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                        'actamount'.$yearperiod->period => $PD_aphdr->outamount + $gltranAmount,
                        'recstatus' => 'ACTIVE'
                    ]);
            }else{
                DB::table('finance.glmasdtl')
                    ->insert([
                        'compcode' => session('compcode'),
                        'costcode' => $drcostcode,
                        'glaccount' => $dracc,
                        'year' => $yearperiod->year,
                        'actamount'.$yearperiod->period => $PD_aphdr->outamount,
                        'adduser' => session('username'),
                        'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                        'recstatus' => 'ACTIVE'
                    ]);
            }

            //3. check glmastdtl utk credit pulak, kalu ada update kalu xde create
            $gltranAmount = defaultController::isGltranExist_($crcostcode,$cracc,$yearperiod->year,$yearperiod->period);

            if($gltranAmount!==false){
                DB::table('finance.glmasdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('costcode','=',$crcostcode)
                    ->where('glaccount','=',$cracc)
                    ->where('year','=',$yearperiod->year)
                    ->update([
                        'upduser' => session('username'),
                        'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                        'actamount'.$yearperiod->period => $gltranAmount - $PD_aphdr->outamount,
                        'recstatus' => 'ACTIVE'
                    ]);
            }else{
                DB::table('finance.glmasdtl')
                    ->insert([
                        'compcode' => session('compcode'),
                        'costcode' => $crcostcode,
                        'glaccount' => $cracc,
                        'year' => $yearperiod->year,
                        'actamount'.$yearperiod->period => -$PD_aphdr->outamount,
                        'adduser' => session('username'),
                        'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                        'recstatus' => 'ACTIVE'
                    ]);
            }

            $ALauditno = $this->defaultSysparam('AP','AL');

            DB::table('finance.apalloc')
                        ->insert([
                            'compcode' => session('compcode'),
                            'unit' => session('unit'),
                            'source' => 'AP',
                            'trantype' => 'AL',
                            'auditno' => $ALauditno,
                            'lineno_' => 1,
                            'docsource' => 'AP',
                            'doctrantype' => 'PD',
                            'docauditno' => $PD_aphdr->auditno,
                            'refsource' => 'CM',
                            'reftrantype' => 'DA',
                            'refauditno' => $auditno,
                            'refamount' => $PD_aphdr->outamount,
                            'allocdate' => $request->postdate,//blank
                            'reference' => session('deptcode'),
                            'remarks' => $request->reason,
                            'allocamount' => $PD_aphdr->outamount,
                            'outamount' => 0,
                            // 'balance' => $balance,
                            'paymode' => $PD_aphdr->paymode,
                            'cheqdate' => $PD_aphdr->cheqdate,
                            // 'recdate' => $request->apacthdr_recdate,
                            'bankcode' => $PD_aphdr->bankcode,
                            'suppcode' => $PD_aphdr->suppcode,
                            'lastuser' => session('username'),
                            'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'recstatus' => 'POSTED'
                        ]);

            DB::table('finance.apacthdr')
                            ->where('compcode',session('compcode'))
                            ->where('idno',$request->idno)
                            ->update([
                                'outamount' => 0
                            ]);


            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function check_amount_comm($apacthdr_get){
        if(strtoupper($apacthdr_get->paymode) == 'CARD'){
            if((floatval($apacthdr_get->amount) + floatval($apacthdr_get->commamt)) != floatval($apacthdr_get->totBankinAmt)){
                return true;
            }
        }
        return false;
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
