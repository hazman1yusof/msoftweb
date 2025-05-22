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

class OccupTherapyUpperExtremityController extends defaultController
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function show(Request $request)
    {
        return view('rehab.occupTherapy.occupTherapy_upperExtremity');
    }

    public function table(Request $request)
    {
        switch($request->action){
            case 'get_table_datetimeUpperExtremity':
                return $this->get_table_datetimeUpperExtremity($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function form(Request $request)
    {
        DB::enableQueryLog();
        switch($request->action){
            case 'save_table_upperExtremity':
                switch($request->oper){
                    case 'add':
                        return $this->add_upperExtremity($request);
                    case 'edit':
                        return $this->edit_upperExtremity($request);
                    default:
                        return 'error happen..';
                }
            
            case 'get_table_upperExtremity':
                return $this->get_table_upperExtremity($request);

            case 'addJqgridrof_save':
                return $this->add_jqgridrof($request);
            
            case 'addJqgridrof_edit':
                return $this->edit_jqgridrof($request);
            
            case 'addJqgridrof_delete':
                return $this->del_jqgridrof($request);

            case 'addJqgridhand_save':
                return $this->add_jqgridhand($request);
            
            case 'addJqgridhand_edit':
                return $this->edit_jqgridhand($request);
            
            case 'addJqgridhand_delete':
                return $this->del_jqgridhand($request);
                
            default:
                return 'error happen..';
        }
    }

    public function get_table_datetimeUpperExtremity(Request $request){
        
        $responce = new stdClass();
        
        $upperExtremity_obj = DB::table('hisdb.ot_upperextremity')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno);
        
        if($upperExtremity_obj->exists()){
            $upperExtremity_obj = $upperExtremity_obj->get();
            
            $data = [];
            
            foreach($upperExtremity_obj as $key => $value){
                if(!empty($value->dateAssess)){
                    $date['dateAssess'] =  Carbon::createFromFormat('Y-m-d', $value->dateAssess)->format('d-m-Y');
                }else{
                    $date['dateAssess'] =  '-';
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
    
    public function add_upperExtremity(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('hisdb.ot_upperextremity')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'dateAssess' => $request->dateAssess,
                        'occupTherapist' => $request->occupTherapist,
                        'handDominant' => $request->handDominant,
                        'diagnosis' => $request->diagnosis,
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
    
    public function edit_upperExtremity(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('hisdb.ot_upperextremity')
                ->where('mrn','=',$request->mrn)
                ->where('episno','=',$request->episno)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'dateAssess' => $request->dateAssess,
                    'occupTherapist' => $request->occupTherapist,
                    'handDominant' => $request->handDominant,
                    'diagnosis' => $request->diagnosis,
                    'upduser'  => session('username'),
                    'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'computerid' => session('computerid'),
                ]);
            
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
    
    public function get_table_upperExtremity(Request $request){
        
        $upperExtremity_obj = DB::table('hisdb.ot_upperextremity')
                                ->where('compcode','=',session('compcode'))
                                ->where('mrn','=',$request->mrn)
                                ->where('episno','=',$request->episno);
        
        $responce = new stdClass();
        
        if($upperExtremity_obj->exists()){
            $upperExtremity_obj = $upperExtremity_obj->first();
            $responce->upperExtremity = $upperExtremity_obj;
        }
        
        return json_encode($responce);
        
    }

    public function add_jqgridrof(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('hisdb.ot_upperExtremity_rof')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'daterof' => $request->daterof,
                        'dominant' => $request->dominant,
                        'shoulder_ext' => $request->shoulder_ext,
                        'shoulder_flex' => $request->shoulder_flex,
                        'shoulder_addAbd' => $request->shoulder_addAbd,
                        'shoulder_intRotation' => $request->shoulder_intRotation,
                        'shoulder_extRotation' => $request->shoulder_extRotation,
                        'elbow_extFlex' => $request->elbow_extFlex,
                        'forearm_pronation' => $request->forearm_pronation,
                        'forearm_supination' => $request->forearm_supination,
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
    
    public function edit_jqgridrof(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('hisdb.ot_upperExtremity_rof')
                ->where('idno','=',$request->idno)
                ->where('compcode','=',session('compcode'))
                ->update([
                   'daterof' => $request->daterof,
                    'dominant' => $request->dominant,
                    'shoulder_ext' => $request->shoulder_ext,
                    'shoulder_flex' => $request->shoulder_flex,
                    'shoulder_addAbd' => $request->shoulder_addAbd,
                    'shoulder_intRotation' => $request->shoulder_intRotation,
                    'shoulder_extRotation' => $request->shoulder_extRotation,
                    'elbow_extFlex' => $request->elbow_extFlex,
                    'forearm_pronation' => $request->forearm_pronation,
                    'forearm_supination' => $request->forearm_supination,
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
    
    public function del_jqgridrof(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('hisdb.ot_upperExtremity_rof')
                ->where('idno','=',$request->idno)
                ->where('compcode','=',session('compcode'))
                ->delete();
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
    
    }

    public function add_jqgridhand(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('hisdb.ot_upperextremity_hand')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'datehand' => $request->datehand,
                        'dominants' => $request->dominants,
                        'wrist_flex' => $request->wrist_flex,
                        'wrist_ext' => $request->wrist_ext,
                        'wrist_ulna' => $request->wrist_ulna,
                        'thumb_extFlexMP' => $request->thumb_extFlexMP,
                        'thumb_extFlexIP' => $request->thumb_extFlexIP,
                        'thumb_extFlexCMC' => $request->thumb_extFlexCMC,
                        'thumb_palmar' => $request->thumb_palmar,
                        'thumb_tip' => $request->thumb_tip,
                        'thumb_base' => $request->thumb_base,
                        'index_MCP' => $request->index_MCP,
                        'index_PIP' => $request->index_PIP,
                        'index_DIP' => $request->index_DIP,
                        'middle_MCP' => $request->middle_MCP,
                        'middle_PIP' => $request->middle_PIP,
                        'middle_DIP' => $request->middle_DIP,
                        'ring_MCP' => $request->ring_MCP,
                        'ring_PIP' => $request->ring_PIP,
                        'ring_DIP' => $request->ring_DIP,
                        'little_MCP' => $request->little_MCP,
                        'little_PIP' => $request->little_PIP,
                        'little_DIP' => $request->little_DIP,
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
    
    public function edit_jqgridhand(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('hisdb.ot_upperextremity_hand')
                ->where('idno','=',$request->idno)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'datehand' => $request->datehand,
                    'dominants' => $request->dominants,
                    'wrist_flex' => $request->wrist_flex,
                    'wrist_ext' => $request->wrist_ext,
                    'wrist_ulna' => $request->wrist_ulna,
                    'thumb_extFlexMP' => $request->thumb_extFlexMP,
                    'thumb_extFlexIP' => $request->thumb_extFlexIP,
                    'thumb_extFlexCMC' => $request->thumb_extFlexCMC,
                    'thumb_palmar' => $request->thumb_palmar,
                    'thumb_tip' => $request->thumb_tip,
                    'thumb_base' => $request->thumb_base,
                    'index_MCP' => $request->index_MCP,
                    'index_PIP' => $request->index_PIP,
                    'index_DIP' => $request->index_DIP,
                    'middle_MCP' => $request->middle_MCP,
                    'middle_PIP' => $request->middle_PIP,
                    'middle_DIP' => $request->middle_DIP,
                    'ring_MCP' => $request->ring_MCP,
                    'ring_PIP' => $request->ring_PIP,
                    'ring_DIP' => $request->ring_DIP,
                    'little_MCP' => $request->little_MCP,
                    'little_PIP' => $request->little_PIP,
                    'little_DIP' => $request->little_DIP,
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
    
    public function del_jqgridhand(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('hisdb.ot_upperextremity_hand')
                ->where('idno','=',$request->idno)
                ->where('compcode','=',session('compcode'))
                ->delete();
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
    
    }

}