<?php

namespace App\Http\Controllers\hisdb;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class PatmastController extends defaultController
{   

    var $table;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {       
        $user = DB::table('sysdb.users')->where('username','=',session('username'))->first();
        $dept = DB::table('sysdb.department')->where('deptcode','=',$user->deptcode)->first();
        return view('hisdb.pat_mgmt.landing',
            [
                'userdeptcode' => $dept->deptcode,
                'userdeptdesc' => $dept->description
            ]);
    }

    public function save_patient(Request $request){
        DB::connection()->enableQueryLog();
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
            $table = DB::table('hisdb.queue')
                        ->select(['queue.mrn','doctor.doctorname','queue.epistycode'])
                        ->leftJoin('hisdb.doctor','doctor.doctorcode','=','queue.admdoctor')
                        ->where('queue.compcode','=',session('compcode'))
                        ->where('queue.deptcode','=',"ALL");

            if($sel_epistycode == 'OP'){
                $table->whereIn('queue.epistycode', ['OP','OTC']);
            }else{
                $table->whereIn('queue.epistycode', ['IP','DP']);
            }

            $paginate = $table->paginate($request->rows);
            $arr_mrn = [];

            foreach ($paginate->items() as $key => $obj) {
                array_push($arr_mrn, $obj->mrn);
            }

            $table_patm = DB::table('hisdb.pat_mast') //ambil dari patmast balik
                            ->where('compcode','=',session('compcode'))
                            ->where('Active','=','1')
                            ->whereBetween('MRN',$mrn_range)
                            ->whereIn('mrn', $arr_mrn);

            if(!empty($request->searchCol)){
                $table_patm = $table_patm->where($request->searchCol[0],'like',$request->searchVal[0]);
            }

            $paginate_patm = $table_patm->paginate($request->rows);


            foreach ($paginate_patm->items() as $key => $value) {
                $value->q_doctorname = $paginate->items()[$key]->doctorname;
                $value->q_epistycode = $paginate->items()[$key]->epistycode;
            }

            $responce = new stdClass();
            $responce->current = $paginate->currentPage();
            $responce->lastPage = $paginate->lastPage();
            $responce->total = $paginate->total();
            $responce->rowCount = $request->rowCount;
            $responce->rows = $paginate_patm->items();
            $responce->sql = $table->toSql();
            $responce->sql_bind = $table->getBindings();
            
            return json_encode($responce);

        }else{

            $table_patm = DB::table('hisdb.pat_mast'); //ambil dari patmast balik
                            // ->where('compcode','=',session('compcode'));

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
                        ->where('compcode','=',session('compcode'))
                        ->whereBetween('MRN',$mrn_range);

            if(!empty($request->sort)){
                foreach ($request->sort as $key => $value) {
                    $table_patm = $table_patm->orderBy($key, $value);
                }
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
                    $data = $data->where('description','=',$request->search)->first();
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
                        ->whereNotIn('debtortype.debtortycode',['PT','PR']);

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
            
            default:
                $data = 'nothing';
                break;
        }

        $responce->data = $data;
        return json_encode($responce);
    }

