<?php

namespace App\Http\Controllers\hisdb;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class NursingNoteMRController extends defaultController
{
    
    var $table;
    var $duplicateCode;
    
    public function __construct(){
        $this->middleware('auth');
        // $this->duplicateCode = "chgtype";
    }
    
    public function show(Request $request){
        return view('hisdb.nursingnote_MR.nursingnote_MR');
    }
    
    public function table(Request $request)
    {
        switch($request->action){
            case 'get_table_datetime': // Progress Note
                return $this->get_table_datetime($request);
            
            case 'get_datetime_intake': // Intake Output
                return $this->get_datetime_intake($request);
            
            case 'get_prescription': // Drug Administration
                return $this->get_prescription($request);
            
            case 'get_datetime_treatment': // Treatment
                return $this->get_datetime_treatment($request);
            
            case 'get_datetime_careplan': // Care Plan
                return $this->get_datetime_careplan($request);
            
            case 'invChart_file': // Investigation - Upload file
                return $this->invChart_file($request);
            
            case 'get_invcat': // Investigation - DataTable
                return $this->get_invcat($request);
            
            case 'get_table_drug':
                return $this->get_table_drug($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function form(Request $request){
        DB::enableQueryLog();
        switch($request->action){
            case 'get_table_progress':
                return $this->get_table_progress($request);
            
            case 'get_table_intake':
                return $this->get_table_intake($request);
            
            case 'get_table_drug':
                return $this->get_table_drug($request);
            
            case 'get_table_treatment':
                return $this->get_table_treatment($request);
            
            case 'get_table_careplan':
                return $this->get_table_careplan($request);
            
            case 'get_table_formFitChart':
                return $this->get_table_formFitChart($request);
            
            case 'get_table_formOthersChart':
                return $this->get_table_formOthersChart($request);
            
            case 'get_table_formInvHeader':
                return $this->get_table_formInvHeader($request);
            
            case 'uploadfile':
                return $this->uploadfile($request);
            
            case 'get_table_bladder1':
                return $this->get_table_bladder1($request);
            
            case 'get_table_bladder2':
                return $this->get_table_bladder2($request);
            
            case 'get_table_bladder3':
                return $this->get_table_bladder3($request);

            default:
                return 'error happen..';
        }
        
        // switch($request->oper){
        //     default:
        //         return 'error happen..';
        // }
    }

    //////////////////////////////////////////INVESTIGATION CHART/////////////////////////////////////////

    public function invChart_file(Request $request){
        
        $responce = new stdClass();
        
        $nurs_invest_file = DB::table('nursing.nurs_invest_file')
                            ->where('compcode',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno);
        
        if($nurs_invest_file->exists()){
            $nurs_invest_file = $nurs_invest_file->get();
            
            $data = [];
            
            foreach($nurs_invest_file as $key => $value){
                $date = [];
                
                $date['idno'] = $value->idno;
                $date['compcode'] = $value->compcode;
                $date['mrn'] = $value->mrn;
                $date['episno'] = $value->episno;
                $date['path'] = $value->path;
                $date['filename'] = $value->filename;
                
                array_push($data,$date);
            }
            
            $responce->data = $data;
        }else{
            $responce->data = [];
        }
        
        return json_encode($responce);
        
    }

    public function get_table_formInvHeader(Request $request){
        
        $episode_obj = DB::table('hisdb.episode')
                        ->select('reg_date')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno);
        
        $responce = new stdClass();
        
        if($episode_obj->exists()){
            $episode_obj = $episode_obj->first();
            // dd($episode_obj);
            $responce->episode = $episode_obj;
        }
        
        return json_encode($responce);
        
    }

    public function get_invcat(Request $request){
        
        $responce = new stdClass();
        
        $nurs_invest_cat_obj = DB::table('nursing.nurs_invest_cat')
                                ->where('compcode','=',session('compcode'))
                                ->where('inv_code','=',$request->inv_code);
        
        if($nurs_invest_cat_obj->exists()){
            $nurs_invest_cat_obj = $nurs_invest_cat_obj->get();
            // dd($nurs_invest_cat_obj);
            
            $data = [];
            
            foreach($nurs_invest_cat_obj as $key => $value){
                $date['idno'] = $value->idno;
                $date['inv_code'] = $value->inv_code;
                $date['inv_cat'] = $value->inv_cat;
                
                array_push($data,$date);
            }
            
            $responce->data = $data;
        }else{
            $responce->data = [];
        }
        
        return json_encode($responce);
        
    }

    public function uploadfile(Request $request){
        
        $type = $request->file('file')->getClientMimeType();
        $filename = $request->file('file')->getClientOriginalName();
        $file_path = $request->file('file')->store('invChart', 'public_uploads');
        
        DB::table('nursing.nurs_invest_file')
            ->insert([
                'compcode' => session('compcode'),
                'mrn' => $request->mrn,
                'episno' => $request->episno,
                'filename' => $filename,
                'path' => $file_path,
                'adduser'  => session('username'),
                'adddate'  => Carbon::now("Asia/Kuala_Lumpur"),
                'computerid' => session('computerid'),
            ]);
        
        $responce = new stdClass();
        $responce->file_path = $file_path;
        return json_encode($responce);
        
    }

    //////////////////////////////////////////PROGRESS NOTE//////////////////////////////////////////
    
    public function get_table_datetime(Request $request){
        
        $responce = new stdClass();
        
        $nurshandover_obj = DB::table('nursing.nurshandover')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno);
        
        if($nurshandover_obj->exists()){
            $nurshandover_obj = $nurshandover_obj->get();
            
            $data = [];
            
            foreach($nurshandover_obj as $key => $value){
                if(!empty($value->datetaken)){
                    $date['datetaken'] =  Carbon::createFromFormat('Y-m-d', $value->datetaken)->format('d-m-Y');
                }else{
                    $date['datetaken'] =  '-';
                }
                $date['idno'] = $value->idno;
                $date['mrn'] = $value->mrn;
                $date['episno'] = $value->episno;
                // $date['timetaken'] = $value->timetaken;
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

    public function get_table_progress(Request $request){
        
        $nurshandover_obj = DB::table('nursing.nurshandover')
                            ->where('compcode','=',session('compcode'))
                            ->where('idno','=',$request->idno);
                            // ->where('mrn','=',$request->mrn)
                            // ->where('episno','=',$request->episno);
        
        $responce = new stdClass();
        
        if($nurshandover_obj->exists()){
            $nurshandover_obj = $nurshandover_obj->first();
            $date = Carbon::createFromFormat('Y-m-d', $nurshandover_obj->datetaken)->format('Y-m-d');
            
            $responce->nurshandover = $nurshandover_obj;
            $responce->date = $date;
        }
        
        return json_encode($responce);
        
    }

    //////////////////////////////////////////INTAKE OUTPUT//////////////////////////////////////////

    public function get_datetime_intake(Request $request){
        
        $responce = new stdClass();
        
        $intakeoutput_obj = DB::table('nursing.intakeoutput')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno);
        
        if($intakeoutput_obj->exists()){
            $intakeoutput_obj = $intakeoutput_obj->get();
            
            $data = [];
            
            foreach($intakeoutput_obj as $key => $value){
                if(!empty($value->recorddate)){
                    $date['recorddate'] =  Carbon::createFromFormat('Y-m-d', $value->recorddate)->format('d-m-Y');
                }else{
                    $date['recorddate'] =  '-';
                }
                $date['idno'] = $value->idno;
                $date['mrn'] = $value->mrn;
                $date['episno'] = $value->episno;
                
                array_push($data,$date);
            }
            
            $responce->data = $data;
        }else{
            $responce->data = [];
        }
        
        return json_encode($responce);
        
    }

    public function get_table_intake(Request $request){
        
        $intakeoutput_obj = DB::table('nursing.intakeoutput')
                            ->where('compcode','=',session('compcode'))
                            ->where('idno','=',$request->idno);
                            // ->where('mrn','=',$request->mrn)
                            // ->where('episno','=',$request->episno);
        
        $responce = new stdClass();
        
        if($intakeoutput_obj->exists()){
            $intakeoutput_obj = $intakeoutput_obj->first();
            $responce->intakeoutput = $intakeoutput_obj;
        }
        
        return json_encode($responce);
        
    }
    
    //////////////////////////////////////////DRUG ADMIN//////////////////////////////////////////

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
            
            foreach($chargetrx_obj as $key => $value){
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

    public function get_table_drug(Request $request){
        
        $patmedication_obj = DB::table('hisdb.patmedication')
                            ->where('compcode','=',session('compcode'))
                            // ->where('idno','=',$request->idno);
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno)
                            ->where('auditno','=',$request->auditno)
                            ->where('chgcode','=',$request->chgcode)
                            ->orderBy('idno', 'asc');
        
        $responce = new stdClass();
        
        if($patmedication_obj->exists()){

            $patmedication_get = $patmedication_obj->get();
            $x=1;
            foreach ($patmedication_get as $obj) {
                $obj->no = $x;
                $x++;

                if($obj->entereddate == null){
                   $obj->entereddate = Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d'); 
                   $obj->entereddate = ''; 

                }
                if($obj->enteredtime == null){
                   $obj->enteredtime = Carbon::now("Asia/Kuala_Lumpur")->format('H:i:s'); 
                   $obj->enteredtime = ''; 
                }
                if($obj->enteredby == null){
                   $obj->enteredby = session('username');
                   $obj->enteredby = ''; 
                }
            }

            $total_qty = $patmedication_obj->sum('qty');
            $responce->total_qty = $total_qty;
            
            $responce->rows = $patmedication_get;
        }
        
        return json_encode($responce);
    }

    //////////////////////////////////////////NURSING REPORT//////////////////////////////////////////

    public function get_datetime_treatment(Request $request){
        
        $responce = new stdClass();
        
        $pattreatment_obj = DB::table('nursing.pattreatment')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno)
                            ->where('type','=',$request->type);
        
        if($pattreatment_obj->exists()){
            $pattreatment_obj = $pattreatment_obj->get();
            
            $data = [];
            
            foreach($pattreatment_obj as $key => $value){
                if(!empty($value->entereddate)){
                    $date['datetime'] =  Carbon::createFromFormat('Y-m-d', $value->entereddate)->format('d-m-Y').'<br>'.Carbon::createFromFormat('H:i:s', $value->enteredtime)->format('h:i A');
                }else{
                    $date['datetime'] =  '-';
                }
                $date['idno'] = $value->idno;
                $date['mrn'] = $value->mrn;
                $date['episno'] = $value->episno;
                $date['adduser'] = $value->adduser;
                if(!empty($value->entereddate)){ // for sorting - easier in 24H
                    $date['dt'] =  Carbon::createFromFormat('Y-m-d', $value->entereddate)->format('d-m-Y').'<br>'.$value->enteredtime;
                }else{
                    $date['dt'] =  '-';
                }
                // $date['enteredtime'] = $value->enteredtime;
                // if(!empty($value->enteredtime)){
                //     $date['enteredtime'] =  Carbon::createFromFormat('H:i:s', $value->enteredtime)->format('h:i A');
                // }else{
                //     $date['enteredtime'] =  '-';
                // }
                
                array_push($data,$date);
            }
            
            $responce->data = $data;
        }else{
            $responce->data = [];
        }
        
        return json_encode($responce);
        
    }

    public function get_table_treatment(Request $request){
        
        $treatment_obj = DB::table('nursing.pattreatment')
                        ->select('mrn','episno','type','entereddate as tr_entereddate','enteredtime as tr_enteredtime','remarks as treatment_remarks','adduser as treatment_adduser')
                        ->where('compcode','=',session('compcode'))
                        ->where('idno','=',$request->idno);
                        // ->where('mrn','=',$request->mrn)
                        // ->where('episno','=',$request->episno)
                        // ->where('type','=','TREATMENT');
        
        $investigation_obj = DB::table('nursing.pattreatment')
                        ->select('mrn','episno','type','entereddate as inv_entereddate','enteredtime as inv_enteredtime','remarks as investigation_remarks','adduser as investigation_adduser')
                        ->where('compcode','=',session('compcode'))
                        ->where('idno','=',$request->idno);
                        // ->where('mrn','=',$request->mrn)
                        // ->where('episno','=',$request->episno)
                        // ->where('type','=','INVESTIGATION');
        
        $injection_obj = DB::table('nursing.pattreatment')
                        ->select('mrn','episno','type','entereddate as inj_entereddate','enteredtime as inj_enteredtime','remarks as injection_remarks','adduser as injection_adduser')
                        ->where('compcode','=',session('compcode'))
                        ->where('idno','=',$request->idno);
                        // ->where('mrn','=',$request->mrn)
                        // ->where('episno','=',$request->episno)
                        // ->where('type','=','INJECTION');
        
        $responce = new stdClass();
        
        if($treatment_obj->exists()){
            $treatment_obj = $treatment_obj->first();
            $responce->treatment = $treatment_obj;
        }
        
        if($investigation_obj->exists()){
            $investigation_obj = $investigation_obj->first();
            $responce->investigation = $investigation_obj;
        }
        
        if($injection_obj->exists()){
            $injection_obj = $injection_obj->first();
            $responce->injection = $injection_obj;
        }
        
        return json_encode($responce);
        
    }
    
    //////////////////////////////////////////CARE PLAN//////////////////////////////////////////

    public function get_datetime_careplan(Request $request){
        
        $responce = new stdClass();
        
        $nurscareplan_obj = DB::table('nursing.nurscareplan')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno);
        
        if($nurscareplan_obj->exists()){
            $nurscareplan_obj = $nurscareplan_obj->get();
            
            $data = [];
            
            foreach($nurscareplan_obj as $key => $value){
                $date['idno'] = $value->idno;
                $date['mrn'] = $value->mrn;
                $date['episno'] = $value->episno;
                if(!empty($value->entereddate)){
                    $date['entereddate'] =  Carbon::createFromFormat('Y-m-d H:i:s', $value->entereddate)->format('d-m-Y');
                }else{
                    $date['entereddate'] =  '-';
                }
                // $date['enteredtime'] = $value->enteredtime;
                if(!empty($value->enteredtime)){
                    $date['enteredtime'] =  Carbon::createFromFormat('H:i:s', $value->enteredtime)->format('h:i A');
                }else{
                    $date['enteredtime'] =  '-';
                }
                
                array_push($data,$date);
            }
            
            $responce->data = $data;
        }else{
            $responce->data = [];
        }
        
        return json_encode($responce);
        
    }

    public function get_table_careplan(Request $request){
        
        $nurscareplan_obj = DB::table('nursing.nurscareplan')
                            ->where('compcode','=',session('compcode'))
                            ->where('idno','=',$request->idno);
                            // ->where('mrn','=',$request->mrn)
                            // ->where('episno','=',$request->episno);
        
        $responce = new stdClass();
        
        if($nurscareplan_obj->exists()){
            $nurscareplan_obj = $nurscareplan_obj->first();
            $responce->nurscareplan = $nurscareplan_obj;
        }
        
        return json_encode($responce);
        
    }
    
    //////////////////////////////////////////FIT CHART//////////////////////////////////////////

    public function get_table_formFitChart(Request $request){
        
        // $nursassessment_obj = DB::table('nursing.nursassessment')
        //                     ->select('diagnosis')
        //                     ->where('compcode','=',session('compcode'))
        //                     ->where('mrn','=',$request->mrn)
        //                     ->where('episno','=',$request->episno);
        
        $nursactplan_obj = DB::table('nursing.nursactplan_hdr')
                            ->select('diagnosis')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno);
        
        $responce = new stdClass();
        
        // if($nursassessment_obj->exists()){
        //     $nursassessment_obj = $nursassessment_obj->first();
            
        //     $diagnosis_obj = $nursassessment_obj->diagnosis;
        //     $responce->diagnosis = $diagnosis_obj;
        // }
        
        if($nursactplan_obj->exists()){
            $nursactplan_obj = $nursactplan_obj->first();
            
            $diagnosis_obj = $nursactplan_obj->diagnosis;
            $responce->diagnosis = $diagnosis_obj;
        }
        
        return json_encode($responce);
        
    }

