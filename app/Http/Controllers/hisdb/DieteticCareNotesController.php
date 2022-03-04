<?php

namespace App\Http\Controllers\hisdb;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class DieteticCareNotesController extends defaultController
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
        return view('hisdb.dieteticCareNotes.dieteticCareNotes');
    }

    public function table(Request $request)
    {   
        switch($request->action){
            case 'get_table_date_dietetic':    // for table date and doctor name    
                return $this->get_table_date_dietetic($request);

            default:
                return 'error happen..';
        }
    }

    public function form(Request $request)
    {   
        DB::enableQueryLog();
        switch($request->action){
            case 'save_table_dieteticCareNotes':

                switch($request->oper){
                    case 'add':
                        return $this->add($request);
                    case 'edit':
                        return $this->edit($request);
                    default:
                        return 'error happen..';
                }

            case 'save_table_dieteticCareNotes_fup':

                switch($request->oper){
                    case 'add_fup':
                        return $this->add_fup($request);
                    case 'edit_fup':
                        return $this->edit_fup($request);
                    default:
                        return 'error happen..';
                }

                case 'get_table_dieteticCareNotes':
                    return $this->get_table_dieteticCareNotes($request);

            default:
                return 'error happen..';
        }
    }

    public function add(Request $request){

        DB::beginTransaction();

        try {

            DB::table('hisdb.patdietncase')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_dieteticCareNotes,
                        'medical_his' => $request->ncase_medical_his,
                        'surgical_his' => $request->ncase_surgical_his,
                        'fam_medical_his' => $request->ncase_fam_medical_his,
                        'history' => $request->ncase_history,
                        'diagnosis' => $request->ncase_diagnosis,
                        'intervention' => $request->ncase_intervention,  
                        'temperature' => $request->ncase_temperature,
                        'pulse' => $request->ncase_pulse,
                        'respiration' => $request->ncase_respiration,
                        'bp_sys1' => $request->ncase_bp_sys1,
                        'bp_dias2' => $request->ncase_bp_dias2,
                        'height' => $request->ncase_height,
                        'weight' => $request->ncase_weight,
                        'gxt' => $request->ncase_gxt,
                        'painscore' => $request->ncase_painscore,
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'recordtime' => Carbon::now("Asia/Kuala_Lumpur"),
                        'adduser'  => session('username'),
                    ]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response('Error DB rollback!'.$e, 500);
        }
    }

    public function add_fup(Request $request){

        DB::beginTransaction();

        try {

            DB::table('hisdb.patdietfup')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_dieteticCareNotes,
                        'episno' => $request->episno_dieteticCareNotes,
                        'progress' => $request->fup_progress,
                        'diagnosis' => $request->fup_diagnosis,
                        'intervention' => $request->fup_intervention,
                        'temperature' => $request->fup_temperature,
                        'pulse' => $request->fup_pulse,
                        'respiration' => $request->fup_respiration,
                        'bp_sys1' => $request->fup_bp_sys1,
                        'bp_dias2' => $request->fup_bp_dias2,
                        'height' => $request->fup_height,
                        'weight' => $request->fup_weight,
                        'gxt' => $request->fup_gxt,
                        'painscore' => $request->fup_painscore,
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'recordtime' => Carbon::now("Asia/Kuala_Lumpur"),
                        'adduser'  => session('username'),
                    ]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response('Error DB rollback!'.$e, 500);
        }
    }

    public function edit_fup(Request $request){

        DB::beginTransaction();

        try {

            DB::table('hisdb.patdietfup')
                ->where('mrn','=',$request->mrn_dieteticCareNotes)
                ->where('episno','=',$request->episno_dieteticCareNotes)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'progress' => $request->fup_progress,
                    'diagnosis' => $request->fup_diagnosis,
                    'intervention' => $request->fup_intervention,
                    'temperature' => $request->fup_temperature,
                    'pulse' => $request->fup_pulse,
                    'respiration' => $request->fup_respiration,
                    'bp_sys1' => $request->fup_bp_sys1,
                    'bp_dias2' => $request->fup_bp_dias2,
                    'height' => $request->fup_height,
                    'weight' => $request->fup_weight,
                    'gxt' => $request->fup_gxt,
                    'painscore' => $request->fup_painscore,
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'recordtime' => Carbon::now("Asia/Kuala_Lumpur"),
                    'adduser'  => session('username'),
                ]);

            $queries = DB::getQueryLog();
            // dump($queries);
            
            DB::commit();

            $responce = new stdClass();
            $responce->mrn = $request->mrn_dieteticCareNotes;
            $responce->episno = $request->episno_dieteticCareNotes;

            return json_encode($responce);

        } catch (\Exception $e) {
            DB::rollback();

            return response('Error DB rollback!'.$e, 500);
        }
    }

    public function get_table_dieteticCareNotes(Request $request){
        
        $patdietncase_obj = DB::table('hisdb.patdietncase')
                    ->select('medical_his as ncase_medical_his','surgical_his as ncase_surgical_his','fam_medical_his as ncase_fam_medical_his','history as ncase_history','diagnosis as ncase_diagnosis','intervention as ncase_intervention','temperature as ncase_temperature','pulse as ncase_pulse','respiration as ncase_respiration','bp_sys1 as ncase_bp_sys1','bp_dias2 as ncase_bp_dias2','height as ncase_height','weight as ncase_weight','gxt as ncase_gxt','painscore as ncase_painscore')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$request->mrn);

        $patdietfup_obj = DB::table('hisdb.patdietfup')
                    ->select('progress as fup_progress','diagnosis as fup_diagnosis','intervention as fup_intervention','temperature as fup_temperature','pulse as fup_pulse','respiration as fup_respiration','bp_sys1 as fup_bp_sys1','bp_dias2 as fup_bp_dias2','height as fup_height','weight as fup_weight','gxt as fup_gxt','painscore as fup_painscore')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$request->mrn)
                    ->where('episno','=',$request->episno);

        $responce = new stdClass();

        if($patdietncase_obj->exists()){
            $patdietncase_obj = $patdietncase_obj->first();
            $responce->patdietncase = $patdietncase_obj;
        }

        if($patdietfup_obj->exists()){
            $patdietfup_obj = $patdietfup_obj->first();
            $responce->patdietfup = $patdietfup_obj;
        }

        return json_encode($responce);

    }

    public function get_table_date_dietetic(Request $request){
        $responce = new stdClass();

        $dietetic_obj = DB::table('hisdb.episode as e')
            ->select('e.mrn','e.episno','p.recordtime','p.adddate','p.adduser','e.admdoctor','d.doctorname')
            ->leftJoin('hisdb.patdietfup as p', function($join) use ($request){
                $join = $join->on('p.mrn', '=', 'e.mrn');
                $join = $join->on('p.episno', '=', 'e.episno');
                $join = $join->on('p.compcode', '=', 'e.compcode');
            })->leftJoin('hisdb.doctor as d', function($join) use ($request){
                $join = $join->on('d.doctorcode', '=', 'e.admdoctor');
                $join = $join->on('d.compcode', '=', 'e.compcode');
            })
            ->where('e.compcode','=',session('compcode'))
            ->where('e.mrn','=',$request->mrn)
            ->orderBy('p.adddate','desc');

        // $patexam_obj = DB::table('hisdb.pathealth')
        //     ->select('mrn','episno','recordtime','adddate','adduser')
        //     ->where('compcode','=',session('compcode'))
        //     ->where('mrn','=',$request->mrn)
        //     ->orderBy('adddate','desc');

        if($dietetic_obj->exists()){
            $dietetic_obj = $dietetic_obj->get();

            $data = [];

            foreach ($dietetic_obj as $key => $value) {
                if(!empty($value->adddate)){
                    $date['date'] =  Carbon::createFromFormat('Y-m-d', $value->adddate)->format('d-m-Y').' '.$value->recordtime;
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

}