<?php

namespace App\Http\Controllers\hisdb;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use DB;
use stdClass;
use Carbon\Carbon;


class otMaintenanceController extends defaultController
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
        return view('hisdb.appointment.ot_maintenance');
    }

    public function table(Request $request)
    {   
        $paginate = DB::table('hisdb.apptresrc')
        			->select('resourcecode','description','TYPE')
        			->where('TYPE','=',$request->filterVal[0]);

        if(!empty($request->searchCol)){
            $paginate = $paginate->where($request->searchCol[0],'like',$request->searchVal[0]);
        }


        $paginate = $paginate->paginate(30);

        $apptres = $paginate->items();

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
            case 'ot_maintenance_save':
                switch($request->oper){
                    case 'add':
                        if(!$this->my_duplicate($request)){
                            return $this->add($request);
                        }else{
                            return response('Duplicate Resoruce Code', 500);
                        }
                    case 'edit':
                        if(!$this->my_duplicate($request)){
                            return $this->edit($request);
                        }else{
                            return response('Duplicate Resoruce Code', 500);
                        }
                    case 'del':
                        return $this->del($request);
                }
            default:
                return 'error happen..';
        }
    }

    public function my_duplicate(Request $request){
        if($request->oper == 'add'){

            return DB::table('hisdb.apptresrc')
                ->where('compcode',session('compcode'))
                ->where('resourcecode','=',$request->resourcecode)
                ->where('TYPE','=',$request->TYPE)
                ->exists();

        }else if($request->oper == 'edit'){

            return DB::table('hisdb.apptresrc')
                ->where('compcode',session('compcode'))
                ->where('idno','!=',$request->idno)
                ->where('resourcecode','=',$request->resourcecode)
                ->where('TYPE','=',$request->TYPE)
                ->exists();

        }
    }

    public function add(Request $request){

        DB::beginTransaction();
        try {

            $idno = DB::table('hisdb.apptresrc')
                ->insertGetId([  
                    'compcode' => session('compcode'),
                    'resourcecode' => $request->code,
                    'description' => strtoupper($request->description),
                    'TYPE' => 'OT',
                    'recstatus' => 'ACTIVE',
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur")
                ]);

            DB::table('hisdb.apptresrc')
                ->where('idno',$idno)
                ->update([
                    'resourcecode' => $idno
                ]);

             DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function edit(Request $request){
        
        DB::beginTransaction();
        try {

            DB::table('hisdb.apptresrc')
                ->where('idno','=',$request->idno)
                ->update([  
                    'description' => strtoupper($request->description),
                    'recstatus' => 'ACTIVE',
                    'upduser' => session('username'),
                    'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                ]); 

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function del(Request $request){

        DB::table('hisdb.apptresrc')
            ->where('idno','=',$request->idno)
            ->update([  
                'recstatus' => 'DEACTIVE',
                'lastuser' => session('username')
            ]);

    }

    public function save_session(Request $request){
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

}
