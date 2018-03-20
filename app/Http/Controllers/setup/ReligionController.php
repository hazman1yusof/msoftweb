<?php

namespace App\Http\Controllers\setup;

use App\model\sysdb\religion;
use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Auth;
use Carbon\Carbon;

class ReligionController extends defaultController
{   

    var $table;

    public function __construct()
    {
        $this->middleware('auth');
        $this->table = new religion;
    }

    public function duplicate($check){
        return $this->table->where('Code','=',$check)->count();
    }

    public function show(Request $request)
    {   
        return view('setup.religion.religion');
    }

    public function table(Request $request)
    {   
        $table = $this->table;

        /////////where/////////


        
        /////////searching/////////
        if(!empty($request->searchCol)){
            foreach ($request->searchCol as $key => $value) {
                $table = $table->orWhere($request->searchCol[$key],'like',$request->searchVal[$key]);
            }
         }

        //////////ordering/////////
        if(!empty($request->sidx)){
            $pieces = explode(", ", $request->sidx .' '. $request->sord);
            if(count($pieces)==1){
                $table = $table->orderBy($request->sidx, $request->sord);
            }else{
                for ($i = sizeof($pieces)-1; $i >= 0 ; $i--) {
                    $pieces_inside = explode(" ", $pieces[$i]);
                    $table = $table->orderBy($pieces_inside[0], $pieces_inside[1]);
                }
            }
        }

        //////////paginate/////////
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

        if($this->duplicate($request->Code)){
            return response('duplicate', 500);
        }

        try {

            $this->table->insert([
                'compcode' => session('compcode'),
                'Code' => $request->Code,
                'Description' => $request->Description,
                'recstatus' => 'A',
                'adduser' => session('username'),
                'adddate' => Carbon::now(),
                'lastcomputerid' => $request->lastcomputerid,
                'lastipaddress' => $request->lastipaddress,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            return response('Error', 500);
            DB::rollback();
        }

    }

    public function edit(Request $request){

        DB::beginTransaction();

        try {

            $table = $this->table->find($request->idno);
            $table->update([
                'compcode' => session('compcode'),
                'Code' => $request->Code,
                'Description' => $request->Description,
                'recstatus' => 'A',
                'upduser' => session('username'),
                'upddate' => Carbon::now(),
                'lastcomputerid' => $request->lastcomputerid,
                'lastipaddress' => $request->lastipaddress,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            return response('Error', 500);
            DB::rollback();
        }

    }

    public function del(Request $request){

        DB::beginTransaction();

        try {

            $table = $this->table->find($request->idno);
            $table->update([
                'recstatus' => 'D',
                'deluser' => session('username'),
                'deldate' => Carbon::now(),
            ]);

            DB::commit();
        } catch (\Exception $e) {
            return response('Error', 500);
            DB::rollback();
        }

    }
}
