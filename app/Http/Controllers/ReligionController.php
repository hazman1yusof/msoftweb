<?php

namespace App\Http\Controllers;

use App\religion;
use Illuminate\Http\Request;
use stdClass;
use DB;
use Auth;

class ReligionController extends Controller
{   
    var $username;
    var $compcode;

    public function __construct()
    {
        $this->middleware('auth');
        $this->username = session('username');
        $this->compcdoe = session('compcode');
    }

    public function duplicate($check){
        return religion::where('Code','=',$check)->count();
    }

    public function show(Request $request)
    {   
        return view('setup.religion.religion');
    }

    public function table(Request $request)
    {   
        
        if(!empty($request->searchCol)){
            $religion = religion::where($request->searchCol[0],'like',$request->searchVal[0]);

            $pieces = explode(",", $request->sidx);
            if(count($pieces)==1){
                $religion->orderBy($request->sidx, $request->sord);
            }else{
                echo 'lebey dari satu';
            }

            $religion ->paginate($request->rows);

        }else{
            $pieces = explode(", ", $request->sidx .' '. $request->sord);
            $religion = new religion;
            if(count($pieces)==1){
                $religion = $religion->orderBy($request->sidx, $request->sord);
            }else{
                for ($i = sizeof($pieces)-1; $i >= 0 ; $i--) {
                    $pieces_inside = explode(" ", $pieces[$i]);
                    $religion = $religion->orderBy($pieces_inside[0], $pieces_inside[1]);
                }
            }
            $paginate = $religion->paginate($request->rows);
        }

        $responce = new stdClass();
        $responce->page = $paginate->currentPage();
        $responce->total = $paginate->lastPage();
        $responce->records = $paginate->total();
        $responce->rows = $paginate->items();
        $responce->sql = $religion->toSql();

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

        if($this->duplicate($request->Code)){
            return response('duplicate', 500);
        }

        try {

            $religion = new religion;
            $religion->insert([
                'Code' => $request->Code,
                'Description' => $request->Description,
                'recstatus' => 'A',
                'adduser' => session('username'),
                'lastcomputerid' => $request->lastcomputerid,
                'lastipaddress' => $request->lastipaddress,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }

        return $religion;
    }

    public function edit(Request $request){

        DB::beginTransaction();

        try {

            $religion = religion::find($request->idno);
            $religion->update([
                'Code' => $request->Code,
                'Description' => $request->Description,
                'recstatus' => 'A',
                'upduser' => session('username'),
                'lastcomputerid' => $request->lastcomputerid,
                'lastipaddress' => $request->lastipaddress,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }

        return $religion;
    }

    public function del(Request $request){

        DB::beginTransaction();

        try {

            $religion = religion::find($request->idno);
            $religion->update([
                'recstatus' => 'D',
                'deluser' => $this->username,
                'deldate' => 'NOW'
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }

        return $religion;
    }
}
