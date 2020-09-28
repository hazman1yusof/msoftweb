<?php

namespace App\Http\Controllers\setup;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;

class AdmisSrcController extends defaultController
{   

    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
        $this->duplicateCode = "admsrccode";
    }

    public function show(Request $request)
    {   
        return view('setup.admissrc.admissrc');
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

            $admissrc = DB::table('hisdb.admissrc')
                            ->where('admsrccode','=',$request->admsrccode);

            if($admissrc->exists()){
                throw new \Exception("Record Duplicate");
            }

            DB::table('hisdb.admissrc')
                ->insert([  
                    'compcode' => session('compcode'),
                    'admsrccode' => strtoupper($request->admsrccode),
                    'description' => strtoupper($request->description),
                    'recstatus' => strtoupper($request->recstatus),
                    'addr1' => strtoupper($request->addr1),
                    'addr2' => strtoupper($request->addr2),
                    'addr3' => strtoupper($request->addr3),
                    'addr4' => strtoupper($request->addr4),
                    'telno' => strtoupper($request->telno),
                    'email' => strtoupper($request->email),
                    'type' => $request->type,
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

            return response(json_encode($responce), 500);
        }
    }

    public function edit(Request $request){
        
        DB::beginTransaction();
        try {

            DB::table('hisdb.admissrc')
                ->where('idno','=',$request->idno)
                ->update([  
                    'admsrccode' => strtoupper($request->admsrccode),
                    'description' => strtoupper($request->description),
                    'recstatus' => strtoupper($request->recstatus),
                    'addr1' => strtoupper($request->addr1),
                    'addr2' => strtoupper($request->addr2),
                    'addr3' => strtoupper($request->addr3),
                    'addr4' => strtoupper($request->addr4),
                    'telno' => strtoupper($request->telno),
                    'email' => strtoupper($request->email),
                    'type' => $request->type,
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
        DB::table('hisdb.admissrc')
            ->where('idno','=',$request->idno)
            ->update([  
                'recstatus' => 'DEACTIVE',
                'deluser' => strtoupper(session('username')),
                'deldate' => Carbon::now("Asia/Kuala_Lumpur")
            ]);
    }
}