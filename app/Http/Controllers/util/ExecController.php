<?php

namespace App\Http\Controllers\util;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Auth;
use Carbon\Carbon;

class ExecController extends defaultController
{   

    public function __construct(){
        $this->middleware('auth');
    }

    public function table(Request $request){  
        switch($request->action){
            case 'gltb':
                return $this->gltb($request);
            default:
                abort(404);
        }
    }

    public function gltb(Request $request){

        $period = $request->period;

        $exec_path = \config('get_config.EXEC_PATH').'';

        $exec = "start /B '' '".$exec_path."'";

        dd($exec);

        exec('start /B "" "C:\Program Files (x86)\Foxit Software\Foxit PDF Reader\FoxitPDFReader.exe" '.$filepath);
    }

}