<?php

namespace App\Http\Controllers\test;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Auth;
use Carbon\Carbon;

class TestController extends defaultController
{   

    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
        $this->duplicateCode = "bloodcode";
    }

    public function show(Request $request)
    {   
        // dd($request);
        return view('test.testexpdateloop');
    }

    public function form(Request $request)
    {   
        $trandate = $request->date;
        $quan = $request->quan;


        $expdate_obj = DB::table('test.test')
                        ->where('expdate','>',$trandate)
                        ->orderBy('expdate', 'asc')
                        ->get();

        foreach ($expdate_obj as $value) {
            $curr_quan = $value->quan;
            if($quan-$curr_quan>0){

                $quan = $quan-$curr_quan;
                DB::table('test.test')
                    ->where('id','=',$value->id)
                    ->update([
                        'quan' => '0'
                    ]);
            
            }else{

                $curr_quan = $curr_quan-$quan;
                DB::table('test.test')
                    ->where('id','=',$value->id)
                    ->update([
                        'quan' => $curr_quan
                    ]);
                    
                break;
            }


        }


    }

}