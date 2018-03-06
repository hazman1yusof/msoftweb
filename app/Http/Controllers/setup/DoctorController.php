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
                        'recstatus' => 'A',
                        'adduser' => session('username'),
                        'adddate' => now()
                    ]);
                }
                return $this->defaultAdd($request);
            case 'edit':

                $got = DB::table('hisdb.apptresrc')->where('resourcecode','=',$request->doctorcode)->first();
                if($request->appointment == '1' && $got == null){
                    DB::table('hisdb.apptresrc')->insert([
                        'compcode' => session('compcode'),
                        'resourcecode' => $request->doctorcode,
                        'description' => $request->doctorname,
                        'recstatus' => 'A',
                        'adduser' => session('username'),
                        'adddate' => now()
                    ]);
                }

                $old_doctor = DB::table('hisdb.doctor')->where('idno','=',$request->idno)->first();
                $apptbook = DB::table('hisdb.apptbook')
                        ->where('loccode','=',$request->doctorcode)
                        ->where('start','>',Carbon::now('Asia/Kuala_Lumpur'))
                        ->get();
                ///check kalau interval time dia lain, kena susnkan balik apptbook
                ///$old_doctor->intervaltime != $request->intervaltime && 
                if($apptbook!=null){
                    $old_intervaltime = $old_doctor->intervaltime;
                    $intervaltime = $request->intervaltime;

                    $apptsession = DB::table('hisdb.apptsession')
                        ->where('doctorcode','=',$request->doctorcode)
                        ->get();

                    foreach ($apptbook as $key => $obj) {
                        print_r($obj);
                        $carbon_time = Carbon::parse($obj->start);
                        print_r($carbon_time);
                        $dayOfWeek = $carbon_time->dayOfWeek;
                        switch ($dayOfWeek) {
                            case '0':
                                $session = $this->getFilterSession($apptsession,'SUNDAY');
                                break;
                            case '1':
                                $session = $this->getFilterSession($apptsession,'MONDAY');
                                break;
                            case '2':
                                $session = $this->getFilterSession($apptsession,'TUESDAY');

                                $fr_start = Carbon::parse($carbon_time->toDateString().' '.$session->timefr1);
                                $fr_to = Carbon::parse($carbon_time->toDateString().' '.$session->timeto1);

                                
                                $fr_start->subMinutes($intervaltime);
                                while($fr_start->lte($fr_to)) {
                                    print_r($fr_start);
                                    $first = $fr_start;
                                    $second = $fr_start->addMinutes($intervaltime);
                                    if($carbon_time->lte($second))
                                    {
                                        echo $carbon_time.' lagi kecik dari '.$second;
                                    }
                                }

                                break;
                            case '3':
                                $session = $this->getFilterSession($apptsession,'WEDNESDAY');
                                break;
                            case '4':
                                $session = $this->getFilterSession($apptsession,'THURSDAY');
                                break;
                            case '5':
                                $session = $this->getFilterSession($apptsession,'FRIDAY');
                                break;
                            case '6':
                                $session = $this->getFilterSession($apptsession,'SATURDAY');
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
                            'recstatus' => 'D'
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
}