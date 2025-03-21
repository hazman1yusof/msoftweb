<?php

namespace App\Http\Controllers\material;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use PDF;
use Mail;
use App\Jobs\SendEmailPR;
// use App\Http\Controllers\util\do_util;

class PurchaseRequestController extends defaultController
{   
    var $gltranAmount;

    public function __construct(){
        $this->middleware('auth');
    }

    public function show(Request $request){   
        $reqdept = DB::table('sysdb.department')
                        ->select('deptcode')
                        ->where('compcode',session('compcode'))
                        ->get();

        return view('material.purchaseRequest.purchaseRequest',compact('reqdept'));
    }

    public function show_mobile(Request $request){   
        $oper = strtolower($request->scope);
        $scope = ucfirst(strtolower($request->scope));
        $recno = $request->recno;
        $purreqhd = DB::table('material.purreqhd as prhd')
                        ->select('prhd.idno','prhd.recno','prhd.reqdept','dept_req.description as reqdept_desc','prhd.prdept','dept_pur.description as prdept_desc','prhd.purreqno','prhd.purreqdt','prhd.totamount','prhd.suppcode','supp.Name as suppcode_desc','prhd.recstatus','prhd.remarks')
                        ->leftjoin('sysdb.department as dept_req', function($join) use ($request){
                            $join = $join
                                ->where('dept_req.compcode',session('compcode'))
                                ->on('dept_req.deptcode','prhd.reqdept');
                        })
                        ->leftjoin('sysdb.department as dept_pur', function($join) use ($request){
                            $join = $join
                                ->where('dept_pur.compcode',session('compcode'))
                                ->on('dept_pur.deptcode','prhd.prdept');
                        })
                        ->leftjoin('material.supplier as supp', function($join) use ($request){
                            $join = $join
                                ->where('supp.compcode',session('compcode'))
                                ->on('supp.SuppCode','prhd.suppcode');
                        })
                        ->where('prhd.compcode',session('compcode'))
                        ->where('prhd.recno',$recno)
                        ->first();

        $purreqdt = DB::table('material.purreqdt as prdt')
                        ->select('prdt.lineno_','prdt.pricecode','psrc.description as pricecode_desc','prdt.itemcode','pmast.description as itemcode_desc','prdt.uomcode','uom.description as uom_desc','prdt.pouom','pouom.description as pouom_desc','prdt.qtyrequest','prdt.unitprice','prdt.totamount','prdt.remarks')
                        ->leftjoin('material.pricesource as psrc', function($join) use ($request){
                            $join = $join
                                ->where('psrc.compcode',session('compcode'))
                                ->on('psrc.pricecode','prdt.pricecode');
                        })
                        ->leftjoin('material.productmaster as pmast', function($join) use ($request){
                            $join = $join
                                ->where('pmast.compcode',session('compcode'))
                                ->on('pmast.itemcode','prdt.itemcode');
                        })
                        ->leftjoin('material.uom as uom', function($join) use ($request){
                            $join = $join
                                ->where('uom.compcode',session('compcode'))
                                ->on('uom.uomcode','prdt.uomcode');
                        })
                        ->leftjoin('material.uom as pouom', function($join) use ($request){
                            $join = $join
                                ->where('pouom.compcode',session('compcode'))
                                ->on('pouom.uomcode','prdt.pouom');
                        })
                        ->where('prdt.compcode',session('compcode'))
                        ->where('prdt.recno',$recno)
                        ->get();

        return view('material.purchaseRequest.purchaseRequest_mobile',compact('purreqhd','purreqdt','scope','oper'));
    }

    public function table(Request $request){   
        switch($request->action){
            case 'maintable':
                return $this->maintable($request);
            default:
                return 'error happen..';
        }
    }

