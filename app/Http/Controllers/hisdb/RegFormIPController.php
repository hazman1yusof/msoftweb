<?php

namespace App\Http\Controllers\hisdb;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;
use PDF;

class RegFormIPController extends defaultController
{
    
    public function __construct()
    {
        $this->middleware('auth');
        // $this->duplicateCode = "chgtype";
    }
    
    public function RegFormIP_pdf(Request $request){
        
        return view('hisdb.RegFormIP.RegFormIP_pdfmake');
        
    }
    
}