<?php

namespace App\Http\Controllers\material;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;


use App\Jobs\SendEmailPR;

class PurchaseOrderController extends defaultController
{   
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        return view('material.purchaseOrder.purchaseOrder');
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

        $purordno = $this->purordno('PO',$request->purordhd_prdept);
        $recno = $this->recno('PUR','PO');
        // $purreqno = $this->purreqno($request->purordhd_purreqno);


        DB::beginTransaction();

        $table = DB::table("material.purordhd");

        $array_insert = [
            'trantype' => 'PO', 
            'recno' => $recno,
            'purordno' => $purordno,
            // 'purreqno' => $purreqno,
            'compcode' => session('compcode'),
            'unit' => session('unit'),
            'adduser' => session('username'),
            'adddate' => Carbon::now(),
            'recstatus' => 'OPEN'
        ];

        foreach ($field as $key => $value) {
            $array_insert[$value] = $request[$request->field[$key]];
        }

        try {

            $idno = $table->insertGetId($array_insert);

            $totalAmount = 0;
            if(!empty($request->referral)){

                ////ni kalu dia amik dari pr
                ////amik detail dari pr sana, save dkt po detail, amik total amount
                $totalAmount = $this->save_dt_from_othr_pr($request->referral,$recno,$purordno);

                $purreqno = $request->purordhd_purreqno;
                // $purordno = $request->purordhd_purordno;

                ////dekat po header sana, save balik delordno dkt situ
                DB::table('material.purreqhd')
                    ->where('purreqno','=',$purreqno)
                    ->where('compcode','=',session('compcode'))
                    ->update(['purordno' => $purordno]);

            }

            $responce = new stdClass();
            $responce->purordno = $purordno;
            $responce->recno = $recno;
            $responce->idno = $idno;
            $responce->totalAmount = $totalAmount;

            echo json_encode($responce);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage().$e, 500);
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

