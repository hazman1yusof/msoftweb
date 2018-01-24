<?php

namespace App\Http\Controllers\hisdb;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;


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

    public function form(Request $request)
    {   
        switch($request->oper){
            case 'add':

            	if($this->default_duplicate( ///check duplicate
            		$request->table_name,
            		$request->table_id,
            		$request->resourcecode
            	)){
            		return response('duplicate', 500);
            	};

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
