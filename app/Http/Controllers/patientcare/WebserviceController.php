<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use stdClass;
use App\User;
use DB;
use Auth;
use Carbon\Carbon;
use Hash;
use Session;

class WebserviceController extends Controller
{
    public function __construct()
    {

    }
    
    public function localpreview(Request $request)
    {   

        $user = User::where('username','doctor');
        Auth::login($user->first(),false);

        if(!empty($request->mrn)){
            $user = DB::table('hisdb.pat_mast')->where('mrn','=',$request->mrn)->first();
        }else{
            return abort(404);
        }

        return view('preview',compact('user'));
    }

    public function login(Request $request){//http://patientcare.test/webservice/login?page=dialysis&username=farid&dept=webservice
        switch ($request->page) {
            case 'upload':
                $goto = '/emergency';
                break;
            case 'dialysis':
                $goto = '/dialysis';
                break;
            default:
                $goto = '/emergency';
                break;
        }

        if(Auth::check()){
            $this->update_dept($request);
            return redirect($goto)->with('navbar','navbar');//maksudnya hide navbar
        }else{



            $user = User::where('username',request('username'));
            if($user->count() > 0){

                $request->session()->put('username', request('username'));
                $request->session()->put('compcode', $user->first()->compcode);

                $this->update_dept($request);
                Auth::login($user->first(),false);
                return redirect($goto)->with('navbar','navbar');

            }else{

                
                $request->session()->put('username', request('username'));
                $request->session()->put('compcode', $user->first()->compcode);

                $this->create_new_user($request);
                $user = User::where('username',request('username'));
                Auth::login($user->first(),false);
                return redirect($goto)->with('navbar','navbar');

            }
        }
    }

    public function update_dept(Request $request){
        $user = User::where('username',request('username'));
        if(empty($user->dept)){
            DB::table('sysdb.users')
                ->where('username','=',request('username'))
                ->update([
                    'dept' => $request->dept
                ]);
        }
    }

    public function create_new_user(Request $request){
        DB::table('sysdb.users')
            ->insert([
                'username' => $request->username,
                'name' => $request->username,
                'password' => $request->username,
                'dept' => $request->dept
            ]);
    }



