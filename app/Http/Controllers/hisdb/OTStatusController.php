<?php

namespace App\Http\Controllers\hisdb;

use Illuminate\Http\Request;
use stdClass;
use App\User;
use DB;
use Carbon\Carbon;
use Auth;
use Session;
use App\Http\Controllers\defaultController;

class OTStatusController extends defaultController
{   

    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
        // $this->duplicateCode = "grpcode";
    }

    public function show(Request $request)
    {   
        return view('hisdb.ot_status.ot_status');
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

            // $chggroup = DB::table('hisdb.chggroup')
            //                 ->where('grpcode','=',$request->grpcode);

            // if($chggroup->exists()){
            //     throw new \Exception("record duplicate");
            // }

            $idno = DB::table('hisdb.otstatus')
                ->insertGetId([  
                    'compcode' => session('compcode'),
                    'code' => $request->code,
                    'description' => strtoupper($request->description),
                    'recstatus' => 'ACTIVE',
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur")
                ]);

            DB::table('hisdb.otstatus')
                ->where('idno',$idno)
                ->update([
                    'code' => $idno
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

            DB::table('hisdb.otstatus')
                ->where('idno','=',$request->idno)
                ->update([  
                    'description' => strtoupper($request->description),
                    'recstatus' => 'ACTIVE',
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

        DB::table('hisdb.otstatus')
            ->where('idno','=',$request->idno)
            ->update([  
                'recstatus' => 'DEACTIVE',
                'deluser' => session('username'),
                'deldate' => Carbon::now("Asia/Kuala_Lumpur")
            ]);

    }
}