<?php

namespace App\Http\Controllers\setup;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;

class WardController extends defaultController
{   

    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
        $this->duplicateCode = "warddept";
    }

    public function show(Request $request)
    {   
        return view('setup.ward.ward');
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

            $warddept = DB::table('sysdb.department')
                            ->where('warddept','=',$request->warddept);

            if($warddept->exists()){
                throw new \Exception("Record Duplicate");
            }

            DB::table('sysdb.department')
                ->insert([  
                    'compcode' => session('compcode'),
                    'warddept' => strtoupper($request->warddept),
                    'description' => strtoupper($request->description),
                    'sector' => strtoupper($request->sector),
                    'region' => strtoupper($request->region),
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

            DB::table('sysdb.department')
                ->where('idno','=',$request->idno)
                ->update([  
                    'warddept' => strtoupper($request->warddept),
                    'description' => strtoupper($request->description),
                    'sector' => strtoupper($request->sector),
                    'region' => strtoupper($request->region),
                    'recstatus' => strtoupper($request->recstatus),
                    'idno' => strtoupper($request->idno),
                    'lastcomputerid' => strtoupper($request->lastcomputerid),
                    'lastipaddress' => strtoupper($request->lastipaddress),
                    'upduser' => strtoupper(session('username')),
                    'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                ]); 

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function del(Request $request){
        DB::table('sysdb.department')
            ->where('idno','=',$request->idno)
            ->update([  
                'recstatus' => 'DEACTIVE',
                'deluser' => strtoupper(session('username')),
                'deldate' => Carbon::now("Asia/Kuala_Lumpur")
            ]);
    }
}