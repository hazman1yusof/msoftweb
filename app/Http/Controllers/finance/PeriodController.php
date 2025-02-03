<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class PeriodController extends defaultController
{   

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        return view('finance.GL.period.period');
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
        DB::beginTransaction();
        
        try {

            $period = DB::table('sysdb.period')
                        ->where('compcode',session('compcode'))
                        ->where('year',$request->year);

            if($period->exists()){
                throw new \Exception("Period of this year already exists", 500);
            }

            DB::table('sysdb.period')
                ->insert([
                    'compcode' => session('compcode'),
                    'year' => $request->year,
                    'datefr1' => $request->datefr1,
                    'datefr2' => $request->datefr2,
                    'datefr3' => $request->datefr3,
                    'datefr4' => $request->datefr4,
                    'datefr5' => $request->datefr5,
                    'datefr6' => $request->datefr6,
                    'datefr7' => $request->datefr7,
                    'datefr8' => $request->datefr8,
                    'datefr9' => $request->datefr9,
                    'datefr10' => $request->datefr10,
                    'datefr11' => $request->datefr11,
                    'datefr12' => $request->datefr12,
                    'dateto1' => $request->dateto1,
                    'dateto2' => $request->dateto2,
                    'dateto3' => $request->dateto3,
                    'dateto4' => $request->dateto4,
                    'dateto5' => $request->dateto5,
                    'dateto6' => $request->dateto6,
                    'dateto7' => $request->dateto7,
                    'dateto8' => $request->dateto8,
                    'dateto9' => $request->dateto9,
                    'dateto10' => $request->dateto10,
                    'dateto11' => $request->dateto11,
                    'dateto12' => $request->dateto12,
                    'periodstatus1' => $request->periodstatus1,
                    'periodstatus2' => $request->periodstatus2,
                    'periodstatus3' => $request->periodstatus3,
                    'periodstatus4' => $request->periodstatus4,
                    'periodstatus5' => $request->periodstatus5,
                    'periodstatus6' => $request->periodstatus6,
                    'periodstatus7' => $request->periodstatus7,
                    'periodstatus8' => $request->periodstatus8,
                    'periodstatus9' => $request->periodstatus9,
                    'periodstatus10' => $request->periodstatus10,
                    'periodstatus11' => $request->periodstatus11,
                    'periodstatus12' => $request->periodstatus12,
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'upduser' => session('username'),
                    'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'recstatus' => 'ACTIVE',
                    'computerid' => session('computerid')
                ]);

            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e, 500);
        
        }
    }

    public function edit(Request $request){
        DB::beginTransaction();
        
        try {

            DB::table('sysdb.period')
                ->where('idno',$request->idno)
                ->update([
                    'datefr1' => $request->datefr1,
                    'datefr2' => $request->datefr2,
                    'datefr3' => $request->datefr3,
                    'datefr4' => $request->datefr4,
                    'datefr5' => $request->datefr5,
                    'datefr6' => $request->datefr6,
                    'datefr7' => $request->datefr7,
                    'datefr8' => $request->datefr8,
                    'datefr9' => $request->datefr9,
                    'datefr10' => $request->datefr10,
                    'datefr11' => $request->datefr11,
                    'datefr12' => $request->datefr12,
                    'dateto1' => $request->dateto1,
                    'dateto2' => $request->dateto2,
                    'dateto3' => $request->dateto3,
                    'dateto4' => $request->dateto4,
                    'dateto5' => $request->dateto5,
                    'dateto6' => $request->dateto6,
                    'dateto7' => $request->dateto7,
                    'dateto8' => $request->dateto8,
                    'dateto9' => $request->dateto9,
                    'dateto10' => $request->dateto10,
                    'dateto11' => $request->dateto11,
                    'dateto12' => $request->dateto12,
                    'periodstatus1' => $request->periodstatus1,
                    'periodstatus2' => $request->periodstatus2,
                    'periodstatus3' => $request->periodstatus3,
                    'periodstatus4' => $request->periodstatus4,
                    'periodstatus5' => $request->periodstatus5,
                    'periodstatus6' => $request->periodstatus6,
                    'periodstatus7' => $request->periodstatus7,
                    'periodstatus8' => $request->periodstatus8,
                    'periodstatus9' => $request->periodstatus9,
                    'periodstatus10' => $request->periodstatus10,
                    'periodstatus11' => $request->periodstatus11,
                    'periodstatus12' => $request->periodstatus12,
                    'upduser' => session('username'),
                    'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'recstatus' => 'ACTIVE',
                    'computerid' => session('computerid')
                ]);

            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e, 500);
        
        }
        
    }

    public function del(Request $request){
        DB::beginTransaction();
        
        try {

            DB::table('sysdb.period')
                ->where('idno',$request->idno)
                ->delete();

            // DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e, 500);
        
        }
        
    }
}
