<?php

namespace App\Http\Controllers\setup;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;

class PostcodeController extends defaultController
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
        return view('setup.postcode.postcode');
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

            $postcode = DB::table('hisdb.postcode')
                            ->where('pc_postcode','=',$request->pc_postcode);

            if($postcode->exists()){
                throw new \Exception("record duplicate");
            }

            DB::table('hisdb.postcode')
                ->insert([  
                    'pc_compcode' => session('pc_compcode'),
                    'pc_postcode' => strtoupper($request->pc_postcode),
                    'pc_place_name' => strtoupper($request->pc_place_name),
                    'pc_recstatus' => strtoupper($request->pc_recstatus),
                    'cn_Code' => strtoupper($request->cn_Code),
                    'st_StateCode' => strtoupper($request->st_StateCode),
                    'pc_district' => strtoupper($request->pc_district),
                    //'effectivedate' => strtoupper($request->effectivedate),
                    'pc_idno' => strtoupper($request->pc_idno),
                    // 'discharge' => strtoupper($request->discharge),
                    // 'discharge' => strtoupper($request->discharge),

                    'pc_lastuser' => session('username'),
                    'pc_lastupdate' => Carbon::now("Asia/Kuala_Lumpur")
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

            DB::table('hisdb.postcode')
                ->where('pc_idno','=',$request->pc_idno)
                ->update([  
                    'pc_compcode' => session('pc_compcode'),
                    'pc_postcode' => strtoupper($request->pc_postcode),
                    'pc_place_name' => strtoupper($request->pc_place_name),
                    'pc_recstatus' => strtoupper($request->pc_recstatus),
                    'cn_Code' => strtoupper($request->cn_Code),
                    'st_StateCode' => strtoupper($request->st_StateCode),
                    'pc_district' => strtoupper($request->pc_district),
                    //'effectivedate' => strtoupper($request->effectivedate),
                    'pc_idno' => strtoupper($request->pc_idno),
                    // 'discharge' => strtoupper($request->discharge),
                    // 'discharge' => strtoupper($request->discharge),

                    'pc_lastuser' => session('username'),
                    'pc_lastupdate' => Carbon::now("Asia/Kuala_Lumpur")
                ]); 

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }
    }

    public function del(Request $request){
        DB::table('hisdb.postcode')
            ->where('pc_idno','=',$request->pc_idno)
            ->update([  
                'pc_recstatus' => 'D',
                'pc_lastuser' => session('username'),
                'pc_lastupdate' => Carbon::now("Asia/Kuala_Lumpur")
            ]);
    }
}