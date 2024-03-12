<?php

namespace App\Http\Controllers\hisdb;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;
use PDF;

class DoctorNoteController extends defaultController
{
    
    var $table;
    var $duplicateCode;
    
    public function __construct()
    {
        $this->middleware('auth');
        // $this->duplicateCode = "chgtype";
    }
    
    public function show(Request $request)
    {
        return view('hisdb.doctornote.doctornote');
    }

    public function bpgraph(Request $request)
    {
        $pat = DB::table('hisdb.pat_mast')
                    ->where('compcode',session('compcode'))
                    ->where('MRN',$request->mrn)
                    ->first();


        return view('hisdb.doctornote.doctornote_bpgraph',compact('pat'));
    }
    
    public function table(Request $request)
    {
        switch($request->action){
            case 'get_table_date_curr':     // for current
                return $this->get_table_date_curr($request);
            case 'get_table_date_past':     // for past history
                return $this->get_table_date_past($request);
            case 'get_table_doctornote':
                return $this->get_table_doctornote($request);
            case 'dialog_icd':
                return $this->dialog_icd($request);
            case 'get_bp_graph':
                return $this->get_bp_graph($request);
            
            // transaction stuff
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
            
            default:
                return 'error happen..';
        }
    }
    
    public function form(Request $request)
    {
        
        DB::enableQueryLog();
        
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
            
            case 'get_table_doctornote':
                return $this->get_table_doctornote($request);
            
            case 'doctornote_save':
                return $this->add_notes($request);
            
            case 'doctornote_transaction_save':
                return $this->doctornote_transaction_save($request);
            
            case 'save_refLetter':
                switch($request->oper){
                    case 'add':
                        return $this->add_refLetter($request);
                    case 'edit':
                        return $this->edit_refLetter($request);
                    default:
                        return 'error happen..';
                }
            
            case 'get_table_refLetter':
                return $this->get_table_refLetter($request);
            
            default:
                return 'error happen..';
        }
    
    }
    
    public function add(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('hisdb.episode')
                ->where('mrn','=',$request->mrn_doctorNote)
                ->where('episno','=',$request->episno_doctorNote)
                ->where('compcode','=',session('compcode'))
                ->update([
                    // 'remarks' => $request->remarks,
                    'diagfinal' => $request->diagfinal,
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                ]);
            
            DB::table('hisdb.patexam')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_doctorNote,
                        'episno' => $request->episno_doctorNote,
                        'examination' => $request->examination,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'recorddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'recordtime' => Carbon::now("Asia/Kuala_Lumpur"),
                    ]);
            
            $doctorcode_obj = DB::table('hisdb.doctor')
                    ->select('doctorcode')
                    ->where('compcode','=',session('compcode'))
                    ->where('loginid','=',session('username'));
            
            $doctorcode=null;
            if($doctorcode_obj->exists()){
                $doctorcode = $doctorcode_obj->first()->doctorcode;
            }
            
            DB::table('hisdb.pathealth')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_doctorNote,
                        'episno' => $request->episno_doctorNote,
                        'complain' => $request->complain,
                        'clinicnote' => $request->clinicnote,
                        'drugh' => $request->drugh,
                        'allergyh' => $request->allergyh,
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
                        'doctorcode'  => $doctorcode,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'recorddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'recordtime' => Carbon::now("Asia/Kuala_Lumpur"),
                    ]);
            
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
            
            DB::table('hisdb.episdiag')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_doctorNote,
                        'episno' => $request->episno_doctorNote,
                        'icdcode' => $request->icdcode,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
    
    }
    
    public function edit(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('hisdb.episode')
                ->where('mrn','=',$request->mrn_doctorNote)
                ->where('episno','=',$request->episno_doctorNote)
                ->where('compcode','=',session('compcode'))
                ->update([
                    // 'remarks' => $request->remarks,
                    'diagfinal' => $request->diagfinal,
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
                ->where('mrn','=',$request->mrn_doctorNote)
                ->where('episno','=',$request->episno_doctorNote)
                ->where('recorddate','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate_doctorNote))
                ->where('compcode','=',session('compcode'));
            
            $pathealth = DB::table('hisdb.pathealth')
                ->where('mrn','=',$request->mrn_doctorNote)
                ->where('episno','=',$request->episno_doctorNote)
                ->where('recorddate','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate_doctorNote))
                ->where('compcode','=',session('compcode'));
            
            $pathistory = DB::table('hisdb.pathistory')
                ->where('mrn','=',$request->mrn_doctorNote)
                ->where('recorddate','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate_doctorNote))
                ->where('compcode','=',session('compcode'));
            
            $episdiag = DB::table('hisdb.episdiag')
                ->where('mrn','=',$request->mrn_doctorNote)
                ->where('episno','=',$request->episno_doctorNote)
                ->where('compcode','=',session('compcode'));
            
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
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'recorddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'recordtime' => Carbon::now("Asia/Kuala_Lumpur"),
                    ]);
            }
            
            $doctorcode_obj = DB::table('hisdb.doctor')
                    ->select('doctorcode')
                    ->where('compcode','=',session('compcode'))
                    ->where('loginid','=',session('username'));
            
            $doctorcode=null;
            if($doctorcode_obj->exists()){
                $doctorcode = $doctorcode_obj->first()->doctorcode;
            }
            
            if($pathealth->exists()){
                $pathealth
                    ->update([
                        'complain' => $request->complain,
                        'clinicnote' => $request->clinicnote,
                        'drugh' => $request->drugh,
                        'allergyh' => $request->allergyh,
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
                        // 'doctorcode'  => $doctorcode,
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }else{
                DB::table('hisdb.pathealth')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_doctorNote,
                        'episno' => $request->episno_doctorNote,
                        'complain' => $request->complain,
                        'clinicnote' => $request->clinicnote,
                        'drugh' => $request->drugh,
                        'allergyh' => $request->allergyh,
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
                        'doctorcode'  => $doctorcode,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'recorddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'recordtime' => Carbon::now("Asia/Kuala_Lumpur"),
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
            
            $queries = DB::getQueryLog();
            // dump($queries);
            
            DB::commit();
            
            $responce = new stdClass();
            $responce->mrn = $request->mrn_doctorNote;
            $responce->episno = $request->episno_doctorNote;
            $responce->recorddate = $request->recorddate_doctorNote;
            
            return json_encode($responce);
        
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function get_transaction_table($request){
        
        if($request->rows == null){
            $request->rows = 100;
        }
        
        $table_chgtrx = DB::table('hisdb.chargetrx as trx') //ambil dari patmast balik
                            ->select('trx.auditno',
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
                            ->where('trx.compcode','=',session('compcode'))
                            
                            ->leftJoin('hisdb.chgmast', function($join) use ($request){
                                $join = $join->on('chgmast.chgcode', '=', 'trx.chgcode')
                                                ->where('chgmast.compcode','=',session('compcode'));
                            })
                            ->leftJoin('hisdb.instruction', function($join) use ($request){
                                $join = $join->on('instruction.inscode', '=', 'trx.instruction')
                                                ->where('instruction.compcode','=',session('compcode'));
                            })
                            ->leftJoin('hisdb.freq', function($join) use ($request){
                                $join = $join->on('freq.freqcode', '=', 'trx.frequency')
                                                ->where('freq.compcode','=',session('compcode'));
                            })
                            ->leftJoin('hisdb.dose', function($join) use ($request){
                                $join = $join->on('dose.dosecode', '=', 'trx.doscode')
                                                ->where('dose.compcode','=',session('compcode'));
                            })
                            ->leftJoin('hisdb.drugindicator', function($join) use ($request){
                                $join = $join->on('drugindicator.drugindcode', '=', 'trx.drugindicator')
                                                ->where('drugindicator.compcode','=',session('compcode'));
                            });
                            // ->leftJoin('hisdb.chgmast','chgmast.chgcode','=','trx.chgcode')
                            // ->leftJoin('hisdb.instruction','instruction.inscode','=','trx.instruction')
                            // ->leftJoin('hisdb.freq','freq.freqcode','=','trx.frequency')
                            // ->leftJoin('hisdb.dose','dose.dosecode','=','trx.doscode')
                            // ->leftJoin('hisdb.drugindicator','drugindicator.drugindcode','=','trx.drugindicator');
        
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
    
    public function get_chgcode(Request $request){
        
        // $pharcode = DB::table('sysdb.sysparam')
        //             ->where('compcode','=',session('compcode'))
        //             ->where('source','=','OE')
        //             ->where('trantype','=','PHAR')
        //             ->first();
        
        $data = DB::table('hisdb.chgmast')
                    ->where('compcode','=',session('compcode'))
                    // ->where('chggroup','=',$pharcode->pvalue1)
                    ->where('active','=',1)
                    ->select('chgcode as code','description as description');
        
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
    
    public function get_table_date_curr(Request $request){
        
        $responce = new stdClass();
        
        $pathealth_obj = DB::table('hisdb.pathealth')
            ->select('mrn','episno','recordtime','adddate','adduser')
            ->where('compcode','=',session('compcode'))
            ->where('mrn','=',$request->mrn)
            ->where('episno','=',$request->episno);
        
        if(!$pathealth_obj->exists()){
            $responce->data = [];
            return json_encode($responce);
        }
        
        $episode_obj = DB::table('hisdb.episode as e')
            ->select('e.mrn','e.episno','p.recordtime','p.adddate','p.adduser','e.admdoctor','d.doctorname')
            ->leftJoin('hisdb.pathealth as p', function($join) use ($request){
                $join = $join->on('p.mrn', '=', 'e.mrn');
                $join = $join->on('p.episno', '=', 'e.episno');
                $join = $join->on('p.compcode', '=', 'e.compcode');
            })->leftJoin('hisdb.doctor as d', function($join) use ($request){
                $join = $join->on('d.doctorcode', '=', 'e.admdoctor');
                $join = $join->on('d.compcode', '=', 'e.compcode');
            })
            ->where('e.compcode','=',session('compcode'))
            ->where('e.mrn','=',$request->mrn)
            ->where('e.episno','=',$request->episno)
            ->orderBy('p.adddate','desc');
        
        if($episode_obj->exists()){
            $episode_obj = $episode_obj->get();
            
            $data = [];
            
            foreach ($episode_obj as $key => $value) {
                if(!empty($value->adddate)){
                    $date['date'] =  Carbon::createFromFormat('Y-m-d', $value->adddate)->format('d-m-Y').' '.$value->recordtime;
                }else{
                    $date['date'] =  '-';
                }
                $date['mrn'] = $value->mrn;
                $date['episno'] = $value->episno;
                $date['adduser'] = $value->adduser;
                $date['doctorname'] = $value->doctorname;
                
                array_push($data,$date);
            }
            
            $responce->data = $data;
        }else{
            $responce->data = [];
        }
        
        return json_encode($responce);
    
    }
    
    public function get_table_date_past(Request $request){
        
        $responce = new stdClass();
        
        $episode_obj = DB::table('hisdb.episode as e')
            ->select('e.mrn','e.episno','p.recordtime','p.recorddate','p.adduser','e.admdoctor','d.doctorname')
            ->leftJoin('hisdb.pathealth as p', function($join) use ($request){
                $join = $join->on('p.mrn', '=', 'e.mrn');
                $join = $join->on('p.episno', '=', 'e.episno');
                $join = $join->on('p.compcode', '=', 'e.compcode');
            })->leftJoin('hisdb.doctor as d', function($join) use ($request){
                $join = $join->on('d.doctorcode', '=', 'e.admdoctor');
                $join = $join->on('d.compcode', '=', 'e.compcode');
            })
            ->where('e.compcode','=',session('compcode'))
            ->where('e.mrn','=',$request->mrn)
            ->orderBy('p.adddate','desc');
        
        // $patexam_obj = DB::table('hisdb.pathealth')
        //     ->select('mrn','episno','recordtime','adddate','adduser')
        //     ->where('compcode','=',session('compcode'))
        //     ->where('mrn','=',$request->mrn)
        //     ->orderBy('adddate','desc');
        
        if($episode_obj->exists()){
            $episode_obj = $episode_obj->get();
            
            $data = [];
            
            foreach ($episode_obj as $key => $value) {
                if(!empty($value->adddate)){
                    $date['date'] =  Carbon::createFromFormat('Y-m-d H:i:s', $value->adddate)->format('d-m-Y').' '.$value->recordtime;
                }else{
                    $date['date'] =  '-';
                }
                $date['mrn'] = $value->mrn;
                $date['episno'] = $value->episno;
                if(!empty($value->adduser)){
                    $date['adduser'] = $value->adduser;
                }else{
                    $date['adduser'] =  '-';
                }
                $date['adduser'] = $value->adduser;
                $date['doctorname'] = $value->doctorname;
                
                array_push($data,$date);
            }
            
            $responce->data = $data;
        }else{
            $responce->data = [];
        }
        
        return json_encode($responce);
    
    }
    
    public function get_table_doctornote(Request $request){
        
        $responce = new stdClass();
        
        $episode_obj = DB::table('hisdb.episode')
            ->select('diagfinal')
            ->where('compcode','=',session('compcode'))
            ->where('mrn','=',$request->mrn)
            ->where('episno','=',$request->episno);
        
        if(!empty($request->recorddate) && $request->recorddate != '-'){
            $pathealth_obj = DB::table('hisdb.pathealth')
                ->where('compcode','=',session('compcode'))
                ->where('mrn','=',$request->mrn)
                ->where('episno','=',$request->episno)
                ->where('recorddate','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate));
                // ->orderBy('recordtime','desc');
            
            $pathistory_obj = DB::table('hisdb.pathistory')
                ->where('compcode','=',session('compcode'))
                ->where('mrn','=',$request->mrn)
                ->where('recorddate','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate));
            
            $patexam_obj = DB::table('hisdb.patexam')
                ->where('compcode','=',session('compcode'))
                ->where('mrn','=',$request->mrn)
                ->where('episno','=',$request->episno)
                ->where('recorddate','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate));
        }
        
        $episdiag_obj = DB::table('hisdb.episdiag')
            ->where('compcode','=',session('compcode'))
            ->where('mrn','=',$request->mrn)
            ->where('episno','=',$request->episno);
        
        $pathealthadd_obj = DB::table('hisdb.pathealthadd')
            ->where('compcode','=',session('compcode'))
            ->where('mrn','=',$request->mrn)
            ->where('episno','=',$request->episno);
        
        if($episode_obj->exists()){
            $episode_obj = $episode_obj->first();
            $responce->episode = $episode_obj;
        }
        
        if(!empty($request->recorddate) && $request->recorddate != '-'){
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
        }
        
        if($episdiag_obj->exists()){
            $episdiag_obj = $episdiag_obj->first();
            $responce->episdiag = $episdiag_obj;
        }
        
        if($pathealthadd_obj->exists()){
            $pathealthadd_obj = $pathealthadd_obj->first();
            $responce->pathealthadd = $pathealthadd_obj;
        }
        
        $responce->transaction = json_decode($this->get_transaction_table($request));
        
        return json_encode($responce);
    
    }
    
    public function add_refLetter(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('hisdb.patreferral')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'refdate' => $request->refdate,
                        'refaddress' => $request->refaddress,
                        'refdoc' => $request->refdoc,
                        'reftitle' => $request->reftitle,
                        'refdiag' => $request->refdiag,
                        'refplan' => $request->refplan,
                        'refprescription' => $request->refprescription,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
    
    }
    
    public function edit_refLetter(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $patreferral = DB::table('hisdb.patreferral')
                ->where('mrn','=',$request->mrn)
                ->where('episno','=',$request->episno)
                ->where('compcode','=',session('compcode'));
            
            if($patreferral->exists()){
                $patreferral
                    ->update([
                        'refdate' => $request->refdate,
                        'refaddress' => $request->refaddress,
                        'refdoc' => $request->refdoc,
                        'reftitle' => $request->reftitle,
                        'refdiag' => $request->refdiag,
                        'refplan' => $request->refplan,
                        'refprescription' => $request->refprescription,
                        'upduser'  => session('username'),
                        'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }else{
                DB::table('hisdb.patreferral')
                        ->insert([
                            'compcode' => session('compcode'),
                            'mrn' => $request->mrn,
                            'episno' => $request->episno,
                            'refdate' => $request->refdate,
                            'refaddress' => $request->refaddress,
                            'refdoc' => $request->refdoc,
                            'reftitle' => $request->reftitle,
                            'refdiag' => $request->refdiag,
                            'refplan' => $request->refplan,
                            'refprescription' => $request->refprescription,
                            'adduser'  => session('username'),
                            'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                            'computerid' => session('computerid'),
                        ]);
            }
            
            $queries = DB::getQueryLog();
            // dump($queries);
            
            DB::commit();
            
            $responce = new stdClass();
            
            return json_encode($responce);
        
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function get_table_refLetter(Request $request){
        
        $responce = new stdClass();
        
        $episode_obj = DB::table('hisdb.episode')
            ->select('diagfinal as diagfinal_ref')
            ->where('compcode','=',session('compcode'))
            ->where('mrn','=',$request->mrn)
            ->where('episno','=',$request->episno);
        
        $date_obj = DB::table('hisdb.pathealth')
            // ->select('recorddate')
            ->where('compcode','=',session('compcode'))
            ->where('mrn','=',$request->mrn)
            ->where('episno','=',$request->episno)
            ->first();
            
        // dd($date_obj->recorddate);
        
        if(!empty($request->recorddate) && $request->recorddate != '-'){
            $pathealth_obj = DB::table('hisdb.pathealth')
                ->select(
                    'complain as complain_ref',
                    'clinicnote as clinicnote_ref',
                    // 'drugh as drugh_ref',
                    // 'allergyh as allergyh_ref',
                    'followuptime as followuptime_ref',
                    'followupdate as followupdate_ref',
                    'plan_ as plan_ref',
                    'height as height_ref',
                    'weight as weight_ref',
                    'bp_sys1 as bp_sys1_ref',
                    'bp_dias2 as bp_dias2_ref',
                    'pulse as pulse_ref',
                    'temperature as temperature_ref',
                    'respiration as respiration_ref',
                    'adduser as adduser_ref',
                    'adddate as adddate_ref'
                )
                ->where('compcode','=',session('compcode'))
                ->where('mrn','=',$request->mrn)
                ->where('episno','=',$request->episno)
                ->where('recorddate','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate));
                // ->orderBy('recordtime','desc');
            
            $pathistory_obj = DB::table('hisdb.pathistory')
                ->select(
                    'pmh as pmh_ref',
                    'drugh as drugh_ref',
                    'allergyh as allergyh_ref',
                    'socialh as socialh_ref',
                    'fmh as fmh_ref'
                )
                ->where('compcode','=',session('compcode'))
                ->where('mrn','=',$request->mrn)
                ->where('recorddate','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate));
            
            $patexam_obj = DB::table('hisdb.patexam')
                ->select('examination as examination_ref')
                ->where('compcode','=',session('compcode'))
                ->where('mrn','=',$request->mrn)
                ->where('episno','=',$request->episno)
                ->where('recorddate','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate));
        }
        
        $episdiag_obj = DB::table('hisdb.episdiag')
            ->select('icdcode as icdcode_ref')
            ->where('compcode','=',session('compcode'))
            ->where('mrn','=',$request->mrn)
            ->where('episno','=',$request->episno);
        
        $pathealthadd_obj = DB::table('hisdb.pathealthadd')
            ->where('compcode','=',session('compcode'))
            ->where('mrn','=',$request->mrn)
            ->where('episno','=',$request->episno);
        
        // form_refLetter
        $pat_mast = DB::table('hisdb.pat_mast')
            // ->select('Name')
            ->where('CompCode',session('compcode'))
            ->where('MRN','=',$request->mrn)
            ->where('Episno','=',$request->episno)
            ->first();
        
        $sysparam = DB::table('sysdb.sysparam')
            // ->select('pvalue1')
            ->where('compcode',session('compcode'))
            ->where('source','=','HIS')
            ->where('trantype','=','REFHDR')
            ->first();
        
        $sys_reftitle = ucwords(strtolower($pat_mast->Name)).' '.$sysparam->pvalue1;
        $responce->sys_reftitle = $sys_reftitle;
        
        $adduser = session('username');
        $responce->adduser = $adduser;
        
        $patreferral_obj = DB::table('hisdb.patreferral')
            ->where('compcode','=',session('compcode'))
            ->where('mrn','=',$request->mrn)
            ->where('episno','=',$request->episno);
        
        if($episode_obj->exists()){
            $episode_obj = $episode_obj->first();
            $responce->episode = $episode_obj;
        }
        
        if(!empty($request->recorddate) && $request->recorddate != '-'){
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
        }
        
        if($episdiag_obj->exists()){
            $episdiag_obj = $episdiag_obj->first();
            $responce->episdiag = $episdiag_obj;
        }
        
        if($pathealthadd_obj->exists()){
            $pathealthadd_obj = $pathealthadd_obj->first();
            $responce->pathealthadd = $pathealthadd_obj;
        }
        
        // form_refLetter
        if($patreferral_obj->exists()){
            $patreferral_obj = $patreferral_obj->first();
            $responce->patreferral = $patreferral_obj;
        }
        
        $responce->transaction = json_decode($this->get_transaction_table($request));
        
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

    public function get_bp_graph(Request $request){
        // $table = DB::table('hisdb.bp_graph')
        //                 ->get();

        $table = DB::table('nursing.nurshandover')
                    ->select()
                    ->where('compcode',session('compcode'))
                    ->where('mrn',$request->mrn)
                    ->where('episno',$request->episno)
                    ->get();

        $responce = new stdClass();
        $responce->data = $table;
        return json_encode($responce);
    
    }
    
    public function add_notes(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $doctorcode_obj = DB::table('hisdb.doctor')
                    ->select('doctorcode')
                    ->where('compcode','=',session('compcode'))
                    ->where('loginid','=',session('username'));
            
            $doctorcode=null;
            if($doctorcode_obj->exists()){
                $doctorcode = $doctorcode_obj->first()->doctorcode;
            }
            
            DB::table('hisdb.pathealthadd')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn,
                    'episno' => $request->episno,
                    'additionalnote' => $request->additionalnote,
                    'doctorcode'  => $doctorcode,
                    'adduser'  => session('username'),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur")
                ]);
             
            DB::commit();
        
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
        
        }
    
    }
    
    public function doctornote_transaction_save(Request $request){
        
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
                    'trxdate' => Carbon::now("Asia/Kuala_Lumpur"),
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
                    'lastuser' => session('username'),
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
            
            return response($e->getMessage(), 500);
        
        }
    
    }
    
    public function iograph(Request $request){
        
        if(empty($request->mrn) || empty($request->episno)){
            abort(404);
        }
        
        $intakeoutput = DB::table('nursing.intakeoutput')
                        ->where('compcode',session('compcode'))
                        ->where('mrn',$request->mrn)
                        ->where('episno',$request->episno)
                        ->first();
        
        $pat_mast = DB::table('hisdb.pat_mast')
                    ->where('CompCode',session('compcode'))
                    ->where('MRN','=',$request->mrn)
                    ->first();
        
        // dd($intakeoutput);
        
        return view('hisdb.doctornote.iograph_pdfmake',compact('intakeoutput','pat_mast'));
        
    }
    
    public function showpdf(Request $request){
        
        $mrn = $request->mrn;
        $episno = $request->episno;
        if(empty($mrn) || empty($episno)){
            abort(404);
        }
        
        $patreferral = DB::table('hisdb.patreferral as ptrf')
            ->select('ptrf.idno','ptrf.compcode','ptrf.mrn','ptrf.episno','ptrf.adduser','ptrf.adddate','ptrf.upduser','ptrf.upddate','ptrf.computerid','ptrf.refdate','ptrf.refaddress','ptrf.refdoc','ptrf.reftitle','ptrf.refdiag','ptrf.refplan','ptrf.refprescription','pm.Name','pm.Newic')
            ->leftJoin('hisdb.pat_mast as pm', function($join) use ($request){
                $join = $join->on('pm.MRN', '=', 'ptrf.mrn')
                                ->where('pm.compcode','=',session('compcode'));
            })
            ->where('ptrf.compcode','=',session('compcode'))
            ->where('ptrf.mrn','=',$mrn)
            ->where('ptrf.episno','=',$episno)
            ->first();
        
        $company = DB::table('sysdb.company')
            ->where('compcode','=',session('compcode'))
            ->first();
        
        $ini_array = [
            'docname' => $patreferral->refdoc,
            'name' => $patreferral->Name,
            'newic' => $patreferral->Newic,
            'reftitle' => $patreferral->reftitle,
            'reffor' => $patreferral->refdiag,
            'exam' => $patreferral->refplan,
            'invest' => $patreferral->refprescription,
            'refdate' => $patreferral->refdate
        ];
        
        return view('hisdb.doctornote.refLetter_pdfmake',compact('ini_array'));
        
    }
    
}