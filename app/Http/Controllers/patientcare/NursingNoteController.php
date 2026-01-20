<?php

namespace App\Http\Controllers\patientcare;

use Illuminate\Http\Request;
use stdClass;
use App\User;
use DB;
use Carbon\Carbon;
use Auth;
use Session;
use App\Http\Controllers\defaultController;
use PDF;

class NursingNoteController extends defaultController
{
    
    var $table;
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function show(Request $request)
    {
        return view('patientcare.nursingnote');
    }
    
    public function table(Request $request)
    {
        switch($request->action){
            case 'get_table_datetime_ED': // Progress Note
                return $this->get_table_datetime_ED($request);

            case 'get_prescription': // Drug Administration
                return $this->get_prescription($request);

            case 'get_table_datetimepivc_ED': // PIVC
                return $this->get_table_datetimepivc_ED($request);

            case 'get_table_datetimethrombo_ED': // Thrombo
                return $this->get_table_datetimethrombo_ED($request);

            default:
                return 'error happen..';
        }
    }
    
    public function form(Request $request)
    {
        DB::enableQueryLog();
        
        switch($request->action){
            case 'save_table_progress_ED':
                switch($request->oper){
                    case 'add':
                        return $this->add_progress_ED($request);
                    case 'edit':
                        return $this->edit_progress_ED($request);
                    default:
                        return 'error happen..';
                }

            case 'patMedic_save':
                return $this->add_patMedic($request);

            case 'save_table_pivc_ED':
                switch($request->oper){
                    case 'add':
                        return $this->add_pivc_ED($request);
                    case 'edit':
                        return $this->edit_pivc_ED($request);
                    default:
                        return 'error happen..';
                }

            case 'save_table_thrombo_ED':
                switch($request->oper){
                    case 'add':
                        return $this->add_thrombo_ED($request);
                    case 'edit':
                        return $this->edit_thrombo_ED($request);
                    default:
                        return 'error happen..';
                }

            case 'get_table_progress_ED':
                return $this->get_table_progress_ED($request);

            case 'get_table_drug':
                return $this->get_table_drug($request);

            case 'get_table_pivc_ED':
                return $this->get_table_pivc_ED($request);

            case 'get_table_thrombo_ED':
                return $this->get_table_thrombo_ED($request);

            case 'thrombo_ED_save':
                return $this->add_thrombojqgrid_ED($request);
            
            case 'thrombo_ED_edit':
                return $this->edit_thrombojqgrid_ED($request);
            
            case 'thrombo_ED_del':
                return $this->del_thrombojqgrid_ED($request);

            default:
                return 'error happen..';
        }
    }
    
    public function get_table_datetime_ED(Request $request){
        
        $responce = new stdClass();
        
        $nurshandover_obj = DB::table('nursing.nurshandover')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno);

        if($nurshandover_obj->exists()){
            $nurshandover_obj = $nurshandover_obj->get();

            $data = [];
            foreach ($nurshandover_obj as $key => $value) {
                if(!empty($value->datetaken)){
                    $date['datetaken'] =  Carbon::createFromFormat('Y-m-d', $value->datetaken)->format('d-m-Y');
                }else{
                    $date['datetaken'] =  '-';
                }
                $date['idno'] = $value->idno;
                $date['mrn'] = $value->mrn;
                $date['episno'] = $value->episno;
                if(!empty($value->timetaken)){
                    $date['timetaken'] =  Carbon::createFromFormat('H:i:s', $value->timetaken)->format('h:i A');
                }else{
                    $date['timetaken'] =  '-';
                }
                $date['adduser'] = $value->adduser;
                $date['location'] = $value->location;
                
                array_push($data,$date);
            }
            
            $responce->data = $data;
        }else{
            $responce->data = [];
        }
        
