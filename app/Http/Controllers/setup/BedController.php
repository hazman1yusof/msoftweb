<?php

namespace App\Http\Controllers\setup;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;

class BedController extends defaultController
{   

    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
        $this->duplicateCode = "bednum";
    }

    public function show(Request $request)
    {   
        return view('setup.bed.bed');
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

            $bednum = DB::table('hisdb.bed')
                            ->where('bednum','=',$request->b_bednum);

            if($bednum->exists()){
                throw new \Exception("record duplicate");
            }

            DB::table('hisdb.bed')
                ->insert([  
                    'compcode' => session('compcode'),
                    'bednum' => strtoupper($request->b_bednum),
                    'bedtype' => strtoupper($request->b_bedtype),  
                    'room' => strtoupper($request->c_room),  
                    'ward' => strtoupper($request->c_ward),  
                    'roomstatus' => strtoupper($request->c_roomstatus),  
                    'mrn' => strtoupper($request->c_mrn),  
                    'episno' => strtoupper($request->c_episno),  
                    //'patname' => strtoupper($request-c_>patname),  

                    //'description' => strtoupper($request->description),
                    'recstatus' => strtoupper($request->b_recstatus),
                    //'idno' => strtoupper($request->idno),
                    'lastcomputerid' => strtoupper($request->lastcomputerid),
                    'lastipaddress' => strtoupper($request->lastipaddress),
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur")
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

            DB::table('hisdb.bed')
                ->where('idno','=',$request->b_idno)
                ->update([  
                    'bednum' => strtoupper($request->b_bednum),
                    'bedtype' => strtoupper($request->b_bedtype),  
                    'room' => strtoupper($request->c_room),  
                    'ward' => strtoupper($request->c_ward),  
                    'roomstatus' => strtoupper($request->c_roomstatus),  
                    'mrn' => strtoupper($request->c_mrn),  
                    'episno' => strtoupper($request->c_episno),  
                    //'patname' => strtoupper($request->c_patname),  

                    //'description' => strtoupper($request->description),
                    'recstatus' => strtoupper($request->b_recstatus),
                    //'idno' => strtoupper($request->idno),
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
        DB::table('hisdb.bed')
            ->where('idno','=',$request->b_idno)
            ->update([  
                'recstatus' => 'D',
                'deluser' => session('username'),
                'deldate' => Carbon::now("Asia/Kuala_Lumpur")
            ]);
    }
}