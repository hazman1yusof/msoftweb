<?php

namespace App\Http\Controllers\hisdb;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class ThrombophlebitisMRController extends defaultController
{
    
    var $table;
    var $duplicateCode;
    
    public function __construct(){
        $this->middleware('auth');
        // $this->duplicateCode = "chgtype";
    }
    
    public function show(Request $request){
        return view('hisdb.nursingnote_MR.nursingnote_MR');
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
           
            case 'get_table_thrombo':
                return $this->get_table_thrombo($request);
            
            default:
                return 'error happen..';
        }
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

        return view('hisdb.nursingnote_MR.thrombo_chart_pdfmake', compact('thrombo','thromboGrid'));
        
    }

}