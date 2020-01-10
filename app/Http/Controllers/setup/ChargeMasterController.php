<?php

namespace App\Http\Controllers\setup;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;

class ChargeMasterController extends defaultController
{   

    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
        $this->duplicateCode = "chgcode";
    }

    public function show(Request $request)
    {   
        return view('setup.chargemaster.chargemaster');
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

    public function chgpricelatest(Request $request){
        dd('test');
    }
}