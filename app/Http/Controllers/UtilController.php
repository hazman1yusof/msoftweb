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

    public function get_table_default(Request $request){   
        $table =  DB::table($request->table_name);

        //////////where//////////
        $table = $table->where('compcode','=',session('compcode'));
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
        if(!empty($request->sidx)){
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

    public function getter(Request $request){
        $table = DB::table($request->table_name);
        $table = $table->select($request->field);

        if(!empty($request->filterCol)){
            foreach ($request->filterCol as $key => $value) {
                $table = $table->where($request->filterCol[$key],'=',$request->filterVal[$key]);
            }
        }

        if(!empty($request->sortby)){
            $table = $table->orderBy($request->sortby, $request->sortorder);
        }

        $responce = new stdClass();
        $responce->rows = $table->get();
        $responce->sql = $table->toSql();
        $responce->sql_bind = $table->getBindings();

        return json_encode($responce);
    }
}
