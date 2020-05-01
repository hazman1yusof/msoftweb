<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use stdClass;
use DB;

class SysparamController extends Controller
{
    //
    public function __construct(){
        // $this->middleware('auth');
    }


    public function sysparam_bed_status(Request $request){
        $sysparam = DB::table('sysdb.sysparam')
			        	->where('compcode','=',session('compcode'))
			        	->where('source','=','BED')
			        	->where('trantype','=','STATUS')
			        	->first();

		$pvalue1 = explode(",",$sysparam->pvalue1);
		$rows = [];

		foreach ($pvalue1 as $key => $value){
			$object = new stdClass();
			switch ($value) {
				case 'OCCUPIED':
					$object->bedcode = '<i class="fa fa-ban fa-2x" aria-hidden="true"></i> '.$value;
					break;
				case 'VACANT':
					$object->bedcode = '<i class="fa fa-bed fa-2x" aria-hidden="true"></i> '.$value;
					break;
				case 'HOUSEKEEPING':
					$object->bedcode = '<i class="fa fa-female fa-2x" aria-hidden="true"></i> '.$value;
					break;
				case 'MAINTENANCE':
					$object->bedcode = '<i class="fa fa-gavel fa-2x" aria-hidden="true"></i> '.$value;
					break;
				case 'ISOLATED':
					$object->bedcode = '<i class="fa fa-bullhorn fa-2x" aria-hidden="true"></i> '.$value;
					break;
				
				default:
					
					$object->bedcode = '<i class="fa fa-bullhorn" aria-hidden="true"></i> '.$value;
					break;
			}
			$object->description = $value;
			$rows[$key] = $object;
		}

        $responce = new stdClass();
        $responce->page = 1;
        $responce->total = 1;
        $responce->records = 4;
        $responce->rows = $rows;

        return json_encode($responce);
    }

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
					$object->colorcode = $value;
					break;
				case 'blue':
					$object->colorcode = $value;
					break;
				case 'yellow':
					$object->colorcode = $value;
					break;
				case 'green':
					$object->colorcode = $value;
					break;
				default:
					$object->colorcode = $value;
					break;
			}
			$object->description = $value;
			$rows[$key] = $object;
		}

        $responce = new stdClass();
        $responce->page = 1;
        $responce->total = 1;
        $responce->records = 4;
        $responce->rows = $rows;

        return json_encode($responce);
    }

}
