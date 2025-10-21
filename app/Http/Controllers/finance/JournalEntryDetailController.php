<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class JournalEntryDetailController extends defaultController
{   

    public function __construct()
    {
        $this->middleware('auth');
    }

     public function form(Request $request)
    {   

        DB::enableQueryLog();
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
 
    public function chgDate($date){
        if(!empty($date)){
            $newstr=explode("/", $date);
            return $newstr[2].'-'.$newstr[1].'-'.$newstr[0];
        }else{
            return '0000-00-00';
        }
    }

    public function add(Request $request){
        DB::beginTransaction();

        try {
           
            $gljnlhdr = DB::table("finance.gljnlhdr")
                ->where('auditno','=',$request->auditno)
                ->where('compcode','=',session('compcode'))
                ->first();

            $auditno = $gljnlhdr->auditno;
            $docno = $gljnlhdr->docno;
            $source = $gljnlhdr->source;
            $trantype = $gljnlhdr->trantype;
            $different = $gljnlhdr->different;
        
            ////1. calculate lineno_ by auditno
            $sqlln = DB::table('finance.gljnldtl')->select('lineno_')
                        ->where('compcode','=',session('compcode'))
                        ->where('auditno','=',$auditno)
                        ->max('lineno_');

            $li=intval($sqlln)+1;
            
            // 2. insert detail
            DB::table('finance.gljnldtl')
                ->insert([
                    'compcode' => session('compcode'),
                    'auditno' => $auditno,
                    'docno' => $docno,
                    'source' => $source,
                    'trantype' => $trantype,
                    'lineno_' => $li,
                    'costcode' => strtoupper($request->costcode),
                    'glaccount' => $request->glaccount,
                    'description' => strtoupper($request->description),
                    'drcrsign' => $request->drcrsign,
                    'amount' => floatval($request->amount),
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'recstatus' => 'OPEN',
                    'unit' => session('unit'),

                ]);

            // 3. calculate total amount for dr/cr
            $totalAmountDR = DB::table('finance.gljnldtl')
                ->where('compcode','=',session('compcode'))
                ->where('auditno','=',$auditno)
                ->where('drcrsign','=','DR')
                ->where('recstatus','!=','DELETE')
                ->sum('amount');

            $totalAmountCR = DB::table('finance.gljnldtl')
                ->where('compcode','=',session('compcode'))
                ->where('auditno','=',$auditno)
                ->where('drcrsign','=','CR')
                ->where('recstatus','!=','DELETE')
                ->sum('amount');
            
            //4. update different dr/cr
            DB::table('finance.gljnlhdr')
                ->where('compcode','=',session('compcode'))
                ->where('auditno','=',$auditno)
                ->update([
                    'creditAmt' => $totalAmountCR,
                    'debitAmt' => $totalAmountDR,
                    'different' => $totalAmountDR-$totalAmountCR, 
                ]);


            DB::commit();

            $responce = new stdClass();
            $responce->totalAmountDR = $totalAmountDR;
            $responce->totalAmountCR = $totalAmountCR;
            $responce->different = $totalAmountDR-$totalAmountCR;
            $responce->auditno = $auditno;

            return json_encode($responce);

        } catch (\Exception $e) {
            DB::rollback();

            return response($e, 500);
        }
    }

    public function edit(Request $request){

        DB::beginTransaction();

        try {

            ///1. update detail
            DB::table('finance.gljnldtl')
                ->where('compcode','=',session('compcode'))
                ->where('auditno','=',$request->auditno)
                ->where('lineno_','=',$request->lineno_)
                ->update([
                    'docno' => $request->docno,
                    'source' => $request->source,
                    'trantype' => $request->trantype,
                    'lineno_' => $request->lineno_,
                    'costcode' => strtoupper($request->costcode),
                    'glaccount' => $request->glaccount,
                    'description' => strtoupper($request->description),
                    'drcrsign' => $request->drcrsign,
                    'amount' => floatval($request->amount),
                    'upduser' => session('username'),
                    'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'recstatus' => 'OPEN',
                    'unit' => session('unit'),
                ]);

            ///2. recalculate total amount
            // $totalAmount = DB::table('finance.gljnldtl')
            //     ->where('compcode','=',session('compcode'))
            //     ->where('auditno','=',$request->auditno)
            //     ->where('recstatus','!=','DELETE')
            //     ->sum('amount');

            ///3. update total amount to header
            // DB::table('finance.gljnlhdr')
            //     ->where('compcode','=',session('compcode'))
            //     ->where('auditno','=',$request->auditno)
            //     ->update([
            //         'outamount' => $totalAmount
            //     ]);

            DB::commit();
            return response($totalAmount,200);

        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }

    }

    public function del(Request $request){

        DB::beginTransaction();

        try {

            ///1. update detail
            DB::table('finance.gljnldtl')
                ->where('compcode','=',session('compcode'))
                ->where('auditno','=',$request->auditno)
                ->where('lineno_','=',$request->lineno_)
                ->delete();

            ///2. recalculate total amount
            // $totalAmount = DB::table('finance.gljnldtl')
            //     ->where('compcode','=',session('compcode'))
            //     ->where('auditno','=',$request->auditno)
            //     ->where('recstatus','!=','DELETE')
            //     ->sum('amount');

          
            //3. update total amount to header


            // 3. calculate total amount for dr/cr
            $totalAmountDR = DB::table('finance.gljnldtl')
                ->where('compcode','=',session('compcode'))
                ->where('auditno','=',$request->auditno)
                ->where('drcrsign','=','DR')
                ->where('recstatus','!=','DELETE')
                ->sum('amount');

            $totalAmountCR = DB::table('finance.gljnldtl')
                ->where('compcode','=',session('compcode'))
                ->where('auditno','=',$request->auditno)
                ->where('drcrsign','=','CR')
                ->where('recstatus','!=','DELETE')
                ->sum('amount');
            
            //4. update different dr/cr
            DB::table('finance.gljnlhdr')
                ->where('compcode','=',session('compcode'))
                ->where('auditno','=',$request->auditno)
                ->update([
                    'creditAmt' => $totalAmountCR,
                    'debitAmt' => $totalAmountDR,
                    'different' => $totalAmountDR-$totalAmountCR, 
                ]);

            DB::commit();


            $responce = new stdClass();
            $responce->totalAmountDR = $totalAmountDR;
            $responce->totalAmountCR = $totalAmountCR;
            $responce->different = $totalAmountDR-$totalAmountCR;
            $responce->auditno = $request->auditno;

            return json_encode($responce);

        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
        
    }


    public function edit_all(Request $request){

        DB::beginTransaction();

        try {

            foreach ($request->dataobj as $key => $value) {

                ///1. update detail
                DB::table('finance.gljnldtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('auditno','=',$request->auditno)
                    ->where('lineno_','=',$value['lineno_'])
                    ->update([
                        'costcode' => strtoupper($value['costcode']),
                        'glaccount' => strtoupper($value['glaccount']),
                        'description' => strtoupper($value['description']),
                        'drcrsign' => $value['drcrsign'],
                        'amount' => $value['amount'],
                        'upduser' => session('username'), 
                        'upddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                    ]);

                // 3. recalculate total amount for dr/cr
                $totalAmountDR = DB::table('finance.gljnldtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('auditno','=',$request->auditno)
                    ->where('drcrsign','=','DR')
                    ->where('recstatus','!=','DELETE')
                    ->sum('amount');

                $totalAmountCR = DB::table('finance.gljnldtl')
                    ->where('compcode','=',session('compcode'))
                    ->where('auditno','=',$request->auditno)
                    ->where('drcrsign','=','CR')
                    ->where('recstatus','!=','DELETE')
                    ->sum('amount');

                //4. update different dr/cr
                DB::table('finance.gljnlhdr')
                    ->where('compcode','=',session('compcode'))
                    ->where('auditno','=',$request->auditno)
                    ->update([
                        'creditAmt' => $totalAmountCR,
                        'debitAmt' => $totalAmountDR,
                        'different' => $totalAmountDR-$totalAmountCR, 
                    ]);

            }

            DB::commit();
            $responce = new stdClass();
            $responce->totalAmountDR = $totalAmountDR;
            $responce->totalAmountCR = $totalAmountCR;
            $responce->different = $totalAmountDR-$totalAmountCR;

            return json_encode($responce);
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }

    }
}

