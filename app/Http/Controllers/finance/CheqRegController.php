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
                throw new \Exception("Record duplicate");
            }


            $startno = $request->startno;
            $endno = $request->endno;
            $cheqtran = DB::table('finance.chqtran')
                        ->whereBetween('cheqno', [$startno, $endno])
                        ->where('bankcode','=',$request->bankcode);


            if($cheqtran->exists()){
                throw new \Exception("Cheque No already exist");
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

            $responce = new stdClass();
            $responce->errormsg = $e->getMessage();
            $responce->request = $_REQUEST;

            return response(json_encode($responce), 500);
        }
    }

    public function edit(Request $request){
        
        DB::beginTransaction();

        try {

            if(!empty($request->idno)){
                $idno_ = $request->idno;
            }else{
                $idno_ = $request->idno;
            }
            
            $cheqreg = DB::table('finance.chqreg')
                ->where('compcode', '=', session('compcode'))
                ->where('idno', '=', $request->idno)
                ->where('startno','=',$request->startno)
                ->where('bankcode','=',$request->bankcode);

            $cheqreg_get = $cheqreg->first();

            $startno = $request->startno;
            $endno = $request->endno;
            $cheqtran = DB::table('finance.chqtran')
                        ->whereBetween('cheqno', [$startno, $endno])
                        ->where('bankcode','=',$request->bankcode);

            $cheqtran_get = $cheqtran->first();

            //check duplicate
            $chqreg_duplicate = DB::table('finance.chqreg')
                        ->where('startno','=',$request->startno)
                        ->where('bankcode','=',$request->bankcode);

            if($chqreg_duplicate->exists()){
                throw new \Exception("Record duplicate");
            }

            //check between range
            $startno = $request->startno;
            $endno = $request->endno;

            $cheqtran_duplicate = DB::table('finance.chqtran')
                        ->whereBetween('cheqno', [$startno, $endno])
                        ->where('bankcode','=',$request->bankcode);


            if($cheqtran_duplicate->exists()){
                throw new \Exception("Cheque No already exist");
            }


            $cheqtrancount = DB::table('finance.chqtran')->select('recstatus')
                            ->where('compcode', '=', session('compcode'))
                            ->where('bankcode', '=', $request->bankcode)
                            ->where('cheqno', '>=', $request->startno)
                            ->where('cheqno', '<=', $request->endno)
                            ->where('recstatus', '<>', 'OPEN')
                            ->count();

            if ($cheqtrancount->exists()){
                throw new \Exception("Cannot edit. Cheque has been issued");
            }

            //dd($cheqtrancount);

            if (!$chqreg_duplicate) {

                DB::table('finance.chqreg')
                    ->where('compcode','=',session('compcode'))
                    ->where('idno','=',$request->idno)
                    ->where('bankcode', '=>', strtoupper($request->bankcode))
                    ->update([  
                        'startno' => $request->startno,
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

            } else if (!$cheqtran_duplicate) {

                DB::table('finance.chqreg')
                    ->where('compcode','=',session('compcode'))
                    ->where('idno','=',$request->idno)
                    ->where('bankcode', '=>', strtoupper($request->bankcode))
                    ->update([  
                        'startno' => ($request->startno),
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

            } else {

                $startno = $request->startno;
                $endno = $request->endno;

                DB::table('finance.chqtran')
                    ->where('idno','=',$request->idno)
                    ->delete();

                for ($i=$request->startno; $i <= $request->endno; $i++) { 

                    $chqtran = DB::table('finance.chqtran')
                        ->where('cheqno','=',$i)
                        ->where('bankcode','=',$request->bankcode);

                    if($chqtran->exists()){
                        continue;
                    }
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

                DB::table('finance.chqreg')
                    ->where('compcode','=',session('compcode'))
                    ->where('idno','=',$request->idno)
                    ->where('bankcode', '=>', strtoupper($request->bankcode))
                    ->update([  
                        'startno' => ($request->startno),
                        'endno' => $request->endno,
                        'cheqqty' => $request->endno -$request->startno+1,
                        'recstatus' => 'OPEN',
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                    ]); 

            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            
            $responce = new stdClass();
            $responce->errormsg = $e->getMessage();
            $responce->request = $_REQUEST;

            return response(json_encode($responce), 500);
        }
    }

    public function del(Request $request){

        DB::beginTransaction();

        try {

            $startno = $request->startno;
            $endno = $request->endno;

             $cheqtrancount_del = DB::table('finance.chqtran')->select('recstatus', 'bankcode')
                                ->where('compcode', '=', session('compcode'))
                                ->where('bankcode', '=', $request->bankcode)
                                ->whereBetween('cheqno', [$startno, $endno])
                                ->where('recstatus', '<>', 'OPEN');
                                //->count();

                if ($cheqtrancount_del->exists()){
                    throw new \Exception("Cannot delete. Cheque has been issued");
                } else {
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
        } catch (\Exception $e) {
            DB::rollback();    

            return response($e->getMessage(), 500);
        }    
    }
}