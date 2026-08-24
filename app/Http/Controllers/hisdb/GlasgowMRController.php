<?php

namespace App\Http\Controllers\hisdb;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class GlasgowMRController extends defaultController
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
            case 'get_table_datetimeGCS': // Glasgow
                return $this->get_table_datetimeGCS($request);

            default:
                return 'error happen..';
        }
    }
    
    public function form(Request $request){
        DB::enableQueryLog();
        switch($request->action){
            case 'get_table_glasgow':
                return $this->get_table_glasgow($request);
                
            default:
                return 'error happen..';
        }

    }

    public function get_table_datetimeGCS(Request $request){
        
        $responce = new stdClass();
        
        $glasgow_obj = DB::table('nursing.glasgowcomascale')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno);
        
        if($glasgow_obj->exists()){
            $glasgow_obj = $glasgow_obj->get();
            
            $data = [];
            
            foreach($glasgow_obj as $key => $value){
                if(!empty($value->gcs_date)){
                    $date['gcs_date'] =  Carbon::createFromFormat('Y-m-d', $value->gcs_date)->format('d-m-Y');
                }else{
                    $date['gcs_date'] =  '-';
                }
                $date['idno'] = $value->idno;
                $date['mrn'] = $value->mrn;
                $date['episno'] = $value->episno;
                // $date['gcs_time'] = $value->gcs_time;
                if(!empty($value->gcs_time)){
                    $date['gcs_time'] =  Carbon::createFromFormat('H:i:s', $value->gcs_time)->format('h:i A');
                }else{
                    $date['gcs_time'] =  '-';
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

    public function get_table_glasgow (Request $request){
        
        $glasgow_obj = DB::table('nursing.glasgowcomascale')
                            ->where('compcode','=',session('compcode'))
                            ->where('idno','=',$request->idno);
                            // ->where('mrn','=',$request->mrn)
                            // ->where('episno','=',$request->episno);
        
        $responce = new stdClass();
        
        if($glasgow_obj->exists()){
            $glasgow_obj = $glasgow_obj->first();
            $date = Carbon::createFromFormat('Y-m-d', $glasgow_obj->gcs_date)->format('Y-m-d');
            
            $responce->glasgow = $glasgow_obj;
            $responce->date = $date;
        }
        
        return json_encode($responce);
        
    }

    public function glasgow_chart(Request $request){
        
        $mrn = $request->mrn;
        $episno = $request->episno;
        if(!$mrn || !$episno){
            abort(404);
        }
        
        $pat_mast = DB::table('hisdb.pat_mast as pm')
                    ->select('pm.MRN','pm.Name','b.ward','b.bednum')
                    ->leftJoin('hisdb.bedalloc as b', function ($join){
                        $join = $join->on('b.mrn','=','pm.MRN')
                                    ->on('b.episno','=','pm.Episno')
                                    ->where('b.compcode','=',session('compcode'));
                    })
                    ->leftJoin('nursing.nursassessment as n', function ($join){
                        $join = $join->on('n.mrn','=','pm.MRN')
                                    ->on('n.episno','=','pm.Episno')
                                    ->where('n.compcode','=',session('compcode'));
                    })
                    ->where('pm.CompCode','=',session('compcode'))
                    ->where('pm.MRN','=',$mrn)
                    ->where('pm.Episno','=',$episno)
                    ->first();
        
        $bladder = DB::table('nursing.nurs_bladder')
                    ->select('mrn','episno','shift','entereddate','enteredtime','input','output','positive','negative','remarks','adduser','adddate','computerid')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$mrn)
                    ->where('episno','=',$episno)
                    ->get();
        
        return view('hisdb.nursingnote_MR.bladder_chart_pdfmake', compact('pat_mast','bladder'));
        
    }

}