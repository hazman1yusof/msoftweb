<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use stdClass;
use App\User;
use DB;
use Carbon\Carbon;
use Auth;

class CardiographController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function get_graph(Request $request){

        switch ($request->action) {
            case 'get_graph_cardio':
                return $this->get_graph_cardio($request);
                break;
        }
        
    }

    public function table(Request $request){

        switch ($request->action) {
            case 'get_cardio_table':
                return $this->get_cardio_table($request);
                break;
        }
        
    }

    public function form(Request $request){

        switch ($request->action) {
            case 'save_cardiograph':
                return $this->save_cardiograph($request);
                break;
        }
        
    }

    public function get_graph_cardio(Request $request){

        $bio = DB::table('hisdb.pat_mast')
                        ->where('mrn',$request->mrn)
                        // ->where('compcode',session('compcode'))
                        ->first();

        $patcardio = DB::table('hisdb.patcardio')
                            ->where('exercise',$request->exercise)
                            ->where('mrn',$request->mrn)
                            ->get();

        return view('cardiograph',compact('patcardio','bio'));
    }

    public function save_cardiograph(Request $request){
        if($request->oper == 'add'){
            DB::table('hisdb.patcardio')
                ->insert([
                    'exercise' => $request->exercise,
                    'date' => $request->date,
                    'mrn' => $request->mrn,
                    'bp_s' => $request->bp_s,
                    'bp_d' => $request->bp_d,
                    'hr' => $request->hr,
                    'speed' => $request->speed,
                    'rpe' => $request->rpe
                ]);
        }else if($request->oper == 'edit'){
            DB::table('hisdb.patcardio')
                ->where('idno','=',$request->idno)
                ->update([
                    'date' => $request->date,
                    'mrn' => $request->mrn,
                    'bp_s' => $request->bp_s,
                    'bp_d' => $request->bp_d,
                    'hr' => $request->hr,
                    'speed' => $request->speed,
                    'rpe' => $request->rpe
                ]);
        }else if($request->oper == 'del'){
            DB::table('hisdb.patcardio')
                ->where('idno','=',$request->idno)
                ->delete();
        }
    }

    public function get_cardio_table(Request $request){
        
        $table = DB::table('hisdb.patcardio')
            ->where('exercise','=',$request->exercise)
            ->where('mrn','=',$request->mrn);

        $paginate = $table->paginate($request->rows);

        $responce = new stdClass();
        $responce->page = $paginate->currentPage();
        $responce->total = $paginate->lastPage();
        $responce->records = $paginate->total();
        $responce->rows = $paginate->items();
        $responce->sql = $table->toSql();
        $responce->sql_bind = $table->getBindings();

        return json_encode($responce);
    }

    public static function mydump($obj,$line='null'){
        dd([
            $line,
            $obj->toSql(),
            $obj->getBindings()
        ]);

    }

}
