<?php

namespace App\Http\Controllers\hisdb;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class MorseFallScaleMRController extends defaultController
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
            case 'get_datetime_morsefallscale':
                return $this->get_datetime_morsefallscale($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function form(Request $request){
        DB::enableQueryLog();
        switch($request->action){
            case 'get_table_morsefallscale':
                return $this->get_table_morsefallscale($request);
            
            default:
                return 'error happen..';
        }
        
        // switch($request->oper){
        //     default:
        //         return 'error happen..';
        // }
    }
    
    public function get_datetime_morsefallscale(Request $request){
        
        $responce = new stdClass();
        
        $morsefallscale_obj = DB::table('nursing.morsefallscale')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno);
        
        if($morsefallscale_obj->exists()){
            $morsefallscale_obj = $morsefallscale_obj->get();
            
            $data = [];
            
            foreach($morsefallscale_obj as $key => $value){
                $date['idno'] = $value->idno;
                $date['mrn'] = $value->mrn;
                $date['episno'] = $value->episno;
                if(!empty($value->datetaken)){
                    $date['datetaken'] =  Carbon::createFromFormat('Y-m-d', $value->datetaken)->format('d-m-Y');
                }else{
                    $date['datetaken'] =  '-';
                }
                // $date['timetaken'] = $value->timetaken;
                if(!empty($value->timetaken)){
                    $date['timetaken'] =  Carbon::createFromFormat('H:i:s', $value->timetaken)->format('h:i A');
                }else{
                    $date['timetaken'] =  '-';
                }
                $date['adduser'] = $value->adduser;
                if(!empty($value->datetaken)){ // for sorting - easier in 24H
                    $date['dt'] =  Carbon::createFromFormat('Y-m-d', $value->datetaken)->format('d-m-Y').'<br>'.$value->timetaken;
                }else{
                    $date['dt'] =  '-';
                }
                
                array_push($data,$date);
            }
            
            $responce->data = $data;
        }else{
            $responce->data = [];
        }
        
        return json_encode($responce);
        
    }
    
    public function get_table_morsefallscale(Request $request){
        
        // $nursassessment_obj = DB::table('nursing.nursassessment')
        //                     ->select('diagnosis')
        //                     ->where('compcode','=',session('compcode'))
        //                     ->where('mrn','=',$request->mrn)
        //                     ->where('episno','=',$request->episno);
        
        // $pat_otbook_obj = DB::table('hisdb.pat_otbook')
        //                 ->select('diagnosis')
        //                 ->where('compcode','=',session('compcode'))
        //                 ->where('mrn','=',$request->mrn)
        //                 ->where('episno','=',$request->episno);
        
        $nursactplan_obj = DB::table('nursing.nursactplan_hdr')
                            ->select('diagnosis')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno);
        
        $episode_obj = DB::table('hisdb.episode')
                        ->select('reg_date')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno);
        
        $morsefallscale_obj = DB::table('nursing.morsefallscale')
                            ->where('compcode','=',session('compcode'))
                            ->where('idno','=',$request->idno);
                            // ->where('mrn','=',$request->mrn)
                            // ->where('episno','=',$request->episno);
        
        $responce = new stdClass();
        
        // if($nursassessment_obj->exists()){
        //     $nursassessment_obj = $nursassessment_obj->first();
            
        //     $diagnosis_obj = $nursassessment_obj->diagnosis;
        //     $responce->diagnosis = $diagnosis_obj;
        // }
        
        // if($pat_otbook_obj->exists()){
        //     $pat_otbook_obj = $pat_otbook_obj->first();
            
        //     $diagnosis_obj = $pat_otbook_obj->diagnosis;
        //     $responce->diagnosis = $diagnosis_obj;
        // }
        
        if($nursactplan_obj->exists()){
            $nursactplan_obj = $nursactplan_obj->first();
            
            $diagnosis_obj = $nursactplan_obj->diagnosis;
            $responce->diagnosis = $diagnosis_obj;
        }
        
        if($episode_obj->exists()){
            $episode_obj = $episode_obj->first();
            
            $regdate_obj = $episode_obj->reg_date;
            $responce->reg_date = $regdate_obj;
        }
        
        if($morsefallscale_obj->exists()){
            $morsefallscale_obj = $morsefallscale_obj->first();
            $date = Carbon::createFromFormat('Y-m-d', $morsefallscale_obj->datetaken)->format('Y-m-d');
            
            $responce->morsefallscale = $morsefallscale_obj;
            $responce->date = $date;
        }
        
        return json_encode($responce);
        
    }
    
    public function morsefallscale_chart(Request $request){
        
        $datefr = Carbon::parse($request->datefr)->format('Y-m-d');
        $dateto = Carbon::parse($request->dateto)->format('Y-m-d');
        $mrn = $request->mrn;
        $episno = $request->episno;
        $age = $request->age;
        if(!$mrn || !$episno){
            abort(404);
        }
        
        $pat_mast = DB::table('nursing.morsefallscale as m')
                    ->select('m.idno','m.compcode','m.mrn','m.episno','m.datetaken','m.timetaken','m.fallHistory','m.secondaryDiag','m.ambulatoryAids','m.IVtherapy','m.gait','m.mentalStatus','m.totalScore','m.staffname','m.adduser','m.adddate','m.upduser','m.upddate','m.lastuser','m.lastupdate','m.computerid','pm.Name','pm.Address1','pm.Address2','pm.Address3','pm.Postcode','pm.Newic','pm.Sex','pm.RaceCode','e.bed as bednum','b.ward')
                    ->leftjoin('hisdb.pat_mast as pm', function ($join){
                        $join = $join->on('pm.MRN','=','m.mrn');
                        // $join = $join->on('pm.Episno','=','m.episno');
                        $join = $join->where('pm.CompCode','=',session('compcode'));
                    })
                    ->leftJoin('hisdb.episode as e', function ($join){
                        $join = $join->on('e.mrn','=','pm.MRN');
                        $join = $join->on('e.episno','=','pm.Episno');
                        $join = $join->where('e.compcode','=',session('compcode'));
                    })
                    ->leftJoin('hisdb.bed as b', function ($join){
                        $join = $join->on('b.bednum','=','e.bed');
                        $join = $join->where('b.compcode','=',session('compcode'));
                    })
                    ->where('m.compcode','=',session('compcode'))
                    ->where('m.mrn','=',$mrn)
                    ->where('m.episno','=',$episno)
                    ->first();
        
        $episode = DB::table('hisdb.episode')
                    ->select('reg_date')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$mrn)
                    ->where('episno','=',$episno)
                    ->first();
        
        // $nursassessment = DB::table('nursing.nursassessment')
        //                 ->select('diagnosis')
        //                 ->where('compcode','=',session('compcode'))
        //                 ->where('mrn','=',$request->mrn)
        //                 ->where('episno','=',$request->episno)
        //                 ->first();
        
        // $pat_otbook = DB::table('hisdb.pat_otbook')
        //             ->select('diagnosis')
        //             ->where('compcode','=',session('compcode'))
        //             ->where('mrn','=',$mrn)
        //             ->where('episno','=',$episno)
        //             ->first();
        
        $nursactplan = DB::table('nursing.nursactplan_hdr')
                        ->select('diagnosis')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno)
                        ->first();
        
        $datetime = DB::table('nursing.morsefallscale')
                    // ->select(DB::raw('DATE_FORMAT(datetaken, "%d/%m/%Y") as date'),DB::raw('TIME(timetaken) as time'))
                    ->select('datetaken','timetaken',DB::raw('DATE_FORMAT(datetaken, "%d/%m/%Y") as date'),DB::raw('TIME(timetaken) as time'))
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$mrn)
                    ->where('episno','=',$episno)
                    ->whereBetween('datetaken',[$datefr,$dateto])
                    ->groupBy('datetaken','timetaken')
                    ->orderBy('datetaken','asc')
                    ->orderBy('timetaken','asc')
                    ->get();
        
        $morsefallscale = DB::table('nursing.morsefallscale as m')
                        ->select('m.idno','m.compcode','m.mrn','m.episno','m.datetaken','m.timetaken',DB::raw('DATE_FORMAT(m.datetaken, "%d/%m/%Y") as date'),DB::raw('TIME(m.timetaken) as time'),'m.fallHistory','m.secondaryDiag','m.ambulatoryAids','m.IVtherapy','m.gait','m.mentalStatus','m.totalScore','m.staffname','m.adduser','m.adddate','m.upduser','m.upddate','m.lastuser','m.lastupdate','m.computerid')
                        ->where('m.compcode','=',session('compcode'))
                        ->where('m.mrn','=',$mrn)
                        ->where('m.episno','=',$episno)
                        ->whereBetween('m.datetaken',[$datefr,$dateto])
                        // ->groupBy('m.datetaken','m.timetaken')
                        ->orderBy('m.datetaken','asc')
                        ->orderBy('m.timetaken','asc')
                        ->get();
        // dd($morsefallscale);
        
        return view('hisdb.nursingnote_MR.morsefallscale_pdfmake', compact('age','pat_mast','episode','nursactplan','datetime','morsefallscale'));
        
    }

}