<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use stdClass;
use App\User;
use DB;
use Carbon\Carbon;
use Auth;
use Session;

class DoctornoteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function table(Request $request)
    {   
        switch($request->action){
            case 'get_table_date_curr':          // for current
                return $this->get_table_date_curr($request);
            case 'get_table_date_past':     // for past history
                return $this->get_table_date_past($request);
            case 'get_table_doctornote':
                return $this->get_table_doctornote($request);
            case 'get_table_doctornote_div':
                return $this->get_table_doctornote_div($request);
            case 'dialog_icd':
                return $this->dialog_icd($request);
            
            //transaction stuff
            case 'get_transaction_table':
                return $this->get_transaction_table($request);
            case 'get_chgcode':
                return $this->get_chgcode($request);
            case 'get_drugindcode':
                return $this->get_drugindcode($request);
            case 'get_freqcode':
                return $this->get_freqcode($request);
            case 'get_dosecode':
                return $this->get_dosecode($request);
            case 'get_inscode':
                return $this->get_inscode($request);

            //event stuff
            case 'doctornote_event':
                return $this->doctornote_event($request);

            default:
                return 'error happen..';
        }
    }


    public function form(Request $request)
    {   
        switch($request->action){
            case 'save_table_doctornote':

                switch($request->oper){
                    case 'add':
                        return $this->add($request);
                    case 'edit':
                        return $this->edit($request);
                    default:
                        return 'error happen..';
                }

            case 'doctornote_save':
                return $this->add_notes($request);

            case 'submit_patient':
                return $this->submit_patient($request);

            default:
                return 'error happen..';
        }
    }

    public function index(Request $request){ 

        $centers = $this->get_maiwp_center_dept();

        if(!empty($request->changedept)){

            $department = DB::table('sysdb.department')
                            ->where('compcode', session('compcode'))
                            ->where('deptcode', $request->changedept);

            if($department->exists()){
                $request->session()->put('dept', $department->first()->deptcode);
                $request->session()->put('dept_desc', $department->first()->description);
            }
        }
        
        return view('doctornote',compact('centers'));
    }

    public function get_table_doctornote($request){
        $table_patm = DB::table('hisdb.pat_mast') //ambil dari patmast balik
                ->select(['pat_mast.idno','pat_mast.CompCode','episode.MRN','episode.Episno','pat_mast.Name','pat_mast.Call_Name','pat_mast.addtype','pat_mast.Address1','pat_mast.Address2','pat_mast.Address3','pat_mast.Postcode','pat_mast.citycode','pat_mast.AreaCode','pat_mast.StateCode','pat_mast.CountryCode','pat_mast.telh','pat_mast.telhp','pat_mast.telo','pat_mast.Tel_O_Ext','pat_mast.ptel','pat_mast.ptel_hp','pat_mast.ID_Type','pat_mast.idnumber','pat_mast.Newic','pat_mast.Oldic','pat_mast.icolor','pat_mast.Sex','pat_mast.DOB','pat_mast.Religion','pat_mast.AllergyCode1','pat_mast.AllergyCode2','pat_mast.Century','pat_mast.Citizencode','pat_mast.OccupCode','pat_mast.Staffid','pat_mast.MaritalCode','pat_mast.LanguageCode','pat_mast.TitleCode','pat_mast.RaceCode','pat_mast.bloodgrp','pat_mast.Accum_chg','pat_mast.Accum_Paid','pat_mast.first_visit_date','pat_mast.Reg_Date','pat_mast.last_visit_date','pat_mast.last_episno','pat_mast.PatStatus','pat_mast.Confidential','pat_mast.Active','pat_mast.FirstIpEpisNo','pat_mast.FirstOpEpisNo','pat_mast.AddUser','pat_mast.AddDate','pat_mast.Lastupdate','pat_mast.LastUser','pat_mast.OffAdd1','pat_mast.OffAdd2','pat_mast.OffAdd3','pat_mast.OffPostcode','pat_mast.MRFolder','pat_mast.MRLoc','pat_mast.MRActive','pat_mast.OldMrn','pat_mast.NewMrn','pat_mast.Remarks','pat_mast.RelateCode','pat_mast.ChildNo','pat_mast.CorpComp','pat_mast.Email','pat_mast.Email_official','pat_mast.CurrentEpis','pat_mast.NameSndx','pat_mast.BirthPlace','pat_mast.TngID','pat_mast.PatientImage','pat_mast.pAdd1','pat_mast.pAdd2','pat_mast.pAdd3','pat_mast.pPostCode','pat_mast.DeptCode','pat_mast.DeceasedDate','pat_mast.PatientCat','pat_mast.PatType','pat_mast.PatClass','pat_mast.upduser','pat_mast.upddate','pat_mast.recstatus','pat_mast.loginid','pat_mast.pat_category','pat_mast.idnumber_exp','episode.doctorstatus','episode.reg_time','episode.payer','episode.pyrmode','episode.regdept','episode.reff_rehab','episode.reff_physio','episode.reff_diet','episode.stats_rehab','episode.stats_physio','episode.stats_diet']);

                $table_patm = $table_patm->leftJoin('hisdb.episode', function($join) use ($request){
                        $join = $join->on('episode.mrn', '=', 'pat_mast.MRN');
                        $join = $join->where(
                                function($query){
                                    return $query
                                            ->whereNull('episode.episstatus')
                                            ->orWhere('episode.episstatus','!=','C');
                                }
                        );
                        
                    });

                $table_patm = $table_patm->where('pat_mast.compcode','=',session('compcode'))
                                        ->where('episode.reg_date' ,'=', $request->filterVal[0]);
                

                if(!empty($request->sidx)){
                    $table_patm = $table_patm->orderBy($request->sidx, $request->sord);
                }else{
                    $table_patm = $table_patm->orderBy('episode.reg_time', 'desc');
                }

        //////////paginate/////////
        $paginate = $table_patm->paginate($request->rows);

        $responce = new stdClass();
        $responce->page = $paginate->currentPage();
        $responce->total = $paginate->lastPage();
        $responce->records = $paginate->total();
        $responce->rows = $paginate->items();
        $responce->sql = $table_patm->toSql();
        $responce->sql_bind = $table_patm->getBindings();
        return json_encode($responce);

    }

    public function get_transaction_table($request){
        if($request->rows == null){
            $request->rows = 100;
        }

        $table_chgtrx = DB::table('hisdb.chargetrx as trx') //ambil dari patmast balik
                            ->select('trx.id',
                                'trx.trxdate',
                                'trx.trxtime',
                                'trx.chgcode as chg_code',
                                'trx.quantity',
                                'trx.remarks',
                                'trx.instruction as ins_code',
                                'trx.doscode as dos_code',
                                'trx.frequency as fre_code',
                                'trx.drugindicator as dru_code',

                                'chgmast.description as chg_desc',
                                'instruction.description as ins_desc',
                                'dose.dosedesc as dos_desc',
                                'freq.freqdesc as fre_desc',
                                'drugindicator.drugindcode as dru_desc')

                            ->where('trx.mrn' ,'=', $request->mrn)
                            ->where('trx.episno' ,'=', $request->episno)
                            ->where('trx.compcode','=',session('compcode'));

        if($request->isudept != 'CLINIC'){
            $table_chgtrx->where('trx.isudept','=',$request->isudept);
        }

        $table_chgtrx = $table_chgtrx
                            ->leftJoin('hisdb.chgmast','chgmast.chgcode','=','trx.chgcode')
                            ->leftJoin('hisdb.instruction','instruction.inscode','=','trx.instruction')
                            ->leftJoin('hisdb.freq','freq.freqcode','=','trx.frequency')
                            ->leftJoin('hisdb.dose','dose.dosecode','=','trx.doscode')
                            ->leftJoin('hisdb.drugindicator','drugindicator.drugindcode','=','trx.drugindicator')
                            ->orderBy('trx.id','desc');

        //////////paginate/////////
        $paginate = $table_chgtrx->paginate($request->rows);

        $responce = new stdClass();
        $responce->page = $paginate->currentPage();
        $responce->total = $paginate->lastPage();
        $responce->records = $paginate->total();
        $responce->rows = $paginate->items();
        $responce->sql = $table_chgtrx->toSql();
        $responce->sql_bind = $table_chgtrx->getBindings();
        return json_encode($responce);

    }

    public function getEvent($obj){
        $events = [];

        for ($i=1; $i <= 31; $i++) {
            $days = 0;
            $reg_date;
            foreach ($obj as $key => $value) {
                $day = Carbon::createFromFormat('Y-m-d',$value->reg_date);
                if($day->day == $i){
                    $reg_date = $value->reg_date;
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

    }

    public function transaction_save(Request $request){
        DB::beginTransaction();
        try {
            $table = DB::table('hisdb.chargetrx');

            $chgmast = DB::table('hisdb.chgmast')
                        ->where('compcode','=',session('compcode'))
                        ->where('chgcode','=',$request->chg_desc)
                        ->first();

            $episode = DB::table('hisdb.episode')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno)
                        ->first();
                        
            $isudept = $episode->regdept;

            if($request->oper == 'edit'){
                $table->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno)
                        ->where('id','=',$request->id);

                $array_edit = [
                    'chgcode' => $request->chg_desc,
                    'chggroup' =>  $chgmast->chggroup,
                    'chgtype' =>  $chgmast->chgtype,
                    'quantity' => $request->quantity,
                    'instruction' => $request->ins_desc,
                    'doscode' => $request->dos_desc,
                    'frequency' => $request->fre_desc,
                    'drugindicator' => $request->dru_desc,
                    'remarks' => $request->remarks,
                    'lastuser' => Auth::user()->username,
                    'lastupdate' => Carbon::now("Asia/Kuala_Lumpur")
                ];

                $table->update($array_edit);
            }else if($request->oper == 'add'){
                $array_insert = [
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn,
                    'episno' => $request->episno,
                    'trxtype' => 'OE',
                    'trxdate' => $request->trxdate,
                    'chgcode' => $request->chg_desc,
                    'chggroup' =>  $chgmast->chggroup,
                    'chgtype' =>  $chgmast->chgtype,
                    'instruction' => $request->ins_desc,
                    'doscode' => $request->dos_desc,
                    'frequency' => $request->fre_desc,
                    'drugindicator' => $request->dru_desc,
                    'remarks' => $request->remarks,
                    'billflag' => '0',
                    'quantity' => $request->quantity,
                    'isudept' => $isudept,
                    'trxtime' => Carbon::now("Asia/Kuala_Lumpur"),
                    'lastuser' => Auth::user()->username,
                    'lastupdate' => Carbon::now("Asia/Kuala_Lumpur")
                ];

                $table->insert($array_insert);
            }else if($request->oper == 'del'){
                $table->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno)
                        ->where('id','=',$request->id)->delete();
            }

            

            $responce = new stdClass();
            $responce->success = 'success';
            echo json_encode($responce);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }
    }


    public function add(Request $request){

        DB::beginTransaction();

        try {

            $arrival_date = Carbon::createFromFormat('d-m-Y', $request->arrival_date)->format('Y-m-d');

            DB::table('hisdb.episode')
                ->where('mrn','=',$request->mrn_doctorNote)
                ->where('episno','=',$request->episno_doctorNote)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'remarks' => $request->remarks,
                    'diagfinal' => $request->diagfinal,
                    'dry_weight' => $request->dry_weight,
                    'duration_hd' => $request->duration_hd,
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                ]);

            $seldate = new Carbon($request->sel_date);
            $recorddate = Carbon::now("Asia/Kuala_Lumpur");
            $recorddate->day($seldate->day);
            $recorddate->month($seldate->month);
            $recorddate->year($seldate->year);

            $pathealth = DB::table('hisdb.pathealth')
                ->where('compcode','=',session('compcode'))
                ->where('mrn','=',$request->mrn_doctorNote)
                ->where('episno','=',$request->episno_doctorNote)
                ->where('arrival_date','=',$arrival_date);

            if($pathealth->exists()){
                $pathealth
                    ->update([
                        'compcode' => session('compcode'),
                        'clinicnote' => $request->clinicnote,
                        'followuptime' => $request->followuptime,
                        'followupdate' => $request->followupdate,
                        'plan_' => $request->plan_,
                        'height' => $request->height,
                        'weight' => $request->weight,
                        'bp_sys1' => $request->bp_sys1,
                        'bp_dias2' => $request->bp_dias2,
                        'pulse' => $request->pulse,
                        'temperature' => $request->temperature,
                        'respiration' => $request->respiration,
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString()
                    ]);
            }else{
                DB::table('hisdb.pathealth')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_doctorNote,
                        'episno' => $request->episno_doctorNote,
                        'arrival_date' => $arrival_date,
                        'clinicnote' => $request->clinicnote,
                        'followuptime' => $request->followuptime,
                        'followupdate' => $request->followupdate,
                        'plan_' => $request->plan_,
                        'height' => $request->height,
                        'weight' => $request->weight,
                        'bp_sys1' => $request->bp_sys1,
                        'bp_dias2' => $request->bp_dias2,
                        'pulse' => $request->pulse,
                        'temperature' => $request->temperature,
                        'respiration' => $request->respiration,
                        'adduser'  => session('username'),
                        'adddate'  => $recorddate,
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'recordtime' => Carbon::now("Asia/Kuala_Lumpur"),
                    ]);
            }


            if(!empty($request->examination)){

                $patexam = DB::table('hisdb.patexam')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$request->mrn_doctorNote)
                    ->where('episno','=',$request->episno_doctorNote);

                if($patexam->exists()){
                    $patexam
                        ->update([
                            'examination' => $request->examination,
                            'lastuser'  => session('username'),
                            'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        ]);
                }else{
                    DB::table('hisdb.patexam')
                        ->insert([
                            'compcode' => session('compcode'),
                            'mrn' => $request->mrn_doctorNote,
                            'episno' => $request->episno_doctorNote,
                            'examination' => $request->examination,
                            'adduser'  => session('username'),
                            'adddate'  => $recorddate,
                            'lastuser'  => session('username'),
                            'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                            'recorddate' => $recorddate,
                            'recordtime' => Carbon::now("Asia/Kuala_Lumpur"),
                        ]);
                }
            }

            $pathistory = DB::table('hisdb.pathistory')
                ->where('compcode','=',session('compcode'))
                ->where('mrn','=',$request->mrn_doctorNote);

            if($pathistory->exists()){
                $pathistory
                    ->update([
                        'pmh' => $request->pmh,
                        'drugh' => $request->drugh,
                        'allergyh' => $request->allergyh,
                        'socialh' => $request->socialh,
                        'fmh' => $request->fmh,
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }else{

                DB::table('hisdb.pathistory')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_doctorNote,
                        'pmh' => $request->pmh,
                        'drugh' => $request->drugh,
                        'allergyh' => $request->allergyh,
                        'socialh' => $request->socialh,
                        'fmh' => $request->fmh,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'recorddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'recordtime' => Carbon::now("Asia/Kuala_Lumpur"),
                    ]);
            }


            if(!empty($request->icdcode)){
                $episdiag = DB::table('hisdb.episdiag')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$request->mrn_doctorNote)
                    ->where('episno','=',$request->episno_doctorNote);

                if($episdiag->exists()){
                    $episdiag
                        ->update([
                            'icdcode' => $request->icdcode,
                        ]);
                }else{
                    DB::table('hisdb.episdiag')
                        ->insert([
                            'compcode' => session('compcode'),
                            'mrn' => $request->mrn_doctorNote,
                            'episno' => $request->episno_doctorNote,
                            'icdcode' => $request->icdcode,
                            'adduser'  => session('username'),
                            'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        ]);
                } 
            }


            // $queries = DB::getQueryLog();
            // dump($queries);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response('Error DB rollback!'.$e, 500);
        }
    }

    public function edit(Request $request){

        DB::beginTransaction();

        try {
            $arrival_date = $request->arrival_date;

            DB::table('hisdb.episode')
                ->where('mrn','=',$request->mrn_doctorNote)
                ->where('episno','=',$request->episno_doctorNote)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'remarks' => $request->remarks,
                    'diagfinal' => $request->diagfinal,
                    'dry_weight' => $request->dry_weight,
                    'duration_hd' => $request->duration_hd,
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                ]);

            // DB::table('hisdb.pathealthadd')
            //     ->insert([
            //         'compcode' => session('compcode'),
            //         'mrn' => $request->mrn_doctorNote,
            //         'episno' => $request->episno_doctorNote,
            //         'additionalnote' => $request->additionalnote,
            //     ]);

            $patexam = DB::table('hisdb.patexam')
                ->where('compcode','=',session('compcode'))
                ->where('mrn','=',$request->mrn_doctorNote)
                ->where('episno','=',$request->episno_doctorNote);

            $pathealth = DB::table('hisdb.pathealth')
                ->where('compcode','=',session('compcode'))
                ->where('mrn','=',$request->mrn_doctorNote)
                ->where('episno','=',$request->episno_doctorNote)
                ->where('arrival_date','=',$arrival_date);
                // ->where('recordtime','=',$request->recordtime);

            $pathistory = DB::table('hisdb.pathistory')
                ->where('compcode','=',session('compcode'))
                ->where('mrn','=',$request->mrn_doctorNote);

            $episdiag = DB::table('hisdb.episdiag')
                ->where('compcode','=',session('compcode'))
                ->where('mrn','=',$request->mrn_doctorNote)
                ->where('episno','=',$request->episno_doctorNote);

            if($patexam->exists()){
                $patexam->update([
                        'examination' => $request->examination,
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }else{
                DB::table('hisdb.patexam')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_doctorNote,
                        'episno' => $request->episno_doctorNote,
                        'examination' => $request->examination,
                        'adduser'  => session('username'),
                        'adddate'  => $request->sel_date,
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'recorddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'recordtime' => $request->recordtime,
                    ]);
            }

            if($pathealth->exists()){
                $pathealth
                    ->update([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_doctorNote,
                        'episno' => $request->episno_doctorNote,
                        'clinicnote' => $request->clinicnote,
                        'followuptime' => $request->followuptime,
                        'followupdate' => $request->followupdate,
                        'plan_' => $request->plan_,
                        'height' => $request->height,
                        'weight' => $request->weight,
                        'bp_sys1' => $request->bp_sys1,
                        'bp_dias2' => $request->bp_dias2,
                        'pulse' => $request->pulse,
                        'temperature' => $request->temperature,
                        'respiration' => $request->respiration,
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString()
                    ]);
            }else{
                DB::table('hisdb.pathealth')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_doctorNote,
                        'episno' => $request->episno_doctorNote,
                        'arrival_date' => $arrival_date,
                        'clinicnote' => $request->clinicnote,
                        'followuptime' => $request->followuptime,
                        'followupdate' => $request->followupdate,
                        'plan_' => $request->plan_,
                        'height' => $request->height,
                        'weight' => $request->weight,
                        'bp_sys1' => $request->bp_sys1,
                        'bp_dias2' => $request->bp_dias2,
                        'pulse' => $request->pulse,
                        'temperature' => $request->temperature,
                        'respiration' => $request->respiration,
                        'adduser'  => session('username'),
                        'adddate'  => $request->sel_date,
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'recordtime' => $request->recordtime,
                    ]);
            }

            if($pathistory->exists()){
                $pathistory
                    ->update([
                        'pmh' => $request->pmh,
                        'drugh' => $request->drugh,
                        'allergyh' => $request->allergyh,
                        'socialh' => $request->socialh,
                        'fmh' => $request->fmh,
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }else{
                DB::table('hisdb.pathistory')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_doctorNote,
                        'pmh' => $request->pmh,
                        'drugh' => $request->drugh,
                        'allergyh' => $request->allergyh,
                        'socialh' => $request->socialh,
                        'fmh' => $request->fmh,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'recorddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'recordtime' => Carbon::now("Asia/Kuala_Lumpur"),
                    ]);
            }

            if(!empty($request->icdcode)){
                if($episdiag->exists()){
                    $episdiag
                        ->update([
                            'icdcode' => $request->icdcode,
                        ]);
                }else{
                    DB::table('hisdb.episdiag')
                        ->insert([
                            'compcode' => session('compcode'),
                            'mrn' => $request->mrn_doctorNote,
                            'episno' => $request->episno_doctorNote,
                            'icdcode' => $request->icdcode,
                            'adduser'  => session('username'),
                            'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        ]);
                }
            }

            // $queries = DB::getQueryLog();
            // dump($queries);
            
            DB::commit();


            $responce = new stdClass();

            return json_encode($responce);


        } catch (\Exception $e) {
            DB::rollback();

            return response('Error DB rollback!'.$e, 500);
        }
    }

    public function get_table_date_curr(Request $request){

        $responce = new stdClass();

        $data = [];

        $dialysis_episode = DB::table('hisdb.dialysis_episode')
            ->select('mrn','episno','arrival_time','arrival_date')
            ->where('compcode','=',session('compcode'))
            ->where('mrn','=',$request->mrn)
            ->where('episno','=',$request->episno)
            ->orderBy('arrival_date','desc');

        if($dialysis_episode->exists()){
            $dialysis_episode = $dialysis_episode->get();

            foreach ($dialysis_episode as $key => $value) {

                $pathealth = DB::table('hisdb.pathealth')
                    ->select('mrn','episno','recordtime','adddate','adduser')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$value->mrn)
                    ->where('episno','=',$value->episno)
                    ->where('arrival_date','=',$value->arrival_date)
                    ->orderBy('idno','desc');

                if($pathealth->exists()){
                    
                    $pathealth = $pathealth->get();

                    foreach ($pathealth as $key2 => $value2) {
                        $date['date'] = Carbon::createFromFormat('Y-m-d', $value->arrival_date)->format('d-m-Y');
                        $date['mrn'] = $value2->mrn;
                        $date['episno'] = $value2->episno;
                        $date['adduser'] = $value2->adduser;
                        $date['adddate'] = $value2->adddate;
                        $date['recordtime'] = $value2->recordtime;
                        $date['type'] = 'pathealth';

                        array_push($data,$date);
                    }

                }else{

                    if(!Carbon::createFromFormat('Y-m-d', $value->arrival_date)->isToday()){
                        continue;
                    }
                    
                    $date['date'] = Carbon::createFromFormat('Y-m-d', $value->arrival_date)->format('d-m-Y');
                    $date['mrn'] = $value->mrn;
                    $date['episno'] = $value->episno;
                    $date['adduser'] = session('username');
                    $date['adddate'] = $value->arrival_date;
                    $date['recordtime'] = $value->arrival_time;
                    $date['type'] = 'episode';

                    array_push($data,$date);
                }
            }

            $responce->data = $data;
        }else{
            $responce->data = [];
        }

        return json_encode($responce);
    }

    public function get_table_date_past(Request $request){

        $responce = new stdClass();

        $data = [];

        $dialysis_episode = DB::table('hisdb.dialysis_episode')
                    ->select('mrn','episno','arrival_time','arrival_date')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$request->mrn)
                    ->orderBy('arrival_date','desc');

        if($dialysis_episode->exists()){
            $dialysis_episode = $dialysis_episode->get();

            foreach ($dialysis_episode as $key => $value) {
                $pathealth = DB::table('hisdb.pathealth')
                    ->select('mrn','episno','recordtime','adddate','adduser')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$value->mrn)
                    ->where('episno','=',$value->episno)
                    ->where('arrival_date','=',$value->arrival_date)
                    ->orderBy('idno','desc');

                if($pathealth->exists()){
                    
                    $pathealth = $pathealth->get();

                    foreach ($pathealth as $key2 => $value2) {
                        $date['date'] = Carbon::createFromFormat('Y-m-d', $value->arrival_date)->format('d-m-Y');
                        $date['mrn'] = $value2->mrn;
                        $date['episno'] = $value2->episno;
                        $date['adduser'] = $value2->adduser;
                        $date['adddate'] = $value2->adddate;
                        $date['recordtime'] = $value2->recordtime;
                        $date['type'] = 'pathealth';

                        array_push($data,$date);
                    }

                }else{

                    if(!Carbon::createFromFormat('Y-m-d', $value->arrival_date)->isToday()){
                        continue;
                    }
                    
                    $date['date'] = Carbon::createFromFormat('Y-m-d', $value->arrival_date)->format('d-m-Y');
                    $date['mrn'] = $value->mrn;
                    $date['episno'] = $value->episno;
                    $date['adduser'] = session('username');
                    $date['adddate'] = $value->arrival_date;
                    $date['recordtime'] = $value->arrival_time;
                    $date['type'] = 'episode';

                    array_push($data,$date);
                }
            }

            $responce->data = $data;

        }else{
            $responce->data = [];
        }

        return json_encode($responce);
    }

    public function get_table_doctornote_div(Request $request){

        $responce = new stdClass();

        $arrival_date = Carbon::createFromFormat('d-m-Y', $request->arrival_date)->format('Y-m-d');

        $episode_obj = DB::table('hisdb.episode')
            ->select('remarks','diagfinal','dry_weight','duration_hd','lastupdate as lastupdate_','lastuser as lastuser_')
            ->where('compcode','=',session('compcode'))
            ->where('mrn','=',$request->mrn)
            ->where('episno','=',$request->episno);

        $pathealth_obj = DB::table('hisdb.pathealth')
            ->where('compcode','=',session('compcode'))
            ->where('mrn','=',$request->mrn)
            ->where('episno','=',$request->episno)
            ->where('arrival_date','=',$arrival_date);
            // ->where('recordtime','=',$request->recordtime);

        $pathistory_obj = DB::table('hisdb.pathistory')
            ->where('compcode','=',session('compcode'))
            ->where('mrn','=',$request->mrn);

        $patexam_obj = DB::table('hisdb.patexam')
            ->where('compcode','=',session('compcode'))
            ->where('mrn','=',$request->mrn)
            ->where('episno','=',$request->episno);

        $episdiag_obj = DB::table('hisdb.episdiag')
            ->where('compcode','=',session('compcode'))
            ->where('mrn','=',$request->mrn)
            ->where('episno','=',$request->episno);

        // $pathealthadd_obj = DB::table('hisdb.pathealthadd')
        //     ->where('compcode','=',session('compcode'))
        //     ->where('mrn','=',$request->mrn)
        //     ->where('episno','=',$request->episno);

        // $nursassess_doc_obj = DB::table('nursing.nursassessment')
        //     ->select('vs_pulse AS pulse','vs_temperature AS temperature','vs_respiration AS respiration')
        //     ->where('compcode','=',session('compcode'))
        //     ->where('mrn','=',$request->mrn)
        //     ->where('episno','=',$request->episno);

        if($episode_obj->exists()){
            $episode_obj = $episode_obj->first();
            $responce->episode = $episode_obj;
        }

        if($pathealth_obj->exists()){
            $pathealth_obj = $pathealth_obj->first();
            $responce->pathealth = $pathealth_obj;
        }

        if($pathistory_obj->exists()){
            $pathistory_obj = $pathistory_obj->first();
            $responce->pathistory = $pathistory_obj;
        }

        if($patexam_obj->exists()){
            $patexam_obj = $patexam_obj->first();
            $responce->patexam = $patexam_obj;
        }

        if($episdiag_obj->exists()){
            $episdiag_obj = $episdiag_obj->first();
            $responce->episdiag = $episdiag_obj;
        }

        // if($pathealthadd_obj->exists()){
        //     $pathealthadd_obj = $pathealthadd_obj->first();
        //     $responce->pathealthadd = $pathealthadd_obj;
        // }

        // if($nursassess_doc_obj->exists()){
        //     $nursassess_doc_obj = $nursassess_doc_obj->first();
        //     $responce->nursassess_doc = $nursassess_doc_obj;
        // }  //akan conflict dgn pathealth

        // $responce->transaction = json_decode($this->get_transaction_table($request));

        return json_encode($responce);
    }

    public function dialog_icd(Request $request){

        $icdver = DB::table('sysdb.sysparam')
                        ->select('pvalue1')
                        ->where('compcode','=',session('compcode'))
                        ->where('source','=','MR')
                        ->where('trantype','=','ICD')
                        ->first();

        $table = DB::table('hisdb.diagtab')
                    ->where('type','=',$icdver->pvalue1)
                    ->orderBy('idno','asc');

        if(!empty($request->searchCol)){
            $searchCol_array = $request->searchCol;

            $count = array_count_values($searchCol_array);
            // dump($count);

            foreach ($count as $key => $value) {
                $occur_ar = $this->index_of_occurance($key,$searchCol_array);

                $table = $table->where(function ($table) use ($request,$searchCol_array,$occur_ar) {
                    foreach ($searchCol_array as $key => $value) {
                        $found = array_search($key,$occur_ar);
                        if($found !== false){
                            $table->Where($searchCol_array[$key],'like',$request->searchVal[$key]);
                        }
                    }
                });
            }
        }
        
        $paginate = $table->paginate($request->rows);

        $responce = new stdClass();
        $responce->page = $paginate->currentPage();
        $responce->total = $paginate->lastPage();
        $responce->records = $paginate->total();
        $responce->rows = $paginate->items();
        $responce->sql = $table->toSql();
        $responce->sql_bind = $table->getBindings();

        return json_encode($responce);
    }

    public function add_notes(Request $request){

        DB::beginTransaction();

        try {

            DB::table('hisdb.pathealthadd')
                ->insert([  
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn,
                    'episno' => $request->episno,
                    'additionalnote' => $request->additionalnote,
                    'adduser'  => session('username'),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur")
                    
                ]);

             DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function submit_patient(Request $request){

        DB::beginTransaction();

        try {

            $episode = DB::table('hisdb.episode')
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno)
                            ->where('reg_date','=',$request->reg_date);

            if($episode->exists()){
                $episode
                    ->update([  
                        'doctorstatus' => 'SEEN'
                    ]);

            }else{
                throw new \Exception("Patient doesnt exists");
            }
            
            DB::commit();

            $responce = new stdClass();
            $responce->data = 'success';


            return json_encode($responce);

            

        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function get_chgcode(Request $request){



        $data = DB::table('hisdb.chgmast')
                    ->select('chgcode as code','description as description')
                    ->where('compcode','=',session('compcode'))
                    ->where('active','=',1);


        if (Session::has('chggroup')){
            $data = $data->where('chggroup','=',session('chggroup'));
        }

        $data = $data->orderBy('chgcode', 'ASC');

        if(!empty($request->search)){
            $data = $data->where('description','LIKE','%'.$request->search.'%')->first();
        }else{
            $data = $data->get();
        }
        
        $responce = new stdClass();
        $responce->data = $data;
        return json_encode($responce);
        
    }

    public function get_drugindcode(Request $request){
        $data = DB::table('hisdb.drugindicator')
                ->select('drugindcode as code','description as description');

        if(!empty($request->search)){
            $data = $data->where('description','LIKE','%'.$request->search.'%')->first();
        }else{
            $data = $data->get();
        }
        
        $responce = new stdClass();
        $responce->data = $data;
        return json_encode($responce);
        
    }

    public function get_freqcode(Request $request){
        $data = DB::table('hisdb.freq')
                ->select('freqcode as code','freqdesc as description')
                ->where('compcode','=',session('compcode'));

        if(!empty($request->search)){
            $data = $data->where('freqdesc','LIKE','%'.$request->search.'%')->first();
        }else{
            $data = $data->get();
        }
        
        $responce = new stdClass();
        $responce->data = $data;
        return json_encode($responce);
        
    }

    public function get_dosecode(Request $request){
        $data = DB::table('hisdb.dose')
                ->select('dosecode as code','dosedesc as description')
                ->where('compcode','=',session('compcode'));

        if(!empty($request->search)){
            $data = $data->where('dosedesc','LIKE','%'.$request->search.'%')->first();
        }else{
            $data = $data->get();
        }
        
        $responce = new stdClass();
        $responce->data = $data;
        return json_encode($responce);
        
    }

    public function get_inscode(Request $request){
        $data = DB::table('hisdb.instruction')
                ->select('inscode as code','description as description')
                ->where('compcode','=',session('compcode'));

        if(!empty($request->search)){
            $data = $data->where('description','LIKE','%'.$request->search.'%')->first();
        }else{
            $data = $data->get();
        }
        
        $responce = new stdClass();
        $responce->data = $data;
        return json_encode($responce);
        
    }

    public function doctornote_event(Request $request){
        $emergency = DB::table('hisdb.episode')
                        ->where('compcode','=',session('compcode'))
                        ->whereRaw(
                          "(reg_date >= ? AND reg_date <= ?)", 
                          [
                             $request->start, 
                             $request->end
                         ])
                        // ->whereIn('episode.episstatus', [null,'C','B'])
                        // ->whereNull('episode.episstatus')
                        // ->orWhere('episode.episstatus','!=','C')
                        ->where(
                                function($query){
                                    return $query
                                            ->whereNull('episode.episstatus')
                                            ->orWhere('episode.episstatus','!=','C');
                                }
                        )
                        ->get();

        return $events = $this->getEvent($emergency);
    }

}
