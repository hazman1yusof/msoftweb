<?php

namespace App\Http\Controllers\hisdb;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;
use PDF;

class DischargeController extends defaultController
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
        return view('hisdb.discharge.discharge');
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
            case 'save_table_discharge':
        
                switch($request->oper){
                    case 'add':
                        return $this->add($request);
                    case 'edit':
                        return $this->edit($request);
                    default:
                        return 'error happen..';
                }
            
            case 'get_table_discharge':
                return $this->get_table_discharge($request);
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

            // if($pat_mast->exists() && $episode->exists() && $queue->exists()){
            //     $episode->update([
            //                 'episstatus' => 'DISCHARGE',
            //                 'episactive' => 0,
            //                 'dischargedate' => Carbon::now("Asia/Kuala_Lumpur"),
            //                 'dischargeuser' => session('username'),
            //                 'dischargetime' => Carbon::now("Asia/Kuala_Lumpur"),
            //                 'dischargedest' => $request->destination
            //             ]);
            //     $pat_mast->update(['PatStatus' => 0]);
            //     $queue->update(['Billflag' => 1]);
            // }else{
            //     throw new \Exception('patmast episno or queue doesnt exist', 500);
            // }

            if($pat_mast->exists()){
                DB::table('hisdb.pat_mast')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno)
                        ->update(['PatStatus' => 0]);
            }

            if($episode->exists()){
                DB::table('hisdb.episode')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno)
                        ->update([
                            'episstatus' => 'DISCHARGE',
                            'episactive' => 0,
                            'dischargedate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'dischargeuser' => session('username'),
                            'dischargetime' => Carbon::now("Asia/Kuala_Lumpur"),
                            'dischargedest' => $request->destination
                        ]);
            }

            if($queue->exists()){
                DB::table('hisdb.queue')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno)
                        ->update(['Billflag' => 1]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            
            return response($e->getMessage(), 500);
        }

    }

    public function add(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('hisdb.episode')
                ->where('mrn','=',$request->mrn_discharge)
                ->where('episno','=',$request->mrn_discharge)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'reg_date' => $request->reg_date,
                    'reg_by' => $request->reg_by,
                    'reg_time' => $request->reg_time,
                    'dischargedate' => $request->dischargedate,
                    'dischargeuser' => session('username'),
                    'dischargetime' => $request->dischargetime,
                    'diagfinal' => $request->diagfinal,
                    'patologist' => $request->patologist,
                    'clinicalnote' => $request->clinicalnote,
                    'phyexam' => $request->phyexam,
                    'diagprov' => $request->diagprov,
                    'treatment' => $request->treatment,
                    'summary' => $request->summary,
                    'followup' => $request->followup,
                    'status_discWell' => $request->status_discWell,
                    'status_discImproved' => $request->status_discImproved,
                    'status_discAOR' => $request->status_discAOR,
                    'status_discExpired' => $request->status_discExpired,
                    'status_discAbsconded' => $request->status_discAbsconded,
                    'status_discTransferred' => $request->status_discTransferred,
                    'medondischg' => $request->medondischg,
                    'medcert' => $request->medcert,
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
            
            $discharge = DB::table('hisdb.episode')
                        ->where('mrn','=',$request->mrn_discharge)
                        ->where('episno','=',$request->episno_discharge)
                        ->where('compcode','=',session('compcode'));
            
            if($discharge->exists()){
                DB::table('hisdb.episode')
                    ->where('mrn','=',$request->mrn_discharge)
                    ->where('episno','=',$request->episno_discharge)
                    ->where('compcode','=',session('compcode'))
                    ->update([
                        'reg_date' => $request->reg_date,
                        'reg_by' => $request->reg_by,
                        'reg_time' => $request->reg_time,
                        'dischargedate' => $request->dischargedate,
                        'dischargeuser' => session('username'),
                        'dischargetime' => $request->dischargetime,
                        'diagfinal' => $request->diagfinal,
                        'patologist' => $request->patologist,
                        'clinicalnote' => $request->clinicalnote,
                        'phyexam' => $request->phyexam,
                        'diagprov' => $request->diagprov,
                        'treatment' => $request->treatment,
                        'summary' => $request->summary,
                        'followup' => $request->followup,
                        'status_discWell' => $request->status_discWell,
                        'status_discImproved' => $request->status_discImproved,
                        'status_discAOR' => $request->status_discAOR,
                        'status_discExpired' => $request->status_discExpired,
                        'status_discAbsconded' => $request->status_discAbsconded,
                        'status_discTransferred' => $request->status_discTransferred,
                        'medondischg' => $request->medondischg,
                        'medcert' => $request->medcert,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }else{
                DB::table('hisdb.episode')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_discharge,
                        'episno' => $request->episno_discharge,
                        'reg_date' => $request->reg_date,
                        'reg_by' => $request->reg_by,
                        'reg_time' => $request->reg_time,
                        'dischargedate' => $request->dischargedate,
                        'dischargeuser' => session('username'),
                        'dischargetime' => $request->dischargetime,
                        'diagfinal' => $request->diagfinal,
                        'patologist' => $request->patologist,
                        'clinicalnote' => $request->clinicalnote,
                        'phyexam' => $request->phyexam,
                        'diagprov' => $request->diagprov,
                        'treatment' => $request->treatment,
                        'summary' => $request->summary,
                        'followup' => $request->followup,
                        'status_discWell' => $request->status_discWell,
                        'status_discImproved' => $request->status_discImproved,
                        'status_discAOR' => $request->status_discAOR,
                        'status_discExpired' => $request->status_discExpired,
                        'status_discAbsconded' => $request->status_discAbsconded,
                        'status_discTransferred' => $request->status_discTransferred,
                        'medondischg' => $request->medondischg,
                        'medcert' => $request->medcert,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }
            
            $queries = DB::getQueryLog();
            // dump($queries);

            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function get_table_discharge(Request $request){
        
        $episode_obj = DB::table('hisdb.episode as e')
                    ->select('e.reg_date','e.reg_by','e.reg_time','e.dischargedate','e.dischargeuser','e.dischargetime','e.diagfinal','e.patologist','e.clinicalnote','e.phyexam','e.diagprov','e.treatment','e.summary','e.followup','e.status_discWell','e.status_discImproved','e.status_discAOR','e.status_discExpired','e.status_discAbsconded','e.status_discTransferred','e.medondischg','e.medcert','e.adduser as reg_by','e.lastuser','e.lastupdate','e.bedtype','bt.bedtype')
                    ->where('e.compcode','=',session('compcode'))
                    ->where('e.mrn','=',$request->mrn)
                    ->where('e.episno','=',$request->episno)
                    ->leftJoin('hisdb.bedtype as bt','bt.bedtype','=','e.bedtype');
    
        $responce = new stdClass();
        
        if($episode_obj->exists()){
            $episode_obj = $episode_obj->first();
            $responce->episode = $episode_obj;
            $responce->reg_by = $episode_obj;

        }
        
        return json_encode($responce);
        
    }

    public function diagnose(Request $request)
    {   
        dd('diagnose');
    }

    public function showpdf(Request $request){

        $mrn = $request->mrn_discharge;
        $episno = $request->episno_discharge;

        $discharge = DB::table('hisdb.episode as e')
                    ->select('e.mrn','e.episno','e.reg_date','e.reg_by','e.reg_time','e.dischargedate','e.dischargeuser','e.dischargetime','e.diagfinal','e.patologist','e.clinicalnote','e.phyexam','e.diagprov','e.treatment','e.summary','e.followup','e.adduser','e.adddate','pm.Name','e.admdoctor','d.doctorname')
                    ->leftjoin('hisdb.pat_mast as pm', function($join) {
                        $join = $join->on('pm.MRN', '=', 'e.mrn');
                        $join = $join->on('pm.Episno', '=', 'e.episno');
                        $join = $join->where('pm.compcode', '=', session('compcode'));
                    })
                    ->leftjoin('hisdb.doctor as d', function($join) {
                        $join = $join->on('d.doctorcode', '=', 'e.admdoctor');
                        $join = $join->where('d.compcode', '=', session('compcode'));
                    })
                    ->where('e.compcode','=',session('compcode'))
                    ->where('e.mrn','=',$mrn)
                    ->where('e.episno','=',$episno)
                    ->first();
        
        $company = DB::table('sysdb.company')
            ->where('compcode','=',session('compcode'))
            ->first();

        return view('hisdb.discharge.discharge_pdfmake',compact('discharge'));
        
    }
}