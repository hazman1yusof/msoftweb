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
                            ->where('postcode','=',$request->postcode);

            if($postcode->exists()){
                throw new \Exception("record duplicate");
            }

            DB::table('hisdb.postcode')
                ->insert([  
                    'compcode' => session('compcode'),
                    'postcode' => strtoupper($request->postcode),
                    'place_name' => strtoupper($request->place_name),
                    'recstatus' => strtoupper($request->recstatus),
                    'countrycode' => strtoupper($request->countrycode),
                    'statecode' => strtoupper($request->statecode),
                    'district' => strtoupper($request->district),
                    //'effectivedate' => strtoupper($request->effectivedate),
                    'idno' => strtoupper($request->idno),
                    'recstatus' => strtoupper($request->recstatus),
                    // 'discharge' => strtoupper($request->discharge),
                    // 'discharge' => strtoupper($request->discharge),

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

            DB::table('hisdb.postcode')
                ->where('idno','=',$request->idno)
                ->update([  
                    'compcode' => session('compcode'),
                    'postcode' => strtoupper($request->postcode),
                    'place_name' => strtoupper($request->place_name),
                    'recstatus' => strtoupper($request->recstatus),
                    'countrycode' => strtoupper($request->countrycode),
                    'statecode' => strtoupper($request->statecode),
                    'district' => strtoupper($request->district),
                    //'effectivedate' => strtoupper($request->effectivedate),
                    'idno' => strtoupper($request->idno),
                    'recstatus' => strtoupper($request->recstatus),
                    // 'discharge' => strtoupper($request->discharge),
                    // 'discharge' => strtoupper($request->discharge),

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
        DB::table('hisdb.postcode')
            ->where('idno','=',$request->idno)
            ->update([  
                'recstatus' => 'D',
                'lastuser' => session('username'),
                'lastupdate' => Carbon::now("Asia/Kuala_Lumpur")
            ]);
    }
}