<?php

namespace App\Http\Controllers\hisdb;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use DB;
use stdClass;


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
        $paginate = DB::table('hisdb.apptresrc')->select('resourcecode','description','TYPE')->paginate(30);
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
}
