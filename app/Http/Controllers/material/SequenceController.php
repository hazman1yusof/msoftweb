<?php

namespace App\Http\Controllers\material;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use DB;

class SequenceController extends defaultController
{   

    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
        $this->duplicateCode = "Code";
    }

    public function show(Request $request)
    {   
        return view('material.sequence.sequence');
    }

    public function form(Request $request)
    {  
        switch($request->oper){
            case 'add':
                $exists = DB::table('material.sequence')
                        ->where('compcode','=',session('compcode'))
                        ->where('dept','=',$request->dept)
                        ->where('trantype','=',$request->trantype)
                        ->exists();

                if($exists){
                    return response('Trantype '.$request->trantype.' already exists', 500);
                }
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