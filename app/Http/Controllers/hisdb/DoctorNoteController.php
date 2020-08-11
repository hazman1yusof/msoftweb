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
                    'remark' => $request->remark,
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
                    ]);

            DB::table('hisdb.pathealth')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_doctorNote,
                        'episno' => $request->episno_doctorNote,
                        'clinicnote' => $request->clinicnote,
                        'pastmedical' => $request->pastmedical,
                        'social' => $request->social,
                        'fmh' => $request->fmh,
                        'plan_' => $request->plan_,
                        'height' => $request->height,
                        'weight' => $request->weight,
                        'bp' => $request->bp,
                        'pulse' => $request->pulse,
                        'temperature' => $request->temperature,
                        'respiration' => $request->respiration,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);

            DB::table('hisdb.pathistory')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_doctorNote,
                        'drug' => $request->drug,
                        'alllergyhistory' => $request->alllergyhistory,
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
                ->where('mrn','=',$request->mrn_doctorNote)
                ->where('episno','=',$request->episno_doctorNote)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'remark' => $request->remark,
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
                    ->where('compcode','=',session('compcode'))
                    ->update([
                        'examination' => $request->examination,
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);

                DB::table('hisdb.pathealth')
                    ->where('mrn','=',$request->mrn_doctorNote)
                    ->where('episno','=',$request->episno_doctorNote)
                    ->where('compcode','=',session('compcode'))
                    ->update([
                        'clinicnote' => $request->clinicnote,
                        'pastmedical' => $request->pastmedical,
                        'social' => $request->social,
                        'fmh' => $request->fmh,
                        'plan_' => $request->plan_,
                        'height' => $request->height,
                        'weight' => $request->weight,
                        'bp' => $request->bp,
                        'pulse' => $request->pulse,
                        'temperature' => $request->temperature,
                        'respiration' => $request->respiration,
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);

                DB::table('hisdb.pathistory')
                    ->where('mrn','=',$request->mrn_doctorNote)
                    ->where('compcode','=',session('compcode'))
                    ->update([
                        'drug' => $request->drug,
                        'alllergyhistory' => $request->alllergyhistory,
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
                    ]);

                DB::table('hisdb.pathealth')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_doctorNote,
                        'episno' => $request->episno_doctorNote,
                        'clinicnote' => $request->clinicnote,
                        'pastmedical' => $request->pastmedical,
                        'social' => $request->social,
                        'fmh' => $request->fmh,
                        'plan_' => $request->plan_,
                        'height' => $request->height,
                        'weight' => $request->weight,
                        'bp' => $request->bp,
                        'pulse' => $request->pulse,
                        'temperature' => $request->temperature,
                        'respiration' => $request->respiration,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);

                DB::table('hisdb.pathistory')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_doctorNote,
                        'drug' => $request->drug,
                        'alllergyhistory' => $request->alllergyhistory,
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

}