<?php

namespace App\Http\Controllers\setup;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;

class mmaController extends defaultController
{   

    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        return view('setup.mma.mma');
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
                throw new \Exception("record duplicate");
            }

            DB::table('hisdb.mmamaster')
                ->insert([  
                    'compcode' => session('compcode'),
                    'mmacode' => strtoupper($request->mmacode),
                    'description' => strtoupper($request->Description),
                    "version" => $type->pvalue1,
                    'recstatus' => strtoupper($request->recstatus),
                    //'idno' => strtoupper($request->idno),
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

            DB::table('hisdb.mmamaster')
                ->where('idno','=',$request->idno)
                ->update([  
                    'Code' => strtoupper($request->Code),
                    'Description' => strtoupper($request->Description),
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

            return response('Error'.$e, 500);
        }
    }

    public function del(Request $request){
        DB::table('hisdb.mmamaster')
            ->where('idno','=',$request->idno)
            ->update([  
                'recstatus' => 'D',
                'deluser' => session('username'),
                'deldate' => Carbon::now("Asia/Kuala_Lumpur")
            ]);
    }
}
