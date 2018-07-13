<?php

namespace App\Http\Controllers\material;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;

class InventoryTransactionController extends defaultController
{   
    var $gltranAmount;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        return view('material.inventoryTransaction.inventoryTransaction');
    }

    public function form(Request $request)
    {   
        DB::enableQueryLog();
        switch($request->oper){
            case 'add':
                return $this->add($request);
            case 'edit':
                return $this->edit($request);
            case 'del':
                return $this->del($request);
            case 'posted':
                return $this->posted($request);
            case 'cancel':
                return $this->cancel($request);
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

        $request_no = $this->request_no($request->trantype, $request->txndept);
        $recno = $this->recno('IV','IT');

        DB::beginTransaction();

        $table = DB::table("material.ivtmphd");

        $array_insert = [
            'trantype' => $request->trantype, 
            'docno' => $request_no,
            'recno' => $recno,
            'compcode' => session('compcode'),
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
            /*if(!empty($request->referral)){
                ////ni kalu dia amik dari do
                ////amik detail dari do sana, save dkt do detail, amik total amount
                $totalAmount = $this->save_dt_from_othr_do($request->referral,$recno);

                $srcdocno = $request->delordhd_srcdocno;
                $delordno = $request->delordhd_delordno;*/

                /*////dekat do header sana, save balik delordno dkt situ
                DB::table('material.delordno')
                ->where('purordno','=',$srcdocno)->where('compcode','=',session('compcode'))
                ->update(['delordno' => $delordno]);*/
           // }

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

        $docno = DB::table('material.ivtmphd')
                    ->select('docno')
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$request->recno)->first();
        
        if($docno->docno == $request->docno){
            // ni edit macam biasa, nothing special
            DB::beginTransaction();

            $table = DB::table("material.ivtmphd");

            $array_update = [
                'compcode' => session('compcode'),
                'upduser' => session('username'),
                'upddate' => Carbon::now("Asia/Kuala_Lumpur")
            ];

            foreach ($field as $key => $value) {
                $array_update[$value] = $request[$request->field[$key]];
            }

            try {
                //////////where//////////
                $table = $table->where('idno','=',$request->idno);
                $table->update($array_update);

                $responce = new stdClass();
                $responce->totalAmount = $request->totamount;
                echo json_encode($responce);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();

                return response('Error'.$e, 500);
            }
        }else{
           /* DB::beginTransaction();

            try{
                // ni edit kalu copy utk do dari existing po
                //1. update po.delordno lama jadi 0, kalu do yang dulu pon copy existing po 
                if($srcdocno->srcdocno != '0'){
                    DB::table('material.purordhd')
                    ->where('purordno','=', $srcdocno->srcdocno)->where('compcode','=',session('compcode'))
                    ->update(['delordno' => '0']);
                }

                //2. Delete detail from delorddt
                DB::table('material.delorddt')->where('recno','=',$request->delordhd_recno);

                //3. Update srcdocno_delordhd
                $table = DB::table("material.delordhd");

                $array_update = [
                    'compcode' => session('compcode'),
                    'upduser' => session('username'),
                    'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                ];

                foreach ($field as $key => $value) {
                    $array_update[$value] = $request[$request->field[$key]];
                }

                $table = $table->where('idno','=',$request->delordhd_idno);
                $table->update($array_update);

                $totalAmount = $request->delordhd_totamount;
                //4. Update delorddt
                if(!empty($request->referral)){
                    $totalAmount = $this->save_dt_from_othr_do($request->referral,$request->delordhd_recno);

                    $srcdocno = $request->delordhd_srcdocno;
                    $delordno = $request->delordhd_delordno;

                    ////dekat po header sana, save balik delordno dkt situ
                    DB::table('material.purordhd')
                        ->where('purordno','=',$srcdocno)->where('compcode','=',session('compcode'))
                        ->update(['delordno' => $delordno]);
                }

                $responce = new stdClass();
                $responce->totalAmount = $totalAmount;
                echo json_encode($responce);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();

                return response('Error'.$e, 500);
            }*/
        }

    }

    public function del(Request $request){

    }

    public function posted(Request $request){
       
    }

    public function cancel(Request $request){
        
    }

    public function request_no($trantype,$dept){
        $seqno = DB::table('material.sequence')
                ->select('seqno')
                ->where('trantype','=',$trantype)
                ->where('dept','=',$dept)
                ->where('recstatus','=', 'A')
                ->first();
                
        if(!$seqno){
            throw new \Exception("Sequence Number for dept $dept is not available");
        }

        DB::table('material.sequence')
            ->where('trantype','=',$trantype)->where('dept','=',$dept)
            ->update(['seqno' => intval($seqno->seqno) + 1]);
        
        return $seqno->seqno;
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

    //nak check glmasdtl exist ke tak utk sekian costcode, glaccount, year, period
    //kalu jumpa dia return true, pastu simpan actamount{month} dkn global variable gltranAmount
    public function isGltranExist($ccode,$glcode,$year,$period){
        $pvalue1 = DB::table('finance.glmasdtl')
                ->select("glaccount","actamount".$period)
                ->where('compcode','=',session('compcode'))
                ->where('year','=',$year)
                ->where('costcode','=',$ccode)
                ->where('glaccount','=',$glcode)
                ->first();
        $pvalue1 = (array)$pvalue1;

        $this->gltranAmount = $pvalue1["actamount".$period];
        return !empty($pvalue1);
    }

    public function toYear($date){
        $carbon = new Carbon($date);
        return $carbon->year;
    }

    public function toMonth($date){
        $carbon = new Carbon($date);
        return $carbon->month;
    }

    public function getyearperiod($date){
        $period = DB::table('sysdb.period')
            ->where('compcode','=',session('compcode'))
            ->get();

        $seldate = new DateTime($date);

        foreach ($period as $value) {
            $arrvalue = (array)$value;

            $year= $value->year;
            $period=0;

            for($x=1;$x<=12;$x++){
                $period = $x;

                $datefr = new DateTime($arrvalue['datefr'.$x]);
                $dateto = new DateTime($arrvalue['dateto'.$x]);
                if (($datefr <= $seldate) &&  ($dateto >= $seldate)){
                    $responce = new stdClass();
                    $responce->year = $year;
                    $responce->period = $period;
                    return $responce;
                }
            }
        }
    }

    public function save_dt_from_othr_do($refer_recno,$recno){
        $do_dt = DB::table('material.delorddt')
                ->select('compcode', 'recno', 'lineno_', 'pricecode', 'itemcode', 'uomcode','pouom',
                    'suppcode','trandate','deldept','deliverydate','qtydelivered','unitprice', 'taxcode', 
                    'perdisc', 'amtdisc', 'amtslstax', 'amount','expdate','batchno','rem_but','remarks')
                ->where('recno', '=', $refer_recno)
                ->where('compcode', '=', session('compcode'))
                ->where('recstatus', '<>', 'DELETE')
                ->get();

        foreach ($do_dt as $key => $value) {
            ///1. insert detail we get from existing purchase order
            $table = DB::table("material.delorddt");
            $table->insert([
                'compcode' => session('compcode'), 
                'recno' => $recno, 
                'lineno_' => $value->lineno_, 
                'pricecode' => $value->pricecode, 
                'itemcode' => $value->itemcode,
                'uomcode' => $value->uomcode, 
                'pouom' =>$value->pouom,
                'suppcode'=>$value->suppcode,
                'trandate'=>$value->trandate,
                'deldept'=>$value->deldept,
                'deliverydate'=>$value->deliverydate,
                'qtydelivered' => $value->qtydelivered,
                'unitprice' => $value->unitprice, 
                'taxcode' => $value->taxcode, 
                'perdisc' => $value->perdisc, 
                'amtdisc' => $value->amtdisc, 
                'amtslstax' => $value->amtslstax, 
                'amount' => $value->amount, 
                'expdate'=>$value->expdate,
                'batchno'=>$value->batchno,
                'rem_but'=>$value->rem_but,
                'adduser' => session('username'), 
                'adddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                'recstatus' => 'A', 
                'remarks' => $value->remarks
            ]);
        }
        ///2. calculate total amount from detail earlier
        $amount = DB::table('material.delorddt')
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$recno)
                    ->where('recstatus','<>','DELETE')
                    ->sum('amount');

        ///3. then update to header
        $table = DB::table('material.delorddt')
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$recno);
        $table->update([
                'totamount' => $amount, 
                //'subamount' => $amount
            ]);

        return $amount;
    }
}

