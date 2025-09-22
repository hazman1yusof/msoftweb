<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use PDF;
use Guzzle\Http\Exception\ClientErrorResponseException;
use GuzzleHttp\Client;

class SalesOrderController extends defaultController
{   
    var $gltranAmount;

    public function __construct(){
        $this->middleware('auth');
    }

    public function show(Request $request){   
        $storedept = DB::table('sysdb.department')
                        ->select('deptcode')
                        ->where('compcode',session('compcode'))
                        ->where('recstatus','ACTIVE')
                        ->where('storedept',1)
                        ->where('chgdept',1)
                        ->get();

        return view('finance.SalesOrder.SalesOrder',compact('storedept'));
    }

    public function show_mobile(Request $request){
        $oper = strtolower($request->scope);//delivered shj sekarang
        $scope = ucfirst(strtolower($request->scope));//Delivered
        $auditno = $request->auditno;

        $db_hd = DB::table('debtor.dbacthdr AS db')
                        ->select('db.debtorcode','db.payercode','dm.name AS payercode_desc','db.entrydate','db.auditno','db.invno','db.ponum','db.amount','db.remark','db.lineno_','db.orderno','db.outamount','db.debtortype','db.billdebtor','db.approvedby','db.mrn','db.unit','db.source','db.trantype','db.termdays','db.termmode','db.hdrtype','db.podate','db.posteddate','db.deptcode','dept.description as deptcode_desc','db.recstatus','db.idno','db.adduser','db.adddate','db.upduser','db.quoteno','db.preparedby','db.prepareddate','db.cancelby','db.canceldate','db.cancelled_remark','db.approved_remark','db.upddate')
                        ->leftJoin('debtor.debtormast as dm', function($join) use ($request){
                            $join = $join->where('dm.compcode', '=', session('compcode'));
                            $join = $join->on('dm.debtorcode', '=', 'db.debtorcode');
                        })->leftjoin('sysdb.department as dept', function($join) use ($request){
                            $join = $join
                                ->where('dept.compcode',session('compcode'))
                                ->on('dept.deptcode','db.deptcode');
                        })
                        ->where('db.compcode',session('compcode'))
                        ->where('db.source','PB')
                        ->where('db.trantype','IN')
                        ->where('db.auditno',$auditno)
                        ->first();

        $db_dt = DB::table('debtor.billsum AS bs')
                ->select('bs.rowno','bs.chggroup','cm.description as chggroup_desc','bs.quantity','bs.qtyorder','bs.unitprice','bs.uom','uom.description as uom_desc','bs.uom_recv','uom.description as uom_recv_desc','bs.totamount')
                ->leftjoin('hisdb.chgmast as cm', function($join) use ($request){
                    $join = $join
                        ->where('cm.compcode',session('compcode'))
                        ->where('cm.unit',session('unit'))
                        ->on('cm.uom','bs.uom')
                        ->on('cm.chgcode','bs.chggroup');
                })
                ->leftjoin('material.uom as uom', function($join) use ($request){
                    $join = $join
                        ->where('uom.compcode',session('compcode'))
                        ->on('uom.uomcode','bs.uom');
                })
                ->leftjoin('material.uom as uom_recv', function($join) use ($request){
                    $join = $join
                        ->where('uom_recv.compcode',session('compcode'))
                        ->on('uom_recv.uomcode','bs.uom_recv');
                })
                ->where('bs.compcode','=',session('compcode'))
                ->where('bs.source','=','PB')
                ->where('bs.trantype','=','IN')
                ->where('bs.billno','=',$auditno)
                ->where('bs.recstatus','!=','DELETE')
                ->get();

        // dd($db_dt->get());

        return view('finance.SalesOrder.SalesOrder_mobile',compact('db_hd','db_dt','scope','oper'));
    }

    public function table(Request $request){   
        DB::enableQueryLog();
        switch($request->action){
            case 'maintable':
                return $this->maintable($request);
            case 'get_hdrtype':
                return $this->get_hdrtype($request);
            case 'get_quoteno':
                return $this->get_quoteno($request);
            case 'get_salesum':
                return $this->get_salesum($request);
            case 'get_hdrtype_check':
                return $this->get_hdrtype_check($request);
            case 'get_quoteno_check':
                return $this->get_quoteno_check($request);
            case 'get_debtor_dtl':
                return $this->get_debtor_dtl($request);
            case 'showpdf_do':
                return $this->showpdf_do($request);
            default:
                return 'error happen..';
        }
    }

    public function form(Request $request){   
        DB::enableQueryLog();
        switch($request->oper){ //SalesOrder_header_save 
            case 'add':
                return $this->add($request);
            case 'edit':
                return $this->edit($request);
            case 'del':
                return $this->del($request);
            case 'posted':
                return $this->posted($request);
            case 'delivered':
                return $this->delivered($request);
            case 'recomputed':
                return $this->recomputed($request);
            case 'reject':
                return $this->reject($request);
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
            case 'new_patient':
                return $this->new_patient($request);
            case 'new_customer':
                return $this->new_customer($request);
            case 'pos_receipt_save':
                return $this->pos_receipt_save($request);
            default:
                return 'Errors happen';
        }
    }

