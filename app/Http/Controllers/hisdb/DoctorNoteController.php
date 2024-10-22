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
            case 'get_table_otbook':
                return $this->get_table_otbook($request);
            case 'get_table_radClinic':
                return $this->get_table_radClinic($request);
            case 'get_table_mri':
                return $this->get_table_mri($request);
            case 'get_table_physio':
                return $this->get_table_physio($request);
            case 'get_table_dressing':
                return $this->get_table_dressing($request);
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
            
            case 'save_otbook':
                switch($request->oper){
                    case 'add':
                        return $this->add_otbook($request);
                    case 'edit':
                        return $this->edit_otbook($request);
                    default:
                        return 'error happen..';
                }
            
            case 'save_radClinic':
                switch($request->oper){
                    case 'add':
                        return $this->add_radClinic($request);
                    case 'edit':
                        return $this->edit_radClinic($request);
                    default:
                        return 'error happen..';
                }
            
            case 'save_mri':
                switch($request->oper){
                    case 'add':
                        return $this->add_mri($request);
                    case 'edit':
                        return $this->edit_mri($request);
                    default:
                        return 'error happen..';
                }
            
            case 'save_physio':
                switch($request->oper){
                    case 'add':
                        return $this->add_physio($request);
                    case 'edit':
                        return $this->edit_physio($request);
                    default:
                        return 'error happen..';
                }
            
            case 'save_dressing':
                switch($request->oper){
                    case 'add':
                        return $this->add_dressing($request);
                    case 'edit':
                        return $this->edit_dressing($request);
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
                    // 'recordtime' => Carbon::now("Asia/Kuala_Lumpur"),
                    'recordtime' => $request->recordtime,
                ]);
            
            $pathistory = DB::table('hisdb.pathistory')
                        ->where('mrn','=',$request->mrn_doctorNote)
                        // ->where('recorddate','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate_doctorNote))
                        ->where('compcode','=',session('compcode'));
            
            $doctorcode_obj = DB::table('hisdb.doctor')
                            ->select('doctorcode')
                            ->where('compcode','=',session('compcode'))
                            ->where('loginid','=',session('username'));
            
            $doctorcode = null;
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
                    'genappear' => $request->genappear,
                    'speech' => $request->speech,
                    'moodaffect' => $request->moodaffect,
                    'perception' => $request->perception,
                    'thinking' => $request->thinking,
                    'cognitivefunc' => $request->cognitivefunc,
                    'followuptime' => $request->followuptime,
                    'followupdate' => $request->followupdate,
                    'aetiology' => $request->aetiology,
                    'investigate' => $request->investigate,
                    'treatment' => $request->treatment,
                    'prognosis' => $request->prognosis,
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
                    // 'recordtime' => Carbon::now("Asia/Kuala_Lumpur"),
                    'recordtime' => $request->recordtime,
                ]);
            
            // DB::table('hisdb.pathistory')
            //     ->insert([
            //         'compcode' => session('compcode'),
            //         'mrn' => $request->mrn_doctorNote,
            //         'pmh' => $request->pmh,
            //         'drugh' => $request->drugh,
            //         'allergyh' => $request->allergyh,
            //         'socialh' => $request->socialh,
            //         'fmh' => $request->fmh,
            //         'adduser'  => session('username'),
            //         'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
            //         'lastuser'  => session('username'),
            //         'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
            //         'recorddate' => Carbon::now("Asia/Kuala_Lumpur"),
            //         'recordtime' => Carbon::now("Asia/Kuala_Lumpur"),
            //     ]);
            
            if($pathistory->exists()){
                $pathistory
                    ->update([
                        'psychiatryh' => $request->psychiatryh,
                        'pmh' => $request->pmh,
                        'fmh' => $request->fmh,
                        'personalh' => $request->personalh,
                        'drugh' => $request->drugh,
                        'allergyh' => $request->allergyh,
                        'socialh' => $request->socialh,
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }else{
                DB::table('hisdb.pathistory')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_doctorNote,
                        'psychiatryh' => $request->psychiatryh,
                        'pmh' => $request->pmh,
                        'fmh' => $request->fmh,
                        'personalh' => $request->personalh,
                        'drugh' => $request->drugh,
                        'allergyh' => $request->allergyh,
                        'socialh' => $request->socialh,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'recorddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        // 'recordtime' => Carbon::now("Asia/Kuala_Lumpur"),
                        'recordtime' => $request->recordtime,
                    ]);
            }
            
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
                        ->where('recorddate','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate_doctorNote)->format('Y-m-d'))
                        ->where('recordtime','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate_doctorNote)->format('H:i:s'))
                        ->where('compcode','=',session('compcode'));
            
            $pathealth = DB::table('hisdb.pathealth')
                        ->where('mrn','=',$request->mrn_doctorNote)
                        ->where('episno','=',$request->episno_doctorNote)
                        ->where('recorddate','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate_doctorNote)->format('Y-m-d'))
                        ->where('recordtime','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate_doctorNote)->format('H:i:s'))
                        ->where('compcode','=',session('compcode'));
            
            $pathistory = DB::table('hisdb.pathistory')
                        ->where('mrn','=',$request->mrn_doctorNote)
                        // ->where('recorddate','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate_doctorNote))
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
                        // 'recordtime' => Carbon::now("Asia/Kuala_Lumpur"),
                        'recordtime' => $request->recordtime,
                    ]);
            }
            
            $doctorcode_obj = DB::table('hisdb.doctor')
                            ->select('doctorcode')
                            ->where('compcode','=',session('compcode'))
                            ->where('loginid','=',session('username'));
            
            $doctorcode = null;
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
                        'genappear' => $request->genappear,
                        'speech' => $request->speech,
                        'moodaffect' => $request->moodaffect,
                        'perception' => $request->perception,
                        'thinking' => $request->thinking,
                        'cognitivefunc' => $request->cognitivefunc,
                        'followuptime' => $request->followuptime,
                        'followupdate' => $request->followupdate,
                        'aetiology' => $request->aetiology,
                        'investigate' => $request->investigate,
                        'treatment' => $request->treatment,
                        'prognosis' => $request->prognosis,
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
                        'genappear' => $request->genappear,
                        'speech' => $request->speech,
                        'moodaffect' => $request->moodaffect,
                        'perception' => $request->perception,
                        'thinking' => $request->thinking,
                        'cognitivefunc' => $request->cognitivefunc,
                        'followuptime' => $request->followuptime,
                        'followupdate' => $request->followupdate,
                        'aetiology' => $request->aetiology,
                        'investigate' => $request->investigate,
                        'treatment' => $request->treatment,
                        'prognosis' => $request->prognosis,
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
                        // 'recordtime' => Carbon::now("Asia/Kuala_Lumpur"),
                        'recordtime' => $request->recordtime,
                    ]);
            }
            
            if($pathistory->exists()){
                $pathistory
                    ->update([
                        'psychiatryh' => $request->psychiatryh,
                        'pmh' => $request->pmh,
                        'fmh' => $request->fmh,
                        'personalh' => $request->personalh,
                        'drugh' => $request->drugh,
                        'allergyh' => $request->allergyh,
                        'socialh' => $request->socialh,
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }else{
                DB::table('hisdb.pathistory')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_doctorNote,
                        'psychiatryh' => $request->psychiatryh,
                        'pmh' => $request->pmh,
                        'fmh' => $request->fmh,
                        'personalh' => $request->personalh,
                        'drugh' => $request->drugh,
                        'allergyh' => $request->allergyh,
                        'socialh' => $request->socialh,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'recorddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        // 'recordtime' => Carbon::now("Asia/Kuala_Lumpur"),
                        'recordtime' => $request->recordtime,
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
        
        $table_chgtrx = DB::table('hisdb.chargetrx as trx')
                        ->select('trx.auditno','trx.compcode','trx.idno','trx.mrn','trx.episno','trx.epistype','trx.trxtype','trx.docref','trx.trxdate','trx.chgcode','trx.billcode','trx.costcd','trx.revcd','trx.mmacode','trx.billflag','trx.billdate','trx.billtype','trx.doctorcode','doc.doctorname','trx.chg_class','trx.unitprce','trx.quantity','trx.amount','trx.trxtime','trx.chggroup','trx.qstat','trx.dracccode','trx.cracccode','trx.arprocess','trx.taxamount','trx.billno','trx.invno','trx.uom','trx.uom_recv','trx.billtime','trx.invgroup','trx.reqdept as deptcode','trx.issdept','trx.invcode','trx.resulttype','trx.resultstatus','trx.inventory','trx.updinv','trx.invbatch','trx.doscode','trx.duration','trx.instruction','trx.discamt','trx.disccode','trx.pkgcode','trx.remarks','trx.frequency','trx.ftxtdosage','trx.addinstruction','trx.qtyorder','trx.ipqueueno','trx.itemseqno','trx.doseqty','trx.freqqty','trx.isudept','trx.qtyissue','trx.durationcode','trx.reqdoctor','trx.unit','trx.agreementid','trx.chgtype','trx.adduser','trx.adddate','trx.lastuser','trx.lastupdate','trx.daytaken','trx.qtydispense','trx.takehomeentry','trx.latechargesentry','trx.taxcode','trx.recstatus','trx.drugindicator','trx.id','trx.patmedication','trx.mmaprice','pt.avgcost as cost_price','pt.avgcost as cost_price','dos.dosedesc as doscode_desc','fre.freqdesc as frequency_desc','ins.description as addinstruction_desc','dru.description as drugindicator_desc','cm.brandname','cm.description')
                        ->where('trx.mrn' ,'=', $request->mrn)
                        ->where('trx.episno' ,'=', $request->episno)
                        ->where('trx.compcode','=',session('compcode'))
                        ->where('trx.taxflag',0)
                        ->where('trx.discflag',0)
                        ->where('trx.recstatus','<>','DELETE')
                        ->orderBy('trx.adddate', 'desc');
        
        $table_chgtrx = $table_chgtrx->leftjoin('material.product as pt', function ($join) use ($request){
                            $join = $join->where('pt.compcode', '=', session('compcode'));
                            $join = $join->on('pt.itemcode', '=', 'trx.chgcode');
                            $join = $join->on('pt.uomcode', '=', 'trx.uom_recv');
                            $join = $join->where('pt.unit', '=', session('unit'));
                        });
        
        $table_chgtrx = $table_chgtrx->leftjoin('hisdb.chgmast as cm', function ($join) use ($request){
                            $join = $join->where('cm.compcode', '=', session('compcode'));
                            $join = $join->on('cm.chgcode', '=', 'trx.chgcode');
                            $join = $join->on('cm.uom', '=', 'trx.uom');
                            $join = $join->where('cm.unit', '=', session('unit'));
                        });
        
        $table_chgtrx = $table_chgtrx->leftjoin('hisdb.dose as dos', function ($join) use ($request){
                            $join = $join->where('dos.compcode', '=', session('compcode'));
                            $join = $join->on('dos.dosecode', '=', 'trx.doscode');
                        });
        
        $table_chgtrx = $table_chgtrx->leftjoin('hisdb.freq as fre', function ($join) use ($request){
                            $join = $join->where('fre.compcode', '=', session('compcode'));
                            $join = $join->on('fre.freqcode', '=', 'trx.frequency');
                        });
        
        $table_chgtrx = $table_chgtrx->leftjoin('hisdb.instruction as ins', function ($join) use ($request){
                            $join = $join->where('ins.compcode', '=', session('compcode'));
                            $join = $join->on('ins.inscode', '=', 'trx.addinstruction');
                        });
        
        $table_chgtrx = $table_chgtrx->leftjoin('hisdb.drugindicator as dru', function ($join) use ($request){
                            $join = $join->where('dru.compcode', '=', session('compcode'));
                            $join = $join->on('dru.drugindcode', '=', 'trx.drugindicator');
                        });
        
        $table_chgtrx = $table_chgtrx->leftjoin('hisdb.doctor as doc', function ($join) use ($request){
                            $join = $join->where('doc.compcode', '=', session('compcode'));
                            $join = $join->on('doc.doctorcode', '=', 'trx.doctorcode');
                        });
        
        //////////paginate//////////
        $paginate = $table_chgtrx->paginate($request->rows);
        
        $responce = new stdClass();
        $responce->page = $paginate->currentPage();
        $responce->total = $paginate->lastPage();
        $responce->records = $paginate->total();
        $responce->rows = $paginate->items();
        $responce->sql = $table_chgtrx->toSql();
        $responce->sql_bind = $table_chgtrx->getBindings();
        $responce->sql_query = $this->getQueries($table_chgtrx);
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
                    ->leftJoin('hisdb.pathealth as p', function ($join) use ($request){
                        $join = $join->on('p.mrn', '=', 'e.mrn');
                        $join = $join->on('p.episno', '=', 'e.episno');
                        $join = $join->on('p.compcode', '=', 'e.compcode');
                    })->leftJoin('hisdb.doctor as d', function ($join) use ($request){
                        $join = $join->on('d.doctorcode', '=', 'e.admdoctor');
                        $join = $join->on('d.compcode', '=', 'e.compcode');
                    })
                    ->where('e.compcode','=',session('compcode'))
                    ->where('e.mrn','=',$request->mrn)
                    ->where('e.episno','=',$request->episno)
                    ->orderBy('p.idno','desc');
        
        if($episode_obj->exists()){
            $episode_obj = $episode_obj->get();
            
            $data = [];
            
            foreach($episode_obj as $key => $value){
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
                        ->join('hisdb.pathealth as p', function ($join) use ($request){
                            $join = $join->on('p.mrn', '=', 'e.mrn');
                            $join = $join->on('p.episno', '=', 'e.episno');
                            $join = $join->on('p.compcode', '=', 'e.compcode');
                            $join = $join->where('p.recorddate', '!=', Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d'));
                        })->leftJoin('hisdb.doctor as d', function ($join) use ($request){
                            $join = $join->on('d.doctorcode', '=', 'e.admdoctor');
                            $join = $join->on('d.compcode', '=', 'e.compcode');
                        })
                        ->where('e.compcode','=',session('compcode'))
                        ->where('e.mrn','=',$request->mrn)
                        ->orderBy('p.recorddate','desc');
        
        // $patexam_obj = DB::table('hisdb.pathealth')
        //                 ->select('mrn','episno','recordtime','adddate','adduser')
        //                 ->where('compcode','=',session('compcode'))
        //                 ->where('mrn','=',$request->mrn)
        //                 ->orderBy('adddate','desc');
        
        if($episode_obj->exists()){
            $episode_obj = $episode_obj->get();
            
            $data = [];
            
            foreach($episode_obj as $key => $value){
                if(!empty($value->recorddate)){
                    $date['date'] =  Carbon::createFromFormat('Y-m-d', $value->recorddate)->format('d-m-Y').' '.$value->recordtime;
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
                            ->select('idno','compcode','mrn','episno','height','weight','temperature','pulse','bp_sys1','bp_dias2','respiration','gxt','pain_score','clinicnote','adduser','adddate','upduser','upddate','complain','recorddate','recordtime','visionl','visionr','colorblind','recstatus','plan_','allergyh','fmh','pmh','socialh','drugh','vas','aggr','easing','pain','behaviour','irritability','severity','lastuser','lastupdate','followupdate','followuptime','anr_rhesus','anr_rubella','anr_vdrl','anr_hiv','anr_hepaB_Ag','anr_hepaB_AB','anr_bloodTrans','anr_drugAllergies','doctorcode','newic','arrival_date','nursing_complete','doctor_complete','computerid','genappear','speech','moodaffect','perception','thinking','cognitivefunc','aetiology','investigate','treatment','prognosis')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno)
                            ->where('recorddate','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate)->format('Y-m-d'))
                            ->where('recordtime','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate)->format('H:i:s'));
                            // ->orderBy('recordtime','desc');
            
            $patexam_obj = DB::table('hisdb.patexam')
                            ->select('idno','compcode','mrn','episno','recorddate','recordtime','examination','adduser','lastuser','lastupdate','recstatus')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno)
                            ->where('recorddate','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate)->format('Y-m-d'))
                            ->where('recordtime','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate)->format('H:i:s'));
        }
        
        $pathistory_obj = DB::table('hisdb.pathistory')
                        ->select('idno','compcode','mrn','recorddate','recordtime','adduser','lastuser','lastupdate','recstatus','pathname','filename','drugh','pmh','fmh','allergyh','socialh','pgh_myomectomy','pgh_laparoscopy','pgh_endometriosis','lastpapsmear','pgh_others','pmh_renaldisease','pmh_hypertension','pmh_diabetes','pmh_heartdisease','pmh_others','psh_appendicectomy','psh_hypertension','psh_laparotomy','psh_thyroidsurgery','psh_others','fh_hypertension','fh_diabetes','fh_epilepsy','fh_multipregnancy','fh_congenital','anr_bloodgroup','anr_attInject_1st','anr_attInject_2nd','anr_attInject_boost','psychiatryh','personalh')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$request->mrn);
        
        $episdiag_obj = DB::table('hisdb.episdiag')
                        ->select('compcode','mrn','episno','seq','icdcode','diagstatus','lastuser','lastupdate','icdcodeno','adduser','type','suppcode','ripdate','f1','f2','f3','f4','f5')
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
                
                // $adddate =  Carbon::createFromFormat('Y-m-d', $pathealth_obj->adddate)->format('d-m-Y');
                // $responce->adddate = $adddate;
            }
            
            if($patexam_obj->exists()){
                $patexam_obj = $patexam_obj->first();
                $responce->patexam = $patexam_obj;
            }
        }
        
        if($pathistory_obj->exists()){
            $pathistory_obj = $pathistory_obj->first();
            $responce->pathistory = $pathistory_obj;
        }
        
        if($episdiag_obj->exists()){
            $episdiag_obj = $episdiag_obj->first();
            $responce->episdiag = $episdiag_obj;
        }
        
        if($pathealthadd_obj->exists()){
            $pathealthadd_obj = $pathealthadd_obj->first();
            $responce->pathealthadd = $pathealthadd_obj;
        }
        
        // $responce->transaction = json_decode($this->get_transaction_table($request));
        
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
                                'genappear as genappear_ref',
                                'speech as speech_ref',
                                'moodaffect as moodaffect_ref',
                                'perception as perception_ref',
                                'thinking as thinking_ref',
                                'cognitivefunc as cognitivefunc_ref',
                                'followuptime as followuptime_ref',
                                'followupdate as followupdate_ref',
                                'aetiology as aetiology_ref',
                                'investigate as investigate_ref',
                                'treatment as treatment_ref',
                                'prognosis as prognosis_ref',
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
                            ->where('recorddate','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate)->format('Y-m-d'))
                            ->where('recordtime','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate)->format('H:i:s'));
                            // ->orderBy('recordtime','desc');
            
            $pathistory_obj = DB::table('hisdb.pathistory')
                            ->select(
                                'psychiatryh as psychiatryh_ref',
                                'pmh as pmh_ref',
                                'fmh as fmh_ref',
                                'personalh as personalh_ref',
                                'drugh as drugh_ref',
                                'allergyh as allergyh_ref',
                                'socialh as socialh_ref',
                            )
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            // ->where('recorddate','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate));
                            ->where('recorddate','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate)->format('Y-m-d'))
                            ->where('recordtime','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate)->format('H:i:s'));
            
            $patexam_obj = DB::table('hisdb.patexam')
                            ->select('examination as examination_ref')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno)
                            ->where('recorddate','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate)->format('Y-m-d'))
                            ->where('recordtime','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate)->format('H:i:s'));
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
    
    public function add_otbook(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('hisdb.pat_otbook')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn,
                    'episno' => $request->episno,
                    'op_date' => $request->op_date,
                    'adm_type' => $request->adm_type,
                    'anaesthetist' => $request->anaesthetist,
                    'ot_remarks' => $request->ot_remarks,
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
    
    public function edit_otbook(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $pat_otbook = DB::table('hisdb.pat_otbook')
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno)
                        ->where('compcode','=',session('compcode'));
            
            if($pat_otbook->exists()){
                $pat_otbook
                    ->update([
                        'op_date' => $request->op_date,
                        'adm_type' => $request->adm_type,
                        'anaesthetist' => $request->anaesthetist,
                        'ot_remarks' => $request->ot_remarks,
                        'upduser'  => session('username'),
                        'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }else{
                DB::table('hisdb.pat_otbook')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'op_date' => $request->op_date,
                        'adm_type' => $request->adm_type,
                        'anaesthetist' => $request->anaesthetist,
                        'ot_remarks' => $request->ot_remarks,
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
    
    public function get_table_otbook(Request $request){
        
        $pat_otbook_obj = DB::table('hisdb.pat_otbook')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno);
        
        $responce = new stdClass();
        
        if($pat_otbook_obj->exists()){
            $pat_otbook_obj = $pat_otbook_obj->first();
            $responce->pat_otbook = $pat_otbook_obj;
        }
        
        return json_encode($responce);
        
    }
    
    public function add_radClinic(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $episode = DB::table('hisdb.episode')
                        ->select('newcaseP','newcaseNP','followupP','followupNP')
                        ->where('compcode',session('compcode'))
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno);
            
            if($episode->exists()){
                $episode_obj = $episode->first();
                
                if(!empty($episode_obj->newcaseP) || !empty($episode_obj->newcaseNP)){
                    if($request->rad_pregnant == 1){
                        $episode
                            ->update([
                                'newcaseP' => 1,
                                'newcaseNP' => null,
                                'lastuser'  => session('username'),
                                'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                            ]);
                    }else if($request->rad_pregnant == 0){
                        $episode
                            ->update([
                                'newcaseP' => null,
                                'newcaseNP' => 1,
                                'lastuser'  => session('username'),
                                'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                            ]);
                    }
                }else if(!empty($episode_obj->followupP) || !empty($episode_obj->followupNP)){
                    if($request->rad_pregnant == 1){
                        $episode
                            ->update([
                                'followupP' => 1,
                                'followupNP' => null,
                                'lastuser'  => session('username'),
                                'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                            ]);
                    }else if($request->rad_pregnant == 0){
                        $episode
                            ->update([
                                'followupP' => null,
                                'followupNP' => 1,
                                'lastuser'  => session('username'),
                                'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                            ]);
                    }
                }
            }
            
            $pathealth = DB::table('hisdb.pathealth')
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno)
                        ->where('recorddate','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate_doctorNote)->format('Y-m-d'))
                        ->where('recordtime','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate_doctorNote)->format('H:i:s'))
                        ->where('compcode','=',session('compcode'));
            
            if($pathealth->exists()){
                $pathealth
                    ->update([
                        'weight' => $request->rad_weight,
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }
            
            $pathistory = DB::table('hisdb.pathistory')
                        ->where('mrn','=',$request->mrn)
                        // ->where('recorddate','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate_doctorNote))
                        ->where('compcode','=',session('compcode'));
            
            if($pathistory->exists()){
                $pathistory
                    ->update([
                        'allergyh' => $request->rad_allergy,
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }
            
            DB::table('hisdb.pat_radiology')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn,
                    'episno' => $request->episno,
                    'pt_condition' => $request->pt_condition,
                    'rad_exam' => $request->rad_exam,
                    'others_remark' => $request->others_remark,
                    'consult_remark' => $request->consult_remark,
                    'consultant'  => session('username'),
                    'rad_remark' => $request->rad_remark,
                    'radiologist'  => session('username'),
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
    
    public function edit_radClinic(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $episode = DB::table('hisdb.episode')
                        ->select('newcaseP','newcaseNP','followupP','followupNP')
                        ->where('compcode',session('compcode'))
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno);
            
            if($episode->exists()){
                $episode_obj = $episode->first();
                
                if(!empty($episode_obj->newcaseP) || !empty($episode_obj->newcaseNP)){
                    if($request->rad_pregnant == 1){
                        $episode
                            ->update([
                                'newcaseP' => 1,
                                'newcaseNP' => null,
                                'lastuser'  => session('username'),
                                'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                            ]);
                    }else if($request->rad_pregnant == 0){
                        $episode
                            ->update([
                                'newcaseP' => null,
                                'newcaseNP' => 1,
                                'lastuser'  => session('username'),
                                'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                            ]);
                    }
                }else if(!empty($episode_obj->followupP) || !empty($episode_obj->followupNP)){
                    if($request->rad_pregnant == 1){
                        $episode
                            ->update([
                                'followupP' => 1,
                                'followupNP' => null,
                                'lastuser'  => session('username'),
                                'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                            ]);
                    }else if($request->rad_pregnant == 0){
                        $episode
                            ->update([
                                'followupP' => null,
                                'followupNP' => 1,
                                'lastuser'  => session('username'),
                                'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                            ]);
                    }
                }
            }
            
            $pathealth = DB::table('hisdb.pathealth')
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno)
                        ->where('recorddate','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate_doctorNote)->format('Y-m-d'))
                        ->where('recordtime','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate_doctorNote)->format('H:i:s'))
                        ->where('compcode','=',session('compcode'));
            
            if($pathealth->exists()){
                $pathealth
                    ->update([
                        'weight' => $request->rad_weight,
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }
            
            $pathistory = DB::table('hisdb.pathistory')
                        ->where('mrn','=',$request->mrn)
                        // ->where('recorddate','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate_doctorNote))
                        ->where('compcode','=',session('compcode'));
            
            if($pathistory->exists()){
                $pathistory
                    ->update([
                        'allergyh' => $request->rad_allergy,
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }
            
            $pat_radiology = DB::table('hisdb.pat_radiology')
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno)
                            ->where('compcode','=',session('compcode'));
            
            if($pat_radiology->exists()){
                $pat_radiology
                    ->update([
                        'pt_condition' => $request->pt_condition,
                        'rad_exam' => $request->rad_exam,
                        'others_remark' => $request->others_remark,
                        'consult_remark' => $request->consult_remark,
                        'consultant'  => session('username'),
                        'rad_remark' => $request->rad_remark,
                        'radiologist'  => session('username'),
                        'upduser'  => session('username'),
                        'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }else{
                DB::table('hisdb.pat_radiology')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'pt_condition' => $request->pt_condition,
                        'rad_exam' => $request->rad_exam,
                        'others_remark' => $request->others_remark,
                        'consult_remark' => $request->consult_remark,
                        'consultant'  => session('username'),
                        'rad_remark' => $request->rad_remark,
                        'radiologist'  => session('username'),
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
    
    public function get_table_radClinic(Request $request){
        
        $pat_radiology_obj = DB::table('hisdb.pat_radiology')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno);
        
        $episode = DB::table('hisdb.episode')
                    ->where('compcode',session('compcode'))
                    ->where('mrn','=',$request->mrn)
                    ->where('episno','=',$request->episno);
        
        if(!empty($request->recorddate_doctorNote) && $request->recorddate_doctorNote != '-'){
            $pathealth_obj = DB::table('hisdb.pathealth')
                            ->select('weight')
                            ->where('compcode','=',session('compcode'))
                            ->where('recorddate','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate_doctorNote)->format('Y-m-d'))
                            ->where('recordtime','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate_doctorNote)->format('H:i:s'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno);
        }
        
        $pathistory_obj = DB::table('hisdb.pathistory')
                        ->select('allergyh')
                        ->where('compcode','=',session('compcode'))
                        // ->where('recorddate','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate_doctorNote))
                        ->where('mrn','=',$request->mrn);
        
        $responce = new stdClass();
        
        if($pat_radiology_obj->exists()){
            $pat_radiology_obj = $pat_radiology_obj->first();
            $responce->pat_radiology = $pat_radiology_obj;
        }
        
        if($episode->exists()){
            $episode = $episode->first();
            if($episode->newcaseP == 1 || $episode->followupP == 1){
                // $pregnant = 1;
                $responce->pregnant = 1;
            }else{
                // $pregnant = 0;
                $responce->pregnant = 0;
            }
        }
        
        if(!empty($request->recorddate_doctorNote) && $request->recorddate_doctorNote != '-'){
            if($pathealth_obj->exists()){
                $pathealth_obj = $pathealth_obj->first();
                
                $rad_weight = $pathealth_obj->weight;
                $responce->rad_weight = $rad_weight;
            }
        }
        
        if($pathistory_obj->exists()){
            $pathistory_obj = $pathistory_obj->first();
            
            $rad_allergy = $pathistory_obj->allergyh;
            $responce->rad_allergy = $rad_allergy;
        }
        
        return json_encode($responce);
        
    }
    
    public function add_mri(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $pathealth = DB::table('hisdb.pathealth')
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno)
                        ->where('recorddate','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate_doctorNote)->format('Y-m-d'))
                        ->where('recordtime','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate_doctorNote)->format('H:i:s'))
                        ->where('compcode','=',session('compcode'));
            
            if($pathealth->exists()){
                $pathealth
                    ->update([
                        'weight' => $request->mri_weight,
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }
            
            DB::table('hisdb.pat_mri')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn,
                    'episno' => $request->episno,
                    'mri_date' => $request->mri_date,
                    'pacemaker' => $request->pacemaker,
                    'pros_valve' => $request->pros_valve,
                    'pros_remark' => $request->pros_remark,
                    'intraocular' => $request->intraocular,
                    'cochlear' => $request->cochlear,
                    'neurotransm' => $request->neurotransm,
                    'bonegrowth' => $request->bonegrowth,
                    'druginfuse' => $request->druginfuse,
                    'surg_clips' => $request->surg_clips,
                    'limb_prosth' => $request->limb_prosth,
                    'shrapnel' => $request->shrapnel,
                    'oper_3mth' => $request->oper_3mth,
                    'oper3mth_remark' => $request->oper3mth_remark,
                    'prev_mri' => $request->prev_mri,
                    'claustrophobia' => $request->claustrophobia,
                    'dental_imp' => $request->dental_imp,
                    'frmgnetic_imp' => $request->frmgnetic_imp,
                    'pregnancy' => $request->pregnancy,
                    'allergy_drug' => $request->allergy_drug,
                    'bloodurea' => $request->bloodurea,
                    'serum_creat' => $request->serum_creat,
                    'doc_name' => session('username'),
                    'pat_name' => $request->pat_name,
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
    
    public function edit_mri(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $pathealth = DB::table('hisdb.pathealth')
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno)
                        ->where('recorddate','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate_doctorNote)->format('Y-m-d'))
                        ->where('recordtime','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate_doctorNote)->format('H:i:s'))
                        ->where('compcode','=',session('compcode'));
            
            if($pathealth->exists()){
                $pathealth
                    ->update([
                        'weight' => $request->mri_weight,
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }
            
            $pat_mri = DB::table('hisdb.pat_mri')
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno)
                        ->where('compcode','=',session('compcode'));
            
            if($pat_mri->exists()){
                $pat_mri
                    ->update([
                        'mri_date' => $request->mri_date,
                        'pacemaker' => $request->pacemaker,
                        'pros_valve' => $request->pros_valve,
                        'pros_remark' => $request->pros_remark,
                        'intraocular' => $request->intraocular,
                        'cochlear' => $request->cochlear,
                        'neurotransm' => $request->neurotransm,
                        'bonegrowth' => $request->bonegrowth,
                        'druginfuse' => $request->druginfuse,
                        'surg_clips' => $request->surg_clips,
                        'limb_prosth' => $request->limb_prosth,
                        'shrapnel' => $request->shrapnel,
                        'oper_3mth' => $request->oper_3mth,
                        'oper3mth_remark' => $request->oper3mth_remark,
                        'prev_mri' => $request->prev_mri,
                        'claustrophobia' => $request->claustrophobia,
                        'dental_imp' => $request->dental_imp,
                        'frmgnetic_imp' => $request->frmgnetic_imp,
                        'pregnancy' => $request->pregnancy,
                        'allergy_drug' => $request->allergy_drug,
                        'bloodurea' => $request->bloodurea,
                        'serum_creat' => $request->serum_creat,
                        'doc_name' => session('username'),
                        'pat_name' => $request->pat_name,
                        'upduser'  => session('username'),
                        'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }else{
                DB::table('hisdb.pat_mri')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'mri_date' => $request->mri_date,
                        'pacemaker' => $request->pacemaker,
                        'pros_valve' => $request->pros_valve,
                        'pros_remark' => $request->pros_remark,
                        'intraocular' => $request->intraocular,
                        'cochlear' => $request->cochlear,
                        'neurotransm' => $request->neurotransm,
                        'bonegrowth' => $request->bonegrowth,
                        'druginfuse' => $request->druginfuse,
                        'surg_clips' => $request->surg_clips,
                        'limb_prosth' => $request->limb_prosth,
                        'shrapnel' => $request->shrapnel,
                        'oper_3mth' => $request->oper_3mth,
                        'oper3mth_remark' => $request->oper3mth_remark,
                        'prev_mri' => $request->prev_mri,
                        'claustrophobia' => $request->claustrophobia,
                        'dental_imp' => $request->dental_imp,
                        'frmgnetic_imp' => $request->frmgnetic_imp,
                        'pregnancy' => $request->pregnancy,
                        'allergy_drug' => $request->allergy_drug,
                        'bloodurea' => $request->bloodurea,
                        'serum_creat' => $request->serum_creat,
                        'doc_name' => session('username'),
                        'pat_name' => $request->pat_name,
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
    
    public function get_table_mri(Request $request){
        
        $pat_mri_obj = DB::table('hisdb.pat_mri')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno);
        
        if(!empty($request->recorddate_doctorNote) && $request->recorddate_doctorNote != '-'){
            $pathealth_obj = DB::table('hisdb.pathealth')
                            ->select('weight')
                            ->where('compcode','=',session('compcode'))
                            ->where('recorddate','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate_doctorNote)->format('Y-m-d'))
                            ->where('recordtime','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate_doctorNote)->format('H:i:s'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno);
        }
        
        $responce = new stdClass();
        
        if($pat_mri_obj->exists()){
            $pat_mri_obj = $pat_mri_obj->first();
            $responce->pat_mri = $pat_mri_obj;
        }
        
        if(!empty($request->recorddate_doctorNote) && $request->recorddate_doctorNote != '-'){
            if($pathealth_obj->exists()){
                $pathealth_obj = $pathealth_obj->first();
                
                $mri_weight = $pathealth_obj->weight;
                $responce->mri_weight = $mri_weight;
            }
        }
        
        return json_encode($responce);
        
    }
    
    public function add_physio(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('hisdb.pat_physio')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn,
                    'episno' => $request->episno,
                    'clinic_diag' => $request->clinic_diag,
                    'findings' => $request->findings,
                    'phy_treatment' => $request->phy_treatment,
                    'req_doc' => session('username'),
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
    
    public function edit_physio(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $pat_physio = DB::table('hisdb.pat_physio')
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno)
                        ->where('compcode','=',session('compcode'));
            
            if($pat_physio->exists()){
                $pat_physio
                    ->update([
                        'clinic_diag' => $request->clinic_diag,
                        'findings' => $request->findings,
                        'phy_treatment' => $request->phy_treatment,
                        // 'req_doc' => session('username'),
                        'upduser'  => session('username'),
                        'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }else{
                DB::table('hisdb.pat_physio')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'clinic_diag' => $request->clinic_diag,
                        'findings' => $request->findings,
                        'phy_treatment' => $request->phy_treatment,
                        'req_doc' => session('username'),
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
    
    public function get_table_physio(Request $request){
        
        $pat_physio_obj = DB::table('hisdb.pat_physio')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno);
        
        $responce = new stdClass();
        
        if($pat_physio_obj->exists()){
            $pat_physio_obj = $pat_physio_obj->first();
            $responce->pat_physio = $pat_physio_obj;
        }
        
        return json_encode($responce);
        
    }
    
    public function add_dressing(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('hisdb.pat_dressing')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn,
                    'episno' => $request->episno,
                    'od_dressing' => $request->od_dressing,
                    'bd_dressing' => $request->bd_dressing,
                    'eod_dressing' => $request->eod_dressing,
                    'others_dressing' => $request->others_dressing,
                    'others_name' => $request->others_name,
                    'solution' => $request->solution,
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
    
    public function edit_dressing(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $pat_dressing = DB::table('hisdb.pat_dressing')
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno)
                            ->where('compcode','=',session('compcode'));
            
            if($pat_dressing->exists()){
                $pat_dressing
                    ->update([
                        'od_dressing' => $request->od_dressing,
                        'bd_dressing' => $request->bd_dressing,
                        'eod_dressing' => $request->eod_dressing,
                        'others_dressing' => $request->others_dressing,
                        'others_name' => $request->others_name,
                        'solution' => $request->solution,
                        'upduser'  => session('username'),
                        'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }else{
                DB::table('hisdb.pat_dressing')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'od_dressing' => $request->od_dressing,
                        'bd_dressing' => $request->bd_dressing,
                        'eod_dressing' => $request->eod_dressing,
                        'others_dressing' => $request->others_dressing,
                        'others_name' => $request->others_name,
                        'solution' => $request->solution,
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
    
    public function get_table_dressing(Request $request){
        
        $pat_dressing_obj = DB::table('hisdb.pat_dressing')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno);
        
        $responce = new stdClass();
        
        if($pat_dressing_obj->exists()){
            $pat_dressing_obj = $pat_dressing_obj->first();
            $responce->pat_dressing = $pat_dressing_obj;
        }
        
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
            
            foreach($count as $key => $value){
                $occur_ar = $this->index_of_occurance($key,$searchCol_array);
                
                $table = $table->where(function ($table) use ($request,$searchCol_array,$occur_ar){
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
        //         ->get();
        
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
            
            // $doctorcode_obj = DB::table('hisdb.doctor')
            //                 ->select('doctorcode')
            //                 ->where('compcode','=',session('compcode'))
            //                 ->where('loginid','=',session('username'));
            
            // $doctorcode = null;
            // if($doctorcode_obj->exists()){
            //     $doctorcode = $doctorcode_obj->first()->doctorcode;
            // }
            
            $users = DB::table('sysdb.users')
                    ->where('compcode','=',session('compcode'))
                    ->where('username','=',session('username'))
                    ->first();
            
            DB::table('hisdb.pathealthadd')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn,
                    'episno' => $request->episno,
                    'additionalnote' => $request->additionalnote,
                    'doctorcode'  => $users->doctorcode,
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
                        ->leftJoin('hisdb.pat_mast as pm', function ($join) use ($request){
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

    public function dressing_chart(Request $request){

        $mrn = $request->mrn_doctorNote;
        $episno = $request->episno_doctorNote;

        $dressing = DB::table('hisdb.pat_dressing as d')
                    ->select('d.mrn','d.episno','d.od_dressing','d.bd_dressing','d.eod_dressing','d.others_dressing','d.others_name','d.solution','d.adduser','pm.Name','pm.Newic')
                    ->leftjoin('hisdb.pat_mast as pm', function($join) {
                        $join = $join->on('pm.MRN', '=', 'd.mrn');
                        $join = $join->on('pm.Episno', '=', 'd.episno');
                        $join = $join->where('pm.compcode', '=', session('compcode'));
                    })
                    ->where('d.compcode','=',session('compcode'))
                    ->where('d.mrn','=',$request->mrn)
                    ->where('d.episno','=',$request->episno)
                    ->first(); //dd($dressing);
        
        $company = DB::table('sysdb.company')
            ->where('compcode','=',session('compcode'))
            ->first();

        return view('hisdb.doctornote.dressingChart_pdfmake',compact('dressing'));
        
    }
    
    public function mri_chart(Request $request){

        $mrn = $request->mrn_doctorNote;
        $episno = $request->episno_doctorNote;

        $mri = DB::table('hisdb.pat_mri as ptm')
                    ->select('ptm.mrn','ptm.episno','ptm.mri_date','ptm.pacemaker','ptm.pros_valve','ptm.pros_remark','ptm.intraocular','ptm.cochlear','ptm.neurotransm','ptm.bonegrowth','ptm.druginfuse','ptm.surg_clips','ptm.limb_prosth','ptm.shrapnel','ptm.oper_3mth','ptm.oper3mth_remark','ptm.prev_mri','ptm.claustrophobia','ptm.dental_imp','ptm.frmgnetic_imp','ptm.pregnancy','ptm.allergy_drug','ptm.bloodurea','ptm.serum_creat','ptm.doc_name','ptm.pat_name','pm.Name','pm.Newic','pm.telhp','ph.weight')
                    ->leftjoin('hisdb.pat_mast as pm', function($join) {
                        $join = $join->on('pm.MRN', '=', 'ptm.mrn');
                        $join = $join->on('pm.Episno', '=', 'ptm.episno');
                        $join = $join->where('pm.compcode', '=', session('compcode'));
                    })
                    ->leftjoin('hisdb.pathealth as ph', function($join) {
                        $join = $join->on('ph.mrn', '=', 'ptm.mrn');
                        $join = $join->on('ph.episno', '=', 'ptm.episno');
                        $join = $join->where('ph.compcode', '=', session('compcode'));
                    })
                    ->where('ptm.compcode','=',session('compcode'))
                    ->where('ptm.mrn','=',$request->mrn)
                    ->where('ptm.episno','=',$request->episno)
                    ->first();
        
        $company = DB::table('sysdb.company')
            ->where('compcode','=',session('compcode'))
            ->first();

        return view('hisdb.doctornote.mriChart_pdfmake',compact('mri'));
        
    }
    
}