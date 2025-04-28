<?php

namespace App\Http\Controllers\hisdb;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class MorseFallScaleController extends defaultController
{
    
    var $table;
    var $duplicateCode;
    
    public function __construct(){
        $this->middleware('auth');
        // $this->duplicateCode = "chgtype";
    }
    
    public function show(Request $request){
        return view('hisdb.nursingnote.nursingnote');
    }
    
    public function table(Request $request)
    {
        switch($request->action){
            case 'get_datetime_morsefallscale':
                return $this->get_datetime_morsefallscale($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function form(Request $request){
        DB::enableQueryLog();
        switch($request->action){
            case 'save_table_morsefallscale':
                switch($request->oper){
                    case 'add':
                        return $this->add_morsefallscale($request);
                    case 'edit':
                        return $this->edit_morsefallscale($request);
                    default:
                        return 'error happen..';
                }
            
            case 'get_table_morsefallscale':
                return $this->get_table_morsefallscale($request);
            
            default:
                return 'error happen..';
        }
        
        // switch($request->oper){
        //     default:
        //         return 'error happen..';
        // }
    }
    
    public function get_datetime_morsefallscale(Request $request){
        
        $responce = new stdClass();
        
        $morsefallscale_obj = DB::table('nursing.morsefallscale')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno);
        
        if($morsefallscale_obj->exists()){
            $morsefallscale_obj = $morsefallscale_obj->get();
            
            $data = [];
            
            foreach($morsefallscale_obj as $key => $value){
                $date['idno'] = $value->idno;
                $date['mrn'] = $value->mrn;
                $date['episno'] = $value->episno;
                if(!empty($value->datetaken)){
                    $date['datetaken'] =  Carbon::createFromFormat('Y-m-d', $value->datetaken)->format('d-m-Y');
                }else{
                    $date['datetaken'] =  '-';
                }
                // $date['timetaken'] = $value->timetaken;
                if(!empty($value->timetaken)){
                    $date['timetaken'] =  Carbon::createFromFormat('H:i:s', $value->timetaken)->format('h:i A');
                }else{
                    $date['timetaken'] =  '-';
                }
                $date['adduser'] = $value->adduser;
                if(!empty($value->datetaken)){ // for sorting - easier in 24H
                    $date['dt'] =  Carbon::createFromFormat('Y-m-d', $value->datetaken)->format('d-m-Y').'<br>'.$value->timetaken;
                }else{
                    $date['dt'] =  '-';
                }
                
                array_push($data,$date);
            }
            
            $responce->data = $data;
        }else{
            $responce->data = [];
        }
        
        return json_encode($responce);
        
    }
    
    public function add_morsefallscale(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.morsefallscale')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn_nursNote,
                    'episno' => $request->episno_nursNote,
                    'datetaken' => $request->datetaken,
                    'timetaken' => $request->timetaken,
                    'fallHistory' => $request->fallHistory,
                    'secondaryDiag' => $request->secondaryDiag,
                    'ambulatoryAids' => $request->ambulatoryAids,
                    'IVtherapy' => $request->IVtherapy,
                    'gait' => $request->gait,
                    'mentalStatus' => $request->mentalStatus,
                    'totalScore' => $request->totalScore,
                    'staffname'  => $request->staffname,
                    'adduser'  => session('username'),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'computerid' => session('computerid'),
                ]);
            
            $pat_otbook = DB::table('hisdb.pat_otbook')
                        ->where('mrn','=',$request->mrn_nursNote)
                        ->where('episno','=',$request->episno_nursNote)
                        ->where('compcode','=',session('compcode'));
            
            if($pat_otbook->exists()){
                $pat_otbook
                    ->update([
                        'diagnosis' => $request->diag,
                        // 'diagnosedby' => session('username'),
                        'upduser'  => session('username'),
                        'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser' => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
            }else{
                DB::table('hisdb.pat_otbook')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_nursNote,
                        'episno' => $request->episno_nursNote,
                        'diagnosis' => $request->diag,
                        // 'diagnosedby' => session('username'),
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser' => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
            }
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function edit_morsefallscale(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            if(!empty($request->idno_morsefallscale)){
                DB::table('nursing.morsefallscale')
                    ->where('idno','=',$request->idno_morsefallscale)
                    // ->where('mrn','=',$request->mrn_nursNote)
                    // ->where('episno','=',$request->episno_nursNote)
                    ->update([
                        'timetaken' => $request->timetaken,
                        'fallHistory' => $request->fallHistory,
                        'secondaryDiag' => $request->secondaryDiag,
                        'ambulatoryAids' => $request->ambulatoryAids,
                        'IVtherapy' => $request->IVtherapy,
                        'gait' => $request->gait,
                        'mentalStatus' => $request->mentalStatus,
                        'totalScore' => $request->totalScore,
                        'staffname'  => $request->staffname,
                        'upduser'  => session('username'),
                        'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
            }else{
                DB::table('nursing.morsefallscale')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_nursNote,
                        'episno' => $request->episno_nursNote,
                        'datetaken' => $request->datetaken,
                        'timetaken' => $request->timetaken,
                        'fallHistory' => $request->fallHistory,
                        'secondaryDiag' => $request->secondaryDiag,
                        'ambulatoryAids' => $request->ambulatoryAids,
                        'IVtherapy' => $request->IVtherapy,
                        'gait' => $request->gait,
                        'mentalStatus' => $request->mentalStatus,
                        'totalScore' => $request->totalScore,
                        'staffname'  => $request->staffname,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
            }
            
            $pat_otbook = DB::table('hisdb.pat_otbook')
                        ->where('mrn','=',$request->mrn_nursNote)
                        ->where('episno','=',$request->episno_nursNote)
                        ->where('compcode','=',session('compcode'));
            
            if($pat_otbook->exists()){
                $pat_otbook
                    ->update([
                        'diagnosis' => $request->diag,
                        // 'diagnosedby' => session('username'),
                        'upduser'  => session('username'),
                        'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser' => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
            }else{
                DB::table('hisdb.pat_otbook')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_nursNote,
                        'episno' => $request->episno_nursNote,
                        'diagnosis' => $request->diag,
                        // 'diagnosedby' => session('username'),
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser' => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
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
    
    public function get_table_morsefallscale(Request $request){
        
        // $nursassessment_obj = DB::table('nursing.nursassessment')
        //                     ->select('diagnosis')
        //                     ->where('compcode','=',session('compcode'))
        //                     ->where('mrn','=',$request->mrn)
        //                     ->where('episno','=',$request->episno);
        
        $pat_otbook_obj = DB::table('hisdb.pat_otbook')
                        ->select('diagnosis')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno);
        
        $episode_obj = DB::table('hisdb.episode')
                        ->select('reg_date')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno);
        
        $morsefallscale_obj = DB::table('nursing.morsefallscale')
                            ->where('compcode','=',session('compcode'))
                            ->where('idno','=',$request->idno);
                            // ->where('mrn','=',$request->mrn)
                            // ->where('episno','=',$request->episno);
        
        $responce = new stdClass();
        
        // if($nursassessment_obj->exists()){
        //     $nursassessment_obj = $nursassessment_obj->first();
            
        //     $diagnosis_obj = $nursassessment_obj->diagnosis;
        //     $responce->diagnosis = $diagnosis_obj;
        // }
        
        if($pat_otbook_obj->exists()){
            $pat_otbook_obj = $pat_otbook_obj->first();
            
            $diagnosis_obj = $pat_otbook_obj->diagnosis;
            $responce->diagnosis = $diagnosis_obj;
        }
        
        if($episode_obj->exists()){
            $episode_obj = $episode_obj->first();
            
            $regdate_obj = $episode_obj->reg_date;
            $responce->reg_date = $regdate_obj;
        }
        
        if($morsefallscale_obj->exists()){
            $morsefallscale_obj = $morsefallscale_obj->first();
            $date = Carbon::createFromFormat('Y-m-d', $morsefallscale_obj->datetaken)->format('Y-m-d');
            
            $responce->morsefallscale = $morsefallscale_obj;
            $responce->date = $date;
        }
        
        return json_encode($responce);
        
    }
    
}