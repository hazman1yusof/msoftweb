<?php

namespace App\Http\Controllers\util;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Auth;
use Carbon\Carbon;

use App\Jobs\SendEmailPR;

class TestController extends defaultController
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
        // $chgmast = DB::table('hisdb.chgmast as cm')
        //             ->where('cm.compcode','9A')
        //             ->where('cm.chggroup','25')
        //             ->where('cm.invflag','1')
        //             ->whereNull('p.idno')
        //             ->leftjoin('material.product as p', function($join) use ($request){
        //                 $join = $join->on('p.itemcode', '=', 'cm.chgcode')
        //                               ->where('p.compcode','9A');
        //             })
        //             ->update([
        //                 'invflag' => 0
        //             ]);

        // dd($chgmast->count());
    }

}