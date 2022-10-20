<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use stdClass;
use DB;
use Carbon\Carbon;

class DischargeController extends Controller
{   

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function table(Request $request)
    {   
       switch($request->action){
            case 'get_table_discharge':
                $bed = DB::table('hisdb.bed as b')
                            ->select('b.bedtype','bt.description','b.bednum','b.room')
                            ->where('b.compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno)
                            ->leftJoin('hisdb.bedtype as bt','bt.bedtype','=','b.bedtype');

                $episode = DB::table('hisdb.episode')
                            ->select('adduser','reg_date','reg_time')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno);

                $data['bed'] = $bed->first();
                $data['episode'] = $episode->first();
                break;

            default:
                $data = 'error happen..';
                break;
        }


        $responce = new stdClass();
        $responce->data = $data;
        return json_encode($responce);
    }

    public function form(Request $request)
    {   
        DB::enableQueryLog();
        switch($request->action){
            case 'discharge_patient':
                return $this->discharge_patient($request);

            default:
                return 'error happen..';
        }
    }

    public function discharge_patient(Request $request){ 
        $pat_mast = DB::table('hisdb.pat_mast')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$request->mrn);

        $episode = DB::table('hisdb.episode')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno);

        $queue = DB::table('hisdb.queue')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno);

        try {

            if($pat_mast->exists() && $episode->exists() && $queue->exists()){
                $episode->update([
                            'episstatus' => 'DISCHARGE',
                            'episactive' => 0,
                            'dischargedate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'dischargeuser' => session('username'),
                            'dischargetime' => Carbon::now("Asia/Kuala_Lumpur"),
                            'dischargedest' => $request->destination
                        ]);
                $pat_mast->update(['PatStatus' => 0]);
                $queue->update(['Billflag' => 1]);
            }else{
                throw new \Exception('patmast, episno or queue doesnt exist', 500);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            
            return response($e->getMessage(), 500);
        }

    }

    public function diagnose(Request $request)
    {   
        dd('diagnose');
    }
}