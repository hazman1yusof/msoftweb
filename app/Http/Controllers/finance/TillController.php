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
        return view('finance.GL.region.region');
    }

    public function form(Request $request)
    {   

        switch($request->action){
            case 'default':
                switch($request->oper){
                    case 'edit':
                        return $this->edit($request);
                    case 'add':
                        return $this->add($request);
                    case 'edit':
                        return $this->edit($request);
                    case 'del':
                        return $this->del($request);
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