    public function fitchart_chart(Request $request){
        
        $mrn = $request->mrn;
        $episno = $request->episno;
        if(!$mrn || !$episno){
            abort(404);
        }
        
        $pat_mast = DB::table('hisdb.pat_mast as pm')
                    // ->select('pm.MRN','pm.Name','e.ward as e_ward','e.bed as bednum','b.ward','ba.ward as ba_ward','ba.bednum as ba_bednum','n.diagnosis')
                    ->select('pm.MRN','pm.Name','e.bed as bednum','b.ward','n.diagnosis as n_diagnosis','na.diagnosis')
                    ->leftJoin('hisdb.episode as e', function ($join){
                        $join = $join->on('e.mrn','=','pm.MRN')
                                    ->on('e.episno','=','pm.Episno')
                                    ->where('e.compcode','=',session('compcode'));
                    })
                    ->leftJoin('hisdb.bed as b', function ($join){
                        $join = $join->on('b.bednum','=','e.bed')
                                    // ->on('b.episno','=','pm.Episno')
                                    ->where('b.compcode','=',session('compcode'));
                    })
                    // ->leftJoin('hisdb.bedalloc as ba', function ($join){
                    //     $join = $join->on('ba.mrn','=','pm.MRN')
                    //                 ->on('ba.episno','=','pm.Episno')
                    //                 ->where('ba.compcode','=',session('compcode'));
                    // })
                    ->leftJoin('nursing.nursassessment as n', function ($join){
                        $join = $join->on('n.mrn','=','pm.MRN')
                                    ->on('n.episno','=','pm.Episno')
                                    ->where('n.compcode','=',session('compcode'));
                    })
                    ->leftJoin('nursing.nursactplan_hdr as na', function ($join){
                        $join = $join->on('na.mrn','=','pm.MRN')
                                    ->on('na.episno','=','pm.Episno')
                                    ->where('na.compcode','=',session('compcode'));
                    })
                    ->where('pm.CompCode','=',session('compcode'))
                    ->where('pm.MRN','=',$mrn)
                    // ->where('pm.Episno','=',$episno)
                    ->first();
        
        // $pat_mast = DB::table('hisdb.pat_mast as pm')
        //             ->select('pm.MRN','pm.Name','b.ward','b.bednum','n.diagnosis')
        //             ->leftJoin('hisdb.bedalloc as b', function ($join){
        //                 $join = $join->on('b.mrn','=','pm.MRN')
        //                             ->on('b.episno','=','pm.Episno')
        //                             ->where('b.compcode','=',session('compcode'));
        //             })
        //             ->leftJoin('nursing.nursassessment as n', function ($join){
        //                 $join = $join->on('n.mrn','=','pm.MRN')
        //                             ->on('n.episno','=','pm.Episno')
        //                             ->where('n.compcode','=',session('compcode'));
        //             })
        //             ->where('pm.CompCode','=',session('compcode'))
        //             ->where('pm.MRN','=',$mrn)
        //             ->where('pm.Episno','=',$episno)
        //             ->first();
        
        $nurs_fitchart = DB::table('nursing.nurs_fitchart as fc')
                        ->select('fc.compcode','fc.mrn','fc.episno','fc.entereddate','fc.enteredtime','fc.fit','fc.duration','fc.remarks','fc.adduser','fc.adddate','fc.addtime','fc.upduser','fc.upddate','fc.lastuser','fc.lastupdate','fc.lastupdtime','fc.computerid')
                        // ->leftJoin('hisdb.pat_mast as pm', function ($join){
                        //     $join = $join->on('pm.MRN','=','fc.mrn')
                        //                 ->where('pm.compcode','=',session('compcode'));
                        // })
                        ->where('fc.compcode','=',session('compcode'))
                        ->where('fc.mrn','=',$mrn)
                        ->where('fc.episno','=',$episno)
                        ->get();
        // dd($nurs_fitchart);
        
        return view('hisdb.nursingnote_MR.fitchart_chart_pdfmake', compact('pat_mast','nurs_fitchart'));
        
    }

