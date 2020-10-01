<?php

namespace App\Http\Controllers\setup;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;

class ChargeClassController extends defaultController
{   

    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
        $this->duplicateCode = "classcode";
    }

    public function show(Request $request)
    {   
        return view('setup.chargeclass.chargeclass');
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

            $chgclass = DB::table('hisdb.chgclass')
                            ->where('classcode','=',$request->classcode);

            if($chgclass->exists()){
                throw new \Exception("record duplicate");
            }

            DB::table('hisdb.chgclass')
                ->insert([  
                    'compcode' => session('compcode'),
                    'classcode' => strtoupper($request->classcode),
                    'description' => strtoupper($request->description),
                    'classlevel' => strtoupper($request->classlevel),
                    'recstatus' => strtoupper($request->recstatus),
                    //'idno' => strtoupper($request->idno),
                    'lastcomputerid' => strtoupper($request->lastcomputerid),
                    'lastipaddress' => strtoupper($request->lastipaddress),
                    'lastuser' => session('username'),
                    'lastupdate' => Carbon::now("Asia/Kuala_Lumpur")
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

            DB::table('hisdb.chgclass')
                ->where('idno','=',$request->idno)
                ->update([  
                    'classcode' => strtoupper($request->classcode),
                    'description' => strtoupper($request->description),
                    'classlevel' => strtoupper($request->classlevel),
                    'recstatus' => strtoupper($request->recstatus),
                    'idno' => strtoupper($request->idno),
                    'lastcomputerid' => strtoupper($request->lastcomputerid),
                    'lastipaddress' => strtoupper($request->lastipaddress),
                    'lastuser' => session('username'),
                    'lastupdate' => Carbon::now("Asia/Kuala_Lumpur")
                ]); 

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function del(Request $request){
        DB::table('hisdb.chgclass')
            ->where('idno','=',$request->idno)
            ->update([  
                'recstatus' => 'DEACTIVE',
                'lastuser' => session('username'),
                'lastupdate' => Carbon::now("Asia/Kuala_Lumpur")
            ]);
    }
}