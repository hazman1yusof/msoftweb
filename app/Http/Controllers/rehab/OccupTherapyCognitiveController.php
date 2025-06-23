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
    
}