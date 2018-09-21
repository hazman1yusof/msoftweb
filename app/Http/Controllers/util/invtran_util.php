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

        if($trantype_obj->isstype == 'Transfer'){

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
                ->first();

            if(count($stockloc_obj)){
                if($stockloc_obj->disptype == 'DS'){
                    $draccno = $category_obj->stockacct;
                    $drccode = $dept_obj->costcode;
                }else if($stockloc_obj->disptype == 'DS1'){
                    $draccno = $category_obj->cosacct;
                    $drccode = $dept_obj->costcode;
                }
            }

        }else{
        	switch($trantype_obj->crdbfl){
        		case 'IN':
			        $draccno = $category_obj->stockacct;
			        $drccode = $dept_obj->costcode;

			        switch ($trantype_obj->accttype) {
			        	case 'Adjustment':
			        		$craccno = $category_obj->stockacct;
	        				$crccode = $dept_obj->costcode;
			        	break;
			        	case 'Expense':
			        		$craccno = $category_obj->expacct;
	        				$crccode = $dept_obj->costcode;
			        	break;
			        	case 'Loan':
			        		$craccno = $category_obj->loanacct;
	        				$crccode = $dept_obj->costcode;
			        	break;
			        	case 'CostOfSale':
			        		$craccno = $category_obj->cosacct;
	        				$crccode = $dept_obj->costcode;
			        	break;
			        	case 'Write Off':
			        		$craccno = $category_obj->woffacct;
	        				$crccode = $dept_obj->costcode;
			        	break;
			        	case 'Others':
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

			        switch ($trantype_obj->accttype) {
			        	case 'Adjustment':
			        		$draccno = $category_obj->stockacct;
	        				$drccode = $dept_obj->costcode;
			        	break;
			        	case 'Expense':
			        		$draccno = $category_obj->expacct;
	        				$drccode = $dept_obj->costcode;
			        	break;
			        	case 'Loan':
			        		$draccno = $category_obj->loanacct;
	        				$drccode = $dept_obj->costcode;
			        	break;
			        	case 'CostOfSale':
			        		$draccno = $category_obj->cosacct;
	        				$drccode = $dept_obj->costcode;
			        	break;
			        	case 'Write Off':
			        		$draccno = $category_obj->woffacct;
	        				$drccode = $dept_obj->costcode;
			        	break;
			        	case 'Others':
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
            ->where('StockLoc.DeptCode','=',$ivtmphd->txndept)
            ->where('StockLoc.ItemCode','=',$value->itemcode)
            ->where('StockLoc.Year','=', defaultController::toYear($ivtmphd->trandate))
            ->where('StockLoc.UomCode','=',$value->uomcode);

        $stockloc_first = $stockloc_obj->first();

        //2.kalu ada stockloc, update 
        if(count($stockloc_first)){

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
                ->where('Year','=',defaultController::toYear($ivtmphd->trandate))
                ->where('DeptCode','=',$ivtmphd->txndept)
                ->where('ItemCode','=',$value->itemcode)
                ->where('UomCode','=',$value->uomcode);

            if($value->expdate == NULL){
                $expdate_obj
                    ->orderBy('expdate', 'asc');
            }else{
                 $expdate_obj
                    ->where('BatchNo','=',$value->batchno)
                    ->where('expdate','<=',$value->expdate)
                    ->orderBy('expdate', 'asc');
            }

            if($expdate_obj->exists()){
                $expdate_get = $expdate_obj->get();
                $txnqty_ = $value->txnqty;
                $balqty = 0;
                foreach ($expdate_get as $value2) {
                    $balqty = $value2->balqty;
                    if($txnqty_-$balqty>0){
                        $txnqty_ = $txnqty_-$balqty;
                        DB::table('material.stockexp')
                            ->where('idno','=',$value2->idno)
                            ->update([
                                'balqty' => '0'
                            ]);
                    }else{
                        $balqty = $balqty-$txnqty_;
                        DB::table('material.stockexp')
                            ->where('idno','=',$value2->idno)
                            ->update([
                                'balqty' => $balqty
                            ]);
                        break;
                    }
                }

            }else{
                throw new \Exception("stockexp xde langsung");
            }

        }else{
            //ni utk kalu xde stockloc
            throw new \Exception("stockloc not exist for item: ".$value->itemcode." | deptcode: ".$ivtmphd->txndept." | year: ".defaultController::toYear($ivtmphd->trandate)." | uomcode: ".$value->uomcode);
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
            ->where('StockLoc.CompCode','=',session('compcode'))
            ->where('StockLoc.DeptCode','=',$ivtmphd->sndrcv)
            ->where('StockLoc.ItemCode','=',$value->itemcode)
            ->where('StockLoc.Year','=', defaultController::toYear($ivtmphd->trandate))
            ->where('StockLoc.UomCode','=',$value->uomcoderecv);

        $stockloc_first = $stockloc_obj->first();

        //4.kalu ada stockloc, update 
        if(count($stockloc_first)){

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

            if(count($expdate_first)){
                $balqty_new = $expdate_first->balqty + $txnqty;

                $expdate_obj->update([

                    'balqty' => $balqty_new
                ]);
            }else{ 
                DB::table('material.stockexp')
                    ->insert([
                    	'compcode' => session('compcode'),
                        'Year' => defaultController::toYear($ivtmphd->trandate),
                        'DeptCode' => $ivtmphd->sndrcv,
                        'ItemCode' => $value->itemcode,
                        'UomCode' => $value->uomcoderecv,
                        'BatchNo' => $value->batchno,
                        'expdate' => $value->expdate,
                        'balqty' => $txnqty
                    ]);
            }

        }else{ 
            //ni utk kalu xde stockloc, buat baru
            
        }

        //-- 6. posting product -> update qtyonhand, avgcost, currprice --//
            //1. waktu OUT trandept

        // $ret_posting_product = invtran_util::posting_product($value,$ivtmphd);

        $product_obj = DB::table('material.product')
            ->where('product.compcode','=',session('compcode'))
            ->where('product.itemcode','=',$value->itemcode)
            ->where('product.uomcode','=',$value->uomcode)
            ->first();

        if(count($product_obj)){ // kalu jumpa
            $month = defaultController::toMonth($ivtmphd->trandate);
            $OldQtyOnHand = $product_obj->qtyonhand;

            $newqtyonhand = $OldQtyOnHand - $value->txnqty;

            // update qtyonhand, avgcost, currprice
            $product_obj = DB::table('material.product')
                ->where('product.compcode','=',session('compcode'))
                ->where('product.itemcode','=',$value->itemcode)
                ->where('product.uomcode','=',$value->uomcode)
                ->update([
                    'qtyonhand' => $newqtyonhand,
                ]);

        }

            //2. waktu IN sndrecv
        $product_obj = DB::table('material.product')
            ->where('product.compcode','=',session('compcode'))
            ->where('product.itemcode','=',$value->itemcode)
            ->where('product.uomcode','=',$value->uomcoderecv)
            ->first();

        if(count($product_obj)){ // kalu jumpa
            $month = defaultController::toMonth($ivtmphd->trandate);
            $OldQtyOnHand = $product_obj->qtyonhand;

            $newqtyonhand = $OldQtyOnHand + $txnqty;

            // update qtyonhand, avgcost, currprice
            $product_obj = DB::table('material.product')
                ->where('product.compcode','=',session('compcode'))
                ->where('product.itemcode','=',$value->itemcode)
                ->where('product.uomcode','=',$value->uomcoderecv)
                ->update([
                    'qtyonhand' => $newqtyonhand,
                ]);
        }

	}

	public static function posting_for_adjustment_in($value,$ivtmphd,$isstype){

 		//1. amik stockloc
        $stockloc_obj = DB::table('material.StockLoc')
            ->where('StockLoc.CompCode','=',session('compcode'))
            ->where('StockLoc.DeptCode','=',$ivtmphd->txndept)
            ->where('StockLoc.ItemCode','=',$value->itemcode)
            ->where('StockLoc.Year','=', defaultController::toYear($ivtmphd->trandate))
            ->where('StockLoc.UomCode','=',$value->uomcode);

        $stockloc_first = $stockloc_obj->first();

        //2.kalu ada stockloc, update 
        if(count($stockloc_first)){

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

            if(count($expdate_first)){
                $balqty_new = $expdate_first->balqty + $value->txnqty;

                $expdate_obj->update([

                    'balqty' => $balqty_new
                ]);
            }else{ 
                DB::table('material.stockexp')
                    ->insert([
                    	'compcode' => session('compcode'),
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
            throw new \Exception("stockloc not exist for item: ".$value->itemcode." | deptcode: ".$ivtmphd->txndept." | year: ".defaultController::toYear($ivtmphd->trandate)." | uomcode: ".$value->uomcode);
        }

        //-- 6. posting product -> update qtyonhand, avgcost, currprice --//
            //1. waktu OUT trandept

        $product_obj = DB::table('material.product')
            ->where('product.compcode','=',session('compcode'))
            ->where('product.itemcode','=',$value->itemcode)
            ->where('product.uomcode','=',$value->uomcode)
            ->first();

        if(count($product_obj)){ // kalu jumpa
            $month = defaultController::toMonth($ivtmphd->trandate);
            $netprice = $value->netprice;
            $txnqty = $value->txnqty;

            $OldQtyOnHand = $product_obj->qtyonhand;
            $currprice = $netprice;
            $Oldavgcost = $product_obj->avgcost;
            $OldAmount = $OldQtyOnHand * $Oldavgcost;
            $NewAmount = $netprice * $txnqty;

            $newqtyonhand = $OldQtyOnHand + $txnqty;
            if($isstype == "Adjustment"){
                $newAvgCost = ($OldAmount + $NewAmount) / ($OldQtyOnHand + $txnqty);
            }else{
                $newAvgCost = $Oldavgcost;
            }


            // update qtyonhand, avgcost, currprice
            $product_obj = DB::table('material.product')
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
            ->where('StockLoc.CompCode','=',session('compcode'))
            ->where('StockLoc.DeptCode','=',$ivtmphd->txndept)
            ->where('StockLoc.ItemCode','=',$value->itemcode)
            ->where('StockLoc.Year','=', defaultController::toYear($ivtmphd->trandate))
            ->where('StockLoc.UomCode','=',$value->uomcode);

        $stockloc_first = $stockloc_obj->first();

        //2.kalu ada stockloc, update 
        if(count($stockloc_first)){

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
                ->where('Year','=',defaultController::toYear($ivtmphd->trandate))
                ->where('DeptCode','=',$ivtmphd->txndept)
                ->where('ItemCode','=',$value->itemcode)
                ->where('UomCode','=',$value->uomcode);

            if($value->expdate == NULL){
                $expdate_obj
                    ->orderBy('expdate', 'asc');
            }else{
                 $expdate_obj
                    ->where('BatchNo','=',$value->batchno)
                    ->where('expdate','<=',$value->expdate)
                    ->orderBy('expdate', 'asc');
            }

            if($expdate_obj->exists()){
                $expdate_get = $expdate_obj->get();
                $txnqty_ = $value->txnqty;
                $balqty = 0;
                foreach ($expdate_get as $value2) {
                    $balqty = $value2->balqty;
                    if($txnqty_-$balqty>0){
                        $txnqty_ = $txnqty_-$balqty;
                        DB::table('material.stockexp')
                            ->where('idno','=',$value2->idno)
                            ->update([
                                'balqty' => '0'
                            ]);
                    }else{
                        $balqty = $balqty-$txnqty_;
                        DB::table('material.stockexp')
                            ->where('idno','=',$value2->idno)
                            ->update([
                                'balqty' => $balqty
                            ]);
                        break;
                    }
                }

            }else{
            	//ni akan jadi mungkin sebab dia "out" pakai expdate dgn batchno, tapi expdate dgn batchno tu x ada dlm stockexp
                throw new \Exception("stockexp xde langsung");
            }

        }else{
            //ni utk kalu xde stockloc
            throw new \Exception("stockloc not exist for item: ".$value->itemcode." | deptcode: ".$ivtmphd->txndept." | year: ".defaultController::toYear($ivtmphd->trandate)." | uomcode: ".$value->uomcode);
        }

        //-- 6. posting product -> update qtyonhand, avgcost, currprice --//
            //1. waktu OUT trandept

        $product_obj = DB::table('material.product')
            ->where('product.compcode','=',session('compcode'))
            ->where('product.itemcode','=',$value->itemcode)
            ->where('product.uomcode','=',$value->uomcode)
            ->first();

        if(count($product_obj)){ // kalu jumpa
            $month = defaultController::toMonth($ivtmphd->trandate);
            $netprice = $value->netprice;
            $txnqty = $value->txnqty;

            $OldQtyOnHand = $product_obj->qtyonhand;
            $currprice = $netprice;
            $Oldavgcost = $product_obj->avgcost;
            $OldAmount = $OldQtyOnHand * $Oldavgcost;
            $NewAmount = $netprice * $txnqty;

            $newqtyonhand = $OldQtyOnHand - $txnqty;
            if($isstype == "Adjustment"){
                $newAvgCost = ($OldAmount - $NewAmount) / ($OldQtyOnHand - $txnqty);
            }else{
                $newAvgCost = $Oldavgcost;
            }

            // update qtyonhand, avgcost, currprice
            $product_obj = DB::table('material.product')
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