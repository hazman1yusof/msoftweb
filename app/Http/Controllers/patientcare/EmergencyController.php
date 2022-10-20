<?php

namespace App\Http\Controllers\patientcare;

use Illuminate\Http\Request;
use stdClass;
use App\User;
use DB;
use Carbon\Carbon;
use Auth;
use App\Http\Controllers\defaultController;

class EmergencyController extends defaultController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){

        // dd(Auth::user());

        // $navbar = $this->navbar();

        // $emergency = DB::table('hisdb.episode')
        // 				->whereMonth('reg_date', '=', now()->month)
        //                 ->whereYear('reg_date', '=', now()->year)
        // 				->get();

        // $events = $this->getEvent($emergency);

        if(!empty($request->username)){
            $user = DB::table('sysdb.users')
                    ->where('username','=',$request->username);
            if($user->exists()){
                $user = User::where('username',$request->username);
                Auth::login($user->first());
            }
        }

        return view('patientcare.emergency');
    }

    public function getEvent($obj){
    	$events = [];

    	for ($i=1; $i <= 31; $i++) {
    		$days = 0;
    		$reg_date;
    		foreach ($obj as $key => $value) {
	    		$day = Carbon::createFromFormat('Y-m-d',$value->reg_date);
	    		if($day->day == $i){
	    			$reg_date = $value->reg_date;
	    			$days++;
	    		}
	    	}
	    	if($days != 0){
	    		$event = new stdClass();
	    		$event->title = $days.' patients';
	    		$event->start = $reg_date;
	    		array_push($events, $event);
	    	}
    	}

    	return $events;

    }
}
