<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class assettransfer2Controller extends defaultController
{   

    var $table;
    //var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
        // $this->duplicateCode = "assetcode";
    }

    public function show(Request $request)
    {   
        return view('finance.FA.assettransfer2.assettransfer2Script');
    }

    public function form(Request $request){   
        DB::enableQueryLog();
        switch($request->action){
            case 'save_table_transferFA':

                switch($request->oper){
                    // case 'add':
                    //     return $this->add($request);
                    case 'edit':
                        return $this->edit($request);
                    default:
                        return 'error happen..';
                }

            case 'save_table_transferFA_compnt':
                return $this->transferFA_compnt($request);

            case 'get_table_transferFA':
                return $this->get_table_transferFA($request);

            default:
                return 'error happen..';
        }
    }

    // public function add(Request $request){

    //     DB::beginTransaction();

    //     try {

    //         DB::table('finance.faregister')
    //                 ->insert([
    //                     'compcode' => session('compcode'),
    //                     'idno' => $request->idno,
    //                     'assetcode' => $request->assetcode,
    //                     'assettype' => $request->assettype,
    //                     'assetno' => $request->assetno,
    //                     'description' => $request->description,
    //                     'serialno' => $request->serialno,
    //                     'lotno' => $request->lotno,
    //                     'casisno' => $request->casisno,
    //                     'engineno' => $request->engineno,
    //                     'deptcode' => $request->deptcode,
    //                     'loccode' => $request->loccode,
    //                     'suppcode' => $request->suppcode,
    //                     'purordno' => $request->purordno,
    //                     'delordno' => $request->delordno,
    //                     'delorddate' => $request->delorddate,
    //                     'assetlineno' => $request->assetlineno,
    //                     'itemcode' => $request->itemcode,
    //                     'invno' => $request->invno,
    //                     'invdate' => $request->invdate,
    //                     'purdate' => $request->purdate,
    //                     'purprice' => $request->purprice,
    //                     'origcost' => $request->origcost,
    //                     'insval' => $request->insval,
    //                     'qty' => $request->qty,
    //                     'startdepdate' => $request->startdepdate,
    //                     'currentcost' => $request->currentcost,
    //                     'lstytddep' => $request->lstytddep,
    //                     'regtype' => $request->regtype,
    //                     'nprefid' => $request->nprefid,
    //                     'lstdepdate' => $request->lstdepdate,
    //                     'trandate' => $request->trandate,
    //                     'trantype' => $request->trantype,
    //                     'statdate' => $request->statdate,
    //                     'individualtag' => $request->individualtag,
    //                     'recstatus' => $request->recstatus,
    //                     'cuytddep' => $request->cuytddep,

    //                     'upduser'  => session('username'),
    //                     'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
    //                 ]);
            
    //         DB::commit();

    //     } catch (\Exception $e) {
    //         DB::rollback();

    //         return response('Error DB rollback!'.$e, 500);
    //     }
    // }

    public function edit(Request $request){

        DB::beginTransaction();

        try {

            $transferFA = DB::table('finance.faregister')
                ->where('idno','=',$request->idno);
                // ->where('assetcode','=',$request->assetcode)
                // ->where('mrn','=',$request->mrn_EnquiryDtl2)
                // ->where('episno','=',$request->episno_EnquiryDtl2)
                // ->where('compcode','=',session('compcode'));

            $transferFAtoFatran = DB::table('finance.fatran')
                ->where('compcode','=',session('compcode'))
                ->where('assetcode','=',$request->assetcode)
                ->where('assettype','=',$request->assettype)
                ->where('assetno','=',$request->assetno);
                // ->where('episno','=',$request->episno_EnquiryDtl2)

            if($transferFA->exists()){
                DB::table('finance.faregister')
                    ->where('idno','=',$request->idno)
                    ->where('compcode','=',session('compcode'))
                    ->update([
                        // 'compcode' => session('compcode'),
                        //'individualtag' => $request->individualtag,
                        // 'itemcode' => $request->itemcode,
                        // 'assetcode' => $request->assetcode,
                        // 'assettype' => $request->assettype,
                        //'description' => $request->description,
                        // 'deptcode' => $request->newdeptcode,
                        // 'loccode' => $request->newloccode,
                        'currdeptcode' => $request->newdeptcode,
                        'currloccode' => $request->newloccode,
                        // 'newdeptcode' => $request->newdeptcode,
                        // 'newloccode' => $request->newloccode,
                        //'assetlineno' => $request->assetlineno,
                        //'assetno' => $request->assetno,
                        //'regtype' => $request->regtype,
                        // 'trandate' => $request->trandate,
                        //'recstatus' => $request->recstatus,
                        'upduser'  => session('username'),
                        'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }else{
                // DB::table('finance.faregister')
                //     ->insert([
                //         'compcode' => session('compcode'),
                //         //'individualtag' => $request->individualtag,
                //         'itemcode' => $request->itemcode,
                //         'assetcode' => $request->assetcode,
                //         'assettype' => $request->assettype,
                //         //'description' => $request->description,
                //         'deptcode' => $request->deptcode,
                //         'loccode' => $request->loccode,
                //         'newdeptcode' => $request->newdeptcode,
                //         'newloccode' => $request->newloccode,
                //         //'assetlineno' => $request->assetlineno,
                //         //'assetno' => $request->assetno,
                //         //'regtype' => $request->regtype,
                //         'trandate' => $request->trandate,
                //         //'recstatus' => $request->recstatus,
                        
                //         'upduser'  => session('username'),
                //         'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                //         'adduser'  => session('username'),
                //         'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                //     ]);
            }

            if($transferFAtoFatran->exists()){
                // $count = $transferFAtoFatran->count();

                // $count = intval($count) + 1;

                $recno = $this->recno('FA','TRF');

                //AMIK DARI SYSPARAM FA,TRF 

                DB::table('finance.fatran')
                    ->insert([
                        'compcode' => session('compcode'),
                        'trantype' => 'TRF',
                        'assetcode' => $request->assetcode,
                        'assettype' => $request->assettype,
                        'assetno' => $request->assetno,
                        'auditno' => $recno,
                        'deptcode' => $request->newdeptcode,
                        'olddeptcode' => $request->currdeptcode,
                        'curloccode' => $request->newloccode,
                        'oldloccode' => $request->currloccode,
                        'trandate' => $request->trandate,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);


                // DB::table('finance.fatran')
                //     ->where('idno','=',$request->idno)
                //     ->where('compcode','=',session('compcode'))
                //     ->update([
                //         'compcode' => session('compcode'),
                //         'assetcode' => $request->assetcode,
                //         'assettype' => $request->assettype,
                //         'auditno' => $request->auditno,
                //         'assetno' => $request->assetno,
                //         'assetlineno' => $request->assetlineno,
                //         'deptcode' => $request->deptcode,
                //         'newdeptcode' => $request->newdeptcode,
                //         'loccode' => $request->loccode,
                //         'newloccode' => $request->newloccode,
                //         'trantype' => $request->trantype,
                //         'trandate' => $request->trandate,
                //         'compntdate' => $request->compntdate,
                //         'amount' => $request->origcost,
                //         // 'qty' => $request->qty,
                //         //'currentcost' => $request->currentcost,
                //         //'individualtag' => $request->individualtag,
                //         'recstatus' => $request->recstatus,
                //         'upduser'  => session('username'),
                //         'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    // ]);
            }else{
                // DB::table('finance.fatran')
                //     ->insert([
                //         'compcode' => session('compcode'),
                //         'assetcode' => $request->assetcode,
                //         'assettype' => $request->assettype,
                //         'assetno' => $request->assetno,
                //         'auditno' => 1,
                //         'deptcode' => $request->newdeptcode,
                //         'olddeptcode' => $request->deptcode,
                //         'curloccode' => $request->newloccode,
                //         'oldloccode' => $request->loccode,
                //         'trandate' => $request->trandate,
                //         'adduser'  => session('username'),
                //         'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                //     ]);
            }
            
            $queries = DB::getQueryLog();
            dump($queries);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response('Error DB rollback!'.$e, 500);
        }
    }

    public function get_table_transferFA(Request $request){

        $transferFA_obj = DB::table('finance.faregister')
                    ->where('compcode','=',session('compcode'))
                    ->where('delordno','=',$request->delordno)
                    ->where('assetno','=',$request->assetno);

        // $faregister_obj = DB::table('finance.faregister')
        //             ->where('compcode','=',session('compcode'))
        //             ->where('delordno','=',$request->delordno)
        //             ->where('assetno','=',$request->assetno);

        $responce = new stdClass();

        if($transferFA_obj->exists()){
            $transferFA_obj = $transferFA_obj->first();
            $responce->transferFA = $transferFA_obj;
        }

        // if($faregister_obj->exists()){
        //     $faregister_obj = $faregister_obj->first();
        //     $responce->faregister = $faregister_obj;
        // }
        
        return json_encode($responce);

    }

    ////ASSET SERIAL LIST/////
    public function transferFA_compnt(Request $request){
        DB::beginTransaction();

        try {

            $transferFA = DB::table('finance.faregister')
                ->where('compcode',session('compcode'))
                ->where('idno','=',$request->idno_fr);

            if($transferFA->exists()){
                DB::table('finance.facompnt')
                ->where('compcode',session('compcode'))
                    ->where('idno','=',$request->idno_fc)
                    ->update([
                        'deptcode' => $request->newdeptcode,
                        'loccode' => $request->newloccode,
                        'upduser'  => session('username'),
                        'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }else{
                
            }

            $queries = DB::getQueryLog();
            dump($queries);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response('Error DB rollback!'.$e, 500);
        }
    }

}