    public function _add(Request $request){
        DB::beginTransaction();

        $table = DB::table('hisdb.pat_mast');

        if(!empty($request->Email_official)){
            $loginid = $request->Email_official;
        }else{
            $loginid = $request->Newic;
        }

        $array_insert = [
            'loginid' => $loginid,
            'compcode' => session('compcode'),
            'adduser' => session('username'),
            'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
            'recstatus' => 'A'
        ];

        $request['first_visit_date'] = Carbon::now("Asia/Kuala_Lumpur");
        $request['last_visit_date'] = Carbon::now("Asia/Kuala_Lumpur");

        foreach ($request->field as $key => $value) {
            if(empty($request[$request->field[$key]]))continue;
            // dump($request[$request->field[$key]]);
            $array_insert[$value] = strtoupper($request[$request->field[$key]]);
        }

        try {

            $mrn = $this->defaultSysparam($request->sysparam['source'],$request->sysparam['trantype']);
            $array_insert['MRN'] = $mrn;
            $lastidno = $table->insertGetId($array_insert);

            if(!empty($request->func_after)){
                if($request->func_after == 'save_preepis'){
                    $this->save_preepis($request,$mrn);
                }
            }

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
                    $epis_typeepis => 1,
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

            DB::table("hisdb.pat_mast")
                ->where("compcode",'=',session('compcode'))
                ->where("mrn",'=',$epis_mrn)
                ->update([
                    'episno' => $epis_no,
                    'patstatus' => 1,
                    'last_visit_date' => Carbon::now("Asia/Kuala_Lumpur"),
                    'first_visit_date' => Carbon::now("Asia/Kuala_Lumpur"),
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
                ->where('source','=','QUE')
                ->where('trantype','=',$epistycode_q);

                //kalu xjumpe buat baru
            if(!$queue_obj->exists()){
                DB::table('sysdb.sysparam')
                    ->insert([
                        'compcode' => '9A',
                        'source' => 'QUE',
                        'trantype' => $epistycode_q,
                        'description' => $epistycode_q.' Queue No.',
                        'pvalue2' => Carbon::now("Asia/Kuala_Lumpur")->toDateString()
                    ]);

                $queue_obj = DB::table('sysdb.sysparam')
                    ->where('source','=','QUE')
                    ->where('trantype','=',$epistycode_q);
            }

            $queue_data = $queue_obj->first();

                //ni start kosong balik bila hari baru
            if($queue_data->pvalue2 != Carbon::now("Asia/Kuala_Lumpur")->toDateString()){
                $queue_obj
                    ->update([
                        'pvalue1' => 0,
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

            $queueAll_data=$queueAll_obj->first();
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
                        'QueueNo' => $current_pvalue1+1,
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

            $queueSPEC_data=$queueSPEC_obj->first();

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
                        'QueueNo' => $current_pvalue1+1,
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

            if(!empty($epis_apptidno)){
                DB::table('hisdb.pre_episode')
                        ->where('apptidno','=',$epis_apptidno)
                        ->update([
                            'episno' => $epis_no
                        ]);
            }

            $queries = DB::getQueryLog();

            dump($queries);

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

            if($epis_type == "IP" || $epis_type == "DP"){
                $epistycode_q = "IP";
            }else{
                $epistycode_q = "OP";
            }

            //cari queue utk source que dgn trantype epistycode
            $queue_obj = DB::table('sysdb.sysparam')
                ->where('source','=','QUE')
                ->where('trantype','=',$epistycode_q);

                //kalu xjumpe buat baru
            if(!$queue_obj->exists()){
                DB::table('sysdb.sysparam')
                    ->insert([
                        'compcode' => '9A',
                        'source' => 'QUE',
                        'trantype' => $epistycode_q,
                        'description' => $epistycode_q.' Queue No.',
                        'pvalue2' => Carbon::now("Asia/Kuala_Lumpur")->toDateString()
                    ]);

                $queue_obj = DB::table('sysdb.sysparam')
                    ->where('source','=','QUE')
                    ->where('trantype','=',$epistycode_q);
            }

            $queue_data = $queue_obj->first();

                //ni start kosong balik bila hari baru
            // if($queue_data->pvalue2 != Carbon::now("Asia/Kuala_Lumpur")->toDateString()){
            //     $queue_obj
            //         ->update([
            //             'pvalue1' => 0,
            //             'pvalue2' => Carbon::now("Asia/Kuala_Lumpur")->toDateString()
            //         ]);
            // }

                //tambah satu dkt queue sysparam
            $current_pvalue1 = intval($queue_data->pvalue1);
            // $queue_obj
            //     ->update([
            //         'pvalue1' => $current_pvalue1+1
            //     ]);


            $queueAll_obj=DB::table('hisdb.queue')
                ->where('mrn','=',$epis_mrn)
                ->where('episno','=',$epis_no)
                ->where('deptcode','=','ALL');

            $queueAll_data=$queueAll_obj->first();
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
                        'QueueNo' => $current_pvalue1+1,
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
            }else{
                $queueAll_obj
                    ->update([
                        'AdmDoctor' => $epis_doctor,
                        'AttnDoctor' => $epis_doctor,
                        'chggroup' => $epis_billtype
                    ]);
            }

            //QUEUE FOR SPECIALIST

            $queueSPEC_obj=DB::table('hisdb.queue')
                ->where('mrn','=',$epis_mrn)
                ->where('episno','=',$epis_no)
                ->where('deptcode','=','SPEC');

            $queueSPEC_data=$queueSPEC_obj->first();

            if(!$queueSPEC_obj->exists()){
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
                        'QueueNo' => $current_pvalue1+1,
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
            }else{
                $queueSPEC_obj
                    ->update([
                        'AdmDoctor' => $epis_doctor,
                        'AttnDoctor' => $epis_doctor,
                        'chggroup' => $epis_billtype
                    ]);
            }

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
                ->where('debtorcode','=',$request->debtorcode)
                ->first();

            $sysparam = DB::table('sysdb.sysparam')
                ->where('source','=','GL')
                ->where('trantype','=',$debtormast->debtortype);

            if($sysparam->exists()){
                $sysparam_get = $sysparam->first();
                $pvalue1 = $sysparam_get->pvalue1;

                $sysparam->update([
                    'pvalue1' => intval($pvalue1+1)
                ]);

                $ourrefno = $debtormast->debtortype .intval($pvalue1+1);
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
                    'debtorcode'  =>  $request->debtorcode,
                    'episno' =>   $request->episno,
                    'staffid' =>   $request['newgl-staffid'],
                    'childno' =>   $request['newgl-childno'],
                    'relatecode' =>   $request['hid_newgl_relatecode'],
                    'gltype' =>   $request['newgl-childno'],
                    'startdate' =>   $request['newgl-effdate'],
                    'enddate' =>   $request['newgl-expdate'],
                    'refno' =>   $request['newgl-refno'],
                    'ourrefno' =>   $ourrefno,
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

        $episode = DB::table('hisdb.episode')
                ->where('mrn','=',$request->mrn)
                ->where('episno','=',$request->episno)
                ->first();

        $epispayer = DB::table('hisdb.epispayer')
                ->where('compcode','=',session('compcode'))
                ->where('mrn','=',$request->mrn)
                ->where('Episno','=',$request->episno)
                ->first();

        $debtormast = DB::table('debtor.debtormast')
                ->where('compcode','=',session('compcode'))
                ->where('debtorcode','=',$epispayer->payercode)
                ->where('debtortype','=',$epispayer->pay_type)
                ->first();

        $bed = DB::table('hisdb.bed')
                ->where('compcode','=',session('compcode'))
                ->where('bednum','=',$episode->bed)
                ->first();

        $responce = new stdClass();
        $responce->episode = $episode;
        $responce->epispayer = $epispayer;
        $responce->debtormast = $debtormast;
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

            $idno = DB::table('hisdb.title')->max('idno');

            DB::table('hisdb.title')
                ->insert([
                    'compcode' => session('compcode'),
                    'Code' => intval($idno) + 1,
                    'description' => $request->title_desc,
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

    public function new_areacode_form(Request $request){

        DB::beginTransaction();

        try {

            $idno = DB::table('hisdb.areacode')->max('idno');


            DB::table('hisdb.areacode')
                ->insert([
                    'compcode' => session('compcode'),
                    'areacode' => intval($idno) + 1,
                    'description' => $request->areacode_desc,
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

    public function new_relationship_form(Request $request){

        DB::beginTransaction();

        try {

            $idno = DB::table('hisdb.relationship')->max('idno');


            DB::table('hisdb.relationship')
                ->insert([
                    'compcode' => session('compcode'),
                    'areacode' => intval($idno) + 1,
                    'description' => $request->relationship_desc,
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


}