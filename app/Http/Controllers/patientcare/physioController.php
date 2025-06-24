<?php

namespace App\Http\Controllers\patientcare;

use Illuminate\Http\Request;
use stdClass;
use DB;
use Carbon\Carbon;
use App\Http\Controllers\defaultController;

class physioController extends defaultController
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function show(Request $request)
    {
        return view('patientcare.hisdb.phys.phys');
    }
    
    public function table(Request $request)
    {
        switch($request->action){
            case 'get_table_date_phys': // for table date and doctor name
                return $this->get_table_date_phys($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function form(Request $request)
    {
        DB::enableQueryLog();
        switch($request->action){
            // case 'save_table_phys':
            //     switch($request->oper){
            //         case 'add':
            //             return $this->add($request);
            //         case 'edit':
            //             return $this->edit($request);
            //         default:
            //             return 'error happen..';
            //     }
            
            case 'save_table_phys_ncase':
                switch($request->oper){
                    case 'add':
                        return $this->add($request);
                    case 'edit':
                        return $this->edit($request);
                    default:
                        return 'error happen..';
                }
            
            case 'get_table_phys':
                return $this->get_table_phys($request);
            
            case 'get_table_phys_ncase':
                return $this->get_table_phys_ncase($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function add(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $episode = DB::table('hisdb.episode')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno);
            
            $episode_obj = $episode->first();
            
            if($request->category == 'Rehabilitation'){
                $episode->update(['stats_rehab' => 'SEEN']);
            }else if($request->category == 'Physioteraphy'){
                $episode->update(['stats_physio' => 'SEEN']);
            }
            
            if(!empty($request->referdiet) && $request->referdiet == 'yes'){
                $episode->update(['reff_diet' => 'YES']);
            }else{
                $episode->update(['reff_diet' => null]);
            }
            
            DB::table('hisdb.patrehabncase')
                ->insert([
                    'ques1' => $request->ques1,
                    'ques2' => $request->ques2,
                    'ques3' => $request->ques3,
                    'ques4' => $request->ques4,
                    'ques5' => $request->ques5,
                    'ques6' => $request->ques6,
                    'ques7' => $request->ques7,
                    'ques8' => $request->ques8,
                    'ques9' => $request->ques9,
                    'ques10' => $request->ques10,
                    'ques11' => $request->ques11,
                    'ques12' => $request->ques12,
                    'ques13' => $request->ques13,
                    'ques14' => $request->ques14,
                    'ques15' => $request->ques15,
                    'ques16' => $request->ques16,
                    'ques17' => $request->ques17,
                    'ques18' => $request->ques18,
                    'ques19' => $request->ques19,
                    'ques20' => $request->ques20,
                    'ques21' => $request->ques21,
                    'ques22' => $request->ques22,
                    'ques23' => $request->ques23,
                    'ques24' => $request->ques24,
                    'quesdet1' => $request->quesdet1,
                    'quesdet2' => $request->quesdet2,
                    'quesdet3' => $request->quesdet3,
                    'quesdet4' => $request->quesdet4,
                    'quesdet5' => $request->quesdet5,
                    'quesdet6' => $request->quesdet6,
                    'quesdet7' => $request->quesdet7,
                    'quesdet8' => $request->quesdet8,
                    'quesdet9' => $request->quesdet9,
                    'quesdet10' => $request->quesdet10,
                    'quesdet11' => $request->quesdet11,
                    'quesdet12' => $request->quesdet12,
                    'quesdet13' => $request->quesdet13,
                    'quesdet14' => $request->quesdet14,
                    'quesdet15' => $request->quesdet15,
                    'quesdet16' => $request->quesdet16,
                    'quesdet17' => $request->quesdet17,
                    'quesdet18' => $request->quesdet18,
                    'quesdet19' => $request->quesdet19,
                    'quesdet20' => $request->quesdet20,
                    'quesdet21' => $request->quesdet21,
                    'quesdet22' => $request->quesdet22,
                    'quesdet23' => $request->quesdet23,
                    'quesdet24' => $request->quesdet24,
                    'presenthistory' => $request->presenthistory,
                    'pasthistory' => $request->pasthistory,
                    'mh' => $request->mh,
                    'sh' => $request->sh,
                    'investigation' => $request->investigation,
                    'function_' => $request->function_,
                    'drmgmt' => $request->drmgmt,
                    'test' => $request->test,
                    'neuro' => $request->neuro,
                    'analysis' => $request->analysis,
                    'long_' => $request->long_,
                    'evaluation' => $request->evaluation,
                    'category' => $request->category,
                    'risk' => $request->risk,
                    'history' => $request->history,
                    'posassmt' => $request->posassmt,
                    'electrodg' => $request->electrodg,
                    'protocol' => $request->protocol,
                    'equipment' => $request->equipment,
                    'recommendation' => $request->recommendation,
                    'vas_ncase' => $request->vas_ncase,
                    'aggr_ncase' => $request->aggr_ncase,
                    'easing_ncase' => $request->easing_ncase,
                    'pain_ncase' => $request->pain_ncase,
                    'behaviour_ncase' => $request->behaviour_ncase,
                    'irritability_ncase' => $request->irritability_ncase,
                    'severity_ncase' => $request->severity_ncase,
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn,
                    'upduser'  => session('username'),
                    'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                ]);
            
            DB::table('hisdb.patrehab')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn,
                    'episno' => $request->episno_phys,
                    'category' => $request->category,
                    'complain' => $request->complain,
                    'genobserv' => $request->genobserv,
                    'localobserv' => $request->localobserv,
                    'rom' => $request->rom,
                    'mmt' => $request->mmt,
                    'palpation' => $request->palpation,
                    'plan_' => $request->plan_,
                    'reassesment' => $request->reassesment,
                    'vas' => $request->vas,
                    'aggr' => $request->aggr,
                    'easing' => $request->easing,
                    'pain' => $request->pain,
                    'behaviour' => $request->behaviour,
                    'irritability' => $request->irritability,
                    'severity' => $request->severity,
                    'recorddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'recordtime' => Carbon::now("Asia/Kuala_Lumpur"),
                    'adduser'  => session('username'),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                ]);
            
            $pat_physio = DB::table('hisdb.pat_physio')
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno_phys)
                        ->where('compcode','=',session('compcode'));
            
            if($pat_physio->exists()){
                $pat_physio
                    ->update([
                        'findings' => $request->findings,
                        // 'treatment' => $request->phy_treatment,
                        'tr_physio' => $request->tr_physio,
                        'tr_occuptherapy' => $request->tr_occuptherapy,
                        'tr_respiphysio' => $request->tr_respiphysio,
                        'tr_neuro' => $request->tr_neuro,
                        'tr_splint' => $request->tr_splint,
                        // 'upduser'  => strtoupper($request->phy_lastuser),
                        // 'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        // 'lastuser' => strtoupper($request->phy_lastuser),
                        // 'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        // 'computerid' => session('computerid'),
                    ]);
            }else{
                DB::table('hisdb.pat_physio')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno_phys,
                        'findings' => $request->findings,
                        // 'treatment' => $request->phy_treatment,
                        'tr_physio' => $request->tr_physio,
                        'tr_occuptherapy' => $request->tr_occuptherapy,
                        'tr_respiphysio' => $request->tr_respiphysio,
                        'tr_neuro' => $request->tr_neuro,
                        'tr_splint' => $request->tr_splint,
                        // 'adduser'  => strtoupper($request->phy_lastuser),
                        // 'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        // 'lastuser' => strtoupper($request->phy_lastuser),
                        // 'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        // 'computerid' => session('computerid'),
                    ]);
            }
            
            DB::commit();
            
            $responce = new stdClass();
            $responce->mrn = $request->mrn;
            return json_encode($responce);
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function edit(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $episode = DB::table('hisdb.episode')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno);
            
            $episode_obj = $episode->first();
            
            if($request->category == 'Rehabilitation'){
                $episode->update(['stats_rehab' => 'SEEN']);
            }else if($request->category == 'Physioteraphy'){
                $episode->update(['stats_physio' => 'SEEN']);
            }
            
            if(!empty($request->referdiet) && $request->referdiet == 'yes'){
                $episode->update(['reff_diet' => 'YES']);
            }else{
                $episode->update(['reff_diet' => null]);
            }
            
            DB::table('hisdb.patrehabncase')
                ->where('compcode','=',session('compcode'))
                ->where('mrn','=',$request->mrn)
                ->update([
                    'ques1' => $request->ques1,
                    'ques2' => $request->ques2,
                    'ques3' => $request->ques3,
                    'ques4' => $request->ques4,
                    'ques5' => $request->ques5,
                    'ques6' => $request->ques6,
                    'ques7' => $request->ques7,
                    'ques8' => $request->ques8,
                    'ques9' => $request->ques9,
                    'ques10' => $request->ques10,
                    'ques11' => $request->ques11,
                    'ques12' => $request->ques12,
                    'ques13' => $request->ques13,
                    'ques14' => $request->ques14,
                    'ques15' => $request->ques15,
                    'ques16' => $request->ques16,
                    'ques17' => $request->ques17,
                    'ques18' => $request->ques18,
                    'ques19' => $request->ques19,
                    'ques20' => $request->ques20,
                    'ques21' => $request->ques21,
                    'ques22' => $request->ques22,
                    'ques23' => $request->ques23,
                    'ques24' => $request->ques24,
                    'quesdet1' => $request->quesdet1,
                    'quesdet2' => $request->quesdet2,
                    'quesdet3' => $request->quesdet3,
                    'quesdet4' => $request->quesdet4,
                    'quesdet5' => $request->quesdet5,
                    'quesdet6' => $request->quesdet6,
                    'quesdet7' => $request->quesdet7,
                    'quesdet8' => $request->quesdet8,
                    'quesdet9' => $request->quesdet9,
                    'quesdet10' => $request->quesdet10,
                    'quesdet11' => $request->quesdet11,
                    'quesdet12' => $request->quesdet12,
                    'quesdet13' => $request->quesdet13,
                    'quesdet14' => $request->quesdet14,
                    'quesdet15' => $request->quesdet15,
                    'quesdet16' => $request->quesdet16,
                    'quesdet17' => $request->quesdet17,
                    'quesdet18' => $request->quesdet18,
                    'quesdet19' => $request->quesdet19,
                    'quesdet20' => $request->quesdet20,
                    'quesdet21' => $request->quesdet21,
                    'quesdet22' => $request->quesdet22,
                    'quesdet23' => $request->quesdet23,
                    'quesdet24' => $request->quesdet24,
                    'presenthistory' => $request->presenthistory,
                    'pasthistory' => $request->pasthistory,
                    'mh' => $request->mh,
                    'sh' => $request->sh,
                    'investigation' => $request->investigation,
                    'function_' => $request->function_,
                    'drmgmt' => $request->drmgmt,
                    'test' => $request->test,
                    'neuro' => $request->neuro,
                    'analysis' => $request->analysis,
                    'long_' => $request->long_,
                    'evaluation' => $request->evaluation,
                    'risk' => $request->risk,
                    'category' => $request->category,
                    'history' => $request->history,
                    'posassmt' => $request->posassmt,
                    'electrodg' => $request->electrodg,
                    'protocol' => $request->protocol,
                    'equipment' => $request->equipment,
                    'recommendation' => $request->recommendation,
                    'vas_ncase' => $request->vas_ncase,
                    'aggr_ncase' => $request->aggr_ncase,
                    'easing_ncase' => $request->easing_ncase,
                    'pain_ncase' => $request->pain_ncase,
                    'behaviour_ncase' => $request->behaviour_ncase,
                    'irritability_ncase' => $request->irritability_ncase,
                    'severity_ncase' => $request->severity_ncase,
                    'upduser'  => session('username'),
                    'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                ]);
            
            DB::table('hisdb.patrehab')
                ->where('compcode','=',session('compcode'))
                ->where('mrn','=',$request->mrn)
                ->where('episno','=',$request->episno)
                ->update([
                    'category' => $request->category,
                    'complain' => $request->complain,
                    'genobserv' => $request->genobserv,
                    'localobserv' => $request->localobserv,
                    'rom' => $request->rom,
                    'mmt' => $request->mmt,
                    'palpation' => $request->palpation,
                    'plan_' => $request->plan_,
                    'reassesment' => $request->reassesment,
                    'vas' => $request->vas,
                    'aggr' => $request->aggr,
                    'easing' => $request->easing,
                    'pain' => $request->pain,
                    'behaviour' => $request->behaviour,
                    'irritability' => $request->irritability,
                    'severity' => $request->severity,
                ]);
            
            $pat_physio = DB::table('hisdb.pat_physio')
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno)
                        ->where('compcode','=',session('compcode'));
            
            if($pat_physio->exists()){
                $pat_physio
                    ->update([
                        'findings' => $request->findings,
                        // 'treatment' => $request->phy_treatment,
                        'tr_physio' => $request->tr_physio,
                        'tr_occuptherapy' => $request->tr_occuptherapy,
                        'tr_respiphysio' => $request->tr_respiphysio,
                        'tr_neuro' => $request->tr_neuro,
                        'tr_splint' => $request->tr_splint,
                        // 'upduser'  => strtoupper($request->phy_lastuser),
                        // 'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        // 'lastuser' => strtoupper($request->phy_lastuser),
                        // 'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        // 'computerid' => session('computerid'),
                    ]);
            }else{
                DB::table('hisdb.pat_physio')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'findings' => $request->findings,
                        // 'treatment' => $request->phy_treatment,
                        'tr_physio' => $request->tr_physio,
                        'tr_occuptherapy' => $request->tr_occuptherapy,
                        'tr_respiphysio' => $request->tr_respiphysio,
                        'tr_neuro' => $request->tr_neuro,
                        'tr_splint' => $request->tr_splint,
                        // 'adduser'  => strtoupper($request->phy_lastuser),
                        // 'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        // 'lastuser' => strtoupper($request->phy_lastuser),
                        // 'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        // 'computerid' => session('computerid'),
                    ]);
            }
            
            // $queries = DB::getQueryLog();
            // dump($queries);
            
            DB::commit();
            
            $responce = new stdClass();
            $responce->mrn = $request->mrn_phys;
            
            return json_encode($responce);
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function get_table_phys(Request $request){
        
        $patrehab_obj = DB::table('hisdb.patrehab')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno);
        
        $patrehabncase_obj = DB::table('hisdb.patrehabncase')
                            // ->select('presenthistory','pasthistory','mh','sh','investigation','function_','drmgmt','test','neuro','analysis','long_','evaluation')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn);
        
        $responce = new stdClass();
        
        if($patrehab_obj->exists()){
            $responce->patrehab = $patrehab_obj->first();
        }
        
        if($patrehabncase_obj->exists()){
            $responce->patrehabncase = $patrehabncase_obj->first();
        }
        
        return json_encode($responce);
        
    }
    
    public function get_table_date_phys(Request $request){
        
        $responce = new stdClass();
        
        $phys_obj = DB::table('hisdb.patrehab as p')
                    ->select('e.mrn','e.episno','p.recordtime','p.recorddate','p.adduser','p.adddate')
                    ->leftJoin('hisdb.episode as e', function ($join) use ($request){
                        $join = $join->on('p.mrn','=','e.mrn');
                        $join = $join->on('p.episno','=','e.episno');
                        $join = $join->on('p.compcode','=','e.compcode');
                    })
                    ->where('e.compcode','=',session('compcode'))
                    ->where('e.mrn','=',$request->mrn);
        
        if($request->type == 'Current'){
            $phys_obj = $phys_obj->where('p.episno','=',$request->episno)->orderBy('p.adddate','desc');
        }else{
            $phys_obj = $phys_obj->orderBy('p.adddate','desc');
        }
        
        if($phys_obj->exists()){
            $phys_obj = $phys_obj->get();
            
            $data = [];
            
            foreach($phys_obj as $key => $value){
                if(!empty($value->recorddate)){
                    $date['date'] = Carbon::createFromFormat('Y-m-d', $value->recorddate)->format('d-m-Y').' '.$value->recordtime;
                }else{
                    $date['date'] = '-';
                }
                $date['mrn'] = $value->mrn;
                $date['episno'] = $value->episno;
                if(!empty($value->adduser)){
                    $date['adduser'] = $value->adduser;
                }else{
                    $date['adduser'] =  '-';
                }
                
                array_push($data,$date);
            }
            
            $responce->data = $data;
        }else{
            $responce->data = [];
        }
        
        return json_encode($responce);
        
    }
    
    public function add_ncase(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            if(!empty($request->referdiet) && $request->referdiet == 'yes'){
                DB::table('hisdb.episode')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$request->mrn)
                    ->where('episno','=',$request->episno)
                    ->update(['reff_diet' => 'YES']);
            }else{
                DB::table('hisdb.episode')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$request->mrn)
                    ->where('episno','=',$request->episno)
                    ->update(['reff_diet' => null]);
            }
            
            $patrehabncase = DB::table('hisdb.patrehabncase')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn);
            
            if($patrehabncase->exists()){
                DB::table('hisdb.patrehabncase')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$request->mrn)
                    ->update([
                        'ques1' => $request->ques1,
                        'ques2' => $request->ques2,
                        'ques3' => $request->ques3,
                        'ques4' => $request->ques4,
                        'ques5' => $request->ques5,
                        'ques6' => $request->ques6,
                        'ques7' => $request->ques7,
                        'ques8' => $request->ques8,
                        'ques9' => $request->ques9,
                        'ques10' => $request->ques10,
                        'ques11' => $request->ques11,
                        'ques12' => $request->ques12,
                        'ques13' => $request->ques13,
                        'ques14' => $request->ques14,
                        'ques15' => $request->ques15,
                        'ques16' => $request->ques16,
                        'ques17' => $request->ques17,
                        'ques18' => $request->ques18,
                        'ques19' => $request->ques19,
                        'ques20' => $request->ques20,
                        'ques21' => $request->ques21,
                        'ques22' => $request->ques22,
                        'ques23' => $request->ques23,
                        'ques24' => $request->ques24,
                        'quesdet1' => $request->quesdet1,
                        'quesdet2' => $request->quesdet2,
                        'quesdet3' => $request->quesdet3,
                        'quesdet4' => $request->quesdet4,
                        'quesdet5' => $request->quesdet5,
                        'quesdet6' => $request->quesdet6,
                        'quesdet7' => $request->quesdet7,
                        'quesdet8' => $request->quesdet8,
                        'quesdet9' => $request->quesdet9,
                        'quesdet10' => $request->quesdet10,
                        'quesdet11' => $request->quesdet11,
                        'quesdet12' => $request->quesdet12,
                        'quesdet13' => $request->quesdet13,
                        'quesdet14' => $request->quesdet14,
                        'quesdet15' => $request->quesdet15,
                        'quesdet16' => $request->quesdet16,
                        'quesdet17' => $request->quesdet17,
                        'quesdet18' => $request->quesdet18,
                        'quesdet19' => $request->quesdet19,
                        'quesdet20' => $request->quesdet20,
                        'quesdet21' => $request->quesdet21,
                        'quesdet22' => $request->quesdet22,
                        'quesdet23' => $request->quesdet23,
                        'quesdet24' => $request->quesdet24,
                        'category' => $request->category,
                        'risk' => $request->risk,
                        'history' => $request->history,
                        'posassmt' => $request->posassmt,
                        'electrodg' => $request->electrodg,
                        'protocol' => $request->protocol,
                        'equipment' => $request->equipment,
                        'recommendation' => $request->recommendation,
                        'vas' => $request->vas,
                        'aggr' => $request->aggr,
                        'easing' => $request->easing,
                        'pain' => $request->pain,
                        'behaviour' => $request->behaviour,
                        'irritability' => $request->irritability,
                        'severity' => $request->severity,
                        'upduser'  => session('username'),
                        'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }else{
                DB::table('hisdb.patrehabncase')
                    ->insert([
                        'ques1' => $request->ques1,
                        'ques2' => $request->ques2,
                        'ques3' => $request->ques3,
                        'ques4' => $request->ques4,
                        'ques5' => $request->ques5,
                        'ques6' => $request->ques6,
                        'ques7' => $request->ques7,
                        'ques8' => $request->ques8,
                        'ques9' => $request->ques9,
                        'ques10' => $request->ques10,
                        'ques11' => $request->ques11,
                        'ques12' => $request->ques12,
                        'ques13' => $request->ques13,
                        'ques14' => $request->ques14,
                        'ques15' => $request->ques15,
                        'ques16' => $request->ques16,
                        'ques17' => $request->ques17,
                        'ques18' => $request->ques18,
                        'ques19' => $request->ques19,
                        'ques20' => $request->ques20,
                        'ques21' => $request->ques21,
                        'ques22' => $request->ques22,
                        'ques23' => $request->ques23,
                        'ques24' => $request->ques24,
                        'quesdet1' => $request->quesdet1,
                        'quesdet2' => $request->quesdet2,
                        'quesdet3' => $request->quesdet3,
                        'quesdet4' => $request->quesdet4,
                        'quesdet5' => $request->quesdet5,
                        'quesdet6' => $request->quesdet6,
                        'quesdet7' => $request->quesdet7,
                        'quesdet8' => $request->quesdet8,
                        'quesdet9' => $request->quesdet9,
                        'quesdet10' => $request->quesdet10,
                        'quesdet11' => $request->quesdet11,
                        'quesdet12' => $request->quesdet12,
                        'quesdet13' => $request->quesdet13,
                        'quesdet14' => $request->quesdet14,
                        'quesdet15' => $request->quesdet15,
                        'quesdet16' => $request->quesdet16,
                        'quesdet17' => $request->quesdet17,
                        'quesdet18' => $request->quesdet18,
                        'quesdet19' => $request->quesdet19,
                        'quesdet20' => $request->quesdet20,
                        'quesdet21' => $request->quesdet21,
                        'quesdet22' => $request->quesdet22,
                        'quesdet23' => $request->quesdet23,
                        'quesdet24' => $request->quesdet24,
                        'category' => $request->category,
                        'risk' => $request->risk,
                        'history' => $request->history,
                        'posassmt' => $request->posassmt,
                        'electrodg' => $request->electrodg,
                        'protocol' => $request->protocol,
                        'equipment' => $request->equipment,
                        'recommendation' => $request->recommendation,
                        'vas' => $request->vas,
                        'aggr' => $request->aggr,
                        'easing' => $request->easing,
                        'pain' => $request->pain,
                        'behaviour' => $request->behaviour,
                        'irritability' => $request->irritability,
                        'severity' => $request->severity,
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'upduser'  => session('username'),
                        'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }
            
            DB::commit();
            
            $responce = new stdClass();
            $responce->mrn = $request->mrn_phys;
            return json_encode($responce);
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function edit_ncase(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            if(!empty($request->referdiet) && $request->referdiet == 'yes'){
                DB::table('hisdb.episode')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$request->mrn)
                    ->where('episno','=',$request->episno)
                    ->update(['reff_diet' => 'YES']);
            }else{
                DB::table('hisdb.episode')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$request->mrn)
                    ->where('episno','=',$request->episno)
                    ->update(['reff_diet' => null]);
            }
            
            DB::table('hisdb.patrehabncase')
                ->where('compcode','=',session('compcode'))
                ->where('mrn','=',$request->mrn)
                ->update([
                    'ques1' => $request->ques1,
                    'ques2' => $request->ques2,
                    'ques3' => $request->ques3,
                    'ques4' => $request->ques4,
                    'ques5' => $request->ques5,
                    'ques6' => $request->ques6,
                    'ques7' => $request->ques7,
                    'ques8' => $request->ques8,
                    'ques9' => $request->ques9,
                    'ques10' => $request->ques10,
                    'ques11' => $request->ques11,
                    'ques12' => $request->ques12,
                    'ques13' => $request->ques13,
                    'ques14' => $request->ques14,
                    'ques15' => $request->ques15,
                    'ques16' => $request->ques16,
                    'ques17' => $request->ques17,
                    'ques18' => $request->ques18,
                    'ques19' => $request->ques19,
                    'ques20' => $request->ques20,
                    'ques21' => $request->ques21,
                    'ques22' => $request->ques22,
                    'ques23' => $request->ques23,
                    'ques24' => $request->ques24,
                    'quesdet1' => $request->quesdet1,
                    'quesdet2' => $request->quesdet2,
                    'quesdet3' => $request->quesdet3,
                    'quesdet4' => $request->quesdet4,
                    'quesdet5' => $request->quesdet5,
                    'quesdet6' => $request->quesdet6,
                    'quesdet7' => $request->quesdet7,
                    'quesdet8' => $request->quesdet8,
                    'quesdet9' => $request->quesdet9,
                    'quesdet10' => $request->quesdet10,
                    'quesdet11' => $request->quesdet11,
                    'quesdet12' => $request->quesdet12,
                    'quesdet13' => $request->quesdet13,
                    'quesdet14' => $request->quesdet14,
                    'quesdet15' => $request->quesdet15,
                    'quesdet16' => $request->quesdet16,
                    'quesdet17' => $request->quesdet17,
                    'quesdet18' => $request->quesdet18,
                    'quesdet19' => $request->quesdet19,
                    'quesdet20' => $request->quesdet20,
                    'quesdet21' => $request->quesdet21,
                    'quesdet22' => $request->quesdet22,
                    'quesdet23' => $request->quesdet23,
                    'quesdet24' => $request->quesdet24,
                    'risk' => $request->risk,
                    'category' => $request->category,
                    'history' => $request->history,
                    'posassmt' => $request->posassmt,
                    'electrodg' => $request->electrodg,
                    'protocol' => $request->protocol,
                    'equipment' => $request->equipment,
                    'recommendation' => $request->recommendation,
                    'vas' => $request->vas,
                    'aggr' => $request->aggr,
                    'easing' => $request->easing,
                    'pain' => $request->pain,
                    'behaviour' => $request->behaviour,
                    'irritability' => $request->irritability,
                    'severity' => $request->severity,
                    'upduser'  => session('username'),
                    'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                ]);
            
            DB::commit();
            
            $responce = new stdClass();
            $responce->mrn = $request->mrn_phys;
            return json_encode($responce);
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function get_table_phys_ncase(Request $request){
        
        $patrehab_ncase_obj = DB::table('hisdb.patrehabncase')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn);
        
        $pat_physio_obj = DB::table('hisdb.pat_physio')
                        ->select('findings','tr_physio','tr_occuptherapy','tr_respiphysio','tr_neuro','tr_splint')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno);
        
        $responce = new stdClass();
        
        if($patrehab_ncase_obj->exists()){
            $patrehab_ncase_obj = $patrehab_ncase_obj->first();
            $responce->patrehab_ncase = $patrehab_ncase_obj;
        }
        
        if($pat_physio_obj->exists()){
            $pat_physio_obj = $pat_physio_obj->first();
            $responce->pat_physio = $pat_physio_obj;
        }
        
        return json_encode($responce);
        
    }
    
}