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
        $this->duplicateCode = "Code";
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
                throw new \Exception("record duplicate");
            }

            DB::table('hisdb.admissrc')
                ->insert([  
                    'compcode' => session('compcode'),
                    'admsrccode' => $request->admsrccode,
                    'description' => $request->description,
                    'addr1' => $request->addr1,
                    'addr2' => $request->addr2,
                    'addr3' => $request->addr3,
                    'addr4' => $request->addr4,
                    'telno' => $request->telno,
                    'email' => $request->email,
                    'type' => $request->type,
                    'lastuser' => session('username'),
                    'lastupdate' => Carbon::now("Asia/Kuala_Lumpur")
                ]);

             DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }
    }

    public function edit(Request $request){
        
        DB::beginTransaction();
        try {

            DB::table('hisdb.admissrc')
                ->where('idno','=',$request->idno)
                ->update([  
                    'admsrccode' => $request->admsrccode,
                    'description' => $request->description,
                    'addr1' => $request->addr1,
                    'addr2' => $request->addr2,
                    'addr3' => $request->addr3,
                    'addr4' => $request->addr4,
                    'telno' => $request->telno,
                    'email' => $request->email,
                    'type' => $request->type,
                    'lastuser' => session('username'),
                    'lastupdate' => Carbon::now("Asia/Kuala_Lumpur")
                ]); 

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }
    }

    public function del(Request $request){
        DB::table('hisdb.admissrc')
            ->where('idno','=',$request->idno)
            ->update([  
                'recstatus' => 'D',
                'lastuser' => session('username'),
                'lastupdate' => Carbon::now("Asia/Kuala_Lumpur")
            ]);
    }
}