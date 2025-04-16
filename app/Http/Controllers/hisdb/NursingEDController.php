<?php

namespace App\Http\Controllers\hisdb;

use Illuminate\Http\Request;
use stdClass;
use DB;
use Carbon\Carbon;
use App\Http\Controllers\defaultController;

class NursingEDController extends defaultController
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
        return view('hisdb.nursingED.nursingED');
    }
    
    public function form(Request $request)
    {
        DB::enableQueryLog();
        switch($request->action){
            case 'save_table_triage': // dari patient list OP
                switch($request->oper){
                    case 'add':
                        return $this->add_triage($request);
                    case 'edit':
                        return $this->edit_triage($request);
                    default:
                        return 'error happen..';
                }
            
            case 'get_table_triageED':
                return $this->get_table_triageED($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function add_triage(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            // $location = $this->get_location($request->mrn_tiED,$request->episno_tiED);
            $location = 'ED';
            
            DB::table('nursing.nursassessment')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn_tiED,
                    'episno' => $request->episno_tiED,
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
                    'location' => $location,
                    'adduser'  => session('username'),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastuser'  => $request->lastuser,
                    'lastupdate'  => $request->lastupdate,
                    'nursesign' => $request->nursesign,
                    'eduser' => $request->eduser,
                    'warduser' => $request->warduser,
                ]);
            
            DB::table('nursing.nurshistory')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn_tiED,
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
                ]);
            
            DB::table('nursing.nursassessgen')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn_tiED,
                    'episno' => $request->episno_tiED,
                    'gsc_eye' => $request->gsc_eye,
                    'gsc_verbal' => $request->gsc_verbal,
                    'gsc_motor' => $request->gsc_motor,
                    'totgsc' => $request->totgsc,
                    'location' => $location,
                    'adduser'  => session('username'),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                ]);
            
            // DB::table('hisdb.episode')
            //     ->insert([
            //         'compcode' => session('compcode'),
            //         'mrn' => $request->mrn_tiED,
            //         'episno' => $request->episno_tiED,
            //         'diagprov' => $request->diagnosis,
            //         'adduser'  => session('username'),
            //         'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
            //         'lastuser'  => session('username'),
            //         'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
            //     ]);
            
            DB::table('hisdb.episode')
                ->where('mrn','=',$request->mrn_tiED)
                ->where('episno','=',$request->episno_tiED)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'diagprov' => $request->diagnosis,
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                ]);
            
            DB::table('hisdb.pat_radiology')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn_tiED,
                    'episno' => $request->episno_tiED,
                    'weight' => $request->vs_weight,
                    'adduser'  => session('username'),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastuser' => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'computerid' => session('computerid'),
                ]);
            
            DB::table('hisdb.pat_mri')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn_tiED,
                    'episno' => $request->episno_tiED,
                    'weight' => $request->vs_weight,
                    'adduser'  => session('username'),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastuser' => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'computerid' => session('computerid'),
                ]);
            
            DB::table('nursing.nurshandover')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn_tiED,
                    'episno' => $request->episno_tiED,
                    'datetaken' => $request->reg_date,
                    'timetaken' => $request->admwardtime,
                    'bpsys_stand' => $request->vs_bp_sys1,
                    'bpdias_stand' => $request->vs_bp_dias2,
                    'spo2' => $request->vs_spo,
                    'hr' => $request->vs_pulse,
                    'gxt' => $request->vs_gxt,
                    'temp_' => $request->vs_temperature,
                    'weight' => $request->vs_weight,
                    'respiration' => $request->vs_respiration,
                    'height' => $request->vs_height,
                    'epistycode' => 'IP',
                    'location' => $location,
                    'adduser'  => session('username'),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
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
            
            // $location = $this->get_location($request->mrn_tiED,$request->episno_tiED);
            $location = 'ED';
            
            $nursassessment_triageinfo = DB::table('nursing.nursassessment')
                                        ->where('mrn','=',$request->mrn_tiED)
                                        ->where('episno','=',$request->episno_tiED)
                                        ->where('compcode','=',session('compcode'))
                                        ->where('location','=', $location);
            
            if(!$nursassessment_triageinfo->exists()){
                DB::table('nursing.nursassessment')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_tiED,
                        'episno' => $request->episno_tiED,
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
                        'nursesign' => $request->nursesign,
                        'eduser' => $request->eduser,
                        'warduser' => $request->warduser,
                    ]);
            }else{
                $nursassessment_triageinfo
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
                        'nursesign' => $request->nursesign,
                        'eduser' => $request->eduser,
                        'warduser' => $request->warduser,
                    ]);
            }
            
            $nurshistory_triage = DB::table('nursing.nurshistory')
                                ->where('mrn','=',$request->mrn_tiED)
                                ->where('compcode','=',session('compcode'));
            
            if(!$nurshistory_triage->exists()){
                DB::table('nursing.nurshistory')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_tiED,
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
                    ]);
            }
            
            $nursassessgen_triageinfo = DB::table('nursing.nursassessgen')
                                        ->where('mrn','=',$request->mrn_tiED)
                                        ->where('episno','=',$request->episno_tiED)
                                        ->where('compcode','=',session('compcode'))
                                        ->where('location','=',$location);
            
            if(!$nursassessgen_triageinfo->exists()){
                DB::table('nursing.nursassessgen')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_tiED,
                        'episno' => $request->episno_tiED,
                        'gsc_eye' => $request->gsc_eye,
                        'gsc_verbal' => $request->gsc_verbal,
                        'gsc_motor' => $request->gsc_motor,
                        'totgsc' => $request->totgsc,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }else{
                $nursassessgen_triageinfo
                    ->update([
                        'gsc_eye' => $request->gsc_eye,
                        'gsc_verbal' => $request->gsc_verbal,
                        'gsc_motor' => $request->gsc_motor,
                        'totgsc' => $request->totgsc,
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }
            
            DB::table('hisdb.episode')
                ->where('mrn','=',$request->mrn_tiED)
                ->where('episno','=',$request->episno_tiED)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'diagprov' => $request->diagnosis,
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                ]);
            
            DB::table('hisdb.pat_radiology')
                ->where('mrn','=',$request->mrn_tiED)
                ->where('episno','=',$request->episno_tiED)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'weight' => $request->vs_weight,
                    'upduser'  => session('username'),
                    'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastuser' => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'computerid' => session('computerid'),
                ]);
            
            DB::table('hisdb.pat_mri')
                ->where('mrn','=',$request->mrn_tiED)
                ->where('episno','=',$request->episno_tiED)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'weight' => $request->vs_weight,
                    'upduser'  => session('username'),
                    'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastuser' => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'computerid' => session('computerid'),
                ]);
            
            $nurshandover_triage = DB::table('nursing.nurshandover')
                                    ->where('mrn','=',$request->mrn_tiED)
                                    ->where('episno','=',$request->episno_tiED)
                                    ->where('compcode','=',session('compcode'));
            
            if(!$nurshandover_triage->exists()){
                DB::table('nursing.nurshandover')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_tiED,
                        'episno' => $request->episno_tiED,
                        'datetaken' => $request->reg_date,
                        'timetaken' => $request->admwardtime,
                        'bpsys_stand' => $request->vs_bp_sys1,
                        'bpdias_stand' => $request->vs_bp_dias2,
                        'spo2' => $request->vs_spo,
                        'hr' => $request->vs_pulse,
                        'gxt' => $request->vs_gxt,
                        'temp_' => $request->vs_temperature,
                        'weight' => $request->vs_weight,
                        'respiration' => $request->vs_respiration,
                        'height' => $request->vs_height,
                        'epistycode' => 'IP',
                        'location' => $location,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }else{
                $nurshandover_triage
                    ->update([
                        'bpsys_stand' => $request->vs_bp_sys1,
                        'bpdias_stand' => $request->vs_bp_dias2,
                        'spo2' => $request->vs_spo,
                        'hr' => $request->vs_pulse,
                        'gxt' => $request->vs_gxt,
                        'temp_' => $request->vs_temperature,
                        'weight' => $request->vs_weight,
                        'respiration' => $request->vs_respiration,
                        'height' => $request->vs_height,
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
    
    public function get_table_triageED(Request $request){
        
        // $location = $this->get_location($request->mrn_tiED,$request->episno_tiED);
        // dd($location);
        
        $triage_obj = DB::table('nursing.nursassessment')
                    ->where('compcode','=',session('compcode'))
                    ->where('location','=','ED')
                    ->where('mrn','=',$request->mrn)
                    ->where('episno','=',$request->episno);
        
        $triage_gen_obj = DB::table('nursing.nursassessgen')
                        ->where('compcode','=',session('compcode'))
                        ->where('location','=','ED')
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno);
        
        // $triage_exm_obj = DB::table('nursing.nurassesexam')
        //                 ->where('compcode','=',session('compcode'))
        //                 ->where('mrn','=',$request->mrn)
        //                 ->where('episno','=',$request->episno);
        
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
    
    public function get_location($mrn,$episno){
        
        $epistype = DB::table('hisdb.episode')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$mrn)
                    ->where('episno','=',$episno);
        
        if($epistype->exists()){
            $epistype = $epistype->first();
            $epistype = $epistype->epistycode;
        }
        
        if($epistype == 'IP' || $epistype == 'OP' ){
            $location = 'WARD';
        }else{
            $location = 'TRIAGE';
        }
        
        return $location;
        
    }
    
}