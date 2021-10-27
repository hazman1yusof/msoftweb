<?php

namespace App\Http\Controllers\hisdb;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class AntenatalController extends defaultController
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
        return view('hisdb.antenatal.antenatal');
    }

    public function form(Request $request)
    {   
        DB::enableQueryLog();
        switch($request->action){
            case 'save_table_antenatal':

                switch($request->oper){
                    case 'add':
                        return $this->add($request);
                    case 'edit':
                        return $this->edit($request);
                    default:
                        return 'error happen..';
                }

                case 'get_table_antenatal':
                    return $this->get_table_antenatal($request);

            default:
                return 'error happen..';
        }
    }

    public function add(Request $request){

        DB::beginTransaction();

        try {

            DB::table('hisdb.pathistory')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_antenatal,
                        'pgh_myomectomy' => $request->pgh_myomectomy,
                        'pgh_laparoscopy' => $request->pgh_laparoscopy,
                        'pgh_endometriosis' => $request->pgh_endometriosis,
                        'lastpapsmear' => $request->lastpapsmear,
                        'pgh_others' => $request->pgh_others,
                        'pmh_renaldisease' => $request->pmh_renaldisease,
                        'pmh_hypertension' => $request->pmh_hypertension,
                        'pmh_diabetes' => $request->pmh_diabetes,
                        'pmh_heartdisease' => $request->pmh_heartdisease,
                        'pmh_others' => $request->pmh_others,
                        'psh_appendicectomy' => $request->psh_appendicectomy,
                        'psh_hypertension' => $request->psh_hypertension,
                        'psh_laparotomy' => $request->psh_laparotomy,
                        'psh_thyroidsurgery' => $request->psh_thyroidsurgery,
                        'psh_others' => $request->psh_others,
                        'fh_hypertension' => $request->fh_hypertension,
                        'fh_diabetes' => $request->fh_diabetes,
                        'fh_epilepsy' => $request->fh_epilepsy,
                        'fh_multipregnancy' => $request->fh_multipregnancy,
                        'fh_congenital' => $request->fh_congenital,
                        // ante-natal record
                        'anr_bloodgroup' => $request->anr_bloodgroup,
                        'anr_attInject_1st' => $request->anr_attInject_1st,
                        'anr_attInject_2nd' => $request->anr_attInject_2nd,
                        'anr_attInject_boost' => $request->anr_attInject_boost,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);

            DB::table('hisdb.pathealth')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_antenatal,
                        'episno' => $request->episno_antenatal,
                        'height' => $request->height,
                        'anr_rhesus' => $request->anr_rhesus,
                        'anr_rubella' => $request->anr_rubella,
                        'anr_vdrl' => $request->anr_vdrl,
                        'anr_hiv' => $request->anr_hiv,
                        'anr_hepaB_Ag' => $request->anr_hepaB_Ag,
                        'anr_hepaB_AB' => $request->anr_hepaB_AB,
                        'anr_bloodTrans' => $request->anr_bloodTrans,
                        'anr_drugAllergies' => $request->anr_drugAllergies,
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

            $pathistory_antenatal = DB::table('hisdb.pathistory')
                ->where('mrn','=',$request->mrn_antenatal)
                ->where('compcode','=',session('compcode'));
            
            if(!$pathistory_antenatal->exists()){
                DB::table('hisdb.pathistory')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_antenatal,
                        'pgh_myomectomy' => $request->pgh_myomectomy,
                        'pgh_laparoscopy' => $request->pgh_laparoscopy,
                        'pgh_endometriosis' => $request->pgh_endometriosis,
                        'lastpapsmear' => $request->lastpapsmear,
                        'pgh_others' => $request->pgh_others,
                        'pmh_renaldisease' => $request->pmh_renaldisease,
                        'pmh_hypertension' => $request->pmh_hypertension,
                        'pmh_diabetes' => $request->pmh_diabetes,
                        'pmh_heartdisease' => $request->pmh_heartdisease,
                        'pmh_others' => $request->pmh_others,
                        'psh_appendicectomy' => $request->psh_appendicectomy,
                        'psh_hypertension' => $request->psh_hypertension,
                        'psh_laparotomy' => $request->psh_laparotomy,
                        'psh_thyroidsurgery' => $request->psh_thyroidsurgery,
                        'psh_others' => $request->psh_others,
                        'fh_hypertension' => $request->fh_hypertension,
                        'fh_diabetes' => $request->fh_diabetes,
                        'fh_epilepsy' => $request->fh_epilepsy,
                        'fh_multipregnancy' => $request->fh_multipregnancy,
                        'fh_congenital' => $request->fh_congenital,
                        // ante-natal record
                        'anr_bloodgroup' => $request->anr_bloodgroup,
                        'anr_attInject_1st' => $request->anr_attInject_1st,
                        'anr_attInject_2nd' => $request->anr_attInject_2nd,
                        'anr_attInject_boost' => $request->anr_attInject_boost,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),                        
                    ]);
            }else{
                $pathistory_antenatal
                    ->update([
                        'pgh_myomectomy' => $request->pgh_myomectomy,
                        'pgh_laparoscopy' => $request->pgh_laparoscopy,
                        'pgh_endometriosis' => $request->pgh_endometriosis,
                        'lastpapsmear' => $request->lastpapsmear,
                        'pgh_others' => $request->pgh_others,
                        'pmh_renaldisease' => $request->pmh_renaldisease,
                        'pmh_hypertension' => $request->pmh_hypertension,
                        'pmh_diabetes' => $request->pmh_diabetes,
                        'pmh_heartdisease' => $request->pmh_heartdisease,
                        'pmh_others' => $request->pmh_others,
                        'psh_appendicectomy' => $request->psh_appendicectomy,
                        'psh_hypertension' => $request->psh_hypertension,
                        'psh_laparotomy' => $request->psh_laparotomy,
                        'psh_thyroidsurgery' => $request->psh_thyroidsurgery,
                        'psh_others' => $request->psh_others,
                        'fh_hypertension' => $request->fh_hypertension,
                        'fh_diabetes' => $request->fh_diabetes,
                        'fh_epilepsy' => $request->fh_epilepsy,
                        'fh_multipregnancy' => $request->fh_multipregnancy,
                        'fh_congenital' => $request->fh_congenital,
                        // ante-natal record
                        'anr_bloodgroup' => $request->anr_bloodgroup,
                        'anr_attInject_1st' => $request->anr_attInject_1st,
                        'anr_attInject_2nd' => $request->anr_attInject_2nd,
                        'anr_attInject_boost' => $request->anr_attInject_boost,
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }

            $pathealth_antenatal = DB::table('hisdb.pathealth')
                ->where('mrn','=',$request->mrn_antenatal)
                ->where('episno','=',$request->episno_antenatal)
                ->where('compcode','=',session('compcode'));

            if(!$pathealth_antenatal->exists()){
                DB::table('hisdb.pathealth')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_antenatal,
                        'episno' => $request->episno_antenatal,
                        'height' => $request->height,
                        'anr_rhesus' => $request->anr_rhesus,
                        'anr_rubella' => $request->anr_rubella,
                        'anr_vdrl' => $request->anr_vdrl,
                        'anr_hiv' => $request->anr_hiv,
                        'anr_hepaB_Ag' => $request->anr_hepaB_Ag,
                        'anr_hepaB_AB' => $request->anr_hepaB_AB,
                        'anr_bloodTrans' => $request->anr_bloodTrans,
                        'anr_drugAllergies' => $request->anr_drugAllergies,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }else{
                $pathealth_antenatal
                    ->update([
                        'height' => $request->height,
                        'anr_rhesus' => $request->anr_rhesus,
                        'anr_rubella' => $request->anr_rubella,
                        'anr_vdrl' => $request->anr_vdrl,
                        'anr_hiv' => $request->anr_hiv,
                        'anr_hepaB_Ag' => $request->anr_hepaB_Ag,
                        'anr_hepaB_AB' => $request->anr_hepaB_AB,
                        'anr_bloodTrans' => $request->anr_bloodTrans,
                        'anr_drugAllergies' => $request->anr_drugAllergies,
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

    public function get_table_antenatal(Request $request){
        
        $an_pathistory_obj = DB::table('hisdb.pathistory')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$request->mrn);

        $an_pathealth_obj = DB::table('hisdb.pathealth')
                    ->where('compcode','=',session('compcode'))
                    ->where('mrn','=',$request->mrn)
                    ->where('episno','=',$request->episno);

        $responce = new stdClass();

        if($an_pathistory_obj->exists()){
            $an_pathistory_obj = $an_pathistory_obj->first();
            $responce->an_pathistory = $an_pathistory_obj;
        }

        if($an_pathealth_obj->exists()){
            $an_pathealth_obj = $an_pathealth_obj->first();
            $responce->an_pathealth = $an_pathealth_obj;
        }

        return json_encode($responce);

    }

}