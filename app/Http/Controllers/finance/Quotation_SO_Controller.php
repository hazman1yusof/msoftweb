<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use PDF;

class Quotation_SO_Controller extends defaultController
{
    var $gltranAmount;
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function show(Request $request)
    {
        $storedept = DB::table('sysdb.department')
                        ->select('deptcode')
                        ->where('compcode',session('compcode'))
                        ->where('recstatus','ACTIVE')
                        ->where('storedept',1)
                        ->where('chgdept',1)
                        ->get();

        return view('finance.Quotation_SO.Quotation_SO',compact('storedept'));
    }
    
    public function table(Request $request)
    {
        DB::enableQueryLog();
        switch($request->action){
            case 'maintable':
                return $this->maintable($request);
            case 'get_hdrtype':
                return $this->get_hdrtype($request);
            case 'get_hdrtype_check':
                return $this->get_hdrtype_check($request);
            case 'get_debtorMaster':
                return $this->get_debtorMaster($request);
            default:
                return 'error happen..';
        }
    }
    
    public function form(Request $request)
    {
        DB::enableQueryLog();
        switch($request->oper){ // Quotation_header_save 
            case 'add':
                return $this->add($request);
            case 'edit':
                return $this->edit($request);
            case 'del':
                return $this->del($request);
            case 'posted':
                return $this->posted($request);
            case 'reopen':
                return $this->reopen($request);
            case 'cancel':
                return $this->cancel($request);
            case 'support':
                return $this->support($request);
            case 'verify':
                return $this->verify($request);
            case 'approved':
                return $this->approved($request);
            case 'refresh_do':
                return $this->refresh_do($request);
            default:
                return 'Errors happen';
        }
    }
    
