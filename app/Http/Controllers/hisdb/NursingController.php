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
                    default:
                        return 'error happen..';
                }

            case 'save_table_ad':

                switch($request->oper){
                    case 'add_ad':
                        return $this->add_ad($request);
                    case 'edit_ad':
                        return $this->edit_ad($request);
                    default:
                        return 'error happen..';
                }

            case 'save_table_tpa':

                switch($request->oper){
                    case 'add_tpa':
                        return $this->add_tpa($request);
                    case 'edit_tpa':
                        return $this->edit_tpa($request);
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
                        'admreason' => $request->admreason,
                        'medicalhistory' => $request->medicalhistory,
                        'surgicalhistory' => $request->surgicalhistory,
                        'familymedicalhist' => $request->familymedicalhist,
                        'currentmedication' => $request->currentmedication,
                        'diagnosis' => $request->diagnosis,
                        'allergydrugs' => $request->allergydrugs,
                        'allergyplaster' => $request->allergyplaster,
                        'allergyfood' => $request->allergyfood,
                        'allergyenviroment' => $request->allergyenviroment,
                        'allergynone' => $request->allergynone,
                        'allergyunknown' => $request->allergyunknown,
                        'allergyothers' => $request->allergyothers,
                        'allergyremarks' => $request->allergyremarks,
                        'vs_temperature' => $request->vs_temperature,
                        'vs_pulse' => $request->vs_pulse,
                        'vs_respiration' => $request->vs_respiration,
                        'vs_bloodpressure_sys1' => $request->vs_bloodpressure_sys1,
                        'vs_bloodpressure_dias2' => $request->vs_bloodpressure_dias2,
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
                    'admreason' => $request->admreason,
                    'medicalhistory' => $request->medicalhistory,
                    'surgicalhistory' => $request->surgicalhistory,
                    'familymedicalhist' => $request->familymedicalhist,
                    'currentmedication' => $request->currentmedication,
                    'diagnosis' => $request->diagnosis,
                    'allergydrugs' => $request->allergydrugs,
                    'allergyplaster' => $request->allergyplaster,
                    'allergyfood' => $request->allergyfood,
                    'allergyenviroment' => $request->allergyenviroment,
                    'allergynone' => $request->allergynone,
                    'allergyunknown' => $request->allergyunknown,
                    'allergyothers' => $request->allergyothers,
                    'allergyremarks' => $request->allergyremarks,
                    'vs_temperature' => $request->vs_temperature,
                    'vs_pulse' => $request->vs_pulse,
                    'vs_respiration' => $request->vs_respiration,
                    'vs_bloodpressure_sys1' => $request->vs_bloodpressure_sys1,
                    'vs_bloodpressure_dias2' => $request->vs_bloodpressure_dias2,
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

    public function add_ad(Request $request){

        DB::beginTransaction();

        try {
            
            DB::table('nursing.nursassessgen')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_edit_ad,
                        'br_breathing' => $request->br_breathing,
                        'br_breathingdesc' => $request->br_breathingdesc,
                        'br_cough' => $request->br_cough,
                        'br_coughdesc' => $request->br_coughdesc,
                        'br_smoke' => $request->br_smoke,
                        'br_smokedesc' => $request->br_smokedesc,
                        'ed_eatdrink' => $request->ed_eatdrink,
                        'ed_eatdrinkdesc' => $request->ed_eatdrinkdesc,
                        'eb_bowelhabit' => $request->eb_bowelhabit,
                        'eb_bowelmove' => $request->eb_bowelmove,
                        'eb_bowelmovedesc' => $request->eb_bowelmovedesc,
                        'bl_urine' => $request->bl_urine,
                        'bl_urinedesc' => $request->bl_urinedesc,
                        'bl_urinefreq' => $request->bl_urinefreq,
                        'sl_sleep' => $request->sl_sleep,
                        'mobilityambulan' => $request->mobilityambulan,
                        'mobilityassistaid' => $request->mobilityassistaid,
                        'mobilitybedridden' => $request->mobilitybedridden,
                        'phygiene_self' => $request->phygiene_self,
                        'phygiene_needassist' => $request->phygiene_needassist,
                        'phygiene_dependant' => $request->phygiene_dependant,
                        'safeenv_siderail' => $request->safeenv_siderail,
                        'safeenv_restraint' => $request->safeenv_restraint,
                        'cspeech_normal' => $request->cspeech_normal,
                        'cspeech_slurred' => $request->cspeech_slurred,
                        'cspeech_impaired' => $request->cspeech_impaired,
                        'cspeech_mute' => $request->cspeech_mute,
                        'cvision_normal' => $request->cvision_normal,
                        'cvision_blurring' => $request->cvision_blurring,
                        'cvision_doublev' => $request->cvision_doublev,
                        'cvision_blind' => $request->cvision_blind,
                        'cvision_visualaids' => $request->cvision_visualaids,
                        'chearing_normal' => $request->chearing_normal,
                        'chearing_deaf' => $request->chearing_deaf,
                        'chearing_hardhear' => $request->chearing_hardhear,
                        'chearing_hearaids' => $request->chearing_hearaids,
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

    public function edit_ad(Request $request){
        
        DB::beginTransaction();

        try {

            DB::table('nursing.nursassessgen')
                ->where('mrn','=',$request->mrn_edit_ad)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'br_breathing' => $request->br_breathing,
                    'br_breathingdesc' => $request->br_breathingdesc,
                    'br_cough' => $request->br_cough,
                    'br_coughdesc' => $request->br_coughdesc,
                    'br_smoke' => $request->br_smoke,
                    'br_smokedesc' => $request->br_smokedesc,
                    'ed_eatdrink' => $request->ed_eatdrink,
                    'ed_eatdrinkdesc' => $request->ed_eatdrinkdesc,
                    'eb_bowelhabit' => $request->eb_bowelhabit,
                    'eb_bowelmove' => $request->eb_bowelmove,
                    'eb_bowelmovedesc' => $request->eb_bowelmovedesc,
                    'bl_urine' => $request->bl_urine,
                    'bl_urinedesc' => $request->bl_urinedesc,
                    'bl_urinefreq' => $request->bl_urinefreq,
                    'sl_sleep' => $request->sl_sleep,
                    'mobilityambulan' => $request->mobilityambulan,
                    'mobilityassistaid' => $request->mobilityassistaid,
                    'mobilitybedridden' => $request->mobilitybedridden,
                    'phygiene_self' => $request->phygiene_self,
                    'phygiene_needassist' => $request->phygiene_needassist,
                    'phygiene_dependant' => $request->phygiene_dependant,
                    'safeenv_siderail' => $request->safeenv_siderail,
                    'safeenv_restraint' => $request->safeenv_restraint,
                    'cspeech_normal' => $request->cspeech_normal,
                    'cspeech_slurred' => $request->cspeech_slurred,
                    'cspeech_impaired' => $request->cspeech_impaired,
                    'cspeech_mute' => $request->cspeech_mute,
                    'cvision_normal' => $request->cvision_normal,
                    'cvision_blurring' => $request->cvision_blurring,
                    'cvision_doublev' => $request->cvision_doublev,
                    'cvision_blind' => $request->cvision_blind,
                    'cvision_visualaids' => $request->cvision_visualaids,
                    'chearing_normal' => $request->chearing_normal,
                    'chearing_deaf' => $request->chearing_deaf,
                    'chearing_hardhear' => $request->chearing_hardhear,
                    'chearing_hearaids' => $request->chearing_hearaids,
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

    public function add_tpa(Request $request){

        DB::beginTransaction();

        try {
            
            DB::table('nursing.nursassessgen')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_edit_tpa,
                        'pa_skindry' => $request->pa_skindry,
                        'pa_skinodema' => $request->pa_skinodema,
                        'pa_skinjaundice' => $request->pa_skinjaundice,
                        'pa_othbruises' => $request->pa_othbruises,
                        'pa_othdeculcer' => $request->pa_othdeculcer,
                        'pa_othlaceration' => $request->pa_othlaceration,
                        'pa_othdiscolor' => $request->pa_othdiscolor,
                        'pa_notes' => $request->pa_notes,
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

    public function edit_tpa(Request $request){

        DB::beginTransaction();

        try {

            DB::table('nursing.nursassessgen')
                ->where('mrn','=',$request->mrn_edit_tpa)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'pa_skindry' => $request->pa_skindry,
                    'pa_skinodema' => $request->pa_skinodema,
                    'pa_skinjaundice' => $request->pa_skinjaundice,
                    'pa_othbruises' => $request->pa_othbruises,
                    'pa_othdeculcer' => $request->pa_othdeculcer,
                    'pa_othlaceration' => $request->pa_othlaceration,
                    'pa_othdiscolor' => $request->pa_othdiscolor,
                    'pa_notes' => $request->pa_notes,
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