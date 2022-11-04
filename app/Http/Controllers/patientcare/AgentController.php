<?php

namespace App\Http\Controllers;

use App\User;
use App\ticket;
use App\message;
use Illuminate\Http\Request;

class AgentController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $agents = User::where('type','=','agent')->orderBy('id', 'desc')->get();
        return view('agent',compact('agents'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        ////validate message
        $validatedData = $request->validate([
            'username' => 'required|min:5|unique:users',
            'password' => 'required|min:5',
            'note' => '',
        ]);

        ////create new message
        $user = new User;

        $user->username = $request->username;
        $user->email = $request->email;
        $user->type = 'agent';
        $user->password = $request->password;
        $user->company = $request->company;
        $user->note = $request->note;
        $user->remember_token = str_random(10);

        $user->save();

        return redirect()->back();
    }

    public function agent_detail(User $user)
    {

        $assign = ticket::where("assign_to","=",$user->id)->count();

        $attention = ticket::where("assign_to","=",$user->id)
            ->where("priority","=","Urgent")
            ->whereIn('status', ['Open','Answered'])
            ->count();

        $open = ticket::where("assign_to","=",$user->id)->whereIn('status', ['Open','Answered'])->count();

        $answer = message::where("user_id","=",$user->id)->distinct('ticket_id')->count('ticket_id');

        $created = ticket::where("created_by","=",$user->username)->count();

        return view('agent_detail',compact('user','assign','attention','answer','open','created'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\agent  $agent
     * @return \Illuminate\Http\Response
     */
    public function edit(agent $agent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\agent  $agent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        // dd($request);

        $pass_check = (!empty($request->password))?'required|min:5':'';

        ////validate message
        $validatedData = $request->validate([
            'password' => $pass_check,
            'email' => 'email',
            'note' => '',
        ]);

        if(!empty($request->password)){
            $user->password = bcrypt($request->password);
        }

        $user->email = $request->email;
        $user->note = $request->note;
        $user->save();

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\agent  $agent
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->status = ($user->status == "Active")?'Inactive':'Active';
        $user->save();

        return redirect()->back();
    }
}
