<?php

namespace App\Http\Controllers\hisdb;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class AdmHandoverController extends defaultController
{
    
    var $table;
    var $duplicateCode;
    
    public function __construct()
    {
        $this->middleware('auth');
        // $this->duplicateCode = "chgtype";
    }
    
    public function show(Request $request)
    {
        return view('hisdb.admhandover.admhandover');
    }
    
    public function table(Request $request)
    {
        // switch($request->action){
        //     case 'dietorder_preview':
        //         return $this->dietorder_preview($request);
            
        //     default:
        //         return 'error happen..';
        // }
    }
    
    public function form(Request $request)
    {
        DB::enableQueryLog();
        switch($request->action){
            case 'save_table_admHandover':
            
                switch($request->oper){
                    case 'add':
                        return $this->add($request);
                    case 'edit':
                        return $this->edit($request);
                    default:
                        return 'error happen..';
                }
            
            case 'get_table_admhandover':
                return $this->get_table_admhandover($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function add(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.admhandover')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn_admHandover,
                    'episno' => $request->episno_admHandover,
                    'dateofadm' => $request->dateofadm,
                    'reasonadm' => $request->reasonadm,
                    'type' => $request->type,
                    'rtkpcr' => $request->rtkpcr,
                    'rtkpcr_remark' => $request->rtkpcr_remark,
                    'bloodinv' => $request->bloodinv,
                    'bloodinv_remark' => $request->bloodinv_remark,
                    'branula' => $request->branula,
                    'branula_remark' => $request->branula_remark,
                    'scan' => $request->scan,
                    'scan_remark' => $request->scan_remark,
                    'insurance' => $request->insurance,
                    'insurance_remark' => $request->insurance_remark,
                    'medication' => $request->medication,
                    'medication_remark' => $request->medication_remark,
                    'consent' => $request->consent,
                    'consent_remark' => $request->consent_remark,
                    'smoking' => $request->smoking,
                    'smoking_remark' => $request->smoking_remark,
                    'nbm' => $request->nbm,
                    'nbm_remark' => $request->nbm_remark,
                    'report' => $request->report,
                    'adduser'  => session('username'),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur"),
                ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function edit(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $admhandover = DB::table('nursing.admhandover')
                        ->where('mrn','=',$request->mrn_admHandover)
                        ->where('episno','=',$request->episno_admHandover)
                        ->where('compcode','=',session('compcode'));
            
            if($admhandover->exists()){
                DB::table('nursing.admhandover')
                    ->where('mrn','=',$request->mrn_admHandover)
                    ->where('episno','=',$request->episno_admHandover)
                    ->where('compcode','=',session('compcode'))
                    ->update([
                        'dateofadm' => $request->dateofadm,
                        'reasonadm' => $request->reasonadm,
                        'type' => $request->type,
                        'rtkpcr' => $request->rtkpcr,
                        'rtkpcr_remark' => $request->rtkpcr_remark,
                        'bloodinv' => $request->bloodinv,
                        'bloodinv_remark' => $request->bloodinv_remark,
                        'branula' => $request->branula,
                        'branula_remark' => $request->branula_remark,
                        'scan' => $request->scan,
                        'scan_remark' => $request->scan_remark,
                        'insurance' => $request->insurance,
                        'insurance_remark' => $request->insurance_remark,
                        'medication' => $request->medication,
                        'medication_remark' => $request->medication_remark,
                        'consent' => $request->consent,
                        'consent_remark' => $request->consent_remark,
                        'smoking' => $request->smoking,
                        'smoking_remark' => $request->smoking_remark,
                        'nbm' => $request->nbm,
                        'nbm_remark' => $request->nbm_remark,
                        'report' => $request->report,
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur"),
                    ]);
            }else{
                DB::table('nursing.admhandover')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_admHandover,
                        'episno' => $request->episno_admHandover,
                        'dateofadm' => $request->dateofadm,
                        'reasonadm' => $request->reasonadm,
                        'type' => $request->type,
                        'rtkpcr' => $request->rtkpcr,
                        'rtkpcr_remark' => $request->rtkpcr_remark,
                        'bloodinv' => $request->bloodinv,
                        'bloodinv_remark' => $request->bloodinv_remark,
                        'branula' => $request->branula,
                        'branula_remark' => $request->branula_remark,
                        'scan' => $request->scan,
                        'scan_remark' => $request->scan_remark,
                        'insurance' => $request->insurance,
                        'insurance_remark' => $request->insurance_remark,
                        'medication' => $request->medication,
                        'medication_remark' => $request->medication_remark,
                        'consent' => $request->consent,
                        'consent_remark' => $request->consent_remark,
                        'smoking' => $request->smoking,
                        'smoking_remark' => $request->smoking_remark,
                        'nbm' => $request->nbm,
                        'nbm_remark' => $request->nbm_remark,
                        'report' => $request->report,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur"),
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
    
    public function get_table_admhandover(Request $request){
        
        $admhandover_obj = DB::table('nursing.admhandover')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$request->mrn)
                    ->where('episno','=',$request->episno);
        
        $episode_obj = DB::table('hisdb.episode')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$request->mrn)
                    ->where('episno','=',$request->episno);
    
        $nurshistory_obj = DB::table('nursing.nurshistory')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$request->mrn);

        $nursassessment_obj = DB::table('nursing.nursassessment')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$request->mrn)
                    ->where('episno','=',$request->episno);
        
        // $allergy = $nurshistory_obj->pvalue1.' '.$sysparam->pvalue1;

        $responce = new stdClass();
        
        if($admhandover_obj->exists()){
            $admhandover_obj = $admhandover_obj->first();
            $responce->admhandover = $admhandover_obj;
        }
        
        if($episode_obj->exists()){
            $episode_obj = $episode_obj->first();
            $responce->episode = $episode_obj;
        }

        if($nurshistory_obj->exists()){
            $nurshistory_obj = $nurshistory_obj->first();
            $responce->nurshistory = $nurshistory_obj;
        }

        if($nursassessment_obj->exists()){
            $nursassessment_obj = $nursassessment_obj->first();
            // dd($nursassessment_obj);
            $responce->nursassessment = $nursassessment_obj;
        }
        
        return json_encode($responce);
        
    }
    
    // public function dietorder_preview(Request $request){

    //     $sel_epistycode = $request->epistycode;
    //     if(empty($sel_epistycode)){
    //         abort('404','No Epistycode');
    //     }

    //     $dietorder = DB::table('nursing.dietorder as do')
    //                 ->select('do.idno','do.compcode','do.mrn','do.episno','do.lodgerflag','do.lodgervalue','do.nbm','do.rtf','do.rof','do.tpn','do.oral','do.regular_a','do.regular_b','do.soft','do.vegetarian_c','do.western_d','do.highprotein','do.highcalorie','do.highfiber','do.diabetic','do.lowprotein','do.lowfat','do.soft_lodger','do.red1200kcal','do.red1500kcal','do.paed6to12mth','do.paed1to3yr','do.paed4to9yr','do.paedgt10yr','do.disposable','do.remark','do.lastuser','do.lastupdate','do.regular_a_lodger','do.regular_b_lodger','do.vegetarian_c_lodger','do.western_d_lodger','do.highprotein_lodger','do.highcalorie_lodger','do.highfiber_lodger','do.diabetic_lodger','do.lowprotein_lodger','do.lowfat_lodger','do.red1200kcal_lodger','do.red1500kcal_lodger','do.paed6to12mth_lodger','do.paed1to3yr_lodger','do.paed4to9yr_lodger','do.paedgt10yr_lodger','do.remarkkitchen','do.adduser','do.adddate','pm.dob','pm.Name','ep.diagfinal','ep.ward','ep.bed')
    //                 ->leftJoin('hisdb.pat_mast as pm', function ($join){
    //                     $join = $join->where('pm.compcode', '=', session('compcode'));
    //                     $join = $join->on('pm.mrn', '=', 'do.mrn');
    //                 })->leftJoin('hisdb.episode as ep', function ($join){
    //                     $join = $join->where('ep.compcode', '=', session('compcode'));
    //                     $join = $join->on('ep.mrn', '=', 'do.mrn');
    //                     $join = $join->on('ep.episno', '=', 'do.episno');
    //                 })->join('hisdb.queue', function($join) use ($request,$sel_epistycode){
    //                             $join = $join->on('queue.mrn', '=', 'do.mrn')
    //                                         ->where('queue.billflag','=',0)
    //                                         ->where('queue.compcode','=',session('compcode'))
    //                                         ->where('queue.deptcode','=',"ALL");

    //                             if($sel_epistycode == 'OP'){
    //                                 $join = $join->whereIn('queue.epistycode', ['OP','OTC']);
    //                             }else{
    //                                 $join = $join->whereIn('queue.epistycode', ['IP','DP']);
    //                             }
    //                         })
    //                 ->where('do.compcode',session('compcode'))
    //                 ->get();
        
    //     foreach($dietorder as $diet){
    //         $DOB = $diet->dob;
            
    //         if(!empty($DOB)){
    //             $age = Carbon::createFromFormat("Y-m-d", $DOB)->age;
    //         }else{
    //             $age = '';
    //         }
            
    //         $diet->age = $age;
    //     }

    //     // dd($dietorder);
        
    //     return view('hisdb.dietorder.dietorder_preview',compact('dietorder'));
        
    // }
    
}