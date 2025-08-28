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

class NeuroAssessmentController extends defaultController
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function show(Request $request)
    {
        return view('rehab.neuroAssessment');
    }
    
    public function form(Request $request)
    {
        DB::enableQueryLog();
        switch($request->action){
            case 'save_table_neuroAssessment':
                switch($request->oper){
                    case 'add':
                        return $this->add_neuroAssessment($request);
                    case 'edit':
                        return $this->edit_neuroAssessment($request);
                    default:
                        return 'error happen..';
                }
            
            case 'get_table_neuroAssessment':
                return $this->get_table_neuroAssessment($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function table(Request $request)
    {
        switch($request->action){
            case 'get_datetime_neuroAssessment':
                return $this->get_datetime_neuroAssessment($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function add_neuroAssessment(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $neuroassessment = DB::table('hisdb.phy_neuroassessment')
                                ->where('compcode','=',session('compcode'))
                                ->where('mrn','=',$request->mrn)
                                ->where('episno','=',$request->episno)
                                ->where('entereddate','=',$request->entereddate)
                                ->where('type','=','neurological');
            
            if($neuroassessment->exists()){
                // throw new \Exception('Date already exist.', 500);
                return response('Date already exist.');
            }
            
            DB::table('hisdb.phy_neuroassessment')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn,
                    'episno' => $request->episno,
                    'type' => $request->type,
                    'entereddate' => $request->entereddate,
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
                    'entereddate' => $request->entereddate,
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
                    'aShlderAddInitP' => $request->aShlderAddInitP,
                    'aShlderAddInitA' => $request->aShlderAddInitA,
                    'aShlderAddProgP' => $request->aShlderAddProgP,
                    'aShlderAddProgA' => $request->aShlderAddProgA,
                    'aShlderAddFinP' => $request->aShlderAddFinP,
                    'aShlderAddFinA' => $request->aShlderAddFinA,
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
                    'aElbowProInitP' => $request->aElbowProInitP,
                    'aElbowProInitA' => $request->aElbowProInitA,
                    'aElbowProProgP' => $request->aElbowProProgP,
                    'aElbowProProgA' => $request->aElbowProProgA,
                    'aElbowProFinP' => $request->aElbowProFinP,
                    'aElbowProFinA' => $request->aElbowProFinA,
                    'aElbowSupInitP' => $request->aElbowSupInitP,
                    'aElbowSupInitA' => $request->aElbowSupInitA,
                    'aElbowSupProgP' => $request->aElbowSupProgP,
                    'aElbowSupProgA' => $request->aElbowSupProgA,
                    'aElbowSupFinP' => $request->aElbowSupFinP,
                    'aElbowSupFinA' => $request->aElbowSupFinA,
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
                    'aWristRadInitP' => $request->aWristRadInitP,
                    'aWristRadInitA' => $request->aWristRadInitA,
                    'aWristRadProgP' => $request->aWristRadProgP,
                    'aWristRadProgA' => $request->aWristRadProgA,
                    'aWristRadFinP' => $request->aWristRadFinP,
                    'aWristRadFinA' => $request->aWristRadFinA,
                    'aWristUlnarInitP' => $request->aWristUlnarInitP,
                    'aWristUlnarInitA' => $request->aWristUlnarInitA,
                    'aWristUlnarProgP' => $request->aWristUlnarProgP,
                    'aWristUlnarProgA' => $request->aWristUlnarProgA,
                    'aWristUlnarFinP' => $request->aWristUlnarFinP,
                    'aWristUlnarFinA' => $request->aWristUlnarFinA,
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
                    'aHipAddInitP' => $request->aHipAddInitP,
                    'aHipAddInitA' => $request->aHipAddInitA,
                    'aHipAddProgP' => $request->aHipAddProgP,
                    'aHipAddProgA' => $request->aHipAddProgA,
                    'aHipAddFinP' => $request->aHipAddFinP,
                    'aHipAddFinA' => $request->aHipAddFinA,
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
                    'aAnkleEverInitP' => $request->aAnkleEverInitP,
                    'aAnkleEverInitA' => $request->aAnkleEverInitA,
                    'aAnkleEverProgP' => $request->aAnkleEverProgP,
                    'aAnkleEverProgA' => $request->aAnkleEverProgA,
                    'aAnkleEverFinP' => $request->aAnkleEverFinP,
                    'aAnkleEverFinA' => $request->aAnkleEverFinA,
                    'aAnkleInverInitP' => $request->aAnkleInverInitP,
                    'aAnkleInverInitA' => $request->aAnkleInverInitA,
                    'aAnkleInverProgP' => $request->aAnkleInverProgP,
                    'aAnkleInverProgA' => $request->aAnkleInverProgA,
                    'aAnkleInverFinP' => $request->aAnkleInverFinP,
                    'aAnkleInverFinA' => $request->aAnkleInverFinA,
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
                    'entereddate' => $request->entereddate,
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
                    'sShlderAddInitP' => $request->sShlderAddInitP,
                    'sShlderAddInitA' => $request->sShlderAddInitA,
                    'sShlderAddProgP' => $request->sShlderAddProgP,
                    'sShlderAddProgA' => $request->sShlderAddProgA,
                    'sShlderAddFinP' => $request->sShlderAddFinP,
                    'sShlderAddFinA' => $request->sShlderAddFinA,
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
                    'sElbowProInitP' => $request->sElbowProInitP,
                    'sElbowProInitA' => $request->sElbowProInitA,
                    'sElbowProProgP' => $request->sElbowProProgP,
                    'sElbowProProgA' => $request->sElbowProProgA,
                    'sElbowProFinP' => $request->sElbowProFinP,
                    'sElbowProFinA' => $request->sElbowProFinA,
                    'sElbowSupInitP' => $request->sElbowSupInitP,
                    'sElbowSupInitA' => $request->sElbowSupInitA,
                    'sElbowSupProgP' => $request->sElbowSupProgP,
                    'sElbowSupProgA' => $request->sElbowSupProgA,
                    'sElbowSupFinP' => $request->sElbowSupFinP,
                    'sElbowSupFinA' => $request->sElbowSupFinA,
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
                    'sWristRadInitP' => $request->sWristRadInitP,
                    'sWristRadInitA' => $request->sWristRadInitA,
                    'sWristRadProgP' => $request->sWristRadProgP,
                    'sWristRadProgA' => $request->sWristRadProgA,
                    'sWristRadFinP' => $request->sWristRadFinP,
                    'sWristRadFinA' => $request->sWristRadFinA,
                    'sWristUlnarInitP' => $request->sWristUlnarInitP,
                    'sWristUlnarInitA' => $request->sWristUlnarInitA,
                    'sWristUlnarProgP' => $request->sWristUlnarProgP,
                    'sWristUlnarProgA' => $request->sWristUlnarProgA,
                    'sWristUlnarFinP' => $request->sWristUlnarFinP,
                    'sWristUlnarFinA' => $request->sWristUlnarFinA,
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
                    'sHipAddInitP' => $request->sHipAddInitP,
                    'sHipAddInitA' => $request->sHipAddInitA,
                    'sHipAddProgP' => $request->sHipAddProgP,
                    'sHipAddProgA' => $request->sHipAddProgA,
                    'sHipAddFinP' => $request->sHipAddFinP,
                    'sHipAddFinA' => $request->sHipAddFinA,
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
                    'sAnkleEverInitP' => $request->sAnkleEverInitP,
                    'sAnkleEverInitA' => $request->sAnkleEverInitA,
                    'sAnkleEverProgP' => $request->sAnkleEverProgP,
                    'sAnkleEverProgA' => $request->sAnkleEverProgA,
                    'sAnkleEverFinP' => $request->sAnkleEverFinP,
                    'sAnkleEverFinA' => $request->sAnkleEverFinA,
                    'sAnkleInverInitP' => $request->sAnkleInverInitP,
                    'sAnkleInverInitA' => $request->sAnkleInverInitA,
                    'sAnkleInverProgP' => $request->sAnkleInverProgP,
                    'sAnkleInverProgA' => $request->sAnkleInverProgA,
                    'sAnkleInverFinP' => $request->sAnkleInverFinP,
                    'sAnkleInverFinA' => $request->sAnkleInverFinA,
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
                    'entereddate' => $request->entereddate,
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
            
            DB::commit();
            
            $responce = new stdClass();
            
            return json_encode($responce);
        
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function edit_neuroAssessment(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $neuroassessment = DB::table('hisdb.phy_neuroassessment')
                                ->where('compcode','=',session('compcode'))
                                ->where('mrn','=',$request->mrn)
                                ->where('episno','=',$request->episno)
                                ->where('entereddate','=',$request->entereddate)
                                ->where('type','=','neurological');
            
            if(!empty($request->idno_neuroAssessment)){
                if($neuroassessment->exists()){
                    if($neuroassessment->first()->idno != $request->idno_neuroAssessment){
                        // throw new \Exception('Date already exist.', 500);
                        return response('Date already exist.');
                    }
                }
                
                DB::table('hisdb.phy_neuroassessment')
                    ->where('idno','=',$request->idno_neuroAssessment)
                    // ->where('compcode','=',session('compcode'))
                    // ->where('mrn','=',$request->mrn)
                    // ->where('episno','=',$request->episno)
                    // ->where('entereddate','=',$request->entereddate)
                    ->update([
                        'entereddate' => $request->entereddate,
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
                    ->where('idno','=',$request->idno_romaffectedside)
                    // ->where('compcode','=',session('compcode'))
                    // ->where('mrn','=',$request->mrn)
                    // ->where('episno','=',$request->episno)
                    // ->where('entereddate','=',$request->entereddate)
                    ->update([
                        'entereddate' => $request->entereddate,
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
                        'aShlderAddInitP' => $request->aShlderAddInitP,
                        'aShlderAddInitA' => $request->aShlderAddInitA,
                        'aShlderAddProgP' => $request->aShlderAddProgP,
                        'aShlderAddProgA' => $request->aShlderAddProgA,
                        'aShlderAddFinP' => $request->aShlderAddFinP,
                        'aShlderAddFinA' => $request->aShlderAddFinA,
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
                        'aElbowProInitP' => $request->aElbowProInitP,
                        'aElbowProInitA' => $request->aElbowProInitA,
                        'aElbowProProgP' => $request->aElbowProProgP,
                        'aElbowProProgA' => $request->aElbowProProgA,
                        'aElbowProFinP' => $request->aElbowProFinP,
                        'aElbowProFinA' => $request->aElbowProFinA,
                        'aElbowSupInitP' => $request->aElbowSupInitP,
                        'aElbowSupInitA' => $request->aElbowSupInitA,
                        'aElbowSupProgP' => $request->aElbowSupProgP,
                        'aElbowSupProgA' => $request->aElbowSupProgA,
                        'aElbowSupFinP' => $request->aElbowSupFinP,
                        'aElbowSupFinA' => $request->aElbowSupFinA,
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
                        'aWristRadInitP' => $request->aWristRadInitP,
                        'aWristRadInitA' => $request->aWristRadInitA,
                        'aWristRadProgP' => $request->aWristRadProgP,
                        'aWristRadProgA' => $request->aWristRadProgA,
                        'aWristRadFinP' => $request->aWristRadFinP,
                        'aWristRadFinA' => $request->aWristRadFinA,
                        'aWristUlnarInitP' => $request->aWristUlnarInitP,
                        'aWristUlnarInitA' => $request->aWristUlnarInitA,
                        'aWristUlnarProgP' => $request->aWristUlnarProgP,
                        'aWristUlnarProgA' => $request->aWristUlnarProgA,
                        'aWristUlnarFinP' => $request->aWristUlnarFinP,
                        'aWristUlnarFinA' => $request->aWristUlnarFinA,
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
                        'aHipAddInitP' => $request->aHipAddInitP,
                        'aHipAddInitA' => $request->aHipAddInitA,
                        'aHipAddProgP' => $request->aHipAddProgP,
                        'aHipAddProgA' => $request->aHipAddProgA,
                        'aHipAddFinP' => $request->aHipAddFinP,
                        'aHipAddFinA' => $request->aHipAddFinA,
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
                        'aAnkleEverInitP' => $request->aAnkleEverInitP,
                        'aAnkleEverInitA' => $request->aAnkleEverInitA,
                        'aAnkleEverProgP' => $request->aAnkleEverProgP,
                        'aAnkleEverProgA' => $request->aAnkleEverProgA,
                        'aAnkleEverFinP' => $request->aAnkleEverFinP,
                        'aAnkleEverFinA' => $request->aAnkleEverFinA,
                        'aAnkleInverInitP' => $request->aAnkleInverInitP,
                        'aAnkleInverInitA' => $request->aAnkleInverInitA,
                        'aAnkleInverProgP' => $request->aAnkleInverProgP,
                        'aAnkleInverProgA' => $request->aAnkleInverProgA,
                        'aAnkleInverFinP' => $request->aAnkleInverFinP,
                        'aAnkleInverFinA' => $request->aAnkleInverFinA,
                        'upduser'  => session('username'),
                        'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
                
                DB::table('hisdb.phy_romsoundside')
                    ->where('idno','=',$request->idno_romsoundside)
                    // ->where('compcode','=',session('compcode'))
                    // ->where('mrn','=',$request->mrn)
                    // ->where('episno','=',$request->episno)
                    // ->where('entereddate','=',$request->entereddate)
                    ->update([
                        'entereddate' => $request->entereddate,
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
                        'sShlderAddInitP' => $request->sShlderAddInitP,
                        'sShlderAddInitA' => $request->sShlderAddInitA,
                        'sShlderAddProgP' => $request->sShlderAddProgP,
                        'sShlderAddProgA' => $request->sShlderAddProgA,
                        'sShlderAddFinP' => $request->sShlderAddFinP,
                        'sShlderAddFinA' => $request->sShlderAddFinA,
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
                        'sElbowProInitP' => $request->sElbowProInitP,
                        'sElbowProInitA' => $request->sElbowProInitA,
                        'sElbowProProgP' => $request->sElbowProProgP,
                        'sElbowProProgA' => $request->sElbowProProgA,
                        'sElbowProFinP' => $request->sElbowProFinP,
                        'sElbowProFinA' => $request->sElbowProFinA,
                        'sElbowSupInitP' => $request->sElbowSupInitP,
                        'sElbowSupInitA' => $request->sElbowSupInitA,
                        'sElbowSupProgP' => $request->sElbowSupProgP,
                        'sElbowSupProgA' => $request->sElbowSupProgA,
                        'sElbowSupFinP' => $request->sElbowSupFinP,
                        'sElbowSupFinA' => $request->sElbowSupFinA,
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
                        'sWristRadInitP' => $request->sWristRadInitP,
                        'sWristRadInitA' => $request->sWristRadInitA,
                        'sWristRadProgP' => $request->sWristRadProgP,
                        'sWristRadProgA' => $request->sWristRadProgA,
                        'sWristRadFinP' => $request->sWristRadFinP,
                        'sWristRadFinA' => $request->sWristRadFinA,
                        'sWristUlnarInitP' => $request->sWristUlnarInitP,
                        'sWristUlnarInitA' => $request->sWristUlnarInitA,
                        'sWristUlnarProgP' => $request->sWristUlnarProgP,
                        'sWristUlnarProgA' => $request->sWristUlnarProgA,
                        'sWristUlnarFinP' => $request->sWristUlnarFinP,
                        'sWristUlnarFinA' => $request->sWristUlnarFinA,
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
                        'sHipAddInitP' => $request->sHipAddInitP,
                        'sHipAddInitA' => $request->sHipAddInitA,
                        'sHipAddProgP' => $request->sHipAddProgP,
                        'sHipAddProgA' => $request->sHipAddProgA,
                        'sHipAddFinP' => $request->sHipAddFinP,
                        'sHipAddFinA' => $request->sHipAddFinA,
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
                        'sAnkleEverInitP' => $request->sAnkleEverInitP,
                        'sAnkleEverInitA' => $request->sAnkleEverInitA,
                        'sAnkleEverProgP' => $request->sAnkleEverProgP,
                        'sAnkleEverProgA' => $request->sAnkleEverProgA,
                        'sAnkleEverFinP' => $request->sAnkleEverFinP,
                        'sAnkleEverFinA' => $request->sAnkleEverFinA,
                        'sAnkleInverInitP' => $request->sAnkleInverInitP,
                        'sAnkleInverInitA' => $request->sAnkleInverInitA,
                        'sAnkleInverProgP' => $request->sAnkleInverProgP,
                        'sAnkleInverProgA' => $request->sAnkleInverProgA,
                        'sAnkleInverFinP' => $request->sAnkleInverFinP,
                        'sAnkleInverFinA' => $request->sAnkleInverFinA,
                        'impressionROM' => $request->impressionROM,
                        'upduser'  => session('username'),
                        'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
                
                DB::table('hisdb.phy_musclepower')
                    ->where('idno','=',$request->idno_musclepower)
                    // ->where('compcode','=',session('compcode'))
                    // ->where('mrn','=',$request->mrn)
                    // ->where('episno','=',$request->episno)
                    // ->where('entereddate','=',$request->entereddate)
                    ->update([
                        'entereddate' => $request->entereddate,
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
            }else{
                if($neuroassessment->exists()){
                    // throw new \Exception('Date already exist.', 500);
                    return response('Date already exist.');
                }
                
                DB::table('hisdb.phy_neuroassessment')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'entereddate' => $request->entereddate,
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
                        'entereddate' => $request->entereddate,
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
                        'aShlderAddInitP' => $request->aShlderAddInitP,
                        'aShlderAddInitA' => $request->aShlderAddInitA,
                        'aShlderAddProgP' => $request->aShlderAddProgP,
                        'aShlderAddProgA' => $request->aShlderAddProgA,
                        'aShlderAddFinP' => $request->aShlderAddFinP,
                        'aShlderAddFinA' => $request->aShlderAddFinA,
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
                        'aElbowProInitP' => $request->aElbowProInitP,
                        'aElbowProInitA' => $request->aElbowProInitA,
                        'aElbowProProgP' => $request->aElbowProProgP,
                        'aElbowProProgA' => $request->aElbowProProgA,
                        'aElbowProFinP' => $request->aElbowProFinP,
                        'aElbowProFinA' => $request->aElbowProFinA,
                        'aElbowSupInitP' => $request->aElbowSupInitP,
                        'aElbowSupInitA' => $request->aElbowSupInitA,
                        'aElbowSupProgP' => $request->aElbowSupProgP,
                        'aElbowSupProgA' => $request->aElbowSupProgA,
                        'aElbowSupFinP' => $request->aElbowSupFinP,
                        'aElbowSupFinA' => $request->aElbowSupFinA,
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
                        'aWristRadInitP' => $request->aWristRadInitP,
                        'aWristRadInitA' => $request->aWristRadInitA,
                        'aWristRadProgP' => $request->aWristRadProgP,
                        'aWristRadProgA' => $request->aWristRadProgA,
                        'aWristRadFinP' => $request->aWristRadFinP,
                        'aWristRadFinA' => $request->aWristRadFinA,
                        'aWristUlnarInitP' => $request->aWristUlnarInitP,
                        'aWristUlnarInitA' => $request->aWristUlnarInitA,
                        'aWristUlnarProgP' => $request->aWristUlnarProgP,
                        'aWristUlnarProgA' => $request->aWristUlnarProgA,
                        'aWristUlnarFinP' => $request->aWristUlnarFinP,
                        'aWristUlnarFinA' => $request->aWristUlnarFinA,
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
                        'aHipAddInitP' => $request->aHipAddInitP,
                        'aHipAddInitA' => $request->aHipAddInitA,
                        'aHipAddProgP' => $request->aHipAddProgP,
                        'aHipAddProgA' => $request->aHipAddProgA,
                        'aHipAddFinP' => $request->aHipAddFinP,
                        'aHipAddFinA' => $request->aHipAddFinA,
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
                        'aAnkleEverInitP' => $request->aAnkleEverInitP,
                        'aAnkleEverInitA' => $request->aAnkleEverInitA,
                        'aAnkleEverProgP' => $request->aAnkleEverProgP,
                        'aAnkleEverProgA' => $request->aAnkleEverProgA,
                        'aAnkleEverFinP' => $request->aAnkleEverFinP,
                        'aAnkleEverFinA' => $request->aAnkleEverFinA,
                        'aAnkleInverInitP' => $request->aAnkleInverInitP,
                        'aAnkleInverInitA' => $request->aAnkleInverInitA,
                        'aAnkleInverProgP' => $request->aAnkleInverProgP,
                        'aAnkleInverProgA' => $request->aAnkleInverProgA,
                        'aAnkleInverFinP' => $request->aAnkleInverFinP,
                        'aAnkleInverFinA' => $request->aAnkleInverFinA,
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
                        'entereddate' => $request->entereddate,
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
                        'sShlderAddInitP' => $request->sShlderAddInitP,
                        'sShlderAddInitA' => $request->sShlderAddInitA,
                        'sShlderAddProgP' => $request->sShlderAddProgP,
                        'sShlderAddProgA' => $request->sShlderAddProgA,
                        'sShlderAddFinP' => $request->sShlderAddFinP,
                        'sShlderAddFinA' => $request->sShlderAddFinA,
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
                        'sElbowProInitP' => $request->sElbowProInitP,
                        'sElbowProInitA' => $request->sElbowProInitA,
                        'sElbowProProgP' => $request->sElbowProProgP,
                        'sElbowProProgA' => $request->sElbowProProgA,
                        'sElbowProFinP' => $request->sElbowProFinP,
                        'sElbowProFinA' => $request->sElbowProFinA,
                        'sElbowSupInitP' => $request->sElbowSupInitP,
                        'sElbowSupInitA' => $request->sElbowSupInitA,
                        'sElbowSupProgP' => $request->sElbowSupProgP,
                        'sElbowSupProgA' => $request->sElbowSupProgA,
                        'sElbowSupFinP' => $request->sElbowSupFinP,
                        'sElbowSupFinA' => $request->sElbowSupFinA,
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
                        'sWristRadInitP' => $request->sWristRadInitP,
                        'sWristRadInitA' => $request->sWristRadInitA,
                        'sWristRadProgP' => $request->sWristRadProgP,
                        'sWristRadProgA' => $request->sWristRadProgA,
                        'sWristRadFinP' => $request->sWristRadFinP,
                        'sWristRadFinA' => $request->sWristRadFinA,
                        'sWristUlnarInitP' => $request->sWristUlnarInitP,
                        'sWristUlnarInitA' => $request->sWristUlnarInitA,
                        'sWristUlnarProgP' => $request->sWristUlnarProgP,
                        'sWristUlnarProgA' => $request->sWristUlnarProgA,
                        'sWristUlnarFinP' => $request->sWristUlnarFinP,
                        'sWristUlnarFinA' => $request->sWristUlnarFinA,
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
                        'sHipAddInitP' => $request->sHipAddInitP,
                        'sHipAddInitA' => $request->sHipAddInitA,
                        'sHipAddProgP' => $request->sHipAddProgP,
                        'sHipAddProgA' => $request->sHipAddProgA,
                        'sHipAddFinP' => $request->sHipAddFinP,
                        'sHipAddFinA' => $request->sHipAddFinA,
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
                        'sAnkleEverInitP' => $request->sAnkleEverInitP,
                        'sAnkleEverInitA' => $request->sAnkleEverInitA,
                        'sAnkleEverProgP' => $request->sAnkleEverProgP,
                        'sAnkleEverProgA' => $request->sAnkleEverProgA,
                        'sAnkleEverFinP' => $request->sAnkleEverFinP,
                        'sAnkleEverFinA' => $request->sAnkleEverFinA,
                        'sAnkleInverInitP' => $request->sAnkleInverInitP,
                        'sAnkleInverInitA' => $request->sAnkleInverInitA,
                        'sAnkleInverProgP' => $request->sAnkleInverProgP,
                        'sAnkleInverProgA' => $request->sAnkleInverProgA,
                        'sAnkleInverFinP' => $request->sAnkleInverFinP,
                        'sAnkleInverFinA' => $request->sAnkleInverFinA,
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
                        'entereddate' => $request->entereddate,
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
    
    public function get_table_neuroAssessment(Request $request){
        
        $neuroassessment_obj = DB::table('hisdb.phy_neuroassessment')
                                ->where('compcode','=',session('compcode'))
                                ->where('idno','=',$request->n_idno);
                                // ->where('mrn','=',$request->mrn)
                                // ->where('episno','=',$request->episno);
        
        $romaffectedside_obj = DB::table('hisdb.phy_romaffectedside')
                                ->where('compcode','=',session('compcode'))
                                ->where('idno','=',$request->a_idno);
                                // ->where('mrn','=',$request->mrn)
                                // ->where('episno','=',$request->episno);
        
        $romsoundside_obj = DB::table('hisdb.phy_romsoundside')
                            ->where('compcode','=',session('compcode'))
                            ->where('idno','=',$request->s_idno);
                            // ->where('mrn','=',$request->mrn)
                            // ->where('episno','=',$request->episno);
        
        $musclepower_obj = DB::table('hisdb.phy_musclepower')
                            ->where('compcode','=',session('compcode'))
                            ->where('idno','=',$request->m_idno);
                            // ->where('mrn','=',$request->mrn)
                            // ->where('episno','=',$request->episno);
        
        $responce = new stdClass();
        
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
        
        return json_encode($responce);
        
    }
    
    public function get_datetime_neuroAssessment(Request $request){
        
        $responce = new stdClass();
        
        $neuroassessment_obj = DB::table('hisdb.phy_neuroassessment as n')
                                ->select('n.idno as n_idno','n.mrn','n.episno','n.entereddate','n.adduser','a.idno as a_idno','s.idno as s_idno','m.idno as m_idno')
                                ->join('hisdb.phy_romaffectedside as a', function ($join) use ($request){
                                    $join = $join->on('a.mrn','=','n.mrn');
                                    $join = $join->on('a.episno','=','n.episno');
                                    $join = $join->on('a.compcode','=','n.compcode');
                                    $join = $join->on('a.entereddate','=','n.entereddate');
                                    $join = $join->where('a.type','=','neurological');
                                })
                                ->join('hisdb.phy_romsoundside as s', function ($join) use ($request){
                                    $join = $join->on('s.mrn','=','n.mrn');
                                    $join = $join->on('s.episno','=','n.episno');
                                    $join = $join->on('s.compcode','=','n.compcode');
                                    $join = $join->on('s.entereddate','=','n.entereddate');
                                    $join = $join->where('s.type','=','neurological');
                                })
                                ->join('hisdb.phy_musclepower as m', function ($join) use ($request){
                                    $join = $join->on('m.mrn','=','n.mrn');
                                    $join = $join->on('m.episno','=','n.episno');
                                    $join = $join->on('m.compcode','=','n.compcode');
                                    $join = $join->on('m.entereddate','=','n.entereddate');
                                    $join = $join->where('m.type','=','neurological');
                                })
                                ->where('n.compcode','=',session('compcode'))
                                ->where('n.mrn','=',$request->mrn)
                                ->where('n.episno','=',$request->episno)
                                ->where('n.type','=','neurological');
        
        if($neuroassessment_obj->exists()){
            $neuroassessment_obj = $neuroassessment_obj->get();
            
            $data = [];
            
            foreach($neuroassessment_obj as $key => $value){
                $date['n_idno'] = $value->n_idno;
                $date['mrn'] = $value->mrn;
                $date['episno'] = $value->episno;
                if(!empty($value->entereddate)){
                    $date['entereddate'] =  Carbon::createFromFormat('Y-m-d', $value->entereddate)->format('d-m-Y');
                }else{
                    $date['entereddate'] =  '-';
                }
                $date['dt'] = $value->entereddate; // for sorting
                $date['adduser'] = $value->adduser;
                $date['a_idno'] = $value->a_idno;
                $date['s_idno'] = $value->s_idno;
                $date['m_idno'] = $value->m_idno;
                
                array_push($data,$date);
            }
            
            $responce->data = $data;
        }else{
            $responce->data = [];
        }
        
        return json_encode($responce);
        
    }
    
    public function neuroassessment_chart(Request $request){
        
        $mrn = $request->mrn;
        $episno = $request->episno;
        $entereddate = $request->entereddate;
        $type1 = $request->type1;
        $type2 = $request->type2;
        if(!$mrn || !$episno || !$entereddate){
            abort(404);
        }
        
        $neuroassessment = DB::table('hisdb.phy_neuroassessment as n')
                            ->select('n.idno','n.compcode','n.mrn','n.episno','n.type','n.entereddate','n.objective','n.painscore','n.painType','n.severityBC','n.irritabilityBC','n.painLocation','n.subluxation','n.palpationBC','n.impressionBC','n.superficialR','n.superficialL','n.superficialSpec','n.deepR','n.deepL','n.deepSpec','n.numbnessR','n.numbnessL','n.numbnessSpec','n.paresthesiaR','n.paresthesiaL','n.paresthesiaSpec','n.otherR','n.otherL','n.otherSpec','n.impressionSens','n.muscleUL','n.muscleLL','n.impressionMAS','n.btrRT','n.btrLT','n.ttrRT','n.ttrLT','n.ktrRT','n.ktrLT','n.atrRT','n.atrLT','n.babinskyRT','n.babinskyLT','n.impressionDTR','n.fingerTestR','n.fingerTestL','n.heelTestR','n.heelTestL','n.impressionCoord','n.transferInit','n.transferProg','n.transferFin','n.suptoSideInit','n.suptoSideProg','n.suptoSideFin','n.sideToSitInit','n.sideToSitProg','n.sideToSitFin','n.sittInit','n.sittProg','n.sittFin','n.sitToStdInit','n.sitToStdProg','n.sitToStdFin','n.stdInit','n.stdProg','n.stdFin','n.shiftInit','n.shiftProg','n.shiftFin','n.ambulationInit','n.ambulationProg','n.ambulationFin','n.impressionFA','n.summary','n.adduser','n.adddate','n.upduser','n.upddate','n.lastuser','n.lastupdate','n.computerid','pm.Name','pm.Newic')
                            ->leftjoin('hisdb.pat_mast as pm', function ($join){
                                $join = $join->on('pm.MRN','=','n.mrn');
                                $join = $join->on('pm.Episno','=','n.episno');
                                $join = $join->where('pm.compcode','=',session('compcode'));
                            })
                            ->where('n.compcode','=',session('compcode'))
                            ->where('n.mrn','=',$mrn)
                            ->where('n.episno','=',$episno)
                            ->where('n.entereddate','=',$entereddate)
                            ->where('n.type','=','neurological')
                            ->first();
        // dd($neuroassessment);
        
        $romaffectedside = DB::table('hisdb.phy_romaffectedside')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$mrn)
                            ->where('episno','=',$episno)
                            ->where('entereddate','=',$entereddate)
                            ->where('type','=','neurological')
                            ->first();
        
        $romsoundside = DB::table('hisdb.phy_romsoundside')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$mrn)
                        ->where('episno','=',$episno)
                        ->where('entereddate','=',$entereddate)
                        ->where('type','=','neurological')
                        ->first();
        
        $musclepower = DB::table('hisdb.phy_musclepower')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$mrn)
                        ->where('episno','=',$episno)
                        ->where('entereddate','=',$entereddate)
                        ->where('type','=','neurological')
                        ->first();
        
        $company = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();
        
        $attachment_files1 = $this->get_attachment_files1($mrn,$episno,$entereddate,$type1);
        $attachment_files2 = $this->get_attachment_files2($mrn,$episno,$entereddate,$type2);
        // dd($attachment_files);
        
        return view('rehab.neuroAssessmentChart_pdfmake',compact('neuroassessment','romaffectedside','romsoundside','musclepower','company','attachment_files1','attachment_files2'));
        
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
        $filepath = public_path().'/hisweb/uploads/ftp/'.$filename;
        $ftppath = "/patientcare_upload/pdf/".$filename;
        
        $exists = Storage::disk('ftp')->exists($ftppath);
        
        if($exists){
            $file = Storage::disk('ftp')->get($ftppath);
            Storage::disk('ftp_uploads')->put($filename, $file);
            
            return '../hisweb/uploads/ftp/'.$filename;
            
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
        $filepath = public_path().'/hisweb/uploads/ftp/'.$filename;
        $ftppath = "/patientcare_upload/pdf/".$filename;
        
        $exists = Storage::disk('ftp')->exists($ftppath);
        
        if($exists){
            $file = Storage::disk('ftp')->get($ftppath);
            Storage::disk('ftp_uploads')->put($filename, $file);
            
            return '../hisweb/uploads/ftp/'.$filename;
            
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