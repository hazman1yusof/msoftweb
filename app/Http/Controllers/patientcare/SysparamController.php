<?php

namespace App\Http\Controllers\patientcare;

use Illuminate\Http\Request;
use stdClass;
use DB;
use App\Http\Controllers\defaultController;

class SysparamController extends defaultController
{
    //
    public function __construct(){
        // $this->middleware('auth');
    }


    // public function sysparam_bed_status(Request $request){
    //     $sysparam = DB::table('sysdb.sysparam')
	// 		        	->where('compcode','=',session('compcode'))
	// 		        	->where('source','=','BED')
	// 		        	->where('trantype','=','STATUS')
	// 		        	->first();

	// 	$pvalue1 = explode(",",$sysparam->pvalue1);
	// 	$rows = [];

	// 	foreach ($pvalue1 as $key => $value){
	// 		$object = new stdClass();
	// 		switch ($value) {
	// 			case 'OCCUPIED':
	// 				$object->bedcode = '<i class="fa fa-bed fa-2x" aria-hidden="true"></i> '.$value;
	// 				$object->description = $value;
	// 				break;
	// 			case 'VACANT':
	// 				$object->bedcode = '<img src="img/bedonly.png" height="20" width="28"></img> '.$value;
	// 				$object->description = $value;
	// 				break;
	// 			case 'HOUSEKEEPING':
	// 				$object->bedcode = '<i class="fa fa-female fa-2x" aria-hidden="true"></i> '.$value;
	// 				$object->description = $value;
	// 				break;
	// 			case 'MAINTENANCE':
	// 				$object->bedcode = '<i class="fa fa-gavel fa-2x" aria-hidden="true"></i> '.$value;
	// 				$object->description = $value;
	// 				break;
	// 			case 'ISOLATED':
	// 				$object->bedcode = '<i class="fa fa-bullhorn fa-2x" aria-hidden="true"></i> '.$value;
	// 				$object->description = $value;
	// 				break;
	// 			case 'RESERVE':
	// 				$object->bedcode = '<i class="fa fa-ban fa-2x" aria-hidden="true"></i> '.$value;
	// 				$object->description = $value;
	// 				break;	
	// 			case 'ACTIVE':
	// 				$object->bedcode = '<i class="fa fa-check fa-2x" aria-hidden="true"></i> '.$value;
	// 				$object->description = $value;
	// 				break;
	// 			case 'DEACTIVE':
	// 				$object->bedcode = '<i class="fa fa-times fa-2x" aria-hidden="true"></i> '.$value;
	// 				$object->description = $value;
	// 				break;			
	// 			default:
	// 				$object->bedcode = '<i class="fa fa-bullhorn" aria-hidden="true"></i> '.$value;
	// 				$object->description = $value;
	// 				break;
	// 		}
	// 		$object->description = $value;
	// 		$rows[$key] = $object;
	// 	}

    //     $responce = new stdClass();
    //     $responce->page = 1;
    //     $responce->total = 1;
    //     $responce->records = 4;
    //     $responce->rows = $rows;

    //     return json_encode($responce);
	// }
	
    // public function sysparam_stat(Request $request){
    //     $sysparam = DB::table('sysdb.sysparam')
	// 		        	->where('compcode','=',session('compcode'))
	// 		        	->where('source','=','STAT')
	// 		        	->where('trantype','=','YES') //filter where pvalue1 = A and D shj
	// 		        	->first();

	// 	$pvalue1 = explode(",",$sysparam->pvalue1);
	// 	$rows = [];

	// 	foreach ($pvalue1 as $key => $value){
	// 		$object = new stdClass();
	// 		switch ($value) {
	// 			case 'TRUE':
	// 				$object->stat = '<i class="fa fa-check fa-2x" aria-hidden="true"></i> '.$value;
	// 				$object->description = 1;
	// 				break;
	// 			case 'FALSE':
	// 				$object->stat = '<i class="fa fa-times fa-2x" aria-hidden="true"></i> '.$value;
	// 				$object->description = 0;
	// 				break;
	// 			default:				
	// 				$object->stat = '<i class="fa fa-times fa-2x" aria-hidden="true"></i> '.$value;
	// 				$object->description = $value;
	// 				break;
	// 		}
	// 		$rows[$key] = $object;
	// 	}

