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

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        return view('material.purchaseRequest.purchaseRequest');
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
            case 'posted_single':
                return $this->posted_single($request);
            case 'reopen_single':
                return $this->reopen($request);
            case 'soft_cancel':
                return $this->soft_cancel($request);
            case 'support':
                return $this->support($request);
            case 'support_single':
                return $this->support_single($request);
            case 'verify':
                return $this->verify($request);
            case 'verify_single':
                return $this->verify_single($request);
            case 'approved':
                return $this->approved($request);
            case 'approved_single':
                return $this->approved_single($request);
            case 'cancel_single':
                return $this->cancel($request);
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

        $request_no = $this->request_no('PR', $request->purreqhd_reqdept);
        $recno = $this->recno('PUR','PR');

        DB::beginTransaction();

        $table = DB::table("material.purreqhd");

        $array_insert = [
            'trantype' => 'PR', 
            'purreqno' => $request_no,
            'recno' => $recno,
            'compcode' => session('compcode'),
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

        // foreach ($field as $key => $value) {
        //     if($value =='remarks' || $value =='prdept' || $value =='suppcode' || $value =='reqdept'){
        //         $array_insert[$value] = strtoupper($request[$request->field[$key]]);
        //     }else{
        //         $array_insert[$value] = $request[$request->field[$key]];
        //     }
        // }
        
        try {
            $idno = $table->insertGetId($array_insert);
            
            $totalAmount = 0;

            $responce = new stdClass();
            $responce->docno = $request_no;
            $responce->recno = $recno;
            $responce->idno = $idno;
            $responce->totalAmount = $totalAmount;
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

        try{

            foreach ($request->idno_array as $value){

                $purreqhd = DB::table("material.purreqhd")
                    ->where('idno','=',$value);

                $purreqhd_get = $purreqhd->first();

                if(!$this->skip_authorization($request,$purreqhd_get->reqdept,$value)){

                    // 1. check authorization
                    $authorise = DB::table('material.authdtl')
                        ->where('compcode','=',session('compcode'))
                        ->where('trantype','=','PR')
                        ->where('cando','=', 'ACTIVE')
                        ->where('recstatus','=','SUPPORT')
                        ->where('deptcode','=',$purreqhd_get->reqdept)
                        ->where('maxlimit','>=',$purreqhd_get->totamount);

                    if(!$authorise->exists()){

                        $authorise = DB::table('material.authdtl')
                            ->where('compcode','=',session('compcode'))
                            ->where('trantype','=','PR')
                            ->where('cando','=', 'ACTIVE')
                            ->where('recstatus','=','SUPPORT')
                            ->where('deptcode','=','ALL')
                            // ->where('deptcode','=','all')
                            ->where('maxlimit','>=',$purreqhd_get->totamount);

                            if(!$authorise->exists()){
                                throw new \Exception("Authorization for this purchase request doesnt exists");
                            }

                    }

                    $authorise_use = $authorise->first();
                    DB::table("material.queuepr")
                        ->insert([
                            'compcode' => session('compcode'),
                            'recno' => $purreqhd_get->recno,
                            'AuthorisedID' => $authorise_use->authorid,
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
                            'supportby' => $authorise_use->authorid,
                            'recstatus' => 'REQUEST'
                        ]);

                    DB::table("material.purreqdt")
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
                }
            }

            DB::commit();
        
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function posted_single(Request $request){
        DB::beginTransaction();
        try{

            $purreqhd = DB::table("material.purreqhd")
                ->where('idno','=',$request->idno);

            $purreqhd_get = $purreqhd->first();


            if(!$this->skip_authorization($request,$purreqhd_get->reqdept,$request->idno)){

                // 1. check authorization
                $authorise = DB::table('material.authdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('trantype','=','PR')
                    ->where('cando','=', 'ACTIVE')
                    ->where('recstatus','=','SUPPORT')
                    ->where('deptcode','=',$purreqhd_get->reqdept)
                    ->where('maxlimit','>=',$purreqhd_get->totamount);

                if(!$authorise->exists()){

                    $authorise = DB::table('material.authdtl')
                        ->where('compcode','=',session('compcode'))
                        ->where('trantype','=','PR')
                        ->where('cando','=', 'ACTIVE')
                        ->where('recstatus','=','SUPPORT')
                        ->where('deptcode','=','ALL')
                        ->where('deptcode','=','all')
                        ->where('maxlimit','>=',$purreqhd_get->totamount);

                        if(!$authorise->exists()){
                            throw new \Exception("Authorization for this purchase request doesnt exists");
                        }

                }

                $authorise_use = $authorise->first();
                DB::table("material.queuepr")
                    ->insert([
                        'compcode' => session('compcode'),
                        'recno' => $purreqhd_get->recno,
                        'AuthorisedID' => $authorise_use->authorid,
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
                        'supportby' => $authorise_use->authorid,
                        'recstatus' => 'REQUEST'
                    ]);

                DB::table("material.purreqdt")
                    ->where('recno','=',$purreqhd_get->recno)
                    ->update([
                        'recstatus' => 'REQUEST',
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                    ]);

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

    public function reopen(Request $request){

         DB::beginTransaction();

        try{

            $purreqhd = DB::table("material.purreqhd")
                ->where('idno','=',$request->idno);

            $purreqhd_get = $purreqhd->first();

            $purreqhd->update([
                    'recstatus' => 'OPEN'
                ]);

            DB::table("material.purreqdt")
                ->where('recno','=',$purreqhd_get->recno)
                ->update([
                    'recstatus' => 'OPEN',
                    'upduser' => session('username'),
                    'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                ]);

            DB::table("material.queuepr")
                ->where('recno','=',$purreqhd_get->recno)
                ->delete();

            DB::commit();
        
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function soft_cancel(Request $request){

         DB::beginTransaction();

        try{

            foreach ($request->idno_array as $value){

                $purreqhd = DB::table("material.purreqhd")
                    ->where('idno','=',$value);

                $purreqhd_get = $purreqhd->first();

                $purreqhd->update([
                        'recstatus' => 'CANCELLED'
                    ]);

                DB::table("material.purreqdt")
                    ->where('recno','=',$purreqhd_get->recno)
                    ->update([
                        'recstatus' => 'CANCELLED',
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur")
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

            $purreqhd = DB::table("material.purreqhd")
                ->where('idno','=',$request->idno);

            $purreqhd_get = $purreqhd->first();

            $purreqhd->update([
                    'recstatus' => 'CANCELLED'
                ]);

            DB::table("material.purreqdt")
                ->where('recno','=',$purreqhd_get->recno)
                ->update([
                    'recstatus' => 'CANCELLED',
                    'upduser' => session('username'),
                    'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                ]);

            DB::table("material.queuepr")
                ->where('recno','=',$purreqhd_get->recno)
                ->delete();
           
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

    public function support_single(Request $request){
         DB::beginTransaction();

        try{


            $purreqhd = DB::table("material.purreqhd")
                ->where('idno','=',$request->idno);

            $purreqhd_get = $purreqhd->first();

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

            // 4. email and whatsapp
            $data = new stdClass();
            $data->status = 'VERIFIED';
            $data->deptcode = $purreqhd_get->reqdept;
            $data->purreqno = $purreqhd_get->purreqno;
            $data->email_to = 'hazman.yusof@gmail.com';
            $data->whatsapp = '01123090948';

            //$this->sendemail($data);
           
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

    public function verify_single(Request $request){
         DB::beginTransaction();

        try{


            $purreqhd = DB::table("material.purreqhd")
                ->where('idno','=',$request->idno);

            $purreqhd_get = $purreqhd->first();

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
                    
            // 4. email and whatsapp
            $data = new stdClass();
            $data->status = 'APPROVED';
            $data->deptcode = $purreqhd_get->reqdept;
            $data->purreqno = $purreqhd_get->purreqno;
            $data->email_to = 'hazman.yusof@gmail.com';
            $data->whatsapp = '01123090948';

            //$this->sendemail($data);
           
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

    public function approved_single(Request $request){
         DB::beginTransaction();

        try{

            $purreqhd = DB::table("material.purreqhd")
                ->where('idno','=',$request->idno);

            $purreqhd_get = $purreqhd->first();

            $purreqhd->update([
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
            ->where('recno','=',$recno)
            ->get();

        $company = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();

        $totamount_expld = explode(".", (float)$purreqhd->totamount);

        $totamt_bm_rm = $this->convertNumberToWord($totamount_expld[0])." RINGGIT ";
        $totamt_bm = $totamt_bm_rm." SAHAJA";

        if(count($totamount_expld) > 1){
            $totamt_bm_sen = $this->convertNumberToWord($totamount_expld[1])." SEN";
            $totamt_bm = $totamt_bm_rm.$totamt_bm_sen." SAHAJA";
        }

        $pdf = PDF::loadView('material.purchaseRequest.purchaseRequest_pdf',compact('purreqhd','purreqdt','totamt_bm','company'));
        return $pdf->stream();      

        
        return view('material.purchaseRequest.purchaseRequest_pdf',compact('purreqhd','purreqdt','totamt_bm','company'));
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
                    ->where('deptcode','=',$deptcode)
                    ->where('recstatus','=','APPROVED');

        if($authdtl->count() > 0){
            dump('true');
            $purreqhd = DB::table("material.purreqhd")
                ->where('idno','=',$request->idno);

            $purreqhd_get = $purreqhd->first();

            $purreqhd->update([
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

            return true;
        }
        
        return false;   
        
    }

    
}