    //////////////////////////////////////////CIRCULATION CHART//////////////////////////////////////////

    public function circulation_chart(Request $request){
        
        $mrn = $request->mrn;
        $episno = $request->episno;
        if(!$mrn || !$episno){
            abort(404);
        }
        $age = $request->age;
        
        $pat_mast = DB::table('hisdb.pat_mast as pm')
                    // ->select('pm.MRN','pm.Name','e.ward as e_ward','e.bed as bednum','b.ward','ba.ward as ba_ward','ba.bednum as ba_bednum','n.diagnosis')
                    ->select('pm.MRN','pm.Name','e.bed as bednum','b.ward','n.diagnosis as n_diagnosis','na.diagnosis')
                    ->leftJoin('hisdb.episode as e', function ($join){
                        $join = $join->on('e.mrn','=','pm.MRN')
                                    ->on('e.episno','=','pm.Episno')
                                    ->where('e.compcode','=',session('compcode'));
                    })
                    ->leftJoin('hisdb.bed as b', function ($join){
                        $join = $join->on('b.bednum','=','e.bed')
                                    // ->on('b.episno','=','pm.Episno')
                                    ->where('b.compcode','=',session('compcode'));
                    })
                    // ->leftJoin('hisdb.bedalloc as ba', function ($join){
                    //     $join = $join->on('ba.mrn','=','pm.MRN')
                    //                 ->on('ba.episno','=','pm.Episno')
                    //                 ->where('ba.compcode','=',session('compcode'));
                    // })
                    ->leftJoin('nursing.nursassessment as n', function ($join){
                        $join = $join->on('n.mrn','=','pm.MRN')
                                    ->on('n.episno','=','pm.Episno')
                                    ->where('n.compcode','=',session('compcode'));
                    })
                    ->leftJoin('nursing.nursactplan_hdr as na', function ($join){
                        $join = $join->on('na.mrn','=','pm.MRN')
                                    ->on('na.episno','=','pm.Episno')
                                    ->where('na.compcode','=',session('compcode'));
                    })
                    ->where('pm.CompCode','=',session('compcode'))
                    ->where('pm.MRN','=',$mrn)
                    // ->where('pm.Episno','=',$episno)
                    ->first();
        
        // $pat_mast = DB::table('hisdb.pat_mast as pm')
        //             ->select('pm.MRN','pm.Name','b.ward','b.bednum','n.diagnosis')
        //             ->leftJoin('hisdb.bedalloc as b', function ($join){
        //                 $join = $join->on('b.mrn','=','pm.MRN')
        //                             ->on('b.episno','=','pm.Episno')
        //                             ->where('b.compcode','=',session('compcode'));
        //             })
        //             ->leftJoin('nursing.nursassessment as n', function ($join){
        //                 $join = $join->on('n.mrn','=','pm.MRN')
        //                             ->on('n.episno','=','pm.Episno')
        //                             ->where('n.compcode','=',session('compcode'));
        //             })
        //             ->where('pm.CompCode','=',session('compcode'))
        //             ->where('pm.MRN','=',$mrn)
        //             ->where('pm.Episno','=',$episno)
        //             ->first();
        
        $nurs_circulation = DB::table('nursing.nurs_circulation as cr')
                            ->select('cr.compcode','cr.mrn','cr.episno','cr.entereddate','cr.enteredtime','cr.capillary','cr.skintemp','cr.pulse','cr.movement','cr.sensation','cr.oedema','cr.adduser','cr.adddate','cr.addtime','cr.upduser','cr.upddate','cr.lastuser','cr.lastupdate','cr.lastupdtime','cr.computerid')
                            // ->leftJoin('hisdb.pat_mast as pm', function ($join){
                            //     $join = $join->on('pm.MRN','=','cr.mrn')
                            //                 ->where('pm.compcode','=',session('compcode'));
                            // })
                            ->where('cr.compcode','=',session('compcode'))
                            ->where('cr.mrn','=',$mrn)
                            ->where('cr.episno','=',$episno)
                            ->get();
        // dd($nurs_circulation);
        
        return view('hisdb.nursingnote_MR.circulation_chart_pdfmake', compact('age','pat_mast','nurs_circulation'));
        
    }

