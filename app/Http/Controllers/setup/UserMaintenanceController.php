<?php

namespace App\Http\Controllers\setup;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Auth;
use Carbon\Carbon;
use PDF;
use App\Exports\UsersSetupExport;
use Maatwebsite\Excel\Facades\Excel;

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

    public function show_profile(){
            $user = DB::table('sysdb.users')
                    ->where('compcode',session('compcode'))
                    ->where('username',session('username'))
                    ->first();

        return view('setup.user_maintenance.user_profile',compact('user'));
    }
    
    public function duplicate($check,$mode,$idno){
        if($mode == 'add'){
            $users = DB::table('users')
                    ->where('compcode',session('compcode'))
                    ->where('username',$check)
                    ->exists();
            
            return $users;
        }else if($mode == 'edit'){
            $users = DB::table('users')
                    ->where('compcode',session('compcode'))
                    ->where('username',$check)
                    ->where('id','!=',$idno)
                    ->exists();
            
            return $users;
        }
        // return $this->table->where('compcode',session('compcode'))->where($this->duplicateCode,'=',$check)->count();
    }
    
    public function duplicate_doctorcode($doctorcode,$mode,$idno){
        if(empty($doctorcode)){
            return false;
        }
        
        if($mode == 'add'){
            $users = DB::table('users')
                    ->where('compcode',session('compcode'))
                    ->where('doctorcode',$doctorcode)
                    ->exists();
            
            return $users;
        }else if($mode == 'edit'){
            $users = DB::table('users')
                    ->where('compcode',session('compcode'))
                    ->where('doctorcode',$doctorcode)
                    ->where('id','!=',$idno)
                    ->exists();
            
            return $users;
        }
    }
    
    public function duplicate_email($email,$mode,$idno){
        if(empty($email)){
            return false;
        }
        
        if($mode == 'add'){
            $users = DB::table('users')
                    ->where('compcode',session('compcode'))
                    ->where('email',$email)
                    ->exists();
            
            return $users;
        }else if($mode == 'edit'){
            $users = DB::table('users')
                    ->where('compcode',session('compcode'))
                    ->where('email',$email)
                    ->where('id','!=',$idno)
                    ->exists();
            
            return $users;
        }
    }
    
    public function show(Request $request){
        return view('setup.user_maintenance.user_maintenance');
    }

    public function save_profile(Request $request){

        DB::beginTransaction();
        try {
            DB::table('users')
                ->where('compcode',session('compcode'))
                ->where('username',session('username'))
                ->update([
                    'name' => $request->name,
                    'designation' => $request->designation,
                    'password' => $request->password,
                ]);

            DB::commit();

            $responce = new stdClass();
            $responce->return = 'SUCCESS';
            
            return json_encode($responce);
            
        } catch (Exception $e) {
            
            
            DB::rollback();
            return response($e->getMessage(), 500);
        }
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
        
        //////////ordering//////////
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
        
        //////////paginate//////////
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
            case 'save_profile':
                return $this->save_profile($request);
            default:
                return 'error happen..';
        }
    }
    
    public function add(Request $request){
        
        DB::beginTransaction();
        
        if($this->duplicate($request->username,'add',null)){
            return response('duplicate username', 500);
        }
        
        if($this->duplicate_doctorcode($request->doctorcode,'add',null)){
            return response('duplicate doctor code', 500);
        }
        
        if($this->duplicate_email($request->email,'add',null)){
            return response('duplicate Email', 500);
        }
        
        try {
            
            DB::table('sysdb.users')->insert([
                'username' => $request->username,
                'name' => $request->name,
                'designation' => $request->designation,
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
                'email' => $request->email,
                'doctorcode' => $request->doctorcode,
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
            
            if($this->duplicate($request->username,'edit',$request->id)){
                return response('duplicate username', 500);
            }
            
            if($this->duplicate_doctorcode($request->doctorcode,'edit',$request->id)){
                return response('duplicate doctor code', 500);
            }

            if($this->duplicate_email($request->email,'edit',$request->id)){
                return response('duplicate Email', 500);
            }
            
            $table = DB::table('sysdb.users')->where('id','=',$request->id)->where('compcode',session('compcode'));
            $table->update([
                'username' => $request->username,
                'name' => $request->name,
                'designation' => $request->designation,
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
                'email' => $request->email,
                'doctorcode' => $request->doctorcode,
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
    
    public function showExcel(Request $request){
        return Excel::download(new UsersSetupExport($request->compcode), 'UsersSetup.xlsx');
    }
    
    public function showpdf(Request $request){
        
        $compcode = $request->compcode;
        
        $users = DB::table('sysdb.users')
                ->where('compcode', '=', session('compcode'))
                ->orderBy('groupid', 'ASC')
                ->get();
        
        $company = DB::table('sysdb.company')
                    ->where('compcode', '=', session('compcode'))
                    ->first();
        
        $header = new stdClass();
        $header->printby = session('username');
        $header->compname = $company->name;
        
        return view('setup.user_maintenance.user_maintenance_pdfmake', compact('users', 'header'));
        
    }
    
}
