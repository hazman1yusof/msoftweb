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
            case 'edit_all':
                return $this->edit_all($request);
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

    public function edit_all(Request $request){

        DB::beginTransaction();

        try {

            foreach ($request->dataobj as $key => $value) {

                DB::table('debtor.drcontrib')
                    ->where('compcode','=',session('compcode'))
                    ->where('drcode','=',$request->drcode)
                    ->where('idno','=',$request->idno)
                    ->where('lineno_','=',$value['lineno_'])
                    ->update([
                        'chgcode' => strtoupper($value['chgcode']),
                        'effdate' => $value['effdate'],
                        'epistype' => $value['epistype'],
                        'stfamount' => $value['stfamount'],
                        'stfpercent' => $value['stfpercent'],
                        'drprcnt' => $value['drprcnt'],
                        'amount' => $value['amount'],
                        'upduser' => session('username'), 
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                       
                    ]);
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }

    }

    public function del(Request $request){

       
    }

   
      
    
}