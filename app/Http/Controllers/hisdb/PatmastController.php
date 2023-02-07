<?php

namespace App\Http\Controllers\hisdb;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;
use Auth;

class PatmastController extends defaultController
{   

    var $table;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {       
        $user = DB::table('sysdb.users')->where('username','=',session('username'))->where('compcode',session('compcode'))->first();
        $dept = DB::table('sysdb.department')->where('deptcode','=',$user->dept)->where('compcode',session('compcode'))->first();
        $btype = DB::table('sysdb.sysparam')->where('source','=','OP')->where('trantype','=','BILLTYPE')->where('compcode',session('compcode'))->first();


        $btype_ = DB::table('hisdb.billtymst')->where('compcode','=',session('compcode'))->where('billtype','=',$btype->pvalue1)->first();

        return view('hisdb.pat_mgmt.landing',
            [
                'userdeptcode' => $dept->deptcode,
                'userdeptdesc' => $dept->description,
                'billtype_def_code' => $btype_->billtype,
                'billtype_def_desc' => $btype_->description
            ]);
    }

    public function save_patient(Request $request){
        switch ($request->oper) {
            case 'add':
                $this->_add($request);
                break;
            
            case 'edit':
                $this->_edit($request);
                break;
            
            case 'delete':
                $this->_delete($request);
                break;
            
            default:
                # code...
                break;
        }
    }

