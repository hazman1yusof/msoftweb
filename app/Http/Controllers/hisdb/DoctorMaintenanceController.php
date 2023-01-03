<?php

namespace App\Http\Controllers\hisdb;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use DB;
use stdClass;
use Carbon\Carbon;


class DoctorMaintenanceController extends defaultController
{   

	
    var $table;
    var $duplicateCode;

    public function __construct()
    {   
        $this->middleware('auth');
        $this->duplicateCode = "resourcecode";
    }

    public function show(Request $request)
    {   
        return view('hisdb.appointment.doctor_maintenance');
    }

    public function table(Request $request)
    {   
        $paginate = DB::table('hisdb.apptresrc')
        			->select('resourcecode','description','TYPE','intervaltime')
        			->where('TYPE','=',$request->filterVal[0]);

        if(!empty($request->searchCol)){
            $paginate = $paginate->where($request->searchCol[0],'like',$request->searchVal[0]);
        }


        $paginate = $paginate->paginate(30);

        $apptres = $paginate->items();

        foreach ($apptres as $key => $value) {
            $value->countsession = DB::table('hisdb.apptsession')->where('doctorcode','=',$value->resourcecode)->count();
        }

        $responce = new stdClass();
        $responce->page = $paginate->currentPage();
        $responce->total = $paginate->lastPage();
        $responce->records = $paginate->total();
        $responce->rows = $paginate->items();

        return json_encode($responce);
    }

    public function form(Request $request)
    {   
        switch($request->action){
            case 'al':
                switch($request->oper){
                    case 'add':
                        return $this->add_al($request);
                    case 'edit':
                        return $this->edit_al($request);
                    case 'del':
                        return $this->del_al($request);
                    default:
                        return 'error happen..';
                }
            case 'ph':
                switch($request->oper){
                    case 'add':
                        return $this->add_ph($request);
                    case 'edit':
                        return $this->edit_ph($request);
                    case 'del':
                        return $this->del_ph($request);
                    default:
                        return 'error happen..';
                }

        }
    }

