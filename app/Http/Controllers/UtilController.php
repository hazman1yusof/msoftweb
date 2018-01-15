<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use stdClass;
use DB;
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

    public function getter(Request $request){

        //////////make table/////////////
        if(is_array($request->table_name)){
            $table =  DB::table($request->table_name[0]);
        }else{
            $table =  DB::table($request->table_name);
        }

        ///////////select field////////
        if(!empty($request->field)){
            $table = $table->select($request->field);
        }

        //////////join//////////
        if(!empty($request->join_onCol)){
            foreach ($request->join_onCol as $key => $value) {

                if(empty($request->join_filterCol)){ //ni nak check kalu ada AND lepas JOIN ON

                    $table = $table->join($request->table_name[$key+1], $request->join_onCol[$key], '=', $request->join_onVal[$key]);
                }else{

                    $table = $table->join($request->table_name[$key+1], function($join) use ($request,$key){
                        $join = $join->on($request->join_onCol[$key], '=', $request->join_onVal[$key]);
                        
                        foreach ($request->join_filterCol as $key2 => $value2) {
                            foreach ($value2 as $key3 => $value3) {
                                $pieces = explode(' ', $value3);
                                if($pieces[1] == 'on'){
                                    $join = $join->on($pieces[0],$pieces[2],$request->join_filterVal[$key2][$key3]);
                                }else{
                                    $join = $join->where($pieces[0],$pieces[2],$request->join_filterVal[$key2][$key3]);
                                }
                            }
                        }
                    });
                }
            }
        }

        //////////where//////////
        if(!empty($request->filterCol)){
            foreach ($request->filterCol as $key => $value) {
                $table = $table->where($request->filterCol[$key],'=',$request->filterVal[$key]);
            }
        }

        /////////searching/////////
        if(!empty($request->searchCol)){
            foreach ($request->searchCol as $key => $value) {
                $table = $table->orWhere($request->searchCol[$key],'like',$request->searchVal[$key]);
            }
        }

        //////////ordering/////////
        if(!empty($request->sortby)){
            foreach ($request->sortby as $key => $value) {
                $pieces = explode(" ", $request->sortby[$key]);
                $table = $table->orderBy($pieces[0], $pieces[1]);
            }
        }else if(!empty($request->sidx)){
            $pieces = explode(", ", $request->sidx .' '. $request->sord);
            if(count($pieces)==1){
                $table = $table->orderBy($request->sidx, $request->sord);
            }else{
                for ($i = sizeof($pieces)-1; $i >= 0 ; $i--) {
                    $pieces_inside = explode(" ", $pieces[$i]);
                    $table = $table->orderBy($pieces_inside[0], $pieces_inside[1]);
                }
            }
        }

        return $table;

    }

    public function get_table_default(Request $request){
        $table = $this->getter($request);

        //////////paginate/////////
        $paginate = $table->paginate($request->rows);

        $responce = new stdClass();
        $responce->page = $paginate->currentPage();
        $responce->total = $paginate->lastPage();
        $responce->records = $paginate->total();
        $responce->rows = $paginate->items();
        $responce->sql = $table->toSql();
        $responce->sql_bind = $table->getBindings();

        return json_encode($responce);
    }

    public function get_value_default(Request $request){
        $table = $this->getter($request);

        $responce = new stdClass();
        $responce->rows = $table->get();
        $responce->sql = $table->toSql();
        $responce->sql_bind = $table->getBindings();

        return json_encode($responce);
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
