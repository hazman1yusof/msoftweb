<?php

namespace App\Http\Controllers\material;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class GoodReturnCreditDetailController extends defaultController
{   
    var $gltranAmount;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function form(Request $request)
    {   
        switch($request->oper){
            case 'add':
                return $this->add($request);
            case 'edit':
                // return $this->edit($request);
            case 'edit_all':
                return $this->edit_all($request);
            case 'del':
                return $this->del($request);
            default:
                return 'error happen..';
        }
    }

    public function get_draccno($itemcode){
        $query = DB::table('material.category')
                ->select('category.stockacct')
                ->join('material.product', 'category.catcode', '=', 'product.productcat')
                ->where('product.itemcode','=',$itemcode)
                ->first();
        
        return $query->stockacct;
    }

    public function get_drccode($deldept){
        $query = DB::table('sysdb.department')
                ->select('costcode')
                ->where('compcode','=',session('compcode'))
                ->where('deptcode','=',$deldept)
                ->first();
        
        return $query->costcode;
    }

    public function get_craccno(){
        $query = DB::table('sysdb.sysparam')
                ->select('pvalue2')
                ->where('compcode','=',session('compcode'))
                ->where('source','=','AP')
                ->where('trantype','=','ACC')
                ->first();
        
        return $query->pvalue2;
    }

    public function get_crccode(){
        $query = DB::table('sysdb.sysparam')
                ->select('pvalue1')
                ->where('compcode','=',session('compcode'))
                ->where('source','=','AP')
                ->where('trantype','=','ACC')
                ->first();
        
        return $query->pvalue1;
    }

    public function chgDate($date){
        if(!empty($date)){
            $newstr=explode("/", $date);
            return $newstr[2].'-'.$newstr[1].'-'.$newstr[0];
        }else{
           return null;
        }
    }

    public function add(Request $request){

        DB::beginTransaction();

        try {

            $delordhd = DB::table('material.delordhd')
                            ->where('compcode','DD')
                            ->where('idno',$request->doidno);

            if($delordhd->exists()){
                $delordhd = $delordhd->first();
                $request_no = $this->request_no('GRT', $delordhd->deldept);
                $recno = $this->recno('IV','IT');
                $compcode = session('compcode');
                $cnno = $this->recno('PB','CN');
                
                $array_insert = [
                    'source' => 'PB',
                    'trantype' => 'CN',
                    'auditno' => $cnno,
                    'compcode' => session('compcode'),
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'recstatus' => 'OPEN',
                    'lineno_' => 1,
                    'recptno' => 'CN-'.$cnno,
                    // 'invno' => $invno,
                    'deptcode' => $delordhd->deldept,
                    'unit' => session('unit'),
                    'debtorcode' => $delordhd->debtorcode,
                    'payercode' => $delordhd->debtorcode,
                    'entrydate' => $delordhd->trandate,
                    'entrytime' => $delordhd->trantime,
                    'entryuser' => session('username'),
                    'hdrtype' => $delordhd->hdrtype,
                    'mrn' => $delordhd->mrn,
                    // 'billno' => $invno,
                    // 'episno' => (!empty($request->db_mrn))?$pat_mast->Episno:null,
                    //'termdays' => strtoupper($request->db_termdays),
                    // 'termmode' => strtoupper($request->db_termmode),
                    // 'orderno' => strtoupper($request->db_orderno),
                    // 'ponum' => strtoupper($request->db_ponum),
                    'remark' => $delordhd->remarks,
                    // 'approvedby' => $request->db_approvedby,
                    // 'approveddate' => $request->db_approveddate,
                    // 'reference' => $request->db_reference,
                    // 'paymode' => $delordhd->paymode,
                    // 'unallocated' => $request->db_unallocated,   
                ];

                DB::table("debtor.dbacthdr")->insert($array_insert);

                DB::table("material.delordhd")
                        ->where('compcode','DD')
                        ->where('idno',$request->doidno)
                        ->update([
                            'compcode' => session('compcode'),
                            'docno' => $request_no,
                            'recno' => $recno,
                            'cnno' => 'CN-'.$cnno
                        ]);
            }else{

                $delordhd = DB::table('material.delordhd')
                            ->where('compcode',session('compcode'))
                            ->where('idno',$request->doidno)
                            ->first();

                $recno = $delordhd->recno;
                $cnno = substr($delordhd->cnno,3);
            }

            ////1. calculate lineno_ by recno
            $sqlln = DB::table('material.delorddt')
                        // ->where('compcode','=',session('compcode'))
                        ->where('recno','=',$recno)
                        ->max('lineno_');

            $li=intval($sqlln)+1;

            ///2. insert detail
            DB::table('material.delorddt')
                ->insert([
                    'compcode' => session('compcode'),
                    'recno' => $recno,
                    'lineno_' => $li,
                    // 'pricecode' => $request->pricecode,
                    'itemcode' => $request->itemcode,
                    'uomcode' => $request->uomcode,
                    'pouom' => $request->uomcode,
                    // 'suppcode' => $delordhd->suppcode,
                    'trandate' => $delordhd->trandate,
                    'deldept' => $delordhd->deldept,
                    // 'deliverydate' => $request->deliverydate,
                    'unitprice' => $request->unitprice, 
                    'taxcode' => $request->taxcode,
                    'perdisc' => $request->perdisc,
                    'amtdisc' => $request->amtdisc,
                    'amtslstax' => $request->tot_gst,
                    'netunitprice' => $request->unitprice,
                    // 'qtydelivered' => $request->qtydelivered,
                    'qtyreturned' => $request->qtyreturned,
                    'amount' => $request->totamount,
                    'totamount' => $request->totamount,
                    // 'draccno' => $draccno,
                    // 'drccode' => $drccode,
                    // 'craccno' => $craccno,
                    // 'crccode' => $crccode, 
                    'adduser' => session('username'), 
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                    // 'expdate' => $this->chgDate($request->expdate), 
                    // 'batchno' => $request->batchno, 
                    'recstatus' => 'OPEN', 
                    'remarks' => $request->remarks
                ]);

            ///2. insert detail
            DB::table('debtor.dbactdtl')
                ->insert([
                    'compcode' => session('compcode'),
                    'source' => 'PB',
                    'trantype' => 'CN',
                    'auditno' => $cnno,
                    'lineno_' => $li,
                    'entrydate' => Carbon::now("Asia/Kuala_Lumpur"),
                    // 'document' => ,
                    'reference' => $request->itemcode,
                    'amount' => $request->totamount,
                    // 'stat' => ,
                    'mrn' => $delordhd->mrn,
                    // 'episno' => ,
                    // 'billno' => ,
                    // 'paymode' => $delordhd->paymode,
                    // 'allocauditno' => ,
                    // 'alloclineno' => ,
                    // 'alloctnauditno' => ,
                    // 'alloctnlineno' => ,
                    'lastuser' => session('username'),
                    'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    // 'grnno' => ,
                    // 'dorecno' => ,
                    // 'category' => ,
                    'deptcode' => $delordhd->deldept,
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'recstatus' => 'OPEN',
                    // 'upduser' => ,
                    // 'upddate' => ,
                    // 'deluser' => ,
                    // 'deldate' => ,
                    'GSTCode' => $request->taxcode,
                    'AmtB4GST' => $request->totamount,
                    'unit' => session('unit'),
                    // 'tot_gst' => ,
                ]);

            ///3. calculate total amount from detail
            $totalAmount = DB::table('material.delorddt')
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$recno)
                    ->where('recstatus','!=','DELETE')
                    ->sum('totamount');

            //calculate tot gst from detail
            $tot_gst = DB::table('material.delorddt')
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$recno)
                    ->where('recstatus','!=','DELETE')
                    ->sum('amtslstax');

