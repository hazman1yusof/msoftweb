<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use stdClass;
use DB;
use Carbon\Carbon;
use Response;
use Auth;
use Storage;

class testController extends Controller
{   
    
    public function __construct()
    {

    }


    public function table(Request $request)
    {  
        switch($request->action){
            case 'chgmast_invflag_tukar_dari_product':
                return $this->chgmast_invflag_tukar_dari_product($request);
            default:
                return 'error happen..';
        }
    }

    public function chgmast_invflag_tukar_dari_product(Request $request){
        $chgmast = DB::table('hisdb.chgmast')
                    ->where('compcode','9A')
                    ->where('chggroup','25');

                    dd($chgmast->count());
    }

}