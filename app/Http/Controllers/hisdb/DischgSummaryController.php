<?php

namespace App\Http\Controllers\hisdb;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class DischgSummaryController extends defaultController
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
        return view('hisdb.dischgsummary.dischgsummary');
    }

    public function form(Request $request)
    {   
        DB::enableQueryLog();
        switch($request->action){
            case 'save_table_dischgSummary':

                switch($request->oper){
                    case 'add':
                        return $this->add($request);
                    case 'edit':
                        return $this->edit($request);
                    default:
                        return 'error happen..';
                }

                case 'get_table_dischgSummary':
                    return $this->get_table_dischgSummary($request);

            default:
                return 'error happen..';
        }
    }

    public function add(Request $request){

        DB::beginTransaction();

        try {

            // DB::table('hisdb.episode')
            //         ->insert([
            //             'compcode' => session('compcode'),
            //             'mrn' => $request->mrn_dischgSummary,
            //             'episno' => $request->episno_dischgSummary,
            //             'dischargetime' => $request->dischargetime,
            //             'diagprov' => $request->diagprov,
            //             'diagfinal' => $request->diagfinal,
            //             'procedure' => $request->procedure,
            //             'treatment' => $request->treatment,
            //             'dischargestatus' => $request->dischargestatus,            
            //             'adduser'  => session('username'),
            //             'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
            //             'lastuser'  => session('username'),
            //             'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
            //         ]);
            
            DB::table('hisdb.episode')
                ->where('mrn','=',$request->mrn_dischgSummary)
                ->where('episno','=',$request->episno_dischgSummary)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'dischargetime' => $request->dischargetime,
                    'diagprov' => $request->diagprov,
                    'diagfinal' => $request->diagfinal,
                    'procedure' => $request->procedure,
                    'treatment' => $request->treatment,
                    'dischargestatus' => $request->dischargestatus,
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

            DB::table('hisdb.episode')
                ->where('mrn','=',$request->mrn_dischgSummary)
                ->where('episno','=',$request->episno_dischgSummary)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'dischargetime' => $request->dischargetime,
                    'diagprov' => $request->diagprov,
                    'diagfinal' => $request->diagfinal,
                    'procedure' => $request->procedure,
                    'treatment' => $request->treatment,
                    'dischargestatus' => $request->dischargestatus,
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

    public function get_table_dischgSummary(Request $request){
        
        $dischgSummary_obj = DB::table('hisdb.episode')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$request->mrn)
                    ->where('episno','=',$request->episno);

        $responce = new stdClass();

        if($dischgSummary_obj->exists()){
            $dischgSummary_obj = $dischgSummary_obj->first();
            $responce->dischgSummary = $dischgSummary_obj;
        }

        return json_encode($responce);

    }

}