<?php

namespace App\Http\Controllers\hisdb;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class NursingController extends defaultController
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
        return view('hisdb.nursing.nursing');
    }

    // public function form(Request $request)
    // {   
    //     switch($request->oper){
    //         case 'add':
    //             return $this->defaultAdd($request);
    //         case 'edit':
    //             return $this->defaultEdit($request);
    //         case 'del':
    //             return $this->defaultDel($request);
    //         default:
    //             return 'error happen..';
    //     }
    // }

    public function form(Request $request)
    {   
        DB::enableQueryLog();
        switch($request->action){
            case 'save_table_ti':

                switch($request->oper){
                    case 'add':
                        return $this->add($request);
                    case 'edit':
                        return $this->edit($request);
                    case 'del':
                        return $this->defaultDel($request);
                    default:
                        return 'error happen..';
                }

            default:
                return 'error happen..';
        }
    }

    public function add(Request $request){

        DB::beginTransaction();

        try {
            
            DB::table('nursing.nursassessment')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_edit_ti,
                        'admwardtime' => $request->admwardtime,
                        'medicalhistory' => $request->medicalhistory,
                        'surgicalhistory' => $request->surgicalhistory,
                        'familymedicalhist' => $request->familymedicalhist,
                        'currentmedication' => $request->currentmedication,
                        'diagnosis' => $request->diagnosis,
                        // 'allergydrugs' => $request->allergydrugs,
                        'allergydrugs'=>$request->has('allergydrugs') ? 1 : 0,
                        'allergyplaster' => $request->allergyplaster,
                        'allergyfood' => $request->allergyfood,
                        'allergyenviroment' => $request->allergyenviroment,
                        'allergyothers' => $request->allergyothers,
                        'allergyremarks' => $request->allergyremarks,
                        'vs_temperature' => $request->vs_temperature,
                        'vs_pulse' => $request->vs_pulse,
                        'vs_respiration' => $request->vs_respiration,
                        // 'vs_bloodpressure_sys1' => $request->vs_bloodpressure_sys1,
                        // 'vs_bloodpressure_dias2' => $request->vs_bloodpressure_dias2,
                        'vs_height' => $request->vs_height,
                        'vs_weight' => $request->vs_weight,
                        'vs_gxt' => $request->vs_gxt,
                        'vs_painscore' => $request->vs_painscore,
                        'moa_walkin' => $request->moa_walkin,
                        'moa_wheelchair' => $request->moa_wheelchair,
                        'moa_trolley' => $request->moa_trolley,
                        'moa_others' => $request->moa_others,
                        'loc_conscious' => $request->loc_conscious,
                        'loc_semiconscious' => $request->loc_semiconscious,
                        'loc_unconscious' => $request->loc_unconscious,
                        'ms_orientated' => $request->ms_orientated,
                        'ms_confused' => $request->ms_confused,
                        'ms_restless' => $request->ms_restless,
                        'ms_aggressive' => $request->ms_aggressive,
                        'es_calm' => $request->es_calm,
                        'es_anxious' => $request->es_anxious,
                        'es_distress' => $request->es_distress,
                        'es_depressed' => $request->es_depressed,
                        'es_irritable' => $request->es_irritable,
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

            DB::table('nursing.nursassessment')
                ->where('mrn','=',$request->mrn_edit_ti)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'admwardtime' => $request->admwardtime,
                    'medicalhistory' => $request->medicalhistory,
                    'surgicalhistory' => $request->surgicalhistory,
                    'familymedicalhist' => $request->familymedicalhist,
                    'currentmedication' => $request->currentmedication,
                    'diagnosis' => $request->diagnosis,
                    'allergydrugs' => $request->allergydrugs,
                    'allergyplaster' => $request->allergyplaster,
                    'allergyfood' => $request->allergyfood,
                    'allergyenviroment' => $request->allergyenviroment,
                    'allergyothers' => $request->allergyothers,
                    'allergyremarks' => $request->allergyremarks,
                    'vs_temperature' => $request->vs_temperature,
                    'vs_pulse' => $request->vs_pulse,
                    'vs_respiration' => $request->vs_respiration,
                    // 'vs_bloodpressure_sys1' => $request->vs_bloodpressure_sys1,
                    // 'vs_bloodpressure_dias2' => $request->vs_bloodpressure_dias2,
                    'vs_height' => $request->vs_height,
                    'vs_weight' => $request->vs_weight,
                    'vs_gxt' => $request->vs_gxt,
                    'vs_painscore' => $request->vs_painscore,
                    'moa_walkin' => $request->moa_walkin,
                    'moa_wheelchair' => $request->moa_wheelchair,
                    'moa_trolley' => $request->moa_trolley,
                    'moa_others' => $request->moa_others,
                    'loc_conscious' => $request->loc_conscious,
                    'loc_semiconscious' => $request->loc_semiconscious,
                    'loc_unconscious' => $request->loc_unconscious,
                    'ms_orientated' => $request->ms_orientated,
                    'ms_confused' => $request->ms_confused,
                    'ms_restless' => $request->ms_restless,
                    'ms_aggressive' => $request->ms_aggressive,
                    'es_calm' => $request->es_calm,
                    'es_anxious' => $request->es_anxious,
                    'es_distress' => $request->es_distress,
                    'es_depressed' => $request->es_depressed,
                    'es_irritable' => $request->es_irritable,
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                ]);

            $queries = DB::getQueryLog();
            dump($queries);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response('Error DB rollback!'.$e, 500);
        }
    }

}