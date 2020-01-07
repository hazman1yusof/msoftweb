<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Auth;
use Carbon\Carbon;
use DateTime;


class DirectPaymentController extends defaultController
{   

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        return view('finance.CM.directPayment.directPayment');
    }

    public function form(Request $request)
    {   
        switch($request->oper){
            case 'add':
                return $this->add($request);
            case 'edit':
                return $this->edit($request);
            case 'del':
                return $this->del($request);
            default:
                return 'error happen..';
        }
    }

    public function add(Request $request){
        DB::beginTransaction();

        try{

            $auditno = $this->defaultSysparam('CM','DP');
            $pvno = $this->defaultSysparam('HIS','PV');
            $amount = 0;

            $idno = DB::table('finance.apacthdr')
                    ->insertGetId([
                        'auditno' => $auditno,
                        'bankcode' => $request->apacthdr_bankcode,
                        'payto' => $request->apacthdr_payto ,
                        'actdate' => $request->apacthdr_actdate ,
                        'amount' => $amount ,
                        'paymode' => $request->apacthdr_paymode ,
                        'cheqno' => $request->apacthdr_cheqno ,
                        'remarks' => $request->apacthdr_remarks ,
                        'TaxClaimable' => $request->apacthdr_TaxClaimable ,
                        'pvno' => $pvno,
                        'cheqdate' => $request->apacthdr_cheqdate ,
                        'source' => $request->apacthdr_source ,
                        'trantype' => $request->apacthdr_trantype ,
                        'compcode' => session('compcode'),
                        'unit' => session('unit'),
                        'adduser' => session('username'),
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'recstatus' => 'A'
                    ]);


            $responce = new stdClass();
            $responce->auditno = $auditno;
            $responce->pvno = $pvno;
            $responce->idno = $idno;
            $responce->amount = 0;

            echo json_encode($responce);

            DB::commit();
        
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage().$e, 500);
        }
    }

    public function edit(Request $request){
        DB::beginTransaction();

        try{

            DB::table('finance.apacthdr')
                    ->where('idno','=',$request->idno)
                    ->update([
                        'bankcode' => $request->apacthdr_bankcode,
                        'payto' => $request->apacthdr_payto ,
                        'actdate' => $request->apacthdr_actdate ,
                        'paymode' => $request->apacthdr_paymode ,
                        'cheqno' => $request->apacthdr_cheqno ,
                        'remarks' => $request->apacthdr_remarks ,
                        'TaxClaimable' => $request->apacthdr_TaxClaimable ,
                        'cheqdate' => $request->apacthdr_cheqdate ,
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                    ]);

            DB::commit();
        
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage().$e, 500);
        }
    }

    public function del(Request $request){
        DB::beginTransaction();

        try{

            DB::table('finance.apacthdr')
                    ->where('idno','=',$request->idno)
                    ->update([
                        'recstatus' => 'D' ,
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                    ]);

            DB::commit();
        
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage().$e, 500);
        }
    }
}
