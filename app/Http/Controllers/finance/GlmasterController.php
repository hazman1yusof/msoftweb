<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;

class GlmasterController extends defaultController
{   
    var $table;
    var $duplicateCode;


    public function __construct()
    {
        $this->middleware('auth');
        $this->duplicateCode = "glaccno";
    }

    public function show(Request $request)
    {   
        return view('finance.GL.GLmaster.GLmaster');
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

            $glmasref = DB::table('finance.glmasref')
                            ->where('glaccno','=',$request->glaccno);

            if($glmasref->exists()){
                throw new \Exception("record duplicate");
            }

            DB::table('finance.glmasref')
                ->insert([  
                    'compcode' => session('compcode'),
                    'glaccno' => strtoupper($request->glaccno),
                    'description' => strtoupper($request->description),
                    'accgroup' => strtoupper($request->accgroup),
                    'recstatus' => strtoupper($request->recstatus),
                    //'idno' => strtoupper($request->idno),
                    'lastcomputerid' => strtoupper($request->lastcomputerid),
                    'lastipaddress' => strtoupper($request->lastipaddress),
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

            DB::table('finance.glmasref')
                ->where('idno','=',$request->idno)
                ->update([  
                    'glaccno' => strtoupper($request->glaccno),
                    'description' => strtoupper($request->description),
                    'accgroup' => strtoupper($request->accgroup),
                    'recstatus' => strtoupper($request->recstatus),
                    'idno' => strtoupper($request->idno),
                    'lastcomputerid' => strtoupper($request->lastcomputerid),
                    'lastipaddress' => strtoupper($request->lastipaddress),
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
        DB::table('finance.glmasref')
            ->where('idno','=',$request->idno)
            ->update([  
                'recstatus' => 'D',
                'lastuser' => session('username'),
                'lastupdate' => Carbon::now("Asia/Kuala_Lumpur")
            ]);
    }
}