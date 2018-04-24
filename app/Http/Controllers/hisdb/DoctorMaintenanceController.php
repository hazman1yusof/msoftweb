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
        			->select('resourcecode','description','TYPE')
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
        switch($request->oper){
            case 'add':
                return $this->defaultAdd($request);
            case 'edit':
                return $this->defaultEdit($request);
            case 'del':
                return $this->defaultDel($request);
            default:
                return 'error happen..';
        }
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
            ->first();

        if(count($check_exist)){
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
