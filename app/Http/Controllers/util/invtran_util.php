<?php

namespace App\Http\Controllers\util;

use DB;
use DateTime;
use stdClass;
use App\Http\Controllers\defaultController;

class invtran_util extends defaultController{

	public static function get_acc($value,$ivtmphd){

		$trantype_obj = DB::table('material.ivtxntype')
            ->where('ivtxntype.compcode','=',session('compcode'))
            ->where('ivtxntype.trantype','=',$ivtmphd->trantype)
            ->first();

        $productcat_obj = DB::table('material.product')
            ->where('product.compcode','=',session('compcode'))
            ->where('product.itemcode','=',$value->itemcode)
            ->where('product.uomcode','=',$value->uomcode)
            ->first();

        $category_obj = DB::table('material.category')
            ->where('category.compcode','=',session('compcode'))
            ->where('category.catcode','=',$productcat_obj->productcat)
            ->first();

        $dept_obj = DB::table('sysdb.department')
            ->where('department.compcode','=',session('compcode'))
            ->where('department.deptcode','=',$ivtmphd->txndept)
            ->first();

        if(strtoupper($ivtmphd->trantype) == 'TUI'){
            $draccno = $category_obj->stockacct;
            $drccode = $dept_obj->costcode;
            $craccno = $category_obj->expacct;
            $crccode = $dept_obj->costcode;
        }else if(strtoupper($ivtmphd->trantype) == 'TUO'){
            $draccno = $category_obj->adjacct;
            $drccode = $dept_obj->costcode;
            $craccno = $category_obj->stockacct;
            $crccode = $dept_obj->costcode;
        }else if(strtoupper($trantype_obj->isstype) == 'TRANSFER'){

	        $craccno = $category_obj->stockacct;
	        $crccode = $dept_obj->costcode;

	        $productcat_obj = DB::table('material.product')
	            ->where('product.compcode','=',session('compcode'))
	            ->where('product.itemcode','=',$value->itemcode)
	            ->where('product.uomcode','=',$value->uomcoderecv)
	            ->first();

	        $category_obj = DB::table('material.category')
	            ->where('category.compcode','=',session('compcode'))
	            ->where('category.catcode','=',$productcat_obj->productcat)
	            ->first();

	        $dept_obj = DB::table('sysdb.department')
	            ->where('department.compcode','=',session('compcode'))
	            ->where('department.deptcode','=',$ivtmphd->sndrcv)
	            ->first();

            $stockloc_obj = DB::table('material.StockLoc')
                ->where('StockLoc.CompCode','=',session('compcode'))
                ->where('StockLoc.DeptCode','=',$ivtmphd->sndrcv)
                ->where('StockLoc.ItemCode','=',$value->itemcode)
                ->where('StockLoc.Year','=', defaultController::toYear($ivtmphd->trandate))
                ->where('StockLoc.UomCode','=',$value->uomcode);

            if($stockloc_obj->exists()){

                $stockloc_obj = $stockloc_obj->first();

                if($stockloc_obj->disptype == 'DS'){
                    $draccno = $category_obj->stockacct;
                    $drccode = $dept_obj->costcode;
                }else if($stockloc_obj->disptype == 'DS1'){
                    $draccno = $category_obj->cosacct;
                    $drccode = $dept_obj->costcode;
                }else{
                    $draccno = $category_obj->stockacct;
                    $drccode = $dept_obj->costcode;
                }
            }else{
                throw new \Exception("Stockloc not exist for item: ".$value->itemcode." | deptcode: ".$ivtmphd->sndrcv." | year: ".defaultController::toYear($ivtmphd->trandate)." | uomcode: ".$value->uomcode);
            }

        }else{
        	switch(strtoupper($trantype_obj->crdbfl)){
        		case 'IN':
			        $draccno = $category_obj->stockacct;
			        $drccode = $dept_obj->costcode;

			        switch (strtoupper($trantype_obj->accttype)) {
			        	case 'ADJUSTMENT':
			        		$craccno = $category_obj->expacct;
	        				$crccode = $dept_obj->costcode;
			        	break;
			        	case 'EXPENSE':
			        		$craccno = $category_obj->expacct;
	        				$crccode = $dept_obj->costcode;
			        	break;
			        	case 'LOAN':
			        		$craccno = $category_obj->loanacct;
	        				$crccode = $dept_obj->costcode;
			        	break;
			        	case 'COSTOFSALE':
			        		$craccno = $category_obj->cosacct;
	        				$crccode = $dept_obj->costcode;
			        	break;
			        	case 'WRITE-OFF':
			        		$craccno = $category_obj->woffacct;
	        				$crccode = $dept_obj->costcode;
			        	break;
			        	case 'OTHERS':
			        		$craccno = $category_obj->OtherAcct;
	        				$crccode = $dept_obj->costcode;
			        	break;
			        	default:
			        		$craccno = null;
	        				$crccode = null;
			        	break;
			        }
			    break;
			    default:
	        		$craccno = $category_obj->stockacct;
    				$crccode = $dept_obj->costcode;

			        switch (strtoupper($trantype_obj->accttype)) {
			        	case 'ADJUSTMENT':
			        		$draccno = $category_obj->adjacct;
	        				$drccode = $dept_obj->costcode;
			        	break;
			        	case 'EXPENSE':
			        		$draccno = $category_obj->expacct;
	        				$drccode = $dept_obj->costcode;
			        	break;
			        	case 'LOAN':
			        		$draccno = $category_obj->loanacct;
	        				$drccode = $dept_obj->costcode;
			        	break;
			        	case 'COSTOFSALE':
			        		$draccno = $category_obj->cosacct;
	        				$drccode = $dept_obj->costcode;
			        	break;
			        	case 'WRITE-OFF':
			        		$draccno = $category_obj->woffacct;
	        				$drccode = $dept_obj->costcode;
			        	break;
			        	case 'OTHERS':
			        		$draccno = $category_obj->OtherAcct;
	        				$drccode = $dept_obj->costcode;
			        	break;
			        	default:
			        		$draccno = null;
	        				$drccode = null;
			        	break;
			        }
			    break;
        	}
        }

        $responce = new stdClass();
        $responce->craccno = $craccno;
        $responce->crccode = $crccode;
        $responce->draccno = $draccno;
        $responce->drccode = $drccode;

        return $responce;
	}

