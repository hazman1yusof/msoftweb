<?php

namespace App\Http\Controllers\hisdb;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

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

    public function table(Request $request)
    {   
        switch($request->action){
            case 'get_table_date':
                return $this->get_table_date($request);
            case 'get_table_doctornote':
                return $this->get_table_doctornote($request);

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
                    'remarks' => $request->remarks,
                    'diagfinal' => $request->diagfinal,
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                ]);

            DB::table('hisdb.patexam')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_doctorNote,
                        'episno' => $request->episno_doctorNote,
                        'examination' => $request->examination,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'recorddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'recordtime' => Carbon::now("Asia/Kuala_Lumpur"),
                    ]);

            DB::table('hisdb.pathealth')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_doctorNote,
                        'episno' => $request->episno_doctorNote,
                        'clinicnote' => $request->clinicnote,
                        'pmh' => $request->pmh,
                        'drugh' => $request->drugh,
                        'allergyh' => $request->allergyh,
                        'socialh' => $request->socialh,
                        'fmh' => $request->fmh,
                        'followuptime' => $request->followuptime,
                        'followupdate' => $request->followupdate,
                        'plan_' => $request->plan_,
                        'height' => $request->height,
                        'weight' => $request->weight,
                        'bp_sys1' => $request->bp_sys1,
                        'bp_dias2' => $request->bp_dias2,
                        'pulse' => $request->pulse,
                        'temperature' => $request->temperature,
                        'respiration' => $request->respiration,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'recordtime' => Carbon::now("Asia/Kuala_Lumpur"),
                    ]);

            DB::table('hisdb.pathistory')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_doctorNote,
                        'pmh' => $request->pmh,
                        'drugh' => $request->drugh,
                        'allergyh' => $request->allergyh,
                        'socialh' => $request->socialh,
                        'fmh' => $request->fmh,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'recorddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'recordtime' => Carbon::now("Asia/Kuala_Lumpur"),
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
                    'remarks' => $request->remarks,
                    'diagfinal' => $request->diagfinal,
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                ]);

            $patexam = DB::table('hisdb.patexam')
                ->where('mrn','=',$request->mrn_doctorNote)
                ->where('episno','=',$request->episno_doctorNote)
                ->where('compcode','=',session('compcode'));

            $pathealth = DB::table('hisdb.pathealth')
                ->where('mrn','=',$request->mrn_doctorNote)
                ->where('episno','=',$request->episno_doctorNote)
                ->where('compcode','=',session('compcode'));

            $pathistory = DB::table('hisdb.pathistory')
                ->where('mrn','=',$request->mrn_doctorNote)
                ->where('compcode','=',session('compcode'));

            if($patexam->exists() && $pathealth->exists() && $pathistory->exists()){
                DB::table('hisdb.patexam')
                    ->where('mrn','=',$request->mrn_doctorNote)
                    ->where('episno','=',$request->episno_doctorNote)
                    ->where('recorddate','=',$request->recorddate)
                    ->update([
                        'examination' => $request->examination,
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);

                DB::table('hisdb.pathealth')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_doctorNote,
                        'episno' => $request->episno_doctorNote,
                        'clinicnote' => $request->clinicnote,
                        'pmh' => $request->pmh,
                        'drugh' => $request->drugh,
                        'allergyh' => $request->allergyh,
                        'socialh' => $request->socialh,
                        'fmh' => $request->fmh,
                        'followuptime' => $request->followuptime,
                        'followupdate' => $request->followupdate,
                        'plan_' => $request->plan_,
                        'height' => $request->height,
                        'weight' => $request->weight,
                        'bp_sys1' => $request->bp_sys1,
                        'bp_dias2' => $request->bp_dias2,
                        'pulse' => $request->pulse,
                        'temperature' => $request->temperature,
                        'respiration' => $request->respiration,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'recordtime' => Carbon::now("Asia/Kuala_Lumpur"),
                    ]);

                DB::table('hisdb.pathistory')
                    ->where('mrn','=',$request->mrn_doctorNote)
                    ->where('compcode','=',session('compcode'))
                    ->where('recorddate','=',$request->recorddate)
                    ->update([
                        'pmh' => $request->pmh,
                        'drugh' => $request->drugh,
                        'allergyh' => $request->allergyh,
                        'socialh' => $request->socialh,
                        'fmh' => $request->fmh,
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }else{

                DB::table('hisdb.patexam')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_doctorNote,
                        'episno' => $request->episno_doctorNote,
                        'examination' => $request->examination,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'recorddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'recordtime' => Carbon::now("Asia/Kuala_Lumpur"),
                    ]);

                DB::table('hisdb.pathealth')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_doctorNote,
                        'episno' => $request->episno_doctorNote,
                        'clinicnote' => $request->clinicnote,
                        'pmh' => $request->pmh,
                        'drugh' => $request->drugh,
                        'allergyh' => $request->allergyh,
                        'socialh' => $request->socialh,
                        'fmh' => $request->fmh,
                        'followuptime' => $request->followuptime,
                        'followupdate' => $request->followupdate,
                        'plan_' => $request->plan_,
                        'height' => $request->height,
                        'weight' => $request->weight,
                        'bp_sys1' => $request->bp_sys1,
                        'bp_dias2' => $request->bp_dias2,
                        'pulse' => $request->pulse,
                        'temperature' => $request->temperature,
                        'respiration' => $request->respiration,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'recordtime' => Carbon::now("Asia/Kuala_Lumpur"),
                    ]);

                DB::table('hisdb.pathistory')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_doctorNote,
                        'pmh' => $request->pmh,
                        'drugh' => $request->drugh,
                        'allergyh' => $request->allergyh,
                        'socialh' => $request->socialh,
                        'fmh' => $request->fmh,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'recorddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'recordtime' => Carbon::now("Asia/Kuala_Lumpur"),
                    ]);
            }

            $queries = DB::getQueryLog();
            // dump($queries);
            
            DB::commit();

            $patexam_obj = DB::table('hisdb.patexam')
                ->select('idno','recorddate AS date')
                ->where('mrn','=',$request->mrn_doctorNote)
                ->where('episno','=',$request->episno_doctorNote)
                ->where('recorddate','=',$request->recorddate)
                ->where('compcode','=',session('compcode'))
                ->first();


            $responce = new stdClass();
            $responce->idno = $patexam_obj->idno;
            $responce->date = $patexam_obj->date;

            return json_encode($responce);


        } catch (\Exception $e) {
            DB::rollback();

            return response('Error DB rollback!'.$e, 500);
        }
    }

    public function get_table_date(Request $request){


        $responce = new stdClass();

        $patexam_obj = DB::table('hisdb.patexam')
            ->select('idno','recorddate AS date')
            ->where('mrn','=',$request->mrn)
            ->where('episno','=',$request->episno)
            ->where('compcode','=',session('compcode'));

        if($patexam_obj->exists()){
            $patexam_obj = $patexam_obj->get();
            $responce->data = $patexam_obj;
        }else{
            $responce->data = [];
        }

        return json_encode($responce);
    }

    public function get_table_doctornote(Request $request){

        $responce = new stdClass();


        $episode_obj = DB::table('hisdb.episode')
            ->select('remarks','diagfinal')
            ->where('compcode','=',session('compcode'))
            ->where('mrn','=',$request->mrn)
            ->where('episno','=',$request->episno);

        $pathealth_obj = DB::table('hisdb.pathealth')
            ->where('compcode','=',session('compcode'))
            ->where('mrn','=',$request->mrn)
            ->where('episno','=',$request->episno);

        $pathistory_obj = DB::table('hisdb.pathistory')
            ->where('compcode','=',session('compcode'))
            ->where('mrn','=',$request->mrn)
            ->where('recorddate','=',$request->recorddate);

        $patexam_obj = DB::table('hisdb.patexam')
            ->where('compcode','=',session('compcode'))
            ->where('mrn','=',$request->mrn)
            ->where('episno','=',$request->episno)
            ->where('recorddate','=',$request->recorddate);

        $episdiag_obj = DB::table('hisdb.episdiag')
            ->where('compcode','=',session('compcode'))
            ->where('mrn','=',$request->mrn)
            ->where('episno','=',$request->episno);

        if($episode_obj->exists()){
            $episode_obj = $episode_obj->first();
            $responce->episode = $episode_obj;
        }

        if($pathealth_obj->exists()){
            $pathealth_obj = $pathealth_obj->first();
            $responce->pathealth = $pathealth_obj;
        }

        if($pathistory_obj->exists()){
            $pathistory_obj = $pathistory_obj->first();
            $responce->pathistory = $pathistory_obj;
        }

        if($patexam_obj->exists()){
            $patexam_obj = $patexam_obj->first();
            $responce->patexam = $patexam_obj;
        }

        if($episdiag_obj->exists()){
            $episdiag_obj = $episdiag_obj->first();
            $responce->episdiag = $episdiag_obj;
        }

        return json_encode($responce);
    }

}