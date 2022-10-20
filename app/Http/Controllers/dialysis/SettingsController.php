<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {   
        
    }
    public function change_password()
    {   
        return view('change_password');
    }

    public function update(Request $request, User $user)
    {
        ////validate message
        $validatedData = $request->validate([
            'password' => 'required|min:5',
        ]);

        $user->password = bcrypt($request->password);
        $user->save();

        return redirect()->route('dashboard');
    }
}
