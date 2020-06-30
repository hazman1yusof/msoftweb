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
   // var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
       // $this->duplicateCode = "startno";
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

            case 'edit_all':
                return $this->edit_all($request);
            case 'del':
                return $this->del($request);
            default:
                return 'error happen..';
        }
    }

     public function add(Request $request){

        DB::beginTransaction();
        try {


            // $chqreg = DB::table('finance.chqreg')
            //             ->where('startno','=',$request->startno)
            //             ->where('bankcode','=',$request->bankcode);


            // if($chqreg->exists()){
            //     throw new \Exception("Record duplicate");
            // }


            $startno = $request->startno;
            $endno = $request->endno;
            $cheqtran = DB::table('finance.chqtran')
                        ->whereBetween('cheqno', [$startno, $endno])
                        ->where('bankcode','=',$request->bankcode);


            if($cheqtran->exists()){
                throw new \Exception("Cheque No already exist");
            }

            $chqregidno = DB::table('finance.chqreg')
                ->insertGetId([  
                    'compcode' => session('compcode'),
                    'bankcode' => strtoupper($request->bankcode),
                    'startno' => $request->startno,
                    'endno' => $request->endno,
                    'cheqqty' => $request->endno-$request->startno+1,
                    'recstatus' => 'OPEN',
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur")
                ]);

            for ($i=$request->startno; $i <= $request->endno; $i++) { 

                DB::table('finance.chqtran')
                    ->insert([  
                        'compcode' => session('compcode'),
                        'bankcode' => strtoupper($request->bankcode),
                        'cheqno' => $i,
                        'chqregidno' => $chqregidno,
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
            
            $cheqreg = DB::table('finance.chqreg')
                ->where('compcode', '=', session('compcode'))
                ->where('idno', '=', $request->idno);

            $cheqreg_get = $cheqreg->first();

            $startno_old = $cheqreg_get->startno;
            $endno_old = $cheqreg_get->endno;
            $startno = $request->startno;
            $endno = $request->endno;

            //check duplicate
            // $chqreg_duplicate = DB::table('finance.chqreg')
            //             ->where('startno','=',$startno)
            //             ->where('bankcode','=',$request->bankcode);

            // if($chqreg_duplicate->exists()){
            //     throw new \Exception("Record duplicate");
            // }

            //check between range
            // $cheqtran_duplicate = DB::table('finance.chqtran')
            //             ->whereBetween('cheqno', [$startno, $endno])
            //             ->where('bankcode','=',$request->bankcode);


            // if($cheqtran_duplicate->exists()){
            //     throw new \Exception("Cheque No already exist");
            // }

            //check duplicate

            $cheqtran = DB::table('finance.chqtran')
                        ->whereBetween('cheqno', [$startno, $endno])
                        ->where('chqregidno','!=',$request->idno)
                        ->where('bankcode','=',$request->bankcode);

            if($cheqtran->exists()){
                throw new \Exception("Cheque No already exist");
            }

            // for ($i=$startno; $i <= $endno; $i++) { 

            //     $chqtran = DB::table('finance.chqtran')
            //                     ->where('compcode','=',session('compcode'))
            //                     ->where('cheqno','=',$i)
            //                     ->where('bankcode','=',$request->bankcode);

            //     if($chqtran->exists()){
            //         $chqtran_first = $chqtran->first();
            //         if($chqtran_first->chqregidno != $request->idno){
            //             throw new \Exception("Cheque No already exist");
            //         }
            //     }
            // }

            //cheqtran yg lama
            $cheqtrancount = DB::table('finance.chqtran')->select('recstatus')
                            ->where('compcode', '=', session('compcode'))
                            ->where('bankcode', '=', $request->bankcode)
                            ->where('cheqno', '>=', $startno_old)
                            ->where('cheqno', '<=', $endno_old)
                            ->where('recstatus', '<>', 'OPEN');

            if ($cheqtrancount->exists()){
                throw new \Exception("Cannot edit. Cheque has been issued");
            }

            //delete yg lama
            DB::table('finance.chqtran')
                ->where('compcode', '=', session('compcode'))
                ->where('bankcode', '=', $request->bankcode)
                ->where('cheqno', '>=', $startno_old)
                ->where('cheqno', '<=', $endno_old)
                ->delete();

            //update chqreg 
            DB::table('finance.chqreg')
                ->where('compcode', '=', session('compcode'))
                ->where('idno', '=', $request->idno)
                ->update([
                    'startno' => $startno,
                    'endno' => $endno,
                    'cheqqty' => $endno -$startno+1,
                    'recstatus' => 'OPEN',
                    'upduser' => session('username'),
                    'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                ]);

            //buat chqtran baru
            for ($i=$startno; $i <= $endno; $i++) { 
                DB::table('finance.chqtran')
                    ->insert([  
                        'compcode' => session('compcode'),
                        'bankcode' => strtoupper($request->bankcode),
                        'cheqno' => $i,
                        'chqregidno' => $request->idno,
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

    public function edit_all(Request $request){
        DB::beginTransaction();
        try {

            foreach ($request->dataobj as $key => $value) {
                $cheqreg = DB::table('finance.chqreg')
                    ->where('compcode', '=', session('compcode'))
                    ->where('idno', '=', $value['idno']);

                $cheqreg_get = $cheqreg->first();

                $startno_old = $cheqreg_get->startno;
                $endno_old = $cheqreg_get->endno;
                $startno = $value['startno'];
                $endno = $value['endno'];

                if($startno_old == $startno && $endno_old == $endno){
                    continue;
                }

                //check duplicate
                $cheqtran = DB::table('finance.chqtran')
                            ->whereBetween('cheqno', [$startno, $endno])
                            ->where('chqregidno','!=',$value['idno'])
                            ->where('bankcode','=',$request->bankcode);

                if($cheqtran->exists()){
                    throw new \Exception("Cheque No already exist");
                }

                //cheqtran yg lama
                $cheqtrancount = DB::table('finance.chqtran')->select('recstatus')
                                ->where('compcode', '=', session('compcode'))
                                ->where('bankcode', '=', $request->bankcode)
                                ->where('cheqno', '>=', $startno_old)
                                ->where('cheqno', '<=', $endno_old)
                                ->where('recstatus', '<>', 'OPEN');

                if ($cheqtrancount->exists()){
                    throw new \Exception("Cannot edit. Cheque has been issued");
                }

                //delete yg lama
                DB::table('finance.chqtran')
                    ->where('compcode', '=', session('compcode'))
                    ->where('bankcode', '=', $request->bankcode)
                    ->where('cheqno', '>=', $startno_old)
                    ->where('cheqno', '<=', $endno_old)
                    ->delete();

                //update chqreg 
                DB::table('finance.chqreg')
                    ->where('compcode', '=', session('compcode'))
                    ->where('idno', '=', $value['idno'])
                    ->update([
                        'startno' => $startno,
                        'endno' => $endno,
                        'cheqqty' => $endno -$startno+1,
                        'recstatus' => 'OPEN',
                        'upduser' => session('username'),
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                    ]);

                //buat chqtran baru
                for ($i=$startno; $i <= $endno; $i++) { 
                    DB::table('finance.chqtran')
                        ->insert([  
                            'compcode' => session('compcode'),
                            'bankcode' => strtoupper($request->bankcode),
                            'cheqno' => $i,
                            'chqregidno' => $value['idno'],
                            'recstatus' => 'OPEN',
                            'adduser' => session('username'),
                            'adddate' => Carbon::now("Asia/Kuala_Lumpur")
                        ]);
                }

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

            $cheqreg = DB::table('finance.chqreg')
                ->where('compcode', '=', session('compcode'))
                ->where('idno', '=', $request->idno);

            $cheqreg_get = $cheqreg->first();

            $startno_old = $cheqreg_get->startno;
            $endno_old = $cheqreg_get->endno;

           
            $cheqtrancount = DB::table('finance.chqtran')->select('recstatus')
                            ->where('compcode', '=', session('compcode'))
                            ->where('bankcode', '=', $cheqreg_get->bankcode)
                            ->where('cheqno', '>=', $startno_old)
                            ->where('cheqno', '<=', $endno_old)
                            ->where('recstatus', '<>', 'OPEN');

            if ($cheqtrancount->exists()){
                throw new \Exception("Cannot delete. Cheque has been issued");

            }

            DB::table('finance.chqreg')
                ->where('compcode', '=', session('compcode'))
                ->where('idno','=',$request->idno)
                ->update([  
                    'recstatus' => 'DEACTIVE',
                    'deluser' => session('username'),
                    'deldate' => Carbon::now("Asia/Kuala_Lumpur")
            ]);

            DB::table('finance.chqtran')
                ->where('compcode', '=', session('compcode'))
                ->where('bankcode', '=', $cheqreg_get->bankcode)
                ->where('cheqno', '>=', $startno_old)
                ->where('cheqno', '<=', $endno_old)
                ->delete();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();    

           return response($e->getMessage(), 500);
        }    
    }
}