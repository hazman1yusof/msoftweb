<?php

namespace App\Http\Controllers\test;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;

class TestController extends defaultController
{   

    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
        $this->duplicateCode = "bloodcode";
    }

    public function show(Request $request)
    {   
        // dd($request);
        return view('test.testexpdateloop');
    }

}