<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Auth;
use Carbon\Carbon;
use DateTime;

class ContributionController extends defaultController
{   

    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
        $this->duplicateCode = "chgcode";
    }

    public function show(Request $request)
    {   
        return view('finance.CF.contribution');
    }

    public function form(Request $request)
    {   
        switch($request->oper){
            case 'add':
                return $this->add($request);
            case 'edit':
                return $this->edit($request);
            case 'del':
                return $this->del($request);
            default:
                return 'error happen..';
        }
    }

    public function add(Request $request){

        $drcode = DB::table('hisdb.doctor')
            ->select('doctorcode')
            ->where('compcode','=',session('compcode'))
            ->where('doctorcode','=',$request->drcode)->first();

        DB::beginTransaction();

        try {

            $sqlln = DB::table('debtor.drcontrib')->select('lineno_')
                ->where('compcode','=',session('compcode'))
                ->where('drcode','=',$request->drcode)
                ->count('lineno_');

            $li=intval($sqlln)+1;   
           
            DB::table('debtor.drcontrib')
                ->insert([
                    'compcode' => session('compcode'),
                    'lineno_' => $li,
                    'unit' => session('unit'),
                    'drcode' => strtoupper($request->drcode),
                    'chgcode' => strtoupper($request->chgcode),
                    'effdate' => $this->turn_date($request->effdate),
                    'epistype' => strtoupper($request->epistype),
                    'stfamount' => $request->stfamount,
                    'stfpercent' => $request->stfpercent,
                    'drprcnt' => $request->drprcnt,
                    'amount' => $request->amount,
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                ]);

             DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
       
    }

    public function edit(Request $request){

       
    }

    public function del(Request $request){

       
    }

   
      
    
}