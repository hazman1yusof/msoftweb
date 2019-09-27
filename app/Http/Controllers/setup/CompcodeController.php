<?php
namespace App\Http\Controllers\setup;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;

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
                return $this->defaultAdd($request);
            case 'edit':
                return $this->defaultEdit($request);
            case 'del':
                return $this->defaultDel($request);
            default:
                return 'error happen..';
        }
    }
/*namespace App\Http\Controllers\serup;

use Illuminate\Http\Request;
use stdClass;
use DB;
use Auth;
use Carbon\Carbon;
use App\Http\Controllers\defaultController;


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

        $field = $request->field;
        $idno = $request->table_id;

        if($this->default_duplicate( ///check duplicate
            $request->table_name,
            $idno,
            $request[$request->table_id]
        )){
            return response('duplicate', 500);
        };

        DB::beginTransaction();

        $table = DB::table($request->table_name);

        $array_insert = [
        	'compcode' => $request->compcode,
            'adduser' => session('username'),
            'adddate' => Carbon::now(),
            'recstatus' => 'A'
        ];

        foreach ($field as $key => $value) {
            $array_insert[$value] = $request[$request->field[$key]];
        }

        try {

            $table->insert($array_insert);

            $responce = new stdClass();
            $responce->sql = $table->toSql();
            $responce->sql_bind = $table->getBindings();
            echo json_encode($responce);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }

    }

    public function edit(Request $request){

        DB::beginTransaction();

        $table = DB::table($request->table_name);

        $array_update = [
        	'compcode' => $request->compcode,
            'upduser' => session('username'),
            'upddate' => Carbon::now(),
            'recstatus' => 'A'
        ];
        $field = $request->field;
        $idno = $request->idno;

        foreach ($field as $key => $value) {
        	$array_update[$value] = $request[$request->field[$key]];
        }

        try {


            //////////where//////////
            $table = $table->where('idno','=',$idno);

            $table->update($array_update);

            $responce = new stdClass();
            $responce->sql = $table->toSql();
            $responce->sql_bind = $table->getBindings();
            echo json_encode($responce);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }

    }

    public function del(Request $request){

        DB::beginTransaction();

        $table = DB::table($request->table_name);

        try {

            $table = $table->where('idno','=',$request->idno);
            $table->update([
                'deluser' => session('username'),
                'deldate' => Carbon::now(),
                'recstatus' => 'D',
            ]);

            $responce = new stdClass();
            $responce->sql = $table->toSql();
            $responce->sql_bind = $table->getBindings();
            echo json_encode($responce);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            
            return response('Error'.$e, 500);
        }

    }*/
}