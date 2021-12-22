<?php

namespace App\Http\Controllers\hisdb;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class DieteticCareNotesController extends defaultController
{   

    var $table;
    var $duplicateCode;

    public function __construct()
    {   
        $this->middleware('auth');
        // $this->duplicateCode = "chgtype";
    }

    public function show(Request $request)
    {   
        return view('hisdb.dieteticCareNotes.dieteticCareNotes');
    }

    public function form(Request $request)
    {   
        DB::enableQueryLog();
        switch($request->action){
            case 'save_table_dieteticCareNotes':

                switch($request->oper){
                    case 'add':
                        return $this->add($request);
                    case 'edit':
                        return $this->edit($request);
                    default:
                        return 'error happen..';
                }

                case 'get_table_dieteticCareNotes':
                    return $this->get_table_dieteticCareNotes($request);

            default:
                return 'error happen..';
        }
    }

    public function add(Request $request){

        DB::beginTransaction();

        try {

        } catch (\Exception $e) {
            DB::rollback();

            return response('Error DB rollback!'.$e, 500);
        }
    }

    public function edit(Request $request){

        DB::beginTransaction();

        try {

        } catch (\Exception $e) {
            DB::rollback();

            return response('Error DB rollback!'.$e, 500);
        }
    }

    public function get_table_dieteticCareNotes(Request $request){
        
        // $an_pathistory_obj = DB::table('hisdb.pathistory')
        //             ->where('compcode','=',session('compcode'))
        //             ->where('mrn','=',$request->mrn);

        // $an_pathealth_obj = DB::table('hisdb.pathealth')
        //             ->where('compcode','=',session('compcode'))
        //             ->where('mrn','=',$request->mrn)
        //             ->where('episno','=',$request->episno);

        $responce = new stdClass();

        // if($an_pathistory_obj->exists()){
        //     $an_pathistory_obj = $an_pathistory_obj->first();
        //     $responce->an_pathistory = $an_pathistory_obj;
        // }

        // if($an_pathealth_obj->exists()){
        //     $an_pathealth_obj = $an_pathealth_obj->first();
        //     $responce->an_pathealth = $an_pathealth_obj;
        // }

        return json_encode($responce);

    }

}