    public function post_entry(Request $request)
    {   

        $mrn_range = $this->mrn_range($request);

        if($request->curpat == 'true'){

            $request->rows = $request->rowCount;

            $sel_epistycode = $request->epistycode;

            $table_patm = DB::table('hisdb.pat_mast') //ambil dari patmast balik
                            ->select(['pat_mast.idno','pat_mast.CompCode','pat_mast.MRN','pat_mast.Episno','pat_mast.Name','pat_mast.Call_Name','pat_mast.addtype','pat_mast.Address1','pat_mast.Address2','pat_mast.Address3','pat_mast.Postcode','pat_mast.citycode','pat_mast.AreaCode','pat_mast.StateCode','pat_mast.CountryCode','pat_mast.telh','pat_mast.telhp','pat_mast.telo','pat_mast.Tel_O_Ext','pat_mast.ptel','pat_mast.ptel_hp','pat_mast.ID_Type','pat_mast.idnumber','pat_mast.Newic','pat_mast.Oldic','pat_mast.icolor','pat_mast.Sex','pat_mast.DOB','pat_mast.Religion','pat_mast.AllergyCode1','pat_mast.AllergyCode2','pat_mast.Century','pat_mast.Citizencode','pat_mast.OccupCode','pat_mast.Staffid','pat_mast.MaritalCode','pat_mast.LanguageCode','pat_mast.TitleCode','pat_mast.RaceCode','pat_mast.bloodgrp','pat_mast.Accum_chg','pat_mast.Accum_Paid','pat_mast.first_visit_date','pat_mast.last_visit_date','pat_mast.last_episno','pat_mast.PatStatus','pat_mast.Confidential','pat_mast.Active','pat_mast.FirstIpEpisNo','pat_mast.FirstOpEpisNo','pat_mast.AddUser','pat_mast.AddDate','pat_mast.Lastupdate','pat_mast.LastUser','pat_mast.OffAdd1','pat_mast.OffAdd2','pat_mast.OffAdd3','pat_mast.OffPostcode','pat_mast.MRFolder','pat_mast.MRLoc','pat_mast.MRActive','pat_mast.OldMrn','pat_mast.NewMrn','pat_mast.Remarks','pat_mast.RelateCode','pat_mast.ChildNo','pat_mast.CorpComp','pat_mast.Email','pat_mast.Email_official','pat_mast.CurrentEpis','pat_mast.NameSndx','pat_mast.BirthPlace','pat_mast.TngID','pat_mast.PatientImage','pat_mast.pAdd1','pat_mast.pAdd2','pat_mast.pAdd3','pat_mast.pPostCode','pat_mast.DeptCode','pat_mast.DeceasedDate','pat_mast.PatientCat','pat_mast.PatType','pat_mast.PatClass','pat_mast.upduser','pat_mast.upddate','pat_mast.recstatus','pat_mast.loginid','pat_mast.pat_category','pat_mast.idnumber_exp','pat_mast.PatientImage','racecode.Description as raceDesc','religion.Description as religionDesc','occupation.description as occupDesc','citizen.Description as cityDesc','areacode.Description as areaDesc','doctor.doctorname as q_doctorname','queue.epistycode as q_epistycode', 'queue.reg_date', 'queue.QueueNo'])
                            ->join('hisdb.queue', function($join) use ($request,$sel_epistycode){
                                $join = $join->on('queue.mrn', '=', 'pat_mast.MRN')
                                            ->where('queue.billflag','=',0)
                                            ->where('queue.compcode','=',session('compcode'))
                                            ->where('queue.deptcode','=',"ALL");

                                if($sel_epistycode == 'OP'){
                                    $join = $join->whereIn('queue.epistycode', ['OP','OTC']);
                                }else{
                                    $join = $join->whereIn('queue.epistycode', ['IP','DP']);
                                }
                            })
                            ->leftJoin('hisdb.doctor', function($join) use ($request){
                                $join = $join->on('doctor.doctorcode', '=', 'queue.admdoctor')
                                                ->where('doctor.compcode','=',session('compcode'));
                            })
                            ->leftJoin('hisdb.racecode', function($join) use ($request){
                                $join = $join->on('racecode.Code', '=', 'pat_mast.RaceCode')
                                                ->where('racecode.compcode','=',session('compcode'));
                            })
                            ->leftJoin('hisdb.religion', function($join) use ($request){
                                $join = $join->on('religion.Code', '=', 'pat_mast.Religion')
                                                ->where('religion.compcode','=',session('compcode'));
                            })
                            ->leftJoin('hisdb.occupation', function($join) use ($request){
                                $join = $join->on('occupation.occupcode', '=', 'pat_mast.OccupCode')
                                                ->where('occupation.compcode','=',session('compcode'));
                            })
                            ->leftJoin('hisdb.citizen', function($join) use ($request){
                                $join = $join->on('citizen.Code', '=', 'pat_mast.Citizencode')
                                                ->where('citizen.compcode','=',session('compcode'));
                            })
                            ->leftJoin('hisdb.areacode', function($join) use ($request){
                                $join = $join->on('areacode.areacode', '=', 'pat_mast.AreaCode')
                                                ->where('areacode.compcode','=',session('compcode'));
                            })
                            // ->leftJoin('hisdb.doctor','doctor.doctorcode','=','queue.admdoctor')
                            // ->leftJoin('hisdb.racecode','racecode.Code','=','pat_mast.RaceCode')
                            // ->leftJoin('hisdb.religion','religion.Code','=','pat_mast.Religion')
                            // ->leftJoin('hisdb.occupation','occupation.occupcode','=','pat_mast.OccupCode')
                            // ->leftJoin('hisdb.citizen','citizen.Code','=','pat_mast.Citizencode')
                            // ->leftJoin('hisdb.areacode','areacode.areacode','=','pat_mast.AreaCode')
                            ->where('pat_mast.compcode','=',session('compcode'))
                            ->where('pat_mast.Active','=','1')
                            // ->whereIn('pat_mast.mrn', $arr_mrn)
                            ->whereBetween('pat_mast.MRN',$mrn_range);
            // dump($table_patm->get());
            // dd($table_patm->paginate());

            if(!empty($request->searchCol) && $request->searchCol[0]!='doctor'){
                $table_patm = $table_patm->where($request->searchCol[0],'like',$request->searchVal[0]);
            }

           if(!empty($request->sort)){
                foreach ($request->sort as $key => $value) {
                    $table_patm = $table_patm->orderBy($key, $value);
                }
            }else{
                $table_patm = $table_patm->orderBy('queue.idno', 'DESC');
            }

            $paginate_patm = $table_patm->paginate($request->rows);


            foreach ($paginate_patm->items() as $key => $value) {
                // foreach ($paginate->items() as $key2 => $value2) {
                //     if($value->MRN == $value2->mrn){
                //         $value->q_doctorname = $value2->doctorname;
                //         $value->q_epistycode = $value2->epistycode;
                //     }
                // }

                $episode = DB::table('hisdb.episode')
                            ->select('newcaseP','newcaseNP','followupP','followupNP')
                            ->where('mrn','=',$value->MRN)
                            ->where('episno','=',$value->Episno);

                if($episode->exists()){
                    $episode = $episode->first();
                    if($episode->newcaseP == 1 || $episode->followupP == 1){
                        $value->pregnant = 1;
                    }else{
                        $value->pregnant = 0;
                    }

                }


            }

            $responce = new stdClass();
            $responce->current = $paginate_patm->currentPage();
            $responce->lastPage = $paginate_patm->lastPage();
            $responce->total = $paginate_patm->total();
            $responce->rowCount = $request->rowCount;
            $responce->rows = $paginate_patm->items();
            $responce->query = $this->getQueries($table_patm);
            
            return json_encode($responce);

        }else{

            // SELECT COUNT(*) FROM `pat_mast` WHERE idno <= 62863
            // if(!empty($request->lastidno)){
                // $count_ = DB::table('hisdb.pat_mast')
                //             ->where('idno','<=','62863')
                //             ->count();
            // }
            // dd($count_);

            // SELECT * FROM pat_mast WHERE idno = 62863
            // $lastrow = DB::table('hisdb.pat_mast')
            //                 ->where('idno','<=','62863');

            // SELECT * FROM pat_mast LIMIT 10 OFFSET 62814  
            // $lastrow = DB::table('hisdb.pat_mast')
            //                 ->where('idno','<=','62863');

            $table_patm = DB::table('hisdb.pat_mast');
            // dd($table_patm->limit(10)->offset(intval($count_) - 10)->get());

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
                        ->where('Active','=','1')
                        ->where('compcode','=','13A')
                        ->whereBetween('MRN',$mrn_range);


            if(!empty($request->sort)){
                foreach ($request->sort as $key => $value) {
                    $table_patm = $table_patm->orderBy($key, $value);
                }
            }else{
                $table_patm = $table_patm->orderBy('idno', 'DESC');
            }

            $request->page = $request->current;

            //////////paginate/////////
            $paginate = $table_patm->paginate($request->rowCount);

            foreach ($paginate->items() as $key => $value) {
                if($value->PatStatus==1){
                    // $queue = DB::table('hisdb.queue')
                    //             ->select(['queue.mrn','doctor.doctorname','queue.epistycode'])
                    //             ->leftJoin('hisdb.doctor','doctor.doctorcode','=','queue.admdoctor')
                    //             ->where('queue.mrn','=',$value->MRN)
                    //             ->where('queue.episno','=',$value->Episno)
                    //             ->where('queue.deptcode','=',"ALL");
                    $episode = DB::table('hisdb.episode')
                                ->select(['episode.mrn','doctor.doctorname','episode.epistycode'])
                                ->leftJoin('hisdb.doctor','doctor.doctorcode','=','episode.admdoctor')
                                ->where('episode.mrn','=',$value->MRN)
                                ->where('episode.episno','=',$value->Episno);

                    if($episode->exists()){
                        $episode = $episode->first();
                        $value->q_epistycode = $episode->epistycode;
                        $value->q_doctorname = $episode->doctorname;
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
    }

    public function get_entry(Request $request)
    {   
        $responce = new stdClass();

        switch ($request->action) {
            case 'get_pat':
                $data = DB::table('hisdb.pat_mast')
                        ->where('mrn','=',$request->mrn)
                        ->where('compcode','=',session('compcode'))
                        ->first();
                break;

            case 'get_patient_occupation':
                $data = DB::table('hisdb.occupation')
                        ->select('occupcode as code','description')
                        ->where('recstatus','=','ACTIVE')
                        ->where('compcode','=',session('compcode'));

                if(!empty($request->search)){
                    $data = $data->where('description','=',$request->search)->first();
                }else{
                    $data = $data->get();
                }
                        
                break;

            case 'get_patient_title':
                $data = DB::table('hisdb.title')
                        ->select('code','description')
                        ->where('recstatus','=','ACTIVE')
                        ->where('compcode','=',session('compcode'))
                        ->orderBy('idno', 'desc');

                if(!empty($request->search)){
                    $data = $data->where('description','=',$request->search)->first();
                }else{
                    $data = $data->get();
                }

                break;

            case 'get_patient_citizen':
                $data = DB::table('hisdb.citizen')
                        ->select('code','description')
                        ->where('recstatus','=','ACTIVE')
                        ->where('compcode','=',session('compcode'));

                if(!empty($request->search)){
                    $data = $data->where('description','=',$request->search)->first();
                }else{
                    $data = $data->get();
                }

                break;

            case 'get_patient_areacode':
                $data = DB::table('hisdb.areacode')
                        ->select('areacode as code','description')
                        ->where('recstatus','=','ACTIVE')
                        ->where('compcode','=',session('compcode'))
                        ->orderBy('idno', 'desc');

                if(!empty($request->search)){
                    $data = $data->where('description','=',$request->search)->first();
                }else{
                    $data = $data->get();
                }
                
                break;

            case 'get_patient_sex':
                $data = DB::table('hisdb.sex')
                        ->select('code','description')
                        ->where('recstatus','=','ACTIVE')
                        ->where('compcode','=',session('compcode'));

                if(!empty($request->search)){
                    $data = $data->where('description','=',$request->search)->first();
                }else{
                    $data = $data->get();
                }
                
                break;

            case 'get_patient_race':
                $data = DB::table('hisdb.racecode')
                        ->select('code','description')
                        ->where('recstatus','=','ACTIVE')
                        ->where('compcode','=',session('compcode'));

                if(!empty($request->search)){
                    $data = $data->where('description','=',$request->search)->first();
                }else{
                    $data = $data->get();
                }
                
                break;
                
            case 'get_patient_religioncode':
                $data = DB::table('hisdb.religion')
                        ->select('code','description')
                        ->where('recstatus','=','ACTIVE')
                        ->where('compcode','=',session('compcode'));

                if(!empty($request->search)){
                    $data = $data->where('description','=',$request->search)->first();
                }else{
                    $data = $data->get();
                }
                
                break;

            case 'get_patient_urlmarital':
                $data = DB::table('hisdb.marital')
                        ->select('code','description')
                        ->where('recstatus','=','ACTIVE')
                        ->where('compcode','=',session('compcode'));

                if(!empty($request->search)){
                    $data = $data->where('description','=',$request->search)->first();
                }else{
                    $data = $data->get();
                }
                
                break;

            case 'get_patient_language':
                $data = DB::table('hisdb.languagecode')
                        ->select('code','description')
                        ->where('recstatus','=','ACTIVE')
                        ->where('compcode','=',session('compcode'));

                if(!empty($request->search)){
                    $data = $data->where('description','=',$request->search)->first();
                }else{
                    $data = $data->get();
                }
                
                break;

            case 'get_patient_relationship':
                $data = DB::table('hisdb.relationship')
                        ->select('relationshipcode as code','description')
                        ->where('recstatus','=','ACTIVE')
                        ->where('compcode','=',session('compcode'));

                if(!empty($request->search)){
                    $data = $data->where('description','=',$request->search)->first();
                }else{
                    $data = $data->get();
                }
                
                break;

            case 'get_reg_dept':
                $data = DB::table('sysdb.department')
                    ->select('deptcode as code','description')
                    ->where('compcode','=',session('compcode'))
                    ->where('recstatus','=','ACTIVE')
                    ->where('regdept','=','1');

                if(!empty($request->search)){
                    $data = $data->where('description','=',$request->search)
                                ->orwhere('deptcode','=', $request->search)
                                ->first();
                }else{
                    $data = $data->get();
                }
                
                break;

            case 'get_reg_source':
                $data = DB::table('hisdb.admissrc')
                        ->select('admsrccode as code','description')
                        ->where('compcode','=',session('compcode'))
                        ->where('recstatus','=','ACTIVE')
                        ->orderBy('idno', 'desc');

                if(!empty($request->search)){
                    $data = $data->where('description','=',$request->search)->first();
                }else{
                    $data = $data->get();
                }
                
                break;

            case 'get_reg_case':
                $data = DB::table('hisdb.casetype')
                        ->select('case_code as code','description')
                        ->where('compcode','=',session('compcode'))
                        ->where('recstatus','=','ACTIVE');

                if(!empty($request->search)){
                    $data = $data->where('description','=',$request->search)->first();
                }else{
                    $data = $data->get();
                }
                
                break;

            case 'get_reg_fin':
                $data = DB::table('debtor.debtortype')
                        ->select('debtortycode as code','description')
                        ->where('compcode','=',session('compcode'))
                        ->where('recstatus','=','ACTIVE');

                if(!empty($request->search)){
                    $data = $data->where('description','=',$request->search)->first();
                }else{
                    $data = $data->get();
                }
                
                break;

            case 'get_reg_bed':
                $data = DB::table('hisdb.bed')
                        ->select('bednum as code','ward as description')
                        ->where('occup','=',"VACANT")
                        ->where('compcode','=',session('compcode'))
                        ->where('recstatus','=','ACTIVE')
                        ->get();

                foreach ($data as $key => $value) {
                    $value->description = 'BED: '.$value->code.',  WARD: '.$value->description;
                }

                break;

            case 'get_reg_doctor':
                $data = DB::table('hisdb.doctor')
                        ->select('doctorcode as code','doctorname as description')
                        ->where('recstatus','=','ACTIVE')
                        ->where('compcode','=',session('compcode'));

                if(!empty($request->search)){
                    $data = $data->where('description','=',$request->search)->first();
                }else{
                    $data = $data->get();
                }
                
                break;

            case 'get_patient_idtype':
                return  '{"data":[{"sysno":"5","Comp":"","code":"O","description":"Own IC","createdBy":"admin","createdDate":"2013-04-11","LastUpdate":"0000-00-00","LastUser":"","RecStatus":""},{"sysno":"7","Comp":"","code":"F","description":"Father","createdBy":"admin","createdDate":"2013-04-11","LastUpdate":"0000-00-00","LastUser":"","RecStatus":""},{"sysno":"8","Comp":"","code":"M","description":"Mother","createdBy":"","createdDate":"0000-00-00","LastUpdate":"0000-00-00","LastUser":"","RecStatus":""},{"sysno":"9","Comp":"","code":"P","description":"Polis","createdBy":"","createdDate":"0000-00-00","LastUpdate":"0000-00-00","LastUser":"","RecStatus":""},{"sysno":"10","Comp":"","code":"T","description":"Tentera","createdBy":"","createdDate":"0000-00-00","LastUpdate":"0000-00-00","LastUser":"","RecStatus":""}]}';
                break;

            case 'get_debtor_list':
                if($request->type == 1){
                    $data = DB::table('debtor.debtormast AS dm')
                            ->select('dm.debtortype','dm.debtorcode','dm.name','dt.description')
                            ->leftJoin('debtor.debtortype as dt', 'dt.debtortycode', '=', 'dm.debtortype')
                            ->where('dm.compcode','=',session('compcode'))  
                            // ->where('debtorcode','=',ltrim($request->mrn, '0'))
                            ->whereIn('dm.debtortype', ['PR', 'PT'])
                            ->get();
                }else if($request->type == 2){
                    $data = DB::table('debtor.debtormast AS dm')
                            ->select('dm.debtortype','dm.debtorcode','dm.name','dt.description')
                            ->leftJoin('debtor.debtortype AS dt', 'dt.debtortycode', '=', 'dm.debtortype')
                            ->where('dm.compcode','=',session('compcode'))  
                            // ->where('debtorcode','=',ltrim($request->mrn, '0'))
                            ->whereNotIn('dm.debtortype', ['PR', 'PT'])
                            ->get();
                }else{
                    $data = DB::table('debtor.debtormast')
                            ->select('debtorcode','name')
                            ->where('compcode','=',session('compcode'))  
                            ->whereIn('debtortype', ['PR', 'PT'])
                            // ->where('debtorcode','=',ltrim($request->mrn, '0'))
                            ->get();
                }
                break;

            case 'get_billtype_list':
                if($request->type == "OP"){
                    $data = DB::table('hisdb.billtymst')
                            ->select('billtype','description')
                            ->where('compcode','=',session('compcode'))  
                            ->where('opprice','=','1')
                            ->where('recstatus','=','ACTIVE')
                            ->get();
                }else if($request->type == "IP"){
                    $data = DB::table('hisdb.billtymst')
                            ->select('billtype','description')
                            ->where('compcode','=',session('compcode'))  
                            ->where('opprice','=','0')
                            ->where('recstatus','=','ACTIVE')
                            ->get();
                }else{
                    $data = DB::table('hisdb.billtymst')
                            ->select('billtype','description')
                            ->where('compcode','=',session('compcode'))
                            ->where('recstatus','=','ACTIVE')
                            ->get();
                }
                break;

            case 'save_new_episode':
                $this->save_new_episode($request);
                break;

            case 'check_debtormast':
                $data = $this->check_debtormast($request);
                break;

            case 'get_bed_type':
                $data = DB::table('hisdb.bedtype')
                    ->where('compcode','=',session('compcode'))
                    ->where('recstatus','=','ACTIVE')
                    ->get();
                break;

            case 'get_bed_ward':
                $data = DB::table('sysdb.department')
                    ->where('warddept','=','1')
                    ->where('compcode','=',session('compcode'))
                    ->where('recstatus','=','ACTIVE')
                    ->get();
                break;

            case 'get_refno_list':
                $data = DB::table('hisdb.guarantee')
                    ->select('debtorcode','name','gltype','staffid','refno','ourrefno','childno','episno','medcase','mrn','relatecode','remark','startdate','enddate')
                    ->where('debtorcode','=',$request->debtorcode)
                    ->where('mrn','=',$request->mrn)
                    ->get();

                break;

            case 'accomodation_table':
                $data = DB::table('hisdb.bed as b')
                        ->select('b.ward','b.room','b.bednum','b.bedtype','b.occup','bt.description as desc_bt','d.description as desc_d')
                        ->leftJoin('hisdb.bedtype as bt', 'b.bedtype', '=', 'bt.bedtype')
                        ->leftJoin('sysdb.department as d', 'b.ward', '=', 'd.deptcode')
                        ->where('b.occup','=',"VACANT")
                        ->where('b.recstatus','=','ACTIVE')
                        ->where('b.compcode','=',session('compcode'))
                        ->orderBy('b.bedtype', 'desc')
                        ->get();

                break;

            case 'get_all_company':

                $data = DB::table('debtor.debtormast')
                        ->select('debtormast.debtorcode as code','debtormast.name as description')
                        ->leftJoin('debtor.debtortype', 'debtortype.debtortycode', '=', 'debtormast.debtortype')
                        ->where('debtormast.compcode','=',session('compcode'))
                        ->whereNotIn('debtormast.debtortype',['PT','PR']);

                if(!empty($request->search)){
                    $data = $data->where('debtormast.name','=',$request->search)->first();
                }else{
                    $data = $data->get();
                }
                break;

            case 'get_epis_other_data':

                $episode = DB::table('hisdb.episode')
                            ->where('mrn','=',$request->mrn)
                            ->orderBy('episno', 'desc')
                            ->first();

                $epispayer = DB::table('hisdb.epispayer')
                            ->where('mrn','=',$request->mrn)
                            ->orderBy('episno', 'desc')
                            ->first();

                $responce->episode = $episode;
                $responce->epispayer = $epispayer;
                return json_encode($responce);


                break;

            case 'get_billtype_default':

                $data = DB::table('sysdb.sysparam')
                            ->where('compcode','=',session('compcode'))
                            ->where('source','=','PB')
                            ->where('trantype','=','PRICE')
                            ->first();

                break;

            case 'get_default_value':
                $pat = DB::table('hisdb.pat_mast')
                            ->select('Episno')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn',$request->mrn)
                            ->first();

                if($pat->Episno != 0){
                    $data = DB::table('hisdb.episode as e')
                                ->select(
                                    'newcaseP',
                                    'newcaseNP',
                                    'followupP',
                                    'followupP',
                                    'e.admsrccode',
                                    'e.case_code',
                                    'e.admdoctor',
                                    'e.attndoctor',
                                    'e.pay_type',
                                    'e.pyrmode',
                                    'e.payer',
                                    'e.billtype',
                                    'adm.description as adm_desc',
                                    'cas.description as cas_desc',
                                    'dbty.description as dbty_desc',
                                    'dbms.name as dbms_name',
                                    'bmst.description as bmst_desc')
                                ->leftJoin('hisdb.admissrc as adm', 'adm.admsrccode', '=', 'e.admsrccode')
                                ->leftJoin('hisdb.casetype as cas', 'cas.case_code', '=', 'e.case_code')
                                ->leftJoin('debtor.debtortype as dbty', 'dbty.debtortycode', '=', 'e.pay_type')
                                ->leftJoin('debtor.debtormast as dbms', 'dbms.debtorcode', '=', 'e.payer')
                                ->leftJoin('hisdb.billtymst as bmst', 'bmst.billtype', '=', 'e.billtype')
                                ->where('e.compcode','=',session('compcode'))
                                ->where('e.mrn',$request->mrn)
                                ->where('e.episno',$pat->Episno);



                    if(!$data->exists()){
                        $data = 'nothing';
                    }else{
                        $data = $data->first();
                        $admdoctor_desc = DB::table('hisdb.doctor')
                                        ->where('doctorcode',$data->admdoctor);

                        if($admdoctor_desc->exists()){
                            $data->admdoctor_desc = $admdoctor_desc->first()->doctorname;
                        }else{
                            $data->admdoctor_desc = null;
                        }

                        $attndoctor_desc = DB::table('hisdb.doctor')
                                        ->where('doctorcode',$data->attndoctor);

                        if($attndoctor_desc->exists()){
                            $data->attndoctor_desc = $attndoctor_desc->first()->doctorname;
                        }else{
                            $data->attndoctor_desc = null;
                        }
                    }



                }else{
                    $data = 'nothing';
                }

                break;
            
            default:
                $data = 'nothing';
                break;
        }

        $responce->data = $data;
        return json_encode($responce);
    }

    public function _add(Request $request){
        DB::beginTransaction();

        try {

            $table = DB::table('hisdb.pat_mast');

            if(!empty($request->Email_official)){
                $loginid = $request->Email_official;
            }else{
                $loginid = $request->Newic;
            }

            if(!empty($request->PatientImage)){
                $PatientImage = $request->PatientImage;
            }else{
                $PatientImage = null;
            }

            $array_insert = [
                'loginid' => $loginid,
                'compcode' => session('compcode'),
                'adduser' => session('username'),
                'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                'recstatus' => 'A',
                'Active' => 1,
                'PatientImage' => $PatientImage,
            ];

            $request['first_visit_date'] = Carbon::now("Asia/Kuala_Lumpur");
            $request['last_visit_date'] = Carbon::now("Asia/Kuala_Lumpur");

            foreach ($request->field as $key => $value) {
                if(empty($request[$request->field[$key]]))continue;
                // dump($request[$request->field[$key]]);
                $array_insert[$value] = strtoupper($request[$request->field[$key]]);
            }

            $mrn = $this->defaultSysparam('HIS','MRN');
            $array_insert['MRN'] = $mrn;
            $lastidno = $table->insertGetId($array_insert);

            // if(!empty($request->func_after)){
            //     if($request->func_after == 'save_preepis'){
            //         $this->save_preepis($request,$mrn);
            //     }
            // }

            $responce = new stdClass();
            $responce->lastMrn = $mrn;
            $responce->lastidno = $lastidno;
            echo json_encode($responce);

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            report($e);

            return response('Error'.$e, 500);
        }
    }

    public function _edit(Request $request){
        
        DB::beginTransaction();

        $table = DB::table('hisdb.pat_mast');

        $array_update = [
            'compcode' => session('compcode'),
            'upduser' => session('username'),
            'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
            'recstatus' => 'A'
        ];

        $array_ignore = ['mrn','MRN','first_visit_date','last_visit_date','Episno'];
        // dd($request->field);

        foreach ($request->field as $key => $value) {
            if(array_search($value,$array_ignore))continue;
            // dump(empty($request[$request->field[$key]]));
            if(empty($request[$request->field[$key]])){
                $array_update[$value] = null;
            }else{
                $array_update[$value] = strtoupper($request[$request->field[$key]]);
            }
            // dump($request[$request->field[$key]]);
        }

        try {

            //////////where//////////
            $table = $table->where('idno','=',$request->idno);
            $user = $table->first();


            // if($user->loginid != $request->loginid){
            //     if($this->default_duplicate('sysdb.users','username',$request->loginid)>0){
            //         throw new \Exception("Username already exist");
            //     }
            //     $this->makeloginid($request);
            // }

            $table->update($array_update);

            // if(!empty($request->Staffid)){
            //     $this->saving_staffid($request);
            // }

            $bed_mrn = DB::table('hisdb.bed')
                        ->where('mrn','=',$request->MRN)
                        ->update([
                            'name' => strtoupper($request->Name)
                        ]);

            $queue_mrn = DB::table('hisdb.queue')
                        ->where('mrn','=',$request->MRN)
                        ->update([
                            'name' => strtoupper($request->Name)
                        ]);

            $queries = DB::getQueryLog();

            $responce = new stdClass();
            $responce->sql = $queries;
            $responce->lastMrn = $request->MRN;
            $responce->lastidno = $request->idno;
            echo json_encode($responce);

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }

    }

    public function _delete(Request $request){
        DB::beginTransaction();

        $table = DB::table('hisdb.pat_mast');

        try {

            $table = $table->where('idno','=',$request->idno);
            $table->update([
                'deluser' => session('username'),
                'deldate' => Carbon::now("Asia/Kuala_Lumpur"),
                'recstatus' => 'D',
                'Active' => 0,
            ]);

            $responce = new stdClass();
            $responce->sql = $table->toSql();
            $responce->sql_bind = $table->getBindings();
            echo json_encode($responce);

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            
            return response('Error'.$e, 500);
        }
    }

    public function makeloginid(Request $request){
        DB::beginTransaction();

        $table = DB::table('sysdb.users');

        $array_insert = [
            'compcode' => session('compcode'),
            'adduser' => session('username'),
            'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
            'recstatus' => 'A',
            'username' => $request->loginid,
            'password' => $request->loginid,
            'groupid' => 'patient'
        ];


        try {

            $table->insert($array_insert);

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            report($e);

            return response('Error'.$e, 500);
        }
    }

    public function save_episode(Request $request){
        switch ($request->episoper) {
            case 'add':
                $this->add_episode($request);
                break;
            
            case 'edit':
                $this->edit_episode($request);
                break;
            
            default:
                # code...
                break;
        }
    }

    public function add_episode(Request $request){

        DB::enableQueryLog();

        $epis_mrn = $request->epis_mrn;
        $epis_no = $request->epis_no;
        $epis_type = $request->epis_type;
        $epis_maturity = $request->epis_maturity;
        $epis_dept = $request->epis_dept;
        $epis_src = $request->epis_src;
        $epis_case = $request->epis_case;
        $epis_doctor = $request->epis_doctor;
        $epis_fin = $request->epis_fin;
        $epis_paymode = $request->epis_pay;
        $epis_payer = $request->epis_payer;
        $epis_billtype = $request->epis_billtype;
        $epis_refno = $request->epis_refno;
        $epis_ourrefno = $request->epis_ourrefno;
        $epis_preg = $request->epis_preg;
        $epis_fee = $request->epis_fee;
        $epis_bednum = $request->epis_bed;
        $epis_apptidno = $request->apptidno;
        $epis_preepisidno = $request->preepisidno;

        $epis_typeepis;
        if ($epis_maturity == "1"){
            if($epis_preg == "Pregnant"){
                $epis_typeepis = "newcaseP";
            }else{
                $epis_typeepis = "newcaseNP";
            }
        }else{
            if($epis_preg == "Pregnant"){
                $epis_typeepis = "followupP";
            }else{
                $epis_typeepis = "followupNP";
            }
        }


        DB::beginTransaction();

        try {

            DB::table("hisdb.episode")
                ->insert([
                    "compcode" => session('compcode'),
                    "mrn" => $epis_mrn,
                    "episno" => $epis_no,
                    "epistycode" => $epis_type,
                    "reg_date" => Carbon::now("Asia/Kuala_Lumpur"),
                    "reg_time" => Carbon::now("Asia/Kuala_Lumpur"),
                    "regdept" => $epis_dept,
                    "admsrccode" => $epis_src,
                    "case_code" => $epis_case,
                    "admdoctor" => $epis_doctor,
                    "pay_type" => $epis_fin,
                    "pyrmode" => $epis_paymode,
                    "billtype" => $epis_billtype,
                    "bed" => $epis_bednum,
                    "payer" => $epis_payer,
                    $epis_typeepis => 1,
                    "AdminFees" => $epis_fee,
                    "adddate" => Carbon::now("Asia/Kuala_Lumpur"),
                    "adduser" => session('username'),
                    "episactive" => 1,
                    "allocpayer" => 1,
                    'episstatus' => 'CURRENT',
                ]);

            //update patmast
                //episno = episode.episno
                //patstatus=episode.episactive
                //last_visit_date=episode.reg_date
                //first_visit_date=episode.reg_date when episno=1
                //Lastupdate=today
                //LastUser=session.user

            DB::table("hisdb.pat_mast")
                ->where("compcode",'=',session('compcode'))
                ->where("mrn",'=',$epis_mrn)
                ->update([
                    'episno' => $epis_no,
                    'patstatus' => 1,
                    'last_visit_date' => Carbon::now("Asia/Kuala_Lumpur"),
                    'Lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'LastUser' => session('username'),
                ]);

            if($epis_no == 1){
                DB::table("hisdb.pat_mast")
                    ->where("compcode",'=',session('compcode'))
                    ->where("mrn",'=',$epis_mrn)
                    ->update([
                        'first_visit_date' => Carbon::now("Asia/Kuala_Lumpur"),
                    ]);
            }

            $patmast_data = DB::table("hisdb.pat_mast")
                                ->where('MRN','=',$epis_mrn)
                                ->where('compcode','=',session('compcode'))
                                ->first();

            //if pay_type = PT
                //buat debtormaster KALAU TAK JUMPA
                    //debtortype = pay_type
                    //debtorcode = MRN prefix until 7 zero
                    //debtorname = patmast.name
                    //address1 address2 address3 address4
                    //actdebccode //select dari debtortype where compcode=session and debtortycode=pay_type
                    //actdebglacc //select dari debtortype where compcode=session and debtortycode=pay_type
                    //depccode //select dari debtortype where compcode=session and debtortycode=pay_type
                    //depglacc //select dari debtortype where compcode=session and debtortycode=pay_type
                    //adduser
                    //adddate
                    //computerid

            if($epis_fin == "PT"){
                $debtortype_data = DB::table('debtor.debtortype')
                    ->where('compcode','=',session('compcode'))
                    ->where('DebtorTyCode','=',$epis_fin)
                    ->first();

                $debtormast_obj = DB::table('debtor.debtormast')
                    ->where('compcode','=',session('compcode'))
                    ->where('debtorcode','=',$epis_mrn);


                if(!$debtormast_obj->exists()){
                    //kalu xjumpa debtormast, buat baru
                    DB::table('debtor.debtormast')
                        ->insert([
                            'CompCode' => session('compcode'),
                            'DebtorCode' => $epis_mrn,
                            'Name' => $patmast_data->Name,
                            'Address1' => $patmast_data->Address1,
                            'Address2' => $patmast_data->Address2,
                            'Address3' => $patmast_data->Address3,
                            'DebtorType' => "PR",
                            'DepCCode'  => $debtortype_data->depccode,
                            'DepGlAcc' => $debtortype_data->depglacc,
                            'BillType' => $epis_type,
                            // 'BillTypeOP' => "OP",
                            'ActDebCCode' => $debtortype_data->actdebccode,
                            'ActDebGlAcc' => $debtortype_data->actdebglacc,
                            'upduser' => session('username'),
                            'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'RecStatus' => "A"
                        ]);
                }else{

                    // $debtormast_data = $debtormast_obj->first();

                }
            }

            //CREATE EPISPAYER
                // mrn
                // episno
                // payercode
                // lineno = 1
                // epistycode
                // pay_type
                // pyrmode
                // billtype
                // adduser
                // adddate
                // computerid

            $epispayer_obj = DB::table('hisdb.epispayer')
                ->where('compcode','=',session('compcode'))
                ->where('mrn','=',$epis_mrn)
                ->where('Episno','=',$epis_no);

            if(!$epispayer_obj->exists()){
                //kalu xjumpa epispayer, buat baru
                DB::table('hisdb.epispayer')
                ->insert([
                    'CompCode' => session('compcode'),
                    'MRN' => $epis_mrn,
                    'Episno' => $epis_no,
                    'EpisTyCode' => $epis_type,
                    'LineNo' => '1',
                    'BillType' => $epis_billtype,
                    'PayerCode' => $epis_payer,
                    'Pay_Type' => $epis_fin,
                    'AddDate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'AddUser' => session('username'),
                    'Lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'LastUser' => session('username')
                ]);
            }

            //CREATE NOK

            //CREATE docalloc
                  //compcode = episode.compcode
                  //mrn = episode.mrn
                  //episno = episode.episno
                  //AllocNo
                  //DoctorCode = episode.admdoctor
                  //asdate = episode.epis_date
                  //astime = episode.epis_time
                  //aedate
                  //aetime
                  //aprovide
                  //astatus
                  //areason
                  //servicecode
                  //doctype = doctor.doctype
                  //epistycode = episode.epistycode
                  //adddate
                  //adduser
                  // computerid
            $docalloc_obj=DB::table('hisdb.docalloc')
                ->where('compcode','=',session('compcode'))
                ->where('Mrn','=',$epis_mrn)
                ->where('Episno','=',$epis_no);

            if(!$docalloc_obj->exists()){
                //kalu xde docalloc buat baru
                DB::table('hisdb.docalloc')
                    ->insert([
                        'mrn' => $epis_mrn,
                        'compcode' => session('compcode'),
                        'episno' => $epis_no,
                        'AllocNo' => 1,
                        'AStatus' => "ADMITTING",
                        'Adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'AddUser' => session('username'),
                        'Epistycode' => $epis_type,
                        'DoctorCode' => $epis_doctor,
                        'Lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'LastUser' => session('username'),
                        'ASDate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'ASTime' => Carbon::now("Asia/Kuala_Lumpur")->toDateTimeString()
                    ]);
            }

            //CREATE BEDALLOC KALAU IP @ DP SHJ
                // from page

            //UPDATE BED set recstatus=OCCUPIED KALAU IP @ DP SHJ
                    //mrn = episode.mrn
                    //episno = episode.episno
                    //name = patmast.name
            if($epis_type == "IP" || $epis_type == "DP"){

                $bed_obj = DB::table('hisdb.bed')
                        ->where('compcode','=',session('compcode'))
                        ->where('bednum','=',$epis_bednum);

                if($bed_obj->exists()){
                    $bed_first = $bed_obj->first();
                    DB::table('hisdb.bedalloc')
                        ->insert([  
                            'mrn' => $epis_mrn,
                            'episno' => $epis_no,
                            'name' => $patmast_data->Name,
                            'astatus' => "Occupied",
                            'ward' =>  $bed_first->ward,
                            'room' =>  $bed_first->room,
                            'bednum' =>  $bed_first->bednum,
                            'asdate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'astime' => Carbon::now("Asia/Kuala_Lumpur"),
                            'compcode' => session('compcode'),
                            'adduser' => strtoupper(session('username')),
                            'adddate' => Carbon::now("Asia/Kuala_Lumpur")
                        ]);

                    $bed_obj->update([
                        'occup' => "OCCUPIED",
                        'mrn' => $epis_mrn,
                        'episno' => $epis_no,
                        'name' => $patmast_data->Name,
                        'admdoctor' => $epis_doctor
                    ]);

                }

            }


            //QUEUE FOR ALL
                //epistycode = IP if epis_type  = IP @ DP
                //epistycode = OP if epis_type  = OP @ OTC

            if($epis_type == "IP" || $epis_type == "DP"){
                $epistycode_q = "IP";
            }else{
                $epistycode_q = "OP";
            }

            //cari queue utk source que dgn trantype epistycode
            $queue_obj = DB::table('sysdb.sysparam')
                ->where('compcode','=',session('compcode'))
                ->where('source','=','QUE')
                ->where('trantype','=',$epistycode_q);

                //kalu xjumpe buat baru
            if(!$queue_obj->exists()){
                DB::table('sysdb.sysparam')
                    ->insert([
                        'compcode' => session('compcode'),
                        'source' => 'QUE',
                        'trantype' => $epistycode_q,
                        'description' => $epistycode_q.' Queue No.',
                        'pvalue2' => Carbon::now("Asia/Kuala_Lumpur")->toDateString()
                    ]);

                $queue_obj = DB::table('sysdb.sysparam')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','=','QUE')
                    ->where('trantype','=',$epistycode_q);
            }

            $queue_data = $queue_obj->first();

                //ni start kosong balik bila hari baru
            if($queue_data->pvalue2 != Carbon::now("Asia/Kuala_Lumpur")->toDateString()){
                $queue_obj
                    ->update([
                        'pvalue1' => 1,
                        'pvalue2' => Carbon::now("Asia/Kuala_Lumpur")->toDateString()
                    ]);
            }

                //tambah satu dkt queue sysparam
            $current_pvalue1 = intval($queue_data->pvalue1);
            $queue_obj
                ->update([
                    'pvalue1' => $current_pvalue1+1
                ]);


            $queueAll_obj=DB::table('hisdb.queue')
                ->where('mrn','=',$epis_mrn)
                ->where('episno','=',$epis_no)
                ->where('deptcode','=','ALL');

            if(!$queueAll_obj->exists()){
                DB::table('hisdb.queue')
                    ->insert([
                        'AdmDoctor' => $epis_doctor,
                        'AttnDoctor' => $epis_doctor,
                        'BedType' => '',
                        'Case_Code' => "MED",
                        'CompCode' => session('compcode'),
                        'Episno' => $epis_no,
                        'EpisTyCode' => $epistycode_q,
                        'LastTime' => Carbon::now("Asia/Kuala_Lumpur")->toTimeString(),
                        'Lastupdate' => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'Lastuser' => session('username'),
                        'MRN' => $epis_mrn,
                        'Reg_Date' => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'Reg_Time' => Carbon::now("Asia/Kuala_Lumpur")->toDateTimeString(),
                        'Bed' => '',
                        'Room' => '',
                        'QueueNo' => $current_pvalue1,
                        'Deptcode' => 'ALL',
                        'DOB' => $this->null_date($patmast_data->DOB),
                        'NAME' => $patmast_data->Name,
                        'Newic' => $patmast_data->Newic,
                        'Oldic' => $patmast_data->Oldic,
                        'Sex' => $patmast_data->Sex,
                        'Religion' => $patmast_data->Religion,
                        'RaceCode' => $patmast_data->RaceCode,
                        'EpisStatus' => '',
                        'chggroup' => $epis_billtype
                    ]);
            }

            //QUEUE FOR SPECIALIST

            $queueSPEC_obj=DB::table('hisdb.queue')
                ->where('mrn','=',$epis_mrn)
                ->where('episno','=',$epis_no)
                ->where('deptcode','=','SPEC');

            if(!$queueSPEC_obj->exists()){
                DB::table('hisdb.queue')
                    ->insert([
                        'AdmDoctor' => $epis_doctor,
                        'AttnDoctor' => $epis_doctor,
                        'BedType' => '',
                        'Case_Code' => "MED",
                        'CompCode' => session('compcode'),
                        'Episno' => $epis_no,
                        'EpisTyCode' => "OP",
                        'LastTime' => Carbon::now("Asia/Kuala_Lumpur")->toTimeString(),
                        'Lastupdate' => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'Lastuser' => session('username'),
                        'MRN' => $epis_mrn,
                        'Reg_Date' => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'Reg_Time' => Carbon::now("Asia/Kuala_Lumpur")->toDateTimeString(),
                        'Bed' => '',
                        'Room' => '',
                        'QueueNo' => $current_pvalue1,
                        'Deptcode' => 'SPEC',
                        'DOB' => $this->null_date($patmast_data->DOB),
                        'NAME' => $patmast_data->Name,
                        'Newic' => $patmast_data->Newic,
                        'Oldic' => $patmast_data->Oldic,
                        'Sex' => $patmast_data->Sex,
                        'Religion' => $patmast_data->Religion,
                        'RaceCode' => $patmast_data->RaceCode,
                        'EpisStatus' => '',
                        'chggroup' => $epis_billtype
                    ]);
            }

            // if(!empty($epis_apptidno)){
            //     DB::table('hisdb.pre_episode')
            //             ->where('apptidno','=',$epis_apptidno)
            //             ->update([
            //                 'episno' => $epis_no
            //             ]);
            // }

            if(!empty($epis_preepisidno)){
                DB::table('hisdb.pre_episode')
                        ->where('idno','=',$epis_preepisidno)
                        ->update([
                            'episno' => $epis_no,
                            'episactive' => 1,
                        ]);
            }

            $queries = DB::getQueryLog();

            // dump($queries);

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }
        
    }

    public function edit_episode(Request $request){

        DB::enableQueryLog();

        $epis_mrn = $request->epis_mrn;
        $epis_no = $request->epis_no;
        $epis_type = $request->epis_type;
        $epis_maturity = $request->epis_maturity;
        $epis_dept = $request->epis_dept;
        $epis_src = $request->epis_src;
        $epis_case = $request->epis_case;
        $epis_doctor = $request->epis_doctor;
        $epis_fin = $request->epis_fin;
        $epis_paymode = $request->epis_pay;
        $epis_payer = $request->epis_payer;
        $epis_billtype = $request->epis_billtype;
        $epis_refno = $request->epis_refno;
        $epis_ourrefno = $request->epis_ourrefno;
        $epis_preg = $request->epis_preg;
        $epis_fee = $request->epis_fee;
        $epis_bednum = $request->epis_bed;

        $epis_typeepis;
        if ($epis_maturity == "1"){
            if($epis_preg == "Pregnant"){
                $epis_typeepis = "newcaseP";
            }else{
                $epis_typeepis = "newcaseNP";
            }
        }else{
            if($epis_preg == "Pregnant"){
                $epis_typeepis = "followupP";
            }else{
                $epis_typeepis = "followupNP";
            }
        }


        DB::beginTransaction();

        try {

            DB::table("hisdb.episode")
                ->where("mrn",'=',$epis_mrn)
                ->where("episno",'=',$epis_no)
                ->update([
                    "compcode" => session('compcode'),
                    "regdept" => $epis_dept,
                    "admsrccode" => $epis_src,
                    "case_code" => $epis_case,
                    "admdoctor" => $epis_doctor,
                    "pay_type" => $epis_fin,
                    "pyrmode" => $epis_paymode,
                    "billtype" => $epis_billtype,
                    "bed" => $epis_bednum,
                    $epis_typeepis => 1,
                    "payer" => $epis_payer,
                    "AdminFees" => $epis_fee,
                    "adddate" => Carbon::now("Asia/Kuala_Lumpur"),
                    "adduser" => session('username'),
                    "episactive" => 1,
                    "allocpayer" => 1
                ]);

            //update patmast
                //episno = episode.episno
                //patstatus=episode.episactive
                //last_visit_date=episode.reg_date
                //first_visit_date=episode.reg_date when episno=1
                //Lastupdate=today
                //LastUser=session.user

            if($epis_no == 1){
                DB::table("hisdb.pat_mast")
                    ->where("compcode",'=',session('compcode'))
                    ->where("mrn",'=',$epis_mrn)
                    ->update([
                        'first_visit_date' => Carbon::now("Asia/Kuala_Lumpur"),
                    ]);
            }

            $patmast_data = DB::table("hisdb.pat_mast")
                                ->where('MRN','=',$epis_mrn)
                                ->where('compcode','=',session('compcode'))
                                ->first();

            //if pay_type = PT
                //buat debtormaster KALAU TAK JUMPA
                    //debtortype = pay_type
                    //debtorcode = MRN prefix until 7 zero
                    //debtorname = patmast.name
                    //address1 address2 address3 address4
                    //actdebccode //select dari debtortype where compcode=session and debtortycode=pay_type
                    //actdebglacc //select dari debtortype where compcode=session and debtortycode=pay_type
                    //depccode //select dari debtortype where compcode=session and debtortycode=pay_type
                    //depglacc //select dari debtortype where compcode=session and debtortycode=pay_type
                    //adduser
                    //adddate
                    //computerid

            if($epis_fin == "PT"){
                $debtortype_data = DB::table('debtor.debtortype')
                    ->where('compcode','=',session('compcode'))
                    ->where('DebtorTyCode','=',$epis_fin)
                    ->first();

                $debtormast_obj = DB::table('debtor.debtormast')
                    ->where('compcode','=',session('compcode'))
                    ->where('debtorcode','=',$epis_mrn);


                if(!$debtormast_obj->exists()){
                    //kalu xjumpa debtormast, buat baru
                    DB::table('debtor.debtormast')
                        ->insert([
                            'CompCode' => session('compcode'),
                            'DebtorCode' => $epis_mrn,
                            'Name' => $patmast_data->Name,
                            'Address1' => $patmast_data->Address1,
                            'Address2' => $patmast_data->Address2,
                            'Address3' => $patmast_data->Address3,
                            'DebtorType' => "PR",
                            'DepCCode'  => $debtortype_data->depccode,
                            'DepGlAcc' => $debtortype_data->depglacc,
                            'BillType' => "IP",
                            'BillTypeOP' => "OP",
                            'ActDebCCode' => $debtortype_data->actdebccode,
                            'ActDebGlAcc' => $debtortype_data->actdebglacc,
                            'upduser' => session('username'),
                            'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'RecStatus' => "A"
                        ]);
                }else{

                    // $debtormast_data = $debtormast_obj->first();

                }
            }

            //CREATE EPISPAYER
                // mrn
                // episno
                // payercode
                // lineno = 1
                // epistycode
                // pay_type
                // pyrmode
                // billtype
                // adduser
                // adddate
                // computerid

            $epispayer_obj = DB::table('hisdb.epispayer')
                ->where('compcode','=',session('compcode'))
                ->where('mrn','=',$epis_mrn)
                ->where('Episno','=',$epis_no);

            if(!$epispayer_obj->exists()){
                //kalu xjumpa epispayer, buat baru
                DB::table('hisdb.epispayer')
                    ->insert([
                        'CompCode' => session('compcode'),
                        'MRN' => $epis_mrn,
                        'Episno' => $epis_no,
                        'refno' => $epis_ourrefno,
                        'EpisTyCode' => "OP",
                        'LineNo' => '1',
                        'BillType' => $epis_billtype,
                        'PayerCode' => $epis_payer,
                        'Pay_Type' => $epis_fin,
                        'AddDate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'AddUser' => session('username'),
                        'Lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'LastUser' => session('username')
                    ]);
            }else{
                DB::table('hisdb.epispayer')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$epis_mrn)
                    ->where('Episno','=',$epis_no)
                    ->update([
                        'refno' => $epis_ourrefno,
                        'BillType' => $epis_billtype,
                        'PayerCode' => $epis_payer,
                        'Pay_Type' => $epis_fin,
                        'Lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'LastUser' => session('username')
                    ]);
            }

            //CREATE NOK

            //CREATE docalloc
                  //compcode = episode.compcode
                  //mrn = episode.mrn
                  //episno = episode.episno
                  //AllocNo
                  //DoctorCode = episode.admdoctor
                  //asdate = episode.epis_date
                  //astime = episode.epis_time
                  //aedate
                  //aetime
                  //aprovide
                  //astatus
                  //areason
                  //servicecode
                  //doctype = doctor.doctype
                  //epistycode = episode.epistycode
                  //adddate
                  //adduser
                  // computerid
            $docalloc_obj=DB::table('hisdb.docalloc')
                ->where('compcode','=',session('compcode'))
                ->where('Mrn','=',$epis_mrn)
                ->where('Episno','=',$epis_no);

            if(!$docalloc_obj->exists()){
                //kalu xde docalloc buat baru
                // $maxallocno = $docalloc_obj->max('AllocNo');

                DB::table('hisdb.docalloc')
                    ->insert([
                        'mrn' => $epis_mrn,
                        'compcode' => session('compcode'),
                        'episno' => $epis_no,
                        'AllocNo' => 1,
                        'AStatus' => "ADMITTING",
                        'Adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'AddUser' => session('username'),
                        'Epistycode' => $epis_type,
                        'DoctorCode' => $epis_doctor,
                        'Lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'LastUser' => session('username'),
                        'ASDate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'ASTime' => Carbon::now("Asia/Kuala_Lumpur")->toDateTimeString()
                    ]);

            }else{
                $docalloc_obj
                    ->delete();

                DB::table('hisdb.docalloc')
                    ->insert([
                        'mrn' => $epis_mrn,
                        'compcode' => session('compcode'),
                        'episno' => $epis_no,
                        'AllocNo' => 1,
                        'AStatus' => "ADMITTING",
                        'Adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'AddUser' => session('username'),
                        'Epistycode' => $epis_type,
                        'DoctorCode' => $epis_doctor,
                        'Lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'LastUser' => session('username'),
                        'ASDate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'ASTime' => Carbon::now("Asia/Kuala_Lumpur")->toDateTimeString()
                    ]);
            }

            //CREATE BEDALLOC KALAU IP @ DP SHJ
                // from page

            //UPDATE BED set recstatus=OCCUPIED KALAU IP @ DP SHJ
                    //mrn = episode.mrn
                    //episno = episode.episno
                    //name = patmast.name
            if($epis_type == "IP" || $epis_type == "DP"){

                $bedalloc_oldidno=DB::table('hisdb.bedalloc')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$epis_mrn)
                    ->where('episno','=',$epis_no);

                if($bedalloc_oldidno->exists()){
                    $bedalloc_old = DB::table('hisdb.bedalloc')
                        ->where('idno','=',$bedalloc_oldidno->max('idno'))
                        ->first();

                    $bed_old = DB::table('hisdb.bed')
                        ->where('compcode','=',session('compcode'))
                        ->where('bednum','=',$bedalloc_old->bednum)
                        ->update([
                            'occup' => "VACANT"
                        ]);

                }

                $bed_obj = DB::table('hisdb.bed')
                    ->where('compcode','=',session('compcode'))
                    ->where('bednum','=',$epis_bednum);

                if($bed_obj->exists()){
                    $bed_first = $bed_obj->first();
                    DB::table('hisdb.bedalloc')
                        ->insert([  
                            'mrn' => $epis_mrn,
                            'episno' => $epis_no,
                            'name' => $patmast_data->Name,
                            'astatus' => "OCCUPIED",
                            'ward' =>  $bed_obj->ward,
                            'room' =>  $bed_obj->room,
                            'bednum' =>  $bed_obj->bednum,
                            'asdate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'astime' => Carbon::now("Asia/Kuala_Lumpur"),
                            'compcode' => session('compcode'),
                            'adduser' => strtoupper(session('username')),
                            'adddate' => Carbon::now("Asia/Kuala_Lumpur")
                        ]);

                    $bed_obj->update([
                        'occup' => "OCCUPIED",
                        'mrn' => $epis_mrn,
                        'episno' => $epis_no,
                        'name' => $patmast_data->Name,
                        'admdoctor' => $epis_doctor
                    ]);

                    DB::table("hisdb.episode")
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno)
                        ->update([
                            'bed' => $request->bed_bednum
                        ]);

                }

            }


            //QUEUE FOR ALL
                //epistycode = IP if epis_type  = IP @ DP
                //epistycode = OP if epis_type  = OP @ OTC

            // if($epis_type == "IP" || $epis_type == "DP"){
            //     $epistycode_q = "IP";
            // }else{
            //     $epistycode_q = "OP";
            // }

            // $queueAll_obj=DB::table('hisdb.queue')
            //     ->where('mrn','=',$epis_mrn)
            //     ->where('episno','=',$epis_no)
            //     ->where('deptcode','=','ALL');

            // if(!$queueAll_obj->exists()){
            //     DB::table('hisdb.queue')
            //         ->insert([
            //             'AdmDoctor' => $epis_doctor,
            //             'AttnDoctor' => $epis_doctor,
            //             'BedType' => '',
            //             'Case_Code' => "MED",
            //             'CompCode' => session('compcode'),
            //             'Episno' => $epis_no,
            //             'EpisTyCode' => $epistycode_q,
            //             'LastTime' => Carbon::now("Asia/Kuala_Lumpur")->toTimeString(),
            //             'Lastupdate' => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
            //             'Lastuser' => session('username'),
            //             'MRN' => $epis_mrn,
            //             'Reg_Date' => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
            //             'Reg_Time' => Carbon::now("Asia/Kuala_Lumpur")->toDateTimeString(),
            //             'Bed' => '',
            //             'Room' => '',
            //             'QueueNo' => $current_pvalue1+1,
            //             'Deptcode' => 'ALL',
            //             'DOB' => $this->null_date($patmast_data->DOB),
            //             'NAME' => $patmast_data->Name,
            //             'Newic' => $patmast_data->Newic,
            //             'Oldic' => $patmast_data->Oldic,
            //             'Sex' => $patmast_data->Sex,
            //             'Religion' => $patmast_data->Religion,
            //             'RaceCode' => $patmast_data->RaceCode,
            //             'EpisStatus' => '',
            //             'chggroup' => $epis_billtype
            //         ]);
            // }else{
            //     $queueAll_obj
            //         ->update([
            //             'AdmDoctor' => $epis_doctor,
            //             'AttnDoctor' => $epis_doctor,
            //             'chggroup' => $epis_billtype
            //         ]);
            // }

            // //QUEUE FOR SPECIALIST

            // $queueSPEC_obj=DB::table('hisdb.queue')
            //     ->where('mrn','=',$epis_mrn)
            //     ->where('episno','=',$epis_no)
            //     ->where('deptcode','=','SPEC');

            // if(!$queueSPEC_obj->exists()){
            //     DB::table('hisdb.queue')
            //         ->insert([
            //             'AdmDoctor' => $epis_doctor,
            //             'AttnDoctor' => $epis_doctor,
            //             'BedType' => '',
            //             'Case_Code' => "MED",
            //             'CompCode' => session('compcode'),
            //             'Episno' => $epis_no,
            //             'EpisTyCode' => $epistycode_q,
            //             'LastTime' => Carbon::now("Asia/Kuala_Lumpur")->toTimeString(),
            //             'Lastupdate' => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
            //             'Lastuser' => session('username'),
            //             'MRN' => $epis_mrn,
            //             'Reg_Date' => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
            //             'Reg_Time' => Carbon::now("Asia/Kuala_Lumpur")->toDateTimeString(),
            //             'Bed' => '',
            //             'Room' => '',
            //             'QueueNo' => $current_pvalue1+1,
            //             'Deptcode' => 'SPEC',
            //             'DOB' => $this->null_date($patmast_data->DOB),
            //             'NAME' => $patmast_data->Name,
            //             'Newic' => $patmast_data->Newic,
            //             'Oldic' => $patmast_data->Oldic,
            //             'Sex' => $patmast_data->Sex,
            //             'Religion' => $patmast_data->Religion,
            //             'RaceCode' => $patmast_data->RaceCode,
            //             'EpisStatus' => '',
            //             'chggroup' => $epis_billtype
            //         ]);
            // }else{
            //     $queueSPEC_obj
            //         ->update([
            //             'AdmDoctor' => $epis_doctor,
            //             'AttnDoctor' => $epis_doctor,
            //             'chggroup' => $epis_billtype
            //         ]);
            // }

            $queries = DB::getQueryLog();

            dump($queries);

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }
        
    }

    public function check_debtormast(Request $request){
        DB::beginTransaction();

        $debtormast =  DB::table('debtor.debtormast')
                    ->where('debtorcode','=',$request->mrn_trailzero)
                    ->where('compcode','=',session('compcode'));

        if($debtormast->exists()){
            return $debtormast->first();
        }else{

            try {

                $pat_mast = DB::table('hisdb.pat_mast')
                                ->where('mrn','=',$request->mrn)
                                ->first();

                $debtortype = DB::table("debtor.debtortype")
                    ->select('actdebccode', 'actdebglacc', 'depccode', 'depglacc')
                    ->where('compcode','=',session('compcode'))
                    ->where('debtortycode', '=', 'PT')
                    ->first();

                DB::table('debtor.debtormast')
                    ->insert([
                        'compcode'    =>  session('compcode'),
                        'debtortype'  =>  "PT",    
                        'debtorcode'  =>  $request->mrn_trailzero,
                        'name'        =>  $pat_mast->Name, 
                        'address1'    =>  $pat_mast->Address1,
                        'address2'    =>  $pat_mast->Address2,
                        'address3'    =>  $pat_mast->Address3,
                        'postcode'    =>  $pat_mast->Postcode,
                        'billtype'    =>  "IP",
                        'billtypeop'  =>  "OP",
                        'actdebccode' =>  $debtortype->actdebccode,
                        'actdebglacc' =>  $debtortype->actdebglacc,
                        'depccode'    =>  $debtortype->depccode,
                        'depglacc'    =>  $debtortype->depglacc
                    ]);

                $debtormast =  DB::table('debtor.debtormast')
                    ->where('debtorcode','=',$request->mrn_trailzero)
                    ->where('compcode','=',session('compcode'))
                    ->first();

                DB::commit();

                return $debtormast;

            } catch (Exception $e) {
                DB::rollback();

                return response('Error'.$e, 500);
            }

        }
    }

    public function check_last_episode(Request $request){

    }

    public function save_adm(Request $request){

        DB::beginTransaction();

        try {


                // $codeexist = DB::table('hisdb.admissrc')
                //     ->where('admsrccode','=',$request->adm_code);

                // if($codeexist->exists()){
                //     throw new \Exception('Admsrccode already exists', 500);
                // }


                DB::table('hisdb.admissrc')
                    ->insert([
                        'compcode'    =>  session('compcode'),
                        'description'  =>  strtoupper($request->adm_desc),
                        'addr1'        =>  $request->adm_addr1, 
                        'addr2'    =>  $request->adm_addr2,
                        'addr3'    =>  $request->adm_addr3,
                        'addr4'    =>  $request->adm_addr4,
                        'telno'    =>  $request->adm_telno,
                        'email'    =>  $request->adm_email,
                        'type'     =>  $request->adm_type,
                        'lastuser'     => session('username'),
                        'lastupdate'     =>  Carbon::now("Asia/Kuala_Lumpur")
                    ]);

                DB::commit();

            } catch (\Exception $e) {
                DB::rollback();

                return response($e->getMessage(), 500);
            }

        

    }

    public function save_bed(Request $request){

        DB::beginTransaction();

        try {
            $bedalloc_oldidno =  DB::table('hisdb.bedalloc')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$request->mrn)
                    ->where('episno','=',$request->episno);

            if($bedalloc_oldidno->exists()){
                $bedalloc_old = DB::table('hisdb.bedalloc')
                    ->where('idno','=',$bedalloc_oldidno->max('idno'))
                    ->first();

                $bed_old = DB::table('hisdb.bed')
                                ->where('compcode','=',session('compcode'))
                                ->where('bednum','=',$bedalloc_old->bednum)
                                ->update([
                                    'occup' => "VACANT"
                                ]);

            }

            $bed_obj = DB::table('hisdb.bed')
                    ->where('compcode','=',session('compcode'))
                    ->where('bednum','=',$request->bed_bednum);

            if($bed_obj->exists()){
                $bed_first = $bed_obj->first();
                DB::table('hisdb.bedalloc')
                    ->insert([  
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'name' => $request->name,
                        'astatus' => $request->bed_status,
                        'ward' =>  $request->bed_ward,
                        'room' =>  $request->bed_room,
                        'bednum' =>  $request->bed_bednum,
                        'isolate' =>  $request->bed_isolate,
                        'lodgerno' =>  $request->bed_lodger,
                        'asdate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'astime' => Carbon::now("Asia/Kuala_Lumpur"),
                        'compcode' => session('compcode'),
                        'adduser' => strtoupper(session('username')),
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur")
                    ]);

                $bed_obj->update([
                    'occup' => "OCCUPIED"
                ]);

                DB::table("hisdb.episode")
                    ->where('mrn','=',$request->mrn)
                    ->where('episno','=',$request->episno)
                    ->update([
                        'bed' => $request->bed_bednum
                    ]);

            }


            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }

        

    }

