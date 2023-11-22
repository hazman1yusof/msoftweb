<?php

namespace App\Http\Controllers\hisdb;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class NursingNoteController extends defaultController
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
            case 'get_table_datetime':
                return $this->get_table_datetime($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function form(Request $request){
        DB::enableQueryLog();
        switch($request->action){
            case 'save_table_progress':
                switch($request->oper){
                    case 'add':
                        return $this->add_progress($request);
                    case 'edit':
                        return $this->edit_progress($request);
                    default:
                        return 'error happen..';
                }
            
            case 'save_table_intake':
                switch($request->oper){
                    case 'add':
                        return $this->add_intake($request);
                    case 'edit':
                        return $this->edit_intake($request);
                    default:
                        return 'error happen..';
                }
            
            case 'get_table_progress':
                return $this->get_table_progress($request);
            
            case 'get_table_intake':
                return $this->get_table_intake($request);
            
            default:
                return 'error happen..';
        }
        
        // switch($request->oper){
        //     default:
        //         return 'error happen..';
        // }
    }
    
    public function get_table_datetime(Request $request){
        
        $responce = new stdClass();
        
        $nurshandover_obj = DB::table('nursing.nurshandover')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno);
        
        if($nurshandover_obj->exists()){
            $nurshandover_obj = $nurshandover_obj->get();
            
            $data = [];
            
            foreach ($nurshandover_obj as $key => $value) {
                if(!empty($value->datetaken)){
                    $date['datetaken'] =  Carbon::createFromFormat('Y-m-d', $value->datetaken)->format('d-m-Y');
                }else{
                    $date['datetaken'] =  '-';
                }
                $date['idno'] = $value->idno;
                $date['mrn'] = $value->mrn;
                $date['episno'] = $value->episno;
                // $date['timetaken'] = $value->timetaken;
                if(!empty($value->timetaken)){
                    $date['timetaken'] =  Carbon::createFromFormat('H:i:s', $value->timetaken)->format('h:i A');
                }else{
                    $date['timetaken'] =  '-';
                }
                $date['lastuser'] = $value->lastuser;
                
                array_push($data,$date);
            }
            
            $responce->data = $data;
        }else{
            $responce->data = [];
        }
        
        return json_encode($responce);
        
    }
    
    public function add_progress(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.nurshandover')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn_nursNote,
                    'episno' => $request->episno_nursNote,
                    'datetaken' => $request->datetaken,
                    'timetaken' => $request->timetaken,
                    'lastuser'  => session('username'),
                    'temp_' => $request->temp_,
                    'hr' => $request->hr,
                    'spo2' => $request->spo2,
                    'bphistolic' => $request->bphistolic,
                    'bpdiastolic' => $request->bpdiastolic,
                    'dxt' => $request->dxt,
                    'roomair' => $request->roomair,
                    'oxygen' => $request->oxygen,
                    'airwayfreetext' => $request->airwayfreetext,
                    'breathnormal' => $request->breathnormal,
                    'breathdifficult' => $request->breathdifficult,
                    'circarrythmias' => $request->circarrythmias,
                    'circlbp' => $request->circlbp,
                    'circhbp' => $request->circhbp,
                    'circirregular' => $request->circirregular,
                    'frhigh' => $request->frhigh,
                    'frlow' => $request->frlow,
                    'frfreetext' => $request->frfreetext,
                    'drainnone' => $request->drainnone,
                    'draindrainage' => $request->draindrainage,
                    'drainfreetext' => $request->drainfreetext,
                    'ivlnone' => $request->ivlnone,
                    'ivlsite' => $request->ivlsite,
                    'ivfreetext' => $request->ivfreetext,
                    'gucontinent' => $request->gucontinent,
                    'gufoley' => $request->gufoley,
                    'assesothers' => $request->assesothers,
                    'plannotes' => $request->plannotes,
                    // 'adduser'  => session('username'),
                    // 'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    // 'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function edit_progress(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            if(!empty($request->idno_progress)){
                DB::table('nursing.nurshandover')
                    ->where('idno','=',$request->idno_progress)
                    // ->where('mrn','=',$request->mrn_nursNote)
                    // ->where('episno','=',$request->episno_nursNote)
                    ->update([
                        'temp_' => $request->temp_,
                        'hr' => $request->hr,
                        'spo2' => $request->spo2,
                        'bphistolic' => $request->bphistolic,
                        'bpdiastolic' => $request->bpdiastolic,
                        'dxt' => $request->dxt,
                        'roomair' => $request->roomair,
                        'oxygen' => $request->oxygen,
                        'airwayfreetext' => $request->airwayfreetext,
                        'breathnormal' => $request->breathnormal,
                        'breathdifficult' => $request->breathdifficult,
                        'circarrythmias' => $request->circarrythmias,
                        'circlbp' => $request->circlbp,
                        'circhbp' => $request->circhbp,
                        'circirregular' => $request->circirregular,
                        'frhigh' => $request->frhigh,
                        'frlow' => $request->frlow,
                        'frfreetext' => $request->frfreetext,
                        'drainnone' => $request->drainnone,
                        'draindrainage' => $request->draindrainage,
                        'drainfreetext' => $request->drainfreetext,
                        'ivlnone' => $request->ivlnone,
                        'ivlsite' => $request->ivlsite,
                        'ivfreetext' => $request->ivfreetext,
                        'gucontinent' => $request->gucontinent,
                        'gufoley' => $request->gufoley,
                        'assesothers' => $request->assesothers,
                        'plannotes' => $request->plannotes,
                    ]);
            }else{
                DB::table('nursing.nurshandover')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_nursNote,
                        'episno' => $request->episno_nursNote,
                        'datetaken' => $request->datetaken,
                        'timetaken' => $request->timetaken,
                        'lastuser'  => session('username'),
                        'temp_' => $request->temp_,
                        'hr' => $request->hr,
                        'spo2' => $request->spo2,
                        'bphistolic' => $request->bphistolic,
                        'bpdiastolic' => $request->bpdiastolic,
                        'dxt' => $request->dxt,
                        'roomair' => $request->roomair,
                        'oxygen' => $request->oxygen,
                        'airwayfreetext' => $request->airwayfreetext,
                        'breathnormal' => $request->breathnormal,
                        'breathdifficult' => $request->breathdifficult,
                        'circarrythmias' => $request->circarrythmias,
                        'circlbp' => $request->circlbp,
                        'circhbp' => $request->circhbp,
                        'circirregular' => $request->circirregular,
                        'frhigh' => $request->frhigh,
                        'frlow' => $request->frlow,
                        'frfreetext' => $request->frfreetext,
                        'drainnone' => $request->drainnone,
                        'draindrainage' => $request->draindrainage,
                        'drainfreetext' => $request->drainfreetext,
                        'ivlnone' => $request->ivlnone,
                        'ivlsite' => $request->ivlsite,
                        'ivfreetext' => $request->ivfreetext,
                        'gucontinent' => $request->gucontinent,
                        'gufoley' => $request->gufoley,
                        'assesothers' => $request->assesothers,
                        'plannotes' => $request->plannotes,
                        // 'adduser'  => session('username'),
                        // 'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        // 'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
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
    
    public function add_intake(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.intakeoutput')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn_nursNote,
                    'episno' => $request->episno_nursNote,
                    // First shift
                    'oraltype1' => $request->oraltype1,
                    'oralamt1' => $request->oralamt1,
                    'intratype1' => $request->intratype1,
                    'intraamt1' => $request->intraamt1,
                    'othertype1' => $request->othertype1,
                    'otheramt1' => $request->otheramt1,
                    'urineamt1' => $request->urineamt1,
                    'vomitamt1' => $request->vomitamt1,
                    'aspamt1' => $request->aspamt1,
                    'otherout1' => $request->otherout1,
                    'oraltype2' => $request->oraltype2,
                    'oralamt2' => $request->oralamt2,
                    'intratype2' => $request->intratype2,
                    'intraamt2' => $request->intraamt2,
                    'othertype2' => $request->othertype2,
                    'otheramt2' => $request->otheramt2,
                    'urineamt2' => $request->urineamt2,
                    'vomitamt2' => $request->vomitamt2,
                    'aspamt2' => $request->aspamt2,
                    'otherout2' => $request->otherout2,
                    'oraltype3' => $request->oraltype3,
                    'oralamt3' => $request->oralamt3,
                    'intratype3' => $request->intratype3,
                    'intraamt3' => $request->intraamt3,
                    'othertype3' => $request->othertype3,
                    'otheramt3' => $request->otheramt3,
                    'urineamt3' => $request->urineamt3,
                    'vomitamt3' => $request->vomitamt3,
                    'aspamt3' => $request->aspamt3,
                    'otherout3' => $request->otherout3,
                    'oraltype4' => $request->oraltype4,
                    'oralamt4' => $request->oralamt4,
                    'intratype4' => $request->intratype4,
                    'intraamt4' => $request->intraamt4,
                    'othertype4' => $request->othertype4,
                    'otheramt4' => $request->otheramt4,
                    'urineamt4' => $request->urineamt4,
                    'vomitamt4' => $request->vomitamt4,
                    'aspamt4' => $request->aspamt4,
                    'otherout4' => $request->otherout4,
                    'oraltype5' => $request->oraltype5,
                    'oralamt5' => $request->oralamt5,
                    'intratype5' => $request->intratype5,
                    'intraamt5' => $request->intraamt5,
                    'othertype5' => $request->othertype5,
                    'otheramt5' => $request->otheramt5,
                    'urineamt5' => $request->urineamt5,
                    'vomitamt5' => $request->vomitamt5,
                    'aspamt5' => $request->aspamt5,
                    'otherout5' => $request->otherout5,
                    'oraltype6' => $request->oraltype6,
                    'oralamt6' => $request->oralamt6,
                    'intratype6' => $request->intratype6,
                    'intraamt6' => $request->intraamt6,
                    'othertype6' => $request->othertype6,
                    'otheramt6' => $request->otheramt6,
                    'urineamt6' => $request->urineamt6,
                    'vomitamt6' => $request->vomitamt6,
                    'aspamt6' => $request->aspamt6,
                    'otherout6' => $request->otherout6,
                    'oraltype7' => $request->oraltype7,
                    'oralamt7' => $request->oralamt7,
                    'intratype7' => $request->intratype7,
                    'intraamt7' => $request->intraamt7,
                    'othertype7' => $request->othertype7,
                    'otheramt7' => $request->otheramt7,
                    'urineamt7' => $request->urineamt7,
                    'vomitamt7' => $request->vomitamt7,
                    'aspamt7' => $request->aspamt7,
                    'otherout7' => $request->otherout7,
                    'oraltype8' => $request->oraltype8,
                    'oralamt8' => $request->oralamt8,
                    'intratype8' => $request->intratype8,
                    'intraamt8' => $request->intraamt8,
                    'othertype8' => $request->othertype8,
                    'otheramt8' => $request->otheramt8,
                    'urineamt8' => $request->urineamt8,
                    'vomitamt8' => $request->vomitamt8,
                    'aspamt8' => $request->aspamt8,
                    'otherout8' => $request->otherout8,
                    // Second shift
                    'oraltype9' => $request->oraltype9,
                    'oralamt9' => $request->oralamt9,
                    'intratype9' => $request->intratype9,
                    'intraamt9' => $request->intraamt9,
                    'othertype9' => $request->othertype9,
                    'otheramt9' => $request->otheramt9,
                    'urineamt9' => $request->urineamt9,
                    'vomitamt9' => $request->vomitamt9,
                    'aspamt9' => $request->aspamt9,
                    'otherout9' => $request->otherout9,
                    'oraltype10' => $request->oraltype10,
                    'oralamt10' => $request->oralamt10,
                    'intratype10' => $request->intratype10,
                    'intraamt10' => $request->intraamt10,
                    'othertype10' => $request->othertype10,
                    'otheramt10' => $request->otheramt10,
                    'urineamt10' => $request->urineamt10,
                    'vomitamt10' => $request->vomitamt10,
                    'aspamt10' => $request->aspamt10,
                    'otherout10' => $request->otherout10,
                    'oraltype11' => $request->oraltype11,
                    'oralamt11' => $request->oralamt11,
                    'intratype11' => $request->intratype11,
                    'intraamt11' => $request->intraamt11,
                    'othertype11' => $request->othertype11,
                    'otheramt11' => $request->otheramt11,
                    'urineamt11' => $request->urineamt11,
                    'vomitamt11' => $request->vomitamt11,
                    'aspamt11' => $request->aspamt11,
                    'otherout11' => $request->otherout11,
                    'oraltype12' => $request->oraltype12,
                    'oralamt12' => $request->oralamt12,
                    'intratype12' => $request->intratype12,
                    'intraamt12' => $request->intraamt12,
                    'othertype12' => $request->othertype12,
                    'otheramt12' => $request->otheramt12,
                    'urineamt12' => $request->urineamt12,
                    'vomitamt12' => $request->vomitamt12,
                    'aspamt12' => $request->aspamt12,
                    'otherout12' => $request->otherout12,
                    'oraltype13' => $request->oraltype13,
                    'oralamt13' => $request->oralamt13,
                    'intratype13' => $request->intratype13,
                    'intraamt13' => $request->intraamt13,
                    'othertype13' => $request->othertype13,
                    'otheramt13' => $request->otheramt13,
                    'urineamt13' => $request->urineamt13,
                    'vomitamt13' => $request->vomitamt13,
                    'aspamt13' => $request->aspamt13,
                    'otherout13' => $request->otherout13,
                    'oraltype14' => $request->oraltype14,
                    'oralamt14' => $request->oralamt14,
                    'intratype14' => $request->intratype14,
                    'intraamt14' => $request->intraamt14,
                    'othertype14' => $request->othertype14,
                    'otheramt14' => $request->otheramt14,
                    'urineamt14' => $request->urineamt14,
                    'vomitamt14' => $request->vomitamt14,
                    'aspamt14' => $request->aspamt14,
                    'otherout14' => $request->otherout14,
                    'oraltype15' => $request->oraltype15,
                    'oralamt15' => $request->oralamt15,
                    'intratype15' => $request->intratype15,
                    'intraamt15' => $request->intraamt15,
                    'othertype15' => $request->othertype15,
                    'otheramt15' => $request->otheramt15,
                    'urineamt15' => $request->urineamt15,
                    'vomitamt15' => $request->vomitamt15,
                    'aspamt15' => $request->aspamt15,
                    'otherout15' => $request->otherout15,
                    'oraltype16' => $request->oraltype16,
                    'oralamt16' => $request->oralamt16,
                    'intratype16' => $request->intratype16,
                    'intraamt16' => $request->intraamt16,
                    'othertype16' => $request->othertype16,
                    'otheramt16' => $request->otheramt16,
                    'urineamt16' => $request->urineamt16,
                    'vomitamt16' => $request->vomitamt16,
                    'aspamt16' => $request->aspamt16,
                    'otherout16' => $request->otherout16,
                    // Third shift
                    'oraltype17' => $request->oraltype17,
                    'oralamt17' => $request->oralamt17,
                    'intratype17' => $request->intratype17,
                    'intraamt17' => $request->intraamt17,
                    'othertype17' => $request->othertype17,
                    'otheramt17' => $request->otheramt17,
                    'urineamt17' => $request->urineamt17,
                    'vomitamt17' => $request->vomitamt17,
                    'aspamt17' => $request->aspamt17,
                    'otherout17' => $request->otherout17,
                    'oraltype18' => $request->oraltype18,
                    'oralamt18' => $request->oralamt18,
                    'intratype18' => $request->intratype18,
                    'intraamt18' => $request->intraamt18,
                    'othertype18' => $request->othertype18,
                    'otheramt18' => $request->otheramt18,
                    'urineamt18' => $request->urineamt18,
                    'vomitamt18' => $request->vomitamt18,
                    'aspamt18' => $request->aspamt18,
                    'otherout18' => $request->otherout18,
                    'oraltype19' => $request->oraltype19,
                    'oralamt19' => $request->oralamt19,
                    'intratype19' => $request->intratype19,
                    'intraamt19' => $request->intraamt19,
                    'othertype19' => $request->othertype19,
                    'otheramt19' => $request->otheramt19,
                    'urineamt19' => $request->urineamt19,
                    'vomitamt19' => $request->vomitamt19,
                    'aspamt19' => $request->aspamt19,
                    'otherout19' => $request->otherout19,
                    'oraltype20' => $request->oraltype20,
                    'oralamt20' => $request->oralamt20,
                    'intratype20' => $request->intratype20,
                    'intraamt20' => $request->intraamt20,
                    'othertype20' => $request->othertype20,
                    'otheramt20' => $request->otheramt20,
                    'urineamt20' => $request->urineamt20,
                    'vomitamt20' => $request->vomitamt20,
                    'aspamt20' => $request->aspamt20,
                    'otherout20' => $request->otherout20,
                    'oraltype21' => $request->oraltype21,
                    'oralamt21' => $request->oralamt21,
                    'intratype21' => $request->intratype21,
                    'intraamt21' => $request->intraamt21,
                    'othertype21' => $request->othertype21,
                    'otheramt21' => $request->otheramt21,
                    'urineamt21' => $request->urineamt21,
                    'vomitamt21' => $request->vomitamt21,
                    'aspamt21' => $request->aspamt21,
                    'otherout21' => $request->otherout21,
                    'oraltype22' => $request->oraltype22,
                    'oralamt22' => $request->oralamt22,
                    'intratype22' => $request->intratype22,
                    'intraamt22' => $request->intraamt22,
                    'othertype22' => $request->othertype22,
                    'otheramt22' => $request->otheramt22,
                    'urineamt22' => $request->urineamt22,
                    'vomitamt22' => $request->vomitamt22,
                    'aspamt22' => $request->aspamt22,
                    'otherout22' => $request->otherout22,
                    'oraltype23' => $request->oraltype23,
                    'oralamt23' => $request->oralamt23,
                    'intratype23' => $request->intratype23,
                    'intraamt23' => $request->intraamt23,
                    'othertype23' => $request->othertype23,
                    'otheramt23' => $request->otheramt23,
                    'urineamt23' => $request->urineamt23,
                    'vomitamt23' => $request->vomitamt23,
                    'aspamt23' => $request->aspamt23,
                    'otherout23' => $request->otherout23,
                    'oraltype24' => $request->oraltype24,
                    'oralamt24' => $request->oralamt24,
                    'intratype24' => $request->intratype24,
                    'intraamt24' => $request->intraamt24,
                    'othertype24' => $request->othertype24,
                    'otheramt24' => $request->otheramt24,
                    'urineamt24' => $request->urineamt24,
                    'vomitamt24' => $request->vomitamt24,
                    'aspamt24' => $request->aspamt24,
                    'otherout24' => $request->otherout24,
                    'recorddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'recordtime'  => Carbon::now("Asia/Kuala_Lumpur"),
                    // 'adduser'  => session('username'),
                    // 'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    // 'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function edit_intake(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.intakeoutput')
                ->where('mrn','=',$request->mrn_nursNote)
                ->where('episno','=',$request->episno_nursNote)
                ->update([
                    // First shift
                    'oraltype1' => $request->oraltype1,
                    'oralamt1' => $request->oralamt1,
                    'intratype1' => $request->intratype1,
                    'intraamt1' => $request->intraamt1,
                    'othertype1' => $request->othertype1,
                    'otheramt1' => $request->otheramt1,
                    'urineamt1' => $request->urineamt1,
                    'vomitamt1' => $request->vomitamt1,
                    'aspamt1' => $request->aspamt1,
                    'otherout1' => $request->otherout1,
                    'oraltype2' => $request->oraltype2,
                    'oralamt2' => $request->oralamt2,
                    'intratype2' => $request->intratype2,
                    'intraamt2' => $request->intraamt2,
                    'othertype2' => $request->othertype2,
                    'otheramt2' => $request->otheramt2,
                    'urineamt2' => $request->urineamt2,
                    'vomitamt2' => $request->vomitamt2,
                    'aspamt2' => $request->aspamt2,
                    'otherout2' => $request->otherout2,
                    'oraltype3' => $request->oraltype3,
                    'oralamt3' => $request->oralamt3,
                    'intratype3' => $request->intratype3,
                    'intraamt3' => $request->intraamt3,
                    'othertype3' => $request->othertype3,
                    'otheramt3' => $request->otheramt3,
                    'urineamt3' => $request->urineamt3,
                    'vomitamt3' => $request->vomitamt3,
                    'aspamt3' => $request->aspamt3,
                    'otherout3' => $request->otherout3,
                    'oraltype4' => $request->oraltype4,
                    'oralamt4' => $request->oralamt4,
                    'intratype4' => $request->intratype4,
                    'intraamt4' => $request->intraamt4,
                    'othertype4' => $request->othertype4,
                    'otheramt4' => $request->otheramt4,
                    'urineamt4' => $request->urineamt4,
                    'vomitamt4' => $request->vomitamt4,
                    'aspamt4' => $request->aspamt4,
                    'otherout4' => $request->otherout4,
                    'oraltype5' => $request->oraltype5,
                    'oralamt5' => $request->oralamt5,
                    'intratype5' => $request->intratype5,
                    'intraamt5' => $request->intraamt5,
                    'othertype5' => $request->othertype5,
                    'otheramt5' => $request->otheramt5,
                    'urineamt5' => $request->urineamt5,
                    'vomitamt5' => $request->vomitamt5,
                    'aspamt5' => $request->aspamt5,
                    'otherout5' => $request->otherout5,
                    'oraltype6' => $request->oraltype6,
                    'oralamt6' => $request->oralamt6,
                    'intratype6' => $request->intratype6,
                    'intraamt6' => $request->intraamt6,
                    'othertype6' => $request->othertype6,
                    'otheramt6' => $request->otheramt6,
                    'urineamt6' => $request->urineamt6,
                    'vomitamt6' => $request->vomitamt6,
                    'aspamt6' => $request->aspamt6,
                    'otherout6' => $request->otherout6,
                    'oraltype7' => $request->oraltype7,
                    'oralamt7' => $request->oralamt7,
                    'intratype7' => $request->intratype7,
                    'intraamt7' => $request->intraamt7,
                    'othertype7' => $request->othertype7,
                    'otheramt7' => $request->otheramt7,
                    'urineamt7' => $request->urineamt7,
                    'vomitamt7' => $request->vomitamt7,
                    'aspamt7' => $request->aspamt7,
                    'otherout7' => $request->otherout7,
                    'oraltype8' => $request->oraltype8,
                    'oralamt8' => $request->oralamt8,
                    'intratype8' => $request->intratype8,
                    'intraamt8' => $request->intraamt8,
                    'othertype8' => $request->othertype8,
                    'otheramt8' => $request->otheramt8,
                    'urineamt8' => $request->urineamt8,
                    'vomitamt8' => $request->vomitamt8,
                    'aspamt8' => $request->aspamt8,
                    'otherout8' => $request->otherout8,
                    // Second shift
                    'oraltype9' => $request->oraltype9,
                    'oralamt9' => $request->oralamt9,
                    'intratype9' => $request->intratype9,
                    'intraamt9' => $request->intraamt9,
                    'othertype9' => $request->othertype9,
                    'otheramt9' => $request->otheramt9,
                    'urineamt9' => $request->urineamt9,
                    'vomitamt9' => $request->vomitamt9,
                    'aspamt9' => $request->aspamt9,
                    'otherout9' => $request->otherout9,
                    'oraltype10' => $request->oraltype10,
                    'oralamt10' => $request->oralamt10,
                    'intratype10' => $request->intratype10,
                    'intraamt10' => $request->intraamt10,
                    'othertype10' => $request->othertype10,
                    'otheramt10' => $request->otheramt10,
                    'urineamt10' => $request->urineamt10,
                    'vomitamt10' => $request->vomitamt10,
                    'aspamt10' => $request->aspamt10,
                    'otherout10' => $request->otherout10,
                    'oraltype11' => $request->oraltype11,
                    'oralamt11' => $request->oralamt11,
                    'intratype11' => $request->intratype11,
                    'intraamt11' => $request->intraamt11,
                    'othertype11' => $request->othertype11,
                    'otheramt11' => $request->otheramt11,
                    'urineamt11' => $request->urineamt11,
                    'vomitamt11' => $request->vomitamt11,
                    'aspamt11' => $request->aspamt11,
                    'otherout11' => $request->otherout11,
                    'oraltype12' => $request->oraltype12,
                    'oralamt12' => $request->oralamt12,
                    'intratype12' => $request->intratype12,
                    'intraamt12' => $request->intraamt12,
                    'othertype12' => $request->othertype12,
                    'otheramt12' => $request->otheramt12,
                    'urineamt12' => $request->urineamt12,
                    'vomitamt12' => $request->vomitamt12,
                    'aspamt12' => $request->aspamt12,
                    'otherout12' => $request->otherout12,
                    'oraltype13' => $request->oraltype13,
                    'oralamt13' => $request->oralamt13,
                    'intratype13' => $request->intratype13,
                    'intraamt13' => $request->intraamt13,
                    'othertype13' => $request->othertype13,
                    'otheramt13' => $request->otheramt13,
                    'urineamt13' => $request->urineamt13,
                    'vomitamt13' => $request->vomitamt13,
                    'aspamt13' => $request->aspamt13,
                    'otherout13' => $request->otherout13,
                    'oraltype14' => $request->oraltype14,
                    'oralamt14' => $request->oralamt14,
                    'intratype14' => $request->intratype14,
                    'intraamt14' => $request->intraamt14,
                    'othertype14' => $request->othertype14,
                    'otheramt14' => $request->otheramt14,
                    'urineamt14' => $request->urineamt14,
                    'vomitamt14' => $request->vomitamt14,
                    'aspamt14' => $request->aspamt14,
                    'otherout14' => $request->otherout14,
                    'oraltype15' => $request->oraltype15,
                    'oralamt15' => $request->oralamt15,
                    'intratype15' => $request->intratype15,
                    'intraamt15' => $request->intraamt15,
                    'othertype15' => $request->othertype15,
                    'otheramt15' => $request->otheramt15,
                    'urineamt15' => $request->urineamt15,
                    'vomitamt15' => $request->vomitamt15,
                    'aspamt15' => $request->aspamt15,
                    'otherout15' => $request->otherout15,
                    'oraltype16' => $request->oraltype16,
                    'oralamt16' => $request->oralamt16,
                    'intratype16' => $request->intratype16,
                    'intraamt16' => $request->intraamt16,
                    'othertype16' => $request->othertype16,
                    'otheramt16' => $request->otheramt16,
                    'urineamt16' => $request->urineamt16,
                    'vomitamt16' => $request->vomitamt16,
                    'aspamt16' => $request->aspamt16,
                    'otherout16' => $request->otherout16,
                    // Third shift
                    'oraltype17' => $request->oraltype17,
                    'oralamt17' => $request->oralamt17,
                    'intratype17' => $request->intratype17,
                    'intraamt17' => $request->intraamt17,
                    'othertype17' => $request->othertype17,
                    'otheramt17' => $request->otheramt17,
                    'urineamt17' => $request->urineamt17,
                    'vomitamt17' => $request->vomitamt17,
                    'aspamt17' => $request->aspamt17,
                    'otherout17' => $request->otherout17,
                    'oraltype18' => $request->oraltype18,
                    'oralamt18' => $request->oralamt18,
                    'intratype18' => $request->intratype18,
                    'intraamt18' => $request->intraamt18,
                    'othertype18' => $request->othertype18,
                    'otheramt18' => $request->otheramt18,
                    'urineamt18' => $request->urineamt18,
                    'vomitamt18' => $request->vomitamt18,
                    'aspamt18' => $request->aspamt18,
                    'otherout18' => $request->otherout18,
                    'oraltype19' => $request->oraltype19,
                    'oralamt19' => $request->oralamt19,
                    'intratype19' => $request->intratype19,
                    'intraamt19' => $request->intraamt19,
                    'othertype19' => $request->othertype19,
                    'otheramt19' => $request->otheramt19,
                    'urineamt19' => $request->urineamt19,
                    'vomitamt19' => $request->vomitamt19,
                    'aspamt19' => $request->aspamt19,
                    'otherout19' => $request->otherout19,
                    'oraltype20' => $request->oraltype20,
                    'oralamt20' => $request->oralamt20,
                    'intratype20' => $request->intratype20,
                    'intraamt20' => $request->intraamt20,
                    'othertype20' => $request->othertype20,
                    'otheramt20' => $request->otheramt20,
                    'urineamt20' => $request->urineamt20,
                    'vomitamt20' => $request->vomitamt20,
                    'aspamt20' => $request->aspamt20,
                    'otherout20' => $request->otherout20,
                    'oraltype21' => $request->oraltype21,
                    'oralamt21' => $request->oralamt21,
                    'intratype21' => $request->intratype21,
                    'intraamt21' => $request->intraamt21,
                    'othertype21' => $request->othertype21,
                    'otheramt21' => $request->otheramt21,
                    'urineamt21' => $request->urineamt21,
                    'vomitamt21' => $request->vomitamt21,
                    'aspamt21' => $request->aspamt21,
                    'otherout21' => $request->otherout21,
                    'oraltype22' => $request->oraltype22,
                    'oralamt22' => $request->oralamt22,
                    'intratype22' => $request->intratype22,
                    'intraamt22' => $request->intraamt22,
                    'othertype22' => $request->othertype22,
                    'otheramt22' => $request->otheramt22,
                    'urineamt22' => $request->urineamt22,
                    'vomitamt22' => $request->vomitamt22,
                    'aspamt22' => $request->aspamt22,
                    'otherout22' => $request->otherout22,
                    'oraltype23' => $request->oraltype23,
                    'oralamt23' => $request->oralamt23,
                    'intratype23' => $request->intratype23,
                    'intraamt23' => $request->intraamt23,
                    'othertype23' => $request->othertype23,
                    'otheramt23' => $request->otheramt23,
                    'urineamt23' => $request->urineamt23,
                    'vomitamt23' => $request->vomitamt23,
                    'aspamt23' => $request->aspamt23,
                    'otherout23' => $request->otherout23,
                    'oraltype24' => $request->oraltype24,
                    'oralamt24' => $request->oralamt24,
                    'intratype24' => $request->intratype24,
                    'intraamt24' => $request->intraamt24,
                    'othertype24' => $request->othertype24,
                    'otheramt24' => $request->otheramt24,
                    'urineamt24' => $request->urineamt24,
                    'vomitamt24' => $request->vomitamt24,
                    'aspamt24' => $request->aspamt24,
                    'otherout24' => $request->otherout24,
                ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function get_table_progress(Request $request){
        
        $nurshandover_obj = DB::table('nursing.nurshandover')
                            ->where('compcode','=',session('compcode'))
                            ->where('idno','=',$request->idno);
                            // ->where('mrn','=',$request->mrn)
                            // ->where('episno','=',$request->episno);
        
        $responce = new stdClass();
        
        if($nurshandover_obj->exists()){
            $nurshandover_obj = $nurshandover_obj->first();
            $date = Carbon::createFromFormat('Y-m-d', $nurshandover_obj->datetaken)->format('Y-m-d');
            
            $responce->nurshandover = $nurshandover_obj;
            $responce->date = $date;
        }
        
        return json_encode($responce);
        
    }
    
    public function get_table_intake(Request $request){
        
        $intakeoutput_obj = DB::table('nursing.intakeoutput')
                            ->where('compcode','=',session('compcode'))
                            // ->where('idno','=',$request->idno);
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno);
        
        $responce = new stdClass();
        
        if($intakeoutput_obj->exists()){
            $intakeoutput_obj = $intakeoutput_obj->first();
            $responce->intakeoutput = $intakeoutput_obj;
        }
        
        return json_encode($responce);
        
    }
    
}