	public static function posting_for_transfer($value,$ivtmphd){
 		//1. amik stockloc utk 'OUT' //

 		//untuk 'out' keluar macam tu jer xde tengok conversion factor, untuk 'in' dekat bawah tu baru ada tengok sebab dia kena convert dari source conversion factor
        $stockloc_obj = DB::table('material.StockLoc')
            ->where('StockLoc.CompCode','=',session('compcode'))
            ->where('StockLoc.unit',session('unit'))
            ->where('StockLoc.DeptCode','=',$ivtmphd->txndept)
            ->where('StockLoc.ItemCode','=',$value->itemcode)
            ->where('StockLoc.Year','=', defaultController::toYear($ivtmphd->trandate))
            ->where('StockLoc.UomCode','=',$value->uomcode);

        $stockloc_first = $stockloc_obj->first();

        //2.kalu ada stockloc, update 
        if($stockloc_obj->exists()){

            //3. set QtyOnHand, NetMvQty, NetMvVal yang baru dekat StockLoc
            $stockloc_arr = (array)$stockloc_first; // tukar obj jadi array
            $month = defaultController::toMonth($ivtmphd->trandate);
            $QtyOnHand = $stockloc_first->qtyonhand - $value->txnqty; 
            $NetMvQty = $stockloc_arr['netmvqty'.$month] - $value->txnqty;
            $NetMvVal = $stockloc_arr['netmvval'.$month] - ($value->netprice * $value->txnqty);

            $stockloc_obj
                ->update([
                    'QtyOnHand' => $QtyOnHand,
                    'NetMvQty'.$month => $NetMvQty, 
                    'NetMvVal'.$month => $NetMvVal
                ]);

            //4. tolak expdate, kalu ada batchno
            $expdate_obj = DB::table('material.stockexp')
                ->where('compcode',session('compcode'))
                ->where('unit',session('unit'))
                ->where('Year','=',defaultController::toYear($ivtmphd->trandate))
                ->where('DeptCode','=',$ivtmphd->txndept)
                ->where('ItemCode','=',$value->itemcode)
                ->where('UomCode','=',$value->uomcode);

            if($value->expdate == null){
                $got_stockexp = false;

                $expdate_obj
                    ->orderBy('expdate', 'asc');
            }else{
                $got_stockexp = true;

                $expdate_obj
                    ->where('BatchNo','=',$value->batchno)
                    ->where('expdate','<=',$value->expdate)
                    ->orderBy('expdate', 'asc');
            }

            if($expdate_obj->exists()){
                $expdate_get = $expdate_obj->get();
                $txnqty_ = $value->txnqty;
                $balqty = 0;

                foreach ($expdate_get as $value_balqty) {
                    $balqty = $value_balqty->balqty;
                    if($txnqty_-$balqty>0){
                        $txnqty_ = $txnqty_-$balqty;
                        DB::table('material.stockexp')
                            ->where('idno','=',$value_balqty->idno)
                            ->update([
                                'balqty' => '0'
                            ]);
                    }else{
                        $balqty = $balqty-$txnqty_;
                        DB::table('material.stockexp')
                            ->where('idno','=',$value_balqty->idno)
                            ->update([
                                'balqty' => $balqty
                            ]);
                        break;
                    }
                }

                if($got_stockexp && $txnqty_>0){
                    $expdate_obj = DB::table('material.stockexp')
                        ->where('compcode',session('compcode'))
                        ->where('unit',session('unit'))
                        ->where('Year','=',defaultController::toYear($ivtmphd->trandate))
                        ->where('DeptCode','=',$ivtmphd->txndept)
                        ->where('ItemCode','=',$value->itemcode)
                        ->where('UomCode','=',$value->uomcode)
                        ->orderBy('expdate', 'asc');

                    $expdate_get = $expdate_obj->get();
                    $txnqty_ = $txnqty_;
                    $balqty = 0;

                    foreach ($expdate_get as $value_balqty) {
                        $balqty = $value_balqty->balqty;
                        if($txnqty_-$balqty>0){
                            $txnqty_ = $txnqty_-$balqty;
                            DB::table('material.stockexp')
                                ->where('idno','=',$value_balqty->idno)
                                ->update([
                                    'balqty' => '0'
                                ]);
                        }else{
                            $balqty = $balqty-$txnqty_;
                            DB::table('material.stockexp')
                                ->where('idno','=',$value_balqty->idno)
                                ->update([
                                    'balqty' => $balqty
                                ]);
                            break;
                        }
                    }
                }

            }else{
                throw new \Exception("No stockexp");
            }

        }else{
            //ni utk kalu xde stockloc
            throw new \Exception("Stockloc not exist for item: ".$value->itemcode." | deptcode: ".$ivtmphd->txndept." | year: ".defaultController::toYear($ivtmphd->trandate)." | uomcode: ".$value->uomcode);
        }

    	// amik stockloc untuk 'IN' //
        //1. amik convfactor
        $convfactor_obj = DB::table('material.uom')
            ->select('convfactor')
            ->where('uomcode','=',$value->uomcoderecv)
            ->where('compcode','=',session('compcode'))
            ->first();
        $convfactor_uomcoderecv = $convfactor_obj->convfactor;

        $convfactor_obj = DB::table('material.uom')
            ->select('convfactor')
            ->where('uomcode','=',$value->uomcode)
            ->where('compcode','=',session('compcode'))
            ->first();
        $convfactor_uomcodetrdept = $convfactor_obj->convfactor;

        //2. tukar txnqty dgn netprice berdasarkan convfactor
        $txnqty = $value->txnqty * ($convfactor_uomcodetrdept / $convfactor_uomcoderecv);
        $netprice = $value->netprice * ($convfactor_uomcoderecv / $convfactor_uomcodetrdept);

        //3. amik stockloc
        $stockloc_obj = DB::table('material.StockLoc')
            ->where('StockLoc.unit',session('unit'))
            ->where('StockLoc.CompCode','=',session('compcode'))
            ->where('StockLoc.DeptCode','=',$ivtmphd->sndrcv)
            ->where('StockLoc.ItemCode','=',$value->itemcode)
            ->where('StockLoc.Year','=', defaultController::toYear($ivtmphd->trandate))
            ->where('StockLoc.UomCode','=',$value->uomcoderecv);

        $stockloc_first = $stockloc_obj->first();

        //4.kalu ada stockloc, update 
        if($stockloc_obj->exists()){
            if($stockloc_first->stocktxntype != 'IS'){
                //5. set QtyOnHand, NetMvQty, NetMvVal yang baru dekat StockLoc
                $stockloc_arr = (array)$stockloc_first; // tukar obj jadi array
                $month = defaultController::toMonth($ivtmphd->trandate);
                $QtyOnHand = $stockloc_first->qtyonhand + $txnqty; 
                $NetMvQty = $stockloc_arr['netmvqty'.$month] + $txnqty;
                $NetMvVal = $stockloc_arr['netmvval'.$month] + ($netprice * $txnqty);

                $stockloc_obj
                    ->update([
                        'QtyOnHand' => $QtyOnHand,
                        'NetMvQty'.$month => $NetMvQty, 
                        'NetMvVal'.$month => $NetMvVal
                    ]);

                //6. tambah stockexp berdasarkan expdate dgn batchno

                $expdate_obj = DB::table('material.stockexp')
                    ->where('compcode',session('compcode'))
                    ->where('unit',session('unit'))
                    ->where('Year','=',defaultController::toYear($ivtmphd->trandate))
                    ->where('DeptCode','=',$ivtmphd->sndrcv)
                    ->where('ItemCode','=',$value->itemcode)
                    ->where('UomCode','=',$value->uomcoderecv)
                    ->where('BatchNo','=',$value->batchno);

                if($value->expdate == NULL){ //ni kalu expdate dia xde @ NULL
                    $expdate_obj
                        ->where('expdate','=',$value->expdate)
                        ->orderBy('expdate', 'asc');
                }else{ // ni kalu expdate dia exist
                     $expdate_obj
                        ->where('expdate','<=',$value->expdate)
                        ->orderBy('expdate', 'asc');
                }

                $expdate_first = $expdate_obj->first();

                if($expdate_obj->exists()){
                    $balqty_new = $expdate_first->balqty + $txnqty;

                    $expdate_obj->update([
                        'balqty' => $balqty_new
                    ]);
                }else{ 
                    DB::table('material.stockexp')
                        ->insert([
                            'compcode' => session('compcode'),
                            'unit' => session('unit'),
                            'Year' => defaultController::toYear($ivtmphd->trandate),
                            'DeptCode' => $ivtmphd->sndrcv,
                            'ItemCode' => $value->itemcode,
                            'UomCode' => $value->uomcoderecv,
                            'BatchNo' => $value->batchno,
                            'expdate' => $value->expdate,
                            'balqty' => $txnqty
                        ]);
                }
            }

        }else{ 
            //ni utk kalu xde stockloc, buat baru
            
        }

        //-- 6. posting product -> update qtyonhand, avgcost, currprice --//
        //1. waktu OUT trandept

        // $ret_posting_product = invtran_util::posting_product($value,$ivtmphd);

        $product_obj = DB::table('material.product')
            ->where('product.unit',session('unit'))
            ->where('product.compcode','=',session('compcode'))
            ->where('product.itemcode','=',$value->itemcode)
            ->where('product.uomcode','=',$value->uomcode);

        if($product_obj->exists()){ // kalu jumpa

            $product_obj = $product_obj->first();

            $month = defaultController::toMonth($ivtmphd->trandate);
            $OldQtyOnHand = $product_obj->qtyonhand;

            $newqtyonhand = $OldQtyOnHand - $value->txnqty;

            // update qtyonhand, avgcost, currprice
            $product_obj = DB::table('material.product')
                ->where('product.unit',session('unit'))
                ->where('product.compcode','=',session('compcode'))
                ->where('product.itemcode','=',$value->itemcode)
                ->where('product.uomcode','=',$value->uomcode)
                ->update([
                    'qtyonhand' => $newqtyonhand
                ]);

        }

        //2. waktu IN sndrecv
        $product_obj = DB::table('material.product')
            ->where('product.unit',session('unit'))
            ->where('product.compcode','=',session('compcode'))
            ->where('product.itemcode','=',$value->itemcode)
            ->where('product.uomcode','=',$value->uomcoderecv);

        if($product_obj->exists()){ // kalu jumpa
            if($stockloc_first->stocktxntype != 'IS'){
                //2. tukar txnqty dgn netprice berdasarkan convfactor
                $txnqty = floatval($value->txnqty) * (floatval($convfactor_uomcodetrdept) / floatval($convfactor_uomcoderecv));
                $netprice = floatval($value->netprice) * (floatval($convfactor_uomcoderecv) / floatval($convfactor_uomcodetrdept));

                $product_obj = $product_obj->first();

                $month = defaultController::toMonth($ivtmphd->trandate);
                $OldQtyOnHand = floatval($product_obj->qtyonhand);
                $currprice = floatval($netprice);
                $Oldavgcost = floatval($product_obj->avgcost);
                $OldAmount = $OldQtyOnHand * $Oldavgcost;
                $NewAmount = $netprice * $txnqty;

                $newqtyonhand = $OldQtyOnHand + $txnqty;
                if($newqtyonhand <= 0){
                    $newAvgCost = 0; //ini kes item baru (qtyonhand 0) dan txnqty kosong
                }else{
                    $newAvgCost = ($OldAmount + $NewAmount) / ($OldQtyOnHand + $txnqty);
                }

                // update qtyonhand, avgcost, currprice
                $product_obj = DB::table('material.product')
                    ->where('product.compcode',session('compcode'))
                    ->where('product.compcode','=',session('compcode'))
                    ->where('product.itemcode','=',$value->itemcode)
                    ->where('product.uomcode','=',$value->uomcoderecv)
                    ->update([
                        'qtyonhand' => $newqtyonhand,
                        'avgcost' => $newAvgCost,
                        'currprice' => $currprice
                    ]);
            }

        }
	}