    //////////////////////////////////////////SLIDING SCALE CHART//////////////////////////////////////////

    public function slidingScale_chart(Request $request){
        
        $mrn = $request->mrn;
        $episno = $request->episno;
        if(!$mrn || !$episno){
            abort(404);
        }
        
        $pat_mast = DB::table('hisdb.pat_mast as pm')
                    // ->select('pm.MRN','pm.Name','e.ward as e_ward','e.bed as bednum','b.ward','ba.ward as ba_ward','ba.bednum as ba_bednum','n.diagnosis')
                    ->select('pm.MRN','pm.Name','e.bed as bednum','b.ward','n.diagnosis as n_diagnosis','na.diagnosis')
                    ->leftJoin('hisdb.episode as e', function ($join){
                        $join = $join->on('e.mrn','=','pm.MRN')
                                    ->on('e.episno','=','pm.Episno')
                                    ->where('e.compcode','=',session('compcode'));
                    })
                    ->leftJoin('hisdb.bed as b', function ($join){
                        $join = $join->on('b.bednum','=','e.bed')
                                    // ->on('b.episno','=','pm.Episno')
                                    ->where('b.compcode','=',session('compcode'));
                    })
                    // ->leftJoin('hisdb.bedalloc as ba', function ($join){
                    //     $join = $join->on('ba.mrn','=','pm.MRN')
                    //                 ->on('ba.episno','=','pm.Episno')
                    //                 ->where('ba.compcode','=',session('compcode'));
                    // })
                    ->leftJoin('nursing.nursassessment as n', function ($join){
                        $join = $join->on('n.mrn','=','pm.MRN')
                                    ->on('n.episno','=','pm.Episno')
                                    ->where('n.compcode','=',session('compcode'));
                    })
                    ->leftJoin('nursing.nursactplan_hdr as na', function ($join){
                        $join = $join->on('na.mrn','=','pm.MRN')
                                    ->on('na.episno','=','pm.Episno')
                                    ->where('na.compcode','=',session('compcode'));
                    })
                    ->where('pm.CompCode','=',session('compcode'))
                    ->where('pm.MRN','=',$mrn)
                    // ->where('pm.Episno','=',$episno)
                    ->first();
        
        // $pat_mast = DB::table('hisdb.pat_mast as pm')
        //             ->select('pm.MRN','pm.Name','b.ward','b.bednum','n.diagnosis')
        //             ->leftJoin('hisdb.bedalloc as b', function ($join){
        //                 $join = $join->on('b.mrn','=','pm.MRN')
        //                             ->on('b.episno','=','pm.Episno')
        //                             ->where('b.compcode','=',session('compcode'));
        //             })
        //             ->leftJoin('nursing.nursassessment as n', function ($join){
        //                 $join = $join->on('n.mrn','=','pm.MRN')
        //                             ->on('n.episno','=','pm.Episno')
        //                             ->where('n.compcode','=',session('compcode'));
        //             })
        //             ->where('pm.CompCode','=',session('compcode'))
        //             ->where('pm.MRN','=',$mrn)
        //             ->where('pm.Episno','=',$episno)
        //             ->first();
        
        $nurs_slidingscale = DB::table('nursing.nurs_slidingscale as ss')
                            ->select('ss.compcode','ss.mrn','ss.episno','ss.entereddate','ss.enteredtime','ss.dextrostix','ss.remarks','ss.adduser','ss.adddate','ss.addtime','ss.upduser','ss.upddate','ss.lastuser','ss.lastupdate','ss.lastupdtime','ss.computerid')
                            // ->leftJoin('hisdb.pat_mast as pm', function ($join){
                            //     $join = $join->on('pm.MRN','=','ss.mrn')
                            //                 ->where('pm.compcode','=',session('compcode'));
                            // })
                            ->where('ss.compcode','=',session('compcode'))
                            ->where('ss.mrn','=',$mrn)
                            ->where('ss.episno','=',$episno)
                            ->get();
        // dd($nurs_slidingscale);
        
        return view('hisdb.nursingnote_MR.slidingScale_chart_pdfmake', compact('pat_mast','nurs_slidingscale'));
        
    }

