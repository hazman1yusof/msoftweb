<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use App\Exports\APAgeingExport;
use Maatwebsite\Excel\Facades\Excel;

class finance_yearendController extends defaultController
{   

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        $currentyear = DB::table('sysdb.sysparam')
                            ->where('compcode',session('compcode'))
                            ->where('source','GL')
                            ->where('trantype','CURRENT_YEAR')
                            ->first();

        $period = DB::table('sysdb.period')
                            ->where('compcode',session('compcode'))
                            ->where('year',$currentyear->pvalue1 + 1);

        if($period->exists()){
            $period = $period->first();
        }else{
            $period = $period->first();
        }



        return view('finance.GL.finance_yearend.finance_yearend',compact('currentyear'));
    }

    public function table(Request $request)
    {   
        switch($request->action){
            case 'process_newyear':
                return $this->process_newyear($request);
            case 'process_lastyear':
                return $this->process_lastyear($request);
            default:
                return 'error happen..';
        }
    }
}