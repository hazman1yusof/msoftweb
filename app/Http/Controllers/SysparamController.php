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
					$object->bedcode = '<i class="fa fa-bed" aria-hidden="true"></i> '.$value;
					break;
				case 'VACANT':
					$object->bedcode = '<i class="fa fa-ban" aria-hidden="true"></i> '.$value;
					break;
				case 'HOUSEKEEPING':
					$object->bedcode = '<i class="fa fa-female" aria-hidden="true"></i> '.$value;
					break;
				case 'MAINTENANCE':
					$object->bedcode = '<i class="fa fa-gavel" aria-hidden="true"></i> '.$value;
					break;
				case 'ISOLATED':
					$object->bedcode = '<i class="fa fa-bullhorn" aria-hidden="true"></i> '.$value;
					break;
				
				default:
					
					$object->bedcode = '<i class="fa fa-bullhorn" aria-hidden="true"></i> '.$value;
					break;
			}
			$object->description = $value.' BED';
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
