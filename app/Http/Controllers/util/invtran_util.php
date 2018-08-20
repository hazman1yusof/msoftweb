<?php

namespace App\Http\Controllers\util;

use DB;
use stdClass;

class invtran_util {

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

        if($trantype_obj->accttype == 'STOCK'){

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

	        $draccno = $category_obj->stockacct;
	        $drccode = $dept_obj->costcode;

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

}

?>