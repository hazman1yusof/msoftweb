<?php

namespace App\Http\Controllers\hisdb;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;
use Response;

class PatEnqController extends defaultController
{   

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request){   
        return view('hisdb.pat_enq.pat_enq');
    }

    public function table(Request $request){
        switch($request->action2){
            case 'getpayercode':
                return $this->getpayercode($request);
        }
        switch($request->action){
            case 'episodelist':
                return $this->episodelist($request);
            case 'loadgl':
                return $this->loadgl($request);
            case 'show_mc':
                return $this->show_mc($request);
            case 'mc_list':
                return $this->mc_list($request);
            case 'mc_last_serialno':
                return $this->mc_last_serialno($request);
            case 'pat_enq_payer':
                return $this->pat_enq_payer($request);
            case 'gletdept':
                return $this->gletdept($request);
            case 'gletitem':
                return $this->gletitem($request);
            case 'addnotes_epno':
                return $this->addnotes_epno($request);
            case 'init_vs_diag':
                return $this->init_vs_diag($request);
            case 'get_serialno':
                return $this->get_serialno($request);
            default:
                return 'error happen..';
        }
    }

    public function form(Request $request){  
        switch($request->action){
            case 'maintable':
                return $this->maintable($request);
            case 'save_mc':
                return $this->save_mc($request);
            case 'save_payer':
                return $this->save_payer($request);
            case 'save_epno_addnotes':
                return $this->save_epno_addnotes($request);
            case 'save_epno_vitstate':
                return $this->save_epno_vitstate($request);
            case 'save_gletdept':
                return $this->save_gletdept($request);
            case 'save_gletitem':
                return $this->save_gletitem($request);
            default:
                return 'error happen..';
        } 
    }

    public function maintable(Request $request){

        // $mrn_range = $this->mrn_range($request);
        $table_patm = DB::table('hisdb.pat_mast')
                        ->select(['pat_mast.idno','pat_mast.CompCode','pat_mast.MRN','pat_mast.Episno','pat_mast.Name','pat_mast.Call_Name','pat_mast.addtype','pat_mast.Address1','pat_mast.Address2','pat_mast.Address3','pat_mast.Postcode','pat_mast.citycode','pat_mast.AreaCode','pat_mast.StateCode','pat_mast.CountryCode','pat_mast.telh','pat_mast.telhp','pat_mast.telo','pat_mast.Tel_O_Ext','pat_mast.ptel','pat_mast.ptel_hp','pat_mast.ID_Type','pat_mast.idnumber','pat_mast.Newic','pat_mast.Oldic','pat_mast.icolor','pat_mast.Sex','pat_mast.DOB','pat_mast.Religion','pat_mast.AllergyCode1','pat_mast.AllergyCode2','pat_mast.Century','pat_mast.Citizencode','pat_mast.OccupCode','pat_mast.Staffid','pat_mast.MaritalCode','pat_mast.LanguageCode','pat_mast.TitleCode','pat_mast.RaceCode','pat_mast.bloodgrp','pat_mast.Accum_chg','pat_mast.Accum_Paid','pat_mast.first_visit_date','pat_mast.last_visit_date','pat_mast.last_episno','pat_mast.PatStatus','pat_mast.Confidential','pat_mast.Active','pat_mast.FirstIpEpisNo','pat_mast.FirstOpEpisNo','pat_mast.AddUser','pat_mast.AddDate','pat_mast.Lastupdate','pat_mast.LastUser','pat_mast.OffAdd1','pat_mast.OffAdd2','pat_mast.OffAdd3','pat_mast.OffPostcode','pat_mast.MRFolder','pat_mast.MRLoc','pat_mast.MRActive','pat_mast.OldMrn','pat_mast.NewMrn','pat_mast.Remarks','pat_mast.RelateCode','pat_mast.ChildNo','pat_mast.CorpComp','pat_mast.Email','pat_mast.Email_official','pat_mast.CurrentEpis','pat_mast.NameSndx','pat_mast.BirthPlace','pat_mast.TngID','pat_mast.PatientImage','pat_mast.pAdd1','pat_mast.pAdd2','pat_mast.pAdd3','pat_mast.pPostCode','pat_mast.DeptCode','pat_mast.DeceasedDate','pat_mast.PatientCat','pat_mast.PatType','pat_mast.PatClass','pat_mast.upduser','pat_mast.upddate','pat_mast.recstatus','pat_mast.loginid','pat_mast.pat_category','pat_mast.idnumber_exp','pat_mast.PatientImage','racecode.Description as raceDesc','religion.Description as religionDesc','occupation.description as occupDesc','citizen.Description as cityDesc','areacode.Description as areaDesc'])
                            ->leftJoin('hisdb.racecode', function($join) use ($request){
                                $join = $join->on('racecode.Code', '=', 'pat_mast.RaceCode')
                                                ->where('racecode.compcode','=',session('compcode'));
                            })
                            ->leftJoin('hisdb.occupation', function($join) use ($request){
                                $join = $join->on('occupation.occupcode', '=', 'pat_mast.OccupCode')
                                                ->where('occupation.compcode','=',session('compcode'));
                            })
                            ->leftJoin('hisdb.religion', function($join) use ($request){
                                $join = $join->on('religion.Code', '=', 'pat_mast.Religion')
                                                ->where('religion.compcode','=',session('compcode'));
                            })
                            ->leftJoin('hisdb.citizen', function($join) use ($request){
                                $join = $join->on('citizen.Code', '=', 'pat_mast.Citizencode')
                                                ->where('citizen.compcode','=',session('compcode'));
                            })
                            ->leftJoin('hisdb.areacode', function($join) use ($request){
                                $join = $join->on('areacode.areacode', '=', 'pat_mast.AreaCode')
                                                ->where('areacode.compcode','=',session('compcode'));
                            });

        if(!empty($request->searchCol)){
            $searchCol_array = $request->searchCol;
            $count = array_count_values($searchCol_array);

            foreach ($count as $key => $value) {
                $occur_ar = $this->index_of_occurance($key,$searchCol_array);

                $table_patm = $table_patm->orWhere(function ($table_patm) use ($request,$searchCol_array,$occur_ar) {
                    foreach ($searchCol_array as $key => $value) {
                        $found = array_search($key,$occur_ar);
                        if($found !== false){
                            $table_patm->Where($searchCol_array[$key],'like',$request->searchVal[$key]);
                        }
                    }
                });
            }
        }

        $table_patm = $table_patm
                    ->where('pat_mast.Active','=','1')
                    ->where('pat_mast.compcode','=',session('compcode'));
                    // ->whereBetween('MRN',$mrn_range);


        if(!empty($request->sort)){
            foreach ($request->sort as $key => $value) {
                $table_patm = $table_patm->orderBy('pat_mast.'.$key, $value);
            }
        }else{
            $table_patm = $table_patm->orderBy('pat_mast.idno', 'DESC');
        }

        $request->page = $request->current;

        //////////paginate/////////
        $paginate = $table_patm->paginate($request->rowCount);

        foreach ($paginate->items() as $key => $value) {
            if($value->PatStatus==1){
                $episode = DB::table('hisdb.episode')
                            ->select(['episode.mrn','doctor.doctorname','episode.epistycode','episode.admdoctor'])
                            ->leftJoin('hisdb.doctor','doctor.doctorcode','=','episode.admdoctor')
                            ->where('episode.mrn','=',$value->MRN)
                            ->where('episode.episno','=',$value->Episno)
                            ->where('episode.compcode','=',session('compcode'));


                if($episode->exists()){
                    $episode = $episode->first();
                    $value->q_epistycode = $episode->epistycode;
                    $value->q_doctorname = $episode->doctorname;
                    $value->admdoctor = $episode->admdoctor;
                }
            }
        }

        $responce = new stdClass();
        $responce->current = $paginate->currentPage();
        $responce->lastPage = $paginate->lastPage();
        $responce->total = $paginate->total();
        $responce->rowCount = $request->rowCount;
        $responce->rows = $paginate->items();
        $responce->sql = $table_patm->toSql();
        $responce->sql_bind = $table_patm->getBindings();

        return json_encode($responce);
    }

    public function episodelist(Request $request){

        // $mrn_range = $this->mrn_range($request);
        $table = DB::table('hisdb.episode as e')
                        ->select('e.idno','e.compcode','e.mrn','e.episno','e.admsrccode','e.epistycode','e.case_code','e.ward','e.bedtype','e.room','e.bed','e.admdoctor','e.attndoctor','e.refdoctor','e.prescribedays','e.pay_type','e.pyrmode','e.climitauthid','e.crnumber','e.depositreq','e.deposit','e.pkgcode','e.billtype','e.remarks','e.episstatus','e.episactive','e.adddate','e.adduser','e.reg_date','e.reg_time','e.dischargedate','e.dischargeuser','e.dischargetime','e.dischargedest','e.allocdoc','e.allocbed','e.allocnok','e.allocpayer','e.allocicd','e.lastupdate','e.lastuser','e.lasttime','e.procedure','e.dischargediag','e.lodgerno','e.regdept','e.diet1','e.diet2','e.diet3','e.diet4','e.diet5','e.glauthid','e.treatment','e.diagcode','e.complain','e.diagfinal','e.clinicalnote','e.conversion','e.newcaseP','e.newcaseNP','e.followupP','e.followupNP','e.bed2','e.bed3','e.bed4','e.bed5','e.bed6','e.bed7','e.bed8','e.bed9','e.bed10','e.diagprov','e.visitcase','e.PkgAutoNo','e.AgreementID','e.AdminFees','e.EDDept','e.dischargestatus','e.procode','e.treatcode','e.payer','e.doctorstatus','e.reff_rehab','e.reff_physio','e.reff_diet','e.stats_rehab','e.stats_physio','e.stats_diet','e.dry_weight','e.duration_hd','e.lastarrivaldate','e.lastarrivaltime','e.lastarrivalno','e.picdoctor','e.nurse_stat','d.doctorname as doctorname','ct.description as case_desc','ad.description as admsrccode_desc','dp.description as regdept_desc','dm.name as payer_desc','btm.description as billtype_desc');

        if(!empty($request->searchCol)){
            if(!empty($request->fixPost)){
                $searchCol_array = $this->fixPost3($request->searchCol);
            }else{
                $searchCol_array = $request->searchCol;
            }

            $count = array_count_values($searchCol_array);
            // dump($count);

            foreach ($count as $key => $value) {
                $occur_ar = $this->index_of_occurance($key,$searchCol_array);

                $table = $table->orWhere(function ($table) use ($request,$searchCol_array,$occur_ar) {
                    foreach ($searchCol_array as $key => $value) {
                        $found = array_search($key,$occur_ar);
                        if($found !== false){
                            $table->Where($searchCol_array[$key],'like',$request->searchVal[$key]);
                        }
                    }
                });
            }
        }

        $table = $table->where('e.mrn','=',$request->mrn)
                        ->where('e.compcode','=',session('compcode'))
                        ->leftJoin('hisdb.doctor as d', function($join) use ($request){
                            $join = $join->on('d.doctorcode', '=', 'e.admdoctor')
                                            ->where('d.compcode','=',session('compcode'));
                        })->leftJoin('sysdb.department as dp', function($join) use ($request){
                            $join = $join->on('dp.deptcode', '=', 'e.regdept')
                                            ->where('dp.compcode','=',session('compcode'));
                        })->leftJoin('hisdb.admissrc as ad', function($join) use ($request){
                            $join = $join->on('ad.admsrccode', '=', 'e.admsrccode')
                                            ->where('ad.compcode','=',session('compcode'));
                        })->leftJoin('debtor.debtormast as dm', function($join) use ($request){
                            $join = $join->on('dm.debtorcode', '=', 'e.payer')
                                            ->where('dm.compcode','=',session('compcode'));
                        })->leftJoin('hisdb.casetype as ct', function($join) use ($request){
                            $join = $join->on('ct.case_code', '=', 'e.case_code')
                                            ->where('ct.compcode','=',session('compcode'));
                        })->leftJoin('hisdb.billtymst as btm', function($join) use ($request){
                            $join = $join->on('btm.billtype', '=', 'e.billtype')
                                            ->where('btm.compcode','=',session('compcode'));
                        });

        if(!empty($request->sort)){
            foreach ($request->sort as $key => $value) {
                $table = $table->orderBy($key, $value);
            }
        }else{
            $table = $table->orderBy('idno', 'DESC');
        }

        //////////paginate/////////
        $paginate = $table->paginate($request->rows);

        $responce = new stdClass();
        $responce->page = $paginate->currentPage();
        $responce->total = $paginate->lastPage();
        $responce->records = $paginate->total();
        $responce->rows = $paginate->items();
        $responce->sql = $table->toSql();
        $responce->sql_bind = $table->getBindings();
        $responce->sql_query = $this->getQueries($table);

        return json_encode($responce);
    }


    public function mrn_range(Request $request){
        if($request->PatClass == 'HIS'){
            $table = DB::table('sysdb.sysparam')
                        ->where('compcode',session('compcode'))
                        ->where('source','=','HIS')
                        ->where('trantype','=','MRN')
                        ->first();

            return explode(',', $table->pvalue2);
        }else if($request->PatClass == 'OTC'){
            $table = DB::table('sysdb.sysparam')
                        ->where('compcode',session('compcode'))
                        ->where('source','=','OTC')
                        ->where('trantype','=','MRN')
                        ->first();

            return explode(',', $table->pvalue2);
        }else{
            dump('wrong patclass, choose only HIS or OTC');
        }
    }


    public function loadgl(Request $request){
        $data = DB::table('hisdb.guarantee AS g')
                    ->select('g.idno','g.compcode','g.debtorcode','g.staffid','g.relatecode','g.childno','g.refno','g.gltype','g.startdate','g.enddate','g.adduser','g.adddate','g.upduser','g.upddate','g.visitno','g.visitbal','g.medcase','g.remark','g.name','g.ourrefno','g.mrn','g.active','g.episno','g.lineno_','g.case','dm.name as debtor_name','r.Description as relate_desc','o.occupcode','o.description as occup_desc')
                    ->leftJoin('debtor.debtormast AS dm', function($join) use ($request){
                        $join = $join->on('dm.debtorcode', '=', 'g.debtorcode')
                                        ->where('dm.compcode','=',session('compcode'));
                    })->leftJoin('hisdb.relationship AS r', function($join) use ($request){
                        $join = $join->on('r.RelationShipCode', '=', 'g.relatecode')
                                        ->where('r.compcode','=',session('compcode'));
                    })->leftJoin('hisdb.pat_mast AS pm', function($join) use ($request){
                        $join = $join->on('pm.MRN', '=', 'g.mrn')
                                        ->where('pm.compcode','=',session('compcode'));
                    })->leftJoin('hisdb.occupation AS o', function($join) use ($request){
                        $join = $join->on('o.occupcode', '=', 'pm.OccupCode')
                                        ->where('o.compcode','=',session('compcode'));
                    })
                    ->where('g.mrn','=',$request->mrn)
                    ->where('g.episno','=',$request->episno)
                    ->where('g.compcode','=',session('compcode'))
                    ->first();

        $responce = new stdClass();
        $responce->data = $data;
        return json_encode($responce);

    }

    public function save_mc(Request $request){

        DB::beginTransaction();
        try {

            $pat_mast = DB::table('hisdb.pat_mast')
                        ->where('compcode',session('compcode'))
                        ->where('mrn',$request->mrn)
                        ->first();

            $idno = DB::table('hisdb.patmc')
                ->insertGetId([  
                    'compcode' => session('compcode'),
                    'datefrom' => $request->datefrom ,
                    'dateto' => $request->dateto ,
                    'mrn' => $request->mrn ,
                    'episno' => $request->episno ,
                    'patfrom' => $pat_mast->Name ,
                    'mccnt' => $request->mccnt ,
                    'serialno' => $request->serialno ,
                    'dateresume' => $request->dateresume ,
                    'datereexam' => $request->datereexam ,
                    'printeddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'printedby' => strtoupper(session('username')),
                    'adduser' => strtoupper(session('username')),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur")
                ]);

            DB::commit();
            $responce = new stdClass();
            $responce->res = 'SUCCESS';
            $responce->idno = $idno;
            return json_encode($responce);

        } catch (\Exception $e) {
            DB::rollback();

            $responce = new stdClass();
            $responce->res = 'ERROR';

            return response(json_encode($responce), 500);
        }
    }

    public function save_epno_addnotes(Request $request){

        DB::beginTransaction();
        try {

            DB::table('hisdb.pathealthadd')
                ->insert([  
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn ,
                    'episno' => $request->episno ,
                    'additionalnote' => $request->additionalnote ,
                    'doctorcode' => $request->doctorcode,
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'addtime' => Carbon::now("Asia/Kuala_Lumpur"),
                    'lastuser' => session('username'),
                    'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'computerid' => session('computerid'),
                ]);

            DB::commit();
            $responce = new stdClass();
            $responce->res = 'SUCCESS';
            return json_encode($responce);

        } catch (\Exception $e) {
            DB::rollback();

            $responce = new stdClass();
            $responce->res = 'ERROR';

            return response(json_encode($responce), 500);
        }
    }

    public function save_epno_vitstate(Request $request){

        DB::beginTransaction();
        try {
            
            $pathealth = DB::table('hisdb.pathealth')
                        ->where('compcode',session('compcode'))
                        ->where('mrn',$request->mrn)
                        ->where('episno',$request->episno);

            if($pathealth->exists()){
                DB::table('hisdb.pathealth')
                    ->where('compcode',session('compcode'))
                    ->where('mrn',$request->mrn)
                    ->where('episno',$request->episno)
                    ->update([  
                        'height' => $request->height,
                        'bp_sys1' => $request->bp_sys1,
                        'bp_dias2' => $request->bp_dias2,
                        'temperature' => $request->temperature,
                        'visionl' => $request->visionl,
                        'weight' => $request->weight,
                        'pulse' => $request->pulse,
                        'respiration' => $request->respiration,
                        'colorblind' => $request->colorblind,
                        'visionr' => $request->visionr,
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'computerid' => session('computerid')
                    ]);
            }else{
                DB::table('hisdb.pathealthadd')
                    ->insert([  
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn ,
                        'episno' => $request->episno ,
                        'height' => $request->height,
                        'bp_sys1' => $request->bp_sys1,
                        'bp_dias2' => $request->bp_dias2,
                        'temperature' => $request->temperature,
                        'visionl' => $request->visionl,
                        'weight' => $request->weight,
                        'pulse' => $request->pulse,
                        'respiration' => $request->respiration,
                        'colorblind' => $request->colorblind,
                        'visionr' => $request->visionr,
                        'adduser' => session('username'),
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'computerid' => session('computerid'),
                    ]);
            }

            DB::commit();
            $responce = new stdClass();
            $responce->res = 'SUCCESS';
            return json_encode($responce);

        } catch (\Exception $e) {
            DB::rollback();

            $responce = new stdClass();
            $responce->res = 'ERROR';

            return response(json_encode($responce), 500);
        }
    }

    public function save_epno_diagnose(Request $request){

        DB::beginTransaction();
        try {

            DB::table('hisdb.episode')
                ->where('compcode',session('compcode'))
                ->where('mrn',$request->mrn)
                ->where('episno',$request->episno)
                ->update([  
                    'diagprov' => $request->diagprov,
                    'diagfinal' => $request->diagfinal,
                    'procedure' => $request->procedure,
                    'lastuser' => session('username'),
                    'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'computerid' => session('computerid')
                ]);

            DB::commit();
            $responce = new stdClass();
            $responce->res = 'SUCCESS';
            return json_encode($responce);

        } catch (\Exception $e) {
            DB::rollback();

            $responce = new stdClass();
            $responce->res = 'ERROR';

            return response(json_encode($responce), 500);
        }
    }

    public function save_payer(Request $request){

        DB::beginTransaction();
        try {

            if($request->oper == 'add'){

                $count = DB::table('hisdb.epispayer')
                            ->where('compcode',session('compcode'))
                            ->where('mrn',$request->mrn)
                            ->where('episno',$request->episno)
                            ->count();

                $lineno = intval($count) + 1;

                $idno = DB::table('hisdb.epispayer')
                        ->insert([  
                            'compcode' => session('compcode'),
                            'mrn' => $request->mrn,
                            'episno' => $request->episno,
                            'payercode' => $request->payercode,
                            'lineno' => $lineno,
                            'epistycode' => $request->epistycode,
                            'pay_type' => $request->pay_type,
                            // 'pyrmode' => $request->,
                            // 'pyrcharge' => $request->,
                            // 'pyrcrdtlmt' => $request->,
                            'pyrlmtamt' => $request->pyrlmtamt,
                            'totbal' => $request->pyrlmtamt,
                            'allgroup' => $request->allgroup,
                            // 'alldept' => $request->,
                            'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'adduser' => session('username'),
                            'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'lastuser' => session('username'),
                            // 'billtype' => $request->,
                            'refno' => $request->refno,
                            'computerid' => session('computerid')
                            // 'chgrate' => $request->
                        ]);

            }else if($request->oper == 'edit'){
                // dd(floatval(preg_replace("/[^-0-9\.]/","",$request->pyrlmtamt)));
                DB::table('hisdb.epispayer')
                        ->where('idno',$request->idno)
                        ->update([  
                            'payercode' => $request->payercode,
                            'pay_type' => $request->pay_type,
                            'pyrlmtamt' => floatval(preg_replace("/[^-0-9\.]/","",$request->pyrlmtamt)),
                            'totbal' => floatval(preg_replace("/[^-0-9\.]/","",$request->pyrlmtamt)),
                            'allgroup' => $request->allgroup,
                            'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'lastuser' => session('username'),
                            'refno' => $request->refno,
                            'computerid' => session('computerid')
                        ]);
            }


            DB::commit();
            $responce = new stdClass();
            $responce->res = 'SUCCESS';
            $responce->idno = $request->idno;
            return json_encode($responce);

        } catch (\Exception $e) {
            // DB::rollback();

            // $responce = new stdClass();
            // $responce->res = 'ERROR';

            // return response(json_encode($responce), 500);
            DB::rollback();
            
            return response($e->getMessage(), 500);
        }
    }

    public function show_mc(Request $request){   
        $patmc = DB::table('hisdb.patmc as mc')
                        ->select('mc.idno','mc.compcode','mc.datefrom','mc.dateto','mc.dateresume','mc.datereexam','mc.mrn','mc.episno','pm.newic','mc.patfrom','mc.mccnt','mc.adduser','mc.adddate','mc.serialno','mc.printeddate','mc.printedby','pm.sex')
                        ->leftJoin('hisdb.pat_mast as pm', function($join) use ($request){
                                $join = $join->on('pm.MRN', '=', 'mc.MRN')
                                                ->where('pm.compcode','=',session('compcode'));
                            })
                        ->where('mc.idno',$request->idno)
                        ->first();

        if(empty($patmc->sex) || $patmc->sex=='M'){
            $sex='he';
        }else{
            $sex='she';
        }

        $ini_array = [
            'compcode' => $patmc->compcode,
            'datefrom' => $patmc->datefrom,
            'dateto' => $patmc->dateto,
            'dateresume' => $patmc->dateresume,
            'datereexam' => $patmc->datereexam,
            'mrn' => $patmc->mrn,
            'episno' => $patmc->episno,
            'newic' => $patmc->newic,
            'patfrom' => $patmc->patfrom,
            'mccnt' => ltrim($patmc->mccnt, '0'),
            'adduser' => $patmc->adduser,
            'adddate' => $patmc->adddate,
            'serialno' => str_pad($patmc->idno, 6, "0", STR_PAD_LEFT),
            'printeddate' => $patmc->printeddate,
            'printedby' => $patmc->printedby,
            'sex' => $sex
        ];

        if(true){
            return view('hisdb.pat_enq.mymc',compact('ini_array'));
        }else{
            abort(403, 'MC not found');
        }

    }

    public function mc_list(Request $request){
        $patmc = DB::table('hisdb.patmc')
                    ->where('compcode',session('compcode'))
                    ->where('mrn',$request->mrn)
                    ->get();

        $responce = new stdClass();
        $responce->data = $patmc;

        return json_encode($responce);
    }

    public function mc_last_serialno(Request $request){
        $patmc = DB::table('hisdb.patmc')
                    ->where('compcode',session('compcode'))
                    ->where('mrn',$request->mrn)
                    ->orderBy('idno','desc')
                    ->first();

        $responce = new stdClass();
        $responce->data = $patmc;

        return json_encode($responce);
    }

    public function addnotes_epno(Request $request){
        $patmc = DB::table('hisdb.pathealthadd as pa')
                    ->select('pa.idno','pa.compcode','pa.mrn','pa.episno','pa.additionalnote','pa.adduser','pa.adddate','pa.addtime','pa.lastuser','pa.lastupdate','pa.doctorcode','pa.computerid','u.name')
                    ->leftJoin('sysdb.users as u', function($join) use ($request){
                        $join = $join->on('u.username', '=', 'pa.adduser')
                                        ->where('u.compcode','=',session('compcode'));
                    })
                    ->where('pa.compcode',session('compcode'))
                    ->where('pa.mrn',$request->mrn)
                    ->where('pa.episno',$request->episno)
                    ->orderBy('pa.idno','desc')
                    ->get();

        $responce = new stdClass();
        $responce->data = $patmc;

        return json_encode($responce);
    }


    public function pat_enq_payer(Request $request){
        $table = DB::table('hisdb.epispayer as ep')
                    ->select('ep.idno','ep.compcode','ep.mrn','ep.episno','pm.Name as name','ep.payercode','ep.lineno','ep.epistycode','ep.pay_type','ep.pyrmode','ep.pyrcharge','ep.pyrcrdtlmt','ep.pyrlmtamt','ep.totbal','ep.allgroup','ep.alldept','ep.adddate','ep.adduser','ep.lastupdate','ep.lastuser','ep.billtype','ep.refno','ep.chgrate','ep.computerid','dm.name as payercode_desc','btm.description as billtype_desc','g.ourrefno')
                    ->leftJoin('debtor.debtormast as dm', function($join) use ($request){
                                $join = $join->on('dm.debtorcode', '=', 'ep.payercode')
                                                ->where('dm.compcode','=',session('compcode'));
                            })
                    ->leftJoin('hisdb.billtymst as btm', function($join) use ($request){
                                $join = $join->on('btm.billtype', '=', 'ep.billtype')
                                                ->where('btm.compcode','=',session('compcode'))
                                                ->where('btm.recstatus','=','ACTIVE');
                            })
                    ->leftJoin('hisdb.pat_mast as pm', function($join) use ($request){
                                $join = $join->on('pm.MRN', '=', 'ep.mrn')
                                                ->where('pm.compcode','=',session('compcode'));
                            })
                    ->leftJoin('hisdb.guarantee AS g', function($join) use ($request){
                                $join = $join->on('g.mrn', '=', 'ep.mrn')
                                                ->on('g.episno','=','ep.episno')
                                                ->where('g.compcode','=',session('compcode'));
                            })
                    ->where('ep.compcode',session('compcode'))
                    ->where('ep.mrn',$request->mrn)
                    ->where('ep.episno',$request->episno)
                    ->orderBy('ep.lineno','asc');

        //////////paginate/////////
        $paginate = $table->paginate($request->rows);

        $responce = new stdClass();
        $responce->page = $paginate->currentPage();
        $responce->total = $paginate->lastPage();
        $responce->records = $paginate->total();
        $responce->rows = $paginate->items();
        $responce->sql = $table->toSql();
        $responce->sql_bind = $table->getBindings();
        $responce->sql_query = $this->getQueries($table);

        return json_encode($responce);
    }

    public function gletdept(Request $request){
        $table = DB::table('hisdb.gletdept as gld')
                    ->select('gld.idno','gld.compcode','gld.payercode','gld.mrn','gld.episno','gld.deptcode','gld.grpcode','gld.deptlimit','gld.deptbal','gld.grplimit','gld.grpbal','gld.inditemlimit','gld.allitem','gld.lastupdate','gld.lastuser','chg.description as grpcode_desc')
                    ->leftJoin('hisdb.chggroup as chg', function($join) use ($request){
                                $join = $join->on('chg.grpcode', '=', 'gld.grpcode')
                                                ->where('chg.compcode','=',session('compcode'));
                            })
                    ->where('gld.compcode',session('compcode'))
                    ->where('gld.mrn',$request->mrn)
                    ->where('gld.episno',$request->episno)
                    ->orderBy('gld.idno', 'desc');

        //////////paginate/////////
        $paginate = $table->paginate($request->rows);

        $responce = new stdClass();
        $responce->page = $paginate->currentPage();
        $responce->total = $paginate->lastPage();
        $responce->records = $paginate->total();
        $responce->rows = $paginate->items();
        $responce->sql = $table->toSql();
        $responce->sql_bind = $table->getBindings();
        $responce->sql_query = $this->getQueries($table);

        return json_encode($responce);

    }

    public function gletitem(Request $request){
        $table = DB::table('hisdb.gletitem as gli')
                    ->select('gli.idno','gli.compcode','gli.payercode','gli.mrn','gli.episno','gli.chgcode','gli.deptcode','gli.grpcode','gli.totitemlimit','gli.totitembal','gli.lastupdate','gli.lastuser','gli.computerid','chgm.description as chgcode_desc')
                    ->join('hisdb.chgmast as chgm', function($join) use ($request){
                                $join = $join->on('chgm.chgcode', '=', 'gli.chgcode')
                                                ->where('chgm.chggroup', '=', $request->grpcode)
                                                ->where('chgm.compcode','=',session('compcode'));
                            })
                    ->where('gli.compcode',session('compcode'))
                    ->where('gli.grpcode',$request->grpcode)
                    ->where('gli.mrn',$request->mrn)
                    ->where('gli.episno',$request->episno)
                    ->orderBy('gli.idno', 'asc');

        //////////paginate/////////
        $paginate = $table->paginate($request->rows);

        $responce = new stdClass();
        $responce->page = $paginate->currentPage();
        $responce->total = $paginate->lastPage();
        $responce->records = $paginate->total();
        $responce->rows = $paginate->items();
        $responce->sql = $table->toSql();
        $responce->sql_bind = $table->getBindings();
        $responce->sql_query = $this->getQueries($table);

        return json_encode($responce);

    }

    public function save_gletdept(Request $request){

        DB::beginTransaction();
        try {
            if($request->oper == 'add'){
                //check sama grpcode
                $grpcode = DB::table('hisdb.gletdept')
                                ->where('compcode',session('compcode'))
                                ->where('mrn',ltrim($request->mrn,'0'))
                                ->where('episno',ltrim($request->episno,'0'))
                                ->where('payercode',$request->payercode)
                                ->where('grpcode',$request->grpcode);

                if($grpcode->exists()){
                    throw new \Exception('Duplicate Group Code!', 500);
                }

                $idno = DB::table('hisdb.gletdept')
                    ->insertGetId([  
                        'payercode' => $request->payercode,
                        'mrn' => ltrim($request->mrn,'0'),
                        'episno' => ltrim($request->episno,'0'),
                        'grpcode' => $request->grpcode,
                        'allitem' => $request->allitem,
                        'grplimit' => $request->grplimit,
                        'grpbal' => $request->grplimit,
                        'inditemlimit' => $request->inditemlimit,
                        'compcode' => session('compcode'),
                        'lastuser' => session('username'),
                        'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'computerid' => session('computerid')
                    ]);
            }else if($request->oper == 'edit'){
                $idno = $request->idno;
                DB::table('hisdb.gletdept')
                    ->where('idno',$request->idno)
                    ->where('compcode',session('compcode'))
                    ->update([  
                        'grpcode' => $request->grpcode,
                        'allitem' => $request->allitem,
                        'grplimit' => $request->grplimit,
                        'grpbal' => $request->grplimit,
                        'inditemlimit' => $request->inditemlimit,
                        'lastuser' => session('username'),
                        'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'computerid' => session('computerid')
                    ]);
            }else if($request->oper == 'del'){
                $idno = 'none';
                DB::table('hisdb.gletdept')
                    ->where('idno',$request->idno)
                    ->where('compcode',session('compcode'))
                    ->delete();
            }


            DB::commit();
            $responce = new stdClass();
            $responce->res = 'SUCCESS';
            $responce->idno = $idno;
            return json_encode($responce);

        } catch (\Exception $e) {
            DB::rollback();

            $responce = new stdClass();
            $responce->res = $e->getMessage();

            return response(json_encode($responce), 500);
        }
    }

    public function save_gletitem(Request $request){

        DB::beginTransaction();
        try {

            if($request->oper == 'add'){
                //check sama chgcode
                $grpcode = DB::table('hisdb.gletitem')
                                ->where('compcode',session('compcode'))
                                ->where('mrn',ltrim($request->mrn,'0'))
                                ->where('episno',ltrim($request->episno,'0'))
                                ->where('payercode',$request->payercode)
                                ->where('grpcode',$request->grpcode)
                                ->where('chgcode',$request->chgcode);

                if($grpcode->exists()){
                    throw new \Exception('Duplicate Charge Code!', 500);
                }

                $idno = DB::table('hisdb.gletitem')
                    ->insertGetId([  
                        'payercode' => $request->payercode,
                        'mrn' => ltrim($request->mrn,'0'),
                        'episno' => ltrim($request->episno,'0'),
                        'chgcode' => $request->chgcode,
                        'grpcode' => $request->grpcode,
                        'totitemlimit' => $request->totitemlimit,
                        'totitembal' => $request->totitemlimit,
                        'compcode' => session('compcode'),
                        'lastuser' => session('username'),
                        'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'computerid' => session('computerid')
                    ]);
            }else if($request->oper == 'edit'){
                $idno = $request->idno;
                DB::table('hisdb.gletitem')
                    ->where('idno',$request->idno)
                    ->where('compcode',session('compcode'))
                    ->update([  
                        'chgcode' => $request->chgcode,
                        'grpcode' => $request->grpcode,
                        'totitemlimit' => $request->totitemlimit,
                        'totitembal' => $request->totitemlimit,
                        'lastuser' => session('username'),
                        'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'computerid' => session('computerid')
                    ]);
            }else if($request->oper == 'del'){
                DB::table('hisdb.gletitem')
                    ->where('idno',$request->idno)
                    ->where('compcode',session('compcode'))
                    ->delete();
            }

            DB::commit();
            $responce = new stdClass();
            $responce->res = 'SUCCESS';
            $responce->idno = $idno;
            return json_encode($responce);

        } catch (\Exception $e) {
            DB::rollback();

            $responce = new stdClass();
            $responce->res = 'ERROR';

            return response(json_encode($responce), 500);
        }
    }


    public function init_vs_diag(Request $request){
        $responce = new stdClass();
        $responce->episode = null;
        $responce->pathealth = null;

        $episode = DB::table('hisdb.episode')
                        ->where('compcode',session('compcode'))
                        ->where('mrn',$request->mrn)
                        ->where('episno',$request->episno);

        $pathealth = DB::table('hisdb.pathealth')
                        ->where('compcode',session('compcode'))
                        ->where('mrn',$request->mrn)
                        ->where('episno',$request->episno);

        if($episode->exists()){
            $responce->episode = $episode->first();
        }

        if($episode->exists()){
            $responce->pathealth = $pathealth->first();
        }

        return json_encode($responce);

    }

    public function get_serialno(Request $request){
        $responce = new stdClass();
        $responce->serialno = '1';

        $patmc = DB::table('hisdb.patmc')
                        ->where('compcode',session('compcode'));

        if($patmc->exists()){
            $responce->serialno = $patmc->max('idno');
        }

        return json_encode($responce);
    }

    public function getpayercode(Request $request){
        if($request->epistycode=='IP'){
            $billtype_fld = 'dm.billtype';
        }else{
            $billtype_fld = 'dm.billtypeop';
        }

        $table = DB::table('debtor.debtormast AS dm')
                        ->select('dm.debtortype','dm.debtorcode','dm.name','dt.description','dt.debtortycode',$billtype_fld.' as billtype','bt.description as billtype_desc');

        $table = $table->leftJoin('debtor.debtortype as dt', function($join) use ($request){
                $join = $join->on('dt.debtortycode', '=', 'dm.debtortype')
                                ->where('dt.compcode','=',session('compcode'))
                                ->where('dt.recstatus','=','ACTIVE');
            })->leftJoin('hisdb.billtymst as bt', function($join) use ($request,$billtype_fld){
                $join = $join->on('bt.billtype', '=', $billtype_fld)
                                ->where('bt.compcode','=',session('compcode'));
            });

        /////////searching/////////
        if(!empty($request->searchCol)){
            if(!empty($request->fixPost)){
                $searchCol_array = $this->fixPost3($request->searchCol);
            }else{
                $searchCol_array = $request->searchCol;
            }

            $count = array_count_values($searchCol_array);
            // dump($count);

            foreach ($count as $key => $value) {
                $occur_ar = $this->index_of_occurance($key,$searchCol_array);

                $table = $table->orWhere(function ($table) use ($request,$searchCol_array,$occur_ar) {
                    foreach ($searchCol_array as $key => $value) {
                        $found = array_search($key,$occur_ar);
                        if($found !== false){
                            $table->Where($searchCol_array[$key],'like',$request->searchVal[$key]);
                        }
                    }
                });
            }
            
        }

        if(!empty($request->searchCol2)){

            $searchCol_array = $request->searchCol2;
            
            $table = $table->where(function($table) use ($searchCol_array, $request){
                foreach ($searchCol_array as $key => $value) {
                    if($key>1) break;
                    $table->orwhere($searchCol_array[$key],'like', $request->searchVal2[$key]);
                }
            });

            if(count($searchCol_array)>2){
                $table = $table->where(function($table) use ($searchCol_array, $request){
                    foreach ($searchCol_array as $key => $value) {
                        if($key<=1) continue;
                        $table->orwhere($searchCol_array[$key],'like', $request->searchVal2[$key]);
                    }
                });
            }
            
        }
        
        $table = $table->where('dm.compcode','=',session('compcode'));

        //////////paginate/////////
        $paginate = $table->paginate($request->rows);

        $responce = new stdClass();
        $responce->page = $paginate->currentPage();
        $responce->total = $paginate->lastPage();
        $responce->records = $paginate->total();
        $responce->rows = $paginate->items();
        $responce->sql = $table->toSql();
        $responce->sql_bind = $table->getBindings();
        $responce->sql_query = $this->getQueries($table);

        return json_encode($responce);
    }
}