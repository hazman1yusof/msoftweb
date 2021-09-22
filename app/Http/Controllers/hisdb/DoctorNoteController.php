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
            case 'get_table_date_curr':          // for current
                return $this->get_table_date_curr($request);
            case 'get_table_date_past':     // for past history
                return $this->get_table_date_past($request);
            case 'get_table_doctornote':
                return $this->get_table_doctornote($request);
            case 'dialog_icd':
                return $this->dialog_icd($request);

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
                        'drugh' => $request->drugh,
                        'allergyh' => $request->allergyh,
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

            DB::table('hisdb.episdiag')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_doctorNote,
                        'episno' => $request->episno_doctorNote,
                        'icdcode' => $request->icdcode,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
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

            DB::table('hisdb.pathealthadd')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn_doctorNote,
                    'episno' => $request->episno_doctorNote,
                    'additionalnote' => $request->additionalnote,
                ]);

            $patexam = DB::table('hisdb.patexam')
                ->where('mrn','=',$request->mrn_doctorNote)
                ->where('episno','=',$request->episno_doctorNote)
                ->where('recorddate','=',$request->recorddate)
                ->where('compcode','=',session('compcode'));

            $pathealth = DB::table('hisdb.pathealth')
                ->where('mrn','=',$request->mrn_doctorNote)
                ->where('episno','=',$request->episno_doctorNote)
                ->where('recordtime','=',$request->recordtime)
                ->where('compcode','=',session('compcode'));

            $pathistory = DB::table('hisdb.pathistory')
                ->where('mrn','=',$request->mrn_doctorNote)
                ->where('recorddate','=',$request->recorddate)
                ->where('compcode','=',session('compcode'));

            $episdiag = DB::table('hisdb.episdiag')
                ->where('mrn','=',$request->mrn_doctorNote)
                ->where('episno','=',$request->episno_doctorNote)
                ->where('compcode','=',session('compcode'));

            if($patexam->exists()){
                $patexam->update([
                        'examination' => $request->examination,
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
            }

            if($pathealth->exists()){
                $pathealth
                    ->update([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_doctorNote,
                        'episno' => $request->episno_doctorNote,
                        'clinicnote' => $request->clinicnote,
                        'drugh' => $request->drugh,
                        'allergyh' => $request->allergyh,
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
            }else{
                DB::table('hisdb.pathealth')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_doctorNote,
                        'episno' => $request->episno_doctorNote,
                        'clinicnote' => $request->clinicnote,
                        'drugh' => $request->drugh,
                        'allergyh' => $request->allergyh,
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
            }

            if($pathistory->exists()){
                $pathistory
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

    public function get_table_date_curr(Request $request){


        $responce = new stdClass();

        $patexam_obj = DB::table('hisdb.patexam')
            ->select('idno','recorddate AS date','adduser')
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

    public function get_table_date_past(Request $request){

        $responce = new stdClass();

        $patexam_obj = DB::table('hisdb.patexam')
            ->select('idno','recorddate AS date','adduser')
            ->where('mrn','=',$request->mrn)
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
            ->where('episno','=',$request->episno)
            ->orderBy('recordtime','desc');

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

        $pathealthadd_obj = DB::table('hisdb.pathealthadd')
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

        if($pathealthadd_obj->exists()){
            $pathealthadd_obj = $pathealthadd_obj->first();
            $responce->pathealthadd = $pathealthadd_obj;
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

            foreach ($count as $key => $value) {
                $occur_ar = $this->index_of_occurance($key,$searchCol_array);

                $table = $table->where(function ($table) use ($request,$searchCol_array,$occur_ar) {
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

    public function add_notes(Request $request){

        DB::beginTransaction();

        try {

            DB::table('hisdb.pathealthadd')
                ->insert([  
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn,
                    'episno' => $request->episno,
                    'additionalnote' => $request->additionalnote,
                    'adduser'  => session('username'),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur")
                    
                ]);

             DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

}