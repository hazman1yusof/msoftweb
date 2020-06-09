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

            default:
                return 'error happen..';
        }
    }

    public function add(Request $request){

        DB::beginTransaction();

        try {

            DB::table('hisdb.episode')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_dischgSummary,
                        'episno' => $request->episno_dischgSummary,
                        'dischargetime' => $request->dischargetime,
                        'diagprov' => $request->diagprov,
                        'diagfinal' => $request->diagfinal,
                        'procedure' => $request->procedure,
                        'treatment' => $request->treatment,
                        'dischargestatus' => $request->dischargestatus,            
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

}