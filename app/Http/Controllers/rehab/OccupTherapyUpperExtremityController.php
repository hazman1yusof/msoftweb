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
            case 'save_table_impressions':
                switch($request->oper){
                    case 'add':
                        return $this->add_impressions($request);
                    case 'edit':
                        return $this->edit_impressions($request);
                    default:
                        return 'error happen..';
                }

            case 'save_table_strength':
                switch($request->oper){
                    case 'add':
                        return $this->add_strength($request);
                    case 'edit':
                        return $this->edit_strength($request);
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

            case 'save_table_skin':
                switch($request->oper){
                    case 'add':
                        return $this->add_skin($request);
                    case 'edit':
                        return $this->edit_skin($request);
                    default:
                        return 'error happen..';
                }

            case 'save_table_edema':
                switch($request->oper){
                    case 'add':
                        return $this->add_edema($request);
                    case 'edit':
                        return $this->edit_edema($request);
                    default:
                        return 'error happen..';
                }

            case 'save_table_func':
                switch($request->oper){
                    case 'add':
                        return $this->add_func($request);
                    case 'edit':
                        return $this->edit_func($request);
                    default:
                        return 'error happen..';
                }

            case 'get_table_upperExtremity':
                return $this->get_table_upperExtremity($request);

            case 'get_table_impressions':
                return $this->get_table_impressions($request);

            case 'get_table_strength':
                return $this->get_table_strength($request);

            case 'get_table_sensation':
                return $this->get_table_sensation($request);

            case 'get_table_prehensive':
                return $this->get_table_prehensive($request);

            case 'get_table_skin':
                return $this->get_table_skin($request);

            case 'get_table_edema':
                return $this->get_table_edema($request);

            case 'get_table_func':
                return $this->get_table_func($request);

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
                        'occupTherapist' => session('username'),
                        'handDominant' => $request->handDominant,
                        'diagnosis' => $request->diagnosis,
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
                        'handDominant' => $request->handDominant,
                        'diagnosis' => $request->diagnosis,
                        'upduser'  => session('username'),
                        'upddate'  => Carbon::now("Asia/Kuala_Lumpur"),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur"),
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
                            'occupTherapist' => session('username'),
                            'handDominant' => $request->handDominant,
                            'diagnosis' => $request->diagnosis,
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

    public function add_impressions(Request $request){
        
        DB::beginTransaction();
        
        try {

            if(!empty($request->rof_impressions)&&($request->idno_rof)){ // to check either tab rof or hand
                $tabName = $request->rof_impressions; 
                $idno_imp = $request->idno_rof;
            } else if(!empty($request->hand_impressions)&&($request->idno_hand)){
                $tabName = $request->hand_impressions; 
                $idno_imp = $request->idno_hand;
            }
            
            DB::table('hisdb.ot_upperextremity_imp')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'idno_imp' => $idno_imp,
                        'tabName' => $tabName,
                        'impressions' => $request->impressions,
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
    
    public function edit_impressions(Request $request){
        
        DB::beginTransaction();
        
        try {

            if(!empty($request->rof_impressions)&&($request->idno_rof)){ // to check either tab rof or hand
                $tabName = $request->rof_impressions; 
                $idno_imp = $request->idno_rof;
            } else if(!empty($request->hand_impressions)&&($request->idno_hand)){
                $tabName = $request->hand_impressions; 
                $idno_imp = $request->idno_hand;
            }
            
            DB::table('hisdb.ot_upperextremity_imp')
                ->where('mrn','=',$request->mrn)
                ->where('episno','=',$request->episno)
                ->where('idno_imp','=',$idno_imp)
                ->where('tabName','=',$tabName)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'impressions' => $request->impressions,
                    'upduser'  => session('username'),
                    'upddate'  => Carbon::now("Asia/Kuala_Lumpur"),
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur"),
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

    public function add_strength(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('hisdb.ot_upperextremity_strength')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'idno_strength' => $request->idno_strength,
                        'mmt' => $request->mmt,
                        'jamar' => $request->jamar,
                        'mmt_remarks' => $request->mmt_remarks,
                        'jamarGripDate' => $request->jamarGripDate,
                        'jamarGrip_rt' => $request->jamarGrip_rt,
                        'jamarGrip_lt' => $request->jamarGrip_lt,
                        'jamarPinchDate' => $request->jamarPinchDate,
                        'jamarPinch_lateral_rt' => $request->jamarPinch_lateral_rt,
                        'jamarPinch_pad_rt' => $request->jamarPinch_pad_rt,
                        'jamarPinch_jaw_rt' => $request->jamarPinch_jaw_rt,
                        'jamarPinch_lateral_lt' => $request->jamarPinch_lateral_lt,
                        'jamarPinch_pad_lt' => $request->jamarPinch_pad_lt,
                        'jamarPinch_jaw_lt' => $request->jamarPinch_jaw_lt,
                        'impressions' => $request->impressions,
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
    
    public function edit_strength(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('hisdb.ot_upperextremity_strength')
                ->where('mrn','=',$request->mrn)
                ->where('episno','=',$request->episno)
                ->where('idno_strength','=',$request->idno_strength)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'mmt' => $request->mmt,
                    'jamar' => $request->jamar,
                    'mmt_remarks' => $request->mmt_remarks,
                    'jamarGripDate' => $request->jamarGripDate,
                    'jamarGrip_rt' => $request->jamarGrip_rt,
                    'jamarGrip_lt' => $request->jamarGrip_lt,
                    'jamarPinchDate' => $request->jamarPinchDate,
                    'jamarPinch_lateral_rt' => $request->jamarPinch_lateral_rt,
                    'jamarPinch_pad_rt' => $request->jamarPinch_pad_rt,
                    'jamarPinch_jaw_rt' => $request->jamarPinch_jaw_rt,
                    'jamarPinch_lateral_lt' => $request->jamarPinch_lateral_lt,
                    'jamarPinch_pad_lt' => $request->jamarPinch_pad_lt,
                    'jamarPinch_jaw_lt' => $request->jamarPinch_jaw_lt,
                    'impressions' => $request->impressions,
                    'upduser'  => session('username'),
                    'upddate'  => Carbon::now("Asia/Kuala_Lumpur"),
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur"),
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
                        'impressions' => $request->impressions,
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
                    'impressions' => $request->impressions,
                    'upduser'  => session('username'),
                    'upddate'  => Carbon::now("Asia/Kuala_Lumpur"),
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur"),
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
                        'idno_prehensive' => $request->idno_prehensive,
                        'prehensive_hook_rt' => $request->prehensive_hook_rt,
                        'prehensive_hook_lt' => $request->prehensive_hook_lt,
                        'prehensive_lateral_rt' => $request->prehensive_lateral_rt,
                        'prehensive_lateral_lt' => $request->prehensive_lateral_lt,
                        'prehensive_tip_rt' => $request->prehensive_tip_rt,
                        'prehensive_tip_lt' => $request->prehensive_tip_lt,
                        'prehensive_cylindrical_rt' => $request->prehensive_cylindrical_rt,
                        'prehensive_cylindrical_lt' => $request->prehensive_cylindrical_lt,
                        'prehensive_pad_rt' => $request->prehensive_pad_rt,
                        'prehensive_pad_lt' => $request->prehensive_pad_lt,
                        'prehensive_jaw_rt' => $request->prehensive_jaw_rt,
                        'prehensive_jaw_lt' => $request->prehensive_jaw_lt,
                        'prehensive_spherical_rt' => $request->prehensive_spherical_rt,
                        'prehensive_spherical_lt' => $request->prehensive_spherical_lt,
                        'impressions' => $request->impressions,
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
    
    public function edit_prehensive(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('hisdb.ot_upperextremity_prehensive')
                ->where('mrn','=',$request->mrn)
                ->where('episno','=',$request->episno)
                ->where('idno_prehensive','=',$request->idno_prehensive)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'prehensive_hook_rt' => $request->prehensive_hook_rt,
                    'prehensive_hook_lt' => $request->prehensive_hook_lt,
                    'prehensive_lateral_rt' => $request->prehensive_lateral_rt,
                    'prehensive_lateral_lt' => $request->prehensive_lateral_lt,
                    'prehensive_tip_rt' => $request->prehensive_tip_rt,
                    'prehensive_tip_lt' => $request->prehensive_tip_lt,
                    'prehensive_cylindrical_rt' => $request->prehensive_cylindrical_rt,
                    'prehensive_cylindrical_lt' => $request->prehensive_cylindrical_lt,
                    'prehensive_pad_rt' => $request->prehensive_pad_rt,
                    'prehensive_pad_lt' => $request->prehensive_pad_lt,
                    'prehensive_jaw_rt' => $request->prehensive_jaw_rt,
                    'prehensive_jaw_lt' => $request->prehensive_jaw_lt,
                    'prehensive_spherical_rt' => $request->prehensive_spherical_rt,
                    'prehensive_spherical_lt' => $request->prehensive_spherical_lt,
                    'impressions' => $request->impressions,
                    'upduser'  => session('username'),
                    'upddate'  => Carbon::now("Asia/Kuala_Lumpur"),
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur"),
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

    public function add_skin(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('hisdb.ot_upperextremity_skin')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'idno_skin' => $request->idno_skin,
                        'skinCondition' => $request->skinCondition,
                        'impressions' => $request->impressions,
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
    
    public function edit_skin(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('hisdb.ot_upperextremity_skin')
                ->where('mrn','=',$request->mrn)
                ->where('episno','=',$request->episno)
                ->where('idno_skin','=',$request->idno_skin)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'skinCondition' => $request->skinCondition,
                    'impressions' => $request->impressions,
                    'upduser'  => session('username'),
                    'upddate'  => Carbon::now("Asia/Kuala_Lumpur"),
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur"),
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

    public function add_edema(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('hisdb.ot_upperextremity_edema')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'idno_edema' => $request->idno_edema,
                        'edema_noted_rt' => $request->edema_noted_rt,
                        'edema_noted_lt' => $request->edema_noted_lt,
                        'edema_new1' => $request->edema_new1,
                        'edema_new1_rt' => $request->edema_new1_rt,
                        'edema_new1_lt' => $request->edema_new1_lt,
                        'impressions' => $request->impressions,
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
    
    public function edit_edema(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('hisdb.ot_upperextremity_edema')
                ->where('mrn','=',$request->mrn)
                ->where('episno','=',$request->episno)
                ->where('idno_edema','=',$request->idno_edema)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'edema_noted_rt' => $request->edema_noted_rt,
                    'edema_noted_lt' => $request->edema_noted_lt,
                    'edema_new1' => $request->edema_new1,
                    'edema_new1_rt' => $request->edema_new1_rt,
                    'edema_new1_lt' => $request->edema_new1_lt,
                    'impressions' => $request->impressions,
                    'upduser'  => session('username'),
                    'upddate'  => Carbon::now("Asia/Kuala_Lumpur"),
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur"),
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

    public function add_func(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('hisdb.ot_upperextremity_func')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'idno_func' => $request->idno_func,
                        'func_writing_rt' => $request->func_writing_rt,
                        'func_writing_lt' => $request->func_writing_lt,
                        'func_pickCoins_rt' => $request->func_pickCoins_rt,
                        'func_pickCoins_lt' => $request->func_pickCoins_lt,
                        'func_pickPins_rt' => $request->func_pickPins_rt,
                        'func_pickPins_lt' => $request->func_pickPins_lt,
                        'func_button_rt' => $request->func_button_rt,
                        'func_button_lt' => $request->func_button_lt,
                        'func_feedSpoon_rt' => $request->func_feedSpoon_rt,
                        'func_feedSpoon_lt' => $request->func_feedSpoon_lt,
                        'func_feedHand_rt' => $request->func_feedHand_rt,
                        'func_feedHand_lt' => $request->func_feedHand_lt,
                        'impressions' => $request->impressions,
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
    
    public function edit_func(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('hisdb.ot_upperextremity_func')
                ->where('mrn','=',$request->mrn)
                ->where('episno','=',$request->episno)
                ->where('idno_func','=',$request->idno_func)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'func_writing_rt' => $request->func_writing_rt,
                    'func_writing_lt' => $request->func_writing_lt,
                    'func_pickCoins_rt' => $request->func_pickCoins_rt,
                    'func_pickCoins_lt' => $request->func_pickCoins_lt,
                    'func_pickPins_rt' => $request->func_pickPins_rt,
                    'func_pickPins_lt' => $request->func_pickPins_lt,
                    'func_button_rt' => $request->func_button_rt,
                    'func_button_lt' => $request->func_button_lt,
                    'func_feedSpoon_rt' => $request->func_feedSpoon_rt,
                    'func_feedSpoon_lt' => $request->func_feedSpoon_lt,
                    'func_feedHand_rt' => $request->func_feedHand_rt,
                    'func_feedHand_lt' => $request->func_feedHand_lt,
                    'impressions' => $request->impressions,
                    'upduser'  => session('username'),
                    'upddate'  => Carbon::now("Asia/Kuala_Lumpur"),
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur"),
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

    public function get_table_impressions(Request $request){
        
        $impressions_obj = DB::table('hisdb.ot_upperextremity_imp')
                                ->where('compcode','=',session('compcode'))
                                ->where('mrn','=',$request->mrn)
                                ->where('episno','=',$request->episno)
                                ->where('tabName','=',$request->tabName)
                                ->where('idno_imp','=',$request->idno_imp);
        
        $responce = new stdClass();
        
        if($impressions_obj->exists()){
            $impressions_obj = $impressions_obj->first();
            $responce->impressions = $impressions_obj;
        }
        
        return json_encode($responce);
        
    }

    public function get_table_strength(Request $request){
        
        $strength_obj = DB::table('hisdb.ot_upperextremity_strength')
                                ->where('compcode','=',session('compcode'))
                                ->where('mrn','=',$request->mrn)
                                ->where('episno','=',$request->episno)
                                ->where('idno_strength','=',$request->idno_strength);
        
        $responce = new stdClass();
        
        if($strength_obj->exists()){
            $strength_obj = $strength_obj->first();
            $responce->strength = $strength_obj;
        }
        
        return json_encode($responce);
        
    }

    public function get_table_sensation(Request $request){
        
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

    public function get_table_prehensive(Request $request){
        
        $prehensive_obj = DB::table('hisdb.ot_upperextremity_prehensive')
                                ->where('compcode','=',session('compcode'))
                                ->where('mrn','=',$request->mrn)
                                ->where('episno','=',$request->episno)
                                ->where('idno_prehensive','=',$request->idno_prehensive);
        
        $responce = new stdClass();
        
        if($prehensive_obj->exists()){
            $prehensive_obj = $prehensive_obj->first();
            $responce->prehensive = $prehensive_obj;
        }
        
        return json_encode($responce);
        
    }

    public function get_table_skin(Request $request){
        
        $skin_obj = DB::table('hisdb.ot_upperextremity_skin')
                                ->where('compcode','=',session('compcode'))
                                ->where('mrn','=',$request->mrn)
                                ->where('episno','=',$request->episno)
                                ->where('idno_skin','=',$request->idno_skin);
        
        $responce = new stdClass();
        
        if($skin_obj->exists()){
            $skin_obj = $skin_obj->first();
            $responce->skin = $skin_obj;
        }
        
        return json_encode($responce);
        
    }

    public function get_table_edema(Request $request){
        
        $edema_obj = DB::table('hisdb.ot_upperextremity_edema')
                                ->where('compcode','=',session('compcode'))
                                ->where('mrn','=',$request->mrn)
                                ->where('episno','=',$request->episno)
                                ->where('idno_edema','=',$request->idno_edema);
        
        $responce = new stdClass();
        
        if($edema_obj->exists()){
            $edema_obj = $edema_obj->first();
            $responce->edema = $edema_obj;
        }
        
        return json_encode($responce);
        
    }

    public function get_table_func(Request $request){
        
        $func_obj = DB::table('hisdb.ot_upperextremity_func')
                                ->where('compcode','=',session('compcode'))
                                ->where('mrn','=',$request->mrn)
                                ->where('episno','=',$request->episno)
                                ->where('idno_func','=',$request->idno_func);
        
        $responce = new stdClass();
        
        if($func_obj->exists()){
            $func_obj = $func_obj->first();
            $responce->func = $func_obj;
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
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur"),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur"),
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
                    'upddate'  => Carbon::now("Asia/Kuala_Lumpur"),
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur"),
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
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur"),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur"),
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
                    'upddate'  => Carbon::now("Asia/Kuala_Lumpur"),
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur"),
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

     public function upperExtremity_chart(Request $request){
        
        $mrn = $request->mrn;
        $episno = $request->episno;
        $dateAssess = $request->dateAssess;

        if(!$mrn || !$episno){
            abort(404);
        }
        
        $upperExtremity = DB::table('hisdb.ot_upperextremity as h')
                ->select('h.idno','h.mrn','h.episno','h.dateAssess','h.occupTherapist','h.handDominant','h.diagnosis','pm.Name','pm.Newic')
                ->leftjoin('hisdb.pat_mast as pm', function ($join){
                    $join = $join->on('pm.MRN','=','h.mrn');
                    // $join = $join->on('pm.Episno','=','h.episno');
                    $join = $join->where('pm.compcode','=',session('compcode'));
                })
                ->where('h.compcode','=',session('compcode'))
                ->where('h.mrn','=',$mrn)
                ->where('h.episno','=',$episno)
                ->where('h.dateAssess','=',$dateAssess)
                ->first();
        // dd($upperExtremity);
            
        $rof = DB::table('hisdb.ot_upperextremity_rof as r')
                ->select('r.idno','r.mrn','r.episno','r.daterof','r.dominant','r.idno_rof','r.shoulder_ext','r.shoulder_flex','r.shoulder_addAbd','r.shoulder_intRotation','r.shoulder_extRotation','r.elbow_extFlex','r.forearm_pronation','r.forearm_supination','r.impressions','h.idno','h.dateAssess')
                ->leftjoin('hisdb.ot_upperextremity as h', function ($join){
                    $join = $join->on('h.mrn','=','r.mrn');
                    $join = $join->on('h.episno','=','r.episno');
                    $join = $join->where('h.compcode','=',session('compcode'));
                })
                ->where('r.compcode','=',session('compcode'))
                ->where('r.mrn','=',$mrn)
                ->where('r.episno','=',$episno)
                ->where('h.dateAssess','=',$dateAssess)
                ->where('r.idno_rof','=',$upperExtremity->idno)
                ->get();
        
        $imp_ROF = DB::table('hisdb.ot_upperextremity_imp')
                                ->where('compcode','=',session('compcode'))
                                ->where('mrn','=',$mrn)
                                ->where('episno','=',$episno)
                                ->where('tabName','=','ROF')
                                ->where('idno_imp','=',$upperExtremity->idno)
                                ->first();
            
        $hand = DB::table('hisdb.ot_upperExtremity_hand as h')
                ->select('h.idno','h.compcode','h.mrn','h.episno','h.datehand','h.dominants','h.idno_hand','h.wrist_flex','h.wrist_ext','h.wrist_ulna','h.thumb_extFlexMP','h.thumb_extFlexIP','h.thumb_extFlexCMC','h.thumb_palmar','h.thumb_tip','h.thumb_base','h.index_MCP','h.index_PIP','h.index_DIP','h.middle_MCP','h.middle_PIP','h.middle_DIP','h.ring_MCP','h.ring_PIP','h.ring_DIP','h.little_MCP','h.little_PIP','h.little_DIP','o.idno as midno','o.dateAssess')
                ->leftjoin('hisdb.ot_upperextremity as o', function ($join){
                    $join = $join->on('o.mrn','=','h.mrn');
                    $join = $join->on('o.episno','=','h.episno');
                    $join = $join->where('o.compcode','=',session('compcode'));
                })
                ->where('h.compcode','=',session('compcode'))
                ->where('h.mrn','=',$mrn)
                ->where('h.episno','=',$episno)
                ->where('o.dateAssess','=',$dateAssess)
                ->where('h.idno_hand','=',$upperExtremity->idno)
                ->get();
        
        $imp_hand = DB::table('hisdb.ot_upperextremity_imp')
                                ->where('compcode','=',session('compcode'))
                                ->where('mrn','=',$mrn)
                                ->where('episno','=',$episno)
                                ->where('tabName','=','hand')
                                ->where('idno_imp','=',$upperExtremity->idno)
                                ->first();

                                //dd($imp_hand);

        // muscle strength
        $strength = DB::table('hisdb.ot_upperextremity_strength as s')
                ->select('s.idno','s.idno_strength','s.mrn','s.episno','s.mmt','s.jamar','s.mmt_remarks','s.jamarGripDate','s.jamarGrip_rt','s.jamarGrip_lt','s.jamarPinchDate','s.jamarPinch_lateral_rt','s.jamarPinch_pad_rt','s.jamarPinch_jaw_rt','s.jamarPinch_lateral_lt','s.jamarPinch_pad_lt','s.jamarPinch_jaw_lt','s.impressions','h.idno','h.dateAssess')
                ->leftjoin('hisdb.ot_upperextremity as h', function ($join){
                    $join = $join->on('h.mrn','=','s.mrn');
                    $join = $join->on('h.episno','=','s.episno');
                    $join = $join->where('h.compcode','=',session('compcode'));
                })
                ->where('s.compcode','=',session('compcode'))
                ->where('s.mrn','=',$mrn)
                ->where('s.episno','=',$episno)
                ->where('h.dateAssess','=',$dateAssess)
                ->where('s.idno_strength','=',$upperExtremity->idno)
                ->first();
        // dd($strength);

        // sensation
        $sensation = DB::table('hisdb.ot_upperextremity_sensation as ss')
                ->select('ss.idno','ss.idno_sensation','ss.mrn','ss.episno','ss.sens_sharpIntact_rt','ss.sens_sharpIntact_lt','ss.sens_dullIntact_rt','ss.sens_dullIntact_lt','ss.sens_lightIntact_rt','ss.sens_lightIntact_lt','ss.sens_deepIntact_rt','ss.sens_deepIntact_lt','ss.sens_stereoIntact','ss.sens_sharpImpaired_rt','ss.sens_sharpImpaired_lt','ss.sens_dullImpaired_rt','ss.sens_dullImpaired_lt','ss.sens_lightImpaired_rt','ss.sens_lightImpaired_lt','ss.sens_deepImpaired_rt','ss.sens_deepImpaired_lt','ss.sens_stereoImpaired','ss.sens_sharpAbsent_rt','ss.sens_sharpAbsent_lt','ss.sens_dullAbsent_rt','ss.sens_dullAbsent_lt','ss.sens_lightAbsent_rt','ss.sens_lightAbsent_lt','ss.sens_deepAbsent_rt','ss.sens_deepAbsent_lt','ss.sens_stereoAbsent','ss.impressions','h.idno','h.dateAssess')
                ->leftjoin('hisdb.ot_upperextremity as h', function ($join){
                    $join = $join->on('h.mrn','=','ss.mrn');
                    $join = $join->on('h.episno','=','ss.episno');
                    $join = $join->where('h.compcode','=',session('compcode'));
                })
                ->where('ss.compcode','=',session('compcode'))
                ->where('ss.mrn','=',$mrn)
                ->where('ss.episno','=',$episno)
                ->where('h.dateAssess','=',$dateAssess)
                ->where('ss.idno_sensation','=',$upperExtremity->idno)
                ->first();
        // dd($sensation);
        // dd($upperExtremity->idno);

        // prehensive pattern
        $prehensive = DB::table('hisdb.ot_upperextremity_prehensive as p')
                ->select('p.idno','p.idno_prehensive','p.mrn','p.episno','p.prehensive_hook_rt','p.prehensive_hook_lt','p.prehensive_lateral_rt','p.prehensive_lateral_lt','p.prehensive_tip_rt','p.prehensive_tip_lt','p.prehensive_cylindrical_rt','p.prehensive_cylindrical_lt','p.prehensive_pad_rt','p.prehensive_pad_lt','p.prehensive_jaw_rt','p.prehensive_jaw_lt','p.prehensive_spherical_rt','p.prehensive_spherical_lt','p.impressions','h.idno','h.dateAssess')
                ->leftjoin('hisdb.ot_upperextremity as h', function ($join){
                    $join = $join->on('h.mrn','=','p.mrn');
                    $join = $join->on('h.episno','=','p.episno');
                    $join = $join->where('h.compcode','=',session('compcode'));
                })
                ->where('p.compcode','=',session('compcode'))
                ->where('p.mrn','=',$mrn)
                ->where('p.episno','=',$episno)
                ->where('h.dateAssess','=',$dateAssess)
                ->where('p.idno_prehensive','=',$upperExtremity->idno)
                ->first();
        // dd($prehensive);

        // skin condition
        $skin = DB::table('hisdb.ot_upperextremity_skin as sc')
                ->select('sc.idno','sc.idno_skin','sc.mrn','sc.episno','sc.skinCondition','sc.impressions','h.idno','h.dateAssess')
                ->leftjoin('hisdb.ot_upperextremity as h', function ($join){
                    $join = $join->on('h.mrn','=','sc.mrn');
                    $join = $join->on('h.episno','=','sc.episno');
                    $join = $join->where('h.compcode','=',session('compcode'));
                })
                ->where('sc.compcode','=',session('compcode'))
                ->where('sc.mrn','=',$mrn)
                ->where('sc.episno','=',$episno)
                ->where('h.dateAssess','=',$dateAssess)
                ->where('sc.idno_skin','=',$upperExtremity->idno)
                ->first();
        // dd($skin);

        // edema
        $edema = DB::table('hisdb.ot_upperextremity_edema as e')
                ->select('e.idno','e.idno_edema','e.mrn','e.episno','e.edema_noted_rt','e.edema_noted_lt','e.edema_new1','e.edema_new1_rt','e.edema_new1_lt','e.impressions','h.idno','h.dateAssess')
                ->leftjoin('hisdb.ot_upperextremity as h', function ($join){
                    $join = $join->on('h.mrn','=','e.mrn');
                    $join = $join->on('h.episno','=','e.episno');
                    $join = $join->where('h.compcode','=',session('compcode'));
                })
                ->where('e.compcode','=',session('compcode'))
                ->where('e.mrn','=',$mrn)
                ->where('e.episno','=',$episno)
                ->where('h.dateAssess','=',$dateAssess)
                ->where('e.idno_edema','=',$upperExtremity->idno)
                ->first();
        // dd($edema);

        // functional activities
        $func = DB::table('hisdb.ot_upperextremity_func as f')
                ->select('f.idno','f.idno_func','f.mrn','f.episno','f.func_writing_rt','f.func_writing_lt','f.func_pickCoins_rt','f.func_pickCoins_lt','f.func_pickPins_rt','f.func_pickPins_lt','f.func_button_rt','f.func_button_lt','f.func_feedSpoon_rt','f.func_feedSpoon_lt','f.func_feedHand_rt','f.func_feedHand_lt','f.impressions','h.idno','h.dateAssess')
                ->leftjoin('hisdb.ot_upperextremity as h', function ($join){
                    $join = $join->on('h.mrn','=','f.mrn');
                    $join = $join->on('h.episno','=','f.episno');
                    $join = $join->where('h.compcode','=',session('compcode'));
                })
                ->where('f.compcode','=',session('compcode'))
                ->where('f.mrn','=',$mrn)
                ->where('f.episno','=',$episno)
                ->where('h.dateAssess','=',$dateAssess)
                ->where('f.idno_func','=',$upperExtremity->idno)
                ->first();
        // dd($func);

        $company = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();
        
        return view('rehab.occupTherapy.upperExtremityChart_pdfmake',compact('upperExtremity','rof','imp_ROF','hand','imp_hand','strength','sensation','prehensive','skin','edema','func'));
        
    }

}