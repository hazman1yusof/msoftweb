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
            case 'get_table_datetime':  // Progress Note
                return $this->get_table_datetime($request);
            
            case 'get_prescription':    // Drug Administration
                return $this->get_prescription($request);
            
            case 'get_datetime_treatment':  // Treatment
                return $this->get_datetime_treatment($request);
            
            case 'get_datetime_careplan':   // Care Plan
                return $this->get_datetime_careplan($request);
            
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
            
            case 'patMedic_save':
                return $this->add_patMedic($request);
            
            case 'save_table_treatment':
                switch($request->oper){
                    case 'add':
                        return $this->add_treatment($request);
                    default:
                        return 'error happen..';
                }
            
            case 'save_table_investigation':
                switch($request->oper){
                    case 'add':
                        return $this->add_investigation($request);
                    default:
                        return 'error happen..';
                }
            
            case 'save_table_injection':
                switch($request->oper){
                    case 'add':
                        return $this->add_injection($request);
                    default:
                        return 'error happen..';
                }
            
            case 'save_table_careplan':
                switch($request->oper){
                    case 'add':
                        return $this->add_careplan($request);
                    default:
                        return 'error happen..';
                }
            
            case 'save_table_othChart':
                switch($request->oper){
                    case 'add':
                        return $this->add_formOthChart($request);
                    case 'edit':
                        return $this->edit_formOthChart($request);
                    default:
                        return 'error happen..';
                }
            
            case 'FitChart_save':
                return $this->add_FitChart($request);
            
            case 'FitChart_edit':
                return $this->edit_FitChart($request);
            
            case 'FitChart_del':
                return $this->del_FitChart($request);
            
            case 'Circulation_save':
                return $this->add_Circulation($request);
            
            case 'Circulation_edit':
                return $this->edit_Circulation($request);
            
            case 'Circulation_del':
                return $this->del_Circulation($request);
            
            case 'SlidScale_save':
                return $this->add_SlidScale($request);
            
            case 'SlidScale_edit':
                return $this->edit_SlidScale($request);
            
            case 'SlidScale_del':
                return $this->del_SlidScale($request);
            
            case 'OthChart_save':
                return $this->add_OthChart($request);
            
            case 'OthChart_edit':
                return $this->edit_OthChart($request);
            
            case 'OthChart_del':
                return $this->del_OthChart($request);
            
            case 'get_table_progress':
                return $this->get_table_progress($request);
            
            case 'get_table_intake':
                return $this->get_table_intake($request);
            
            case 'get_table_drug':
                return $this->get_table_drug($request);
            
            case 'get_table_treatment':
                return $this->get_table_treatment($request);
            
            case 'get_table_careplan':
                return $this->get_table_careplan($request);
            
            case 'get_table_formFitChart':
                return $this->get_table_formFitChart($request);
            
            case 'get_table_formOthChart':
                return $this->get_table_formOthChart($request);
            
            case 'get_table_othChart':
                return $this->get_table_othChart($request);
            
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
    
    public function get_prescription(Request $request){
        
        $responce = new stdClass();
        
        $chargetrx_obj = DB::table('hisdb.chargetrx as trx')
                        ->select('trx.auditno', 'trx.mrn', 'trx.episno', 'trx.chgcode', 'trx.quantity', 'trx.uom', 'trx.doscode', 'trx.frequency', 'trx.ftxtdosage', 'trx.addinstruction', 'trx.drugindicator', 'cm.description', 'cm.uom', 'dos.dosedesc as doscode_desc', 'fre.freqdesc as frequency_desc', 'ins.description as addinstruction_desc', 'dru.description as drugindicator_desc')
                        ->leftjoin('hisdb.chgmast as cm', function($join) use ($request){
                            $join = $join->on('cm.chgcode', '=', 'trx.chgcode')
                                        ->on('cm.uom','=','trx.uom')
                                        ->where('cm.compcode','=',session('compcode'));
                        })
                        ->leftjoin('hisdb.dose as dos', function($join) use ($request){
                            $join = $join->on('dos.dosecode', '=', 'trx.doscode')
                                        ->where('dos.compcode','=',session('compcode'));
                        })
                        ->leftjoin('hisdb.freq as fre', function($join) use ($request){
                            $join = $join->on('fre.freqcode', '=', 'trx.frequency')
                                        ->where('fre.compcode','=',session('compcode'));
                        })
                        ->leftjoin('hisdb.instruction as ins', function($join) use ($request){
                            $join = $join->on('ins.inscode', '=', 'trx.addinstruction')
                                        ->where('ins.compcode','=',session('compcode'));
                        })
                        ->leftjoin('hisdb.drugindicator as dru', function($join) use ($request){
                            $join = $join->on('dru.drugindcode', '=', 'trx.drugindicator')
                                        ->where('dru.compcode','=',session('compcode'));
                        })
                        ->where('trx.mrn' ,'=', $request->mrn)
                        ->where('trx.episno' ,'=', $request->episno)
                        ->where('trx.compcode','=',session('compcode'))
                        ->where('trx.chggroup',$request->chggroup)
                        ->where('trx.recstatus','<>','DELETE')
                        ->orderBy('trx.adddate', 'desc');
        
        if($chargetrx_obj->exists()){
            $chargetrx_obj = $chargetrx_obj->get();
            
            $data = [];
            
            foreach ($chargetrx_obj as $key => $value) {
                $date['auditno'] = $value->auditno;
                $date['mrn'] = $value->mrn;
                $date['episno'] = $value->episno;
                $date['chgcode'] = $value->chgcode;
                $date['description'] = $value->description;
                $date['quantity'] = $value->quantity;
                $date['doscode'] = $value->doscode;
                $date['doscode_desc'] = $value->doscode_desc;
                $date['frequency'] = $value->frequency;
                $date['frequency_desc'] = $value->frequency_desc;
                $date['ftxtdosage'] = $value->ftxtdosage;
                $date['addinstruction'] = $value->addinstruction;
                $date['addinstruction_desc'] = $value->addinstruction_desc;
                $date['drugindicator'] = $value->drugindicator;
                $date['drugindicator_desc'] = $value->drugindicator_desc;
                
                array_push($data,$date);
            }
            
            $responce->data = $data;
        }else{
            $responce->data = [];
        }
        
        return json_encode($responce);
        
    }
    
    public function get_datetime_treatment(Request $request){
        
        $responce = new stdClass();
        
        $pattreatment_obj = DB::table('nursing.pattreatment')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno)
                            ->where('type','=',$request->type);
        
        if($pattreatment_obj->exists()){
            $pattreatment_obj = $pattreatment_obj->get();
            
            $data = [];
            
            foreach ($pattreatment_obj as $key => $value) {
                if(!empty($value->entereddate)){
                    $date['datetime'] =  Carbon::createFromFormat('Y-m-d', $value->entereddate)->format('d-m-Y').'<br>'.Carbon::createFromFormat('H:i:s', $value->enteredtime)->format('h:i A');
                }else{
                    $date['datetime'] =  '-';
                }
                $date['idno'] = $value->idno;
                $date['mrn'] = $value->mrn;
                $date['episno'] = $value->episno;
                // $date['enteredtime'] = $value->enteredtime;
                // if(!empty($value->enteredtime)){
                //     $date['enteredtime'] =  Carbon::createFromFormat('H:i:s', $value->enteredtime)->format('h:i A');
                // }else{
                //     $date['enteredtime'] =  '-';
                // }
                
                array_push($data,$date);
            }
            
            $responce->data = $data;
        }else{
            $responce->data = [];
        }
        
        return json_encode($responce);
        
    }
    
    public function get_datetime_careplan(Request $request){
        
        $responce = new stdClass();
        
        $nurscareplan_obj = DB::table('nursing.nurscareplan')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno);
        
        if($nurscareplan_obj->exists()){
            $nurscareplan_obj = $nurscareplan_obj->get();
            
            $data = [];
            
            foreach ($nurscareplan_obj as $key => $value) {
                $date['idno'] = $value->idno;
                $date['mrn'] = $value->mrn;
                $date['episno'] = $value->episno;
                if(!empty($value->entereddate)){
                    $date['entereddate'] =  Carbon::createFromFormat('Y-m-d H:i:s', $value->entereddate)->format('d-m-Y');
                }else{
                    $date['entereddate'] =  '-';
                }
                // $date['enteredtime'] = $value->enteredtime;
                if(!empty($value->enteredtime)){
                    $date['enteredtime'] =  Carbon::createFromFormat('H:i:s', $value->enteredtime)->format('h:i A');
                }else{
                    $date['enteredtime'] =  '-';
                }
                
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
    
    public function add_patMedic(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('hisdb.patmedication')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn,
                    'episno' => $request->episno,
                    'auditno' => $request->auditno,
                    'chgcode' => $request->chgcode,
                    'entereddate' => $request->entereddate,
                    'enteredtime' => $request->enteredtime,
                    'failure' => $request->failure,
                    'remarks' => $request->remarks,
                    'qty' => $request->qty,
                    'enteredby' => session('username'),
                    'adduser'  => session('username'),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur")
                ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
        
    }
    
    public function add_treatment(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.pattreatment')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn_nursNote,
                    'episno' => $request->episno_nursNote,
                    'type' => 'TREATMENT',
                    'remarks' => $request->treatment_remarks,
                    'entereddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'enteredtime'  => Carbon::now("Asia/Kuala_Lumpur"),
                    'adduser'  => session('username'),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString()
                ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function add_investigation(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.pattreatment')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn_nursNote,
                    'episno' => $request->episno_nursNote,
                    'type' => 'INVESTIGATION',
                    'remarks' => $request->investigation_remarks,
                    'entereddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'enteredtime'  => Carbon::now("Asia/Kuala_Lumpur"),
                    'adduser'  => session('username'),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString()
                ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function add_injection(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.pattreatment')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn_nursNote,
                    'episno' => $request->episno_nursNote,
                    'type' => 'INJECTION',
                    'remarks' => $request->injection_remarks,
                    'entereddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'enteredtime'  => Carbon::now("Asia/Kuala_Lumpur"),
                    'adduser'  => session('username'),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString()
                ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function add_careplan(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.nurscareplan')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn_nursNote,
                    'episno' => $request->episno_nursNote,
                    'problem' => $request->problem,
                    'problemdata' => $request->problemdata,
                    'problemintincome' => $request->problemintincome,
                    'nursintervention' => $request->nursintervention,
                    'nursevaluation' => $request->nursevaluation,
                    'entereddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'enteredtime'  => Carbon::now("Asia/Kuala_Lumpur"),
                    'adduser'  => session('username'),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'addtime'  => Carbon::now("Asia/Kuala_Lumpur"),
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastupdtime'  => Carbon::now("Asia/Kuala_Lumpur")
                ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function add_formOthChart(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            if(!empty($request->othChart1_tabtitle)){
                $tabtitle = $request->othChart1_tabtitle;
            }else if(!empty($request->othChart2_tabtitle)){
                $tabtitle = $request->othChart2_tabtitle;
            }
            
            if(!empty($request->othChart1_title)){
                $title = $request->othChart1_title;
            }else if(!empty($request->othChart2_title)){
                $title = $request->othChart2_title;
            }
            
            DB::table('nursing.nurs_othChart')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn_nursNote,
                    'episno' => $request->episno_nursNote,
                    'tabtitle' => $tabtitle,
                    'title' => $title,
                    'adduser'  => session('username'),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'addtime'  => Carbon::now("Asia/Kuala_Lumpur"),
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastupdtime'  => Carbon::now("Asia/Kuala_Lumpur"),
                    'computerid' => session('computerid'),
                ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function edit_formOthChart(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            if(!empty($request->othChart1_tabtitle)){
                $tabtitle = $request->othChart1_tabtitle;
            }else if(!empty($request->othChart2_tabtitle)){
                $tabtitle = $request->othChart2_tabtitle;
            }
            
            if(!empty($request->othChart1_title)){
                $title = $request->othChart1_title;
            }else if(!empty($request->othChart2_title)){
                $title = $request->othChart2_title;
            }
            
            DB::table('nursing.nurs_othChart')
                ->where('mrn','=',$request->mrn_nursNote)
                ->where('episno','=',$request->episno_nursNote)
                ->where('tabtitle','=',$tabtitle)
                ->update([
                    'title' => $title,
                    'upduser'  => session('username'),
                    'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastupdtime'  => Carbon::now("Asia/Kuala_Lumpur"),
                    'computerid' => session('computerid'),
                ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function add_FitChart(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.nurs_fitchart')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn,
                    'episno' => $request->episno,
                    'entereddate' => $request->entereddate,
                    'enteredtime' => $request->enteredtime,
                    'fit' => $request->fit,
                    'duration' => $request->duration,
                    'remarks' => $request->remarks,
                    'adduser'  => session('username'),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'addtime'  => Carbon::now("Asia/Kuala_Lumpur"),
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastupdtime'  => Carbon::now("Asia/Kuala_Lumpur"),
                    'computerid' => session('computerid'),
                ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function edit_FitChart(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.nurs_fitchart')
                ->where('idno','=',$request->idno)
                // ->where('mrn','=',$request->mrn)
                // ->where('episno','=',$request->episno)
                ->update([
                    'entereddate' => Carbon::parse($request->entereddate)->format('Y-m-d'),
                    'enteredtime' => $request->enteredtime,
                    'fit' => $request->fit,
                    'duration' => $request->duration,
                    'remarks' => $request->remarks,
                    'upduser'  => session('username'),
                    'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastupdtime'  => Carbon::now("Asia/Kuala_Lumpur"),
                    'computerid' => session('computerid'),
                ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function del_FitChart(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.nurs_fitchart')
                ->where('compcode','=',session('compcode'))
                ->where('idno','=',$request->idno)
                ->delete();
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
        
    }
    
    public function add_Circulation(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.nurs_circulation')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn,
                    'episno' => $request->episno,
                    'entereddate' => $request->entereddate,
                    'enteredtime' => $request->enteredtime,
                    'capillary' => $request->capillary,
                    'skintemp' => $request->skintemp,
                    'pulse' => $request->pulse,
                    'movement' => $request->movement,
                    'sensation' => $request->sensation,
                    'oedema' => $request->oedema,
                    'adduser'  => session('username'),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'addtime'  => Carbon::now("Asia/Kuala_Lumpur"),
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastupdtime'  => Carbon::now("Asia/Kuala_Lumpur"),
                    'computerid' => session('computerid'),
                ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function edit_Circulation(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.nurs_circulation')
                ->where('idno','=',$request->idno)
                // ->where('mrn','=',$request->mrn)
                // ->where('episno','=',$request->episno)
                ->update([
                    'entereddate' => Carbon::parse($request->entereddate)->format('Y-m-d'),
                    'enteredtime' => $request->enteredtime,
                    'capillary' => $request->capillary,
                    'skintemp' => $request->skintemp,
                    'pulse' => $request->pulse,
                    'movement' => $request->movement,
                    'sensation' => $request->sensation,
                    'oedema' => $request->oedema,
                    'upduser'  => session('username'),
                    'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastupdtime'  => Carbon::now("Asia/Kuala_Lumpur"),
                    'computerid' => session('computerid'),
                ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function del_Circulation(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.nurs_circulation')
                ->where('compcode','=',session('compcode'))
                ->where('idno','=',$request->idno)
                ->delete();
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
        
    }
    
    public function add_SlidScale(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.nurs_slidscale')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn,
                    'episno' => $request->episno,
                    'entereddate' => $request->entereddate,
                    'enteredtime' => $request->enteredtime,
                    'dextrostix' => $request->dextrostix,
                    'remarks' => $request->remarks,
                    'adduser'  => session('username'),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'addtime'  => Carbon::now("Asia/Kuala_Lumpur"),
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastupdtime'  => Carbon::now("Asia/Kuala_Lumpur"),
                    'computerid' => session('computerid'),
                ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function edit_SlidScale(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.nurs_slidscale')
                ->where('idno','=',$request->idno)
                // ->where('mrn','=',$request->mrn)
                // ->where('episno','=',$request->episno)
                ->update([
                    'entereddate' => Carbon::parse($request->entereddate)->format('Y-m-d'),
                    'enteredtime' => $request->enteredtime,
                    'dextrostix' => $request->dextrostix,
                    'remarks' => $request->remarks,
                    'upduser'  => session('username'),
                    'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastupdtime'  => Carbon::now("Asia/Kuala_Lumpur"),
                    'computerid' => session('computerid'),
                ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function del_SlidScale(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.nurs_slidscale')
                ->where('compcode','=',session('compcode'))
                ->where('idno','=',$request->idno)
                ->delete();
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
        
    }
    
    public function add_OthChart(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $nurs_othChart = DB::table('nursing.nurs_othChart')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno)
                            ->where('tabtitle','=',$request->tabtitle);
            
            if($nurs_othChart->exists()){
                DB::table('nursing.nurs_othChart')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'tabtitle' => $request->tabtitle,
                        'title' => $request->title,
                        'entereddate' => $request->entereddate,
                        'enteredtime' => $request->enteredtime,
                        'remarks' => $request->remarks,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'addtime'  => Carbon::now("Asia/Kuala_Lumpur"),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastupdtime'  => Carbon::now("Asia/Kuala_Lumpur"),
                        'computerid' => session('computerid'),
                    ]);
            }else{
                throw new \Exception('Please enter chart title first.', 500);
            }
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
        
    }
    
    public function edit_OthChart(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.nurs_othChart')
                ->where('idno','=',$request->idno)
                // ->where('mrn','=',$request->mrn)
                // ->where('episno','=',$request->episno)
                // ->where('title','=',$request->title)
                ->update([
                    'entereddate' => Carbon::parse($request->entereddate)->format('Y-m-d'),
                    'enteredtime' => $request->enteredtime,
                    'remarks' => $request->remarks,
                    'upduser'  => session('username'),
                    'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastupdtime'  => Carbon::now("Asia/Kuala_Lumpur"),
                    'computerid' => session('computerid'),
                ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function del_OthChart(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.nurs_othChart')
                ->where('compcode','=',session('compcode'))
                ->where('idno','=',$request->idno)
                ->delete();
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
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
    
    public function get_table_drug(Request $request){
        
        $patmedication_obj = DB::table('hisdb.patmedication')
                            ->where('compcode','=',session('compcode'))
                            // ->where('idno','=',$request->idno);
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno)
                            ->where('auditno','=',$request->auditno)
                            ->where('chgcode','=',$request->chgcode);
        
        $responce = new stdClass();
        
        if($patmedication_obj->exists()){
            $total_qty = $patmedication_obj->sum('qty');
            $responce->total_qty = $total_qty;
            
            $patmedication_obj = $patmedication_obj->first();
            $responce->patmedication = $patmedication_obj;
        }
        
        return json_encode($responce);
        
    }
    
    public function get_table_treatment(Request $request){
        
        $treatment_obj = DB::table('nursing.pattreatment')
                        ->select('mrn', 'episno', 'type', 'remarks as treatment_remarks', 'adduser as treatment_adduser')
                        ->where('compcode','=',session('compcode'))
                        ->where('idno','=',$request->idno);
                        // ->where('mrn','=',$request->mrn)
                        // ->where('episno','=',$request->episno)
                        // ->where('type','=','TREATMENT');
        
        $investigation_obj = DB::table('nursing.pattreatment')
                        ->select('mrn', 'episno', 'type', 'remarks as investigation_remarks', 'adduser as investigation_adduser')
                        ->where('compcode','=',session('compcode'))
                        ->where('idno','=',$request->idno);
                        // ->where('mrn','=',$request->mrn)
                        // ->where('episno','=',$request->episno)
                        // ->where('type','=','INVESTIGATION');
        
        $injection_obj = DB::table('nursing.pattreatment')
                        ->select('mrn', 'episno', 'type', 'remarks as injection_remarks', 'adduser as injection_adduser')
                        ->where('compcode','=',session('compcode'))
                        ->where('idno','=',$request->idno);
                        // ->where('mrn','=',$request->mrn)
                        // ->where('episno','=',$request->episno)
                        // ->where('type','=','INJECTION');
        
        $responce = new stdClass();
        
        if($treatment_obj->exists()){
            $treatment_obj = $treatment_obj->first();
            $responce->treatment = $treatment_obj;
        }
        
        if($investigation_obj->exists()){
            $investigation_obj = $investigation_obj->first();
            $responce->investigation = $investigation_obj;
        }
        
        if($injection_obj->exists()){
            $injection_obj = $injection_obj->first();
            $responce->injection = $injection_obj;
        }
        
        return json_encode($responce);
        
    }
    
    public function get_table_careplan(Request $request){
        
        $nurscareplan_obj = DB::table('nursing.nurscareplan')
                            ->where('compcode','=',session('compcode'))
                            ->where('idno','=',$request->idno);
                            // ->where('mrn','=',$request->mrn)
                            // ->where('episno','=',$request->episno);
        
        $responce = new stdClass();
        
        if($nurscareplan_obj->exists()){
            $nurscareplan_obj = $nurscareplan_obj->first();
            $responce->nurscareplan = $nurscareplan_obj;
        }
        
        return json_encode($responce);
        
    }
    
    public function get_table_formFitChart(Request $request){
        
        $nursassessment_obj = DB::table('nursing.nursassessment')
                            ->select('diagnosis')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno);
        
        $responce = new stdClass();
        
        if($nursassessment_obj->exists()){
            $nursassessment_obj = $nursassessment_obj->first();
            
            $diagnosis_obj = $nursassessment_obj->diagnosis;
            $responce->diagnosis = $diagnosis_obj;
        }
        
        return json_encode($responce);
        
    }
    
    public function get_table_formOthChart(Request $request){
        
        $nurs_othChart_obj = DB::table('nursing.nurs_othChart')
                            ->select('title')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno)
                            ->where('tabtitle','=',$request->tabtitle);
        
        $nursassessment_obj = DB::table('nursing.nursassessment')
                            ->select('diagnosis')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno);
        
        $responce = new stdClass();
        
        if($nurs_othChart_obj->exists()){
            $nurs_othChart_obj = $nurs_othChart_obj->first();
            
            $title_obj = $nurs_othChart_obj->title;
            $responce->title = $title_obj;
            // $responce->nurs_othChart = $nurs_othChart_obj;
        }
        
        if($nursassessment_obj->exists()){
            $nursassessment_obj = $nursassessment_obj->first();
            
            $diagnosis_obj = $nursassessment_obj->diagnosis;
            $responce->diagnosis = $diagnosis_obj;
        }
        
        return json_encode($responce);
        
    }
    
    public function get_table_othChart(Request $request){
        
        $table = DB::table('nursing.nurs_othChart')
                ->where('compcode','=',session('compcode'))
                ->where('mrn','=',$request->mrn)
                ->where('episno','=',$request->episno)
                ->where('tabtitle','=',$request->tabtitle)
                ->orderBy('idno','desc');
        
        //////////paginate//////////
        $paginate = $table->paginate($request->rows);
        
        foreach($paginate->items() as $key => $value){
            if($value->entereddate !== null && $value->enteredtime !== null && $value->remarks !== null){
                
            }
        }
        
        $responce = new stdClass();
        $responce->page = $paginate->currentPage();
        $responce->total = $paginate->lastPage();
        $responce->records = $paginate->total();
        $responce->rows = $paginate->items();
        $responce->sql = $table->toSql();
        $responce->sql_bind = $table->getBindings();
        
        return json_encode($responce);
        
    }
    
}