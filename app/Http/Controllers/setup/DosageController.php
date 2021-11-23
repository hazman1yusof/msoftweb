<?php

namespace App\Http\Controllers\setup;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;

class DosageController extends defaultController
{   

    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
        $this->duplicateCode = "dosecode";
    }

    public function show(Request $request)
    {   
        return view('setup.dosage.dosage');
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

            $dosage = DB::table('hisdb.dose')
                            ->where('dosecode','=',$request->dosecode);

            if($dosage->exists()){
                throw new \Exception("Record Duplicate");
            }

            DB::table('hisdb.dose')
                ->insert([  
                    'compcode' => session('compcode'),
                    'dosecode' => strtoupper($request->dosecode),
                    'dosedesc' => strtoupper($request->dosedesc),
                    'convfactor' => strtoupper($request->convfactor),
                    'recstatus' => strtoupper($request->recstatus),
                    //'idno' => strtoupper($request->idno),
                    'lastcomputerid' => strtoupper($request->lastcomputerid),
                    'lastipaddress' => strtoupper($request->lastipaddress),
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur")
                ]);

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

            DB::table('hisdb.dose')
                ->where('idno','=',$request->idno)
                ->update([  
                    'dosecode' => strtoupper($request->dosecode),
                    'dosedesc' => strtoupper($request->dosedesc),
                    'convfactor' => strtoupper($request->convfactor),
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
        DB::table('hisdb.dose')
            ->where('idno','=',$request->idno)
            ->update([  
                'recstatus' => 'DEACTIVE',
                'lastuser' => session('username'),
                'lastupdate' => Carbon::now("Asia/Kuala_Lumpur")
            ]);
    }
}