    public function maintable(Request $request){
        
        $table = DB::table('finance.salehdr AS SL')
                ->select(
                    'SL.idno AS SL_idno',
                    'SL.source AS SL_source',
                    'SL.trantype AS SL_trantype',
                    'SL.auditno AS SL_auditno',
                    'SL.quoteno AS SL_quoteno',
                    'SL.lineno_ AS SL_lineno_',
                    'SL.amount AS SL_amount',
                    'SL.outamount AS SL_outamount',
                    'SL.hdrsts AS SL_hdrsts',
                    'SL.posteddate AS SL_posteddate',
                    'SL.entrydate AS SL_entrydate',
                    'SL.entrytime AS SL_entrytime',
                    'SL.entryuser AS SL_entryuser',
                    'SL.reference AS SL_reference',
                    'SL.recptno AS SL_recptno',
                    'SL.paymode AS SL_paymode',
                    'SL.tillcode AS SL_tillcode',
                    'SL.tillno AS SL_tillno',
                    'SL.debtortype AS SL_debtortype',
                    'SL.debtorcode AS SL_debtorcode',
                    'SL.payercode AS SL_payercode',
                    'SL.billdebtor AS SL_billdebtor',
                    'SL.remark AS SL_remark',
                    'SL.mrn AS SL_mrn',
                    'SL.doctorcode AS SL_doctorcode',
                    'SL.episno AS SL_episno',
                    'SL.authno AS SL_authno',
                    'SL.expdate AS SL_expdate',
                    'SL.adddate AS SL_adddate',
                    'SL.adduser AS SL_adduser',
                    'SL.upddate AS SL_upddate',
                    'SL.upduser AS SL_upduser',
                    'SL.epistype AS SL_epistype',
                    'SL.cbflag AS SL_cbflag',
                    'SL.conversion AS SL_conversion',
                    'SL.payername AS SL_payername',
                    'SL.hdrtype AS SL_hdrtype',
                    'SL.currency AS SL_currency',
                    'SL.rate AS SL_rate',
                    'SL.startdate AS SL_startdate',
                    'SL.termvalue AS SL_termvalue',
                    'SL.termcode AS SL_termcode',
                    'SL.frequency AS SL_frequency',
                    'SL.pono AS SL_pono',
                    'SL.podate AS SL_podate',
                    'SL.saleid AS SL_saleid',
                    'SL.billtype AS SL_billtype',
                    'SL.docdate AS SL_docdate',
                    'SL.unit AS SL_unit',
                    'SL.recstatus AS SL_recstatus',
                    'SL.deptcode AS SL_deptcode',
                    'dm.name AS dm_name',
                )
                ->where('SL.compcode',session('compcode'))
                // ->where('SL.deptcode',session('deptcode'))
                ->where('SL.source','SL')
                ->where('SL.trantype','RECNO');
        
        $table = $table->join('debtor.debtormast as dm', function ($join) use ($request){
                $join = $join->where('dm.compcode', '=', session('compcode'));
                $join = $join->on('dm.debtorcode', '=', 'SL.debtorcode');
        });
        
        if(!empty($request->filterCol)){
            $table = $table->where($request->filterCol[0],'=',$request->filterVal[0]);
        }
        
        if(!empty($request->filterdate)){
            $table = $table->where('SL.posteddate','>=',$request->filterdate[0]);
            $table = $table->where('SL.posteddate','<=',$request->filterdate[1]);
        }
        
        if(!empty($request->searchCol)){
            if($request->searchCol[0] == 'SL_quoteno'){
                $table = $table->Where(function ($table) use ($request){
                        $table->Where('SL.quoteno','like',$request->searchVal[0]);
                });
            }else{
                $table = $table->Where(function ($table) use ($request){
                        $table->Where($request->searchCol[0],'like',$request->searchVal[0]);
                });
            }
        }
        
        if(!empty($request->sidx)){
            $pieces = explode(", ", $request->sidx .' '. $request->sord);
            
            if(count($pieces) == 1){
                $table = $table->orderBy($request->sidx, $request->sord);
            }else{
                foreach($pieces as $key => $value){
                    $value_ = substr_replace($value,"SL.",0,strpos($value,"_")+1);
                    $pieces_inside = explode(" ", $value_);
                    $table = $table->orderBy($pieces_inside[0], $pieces_inside[1]);
                }
            }
        }else{
            $table = $table->orderBy('SL.idno','DESC');
        }
        
        $paginate = $table->paginate($request->rows);
        
        // foreach($paginate->items() as $key => $value){
        //     $apactdtl = DB::table('finance.apactdtl')
        //                 ->where('source','=',$value->apacthdr_source)
        //                 ->where('trantype','=',$value->apacthdr_trantype)
        //                 ->where('auditno','=',$value->apacthdr_auditno);
            
        //     // if($apactdtl->exists()){
        //     //     $value->apactdtl_outamt = $apactdtl->sum('amount');
        //     // }else{
        //     //     $value->apactdtl_outamt = $value->apacthdr_outamount;
        //     // }
            
        //     // $apalloc = DB::table('finance.apalloc')
        //     //             ->select('allocdate')
        //     //             ->where('refsource','=',$value->apacthdr_source)
        //     //             ->where('reftrantype','=',$value->apacthdr_trantype)
        //     //             ->where('refauditno','=',$value->apacthdr_auditno)
        //     //             ->where('recstatus','!=','CANCELLED')
        //     //             ->orderBy('idno', 'desc');
            
        //     // if($apalloc->exists()){
        //     //     $value->apalloc_allocdate = $apalloc->first()->allocdate;
        //     // }else{
        //     //     $value->apalloc_allocdate = '';
        //     // }
        // }
        
        //////////paginate//////////
        
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
        
        $table = DB::table("finance.salehdr");
        
        try {
            
            $auditno = $this->recno('SL','RECNO');
            // $quoteno = $this->recno('SL','QN');
            $chk_billtype = $this->chk_billtype($request);
            
            if($chk_billtype->error){
                throw new \Exception($chk_billtype->msg,500);
            }
            
            if(!empty($request->SL_mrn)){
                $pat_mast = DB::table('hisdb.pat_mast')
                            ->where('compcode','=',session('compcode'))
                            ->where('newmrn','=',$request->SL_mrn)
                            ->first();
            }
            
            $debtormast = DB::table('debtor.debtormast')
                        // ->select('recstatus')
                        ->where('compcode', '=', session('compcode'))
                        ->where('debtorcode', '=', $request->SL_debtorcode)
                        ->first();
            
            if($debtormast->recstatus !== 'ACTIVE'){
                throw new \Exception("This debtor is not active.",500);
            }
            
            $array_insert = [
                'compcode' => session('compcode'),
                'source' => 'SL',
                'trantype' => 'RECNO',
                'auditno' => $auditno,
                // 'quoteno' => $quoteno,
                'lineno_' => 1,
                'entrydate' => $request->SL_entrydate,
                'entrytime' => Carbon::now("Asia/Kuala_Lumpur"),
                'debtorcode' => strtoupper($request->SL_debtorcode),
                'payercode' => strtoupper($request->SL_debtorcode),
                'remark' => strtoupper($request->SL_remark),
                // 'mrn' => '0',
                'mrn' => strtoupper($request->SL_mrn),
                'doctorcode' => strtoupper($request->SL_doctorcode),
                // 'episno' => (!empty($request->SL_mrn))?$pat_mast->Episno:null,
                'adduser' => session('username'),
                'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                'hdrtype' => strtoupper($request->SL_hdrtype),
                'termvalue' => $request->SL_termvalue,
                'termcode' => strtoupper($request->SL_termcode),
                // 'pono' => strtoupper($request->SL_pono),
                // 'podate' => (!empty($request->SL_podate))?$request->SL_podate:null,
                'unit' => session('unit'),
                'recstatus' => 'OPEN',
                'deptcode' => strtoupper($request->SL_deptcode),
            ];
            
            //////////where//////////
            // $table = $table->where('idno','=',$request->idno);
            $idno = $table->insertGetId($array_insert);
            
            $responce = new stdClass();
            $responce->totalAmount = 0.00;
            $responce->idno = $idno;
            $responce->auditno = $auditno;
            echo json_encode($responce);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
        
    }
    
    public function edit(Request $request){
        
        DB::beginTransaction();
        
        $table = DB::table("finance.salehdr");
        
        $array_update = [
            'entrydate' => $request->SL_entrydate,
            'entrytime' => Carbon::now("Asia/Kuala_Lumpur"),
            'debtorcode' => strtoupper($request->SL_debtorcode),
            'payercode' => strtoupper($request->SL_debtorcode),
            'remark' => strtoupper($request->SL_remark),
            'mrn' => '0',
            'hdrtype' => strtoupper($request->SL_hdrtype),
            'pono' => strtoupper($request->SL_pono),
            'podate' => (!empty($request->SL_podate))?$request->SL_podate:null,
            'unit' => session('unit'),
            'deptcode' => strtoupper($request->SL_deptcode),
        ];
        
        try {
            
            $chk_billtype = $this->chk_billtype($request);
            
            if($chk_billtype->error){
                throw new \Exception($chk_billtype->msg,500);
            }
            
            $debtormast = DB::table('debtor.debtormast')
                        // ->select('recstatus')
                        ->where('compcode', '=', session('compcode'))
                        ->where('debtorcode', '=', $request->SL_debtorcode)
                        ->first();
            
            if($debtormast->recstatus !== 'ACTIVE'){
                throw new \Exception("This debtor is not active.",500);
            }
            
            //////////where//////////
            $table = $table->where('idno','=',$request->SL_idno);
            $table->update($array_update);
            
            $responce = new stdClass();
            $responce->totalAmount = $request->purreqhd_totamount;
            echo json_encode($responce);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
        
    }
    
    public function del(Request $request){
        
    }
    
    public function posted(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            foreach($request->idno_array as $value){
                $salehdr = DB::table("finance.salehdr")
                        ->where('compcode',session('compcode'))
                        ->where('idno','=',$value)
                        ->first();
                
                if($salehdr->recstatus != 'OPEN'){
                    continue;
                }
                
                // $invno = $this->recno('PB','INV');
                $quoteno = $this->recno('SL','QN');
                
                DB::table("finance.salehdr")
                    ->where('compcode',session('compcode'))
                    ->where('idno','=',$value)
                    ->update([
                        'quoteno' => $quoteno,
                        'recstatus' => 'POSTED',
                        'posteddate' => Carbon::now("Asia/Kuala_Lumpur")
                    ]);
                
                $salesum = DB::table("finance.salesum")
                        ->where('compcode',session('compcode'))
                        ->where('source','=','SL')
                        ->where('trantype','=','RECNO')
                        ->where('auditno','=',$salehdr->auditno)
                        ->update([
                            'recstatus' => 'POSTED',
                        ]);
            }
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e, 500);
            
        }
        
    }
    
