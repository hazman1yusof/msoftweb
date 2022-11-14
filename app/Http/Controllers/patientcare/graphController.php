<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use stdClass;
use App\User;
use DB;
use Carbon\Carbon;
use Auth;

class graphController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function get_graph(Request $request){

        switch ($request->action) {
            case 'get_graph_cardio':
                return $this->get_graph_cardio($request);
                break;
        }
        
    }

    public function get_graph_cardio(Request $request){
        $patcardio = DB::table('hisdb.patcardio')
                            ->where('mrn','11')
                            ->get();

        return view('cardiograph',compact('patcardio'));
    }

    public static function mydump($obj,$line='null'){
        dd([
            $line,
            $obj->toSql(),
            $obj->getBindings()
        ]);

    }

}
