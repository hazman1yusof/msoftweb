<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;

class CheqRegController extends defaultController
{   

    var $table;
    //var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
        //$this->duplicateCode = "Code";
    }

    public function show(Request $request)
    {   
        return view('finance.CM.chqreg.cheqreg');
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