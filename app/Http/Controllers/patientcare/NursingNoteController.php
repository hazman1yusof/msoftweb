<?php

namespace App\Http\Controllers\patientcare;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;
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
            case 'get_table_datetime': // Progress Note
                return $this->get_table_datetime($request);
            default:
                return 'error happen..';
        }
    }
    
    public function form(Request $request)
    {
        DB::enableQueryLog();
        
        switch($request->action){
            case 'save_table_progress':
                switch($request->oper){
                    case 'add':
                        return $this->add_progress($request);
                    case 'edit':
                        return $this->edit_progress($request);
                    default:
                        return 'error happen..';
                }
            case 'get_table_progress':
                return $this->get_table_progress($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function get_table_datetime(Request $request){
        
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
                // $date['timetaken'] = $value->timetaken;
                if(!empty($value->timetaken)){
                    $date['timetaken'] =  Carbon::createFromFormat('H:i:s', $value->timetaken)->format('h:i A');
                }else{
                    $date['timetaken'] =  '-';
                }
                $date['adduser'] = $value->adduser;
                $date['epistycode'] = $value->epistycode;
                
                array_push($data,$date);
            }
            
            $responce->data = $data;
        }else{
            $responce->data = [];
        }
        
        return json_encode($responce);
        
    }

    public function add_progress(Request $request){
        
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
                    'epistycode' => $request->epistycode,
                    'adduser'  => session('username'),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function edit_progress(Request $request){
        
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
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
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
                        'epistycode' => $request->epistycode,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
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
}