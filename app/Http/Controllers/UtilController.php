<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use stdClass;
use DB;
use App\sysparam;

class UtilController extends defaultController
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

    public function input_check(Request $request){
        $table = $this->getter($request);

        if($table->count()>0){
            $msg = "success";
        }else{
            $msg = "fail";
        }

        $responce = new stdClass();
        $responce->value = $table->first()->{$request->field[1]};
        $responce->msg = $msg;
        $responce->row = $table->toSql();
        $responce->sql_bind = $table->getBindings();

        return json_encode($responce);
    }
}
