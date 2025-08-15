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
use Storage;

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
    
    public function posturalassessment_chart(Request $request){
        
        $mrn = $request->mrn;
        $episno = $request->episno;
        $entereddate = $request->entereddate;
        $type1 = $request->type1;
        $type2 = $request->type2;
        if(!$mrn || !$episno || !$entereddate){
            abort(404);
        }
        
        $posturalassessment = DB::table('hisdb.phy_posturalassessment as pa')
                            ->select('pa.idno','pa.compcode','pa.mrn','pa.episno','pa.entereddate','pa.FACToeOutL','pa.FACToeOutR','pa.FACToeInL','pa.FACToeInR','pa.FACPronationL','pa.FACPronationR','pa.FACFlatFeetL','pa.FACFlatFeetR','pa.FACHighArchL','pa.FACHighArchR','pa.KHKnockKneesL','pa.KHKnockKneesR','pa.KHBowLegsL','pa.KHBowLegsR','pa.spineScoliosisL','pa.spineScoliosisR','pa.scapulaDeviationL','pa.scapulaDeviationR','pa.shoulderDeviationL','pa.shoulderDeviationR','pa.headTiltL','pa.headTiltR','pa.headRotateL','pa.headRotateR','pa.anteriorPosteriorRmk','pa.ankleDorsiflexL','pa.ankleDorsiflexR','pa.anklePlantarL','pa.anklePlantarR','pa.kneeFlexedL','pa.kneeFlexedR','pa.kneeHyperextendL','pa.kneeHyperextendR','pa.pelvisAnterTransL','pa.pelvisAnterTransR','pa.devSymmetry','pa.tiltAnterior','pa.tiltPosterior','pa.LSLordosis','pa.LSFlat','pa.TSKyphosis','pa.TSFlat','pa.trunkRotation','pa.shoulderForward','pa.HPForward','pa.HPBack','pa.lateralRmk','pa.adduser','pa.adddate','pa.upduser','pa.upddate','pa.lastuser','pa.lastupdate','pa.computerid','pm.Name','pm.Newic','pm.Sex')
                            ->leftjoin('hisdb.pat_mast as pm', function ($join){
                                $join = $join->on('pm.MRN','=','pa.mrn');
                                $join = $join->on('pm.Episno','=','pa.episno');
                                $join = $join->where('pm.compcode','=',session('compcode'));
                            })
                            ->where('pa.compcode','=',session('compcode'))
                            ->where('pa.mrn','=',$mrn)
                            ->where('pa.episno','=',$episno)
                            ->where('pa.entereddate','=',$entereddate)
                            ->first();
        // dd($posturalassessment);
        
        $company = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();
        
        $attachment_files1 = $this->get_attachment_files1($mrn,$episno,$entereddate,$type1);
        $attachment_files2 = $this->get_attachment_files2($mrn,$episno,$entereddate,$type2);
        // dd($attachment_files);
        
        return view('rehab.posturalAssessmentChart_pdfmake',compact('posturalassessment','company','attachment_files1','attachment_files2'));
        
    }
    
    public function get_attachment_files1($mrn,$episno,$entereddate,$type1){
        
        $mrn = $mrn;
        $episno = $episno;
        $entereddate = $entereddate;
        $type = $type1;
        
        // $foxitpath1 = "C:\Program Files (x86)\Foxit Software\Foxit PDF Reader\FoxitPDFReader.exe";
        // $foxitpath2 = "C:\Program Files (x86)\Foxit Software\Foxit Reader\FoxitReader.exe";
        
        // $foxitpath = "C:\laragon\www\pdf\open.bat  > /dev/null";
        $filename = $type."_".$mrn."_".$episno."_".$entereddate.".pdf";
        $blankpath = 'blank/'.$type.'.pdf';
        $filepath = public_path().'/uploads/ftp/'.$filename;
        $ftppath = "/patientcare_upload/pdf/".$filename;
        
        $exists = Storage::disk('ftp')->exists($ftppath);
        
        if($exists){
            $file = Storage::disk('ftp')->get($ftppath);
            Storage::disk('ftp_uploads')->put($filename, $file);
            
            return '../uploads/ftp/'.$filename;
            
            // exec('start /B "" "C:\Program Files (x86)\Foxit Software\Foxit PDF Reader\FoxitPDFReader.exe" '.$filepath);
            
            // $localfile = Storage::disk('ftp_uploads')->get($filename);
            // Storage::disk('ftp')->put($ftppath, $localfile);
        }else{
            // $blankfile = Storage::disk('ftp_uploads')->get($blankpath);
            // Storage::disk('ftp_uploads')->put($filename, $blankfile);
            
            return '';
            
            // exec('start /B "" "C:\Program Files (x86)\Foxit Software\Foxit PDF Reader\FoxitPDFReader.exe" '.$filepath);
            
            // $localfile = Storage::disk('ftp_uploads')->get($filename);
            // Storage::disk('ftp')->put($ftppath, $localfile);
        }
        
    }
    
    public function get_attachment_files2($mrn,$episno,$entereddate,$type2){
        
        $mrn = $mrn;
        $episno = $episno;
        $entereddate = $entereddate;
        $type = $type2;
        
        // $foxitpath1 = "C:\Program Files (x86)\Foxit Software\Foxit PDF Reader\FoxitPDFReader.exe";
        // $foxitpath2 = "C:\Program Files (x86)\Foxit Software\Foxit Reader\FoxitReader.exe";
        
        // $foxitpath = "C:\laragon\www\pdf\open.bat  > /dev/null";
        $filename = $type."_".$mrn."_".$episno."_".$entereddate.".pdf";
        $blankpath = 'blank/'.$type.'.pdf';
        $filepath = public_path().'/uploads/ftp/'.$filename;
        $ftppath = "/patientcare_upload/pdf/".$filename;
        
        $exists = Storage::disk('ftp')->exists($ftppath);
        
        if($exists){
            $file = Storage::disk('ftp')->get($ftppath);
            Storage::disk('ftp_uploads')->put($filename, $file);
            
            return '../uploads/ftp/'.$filename;
            
            // exec('start /B "" "C:\Program Files (x86)\Foxit Software\Foxit PDF Reader\FoxitPDFReader.exe" '.$filepath);
            
            // $localfile = Storage::disk('ftp_uploads')->get($filename);
            // Storage::disk('ftp')->put($ftppath, $localfile);
        }else{
            // $blankfile = Storage::disk('ftp_uploads')->get($blankpath);
            // Storage::disk('ftp_uploads')->put($filename, $blankfile);
            
            return '';
            
            // exec('start /B "" "C:\Program Files (x86)\Foxit Software\Foxit PDF Reader\FoxitPDFReader.exe" '.$filepath);
            
            // $localfile = Storage::disk('ftp_uploads')->get($filename);
            // Storage::disk('ftp')->put($ftppath, $localfile);
        }
        
    }
    
}