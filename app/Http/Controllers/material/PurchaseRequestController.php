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
        $purdept = DB::table('sysdb.department')
                        ->select('deptcode')
                        ->where('compcode',session('compcode'))
                        ->where('purdept',1)
                        ->get();

        return view('material.purchaseRequest.purchaseRequest',compact('purdept'));
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

    public function form(Request $request)
    {   
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
            case 'support':
                return $this->support($request);
            case 'verify':
                return $this->verify($request);
            case 'verified':
                return $this->verify($request);
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

        // foreach ($field as $key => $value) {
        //     if($value =='remarks' || $value =='prdept' || $value =='suppcode' || $value =='reqdept'){
        //         $array_insert[$value] = strtoupper($request[$request->field[$key]]);
        //     }else{
        //         $array_insert[$value] = $request[$request->field[$key]];
        //     }
        // }
        
        try {

            // $request_no = $this->request_no('PR', $request->purreqhd_reqdept);
            // $recno = $this->recno('PUR','PR');

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
                'purreqdt' => strtoupper($request->purreqhd_purreqdt),
                'suppcode' => strtoupper($request->purreqhd_suppcode),
                'totamount' => $request->purreqhd_totamount,
                'remarks' => strtoupper($request->purreqhd_remarks),
                'perdisc' => $request->purreqhd_perdisc,
                'amtdisc' => $request->purreqhd_amtdisc,
                'subamount' => $request->purreqhd_subamount

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
        if(!empty($request->fixPost)){
            $field = $this->fixPost2($request->field);
            $idno = substr(strstr($request->table_id,'_'),1);
        }else{
            $field = $request->field;
            $idno = $request->table_id;
        }

        DB::beginTransaction();

        $table = DB::table("material.purreqhd");

        $array_update = [
            'unit' => session('unit'),
            'compcode' => session('compcode'),
            'upduser' => session('username'),
            'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
            'reqdept' => strtoupper($request->purreqhd_reqdept),
            'prdept' => strtoupper($request->purreqhd_prdept),
            'purreqdt' => strtoupper($request->purreqhd_purreqdt),
            'suppcode' => strtoupper($request->purreqhd_suppcode),
            'totamount' => $request->purreqhd_totamount,
            'remarks' => strtoupper($request->purreqhd_remarks),
            'perdisc' => $request->purreqhd_perdisc,
            'amtdisc' => $request->purreqhd_amtdisc,
            'subamount' => $request->purreqhd_subamount

        ];

        // foreach ($field as $key => $value) {
        //     $array_update[$value] = strtoupper($request[$request->field[$key]]);
        // }

        try {
            //////////where//////////
            $table = $table->where('idno','=',$request->purreqhd_idno);

            $obj_ = $table->first();

            if($obj_->recstatus != 'OPEN'){
                throw new \Exception("Cant Edit this document, status is not OPEN!");
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
                //

                // if(!$this->skip_authorization_2($request,$purreqhd_get)){

                    // 1. check authorization
                    // $authorise = DB::table('material.authdtl')
                    //     ->where('compcode','=',session('compcode'))
                    //     ->where('trantype','=','PR')
                    //     ->where('cando','=', 'ACTIVE')
                    //     ->where('recstatus','=','SUPPORT')
                    //     ->where('deptcode','=',$purreqhd_get->reqdept)
                    //     ->where('maxlimit','>=',$purreqhd_get->totamount);

                    // if(!$authorise->exists()){

                    //     $authorise = DB::table('material.authdtl')
                    //         ->where('compcode','=',session('compcode'))
                    //         ->where('trantype','=','PR')
                    //         ->where('cando','=', 'ACTIVE')
                    //         ->where('recstatus','=','SUPPORT')
                    //         ->where('deptcode','=','ALL')
                    //         // ->where('deptcode','=','all')
                    //         ->where('maxlimit','>=',$purreqhd_get->totamount);

                    //         if(!$authorise->exists()){
                    //             throw new \Exception("The user doesnt have authority");
                    //         }

                    // }

                    // $authorise_use = $authorise->first();
                    DB::table("material.queuepr")
                        ->insert([
                            'compcode' => session('compcode'),
                            'recno' => $purreqhd_get->recno,
                            'AuthorisedID' => session('username'),
                            'deptcode' => $purreqhd_get->reqdept,
                            'recstatus' => 'REQUEST',
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
                            'recstatus' => 'REQUEST'
                        ]);

                    DB::table("material.purreqdt")
                        ->where('compcode','=',session('compcode'))
                        ->where('recno','=',$purreqhd_get->recno)
                        ->update([
                            'recstatus' => 'REQUEST',
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

                    //$this->sendemail($data);
                // }
            }

            DB::commit();
        
        } catch (\Exception $e) {
            DB::rollback();
            return response($e, 500);
        }
    }

    public function reopen(Request $request){

        DB::beginTransaction();

        try{

            foreach ($request->idno_array as $value){

                $purreqhd = DB::table("material.purreqhd")
                    ->where('idno','=',$value);

                $purreqhd_get = $purreqhd->first();
                if(!in_array($purreqhd_get->recstatus, ['CANCELLED','REQUEST','SUPPORT','VERIFIED','APPROVED'])){
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
                if(in_array(strtoupper($purreqhd_get->recstatus), ['CANCELLED','COMPLETED','PARTIAL','APPROVED'])){
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

    public function support(Request $request){
        DB::beginTransaction();

        try{

            foreach ($request->idno_array as $value){

                $purreqhd = DB::table("material.purreqhd")
                    ->where('idno','=',$value);

                $purreqhd_get = $purreqhd->first();

                if($purreqhd_get->recstatus != 'REQUEST'){
                    continue;
                }

                // if(!$this->skip_authorization_2($request,$purreqhd_get)){
                    $authorise = DB::table('material.authdtl')
                        ->where('authorid','=',session('username'))
                        ->where('compcode','=',session('compcode'))
                        ->where('trantype','=','PR')
                        ->where('cando','=', 'ACTIVE')
                        ->where('recstatus','=','SUPPORT')
                        ->where('deptcode','=',$purreqhd_get->reqdept)
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

                if($purreqhd_get->recstatus != 'SUPPORT'){
                    continue;
                }

                // if(!$this->skip_authorization_2($request,$purreqhd_get)){
                    $authorise = DB::table('material.authdtl')
                        ->where('authorid','=',session('username'))
                        ->where('compcode','=',session('compcode'))
                        ->where('trantype','=','PR')
                        ->where('cando','=', 'ACTIVE')
                        ->where('recstatus','=','VERIFIED')
                        ->where('deptcode','=',$purreqhd_get->reqdept)
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

                if($purreqhd_get->recstatus != 'VERIFIED'){
                    continue;
                }

                // if(!$this->skip_authorization($request,$purreqhd_get->reqdept,$value)){

                    $authorise = DB::table('material.authdtl')
                        ->where('authorid','=',session('username'))
                        ->where('compcode','=',session('compcode'))
                        ->where('trantype','=','PR')
                        ->where('cando','=', 'ACTIVE')
                        ->where('recstatus','=','APPROVED')
                        ->where('deptcode','=',$purreqhd_get->reqdept)
                        ->where('maxlimit','>=',$purreqhd_get->totamount);

                    if(!$authorise->exists()){

                        $authorise = DB::table('material.authdtl')
                            ->where('authorid','=',session('username'))
                            ->where('compcode','=',session('compcode'))
                            ->where('trantype','=','PR')
                            ->where('cando','=', 'ACTIVE')
                            ->where('recstatus','=','APPROVED')
                            ->where('deptcode','=','ALL')
                            ->where('deptcode','=','all')
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

        $purreqhd = DB::table('material.purreqhd')
            ->where('recno','=',$recno)
            ->first();
            
        $purreqdt = DB::table('material.purreqdt AS prdt', 'material.productmaster AS p', 'material.uom as u')
            ->select('prdt.compcode', 'prdt.recno', 'prdt.lineno_', 'prdt.pricecode', 'prdt.itemcode', 'p.description', 'prdt.uomcode', 'prdt.pouom', 'prdt.qtyrequest', 'prdt.unitprice', 'prdt.taxcode', 'prdt.perdisc', 'prdt.amtdisc', 'prdt.amtslstax as tot_gst','prdt.netunitprice', 'prdt.totamount','prdt.amount', 'prdt.rem_but AS remarks_button', 'prdt.remarks', 'prdt.recstatus', 'prdt.unit', 'u.description as uom_desc')
            ->leftJoin('material.productmaster as p', 'prdt.itemcode', '=', 'p.itemcode')
            ->leftJoin('material.uom as u', 'prdt.uomcode', '=', 'u.uomcode')
            ->where('prdt.compcode','=',session('compcode'))
            ->where('p.compcode','=',session('compcode'))
            ->where('u.compcode','=',session('compcode'))
            ->where('recno','=',$recno)
            ->get();

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

        // $pdf = PDF::loadView('material.purchaseRequest.purchaseRequest_pdf',compact('purreqhd','purreqdt','totamt_bm','company', 'supplier', 'prdept', 'total_tax', 'total_discamt'));
        // return $pdf->stream();      

        return view('material.purchaseRequest.purchaseRequest_pdfmake',compact('purreqhd','purreqdt','totamt_eng','company', 'supplier', 'reqdept', 'total_tax', 'total_discamt'));
    }

    function sendemail($data){
        SendEmailPR::dispatch($data);
        // ProcessPodcast::dispatch();

        // $data_ = ['data' => $data];

        // Mail::send('email.mail', $data_, function($message) use ($data) {
        //     $message->from('me@gmail.com', 'medicsoft');
        //     $message->to($data->email_to);
        // });
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

    
}

