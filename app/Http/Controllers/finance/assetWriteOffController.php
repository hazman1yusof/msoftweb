<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class assetWriteOffController extends defaultController
{   

    var $table;
    //var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
        // $this->duplicateCode = "assetcode";
    }

    public function show(Request $request)
    {   
        return view('finance.FA.assetWriteOff.assetWriteOff');
    }

    public function form(Request $request)
    {   
        DB::enableQueryLog();
        switch($request->action){

            default:
                return 'error happen..';
        }
    }

}