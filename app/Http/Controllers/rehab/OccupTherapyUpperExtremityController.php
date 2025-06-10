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

            case 'save_table_sensation':
                switch($request->oper){
                    case 'add':
                        return $this->add_sensation($request);
                    case 'edit':
                        return $this->edit_sensation($request);
                    default:
                        return 'error happen..';
                }

            case 'save_table_prehensive':
                switch($request->oper){
                    case 'add':
                        return $this->add_prehensive($request);
                    case 'edit':
                        return $this->edit_prehensive($request);
                    default:
                        return 'error happen..';
                }
            
            case 'get_table_upperExtremity':
                return $this->get_table_upperExtremity($request);

            case 'get_table_sensation':
                return $this->get_table_sensation($request);

            case 'get_table_prehensive':
                return $this->get_table_prehensive($request);

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

            $upperExtremity = DB::table('hisdb.ot_upperextremity')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno)
                            ->where('dateAssess','=',$request->dateAssess);

            if(!empty($request->idno_upperExtremity)){
                DB::table('hisdb.ot_upperextremity')
                    ->where('idno','=',$request->idno_upperExtremity)
                    ->update([
                        'occupTherapist' => $request->occupTherapist,
                        'handDominant' => $request->handDominant,
                        'diagnosis' => $request->diagnosis,
                        'upduser'  => session('username'),
                        'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastcomputerid' => session('computerid'),
                    ]);
            
            }else{

                if($upperExtremity->exists()){
                    return response('Date already exist.');
                }
                
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

    public function add_sensation(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('hisdb.ot_upperextremity_sensation')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'idno_sensation' => $request->idno_sensation,
                        'sens_sharpIntact_rt' => $request->sens_sharpIntact_rt,
                        'sens_sharpIntact_lt' => $request->sens_sharpIntact_lt,
                        'sens_dullIntact_rt' => $request->sens_dullIntact_rt,
                        'sens_dullIntact_lt' => $request->sens_dullIntact_lt,
                        'sens_lightIntact_rt' => $request->sens_lightIntact_rt,
                        'sens_lightIntact_lt' => $request->sens_lightIntact_lt,
                        'sens_deepIntact_rt' => $request->sens_deepIntact_rt,
                        'sens_deepIntact_lt' => $request->sens_deepIntact_lt,
                        'sens_stereoIntact' => $request->sens_stereoIntact,
                        'sens_sharpImpaired_rt' => $request->sens_sharpImpaired_rt,
                        'sens_sharpImpaired_lt' => $request->sens_sharpImpaired_lt,
                        'sens_dullImpaired_rt' => $request->sens_dullImpaired_rt,
                        'sens_dullImpaired_lt' => $request->sens_dullImpaired_lt,
                        'sens_lightImpaired_rt' => $request->sens_lightImpaired_rt,
                        'sens_lightImpaired_lt' => $request->sens_lightImpaired_lt,
                        'sens_deepImpaired_rt' => $request->sens_deepImpaired_rt,
                        'sens_deepImpaired_lt' => $request->sens_deepImpaired_lt,
                        'sens_stereoImpaired' => $request->sens_stereoImpaired,
                        'sens_sharpAbsent_rt' => $request->sens_sharpAbsent_rt,
                        'sens_sharpAbsent_lt' => $request->sens_sharpAbsent_lt,
                        'sens_dullAbsent_rt' => $request->sens_dullAbsent_rt,
                        'sens_dullAbsent_lt' => $request->sens_dullAbsent_lt,
                        'sens_lightAbsent_rt' => $request->sens_lightAbsent_rt,
                        'sens_lightAbsent_lt' => $request->sens_lightAbsent_lt,
                        'sens_deepAbsent_rt' => $request->sens_deepAbsent_rt,
                        'sens_deepAbsent_lt' => $request->sens_deepAbsent_lt,
                        'sens_stereoAbsent' => $request->sens_stereoAbsent,
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
    
    public function edit_sensation(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('hisdb.ot_upperextremity_sensation')
                ->where('mrn','=',$request->mrn)
                ->where('episno','=',$request->episno)
                ->where('idno_sensation','=',$request->idno_sensation)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'sens_sharpIntact_rt' => $request->sens_sharpIntact_rt,
                    'sens_sharpIntact_lt' => $request->sens_sharpIntact_lt,
                    'sens_dullIntact_rt' => $request->sens_dullIntact_rt,
                    'sens_dullIntact_lt' => $request->sens_dullIntact_lt,
                    'sens_lightIntact_rt' => $request->sens_lightIntact_rt,
                    'sens_lightIntact_lt' => $request->sens_lightIntact_lt,
                    'sens_deepIntact_rt' => $request->sens_deepIntact_rt,
                    'sens_deepIntact_lt' => $request->sens_deepIntact_lt,
                    'sens_stereoIntact' => $request->sens_stereoIntact,
                    'sens_sharpImpaired_rt' => $request->sens_sharpImpaired_rt,
                    'sens_sharpImpaired_lt' => $request->sens_sharpImpaired_lt,
                    'sens_dullImpaired_rt' => $request->sens_dullImpaired_rt,
                    'sens_dullImpaired_lt' => $request->sens_dullImpaired_lt,
                    'sens_lightImpaired_rt' => $request->sens_lightImpaired_rt,
                    'sens_lightImpaired_lt' => $request->sens_lightImpaired_lt,
                    'sens_deepImpaired_rt' => $request->sens_deepImpaired_rt,
                    'sens_deepImpaired_lt' => $request->sens_deepImpaired_lt,
                    'sens_stereoImpaired' => $request->sens_stereoImpaired,
                    'sens_sharpAbsent_rt' => $request->sens_sharpAbsent_rt,
                    'sens_sharpAbsent_lt' => $request->sens_sharpAbsent_lt,
                    'sens_dullAbsent_rt' => $request->sens_dullAbsent_rt,
                    'sens_dullAbsent_lt' => $request->sens_dullAbsent_lt,
                    'sens_lightAbsent_rt' => $request->sens_lightAbsent_rt,
                    'sens_lightAbsent_lt' => $request->sens_lightAbsent_lt,
                    'sens_deepAbsent_rt' => $request->sens_deepAbsent_rt,
                    'sens_deepAbsent_lt' => $request->sens_deepAbsent_lt,
                    'sens_stereoAbsent' => $request->sens_stereoAbsent,
                    'upduser'  => session('username'),
                    'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastcomputerid' => session('computerid'),
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

    public function add_prehensive(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('hisdb.ot_upperextremity_prehensive')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'sens_sharpIntact_rt' => $request->sens_sharpIntact_rt,
                        'sens_sharpIntact_lt' => $request->sens_sharpIntact_lt,
                        'sens_dullIntact_rt' => $request->sens_dullIntact_rt,
                        'sens_dullIntact_lt' => $request->sens_dullIntact_lt,
                        'sens_lightIntact_rt' => $request->sens_lightIntact_rt,
                        'sens_lightIntact_lt' => $request->sens_lightIntact_lt,
                        'sens_deepIntact_rt' => $request->sens_deepIntact_rt,
                        'sens_deepIntact_lt' => $request->sens_deepIntact_lt,
                        'sens_stereoIntact' => $request->sens_stereoIntact,
                        'sens_sharpImpaired_rt' => $request->sens_sharpImpaired_rt,
                        'sens_sharpImpaired_lt' => $request->sens_sharpImpaired_lt,
                        'sens_dullImpaired_rt' => $request->sens_dullImpaired_rt,
                        'sens_dullImpaired_lt' => $request->sens_dullImpaired_lt,
                        'sens_lightImpaired_rt' => $request->sens_lightImpaired_rt,
                        'sens_lightImpaired_lt' => $request->sens_lightImpaired_lt,
                        'sens_deepImpaired_rt' => $request->sens_deepImpaired_rt,
                        'sens_deepImpaired_lt' => $request->sens_deepImpaired_lt,
                        'sens_stereoImpaired' => $request->sens_stereoImpaired,
                        'sens_sharpAbsent_rt' => $request->sens_sharpAbsent_rt,
                        'sens_sharpAbsent_lt' => $request->sens_sharpAbsent_lt,
                        'sens_dullAbsent_rt' => $request->sens_dullAbsent_rt,
                        'sens_dullAbsent_lt' => $request->sens_dullAbsent_lt,
                        'sens_lightAbsent_rt' => $request->sens_lightAbsent_rt,
                        'sens_lightAbsent_lt' => $request->sens_lightAbsent_lt,
                        'sens_deepAbsent_rt' => $request->sens_deepAbsent_rt,
                        'sens_deepAbsent_lt' => $request->sens_deepAbsent_lt,
                        'sens_stereoAbsent' => $request->sens_stereoAbsent,
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
    
    public function edit_prehensive(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('hisdb.ot_upperextremity_prehensive')
                ->where('mrn','=',$request->mrn)
                ->where('episno','=',$request->episno)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'sens_sharpIntact_rt' => $request->sens_sharpIntact_rt,
                    'sens_sharpIntact_lt' => $request->sens_sharpIntact_lt,
                    'sens_dullIntact_rt' => $request->sens_dullIntact_rt,
                    'sens_dullIntact_lt' => $request->sens_dullIntact_lt,
                    'sens_lightIntact_rt' => $request->sens_lightIntact_rt,
                    'sens_lightIntact_lt' => $request->sens_lightIntact_lt,
                    'sens_deepIntact_rt' => $request->sens_deepIntact_rt,
                    'sens_deepIntact_lt' => $request->sens_deepIntact_lt,
                    'sens_stereoIntact' => $request->sens_stereoIntact,
                    'sens_sharpImpaired_rt' => $request->sens_sharpImpaired_rt,
                    'sens_sharpImpaired_lt' => $request->sens_sharpImpaired_lt,
                    'sens_dullImpaired_rt' => $request->sens_dullImpaired_rt,
                    'sens_dullImpaired_lt' => $request->sens_dullImpaired_lt,
                    'sens_lightImpaired_rt' => $request->sens_lightImpaired_rt,
                    'sens_lightImpaired_lt' => $request->sens_lightImpaired_lt,
                    'sens_deepImpaired_rt' => $request->sens_deepImpaired_rt,
                    'sens_deepImpaired_lt' => $request->sens_deepImpaired_lt,
                    'sens_stereoImpaired' => $request->sens_stereoImpaired,
                    'sens_sharpAbsent_rt' => $request->sens_sharpAbsent_rt,
                    'sens_sharpAbsent_lt' => $request->sens_sharpAbsent_lt,
                    'sens_dullAbsent_rt' => $request->sens_dullAbsent_rt,
                    'sens_dullAbsent_lt' => $request->sens_dullAbsent_lt,
                    'sens_lightAbsent_rt' => $request->sens_lightAbsent_rt,
                    'sens_lightAbsent_lt' => $request->sens_lightAbsent_lt,
                    'sens_deepAbsent_rt' => $request->sens_deepAbsent_rt,
                    'sens_deepAbsent_lt' => $request->sens_deepAbsent_lt,
                    'sens_stereoAbsent' => $request->sens_stereoAbsent,
                    'upduser'  => session('username'),
                    'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastcomputerid' => session('computerid'),
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
                                ->where('idno','=',$request->idno);
        
        $responce = new stdClass();
        
        if($upperExtremity_obj->exists()){
            $upperExtremity_obj = $upperExtremity_obj->first();
            $date = Carbon::createFromFormat('Y-m-d', $upperExtremity_obj->dateAssess)->format('Y-m-d');

            $responce->upperExtremity = $upperExtremity_obj;
            $responce->date = $date;
        }
        
        return json_encode($responce);
        
    }

    public function get_table_sensation(Request $request){
        
        // $idno_upperExtremity = $request->idno_upperExtremity;
        // dd($idno_upperExtremity);
        $sensation_obj = DB::table('hisdb.ot_upperextremity_sensation')
                                ->where('compcode','=',session('compcode'))
                                ->where('mrn','=',$request->mrn)
                                ->where('episno','=',$request->episno)
                                ->where('idno_sensation','=',$request->idno_sensation);
        
        $responce = new stdClass();
        
        if($sensation_obj->exists()){
            $sensation_obj = $sensation_obj->first();
            $responce->sensation = $sensation_obj;
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
                        'idno_rof' => $request->idno_rof,
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
                    'lastcomputerid' => session('computerid'),
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
                        'idno_hand' => $request->idno_hand,
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
                    'lastcomputerid' => session('computerid'),
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