            ///4. then update to header
            DB::table('material.delordhd')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$recno)
                ->update([
                    'totamount' => $totalAmount, 
                    'subamount'=> $totalAmount, 
                    'TaxAmt' => $tot_gst
                ]);

            ///4. then update to header
            DB::table('debtor.dbacthdr')
                ->where('compcode','=',session('compcode'))
                ->where('source','PB')
                ->where('trantype','CN')
                ->where('auditno',$cnno)
                ->update([
                    'amount' => $totalAmount, 
                    'outamount' => $totalAmount, 
                ]);

            echo $totalAmount;

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }
    }

    public function edit(Request $request){

        DB::beginTransaction();

        try {

            ///1. update detail
            DB::table('material.delorddt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->where('lineno_','=',$request->lineno_)
                ->update([
                    'pricecode' => $request->pricecode, 
                    'itemcode'=> $request->itemcode, 
                    'uomcode'=> $request->uomcode, 
                    'pouom'=> $request->pouom,
                    'qtydelivered'=> $request->qtydelivered, 
                    'qtyreturned'=> $request->qtyreturned,
                    'unitprice'=> $request->unitprice,
                    'taxcode'=> $request->taxcode, 
                    'perdisc'=> $request->perdisc, 
                    'amtdisc'=> $request->amtdisc, 
                    'amtslstax'=> $request->tot_gst, 
                    'netunitprice'=> $request->netunitprice, 
                    'amount'=> $request->amount, 
                    'totamount'=> $request->totamount, 
                    'upduser'=> session('username'), 
                    'upddate'=> Carbon::now("Asia/Kuala_Lumpur"), 
                    'expdate'=> $this->chgDate($request->expdate),  
                    'batchno'=> $request->batchno, 
                    'remarks'=> $request->remarks
                ]);

            ///2. recalculate total amount
            $totalAmount = DB::table('material.delorddt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->where('recstatus','!=','DELETE')
                ->sum('totamount');

            //calculate tot gst from detail
            $tot_gst = DB::table('material.delorddt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->where('recstatus','!=','DELETE')
                ->sum('amtslstax');

            ///3. update total amount to header
            DB::table('material.delordhd')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->update([
                    'totamount' => $totalAmount, 
                    'subamount'=> $totalAmount, 
                    'TaxAmt' => $tot_gst
                ]);
            
            echo $totalAmount;

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }
    }

    public function edit_all(Request $request){

        DB::beginTransaction();

        try {

            $delordhd = DB::table('material.delordhd')
                        ->where('compcode',session('compcode'))
                        ->where('recno','=',$request->recno);

            if(!$delordhd->exists()){
                throw new \Exception("No Delivery Order Header idno:".$request->idno);
            }

            foreach ($request->dataobj as $key => $value) {
                ///1. update detail
                DB::table('material.delorddt')
                    ->where('compcode','=',session('compcode'))
                    ->where('recno','=',$request->recno)
                    ->where('lineno_','=',$value['lineno_'])
                    ->update([
                        // 'pricecode' => $value['pricecode'], 
                        // 'itemcode'=> $value['itemcode'], 
                        // 'uomcode'=> $value['uomcode'], 
                        // 'pouom'=> $value['pouom'],
                        // 'qtydelivered'=> $value['qtydelivered'], 
                        'qtyreturned'=>  $value['qtyreturned'],
                        'unitprice'=>  $value['unitprice'],
                        'taxcode'=>  $value['taxcode'], 
                        'perdisc'=>  $value['perdisc'], 
                        'amtdisc'=>  $value['amtdisc'], 
                        'amtslstax'=>  $value['tot_gst'], 
                        'netunitprice'=>  $value['netunitprice'], 
                        'amount'=>  $value['amount'], 
                        'totamount'=>  $value['totamount'], 
                        'upduser'=> session('username'), 
                        'upddate'=> Carbon::now("Asia/Kuala_Lumpur"), 
                        // 'expdate'=> $this->chgDate($value['expdate']), 
                        // 'batchno'=>  $value['batchno'], 
                        'remarks'=>  $value['remarks']
                    ]);
            }
            
            ///2. recalculate total amount
            $totalAmount = DB::table('material.delorddt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->where('recstatus','!=','DELETE')
                ->sum('totamount');

            //calculate tot gst from detail
            $tot_gst = DB::table('material.delorddt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$request->recno)
                ->where('recstatus','!=','DELETE')
                ->sum('amtslstax');

            ///3. update total amount to header
            DB::table('material.delordhd')
                ->where('recno','=',$request->recno)
                ->update([
                    'compcode' => session('compcode'),
                    'totamount' => $totalAmount, 
                    'subamount'=> $totalAmount, 
                    'TaxAmt' => $tot_gst
                ]);
            
            echo $totalAmount;

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }
    }

    public function del(Request $request){

        DB::beginTransaction();

        try {

            $delordhd = DB::table('material.delordhd')
                        ->where('compcode',session('compcode'))
                        ->where('idno',$request->doidno)
                        ->first();

            $recno = $delordhd->recno;
            $cnno = substr($delordhd->cnno,3);

            ///1. update detail
            DB::table('material.delorddt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$recno)
                ->where('lineno_','=',$request->lineno_)
                ->update([ 
                    'deluser' => session('username'), 
                    'deldate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'recstatus' => 'DELETE'
                ]);

            DB::table('debtor.dbactdtl')
                ->where('compcode','=',session('compcode'))
                ->where('source','PB')
                ->where('trantype','CN')
                ->where('auditno',$cnno)
                ->where('lineno_','=',$request->lineno_)
                ->update([
                    'deluser' => session('username'), 
                    'deldate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'recstatus' => 'DELETE'
                ]);

            ///2. recalculate total amount
            $totalAmount = DB::table('material.delorddt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$recno)
                ->where('recstatus','!=','DELETE')
                ->sum('totamount');

            //calculate tot gst from detail
            $tot_gst = DB::table('material.delorddt')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$recno)
                ->where('recstatus','!=','DELETE')
                ->sum('amtslstax');

            ///3. update total amount to header
            DB::table('material.delordhd')
                ->where('compcode','=',session('compcode'))
                ->where('recno','=',$recno)
                ->update([
                    'totamount' => $totalAmount, 
                    'subamount'=> $totalAmount, 
                    'TaxAmt' => $tot_gst
                ]);

            DB::table('debtor.dbacthdr')
                ->where('compcode','=',session('compcode'))
                ->where('source','PB')
                ->where('trantype','CN')
                ->where('auditno',$cnno)
                ->update([
                    'amount' => $totalAmount, 
                    'outamount' => $totalAmount, 
                ]);

            echo $totalAmount;

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }
    }

}

