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
                            ->where('startno','=',$request->startno);

            if($chqreg->exists()){
                throw new \Exception("record duplicate");
            }

            DB::table('finance.chqreg')
                ->insert([  
                    'compcode' => session('compcode'),
                    'bankcode' => strtoupper($request->bankcode),
                    'startno' => strtoupper($request->startno),
                    'endno' => $request->endno,
                    'cheqqty' => $request->endno -$request->startno+1,
                    'recstatus' => 'ACTIVE',
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur")
                ]);

            $bankcode = $request->bankcode;
            $startno = $request->startno;
            $endno = $request->endno;

            DB::table('finance.chqtran')
                    ->insert([  
                        'compcode' => session('compcode'),
                        'bankcode' => strtoupper($request->bankcode),
                        'cheqno' => $startno++,
                        'recstatus' => 'OPEN',
                        'adduser' => session('username'),
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur")
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

            DB::table('material.category')
                ->where('idno','=',$request->idno)
                ->update([  
                    'catcode' => strtoupper($request->catcode),
                    'description' => strtoupper($request->description),
                    'expacct' => strtoupper($request->expacct),
                    'povalidate' => strtoupper($request->povalidate),
                    'recstatus' => strtoupper($request->recstatus),
                    'idno' => strtoupper($request->idno),
                    'lastcomputerid' => strtoupper($request->lastcomputerid),
                    'lastipaddress' => strtoupper($request->lastipaddress),
                    'upduser' => session('username'),
                    'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                ]); 

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

             return response($e->getMessage(), 500);
        }
    }

    public function del(Request $request){
        DB::table('material.category')
            ->where('idno','=',$request->idno)
            ->update([  
                'recstatus' => 'D',
                'deluser' => session('username'),
                'deldate' => Carbon::now("Asia/Kuala_Lumpur")
            ]);
    }
}