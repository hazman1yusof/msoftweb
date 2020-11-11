<?php

namespace App\Http\Controllers\hisdb;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class WardPanelController extends defaultController
{   

    var $table;
    var $duplicateCode;

    public function __construct(){
        $this->middleware('auth');
        // $this->duplicateCode = "chgtype";
    }

    public function show(Request $request){
        return view('hisdb.wardpanel.wardpanel');
    }

    public function form(Request $request){
        DB::enableQueryLog();
        switch($request->action){
            case 'save_table_ward':

                switch($request->oper){
                    case 'add':
                        return $this->add($request);
                    case 'edit':
                        return $this->edit($request);
                    default:
                        return 'error happen..';
                }

            case 'get_table_ward':
                return $this->get_table_ward($request);

            default:
                return 'error happen..';
        }

        switch($request->oper){
            case 'add_exam':
                return $this->add_exam($request);
            case 'edit_exam':
                return $this->edit_exam($request);
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
                        'mrn' => $request->mrn_ward,
                        'episno' => $request->episno_ward,
                        'admwardtime' => $request->admwardtime,
                        'triagecolor' => $request->triagecolor,
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
                        'location' => 'WARD',
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);

            DB::table('nursing.nursassessgen')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_ward,
                        'episno' => $request->episno_ward,
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
                        'location' => 'WARD',
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);

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
                            'mrn' => $request->mrn_ward,
                            'episno' => $request->episno_ward,
                            'location' => 'WARD',
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

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response('Error DB rollback!'.$e, 500);
        }
    }

    public function edit(Request $request){

        DB::beginTransaction();

        try {

            $nursassessment = DB::table('nursing.nursassessment')
                ->where('mrn','=',$request->mrn_ward)
                ->where('episno','=',$request->episno_ward)
                ->where('compcode','=',session('compcode'))
                ->where('location','=','WARD');

            if(!$nursassessment->exists()){
                DB::table('nursing.nursassessment')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_ward,
                        'episno' => $request->episno_ward,
                        'admwardtime' => $request->admwardtime,
                        'triagecolor' => $request->triagecolor,
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
                        'location' => 'WARD',
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }else{
                $nursassessment
                    ->update([
                        'admwardtime' => $request->admwardtime,
                        'triagecolor' => $request->triagecolor,
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
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }

            $nursassessgen = DB::table('nursing.nursassessgen')
                ->where('mrn','=',$request->mrn_ward)
                ->where('episno','=',$request->episno_ward)
                ->where('compcode','=',session('compcode'))
                ->where('location','=','WARD');

            if(!$nursassessgen->exists()){
                DB::table('nursing.nursassessgen')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_ward,
                        'episno' => $request->episno_ward,
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
                        'location' => 'WARD',
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }else{
                $nursassessgen
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
                            'mrn' => $request->mrn_ward,
                            'episno' => $request->episno_ward,
                            'location' => 'WARD',
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

    public function get_table_ward(Request $request){

        $ward_obj = DB::table('nursing.nursassessment')
                    ->where('compcode','=',session('compcode'))
                    ->where('location','=','WARD')
                    ->where('mrn','=',$request->mrn)
                    ->where('episno','=',$request->episno);

        $ward_gen_obj = DB::table('nursing.nursassessgen')
                    ->where('compcode','=',session('compcode'))
                    ->where('location','=','WARD')
                    ->where('mrn','=',$request->mrn)
                    ->where('episno','=',$request->episno);

        $ward_exm_obj = DB::table('nursing.nurassesexam')
                    ->where('compcode','=',session('compcode'))
                    ->where('location','=','WARD')
                    ->where('mrn','=',$request->mrn)
                    ->where('episno','=',$request->episno);

        $responce = new stdClass();

        if($ward_obj->exists()){
            $ward_obj = $ward_obj->first();
            $responce->ward = $ward_obj;
        }

        if($ward_gen_obj->exists()){
            $ward_gen_obj = $ward_gen_obj->first();
            $responce->ward_gen = $ward_gen_obj;
        }

        if($ward_exm_obj->exists()){
            $ward_exm_obj = $ward_exm_obj->get()->toArray();
            $responce->ward_exm = $ward_exm_obj;
        }

        return json_encode($responce);

    }

    public function add_exam(Request $request){

        DB::beginTransaction();

        try {

            DB::table('nursing.nurassesexam')
                ->insert([  
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn_ward,
                    'episno' => $request->episno_ward,
                    'location' => 'WARD',
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


}