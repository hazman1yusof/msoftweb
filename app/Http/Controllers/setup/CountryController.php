<?php

namespace App\Http\Controllers\setup;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;

class CountryController extends defaultController
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
        return view('setup.country.country');
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

            $country = DB::table('hisdb.country')
                            ->where('compcode','=',session('compcode'))
                            ->where('Code','=',$request->Code);

            if($country->exists()){
                throw new \Exception("Record Duplicate");
            }

            DB::table('hisdb.country')
                ->insert([  
                    'compcode' => session('compcode'),
                    'Code' => strtoupper($request->Code),
                    'Description' => strtoupper($request->Description),
                    'recstatus' => strtoupper($request->recstatus),
                    'computerid' => session('computerid'),
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

            DB::table('hisdb.country')
                ->where('idno','=',$request->idno)
                ->update([  
                    'Code' => strtoupper($request->Code),
                    'Description' => strtoupper($request->Description),
                    'recstatus' => strtoupper($request->recstatus),
                    'idno' => $request->idno,
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
        DB::table('hisdb.country')
            ->where('idno','=',$request->idno)
            ->update([  
                'recstatus' => 'DEACTIVE',
                'deluser' => strtoupper(session('username')),
                'deldate' => Carbon::now("Asia/Kuala_Lumpur"),
                'computerid' => session('computerid')
            ]);
    }
}