<?php

namespace App\Http\Controllers\hisdb;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Auth;
use Carbon\Carbon;

class Appointment_rscController extends defaultController
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
        $ALCOLOR = DB::table('sysdb.sysparam')
                    ->where('compcode','=',session('compcode'))
                    ->where('source','=','HIS')
                    ->where('trantype','=','ALCOLOR')
                    ->first();

        $otstatus = DB::table('hisdb.otstatus')
                    ->select('code','description')
                    ->where('compcode','=',session('compcode'))
                    ->get();

        if(Auth::user()->groupid == "patient"){
            $pat_info = DB::table('hisdb.pat_mast')
                    ->where('loginid','=',Auth::user()->username)
                    ->first();
                    
            return view('hisdb.apptrsc.apptrsc2',compact('ALCOLOR','pat_info'));
        }

        return view('hisdb.apptrsc.apptrsc2',compact('ALCOLOR','otstatus'));
    }

    public function apptrsc_rsc_iframe(Request $request){   

        $apptresrc = DB::table('hisdb.apptresrc')
                        ->where('compcode',session('compcode'))
                        ->where('TYPE','OT')
                        ->first();

        $apptresrc_all = DB::table('hisdb.apptresrc')
                        ->where('compcode',session('compcode'))
                        ->where('TYPE','OT')
                        ->get();

        $op_unit = DB::table('hisdb.discipline')
                        ->where('compcode',session('compcode'))
                        ->get();

        $episode = DB::table('hisdb.episode')
                        ->where('mrn',$request->mrn)
                        ->where('episno',$request->episno)
                        ->first();

        $patmast = DB::table('hisdb.pat_mast')
                        ->where('mrn',$request->mrn)
                        ->first();

        return view('hisdb.apptrsc.apptrsc_iframe',compact('apptresrc','apptresrc_all','op_unit','episode','patmast'));
    }

    public function wardbook_iframe(Request $request){
        return view('hisdb.apptrsc.wardbook_iframe');
    }

    public function table(Request $request)
    {   
        switch($request->action){
            case 'populate_new_episode_by_mrn_apptrsc':
                return $this->populate_new_episode_by_mrn_apptrsc($request);
            case 'get_table_ot_iframe':
                return $this->get_table_ot_iframe($request);
            case 'get_icd':
                return $this->get_icd($request);
            
            default:
                return 'error happen..';
        }
    }

    public function get_icd(Request $request){
        $table = DB::table('hisdb.diagtab')
                    ->select('icdcode','description')
                    ->where('type','icd-10')
                    ->where('recstatus',"ACTIVE")
                    ->limit(100)
                    ->get();

        foreach ($table as $key => $value) {
            $value->name = $value->icdcode;
        }


        $responce = new stdClass();
        $responce->success = true;
        $responce->results = $table;
        return json_encode($responce);
    }

    public function get_table_ot_iframe(Request $request){

        $table = DB::table('hisdb.apptbook')
                    ->where('loccode','=',$request->loccode)
                    ->where('recstatus','=',"A")
                    ->where('surgery_date','=', $request->date);
                    // ->get();

        
        //////////paginate//////////
        $paginate = $table->paginate($request->rows);

        foreach ($paginate as $key => $value) {
            $value->start = Carbon::createFromFormat('Y-m-d H:i:s',$value->start)->format('d-m-Y h:i A');
            $value->end = Carbon::createFromFormat('Y-m-d H:i:s',$value->end)->format('d-m-Y h:i A');
        }
        
        $responce = new stdClass();
        $responce->page = $paginate->currentPage();
        $responce->total = $paginate->lastPage();
        $responce->records = $paginate->total();
        $responce->rows = $paginate->items();
        $responce->sql_query = $this->getQueries($table);
        return json_encode($responce);

    }

    public function form(Request $request)
    {   
        DB::enableQueryLog();
        switch($request->action){
            case 'reserve_ot_iframe':
                return $this->reserve_ot_iframe($request);
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

    public function reserve_ot_iframe(Request $request){
        
        DB::beginTransaction();

        try {

            if(empty($request->date) || empty($request->time_start) || empty($request->time_end) ){
                throw new \Exception('Reserve need date, time start and end', 500);
            }

            $episode = DB::table('hisdb.episode')
                        ->where('compcode',session('compcode'))
                        ->where('mrn',$request->mrn)
                        ->where('episno',$request->episno)
                        ->first();

            $doctor = DB::table('hisdb.doctor')
                        ->where('compcode',session('compcode'))
                        ->where('doctorcode',$episode->admdoctor)
                        ->first();

            $patmast = DB::table('hisdb.pat_mast')
                        ->where('compcode',session('compcode'))
                        ->where('mrn',$request->mrn)
                        ->first();

            $apptidno = DB::table('hisdb.apptbook')->insertGetId([
                    'compcode'    => session('compcode'),
                    'title'       => $request->patname.' - '.$request->telhp.substr(preg_replace("/\s+/", " ", $request->remarks), 0, 30),
                    'loccode'     => $request->resourcecode,
                    'icnum'       => $patmast->Newic,
                    'mrn'         => $request->mrn,
                    'pat_name'    => $request->pat_name,
                    'apptdatefr'  => $request->date,
                    'apptdateto'  => $request->date,
                    'start'       => $request->date.' '.$request->time_start,
                    'end'         => $request->date.' '.$request->time_end,
                    'telno'       => $request->telno,
                    'apptstatus'  => 'attend',
                    'telhp'       => $request->telhp,
                    'remarks'     => $request->remarks,
                    'ot_room'     => $request->resourcecode,
                    'surgery_date'=> $request->date,
                    'op_unit'     => strtoupper($request->op_unit),
                    'oper_type'   => strtoupper($request->oper_type),
                    // 'oper_status' => strtoupper($request->oper_status),
                    'diagnosis' => $request->diagnosis,
                    'procedure' => $request->procedure,
                    'doctorname'   => strtoupper($doctor->doctorname),
                    // 'anaesthetist'   => strtoupper($request->anaesthetist),
                    // 'surgeon'   => strtoupper($request->surgeon),
                    'admdoctor'   => $episode->admdoctor,
                    'recstatus'   => 'A',
                    'computerid'   => session('computerid'),
                    'adduser'     => session('username'),
                    'adddate'     => Carbon::now("Asia/Kuala_Lumpur"),
                    'lastuser'    => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur"),
                    'type'        => 'OT',
                    'iPesakit'    => $request->iPesakit,
                    'cArm'        => $request->cArm,
                ]);

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
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
            case 'apptbook_iframe':

                $obj = DB::table('hisdb.apptbook')
                    ->where('loccode','=',$request->drrsc)
                    ->where('recstatus','=',"A")
                    ->whereBetween('start', [$request->start, $request->end])
                    ->get();

                $events = [];
        
                for($i = 1; $i <= 31; $i++){
                    $days = 0;
                    $reg_date;
                    foreach($obj as $key => $value){
                        $day = Carbon::createFromFormat('Y-m-d H:i:s',$value->start);
                        if($day->day == $i){
                            $reg_date = Carbon::createFromFormat('Y-m-d H:i:s',$value->start)->format('Y-m-d');
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

                break;
            case 'appt_ph':
                $select = DB::table('hisdb.apptph')
                    ->select('apptph.datefr as start','apptph.dateto as end','apptphcolor.color as color','apptph.remark as title')
                    ->leftJoin('hisdb.apptphcolor', 'apptph.idno', '=', 'apptphcolor.phidno')
                    ->where('apptphcolor.userid', '=' , session('username'))
                    ->whereBetween('apptph.datefr', [$request->start, $request->end])
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
        
        DB::beginTransaction();

        try {
            if($request->Class2 == 'OT'){
                
                $apptidno = DB::table('hisdb.apptbook')->insertGetId([
                    'compcode'    => session('compcode'),
                    'title'       => $request->patname.' - '.$request->telhp.substr(preg_replace("/\s+/", " ", $request->remarks), 0, 30),
                    'loccode'     => $request->doctor,
                    'icnum'       => $request->icnum,
                    'mrn'         => $request->mrn,
                    'pat_name'    => $request->patname,
                    'apptdatefr'  => $request->apptdatefr_day,
                    'apptdateto'  => $request->apptdatefr_day,
                    'start'       => $request->apptdatefr_day.' '.$request->start_time,
                    'end'         => $request->apptdatefr_day.' '.$request->end_time,
                    'telno'       => $request->telh,
                    'apptstatus'  => $request->status,
                    'telhp'       => $request->telhp,
                    'remarks'     => $request->remarks,
                    'ot_room'     => $request->doctor,
                    'surgery_date'=> $request->apptdatefr_day,
                    'op_unit'     => strtoupper($request->op_unit),
                    'oper_type'   => strtoupper($request->oper_type),
                    // 'oper_status' => strtoupper($request->oper_status),
                    'diagnosis' => $request->diagnosis,
                    'procedure' => $request->procedure,
                    'doctorname'   => strtoupper($request->doctorname),
                    'anaesthetist'   => strtoupper($request->anaesthetist),
                    'surgeon'   => strtoupper($request->surgeon),
                    'recstatus'   => 'A',
                    'computerid'   => $request->computerid,
                    'adduser'     => session('username'),
                    'adddate'     => Carbon::now("Asia/Kuala_Lumpur"),
                    'lastuser'    => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur"),
                    'type'        => $request->Class2,
                    'iPesakit'    => $request->iPesakit,
                    'cArm'        => $request->cArm,

                ]);

            }else{

                $apptidno = DB::table('hisdb.apptbook')->insertGetId([
                    'compcode'    => session('compcode'),
                    'title'       => $request->patname.' - '.$request->telhp.substr(preg_replace("/\s+/", " ", $request->remarks), 0, 30),
                    'loccode'     => $request->doctor,
                    'icnum'       => $request->icnum,
                    'mrn'         => $request->mrn,
                    'pat_name'    => $request->patname,
                    'apptdatefr'  => $request->apptdatefr_day,
                    'apptdateto'  => $request->apptdatefr_day,
                    'start'       => $request->apptdatefr_day.' '.$request->start_time,
                    'end'         => $request->apptdatefr_day.' '.$request->end_time,
                    'telno'       => $request->telh,
                    'apptstatus'  => $request->status,
                    'telhp'       => $request->telhp,
                    'remarks'     => $request->remarks,
                    'recstatus'   => 'A',
                    'computerid'   => $request->computerid,
                    'adduser'     => session('username'),
                    'adddate'     => Carbon::now("Asia/Kuala_Lumpur"),
                    'lastuser'    => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur"),
                    'type'        => $request->Class2,
                    'iPesakit'    => $request->iPesakit,
                    'cArm'        => $request->cArm,
                ]);
            }

            if(!empty($request->iPesakit)){
                $pat_mast = DB::table('hisdb.pat_mast')
                                ->where('compcode',session('compcode'))
                                ->where('mrn',$request->mrn)
                                ->first();
                if($pat_mast->iPesakit != $request->iPesakit){
                    DB::table('hisdb.pat_mast')
                            ->where('compcode',session('compcode'))
                            ->where('mrn',$request->mrn)
                            ->update([
                                'iPesakit'  => $request->iPesakit
                            ]);
                }
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

                $apptbook = DB::table('hisdb.apptbook')
                    ->where('compcode',session('compcode'))
                    ->where('loccode',$request->loccode)
                    ->where('start',$request->start);

                if(!$apptbook->exists()){
                    DB::table('hisdb.apptbook')
                        ->where('idno','=',$request->idno)
                        ->update([
                            'apptdatefr'  => $request->start,
                            'apptdateto'  => $request->end,
                            'start'       => $request->start,
                            'end'         => $request->end
                        ]);
                }

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


                if($request->Class2 == 'OT'){
                    DB::table('hisdb.apptbook')
                        ->where('idno','=',$request->idno)
                        ->update([
                            'compcode'    => session('compcode'),
                            'title'       => $request->patname.' - '.$request->telhp.' - '.substr(preg_replace("/\s+/", " ", $request->remarks), 0, 30),
                            'loccode'     => $request->doctor,
                            'mrn'         => $request->mrn,
                            'icnum'       => $request->icnum,
                            'pat_name'    => $request->patname,
                            'apptdatefr'  => $request->apptdatefr_day,
                            'apptdateto'  => $request->apptdatefr_day,
                            'start'       => $request->apptdatefr_day.' '.$request->start_time,
                            'end'         => $request->apptdatefr_day.' '.$request->end_time,
                            'telno'       => $request->telh,
                            'apptstatus'  => $request->status,
                            'recstatus'   => 'A',
                            'telhp'       => $request->telhp,
                            'remarks'     => $request->remarks,
                            'ot_room'     => strtoupper($request->doctor),
                            'op_unit'     => strtoupper($request->op_unit),
                            'oper_type'   => strtoupper($request->oper_type),
                            // 'oper_status' => strtoupper($request->oper_status),
                            'diagnosis' => $request->diagnosis,
                            'procedure' => $request->procedure,
                            'doctorname'   => strtoupper($request->doctorname),
                            'anaesthetist'   => strtoupper($request->anaesthetist),
                            'surgeon'   => strtoupper($request->surgeon),
                            'computerid'   => $request->computerid,
                            'lastuser'    => session('username'),
                            'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur"),
                            'iPesakit'    => $request->iPesakit,
                            'cArm'        => $request->cArm,
                        ]);
                }else{
                    DB::table('hisdb.apptbook')
                        ->where('idno','=',$request->idno)
                        ->update([
                            'compcode'=> session('compcode'),
                            'title'       => $request->patname.' - '.$request->telhp.' - '.substr(preg_replace("/\s+/", " ", $request->remarks), 0, 30),
                            'loccode'     => $request->doctor,
                            'mrn'         => $request->mrn,
                            'icnum'       => $request->icnum,
                            'pat_name'    => $request->patname,
                            'apptdatefr'  => $request->apptdatefr_day,
                            'apptdateto'  => $request->apptdatefr_day,
                            'start'       => $request->apptdatefr_day.' '.$request->start_time,
                            'end'         => $request->apptdatefr_day.' '.$request->end_time,
                            'telno'       => $request->telh,
                            'apptstatus'  => $request->status,
                            'recstatus'   => 'A',
                            'telhp'       => $request->telhp,
                            'remarks'     => $request->remarks,
                            'computerid'   => $request->computerid,
                            'lastuser'    => session('username'),
                            'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur"),
                            'iPesakit'    => $request->iPesakit,
                            'cArm'        => $request->cArm,
                        ]);
                }

                if(!empty($request->iPesakit)){
                    $pat_mast = DB::table('hisdb.pat_mast')
                                    ->where('compcode',session('compcode'))
                                    ->where('mrn',$request->mrn)
                                    ->first();
                    if($pat_mast->iPesakit != $request->iPesakit){
                        DB::table('hisdb.pat_mast')
                                ->where('compcode',session('compcode'))
                                ->where('mrn',$request->mrn)
                                ->update([
                                    'iPesakit'  => $request->iPesakit
                                ]);
                    }
                }
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
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

    public function save_patient_add(Request $request){
        DB::beginTransaction();

        $table = DB::table('hisdb.pat_mast');

        $array_insert = [
            'compcode' => session('compcode'),
            'adduser' => session('username'),
            'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
            'recstatus' => 'A'
        ];

        foreach ($request->field as $key => $value) {
            $array_insert[$value] = $request[$request->field[$key]];
        }

        $array_insert['first_visit_date'] = Carbon::createFromFormat('d/m/Y', $request->first_visit_date);
        $array_insert['last_visit_date'] = Carbon::createFromFormat('d/m/Y', $request->last_visit_date);
        $array_insert['iPesakit'] = $request->iPesakit;


        try {

            //1. save into pat_mast
            $mrn = $this->defaultSysparam($request->sysparam['source'],$request->sysparam['trantype']);
            $array_insert['MRN'] = $mrn;
            $lastidno = $table->insertGetId($array_insert);

            //2. edit apptbook mrn, telh, telhp
            $old_apptbook = DB::table('hisdb.apptbook')
                ->where('idno','=',$request->apptbook_idno)
                ->first();

            $newtitle = $mrn.' - '.$old_apptbook->pat_name.' - '.$request->telhp.' - '.$old_apptbook->case_code.' - '.$old_apptbook->remarks;

            DB::table('hisdb.apptbook')
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

        $table = DB::table('hisdb.pat_mast');

        $array_update = [
            'compcode' => session('compcode'),
            'upduser' => session('username'),
            'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
            'recstatus' => 'A'
        ];

        foreach ($request->field as $key => $value) {
            $array_update[$value] = $request[$request->field[$key]];
        }

        array_pull($array_update, 'first_visit_date');
        array_pull($array_update, 'last_visit_date');
        array_pull($array_update, 'iPesakit');

        try {

            //////////where//////////
            //1. edit pat_mast
            $table = $table->where('idno','=',$request->idno);
            $table->update($array_update);

            //2. edit apptbook mrn, telh, telhp
            $old_apptbook = DB::table('hisdb.apptbook')
                ->where('idno','=',$request->apptbook_idno)
                ->first();

            $newtitle = $old_apptbook->mrn.' - '.$old_apptbook->pat_name.' - '.$request->telhp.' - '.$old_apptbook->case_code.' - '.$old_apptbook->remarks;

            DB::table('hisdb.apptbook')
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

    public function populate_new_episode_by_mrn_apptrsc(Request $request){
        $episode_latest = DB::table('hisdb.episode')
                            ->where('mrn','=',$request->mrn)
                            ->orderBy('episno', 'desc')
                            ->first();

        $pat_mast = DB::table('hisdb.pat_mast')
                            ->where('mrn','=',$request->mrn)
                            ->first();


        $responce = new stdClass();
        $responce->episode = $episode_latest;
        $responce->pat_mast = $pat_mast;
        echo json_encode($responce);
    }
    
}
