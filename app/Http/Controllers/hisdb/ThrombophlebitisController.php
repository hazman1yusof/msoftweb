<?php

namespace App\Http\Controllers\hisdb;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class ThrombophlebitisController extends defaultController
{
    
    var $table;
    var $duplicateCode;
    
    public function __construct(){
        $this->middleware('auth');
        // $this->duplicateCode = "chgtype";
    }
    
    public function show(Request $request){
        return view('hisdb.nursingnote.nursingnote');
    }
    
    public function table(Request $request)
    {
        switch($request->action){
            case 'get_table_datetimeThrombo': // PIVC
                return $this->get_table_datetimeThrombo($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function form(Request $request){
        DB::enableQueryLog();
        switch($request->action){
            case 'save_table_thrombo':
                switch($request->oper){
                    case 'add':
                        return $this->add_thrombo($request);
                    case 'edit':
                        return $this->edit_thrombo($request);
                    default:
                        return 'error happen..';
                }
            
            case 'get_table_thrombo':
                return $this->get_table_thrombo($request);

            case 'addThrombo_save':
                return $this->add_thrombojqgrid($request);
            
            case 'addThrombo_edit':
                return $this->edit_thrombojqgrid($request);
            
            case 'addThrombo_del':
                return $this->del_thrombojqgrid($request);
            
            default:
                return 'error happen..';
        }
        
        // switch($request->oper){
        //     default:
        //         return 'error happen..';
        // }
    }

    public function get_table_datetimeThrombo(Request $request){
        
        $responce = new stdClass();
        
        $thrombo_obj = DB::table('nursing.thrombophlebitis')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno);
        
        if($thrombo_obj->exists()){
            $thrombo_obj = $thrombo_obj->get();
            
            $data = [];
            
            foreach($thrombo_obj as $key => $value){
                if(!empty($value->dateInsert)){
                    $date['dateInsert'] =  Carbon::createFromFormat('Y-m-d', $value->dateInsert)->format('d-m-Y');
                }else{
                    $date['dateInsert'] =  '-';
                }
                $date['idno'] = $value->idno;
                $date['mrn'] = $value->mrn;
                $date['episno'] = $value->episno;
                // $date['timeInsert'] = $value->timeInsert;
                if(!empty($value->timeInsert)){
                    $date['timeInsert'] =  Carbon::createFromFormat('H:i:s', $value->timeInsert)->format('h:i A');
                }else{
                    $date['timeInsert'] =  '-';
                }
                $date['adduser'] = $value->adduser;
                
                array_push($data,$date);
            }
            
            $responce->data = $data;
        }else{
            $responce->data = [];
        }
        
        return json_encode($responce);
        
    }
    
    public function add_thrombo(Request $request){
        
        DB::beginTransaction();

        try {
            
            DB::table('nursing.thrombophlebitis')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn_nursNote,
                    'episno' => $request->episno_nursNote,
                    'dateInsert' => $request->dateInsert,
                    'timeInsert' => $request->timeInsert,
                    'gauge' => $request->gauge,
                    'attempts' => $request->attempts,
                    'sitesMetacarpal' => $request->sitesMetacarpal,
                    'sitesBasilic' => $request->sitesBasilic,
                    'sitesCephalic' => $request->sitesCephalic,
                    'sitesMCubital' => $request->sitesMCubital,
                    'dateRemoval' => $request->dateRemoval,
                    'timeRemoval' => $request->timeRemoval,
                    'totIndwelling' => $request->totIndwelling,
                    'remarksThrombo' => $request->remarksThrombo,
                    'adduser'  => session('username'),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'computerid' => session('computerid'),
                ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function edit_thrombo(Request $request){
        
        DB::beginTransaction();
        
        try {

            $thrombo = DB::table('nursing.thrombophlebitis')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn_nursNote)
                            ->where('episno','=',$request->episno_nursNote)
                            ->where('dateInsert','=',$request->dateInsert);
            
            if(!empty($request->idno_thrombo)){
                DB::table('nursing.thrombophlebitis')
                    ->where('idno','=',$request->idno_thrombo)
                    ->update([
                        'gauge' => $request->gauge,
                        'attempts' => $request->attempts,
                        'sitesMetacarpal' => $request->sitesMetacarpal,
                        'sitesBasilic' => $request->sitesBasilic,
                        'sitesCephalic' => $request->sitesCephalic,
                        'sitesMCubital' => $request->sitesMCubital,
                        'dateRemoval' => $request->dateRemoval,
                        'timeRemoval' => $request->timeRemoval,
                        'totIndwelling' => $request->totIndwelling,
                        'remarksThrombo' => $request->remarksThrombo,
                        'upduser'  => session('username'),
                        'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastcomputerid' => session('computerid'),

                    ]);
            }else{

                if($thrombo->exists()){
                    return response('Date already exist.');
                }
                
                DB::table('nursing.thrombophlebitis')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_nursNote,
                        'episno' => $request->episno_nursNote,
                        'dateInsert' => $request->dateInsert,
                        'timeInsert' => $request->timeInsert,
                        'gauge' => $request->gauge,
                        'attempts' => $request->attempts,
                        'sitesMetacarpal' => $request->sitesMetacarpal,
                        'sitesBasilic' => $request->sitesBasilic,
                        'sitesCephalic' => $request->sitesCephalic,
                        'sitesMCubital' => $request->sitesMCubital,
                        'dateRemoval' => $request->dateRemoval,
                        'timeRemoval' => $request->timeRemoval,
                        'totIndwelling' => $request->totIndwelling,
                        'remarksThrombo' => $request->remarksThrombo,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
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

    public function add_thrombojqgrid(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.thrombophlebitisadd')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'cannulationNo' => $request->cannulationNo,
                        'flushingDone' => $request->flushingDone,
                        'dateAssessment' => $request->dateAssessment,
                        'shift' => $request->shift,
                        'dressingChanged' => $request->dressingChanged,
                        'staffId' => session('username'),
                        'phlebitisGrade' => $request->phlebitisGrade,
                        'infiltration' => $request->infiltration,
                        'hematoma' => $request->hematoma,
                        'extravasation' => $request->extravasation,
                        'occlusion' => $request->occlusion,
                        'asPerProtocol' => $request->asPerProtocol,
                        'ptDischarged' => $request->ptDischarged,
                        'ivTerminate' => $request->ivTerminate,
                        'fibrinClot' => $request->fibrinClot,
                        'kinkedHub' => $request->kinkedHub,
                        'kinkedShaft' => $request->kinkedShaft,
                        'tipDamage' => $request->tipDamage,
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
    
    public function edit_thrombojqgrid(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.thrombophlebitisadd')
                ->where('idno','=',$request->idno)
                ->where('compcode','=',session('compcode'))
                ->update([
                    'flushingDone' => $request->flushingDone,
                    'dateAssessment' => $request->dateAssessment,
                    'shift' => $request->shift,
                    'dressingChanged' => $request->dressingChanged,
                    'staffId' => session('username'),
                    'phlebitisGrade' => $request->phlebitisGrade,
                    'infiltration' => $request->infiltration,
                    'hematoma' => $request->hematoma,
                    'extravasation' => $request->extravasation,
                    'occlusion' => $request->occlusion,
                    'asPerProtocol' => $request->asPerProtocol,
                    'ptDischarged' => $request->ptDischarged,
                    'ivTerminate' => $request->ivTerminate,
                    'fibrinClot' => $request->fibrinClot,
                    'kinkedHub' => $request->kinkedHub,
                    'kinkedShaft' => $request->kinkedShaft,
                    'tipDamage' => $request->tipDamage,
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
    
    public function del_thrombojqgrid(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.thrombophlebitisadd')
                ->where('idno','=',$request->idno)
                ->where('compcode','=',session('compcode'))
                ->delete();
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
    
    }

    public function get_table_thrombo (Request $request){
        
        $thrombo_obj = DB::table('nursing.thrombophlebitis')
                            ->where('compcode','=',session('compcode'))
                            ->where('idno','=',$request->idno);
                            // ->where('mrn','=',$request->mrn)
                            // ->where('episno','=',$request->episno);
        
        $responce = new stdClass();
        
        if($thrombo_obj->exists()){
            $thrombo_obj = $thrombo_obj->first();
            $date = Carbon::createFromFormat('Y-m-d', $thrombo_obj->dateInsert)->format('Y-m-d');
            
            $responce->thrombo = $thrombo_obj;
            $responce->date = $date;
        }
        
        return json_encode($responce);
        
    }

    public function thrombophlebitis_chart(Request $request){
        
        $mrn = $request->mrn;
        $episno = $request->episno;
        $dateInsert = $request->dateInsert;
        
        // dd($dateInsert);

        if(!$mrn || !$episno){
            abort(404);
        }
        
        $thrombo = DB::table('nursing.thrombophlebitis as p')
                ->select('p.idno','p.mrn','p.episno','p.dateInsert','p.timeInsert','p.gauge','p.attempts','p.sitesMetacarpal','p.sitesBasilic','p.sitesCephalic','p.sitesMCubital','p.dateRemoval','p.timeRemoval','p.totIndwelling','p.remarksThrombo','pm.Name','pm.Newic')
                ->leftjoin('hisdb.pat_mast as pm', function ($join){
                    $join = $join->on('pm.MRN','=','p.mrn');
                    // $join = $join->on('pm.Episno','=','p.episno');
                    $join = $join->where('pm.compcode','=',session('compcode'));
                })
                ->where('p.compcode','=',session('compcode'))
                ->where('p.mrn','=',$mrn)
                ->where('p.episno','=',$episno)
                ->where('p.dateInsert','=',$dateInsert)
                ->first();    
        // dd($thrombo);

        $thromboGrid = DB::table('nursing.thrombophlebitisadd as r')
                ->select('r.idno','r.mrn','r.episno','r.cannulationNo','r.flushingDone','r.dateAssessment','r.shift','r.dressingChanged','r.staffId','r.phlebitisGrade','r.infiltration','r.hematoma','r.extravasation','r.occlusion','r.asPerProtocol','r.ptDischarged','r.ivTerminate','r.fibrinClot','r.kinkedHub','r.kinkedShaft','r.tipDamage','h.idno')
                ->leftjoin('nursing.thrombophlebitis as h', function ($join){
                    $join = $join->on('h.mrn','=','r.mrn');
                    $join = $join->on('h.episno','=','r.episno');
                    $join = $join->where('h.compcode','=',session('compcode'));
                })
                ->where('r.compcode','=',session('compcode'))
                ->where('r.mrn','=',$mrn)
                ->where('r.episno','=',$episno)
                ->where('h.dateInsert','=',$dateInsert)
                ->where('r.cannulationNo','=',$thrombo->idno)
                ->get();

        // dd($thromboGrid);

        return view('hisdb.nursingnote.thrombo_chart_pdfmake', compact('thrombo','thromboGrid'));
        
    }
    
}