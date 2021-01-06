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

    public function form(Request $request)
    {   
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
    //                     'lineno_' => $request->lineno_,
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
                ->where('idno','=',$request->idno)
                // ->where('assetcode','=',$request->assetcode)
                // ->where('mrn','=',$request->mrn_EnquiryDtl2)
                // ->where('episno','=',$request->episno_EnquiryDtl2)
                ->where('compcode','=',session('compcode'));

            if($transferFA->exists()){
                DB::table('finance.faregister')
                    ->where('idno','=',$request->idno)
                    ->where('compcode','=',session('compcode'))
                    ->update([
                        'compcode' => session('compcode'),
                        'idno' => $request->idno,
                        'assetcode' => $request->assetcode,
                        'assettype' => $request->assettype,
                        'description' => $request->description,
                        'deptcode' => $request->deptcode,
                        'loccode' => $request->loccode,
                        'currdeptcode' => $request->currdeptcode,
                        'currloccode' => $request->currloccode,
                        'suppcode' => $request->suppcode,
                        'purordno' => $request->purordno,
                        'delordno' => $request->delordno,
                        'delorddate' => $request->delorddate,
                        'lineno_' => $request->lineno_,
                        'itemcode' => $request->itemcode,
                        'assetno' => $request->assetno,
                        'insval' => $request->insval,
                        'qty' => $request->qty,
                        'startdepdate' => $request->startdepdate,
                        'currentcost' => $request->currentcost,
                        'lstytddep' => $request->lstytddep,
                        'regtype' => $request->regtype,
                        'nprefid' => $request->nprefid,
                        'lstdepdate' => $request->lstdepdate,
                        'trandate' => $request->trandate,
                        'trantype' => $request->trantype,
                        'statdate' => $request->statdate,
                        'individualtag' => $request->individualtag,
                        'recstatus' => $request->recstatus,
                        'cuytddep' => $request->cuytddep,

                        'upduser'  => session('username'),
                        'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }else{
                DB::table('finance.faregister')
                    ->insert([
                        'compcode' => session('compcode'),
                        'delordno' => $request->delordno_transferFA,
                        'assetno' => $request->assetno_transferFA,
                        'compcode' => session('compcode'),
                        'idno' => $request->idno,
                        'assetcode' => $request->assetcode,
                        'assettype' => $request->assettype,
                        'description' => $request->description,
                        'deptcode' => $request->deptcode,
                        'loccode' => $request->loccode,
                        'currdeptcode' => $request->currdeptcode,
                        'currloccode' => $request->currloccode,
                        'suppcode' => $request->suppcode,
                        'purordno' => $request->purordno,
                        'delordno' => $request->delordno,
                        'delorddate' => $request->delorddate,
                        'lineno_' => $request->lineno_,
                        'itemcode' => $request->itemcode,
                        'assetno' => $request->assetno,
                        'insval' => $request->insval,
                        'qty' => $request->qty,
                        'startdepdate' => $request->startdepdate,
                        'currentcost' => $request->currentcost,
                        'lstytddep' => $request->lstytddep,
                        'regtype' => $request->regtype,
                        'nprefid' => $request->nprefid,
                        'lstdepdate' => $request->lstdepdate,
                        'trandate' => $request->trandate,
                        'trantype' => $request->trantype,
                        'statdate' => $request->statdate,
                        'individualtag' => $request->individualtag,
                        'recstatus' => $request->recstatus,
                        
                        'upduser'  => session('username'),
                        'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
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

        $faregister_obj = DB::table('finance.faregister')
                    ->where('compcode','=',session('compcode'))
                    ->where('delordno','=',$request->delordno)
                    ->where('assetno','=',$request->assetno);

        $responce = new stdClass();

        if($transferFA_obj->exists()){
            $transferFA_obj = $transferFA_obj->first();
            $responce->transferFA = $transferFA_obj;
        }

        if($faregister_obj->exists()){
            $faregister_obj = $faregister_obj->first();
            $responce->faregister = $faregister_obj;
        }
        
        return json_encode($responce);

    }

}