    public function init_glmastdtl($dbcc,$dbacc,$crcc,$cracc,$yearperiod,$amount){
        // 2. check glmastdtl utk debit, kalu ada update kalu xde create
        $gltranAmount = $this->isGltranExist($dbcc,$dbacc,$yearperiod->year,$yearperiod->period);
        
        if($gltranAmount!==false){
            DB::table('finance.glmasdtl')
                ->where('compcode','=',session('compcode'))
                ->where('costcode','=',$dbcc)
                ->where('glaccount','=',$dbacc)
                ->where('year','=',$yearperiod->year)
                ->update([
                    'upduser' => session('username'),
                    'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'actamount'.$yearperiod->period => floatval($amount) + $gltranAmount,
                    'recstatus' => 'ACTIVE'
                ]);
        }else{
            DB::table('finance.glmasdtl')
                ->insert([
                    'compcode' => session('compcode'),
                    'costcode' => $dbcc,
                    'glaccount' => $dbacc,
                    'year' => $yearperiod->year,
                    'actamount'.$yearperiod->period => floatval($amount),
                    'adduser' => session('username'),
                    'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'recstatus' => 'ACTIVE'
                ]);
        }
        
        // 3. check glmastdtl utk credit pulak, kalu ada update kalu xde create
        $gltranAmount = defaultController::isGltranExist_($crcc,$cracc,$yearperiod->year,$yearperiod->period);
        
        if($gltranAmount!==false){
            DB::table('finance.glmasdtl')
                ->where('compcode','=',session('compcode'))
                ->where('costcode','=',$crcc)
                ->where('glaccount','=',$cracc)
                ->where('year','=',$yearperiod->year)
                ->update([
                    'upduser' => session('username'),
                    'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'actamount'.$yearperiod->period => $gltranAmount - floatval($amount),
                    'recstatus' => 'ACTIVE'
                ]);
        }else{
            DB::table('finance.glmasdtl')
                ->insert([
                    'compcode' => session('compcode'),
                    'costcode' => $crcc,
                    'glaccount' => $cracc,
                    'year' => $yearperiod->year,
                    'actamount'.$yearperiod->period => -floatval($amount),
                    'adduser' => session('username'),
                    'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'recstatus' => 'ACTIVE'
                ]);
        }
    }
    
    // public function reopen(Request $request){
        
    //     DB::beginTransaction();
        
    //     try {
            
    //         foreach($request->idno_array as $value){
    //             $purreqhd = DB::table("material.purreqhd")
    //                         ->where('idno','=',$value);
                
    //             $purreqhd_get = $purreqhd->first();
    //             if(!in_array($purreqhd_get->recstatus, ['CANCELLED','REQUEST','SUPPORT','VERIFIED','APPROVED'])){
    //                 continue;
    //             }
                
    //             $purreqhd->update([
    //                 'recstatus' => 'OPEN',
    //                 'requestby' => null,
    //                 'requestdate' => null,
    //                 'supportby' => null,
    //                 'supportdate' => null,
    //                 'verifiedby' => null,
    //                 'verifieddate' => null,
    //                 'approvedby' => null,
    //                 'approveddate' => null,
    //             ]);
                
    //             DB::table("material.purreqdt")
    //                 ->where('recno','=',$purreqhd_get->recno)
    //                 ->update([
    //                     'recstatus' => 'OPEN',
    //                     'upduser' => session('username'),
    //                     'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
    //                 ]);
                
    //             DB::table("material.queuepr")
    //                 ->where('recno','=',$purreqhd_get->recno)
    //                 ->delete();
    //         }
            
    //         DB::commit();
            
    //     } catch (\Exception $e) {
            
    //         DB::rollback();
            
    //         return response($e->getMessage(), 500);
            
    //     }
        
    // }
    
    public function cancel(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            foreach($request->idno_array as $value){
                $salehdr = DB::table("finance.salehdr")
                        ->where('idno','=',$value)
                        ->first();
                
                $salesum = DB::table("finance.salesum")
                        ->where('source','=',$salehdr->source)
                        ->where('trantype','=',$salehdr->trantype)
                        ->where('auditno','=',$salehdr->auditno)
                        ->get();
                
                DB::table("finance.salesum")
                    ->where('source','=',$salehdr->source)
                    ->where('trantype','=',$salehdr->trantype)
                    ->where('auditno','=',$salehdr->auditno)
                    ->update([
                        'recstatus' => 'CANCELLED',
                    ]);
                
                DB::table("finance.salehdr")
                    ->where('idno','=',$value)
                    ->update([
                        'recstatus' => 'CANCELLED',
                    ]);
            }
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
        
    }
    
