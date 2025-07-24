<?php

namespace App\Http\Controllers\hisdb;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;
use PDF;

class ClientProgressNoteController extends defaultController
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
        return view('hisdb.clientprogressnote.clientprogressnote');
    }
    
    public function table(Request $request)
    {
        switch($request->action){
            case 'get_datetime_clientprognote':
                return $this->get_datetime_clientprognote($request);
            
            case 'get_table_clientprognote':
                return $this->get_table_clientprognote($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function form(Request $request)
    {
        DB::enableQueryLog();
        
        switch($request->action){
            case 'save_table_clientprognote':
                switch($request->oper){
                    case 'add':
                        return $this->add($request);
                    case 'edit':
                        return $this->edit($request);
                    default:
                        return 'error happen..';
                }

            case 'save_refLetterClientProgNote':
                switch($request->oper){
                    case 'add':
                        return $this->add_refLetterClientProgNote($request);
                    case 'edit':
                        return $this->edit_refLetterClientProgNote($request);
                    default:
                        return 'error happen..';
                }
            
            case 'get_table_clientprognote':
                return $this->get_table_clientprognote($request);

            case 'get_table_refLetterClientProgNote':
                return $this->get_table_refLetterClientProgNote($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function add(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            // $doctorcode_obj = DB::table('hisdb.doctor')
            //                 ->select('doctorcode')
            //                 ->where('compcode','=',session('compcode'))
            //                 ->where('loginid','=',session('username'));
            
            // get doctorname from episode.admdoctor
            $doctorcode_obj = DB::table('hisdb.episode')
                            ->select('admdoctor')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn_clientProgNote)
                            ->where('episno','=',$request->episno_clientProgNote);
            
            $doctorcode = null;
            if($doctorcode_obj->exists()){
                // $doctorcode = $doctorcode_obj->first()->doctorcode;
                $doctorcode = $doctorcode_obj->first()->admdoctor;
            }
            
            if($request->epistycode_clientProgNote == 'OP'){
                $plan = $request->plan;
            }else if($request->epistycode_clientProgNote == 'IP'){
                $plan = null;
            }
            
            DB::table('hisdb.patprogressnote')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn_clientProgNote,
                    'episno' => $request->episno_clientProgNote,
                    // 'datetaken' => Carbon::now("Asia/Kuala_Lumpur"),
                    'datetaken' => $request->datetaken,
                    // 'timetaken' => Carbon::now("Asia/Kuala_Lumpur"),
                    'timetaken' => $request->timetaken,
                    'progressnote' => $request->progressnote,
                    'plan' => $plan,
                    'doctorcode'  => $doctorcode,
                    // 'doctorcode'  => session('username'),
                    'adduser'  => session('username'),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastuser'  => session('username'),
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
            
            $patprogressnote = DB::table('hisdb.patprogressnote')
                                ->where('mrn','=',$request->mrn_clientProgNote)
                                ->where('episno','=',$request->episno_clientProgNote)
                                ->where('datetaken','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->datetime_clientProgNote)->format('Y-m-d'))
                                ->where('timetaken','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->datetime_clientProgNote)->format('H:i:s'))
                                ->where('compcode','=',session('compcode'));
            
            // $doctorcode_obj = DB::table('hisdb.doctor')
            //                 ->select('doctorcode')
            //                 ->where('compcode','=',session('compcode'))
            //                 ->where('loginid','=',session('username'));
            
            // get doctorname from episode.admdoctor
            $doctorcode_obj = DB::table('hisdb.episode')
                            ->select('admdoctor')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn_clientProgNote)
                            ->where('episno','=',$request->episno_clientProgNote);
            
            $doctorcode = null;
            if($doctorcode_obj->exists()){
                // $doctorcode = $doctorcode_obj->first()->doctorcode;
                $doctorcode = $doctorcode_obj->first()->admdoctor;
            }
            
            if($request->epistycode_clientProgNote == 'OP'){
                $plan = $request->plan;
            }else if($request->epistycode_clientProgNote == 'IP'){
                $plan = null;
            }
            
            if($patprogressnote->exists()){
                $patprogressnote
                    ->update([
                        // 'datetaken' => $request->datetaken,
                        'timetaken' => $request->timetaken,
                        'progressnote' => $request->progressnote,
                        'plan' => $plan,
                        'upduser'  => session('username'),
                        'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
            }else{
                DB::table('hisdb.patprogressnote')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_clientProgNote,
                        'episno' => $request->episno_clientProgNote,
                        // 'datetaken' => Carbon::now("Asia/Kuala_Lumpur"),
                        'datetaken' => $request->datetaken,
                        // 'timetaken' => Carbon::now("Asia/Kuala_Lumpur"),
                        'timetaken' => $request->timetaken,
                        'progressnote' => $request->progressnote,
                        'plan' => $plan,
                        'doctorcode'  => $doctorcode,
                        // 'doctorcode'  => session('username'),
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
            }
            
            $queries = DB::getQueryLog();
            // dump($queries);
            
            DB::commit();
            
            $responce = new stdClass();
            $responce->mrn = $request->mrn_clientProgNote;
            $responce->episno = $request->episno_clientProgNote;
            $responce->datetime = $request->datetime_clientProgNote;
            
            return json_encode($responce);
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function get_datetime_clientprognote(Request $request){
        
        $responce = new stdClass();
        
        $patprogressnote_obj = DB::table('hisdb.patprogressnote')
                                ->select('mrn','episno','datetaken','timetaken','adduser')
                                ->where('compcode','=',session('compcode'))
                                ->where('mrn','=',$request->mrn)
                                ->where('episno','=',$request->episno);
        
        if(!$patprogressnote_obj->exists()){
            $responce->data = [];
            return json_encode($responce);
        }
        
        $episode_obj = DB::table('hisdb.episode as e')
                        ->select('e.mrn','e.episno','e.admdoctor','p.datetaken','p.timetaken','p.doctorcode','p.adduser','d.doctorname as docname','doc.doctorname')
                        ->leftJoin('hisdb.patprogressnote as p', function ($join) use ($request){
                            $join = $join->on('p.doctorcode','=','e.admdoctor');
                            $join = $join->on('p.mrn','=','e.mrn');
                            $join = $join->on('p.episno','=','e.episno');
                            $join = $join->on('p.compcode','=','e.compcode');
                        })->leftJoin('hisdb.doctor as d', function ($join) use ($request){
                            $join = $join->on('d.doctorcode','=','e.admdoctor');
                            $join = $join->on('d.compcode','=','e.compcode');
                        })->leftJoin('hisdb.doctor as doc', function ($join) use ($request){
                            $join = $join->on('doc.doctorcode','=','p.doctorcode');
                            $join = $join->on('doc.compcode','=','p.compcode');
                        })
                        ->where('e.compcode','=',session('compcode'))
                        ->where('e.mrn','=',$request->mrn)
                        ->where('e.episno','=',$request->episno)
                        ->orderBy('p.idno','desc');
        
        if($episode_obj->exists()){
            $episode_obj = $episode_obj->get();
            
            $data = [];
            
            foreach($episode_obj as $key => $value){
                if(!empty($value->datetaken)){
                    $date['date'] =  Carbon::createFromFormat('Y-m-d', $value->datetaken)->format('d-m-Y').' '.Carbon::createFromFormat('H:i:s', $value->timetaken)->format('h:i A');
                }else{
                    $date['date'] =  '-';
                }
                $date['mrn'] = $value->mrn;
                $date['episno'] = $value->episno;
                if(!empty($value->datetaken)){ // for sorting - easier in 24H
                    $date['recdatetime'] =  Carbon::createFromFormat('Y-m-d', $value->datetaken)->format('d-m-Y').' '.$value->timetaken;
                }else{
                    $date['recdatetime'] =  '-';
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
    
    public function get_table_clientprognote(Request $request){
        
        $responce = new stdClass();
        
        // $episode_obj = DB::table('hisdb.episode')
        //                 ->select('diagfinal')
        //                 ->where('compcode','=',session('compcode'))
        //                 ->where('mrn','=',$request->mrn)
        //                 ->where('episno','=',$request->episno);
        
        if(!empty($request->datetime) && $request->datetime != '-'){
            $patprogressnote_obj = DB::table('hisdb.patprogressnote')
                                    ->select('idno','compcode','mrn','episno','datetaken','timetaken','progressnote','plan','doctorcode','adduser','adddate','upduser','upddate','lastuser','lastupdate','computerid')
                                    ->where('compcode','=',session('compcode'))
                                    ->where('mrn','=',$request->mrn)
                                    ->where('episno','=',$request->episno)
                                    ->where('datetaken','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->datetime)->format('Y-m-d'))
                                    ->where('timetaken','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->datetime)->format('H:i:s'));
        }
        
        // if($episode_obj->exists()){
        //     $episode_obj = $episode_obj->first();
        //     $responce->episode = $episode_obj;
        // }
        
        if(!empty($request->datetime) && $request->datetime != '-'){
            if($patprogressnote_obj->exists()){
                $patprogressnote_obj = $patprogressnote_obj->first();
                $responce->patprogressnote = $patprogressnote_obj;
            }
        }
        
        return json_encode($responce);
        
    }
    
    public function add_refLetterClientProgNote(Request $request){
        
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
                    'reftype' => $request->reftype,
                ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function edit_refLetterClientProgNote(Request $request){
        
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
                        'reftype' => $request->reftype,
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
    
    public function get_table_refLetterClientProgNote(Request $request){
        
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

        if(!empty($request->datetime) && $request->datetime != '-'){
            $patprogressnote_obj = DB::table('hisdb.patprogressnote')
                                    ->select('idno','compcode','mrn','episno','datetaken','timetaken','progressnote','plan','doctorcode','adduser','adddate','upduser','upddate','lastuser','lastupdate','computerid')
                                    ->where('compcode','=',session('compcode'))
                                    ->where('mrn','=',$request->mrn)
                                    ->where('episno','=',$request->episno)
                                    ->where('datetaken','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->datetime)->format('Y-m-d'))
                                    ->where('timetaken','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->datetime)->format('H:i:s'));
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
                            ->where('episno','=',$request->episno)
                            ->where('reftype', '=','ClientProgNote');
        
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

        if(!empty($request->datetime) && $request->datetime != '-'){
            if($patprogressnote_obj->exists()){
                $patprogressnote_obj = $patprogressnote_obj->first();
                $responce->patprogressnote = $patprogressnote_obj;
            }
        }
        
        // form_refLetter
        if($patreferral_obj->exists()){
            $patreferral_obj = $patreferral_obj->first();
            $responce->patreferral = $patreferral_obj;
        }
        
        // $responce->transaction = json_decode($this->get_transaction_table($request));
        
        return json_encode($responce);
        
    }

    public function refLetterClientProgNote_chart(Request $request){
        
        $mrn = $request->mrn;
        $episno = $request->episno;
        if(empty($mrn) || empty($episno)){
            abort(404);
        }
        
        $patreferral = DB::table('hisdb.patreferral as ptrf')
                        ->select('ptrf.idno','ptrf.compcode','ptrf.mrn','ptrf.episno','ptrf.adduser','ptrf.adddate','ptrf.upduser','ptrf.upddate','ptrf.computerid','ptrf.refdate','ptrf.refaddress','ptrf.refdoc','ptrf.reftitle','ptrf.refdiag','ptrf.refplan','ptrf.refprescription','ptrf.reftype','pm.Name','pm.Newic')
                        ->leftJoin('hisdb.pat_mast as pm', function ($join) use ($request){
                            $join = $join->on('pm.MRN', '=', 'ptrf.mrn')
                                        ->where('pm.compcode','=',session('compcode'));
                        })
                        ->where('ptrf.compcode','=',session('compcode'))
                        ->where('ptrf.mrn','=',$mrn)
                        ->where('ptrf.episno','=',$episno)
                        ->where('ptrf.reftype','=','ClientProgNote')
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
        
        return view('hisdb.clientprogressnote.refLetterClientProgNote_pdfmake',compact('ini_array'));
        
    }
}