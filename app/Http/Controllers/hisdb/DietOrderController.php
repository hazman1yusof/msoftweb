<?php

namespace App\Http\Controllers\hisdb;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class DietOrderController extends defaultController
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
        return view('hisdb.dietorder.dietorder');
    }

    public function table(Request $request)
    {
        switch($request->action){
            case 'dietorder_preview':
                return $this->dietorder_preview($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function form(Request $request)
    {
        DB::enableQueryLog();
        switch($request->action){
            case 'save_table_dietOrder':
            
                switch($request->oper){
                    case 'add':
                        return $this->add($request);
                    case 'edit':
                        return $this->edit($request);
                    default:
                        return 'error happen..';
                }
            
            case 'get_table_dietorder':
                return $this->get_table_dietorder($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function add(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.dietorder')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn_dietOrder,
                    'episno' => $request->episno_dietOrder,
                    'oral' => $request->oral,
                    'nbm' => $request->nbm,
                    'rtf' => $request->rtf,
                    'rof' => $request->rof,
                    'tpn' => $request->tpn,
                    'regular_a' => $request->regular_a,
                    'regular_b' => $request->regular_b,
                    'soft' => $request->soft,
                    'vegetarian_c' => $request->vegetarian_c,
                    'western_d' => $request->western_d,
                    'highprotein' => $request->highprotein,
                    'highcalorie' => $request->highcalorie,
                    'highfiber' => $request->highfiber,
                    'diabetic' => $request->diabetic,
                    'lowprotein' => $request->lowprotein,
                    'lowfat' => $request->lowfat,
                    'red1200kcal' => $request->red1200kcal,
                    'red1500kcal' => $request->red1500kcal,
                    'paed6to12mth' => $request->paed6to12mth,
                    'paed1to3yr' => $request->paed1to3yr,
                    'paed4to9yr' => $request->paed4to9yr,
                    'paedgt10yr' => $request->paedgt10yr,
                    'lodgerflag' => $request->lodgerflag,
                    'lodgervalue' => $request->lodgervalue,
                    'disposable' => $request->disposable,
                    'remark' => $request->remark,
                    'remarkkitchen' => $request->remarkkitchen,
                    'adduser'  => session('username'),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
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
            
            $dietorder = DB::table('nursing.dietorder')
                        ->where('mrn','=',$request->mrn_dietOrder)
                        ->where('episno','=',$request->episno_dietOrder)
                        ->where('compcode','=',session('compcode'));
            
            if($dietorder->exists()){
                DB::table('nursing.dietorder')
                    ->where('mrn','=',$request->mrn_dietOrder)
                    ->where('episno','=',$request->episno_dietOrder)
                    ->where('compcode','=',session('compcode'))
                    ->update([
                        'oral' => $request->oral,
                        'nbm' => $request->nbm,
                        'rtf' => $request->rtf,
                        'rof' => $request->rof,
                        'tpn' => $request->tpn,
                        'regular_a' => $request->regular_a,
                        'regular_b' => $request->regular_b,
                        'soft' => $request->soft,
                        'vegetarian_c' => $request->vegetarian_c,
                        'western_d' => $request->western_d,
                        'highprotein' => $request->highprotein,
                        'highcalorie' => $request->highcalorie,
                        'highfiber' => $request->highfiber,
                        'diabetic' => $request->diabetic,
                        'lowprotein' => $request->lowprotein,
                        'lowfat' => $request->lowfat,
                        'red1200kcal' => $request->red1200kcal,
                        'red1500kcal' => $request->red1500kcal,
                        'paed6to12mth' => $request->paed6to12mth,
                        'paed1to3yr' => $request->paed1to3yr,
                        'paed4to9yr' => $request->paed4to9yr,
                        'paedgt10yr' => $request->paedgt10yr,
                        'lodgerflag' => $request->lodgerflag,
                        'lodgervalue' => $request->lodgervalue,
                        'disposable' => $request->disposable,
                        'remark' => $request->remark,
                        'remarkkitchen' => $request->remarkkitchen,
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }else{
                DB::table('nursing.dietorder')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_dietOrder,
                        'episno' => $request->episno_dietOrder,
                        'oral' => $request->oral,
                        'nbm' => $request->nbm,
                        'rtf' => $request->rtf,
                        'rof' => $request->rof,
                        'tpn' => $request->tpn,
                        'regular_a' => $request->regular_a,
                        'regular_b' => $request->regular_b,
                        'soft' => $request->soft,
                        'vegetarian_c' => $request->vegetarian_c,
                        'western_d' => $request->western_d,
                        'highprotein' => $request->highprotein,
                        'highcalorie' => $request->highcalorie,
                        'highfiber' => $request->highfiber,
                        'diabetic' => $request->diabetic,
                        'lowprotein' => $request->lowprotein,
                        'lowfat' => $request->lowfat,
                        'red1200kcal' => $request->red1200kcal,
                        'red1500kcal' => $request->red1500kcal,
                        'paed6to12mth' => $request->paed6to12mth,
                        'paed1to3yr' => $request->paed1to3yr,
                        'paed4to9yr' => $request->paed4to9yr,
                        'paedgt10yr' => $request->paedgt10yr,
                        'lodgerflag' => $request->lodgerflag,
                        'lodgervalue' => $request->lodgervalue,
                        'disposable' => $request->disposable,
                        'remark' => $request->remark,
                        'remarkkitchen' => $request->remarkkitchen,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
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
    
    public function get_table_dietorder(Request $request){
        
        $dietorder_obj = DB::table('nursing.dietorder')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno);
        
        $episode_obj = DB::table('hisdb.episode')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno);
        
        $responce = new stdClass();
        
        if($dietorder_obj->exists()){
            $dietorder_obj = $dietorder_obj->first();
            $responce->dietorder = $dietorder_obj;
        }
        
        if($episode_obj->exists()){
            $episode_obj = $episode_obj->first();
            $responce->episode = $episode_obj;
        }
        
        return json_encode($responce);
        
    }

    public function dietorder_preview(Request $request){

        if(empty($request->mrn) || empty($request->episno)){
            abort(403,'No mrn or episno');
        }

        $dietorder = DB::table('nursing.dietorder as do')
            ->select('do.idno','do.compcode','do.mrn','do.episno','do.lodgerflag','do.lodgervalue','do.nbm','do.rtf','do.rof','do.tpn','do.oral','do.regular_a','do.regular_b','do.soft','do.vegetarian_c','do.western_d','do.highprotein','do.highcalorie','do.highfiber','do.diabetic','do.lowprotein','do.lowfat','do.soft_lodger','do.red1200kcal','do.red1500kcal','do.paed6to12mth','do.paed1to3yr','do.paed4to9yr','do.paedgt10yr','do.disposable','do.remark','do.lastuser','do.lastupdate','do.regular_a_lodger','do.regular_b_lodger','do.vegetarian_c_lodger','do.western_d_lodger','do.highprotein_lodger','do.highcalorie_lodger','do.highfiber_lodger','do.diabetic_lodger','do.lowprotein_lodger','do.lowfat_lodger','do.red1200kcal_lodger','do.red1500kcal_lodger','do.paed6to12mth_lodger','do.paed1to3yr_lodger','do.paed4to9yr_lodger','do.paedgt10yr_lodger','do.remarkkitchen','do.adduser','do.adddate','pm.dob','pm.Name','ep.diagfinal','ep.ward','ep.bed')
            ->leftJoin('hisdb.pat_mast as pm', function($join){
                $join = $join->where('pm.compcode', '=', session('compcode'));
                $join = $join->on('pm.mrn', '=', 'do.mrn');
            })->leftJoin('hisdb.episode as ep', function($join){
                $join = $join->where('ep.compcode', '=', session('compcode'));
                $join = $join->on('ep.mrn', '=', 'do.mrn');
                $join = $join->on('ep.episno', '=', 'do.episno');
            })
            ->where('do.compcode',session('compcode'))
            ->where('do.mrn',$request->mrn)
            ->where('do.episno',$request->episno)
            ->get();

        foreach ($dietorder as $diet) {
            $DOB = $diet->dob;

            if(!empty($DOB)){
                $age = Carbon::createFromFormat("Y-m-d", $DOB)->age;
            }else{
                $age = '';
            }

            $diet->age=$age;
        }

        return view('hisdb.dietorder.dietorder_preview',compact('dietorder'));
    }
    
}