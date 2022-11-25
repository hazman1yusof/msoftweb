<?php

namespace App\Http\Controllers\setup;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class DoctorContributionController extends defaultController
{   
    public function __construct()
    {
        $this->middleware('auth');
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

        DB::beginTransaction();

        try {
           
            $drtran = DB::table('debtor.drtran')
                ->where('compcode','=',session('compcode'))
                ->where('drcode','=',$request->drcode)
                ->where('chgcode','=',$request->chgcode)
                ->whereDate('trandate','>=',Carbon::createFromFormat('d/m/Y',$request->effdate)->format('Y-m-d'));

            if($drtran->exists()){
                throw new \Exception("drtran exists");
            }

            DB::table('debtor.drcontrib')
                ->where('compcode','=',session('compcode'))
                ->where('drcode','=',$request->drcode)
                ->where('idno','=',$request->idno)
                ->where('lineno_','=',$request->lineno_)
                ->update([
                    'chgcode' => strtoupper($request->chgcode),
                    'effdate' => $this->turn_date($request->effdate),
                    'epistype' => strtoupper($request->epistype),
                    'stfamount' => $request->stfamount,
                    'stfpercent' => $request->stfpercent,
                    'drprcnt' => $request->drprcnt,
                    'amount' => $request->amount,
                    'lastuser' => session('username'), 
                    'lastdate' => Carbon::now("Asia/Kuala_Lumpur"), 
                    
                ]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }

    }

    public function del(Request $request){
        DB::beginTransaction();

        try {

            $drcontrib = DB::table('debtor.drcontrib')
                            ->where('compcode','=',session('compcode'))
                            ->where('idno','=',$request->idno);

            if($drcontrib->exists()){
                $drcontrib = $drcontrib->first();

                $drtran1 = DB::table('debtor.drtran')
                    ->where('compcode','=',session('compcode'))
                    ->where('drcode','=',$drcontrib->drcode)
                    ->where('chgcode','=',$drcontrib->chgcode)
                    ->whereDate('trandate','>=',$drcontrib->effdate);

                if($drtran1->exists()){
                    throw new \Exception("drtran exists");
                }

                DB::table('debtor.drcontrib')
                    ->where('compcode','=',session('compcode'))
                    ->where('idno','=',$request->idno)
                    ->delete();
            }            
            
            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

}