    //////////////////////////////////////////PAD & DRAIN CHART//////////////////////////////////////////

    public function get_table_formOthersChart(Request $request){
        
        $nurs_othershdr_obj = DB::table('nursing.nurs_othershdr')
                            ->select('title')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno)
                            ->where('tabtitle','=',$request->tabtitle);
        
        // $nursassessment_obj = DB::table('nursing.nursassessment')
        //                     ->select('diagnosis')
        //                     ->where('compcode','=',session('compcode'))
        //                     ->where('mrn','=',$request->mrn)
        //                     ->where('episno','=',$request->episno);
        
        $nursactplan_obj = DB::table('nursing.nursactplan_hdr')
                            ->select('diagnosis')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno);
        
        $responce = new stdClass();
        
        if($nurs_othershdr_obj->exists()){
            $nurs_othershdr_obj = $nurs_othershdr_obj->first();
            
            $title_obj = $nurs_othershdr_obj->title;
            $responce->title = $title_obj;
            // $responce->nurs_othershdr = $nurs_othershdr_obj;
        }
        
        // if($nursassessment_obj->exists()){
        //     $nursassessment_obj = $nursassessment_obj->first();
            
        //     $diagnosis_obj = $nursassessment_obj->diagnosis;
        //     $responce->diagnosis = $diagnosis_obj;
        // }
        
        if($nursactplan_obj->exists()){
            $nursactplan_obj = $nursactplan_obj->first();
            
            $diagnosis_obj = $nursactplan_obj->diagnosis;
            $responce->diagnosis = $diagnosis_obj;
        }
        
        return json_encode($responce);
        
    }

    public function othersChart_chart(Request $request){
        
        $mrn = $request->mrn;
        $episno = $request->episno;
        if(!$mrn || !$episno){
            abort(404);
        }
        $tabtitle = $request->tabtitle;
        
        $pat_mast = DB::table('hisdb.pat_mast as pm')
                    // ->select('pm.MRN','pm.Name','e.ward as e_ward','e.bed as bednum','b.ward','ba.ward as ba_ward','ba.bednum as ba_bednum','n.diagnosis')
                    ->select('pm.MRN','pm.Name','e.bed as bednum','b.ward','n.diagnosis as n_diagnosis','na.diagnosis')
                    ->leftJoin('hisdb.episode as e', function ($join){
                        $join = $join->on('e.mrn','=','pm.MRN')
                                    ->on('e.episno','=','pm.Episno')
                                    ->where('e.compcode','=',session('compcode'));
                    })
                    ->leftJoin('hisdb.bed as b', function ($join){
                        $join = $join->on('b.bednum','=','e.bed')
                                    // ->on('b.episno','=','pm.Episno')
                                    ->where('b.compcode','=',session('compcode'));
                    })
                    // ->leftJoin('hisdb.bedalloc as ba', function ($join){
                    //     $join = $join->on('ba.mrn','=','pm.MRN')
                    //                 ->on('ba.episno','=','pm.Episno')
                    //                 ->where('ba.compcode','=',session('compcode'));
                    // })
                    ->leftJoin('nursing.nursassessment as n', function ($join){
                        $join = $join->on('n.mrn','=','pm.MRN')
                                    ->on('n.episno','=','pm.Episno')
                                    ->where('n.compcode','=',session('compcode'));
                    })
                    ->leftJoin('nursing.nursactplan_hdr as na', function ($join){
                        $join = $join->on('na.mrn','=','pm.MRN')
                                    ->on('na.episno','=','pm.Episno')
                                    ->where('na.compcode','=',session('compcode'));
                    })
                    ->where('pm.CompCode','=',session('compcode'))
                    ->where('pm.MRN','=',$mrn)
                    // ->where('pm.Episno','=',$episno)
                    ->first();
        
        // $pat_mast = DB::table('hisdb.pat_mast as pm')
        //             ->select('pm.MRN','pm.Name','b.ward','b.bednum','n.diagnosis')
        //             ->leftJoin('hisdb.bedalloc as b', function ($join){
        //                 $join = $join->on('b.mrn','=','pm.MRN')
        //                             ->on('b.episno','=','pm.Episno')
        //                             ->where('b.compcode','=',session('compcode'));
        //             })
        //             ->leftJoin('nursing.nursassessment as n', function ($join){
        //                 $join = $join->on('n.mrn','=','pm.MRN')
        //                             ->on('n.episno','=','pm.Episno')
        //                             ->where('n.compcode','=',session('compcode'));
        //             })
        //             ->where('pm.CompCode','=',session('compcode'))
        //             ->where('pm.MRN','=',$mrn)
        //             ->where('pm.Episno','=',$episno)
        //             ->first();
        
        $nurs_othershdr = DB::table('nursing.nurs_othershdr')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$mrn)
                        ->where('episno','=',$episno)
                        ->where('tabtitle','=',$tabtitle)
                        ->first();
        
        $nurs_othersdtl = DB::table('nursing.nurs_othersdtl')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$mrn)
                        ->where('episno','=',$episno)
                        ->where('tabtitle','=',$tabtitle)
                        ->get();
        // dd($tabtitle);
        
        return view('hisdb.nursingnote_MR.othersChart_chart_pdfmake', compact('tabtitle','pat_mast','nurs_othershdr','nurs_othersdtl'));
        
    }

    //////////////////////////////////////////BLADDER IRRIGATION//////////////////////////////////////////

    public function get_table_bladder1(Request $request){
        
        $bladder_obj = DB::table('nursing.nurs_bladder')
                            ->where('compcode','=',session('compcode'))
                            // ->where('idno','=',$request->idno);
                            ->where('mrn','=',$request->mrn_nursNote)
                            ->where('episno','=',$request->episno_nursNote)
                            ->where('shift','=',$request->firstShift);
        
        $responce = new stdClass();
        
        if($bladder_obj->exists()){
            $total_input1 = $bladder_obj->sum('input');
            $responce->total_input1 = $total_input1;

            $total_output1 = $bladder_obj->sum('output');
            $responce->total_output1 = $total_output1;
            
            $bladder_obj = $bladder_obj->first();
            $responce->bladder = $bladder_obj;
        }
        
        return json_encode($responce);
        
    }

    public function get_table_bladder2(Request $request){
        
        $bladder_obj = DB::table('nursing.nurs_bladder')
                            ->where('compcode','=',session('compcode'))
                            // ->where('idno','=',$request->idno);
                            ->where('mrn','=',$request->mrn_nursNote)
                            ->where('episno','=',$request->episno_nursNote)
                            ->where('shift','=',$request->secondShift);
        
        $responce = new stdClass();
        
        if($bladder_obj->exists()){
            $total_input2 = $bladder_obj->sum('input');
            $responce->total_input2 = $total_input2;

            $total_output2 = $bladder_obj->sum('output');
            $responce->total_output2 = $total_output2;
            
            $bladder_obj = $bladder_obj->first();
            $responce->bladder = $bladder_obj;
        }
        
        return json_encode($responce);
        
    }

    public function get_table_bladder3(Request $request){
        
        $bladder_obj = DB::table('nursing.nurs_bladder')
                            ->where('compcode','=',session('compcode'))
                            // ->where('idno','=',$request->idno);
                            ->where('mrn','=',$request->mrn_nursNote)
                            ->where('episno','=',$request->episno_nursNote)
                            ->where('shift','=',$request->thirdShift);
        
        $responce = new stdClass();
        
        if($bladder_obj->exists()){
            $total_input3 = $bladder_obj->sum('input');
            $responce->total_input3 = $total_input3;

            $total_output3 = $bladder_obj->sum('output');
            $responce->total_output3 = $total_output3;
            
            $bladder_obj = $bladder_obj->first();
            $responce->bladder = $bladder_obj;
        }
        
        return json_encode($responce);
        
    }

    public function bladder_chart(Request $request){
        
        $mrn = $request->mrn;
        $episno = $request->episno;
        if(!$mrn || !$episno){
            abort(404);
        }
        
        $pat_mast = DB::table('hisdb.pat_mast as pm')
                    ->select('pm.MRN','pm.Name','b.ward','b.bednum')
                    ->leftJoin('hisdb.bedalloc as b', function ($join){
                        $join = $join->on('b.mrn','=','pm.MRN')
                                    ->on('b.episno','=','pm.Episno')
                                    ->where('b.compcode','=',session('compcode'));
                    })
                    ->leftJoin('nursing.nursassessment as n', function ($join){
                        $join = $join->on('n.mrn','=','pm.MRN')
                                    ->on('n.episno','=','pm.Episno')
                                    ->where('n.compcode','=',session('compcode'));
                    })
                    ->where('pm.CompCode','=',session('compcode'))
                    ->where('pm.MRN','=',$mrn)
                    ->where('pm.Episno','=',$episno)
                    ->first();
        
        $bladder = DB::table('nursing.nurs_bladder')
                    ->select('mrn','episno','shift','entereddate','enteredtime','input','output','positive','negative','remarks','adduser','adddate','computerid')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$mrn)
                    ->where('episno','=',$episno)
                    ->get();
        
        return view('hisdb.nursingnote_MR.bladder_chart_pdfmake', compact('pat_mast','bladder'));
        
    }
    
}