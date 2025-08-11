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

class OswestryQuestController extends defaultController
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function show(Request $request)
    {
        return view('rehab.oswestryQuest');
    }
    
    public function form(Request $request)
    {
        DB::enableQueryLog();
        switch($request->action){
            case 'save_table_oswestryQuest':
                switch($request->oper){
                    case 'add':
                        return $this->add_oswestryQuest($request);
                    case 'edit':
                        return $this->edit_oswestryQuest($request);
                    default:
                        return 'error happen..';
                }
            
            case 'get_table_oswestryQuest':
                return $this->get_table_oswestryQuest($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function table(Request $request)
    {
        switch($request->action){
            case 'get_datetime_oswestryQuest':
                return $this->get_datetime_oswestryQuest($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function add_oswestryQuest(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $oswestryquest = DB::table('hisdb.phy_oswestryquest')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno)
                            ->where('entereddate','=',$request->entereddate);
            
            if($oswestryquest->exists()){
                // throw new \Exception('Date already exist.', 500);
                return response('Date already exist.');
            }
            
            DB::table('hisdb.phy_oswestryquest')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn,
                    'episno' => $request->episno,
                    'entereddate' => $request->entereddate,
                    'painIntensity' => $request->painIntensity,
                    'personalCare' => $request->personalCare,
                    'lifting' => $request->lifting,
                    'walking' => $request->walking,
                    'sitting' => $request->sitting,
                    'standing' => $request->standing,
                    'sleeping' => $request->sleeping,
                    'socialLife' => $request->socialLife,
                    'travelling' => $request->travelling,
                    'employHomemaking' => $request->employHomemaking,
                    'totalScore' => $request->totalScore,
                    'disabilityLevel' => $request->disabilityLevel,
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
    
    public function edit_oswestryQuest(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $oswestryquest = DB::table('hisdb.phy_oswestryquest')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno)
                            ->where('entereddate','=',$request->entereddate);
            
            if(!empty($request->idno_oswestryQuest)){
                if($oswestryquest->exists()){
                    if($oswestryquest->first()->idno != $request->idno_oswestryQuest){
                        // throw new \Exception('Date already exist.', 500);
                        return response('Date already exist.');
                    }
                }
                
                DB::table('hisdb.phy_oswestryquest')
                    ->where('idno','=',$request->idno_oswestryQuest)
                    // ->where('mrn','=',$request->mrn)
                    // ->where('episno','=',$request->episno)
                    // ->where('compcode','=',session('compcode'))
                    ->update([
                        'entereddate' => $request->entereddate,
                        'painIntensity' => $request->painIntensity,
                        'personalCare' => $request->personalCare,
                        'lifting' => $request->lifting,
                        'walking' => $request->walking,
                        'sitting' => $request->sitting,
                        'standing' => $request->standing,
                        'sleeping' => $request->sleeping,
                        'socialLife' => $request->socialLife,
                        'travelling' => $request->travelling,
                        'employHomemaking' => $request->employHomemaking,
                        'totalScore' => $request->totalScore,
                        'disabilityLevel' => $request->disabilityLevel,
                        'upduser'  => session('username'),
                        'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
            }else{
                if($oswestryquest->exists()){
                    // throw new \Exception('Date already exist.', 500);
                    return response('Date already exist.');
                }
                
                DB::table('hisdb.phy_oswestryquest')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'entereddate' => $request->entereddate,
                        'painIntensity' => $request->painIntensity,
                        'personalCare' => $request->personalCare,
                        'lifting' => $request->lifting,
                        'walking' => $request->walking,
                        'sitting' => $request->sitting,
                        'standing' => $request->standing,
                        'sleeping' => $request->sleeping,
                        'socialLife' => $request->socialLife,
                        'travelling' => $request->travelling,
                        'employHomemaking' => $request->employHomemaking,
                        'totalScore' => $request->totalScore,
                        'disabilityLevel' => $request->disabilityLevel,
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
    
    public function get_table_oswestryQuest(Request $request){
        
        $oswestryquest_obj = DB::table('hisdb.phy_oswestryquest')
                            ->where('compcode','=',session('compcode'))
                            ->where('idno','=',$request->idno);
                            // ->where('mrn','=',$request->mrn)
                            // ->where('episno','=',$request->episno);
        
        $responce = new stdClass();
        
        if($oswestryquest_obj->exists()){
            $oswestryquest_obj = $oswestryquest_obj->first();
            $responce->oswestryquest = $oswestryquest_obj;
        }
        
        return json_encode($responce);
        
    }
    
    public function get_datetime_oswestryQuest(Request $request){
        
        $responce = new stdClass();
        
        $oswestryquest_obj = DB::table('hisdb.phy_oswestryquest')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno);
        
        if($oswestryquest_obj->exists()){
            $oswestryquest_obj = $oswestryquest_obj->get();
            
            $data = [];
            
            foreach($oswestryquest_obj as $key => $value){
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
    
    public function oswestryquest_chart(Request $request){
        
        $mrn = $request->mrn;
        $episno = $request->episno;
        $entereddate = $request->entereddate;
        if(!$mrn || !$episno || !$entereddate){
            abort(404);
        }
        
        $oswestryquest = DB::table('hisdb.phy_oswestryquest as oq')
                        ->select('oq.idno','oq.compcode','oq.mrn','oq.episno','oq.entereddate','oq.painIntensity','oq.personalCare','oq.lifting','oq.walking','oq.sitting','oq.standing','oq.sleeping','oq.socialLife','oq.travelling','oq.employHomemaking','oq.totalScore','oq.disabilityLevel','oq.adduser','oq.adddate','oq.upduser','oq.upddate','oq.lastuser','oq.lastupdate','oq.computerid','pm.Name','pm.Newic')
                        ->leftjoin('hisdb.pat_mast as pm', function ($join){
                            $join = $join->on('pm.MRN','=','oq.mrn');
                            $join = $join->on('pm.Episno','=','oq.episno');
                            $join = $join->where('pm.compcode','=',session('compcode'));
                        })
                        ->where('oq.compcode','=',session('compcode'))
                        ->where('oq.mrn','=',$mrn)
                        ->where('oq.episno','=',$episno)
                        ->where('oq.entereddate','=',$entereddate)
                        ->first();
        // dd($oswestryquest);
        
        $company = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();
        
        return view('rehab.oswestryQuestChart_pdfmake',compact('oswestryquest','company'));
        
    }
    
}