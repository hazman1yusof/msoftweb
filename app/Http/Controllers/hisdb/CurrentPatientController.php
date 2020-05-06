<?php

namespace App\Http\Controllers\hisdb;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class CurrentPatientController extends defaultController
{   

    var $table;
    // var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
        // $this->duplicateCode = "Code";
    }

    public function show(Request $request)
    {   
        return view('hisdb.currentPt.currentPt');
    }

    public function table(Request $request)
    {   
        $request->rows = $request->rowCount;

        $sel_epistycode = $request->epistycode;
        $table = DB::table('hisdb.queue')
                        ->where('compcode','=',session('compcode'))
                        ->where('deptcode','=',"ALL");

        if($sel_epistycode == 'OP'){
                $table->whereIn('epistycode', ['OP','OTC']);
        }else{
                $table->whereIn('epistycode', ['IP','DP']);
        }


        if(!empty($request->searchCol)){
            $table = $table->where($request->searchCol[0],'like',$request->searchVal[0]);
        }

        $paginate = $table->paginate($request->rows);


        $responce = new stdClass();
        $responce->page = $paginate->currentPage();
        $responce->total = $paginate->lastPage();
        $responce->records = $paginate->total();
        $responce->rows = $paginate->items();
        return json_encode($responce);
    }    

    public function form(Request $request)
    {   
        switch($request->oper){
            case 'add':
                return $this->defaultAdd($request);
            case 'edit':
                return $this->defaultEdit($request);
            case 'del':
                return $this->defaultDel($request);
            default:
                return 'error happen..';
        }
    }
}
