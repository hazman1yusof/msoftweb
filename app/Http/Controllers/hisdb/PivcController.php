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
        
        return view('hisdb.nursingnote.bladder_chart_pdfmake', compact('pat_mast','bladder'));
        
    }
    
    
}