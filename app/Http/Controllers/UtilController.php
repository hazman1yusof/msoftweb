<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use stdClass;
use App\sysparam;

class UtilController extends Controller
{
    //
    public function __construct(){
        $this->middleware('auth');
    }

    public function getcompid(){
    	$responce = new stdClass();
		$responce->ipaddress =  $_SERVER['REMOTE_ADDR'];
		$responce->computerid = gethostbyaddr($_SERVER['REMOTE_ADDR']);

		return json_encode($responce);
    }

    public function getpadlen(){

        return sysparam::select('pvalue1')
            ->where('source','=','IV')
            ->where('trantype','=','ZERO')
            ->get();
    }
}
