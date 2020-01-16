<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Auth;
use Carbon\Carbon;
use DateTime;

class BankEnquiryController extends defaultController
{   

    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        return view('finance.CM.bankEnquiry.bankEnquiry');
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
            case 'getdatadr':
                return $this->getdatadr($request);
            default:
                return 'error happen..';
        }
    }

    public function table(Request $request)
    {   
        switch($request->oper){
            case 'getdatadr':
                return $this->getdatadr($request);
            default:
                return 'error happen..';
        }
    }

    public function getdatadr(Request $request){

        $table = DB::table('finance.cbtran')
                    ->select(
                        'source','source','trantype','auditno','postdate','reference','cheqno','amount as amountdr','source')
                    ->where('bankcode','=',$request->bankcode) 
                    ->where('year','=',$request->year) 
                    ->where('period','=',$request->period);


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
}