	public static function posting_for_adjustment_in($value,$ivtmphd,$isstype){

 		//1. amik stockloc
        $stockloc_obj = DB::table('material.StockLoc')
            ->where('compcode',session('compcode'))
            ->where('unit',session('unit'))
            ->where('StockLoc.CompCode','=',session('compcode'))
            ->where('StockLoc.DeptCode','=',$ivtmphd->txndept)
            ->where('StockLoc.ItemCode','=',$value->itemcode)
            ->where('StockLoc.Year','=', defaultController::toYear($ivtmphd->trandate))
            ->where('StockLoc.UomCode','=',$value->uomcode);

        $stockloc_first = $stockloc_obj->first();

        //2.kalu ada stockloc, update 
        if($stockloc_obj->exists()){

        //3. set QtyOnHand, NetMvQty, NetMvVal yang baru dekat StockLoc
            $stockloc_arr = (array)$stockloc_first; // tukar obj jadi array
            $month = defaultController::toMonth($ivtmphd->trandate);
            $QtyOnHand = $stockloc_first->qtyonhand + $value->txnqty; 
            $NetMvQty = $stockloc_arr['netmvqty'.$month] + $value->txnqty;
            $NetMvVal = $stockloc_arr['netmvval'.$month] + ($value->netprice * $value->txnqty);

            $stockloc_obj
                ->update([
                    'QtyOnHand' => $QtyOnHand,
                    'NetMvQty'.$month => $NetMvQty, 
                    'NetMvVal'.$month => $NetMvVal
                ]);

        //4. tambah expdate, kalu ada batchno
            $expdate_obj = DB::table('material.stockexp')
                ->where('compcode',session('compcode'))
                ->where('unit',session('unit'))
                ->where('Year','=',defaultController::toYear($ivtmphd->trandate))
                ->where('DeptCode','=',$ivtmphd->txndept)
                ->where('ItemCode','=',$value->itemcode)
                ->where('UomCode','=',$value->uomcode)
                ->where('BatchNo','=',$value->batchno);

            if($value->expdate == NULL){ //ni kalu expdate dia xde @ NULL
                $expdate_obj
                    ->where('expdate','=',$value->expdate)
                    ->orderBy('expdate', 'asc');
            }else{ // ni kalu expdate dia exist
                 $expdate_obj
                    ->where('expdate','<=',$value->expdate)
                    ->orderBy('expdate', 'asc');
            }

            $expdate_first = $expdate_obj->first();

            if($expdate_obj->exists()){
                $balqty_new = $expdate_first->balqty + $value->txnqty;

                $expdate_obj->update([

                    'balqty' => $balqty_new
                ]);
            }else{ 
                DB::table('material.stockexp')
                    ->insert([
                    	'compcode' => session('compcode'),
                        'unit' => session('unit'),
                        'Year' => defaultController::toYear($ivtmphd->trandate),
                        'DeptCode' => $ivtmphd->txndept,
                        'ItemCode' => $value->itemcode,
                        'UomCode' => $value->uomcode,
                        'BatchNo' => $value->batchno,
                        'expdate' => $value->expdate,
                        'balqty' => $value->txnqty
                    ]);
            }

        }else{
            //ni utk kalu xde stockloc
            throw new \Exception("Stockloc not exist for item: ".$value->itemcode." | deptcode: ".$ivtmphd->txndept." | year: ".defaultController::toYear($ivtmphd->trandate)." | uomcode: ".$value->uomcode);
        }

        //-- 6. posting product -> update qtyonhand, avgcost, currprice --//
            //1. waktu OUT trandept

        $product_obj = DB::table('material.product')
            ->where('product.unit',session('unit'))
            ->where('product.compcode','=',session('compcode'))
            ->where('product.itemcode','=',$value->itemcode)
            ->where('product.uomcode','=',$value->uomcode);

        if($product_obj->exists()){ // kalu jumpa
            $product_obj = $product_obj->first();

            $month = defaultController::toMonth($ivtmphd->trandate);
            $netprice = $value->netprice;
            $txnqty = $value->txnqty;

            $OldQtyOnHand = $product_obj->qtyonhand;
            $currprice = $netprice;
            $Oldavgcost = $product_obj->avgcost;
            $OldAmount = $OldQtyOnHand * $Oldavgcost;
            $NewAmount = $netprice * $txnqty;

            $newqtyonhand = $OldQtyOnHand + $txnqty;
            $newAvgCost = ($OldAmount + $NewAmount) / ($OldQtyOnHand + $txnqty);
            // if(strtoupper($isstype) == "ADJUSTMENT"){
            //     $newAvgCost = ($OldAmount + $NewAmount) / ($OldQtyOnHand + $txnqty);
            // }else{
            //     $newAvgCost = $Oldavgcost;
            // }


            // update qtyonhand, avgcost, currprice
            $product_obj = DB::table('material.product')
                ->where('product.unit',session('unit'))
                ->where('product.compcode','=',session('compcode'))
                ->where('product.itemcode','=',$value->itemcode)
                ->where('product.uomcode','=',$value->uomcode)
                ->update([
                    'qtyonhand' => $newqtyonhand,
                    'avgcost' => $newAvgCost,
                    'currprice' => $currprice
                ]);

        }
	}

