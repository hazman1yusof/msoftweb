<?php

namespace App\Http\Controllers\hisdb;

use Illuminate\Http\Request;
use stdClass;
use App\User;
use DB;
use Carbon\Carbon;
use Auth;
use Session;
use App\Http\Controllers\defaultController;

class PreoperativeDCController extends defaultController
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function show(Request $request)
    {
        return view('hisdb.preoperativeDC.preoperativeDC');
    }
    
    public function table(Request $request)
    {
        switch($request->action){
            default:
                return 'error happen..';
        }
    }
    
    public function form(Request $request)
    {
        DB::enableQueryLog();
        switch($request->action){
            case 'save_table_preoperativeDC':
            
                switch($request->oper){
                    case 'add':
                        return $this->add($request);
                    case 'edit':
                        return $this->edit($request);
                    default:
                        return 'error happen..';
                }
                
            case 'get_table_preoperativeDC':
                return $this->get_table_preoperativeDC($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function add(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.otpreopdaycare')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_preoperativeDC,
                        'episno' => $request->episno_preoperativeDC,
                        'surgeonDC' => $request->surgeonDC,
                        'anaestDC' => $request->anaestDC,
                        'natureoperDC' => $request->natureoperDC,
                        'operdateDC' => $request->operdateDC,
                        'idBracelet_ward' => $request->idBracelet_ward,
                        'idBracelet_rec' => $request->idBracelet_rec,
                        'idBracelet_theatre' => $request->idBracelet_theatre,
                        'idBracelet_remarks' => $request->idBracelet_remarks,
                        'operSite_ward' => $request->operSite_ward,
                        'operSite_rec' => $request->operSite_rec,
                        'operSite_theatre' => $request->operSite_theatre,
                        'operSite_remarks' => $request->operSite_remarks,
                        'fasted_time_from' => $request->fasted_time_from,
                        'fasted_time_until' => $request->fasted_time_until,
                        'fasted_hours' => $request->fasted_hours,
                        'fasted_ward' => $request->fasted_ward,
                        'fasted_rec' => $request->fasted_rec,
                        'fasted_theatre' => $request->fasted_theatre,
                        'fasted_remarks' => $request->fasted_remarks,
                        'consentValid_ward' => $request->consentValid_ward,
                        'consentValid_rec' => $request->consentValid_rec,
                        'consentValid_theatre' => $request->consentValid_theatre,
                        'consentValid_remarks' => $request->consentValid_remarks,
                        'consentAnaest_ward' => $request->consentAnaest_ward,
                        'consentAnaest_rec' => $request->consentAnaest_rec,
                        'consentAnaest_theatre' => $request->consentAnaest_theatre,
                        'consentAnaest_remarks' => $request->consentAnaest_remarks,
                        'otGown_ward' => $request->otGown_ward,
                        'otGown_rec' => $request->otGown_rec,
                        'otGown_theatre' => $request->otGown_theatre,
                        'otGown_remarks' => $request->otGown_remarks,
                        'shaving_ward' => $request->shaving_ward,
                        'shaving_rec' => $request->shaving_rec,
                        'shaving_theatre' => $request->shaving_theatre,
                        'shaving_remarks' => $request->shaving_remarks,
                        'bowelPrep_ward' => $request->bowelPrep_ward,
                        'bowelPrep_rec' => $request->bowelPrep_rec,
                        'bowelPrep_theatre' => $request->bowelPrep_theatre,
                        'bowelPrep_remarks' => $request->bowelPrep_remarks,
                        'bladder_ward' => $request->bladder_ward,
                        'bladder_rec' => $request->bladder_rec,
                        'bladder_theatre' => $request->bladder_theatre,
                        'bladder_remarks' => $request->bladder_remarks,
                        'dentures_ward' => $request->dentures_ward,
                        'dentures_rec' => $request->dentures_rec,
                        'dentures_theatre' => $request->dentures_theatre,
                        'dentures_remarks' => $request->dentures_remarks,
                        'lensImpSpec_ward' => $request->lensImpSpec_ward,
                        'lensImpSpec_rec' => $request->lensImpSpec_rec,
                        'lensImpSpec_theatre' => $request->lensImpSpec_theatre,
                        'lensImpSpec_remarks' => $request->lensImpSpec_remarks,
                        'nailVarnish_ward' => $request->nailVarnish_ward,
                        'nailVarnish_rec' => $request->nailVarnish_rec,
                        'nailVarnish_theatre' => $request->nailVarnish_theatre,
                        'nailVarnish_remarks' => $request->nailVarnish_remarks,
                        'hairClips_ward' => $request->hairClips_ward,
                        'hairClips_rec' => $request->hairClips_rec,
                        'hairClips_theatre' => $request->hairClips_theatre,
                        'hairClips_remarks' => $request->hairClips_remarks,
                        'valuables_ward' => $request->valuables_ward,
                        'valuables_rec' => $request->valuables_rec,
                        'valuables_theatre' => $request->valuables_theatre,
                        'valuables_remarks' => $request->valuables_remarks,
                        'ivFluids_ward' => $request->ivFluids_ward,
                        'ivFluids_rec' => $request->ivFluids_rec,
                        'ivFluids_theatre' => $request->ivFluids_theatre,
                        'ivFluids_remarks' => $request->ivFluids_remarks,
                        'premedGiven_hours' => $request->premedGiven_hours,
                        'premedGiven_ward' => $request->premedGiven_ward,
                        'premedGiven_rec' => $request->premedGiven_rec,
                        'premedGiven_theatre' => $request->premedGiven_theatre,
                        'premedGiven_remarks' => $request->premedGiven_remarks,
                        'medChart_ward' => $request->medChart_ward,
                        'medChart_rec' => $request->medChart_rec,
                        'medChart_theatre' => $request->medChart_theatre,
                        'medChart_remarks' => $request->medChart_remarks,
                        'caseNote_ward' => $request->caseNote_ward,
                        'caseNote_rec' => $request->caseNote_rec,
                        'caseNote_theatre' => $request->caseNote_theatre,
                        'caseNote_remarks' => $request->caseNote_remarks,
                        'oldNotes_ward' => $request->oldNotes_ward,
                        'oldNotes_rec' => $request->oldNotes_rec,
                        'oldNotes_theatre' => $request->oldNotes_theatre,
                        'oldNotes_remarks' => $request->oldNotes_remarks,
                        'ptBelongings_ward' => $request->ptBelongings_ward,
                        'ptBelongings_rec' => $request->ptBelongings_rec,
                        'ptBelongings_theatre' => $request->ptBelongings_theatre,
                        'ptBelongings_remarks' => $request->ptBelongings_remarks,
                        'allergies_ward' => $request->allergies_ward,
                        'allergies_rec' => $request->allergies_rec,
                        'allergies_theatre' => $request->allergies_theatre,
                        'allergies_remarks' => $request->allergies_remarks,
                        'medLegalCase_ward' => $request->medLegalCase_ward,
                        'medLegalCase_rec' => $request->medLegalCase_rec,
                        'medLegalCase_theatre' => $request->medLegalCase_theatre,
                        'medLegalCase_remarks' => $request->medLegalCase_remarks,
                        'checkedBy_ward' => $request->checkedBy_ward,
                        'checkedBy_rec' => $request->checkedBy_rec,
                        'checkedBy_theatre' => $request->checkedBy_theatre,
                        'checkedBy_remarks' => $request->checkedBy_remarks,
                        'checkedDate_ward' => $request->checkedDate_ward,
                        'checkedDate_rec' => $request->checkedDate_rec,
                        'checkedDate_theatre' => $request->checkedDate_theatre,
                        'checkedDate_remarks' => $request->checkedDate_remarks,
                        'bloodTest_1' => $request->bloodTest_1,
                        'bloodTest_2' => $request->bloodTest_2,
                        'bloodTest_3' => $request->bloodTest_3,
                        'bloodTest_4' => $request->bloodTest_4,
                        'bloodTest_doc' => $request->bloodTest_doc,
                        'bloodTest_remarks' => $request->bloodTest_remarks,
                        'grpCrossMatch_1' => $request->grpCrossMatch_1,
                        'grpCrossMatch_2' => $request->grpCrossMatch_2,
                        'grpCrossMatch_3' => $request->grpCrossMatch_3,
                        'grpCrossMatch_4' => $request->grpCrossMatch_4,
                        'grpCrossMatch_doc' => $request->grpCrossMatch_doc,
                        'grpCrossMatch_remarks' => $request->grpCrossMatch_remarks,
                        'ecg_1' => $request->ecg_1,
                        'ecg_2' => $request->ecg_2,
                        'ecg_3' => $request->ecg_3,
                        'ecg_4' => $request->ecg_4,
                        'ecg_doc' => $request->ecg_doc,
                        'ecg_remarks' => $request->ecg_remarks,
                        'xray_1' => $request->xray_1,
                        'xray_2' => $request->xray_2,
                        'xray_3' => $request->xray_3,
                        'xray_4' => $request->xray_4,
                        'xray_doc' => $request->xray_doc,
                        'xray_remarks' => $request->xray_remarks,
                        'ctg_1' => $request->ctg_1,
                        'ctg_2' => $request->ctg_2,
                        'ctg_3' => $request->ctg_3,
                        'ctg_4' => $request->ctg_4,
                        'ctg_doc' => $request->ctg_doc,
                        'ctg_remarks' => $request->ctg_remarks,
                        'vsbp_1' => $request->vsbp_1,
                        'vsp_1' => $request->vsp_1,
                        'vsbp_2' => $request->vsbp_2,
                        'vsp_2' => $request->vsp_2,
                        'vsbp_3' => $request->vsbp_3,
                        'vsp_3' => $request->vsp_3,
                        'vsbp_4' => $request->vsbp_4,
                        'vsp_4' => $request->vsp_4,
                        'vsbp_doc' => $request->vsbp_doc,
                        'vsp_doc' => $request->vsp_doc,
                        'vs_remarks' => $request->vs_remarks,
                        'others_1' => $request->others_1,
                        'others_2' => $request->others_2,
                        'others_3' => $request->others_3,
                        'others_4' => $request->others_4,
                        'others_doc' => $request->others_doc,
                        'others_remarks' => $request->others_remarks,
                        'completedBy_1' => $request->completedBy_1,
                        'completedBy_2' => $request->completedBy_2,
                        'completedBy_3' => $request->completedBy_3,
                        'completedBy_4' => $request->completedBy_4,
                        'completedBy_doc' => $request->completedBy_doc,
                        'completedBy_remarks' => $request->completedBy_remarks,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
            
            DB::commit();
            
            $responce = new stdClass();
            
            return json_encode($responce);
        
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function edit(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.otpreopdaycare')
                ->where('mrn','=',$request->mrn_preoperativeDC)
                ->where('episno','=',$request->episno_preoperativeDC)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'surgeonDC' => $request->surgeonDC,
                    'anaestDC' => $request->anaestDC,
                    'natureoperDC' => $request->natureoperDC,
                    'operdateDC' => $request->operdateDC,
                    'idBracelet_ward' => $request->idBracelet_ward,
                    'idBracelet_rec' => $request->idBracelet_rec,
                    'idBracelet_theatre' => $request->idBracelet_theatre,
                    'idBracelet_remarks' => $request->idBracelet_remarks,
                    'operSite_ward' => $request->operSite_ward,
                    'operSite_rec' => $request->operSite_rec,
                    'operSite_theatre' => $request->operSite_theatre,
                    'operSite_remarks' => $request->operSite_remarks,
                    'fasted_time_from' => $request->fasted_time_from,
                    'fasted_time_until' => $request->fasted_time_until,
                    'fasted_hours' => $request->fasted_hours,
                    'fasted_ward' => $request->fasted_ward,
                    'fasted_rec' => $request->fasted_rec,
                    'fasted_theatre' => $request->fasted_theatre,
                    'fasted_remarks' => $request->fasted_remarks,
                    'consentValid_ward' => $request->consentValid_ward,
                    'consentValid_rec' => $request->consentValid_rec,
                    'consentValid_theatre' => $request->consentValid_theatre,
                    'consentValid_remarks' => $request->consentValid_remarks,
                    'consentAnaest_ward' => $request->consentAnaest_ward,
                    'consentAnaest_rec' => $request->consentAnaest_rec,
                    'consentAnaest_theatre' => $request->consentAnaest_theatre,
                    'consentAnaest_remarks' => $request->consentAnaest_remarks,
                    'otGown_ward' => $request->otGown_ward,
                    'otGown_rec' => $request->otGown_rec,
                    'otGown_theatre' => $request->otGown_theatre,
                    'otGown_remarks' => $request->otGown_remarks,
                    'shaving_ward' => $request->shaving_ward,
                    'shaving_rec' => $request->shaving_rec,
                    'shaving_theatre' => $request->shaving_theatre,
                    'shaving_remarks' => $request->shaving_remarks,
                    'bowelPrep_ward' => $request->bowelPrep_ward,
                    'bowelPrep_rec' => $request->bowelPrep_rec,
                    'bowelPrep_theatre' => $request->bowelPrep_theatre,
                    'bowelPrep_remarks' => $request->bowelPrep_remarks,
                    'bladder_ward' => $request->bladder_ward,
                    'bladder_rec' => $request->bladder_rec,
                    'bladder_theatre' => $request->bladder_theatre,
                    'bladder_remarks' => $request->bladder_remarks,
                    'dentures_ward' => $request->dentures_ward,
                    'dentures_rec' => $request->dentures_rec,
                    'dentures_theatre' => $request->dentures_theatre,
                    'dentures_remarks' => $request->dentures_remarks,
                    'lensImpSpec_ward' => $request->lensImpSpec_ward,
                    'lensImpSpec_rec' => $request->lensImpSpec_rec,
                    'lensImpSpec_theatre' => $request->lensImpSpec_theatre,
                    'lensImpSpec_remarks' => $request->lensImpSpec_remarks,
                    'nailVarnish_ward' => $request->nailVarnish_ward,
                    'nailVarnish_rec' => $request->nailVarnish_rec,
                    'nailVarnish_theatre' => $request->nailVarnish_theatre,
                    'nailVarnish_remarks' => $request->nailVarnish_remarks,
                    'hairClips_ward' => $request->hairClips_ward,
                    'hairClips_rec' => $request->hairClips_rec,
                    'hairClips_theatre' => $request->hairClips_theatre,
                    'hairClips_remarks' => $request->hairClips_remarks,
                    'valuables_ward' => $request->valuables_ward,
                    'valuables_rec' => $request->valuables_rec,
                    'valuables_theatre' => $request->valuables_theatre,
                    'valuables_remarks' => $request->valuables_remarks,
                    'ivFluids_ward' => $request->ivFluids_ward,
                    'ivFluids_rec' => $request->ivFluids_rec,
                    'ivFluids_theatre' => $request->ivFluids_theatre,
                    'ivFluids_remarks' => $request->ivFluids_remarks,
                    'premedGiven_hours' => $request->premedGiven_hours,
                    'premedGiven_ward' => $request->premedGiven_ward,
                    'premedGiven_rec' => $request->premedGiven_rec,
                    'premedGiven_theatre' => $request->premedGiven_theatre,
                    'premedGiven_remarks' => $request->premedGiven_remarks,
                    'medChart_ward' => $request->medChart_ward,
                    'medChart_rec' => $request->medChart_rec,
                    'medChart_theatre' => $request->medChart_theatre,
                    'medChart_remarks' => $request->medChart_remarks,
                    'caseNote_ward' => $request->caseNote_ward,
                    'caseNote_rec' => $request->caseNote_rec,
                    'caseNote_theatre' => $request->caseNote_theatre,
                    'caseNote_remarks' => $request->caseNote_remarks,
                    'oldNotes_ward' => $request->oldNotes_ward,
                    'oldNotes_rec' => $request->oldNotes_rec,
                    'oldNotes_theatre' => $request->oldNotes_theatre,
                    'oldNotes_remarks' => $request->oldNotes_remarks,
                    'ptBelongings_ward' => $request->ptBelongings_ward,
                    'ptBelongings_rec' => $request->ptBelongings_rec,
                    'ptBelongings_theatre' => $request->ptBelongings_theatre,
                    'ptBelongings_remarks' => $request->ptBelongings_remarks,
                    'allergies_ward' => $request->allergies_ward,
                    'allergies_rec' => $request->allergies_rec,
                    'allergies_theatre' => $request->allergies_theatre,
                    'allergies_remarks' => $request->allergies_remarks,
                    'medLegalCase_ward' => $request->medLegalCase_ward,
                    'medLegalCase_rec' => $request->medLegalCase_rec,
                    'medLegalCase_theatre' => $request->medLegalCase_theatre,
                    'medLegalCase_remarks' => $request->medLegalCase_remarks,
                    'checkedBy_ward' => $request->checkedBy_ward,
                    'checkedBy_rec' => $request->checkedBy_rec,
                    'checkedBy_theatre' => $request->checkedBy_theatre,
                    'checkedBy_remarks' => $request->checkedBy_remarks,
                    'checkedDate_ward' => $request->checkedDate_ward,
                    'checkedDate_rec' => $request->checkedDate_rec,
                    'checkedDate_theatre' => $request->checkedDate_theatre,
                    'checkedDate_remarks' => $request->checkedDate_remarks,
                    'bloodTest_1' => $request->bloodTest_1,
                    'bloodTest_2' => $request->bloodTest_2,
                    'bloodTest_3' => $request->bloodTest_3,
                    'bloodTest_4' => $request->bloodTest_4,
                    'bloodTest_doc' => $request->bloodTest_doc,
                    'bloodTest_remarks' => $request->bloodTest_remarks,
                    'grpCrossMatch_1' => $request->grpCrossMatch_1,
                    'grpCrossMatch_2' => $request->grpCrossMatch_2,
                    'grpCrossMatch_3' => $request->grpCrossMatch_3,
                    'grpCrossMatch_4' => $request->grpCrossMatch_4,
                    'grpCrossMatch_doc' => $request->grpCrossMatch_doc,
                    'grpCrossMatch_remarks' => $request->grpCrossMatch_remarks,
                    'ecg_1' => $request->ecg_1,
                    'ecg_2' => $request->ecg_2,
                    'ecg_3' => $request->ecg_3,
                    'ecg_4' => $request->ecg_4,
                    'ecg_doc' => $request->ecg_doc,
                    'ecg_remarks' => $request->ecg_remarks,
                    'xray_1' => $request->xray_1,
                    'xray_2' => $request->xray_2,
                    'xray_3' => $request->xray_3,
                    'xray_4' => $request->xray_4,
                    'xray_doc' => $request->xray_doc,
                    'xray_remarks' => $request->xray_remarks,
                    'ctg_1' => $request->ctg_1,
                    'ctg_2' => $request->ctg_2,
                    'ctg_3' => $request->ctg_3,
                    'ctg_4' => $request->ctg_4,
                    'ctg_doc' => $request->ctg_doc,
                    'ctg_remarks' => $request->ctg_remarks,
                    'vsbp_1' => $request->vsbp_1,
                    'vsp_1' => $request->vsp_1,
                    'vsbp_2' => $request->vsbp_2,
                    'vsp_2' => $request->vsp_2,
                    'vsbp_3' => $request->vsbp_3,
                    'vsp_3' => $request->vsp_3,
                    'vsbp_4' => $request->vsbp_4,
                    'vsp_4' => $request->vsp_4,
                    'vsbp_doc' => $request->vsbp_doc,
                    'vsp_doc' => $request->vsp_doc,
                    'vs_remarks' => $request->vs_remarks,
                    'others_1' => $request->others_1,
                    'others_2' => $request->others_2,
                    'others_3' => $request->others_3,
                    'others_4' => $request->others_4,
                    'others_doc' => $request->others_doc,
                    'others_remarks' => $request->others_remarks,
                    'completedBy_1' => $request->completedBy_1,
                    'completedBy_2' => $request->completedBy_2,
                    'completedBy_3' => $request->completedBy_3,
                    'completedBy_4' => $request->completedBy_4,
                    'completedBy_doc' => $request->completedBy_doc,
                    'completedBy_remarks' => $request->completedBy_remarks,
                    'upduser'  => session('username'),
                    'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                ]);
            
            // $queries = DB::getQueryLog();
            // dump($queries);
            
            DB::commit();
            
            $responce = new stdClass();
            
            return json_encode($responce);
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function get_table_preoperativeDC(Request $request){

        // $otmanage_obj = DB::table('nursing.otmanage')
        //             ->where('compcode','=',session('compcode'))
        //             ->where('mrn','=',$request->mrn)
        //             ->where('episno','=',$request->episno);
        
        $preopdc_obj = DB::table('nursing.otpreopdaycare')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$request->mrn)
                    ->where('episno','=',$request->episno);
        
        $responce = new stdClass();
        
        // if($otmanage_obj->exists()){
        //     $otmanage_obj = $otmanage_obj->first();
        //     $responce->otmanage = $otmanage_obj;
        // }

        if($preopdc_obj->exists()){
            $preopdc_obj = $preopdc_obj->first();
            $responce->preopdc = $preopdc_obj;
        }
        
        return json_encode($responce);
        
    }

}