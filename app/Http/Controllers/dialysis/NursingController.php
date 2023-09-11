<?php

namespace App\Http\Controllers\dialysis;

use Illuminate\Http\Request;
use stdClass;
use DB;
use Carbon\Carbon;

class NursingController extends Controller
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
        return view('dialysis.nursing');
    }


    public function table(Request $request)
    {   
        switch($request->action){
            case 'get_table_date_curr':          // for current
                return $this->get_table_date_curr($request);
            case 'get_table_date_past':     // for past history
                return $this->get_table_date_past($request);

            default:
                return 'error happen..';
        }
    }

    public function form(Request $request)
    {   
        DB::enableQueryLog();
        switch($request->action){
            case 'save_table_ti': //dari bed management

                switch($request->oper){
                    case 'add':
                        return $this->add($request);
                    case 'edit':
                        return $this->edit($request);
                    default:
                        return 'error happen..';
                }

            case 'save_table_triage': //dari patient list OP

                switch($request->oper){
                    case 'add':
                        return $this->add_triage($request);
                    case 'edit':
                        return $this->edit_triage($request);
                    default:
                        return 'error happen..';
                }

            case 'nursing_save':
                return $this->add_exam($request);

            case 'nursing_edit':
                return $this->edit_exam($request);

            case 'more_examTriage_save':
                return $this->add_more_exam($request);

            case 'get_table_triage':
                return $this->get_table_triage($request);

            case 'addNotesTriage_save':
                return $this->addNotes_triage($request);

            default:
                return 'error happen..';
        }
    }

    public function add(Request $request){

        DB::beginTransaction();

        try {

            $triage_obj = DB::table('nursing.nursassessment')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$request->mrn_ti)
                    ->where('episno','=',$request->episno_ti)
                    ->where('arrival_date','=',$request->reg_date);

            $triage_gen_obj = DB::table('nursing.nursassessgen')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$request->mrn_ti)
                        ->where('episno','=',$request->episno_ti)
                        ->where('arrival_date','=',$request->reg_date);
                        
            $triage_nurshistory_obj = DB::table('nursing.nurshistory')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$request->mrn_ti);
            
            if($triage_obj->exists()){

                DB::table('nursing.nursassessment')
                    ->where('mrn','=',$request->mrn_ti)
                    ->where('episno','=',$request->episno_ti)
                    ->where('arrival_date','=',$request->reg_date)
                    ->update([
                        'admwardtime' => $request->admwardtime,
                        'triagecolor' => $request->triagecolor,
                        'admreason' => $request->admreason,
                        'currentmedication' => $request->currentmedication,
                        'diagnosis' => $request->diagnosis,
                        'vs_temperature' => $request->vs_temperature,
                        'vs_pulse' => $request->vs_pulse,
                        'vs_respiration' => $request->vs_respiration,
                        'vs_bp_sys1' => $request->vs_bp_sys1,
                        'vs_bp_dias2' => $request->vs_bp_dias2,
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
                        'fra_prevfalls' => $request->fra_prevfalls,
                        'fra_age' => $request->fra_age,
                        'fra_physicalLimitation' => $request->fra_physicalLimitation,
                        'fra_neurologicaldeficit' => $request->fra_neurologicaldeficit,
                        'fra_dizziness' => $request->fra_dizziness,
                        'fra_cerebralaccident' => $request->fra_cerebralaccident,
                        'fra_notatrisk' => $request->fra_notatrisk,
                        'fra_atrisk' => $request->fra_atrisk,
                        'psra_incontinent' => $request->psra_incontinent,
                        'psra_immobility' => $request->psra_immobility,
                        'psra_poorskintype' => $request->psra_poorskintype,
                        'psra_notatrisk' => $request->psra_notatrisk,
                        'psra_atrisk' => $request->psra_atrisk,
                        'location' => 'TRIAGE',
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }else{

                DB::table('nursing.nursassessment')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_ti,
                        'episno' => $request->episno_ti,
                        'admwardtime' => $request->admwardtime,
                        'triagecolor' => $request->triagecolor,
                        'admreason' => $request->admreason,
                        'currentmedication' => $request->currentmedication,
                        'diagnosis' => $request->diagnosis,
                        'vs_temperature' => $request->vs_temperature,
                        'vs_pulse' => $request->vs_pulse,
                        'vs_respiration' => $request->vs_respiration,
                        'vs_bp_sys1' => $request->vs_bp_sys1,
                        'vs_bp_dias2' => $request->vs_bp_dias2,
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
                        'fra_prevfalls' => $request->fra_prevfalls,
                        'fra_age' => $request->fra_age,
                        'fra_physicalLimitation' => $request->fra_physicalLimitation,
                        'fra_neurologicaldeficit' => $request->fra_neurologicaldeficit,
                        'fra_dizziness' => $request->fra_dizziness,
                        'fra_cerebralaccident' => $request->fra_cerebralaccident,
                        'fra_notatrisk' => $request->fra_notatrisk,
                        'fra_atrisk' => $request->fra_atrisk,
                        'psra_incontinent' => $request->psra_incontinent,
                        'psra_immobility' => $request->psra_immobility,
                        'psra_poorskintype' => $request->psra_poorskintype,
                        'psra_notatrisk' => $request->psra_notatrisk,
                        'psra_atrisk' => $request->psra_atrisk,
                        'location' => 'TRIAGE',
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'arrival_date' => $request->reg_date,
                    ]);
            }

            if($triage_nurshistory_obj->exists()){
                DB::table('nursing.nurshistory')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$request->mrn_ti)
                    ->update([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_ti,
                        'medicalhistory' => $request->medicalhistory,
                        'surgicalhistory' => $request->surgicalhistory,
                        'familymedicalhist' => $request->familymedicalhist,
                        'allergydrugs' => $request->allergydrugs,
                        'drugs_remarks' => $request->drugs_remarks,
                        'allergyplaster' => $request->allergyplaster,
                        'plaster_remarks' => $request->plaster_remarks,
                        'allergyfood' => $request->allergyfood,
                        'food_remarks' => $request->food_remarks,
                        'allergyenvironment' => $request->allergyenvironment,
                        'environment_remarks' => $request->environment_remarks,
                        'allergyothers' => $request->allergyothers,
                        'others_remarks' => $request->others_remarks,
                        'allergyunknown' => $request->allergyunknown,
                        'unknown_remarks' => $request->unknown_remarks,
                        'allergynone' => $request->allergynone,
                        'none_remarks' => $request->none_remarks,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString()
                    ]);
            }else{

                DB::table('nursing.nurshistory')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_ti,
                        'medicalhistory' => $request->medicalhistory,
                        'surgicalhistory' => $request->surgicalhistory,
                        'familymedicalhist' => $request->familymedicalhist,
                        'allergydrugs' => $request->allergydrugs,
                        'drugs_remarks' => $request->drugs_remarks,
                        'allergyplaster' => $request->allergyplaster,
                        'plaster_remarks' => $request->plaster_remarks,
                        'allergyfood' => $request->allergyfood,
                        'food_remarks' => $request->food_remarks,
                        'allergyenvironment' => $request->allergyenvironment,
                        'environment_remarks' => $request->environment_remarks,
                        'allergyothers' => $request->allergyothers,
                        'others_remarks' => $request->others_remarks,
                        'allergyunknown' => $request->allergyunknown,
                        'unknown_remarks' => $request->unknown_remarks,
                        'allergynone' => $request->allergynone,
                        'none_remarks' => $request->none_remarks,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString()
                    ]);
            }
            
            if($triage_gen_obj->exists()){
                DB::table('nursing.nursassessgen')
                    ->where('mrn','=',$request->mrn_ti)
                    ->where('episno','=',$request->episno_ti)
                    ->where('arrival_date','=',$request->reg_date)
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
                        // TRIAGE PHYSICAL ASSESSMENT
                        'pa_skindry' => $request->pa_skindry,
                        'pa_skinodema' => $request->pa_skinodema,
                        'pa_skinjaundice' => $request->pa_skinjaundice,
                        'pa_skinnil' => $request->pa_skinnil,
                        'pa_othbruises' => $request->pa_othbruises,
                        'pa_othdeculcer' => $request->pa_othdeculcer,
                        'pa_othlaceration' => $request->pa_othlaceration,
                        'pa_othdiscolor' => $request->pa_othdiscolor,
                        'pa_othnil' => $request->pa_othnil,
                        'pa_notes' => $request->pa_notes,
                        'location' => 'TRIAGE',
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }else{
                DB::table('nursing.nursassessgen')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_ti,
                        'episno' => $request->episno_ti,
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
                        // TRIAGE PHYSICAL ASSESSMENT
                        'pa_skindry' => $request->pa_skindry,
                        'pa_skinodema' => $request->pa_skinodema,
                        'pa_skinjaundice' => $request->pa_skinjaundice,
                        'pa_skinnil' => $request->pa_skinnil,
                        'pa_othbruises' => $request->pa_othbruises,
                        'pa_othdeculcer' => $request->pa_othdeculcer,
                        'pa_othlaceration' => $request->pa_othlaceration,
                        'pa_othdiscolor' => $request->pa_othdiscolor,
                        'pa_othnil' => $request->pa_othnil,
                        'pa_notes' => $request->pa_notes,
                        'location' => 'TRIAGE',
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'arrival_date' => $request->reg_date,
                    ]);
            }

            DB::table('hisdb.episode')
                ->where('mrn','=',$request->mrn_ti)
                ->where('episno','=',$request->episno_ti)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'nurse_stat' =>  1,
                    'diagprov' => $request->diagnosis,
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



            $triage_obj = DB::table('nursing.nursassessment')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$request->mrn_ti)
                    ->where('episno','=',$request->episno_ti)
                    ->where('arrival_date','=',$request->reg_date);

            $triage_gen_obj = DB::table('nursing.nursassessgen')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$request->mrn_ti)
                        ->where('episno','=',$request->episno_ti)
                        ->where('arrival_date','=',$request->reg_date);
                        
            $triage_nurshistory_obj = DB::table('nursing.nurshistory')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$request->mrn_ti);
            
            if($triage_obj->exists()){

                DB::table('nursing.nursassessment')
                    ->where('mrn','=',$request->mrn_ti)
                    ->where('episno','=',$request->episno_ti)
                    ->where('arrival_date','=',$request->reg_date)
                    ->update([
                        'admwardtime' => $request->admwardtime,
                        'triagecolor' => $request->triagecolor,
                        'admreason' => $request->admreason,
                        'currentmedication' => $request->currentmedication,
                        'diagnosis' => $request->diagnosis,
                        'vs_temperature' => $request->vs_temperature,
                        'vs_pulse' => $request->vs_pulse,
                        'vs_respiration' => $request->vs_respiration,
                        'vs_bp_sys1' => $request->vs_bp_sys1,
                        'vs_bp_dias2' => $request->vs_bp_dias2,
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
                        'fra_prevfalls' => $request->fra_prevfalls,
                        'fra_age' => $request->fra_age,
                        'fra_physicalLimitation' => $request->fra_physicalLimitation,
                        'fra_neurologicaldeficit' => $request->fra_neurologicaldeficit,
                        'fra_dizziness' => $request->fra_dizziness,
                        'fra_cerebralaccident' => $request->fra_cerebralaccident,
                        'fra_notatrisk' => $request->fra_notatrisk,
                        'fra_atrisk' => $request->fra_atrisk,
                        'psra_incontinent' => $request->psra_incontinent,
                        'psra_immobility' => $request->psra_immobility,
                        'psra_poorskintype' => $request->psra_poorskintype,
                        'psra_notatrisk' => $request->psra_notatrisk,
                        'psra_atrisk' => $request->psra_atrisk,
                        'location' => 'TRIAGE',
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }else{

                DB::table('nursing.nursassessment')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_ti,
                        'episno' => $request->episno_ti,
                        'admwardtime' => $request->admwardtime,
                        'triagecolor' => $request->triagecolor,
                        'admreason' => $request->admreason,
                        'currentmedication' => $request->currentmedication,
                        'diagnosis' => $request->diagnosis,
                        'vs_temperature' => $request->vs_temperature,
                        'vs_pulse' => $request->vs_pulse,
                        'vs_respiration' => $request->vs_respiration,
                        'vs_bp_sys1' => $request->vs_bp_sys1,
                        'vs_bp_dias2' => $request->vs_bp_dias2,
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
                        'fra_prevfalls' => $request->fra_prevfalls,
                        'fra_age' => $request->fra_age,
                        'fra_physicalLimitation' => $request->fra_physicalLimitation,
                        'fra_neurologicaldeficit' => $request->fra_neurologicaldeficit,
                        'fra_dizziness' => $request->fra_dizziness,
                        'fra_cerebralaccident' => $request->fra_cerebralaccident,
                        'fra_notatrisk' => $request->fra_notatrisk,
                        'fra_atrisk' => $request->fra_atrisk,
                        'psra_incontinent' => $request->psra_incontinent,
                        'psra_immobility' => $request->psra_immobility,
                        'psra_poorskintype' => $request->psra_poorskintype,
                        'psra_notatrisk' => $request->psra_notatrisk,
                        'psra_atrisk' => $request->psra_atrisk,
                        'location' => 'TRIAGE',
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'arrival_date' => $request->reg_date,
                    ]);
            }

            if($triage_nurshistory_obj->exists()){
                DB::table('nursing.nurshistory')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$request->mrn_ti)
                    ->update([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_ti,
                        'medicalhistory' => $request->medicalhistory,
                        'surgicalhistory' => $request->surgicalhistory,
                        'familymedicalhist' => $request->familymedicalhist,
                        'allergydrugs' => $request->allergydrugs,
                        'drugs_remarks' => $request->drugs_remarks,
                        'allergyplaster' => $request->allergyplaster,
                        'plaster_remarks' => $request->plaster_remarks,
                        'allergyfood' => $request->allergyfood,
                        'food_remarks' => $request->food_remarks,
                        'allergyenvironment' => $request->allergyenvironment,
                        'environment_remarks' => $request->environment_remarks,
                        'allergyothers' => $request->allergyothers,
                        'others_remarks' => $request->others_remarks,
                        'allergyunknown' => $request->allergyunknown,
                        'unknown_remarks' => $request->unknown_remarks,
                        'allergynone' => $request->allergynone,
                        'none_remarks' => $request->none_remarks,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString()
                    ]);
            }else{

                DB::table('nursing.nurshistory')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_ti,
                        'medicalhistory' => $request->medicalhistory,
                        'surgicalhistory' => $request->surgicalhistory,
                        'familymedicalhist' => $request->familymedicalhist,
                        'allergydrugs' => $request->allergydrugs,
                        'drugs_remarks' => $request->drugs_remarks,
                        'allergyplaster' => $request->allergyplaster,
                        'plaster_remarks' => $request->plaster_remarks,
                        'allergyfood' => $request->allergyfood,
                        'food_remarks' => $request->food_remarks,
                        'allergyenvironment' => $request->allergyenvironment,
                        'environment_remarks' => $request->environment_remarks,
                        'allergyothers' => $request->allergyothers,
                        'others_remarks' => $request->others_remarks,
                        'allergyunknown' => $request->allergyunknown,
                        'unknown_remarks' => $request->unknown_remarks,
                        'allergynone' => $request->allergynone,
                        'none_remarks' => $request->none_remarks,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString()
                    ]);
            }
            
            if($triage_gen_obj->exists()){
                DB::table('nursing.nursassessgen')
                    ->where('mrn','=',$request->mrn_ti)
                    ->where('episno','=',$request->episno_ti)
                    ->where('arrival_date','=',$request->reg_date)
                    ->insert([
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
                        // TRIAGE PHYSICAL ASSESSMENT
                        'pa_skindry' => $request->pa_skindry,
                        'pa_skinodema' => $request->pa_skinodema,
                        'pa_skinjaundice' => $request->pa_skinjaundice,
                        'pa_skinnil' => $request->pa_skinnil,
                        'pa_othbruises' => $request->pa_othbruises,
                        'pa_othdeculcer' => $request->pa_othdeculcer,
                        'pa_othlaceration' => $request->pa_othlaceration,
                        'pa_othdiscolor' => $request->pa_othdiscolor,
                        'pa_othnil' => $request->pa_othnil,
                        'pa_notes' => $request->pa_notes,
                        'location' => 'TRIAGE',
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }else{
                DB::table('nursing.nursassessgen')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_ti,
                        'episno' => $request->episno_ti,
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
                        // TRIAGE PHYSICAL ASSESSMENT
                        'pa_skindry' => $request->pa_skindry,
                        'pa_skinodema' => $request->pa_skinodema,
                        'pa_skinjaundice' => $request->pa_skinjaundice,
                        'pa_skinnil' => $request->pa_skinnil,
                        'pa_othbruises' => $request->pa_othbruises,
                        'pa_othdeculcer' => $request->pa_othdeculcer,
                        'pa_othlaceration' => $request->pa_othlaceration,
                        'pa_othdiscolor' => $request->pa_othdiscolor,
                        'pa_othnil' => $request->pa_othnil,
                        'pa_notes' => $request->pa_notes,
                        'location' => 'TRIAGE',
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'arrival_date' => $request->reg_date,
                    ]);
            }

            DB::table('hisdb.episode')
                ->where('mrn','=',$request->mrn_ti)
                ->where('episno','=',$request->episno_ti)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'nurse_stat' =>  1,
                    'diagprov' => $request->diagnosis,
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                ]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response('Error DB rollback!'.$e, 500);
        }
    }

    public function add_triage(Request $request){

        DB::beginTransaction();

        try {

            $location = $this->get_location($request->mrn_ti,$request->episno_ti);
            
            DB::table('nursing.nursassessment')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_ti,
                        'episno' => $request->episno_ti,
                        'admwardtime' => $request->admwardtime,
                        'triagecolor' => $request->triagecolor,
                        'admreason' => $request->admreason,
                        'currentmedication' => $request->currentmedication,
                        'diagnosis' => $request->diagnosis,
                        'vs_temperature' => $request->vs_temperature,
                        'vs_pulse' => $request->vs_pulse,
                        'vs_respiration' => $request->vs_respiration,
                        'vs_bp_sys1' => $request->vs_bp_sys1,
                        'vs_bp_dias2' => $request->vs_bp_dias2,
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
                        'fra_prevfalls' => $request->fra_prevfalls,
                        'fra_age' => $request->fra_age,
                        'fra_physicalLimitation' => $request->fra_physicalLimitation,
                        'fra_neurologicaldeficit' => $request->fra_neurologicaldeficit,
                        'fra_dizziness' => $request->fra_dizziness,
                        'fra_cerebralaccident' => $request->fra_cerebralaccident,
                        'fra_notatrisk' => $request->fra_notatrisk,
                        'fra_atrisk' => $request->fra_atrisk,
                        'psra_incontinent' => $request->psra_incontinent,
                        'psra_immobility' => $request->psra_immobility,
                        'psra_poorskintype' => $request->psra_poorskintype,
                        'psra_notatrisk' => $request->psra_notatrisk,
                        'psra_atrisk' => $request->psra_atrisk,
                        'location' => $location,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);

            DB::table('nursing.nurshistory')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_ti,
                        'medicalhistory' => $request->medicalhistory,
                        'surgicalhistory' => $request->surgicalhistory,
                        'familymedicalhist' => $request->familymedicalhist,
                        'allergydrugs' => $request->allergydrugs,
                        'drugs_remarks' => $request->drugs_remarks,
                        'allergyplaster' => $request->allergyplaster,
                        'plaster_remarks' => $request->plaster_remarks,
                        'allergyfood' => $request->allergyfood,
                        'food_remarks' => $request->food_remarks,
                        'allergyenvironment' => $request->allergyenvironment,
                        'environment_remarks' => $request->environment_remarks,
                        'allergyothers' => $request->allergyothers,
                        'others_remarks' => $request->others_remarks,
                        'allergyunknown' => $request->allergyunknown,
                        'unknown_remarks' => $request->unknown_remarks,
                        'allergynone' => $request->allergynone,
                        'none_remarks' => $request->none_remarks,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);

            DB::table('nursing.nursassessgen')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_ti,
                        'episno' => $request->episno_ti,
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
                        // TRIAGE PHYSICAL ASSESSMENT
                        'pa_skindry' => $request->pa_skindry,
                        'pa_skinodema' => $request->pa_skinodema,
                        'pa_skinjaundice' => $request->pa_skinjaundice,
                        'pa_skinnil' => $request->pa_skinnil,
                        'pa_othbruises' => $request->pa_othbruises,
                        'pa_othdeculcer' => $request->pa_othdeculcer,
                        'pa_othlaceration' => $request->pa_othlaceration,
                        'pa_othdiscolor' => $request->pa_othdiscolor,
                        'pa_othnil' => $request->pa_othnil,
                        'pa_notes' => $request->pa_notes,
                        'location' => $location,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);

            // DB::table('hisdb.episode')
            //         ->insert([
            //             'compcode' => session('compcode'),
            //             'mrn' => $request->mrn_ti,
            //             'episno' => $request->episno_ti,
            //             'diagprov' => $request->diagnosis,
            //             'adduser'  => session('username'),
            //             'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
            //             'lastuser'  => session('username'),
            //             'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
            //         ]);

            DB::table('hisdb.episode')
                ->where('mrn','=',$request->mrn_ti)
                ->where('episno','=',$request->episno_ti)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'diagprov' => $request->diagnosis,
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                ]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response('Error DB rollback!'.$e, 500);
        }
    }

    public function edit_triage(Request $request){

        DB::beginTransaction();

        try {

            $location = $this->get_location($request->mrn_ti,$request->episno_ti);

            $nursassessment_triageinfo = DB::table('nursing.nursassessment')
                ->where('mrn','=',$request->mrn_ti)
                ->where('episno','=',$request->episno_ti)
                ->where('compcode','=',session('compcode'))
                ->where('location','=', $location);

            if(!$nursassessment_triageinfo->exists()){
                DB::table('nursing.nursassessment')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_ti,
                        'episno' => $request->episno_ti,
                        'admwardtime' => $request->admwardtime,
                        'triagecolor' => $request->triagecolor,
                        'admreason' => $request->admreason,
                        'currentmedication' => $request->currentmedication,
                        'diagnosis' => $request->diagnosis,
                        'vs_temperature' => $request->vs_temperature,
                        'vs_pulse' => $request->vs_pulse,
                        'vs_respiration' => $request->vs_respiration,
                        'vs_bp_sys1' => $request->vs_bp_sys1,
                        'vs_bp_dias2' => $request->vs_bp_dias2,
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
                        'fra_prevfalls' => $request->fra_prevfalls,
                        'fra_age' => $request->fra_age,
                        'fra_physicalLimitation' => $request->fra_physicalLimitation,
                        'fra_neurologicaldeficit' => $request->fra_neurologicaldeficit,
                        'fra_dizziness' => $request->fra_dizziness,
                        'fra_cerebralaccident' => $request->fra_cerebralaccident,
                        'fra_notatrisk' => $request->fra_notatrisk,
                        'fra_atrisk' => $request->fra_atrisk,
                        'psra_incontinent' => $request->psra_incontinent,
                        'psra_immobility' => $request->psra_immobility,
                        'psra_poorskintype' => $request->psra_poorskintype,
                        'psra_notatrisk' => $request->psra_notatrisk,
                        'psra_atrisk' => $request->psra_atrisk,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }else{
                $nursassessment_triageinfo
                    ->update([
                        'admwardtime' => $request->admwardtime,
                        'triagecolor' => $request->triagecolor,
                        'admreason' => $request->admreason,
                        'currentmedication' => $request->currentmedication,
                        'diagnosis' => $request->diagnosis,
                        'vs_temperature' => $request->vs_temperature,
                        'vs_pulse' => $request->vs_pulse,
                        'vs_respiration' => $request->vs_respiration,
                        'vs_bp_sys1' => $request->vs_bp_sys1,
                        'vs_bp_dias2' => $request->vs_bp_dias2,
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
                        'fra_prevfalls' => $request->fra_prevfalls,
                        'fra_age' => $request->fra_age,
                        'fra_physicalLimitation' => $request->fra_physicalLimitation,
                        'fra_neurologicaldeficit' => $request->fra_neurologicaldeficit,
                        'fra_dizziness' => $request->fra_dizziness,
                        'fra_cerebralaccident' => $request->fra_cerebralaccident,
                        'fra_notatrisk' => $request->fra_notatrisk,
                        'fra_atrisk' => $request->fra_atrisk,
                        'psra_incontinent' => $request->psra_incontinent,
                        'psra_immobility' => $request->psra_immobility,
                        'psra_poorskintype' => $request->psra_poorskintype,
                        'psra_notatrisk' => $request->psra_notatrisk,
                        'psra_atrisk' => $request->psra_atrisk,
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }

            $nurshistory_triage = DB::table('nursing.nurshistory')
                ->where('mrn','=',$request->mrn_ti)
                ->where('compcode','=',session('compcode'));

            if(!$nurshistory_triage->exists()){
                DB::table('nursing.nurshistory')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_ti,
                        'medicalhistory' => $request->medicalhistory,
                        'surgicalhistory' => $request->surgicalhistory,
                        'familymedicalhist' => $request->familymedicalhist,
                        'allergydrugs' => $request->allergydrugs,
                        'drugs_remarks' => $request->drugs_remarks,
                        'allergyplaster' => $request->allergyplaster,
                        'plaster_remarks' => $request->plaster_remarks,
                        'allergyfood' => $request->allergyfood,
                        'food_remarks' => $request->food_remarks,
                        'allergyenvironment' => $request->allergyenvironment,
                        'environment_remarks' => $request->environment_remarks,
                        'allergyothers' => $request->allergyothers,
                        'others_remarks' => $request->others_remarks,
                        'allergyunknown' => $request->allergyunknown,
                        'unknown_remarks' => $request->unknown_remarks,
                        'allergynone' => $request->allergynone,
                        'none_remarks' => $request->none_remarks,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }else{
                $nurshistory_triage
                    ->update([
                        'medicalhistory' => $request->medicalhistory,
                        'surgicalhistory' => $request->surgicalhistory,
                        'familymedicalhist' => $request->familymedicalhist,
                        'allergydrugs' => $request->allergydrugs,
                        'drugs_remarks' => $request->drugs_remarks,
                        'allergyplaster' => $request->allergyplaster,
                        'plaster_remarks' => $request->plaster_remarks,
                        'allergyfood' => $request->allergyfood,
                        'food_remarks' => $request->food_remarks,
                        'allergyenvironment' => $request->allergyenvironment,
                        'environment_remarks' => $request->environment_remarks,
                        'allergyothers' => $request->allergyothers,
                        'others_remarks' => $request->others_remarks,
                        'allergyunknown' => $request->allergyunknown,
                        'unknown_remarks' => $request->unknown_remarks,
                        'allergynone' => $request->allergynone,
                        'none_remarks' => $request->none_remarks,
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }      

            $nursassessgen_triageinfo = DB::table('nursing.nursassessgen')
                ->where('mrn','=',$request->mrn_ti)
                ->where('episno','=',$request->episno_ti)
                ->where('compcode','=',session('compcode'))
                ->where('location','=',$location);

            if(!$nursassessgen_triageinfo->exists()){
                DB::table('nursing.nursassessgen')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_ti,
                        'episno' => $request->episno_ti,
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
                        // TRIAGE PHYSICAL ASSESSMENT
                        'pa_skindry' => $request->pa_skindry,
                        'pa_skinodema' => $request->pa_skinodema,
                        'pa_skinjaundice' => $request->pa_skinjaundice,
                        'pa_skinnil' => $request->pa_skinnil,
                        'pa_othbruises' => $request->pa_othbruises,
                        'pa_othdeculcer' => $request->pa_othdeculcer,
                        'pa_othlaceration' => $request->pa_othlaceration,
                        'pa_othdiscolor' => $request->pa_othdiscolor,
                        'pa_othnil' => $request->pa_othnil,
                        'pa_notes' => $request->pa_notes,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }else{
                $nursassessgen_triageinfo
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
                        // TRIAGE PHYSICAL ASSESSMENT
                        'pa_skindry' => $request->pa_skindry,
                        'pa_skinodema' => $request->pa_skinodema,
                        'pa_skinjaundice' => $request->pa_skinjaundice,
                        'pa_skinnil' => $request->pa_skinnil,
                        'pa_othbruises' => $request->pa_othbruises,
                        'pa_othdeculcer' => $request->pa_othdeculcer,
                        'pa_othlaceration' => $request->pa_othlaceration,
                        'pa_othdiscolor' => $request->pa_othdiscolor,
                        'pa_othnil' => $request->pa_othnil,
                        'pa_notes' => $request->pa_notes,
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }

            DB::table('hisdb.episode')
                ->where('mrn','=',$request->mrn_ti)
                ->where('episno','=',$request->episno_ti)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'diagprov' => $request->diagnosis,
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                ]);

            // $examidno = [];
            // $examsel = [];
            // $examnote = [];

            // foreach($request->all() as $key => $value) {
            //     if(strpos($key, "examnote") === 0){
            //         array_push($examnote, $value);
            //     }else if(strpos($key, "examsel") === 0){
            //         array_push($examsel, $value);
            //     }else if(strpos($key, "examidno") === 0){
            //         array_push($examidno, $value);
            //     }
            // }

            // foreach ($examidno as $key => $value) {
            //     if($value=='0'){
            //         DB::table('nursing.nurassesexam')
            //             ->insert([
            //                 'compcode' => session('compcode'),
            //                 'mrn' => $request->mrn_ti,
            //                 'episno' => $request->episno_ti,
            //                 'location' => $location,
            //                 'exam' => $examsel[$key],
            //                 'examnote' => $examnote[$key]
            //             ]);
            //     }else{
            //         DB::table('nursing.nurassesexam')
            //             ->where('idno','=',$value)
            //             ->update([
            //                 'exam' => $examsel[$key],
            //                 'examnote' => $examnote[$key]
            //             ]);
            //     }
            // }

            $queries = DB::getQueryLog();
            // dump($queries);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response('Error DB rollback!'.$e, 500);
        }
    }

    public function get_table_triage(Request $request){

        // $location = $this->get_location($request->mrn,$request->episno);
        $responce = new stdClass();

        $arrival_date = Carbon::createFromFormat('d-m-Y', $request->arrival_date)->format('Y-m-d');

        $triage_obj = DB::table('nursing.nursassessment')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$request->mrn)
                    ->where('episno','=',$request->episno)
                    ->where('arrival_date','=',$arrival_date);

        $triage_gen_obj = DB::table('nursing.nursassessgen')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$request->mrn)
                    ->where('episno','=',$request->episno)
                    ->where('arrival_date','=',$arrival_date);

        // $triage_exm_obj = DB::table('nursing.nurassesexam')
        //             ->where('compcode','=',session('compcode'))
        //             ->where('mrn','=',$request->mrn)
        //             ->where('episno','=',$request->episno)
        //             ->where('arrival_date','=',$arrival_date);

        // $triage_regdate_obj = DB::table('hisdb.episode')
        //             ->select('reg_date')
        //             ->where('compcode','=',session('compcode'))
        //             ->where('mrn','=',$request->mrn)
        //             ->where('episno','=',$request->episno);
                    
        $triage_nurshistory_obj = DB::table('nursing.nurshistory')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$request->mrn);

        $responce = new stdClass();

        if($triage_obj->exists()){
            $triage_obj = $triage_obj->first();
            $responce->triage = $triage_obj;
        }

        if($triage_gen_obj->exists()){
            $triage_gen_obj = $triage_gen_obj->first();
            $responce->triage_gen = $triage_gen_obj;
        }

        // if($triage_exm_obj->exists()){
        //     $triage_exm_obj = $triage_exm_obj->get()->toArray();
        //     $responce->triage_exm = $triage_exm_obj;
        // }

        $responce->triage_regdate = $arrival_date;

        if($triage_nurshistory_obj->exists()){
            $triage_nurshistory_obj = $triage_nurshistory_obj->first();
            $responce->triage_nurshistory = $triage_nurshistory_obj;
        }

        return json_encode($responce);

    }

    public function add_exam(Request $request){

        DB::beginTransaction();

        try {

            DB::table('nursing.nurassesexam')
                ->insert([  
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn,
                    'episno' => $request->episno,
                    'location' => 'TRIAGE',
                    'exam' => $request->exam,
                    'examnote' => $request->examnote,
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'adduser'  => session('username')
                ]);

             DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function edit_exam(Request $request){
        
        DB::beginTransaction();

        try {

            DB::table('nursing.nurassesexam')
                ->where('idno','=',$request->idno)
                ->update([  
                    'exam' => $request->exam,
                    'examnote' => $request->examnote
                ]); 

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function add_more_exam(Request $request){

        DB::beginTransaction();

        try {

            DB::table('nursing.examination')
                ->insert([  
                    'compcode' => session('compcode'),
                    'examcode' => $request->examcode,
                    'description' => $request->description,
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'adduser'  => session('username')
                ]);

             DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function get_location($mrn,$episno){

        $epistype = DB::table('hisdb.episode')
            ->where('compcode','=',session('compcode'))
            ->where('mrn','=',$mrn)
            ->where('episno','=',$episno);

        if($epistype->exists()){
            $epistype = $epistype->first();
            $epistype = $epistype->epistycode;
        }

        if($epistype == 'IP' || $epistype == 'DP' ){
            $location = 'WARD';
        }else{
            $location = 'TRIAGE';
        }

        return $location;
    }

    public function addNotes_triage(Request $request){

        DB::beginTransaction();

        try {

            DB::table('nursing.triage_addnotes')
                ->insert([  
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn,
                    'episno' => $request->episno,
                    'location' => 'TRIAGE',
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

    public function get_table_date_curr(Request $request){

        $responce = new stdClass();

        $data = [];

        // $dialysis_episode = DB::table('hisdb.dialysis_episode')
        //     ->select('mrn','episno','arrival_time','arrival_date')
        //     ->where('compcode','=',session('compcode'))
        //     ->where('mrn','=',$request->mrn)
        //     ->where('episno','=',$request->episno)
        //     ->orderBy('arrival_date','desc');

        // if($dialysis_episode->exists()){
        //     $dialysis_episode = $dialysis_episode->get();

        //     foreach ($dialysis_episode as $key => $value) {

        //         $pathealth = DB::table('nursing.nursassessment')
        //             ->select('mrn','episno','admwardtime','adduser','adddate')
        //             ->where('compcode','=',session('compcode'))
        //             ->where('mrn','=',$value->mrn)
        //             ->where('episno','=',$value->episno)
        //             ->where('arrival_date','=',$value->arrival_date)
        //             ->orderBy('idno','desc');

        //         if($pathealth->exists()){
                    
        //             $pathealth = $pathealth->get();

        //             foreach ($pathealth as $key2 => $value2) {
        //                 $date['date'] = Carbon::createFromFormat('Y-m-d', $value->arrival_date)->format('d-m-Y');
        //                 $date['mrn'] = $value2->mrn;
        //                 $date['episno'] = $value2->episno;
        //                 $date['adduser'] = $value2->adduser;
        //                 $date['adddate'] = $value2->adddate;
        //                 $date['recordtime'] = $value2->admwardtime;
        //                 $date['type'] = 'nursassessment';

        //                 array_push($data,$date);
        //             }

        //         }else{
                    
        //             if(!Carbon::createFromFormat('Y-m-d', $value->arrival_date)->isToday()){
        //                 continue;
        //             }
                    
        //             $date['date'] = Carbon::createFromFormat('Y-m-d', $value->arrival_date)->format('d-m-Y');
        //             $date['mrn'] = $value->mrn;
        //             $date['episno'] = $value->episno;
        //             $date['adduser'] = session('username');
        //             $date['adddate'] = $value->arrival_date;
        //             $date['recordtime'] = $value->arrival_time;
        //             $date['type'] = 'episode';

        //             array_push($data,$date);
        //         }
        //     }

        $pathealth = DB::table('nursing.nursassessment')
                ->where('compcode','=',session('compcode'))
                ->where('mrn','=',$request->mrn)
                ->where('episno','=',$request->episno)
                ->whereYear('arrival_date', '=', Carbon::now("Asia/Kuala_Lumpur")->year)
                ->whereMonth('arrival_date', '=', Carbon::now("Asia/Kuala_Lumpur")->month)
                ->orderBy('idno','desc');

        if($pathealth->exists()){
            
            $pathealth = $pathealth->get();

            $gottoday = false;
            foreach ($pathealth as $key2 => $value2) {

                if(Carbon::createFromFormat('Y-m-d', $value2->arrival_date)->isToday()){
                    $gottoday = true;
                }

                $date['date'] = Carbon::createFromFormat('Y-m-d', $value2->arrival_date)->format('d-m-Y');
                $date['mrn'] = $value2->mrn;
                $date['episno'] = $value2->episno;
                $date['adduser'] = $value2->adduser;
                $date['adddate'] = $value2->adddate;
                $date['recordtime'] = $value2->admwardtime;
                $date['type'] = 'nursassessment';

                array_push($data,$date);
            }

            if(!$gottoday){

                $date['date'] = Carbon::now()->format('d-m-Y');
                $date['mrn'] = $request->mrn;
                $date['episno'] = $request->episno;
                $date['adduser'] = 'system';
                $date['adddate'] = Carbon::now()->format('d-m-Y');
                $date['recordtime'] = Carbon::now()->format('H:i:s');
                $date['type'] = 'episode';

                array_unshift($data , $date);
            }

        }else{
            $date['date'] = Carbon::now()->format('d-m-Y');
            $date['mrn'] = $request->mrn;
            $date['episno'] = $request->episno;
            $date['adduser'] = 'system';
            $date['adddate'] = Carbon::now()->format('d-m-Y');
            $date['recordtime'] = Carbon::now()->format('H:i:s');
            $date['type'] = 'episode';

            array_push($data , $date);
        }

        $responce->data = $data;

        return json_encode($responce);
    }

    public function get_table_date_past(Request $request){

        $responce = new stdClass();

        $data = [];

        $dialysis_episode = DB::table('hisdb.dialysis_episode')
                    ->select('mrn','episno','arrival_time','arrival_date')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$request->mrn)
                    ->orderBy('arrival_date','desc');

        if($dialysis_episode->exists()){
            $dialysis_episode = $dialysis_episode->get();

            foreach ($dialysis_episode as $key => $value) {
                $pathealth = DB::table('nursing.nursassessment')
                    ->select('mrn','episno','admwardtime','adduser','adddate')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$value->mrn)
                    ->where('episno','=',$value->episno)
                    ->where('arrival_date','=',$value->arrival_date)
                    ->orderBy('idno','desc');

                if($pathealth->exists()){
                    
                    $pathealth = $pathealth->get();

                    foreach ($pathealth as $key2 => $value2) {
                        $date['date'] = Carbon::createFromFormat('Y-m-d', $value->arrival_date)->format('d-m-Y');
                        $date['mrn'] = $value2->mrn;
                        $date['episno'] = $value2->episno;
                        $date['adduser'] = $value2->adduser;
                        $date['adddate'] = $value2->adddate;
                        $date['recordtime'] = $value2->admwardtime;
                        $date['type'] = 'nursassessment';

                        array_push($data,$date);
                    }

                }else{

                    if(!Carbon::createFromFormat('Y-m-d', $value->arrival_date)->isToday()){
                        continue;
                    }
                    
                    $date['date'] = Carbon::createFromFormat('Y-m-d', $value->arrival_date)->format('d-m-Y');
                    $date['mrn'] = $value->mrn;
                    $date['episno'] = $value->episno;
                    $date['adduser'] = session('username');
                    $date['adddate'] = $value->arrival_date;
                    $date['recordtime'] = $value->arrival_time;
                    $date['type'] = 'episode';

                    array_push($data,$date);
                }
            }

            $responce->data = $data;

        }else{
            $responce->data = [];
        }

        return json_encode($responce);
    }

}