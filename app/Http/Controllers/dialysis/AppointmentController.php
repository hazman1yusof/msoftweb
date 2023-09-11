<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use stdClass;
use DB;
use Auth;
use Carbon\Carbon;

class AppointmentController extends Controller
{   

    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   

        // $navbar = $this->navbar();

        $resources = DB::table('hisdb.apptresrc as a')
                        ->select('a.resourcecode','a.description','d.intervaltime')
                        ->join('hisdb.doctor as d', 'a.resourcecode', '=', 'd.doctorcode')
                        ->where('a.TYPE','=','DOC')
                        ->get();

        $ALCOLOR = DB::table('sysdb.sysparam')
                    ->where('source','=','HIS')
                    ->where('trantype','=','ALCOLOR')
                    ->first();
        
        if(Auth::user()->groupid == "patient"){
            $pat_info = DB::table('hisdb.pat_mast')
                    ->where('mrn','=',Auth::user()->mrn)
                    ->first();
        }else{
            $pat_info=null;
        }

        return view('appointment',compact('ALCOLOR','pat_info','resources'));
    }

    public function form(Request $request)
    {   
        DB::enableQueryLog();
        switch($request->action){
            case 'add':
                return $this->defaultAdd($request);
            case 'edit':
                return $this->defaultEdit($request);
            case 'del':
                return $this->defaultDel($request);
            case 'save_patient':
                if($request->oper == 'add'){
                    return $this->save_patient_add($request);
                }else if($request->oper == 'edit'){
                    return $this->save_patient_edit($request);
                }else{
                    return false; 
                }
                
            default:
                return 'error happen..';
        }
    }

    public function getEvent(Request $request){
        switch ($request->type) {
            case 'apptbook':

                $select = DB::table('apptbook')
                    ->where('loccode','=',$request->drrsc)
                    ->where('recstatus','=',"A")
                    ->whereBetween('start', [$request->start, $request->end])
                    ->get();

                break;
            case 'appt_ph':
                $select = DB::table('apptph')
                    ->select('apptph.datefr as start','apptph.dateto as end','apptphcolor.color as color','apptph.remark as title')
                    ->leftJoin('apptphcolor', 'apptph.idno', '=', 'apptphcolor.phidno')
                    ->where('apptphcolor.userid', '=' , 'farid')
                    ->whereBetween('apptph.datefr', [$request->start, $request->end])
                    ->get();

                foreach ($select as $key => $value) {
                    $value->textColor = 'white';
                    $value->rendering = 'background';
                }


                break;
            case 'appt_leave':

                 $select = DB::table('apptleave')
                    ->select('datefr as start','dateto as end','remark as title')
                    ->where('resourcecode','=',$request->drrsc)
                    ->whereBetween('datefr', [$request->start, $request->end])
                    ->get();

                break;
            case 'apptbook_1':

                $select = DB::table('apptbook')
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

        DB::beginTransaction();

        try {

            $case = DB::table('casetype')
                    ->where('case_code','=',$request->case)
                    ->first();

            $mrn_ = ($request->mrn == '')? '00000': $request->mrn;
            $apptidno = DB::table('apptbook')->insertGetId([
                'title'       => str_pad($mrn_, 7, "0", STR_PAD_LEFT).' - '.$request->patname.' - '.$request->telhp.' - '.$case->description.' - '.substr(preg_replace("/\s+/", " ", $request->remarks), 0, 30),
                'loccode'     => $request->doctor,
                'icnum'       => $request->icnum,
                'mrn'         => $request->mrn,
                'pat_name'    => $request->patname,
                'start'       => $request->apptdatefr_day.' '.$request->start_time,
                'end'         => $request->apptdatefr_day.' '.$request->end_time,
                'telno'       => $request->telh,
                'apptstatus'  => $request->status,
                'telhp'       => $request->telhp,
                'case_code'   => $request->case,
                'case_desc'   => $case->description,
                'remarks'     => $request->remarks,
                'recstatus'   => 'A',
                'adduser'     => 'system',
                'adddate'     => Carbon::now("Asia/Kuala_Lumpur"),
                'lastuser'    => 'system',
                'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur"),
                'type'        => 'DOC'
            ]);

            if($request->mrn != ''){
                $pat_mast = DB::table("pat_mast")
                        ->where("mrn",'=',$request->mrn)
                        ->first();

                $mrn = ltrim($request->mrn, '0');
            }else{
                $mrn = '00000';
            }

            //edit no telefon dkt patmast
            if($mrn != '00000'){
                DB::table('pat_mast')
                    ->where("mrn",'=',$mrn)
                    ->update([
                        'telhp'    => $request->telhp,
                        'telh'    => $request->telh,
                    ]);
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }
        
    }

    public function editEvent(Request $request){

        DB::beginTransaction();

        try {

            if(!empty($request->event_drop)){
                DB::table('apptbook')
                ->where('idno','=',$request->idno)
                ->update([
                    'start'       => $request->start,
                    'end'         => $request->end
                ]);
                
            }else if(!empty($request->type) && $request->type=='transfer'){

                foreach ($request->arraytd as $key => $value) {
                    DB::table('apptbook')
                    ->where('idno','=',$value['idno'])
                    ->update([
                        'start'       => $value['new_start'],
                        'end'         => $value['new_end']
                    ]);
                }

            }else{
                $mrn_ = ($request->mrn == '')? '00000': $request->mrn;
                DB::table('apptbook')
                ->where('idno','=',$request->idno)
                ->update([
                    'title'       => str_pad($mrn_, 7, "0", STR_PAD_LEFT).' - '.$request->patname.' - '.$request->telhp.' - '.$case->description.' - '.substr(preg_replace("/\s+/", " ", $request->remarks), 0, 30),
                    'loccode'     => $request->doctor,
                    'mrn'         => $request->mrn,
                    'icnum'       => $request->icnum,
                    'pat_name'    => $request->patname,
                    'start'       => $request->apptdatefr_day.' '.$request->start_time,
                    'end'         => $request->apptdatefr_day.' '.$request->end_time,
                    'telno'       => $request->telh,
                    'apptstatus'  => $request->status,
                    'recstatus'   => 'A',
                    'telhp'       => $request->telhp,
                    'case_code'   => $request->case,
                    'case_desc'   => $case->description,
                    'remarks'     => $request->remarks,
                    'lastuser'    => 'system',
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")
                ]);

                //edit no telefon dkt patmast
                if($mrn_ != '00000'){
                    DB::table('hisdb.pat_mast')
                        ->where("mrn",'=',$mrn)
                        ->update([
                            'telhp'    => $request->telhp,
                            'telh'    => $request->telh,
                        ]);
                }

            }

        DB::commit();

        } catch (Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }
    }

    public function delEvent(Request $request){

            DB::table('apptbook')
            ->where('idno','=',$request->idno)
            ->update([
                'recstatus'   => 'D',
                'deluser'     => 'system',
                'deldate'     => Carbon::now("Asia/Kuala_Lumpur")
            ]);
    }

    public function save_patient_add(Request $request){
        DB::beginTransaction();

        $table = DB::table('pat_mast');

        $array_insert = [
            'compcode' => '9A',
            'adduser' => 'system',
            'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
            'recstatus' => 'A'
        ];

        foreach ($request->field as $key => $value) {
            $array_insert[$value] = $request[$request->field[$key]];
        }

        $array_insert['first_visit_date'] = Carbon::createFromFormat('d/m/Y', $request->first_visit_date);
        $array_insert['last_visit_date'] = Carbon::createFromFormat('d/m/Y', $request->last_visit_date);


        try {

            //1. save into pat_mast
            $mrn = $this->defaultSysparam($request->sysparam['source'],$request->sysparam['trantype']);
            $array_insert['MRN'] = $mrn;
            $lastidno = $table->insertGetId($array_insert);

            //2. edit apptbook mrn, telh, telhp
            $old_apptbook = DB::table('apptbook')
                ->where('idno','=',$request->apptbook_idno)
                ->first();

            $newtitle = $mrn.' - '.$old_apptbook->pat_name.' - '.$request->telhp.' - '.$old_apptbook->case_code.' - '.$old_apptbook->remarks;

            DB::table('apptbook')
                ->where('idno','=',$request->apptbook_idno)
                ->update([
                    'title' => $newtitle,
                    'mrn' => $mrn,
                    'telno' => $request->telh,
                    'telhp' => $request->telhp
                ]);


           
            $responce = new stdClass();
            $responce->lastMrn = $mrn;
            $responce->lastidno = $lastidno;
            echo json_encode($responce);

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            report($e);

            return response('Error'.$e, 500);
        }
    }

    public function save_patient_edit(Request $request){
        DB::beginTransaction();

        $table = DB::table('pat_mast');

        $array_update = [
            'compcode' => '9A',
            'upduser' => 'system',
            'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
            'recstatus' => 'A'
        ];

        foreach ($request->field as $key => $value) {
            $array_update[$value] = $request[$request->field[$key]];
        }

        array_pull($array_update, 'first_visit_date');
        array_pull($array_update, 'last_visit_date');

        try {

            //////////where//////////
            //1. edit pat_mast
            $table = $table->where('idno','=',$request->idno);
            $table->update($array_update);

            //2. edit apptbook mrn, telh, telhp
            $old_apptbook = DB::table('apptbook')
                ->where('idno','=',$request->apptbook_idno)
                ->first();

            $newtitle = $old_apptbook->mrn.' - '.$old_apptbook->pat_name.' - '.$request->telhp.' - '.$old_apptbook->case_code.' - '.$old_apptbook->remarks;

            DB::table('apptbook')
                ->where('idno','=',$request->apptbook_idno)
                ->update([
                    'title' => $newtitle,
                    'telno' => $request->telh,
                    'telhp' => $request->telhp
                ]);

            $queries = DB::getQueryLog();

            $responce = new stdClass();
            $responce->sql = $queries;
            echo json_encode($responce);

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }
    }

}
