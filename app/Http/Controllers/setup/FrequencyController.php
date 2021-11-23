<?php

namespace App\Http\Controllers\setup;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;

class FrequencyController extends defaultController
{   

    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
        $this->duplicateCode = "freqcode";
    }

    public function show(Request $request)
    {   
        return view('setup.frequency.frequency');
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

            $frequency = DB::table('hisdb.freq')
                            ->where('freqcode','=',$request->freqcode);

            if($frequency->exists()){
                throw new \Exception("Record Duplicate");
            }

            DB::table('hisdb.freq')
                ->insert([  
                    'compcode' => session('compcode'),
                    'freqcode' => strtoupper($request->freqcode),
                    'freqdesc' => strtoupper($request->freqdesc),
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

            DB::table('hisdb.freq')
                ->where('idno','=',$request->idno)
                ->update([  
                    'freqcode' => strtoupper($request->freqcode),
                    'freqdesc' => strtoupper($request->freqdesc),
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
        DB::table('hisdb.freq')
            ->where('idno','=',$request->idno)
            ->update([  
                'recstatus' => 'DEACTIVE',
                'deluser' => session('username'),
                'deldate' => Carbon::now("Asia/Kuala_Lumpur")
            ]);
    }
}