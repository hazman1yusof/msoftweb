<?php

namespace App\Http\Controllers\setup;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;

class mmamaintenanceController extends defaultController
{   
    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
        $this->duplicateCode = "mmacode";
    }

    public function show(Request $request)
    {   
        return view('setup.mmamaintenance.mmamaintenance');
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

            $mmamaster = DB::table('hisdb.mmamaster')
                            ->where('mmacode','=',$request->mmacode);

            $type = DB::table('sysdb.sysparam')
                            ->where('source','=',"MR")
                            ->where('trantype','=',"MMAVER")
                            ->first();

            if($mmamaster->exists()){
                throw new \Exception("Record Duplicate");
            }

            DB::table('hisdb.mmamaster')
                ->insert([  
                    'compcode' => session('compcode'),
                    'mmacode' => strtoupper($request->mmacode),
                    'description' => strtoupper($request->description),
                    "version" => $type->pvalue1,
                    'recstatus' => strtoupper($request->recstatus),
                    //'idno' => strtoupper($request->idno),
                    'lastcomputerid' => strtoupper($request->lastcomputerid),
                    'lastipaddress' => strtoupper($request->lastipaddress),
                    'adduser' => strtoupper(session('username')),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur")
                ]);

             DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            $responce = new stdClass();
            $responce->errormsg = $e->getMessage();
            $responce->request = $_REQUEST;

            return response(json_encode($responce), 500);        }
    }

    public function edit(Request $request){
        
        DB::beginTransaction();
        try {

            $type = DB::table('sysdb.sysparam')
            ->where('source','=',"MR")
            ->where('trantype','=',"MMAVER")
            ->first();

            DB::table('hisdb.mmamaster')
                ->where('idno','=',$request->idno)
                ->update([  
                    'mmacode' => strtoupper($request->mmacode),
                    'description' => strtoupper($request->description),
                    'version' => $type->pvalue1,
                    'recstatus' => strtoupper($request->recstatus),
                    'idno' => $request->idno,
                    'lastcomputerid' => strtoupper($request->lastcomputerid),
                    'lastipaddress' => strtoupper($request->lastipaddress),
                    'lastuser' => strtoupper(session('username')),
                    'lastupdate' => Carbon::now("Asia/Kuala_Lumpur")
                ]); 

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function del(Request $request){
        DB::table('hisdb.mmamaster')
            ->where('idno','=',$request->idno)
            ->update([  
                'recstatus' => 'DEACTIVE',
                'deluser' => strtoupper(session('username')),
                'deldate' => Carbon::now("Asia/Kuala_Lumpur")
            ]);
    }
}