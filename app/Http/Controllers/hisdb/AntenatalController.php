<?php

namespace App\Http\Controllers\hisdb;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class AntenatalController extends defaultController
{   

    var $table;
    var $duplicateCode;

    public function __construct()
    {   
        $this->middleware('auth');
        // $this->duplicateCode = "chgtype";
    }

    public function chart(Request $request)
    {   
        return view('hisdb.antenatal.antenatal_chart');
    }

    public function show(Request $request)
    {   
        return view('hisdb.antenatal.antenatal');
    }

    public function table(Request $request)
    {   
        switch($request->action){
            case 'CurrPregnancy':
                return $this->CurrPregnancy($request);break;
            case 'ObstetricsUltrasound':
                return $this->ObstetricsUltrasound($request);break;
            case 'get_table_ultrasound':
                return $this->get_table_ultrasound($request);break;
            case 'get_table_pregnancy':
                return $this->get_table_pregnancy($request);break;
            default:
                return 'error happen..';
        }
    }

    public function CurrPregnancy(Request $request){
        $table = DB::table('nursing.pregnancy_episode')
                            ->where('compcode','=', session('compcode'))
                            ->where('mrn','=', $request->filterVal[0])
                            ->where('episno','=', $request->filterVal[1])
                            ->where('pregnan_idno','=', $request->filterVal[2]);

        $paginate = $table->paginate($request->rows);

        foreach ($paginate->items() as $key => $value) {
            if((!empty($value->bp_sys1)) || (!empty($value->bp_dias2))){
                $value->bp_ = $value->bp_sys1.' / '.$value->bp_dias2;
            }
        }

        $responce = new stdClass();
        $responce->page = $paginate->currentPage();
        $responce->total = $paginate->lastPage();
        $responce->records = $paginate->total();
        $responce->rows = $paginate->items();
        $responce->sql_query = $this->getQueries($table);
        
        return json_encode($responce);

    }

    public function ObstetricsUltrasound(Request $request){
        $table = DB::table('nursing.antenatal_ultrasound')
                            ->where('compcode','=', session('compcode'))
                            ->where('mrn','=', $request->filterVal[0])
                            ->where('pregnan_idno','=', $request->filterVal[1]);

        $paginate = $table->paginate($request->rows);

        foreach ($paginate->items() as $key => $value) {
            if((!empty($value->crl_w)) || (!empty($value->crl_d))){
                $value->crl_ = $value->crl.' = '.$value->crl_w.' + '.$value->crl_d;
            }

            if((!empty($value->bpd_w)) || (!empty($value->bpd_d))){
                $value->bpd_ = $value->bpd.' = '.$value->bpd_w.' + '.$value->bpd_d;
            }

            if((!empty($value->hc_w)) || (!empty($value->hc_d))){
                $value->hc_ = $value->hc.' = '.$value->hc_w.' + '.$value->hc_d;
            }

            if((!empty($value->ac_w)) || (!empty($value->ac_d))){
                $value->ac_ = $value->ac.' = '.$value->ac_w.' + '.$value->ac_d;
            }

            if((!empty($value->fl_w)) || (!empty($value->fl_d))){
                $value->fl_ = $value->fl.' = '.$value->fl_w.' + '.$value->fl_d;
            }

            if((!empty($value->atd_w)) || (!empty($value->atd_d))){
                $value->atd_ = $value->atd.' = '.$value->atd_w.' + '.$value->atd_d;
            }

            if((!empty($value->ald_w)) || (!empty($value->ald_d))){
                $value->ald_ = $value->ald.' = '.$value->ald_w.' + '.$value->ald_d;
            }
        }

        $responce = new stdClass();
        $responce->page = $paginate->currentPage();
        $responce->total = $paginate->lastPage();
        $responce->records = $paginate->total();
        $responce->rows = $paginate->items();
        $responce->sql_query = $this->getQueries($table);
        
        return json_encode($responce);

    }

    public function get_table_ultrasound(Request $request){
        $table = DB::table('nursing.antenatal_ultrasound')
                            ->where('compcode','=', session('compcode'))
                            ->where('mrn','=', $request->mrn)
                            ->where('date','=', $request->date);

        $pregnancy_ultra_obj = DB::table('nursing.pregnancy')
                            ->where('idno','=',$request->idno);

        $responce = new stdClass();
        $responce->rows = $table->first();
        $responce->sql_query = $this->getQueries($table);

        if($pregnancy_ultra_obj->exists()){
            $pregnancy_ultra_obj = $pregnancy_ultra_obj->first();
            $responce->pregnancy_ultra = $pregnancy_ultra_obj;
        }
        
        return json_encode($responce);

    }

    public function get_table_pregnancy(Request $request){

        $pregnancy_obj = DB::table('nursing.pregnancy')
                    ->where('idno','=',$request->idno);

        $responce = new stdClass();

        if($pregnancy_obj->exists()){
            $pregnancy_obj = $pregnancy_obj->first();
            $responce->pregnancy = $pregnancy_obj;
        }

        return json_encode($responce);

    }

