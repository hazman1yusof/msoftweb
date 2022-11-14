<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use DB;

class DoctorCOntroller extends Controller
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
        $doctors = DB::table('users')->where('type','=','doctor')->get();
        $patients = User::where('type','=','patient')->orderBy('id', 'desc')->get();
        return view('doctor',compact('doctors','patients'));
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
            'company' => '',
            'note' => '',
        ]);

        ////create new message
        $user = new User;

        $user->username = $request->username;
        $user->email = $request->email;
        $user->type = 'patient';
        $user->password = $request->password;
        $user->company = $request->company;
        $user->note = $request->note;
        $user->remember_token = str_random(10);

        $user->save();

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\customer  $customer
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
            'company' => '',
            'note' => '',
        ]);

        if(!empty($request->password)){
            $user->password = bcrypt($request->password);
        }

        $user->email = $request->email;
        $user->company = $request->company;
        $user->note = $request->note;
        $user->save();

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {   
        $user->status = ($user->status == "Active")?'Inactive':'Active';
        $user->save();

        return redirect()->back();
    }
}
