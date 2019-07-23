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
        return view('hisdb.pat_mgmt.landing');
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
        $table = $this->defaultGetter($request);

        if(!empty($request->sort)){
            foreach ($request->sort as $key => $value) {
                $table = $table->orderBy($key, $value);
            }
        }

        $request->page = $request->current;

        //////////paginate/////////
        $paginate = $table->paginate($request->rowCount);

        $responce = new stdClass();
        $responce->current = $paginate->currentPage();
        $responce->lastPage = $paginate->lastPage();
        $responce->total = $paginate->total();
        $responce->rowCount = $request->rowCount;
        $responce->rows = $paginate->items();
        $responce->sql = $table->toSql();
        $responce->sql_bind = $table->getBindings();

        return json_encode($responce);
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
                        ->where('compcode','=',session('compcode'))
                        ->get();
                break;

            case 'get_patient_title':
                $data = DB::table('hisdb.title')
                        ->select('code','description')
                        ->where('compcode','=',session('compcode'))
                        ->get();
                break;

            case 'get_patient_citizen':
                $data = DB::table('hisdb.citizen')
                        ->select('code','description')
                        ->where('compcode','=',session('compcode'))
                        ->get();
                break;

            case 'get_patient_areacode':
                $data = DB::table('hisdb.areacode')
                        ->select('areacode as code','description')
                        ->where('compcode','=',session('compcode'))
                        ->get();
                break;

            case 'get_patient_sex':
                $data = DB::table('hisdb.sex')
                        ->select('code','description')
                        // ->where('compcode','=',session('compcode'))
                        ->get();
                break;

            case 'get_patient_race':
                $data = DB::table('hisdb.racecode')
                        ->select('code','description')
                        ->where('compcode','=',session('compcode'))
                        ->get();
                break;
                
            case 'get_patient_religioncode':
                $data = DB::table('hisdb.religion')
                        ->select('code','description')
                        ->where('compcode','=',session('compcode'))
                        ->get();
                break;

            case 'get_patient_urlmarital':
                $data = DB::table('hisdb.marital')
                        ->select('code','description')
                        ->where('compcode','=',session('compcode'))
                        ->get();
                break;

            case 'get_patient_language':
                $data = DB::table('hisdb.languagecode')
                        ->select('code','description')
                        ->where('compcode','=',session('compcode'))
                        ->get();
                break;

            case 'get_patient_relationship':
                $data = DB::table('hisdb.relationship')
                        ->select('relationshipcode as code','description')
                        ->where('compcode','=',session('compcode'))
                        ->get();
                break;

            case 'get_reg_dept':
                $data = DB::table('sysdb.department')
                        ->select('deptcode as code','description')
                        ->where('compcode','=',session('compcode'))
                        ->get();
                break;

            case 'get_reg_source':
                $data = DB::table('sysdb.department')
                        ->select('deptcode as code','description')
                        ->where('compcode','=',session('compcode'))
                        ->get();
                break;

            case 'get_reg_case':
                $data = DB::table('hisdb.casetype')
                        ->select('case_code as code','description')
                        ->where('compcode','=',session('compcode'))
                        ->get();
                break;

            case 'get_reg_fin':
                $data = DB::table('debtor.debtortype')
                        ->select('debtortycode as code','description')
                        ->where('compcode','=',session('compcode'))
                        ->get();
                break;

            case 'get_reg_doctor':
                $data = DB::table('hisdb.doctor')
                        ->select('doctorcode as code','doctorname as description')
                        ->where('compcode','=',session('compcode'))     
                        ->get();
                break;

            case 'get_patient_idtype':
                return  '{"data":[{"sysno":"5","Comp":"","code":"O","description":"Own IC","createdBy":"admin","createdDate":"2013-04-11","LastUpdate":"0000-00-00","LastUser":"","RecStatus":""},{"sysno":"7","Comp":"","code":"F","description":"Father","createdBy":"admin","createdDate":"2013-04-11","LastUpdate":"0000-00-00","LastUser":"","RecStatus":""},{"sysno":"8","Comp":"","code":"M","description":"Mother","createdBy":"","createdDate":"0000-00-00","LastUpdate":"0000-00-00","LastUser":"","RecStatus":""},{"sysno":"9","Comp":"","code":"P","description":"Polis","createdBy":"","createdDate":"0000-00-00","LastUpdate":"0000-00-00","LastUser":"","RecStatus":""},{"sysno":"10","Comp":"","code":"T","description":"Tentera","createdBy":"","createdDate":"0000-00-00","LastUpdate":"0000-00-00","LastUser":"","RecStatus":""}]}';
                break;

            case 'get_debtor_list':
                if($request->type == 1){
                    $data = DB::table('debtor.debtormast')
                            ->where('compcode','=',session('compcode'))  
                            ->where('debtorcode','=',ltrim($request->mrn, '0'))
                            ->whereIn('debtortype', ['PR', 'PT'])
                            ->get();
                }else{
                    $data = DB::table('debtor.debtormast')
                            ->where('compcode','=',session('compcode'))  
                            ->where('debtorcode','=',ltrim($request->mrn, '0'))
                            ->whereNotIn('debtortype', ['PR', 'PT'])
                            ->get();
                }
                break;

            case 'get_billtype_list':
                if($request->type == "OP"){
                    $data = DB::table('hisdb.billtymst')
                            ->where('compcode','=',session('compcode'))  
                            ->where('opprice','=',$request->type)
                            ->get();
                }else{
                    $data = DB::table('hisdb.billtymst')
                            ->where('compcode','=',session('compcode')) 
                            ->where('opprice','=',$request->type)
                            ->get();
                }
                break;

            case 'save_new_episode':
                $this->save_new_episode($request);

            // case 'get_patient_active':
            //     return DB::table('hisdb.title')->select('code','description')->get();

            // case 'get_patient_urlconfidential':
            //     return DB::table('hisdb.occupation')->select('occupcode','description')->get();
            
            // case 'get_patient_mrfolder':
            //     return DB::table('hisdb.title')->select('code','description')->get();

            // case 'get_patient_patientcat':
            //     return DB::table('hisdb.occupation')->select('occupcode','description')->get();
            
            default:
                $data ='nothing';
                break;
        }

        $responce->data = $data;
        return json_encode($responce);
    }

    public function _add(Request $request){
        DB::beginTransaction();

        $table = DB::table('hisdb.pat_mast');

        $array_insert = [
            'compcode' => session('compcode'),
            'adduser' => session('username'),
            'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
            'recstatus' => 'A'
        ];

        foreach ($request->field as $key => $value) {
            $array_insert[$value] = $request[$request->field[$key]];
        }

        try {

            $mrn = $this->defaultSysparam($request->sysparam['source'],$request->sysparam['trantype']);
            $array_insert['MRN'] = $mrn;
            $lastidno = $table->insertGetId($array_insert);

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

        $array_ignore = ['mrn','MRN','first_visit_date','last_visit_date'];

        foreach ($request->field as $key => $value) {
            if(array_search($value,$array_ignore))continue;
            $array_update[$value] = $request[$request->field[$key]];
        }

        try {

            //////////where//////////
            $table = $table->where('idno','=',$request->idno);
            $user = $table->first();


            if($user->loginid != $request->loginid){
                if($this->default_duplicate('sysdb.users','username',$request->loginid)>0){
                    throw new \Exception("Username already exist");
                }
                $this->makeloginid($request);
            }


            $table->update($array_update);

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

    public function save_new_episode(Request $request){

        $epis_mrn = $request->epis_mrn;
        $epis_no = $request->epis_no;
        $epis_type = $request->epis_type;
        $epis_maturity = $request->epis_maturity;
        $epis_date = $request->epis_date;
        $epis_time = $request->epis_time;
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

        if ($epis_maturity == "1")
        {
            $epis_newcase = "1";
            $epis_followup = "0";           
        }
        else
        {
            $epis_newcase = "0";
            $epis_followup = "1";           
        }

        $epis_nc_preg = $epis_preg;
        $epis_fu_preg = $epis_preg;

        DB::table("hisdb.episode")
            ->insert([
                        "epis_compcode" => "9A",
                        "epis_mrn" => $epis_mrn,
                        "epis_no" => $epis_no,
                        "epis_type" => $epis_type,
                        "epis_newcase" => $epis_newcase,
                        "epis_followup" => $epis_followup,
                        "epis_date" => $epis_date,
                        "epis_time" => $epis_time,
                        "epis_dept" => $epis_dept,
                        "epis_src" => $epis_src,
                        "epis_case" => $epis_case,
                        "epis_doctor" => $epis_doctor,
                        "epis_paytype" => $epis_fin,
                        "epis_paymode" => $epis_paymode,
                        "epis_billtype" => $epis_billtype,
                        "epis_fu_preg" => $epis_fu_preg,
                        "epis_nc_preg" => $epis_nc_preg,
                        "epis_fee" => $epis_fee,
                        "epis_createdate" => Carbon::now("Asia/Kuala_Lumpur"),
                        "epis_createuser" => $session('username'),
                        "epis_updatedate" => Carbon::now("Asia/Kuala_Lumpur"),
                        "epis_updatetime" => Carbon::now("Asia/Kuala_Lumpur"),
                        "epis_upduser" => $session('username'),
                        "epis_active" => 1,
                        "epis_allocpayer" => 1
                     ]);
    }
}