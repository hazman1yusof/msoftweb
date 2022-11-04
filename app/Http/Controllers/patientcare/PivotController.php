<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use stdClass;
use DB;
use Carbon\Carbon;
use Intervention\Image\Facades\Image;
use Response;
use Auth;
use App\Models\SuratMasuk;

class PivotController extends Controller
{   
    
    public function __construct()
    {

        $this->middleware('auth');
    }

    public function show(Request $request)
    {
        return view('pivot');
    }

    public function get_json_pivot(Request $request){
    	$pateis = DB::table('pateis_epis')
    				->select('gender','race','religion','payertype','regdept','admdoctor','admsrc','docdiscipline','casetype','agerange','citizen','area','year','quarter','month','bedtype')
                    ->where('year','=','Y2021')
                    // ->where('month','=','M02')
                    ->where('datetype','=','dis')
                    ->take(500)
    				->get();

    	return json_encode($pateis);
    }

}