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

class SpinalCordController extends defaultController
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function show(Request $request)
    {
        return view('rehab.spinalCord');
    }
    
    public function form(Request $request)
    {
        DB::enableQueryLog();
        switch($request->action){
            case 'save_table_spinalCord':
                switch($request->oper){
                    case 'add':
                        return $this->add_spinalCord($request);
                    case 'edit':
                        return $this->edit_spinalCord($request);
                    default:
                        return 'error happen..';
                }
            
            case 'get_table_spinalCord':
                return $this->get_table_spinalCord($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function table(Request $request)
    {
        switch($request->action){
            case 'get_datetime_spinalCord':
                return $this->get_datetime_spinalCord($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function add_spinalCord(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $spinalcord = DB::table('hisdb.phy_spinalcord')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno)
                        ->where('entereddate','=',$request->entereddate);
            
            if($spinalcord->exists()){
                // throw new \Exception('Date already exist.', 500);
                return response('Date already exist.');
            }
            
            DB::table('hisdb.phy_spinalcord')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn,
                    'episno' => $request->episno,
                    'entereddate' => $request->entereddate,
                    'ltrC2' => $request->ltrC2,
                    'pprC2' => $request->pprC2,
                    'ltrC3' => $request->ltrC3,
                    'pprC3' => $request->pprC3,
                    'ltrC4' => $request->ltrC4,
                    'pprC4' => $request->pprC4,
                    'motorRC5' => $request->motorRC5,
                    'ltrC5' => $request->ltrC5,
                    'pprC5' => $request->pprC5,
                    'motorRC6' => $request->motorRC6,
                    'ltrC6' => $request->ltrC6,
                    'pprC6' => $request->pprC6,
                    'motorRC7' => $request->motorRC7,
                    'ltrC7' => $request->ltrC7,
                    'pprC7' => $request->pprC7,
                    'motorRC8' => $request->motorRC8,
                    'ltrC8' => $request->ltrC8,
                    'pprC8' => $request->pprC8,
                    'motorRT1' => $request->motorRT1,
                    'ltrT1' => $request->ltrT1,
                    'pprT1' => $request->pprT1,
                    'comments' => $request->comments,
                    'ltrT2' => $request->ltrT2,
                    'pprT2' => $request->pprT2,
                    'ltrT3' => $request->ltrT3,
                    'pprT3' => $request->pprT3,
                    'ltrT4' => $request->ltrT4,
                    'pprT4' => $request->pprT4,
                    'ltrT5' => $request->ltrT5,
                    'pprT5' => $request->pprT5,
                    'ltrT6' => $request->ltrT6,
                    'pprT6' => $request->pprT6,
                    'ltrT7' => $request->ltrT7,
                    'pprT7' => $request->pprT7,
                    'ltrT8' => $request->ltrT8,
                    'pprT8' => $request->pprT8,
                    'ltrT9' => $request->ltrT9,
                    'pprT9' => $request->pprT9,
                    'ltrT10' => $request->ltrT10,
                    'pprT10' => $request->pprT10,
                    'ltrT11' => $request->ltrT11,
                    'pprT11' => $request->pprT11,
                    'ltrT12' => $request->ltrT12,
                    'pprT12' => $request->pprT12,
                    'ltrL1' => $request->ltrL1,
                    'pprL1' => $request->pprL1,
                    'motorRL2' => $request->motorRL2,
                    'ltrL2' => $request->ltrL2,
                    'pprL2' => $request->pprL2,
                    'motorRL3' => $request->motorRL3,
                    'ltrL3' => $request->ltrL3,
                    'pprL3' => $request->pprL3,
                    'motorRL4' => $request->motorRL4,
                    'ltrL4' => $request->ltrL4,
                    'pprL4' => $request->pprL4,
                    'motorRL5' => $request->motorRL5,
                    'ltrL5' => $request->ltrL5,
                    'pprL5' => $request->pprL5,
                    'motorRS1' => $request->motorRS1,
                    'ltrS1' => $request->ltrS1,
                    'pprS1' => $request->pprS1,
                    'ltrS2' => $request->ltrS2,
                    'pprS2' => $request->pprS2,
                    'ltrS3' => $request->ltrS3,
                    'pprS3' => $request->pprS3,
                    'vac' => $request->vac,
                    'ltrS4' => $request->ltrS4,
                    'pprS4' => $request->pprS4,
                    'motorRTotal' => $request->motorRTotal,
                    'ltrTotal' => $request->ltrTotal,
                    'pprTotal' => $request->pprTotal,
                    'ltlC2' => $request->ltlC2,
                    'pplC2' => $request->pplC2,
                    'ltlC3' => $request->ltlC3,
                    'pplC3' => $request->pplC3,
                    'ltlC4' => $request->ltlC4,
                    'pplC4' => $request->pplC4,
                    'ltlC5' => $request->ltlC5,
                    'pplC5' => $request->pplC5,
                    'motorLC5' => $request->motorLC5,
                    'ltlC6' => $request->ltlC6,
                    'pplC6' => $request->pplC6,
                    'motorLC6' => $request->motorLC6,
                    'ltlC7' => $request->ltlC7,
                    'pplC7' => $request->pplC7,
                    'motorLC7' => $request->motorLC7,
                    'ltlC8' => $request->ltlC8,
                    'pplC8' => $request->pplC8,
                    'motorLC8' => $request->motorLC8,
                    'ltlT1' => $request->ltlT1,
                    'pplT1' => $request->pplT1,
                    'motorLT1' => $request->motorLT1,
                    'ltlT2' => $request->ltlT2,
                    'pplT2' => $request->pplT2,
                    'ltlT3' => $request->ltlT3,
                    'pplT3' => $request->pplT3,
                    'ltlT4' => $request->ltlT4,
                    'pplT4' => $request->pplT4,
                    'ltlT5' => $request->ltlT5,
                    'pplT5' => $request->pplT5,
                    'ltlT6' => $request->ltlT6,
                    'pplT6' => $request->pplT6,
                    'ltlT7' => $request->ltlT7,
                    'pplT7' => $request->pplT7,
                    'ltlT8' => $request->ltlT8,
                    'pplT8' => $request->pplT8,
                    'ltlT9' => $request->ltlT9,
                    'pplT9' => $request->pplT9,
                    'ltlT10' => $request->ltlT10,
                    'pplT10' => $request->pplT10,
                    'ltlT11' => $request->ltlT11,
                    'pplT11' => $request->pplT11,
                    'ltlT12' => $request->ltlT12,
                    'pplT12' => $request->pplT12,
                    'ltlL1' => $request->ltlL1,
                    'pplL1' => $request->pplL1,
                    'ltlL2' => $request->ltlL2,
                    'pplL2' => $request->pplL2,
                    'motorLL2' => $request->motorLL2,
                    'ltlL3' => $request->ltlL3,
                    'pplL3' => $request->pplL3,
                    'motorLL3' => $request->motorLL3,
                    'ltlL4' => $request->ltlL4,
                    'pplL4' => $request->pplL4,
                    'motorLL4' => $request->motorLL4,
                    'ltlL5' => $request->ltlL5,
                    'pplL5' => $request->pplL5,
                    'motorLL5' => $request->motorLL5,
                    'ltlS1' => $request->ltlS1,
                    'pplS1' => $request->pplS1,
                    'motorLS1' => $request->motorLS1,
                    'ltlS2' => $request->ltlS2,
                    'pplS2' => $request->pplS2,
                    'ltlS3' => $request->ltlS3,
                    'pplS3' => $request->pplS3,
                    'ltlS4' => $request->ltlS4,
                    'pplS4' => $request->pplS4,
                    'dap' => $request->dap,
                    'ltlTotal' => $request->ltlTotal,
                    'pplTotal' => $request->pplTotal,
                    'motorLTotal' => $request->motorLTotal,
                    'uer' => $request->uer,
                    'uel' => $request->uel,
                    'uemsTotal' => $request->uemsTotal,
                    'ler' => $request->ler,
                    'lel' => $request->lel,
                    'lemsTotal' => $request->lemsTotal,
                    'ltr' => $request->ltr,
                    'ltl' => $request->ltl,
                    'ltTotal' => $request->ltTotal,
                    'ppr' => $request->ppr,
                    'ppl' => $request->ppl,
                    'ppTotal' => $request->ppTotal,
                    'sensoryRNL' => $request->sensoryRNL,
                    'sensoryLNL' => $request->sensoryLNL,
                    'motorRNL' => $request->motorRNL,
                    'motorLNL' => $request->motorLNL,
                    'nli' => $request->nli,
                    'completion' => $request->completion,
                    'ais' => $request->ais,
                    'sensoryRPP' => $request->sensoryRPP,
                    'sensoryLPP' => $request->sensoryLPP,
                    'motorRPP' => $request->motorRPP,
                    'motorLPP' => $request->motorLPP,
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
    
    public function edit_spinalCord(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $spinalcord = DB::table('hisdb.phy_spinalcord')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno)
                        ->where('entereddate','=',$request->entereddate);
            
            if(!empty($request->idno_spinalCord)){
                if($spinalcord->exists()){
                    if($spinalcord->first()->idno != $request->idno_spinalCord){
                        // throw new \Exception('Date already exist.', 500);
                        return response('Date already exist.');
                    }
                }
                
                DB::table('hisdb.phy_spinalcord')
                    ->where('idno','=',$request->idno_spinalCord)
                    // ->where('mrn','=',$request->mrn)
                    // ->where('episno','=',$request->episno)
                    // ->where('compcode','=',session('compcode'))
                    ->update([
                        'entereddate' => $request->entereddate,
                        'ltrC2' => $request->ltrC2,
                        'pprC2' => $request->pprC2,
                        'ltrC3' => $request->ltrC3,
                        'pprC3' => $request->pprC3,
                        'ltrC4' => $request->ltrC4,
                        'pprC4' => $request->pprC4,
                        'motorRC5' => $request->motorRC5,
                        'ltrC5' => $request->ltrC5,
                        'pprC5' => $request->pprC5,
                        'motorRC6' => $request->motorRC6,
                        'ltrC6' => $request->ltrC6,
                        'pprC6' => $request->pprC6,
                        'motorRC7' => $request->motorRC7,
                        'ltrC7' => $request->ltrC7,
                        'pprC7' => $request->pprC7,
                        'motorRC8' => $request->motorRC8,
                        'ltrC8' => $request->ltrC8,
                        'pprC8' => $request->pprC8,
                        'motorRT1' => $request->motorRT1,
                        'ltrT1' => $request->ltrT1,
                        'pprT1' => $request->pprT1,
                        'comments' => $request->comments,
                        'ltrT2' => $request->ltrT2,
                        'pprT2' => $request->pprT2,
                        'ltrT3' => $request->ltrT3,
                        'pprT3' => $request->pprT3,
                        'ltrT4' => $request->ltrT4,
                        'pprT4' => $request->pprT4,
                        'ltrT5' => $request->ltrT5,
                        'pprT5' => $request->pprT5,
                        'ltrT6' => $request->ltrT6,
                        'pprT6' => $request->pprT6,
                        'ltrT7' => $request->ltrT7,
                        'pprT7' => $request->pprT7,
                        'ltrT8' => $request->ltrT8,
                        'pprT8' => $request->pprT8,
                        'ltrT9' => $request->ltrT9,
                        'pprT9' => $request->pprT9,
                        'ltrT10' => $request->ltrT10,
                        'pprT10' => $request->pprT10,
                        'ltrT11' => $request->ltrT11,
                        'pprT11' => $request->pprT11,
                        'ltrT12' => $request->ltrT12,
                        'pprT12' => $request->pprT12,
                        'ltrL1' => $request->ltrL1,
                        'pprL1' => $request->pprL1,
                        'motorRL2' => $request->motorRL2,
                        'ltrL2' => $request->ltrL2,
                        'pprL2' => $request->pprL2,
                        'motorRL3' => $request->motorRL3,
                        'ltrL3' => $request->ltrL3,
                        'pprL3' => $request->pprL3,
                        'motorRL4' => $request->motorRL4,
                        'ltrL4' => $request->ltrL4,
                        'pprL4' => $request->pprL4,
                        'motorRL5' => $request->motorRL5,
                        'ltrL5' => $request->ltrL5,
                        'pprL5' => $request->pprL5,
                        'motorRS1' => $request->motorRS1,
                        'ltrS1' => $request->ltrS1,
                        'pprS1' => $request->pprS1,
                        'ltrS2' => $request->ltrS2,
                        'pprS2' => $request->pprS2,
                        'ltrS3' => $request->ltrS3,
                        'pprS3' => $request->pprS3,
                        'vac' => $request->vac,
                        'ltrS4' => $request->ltrS4,
                        'pprS4' => $request->pprS4,
                        'motorRTotal' => $request->motorRTotal,
                        'ltrTotal' => $request->ltrTotal,
                        'pprTotal' => $request->pprTotal,
                        'ltlC2' => $request->ltlC2,
                        'pplC2' => $request->pplC2,
                        'ltlC3' => $request->ltlC3,
                        'pplC3' => $request->pplC3,
                        'ltlC4' => $request->ltlC4,
                        'pplC4' => $request->pplC4,
                        'ltlC5' => $request->ltlC5,
                        'pplC5' => $request->pplC5,
                        'motorLC5' => $request->motorLC5,
                        'ltlC6' => $request->ltlC6,
                        'pplC6' => $request->pplC6,
                        'motorLC6' => $request->motorLC6,
                        'ltlC7' => $request->ltlC7,
                        'pplC7' => $request->pplC7,
                        'motorLC7' => $request->motorLC7,
                        'ltlC8' => $request->ltlC8,
                        'pplC8' => $request->pplC8,
                        'motorLC8' => $request->motorLC8,
                        'ltlT1' => $request->ltlT1,
                        'pplT1' => $request->pplT1,
                        'motorLT1' => $request->motorLT1,
                        'ltlT2' => $request->ltlT2,
                        'pplT2' => $request->pplT2,
                        'ltlT3' => $request->ltlT3,
                        'pplT3' => $request->pplT3,
                        'ltlT4' => $request->ltlT4,
                        'pplT4' => $request->pplT4,
                        'ltlT5' => $request->ltlT5,
                        'pplT5' => $request->pplT5,
                        'ltlT6' => $request->ltlT6,
                        'pplT6' => $request->pplT6,
                        'ltlT7' => $request->ltlT7,
                        'pplT7' => $request->pplT7,
                        'ltlT8' => $request->ltlT8,
                        'pplT8' => $request->pplT8,
                        'ltlT9' => $request->ltlT9,
                        'pplT9' => $request->pplT9,
                        'ltlT10' => $request->ltlT10,
                        'pplT10' => $request->pplT10,
                        'ltlT11' => $request->ltlT11,
                        'pplT11' => $request->pplT11,
                        'ltlT12' => $request->ltlT12,
                        'pplT12' => $request->pplT12,
                        'ltlL1' => $request->ltlL1,
                        'pplL1' => $request->pplL1,
                        'ltlL2' => $request->ltlL2,
                        'pplL2' => $request->pplL2,
                        'motorLL2' => $request->motorLL2,
                        'ltlL3' => $request->ltlL3,
                        'pplL3' => $request->pplL3,
                        'motorLL3' => $request->motorLL3,
                        'ltlL4' => $request->ltlL4,
                        'pplL4' => $request->pplL4,
                        'motorLL4' => $request->motorLL4,
                        'ltlL5' => $request->ltlL5,
                        'pplL5' => $request->pplL5,
                        'motorLL5' => $request->motorLL5,
                        'ltlS1' => $request->ltlS1,
                        'pplS1' => $request->pplS1,
                        'motorLS1' => $request->motorLS1,
                        'ltlS2' => $request->ltlS2,
                        'pplS2' => $request->pplS2,
                        'ltlS3' => $request->ltlS3,
                        'pplS3' => $request->pplS3,
                        'ltlS4' => $request->ltlS4,
                        'pplS4' => $request->pplS4,
                        'dap' => $request->dap,
                        'ltlTotal' => $request->ltlTotal,
                        'pplTotal' => $request->pplTotal,
                        'motorLTotal' => $request->motorLTotal,
                        'uer' => $request->uer,
                        'uel' => $request->uel,
                        'uemsTotal' => $request->uemsTotal,
                        'ler' => $request->ler,
                        'lel' => $request->lel,
                        'lemsTotal' => $request->lemsTotal,
                        'ltr' => $request->ltr,
                        'ltl' => $request->ltl,
                        'ltTotal' => $request->ltTotal,
                        'ppr' => $request->ppr,
                        'ppl' => $request->ppl,
                        'ppTotal' => $request->ppTotal,
                        'sensoryRNL' => $request->sensoryRNL,
                        'sensoryLNL' => $request->sensoryLNL,
                        'motorRNL' => $request->motorRNL,
                        'motorLNL' => $request->motorLNL,
                        'nli' => $request->nli,
                        'completion' => $request->completion,
                        'ais' => $request->ais,
                        'sensoryRPP' => $request->sensoryRPP,
                        'sensoryLPP' => $request->sensoryLPP,
                        'motorRPP' => $request->motorRPP,
                        'motorLPP' => $request->motorLPP,
                        'upduser'  => session('username'),
                        'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
            }else{
                if($spinalcord->exists()){
                    // throw new \Exception('Date already exist.', 500);
                    return response('Date already exist.');
                }
                
                DB::table('hisdb.phy_spinalcord')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'entereddate' => $request->entereddate,
                        'ltrC2' => $request->ltrC2,
                        'pprC2' => $request->pprC2,
                        'ltrC3' => $request->ltrC3,
                        'pprC3' => $request->pprC3,
                        'ltrC4' => $request->ltrC4,
                        'pprC4' => $request->pprC4,
                        'motorRC5' => $request->motorRC5,
                        'ltrC5' => $request->ltrC5,
                        'pprC5' => $request->pprC5,
                        'motorRC6' => $request->motorRC6,
                        'ltrC6' => $request->ltrC6,
                        'pprC6' => $request->pprC6,
                        'motorRC7' => $request->motorRC7,
                        'ltrC7' => $request->ltrC7,
                        'pprC7' => $request->pprC7,
                        'motorRC8' => $request->motorRC8,
                        'ltrC8' => $request->ltrC8,
                        'pprC8' => $request->pprC8,
                        'motorRT1' => $request->motorRT1,
                        'ltrT1' => $request->ltrT1,
                        'pprT1' => $request->pprT1,
                        'comments' => $request->comments,
                        'ltrT2' => $request->ltrT2,
                        'pprT2' => $request->pprT2,
                        'ltrT3' => $request->ltrT3,
                        'pprT3' => $request->pprT3,
                        'ltrT4' => $request->ltrT4,
                        'pprT4' => $request->pprT4,
                        'ltrT5' => $request->ltrT5,
                        'pprT5' => $request->pprT5,
                        'ltrT6' => $request->ltrT6,
                        'pprT6' => $request->pprT6,
                        'ltrT7' => $request->ltrT7,
                        'pprT7' => $request->pprT7,
                        'ltrT8' => $request->ltrT8,
                        'pprT8' => $request->pprT8,
                        'ltrT9' => $request->ltrT9,
                        'pprT9' => $request->pprT9,
                        'ltrT10' => $request->ltrT10,
                        'pprT10' => $request->pprT10,
                        'ltrT11' => $request->ltrT11,
                        'pprT11' => $request->pprT11,
                        'ltrT12' => $request->ltrT12,
                        'pprT12' => $request->pprT12,
                        'ltrL1' => $request->ltrL1,
                        'pprL1' => $request->pprL1,
                        'motorRL2' => $request->motorRL2,
                        'ltrL2' => $request->ltrL2,
                        'pprL2' => $request->pprL2,
                        'motorRL3' => $request->motorRL3,
                        'ltrL3' => $request->ltrL3,
                        'pprL3' => $request->pprL3,
                        'motorRL4' => $request->motorRL4,
                        'ltrL4' => $request->ltrL4,
                        'pprL4' => $request->pprL4,
                        'motorRL5' => $request->motorRL5,
                        'ltrL5' => $request->ltrL5,
                        'pprL5' => $request->pprL5,
                        'motorRS1' => $request->motorRS1,
                        'ltrS1' => $request->ltrS1,
                        'pprS1' => $request->pprS1,
                        'ltrS2' => $request->ltrS2,
                        'pprS2' => $request->pprS2,
                        'ltrS3' => $request->ltrS3,
                        'pprS3' => $request->pprS3,
                        'vac' => $request->vac,
                        'ltrS4' => $request->ltrS4,
                        'pprS4' => $request->pprS4,
                        'motorRTotal' => $request->motorRTotal,
                        'ltrTotal' => $request->ltrTotal,
                        'pprTotal' => $request->pprTotal,
                        'ltlC2' => $request->ltlC2,
                        'pplC2' => $request->pplC2,
                        'ltlC3' => $request->ltlC3,
                        'pplC3' => $request->pplC3,
                        'ltlC4' => $request->ltlC4,
                        'pplC4' => $request->pplC4,
                        'ltlC5' => $request->ltlC5,
                        'pplC5' => $request->pplC5,
                        'motorLC5' => $request->motorLC5,
                        'ltlC6' => $request->ltlC6,
                        'pplC6' => $request->pplC6,
                        'motorLC6' => $request->motorLC6,
                        'ltlC7' => $request->ltlC7,
                        'pplC7' => $request->pplC7,
                        'motorLC7' => $request->motorLC7,
                        'ltlC8' => $request->ltlC8,
                        'pplC8' => $request->pplC8,
                        'motorLC8' => $request->motorLC8,
                        'ltlT1' => $request->ltlT1,
                        'pplT1' => $request->pplT1,
                        'motorLT1' => $request->motorLT1,
                        'ltlT2' => $request->ltlT2,
                        'pplT2' => $request->pplT2,
                        'ltlT3' => $request->ltlT3,
                        'pplT3' => $request->pplT3,
                        'ltlT4' => $request->ltlT4,
                        'pplT4' => $request->pplT4,
                        'ltlT5' => $request->ltlT5,
                        'pplT5' => $request->pplT5,
                        'ltlT6' => $request->ltlT6,
                        'pplT6' => $request->pplT6,
                        'ltlT7' => $request->ltlT7,
                        'pplT7' => $request->pplT7,
                        'ltlT8' => $request->ltlT8,
                        'pplT8' => $request->pplT8,
                        'ltlT9' => $request->ltlT9,
                        'pplT9' => $request->pplT9,
                        'ltlT10' => $request->ltlT10,
                        'pplT10' => $request->pplT10,
                        'ltlT11' => $request->ltlT11,
                        'pplT11' => $request->pplT11,
                        'ltlT12' => $request->ltlT12,
                        'pplT12' => $request->pplT12,
                        'ltlL1' => $request->ltlL1,
                        'pplL1' => $request->pplL1,
                        'ltlL2' => $request->ltlL2,
                        'pplL2' => $request->pplL2,
                        'motorLL2' => $request->motorLL2,
                        'ltlL3' => $request->ltlL3,
                        'pplL3' => $request->pplL3,
                        'motorLL3' => $request->motorLL3,
                        'ltlL4' => $request->ltlL4,
                        'pplL4' => $request->pplL4,
                        'motorLL4' => $request->motorLL4,
                        'ltlL5' => $request->ltlL5,
                        'pplL5' => $request->pplL5,
                        'motorLL5' => $request->motorLL5,
                        'ltlS1' => $request->ltlS1,
                        'pplS1' => $request->pplS1,
                        'motorLS1' => $request->motorLS1,
                        'ltlS2' => $request->ltlS2,
                        'pplS2' => $request->pplS2,
                        'ltlS3' => $request->ltlS3,
                        'pplS3' => $request->pplS3,
                        'ltlS4' => $request->ltlS4,
                        'pplS4' => $request->pplS4,
                        'dap' => $request->dap,
                        'ltlTotal' => $request->ltlTotal,
                        'pplTotal' => $request->pplTotal,
                        'motorLTotal' => $request->motorLTotal,
                        'uer' => $request->uer,
                        'uel' => $request->uel,
                        'uemsTotal' => $request->uemsTotal,
                        'ler' => $request->ler,
                        'lel' => $request->lel,
                        'lemsTotal' => $request->lemsTotal,
                        'ltr' => $request->ltr,
                        'ltl' => $request->ltl,
                        'ltTotal' => $request->ltTotal,
                        'ppr' => $request->ppr,
                        'ppl' => $request->ppl,
                        'ppTotal' => $request->ppTotal,
                        'sensoryRNL' => $request->sensoryRNL,
                        'sensoryLNL' => $request->sensoryLNL,
                        'motorRNL' => $request->motorRNL,
                        'motorLNL' => $request->motorLNL,
                        'nli' => $request->nli,
                        'completion' => $request->completion,
                        'ais' => $request->ais,
                        'sensoryRPP' => $request->sensoryRPP,
                        'sensoryLPP' => $request->sensoryLPP,
                        'motorRPP' => $request->motorRPP,
                        'motorLPP' => $request->motorLPP,
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
    
    public function get_table_spinalCord(Request $request){
        
        $spinalcord_obj = DB::table('hisdb.phy_spinalcord')
                        ->where('compcode','=',session('compcode'))
                        ->where('idno','=',$request->idno);
                        // ->where('mrn','=',$request->mrn)
                        // ->where('episno','=',$request->episno);
        
        $responce = new stdClass();
        
        if($spinalcord_obj->exists()){
            $spinalcord_obj = $spinalcord_obj->first();
            $responce->spinalcord = $spinalcord_obj;
        }
        
        return json_encode($responce);
        
    }
    
    public function get_datetime_spinalCord(Request $request){
        
        $responce = new stdClass();
        
        $spinalcord_obj = DB::table('hisdb.phy_spinalcord')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno);
        
        if($spinalcord_obj->exists()){
            $spinalcord_obj = $spinalcord_obj->get();
            
            $data = [];
            
            foreach($spinalcord_obj as $key => $value){
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
    
    public function spinalcord_chart(Request $request){
        
        $mrn = $request->mrn;
        $episno = $request->episno;
        $entereddate = $request->entereddate;
        if(!$mrn || !$episno || !$entereddate){
            abort(404);
        }
        
        $spinalcord = DB::table('hisdb.phy_spinalcord as sc')
                    ->select('sc.idno','sc.compcode','sc.mrn','sc.episno','sc.entereddate','sc.ltrC2','sc.pprC2','sc.ltrC3','sc.pprC3','sc.ltrC4','sc.pprC4','sc.motorRC5','sc.ltrC5','sc.pprC5','sc.motorRC6','sc.ltrC6','sc.pprC6','sc.motorRC7','sc.ltrC7','sc.pprC7','sc.motorRC8','sc.ltrC8','sc.pprC8','sc.motorRT1','sc.ltrT1','sc.pprT1','sc.comments','sc.ltrT2','sc.pprT2','sc.ltrT3','sc.pprT3','sc.ltrT4','sc.pprT4','sc.ltrT5','sc.pprT5','sc.ltrT6','sc.pprT6','sc.ltrT7','sc.pprT7','sc.ltrT8','sc.pprT8','sc.ltrT9','sc.pprT9','sc.ltrT10','sc.pprT10','sc.ltrT11','sc.pprT11','sc.ltrT12','sc.pprT12','sc.ltrL1','sc.pprL1','sc.motorRL2','sc.ltrL2','sc.pprL2','sc.motorRL3','sc.ltrL3','sc.pprL3','sc.motorRL4','sc.ltrL4','sc.pprL4','sc.motorRL5','sc.ltrL5','sc.pprL5','sc.motorRS1','sc.ltrS1','sc.pprS1','sc.ltrS2','sc.pprS2','sc.ltrS3','sc.pprS3','sc.vac','sc.ltrS4','sc.pprS4','sc.motorRTotal','sc.ltrTotal','sc.pprTotal','sc.ltlC2','sc.pplC2','sc.ltlC3','sc.pplC3','sc.ltlC4','sc.pplC4','sc.ltlC5','sc.pplC5','sc.motorLC5','sc.ltlC6','sc.pplC6','sc.motorLC6','sc.ltlC7','sc.pplC7','sc.motorLC7','sc.ltlC8','sc.pplC8','sc.motorLC8','sc.ltlT1','sc.pplT1','sc.motorLT1','sc.ltlT2','sc.pplT2','sc.ltlT3','sc.pplT3','sc.ltlT4','sc.pplT4','sc.ltlT5','sc.pplT5','sc.ltlT6','sc.pplT6','sc.ltlT7','sc.pplT7','sc.ltlT8','sc.pplT8','sc.ltlT9','sc.pplT9','sc.ltlT10','sc.pplT10','sc.ltlT11','sc.pplT11','sc.ltlT12','sc.pplT12','sc.ltlL1','sc.pplL1','sc.ltlL2','sc.pplL2','sc.motorLL2','sc.ltlL3','sc.pplL3','sc.motorLL3','sc.ltlL4','sc.pplL4','sc.motorLL4','sc.ltlL5','sc.pplL5','sc.motorLL5','sc.ltlS1','sc.pplS1','sc.motorLS1','sc.ltlS2','sc.pplS2','sc.ltlS3','sc.pplS3','sc.ltlS4','sc.pplS4','sc.dap','sc.ltlTotal','sc.pplTotal','sc.motorLTotal','sc.uer','sc.uel','sc.uemsTotal','sc.ler','sc.lel','sc.lemsTotal','sc.ltr','sc.ltl','sc.ltTotal','sc.ppr','sc.ppl','sc.ppTotal','sc.sensoryRNL','sc.sensoryLNL','sc.motorRNL','sc.motorLNL','sc.nli','sc.completion','sc.ais','sc.sensoryRPP','sc.sensoryLPP','sc.motorRPP','sc.motorLPP','sc.adduser','sc.adddate','sc.upduser','sc.upddate','sc.lastuser','sc.lastupdate','sc.computerid','pm.Name','pm.Newic')
                    ->leftjoin('hisdb.pat_mast as pm', function ($join){
                        $join = $join->on('pm.MRN','=','sc.mrn');
                        // $join = $join->on('pm.Episno','=','sc.episno');
                        $join = $join->where('pm.compcode','=',session('compcode'));
                    })
                    ->where('sc.compcode','=',session('compcode'))
                    ->where('sc.mrn','=',$mrn)
                    ->where('sc.episno','=',$episno)
                    ->where('sc.entereddate','=',$entereddate)
                    ->first();
        // dd($spinalcord);
        
        $company = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();
        
        return view('rehab.spinalCordChart_pdfmake',compact('spinalcord','company'));
        
    }
    
}