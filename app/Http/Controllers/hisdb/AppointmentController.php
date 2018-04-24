<?php

namespace App\Http\Controllers\hisdb;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use DB;
use Carbon\Carbon;

class AppointmentController extends defaultController
{   

    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
        $this->duplicateCode = "resourcecode";
    }

    public function show(Request $request)
    {   
        $ALCOLOR = DB::table('sysdb.users')
                    ->where('username','=',session('username'))
                    ->first();

        return view('hisdb.apptrsc.apptrsc',compact('ALCOLOR'));
    }

    public function form(Request $request)
    {   
        switch($request->oper){
            case 'add':
                return $this->defaultAdd($request);
            case 'edit':
                return $this->defaultEdit($request);
            case 'del':
                return $this->defaultDel($request);
            default:
                return 'error happen..';
        }
    }

    public function getEvent(Request $request){
        switch ($request->type) {
            case 'apptbook':

                $select = DB::table('hisdb.apptbook')
                    ->where('loccode','=',$request->drrsc)
                    ->where('recstatus','=',"A")
                    ->whereBetween('start', [$request->start, $request->end])
                    ->get();

                break;
            case 'appt_ph':

                $select = DB::table('hisdb.apptph')
                   ->select('datefr as start','dateto as end','backgroundcolor as color','remark as title')
                   ->whereBetween('datefr', [$request->start, $request->end])
                   ->get();

                foreach ($select as $key => $value) {
                    $value->textColor = 'white';
                    $value->rendering = 'background';
                }


                break;
            case 'appt_leave':

                 $select = DB::table('hisdb.apptleave')
                    ->select('datefr as start','dateto as end','remark as title')
                    ->where('resourcecode','=',$request->drrsc)
                    ->whereBetween('datefr', [$request->start, $request->end])
                    ->get();

                break;
            case 'apptbook_1':

                $select = DB::table('hisdb.apptbook')
                    ->where('loccode','=',$request->drrsc)
                    ->where('recstatus','=',"A")
                    ->whereBetween('start', [$request->start, $request->end])
                    ->get();

                break;
            default:
                return [];
                break;
        }
                    
        return $select;
    }

    public function addEvent(Request $request){

        $mrn_ = ($request->mrn == '')? '00000': $request->mrn;
        DB::table('hisdb.apptbook')->insert([
            'title'       => $mrn_.' - '.$request->patname.' - '.$request->telhp.' - '.$request->case.' - '.$request->remarks,
            'loccode'     => $request->doctor,
            'mrn'         => $request->mrn,
            'pat_name'    => $request->patname,
            'start'       => $request->apptdatefr_day.' '.$request->start_time,
            'end'         => $request->apptdatefr_day.' '.$request->end_time,
            'telno'       => $request->telh,
            'apptstatus'  => $request->status,
            'telhp'       => $request->telhp,
            'case_code'   => $request->case,
            'remarks'     => $request->remarks,
            'recstatus'   => 'A',
            'adduser'     => session('username'),
            'adddate'     => Carbon::now("Asia/Kuala_Lumpur"),
            'lastuser'    => session('username'),
            'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")
        ]);
        
    }

    public function editEvent(Request $request){

        if(!empty($request->event_drop)){
            DB::table('hisdb.apptbook')
            ->where('idno','=',$request->idno)
            ->update([
                'start'       => $request->start,
                'end'         => $request->end
            ]);
            
        }else if(!empty($request->type) && $request->type=='transfer'){

            foreach ($request->arraytd as $key => $value) {
                DB::table('hisdb.apptbook')
                ->where('idno','=',$value['idno'])
                ->update([
                    'start'       => $value['new_start'],
                    'end'         => $value['new_end']
                ]);
            }

        }else{
            $mrn_ = ($request->mrn == '')? '00000': $request->mrn;
            DB::table('hisdb.apptbook')
            ->where('idno','=',$request->idno)
            ->update([
                'title'       => $mrn_.' - '.$request->patname.' - '.$request->telhp.' - '.$request->case.' - '.$request->remarks,
                'loccode'     => $request->doctor,
                'mrn'         => $request->mrn,
                'pat_name'    => $request->patname,
                'start'       => $request->apptdatefr_day.' '.$request->start_time,
                'end'         => $request->apptdatefr_day.' '.$request->end_time,
                'telno'       => $request->telh,
                'apptstatus'  => $request->status,
                'recstatus'   => 'A',
                'telhp'       => $request->telhp,
                'case_code'   => $request->case,
                'remarks'     => $request->remarks,
                'lastuser'    => session('username'),
                'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")
            ]);

        }
    }

    public function delEvent(Request $request){

            DB::table('hisdb.apptbook')
            ->where('idno','=',$request->idno)
            ->update([
                'recstatus'   => 'D',
                'deluser'     => session('username'),
                'deldate'     => Carbon::now("Asia/Kuala_Lumpur")
            ]);
    }

}
