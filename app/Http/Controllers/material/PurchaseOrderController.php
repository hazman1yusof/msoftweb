<?php

namespace App\Http\Controllers\material;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use PDF;


use App\Jobs\SendEmailPR;

class PurchaseOrderController extends defaultController
{   
    public function __construct(){
        $this->middleware('auth');
    }

    public function show(Request $request){   
        $purdept = DB::table('sysdb.department')
                        ->select('deptcode')
                        ->where('compcode',session('compcode'))
                        ->where('purdept',1)
                        ->get();

        return view('material.purchaseOrder.purchaseOrder',compact('purdept'));
    }

    public function show_mobile(Request $request){   
        $oper = strtolower($request->scope);
        $scope = ucfirst(strtolower($request->scope));
        $recno = $request->recno;
        $po_hd = DB::table('material.purordhd as pohd')
                        ->select('pohd.idno','pohd.recno','pohd.prdept','dept_pr.description as reqdept_desc','pohd.deldept','dept_del.description as prdept_desc','pohd.purreqno','pohd.purdate','pohd.totamount','pohd.suppcode','supp.Name as suppcode_desc','pohd.recstatus','pohd.remarks')
                        ->leftjoin('sysdb.department as dept_del', function($join) use ($request){
                            $join = $join
                                ->where('dept_del.compcode',session('compcode'))
                                ->on('dept_del.deptcode','pohd.deldept');
                        })
                        ->leftjoin('sysdb.department as dept_pr', function($join) use ($request){
                            $join = $join
                                ->where('dept_pr.compcode',session('compcode'))
                                ->on('dept_pr.deptcode','pohd.prdept');
                        })
                        ->leftjoin('material.supplier as supp', function($join) use ($request){
                            $join = $join
                                ->where('supp.compcode',session('compcode'))
                                ->on('supp.SuppCode','pohd.suppcode');
                        })
                        ->where('pohd.compcode',session('compcode'))
                        ->where('pohd.recno',$recno)
                        ->first();

        $po_dt = DB::table('material.purorddt as podt')
                        ->select('podt.lineno_','podt.pricecode','psrc.description as pricecode_desc','podt.itemcode','pmast.description as itemcode_desc','podt.uomcode','uom.description as uom_desc','podt.pouom','pouom.description as pouom_desc','podt.qtyorder','podt.unitprice','podt.totamount','podt.remarks')
                        ->leftjoin('material.pricesource as psrc', function($join) use ($request){
                            $join = $join
                                ->where('psrc.compcode',session('compcode'))
                                ->on('psrc.pricecode','podt.pricecode');
                        })
                        ->leftjoin('material.productmaster as pmast', function($join) use ($request){
                            $join = $join
                                ->where('pmast.compcode',session('compcode'))
                                ->on('pmast.itemcode','podt.itemcode');
                        })
                        ->leftjoin('material.uom as uom', function($join) use ($request){
                            $join = $join
                                ->where('uom.compcode',session('compcode'))
                                ->on('uom.uomcode','podt.uomcode');
                        })
                        ->leftjoin('material.uom as pouom', function($join) use ($request){
                            $join = $join
                                ->where('pouom.compcode',session('compcode'))
                                ->on('pouom.uomcode','podt.pouom');
                        })
                        ->where('podt.compcode',session('compcode'))
                        ->where('podt.recno',$recno)
                        ->get();

        return view('material.purchaseOrder.purchaseOrder_mobile',compact('po_hd','po_dt','scope','oper'));
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
            case 'reopen':
                return $this->reopen($request);
            case 'support':
                return $this->support($request);
            case 'verify':
                return $this->verify($request);
            case 'verified':
                return $this->verify($request);
            case 'approved':
                return $this->approved($request);
            case 'cancel':
                return $this->cancel($request);
            case 'cancel_from_reject':
                return $this->cancel_from_reject($request);
            case 'reject':
                return $this->reject($request);
            case 'refresh_do':
                return $this->refresh_do($request);
            case 'add_from_pr':
                return $this->add_from_pr($request);
            default:
                return 'error happen..';
        }
    }

    public function add(Request $request){

        if(!empty($request->fixPost)){
            $field = $this->fixPost2($request->field);
            $idno = substr(strstr($request->table_id,'_'),1);
        }else{
            $field = $request->field;
            $idno = $request->table_id;
        }

        // $purreqno = $this->purreqno($request->purordhd_purreqno);


        DB::beginTransaction();

        try {

            if(!empty($request->referral)){
                $purordno = $this->request_no('PO',$request->purordhd_prdept);
                $recno = $this->recno('PUR','PO');
                $compcode = session('compcode');
            }else{
                $purordno = 0;
                $recno = 0;
                $compcode = 'DD';
            }

            $table = DB::table("material.purordhd");

            $array_insert = [
                'trantype' => 'PO', 
                'recno' => $recno,
                'purordno' => $purordno,
                // 'purreqno' => $purreqno,
                'compcode' => $compcode,
                'unit' => session('unit'),
                'adduser' => session('username'),
                'adddate' => Carbon::now(),
                'upduser' => session('username'),
                'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                'recstatus' => 'OPEN'
            ];

            foreach ($field as $key => $value) {
                $array_insert[$value] = $request[$request->field[$key]];
            }

            $idno = $table->insertGetId($array_insert);

            $totalAmount = 0;
            if(!empty($request->referral)){

                ////ni kalu dia amik dari pr
                ////amik detail dari pr sana, save dkt po detail, amik total amount
                $totalAmount = $this->save_dt_from_othr_pr($request->referral,$recno,$purordno,$compcode,$request->purordhd_deldept);

                $purreqno = $request->purordhd_purreqno;
                // $purordno = $request->purordhd_purordno;

                ////dekat po header sana, save balik delordno dkt situ
                DB::table('material.purreqhd')
                    ->where('purreqno','=',$purreqno)
                    ->where('reqdept','=',$request->purordhd_reqdept)
                    ->where('compcode','=',session('compcode'))
                    ->update(['purordno' => $purordno]);

                $this->move_attachment($request->referral,$idno);
            }

            $responce = new stdClass();
            $responce->purordno = $purordno;
            $responce->recno = $recno;
            $responce->idno = $idno;
            $responce->totalAmount = $totalAmount;
            $responce->adduser = session('username');
            $responce->adddate = Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d H:i:s');

            DB::commit();

            return json_encode($responce);
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

        $purordhd_obj = DB::table('material.purordhd')
                            // ->where('compcode','=',session('compcode'))
                            ->where('idno','=',$request->purordhd_idno)->first();

        $purreqno = $purordhd_obj->purreqno;

        if(!in_array($purordhd_obj->recstatus, ['OPEN','INCOMPLETED','PREPARED'])){
            throw new \Exception("Cant Edit this document, status is not OPEN or INCOMPLETED");
        }
        
        if($purreqno == $request->purordhd_purreqno){
            // ni edit macam biasa, nothing special
            DB::beginTransaction();

            $table = DB::table("material.purordhd");

            $array_update = [
                'compcode' => session('compcode'),
                'upduser' => session('username'),
                'upddate' => Carbon::now(),
                // 'delordno' => $request->purordhd_delordno, 
                // 'prtype' => $request->purordhd_prtype, 
                // 'prdept' => $request->purordhd_prdept, 
                // 'purordno' => $request->purordhd_purordno, 
                // 'recno' => $request->purordhd_recno, 
                // 'deldept' => $request->purordhd_deldept, 
                // 'reqdept' => $request->purordhd_reqdept, 
                // 'purreqno' => $request->purordhd_purreqno, 
                'suppcode' => $request->purordhd_suppcode, 
                'credcode' => $request->purordhd_credcode, 
                'purdate' => $request->purordhd_purdate, 
                'expecteddate' => $request->purordhd_expecteddate, 
                'termdays' => $request->purordhd_termdays, 
                'perdisc' => $request->purordhd_perdisc, 
                'amtdisc' => $request->purordhd_amtdisc, 
                // 'recstatus' => $request->purordhd_recstatus, 
                // 'subamount' => $request->purordhd_subamount, 
                // 'totamount' => $request->purordhd_totamount, 
                'taxclaimable' => $request->purordhd_taxclaimable, 
            ];

            if($purordhd_obj->compcode == 'DD'){
                $array_update['compcode'] = 'DD';
            }

            try {
                //////////where//////////
                $table = $table->where('idno','=',$request->purordhd_idno);
                $table->update($array_update);

                $responce = new stdClass();
                $responce->totalAmount = $request->purordhd_totamount;
                $responce->upduser = session('username');
                $responce->upddate = Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d H:i:s');
                echo json_encode($responce);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();

                return response($e->getMessage(), 500);
            }
        }else{
            DB::beginTransaction();
            try{
                // ni edit kalu copy utk do dari existing po
                //1. update po.delordno lama jadi 0, kalu do yang dulu pon copy existing po 
                if($purreqno != '0'){
                    DB::table('material.purreqhd')
                    ->where('purreqno','=', $purreqno)
                    ->where('reqdept','=',$request->purordhd_reqdept)
                    ->where('compcode','=',session('compcode'))
                    ->update(['purordno' => '0']);
                }

                //2. Delete detail from delorddt
                DB::table('material.purorddt')->where('recno','=',$request->purordhd_recno)->delete();

                //3. Update srcdocno_delordhd
                $table = DB::table("material.purordhd");

                $array_update = [
                    'compcode' => session('compcode'),
                    'upduser' => session('username'),
                    'upddate' => Carbon::now(),
                    // 'delordno' => $request->purordhd_delordno, 
                    // 'prtype' => $request->purordhd_prtype, 
                    // 'prdept' => $request->purordhd_prdept, 
                    // 'purordno' => $request->purordhd_purordno, 
                    // 'recno' => $request->purordhd_recno, 
                    // 'deldept' => $request->purordhd_deldept, 
                    // 'reqdept' => $request->purordhd_reqdept, 
                    // 'purreqno' => $request->purordhd_purreqno, 
                    'suppcode' => $request->purordhd_suppcode, 
                    'credcode' => $request->purordhd_credcode, 
                    'purdate' => $request->purordhd_purdate, 
                    'expecteddate' => $request->purordhd_expecteddate, 
                    'termdays' => $request->purordhd_termdays, 
                    'perdisc' => $request->purordhd_perdisc, 
                    'amtdisc' => $request->purordhd_amtdisc, 
                    // 'recstatus' => $request->purordhd_recstatus, 
                    // 'subamount' => $request->purordhd_subamount, 
                    // 'totamount' => $request->purordhd_totamount, 
                    'taxclaimable' => $request->purordhd_taxclaimable, 
                ];

                // $array_update = [
                //     'compcode' => session('compcode'),
                //     'upduser' => session('username'),
                //     'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                // ];

                // foreach ($field as $key => $value) {
                //     $array_update[$value] = $request[$request->field[$key]];
                // }

                $table = $table->where('idno','=',$request->purordhd_idno);
                $table->update($array_update);

                $totalAmount = $request->purordhd_totamount;
                //4. Update delorddt
                // if(!empty($request->referral)){
                //     $totalAmount = $this->save_dt_from_othr_pr($request->referral,$purordhd_obj->recno,$purordhd_obj->purordno,session('compcode'),$request->purordhd_deldept);

                //     // $purreqno = $request->purordhd_purreqno;
                //     // $purordno = $request->purordhd_purordno;

                //     ////dekat pr header sana, save balik purordno dkt situ
                //     DB::table('material.purreqhd')
                //         ->where('purreqno','=',$purreqno)
                //         ->where('reqdept','=',$request->purordhd_reqdept)
                //         ->where('compcode','=',session('compcode'))
                //         ->update(['purordno' => $purordhd_obj->purordno]);  
                // }

                $responce = new stdClass();
                $responce->totalAmount = $totalAmount;
                $responce->upduser = session('username');
                $responce->upddate = Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d H:i:s');
                echo json_encode($responce);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();

                return response($e->getMessage(), 500);
            }
        }
    }

    public function del(Request $request){
    }
    
    public function posted(Request $request){
        DB::beginTransaction();

        try{

            foreach ($request->idno_array as $value){

                $purordhd = DB::table("material.purordhd")
                            ->where('idno','=',$value);

                $purordhd_get = $purordhd->first();

                if($purordhd_get->recstatus != 'OPEN'){
                    continue;
                }

                if(strtoupper($purordhd_get->prtype) == 'STOCK'){
                    $prtype = 'STOCK';
                }else{
                    $prtype = 'OTHERS';
                }
            
                DB::table("material.queuepo")
                    ->insert([
                        'compcode' => session('compcode'),
                        'recno' => $purordhd_get->recno,
                        'AuthorisedID' => session('username'),
                        'deptcode' => $purordhd_get->prdept,
                        'prtype' => $prtype,
                        'recstatus' => 'PREPARED',
                        'trantype' => 'VERIFIED',
                        'adduser' => session('username'),
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur")
                    ]);

                // 3. change recstatus to posted
                $purordhd
                    ->update([
                        'requestby' => session('username'),
                        'requestdate' => Carbon::now("Asia/Kuala_Lumpur"),
                        // 'supportby' => $authorise_use->authorid,
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'recstatus' => 'PREPARED'
                    ]);

                DB::table("material.purorddt")
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$purordhd_get->recno)
                    ->update([
                        'recstatus' => 'PREPARED',
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                    ]);


                // 4. email and whatsapp
                $data = new stdClass();
                $data->status = 'SUPPORT';
                $data->deptcode = $purordhd_get->reqdept;
                $data->purreqno = $purordhd_get->purreqno;
                $data->email_to = 'hazman.yusof@gmail.com';
                $data->whatsapp = '01123090948';

                   // $this->sendemail($data);

                // }

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

                $purordhd = DB::table("material.purordhd")
                            ->where('idno','=',$value);

                $purordhd_get = $purordhd->first();

                if($purordhd_get->recstatus != 'CANCELLED'){
                    continue;
                }

                $purordhd->update([
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

                DB::table("material.purorddt")
                    ->where('recno','=',$purordhd_get->recno)
                    ->update([
                        'recstatus' => 'OPEN',
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                    ]);

                DB::table("material.queuepo")
                    ->where('recno','=',$purordhd_get->recno)
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

                $purordhd = DB::table("material.purordhd")
                            ->where('idno','=',$value);

                $purordhd_get = $purordhd->first();

                if($purordhd_get->recstatus != 'OPEN'){
                    continue;
                }

                $purordhd->update([
                    'recstatus' => 'CANCELLED'
                ]);

                DB::table("material.purorddt")
                    ->where('recno','=',$purordhd_get->recno)
                    ->update([
                        'recstatus' => 'CANCELLED',
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                    ]);
            }


            // $po_dt = DB::table('material.purorddt')
            //     ->where('recno', '=', $purordhd_get->recno)
            //     ->where('compcode', '=', session('compcode'))
            //     ->where('recstatus', '<>', 'DELETE')
            //     ->get();

            // foreach ($po_dt as $key => $value) {
            //     if($value->qtyorder != $value->qtyoutstand){
            //         DB::rollback();
                        
            //         return response('Error: Please Cancel all DO before CANCEL', 500)
            //             ->header('Content-Type', 'text/plain');
            //     }
            // }

            $purordhd->update([
                    'recstatus' => 'CANCELLED'
                ]);

            DB::table("material.purorddt")
                ->where('recno','=',$purordhd_get->recno)
                ->update([
                    'recstatus' => 'CANCELLED',
                    'upduser' => session('username'),
                    'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                ]);

            DB::table("material.queuepo")
                ->where('recno','=',$purordhd_get->recno)
                ->delete();
           
            DB::commit();
            
        } catch (\Exception $e) {
            DB::rollback();

            return response($e, 500);
        }
    }

    public function cancel_from_reject(Request $request){
        DB::beginTransaction();

        try{

            foreach ($request->idno_array as $value){

                $purordhd = DB::table("material.purordhd")
                            ->where('idno','=',$value);

                $purordhd_get = $purordhd->first();

                if($purordhd_get->recstatus != 'CANCELLED'){
                    continue;
                }

                DB::table("material.queuepo")
                    ->where('recno','=',$purordhd_get->recno)
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

                $purordhd = DB::table("material.purordhd")
                    ->where('idno','=',$value);

                $purordhd_get = $purordhd->first();
                if(!in_array($purordhd_get->recstatus, ['SUPPORT','VERIFIED','APPROVED'])){
                    continue;
                }

                if(strtoupper($purordhd_get->prtype) == 'STOCK'){
                    $prtype = 'STOCK';
                }else{
                    $prtype = 'OTHERS';
                }

                $authorise = DB::table('material.authdtl')
                    ->where('authorid','=',session('username'))
                    ->where('compcode','=',session('compcode'))
                    ->where('trantype','=','PO')
                    ->where('cando','=', 'ACTIVE')
                    ->where('prtype','=',$prtype)
                    ->where('deptcode','=',$purordhd_get->reqdept)
                    ->where('maxlimit','>=',$purordhd_get->totamount)
                    ->whereIn('recstatus',['SUPPORT','VERIFIED','RECOMMENDED1','RECOMMENDED2','APPROVED']);

                if(!$authorise->exists()){

                    $authorise = DB::table('material.authdtl')
                        ->where('authorid','=',session('username'))
                        ->where('compcode','=',session('compcode'))
                        ->where('trantype','=','PO')
                        ->where('cando','=', 'ACTIVE')
                        ->where('deptcode','=','ALL')
                        ->where('deptcode','=','all')
                        ->where('prtype','=',$prtype)
                        ->where('maxlimit','>=',$purordhd_get->totamount)
                        ->whereIn('recstatus',['SUPPORT','VERIFIED','RECOMMENDED1','RECOMMENDED2','APPROVED']);

                        if(!$authorise->exists()){
                            throw new \Exception("The user doesnt have authority",500);
                        }
                        
                }

                $purordhd_update = [
                    'recstatus' => 'CANCELLED',
                    'cancelby' => session('username'),
                    'canceldate' => Carbon::now("Asia/Kuala_Lumpur"),
                ];

                if(!empty($request->remarks)){
                    $purordhd_update['cancelled_remark'] = $request->remarks;
                }

                $purordhd->update($purordhd_update);

                DB::table("material.purorddt")
                    ->where('recno','=',$purordhd_get->recno)
                    ->update([
                        'recstatus' => 'CANCELLED'
                    ]);

                DB::table("material.queuepo")
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$purordhd_get->recno)
                    ->update([
                        'AuthorisedID' => $purordhd_get->adduser,
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

                $purordhd = DB::table("material.purordhd")
                    ->where('idno','=',$value);

                $purordhd_get = $purordhd->first();

                throw new \Exception("PO cant be supported anymore",500);

                if($purordhd_get->recstatus != 'PREPARED'){
                    continue;
                }

                if(strtoupper($purordhd_get->prtype) == 'STOCK'){
                    $prtype = 'STOCK';
                }else{
                    $prtype = 'OTHERS';
                }

                // if(!$this->skip_authorization_2($request,$purordhd_get)){
                    $authorise = DB::table('material.authdtl')
                        ->where('compcode','=',session('compcode'))
                        ->where('authorid','=',session('username'))
                        ->where('trantype','=','PO')
                        ->where('cando','=', 'ACTIVE')
                        ->where('recstatus','=','SUPPORT')
                        ->where('prtype','=',$prtype)
                        ->where('deptcode','=',$purordhd_get->prdept)
                        ->where('minlimit','<=',$purordhd_get->totamount)
                        ->where('maxlimit','>=',$purordhd_get->totamount);

                    if(!$authorise->exists()){

                        $authorise = DB::table('material.authdtl')
                            ->where('compcode','=',session('compcode'))
                            ->where('authorid','=',session('username'))
                            ->where('trantype','=','PO')
                            ->where('cando','=', 'ACTIVE')
                            ->where('recstatus','=','SUPPORT')
                            ->where('deptcode','=','ALL')
                            ->where('deptcode','=','all')
                            ->where('prtype','=',$prtype)
                            ->where('minlimit','<=',$purordhd_get->totamount)
                            ->where('maxlimit','>=',$purordhd_get->totamount);

                            if(!$authorise->exists()){
                                throw new \Exception("The user doesnt have authority",500);
                            }
                            
                    }

                    $authorise_use = $authorise->first();

                    $purordhd->update([
                            'supportby' => $authorise_use->authorid,
                            'supportdate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'upduser' => session('username'),
                            'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'recstatus' => 'SUPPORT'
                        ]);

                    DB::table("material.purorddt")
                        ->where('compcode','=',session('compcode'))
                        ->where('recno','=',$purordhd_get->recno)
                        ->update([
                            'recstatus' => 'SUPPORT',
                            'upduser' => session('username'),
                            'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                        ]);

                    DB::table("material.queuepo")
                        ->where('compcode','=',session('compcode'))
                        ->where('recno','=',$purordhd_get->recno)
                        ->update([
                            'AuthorisedID' => $authorise_use->authorid,
                            'recstatus' => 'SUPPORT',
                            'trantype' => 'VERIFIED',
                            'adduser' => session('username'),
                            'adddate' => Carbon::now("Asia/Kuala_Lumpur")
                        ]);
                // }
            }


            // 4. email and whatsapp
            // $data = new stdClass();
            // $data->status = 'VERIFIED';
            // $data->deptcode = $purordhd_get->reqdept;
            // $data->purreqno = $purordhd_get->purreqno;
            // $data->email_to = 'hazman.yusof@gmail.com';
            // $data->whatsapp = '01123090948';

           // $this->sendemail($data);


           
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

                $purordhd = DB::table("material.purordhd")
                    ->where('idno','=',$value);

                $purordhd_get = $purordhd->first();

                if($purordhd_get->recstatus != 'PREPARED'){
                    continue;
                }

                if(strtoupper($purordhd_get->prtype) == 'STOCK'){
                    $prtype = 'STOCK';
                }else{
                    $prtype = 'OTHERS';
                }

                // if(!$this->skip_authorization_2($request,$purordhd_get)){
                    $authorise = DB::table('material.authdtl')
                        ->where('compcode','=',session('compcode'))
                        ->where('authorid','=',session('username'))
                        ->where('trantype','=','PO')
                        ->where('cando','=', 'ACTIVE')
                        ->where('recstatus','=','VERIFIED')
                        ->where('deptcode','=',$purordhd_get->reqdept)
                        ->where('prtype','=',$prtype)
                        ->where('minlimit','<=',$purordhd_get->totamount)
                        ->where('maxlimit','>=',$purordhd_get->totamount);

                    if(!$authorise->exists()){

                        $authorise = DB::table('material.authdtl')
                            ->where('compcode','=',session('compcode'))
                            ->where('authorid','=',session('username'))
                            ->where('trantype','=','PO')
                            ->where('cando','=', 'ACTIVE')
                            ->where('recstatus','=','VERIFIED')
                            ->where('deptcode','=','ALL')
                            ->where('deptcode','=','all')
                            ->where('prtype','=',$prtype)
                            ->where('minlimit','<=',$purordhd_get->totamount)
                            ->where('maxlimit','>=',$purordhd_get->totamount);

                            if(!$authorise->exists()){
                                throw new \Exception("The user doesnt have authority",500);
                            }
                            
                    }

                    $authorise_use = $authorise->first();

                    $purordhd->update([
                            'verifiedby' => $authorise_use->authorid,
                            'verifieddate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'upduser' => session('username'),
                            'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'recstatus' => 'VERIFIED'
                        ]);

                    DB::table("material.purorddt")
                        ->where('compcode','=',session('compcode'))
                        ->where('recno','=',$purordhd_get->recno)
                        ->update([
                            'recstatus' => 'VERIFIED',
                            'upduser' => session('username'),
                            'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                        ]);

                    DB::table("material.queuepo")
                        ->where('compcode','=',session('compcode'))
                        ->where('recno','=',$purordhd_get->recno)
                        ->update([
                            'AuthorisedID' => $authorise_use->authorid,
                            'recstatus' => 'VERIFIED',
                            'trantype' => 'APPROVED',
                            'adduser' => session('username'),
                            'adddate' => Carbon::now("Asia/Kuala_Lumpur")
                        ]);
                // }
            }

            // 4. email and whatsapp
            // $data = new stdClass();
            // $data->status = 'APPROVED';
            // $data->deptcode = $purordhd_get->reqdept;
            // $data->purreqno = $purordhd_get->purreqno;
            // $data->email_to = 'hazman.yusof@gmail.com';
            // $data->whatsapp = '01123090948';

           // $this->sendemail($data);

           
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

                $purordhd = DB::table("material.purordhd")
                    ->where('idno','=',$value);

                $purordhd_get = $purordhd->first();

                if($purordhd_get->recstatus != 'VERIFIED'){
                    continue;
                }

                $this->need_upd_purreq($value);

                if(strtoupper($purordhd_get->prtype) == 'STOCK'){
                    $prtype = 'STOCK';
                }else{
                    $prtype = 'OTHERS';
                }

                $authorise = DB::table('material.authdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('authorid','=',session('username'))
                    ->where('trantype','=','PO')
                    ->where('cando','=', 'ACTIVE')
                    ->where('recstatus','=','APPROVED')
                    ->where('prtype','=',$prtype)
                    ->where('minlimit','<=',$purordhd_get->totamount)
                    ->where('deptcode','=',$purordhd_get->reqdept)
                    ->where('maxlimit','>=',$purordhd_get->totamount);

                if(!$authorise->exists()){

                    $authorise = DB::table('material.authdtl')
                        ->where('compcode','=',session('compcode'))
                        ->where('authorid','=',session('username'))
                        ->where('trantype','=','PO')
                        ->where('cando','=', 'ACTIVE')
                        ->where('recstatus','=','APPROVED')
                        ->where('deptcode','=','ALL')
                        ->where('deptcode','=','all')
                        ->where('prtype','=',$prtype)
                        ->where('minlimit','<=',$purordhd_get->totamount)
                        ->where('maxlimit','>=',$purordhd_get->totamount);

                        if(!$authorise->exists()){
                            throw new \Exception("The user doesnt have authority",500);
                        }
                        
                }
                $authorise_use = $authorise->first();

                $purordhd->update([
                        'approvedby' => $authorise_use->authorid,
                        'approveddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'recstatus' => 'APPROVED'
                    ]);

                DB::table("material.purorddt")
                    ->where('recno','=',$purordhd_get->recno)
                    ->where('compcode','=',session('compcode'))
                    ->update([
                        'recstatus' => 'APPROVED',
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                    ]);

                DB::table("material.queuepo")
                    ->where('recno','=',$purordhd_get->recno)
                    ->where('compcode','=',session('compcode'))
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

    public function recno($source,$trantype){
        $pvalue1 = DB::table('sysdb.sysparam')
                ->select('pvalue1')
                ->where('source','=',$source)->where('trantype','=',$trantype)->first();

        DB::table('sysdb.sysparam')
        ->where('source','=',$source)->where('trantype','=',$trantype)
        ->update(['pvalue1' => intval($pvalue1->pvalue1) + 1]);
        
        return $pvalue1->pvalue1;
    }

    public function purordno($trantype,$dept){
        $seqno = DB::table('material.sequence')
                ->select('seqno')
                ->where('trantype','=',$trantype)->where('dept','=',$dept)->first();

        DB::table('material.sequence')
            ->where('trantype','=',$trantype)->where('dept','=',$dept)
            ->update(['seqno' => intval($seqno->seqno) + 1]);
        
        return $seqno->seqno;
    }
    
    public function showpdf(Request $request){
        $recno = $request->recno;
        if(!$recno){
            abort(404);
        }
        
        $purordhd = DB::table('material.purordhd as p')
                    ->select('p.idno','p.recno','p.prdept','p.purordno','p.compcode','p.reqdept','p.purreqno','p.deldept','p.purdate','p.expecteddate','p.expirydate','p.suppcode','p.credcode','p.termdays','p.subamount','p.amtdisc','p.perdisc','p.totamount','p.taxclaimable','p.isspersonid','p.issdate','p.authpersonid','p.authdate','p.remarks','p.recstatus','p.adduser','p.adddate','p.upduser','p.upddate','p.assflg','p.potype','p.delordno','p.expflg','p.prortdisc','p.cancelby','p.canceldate','p.reopenby','p.reopendate','p.TaxAmt','p.postedby','p.postdate','p.unit','p.trantype','p.requestby','p.requestdate','p.supportby','p.supportdate','p.verifiedby','p.verifieddate','p.approvedby','p.approveddate','p.cancelled_remark','p.support_remark','p.verified_remark','p.approved_remark','p.prtype','u.name as requestby_name','s.name as supportby_name','e.name as verifiedby_name','r.name as approvedby_name')
                    ->leftJoin('sysdb.users as u', function ($join) use ($request){
                        $join = $join->on('u.username', '=', 'p.requestby')
                                    ->where('u.compcode','=',session('compcode'));
                    })
                    ->leftJoin('sysdb.users as s', function ($join) use ($request){
                        $join = $join->on('s.username', '=', 'p.supportby')
                                    ->where('s.compcode','=',session('compcode'));
                    })
                    ->leftJoin('sysdb.users as e', function ($join) use ($request){
                        $join = $join->on('e.username', '=', 'p.verifiedby')
                                    ->where('e.compcode','=',session('compcode'));
                    })
                    ->leftJoin('sysdb.users as r', function ($join) use ($request){
                        $join = $join->on('r.username', '=', 'p.approvedby')
                                    ->where('r.compcode','=',session('compcode'));
                    })
                    ->where('p.compcode','=',session('compcode'))
                    ->where('p.recno','=',$recno)
                    ->first();
        
        $purorddt = DB::table('material.purorddt AS podt', 'material.productmaster AS p', 'material.uom as u')
                    ->select('podt.compcode', 'podt.recno', 'podt.lineno_', 'podt.pricecode', 'podt.itemcode', 'p.description', 'podt.uomcode', 'podt.pouom', 'podt.qtyorder', 'podt.unitprice', 'podt.taxcode', 'podt.perdisc', 'podt.amtdisc', 'podt.amtslstax as tot_gst','podt.netunitprice', 'podt.totamount','podt.amount', 'podt.rem_but AS remarks_button', 'podt.remarks', 'podt.recstatus', 'podt.unit', 'u.description as uom_desc')
                    ->leftJoin('material.uom as u', function ($join){
                        $join = $join->on('u.uomcode', '=', 'podt.pouom')
                                    ->where('u.compcode','=',session('compcode'));
                    })
                    ->leftJoin('material.product as p', function ($join){
                        $join = $join->on('p.itemcode', '=', 'podt.itemcode')
                                    ->where('p.compcode','=',session('compcode'))
                                    ->on('p.uomcode','=','podt.uomcode')
                                    ->on('p.unit','=','podt.unit');
                                    // ->where('p.unit','=',session('unit'));
                    })
                    ->where('podt.compcode','=',session('compcode'))
                    ->where('recno','=',$recno)
                    ->get();
                    
        $company = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();
        
        $supplier = DB::table('material.supplier')
                    ->where('compcode','=',session('compcode'))
                    ->where('SuppCode','=',$purordhd->suppcode)
                    ->first();
        
        $deldept = DB::table('material.deldept')
                    ->where('compcode','=',session('compcode'))
                    ->where('deptcode','=',$purordhd->deldept)
                    ->first();
        
        $total_tax = DB::table('material.purorddt')
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$recno)
                    ->sum('amtslstax');
        
        $total_discamt = DB::table('material.purorddt')
                        ->where('compcode','=',session('compcode'))
                        ->where('recno','=',$recno)
                        ->sum('amtdisc');
        
        $totamount_expld = explode(".", (float)$purordhd->totamount);
        
        // $totamt_bm_rm = $this->convertNumberToWordBM($totamount_expld[0])." RINGGIT ";
        // $totamt_bm = $totamt_bm_rm." SAHAJA";
        
        // if(count($totamount_expld) > 1){
        //     $totamt_bm_sen = $this->convertNumberToWordBM($totamount_expld[1])." SEN";
        //     $totamt_bm = $totamt_bm_rm.$totamt_bm_sen." SAHAJA";
        // }
        
        $totamt_eng_rm = $this->convertNumberToWordENG($totamount_expld[0])."";
        $totamt_eng = $totamt_eng_rm." ONLY";
        
        if(count($totamount_expld) > 1){
            $totamt_eng_sen = $this->convertNumberToWordENG($totamount_expld[1]). "CENT";
            $totamt_eng = $totamt_eng_rm.$totamt_eng_sen." ONLY";
        }

        $attachment_files =$this->get_attachment_files($purordhd->idno);
        
        return view('material.purchaseOrder.purchaseOrder_pdfmake2',compact('purordhd','purorddt','totamt_eng', 'company', 'supplier','deldept', 'total_tax', 'total_discamt','attachment_files'));
    }

     // public function toGetAllpurreqhd($recno){
     //    $purreqno = DB::table('material.purordhd')
     //            ->select('purreqno')
     //            ->where('recno','=',$recno)->first();

     //    DB::table('material.purordhd')
     //    ->where('recno','=',$recno)
     //    ->update(['purreqno' => intval($purreqno->purreqno) + 1]);
        
     //    return $purreqno->purreqno;

     //    }

    public function save_dt_from_othr_pr($refer_recno,$recno,$purordno,$compcode,$del_dept){
        $pr_dt = DB::table('material.purreqdt')
                ->where('recno', '=', $refer_recno)
                ->where('compcode', '=', session('compcode'))
                // ->where('unit', '=', session('unit'))
                ->where('recstatus', '<>', 'DELETE')
                ->get();

        $pr_hd = DB::table('material.purreqhd')
                ->where('recno', '=', $refer_recno)
                ->where('compcode', '=', session('compcode'))
                // ->where('unit', '=', session('unit'))
                ->first();

        $deldept_unit = DB::table('sysdb.department')
                        ->where('compcode',session('compcode'))
                        ->where('deptcode',$del_dept)
                        ->first();

        $deldept_unit = $deldept_unit->sector;

        foreach ($pr_dt as $key => $value) {
            ///1. insert detail we get from existing purchase request
            $prtype = $pr_hd->prtype;

            if($prtype == 'Stock'){
                $product = DB::table('material.stockloc as s')
                            ->leftJoin('material.product AS p', function($join){
                                $join = $join->on("p.itemcode", '=', 's.itemcode');
                                $join = $join->on("p.uomcode", '=', 's.uomcode');
                                $join = $join->where("p.unit", '=', $deldept_unit);
                                $join = $join->where("p.compcode", '=', session('compcode'));
                            })
                            ->where('s.unit','=',$deldept_unit)
                            ->where('s.compcode','=',session('compcode'))
                            ->where('s.year','=',Carbon::now("Asia/Kuala_Lumpur")->year)
                            ->where('s.deptcode','=',$del_dept)
                            ->where('s.itemcode','=',$value->itemcode)
                            ->where('s.uomcode','=',$value->uomcode)
                            ->whereIn('p.groupcode',['STOCK','CONSIGNMENT']);

                if(!$product->exists()){
                    throw new \Exception("Itemcode $value->itemcode - $value->uomcode - $del_dept , doesnt have stockloc or product");
                }

            }else{
                $product = DB::table('material.product AS p')
                            ->where('p.compcode','=',session('compcode'))
                            ->where('p.itemcode','=',$value->itemcode)
                            ->where('p.uomcode','=',$value->uomcode)
                            ->whereIn('p.groupcode',['ASSET','OTHERS']);
            }

            if(!$product->exists()){
                throw new \Exception("Itemcode $value->itemcode - $value->uomcode , doesnt have product");
            }


            // $product = DB::table("material.product as p")
            //             ->where('p.compcode',session('compcode'))
            //             ->where('p.unit',session('unit'))
            //             ->where('p.itemcode',$value->itemcode)
            //             ->where('p.uomcode',$value->uomcode);
            // if(!$product->exists()){
            //     throw new \Exception("Product Doesnt Exists for item: ".$value->itemcode." uomcode: ".$value->uomcode);
            // }

            // $stockloc = DB::table("material.stockloc as s")
            //             ->where('s.compcode',session('compcode'))
            //             ->where('s.unit',session('unit'))
            //             ->where('s.itemcode',$value->itemcode)
            //             ->where('s.uomcode',$value->uomcode)
            //             ->where('s.year',Carbon::now("Asia/Kuala_Lumpur")->year)
            //             ->where('s.deptcode',$del_dept);
            // if(!$stockloc->exists()){
            //     throw new \Exception("Stock Location for this Product Doesnt Exists for item: ".$value->itemcode." uomcode: ".$value->uomcode." Department: ".$del_dept." year ".Carbon::now("Asia/Kuala_Lumpur")->year);
            // }

            $table = DB::table("material.purorddt");
            $table->insert([
                'compcode' => $compcode, 
                'unit' => session('unit'), 
                'recno' => $recno, 
                'lineno_' => $value->lineno_, 
                'pricecode' => $value->pricecode, 
                'itemcode' => $value->itemcode, 
                'uomcode' => $value->uomcode, 
                'pouom' => $value->pouom, 
                'qtyorder' => $value->qtyrequest, 
                'qtydelivered' => 0,
                'qtyoutstand' => $value->qtybalance,
                'qtyrequest' => $value->qtyrequest,
                'unitprice' => $value->unitprice, 
                'taxcode' => $value->taxcode, 
                'perdisc' => $value->perdisc, 
                'amtdisc' => $value->amtdisc, 
                'amtslstax' => $value->amtslstax, 
                'amount' => $value->amount, 
                'totamount' => $value->totamount, 
                'netunitprice' => $value->netunitprice, 
                'purordno' => $purordno,
                'adduser' => session('username'), 
                'adddate' => Carbon::now(), 
                'recstatus' => 'ACTIVE', 
                'reqdept' => $pr_hd->reqdept, 
                'purreqno' => $pr_hd->purreqno, 
                'remarks' => $value->remarks
            ]);

            
        }
        ///2. calculate total amount from detail erlier
        $amount = DB::table('material.purorddt')
                    ->where('compcode','=',$compcode)
                    ->where('recno','=',$recno)
                    ->where('recstatus','<>','DELETE')
                    ->sum('amount');

        ///3. then update to header
        $table = DB::table('material.purordhd')
                    ->where('compcode','=',$compcode)
                    ->where('recno','=',$recno);
        $table->update([
                'totamount' => $amount, 
                'subamount' => $amount
            ]);

        $this->check_incompleted($recno);

        return $amount;
    }

    public function move_attachment($refer_recno,$idno){
        $pr_hd = DB::table('material.purreqhd')
                ->where('recno', '=', $refer_recno)
                ->where('compcode', '=', session('compcode'))
                // ->where('unit', '=', session('unit'))
                ->first();

        $attachment = DB::table('finance.attachment')
                        ->where('compcode', '=', session('compcode'))
                        ->where('page','=','purchaserequest')
                        ->where('auditno', '=', $pr_hd->idno);

        if($attachment->exists()){
            $attachment = $attachment->get();

            foreach ($attachment as $obj) {
                DB::table('finance.attachment')
                    ->insert([
                        'compcode' => session('compcode'),
                        'resulttext' => $obj->resulttext,
                        'attachmentfile' => $obj->attachmentfile,
                        'adduser' => 'system',
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'page' => 'purchaseorder',
                        'auditno' => $idno,
                        'type' => $obj->type,
                        'trxdate' => Carbon::now("Asia/Kuala_Lumpur")
                    ]);
            }
        }
    }

    function check_incompleted($recno){
        $incompleted = false;
        $purorddt_null = DB::table('material.purorddt')
                            ->where('compcode','=',session('compcode'))
                            ->where('recno','=',$recno)
                            ->where('pricecode','IV')
                            ->where('recstatus','<>','DELETE')
                            // ->whereNull('unitprice')
                            // ->orWhereNull('pouom');
                            ->where(function ($purorddt_null){
                                $purorddt_null
                                        ->whereNull('unitprice')
                                        ->orWhereNull('pouom'); 
                            });

        $purorddt_empty = DB::table('material.purorddt')
                            ->where('compcode','=',session('compcode'))
                            ->where('recno','=',$recno)
                            ->where('recstatus','<>','DELETE')
                            ->where('pricecode','IV')
                            // ->where('unitprice','=','0')
                            // ->orWhere('pouom','=','');
                            ->where(function ($purorddt_empty){
                                $purorddt_empty
                                    ->where('unitprice','=','0.00')
                                    ->orWhere('pouom','=','');   
                            });



        if($purorddt_null->exists() || $purorddt_empty->exists()){
            $incompleted = true;
        }

        if($incompleted){
            DB::table('material.purordhd')
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$recno)
                    ->update([
                        'recstatus' => 'INCOMPLETED'
                    ]);
        }else{
            DB::table('material.purordhd')
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$recno)
                    ->update([
                        'recstatus' => 'OPEN'
                    ]);
        }
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
                    ->where('trantype','=','PO')
                    ->where(function($q) use ($deptcode) {
                          $q->where('deptcode', $deptcode)
                            ->orWhere('deptcode', 'ALL');
                      })
                    ->where('recstatus','=','APPROVED');

        if($authdtl->count() > 0){


            $purordhd = DB::table("material.purordhd")
                ->where('idno','=',$idno);

            $purordhd_get = $purordhd->first();

            $authdtl = DB::table('material.authdtl')
                    ->where('authorid','=',session('username'))
                    ->where('trantype','=','PO')
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

            $purordhd->update($array_update);

            DB::table("material.purorddt")
                ->where('recno','=',$purordhd_get->recno)
                ->update([
                    'recstatus' => 'APPROVED',
                    'upduser' => session('username'),
                    'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                ]);

            $queuepo = DB::table("material.queuepo")
                        ->where('compcode','=',session('compcode'))
                        ->where('recno','=',$purordhd_get->recno);

            if($queuepo->exists()){
                $queuepo
                    ->update([
                        'recstatus' => 'APPROVED',
                        'trantype' => 'DONE',
                    ]);
                    
            }else{

                DB::table("material.queuepo")
                    ->insert([
                        'compcode' => session('compcode'),
                        'recno' => $purordhd_get->recno,
                        'AuthorisedID' => session('username'),
                        'deptcode' => $purordhd_get->prdept,
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

    function skip_authorization_2(Request $request, $pohd){

        $idno = $pohd->idno;
        $recno = $pohd->recno;
        $deptcode = $pohd->prdept;
        $maxlimit = $pohd->totamount;
        $authdtl = DB::table('material.authdtl')
                    ->where('authorid','=',session('username'))
                    ->where('trantype','=','PO')
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

            // if(!empty($array_update['approvedby'])){
            //     $recstatus = 'APPROVED';
            //     $recstatus_after = 'DONE';
            //     $array_update['recstatus'] = 'APPROVED';
            //     $array_update['supportby'] = session('username');
            //     $array_update['supportdate'] = Carbon::now("Asia/Kuala_Lumpur");
            //     $array_update['verifiedby'] = session('username');
            //     $array_update['verifieddate'] = Carbon::now("Asia/Kuala_Lumpur");
            //     $array_update['approvedby'] = session('username');
            //     $array_update['approveddate'] = Carbon::now("Asia/Kuala_Lumpur");
            // }else if(!empty($array_update['verifiedby'])){
            //     $recstatus = 'VERIFIED';
            //     $recstatus_after = 'APPROVED';
            //     $array_update['recstatus'] = 'VERIFIED';
            //     $array_update['supportby'] = session('username');
            //     $array_update['supportdate'] = Carbon::now("Asia/Kuala_Lumpur");
            //     $array_update['verifiedby'] = session('username');
            //     $array_update['verifieddate'] = Carbon::now("Asia/Kuala_Lumpur");
            // }else if(!empty($array_update['supportby'])){
            //     $recstatus = 'SUPPORT';
            //     $recstatus_after = 'VERIFIED';
            //     $array_update['recstatus'] = 'SUPPORT';
            //     $array_update['supportby'] = session('username');
            //     $array_update['supportdate'] = Carbon::now("Asia/Kuala_Lumpur");
            // }else{
            //     return false;
            // }

            DB::table("material.purordhd")
                ->where('idno',$idno)
                ->update($array_update);

            DB::table("material.purorddt")
                ->where('recno','=',$recno)
                ->update([
                    'recstatus' => $recstatus,
                    'upduser' => session('username'),
                    'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                ]);

            $queuepo = DB::table("material.queuepo")
                        ->where('compcode','=',session('compcode'))
                        ->where('recno','=',$recno);

            if($queuepo->exists()){
                $queuepo
                    ->update([
                        'recstatus' => $recstatus,
                        'trantype' => $recstatus_after,
                    ]);
                    
            }else{

                DB::table("material.queuepo")
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

    function add_from_pr($request){
        $table = DB::table('material.purreqdt')
                    ->where('recstatus','=','APPROVED');

        //////////paginate/////////
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

    public function need_upd_purreq($idno){

        $purordhd = DB::table("material.purordhd")
                    ->where('idno','=',$idno)
                    ->first();

        if(!empty($purordhd->purreqno)){

            $status_header = 'COMPLETED';

            $purreqhd = DB::table('material.purreqhd')
                        ->where('compcode','=',session('compcode'))
                        ->where('reqdept','=',$purordhd->reqdept)
                        ->where('purreqno','=',$purordhd->purreqno);

            if($purreqhd->exists()){
                $purreqhd = $purreqhd->first();
                $purreqdt = DB::table('material.purreqdt')
                            ->where('recno','=',$purreqhd->recno)
                            ->where('compcode', '=', session('compcode'))
                            ->where('recstatus', '<>', 'DELETE');

                if($purreqdt->exists()){
                    $purreqdt = $purreqdt->get();

                    foreach ($purreqdt as $key => $value) {
                        $status = 'COMPLETED';

                        $purorddt = DB::table('material.purorddt')
                            ->where('compcode', '=', session('compcode'))
                            ->where('recno','=',$purordhd->recno)
                            ->where('lineno_','=',$value->lineno_);

                        if(!$purorddt->exists()){
                            continue;
                        }

                        $purorddt = $purorddt->first();

                        $qtyreq = $purorddt->qtyrequest;
                        $qtytxn = $purorddt->qtyorder;
                        $qtybalance = $value->qtybalance;
                        $qtyapproved = $value->qtyapproved;

                        $newbalance = Floatval($qtyreq) - Floatval($qtytxn);
                        $newqtyapproved = Floatval($qtytxn) + Floatval($qtyapproved);
                        // if($newbalance > 0){
                        //     $status = 'PARTIAL';
                        //     $status_header = 'PARTIAL';
                        // }else{
                        //     $status = 'COMPLETED';
                        // }


                        //nak buat qtyrequest1S and qtybalance1S
                        $convfactorUOM_obj = DB::table('material.uom')
                            ->select('convfactor')
                            ->where('compcode','=',session('compcode'))
                            ->where('uomcode','=',$purorddt->uomcode)
                            ->first();
                        $convfactorUOM = $convfactorUOM_obj->convfactor;

                        $qtyrequest1S_purorddt = $purorddt->qtyorder * $convfactorUOM;
                        $newqtybalance1S = Floatval($value->qtybalance1S) - Floatval($qtyrequest1S_purorddt);
                        if($newqtybalance1S > 0){
                            $status = 'PARTIAL';
                            $status_header = 'PARTIAL';
                        }else{
                            $status = 'COMPLETED';
                        }
                        //

                        DB::table('material.purreqdt')
                            ->where('idno','=',$value->idno)
                            ->update([
                                'qtybalance1S' => $newqtybalance1S,
                                'qtyapproved' => $newqtyapproved,
                                'qtybalance' => $newbalance,
                                'recstatus' => $status
                            ]);

                        DB::table('material.purorddt')
                            ->where('compcode', '=', session('compcode'))
                            ->where('recno','=',$purordhd->recno)
                            ->where('lineno_','=',$value->lineno_)
                            ->update([
                                'qtyoutstand' => $newbalance
                            ]);
                    }
                    
                    DB::table('material.purreqhd')
                        ->where('compcode','=',session('compcode'))
                        ->where('purreqno','=',$purordhd->purreqno)
                        ->update([
                            'recstatus' => $status_header
                        ]);

                }else{
                    return;
                }
            }else{
                return;
            }
        }
    }

    function get_attachment_files($auditno){
        $attachment_files = DB::table('finance.attachment')
            ->where('compcode','=',session('compcode'))
            ->where('page','=','purchaseorder')
            ->where('type','=','application/pdf')
            ->where('auditno','=',$auditno)
            ->get();

        return $attachment_files;
    }


}


