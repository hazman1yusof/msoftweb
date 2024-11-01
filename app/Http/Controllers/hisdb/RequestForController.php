<?php

namespace App\Http\Controllers\hisdb;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;
use PDF;

class RequestForController extends defaultController
{
    
    var $table;
    var $duplicateCode;
    
    public function __construct()
    {
        $this->middleware('auth');
        // $this->duplicateCode = "chgtype";
    }
    
    public function show(Request $request)
    {
        return view('hisdb.requestfor.requestfor');
    }
    
    public function table(Request $request)
    {
        switch($request->action){
            default:
                return 'error happen..';
        }
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