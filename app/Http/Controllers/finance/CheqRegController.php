<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;

class CheqRegController extends defaultController
{   

    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
        $this->duplicateCode = "startno";
    }

    public function show(Request $request)
    {   
        return view('finance.CM.chqreg.cheqreg');
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


            $chqreg = DB::table('finance.chqreg')
                            ->where('startno','=',$request->startno)
                            ->where('bankcode','=',$request->bankcode);

            if($chqreg->exists()){
                throw new \Exception("record duplicate");
            }

            DB::table('finance.chqreg')
                ->insert([  
                    'compcode' => session('compcode'),
                    'bankcode' => strtoupper($request->bankcode),
                    'startno' => $request->startno,
                    'endno' => $request->endno,
                    'cheqqty' => $request->endno-$request->startno+1,
                    'recstatus' => 'OPEN',
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur")
                ]);

            $bankcode = $request->bankcode;
            $startno = $request->startno;
            $endno = $request->endno;


            for ($i=$request->startno; $i <= $request->endno; $i++) { 

                $chqtran = DB::table('finance.chqtran')
                                ->where('cheqno','=',$i)
                                ->where('bankcode','=',$request->bankcode);

                if($chqtran->exists()){
                    continue;
                }


                DB::table('finance.chqtran')
                    ->insert([  
                        'compcode' => session('compcode'),
                        'bankcode' => strtoupper($request->bankcode),
                        'cheqno' => $i,
                        'recstatus' => 'OPEN',
                        'adduser' => session('username'),
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur")
                    ]);
            }

            

             DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

             return response($e->getMessage(), 500);
        }
    }

    public function edit(Request $request){
        
        DB::beginTransaction();


        try {

            $cheqtran = DB::table('finance.chqtran')->select('recstatus')
                            ->where('compcode', '=', session('compcode'))
                            ->where('bankcode', '=', $request->bankcode)
                            ->where('recstatus', '<>', 'OPEN')
                            ->count();

            if ($cheqtran){
                throw new \Exception("Cannot edit. Cheque has been issued");
            }

            dd($cheqtran);

            DB::table('finance.chqreg')
                ->where('idno','=',$request->idno)
                ->update([  
                   'bankcode' => strtoupper($request->bankcode),
                    'startno' => strtoupper($request->startno),
                    'endno' => $request->endno,
                    'cheqqty' => $request->endno -$request->startno+1,
                    'recstatus' => 'OPEN',
                    'upduser' => session('username'),
                    'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                ]); 

            for ($i=$request->startno; $i <= $request->endno; $i++) { 

                $chqtran = DB::table('finance.chqtran')
                    ->where('cheqno','=',$i)
                    ->where('bankcode','=',$request->bankcode);

                if($chqtran->exists()){
                    continue;
                }


            DB::table('finance.chqtran')
                ->where('idno','=',$request->idno)
                ->update([  
                   'bankcode' => strtoupper($request->bankcode),
                    'cheqno' => $i,
                    'recstatus' => 'OPEN',
                    'upduser' => session('username'),
                    'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                ]); 
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

             return response($e->getMessage(), 500);
        }
    }

    public function del(Request $request){
        DB::table('finance.chqreg')
            ->where('idno','=',$request->idno)
            ->update([  
                'recstatus' => 'DEACTIVE',
                'deluser' => session('username'),
                'deldate' => Carbon::now("Asia/Kuala_Lumpur")
            ]);

        DB::table('finance.chqtran')
            ->where('idno','=',$request->idno)
            ->delete();
             
    }
}