        $purreqno = DB::table('material.purordhd')
                    ->select('purreqno')
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$request->purordhd_recno)->first();
       
       if($purreqno->purreqno == $request->purordhd_purreqno){
            // ni edit macam biasa, nothing special
            DB::beginTransaction();

            $table = DB::table("material.purordhd");

            $array_update = [
                'compcode' => session('compcode'),
                'upduser' => session('username'),
                'upddate' => Carbon::now()
            ];

            foreach ($field as $key => $value) {
                $array_update[$value] = $request[$request->field[$key]];
            }

            try {
                //////////where//////////
                $table = $table->where('idno','=',$request->purordhd_idno);
                $table->update($array_update);

                $responce = new stdClass();
                $responce->totalAmount = $request->purordhd_totamount;
                echo json_encode($responce);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();

                return response($e->getMessage().$e, 500);
            }
        }else{
            DB::beginTransaction();
            try{
                // ni edit kalu copy utk do dari existing po
                //1. update po.delordno lama jadi 0, kalu do yang dulu pon copy existing po 
                if($purreqno->purreqno != '0'){
                    DB::table('material.purreqhd')
                    ->where('purreqno','=', $purreqno->purreqno)->where('compcode','=',session('compcode'))
                    ->update(['purordno' => '0']);
                }

                //2. Delete detail from delorddt
                DB::table('material.purorddt')->where('recno','=',$request->purordhd_recno);

                //3. Update srcdocno_delordhd
                $table = DB::table("material.purordhd");

                $array_update = [
                    'compcode' => session('compcode'),
                    'upduser' => session('username'),
                    'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                ];

                foreach ($field as $key => $value) {
                    $array_update[$value] = $request[$request->field[$key]];
                }

                $table = $table->where('idno','=',$request->purordhd_idno);
                $table->update($array_update);

                $totalAmount = $request->purordhd_totamount;
                //4. Update delorddt
                if(!empty($request->referral)){
                    $totalAmount = $this->save_dt_from_othr_pr($request->referral,$request->purordhd_recno);

                    $purreqno = $request->purordhd_purreqno;
                    $purordno = $request->purordhd_purordno;

                    ////dekat pr header sana, save balik purordno dkt situ
                    DB::table('material.purreqhd')
                        ->where('purreqno','=',$purreqno)->where('compcode','=',session('compcode'))
                        ->update(['purordno' => $purordno]);  
                }

                $responce = new stdClass();
                $responce->totalAmount = $totalAmount;
                echo json_encode($responce);

                // DB::commit();
            } catch (\Exception $e) {
                DB::rollback();

                return response($e->getMessage().$e, 500);
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


                if(!$this->skip_authorization($request,$purordhd_get->prdept,$value)){

                    // 1. check authorization
                    $authorise = DB::table('material.authdtl')
                        ->where('compcode','=',session('compcode'))
                        ->where('trantype','=','PO')
                        ->where('cando','=', 'ACTIVE')
                        ->where('recstatus','=','SUPPORT')
                        ->where('deptcode','=',$purordhd_get->prdept)
                        ->where('maxlimit','>=',$purordhd_get->totamount);

                    if(!$authorise->exists()){

                        $authorise = DB::table('material.authdtl')
                            ->where('compcode','=',session('compcode'))
                            ->where('trantype','=','PO')
                            ->where('cando','=', 'ACTIVE')
                            ->where('recstatus','=','SUPPORT')
                            ->where('deptcode','=','ALL')
                            ->where('deptcode','=','all')
                            ->where('maxlimit','>=',$purordhd_get->totamount);

                            if(!$authorise->exists()){
                                throw new \Exception("Authorization for this Purchase Order doesnt exists");
                            }

                    }

                    $authorise_use = $authorise->first();
                    DB::table("material.queuepo")
                        ->insert([
                            'compcode' => session('compcode'),
                            'recno' => $purordhd_get->recno,
                            'AuthorisedID' => $authorise_use->authorid,
                            'deptcode' => $purordhd_get->prdept,
                            'recstatus' => 'REQUEST',
                            'trantype' => 'SUPPORT',
                            'adduser' => session('username'),
                            'adddate' => Carbon::now("Asia/Kuala_Lumpur")
                        ]);

                    // 3. change recstatus to posted
                    $purordhd
                        ->update([
                            'requestby' => session('username'),
                            'requestdate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'supportby' => $authorise_use->authorid,
                            'recstatus' => 'REQUEST'
                        ]);

                    DB::table("material.purorddt")
                        ->where('recno','=',$purordhd_get->recno)
                        ->update([
                            'recstatus' => 'REQUEST',
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

                }

            }
           
            DB::commit();
        
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage().$e, 500);
        }
    }       

    public function posted_single(Request $request){
        DB::beginTransaction();

        try{


            $purordhd = DB::table("material.purordhd")
                ->where('idno','=',$request->idno);

            $purordhd_get = $purordhd->first();


            if(!$this->skip_authorization($request,$purordhd_get->prdept,$request->idno)){

                // 1. check authorization
                $authorise = DB::table('material.authdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('trantype','=','PO')
                    ->where('cando','=', 'ACTIVE')
                    ->where('recstatus','=','SUPPORT')
                    ->where('deptcode','=',$purordhd_get->prdept)
                    ->where('maxlimit','>=',$purordhd_get->totamount);

                if(!$authorise->exists()){

                    $authorise = DB::table('material.authdtl')
                        ->where('compcode','=',session('compcode'))
                        ->where('trantype','=','PO')
                        ->where('cando','=', 'ACTIVE')
                        ->where('recstatus','=','SUPPORT')
                        ->where('deptcode','=','ALL')
                        ->where('deptcode','=','all')
                        ->where('maxlimit','>=',$purordhd_get->totamount);

                        if(!$authorise->exists()){
                            throw new \Exception("Authorization for this Purchase Order doesnt exists");
                        }

                }

                $authorise_use = $authorise->first();
                DB::table("material.queuepo")
                    ->insert([
                        'compcode' => session('compcode'),
                        'recno' => $purordhd_get->recno,
                        'AuthorisedID' => $authorise_use->authorid,
                        'deptcode' => $purordhd_get->prdept,
                        'recstatus' => 'REQUEST',
                        'trantype' => 'SUPPORT',
                        'adduser' => session('username'),
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur")
                    ]);

                // 3. change recstatus to posted
                $purordhd
                    ->update([
                        'requestby' => session('username'),
                        'requestdate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'supportby' => $authorise_use->authorid,
                        'recstatus' => 'REQUEST'
                    ]);

                DB::table("material.purorddt")
                    ->where('recno','=',$purordhd_get->recno)
                    ->update([
                        'recstatus' => 'REQUEST',
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

            $purordhd = DB::table("material.purordhd")
                ->where('idno','=',$request->idno);

            $purordhd_get = $purordhd->first();

            $purordhd->update([
                    'recstatus' => 'OPEN'
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

            DB::commit();
        
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage().$e, 500);
        }
    }

     public function soft_cancel(Request $request){

         DB::beginTransaction();

        try{

            foreach ($request->idno_array as $value){

                $purordhd = DB::table("material.purordhd")
                    ->where('idno','=',$value);

                $purordhd_get = $purordhd->first();

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


            DB::commit();
        
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage().$e, 500);
        }
    }

    public function cancel(Request $request){
        DB::beginTransaction();

        try{

            $purordhd = DB::table("material.purordhd")
                ->where('idno','=',$request->idno);

            $purordhd_get = $purordhd->first();


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

            return response($e->getMessage().$e, 500);
        }
    }                      

    public function support(Request $request){
         DB::beginTransaction();

        try{

            foreach ($request->idno_array as $value){

                $purordhd = DB::table("material.purordhd")
                    ->where('idno','=',$value);

                $purordhd_get = $purordhd->first();

                $authorise = DB::table('material.authdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('trantype','=','PO')
                    ->where('cando','=', 'ACTIVE')
                    ->where('recstatus','=','VERIFIED')
                    ->where('deptcode','=',$purordhd_get->prdept)
                    ->where('maxlimit','>=',$purordhd_get->totamount);

                if(!$authorise->exists()){

                    $authorise = DB::table('material.authdtl')
                        ->where('compcode','=',session('compcode'))
                        ->where('trantype','=','PO')
                        ->where('cando','=', 'ACTIVE')
                        ->where('recstatus','=','VERIFIED')
                        ->where('deptcode','=','ALL')
                        ->where('deptcode','=','all')
                        ->where('maxlimit','>=',$purordhd_get->totamount);

                        if(!$authorise->exists()){
                            throw new \Exception("Authorization for this purchase order doesnt exists",500);
                        }
                        
                }

                $authorise_use = $authorise->first();

                $purordhd->update([
                        'verifiedby' => $authorise_use->authorid,
                        'supportdate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'recstatus' => 'SUPPORT'
                    ]);

                DB::table("material.purorddt")
                    ->where('recno','=',$purordhd_get->recno)
                    ->update([
                        'recstatus' => 'SUPPORT',
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                    ]);

                DB::table("material.queuepo")
                    ->where('recno','=',$purordhd_get->recno)
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
            $data->status = 'VERIFIED';
            $data->deptcode = $purordhd_get->reqdept;
            $data->purreqno = $purordhd_get->purreqno;
            $data->email_to = 'hazman.yusof@gmail.com';
            $data->whatsapp = '01123090948';

           // $this->sendemail($data);


           
            DB::commit();
        
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage().$e, 500);
        }
    }

    public function support_single(Request $request){
         DB::beginTransaction();

        try{
            
            $purordhd = DB::table("material.purordhd")
                ->where('idno','=',$request->idno);

            $purordhd_get = $purordhd->first();

            $authorise = DB::table('material.authdtl')
                ->where('compcode','=',session('compcode'))
                ->where('trantype','=','PO')
                ->where('cando','=', 'ACTIVE')
                ->where('recstatus','=','VERIFIED')
                ->where('deptcode','=',$purordhd_get->prdept)
                ->where('maxlimit','>=',$purordhd_get->totamount);

            if(!$authorise->exists()){

                $authorise = DB::table('material.authdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('trantype','=','PO')
                    ->where('cando','=', 'ACTIVE')
                    ->where('recstatus','=','VERIFIED')
                    ->where('deptcode','=','ALL')
                    ->where('deptcode','=','all')
                    ->where('maxlimit','>=',$purordhd_get->totamount);

                    if(!$authorise->exists()){
                        throw new \Exception("Authorization for this purchase order doesnt exists",500);
                    }
                    
            }

            $authorise_use = $authorise->first();

            $purordhd->update([
                    'verifiedby' => $authorise_use->authorid,
                    'supportdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'recstatus' => 'SUPPORT'
                ]);

            DB::table("material.purorddt")
                ->where('recno','=',$purordhd_get->recno)
                ->update([
                    'recstatus' => 'SUPPORT',
                    'upduser' => session('username'),
                    'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                ]);

            DB::table("material.queuepo")
                ->where('recno','=',$purordhd_get->recno)
                ->update([
                    'AuthorisedID' => $authorise_use->authorid,
                    'recstatus' => 'SUPPORT',
                    'trantype' => 'VERIFIED',
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur")
                ]);
           
            DB::commit();

            // 4. email and whatsapp
            $data = new stdClass();
            $data->status = 'VERIFIED';
            $data->deptcode = $purordhd_get->reqdept;
            $data->purreqno = $purordhd_get->purreqno;
            $data->email_to = 'hazman.yusof@gmail.com';
            $data->whatsapp = '01123090948';

          //  $this->sendemail($data);

        
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage().$e, 500);
        }
    }

    public function verify(Request $request){
         DB::beginTransaction();

        try{

            foreach ($request->idno_array as $value){

                $purordhd = DB::table("material.purordhd")
                    ->where('idno','=',$value);

                $purordhd_get = $purordhd->first();

                $authorise = DB::table('material.authdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('trantype','=','PO')
                    ->where('cando','=', 'ACTIVE')
                    ->where('recstatus','=','APPROVED')
                    ->where('deptcode','=',$purordhd_get->reqdept)
                    ->where('maxlimit','>=',$purordhd_get->totamount);

                if(!$authorise->exists()){

                    $authorise = DB::table('material.authdtl')
                        ->where('compcode','=',session('compcode'))
                        ->where('trantype','=','PO')
                        ->where('cando','=', 'ACTIVE')
                        ->where('recstatus','=','APPROVED')
                        ->where('deptcode','=','ALL')
                        ->where('deptcode','=','all')
                        ->where('maxlimit','>=',$purordhd_get->totamount);

                        if(!$authorise->exists()){
                            throw new \Exception("Authorization for this purchase order doesnt exists",500);
                        }
                        
                }

                $authorise_use = $authorise->first();

                $purordhd->update([
                        'approvedby' => $authorise_use->authorid,
                        'verifieddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'recstatus' => 'VERIFIED'
                    ]);

                DB::table("material.purorddt")
                    ->where('recno','=',$purordhd_get->recno)
                    ->update([
                        'recstatus' => 'VERIFIED',
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                    ]);

                DB::table("material.queuepo")
                    ->where('recno','=',$purordhd_get->recno)
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
            $data->deptcode = $purordhd_get->reqdept;
            $data->purreqno = $purordhd_get->purreqno;
            $data->email_to = 'hazman.yusof@gmail.com';
            $data->whatsapp = '01123090948';

           // $this->sendemail($data);

           
            DB::commit();
        
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage().$e, 500);
        }
    }

    public function verify_single(Request $request){
         DB::beginTransaction();

        try{
            
            $purordhd = DB::table("material.purordhd")
                ->where('idno','=',$request->idno);

            $purordhd_get = $purordhd->first();

            $authorise = DB::table('material.authdtl')
                ->where('compcode','=',session('compcode'))
                ->where('trantype','=','PO')
                ->where('cando','=', 'ACTIVE')
                ->where('recstatus','=','APPROVED')
                ->where('deptcode','=',$purordhd_get->reqdept)
                ->where('maxlimit','>=',$purordhd_get->totamount);

            if(!$authorise->exists()){

                $authorise = DB::table('material.authdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('trantype','=','PO')
                    ->where('cando','=', 'ACTIVE')
                    ->where('recstatus','=','APPROVED')
                    ->where('deptcode','=','ALL')
                    ->where('deptcode','=','all')
                    ->where('maxlimit','>=',$purordhd_get->totamount);

                    if(!$authorise->exists()){
                        throw new \Exception("Authorization for this purchase order doesnt exists",500);
                    }
                    
            }

            $authorise_use = $authorise->first();

            $purordhd->update([
                    'approvedby' => $authorise_use->authorid,
                    'verifieddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'recstatus' => 'VERIFIED'
                ]);

            DB::table("material.purorddt")
                ->where('recno','=',$purordhd_get->recno)
                ->update([
                    'recstatus' => 'VERIFIED',
                    'upduser' => session('username'),
                    'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                ]);

            DB::table("material.queuepo")
                ->where('recno','=',$purordhd_get->recno)
                ->update([
                    'AuthorisedID' => $authorise_use->authorid,
                    'recstatus' => 'VERIFIED',
                    'trantype' => 'APPROVED',
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur")
                ]);
           
            DB::commit();

            // 4. email and whatsapp
            $data = new stdClass();
            $data->status = 'APPROVED';
            $data->deptcode = $purordhd_get->reqdept;
            $data->purreqno = $purordhd_get->purreqno;
            $data->email_to = 'hazman.yusof@gmail.com';
            $data->whatsapp = '01123090948';

          //  $this->sendemail($data);

        
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage().$e, 500);
        }
    }

    public function approved(Request $request){
         DB::beginTransaction();

        try{

            foreach ($request->idno_array as $value){

                $purordhd = DB::table("material.purordhd")
                    ->where('idno','=',$value);

                $purordhd_get = $purordhd->first();

                $purordhd->update([
                        'approveddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'recstatus' => 'APPROVED'
                    ]);

                DB::table("material.purorddt")
                    ->where('recno','=',$purordhd_get->recno)
                    ->update([
                        'recstatus' => 'APPROVED',
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                    ]);

                DB::table("material.queuepo")
                    ->where('recno','=',$purordhd_get->recno)
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

            return response($e->getMessage().$e, 500);
        }
    }

    public function approved_single(Request $request){
         DB::beginTransaction();

        try{

            $purordhd = DB::table("material.purordhd")
                    ->where('idno','=',$request->idno);

            $purordhd_get = $purordhd->first();

            $purordhd->update([
                    'approveddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'recstatus' => 'APPROVED'
                ]);

            DB::table("material.purorddt")
                ->where('recno','=',$purordhd_get->recno)
                ->update([
                    'recstatus' => 'APPROVED',
                    'upduser' => session('username'),
                    'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                ]);

            DB::table("material.queuepo")
                ->where('recno','=',$purordhd_get->recno)
                ->update([
                    'recstatus' => 'APPROVED',
                    'trantype' => 'DONE',
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur")
                ]);
           
            DB::commit();
        
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage().$e, 500);
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

        $purordhd = DB::table('material.purordhd')
            ->where('recno','=',$recno)
            ->first();

        $purorddt = DB::table('material.purorddt AS podt', 'material.productmaster AS p', 'material.uom as u')
            ->select('podt.compcode', 'podt.recno', 'podt.lineno_', 'podt.pricecode', 'podt.itemcode', 'p.description', 'podt.uomcode', 'podt.pouom', 'podt.qtyrequest', 'podt.unitprice', 'podt.taxcode', 'podt.perdisc', 'podt.amtdisc', 'podt.amtslstax as tot_gst','podt.netunitprice', 'podt.totamount','podt.amount', 'podt.rem_but AS remarks_button', 'podt.remarks', 'podt.recstatus', 'podt.unit', 'u.description as uom_desc')
            ->leftJoin('material.productmaster as p', 'podt.itemcode', '=', 'p.itemcode')
            ->leftJoin('material.uom as u', 'podt.uomcode', '=', 'u.uomcode')
            ->where('recno','=',$recno)
            ->get();

        $totamount_expld = explode(".", (float)$purordhd->totamount);

        $totamt_bm_rm = $this->convertNumberToWord($totamount_expld[0])." RINGGIT ";
        $totamt_bm = $totamt_bm_rm." SAHAJA";

        if(count($totamount_expld) > 1){
            $totamt_bm_sen = $this->convertNumberToWord($totamount_expld[1])." SEN";
            $totamt_bm = $totamt_bm_rm.$totamt_bm_sen." SAHAJA";
        }

        $pdf = PDF::loadView('material.purchaseOrder.purchaseOrder_pdf',compact('purordhd','purorddt','totamt_bm'));
        return $pdf->stream();      

        
        return view('material.purchaseOrder.purchaseOrder_pdf',compact('purordhd','purorddt','totamt_bm'));
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

    public function save_dt_from_othr_pr($refer_recno,$recno,$purordno){
        $pr_dt = DB::table('material.purreqdt')
                ->select('compcode', 'recno', 'lineno_', 'pricecode', 'itemcode', 'uomcode', 'pouom', 'qtyrequest', 'unitprice', 'taxcode','perdisc','amtdisc', 'amtslstax','amount','netunitprice','totamount','recstatus','remarks')
                ->where('recno', '=', $refer_recno)
                ->where('compcode', '=', session('compcode'))
                ->where('recstatus', '<>', 'DELETE')
                ->get();

        foreach ($pr_dt as $key => $value) {
            ///1. insert detail we get from existing purchase request
            $table = DB::table("material.purorddt");
            $table->insert([
                'compcode' => session('compcode'), 
                'recno' => $recno, 
                'lineno_' => $value->lineno_, 
                'pricecode' => $value->pricecode, 
                'itemcode' => $value->itemcode, 
                'uomcode' => $value->uomcode, 
                'pouom' => $value->pouom, 
                'qtyorder' => $value->qtyrequest, 
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
                'remarks' => $value->remarks
            ]);

            
        }
        ///2. calculate total amount from detail erlier
        $amount = DB::table('material.purorddt')
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$recno)
                    ->where('recstatus','<>','DELETE')
                    ->sum('amount');

        ///3. then update to header
        $table = DB::table('material.purordhd')
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$recno);
        $table->update([
                'totamount' => $amount, 
                'subamount' => $amount
            ]);

        $this->check_incompleted($recno);

        return $amount;
    }

    function check_incompleted($recno){
        $incompleted = false;
        $purorddt_null = DB::table('material.purorddt')
                            ->where('compcode','=',session('compcode'))
                            ->where('recno','=',$recno)
                            ->where('recstatus','<>','DELETE')
                            ->whereNull('unitprice')
                            ->orWhereNull('pouom');

        $purorddt_empty = DB::table('material.purorddt')
                            ->where('compcode','=',session('compcode'))
                            ->where('recno','=',$recno)
                            ->where('recstatus','<>','DELETE')
                            ->where('unitprice','=','0')
                            ->orWhere('pouom','=','');



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
                    ->where('deptcode','=',$deptcode)
                    ->where('recstatus','=','APPROVED');

        if($authdtl->count() > 0){


            $purordhd = DB::table("material.purordhd")
                ->where('idno','=',$idno);

            $purordhd_get = $purordhd->first();

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

            $purordhd->update($array_update);

            DB::table("material.purorddt")
                ->where('recno','=',$purordhd_get->recno)
                ->update([
                    'recstatus' => 'APPROVED',
                    'upduser' => session('username'),
                    'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                ]);

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


}