	public static function posting_for_adjustment_out($value,$ivtmphd,$isstype){
		//1. amik stockloc
        $stockloc_obj = DB::table('material.StockLoc')
            ->where('StockLoc.unit',session('unit'))
            ->where('StockLoc.CompCode','=',session('compcode'))
            ->where('StockLoc.DeptCode','=',$ivtmphd->txndept)
            ->where('StockLoc.ItemCode','=',$value->itemcode)
            ->where('StockLoc.Year','=', defaultController::toYear($ivtmphd->trandate))
            ->where('StockLoc.UomCode','=',$value->uomcode);

        $stockloc_first = $stockloc_obj->first();

        //2.kalu ada stockloc, update 
        if($stockloc_obj->exists()){

            //3. set QtyOnHand, NetMvQty, NetMvVal yang baru dekat StockLoc
            $stockloc_arr = (array)$stockloc_first; // tukar obj jadi array
            $month = defaultController::toMonth($ivtmphd->trandate);
            $QtyOnHand = $stockloc_first->qtyonhand - $value->txnqty; 
            $NetMvQty = $stockloc_arr['netmvqty'.$month] - $value->txnqty;
            $NetMvVal = $stockloc_arr['netmvval'.$month] - ($value->netprice * $value->txnqty);

            $stockloc_obj
                ->update([
                    'QtyOnHand' => $QtyOnHand,
                    'NetMvQty'.$month => $NetMvQty, 
                    'NetMvVal'.$month => $NetMvVal
                ]);

            //4. tolak expdate, kalu ada batchno
            $expdate_obj = DB::table('material.stockexp')
                ->where('compcode',session('compcode'))
                ->where('unit',session('unit'))
                ->where('Year','=',defaultController::toYear($ivtmphd->trandate))
                ->where('DeptCode','=',$ivtmphd->txndept)
                ->where('ItemCode','=',$value->itemcode)
                ->where('UomCode','=',$value->uomcode);

            if($value->expdate == NULL){
                $expdate_obj
                    ->orderBy('expdate', 'asc');
                $got_stockexp = false;
            }else{
                 $expdate_obj
                    ->where('BatchNo','=',$value->batchno)
                    ->where('expdate','<=',$value->expdate)
                    ->orderBy('expdate', 'asc');
                $got_stockexp = true;
            }

            if($expdate_obj->exists()){
                $expdate_get = $expdate_obj->get();
                $txnqty_ = $value->txnqty;
                $balqty = 0;
                foreach ($expdate_get as $value_balqty) {
                    $balqty = $value_balqty->balqty;
                    if($txnqty_-$balqty>0){
                        $txnqty_ = $txnqty_-$balqty;
                        DB::table('material.stockexp')
                            ->where('idno','=',$value_balqty->idno)
                            ->update([
                                'balqty' => '0'
                            ]);
                    }else{
                        $balqty = $balqty-$txnqty_;
                        DB::table('material.stockexp')
                            ->where('idno','=',$value_balqty->idno)
                            ->update([
                                'balqty' => $balqty
                            ]);
                        break;
                    }
                }

                if($got_stockexp && $txnqty_>0){
                    $expdate_obj = DB::table('material.stockexp')
                        ->where('compcode',session('compcode'))
                        ->where('unit',session('unit'))
                        ->where('Year','=',defaultController::toYear($ivtmphd->trandate))
                        ->where('DeptCode','=',$ivtmphd->txndept)
                        ->where('ItemCode','=',$value->itemcode)
                        ->where('UomCode','=',$value->uomcode)
                        ->orderBy('expdate', 'asc');

                    $expdate_get = $expdate_obj->get();
                    $txnqty_ = $txnqty_;
                    $balqty = 0;

                    foreach ($expdate_get as $value_balqty) {
                        $balqty = $value_balqty->balqty;
                        if($txnqty_-$balqty>0){
                            $txnqty_ = $txnqty_-$balqty;
                            DB::table('material.stockexp')
                                ->where('idno','=',$value_balqty->idno)
                                ->update([
                                    'balqty' => '0'
                                ]);
                        }else{
                            $balqty = $balqty-$txnqty_;
                            DB::table('material.stockexp')
                                ->where('idno','=',$value_balqty->idno)
                                ->update([
                                    'balqty' => $balqty
                                ]);
                            break;
                        }
                    }
                }

            }else{
            	//ni akan jadi mungkin sebab dia "out" pakai expdate dgn batchno, tapi expdate dgn batchno tu x ada dlm stockexp
                throw new \Exception("No stockexp");
            }

        }else{
            //ni utk kalu xde stockloc
            throw new \Exception("Stockloc not exist for item: ".$value->itemcode." | deptcode: ".$ivtmphd->txndept." | year: ".defaultController::toYear($ivtmphd->trandate)." | uomcode: ".$value->uomcode);
        }

        //-- 6. posting product -> update qtyonhand, avgcost, currprice --//
            //1. waktu OUT trandept

        $product_obj = DB::table('material.product')
            ->where('product.unit',session('unit'))
            ->where('product.compcode','=',session('compcode'))
            ->where('product.itemcode','=',$value->itemcode)
            ->where('product.uomcode','=',$value->uomcode);

        if($product_obj->exists()){ // kalu jumpa
            $product_obj = $product_obj->first();
            
            $month = defaultController::toMonth($ivtmphd->trandate);
            $netprice = $value->netprice;
            $txnqty = $value->txnqty;

            $OldQtyOnHand = $product_obj->qtyonhand;
            $currprice = $netprice;
            $Oldavgcost = $product_obj->avgcost;
            $OldAmount = $OldQtyOnHand * $Oldavgcost;
            $NewAmount = $netprice * $txnqty;

            $newqtyonhand = $OldQtyOnHand - $txnqty;
            $newAvgCost = ($OldAmount - $NewAmount) / ($OldQtyOnHand - $txnqty);
            // if(strtoupper($isstype) == "ADJUSTMENT"){
            //     $newAvgCost = ($OldAmount - $NewAmount) / ($OldQtyOnHand - $txnqty);
            // }else{
            //     $newAvgCost = $Oldavgcost;
            // }

            // update qtyonhand, avgcost, currprice
            $product_obj = DB::table('material.product')
                ->where('product.unit',session('unit'))
                ->where('product.compcode','=',session('compcode'))
                ->where('product.itemcode','=',$value->itemcode)
                ->where('product.uomcode','=',$value->uomcode)
                ->update([
                    'qtyonhand' => $newqtyonhand,
                    'avgcost' => $newAvgCost,
                    'currprice' => $currprice
                ]);

        }
	}