    public function maintable(Request $request){

        if($request->scope == 'history'){
            $compcode = 'xx';
        }else{
            $compcode = session('compcode');
        }
        
        $table = DB::table('debtor.dbacthdr AS db')
                    ->select(
                        'db.compcode AS db_compcode',
                        'db.debtorcode AS db_debtorcode',
                        'db.payercode AS db_payercode',
                        'dm.name AS dm_name', 
                        'db.entrydate AS db_entrydate',
                        'db.auditno AS db_auditno',
                        'db.invno AS db_invno',
                        'db.ponum AS db_ponum',
                        'db.amount AS db_amount',
                        'db.remark AS db_remark',
                        'db.lineno_ AS db_lineno_',
                        'db.orderno AS db_orderno',
                        'db.outamount AS db_outamount',
                        'db.debtortype AS db_debtortype',
                        'db.billdebtor AS db_billdebtor',
                        'db.approvedby AS db_approvedby',
                        'db.mrn AS db_mrn',
                        'db.unit AS db_unit',
                        'db.source AS db_source',
                        'db.trantype AS db_trantype',
                        'db.termdays AS db_termdays',
                        'db.termmode AS db_termmode',
                        'db.hdrtype AS db_hdrtype',
                        'db.podate AS db_podate',
                        'db.posteddate AS db_posteddate',
                        'db.deptcode AS db_deptcode',
                        'db.recstatus AS db_recstatus',
                        'db.idno AS db_idno',
                        'db.adduser AS db_adduser',
                        'db.adddate AS db_adddate',
                        'db.upduser AS db_upduser',
                        'db.quoteno AS db_quoteno',
                        'db.preparedby AS db_preparedby',
                        'db.prepareddate AS db_prepareddate',
                        'db.cancelby AS db_cancelby',
                        'db.canceldate AS db_canceldate',
                        'db.cancelled_remark AS db_cancelled_remark',
                        'db.approved_remark AS db_approved_remark',
                        'db.upddate AS db_upddate',
                        'db.pointofsales AS db_pointofsales',
                        'db.doctorcode AS db_doctorcode'
                    )
                    ->whereIn('db.compcode',[session('compcode'),'xx'])
                    ->where('db.source','PB')
                    ->whereIn('db.trantype',['IN','RD'])
                    ->whereNotNull('db.deptcode')
                    ->where('db.pointofsales','0');
        
        $table = $table->join('debtor.debtormast as dm', function($join) use ($request){
                $join = $join->where('dm.compcode', '=', session('compcode'));
                $join = $join->on('dm.debtorcode', '=', 'db.debtorcode');
        });
        
        if(!empty($request->filterCol)){
            // $table = $table->where($request->filterCol[0],'=',$request->filterVal[0]);
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
                }else if($pieces[0] == 'notnull'){
                    $table = $table->whereNotNull($request->filterCol[$key]);
                }else if($pieces[0] == 'raw'){
                    $table = $table->where($request->filterCol[$key],'=',DB::raw($pieces[1]));
                }else{
                    $table = $table->where($request->filterCol[$key],'=',$request->filterVal[$key]);
                }
            }
        }
        
        if(!empty($request->filterdate)){
            $table = $table->where('db.entrydate','>',$request->filterdate[0]);
            $table = $table->where('db.entrydate','<',$request->filterdate[1]);
        }
        
        if(!empty($request->searchCol)){
            if($request->searchCol[0] == 'db_invno'){
                $table = $table->Where(function ($table) use ($request){
                        $table->Where('db.invno','like',$request->searchVal[0]);
                });
            }else if($request->searchCol[0] == 'dm_name'){
                $table = $table->Where(function ($table) use ($request){
                        $table->Where('dm.name','like',$request->searchVal[0]);
                });
            }else if($request->searchCol[0] == 'db_payercode'){
                $table = $table->Where(function ($table) use ($request){
                        $table->Where('db.payercode','like',$request->searchVal[0]);
                });
            }else if($request->searchCol[0] == 'db_mrn'){
                $table = $table->Where(function ($table) use ($request){
                        $table->Where('db.mrn','like',$request->searchVal[0]);
                });
            }else if($request->searchCol[0] == 'db_auditno'){
                $table = $table->Where(function ($table) use ($request){
                        $table->Where('db.auditno',$request->wholeword);
                });
            }else{
                $table = $table->Where(function ($table) use ($request){
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
                    $value_ = substr_replace($value,"db.",0,strpos($value,"_")+1);
                    $pieces_inside = explode(" ", $value_);
                    $table = $table->orderBy($pieces_inside[0], $pieces_inside[1]);
                }
            }
        }else{
            $table = $table->orderBy('db.idno','DESC');
        }
        
        $paginate = $table->paginate($request->rows);
        
        // foreach ($paginate->items() as $key => $value) {
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
        
        $table = DB::table("debtor.dbacthdr");
        
        try {
            
            $auditno = $this->recno('PB','IN');
            $chk_billtype = $this->chk_billtype($request);

            if($chk_billtype->error){
                throw new \Exception($chk_billtype->msg,500);
            }
            
            if(!empty($request->db_mrn)){
                $pat_mast = DB::table('hisdb.pat_mast')
                            ->where('compcode','=',session('compcode'))
                            ->where('MRN','=',$request->db_mrn)
                            ->first();
            }
            
            $array_insert = [
                'source' => 'PB',
                'trantype' => 'IN',
                'auditno' => $auditno,
                'compcode' => session('compcode'),
                'adduser' => session('username'),
                'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                'upduser' => session('username'),
                'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                'recstatus' => 'OPEN',
                'lineno_' => 1,
                // 'invno' => $invno,
                'deptcode' => strtoupper($request->db_deptcode),
                'quoteno' =>  (!empty($request->db_quoteno))?$request->db_quoteno:null,
                'unit' => session('unit'),//department.sector
                'debtorcode' => strtoupper($request->db_debtorcode),
                'payercode' => strtoupper($request->db_debtorcode),
                'entrydate' => strtoupper($request->db_entrydate),
                'entrytime' => Carbon::now("Asia/Kuala_Lumpur"),
                'hdrtype' => strtoupper($request->db_hdrtype),
                // 'mrn' => strtoupper($request->db_mrn),
                'mrn' => strtoupper($request->db_mrn),
                'doctorcode' => strtoupper($request->db_doctorcode),
                // 'billno' => $invno,
                // 'episno' => (!empty($request->db_mrn))?$pat_mast->Episno:null,
                'termdays' => strtoupper($request->db_termdays),
                'termmode' => strtoupper($request->db_termmode),
                'orderno' => strtoupper($request->db_orderno),
                'ponum' => strtoupper($request->db_ponum),
                'podate' => (!empty($request->db_podate))?$request->db_podate:null,
                'remark' => strtoupper($request->db_remark),
                // 'approvedby' => $request->db_approvedby
            ];
            
            //////////where//////////
            // $table = $table->where('idno','=',$request->idno);
            $idno = $table->insertGetId($array_insert);

            $totalAmount = 0;
            if(!empty($request->db_quoteno)){
                $totalAmount = $this->save_dt_from_othr_qo($request->db_quoteno,$idno);
            }
            
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

    public function save_dt_from_othr_qo($quoteno,$idno){
        $dbacthdr = DB::table("debtor.dbacthdr")
                        ->where('compcode',session('compcode'))
                        ->where('idno',$idno)
                        ->first();

        $qo_hd = DB::table('finance.salehdr')
                        ->where('compcode',session('compcode'))
                        ->where('source','SL')
                        ->where('trantype','RECNO')
                        ->where('recstatus','!=','COMPLETED')
                        ->where('quoteno',$quoteno);

        if(!$qo_hd->exists()){
            throw new \Exception('Wrong Quotation, please check',500);
        }

        $qo_hd = $qo_hd->first();

        $qo_dt = DB::table('finance.salesum')
                        ->where('compcode',session('compcode'))
                        ->where('source','SL')
                        ->where('trantype','RECNO')
                        ->where('auditno',$qo_hd->auditno)
                        ->get();

        foreach ($qo_dt as $key => $value) {
            
            ////1. calculate rowno by recno
            // $sqlln = DB::table('debtor.billsum')->select('rowno')
            //             ->where('compcode','=',session('compcode'))
            //             ->where('source','=','PB')
            //             ->where('trantype','=','IN')
            //             ->where('billno','=',$dbacthdr->auditno)
            //             ->count('rowno');
            
            // $li=intval($sqlln)+1;

            $quantity = floatval($value->quantity) - floatval($value->qtydelivered);
            if($quantity == 0){
                continue;
            }
            // $amount = $value->unitprice * $quantity;
            // $discamt = ($amount * (100-$value->billtypeperct) / 100) + $value->billtypeamt;
            // $rate = $this->taxrate($value->taxcode);
            // $taxamt = $amount * $rate / 100;
            // $totamount = $amount - $discamt + $taxamt;

            $stockloc = DB::table('material.stockloc')
                    ->where('compcode','=',session('compcode'))
                    ->where('uomcode','=',$value->uom)
                    ->where('itemcode','=',$value->chggroup)
                    ->where('deptcode','=',$dbacthdr->deptcode)
                    ->where('year','=',Carbon::now("Asia/Kuala_Lumpur")->year)
                    ->first();
            
            ///2. insert detail
            $insertGetId = DB::table('debtor.billsum')
                ->insertGetId([
                    // 'auditno' => $recno, //->OE IN
                    'billno' => $dbacthdr->auditno, // dari dbacthdr.auditno
                    'invno' => $dbacthdr->auditno, // dari dbacthdr.auditno
                    // 'idno' => $recno, //autogen
                    'compcode' => session('compcode'),
                    'source' => 'PB',
                    'trantype' => 'IN',
                    'chggroup' => $value->chggroup,
                    'description' => $value->description,
                    'lineno_' => '1',
                    'rowno' => $value->lineno_,
                    'mrn' => $value->mrn,
                    'episno' => $value->episno,
                    // 'episno' => (!empty($dbacthdr->episno))?$dbacthdr->episno:null,
                    'uom' => $value->uom,
                    'uom_recv' => $value->uom_recv,
                    'taxcode' => $value->taxcode,
                    'unitprice' => $value->unitprice,
                    'quantity' => $quantity,
                    'qtyonhand' => $stockloc->qtyonhand,
                    'qtyorder' => $quantity,
                    'amount' => $value->amount, //unitprice * quantity, xde tax
                    'outamt' => $value->outamt,
                    'totamount' => $value->totamount,
                    'discamt' => $value->discamt,
                    'taxamt' => $value->taxamt,
                    'lastuser' => session('username'), 
                    'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"), 
                    'recstatus' => 'OPEN',
                    'taxcode' => $value->taxcode,
                    'billtypeperct' => $value->billtypeperct,
                    'billtypeamt' => $value->billtypeamt,
                ]);
            
            $billsum_obj = db::table('debtor.billsum')
                            ->where('compcode',session('compcode'))
                            ->where('idno', '=', $insertGetId)
                            ->first();

            Db::table('debtor.billsum')
                    ->where('compcode',session('compcode'))
                    ->where('idno', '=', $insertGetId)
                    ->update(['auditno' => $insertGetId]);
        }

        //3. calculate total amount from detail
        $totalAmount = DB::table('debtor.billsum')
                ->where('compcode','=',session('compcode'))
                ->where('source','=','PB')
                ->where('trantype','=','IN')
                ->where('billno','=',$dbacthdr->auditno)
                ->where('recstatus','!=','DELETE')
                ->sum('totamount');
        
        ///4. then update to header
        DB::table('debtor.dbacthdr')
                ->where('compcode','=',session('compcode'))
                ->where('source','=','PB')
                ->where('trantype','=','IN')
                ->where('auditno','=',$dbacthdr->auditno)
                ->update([
                    'amount' => $totalAmount,
                    'outamount' => $totalAmount,
                ]);
    }
    
    public function edit(Request $request){
        
        DB::beginTransaction();
        
        $table = DB::table("debtor.dbacthdr");
        
        $array_update = [
            'deptcode' => strtoupper($request->db_deptcode),
            'unit' => session('unit'),
            'debtorcode' => strtoupper($request->db_debtorcode),
            'payercode' => strtoupper($request->db_debtorcode),
            'entrydate' => strtoupper($request->db_entrydate),
            'hdrtype' => strtoupper($request->db_hdrtype),
            // 'mrn' => strtoupper($request->db_mrn),
            'termdays' => strtoupper($request->db_termdays),
            'termmode' => strtoupper($request->db_termmode),
            'orderno' => strtoupper($request->db_orderno),
            'ponum' => strtoupper($request->db_ponum),
            'podate' => (!empty($request->db_podate))?$request->db_podate:null,
            'remark' => strtoupper($request->db_remark),
            'mrn' => strtoupper($request->db_mrn),
            'doctorcode' => strtoupper($request->db_doctorcode),
            // 'approvedby' => strtoupper($request->db_approvedby)
        ];
        
        try {

            $chk_billtype = $this->chk_billtype($request);

            if($chk_billtype->error){
                throw new \Exception($chk_billtype->msg,500);
            }

            //////////where//////////
            $table = $table->where('idno','=',$request->db_idno);
            $table->update($array_update);
            
            $responce = new stdClass();
            $responce->totalAmount = $request->purreqhd_totamount;
            echo json_encode($responce);
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            
            return response($e->getMessage(), 500);
        }//xguna     
    }
    
    public function del(Request $request){        
    }
    
    public function posted(Request $request){
        
        DB::beginTransaction();
        
        try{
            
            foreach ($request->idno_array as $value){
                
                $dbacthdr = DB::table("debtor.dbacthdr")
                            ->where('compcode',session('compcode'))
                            ->where('idno','=',$value)
                            ->first();

                if(!in_array($dbacthdr->recstatus, ['OPEN','RECOMPUTED'])){
                    throw new \Exception("Cant Edit this document, status is not OPEN or RECOMPUTED");
                }

                $totalAmount = DB::table('debtor.billsum')
                        ->where('compcode','=',session('compcode'))
                        ->where('source','=','PB')
                        ->where('trantype','=','IN')
                        ->where('billno','=',$dbacthdr->auditno)
                        ->where('recstatus','!=','DELETE')
                        ->sum('totamount');
                
                DB::table("debtor.dbacthdr")
                    ->where('compcode',session('compcode'))
                    ->where('idno','=',$value)
                    ->update([
                        'amount' => $totalAmount,
                        'outamount' => $totalAmount,
                        'recstatus' => 'PREPARED',
                        'preparedby' => session('username'),
                        'prepareddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                    ]);
                
                DB::table("debtor.billsum")
                    ->where('compcode',session('compcode'))
                    ->where('source','=',$dbacthdr->source)
                    ->where('trantype','=',$dbacthdr->trantype)
                    ->where('billno','=',$dbacthdr->auditno)
                    ->update([
                        'recstatus' => 'PREPARED',
                    ]);

                DB::table('finance.queueso')
                    ->insert([
                        'compcode' => session('compcode'),
                        'recno' => $dbacthdr->auditno,
                        'AuthorisedID' => session('username'),
                        'deptcode' => 'ALL',
                        'recstatus' => 'PREPARED',
                        'trantype' => 'DELIVERED',
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

    public function delivered(Request $request){
        
        DB::beginTransaction();
        
        try{
            
            foreach ($request->idno_array as $value){
                
                $dbacthdr = DB::table("debtor.dbacthdr")
                            ->where('compcode',session('compcode'))
                            ->where('idno','=',$value)
                            ->first();

                $totalAmount = DB::table('debtor.billsum')
                        ->where('compcode','=',session('compcode'))
                        ->where('source','=','PB')
                        ->where('trantype','=','IN')
                        ->where('billno','=',$dbacthdr->auditno)
                        ->where('recstatus','!=','DELETE')
                        ->sum('totamount');
                
                DB::table("debtor.dbacthdr")
                    ->where('compcode',session('compcode'))
                    ->where('idno','=',$value)
                    ->update([
                        'amount' => $totalAmount,
                        'outamount' => $totalAmount
                    ]);

                $authorise = DB::table('finance.permissiondtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('authorid','=',session('username'))
                    ->where('trantype','=','SO')
                    ->where('cando','=', 'ACTIVE')
                    ->where('recstatus','=','DELIVERED')
                    // ->where('deptcode','=',$purordhd_get->prdept)
                    ->where('maxlimit','>=',$dbacthdr->amount);

                if(!$authorise->exists()){
                    throw new \Exception("The user doesnt have authority",500);
                }

                if($dbacthdr->recstatus != 'PREPARED'){
                    continue;
                }

                if(!empty($dbacthdr->quoteno)){
                    $qo_hd = DB::table('finance.salehdr')
                                ->where('compcode',session('compcode'))
                                ->where('source','SL')
                                ->where('trantype','RECNO')
                                ->where('quoteno',$dbacthdr->quoteno)
                                ->first();
                }
                
                if(!empty($dbacthdr->invno)){
                    $invno = $dbacthdr->invno;
                }else{
                    $invno = $this->recno('PB','INV');
                }
                
                $billsum = DB::table("debtor.billsum")
                            ->where('compcode',session('compcode'))
                            ->where('source','=',$dbacthdr->source)
                            ->where('trantype','=',$dbacthdr->trantype)
                            ->where('billno','=',$dbacthdr->auditno)
                            ->get();
                
                $qo_dt_recstatus = 'COMPLETED';
                foreach ($billsum as $billsum_obj){

                    if(!empty($dbacthdr->quoteno)){
                        $qo_dt = DB::table('finance.salesum')
                                    ->where('compcode',session('compcode'))
                                    ->where('source','SL')
                                    ->where('trantype','RECNO')
                                    ->where('lineno_',$billsum_obj->rowno)
                                    ->where('auditno',$qo_hd->auditno);

                        if($qo_dt->exists()){
                            $qo_dt = $qo_dt->first();

                            $qo_dt_qtydelivered = $qo_dt->qtydelivered;
                            $qo_dt_quantity = $qo_dt->quantity;
                            $new_qtydelivered = $billsum_obj->quantity + $qo_dt_qtydelivered;
                            // if($new_qtydelivered > $qo_dt_quantity){
                            //     $cant_exceed = $qo_dt->quantity - $qo_dt->qtydelivered;
                            //     throw new \Exception("Quotation Quantity Delivered Exceed for item: ".$billsum_obj->chggroup." uom: ".$billsum_obj->uom." Quantity delivered cant exceed: ".$cant_exceed,500);
                            // }

                            DB::table('finance.salesum')
                                        ->where('compcode',session('compcode'))
                                        ->where('source','SL')
                                        ->where('trantype','RECNO')
                                        ->where('lineno_',$billsum_obj->rowno)
                                        ->where('auditno',$qo_hd->auditno)
                                        ->update([
                                            'qtydelivered' => $new_qtydelivered
                                        ]);

                            if($qo_dt_recstatus == 'PARTIAL'){
                                $qo_dt_recstatus = 'PARTIAL';
                            }else{
                               if($qo_dt_quantity - $new_qtydelivered > 0){
                                    $qo_dt_recstatus = 'PARTIAL';
                                } 
                            }
                        }
                    }
                    
                    $chgmast = DB::table("hisdb.chgmast")
                            ->where('compcode','=',session('compcode'))
                            ->where('chgcode','=',$billsum_obj->chggroup)
                            ->where('uom','=',$billsum_obj->uom);

                    if(!$chgmast->exists()){
                        $chgmast_ = DB::table("hisdb.chgmast")
                                        ->where('compcode','=',session('compcode'))
                                        ->where('chgcode','=',$billsum_obj->chggroup)
                                        ->first();

                        DB::table('debtor.billsum')
                                ->where('idno',$billsum_obj->idno)
                                ->where('chggroup',$chgmast_->uom)
                                ->update([
                                    'uom' => $chgmast_->uom
                                ]);

                        $billsum_obj->uom = $chgmast_->uom;
                    }

                    $chgmast = DB::table("hisdb.chgmast")
                            ->where('compcode','=',session('compcode'))
                            ->where('chgcode','=',$billsum_obj->chggroup)
                            ->where('uom','=',$billsum_obj->uom)
                            ->first();
                    
                    $updinv = ($chgmast->invflag == '1') ? 1 : 0;

                    $product = DB::table('material.product')
                                    ->where('compcode','=',session('compcode'))
                                    ->where('uomcode','=',$billsum_obj->uom)
                                    ->where('itemcode','=',$billsum_obj->chggroup)
                                    ->whereIn('groupcode',['STOCK','Consignment']);
                    
                    if($product->exists() && $chgmast->invflag == 1){
                        $stockloc = DB::table('material.stockloc')
                                ->where('compcode','=',session('compcode'))
                                ->where('uomcode','=',$billsum_obj->uom)
                                ->where('itemcode','=',$billsum_obj->chggroup)
                                ->where('deptcode','=',$dbacthdr->deptcode)
                                ->where('year','=',Carbon::now("Asia/Kuala_Lumpur")->year);
                        
                        if($stockloc->exists()){
                            $stockloc = $stockloc->first();
                        }else{
                            throw new \Exception("Stockloc not exists for item: ".$billsum_obj->chggroup." dept: ".$dbacthdr->deptcode." uom: ".$billsum_obj->uom,500);
                        }
                        
                        $ivdspdt = DB::table('material.ivdspdt')
                            ->where('compcode','=',session('compcode'))
                            ->where('recno','=',$billsum_obj->auditno);
                        
                        if($ivdspdt->exists()){
                            $this->updivdspdt($billsum_obj,$dbacthdr);
                            // $this->updgltran($ivdspdt->first()->idno,$dbacthdr);
                        }else{
                            $ivdspdt_idno = $this->crtivdspdt($billsum_obj,$dbacthdr);
                            // $this->crtgltran($ivdspdt_idno,$dbacthdr);
                        }
                    }
        
                    $recno = $this->recno('OE','IN');
                    
                    $insertGetId = DB::table("hisdb.chargetrx")
                        ->insertGetId([
                            'auditno' => $recno,
                            'idno' => $billsum_obj->idno,
                            'compcode'  => session('compcode'),
                            'mrn'  => $billsum_obj->mrn,
                            'episno'  => $billsum_obj->episno,
                            // 'trxdate' => $dbacthdr->entrydate,
                            'trxdate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'chgcode' => $billsum_obj->chggroup,
                            'billflag' => 1,
                            'billdate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'billtype'  => $billsum_obj->billtype,
                            'chg_class' => $chgmast->chgclass,
                            'unitprce' => $billsum_obj->unitprice,
                            'quantity' => $billsum_obj->quantity,
                            'amount' => $billsum_obj->amount,
                            'trxtime' => $dbacthdr->entrytime,
                            'chggroup' => $chgmast->chggroup,
                            'taxamount' => $billsum_obj->taxamt,
                            'billno' => $billsum_obj->billno,
                            'invno' => $invno,
                            'uom' => $billsum_obj->uom,
                            'billtime' => $dbacthdr->entrytime,
                            'invgroup' => $chgmast->invgroup,
                            'reqdept' => $dbacthdr->deptcode,
                            'issdept' => $dbacthdr->deptcode,
                            'invcode' => $chgmast->chggroup,
                            'inventory' => $chgmast->invflag,
                            'updinv' =>  $updinv,
                            'discamt' => $billsum_obj->discamt,
                            'qtyorder' => $billsum_obj->quantity,
                            'qtyissue' => $billsum_obj->quantity,
                            'unit' => session('unit'),
                            'chgtype' => $chgmast->chgtype,
                            'adduser' => session('username'),
                            'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'lastuser' => session('username'),
                            'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'qtydispense' => $billsum_obj->quantity,
                            'taxcode' => $billsum_obj->taxcode,
                            'recstatus' => 'POSTED',
                        ]);
                    
                    DB::table("hisdb.billdet")
                        ->insert([
                            'auditno' => $recno,
                            // 'idno' => $billsum_obj->idno,
                            'compcode'  => session('compcode'),
                            'mrn'  => $billsum_obj->mrn,
                            'episno'  => $billsum_obj->episno,
                            // 'trxdate' => $dbacthdr->entrydate,
                            'trxdate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'chgcode' => $billsum_obj->chggroup,
                            'billflag' => 1,
                            'billdate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'billtype'  => $billsum_obj->billtype,
                            'chg_class' => $chgmast->chgclass,
                            'unitprce' => $billsum_obj->unitprice,
                            'quantity' => $billsum_obj->quantity,
                            'amount' => $billsum_obj->amount,
                            'trxtime' => $dbacthdr->entrytime,
                            'chggroup' => $chgmast->chggroup,
                            'taxamount' => $billsum_obj->taxamt,
                            'billno' => $billsum_obj->billno,
                            'invno' => $invno,
                            'uom' => $billsum_obj->uom,
                            'billtime' => $dbacthdr->entrytime,
                            'invgroup' => $chgmast->invgroup,
                            'reqdept' => $dbacthdr->deptcode,
                            'issdept' => $dbacthdr->deptcode,
                            'invcode' => $chgmast->chggroup,
                            // 'inventory' => $chgmast->invflag,
                            // 'updinv' =>  $updinv,
                            'discamt' => $billsum_obj->discamt,
                            // 'qtyorder' => $billsum_obj->quantity,
                            // 'qtyissue' => $billsum_obj->quantity,
                            // 'unit' => $department->sector,
                            // 'chgtype' => $chgmast->chgtype,
                            'adduser' => session('username'),
                            'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'lastuser' => session('username'),
                            'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                            // 'qtydispense' => $billsum_obj->quantity,
                            'taxcode' => $billsum_obj->taxcode,
                            'recstatus' => 'POSTED',
                        ]);
                    
                    //gltran
                    $yearperiod = $this->getyearperiod(Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d'));
                    $chgmast = DB::table('hisdb.chgmast')
                                ->where('compcode',session('compcode'))
                                ->where('chgcode',$billsum_obj->chggroup)
                                ->first();

                    $chgtype = DB::table('hisdb.chgtype')
                                ->where('compcode',session('compcode'))
                                ->where('chgtype',$chgmast->chgtype)
                                ->first();

                    $dept = DB::table('sysdb.department')
                                ->where('compcode',session('compcode'))
                                ->where('deptcode',$dbacthdr->deptcode)
                                ->first();

                    $sysparam = DB::table('sysdb.sysparam')
                                ->where('compcode',session('compcode'))
                                ->where('source','AR')
                                ->where('trantype','AD')
                                ->first();

                    // 1. buat gltran
                    DB::table('finance.gltran')
                        ->insert([
                            'compcode' => session('compcode'),
                            'adduser' => session('username'),
                            'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'auditno' => $billsum_obj->auditno,
                            'lineno_' => 1,
                            'source' => 'OE', //kalau stock 'IV', lain dari stock 'DO'
                            'trantype' => 'IN',
                            'reference' => $invno,
                            'description' => $billsum_obj->chggroup, 
                            'postdate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'year' => $yearperiod->year,
                            'period' => $yearperiod->period,
                            'drcostcode' => $sysparam->pvalue1,
                            'dracc' => $sysparam->pvalue2,
                            'crcostcode' => $dept->costcode,
                            'cracc' => $chgtype->opacccode,
                            'amount' => $billsum_obj->amount 
                        ]);

                    $this->init_glmastdtl(
                            $sysparam->pvalue1,//drcostcode
                            $sysparam->pvalue2,//dracc
                            $dept->costcode,//crcostcode
                            $chgtype->opacccode,//cracc
                            $yearperiod,
                            $billsum_obj->amount
                    );


                    if(!empty(floatval($billsum_obj->taxamt))){

                        $sysparam_tx = DB::table('sysdb.sysparam')
                                        ->where('compcode',session('compcode'))
                                        ->where('source','TX')
                                        ->where('trantype','BS')
                                        ->first();

                        DB::table('finance.gltran')
                            ->insert([
                                'compcode' => session('compcode'),
                                'adduser' => session('username'),
                                'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                                'auditno' => $billsum_obj->auditno,
                                'lineno_' => 1,
                                'source' => 'OE', //kalau stock 'IV', lain dari stock 'DO'
                                'trantype' => 'TX',
                                'reference' => $invno,
                                'description' => $billsum_obj->chggroup, 
                                'postdate' => Carbon::now("Asia/Kuala_Lumpur"),
                                'year' => $yearperiod->year,
                                'period' => $yearperiod->period,
                                'drcostcode' => $sysparam->pvalue1,
                                'dracc' => $sysparam->pvalue2,
                                'crcostcode' => $sysparam_tx->pvalue1,
                                'cracc' => $sysparam_tx->pvalue2,
                                'amount' => $billsum_obj->taxamt
                            ]);

                        $this->init_glmastdtl(
                            $sysparam->pvalue1,//drcostcode
                            $sysparam->pvalue2,//dracc
                            $sysparam_tx->pvalue1,//crcostcode
                            $sysparam_tx->pvalue2,//cracc
                            $yearperiod,
                            $billsum_obj->taxamt
                        );
                    }
                    
                    if(!empty(floatval($billsum_obj->discamt))){
                        $sysparam_dis = DB::table('sysdb.sysparam')
                                        ->where('compcode',session('compcode'))
                                        ->where('source','OE')
                                        ->where('trantype','DIS')
                                        ->first();

                        DB::table('finance.gltran')
                            ->insert([
                                'compcode' => session('compcode'),
                                'adduser' => session('username'),
                                'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                                'auditno' => $billsum_obj->auditno,
                                'lineno_' => 1,
                                'source' => 'OE', //kalau stock 'IV', lain dari stock 'DO'
                                'trantype' => 'DIS',
                                'reference' => $invno,
                                'description' => $billsum_obj->chggroup, 
                                'postdate' => Carbon::now("Asia/Kuala_Lumpur"),
                                'year' => $yearperiod->year,
                                'period' => $yearperiod->period,
                                'drcostcode' => $sysparam->pvalue1,
                                'dracc' => $sysparam->pvalue2,
                                'crcostcode' => $dept->costcode,
                                'cracc' => $sysparam_dis->pvalue1,
                                'amount' => -$billsum_obj->discamt
                            ]);

                        $this->init_glmastdtl(
                            $sysparam->pvalue1,//drcostcode
                            $sysparam->pvalue2,//dracc
                            $dept->costcode,//crcostcode
                            $sysparam_dis->pvalue1,//cracc
                            $yearperiod,
                            -$billsum_obj->discamt
                        );
                    }
                }

                if(!empty($dbacthdr->quoteno)){
                    $qo_hd = DB::table('finance.salehdr')
                                ->where('compcode',session('compcode'))
                                ->where('source','SL')
                                ->where('trantype','RECNO')
                                ->where('quoteno',$dbacthdr->quoteno)
                                ->update([
                                    'recstatus' => $qo_dt_recstatus
                                ]);
                }    

                DB::table('finance.queueso')
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$dbacthdr->auditno)
                    ->update([
                        'AuthorisedID' => session('username'),
                        'deptcode' => 'ALL',
                        'recstatus' => 'POSTED',
                        'trantype' => 'DONE',
                        'adduser' => session('username'),
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur")
                    ]);
                
                DB::table("debtor.billsum")
                    ->where('compcode',session('compcode'))
                    ->where('source','=',$dbacthdr->source)
                    ->where('trantype','=',$dbacthdr->trantype)
                    ->where('billno','=',$dbacthdr->auditno)
                    ->update([
                        'invno' => $invno,
                        'recstatus' => 'POSTED',
                    ]);
                
                DB::table("debtor.dbacthdr")
                    ->where('compcode',session('compcode'))
                    ->where('idno','=',$value)
                    ->update([
                        'invno' => $invno,
                        'recstatus' => 'POSTED',
                        'posteddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'approvedby' => session('username'),
                        'approveddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                        // 'amount' => accumalated amount (billsum.amt-billsum.discamt+billsum.taxamt)
                        // 'outamount' => accumalated amount (billsum.amt-billsum.discamt+billsum.taxamt)
                    ]);

                $debtormast = DB::table("debtor.debtormast")
                                ->where('compcode',session('compcode'))
                                ->where('debtorcode',$dbacthdr->payercode)
                                ->first();

                DB::table('finance.gltran')
                    ->insert([
                        'compcode' => session('compcode'),
                        'adduser' => session('username'),
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'auditno' => $invno,
                        'lineno_' => 1,
                        'source' => 'PB', //kalau stock 'IV', lain dari stock 'DO'
                        'trantype' => 'IN',
                        'reference' => $invno,
                        'description' => $dbacthdr->remark, 
                        'postdate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'year' => $yearperiod->year,
                        'period' => $yearperiod->period,
                        'drcostcode' => $debtormast->actdebccode,
                        'dracc' => $debtormast->actdebglacc,
                        'crcostcode' => $sysparam->pvalue1,
                        'cracc' => $sysparam->pvalue2,
                        'amount' => $dbacthdr->amount
                    ]);

                $this->init_glmastdtl(
                            $debtormast->actdebccode,//drcostcode
                            $debtormast->actdebglacc,//dracc
                            $sysparam->pvalue1,//crcostcode
                            $sysparam->pvalue2,//cracc
                            $yearperiod,
                            $dbacthdr->amount
                        );
                
                
            }
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            
            return response($e->getMessage(), 500);
        }
    }

    public function recomputed(Request $request){
        
        DB::beginTransaction();
        
        try{

            foreach ($request->idno_array as $value){

                $dbacthdr = DB::table("debtor.dbacthdr")
                        ->where('compcode',session('compcode'))
                        ->where('idno','=',$value)
                        ->first();

                $totalAmount = DB::table('debtor.billsum')
                        ->where('compcode','=',session('compcode'))
                        ->where('source','=','PB')
                        ->where('trantype','=','IN')
                        ->where('billno','=',$dbacthdr->auditno)
                        ->where('recstatus','!=','DELETE')
                        ->sum('totamount');
                
                DB::table("debtor.dbacthdr")
                    ->where('compcode',session('compcode'))
                    ->where('idno','=',$value)
                    ->update([
                        'amount' => $totalAmount,
                        'outamount' => $totalAmount
                    ]);

                if($dbacthdr->recstatus != 'POSTED'){
                    throw new \Exception("Dbacthdr recstatus is not POSTED",500);
                }

                $balance_ = floatval($dbacthdr->amount) - floatval($dbacthdr->outamount);

                if($balance_ != 0){
                    throw new \Exception("Amount and outamount need to be same",500);
                }

                $invno = $dbacthdr->invno;

                DB::table("hisdb.chargetrx")
                        ->where('compcode',session('compcode'))
                        ->where('invno',$invno)
                        ->delete();

                DB::table("hisdb.billdet")
                        ->where('compcode',session('compcode'))
                        ->where('invno',$invno)
                        ->delete();
                
                $billsum = DB::table("debtor.billsum")
                            ->where('compcode',session('compcode'))
                            ->where('source','=',$dbacthdr->source)
                            ->where('trantype','=',$dbacthdr->trantype)
                            ->where('billno','=',$dbacthdr->auditno)
                            ->get();
                
                foreach ($billsum as $billsum_obj){

                    $chgmast = DB::table("hisdb.chgmast")
                            ->where('compcode','=',session('compcode'))
                            ->where('chgcode','=',$billsum_obj->chggroup)
                            ->where('uom','=',$billsum_obj->uom)
                            ->first();
                    
                    // $updinv = ($chgmast->invflag == '1')? 1 : 0;

                    $product = DB::table('material.product')
                                    ->where('compcode','=',session('compcode'))
                                    ->where('uomcode','=',$billsum_obj->uom)
                                    ->where('itemcode','=',$billsum_obj->chggroup);
                    
                    if($product->exists() && $chgmast->invflag == 1){
                        // $stockloc = DB::table('material.stockloc')
                        //         ->where('compcode','=',session('compcode'))
                        //         ->where('uomcode','=',$billsum_obj->uom)
                        //         ->where('itemcode','=',$billsum_obj->chggroup)
                        //         ->where('deptcode','=',$dbacthdr->deptcode)
                        //         ->where('year','=',Carbon::now("Asia/Kuala_Lumpur")->year);
                        
                        // if($stockloc->exists()){
                        //     $stockloc = $stockloc->first();
                        // }else{
                        //     throw new \Exception("Stockloc not exists for item: ".$billsum_obj->chggroup." dept: ".$dbacthdr->deptcode." uom: ".$billsum_obj->uom,500);
                        // }
                        
                        // $ivdspdt = DB::table('material.ivdspdt')
                        //     ->where('compcode','=',session('compcode'))
                        //     ->where('recno','=',$billsum_obj->auditno);
                        
                        // if($ivdspdt->exists()){
                        //     $this->updivdspdt($billsum_obj,$dbacthdr);
                        //     $this->updgltran($ivdspdt->first()->idno,$dbacthdr);
                        // }else{
                        //     $ivdspdt_idno = $this->crtivdspdt($billsum_obj,$dbacthdr);
                        //     $this->crtgltran($ivdspdt_idno,$dbacthdr);
                        // }
                        $this->delivdspdt($billsum_obj,$dbacthdr);
                    }
                    
                    //gltran
                    $yearperiod = $this->getyearperiod($dbacthdr->posteddate);
                    $chgmast = DB::table('hisdb.chgmast')
                                ->where('compcode',session('compcode'))
                                ->where('chgcode',$billsum_obj->chggroup)
                                ->first();

                    $chgtype = DB::table('hisdb.chgtype')
                                ->where('compcode',session('compcode'))
                                ->where('chgtype',$chgmast->chgtype)
                                ->first();

                    $dept = DB::table('sysdb.department')
                                ->where('compcode',session('compcode'))
                                ->where('deptcode',$dbacthdr->deptcode)
                                ->first();

                    $sysparam = DB::table('sysdb.sysparam')
                                ->where('compcode',session('compcode'))
                                ->where('source','AR')
                                ->where('trantype','AD')
                                ->first();

                    $this->init_glmastdtl_del(
                            $sysparam->pvalue1,//drcostcode
                            $sysparam->pvalue2,//dracc
                            $dept->costcode,//crcostcode
                            $chgtype->opacccode,//cracc
                            $yearperiod,
                            $billsum_obj->amount
                    );

                    DB::table('finance.gltran')
                            ->where('compcode',session('compcode'))
                            ->where('source','OE')
                            ->where('trantype','IN')
                            ->where('auditno',$billsum_obj->auditno)
                            ->delete();
                }
                
                DB::table("debtor.billsum")
                    ->where('compcode',session('compcode'))
                    ->where('source','=',$dbacthdr->source)
                    ->where('trantype','=',$dbacthdr->trantype)
                    ->where('billno','=',$dbacthdr->auditno)
                    ->update([
                        // 'invno' => null,
                        'recstatus' => 'OPEN',
                    ]);
                
                DB::table("debtor.dbacthdr")
                    ->where('compcode',session('compcode'))
                    ->where('idno','=',$value)
                    ->update([
                        // 'invno' => null,
                        'recstatus' => 'RECOMPUTED',
                        'posteddate' => null,
                        'approvedby' => null,
                        'approveddate' => null,
                        // 'amount' => accumalated amount (billsum.amt-billsum.discamt+billsum.taxamt)
                        // 'outamount' => accumalated amount (billsum.amt-billsum.discamt+billsum.taxamt)
                    ]);

                $debtormast = DB::table("debtor.debtormast")
                                ->where('compcode',session('compcode'))
                                ->where('debtorcode',$dbacthdr->payercode)
                                ->first();

                $this->init_glmastdtl_del(
                            $debtormast->actdebccode,//drcostcode
                            $debtormast->actdebglacc,//dracc
                            $sysparam->pvalue1,//crcostcode
                            $sysparam->pvalue2,//cracc
                            $yearperiod,
                            $dbacthdr->amount
                        );

                DB::table('finance.gltran')
                    ->where('compcode',session('compcode'))
                    ->where('source','PB')
                    ->where('trantype','IN')
                    ->where('auditno',$invno)
                    ->delete();
            }
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            
            return response($e->getMessage(), 500);
        }
    }

    public function init_glmastdtl($dbcc,$dbacc,$crcc,$cracc,$yearperiod,$amount){
        //2. check glmastdtl utk debit, kalu ada update kalu xde create
        $gltranAmount =  $this->isGltranExist($dbcc,$dbacc,$yearperiod->year,$yearperiod->period);

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

        //3. check glmastdtl utk credit pulak, kalu ada update kalu xde create
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

    public function init_glmastdtl_del($dbcc,$dbacc,$crcc,$cracc,$yearperiod,$amount){
        //2. check glmastdtl utk debit, kalu ada update kalu xde create
        $gltranAmount =  $this->isGltranExist($dbcc,$dbacc,$yearperiod->year,$yearperiod->period);

        if($gltranAmount!==false){
            DB::table('finance.glmasdtl')
                ->where('compcode','=',session('compcode'))
                ->where('costcode','=',$dbcc)
                ->where('glaccount','=',$dbacc)
                ->where('year','=',$yearperiod->year)
                ->update([
                    'upduser' => session('username'),
                    'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'actamount'.$yearperiod->period => floatval($amount) - $gltranAmount,
                    'recstatus' => 'ACTIVE'
                ]);
        }else{
            DB::table('finance.glmasdtl')
                ->insert([
                    'compcode' => session('compcode'),
                    'costcode' => $dbcc,
                    'glaccount' => $dbacc,
                    'year' => $yearperiod->year,
                    'actamount'.$yearperiod->period => - floatval($amount),
                    'adduser' => session('username'),
                    'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'recstatus' => 'ACTIVE'
                ]);
        }

        //3. check glmastdtl utk credit pulak, kalu ada update kalu xde create
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
                    'actamount'.$yearperiod->period => $gltranAmount + floatval($amount),
                    'recstatus' => 'ACTIVE'
                ]);
        }else{
            DB::table('finance.glmasdtl')
                ->insert([
                    'compcode' => session('compcode'),
                    'costcode' => $crcc,
                    'glaccount' => $cracc,
                    'year' => $yearperiod->year,
                    'actamount'.$yearperiod->period => +floatval($amount),
                    'adduser' => session('username'),
                    'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'recstatus' => 'ACTIVE'
                ]);
        }
    }
    
    public function reopen(Request $request){

        DB::beginTransaction();

        try{

            foreach ($request->idno_array as $value){
                
                $dbacthdr = DB::table("debtor.dbacthdr")
                            ->where('compcode',session('compcode'))
                            ->where('idno','=',$value)
                            ->first();

                if($dbacthdr->recstatus != 'CANCELLED'){
                    continue;
                }

                DB::table("debtor.dbacthdr")
                    ->where('compcode',session('compcode'))
                    ->where('idno','=',$value)
                    ->update([
                        'recstatus' => 'OPEN',
                        'preparedby' => null,
                        'prepareddate' => null,
                    ]);
                
                DB::table("debtor.billsum")
                    ->where('compcode',session('compcode'))
                    ->where('source','=',$dbacthdr->source)
                    ->where('trantype','=',$dbacthdr->trantype)
                    ->where('billno','=',$dbacthdr->auditno)
                    ->update([
                        'recstatus' => 'OPEN',
                    ]);

                DB::table('finance.queueso')
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$dbacthdr->auditno)
                    ->delete();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function reject(Request $request){
        DB::beginTransaction();

        try{
           
            foreach ($request->idno_array as $value){

                $dbacthdr = DB::table("debtor.dbacthdr")
                            ->where('compcode',session('compcode'))
                            ->where('idno','=',$value)
                            ->first();

                if($dbacthdr->recstatus != 'PREPARED'){
                    continue;
                }

                $dbacthdr_update = [
                    'recstatus' => 'CANCELLED',
                    'cancelby' => session('username'),
                    'canceldate' => Carbon::now("Asia/Kuala_Lumpur"),
                ];

                if(!empty($request->remarks)){
                    $dbacthdr_update['cancelled_remark'] = $request->remarks;
                }

                DB::table('debtor.dbacthdr')
                    ->where('compcode',session('compcode'))
                    ->where('idno','=',$value)
                    ->update($dbacthdr_update);
                
                DB::table("debtor.billsum")
                    ->where('compcode',session('compcode'))
                    ->where('source','=',$dbacthdr->source)
                    ->where('trantype','=',$dbacthdr->trantype)
                    ->where('billno','=',$dbacthdr->auditno)
                    ->update([
                        'recstatus' => 'CANCELLED',
                    ]);

                DB::table('finance.queueso')
                    ->update([
                        'compcode' => session('compcode'),
                        'recno' => $dbacthdr->auditno,
                        'AuthorisedID' => $dbacthdr->adduser,
                        'deptcode' => 'ALL',
                        'recstatus' => 'CANCELLED',
                        'trantype' => 'REOPEN',
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

    public function cancel(Request $request){
        DB::beginTransaction();

        try{
            
            foreach ($request->idno_array as $value){
                
                $dbacthdr = DB::table("debtor.dbacthdr")
                            ->where('compcode',session('compcode'))
                            ->where('idno','=',$value)
                            ->first();

                if(!in_array($dbacthdr->recstatus, ['OPEN','PREPARED'])){
                    throw new \Exception("Cant cancel this document, status is not OPEN or PREPARED");
                }

                $dbacthdr_update = [
                    'recstatus' => 'CANCELLED',
                    'cancelby' => session('username'),
                    'canceldate' => Carbon::now("Asia/Kuala_Lumpur"),
                ];

                DB::table('debtor.dbacthdr')
                    ->where('compcode',session('compcode'))
                    ->where('idno','=',$value)
                    ->update($dbacthdr_update);

                DB::table("debtor.billsum")
                    ->where('source','=',$dbacthdr->source)
                    ->where('trantype','=',$dbacthdr->trantype)
                    ->where('auditno','=',$dbacthdr->auditno)
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

        try{

            foreach ($request->idno_array as $value){

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
            
                //$this->sendemail($data);

            }

           
            DB::commit();
        
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function verify(Request $request){
         DB::beginTransaction();

        try{

            foreach ($request->idno_array as $value){

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

                //$this->sendemail($data);

            }

            DB::commit();
        
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function approved(Request $request){
         DB::beginTransaction();

        try{

            foreach ($request->idno_array as $value){

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

    public function get_einvoiceQR($invno){
        $invno = '5214968';
        $url = 'http://175.143.1.33:8080/einvoice/einvoice_get_qrcode?invno='.$invno.'&compcode=medicare';

        $client = new \GuzzleHttp\Client();

        $response = $client->request('GET', $url, [
          'headers' => [
            'accept' => 'application/json',
          ],
        ]);

        $response_ = $response->getBody()->getContents();
        $myresponse = json_decode($response_);

        return $myresponse;
    }

    public function showpdf(Request $request){
        $idno = $request->idno;
        if(!$idno){
            abort(404);
        }

        if(!empty($request->idno_billsum)){
            return $this->showpdf_disp($request);
        }

        $dbacthdr = DB::table('debtor.dbacthdr as h')
            ->select('h.source','h.trantype','h.epistype','h.compcode', 'h.idno', 'h.auditno', 'h.lineno_', 'h.amount', 'h.outamount', 'h.recstatus', 'h.debtortype', 'h.debtorcode', 'h.mrn', 'h.invno', 'h.ponum', 'h.podate', 'h.deptcode', 'h.entrydate','h.hdrtype','h.LHDNStatus',
            'm.debtorcode as debt_debtcode', 'm.name as debt_name', 'm.address1 as cust_address1', 'm.address2 as cust_address2', 'm.address3 as cust_address3', 'm.address4 as cust_address4', 'm.creditterm as crterm','m.billtype as billtype','dt.debtortycode as dt_debtortycode', 'dt.description as dt_description','bt.description as bt_desc','pm.Name as pm_name','pm.address1 as pm_address1','pm.address2 as pm_address2','pm.address3 as pm_address3','pm.postcode as pm_postcode','h.doctorcode','dc.doctorname','h.remark','m.debtortype as m_debtortype')
            ->leftJoin('debtor.debtormast as m', function($join) use ($request){
                $join = $join->on("m.debtorcode", '=', 'h.debtorcode');    
                $join = $join->where("m.compcode", '=', session('compcode'));
            })
            ->leftJoin('debtor.debtortype as dt', function($join) use ($request){
                $join = $join->on("dt.debtortycode", '=', 'm.debtortype');    
                $join = $join->where("dt.compcode", '=', session('compcode'));
            })
            ->leftJoin('hisdb.billtymst as bt', function($join) use ($request){
                $join = $join->on("bt.billtype", '=', 'h.hdrtype');    
                $join = $join->where("bt.compcode", '=', session('compcode'));
            })
            ->leftJoin('hisdb.pat_mast as pm', function($join) use ($request){
                $join = $join->on("pm.newmrn", '=', 'h.mrn');    
                $join = $join->where("pm.compcode", '=', session('compcode'));
            })
            ->leftJoin('hisdb.doctor as dc', function($join) use ($request){
                $join = $join->on("dc.doctorcode", '=', 'h.doctorcode');    
                $join = $join->where("dc.compcode", '=', session('compcode'));
            })
            ->where('h.idno','=',$idno)
            // ->where('h.mrn','=','0')
            // ->where('h.compcode','=',session('compcode'))
            ->first();

        if($dbacthdr->LHDNStatus == 'ACCEPTED'){
            $einvoiceQR = $this->get_einvoiceQR($dbacthdr->invno);
        }else{
            $einvoiceQR = null;
        }

        if($dbacthdr->recstatus == 'CANCELLED'){
            abort(403, 'INVOICE CANCELLED');
        }

        $billsum = DB::table('debtor.billsum AS b')
            ->select('b.compcode', 'b.idno','b.invno', 'b.mrn', 'b.billno', 'b.lineno_', 'b.chgclass', 'b.chggroup', 'b.description', 'b.uom', 'b.quantity', 'b.amount', 'b.outamt', 'b.taxamt', 'b.unitprice', 'b.taxcode', 'b.discamt', 'b.recstatus',
            'u.description as uom_desc', 
            'd.debtorcode as debt_debtcode','d.name as debt_name', 
            'm.description as chgmast_desc','iv.expdate','iv.batchno')
            ->leftJoin('hisdb.chgmast as m', function($join) use ($request){
                $join = $join->on('b.chggroup', '=', 'm.chgcode');
                $join = $join->on('b.uom', '=', 'm.uom');
                $join = $join->where('m.compcode', '=', session('compcode'));
                // $join = $join->where('m.unit', '=', session('unit'));
            })
            ->leftJoin('material.uom as u', function($join) use ($request){
                $join = $join->on('b.uom', '=', 'u.uomcode');
                $join = $join->where('u.compcode', '=', session('compcode'));
            })
            //->leftJoin('material.productmaster as p', 'b.description', '=', 'p.description')
            // ->leftJoin('material.uom as u', 'b.uom', '=', 'u.uomcode')
            // ->leftJoin('debtor.debtormast as d', 'b.debtorcode', '=', 'd.debtorcode')
            ->leftJoin('debtor.debtormast as d', function($join) use ($request){
                $join = $join->on('b.debtorcode', '=', 'd.debtorcode');
                $join = $join->where('d.compcode', '=', session('compcode'));
            })
            ->leftJoin('material.ivdspdt as iv', function($join) use ($request){
                $join = $join->on('iv.recno', '=', 'b.auditno');
                $join = $join->where('iv.lineno_', '=', '1');
                $join = $join->on('iv.itemcode', '=', 'b.chggroup');
                $join = $join->on('iv.uomcode', '=', 'b.uom');
                $join = $join->where('iv.compcode', '=', session('compcode'));
            })
            ->where('b.source','=',$dbacthdr->source)
            ->where('b.trantype','=',$dbacthdr->trantype)
            ->where('b.billno','=',$dbacthdr->auditno)
            ->where('b.compcode','=',session('compcode'))
            ->get();

        foreach ($billsum as $obj) {
            $obj->chgmast_desc = str_replace('`', '', $obj->chgmast_desc);
        }

        // $chgmast = DB::table('debtor.billsum AS b', 'hisdb.chgmast as m')
        //     ->select('b.compcode', 'b.idno','b.invno', 'b.mrn', 'b.billno', 'b.lineno_', 'b.chgclass', 'b.chggroup', 'b.description', 'b.uom', 'b.quantity', 'b.amount', 'b.outamt', 'b.taxamt', 'b.unitprice', 'b.taxcode', 'b.discamt', 'b.recstatus', 'm.description as chgmast_desc')
        //     ->leftJoin('hisdb.chgmast as m', 'b.description', '=', 'm.description')
        //     ->where('b.source','=',$dbacthdr->source)
        //     ->where('b.trantrype','=',$dbacthdr->trantrype)
        //     ->where('b.billno','=',$dbacthdr->auditno)
        //     ->get();
        
        if($dbacthdr->recstatus == "OPEN"){
            if(strtoupper(session('unit')) == "W'HOUSE")
            $title = "DELIVERY ORDER";
        }else{
            $title = "INVOICE";
        }

        $company = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();

        $sum_billsum = $billsum->sum('amount');

        $paid = $dbacthdr->amount - $dbacthdr->outamount;

        $totalamount = $dbacthdr->outamount;

        if(strtoupper($dbacthdr->m_debtortype) == 'PR' || strtoupper($dbacthdr->m_debtortype) == 'PT' ){
            $pdeposit = DB::table('debtor.dbacthdr')
                            ->where('compcode',session('compcode'))
                            ->where('source','PB')
                            ->where('trantype','RD')
                            ->where('debtorcode',$dbacthdr->debtorcode)
                            ->where('recstatus','POSTED')
                            ->sum('outamount');

            $paid = $paid + $pdeposit;
            $totalamount = $dbacthdr->amount - $paid;
        }

        $totamount_expld = explode(".", (float)$totalamount);

        $totamt_bm_rm = $this->convertNumberToWordBM($totamount_expld[0])." RINGGIT ";
        $totamt_bm = $totamt_bm_rm." SAHAJA";

        if(count($totamount_expld) > 1){
            $totamt_bm_sen = $this->convertNumberToWordBM($totamount_expld[1])." SEN";
            $totamt_bm = $totamt_bm_rm.$totamt_bm_sen." SAHAJA";
        }
        
        // $pdf = PDF::loadView('finance.SalesOrder.SalesOrder_pdf',compact('dbacthdr','billsum','totamt_bm','company', 'title'));
    
        // return $pdf->stream();
        
        return view('finance.SalesOrder.SalesOrder_pdfmake',compact('dbacthdr','billsum','totamt_bm','company', 'title','sum_billsum','paid','totalamount','einvoiceQR'));
    }

    //function sendmeail($data) -- nak kena ada atau tak

    function showpdf_disp($request){
        $idno_billsum = $request->idno_billsum;
        $idno = $request->idno;

        $dbacthdr = DB::table('debtor.dbacthdr as h')
            ->select('h.source','h.trantype','h.epistype','h.compcode', 'h.idno', 'h.auditno', 'h.lineno_', 'h.amount', 'h.outamount', 'h.recstatus', 'h.debtortype', 'h.debtorcode', 'h.mrn', 'h.invno', 'h.ponum', 'h.podate', 'h.deptcode', 'h.entrydate','h.hdrtype',
            'm.debtorcode as debt_debtcode', 'm.name as debt_name', 'm.address1 as cust_address1', 'm.address2 as cust_address2', 'm.address3 as cust_address3', 'm.address4 as cust_address4', 'm.creditterm as crterm','m.billtype as billtype','dt.debtortycode as dt_debtortycode', 'dt.description as dt_description','bt.description as bt_desc','pm.Name as pm_name','pm.address1 as pm_address1','pm.address2 as pm_address2','pm.address3 as pm_address3','pm.postcode as pm_postcode','h.doctorcode','dc.doctorname')
            ->leftJoin('debtor.debtormast as m', function($join) use ($request){
                $join = $join->on("m.debtorcode", '=', 'h.debtorcode');    
                $join = $join->where("m.compcode", '=', session('compcode'));
            })
            ->leftJoin('debtor.debtortype as dt', function($join) use ($request){
                $join = $join->on("dt.debtortycode", '=', 'm.debtortype');    
                $join = $join->where("dt.compcode", '=', session('compcode'));
            })
            ->leftJoin('hisdb.billtymst as bt', function($join) use ($request){
                $join = $join->on("bt.billtype", '=', 'h.hdrtype');    
                $join = $join->where("bt.compcode", '=', session('compcode'));
            })
            ->leftJoin('hisdb.pat_mast as pm', function($join) use ($request){
                $join = $join->on("pm.newmrn", '=', 'h.mrn');    
                $join = $join->where("pm.compcode", '=', session('compcode'));
            })
            ->leftJoin('hisdb.doctor as dc', function($join) use ($request){
                $join = $join->on("dc.doctorcode", '=', 'h.doctorcode');    
                $join = $join->where("dc.compcode", '=', session('compcode'));
            })
            ->where('h.idno','=',$idno)
            // ->where('h.mrn','=','0')
            ->where('h.compcode','=',session('compcode'))
            ->first();

        $billsum = DB::table('debtor.billsum AS b')
                        ->where('b.idno','=',$idno_billsum)
                        ->first();

        $invcode = $billsum->invcode;

        $billsum = DB::table('hisdb.billdet AS b')
            ->select('b.compcode', 'b.idno','b.invno', 'b.mrn', 'b.billno', 'b.lineno_', 'b.chgcode as chggroup', 'b.uom', 'b.quantity', 'b.amount', 'b.taxamount as taxamt', 'b.unitprce as unitprice', 'b.taxcode', 'b.discamt', 'b.recstatus',
            'u.description as uom_desc', 
            'm.description as chgmast_desc','iv.expdate','iv.batchno')
            ->leftJoin('hisdb.chgmast as m', function($join) use ($request){
                $join = $join->on('b.chgcode', '=', 'm.chgcode');
                $join = $join->on('b.uom', '=', 'm.uom');
                $join = $join->where('m.compcode', '=', session('compcode'));
                // $join = $join->where('m.unit', '=', session('unit'));
            })
            ->leftJoin('material.uom as u', function($join) use ($request){
                $join = $join->on('b.uom', '=', 'u.uomcode');
                $join = $join->where('u.compcode', '=', session('compcode'));
            })
            ->leftJoin('material.ivdspdt as iv', function($join) use ($request){
                $join = $join->on('iv.recno', '=', 'b.auditno');
                $join = $join->where('iv.lineno_', '=', '1');
                $join = $join->on('iv.itemcode', '=', 'b.chggroup');
                $join = $join->on('iv.uomcode', '=', 'b.uom');
                $join = $join->where('iv.compcode', '=', session('compcode'));
            })
            // ->where('b.source','=',$dbacthdr->source)
            // ->where('b.trantype','=',$dbacthdr->trantype)
            ->where('b.invcode','=',$invcode)
            ->where('b.billno','=',$dbacthdr->auditno)
            ->where('b.compcode','=',session('compcode'))
            ->get();

        foreach ($billsum as $obj) {
            $obj->chgmast_desc = str_replace('`', '', $obj->chgmast_desc);
        }

        // $chgmast = DB::table('debtor.billsum AS b', 'hisdb.chgmast as m')
        //     ->select('b.compcode', 'b.idno','b.invno', 'b.mrn', 'b.billno', 'b.lineno_', 'b.chgclass', 'b.chggroup', 'b.description', 'b.uom', 'b.quantity', 'b.amount', 'b.outamt', 'b.taxamt', 'b.unitprice', 'b.taxcode', 'b.discamt', 'b.recstatus', 'm.description as chgmast_desc')
        //     ->leftJoin('hisdb.chgmast as m', 'b.description', '=', 'm.description')
        //     ->where('b.source','=',$dbacthdr->source)
        //     ->where('b.trantrype','=',$dbacthdr->trantrype)
        //     ->where('b.billno','=',$dbacthdr->auditno)
        //     ->get();
        
        if($dbacthdr->recstatus == "OPEN"){
            $title = "DELIVERY ORDER";
        }else{
            $title = "INVOICE";
        }

        $company = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();

        $totamount = $billsum->sum('amount');
        $dbacthdr->amount = $totamount;

        $totamount_expld = explode(".", (float)$dbacthdr->amount);

        $totamt_bm_rm = $this->convertNumberToWordBM($totamount_expld[0])." RINGGIT ";
        $totamt_bm = $totamt_bm_rm." SAHAJA";

        if(count($totamount_expld) > 1){
            $totamt_bm_sen = $this->convertNumberToWordBM($totamount_expld[1])." SEN";
            $totamt_bm = $totamt_bm_rm.$totamt_bm_sen." SAHAJA";
        }
        
        // $pdf = PDF::loadView('finance.SalesOrder.SalesOrder_pdf',compact('dbacthdr','billsum','totamt_bm','company', 'title'));
    
        // return $pdf->stream();
        
        return view('finance.SalesOrder.SalesOrder_pdfmake',compact('dbacthdr','billsum','totamt_bm','company', 'title'));
    }

    public function showpdf_do(Request $request){
        $idno = $request->idno;
        if(!$idno){
            abort(404);
        }

        $dbacthdr = DB::table('debtor.dbacthdr as h')
            ->select('h.source','h.trantype','h.epistype','h.compcode', 'h.idno', 'h.auditno', 'h.lineno_', 'h.amount', 'h.outamount', 'h.recstatus', 'h.debtortype', 'h.debtorcode', 'h.mrn', 'h.invno', 'h.ponum', 'h.podate', 'h.deptcode', 'h.entrydate','h.hdrtype',
            'm.debtorcode as debt_debtcode', 'm.name as debt_name', 'm.address1 as cust_address1', 'm.address2 as cust_address2', 'm.address3 as cust_address3', 'm.address4 as cust_address4', 'm.creditterm as crterm','m.billtype as billtype','dt.debtortycode as dt_debtortycode', 'dt.description as dt_description','bt.description as bt_desc','pm.Name as pm_name','pm.address1 as pm_address1','pm.address2 as pm_address2','pm.address3 as pm_address3','pm.postcode as pm_postcode','h.doctorcode','dc.doctorname','h.remark','h.adduser')
            ->leftJoin('debtor.debtormast as m', function($join) use ($request){
                $join = $join->on("m.debtorcode", '=', 'h.debtorcode');    
                $join = $join->where("m.compcode", '=', session('compcode'));
            })
            ->leftJoin('debtor.debtortype as dt', function($join) use ($request){
                $join = $join->on("dt.debtortycode", '=', 'm.debtortype');    
                $join = $join->where("dt.compcode", '=', session('compcode'));
            })
            ->leftJoin('hisdb.billtymst as bt', function($join) use ($request){
                $join = $join->on("bt.billtype", '=', 'h.hdrtype');    
                $join = $join->where("bt.compcode", '=', session('compcode'));
            })
            ->leftJoin('hisdb.pat_mast as pm', function($join) use ($request){
                $join = $join->on("pm.newmrn", '=', 'h.mrn');    
                $join = $join->where("pm.compcode", '=', session('compcode'));
            })
            ->leftJoin('hisdb.doctor as dc', function($join) use ($request){
                $join = $join->on("dc.doctorcode", '=', 'h.doctorcode');    
                $join = $join->where("dc.compcode", '=', session('compcode'));
            })
            ->where('h.idno','=',$idno)
            // ->where('h.mrn','=','0')
            // ->where('h.compcode','=',session('compcode'))
            ->first();

        $delordhd = [
              'idno' => $dbacthdr->idno,
              'compcode' => $dbacthdr->compcode,
              'recno' => $dbacthdr->auditno,
              'prdept' => $dbacthdr->deptcode,
              'trantype' => $dbacthdr->trantype,
              'docno' => $dbacthdr->auditno,
              'delordno' => $dbacthdr->auditno,
              'invoiceno' => $dbacthdr->invno,
              'suppcode' => $dbacthdr->debtorcode,
              'deldept' => $dbacthdr->deptcode,
              'totamount' => $dbacthdr->amount,
              'deliverydate' => $dbacthdr->entrydate,
              'trandate' => $dbacthdr->entrydate,
              'respersonid' => $dbacthdr->adduser,
              'checkpersonid' => $dbacthdr->adduser,
              'checkdate' => $dbacthdr->entrydate,
              'postedby' => $dbacthdr->adduser,
              'recstatus' => $dbacthdr->recstatus,
              'remarks' => $dbacthdr->remark,
              'postedby_name' => $dbacthdr->adduser,
              'srcdocno ' => 0,
        ];

        $delordhd = (Object)$delordhd;

        // dd($delordhd);

        if($dbacthdr->recstatus == 'CANCELLED'){
            abort(403, 'INVOICE CANCELLED');
        }

        $billsum = DB::table('debtor.billsum AS b')
            ->select('b.compcode', 'b.idno','b.invno', 'b.mrn', 'b.billno', 'b.lineno_', 'b.chgclass', 'b.chggroup', 'b.description', 'b.uom', 'b.quantity', 'b.amount', 'b.outamt', 'b.taxamt', 'b.unitprice', 'b.taxcode', 'b.discamt', 'b.recstatus',
            'u.description as uom_desc', 'b.auditno',
            'd.debtorcode as debt_debtcode','d.name as debt_name', 
            'm.description as chgmast_desc','iv.expdate','iv.batchno')
            ->leftJoin('hisdb.chgmast as m', function($join) use ($request){
                $join = $join->on('b.chggroup', '=', 'm.chgcode');
                $join = $join->on('b.uom', '=', 'm.uom');
                $join = $join->where('m.compcode', '=', session('compcode'));
                // $join = $join->where('m.unit', '=', session('unit'));
            })
            ->leftJoin('material.uom as u', function($join) use ($request){
                $join = $join->on('b.uom', '=', 'u.uomcode');
                $join = $join->where('u.compcode', '=', session('compcode'));
            })
            //->leftJoin('material.productmaster as p', 'b.description', '=', 'p.description')
            // ->leftJoin('material.uom as u', 'b.uom', '=', 'u.uomcode')
            // ->leftJoin('debtor.debtormast as d', 'b.debtorcode', '=', 'd.debtorcode')
            ->leftJoin('debtor.debtormast as d', function($join) use ($request){
                $join = $join->on('b.debtorcode', '=', 'd.debtorcode');
                $join = $join->where('d.compcode', '=', session('compcode'));
            })
            ->leftJoin('material.ivdspdt as iv', function($join) use ($request){
                $join = $join->on('iv.recno', '=', 'b.auditno');
                $join = $join->where('iv.lineno_', '=', '1');
                $join = $join->on('iv.itemcode', '=', 'b.chggroup');
                $join = $join->on('iv.uomcode', '=', 'b.uom');
                $join = $join->where('iv.compcode', '=', session('compcode'));
            })
            ->where('b.source','=',$dbacthdr->source)
            ->where('b.trantype','=',$dbacthdr->trantype)
            ->where('b.billno','=',$dbacthdr->auditno)
            ->where('b.compcode','=',session('compcode'))
            ->get();

        foreach ($billsum as $obj) {
            $obj->compcode = $obj->compcode;
            $obj->recno = $obj->auditno;
            $obj->lineno_ = $obj->lineno_;
            // $obj->pricecode = $obj->;
            $obj->itemcode = $obj->chggroup;    
            $obj->description = $obj->description;
            $obj->uomcode = $obj->uom;
            $obj->pouom = $obj->uom;
            $obj->qtyorder = $obj->quantity;
            $obj->qtydelivered = $obj->quantity;
            $obj->unitprice = $obj->unitprice;
            $obj->taxcode = $obj->taxcode;
            $obj->amtdisc = '-';
            // $obj->tot_gst = $obj->;
            // $obj->netunitprice = $obj->;
            $obj->totamount = $obj->amount;
            $obj->amount = $obj->amount;
            // $obj->remarks_button = $obj->;
            // $obj->remarks = $obj->;
            $obj->recstatus = $obj->recstatus;
            $obj->batchno = $obj->batchno;
            $obj->expdate = $obj->expdate;
            // $obj->unit = $obj->;
            $obj->kkmappno = '-';
            $obj->uom_desc = $obj->uom_desc;
        }

        $delorddt = $billsum;

        // $chgmast = DB::table('debtor.billsum AS b', 'hisdb.chgmast as m')
        //     ->select('b.compcode', 'b.idno','b.invno', 'b.mrn', 'b.billno', 'b.lineno_', 'b.chgclass', 'b.chggroup', 'b.description', 'b.uom', 'b.quantity', 'b.amount', 'b.outamt', 'b.taxamt', 'b.unitprice', 'b.taxcode', 'b.discamt', 'b.recstatus', 'm.description as chgmast_desc')
        //     ->leftJoin('hisdb.chgmast as m', 'b.description', '=', 'm.description')
        //     ->where('b.source','=',$dbacthdr->source)
        //     ->where('b.trantrype','=',$dbacthdr->trantrype)
        //     ->where('b.billno','=',$dbacthdr->auditno)
        //     ->get();
        
        $title = "DELIVERY ORDER";
        // if($dbacthdr->recstatus == "OPEN"){
        //     $title = "DELIVERY ORDER";
        // }else{
        //     $title = " INVOICE";
        // }

        $company = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();

        $sum_billsum = $billsum->sum('amount');

        $totamount_expld = explode(".", (float)$sum_billsum);

        $totamt_bm_rm = $this->convertNumberToWordBM($totamount_expld[0])." RINGGIT ";
        $totamt_bm = $totamt_bm_rm." SAHAJA";

        if(count($totamount_expld) > 1){
            $totamt_bm_sen = $this->convertNumberToWordBM($totamount_expld[1])." SEN";
            $totamt_bm = $totamt_bm_rm.$totamt_bm_sen." SAHAJA";
        }
        $totamt_eng = $totamt_bm;
        $total_tax = 0;
        $total_discamt = 0;
        $total_amt = $dbacthdr->amount;
        $cr_acc = [];
        $db_acc = [];
        
        // $pdf = PDF::loadView('finance.SalesOrder.SalesOrder_pdf',compact('dbacthdr','billsum','totamt_bm','company', 'title'));
    
        // return $pdf->stream();

        return view('material.deliveryOrder.deliveryOrder_pdfmake',compact('delordhd','delorddt','totamt_eng', 'company', 'total_tax', 'total_discamt', 'total_amt','cr_acc','db_acc','title'));    
        
        // return view('finance.SalesOrder.SalesOrder_pdfmake',compact('dbacthdr','billsum','totamt_bm','company', 'title','sum_billsum'));
    }

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

    public function get_quoteno(Request $request){

        $table = DB::table('finance.salehdr as sh')
                        ->select('sh.quoteno','sh.debtorcode','dm.name','sh.entrydate','sh.idno','sh.compcode','sh.source','sh.trantype','sh.auditno','sh.lineno_','sh.amount','sh.outamount','sh.hdrsts','sh.posteddate','sh.entrytime','sh.entryuser','sh.reference','sh.recptno','sh.paymode','sh.tillcode','sh.tillno','sh.debtortype','sh.payercode','sh.billdebtor','sh.remark','sh.mrn','sh.episno','sh.authno','sh.expdate','sh.adddate','sh.adduser','sh.upddate','sh.upduser','sh.epistype','sh.cbflag','sh.conversion','sh.payername','sh.hdrtype','sh.currency','sh.rate','sh.startdate','sh.termvalue','sh.termcode','sh.frequency','sh.pono','sh.podate','sh.saleid','sh.billtype','sh.docdate','sh.unit','sh.recstatus','sh.deptcode','sh.doctorcode','pm.Name as pm_Name')
                        ->leftJoin('debtor.debtormast as dm', function($join) use ($request){
                            $join = $join->where('dm.compcode',session('compcode'));
                            $join = $join->on('dm.debtorcode', '=', 'sh.debtorcode');
                        })
                        ->leftJoin('hisdb.pat_mast as pm', function($join) use ($request){
                            $join = $join->where('pm.CompCode',session('compcode'));
                            $join = $join->on('pm.NewMrn', '=', 'sh.mrn');
                        })
                        ->where('sh.compcode',session('compcode'))
                        ->where('sh.deptcode',$request->deptcode)
                        ->whereIn('sh.recstatus',['POSTED','PARTIAL']);

        if(!empty($request->searchCol)){
            if($request->searchCol[0] == 'db_invno'){
                $table = $table->Where(function ($table) use ($request){
                        $table->Where('db.invno','like',$request->searchVal[0]);
                });
            }else if($request->searchCol[0] == 'dm_name'){
                $table = $table->Where(function ($table) use ($request){
                        $table->Where('dm.name','like',$request->searchVal[0]);
                });
            }else if($request->searchCol[0] == 'db_payercode'){
                $table = $table->Where(function ($table) use ($request){
                        $table->Where('db.payercode','like',$request->searchVal[0]);
                });
            }else if($request->searchCol[0] == 'db_mrn'){
                $table = $table->Where(function ($table) use ($request){
                        $table->Where('db.mrn','like',$request->searchVal[0]);
                });
            }else if($request->searchCol[0] == 'db_auditno'){
                $table = $table->Where(function ($table) use ($request){
                        $table->Where('db.auditno',$request->wholeword);
                });
            }else{
                $table = $table->Where(function ($table) use ($request){
                        $table->Where($request->searchCol[0],'like',$request->searchVal[0]);
                });
            }
        }

        if(!empty($request->searchCol2)){

            if(!empty($request->fixPost)){
                $searchCol_array = $this->fixPost3($request->searchCol2);
            }else{
                $searchCol_array = $request->searchCol2;
            }

            $wholeword = false;
            if(!empty($searchCol_array[0])){
                $clone = clone $table;
                $clone = $clone->where($searchCol_array[0],$request->wholeword);
                // dd($this->getQueries($clone));
                if($clone->exists()){
                    $table = $table->where($searchCol_array[0],$request->wholeword);
                    $wholeword = true;
                }
            }

            if(!$wholeword && !empty($searchCol_array[1])){
                $clone = clone $table;
                $clone = $clone->where($searchCol_array[1],$request->wholeword);
                // dd($this->getQueries($clone));
                if($clone->exists()){
                    $table = $table->where($searchCol_array[1],$request->wholeword);
                    $wholeword = true;
                }
            }

            // $searchCol_array_1 = $searchCol_array_2 = $searchVal_array_1 = $searchVal_array_2 = [];

            // foreach ($searchCol_array as $key => $value) {
            //     if(($key+1)%2){
            //         array_push($searchCol_array_1, $searchCol_array[$key]);
            //         array_push($searchVal_array_1, $request->searchVal2[$key]);
            //     }else{
            //         array_push($searchCol_array_2, $searchCol_array[$key]);
            //         array_push($searchVal_array_2, $request->searchVal2[$key]);
            //     }
            // }
            if(!$wholeword){
                $table = $table->where(function($table) use ($searchCol_array, $request){
                    foreach ($searchCol_array as $key => $value) {
                        if($key>1) break;
                        $table->orwhere($searchCol_array[$key],'like', $request->searchVal2[$key]);
                    }
                });

                if(count($searchCol_array)>2){
                    $table = $table->where(function($table) use ($searchCol_array, $request){
                        foreach ($searchCol_array as $key => $value) {
                            if($key<=1) continue;
                            $table->orwhere($searchCol_array[$key],'like', $request->searchVal2[$key]);
                        }
                    });
                }
            }            
        }
        
        $paginate = $table->paginate($request->rows);

        $responce = new stdClass();
        $responce->page = $paginate->currentPage();
        $responce->total = $paginate->lastPage();
        $responce->records = $paginate->total();
        $responce->rows = $paginate->items();
        $responce->sql_query = $this->getQueries($table);
        
        return json_encode($responce);
    }

    public function get_salesum(Request $request){

        $table = DB::table('finance.salesum as sm')
                        ->select('sm.idno','sm.compcode','sm.source','sm.trantype','sm.auditno','sm.lineno_','sm.description','sm.quantity','sm.amount','sm.outamt','sm.totamount','sm.taxcode','sm.taxamt','sm.mrn','sm.episno','sm.paymode','sm.cardno','sm.debtortype','sm.debtorcode','sm.billno','sm.rowno','sm.billtype','sm.chgclass','sm.classlevel','sm.chggroup','sm.lastuser','sm.lastupdate','sm.invcode','sm.seqno','sm.discamt','sm.docref','sm.uprice','sm.remarks','sm.invdate','sm.percentdisc','sm.amtdisc','sm.adduser','sm.adddate','sm.upduser','sm.upddate','sm.saleid','sm.uom','sm.uom_recv','sm.pouom','sm.reference','sm.balance','sm.qtyonhand','sm.qtydelivered','sm.ucost','sm.qtydel','sm.unitprice','sm.billtypeperct','sm.billtypeamt','sm.recstatus')
                        ->where('sm.compcode',session('compcode'))
                        ->where('sm.auditno',$request->auditno);

        $responce = new stdClass();
        $responce->rows = $table->get();
        
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

    public function get_quoteno_check(Request $request){
        $quoteno = $request->filterVal[0];
        $table = DB::table('finance.salehdr as sh')
                        ->select('sh.quoteno','sh.debtorcode','dm.name','sh.entrydate')
                        ->leftJoin('debtor.debtormast as dm', function($join) use ($request){
                            $join = $join->where('dm.compcode',session('compcode'));
                            $join = $join->on('dm.debtorcode', '=', 'sh.debtorcode');
                        })
                        ->where('sh.compcode',session('compcode'))
                        ->where('sh.deptcode',$request->deptcode)
                        ->where('sh.quoteno',$quoteno)
                        ->whereIn('sh.recstatus',['POSTED','PARTIAL']);
        
        $responce = new stdClass();
        $responce->rows = $table->get();
        
        return json_encode($responce);
    }

    public function chk_billtype(Request $request){ 
        $hdrtype = $request->db_hdrtype;
        $posteddate = $request->db_entrydate;
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

    public function crtivdspdt($billsum_obj,$dbacthdr){

        $my_uom = $billsum_obj->uom;
        $my_chggroup = $billsum_obj->chggroup;

        $product = DB::table('material.product')
            ->where('compcode','=',session('compcode'))
            ->where('unit','=',session('unit'))
            ->where('uomcode','=',$my_uom)
            ->where('itemcode','=',$my_chggroup);

        $stockloc = DB::table('material.stockloc')
            ->where('compcode','=',session('compcode'))
            ->where('unit','=',session('unit'))
            ->where('uomcode','=',$my_uom)
            ->where('itemcode','=',$my_chggroup)
            ->where('deptcode','=',$dbacthdr->deptcode)
            ->where('year','=',Carbon::now("Asia/Kuala_Lumpur")->year);

        // dapatkan uom conversion factor untuk dapatkan txnqty dgn netprice
        if(!empty($billsum_obj->uom_recv)){
            $convuom_recv = DB::table('material.uom')
                ->where('compcode','=',session('compcode'))
                ->where('uomcode','=',$billsum_obj->uom_recv)
                ->first();
        }else{
            $convuom_recv = DB::table('material.uom')
                ->where('compcode','=',session('compcode'))
                ->where('uomcode','=',$billsum_obj->uom)
                ->first();
        }

        $convuom_recv = $convuom_recv->convfactor;

        $conv_uom = DB::table('material.uom')
            ->where('compcode','=',session('compcode'))
            ->where('uomcode','=',$billsum_obj->uom)
            ->first();
        $conv_uom = $conv_uom->convfactor;

        $curr_netprice = $product->first()->avgcost;
        $curr_quan = $billsum_obj->quantity * ($convuom_recv / $conv_uom);
        if($stockloc->exists()){
            $qoh_quan = $stockloc->first()->qtyonhand;
            $new_qoh = floatval($qoh_quan) - floatval($curr_quan);
            $new_val = floatval($curr_netprice) * floatval($curr_quan);
            $new_val = round($new_val, 2);

            $stockloc_first = $stockloc->first();
            $stockloc_arr = (array)$stockloc_first;

            $month = defaultController::toMonth(Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d'));
            $NetMvQty = floatval($stockloc_arr['netmvqty'.$month]) - floatval($curr_quan);
            $NetMvVal = floatval($stockloc_arr['netmvval'.$month]) - floatval($new_val);

            $stockloc
                ->update([
                    'QtyOnHand' => $new_qoh,
                    'NetMvQty'.$month => $NetMvQty, 
                    'NetMvVal'.$month => $NetMvVal
                ]);

            $sumqtyonhand = DB::table('material.stockloc')
                                ->select(DB::raw('SUM(qtyonhand) AS sum_qtyonhand'))
                                ->where('compcode','=',session('compcode'))
                                ->where('unit','=',session('unit'))
                                ->where('uomcode','=',$my_uom)
                                ->where('itemcode','=',$my_chggroup)
                                ->where('year','=',Carbon::now("Asia/Kuala_Lumpur")->year)
                                ->first();

            DB::table('material.product')
                ->where('compcode','=',session('compcode'))
                ->where('unit','=',session('unit'))
                ->where('uomcode','=',$my_uom)
                ->where('itemcode','=',$my_chggroup)
                ->update([
                    'qtyonhand' => $sumqtyonhand->sum_qtyonhand,
                ]);

            //4. tolak expdate, kalu ada batchno
            $expdate_obj = DB::table('material.stockexp')
                ->where('compcode',session('compcode'))
                // ->where('Year','=',defaultController::toYear($dbacthdr->entrydate))
                ->where('DeptCode','=',$dbacthdr->deptcode)
                ->where('ItemCode','=',$my_chggroup)
                ->where('UomCode','=',$my_uom)
                ->orderBy('expdate', 'asc');

            if($expdate_obj->exists()){
                $expdate_get = $expdate_obj->get();
                $txnqty_ = $curr_quan;
                $balqty = 0;
                $stockexp_use = $expdate_obj->first();

                foreach ($expdate_get as $value2) {
                    $balqty = $value2->balqty;
                    if($txnqty_-$balqty>0){
                        $txnqty_ = $txnqty_-$balqty;
                        DB::table('material.stockexp')
                            ->where('idno','=',$value2->idno)
                            ->update([
                                'balqty' => '0'
                            ]);
                    }else{
                        $balqty = $balqty-$txnqty_;
                        DB::table('material.stockexp')
                            ->where('idno','=',$value2->idno)
                            ->update([
                                'balqty' => $balqty
                            ]);
                        break;
                    }
                }
            }else{
                //3.kalu xde Stock Expiry, buat baru
                $BalQty = -$curr_quan;

                $stockexp_getid = DB::table('material.stockexp')
                            ->insertGetId([
                                'compcode' => session('compcode'), 
                                'unit' => session('unit'), 
                                'deptcode' => $dbacthdr->deptcode, 
                                'itemcode' => $my_chggroup, 
                                'uomcode' => $my_uom, 
                                'balqty' => $BalQty, 
                                'adduser' => session('username'), 
                                'adddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                                'upduser' => session('username'), 
                                'upddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                               // 'lasttt' => 'GRN', 
                                'year' => Carbon::now("Asia/Kuala_Lumpur")->year
                            ]);


                $stockexp_use = $expdate_obj->where('idno',$stockexp_getid)->first();
            }
        }

        $ivdspdt_arr = [
            'compcode' => session('compcode'),
            'recno' => $billsum_obj->auditno,//OE IN
            'lineno_' => 1,
            'itemcode' => $billsum_obj->chggroup,
            'uomcode' => $billsum_obj->uom,
            'txnqty' => $curr_quan,
            'adduser' => session('username'),
            'adddate' => $dbacthdr->entrydate,
            'netprice' => $curr_netprice,
            'saleamt' => $billsum_obj->amount,
            'productcat' => $product->first()->productcat,
            'issdept' => $dbacthdr->deptcode,
            'reqdept' => $dbacthdr->deptcode,
            'amount' => round(floatval($curr_netprice) * floatval($curr_quan), 2),
            'trantype' => 'DS',
            'trandate' => Carbon::now("Asia/Kuala_Lumpur"),
            'trxaudno' => $billsum_obj->auditno,
            'mrn' => $this->givenullifempty($dbacthdr->mrn),
            'episno' => $this->givenullifempty($dbacthdr->episno),
            'updtime' => Carbon::now("Asia/Kuala_Lumpur"),
            'expdate' => $stockexp_use->expdate,
            'batchno' => $stockexp_use->batchno
        ];


        $insertGetId = DB::table('material.ivdspdt')
                            ->insertGetId($ivdspdt_arr);

        return $insertGetId;
    }

    public function updivdspdt($billsum_obj,$dbacthdr){

        $ivdspdt_lama = DB::table('material.ivdspdt')
            ->where('compcode','=',session('compcode'))
            ->where('recno','=',$billsum_obj->auditno);

        $product = DB::table('material.product')
            ->where('compcode','=',session('compcode'))
            ->where('unit','=',session('unit'))
            ->where('uomcode','=',$billsum_obj->uom)
            ->where('itemcode','=',$billsum_obj->chggroup);

        $stockloc = DB::table('material.stockloc')
            ->where('compcode','=',session('compcode'))
            ->where('unit','=',session('unit'))
            ->where('uomcode','=',$billsum_obj->uom)
            ->where('itemcode','=',$billsum_obj->chggroup)
            ->where('deptcode','=',$dbacthdr->deptcode)
            ->where('year','=',Carbon::now("Asia/Kuala_Lumpur")->year);

        // dapatkan uom conversion factor untuk dapatkan txnqty dgn netprice
        $convuom_recv = DB::table('material.uom')
            ->where('compcode','=',session('compcode'))
            ->where('uomcode','=',$billsum_obj->uom_recv)
            ->first();
        $convuom_recv = $convuom_recv->convfactor;

        $conv_uom = DB::table('material.uom')
            ->where('compcode','=',session('compcode'))
            ->where('uomcode','=',$billsum_obj->uom)
            ->first();
        $conv_uom = $conv_uom->convfactor;

        if($stockloc->exists()){

            $prev_netprice = $product->first()->avgcost; 
            $prev_quan = $ivdspdt_lama->first()->txnqty;
            $curr_netprice = $product->first()->avgcost;
            $curr_quan = $billsum_obj->quantity * ($convuom_recv / $conv_uom);
            $qoh_quan = $stockloc->first()->qtyonhand;
            $new_qoh = floatval($qoh_quan) + floatval($prev_quan) - floatval($curr_quan);

            $stockloc_first = $stockloc->first();
            $stockloc_arr = (array)$stockloc_first;
            $new_val = round(floatval(floatval($prev_netprice) * floatval($prev_quan)) - floatval(floatval($curr_netprice) * floatval($curr_quan)), 2);

            $month = defaultController::toMonth(Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d'));
            $NetMvQty = floatval($stockloc_arr['netmvqty'.$month]) + floatval($prev_quan) - floatval($curr_quan);
            $NetMvVal = floatval($stockloc_arr['netmvval'.$month]) + $new_val;

            $stockloc
                ->update([
                    'QtyOnHand' => $new_qoh,
                    'NetMvQty'.$month => $NetMvQty, 
                    'NetMvVal'.$month => $NetMvVal
                ]);

            $sumqtyonhand = DB::table('material.stockloc')
                                ->select(DB::raw('SUM(qtyonhand) AS sum_qtyonhand'))
                                ->where('compcode','=',session('compcode'))
                                ->where('unit','=',session('unit'))
                                ->where('uomcode','=',$billsum_obj->uom)
                                ->where('itemcode','=',$billsum_obj->chggroup)
                                ->where('year','=',Carbon::now("Asia/Kuala_Lumpur")->year)
                                ->first();

            $product
                ->update([
                    'qtyonhand' => $sumqtyonhand->sum_qtyonhand,
                ]);

            //4. tolak expdate, kalu ada batchno
            $expdate_obj = DB::table('material.stockexp')
                ->where('compcode',session('compcode'))
                // ->where('Year','=',defaultController::toYear($dbacthdr->entrydate))
                ->where('DeptCode','=',$dbacthdr->deptcode)
                ->where('ItemCode','=',$billsum_obj->chggroup)
                ->where('UomCode','=',$billsum_obj->uom)
                ->where('batchno','=',$ivdspdt_lama->first()->batchno)
                ->where('expdate','=',$ivdspdt_lama->first()->expdate)
                ->orderBy('expdate', 'asc');

            if($expdate_obj->exists()){
                $expdate_first = $expdate_obj->first();
                $txnqty_ = $curr_quan;
                $balqty = floatval($expdate_first->balqty) + floatval($prev_quan) - floatval($curr_quan);
                $expdate_obj
                        ->update([
                            'balqty' => $balqty
                        ]);

            }else{
                $BalQty = floatval($prev_quan) - floatval($curr_quan);

                $stockexp_getid = DB::table('material.stockexp')
                    ->insert([
                        'compcode' => session('compcode'), 
                        'unit' => session('unit'), 
                        'deptcode' => $dbacthdr->deptcode, 
                        'itemcode' => $billsum_obj->chggroup, 
                        'uomcode' => $billsum_obj->uom, 
                        'balqty' => $BalQty, 
                        'adduser' => session('username'), 
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                        'upduser' => session('username'), 
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                       // 'lasttt' => 'GRN', 
                        'year' => Carbon::now("Asia/Kuala_Lumpur")->year
                    ]);

                $stockexp_use = $expdate_obj->where('idno',$stockexp_getid)->first();
            }

        }

        $ivdspdt_arr = [
            'txnqty' => $curr_quan,
            'upduser' => session('username'),
            'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
            'netprice' => $curr_netprice,
            'saleamt' => $billsum_obj->amount,
            'amount' => floatval(floatval($curr_netprice) * floatval($curr_quan)),
            'updtime' => Carbon::now("Asia/Kuala_Lumpur")
        ];

        // pindah ke ivdspdtlog
        // recstatus->update
        $ivdspdt_lama = DB::table('material.ivdspdt')
                        ->where('compcode','=',session('compcode'))
                        ->where('recno','=',$billsum_obj->auditno)
                        ->first();

        // $this->sysdb_log('update',$ivdspdt_lama,'sysdb.ivdspdtlog');

        DB::table('material.ivdspdt')
            ->where('compcode','=',session('compcode'))
            ->where('recno','=',$billsum_obj->auditno)
            ->update($ivdspdt_arr);
    }

    public function delivdspdt($billsum_obj,$dbacthdr){

        $ivdspdt_lama = DB::table('material.ivdspdt')
            ->where('compcode','=',session('compcode'))
            ->where('recno','=',$billsum_obj->auditno);

        $product = DB::table('material.product')
            ->where('compcode','=',session('compcode'))
            ->where('unit','=',session('unit'))
            ->where('uomcode','=',$billsum_obj->uom)
            ->where('itemcode','=',$billsum_obj->chggroup);

        $stockloc = DB::table('material.stockloc')
            ->where('compcode','=',session('compcode'))
            ->where('unit','=',session('unit'))
            ->where('uomcode','=',$billsum_obj->uom)
            ->where('itemcode','=',$billsum_obj->chggroup)
            ->where('deptcode','=',$dbacthdr->deptcode)
            ->where('year','=',Carbon::now("Asia/Kuala_Lumpur")->year);

        if($stockloc->exists()){

            $prev_netprice = $ivdspdt_lama->first()->netprice; 
            $prev_quan = $ivdspdt_lama->first()->txnqty;
            $qoh_quan = $stockloc->first()->qtyonhand;
            $new_qoh = floatval($qoh_quan) + floatval($prev_quan);

            $stockloc_first = $stockloc->first();
            $stockloc_arr = (array)$stockloc_first;

            $month = defaultController::toMonth($dbacthdr->posteddate);
            $NetMvQty = floatval($stockloc_arr['netmvqty'.$month]) + floatval($prev_quan);
            $NetMvVal = floatval($stockloc_arr['netmvval'.$month]) + round(floatval(floatval($prev_netprice) * floatval($prev_quan)),2);

            $stockloc
                ->update([
                    'QtyOnHand' => $new_qoh,
                    'NetMvQty'.$month => $NetMvQty, 
                    'NetMvVal'.$month => $NetMvVal
                ]);

            $product
                ->update([
                    'qtyonhand' => $new_qoh,
                ]);

            //4. tolak expdate, kalu ada batchno
            $expdate_obj = DB::table('material.stockexp')
                ->where('compcode',session('compcode'))
                // ->where('Year','=',defaultController::toYear($dbacthdr->entrydate))
                ->where('DeptCode','=',$dbacthdr->deptcode)
                ->where('ItemCode','=',$billsum_obj->chggroup)
                ->where('UomCode','=',$billsum_obj->uom)
                ->where('batchno','=',$ivdspdt_lama->first()->batchno)
                ->where('expdate','=',$ivdspdt_lama->first()->expdate)
                ->orderBy('expdate', 'asc');

            if($expdate_obj->exists()){
                $expdate_first = $expdate_obj->first();
                // $txnqty_ = $curr_quan;
                $balqty = floatval($expdate_first->balqty) + floatval($prev_quan);
                $expdate_obj
                        ->update([
                            'balqty' => $balqty
                        ]);

            }else{
                $BalQty = $prev_quan;

                DB::table('material.stockexp')
                    ->insert([
                        'compcode' => session('compcode'), 
                        'unit' => session('unit'), 
                        'deptcode' => $dbacthdr->deptcode, 
                        'itemcode' => $billsum_obj->chggroup, 
                        'uomcode' => $billsum_obj->uom, 
                        'balqty' => $BalQty, 
                        'adduser' => session('username'), 
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                        'upduser' => session('username'), 
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                       // 'lasttt' => 'GRN', 
                        'year' => Carbon::now("Asia/Kuala_Lumpur")->year
                    ]);
            }
        }

        $gltran = DB::table('finance.gltran')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','=','IV')
                    ->where('trantype','=','DS')
                    ->where('auditno','=',$billsum_obj->auditno);

        if($gltran->exists()){
            $gltran_first = $gltran->first();

            $OldAmount = $gltran_first->amount;
            $yearperiod = $this->getyearperiod($ivdspdt_lama->first()->trandate);
            $drcostcode = $gltran_first->drcostcode;
            $dracc = $gltran_first->dracc;
            $crcostcode = $gltran_first->crcostcode;
            $cracc = $gltran_first->cracc;

            //2. check glmastdtl utk debit, kalu ada update kalu xde create
            $gltranAmount =  $this->isGltranExist($drcostcode,$dracc,$yearperiod->year,$yearperiod->period);

            if($gltranAmount!==false){
                DB::table('finance.glmasdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('costcode','=',$drcostcode)
                    ->where('glaccount','=',$dracc)
                    ->where('year','=',$yearperiod->year)
                    ->update([
                        'upduser' => session('username'),
                        'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                        'actamount'.$yearperiod->period => $gltranAmount - $OldAmount,
                        'recstatus' => 'ACTIVE'
                    ]);
            }else{
                DB::table('finance.glmasdtl')
                    ->insert([
                        'compcode' => session('compcode'),
                        'costcode' => $drcostcode,
                        'glaccount' => $dracc,
                        'year' => $yearperiod->year,
                        'actamount'.$yearperiod->period => -$OldAmount,
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
                        'actamount'.$yearperiod->period => $gltranAmount + $OldAmount,
                        'recstatus' => 'ACTIVE'
                    ]);
            }else{
                DB::table('finance.glmasdtl')
                    ->insert([
                        'compcode' => session('compcode'),
                        'costcode' => $crcostcode,
                        'glaccount' => $cracc,
                        'year' => $yearperiod->year,
                        'actamount'.$yearperiod->period => +$OldAmount,
                        'adduser' => session('username'),
                        'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                        'recstatus' => 'ACTIVE'
                    ]);
            }

            DB::table('finance.gltran')
                ->where('compcode','=',session('compcode'))
                ->where('source','=','IV')
                ->where('trantype','=','DS')
                ->where('auditno','=',$billsum_obj->auditno)
                ->delete();
        }

        // pindah ke ivdspdtlog
        // recstatus->delete
        // $ivdspdt_lama = DB::table('material.ivdspdt')
        //                 ->where('compcode','=',session('compcode'))
        //                 ->where('recno','=',$billsum_obj->auditno)
        //                 ->first();

        // $this->sysdb_log('delete',$ivdspdt_lama,'sysdb.ivdspdtlog');

        DB::table('material.ivdspdt')
            ->where('compcode','=',session('compcode'))
            ->where('trantype','=','DS')
            ->where('recno','=',$billsum_obj->auditno)
            ->delete();
    }

    public function crtgltran($ivdspdt_idno,$dbacthdr){
        $yearperiod = $this->getyearperiod(Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d'));

        $ivdspdt = DB::table('material.ivdspdt')
                        ->where('compcode',session('compcode'))
                        ->where('idno','=',$ivdspdt_idno)
                        ->first();

        //tengok product category
        $product_obj = DB::table('material.product')
            ->where('compcode','=', session('compcode'))
            ->where('unit','=', session('unit'))
            ->where('itemcode','=', $ivdspdt->itemcode)
            ->first();

        $row_dept = DB::table('sysdb.department')
            ->select('costcode')
            ->where('compcode','=',session('compcode'))
            ->where('deptcode','=',$dbacthdr->deptcode)
            ->first();
        //utk debit accountcode
        $row_cat = DB::table('material.category')
            ->select('stockacct','cosacct')
            ->where('compcode','=',session('compcode'))
            ->where('catcode','=',$product_obj->productcat);

        if(!$row_cat->exists()){
            throw new \Exception("category doesnt exists: ".$product_obj->productcat." for itemcode: ".$ivdspdt->itemcode);
        }else{
            $row_cat = $row_cat->first();
        }
            
        $drcostcode = $row_dept->costcode;
        $dracc = $row_cat->cosacct;
        $crcostcode = $row_dept->costcode;
        $cracc = $row_cat->stockacct;

        //1. buat gltran
        DB::table('finance.gltran')
            ->insert([
                'compcode' => session('compcode'),
                'adduser' => session('username'),
                'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                'auditno' => $ivdspdt->recno,//billsum auditno
                'lineno_' => 1,
                'source' => 'IV', //kalau stock 'IV', lain dari stock 'DO'
                'trantype' => 'DS',
                'reference' => $ivdspdt->uomcode,
                'description' => $ivdspdt->itemcode, 
                'postdate' => Carbon::now("Asia/Kuala_Lumpur"),
                'year' => $yearperiod->year,
                'period' => $yearperiod->period,
                'drcostcode' => $drcostcode,
                'dracc' => $dracc,
                'crcostcode' => $crcostcode,
                'cracc' => $cracc,
                'amount' => $ivdspdt->amount
            ]);

        //2. check glmastdtl utk debit, kalu ada update kalu xde create
        $gltranAmount =  $this->isGltranExist($drcostcode,$dracc,$yearperiod->year,$yearperiod->period);

        if($gltranAmount!==false){
            DB::table('finance.glmasdtl')
                ->where('compcode','=',session('compcode'))
                ->where('costcode','=',$drcostcode)
                ->where('glaccount','=',$dracc)
                ->where('year','=',$yearperiod->year)
                ->update([
                    'upduser' => session('username'),
                    'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'actamount'.$yearperiod->period => $ivdspdt->amount + $gltranAmount,
                    'recstatus' => 'ACTIVE'
                ]);
        }else{
            DB::table('finance.glmasdtl')
                ->insert([
                    'compcode' => session('compcode'),
                    'costcode' => $drcostcode,
                    'glaccount' => $dracc,
                    'year' => $yearperiod->year,
                    'actamount'.$yearperiod->period => $ivdspdt->amount,
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
                    'actamount'.$yearperiod->period => $gltranAmount - $ivdspdt->amount,
                    'recstatus' => 'ACTIVE'
                ]);
        }else{
            DB::table('finance.glmasdtl')
                ->insert([
                    'compcode' => session('compcode'),
                    'costcode' => $crcostcode,
                    'glaccount' => $cracc,
                    'year' => $yearperiod->year,
                    'actamount'.$yearperiod->period => -$ivdspdt->amount,
                    'adduser' => session('username'),
                    'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'recstatus' => 'ACTIVE'
                ]);
        }
    }

    public function updgltran($ivdspdt_idno,$dbacthdr){
        $ivdspdt = DB::table('material.ivdspdt')
                        ->where('idno','=',$ivdspdt_idno)
                        ->first();

        $gltran = DB::table('finance.gltran')
                    ->where('compcode','=',session('compcode'))
                    ->where('auditno','=',$ivdspdt->recno);

        if($gltran->exists()){
            $gltran_first = $gltran->first();

            $OldAmount = $gltran_first->amount;
            $yearperiod = $this->getyearperiod($ivdspdt->trandate);
            $drcostcode = $gltran_first->drcostcode;
            $dracc = $gltran_first->dracc;
            $crcostcode = $gltran_first->crcostcode;
            $cracc = $gltran_first->cracc;

            $gltran->update([
                'amount' => $ivdspdt->amount
            ]);

            //2. check glmastdtl utk debit, kalu ada update kalu xde create
            $gltranAmount =  $this->isGltranExist($drcostcode,$dracc,$yearperiod->year,$yearperiod->period);

            if($gltranAmount!==false){
                DB::table('finance.glmasdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('costcode','=',$drcostcode)
                    ->where('glaccount','=',$dracc)
                    ->where('year','=',$yearperiod->year)
                    ->update([
                        'upduser' => session('username'),
                        'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                        'actamount'.$yearperiod->period => $gltranAmount - $OldAmount + $ivdspdt->amount,
                        'recstatus' => 'ACTIVE'
                    ]);
            }else{
                DB::table('finance.glmasdtl')
                    ->insert([
                        'compcode' => session('compcode'),
                        'costcode' => $drcostcode,
                        'glaccount' => $dracc,
                        'year' => $yearperiod->year,
                        'actamount'.$yearperiod->period => $ivdspdt->amount,
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
                        'actamount'.$yearperiod->period => $gltranAmount + $OldAmount - $ivdspdt->amount,
                        'recstatus' => 'ACTIVE'
                    ]);
            }else{
                DB::table('finance.glmasdtl')
                    ->insert([
                        'compcode' => session('compcode'),
                        'costcode' => $crcostcode,
                        'glaccount' => $cracc,
                        'year' => $yearperiod->year,
                        'actamount'.$yearperiod->period => -$ivdspdt->amount,
                        'adduser' => session('username'),
                        'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                        'recstatus' => 'ACTIVE'
                    ]);
            }

        }else{
            throw new \Exception("Gltran doesnt exists");
        }
    }

    public function delgltran($billsum_obj,$dbacthdr){
        $gltran = DB::table('finance.gltran')
                    ->where('compcode','=',session('compcode'))
                    ->where('auditno','=',$billsum_obj->auditno);

        if($gltran->exists()){
            $gltran_first = $gltran->first();

            $OldAmount = $gltran_first->amount;
            $yearperiod = $this->getyearperiod(Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d'));
            $drcostcode = $gltran_first->drcostcode;
            $dracc = $gltran_first->dracc;
            $crcostcode = $gltran_first->crcostcode;
            $cracc = $gltran_first->cracc;

            // $gltran->update([
            //     'amount' => $ivdspdt->amount
            // ]);

            //2. check glmastdtl utk debit, kalu ada update kalu xde create
            $gltranAmount =  $this->isGltranExist($drcostcode,$dracc,$yearperiod->year,$yearperiod->period);

            if($gltranAmount!==false){
                DB::table('finance.glmasdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('costcode','=',$drcostcode)
                    ->where('glaccount','=',$dracc)
                    ->where('year','=',$yearperiod->year)
                    ->update([
                        'upduser' => session('username'),
                        'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                        'actamount'.$yearperiod->period => $gltranAmount - $OldAmount,
                        'recstatus' => 'ACTIVE'
                    ]);
            }else{
                DB::table('finance.glmasdtl')
                    ->insert([
                        'compcode' => session('compcode'),
                        'costcode' => $drcostcode,
                        'glaccount' => $dracc,
                        'year' => $yearperiod->year,
                        'actamount'.$yearperiod->period => -$OldAmount,
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
                        'actamount'.$yearperiod->period => $gltranAmount + $OldAmount,
                        'recstatus' => 'ACTIVE'
                    ]);
            }else{
                DB::table('finance.glmasdtl')
                    ->insert([
                        'compcode' => session('compcode'),
                        'costcode' => $crcostcode,
                        'glaccount' => $cracc,
                        'year' => $yearperiod->year,
                        'actamount'.$yearperiod->period => +$OldAmount,
                        'adduser' => session('username'),
                        'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                        'recstatus' => 'ACTIVE'
                    ]);
            }

            DB::table('finance.gltran')
                ->where('compcode','=',session('compcode'))
                ->where('auditno','=',$billsum_obj->auditno)
                ->delete();
        }
    }

    public function new_patient(Request $request){

        DB::beginTransaction();
        
        try{

            if($request->oper_ == 'add'){

                $pat_mast = DB::table('hisdb.pat_mast')
                            ->where('compcode',session('compcode'))
                            ->where('NewMrn',$request->np_newmrn);

                if($pat_mast->exists()){
                    throw new \Exception("HUKM MRN already exists");
                }

                $pat_mast_ic = DB::table('hisdb.pat_mast')
                            ->where('compcode',session('compcode'))
                            ->where('Newic',$request->np_newic);

                if($pat_mast_ic->exists()){
                    throw new \Exception("I/C already exists");
                }

                $mrn_ = $this->recno('HIS','MRN');
                $newmrn_ = strtoupper($request->np_newmrn);
                DB::table('hisdb.pat_mast')
                    ->insert([
                        'CompCode' => session('compcode'),
                        'MRN' => $mrn_,
                        'Name' => strtoupper($request->np_name),
                        'Newic' => $request->np_newic,
                        'Address1' => $request->np_address1,
                        'Address2' => $request->np_address2,
                        'Address3' => $request->np_address3,
                        'Postcode' => $request->np_postcode,
                        'Reg_Date' => Carbon::now("Asia/Kuala_Lumpur"),
                        'Active' => 1,
                        'AddUser' => session('username'),
                        'AddDate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'Lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'LastUser' => session('username'),
                        'NewMrn' => strtoupper($request->np_newmrn),
                        'PatClass' => 'HIS',
                        'computerid' => session('computerid'),
                    ]);

                DB::table('debtor.debtormast')
                    ->where('compcode',session('compcode'))
                    ->where('debtorcode',$request->np_newmrn)
                    ->update([
                        'newic' => $request->np_newic
                    ]);
            }else if($request->oper_ == 'edit'){
                $pat_mast = DB::table('hisdb.pat_mast')
                            ->where('idno','!=',$request->np_idno)
                            ->where('compcode',session('compcode'))
                            ->where('NewMrn',$request->np_newmrn);

                if($pat_mast->exists()){
                    throw new \Exception("HUKM MRN already exists");
                }

                $pat_mast_ic = DB::table('hisdb.pat_mast')
                            ->where('idno','!=',$request->np_idno)
                            ->where('compcode',session('compcode'))
                            ->where('Newic',$request->np_newic);

                if($pat_mast_ic->exists()){
                    throw new \Exception("I/C already exists");
                }

                $newmrn_ = 'none';
                DB::table('hisdb.pat_mast')
                    ->where('idno',$request->np_idno)
                    ->update([
                        'Name' => strtoupper($request->np_name),
                        'Newic' => $request->np_newic,
                        'Address1' => $request->np_address1,
                        'Address2' => $request->np_address2,
                        'Address3' => $request->np_address3,
                        'Postcode' => $request->np_postcode,
                        'Active' => 1,
                        'Lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'LastUser' => session('username'),
                        'NewMrn' => strtoupper($request->np_newmrn),
                        'PatClass' => 'HIS',
                        'computerid' => session('computerid'),
                    ]);
            
                DB::table('debtor.debtormast')
                    ->where('compcode',session('compcode'))
                    ->where('debtorcode',$request->np_newmrn)
                    ->update([
                        'newic' => $request->np_newic
                    ]);
            }

            DB::commit();

            $responce = new stdClass();
            $responce->mrn = $newmrn_;
            $responce->name = $request->np_name;
            echo json_encode($responce);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response($e->getMessage(), 500);
        }
    }

    public function new_customer(Request $request){

        DB::beginTransaction();
        
        try{

            if($request->oper_ == 'add'){

                $debtormast = DB::table('debtor.debtormast')
                            ->where('compcode',session('compcode'))
                            ->where('debtorcode',$request->nc_debtorcode);

                if($debtormast->exists()){
                    throw new \Exception("Debtorcode already exists");
                }
                $debtorcode_ = strtoupper($request->nc_debtorcode);

                $debtortype = DB::table('debtor.debtortype')
                            ->where('compcode',session('compcode'))
                            ->where('debtortycode','PT')
                            ->first();

                DB::table('debtor.debtormast')
                    ->insert([
                        'compcode' => session('compcode'),
                        'debtortype' => 'PT',
                        'debtorcode' => strtoupper($request->nc_debtorcode),
                        'name' => strtoupper($request->nc_name),
                        'address1' => $request->nc_address1,
                        'address2' => $request->nc_address2,
                        'address3' => $request->nc_address3,
                        'address4' => $request->nc_address4,
                        'postcode' => $request->nc_postcode,
                        'billtype' => 'OP' ,
                        'billtypeop' => 'OP' ,
                        'recstatus' => 'ACTIVE' ,
                        'actdebccode' => $debtortype->actdebccode,
                        'actdebglacc' => $debtortype->actdebglacc,
                        'depccode' => $debtortype->depccode,
                        'depglacc' => $debtortype->depglacc,
                        'adduser' => session('username'),
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'coverageip' => 9999999.99,
                        'coverageop' => 9999999.99,
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'computerid' => session('computerid')
                    ]);
            }else if($request->oper_ == 'edit'){

                $debtormast = DB::table('debtor.debtormast')
                            ->where('idno','!=',$request->nc_idno)
                            ->where('compcode',session('compcode'))
                            ->where('debtorcode',$request->nc_debtorcode);

                if($debtormast->exists()){
                    throw new \Exception("Debtorcode already exists");
                }
                $debtorcode_ = 'none';
                DB::table('debtor.debtormast')
                    ->where('idno',$request->nc_idno)
                    ->update([
                        'debtorcode' => strtoupper($request->nc_debtorcode),
                        'name' => strtoupper($request->nc_name),
                        'address1' => $request->nc_address1,
                        'address2' => $request->nc_address2,
                        'address3' => $request->nc_address3,
                        'address4' => $request->nc_address4,
                        'postcode' => $request->nc_postcode,
                        'recstatus' => 'ACTIVE' ,
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'computerid' => session('computerid')
                    ]);
            }

            DB::commit();

            $responce = new stdClass();
            $responce->debtorcode = $debtorcode_;
            $responce->name = $request->np_name;
            echo json_encode($responce);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response($e->getMessage(), 500);
        }
    }

    public function pos_receipt_save(Request $request){
        DB::beginTransaction();
        
        try {
            $this->validate_receipt($request);
            if(empty($request->dbacthdr_entrydate)){
                $request->dbacthdr_entrydate = Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d');
            }

            $auditno = $this->defaultSysparam('PB','RC');

            $till = DB::table('debtor.till')
                            ->where('compcode',session('compcode'))
                            ->where('tillstatus','O')
                            ->where('lastuser',session('username'));

            if($till->exists()){

                $till_obj = $till->first();

                $tilldetl = DB::table('debtor.tilldetl')
                            ->where('compcode',session('compcode'))
                            ->where('tillcode',$till_obj->tillcode)
                            ->where('cashier',$till_obj->lastuser)
                            ->where('opendate','=',$till_obj->upddate)
                            ->whereNull('closedate');

                $lastrcnumber = $this->defaultTill($till_obj->tillcode,'lastrcnumber');

                $tillcode = $till_obj->tillcode;
                $tilldeptcode = $till_obj->dept;
               // dd($tilldeptcode);
                $tillno = $tilldetl->first()->tillno;
                $recptno = $till_obj->tillcode.'-'.str_pad($lastrcnumber, 9, "0", STR_PAD_LEFT);

            }else{
                throw new \Exception("User dont have till");
            }

            $paymode_ = $this->paymode_chg($request->tabform,$request->dbacthdr_paymode);

            // if(strtolower($paymode_) == 'cash' && $request->dbacthdr_trantype == "RC"){
            //     if(empty($request->dbacthdr_RCFinalbalance) && floatval($request->dbacthdr_amount) > floatval($request->dbacthdr_outamount)){
            //         $dbacthdr_amount = $request->dbacthdr_outamount;
            //     }
            // }

            $payercode = DB::table('debtor.dbacthdr as db')
                            ->select('db.auditno','db.amount','db.lineno_','dm.debtortype','dm.debtorcode','dm.name','db.outamount')
                            ->leftJoin('debtor.debtormast as dm', function($join) use ($request){
                                $join = $join->on('dm.debtorcode', '=', 'db.debtorcode');
                                $join = $join->where('dm.compcode', '=', session('compcode'));
                            })
                            ->where('db.idno',$request->idno);
            if(!$payercode->exists()){
                throw new \Exception("Debtorcode doesnt exists, error");
            }
            $payercode = $payercode->first();

            $dbacthdr_amount = $request->dbacthdr_amount;
            $amount_paid = floatval($dbacthdr_amount);
            $amount_bal = floatval($payercode->outamount) - floatval($dbacthdr_amount);

            $array_insert = [
                'compcode' => session('compcode'),
                'unit' => session('unit'),
                'adduser' => session('username'),
                'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                'entrydate' => Carbon::now("Asia/Kuala_Lumpur"),
                'entrytime' => Carbon::now("Asia/Kuala_Lumpur"),
                'posteddate' => Carbon::now("Asia/Kuala_Lumpur"),
                'reference' => strtoupper($request->dbacthdr_reference),
                'authno' => $request->dbacthdr_authno,
                'expdate' => Carbon::parse($request->dbacthdr_expdate)->endOfMonth()->toDateString(),
                'entryuser' => session('username'),
                'recstatus' => 'POSTED',
                'source' => 'PB',
                'trantype' => 'RC',
                'auditno' => $auditno,
                'lineno_' => 1,
                // 'currency' => $request->dbacthdr_currency,
                'debtortype' => $payercode->debtortype,
                'PymtDescription' => 'Sales Order',
                'payercode' => $payercode->debtorcode,
                'debtorcode' => $payercode->debtorcode,
                'payername' => $payercode->name,
                'paytype' => $request->tabform,
                'paymode' => $paymode_,
                'amount' => $dbacthdr_amount,  
                'outamount' => $dbacthdr_amount,  
                'remark' => 'Sales Order',  
                'tillcode' => $tillcode,  
                'tillno' => $tillno,  
                'recptno' => $recptno,     
                'deptcode' => $tilldeptcode, 
                'hdrtype' => 'RC',
            ];

            DB::table('debtor.dbacthdr')
                        ->insert($array_insert);

            //cbtran if paymode by bank
            if(strtolower($request->tabform) == '#f_tab-debit'){
                $yearperiod = $this->getyearperiod(Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d'));
                $paymode_db = DB::table('debtor.paymode')
                            ->where('compcode',session('compcode'))
                            ->where('source','AR')
                            ->where('paytype','Bank')
                            ->where('paymode',$request->dbacthdr_paymode)
                            ->first();
                $bankcode = $paymode_db->cardcent;
                
                DB::table('finance.cbtran')
                    ->insert([  
                        'compcode' => session('compcode'), 
                        'bankcode' => $bankcode, 
                        'source' => 'PB', 
                        'trantype' => 'RC', 
                        'auditno' => $auditno, 
                        'postdate' => $request->dbacthdr_entrydate, 
                        'year' => $yearperiod->year, 
                        'period' => $yearperiod->period, 
                        // 'cheqno' => $request->cheqno, 
                        'amount' => $dbacthdr_amount, 
                        'remarks' => strtoupper($payercode->name), 
                        'upduser' => session('username'), 
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                        'reference' => 'Pay by POS :'. ' ' .$payercode->name, 
                        'recstatus' => 'ACTIVE' 
                    ]);

                //1st step, 2nd phase, update bank detaild
                if($this->isCBtranExist($bankcode,$yearperiod->year,$yearperiod->period)){

                    $totamt = $this->getCbtranTotamt($bankcode,$yearperiod->year,$yearperiod->period);

                    DB::table('finance.bankdtl')
                        ->where('compcode','=',session('compcode'))
                        ->where('year','=',$yearperiod->year)
                        ->where('bankcode','=',$bankcode)
                        ->update([
                            "actamount".$yearperiod->period => $totamt->amount
                        ]);

                }else{

                    $totamt = $this->getCbtranTotamt($bankcode,$yearperiod->year,$yearperiod->period);

                    DB::table('finance.bankdtl')
                            ->insert([
                                'compcode' => session('compcode'),
                                'bankcode' => $bankcode,
                                'year' => $yearperiod->year,
                                'actamount'.$yearperiod->period => $totamt->amount,
                                'upduser' => session('username'),
                                'upddate' => Carbon::now("Asia/Kuala_Lumpur")

                            ]);
                }
            }

            $this->gltran_receipt($auditno,'RC');

            //allocation
            // $auditno_al = $this->defaultSysparam('AR','AL');                
            // DB::table('debtor.dballoc')
            //     ->insert([
            //         'compcode' => session('compcode'),
            //         'source' => 'AR',
            //         'trantype' => 'AL',
            //         'auditno' => $auditno_al,
            //         'lineno_' => 1,
            //         'docsource' => 'PB',
            //         'doctrantype' => 'RC',
            //         'docauditno' => $auditno,
            //         'refsource' => 'PB',
            //         'reftrantype' => 'IN',
            //         'refauditno' => $payercode->auditno,
            //         'refamount' => $payercode->amount,
            //         'reflineno' => $payercode->lineno_,
            //         'recptno' => $recptno,
            //         // 'mrn' => $receipt_first->mrn,
            //         // 'episno' => $receipt_first->episno,
            //         'allocsts' => 'ACTIVE',
            //         'amount' => $amount_paid,
            //         'tillcode' => $tillcode,
            //         'debtortype' => $payercode->debtortype,
            //         'debtorcode' => $payercode->debtorcode,
            //         'payercode' => $payercode->debtorcode,
            //         'paymode' => $paymode_,
            //         'allocdate' => Carbon::now("Asia/Kuala_Lumpur"),
            //         'remark' => 'Allocation POS',
            //         'balance' => $amount_bal,
            //         'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
            //         'adduser' => session('username'),
            //         'recstatus' => 'POSTED'
            //     ]);

            // DB::table('debtor.dbacthdr')
            //     ->where('idno',$request->idno)
            //     ->update([
            //         'outamount' => $amount_bal
            //     ]);


            DB::commit();

            $responce = new stdClass();
            $responce->auditno = $auditno;
            $responce->recptno = $recptno;
            $responce->payercode = $payercode->debtorcode;
            $responce->payername = $payercode->name;
            $responce->amount = $dbacthdr_amount;
            $responce->payername = $payercode->name;
            echo json_encode($responce);

        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }  
    }

    public function validate_receipt(Request $request){
            if(empty($request->dbacthdr_amount) || $request->dbacthdr_amount == 0.00){
                throw new \Exception("Payment amount needed");
            }

            if($request->tabform == '#f_tab-cash'){

            }else if($request->tabform == '#f_tab-card'){
                if(empty($request->dbacthdr_paymode)){
                    throw new \Exception("Please select card");
                }
                if(empty($request->dbacthdr_reference)){
                    throw new \Exception("Please enter reference");
                }
            }else if($request->tabform == '#f_tab-cheque'){
                if(empty($request->dbacthdr_entrydate)){
                    throw new \Exception("Please enter Entry Date");
                }
                if(empty($request->dbacthdr_reference)){
                    throw new \Exception("Please enter reference");
                }
            }else if($request->tabform == '#f_tab-debit'){
                if(empty($request->dbacthdr_entrydate)){
                    throw new \Exception("Please enter Entry Date");
                }
                if(empty($request->dbacthdr_reference)){
                    throw new \Exception("Please enter reference");
                }
                if(empty($request->dbacthdr_paymode)){
                    throw new \Exception("Please select bank");
                }
            }else if($request->tabform == '#f_tab-forex'){
                throw new \Exception("Forex are disabed");
            }else{
                throw new \Exception("Error request data");
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

    public function gltran_receipt($auditno,$trantype){//PB,RC
        $dbacthdr = DB::table('debtor.dbacthdr')
                            ->where('compcode',session('compcode'))
                            ->where('source','PB')
                            ->where('trantype',$trantype)
                            ->where('auditno',$auditno);

        if($dbacthdr->exists()){
            $dbacthdr_obj = $dbacthdr->first();
            $yearperiod = defaultController::getyearperiod_($dbacthdr_obj->entrydate);
            $paymode_obj = $this->gltran_frompaymode($dbacthdr_obj->paymode);
            $dept_obj = $this->gltran_fromdept($dbacthdr_obj->deptcode);
            $debtormast_obj = $this->gltran_fromdebtormast($dbacthdr_obj->payercode);

            if(strtoupper($trantype) == 'RD'){
                $crcostcode = $debtormast_obj->depccode;
                $cracc = $debtormast_obj->depglacc;
            }else{
                $crcostcode = $debtormast_obj->actdebccode;
                $cracc = $debtormast_obj->actdebglacc;
            }

            $gltran = DB::table('finance.gltran')
                        ->where('compcode','=',session('compcode'))
                        ->where('source','=','PB')
                        ->where('trantype','=',$dbacthdr_obj->trantype)
                        ->where('auditno','=',$dbacthdr_obj->auditno)
                        ->where('lineno_','=',1);

            if($gltran->exists()){
                throw new \Exception("gltran already exists",500);
            }

            //1. buat gltran
            DB::table('finance.gltran')
                ->insert([
                    'compcode' => $dbacthdr_obj->compcode,
                    'auditno' => $dbacthdr_obj->auditno,
                    'lineno_' => 1,
                    'source' => 'PB',
                    'trantype' => $dbacthdr_obj->trantype,
                    'reference' => $dbacthdr_obj->recptno,
                    'description' => $dbacthdr_obj->remark,
                    'year' => $yearperiod->year,
                    'period' => $yearperiod->period,
                    'drcostcode' => $paymode_obj->ccode,
                    'dracc' => $paymode_obj->glaccno,
                    'crcostcode' => $crcostcode,
                    'cracc' => $cracc,
                    'amount' => $dbacthdr_obj->amount,
                    'postdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'adduser' => $dbacthdr_obj->adduser,
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'idno' => null
                ]);

            //2. check glmastdtl utk debit, kalu ada update kalu xde create
            $this->init_glmastdtl(
                        $dept_obj->costcode,//drcostcode
                        $paymode_obj->glaccno,//dracc
                        $crcostcode,//crcostcode
                        $cracc,//cracc
                        $yearperiod,
                        $dbacthdr_obj->amount
                );
        }else{
            throw new \Exception("Dbacthdr doesnt exists",500);
        }
    }

    public function gltran_fromdept($deptcode){
        $obj = DB::table('sysdb.department')
                ->select('costcode')
                ->where('compcode','=',session('compcode'))
                ->where('deptcode','=',$deptcode)
                ->first();

        return $obj;
    }

    public function gltran_frompaymode($paymode){
        $obj = DB::table('debtor.paymode')
                ->select('glaccno','ccode')
                ->where('compcode','=',session('compcode'))
                ->where('source','=','AR')
                ->where('paymode','=',$paymode)
                ->first();

        return $obj;
    }

    public function gltran_fromdebtormast($payercode){
        $obj = DB::table('debtor.debtormast')
                ->select('actdebglacc','actdebccode','depccode','depglacc')
                ->where('compcode','=',session('compcode'))
                ->where('debtorcode','=',$payercode)
                ->first();

        return $obj;
    }

    public function get_debtor_dtl(Request $request){
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
            $responce->outamount = 0.00;
        }

        return json_encode($responce);
    }

    
}
