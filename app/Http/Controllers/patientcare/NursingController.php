<?php

namespace App\Http\Controllers\patientcare;

use Illuminate\Http\Request;
use stdClass;
use DB;
use Carbon\Carbon;
use App\Http\Controllers\defaultController;

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
        return view('patientcare.hisdb.nursing.nursing');
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
            
            DB::table('nursing.nursassessment')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_ti,
                        'episno' => $request->episno_ti,
                        'admwardtime' => $request->admwardtime,
                        'triagecolor' => $request->triagecolor,
                        'admreason' => $request->admreason,
                        'currentmedication' => $request->currentmedication,
                        'vs_temperature' => $request->vs_temperature,
                        'vs_pulse' => $request->vs_pulse,
                        'vs_respiration' => $request->vs_respiration,
                        'vs_bp_sys1' => $request->vs_bp_sys1,
                        'vs_bp_dias2' => $request->vs_bp_dias2,
                        'vs_height' => $request->vs_height,
                        'vs_weight' => $request->vs_weight,
                        'vs_gxt' => $request->vs_gxt,
                        'vs_spo' => $request->vs_spo,
                        'moa_walkin' => $request->moa_walkin,
                        'moa_wheelchair' => $request->moa_wheelchair,
                        'moa_trolley' => $request->moa_trolley,
                        'moa_carried' => $request->moa_carried,
                        'moa_accpera' => $request->moa_accpera,
                        'moa_accperna' => $request->moa_accperna,
                        'moa_accperna_note' => $request->moa_accperna_note,
                        'ms_orientated' => $request->ms_orientated,
                        'ms_confused' => $request->ms_confused,
                        'ms_semiconscious' => $request->ms_semiconscious,
                        'ms_unconscious' => $request->ms_unconscious,
                        'tpa_oxygen' => $request->tpa_oxygen,
                        'tpa_ccollar' => $request->tpa_ccollar,
                        'tpa_backboard' => $request->tpa_backboard,
                        'tpa_icepack' => $request->tpa_icepack,
                        'tpa_others' => $request->tpa_others,
                        'tpa_medication' => $request->tpa_medication,
                        'tpa_medication_note' => $request->tpa_medication_note,
                        'pi_labinv' => $request->pi_labinv,
                        'pi_labinv_remarks' => $request->pi_labinv_remarks,
                        'pi_bloodprod' => $request->pi_bloodprod,
                        'pi_bloodprod_remarks' => $request->pi_bloodprod_remarks,
                        'pi_diaginv' => $request->pi_diaginv,
                        'pi_diaginv_remarks' => $request->pi_diaginv_remarks,
                        'pi_ecg' => $request->pi_ecg,
                        'pi_abg' => $request->pi_abg,
                        'pi_codeblue' => $request->pi_codeblue,
                        'mos_ivfluids' => $request->mos_ivfluids,
                        'mos_ivfluids_remarks' => $request->mos_ivfluids_remarks,
                        'mos_oxygen' => $request->mos_oxygen,
                        'mos_oxygen_remarks' => $request->mos_oxygen_remarks,
                        'mos_woundprep' => $request->mos_woundprep,
                        'mos_woundprep_remarks' => $request->mos_woundprep_remarks,
                        'mos_sci' => $request->mos_sci,
                        'vsd_bp_sys1' => $request->vsd_bp_sys1,
                        'vsd_bp_dias2' => $request->vsd_bp_dias2,
                        'vsd_pulse' => $request->vsd_pulse,
                        'vsd_temperature' => $request->vsd_temperature,
                        'vsd_respiration' => $request->vsd_respiration,
                        'vsd_spo' => $request->vsd_spo,
                        'vsd_cbs' => $request->vsd_cbs,
                        'vsd_pefr' => $request->vsd_pefr,
                        'vsd_gcs' => $request->vsd_gcs,
                        'vsd_pain' => $request->vsd_pain,
                        'vsd_painscore' => $request->vsd_painscore,
                        'vsd_painroomair' => $request->vsd_painroomair,
                        'vsd_painoxygen' => $request->vsd_painoxygen,
                        'vsd_painoxygen_note' => $request->vsd_painoxygen_note,
                        'mod_walk' => $request->mod_walk,
                        'mod_carried' => $request->mod_carried,
                        'mod_trolley' => $request->mod_trolley,
                        'mod_wheelchair' => $request->mod_wheelchair,
                        'mod_ambulance' => $request->mod_ambulance,
                        'painscore' => $request->painscore,
                        'dop_arching' => $request->dop_arching,
                        'dop_throbbing' => $request->dop_throbbing,
                        'dop_stabbing' => $request->dop_stabbing,
                        'dop_sharp' => $request->dop_sharp,
                        'dop_burning' => $request->dop_burning,
                        'dop_numb' => $request->dop_numb,
                        'location' => 'TRIAGE',
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => $request->lastuser,
                        'lastupdate'  => $request->lastupdate
                        // 'diagnosis' => $request->diagnosis,
                        // 'vs_painscore' => $request->vs_painscore,
                        // 'moa_others' => $request->moa_others,
                        // 'loc_conscious' => $request->loc_conscious,
                        // 'loc_semiconscious' => $request->loc_semiconscious,
                        // 'loc_unconscious' => $request->loc_unconscious,
                        // 'es_calm' => $request->es_calm,
                        // 'es_anxious' => $request->es_anxious,
                        // 'es_distress' => $request->es_distress,
                        // 'es_depressed' => $request->es_depressed,
                        // 'es_irritable' => $request->es_irritable,
                        // 'fra_prevfalls' => $request->fra_prevfalls,
                        // 'fra_age' => $request->fra_age,
                        // 'fra_physicalLimitation' => $request->fra_physicalLimitation,
                        // 'fra_neurologicaldeficit' => $request->fra_neurologicaldeficit,
                        // 'fra_dizziness' => $request->fra_dizziness,
                        // 'fra_cerebralaccident' => $request->fra_cerebralaccident,
                        // 'fra_notatrisk' => $request->fra_notatrisk,
                        // 'fra_atrisk' => $request->fra_atrisk,
                        // 'psra_incontinent' => $request->psra_incontinent,
                        // 'psra_immobility' => $request->psra_immobility,
                        // 'psra_poorskintype' => $request->psra_poorskintype,
                        // 'psra_notatrisk' => $request->psra_notatrisk,
                        // 'psra_atrisk' => $request->psra_atrisk,
                        // 'ms_restless' => $request->ms_restless,
                        // 'ms_aggressive' => $request->ms_aggressive,
                    ]);

            DB::table('nursing.nurshistory')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_ti,
                        'medhis_heartdisease' => $request->medhis_heartdisease,
                        'medhis_seizures' => $request->medhis_seizures,
                        'medhis_diabetes' => $request->medhis_diabetes,
                        'medhis_bloodisorder' => $request->medhis_bloodisorder,
                        'medhis_hypertension' => $request->medhis_hypertension,
                        'medhis_asthma' => $request->medhis_asthma,
                        'medhis_cva' => $request->medhis_cva,
                        'medhis_crf' => $request->medhis_crf,
                        'medhis_cancer' => $request->medhis_cancer,
                        'medhis_drugabuse' => $request->medhis_drugabuse,
                        'medhis_oth' => $request->medhis_oth,
                        'medhis_oth_note' => $request->medhis_oth_note,
                        'allergydrugs' => $request->allergydrugs,
                        'drugs_remarks' => $request->drugs_remarks,
                        'allergyfood' => $request->allergyfood,
                        'food_remarks' => $request->food_remarks,
                        'allergyothers' => $request->allergyothers,
                        'others_remarks' => $request->others_remarks,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        // 'medicalhistory' => $request->medicalhistory,
                        // 'surgicalhistory' => $request->surgicalhistory,
                        // 'familymedicalhist' => $request->familymedicalhist,
                        // 'allergyplaster' => $request->allergyplaster,
                        // 'plaster_remarks' => $request->plaster_remarks,
                        // 'allergyenvironment' => $request->allergyenvironment,
                        // 'environment_remarks' => $request->environment_remarks,
                        // 'allergyunknown' => $request->allergyunknown,
                        // 'unknown_remarks' => $request->unknown_remarks,
                        // 'allergynone' => $request->allergynone,
                        // 'none_remarks' => $request->none_remarks,
                    ]);

            DB::table('nursing.nursassessgen')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_ti,
                        'episno' => $request->episno_ti,
                        'gsc_eye' => $request->gsc_eye,
                        'gsc_verbal' => $request->gsc_verbal,
                        'gsc_motor' => $request->gsc_motor,
                        'location' => 'TRIAGE',
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        // TRIAGE PHYSICAL ASSESSMENT
                        // 'pa_skindry' => $request->pa_skindry,
                        // 'pa_skinodema' => $request->pa_skinodema,
                        // 'pa_skinjaundice' => $request->pa_skinjaundice,
                        // 'pa_skinnil' => $request->pa_skinnil,
                        // 'pa_othbruises' => $request->pa_othbruises,
                        // 'pa_othdeculcer' => $request->pa_othdeculcer,
                        // 'pa_othlaceration' => $request->pa_othlaceration,
                        // 'pa_othdiscolor' => $request->pa_othdiscolor,
                        // 'pa_othnil' => $request->pa_othnil,
                        // 'pa_notes' => $request->pa_notes,
                        // 'br_breathing' => $request->br_breathing,
                        // 'br_breathingdesc' => $request->br_breathingdesc,
                        // 'br_cough' => $request->br_cough,
                        // 'br_coughdesc' => $request->br_coughdesc,
                        // 'br_smoke' => $request->br_smoke,
                        // 'br_smokedesc' => $request->br_smokedesc,
                        // 'ed_eatdrink' => $request->ed_eatdrink,
                        // 'ed_eatdrinkdesc' => $request->ed_eatdrinkdesc,
                        // 'eb_bowelhabit' => $request->eb_bowelhabit,
                        // 'eb_bowelmove' => $request->eb_bowelmove,
                        // 'eb_bowelmovedesc' => $request->eb_bowelmovedesc,
                        // 'bl_urine' => $request->bl_urine,
                        // 'bl_urinedesc' => $request->bl_urinedesc,
                        // 'bl_urinefreq' => $request->bl_urinefreq,
                        // 'sl_sleep' => $request->sl_sleep,
                        // 'mobilityambulan' => $request->mobilityambulan,
                        // 'mobilityassistaid' => $request->mobilityassistaid,
                        // 'mobilitybedridden' => $request->mobilitybedridden,
                        // 'phygiene_self' => $request->phygiene_self,
                        // 'phygiene_needassist' => $request->phygiene_needassist,
                        // 'phygiene_dependant' => $request->phygiene_dependant,
                        // 'safeenv_siderail' => $request->safeenv_siderail,
                        // 'safeenv_restraint' => $request->safeenv_restraint,
                        // 'cspeech_normal' => $request->cspeech_normal,
                        // 'cspeech_slurred' => $request->cspeech_slurred,
                        // 'cspeech_impaired' => $request->cspeech_impaired,
                        // 'cspeech_mute' => $request->cspeech_mute,
                        // 'cvision_normal' => $request->cvision_normal,
                        // 'cvision_blurring' => $request->cvision_blurring,
                        // 'cvision_doublev' => $request->cvision_doublev,
                        // 'cvision_blind' => $request->cvision_blind,
                        // 'cvision_visualaids' => $request->cvision_visualaids,
                        // 'chearing_normal' => $request->chearing_normal,
                        // 'chearing_deaf' => $request->chearing_deaf,
                        // 'chearing_hardhear' => $request->chearing_hardhear,
                        // 'chearing_hearaids' => $request->chearing_hearaids,
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

            // DB::table('hisdb.episode')
            //     ->where('mrn','=',$request->mrn_ti)
            //     ->where('episno','=',$request->episno_ti)
            //     ->where('compcode','=',session('compcode'))
            //     ->update([
            //         'diagprov' => $request->diagnosis,
            //         'lastuser'  => session('username'),
            //         'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
            //     ]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response('Error DB rollback!'.$e, 500);
        }
    }

    public function edit(Request $request){

        DB::beginTransaction();

        try {

            $nursassessment_triage = DB::table('nursing.nursassessment')
                ->where('mrn','=',$request->mrn_ti)
                ->where('episno','=',$request->episno_ti)
                ->where('compcode','=',session('compcode'))
                ->where('location','=','TRIAGE');

            if(!$nursassessment_triage->exists()){
                DB::table('nursing.nursassessment')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_ti,
                        'episno' => $request->episno_ti,
                        'admwardtime' => $request->admwardtime,
                        'triagecolor' => $request->triagecolor,
                        'admreason' => $request->admreason,
                        'currentmedication' => $request->currentmedication,
                        'vs_temperature' => $request->vs_temperature,
                        'vs_pulse' => $request->vs_pulse,
                        'vs_respiration' => $request->vs_respiration,
                        'vs_bp_sys1' => $request->vs_bp_sys1,
                        'vs_bp_dias2' => $request->vs_bp_dias2,
                        'vs_height' => $request->vs_height,
                        'vs_weight' => $request->vs_weight,
                        'vs_gxt' => $request->vs_gxt,
                        'vs_spo' => $request->vs_spo,
                        'moa_walkin' => $request->moa_walkin,
                        'moa_wheelchair' => $request->moa_wheelchair,
                        'moa_trolley' => $request->moa_trolley,
                        'moa_carried' => $request->moa_carried,
                        'moa_accpera' => $request->moa_accpera,
                        'moa_accperna' => $request->moa_accperna,
                        'moa_accperna_note' => $request->moa_accperna_note,
                        'ms_orientated' => $request->ms_orientated,
                        'ms_confused' => $request->ms_confused,
                        'ms_semiconscious' => $request->ms_semiconscious,
                        'ms_unconscious' => $request->ms_unconscious,
                        'tpa_oxygen' => $request->tpa_oxygen,
                        'tpa_ccollar' => $request->tpa_ccollar,
                        'tpa_backboard' => $request->tpa_backboard,
                        'tpa_icepack' => $request->tpa_icepack,
                        'tpa_others' => $request->tpa_others,
                        'tpa_medication' => $request->tpa_medication,
                        'tpa_medication_note' => $request->tpa_medication_note,
                        'pi_labinv' => $request->pi_labinv,
                        'pi_labinv_remarks' => $request->pi_labinv_remarks,
                        'pi_bloodprod' => $request->pi_bloodprod,
                        'pi_bloodprod_remarks' => $request->pi_bloodprod_remarks,
                        'pi_diaginv' => $request->pi_diaginv,
                        'pi_diaginv_remarks' => $request->pi_diaginv_remarks,
                        'pi_ecg' => $request->pi_ecg,
                        'pi_abg' => $request->pi_abg,
                        'pi_codeblue' => $request->pi_codeblue,
                        'mos_ivfluids' => $request->mos_ivfluids,
                        'mos_ivfluids_remarks' => $request->mos_ivfluids_remarks,
                        'mos_oxygen' => $request->mos_oxygen,
                        'mos_oxygen_remarks' => $request->mos_oxygen_remarks,
                        'mos_woundprep' => $request->mos_woundprep,
                        'mos_woundprep_remarks' => $request->mos_woundprep_remarks,
                        'mos_sci' => $request->mos_sci,
                        'vsd_bp_sys1' => $request->vsd_bp_sys1,
                        'vsd_bp_dias2' => $request->vsd_bp_dias2,
                        'vsd_pulse' => $request->vsd_pulse,
                        'vsd_temperature' => $request->vsd_temperature,
                        'vsd_respiration' => $request->vsd_respiration,
                        'vsd_spo' => $request->vsd_spo,
                        'vsd_cbs' => $request->vsd_cbs,
                        'vsd_pefr' => $request->vsd_pefr,
                        'vsd_gcs' => $request->vsd_gcs,
                        'vsd_pain' => $request->vsd_pain,
                        'vsd_painscore' => $request->vsd_painscore,
                        'vsd_painroomair' => $request->vsd_painroomair,
                        'vsd_painoxygen' => $request->vsd_painoxygen,
                        'vsd_painoxygen_note' => $request->vsd_painoxygen_note,
                        'mod_walk' => $request->mod_walk,
                        'mod_carried' => $request->mod_carried,
                        'mod_trolley' => $request->mod_trolley,
                        'mod_wheelchair' => $request->mod_wheelchair,
                        'mod_ambulance' => $request->mod_ambulance,
                        'painscore' => $request->painscore,
                        'dop_arching' => $request->dop_arching,
                        'dop_throbbing' => $request->dop_throbbing,
                        'dop_stabbing' => $request->dop_stabbing,
                        'dop_sharp' => $request->dop_sharp,
                        'dop_burning' => $request->dop_burning,
                        'dop_numb' => $request->dop_numb,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        // 'diagnosis' => $request->diagnosis,
                        // 'vs_painscore' => $request->vs_painscore,
                        // 'moa_others' => $request->moa_others,
                        // 'loc_conscious' => $request->loc_conscious,
                        // 'loc_semiconscious' => $request->loc_semiconscious,
                        // 'loc_unconscious' => $request->loc_unconscious,
                        // 'es_calm' => $request->es_calm,
                        // 'es_anxious' => $request->es_anxious,
                        // 'es_distress' => $request->es_distress,
                        // 'es_depressed' => $request->es_depressed,
                        // 'es_irritable' => $request->es_irritable,
                        // 'fra_prevfalls' => $request->fra_prevfalls,
                        // 'fra_age' => $request->fra_age,
                        // 'fra_physicalLimitation' => $request->fra_physicalLimitation,
                        // 'fra_neurologicaldeficit' => $request->fra_neurologicaldeficit,
                        // 'fra_dizziness' => $request->fra_dizziness,
                        // 'fra_cerebralaccident' => $request->fra_cerebralaccident,
                        // 'fra_notatrisk' => $request->fra_notatrisk,
                        // 'fra_atrisk' => $request->fra_atrisk,
                        // 'psra_incontinent' => $request->psra_incontinent,
                        // 'psra_immobility' => $request->psra_immobility,
                        // 'psra_poorskintype' => $request->psra_poorskintype,
                        // 'psra_notatrisk' => $request->psra_notatrisk,
                        // 'psra_atrisk' => $request->psra_atrisk,
                        // 'ms_restless' => $request->ms_restless,
                        // 'ms_aggressive' => $request->ms_aggressive,
                    ]);
            }else{
                $nursassessment_triage
                    ->update([
                        'admwardtime' => $request->admwardtime,
                        'triagecolor' => $request->triagecolor,
                        'admreason' => $request->admreason,
                        'currentmedication' => $request->currentmedication,
                        'vs_temperature' => $request->vs_temperature,
                        'vs_pulse' => $request->vs_pulse,
                        'vs_respiration' => $request->vs_respiration,
                        'vs_bp_sys1' => $request->vs_bp_sys1,
                        'vs_bp_dias2' => $request->vs_bp_dias2,
                        'vs_height' => $request->vs_height,
                        'vs_weight' => $request->vs_weight,
                        'vs_gxt' => $request->vs_gxt,
                        'vs_spo' => $request->vs_spo,
                        'moa_walkin' => $request->moa_walkin,
                        'moa_wheelchair' => $request->moa_wheelchair,
                        'moa_trolley' => $request->moa_trolley,
                        'moa_carried' => $request->moa_carried,
                        'moa_accpera' => $request->moa_accpera,
                        'moa_accperna' => $request->moa_accperna,
                        'moa_accperna_note' => $request->moa_accperna_note,
                        'ms_orientated' => $request->ms_orientated,
                        'ms_confused' => $request->ms_confused,
                        'ms_semiconscious' => $request->ms_semiconscious,
                        'ms_unconscious' => $request->ms_unconscious,
                        'tpa_oxygen' => $request->tpa_oxygen,
                        'tpa_ccollar' => $request->tpa_ccollar,
                        'tpa_backboard' => $request->tpa_backboard,
                        'tpa_icepack' => $request->tpa_icepack,
                        'tpa_others' => $request->tpa_others,
                        'tpa_medication' => $request->tpa_medication,
                        'tpa_medication_note' => $request->tpa_medication_note,
                        'pi_labinv' => $request->pi_labinv,
                        'pi_labinv_remarks' => $request->pi_labinv_remarks,
                        'pi_bloodprod' => $request->pi_bloodprod,
                        'pi_bloodprod_remarks' => $request->pi_bloodprod_remarks,
                        'pi_diaginv' => $request->pi_diaginv,
                        'pi_diaginv_remarks' => $request->pi_diaginv_remarks,
                        'pi_ecg' => $request->pi_ecg,
                        'pi_abg' => $request->pi_abg,
                        'pi_codeblue' => $request->pi_codeblue,
                        'mos_ivfluids' => $request->mos_ivfluids,
                        'mos_ivfluids_remarks' => $request->mos_ivfluids_remarks,
                        'mos_oxygen' => $request->mos_oxygen,
                        'mos_oxygen_remarks' => $request->mos_oxygen_remarks,
                        'mos_woundprep' => $request->mos_woundprep,
                        'mos_woundprep_remarks' => $request->mos_woundprep_remarks,
                        'mos_sci' => $request->mos_sci,
                        'vsd_bp_sys1' => $request->vsd_bp_sys1,
                        'vsd_bp_dias2' => $request->vsd_bp_dias2,
                        'vsd_pulse' => $request->vsd_pulse,
                        'vsd_temperature' => $request->vsd_temperature,
                        'vsd_respiration' => $request->vsd_respiration,
                        'vsd_spo' => $request->vsd_spo,
                        'vsd_cbs' => $request->vsd_cbs,
                        'vsd_pefr' => $request->vsd_pefr,
                        'vsd_gcs' => $request->vsd_gcs,
                        'vsd_pain' => $request->vsd_pain,
                        'vsd_painscore' => $request->vsd_painscore,
                        'vsd_painroomair' => $request->vsd_painroomair,
                        'vsd_painoxygen' => $request->vsd_painoxygen,
                        'vsd_painoxygen_note' => $request->vsd_painoxygen_note,
                        'mod_walk' => $request->mod_walk,
                        'mod_carried' => $request->mod_carried,
                        'mod_trolley' => $request->mod_trolley,
                        'mod_wheelchair' => $request->mod_wheelchair,
                        'mod_ambulance' => $request->mod_ambulance,
                        'painscore' => $request->painscore,
                        'dop_arching' => $request->dop_arching,
                        'dop_throbbing' => $request->dop_throbbing,
                        'dop_stabbing' => $request->dop_stabbing,
                        'dop_sharp' => $request->dop_sharp,
                        'dop_burning' => $request->dop_burning,
                        'dop_numb' => $request->dop_numb,
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        // 'diagnosis' => $request->diagnosis,
                        // 'vs_painscore' => $request->vs_painscore,
                        // 'moa_others' => $request->moa_others,
                        // 'loc_conscious' => $request->loc_conscious,
                        // 'loc_semiconscious' => $request->loc_semiconscious,
                        // 'loc_unconscious' => $request->loc_unconscious,
                        // 'es_calm' => $request->es_calm,
                        // 'es_anxious' => $request->es_anxious,
                        // 'es_distress' => $request->es_distress,
                        // 'es_depressed' => $request->es_depressed,
                        // 'es_irritable' => $request->es_irritable,
                        // 'fra_prevfalls' => $request->fra_prevfalls,
                        // 'fra_age' => $request->fra_age,
                        // 'fra_physicalLimitation' => $request->fra_physicalLimitation,
                        // 'fra_neurologicaldeficit' => $request->fra_neurologicaldeficit,
                        // 'fra_dizziness' => $request->fra_dizziness,
                        // 'fra_cerebralaccident' => $request->fra_cerebralaccident,
                        // 'fra_notatrisk' => $request->fra_notatrisk,
                        // 'fra_atrisk' => $request->fra_atrisk,
                        // 'psra_incontinent' => $request->psra_incontinent,
                        // 'psra_immobility' => $request->psra_immobility,
                        // 'psra_poorskintype' => $request->psra_poorskintype,
                        // 'psra_notatrisk' => $request->psra_notatrisk,
                        // 'psra_atrisk' => $request->psra_atrisk,
                        // 'ms_restless' => $request->ms_restless,
                        // 'ms_aggressive' => $request->ms_aggressive,
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
                        'medhis_heartdisease' => $request->medhis_heartdisease,
                        'medhis_seizures' => $request->medhis_seizures,
                        'medhis_diabetes' => $request->medhis_diabetes,
                        'medhis_bloodisorder' => $request->medhis_bloodisorder,
                        'medhis_hypertension' => $request->medhis_hypertension,
                        'medhis_asthma' => $request->medhis_asthma,
                        'medhis_cva' => $request->medhis_cva,
                        'medhis_crf' => $request->medhis_crf,
                        'medhis_cancer' => $request->medhis_cancer,
                        'medhis_drugabuse' => $request->medhis_drugabuse,
                        'medhis_oth' => $request->medhis_oth,
                        'medhis_oth_note' => $request->medhis_oth_note,
                        'allergydrugs' => $request->allergydrugs,
                        'drugs_remarks' => $request->drugs_remarks,
                        'allergyfood' => $request->allergyfood,
                        'food_remarks' => $request->food_remarks,
                        'allergyothers' => $request->allergyothers,
                        'others_remarks' => $request->others_remarks,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        // 'medicalhistory' => $request->medicalhistory,
                        // 'surgicalhistory' => $request->surgicalhistory,
                        // 'familymedicalhist' => $request->familymedicalhist,
                        // 'allergyplaster' => $request->allergyplaster,
                        // 'plaster_remarks' => $request->plaster_remarks,
                        // 'allergyenvironment' => $request->allergyenvironment,
                        // 'environment_remarks' => $request->environment_remarks,
                        // 'allergyunknown' => $request->allergyunknown,
                        // 'unknown_remarks' => $request->unknown_remarks,
                        // 'allergynone' => $request->allergynone,
                        // 'none_remarks' => $request->none_remarks,
                    ]);
            }else{
                $nurshistory_triage
                    ->update([
                        'medhis_heartdisease' => $request->medhis_heartdisease,
                        'medhis_seizures' => $request->medhis_seizures,
                        'medhis_diabetes' => $request->medhis_diabetes,
                        'medhis_bloodisorder' => $request->medhis_bloodisorder,
                        'medhis_hypertension' => $request->medhis_hypertension,
                        'medhis_asthma' => $request->medhis_asthma,
                        'medhis_cva' => $request->medhis_cva,
                        'medhis_crf' => $request->medhis_crf,
                        'medhis_cancer' => $request->medhis_cancer,
                        'medhis_drugabuse' => $request->medhis_drugabuse,
                        'medhis_oth' => $request->medhis_oth,
                        'medhis_oth_note' => $request->medhis_oth_note,
                        'allergydrugs' => $request->allergydrugs,
                        'drugs_remarks' => $request->drugs_remarks,
                        'allergyfood' => $request->allergyfood,
                        'food_remarks' => $request->food_remarks,
                        'allergyothers' => $request->allergyothers,
                        'others_remarks' => $request->others_remarks,
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        // 'medicalhistory' => $request->medicalhistory,
                        // 'surgicalhistory' => $request->surgicalhistory,
                        // 'familymedicalhist' => $request->familymedicalhist,
                        // 'allergyplaster' => $request->allergyplaster,
                        // 'plaster_remarks' => $request->plaster_remarks,
                        // 'allergyenvironment' => $request->allergyenvironment,
                        // 'environment_remarks' => $request->environment_remarks,
                        // 'allergyunknown' => $request->allergyunknown,
                        // 'unknown_remarks' => $request->unknown_remarks,
                        // 'allergynone' => $request->allergynone,
                        // 'none_remarks' => $request->none_remarks,
                    ]);
            }

            $nursassessgen_triage = DB::table('nursing.nursassessgen')
                ->where('mrn','=',$request->mrn_ti)
                ->where('episno','=',$request->episno_ti)
                ->where('compcode','=',session('compcode'))
                ->where('location','=','TRIAGE');

            if(!$nursassessgen_triage->exists()){
                DB::table('nursing.nursassessgen')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_ti,
                        'episno' => $request->episno_ti,
                        'gsc_eye' => $request->gsc_eye,
                        'gsc_verbal' => $request->gsc_verbal,
                        'gsc_motor' => $request->gsc_motor,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        // TRIAGE PHYSICAL ASSESSMENT
                        // 'pa_skindry' => $request->pa_skindry,
                        // 'pa_skinodema' => $request->pa_skinodema,
                        // 'pa_skinjaundice' => $request->pa_skinjaundice,
                        // 'pa_skinnil' => $request->pa_skinnil,
                        // 'pa_othbruises' => $request->pa_othbruises,
                        // 'pa_othdeculcer' => $request->pa_othdeculcer,
                        // 'pa_othlaceration' => $request->pa_othlaceration,
                        // 'pa_othdiscolor' => $request->pa_othdiscolor,
                        // 'pa_othnil' => $request->pa_othnil,
                        // 'pa_notes' => $request->pa_notes,
                        // 'br_breathing' => $request->br_breathing,
                        // 'br_breathingdesc' => $request->br_breathingdesc,
                        // 'br_cough' => $request->br_cough,
                        // 'br_coughdesc' => $request->br_coughdesc,
                        // 'br_smoke' => $request->br_smoke,
                        // 'br_smokedesc' => $request->br_smokedesc,
                        // 'ed_eatdrink' => $request->ed_eatdrink,
                        // 'ed_eatdrinkdesc' => $request->ed_eatdrinkdesc,
                        // 'eb_bowelhabit' => $request->eb_bowelhabit,
                        // 'eb_bowelmove' => $request->eb_bowelmove,
                        // 'eb_bowelmovedesc' => $request->eb_bowelmovedesc,
                        // 'bl_urine' => $request->bl_urine,
                        // 'bl_urinedesc' => $request->bl_urinedesc,
                        // 'bl_urinefreq' => $request->bl_urinefreq,
                        // 'sl_sleep' => $request->sl_sleep,
                        // 'mobilityambulan' => $request->mobilityambulan,
                        // 'mobilityassistaid' => $request->mobilityassistaid,
                        // 'mobilitybedridden' => $request->mobilitybedridden,
                        // 'phygiene_self' => $request->phygiene_self,
                        // 'phygiene_needassist' => $request->phygiene_needassist,
                        // 'phygiene_dependant' => $request->phygiene_dependant,
                        // 'safeenv_siderail' => $request->safeenv_siderail,
                        // 'safeenv_restraint' => $request->safeenv_restraint,
                        // 'cspeech_normal' => $request->cspeech_normal,
                        // 'cspeech_slurred' => $request->cspeech_slurred,
                        // 'cspeech_impaired' => $request->cspeech_impaired,
                        // 'cspeech_mute' => $request->cspeech_mute,
                        // 'cvision_normal' => $request->cvision_normal,
                        // 'cvision_blurring' => $request->cvision_blurring,
                        // 'cvision_doublev' => $request->cvision_doublev,
                        // 'cvision_blind' => $request->cvision_blind,
                        // 'cvision_visualaids' => $request->cvision_visualaids,
                        // 'chearing_normal' => $request->chearing_normal,
                        // 'chearing_deaf' => $request->chearing_deaf,
                        // 'chearing_hardhear' => $request->chearing_hardhear,
                        // 'chearing_hearaids' => $request->chearing_hearaids,
                    ]);
            }else{
                $nursassessgen_triage
                    ->update([
                        'gsc_eye' => $request->gsc_eye,
                        'gsc_verbal' => $request->gsc_verbal,
                        'gsc_motor' => $request->gsc_motor,
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        // TRIAGE PHYSICAL ASSESSMENT
                        // 'pa_skindry' => $request->pa_skindry,
                        // 'pa_skinodema' => $request->pa_skinodema,
                        // 'pa_skinjaundice' => $request->pa_skinjaundice,
                        // 'pa_skinnil' => $request->pa_skinnil,
                        // 'pa_othbruises' => $request->pa_othbruises,
                        // 'pa_othdeculcer' => $request->pa_othdeculcer,
                        // 'pa_othlaceration' => $request->pa_othlaceration,
                        // 'pa_othdiscolor' => $request->pa_othdiscolor,
                        // 'pa_othnil' => $request->pa_othnil,
                        // 'pa_notes' => $request->pa_notes,
                        // 'br_breathing' => $request->br_breathing,
                        // 'br_breathingdesc' => $request->br_breathingdesc,
                        // 'br_cough' => $request->br_cough,
                        // 'br_coughdesc' => $request->br_coughdesc,
                        // 'br_smoke' => $request->br_smoke,
                        // 'br_smokedesc' => $request->br_smokedesc,
                        // 'ed_eatdrink' => $request->ed_eatdrink,
                        // 'ed_eatdrinkdesc' => $request->ed_eatdrinkdesc,
                        // 'eb_bowelhabit' => $request->eb_bowelhabit,
                        // 'eb_bowelmove' => $request->eb_bowelmove,
                        // 'eb_bowelmovedesc' => $request->eb_bowelmovedesc,
                        // 'bl_urine' => $request->bl_urine,
                        // 'bl_urinedesc' => $request->bl_urinedesc,
                        // 'bl_urinefreq' => $request->bl_urinefreq,
                        // 'sl_sleep' => $request->sl_sleep,
                        // 'mobilityambulan' => $request->mobilityambulan,
                        // 'mobilityassistaid' => $request->mobilityassistaid,
                        // 'mobilitybedridden' => $request->mobilitybedridden,
                        // 'phygiene_self' => $request->phygiene_self,
                        // 'phygiene_needassist' => $request->phygiene_needassist,
                        // 'phygiene_dependant' => $request->phygiene_dependant,
                        // 'safeenv_siderail' => $request->safeenv_siderail,
                        // 'safeenv_restraint' => $request->safeenv_restraint,
                        // 'cspeech_normal' => $request->cspeech_normal,
                        // 'cspeech_slurred' => $request->cspeech_slurred,
                        // 'cspeech_impaired' => $request->cspeech_impaired,
                        // 'cspeech_mute' => $request->cspeech_mute,
                        // 'cvision_normal' => $request->cvision_normal,
                        // 'cvision_blurring' => $request->cvision_blurring,
                        // 'cvision_doublev' => $request->cvision_doublev,
                        // 'cvision_blind' => $request->cvision_blind,
                        // 'cvision_visualaids' => $request->cvision_visualaids,
                        // 'chearing_normal' => $request->chearing_normal,
                        // 'chearing_deaf' => $request->chearing_deaf,
                        // 'chearing_hardhear' => $request->chearing_hardhear,
                        // 'chearing_hearaids' => $request->chearing_hearaids,
                    ]);
            }

            // DB::table('hisdb.episode')
            //     ->where('mrn','=',$request->mrn_ti)
            //     ->where('episno','=',$request->episno_ti)
            //     ->where('compcode','=',session('compcode'))
            //     ->update([
            //         'diagprov' => $request->diagnosis,
            //         'lastuser'  => session('username'),
            //         'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
            //     ]);

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
            //                 'location' => 'TRIAGE',
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

            // DB::table('hisdb.episode')
            //     ->where('mrn','=',$request->mrn_ti)
            //     ->where('episno','=',$request->episno_ti)
            //     ->where('compcode','=',session('compcode'))
            //     ->update([
            //         'diagprov' => $request->diagnosis,
            //         'lastuser'  => session('username'),
            //         'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
            //     ]);

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

            // DB::table('hisdb.episode')
            //     ->where('mrn','=',$request->mrn_ti)
            //     ->where('episno','=',$request->episno_ti)
            //     ->where('compcode','=',session('compcode'))
            //     ->update([
            //         'diagprov' => $request->diagnosis,
            //         'lastuser'  => session('username'),
            //         'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
            //     ]);

            $examidno = [];
            $examsel = [];
            $examnote = [];

            foreach($request->all() as $key => $value) {
                if(strpos($key, "examnote") === 0){
                    array_push($examnote, $value);
                }else if(strpos($key, "examsel") === 0){
                    array_push($examsel, $value);
                }else if(strpos($key, "examidno") === 0){
                    array_push($examidno, $value);
                }
            }

            foreach ($examidno as $key => $value) {
                if($value=='0'){
                    DB::table('nursing.nurassesexam')
                        ->insert([
                            'compcode' => session('compcode'),
                            'mrn' => $request->mrn_ti,
                            'episno' => $request->episno_ti,
                            'location' => $location,
                            'exam' => $examsel[$key],
                            'examnote' => $examnote[$key]
                        ]);
                }else{
                    DB::table('nursing.nurassesexam')
                        ->where('idno','=',$value)
                        ->update([
                            'exam' => $examsel[$key],
                            'examnote' => $examnote[$key]
                        ]);
                }
            }

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

        $triage_obj = DB::table('nursing.nursassessment')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$request->mrn)
                    ->where('episno','=',$request->episno);

        $triage_gen_obj = DB::table('nursing.nursassessgen')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$request->mrn)
                    ->where('episno','=',$request->episno);

        // $triage_exm_obj = DB::table('nursing.nurassesexam')
        //             ->where('compcode','=',session('compcode'))
        //             ->where('mrn','=',$request->mrn)
        //             ->where('episno','=',$request->episno);

        $triage_regdate_obj = DB::table('hisdb.episode')
                    ->select('reg_date')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$request->mrn)
                    ->where('episno','=',$request->episno);
                    
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

        if($triage_regdate_obj->exists()){
            $triage_regdate_obj = $triage_regdate_obj->first();
            $responce->triage_regdate = $triage_regdate_obj;
        }

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

}