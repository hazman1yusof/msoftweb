<?php

namespace App\Http\Controllers\hisdb;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class PivcController extends defaultController
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
            case 'get_table_datetimePIVC': // PIVC
                return $this->get_table_datetimePIVC($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function form(Request $request){
        DB::enableQueryLog();
        switch($request->action){
            case 'save_table_pivc':
                switch($request->oper){
                    case 'add':
                        return $this->add_pivc($request);
                    case 'edit':
                        return $this->edit_pivc($request);
                    default:
                        return 'error happen..';
                }
            
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
    
    
    public function add_pivc(Request $request){
        
        DB::beginTransaction();

        try {
            
            DB::table('nursing.pivc')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn_nursNote,
                    'episno' => $request->episno_nursNote,
                    'practiceDate' => $request->practiceDate,
                    'consultant' => $request->consultant,
                    'hygiene_M' => $request->hygiene_M,
                    'hygiene_E' => $request->hygiene_E,
                    'hygiene_N' => $request->hygiene_N,
                    'dressing_M' => $request->dressing_M,
                    'dressing_E' => $request->dressing_E,
                    'dressing_N' => $request->dressing_N,
                    'alcoholSwab_M' => $request->alcoholSwab_M,
                    'alcoholSwab_E' => $request->alcoholSwab_E,
                    'alcoholSwab_N' => $request->alcoholSwab_N,
                    'siteLabelled_M' => $request->siteLabelled_M,
                    'siteLabelled_E' => $request->siteLabelled_E,
                    'siteLabelled_N' => $request->siteLabelled_N,
                    'correct_M' => $request->correct_M,
                    'correct_E' => $request->correct_E,
                    'correct_N' => $request->correct_N,
                    'multiDoseVial_M' => $request->multiDoseVial_M,
                    'multiDoseVial_E' => $request->multiDoseVial_E,
                    'multiDoseVial_N' => $request->multiDoseVial_N,
                    'cleanVial_M' => $request->cleanVial_M,
                    'cleanVial_E' => $request->cleanVial_E,
                    'cleanVial_N' => $request->cleanVial_N,
                    'splitSeptum_M' => $request->splitSeptum_M,
                    'splitSeptum_E' => $request->splitSeptum_E,
                    'splitSeptum_N' => $request->splitSeptum_N,
                    'cleanSite_M' => $request->cleanSite_M,
                    'cleanSite_E' => $request->cleanSite_E,
                    'cleanSite_N' => $request->cleanSite_N,
                    'chgSplitSeptum_M' => $request->chgSplitSeptum_M,
                    'chgSplitSeptum_E' => $request->chgSplitSeptum_E,
                    'chgSplitSeptum_N' => $request->chgSplitSeptum_N,
                    'flushingACL_M' => $request->flushingACL_M,
                    'flushingACL_E' => $request->flushingACL_E,
                    'flushingACL_N' => $request->flushingACL_N,
                    'clamping_M' => $request->clamping_M,
                    'clamping_E' => $request->clamping_E,
                    'clamping_N' => $request->clamping_N,
                    'set_M' => $request->set_M,
                    'set_E' => $request->set_E,
                    'set_N' => $request->set_N,
                    'removalPIVC_M' => $request->removalPIVC_M,
                    'removalPIVC_E' => $request->removalPIVC_E,
                    'removalPIVC_N' => $request->removalPIVC_N,
                    'name_M' => $request->name_M,
                    'name_E' => $request->name_E,
                    'name_N' => $request->name_N,
                    'datetime_M' => $request->datetime_M,
                    'datetime_E' => $request->datetime_E,
                    'datetime_N' => $request->datetime_N,
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

    
    public function edit_pivc(Request $request){
        
        DB::beginTransaction();
        
        try {

            $pivc = DB::table('nursing.pivc')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn_nursNote)
                            ->where('episno','=',$request->episno_nursNote)
                            ->where('practiceDate','=',$request->practiceDate);
            
            if(!empty($request->idno_pivc)){
                DB::table('nursing.pivc')
                    ->where('idno','=',$request->idno_pivc)
                    ->update([
                        'consultant' => $request->consultant,
                        'hygiene_M' => $request->hygiene_M,
                        'hygiene_E' => $request->hygiene_E,
                        'hygiene_N' => $request->hygiene_N,
                        'dressing_M' => $request->dressing_M,
                        'dressing_E' => $request->dressing_E,
                        'dressing_N' => $request->dressing_N,
                        'alcoholSwab_M' => $request->alcoholSwab_M,
                        'alcoholSwab_E' => $request->alcoholSwab_E,
                        'alcoholSwab_N' => $request->alcoholSwab_N,
                        'siteLabelled_M' => $request->siteLabelled_M,
                        'siteLabelled_E' => $request->siteLabelled_E,
                        'siteLabelled_N' => $request->siteLabelled_N,
                        'correct_M' => $request->correct_M,
                        'correct_E' => $request->correct_E,
                        'correct_N' => $request->correct_N,
                        'multiDoseVial_M' => $request->multiDoseVial_M,
                        'multiDoseVial_E' => $request->multiDoseVial_E,
                        'multiDoseVial_N' => $request->multiDoseVial_N,
                        'cleanVial_M' => $request->cleanVial_M,
                        'cleanVial_E' => $request->cleanVial_E,
                        'cleanVial_N' => $request->cleanVial_N,
                        'splitSeptum_M' => $request->splitSeptum_M,
                        'splitSeptum_E' => $request->splitSeptum_E,
                        'splitSeptum_N' => $request->splitSeptum_N,
                        'cleanSite_M' => $request->cleanSite_M,
                        'cleanSite_E' => $request->cleanSite_E,
                        'cleanSite_N' => $request->cleanSite_N,
                        'chgSplitSeptum_M' => $request->chgSplitSeptum_M,
                        'chgSplitSeptum_E' => $request->chgSplitSeptum_E,
                        'chgSplitSeptum_N' => $request->chgSplitSeptum_N,
                        'flushingACL_M' => $request->flushingACL_M,
                        'flushingACL_E' => $request->flushingACL_E,
                        'flushingACL_N' => $request->flushingACL_N,
                        'clamping_M' => $request->clamping_M,
                        'clamping_E' => $request->clamping_E,
                        'clamping_N' => $request->clamping_N,
                        'set_M' => $request->set_M,
                        'set_E' => $request->set_E,
                        'set_N' => $request->set_N,
                        'removalPIVC_M' => $request->removalPIVC_M,
                        'removalPIVC_E' => $request->removalPIVC_E,
                        'removalPIVC_N' => $request->removalPIVC_N,
                        'name_M' => $request->name_M,
                        'name_E' => $request->name_E,
                        'name_N' => $request->name_N,
                        'datetime_M' => $request->datetime_M,
                        'datetime_E' => $request->datetime_E,
                        'datetime_N' => $request->datetime_N,
                        'upduser'  => session('username'),
                        'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }else{

                if($pivc->exists()){
                    return response('Date already exist.');
                }
                
                DB::table('nursing.pivc')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_nursNote,
                        'episno' => $request->episno_nursNote,
                        'practiceDate' => $request->practiceDate,
                        'consultant' => $request->consultant,
                        'hygiene_M' => $request->hygiene_M,
                        'hygiene_E' => $request->hygiene_E,
                        'hygiene_N' => $request->hygiene_N,
                        'dressing_M' => $request->dressing_M,
                        'dressing_E' => $request->dressing_E,
                        'dressing_N' => $request->dressing_N,
                        'alcoholSwab_M' => $request->alcoholSwab_M,
                        'alcoholSwab_E' => $request->alcoholSwab_E,
                        'alcoholSwab_N' => $request->alcoholSwab_N,
                        'siteLabelled_M' => $request->siteLabelled_M,
                        'siteLabelled_E' => $request->siteLabelled_E,
                        'siteLabelled_N' => $request->siteLabelled_N,
                        'correct_M' => $request->correct_M,
                        'correct_E' => $request->correct_E,
                        'correct_N' => $request->correct_N,
                        'multiDoseVial_M' => $request->multiDoseVial_M,
                        'multiDoseVial_E' => $request->multiDoseVial_E,
                        'multiDoseVial_N' => $request->multiDoseVial_N,
                        'cleanVial_M' => $request->cleanVial_M,
                        'cleanVial_E' => $request->cleanVial_E,
                        'cleanVial_N' => $request->cleanVial_N,
                        'splitSeptum_M' => $request->splitSeptum_M,
                        'splitSeptum_E' => $request->splitSeptum_E,
                        'splitSeptum_N' => $request->splitSeptum_N,
                        'cleanSite_M' => $request->cleanSite_M,
                        'cleanSite_E' => $request->cleanSite_E,
                        'cleanSite_N' => $request->cleanSite_N,
                        'chgSplitSeptum_M' => $request->chgSplitSeptum_M,
                        'chgSplitSeptum_E' => $request->chgSplitSeptum_E,
                        'chgSplitSeptum_N' => $request->chgSplitSeptum_N,
                        'flushingACL_M' => $request->flushingACL_M,
                        'flushingACL_E' => $request->flushingACL_E,
                        'flushingACL_N' => $request->flushingACL_N,
                        'clamping_M' => $request->clamping_M,
                        'clamping_E' => $request->clamping_E,
                        'clamping_N' => $request->clamping_N,
                        'set_M' => $request->set_M,
                        'set_E' => $request->set_E,
                        'set_N' => $request->set_N,
                        'removalPIVC_M' => $request->removalPIVC_M,
                        'removalPIVC_E' => $request->removalPIVC_E,
                        'removalPIVC_N' => $request->removalPIVC_N,
                        'name_M' => $request->name_M,
                        'name_E' => $request->name_E,
                        'name_N' => $request->name_N,
                        'datetime_M' => $request->datetime_M,
                        'datetime_E' => $request->datetime_E,
                        'datetime_N' => $request->datetime_N,
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

        return view('hisdb.nursingnote.pivc_chart_pdfmake', compact('pivc','pivc_date','array_report'));
        
    }
    
}