    public function support(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            foreach($request->idno_array as $value){
                $purreqhd = DB::table("material.purreqhd")
                            ->where('idno','=',$value);
                
                $purreqhd_get = $purreqhd->first();
                
                if(!$this->skip_authorization($request,$purreqhd_get->reqdept,$value)){
                    $authorise = DB::table('material.authdtl')
                                ->where('compcode','=',session('compcode'))
                                ->where('trantype','=','PR')
                                ->where('cando','=', 'ACTIVE')
                                ->where('recstatus','=','VERIFIED')
                                ->where('deptcode','=',$purreqhd_get->reqdept)
                                ->where('maxlimit','>=',$purreqhd_get->totamount);
                    
                    if(!$authorise->exists()){
                        $authorise = DB::table('material.authdtl')
                                    ->where('compcode','=',session('compcode'))
                                    ->where('trantype','=','PR')
                                    ->where('cando','=', 'ACTIVE')
                                    ->where('recstatus','=','VERIFIED')
                                    ->where('deptcode','=','ALL')
                                    ->where('deptcode','=','all')
                                    ->where('maxlimit','>=',$purreqhd_get->totamount);
                        
                        if(!$authorise->exists()){
                            throw new \Exception("Authorization for this purchase request doesnt exists",500);
                        }
                    }
                    
                    $authorise_use = $authorise->first();
                    
                    $purreqhd->update([
                        'verifiedby' => $authorise_use->authorid,
                        'supportdate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'recstatus' => 'SUPPORT'
                    ]);
                    
                    DB::table("material.purreqdt")
                        ->where('recno','=',$purreqhd_get->recno)
                        ->update([
                            'recstatus' => 'SUPPORT',
                            'upduser' => session('username'),
                            'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                        ]);
                    
                    DB::table("material.queuepr")
                        ->where('recno','=',$purreqhd_get->recno)
                        ->update([
                            'AuthorisedID' => $authorise_use->authorid,
                            'recstatus' => 'SUPPORT',
                            'trantype' => 'VERIFIED',
                            'adduser' => session('username'),
                            'adddate' => Carbon::now("Asia/Kuala_Lumpur")
                        ]);
                }
                
                // 4. email and whatsapp
                $data = new stdClass();
                $data->status = 'SUPPORT';
                $data->deptcode = $purreqhd_get->reqdept;
                $data->purreqno = $purreqhd_get->purreqno;
                $data->email_to = 'hazman.yusof@gmail.com';
                $data->whatsapp = '01123090948';
                
                // $this->sendemail($data);
            }
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
        
        }
        
    }
    
    public function verify(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            foreach($request->idno_array as $value){
                $purreqhd = DB::table("material.purreqhd")
                            ->where('idno','=',$value);
                
                $purreqhd_get = $purreqhd->first();
                
                if(!$this->skip_authorization($request,$purreqhd_get->reqdept,$value)){
                    $authorise = DB::table('material.authdtl')
                                ->where('compcode','=',session('compcode'))
                                ->where('trantype','=','PR')
                                ->where('cando','=', 'ACTIVE')
                                ->where('recstatus','=','APPROVED')
                                ->where('deptcode','=',$purreqhd_get->reqdept)
                                ->where('maxlimit','>=',$purreqhd_get->totamount);
                    
                    if(!$authorise->exists()){
                        $authorise = DB::table('material.authdtl')
                                    ->where('compcode','=',session('compcode'))
                                    ->where('trantype','=','PR')
                                    ->where('cando','=', 'ACTIVE')
                                    ->where('recstatus','=','APPROVED')
                                    ->where('deptcode','=','ALL')
                                    ->where('deptcode','=','all')
                                    ->where('maxlimit','>=',$purreqhd_get->totamount);
                            
                        if(!$authorise->exists()){
                            throw new \Exception("Authorization for this purchase request doesnt exists",500);
                        }
                    }
                    
                    $authorise_use = $authorise->first();
                    
                    $purreqhd->update([
                        'approvedby' => $authorise_use->authorid,
                        'verifieddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'recstatus' => 'VERIFIED'
                    ]);
                    
                    DB::table("material.purreqdt")
                        ->where('recno','=',$purreqhd_get->recno)
                        ->update([
                            'recstatus' => 'VERIFIED',
                            'upduser' => session('username'),
                            'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                        ]);
                    
                    DB::table("material.queuepr")
                        ->where('recno','=',$purreqhd_get->recno)
                        ->update([
                            'AuthorisedID' => $authorise_use->authorid,
                            'recstatus' => 'VERIFIED',
                            'trantype' => 'APPROVED',
                            'adduser' => session('username'),
                            'adddate' => Carbon::now("Asia/Kuala_Lumpur")
                        ]);
                }
                
                // 4. email and whatsapp
                $data = new stdClass();
                $data->status = 'APPROVED';
                $data->deptcode = $purreqhd_get->reqdept;
                $data->purreqno = $purreqhd_get->purreqno;
                $data->email_to = 'hazman.yusof@gmail.com';
                $data->whatsapp = '01123090948';
                
                // $this->sendemail($data);
            }
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
        
    }
    
    public function approved(Request $request){
        DB::beginTransaction();
        
        try {
            
            foreach($request->idno_array as $value){
                $purreqhd = DB::table("material.purreqhd")
                            ->where('idno','=',$value);
                
                $purreqhd_get = $purreqhd->first();
                
                $purreqhd->update([
                    'approveddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'recstatus' => 'APPROVED'
                ]);
                
                DB::table("material.purreqdt")
                    ->where('recno','=',$purreqhd_get->recno)
                    ->update([
                        'recstatus' => 'APPROVED',
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                    ]);
                
                DB::table("material.queuepr")
                    ->where('recno','=',$purreqhd_get->recno)
                    ->update([
                        'recstatus' => 'APPROVED',
                        'trantype' => 'DONE',
                        'adduser' => session('username'),
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur")
                    ]);
            }
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
        
    }
    
    public function showpdf(Request $request){
        $idno = $request->idno;
        if(empty($idno)){
            abort(404);
        }

        $salehdr = DB::table('finance.salehdr as sh')
            ->select('sh.idno','sh.compcode','sh.source','sh.trantype','sh.auditno','sh.quoteno','sh.lineno_','sh.amount','sh.outamount','sh.hdrsts','sh.posteddate','sh.entrydate','sh.entrytime','sh.entryuser','sh.reference','sh.recptno','sh.paymode','sh.tillcode','sh.tillno','sh.debtortype','sh.debtorcode','sh.payercode','sh.billdebtor','sh.remark','sh.mrn','sh.episno','sh.authno','sh.expdate','sh.adddate','sh.adduser','sh.upddate','sh.upduser','sh.epistype','sh.cbflag','sh.conversion','sh.payername','sh.hdrtype','sh.currency','sh.rate','sh.startdate','sh.termvalue','sh.termcode','sh.frequency','sh.pono','sh.podate','sh.saleid','sh.billtype','sh.docdate','sh.unit','sh.recstatus','sh.deptcode','m.name as debtorcode_desc','m.address1','m.address2','m.address3','m.address4','bt.description as billtype_desc','pm.Name as  pm_name','pm.address1 as pm_address1','pm.address2 as pm_address2','pm.address3 as pm_address3','pm.postcode as pm_postcode','sh.doctorcode','dc.doctorname')
            ->leftJoin('debtor.debtormast as m', function($join) use ($request){
                $join = $join->on("m.debtorcode", '=', 'sh.debtorcode');    
                $join = $join->where("m.compcode", '=', session('compcode'));
            })
            ->leftJoin('hisdb.billtymst as bt', function($join) use ($request){
                $join = $join->on("bt.billtype", '=', 'sh.hdrtype');    
                $join = $join->where("bt.compcode", '=', session('compcode'));
            })
            ->leftJoin('hisdb.pat_mast as pm', function($join) use ($request){
                $join = $join->on("pm.newmrn", '=', 'sh.mrn');    
                $join = $join->where("pm.compcode", '=', session('compcode'));
            })
            ->leftJoin('hisdb.doctor as dc', function($join) use ($request){
                $join = $join->on("dc.doctorcode", '=', 'sh.doctorcode');    
                $join = $join->where("dc.compcode", '=', session('compcode'));
            })
            ->where('sh.idno','=',$idno)
            // ->where('h.mrn','=','0')
            ->where('sh.compcode','=',session('compcode'))
            ->first();

        $salesum = DB::table('finance.salesum as ss')
            ->select('ss.idno','ss.compcode','ss.source','ss.trantype','ss.auditno','ss.lineno_','ss.description','ss.quantity','ss.amount','ss.outamt','ss.totamount','ss.taxcode','ss.taxamt','ss.mrn','ss.episno','ss.paymode','ss.cardno','ss.debtortype','ss.debtorcode','ss.billno','ss.rowno','ss.billtype','ss.chgclass','ss.classlevel','ss.chggroup','ss.lastuser','ss.lastupdate','ss.invcode','ss.seqno','ss.discamt','ss.docref','ss.uprice','ss.remarks','ss.invdate','ss.percentdisc','ss.amtdisc','ss.adduser','ss.adddate','ss.upduser','ss.upddate','ss.saleid','ss.uom','ss.uom_recv','ss.pouom','ss.reference','ss.balance','ss.qtyonhand','ss.qtydelivered','ss.ucost','ss.qtydel','ss.unitprice','ss.billtypeperct','ss.billtypeamt','ss.recstatus','cm.description as chgmast_desc','u.description as uom_desc')
            ->leftJoin('hisdb.chgmast as cm', function($join) use ($request){
                $join = $join->on('cm.chgcode', '=', 'ss.chggroup');
                $join = $join->on('cm.uom', '=', 'ss.uom');
                $join = $join->where("cm.compcode", '=', session('compcode'));
            })
            ->leftJoin('material.uom as u', function($join) use ($request){
                $join = $join->on('u.uomcode', '=', 'ss.uom');
                $join = $join->where("u.compcode", '=', session('compcode'));
            })
            ->where('ss.source','=','SL')
            ->where('ss.trantype','=','RECNO')
            ->where('ss.auditno','=',$salehdr->auditno)
            ->where('ss.compcode','=',session('compcode'))
            ->get();

        // $chgmast = DB::table('debtor.billsum AS b', 'hisdb.chgmast as m')
        //     ->select('b.compcode', 'b.idno','b.invno', 'b.mrn', 'b.billno', 'b.lineno_', 'b.chgclass', 'b.chggroup', 'b.description', 'b.uom', 'b.quantity', 'b.amount', 'b.outamt', 'b.taxamt', 'b.unitprice', 'b.taxcode', 'b.discamt', 'b.recstatus', 'm.description as chgmast_desc')
        //     ->leftJoin('hisdb.chgmast as m', 'b.description', '=', 'm.description')
        //     ->where('b.source','=',$dbacthdr->source)
        //     ->where('b.trantrype','=',$dbacthdr->trantrype)
        //     ->where('b.billno','=',$dbacthdr->auditno)
        //     ->get();
        
        $title = " QUOTATION";

        $company = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();

        $totamount_expld = explode(".", (float)$salehdr->amount);

        $totamt_bm_rm = $this->convertNumberToWordBM($totamount_expld[0])." RINGGIT ";
        $totamt_bm = $totamt_bm_rm." SAHAJA";

        if(count($totamount_expld) > 1){
            $totamt_bm_sen = $this->convertNumberToWordBM($totamount_expld[1])." SEN";
            $totamt_bm = $totamt_bm_rm.$totamt_bm_sen." SAHAJA";
        }
        
        // $pdf = PDF::loadView('finance.SalesOrder.SalesOrder_pdf',compact('dbacthdr','billsum','totamt_bm','company', 'title'));
    
        // return $pdf->stream();
        
        return view('finance.Quotation_SO.Quotation_SO_pdfmake',compact('salehdr','salesum','totamt_bm','company', 'title'));
    }
    
    // function sendmeail($data) -- nak kena ada atau tak

    function skip_authorization(Request $request, $deptcode, $idno){
        $authdtl = DB::table('material.authdtl')
                    ->where('authorid','=',session('username'))
                    ->where('trantype','=','PR')
                    ->where(function($q) use ($deptcode) {
                          $q->where('deptcode', $deptcode)
                            ->orWhere('deptcode', 'ALL');
                      })
                    ->where('recstatus','=','APPROVED');
        // dd($authdtl->first());

        if($authdtl->count() > 0){

            $purreqhd = DB::table("material.purreqhd")
                ->where('idno','=',$idno);

            $purreqhd_get = $purreqhd->first();

            $authdtl = DB::table('material.authdtl')
                    ->where('authorid','=',session('username'))
                    ->where('trantype','=','PR')
                    ->where(function($q) use ($deptcode) {
                          $q->where('deptcode', $deptcode)
                            ->orWhere('deptcode', 'ALL');
                      });

            $array_update = [];
            $array_update['requestby'] = session('username');
            $array_update['requestdate'] = Carbon::now("Asia/Kuala_Lumpur");
            $array_update['recstatus'] = 'APPROVED';
            foreach ($authdtl->get() as $key => $value) {

                switch ($value->recstatus) {
                    case 'SUPPORT':
                        $array_update['supportby'] = session('username');
                        $array_update['supportdate'] = Carbon::now("Asia/Kuala_Lumpur");
                        break;
                    case 'VERIFIED':
                        $array_update['verifiedby'] = session('username');
                        $array_update['verifieddate'] = Carbon::now("Asia/Kuala_Lumpur");
                        break;
                    case 'APPROVED':
                        $array_update['approvedby'] = session('username');
                        $array_update['approveddate'] = Carbon::now("Asia/Kuala_Lumpur");
                        break;
                }
                
            }

            $purreqhd->update($array_update);
            

            DB::table("material.purreqdt")
                ->where('recno','=',$purreqhd_get->recno)
                ->update([
                    'recstatus' => 'APPROVED',
                    'upduser' => session('username'),
                    'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                ]);

            $queuepr = DB::table("material.queuepr")
                        ->where('compcode','=',session('compcode'))
                        ->where('recno','=',$purreqhd_get->recno)
                        ->where('AuthorisedID','=',session('username'))
                        ->where('deptcode','=',$purreqhd_get->reqdept);

            if($queuepr->exists()){

                $queuepr
                    ->update([
                        'recstatus' => 'APPROVED',
                        'trantype' => 'DONE',
                    ]);
                    
            }else{

                DB::table("material.queuepr")
                    ->insert([
                        'compcode' => session('compcode'),
                        'recno' => $purreqhd_get->recno,
                        'AuthorisedID' => session('username'),
                        'deptcode' => $purreqhd_get->reqdept,
                        'recstatus' => 'APPROVED',
                        'trantype' => 'DONE',
                        'adduser' => session('username'),
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur")
                    ]);
            }

            return true;
        }
        
        return false;   
    }

    public function updivdspdt($billsum_obj,$product,$dbacthdr,$stockloc,$insertGetId){
        $ivdspdt_arr = [
            'compcode' => session('compcode'),
            'recno' => $billsum_obj->idno,
            'lineno_' => $billsum_obj->lineno_,
            'itemcode' => $billsum_obj->chggroup,
            'txnqty' => $billsum_obj->quantity,
            'adduser' => session('username'),
            'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
            'netprice' => $billsum_obj->unitprice,
            'productcat' => $product->productcat,
            'reqdept' => $dbacthdr->deptcode,
            'saleamt' => $billsum_obj->amount,
            'trantype' => $billsum_obj->trantype,
            'trandate' => $dbacthdr->entrydate,
            'trxaudno' => $billsum_obj->idno,
            'mrn' => $dbacthdr->mrn,
            'episno' => $dbacthdr->episno,
            'updtime' => Carbon::now("Asia/Kuala_Lumpur")
        ];

        $ivtntype = DB::table('material.ivtntype')
                        ->where('compcode','=', session('compcode'))
                        ->where('trantype','=', $stockloc->disptype)
                        ->first();

        if($ivtntype->updamt = 1){
            $category = DB::table('material.category')
                        ->where('compcode','=', session('compcode'))
                        ->where('catcode','=', $product->productcat)
                        ->first();

            $department = DB::table('sysdb.departmet')
                        ->where('compcode','=', session('compcode'))
                        ->where('deptcode','=',$dbacthdr->deptcode)
                        ->first();

            $ivdspdt_arr['DrCcode'] = $department->costcode;
            $ivdspdt_arr['DrAccNo'] = $category->cosacct;
            $ivdspdt_arr['CrCcode'] = $department->costcode;
            $ivdspdt_arr['CrAccNo'] = $category->stockacct;

            $glinface_arr = [
                'compcode' => session('compcode'),
                'Source' => 'IV',
                'TranType' => $stockloc->disptype,
                'AuditNo' => $insertGetId,
                'LineNo' => 1,
                'Reference' => $dbacthdr->deptcode.' - '.$billsum_obj->chggroup,
                'IdNo' => $dbacthdr->mrn.' - '.$dbacthdr->episno,
                'Description' => 'Posted from Online-Dispensing',
                'Amount' => $billsum_obj->amount,
                'PostDate' => $dbacthdr->entrydate,
                'OprType' => 'A',
                'LastUser' => session('username'),
                'LastUpdate' => Carbon::now("Asia/Kuala_Lumpur")
            ];

            if($ivtntype->crdbfl = 'OUT'){
                $glinface_arr['DrCcode'] = $department->costcode;
                $glinface_arr['DrAccNo'] = $category->cosacct;
                $glinface_arr['CrCcode'] = $department->costcode;
                $glinface_arr['CrAccNo'] = $category->stockacct;
            }

            DB::table('finance.glinface')
                ->insert($glinface_arr);
        }

        DB::table('material.ivdspdt')
                ->insert($ivdspdt_arr);
    }

    public function crtivdspdt($billsum_obj,$product,$dbacthdr,$stockloc){//xguna
        $ivdspdt_arr = [
            'compcode' => session('compcode'),
            'recno' => $billsum_obj->idno,
            'lineno_' => $billsum_obj->lineno_,
            'itemcode' => $billsum_obj->chggroup,
            'txnqty' => $billsum_obj->quantity,
            'adduser' => session('username'),
            'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
            'netprice' => $billsum_obj->unitprice,
            'productcat' => $product->productcat,
            'reqdept' => $dbacthdr->deptcode,
            'saleamt' => $billsum_obj->amount,
            'trantype' => $billsum_obj->trantype,
            'trandate' => $dbacthdr->entrydate,
            'trxaudno' => $billsum_obj->idno,
            'mrn' => $dbacthdr->mrn,
            'episno' => $dbacthdr->episno,
            'updtime' => Carbon::now("Asia/Kuala_Lumpur")
        ];

        $ivtntype = DB::table('material.ivtntype')
                        ->where('compcode','=', session('compcode'))
                        ->where('trantype','=', $stockloc->disptype)
                        ->first();

        if($ivtntype->updamt = 1){
            $category = DB::table('material.category')
                        ->where('compcode','=', session('compcode'))
                        ->where('catcode','=', $product->productcat)
                        ->first();

            $department = DB::table('sysdb.departmet')
                        ->where('compcode','=', session('compcode'))
                        ->where('deptcode','=',$dbacthdr->deptcode)
                        ->first();

            $ivdspdt_arr['DrCcode'] = $department->costcode;
            $ivdspdt_arr['DrAccNo'] = $category->cosacct;
            $ivdspdt_arr['CrCcode'] = $department->costcode;
            $ivdspdt_arr['CrAccNo'] = $category->stockacct;

            $glinface_arr = [
                'compcode' => session('compcode'),
                'Source' => 'IV',
                'TranType' => $stockloc->disptype,
                'AuditNo' => $insertGetId,
                'LineNo' => 1,
                'Reference' => $dbacthdr->deptcode.' - '.$billsum_obj->chggroup,
                'IdNo' => $dbacthdr->mrn.' - '.$dbacthdr->episno,
                'Description' => 'Posted from Online-Dispensing',
                'Amount' => $billsum_obj->amount,
                'PostDate' => $dbacthdr->entrydate,
                'OprType' => 'A',
                'LastUser' => session('username'),
                'LastUpdate' => Carbon::now("Asia/Kuala_Lumpur")
            ];

            if($ivtntype->crdbfl = 'OUT'){
                $glinface_arr['DrCcode'] = $department->costcode;
                $glinface_arr['DrAccNo'] = $category->cosacct;
                $glinface_arr['CrCcode'] = $department->costcode;
                $glinface_arr['CrAccNo'] = $category->stockacct;
            }

            DB::table('finance.glinface')
                ->insert($glinface_arr);
        }

        DB::table('material.ivdspdt')
                ->insert($ivdspdt_arr);
    }

    public function get_hdrtype(Request $request){

        $billtymst = collect();
        $today = Carbon::now("Asia/Kuala_Lumpur");


        $billtymst2 = DB::table('hisdb.billtymst')
                            ->where('compcode',session('compcode'))
                            ->where('recstatus','ACTIVE')
                            ->whereNotNull('effdatefrom')
                            ->whereNotNull('effdatefrom')
                            ->where('opprice',1)
                            ->whereDate('effdatefrom','<=',$today)
                            ->whereDate('effdateto','>=',$today);
        if($billtymst2->exists()){
            foreach ($billtymst2->get() as $key => $value) {
                $billtymst->push($value);
            }
        }

        $billtymst3 = DB::table('hisdb.billtymst')
                            ->where('compcode',session('compcode'))
                            ->where('recstatus','ACTIVE')
                            ->whereNull('effdatefrom')
                            ->whereNotNull('effdateto')
                            ->where('opprice',1)
                            ->whereDate('effdateto','>=',$today);
        if($billtymst3->exists()){
            foreach ($billtymst3->get() as $key => $value) {
                $billtymst->push($value);
            }
        }

        $billtymst4 = DB::table('hisdb.billtymst')
                            ->where('compcode',session('compcode'))
                            ->where('recstatus','ACTIVE')
                            ->whereNotNull('effdatefrom')
                            ->whereNull('effdateto')
                            ->where('opprice',1)
                            ->whereDate('effdatefrom','<=',$today);
        if($billtymst4->exists()){
            foreach ($billtymst4->get() as $key => $value) {
                $billtymst->push($value);
            }
        }

        $responce = new stdClass();
        $responce->rows = $billtymst;

        return json_encode($responce);
    }

    public function get_hdrtype_check(Request $request){
        $hdrtype = $request->filterVal[0];
        $billtymst = collect();
        $today = Carbon::now("Asia/Kuala_Lumpur");


        $billtymst2 = DB::table('hisdb.billtymst')
                            ->where('compcode',session('compcode'))
                            ->where('billtype',$hdrtype)
                            ->where('recstatus','ACTIVE')
                            ->whereNotNull('effdatefrom')
                            ->whereNotNull('effdatefrom')
                            ->where('opprice',1)
                            ->whereDate('effdatefrom','<=',$today)
                            ->whereDate('effdateto','>=',$today);
        if($billtymst2->exists()){
            foreach ($billtymst2->get() as $key => $value) {
                $billtymst->push($value);
            }
        }

        $billtymst3 = DB::table('hisdb.billtymst')
                            ->where('compcode',session('compcode'))
                            ->where('billtype',$hdrtype)
                            ->where('recstatus','ACTIVE')
                            ->whereNull('effdatefrom')
                            ->whereNotNull('effdateto')
                            ->where('opprice',1)
                            ->whereDate('effdateto','>=',$today);
        if($billtymst3->exists()){
            foreach ($billtymst3->get() as $key => $value) {
                $billtymst->push($value);
            }
        }

        $billtymst4 = DB::table('hisdb.billtymst')
                            ->where('compcode',session('compcode'))
                            ->where('billtype',$hdrtype)
                            ->where('recstatus','ACTIVE')
                            ->whereNotNull('effdatefrom')
                            ->whereNull('effdateto')
                            ->where('opprice',1)
                            ->whereDate('effdatefrom','<=',$today);
        if($billtymst4->exists()){
            foreach ($billtymst4->get() as $key => $value) {
                $billtymst->push($value);
            }
        }
        
        $responce = new stdClass();
        $responce->rows = $billtymst;

        return json_encode($responce);
    }
    
    public function get_debtorMaster(Request $request){
        
        $table = DB::table('debtor.debtormast')
                ->where('compcode',session('compcode'))
                ->where('recstatus','!=',' ');
        
        if(!empty($request->searchCol)){
            $searchCol_array = $request->searchCol;
            
            $count = array_count_values($searchCol_array);
            
            foreach($count as $key => $value){
                $occur_ar = $this->index_of_occurance($key,$searchCol_array);
                
                $table = $table->where(function ($table) use ($request,$searchCol_array,$occur_ar){
                    foreach($searchCol_array as $key => $value){
                        $found = array_search($key,$occur_ar);
                        if($found !== false){
                            $table->Where($searchCol_array[$key],'like',$request->searchVal[$key]);
                            // $table->Where('uom.'.$searchCol_array[$key],'like',$request->searchVal[$key]);
                        }
                    }
                });
            }
        }
        
        if(!empty($request->searchCol2)){
            $searchCol_array = $request->searchCol2;
            $table = $table->where(function ($table) use ($searchCol_array, $request){
                foreach($searchCol_array as $key => $value){
                    if($key > 1) break;
                    $table->orwhere($searchCol_array[$key],'like', $request->searchVal2[$key]);
                    // $table->orwhere('uom.'.$searchCol_array[$key],'like', $request->searchVal2[$key]);
                }
            });
            
            if(count($searchCol_array) > 2){
                $table = $table->where(function ($table) use ($searchCol_array, $request){
                    foreach($searchCol_array as $key => $value){
                        if($key <= 1) continue;
                        $table->orwhere($searchCol_array[$key],'like', $request->searchVal2[$key]);
                        // $table->orwhere('uom.'.$searchCol_array[$key],'like', $request->searchVal2[$key]);
                    }
                });
            }
        }
        
        if(!empty($request->filterCol)){
            foreach($request->filterCol as $key => $value){
                $table = $table->where($request->filterCol[$key],'=',$request->filterVal[$key]);
            }
        }
        
        if(!empty($request->sidx)){
            if(!empty($request->fixPost)){
                $request->sidx = substr_replace($request->sidx, ".", strpos($request->sidx, "_"), strlen("."));
            }
            
            $pieces = explode(", ", $request->sidx .' '. $request->sord);
            if(count($pieces) == 1){
                $table = $table->orderBy($request->sidx, $request->sord);
            }else{
                for($i = sizeof($pieces)-1; $i >= 0 ; $i--){
                    $pieces_inside = explode(" ", $pieces[$i]);
                    $table = $table->orderBy($pieces_inside[0], $pieces_inside[1]);
                }
            }
        }else{
            $table = $table->orderBy('idno','asc');
        }
        
        //////////paginate//////////
        $paginate = $table->paginate($request->rows);
        
        foreach($paginate->items() as $key => $value){
            if($value->recstatus == 'ACTIVE' || $value->recstatus == 'A'){
                $value->recstatus = ' ';
            }
        }
        
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
    
    public function chk_billtype(Request $request){ 
        $hdrtype = $request->SL_hdrtype;
        $posteddate = $request->SL_entrydate;
        $responce = new stdClass();
        $responce->error = false;
        $responce->msg = '';

        $billtymst_active = DB::table('hisdb.billtymst')
                            ->where('compcode',session('compcode'))
                            ->where('billtype',$hdrtype)
                            ->where('recstatus','!=','ACTIVE');
        if($billtymst_active->exists()){
            $responce->error = true;
            $responce->msg = 'Billtype deactive, please check..';

            return $responce;
        }

        $billtymst_opprice = DB::table('hisdb.billtymst')
                            ->where('compcode',session('compcode'))
                            ->where('billtype',$hdrtype)
                            ->where('opprice','!=','1');
        if($billtymst_opprice->exists()){
            $responce->error = true;
            $responce->msg = 'Billtype setup incorrect, please check..';

            return $responce;
        }

        $billtymst2 = DB::table('hisdb.billtymst')
                            ->where('compcode',session('compcode'))
                            ->where('billtype',$hdrtype)
                            ->where('recstatus','ACTIVE')
                            ->whereNotNull('effdateto')
                            ->where('opprice',1)
                            ->whereDate('effdateto','<',$posteddate);
        if($billtymst2->exists()){
            $responce->error = true;
            $responce->msg = 'Billtype date exceed , please check..';

            return $responce;
        }

        $billtymst4 = DB::table('hisdb.billtymst')
                            ->where('compcode',session('compcode'))
                            ->where('billtype',$hdrtype)
                            ->where('recstatus','ACTIVE')
                            ->whereNotNull('effdatefrom')
                            ->where('opprice',1)
                            ->whereDate('effdatefrom','>',$posteddate);
        if($billtymst4->exists()){
            $responce->error = true;
            $responce->msg = 'Billtype date exceed, please check..';

            return $responce;
        }

        return $responce;
    }

    
}
