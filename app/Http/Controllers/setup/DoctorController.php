<?php

namespace App\Http\Controllers\setup;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use DB;
use Carbon\Carbon;

class DoctorController extends defaultController
{   

    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
        $this->duplicateCode = "Code";
    }

    public function show(Request $request)
    {   
        return view('setup.doctor.doctor');
    }

    public function form(Request $request)
    {   
        switch($request->oper){
            case 'add':
                if($request->appointment == '1'){
                    DB::table('hisdb.apptresrc')->insert([
                        'compcode' => session('compcode'),
                        'resourcecode' => $request->doctorcode,
                        'description' => $request->doctorname,
                        'TYPE' => 'DOC',
                        'recstatus' => 'ACTIVE',
                        'intervaltime' => $request->intervaltime,
                        'adduser' => session('username'),
                        'adddate' => Carbon::now('Asia/Kuala_Lumpur')
                    ]);
                }

                $users = DB::table('sysdb.users')
                            ->where('compcode',session('compcode'))
                            ->where('username',$request->loginid)
                            ->where('doctorcode','!=',$request->doctorcode);

                if($users->exists()){
                    return response('User already Exists', 500);
                }

                DB::table('sysdb.users')
                    ->insert([
                        'compcode' => session('compcode'),
                        'username' => $request->loginid,
                        'password' => $request->loginid,
                        'name' => $request->doctorname,
                        'dept' => 'MRS',
                        'designation' => 'DOCTOR',
                        'groupid' => 'MEDICSOFT',
                        'programmenu' => 'MAIN',
                        'priceview' => 0,
                        'editpkgpat' => 0,
                        'recstatus' => 'ACTIVE',
                        'adduser' => 'SYSTEM',
                        'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                        'PHColor' => '#FFFFFF',
                        'ALcolor' => '#FFFFFF',
                        'DiscPTcolor' => '#FFFFFF',
                        'CancelPTcolor' => '#FFFFFF',
                        'CurrentPTcolor' => '#FFFFFF',
                        'mrn' => 0,
                        'nurse' => 0,
                        'doctor' => 1,
                        'billing' => 0,
                        'register' => 0,
                        'viewallcenter' => 0,
                        'xray' => 0,
                        'phar' => 0,
                        'doctorcode' => $request->doctorcode
                    ]);

                return $this->defaultAdd($request);
                break;
            case 'edit':
                $old_doctor = DB::table('hisdb.doctor')->where('idno','=',$request->idno)->first();

                $users = DB::table('sysdb.users')
                            ->where('compcode',session('compcode'))
                            ->where('username',$request->loginid)
                            ->where('doctorcode','!=',$request->doctorcode);

                if($users->exists()){
                    
                    return response('loginid already Exists', 500);
                }else{

                    

                    $users = DB::table('sysdb.users')
                            ->where('compcode',session('compcode'))
                            ->where('username',$request->loginid)
                            ->where('doctorcode',$request->doctorcode);

                    if(!$users->exists()){
                        DB::table('sysdb.users')
                            ->insert([
                                'compcode' => session('compcode'),
                                'username' => $request->loginid,
                                'password' => $request->loginid,
                                'name' => $request->doctorname,
                                'dept' => session('deptcode'),
                                'designation' => 'DOCTOR',
                                'groupid' => 'MEDICSOFT',
                                'programmenu' => 'MAIN',
                                'priceview' => 0,
                                'editpkgpat' => 0,
                                'recstatus' => 'ACTIVE',
                                'adduser' => 'SYSTEM',
                                'adddate' => Carbon::now('Asia/Kuala_Lumpur'),
                                'PHColor' => '#FFFFFF',
                                'ALcolor' => '#FFFFFF',
                                'DiscPTcolor' => '#FFFFFF',
                                'CancelPTcolor' => '#FFFFFF',
                                'CurrentPTcolor' => '#FFFFFF',
                                'mrn' => 0,
                                'nurse' => 0,
                                'doctor' => 1,
                                'billing' => 0,
                                'register' => 0,
                                'viewallcenter' => 0,
                                'xray' => 0,
                                'phar' => 0,
                                'doctorcode' => $request->doctorcode
                            ]);
                    }
                }
                
                $got = DB::table('hisdb.apptresrc')->where('resourcecode','=',$request->doctorcode)->exists();
                if($request->appointment == '1' && !$got){
                    DB::table('hisdb.apptresrc')->insert([
                        'compcode' => session('compcode'),
                        'resourcecode' => $request->doctorcode,
                        'description' => $request->doctorname,
                        'TYPE' => 'DOC',
                        'recstatus' => 'ACTIVE',
                        'intervaltime' => $request->intervaltime,
                        'adduser' => session('username'),
                        'adddate' => Carbon::now('Asia/Kuala_Lumpur')
                    ]);
                }else if($request->appointment == '1' && $got){
                    DB::table('hisdb.apptresrc')
                        ->where('resourcecode','=',$request->doctorcode)
                            ->update([
                            'description' => $request->doctorname,
                            'recstatus' => 'ACTIVE',
                            'intervaltime' => $request->intervaltime,
                            'upduser' => session('username'),
                            'upddate' => Carbon::now('Asia/Kuala_Lumpur')
                        ]);
                }

                $apptbook = DB::table('hisdb.apptbook')
                        ->where('loccode','=',$request->doctorcode)
                        ->where('recstatus','=',"ACTIVE")
                        ->where('start','>',Carbon::now('Asia/Kuala_Lumpur'))
                        ->get();
                ///check kalau interval time dia lain, kena susnkan balik apptbook
                if($old_doctor->intervaltime != $request->intervaltime && $apptbook!=null){
                    $old_intervaltime = $old_doctor->intervaltime;
                    $intervaltime = $request->intervaltime;

                    $apptsession = DB::table('hisdb.apptsession')
                        ->where('doctorcode','=',$request->doctorcode)
                        ->get();

                    foreach ($apptbook as $key => $obj) {
                        $carbon_time = Carbon::parse($obj->start);
                        $dayOfWeek = $carbon_time->dayOfWeek;
                        switch ($dayOfWeek) {
                            case '0':
                                $session = $this->getFilterSession($apptsession,'SUNDAY');
                                $this->reconfigureSession($session,$carbon_time,$intervaltime,$obj);
                                break;
                            case '1':
                                $session = $this->getFilterSession($apptsession,'MONDAY');
                                $this->reconfigureSession($session,$carbon_time,$intervaltime,$obj);
                                break;
                            case '2':
                                $session = $this->getFilterSession($apptsession,'TUESDAY');
                                $this->reconfigureSession($session,$carbon_time,$intervaltime,$obj);
                                break;
                            case '3':
                                $session = $this->getFilterSession($apptsession,'WEDNESDAY');
                                $this->reconfigureSession($session,$carbon_time,$intervaltime,$obj);
                                break;
                            case '4':
                                $session = $this->getFilterSession($apptsession,'THURSDAY');
                                $this->reconfigureSession($session,$carbon_time,$intervaltime,$obj);
                                break;
                            case '5':
                                $session = $this->getFilterSession($apptsession,'FRIDAY');
                                $this->reconfigureSession($session,$carbon_time,$intervaltime,$obj);
                                break;
                            case '6':
                                $session = $this->getFilterSession($apptsession,'SATURDAY');
                                $this->reconfigureSession($session,$carbon_time,$intervaltime,$obj);
                                break;
                        }
                    }                    
                }

                return $this->defaultEdit($request);
                break;
            case 'del':
                $got = DB::table('hisdb.apptresrc')->where('resourcecode','=',$request->doctorcode)->first();
                if($got != null){
                    DB::table('hisdb.apptresrc')
                        ->where('resourcecode','=',$request->doctorcode)
                        ->update([
                            'deluser' => session('username'),
                            'deldate' => Carbon::now('Asia/Kuala_Lumpur'),
                            'recstatus' => 'DEACTIVE'
                        ]);
                }
                return $this->defaultDel($request);
                break;
            default:
                return 'error happen..';
        }
    }

    public function getFilterSession($apptsession,$days){
        foreach ($apptsession as $key => $value) {
            if($value->days == $days){
                return $value;
            }
        }
    }

    public function reconfigureSession($session,$carbon_time,$intervaltime,$obj){
        $fr_start = Carbon::parse($carbon_time->toDateString().' '.$session->timefr1);
        $fr_to = Carbon::parse($carbon_time->toDateString().' '.$session->timeto1);

        while($fr_start->lte($fr_to)) {
            $first = $fr_start;
            $second = $fr_start->copy()->addMinutes($intervaltime);
            if($carbon_time->gte($first) && $carbon_time->lte($second)){
                DB::table('hisdb.apptbook')
                ->where('idno','=',$obj->idno)
                ->update([
                    'start' => $first,
                    'end' => $second
                ]);
            }
            $fr_start->addMinutes($intervaltime);
        }
    }
}