    public function maintable(Request $request){
        $scope = $request->scope;
        $table = DB::table('material.purreqhd AS pr')
                    ->select('pr.idno AS purreqhd_idno','pr.compcode AS purreqhd_compcode','pr.reqdept AS purreqhd_reqdept','pr.purreqno AS purreqhd_purreqno','pr.purreqdt AS purreqhd_purreqdt','pr.recno AS purreqhd_recno','pr.reqpersonid AS purreqhd_reqpersonid','pr.prdept AS purreqhd_prdept','pr.authpersonid AS purreqhd_authpersonid','pr.authdate AS purreqhd_authdate','pr.remarks AS purreqhd_remarks','pr.recstatus AS purreqhd_recstatus','pr.subamount AS purreqhd_subamount','pr.amtdisc AS purreqhd_amtdisc','pr.perdisc AS purreqhd_perdisc','pr.totamount AS purreqhd_totamount','pr.adduser AS purreqhd_adduser','pr.adddate AS purreqhd_adddate','pr.upduser AS purreqhd_upduser','pr.upddate AS purreqhd_upddate','pr.cancelby AS purreqhd_cancelby','pr.canceldate AS purreqhd_canceldate','pr.reopenby AS purreqhd_reopenby','pr.reopendate AS purreqhd_reopendate','pr.suppcode AS purreqhd_suppcode','pr.purordno AS purreqhd_purordno','pr.prortdisc AS purreqhd_prortdisc','pr.unit AS purreqhd_unit','pr.trantype AS purreqhd_trantype','pr.TaxAmt AS purreqhd_TaxAmt','pr.requestby AS purreqhd_requestby','pr.requestdate AS purreqhd_requestdate','pr.supportby AS purreqhd_supportby','pr.supportdate AS purreqhd_supportdate','pr.verifiedby AS purreqhd_verifiedby','pr.verifieddate AS purreqhd_verifieddate','pr.approvedby AS purreqhd_approvedby','pr.approveddate AS purreqhd_approveddate','pr.prtype AS purreqhd_prtype','pr.support_remark AS purreqhd_support_remark','pr.verified_remark AS purreqhd_verified_remark','pr.approved_remark AS purreqhd_approved_remark','pr.cancelled_remark AS purreqhd_cancelled_remark','pr.recommended1by AS purreqhd_recommended1by','pr.recommended2by AS purreqhd_recommended2by','pr.recommended1date AS purreqhd_recommended1date','pr.recommended2date AS purreqhd_recommended2date','pr.recommended1_remark AS purreqhd_recommended1_remark','pr.recommended2_remark AS purreqhd_recommended2_remark','pr.assetno AS purreqhd_assetno','s.name AS supplier_name')
                    ->where('pr.compcode',session('compcode'));

        if(!in_array($scope, ['ALL','CANCEL','REOPEN'])){
            if($scope == 'RECOMMENDED1'){
                $table = $table->where('pr.totamount','>=','10000');
            }else if($scope == 'RECOMMENDED2'){
                $table = $table->where('pr.totamount','>=','50000');
            }
        }
        
        $table = $table->join('material.supplier as s', function($join) use ($request){
                $join = $join->where('s.compcode', '=', session('compcode'));
                $join = $join->on('s.SuppCode', '=', 'pr.suppcode');
        });

        if(!in_array($scope, ['ALL','CANCEL','REOPEN'])){
            $table = $table->join('material.queuepr as qpr', function($join) use ($request,$scope){
                $join = $join
                    ->where('qpr.compcode',session('compcode'))
                    ->where('qpr.trantype','<>','DONE')
                    ->on('qpr.recno','pr.recno')
                    ->on('qpr.recstatus','pr.recstatus')
                    ->where('qpr.trantype',$scope);
            });

            $table = $table->join('material.authdtl as adtl', function($join) use ($request,$scope){
                $join = $join
                    ->where('adtl.compcode',session('compcode'))
                    ->where('adtl.authorid',session('username'))
                    ->where('adtl.trantype','PR')
                    ->where('adtl.cando','ACTIVE')
                    ->on('adtl.prtype','qpr.prtype')
                    ->where('adtl.recstatus',$scope)
                    ->where(function ($query) {
                        $query->on('adtl.deptcode','pr.reqdept')
                              ->orWhere('adtl.deptcode', 'ALL');
                    })
                    ->where(function ($query) {
                        $query
                            ->on('pr.totamount','>=','adtl.minlimit')
                            ->on('pr.totamount','<=', 'adtl.maxlimit');
                    });
            });
        }
        
        if(!empty($request->filterCol)){
            foreach ($request->filterCol as $key => $value) {
                $sr = substr(strstr($value,'.'),1); // tukar puerreqhd. ke pr.
                if(empty($sr)){continue;}
                $pieces = explode(".", $request->filterVal[$key], 2);
                if($pieces[0] == 'session'){
                    $table = $table->where('pr.'.$sr,'=',session($pieces[1]));
                }else if($pieces[0] == '<>'){
                    $table = $table->where('pr.'.$sr,'<>',$pieces[1]);
                }else if($pieces[0] == '>'){
                    $table = $table->where('pr.'.$sr,'>',$pieces[1]);
                }else if($pieces[0] == '>='){
                    $table = $table->where('pr.'.$sr,'>=',$pieces[1]);
                }else if($pieces[0] == '<'){
                    $table = $table->where('pr.'.$sr,'<',$pieces[1]);
                }else if($pieces[0] == '<='){
                    $table = $table->where('pr.'.$sr,'<=',$pieces[1]);
                }else if($pieces[0] == 'on'){
                    $table = $table->whereColumn('pr.'.$sr,$pieces[1]);
                }else if($pieces[0] == 'null'){
                    $table = $table->whereNull('pr.'.$sr);
                }else if($pieces[0] == 'notnull'){
                    $table = $table->whereNotNull('pr.'.$sr);
                }else if($pieces[0] == 'raw'){
                    $table = $table->where('pr.'.$sr,'=',DB::raw($pieces[1]));
                }else{
                    $table = $table->where('pr.'.$sr,'=',$request->filterVal[$key]);
                }
            }
        }

        if(!empty($request->WhereInCol[0])){
            foreach ($request->WhereInCol as $key => $value) {
                $sr = substr(strstr($value,'.'),1);
                $table = $table->whereIn('pr.'.$sr,$request->WhereInVal[$key]);
            }
        }
        
        if(!empty($request->searchCol)){
            if($request->searchCol[0] == 'db_invno'){
                $table = $table->Where(function ($table) use ($request){
                        $table->Where('db.invno','like',$request->searchVal[0]);
                });
            }else{
                $table = $table->Where(function ($table) use ($request){
                        $sr = substr(strstr($request->searchCol[0],'_'),1); // tukar puerreqhd_ ke pr.

                        $table->Where('pr.'.$sr,'like',$request->searchVal[0]);
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

    public function form(Request $request){
        DB::enableQueryLog();
        // return $this->request_no('GRN','2FL');
        switch($request->oper){
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
            case 'cancel_from_reject':
                return $this->cancel_from_reject($request);
            case 'reject':
                return $this->reject($request);
            case 'support':
                return $this->support($request);
            case 'verify':
                return $this->verify($request);
            case 'verified':
                return $this->verify($request);
            case 'recommended1':
                return $this->recommended1($request);
            case 'recommended2':
                return $this->recommended2($request);
            case 'approved':
                return $this->approved($request);
            case 'refresh_do':
                return $this->refresh_do($request);
            default:
                return 'Errors happen';
        }
    }

    public function get_productcat($itemcode){
        $query = DB::table('material.product')
                ->select('productcat')
                ->where('compcode','=',session('compcode'))
                ->where('itemcode','=',$itemcode)
                ->first();
        
        return $query->productcat;
    }

    public function add(Request $request){

        if(!empty($request->fixPost)){
            $field = $this->fixPost2($request->field);
            $idno = substr(strstr($request->table_id,'_'),1);
        }else{
            $field = $request->field;
            $idno = $request->table_id;
        }


        DB::beginTransaction();
        
        try {

            $reqdept = DB::table('sysdb.department')
                        ->where('compcode',session('compcode'))
                        ->where('recstatus','ACTIVE')
                        ->where('deptcode',$request->purreqhd_reqdept)
                        ->exists();
            if(!$reqdept){
                throw new \Exception("Request department doesnt exists");
            }

            $prdept = DB::table('sysdb.department')
                        ->where('compcode',session('compcode'))
                        ->where('recstatus','ACTIVE')
                        ->where('purdept','1')
                        ->where('deptcode',$request->purreqhd_prdept)
                        ->exists();
            if(!$prdept){
                throw new \Exception("Purchase department doesnt exists");
            }

            if(!empty($request->purreqhd_suppcode)){
                $suppcode = DB::table('material.supplier')
                            ->where('compcode',session('compcode'))
                            ->where('recstatus','ACTIVE')
                            ->where('suppcode',$request->purreqhd_suppcode)
                            ->exists();
                if(!$suppcode){
                    throw new \Exception("supplier doesnt exists");
                }
            }else{
                throw new \Exception("supplier doesnt exists");
            }

            $table = DB::table("material.purreqhd");

            $array_insert = [
                'trantype' => 'PR',
                'purreqno' => 0,
                'recno' => 0,
                'compcode' => 'DD',
                'unit' => session('unit'),
                'adduser' => session('username'),
                'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                'recstatus' => 'OPEN',
                'reqdept' => strtoupper($request->purreqhd_reqdept),
                'prdept' => strtoupper($request->purreqhd_prdept),
                'purreqdt' => $request->purreqhd_purreqdt,
                'suppcode' => strtoupper($request->purreqhd_suppcode),
                'totamount' => $request->purreqhd_totamount,
                'remarks' => strtoupper($request->purreqhd_remarks),
                'prtype' => $request->purreqhd_prtype,
                'perdisc' => $request->purreqhd_perdisc,
                'amtdisc' => $request->purreqhd_amtdisc,
                'subamount' => $request->purreqhd_subamount,
                'assetno' => $request->purreqhd_assetno,
            ];

            $idno = $table->insertGetId($array_insert);
            
            $totalAmount = 0;

            $responce = new stdClass();
            $responce->purreqno = 0;
            $responce->recno = 0;
            $responce->idno = $idno;
            $responce->totalAmount = $totalAmount;
            $responce->adduser = session('username');
            $responce->adddate = Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d H:i:s');
            echo json_encode($responce);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }

    }

    public function edit(Request $request){
        DB::beginTransaction();

        try {

            if(!empty($request->fixPost)){
                $field = $this->fixPost2($request->field);
                $idno = substr(strstr($request->table_id,'_'),1);
            }else{
                $field = $request->field;
                $idno = $request->table_id;
            }

            $prdept = DB::table('sysdb.department')
                        ->where('compcode',session('compcode'))
                        ->where('recstatus','ACTIVE')
                        ->where('purdept','1')
                        ->where('deptcode',$request->purreqhd_prdept)
                        ->exists();
            if(!$prdept){
                throw new \Exception("Purchase department doesnt exists");
            }

            $suppcode = DB::table('material.supplier')
                        ->where('compcode',session('compcode'))
                        ->where('recstatus','ACTIVE')
                        ->where('suppcode',$request->purreqhd_suppcode)
                        ->exists();
            if(!$suppcode){
                throw new \Exception("supplier doesnt exists");
            }

            $table = DB::table("material.purreqhd");

            $array_update = [
                'unit' => session('unit'),
                'compcode' => session('compcode'),
                'upduser' => session('username'),
                'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                // 'reqdept' => strtoupper($request->purreqhd_reqdept),
                'prdept' => strtoupper($request->purreqhd_prdept),
                'purreqdt' => $request->purreqhd_purreqdt,
                'suppcode' => strtoupper($request->purreqhd_suppcode),
                'totamount' => $request->purreqhd_totamount,
                'remarks' => strtoupper($request->purreqhd_remarks),
                'perdisc' => $request->purreqhd_perdisc,
                'amtdisc' => $request->purreqhd_amtdisc,
                'subamount' => $request->purreqhd_subamount,
                // 'prtype' => $request->purreqhd_prtype,
                'assetno' => $request->purreqhd_assetno,
            ];

            //////////where//////////
            $table = $table->where('idno','=',$request->purreqhd_idno);

            $obj_ = $table->first();

            if(!in_array($obj_->recstatus, ['OPEN','INCOMPLETED','SUPPORT','PREPARED','VERIFIED','APPROVED'])){
                throw new \Exception("Cant Edit this document, status ERROR!");
            }

            $table->update($array_update);

            $responce = new stdClass();
            $responce->totalAmount = $request->purreqhd_totamount;
            $responce->upduser = session('username');
            $responce->upddate = Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d H:i:s');
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

        try{

            foreach ($request->idno_array as $value){

                $purreqhd = DB::table("material.purreqhd")
                    ->where('idno','=',$value);

                $purreqhd_get = $purreqhd->first();

                if($purreqhd_get->recstatus != 'OPEN'){
                    continue;
                }

                //nak buat qtyrequest1S and qtybalance1S
                $purreqdt = DB::table("material.purreqdt")
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$purreqhd_get->recno)
                    ->get();

                foreach ($purreqdt as $key_reqdt => $value_reqdt){

                    $convfactorUOM_obj = DB::table('material.uom')
                        ->select('convfactor')
                        ->where('compcode','=',session('compcode'))
                        ->where('uomcode','=',$value_reqdt->uomcode)
                        ->first();
                    $convfactorUOM = $convfactorUOM_obj->convfactor;

                    $qtyrequest1S = $value_reqdt->qtyrequest * $convfactorUOM;
                    DB::table("material.purreqdt")
                        ->where('idno','=',$value_reqdt->idno)
                        ->update([
                            'qtyrequest1S'=>$qtyrequest1S,
                            'qtybalance1S'=>$qtyrequest1S
                        ]);

                }

                if(strtoupper($purreqhd_get->prtype) == 'STOCK'){
                    $prtype = 'STOCK';
                }else{
                    $prtype = 'OTHERS';
                }
                
                // $authorise_use = $authorise->first();
                DB::table("material.queuepr")
                    ->insert([
                        'compcode' => session('compcode'),
                        'recno' => $purreqhd_get->recno,
                        'AuthorisedID' => session('username'),
                        'deptcode' => $purreqhd_get->reqdept,
                        'prtype' => $prtype,
                        'recstatus' => 'PREPARED',
                        'trantype' => 'SUPPORT',
                        'adduser' => session('username'),
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur")
                    ]);

                // 3. update status to posted
                $purreqhd->update([
                        'requestby' => session('username'),
                        'requestdate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'recstatus' => 'PREPARED'
                    ]);

                DB::table("material.purreqdt")
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$purreqhd_get->recno)
                    ->update([
                        'recstatus' => 'PREPARED',
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                    ]);

                // 5. email and whatsapp
                $data = new stdClass();
                $data->status = 'SUPPORT';
                $data->deptcode = $purreqhd_get->reqdept;
                $data->purreqno = $purreqhd_get->purreqno;
                $data->email_to = 'hazman.yusof@gmail.com';
                $data->whatsapp = '01123090948';

                $this->sendemail('SUPPORT',$purreqhd_get->recno);
            }

            DB::commit();
        
        } catch (\Exception $e) {
            DB::rollback();
            return response($e->getMessage(), 500);
        }
    }

    public function reopen(Request $request){

        DB::beginTransaction();

        try{

            foreach ($request->idno_array as $value){

                $purreqhd = DB::table("material.purreqhd")
                    ->where('idno','=',$value);

                $purreqhd_get = $purreqhd->first();
                if(!in_array($purreqhd_get->recstatus, ['CANCELLED','PREPARED','SUPPORT','VERIFIED','APPROVED'])){
                    continue;
                }

                $purreqhd->update([
                    'recstatus' => 'OPEN',
                    'requestby' => null,
                    'requestdate' => null,
                    'supportby' => null,
                    'supportdate' => null,
                    'support_remark' => null,
                    'verifiedby' => null,
                    'verifieddate' => null,
                    'verified_remark' => null,
                    'approvedby' => null,
                    'approveddate' => null,
                    'approved_remark' => null,
                ]);

                DB::table("material.purreqdt")
                    ->where('recno','=',$purreqhd_get->recno)
                    ->update([
                        'recstatus' => 'OPEN',
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    ]);

                DB::table("material.queuepr")
                    ->where('recno','=',$purreqhd_get->recno)
                    ->delete();

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

                $purreqhd = DB::table("material.purreqhd")
                    ->where('idno','=',$value);

                $purreqhd_get = $purreqhd->first();
                if($purreqhd_get->recstatus != 'OPEN'){
                    continue;
                }

                $purreqhd_update = [
                    'recstatus' => 'CANCELLED',
                    'cancelby' => session('username'),
                    'canceldate' => Carbon::now("Asia/Kuala_Lumpur"),
                ];

                if(!empty($request->remarks)){
                    $purreqhd_update['cancelled_remark'] = $request->remarks;
                }

                $purreqhd->update($purreqhd_update);

                DB::table("material.purreqdt")
                    ->where('recno','=',$purreqhd_get->recno)
                    ->update([
                        'recstatus' => 'CANCELLED'
                    ]);

                // DB::table("material.queuepr")
                //     ->where('recno','=',$purreqhd_get->recno)
                //     ->delete();

            }
           
            DB::commit();
        
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function cancel_from_reject(Request $request){
        DB::beginTransaction();

        try{

            foreach ($request->idno_array as $value){

                $purreqhd = DB::table("material.purreqhd")
                    ->where('idno','=',$value);

                $purreqhd_get = $purreqhd->first();
                if($purreqhd_get->recstatus != 'CANCELLED'){
                    continue;
                }

                DB::table("material.queuepr")
                    ->where('recno','=',$purreqhd_get->recno)
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

                $purreqhd = DB::table("material.purreqhd")
                    ->where('idno','=',$value);

                $purreqhd_get = $purreqhd->first();
                if(!in_array($purreqhd_get->recstatus, ['SUPPORT','VERIFIED','RECOMMENDED1','RECOMMENDED2','PREPARED'])){
                    continue;
                }

                if(strtoupper($purreqhd_get->prtype) == 'STOCK'){
                    $prtype = 'STOCK';
                }else{
                    $prtype = 'OTHERS';
                }

                $authorise = DB::table('material.authdtl')
                    ->where('authorid','=',session('username'))
                    ->where('compcode','=',session('compcode'))
                    ->where('trantype','=','PR')
                    ->where('cando','=', 'ACTIVE')
                    ->where('prtype','=',$prtype)
                    ->where('deptcode','=',$purreqhd_get->reqdept)
                    ->where('maxlimit','>=',$purreqhd_get->totamount)
                    ->whereIn('recstatus',['SUPPORT','VERIFIED','RECOMMENDED1','RECOMMENDED2','APPROVED']);

                if(!$authorise->exists()){

                    $authorise = DB::table('material.authdtl')
                        ->where('authorid','=',session('username'))
                        ->where('compcode','=',session('compcode'))
                        ->where('trantype','=','PR')
                        ->where('cando','=', 'ACTIVE')
                        ->where('deptcode','=','ALL')
                        ->where('deptcode','=','all')
                        ->where('prtype','=',$prtype)
                        ->where('maxlimit','>=',$purreqhd_get->totamount)
                        ->whereIn('recstatus',['SUPPORT','VERIFIED','RECOMMENDED1','RECOMMENDED2','APPROVED']);

                        if(!$authorise->exists()){
                            throw new \Exception("The user doesnt have authority",500);
                        }
                        
                }

                $purreqhd_update = [
                    'recstatus' => 'CANCELLED',
                    'cancelby' => session('username'),
                    'canceldate' => Carbon::now("Asia/Kuala_Lumpur"),
                ];

                if(!empty($request->remarks)){
                    $purreqhd_update['cancelled_remark'] = $request->remarks;
                }

                $purreqhd->update($purreqhd_update);

                DB::table("material.purreqdt")
                    ->where('recno','=',$purreqhd_get->recno)
                    ->update([
                        'recstatus' => 'CANCELLED'
                    ]);

                DB::table("material.queuepr")
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$purreqhd_get->recno)
                    ->update([
                        'AuthorisedID' => $purreqhd_get->adduser,
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

    public function support(Request $request){
        DB::beginTransaction();

        try{

            foreach ($request->idno_array as $value){

                $purreqhd = DB::table("material.purreqhd")
                    ->where('idno','=',$value);

                $purreqhd_get = $purreqhd->first();

                if($purreqhd_get->recstatus != 'PREPARED'){
                    continue;
                }

                if(strtoupper($purreqhd_get->prtype) == 'STOCK'){
                    $prtype = 'STOCK';
                }else{
                    $prtype = 'OTHERS';
                }

                // if(!$this->skip_authorization_2($request,$purreqhd_get)){
                    $authorise = DB::table('material.authdtl')
                        ->where('authorid','=',session('username'))
                        ->where('compcode','=',session('compcode'))
                        ->where('trantype','=','PR')
                        ->where('cando','=', 'ACTIVE')
                        ->where('recstatus','=','SUPPORT')
                        ->where('prtype','=',$prtype)
                        ->where('deptcode','=',$purreqhd_get->reqdept)
                        ->where('minlimit','<=',$purreqhd_get->totamount)
                        ->where('maxlimit','>=',$purreqhd_get->totamount);

                    if(!$authorise->exists()){

                        $authorise = DB::table('material.authdtl')
                            ->where('authorid','=',session('username'))
                            ->where('compcode','=',session('compcode'))
                            ->where('trantype','=','PR')
                            ->where('cando','=', 'ACTIVE')
                            ->where('recstatus','=','SUPPORT')
                            ->where('deptcode','=','ALL')
                            ->where('deptcode','=','all')
                            ->where('prtype','=',$prtype)
                            ->where('minlimit','<=',$purreqhd_get->totamount)
                            ->where('maxlimit','>=',$purreqhd_get->totamount);

                            if(!$authorise->exists()){
                                throw new \Exception("The user doesnt have authority",500);
                            }
                            
                    }

                    $purreqhd_update = [
                        'supportby' => session('username'),
                        'supportdate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'recstatus' => 'SUPPORT'
                    ];

                    if(!empty($request->remarks)){
                        $purreqhd_update['support_remark'] = $request->remarks;
                    }

                    $purreqhd->update($purreqhd_update);

                    DB::table("material.purreqdt")
                        ->where('compcode','=',session('compcode'))
                        ->where('recno','=',$purreqhd_get->recno)
                        ->update([
                            'recstatus' => 'SUPPORT',
                            'upduser' => session('username'),
                            'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                        ]);

                    DB::table("material.queuepr")
                        ->where('compcode','=',session('compcode'))
                        ->where('recno','=',$purreqhd_get->recno)
                        ->update([
                            'AuthorisedID' => session('username'),
                            'recstatus' => 'SUPPORT',
                            'trantype' => 'VERIFIED',
                            'upduser' => session('username'),
                            'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                        ]);
                // }


                // 4. email and whatsapp
                $data = new stdClass();
                $data->status = 'SUPPORT';
                $data->deptcode = $purreqhd_get->reqdept;
                $data->purreqno = $purreqhd_get->purreqno;
                $data->email_to = 'hazman.yusof@gmail.com';
                $data->whatsapp = '01123090948';
            
                $this->sendemail('VERIFIED',$purreqhd_get->recno);

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

            $recomm_limit = DB::table('sysdb.sysparam')
                            ->select('pvalue1','pvalue2')
                            ->where('compcode',session('compcode'))
                            ->where('source','IV')
                            ->where('trantype','PRLIMIT');

            if($recomm_limit->exists()){
                $pvalue1 = $recomm_limit->first()->pvalue1;
                $pvalue2 = $recomm_limit->first()->pvalue2;
            }else{
                $pvalue1 = 0;
                $pvalue2 = 0;
            }

            foreach ($request->idno_array as $value){

                $purreqhd = DB::table("material.purreqhd")
                    ->where('idno','=',$value);

                $purreqhd_get = $purreqhd->first();

                if($purreqhd_get->recstatus != 'SUPPORT'){
                    continue;
                }

                if(strtoupper($purreqhd_get->prtype) == 'STOCK'){
                    $prtype = 'STOCK';
                }else{
                    $prtype = 'OTHERS';
                }

                // if(!$this->skip_authorization_2($request,$purreqhd_get)){
                    $authorise = DB::table('material.authdtl')
                        ->where('authorid','=',session('username'))
                        ->where('compcode','=',session('compcode'))
                        ->where('trantype','=','PR')
                        ->where('cando','=', 'ACTIVE')
                        ->where('recstatus','=','VERIFIED')
                        ->where('prtype','=',$prtype)
                        ->where('deptcode','=',$purreqhd_get->reqdept)
                        ->where('minlimit','<=',$purreqhd_get->totamount)
                        ->where('maxlimit','>=',$purreqhd_get->totamount);

                    if(!$authorise->exists()){

                        $authorise = DB::table('material.authdtl')
                            ->where('authorid','=',session('username'))
                            ->where('compcode','=',session('compcode'))
                            ->where('trantype','=','PR')
                            ->where('cando','=', 'ACTIVE')
                            ->where('recstatus','=','VERIFIED')
                            ->where('deptcode','=','ALL')
                            ->where('deptcode','=','all')
                            ->where('prtype','=',$prtype)
                            ->where('minlimit','<=',$purreqhd_get->totamount)
                            ->where('maxlimit','>=',$purreqhd_get->totamount);

                            if(!$authorise->exists()){
                                throw new \Exception("The user doesnt have authority",500);
                            }
                            
                    }

                    $purreqhd_update = [
                        'verifiedby' => session('username'),
                        'verifieddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'recstatus' => 'VERIFIED'
                    ];

                    if(!empty($request->remarks)){
                        $purreqhd_update['verified_remark'] = $request->remarks;
                    }

                    $purreqhd->update($purreqhd_update);

                    DB::table("material.purreqdt")
                        ->where('compcode','=',session('compcode'))
                        ->where('recno','=',$purreqhd_get->recno)
                        ->update([
                            'recstatus' => 'VERIFIED'
                        ]);

                    if(!empty($pvalue1) && $purreqhd_get->totamount >= $pvalue1){

                        DB::table("material.queuepr")
                            ->where('compcode','=',session('compcode'))
                            ->where('recno','=',$purreqhd_get->recno)
                            ->update([
                                'AuthorisedID' => session('username'),
                                'recstatus' => 'VERIFIED',
                                'trantype' => 'RECOMMENDED1',
                                'upduser' => session('username'),
                                'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                            ]);
                    }else{

                        DB::table("material.queuepr")
                            ->where('compcode','=',session('compcode'))
                            ->where('recno','=',$purreqhd_get->recno)
                            ->update([
                                'AuthorisedID' => session('username'),
                                'recstatus' => 'VERIFIED',
                                'trantype' => 'APPROVED',
                                'upduser' => session('username'),
                                'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                            ]);

                    }

                // }


                // 4. email and whatsapp
                $data = new stdClass();
                $data->status = 'APPROVED';
                $data->deptcode = $purreqhd_get->reqdept;
                $data->purreqno = $purreqhd_get->purreqno;
                $data->email_to = 'hazman.yusof@gmail.com';
                $data->whatsapp = '01123090948';

                $this->sendemail('APPROVED',$purreqhd_get->recno);

            }

           
            DB::commit();
        
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function recommended1(Request $request){
         DB::beginTransaction();

        try{

            $recomm_limit = DB::table('sysdb.sysparam')
                            ->select('pvalue1','pvalue2')
                            ->where('compcode',session('compcode'))
                            ->where('source','IV')
                            ->where('trantype','PRLIMIT');

            if($recomm_limit->exists()){
                $pvalue1 = $recomm_limit->first()->pvalue1;
                $pvalue2 = $recomm_limit->first()->pvalue2;
            }else{
                $pvalue1 = 0;
                $pvalue2 = 0;
            }

            foreach ($request->idno_array as $value){

                $purreqhd = DB::table("material.purreqhd")
                    ->where('idno','=',$value);

                $purreqhd_get = $purreqhd->first();

                if($purreqhd_get->recstatus != 'VERIFIED'){
                    continue;
                }

                if(strtoupper($purreqhd_get->prtype) == 'STOCK'){
                    $prtype = 'STOCK';
                }else{
                    $prtype = 'OTHERS';
                }

                // if(!$this->skip_authorization_2($request,$purreqhd_get)){
                    $authorise = DB::table('material.authdtl')
                        ->where('authorid','=',session('username'))
                        ->where('compcode','=',session('compcode'))
                        ->where('trantype','=','PR')
                        ->where('cando','=', 'ACTIVE')
                        ->where('recstatus','=','RECOMMENDED1')
                        ->where('deptcode','=',$purreqhd_get->reqdept)
                        ->where('prtype','=',$prtype)
                        ->where('minlimit','<=',$purreqhd_get->totamount)
                        ->where('maxlimit','>=',$purreqhd_get->totamount);

                    if(!$authorise->exists()){

                        $authorise = DB::table('material.authdtl')
                            ->where('authorid','=',session('username'))
                            ->where('compcode','=',session('compcode'))
                            ->where('trantype','=','PR')
                            ->where('cando','=', 'ACTIVE')
                            ->where('recstatus','=','RECOMMENDED1')
                            ->where('deptcode','=','ALL')
                            ->where('deptcode','=','all')
                            ->where('prtype','=',$prtype)
                            ->where('minlimit','<=',$purreqhd_get->totamount)
                            ->where('maxlimit','>=',$purreqhd_get->totamount);

                            if(!$authorise->exists()){
                                throw new \Exception("The user doesnt have authority",500);
                            }
                            
                    }

                    $purreqhd_update = [
                        'recommended1by' => session('username'),
                        'recommended1date' => Carbon::now("Asia/Kuala_Lumpur"),
                        'recstatus' => 'RECOMMENDED1'
                    ];

                    if(!empty($request->remarks)){
                        $purreqhd_update['recommended1_remark'] = $request->remarks;
                    }

                    $purreqhd->update($purreqhd_update);

                    DB::table("material.purreqdt")
                        ->where('compcode','=',session('compcode'))
                        ->where('recno','=',$purreqhd_get->recno)
                        ->update([
                            'recstatus' => 'RECOMMENDED1'
                        ]);

                    if(!empty($pvalue2) && $purreqhd_get->totamount >= $pvalue2){

                        DB::table("material.queuepr")
                            ->where('compcode','=',session('compcode'))
                            ->where('recno','=',$purreqhd_get->recno)
                            ->update([
                                'AuthorisedID' => session('username'),
                                'recstatus' => 'RECOMMENDED1',
                                'trantype' => 'RECOMMENDED2',
                                'upduser' => session('username'),
                                'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                            ]);
                    }else{

                        DB::table("material.queuepr")
                            ->where('compcode','=',session('compcode'))
                            ->where('recno','=',$purreqhd_get->recno)
                            ->update([
                                'AuthorisedID' => session('username'),
                                'recstatus' => 'RECOMMENDED1',
                                'trantype' => 'APPROVED',
                                'upduser' => session('username'),
                                'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                            ]);

                    }

                // }


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

    public function recommended2(Request $request){
         DB::beginTransaction();

        try{

            foreach ($request->idno_array as $value){

                $purreqhd = DB::table("material.purreqhd")
                    ->where('idno','=',$value);

                $purreqhd_get = $purreqhd->first();

                if($purreqhd_get->recstatus != 'RECOMMENDED1'){
                    continue;
                }

                if(strtoupper($purreqhd_get->prtype) == 'STOCK'){
                    $prtype = 'STOCK';
                }else{
                    $prtype = 'OTHERS';
                }

                // if(!$this->skip_authorization_2($request,$purreqhd_get)){
                    $authorise = DB::table('material.authdtl')
                        ->where('authorid','=',session('username'))
                        ->where('compcode','=',session('compcode'))
                        ->where('trantype','=','PR')
                        ->where('cando','=', 'ACTIVE')
                        ->where('recstatus','=','RECOMMENDED2')
                        ->where('deptcode','=',$purreqhd_get->reqdept)
                        ->where('prtype','=',$prtype)
                        ->where('minlimit','<=',$purreqhd_get->totamount)
                        ->where('maxlimit','>=',$purreqhd_get->totamount);

                    if(!$authorise->exists()){

                        $authorise = DB::table('material.authdtl')
                            ->where('authorid','=',session('username'))
                            ->where('compcode','=',session('compcode'))
                            ->where('trantype','=','PR')
                            ->where('cando','=', 'ACTIVE')
                            ->where('recstatus','=','RECOMMENDED2')
                            ->where('deptcode','=','ALL')
                            ->where('deptcode','=','all')
                            ->where('prtype','=',$prtype)
                            ->where('minlimit','<=',$purreqhd_get->totamount)
                            ->where('maxlimit','>=',$purreqhd_get->totamount);

                            if(!$authorise->exists()){
                                throw new \Exception("The user doesnt have authority",500);
                            }
                            
                    }

                    $purreqhd_update = [
                        'recommended2by' => session('username'),
                        'recommended2date' => Carbon::now("Asia/Kuala_Lumpur"),
                        'recstatus' => 'RECOMMENDED2'
                    ];

                    if(!empty($request->remarks)){
                        $purreqhd_update['recommended2_remark'] = $request->remarks;
                    }

                    $purreqhd->update($purreqhd_update);

                    DB::table("material.purreqdt")
                        ->where('compcode','=',session('compcode'))
                        ->where('recno','=',$purreqhd_get->recno)
                        ->update([
                            'recstatus' => 'RECOMMENDED2'
                        ]);


                    DB::table("material.queuepr")
                        ->where('compcode','=',session('compcode'))
                        ->where('recno','=',$purreqhd_get->recno)
                        ->update([
                            'AuthorisedID' => session('username'),
                            'recstatus' => 'RECOMMENDED2',
                            'trantype' => 'APPROVED',
                            'upduser' => session('username'),
                            'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                        ]);

                // }


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

                if(!in_array(strtoupper($purreqhd_get->recstatus), ['VERIFIED','RECOMMENDED1','RECOMMENDED2'])){
                    continue;
                }

                // if(!$this->skip_authorization($request,$purreqhd_get->reqdept,$value)){

                if(strtoupper($purreqhd_get->prtype) == 'STOCK'){
                    $prtype = 'STOCK';
                }else{
                    $prtype = 'OTHERS';
                }

                $authorise = DB::table('material.authdtl')
                    ->where('authorid','=',session('username'))
                    ->where('compcode','=',session('compcode'))
                    ->where('trantype','=','PR')
                    ->where('cando','=', 'ACTIVE')
                    ->where('recstatus','=','APPROVED')
                    ->where('prtype','=',$prtype)
                    ->where('deptcode','=',$purreqhd_get->reqdept)
                    ->where('minlimit','<=',$purreqhd_get->totamount)
                    ->where('maxlimit','>=',$purreqhd_get->totamount);

                if(!$authorise->exists()){

                    $authorise = DB::table('material.authdtl')
                        ->where('authorid','=',session('username'))
                        ->where('compcode','=',session('compcode'))
                        ->where('trantype','=','PR')
                        ->where('cando','=', 'ACTIVE')
                        ->where('recstatus','=','APPROVED')
                        ->where('prtype','=',$prtype)
                        ->where('deptcode','=','ALL')
                        ->where('deptcode','=','all')
                        ->where('minlimit','<=',$purreqhd_get->totamount)
                        ->where('maxlimit','>=',$purreqhd_get->totamount);

                        if(!$authorise->exists()){
                            throw new \Exception("The user doesnt have authority",500);
                        }
                        
                }

                $purreqhd_update = [
                    'approvedby' => session('username'),
                    'approveddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'recstatus' => 'APPROVED'
                ];

                if(!empty($request->remarks)){
                    $purreqhd_update['approved_remark'] = $request->remarks;
                }

                $purreqhd->update($purreqhd_update);

                DB::table("material.purreqdt")
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$purreqhd_get->recno)
                    ->update([
                        'recstatus' => 'APPROVED',
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                    ]);

                DB::table("material.queuepr")
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$purreqhd_get->recno)
                    ->update([
                        'AuthorisedID' => session('username'),
                        'recstatus' => 'APPROVED',
                        'trantype' => 'DONE',
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                    ]);

                // }


                // 4. email and whatsapp
                $data = new stdClass();
                $data->status = 'APPROVED';
                $data->deptcode = $purreqhd_get->reqdept;
                $data->purreqno = $purreqhd_get->purreqno;
                $data->email_to = 'hazman.yusof@gmail.com';
                $data->whatsapp = '01123090948';

                //$this->sendemail($data);

                // $purreqhd = DB::table("material.purreqhd")
                //     ->where('idno','=',$value);

                // $purreqhd_get = $purreqhd->first();

                // $purreqhd->update([
                //         'approveddate' => Carbon::now("Asia/Kuala_Lumpur"),
                //         'recstatus' => 'APPROVED'
                //     ]);

                // DB::table("material.purreqdt")
                //     ->where('recno','=',$purreqhd_get->recno)
                //     ->update([
                //         'recstatus' => 'APPROVED',
                //         'upduser' => session('username'),
                //         'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                //     ]);

                // DB::table("material.queuepr")
                //     ->where('recno','=',$purreqhd_get->recno)
                //     ->update([
                //         'recstatus' => 'APPROVED',
                //         'trantype' => 'DONE',
                //         'adduser' => session('username'),
                //         'adddate' => Carbon::now("Asia/Kuala_Lumpur")
                //     ]);

            }

           
            DB::commit();
        
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function showpdf(Request $request){
        $recno = $request->recno;
        
        if(!$recno){
            abort(404);
        }
        
        $purreqhd = DB::table('material.purreqhd as ph')
                    ->select('ph.idno','ph.compcode','ph.reqdept','ph.purreqno','ph.purreqdt','ph.recno','ph.reqpersonid','ph.prdept','ph.authpersonid','ph.authdate','ph.remarks','ph.recstatus','ph.subamount','ph.amtdisc','ph.perdisc','ph.totamount','ph.adduser','ph.adddate','ph.upduser','ph.upddate','ph.cancelby','ph.canceldate','ph.reopenby','ph.reopendate','ph.suppcode','ph.purordno','ph.prortdisc','ph.unit','ph.trantype','ph.TaxAmt','ph.requestby','ph.requestdate','ph.supportby','ph.supportdate','ph.verifiedby','ph.verifieddate','ph.approvedby','ph.approveddate','ph.support_remark','ph.verified_remark','ph.approved_remark','ph.cancelled_remark','ph.recommended1by','ph.recommended2by','ph.recommended1date','ph.recommended2date','ph.recommended1_remark','ph.recommended2_remark','ph.prtype','u.name as requestby_name','u.designation as requestby_dsg','s.name as supportby_name','s.designation as supportby_dsg','e.name as verifiedby_name','e.designation as verifiedby_dsg','r.name as recommended1by_name','r.designation as recommended1by_dsg','us.name as recommended2by_name','us.designation as recommended2by_dsg','ur.name as approvedby_name','ur.designation as approvedby_dsg','d.description as reqdept_name')
                    ->leftJoin('sysdb.users as u', function ($join) use ($request){
                        $join = $join->on('u.username', '=', 'ph.requestby')
                                    ->where('u.compcode','=',session('compcode'));
                    })
                    ->leftJoin('sysdb.users as s', function ($join) use ($request){
                        $join = $join->on('s.username', '=', 'ph.supportby')
                                    ->where('s.compcode','=',session('compcode'));
                    })
                    ->leftJoin('sysdb.users as e', function ($join) use ($request){
                        $join = $join->on('e.username', '=', 'ph.verifiedby')
                                    ->where('e.compcode','=',session('compcode'));
                    })
                    ->leftJoin('sysdb.users as r', function ($join) use ($request){
                        $join = $join->on('r.username', '=', 'ph.recommended1by')
                                    ->where('r.compcode','=',session('compcode'));
                    })
                    ->leftJoin('sysdb.users as us', function ($join) use ($request){
                        $join = $join->on('us.username', '=', 'ph.recommended2by')
                                    ->where('us.compcode','=',session('compcode'));
                    })
                    ->leftJoin('sysdb.users as ur', function ($join) use ($request){
                        $join = $join->on('ur.username', '=', 'ph.approvedby')
                                    ->where('ur.compcode','=',session('compcode'));
                    })
                    ->leftJoin('sysdb.department as d', function ($join) use ($request){
                        $join = $join->on('d.deptcode', '=', 'ph.reqdept')
                                    ->where('d.compcode','=',session('compcode'));
                    })
                    ->where('ph.recno','=',$recno)
                    ->first();
        // dd($purreqhd);
        
        $purreqdt = DB::table('material.purreqdt AS prdt', 'material.productmaster AS p', 'material.uom as u')
            ->select('prdt.compcode', 'prdt.recno', 'prdt.lineno_', 'prdt.pricecode', 'prdt.itemcode', 'p.description', 'prdt.uomcode', 'prdt.pouom', 'prdt.qtyrequest', 'prdt.unitprice', 'prdt.taxcode', 'prdt.perdisc', 'prdt.amtdisc', 'prdt.amtslstax as tot_gst','prdt.netunitprice', 'prdt.totamount','prdt.amount', 'prdt.rem_but AS remarks_button', 'prdt.remarks', 'prdt.recstatus', 'prdt.unit', 'u.description as uom_desc')
            ->leftJoin('material.productmaster as p', 'prdt.itemcode', '=', 'p.itemcode')
            ->leftJoin('material.uom as u', 'prdt.uomcode', '=', 'u.uomcode')
            ->where('prdt.compcode','=',session('compcode'))
            ->where('p.compcode','=',session('compcode'))
            ->where('u.compcode','=',session('compcode'))
            ->where('recno','=',$recno)
            ->get();
        // dd($purreqdt);
                    
        $company = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();
        
        $supplier = DB::table('material.supplier')
            ->where('compcode','=',session('compcode'))
            ->where('SuppCode','=',$purreqhd->suppcode)
            ->first();

        $reqdept = DB::table('material.deldept')
            ->where('compcode','=',session('compcode'))
            ->where('deptcode','=',$purreqhd->reqdept)
            ->first();

        $total_tax = DB::table('material.purreqdt')
            ->where('compcode','=',session('compcode'))
            ->where('recno','=',$recno)
            ->sum('amtslstax');
        
        $total_discamt = DB::table('material.purreqdt')
            ->where('compcode','=',session('compcode'))
            ->where('recno','=',$recno)
            ->sum('amtdisc');


        $totamount_expld = explode(".", (float)$purreqhd->totamount);

        // $totamt_bm_rm = $this->convertNumberToWordBM($totamount_expld[0])." RINGGIT ";
        // $totamt_bm = $totamt_bm_rm." SAHAJA";

        // if(count($totamount_expld) > 1){
        //     $totamt_bm_sen = $this->convertNumberToWordBM($totamount_expld[1])." SEN";
        //     $totamt_bm = $totamt_bm_rm.$totamt_bm_sen." SAHAJA";
        // }
        
        $totamt_eng_rm = $this->convertNumberToWordENG($totamount_expld[0])."";
        $totamt_eng = $totamt_eng_rm. " ONLY";

        if(count($totamount_expld) > 1){
            $totamt_eng_sen = $this->convertNumberToWordENG($totamount_expld[1]). "CENT";
            $totamt_eng = $totamt_eng_rm.$totamt_eng_sen. " ONLY";
        }

        $attachment_files =$this->get_attachment_files($purreqhd->idno);

        $print_connection = $this->print_connection($purreqhd);

        // $pdf = PDF::loadView('material.purchaseRequest.purchaseRequest_pdf',compact('purreqhd','purreqdt','totamt_bm','company', 'supplier', 'prdept', 'total_tax', 'total_discamt'));
        // return $pdf->stream();      
        
        return view('material.purchaseRequest.purchaseRequest_pdfmake',compact('purreqhd','purreqdt','totamt_eng','company','supplier','reqdept','total_tax','total_discamt','attachment_files'));
    }

    function sendemail($trantype,$recno){
        // $trantype = 'SUPPORT';
        // $recno = '64';
        $qpr = DB::table('material.queuepr as qpr')
                    ->select('qpr.trantype','adtl.authorid','prhd.recno','prhd.reqdept','prhd.purreqno','prhd.purreqdt','prhd.recstatus','prhd.totamount','prhd.adduser','users.email')
                    ->join('material.authdtl as adtl', function($join){
                        $join = $join
                            ->where('adtl.compcode',session('compcode'))
                            // ->where('adtl.authorid',session('username'))
                            ->where('adtl.trantype','PR')
                            ->where('adtl.cando','ACTIVE')
                            ->on('adtl.prtype','qpr.prtype')
                            ->on('adtl.recstatus','qpr.trantype')
                            ->where(function ($query) {
                                $query->on('adtl.deptcode','qpr.deptcode')
                                      ->orWhere('adtl.deptcode', 'ALL');
                            });
                    })
                    ->join('material.purreqhd as prhd', function($join){
                        $join = $join
                            ->where('prhd.compcode',session('compcode'))
                            ->on('prhd.recno','qpr.recno')
                            ->on('prhd.recstatus','qpr.recstatus')
                            ->where(function ($query) {
                                $query
                                    ->on('prhd.totamount','>=','adtl.minlimit')
                                    ->on('prhd.totamount','<=', 'adtl.maxlimit');
                            });
                    })
                    ->join('sysdb.users as users', function($join){
                        $join = $join
                            ->where('users.compcode',session('compcode'))
                            // ->where('users.email','HAZMAN.YUSOF@GMAIL.COM')
                            ->on('users.username','adtl.authorid');
                    })
                    ->where('qpr.compcode',session('compcode'))
                    ->where('qpr.trantype',$trantype)
                    ->where('qpr.recno',$recno)
                    ->get();
                    
        SendEmailPR::dispatch($qpr);
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
            // $array_update['requestby'] = session('username');
            // $array_update['requestdate'] = Carbon::now("Asia/Kuala_Lumpur");
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
                        ->where('recno','=',$purreqhd_get->recno);

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

    function skip_authorization_2(Request $request, $prhd){

        $idno = $prhd->idno;
        $recno = $prhd->recno;
        $deptcode = $prhd->reqdept;
        $maxlimit = $prhd->totamount;
        if($maxlimit == null){
            $maxlimit = 0;
        }
        $authdtl = DB::table('material.authdtl')
                    ->where('authorid','=',session('username'))
                    ->where('trantype','=','PR')
                    ->where('maxlimit','>=',$maxlimit)
                    ->where(function($q) use ($deptcode) {
                          $q->where('deptcode', $deptcode)
                            ->orWhere('deptcode', 'ALL');
                      });

        if($authdtl->count() > 0){
            $array_update = [];
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

            if(!empty($array_update['approvedby'])){
                $recstatus = 'APPROVED';
                $recstatus_after = 'DONE';
                $array_update['recstatus'] = 'APPROVED';
                $array_update['supportby'] = session('username');
                $array_update['supportdate'] = Carbon::now("Asia/Kuala_Lumpur");
                $array_update['verifiedby'] = session('username');
                $array_update['verifieddate'] = Carbon::now("Asia/Kuala_Lumpur");
                $array_update['approvedby'] = session('username');
                $array_update['approveddate'] = Carbon::now("Asia/Kuala_Lumpur");
            }else if(!empty($array_update['verifiedby'])){
                $recstatus = 'VERIFIED';
                $recstatus_after = 'APPROVED';
                $array_update['recstatus'] = 'VERIFIED';
                $array_update['supportby'] = session('username');
                $array_update['supportdate'] = Carbon::now("Asia/Kuala_Lumpur");
                $array_update['verifiedby'] = session('username');
                $array_update['verifieddate'] = Carbon::now("Asia/Kuala_Lumpur");
            }else if(!empty($array_update['supportby'])){
                $recstatus = 'SUPPORT';
                $recstatus_after = 'VERIFIED';
                $array_update['recstatus'] = 'SUPPORT';
                $array_update['supportby'] = session('username');
                $array_update['supportdate'] = Carbon::now("Asia/Kuala_Lumpur");
            }else{
                return false;
            }

            DB::table("material.purreqhd")
                ->where('idno',$idno)
                ->update($array_update);

            DB::table("material.purreqdt")
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$recno)
                ->update([
                    'recstatus' => $recstatus,
                    'upduser' => session('username'),
                    'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                ]);

                // dd($recstatus);

            $queuepr = DB::table("material.queuepr")
                        ->where('compcode','=',session('compcode'))
                        ->where('recno','=',$recno);

            if($queuepr->exists()){
                $queuepr
                    ->update([
                        'recstatus' => $recstatus,
                        'trantype' => $recstatus_after,
                    ]);
                    
            }else{

                DB::table("material.queuepr")
                    ->insert([
                        'compcode' => session('compcode'),
                        'recno' => $recno,
                        'AuthorisedID' => session('username'),
                        'deptcode' => $deptcode,
                        'recstatus' => $recstatus,
                        'trantype' => $recstatus_after,
                        'adduser' => session('username'),
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur")
                    ]);
            }

            return true;
        }
        
        return false;      
    }

    function get_attachment_files($auditno){
        $attachment_files = DB::table('finance.attachment')
            ->where('compcode','=',session('compcode'))
            ->where('page','=','purchaserequest')
            ->where('type','=','application/pdf')
            ->where('auditno','=',$auditno)
            ->get();

        return $attachment_files;
    }

    function print_connection($purreqhd){
        //1. get PO
        // $purordhd = DB::table('material.purordhd')
        //     ->where('compcode','=',session('compcode'))
    }
    
}

