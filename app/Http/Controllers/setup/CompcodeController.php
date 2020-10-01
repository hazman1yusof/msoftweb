<?php
namespace App\Http\Controllers\setup;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;

class CompcodeController extends defaultController
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
        return view('setup.compcode.compcode');
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

            $compcode = DB::table('sysdb.company')
                            ->where('compcode','=',$request->compcode);

            if($compcode->exists()){
                throw new \Exception("Record Duplicate");
            }

            DB::table('sysdb.company')
                ->insert([  
                    'compcode' => session('compcode'),
                    'name' => strtoupper($request->name),
                    'address1' => strtoupper($request->address1),
                    'bmppath1' => strtoupper($request->bmppath1),
                    'logo1' => strtoupper($request->logo1),
                    'recstatus' => strtoupper($request->recstatus),
                    //'idno' => strtoupper($request->idno),
                   // 'lastcomputerid' => strtoupper($request->lastcomputerid),
//'lastipaddress' => strtoupper($request->lastipaddress),
                    //'lastuser' => strtoupper(session('username')),
                    //'lastupdate' => Carbon::now("Asia/Kuala_Lumpur")
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

            DB::table('sysdb.company')
                ->where('compcode','=',$request->compcode)
                ->update([  
                    'name' => strtoupper($request->name),
                    'address1' => strtoupper($request->address1),
                    'bmppath1' => strtoupper($request->bmppath1),
                    'logo1' => strtoupper($request->logo1),
                    'recstatus' => strtoupper($request->recstatus),
                    'compcode' => strtoupper($request->compcode),
                    'lastcomputerid' => strtoupper($request->lastcomputerid),
                    //'lastipaddress' => strtoupper($request->lastipaddress),
                    //'lastuser' => strtoupper(session('username')),
                    //'lastupdate' => Carbon::now("Asia/Kuala_Lumpur")
                ]); 

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }
    }

    public function del(Request $request){
        DB::table('sysdb.company')
            ->where('compcode','=',$request->compcode)
            ->update([  
                'recstatus' => 'DEACTIVE',
                'deluser' => strtoupper(session('username')),
                'deldate' => Carbon::now("Asia/Kuala_Lumpur")
            ]);
    }
}