<?php

namespace App\Http\Controllers\hisdb;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;

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

    // public function table(Request $request)
    // {   
    //     $paginate = DB::table('hisdb.queue')
    //                 ->select('epistycode')
    //                 ->where('epistycode','=',$request->filterVal[0]);

    //     if(!empty($request->searchCol)){
    //         $paginate = $paginate->where($request->searchCol[0],'like',$request->searchVal[0]);
    //     }
    //     $responce = new stdClass();
    //     $responce->page = $paginate->currentPage();
    //     $responce->total = $paginate->lastPage();
    //     $responce->records = $paginate->total();
    //     $responce->rows = $paginate->items();
    //     return json_encode($responce);
    // }    

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