    public function save_doc(Request $request){

        DB::beginTransaction();

        try {

            if($request->oper == 'add'){
                $docalloc_obj = DB::table('hisdb.docalloc')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno);

                if($docalloc_obj->exists()){
                    $allocno = intval($docalloc_obj->max('AllocNo')) + 1;
                }else{
                    $allocno = 1;
                }

                DB::table('hisdb.docalloc')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn'    =>  $request->mrn,
                        'episno'  =>  $request->episno,
                        'AllocNo' =>   $allocno,
                        'doctorcode'  =>  $request->doctorcode,
                        'asdate'        =>  Carbon::now("Asia/Kuala_Lumpur"), 
                        'astime'    =>  Carbon::now("Asia/Kuala_Lumpur"),
                        'astatus'    =>  $request->status,
                        'epistycode'    =>  $request->epistycode,
                        'adddate'    =>  Carbon::now("Asia/Kuala_Lumpur"),
                        'adduser'    =>  session('username'),
                        'lastupdate'    =>  Carbon::now("Asia/Kuala_Lumpur"),
                        'lastuser'    =>  session('username')
                    ]);
            }else if($request->oper == 'edit'){
                $docalloc_obj = DB::table('hisdb.docalloc')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno)
                        ->where('allocno','=',$request->allocno);

                if($docalloc_obj->exists()){
                    $docalloc_obj->update([
                        'doctorcode'  =>  $request->doctorcode,
                        'astatus'    =>  $request->status,
                        'asdate'        =>  Carbon::now("Asia/Kuala_Lumpur"), 
                        'astime'    =>  Carbon::now("Asia/Kuala_Lumpur"),
                        'lastupdate'    =>  Carbon::now("Asia/Kuala_Lumpur"),
                        'lastuser'    =>  session('username')
                    ]);
                }
            }else{
                throw new \Exception("Error happen");
            }
                

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function save_gl(Request $request){

        DB::beginTransaction();

        try {

            $debtormast = DB::table('debtor.debtormast')
                ->where('compcode','=', session('compcode'))
                ->where('debtorcode','=',$request['hid_newgl_corpcomp'])
                ->first();

            $sysparam = DB::table('sysdb.sysparam')
                ->where('source','=','GL')
                ->where('trantype','=',$debtormast->debtortype);

            if($sysparam->exists()){
                $sysparam_get = $sysparam->first();
                $pvalue1 = $sysparam_get->pvalue1;

                $ourrefno = $debtormast->debtortype .intval($pvalue1+1);

                $sysparam->update([
                    'pvalue1' => intval($pvalue1+1)
                ]);
            }else{
                DB::table('sysdb.sysparam')->insert([
                    'compcode' => session('compcode'),
                    'pvalue1' => 1,
                    'source' => 'GL',
                    'trantype' => $debtormast->debtortype,
                ]);
                $ourrefno = $debtormast->debtortype . strval(1);
            }

             DB::table('hisdb.guarantee')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn'    =>  $request->mrn,
                    'episno' =>   $request->episno,
                    'ourrefno' =>   $ourrefno,
                    'refno' =>   $request['newgl-refno'],
                    'debtorcode'  =>  $request['hid_newgl_corpcomp'],
                    'name'  =>  $request['txt_newgl_corpcomp'],
                    'staffid' =>   $request['newgl-staffid'],
                    'childno' =>   $request['newgl-childno'],
                    'relatecode' =>   $request['hid_newgl_relatecode'],
                    'gltype' =>   $request['newgl-gltype'],
                    'startdate' =>   $request['newgl-effdate'],
                    'enddate' =>   $request['newgl-expdate'],
                    'remark' =>   $request['newgl-remark'],
                    'medcase' =>   $request['newgl-case'],
                    'active' =>   'ACTIVE',
                    'adduser' => session('username'), 
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                ]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function save_nok(Request $request){

        DB::beginTransaction();

        try {

            if($request->oper == 'add'){
                DB::table('hisdb.nok_ec')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn'    =>  $request->mrn,
                        'episno'  =>  $request->episno,
                        'name'  =>  $request->name,
                        'relationshipcode' =>  $request->relationshipcode, 
                        'address1'    =>  $request->address1,
                        'address2'    =>  $request->address2,
                        'address3'    =>  $request->address3,
                        'postcode'    =>  $request->postcode,
                        'tel_h'    =>   $request->tel_h,
                        'tel_hp'    =>   $request->tel_hp,
                        'tel_o'    =>  $request->tel_o,
                        'tel_o_ext'    =>  $request->tel_o_ext
                    ]);

            }else if($request->oper == 'edit'){
                $nok_ec_obj = DB::table('hisdb.nok_ec')
                        ->where('idno','=',$request->idno);

                if($nok_ec_obj->exists()){
                    $nok_ec_obj
                        ->update([
                            'name'  =>  $request->name,
                            'relationshipcode' =>  $request->relationshipcode, 
                            'address1'    =>  $request->address1,
                            'address2'    =>  $request->address2,
                            'address3'    =>  $request->address3,
                            'postcode'    =>  $request->postcode,
                            'tel_h'    =>   $request->tel_h,
                            'tel_hp'    =>   $request->tel_hp,
                            'tel_o'    =>  $request->tel_o,
                            'tel_o_ext'    =>  $request->tel_o_ext
                        ]);
                }

            }else{
                throw new \Exception("Error happen");

            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function save_emr(Request $request){

        DB::beginTransaction();

        try {

            if($request->oper == 'add'){
                DB::table('hisdb.pat_emergency')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn'    =>  $request->mrn,
                        'name'  =>  $request->name,
                        'relationship' =>  $request->relationshipcode, 
                        'telh'    =>   $request->tel_h,
                        'telhp'    =>   $request->tel_hp,
                        'email'    =>  $request->email
                    ]);

            }else if($request->oper == 'edit'){
                $pat_emergency_obj = DB::table('hisdb.pat_emergency')
                        ->where('idno','=',$request->idno);

                if($pat_emergency_obj->exists()){
                    $pat_emergency_obj
                        ->update([
                            'compcode' => session('compcode'),
                            'mrn'    =>  $request->mrn,
                            'name'  =>  $request->name,
                            'relationship' =>  $request->relationshipcode, 
                            'telh'    =>   $request->tel_h,
                            'telhp'    =>   $request->tel_hp,
                            'email'    =>  $request->email
                        ]);
                }

            }else{
                throw new \Exception("Error happen");

            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function get_episode_by_mrn(Request $request){

        $episode = DB::table('hisdb.episode as e')
                                ->select(
                                    'reg_date',
                                    'reg_time',
                                    'episno',
                                    'epistycode',
                                    'e.bed',
                                    'newcaseP',
                                    'newcaseNP',
                                    'followupP',
                                    'followupP',
                                    'e.regdept',
                                    'e.admsrccode',
                                    'e.case_code',
                                    'e.admdoctor',
                                    'e.pay_type',
                                    'e.pyrmode',
                                    'e.payer',
                                    'e.billtype',
                                    'dpmt.description as reg_desc',
                                    'adm.description as adm_desc',
                                    'cas.description as cas_desc',
                                    'doc.doctorname as doc_desc',
                                    'dbty.description as dbty_desc',
                                    'dbms.name as dbms_name',
                                    'bmst.description as bmst_desc')
                                ->leftJoin('sysdb.department as dpmt', 'dpmt.deptcode', '=', 'e.regdept')
                                ->leftJoin('hisdb.admissrc as adm', 'adm.admsrccode', '=', 'e.admsrccode')
                                ->leftJoin('hisdb.casetype as cas', 'cas.case_code', '=', 'e.case_code')
                                ->leftJoin('hisdb.doctor as doc', 'doc.doctorcode', '=', 'e.admdoctor')
                                ->leftJoin('debtor.debtortype as dbty', 'dbty.debtortycode', '=', 'e.pay_type')
                                ->leftJoin('debtor.debtormast as dbms', 'dbms.debtorcode', '=', 'e.payer')
                                ->leftJoin('hisdb.billtymst as bmst', 'bmst.billtype', '=', 'e.billtype')
                                ->where('e.compcode','=',session('compcode'))
                                ->where('e.mrn',$request->mrn)
                                ->where('e.episno',$request->episno)
                                ->first();

        $epispayer = DB::table('hisdb.epispayer')
                ->where('compcode','=',session('compcode'))
                ->where('mrn','=',$request->mrn)
                ->where('Episno','=',$request->episno)
                ->first();

        $txt_epis_refno = "";
        $txt_epis_our_refno = "";
        if(!empty($epispayer->refno)){
            $guarantee = DB::table('hisdb.guarantee')
                ->where('compcode','=',session('compcode'))
                ->where('mrn','=',$request->mrn)
                ->where('episno','=',$request->episno)
                ->where('ourrefno','=',$epispayer->refno);

            if($guarantee->exists()){
                $guarantee = $guarantee->first();
                $txt_epis_refno = $guarantee->refno;
                $txt_epis_our_refno = $guarantee->ourrefno;
            }
        }

        $debtormast = DB::table('debtor.debtormast')
                ->where('compcode','=',session('compcode'))
                ->where('debtorcode','=',$epispayer->payercode)
                // ->where('debtortype','=',$epispayer->pay_type)
                ->first();

        $bed = DB::table('hisdb.bed')
                ->where('compcode','=',session('compcode'))
                ->where('bednum','=',$episode->bed)
                ->first();

        $responce = new stdClass();
        $responce->episode = $episode;
        $responce->epispayer = $epispayer;
        $responce->debtormast = $debtormast;
        $responce->txt_epis_refno = $txt_epis_refno;
        $responce->txt_epis_our_refno = $txt_epis_our_refno;
        $responce->bed = $bed;

        return json_encode($responce);
    }

    public function new_occup_form(Request $request){

        DB::beginTransaction();

        try {

            $idno = DB::table('hisdb.occupation')->max('idno');


            DB::table('hisdb.occupation')
                ->insert([
                    'compcode' => session('compcode'),
                    'occupcode' => intval($idno) + 1,
                    'description' => $request->occup_desc,
                    'recstatus' => 'A',
                    'adduser' => session('username'), 
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                ]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function new_title_form(Request $request){

        DB::beginTransaction();

        try {
            
            if($this->default_duplicate('hisdb.title','Code',$request->title_code)>0){
                throw new \Exception("Username already exist");
            }

            DB::table('hisdb.title')
                ->insert([
                    'compcode' => session('compcode'),
                    'Code' => $request->title_code,
                    'description' => $request->title_desc,
                    'recstatus' => 'ACTIVE',
                    'adduser' => session('username'), 
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                ]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function new_areacode_form(Request $request){

        DB::beginTransaction();

        try {

            // if($this->default_duplicate('hisdb.areacode','areacode',$request->title_code)>0){
            //     throw new \Exception("Areacode already exist");
            // }

            $idno = DB::table('hisdb.areacode')->max('idno');

            DB::table('hisdb.areacode')
                ->insert([
                    'compcode' => session('compcode'),
                    'areacode' => intval($idno) + 1,
                    'description' => $request->areacode_desc,
                    'recstatus' => 'ACTIVE',
                    'adduser' => session('username'), 
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                ]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function new_relationship_form(Request $request){

        DB::beginTransaction();

        try {

            // if($this->default_duplicate('hisdb.relationship','RelationShipCode',$request->title_code)>0){
            //     throw new \Exception("Areacode already exist");
            // }

            $idno = DB::table('hisdb.relationship')->max('idno');


            DB::table('hisdb.relationship')
                ->insert([
                    'compcode' => session('compcode'),
                    'RelationShipCode' => intval($idno) + 1,
                    'description' => $request->relationship_desc,
                    'recstatus' => 'ACTIVE',
                    'adduser' => session('username'), 
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                ]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function mrn_range(Request $request){
        if($request->PatClass == 'HIS'){
            $table = DB::table('sysdb.sysparam')
                        ->where('source','=','HIS')
                        ->where('trantype','=','MRN')
                        ->first();

            return explode(',', $table->pvalue2);
        }else if($request->PatClass == 'OTC'){
            $table = DB::table('sysdb.sysparam')
                        ->where('source','=','OTC')
                        ->where('trantype','=','MRN')
                        ->first();

            return explode(',', $table->pvalue2);
        }else{
            dump('wrong patclass, choose only HIS or OTC');
        }
    }


    public function get_preepis_data(Request $request){

        $preepisode = DB::table('hisdb.pre_episode')
                    ->where('idno','=',$request->idno)
                    ->first();

        $table = array();
        $table[0] = [
            'MRN' => $preepisode->mrn,
            'Name' => $preepisode->Name,
            'Newic' => (empty($preepisode->Newic))?'':$preepisode->Newic,
            'telhp' => (empty($preepisode->telhp))?'':$preepisode->telhp,
            'telh' => (empty($preepisode->telno))?'':$preepisode->telno
        ];
        
        $responce = new stdClass();
        $responce->rows = $table;

        return json_encode($responce);

    }

    public function save_preepis(Request $request,$mrn){
        $preepisode = DB::table('hisdb.pre_episode')
                        ->where('apptidno','=',$request->apptidno);

        if($preepisode->exists()){
            $preepisode->update([
                'mrn' => $mrn
            ]);
        }

        $apptbook = DB::table('hisdb.apptbook')
                        ->where('idno','=',$request->apptidno);

        if($apptbook->exists()){
            $apptbook->update([
                'mrn' => $mrn
            ]);
        }

    }

    public function saving_staffid(Request $request){

        $corpstaff = DB::table('hisdb.corpstaff')
            ->where('staffid','=',$request->Staffid)
            ->where('debtorcode','=',$request->CorpComp);

        if($corpstaff->exists()){
            $corpstaff
                ->update([
                    'debtorcode' => strtoupper($request->CorpComp),

                    'debtorcode' => strtoupper($request->CorpComp)
                ]);
        }

    }

    public function auto_save(Request $request){
        DB::beginTransaction();

        try {

            if($this->default_duplicate( ///check duplicate
                $request->table_name,
                $request->code_name,
                $request[$request->code_name]
            )){
                return response('Already Exists', 500);
            };


            DB::table($request->table_name)
                ->insert([
                    'compcode'          =>  session('compcode'),
                    $request->code_name =>  $request[$request->code_name],
                    $request->desc_name =>  $request[$request->desc_name],
                    'recstatus'         =>  'ACTIVE',
                    'adduser'           =>  session('username'),
                    'adddate'           =>  Carbon::now("Asia/Kuala_Lumpur")
                ]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }


}