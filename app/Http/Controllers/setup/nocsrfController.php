<?php

namespace App\Http\Controllers\setup;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;

class nocsrfController extends defaultController
{   

    public function __construct(){
    }

    public function form(Request $request){  
        switch($request->action){
            case 'register_imsc':
                return $this->register_imsc($request);
            default:
                return 'error happen..';
        }
    }

    public function register_imsc(Request $request){
        DB::beginTransaction();
        try {
            $patmast_req = (object)$request->patmast[0];
            $episode_req = (object)$request->episode[0];
            $epispayer_req = $request->epispayer;
            $queue_req = $request->queue;
            $docalloc_req = $request->docalloc;
            $bedalloc_req = $request->bedalloc;
            $doctor_req = $request->doctor;
            $debtor_req = $request->debtor;

            // print_r($debtor_req);
            // return 0;

            if(!empty($patmast_req->mrn)){
                $patmast = DB::table('hisdb.pat_mast')
                                ->where('compcode',$patmast_req->compcode)
                                ->where('mrn',$patmast_req->mrn);

                if(!$patmast->exists()){
                    $this->patmast_add($patmast_req);
                    print_r('patmast_add ');
                }else{
                    $this->patmast_edit($patmast_req);
                    print_r('patmast_edit ');
                }
            }

            if(!empty($episode_req->mrn) && !empty($episode_req->episno)){
                $episode = DB::table('hisdb.episode')
                                ->where('compcode',$episode_req->compcode)
                                ->where('mrn',$episode_req->mrn)
                                ->where('episno',$episode_req->episno);

                if(!$episode->exists()){
                    $this->episode_add($episode_req);
                    print_r('episode_add ');
                }else{
                    $this->episode_edit($episode_req);
                    print_r('episode_edit ');
                }
            }

            foreach ($epispayer_req as $epispayer_obj) {
                $epispayer_obj = (object)$epispayer_obj;
                if(!empty($epispayer_obj->mrn) && !empty($epispayer_obj->episno) && !empty($epispayer_obj->lineno)){
                    $epispayer = DB::table('hisdb.epispayer')
                                    ->where('compcode',$epispayer_obj->compcode)
                                    ->where('mrn',$epispayer_obj->mrn)
                                    ->where('episno',$epispayer_obj->episno)
                                    ->where('lineno',$epispayer_obj->lineno);

                    if(!$epispayer->exists()){
                        $this->epispayer_add($epispayer_obj);
                        print_r('epispayer_add ');
                    }else{
                        $this->epispayer_edit($epispayer_obj);
                        print_r('epispayer_edit ');
                    }
                }
            }

            $queue_obj = (object)$queue_req[0];
            DB::table('hisdb.queue')
                        ->where('compcode',$queue_obj->compcode)
                        // ->where('deptcode',$queue_obj->deptcode)
                        ->where('mrn',$queue_obj->mrn)
                        ->where('episno',$queue_obj->episno)
                        ->delete();

            foreach ($queue_req as $queue_obj) {
                $queue_obj = (object)$queue_obj;
                if(!empty($queue_obj->mrn) && !empty($queue_obj->episno) && !empty($queue_obj->deptcode)){
                    // $queue = DB::table('hisdb.queue')
                    //                 ->where('compcode',$queue_obj->compcode)
                    //                 ->where('deptcode',$queue_obj->deptcode)
                    //                 ->where('mrn',$queue_obj->mrn)
                    //                 ->where('episno',$queue_obj->episno);

                    // if(!$queue->exists()){

                    $this->queue_add($queue_obj);
                    print_r('queue_add ');
                    // }
                    // else{
                    //     $this->queue_edit($queue_obj);
                    //     print_r('queue_edit ');
                    // }
                }
            }


            foreach ($docalloc_req as $docalloc_obj) {
                $docalloc_obj = (object)$docalloc_obj;
                if(!empty($docalloc_obj->mrn) && !empty($docalloc_obj->episno) && !empty($docalloc_obj->allocno)){
                    $docalloc = DB::table('hisdb.docalloc')
                                    ->where('compcode',$docalloc_obj->compcode)
                                    ->where('mrn',$docalloc_obj->mrn)
                                    ->where('episno',$docalloc_obj->episno)
                                    ->where('allocno',$docalloc_obj->allocno);

                    if(!$docalloc->exists()){
                        $this->docalloc_add($docalloc_obj);
                        print_r('docalloc_add ');
                    }else{
                        $this->docalloc_edit($docalloc_obj);
                        print_r('docalloc_edit ');
                    }
                }
            }

            foreach ($bedalloc_req as $bedalloc_obj) {
                $bedalloc_obj = (object)$bedalloc_obj;
                if(!empty($bedalloc_obj->mrn) && !empty($docalloc_obj->episno) && !empty($docalloc_obj->allocno)){
                    $bedalloc = DB::table('hisdb.bedalloc')
                                    ->where('compcode',$bedalloc_obj->compcode)
                                    ->where('mrn',$bedalloc_obj->mrn)
                                    ->where('episno',$bedalloc_obj->episno)
                                    ->where('anum',$bedalloc_obj->anum);

                    if(!$bedalloc->exists()){
                        $this->bedalloc_add($bedalloc_obj);
                        print_r('bedalloc_add ');
                    }else{
                        $this->bedalloc_edit($bedalloc_obj);
                        print_r('bedalloc_edit ');
                    }
                }
            }

            foreach ($doctor_req as $doctor_obj) {
                $doctor_obj = (object)$doctor_obj;
                if(!empty($doctor_obj->doctorcode)){
                    $doctor = DB::table('hisdb.doctor')
                                    ->where('compcode',$doctor_obj->compcode)
                                    ->where('doctorcode',$doctor_obj->doctorcode);

                    if(!$doctor->exists()){
                        $this->doctor_add($doctor_obj);
                        print_r('doctor_add ');
                    }else{
                        $this->doctor_edit($doctor_obj);
                        print_r('doctor_edit ');
                    }
                }
            }

            foreach ($debtor_req as $debtor_obj) {
                $debtor_obj = (object)$debtor_obj;
                if(!empty($debtor_obj->debtorcode)){
                    $debtor = DB::table('debtor.debtormast')
                                    ->where('compcode',$debtor_obj->compcode)
                                    ->where('debtorcode',$debtor_obj->debtorcode);

                    if(!$debtor->exists()){
                        $this->debtor_add($debtor_obj);
                        print_r('debtor_add ');
                    }else{
                        $this->debtor_edit($debtor_obj);
                        print_r('debtor_edit ');
                    }
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function patmast_add($patmast){
        DB::table('hisdb.pat_mast')
            ->insert([
                'compcode' => $this->null($patmast->compcode),
                'mrn' => $this->null($patmast->mrn),
                'episno' => $this->null($patmast->episno),
                'name' => $this->null($patmast->name),
                'call_name' => $this->null($patmast->call_name),
                'addtype' => $this->null($patmast->addtype),
                'address1' => $this->null($patmast->address1),
                'address2' => $this->null($patmast->address2),
                'address3' => $this->null($patmast->address3),
                'postcode' => $this->null($patmast->postcode),
                'citycode' => $this->null($patmast->citycode),
                'areacode' => $this->null($patmast->areacode),
                'statecode' => $this->null($patmast->statecode),
                'countrycode' => $this->null($patmast->countrycode),
                'telh' => $this->null($patmast->telh),
                'telhp' => $this->null($patmast->telhp),
                'telo' => $this->null($patmast->telo),
                'tel_o_ext' => $this->null($patmast->tel_o_ext),
                'ptel' => $this->null($patmast->ptel),
                'ptel_hp' => $this->null($patmast->ptel_hp),
                'id_type' => $this->null($patmast->id_type),
                'idnumber' => $this->null($patmast->idnumber),
                'newic' => $this->null($patmast->newic),
                'oldic' => $this->null($patmast->oldic),
                'icolor' => $this->null($patmast->icolor),
                'sex' => $this->null($patmast->sex),
                'dob' => $this->turn_date($this->null($patmast->dob)),
                'religion' => $this->null($patmast->religion),
                'allergycode1' => $this->null($patmast->allergycode1),
                'allergycode2' => $this->null($patmast->allergycode2),
                'century' => $this->null($patmast->century),
                'citizencode' => $this->null($patmast->citizencode),
                'occupcode' => $this->null($patmast->occupcode),
                'staffid' => $this->null($patmast->staffid),
                'maritalcode' => $this->null($patmast->maritalcode),
                'languagecode' => $this->null($patmast->languagecode),
                'titlecode' => $this->null($patmast->titlecode),
                'racecode' => $this->null($patmast->racecode),
                'bloodgrp' => $this->null($patmast->bloodgrp),
                'accum_chg' => $this->null($patmast->accum_chg),
                'accum_paid' => $this->null($patmast->accum_paid),
                'first_visit_date' => $this->turn_date($this->null($patmast->first_visit_date)),
                'reg_date' => $this->turn_date($this->null($patmast->reg_date)),
                'last_visit_date' => $this->turn_date($this->null($patmast->last_visit_date)),
                'last_episno' => $this->null($patmast->last_episno),
                'patstatus' => $this->null($patmast->patstatus),
                'confidential' => $this->null($patmast->confidential),
                'active' => $this->null($patmast->active),
                'firstipepisno' => $this->null($patmast->firstipepisno),
                'firstopepisno' => $this->null($patmast->firstopepisno),
                'adduser' => $this->null($patmast->adduser),
                'adddate' => $this->turn_date($this->null($patmast->adddate)),
                'lastupdate' => $this->turn_date($this->null($patmast->lastupdate)),
                'lastuser' => $this->null($patmast->lastuser),
                'offadd1' => $this->null($patmast->offadd1),
                'offadd2' => $this->null($patmast->offadd2),
                'offadd3' => $this->null($patmast->offadd3),
                'offpostcode' => $this->null($patmast->offpostcode),
                'mrfolder' => $this->null($patmast->mrfolder),
                'mrloc' => $this->null($patmast->mrloc),
                'mractive' => $this->null($patmast->mractive),
                'oldmrn' => $this->null($patmast->oldmrn),
                'newmrn' => $this->null($patmast->newmrn),
                'remarks' => $this->null($patmast->remarks),
                'relatecode' => $this->null($patmast->relatecode),
                'childno' => $this->null($patmast->childno),
                'corpcomp' => $this->null($patmast->corpcomp),
                'email' => $this->null($patmast->email),
                'email_official' => $this->null($patmast->email_official),
                'currentepis' => $this->null($patmast->currentepis),
                'namesndx' => $this->null($patmast->namesndx),
                'birthplace' => $this->null($patmast->birthplace),
                'tngid' => $this->null($patmast->tngid),
                'patientimage' => $this->null($patmast->patientimage),
                'padd1' => $this->null($patmast->padd1),
                'padd2' => $this->null($patmast->padd2),
                'padd3' => $this->null($patmast->padd3),
                'ppostcode' => $this->null($patmast->ppostcode),
                'deptcode' => $this->null($patmast->deptcode),
                'deceaseddate' => $this->turn_date($this->null($patmast->deceaseddate)),
                'patientcat' => $this->null($patmast->patientcat),
                'pattype' => $this->null($patmast->pattype),
                // 'idno' => $this->null($patmast->idno),
                'upduser' => $this->null($patmast->upduser),
                'upddate' => $this->turn_date($this->null($patmast->upddate)),
                'recstatus' => $this->null($patmast->recstatus),
                'loginid' => $this->null($patmast->loginid)
            ]);
    }

    public function patmast_edit($patmast){
        DB::table('hisdb.pat_mast')
            ->where('compcode','10A')
            ->where('mrn',$patmast->mrn)
            ->update([
                'compcode' => $this->null($patmast->compcode),
                'mrn' => $this->null($patmast->mrn),
                'episno' => $this->null($patmast->episno),
                'name' => $this->null($patmast->name),
                'call_name' => $this->null($patmast->call_name),
                'addtype' => $this->null($patmast->addtype),
                'address1' => $this->null($patmast->address1),
                'address2' => $this->null($patmast->address2),
                'address3' => $this->null($patmast->address3),
                'postcode' => $this->null($patmast->postcode),
                'citycode' => $this->null($patmast->citycode),
                'areacode' => $this->null($patmast->areacode),
                'statecode' => $this->null($patmast->statecode),
                'countrycode' => $this->null($patmast->countrycode),
                'telh' => $this->null($patmast->telh),
                'telhp' => $this->null($patmast->telhp),
                'telo' => $this->null($patmast->telo),
                'tel_o_ext' => $this->null($patmast->tel_o_ext),
                'ptel' => $this->null($patmast->ptel),
                'ptel_hp' => $this->null($patmast->ptel_hp),
                'id_type' => $this->null($patmast->id_type),
                'idnumber' => $this->null($patmast->idnumber),
                'newic' => $this->null($patmast->newic),
                'oldic' => $this->null($patmast->oldic),
                'icolor' => $this->null($patmast->icolor),
                'sex' => $this->null($patmast->sex),
                'dob' => $this->turn_date($this->null($patmast->dob)),
                'religion' => $this->null($patmast->religion),
                'allergycode1' => $this->null($patmast->allergycode1),
                'allergycode2' => $this->null($patmast->allergycode2),
                'century' => $this->null($patmast->century),
                'citizencode' => $this->null($patmast->citizencode),
                'occupcode' => $this->null($patmast->occupcode),
                'staffid' => $this->null($patmast->staffid),
                'maritalcode' => $this->null($patmast->maritalcode),
                'languagecode' => $this->null($patmast->languagecode),
                'titlecode' => $this->null($patmast->titlecode),
                'racecode' => $this->null($patmast->racecode),
                'bloodgrp' => $this->null($patmast->bloodgrp),
                'accum_chg' => $this->null($patmast->accum_chg),
                'accum_paid' => $this->null($patmast->accum_paid),
                'first_visit_date' => $this->turn_date($this->null($patmast->first_visit_date)),
                'reg_date' => $this->turn_date($this->null($patmast->reg_date)),
                'last_visit_date' => $this->turn_date($this->null($patmast->last_visit_date)),
                'last_episno' => $this->null($patmast->last_episno),
                'patstatus' => $this->null($patmast->patstatus),
                'confidential' => $this->null($patmast->confidential),
                'active' => $this->null($patmast->active),
                'firstipepisno' => $this->null($patmast->firstipepisno),
                'firstopepisno' => $this->null($patmast->firstopepisno),
                'adduser' => $this->null($patmast->adduser),
                'adddate' => $this->turn_date($this->null($patmast->adddate)),
                'lastupdate' => $this->turn_date($this->null($patmast->lastupdate)),
                'lastuser' => $this->null($patmast->lastuser),
                'offadd1' => $this->null($patmast->offadd1),
                'offadd2' => $this->null($patmast->offadd2),
                'offadd3' => $this->null($patmast->offadd3),
                'offpostcode' => $this->null($patmast->offpostcode),
                'mrfolder' => $this->null($patmast->mrfolder),
                'mrloc' => $this->null($patmast->mrloc),
                'mractive' => $this->null($patmast->mractive),
                'oldmrn' => $this->null($patmast->oldmrn),
                'newmrn' => $this->null($patmast->newmrn),
                'remarks' => $this->null($patmast->remarks),
                'relatecode' => $this->null($patmast->relatecode),
                'childno' => $this->null($patmast->childno),
                'corpcomp' => $this->null($patmast->corpcomp),
                'email' => $this->null($patmast->email),
                'email_official' => $this->null($patmast->email_official),
                'currentepis' => $this->null($patmast->currentepis),
                'namesndx' => $this->null($patmast->namesndx),
                'birthplace' => $this->null($patmast->birthplace),
                'tngid' => $this->null($patmast->tngid),
                'patientimage' => $this->null($patmast->patientimage),
                'padd1' => $this->null($patmast->padd1),
                'padd2' => $this->null($patmast->padd2),
                'padd3' => $this->null($patmast->padd3),
                'ppostcode' => $this->null($patmast->ppostcode),
                'deptcode' => $this->null($patmast->deptcode),
                'deceaseddate' => $this->turn_date($this->null($patmast->deceaseddate)),
                'patientcat' => $this->null($patmast->patientcat),
                'pattype' => $this->null($patmast->pattype),
                // 'idno' => $this->null($patmast->idno),
                'upduser' => $this->null($patmast->upduser),
                'upddate' => $this->turn_date($this->null($patmast->upddate)),
                'recstatus' => $this->null($patmast->recstatus),
                'loginid' => $this->null($patmast->loginid)
            ]);
    }

    public function episode_add($episode){
        $reff_ed = null;
        $reff_physio = null;
        if(strtoupper($episode->regdept) == 'A&E'){
            $reff_ed = 1;
        }else if(strtoupper($episode->regdept) == 'PHY'){
            $reff_physio = 1;
        }

        DB::table('hisdb.episode')
            ->insert([
                'compcode' => $this->null($episode->compcode),
                'mrn' => $this->null($episode->mrn),
                'episno' => $this->null($episode->episno),
                'admsrccode' => $this->null($episode->admsrccode),
                'epistycode' => $this->null($episode->epistycode),
                'case_code' => $this->null($episode->case_code),
                'ward' => $this->null($episode->ward),
                'bedtype' => $this->null($episode->bedtype),
                'room' => $this->null($episode->room),
                'bed' => $this->null($episode->bed),
                'admdoctor' => $this->null($episode->admdoctor),
                'attndoctor' => $this->null($episode->attndoctor),
                'refdoctor' => $this->null($episode->refdoctor),
                'prescribedays' => $this->null($episode->prescribedays),
                'pay_type' => $this->null($episode->pay_type),
                'pyrmode' => $this->null($episode->pyrmode),
                'climitauthid' => $this->null($episode->climitauthid),
                'crnumber' => $this->null($episode->crnumber),
                'depositreq' => $this->null($episode->depositreq),
                'deposit' => $this->null($episode->deposit),
                'pkgcode' => $this->null($episode->pkgcode),
                'billtype' => $this->null($episode->billtype),
                'remarks' => $this->null($episode->remarks),
                'episstatus' => $this->null($episode->episstatus),
                'episactive' => $this->null($episode->episactive),
                'adddate' => $this->turn_date($this->null($episode->adddate)),
                'adduser' => $this->null($episode->adduser),
                'reg_date' => $this->turn_date($this->null($episode->reg_date)),
                'reg_time' => $this->null($episode->reg_time),
                'dischargedate' => $this->turn_date($this->null($episode->dischargedate)),
                'dischargeuser' => $this->null($episode->dischargeuser),
                'dischargetime' => $this->null($episode->dischargetime),
                'dischargedest' => $this->null($episode->dischargedest),
                'allocdoc' => $this->null($episode->allocdoc),
                'allocbed' => $this->null($episode->allocbed),
                'allocnok' => $this->null($episode->allocnok),
                'allocpayer' => $this->null($episode->allocpayer),
                'allocicd' => $this->null($episode->allocicd),
                'lastupdate' => $this->turn_date($this->null($episode->lastupdate)),
                'lastuser' => $this->null($episode->lastuser),
                'lasttime' => $this->null($episode->lasttime),
                'procode' => $this->null($episode->procode),
                'dischargediag' => $this->null($episode->dischargediag),
                'lodgerno' => $this->null($episode->lodgerno),
                'regdept' => $this->null($episode->regdept),
                'diet1' => $this->null($episode->diet1),
                'diet2' => $this->null($episode->diet2),
                'diet3' => $this->null($episode->diet3),
                'diet4' => $this->null($episode->diet4),
                'diet5' => $this->null($episode->diet5),
                'glauthid' => $this->null($episode->glauthid),
                'treatcode' => $this->null($episode->treatcode),
                'diagcode' => $this->null($episode->diagcode),
                'complain' => $this->null($episode->complain),
                'diagfinal' => $this->null($episode->diagfinal),
                'clinicalnote' => $this->null($episode->clinicalnote),
                'conversion' => $this->null($episode->conversion),
                'newcasep' => $this->null($episode->newcasep),
                'newcasenp' => $this->null($episode->newcasenp),
                'followupp' => $this->null($episode->followupp),
                'followupnp' => $this->null($episode->followupnp),
                'bed2' => $this->null($episode->bed2),
                'bed3' => $this->null($episode->bed3),
                'bed4' => $this->null($episode->bed4),
                'bed5' => $this->null($episode->bed5),
                'bed6' => $this->null($episode->bed6),
                'bed7' => $this->null($episode->bed7),
                'bed8' => $this->null($episode->bed8),
                'bed9' => $this->null($episode->bed9),
                'bed10' => $this->null($episode->bed10),
                'diagprov' => $this->null($episode->diagprov),
                'visitcase' => $this->null($episode->visitcase),
                'pkgautono' => $this->null($episode->pkgautono),
                'agreementid' => $this->null($episode->agreementid),
                'adminfees' => $this->null($episode->adminfees),
                'eddept' => $this->null($episode->eddept),
                'payer' => $this->null($episode->payer),
                'reff_ed' => $reff_ed,
                'reff_physio' => $reff_physio
            ]);
    }

    public function episode_edit($episode){
        DB::table('hisdb.episode')
            ->where('compcode','10A')
            ->where('mrn',$episode->mrn)
            ->where('episno',$episode->episno)
            ->update([
                'compcode' => $this->null($episode->compcode),
                'mrn' => $this->null($episode->mrn),
                'episno' => $this->null($episode->episno),
                'admsrccode' => $this->null($episode->admsrccode),
                'epistycode' => $this->null($episode->epistycode),
                'case_code' => $this->null($episode->case_code),
                'ward' => $this->null($episode->ward),
                'bedtype' => $this->null($episode->bedtype),
                'room' => $this->null($episode->room),
                'bed' => $this->null($episode->bed),
                'admdoctor' => $this->null($episode->admdoctor),
                'attndoctor' => $this->null($episode->attndoctor),
                'refdoctor' => $this->null($episode->refdoctor),
                'prescribedays' => $this->null($episode->prescribedays),
                'pay_type' => $this->null($episode->pay_type),
                'pyrmode' => $this->null($episode->pyrmode),
                'climitauthid' => $this->null($episode->climitauthid),
                'crnumber' => $this->null($episode->crnumber),
                'depositreq' => $this->null($episode->depositreq),
                'deposit' => $this->null($episode->deposit),
                'pkgcode' => $this->null($episode->pkgcode),
                'billtype' => $this->null($episode->billtype),
                'remarks' => $this->null($episode->remarks),
                'episstatus' => $this->null($episode->episstatus),
                'episactive' => $this->null($episode->episactive),
                'adddate' => $this->turn_date($this->null($episode->adddate)),
                'adduser' => $this->null($episode->adduser),
                'reg_date' => $this->turn_date($this->null($episode->reg_date)),
                'reg_time' => $this->null($episode->reg_time),
                'dischargedate' => $this->turn_date($this->null($episode->dischargedate)),
                'dischargeuser' => $this->null($episode->dischargeuser),
                'dischargetime' => $this->null($episode->dischargetime),
                'dischargedest' => $this->null($episode->dischargedest),
                'allocdoc' => $this->null($episode->allocdoc),
                'allocbed' => $this->null($episode->allocbed),
                'allocnok' => $this->null($episode->allocnok),
                'allocpayer' => $this->null($episode->allocpayer),
                'allocicd' => $this->null($episode->allocicd),
                'lastupdate' => $this->turn_date($this->null($episode->lastupdate)),
                'lastuser' => $this->null($episode->lastuser),
                'lasttime' => $this->null($episode->lasttime),
                'procode' => $this->null($episode->procode),
                'dischargediag' => $this->null($episode->dischargediag),
                'lodgerno' => $this->null($episode->lodgerno),
                'regdept' => $this->null($episode->regdept),
                'diet1' => $this->null($episode->diet1),
                'diet2' => $this->null($episode->diet2),
                'diet3' => $this->null($episode->diet3),
                'diet4' => $this->null($episode->diet4),
                'diet5' => $this->null($episode->diet5),
                'glauthid' => $this->null($episode->glauthid),
                'treatcode' => $this->null($episode->treatcode),
                'diagcode' => $this->null($episode->diagcode),
                'complain' => $this->null($episode->complain),
                'diagfinal' => $this->null($episode->diagfinal),
                'clinicalnote' => $this->null($episode->clinicalnote),
                'conversion' => $this->null($episode->conversion),
                'newcasep' => $this->null($episode->newcasep),
                'newcasenp' => $this->null($episode->newcasenp),
                'followupp' => $this->null($episode->followupp),
                'followupnp' => $this->null($episode->followupnp),
                'bed2' => $this->null($episode->bed2),
                'bed3' => $this->null($episode->bed3),
                'bed4' => $this->null($episode->bed4),
                'bed5' => $this->null($episode->bed5),
                'bed6' => $this->null($episode->bed6),
                'bed7' => $this->null($episode->bed7),
                'bed8' => $this->null($episode->bed8),
                'bed9' => $this->null($episode->bed9),
                'bed10' => $this->null($episode->bed10),
                'diagprov' => $this->null($episode->diagprov),
                'visitcase' => $this->null($episode->visitcase),
                'pkgautono' => $this->null($episode->pkgautono),
                'agreementid' => $this->null($episode->agreementid),
                'adminfees' => $this->null($episode->adminfees),
                'eddept' => $this->null($episode->eddept),
                'payer' => $this->null($episode->payer)
            ]);
    }

    public function epispayer_add($epispayer){
        DB::table('hisdb.epispayer')
                ->insert([
                    'compcode' => $this->null($epispayer->compcode),
                    'mrn' => $this->null($epispayer->mrn),
                    'episno' => $this->null($epispayer->episno),
                    'payercode' => $this->null($epispayer->payercode),
                    'lineno' => $this->null($epispayer->lineno),
                    'epistycode' => $this->null($epispayer->epistycode),
                    'pay_type' => $this->null($epispayer->pay_type),
                    'pyrmode' => $this->null($epispayer->pyrmode),
                    'pyrcharge' => $this->null($epispayer->pyrcharge),
                    'pyrcrdtlmt' => $this->null_yes($this->null($epispayer->pyrcrdtlmt)),
                    'pyrlmtamt' => $this->null($epispayer->pyrlmtamt),
                    'totbal' => $this->null($epispayer->totbal),
                    'allgroup' => $this->null($epispayer->allgroup),
                    'alldept' => $this->null($epispayer->alldept),
                    'adddate' => $this->turn_date($this->null($epispayer->adddate)),
                    'adduser' => $this->null($epispayer->adduser),
                    'lastupdate' => $this->turn_date($this->null($epispayer->lastupdate)),
                    'billtype' => $this->null($epispayer->billtype),
                    'refno' => $this->null($epispayer->refno),
                    'chgrate' => $this->null($epispayer->chgrate)
                ]);
    }

    public function epispayer_edit($epispayer){
        DB::table('hisdb.epispayer')
                ->where('compcode','10A')
                ->where('mrn',$epispayer->mrn)
                ->where('episno',$epispayer->episno)
                ->where('lineno',$epispayer->lineno)
                ->update([
                    'compcode' => $this->null($epispayer->compcode),
                    'mrn' => $this->null($epispayer->mrn),
                    'episno' => $this->null($epispayer->episno),
                    'payercode' => $this->null($epispayer->payercode),
                    'lineno' => $this->null($epispayer->lineno),
                    'epistycode' => $this->null($epispayer->epistycode),
                    'pay_type' => $this->null($epispayer->pay_type),
                    'pyrmode' => $this->null($epispayer->pyrmode),
                    'pyrcharge' => $this->null($epispayer->pyrcharge),
                    'pyrcrdtlmt' => $this->null_yes($this->null($epispayer->pyrcrdtlmt)),
                    'pyrlmtamt' => $this->null($epispayer->pyrlmtamt),
                    'totbal' => $this->null($epispayer->totbal),
                    'allgroup' => $this->null($epispayer->allgroup),
                    'alldept' => $this->null($epispayer->alldept),
                    'adddate' => $this->turn_date($this->null($epispayer->adddate)),
                    'adduser' => $this->null($epispayer->adduser),
                    'lastupdate' => $this->turn_date($this->null($epispayer->lastupdate)),
                    'billtype' => $this->null($epispayer->billtype),
                    'refno' => $this->null($epispayer->refno),
                    'chgrate' => $this->null($epispayer->chgrate)
                ]);
    }

    public function queue_add($queue){
        DB::table('hisdb.queue')
            ->insert([
                'compcode' => $this->null($queue->compcode),
                'queueno' => $this->null($queue->queueno),
                'mrn' => $this->null($queue->mrn),
                'episno' => $this->null($queue->episno),
                'memberno' => $this->null($queue->memberno),
                'chggroup' => $this->null($queue->chggroup),
                'epistycode' => $this->null($queue->epistycode),
                'episqueue' => $this->null($queue->episqueue),
                'counter' => $this->null($queue->counter),
                'bedtype' => $this->null($queue->bedtype),
                'room' => $this->null($queue->room),
                'bed' => $this->null($queue->bed),
                'admdoctor' => $this->null($queue->admdoctor),
                'adddate' => $this->turn_date($this->null($queue->adddate)),
                'adduser' => $this->null($queue->adduser),
                'newic' => $this->null($queue->newic),
                'oldic' => $this->null($queue->oldic),
                'sex' => $this->null($queue->sex),
                'dob' => $this->turn_date($this->null($queue->dob)),
                'religion' => $this->null($queue->religion),
                'racecode' => $this->null($queue->racecode),
                'reg_date' => $this->turn_date($this->null($queue->reg_date)),
                'ageyy' => $this->null($queue->ageyy),
                'case_code' => $this->null($queue->case_code),
                'episstatus' => $this->null($queue->episstatus),
                'reg_time' => $this->null($queue->reg_time),
                'lastupdate' => $this->turn_date($this->null($queue->lastupdate)),
                'lastuser' => $this->null($queue->lastuser),
                'lasttime' => $this->null($queue->lasttime),
                'deptcode' => $this->null($queue->deptcode),
                'seqno' => $this->null($queue->seqno),
                'name' => $this->null($queue->name),
                'agemm' => $this->null($queue->agemm),
                'qdate' => $this->turn_date($this->null($queue->qdate)),
                'qtime' => $this->null($queue->qtime),
                'attndoctor' => $this->null($queue->attndoctor),
                'deposit' => $this->null($queue->deposit),
                'sounddex' => $this->null($queue->sounddex),
                'queuecode' => $this->null($queue->queuecode),
                'ward' => $this->null($queue->ward),
                'qtyorder' => $this->null($queue->qtyorder),
                'qtyissue' => $this->null($queue->qtyissue),
                'ipqueueno' => $this->null($queue->ipqueueno),
                'bed2' => $this->null($queue->bed2),
                'bed3' => $this->null($queue->bed3),
                'bed4' => $this->null($queue->bed4),
                'bed5' => $this->null($queue->bed5),
                'bed6' => $this->null($queue->bed6),
                'bed7' => $this->null($queue->bed7),
                'bed8' => $this->null($queue->bed8),
                'bed9' => $this->null($queue->bed9),
                'bed10' => $this->null($queue->bed10),
                'telhp' => $this->null($queue->telhp),
                'telh' => $this->null($queue->telh),
                'billflag' => $this->null($queue->billflag)
            ]);
    }

    public function queue_edit($queue){
        DB::table('hisdb.queue')
            ->where('compcode','10A')
            ->where('queueno',$queue->queueno)
            ->where('mrn',$queue->mrn)
            ->where('episno',$queue->episno)
            ->update([
                'compcode' => $this->null($queue->compcode),
                'queueno' => $this->null($queue->queueno),
                'mrn' => $this->null($queue->mrn),
                'episno' => $this->null($queue->episno),
                'memberno' => $this->null($queue->memberno),
                'chggroup' => $this->null($queue->chggroup),
                'epistycode' => $this->null($queue->epistycode),
                'episqueue' => $this->null($queue->episqueue),
                'counter' => $this->null($queue->counter),
                'bedtype' => $this->null($queue->bedtype),
                'room' => $this->null($queue->room),
                'bed' => $this->null($queue->bed),
                'admdoctor' => $this->null($queue->admdoctor),
                'adddate' => $this->turn_date($this->null($queue->adddate)),
                'adduser' => $this->null($queue->adduser),
                'newic' => $this->null($queue->newic),
                'oldic' => $this->null($queue->oldic),
                'sex' => $this->null($queue->sex),
                'dob' => $this->turn_date($this->null($queue->dob)),
                'religion' => $this->null($queue->religion),
                'racecode' => $this->null($queue->racecode),
                'reg_date' => $this->turn_date($this->null($queue->reg_date)),
                'ageyy' => $this->null($queue->ageyy),
                'case_code' => $this->null($queue->case_code),
                'episstatus' => $this->null($queue->episstatus),
                'reg_time' => $this->null($queue->reg_time),
                'lastupdate' => $this->turn_date($this->null($queue->lastupdate)),
                'lastuser' => $this->null($queue->lastuser),
                'lasttime' => $this->null($queue->lasttime),
                'deptcode' => $this->null($queue->deptcode),
                'seqno' => $this->null($queue->seqno),
                'name' => $this->null($queue->name),
                'agemm' => $this->null($queue->agemm),
                'qdate' => $this->turn_date($this->null($queue->qdate)),
                'qtime' => $this->null($queue->qtime),
                'attndoctor' => $this->null($queue->attndoctor),
                'deposit' => $this->null($queue->deposit),
                'sounddex' => $this->null($queue->sounddex),
                'queuecode' => $this->null($queue->queuecode),
                'ward' => $this->null($queue->ward),
                'qtyorder' => $this->null($queue->qtyorder),
                'qtyissue' => $this->null($queue->qtyissue),
                'ipqueueno' => $this->null($queue->ipqueueno),
                'bed2' => $this->null($queue->bed2),
                'bed3' => $this->null($queue->bed3),
                'bed4' => $this->null($queue->bed4),
                'bed5' => $this->null($queue->bed5),
                'bed6' => $this->null($queue->bed6),
                'bed7' => $this->null($queue->bed7),
                'bed8' => $this->null($queue->bed8),
                'bed9' => $this->null($queue->bed9),
                'bed10' => $this->null($queue->bed10),
                'telhp' => $this->null($queue->telhp),
                'telh' => $this->null($queue->telh),
                'billflag' => $this->null($queue->billflag)
            ]);
    }

    public function docalloc_add($docalloc){
        DB::table('hisdb.docalloc')
            ->insert([
                'compcode' => $this->null($docalloc->compcode),
                'mrn' => $this->null($docalloc->mrn),
                'episno' => $this->null($docalloc->episno),
                'allocno' => $this->null($docalloc->allocno),
                'doctorcode' => $this->null($docalloc->doctorcode),
                'asdate' => $this->turn_date($this->null($docalloc->asdate)),
                'astime' => $this->null($docalloc->astime),
                'aedate' => $this->turn_date($this->null($docalloc->aedate)),
                'aetime' => $this->null($docalloc->aetime),
                'aprovide' => $this->null($docalloc->aprovide),
                'astatus' => $this->null($docalloc->astatus),
                'areason' => $this->null($docalloc->areason),
                'servicecode' => $this->null($docalloc->servicecode),
                'doctype' => $this->null($docalloc->doctype),
                'epistycode' => $this->null($docalloc->epistycode),
                'adddate' => $this->turn_date($this->null($docalloc->adddate)),
                'adduser' => $this->null($docalloc->adduser),
                'lastupdate' => $this->turn_date($this->null($docalloc->lastupdate)),
                'lastuser' => $this->null($docalloc->lastuser),
                'computerid' => $this->null($docalloc->computerid)
            ]);
    }

    public function docalloc_edit($docalloc){
        DB::table('hisdb.docalloc')
            ->where('compcode','10A')
            ->where('mrn',$docalloc->mrn)
            ->where('episno',$docalloc->episno)
            ->where('allocno',$docalloc->allocno)
            ->update([
                'compcode' => $this->null($docalloc->compcode),
                'mrn' => $this->null($docalloc->mrn),
                'episno' => $this->null($docalloc->episno),
                'allocno' => $this->null($docalloc->allocno),
                'doctorcode' => $this->null($docalloc->doctorcode),
                'asdate' => $this->turn_date($this->null($docalloc->asdate)),
                'astime' => $this->null($docalloc->astime),
                'aedate' => $this->turn_date($this->null($docalloc->aedate)),
                'aetime' => $this->null($docalloc->aetime),
                'aprovide' => $this->null($docalloc->aprovide),
                'astatus' => $this->null($docalloc->astatus),
                'areason' => $this->null($docalloc->areason),
                'servicecode' => $this->null($docalloc->servicecode),
                'doctype' => $this->null($docalloc->doctype),
                'epistycode' => $this->null($docalloc->epistycode),
                'adddate' => $this->turn_date($this->null($docalloc->adddate)),
                'adduser' => $this->null($docalloc->adduser),
                'lastupdate' => $this->turn_date($this->null($docalloc->lastupdate)),
                'lastuser' => $this->null($docalloc->lastuser),
                'computerid' => $this->null($docalloc->computerid)
            ]);
    }

    public function bedalloc_add($bedalloc){
        DB::table('hisdb.bedalloc')
            ->insert([
                'compcode' => $this->null($bedalloc->compcode),
                'mrn' => $this->null($bedalloc->mrn),
                'episno' => $this->null($bedalloc->episno),
                'anum' => $this->null($bedalloc->anum),
                'epistycode' => $this->null($bedalloc->epistycode),
                'acode' => $this->null($bedalloc->acode),
                'atype' => $this->null($bedalloc->atype),
                'asdate' => $this->turn_date($this->null($bedalloc->asdate)),
                'astime' => $this->null($bedalloc->astime),
                'aedate' => $this->turn_date($this->null($bedalloc->aedate)),
                'aetime' => $this->null($bedalloc->aetime),
                'aprovide' => $this->null($bedalloc->aprovide),
                'astatus' => $this->null($bedalloc->astatus),
                'areason' => $this->null($bedalloc->areason),
                'servicecode' => $this->null($bedalloc->servicecode),
                'ward' => $this->null($bedalloc->ward),
                'room' => $this->null($bedalloc->room),
                'bednum' => $this->null($bedalloc->bednum),
                'sex' => $this->null($bedalloc->sex),
                'name' => $this->null($bedalloc->name),
                'isolate' => $this->null($bedalloc->isolate),
                'adddate' => $this->turn_date($this->null($bedalloc->adddate)),
                'adduser' => $this->null($bedalloc->adduser),
                'lastupdate' => $this->turn_date($this->null($bedalloc->lastupdate)),
                'lastuser' => $this->null($bedalloc->lastuser),
                'lodgerno' => $this->null($bedalloc->lodgerno),
                'baby' => $this->null($bedalloc->baby),
                'bed2' => $this->null($bedalloc->bed2),
                'bed3' => $this->null($bedalloc->bed3),
                'bed4' => $this->null($bedalloc->bed4),
                'bed5' => $this->null($bedalloc->bed5),
                'bed6' => $this->null($bedalloc->bed6),
                'bed7' => $this->null($bedalloc->bed7),
                'bed8' => $this->null($bedalloc->bed8),
                'bed9' => $this->null($bedalloc->bed9),
                'bed10' => $this->null($bedalloc->bed10),
                'deluser' => $this->null($bedalloc->deluser),
                'deldate' => $this->turn_date($this->null($bedalloc->deldate)),
                'upduser' => $this->null($bedalloc->upduser),
                'upddate' => $this->turn_date($this->null($bedalloc->upddate)),
                'computerid' => $this->null($bedalloc->computerid)
            ]);
    }

    public function bedalloc_edit($bedalloc){
        DB::table('hisdb.bedalloc')
            ->where('compcode','10A')
            ->where('mrn',$bedalloc->mrn)
            ->where('episno',$bedalloc->episno)
            ->where('anum',$bedalloc->anum)
            ->update([
                'compcode' => $this->null($bedalloc->compcode),
                'mrn' => $this->null($bedalloc->mrn),
                'episno' => $this->null($bedalloc->episno),
                'anum' => $this->null($bedalloc->anum),
                'epistycode' => $this->null($bedalloc->epistycode),
                'acode' => $this->null($bedalloc->acode),
                'atype' => $this->null($bedalloc->atype),
                'asdate' => $this->turn_date($this->null($bedalloc->asdate)),
                'astime' => $this->null($bedalloc->astime),
                'aedate' => $this->turn_date($this->null($bedalloc->aedate)),
                'aetime' => $this->null($bedalloc->aetime),
                'aprovide' => $this->null($bedalloc->aprovide),
                'astatus' => $this->null($bedalloc->astatus),
                'areason' => $this->null($bedalloc->areason),
                'servicecode' => $this->null($bedalloc->servicecode),
                'ward' => $this->null($bedalloc->ward),
                'room' => $this->null($bedalloc->room),
                'bednum' => $this->null($bedalloc->bednum),
                'sex' => $this->null($bedalloc->sex),
                'name' => $this->null($bedalloc->name),
                'isolate' => $this->null($bedalloc->isolate),
                'adddate' => $this->turn_date($this->null($bedalloc->adddate)),
                'adduser' => $this->null($bedalloc->adduser),
                'lastupdate' => $this->turn_date($this->null($bedalloc->lastupdate)),
                'lastuser' => $this->null($bedalloc->lastuser),
                'lodgerno' => $this->null($bedalloc->lodgerno),
                'baby' => $this->null($bedalloc->baby),
                'bed2' => $this->null($bedalloc->bed2),
                'bed3' => $this->null($bedalloc->bed3),
                'bed4' => $this->null($bedalloc->bed4),
                'bed5' => $this->null($bedalloc->bed5),
                'bed6' => $this->null($bedalloc->bed6),
                'bed7' => $this->null($bedalloc->bed7),
                'bed8' => $this->null($bedalloc->bed8),
                'bed9' => $this->null($bedalloc->bed9),
                'bed10' => $this->null($bedalloc->bed10),
                'deluser' => $this->null($bedalloc->deluser),
                'deldate' => $this->turn_date($this->null($bedalloc->deldate)),
                'upduser' => $this->null($bedalloc->upduser),
                'upddate' => $this->turn_date($this->null($bedalloc->upddate)),
                'computerid' => $this->null($bedalloc->computerid)
            ]);
    }

    public function doctor_add($doctor){
        DB::table('hisdb.doctor')
            ->insert([
                'compcode' => $this->null($doctor->compcode),
                'doctorcode' => $this->null($doctor->doctorcode),
                'doctorname' => $this->null($doctor->doctorname),
                'department' => $this->null($doctor->department),
                'company' => $this->null($doctor->company),
                'address1' => $this->null($doctor->address1),
                'address2' => $this->null($doctor->address2),
                'address3' => $this->null($doctor->address3),
                'postcode' => $this->null($doctor->postcode),
                'statecode' => $this->null($doctor->statecode),
                'countrycode' => $this->null($doctor->countrycode),
                'res_tel' => $this->null($doctor->res_tel),
                'tel_hp' => $this->null($doctor->tel_hp),
                'off_tel' => $this->null($doctor->off_tel),
                'tel_o_ext' => $this->null($doctor->tel_o_ext),
                'specialitycode' => $this->null($doctor->specialitycode),
                'disciplinecode' => $this->null($doctor->disciplinecode),
                'type' => $this->null($doctor->type),
                'doctype' => $this->null($doctor->doctype),
                'statuscode' => $this->null($doctor->statuscode),
                'upddate' => $this->turn_date($this->null($doctor->upddate)),
                'upduser' => $this->null($doctor->upduser),
                'chgcode' => $this->null($doctor->chgcode),
                'creditorcode' => $this->null($doctor->creditorcode),
                'debtorcode' => $this->null($doctor->debtorcode),
                'contraflag' => $this->null($doctor->contraflag),
                'admright' => $this->null($doctor->admright),
                'resigndate' => $this->turn_date($this->null($doctor->resigndate)),
                'recstatus' => $this->null($doctor->recstatus),
                'deptcode' => $this->null($doctor->deptcode),
                'costcode' => $this->null($doctor->costcode),
                'appointment' => $this->null($doctor->appointment),
                'classcode' => $this->null($doctor->classcode),
                'adduser' => $this->null($doctor->adduser),
                'adddate' => $this->turn_date($this->null($doctor->adddate)),
                'deldate' => $this->turn_date($this->null($doctor->deldate)),
                'deluser' => $this->null($doctor->deluser),
                'gstno' => $this->null($doctor->gstno),
                'operationtheatre' => $this->null($doctor->operationtheatre),
                'intervaltime' => $this->null($doctor->intervaltime),
                'computerid' => $this->null($doctor->computerid),
                'ipaddress' => $this->null($doctor->ipaddress),
                'lastcomputerid' => $this->null($doctor->lastcomputerid),
                'lastipaddress' => $this->null($doctor->lastipaddress),
                'loginid' => $this->null($doctor->loginid),
                'mmcid' => $this->null($doctor->mmcid),
                'apcid' => $this->null($doctor->apcid),
                'unit' => $this->null($doctor->unit)
            ]);
    }

    public function doctor_edit($doctor){
        DB::table('hisdb.doctor')
            ->where('compcode','10A')
            ->where('doctorcode',$doctor->doctorcode)
            ->update([
                'compcode' => $this->null($doctor->compcode),
                'doctorcode' => $this->null($doctor->doctorcode),
                'doctorname' => $this->null($doctor->doctorname),
                'department' => $this->null($doctor->department),
                'company' => $this->null($doctor->company),
                'address1' => $this->null($doctor->address1),
                'address2' => $this->null($doctor->address2),
                'address3' => $this->null($doctor->address3),
                'postcode' => $this->null($doctor->postcode),
                'statecode' => $this->null($doctor->statecode),
                'countrycode' => $this->null($doctor->countrycode),
                'res_tel' => $this->null($doctor->res_tel),
                'tel_hp' => $this->null($doctor->tel_hp),
                'off_tel' => $this->null($doctor->off_tel),
                'tel_o_ext' => $this->null($doctor->tel_o_ext),
                'specialitycode' => $this->null($doctor->specialitycode),
                'disciplinecode' => $this->null($doctor->disciplinecode),
                'type' => $this->null($doctor->type),
                'doctype' => $this->null($doctor->doctype),
                'statuscode' => $this->null($doctor->statuscode),
                'upddate' => $this->turn_date($this->null($doctor->upddate)),
                'upduser' => $this->null($doctor->upduser),
                'chgcode' => $this->null($doctor->chgcode),
                'creditorcode' => $this->null($doctor->creditorcode),
                'debtorcode' => $this->null($doctor->debtorcode),
                'contraflag' => $this->null($doctor->contraflag),
                'admright' => $this->null($doctor->admright),
                'resigndate' => $this->turn_date($this->null($doctor->resigndate)),
                'recstatus' => $this->null($doctor->recstatus),
                'deptcode' => $this->null($doctor->deptcode),
                'costcode' => $this->null($doctor->costcode),
                'appointment' => $this->null($doctor->appointment),
                'classcode' => $this->null($doctor->classcode),
                'adduser' => $this->null($doctor->adduser),
                'adddate' => $this->turn_date($this->null($doctor->adddate)),
                'deldate' => $this->turn_date($this->null($doctor->deldate)),
                'deluser' => $this->null($doctor->deluser),
                'gstno' => $this->null($doctor->gstno),
                'operationtheatre' => $this->null($doctor->operationtheatre),
                'intervaltime' => $this->null($doctor->intervaltime),
                'computerid' => $this->null($doctor->computerid),
                'ipaddress' => $this->null($doctor->ipaddress),
                'lastcomputerid' => $this->null($doctor->lastcomputerid),
                'lastipaddress' => $this->null($doctor->lastipaddress),
                'loginid' => $this->null($doctor->loginid),
                'mmcid' => $this->null($doctor->mmcid),
                'apcid' => $this->null($doctor->apcid),
                'unit' => $this->null($doctor->unit)
            ]);
    }

    public function debtor_add($debtor){
        DB::table('debtor.debtormast')
            ->insert([
                'compcode' => $this->null($debtor->compcode), 
                'debtortype' => $this->null($debtor->debtortype), 
                'debtorcode' => $this->null($debtor->debtorcode), 
                'name' => $this->null($debtor->name), 
                'address1' => $this->null($debtor->address1), 
                'address2' => $this->null($debtor->address2), 
                'address3' => $this->null($debtor->address3), 
                'address4' => $this->null($debtor->address4), 
                'postcode' => $this->null($debtor->postcode), 
                'statecode' => $this->null($debtor->statecode), 
                'countrycode' => $this->null($debtor->countrycode), 
                'contact' => $this->null($debtor->contact), 
                'position' => $this->null($debtor->position), 
                'teloffice' => $this->null($debtor->teloffice), 
                'fax' => $this->null($debtor->fax), 
                'email' => $this->null($debtor->email), 
                'payto' => $this->null($debtor->payto), 
                'billtype' => $this->null($debtor->billtype), 
                'billtypeop' => $this->null($debtor->billtypeop), 
                'recstatus' => $this->null($debtor->recstatus), 
                'outamt' => $this->null($debtor->outamt), 
                'depamt' => $this->null($debtor->depamt), 
                'creditlimit' => $this->null($debtor->creditlimit), 
                'actdebccode' => $this->null($debtor->actdebccode), 
                'actdebglacc' => $this->null($debtor->actdebglacc), 
                'depccode' => $this->null($debtor->depccode), 
                'depglacc' => $this->null($debtor->depglacc), 
                'otherccode' => $this->null($debtor->otherccode), 
                'otheracct' => $this->null($debtor->otheracct), 
                'debtorgroup' => $this->null($debtor->debtorgroup), 
                'crgroup' => $this->null($debtor->crgroup), 
                'otheraddr1' => $this->null($debtor->otheraddr1), 
                'otheraddr2' => $this->null($debtor->otheraddr2), 
                'otheraddr3' => $this->null($debtor->otheraddr3), 
                'otheraddr4' => $this->null($debtor->otheraddr4), 
                'accno' => $this->null($debtor->accno), 
                'othertel' => $this->null($debtor->othertel), 
                'requestgl' => $this->null($debtor->requestgl), 
                'creditterm' => $this->null($debtor->creditterm), 
                'adduser' => $this->null($debtor->adduser), 
                'adddate' => $this->turn_date($this->null($debtor->adddate)), 
                'coverageip' => $this->null($debtor->coverageip), 
                'coverageop' => $this->null($debtor->coverageop), 
                'upduser' => $this->null($debtor->upduser), 
                'upddate' => $this->turn_date($this->null($debtor->upddate)), 
                'deluser' => $this->null($debtor->deluser), 
                'deldate' => $this->turn_date($this->null($debtor->deldate)), 
                'computerid' => $this->null($debtor->computerid), 
                'ipaddress' => $this->null($debtor->ipaddress), 
                'lastcomputerid' => $this->null($debtor->lastcomputerid), 
                'lastipaddress' => $this->null($debtor->lastipaddress)
            ]);
    }

    public function debtor_edit($debtor){
        DB::table('debtor.debtormast')
            ->where('compcode','10A')
            ->where('debtorcode',$debtor->debtorcode)
            ->update([
                'compcode' => $this->null($debtor->compcode), 
                'debtortype' => $this->null($debtor->debtortype), 
                'debtorcode' => $this->null($debtor->debtorcode), 
                'name' => $this->null($debtor->name), 
                'address1' => $this->null($debtor->address1), 
                'address2' => $this->null($debtor->address2), 
                'address3' => $this->null($debtor->address3), 
                'address4' => $this->null($debtor->address4), 
                'postcode' => $this->null($debtor->postcode), 
                'statecode' => $this->null($debtor->statecode), 
                'countrycode' => $this->null($debtor->countrycode), 
                'contact' => $this->null($debtor->contact), 
                'position' => $this->null($debtor->position), 
                'teloffice' => $this->null($debtor->teloffice), 
                'fax' => $this->null($debtor->fax), 
                'email' => $this->null($debtor->email), 
                'payto' => $this->null($debtor->payto), 
                'billtype' => $this->null($debtor->billtype), 
                'billtypeop' => $this->null($debtor->billtypeop), 
                'recstatus' => $this->null($debtor->recstatus), 
                'outamt' => $this->null($debtor->outamt), 
                'depamt' => $this->null($debtor->depamt), 
                'creditlimit' => $this->null($debtor->creditlimit), 
                'actdebccode' => $this->null($debtor->actdebccode), 
                'actdebglacc' => $this->null($debtor->actdebglacc), 
                'depccode' => $this->null($debtor->depccode), 
                'depglacc' => $this->null($debtor->depglacc), 
                'otherccode' => $this->null($debtor->otherccode), 
                'otheracct' => $this->null($debtor->otheracct), 
                'debtorgroup' => $this->null($debtor->debtorgroup), 
                'crgroup' => $this->null($debtor->crgroup), 
                'otheraddr1' => $this->null($debtor->otheraddr1), 
                'otheraddr2' => $this->null($debtor->otheraddr2), 
                'otheraddr3' => $this->null($debtor->otheraddr3), 
                'otheraddr4' => $this->null($debtor->otheraddr4), 
                'accno' => $this->null($debtor->accno), 
                'othertel' => $this->null($debtor->othertel), 
                'requestgl' => $this->null($debtor->requestgl), 
                'creditterm' => $this->null($debtor->creditterm), 
                'adduser' => $this->null($debtor->adduser), 
                'adddate' => $this->turn_date($this->null($debtor->adddate)), 
                'coverageip' => $this->null($debtor->coverageip), 
                'coverageop' => $this->null($debtor->coverageop), 
                'upduser' => $this->null($debtor->upduser), 
                'upddate' => $this->turn_date($this->null($debtor->upddate)), 
                'deluser' => $this->null($debtor->deluser), 
                'deldate' => $this->turn_date($this->null($debtor->deldate)), 
                'computerid' => $this->null($debtor->computerid), 
                'ipaddress' => $this->null($debtor->ipaddress), 
                'lastcomputerid' => $this->null($debtor->lastcomputerid), 
                'lastipaddress' => $this->null($debtor->lastipaddress)
            ]);
    }

    public function null($val){
        if(trim($val) == ''){
            return null;
        }else{
            return $val;
        }
    }

    public function null_yes($val){
        if(trim($val) == 'yes'){
            return 1;
        }else{
            return 0;
        }
    }
}