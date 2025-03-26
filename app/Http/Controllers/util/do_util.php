<?php

namespace App\Http\Controllers\util;

use DB;
use DateTime;
use stdClass;
use Carbon\Carbon;
use App\Http\Controllers\defaultController;

class do_util extends defaultController{

	public static function ivtxndt_ins($value,$txnqty,$netprice,$delordhd_obj,$productcat){
		DB::table('material.ivtxndt')
            ->insert([
                'compcode' => $value->compcode, 
                'unit' => session('unit'), //ikut unit delorddt
                'recno' => $value->recno, 
                'lineno_' => $value->lineno_, 
                'itemcode' => $value->itemcode, 
                'uomcode' => $value->pouom, 
                'uomcoderecv' => $value->uomcode, 
                'txnqty' => $value->qtydelivered, 
                'netprice' => $value->netunitprice, 
                'adduser' => $value->adduser, 
                'adddate' => $value->adddate, 
                'upduser' => $value->upduser, 
                'upddate' => $value->upddate, 
                'productcat' => $productcat, 
                'draccno' => $value->draccno, 
                'drccode' => $value->drccode, 
                'craccno' => $value->craccno, 
                'crccode' => $value->crccode, 
                'expdate' => $value->expdate, 
                'remarks' => $value->remarks, 
                'qtyonhand' => 0, 
                'batchno' => $value->batchno, 
                'amount' => $value->amount, 
                // 'amount' => round($value->netunitprice * $value->qtydelivered, 2), 
                'trandate' => Carbon::now("Asia/Kuala_Lumpur"), 
                'trantype' => $delordhd_obj->trantype,
                'deptcode' => $value->deldept, 
                'gstamount' => $value->amtslstax, 
                'totamount' => $value->totamount
            ]);
	}