    public function form(Request $request)
    {   
        DB::enableQueryLog();
        switch($request->action){
            case 'save_table_antenatal':

                switch($request->oper){
                    case 'add_antenatal':
                        return $this->add_antenatal($request);
                    case 'edit_antenatal':
                        return $this->edit_antenatal($request);
                    default:
                        return 'error happen..';
                }

            case 'save_table_pregnancy':

                switch($request->oper){
                    case 'add_pregnancy':
                        return $this->add_pregnancy($request);
                    case 'edit_pregnancy':
                        return $this->edit_pregnancy($request);
                    default:
                        return 'error happen..';
                }

            case 'save_table_ultrasound':

                switch($request->oper){
                    case 'add_ultrasound':
                        return $this->add_ultrasound($request);
                    case 'edit_ultrasound':
                        return $this->edit_ultrasound($request);
                    default:
                        return 'error happen..';
                }

            case 'get_table_antenatal':
                return $this->get_table_antenatal($request);

            case 'prevObstetrics_save':
                return $this->add_prevObstetrics($request);

            case 'prevObstetrics_edit':
                return $this->edit_prevObstetrics($request);

            case 'obstetricsUltrasound_save':
                return $this->add_obstetricsUltrasound($request);

            case 'obstetricsUltrasound_edit':
                return $this->edit_obstetricsUltrasound($request);

            case 'currPregnancy_save':
                return $this->add_currPregnancy($request);

            case 'currPregnancy_edit':
                return $this->edit_currPregnancy($request);

            default:
                return 'error happen..';
        }
    }

