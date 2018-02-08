<?php

namespace App\Http\Controllers\setup;

use App\model\sysdb\groupacc;
use App\Http\Controllers\defaultController;
use Illuminate\Http\Request;
use stdClass;
use DB;
use Auth;
use Carbon\Carbon;

class GroupMaintenanceController extends defaultController
{
    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
        $this->table = new groupacc;
        $this->duplicateCode = "programid";
    }

    public function duplicate($check){
        return $this->table->where($this->duplicateCode,'=',$check)->count();
    }

    public function show(Request $request)
    {   
        return view('setup.group_maintenance.group_maintenance');
    }

    public function table(Request $request)
    {   
        $pieces = explode(", ", $request->sidx .' '. $request->sord);
        $table = $this->table;

        /////////where/////////
        $table = $table->where($request->filterCol[0],'=',$request->filterVal[0]);

        /////////searching/////////
        if(!empty($request->searchCol)){
            foreach ($request->searchCol as $key => $value) {
                $table = $table->orWhere($request->searchCol[$key],'like',$request->searchVal[$key]);
            }
         }

        //////////ordering/////////
        if(count($pieces)==1){
            $table = $table->orderBy($request->sidx, $request->sord);
        }else{
            for ($i = sizeof($pieces)-1; $i >= 0 ; $i--) {
                $pieces_inside = explode(" ", $pieces[$i]);
                $table = $table->orderBy($pieces_inside[0], $pieces_inside[1]);
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
            	if($request->action == "grpmaintenance_save"){
            		return $this->defaultAdd($request);
            	}else{
                	return $this->add($request);
            	}
            case 'edit':
            	if($request->action == "grpmaintenance_save"){
            		return $this->defaultEdit($request);
            	}else{
                	return $this->add($request);
            	}
            case 'del':
            	if($request->action == "grpmaintenance_save"){
            		return $this->defaultDel($request);
            	}else{
                	return $this->add($request);
            	}
            default:
                return 'error happen..';
        }
    }

    public function add(Request $request){
        DB::beginTransaction();

		$canrun=$request->canrun;
		$yesall=$request->yesall;
		$canrunold=$request->canrunold;
		$yesallold=$request->yesallold;
	    $groupid=$request->groupid;
	    $programid=$request->programid;
	    $programmenu=$request->programmenu;
	    $lineno=$request->lineno;


        try {

        	if($canrun=="Yes" && $canrunold=="No"){//insert into groupacc

        		$raw = DB::raw("INSERT INTO sysdb.groupacc (compcode,groupid,programmenu,lineno,canrun) VALUES ('".session('compcode')."', '{$groupid}', '{$programmenu}', '{$lineno}','1')");

				DB::insert($raw);

				if($yesall=="Yes" && $yesallold=="No"){//update groupacc and insert child

					$sql2 =  DB::raw("UPDATE sysdb.groupacc SET yesall=1 WHERE lineno='{$lineno}' and groupid='{$groupid}' and programmenu='{$programmenu}' and compcode='".session('compcode')."'");
					$sql3 =  DB::raw("INSERT INTO sysdb.groupacc (compcode,groupid,programmenu,lineno,canrun) SELECT compcode,'{$groupid}',programmenu,lineno,'1' FROM sysdb.programtab WHERE programmenu='{$programid}' AND compcode='".session('compcode')."'");

					DB::update($sql2);
					DB::insert($sql3);
				}

			}else if($canrun=="Yes" && $canrunold=="Yes"){//check yesall

				if($yesall=="Yes" && $yesallold=="No"){//update groupacc and insert child

					$sql1 = DB::raw("UPDATE sysdb.groupacc SET yesall=1 WHERE lineno='{$lineno}' and groupid='{$groupid}' and programmenu='{$programmenu}' and compcode='".session('compcode')."'");
					$sql2 = DB::raw("INSERT INTO sysdb.groupacc(compcode,groupid,programmenu,lineno,canrun) SELECT compcode,'{$groupid}',programmenu,lineno,'1' FROM sysdb.programtab WHERE programmenu='{$programid}' AND compcode='".session('compcode')."'");

					DB::update($sql1);
					DB::insert($sql2);

				}else if($yesall=="No" && $yesallold=="Yes"){//update from groupacc

					$sql1 = DB::raw("UPDATE sysdb.groupacc SET yesall=0 WHERE lineno='{$lineno}' and groupid='{$groupid}' and programmenu='{$programmenu}' and compcode='".session('compcode')."'");
					$sql2 = DB::raw("DELETE FROM sysdb.groupacc WHERE groupid='{$groupid}' and programmenu='{$programid}' and compcode='".session('compcode')."'");

					DB::update($sql1);
					DB::delete($sql2);

				}

			}else if($canrun=="No" && $canrunold=="Yes"){//delete from groupacc

				$sql1 = DB::raw("DELETE FROM sysdb.groupacc WHERE lineno='{$lineno}' and groupid='{$groupid}' and programmenu='{$programmenu}' and compcode='".session('compcode')."'");
				$sql2 = DB::raw("DELETE FROM sysdb.groupacc WHERE groupid='{$groupid}' and programmenu='{$programid}' and compcode='".session('compcode')."'");

				DB::delete($sql1);
				DB::delete($sql2);
			}

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }

    }

    public function edit(Request $request){

        DB::beginTransaction();

        try {

        } catch (\Exception $e) {
            DB::rollback();
        }

    }

    public function del(Request $request){

        DB::beginTransaction();

        try {

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }

    }
}