    public static function posting_TUO($value,$ivtmphd){
        //1. amik stockloc
        $stockloc_obj = DB::table('material.StockLoc')
            ->where('StockLoc.unit',session('unit'))
            ->where('StockLoc.CompCode','=',session('compcode'))
            ->where('StockLoc.DeptCode','=',$ivtmphd->txndept)
            ->where('StockLoc.ItemCode','=',$value->itemcode)
            ->where('StockLoc.Year','=', defaultController::toYear($ivtmphd->trandate))
            ->where('StockLoc.UomCode','=',$value->uomcode);

        $stockloc_first = $stockloc_obj->first();

        //2.kalu ada stockloc, update 
        if($stockloc_obj->exists()){

            //3. set QtyOnHand, NetMvQty, NetMvVal yang baru dekat StockLoc
            $stockloc_arr = (array)$stockloc_first; // tukar obj jadi array
            $month = defaultController::toMonth($ivtmphd->trandate);
            $QtyOnHand = $stockloc_first->qtyonhand - $value->txnqty; 
            $NetMvQty = $stockloc_arr['netmvqty'.$month] - $value->txnqty;
            $NetMvVal = $stockloc_arr['netmvval'.$month] - ($value->netprice * $value->txnqty);

            $stockloc_obj
                ->update([
                    'QtyOnHand' => $QtyOnHand,
                    'NetMvQty'.$month => $NetMvQty, 
                    'NetMvVal'.$month => $NetMvVal
                ]);

            //4. tolak expdate, kalu ada batchno
            $expdate_obj = DB::table('material.stockexp')
                ->where('compcode',session('compcode'))
                ->where('unit',session('unit'))
                ->where('Year','=',defaultController::toYear($ivtmphd->trandate))
                ->where('DeptCode','=',$ivtmphd->txndept)
                ->where('ItemCode','=',$value->itemcode)
                ->where('UomCode','=',$value->uomcode);

            if($value->expdate == NULL){
                $expdate_obj
                    ->orderBy('expdate', 'asc');
                $got_stockexp = false;
            }else{
                 $expdate_obj
                    ->where('BatchNo','=',$value->batchno)
                    ->where('expdate','<=',$value->expdate)
                    ->orderBy('expdate', 'asc');
                $got_stockexp = true;
            }

            if($expdate_obj->exists()){
                $expdate_get = $expdate_obj->get();
                $txnqty_ = $value->txnqty;
                $balqty = 0;
                foreach ($expdate_get as $value_balqty) {
                    $balqty = $value_balqty->balqty;
                    if($txnqty_-$balqty>0){
                        $txnqty_ = $txnqty_-$balqty;
                        DB::table('material.stockexp')
                            ->where('idno','=',$value_balqty->idno)
                            ->update([
                                'balqty' => '0'
                            ]);
                    }else{
                        $balqty = $balqty-$txnqty_;
                        DB::table('material.stockexp')
                            ->where('idno','=',$value_balqty->idno)
                            ->update([
                                'balqty' => $balqty
                            ]);
                        break;
                    }
                }

                if($got_stockexp && $txnqty_>0){
                    $expdate_obj = DB::table('material.stockexp')
                        ->where('compcode',session('compcode'))
                        ->where('unit',session('unit'))
                        ->where('Year','=',defaultController::toYear($ivtmphd->trandate))
                        ->where('DeptCode','=',$ivtmphd->txndept)
                        ->where('ItemCode','=',$value->itemcode)
                        ->where('UomCode','=',$value->uomcode)
                        ->orderBy('expdate', 'asc');

                    $expdate_get = $expdate_obj->get();
                    $txnqty_ = $txnqty_;
                    $balqty = 0;

                    foreach ($expdate_get as $value_balqty) {
                        $balqty = $value_balqty->balqty;
                        if($txnqty_-$balqty>0){
                            $txnqty_ = $txnqty_-$balqty;
                            DB::table('material.stockexp')
                                ->where('idno','=',$value_balqty->idno)
                                ->update([
                                    'balqty' => '0'
                                ]);
                        }else{
                            $balqty = $balqty-$txnqty_;
                            DB::table('material.stockexp')
                                ->where('idno','=',$value_balqty->idno)
                                ->update([
                                    'balqty' => $balqty
                                ]);
                            break;
                        }
                    }
                }

            }else{
                //ni akan jadi mungkin sebab dia "out" pakai expdate dgn batchno, tapi expdate dgn batchno tu x ada dlm stockexp
                throw new \Exception("No stockexp itemcode: ".$value->itemcode." uomcode: ".$value->uomcode." deptcode: ".$ivtmphd->txndept.' year: '.defaultController::toYear($ivtmphd->trandate).' unit: '.session('unit'));
            }

        }else{
            //ni utk kalu xde stockloc
            throw new \Exception("Stockloc not exist for item: ".$value->itemcode." | deptcode: ".$ivtmphd->txndept." | year: ".defaultController::toYear($ivtmphd->trandate)." | uomcode: ".$value->uomcode);
        }

        //-- 6. posting product -> update qtyonhand, avgcost, currprice --//
            //1. waktu OUT trandept

        $product_obj = DB::table('material.product')
            ->where('product.unit',session('unit'))
            ->where('product.compcode','=',session('compcode'))
            ->where('product.itemcode','=',$value->itemcode)
            ->where('product.uomcode','=',$value->uomcode);

        if($product_obj->exists()){ // kalu jumpa
            $product_obj = $product_obj->first();
            
            $month = defaultController::toMonth($ivtmphd->trandate);
            $netprice = $value->netprice;
            $txnqty = $value->txnqty;

            $OldQtyOnHand = $product_obj->qtyonhand;
            $currprice = $netprice;
            $Oldavgcost = $product_obj->avgcost;
            $OldAmount = $OldQtyOnHand * $Oldavgcost;
            $NewAmount = $netprice * $txnqty;

            $newqtyonhand = $OldQtyOnHand - $txnqty;
            $newAvgCost = ($OldAmount - $NewAmount) / ($OldQtyOnHand - $txnqty);

            if($newqtyonhand < 0){
                throw new \Exception("Product itemcode: ".$value->itemcode." uomcode: ".$value->uomcode." will become -ve value : ".$newqtyonhand);
            }

            // if(strtoupper($isstype) == "ADJUSTMENT"){
            //     $newAvgCost = ($OldAmount - $NewAmount) / ($OldQtyOnHand - $txnqty);
            // }else{
            //     $newAvgCost = $Oldavgcost;
            // }

            // update qtyonhand, avgcost, currprice
            $product_obj = DB::table('material.product')
                ->where('product.unit',session('unit'))
                ->where('product.compcode','=',session('compcode'))
                ->where('product.itemcode','=',$value->itemcode)
                ->where('product.uomcode','=',$value->uomcode)
                ->update([
                    'qtyonhand' => $newqtyonhand,
                    'avgcost' => $newAvgCost,
                    'currprice' => $currprice
                ]);
        }
    }