    public function add_antenatal(Request $request){

        DB::beginTransaction();

        try {

            DB::table('nursing.antenatal')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_antenatal,
                        'blood_grp' => $request->blood_grp,
                        'height' => $request->height,
                        'rhesus_factor' => $request->rhesus_factor,
                        'rubella' => $request->rubella,
                        'VDRL' => $request->VDRL,
                        'HIV' => $request->HIV,
                        'hep_B_Ag' => $request->hep_B_Ag,
                        'hep_B_AB' => $request->hep_B_AB,
                        'first_dose' => $request->first_dose,
                        'second_dose' => $request->second_dose,
                        'booster' => $request->booster,
                        'blood_trans' => $request->blood_trans,
                        'drug_allergy' => $request->drug_allergy,
                        'sysexam_date' => $request->sysexam_date,
                        'sysexam_varicose' => $request->sysexam_varicose,
                        'sysexam_pallor' => $request->sysexam_pallor,
                        'sysexam_jaundice' => $request->sysexam_jaundice,
                        'sysexam_oral' => $request->sysexam_oral,
                        'sysexam_thyroid' => $request->sysexam_thyroid,
                        'sysexam_breastr' => $request->sysexam_breastr,
                        'sysexam_breastl' => $request->sysexam_breastl,
                        'sysexam_cvs' => $request->sysexam_cvs,
                        'sysexam_resp' => $request->sysexam_resp,
                        'sysexam_abdomen' => $request->sysexam_abdomen,
                        'sysexam_remark' => $request->sysexam_remark,
                        'pgh_myomectomy' => $request->pgh_myomectomy,
                        'pgh_laparoscopy' => $request->pgh_laparoscopy,
                        'pgh_endomet' => $request->pgh_endomet,
                        'pgh_lastpapsmear' => $request->pgh_lastpapsmear,
                        'pgh_others' => $request->pgh_others,
                        'pmh_renal' => $request->pmh_renal,
                        'pmh_hypertension' => $request->pmh_hypertension,
                        'pmh_diabetes' => $request->pmh_diabetes,
                        'pmh_heart' => $request->pmh_heart,
                        'pmh_others' => $request->pmh_others,
                        'psh_appendic' => $request->psh_appendic,
                        'psh_hypertension' => $request->psh_hypertension,
                        'psh_laparotomy' => $request->psh_laparotomy,
                        'psh_thyroid' => $request->psh_thyroid,
                        'psh_others' => $request->psh_others,
                        'fh_hypertension' => $request->fh_hypertension,
                        'fh_diabetes' => $request->fh_diabetes,
                        'fh_epilepsy' => $request->fh_epilepsy,
                        'fh_multipregnan' => $request->fh_multipregnan,
                        'fh_congenital' => $request->fh_congenital,
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

    public function edit_antenatal(Request $request){

        DB::beginTransaction();

        try {

            $antenatal = DB::table('nursing.antenatal')
                ->where('mrn','=',$request->mrn_antenatal)
                ->where('compcode','=',session('compcode'));

            if($antenatal->exists()){
                $antenatal->update([
                        'blood_grp' => $request->blood_grp,
                        'height' => $request->height,
                        'rhesus_factor' => $request->rhesus_factor,
                        'rubella' => $request->rubella,
                        'VDRL' => $request->VDRL,
                        'HIV' => $request->HIV,
                        'hep_B_Ag' => $request->hep_B_Ag,
                        'hep_B_AB' => $request->hep_B_AB,
                        'first_dose' => $request->first_dose,
                        'second_dose' => $request->second_dose,
                        'booster' => $request->booster,
                        'blood_trans' => $request->blood_trans,
                        'drug_allergy' => $request->drug_allergy,
                        'sysexam_date' => $request->sysexam_date,
                        'sysexam_varicose' => $request->sysexam_varicose,
                        'sysexam_pallor' => $request->sysexam_pallor,
                        'sysexam_jaundice' => $request->sysexam_jaundice,
                        'sysexam_oral' => $request->sysexam_oral,
                        'sysexam_thyroid' => $request->sysexam_thyroid,
                        'sysexam_breastr' => $request->sysexam_breastr,
                        'sysexam_breastl' => $request->sysexam_breastl,
                        'sysexam_cvs' => $request->sysexam_cvs,
                        'sysexam_resp' => $request->sysexam_resp,
                        'sysexam_abdomen' => $request->sysexam_abdomen,
                        'sysexam_remark' => $request->sysexam_remark,
                        'pgh_myomectomy' => $request->pgh_myomectomy,
                        'pgh_laparoscopy' => $request->pgh_laparoscopy,
                        'pgh_endomet' => $request->pgh_endomet,
                        'pgh_lastpapsmear' => $request->pgh_lastpapsmear,
                        'pgh_others' => $request->pgh_others,
                        'pmh_renal' => $request->pmh_renal,
                        'pmh_hypertension' => $request->pmh_hypertension,
                        'pmh_diabetes' => $request->pmh_diabetes,
                        'pmh_heart' => $request->pmh_heart,
                        'pmh_others' => $request->pmh_others,
                        'psh_appendic' => $request->psh_appendic,
                        'psh_hypertension' => $request->psh_hypertension,
                        'psh_laparotomy' => $request->psh_laparotomy,
                        'psh_thyroid' => $request->psh_thyroid,
                        'psh_others' => $request->psh_others,
                        'fh_hypertension' => $request->fh_hypertension,
                        'fh_diabetes' => $request->fh_diabetes,
                        'fh_epilepsy' => $request->fh_epilepsy,
                        'fh_multipregnan' => $request->fh_multipregnan,
                        'fh_congenital' => $request->fh_congenital,
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }else{
                DB::table('nursing.antenatal')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_antenatal,
                        'blood_grp' => $request->blood_grp,
                        'height' => $request->height,
                        'rhesus_factor' => $request->rhesus_factor,
                        'rubella' => $request->rubella,
                        'VDRL' => $request->VDRL,
                        'HIV' => $request->HIV,
                        'hep_B_Ag' => $request->hep_B_Ag,
                        'hep_B_AB' => $request->hep_B_AB,
                        'first_dose' => $request->first_dose,
                        'second_dose' => $request->second_dose,
                        'booster' => $request->booster,
                        'blood_trans' => $request->blood_trans,
                        'drug_allergy' => $request->drug_allergy,
                        'sysexam_date' => $request->sysexam_date,
                        'sysexam_varicose' => $request->sysexam_varicose,
                        'sysexam_pallor' => $request->sysexam_pallor,
                        'sysexam_jaundice' => $request->sysexam_jaundice,
                        'sysexam_oral' => $request->sysexam_oral,
                        'sysexam_thyroid' => $request->sysexam_thyroid,
                        'sysexam_breastr' => $request->sysexam_breastr,
                        'sysexam_breastl' => $request->sysexam_breastl,
                        'sysexam_cvs' => $request->sysexam_cvs,
                        'sysexam_resp' => $request->sysexam_resp,
                        'sysexam_abdomen' => $request->sysexam_abdomen,
                        'sysexam_remark' => $request->sysexam_remark,
                        'pgh_myomectomy' => $request->pgh_myomectomy,
                        'pgh_laparoscopy' => $request->pgh_laparoscopy,
                        'pgh_endomet' => $request->pgh_endomet,
                        'pgh_lastpapsmear' => $request->pgh_lastpapsmear,
                        'pgh_others' => $request->pgh_others,
                        'pmh_renal' => $request->pmh_renal,
                        'pmh_hypertension' => $request->pmh_hypertension,
                        'pmh_diabetes' => $request->pmh_diabetes,
                        'pmh_heart' => $request->pmh_heart,
                        'pmh_others' => $request->pmh_others,
                        'psh_appendic' => $request->psh_appendic,
                        'psh_hypertension' => $request->psh_hypertension,
                        'psh_laparotomy' => $request->psh_laparotomy,
                        'psh_thyroid' => $request->psh_thyroid,
                        'psh_others' => $request->psh_others,
                        'fh_hypertension' => $request->fh_hypertension,
                        'fh_diabetes' => $request->fh_diabetes,
                        'fh_epilepsy' => $request->fh_epilepsy,
                        'fh_multipregnan' => $request->fh_multipregnan,
                        'fh_congenital' => $request->fh_congenital,
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

    public function add_pregnancy(Request $request){

        DB::beginTransaction();

        try {

            $idno = DB::table('nursing.pregnancy')
                    ->insertGetId([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_pregnancy,
                        'episno' => $request->episno_pregnancy,
                        'gravida' => $request->gravida,
                        'para' => $request->para,
                        'abortus' => $request->abortus,
                        'lmp' => $request->lmp,
                        'edd' => $request->edd,
                        'corrected_edd' => $request->corrected_edd,
                        'deliverydate' => $request->deliverydate,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);

            
            DB::commit();
            $responce = new stdClass();

            $pregnancy_page = DB::table('nursing.pregnancy')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$request->mrn_pregnancy)
                    ->orderBy('idno', 'ASC');

            if($pregnancy_page->exists()){
                $responce->pregnancy_page = $pregnancy_page->paginate();
            }

            $responce->idno = $idno;
            return json_encode($responce);

        } catch (\Exception $e) {
            DB::rollback();

            return response('Error DB rollback!'.$e, 500);
        }
    }

    public function edit_pregnancy(Request $request){

        DB::beginTransaction();

        try {

            $idno=null;

            $pregnancy = DB::table('nursing.pregnancy')
                ->where('mrn','=',$request->mrn_pregnancy)
                ->where('episno','=',$request->episno_pregnancy)
                ->where('idno','=',$request->pregnan_idno)
                ->where('compcode','=',session('compcode'));

            if (!empty($request->deliverydate)) {
                $pregnancy->update([
                        'recstatus' => 'DELIVERED',
                    ]);
            }

            if($pregnancy->exists()){
                $idno = $request->pregnan_idno;
                $pregnancy->update([
                        'gravida' => $request->gravida,
                        'para' => $request->para,
                        'abortus' => $request->abortus,
                        'lmp' => $request->lmp,
                        'edd' => $request->edd,
                        'corrected_edd' => $request->corrected_edd,
                        'deliverydate' => $request->deliverydate,
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }else{
                $idno = DB::table('nursing.pregnancy')
                        ->insertGetId([
                            'compcode' => session('compcode'),
                            'mrn' => $request->mrn_pregnancy,
                            'episno' => $request->episno_pregnancy,
                            'gravida' => $request->gravida,
                            'para' => $request->para,
                            'abortus' => $request->abortus,
                            'lmp' => $request->lmp,
                            'edd' => $request->edd,
                            'corrected_edd' => $request->corrected_edd,
                            'deliverydate' => $request->deliverydate,
                            'adduser'  => session('username'),
                            'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                            'lastuser'  => session('username'),
                            'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        ]);
            }

            DB::commit();
            $responce = new stdClass();

            $pregnancy_page = DB::table('nursing.pregnancy')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$request->mrn_pregnancy)
                    ->orderBy('idno', 'ASC');

            if($pregnancy_page->exists()){
                $responce->pregnancy_page = $pregnancy_page->paginate();
            }

            $responce->idno = $idno;
            return json_encode($responce);

        } catch (\Exception $e) {
            DB::rollback();

            return response('Error DB rollback!'.$e, 500);
        }
    }

    public function add_ultrasound(Request $request){

        DB::beginTransaction();

        try {

            DB::table('nursing.antenatal_ultrasound')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_ultrasound,
                        'is_cerebrum_normal' => $request->is_cerebrum_normal,
                        'cerebrum_hydran' => $request->cerebrum_hydran,
                        'cerebrum_holo' => $request->cerebrum_holo,
                        'cerebrum_text' => $request->cerebrum_text,
                        'pellucidum_normal' => $request->pellucidum_normal,
                        'pellucidum_text' => $request->pellucidum_text,
                        'falx_normal' => $request->falx_normal,
                        'falx_textCheck' => $request->falx_textCheck,
                        'falx_text' => $request->falx_text,
                        'cerebellum_normal' => $request->cerebellum_normal,
                        'cerebellum_textCheck' => $request->cerebellum_textCheck,
                        'cerebellum_text' => $request->cerebellum_text,
                        'ventricles_normal' => $request->ventricles_normal,
                        'ventricles_hydro' => $request->ventricles_hydro,
                        'ventricles_mild' => $request->ventricles_mild,
                        'ventricles_moderate' => $request->ventricles_moderate,
                        'ventricles_severe' => $request->ventricles_severe,
                        'cerebrum_normal' => $request->cerebrum_normal,
                        'cerebrum_anencephly' => $request->cerebrum_anencephly,
                        'cerebrum_mening' => $request->cerebrum_mening,
                        'upperlip_normal' => $request->upperlip_normal,
                        'upperlip_cleftlip' => $request->upperlip_cleftlip,
                        'lowerlip_normal' => $request->lowerlip_normal,
                        'lowerlip_text' => $request->lowerlip_text,
                        'palate_normal' => $request->palate_normal,
                        'palate_cleft' => $request->palate_cleft,
                        'nose_normal' => $request->nose_normal,
                        'nose_textCheck' => $request->nose_textCheck,
                        'nose_text' => $request->nose_text,
                        'righteyes_normal' => $request->righteyes_normal,
                        'righteyes_text' => $request->righteyes_text,
                        'lefteyes_normal' => $request->lefteyes_normal,
                        'lefteyes_text' => $request->lefteyes_text,
                        'mandible_normal' => $request->mandible_normal,
                        'mandible_macro' => $request->mandible_macro,
                        'mandible_micro' => $request->mandible_micro,
                        'neck_normal' => $request->neck_normal,
                        'neck_cystic' => $request->neck_cystic,
                        'chestwall_normal' => $request->chestwall_normal,
                        'chestwall_text' => $request->chestwall_text,
                        'heartsize_normal' => $request->heartsize_normal,
                        'heartsize_small' => $request->heartsize_small,
                        'fourchamber_normal' => $request->fourchamber_normal,
                        'fourchamber_text' => $request->fourchamber_text,
                        'aorticarc_normal' => $request->aorticarc_normal,
                        'aorticarc_coarctation' => $request->aorticarc_coarctation,
                        'aortictrunk_normal' => $request->aortictrunk_normal,
                        'aortictrunk_stenosis' => $request->aortictrunk_stenosis,
                        'pulmonary_normal' => $request->pulmonary_normal,
                        'pulmonary_stenosis' => $request->pulmonary_stenosis,
                        'septum_normal' => $request->septum_normal,
                        'septum_vsd' => $request->septum_vsd,
                        'septum_membraneous' => $request->septum_membraneous,
                        'septum_muscular' => $request->septum_muscular,
                        'septum_combined' => $request->septum_combined,
                        'vsdsize' => $request->vsdsize,
                        'pericardium_normal' => $request->pericardium_normal,
                        'pericardium_peri' => $request->pericardium_peri,
                        'otherDefects' => $request->otherDefects,
                        'diaphragm_normal' => $request->diaphragm_normal,
                        'diaphragm_hernia' => $request->diaphragm_hernia,
                        'lungs_normal' => $request->lungs_normal,
                        'lungs_hypo' => $request->lungs_hypo,
                        'lungs_pleural' => $request->lungs_pleural,
                        'chestwall_intact' => $request->chestwall_intact,
                        'chestwall_ompha' => $request->chestwall_ompha,
                        'chestwall_gastro' => $request->chestwall_gastro,
                        'cordA' => $request->cordA,
                        'cordV' => $request->cordV,
                        'cordinsert_intact' => $request->cordinsert_intact,
                        'cordinsert_text' => $request->cordinsert_text,
                        'stomach_normal' => $request->stomach_normal,
                        'stomach_double' => $request->stomach_double,
                        'stomach_absent' => $request->stomach_absent,
                        'liver_normal' => $request->liver_normal,
                        'liver_hepa' => $request->liver_hepa,
                        'liver_hypo' => $request->liver_hypo,
                        'rightkidney_normal' => $request->rightkidney_normal,
                        'rightkidney_absent' => $request->rightkidney_absent,
                        'rightkidney_hydro' => $request->rightkidney_hydro,
                        'rightkidney_text' => $request->rightkidney_text,
                        'leftkidney_normal' => $request->leftkidney_normal,
                        'leftkidney_absent' => $request->leftkidney_absent,
                        'leftkidney_hydro' => $request->leftkidney_hydro,
                        'leftkidney_text' => $request->leftkidney_text,
                        'bladder_normal' => $request->bladder_normal,
                        'bladder_absent' => $request->bladder_absent,
                        'bladder_text' => $request->bladder_text,
                        'ascites_distended' => $request->ascites_distended,
                        'ascites_absent' => $request->ascites_absent,
                        'ascites_present' => $request->ascites_present,
                        'upperlimbs_1R' => $request->upperlimbs_1R,
                        'upperlimbs_2R' => $request->upperlimbs_2R,
                        'upperlimbs_3R' => $request->upperlimbs_3R,
                        'upperlimbs_1L' => $request->upperlimbs_1L,
                        'upperlimbs_2L' => $request->upperlimbs_2L,
                        'upperlimbs_3L' => $request->upperlimbs_3L,
                        'lowerlimbs_1R' => $request->lowerlimbs_1R,
                        'lowerlimbs_2R' => $request->lowerlimbs_2R,
                        'lowerlimbs_3R' => $request->lowerlimbs_3R,
                        'lowerlimbs_1L' => $request->lowerlimbs_1L,
                        'lowerlimbs_2L' => $request->lowerlimbs_2L,
                        'lowerlimbs_3L' => $request->lowerlimbs_3L,
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

    public function edit_ultrasound(Request $request){

        DB::beginTransaction();

        try {

            $antenatal_ultrasound = DB::table('nursing.antenatal_ultrasound')
                ->where('mrn','=',$request->mrn_ultrasound)
                ->where('date','=',$request->date)
                ->where('compcode','=',session('compcode'));

            if($antenatal_ultrasound->exists()){
                $antenatal_ultrasound->update([
                        'is_cerebrum_normal' => $request->is_cerebrum_normal,
                        'cerebrum_hydran' => $request->cerebrum_hydran,
                        'cerebrum_holo' => $request->cerebrum_holo,
                        'cerebrum_text' => $request->cerebrum_text,
                        'pellucidum_normal' => $request->pellucidum_normal,
                        'pellucidum_text' => $request->pellucidum_text,
                        'falx_normal' => $request->falx_normal,
                        'falx_textCheck' => $request->falx_textCheck,
                        'falx_text' => $request->falx_text,
                        'cerebellum_normal' => $request->cerebellum_normal,
                        'cerebellum_textCheck' => $request->cerebellum_textCheck,
                        'cerebellum_text' => $request->cerebellum_text,
                        'ventricles_normal' => $request->ventricles_normal,
                        'ventricles_hydro' => $request->ventricles_hydro,
                        'ventricles_mild' => $request->ventricles_mild,
                        'ventricles_moderate' => $request->ventricles_moderate,
                        'ventricles_severe' => $request->ventricles_severe,
                        'cerebrum_normal' => $request->cerebrum_normal,
                        'cerebrum_anencephly' => $request->cerebrum_anencephly,
                        'cerebrum_mening' => $request->cerebrum_mening,
                        'upperlip_normal' => $request->upperlip_normal,
                        'upperlip_cleftlip' => $request->upperlip_cleftlip,
                        'lowerlip_normal' => $request->lowerlip_normal,
                        'lowerlip_text' => $request->lowerlip_text,
                        'palate_normal' => $request->palate_normal,
                        'palate_cleft' => $request->palate_cleft,
                        'nose_normal' => $request->nose_normal,
                        'nose_textCheck' => $request->nose_textCheck,
                        'nose_text' => $request->nose_text,
                        'righteyes_normal' => $request->righteyes_normal,
                        'righteyes_text' => $request->righteyes_text,
                        'lefteyes_normal' => $request->lefteyes_normal,
                        'lefteyes_text' => $request->lefteyes_text,
                        'mandible_normal' => $request->mandible_normal,
                        'mandible_macro' => $request->mandible_macro,
                        'mandible_micro' => $request->mandible_micro,
                        'neck_normal' => $request->neck_normal,
                        'neck_cystic' => $request->neck_cystic,
                        'chestwall_normal' => $request->chestwall_normal,
                        'chestwall_text' => $request->chestwall_text,
                        'heartsize_normal' => $request->heartsize_normal,
                        'heartsize_small' => $request->heartsize_small,
                        'fourchamber_normal' => $request->fourchamber_normal,
                        'fourchamber_text' => $request->fourchamber_text,
                        'aorticarc_normal' => $request->aorticarc_normal,
                        'aorticarc_coarctation' => $request->aorticarc_coarctation,
                        'aortictrunk_normal' => $request->aortictrunk_normal,
                        'aortictrunk_stenosis' => $request->aortictrunk_stenosis,
                        'pulmonary_normal' => $request->pulmonary_normal,
                        'pulmonary_stenosis' => $request->pulmonary_stenosis,
                        'septum_normal' => $request->septum_normal,
                        'septum_vsd' => $request->septum_vsd,
                        'septum_membraneous' => $request->septum_membraneous,
                        'septum_muscular' => $request->septum_muscular,
                        'septum_combined' => $request->septum_combined,
                        'vsdsize' => $request->vsdsize,
                        'pericardium_normal' => $request->pericardium_normal,
                        'pericardium_peri' => $request->pericardium_peri,
                        'otherDefects' => $request->otherDefects,
                        'diaphragm_normal' => $request->diaphragm_normal,
                        'diaphragm_hernia' => $request->diaphragm_hernia,
                        'lungs_normal' => $request->lungs_normal,
                        'lungs_hypo' => $request->lungs_hypo,
                        'lungs_pleural' => $request->lungs_pleural,
                        'chestwall_intact' => $request->chestwall_intact,
                        'chestwall_ompha' => $request->chestwall_ompha,
                        'chestwall_gastro' => $request->chestwall_gastro,
                        'cordA' => $request->cordA,
                        'cordV' => $request->cordV,
                        'cordinsert_intact' => $request->cordinsert_intact,
                        'cordinsert_text' => $request->cordinsert_text,
                        'stomach_normal' => $request->stomach_normal,
                        'stomach_double' => $request->stomach_double,
                        'stomach_absent' => $request->stomach_absent,
                        'liver_normal' => $request->liver_normal,
                        'liver_hepa' => $request->liver_hepa,
                        'liver_hypo' => $request->liver_hypo,
                        'rightkidney_normal' => $request->rightkidney_normal,
                        'rightkidney_absent' => $request->rightkidney_absent,
                        'rightkidney_hydro' => $request->rightkidney_hydro,
                        'rightkidney_text' => $request->rightkidney_text,
                        'leftkidney_normal' => $request->leftkidney_normal,
                        'leftkidney_absent' => $request->leftkidney_absent,
                        'leftkidney_hydro' => $request->leftkidney_hydro,
                        'leftkidney_text' => $request->leftkidney_text,
                        'bladder_normal' => $request->bladder_normal,
                        'bladder_absent' => $request->bladder_absent,
                        'bladder_text' => $request->bladder_text,
                        'ascites_distended' => $request->ascites_distended,
                        'ascites_absent' => $request->ascites_absent,
                        'ascites_present' => $request->ascites_present,
                        'upperlimbs_1R' => $request->upperlimbs_1R,
                        'upperlimbs_2R' => $request->upperlimbs_2R,
                        'upperlimbs_3R' => $request->upperlimbs_3R,
                        'upperlimbs_1L' => $request->upperlimbs_1L,
                        'upperlimbs_2L' => $request->upperlimbs_2L,
                        'upperlimbs_3L' => $request->upperlimbs_3L,
                        'lowerlimbs_1R' => $request->lowerlimbs_1R,
                        'lowerlimbs_2R' => $request->lowerlimbs_2R,
                        'lowerlimbs_3R' => $request->lowerlimbs_3R,
                        'lowerlimbs_1L' => $request->lowerlimbs_1L,
                        'lowerlimbs_2L' => $request->lowerlimbs_2L,
                        'lowerlimbs_3L' => $request->lowerlimbs_3L,
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }else{
                DB::table('nursing.antenatal_ultrasound')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_ultrasound,
                        'is_cerebrum_normal' => $request->is_cerebrum_normal,
                        'cerebrum_hydran' => $request->cerebrum_hydran,
                        'cerebrum_holo' => $request->cerebrum_holo,
                        'cerebrum_text' => $request->cerebrum_text,
                        'pellucidum_normal' => $request->pellucidum_normal,
                        'pellucidum_text' => $request->pellucidum_text,
                        'falx_normal' => $request->falx_normal,
                        'falx_textCheck' => $request->falx_textCheck,
                        'falx_text' => $request->falx_text,
                        'cerebellum_normal' => $request->cerebellum_normal,
                        'cerebellum_textCheck' => $request->cerebellum_textCheck,
                        'cerebellum_text' => $request->cerebellum_text,
                        'ventricles_normal' => $request->ventricles_normal,
                        'ventricles_hydro' => $request->ventricles_hydro,
                        'ventricles_mild' => $request->ventricles_mild,
                        'ventricles_moderate' => $request->ventricles_moderate,
                        'ventricles_severe' => $request->ventricles_severe,
                        'cerebrum_normal' => $request->cerebrum_normal,
                        'cerebrum_anencephly' => $request->cerebrum_anencephly,
                        'cerebrum_mening' => $request->cerebrum_mening,
                        'upperlip_normal' => $request->upperlip_normal,
                        'upperlip_cleftlip' => $request->upperlip_cleftlip,
                        'lowerlip_normal' => $request->lowerlip_normal,
                        'lowerlip_text' => $request->lowerlip_text,
                        'palate_normal' => $request->palate_normal,
                        'palate_cleft' => $request->palate_cleft,
                        'nose_normal' => $request->nose_normal,
                        'nose_textCheck' => $request->nose_textCheck,
                        'nose_text' => $request->nose_text,
                        'righteyes_normal' => $request->righteyes_normal,
                        'righteyes_text' => $request->righteyes_text,
                        'lefteyes_normal' => $request->lefteyes_normal,
                        'lefteyes_text' => $request->lefteyes_text,
                        'mandible_normal' => $request->mandible_normal,
                        'mandible_macro' => $request->mandible_macro,
                        'mandible_micro' => $request->mandible_micro,
                        'neck_normal' => $request->neck_normal,
                        'neck_cystic' => $request->neck_cystic,
                        'chestwall_normal' => $request->chestwall_normal,
                        'chestwall_text' => $request->chestwall_text,
                        'heartsize_normal' => $request->heartsize_normal,
                        'heartsize_small' => $request->heartsize_small,
                        'fourchamber_normal' => $request->fourchamber_normal,
                        'fourchamber_text' => $request->fourchamber_text,
                        'aorticarc_normal' => $request->aorticarc_normal,
                        'aorticarc_coarctation' => $request->aorticarc_coarctation,
                        'aortictrunk_normal' => $request->aortictrunk_normal,
                        'aortictrunk_stenosis' => $request->aortictrunk_stenosis,
                        'pulmonary_normal' => $request->pulmonary_normal,
                        'pulmonary_stenosis' => $request->pulmonary_stenosis,
                        'septum_normal' => $request->septum_normal,
                        'septum_vsd' => $request->septum_vsd,
                        'septum_membraneous' => $request->septum_membraneous,
                        'septum_muscular' => $request->septum_muscular,
                        'septum_combined' => $request->septum_combined,
                        'vsdsize' => $request->vsdsize,
                        'pericardium_normal' => $request->pericardium_normal,
                        'pericardium_peri' => $request->pericardium_peri,
                        'otherDefects' => $request->otherDefects,
                        'diaphragm_normal' => $request->diaphragm_normal,
                        'diaphragm_hernia' => $request->diaphragm_hernia,
                        'lungs_normal' => $request->lungs_normal,
                        'lungs_hypo' => $request->lungs_hypo,
                        'lungs_pleural' => $request->lungs_pleural,
                        'chestwall_intact' => $request->chestwall_intact,
                        'chestwall_ompha' => $request->chestwall_ompha,
                        'chestwall_gastro' => $request->chestwall_gastro,
                        'cordA' => $request->cordA,
                        'cordV' => $request->cordV,
                        'cordinsert_intact' => $request->cordinsert_intact,
                        'cordinsert_text' => $request->cordinsert_text,
                        'stomach_normal' => $request->stomach_normal,
                        'stomach_double' => $request->stomach_double,
                        'stomach_absent' => $request->stomach_absent,
                        'liver_normal' => $request->liver_normal,
                        'liver_hepa' => $request->liver_hepa,
                        'liver_hypo' => $request->liver_hypo,
                        'rightkidney_normal' => $request->rightkidney_normal,
                        'rightkidney_absent' => $request->rightkidney_absent,
                        'rightkidney_hydro' => $request->rightkidney_hydro,
                        'rightkidney_text' => $request->rightkidney_text,
                        'leftkidney_normal' => $request->leftkidney_normal,
                        'leftkidney_absent' => $request->leftkidney_absent,
                        'leftkidney_hydro' => $request->leftkidney_hydro,
                        'leftkidney_text' => $request->leftkidney_text,
                        'bladder_normal' => $request->bladder_normal,
                        'bladder_absent' => $request->bladder_absent,
                        'bladder_text' => $request->bladder_text,
                        'ascites_distended' => $request->ascites_distended,
                        'ascites_absent' => $request->ascites_absent,
                        'ascites_present' => $request->ascites_present,
                        'upperlimbs_1R' => $request->upperlimbs_1R,
                        'upperlimbs_2R' => $request->upperlimbs_2R,
                        'upperlimbs_3R' => $request->upperlimbs_3R,
                        'upperlimbs_1L' => $request->upperlimbs_1L,
                        'upperlimbs_2L' => $request->upperlimbs_2L,
                        'upperlimbs_3L' => $request->upperlimbs_3L,
                        'lowerlimbs_1R' => $request->lowerlimbs_1R,
                        'lowerlimbs_2R' => $request->lowerlimbs_2R,
                        'lowerlimbs_3R' => $request->lowerlimbs_3R,
                        'lowerlimbs_1L' => $request->lowerlimbs_1L,
                        'lowerlimbs_2L' => $request->lowerlimbs_2L,
                        'lowerlimbs_3L' => $request->lowerlimbs_3L,
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

    public function get_table_antenatal(Request $request){
        
        $pregnancy_page = DB::table('nursing.pregnancy')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$request->mrn)
                    ->orderBy('idno', 'ASC');

        $pregnancy_obj = DB::table('nursing.pregnancy')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$request->mrn)
                    ->orderBy('idno', 'DESC');
        
        $antenatal_obj = DB::table('nursing.antenatal')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$request->mrn);

        $responce = new stdClass();

        if($pregnancy_obj->exists()){
            $paginate_preg = $pregnancy_page->paginate();
            $pregnancy_obj = $pregnancy_obj->first();
            $responce->pregnancy = $pregnancy_obj;
            $responce->pregnancy_page = $paginate_preg;
        }

        if($antenatal_obj->exists()){
            $antenatal_obj = $antenatal_obj->first();
            $responce->antenatal = $antenatal_obj;
        }

        return json_encode($responce);

    }

    public function add_prevObstetrics(Request $request){

        DB::beginTransaction();

        try {

            DB::table('nursing.antenatal_history')
                ->insert([  
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn,
                    'year' => $request->year,
                    'gestation' => $request->gestation,
                    'placedeliver' => $request->placedeliver,
                    'lab_del' => $request->lab_del,
                    'purperium' => $request->purperium,
                    'weight' => $request->weight,
                    'sex' => $request->sex,
                    'breastfed' => $request->breastfed,
                    'comments' => $request->comments,
                    'adduser'  => session('username'),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur"),
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString()
                ]);

             DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function edit_prevObstetrics(Request $request){

        DB::beginTransaction();

        try {

            DB::table('nursing.antenatal_history')
                ->where('idno','=',$request->idno)
                ->update([  
                    'year' => $request->year,
                    'gestation' => $request->gestation,
                    'placedeliver' => $request->placedeliver,
                    'lab_del' => $request->lab_del,
                    'purperium' => $request->purperium,
                    'weight' => $request->weight,
                    'sex' => $request->sex,
                    'breastfed' => $request->breastfed,
                    'comments' => $request->comments,
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString()
                ]);

             DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function add_obstetricsUltrasound(Request $request){

        DB::beginTransaction();

        try {

            DB::table('nursing.antenatal_ultrasound')
                ->insert([  
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn,
                    'pregnan_idno' => $request->pregnan_idno,
                    'date' => $this->turn_date($request->date),
                    'poa' => $request->poa,
                    'pog' => $request->pog,
                    'crl' => $request->crl_[0],
                    'crl_w' => $request->crl_[1],
                    'crl_d' => $request->crl_[2],
                    'bpd' => $request->bpd_[0],
                    'bpd_w' => $request->bpd_[1],
                    'bpd_d' => $request->bpd_[2],
                    'hc' => $request->hc_[0], 
                    'hc_w' => $request->hc_[1],
                    'hc_d' => $request->hc_[2],
                    'ac' => $request->ac_[0],
                    'ac_w' => $request->ac_[1],
                    'ac_d' => $request->ac_[2],
                    'fl' => $request->fl_[0],
                    'fl_w' => $request->fl_[1],
                    'fl_d' => $request->fl_[2],
                    'atd' => $request->atd_[0],
                    'atd_w' => $request->atd_[1],
                    'atd_d' => $request->atd_[2],
                    'ald' => $request->ald_[0],
                    'ald_w' => $request->ald_[1],
                    'ald_d' => $request->ald_[2],
                    'efbw' => $request->efbw,
                    'afi' => $request->afi,
                    'pres' => $request->pres,
                    'placenta' => $request->placenta,
                    'adduser'  => session('username'),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur"),
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString()
                ]);

             DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function edit_obstetricsUltrasound(Request $request){

        DB::beginTransaction();

        try {

            DB::table('nursing.antenatal_ultrasound')
                ->where('idno','=',$request->idno)
                ->update([  
                    'date' => $this->turn_date($request->date),
                    'poa' => $request->poa,
                    'pog' => $request->pog,
                    'crl' => $request->crl_[0],
                    'crl_w' => $request->crl_[1],
                    'crl_d' => $request->crl_[2],
                    'bpd' => $request->bpd_[0],
                    'bpd_w' => $request->bpd_[1],
                    'bpd_d' => $request->bpd_[2],
                    'hc' => $request->hc_[0], 
                    'hc_w' => $request->hc_[1],
                    'hc_d' => $request->hc_[2],
                    'ac' => $request->ac_[0],
                    'ac_w' => $request->ac_[1],
                    'ac_d' => $request->ac_[2],
                    'fl' => $request->fl_[0],
                    'fl_w' => $request->fl_[1],
                    'fl_d' => $request->fl_[2],
                    'atd' => $request->atd_[0],
                    'atd_w' => $request->atd_[1],
                    'atd_d' => $request->atd_[2],
                    'ald' => $request->ald_[0],
                    'ald_w' => $request->ald_[1],
                    'ald_d' => $request->ald_[2],
                    'efbw' => $request->efbw,
                    'afi' => $request->afi,
                    'pres' => $request->pres,
                    'placenta' => $request->placenta,
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString()
                ]);

             DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function add_currPregnancy(Request $request){

        DB::beginTransaction();

        try {

            DB::table('nursing.pregnancy_episode')
                ->insert([  
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn,
                    'episno' => $request->episno,
                    'pregnan_idno' => $request->pregnan_idno,
                    'date' => $this->turn_date($request->date),
                    'report' => $request->report,
                    'poa_pog' => $request->poa_pog,
                    'uterinesize' => $request->uterinesize,
                    'albumin' => $request->albumin,
                    'sugar' => $request->sugar,
                    'weight' => $request->weight,
                    'bp_sys1' => $request->bp_[0],
                    'bp_dias2' => $request->bp_[1],
                    'hb' => $request->hb,
                    'oedema' => $request->oedema,
                    'lie' => $request->lie,
                    'pres' => $request->pres,
                    'fhr' => $request->fhr,
                    'fm' => $request->fm,
                    'adduser'  => session('username'),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur"),
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString()
                ]);

             DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function edit_currPregnancy(Request $request){

        DB::beginTransaction();

        try {

            DB::table('nursing.pregnancy_episode')
                ->where('idno','=',$request->idno)
                ->update([  
                    'date' => $this->turn_date($request->date),
                    'report' => $request->report,
                    'poa_pog' => $request->poa_pog,
                    'uterinesize' => $request->uterinesize,
                    'albumin' => $request->albumin,
                    'sugar' => $request->sugar,
                    'weight' => $request->weight,
                    'bp_sys1' => $request->bp_[0],
                    'bp_dias2' => $request->bp_[1],
                    'hb' => $request->hb,
                    'oedema' => $request->oedema,
                    'lie' => $request->lie,
                    'pres' => $request->pres,
                    'fhr' => $request->fhr,
                    'fm' => $request->fm,
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString()
                ]);

             DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

}