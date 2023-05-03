<?php

namespace App\Http\Controllers\material;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class QuotationDetailController extends defaultController
{   
    var $gltranAmount;
    var $srcdocno;

    public function __construct()
    {
        $this->middleware('auth');
    }

     public function form(Request $request)
    {   

        DB::enableQueryLog();
        switch($request->oper){
            case 'add': 
                return $this->add($request);
            case 'edit':
                return $this->edit($request);
            case 'edit_all':
                return $this->edit_all($request);
            case 'del':
                return $this->del($request);
            case 'delete_dd':
                return $this->delete_dd($request);
            default:
                return 'error happen..';
        }
    }

    public function chgDate($date){
        if(!empty($date)){
            $newstr=explode("/", $date);
            return $newstr[2].'-'.$newstr[1].'-'.$newstr[0];
        }else{
            return '0000-00-00';
        }
    }

    public function add(Request $request){

        
        DB::beginTransaction();

        try {

            $recno = $request->recno;
            $ivreqno = $request->ivreqno;
            $reqdept = $request->reqdept;

            $ivreqhd = DB::table("material.ivreqhd")
                            ->where('idno','=',$request->idno)
                            ->where('compcode','=','DD');

            if($ivreqhd->exists()){
                $ivreqno = $this->request_no('SR', $request->reqdept);
                $recno = $this->recno('PUR','SR');

                DB::table("material.ivreqhd")
                    ->where('idno','=',$request->idno)
                    ->update([
                        'ivreqno' => $ivreqno,
                        'recno' => $recno,
                        'compcode' => session('compcode'),
                    ]);
            }


            //$request->expdate = $this->null_date($request->expdate);
            ////1. calculate lineno_ by recno
            $sqlln = DB::table('material.ivreqdt')->select('lineno_')
                        ->where('compcode','=',session('compcode'))
                        ->where('recno','=',$recno)
                        ->count('lineno_');

            $li=intval($sqlln)+1;

            ///2. insert detail
            DB::table('material.ivreqdt')
                ->insert([
                    'compcode' => session('compcode'),
                    'reqdept' => $reqdept,
                    'recno' => $recno,
                    'lineno_' => $li,
                    'ivreqno' => $ivreqno,
                    'itemcode' => strtoupper($request->itemcode),
                    'uomcode' => strtoupper($request->uomcode),
                    'pouom' => strtoupper($request->pouom),
                    'maxqty' => $request->maxqty,
                    'qohconfirm' => $request->qohconfirm,
                    'qtyonhand' => $request->qtyonhand,
                    'netprice' => $request->netprice,
                    // 'qtytxn'=> $request->qtytxn,
                    'qtybalance'=> $request->qtyrequest,
                    'qtyrequest'=> $request->qtyrequest,
                    'adduser' => session('username'), 
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"),  
                    'recstatus' => 'OPEN', 
                    'unit' => session('unit')
                ]);

            DB::commit();

            $responce = new stdClass();
            $responce->recno = $recno;
            $responce->ivreqno = $ivreqno;
            return json_encode($responce);
        
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function edit(Request $request){

        DB::beginTransaction();

        try {
           // $request->expdate = $this->null_date($request->expdate);

            ///1. update detail
            DB::table('material.ivtmpdt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->where('lineno_','=',$request->lineno_)
                ->update([
                    'itemcode' => strtoupper($request->itemcode),
                    'uomcode' => strtoupper($request->uomcode),
                    'pouom' => strtoupper($value['pouom']),
                    'productcat' => productcat($request->productcat),
                    'qtyrequest'=> $request->qtyrequest,
                    'upduser' => session('username'), 
                    'upddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                    'recstatus' => 'OPEN', 
                ]);

            DB::commit();
          //  return response($totalAmount,200);

        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }

    }

    public function del(Request $request){

        DB::beginTransaction();

        try {

            DB::table('material.ivreqdt')
            ->where('compcode','=',session('compcode'))
            ->where('recno','=',$request->recno)
            ->where('lineno_','=',$request->lineno_)
            ->delete();

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
        
    }

    public function edit_all(Request $request){

        DB::beginTransaction();

        try {

            foreach ($request->dataobj as $key => $value) {

                ///1. update detail
                DB::table('material.ivreqdt')
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$request->recno)
                    ->where('lineno_','=',$value['lineno_'])
                    ->update([
                        'itemcode' => strtoupper($value['itemcode']),
                        'uomcode' => strtoupper($value['uomcode']),
                        'pouom' => strtoupper($value['pouom']),
                        'qtyrequest'=> $value['qtyrequest'],
                        'upduser' => session('username'), 
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                        'recstatus' => 'OPEN', 
                    ]);

            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }

    }

    public function delete_dd(Request $request){
        DB::table('material.ivreqhd')
                ->where('idno','=',$request->idno)
                ->where('compcode','=','DD')
                ->delete();
    }

}

