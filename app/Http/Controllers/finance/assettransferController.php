<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class assettransferController extends defaultController
{   

    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
        // $this->duplicateCode = "assetregister";
    }

    public function show(Request $request)
    {   
        return view('finance.FA.assettransfer.assettransfer');
    }

    public function form(Request $request)
    {   
        switch($request->oper){
            case 'add':
                return $this->defaultAdd($request);
            case 'edit':
                return $this->edit($request);
            case 'del':
                return $this->defaultDel($request);
            default:
                return 'error happen..';
        }
    }

    public function edit(Request $request){

        DB::beginTransaction();

        try {

            $transferFA = DB::table('finance.faregister')
                ->where('idno','=',$request->idno);

            if($transferFA->exists()){
                $recno = $this->recno('FA','TRF');

                DB::table('finance.fatran')
                    ->insert([
                        'compcode' => session('compcode'),
                        'trantype' => 'TRF',
                        'assetcode' => $request->assetcode,
                        'assettype' => $request->assettype,
                        'assetno' => $request->assetno,
                        'auditno' => $recno,
                        'deptcode' => $request->deptcode,
                        'olddeptcode' => $request->currdeptcode,
                        'curloccode' => $request->loccode,
                        'oldloccode' => $request->currloccode,
                        'trandate' => $request->trandate,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);

                DB::table('finance.faregister')
                    ->where('idno','=',$request->idno)
                    ->update([
                        'currdeptcode' => $request->deptcode,
                        'currloccode' => $request->loccode,
                        'trandate' => $request->trandate,
                        'upduser'  => session('username'),
                        'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
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
}
