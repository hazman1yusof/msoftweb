<?php

namespace App\Http\Controllers\setup;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;

class BedTypeController extends defaultController
{   

    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
        $this->duplicateCode = "bedtype";
    }

    public function show(Request $request)
    {   
        return view('setup.bedtype.bedtype');
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

            $bedtype = DB::table('hisdb.bedtype')
                            ->where('compcode','=',session('compcode'))
                            ->where('bedtype','=',$request->bedtype);

            if($bedtype->exists()){
                throw new \Exception("RECORD DUPLICATE");
            }

            DB::table('hisdb.bedtype')
                ->insert([  
                    'compcode' => session('compcode'),
                    'bedtype' => strtoupper($request->bedtype),
                    'description' => strtoupper($request->description),
                    'bedchgcode' => strtoupper($request->bedchgcode),
                    'lodchgcode' => strtoupper($request->lodchgcode),
                    'recstatus' => 'ACTIVE',
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

            DB::table('hisdb.bedtype')
                ->where('idno','=',$request->idno)
                ->update([  
                    'bedtype' => strtoupper($request->bedtype),
                    'description' => strtoupper($request->description),
                    'bedchgcode' => strtoupper($request->bedchgcode),
                    'lodchgcode' => strtoupper($request->lodchgcode),
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
        DB::table('hisdb.bedtype')
            ->where('idno','=',$request->idno)
            ->update([  
                'recstatus' => 'DEACTIVE',
                'deluser' => strtoupper(session('username')),
                'deldate' => Carbon::now("Asia/Kuala_Lumpur")
            ]);
    }
}