    public function store_dashb(Request $request){
        $month = $request->month;
        $year = $request->year;

        $firstdate = Carbon::createFromDate($year, $month, 1);
        $seconddate = Carbon::createFromDate($year, $month, 1)->addDays(6);
        $thirddate = Carbon::createFromDate($year, $month, 1)->addDays(12+1);
        $fourthdate = Carbon::createFromDate($year, $month, 1)->addDays(18+2);
        $fiftthdate = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        $week1ip = DB::table('hisdb.pateis_rev')
                    ->where('year','=','Y'.$year)
                    ->where('month','=','M'.str_pad($month,2,"0",STR_PAD_LEFT))
                    ->where('epistype','=','IP')
                    ->where('units','=','ABC')
                    ->where('datetype','=','DIS')
                    ->whereBetween('disdate', [$firstdate, $seconddate])
                    ->sum('amount');

        $week2ip = DB::table('hisdb.pateis_rev')
                    ->where('year','=','Y'.$year)
                    ->where('month','=','M'.str_pad($month,2,"0",STR_PAD_LEFT))
                    ->where('epistype','=','IP')
                    ->where('units','=','ABC')
                    ->where('datetype','=','DIS')
                    ->whereBetween('disdate', [$seconddate, $thirddate])
                    ->sum('amount');

        $week3ip = DB::table('hisdb.pateis_rev')
                    ->where('year','=','Y'.$year)
                    ->where('month','=','M'.str_pad($month,2,"0",STR_PAD_LEFT))
                    ->where('epistype','=','IP')
                    ->where('units','=','ABC')
                    ->where('datetype','=','DIS')
                    ->whereBetween('disdate', [$thirddate, $fourthdate])
                    ->sum('amount');

        $week4ip = DB::table('hisdb.pateis_rev')
                    ->where('year','=','Y'.$year)
                    ->where('month','=','M'.str_pad($month,2,"0",STR_PAD_LEFT))
                    ->where('epistype','=','IP')
                    ->where('units','=','ABC')
                    ->where('datetype','=','DIS')
                    ->whereBetween('disdate', [$fourthdate, $fiftthdate])
                    ->sum('amount');

        $patsumepis = DB::table('hisdb.patsumepis')
                        ->where('month','=',$month)
                        ->where('year','=',$year)
                        ->where('type','=','REV')
                        ->where('patient','=','IP');

        if($patsumepis->exists()){
            $patsumepis->update([
                'week1' => $week1ip,
                'week2' => $week2ip,
                'week3' => $week3ip,
                'week4' => $week4ip
            ]);
        }else{
            $patsumepis->insert([
                'month' => $month,
                'year' => $year,
                'type' => 'REV',
                'patient' => 'IP',
                'week1' => $week1ip,
                'week2' => $week2ip,
                'week3' => $week3ip,
                'week4' => $week4ip
            ]);
        }

        $week1op = DB::table('hisdb.pateis_rev')
                    ->where('year','=','Y'.$year)
                    ->where('month','=','M'.str_pad($month,2,"0",STR_PAD_LEFT))
                    ->where('epistype','=','OP')
                    ->where('units','=','ABC')
                    ->where('datetype','=','DIS')
                    ->whereBetween('disdate', [$firstdate, $seconddate])
                    ->sum('amount');

        $week2op = DB::table('hisdb.pateis_rev')
                    ->where('year','=','Y'.$year)
                    ->where('month','=','M'.str_pad($month,2,"0",STR_PAD_LEFT))
                    ->where('epistype','=','OP')
                    ->where('units','=','ABC')
                    ->where('datetype','=','DIS')
                    ->whereBetween('disdate', [$seconddate, $thirddate])
                    ->sum('amount');

        $week3op = DB::table('hisdb.pateis_rev')
                    ->where('year','=','Y'.$year)
                    ->where('month','=','M'.str_pad($month,2,"0",STR_PAD_LEFT))
                    ->where('epistype','=','OP')
                    ->where('units','=','ABC')
                    ->where('datetype','=','DIS')
                    ->whereBetween('disdate', [$thirddate, $fourthdate])
                    ->sum('amount');

        $week4op = DB::table('hisdb.pateis_rev')
                    ->where('year','=','Y'.$year)
                    ->where('month','=','M'.str_pad($month,2,"0",STR_PAD_LEFT))
                    ->where('epistype','=','OP')
                    ->where('units','=','ABC')
                    ->where('datetype','=','DIS')
                    ->whereBetween('disdate', [$fourthdate, $fiftthdate])
                    ->sum('amount');


        $patsumepis = DB::table('hisdb.patsumepis')
                        ->where('month','=',$month)
                        ->where('year','=',$year)
                        ->where('type','=','REV')
                        ->where('patient','=','OP');

        if($patsumepis->exists()){
            $patsumepis->update([
                'week1' => $week1op,
                'week2' => $week2op,
                'week3' => $week3op,
                'week4' => $week4op
            ]);
        }else{
            $patsumepis->insert([
                'month' => $month,
                'year' => $year,
                'type' => 'REV',
                'patient' => 'OP',
                'week1' => $week1op,
                'week2' => $week2op,
                'week3' => $week3op,
                'week4' => $week4op
            ]);
        }


        $week1ip_pt = DB::table('hisdb.pateis_epis')
                    ->where('year','=','Y'.$year)
                    ->where('month','=','M'.str_pad($month,2,"0",STR_PAD_LEFT))
                    ->where('epistype','=','IN-PATIENT')
                    ->where('units','=','ABC')
                    ->where('datetype','=','DIS')
                    ->whereBetween('admdate', [$firstdate, $seconddate])
                    ->count();

        $week2ip_pt = DB::table('hisdb.pateis_epis')
                    ->where('year','=','Y'.$year)
                    ->where('month','=','M'.str_pad($month,2,"0",STR_PAD_LEFT))
                    ->where('epistype','=','IN-PATIENT')
                    ->where('units','=','ABC')
                    ->where('datetype','=','DIS')
                    ->whereBetween('admdate', [$seconddate, $thirddate])
                    ->count();

        $week3ip_pt = DB::table('hisdb.pateis_epis')
                    ->where('year','=','Y'.$year)
                    ->where('month','=','M'.str_pad($month,2,"0",STR_PAD_LEFT))
                    ->where('epistype','=','IN-PATIENT')
                    ->where('units','=','ABC')
                    ->where('datetype','=','DIS')
                    ->whereBetween('admdate', [$thirddate, $fourthdate])
                    ->count();

        $week4ip_pt = DB::table('hisdb.pateis_epis')
                    ->where('year','=','Y'.$year)
                    ->where('month','=','M'.str_pad($month,2,"0",STR_PAD_LEFT))
                    ->where('epistype','=','IN-PATIENT')
                    ->where('units','=','ABC')
                    ->where('datetype','=','DIS')
                    ->whereBetween('admdate', [$fourthdate, $fiftthdate])
                    ->count();

        $patsumepis = DB::table('hisdb.patsumepis')
                        ->where('year','=',$year)
                        ->where('month','=',$month)
                        ->where('type','=','epis')
                        ->where('patient','=','IP');

        if($patsumepis->exists()){
            $patsumepis->update([
                'week1' => $week1ip_pt,
                'week2' => $week2ip_pt,
                'week3' => $week3ip_pt,
                'week4' => $week4ip_pt
            ]);
        }else{
            $patsumepis->insert([
                'month' => $month,
                'year' => $year,
                'type' => 'epis',
                'patient' => 'IP',
                'week1' => $week1ip_pt,
                'week2' => $week2ip_pt,
                'week3' => $week3ip_pt,
                'week4' => $week4ip_pt
            ]);
        }

        $week1op_pt = DB::table('hisdb.pateis_epis')
                    ->where('year','=','Y'.$year)
                    ->where('month','=','M'.str_pad($month,2,"0",STR_PAD_LEFT))
                    ->where('epistype','=','OUT-PATIENT')
                    ->where('units','=','ABC')
                    ->where('datetype','=','DIS')
                    ->whereBetween('admdate', [$firstdate, $seconddate])
                    ->count();

        $week2op_pt = DB::table('hisdb.pateis_epis')
                    ->where('year','=','Y'.$year)
                    ->where('month','=','M'.str_pad($month,2,"0",STR_PAD_LEFT))
                    ->where('epistype','=','OUT-PATIENT')
                    ->where('units','=','ABC')
                    ->where('datetype','=','DIS')
                    ->whereBetween('admdate', [$seconddate, $thirddate])
                    ->count();

        $week3op_pt = DB::table('hisdb.pateis_epis')
                    ->where('year','=','Y'.$year)
                    ->where('month','=','M'.str_pad($month,2,"0",STR_PAD_LEFT))
                    ->where('epistype','=','OUT-PATIENT')
                    ->where('units','=','ABC')
                    ->where('datetype','=','DIS')
                    ->whereBetween('admdate', [$thirddate, $fourthdate])
                    ->count();

        $week4op_pt = DB::table('hisdb.pateis_epis')
                    ->where('year','=','Y'.$year)
                    ->where('month','=','M'.str_pad($month,2,"0",STR_PAD_LEFT))
                    ->where('epistype','=','OUT-PATIENT')
                    ->where('units','=','ABC')
                    ->where('datetype','=','DIS')
                    ->whereBetween('admdate', [$fourthdate, $fiftthdate])
                    ->count();

        $patsumepis = DB::table('hisdb.patsumepis')
                        ->where('month','=',$month)
                        ->where('type','=','epis')
                        ->where('patient','=','OP');

        if($patsumepis->exists()){
            $patsumepis->update([
                'week1' => $week1op_pt,
                'week2' => $week2op_pt,
                'week3' => $week3op_pt,
                'week4' => $week4op_pt
            ]);
        }else{
            $patsumepis->insert([
                'month' => $month,
                'year' => $year,
                'type' => 'epis',
                'patient' => 'OP',
                'week1' => $week1op_pt,
                'week2' => $week2op_pt,
                'week3' => $week3op_pt,
                'week4' => $week4op_pt
            ]);
        }

        $groupdesc_ = DB::table('hisdb.pateis_rev')->distinct()->get(['groupdesc']);

        $groupdesc = [];
        $groupdesc_val_op = [];
        $groupdesc_val_ip = [];
        $groupdesc_val = [];
        foreach ($groupdesc_ as $key => $value) {
            $groupdesc[$key] = $value->groupdesc;
            $groupdesc_op = DB::table('hisdb.pateis_rev')
                            ->where('year','=','Y'.$year)
                            ->where('month','=','M'.str_pad($month,2,"0",STR_PAD_LEFT))
                            ->where('epistype','=','OP')
                            ->where('groupdesc','=',$value->groupdesc)
                            ->where('units','=','ABC')
                            ->where('datetype','=','DIS');

            $groupdesc_op_sum = $groupdesc_op->sum('amount');
            $groupdesc_op_cnt = $groupdesc_op->count();

            $groupdesc_val_op[$key] = $groupdesc_op_sum;
            $groupdesc_cnt_op[$key] = $groupdesc_op_cnt;

            $groupdesc_ip = DB::table('hisdb.pateis_rev')
                            ->where('year','=','Y'.$year)
                            ->where('month','=','M'.str_pad($month,2,"0",STR_PAD_LEFT))
                            ->where('epistype','=','IP')
                            ->where('groupdesc','=',$value->groupdesc)
                            ->where('units','=','ABC')
                            ->where('datetype','=','DIS');

            $groupdesc_ip_sum = $groupdesc_ip->sum('amount');
            $groupdesc_ip_cnt = $groupdesc_ip->count();

            $groupdesc_val_ip[$key] = $groupdesc_ip_sum;
            $groupdesc_cnt_ip[$key] = $groupdesc_ip_cnt;
            $groupdesc_val[$key] = $groupdesc_op_sum + $groupdesc_ip_sum;

        }

        $patsumrev = DB::table('hisdb.patsumrev')
                        ->where('month','=',$month)
                        ->where('year','=',$year);

        if($patsumrev->exists()){
            $patsumrev = DB::table('hisdb.patsumrev')
                            ->where('month','=',$month)
                            ->where('year','=',$year);

            foreach ($groupdesc_ as $key => $value) {
                $patsumrev->where('group','=',$value->groupdesc)
                            ->update([
                                'ipcnt' => $groupdesc_cnt_ip[$key],
                                'opcnt' => $groupdesc_cnt_op[$key],
                                'ipsum' => $groupdesc_val_ip[$key],
                                'opsum' => $groupdesc_val_op[$key],
                                'totalsum' => $groupdesc_val[$key],
                            ]);
            }
        }else{
            foreach ($groupdesc_ as $key => $value) {
                $patsumrev->insert([
                                'month' => $month,
                                'year' => $year,
                                'group' => $value->groupdesc,
                                'ipcnt' => $groupdesc_cnt_ip[$key],
                                'opcnt' => $groupdesc_cnt_op[$key],
                                'ipsum' => $groupdesc_val_ip[$key],
                                'opsum' => $groupdesc_val_op[$key],
                                'totalsum' => $groupdesc_val[$key],
                            ]);
            }
        }

    }
    
}
