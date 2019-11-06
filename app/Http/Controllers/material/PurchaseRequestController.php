<?php

namespace App\Http\Controllers\material;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use PDF;
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
            case 'cancel':
                return $this->cancel($request);
            case 'refresh_do':
                return $this->refresh_do($request);
            default:
                return 'error happen..';
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
            'recstatus' => 'OPEN'
        ];

        foreach ($field as $key => $value) {
            $array_insert[$value] = $request[$request->field[$key]];
        }

        try {
            $idno = $table->insertGetId($array_insert);
            
            $totalAmount = 0;
            if(!empty($request->referral)){
                ////ni kalu dia amik dari po
                ////amik detail dari po sana, save dkt do detail, amik total amount
                $totalAmount = $this->save_dt_from_othr_po($request->referral,$recno,$request->purreqhd_purreqno);

                $purreqno = $request->purreqhd_purreqno;
/*
                ////dekat po header sana, save balik delordno dkt situ
                DB::table('material.purordhd')
                    ->where('purordno','=',$purreqno)->where('compcode','=',session('compcode'))
                    ->update(['delordno' => $delordno]);*/
            }

            $responce = new stdClass();
            $responce->docno = $request_no;
            $responce->recno = $recno;
            $responce->idno = $idno;
            $responce->totalAmount = $totalAmount;
            echo json_encode($responce);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
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
            'upddate' => Carbon::now("Asia/Kuala_Lumpur")
        ];

        foreach ($field as $key => $value) {
            $array_update[$value] = $request[$request->field[$key]];
        }

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

            return response('Error'.$e, 500);
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

                // 1. check authorization
                $authorise = DB::table('material.authdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('trantype','=','PR')
                    ->where('cando','=', 'A')
                    ->where('recstatus','=','Support')
                    ->where('deptcode','=',$purreqhd_get->reqdept)
                    ->orWhere('deptcode','=','ALL')
                    ->orWhere('deptcode','=','all');

                if(!$authorise->exists()){
                    throw new \Exception("Authorization for this purchase request doesnt exists");
                }

                $authorise = $authorise->get();
                $totamount = $purreqhd_get->totamount;
                $idno_auth;

                foreach ($authorise as $value) {
                    $idno_auth = $value->idno;
                    if($totamount>$value->maxlimit){
                        continue;
                    }else{
                        break;
                    }
                }

                $authorise_use = DB::table('material.authdtl')->where('idno','=',$idno_auth)->first();

                // 2. make queue
                DB::table("material.queuepr")
                    ->insert([
                        'compcode' => session('compcode'),
                        'recno' => $purreqhd_get->recno,
                        'AuthorisedID' => $authorise_use->authorid,
                        'deptcode' => $purreqhd_get->reqdept,
                        'recstatus' => 'POSTED',
                        'trantype' => 'SUPPORT',
                        'adduser' => session('username'),
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur")
                    ]);

                // 3. update status to posted
                $purreqhd->update([
                        'recstatus' => 'POSTED'
                    ]);

                DB::table("material.purreqdt")
                    ->where('recno','=',$purreqhd_get->recno)
                    ->update([
                        'recstatus' => 'POSTED',
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

    public function posted_single(Request $request){
        DB::beginTransaction();

        try{


            $purreqhd = DB::table("material.purreqhd")
                ->where('idno','=',$request->idno);

            $purreqhd_get = $purreqhd->first();

            // 1. check authorization
            $authorise = DB::table('material.authdtl')
                ->where('compcode','=',session('compcode'))
                ->where('trantype','=','PR')
                ->where('cando','=', 'A')
                ->where('recstatus','=','Support')
                ->where('deptcode','=',$purreqhd_get->reqdept)
                ->orWhere('deptcode','=','ALL')
                ->orWhere('deptcode','=','all');

            if(!$authorise->exists()){
                throw new \Exception("Authorization for this purchase request doesnt exists");
            }

            $authorise = $authorise->get();
            $totamount = $purreqhd_get->totamount;
            $idno_auth;

            foreach ($authorise as $value) {
                $idno_auth = $value->idno;
                if($totamount>$value->maxlimit){
                    continue;
                }else{
                    break;
                }
            }

            $authorise_use = DB::table('material.authdtl')->where('idno','=',$idno_auth)->first();

            // 2. make queue
            DB::table("material.queuepr")
                ->insert([
                    'compcode' => session('compcode'),
                    'recno' => $purreqhd_get->recno,
                    'AuthorisedID' => $authorise_use->authorid,
                    'deptcode' => $purreqhd_get->reqdept,
                    'recstatus' => 'POSTED',
                    'trantype' => 'SUPPORT',
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur")
                ]);

            // 3. update status to posted
            $purreqhd->update([
                    'recstatus' => 'POSTED'
                ]);

            DB::table("material.purreqdt")
                ->where('recno','=',$purreqhd_get->recno)
                ->update([
                    'recstatus' => 'POSTED',
                    'upduser' => session('username'),
                    'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                ]);


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

            return response('Error'.$e, 500);
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

            return response('Error'.$e, 500);
        }
    }

    public function cancel(Request $request){
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

            return response('Error'.$e, 500);
        }
    }

    public function support(Request $request){
         DB::beginTransaction();

        try{

            foreach ($request->idno_array as $value){

                $purreqhd = DB::table("material.purreqhd")
                    ->where('idno','=',$value);

                $purreqhd_get = $purreqhd->first();

                $purreqhd->update([
                        'recstatus' => 'SUPPORT'
                    ]);

                DB::table("material.purreqdt")
                    ->where('recno','=',$purreqhd_get->recno)
                    ->update([
                        'recstatus' => 'SUPPORT',
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                    ]);

            }

           
            DB::commit();
        
        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }
    }

    public function support_single(Request $request){
         DB::beginTransaction();

        try{


            $purreqhd = DB::table("material.purreqhd")
                ->where('idno','=',$request->idno);

            $purreqhd_get = $purreqhd->first();

            $purreqhd->update([
                    'recstatus' => 'SUPPORT'
                ]);

            DB::table("material.purreqdt")
                ->where('recno','=',$purreqhd_get->recno)
                ->update([
                    'recstatus' => 'SUPPORT',
                    'upduser' => session('username'),
                    'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                ]);


           
            DB::commit();
        
        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }
    }

    public function verify(Request $request){
         DB::beginTransaction();

        try{

            foreach ($request->idno_array as $value){

                $purreqhd = DB::table("material.purreqhd")
                    ->where('idno','=',$value);

                $purreqhd_get = $purreqhd->first();

                $purreqhd->update([
                        'recstatus' => 'VERIFY'
                    ]);

                DB::table("material.purreqdt")
                    ->where('recno','=',$purreqhd_get->recno)
                    ->update([
                        'recstatus' => 'VERIFY',
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                    ]);

            }

           
            DB::commit();
        
        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }
    }

    public function verify_single(Request $request){
         DB::beginTransaction();

        try{


            $purreqhd = DB::table("material.purreqhd")
                ->where('idno','=',$request->idno);

            $purreqhd_get = $purreqhd->first();

            $purreqhd->update([
                    'recstatus' => 'VERIFY'
                ]);

            DB::table("material.purreqdt")
                ->where('recno','=',$purreqhd_get->recno)
                ->update([
                    'recstatus' => 'VERIFY',
                    'upduser' => session('username'),
                    'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                ]);


           
            DB::commit();
        
        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
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
                        'recstatus' => 'APPROVED'
                    ]);

                DB::table("material.purreqdt")
                    ->where('recno','=',$purreqhd_get->recno)
                    ->update([
                        'recstatus' => 'APPROVED',
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                    ]);

            }

           
            DB::commit();
        
        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
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
           
            DB::commit();
        
        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
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

        $totamount_expld = explode(".", (float)$purreqhd->totamount);

        $totamt_bm_rm = $this->convertNumberToWord($totamount_expld[0])." RINGGIT ";
        $totamt_bm = $totamt_bm_rm." SAHAJA";

        if(count($totamount_expld) > 1){
            $totamt_bm_sen = $this->convertNumberToWord($totamount_expld[1])." SEN";
            $totamt_bm = $totamt_bm_rm.$totamt_bm_sen." SAHAJA";
        }

        $pdf = PDF::loadView('material.purchaseRequest.purchaseRequest_pdf',compact('purreqhd','purreqdt','totamt_bm'));
        return $pdf->stream();      

        
        return view('material.purchaseRequest.purchaseRequest_pdf',compact('purreqhd','purreqdt','totamt_bm'));
    }

    
}

