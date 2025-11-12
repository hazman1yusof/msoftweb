<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use Response;

class updPNLAccount extends defaultController
{   

    public function __construct(){
        // $this->middleware('auth');
    }

    public function show(Request $request){
        return view('other.updPNLAccount.updPNLAccount');
    }

    public function table(Request $request){ 
        switch($request->action){
            case 'process':
                return $this->process($request);
            default:
                return 'error happen..';
        }
    }

    public function process(Request $request){
        DB::beginTransaction();

        try {


            DB::commit();

        } catch (Exception $e) {
            DB::rollback();

            dd('Error'.$e);
        }
    }
}