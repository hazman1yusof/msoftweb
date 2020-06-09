<?php

namespace App\Http\Controllers\hisdb;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class DietOrderController extends defaultController
{   

    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
        // $this->duplicateCode = "chgtype";
    }

    public function show(Request $request)
    {   
        return view('hisdb.dietorder.dietorder');
    }

    public function form(Request $request)
    {   
        DB::enableQueryLog();
        switch($request->action){
            case 'save_table_dietOrder':

                switch($request->oper){
                    case 'add':
                        return $this->add($request);
                    case 'edit':
                        return $this->edit($request);
                    default:
                        return 'error happen..';
                }

            default:
                return 'error happen..';
        }
    }

    public function add(Request $request){

        DB::beginTransaction();

        try {

            DB::table('nursing.dietorder')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_dietOrder,
                        'episno' => $request->episno_dietOrder,
                        'lodgerflag' => $request->lodgerflag,
                        'lodgervalue' => $request->lodgervalue,
                        'disposable' => $request->disposable,
                        'remark' => $request->remark,
                        'remarkkitchen' => $request->remarkkitchen,
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

    public function edit(Request $request){

        DB::beginTransaction();

        try {

            DB::table('nursing.dietorder')
                ->where('mrn','=',$request->mrn_dietOrder)
                ->where('episno','=',$request->episno_dietOrder)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'lodgerflag' => $request->lodgerflag,
                    'lodgervalue' => $request->lodgervalue,
                    'disposable' => $request->disposable,
                    'remark' => $request->remark,
                    'remarkkitchen' => $request->remarkkitchen,
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                ]);

            $queries = DB::getQueryLog();
            // dump($queries);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response('Error DB rollback!'.$e, 500);
        }
    }

}