    public function save_session(Request $request){

        DB::beginTransaction();

        try {

            $intervaltime = intval($request->intervaltime);

            $apptresrc = DB::table('hisdb.apptresrc')
                                ->where('resourcecode',$request->resourcecode);

            if(!$apptresrc->exists()){
                throw new \Exception("resourcecode doesnt exist in apptresrc");
            }

            DB::table('hisdb.apptresrc')
                    ->where('resourcecode',$request->resourcecode)
                    ->update([
                        'intervaltime' => $intervaltime
                    ]);
                        
            if($request->oper == 'add'){

                foreach ($request->rowsArray as $key => $value) {
                    if($value['status']=='True'){
                        DB::table('hisdb.apptsession')->insert([
                            'compcode' => session('compcode'),
                            'adduser' => session('username'),
                            'adddate' => Carbon::now(),
                            'recstatus' =>'A',
                            'doctorcode' => $value['doctorcode'],
                            'days' => $value['days'],
                            'timefr1' => $value['timefr1'],
                            'timeto1' => $value['timeto1'],
                            'timefr2' => $value['timefr2'],
                            'timeto2' => $value['timeto2'],
                            'status' => $value['status'],

                        ]);
                    }else{
                        if($value['status']=='False'){
                          DB::table('hisdb.apptsession')->insert([
                                'compcode' => session('compcode'),
                                'adduser' => session('username'),
                                'adddate' => Carbon::now(),
                                'recstatus' =>'A',
                                'doctorcode' => $value['doctorcode'],
                                'days' => $value['days'],
                                'timefr1' => $value['timefr1'],
                                'timeto1' => $value['timeto1'],
                                'timefr2' => $value['timefr2'],
                                'timeto2' => $value['timeto2'],
                                'status' => $value['status'],

                            ]);
                        }
                    }
                }
            }else{
                foreach ($request->rowsArray as $key => $value) {
                    DB::table('hisdb.apptsession')
                        ->where('doctorcode','=',$value['doctorcode'])
                        ->where('days','=',$value['days'])
                        ->update([
                            'compcode' => session('compcode'),
                            'upduser' => session('username'),
                            'upddate' => Carbon::now(),
                            'recstatus' => 'A',
                            'timefr1' => $value['timefr1'],
                            'timeto1' => $value['timeto1'],
                            'timefr2' => $value['timefr2'],
                            'timeto2' => $value['timeto2'],
                            'status' => $value['status'],
                        ]);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e, 500);
        }


    }

    public function save_bgleave(Request $request){
        DB::table('sysdb.sysparam')
            ->where('compcode','=',session('compcode'))
            ->where('source','=','HIS')
            ->where('trantype','=','ALCOLOR')
            ->update(['pvalue1' => $request->bg_leave]);
    }

    public function save_colorph(Request $request){
        $check_exist = DB::table('hisdb.apptphcolor')
            ->where('compcode','=',session('compcode'))
            ->where('userid','=',session('username'))
            ->where('phidno','=',$request->phidno)
            ->exists();

        if($check_exist){
            DB::table('hisdb.apptphcolor')
                ->where('compcode','=',session('compcode'))
                ->where('userid','=',session('username'))
                ->where('phidno','=',$request->phidno)
                ->update([
                    'color' => $request->color,
                    'upduser' => session('username'),
                    'upddate' => Carbon::now('Asia/Kuala_Lumpur'),
                ]);

        }else{
            DB::table('hisdb.apptphcolor')
                ->insert([
                    'compcode' => session('compcode'),
                    'color' => $request->color,
                    'phidno' => $request->phidno,
                    'userid' => session('username'),
                    'adduser' => session('username'),
                    'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                    'recstatus' => 'A'
                ]);
        }
    }

    public function add_al(Request $request){
        DB::beginTransaction();

        $year = Carbon::parse($request->datefr)->format('Y');

        try {

            $table = DB::table('hisdb.apptleave');

            $array_insert = [
                'resourcecode' => $request->resourcecode,
                'year' => $year,
                'datefr' => $request->datefr,
                'dateto' => $request->dateto,
                'remark' => $request->remark,
                'compcode' => session('compcode'),
                'adduser' => session('username'),
                'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                'recstatus' => 'ACTIVE'
            ];

            $table->insert($array_insert);
            $queries = DB::getQueryLog();

            $responce = new stdClass();
            $responce->queries = $queries;
            echo json_encode($responce);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            
            return response($e->getMessage(), 500);
        }
    }

    public function edit_al(Request $request){
        DB::beginTransaction();

        $year = Carbon::parse($request->datefr)->format('Y');
        try {

            $table = DB::table('hisdb.apptleave')->where('idno','=',$request->idno);

            $array_update = [
                'year' => $year,
                'datefr' => $request->datefr,
                'dateto' => $request->dateto,
                'remark' => $request->remark,
                'compcode' => session('compcode'),
                'upduser' => session('username'),
                'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                'recstatus' => 'ACTIVE'
            ];

            $table->update($array_update);

            $queries = DB::getQueryLog();

            $responce = new stdClass();
            $responce->queries = $queries;
            echo json_encode($responce);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function del_al(Request $request){
        DB::beginTransaction();
        try {

            $table = DB::table('hisdb.apptleave')->where('idno','=',$request->idno);
            $table->update([
                'deluser' => session('username'),
                'deldate' => Carbon::now("Asia/Kuala_Lumpur"),
                'recstatus' => 'DEACTIVE',
            ]);

            $responce = new stdClass();
            $responce->sql = $table->toSql();
            $responce->sql_bind = $table->getBindings();
            echo json_encode($responce);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            
            return response($e->getMessage(), 500);
        }
    }

    public function add_ph(Request $request){
        DB::beginTransaction();

        $year = Carbon::parse($request->datefr)->format('Y');
        try {

            $table = DB::table('hisdb.apptph');

            $array_insert = [
                'year' => $year,
                'datefr' => $request->datefr,
                'dateto' => $request->dateto,
                'remark' => $request->remark,
                'compcode' => session('compcode'),
                'adduser' => session('username'),
                'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                'recstatus' => 'ACTIVE'
            ];

            $table->insert($array_insert);
            $queries = DB::getQueryLog();

            $responce = new stdClass();
            $responce->queries = $queries;
            echo json_encode($responce);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            
            return response($e->getMessage(), 500);
        }
    }

    public function edit_ph(Request $request){
        DB::beginTransaction();
        
        $year = Carbon::parse($request->datefr)->format('Y');
        try {

            $table = DB::table('hisdb.apptph')->where('idno','=',$request->idno);

            $array_update = [
                'year' => $year,
                'datefr' => $request->datefr,
                'dateto' => $request->dateto,
                'remark' => $request->remark,
                'compcode' => session('compcode'),
                'upduser' => session('username'),
                'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                'recstatus' => 'ACTIVE'
            ];

            $table->update($array_update);

            $queries = DB::getQueryLog();

            $responce = new stdClass();
            $responce->queries = $queries;
            echo json_encode($responce);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

        }
        
    }

    public function del_ph(Request $request){
        DB::beginTransaction();
        try {

            $table = DB::table('hisdb.apptph')->where('idno','=',$request->idno);
            $table->update([
                'deluser' => session('username'),
                'deldate' => Carbon::now("Asia/Kuala_Lumpur"),
                'recstatus' => 'DEACTIVE',
            ]);

            $responce = new stdClass();
            $responce->sql = $table->toSql();
            $responce->sql_bind = $table->getBindings();
            echo json_encode($responce);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            
            return response($e->getMessage(), 500);
        }
        
    }

}
