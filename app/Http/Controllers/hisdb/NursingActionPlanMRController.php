<?php

namespace App\Http\Controllers\hisdb;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class NursingActionPlanMRController extends defaultController
{
    
    var $table;
    var $duplicateCode;
    
    public function __construct(){
        $this->middleware('auth');
    }
    
    public function show(Request $request){
        
        return view('hisdb.nursingActionPlan_MR.nursingActionPlan_MR');
        
    }
    
    public function table(Request $request)
    {
        switch($request->action){
            
            default:
                return 'error happen..';
        }
    }
    
    public function form(Request $request){
        DB::enableQueryLog();
        switch($request->action){
            case 'get_table_formHeader':
                return $this->get_table_formHeader($request);

            default:
                return 'error happen..';
        }
    }

    public function get_table_formHeader(Request $request){
        
        $episode_obj = DB::table('hisdb.episode')
                        ->select('reg_date')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$request->mrn_nursActionPlan)
                        ->where('episno','=',$request->episno_nursActionPlan);

        $header_obj = DB::table('nursing.nursactplan_hdr')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn_nursActionPlan)
                            ->where('episno','=',$request->episno_nursActionPlan);
        
        $responce = new stdClass();
        
        if($episode_obj->exists()){
            $episode_obj = $episode_obj->first();
            // dd($episode_obj);
            $responce->episode = $episode_obj;
        }

        if($header_obj->exists()){
            $header_obj = $header_obj->first();
            $responce->header = $header_obj;
        }
        
        return json_encode($responce);
        
    }
    
    public function treatment_chart(Request $request){
        
        $mrn = $request->mrn;
        $episno = $request->episno;
        if(!$mrn || !$episno){
            abort(404);
        }
        $age = $request->age;
        
        $pat_mast = DB::table('hisdb.pat_mast as pm')
                    ->select('pm.MRN','pm.Name','pm.Newic','b.ward','b.bednum','e.reg_date','h.diagnosis','h.operation')
                    ->leftJoin('hisdb.bedalloc as b', function ($join){
                        $join = $join->on('b.mrn','=','pm.MRN')
                                    ->on('b.episno','=','pm.Episno')
                                    ->where('b.compcode','=',session('compcode'));
                    })
                    ->leftJoin('hisdb.episode as e', function ($join){
                        $join = $join->on('e.mrn','=','pm.MRN')
                                    ->on('e.episno','=','pm.Episno')
                                    ->where('e.compcode','=',session('compcode'));
                    })
                    ->leftJoin('nursing.nursactplan_hdr as h', function ($join){
                        $join = $join->on('h.mrn','=','pm.MRN')
                                    ->on('h.episno','=','pm.Episno')
                                    ->where('h.compcode','=',session('compcode'));
                    })
                    ->where('pm.CompCode','=',session('compcode'))
                    ->where('pm.MRN','=',$mrn)
                    ->where('pm.Episno','=',$episno)
                    ->first();

        $treatment = DB::table('nursing.nursactplan_treatment as t')
                        ->select('t.compcode','t.mrn','t.episno','t.startdate','t.enddate','t.treatment','t.adduser','t.adddate','t.upduser','t.upddate','t.lastuser','t.lastupdate','t.computerid')
                        ->where('t.compcode','=',session('compcode'))
                        ->where('t.mrn','=',$mrn)
                        ->where('t.episno','=',$episno)
                        ->get();
        
        return view('hisdb.nursingActionPlan_MR.treatment_chart_pdfmake', compact('age','pat_mast','treatment'));
        
    }

    public function observation_chart(Request $request){
        
        $mrn = $request->mrn;
        $episno = $request->episno;
        if(!$mrn || !$episno){
            abort(404);
        }
        $age = $request->age;
        
        $pat_mast = DB::table('hisdb.pat_mast as pm')
                    ->select('pm.MRN','pm.Name','pm.Newic','b.ward','b.bednum','e.reg_date','h.diagnosis','h.operation')
                    ->leftJoin('hisdb.bedalloc as b', function ($join){
                        $join = $join->on('b.mrn','=','pm.MRN')
                                    ->on('b.episno','=','pm.Episno')
                                    ->where('b.compcode','=',session('compcode'));
                    })
                    ->leftJoin('hisdb.episode as e', function ($join){
                        $join = $join->on('e.mrn','=','pm.MRN')
                                    ->on('e.episno','=','pm.Episno')
                                    ->where('e.compcode','=',session('compcode'));
                    })
                    ->leftJoin('nursing.nursactplan_hdr as h', function ($join){
                        $join = $join->on('h.mrn','=','pm.MRN')
                                    ->on('h.episno','=','pm.Episno')
                                    ->where('h.compcode','=',session('compcode'));
                    })
                    ->where('pm.CompCode','=',session('compcode'))
                    ->where('pm.MRN','=',$mrn)
                    ->where('pm.Episno','=',$episno)
                    ->first();

        $observation = DB::table('nursing.nursactplan_observation as o')
                        ->select('o.compcode','o.mrn','o.episno','o.startdate','o.enddate','o.observation','o.adduser','o.adddate','o.upduser','o.upddate','o.lastuser','o.lastupdate','o.computerid')
                        ->where('o.compcode','=',session('compcode'))
                        ->where('o.mrn','=',$mrn)
                        ->where('o.episno','=',$episno)
                        ->get();
        
        return view('hisdb.nursingActionPlan_MR.observation_chart_pdfmake', compact('age','pat_mast','observation'));
        
    }

    public function feeding_chart(Request $request){
        
        $mrn = $request->mrn;
        $episno = $request->episno;
        if(!$mrn || !$episno){
            abort(404);
        }
        $age = $request->age;
        
        $pat_mast = DB::table('hisdb.pat_mast as pm')
                    ->select('pm.MRN','pm.Name','pm.Newic','b.ward','b.bednum','e.reg_date','h.diagnosis','h.operation')
                    ->leftJoin('hisdb.bedalloc as b', function ($join){
                        $join = $join->on('b.mrn','=','pm.MRN')
                                    ->on('b.episno','=','pm.Episno')
                                    ->where('b.compcode','=',session('compcode'));
                    })
                    ->leftJoin('hisdb.episode as e', function ($join){
                        $join = $join->on('e.mrn','=','pm.MRN')
                                    ->on('e.episno','=','pm.Episno')
                                    ->where('e.compcode','=',session('compcode'));
                    })
                    ->leftJoin('nursing.nursactplan_hdr as h', function ($join){
                        $join = $join->on('h.mrn','=','pm.MRN')
                                    ->on('h.episno','=','pm.Episno')
                                    ->where('h.compcode','=',session('compcode'));
                    })
                    ->where('pm.CompCode','=',session('compcode'))
                    ->where('pm.MRN','=',$mrn)
                    ->where('pm.Episno','=',$episno)
                    ->first();

        $feeding = DB::table('nursing.nursactplan_feeding as f')
                        ->select('f.compcode','f.mrn','f.episno','f.startdate','f.enddate','f.feeding','f.adduser','f.adddate','f.upduser','f.upddate','f.lastuser','f.lastupdate','f.computerid')
                        ->where('f.compcode','=',session('compcode'))
                        ->where('f.mrn','=',$mrn)
                        ->where('f.episno','=',$episno)
                        ->get();
        
        return view('hisdb.nursingActionPlan_MR.feeding_chart_pdfmake', compact('age','pat_mast','feeding'));
        
    }

    public function imgDiag_chart(Request $request){
        
        $mrn = $request->mrn;
        $episno = $request->episno;
        if(!$mrn || !$episno){
            abort(404);
        }
        $age = $request->age;
        
        $pat_mast = DB::table('hisdb.pat_mast as pm')
                    ->select('pm.MRN','pm.Name','pm.Newic','b.ward','b.bednum','e.reg_date','h.diagnosis','h.operation')
                    ->leftJoin('hisdb.bedalloc as b', function ($join){
                        $join = $join->on('b.mrn','=','pm.MRN')
                                    ->on('b.episno','=','pm.Episno')
                                    ->where('b.compcode','=',session('compcode'));
                    })
                    ->leftJoin('hisdb.episode as e', function ($join){
                        $join = $join->on('e.mrn','=','pm.MRN')
                                    ->on('e.episno','=','pm.Episno')
                                    ->where('e.compcode','=',session('compcode'));
                    })
                    ->leftJoin('nursing.nursactplan_hdr as h', function ($join){
                        $join = $join->on('h.mrn','=','pm.MRN')
                                    ->on('h.episno','=','pm.Episno')
                                    ->where('h.compcode','=',session('compcode'));
                    })
                    ->where('pm.CompCode','=',session('compcode'))
                    ->where('pm.MRN','=',$mrn)
                    ->where('pm.Episno','=',$episno)
                    ->first();

        $imgDiag = DB::table('nursing.nursactplan_imgdiag as id')
                        ->select('id.compcode','id.mrn','id.episno','id.startdate','id.imgdiag','id.adduser','id.adddate','id.upduser','id.upddate','id.lastuser','id.lastupdate','id.computerid','id.remarks')
                        ->where('id.compcode','=',session('compcode'))
                        ->where('id.mrn','=',$mrn)
                        ->where('id.episno','=',$episno)
                        ->get();
        
        return view('hisdb.nursingActionPlan_MR.imgDiag_chart_pdfmake', compact('age','pat_mast','imgDiag'));
        
    }

    public function bloodTrans_chart(Request $request){
        
        $mrn = $request->mrn;
        $episno = $request->episno;
        if(!$mrn || !$episno){
            abort(404);
        }
        $age = $request->age;
        
        $pat_mast = DB::table('hisdb.pat_mast as pm')
                    ->select('pm.MRN','pm.Name','pm.Newic','b.ward','b.bednum','e.reg_date','h.diagnosis','h.operation')
                    ->leftJoin('hisdb.bedalloc as b', function ($join){
                        $join = $join->on('b.mrn','=','pm.MRN')
                                    ->on('b.episno','=','pm.Episno')
                                    ->where('b.compcode','=',session('compcode'));
                    })
                    ->leftJoin('hisdb.episode as e', function ($join){
                        $join = $join->on('e.mrn','=','pm.MRN')
                                    ->on('e.episno','=','pm.Episno')
                                    ->where('e.compcode','=',session('compcode'));
                    })
                    ->leftJoin('nursing.nursactplan_hdr as h', function ($join){
                        $join = $join->on('h.mrn','=','pm.MRN')
                                    ->on('h.episno','=','pm.Episno')
                                    ->where('h.compcode','=',session('compcode'));
                    })
                    ->where('pm.CompCode','=',session('compcode'))
                    ->where('pm.MRN','=',$mrn)
                    ->where('pm.Episno','=',$episno)
                    ->first();

        $bloodTrans = DB::table('nursing.nursactplan_bloodtrans as bt')
                        ->select('bt.compcode','bt.mrn','bt.episno','bt.startdate','bt.packcell','bt.wholebody','bt.platlet','bt.ffp','bt.adduser','bt.adddate','bt.upduser','bt.upddate','bt.lastuser','bt.lastupdate','bt.computerid','bt.remarks')
                        ->where('bt.compcode','=',session('compcode'))
                        ->where('bt.mrn','=',$mrn)
                        ->where('bt.episno','=',$episno)
                        ->get();
        
        return view('hisdb.nursingActionPlan_MR.bloodTrans_chart_pdfmake', compact('age','pat_mast','bloodTrans'));
        
    }

    public function exams_chart(Request $request){
        
        $mrn = $request->mrn;
        $episno = $request->episno;
        if(!$mrn || !$episno){
            abort(404);
        }
        $age = $request->age;
        
        $pat_mast = DB::table('hisdb.pat_mast as pm')
                    ->select('pm.MRN','pm.Name','pm.Newic','b.ward','b.bednum','e.reg_date','h.diagnosis','h.operation')
                    ->leftJoin('hisdb.bedalloc as b', function ($join){
                        $join = $join->on('b.mrn','=','pm.MRN')
                                    ->on('b.episno','=','pm.Episno')
                                    ->where('b.compcode','=',session('compcode'));
                    })
                    ->leftJoin('hisdb.episode as e', function ($join){
                        $join = $join->on('e.mrn','=','pm.MRN')
                                    ->on('e.episno','=','pm.Episno')
                                    ->where('e.compcode','=',session('compcode'));
                    })
                    ->leftJoin('nursing.nursactplan_hdr as h', function ($join){
                        $join = $join->on('h.mrn','=','pm.MRN')
                                    ->on('h.episno','=','pm.Episno')
                                    ->where('h.compcode','=',session('compcode'));
                    })
                    ->where('pm.CompCode','=',session('compcode'))
                    ->where('pm.MRN','=',$mrn)
                    ->where('pm.Episno','=',$episno)
                    ->first();

        $exams = DB::table('nursing.nursactplan_exam as e')
                        ->select('e.compcode','e.mrn','e.episno','e.startdate','e.dateline','e.exam','e.adduser','e.adddate','e.upduser','e.upddate','e.lastuser','e.lastupdate','e.computerid')
                        ->where('e.compcode','=',session('compcode'))
                        ->where('e.mrn','=',$mrn)
                        ->where('e.episno','=',$episno)
                        ->get();
        
        return view('hisdb.nursingActionPlan_MR.exams_chart_pdfmake', compact('age','pat_mast','exams'));
        
    }

    public function procedure_chart(Request $request){
        
        $mrn = $request->mrn;
        $episno = $request->episno;
        if(!$mrn || !$episno){
            abort(404);
        }
        $age = $request->age;
        
        $pat_mast = DB::table('hisdb.pat_mast as pm')
                    ->select('pm.MRN','pm.Name','pm.Newic','b.ward','b.bednum','e.reg_date','h.diagnosis','h.operation')
                    ->leftJoin('hisdb.bedalloc as b', function ($join){
                        $join = $join->on('b.mrn','=','pm.MRN')
                                    ->on('b.episno','=','pm.Episno')
                                    ->where('b.compcode','=',session('compcode'));
                    })
                    ->leftJoin('hisdb.episode as e', function ($join){
                        $join = $join->on('e.mrn','=','pm.MRN')
                                    ->on('e.episno','=','pm.Episno')
                                    ->where('e.compcode','=',session('compcode'));
                    })
                    ->leftJoin('nursing.nursactplan_hdr as h', function ($join){
                        $join = $join->on('h.mrn','=','pm.MRN')
                                    ->on('h.episno','=','pm.Episno')
                                    ->where('h.compcode','=',session('compcode'));
                    })
                    ->where('pm.CompCode','=',session('compcode'))
                    ->where('pm.MRN','=',$mrn)
                    ->where('pm.Episno','=',$episno)
                    ->first();

        $procedure = DB::table('nursing.nursactplan_procedure as p')
                        ->select('p.compcode','p.mrn','p.episno','p.startdate','p.prodType','p.enddate','p.adduser','p.adddate','p.upduser','p.upddate','p.lastuser','p.lastupdate','p.computerid','p.size')
                        ->where('p.compcode','=',session('compcode'))
                        ->where('p.mrn','=',$mrn)
                        ->where('p.episno','=',$episno)
                        ->get();

        $prodType = DB::table('nursing.nursactplan_procedure as p')
                    ->select('p.prodType')
                    ->where('p.compcode','=',session('compcode'))
                    ->where('p.mrn','=',$mrn)
                    ->where('p.episno','=',$episno)
                    ->distinct('p.prodType');
                    
        $prodType = $prodType->get(['p.prodType']);
        
        return view('hisdb.nursingActionPlan_MR.procedure_chart_pdfmake', compact('age','pat_mast','procedure','prodType'));
        
    }
}