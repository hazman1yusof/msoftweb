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
            default:
                return 'error happen..';
        }
    }

    public function table(Request $request)
    {   
        switch($request->action){
            case 'getdata':
                return $this->getdata($request);
            default:
                return 'error happen..';
        }
    }

    public function getdata(Request $request){

        // $table = DB::table('finance.cbtran')
        //             ->select(
        //                 'source','source','trantype','auditno','postdate','reference','cheqno','amount as amountdr','source')
        //             ->where('bankcode','=',$request->bankcode) 
        //             ->where('year','=',$request->year) 
        //             ->where('period','=',$request->period);

        //////////paginate/////////
        // $paginate = $table->paginate($request->rows);

        // $responce = new stdClass();
        // $responce->page = $paginate->currentPage();
        // $responce->total = $paginate->lastPage();
        // $responce->records = $paginate->total();
        // $responce->rows = $paginate->items();
        // $responce->sql = $table->toSql();
        // $responce->sql_bind = $table->getBindings();
        
        $responce = new stdClass();

        if(empty($request->bankcode)){
            $responce->data = [];
            return json_encode($responce);
        }


        $table_dr = DB::table('finance.cbtran')
                    ->select(DB::raw("'open' as open"),DB::raw("'' as amountcr"),'cbtran.source','cbtran.trantype','cbtran.auditno','cbtran.postdate','cbtran.reference','cbtran.cheqno','cbtran.amount as amountdr')
                    ->where('cbtran.compcode',session('compcode'))
                    ->where('cbtran.bankcode',$request->bankcode)
                    ->where('cbtran.year',$request->year)
                    ->where('cbtran.period',$request->period)
                    ->where('cbtran.amount','>=',0)
                    ->get();

        $table_cr = DB::table('finance.cbtran')
                    ->select(DB::raw("'open' as open"),DB::raw("'' as amountdr"),'cbtran.source','cbtran.trantype','cbtran.auditno','cbtran.postdate','cbtran.reference','cbtran.cheqno','cbtran.amount as amountcr')
                    ->where('cbtran.compcode',session('compcode'))
                    ->where('cbtran.bankcode',$request->bankcode)
                    ->where('cbtran.year',$request->year)
                    ->where('cbtran.period',$request->period)
                    ->where('cbtran.amount','<',0)
                    ->get();
        
        $table_merge = $table_dr->merge($table_cr);

        $responce->data = $table_merge;
        return json_encode($responce);
    }
}
