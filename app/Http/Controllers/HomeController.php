<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;

class HomeController extends Controller
{   
    var $_x=-2;
    var $_menu_str = "";
    var $_arr = array();

    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        $user = Auth::user();
        $menu = $this->create_main_menu();
        $units = DB::table('sysdb.sector')
                ->where('compcode','=',$user->compcode)
                ->get();
        $unit_user = '';
        if($user->dept != ''){
            $unit_user_ = DB::table('sysdb.department')
                ->where('compcode','=',$user->compcode)
                ->where('deptcode','=',$user->dept)
                ->first();
            $unit_user = $unit_user_->sector;
        }
        return view('init.container',compact('menu','units','unit_user'));
    }

    public function create_main_menu(){
        $user = Auth::user();
        $groupid = $user->groupid;
        $company = $user->compcode;

        $query = DB::table('sysdb.programtab as a')
                    ->join('sysdb.groupacc as b',function($join) {
                        $join->on('a.programmenu', '=', 'b.programmenu')
                        ->on('a.lineno', '=', 'b.lineno');
                    })
                    ->where('b.groupid','=',$groupid)
                    ->where('b.compcode','=',$company)
                    ->where('b.programmenu','=','main')
                    ->orderBy('b.lineno', 'asc');

        foreach ($query->get() as $key=>$value){
            $this->create_sub_menu($value,$this->_x,'main');
        }

        return $this->_menu_str;
    }

    public function create_sub_menu($rowX,$x,$class){
        $user = Auth::user();
        $groupid = $user->groupid;
        $company = $user->compcode;
        $this->_x = $this->_x+1;
        if($rowX->programtype=='M')
            {   
                
                if($class!='main')
                {
                    $this->_menu_str .= "
                    <li>
                        <a href='#' aria-expanded='false' style='padding-left:".$this->tab($this->_x)."'><span class='lilabel'>" .$rowX->programname."</span><span class='fa plus-minus'></span></a>
                        <ul aria-expanded='false'>";
                }
                else
                {
                    $this->_menu_str .= "
                    <li>
                        <a href='#' aria-expanded='false' class='main' style='padding-left:".$this->tab($this->_x)."'><span class='fa " .$rowX->condition3 ." fa-2x' style='padding-right:5px'></span><span class='lilabel'>". $rowX->programname ."</span><span class='glyphicon arrow'></span></a>
                        <ul aria-expanded='false'>";
                }

                $query = DB::table('sysdb.programtab as a')
                    ->join('sysdb.groupacc as b',function($join) {
                        $join->on('a.programmenu', '=', 'b.programmenu')
                        ->on('a.lineno', '=', 'b.lineno');
                    })
                    ->where('b.groupid','=',$groupid)
                    ->where('b.compcode','=',$company)
                    ->where('b.programmenu','=',$rowX->programid)
                    ->orderBy('b.lineno', 'asc');

                foreach ($query->get() as $key=>$value){
                    $this->_class='notmain';
                    $this->create_sub_menu($value,$this->_x,$this->_class);
                }
                
                $this->_menu_str .= "</ul></li>";
                
            }
            else
            {   

                $url = $rowX->url;
                if (str_starts_with($rowX->url, '/')) {
                    $url = ltrim($rowX->url, '/');
                }

                $this->_menu_str .= "<li><a style='padding-left:".$this->tab($this->_x)."' title='".$rowX->programname."' class='clickable' programid='".$rowX->programid."' targetURL='".$url."'><span class='lilabel'>".$rowX->programname."</span></a></li>"; 
            }

            $this->_x = $this->_x-1;
    }

    public function tab($loop){
        return (30 + (10 * $loop)) . 'px';
    }

    public function changeSessionUnit(Request $request){
        $request->session()->put('unit', $request->unit);
    }
}
