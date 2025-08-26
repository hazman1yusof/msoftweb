<?php

namespace App\Http\Controllers\patientcare;

use Illuminate\Http\Request;
use stdClass;
use DB;
use Carbon\Carbon;
use App\Http\Controllers\defaultController;
use Storage;

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
                    'addNotes' => $request->addNotes,
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
            
            // perkeso starts
            DB::table('hisdb.phy_neuroassessment')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn,
                    'episno' => $request->episno,
                    'type' => $request->type,
                    // 'entereddate' => $request->entereddate,
                    'objective' => $request->objective,
                    'painscore' => $request->painscore,
                    'painType' => $request->painType,
                    'severityBC' => $request->severityBC,
                    'irritabilityBC' => $request->irritabilityBC,
                    'painLocation' => $request->painLocation,
                    'subluxation' => $request->subluxation,
                    'palpationBC' => $request->palpationBC,
                    'impressionBC' => $request->impressionBC,
                    'superficialR' => $request->superficialR,
                    'superficialL' => $request->superficialL,
                    'superficialSpec' => $request->superficialSpec,
                    'deepR' => $request->deepR,
                    'deepL' => $request->deepL,
                    'deepSpec' => $request->deepSpec,
                    'numbnessR' => $request->numbnessR,
                    'numbnessL' => $request->numbnessL,
                    'numbnessSpec' => $request->numbnessSpec,
                    'paresthesiaR' => $request->paresthesiaR,
                    'paresthesiaL' => $request->paresthesiaL,
                    'paresthesiaSpec' => $request->paresthesiaSpec,
                    'otherR' => $request->otherR,
                    'otherL' => $request->otherL,
                    'otherSpec' => $request->otherSpec,
                    'impressionSens' => $request->impressionSens,
                    'muscleUL' => $request->muscleUL,
                    'muscleLL' => $request->muscleLL,
                    'impressionMAS' => $request->impressionMAS,
                    'btrRT' => $request->btrRT,
                    'btrLT' => $request->btrLT,
                    'ttrRT' => $request->ttrRT,
                    'ttrLT' => $request->ttrLT,
                    'ktrRT' => $request->ktrRT,
                    'ktrLT' => $request->ktrLT,
                    'atrRT' => $request->atrRT,
                    'atrLT' => $request->atrLT,
                    'babinskyRT' => $request->babinskyRT,
                    'babinskyLT' => $request->babinskyLT,
                    'impressionDTR' => $request->impressionDTR,
                    'fingerTestR' => $request->fingerTestR,
                    'fingerTestL' => $request->fingerTestL,
                    'heelTestR' => $request->heelTestR,
                    'heelTestL' => $request->heelTestL,
                    'impressionCoord' => $request->impressionCoord,
                    'transferInit' => $request->transferInit,
                    'transferProg' => $request->transferProg,
                    'transferFin' => $request->transferFin,
                    'suptoSideInit' => $request->suptoSideInit,
                    'suptoSideProg' => $request->suptoSideProg,
                    'suptoSideFin' => $request->suptoSideFin,
                    'sideToSitInit' => $request->sideToSitInit,
                    'sideToSitProg' => $request->sideToSitProg,
                    'sideToSitFin' => $request->sideToSitFin,
                    'sittInit' => $request->sittInit,
                    'sittProg' => $request->sittProg,
                    'sittFin' => $request->sittFin,
                    'sitToStdInit' => $request->sitToStdInit,
                    'sitToStdProg' => $request->sitToStdProg,
                    'sitToStdFin' => $request->sitToStdFin,
                    'stdInit' => $request->stdInit,
                    'stdProg' => $request->stdProg,
                    'stdFin' => $request->stdFin,
                    'shiftInit' => $request->shiftInit,
                    'shiftProg' => $request->shiftProg,
                    'shiftFin' => $request->shiftFin,
                    'ambulationInit' => $request->ambulationInit,
                    'ambulationProg' => $request->ambulationProg,
                    'ambulationFin' => $request->ambulationFin,
                    'impressionFA' => $request->impressionFA,
                    'summary' => $request->summary,
                    'adduser'  => session('username'),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'computerid' => session('computerid'),
                ]);
            
            DB::table('hisdb.phy_romaffectedside')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn,
                    'episno' => $request->episno,
                    'type' => $request->type,
                    // 'entereddate' => $request->entereddate,
                    'romAffectedSide' => $request->romAffectedSide,
                    'aShlderFlxInitP' => $request->aShlderFlxInitP,
                    'aShlderFlxInitA' => $request->aShlderFlxInitA,
                    'aShlderFlxProgP' => $request->aShlderFlxProgP,
                    'aShlderFlxProgA' => $request->aShlderFlxProgA,
                    'aShlderFlxFinP' => $request->aShlderFlxFinP,
                    'aShlderFlxFinA' => $request->aShlderFlxFinA,
                    'aShlderExtInitP' => $request->aShlderExtInitP,
                    'aShlderExtInitA' => $request->aShlderExtInitA,
                    'aShlderExtProgP' => $request->aShlderExtProgP,
                    'aShlderExtProgA' => $request->aShlderExtProgA,
                    'aShlderExtFinP' => $request->aShlderExtFinP,
                    'aShlderExtFinA' => $request->aShlderExtFinA,
                    'aShlderAbdInitP' => $request->aShlderAbdInitP,
                    'aShlderAbdInitA' => $request->aShlderAbdInitA,
                    'aShlderAbdProgP' => $request->aShlderAbdProgP,
                    'aShlderAbdProgA' => $request->aShlderAbdProgA,
                    'aShlderAbdFinP' => $request->aShlderAbdFinP,
                    'aShlderAbdFinA' => $request->aShlderAbdFinA,
                    // 'aShlderAddInitP' => $request->aShlderAddInitP,
                    // 'aShlderAddInitA' => $request->aShlderAddInitA,
                    // 'aShlderAddProgP' => $request->aShlderAddProgP,
                    // 'aShlderAddProgA' => $request->aShlderAddProgA,
                    // 'aShlderAddFinP' => $request->aShlderAddFinP,
                    // 'aShlderAddFinA' => $request->aShlderAddFinA,
                    'aShlderIntRotInitP' => $request->aShlderIntRotInitP,
                    'aShlderIntRotInitA' => $request->aShlderIntRotInitA,
                    'aShlderIntRotProgP' => $request->aShlderIntRotProgP,
                    'aShlderIntRotProgA' => $request->aShlderIntRotProgA,
                    'aShlderIntRotFinP' => $request->aShlderIntRotFinP,
                    'aShlderIntRotFinA' => $request->aShlderIntRotFinA,
                    'aShlderExtRotInitP' => $request->aShlderExtRotInitP,
                    'aShlderExtRotInitA' => $request->aShlderExtRotInitA,
                    'aShlderExtRotProgP' => $request->aShlderExtRotProgP,
                    'aShlderExtRotProgA' => $request->aShlderExtRotProgA,
                    'aShlderExtRotFinP' => $request->aShlderExtRotFinP,
                    'aShlderExtRotFinA' => $request->aShlderExtRotFinA,
                    'aElbowFlxInitP' => $request->aElbowFlxInitP,
                    'aElbowFlxInitA' => $request->aElbowFlxInitA,
                    'aElbowFlxProgP' => $request->aElbowFlxProgP,
                    'aElbowFlxProgA' => $request->aElbowFlxProgA,
                    'aElbowFlxFinP' => $request->aElbowFlxFinP,
                    'aElbowFlxFinA' => $request->aElbowFlxFinA,
                    'aElbowExtInitP' => $request->aElbowExtInitP,
                    'aElbowExtInitA' => $request->aElbowExtInitA,
                    'aElbowExtProgP' => $request->aElbowExtProgP,
                    'aElbowExtProgA' => $request->aElbowExtProgA,
                    'aElbowExtFinP' => $request->aElbowExtFinP,
                    'aElbowExtFinA' => $request->aElbowExtFinA,
                    // 'aElbowProInitP' => $request->aElbowProInitP,
                    // 'aElbowProInitA' => $request->aElbowProInitA,
                    // 'aElbowProProgP' => $request->aElbowProProgP,
                    // 'aElbowProProgA' => $request->aElbowProProgA,
                    // 'aElbowProFinP' => $request->aElbowProFinP,
                    // 'aElbowProFinA' => $request->aElbowProFinA,
                    // 'aElbowSupInitP' => $request->aElbowSupInitP,
                    // 'aElbowSupInitA' => $request->aElbowSupInitA,
                    // 'aElbowSupProgP' => $request->aElbowSupProgP,
                    // 'aElbowSupProgA' => $request->aElbowSupProgA,
                    // 'aElbowSupFinP' => $request->aElbowSupFinP,
                    // 'aElbowSupFinA' => $request->aElbowSupFinA,
                    'aWristFlxInitP' => $request->aWristFlxInitP,
                    'aWristFlxInitA' => $request->aWristFlxInitA,
                    'aWristFlxProgP' => $request->aWristFlxProgP,
                    'aWristFlxProgA' => $request->aWristFlxProgA,
                    'aWristFlxFinP' => $request->aWristFlxFinP,
                    'aWristFlxFinA' => $request->aWristFlxFinA,
                    'aWristExtInitP' => $request->aWristExtInitP,
                    'aWristExtInitA' => $request->aWristExtInitA,
                    'aWristExtProgP' => $request->aWristExtProgP,
                    'aWristExtProgA' => $request->aWristExtProgA,
                    'aWristExtFinP' => $request->aWristExtFinP,
                    'aWristExtFinA' => $request->aWristExtFinA,
                    // 'aWristRadInitP' => $request->aWristRadInitP,
                    // 'aWristRadInitA' => $request->aWristRadInitA,
                    // 'aWristRadProgP' => $request->aWristRadProgP,
                    // 'aWristRadProgA' => $request->aWristRadProgA,
                    // 'aWristRadFinP' => $request->aWristRadFinP,
                    // 'aWristRadFinA' => $request->aWristRadFinA,
                    // 'aWristUlnarInitP' => $request->aWristUlnarInitP,
                    // 'aWristUlnarInitA' => $request->aWristUlnarInitA,
                    // 'aWristUlnarProgP' => $request->aWristUlnarProgP,
                    // 'aWristUlnarProgA' => $request->aWristUlnarProgA,
                    // 'aWristUlnarFinP' => $request->aWristUlnarFinP,
                    // 'aWristUlnarFinA' => $request->aWristUlnarFinA,
                    'aHipFlxInitP' => $request->aHipFlxInitP,
                    'aHipFlxInitA' => $request->aHipFlxInitA,
                    'aHipFlxProgP' => $request->aHipFlxProgP,
                    'aHipFlxProgA' => $request->aHipFlxProgA,
                    'aHipFlxFinP' => $request->aHipFlxFinP,
                    'aHipFlxFinA' => $request->aHipFlxFinA,
                    'aHipExtInitP' => $request->aHipExtInitP,
                    'aHipExtInitA' => $request->aHipExtInitA,
                    'aHipExtProgP' => $request->aHipExtProgP,
                    'aHipExtProgA' => $request->aHipExtProgA,
                    'aHipExtFinP' => $request->aHipExtFinP,
                    'aHipExtFinA' => $request->aHipExtFinA,
                    'aHipAbdInitP' => $request->aHipAbdInitP,
                    'aHipAbdInitA' => $request->aHipAbdInitA,
                    'aHipAbdProgP' => $request->aHipAbdProgP,
                    'aHipAbdProgA' => $request->aHipAbdProgA,
                    'aHipAbdFinP' => $request->aHipAbdFinP,
                    'aHipAbdFinA' => $request->aHipAbdFinA,
                    // 'aHipAddInitP' => $request->aHipAddInitP,
                    // 'aHipAddInitA' => $request->aHipAddInitA,
                    // 'aHipAddProgP' => $request->aHipAddProgP,
                    // 'aHipAddProgA' => $request->aHipAddProgA,
                    // 'aHipAddFinP' => $request->aHipAddFinP,
                    // 'aHipAddFinA' => $request->aHipAddFinA,
                    'aHipIntRotInitP' => $request->aHipIntRotInitP,
                    'aHipIntRotInitA' => $request->aHipIntRotInitA,
                    'aHipIntRotProgP' => $request->aHipIntRotProgP,
                    'aHipIntRotProgA' => $request->aHipIntRotProgA,
                    'aHipIntRotFinP' => $request->aHipIntRotFinP,
                    'aHipIntRotFinA' => $request->aHipIntRotFinA,
                    'aHipExtRotInitP' => $request->aHipExtRotInitP,
                    'aHipExtRotInitA' => $request->aHipExtRotInitA,
                    'aHipExtRotProgP' => $request->aHipExtRotProgP,
                    'aHipExtRotProgA' => $request->aHipExtRotProgA,
                    'aHipExtRotFinP' => $request->aHipExtRotFinP,
                    'aHipExtRotFinA' => $request->aHipExtRotFinA,
                    'aKneeExtInitP' => $request->aKneeExtInitP,
                    'aKneeExtInitA' => $request->aKneeExtInitA,
                    'aKneeExtProgP' => $request->aKneeExtProgP,
                    'aKneeExtProgA' => $request->aKneeExtProgA,
                    'aKneeExtFinP' => $request->aKneeExtFinP,
                    'aKneeExtFinA' => $request->aKneeExtFinA,
                    'aKneeFlxInitP' => $request->aKneeFlxInitP,
                    'aKneeFlxInitA' => $request->aKneeFlxInitA,
                    'aKneeFlxProgP' => $request->aKneeFlxProgP,
                    'aKneeFlxProgA' => $request->aKneeFlxProgA,
                    'aKneeFlxFinP' => $request->aKneeFlxFinP,
                    'aKneeFlxFinA' => $request->aKneeFlxFinA,
                    'aAnkleDorsInitP' => $request->aAnkleDorsInitP,
                    'aAnkleDorsInitA' => $request->aAnkleDorsInitA,
                    'aAnkleDorsProgP' => $request->aAnkleDorsProgP,
                    'aAnkleDorsProgA' => $request->aAnkleDorsProgA,
                    'aAnkleDorsFinP' => $request->aAnkleDorsFinP,
                    'aAnkleDorsFinA' => $request->aAnkleDorsFinA,
                    'aAnklePtarInitP' => $request->aAnklePtarInitP,
                    'aAnklePtarInitA' => $request->aAnklePtarInitA,
                    'aAnklePtarProgP' => $request->aAnklePtarProgP,
                    'aAnklePtarProgA' => $request->aAnklePtarProgA,
                    'aAnklePtarFinP' => $request->aAnklePtarFinP,
                    'aAnklePtarFinA' => $request->aAnklePtarFinA,
                    // 'aAnkleEverInitP' => $request->aAnkleEverInitP,
                    // 'aAnkleEverInitA' => $request->aAnkleEverInitA,
                    // 'aAnkleEverProgP' => $request->aAnkleEverProgP,
                    // 'aAnkleEverProgA' => $request->aAnkleEverProgA,
                    // 'aAnkleEverFinP' => $request->aAnkleEverFinP,
                    // 'aAnkleEverFinA' => $request->aAnkleEverFinA,
                    // 'aAnkleInverInitP' => $request->aAnkleInverInitP,
                    // 'aAnkleInverInitA' => $request->aAnkleInverInitA,
                    // 'aAnkleInverProgP' => $request->aAnkleInverProgP,
                    // 'aAnkleInverProgA' => $request->aAnkleInverProgA,
                    // 'aAnkleInverFinP' => $request->aAnkleInverFinP,
                    // 'aAnkleInverFinA' => $request->aAnkleInverFinA,
                    'adduser'  => session('username'),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'computerid' => session('computerid'),
                ]);
            
            DB::table('hisdb.phy_romsoundside')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn,
                    'episno' => $request->episno,
                    'type' => $request->type,
                    // 'entereddate' => $request->entereddate,
                    'romSoundSide' => $request->romSoundSide,
                    'sShlderFlxInitP' => $request->sShlderFlxInitP,
                    'sShlderFlxInitA' => $request->sShlderFlxInitA,
                    'sShlderFlxProgP' => $request->sShlderFlxProgP,
                    'sShlderFlxProgA' => $request->sShlderFlxProgA,
                    'sShlderFlxFinP' => $request->sShlderFlxFinP,
                    'sShlderFlxFinA' => $request->sShlderFlxFinA,
                    'sShlderExtInitP' => $request->sShlderExtInitP,
                    'sShlderExtInitA' => $request->sShlderExtInitA,
                    'sShlderExtProgP' => $request->sShlderExtProgP,
                    'sShlderExtProgA' => $request->sShlderExtProgA,
                    'sShlderExtFinP' => $request->sShlderExtFinP,
                    'sShlderExtFinA' => $request->sShlderExtFinA,
                    'sShlderAbdInitP' => $request->sShlderAbdInitP,
                    'sShlderAbdInitA' => $request->sShlderAbdInitA,
                    'sShlderAbdProgP' => $request->sShlderAbdProgP,
                    'sShlderAbdProgA' => $request->sShlderAbdProgA,
                    'sShlderAbdFinP' => $request->sShlderAbdFinP,
                    'sShlderAbdFinA' => $request->sShlderAbdFinA,
                    // 'sShlderAddInitP' => $request->sShlderAddInitP,
                    // 'sShlderAddInitA' => $request->sShlderAddInitA,
                    // 'sShlderAddProgP' => $request->sShlderAddProgP,
                    // 'sShlderAddProgA' => $request->sShlderAddProgA,
                    // 'sShlderAddFinP' => $request->sShlderAddFinP,
                    // 'sShlderAddFinA' => $request->sShlderAddFinA,
                    'sShlderIntRotInitP' => $request->sShlderIntRotInitP,
                    'sShlderIntRotInitA' => $request->sShlderIntRotInitA,
                    'sShlderIntRotProgP' => $request->sShlderIntRotProgP,
                    'sShlderIntRotProgA' => $request->sShlderIntRotProgA,
                    'sShlderIntRotFinP' => $request->sShlderIntRotFinP,
                    'sShlderIntRotFinA' => $request->sShlderIntRotFinA,
                    'sShlderExtRotInitP' => $request->sShlderExtRotInitP,
                    'sShlderExtRotInitA' => $request->sShlderExtRotInitA,
                    'sShlderExtRotProgP' => $request->sShlderExtRotProgP,
                    'sShlderExtRotProgA' => $request->sShlderExtRotProgA,
                    'sShlderExtRotFinP' => $request->sShlderExtRotFinP,
                    'sShlderExtRotFinA' => $request->sShlderExtRotFinA,
                    'sElbowFlxInitP' => $request->sElbowFlxInitP,
                    'sElbowFlxInitA' => $request->sElbowFlxInitA,
                    'sElbowFlxProgP' => $request->sElbowFlxProgP,
                    'sElbowFlxProgA' => $request->sElbowFlxProgA,
                    'sElbowFlxFinP' => $request->sElbowFlxFinP,
                    'sElbowFlxFinA' => $request->sElbowFlxFinA,
                    'sElbowExtInitP' => $request->sElbowExtInitP,
                    'sElbowExtInitA' => $request->sElbowExtInitA,
                    'sElbowExtProgP' => $request->sElbowExtProgP,
                    'sElbowExtProgA' => $request->sElbowExtProgA,
                    'sElbowExtFinP' => $request->sElbowExtFinP,
                    'sElbowExtFinA' => $request->sElbowExtFinA,
                    // 'sElbowProInitP' => $request->sElbowProInitP,
                    // 'sElbowProInitA' => $request->sElbowProInitA,
                    // 'sElbowProProgP' => $request->sElbowProProgP,
                    // 'sElbowProProgA' => $request->sElbowProProgA,
                    // 'sElbowProFinP' => $request->sElbowProFinP,
                    // 'sElbowProFinA' => $request->sElbowProFinA,
                    // 'sElbowSupInitP' => $request->sElbowSupInitP,
                    // 'sElbowSupInitA' => $request->sElbowSupInitA,
                    // 'sElbowSupProgP' => $request->sElbowSupProgP,
                    // 'sElbowSupProgA' => $request->sElbowSupProgA,
                    // 'sElbowSupFinP' => $request->sElbowSupFinP,
                    // 'sElbowSupFinA' => $request->sElbowSupFinA,
                    'sWristFlxInitP' => $request->sWristFlxInitP,
                    'sWristFlxInitA' => $request->sWristFlxInitA,
                    'sWristFlxProgP' => $request->sWristFlxProgP,
                    'sWristFlxProgA' => $request->sWristFlxProgA,
                    'sWristFlxFinP' => $request->sWristFlxFinP,
                    'sWristFlxFinA' => $request->sWristFlxFinA,
                    'sWristExtInitP' => $request->sWristExtInitP,
                    'sWristExtInitA' => $request->sWristExtInitA,
                    'sWristExtProgP' => $request->sWristExtProgP,
                    'sWristExtProgA' => $request->sWristExtProgA,
                    'sWristExtFinP' => $request->sWristExtFinP,
                    'sWristExtFinA' => $request->sWristExtFinA,
                    // 'sWristRadInitP' => $request->sWristRadInitP,
                    // 'sWristRadInitA' => $request->sWristRadInitA,
                    // 'sWristRadProgP' => $request->sWristRadProgP,
                    // 'sWristRadProgA' => $request->sWristRadProgA,
                    // 'sWristRadFinP' => $request->sWristRadFinP,
                    // 'sWristRadFinA' => $request->sWristRadFinA,
                    // 'sWristUlnarInitP' => $request->sWristUlnarInitP,
                    // 'sWristUlnarInitA' => $request->sWristUlnarInitA,
                    // 'sWristUlnarProgP' => $request->sWristUlnarProgP,
                    // 'sWristUlnarProgA' => $request->sWristUlnarProgA,
                    // 'sWristUlnarFinP' => $request->sWristUlnarFinP,
                    // 'sWristUlnarFinA' => $request->sWristUlnarFinA,
                    'sHipFlxInitP' => $request->sHipFlxInitP,
                    'sHipFlxInitA' => $request->sHipFlxInitA,
                    'sHipFlxProgP' => $request->sHipFlxProgP,
                    'sHipFlxProgA' => $request->sHipFlxProgA,
                    'sHipFlxFinP' => $request->sHipFlxFinP,
                    'sHipFlxFinA' => $request->sHipFlxFinA,
                    'sHipExtInitP' => $request->sHipExtInitP,
                    'sHipExtInitA' => $request->sHipExtInitA,
                    'sHipExtProgP' => $request->sHipExtProgP,
                    'sHipExtProgA' => $request->sHipExtProgA,
                    'sHipExtFinP' => $request->sHipExtFinP,
                    'sHipExtFinA' => $request->sHipExtFinA,
                    'sHipAbdInitP' => $request->sHipAbdInitP,
                    'sHipAbdInitA' => $request->sHipAbdInitA,
                    'sHipAbdProgP' => $request->sHipAbdProgP,
                    'sHipAbdProgA' => $request->sHipAbdProgA,
                    'sHipAbdFinP' => $request->sHipAbdFinP,
                    'sHipAbdFinA' => $request->sHipAbdFinA,
                    // 'sHipAddInitP' => $request->sHipAddInitP,
                    // 'sHipAddInitA' => $request->sHipAddInitA,
                    // 'sHipAddProgP' => $request->sHipAddProgP,
                    // 'sHipAddProgA' => $request->sHipAddProgA,
                    // 'sHipAddFinP' => $request->sHipAddFinP,
                    // 'sHipAddFinA' => $request->sHipAddFinA,
                    'sHipIntRotInitP' => $request->sHipIntRotInitP,
                    'sHipIntRotInitA' => $request->sHipIntRotInitA,
                    'sHipIntRotProgP' => $request->sHipIntRotProgP,
                    'sHipIntRotProgA' => $request->sHipIntRotProgA,
                    'sHipIntRotFinP' => $request->sHipIntRotFinP,
                    'sHipIntRotFinA' => $request->sHipIntRotFinA,
                    'sHipExtRotInitP' => $request->sHipExtRotInitP,
                    'sHipExtRotInitA' => $request->sHipExtRotInitA,
                    'sHipExtRotProgP' => $request->sHipExtRotProgP,
                    'sHipExtRotProgA' => $request->sHipExtRotProgA,
                    'sHipExtRotFinP' => $request->sHipExtRotFinP,
                    'sHipExtRotFinA' => $request->sHipExtRotFinA,
                    'sKneeExtInitP' => $request->sKneeExtInitP,
                    'sKneeExtInitA' => $request->sKneeExtInitA,
                    'sKneeExtProgP' => $request->sKneeExtProgP,
                    'sKneeExtProgA' => $request->sKneeExtProgA,
                    'sKneeExtFinP' => $request->sKneeExtFinP,
                    'sKneeExtFinA' => $request->sKneeExtFinA,
                    'sKneeFlxInitP' => $request->sKneeFlxInitP,
                    'sKneeFlxInitA' => $request->sKneeFlxInitA,
                    'sKneeFlxProgP' => $request->sKneeFlxProgP,
                    'sKneeFlxProgA' => $request->sKneeFlxProgA,
                    'sKneeFlxFinP' => $request->sKneeFlxFinP,
                    'sKneeFlxFinA' => $request->sKneeFlxFinA,
                    'sAnkleDorsInitP' => $request->sAnkleDorsInitP,
                    'sAnkleDorsInitA' => $request->sAnkleDorsInitA,
                    'sAnkleDorsProgP' => $request->sAnkleDorsProgP,
                    'sAnkleDorsProgA' => $request->sAnkleDorsProgA,
                    'sAnkleDorsFinP' => $request->sAnkleDorsFinP,
                    'sAnkleDorsFinA' => $request->sAnkleDorsFinA,
                    'sAnklePtarInitP' => $request->sAnklePtarInitP,
                    'sAnklePtarInitA' => $request->sAnklePtarInitA,
                    'sAnklePtarProgP' => $request->sAnklePtarProgP,
                    'sAnklePtarProgA' => $request->sAnklePtarProgA,
                    'sAnklePtarFinP' => $request->sAnklePtarFinP,
                    'sAnklePtarFinA' => $request->sAnklePtarFinA,
                    // 'sAnkleEverInitP' => $request->sAnkleEverInitP,
                    // 'sAnkleEverInitA' => $request->sAnkleEverInitA,
                    // 'sAnkleEverProgP' => $request->sAnkleEverProgP,
                    // 'sAnkleEverProgA' => $request->sAnkleEverProgA,
                    // 'sAnkleEverFinP' => $request->sAnkleEverFinP,
                    // 'sAnkleEverFinA' => $request->sAnkleEverFinA,
                    // 'sAnkleInverInitP' => $request->sAnkleInverInitP,
                    // 'sAnkleInverInitA' => $request->sAnkleInverInitA,
                    // 'sAnkleInverProgP' => $request->sAnkleInverProgP,
                    // 'sAnkleInverProgA' => $request->sAnkleInverProgA,
                    // 'sAnkleInverFinP' => $request->sAnkleInverFinP,
                    // 'sAnkleInverFinA' => $request->sAnkleInverFinA,
                    'impressionROM' => $request->impressionROM,
                    'adduser'  => session('username'),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'computerid' => session('computerid'),
                ]);
            
            DB::table('hisdb.phy_musclepower')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn,
                    'episno' => $request->episno,
                    'type' => $request->type,
                    // 'entereddate' => $request->entereddate,
                    'affectedSide' => $request->affectedSide,
                    'aShlderFlxInit' => $request->aShlderFlxInit,
                    'aShlderFlxProg' => $request->aShlderFlxProg,
                    'aShlderFlxFin' => $request->aShlderFlxFin,
                    'aShlderExtInit' => $request->aShlderExtInit,
                    'aShlderExtProg' => $request->aShlderExtProg,
                    'aShlderExtFin' => $request->aShlderExtFin,
                    'aShlderAbdInit' => $request->aShlderAbdInit,
                    'aShlderAbdProg' => $request->aShlderAbdProg,
                    'aShlderAbdFin' => $request->aShlderAbdFin,
                    'aElbowFlxInit' => $request->aElbowFlxInit,
                    'aElbowFlxProg' => $request->aElbowFlxProg,
                    'aElbowFlxFin' => $request->aElbowFlxFin,
                    'aElbowExtInit' => $request->aElbowExtInit,
                    'aElbowExtProg' => $request->aElbowExtProg,
                    'aElbowExtFin' => $request->aElbowExtFin,
                    'aWristFlxInit' => $request->aWristFlxInit,
                    'aWristFlxProg' => $request->aWristFlxProg,
                    'aWristFlxFin' => $request->aWristFlxFin,
                    'aWristExtInit' => $request->aWristExtInit,
                    'aWristExtProg' => $request->aWristExtProg,
                    'aWristExtFin' => $request->aWristExtFin,
                    'aHipFlxInit' => $request->aHipFlxInit,
                    'aHipFlxProg' => $request->aHipFlxProg,
                    'aHipFlxFin' => $request->aHipFlxFin,
                    'aHipExtInit' => $request->aHipExtInit,
                    'aHipExtProg' => $request->aHipExtProg,
                    'aHipExtFin' => $request->aHipExtFin,
                    'aHipAbdInit' => $request->aHipAbdInit,
                    'aHipAbdProg' => $request->aHipAbdProg,
                    'aHipAbdFin' => $request->aHipAbdFin,
                    'aKneeExtInit' => $request->aKneeExtInit,
                    'aKneeExtProg' => $request->aKneeExtProg,
                    'aKneeExtFin' => $request->aKneeExtFin,
                    'aKneeFlxInit' => $request->aKneeFlxInit,
                    'aKneeFlxProg' => $request->aKneeFlxProg,
                    'aKneeFlxFin' => $request->aKneeFlxFin,
                    'aAnkleDorsInit' => $request->aAnkleDorsInit,
                    'aAnkleDorsProg' => $request->aAnkleDorsProg,
                    'aAnkleDorsFin' => $request->aAnkleDorsFin,
                    'aAnklePtarInit' => $request->aAnklePtarInit,
                    'aAnklePtarProg' => $request->aAnklePtarProg,
                    'aAnklePtarFin' => $request->aAnklePtarFin,
                    'soundSide' => $request->soundSide,
                    'sShlderFlxInit' => $request->sShlderFlxInit,
                    'sShlderFlxProg' => $request->sShlderFlxProg,
                    'sShlderFlxFin' => $request->sShlderFlxFin,
                    'sShlderExtInit' => $request->sShlderExtInit,
                    'sShlderExtProg' => $request->sShlderExtProg,
                    'sShlderExtFin' => $request->sShlderExtFin,
                    'sShlderAbdInit' => $request->sShlderAbdInit,
                    'sShlderAbdProg' => $request->sShlderAbdProg,
                    'sShlderAbdFin' => $request->sShlderAbdFin,
                    'sElbowFlxInit' => $request->sElbowFlxInit,
                    'sElbowFlxProg' => $request->sElbowFlxProg,
                    'sElbowFlxFin' => $request->sElbowFlxFin,
                    'sElbowExtInit' => $request->sElbowExtInit,
                    'sElbowExtProg' => $request->sElbowExtProg,
                    'sElbowExtFin' => $request->sElbowExtFin,
                    'sWristFlxInit' => $request->sWristFlxInit,
                    'sWristFlxProg' => $request->sWristFlxProg,
                    'sWristFlxFin' => $request->sWristFlxFin,
                    'sWristExtInit' => $request->sWristExtInit,
                    'sWristExtProg' => $request->sWristExtProg,
                    'sWristExtFin' => $request->sWristExtFin,
                    'sHipFlxInit' => $request->sHipFlxInit,
                    'sHipFlxProg' => $request->sHipFlxProg,
                    'sHipFlxFin' => $request->sHipFlxFin,
                    'sHipExtInit' => $request->sHipExtInit,
                    'sHipExtProg' => $request->sHipExtProg,
                    'sHipExtFin' => $request->sHipExtFin,
                    'sHipAbdInit' => $request->sHipAbdInit,
                    'sHipAbdProg' => $request->sHipAbdProg,
                    'sHipAbdFin' => $request->sHipAbdFin,
                    'sKneeExtInit' => $request->sKneeExtInit,
                    'sKneeExtProg' => $request->sKneeExtProg,
                    'sKneeExtFin' => $request->sKneeExtFin,
                    'sKneeFlxInit' => $request->sKneeFlxInit,
                    'sKneeFlxProg' => $request->sKneeFlxProg,
                    'sKneeFlxFin' => $request->sKneeFlxFin,
                    'sAnkleDorsInit' => $request->sAnkleDorsInit,
                    'sAnkleDorsProg' => $request->sAnkleDorsProg,
                    'sAnkleDorsFin' => $request->sAnkleDorsFin,
                    'sAnklePtarInit' => $request->sAnklePtarInit,
                    'sAnklePtarProg' => $request->sAnklePtarProg,
                    'sAnklePtarFin' => $request->sAnklePtarFin,
                    'impressionSMP' => $request->impressionSMP,
                    'adduser'  => session('username'),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'computerid' => session('computerid'),
                ]);
            
            DB::table('hisdb.patrehabperkeso')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn,
                    'episno' => $request->episno,
                    'type' => $request->type,
                    'diagnosis' => $request->diagnosis,
                    'incomeSource' => $request->incomeSource,
                    'totDependents' => $request->totDependents,
                    'eduLevel' => $request->eduLevel,
                    'dateTCA' => $request->dateTCA,
                    'typeTCA' => $request->typeTCA,
                    'dateMC' => $request->dateMC,
                    'employmentStat' => $request->employmentStat,
                    'workInfo' => $request->workInfo,
                    'employmentHist' => $request->employmentHist,
                    'communityMobility' => $request->communityMobility,
                    'workView' => $request->workView,
                    'workIndustry' => $request->workIndustry,
                    'OBmotivation' => $request->OBmotivation,
                    'subjective' => $request->subjective,
                    'initialDate' => $request->initialDate,
                    'progressDate' => $request->progressDate,
                    'finalDate' => $request->finalDate,
                    'initialComplaint' => $request->initialComplaint,
                    'progressComplaint' => $request->progressComplaint,
                    'finalComplaint' => $request->finalComplaint,
                    'patExpectation' => $request->patExpectation,
                    'familyExpectation' => $request->familyExpectation,
                    'objective' => $request->objective,
                    'barthelIndexInit' => $request->barthelIndexInit,
                    'barthelIndexProg' => $request->barthelIndexProg,
                    'barthelIndexFin' => $request->barthelIndexFin,
                    'bergBalanceInit' => $request->bergBalanceInit,
                    'bergBalanceProg' => $request->bergBalanceProg,
                    'bergBalanceFin' => $request->bergBalanceFin,
                    'sixMinWalkInit' => $request->sixMinWalkInit,
                    'sixMinWalkProg' => $request->sixMinWalkProg,
                    'sixMinWalkFin' => $request->sixMinWalkFin,
                    'impressionST' => $request->impressionST,
                    'finding1' => $request->finding1,
                    'intervention1' => $request->intervention1,
                    'finding2' => $request->finding2,
                    'intervention2' => $request->intervention2,
                    'finding3' => $request->finding3,
                    'intervention3' => $request->intervention3,
                    'finding4' => $request->finding4,
                    'intervention4' => $request->intervention4,
                    'finding5' => $request->finding5,
                    'intervention5' => $request->intervention5,
                    'finding6' => $request->finding6,
                    'intervention6' => $request->intervention6,
                    'rehabPlansInit' => $request->rehabPlansInit,
                    'rehabPlansProg' => $request->rehabPlansProg,
                    'rehabPlansFin' => $request->rehabPlansFin,
                    'limitInit' => $request->limitInit,
                    'limitProg' => $request->limitProg,
                    'limitFin' => $request->limitFin,
                    'improvementInit' => $request->improvementInit,
                    'improvementProg' => $request->improvementProg,
                    'improvementFin' => $request->improvementFin,
                    'recommendInit' => $request->recommendInit,
                    'recommendProg' => $request->recommendProg,
                    'recommendFin' => $request->recommendFin,
                    'therapistNameInit1' => $request->therapistNameInit1,
                    'therapistNameProg1' => $request->therapistNameProg1,
                    'therapistNameFin1' => $request->therapistNameFin1,
                    'therapistNameInit2' => $request->therapistNameInit2,
                    'therapistNameProg2' => $request->therapistNameProg2,
                    'therapistNameFin2' => $request->therapistNameFin2,
                    'summaryInitRmk' => $request->summaryInitRmk,
                    'summaryInitial' => $request->summaryInitial,
                    'summaryFinalRmk' => $request->summaryFinalRmk,
                    'summaryFinal' => $request->summaryFinal,
                    'adduser'  => session('username'),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'computerid' => session('computerid'),
                ]);
            // perkeso ends
            
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
                    'addNotes' => $request->addNotes,
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
            
            // perkeso starts
            DB::table('hisdb.phy_neuroassessment')
                ->where('compcode','=',session('compcode'))
                ->where('mrn','=',$request->mrn)
                ->where('episno','=',$request->episno)
                ->where('type','=','perkeso')
                ->update([
                    // 'entereddate' => $request->entereddate,
                    'objective' => $request->objective,
                    'painscore' => $request->painscore,
                    'painType' => $request->painType,
                    'severityBC' => $request->severityBC,
                    'irritabilityBC' => $request->irritabilityBC,
                    'painLocation' => $request->painLocation,
                    'subluxation' => $request->subluxation,
                    'palpationBC' => $request->palpationBC,
                    'impressionBC' => $request->impressionBC,
                    'superficialR' => $request->superficialR,
                    'superficialL' => $request->superficialL,
                    'superficialSpec' => $request->superficialSpec,
                    'deepR' => $request->deepR,
                    'deepL' => $request->deepL,
                    'deepSpec' => $request->deepSpec,
                    'numbnessR' => $request->numbnessR,
                    'numbnessL' => $request->numbnessL,
                    'numbnessSpec' => $request->numbnessSpec,
                    'paresthesiaR' => $request->paresthesiaR,
                    'paresthesiaL' => $request->paresthesiaL,
                    'paresthesiaSpec' => $request->paresthesiaSpec,
                    'otherR' => $request->otherR,
                    'otherL' => $request->otherL,
                    'otherSpec' => $request->otherSpec,
                    'impressionSens' => $request->impressionSens,
                    'muscleUL' => $request->muscleUL,
                    'muscleLL' => $request->muscleLL,
                    'impressionMAS' => $request->impressionMAS,
                    'btrRT' => $request->btrRT,
                    'btrLT' => $request->btrLT,
                    'ttrRT' => $request->ttrRT,
                    'ttrLT' => $request->ttrLT,
                    'ktrRT' => $request->ktrRT,
                    'ktrLT' => $request->ktrLT,
                    'atrRT' => $request->atrRT,
                    'atrLT' => $request->atrLT,
                    'babinskyRT' => $request->babinskyRT,
                    'babinskyLT' => $request->babinskyLT,
                    'impressionDTR' => $request->impressionDTR,
                    'fingerTestR' => $request->fingerTestR,
                    'fingerTestL' => $request->fingerTestL,
                    'heelTestR' => $request->heelTestR,
                    'heelTestL' => $request->heelTestL,
                    'impressionCoord' => $request->impressionCoord,
                    'transferInit' => $request->transferInit,
                    'transferProg' => $request->transferProg,
                    'transferFin' => $request->transferFin,
                    'suptoSideInit' => $request->suptoSideInit,
                    'suptoSideProg' => $request->suptoSideProg,
                    'suptoSideFin' => $request->suptoSideFin,
                    'sideToSitInit' => $request->sideToSitInit,
                    'sideToSitProg' => $request->sideToSitProg,
                    'sideToSitFin' => $request->sideToSitFin,
                    'sittInit' => $request->sittInit,
                    'sittProg' => $request->sittProg,
                    'sittFin' => $request->sittFin,
                    'sitToStdInit' => $request->sitToStdInit,
                    'sitToStdProg' => $request->sitToStdProg,
                    'sitToStdFin' => $request->sitToStdFin,
                    'stdInit' => $request->stdInit,
                    'stdProg' => $request->stdProg,
                    'stdFin' => $request->stdFin,
                    'shiftInit' => $request->shiftInit,
                    'shiftProg' => $request->shiftProg,
                    'shiftFin' => $request->shiftFin,
                    'ambulationInit' => $request->ambulationInit,
                    'ambulationProg' => $request->ambulationProg,
                    'ambulationFin' => $request->ambulationFin,
                    'impressionFA' => $request->impressionFA,
                    'summary' => $request->summary,
                    'upduser'  => session('username'),
                    'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'computerid' => session('computerid'),
                ]);
            
            DB::table('hisdb.phy_romaffectedside')
                ->where('compcode','=',session('compcode'))
                ->where('mrn','=',$request->mrn)
                ->where('episno','=',$request->episno)
                ->where('type','=','perkeso')
                ->update([
                    // 'entereddate' => $request->entereddate,
                    'romAffectedSide' => $request->romAffectedSide,
                    'aShlderFlxInitP' => $request->aShlderFlxInitP,
                    'aShlderFlxInitA' => $request->aShlderFlxInitA,
                    'aShlderFlxProgP' => $request->aShlderFlxProgP,
                    'aShlderFlxProgA' => $request->aShlderFlxProgA,
                    'aShlderFlxFinP' => $request->aShlderFlxFinP,
                    'aShlderFlxFinA' => $request->aShlderFlxFinA,
                    'aShlderExtInitP' => $request->aShlderExtInitP,
                    'aShlderExtInitA' => $request->aShlderExtInitA,
                    'aShlderExtProgP' => $request->aShlderExtProgP,
                    'aShlderExtProgA' => $request->aShlderExtProgA,
                    'aShlderExtFinP' => $request->aShlderExtFinP,
                    'aShlderExtFinA' => $request->aShlderExtFinA,
                    'aShlderAbdInitP' => $request->aShlderAbdInitP,
                    'aShlderAbdInitA' => $request->aShlderAbdInitA,
                    'aShlderAbdProgP' => $request->aShlderAbdProgP,
                    'aShlderAbdProgA' => $request->aShlderAbdProgA,
                    'aShlderAbdFinP' => $request->aShlderAbdFinP,
                    'aShlderAbdFinA' => $request->aShlderAbdFinA,
                    // 'aShlderAddInitP' => $request->aShlderAddInitP,
                    // 'aShlderAddInitA' => $request->aShlderAddInitA,
                    // 'aShlderAddProgP' => $request->aShlderAddProgP,
                    // 'aShlderAddProgA' => $request->aShlderAddProgA,
                    // 'aShlderAddFinP' => $request->aShlderAddFinP,
                    // 'aShlderAddFinA' => $request->aShlderAddFinA,
                    'aShlderIntRotInitP' => $request->aShlderIntRotInitP,
                    'aShlderIntRotInitA' => $request->aShlderIntRotInitA,
                    'aShlderIntRotProgP' => $request->aShlderIntRotProgP,
                    'aShlderIntRotProgA' => $request->aShlderIntRotProgA,
                    'aShlderIntRotFinP' => $request->aShlderIntRotFinP,
                    'aShlderIntRotFinA' => $request->aShlderIntRotFinA,
                    'aShlderExtRotInitP' => $request->aShlderExtRotInitP,
                    'aShlderExtRotInitA' => $request->aShlderExtRotInitA,
                    'aShlderExtRotProgP' => $request->aShlderExtRotProgP,
                    'aShlderExtRotProgA' => $request->aShlderExtRotProgA,
                    'aShlderExtRotFinP' => $request->aShlderExtRotFinP,
                    'aShlderExtRotFinA' => $request->aShlderExtRotFinA,
                    'aElbowFlxInitP' => $request->aElbowFlxInitP,
                    'aElbowFlxInitA' => $request->aElbowFlxInitA,
                    'aElbowFlxProgP' => $request->aElbowFlxProgP,
                    'aElbowFlxProgA' => $request->aElbowFlxProgA,
                    'aElbowFlxFinP' => $request->aElbowFlxFinP,
                    'aElbowFlxFinA' => $request->aElbowFlxFinA,
                    'aElbowExtInitP' => $request->aElbowExtInitP,
                    'aElbowExtInitA' => $request->aElbowExtInitA,
                    'aElbowExtProgP' => $request->aElbowExtProgP,
                    'aElbowExtProgA' => $request->aElbowExtProgA,
                    'aElbowExtFinP' => $request->aElbowExtFinP,
                    'aElbowExtFinA' => $request->aElbowExtFinA,
                    // 'aElbowProInitP' => $request->aElbowProInitP,
                    // 'aElbowProInitA' => $request->aElbowProInitA,
                    // 'aElbowProProgP' => $request->aElbowProProgP,
                    // 'aElbowProProgA' => $request->aElbowProProgA,
                    // 'aElbowProFinP' => $request->aElbowProFinP,
                    // 'aElbowProFinA' => $request->aElbowProFinA,
                    // 'aElbowSupInitP' => $request->aElbowSupInitP,
                    // 'aElbowSupInitA' => $request->aElbowSupInitA,
                    // 'aElbowSupProgP' => $request->aElbowSupProgP,
                    // 'aElbowSupProgA' => $request->aElbowSupProgA,
                    // 'aElbowSupFinP' => $request->aElbowSupFinP,
                    // 'aElbowSupFinA' => $request->aElbowSupFinA,
                    'aWristFlxInitP' => $request->aWristFlxInitP,
                    'aWristFlxInitA' => $request->aWristFlxInitA,
                    'aWristFlxProgP' => $request->aWristFlxProgP,
                    'aWristFlxProgA' => $request->aWristFlxProgA,
                    'aWristFlxFinP' => $request->aWristFlxFinP,
                    'aWristFlxFinA' => $request->aWristFlxFinA,
                    'aWristExtInitP' => $request->aWristExtInitP,
                    'aWristExtInitA' => $request->aWristExtInitA,
                    'aWristExtProgP' => $request->aWristExtProgP,
                    'aWristExtProgA' => $request->aWristExtProgA,
                    'aWristExtFinP' => $request->aWristExtFinP,
                    'aWristExtFinA' => $request->aWristExtFinA,
                    // 'aWristRadInitP' => $request->aWristRadInitP,
                    // 'aWristRadInitA' => $request->aWristRadInitA,
                    // 'aWristRadProgP' => $request->aWristRadProgP,
                    // 'aWristRadProgA' => $request->aWristRadProgA,
                    // 'aWristRadFinP' => $request->aWristRadFinP,
                    // 'aWristRadFinA' => $request->aWristRadFinA,
                    // 'aWristUlnarInitP' => $request->aWristUlnarInitP,
                    // 'aWristUlnarInitA' => $request->aWristUlnarInitA,
                    // 'aWristUlnarProgP' => $request->aWristUlnarProgP,
                    // 'aWristUlnarProgA' => $request->aWristUlnarProgA,
                    // 'aWristUlnarFinP' => $request->aWristUlnarFinP,
                    // 'aWristUlnarFinA' => $request->aWristUlnarFinA,
                    'aHipFlxInitP' => $request->aHipFlxInitP,
                    'aHipFlxInitA' => $request->aHipFlxInitA,
                    'aHipFlxProgP' => $request->aHipFlxProgP,
                    'aHipFlxProgA' => $request->aHipFlxProgA,
                    'aHipFlxFinP' => $request->aHipFlxFinP,
                    'aHipFlxFinA' => $request->aHipFlxFinA,
                    'aHipExtInitP' => $request->aHipExtInitP,
                    'aHipExtInitA' => $request->aHipExtInitA,
                    'aHipExtProgP' => $request->aHipExtProgP,
                    'aHipExtProgA' => $request->aHipExtProgA,
                    'aHipExtFinP' => $request->aHipExtFinP,
                    'aHipExtFinA' => $request->aHipExtFinA,
                    'aHipAbdInitP' => $request->aHipAbdInitP,
                    'aHipAbdInitA' => $request->aHipAbdInitA,
                    'aHipAbdProgP' => $request->aHipAbdProgP,
                    'aHipAbdProgA' => $request->aHipAbdProgA,
                    'aHipAbdFinP' => $request->aHipAbdFinP,
                    'aHipAbdFinA' => $request->aHipAbdFinA,
                    // 'aHipAddInitP' => $request->aHipAddInitP,
                    // 'aHipAddInitA' => $request->aHipAddInitA,
                    // 'aHipAddProgP' => $request->aHipAddProgP,
                    // 'aHipAddProgA' => $request->aHipAddProgA,
                    // 'aHipAddFinP' => $request->aHipAddFinP,
                    // 'aHipAddFinA' => $request->aHipAddFinA,
                    'aHipIntRotInitP' => $request->aHipIntRotInitP,
                    'aHipIntRotInitA' => $request->aHipIntRotInitA,
                    'aHipIntRotProgP' => $request->aHipIntRotProgP,
                    'aHipIntRotProgA' => $request->aHipIntRotProgA,
                    'aHipIntRotFinP' => $request->aHipIntRotFinP,
                    'aHipIntRotFinA' => $request->aHipIntRotFinA,
                    'aHipExtRotInitP' => $request->aHipExtRotInitP,
                    'aHipExtRotInitA' => $request->aHipExtRotInitA,
                    'aHipExtRotProgP' => $request->aHipExtRotProgP,
                    'aHipExtRotProgA' => $request->aHipExtRotProgA,
                    'aHipExtRotFinP' => $request->aHipExtRotFinP,
                    'aHipExtRotFinA' => $request->aHipExtRotFinA,
                    'aKneeExtInitP' => $request->aKneeExtInitP,
                    'aKneeExtInitA' => $request->aKneeExtInitA,
                    'aKneeExtProgP' => $request->aKneeExtProgP,
                    'aKneeExtProgA' => $request->aKneeExtProgA,
                    'aKneeExtFinP' => $request->aKneeExtFinP,
                    'aKneeExtFinA' => $request->aKneeExtFinA,
                    'aKneeFlxInitP' => $request->aKneeFlxInitP,
                    'aKneeFlxInitA' => $request->aKneeFlxInitA,
                    'aKneeFlxProgP' => $request->aKneeFlxProgP,
                    'aKneeFlxProgA' => $request->aKneeFlxProgA,
                    'aKneeFlxFinP' => $request->aKneeFlxFinP,
                    'aKneeFlxFinA' => $request->aKneeFlxFinA,
                    'aAnkleDorsInitP' => $request->aAnkleDorsInitP,
                    'aAnkleDorsInitA' => $request->aAnkleDorsInitA,
                    'aAnkleDorsProgP' => $request->aAnkleDorsProgP,
                    'aAnkleDorsProgA' => $request->aAnkleDorsProgA,
                    'aAnkleDorsFinP' => $request->aAnkleDorsFinP,
                    'aAnkleDorsFinA' => $request->aAnkleDorsFinA,
                    'aAnklePtarInitP' => $request->aAnklePtarInitP,
                    'aAnklePtarInitA' => $request->aAnklePtarInitA,
                    'aAnklePtarProgP' => $request->aAnklePtarProgP,
                    'aAnklePtarProgA' => $request->aAnklePtarProgA,
                    'aAnklePtarFinP' => $request->aAnklePtarFinP,
                    'aAnklePtarFinA' => $request->aAnklePtarFinA,
                    // 'aAnkleEverInitP' => $request->aAnkleEverInitP,
                    // 'aAnkleEverInitA' => $request->aAnkleEverInitA,
                    // 'aAnkleEverProgP' => $request->aAnkleEverProgP,
                    // 'aAnkleEverProgA' => $request->aAnkleEverProgA,
                    // 'aAnkleEverFinP' => $request->aAnkleEverFinP,
                    // 'aAnkleEverFinA' => $request->aAnkleEverFinA,
                    // 'aAnkleInverInitP' => $request->aAnkleInverInitP,
                    // 'aAnkleInverInitA' => $request->aAnkleInverInitA,
                    // 'aAnkleInverProgP' => $request->aAnkleInverProgP,
                    // 'aAnkleInverProgA' => $request->aAnkleInverProgA,
                    // 'aAnkleInverFinP' => $request->aAnkleInverFinP,
                    // 'aAnkleInverFinA' => $request->aAnkleInverFinA,
                    'upduser'  => session('username'),
                    'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'computerid' => session('computerid'),
                ]);
            
            DB::table('hisdb.phy_romsoundside')
                ->where('compcode','=',session('compcode'))
                ->where('mrn','=',$request->mrn)
                ->where('episno','=',$request->episno)
                ->where('type','=','perkeso')
                ->update([
                    // 'entereddate' => $request->entereddate,
                    'romSoundSide' => $request->romSoundSide,
                    'sShlderFlxInitP' => $request->sShlderFlxInitP,
                    'sShlderFlxInitA' => $request->sShlderFlxInitA,
                    'sShlderFlxProgP' => $request->sShlderFlxProgP,
                    'sShlderFlxProgA' => $request->sShlderFlxProgA,
                    'sShlderFlxFinP' => $request->sShlderFlxFinP,
                    'sShlderFlxFinA' => $request->sShlderFlxFinA,
                    'sShlderExtInitP' => $request->sShlderExtInitP,
                    'sShlderExtInitA' => $request->sShlderExtInitA,
                    'sShlderExtProgP' => $request->sShlderExtProgP,
                    'sShlderExtProgA' => $request->sShlderExtProgA,
                    'sShlderExtFinP' => $request->sShlderExtFinP,
                    'sShlderExtFinA' => $request->sShlderExtFinA,
                    'sShlderAbdInitP' => $request->sShlderAbdInitP,
                    'sShlderAbdInitA' => $request->sShlderAbdInitA,
                    'sShlderAbdProgP' => $request->sShlderAbdProgP,
                    'sShlderAbdProgA' => $request->sShlderAbdProgA,
                    'sShlderAbdFinP' => $request->sShlderAbdFinP,
                    'sShlderAbdFinA' => $request->sShlderAbdFinA,
                    // 'sShlderAddInitP' => $request->sShlderAddInitP,
                    // 'sShlderAddInitA' => $request->sShlderAddInitA,
                    // 'sShlderAddProgP' => $request->sShlderAddProgP,
                    // 'sShlderAddProgA' => $request->sShlderAddProgA,
                    // 'sShlderAddFinP' => $request->sShlderAddFinP,
                    // 'sShlderAddFinA' => $request->sShlderAddFinA,
                    'sShlderIntRotInitP' => $request->sShlderIntRotInitP,
                    'sShlderIntRotInitA' => $request->sShlderIntRotInitA,
                    'sShlderIntRotProgP' => $request->sShlderIntRotProgP,
                    'sShlderIntRotProgA' => $request->sShlderIntRotProgA,
                    'sShlderIntRotFinP' => $request->sShlderIntRotFinP,
                    'sShlderIntRotFinA' => $request->sShlderIntRotFinA,
                    'sShlderExtRotInitP' => $request->sShlderExtRotInitP,
                    'sShlderExtRotInitA' => $request->sShlderExtRotInitA,
                    'sShlderExtRotProgP' => $request->sShlderExtRotProgP,
                    'sShlderExtRotProgA' => $request->sShlderExtRotProgA,
                    'sShlderExtRotFinP' => $request->sShlderExtRotFinP,
                    'sShlderExtRotFinA' => $request->sShlderExtRotFinA,
                    'sElbowFlxInitP' => $request->sElbowFlxInitP,
                    'sElbowFlxInitA' => $request->sElbowFlxInitA,
                    'sElbowFlxProgP' => $request->sElbowFlxProgP,
                    'sElbowFlxProgA' => $request->sElbowFlxProgA,
                    'sElbowFlxFinP' => $request->sElbowFlxFinP,
                    'sElbowFlxFinA' => $request->sElbowFlxFinA,
                    'sElbowExtInitP' => $request->sElbowExtInitP,
                    'sElbowExtInitA' => $request->sElbowExtInitA,
                    'sElbowExtProgP' => $request->sElbowExtProgP,
                    'sElbowExtProgA' => $request->sElbowExtProgA,
                    'sElbowExtFinP' => $request->sElbowExtFinP,
                    'sElbowExtFinA' => $request->sElbowExtFinA,
                    // 'sElbowProInitP' => $request->sElbowProInitP,
                    // 'sElbowProInitA' => $request->sElbowProInitA,
                    // 'sElbowProProgP' => $request->sElbowProProgP,
                    // 'sElbowProProgA' => $request->sElbowProProgA,
                    // 'sElbowProFinP' => $request->sElbowProFinP,
                    // 'sElbowProFinA' => $request->sElbowProFinA,
                    // 'sElbowSupInitP' => $request->sElbowSupInitP,
                    // 'sElbowSupInitA' => $request->sElbowSupInitA,
                    // 'sElbowSupProgP' => $request->sElbowSupProgP,
                    // 'sElbowSupProgA' => $request->sElbowSupProgA,
                    // 'sElbowSupFinP' => $request->sElbowSupFinP,
                    // 'sElbowSupFinA' => $request->sElbowSupFinA,
                    'sWristFlxInitP' => $request->sWristFlxInitP,
                    'sWristFlxInitA' => $request->sWristFlxInitA,
                    'sWristFlxProgP' => $request->sWristFlxProgP,
                    'sWristFlxProgA' => $request->sWristFlxProgA,
                    'sWristFlxFinP' => $request->sWristFlxFinP,
                    'sWristFlxFinA' => $request->sWristFlxFinA,
                    'sWristExtInitP' => $request->sWristExtInitP,
                    'sWristExtInitA' => $request->sWristExtInitA,
                    'sWristExtProgP' => $request->sWristExtProgP,
                    'sWristExtProgA' => $request->sWristExtProgA,
                    'sWristExtFinP' => $request->sWristExtFinP,
                    'sWristExtFinA' => $request->sWristExtFinA,
                    // 'sWristRadInitP' => $request->sWristRadInitP,
                    // 'sWristRadInitA' => $request->sWristRadInitA,
                    // 'sWristRadProgP' => $request->sWristRadProgP,
                    // 'sWristRadProgA' => $request->sWristRadProgA,
                    // 'sWristRadFinP' => $request->sWristRadFinP,
                    // 'sWristRadFinA' => $request->sWristRadFinA,
                    // 'sWristUlnarInitP' => $request->sWristUlnarInitP,
                    // 'sWristUlnarInitA' => $request->sWristUlnarInitA,
                    // 'sWristUlnarProgP' => $request->sWristUlnarProgP,
                    // 'sWristUlnarProgA' => $request->sWristUlnarProgA,
                    // 'sWristUlnarFinP' => $request->sWristUlnarFinP,
                    // 'sWristUlnarFinA' => $request->sWristUlnarFinA,
                    'sHipFlxInitP' => $request->sHipFlxInitP,
                    'sHipFlxInitA' => $request->sHipFlxInitA,
                    'sHipFlxProgP' => $request->sHipFlxProgP,
                    'sHipFlxProgA' => $request->sHipFlxProgA,
                    'sHipFlxFinP' => $request->sHipFlxFinP,
                    'sHipFlxFinA' => $request->sHipFlxFinA,
                    'sHipExtInitP' => $request->sHipExtInitP,
                    'sHipExtInitA' => $request->sHipExtInitA,
                    'sHipExtProgP' => $request->sHipExtProgP,
                    'sHipExtProgA' => $request->sHipExtProgA,
                    'sHipExtFinP' => $request->sHipExtFinP,
                    'sHipExtFinA' => $request->sHipExtFinA,
                    'sHipAbdInitP' => $request->sHipAbdInitP,
                    'sHipAbdInitA' => $request->sHipAbdInitA,
                    'sHipAbdProgP' => $request->sHipAbdProgP,
                    'sHipAbdProgA' => $request->sHipAbdProgA,
                    'sHipAbdFinP' => $request->sHipAbdFinP,
                    'sHipAbdFinA' => $request->sHipAbdFinA,
                    // 'sHipAddInitP' => $request->sHipAddInitP,
                    // 'sHipAddInitA' => $request->sHipAddInitA,
                    // 'sHipAddProgP' => $request->sHipAddProgP,
                    // 'sHipAddProgA' => $request->sHipAddProgA,
                    // 'sHipAddFinP' => $request->sHipAddFinP,
                    // 'sHipAddFinA' => $request->sHipAddFinA,
                    'sHipIntRotInitP' => $request->sHipIntRotInitP,
                    'sHipIntRotInitA' => $request->sHipIntRotInitA,
                    'sHipIntRotProgP' => $request->sHipIntRotProgP,
                    'sHipIntRotProgA' => $request->sHipIntRotProgA,
                    'sHipIntRotFinP' => $request->sHipIntRotFinP,
                    'sHipIntRotFinA' => $request->sHipIntRotFinA,
                    'sHipExtRotInitP' => $request->sHipExtRotInitP,
                    'sHipExtRotInitA' => $request->sHipExtRotInitA,
                    'sHipExtRotProgP' => $request->sHipExtRotProgP,
                    'sHipExtRotProgA' => $request->sHipExtRotProgA,
                    'sHipExtRotFinP' => $request->sHipExtRotFinP,
                    'sHipExtRotFinA' => $request->sHipExtRotFinA,
                    'sKneeExtInitP' => $request->sKneeExtInitP,
                    'sKneeExtInitA' => $request->sKneeExtInitA,
                    'sKneeExtProgP' => $request->sKneeExtProgP,
                    'sKneeExtProgA' => $request->sKneeExtProgA,
                    'sKneeExtFinP' => $request->sKneeExtFinP,
                    'sKneeExtFinA' => $request->sKneeExtFinA,
                    'sKneeFlxInitP' => $request->sKneeFlxInitP,
                    'sKneeFlxInitA' => $request->sKneeFlxInitA,
                    'sKneeFlxProgP' => $request->sKneeFlxProgP,
                    'sKneeFlxProgA' => $request->sKneeFlxProgA,
                    'sKneeFlxFinP' => $request->sKneeFlxFinP,
                    'sKneeFlxFinA' => $request->sKneeFlxFinA,
                    'sAnkleDorsInitP' => $request->sAnkleDorsInitP,
                    'sAnkleDorsInitA' => $request->sAnkleDorsInitA,
                    'sAnkleDorsProgP' => $request->sAnkleDorsProgP,
                    'sAnkleDorsProgA' => $request->sAnkleDorsProgA,
                    'sAnkleDorsFinP' => $request->sAnkleDorsFinP,
                    'sAnkleDorsFinA' => $request->sAnkleDorsFinA,
                    'sAnklePtarInitP' => $request->sAnklePtarInitP,
                    'sAnklePtarInitA' => $request->sAnklePtarInitA,
                    'sAnklePtarProgP' => $request->sAnklePtarProgP,
                    'sAnklePtarProgA' => $request->sAnklePtarProgA,
                    'sAnklePtarFinP' => $request->sAnklePtarFinP,
                    'sAnklePtarFinA' => $request->sAnklePtarFinA,
                    // 'sAnkleEverInitP' => $request->sAnkleEverInitP,
                    // 'sAnkleEverInitA' => $request->sAnkleEverInitA,
                    // 'sAnkleEverProgP' => $request->sAnkleEverProgP,
                    // 'sAnkleEverProgA' => $request->sAnkleEverProgA,
                    // 'sAnkleEverFinP' => $request->sAnkleEverFinP,
                    // 'sAnkleEverFinA' => $request->sAnkleEverFinA,
                    // 'sAnkleInverInitP' => $request->sAnkleInverInitP,
                    // 'sAnkleInverInitA' => $request->sAnkleInverInitA,
                    // 'sAnkleInverProgP' => $request->sAnkleInverProgP,
                    // 'sAnkleInverProgA' => $request->sAnkleInverProgA,
                    // 'sAnkleInverFinP' => $request->sAnkleInverFinP,
                    // 'sAnkleInverFinA' => $request->sAnkleInverFinA,
                    'impressionROM' => $request->impressionROM,
                    'upduser'  => session('username'),
                    'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'computerid' => session('computerid'),
                ]);
            
            DB::table('hisdb.phy_musclepower')
                ->where('compcode','=',session('compcode'))
                ->where('mrn','=',$request->mrn)
                ->where('episno','=',$request->episno)
                ->where('type','=','perkeso')
                ->update([
                    // 'entereddate' => $request->entereddate,
                    'affectedSide' => $request->affectedSide,
                    'aShlderFlxInit' => $request->aShlderFlxInit,
                    'aShlderFlxProg' => $request->aShlderFlxProg,
                    'aShlderFlxFin' => $request->aShlderFlxFin,
                    'aShlderExtInit' => $request->aShlderExtInit,
                    'aShlderExtProg' => $request->aShlderExtProg,
                    'aShlderExtFin' => $request->aShlderExtFin,
                    'aShlderAbdInit' => $request->aShlderAbdInit,
                    'aShlderAbdProg' => $request->aShlderAbdProg,
                    'aShlderAbdFin' => $request->aShlderAbdFin,
                    'aElbowFlxInit' => $request->aElbowFlxInit,
                    'aElbowFlxProg' => $request->aElbowFlxProg,
                    'aElbowFlxFin' => $request->aElbowFlxFin,
                    'aElbowExtInit' => $request->aElbowExtInit,
                    'aElbowExtProg' => $request->aElbowExtProg,
                    'aElbowExtFin' => $request->aElbowExtFin,
                    'aWristFlxInit' => $request->aWristFlxInit,
                    'aWristFlxProg' => $request->aWristFlxProg,
                    'aWristFlxFin' => $request->aWristFlxFin,
                    'aWristExtInit' => $request->aWristExtInit,
                    'aWristExtProg' => $request->aWristExtProg,
                    'aWristExtFin' => $request->aWristExtFin,
                    'aHipFlxInit' => $request->aHipFlxInit,
                    'aHipFlxProg' => $request->aHipFlxProg,
                    'aHipFlxFin' => $request->aHipFlxFin,
                    'aHipExtInit' => $request->aHipExtInit,
                    'aHipExtProg' => $request->aHipExtProg,
                    'aHipExtFin' => $request->aHipExtFin,
                    'aHipAbdInit' => $request->aHipAbdInit,
                    'aHipAbdProg' => $request->aHipAbdProg,
                    'aHipAbdFin' => $request->aHipAbdFin,
                    'aKneeExtInit' => $request->aKneeExtInit,
                    'aKneeExtProg' => $request->aKneeExtProg,
                    'aKneeExtFin' => $request->aKneeExtFin,
                    'aKneeFlxInit' => $request->aKneeFlxInit,
                    'aKneeFlxProg' => $request->aKneeFlxProg,
                    'aKneeFlxFin' => $request->aKneeFlxFin,
                    'aAnkleDorsInit' => $request->aAnkleDorsInit,
                    'aAnkleDorsProg' => $request->aAnkleDorsProg,
                    'aAnkleDorsFin' => $request->aAnkleDorsFin,
                    'aAnklePtarInit' => $request->aAnklePtarInit,
                    'aAnklePtarProg' => $request->aAnklePtarProg,
                    'aAnklePtarFin' => $request->aAnklePtarFin,
                    'soundSide' => $request->soundSide,
                    'sShlderFlxInit' => $request->sShlderFlxInit,
                    'sShlderFlxProg' => $request->sShlderFlxProg,
                    'sShlderFlxFin' => $request->sShlderFlxFin,
                    'sShlderExtInit' => $request->sShlderExtInit,
                    'sShlderExtProg' => $request->sShlderExtProg,
                    'sShlderExtFin' => $request->sShlderExtFin,
                    'sShlderAbdInit' => $request->sShlderAbdInit,
                    'sShlderAbdProg' => $request->sShlderAbdProg,
                    'sShlderAbdFin' => $request->sShlderAbdFin,
                    'sElbowFlxInit' => $request->sElbowFlxInit,
                    'sElbowFlxProg' => $request->sElbowFlxProg,
                    'sElbowFlxFin' => $request->sElbowFlxFin,
                    'sElbowExtInit' => $request->sElbowExtInit,
                    'sElbowExtProg' => $request->sElbowExtProg,
                    'sElbowExtFin' => $request->sElbowExtFin,
                    'sWristFlxInit' => $request->sWristFlxInit,
                    'sWristFlxProg' => $request->sWristFlxProg,
                    'sWristFlxFin' => $request->sWristFlxFin,
                    'sWristExtInit' => $request->sWristExtInit,
                    'sWristExtProg' => $request->sWristExtProg,
                    'sWristExtFin' => $request->sWristExtFin,
                    'sHipFlxInit' => $request->sHipFlxInit,
                    'sHipFlxProg' => $request->sHipFlxProg,
                    'sHipFlxFin' => $request->sHipFlxFin,
                    'sHipExtInit' => $request->sHipExtInit,
                    'sHipExtProg' => $request->sHipExtProg,
                    'sHipExtFin' => $request->sHipExtFin,
                    'sHipAbdInit' => $request->sHipAbdInit,
                    'sHipAbdProg' => $request->sHipAbdProg,
                    'sHipAbdFin' => $request->sHipAbdFin,
                    'sKneeExtInit' => $request->sKneeExtInit,
                    'sKneeExtProg' => $request->sKneeExtProg,
                    'sKneeExtFin' => $request->sKneeExtFin,
                    'sKneeFlxInit' => $request->sKneeFlxInit,
                    'sKneeFlxProg' => $request->sKneeFlxProg,
                    'sKneeFlxFin' => $request->sKneeFlxFin,
                    'sAnkleDorsInit' => $request->sAnkleDorsInit,
                    'sAnkleDorsProg' => $request->sAnkleDorsProg,
                    'sAnkleDorsFin' => $request->sAnkleDorsFin,
                    'sAnklePtarInit' => $request->sAnklePtarInit,
                    'sAnklePtarProg' => $request->sAnklePtarProg,
                    'sAnklePtarFin' => $request->sAnklePtarFin,
                    'impressionSMP' => $request->impressionSMP,
                    'upduser'  => session('username'),
                    'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'computerid' => session('computerid'),
                ]);
            
            DB::table('hisdb.patrehabperkeso')
                ->where('compcode','=',session('compcode'))
                ->where('mrn','=',$request->mrn)
                ->where('episno','=',$request->episno)
                ->where('type','=','perkeso')
                ->update([
                    'diagnosis' => $request->diagnosis,
                    'incomeSource' => $request->incomeSource,
                    'totDependents' => $request->totDependents,
                    'eduLevel' => $request->eduLevel,
                    'dateTCA' => $request->dateTCA,
                    'typeTCA' => $request->typeTCA,
                    'dateMC' => $request->dateMC,
                    'employmentStat' => $request->employmentStat,
                    'workInfo' => $request->workInfo,
                    'employmentHist' => $request->employmentHist,
                    'communityMobility' => $request->communityMobility,
                    'workView' => $request->workView,
                    'workIndustry' => $request->workIndustry,
                    'OBmotivation' => $request->OBmotivation,
                    'subjective' => $request->subjective,
                    'initialDate' => $request->initialDate,
                    'progressDate' => $request->progressDate,
                    'finalDate' => $request->finalDate,
                    'initialComplaint' => $request->initialComplaint,
                    'progressComplaint' => $request->progressComplaint,
                    'finalComplaint' => $request->finalComplaint,
                    'patExpectation' => $request->patExpectation,
                    'familyExpectation' => $request->familyExpectation,
                    'objective' => $request->objective,
                    'barthelIndexInit' => $request->barthelIndexInit,
                    'barthelIndexProg' => $request->barthelIndexProg,
                    'barthelIndexFin' => $request->barthelIndexFin,
                    'bergBalanceInit' => $request->bergBalanceInit,
                    'bergBalanceProg' => $request->bergBalanceProg,
                    'bergBalanceFin' => $request->bergBalanceFin,
                    'sixMinWalkInit' => $request->sixMinWalkInit,
                    'sixMinWalkProg' => $request->sixMinWalkProg,
                    'sixMinWalkFin' => $request->sixMinWalkFin,
                    'impressionST' => $request->impressionST,
                    'finding1' => $request->finding1,
                    'intervention1' => $request->intervention1,
                    'finding2' => $request->finding2,
                    'intervention2' => $request->intervention2,
                    'finding3' => $request->finding3,
                    'intervention3' => $request->intervention3,
                    'finding4' => $request->finding4,
                    'intervention4' => $request->intervention4,
                    'finding5' => $request->finding5,
                    'intervention5' => $request->intervention5,
                    'finding6' => $request->finding6,
                    'intervention6' => $request->intervention6,
                    'rehabPlansInit' => $request->rehabPlansInit,
                    'rehabPlansProg' => $request->rehabPlansProg,
                    'rehabPlansFin' => $request->rehabPlansFin,
                    'limitInit' => $request->limitInit,
                    'limitProg' => $request->limitProg,
                    'limitFin' => $request->limitFin,
                    'improvementInit' => $request->improvementInit,
                    'improvementProg' => $request->improvementProg,
                    'improvementFin' => $request->improvementFin,
                    'recommendInit' => $request->recommendInit,
                    'recommendProg' => $request->recommendProg,
                    'recommendFin' => $request->recommendFin,
                    'therapistNameInit1' => $request->therapistNameInit1,
                    'therapistNameProg1' => $request->therapistNameProg1,
                    'therapistNameFin1' => $request->therapistNameFin1,
                    'therapistNameInit2' => $request->therapistNameInit2,
                    'therapistNameProg2' => $request->therapistNameProg2,
                    'therapistNameFin2' => $request->therapistNameFin2,
                    'summaryInitRmk' => $request->summaryInitRmk,
                    'summaryInitial' => $request->summaryInitial,
                    'summaryFinalRmk' => $request->summaryFinalRmk,
                    'summaryFinal' => $request->summaryFinal,
                    'upduser'  => session('username'),
                    'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'computerid' => session('computerid'),
                ]);
            // perkeso ends
            
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
        
        $neuroassessment_obj = DB::table('hisdb.phy_neuroassessment')
                                ->where('compcode','=',session('compcode'))
                                ->where('mrn','=',$request->mrn)
                                ->where('episno','=',$request->episno)
                                ->where('type','=','perkeso');
        
        $romaffectedside_obj = DB::table('hisdb.phy_romaffectedside')
                                ->where('compcode','=',session('compcode'))
                                ->where('mrn','=',$request->mrn)
                                ->where('episno','=',$request->episno)
                                ->where('type','=','perkeso');
        
        $romsoundside_obj = DB::table('hisdb.phy_romsoundside')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno)
                            ->where('type','=','perkeso');
        
        $musclepower_obj = DB::table('hisdb.phy_musclepower')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno)
                            ->where('type','=','perkeso');
        
        $patrehabperkeso_obj = DB::table('hisdb.patrehabperkeso')
                                ->where('compcode','=',session('compcode'))
                                ->where('mrn','=',$request->mrn)
                                ->where('episno','=',$request->episno)
                                ->where('type','=','perkeso');
        
        $responce = new stdClass();
        
        if($patrehab_ncase_obj->exists()){
            $patrehab_ncase_obj = $patrehab_ncase_obj->first();
            $responce->patrehab_ncase = $patrehab_ncase_obj;
        }
        
        if($pat_physio_obj->exists()){
            $pat_physio_obj = $pat_physio_obj->first();
            $responce->pat_physio = $pat_physio_obj;
        }
        
        if($neuroassessment_obj->exists()){
            $neuroassessment_obj = $neuroassessment_obj->first();
            $responce->neuroassessment = $neuroassessment_obj;
        }
        
        if($romaffectedside_obj->exists()){
            $romaffectedside_obj = $romaffectedside_obj->first();
            $responce->romaffectedside = $romaffectedside_obj;
        }
        
        if($romsoundside_obj->exists()){
            $romsoundside_obj = $romsoundside_obj->first();
            $responce->romsoundside = $romsoundside_obj;
        }
        
        if($musclepower_obj->exists()){
            $musclepower_obj = $musclepower_obj->first();
            $responce->musclepower = $musclepower_obj;
        }
        
        if($patrehabperkeso_obj->exists()){
            $patrehabperkeso_obj = $patrehabperkeso_obj->first();
            $responce->patrehabperkeso = $patrehabperkeso_obj;
        }
        
        return json_encode($responce);
        
    }
    
    public function rehabperkeso_chart(Request $request){
        
        $mrn = $request->mrn;
        $episno = $request->episno;
        // $entereddate = $request->entereddate;
        $type1 = $request->type1;
        $type2 = $request->type2;
        if(!$mrn || !$episno){
            abort(404);
        }
        
        $patrehabperkeso = DB::table('hisdb.patrehabperkeso as rp')
                            ->select('rp.idno','rp.compcode','rp.mrn','rp.episno','rp.type','rp.diagnosis','rp.incomeSource','rp.totDependents','rp.eduLevel','rp.dateTCA','rp.typeTCA','rp.dateMC','rp.employmentStat','rp.workInfo','rp.employmentHist','rp.communityMobility','rp.workView','rp.workIndustry','rp.OBmotivation','rp.subjective','rp.initialDate','rp.progressDate','rp.finalDate','rp.initialComplaint','rp.progressComplaint','rp.finalComplaint','rp.patExpectation','rp.familyExpectation','rp.objective','rp.barthelIndexInit','rp.barthelIndexProg','rp.barthelIndexFin','rp.bergBalanceInit','rp.bergBalanceProg','rp.bergBalanceFin','rp.sixMinWalkInit','rp.sixMinWalkProg','rp.sixMinWalkFin','rp.impressionST','rp.finding1','rp.intervention1','rp.finding2','rp.intervention2','rp.finding3','rp.intervention3','rp.finding4','rp.intervention4','rp.finding5','rp.intervention5','rp.finding6','rp.intervention6','rp.rehabPlansInit','rp.rehabPlansProg','rp.rehabPlansFin','rp.limitInit','rp.limitProg','rp.limitFin','rp.improvementInit','rp.improvementProg','rp.improvementFin','rp.recommendInit','rp.recommendProg','rp.recommendFin','rp.therapistNameInit1','rp.therapistNameProg1','rp.therapistNameFin1','rp.therapistNameInit2','rp.therapistNameProg2','rp.therapistNameFin2','rp.summaryInitRmk','rp.summaryInitial','rp.summaryFinalRmk','rp.summaryFinal','rp.adduser','rp.adddate','rp.upduser','rp.upddate','rp.lastuser','rp.lastupdate','rp.computerid','pm.Name','pm.Newic')
                            ->leftjoin('hisdb.pat_mast as pm', function ($join){
                                $join = $join->on('pm.MRN','=','rp.mrn');
                                $join = $join->on('pm.Episno','=','rp.episno');
                                $join = $join->where('pm.compcode','=',session('compcode'));
                            })
                            ->where('rp.compcode','=',session('compcode'))
                            ->where('rp.mrn','=',$mrn)
                            ->where('rp.episno','=',$episno)
                            // ->where('rp.entereddate','=',$entereddate)
                            ->where('rp.type','=','perkeso')
                            ->first();
        // dd($patrehabperkeso);
        
        $neuroassessment = DB::table('hisdb.phy_neuroassessment')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$mrn)
                            ->where('episno','=',$episno)
                            // ->where('entereddate','=',$entereddate)
                            ->where('type','=','perkeso')
                            ->first();
        
        $romaffectedside = DB::table('hisdb.phy_romaffectedside')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$mrn)
                            ->where('episno','=',$episno)
                            // ->where('entereddate','=',$entereddate)
                            ->where('type','=','perkeso')
                            ->first();
        
        $romsoundside = DB::table('hisdb.phy_romsoundside')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$mrn)
                        ->where('episno','=',$episno)
                        // ->where('entereddate','=',$entereddate)
                        ->where('type','=','perkeso')
                        ->first();
        
        $musclepower = DB::table('hisdb.phy_musclepower')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$mrn)
                        ->where('episno','=',$episno)
                        // ->where('entereddate','=',$entereddate)
                        ->where('type','=','perkeso')
                        ->first();
        
        $company = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();
        
        $attachment_files1 = $this->get_attachment_files1($mrn,$episno,$type1);
        $attachment_files2 = $this->get_attachment_files2($mrn,$episno,$type2);
        // dd($attachment_files);
        
        return view('patientcare.rehabPerkesoChart_pdfmake',compact('patrehabperkeso','neuroassessment','romaffectedside','romsoundside','musclepower','company','attachment_files1','attachment_files2'));
        
    }
    
    public function get_attachment_files1($mrn,$episno,$type1){
        
        $mrn = $mrn;
        $episno = $episno;
        // $entereddate = $entereddate;
        $type = $type1;
        
        // $foxitpath1 = "C:\Program Files (x86)\Foxit Software\Foxit PDF Reader\FoxitPDFReader.exe";
        // $foxitpath2 = "C:\Program Files (x86)\Foxit Software\Foxit Reader\FoxitReader.exe";
        
        // $foxitpath = "C:\laragon\www\pdf\open.bat  > /dev/null";
        $filename = $type."_".$mrn."_".$episno.".pdf"; // sebab tak perlu entereddate
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
    
    public function get_attachment_files2($mrn,$episno,$type2){
        
        $mrn = $mrn;
        $episno = $episno;
        // $entereddate = $entereddate;
        $type = $type2;
        
        // $foxitpath1 = "C:\Program Files (x86)\Foxit Software\Foxit PDF Reader\FoxitPDFReader.exe";
        // $foxitpath2 = "C:\Program Files (x86)\Foxit Software\Foxit Reader\FoxitReader.exe";
        
        // $foxitpath = "C:\laragon\www\pdf\open.bat  > /dev/null";
        $filename = $type."_".$mrn."_".$episno.".pdf"; // sebab tak perlu entereddate
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