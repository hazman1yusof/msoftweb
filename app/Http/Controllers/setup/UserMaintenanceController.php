<?php

namespace App\Http\Controllers\setup;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Auth;
use Carbon\Carbon;

class UserMaintenanceController extends defaultController
{
    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
        $this->table = DB::table('sysdb.users');
        $this->duplicateCode = "username";
    }

    public function duplicate($check){
        return $this->table->where($this->duplicateCode,'=',$check)->count();
    }

    public function show(Request $request)
    {   
        return view('setup.user_maintenance.user_maintenance');
    }

    public function table(Request $request)
    {   
        $table = $this->table;

        $table = $table->where('compcode',session('compcode'));

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

        if($this->duplicate($request->username)){
            return response('duplicate', 500);
        }

        try {
            
            DB::table('sysdb.users')->insert([
                'username' => $request->username,
                'name' => $request->name,
                'groupid' => $request->groupid,
                'dept' => $request->dept,
                'cashier' => $request->cashier,
                'billing' => $request->billing,
                'nurse' => $request->nurse,
                'doctor' => $request->doctor,
                'register' => $request->register,
                'priceview' => $request->priceview,
                'programmenu' => $request->programmenu,
                'password' => $request->password,
                'ALcolor' => $request->ALcolor,
                'DiscPTcolor' => $request->DiscPTcolor,
                'CancelPTcolor' => $request->CancelPTcolor,
                'CurrentPTcolor' => $request->CurrentPTcolor,
                'compcode' => session('compcode'),
                'adduser' => session('username'),
                'adddate' => Carbon::now(),
                'recstatus' => 'ACTIVE'
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return response('Error'.$e, 500);
        }

    }

    public function edit(Request $request){

        DB::beginTransaction();

        try {

            $table = DB::table('sysdb.users')->where('id','=',$request->id)->where('compcode',session('compcode'));
            $table->update([
                'username' => $request->username,
                'name' => $request->name,
                'groupid' => $request->groupid,
                'dept' => $request->dept,
                'cashier' => $request->cashier,
                'billing' => $request->billing,
                'nurse' => $request->nurse,
                'doctor' => $request->doctor,
                'register' => $request->register,
                'priceview' => $request->priceview,
                'programmenu' => $request->programmenu,
                'password' => $request->password,
                'ALcolor' => $request->ALcolor,
                'DiscPTcolor' => $request->DiscPTcolor,
                'CancelPTcolor' => $request->CancelPTcolor,
                'CurrentPTcolor' => $request->CurrentPTcolor,
                'compcode' => session('compcode'),
                'upduser' => session('username'),
                'upddate' => Carbon::now(),
                'recstatus' => 'ACTIVE'
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return response('Error'.$e, 500);
        }

    }

    public function del(Request $request){

        DB::beginTransaction();

        try {

            $table = DB::table('sysdb.users')->where('id','=',$request->id)->where('compcode',session('compcode'));
            $table->update([
                'recstatus' => 'DEACTIVE',
                'deluser' => session('username'),
                'deldate' => Carbon::now(),
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return response('Error'.$e, 500);
        }

    }

    public function save_color(Request $request){
        DB::table('sysdb.sysparam')
            ->where('compcode','=',session('compcode'))
            ->where('source','=','HIS')
            ->where('trantype','=','ALCOLOR')
            ->update(['pvalue1' => $request->bg_leave]);
    }
}
