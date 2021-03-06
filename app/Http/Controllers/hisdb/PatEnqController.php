<?php

namespace App\Http\Controllers\hisdb;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;
use Intervention\Image\Facades\Image;
use Response;

class PatEnqController extends defaultController
{   

    var $table;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        return view('hisdb.pat_enq.landing');
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

    public function form(Request $request)
    {   
        $type = $request->file('file')->getClientMimeType();
        $file_path = $request->file('file')->store('pat_enq', 'public_uploads');
        DB::table('hisdb.patresult')
            ->insert([
                'compcode' => session('compcode'),
                'attachmentfile' => $file_path,
                'adduser' => session('username'),
                'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                'mrn' => $request->mrn,
                'type' => $type,
                'trxdate' => $request->trxdate
            ]);
    }

    public function thumbnail($folder,$image_path){

        if($folder == 'pat_enq'){ //image
            $img = Image::make('uploads/'.$folder.'/'.$image_path)->resize(96, 96);
        }else if($folder == 'application'){
            switch($image_path){
                case 'pdf': $img = Image::make('uploads/pat_enq/pdf_icon.png')->resize(96, 96); break;
            }
        }else if($folder == 'video'){

        }else if($folder == 'audio'){

        }

        return $img->response();
    }

    public function download($folder,$image_path){
        $file = public_path()."\\uploads\\".$folder."\\".$image_path;
        // dump($file);
        return Response::download($file);
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
}