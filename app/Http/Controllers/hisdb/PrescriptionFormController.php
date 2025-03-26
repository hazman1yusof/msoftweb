<?php

namespace App\Http\Controllers\hisdb;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;
use PDF;

class PrescriptionFormController extends defaultController
{
    
    public function __construct()
    {
        $this->middleware('auth');
        // $this->duplicateCode = "chgtype";
    }
    
    public function PrescriptionForm_pdf(Request $request){
        
        return view('hisdb.PrescriptionForm.PrescriptionForm_pdfmake');
        
    }
    
}