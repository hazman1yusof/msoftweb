<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ticket;
use App\message;
use Auth;

class DashboardController extends Controller
{

	public function __construct()
    {
        $this->middleware('auth');
    }
    //
    public function index()
    {   
        $user = Auth::user();

        if($user->type == 'doctor'){

            $assign = ticket::where("assign_to","=",$user->id)->count();

            $attention = ticket::where("assign_to","=",$user->id)
                ->where("priority","=","Urgent")
                ->whereIn('status', ['Open','Answered'])
                ->count();

            $open = ticket::where("assign_to","=",$user->id)->whereIn('status', ['Open','Answered'])->count();

            $answer = message::where("user_id","=",$user->id)->distinct('ticket_id')->count('ticket_id');

            return view('dashboard',compact('user','assign','attention','answer','open','created'));

        }else if($user->type == 'patient'){

            $report_by = ticket::where("report_by","=",$user->id)->count();

            $answered = ticket::where("report_by","=",$user->id)->where('status', 'Answered')->count();

            $open = ticket::where("report_by","=",$user->id)->where('status', 'Open')->count();

            $resolved = ticket::where("report_by","=",$user->id)->where('status', 'Resolved')->count();

            $closed = ticket::where("report_by","=",$user->id)->where('status', 'Closed')->count();

            return view('dashboard',compact('user','report_by','answered','open','resolved','closed'));

        }
    }

    

    public function redirect()
    {
        if(Auth::user()->groupid == 'patient'){
            return redirect('/dialysis');
        }else if(strtoupper(Auth::user()->groupid) == 'DOCTOR'){
            return redirect('/dialysis');
        }else if(strtoupper(Auth::user()->groupid) == 'CLINICAL'){
            return redirect('/dialysis');
        }else if(strtoupper(Auth::user()->groupid) == 'ADMIN'){
            return redirect('/dialysis');
        }else if(strtoupper(Auth::user()->groupid) == 'MR'){
            return redirect('/dialysis');
        }else{
            return redirect('/dialysis');
        }
    }
}
