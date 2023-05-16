<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;

class UnitController extends defaultController
{   
    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
        $this->duplicateCode = "sectorcode";
    }

    public function show(Request $request)
    {   
        return view('finance.GL.unit.unit');
        
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

            $sector = DB::table('sysdb.sector')
                            ->where('compcode','=',session('compcode'))
                            ->where('sectorcode','=',$request->sectorcode);

            if($sector->exists()){
                throw new \Exception("Record Duplicate");
            }

            DB::table('sysdb.sector')
                ->insert([  
                    'compcode' => session('compcode'),
                    'sectorcode' => strtoupper($request->sectorcode),
                    'description' => strtoupper($request->description),
                    'regioncode' => strtoupper($request->regioncode),
                    'recstatus' => strtoupper($request->recstatus),
                    //'idno' => strtoupper($request->idno),
                    'computerid' => session('computerid'),
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

            DB::table('sysdb.sector')
                ->where('idno','=',$request->idno)
                ->update([  
                    'sectorcode' => strtoupper($request->sectorcode),
                    'description' => strtoupper($request->description),
                    'regioncode' => strtoupper($request->regioncode),
                    'recstatus' => strtoupper($request->recstatus),
                    'idno' => strtoupper($request->idno),
                    'lastcomputerid' => session('computerid'),
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
        DB::table('sysdb.sector')
            ->where('idno','=',$request->idno)
            ->update([  
                'recstatus' => 'DEACTIVE',
                'deluser' => session('username'),
                'deldate' => Carbon::now("Asia/Kuala_Lumpur"),
                'computerid' => session('computerid')
            ]);
    }
}