<?php

namespace App\Http\Controllers\rehab;

use Illuminate\Http\Request;
use stdClass;
use App\User;
use DB;
use Carbon\Carbon;
use Auth;
use Session;
use App\Http\Controllers\defaultController;

class PosturalAssessmentController extends defaultController
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function show(Request $request)
    {
        return view('rehab.posturalAssessment');
    }
    
    public function form(Request $request)
    {
        DB::enableQueryLog();
        switch($request->action){
            case 'save_table_posturalAssessment':
                switch($request->oper){
                    case 'add':
                        return $this->add_posturalAssessment($request);
                    case 'edit':
                        return $this->edit_posturalAssessment($request);
                    default:
                        return 'error happen..';
                }
            
            case 'get_table_posturalAssessment':
                return $this->get_table_posturalAssessment($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function table(Request $request)
    {
        switch($request->action){
            case 'get_datetime_posturalAssessment':
                return $this->get_datetime_posturalAssessment($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function add_posturalAssessment(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $posturalassessment = DB::table('hisdb.phy_posturalassessment')
                                ->where('compcode','=',session('compcode'))
                                ->where('mrn','=',$request->mrn)
                                ->where('episno','=',$request->episno)
                                ->where('entereddate','=',$request->entereddate);
            
            if($posturalassessment->exists()){
                // throw new \Exception('Date already exist.', 500);
                return response('Date already exist.');
            }
            
            DB::table('hisdb.phy_posturalassessment')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn,
                    'episno' => $request->episno,
                    'entereddate' => $request->entereddate,
                    'FACToeOutL' => $request->FACToeOutL,
                    'FACToeOutR' => $request->FACToeOutR,
                    'FACToeInL' => $request->FACToeInL,
                    'FACToeInR' => $request->FACToeInR,
                    'FACPronationL' => $request->FACPronationL,
                    'FACPronationR' => $request->FACPronationR,
                    'FACFlatFeetL' => $request->FACFlatFeetL,
                    'FACFlatFeetR' => $request->FACFlatFeetR,
                    'FACHighArchL' => $request->FACHighArchL,
                    'FACHighArchR' => $request->FACHighArchR,
                    'KHKnockKneesL' => $request->KHKnockKneesL,
                    'KHKnockKneesR' => $request->KHKnockKneesR,
                    'KHBowLegsL' => $request->KHBowLegsL,
                    'KHBowLegsR' => $request->KHBowLegsR,
                    'spineScoliosisL' => $request->spineScoliosisL,
                    'spineScoliosisR' => $request->spineScoliosisR,
                    'scapulaDeviationL' => $request->scapulaDeviationL,
                    'scapulaDeviationR' => $request->scapulaDeviationR,
                    'shoulderDeviationL' => $request->shoulderDeviationL,
                    'shoulderDeviationR' => $request->shoulderDeviationR,
                    'headTiltL' => $request->headTiltL,
                    'headTiltR' => $request->headTiltR,
                    'headRotateL' => $request->headRotateL,
                    'headRotateR' => $request->headRotateR,
                    'anteriorPosteriorRmk' => $request->anteriorPosteriorRmk,
                    'ankleDorsiflexL' => $request->ankleDorsiflexL,
                    'ankleDorsiflexR' => $request->ankleDorsiflexR,
                    'anklePlantarL' => $request->anklePlantarL,
                    'anklePlantarR' => $request->anklePlantarR,
                    'kneeFlexedL' => $request->kneeFlexedL,
                    'kneeFlexedR' => $request->kneeFlexedR,
                    'kneeHyperextendL' => $request->kneeHyperextendL,
                    'kneeHyperextendR' => $request->kneeHyperextendR,
                    'pelvisAnterTransL' => $request->pelvisAnterTransL,
                    'pelvisAnterTransR' => $request->pelvisAnterTransR,
                    'devSymmetry' => $request->devSymmetry,
                    'tiltAnterior' => $request->tiltAnterior,
                    'tiltPosterior' => $request->tiltPosterior,
                    'LSLordosis' => $request->LSLordosis,
                    'LSFlat' => $request->LSFlat,
                    'TSKyphosis' => $request->TSKyphosis,
                    'TSFlat' => $request->TSFlat,
                    'trunkRotation' => $request->trunkRotation,
                    'shoulderForward' => $request->shoulderForward,
                    'HPForward' => $request->HPForward,
                    'HPBack' => $request->HPBack,
                    'lateralRmk' => $request->lateralRmk,
                    'adduser'  => session('username'),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'computerid' => session('computerid'),
                ]);
            
            DB::commit();
            
            $responce = new stdClass();
            
            return json_encode($responce);
        
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function edit_posturalAssessment(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $posturalassessment = DB::table('hisdb.phy_posturalassessment')
                                ->where('compcode','=',session('compcode'))
                                ->where('mrn','=',$request->mrn)
                                ->where('episno','=',$request->episno)
                                ->where('entereddate','=',$request->entereddate);
            
            if(!empty($request->idno_posturalAssessment)){
                if($posturalassessment->exists()){
                    if($posturalassessment->first()->idno != $request->idno_posturalAssessment){
                        // throw new \Exception('Date already exist.', 500);
                        return response('Date already exist.');
                    }
                }
                
                DB::table('hisdb.phy_posturalassessment')
                    ->where('idno','=',$request->idno_posturalAssessment)
                    // ->where('mrn','=',$request->mrn)
                    // ->where('episno','=',$request->episno)
                    // ->where('compcode','=',session('compcode'))
                    ->update([
                        'entereddate' => $request->entereddate,
                        'FACToeOutL' => $request->FACToeOutL,
                        'FACToeOutR' => $request->FACToeOutR,
                        'FACToeInL' => $request->FACToeInL,
                        'FACToeInR' => $request->FACToeInR,
                        'FACPronationL' => $request->FACPronationL,
                        'FACPronationR' => $request->FACPronationR,
                        'FACFlatFeetL' => $request->FACFlatFeetL,
                        'FACFlatFeetR' => $request->FACFlatFeetR,
                        'FACHighArchL' => $request->FACHighArchL,
                        'FACHighArchR' => $request->FACHighArchR,
                        'KHKnockKneesL' => $request->KHKnockKneesL,
                        'KHKnockKneesR' => $request->KHKnockKneesR,
                        'KHBowLegsL' => $request->KHBowLegsL,
                        'KHBowLegsR' => $request->KHBowLegsR,
                        'spineScoliosisL' => $request->spineScoliosisL,
                        'spineScoliosisR' => $request->spineScoliosisR,
                        'scapulaDeviationL' => $request->scapulaDeviationL,
                        'scapulaDeviationR' => $request->scapulaDeviationR,
                        'shoulderDeviationL' => $request->shoulderDeviationL,
                        'shoulderDeviationR' => $request->shoulderDeviationR,
                        'headTiltL' => $request->headTiltL,
                        'headTiltR' => $request->headTiltR,
                        'headRotateL' => $request->headRotateL,
                        'headRotateR' => $request->headRotateR,
                        'anteriorPosteriorRmk' => $request->anteriorPosteriorRmk,
                        'ankleDorsiflexL' => $request->ankleDorsiflexL,
                        'ankleDorsiflexR' => $request->ankleDorsiflexR,
                        'anklePlantarL' => $request->anklePlantarL,
                        'anklePlantarR' => $request->anklePlantarR,
                        'kneeFlexedL' => $request->kneeFlexedL,
                        'kneeFlexedR' => $request->kneeFlexedR,
                        'kneeHyperextendL' => $request->kneeHyperextendL,
                        'kneeHyperextendR' => $request->kneeHyperextendR,
                        'pelvisAnterTransL' => $request->pelvisAnterTransL,
                        'pelvisAnterTransR' => $request->pelvisAnterTransR,
                        'devSymmetry' => $request->devSymmetry,
                        'tiltAnterior' => $request->tiltAnterior,
                        'tiltPosterior' => $request->tiltPosterior,
                        'LSLordosis' => $request->LSLordosis,
                        'LSFlat' => $request->LSFlat,
                        'TSKyphosis' => $request->TSKyphosis,
                        'TSFlat' => $request->TSFlat,
                        'trunkRotation' => $request->trunkRotation,
                        'shoulderForward' => $request->shoulderForward,
                        'HPForward' => $request->HPForward,
                        'HPBack' => $request->HPBack,
                        'lateralRmk' => $request->lateralRmk,
                        'upduser'  => session('username'),
                        'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
            }else{
                if($posturalassessment->exists()){
                    // throw new \Exception('Date already exist.', 500);
                    return response('Date already exist.');
                }
                
                DB::table('hisdb.phy_posturalassessment')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'entereddate' => $request->entereddate,
                        'FACToeOutL' => $request->FACToeOutL,
                        'FACToeOutR' => $request->FACToeOutR,
                        'FACToeInL' => $request->FACToeInL,
                        'FACToeInR' => $request->FACToeInR,
                        'FACPronationL' => $request->FACPronationL,
                        'FACPronationR' => $request->FACPronationR,
                        'FACFlatFeetL' => $request->FACFlatFeetL,
                        'FACFlatFeetR' => $request->FACFlatFeetR,
                        'FACHighArchL' => $request->FACHighArchL,
                        'FACHighArchR' => $request->FACHighArchR,
                        'KHKnockKneesL' => $request->KHKnockKneesL,
                        'KHKnockKneesR' => $request->KHKnockKneesR,
                        'KHBowLegsL' => $request->KHBowLegsL,
                        'KHBowLegsR' => $request->KHBowLegsR,
                        'spineScoliosisL' => $request->spineScoliosisL,
                        'spineScoliosisR' => $request->spineScoliosisR,
                        'scapulaDeviationL' => $request->scapulaDeviationL,
                        'scapulaDeviationR' => $request->scapulaDeviationR,
                        'shoulderDeviationL' => $request->shoulderDeviationL,
                        'shoulderDeviationR' => $request->shoulderDeviationR,
                        'headTiltL' => $request->headTiltL,
                        'headTiltR' => $request->headTiltR,
                        'headRotateL' => $request->headRotateL,
                        'headRotateR' => $request->headRotateR,
                        'anteriorPosteriorRmk' => $request->anteriorPosteriorRmk,
                        'ankleDorsiflexL' => $request->ankleDorsiflexL,
                        'ankleDorsiflexR' => $request->ankleDorsiflexR,
                        'anklePlantarL' => $request->anklePlantarL,
                        'anklePlantarR' => $request->anklePlantarR,
                        'kneeFlexedL' => $request->kneeFlexedL,
                        'kneeFlexedR' => $request->kneeFlexedR,
                        'kneeHyperextendL' => $request->kneeHyperextendL,
                        'kneeHyperextendR' => $request->kneeHyperextendR,
                        'pelvisAnterTransL' => $request->pelvisAnterTransL,
                        'pelvisAnterTransR' => $request->pelvisAnterTransR,
                        'devSymmetry' => $request->devSymmetry,
                        'tiltAnterior' => $request->tiltAnterior,
                        'tiltPosterior' => $request->tiltPosterior,
                        'LSLordosis' => $request->LSLordosis,
                        'LSFlat' => $request->LSFlat,
                        'TSKyphosis' => $request->TSKyphosis,
                        'TSFlat' => $request->TSFlat,
                        'trunkRotation' => $request->trunkRotation,
                        'shoulderForward' => $request->shoulderForward,
                        'HPForward' => $request->HPForward,
                        'HPBack' => $request->HPBack,
                        'lateralRmk' => $request->lateralRmk,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
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
    
    public function get_table_posturalAssessment(Request $request){
        
        $posturalassessment_obj = DB::table('hisdb.phy_posturalassessment')
                                ->where('compcode','=',session('compcode'))
                                ->where('idno','=',$request->idno);
                                // ->where('mrn','=',$request->mrn)
                                // ->where('episno','=',$request->episno);
        
        $responce = new stdClass();
        
        if($posturalassessment_obj->exists()){
            $posturalassessment_obj = $posturalassessment_obj->first();
            $responce->posturalassessment = $posturalassessment_obj;
        }
        
        return json_encode($responce);
        
    }
    
    public function get_datetime_posturalAssessment(Request $request){
        
        $responce = new stdClass();
        
        $posturalassessment_obj = DB::table('hisdb.phy_posturalassessment')
                                ->where('compcode','=',session('compcode'))
                                ->where('mrn','=',$request->mrn)
                                ->where('episno','=',$request->episno);
        
        if($posturalassessment_obj->exists()){
            $posturalassessment_obj = $posturalassessment_obj->get();
            
            $data = [];
            
            foreach($posturalassessment_obj as $key => $value){
                $date['idno'] = $value->idno;
                $date['mrn'] = $value->mrn;
                $date['episno'] = $value->episno;
                if(!empty($value->entereddate)){
                    $date['entereddate'] =  Carbon::createFromFormat('Y-m-d', $value->entereddate)->format('d-m-Y');
                }else{
                    $date['entereddate'] =  '-';
                }
                $date['dt'] = $value->entereddate; // for sorting
                $date['adduser'] = $value->adduser;
                
                array_push($data,$date);
            }
            
            $responce->data = $data;
        }else{
            $responce->data = [];
        }
        
        return json_encode($responce);
        
    }
    
}