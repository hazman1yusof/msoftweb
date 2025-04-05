<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Auth;
use Carbon\Carbon;
use DateTime;


class bankReconController extends defaultController
{   

    public function __construct(){
        $this->middleware('auth');
    }

    public function show(Request $request){   

        $bank = DB::table('finance.bank')
                    ->where('compcode',session('compcode'))
                    ->get();

        return view('finance.CM.bankRecon.bankRecon',compact('bank'));
    }

    public function form(Request $request){   
        switch($request->oper){
            case 'add':
                return $this->add($request);
            case 'edit':
                return $this->edit($request);
            case 'del':
                return $this->del($request);
            case 'posted':
                return $this->posted($request);
            case 'cbrecdtl_add':
                return $this->cbrecdtl_add($request);
            case 'cbrecdtl_del':
                return $this->cbrecdtl_del($request);
            default:
                return 'error happen..';
        }
    }

    public function table(Request $request){   
        switch($request->action){
            case 'maintable':
                return $this->maintable($request);
            case 'cbrecdtl_tbl':
                return $this->cbrecdtl_tbl($request);
            case 'cbtran_tbl':
                return $this->cbtran_tbl($request);
            default:
                return 'error happen..';
        }
    }

    public function maintable(Request $request){

        if(empty($request->bankcode) && empty($request->recdate)){
            $responce = new stdClass();
            $responce->page = 0;
            $responce->total = 0;
            $responce->records = 0;
            $responce->rows = [];
            $responce->cbrecdtl_sumamt = 0;
            return json_encode($responce);
        }

        $recdate_ = Carbon::createFromFormat('Y-m',$request->recdate)->endOfMonth()->format('Y-m-d');

        $cbhdr = DB::table('finance.cbrechdr')
                        ->where('compcode',session('compcode'))
                        ->where('recdate',$recdate_)
                        ->where('bankcode',$request->bankcode);

        if(!$cbhdr->exists()){
            $responce = new stdClass();
            $responce->page = 0;
            $responce->total = 0;
            $responce->records = 0;
            $responce->rows = [];
            $responce->cbrecdtl_sumamt = 0;
            return json_encode($responce);
        }

        $cbhdr = $cbhdr->first();

        if($request->oper == 'init'){
            $cbrecdtl_sumamt = $this->init_recon($request,$cbhdr);
        }

        $table = DB::table('finance.cbrecdtl AS cbdt')
                    ->select('cbdt.idno','cbdt.compcode','cbdt.auditno','cbdt.docdate','cbdt.year','cbdt.period','cbdt.amount','cbdt.remarks','cbdt.lastuser','cbdt.lastupdate','cbdt.bitype','cbdt.reference','cbdt.stat','cbdt.refsrc','cbdt.reftrantype','cbdt.refauditno','cbdt.recstatus','cbdt.bankcode','cbdt.cheqno',)
                    ->where('cbdt.compcode','=', session('compcode'))
                    ->where('cbdt.auditno','=', $cbhdr->auditno);

        if(!empty($request->filterCol)){
            $table = $table->where($request->filterCol[0],'=',$request->filterVal[0]);
        }

        if(!empty($request->filterdate)){
            $table = $table->where('cbdt.actdate','>=',$request->filterdate[0]);
            $table = $table->where('cbdt.actdate','<=',$request->filterdate[1]);
        }

        if(!empty($request->searchCol)){
            if($request->searchCol[0] == 'bankcode'){
                $table = $table->Where(function ($table) use ($request) {
                        $table->Where('cbdt.bankcode','like',$request->searchVal[0]);
                    });
            } else if($request->searchCol[0] == 'payto'){
                $table = $table->Where(function ($table) use ($request) {
                        $table->Where('cbdt.payto','like',$request->searchVal[0]);
                    });
            }else if($request->searchCol[0] == 'auditno'){
                $table = $table->Where(function ($table) use ($request) {
                        $table->Where('cbdt.auditno','like',$request->searchVal[0]);
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
                    $value_ = 'cbdt.'.$value;
                    $pieces_inside = explode(" ", $value_);
                    $table = $table->orderBy($pieces_inside[0], $pieces_inside[1]);
                }
            }
        }else{
            $table = $table->orderBy('cbdt.idno','DESC');
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
        $responce->cbrecdtl_sumamt = $cbrecdtl_sumamt;

        return json_encode($responce);
    }

    public function init_recon(Request $request,$cbhdr){
        $cbrecdtl_sumamt = DB::table('finance.cbrecdtl')
                            ->where('compcode',session('compcode'))
                            ->where('auditno',$cbhdr->auditno)
                            ->sum('amount');

        $bankstmt = DB::table('finance.bankstmt')
                                ->where('compcode',session('compcode'))
                                ->where('mmyy',$cbhdr->recdate)
                                ->where('bankcode',$cbhdr->bankcode);

        if($bankstmt->exists()){
            DB::table('finance.bankstmt')
                    ->where('compcode',session('compcode'))
                    ->where('mmyy',$cbhdr->recdate)
                    ->where('bankcode',$cbhdr->bankcode)
                    ->update([
                        'currentbal' => $cbrecdtl_sumamt
                    ]);

            return $cbrecdtl_sumamt;
        }else{
            DB::table('finance.bankstmt')
                ->insert([
                    'compcode' => session('compcode'),
                    'bankcode' => $cbhdr->bankcode,
                    'mmyy' => $cbhdr->recdate,
                    'currentbal' => $cbrecdtl_sumamt,
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'upduser' => session('username'),
                    'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    // 'unreconamt' => ,
                ]);

            return $cbrecdtl_sumamt;
        }
    }

    public function cbrecdtl_tbl(Request $request){
        if(empty($request->bankcode) && empty($request->recdate)){
            $responce = new stdClass();
            $responce->page = 0;
            $responce->total = 0;
            $responce->records = 0;
            $responce->rows = [];
            return json_encode($responce);
        }

        $recdate_ = Carbon::createFromFormat('Y-m',$request->recdate)->endOfMonth()->format('Y-m-d');

        $cbhdr = DB::table('finance.cbrechdr')
                        ->where('compcode',session('compcode'))
                        ->where('recdate',$recdate_)
                        ->where('bankcode',$request->bankcode);

        if(!$cbhdr->exists()){
            $responce = new stdClass();
            $responce->page = 0;
            $responce->total = 0;
            $responce->records = 0;
            $responce->rows = [];
            return json_encode($responce);
        }

        $cbhdr = $cbhdr->first();

        $table = DB::table('finance.cbrecdtl AS cbdt')
                    ->select('cbdt.idno','cbdt.compcode','cbdt.auditno','cbdt.docdate','cbdt.year','cbdt.period','cbdt.amount','cbdt.remarks','cbdt.lastuser','cbdt.lastupdate','cbdt.bitype','cbdt.reference','cbdt.stat','cbdt.refsrc','cbdt.reftrantype','cbdt.refauditno','cbdt.recstatus','cbdt.bankcode','cbdt.cheqno',)
                    ->where('cbdt.compcode','=', session('compcode'))
                    ->where('cbdt.auditno','=', $cbhdr->auditno);

        if(!empty($request->filterCol)){
            $table = $table->where($request->filterCol[0],'=',$request->filterVal[0]);
        }

        if(!empty($request->filterdate)){
            $table = $table->where('cbdt.actdate','>=',$request->filterdate[0]);
            $table = $table->where('cbdt.actdate','<=',$request->filterdate[1]);
        }

        if(!empty($request->searchCol)){
            if($request->searchCol[0] == 'bankcode'){
                $table = $table->Where(function ($table) use ($request) {
                        $table->Where('cbdt.bankcode','like',$request->searchVal[0]);
                    });
            } else if($request->searchCol[0] == 'payto'){
                $table = $table->Where(function ($table) use ($request) {
                        $table->Where('cbdt.payto','like',$request->searchVal[0]);
                    });
            }else if($request->searchCol[0] == 'auditno'){
                $table = $table->Where(function ($table) use ($request) {
                        $table->Where('cbdt.auditno','like',$request->searchVal[0]);
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
                    $value_ = 'cbdt.'.$value;
                    $pieces_inside = explode(" ", $value_);
                    $table = $table->orderBy($pieces_inside[0], $pieces_inside[1]);
                }
            }
        }else{
            $table = $table->orderBy('cbdt.idno','DESC');
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

    public function cbtran_tbl(Request $request){
        if(empty($request->bankcode) && empty($request->recdate)){
            $responce = new stdClass();
            $responce->page = 0;
            $responce->total = 0;
            $responce->records = 0;
            $responce->rows = [];
            return json_encode($responce);
        }

        $recdate_ = Carbon::createFromFormat('Y-m',$request->recdate)->endOfMonth()->format('Y-m-d');

        $table = DB::table('finance.cbtran AS cb')
                    ->select('cb.idno','cb.compcode','cb.bankcode','cb.source','cb.trantype','cb.auditno','cb.postdate','cb.year','cb.period','cb.cheqno','cb.amount','cb.remarks','cb.upduser','cb.upddate','cb.bitype','cb.reference','cb.recstatus','cb.refsrc','cb.reftrantype','cb.refauditno','cb.reconstatus')
                    ->where('cb.compcode','=', session('compcode'))
                    ->where('cb.reconstatus','!=', 1)
                    ->where('cb.bankcode','=', $request->bankcode);

        if(!empty($request->filterCol)){
            $table = $table->where($request->filterCol[0],'=',$request->filterVal[0]);
        }

        if(!empty($request->filterdate)){
            $table = $table->where('cb.actdate','>=',$request->filterdate[0]);
            $table = $table->where('cb.actdate','<=',$request->filterdate[1]);
        }

        if(!empty($request->searchCol)){
            if($request->searchCol[0] == 'bankcode'){
                $table = $table->Where(function ($table) use ($request) {
                        $table->Where('cb.bankcode','like',$request->searchVal[0]);
                    });
            } else if($request->searchCol[0] == 'payto'){
                $table = $table->Where(function ($table) use ($request) {
                        $table->Where('cb.payto','like',$request->searchVal[0]);
                    });
            }else if($request->searchCol[0] == 'auditno'){
                $table = $table->Where(function ($table) use ($request) {
                        $table->Where('cb.auditno','like',$request->searchVal[0]);
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
                    $value_ = 'cb.'.$value;
                    $pieces_inside = explode(" ", $value_);
                    $table = $table->orderBy($pieces_inside[0], $pieces_inside[1]);
                }
            }
        }else{
            $table = $table->orderBy('cb.idno','DESC');
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

    public function cbrecdtl_add(Request $request){
        DB::beginTransaction();

        try{

            $recdate_ = Carbon::createFromFormat('Y-m',$request->recdate)->endOfMonth()->format('Y-m-d');

            $cbrechdr = DB::table('finance.cbrechdr')
                            ->where('compcode',session('compcode'))
                            ->where('bankcode',$request->bankcode)
                            ->where('recdate',$recdate_);

            if($cbrechdr->exists()){
                DB::table('finance.cbrechdr')
                            ->where('compcode',session('compcode'))
                            ->where('bankcode',$request->bankcode)
                            ->where('recdate',$recdate_)
                            ->update([
                                'openamt' => $request->clsBnkStatmnt,
                                'upduser' => session('username'),
                                'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                            ]);

                $cbrechdr = $cbrechdr->first();

                $auditno = $cbrechdr->auditno;
            }else{

                $auditno = $this->recno('CM','RECON');

                DB::table('finance.cbrechdr')
                            ->insert([
                                'compcode' => session('compcode'),
                                'auditno' => $auditno,
                                'bankcode' => $request->bankcode,
                                'recdate' => $recdate_,
                                'openamt' => $request->clsBnkStatmnt,
                                'adduser' => session('username'),
                                'adddate' => Carbon::now("Asia/Kuala_Lumpur")
                            ]);
            }

            foreach ($request->idno_array as $key_db => $value_db) {
                $cbtran = DB::table('finance.cbtran')
                                ->where('compcode',session('compcode'))
                                ->where('idno',$value_db)
                                ->first();

                $cbrecdtl = DB::table('finance.cbrecdtl')
                        ->where('compcode',session('compcode'))
                        ->where('refsrc',$cbtran->source)
                        ->where('reftrantype',$cbtran->trantype)
                        ->where('refauditno',$cbtran->auditno);

                if(!$cbrecdtl->exists()){
                    DB::table('finance.cbrecdtl')
                        ->insert([
                            'compcode' => session('compcode'),
                            'auditno' => $auditno,
                            'docdate' => $cbtran->postdate,
                            'year' => $cbtran->year,
                            'period' => $cbtran->period,
                            'amount' => $cbtran->amount,
                            'remarks' => $cbtran->remarks,
                            'lastuser' => session('username'),
                            'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'bitype' => $cbtran->bitype,
                            'reference' => $cbtran->reference,
                            'stat' => 1,
                            'refsrc' => $cbtran->source,
                            'reftrantype' => $cbtran->trantype,
                            'refauditno' => $cbtran->auditno,
                            'recstatus' => $cbtran->recstatus,
                            'bankcode' => $cbtran->bankcode,
                            'cheqno' => $cbtran->cheqno,
                        ]);


                        DB::table('finance.cbtran')
                                ->where('compcode',session('compcode'))
                                ->where('idno',$value_db)
                                ->update([
                                    'reconstatus' => 1
                                ]);
                }
            }


            $responce = new stdClass();
            $responce->auditno = $auditno;

            echo json_encode($responce);

            DB::commit();
        
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function cbrecdtl_del(Request $request){
        DB::beginTransaction();

        try{

            $recdate_ = Carbon::createFromFormat('Y-m',$request->recdate)->endOfMonth()->format('Y-m-d');

            $cbrechdr = DB::table('finance.cbrechdr')
                            ->where('compcode',session('compcode'))
                            ->where('bankcode',$request->bankcode)
                            ->where('recdate',$recdate_);

            if($cbrechdr->exists()){
                DB::table('finance.cbrechdr')
                            ->where('compcode',session('compcode'))
                            ->where('bankcode',$request->bankcode)
                            ->where('recdate',$recdate_)
                            ->update([
                                'openamt' => $request->clsBnkStatmnt,
                                'upduser' => session('username'),
                                'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                            ]);

                $cbrechdr = $cbrechdr->first();

                $auditno = $cbrechdr->auditno;
            }else{
                throw new \Exception('Error, No cbrechdr', 500);
            }

            foreach ($request->idno_array as $key_db => $value_db) {
                $cbrecdtl = DB::table('finance.cbrecdtl')
                                ->where('compcode',session('compcode'))
                                ->where('idno',$value_db);

                if(!$cbrecdtl->exists()){
                    continue;
                }

                $cbrecdtl = $cbrecdtl->first();

                $cbtran = DB::table('finance.cbtran')
                        ->where('compcode',session('compcode'))
                        ->where('source',$cbrecdtl->refsrc)
                        ->where('trantype',$cbrecdtl->reftrantype)
                        ->where('auditno',$cbrecdtl->refauditno);

                if($cbtran->exists()){
                    DB::table('finance.cbtran')
                        ->where('compcode',session('compcode'))
                        ->where('source',$cbrecdtl->refsrc)
                        ->where('trantype',$cbrecdtl->reftrantype)
                        ->where('auditno',$cbrecdtl->refauditno)
                        ->update([
                            'reconstatus' => 0
                        ]);

                    DB::table('finance.cbrecdtl')
                                ->where('compcode',session('compcode'))
                                ->where('idno',$value_db)
                                ->delete();
                }
            }


            $responce = new stdClass();
            $responce->auditno = $auditno;

            echo json_encode($responce);

            DB::commit();
        
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
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

            foreach ($request->idno_array as $idno){

                $apacthdr = DB::table('finance.apacthdr')
                            ->where('compcode',session('compcode'))
                            ->where('idno','=',$idno);

                $apacthdr_get = $apacthdr->first();

                if($this->check_amount_comm($apacthdr_get)){
                    throw new \Exception('Amunt + commision not equal with total detail', 500);
                }

                $yearperiod = $this->getyearperiod($apacthdr_get->postdate);

                //1st step add cbtran credit
                DB::table('finance.cbtran')
                    ->insert([  
                        'compcode' => session('compcode'), 
                        'bankcode' => $apacthdr_get->bankcode, 
                        'source' => $apacthdr_get->source, 
                        'trantype' => $apacthdr_get->trantype, 
                        'auditno' => $apacthdr_get->auditno, 
                        'postdate' => $apacthdr_get->postdate, 
                        'year' => $yearperiod->year, 
                        'period' => $yearperiod->period, 
                        // 'cheqno' => $apacthdr_get->cheqno, 
                        'amount' => $apacthdr_get->amount, 
                        'remarks' => strtoupper($apacthdr_get->payto), 
                        'upduser' => session('username'), 
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                        'reference' => $apacthdr_get->reference, 
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
                $bank_get = DB::table('finance.bank')
                                ->where('compcode', session('compcode'))
                                ->where('bankcode', $apacthdr_get->bankcode)
                                ->first();
                $drcostcode = $bank_get->glccode;
                $dracc = $bank_get->glaccno;

                if($apacthdr_get->trantype == 'BD'){
                    $paymode_get = DB::table('debtor.paymode')
                                    ->where('compcode', session('compcode'))
                                    ->where('cardcent', $apacthdr_get->payto)
                                    ->first();
                    $crcostcode = $paymode_get->ccode;
                    $cracc = $paymode_get->glaccno;

                }else if($apacthdr_get->trantype == 'BS'){
                    $paymode_get = DB::table('debtor.paymode')
                                    ->where('compcode', session('compcode'))
                                    ->where('paytype', 'CASH')
                                    ->first();
                    $crcostcode = $paymode_get->ccode;
                    $cracc = $paymode_get->glaccno;

                }else if($apacthdr_get->trantype == 'BQ'){
                    $paymode_get = DB::table('debtor.paymode')
                                    ->where('compcode', session('compcode'))
                                    ->where('paytype', 'CHEQUE')
                                    ->first();
                    $crcostcode = $paymode_get->ccode;
                    $cracc = $paymode_get->glaccno;
                }

                DB::table('finance.gltran')
                            ->insert([
                                'compcode' => session('compcode'),
                                'auditno' => $apacthdr_get->auditno,
                                'lineno_' => 1,
                                'source' => $apacthdr_get->source,
                                'trantype' => $apacthdr_get->trantype,
                                'reference' => $apacthdr_get->reference,
                                'description' => strtoupper($apacthdr_get->payto),
                                'year' => $yearperiod->year,
                                'period' => $yearperiod->period,
                                'drcostcode' => $drcostcode,
                                'crcostcode' => $crcostcode,
                                'dracc' => $dracc,
                                'cracc' => $cracc,
                                'amount' => $apacthdr_get->amount,
                                'postdate' => $apacthdr_get->postdate,
                                'adduser' => session('username'),
                                'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                            ]);

                if($this->isGltranExist($drcostcode,$dracc,$yearperiod->year,$yearperiod->period)){
                    DB::table('finance.glmasdtl')
                        ->where('compcode','=',session('compcode'))
                        ->where('costcode','=',$drcostcode)
                        ->where('glaccount','=',$dracc)
                        ->where('year','=',$yearperiod->year)
                        ->update([
                            'upduser' => session('username'),
                            'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                            'actamount'.$yearperiod->period => $this->gltranAmount + $apacthdr_get->amount,
                            'recstatus' => 'ACTIVE'
                        ]);
                }else{
                    DB::table('finance.glmasdtl')
                        ->insert([
                            'compcode' => session('compcode'),
                            'costcode' => $drcostcode,
                            'glaccount' => $dracc,
                            'year' => $yearperiod->year,
                            "actamount".$yearperiod->period => $apacthdr_get->amount,
                            'adduser' => session('username'),
                            'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                            'recstatus' => 'ACTIVE'
                        ]);
                }
            
                if($this->isGltranExist($crcostcode,$cracc,$yearperiod->year,$yearperiod->period)){
                    DB::table('finance.glmasdtl')
                        ->where('compcode','=',session('compcode'))
                        ->where('costcode','=',$crcostcode)
                        ->where('glaccount','=',$cracc)
                        ->where('year','=',$yearperiod->year)
                        ->update([
                            'upduser' => session('username'),
                            'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                            'actamount'.$yearperiod->period => $this->gltranAmount - $apacthdr_get->amount,
                            'recstatus' => 'ACTIVE'
                        ]);
                }else{
                    DB::table('finance.glmasdtl')
                        ->insert([
                            'compcode' => session('compcode'),
                            'costcode' => $crcostcode,
                            'glaccount' => $cracc,
                            'year' => $yearperiod->year,
                            "actamount".$yearperiod->period => -$apacthdr_get->amount,
                            'adduser' => session('username'),
                            'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                            'recstatus' => 'ACTIVE'
                        ]);
                }

                if($apacthdr_get->commamt != 0){

                    $sysparam_get = DB::table('sysdb.sysparam')
                                    ->where('compcode', session('compcode'))
                                    ->where('source', 'CM')
                                    ->where('trantype', 'CARDCOMM')
                                    ->first();
                    $drcostcode = $sysparam_get->pvalue1;
                    $dracc = $sysparam_get->pvalue2;

                    $paymode_get = DB::table('debtor.paymode')
                                    ->where('compcode', session('compcode'))
                                    ->where('cardcent', $apacthdr_get->payto)
                                    ->first();
                    $crcostcode = $paymode_get->ccode;
                    $cracc = $paymode_get->glaccno;

                    DB::table('finance.gltran')
                            ->insert([
                                'compcode' => session('compcode'),
                                'auditno' => $apacthdr_get->auditno,
                                'lineno_' => 2,
                                'source' => $apacthdr_get->source,
                                'trantype' => $apacthdr_get->trantype,
                                'reference' => $apacthdr_get->reference,
                                'description' => strtoupper($apacthdr_get->payto),
                                'year' => $yearperiod->year,
                                'period' => $yearperiod->period,
                                'drcostcode' => $drcostcode,
                                'crcostcode' => $crcostcode,
                                'dracc' => $dracc,
                                'cracc' => $cracc,
                                'amount' => $apacthdr_get->commamt,
                                'postdate' => $apacthdr_get->postdate,
                                'adduser' => session('username'),
                                'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                            ]);

                    if($this->isGltranExist($drcostcode,$dracc,$yearperiod->year,$yearperiod->period)){
                        DB::table('finance.glmasdtl')
                            ->where('compcode','=',session('compcode'))
                            ->where('costcode','=',$drcostcode)
                            ->where('glaccount','=',$dracc)
                            ->where('year','=',$yearperiod->year)
                            ->update([
                                'upduser' => session('username'),
                                'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                                'actamount'.$yearperiod->period => $this->gltranAmount + $apacthdr_get->commamt,
                                'recstatus' => 'ACTIVE'
                            ]);
                    }else{
                        DB::table('finance.glmasdtl')
                            ->insert([
                                'compcode' => session('compcode'),
                                'costcode' => $drcostcode,
                                'glaccount' => $dracc,
                                'year' => $yearperiod->year,
                                "actamount".$yearperiod->period => $apacthdr_get->commamt,
                                'adduser' => session('username'),
                                'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                                'recstatus' => 'ACTIVE'
                            ]);
                    }
                
                    if($this->isGltranExist($crcostcode,$cracc,$yearperiod->year,$yearperiod->period)){
                        DB::table('finance.glmasdtl')
                            ->where('compcode','=',session('compcode'))
                            ->where('costcode','=',$crcostcode)
                            ->where('glaccount','=',$cracc)
                            ->where('year','=',$yearperiod->year)
                            ->update([
                                'upduser' => session('username'),
                                'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                                'actamount'.$yearperiod->period => $this->gltranAmount - $apacthdr_get->commamt,
                                'recstatus' => 'ACTIVE'
                            ]);
                    }else{
                        DB::table('finance.glmasdtl')
                            ->insert([
                                'compcode' => session('compcode'),
                                'costcode' => $crcostcode,
                                'glaccount' => $cracc,
                                'year' => $yearperiod->year,
                                "actamount".$yearperiod->period => -$apacthdr_get->commamt,
                                'adduser' => session('username'),
                                'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                                'recstatus' => 'ACTIVE'
                            ]);
                    }
                }
                
                //5th step change status to posted
                DB::table('finance.apacthdr')
                            ->where('compcode',session('compcode'))
                            ->where('idno','=',$idno)
                            ->update([
                                'recstatus' => 'POSTED'
                            ]);

                DB::table('finance.cbdtl')
                            ->where('compcode',session('compcode'))
                            ->where('source',$apacthdr_get->source)
                            ->where('trantype',$apacthdr_get->trantype)
                            ->where('auditno',$apacthdr_get->auditno)
                            ->update([
                                'recstatus' => 'POSTED'
                            ]);

                $cbdtl_get = DB::table('finance.cbdtl')
                            ->where('compcode',session('compcode'))
                            ->where('source',$apacthdr_get->source)
                            ->where('trantype',$apacthdr_get->trantype)
                            ->where('auditno',$apacthdr_get->auditno)
                            ->get();

                foreach ($cbdtl_get as $key => $value) {
                    DB::table('debtor.dbacthdr')
                            ->where('compcode',session('compcode'))
                            ->where('source',$value->refsrc)
                            ->where('trantype',$value->reftrantype)
                            ->where('auditno',$value->refauditno)
                            ->update([
                                'cbflag' => 1
                            ]);
                }

            }

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