    //     $responce = new stdClass();
    //     $responce->page = 1;
    //     $responce->total = 1;
    //     $responce->records = 4;
    //     $responce->rows = $rows;

    //     return json_encode($responce);
	// }
	
	// public function sysparam_recstatus(Request $request){
    //     $sysparam = DB::table('sysdb.sysparam')
	// 		        	->where('compcode','=',session('compcode'))
	// 		        	->where('source','=','STAT')
	// 		        	->where('trantype','=','YES')
	// 		        	->first();

	// 	$pvalue1 = explode(",",$sysparam->pvalue1);
	// 	$rows = [];

	// 	foreach ($pvalue1 as $key => $value){
	// 		$object = new stdClass();
	// 		switch ($value) {
	// 			case 'TRUE':
	// 				$object->stat = '<i class="fa fa-check fa-2x" aria-hidden="true"></i> '.$value;
	// 				$object->description = 'A';
	// 				break;
	// 			case 'FALSE':
	// 				$object->stat = '<i class="fa fa-times fa-2x" aria-hidden="true"></i> '.$value;
	// 				$object->description = 'D';
	// 				break;
	// 			default:
	// 				$object->stat = '<i class="fa fa-times fa-2x" aria-hidden="true"></i> '.$value;
	// 				$object->description = $value;
	// 				break;
	// 		}
	// 		$rows[$key] = $object;
	// 	}

    //     $responce = new stdClass();
    //     $responce->page = 1;
    //     $responce->total = 1;
    //     $responce->records = 4;
    //     $responce->rows = $rows;

    //     return json_encode($responce);
	// }

    public function sysparam_triage_color(Request $request){
        $sysparam = DB::table('sysdb.sysparam')
			        	->where('compcode','=',session('compcode'))
			        	->where('source','=','HIS')
			        	->where('trantype','=','DISCSTATUS')
			        	->first();

		$pvalue1 = explode(",",$sysparam->pvalue1);
		$rows = [];

		foreach ($pvalue1 as $key => $value){
			$object = new stdClass();
			switch ($value) {
				case 'red':
					$object->colorcode = '<input type="radio" name="colorcode_select"> '.$value;
					break;
				case 'blue':
					$object->colorcode = '<input type="radio" name="colorcode_select"> '.$value;
					break;
				case 'yellow':
					$object->colorcode = '<input type="radio" name="colorcode_select"> '.$value;
					break;
				case 'green':
					$object->colorcode = '<input type="radio" name="colorcode_select"> '.$value;
					break;
				default:
					$object->colorcode = '<input type="radio" name="colorcode_select"> '.$value;
					break;
			}
			$object->description = trim($value);
			$rows[$key] = $object;
		}

        $responce = new stdClass();
        $responce->page = 1;
        $responce->total = 1;
        $responce->records = 4;
        $responce->rows = $rows;

        return json_encode($responce);
    }

    public function sysparam_triage_color_chk(Request $request){
		$sysparam = DB::table('sysdb.sysparam')
	        	->where('compcode','=',session('compcode'))
	        	->where('source','=','HIS')
	        	->where('trantype','=','DISCSTATUS')
	        	->first();

		$pvalue1 = explode(",",$sysparam->pvalue1);
		$retval = '';
		foreach ($pvalue1 as $key => $value) {
			if(strtoupper(trim($value)) == strtoupper($request->filterVal[2])){
				$retval = trim($value);
				break;
			}
		}

		$object = new stdClass();
		$object->description = $retval;

        $responce = new stdClass();
        $responce->rows[0] = $object;
        return json_encode($responce);
    }

}