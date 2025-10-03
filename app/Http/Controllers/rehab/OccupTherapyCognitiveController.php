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

class OccupTherapyCognitiveController extends defaultController
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function show(Request $request)
    {
        return view('rehab.occupTherapy.occupTherapy_cognitive');
    }

    public function table(Request $request)
    {
        switch($request->action){
            case 'get_table_datetimeMMSE': // MMSE
                return $this->get_table_datetimeMMSE($request);

            case 'get_table_datetimeMOCA': // MOCA
                return $this->get_table_datetimeMOCA($request);

            case 'maintable':
                return $this->maintable($request);
            case 'ot_mmse_file':
                return $this->ot_mmse_file($request);
                
            default:
                return 'error happen..';
        }
    }
    
    public function form(Request $request)
    {
        DB::enableQueryLog();
        switch($request->action){
            case 'save_table_mmse':
                switch($request->oper){
                    case 'add':
                        return $this->add_mmse($request);
                    case 'edit':
                        return $this->edit_mmse($request);
                    default:
                        return 'error happen..';
                }
            
            case 'save_table_moca':
                switch($request->oper){
                    case 'add':
                        return $this->add_moca($request);
                    case 'edit':
                        return $this->edit_moca($request);
                    default:
                        return 'error happen..';
                }
            
            case 'get_table_mmse':
                return $this->get_table_mmse($request);
            
            case 'get_table_moca':
                return $this->get_table_moca($request);   

            case 'uploadfile':
                return $this->uploadfile($request);
            default:
                return 'error happen..';
        }
    }

    public function get_table_datetimeMMSE(Request $request){
        
        $responce = new stdClass();
        
        $mmse_obj = DB::table('hisdb.ot_mmse')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno);
        
        if($mmse_obj->exists()){
            $mmse_obj = $mmse_obj->get();
            
            $data = [];
            
            foreach($mmse_obj as $key => $value){
                if(!empty($value->dateofexam)){
                    $date['dateofexam'] =  Carbon::createFromFormat('Y-m-d', $value->dateofexam)->format('d-m-Y');
                }else{
                    $date['dateofexam'] =  '-';
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
    
    public function get_table_datetimeMOCA(Request $request){
        
        $responce = new stdClass();
        
        $moca_obj = DB::table('hisdb.ot_moca')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno);
        
        if($moca_obj->exists()){
            $moca_obj = $moca_obj->get();
            
            $data = [];
            
            foreach($moca_obj as $key => $value){
                if(!empty($value->dateAssessment)){
                    $date['dateAssessment'] =  Carbon::createFromFormat('Y-m-d', $value->dateAssessment)->format('d-m-Y');
                }else{
                    $date['dateAssessment'] =  '-';
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
    
    public function add_mmse(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('hisdb.ot_mmse')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'dateofexam' => $request->dateofexam,
                        'examiner' => strtoupper($request->examiner),
                        'orientation1' => $request->orientation1,
                        'orientation2' => $request->orientation2,
                        'registration' => $request->registration,
                        'registrationTrials' => $request->registrationTrials,
                        'attnCalc' => $request->attnCalc,
                        'recall' => $request->recall,
                        'language1' => $request->language1,
                        'language2' => $request->language2,
                        'language3' => $request->language3,
                        'language4' => $request->language4,
                        'language5' => $request->language5,
                        'language6' => $request->language6,
                        'tot_mmse' => $request->tot_mmse,
                        'assess_lvl' => strtoupper($request->assess_lvl),
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur"),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur"),
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
    
    public function edit_mmse(Request $request){
        
        DB::beginTransaction();
        
        try {

            $mmse = DB::table('hisdb.ot_mmse')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno)
                            ->where('dateofexam','=',$request->dateofexam);

            if(!empty($request->idno_mmse)){
                DB::table('hisdb.ot_mmse')
                    ->where('idno','=',$request->idno_mmse)
                    ->update([
                        'examiner' => strtoupper($request->examiner),
                        'orientation1' => $request->orientation1,
                        'orientation2' => $request->orientation2,
                        'registration' => $request->registration,
                        'registrationTrials' => $request->registrationTrials,
                        'attnCalc' => $request->attnCalc,
                        'recall' => $request->recall,
                        'language1' => $request->language1,
                        'language2' => $request->language2,
                        'language3' => $request->language3,
                        'language4' => $request->language4,
                        'language5' => $request->language5,
                        'language6' => $request->language6,
                        'tot_mmse' => $request->tot_mmse,
                        'assess_lvl' => strtoupper($request->assess_lvl),
                        'upduser'  => session('username'),
                        'upddate'  => Carbon::now("Asia/Kuala_Lumpur"),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur"),
                        'lastcomputerid' => session('computerid'),
                    ]);
            }else{

                if($mmse->exists()){
                    return response('Date already exist.');
                }

                DB::table('hisdb.ot_mmse')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'dateofexam' => $request->dateofexam,
                        'examiner' => strtoupper($request->examiner),
                        'orientation1' => $request->orientation1,
                        'orientation2' => $request->orientation2,
                        'registration' => $request->registration,
                        'registrationTrials' => $request->registrationTrials,
                        'attnCalc' => $request->attnCalc,
                        'recall' => $request->recall,
                        'language1' => $request->language1,
                        'language2' => $request->language2,
                        'language3' => $request->language3,
                        'language4' => $request->language4,
                        'language5' => $request->language5,
                        'language6' => $request->language6,
                        'tot_mmse' => $request->tot_mmse,
                        'assess_lvl' => strtoupper($request->assess_lvl),
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

    public function add_moca(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('hisdb.ot_moca')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'dateAssessment' => $request->dateAssessment,
                        'education' => strtoupper($request->education),
                        'visuospatial' => $request->visuospatial,
                        'naming' => $request->naming,
                        'attention1' => $request->attention1,
                        'attention2' => $request->attention2,
                        'attention3' => $request->attention3,
                        'languageRepeat' => $request->languageRepeat,
                        'languageFluency' => $request->languageFluency,
                        'abstraction' => $request->abstraction,
                        'delayed' => $request->delayed,
                        'orientation' => $request->orientation,
                        'tot_moca' => $request->tot_moca,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur"),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur"),
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
    
    public function edit_moca(Request $request){
        
        DB::beginTransaction();
        
        try {

            $moca = DB::table('hisdb.ot_moca')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno)
                            ->where('dateAssessment','=',$request->dateAssessment);
            
            if(!empty($request->idno_moca)){
                DB::table('hisdb.ot_moca')
                    ->where('idno','=',$request->idno_moca)
                    ->update([
                        'dateAssessment' => $request->dateAssessment,
                        'education' => strtoupper($request->education),
                        'visuospatial' => $request->visuospatial,
                        'naming' => $request->naming,
                        'attention1' => $request->attention1,
                        'attention2' => $request->attention2,
                        'attention3' => $request->attention3,
                        'languageRepeat' => $request->languageRepeat,
                        'languageFluency' => $request->languageFluency,
                        'abstraction' => $request->abstraction,
                        'delayed' => $request->delayed,
                        'orientation' => $request->orientation,
                        'tot_moca' => $request->tot_moca,
                        'upduser'  => session('username'),
                        'upddate'  => Carbon::now("Asia/Kuala_Lumpur"),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur"),
                        'computerid' => session('computerid'),
                    ]);
            }else{

                if($moca->exists()){
                    return response('Date already exist.');
                }

                DB::table('hisdb.ot_moca')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'dateAssessment' => $request->dateAssessment,
                        'education' => strtoupper($request->education),
                        'visuospatial' => $request->visuospatial,
                        'naming' => $request->naming,
                        'attention1' => $request->attention1,
                        'attention2' => $request->attention2,
                        'attention3' => $request->attention3,
                        'languageRepeat' => $request->languageRepeat,
                        'languageFluency' => $request->languageFluency,
                        'abstraction' => $request->abstraction,
                        'delayed' => $request->delayed,
                        'orientation' => $request->orientation,
                        'tot_moca' => $request->tot_moca,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur"),
                        'computerid' => session('computerid'),
                    ]);
            }
            $queries = DB::getQueryLog();
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function get_table_mmse(Request $request){
        
        $mmse_obj = DB::table('hisdb.ot_mmse')
                        ->where('compcode','=',session('compcode'))
                        ->where('idno','=',$request->idno);

        $responce = new stdClass();
        
        if($mmse_obj->exists()){
            $mmse_obj = $mmse_obj->first();
            $date = Carbon::createFromFormat('Y-m-d', $mmse_obj->dateofexam)->format('Y-m-d');

            $responce->mmse = $mmse_obj;
            $responce->date = $date;
        }
        
        return json_encode($responce);
        
    }
    
    public function get_table_moca(Request $request){
        
        $moca_obj = DB::table('hisdb.ot_moca')
                                ->where('compcode','=',session('compcode'))
                                ->where('idno','=',$request->idno);
        
        $responce = new stdClass();
        
        if($moca_obj->exists()){
            $moca_obj = $moca_obj->first();
            $date = Carbon::createFromFormat('Y-m-d', $moca_obj->dateAssessment)->format('Y-m-d');

            $responce->moca = $moca_obj;
            $responce->date = $date;
        }
        
        return json_encode($responce);
        
    }

    public function mmse_chart(Request $request){
        
        $mrn = $request->mrn;
        $episno = $request->episno;
        $dateofexam = $request->dateofexam;

        if(!$mrn || !$episno){
            abort(404);
        }
        
        $mmse = DB::table('hisdb.ot_mmse as m')
                ->select('m.idno','m.mrn','m.episno','m.dateofexam','m.examiner','m.orientation1','m.orientation2','m.registration','m.registrationTrials','m.attnCalc','m.recall','m.language1','m.language2','m.language3','m.language4','m.language5','m.language6','m.tot_mmse','m.assess_lvl','pm.Name','pm.Newic')
                ->leftjoin('hisdb.pat_mast as pm', function ($join){
                    $join = $join->on('pm.MRN','=','m.mrn');
                    // $join = $join->on('pm.Episno','=','m.episno');
                    $join = $join->where('pm.compcode','=',session('compcode'));
                })
                ->where('m.compcode','=',session('compcode'))
                ->where('m.mrn','=',$mrn)
                ->where('m.episno','=',$episno)
                ->where('m.dateofexam','=',$dateofexam)
                ->first();
        // dd($mmse);

        $company = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();

        $attachment_files = $this->get_attachment_files($mrn,$episno,$mmse->idno);
        // dd($attachment_files);
        
        return view('rehab.occupTherapy.mmseChart_pdfmake',compact('mmse','attachment_files'));
        
    }

    public function get_attachment_files($mrn,$episno,$idno_mmse){
        
        $attachment_files = DB::table('hisdb.ot_mmse_file')
            ->where('compcode','=',session('compcode'))
            ->where('mrn','=',$mrn)
            ->where('episno','=',$episno)
            ->where('idno_mmse','=',$idno_mmse)
            ->get();

        return $attachment_files;
        
    }

    public function moca_chart(Request $request){
        
        $mrn = $request->mrn;
        $episno = $request->episno;
        $dateAssessment = $request->dateAssessment;

        if(!$mrn || !$episno){
            abort(404);
        }
        
        $moca = DB::table('hisdb.ot_moca as c')
                ->select('c.mrn','c.episno','c.dateAssessment','c.education','c.visuospatial','c.naming','c.attention1','c.attention2','c.attention3','c.languageRepeat','c.languageFluency','c.abstraction','c.delayed','c.orientation','c.tot_moca','pm.Name','pm.Newic')
                ->leftjoin('hisdb.pat_mast as pm', function ($join){
                    $join = $join->on('pm.MRN','=','c.mrn');
                    // $join = $join->on('pm.Episno','=','c.episno');
                    $join = $join->where('pm.compcode','=',session('compcode'));
                })
                ->where('c.compcode','=',session('compcode'))
                ->where('c.mrn','=',$mrn)
                ->where('c.episno','=',$episno)
                ->where('c.dateAssessment','=',$dateAssessment)
                ->first();
        // dd($moca);

        $company = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();
        
        return view('rehab.occupTherapy.mocaChart_pdfmake',compact('moca'));
        
    }

    public function maintable(Request $request){
        $table = DB::table('hisdb.ot_mmse')
                    ->where('compcode',session('compcode'))
                    ->where('mrn','=',$request->mrn)
                    ->where('episno','=',$request->episno)
                    ->where('dateofexam','=',$request->dateofexam)
                    ->get();

        foreach ($table as $key => $value) {
            $all_attach = DB::table('hisdb.ot_mmse_file')
                ->where('idno','=',$request->idno)
                ->where('mrn','=',$request->mrn)
                ->where('episno','=',$request->episno)
                ->where('dateofexam','=',$request->dateofexam)
                ->get();

            $value->all_attach = $all_attach;
        }

        $responce = new stdClass();
        $responce->data = $table;
        return json_encode($responce);

    }

    public function ot_mmse_file(Request $request){
        $responce = new stdClass();
        $ot_mmse_file = DB::table('hisdb.ot_mmse_file')
                    ->where('compcode',session('compcode'))
                    ->where('idno_mmse','=',$request->idno_mmse);

        if($ot_mmse_file->exists()){
            $ot_mmse_file = $ot_mmse_file->get();
            
            $data = [];
            
            foreach($ot_mmse_file as $key => $value){
                $date = [];
                
                $date['idno'] = $value->idno;
                $date['idno_mmse'] = $value->idno_mmse;
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

    public function uploadfile(Request $request){
        $type = $request->file('file')->getClientMimeType();
        $filename = $request->file('file')->getClientOriginalName();
        $file_path = $request->file('file')->store('mmse', 'public_uploads');

        $ot_mmse = DB::table('hisdb.ot_mmse')
                    ->where('compcode',session('compcode'))
                    ->where('idno','=',$request->idno)
                    ->first();

        DB::table('hisdb.ot_mmse_file')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $ot_mmse->mrn,
                    'idno_mmse' => $request->idno,
                    'episno' => $ot_mmse->episno,
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
    
}