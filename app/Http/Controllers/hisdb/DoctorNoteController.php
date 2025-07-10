<?php

namespace App\Http\Controllers\hisdb;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;
use PDF;

class DoctorNoteController extends defaultController
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
        return view('hisdb.doctornote.doctornote');
    }
    
    public function bpgraph(Request $request)
    {
        $pat = DB::table('hisdb.pat_mast')
                ->where('compcode',session('compcode'))
                ->where('MRN',$request->mrn)
                ->first();
        
        return view('hisdb.doctornote.doctornote_bpgraph',compact('pat'));
    }
    
    public function table(Request $request)
    {
        switch($request->action){
            case 'get_table_date_curr':     // for current
                return $this->get_table_date_curr($request);
            case 'get_table_date_past':     // for past history
                return $this->get_table_date_past($request);
            case 'get_table_doctornote':
                return $this->get_table_doctornote($request);
            case 'get_table_otbook':
                return $this->get_table_otbook($request);
            case 'get_table_radClinic':
                return $this->get_table_radClinic($request);
            case 'get_table_mri':
                return $this->get_table_mri($request);
            case 'get_table_physio':
                return $this->get_table_physio($request);
            case 'get_table_dressing':
                return $this->get_table_dressing($request);
            case 'get_table_preContrast':
                return $this->get_table_preContrast($request);
            case 'get_table_consentForm':
                return $this->get_table_consentForm($request);
            case 'dialog_icd':
                return $this->dialog_icd($request);
            case 'get_bp_graph':
                return $this->get_bp_graph($request);
            
            // transaction stuff
            case 'get_transaction_table':
                return $this->get_transaction_table($request);
            case 'get_chgcode':
                return $this->get_chgcode($request);
            case 'get_drugindcode':
                return $this->get_drugindcode($request);
            case 'get_freqcode':
                return $this->get_freqcode($request);
            case 'get_dosecode':
                return $this->get_dosecode($request);
            case 'get_inscode':
                return $this->get_inscode($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function form(Request $request)
    {
        DB::enableQueryLog();
        
        switch($request->action){
            case 'save_table_doctornote':
                switch($request->oper){
                    case 'add':
                        return $this->add($request);
                    case 'edit':
                        return $this->edit($request);
                    default:
                        return 'error happen..';
                }
            
            case 'get_table_doctornote':
                return $this->get_table_doctornote($request);
            
            case 'doctornote_save':
                return $this->add_notes($request);
            
            case 'doctornote_transaction_save':
                return $this->doctornote_transaction_save($request);
            
            case 'save_refLetter':
                switch($request->oper){
                    case 'add':
                        return $this->add_refLetter($request);
                    case 'edit':
                        return $this->edit_refLetter($request);
                    default:
                        return 'error happen..';
                }
            
            case 'save_otbook':
                switch($request->oper){
                    case 'add':
                        return $this->add_otbook($request);
                    case 'edit':
                        return $this->edit_otbook($request);
                    default:
                        return 'error happen..';
                }
            
            case 'save_radClinic':
                switch($request->oper){
                    case 'add':
                        return $this->add_radClinic($request);
                    case 'edit':
                        return $this->edit_radClinic($request);
                    default:
                        return 'error happen..';
                }
            
            case 'save_mri':
                switch($request->oper){
                    case 'add':
                        return $this->add_mri($request);
                    case 'edit':
                        return $this->edit_mri($request);
                    default:
                        return 'error happen..';
                }
            
            case 'accept_mri':
                return $this->accept_mri($request);
            
            case 'save_physio':
                switch($request->oper){
                    case 'add':
                        return $this->add_physio($request);
                    case 'edit':
                        return $this->edit_physio($request);
                    default:
                        return 'error happen..';
                }
            
            case 'save_dressing':
                switch($request->oper){
                    case 'add':
                        return $this->add_dressing($request);
                    case 'edit':
                        return $this->edit_dressing($request);
                    default:
                        return 'error happen..';
                }

            case 'save_preContrast':
                switch($request->oper){
                    case 'add':
                        return $this->add_preContrast($request);
                    case 'edit':
                        return $this->edit_preContrast($request);
                    default:
                        return 'error happen..';
                }

            case 'save_consentForm':
                switch($request->oper){
                    case 'add':
                        return $this->add_consentForm($request);
                    case 'edit':
                        return $this->edit_consentForm($request);
                    default:
                        return 'error happen..';
                }
            
            case 'get_table_refLetter':
                return $this->get_table_refLetter($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function add(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('hisdb.episode')
                ->where('mrn','=',$request->mrn_doctorNote)
                ->where('episno','=',$request->episno_doctorNote)
                ->where('compcode','=',session('compcode'))
                ->update([
                    // 'remarks' => $request->remarks,
                    'diagfinal' => $request->diagfinal,
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                ]);
            
            // DB::table('hisdb.patexam')
            //     ->insert([
            //         'compcode' => session('compcode'),
            //         'mrn' => $request->mrn_doctorNote,
            //         'episno' => $request->episno_doctorNote,
            //         'examination' => $request->examination,
            //         'adduser'  => session('username'),
            //         'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
            //         'lastuser'  => session('username'),
            //         'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
            //         'recorddate' => Carbon::now("Asia/Kuala_Lumpur"),
            //         // 'recordtime' => Carbon::now("Asia/Kuala_Lumpur"),
            //         'recordtime' => $request->recordtime,
            //     ]);
            
            $pathistory = DB::table('hisdb.pathistory')
                        ->where('mrn','=',$request->mrn_doctorNote)
                        // ->where('recorddate','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate_doctorNote))
                        ->where('compcode','=',session('compcode'));
            
            $doctorcode_obj = DB::table('hisdb.doctor')
                            ->select('doctorcode')
                            ->where('compcode','=',session('compcode'))
                            ->where('loginid','=',session('username'));
            
            $doctorcode = null;
            if($doctorcode_obj->exists()){
                $doctorcode = $doctorcode_obj->first()->doctorcode;
            }
            
            DB::table('hisdb.pathealth')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn_doctorNote,
                    'episno' => $request->episno_doctorNote,
                    'complain' => $request->complain,
                    'clinicnote' => $request->clinicnote,
                    'drugh' => $request->drugh,
                    'allergyh' => $request->allergyh,
                    'genappear' => $request->genappear,
                    'speech' => $request->speech,
                    'moodaffect' => $request->moodaffect,
                    'perception' => $request->perception,
                    'thinking' => $request->thinking,
                    'cognitivefunc' => $request->cognitivefunc,
                    'followuptime' => $request->followuptime,
                    'followupdate' => $request->followupdate,
                    'aetiology' => $request->aetiology,
                    'investigate' => $request->investigate,
                    'treatment' => $request->treatment,
                    'prognosis' => $request->prognosis,
                    'plan_' => $request->plan_,
                    'bp_sys1' => $request->bp_sys1,
                    'bp_dias2' => $request->bp_dias2,
                    'spo2' => $request->spo2,
                    'pulse' => $request->pulse,
                    'gxt' => $request->gxt,
                    'temperature' => $request->temperature,
                    'height' => $request->height,
                    'weight' => $request->weight,
                    'respiration' => $request->respiration,
                    // 'pain_score' => $request->pain_score,
                    'doctorcode'  => $doctorcode,
                    'adduser'  => session('username'),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'recorddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    // 'recordtime' => Carbon::now("Asia/Kuala_Lumpur"),
                    'recordtime' => $request->recordtime,
                ]);
            
            // DB::table('hisdb.pathistory')
            //     ->insert([
            //         'compcode' => session('compcode'),
            //         'mrn' => $request->mrn_doctorNote,
            //         'pmh' => $request->pmh,
            //         'drugh' => $request->drugh,
            //         'allergyh' => $request->allergyh,
            //         'socialh' => $request->socialh,
            //         'fmh' => $request->fmh,
            //         'adduser'  => session('username'),
            //         'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
            //         'lastuser'  => session('username'),
            //         'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
            //         'recorddate' => Carbon::now("Asia/Kuala_Lumpur"),
            //         'recordtime' => Carbon::now("Asia/Kuala_Lumpur"),
            //     ]);
            
            if($pathistory->exists()){
                $pathistory
                    ->update([
                        'psychiatryh' => $request->psychiatryh,
                        'pmh' => $request->pmh,
                        'fmh' => $request->fmh,
                        'personalh' => $request->personalh,
                        'drugh' => $request->drugh,
                        'allergyh' => $request->allergyh,
                        'socialh' => $request->socialh,
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }else{
                DB::table('hisdb.pathistory')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_doctorNote,
                        'psychiatryh' => $request->psychiatryh,
                        'pmh' => $request->pmh,
                        'fmh' => $request->fmh,
                        'personalh' => $request->personalh,
                        'drugh' => $request->drugh,
                        'allergyh' => $request->allergyh,
                        'socialh' => $request->socialh,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'recorddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        // 'recordtime' => Carbon::now("Asia/Kuala_Lumpur"),
                        'recordtime' => $request->recordtime,
                    ]);
            }
            
            DB::table('hisdb.episdiag')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn_doctorNote,
                    'episno' => $request->episno_doctorNote,
                    'icdcode' => $request->icdcode,
                    'adduser'  => session('username'),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                ]);
            
            DB::table('hisdb.pat_radiology')
                ->where('mrn','=',$request->mrn_doctorNote)
                ->where('episno','=',$request->episno_doctorNote)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'weight' => $request->weight,
                    'upduser'  => session('username'),
                    'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastuser' => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'computerid' => session('computerid'),
                ]);
            
            DB::table('hisdb.pat_mri')
                ->where('mrn','=',$request->mrn_doctorNote)
                ->where('episno','=',$request->episno_doctorNote)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'weight' => $request->weight,
                    'upduser'  => session('username'),
                    'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastuser' => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'computerid' => session('computerid'),
                ]);
            
            DB::table('nursing.admhandover')
                ->where('mrn','=',$request->mrn_doctorNote)
                ->where('episno','=',$request->episno_doctorNote)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'weights' => $request->weight,
                    'diagnosis' => $request->diagfinal,
                    'upduser'  => session('username'),
                    'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastuser' => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'computerid' => session('computerid'),
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
                ->where('mrn','=',$request->mrn_doctorNote)
                ->where('episno','=',$request->episno_doctorNote)
                ->where('compcode','=',session('compcode'))
                ->update([
                    // 'remarks' => $request->remarks,
                    'diagfinal' => $request->diagfinal,
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                ]);
            
            // DB::table('hisdb.pathealthadd')
            //     ->insert([
            //         'compcode' => session('compcode'),
            //         'mrn' => $request->mrn_doctorNote,
            //         'episno' => $request->episno_doctorNote,
            //         'additionalnote' => $request->additionalnote,
            //     ]);
            
            // $patexam = DB::table('hisdb.patexam')
            //             ->where('mrn','=',$request->mrn_doctorNote)
            //             ->where('episno','=',$request->episno_doctorNote)
            //             ->where('recorddate','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate_doctorNote)->format('Y-m-d'))
            //             ->where('recordtime','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate_doctorNote)->format('H:i:s'))
            //             ->where('compcode','=',session('compcode'));
            
            $pathealth = DB::table('hisdb.pathealth')
                        ->where('mrn','=',$request->mrn_doctorNote)
                        ->where('episno','=',$request->episno_doctorNote)
                        ->where('recorddate','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate_doctorNote)->format('Y-m-d'))
                        ->where('recordtime','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate_doctorNote)->format('H:i:s'))
                        ->where('compcode','=',session('compcode'));
            
            $pathistory = DB::table('hisdb.pathistory')
                        ->where('mrn','=',$request->mrn_doctorNote)
                        // ->where('recorddate','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate_doctorNote))
                        ->where('compcode','=',session('compcode'));
            
            $episdiag = DB::table('hisdb.episdiag')
                        ->where('mrn','=',$request->mrn_doctorNote)
                        ->where('episno','=',$request->episno_doctorNote)
                        ->where('compcode','=',session('compcode'));
            
            // if($patexam->exists()){
            //     $patexam->update([
            //             'examination' => $request->examination,
            //             'lastuser'  => session('username'),
            //             'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
            //     ]);
            // }else{
            //     DB::table('hisdb.patexam')
            //         ->insert([
            //             'compcode' => session('compcode'),
            //             'mrn' => $request->mrn_doctorNote,
            //             'episno' => $request->episno_doctorNote,
            //             'examination' => $request->examination,
            //             'adduser'  => session('username'),
            //             'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
            //             'lastuser'  => session('username'),
            //             'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
            //             'recorddate' => Carbon::now("Asia/Kuala_Lumpur"),
            //             // 'recordtime' => Carbon::now("Asia/Kuala_Lumpur"),
            //             'recordtime' => $request->recordtime,
            //         ]);
            // }
            
            $doctorcode_obj = DB::table('hisdb.doctor')
                            ->select('doctorcode')
                            ->where('compcode','=',session('compcode'))
                            ->where('loginid','=',session('username'));
            
            $doctorcode = null;
            if($doctorcode_obj->exists()){
                $doctorcode = $doctorcode_obj->first()->doctorcode;
            }
            
            if($pathealth->exists()){
                $pathealth
                    ->update([
                        'complain' => $request->complain,
                        'clinicnote' => $request->clinicnote,
                        'drugh' => $request->drugh,
                        'allergyh' => $request->allergyh,
                        'genappear' => $request->genappear,
                        'speech' => $request->speech,
                        'moodaffect' => $request->moodaffect,
                        'perception' => $request->perception,
                        'thinking' => $request->thinking,
                        'cognitivefunc' => $request->cognitivefunc,
                        'followuptime' => $request->followuptime,
                        'followupdate' => $request->followupdate,
                        'aetiology' => $request->aetiology,
                        'investigate' => $request->investigate,
                        'treatment' => $request->treatment,
                        'prognosis' => $request->prognosis,
                        'plan_' => $request->plan_,
                        'bp_sys1' => $request->bp_sys1,
                        'bp_dias2' => $request->bp_dias2,
                        'spo2' => $request->spo2,
                        'pulse' => $request->pulse,
                        'gxt' => $request->gxt,
                        'temperature' => $request->temperature,
                        'height' => $request->height,
                        'weight' => $request->weight,
                        'respiration' => $request->respiration,
                        // 'pain_score' => $request->pain_score,
                        // 'doctorcode'  => $doctorcode,
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }else{
                DB::table('hisdb.pathealth')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_doctorNote,
                        'episno' => $request->episno_doctorNote,
                        'complain' => $request->complain,
                        'clinicnote' => $request->clinicnote,
                        'drugh' => $request->drugh,
                        'allergyh' => $request->allergyh,
                        'genappear' => $request->genappear,
                        'speech' => $request->speech,
                        'moodaffect' => $request->moodaffect,
                        'perception' => $request->perception,
                        'thinking' => $request->thinking,
                        'cognitivefunc' => $request->cognitivefunc,
                        'followuptime' => $request->followuptime,
                        'followupdate' => $request->followupdate,
                        'aetiology' => $request->aetiology,
                        'investigate' => $request->investigate,
                        'treatment' => $request->treatment,
                        'prognosis' => $request->prognosis,
                        'plan_' => $request->plan_,
                        'bp_sys1' => $request->bp_sys1,
                        'bp_dias2' => $request->bp_dias2,
                        'spo2' => $request->spo2,
                        'pulse' => $request->pulse,
                        'gxt' => $request->gxt,
                        'temperature' => $request->temperature,
                        'height' => $request->height,
                        'weight' => $request->weight,
                        'respiration' => $request->respiration,
                        // 'pain_score' => $request->pain_score,
                        'doctorcode'  => $doctorcode,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'recorddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        // 'recordtime' => Carbon::now("Asia/Kuala_Lumpur"),
                        'recordtime' => $request->recordtime,
                    ]);
            }
            
            if($pathistory->exists()){
                $pathistory
                    ->update([
                        'psychiatryh' => $request->psychiatryh,
                        'pmh' => $request->pmh,
                        'fmh' => $request->fmh,
                        'personalh' => $request->personalh,
                        'drugh' => $request->drugh,
                        'allergyh' => $request->allergyh,
                        'socialh' => $request->socialh,
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }else{
                DB::table('hisdb.pathistory')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_doctorNote,
                        'psychiatryh' => $request->psychiatryh,
                        'pmh' => $request->pmh,
                        'fmh' => $request->fmh,
                        'personalh' => $request->personalh,
                        'drugh' => $request->drugh,
                        'allergyh' => $request->allergyh,
                        'socialh' => $request->socialh,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'recorddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        // 'recordtime' => Carbon::now("Asia/Kuala_Lumpur"),
                        'recordtime' => $request->recordtime,
                    ]);
            }
            
            if($episdiag->exists()){
                $episdiag
                    ->update([
                        'icdcode' => $request->icdcode,
                    ]);
            }else{
                DB::table('hisdb.episdiag')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_doctorNote,
                        'episno' => $request->episno_doctorNote,
                        'icdcode' => $request->icdcode,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }
            
            DB::table('hisdb.pat_radiology')
                ->where('mrn','=',$request->mrn_doctorNote)
                ->where('episno','=',$request->episno_doctorNote)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'weight' => $request->weight,
                    'upduser'  => session('username'),
                    'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastuser' => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'computerid' => session('computerid'),
                ]);
            
            DB::table('hisdb.pat_mri')
                ->where('mrn','=',$request->mrn_doctorNote)
                ->where('episno','=',$request->episno_doctorNote)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'weight' => $request->weight,
                    'upduser'  => session('username'),
                    'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastuser' => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'computerid' => session('computerid'),
                ]);
            
            DB::table('nursing.admhandover')
                ->where('mrn','=',$request->mrn_doctorNote)
                ->where('episno','=',$request->episno_doctorNote)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'weights' => $request->weight,
                    'diagnosis' => $request->diagfinal,
                    'upduser'  => session('username'),
                    'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastuser' => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'computerid' => session('computerid'),
                ]);
            
            $queries = DB::getQueryLog();
            // dump($queries);
            
            DB::commit();
            
            $responce = new stdClass();
            $responce->mrn = $request->mrn_doctorNote;
            $responce->episno = $request->episno_doctorNote;
            $responce->recorddate = $request->recorddate_doctorNote;
            
            return json_encode($responce);
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function get_transaction_table($request){
        
        if($request->rows == null){
            $request->rows = 100;
        }
        
        $table_chgtrx = DB::table('hisdb.chargetrx as trx')
                        ->select('trx.auditno','trx.compcode','trx.idno','trx.mrn','trx.episno','trx.epistype','trx.trxtype','trx.docref','trx.trxdate','trx.chgcode','trx.billcode','trx.costcd','trx.revcd','trx.mmacode','trx.billflag','trx.billdate','trx.billtype','trx.doctorcode','doc.doctorname','trx.chg_class','trx.unitprce','trx.quantity','trx.amount','trx.trxtime','trx.chggroup','trx.qstat','trx.dracccode','trx.cracccode','trx.arprocess','trx.taxamount','trx.billno','trx.invno','trx.uom','trx.uom_recv','trx.billtime','trx.invgroup','trx.reqdept as deptcode','trx.issdept','trx.invcode','trx.resulttype','trx.resultstatus','trx.inventory','trx.updinv','trx.invbatch','trx.doscode','trx.duration','trx.instruction','trx.discamt','trx.disccode','trx.pkgcode','trx.remarks','trx.frequency','trx.ftxtdosage','trx.addinstruction','trx.qtyorder','trx.ipqueueno','trx.itemseqno','trx.doseqty','trx.freqqty','trx.isudept','trx.qtyissue','trx.durationcode','trx.reqdoctor','trx.unit','trx.agreementid','trx.chgtype','trx.adduser','trx.adddate','trx.lastuser','trx.lastupdate','trx.daytaken','trx.qtydispense','trx.takehomeentry','trx.latechargesentry','trx.taxcode','trx.recstatus','trx.drugindicator','trx.id','trx.patmedication','trx.mmaprice','pt.avgcost as cost_price','pt.avgcost as cost_price','dos.dosedesc as doscode_desc','fre.freqdesc as frequency_desc','ins.description as addinstruction_desc','dru.description as drugindicator_desc','cm.brandname','cm.description')
                        ->where('trx.mrn' ,'=', $request->mrn)
                        ->where('trx.episno' ,'=', $request->episno)
                        ->where('trx.compcode','=',session('compcode'))
                        ->where('trx.taxflag',0)
                        ->where('trx.discflag',0)
                        ->where('trx.recstatus','<>','DELETE')
                        ->orderBy('trx.adddate', 'desc');
        
        $table_chgtrx = $table_chgtrx->leftjoin('material.product as pt', function ($join) use ($request){
                            $join = $join->where('pt.compcode', '=', session('compcode'));
                            $join = $join->on('pt.itemcode', '=', 'trx.chgcode');
                            $join = $join->on('pt.uomcode', '=', 'trx.uom_recv');
                            $join = $join->where('pt.unit', '=', session('unit'));
                        });
        
        $table_chgtrx = $table_chgtrx->leftjoin('hisdb.chgmast as cm', function ($join) use ($request){
                            $join = $join->where('cm.compcode', '=', session('compcode'));
                            $join = $join->on('cm.chgcode', '=', 'trx.chgcode');
                            $join = $join->on('cm.uom', '=', 'trx.uom');
                            $join = $join->where('cm.unit', '=', session('unit'));
                        });
        
        $table_chgtrx = $table_chgtrx->leftjoin('hisdb.dose as dos', function ($join) use ($request){
                            $join = $join->where('dos.compcode', '=', session('compcode'));
                            $join = $join->on('dos.dosecode', '=', 'trx.doscode');
                        });
        
        $table_chgtrx = $table_chgtrx->leftjoin('hisdb.freq as fre', function ($join) use ($request){
                            $join = $join->where('fre.compcode', '=', session('compcode'));
                            $join = $join->on('fre.freqcode', '=', 'trx.frequency');
                        });
        
        $table_chgtrx = $table_chgtrx->leftjoin('hisdb.instruction as ins', function ($join) use ($request){
                            $join = $join->where('ins.compcode', '=', session('compcode'));
                            $join = $join->on('ins.inscode', '=', 'trx.addinstruction');
                        });
        
        $table_chgtrx = $table_chgtrx->leftjoin('hisdb.drugindicator as dru', function ($join) use ($request){
                            $join = $join->where('dru.compcode', '=', session('compcode'));
                            $join = $join->on('dru.drugindcode', '=', 'trx.drugindicator');
                        });
        
        $table_chgtrx = $table_chgtrx->leftjoin('hisdb.doctor as doc', function ($join) use ($request){
                            $join = $join->where('doc.compcode', '=', session('compcode'));
                            $join = $join->on('doc.doctorcode', '=', 'trx.doctorcode');
                        });
        
        //////////paginate//////////
        $paginate = $table_chgtrx->paginate($request->rows);
        
        $responce = new stdClass();
        $responce->page = $paginate->currentPage();
        $responce->total = $paginate->lastPage();
        $responce->records = $paginate->total();
        $responce->rows = $paginate->items();
        $responce->sql = $table_chgtrx->toSql();
        $responce->sql_bind = $table_chgtrx->getBindings();
        $responce->sql_query = $this->getQueries($table_chgtrx);
        return json_encode($responce);
        
    }
    
    public function get_chgcode(Request $request){
        
        // $pharcode = DB::table('sysdb.sysparam')
        //             ->where('compcode','=',session('compcode'))
        //             ->where('source','=','OE')
        //             ->where('trantype','=','PHAR')
        //             ->first();
        
        $data = DB::table('hisdb.chgmast')
                ->where('compcode','=',session('compcode'))
                // ->where('chggroup','=',$pharcode->pvalue1)
                ->where('active','=',1)
                ->select('chgcode as code','description as description');
        
        if(!empty($request->search)){
            $data = $data->where('description','LIKE','%'.$request->search.'%')->first();
        }else{
            $data = $data->get();
        }
        
        $responce = new stdClass();
        $responce->data = $data;
        return json_encode($responce);
        
    }
    
    public function get_drugindcode(Request $request){
        
        $data = DB::table('hisdb.drugindicator')
                ->select('drugindcode as code','description as description');
        
        if(!empty($request->search)){
            $data = $data->where('description','LIKE','%'.$request->search.'%')->first();
        }else{
            $data = $data->get();
        }
        
        $responce = new stdClass();
        $responce->data = $data;
        return json_encode($responce);
        
    }
    
    public function get_freqcode(Request $request){
        
        $data = DB::table('hisdb.freq')
                ->select('freqcode as code','freqdesc as description')
                ->where('compcode','=',session('compcode'));
        
        if(!empty($request->search)){
            $data = $data->where('freqdesc','LIKE','%'.$request->search.'%')->first();
        }else{
            $data = $data->get();
        }
        
        $responce = new stdClass();
        $responce->data = $data;
        return json_encode($responce);
        
    }
    
    public function get_dosecode(Request $request){
        
        $data = DB::table('hisdb.dose')
                ->select('dosecode as code','dosedesc as description')
                ->where('compcode','=',session('compcode'));
        
        if(!empty($request->search)){
            $data = $data->where('dosedesc','LIKE','%'.$request->search.'%')->first();
        }else{
            $data = $data->get();
        }
        
        $responce = new stdClass();
        $responce->data = $data;
        return json_encode($responce);
        
    }
    
    public function get_inscode(Request $request){
        
        $data = DB::table('hisdb.instruction')
                ->select('inscode as code','description as description')
                ->where('compcode','=',session('compcode'));
        
        if(!empty($request->search)){
            $data = $data->where('description','LIKE','%'.$request->search.'%')->first();
        }else{
            $data = $data->get();
        }
        
        $responce = new stdClass();
        $responce->data = $data;
        return json_encode($responce);
        
    }
    
    public function get_table_date_curr(Request $request){
        
        $responce = new stdClass();
        
        $pathealth_obj = DB::table('hisdb.pathealth')
                        ->select('mrn','episno','recordtime','adddate','adduser')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno);
        
        if(!$pathealth_obj->exists()){
            $responce->data = [];
            return json_encode($responce);
        }
        
        $episode_obj = DB::table('hisdb.episode as e')
                    ->select('e.mrn','e.episno','p.recordtime','p.adddate','p.adduser','e.admdoctor','d.doctorname')
                    ->leftJoin('hisdb.pathealth as p', function ($join) use ($request){
                        $join = $join->on('p.mrn', '=', 'e.mrn');
                        $join = $join->on('p.episno', '=', 'e.episno');
                        $join = $join->on('p.compcode', '=', 'e.compcode');
                    })->leftJoin('hisdb.doctor as d', function ($join) use ($request){
                        $join = $join->on('d.doctorcode', '=', 'e.admdoctor');
                        $join = $join->on('d.compcode', '=', 'e.compcode');
                    })
                    ->where('e.compcode','=',session('compcode'))
                    ->where('e.mrn','=',$request->mrn)
                    ->where('e.episno','=',$request->episno)
                    ->orderBy('p.idno','desc');
        
        if($episode_obj->exists()){
            $episode_obj = $episode_obj->get();
            
            $data = [];
            
            foreach($episode_obj as $key => $value){
                if(!empty($value->adddate)){
                    $date['date'] =  Carbon::createFromFormat('Y-m-d', $value->adddate)->format('d-m-Y').' '.$value->recordtime;
                }else{
                    $date['date'] =  '-';
                }
                $date['mrn'] = $value->mrn;
                $date['episno'] = $value->episno;
                $date['adduser'] = $value->adduser;
                $date['doctorname'] = $value->doctorname;
                
                array_push($data,$date);
            }
            
            $responce->data = $data;
        }else{
            $responce->data = [];
        }
        
        return json_encode($responce);
        
    }
    
    public function get_table_date_past(Request $request){
        
        $responce = new stdClass();
        
        $episode_obj = DB::table('hisdb.episode as e')
                        ->select('e.mrn','e.episno','p.recordtime','p.recorddate','p.adduser','e.admdoctor','d.doctorname')
                        ->join('hisdb.pathealth as p', function ($join) use ($request){
                            $join = $join->on('p.mrn', '=', 'e.mrn');
                            $join = $join->on('p.episno', '=', 'e.episno');
                            $join = $join->on('p.compcode', '=', 'e.compcode');
                            $join = $join->where('p.recorddate', '!=', Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d'));
                        })->leftJoin('hisdb.doctor as d', function ($join) use ($request){
                            $join = $join->on('d.doctorcode', '=', 'e.admdoctor');
                            $join = $join->on('d.compcode', '=', 'e.compcode');
                        })
                        ->where('e.compcode','=',session('compcode'))
                        ->where('e.mrn','=',$request->mrn)
                        ->orderBy('p.recorddate','desc');
        
        // $patexam_obj = DB::table('hisdb.pathealth')
        //                 ->select('mrn','episno','recordtime','adddate','adduser')
        //                 ->where('compcode','=',session('compcode'))
        //                 ->where('mrn','=',$request->mrn)
        //                 ->orderBy('adddate','desc');
        
        if($episode_obj->exists()){
            $episode_obj = $episode_obj->get();
            
            $data = [];
            
            foreach($episode_obj as $key => $value){
                if(!empty($value->recorddate)){
                    $date['date'] =  Carbon::createFromFormat('Y-m-d', $value->recorddate)->format('d-m-Y').' '.$value->recordtime;
                }else{
                    $date['date'] =  '-';
                }
                $date['mrn'] = $value->mrn;
                $date['episno'] = $value->episno;
                if(!empty($value->adduser)){
                    $date['adduser'] = $value->adduser;
                }else{
                    $date['adduser'] =  '-';
                }
                $date['adduser'] = $value->adduser;
                $date['doctorname'] = $value->doctorname;
                
                array_push($data,$date);
            }
            
            $responce->data = $data;
        }else{
            $responce->data = [];
        }
        
        return json_encode($responce);
        
    }
    
    public function get_table_doctornote(Request $request){
        
        $responce = new stdClass();
        
        $episode_obj = DB::table('hisdb.episode')
                        ->select('diagfinal')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno);
        
        if(!empty($request->recorddate) && $request->recorddate != '-'){
            $pathealth_obj = DB::table('hisdb.pathealth')
                            ->select('idno','compcode','mrn','episno','clinicnote','adduser','adddate','upduser','upddate','complain','recorddate','recordtime','visionl','visionr','colorblind','recstatus','plan_','allergyh','fmh','pmh','socialh','drugh','vas','aggr','easing','pain','behaviour','irritability','severity','lastuser','lastupdate','followupdate','followuptime','anr_rhesus','anr_rubella','anr_vdrl','anr_hiv','anr_hepaB_Ag','anr_hepaB_AB','anr_bloodTrans','anr_drugAllergies','doctorcode','newic','arrival_date','nursing_complete','doctor_complete','computerid','genappear','speech','moodaffect','perception','thinking','cognitivefunc','aetiology','investigate','treatment','prognosis')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno)
                            ->where('recorddate','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate)->format('Y-m-d'))
                            ->where('recordtime','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate)->format('H:i:s'));
                            // ->orderBy('recordtime','desc');
            
            $vitalsign_doc = DB::table('hisdb.pathealth')
                            ->select('bp_sys1','bp_dias2','spo2','pulse','gxt','temperature','height','weight','respiration')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno)
                            ->where('recorddate','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate)->format('Y-m-d'))
                            ->where('recordtime','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate)->format('H:i:s'));
                            // ->orderBy('recordtime','desc');
            
            // $patexam_obj = DB::table('hisdb.patexam')
            //                 ->select('idno','compcode','mrn','episno','recorddate','recordtime','examination','adduser','lastuser','lastupdate','recstatus')
            //                 ->where('compcode','=',session('compcode'))
            //                 ->where('mrn','=',$request->mrn)
            //                 ->where('episno','=',$request->episno)
            //                 ->where('recorddate','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate)->format('Y-m-d'))
            //                 ->where('recordtime','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate)->format('H:i:s'));
        }
        
        $pathistory_obj = DB::table('hisdb.pathistory')
                        ->select('idno','compcode','mrn','recorddate','adduser','lastuser','lastupdate','recstatus','pathname','filename','drugh','pmh','fmh','allergyh','socialh','pgh_myomectomy','pgh_laparoscopy','pgh_endometriosis','lastpapsmear','pgh_others','pmh_renaldisease','pmh_hypertension','pmh_diabetes','pmh_heartdisease','pmh_others','psh_appendicectomy','psh_hypertension','psh_laparotomy','psh_thyroidsurgery','psh_others','fh_hypertension','fh_diabetes','fh_epilepsy','fh_multipregnancy','fh_congenital','anr_bloodgroup','anr_attInject_1st','anr_attInject_2nd','anr_attInject_boost','psychiatryh','personalh')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$request->mrn);
        
        $episdiag_obj = DB::table('hisdb.episdiag')
                        ->select('compcode','mrn','episno','seq','icdcode','diagstatus','lastuser','lastupdate','icdcodeno','adduser','type','suppcode','ripdate','f1','f2','f3','f4','f5')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno);
        
        $pathealthadd_obj = DB::table('hisdb.pathealthadd')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno);
        
        $vitalsign_triage = DB::table('nursing.nursassessment')
                            ->select('vs_bp_sys1 as bp_sys1','vs_bp_dias2 as bp_dias2','vs_spo as spo2','vs_pulse as pulse','vs_gxt as gxt','vs_temperature as temperature','vs_weight as weight','vs_respiration as respiration','vs_height as height','vs_painscore as pain_score')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno);
        
        if($episode_obj->exists()){
            $episode_obj = $episode_obj->first();
            $responce->episode = $episode_obj;
        }
        
        if(!empty($request->recorddate) && $request->recorddate != '-'){
            if($pathealth_obj->exists()){
                $pathealth_obj = $pathealth_obj->first();
                $responce->pathealth = $pathealth_obj;
                
                // $adddate =  Carbon::createFromFormat('Y-m-d', $pathealth_obj->adddate)->format('d-m-Y');
                // $responce->adddate = $adddate;
            }
            
            if($vitalsign_doc->exists()){
                $vitalsign_doc = $vitalsign_doc->first();
                $responce->vitalsign_doc = $vitalsign_doc;
            }
            
            // if($patexam_obj->exists()){
            //     $patexam_obj = $patexam_obj->first();
            //     $responce->patexam = $patexam_obj;
            // }
        }
        
        if($pathistory_obj->exists()){
            $pathistory_obj = $pathistory_obj->first();
            $responce->pathistory = $pathistory_obj;
        }
        
        if($episdiag_obj->exists()){
            $episdiag_obj = $episdiag_obj->first();
            $responce->episdiag = $episdiag_obj;
        }
        
        if($pathealthadd_obj->exists()){
            $pathealthadd_obj = $pathealthadd_obj->first();
            $responce->pathealthadd = $pathealthadd_obj;
        }
        
        if($vitalsign_triage->exists()){
            $vitalsign_triage = $vitalsign_triage->first();
            $responce->vitalsign_triage = $vitalsign_triage;
        }
        
        // $responce->transaction = json_decode($this->get_transaction_table($request));
        
        return json_encode($responce);
        
    }
    
    public function add_refLetter(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('hisdb.patreferral')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn,
                    'episno' => $request->episno,
                    'refdate' => $request->refdate,
                    'refaddress' => $request->refaddress,
                    'refdoc' => $request->refdoc,
                    'reftitle' => $request->reftitle,
                    'refdiag' => $request->refdiag,
                    'refplan' => $request->refplan,
                    'refprescription' => $request->refprescription,
                    'adduser'  => session('username'),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'computerid' => session('computerid'),
                ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function edit_refLetter(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $patreferral = DB::table('hisdb.patreferral')
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno)
                            ->where('compcode','=',session('compcode'));
            
            if($patreferral->exists()){
                $patreferral
                    ->update([
                        'refdate' => $request->refdate,
                        'refaddress' => $request->refaddress,
                        'refdoc' => $request->refdoc,
                        'reftitle' => $request->reftitle,
                        'refdiag' => $request->refdiag,
                        'refplan' => $request->refplan,
                        'refprescription' => $request->refprescription,
                        'upduser'  => session('username'),
                        'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }else{
                DB::table('hisdb.patreferral')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'refdate' => $request->refdate,
                        'refaddress' => $request->refaddress,
                        'refdoc' => $request->refdoc,
                        'reftitle' => $request->reftitle,
                        'refdiag' => $request->refdiag,
                        'refplan' => $request->refplan,
                        'refprescription' => $request->refprescription,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
            }
            
            $queries = DB::getQueryLog();
            // dump($queries);
            
            DB::commit();
            
            $responce = new stdClass();
            
            return json_encode($responce);
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function get_table_refLetter(Request $request){
        
        $responce = new stdClass();
        
        $episode_obj = DB::table('hisdb.episode')
                        ->select('diagfinal as diagfinal_ref')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno);
        
        $date_obj = DB::table('hisdb.pathealth')
                    // ->select('recorddate')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$request->mrn)
                    ->where('episno','=',$request->episno)
                    ->first();
        
        // dd($date_obj->recorddate);
        
        if(!empty($request->recorddate) && $request->recorddate != '-'){
            $pathealth_obj = DB::table('hisdb.pathealth')
                            ->select(
                                'complain as complain_ref',
                                'clinicnote as clinicnote_ref',
                                // 'drugh as drugh_ref',
                                // 'allergyh as allergyh_ref',
                                'genappear as genappear_ref',
                                'speech as speech_ref',
                                'moodaffect as moodaffect_ref',
                                'perception as perception_ref',
                                'thinking as thinking_ref',
                                'cognitivefunc as cognitivefunc_ref',
                                'followuptime as followuptime_ref',
                                'followupdate as followupdate_ref',
                                'aetiology as aetiology_ref',
                                'investigate as investigate_ref',
                                'treatment as treatment_ref',
                                'prognosis as prognosis_ref',
                                'plan_ as plan_ref',
                                'bp_sys1 as bp_sys1_ref',
                                'bp_dias2 as bp_dias2_ref',
                                'spo2 as spo2_ref',
                                'pulse as pulse_ref',
                                'gxt as gxt_ref',
                                'temperature as temperature_ref',
                                'height as height_ref',
                                'weight as weight_ref',
                                'respiration as respiration_ref',
                                'pain_score as pain_score_ref',
                                'adduser as adduser_ref',
                                'adddate as adddate_ref'
                            )
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno)
                            ->where('recorddate','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate)->format('Y-m-d'))
                            ->where('recordtime','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate)->format('H:i:s'));
                            // ->orderBy('recordtime','desc');
            
            $pathistory_obj = DB::table('hisdb.pathistory')
                            ->select(
                                'psychiatryh as psychiatryh_ref',
                                'pmh as pmh_ref',
                                'fmh as fmh_ref',
                                'personalh as personalh_ref',
                                'drugh as drugh_ref',
                                'allergyh as allergyh_ref',
                                'socialh as socialh_ref',
                            )
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn);
                            // ->where('recorddate','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate));
                            // ->where('recorddate','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate)->format('Y-m-d'))
                            // ->where('recordtime','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate)->format('H:i:s'));
            
            // $patexam_obj = DB::table('hisdb.patexam')
            //                 ->select('examination as examination_ref')
            //                 ->where('compcode','=',session('compcode'))
            //                 ->where('mrn','=',$request->mrn)
            //                 ->where('episno','=',$request->episno)
            //                 ->where('recorddate','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate)->format('Y-m-d'))
            //                 ->where('recordtime','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate)->format('H:i:s'));
        }
        
        $episdiag_obj = DB::table('hisdb.episdiag')
                        ->select('icdcode as icdcode_ref')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno);
        
        $pathealthadd_obj = DB::table('hisdb.pathealthadd')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno);
        
        // form_refLetter
        $pat_mast = DB::table('hisdb.pat_mast')
                    // ->select('Name')
                    ->where('CompCode',session('compcode'))
                    ->where('MRN','=',$request->mrn)
                    ->where('Episno','=',$request->episno)
                    ->first();
        
        $sysparam = DB::table('sysdb.sysparam')
                    // ->select('pvalue1')
                    ->where('compcode',session('compcode'))
                    ->where('source','=','HIS')
                    ->where('trantype','=','REFHDR')
                    ->first();
        
        $sys_reftitle = ucwords(strtolower($pat_mast->Name)).' '.$sysparam->pvalue1;
        $responce->sys_reftitle = $sys_reftitle;
        
        $adduser = session('username');
        $responce->adduser = $adduser;
        
        $patreferral_obj = DB::table('hisdb.patreferral')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno);
        
        if($episode_obj->exists()){
            $episode_obj = $episode_obj->first();
            $responce->episode = $episode_obj;
        }
        
        if(!empty($request->recorddate) && $request->recorddate != '-'){
            if($pathealth_obj->exists()){
                $pathealth_obj = $pathealth_obj->first();
                $responce->pathealth = $pathealth_obj;
            }
            
            if($pathistory_obj->exists()){
                $pathistory_obj = $pathistory_obj->first();
                $responce->pathistory = $pathistory_obj;
            }
            
            // if($patexam_obj->exists()){
            //     $patexam_obj = $patexam_obj->first();
            //     $responce->patexam = $patexam_obj;
            // }
        }
        
        if($episdiag_obj->exists()){
            $episdiag_obj = $episdiag_obj->first();
            $responce->episdiag = $episdiag_obj;
        }
        
        if($pathealthadd_obj->exists()){
            $pathealthadd_obj = $pathealthadd_obj->first();
            $responce->pathealthadd = $pathealthadd_obj;
        }
        
        // form_refLetter
        if($patreferral_obj->exists()){
            $patreferral_obj = $patreferral_obj->first();
            $responce->patreferral = $patreferral_obj;
        }
        
        $responce->transaction = json_decode($this->get_transaction_table($request));
        
        return json_encode($responce);
        
    }
    
    public function add_otbook(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('hisdb.pat_otbook')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn,
                    'episno' => $request->episno,
                    'iPesakit' => $request->iPesakit,
                    'req_type' => $request->req_type,
                    'op_date' => $request->op_date,
                    'oper_type' => $request->oper_type,
                    'adm_type' => $request->adm_type,
                    'anaesthetist' => $request->anaesthetist,
                    'diagnosis' => $request->ot_diagnosis,
                    'diagnosedby' => strtoupper($request->ot_diagnosedby),
                    'remarks' => $request->ot_remarks,
                    'doctorname'  => strtoupper($request->ot_doctorname),
                    'adduser'  => strtoupper($request->ot_lastuser),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastuser' => strtoupper($request->ot_lastuser),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'computerid' => session('computerid'),
                ]);
            
            DB::table('hisdb.episode')
                ->where('mrn','=',$request->mrn)
                ->where('episno','=',$request->episno)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'reff_ed' => '1',
                    'lastuser'  => strtoupper($request->ot_lastuser),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                ]);
            
            if(!empty($request->iPesakit)){
                $pat_mast = DB::table('hisdb.pat_mast')
                            ->where('CompCode',session('compcode'))
                            ->where('MRN',$request->mrn)
                            ->first();
                
                if($pat_mast->iPesakit != $request->iPesakit){
                    DB::table('hisdb.pat_mast')
                        ->where('CompCode',session('compcode'))
                        ->where('MRN',$request->mrn)
                        ->update([
                            'iPesakit' => $request->iPesakit,
                        ]);
                }
            }
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function edit_otbook(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $pat_otbook = DB::table('hisdb.pat_otbook')
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno)
                        ->where('compcode','=',session('compcode'));
            
            if($pat_otbook->exists()){
                $pat_otbook
                    ->update([
                        'iPesakit' => $request->iPesakit,
                        'req_type' => $request->req_type,
                        'op_date' => $request->op_date,
                        'oper_type' => $request->oper_type,
                        'adm_type' => $request->adm_type,
                        'anaesthetist' => $request->anaesthetist,
                        'diagnosis' => $request->ot_diagnosis,
                        'diagnosedby' => strtoupper($request->ot_diagnosedby),
                        'remarks' => $request->ot_remarks,
                        'doctorname'  => strtoupper($request->ot_doctorname),
                        'upduser'  => strtoupper($request->ot_lastuser),
                        'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser' => strtoupper($request->ot_lastuser),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
            }else{
                DB::table('hisdb.pat_otbook')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'iPesakit' => $request->iPesakit,
                        'req_type' => $request->req_type,
                        'op_date' => $request->op_date,
                        'oper_type' => $request->oper_type,
                        'adm_type' => $request->adm_type,
                        'anaesthetist' => $request->anaesthetist,
                        'diagnosis' => $request->ot_diagnosis,
                        'diagnosedby' => strtoupper($request->ot_diagnosedby),
                        'remarks' => $request->ot_remarks,
                        'doctorname'  => strtoupper($request->ot_doctorname),
                        'adduser'  => strtoupper($request->ot_lastuser),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser' => strtoupper($request->ot_lastuser),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
            }
            
            DB::table('hisdb.episode')
                ->where('mrn','=',$request->mrn)
                ->where('episno','=',$request->episno)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'reff_ed' => '1',
                    'lastuser'  => strtoupper($request->ot_lastuser),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                ]);
            
            if(!empty($request->iPesakit)){
                $pat_mast = DB::table('hisdb.pat_mast')
                            ->where('CompCode',session('compcode'))
                            ->where('MRN',$request->mrn)
                            ->first();
                
                if($pat_mast->iPesakit != $request->iPesakit){
                    DB::table('hisdb.pat_mast')
                        ->where('CompCode',session('compcode'))
                        ->where('MRN',$request->mrn)
                        ->update([
                            'iPesakit' => $request->iPesakit,
                        ]);
                }
            }
            
            $queries = DB::getQueryLog();
            // dump($queries);
            
            DB::commit();
            
            $responce = new stdClass();
            
            return json_encode($responce);
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function get_table_otbook(Request $request){
        
        $pat_otbook_bed_obj = DB::table('hisdb.bed')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno);
        
        $pat_otbook_obj = DB::table('hisdb.pat_otbook')
                        ->select('idno','compcode','mrn','episno','iPesakit as p_iPesakit','req_type','op_date','oper_type','adm_type','anaesthetist','diagnosis as ot_diagnosis','diagnosedby as ot_diagnosedby','remarks as ot_remarks','doctorname as ot_doctorname','adduser','adddate','upduser','upddate','lastuser as ot_lastuser','lastupdate','computerid')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno);
        
        $nurshandover_obj = DB::table('nursing.nurshandover')
                            ->select('bpsys_stand as vs_bp_sys1','bpdias_stand as vs_bp_dias2','spo2 as vs_spo','hr as vs_pulse','gxt as vs_gxt','temp_ as vs_temperature','weight as vs_weight','height as vs_height','respiration as vs_respiration')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno)
                            ->orderBy('idno','desc');
                            // ->where('epistycode','=','OP');
        
        $nurshistory_obj = DB::table('nursing.nurshistory')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn);
        
        $patmast_obj = DB::table('hisdb.pat_mast')
                        ->select('iPesakit')
                        ->where('compcode',session('compcode'))
                        ->where('mrn','=',$request->mrn);
        
        $responce = new stdClass();
        
        if($pat_otbook_bed_obj->exists()){
            $pat_otbook_bed_obj = $pat_otbook_bed_obj->first();
            $responce->pat_otbook_bed = $pat_otbook_bed_obj;
        }
        
        if($pat_otbook_obj->exists()){
            $pat_otbook_obj = $pat_otbook_obj->first();
            $responce->pat_otbook = $pat_otbook_obj;
        }
        
        if($nurshandover_obj->exists()){
            $nurshandover_obj = $nurshandover_obj->first();
            $responce->nurshandover = $nurshandover_obj;
        }
        
        if($nurshistory_obj->exists()){
            $nurshistory_obj = $nurshistory_obj->first();
            $responce->nurshistory = $nurshistory_obj;
        }
        
        if($patmast_obj->exists()){
            $patmast_obj = $patmast_obj->first();
            
            $iPesakit = $patmast_obj->iPesakit;
            $responce->iPesakit = $iPesakit;
        }
        
        return json_encode($responce);
        
    }
    
    public function add_radClinic(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $episode = DB::table('hisdb.episode')
                        ->select('newcaseP','newcaseNP','followupP','followupNP')
                        ->where('compcode',session('compcode'))
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno);
            
            if($episode->exists()){
                $episode_obj = $episode->first();
                
                if(!empty($episode_obj->newcaseP) || !empty($episode_obj->newcaseNP)){
                    if($request->rad_pregnant == 1){
                        $episode
                            ->update([
                                'newcaseP' => 1,
                                'newcaseNP' => null,
                                'reff_rad' => '1',
                                'lastuser'  => session('username'),
                                'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                            ]);
                    }else if($request->rad_pregnant == 0){
                        $episode
                            ->update([
                                'newcaseP' => null,
                                'newcaseNP' => 1,
                                'reff_rad' => '1',
                                'lastuser'  => session('username'),
                                'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                            ]);
                    }
                }else if(!empty($episode_obj->followupP) || !empty($episode_obj->followupNP)){
                    if($request->rad_pregnant == 1){
                        $episode
                            ->update([
                                'followupP' => 1,
                                'followupNP' => null,
                                'reff_rad' => '1',
                                'lastuser'  => session('username'),
                                'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                            ]);
                    }else if($request->rad_pregnant == 0){
                        $episode
                            ->update([
                                'followupP' => null,
                                'followupNP' => 1,
                                'reff_rad' => '1',
                                'lastuser'  => session('username'),
                                'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                            ]);
                    }
                }
            }
            
            // $nursassessment = DB::table('nursing.nursassessment')
            //                 ->where('mrn','=',$request->mrn)
            //                 ->where('episno','=',$request->episno)
            //                 ->where('compcode','=',session('compcode'));
            
            // if($nursassessment->exists()){
            //     $nursassessment
            //         ->update([
            //             'vs_weight' => $request->rad_weight,
            //             'lastuser'  => session('username'),
            //             'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
            //         ]);
            // }
            
            // $pathealth = DB::table('hisdb.pathealth')
            //             ->where('mrn','=',$request->mrn)
            //             ->where('episno','=',$request->episno)
            //             ->where('recorddate','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate)->format('Y-m-d'))
            //             ->where('recordtime','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate)->format('H:i:s'))
            //             ->where('compcode','=',session('compcode'));
            
            // if($pathealth->exists()){
            //     $pathealth
            //         ->update([
            //             'weight' => $request->rad_weight,
            //             'lastuser'  => session('username'),
            //             'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
            //         ]);
            // }
            
            $pathistory = DB::table('hisdb.pathistory')
                        ->where('mrn','=',$request->mrn)
                        // ->where('recorddate','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate))
                        ->where('compcode','=',session('compcode'));
            
            if($pathistory->exists()){
                $pathistory
                    ->update([
                        'allergyh' => $request->rad_allergy,
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }
            
            $pat_radiology = DB::table('hisdb.pat_radiology')
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno)
                            ->where('compcode','=',session('compcode'));
            
            if($pat_radiology->exists()){
                $pat_radiology
                    ->update([
                        'iPesakit' => $request->iPesakit,
                        'weight' => $request->rad_weight,
                        'pt_condition' => $request->pt_condition,
                        'LMP' => $request->LMP,
                        'xray' => $request->xray,
                        'xray_date' => $request->xray_date,
                        'xray_remark' => $request->xray_remark,
                        'mri' => $request->mri,
                        'mri_date' => $request->mri_date,
                        'mri_remark' => $request->mri_remark,
                        'angio' => $request->angio,
                        'angio_date' => $request->angio_date,
                        'angio_remark' => $request->angio_remark,
                        'ultrasound' => $request->ultrasound,
                        'ultrasound_date' => $request->ultrasound_date,
                        'ultrasound_remark' => $request->ultrasound_remark,
                        'ct' => $request->ct,
                        'ct_date' => $request->ct_date,
                        'ct_remark' => $request->ct_remark,
                        'fluroscopy' => $request->fluroscopy,
                        'fluroscopy_date' => $request->fluroscopy_date,
                        'fluroscopy_remark' => $request->fluroscopy_remark,
                        'mammogram' => $request->mammogram,
                        'mammogram_date' => $request->mammogram_date,
                        'mammogram_remark' => $request->mammogram_remark,
                        'bmd' => $request->bmd,
                        'bmd_date' => $request->bmd_date,
                        'bmd_remark' => $request->bmd_remark,
                        'clinicaldata' => $request->clinicaldata,
                        'doctorname'  => strtoupper($request->radClinic_doctorname),
                        'rad_note' => $request->rad_note,
                        'radiologist'  => strtoupper($request->radClinic_radiologist),
                        'upduser'  => session('username'),
                        'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser' => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
            }else{
                DB::table('hisdb.pat_radiology')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'iPesakit' => $request->iPesakit,
                        'weight' => $request->rad_weight,
                        'pt_condition' => $request->pt_condition,
                        'LMP' => $request->LMP,
                        'xray' => $request->xray,
                        'xray_date' => $request->xray_date,
                        'xray_remark' => $request->xray_remark,
                        'mri' => $request->mri,
                        'mri_date' => $request->mri_date,
                        'mri_remark' => $request->mri_remark,
                        'angio' => $request->angio,
                        'angio_date' => $request->angio_date,
                        'angio_remark' => $request->angio_remark,
                        'ultrasound' => $request->ultrasound,
                        'ultrasound_date' => $request->ultrasound_date,
                        'ultrasound_remark' => $request->ultrasound_remark,
                        'ct' => $request->ct,
                        'ct_date' => $request->ct_date,
                        'ct_remark' => $request->ct_remark,
                        'fluroscopy' => $request->fluroscopy,
                        'fluroscopy_date' => $request->fluroscopy_date,
                        'fluroscopy_remark' => $request->fluroscopy_remark,
                        'mammogram' => $request->mammogram,
                        'mammogram_date' => $request->mammogram_date,
                        'mammogram_remark' => $request->mammogram_remark,
                        'bmd' => $request->bmd,
                        'bmd_date' => $request->bmd_date,
                        'bmd_remark' => $request->bmd_remark,
                        'clinicaldata' => $request->clinicaldata,
                        'doctorname'  => strtoupper($request->radClinic_doctorname),
                        'rad_note' => $request->rad_note,
                        'radiologist'  => strtoupper($request->radClinic_radiologist),
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser' => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
            }
            
            if(!empty($request->iPesakit)){
                $pat_mast = DB::table('hisdb.pat_mast')
                            ->where('CompCode',session('compcode'))
                            ->where('MRN',$request->mrn)
                            ->first();
                
                if($pat_mast->iPesakit != $request->iPesakit){
                    DB::table('hisdb.pat_mast')
                        ->where('CompCode',session('compcode'))
                        ->where('MRN',$request->mrn)
                        ->update([
                            'iPesakit' => $request->iPesakit,
                        ]);
                }
            }
            
            // DB::table('hisdb.episode')
            //     ->where('mrn','=',$request->mrn)
            //     ->where('episno','=',$request->episno)
            //     ->where('compcode','=',session('compcode'))
            //     ->update([
            //         'reff_rad' => '1',
            //         'lastuser'  => session('username'),
            //         'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
            //     ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function edit_radClinic(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $episode = DB::table('hisdb.episode')
                        ->select('newcaseP','newcaseNP','followupP','followupNP')
                        ->where('compcode',session('compcode'))
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno);
            
            if($episode->exists()){
                $episode_obj = $episode->first();
                
                if(!empty($episode_obj->newcaseP) || !empty($episode_obj->newcaseNP)){
                    if($request->rad_pregnant == 1){
                        $episode
                            ->update([
                                'newcaseP' => 1,
                                'newcaseNP' => null,
                                'reff_rad' => '1',
                                'lastuser'  => session('username'),
                                'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                            ]);
                    }else if($request->rad_pregnant == 0){
                        $episode
                            ->update([
                                'newcaseP' => null,
                                'newcaseNP' => 1,
                                'reff_rad' => '1',
                                'lastuser'  => session('username'),
                                'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                            ]);
                    }
                }else if(!empty($episode_obj->followupP) || !empty($episode_obj->followupNP)){
                    if($request->rad_pregnant == 1){
                        $episode
                            ->update([
                                'followupP' => 1,
                                'followupNP' => null,
                                'reff_rad' => '1',
                                'lastuser'  => session('username'),
                                'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                            ]);
                    }else if($request->rad_pregnant == 0){
                        $episode
                            ->update([
                                'followupP' => null,
                                'followupNP' => 1,
                                'reff_rad' => '1',
                                'lastuser'  => session('username'),
                                'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                            ]);
                    }
                }
            }
            
            // $nursassessment = DB::table('nursing.nursassessment')
            //                 ->where('mrn','=',$request->mrn)
            //                 ->where('episno','=',$request->episno)
            //                 ->where('compcode','=',session('compcode'));
            
            // if($nursassessment->exists()){
            //     $nursassessment
            //         ->update([
            //             'vs_weight' => $request->rad_weight,
            //             'lastuser'  => session('username'),
            //             'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
            //         ]);
            // }
            
            // $pathealth = DB::table('hisdb.pathealth')
            //             ->where('mrn','=',$request->mrn)
            //             ->where('episno','=',$request->episno)
            //             ->where('recorddate','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate)->format('Y-m-d'))
            //             ->where('recordtime','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate)->format('H:i:s'))
            //             ->where('compcode','=',session('compcode'));
            
            // if($pathealth->exists()){
            //     $pathealth
            //         ->update([
            //             'weight' => $request->rad_weight,
            //             'lastuser'  => session('username'),
            //             'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
            //         ]);
            // }
            
            $pathistory = DB::table('hisdb.pathistory')
                        ->where('mrn','=',$request->mrn)
                        // ->where('recorddate','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate))
                        ->where('compcode','=',session('compcode'));
            
            if($pathistory->exists()){
                $pathistory
                    ->update([
                        'allergyh' => $request->rad_allergy,
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }
            
            $pat_radiology = DB::table('hisdb.pat_radiology')
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno)
                            ->where('compcode','=',session('compcode'));
            
            if($pat_radiology->exists()){
                $pat_radiology
                    ->update([
                        'iPesakit' => $request->iPesakit,
                        'weight' => $request->rad_weight,
                        'pt_condition' => $request->pt_condition,
                        'LMP' => $request->LMP,
                        'xray' => $request->xray,
                        'xray_date' => $request->xray_date,
                        'xray_remark' => $request->xray_remark,
                        'mri' => $request->mri,
                        'mri_date' => $request->mri_date,
                        'mri_remark' => $request->mri_remark,
                        'angio' => $request->angio,
                        'angio_date' => $request->angio_date,
                        'angio_remark' => $request->angio_remark,
                        'ultrasound' => $request->ultrasound,
                        'ultrasound_date' => $request->ultrasound_date,
                        'ultrasound_remark' => $request->ultrasound_remark,
                        'ct' => $request->ct,
                        'ct_date' => $request->ct_date,
                        'ct_remark' => $request->ct_remark,
                        'fluroscopy' => $request->fluroscopy,
                        'fluroscopy_date' => $request->fluroscopy_date,
                        'fluroscopy_remark' => $request->fluroscopy_remark,
                        'mammogram' => $request->mammogram,
                        'mammogram_date' => $request->mammogram_date,
                        'mammogram_remark' => $request->mammogram_remark,
                        'bmd' => $request->bmd,
                        'bmd_date' => $request->bmd_date,
                        'bmd_remark' => $request->bmd_remark,
                        'clinicaldata' => $request->clinicaldata,
                        'doctorname'  => strtoupper($request->radClinic_doctorname),
                        'rad_note' => $request->rad_note,
                        'radiologist'  => strtoupper($request->radClinic_radiologist),
                        'upduser'  => session('username'),
                        'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser' => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
            }else{
                DB::table('hisdb.pat_radiology')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'iPesakit' => $request->iPesakit,
                        'weight' => $request->rad_weight,
                        'pt_condition' => $request->pt_condition,
                        'LMP' => $request->LMP,
                        'xray' => $request->xray,
                        'xray_date' => $request->xray_date,
                        'xray_remark' => $request->xray_remark,
                        'mri' => $request->mri,
                        'mri_date' => $request->mri_date,
                        'mri_remark' => $request->mri_remark,
                        'angio' => $request->angio,
                        'angio_date' => $request->angio_date,
                        'angio_remark' => $request->angio_remark,
                        'ultrasound' => $request->ultrasound,
                        'ultrasound_date' => $request->ultrasound_date,
                        'ultrasound_remark' => $request->ultrasound_remark,
                        'ct' => $request->ct,
                        'ct_date' => $request->ct_date,
                        'ct_remark' => $request->ct_remark,
                        'fluroscopy' => $request->fluroscopy,
                        'fluroscopy_date' => $request->fluroscopy_date,
                        'fluroscopy_remark' => $request->fluroscopy_remark,
                        'mammogram' => $request->mammogram,
                        'mammogram_date' => $request->mammogram_date,
                        'mammogram_remark' => $request->mammogram_remark,
                        'bmd' => $request->bmd,
                        'bmd_date' => $request->bmd_date,
                        'bmd_remark' => $request->bmd_remark,
                        'clinicaldata' => $request->clinicaldata,
                        'doctorname'  => strtoupper($request->radClinic_doctorname),
                        'rad_note' => $request->rad_note,
                        'radiologist'  => strtoupper($request->radClinic_radiologist),
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser' => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
            }
            
            if(!empty($request->iPesakit)){
                $pat_mast = DB::table('hisdb.pat_mast')
                            ->where('CompCode',session('compcode'))
                            ->where('MRN',$request->mrn)
                            ->first();
                
                if($pat_mast->iPesakit != $request->iPesakit){
                    DB::table('hisdb.pat_mast')
                        ->where('CompCode',session('compcode'))
                        ->where('MRN',$request->mrn)
                        ->update([
                            'iPesakit' => $request->iPesakit,
                        ]);
                }
            }
            
            // DB::table('hisdb.episode')
            //     ->where('mrn','=',$request->mrn)
            //     ->where('episno','=',$request->episno)
            //     ->where('compcode','=',session('compcode'))
            //     ->update([
            //         'reff_rad' => '1',
            //         'lastuser'  => session('username'),
            //         'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
            //     ]);
            
            $queries = DB::getQueryLog();
            // dump($queries);
            
            DB::commit();
            
            $responce = new stdClass();
            
            return json_encode($responce);
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function get_table_radClinic(Request $request){
        
        $pat_radiology_obj = DB::table('hisdb.pat_radiology as pr')
                            ->select('pr.compcode','pr.mrn','pr.episno','pr.iPesakit as pr_iPesakit','pr.weight as rad_weight','pr.pt_condition','pr.LMP','pr.xray','pr.xray_date','pr.xray_remark','pr.mri','pr.mri_date','pr.mri_remark','pr.angio','pr.angio_date','pr.angio_remark','pr.ultrasound','pr.ultrasound_date','pr.ultrasound_remark','pr.ct','pr.ct_date','pr.ct_remark','pr.fluroscopy','pr.fluroscopy_date','pr.fluroscopy_remark','pr.mammogram','pr.mammogram_date','pr.mammogram_remark','pr.bmd','pr.bmd_date','pr.bmd_remark','pr.clinicaldata','pr.doctorname as radClinic_doctorname','pr.rad_note','pr.radiologist as radClinic_radiologist','pr.adduser','pr.adddate','pr.upduser','pr.upddate','pr.lastuser as radClinic_lastuser','pr.lastupdate','pr.computerid')
                            // ->leftJoin('nursing.nursassessment as na', function ($join) use ($request){
                            //     $join = $join->on('na.mrn', '=', 'pr.mrn')
                            //                 ->on('na.episno', '=', 'pr.episno')
                            //                 ->where('na.compcode','=',session('compcode'));
                            // })
                            // ->leftJoin('nursing.nurshistory as nh', function ($join) use ($request){
                            //     $join = $join->on('nh.mrn', '=', 'pr.mrn')
                            //                 ->where('nh.compcode','=',session('compcode'));
                            // })
                            ->where('pr.compcode','=',session('compcode'))
                            ->where('pr.mrn','=',$request->mrn)
                            ->where('pr.episno','=',$request->episno);
        
        $episode = DB::table('hisdb.episode')
                    ->where('compcode',session('compcode'))
                    ->where('mrn','=',$request->mrn)
                    ->where('episno','=',$request->episno);
        
        // if(!empty($request->recorddate) && $request->recorddate != '-'){
        //     $pathealth_obj = DB::table('hisdb.pathealth')
        //                     ->select('weight')
        //                     ->where('compcode','=',session('compcode'))
        //                     ->where('recorddate','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate)->format('Y-m-d'))
        //                     ->where('recordtime','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate)->format('H:i:s'))
        //                     ->where('mrn','=',$request->mrn)
        //                     ->where('episno','=',$request->episno);
        // }
        
        $pathistory_obj = DB::table('hisdb.pathistory')
                        ->select('allergyh')
                        ->where('compcode','=',session('compcode'))
                        // ->where('recorddate','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate))
                        ->where('mrn','=',$request->mrn);
        
        // $nursassessment_obj = DB::table('nursing.nursassessment')
        //                     ->select('vs_weight')
        //                     ->where('compcode','=',session('compcode'))
        //                     ->where('mrn','=',$request->mrn)
        //                     ->where('episno','=',$request->episno);
        
        $patmast_obj = DB::table('hisdb.pat_mast')
                        ->select('iPesakit')
                        ->where('compcode',session('compcode'))
                        ->where('mrn','=',$request->mrn);
        
        $responce = new stdClass();
        
        if($pat_radiology_obj->exists()){
            $pat_radiology_obj = $pat_radiology_obj->first();
            $responce->pat_radiology = $pat_radiology_obj;
        }
        
        if($episode->exists()){
            $episode = $episode->first();
            if($episode->newcaseP == 1 || $episode->followupP == 1){
                // $pregnant = 1;
                $responce->pregnant = 1;
            }else if($episode->newcaseNP == 1 || $episode->followupNP == 1){
                // $pregnant = 0;
                $responce->pregnant = 0;
            }
        }
        
        // if(!empty($request->recorddate) && $request->recorddate != '-'){
        //     if($pathealth_obj->exists()){
        //         $pathealth_obj = $pathealth_obj->first();
                
        //         $rad_weight = $pathealth_obj->weight;
        //         $responce->rad_weight = $rad_weight;
        //     }
        // }
        
        if($pathistory_obj->exists()){
            $pathistory_obj = $pathistory_obj->first();
            
            $rad_allergy = $pathistory_obj->allergyh;
            $responce->rad_allergy = $rad_allergy;
        }
        
        // if($nursassessment_obj->exists()){
        //     $nursassessment_obj = $nursassessment_obj->first();
            
        //     $rad_weight = $nursassessment_obj->vs_weight;
        //     $responce->rad_weight = $rad_weight;
        // }
        
        if($patmast_obj->exists()){
            $patmast_obj = $patmast_obj->first();
            
            $iPesakit = $patmast_obj->iPesakit;
            $responce->iPesakit = $iPesakit;
        }
        
        return json_encode($responce);
        
    }
    
    public function add_mri(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            // $pathealth = DB::table('hisdb.pathealth')
            //             ->where('mrn','=',$request->mrn)
            //             ->where('episno','=',$request->episno)
            //             ->where('recorddate','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate)->format('Y-m-d'))
            //             ->where('recordtime','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate)->format('H:i:s'))
            //             ->where('compcode','=',session('compcode'));
            
            // if($pathealth->exists()){
            //     $pathealth
            //         ->update([
            //             'weight' => $request->mri_weight,
            //             'lastuser'  => session('username'),
            //             'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
            //         ]);
            // }
            
            $nursassessment = DB::table('nursing.nursassessment')
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno)
                            ->where('compcode','=',session('compcode'));
            
            if($nursassessment->exists()){
                $nursassessment
                    ->update([
                        'vs_weight' => $request->mri_weight,
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }
            
            $pat_mri = DB::table('hisdb.pat_mri')
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno)
                        ->where('compcode','=',session('compcode'));
            
            if($pat_mri->exists()){
                $pat_mri
                    ->update([
                        'weight' => $request->mri_weight,
                        'entereddate' => $request->mri_entereddate,
                        'cardiacpacemaker' => $request->cardiacpacemaker,
                        'pros_valve' => $request->pros_valve,
                        'prosvalve_rmk' => $request->prosvalve_rmk,
                        'intraocular' => $request->intraocular,
                        'cochlear_imp' => $request->cochlear_imp,
                        'neurotransm' => $request->neurotransm,
                        'bonegrowth' => $request->bonegrowth,
                        'druginfuse' => $request->druginfuse,
                        'surg_clips' => $request->surg_clips,
                        'jointlimb_pros' => $request->jointlimb_pros,
                        'shrapnel' => $request->shrapnel,
                        'oper_3mth' => $request->oper_3mth,
                        'oper3mth_remark' => $request->oper3mth_remark,
                        'prev_mri' => $request->prev_mri,
                        'claustrophobia' => $request->claustrophobia,
                        'dental_imp' => $request->dental_imp,
                        'frmgnetic_imp' => $request->frmgnetic_imp,
                        'pregnancy' => $request->pregnancy,
                        'allergy_drug' => $request->allergy_drug,
                        'bloodurea' => $request->bloodurea,
                        'serum_creatinine' => $request->serum_creatinine,
                        'doctorname' => strtoupper($request->mri_doctorname),
                        'radiologist' => strtoupper($request->mri_radiologist),
                        'radiographer' => strtoupper($request->radiographer),
                        // 'staffnurse' => $request->staffnurse,
                        'upduser'  => strtoupper($request->mri_lastuser),
                        'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser' => strtoupper($request->mri_lastuser),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
            }else{
                DB::table('hisdb.pat_mri')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'weight' => $request->mri_weight,
                        'entereddate' => $request->mri_entereddate,
                        'cardiacpacemaker' => $request->cardiacpacemaker,
                        'pros_valve' => $request->pros_valve,
                        'prosvalve_rmk' => $request->prosvalve_rmk,
                        'intraocular' => $request->intraocular,
                        'cochlear_imp' => $request->cochlear_imp,
                        'neurotransm' => $request->neurotransm,
                        'bonegrowth' => $request->bonegrowth,
                        'druginfuse' => $request->druginfuse,
                        'surg_clips' => $request->surg_clips,
                        'jointlimb_pros' => $request->jointlimb_pros,
                        'shrapnel' => $request->shrapnel,
                        'oper_3mth' => $request->oper_3mth,
                        'oper3mth_remark' => $request->oper3mth_remark,
                        'prev_mri' => $request->prev_mri,
                        'claustrophobia' => $request->claustrophobia,
                        'dental_imp' => $request->dental_imp,
                        'frmgnetic_imp' => $request->frmgnetic_imp,
                        'pregnancy' => $request->pregnancy,
                        'allergy_drug' => $request->allergy_drug,
                        'bloodurea' => $request->bloodurea,
                        'serum_creatinine' => $request->serum_creatinine,
                        'doctorname' => strtoupper($request->mri_doctorname),
                        'radiologist' => strtoupper($request->mri_radiologist),
                        'radiographer' => strtoupper($request->radiographer),
                        // 'staffnurse' => $request->staffnurse,
                        'adduser'  => strtoupper($request->mri_lastuser),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser' => strtoupper($request->mri_lastuser),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
            }
            
            DB::table('hisdb.episode')
                ->where('mrn','=',$request->mrn)
                ->where('episno','=',$request->episno)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'reff_rad' => '1',
                    'lastuser'  => strtoupper($request->mri_lastuser),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function edit_mri(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            // $pathealth = DB::table('hisdb.pathealth')
            //             ->where('mrn','=',$request->mrn)
            //             ->where('episno','=',$request->episno)
            //             ->where('recorddate','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate)->format('Y-m-d'))
            //             ->where('recordtime','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate)->format('H:i:s'))
            //             ->where('compcode','=',session('compcode'));
            
            // if($pathealth->exists()){
            //     $pathealth
            //         ->update([
            //             'weight' => $request->mri_weight,
            //             'lastuser'  => session('username'),
            //             'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
            //         ]);
            // }
            
            $nursassessment = DB::table('nursing.nursassessment')
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno)
                            ->where('compcode','=',session('compcode'));
            
            if($nursassessment->exists()){
                $nursassessment
                    ->update([
                        'vs_weight' => $request->mri_weight,
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }
            
            $pat_mri = DB::table('hisdb.pat_mri')
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno)
                        ->where('compcode','=',session('compcode'));
            
            if($pat_mri->exists()){
                $pat_mri
                    ->update([
                        'weight' => $request->mri_weight,
                        'entereddate' => $request->mri_entereddate,
                        'cardiacpacemaker' => $request->cardiacpacemaker,
                        'pros_valve' => $request->pros_valve,
                        'prosvalve_rmk' => $request->prosvalve_rmk,
                        'intraocular' => $request->intraocular,
                        'cochlear_imp' => $request->cochlear_imp,
                        'neurotransm' => $request->neurotransm,
                        'bonegrowth' => $request->bonegrowth,
                        'druginfuse' => $request->druginfuse,
                        'surg_clips' => $request->surg_clips,
                        'jointlimb_pros' => $request->jointlimb_pros,
                        'shrapnel' => $request->shrapnel,
                        'oper_3mth' => $request->oper_3mth,
                        'oper3mth_remark' => $request->oper3mth_remark,
                        'prev_mri' => $request->prev_mri,
                        'claustrophobia' => $request->claustrophobia,
                        'dental_imp' => $request->dental_imp,
                        'frmgnetic_imp' => $request->frmgnetic_imp,
                        'pregnancy' => $request->pregnancy,
                        'allergy_drug' => $request->allergy_drug,
                        'bloodurea' => $request->bloodurea,
                        'serum_creatinine' => $request->serum_creatinine,
                        'doctorname' => strtoupper($request->mri_doctorname),
                        'radiologist' => strtoupper($request->mri_radiologist),
                        'radiographer' => strtoupper($request->radiographer),
                        // 'staffnurse' => $request->staffnurse,
                        'upduser'  => strtoupper($request->mri_lastuser),
                        'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser' => strtoupper($request->mri_lastuser),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
            }else{
                DB::table('hisdb.pat_mri')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'weight' => $request->mri_weight,
                        'entereddate' => $request->mri_entereddate,
                        'cardiacpacemaker' => $request->cardiacpacemaker,
                        'pros_valve' => $request->pros_valve,
                        'prosvalve_rmk' => $request->prosvalve_rmk,
                        'intraocular' => $request->intraocular,
                        'cochlear_imp' => $request->cochlear_imp,
                        'neurotransm' => $request->neurotransm,
                        'bonegrowth' => $request->bonegrowth,
                        'druginfuse' => $request->druginfuse,
                        'surg_clips' => $request->surg_clips,
                        'jointlimb_pros' => $request->jointlimb_pros,
                        'shrapnel' => $request->shrapnel,
                        'oper_3mth' => $request->oper_3mth,
                        'oper3mth_remark' => $request->oper3mth_remark,
                        'prev_mri' => $request->prev_mri,
                        'claustrophobia' => $request->claustrophobia,
                        'dental_imp' => $request->dental_imp,
                        'frmgnetic_imp' => $request->frmgnetic_imp,
                        'pregnancy' => $request->pregnancy,
                        'allergy_drug' => $request->allergy_drug,
                        'bloodurea' => $request->bloodurea,
                        'serum_creatinine' => $request->serum_creatinine,
                        'doctorname' => strtoupper($request->mri_doctorname),
                        'radiologist' => strtoupper($request->mri_radiologist),
                        'radiographer' => strtoupper($request->radiographer),
                        // 'staffnurse' => $request->staffnurse,
                        'adduser'  => strtoupper($request->mri_lastuser),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser' => strtoupper($request->mri_lastuser),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
            }
            
            DB::table('hisdb.episode')
                ->where('mrn','=',$request->mrn)
                ->where('episno','=',$request->episno)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'reff_rad' => '1',
                    'lastuser'  => strtoupper($request->mri_lastuser),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                ]);
            
            $queries = DB::getQueryLog();
            // dump($queries);
            
            DB::commit();
            
            $responce = new stdClass();
            
            return json_encode($responce);
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function accept_mri(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $pat_mri = DB::table('hisdb.pat_mri')
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno)
                        ->where('compcode','=',session('compcode'));
            
            if($pat_mri->exists()){
                $pat_mri
                    ->update([
                        'radiographer' => session('username'),
                        // 'upduser'  => session('username'),
                        // 'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        // 'lastuser' => session('username'),
                        // 'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        // 'computerid' => session('computerid'),
                    ]);
            }
            
            $queries = DB::getQueryLog();
            // dump($queries);
            
            DB::commit();
            
            $responce = new stdClass();
            
            return json_encode($responce);
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function get_table_mri(Request $request){
        
        $pat_mri_obj = DB::table('hisdb.pat_mri')
                        ->select('compcode','mrn','episno','weight as mri_weight','entereddate as mri_entereddate','cardiacpacemaker','pros_valve','prosvalve_rmk','intraocular','cochlear_imp','neurotransm','bonegrowth','druginfuse','surg_clips','jointlimb_pros','shrapnel','oper_3mth','oper3mth_remark','prev_mri','claustrophobia','dental_imp','frmgnetic_imp','pregnancy','allergy_drug','bloodurea','serum_creatinine','doctorname as mri_doctorname','radiologist as mri_radiologist','radiographer','staffnurse','adduser','adddate','upduser','upddate','lastuser as mri_lastuser','lastupdate','computerid')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno);
        
        // if(!empty($request->recorddate) && $request->recorddate != '-'){
        //     $pathealth_obj = DB::table('hisdb.pathealth')
        //                     ->select('weight')
        //                     ->where('compcode','=',session('compcode'))
        //                     ->where('recorddate','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate)->format('Y-m-d'))
        //                     ->where('recordtime','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate)->format('H:i:s'))
        //                     ->where('mrn','=',$request->mrn)
        //                     ->where('episno','=',$request->episno);
        // }
        
        // $nursassessment_obj = DB::table('nursing.nursassessment')
        //                     ->select('vs_weight')
        //                     ->where('compcode','=',session('compcode'))
        //                     ->where('mrn','=',$request->mrn)
        //                     ->where('episno','=',$request->episno);
        
        $responce = new stdClass();
        
        if($pat_mri_obj->exists()){
            $pat_mri_obj = $pat_mri_obj->first();
            $responce->pat_mri = $pat_mri_obj;
        }
        
        // if(!empty($request->recorddate) && $request->recorddate != '-'){
        //     if($pathealth_obj->exists()){
        //         $pathealth_obj = $pathealth_obj->first();
                
        //         $mri_weight = $pathealth_obj->weight;
        //         $responce->mri_weight = $mri_weight;
        //     }
        // }
        
        // if($nursassessment_obj->exists()){
        //     $nursassessment_obj = $nursassessment_obj->first();
            
        //     $mri_weight = $nursassessment_obj->vs_weight;
        //     $responce->mri_weight = $mri_weight;
        // }
        
        return json_encode($responce);
        
    }
    
    public function add_physio(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $pat_physio = DB::table('hisdb.pat_physio')
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno)
                        ->where('compcode','=',session('compcode'));
            
            if($pat_physio->exists()){
                $pat_physio
                    ->update([
                        'req_date' => $request->req_date,
                        'clinic_diag' => $request->clinic_diag,
                        'findings' => $request->findings,
                        // 'treatment' => $request->phy_treatment,
                        'tr_physio' => $request->tr_physio,
                        'tr_occuptherapy' => $request->tr_occuptherapy,
                        'tr_respiphysio' => $request->tr_respiphysio,
                        'tr_neuro' => $request->tr_neuro,
                        'tr_splint' => $request->tr_splint,
                        'remarks' => $request->remarks,
                        'doctorname' => strtoupper($request->phy_doctorname),
                        'upduser'  => strtoupper($request->phy_lastuser),
                        'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser' => strtoupper($request->phy_lastuser),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
            }else{
                DB::table('hisdb.pat_physio')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'req_date' => $request->req_date,
                        'clinic_diag' => $request->clinic_diag,
                        'findings' => $request->findings,
                        // 'treatment' => $request->phy_treatment,
                        'tr_physio' => $request->tr_physio,
                        'tr_occuptherapy' => $request->tr_occuptherapy,
                        'tr_respiphysio' => $request->tr_respiphysio,
                        'tr_neuro' => $request->tr_neuro,
                        'tr_splint' => $request->tr_splint,
                        'remarks' => $request->remarks,
                        'doctorname' => strtoupper($request->phy_doctorname),
                        'adduser'  => strtoupper($request->phy_lastuser),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser' => strtoupper($request->phy_lastuser),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
            }
            
            DB::table('hisdb.episode')
                ->where('mrn','=',$request->mrn)
                ->where('episno','=',$request->episno)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'reff_physio' => '1',
                    'lastuser'  => strtoupper($request->phy_lastuser),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function edit_physio(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $pat_physio = DB::table('hisdb.pat_physio')
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno)
                        ->where('compcode','=',session('compcode'));
            
            if($pat_physio->exists()){
                $pat_physio
                    ->update([
                        'req_date' => $request->req_date,
                        'clinic_diag' => $request->clinic_diag,
                        'findings' => $request->findings,
                        // 'treatment' => $request->phy_treatment,
                        'tr_physio' => $request->tr_physio,
                        'tr_occuptherapy' => $request->tr_occuptherapy,
                        'tr_respiphysio' => $request->tr_respiphysio,
                        'tr_neuro' => $request->tr_neuro,
                        'tr_splint' => $request->tr_splint,
                        'remarks' => $request->remarks,
                        'doctorname' => strtoupper($request->phy_doctorname),
                        'upduser'  => strtoupper($request->phy_lastuser),
                        'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser' => strtoupper($request->phy_lastuser),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
            }else{
                DB::table('hisdb.pat_physio')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'req_date' => $request->req_date,
                        'clinic_diag' => $request->clinic_diag,
                        'findings' => $request->findings,
                        // 'treatment' => $request->phy_treatment,
                        'tr_physio' => $request->tr_physio,
                        'tr_occuptherapy' => $request->tr_occuptherapy,
                        'tr_respiphysio' => $request->tr_respiphysio,
                        'tr_neuro' => $request->tr_neuro,
                        'tr_splint' => $request->tr_splint,
                        'remarks' => $request->remarks,
                        'doctorname' => strtoupper($request->phy_doctorname),
                        'adduser'  => strtoupper($request->phy_lastuser),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser' => strtoupper($request->phy_lastuser),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
            }
            
            DB::table('hisdb.episode')
                ->where('mrn','=',$request->mrn)
                ->where('episno','=',$request->episno)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'reff_physio' => '1',
                    'lastuser'  => strtoupper($request->phy_lastuser),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                ]);
            
            $queries = DB::getQueryLog();
            // dump($queries);
            
            DB::commit();
            
            $responce = new stdClass();
            
            return json_encode($responce);
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function get_table_physio(Request $request){
        
        $pat_physio_obj = DB::table('hisdb.pat_physio')
                        ->select('compcode','mrn','episno','req_date','clinic_diag','findings','treatment as phy_treatment','tr_physio','tr_occuptherapy','tr_respiphysio','tr_neuro','tr_splint','remarks','doctorname as phy_doctorname','adduser','adddate','upduser','upddate','lastuser as phy_lastuser','lastupdate','computerid')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno);
        
        $responce = new stdClass();
        
        if($pat_physio_obj->exists()){
            $pat_physio_obj = $pat_physio_obj->first();
            $responce->pat_physio = $pat_physio_obj;
        }
        
        return json_encode($responce);
        
    }
    
    public function add_dressing(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('hisdb.pat_dressing')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn,
                    'episno' => $request->episno,
                    'od_dressing' => $request->od_dressing,
                    'bd_dressing' => $request->bd_dressing,
                    'eod_dressing' => $request->eod_dressing,
                    'others_dressing' => $request->others_dressing,
                    'others_name' => $request->others_name,
                    'solution' => $request->solution,
                    'doctorname' => strtoupper($request->dressing_doctorname),
                    'adduser'  => strtoupper($request->dressing_lastuser),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastuser' => strtoupper($request->dressing_lastuser),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'computerid' => session('computerid'),
                ]);
            
            DB::table('hisdb.episode')
                ->where('mrn','=',$request->mrn)
                ->where('episno','=',$request->episno)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'reff_ed' => '1',
                    'lastuser'  => strtoupper($request->dressing_lastuser),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function edit_dressing(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $pat_dressing = DB::table('hisdb.pat_dressing')
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno)
                            ->where('compcode','=',session('compcode'));
            
            if($pat_dressing->exists()){
                $pat_dressing
                    ->update([
                        'od_dressing' => $request->od_dressing,
                        'bd_dressing' => $request->bd_dressing,
                        'eod_dressing' => $request->eod_dressing,
                        'others_dressing' => $request->others_dressing,
                        'others_name' => $request->others_name,
                        'solution' => $request->solution,
                        'doctorname' => strtoupper($request->dressing_doctorname),
                        'upduser'  => strtoupper($request->dressing_lastuser),
                        'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser' => strtoupper($request->dressing_lastuser),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
            }else{
                DB::table('hisdb.pat_dressing')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'od_dressing' => $request->od_dressing,
                        'bd_dressing' => $request->bd_dressing,
                        'eod_dressing' => $request->eod_dressing,
                        'others_dressing' => $request->others_dressing,
                        'others_name' => $request->others_name,
                        'solution' => $request->solution,
                        'doctorname' => strtoupper($request->dressing_doctorname),
                        'adduser'  => strtoupper($request->dressing_lastuser),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser' => strtoupper($request->dressing_lastuser),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
            }
            
            DB::table('hisdb.episode')
                ->where('mrn','=',$request->mrn)
                ->where('episno','=',$request->episno)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'reff_ed' => '1',
                    'lastuser'  => strtoupper($request->dressing_lastuser),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                ]);
            
            $queries = DB::getQueryLog();
            // dump($queries);
            
            DB::commit();
            
            $responce = new stdClass();
            
            return json_encode($responce);
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function get_table_dressing(Request $request){
        
        $pat_dressing_obj = DB::table('hisdb.pat_dressing')
                            ->select('compcode','mrn','episno','od_dressing','bd_dressing','eod_dressing','others_dressing','others_name','solution','doctorname as dressing_doctorname','adduser','adddate','upduser','upddate','lastuser as dressing_lastuser','lastupdate','computerid')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno);
        
        $responce = new stdClass();
        
        if($pat_dressing_obj->exists()){
            $pat_dressing_obj = $pat_dressing_obj->first();
            $responce->pat_dressing = $pat_dressing_obj;
        }
        
        return json_encode($responce);
        
    }

    public function add_preContrast(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('hisdb.pat_precontrastq')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn,
                    'episno' => $request->episno,
                    'examination' => $request->examination,
                    'hisAllergy' => $request->hisAllergy,
                    'feverAllergic' => $request->feverAllergic,
                    'prevReactContrast' => $request->prevReactContrast,
                    'prevReactDrug' => $request->prevReactDrug,
                    'asthma' => $request->asthma,
                    'heartDisease' => $request->heartDisease,
                    'veryOldYoung' => $request->veryOldYoung,
                    'poorCondition' => $request->poorCondition,
                    'dehydrated' => $request->dehydrated,
                    'seriousMedCondition' => $request->seriousMedCondition,
                    'prevContrastExam' => $request->prevContrastExam,
                    'consentProcedure' => $request->consentProcedure,
                    'LMP' => $request->LMP,
                    'renalFunction' => $request->renalFunction,
                    'docName' => $request->docName,
                    'adduser'  => session('username'),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur"),
                    'lastuser' => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur"),
                    'computerid' => session('computerid'),
                ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function edit_preContrast(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $pat_preContrast = DB::table('hisdb.pat_precontrastq')
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno)
                            ->where('compcode','=',session('compcode'));
            
            if($pat_preContrast->exists()){
                $pat_preContrast
                    ->update([
                        'examination' => $request->examination,
                        'hisAllergy' => $request->hisAllergy,
                        'feverAllergic' => $request->feverAllergic,
                        'prevReactContrast' => $request->prevReactContrast,
                        'prevReactDrug' => $request->prevReactDrug,
                        'asthma' => $request->asthma,
                        'heartDisease' => $request->heartDisease,
                        'veryOldYoung' => $request->veryOldYoung,
                        'poorCondition' => $request->poorCondition,
                        'dehydrated' => $request->dehydrated,
                        'seriousMedCondition' => $request->seriousMedCondition,
                        'prevContrastExam' => $request->prevContrastExam,
                        'consentProcedure' => $request->consentProcedure,
                        'LMP' => $request->LMP,
                        'renalFunction' => $request->renalFunction,
                        'docName' => $request->docName,
                        'upduser'  => session('username'),
                        'upddate'  => Carbon::now("Asia/Kuala_Lumpur"),
                        'lastuser' => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur"),
                        'lastcomputerid' => session('computerid'),
                    ]);
            }else{
                DB::table('hisdb.pat_precontrastq')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'examination' => $request->examination,
                        'hisAllergy' => $request->hisAllergy,
                        'feverAllergic' => $request->feverAllergic,
                        'prevReactContrast' => $request->prevReactContrast,
                        'prevReactDrug' => $request->prevReactDrug,
                        'asthma' => $request->asthma,
                        'heartDisease' => $request->heartDisease,
                        'veryOldYoung' => $request->veryOldYoung,
                        'poorCondition' => $request->poorCondition,
                        'dehydrated' => $request->dehydrated,
                        'seriousMedCondition' => $request->seriousMedCondition,
                        'prevContrastExam' => $request->prevContrastExam,
                        'consentProcedure' => $request->consentProcedure,
                        'LMP' => $request->LMP,
                        'renalFunction' => $request->renalFunction,
                        'docName' => $request->docName,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur"),
                        'lastuser' => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur"),
                        'computerid' => session('computerid'),
                    ]);
            }
            
            $queries = DB::getQueryLog();
            // dump($queries);
            
            DB::commit();
            
            $responce = new stdClass();
            
            return json_encode($responce);
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function get_table_preContrast(Request $request){
        
        $pat_preContrast_obj = DB::table('hisdb.pat_precontrastq')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno);
        
        $responce = new stdClass();
        
        if($pat_preContrast_obj->exists()){
            $pat_preContrast_obj = $pat_preContrast_obj->first();
            $responce->pat_preContrast = $pat_preContrast_obj;
        }
        
        return json_encode($responce);
        
    }

    public function add_consentForm(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('hisdb.pat_consent')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn,
                    'episno' => $request->episno,
                    'guardianName' => $request->guardianName,
                    'address' => $request->address,
                    'procedureName' => $request->procedureName,
                    'guardianType' => $request->guardianType,
                    'patientName' => $request->patientName,
                    'procedureRadName' => $request->procedureRadName,
                    'doctorName' => $request->doctorName,
                    'dateConsentGuardian' => $request->dateConsentGuardian,
                    'guardianSignType' => $request->guardianSignType,
                    'guardianSign' => $request->guardianSign,
                    'relationship' => $request->relationship,
                    'guardianICNum' => $request->guardianICNum,
                    'guardianSignTypeDoc' => $request->guardianSignTypeDoc,
                    'dateConsentDoc' => $request->dateConsentDoc,
                    'doctorSign' => $request->doctorSign,
                    'adduser'  => session('username'),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur"),
                    'lastuser' => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur"),
                    'computerid' => session('computerid'),
                ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function edit_consentForm(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $pat_consent = DB::table('hisdb.pat_consent')
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno)
                            ->where('compcode','=',session('compcode'));
            
            if($pat_consent->exists()){
                $pat_consent
                    ->update([
                        'guardianName' => $request->guardianName,
                        'address' => $request->address,
                        'procedureName' => $request->procedureName,
                        'guardianType' => $request->guardianType,
                        'patientName' => $request->patientName,
                        'procedureRadName' => $request->procedureRadName,
                        'doctorName' => $request->doctorName,
                        'dateConsentGuardian' => $request->dateConsentGuardian,
                        'guardianSign' => $request->guardianSign,
                        'guardianSignType' => $request->guardianSignType,
                        'relationship' => $request->relationship,
                        'guardianICNum' => $request->guardianICNum,
                        'guardianSignTypeDoc' => $request->guardianSignTypeDoc,
                        'dateConsentDoc' => $request->dateConsentDoc,
                        'doctorSign' => $request->doctorSign,
                        'upduser'  => session('username'),
                        'upddate'  => Carbon::now("Asia/Kuala_Lumpur"),
                        'lastuser' => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur"),
                        'lastcomputerid' => session('computerid'),
                    ]);
            }else{
                DB::table('hisdb.pat_consent')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'guardianName' => $request->guardianName,
                        'address' => $request->address,
                        'procedureName' => $request->procedureName,
                        'guardianType' => $request->guardianType,
                        'patientName' => $request->patientName,
                        'procedureRadName' => $request->procedureRadName,
                        'doctorName' => $request->doctorName,
                        'dateConsentGuardian' => $request->dateConsentGuardian,
                        'guardianSign' => $request->guardianSign,
                        'guardianSignType' => $request->guardianSignType,
                        'relationship' => $request->relationship,
                        'guardianICNum' => $request->guardianICNum,
                        'guardianSignTypeDoc' => $request->guardianSignTypeDoc,
                        'dateConsentDoc' => $request->dateConsentDoc,
                        'doctorSign' => $request->doctorSign,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur"),
                        'lastuser' => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur"),
                        'computerid' => session('computerid'),
                    ]);
            }
            
            $queries = DB::getQueryLog();
            // dump($queries);
            
            DB::commit();
            
            $responce = new stdClass();
            
            return json_encode($responce);
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function get_table_consentForm(Request $request){
        
        $pat_consentForm_obj = DB::table('hisdb.pat_consent')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno);
        
        $responce = new stdClass();
        
        if($pat_consentForm_obj->exists()){
            $pat_consentForm_obj = $pat_consentForm_obj->first();
            $responce->pat_consentForm = $pat_consentForm_obj;
        }
        
        return json_encode($responce);
        
    }
    
    public function dialog_icd(Request $request){
        
        $icdver = DB::table('sysdb.sysparam')
                ->select('pvalue1')
                ->where('compcode','=',session('compcode'))
                ->where('source','=','MR')
                ->where('trantype','=','ICD')
                ->first();
        
        $table = DB::table('hisdb.diagtab')
                ->where('type','=',$icdver->pvalue1)
                ->orderBy('idno','asc');
        
        if(!empty($request->searchCol)){
            $searchCol_array = $request->searchCol;
            
            $count = array_count_values($searchCol_array);
            // dump($count);
            
            foreach($count as $key => $value){
                $occur_ar = $this->index_of_occurance($key,$searchCol_array);
                
                $table = $table->where(function ($table) use ($request,$searchCol_array,$occur_ar){
                    foreach ($searchCol_array as $key => $value) {
                        $found = array_search($key,$occur_ar);
                        if($found !== false){
                            $table->Where($searchCol_array[$key],'like',$request->searchVal[$key]);
                        }
                    }
                });
            }
        }
        
        $paginate = $table->paginate($request->rows);
        
        $responce = new stdClass();
        $responce->page = $paginate->currentPage();
        $responce->total = $paginate->lastPage();
        $responce->records = $paginate->total();
        $responce->rows = $paginate->items();
        $responce->sql = $table->toSql();
        $responce->sql_bind = $table->getBindings();
        
        return json_encode($responce);
        
    }
    
    public function get_bp_graph(Request $request){
        
        // $table = DB::table('hisdb.bp_graph')
        //         ->get();
        
        $table = DB::table('nursing.nurshandover')
                ->select()
                ->where('compcode',session('compcode'))
                ->where('mrn',$request->mrn)
                ->where('episno',$request->episno)
                ->get();
        
        $responce = new stdClass();
        $responce->data = $table;
        return json_encode($responce);
        
    }
    
    public function add_notes(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            // $doctorcode_obj = DB::table('hisdb.doctor')
            //                 ->select('doctorcode')
            //                 ->where('compcode','=',session('compcode'))
            //                 ->where('loginid','=',session('username'));
            
            // $doctorcode = null;
            // if($doctorcode_obj->exists()){
            //     $doctorcode = $doctorcode_obj->first()->doctorcode;
            // }
            
            $users = DB::table('sysdb.users')
                    ->where('compcode','=',session('compcode'))
                    ->where('username','=',session('username'))
                    ->first();
            
            DB::table('hisdb.pathealthadd')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn,
                    'episno' => $request->episno,
                    'additionalnote' => $request->additionalnote,
                    'doctorcode'  => $users->doctorcode,
                    'adduser'  => session('username'),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur")
                ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
        
    }
    
    public function doctornote_transaction_save(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $table = DB::table('hisdb.chargetrx');
            
            $chgmast = DB::table('hisdb.chgmast')
                        ->where('compcode','=',session('compcode'))
                        ->where('chgcode','=',$request->chg_desc)
                        ->first();
            
            $episode = DB::table('hisdb.episode')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno)
                        ->first();
            
            $isudept = $episode->regdept;
            
            if($request->oper == 'edit'){
                $table->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno)
                        ->where('id','=',$request->id);
                
                $array_edit = [
                    'chgcode' => $request->chg_desc,
                    'chggroup' =>  $chgmast->chggroup,
                    'chgtype' =>  $chgmast->chgtype,
                    'quantity' => $request->quantity,
                    'instruction' => $request->ins_desc,
                    'doscode' => $request->dos_desc,
                    'frequency' => $request->fre_desc,
                    'drugindicator' => $request->dru_desc,
                    'remarks' => $request->remarks,
                    'lastuser' => Auth::user()->username,
                    'lastupdate' => Carbon::now("Asia/Kuala_Lumpur")
                ];
                
                $table->update($array_edit);
            }else if($request->oper == 'add'){
                $array_insert = [
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn,
                    'episno' => $request->episno,
                    'trxtype' => 'OE',
                    'trxdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'chgcode' => $request->chg_desc,
                    'chggroup' =>  $chgmast->chggroup,
                    'chgtype' =>  $chgmast->chgtype,
                    'instruction' => $request->ins_desc,
                    'doscode' => $request->dos_desc,
                    'frequency' => $request->fre_desc,
                    'drugindicator' => $request->dru_desc,
                    'remarks' => $request->remarks,
                    'billflag' => '0',
                    'quantity' => $request->quantity,
                    'isudept' => $isudept,
                    'trxtime' => Carbon::now("Asia/Kuala_Lumpur"),
                    'lastuser' => session('username'),
                    'lastupdate' => Carbon::now("Asia/Kuala_Lumpur")
                ];
                
                $table->insert($array_insert);
            }else if($request->oper == 'del'){
                $table->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno)
                        ->where('id','=',$request->id)->delete();
            }
            
            $responce = new stdClass();
            $responce->success = 'success';
            echo json_encode($responce);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
        
    }
    
    public function iograph(Request $request){
        
        if(empty($request->mrn) || empty($request->episno)){
            abort(404);
        }
        
        $recorddate = DB::table('nursing.intakeoutput')
                    ->select('mrn','episno','recorddate')
                    ->where('compcode',session('compcode'))
                    ->where('mrn',$request->mrn)
                    ->where('episno',$request->episno)
                    ->get();
        
        $intakeoutput = [];
        foreach($recorddate as $key => $value){
            $io_data = DB::table('nursing.intakeoutput')
                        ->where('compcode',session('compcode'))
                        ->where('mrn',$value->mrn)
                        ->where('episno',$value->episno)
                        ->where('recorddate',$value->recorddate)
                        ->first();
            
            array_push($intakeoutput, $io_data);
        }
        
        $pat_mast = DB::table('hisdb.pat_mast')
                    ->where('CompCode',session('compcode'))
                    ->where('MRN','=',$request->mrn)
                    ->first();
        
        // dd($intakeoutput);
        
        // return view('hisdb.doctornote.iograph_pdfmake',compact('recorddate','intakeoutput','pat_mast'));
        return view('hisdb.doctornote.intakeoutput_pdfmake',compact('recorddate','intakeoutput','pat_mast'));
        
    }
    
    public function showpdf(Request $request){
        
        $mrn = $request->mrn;
        $episno = $request->episno;
        if(empty($mrn) || empty($episno)){
            abort(404);
        }
        
        $patreferral = DB::table('hisdb.patreferral as ptrf')
                        ->select('ptrf.idno','ptrf.compcode','ptrf.mrn','ptrf.episno','ptrf.adduser','ptrf.adddate','ptrf.upduser','ptrf.upddate','ptrf.computerid','ptrf.refdate','ptrf.refaddress','ptrf.refdoc','ptrf.reftitle','ptrf.refdiag','ptrf.refplan','ptrf.refprescription','pm.Name','pm.Newic')
                        ->leftJoin('hisdb.pat_mast as pm', function ($join) use ($request){
                            $join = $join->on('pm.MRN', '=', 'ptrf.mrn')
                                        ->where('pm.compcode','=',session('compcode'));
                        })
                        ->where('ptrf.compcode','=',session('compcode'))
                        ->where('ptrf.mrn','=',$mrn)
                        ->where('ptrf.episno','=',$episno)
                        ->first();
        
        $company = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();
        
        $ini_array = [
            'docname' => $patreferral->refdoc,
            'name' => $patreferral->Name,
            'newic' => $patreferral->Newic,
            'reftitle' => $patreferral->reftitle,
            'reffor' => $patreferral->refdiag,
            'exam' => $patreferral->refplan,
            'invest' => $patreferral->refprescription,
            'refdate' => $patreferral->refdate
        ];
        
        return view('hisdb.doctornote.refLetter_pdfmake',compact('ini_array'));
        
    }
    
    public function otbook_chart(Request $request){
        
        $mrn = $request->mrn;
        $episno = $request->episno;
        if(!$mrn || !$episno){
            abort(404);
        }
        
        $pat_otbook = DB::table('hisdb.pat_otbook as ot')
                    ->select('ot.compcode','ot.mrn','ot.episno','ot.op_date','ot.oper_type','ot.adm_type','ot.anaesthetist','ot.remarks','ot.doctorname','ot.adduser','ot.adddate','ot.upduser','ot.upddate','ot.lastuser','ot.lastupdate','ot.computerid','pm.Name','pm.Newic','e.pay_type','e.pyrmode','ep.payercode','dm.name AS debtor_name','g.staffid')
                    ->leftJoin('hisdb.pat_mast as pm', function ($join) use ($request){
                        $join = $join->on('pm.MRN','=','ot.mrn')
                                    ->on('pm.Episno','=','ot.episno')
                                    ->where('pm.CompCode','=',session('compcode'));
                    })
                    ->leftJoin('hisdb.episode as e', function ($join) use ($request){
                        $join = $join->on('e.mrn','=','ot.mrn')
                                    ->on('e.episno','=','ot.episno')
                                    ->where('e.compcode','=',session('compcode'));
                    })
                    ->leftJoin('hisdb.epispayer as ep', function ($join) use ($request){
                        $join = $join->on('ep.mrn','=','ot.mrn')
                                    ->on('ep.episno','=','ot.episno')
                                    ->where('ep.compcode','=',session('compcode'));
                    })
                    ->leftJoin('debtor.debtormast as dm', function ($join) use ($request){
                        $join = $join->on('dm.debtorcode','=','ep.payercode')
                                    ->where('dm.compcode','=',session('compcode'));
                    })
                    ->leftJoin('hisdb.guarantee as g', function ($join) use ($request){
                        $join = $join->on('g.mrn','=','ot.mrn')
                                    ->on('g.episno','=','ot.episno')
                                    ->where('g.compcode','=',session('compcode'));
                    })
                    ->where('ot.compcode','=',session('compcode'))
                    ->where('ot.mrn','=',$mrn)
                    ->where('ot.episno','=',$episno)
                    ->first();
        // dd($pat_otbook);
        
        return view('hisdb.doctornote.otbookChart_pdfmake', compact('pat_otbook'));
        
    }
    
    public function radClinic_chart(Request $request){
        
        $mrn = $request->mrn;
        $episno = $request->episno;
        if(!$mrn || !$episno){
            abort(404);
        }
        
        $pat_radiology = DB::table('hisdb.pat_radiology as r')
                        ->select('r.idno','r.compcode','r.mrn','r.episno','r.iPesakit as r_iPesakit','r.weight','r.pt_condition','r.LMP','r.xray','r.xray_date','r.xray_remark','r.mri','r.mri_date','r.mri_remark','r.angio','r.angio_date','r.angio_remark','r.ultrasound','r.ultrasound_date','r.ultrasound_remark','r.ct','r.ct_date','r.ct_remark','r.fluroscopy','r.fluroscopy_date','r.fluroscopy_remark','r.mammogram','r.mammogram_date','r.mammogram_remark','r.bmd','r.bmd_date','r.bmd_remark','r.clinicaldata','r.doctorname','r.rad_note','r.radiologist','r.adduser','r.adddate','r.upduser','r.upddate','r.lastuser','r.lastupdate','r.computerid','pm.iPesakit','pm.Name','pm.Address1','pm.Address2','pm.Address3','pm.Postcode','pm.telhp','pm.Newic','pm.Sex','pm.RaceCode','ep.reg_date','ep.newcaseP','ep.newcaseNP','ep.followupP','ep.followupNP','b.ward as EpWard','his.allergyh')
                        ->leftjoin('hisdb.pat_mast as pm', function ($join){
                            $join = $join->on('pm.MRN','=','r.mrn');
                            $join = $join->on('pm.Episno','=','r.episno');
                            $join = $join->where('pm.compcode','=',session('compcode'));
                        })
                        ->leftjoin('hisdb.episode as ep', function ($join){
                            $join = $join->on('ep.mrn','=','r.mrn');
                            $join = $join->on('ep.episno','=','r.episno');
                            $join = $join->where('ep.compcode','=',session('compcode'));
                        })
                        ->leftjoin('hisdb.bed as b', function ($join){
                            $join = $join->on('b.bednum','=','ep.bed');
                            $join = $join->where('b.compcode','=',session('compcode'));
                        })
                        ->leftjoin('hisdb.pathistory as his', function ($join){
                            $join = $join->on('his.mrn','=','r.mrn');
                            $join = $join->where('his.compcode','=',session('compcode'));
                        })
                        ->where('r.compcode','=',session('compcode'))
                        ->where('r.mrn','=',$mrn)
                        ->where('r.episno','=',$episno)
                        ->first();
        // dd($pat_radiology);
        
        $age = $request->age;
        
        $company = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();
        
        return view('hisdb.doctornote.radClinicChart_pdfmake',compact('pat_radiology','age'));
        
    }
    
    public function mri_chart(Request $request){
        
        $mrn = $request->mrn;
        $episno = $request->episno;
        
        $mri = DB::table('hisdb.pat_mri as ptm')
                ->select('ptm.mrn','ptm.episno','ptm.weight as mri_weight','ptm.entereddate','ptm.cardiacpacemaker','ptm.pros_valve','ptm.prosvalve_rmk','ptm.intraocular','ptm.cochlear_imp','ptm.neurotransm','ptm.bonegrowth','ptm.druginfuse','ptm.surg_clips','ptm.jointlimb_pros','ptm.shrapnel','ptm.oper_3mth','ptm.oper3mth_remark','ptm.prev_mri','ptm.claustrophobia','ptm.dental_imp','ptm.frmgnetic_imp','ptm.pregnancy','ptm.allergy_drug','ptm.bloodurea','ptm.serum_creatinine','ptm.doctorname as mri_doctorname','ptm.radiologist','ptm.radiographer','ptm.staffnurse','ptm.adduser','ptm.adddate','ptm.upduser','ptm.upddate','ptm.lastuser as mri_lastuser','ptm.lastupdate','pm.Name','pm.Newic','pm.telhp','pm.telh','ph.weight','n.vs_weight','e.bed as ward','b.ward as EpWard')
                ->leftjoin('hisdb.pat_mast as pm', function ($join){
                    $join = $join->on('pm.MRN','=','ptm.mrn');
                    $join = $join->on('pm.Episno','=','ptm.episno');
                    $join = $join->where('pm.compcode','=',session('compcode'));
                })
                ->leftjoin('hisdb.pathealth as ph', function ($join){
                    $join = $join->on('ph.mrn','=','ptm.mrn');
                    $join = $join->on('ph.episno','=','ptm.episno');
                    $join = $join->where('ph.compcode','=',session('compcode'));
                })
                ->leftjoin('nursing.nursassessment as n', function ($join){
                    $join = $join->on('n.mrn','=','ptm.mrn');
                    $join = $join->on('n.episno','=','ptm.episno');
                    $join = $join->where('n.compcode','=',session('compcode'));
                })
                ->leftjoin('hisdb.episode as e', function ($join){
                    $join = $join->on('e.mrn','=','ptm.mrn');
                    $join = $join->on('e.episno','=','ptm.episno');
                    $join = $join->where('e.compcode','=',session('compcode'));
                })
                ->leftjoin('hisdb.bed as b', function ($join){
                    $join = $join->on('b.bednum','=','e.bed');
                    $join = $join->where('b.compcode','=',session('compcode'));
                })
                ->where('ptm.compcode','=',session('compcode'))
                ->where('ptm.mrn','=',$mrn)
                ->where('ptm.episno','=',$episno)
                ->first();
        // dd($mri);
        
        $company = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();
        
        return view('hisdb.doctornote.mriChart_pdfmake',compact('mri'));
        
    }
    
    public function physio_chart(Request $request){
        
        $mrn = $request->mrn;
        $episno = $request->episno;
        if(!$mrn || !$episno){
            abort(404);
        }
        
        $pat_physio = DB::table('hisdb.pat_physio as p')
                    ->select('p.idno','p.compcode','p.mrn','p.episno','p.req_date','p.clinic_diag','p.findings','p.treatment','p.tr_physio','p.tr_occuptherapy','p.tr_respiphysio','p.tr_neuro','p.tr_splint','p.remarks','p.doctorname','p.adduser','p.adddate','p.upduser','p.upddate','p.lastuser','p.lastupdate','p.computerid','pm.Name','pm.Address1','pm.Address2','pm.Address3','pm.Postcode','pm.telhp','pm.Newic','pm.Sex','ep.reg_date','b.ward as EpWard')
                    ->leftjoin('hisdb.pat_mast as pm', function ($join){
                        $join = $join->on('pm.MRN','=','p.mrn');
                        $join = $join->on('pm.Episno','=','p.episno');
                        $join = $join->where('pm.compcode','=',session('compcode'));
                    })
                    ->leftjoin('hisdb.episode as ep', function ($join){
                        $join = $join->on('ep.mrn','=','p.mrn');
                        $join = $join->on('ep.episno','=','p.episno');
                        $join = $join->where('ep.compcode','=',session('compcode'));
                    })
                    ->leftjoin('hisdb.bed as b', function ($join){
                        $join = $join->on('b.bednum','=','ep.bed');
                        $join = $join->where('b.compcode','=',session('compcode'));
                    })
                    ->where('p.compcode','=',session('compcode'))
                    ->where('p.mrn','=',$mrn)
                    ->where('p.episno','=',$episno)
                    ->first();
        // dd($pat_physio);
        
        $company = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();
        
        return view('hisdb.doctornote.physioChart_pdfmake',compact('pat_physio'));
        
    }
    
    public function dressing_chart(Request $request){
        
        $mrn = $request->mrn;
        $episno = $request->episno;
        
        $dressing = DB::table('hisdb.pat_dressing as d')
                    ->select('d.mrn','d.episno','d.od_dressing','d.bd_dressing','d.eod_dressing','d.others_dressing','d.others_name','d.solution','d.doctorname','d.adduser','d.adddate','d.upduser','d.upddate','d.lastuser','d.lastupdate','d.computerid','pm.Name','pm.Newic')
                    ->leftjoin('hisdb.pat_mast as pm', function ($join){
                        $join = $join->on('pm.MRN','=','d.mrn');
                        $join = $join->on('pm.Episno','=','d.episno');
                        $join = $join->where('pm.compcode','=',session('compcode'));
                    })
                    ->where('d.compcode','=',session('compcode'))
                    ->where('d.mrn','=',$mrn)
                    ->where('d.episno','=',$episno)
                    ->first();
        // dd($dressing);
        
        $company = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();
        
        return view('hisdb.doctornote.dressingChart_pdfmake',compact('dressing'));
        
    }

    public function preContrast_chart(Request $request){
        
        $mrn = $request->mrn;
        $episno = $request->episno;
        if(!$mrn || !$episno){
            abort(404);
        }
        
        $preContrast = DB::table('hisdb.pat_precontrastq as p')
                ->select('p.mrn','p.episno','p.examination','p.hisAllergy','p.feverAllergic','p.prevReactContrast','p.prevReactDrug','p.asthma','p.heartDisease','p.veryOldYoung','p.poorCondition','p.dehydrated','p.seriousMedCondition','p.prevContrastExam','p.consentProcedure','p.LMP','p.renalFunction','p.docName','pm.Name','pm.Newic')
                ->leftjoin('hisdb.pat_mast as pm', function ($join){
                    $join = $join->on('pm.MRN','=','p.mrn');
                    $join = $join->on('pm.Episno','=','p.episno');
                    $join = $join->where('pm.compcode','=',session('compcode'));
                })
                ->where('p.compcode','=',session('compcode'))
                ->where('p.mrn','=',$mrn)
                ->where('p.episno','=',$episno)
                ->first();
        // dd($preContrast);
        $age = $request->age;

        $company = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();
        
        return view('hisdb.doctornote.preContrastChart_pdfmake',compact('preContrast','age'));
        
    }

    public function consentForm_chart(Request $request){
        
        $mrn = $request->mrn;
        $episno = $request->episno;
        if(!$mrn || !$episno){
            abort(404);
        }
        
        $consentForm = DB::table('hisdb.pat_consent as p')
                ->select('p.mrn','p.episno','p.guardianName','p.address','p.procedureName','p.guardianType','p.patientName','p.procedureRadName','p.doctorName','p.dateConsentGuardian','p.guardianSign','p.guardianSignType','p.relationship','p.guardianICNum','p.guardianSignTypeDoc','p.dateConsentDoc','p.doctorSign','pm.Name','pm.Newic')
                ->leftjoin('hisdb.pat_mast as pm', function ($join){
                    $join = $join->on('pm.MRN','=','p.mrn');
                    $join = $join->on('pm.Episno','=','p.episno');
                    $join = $join->where('pm.compcode','=',session('compcode'));
                })
                ->where('p.compcode','=',session('compcode'))
                ->where('p.mrn','=',$mrn)
                ->where('p.episno','=',$episno)
                ->first();
        // dd($consentForm);

        $company = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();
        
        return view('hisdb.doctornote.consentFormChart_pdfmake',compact('consentForm'));
        
    }
    
}