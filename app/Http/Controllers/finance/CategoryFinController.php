<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;


class CategoryFinController extends defaultController
{   
    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
        $this->duplicateCode = "catcode";
    }

    public function show(Request $request)
    {   
        return view('material.categoryFIN.category');
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

            $category = DB::table('material.category')
                            ->where('catcode','=',$request->catcode);

            if($category->exists()){
                throw new \Exception("record duplicate");
            }

            DB::table('material.category')
                ->insert([  
                    'compcode' => session('compcode'),
                    'source' => 'CR',
                    'cattype' => 'OTHER',
                    'catcode' => strtoupper($request->catcode),
                    'description' => strtoupper($request->description),
                    'expacct' => strtoupper($request->expacct),
                    'povalidate' => strtoupper($request->povalidate),
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

            DB::table('material.category')
                ->where('idno','=',$request->idno)
                ->update([  
                    'catcode' => strtoupper($request->catcode),
                    'description' => strtoupper($request->description),
                    'expacct' => strtoupper($request->expacct),
                    'povalidate' => strtoupper($request->povalidate),
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
        DB::table('material.category')
            ->where('idno','=',$request->idno)
            ->update([  
                'recstatus' => 'D',
                'deluser' => session('username'),
                'deldate' => Carbon::now("Asia/Kuala_Lumpur")
            ]);
    }
}
