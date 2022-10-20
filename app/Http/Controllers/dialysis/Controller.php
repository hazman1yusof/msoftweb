<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function navbar(){//guna table id, tak fixpost
    	$navbar = DB::table('programtab')
    					->where('programmenu','=','patient')
    					->get();
    	return $navbar;
    }

    public function index_of_occurance($val,$array) {
        $occ_idx = [];
        foreach($array as $key => $value){
            if($value == $val){
                array_push($occ_idx, $key);
            }
        }   
        return $occ_idx;
    }

    public function get_maiwp_center_dept(){
        $centers = DB::table('sysdb.department')
                        ->select('deptcode','description')
                        ->where('compcode','=',session('compcode'))
                        ->get();
        return $centers;
    }

}