        return json_encode($responce);
        
    }

    public function get_prescription(Request $request){
        
        $responce = new stdClass();
        
        $chargetrx_obj = DB::table('hisdb.chargetrx as trx')
                        ->select('trx.auditno', 'trx.mrn', 'trx.episno', 'trx.chgcode', 'trx.quantity', 'trx.uom', 'trx.doscode', 'trx.frequency', 'trx.ftxtdosage', 'trx.addinstruction', 'trx.drugindicator', 'cm.description', 'cm.uom', 'dos.dosedesc as doscode_desc', 'fre.freqdesc as frequency_desc', 'ins.description as addinstruction_desc', 'dru.description as drugindicator_desc')
                        ->leftjoin('hisdb.chgmast as cm', function($join) use ($request){
                            $join = $join->on('cm.chgcode', '=', 'trx.chgcode')
                                        ->on('cm.uom','=','trx.uom')
                                        ->where('cm.compcode','=',session('compcode'));
                        })
                        ->leftjoin('hisdb.dose as dos', function($join) use ($request){
                            $join = $join->on('dos.dosecode', '=', 'trx.doscode')
                                        ->where('dos.compcode','=',session('compcode'));
                        })
                        ->leftjoin('hisdb.freq as fre', function($join) use ($request){
                            $join = $join->on('fre.freqcode', '=', 'trx.frequency')
                                        ->where('fre.compcode','=',session('compcode'));
                        })
                        ->leftjoin('hisdb.instruction as ins', function($join) use ($request){
                            $join = $join->on('ins.inscode', '=', 'trx.addinstruction')
                                        ->where('ins.compcode','=',session('compcode'));
                        })
                        ->leftjoin('hisdb.drugindicator as dru', function($join) use ($request){
                            $join = $join->on('dru.drugindcode', '=', 'trx.drugindicator')
                                        ->where('dru.compcode','=',session('compcode'));
                        })
                        ->where('trx.mrn' ,'=', $request->mrn)
                        ->where('trx.episno' ,'=', $request->episno)
                        ->where('trx.compcode','=',session('compcode'))
                        ->where('trx.chggroup',$request->chggroup)
                        ->where('trx.recstatus','<>','DELETE')
                        ->orderBy('trx.adddate', 'desc');
        
        if($chargetrx_obj->exists()){
            $chargetrx_obj = $chargetrx_obj->get();
            
            $data = [];
            
            foreach ($chargetrx_obj as $key => $value) {
                $date['auditno'] = $value->auditno;
                $date['mrn'] = $value->mrn;
                $date['episno'] = $value->episno;
                $date['chgcode'] = $value->chgcode;
                $date['description'] = $value->description;
                $date['quantity'] = $value->quantity;
                $date['doscode'] = $value->doscode;
                $date['doscode_desc'] = $value->doscode_desc;
                $date['frequency'] = $value->frequency;
                $date['frequency_desc'] = $value->frequency_desc;
                $date['ftxtdosage'] = $value->ftxtdosage;
                $date['addinstruction'] = $value->addinstruction;
                $date['addinstruction_desc'] = $value->addinstruction_desc;
                $date['drugindicator'] = $value->drugindicator;
                $date['drugindicator_desc'] = $value->drugindicator_desc;
                
                array_push($data,$date);
            }
            
            $responce->data = $data;
        }else{
            $responce->data = [];
        }
        
        return json_encode($responce);
        
    }

    public function get_table_datetimepivc_ED(Request $request){
        
        $responce = new stdClass();
        
        $pivc_ED_obj = DB::table('nursing.pivc')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno);
     
        if($pivc_ED_obj->exists()){
            $pivc_ED_obj = $pivc_ED_obj->get();
            
            $data = [];
            
            foreach($pivc_ED_obj as $key => $value){
                if(!empty($value->practiceDate)){
                    $date['practiceDate'] =  Carbon::createFromFormat('Y-m-d', $value->practiceDate)->format('d-m-Y');
                }else{
                    $date['practiceDate'] =  '-';
                }
                $date['idno'] = $value->idno;
                $date['mrn'] = $value->mrn;
                $date['episno'] = $value->episno;
                $date['adduser'] = $value->adduser;

                array_push($data,$date);
            }
            
            $responce->data = $data;
        }else{
            $responce->data = [];
        }
        
        return json_encode($responce);
        
    }

    public function get_table_datetimethrombo_ED(Request $request){
        
        $responce = new stdClass();
        
        $thrombo_ED_obj = DB::table('nursing.thrombophlebitis')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno);
     
        if($thrombo_ED_obj->exists()){
            $thrombo_ED_obj = $thrombo_ED_obj->get();
            
            $data = [];
            
            foreach($thrombo_ED_obj as $key => $value){
                if(!empty($value->dateInsert)){
                    $date['dateInsert'] =  Carbon::createFromFormat('Y-m-d', $value->dateInsert)->format('d-m-Y');
                }else{
                    $date['dateInsert'] =  '-';
                }
                $date['idno'] = $value->idno;
                $date['mrn'] = $value->mrn;
                $date['episno'] = $value->episno;
                if(!empty($value->timeInsert)){
                    $date['timeInsert'] =  Carbon::createFromFormat('H:i:s', $value->timeInsert)->format('h:i A');
                }else{
                    $date['timeInsert'] =  '-';
                }
                $date['adduser'] = $value->adduser;

                array_push($data,$date);
            }
            
            $responce->data = $data;
        }else{
            $responce->data = [];
        }
        
        return json_encode($responce);
        
    }

    public function add_progress_ED(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.nurshandover')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn_nursNote,
                    'episno' => $request->episno_nursNote,
                    'datetaken' => $request->datetaken,
                    'timetaken' => $request->timetaken,
                    'bpsys_stand' => $request->bpsys_stand,
                    'bpdias_stand' => $request->bpdias_stand,
                    'bpsys_lieDown' => $request->bpsys_lieDown,
                    'bpdias_lieDown' => $request->bpdias_lieDown,
                    'spo2' => $request->spo2,
                    'hr' => $request->hr,
                    'gxt' => $request->gxt,
                    'temp_' => $request->temp_,
                    'weight' => $request->weight,
                    'respiration' => $request->respiration,
                    'height' => $request->height,
                    'roomair' => $request->roomair,
                    'oxygen' => $request->oxygen,
                    'airwayfreetext' => $request->airwayfreetext,
                    'breathnormal' => $request->breathnormal,
                    'breathdifficult' => $request->breathdifficult,
                    'circarrythmias' => $request->circarrythmias,
                    'circlbp' => $request->circlbp,
                    'circhbp' => $request->circhbp,
                    'circirregular' => $request->circirregular,
                    'frhigh' => $request->frhigh,
                    'frlow' => $request->frlow,
                    'frfreetext' => $request->frfreetext,
                    'drainnone' => $request->drainnone,
                    'draindrainage' => $request->draindrainage,
                    'drainfreetext' => $request->drainfreetext,
                    'ivlnone' => $request->ivlnone,
                    'ivlsite' => $request->ivlsite,
                    'ivfreetext' => $request->ivfreetext,
                    'gucontinent' => $request->gucontinent,
                    'gufoley' => $request->gufoley,
                    'assesothers' => $request->assesothers,
                    'plannotes' => $request->plannotes,
                    'epistycode' => 'OP',
                    'location' => 'ED',
                    'adduser'  => session('username'),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur"),
                    'painscore' => $request->painscore,
                ]);

            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function edit_progress_ED(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            if(!empty($request->idno_progress)){
                DB::table('nursing.nurshandover')
                    ->where('idno','=',$request->idno_progress)
                    // ->where('mrn','=',$request->mrn_nursNote)
                    // ->where('episno','=',$request->episno_nursNote)
                    ->update([
                        'bpsys_stand' => $request->bpsys_stand,
                        'bpdias_stand' => $request->bpdias_stand,
                        'bpsys_lieDown' => $request->bpsys_lieDown,
                        'bpdias_lieDown' => $request->bpdias_lieDown,
                        'spo2' => $request->spo2,
                        'hr' => $request->hr,
                        'gxt' => $request->gxt,
                        'temp_' => $request->temp_,
                        'weight' => $request->weight,
                        'respiration' => $request->respiration,
                        'height' => $request->height,
                        'roomair' => $request->roomair,
                        'oxygen' => $request->oxygen,
                        'airwayfreetext' => $request->airwayfreetext,
                        'breathnormal' => $request->breathnormal,
                        'breathdifficult' => $request->breathdifficult,
                        'circarrythmias' => $request->circarrythmias,
                        'circlbp' => $request->circlbp,
                        'circhbp' => $request->circhbp,
                        'circirregular' => $request->circirregular,
                        'frhigh' => $request->frhigh,
                        'frlow' => $request->frlow,
                        'frfreetext' => $request->frfreetext,
                        'drainnone' => $request->drainnone,
                        'draindrainage' => $request->draindrainage,
                        'drainfreetext' => $request->drainfreetext,
                        'ivlnone' => $request->ivlnone,
                        'ivlsite' => $request->ivlsite,
                        'ivfreetext' => $request->ivfreetext,
                        'gucontinent' => $request->gucontinent,
                        'gufoley' => $request->gufoley,
                        'assesothers' => $request->assesothers,
                        'plannotes' => $request->plannotes,
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur"),
                        'painscore' => $request->painscore,
                    ]);
            }else{
                DB::table('nursing.nurshandover')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_nursNote,
                        'episno' => $request->episno_nursNote,
                        'datetaken' => $request->datetaken,
                        'timetaken' => $request->timetaken,
                        'bpsys_stand' => $request->bpsys_stand,
                        'bpdias_stand' => $request->bpdias_stand,
                        'bpsys_lieDown' => $request->bpsys_lieDown,
                        'bpdias_lieDown' => $request->bpdias_lieDown,
                        'spo2' => $request->spo2,
                        'hr' => $request->hr,
                        'gxt' => $request->gxt,
                        'temp_' => $request->temp_,
                        'weight' => $request->weight,
                        'respiration' => $request->respiration,
                        'height' => $request->height,
                        'roomair' => $request->roomair,
                        'oxygen' => $request->oxygen,
                        'airwayfreetext' => $request->airwayfreetext,
                        'breathnormal' => $request->breathnormal,
                        'breathdifficult' => $request->breathdifficult,
                        'circarrythmias' => $request->circarrythmias,
                        'circlbp' => $request->circlbp,
                        'circhbp' => $request->circhbp,
                        'circirregular' => $request->circirregular,
                        'frhigh' => $request->frhigh,
                        'frlow' => $request->frlow,
                        'frfreetext' => $request->frfreetext,
                        'drainnone' => $request->drainnone,
                        'draindrainage' => $request->draindrainage,
                        'drainfreetext' => $request->drainfreetext,
                        'ivlnone' => $request->ivlnone,
                        'ivlsite' => $request->ivlsite,
                        'ivfreetext' => $request->ivfreetext,
                        'gucontinent' => $request->gucontinent,
                        'gufoley' => $request->gufoley,
                        'assesothers' => $request->assesothers,
                        'plannotes' => $request->plannotes,
                        'epistycode' => 'OP',
                        'location' => 'ED',
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur"),
                    ]);
            }
            
            $queries = DB::getQueryLog();
            // dump($queries);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }

    public function add_patMedic(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('hisdb.patmedication')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn,
                    'episno' => $request->episno,
                    'auditno' => $request->auditno,
                    'chgcode' => $request->chgcode,
                    'entereddate' => $request->entereddate,
                    'enteredtime' => $request->enteredtime,
                    'failure' => $request->failure,
                    'remarks' => $request->remarks,
                    'qty' => $request->qty,
                    'enteredby' => session('username'),
                    'adduser'  => session('username'),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur")
                ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
        
    }

    public function add_pivc_ED(Request $request){
        
        DB::beginTransaction();

        try {
            
            DB::table('nursing.pivc')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn_nursNote,
                    'episno' => $request->episno_nursNote,
                    'practiceDate' => $request->practiceDate,
                    'consultant' => $request->consultant,
                    'hygiene_M' => $request->hygiene_M,
                    'hygiene_E' => $request->hygiene_E,
                    'hygiene_N' => $request->hygiene_N,
                    'dressing_M' => $request->dressing_M,
                    'dressing_E' => $request->dressing_E,
                    'dressing_N' => $request->dressing_N,
                    'alcoholSwab_M' => $request->alcoholSwab_M,
                    'alcoholSwab_E' => $request->alcoholSwab_E,
                    'alcoholSwab_N' => $request->alcoholSwab_N,
                    'siteLabelled_M' => $request->siteLabelled_M,
                    'siteLabelled_E' => $request->siteLabelled_E,
                    'siteLabelled_N' => $request->siteLabelled_N,
                    'correct_M' => $request->correct_M,
                    'correct_E' => $request->correct_E,
                    'correct_N' => $request->correct_N,
                    'multiDoseVial_M' => $request->multiDoseVial_M,
                    'multiDoseVial_E' => $request->multiDoseVial_E,
                    'multiDoseVial_N' => $request->multiDoseVial_N,
                    'cleanVial_M' => $request->cleanVial_M,
                    'cleanVial_E' => $request->cleanVial_E,
                    'cleanVial_N' => $request->cleanVial_N,
                    'splitSeptum_M' => $request->splitSeptum_M,
                    'splitSeptum_E' => $request->splitSeptum_E,
                    'splitSeptum_N' => $request->splitSeptum_N,
                    'cleanSite_M' => $request->cleanSite_M,
                    'cleanSite_E' => $request->cleanSite_E,
                    'cleanSite_N' => $request->cleanSite_N,
                    'chgSplitSeptum_M' => $request->chgSplitSeptum_M,
                    'chgSplitSeptum_E' => $request->chgSplitSeptum_E,
                    'chgSplitSeptum_N' => $request->chgSplitSeptum_N,
                    'flushingACL_M' => $request->flushingACL_M,
                    'flushingACL_E' => $request->flushingACL_E,
                    'flushingACL_N' => $request->flushingACL_N,
                    'clamping_M' => $request->clamping_M,
                    'clamping_E' => $request->clamping_E,
                    'clamping_N' => $request->clamping_N,
                    'set_M' => $request->set_M,
                    'set_E' => $request->set_E,
                    'set_N' => $request->set_N,
                    'removalPIVC_M' => $request->removalPIVC_M,
                    'removalPIVC_E' => $request->removalPIVC_E,
                    'removalPIVC_N' => $request->removalPIVC_N,
                    'name_M' => $request->name_M,
                    'name_E' => $request->name_E,
                    'name_N' => $request->name_N,
                    'datetime_M' => $request->datetime_M,
                    'datetime_E' => $request->datetime_E,
                    'datetime_N' => $request->datetime_N,
                    'adduser'  => session('username'),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur"),
                    'computerid' => session('computerid'),
                ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function edit_pivc_ED(Request $request){
        
        DB::beginTransaction();
        
        try {

            $pivc = DB::table('nursing.pivc')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn_nursNote)
                            ->where('episno','=',$request->episno_nursNote)
                            ->where('practiceDate','=',$request->practiceDate);
            
            if(!empty($request->idno_pivc)){
                DB::table('nursing.pivc')
                    ->where('idno','=',$request->idno_pivc)
                    ->update([
                        'consultant' => $request->consultant,
                        'hygiene_M' => $request->hygiene_M,
                        'hygiene_E' => $request->hygiene_E,
                        'hygiene_N' => $request->hygiene_N,
                        'dressing_M' => $request->dressing_M,
                        'dressing_E' => $request->dressing_E,
                        'dressing_N' => $request->dressing_N,
                        'alcoholSwab_M' => $request->alcoholSwab_M,
                        'alcoholSwab_E' => $request->alcoholSwab_E,
                        'alcoholSwab_N' => $request->alcoholSwab_N,
                        'siteLabelled_M' => $request->siteLabelled_M,
                        'siteLabelled_E' => $request->siteLabelled_E,
                        'siteLabelled_N' => $request->siteLabelled_N,
                        'correct_M' => $request->correct_M,
                        'correct_E' => $request->correct_E,
                        'correct_N' => $request->correct_N,
                        'multiDoseVial_M' => $request->multiDoseVial_M,
                        'multiDoseVial_E' => $request->multiDoseVial_E,
                        'multiDoseVial_N' => $request->multiDoseVial_N,
                        'cleanVial_M' => $request->cleanVial_M,
                        'cleanVial_E' => $request->cleanVial_E,
                        'cleanVial_N' => $request->cleanVial_N,
                        'splitSeptum_M' => $request->splitSeptum_M,
                        'splitSeptum_E' => $request->splitSeptum_E,
                        'splitSeptum_N' => $request->splitSeptum_N,
                        'cleanSite_M' => $request->cleanSite_M,
                        'cleanSite_E' => $request->cleanSite_E,
                        'cleanSite_N' => $request->cleanSite_N,
                        'chgSplitSeptum_M' => $request->chgSplitSeptum_M,
                        'chgSplitSeptum_E' => $request->chgSplitSeptum_E,
                        'chgSplitSeptum_N' => $request->chgSplitSeptum_N,
                        'flushingACL_M' => $request->flushingACL_M,
                        'flushingACL_E' => $request->flushingACL_E,
                        'flushingACL_N' => $request->flushingACL_N,
                        'clamping_M' => $request->clamping_M,
                        'clamping_E' => $request->clamping_E,
                        'clamping_N' => $request->clamping_N,
                        'set_M' => $request->set_M,
                        'set_E' => $request->set_E,
                        'set_N' => $request->set_N,
                        'removalPIVC_M' => $request->removalPIVC_M,
                        'removalPIVC_E' => $request->removalPIVC_E,
                        'removalPIVC_N' => $request->removalPIVC_N,
                        'name_M' => $request->name_M,
                        'name_E' => $request->name_E,
                        'name_N' => $request->name_N,
                        'datetime_M' => $request->datetime_M,
                        'datetime_E' => $request->datetime_E,
                        'datetime_N' => $request->datetime_N,
                        'upduser'  => session('username'),
                        'upddate'  => Carbon::now("Asia/Kuala_Lumpur"),
                    ]);
            }else{

                if($pivc->exists()){
                    return response('Date already exist.');
                }
                
                DB::table('nursing.pivc')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_nursNote,
                        'episno' => $request->episno_nursNote,
                        'practiceDate' => $request->practiceDate,
                        'consultant' => $request->consultant,
                        'hygiene_M' => $request->hygiene_M,
                        'hygiene_E' => $request->hygiene_E,
                        'hygiene_N' => $request->hygiene_N,
                        'dressing_M' => $request->dressing_M,
                        'dressing_E' => $request->dressing_E,
                        'dressing_N' => $request->dressing_N,
                        'alcoholSwab_M' => $request->alcoholSwab_M,
                        'alcoholSwab_E' => $request->alcoholSwab_E,
                        'alcoholSwab_N' => $request->alcoholSwab_N,
                        'siteLabelled_M' => $request->siteLabelled_M,
                        'siteLabelled_E' => $request->siteLabelled_E,
                        'siteLabelled_N' => $request->siteLabelled_N,
                        'correct_M' => $request->correct_M,
                        'correct_E' => $request->correct_E,
                        'correct_N' => $request->correct_N,
                        'multiDoseVial_M' => $request->multiDoseVial_M,
                        'multiDoseVial_E' => $request->multiDoseVial_E,
                        'multiDoseVial_N' => $request->multiDoseVial_N,
                        'cleanVial_M' => $request->cleanVial_M,
                        'cleanVial_E' => $request->cleanVial_E,
                        'cleanVial_N' => $request->cleanVial_N,
                        'splitSeptum_M' => $request->splitSeptum_M,
                        'splitSeptum_E' => $request->splitSeptum_E,
                        'splitSeptum_N' => $request->splitSeptum_N,
                        'cleanSite_M' => $request->cleanSite_M,
                        'cleanSite_E' => $request->cleanSite_E,
                        'cleanSite_N' => $request->cleanSite_N,
                        'chgSplitSeptum_M' => $request->chgSplitSeptum_M,
                        'chgSplitSeptum_E' => $request->chgSplitSeptum_E,
                        'chgSplitSeptum_N' => $request->chgSplitSeptum_N,
                        'flushingACL_M' => $request->flushingACL_M,
                        'flushingACL_E' => $request->flushingACL_E,
                        'flushingACL_N' => $request->flushingACL_N,
                        'clamping_M' => $request->clamping_M,
                        'clamping_E' => $request->clamping_E,
                        'clamping_N' => $request->clamping_N,
                        'set_M' => $request->set_M,
                        'set_E' => $request->set_E,
                        'set_N' => $request->set_N,
                        'removalPIVC_M' => $request->removalPIVC_M,
                        'removalPIVC_E' => $request->removalPIVC_E,
                        'removalPIVC_N' => $request->removalPIVC_N,
                        'name_M' => $request->name_M,
                        'name_E' => $request->name_E,
                        'name_N' => $request->name_N,
                        'datetime_M' => $request->datetime_M,
                        'datetime_E' => $request->datetime_E,
                        'datetime_N' => $request->datetime_N,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur"),
                        'computerid' => session('computerid'),
                    ]);
            }
            
            $queries = DB::getQueryLog();
            // dump($queries);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }

    public function add_thrombo_ED (Request $request){
        
        DB::beginTransaction();

        try {
            
            DB::table('nursing.thrombophlebitis')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn_nursNote,
                    'episno' => $request->episno_nursNote,
                    'dateInsert' => $request->dateInsert,
                    'timeInsert' => $request->timeInsert,
                    'gauge' => $request->gauge,
                    'attempts' => $request->attempts,
                    'sitesMetacarpal' => $request->sitesMetacarpal,
                    'sitesBasilic' => $request->sitesBasilic,
                    'sitesCephalic' => $request->sitesCephalic,
                    'sitesMCubital' => $request->sitesMCubital,
                    'dateRemoval' => $request->dateRemoval,
                    'timeRemoval' => $request->timeRemoval,
                    'totIndwelling' => $request->totIndwelling,
                    'remarksThrombo' => $request->remarksThrombo,
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
    
    public function edit_thrombo_ED(Request $request){
        
        DB::beginTransaction();
        
        try {

            $thrombo = DB::table('nursing.thrombophlebitis')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn_nursNote)
                            ->where('episno','=',$request->episno_nursNote)
                            ->where('dateInsert','=',$request->dateInsert);
            
            if(!empty($request->idno_thrombo)){
                DB::table('nursing.thrombophlebitis')
                    ->where('idno','=',$request->idno_thrombo)
                    ->update([
                        'gauge' => $request->gauge,
                        'attempts' => $request->attempts,
                        'sitesMetacarpal' => $request->sitesMetacarpal,
                        'sitesBasilic' => $request->sitesBasilic,
                        'sitesCephalic' => $request->sitesCephalic,
                        'sitesMCubital' => $request->sitesMCubital,
                        'dateRemoval' => $request->dateRemoval,
                        'timeRemoval' => $request->timeRemoval,
                        'totIndwelling' => $request->totIndwelling,
                        'remarksThrombo' => $request->remarksThrombo,
                        'upduser'  => session('username'),
                        'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastcomputerid' => session('computerid'),

                    ]);
            }else{

                if($thrombo->exists()){
                    return response('Date already exist.');
                }
                
                DB::table('nursing.thrombophlebitis')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_nursNote,
                        'episno' => $request->episno_nursNote,
                        'dateInsert' => $request->dateInsert,
                        'timeInsert' => $request->timeInsert,
                        'gauge' => $request->gauge,
                        'attempts' => $request->attempts,
                        'sitesMetacarpal' => $request->sitesMetacarpal,
                        'sitesBasilic' => $request->sitesBasilic,
                        'sitesCephalic' => $request->sitesCephalic,
                        'sitesMCubital' => $request->sitesMCubital,
                        'dateRemoval' => $request->dateRemoval,
                        'timeRemoval' => $request->timeRemoval,
                        'totIndwelling' => $request->totIndwelling,
                        'remarksThrombo' => $request->remarksThrombo,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
            }
            
            $queries = DB::getQueryLog();
            // dump($queries);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }

    public function add_thrombojqgrid_ED(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.thrombophlebitisadd')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'cannulationNo' => $request->cannulationNo,
                        'flushingDone' => $request->flushingDone,
                        'dateAssessment' => $request->dateAssessment,
                        'shift' => $request->shift,
                        'dressingChanged' => $request->dressingChanged,
                        'staffId' => session('username'),
                        'phlebitisGrade' => $request->phlebitisGrade,
                        'infiltration' => $request->infiltration,
                        'hematoma' => $request->hematoma,
                        'extravasation' => $request->extravasation,
                        'occlusion' => $request->occlusion,
                        'asPerProtocol' => $request->asPerProtocol,
                        'ptDischarged' => $request->ptDischarged,
                        'ivTerminate' => $request->ivTerminate,
                        'fibrinClot' => $request->fibrinClot,
                        'kinkedHub' => $request->kinkedHub,
                        'kinkedShaft' => $request->kinkedShaft,
                        'tipDamage' => $request->tipDamage,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
    
    }
    
    public function edit_thrombojqgrid_ED(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.thrombophlebitisadd')
                ->where('idno','=',$request->idno)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'flushingDone' => $request->flushingDone,
                    'dateAssessment' => $request->dateAssessment,
                    'shift' => $request->shift,
                    'dressingChanged' => $request->dressingChanged,
                    'staffId' => session('username'),
                    'phlebitisGrade' => $request->phlebitisGrade,
                    'infiltration' => $request->infiltration,
                    'hematoma' => $request->hematoma,
                    'extravasation' => $request->extravasation,
                    'occlusion' => $request->occlusion,
                    'asPerProtocol' => $request->asPerProtocol,
                    'ptDischarged' => $request->ptDischarged,
                    'ivTerminate' => $request->ivTerminate,
                    'fibrinClot' => $request->fibrinClot,
                    'kinkedHub' => $request->kinkedHub,
                    'kinkedShaft' => $request->kinkedShaft,
                    'tipDamage' => $request->tipDamage,
                    'upduser'  => session('username'),
                    'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'computerid' => session('computerid'),
                ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
    
    }
    
    public function del_thrombojqgrid_ED(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.thrombophlebitisadd')
                ->where('idno','=',$request->idno)
                ->where('compcode','=',session('compcode'))
                ->delete();
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
    
    }

    public function get_table_progress_ED(Request $request){
        $nurshandover_obj = DB::table('nursing.nurshandover')
                            ->where('compcode','=',session('compcode'))
                            ->where('idno','=',$request->idno);
                            // ->where('epistycode','=','OP')
                            // ->where('mrn','=',$request->mrn)
                            // ->where('episno','=',$request->episno);
        
        $responce = new stdClass();
        
        if($nurshandover_obj->exists()){
            $nurshandover_obj = $nurshandover_obj->first();
            // dd($nurshandover_obj);
            $date = Carbon::createFromFormat('Y-m-d', $nurshandover_obj->datetaken)->format('Y-m-d');
            $time = Carbon::createFromFormat('H:i:s', $nurshandover_obj->timetaken)->format('h:i A');
            
            $responce->nurshandover = $nurshandover_obj;
            $responce->date = $date;
            $responce->time = $time;

        }
        
        return json_encode($responce);
        
    }

    public function get_table_drug(Request $request){
        
        $patmedication_obj = DB::table('hisdb.patmedication')
                            ->where('compcode','=',session('compcode'))
                            // ->where('idno','=',$request->idno);
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno)
                            ->where('auditno','=',$request->auditno)
                            ->where('chgcode','=',$request->chgcode);
        
        $responce = new stdClass();
        
        if($patmedication_obj->exists()){
            $total_qty = $patmedication_obj->sum('qty');
            $responce->total_qty = $total_qty;
            
            $patmedication_obj = $patmedication_obj->first();
            $responce->patmedication = $patmedication_obj;
        }
        
        return json_encode($responce);
        
    }

    public function get_table_pivc_ED(Request $request){
        
        $pivc_ED_obj = DB::table('nursing.pivc')
                            ->where('compcode','=',session('compcode'))
                            ->where('idno','=',$request->idno);
        
        $responce = new stdClass();
        
        if($pivc_ED_obj->exists()){
            $pivc_ED_obj = $pivc_ED_obj->first();
            $date = Carbon::createFromFormat('Y-m-d', $pivc_ED_obj->practiceDate)->format('Y-m-d');

            $responce->pivc = $pivc_ED_obj;
            $responce->date = $date;
        }
        
        return json_encode($responce);
        
    }

    public function get_table_thrombo_ED (Request $request){
        
        $thrombo_ED_obj = DB::table('nursing.thrombophlebitis')
                            ->where('compcode','=',session('compcode'))
                            ->where('idno','=',$request->idno);
        
        $responce = new stdClass();
        
        if($thrombo_ED_obj->exists()){
            $thrombo_ED_obj = $thrombo_ED_obj->first();
            $date = Carbon::createFromFormat('Y-m-d', $thrombo_ED_obj->dateInsert)->format('Y-m-d');
            
            $responce->thrombo = $thrombo_ED_obj;
            $responce->date = $date;
        }
        
        return json_encode($responce);
        
    }
}