    public static function posting_TUI($value,$ivtmphd){
        //1. amik stockloc
        $stockloc_obj = DB::table('material.StockLoc')
            ->where('compcode',session('compcode'))
            ->where('unit',session('unit'))
            ->where('StockLoc.CompCode','=',session('compcode'))
            ->where('StockLoc.DeptCode','=',$ivtmphd->txndept)
            ->where('StockLoc.ItemCode','=',$value->itemcode)
            ->where('StockLoc.Year','=', defaultController::toYear($ivtmphd->trandate))
            ->where('StockLoc.UomCode','=',$value->uomcode);

        $stockloc_first = $stockloc_obj->first();

        //2.kalu ada stockloc, update 
        if($stockloc_obj->exists()){

        //3. set QtyOnHand, NetMvQty, NetMvVal yang baru dekat StockLoc
        $stockloc_arr = (array)$stockloc_first; // tukar obj jadi array
        $month = defaultController::toMonth($ivtmphd->trandate);
        $QtyOnHand = $stockloc_first->qtyonhand + $value->txnqty; 
        $NetMvQty = $stockloc_arr['netmvqty'.$month] + $value->txnqty;
        $NetMvVal = $stockloc_arr['netmvval'.$month] + ($value->netprice * $value->txnqty);

        $stockloc_obj
            ->update([
                'QtyOnHand' => $QtyOnHand,
                'NetMvQty'.$month => $NetMvQty, 
                'NetMvVal'.$month => $NetMvVal
                ]);

        //4. tambah expdate, kalu ada batchno
        $expdate_obj = DB::table('material.stockexp')
            ->where('compcode',session('compcode'))
            ->where('unit',session('unit'))
            ->where('Year','=',defaultController::toYear($ivtmphd->trandate))
            ->where('DeptCode','=',$ivtmphd->txndept)
            ->where('ItemCode','=',$value->itemcode)
            ->where('UomCode','=',$value->uomcode)
            ->where('BatchNo','=',$value->batchno);

        if($value->expdate == NULL){ //ni kalu expdate dia xde @ NULL
            $expdate_obj
                ->where('expdate','=',$value->expdate)
                ->orderBy('expdate', 'asc');
        }else{ // ni kalu expdate dia exist
             $expdate_obj
                ->where('expdate','<=',$value->expdate)
                ->orderBy('expdate', 'asc');
        }

        $expdate_first = $expdate_obj->first();

        if($expdate_obj->exists()){
            $balqty_new = $expdate_first->balqty + $value->txnqty;

            $expdate_obj->update([
                'balqty' => $balqty_new
            ]);
        }else{ 
            DB::table('material.stockexp')
                ->insert([
                    'compcode' => session('compcode'),
                    'unit' => session('unit'),
                    'Year' => defaultController::toYear($ivtmphd->trandate),
                    'DeptCode' => $ivtmphd->txndept,
                    'ItemCode' => $value->itemcode,
                    'UomCode' => $value->uomcode,
                    'BatchNo' => $value->batchno,
                    'expdate' => $value->expdate,
                    'balqty' => $value->txnqty
                ]);
        }

        }else{
            //ni utk kalu xde stockloc
            throw new \Exception("Stockloc not exist for item: ".$value->itemcode." | deptcode: ".$ivtmphd->txndept." | year: ".defaultController::toYear($ivtmphd->trandate)." | uomcode: ".$value->uomcode);
        }

        //-- 6. posting product -> update qtyonhand, avgcost, currprice --//
            //1. waktu OUT trandept

        $product_obj = DB::table('material.product')
            ->where('product.unit',session('unit'))
            ->where('product.compcode','=',session('compcode'))
            ->where('product.itemcode','=',$value->itemcode)
            ->where('product.uomcode','=',$value->uomcode);

        if($product_obj->exists()){ // kalu jumpa
            $product_obj = $product_obj->first();

            $month = defaultController::toMonth($ivtmphd->trandate);
            $netprice = $value->netprice;
            $txnqty = $value->txnqty;

            $OldQtyOnHand = $product_obj->qtyonhand;
            $currprice = $netprice;
            $Oldavgcost = $product_obj->avgcost;
            $OldAmount = $OldQtyOnHand * $Oldavgcost;
            $NewAmount = $netprice * $txnqty;

            $newqtyonhand = $OldQtyOnHand + $txnqty;
            $newAvgCost = ($OldAmount + $NewAmount) / ($OldQtyOnHand + $txnqty);
            // if(strtoupper($isstype) == "ADJUSTMENT"){
            //     $newAvgCost = ($OldAmount + $NewAmount) / ($OldQtyOnHand + $txnqty);
            // }else{
            //     $newAvgCost = $Oldavgcost;
            // }

            // update qtyonhand, avgcost, currprice
            $product_obj = DB::table('material.product')
                ->where('product.unit',session('unit'))
                ->where('product.compcode','=',session('compcode'))
                ->where('product.itemcode','=',$value->itemcode)
                ->where('product.uomcode','=',$value->uomcode)
                ->update([
                    'qtyonhand' => $newqtyonhand,
                    'avgcost' => $newAvgCost,
                    'currprice' => $currprice
                ]);

        }
    }


}

?>