	public static function stockloc_ins($value,$txnqty,$netprice,$deldept_unit,$delordhd_obj){
		//1. amik stockloc
        $stockloc_obj = DB::table('material.StockLoc')
            ->where('StockLoc.CompCode','=',session('compcode'))
            ->where('StockLoc.unit','=',$deldept_unit)
            ->where('StockLoc.DeptCode','=',$value->deldept)
            ->where('StockLoc.ItemCode','=',$value->itemcode)
            ->where('StockLoc.Year','=', defaultController::toYear(Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d')))
            ->where('StockLoc.UomCode','=',$value->uomcode);

        //2.kalu ada stockloc, update 
        if($stockloc_obj->exists()){

        //3. set QtyOnHand, NetMvQty, NetMvVal yang baru dekat StockLoc
            $stockloc_arr = (array)$stockloc_obj->first();
            $month = defaultController::toMonth(Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d'));
            $QtyOnHand = $stockloc_obj->first()->qtyonhand + $txnqty; 
            $NetMvQty = $stockloc_arr['netmvqty'.$month] + $txnqty;
            $NetMvVal = $stockloc_arr['netmvval'.$month] + round($netprice * $txnqty, 2);

            DB::table('material.StockLoc')
                ->where('StockLoc.CompCode','=',session('compcode'))
                ->where('StockLoc.unit','=',$deldept_unit)
                ->where('StockLoc.DeptCode','=',$value->deldept)
                ->where('StockLoc.ItemCode','=',$value->itemcode)
                ->where('StockLoc.Year','=', defaultController::toYear(Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d')))
                ->where('StockLoc.UomCode','=',$value->uomcode)
                ->update([
                    'QtyOnHand' => $QtyOnHand,
                    'NetMvQty'.$month => $NetMvQty, 
                    'NetMvVal'.$month => $NetMvVal
                ]);

        }else{
        //3.kalu xde stockloc, create stockloc baru
            // throw new \Exception("Product doesnt have stockloc: ".$value->itemcode." UOM: ".$value->uomcode." Dept: ".$value->deldept." Year: ".defaultController::toYear($delordhd_obj->trandate));
            DB::table('material.stockloc')
                ->insert([
                    'compcode' => session('compcode'),
                    'unit' => $deldept_unit,
                    'deptcode' => $value->deldept,
                    'itemcode' => $value->itemcode,
                    'uomcode' => $value->uomcode,
                    'year' => defaultController::toYear(Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d')),
                    'stocktxntype' => 'TR',
                    // 'frozen' => $request->frozen,
                    // 'disptype' => $request->disptype,
                    // 'minqty' => $request->minqty,
                    // 'maxqty' => $request->maxqty,
                    // 'reordlevel' => $request->reordlevel,
                    // 'reordqty' => $request->reordqty,
                    'recstatus' => 'ACTIVE',
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'computerid' => session('computerid'),
                ]);
        }
	}

	public static function stockExp_ins($value,$txnqty,$netprice,$deldept_unit,$delordhd_obj){
		//1. amik Stock Expiry
        $stockexp_obj = DB::table('material.stockexp')
            ->where('stockexp.compcode','=',$value->compcode)
            ->where('stockexp.unit','=',$deldept_unit)
            ->where('stockexp.deptcode','=',$value->deldept)
            ->where('stockexp.itemcode','=',$value->itemcode)
            ->where('stockexp.expdate','=',$value->expdate)
            // ->where('stockexp.year','=', defaultController::toYear($delordhd_obj->trandate))
            ->where('stockexp.uomcode','=',$value->uomcode)
            ->where('stockexp.batchno','=',$value->batchno);

        if($stockexp_obj->exists()){
        //2.kalu ada Stock Expiry, update
            $BalQty = $stockexp_obj->first()->balqty + $txnqty;

            DB::table('material.stockexp')
                ->where('stockexp.compcode','=',$value->compcode)
                ->where('stockexp.unit','=',$deldept_unit)
                ->where('stockexp.deptcode','=',$value->deldept)
                ->where('stockexp.itemcode','=',$value->itemcode)
                ->where('stockexp.expdate','=',$value->expdate)
                // ->where('stockexp.year','=', defaultController::toYear($delordhd_obj->trandate))
                ->where('stockexp.uomcode','=',$value->uomcode)
                ->where('stockexp.batchno','=',$value->batchno)
                ->update([
                    'balqty' => $BalQty
                ]);

        }else{
        //3.kalu xde Stock Expiry, buat baru
            $BalQty = $txnqty;

            DB::table('material.stockexp')
                ->insert([
                    'compcode' => $value->compcode, 
                    'unit' => $deldept_unit, 
                    'deptcode' => $value->deldept, 
                    'itemcode' => $value->itemcode, 
                    'uomcode' => $value->uomcode, 
                    'expdate' => $value->expdate, 
                    'batchno' => $value->batchno, 
                    'balqty' => $BalQty, 
                    'adduser' => $value->adduser, 
                    'adddate' => $value->adddate, 
                    'upduser' => $value->upduser, 
                    'upddate' => $value->upddate, 
                   // 'lasttt' => 'GRN', 
                    'year' => defaultController::toYear(Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d'))
                ]);
        }
	}

	public static function product_ins($value,$txnqty,$netprice,$deldept_unit,$delordhd_obj){
		$product_obj = DB::table('material.product')
	        ->where('product.compcode','=',session('compcode'))
            // ->where('product.unit','=',$deldept_unit)
	        ->where('product.itemcode','=',$value->itemcode)
	        ->where('product.uomcode','=',$value->uomcode);

	    if($product_obj->exists()){ // kalu jumpa
	        $month = defaultController::toMonth(Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d'));
	        $OldQtyOnHand = $product_obj->first()->qtyonhand;
	        $currprice = $netprice;
	        $Oldavgcost = $product_obj->first()->avgcost;
	        $OldAmount = $OldQtyOnHand * $Oldavgcost;
	        $NewAmount = $netprice * $txnqty;

	        $newqtyonhand = $OldQtyOnHand + $txnqty;
            if($OldQtyOnHand + $txnqty == 0){
                $newAvgCost = 0; //ini kes item baru (qtyonhand 0) dan txnqty kosong
            }else{
                $newAvgCost = ($OldAmount + $NewAmount) / ($OldQtyOnHand + $txnqty);
            }

            if($value->pricecode == ['BO']){
                $product_upd = [
                    'qtyonhand' => $newqtyonhand ,
                    'avgcost' => $newAvgCost
                ];
            }else{
                $product_upd = [
                    'qtyonhand' => $newqtyonhand ,
                    'avgcost' => $newAvgCost,
                    'currprice' => $currprice
                ];
            }

	        // update qtyonhand, avgcost, currprice
	        DB::table('material.product')
                ->where('product.compcode','=',session('compcode'))
                // ->where('product.unit','=',$deldept_unit)
                ->where('product.itemcode','=',$value->itemcode)
                ->where('product.uomcode','=',$value->uomcode)
	            ->update($product_upd);

	    }else{
            throw new \Exception("Product doesnt exists Item: ".$value->itemcode." UOM: ".$value->uomcode);
        }
	}

	public static function postingGL($value,$delordhd_obj,$productcat,$deldept_unit){

        //amik yearperiod dari delordhd
        $yearperiod = defaultController::getyearperiod_(Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d'));

        //tengok product category
        $product_obj = DB::table('material.product')
            ->where('compcode','=', $value->compcode)
            // ->where('unit','=', $deldept_unit)
            ->where('itemcode','=', $value->itemcode)
            ->first();

        //amik department,category dgn sysparam pvalue1 dgn pvalue2
        //utk debit costcode
        if(strtoupper($product_obj->groupcode) == "STOCK" || strtoupper($product_obj->groupcode) == "OTHERS" || strtoupper($product_obj->groupcode) == "CONSIGNMENT" ){
            $row_dept = DB::table('sysdb.department')
                ->select('costcode')
                ->where('compcode','=',session('compcode'))
                ->where('deptcode','=',$delordhd_obj->deldept)
                ->first();
            //utk debit accountcode
            $row_cat = DB::table('material.category')
                ->select('stockacct')
                ->where('compcode','=',session('compcode'))
                ->where('catcode','=',$productcat)
                ->first();

            $drcostcode = $row_dept->costcode;
            $dracc = $row_cat->stockacct;

            //utk credit costcode dgn accountocde
            $row_sysparam = DB::table('sysdb.sysparam')
                ->select('pvalue1','pvalue2')
                ->where('compcode','=',session('compcode'))
                ->where('source','=','AP')
                ->where('trantype','=','ACC')
                ->first();

        }else if(strtoupper($product_obj->groupcode) == "ASSET"){
            $facode = DB::table('finance.facode')
                ->where('compcode','=', $value->compcode)
                ->where('assetcode','=', $product_obj->productcat)
                ->first();

            $drcostcode = $facode->glassetccode;
            $dracc = $facode->glasset;
            
            //utk credit costcode dgn accountocde
            $row_sysparam = DB::table('sysdb.sysparam')
                ->select('pvalue1','pvalue2')
                ->where('compcode','=',session('compcode'))
                ->where('source','=','AP')
                ->where('trantype','=','ACC')
                ->first();

        }else{
            throw new \Exception("Item at delorddt doesn't have groupcode at table product");
        }

        if(strtoupper($product_obj->groupcode) == "STOCK"){
            $source_ = 'IV';
        }else{
            $source_ = 'DO';
        }

        //1. buat gltran
        DB::table('finance.gltran')
            ->insert([
                'compcode' => $value->compcode,
                'adduser' => $value->adduser,
                'adddate' => $value->adddate,
                'auditno' => $value->recno,
                'lineno_' => $value->lineno_,
                'source' => $source_, //kalau stock 'IV', lain dari stock 'DO'
                'trantype' => $delordhd_obj->trantype,
                'reference' => $delordhd_obj->deldept .' '. str_pad($delordhd_obj->docno,7,"0",STR_PAD_LEFT),
                'description' => $value->itemcode.'</br>'.$product_obj->description, 
                'postdate' => Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d'),
                'year' => $yearperiod->year,
                'period' => $yearperiod->period,
                'drcostcode' => $drcostcode,
                'dracc' => $dracc,
                'crcostcode' => $row_sysparam->pvalue1,
                'cracc' => $row_sysparam->pvalue2,
                'amount' => $value->amount,
                'idno' => $delordhd_obj->deldept .' '. $delordhd_obj->docno
            ]);

        //2. check glmastdtl utk debit, kalu ada update kalu xde create
        $gltranAmount =  defaultController::isGltranExist_($drcostcode,$dracc,$yearperiod->year,$yearperiod->period);

        if($gltranAmount!==false){
            DB::table('finance.glmasdtl')
                ->where('compcode','=',session('compcode'))
                ->where('costcode','=',$drcostcode)
                ->where('glaccount','=',$dracc)
                ->where('year','=',$yearperiod->year)
                ->update([
                    'upduser' => session('username'),
                    'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'actamount'.$yearperiod->period => $value->amount + $gltranAmount,
                    'recstatus' => 'ACTIVE'
                ]);
        }else{
            DB::table('finance.glmasdtl')
                ->insert([
                    'compcode' => session('compcode'),
                    'costcode' => $drcostcode,
                    'glaccount' => $dracc,
                    'year' => $yearperiod->year,
                    'actamount'.$yearperiod->period => $value->amount,
                    'adduser' => session('username'),
                    'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'recstatus' => 'ACTIVE'
                ]);
        }

        //3. check glmastdtl utk credit pulak, kalu ada update kalu xde create
        $gltranAmount = defaultController::isGltranExist_($row_sysparam->pvalue1,$row_sysparam->pvalue2,$yearperiod->year,$yearperiod->period);

        if($gltranAmount!==false){
            DB::table('finance.glmasdtl')
                ->where('compcode','=',session('compcode'))
                ->where('costcode','=',$row_sysparam->pvalue1)
                ->where('glaccount','=',$row_sysparam->pvalue2)
                ->where('year','=',$yearperiod->year)
                ->update([
                    'upduser' => session('username'),
                    'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'actamount'.$yearperiod->period => $gltranAmount - $value->amount,
                    'recstatus' => 'ACTIVE'
                ]);
        }else{
            DB::table('finance.glmasdtl')
                ->insert([
                    'compcode' => session('compcode'),
                    'costcode' => $row_sysparam->pvalue1,
                    'glaccount' => $row_sysparam->pvalue2,
                    'year' => $yearperiod->year,
                    'actamount'.$yearperiod->period => -$value->amount,
                    'adduser' => session('username'),
                    'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'recstatus' => 'ACTIVE'
                ]);
        }
	}

    public static function postingGL_cancel($value,$delordhd_obj,$productcat,$deldept_unit){

        //amik yearperiod dari delordhd
        $yearperiod = defaultController::getyearperiod_($delordhd_obj->postdate);

        //tengok product category
        $product_obj = DB::table('material.product')
            ->where('compcode','=', $value->compcode)
            // ->where('unit','=', $deldept_unit)
            ->where('itemcode','=', $value->itemcode)
            ->first();

        //amik department,category dgn sysparam pvalue1 dgn pvalue2
        //utk debit costcode
        if(strtoupper($product_obj->groupcode) == "STOCK" || strtoupper($product_obj->groupcode) == "OTHERS" || strtoupper($product_obj->groupcode) == "CONSIGNMENT" ){
            $row_dept = DB::table('sysdb.department')
                ->select('costcode')
                ->where('compcode','=',session('compcode'))
                ->where('deptcode','=',$delordhd_obj->deldept)
                ->first();
            //utk debit accountcode
            $row_cat = DB::table('material.category')
                ->select('stockacct')
                ->where('compcode','=',session('compcode'))
                ->where('catcode','=',$productcat)
                ->first();

            $drcostcode = $row_dept->costcode;
            $dracc = $row_cat->stockacct;

            //utk credit costcode dgn accountocde
            $row_sysparam = DB::table('sysdb.sysparam')
                ->select('pvalue1','pvalue2')
                ->where('compcode','=',session('compcode'))
                ->where('source','=','AP')
                ->where('trantype','=','ACC')
                ->first();

        }else if(strtoupper($product_obj->groupcode) == "ASSET"){
            $facode = DB::table('finance.facode')
                ->where('compcode','=', $value->compcode)
                ->where('assetcode','=', $product_obj->productcat)
                ->first();

            $drcostcode = $facode->glassetccode;
            $dracc = $facode->glasset;
            
            //utk credit costcode dgn accountocde
            $row_sysparam = DB::table('sysdb.sysparam')
                ->select('pvalue1','pvalue2')
                ->where('compcode','=',session('compcode'))
                ->where('source','=','AP')
                ->where('trantype','=','ACC')
                ->first();

        }else{
            throw new \Exception("Item at delorddt doesn't have groupcode at table product");
        }

        if(strtoupper($product_obj->groupcode) == "STOCK"){
            $source_ = 'IV';
        }else{
            $source_ = 'DO';
        }

        //1. buat gltran
        DB::table('finance.gltran')
            ->where('compcode',session('compcode'))
            ->where('lineno_',$value->lineno_)
            ->where('source',$source_)
            ->where('auditno',$delordhd_obj->recno)
            ->where('trantype',$delordhd_obj->trantype)
            ->update([
                'compcode' => 'XX'
            ]);

        //2. check glmastdtl utk debit, kalu ada update kalu xde create
        $gltranAmount =  defaultController::isGltranExist_($drcostcode,$dracc,$yearperiod->year,$yearperiod->period);

        if($gltranAmount!==false){
            DB::table('finance.glmasdtl')
                ->where('compcode','=',session('compcode'))
                ->where('costcode','=',$drcostcode)
                ->where('glaccount','=',$dracc)
                ->where('year','=',$yearperiod->year)
                ->update([
                    'upduser' => session('username'),
                    'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'actamount'.$yearperiod->period => $gltranAmount - $value->amount,
                    'recstatus' => 'ACTIVE'
                ]);
        }else{
            DB::table('finance.glmasdtl')
                ->insert([
                    'compcode' => session('compcode'),
                    'costcode' => $drcostcode,
                    'glaccount' => $dracc,
                    'year' => $yearperiod->year,
                    'actamount'.$yearperiod->period => -$value->amount,
                    'adduser' => session('username'),
                    'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'recstatus' => 'ACTIVE'
                ]);
        }

        //3. check glmastdtl utk credit pulak, kalu ada update kalu xde create
        $gltranAmount = defaultController::isGltranExist_($row_sysparam->pvalue1,$row_sysparam->pvalue2,$yearperiod->year,$yearperiod->period);

        if($gltranAmount!==false){
            DB::table('finance.glmasdtl')
                ->where('compcode','=',session('compcode'))
                ->where('costcode','=',$row_sysparam->pvalue1)
                ->where('glaccount','=',$row_sysparam->pvalue2)
                ->where('year','=',$yearperiod->year)
                ->update([
                    'upduser' => session('username'),
                    'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'actamount'.$yearperiod->period => $gltranAmount + $value->amount,
                    'recstatus' => 'ACTIVE'
                ]);
        }else{
            DB::table('finance.glmasdtl')
                ->insert([
                    'compcode' => session('compcode'),
                    'costcode' => $row_sysparam->pvalue1,
                    'glaccount' => $row_sysparam->pvalue2,
                    'year' => $yearperiod->year,
                    'actamount'.$yearperiod->period => $value->amount,
                    'adduser' => session('username'),
                    'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'recstatus' => 'ACTIVE'
                ]);
        }
    }

	public static function postingGL_GST($value,$delordhd_obj){

		if($value->amtslstax > 0){

            //amik yearperiod dari delordhd
            $yearperiod = defaultController::getyearperiod_(Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d'));

            $queryACC = DB::table('sysdb.sysparam')
                ->where('compcode','=',session('compcode'))
                ->where('source','=','AP')
                ->where('trantype','=','ACC')
                ->first();

            //nak pilih debit costcode dgn acc berdasarkan supplier gstid
            $querysupp = DB::table('material.supplier')
                ->where('compcode','=',session('compcode'))
                ->where('suppcode','=',$delordhd_obj->suppcode)
                ->first();

            //kalu xde guna GST-PL, kalu ada guna GST-BS
            if($querysupp->GSTID == ''){
                $queryGSTPL = DB::table('sysdb.sysparam')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','=','GST')
                    ->where('trantype','=','PL')
                    ->first();

                $drcostcode_ = $queryGSTPL->pvalue1;
                $dracc_ = $queryGSTPL->pvalue2;
            }else{
                $queryGSTBS = DB::table('sysdb.sysparam')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','=','GST')
                    ->where('trantype','=','BS')
                    ->first();

                $drcostcode_ = $queryGSTBS->pvalue1;
                $dracc_ = $queryGSTBS->pvalue2;
            }

            //1. buat gltran utk GST
            DB::table('finance.gltran')
                ->insert([
                    'compcode' => $value->compcode,
                    'adduser' => $value->adduser,
                    'adddate' => $value->adddate,
                    'auditno' => $value->recno,
                    'lineno_' => $value->lineno_,
                    'source' => 'IV',
                    'trantype' => 'GST',
                    'reference' => $delordhd_obj->deldept .' '. str_pad($delordhd_obj->docno,7,"0",STR_PAD_LEFT),
                    'description' => $delordhd_obj->suppcode,
                    'postdate' => $delordhd_obj->trandate,
                    'year' => $yearperiod->year,
                    'period' => $yearperiod->period,
                    'drcostcode' => $drcostcode_,
                    'dracc' => $dracc_,
                    'crcostcode' => $queryACC->pvalue1,
                    'cracc' => $queryACC->pvalue2,
                    'amount' => $value->amtslstax,
                    'idno' => $value->itemcode
                ]);

            //2. check glmastdtl utk debit, kalu ada update kalu xde create
             $gltranAmount = defaultController::isGltranExist_($drcostcode_,$dracc_,$yearperiod->year,$yearperiod->period);

            if($gltranAmount!==false){
                DB::table('finance.glmasdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('costcode','=',$drcostcode_)
                    ->where('glaccount','=',$dracc_)
                    ->where('year','=',$yearperiod->year)
                    ->update([
                        'upduser' => session('username'),
                        'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                        'actamount'.$yearperiod->period => $value->amtslstax + $gltranAmount,
                        'recstatus' => 'ACTIVE'
                    ]);
            }else{
                DB::table('finance.glmasdtl')
                    ->insert([
                        'compcode' => session('compcode'),
                        'costcode' => $drcostcode_,
                        'glaccount' => $dracc_,
                        'year' => $yearperiod->year,
                        'actamount'.$yearperiod->period => $value->amtslstax,
                        'adduser' => session('username'),
                        'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                        'recstatus' => 'ACTIVE'
                    ]);
            }

            //3. check glmastdtl utk credit pulak, kalu ada update kalu xde create
            $gltranAmount = defaultController::isGltranExist_($queryACC->pvalue1,$queryACC->pvalue2,$yearperiod->year,$yearperiod->period);

            if($gltranAmount!==false){
                DB::table('finance.glmasdtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('costcode','=',$queryACC->pvalue1)
                    ->where('glaccount','=',$queryACC->pvalue2)
                    ->where('year','=',$yearperiod->year)
                    ->update([
                        'upduser' => session('username'),
                        'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                        'actamount'.$yearperiod->period => $gltranAmount - $value->amtslstax,
                        'recstatus' => 'ACTIVE'
                    ]);
            }else{
                DB::table('finance.glmasdtl')
                    ->insert([
                        'compcode' => session('compcode'),
                        'costcode' => $queryACC->pvalue1,
                        'glaccount' => $queryACC->pvalue2,
                        'year' => $yearperiod->year,
                        'actamount'.$yearperiod->period => -$value->amtslstax,
                        'adduser' => session('username'),
                        'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                        'recstatus' => 'ACTIVE'
                    ]);
            }
        }
	}

    public static function update_po($value,$txnqty,$netprice){
        $delordhd = DB::table('material.delordhd')
            ->where('compcode','=',session('compcode'))
            ->where('recno','=',$value->recno)
            ->first();

        // if($delordhd->srcdocno!=""){
        //     $purorddt = DB::table("material.purorddt")
        //                     ->where('recno','=',$delordhd->srcdocno)
        //                     ->where('lineno_','=',$value->lineno_);

        //     if($purorddt->exists()){
        //         $purorddt->update([
        //             'qtydelivered' => $value->qtydelivered
        //         ]);
        //     }
        // }
    }

}

?>