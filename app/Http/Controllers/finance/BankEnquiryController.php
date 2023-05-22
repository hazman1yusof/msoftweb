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
                    // ->leftJoin('finance.bank', function($join) use ($request){
                    //     $join = $join->on('finance.glaccno', '=', 'cbtran.dracc')
                    //                     ->where('glmasref.compcode','=',session('compcode'));
                    // })
                    ->where('cbtran.compcode',session('compcode'))
                    ->where('cbtran.bankcode',$request->bankcode)
                    ->where('cbtran.year',$request->year)
                    ->where('cbtran.period',$request->period)
                    ->where('cbtran.amount','>=',0)
                    ->get();

        $table_cr = DB::table('finance.cbtran')
                    ->select(DB::raw("'open' as open"),DB::raw("'' as amountdr"),'cbtran.source','cbtran.trantype','cbtran.auditno','cbtran.postdate','cbtran.reference','cbtran.cheqno','cbtran.amount as amountcr')
                    // ->leftJoin('finance.bank', function($join) use ($request){
                    //     $join = $join->on('finance.glaccno', '=', 'cbtran.dracc')
                    //                     ->where('glmasref.compcode','=',session('compcode'));
                    // })
                    ->where('cbtran.compcode',session('compcode'))
                    ->where('cbtran.bankcode',$request->bankcode)
                    ->where('cbtran.year',$request->year)
                    ->where('cbtran.period',$request->period)
                    ->where('cbtran.amount','<',0)
                    ->get();

        // $table_dr = DB::table('finance.gltran')
        //             ->select(DB::raw("'open' as open"),DB::raw("'' as cramount"),'gltran.source','gltran.trantype','gltran.auditno','gltran.postdate','gltran.description','gltran.reference','gltran.dracc as acccode','gltran.amount as dramount','glmasref.description as acctname')
        //             ->leftJoin('finance.glmasref', function($join) use ($request){
        //                 $join = $join->on('glmasref.glaccno', '=', 'gltran.dracc')
        //                                 ->where('glmasref.compcode','=',session('compcode'));
        //             })
        //             ->where('gltran.compcode',session('compcode'))
        //             ->where('gltran.drcostcode',$request->costcode)
        //             ->where('gltran.dracc',$request->acc)
        //             ->where('gltran.year',$request->year)
        //             ->where('gltran.period',$request->period)
        //             ->get();

        // $table_cr = DB::table('finance.gltran')
        //             ->select(DB::raw("'open' as open"),DB::raw("'' as dramount"),'gltran.source','gltran.trantype','gltran.auditno','gltran.postdate','gltran.description','gltran.reference','gltran.cracc as acccode','gltran.amount as cramount','glmasref.description as acctname')
        //             ->leftJoin('finance.glmasref', function($join) use ($request){
        //                 $join = $join->on('glmasref.glaccno', '=', 'gltran.cracc')
        //                                 ->where('glmasref.compcode','=',session('compcode'));
        //             })
        //             ->where('gltran.compcode',session('compcode'))
        //             ->where('gltran.crcostcode',$request->costcode)
        //             ->where('gltran.cracc',$request->acc)
        //             ->where('gltran.year',$request->year)
        //             ->where('gltran.period',$request->period)
        //             ->get();
        
        $table_merge = $table_dr->merge($table_cr);

        $responce->data = $table_merge;
        return json_encode($responce);
    }
}
