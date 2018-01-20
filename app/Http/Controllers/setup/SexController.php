<?php

namespace App\Http\Controllerssetup;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;

class SexController extends extends defaultController
{   

    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
        $this->duplicateCode = "statuscode";
    }

    public function show(Request $request)
    {   
        return view('setup.doctorstatus.doctorstatus');
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