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
                return $this->defaultAdd($request);
            case 'edit':
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

                $old_doctor = DB::table('hisdb.doctor')->where('idno','=',$request->idno)->first();
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