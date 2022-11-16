<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use Auth;

class TillController extends defaultController
{   
    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
        $this->duplicateCode = "tillcode";
    }

    public function show(Request $request)
    {   
        return view('finance.AR.till.till');
    }

    public function till_close(Request $request)
    {   
        return view('finance.AR.till.till_close');
    }

    public function form(Request $request)
    {   

        switch($request->action){
            case 'default':
                switch($request->oper){
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
        DB::beginTransaction();
        try {

            DB::table('debtor.till')
                ->where('tillcode','=',$request->tillcode)
                ->update([
                    'compcode' => session('compcode'), 
                    'tillstatus' => 'O', 
                    'dept' => auth()->user()->deptcode,
                    'upduser' => session('username'),
                    'lastuser' => session('username'),
                    'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                ]);

            DB::table('debtor.tilldetl')
                ->insert([
                    'compcode' => session('compcode'), 
                    'tillcode' => $request->tillcode,
                    'cashamt' => $request->openamt,
                    'cashier' => session('username'),
                    'opendate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'opentime' => Carbon::now("Asia/Kuala_Lumpur")
                ]);


            $request->session()->put('till', $request->tillcode);

             DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function add(Request $request){

        DB::beginTransaction();
        try {

            $tillcode = DB::table('debtor.till')
                            ->where('tillcode','=',$request->tillcode);

            if($tillcode->exists()){
                throw new \Exception("Record Duplicate");
            }
            DB::table('debtor.till')
                ->insert([
                    'compcode' => session('compcode'),
                    'adduser' => session('username'), 
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"), 
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
            
            DB::table('debtor.till')
                ->where('tillcode','=',$request->tillcode)
                ->update([
                    'description' => $request->description, 
                    'dept' => $request->dept, 
                    'effectdate' => $request->effectdate, 
                    'defopenamt' => $request->defopenamt, 
                    'tillstatus' => $request->tillstatus, 
                    'lastrcnumber' => $request->lastrcnumber, 
                    'lastrefundno' => $request->lastrefundno, 
                    'lastcrnoteno' => $request->lastcrnoteno, 
                    'lastinnumber' => $request->lastinnumber,
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
        DB::table('debtor.till')
            ->where('tillcode','=',$request->tillcode)
            ->update([  
                'recstatus' => 'DEACTIVE',
                'deluser' => strtoupper(session('username')),
                'deldate' => Carbon::now("Asia/Kuala_Lumpur")
            ]);
    }
}
