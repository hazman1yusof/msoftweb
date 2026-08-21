<?php

namespace App\Http\Controllers\hisdb;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class PivcMRController extends defaultController
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
            case 'get_table_datetimePIVC': // PIVC
                return $this->get_table_datetimePIVC($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function form(Request $request){
        DB::enableQueryLog();
        switch($request->action){ 
            case 'get_table_pivc':
                return $this->get_table_pivc($request);
                
            default:
                return 'error happen..';
        }

    }

    public function get_table_datetimePIVC(Request $request){
        
        $responce = new stdClass();
        
        $pivc_obj = DB::table('nursing.pivc')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno);
        
        if($pivc_obj->exists()){
            $pivc_obj = $pivc_obj->get();
            
            $data = [];
            
            foreach($pivc_obj as $key => $value){
                if(!empty($value->practiceDate)){
                    $date['practiceDate'] =  Carbon::createFromFormat('Y-m-d', $value->practiceDate)->format('d-m-Y');
                }else{
                    $date['practiceDate'] =  '-';
                }
                $date['idno'] = $value->idno;
                $date['mrn'] = $value->mrn;
                $date['episno'] = $value->episno;
                $date['adduser'] = $value->adduser;

                array_push($data,$date);
            }
            
            $responce->data = $data;
        }else{
            $responce->data = [];
        }
        
        return json_encode($responce);
        
    }
    
    public function get_table_pivc(Request $request){
        
        $pivc_obj = DB::table('nursing.pivc')
                            ->where('compcode','=',session('compcode'))
                            ->where('idno','=',$request->idno);
                            // ->where('mrn','=',$request->mrn)
                            // ->where('episno','=',$request->episno);
        
        $responce = new stdClass();
        
        if($pivc_obj->exists()){
            $pivc_obj = $pivc_obj->first();
            $responce->pivc = $pivc_obj;
        }
        
        return json_encode($responce);
        
    }

    public function pivc_chart(Request $request){
        
        $mrn = $request->mrn;
        $episno = $request->episno;
        $practiceDate = $request->practiceDate;
        $datefr = Carbon::parse($request->datefr)->format('Y-m-d');
        $dateto = Carbon::parse($request->dateto)->format('Y-m-d');        
        if(!$mrn || !$episno){
            abort(404);
        }

        $pivc = DB::table('nursing.pivc as p')
                ->select('p.idno','p.mrn','p.episno','p.practiceDate','p.consultant','p.hygiene_M','p.hygiene_E','p.hygiene_N','p.dressing_M','p.dressing_E','p.dressing_N','p.alcoholSwab_M','p.alcoholSwab_E','p.alcoholSwab_N','p.siteLabelled_M','p.siteLabelled_E','p.siteLabelled_N','p.correct_M','p.correct_E','p.correct_N','p.multiDoseVial_M','p.multiDoseVial_E','p.multiDoseVial_N','p.cleanVial_M','p.cleanVial_E','p.cleanVial_N','p.splitSeptum_M','p.splitSeptum_E','p.splitSeptum_N','p.cleanSite_M','p.cleanSite_E','p.cleanSite_N','p.chgSplitSeptum_M','p.chgSplitSeptum_E','p.chgSplitSeptum_N','p.flushingACL_M','p.flushingACL_E','p.flushingACL_N','p.clamping_M','p.clamping_E','p.clamping_N','p.set_M','p.set_E','p.set_N','p.removalPIVC_M','p.removalPIVC_E','p.removalPIVC_N','p.name_M','p.name_E','p.name_N','p.datetime_M','p.datetime_E','p.datetime_N','pm.Name','pm.Newic')
                ->leftjoin('hisdb.pat_mast as pm', function ($join){
                    $join = $join->on('pm.MRN','=','p.mrn');
                    // $join = $join->on('pm.Episno','=','p.episno');
                    $join = $join->where('pm.compcode','=',session('compcode'));
                })
                ->where('p.compcode','=',session('compcode'))
                ->where('p.mrn','=',$mrn)
                ->where('p.episno','=',$episno)
                ->whereBetween('p.practiceDate',[$datefr,$dateto])                
                ->first();    
        // dd($pivc);

        $pivc_date = DB::table('nursing.pivc as d')
                ->select('d.idno','d.mrn','d.episno','d.practiceDate','D.consultant','d.hygiene_M','d.hygiene_E','d.hygiene_N','d.dressing_M','d.dressing_E','d.dressing_N','d.alcoholSwab_M','d.alcoholSwab_E','d.alcoholSwab_N','d.siteLabelled_M','d.siteLabelled_E','d.siteLabelled_N','d.correct_M','d.correct_E','d.correct_N','d.multiDoseVial_M','d.multiDoseVial_E','d.multiDoseVial_N','d.cleanVial_M','d.cleanVial_E','d.cleanVial_N','d.splitSeptum_M','d.splitSeptum_E','d.splitSeptum_N','d.cleanSite_M','d.cleanSite_E','d.cleanSite_N','d.chgSplitSeptum_M','d.chgSplitSeptum_E','d.chgSplitSeptum_N','d.flushingACL_M','d.flushingACL_E','d.flushingACL_N','d.clamping_M','d.clamping_E','d.clamping_N','d.set_M','d.set_E','d.set_N','d.removalPIVC_M','d.removalPIVC_E','d.removalPIVC_N','d.name_M','d.name_E','d.name_N','d.datetime_M','d.datetime_E','d.datetime_N',DB::raw('DATE_FORMAT(d.practiceDate, "%d/%m/%Y") as date'))
                ->where('d.compcode','=',session('compcode'))
                ->where('d.mrn','=',$mrn)
                ->where('d.episno','=',$episno)
                ->whereBetween('d.practiceDate',[$datefr,$dateto])                
                ->get();
        // dd($pivc_date);

        $array_report = [];

        foreach ($pivc_date as $key => $value){
            $pivc = DB::table('nursing.pivc as p')
                ->select('p.idno','p.mrn','p.episno','p.practiceDate','p.consultant','p.hygiene_M','p.hygiene_E','p.hygiene_N','p.dressing_M','p.dressing_E','p.dressing_N','p.alcoholSwab_M','p.alcoholSwab_E','p.alcoholSwab_N','p.siteLabelled_M','p.siteLabelled_E','p.siteLabelled_N','p.correct_M','p.correct_E','p.correct_N','p.multiDoseVial_M','p.multiDoseVial_E','p.multiDoseVial_N','p.cleanVial_M','p.cleanVial_E','p.cleanVial_N','p.splitSeptum_M','p.splitSeptum_E','p.splitSeptum_N','p.cleanSite_M','p.cleanSite_E','p.cleanSite_N','p.chgSplitSeptum_M','p.chgSplitSeptum_E','p.chgSplitSeptum_N','p.flushingACL_M','p.flushingACL_E','p.flushingACL_N','p.clamping_M','p.clamping_E','p.clamping_N','p.set_M','p.set_E','p.set_N','p.removalPIVC_M','p.removalPIVC_E','p.removalPIVC_N','p.name_M','p.name_E','p.name_N','p.datetime_M','p.datetime_E','p.datetime_N','pm.Name','pm.Newic')
                ->leftjoin('hisdb.pat_mast as pm', function ($join){
                    $join = $join->on('pm.MRN','=','p.mrn');
                    // $join = $join->on('pm.Episno','=','p.episno');
                    $join = $join->where('pm.compcode','=',session('compcode'));
                })
                ->where('p.compcode','=',session('compcode'))
                ->where('p.mrn','=',$mrn)
                ->where('p.episno','=',$episno)
                ->whereBetween('p.practiceDate',[$datefr,$dateto])                
                ->first();    
            array_push($array_report,$value);

        }
        // dd($array_report);

        return view('hisdb.nursingnote_MR.pivc_chart_pdfmake', compact('pivc','pivc_date','array_report'));
        
    }

}