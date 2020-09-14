<?php

namespace App\Http\Controllers\hisdb;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class OrdcomController extends defaultController
{   

    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
        // $this->duplicateCode = "chgcode";
    }

    public function show(Request $request)
    {   
        return view('hisdb.ordcom.ordcom');
    }

    public function form(Request $request)
    {   
        DB::enableQueryLog();
        switch($request->action){
            case 'save_table_chargetrx':

                switch($request->oper){
                    case 'add':
                        return $this->add($request);
                    case 'edit':
                        return $this->edit($request);
                    default:
                        return 'error happen..';
                }

            case 'get_table_ordcom':
                return $this->get_table_ordcom($request);

            default:
                return 'error happen..';
        }
    }

    public function add(Request $request){

        DB::beginTransaction();

        try {
            
            // DB::table('hisdb.chargetrx')
            //         ->insert([
            //             'compcode' => session('compcode'),
            //             'mrn' => $request->mrn_ordcom,
            //             'episno' => $request->episno_ordcom,
            //             'trxtype' => $request->trxtype,
            //             'docref' => $request->docref,
            //             'trxdate' => $request->trxdate,
            //             'chgcode' => $request->chgcode,
            //             'billcode' => $request->billcode,
            //             'doctorcode' => $request->doctorcode,
            //             'chg_class' => $request->chg_class,
            //             'chgtype' => $request->chgtype,
            //             'trxtime' => $request->trxtime,
            //             'chggroup' => $request->chggroup,
            //             'dracccode' => $request->dracccode,
            //             'cracccode' => $request->cracccode,
                        
            //             //'location' => 'WARD',
            //             'adduser'  => session('username'),
            //             'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
            //             'lastuser'  => session('username'),
            //             'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
            //         ]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response('Error DB rollback!'.$e, 500);
        }
    }

    public function edit(Request $request){

        DB::beginTransaction();

        try {
            
            DB::table('hisdb.chargetrx')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_ordcom,
                        'episno' => $request->episno_ordcom,
                        'trxtype' => $request->trxtype,
                        'docref' => $request->docref,
                        'trxdate' => $request->trxdate,
                        'chgcode' => $request->chgcode,
                        'billcode' => $request->billcode,
                        'doctorcode' => $request->doctorcode,
                        'chg_class' => $request->chg_class,
                        'chgtype' => $request->chgtype,
                        'trxtime' => $request->trxtime,
                        'chggroup' => $request->chggroup,
                        'dracccode' => $request->dracccode,
                        'cracccode' => $request->cracccode,
                        'taxcode' => $request->taxcode,
                      
                        //'location' => 'WARD',
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);

            DB::table('hisdb.chargetrx')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_ordcom,
                        'episno' => $request->episno_ordcom,
                        'trxtype' => $request->trxtype,
                        'docref' => $request->docref,
                        'trxdate' => $request->trxdate,
                        'chgcode' => $request->chgcode,
                        'billcode' => $request->billcode,
                        'doctorcode' => $request->doctorcode,
                        'chg_class' => $request->chg_class,
                        'chgtype' => $request->chgtype,
                        'trxtime' => $request->trxtime,
                        'chggroup' => $request->chggroup,
                        'dracccode' => $request->dracccode,
                        'cracccode' => $request->cracccode,
                        'taxcode' => $request->taxcode,
                        
                        //'location' => 'WARD',
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response('Error DB rollback!'.$e, 500);
        }
    }

    public function del(Request $request){
        DB::beginTransaction();
        try {

            DB::table('hisdb.chargetrx')
                ->where('idno','=',$request->idno)
                ->update([  
                    'recstatus' => 'D',
                    'deluser' => strtoupper(session('username')),
                    'deldate' => Carbon::now("Asia/Kuala_Lumpur")
                ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
        
    }

}