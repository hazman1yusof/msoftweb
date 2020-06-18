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

            $queries = DB::getQueryLog();
            // dump($queries);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response('Error DB rollback!'.$e, 500);
        }
    }

    public function get_table_dietorder(Request $request){
        
        $dietorder_obj = DB::table('nursing.dietorder AS d')
                    ->join('hisdb.episode AS e', function($join) use ($request){
                        $join = $join->on('d.mrn','=','e.mrn');
                        $join = $join->on('d.compcode','=','e.compcode');
                    })
                    ->where('d.compcode','=',session('compcode'))
                    ->where('d.mrn','=',$request->mrn)
                    ->where('d.episno','=',$request->episno);

        $responce = new stdClass();

        if($dietorder_obj->exists()){
            $dietorder_obj = $dietorder_obj->first();
            $responce->dietorder = $dietorder_obj;
        }

        return json_encode($responce);

    }

}