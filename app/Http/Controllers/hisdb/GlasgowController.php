<?php

namespace App\Http\Controllers\hisdb;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class GlasgowController extends defaultController
{
    
    var $table;
    var $duplicateCode;
    
    public function __construct(){
        $this->middleware('auth');
        // $this->duplicateCode = "chgtype";
    }
    
    public function show(Request $request){
        return view('hisdb.nursingnote.nursingnote');
    }
    
    public function table(Request $request)
    {
        switch($request->action){
            case 'get_table_datetimeGCS': // Glasgow
                return $this->get_table_datetimeGCS($request);

            default:
                return 'error happen..';
        }
    }
    
    public function form(Request $request){
        DB::enableQueryLog();
        switch($request->action){
            case 'save_table_glasgow':
                switch($request->oper){
                    case 'add':
                        return $this->add_glasgow($request);
                    case 'edit':
                        return $this->edit_glasgow($request);
                    default:
                        return 'error happen..';
                }
            
            case 'get_table_glasgow':
                return $this->get_table_glasgow($request);
                
            default:
                return 'error happen..';
        }

    }

    public function get_table_datetimeGCS(Request $request){
        
        $responce = new stdClass();
        
        $glasgow_obj = DB::table('nursing.glasgowcomascale')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno);
        
        if($glasgow_obj->exists()){
            $glasgow_obj = $glasgow_obj->get();
            
            $data = [];
            
            foreach($glasgow_obj as $key => $value){
                if(!empty($value->gcs_date)){
                    $date['gcs_date'] =  Carbon::createFromFormat('Y-m-d', $value->gcs_date)->format('d-m-Y');
                }else{
                    $date['gcs_date'] =  '-';
                }
                $date['idno'] = $value->idno;
                $date['mrn'] = $value->mrn;
                $date['episno'] = $value->episno;
                // $date['gcs_time'] = $value->gcs_time;
                if(!empty($value->gcs_time)){
                    $date['gcs_time'] =  Carbon::createFromFormat('H:i:s', $value->gcs_time)->format('h:i A');
                }else{
                    $date['gcs_time'] =  '-';
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

    public function add_glasgow(Request $request){
        
        DB::beginTransaction();

        try {
            
            DB::table('nursing.glasgowcomascale')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn_nursNote,
                    'episno' => $request->episno_nursNote,
                    'gcs_date' => $request->gcs_date,
                    'gcs_time' => $request->gcs_time,
                    'gcsEye' => $request->gcsEye,
                    'gcsVerbal' => $request->gcsVerbal,
                    'gcsMotor' => $request->gcsMotor,
                    'gcs_hr' => $request->gcs_hr,
                    'gcs_rr' => $request->gcs_rr,
                    'gcs_bp_sys1' => $request->gcs_bp_sys1,
                    'gcs_bp_dias2' => $request->gcs_bp_dias2,
                    'gcs_temp' => $request->gcs_temp,
                    'gcs_pupilSize_R' => $request->gcs_pupilSize_R,
                    'gcs_pupilReact_R' => $request->gcs_pupilReact_R,
                    'gcs_pupilSize_L' => $request->gcs_pupilSize_L,
                    'gcs_pupilReact_L' => $request->gcs_pupilReact_L,
                    'gcs_armNormal_R' => $request->gcs_armNormal_R,
                    'gcs_armWeak_R' => $request->gcs_armWeak_R,
                    'gcs_armVeryweak_R' => $request->gcs_armVeryweak_R,
                    'gcs_armSpastic_R' => $request->gcs_armSpastic_R,
                    'gcs_armExtension_R' => $request->gcs_armExtension_R,
                    'gcs_armNoreaction_R' => $request->gcs_armNoreaction_R,
                    'gcs_armNormal_L' => $request->gcs_armNormal_L,
                    'gcs_armWeak_L' => $request->gcs_armWeak_L,
                    'gcs_armVeryweak_L' => $request->gcs_armVeryweak_L,
                    'gcs_armSpastic_L' => $request->gcs_armSpastic_L,
                    'gcs_armExtension_L' => $request->gcs_armExtension_L,
                    'gcs_armNoreaction_L' => $request->gcs_armNoreaction_L,
                    'gcs_legNormal_R' => $request->gcs_legNormal_R,
                    'gcs_legWeak_R' => $request->gcs_legWeak_R,
                    'gcs_legVeryweak_R' => $request->gcs_legVeryweak_R,
                    'gcs_legExtension_R' => $request->gcs_legExtension_R,
                    'gcs_legNoreaction_R' => $request->gcs_legNoreaction_R,
                    'gcs_legNormal_L' => $request->gcs_legNormal_L,
                    'gcs_legWeak_L' => $request->gcs_legWeak_L,
                    'gcs_legVeryweak_L' => $request->gcs_legVeryweak_L,
                    'gcs_legExtension_L' => $request->gcs_legExtension_L,
                    'gcs_legNoreaction_L' => $request->gcs_legNoreaction_L,
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
    
    public function edit_glasgow(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            if(!empty($request->idno_glasgow)){
                DB::table('nursing.glasgowcomascale')
                    ->where('idno','=',$request->idno_glasgow)
                    // ->where('mrn','=',$request->mrn_nursNote)
                    // ->where('episno','=',$request->episno_nursNote)
                    ->update([
                        'gcs_date' => $request->gcs_date,
                        'gcs_time' => $request->gcs_time,
                        'gcsEye' => $request->gcsEye,
                        'gcsVerbal' => $request->gcsVerbal,
                        'gcsMotor' => $request->gcsMotor,
                        'gcs_hr' => $request->gcs_hr,
                        'gcs_rr' => $request->gcs_rr,
                        'gcs_bp_sys1' => $request->gcs_bp_sys1,
                        'gcs_bp_dias2' => $request->gcs_bp_dias2,
                        'gcs_temp' => $request->gcs_temp,
                        'gcs_pupilSize_R' => $request->gcs_pupilSize_R,
                        'gcs_pupilReact_R' => $request->gcs_pupilReact_R,
                        'gcs_pupilSize_L' => $request->gcs_pupilSize_L,
                        'gcs_pupilReact_L' => $request->gcs_pupilReact_L,
                        'gcs_armNormal_R' => $request->gcs_armNormal_R,
                        'gcs_armWeak_R' => $request->gcs_armWeak_R,
                        'gcs_armVeryweak_R' => $request->gcs_armVeryweak_R,
                        'gcs_armSpastic_R' => $request->gcs_armSpastic_R,
                        'gcs_armExtension_R' => $request->gcs_armExtension_R,
                        'gcs_armNoreaction_R' => $request->gcs_armNoreaction_R,
                        'gcs_armNormal_L' => $request->gcs_armNormal_L,
                        'gcs_armWeak_L' => $request->gcs_armWeak_L,
                        'gcs_armVeryweak_L' => $request->gcs_armVeryweak_L,
                        'gcs_armSpastic_L' => $request->gcs_armSpastic_L,
                        'gcs_armExtension_L' => $request->gcs_armExtension_L,
                        'gcs_armNoreaction_L' => $request->gcs_armNoreaction_L,
                        'gcs_legNormal_R' => $request->gcs_legNormal_R,
                        'gcs_legWeak_R' => $request->gcs_legWeak_R,
                        'gcs_legVeryweak_R' => $request->gcs_legVeryweak_R,
                        'gcs_legExtension_R' => $request->gcs_legExtension_R,
                        'gcs_legNoreaction_R' => $request->gcs_legNoreaction_R,
                        'gcs_legNormal_L' => $request->gcs_legNormal_L,
                        'gcs_legWeak_L' => $request->gcs_legWeak_L,
                        'gcs_legVeryweak_L' => $request->gcs_legVeryweak_L,
                        'gcs_legExtension_L' => $request->gcs_legExtension_L,
                        'gcs_legNoreaction_L' => $request->gcs_legNoreaction_L,
                        'upduser'  => session('username'),
                        'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }else{
                DB::table('nursing.glasgowcomascale')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_nursNote,
                        'episno' => $request->episno_nursNote,
                        'gcs_date' => $request->gcs_date,
                        'gcs_time' => $request->gcs_time,
                        'gcsEye' => $request->gcsEye,
                        'gcsVerbal' => $request->gcsVerbal,
                        'gcsMotor' => $request->gcsMotor,
                        'gcs_hr' => $request->gcs_hr,
                        'gcs_rr' => $request->gcs_rr,
                        'gcs_bp_sys1' => $request->gcs_bp_sys1,
                        'gcs_bp_dias2' => $request->gcs_bp_dias2,
                        'gcs_temp' => $request->gcs_temp,
                        'gcs_pupilSize_R' => $request->gcs_pupilSize_R,
                        'gcs_pupilReact_R' => $request->gcs_pupilReact_R,
                        'gcs_pupilSize_L' => $request->gcs_pupilSize_L,
                        'gcs_pupilReact_L' => $request->gcs_pupilReact_L,
                        'gcs_armNormal_R' => $request->gcs_armNormal_R,
                        'gcs_armWeak_R' => $request->gcs_armWeak_R,
                        'gcs_armVeryweak_R' => $request->gcs_armVeryweak_R,
                        'gcs_armSpastic_R' => $request->gcs_armSpastic_R,
                        'gcs_armExtension_R' => $request->gcs_armExtension_R,
                        'gcs_armNoreaction_R' => $request->gcs_armNoreaction_R,
                        'gcs_armNormal_L' => $request->gcs_armNormal_L,
                        'gcs_armWeak_L' => $request->gcs_armWeak_L,
                        'gcs_armVeryweak_L' => $request->gcs_armVeryweak_L,
                        'gcs_armSpastic_L' => $request->gcs_armSpastic_L,
                        'gcs_armExtension_L' => $request->gcs_armExtension_L,
                        'gcs_armNoreaction_L' => $request->gcs_armNoreaction_L,
                        'gcs_legNormal_R' => $request->gcs_legNormal_R,
                        'gcs_legWeak_R' => $request->gcs_legWeak_R,
                        'gcs_legVeryweak_R' => $request->gcs_legVeryweak_R,
                        'gcs_legExtension_R' => $request->gcs_legExtension_R,
                        'gcs_legNoreaction_R' => $request->gcs_legNoreaction_R,
                        'gcs_legNormal_L' => $request->gcs_legNormal_L,
                        'gcs_legWeak_L' => $request->gcs_legWeak_L,
                        'gcs_legVeryweak_L' => $request->gcs_legVeryweak_L,
                        'gcs_legExtension_L' => $request->gcs_legExtension_L,
                        'gcs_legNoreaction_L' => $request->gcs_legNoreaction_L,
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

    public function get_table_glasgow (Request $request){
        
        $glasgow_obj = DB::table('nursing.glasgowcomascale')
                            ->where('compcode','=',session('compcode'))
                            ->where('idno','=',$request->idno);
                            // ->where('mrn','=',$request->mrn)
                            // ->where('episno','=',$request->episno);
        
        $responce = new stdClass();
        
        if($glasgow_obj->exists()){
            $glasgow_obj = $glasgow_obj->first();
            $date = Carbon::createFromFormat('Y-m-d', $glasgow_obj->gcs_date)->format('Y-m-d');
            
            $responce->glasgow = $glasgow_obj;
            $responce->date = $date;
        }
        
        return json_encode($responce);
        
    }

     public function glasgow_chart(Request $request){
        
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
        
        return view('hisdb.nursingnote.bladder_chart_pdfmake', compact('pat_mast','bladder'));
        
    }
    
    
}