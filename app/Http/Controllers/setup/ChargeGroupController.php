<?php

namespace App\Http\Controllers\setup;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;

class ChargeGroupController extends defaultController
{   

    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
        $this->duplicateCode = "grpcode";
    }

    public function show(Request $request)
    {   
        return view('setup.chargegroup.chargegroup');
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

            $chggroup = DB::table('hisdb.chggroup')
                            ->where('compcode','=',session('compcode'))
                            ->where('grpcode','=',$request->grpcode);

            if($chggroup->exists()){
                throw new \Exception("record duplicate");
            }

            DB::table('hisdb.chggroup')
                ->insert([  
                    'compcode' => session('compcode'),
                    'grpcode' => strtoupper($request->grpcode),
                    'description' => strtoupper($request->description),
                    'seqno' => strtoupper($request->seqno),
                    'recstatus' => strtoupper($request->recstatus),
                    //'idno' => strtoupper($request->idno),
                    'computerid' => session('computerid'),
                    'adduser' => strtoupper(session('username')),
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

            DB::table('hisdb.chggroup')
                ->where('idno','=',$request->idno)
                ->update([  
                    'grpcode' => strtoupper($request->grpcode),
                    'description' => strtoupper($request->description),
                    'seqno' => strtoupper($request->seqno),
                    'recstatus' => strtoupper($request->recstatus),
                    'idno' => strtoupper($request->idno),
                    'lastcomputerid' => session('computerid'),
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
        DB::table('hisdb.chggroup')
            ->where('idno','=',$request->idno)
            ->update([  
                'recstatus' => 'DEACTIVE',
                'deluser' => strtoupper(session('username')),
                'deldate' => Carbon::now("Asia/Kuala_Lumpur"),
                'computerid' => session('computerid')
            ]);
    }
}