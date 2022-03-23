<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;

class TillController extends defaultController
{   

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        return view('finance.AR.till.till');
    }

    public function form(Request $request)
    {   

        switch($request->action){
            case 'default':
                switch($request->oper){
                    case 'edit':
                        return $this->edit($request);break;
                    case 'add':
                        return $this->add($request);break;
                    case 'edit':
                        return $this->edit($request);break;
                    case 'del':
                        return $this->del($request);break;
                    default:
                        return 'error happen..';
                }
            case 'use_till':
                return $this->use_till($request);
        }
    }

    public function use_till(Request $request){
        dd('use_till');
        DB::beginTransaction();
        try {


             DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function add(Request $request){

        DB::beginTransaction();
        try {

            DB::table('debtor.till')
                ->insert([
                    'compcode' => session('compcode'),
                    'adduser' => session('username'), 
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                    // 'unit' => session('unit'),
                    'tillcode' => $request->tillcode, 
                    'description' => $request->description, 
                    'dept' => $request->dept, 
                    'effectdate' => $request->effectdate, 
                    'defopenamt' => $request->defopenamt, 
                    'tillstatus' => $request->tillstatus, 
                    'lastrcnumber' => $request->lastrcnumber, 
                    'lastrefundno' => $request->lastrefundno, 
                    'lastcrnoteno' => $request->lastcrnoteno, 
                    'lastinnumber' => $request->lastinnumber,
                ]);

             DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function edit(Request $request){
        
        DB::beginTransaction();
        try {

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function del(Request $request){
        DB::beginTransaction();
        try {

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }
}
