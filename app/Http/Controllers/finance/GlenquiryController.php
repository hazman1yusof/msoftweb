<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use stdClass;
use App\Http\Controllers\defaultController;
use DB;

class GlenquiryController extends defaultController
{   

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        return view('finance.GL.glmasdtl.glmasdtl');
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

    public function table(Request $request)
    {   
        switch($request->action){
            case 'dialogForm_paymentVoucher':
                return $this->dialogForm_paymentVoucher($request);
            default:
                return 'error happen..';
        }
    }

    public function dialogForm_paymentVoucher(Request $request){
         $table = DB::table('finance.apacthdr')
                    ->select(
                        'auditno',
                        'trantype',
                        'doctype',
                        'suppcode',
                        'actdate',
                        'document',
                        'cheqno',
                        'deptcode',
                        'amount',
                        'outamount',
                        'recstatus',
                        'payto',
                        'recdate',
                        'category',
                        'remarks',
                        'adduser',
                        'adddate',
                        'upduser',
                        'upddate',
                        'source',
                        'idno',
                        'unit',
                        'pvno',
                        'paymode',
                        'bankcode'
                    )
                    ->where('compcode',session('compcode'))
                    ->where('source',$request->source)
                    ->where('trantype',$request->trantype)
                    ->where('auditno',$request->auditno);

        $responce = new stdClass();
        $responce->rows = $table->get();
        $responce->sql_query = $this->getQueries($table);

        return json_encode($responce);
    }
}