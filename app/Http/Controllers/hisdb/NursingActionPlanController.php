<?php

namespace App\Http\Controllers\hisdb;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class NursingActionPlanController extends defaultController
{
    
    var $table;
    var $duplicateCode;
    
    public function __construct(){
        $this->middleware('auth');
    }
    
    public function show(Request $request){
        
        return view('hisdb.nursingActionPlan.nursingActionPlan');
        
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
            case 'save_table_header':
                switch($request->oper){
                    case 'add':
                        return $this->add_header($request);
                    case 'edit':
                        return $this->edit_header($request);
                    default:
                        return 'error happen..';
                }

            case 'Treatment_save':
                return $this->add_Treatment($request);
            
            case 'Treatment_edit':
                return $this->edit_Treatment($request);
            
            case 'Treatment_del':
                return $this->del_Treatment($request);
            
            case 'Observation_save':
                return $this->add_Observation($request);
            
            case 'Observation_edit':
                return $this->edit_Observation($request);
            
            case 'Observation_del':
                return $this->del_Observation($request);
            
            case 'Feeding_save':
                return $this->add_Feeding($request);
            
            case 'Feeding_edit':
                return $this->edit_Feeding($request);
            
            case 'Feeding_del':
                return $this->del_Feeding($request);
            
            case 'ImgDiag_save':
                return $this->add_ImgDiag($request);
            
            case 'ImgDiag_edit':
                return $this->edit_ImgDiag($request);
            
            case 'ImgDiag_del':
                return $this->del_ImgDiag($request);

            case 'BloodTrans_save':
                return $this->add_BloodTrans($request);
            
            case 'BloodTrans_edit':
                return $this->edit_BloodTrans($request);
            
            case 'BloodTrans_del':
                return $this->del_BloodTrans($request);

            case 'Exams_save':
                return $this->add_Exams($request);
            
            case 'Exams_edit':
                return $this->edit_Exams($request);
            
            case 'Exams_del':
                return $this->del_Exams($request);

            case 'Procedure_save':
                return $this->add_Procedure($request);
            
            case 'Procedure_edit':
                return $this->edit_Procedure($request);
            
            case 'Procedure_del':
                return $this->del_Procedure($request);
        
            case 'get_table_formHeader':
                return $this->get_table_formHeader($request);

            default:
                return 'error happen..';
        }
        
        // switch($request->oper){
        //     default:
        //         return 'error happen..';
        // }
    }

    public function add_header(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.nursactplan_hdr')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn_nursActionPlan,
                    'episno' => $request->episno_nursActionPlan,
                    'dateofadm' => $request->reg_date,
                    'op_date' => $request->op_date,
                    'operation' => $request->operation,
                    'diagnosis' => $request->diagnosis,
                    'adduser'  => session('username'),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function edit_header(Request $request){
        
        DB::beginTransaction();
        
        try {

            $header = DB::table('nursing.nursactplan_hdr')
                        ->where('mrn','=',$request->mrn_nursActionPlan)
                        ->where('episno','=',$request->episno_nursActionPlan)
                        ->where('compcode','=',session('compcode'));
            
            if($header->exists()){
                DB::table('nursing.nursactplan_hdr')
                    ->where('mrn','=',$request->mrn_nursActionPlan)
                    ->where('episno','=',$request->episno_nursActionPlan)
                    ->where('compcode','=',session('compcode'))
                    ->update([
                        'dateofadm' => $request->reg_date,
                        'op_date' => $request->op_date,
                        'operation' => $request->operation,
                        'diagnosis' => $request->diagnosis,
                        'upduser'  => session('username'),
                        'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }else{
                DB::table('nursing.nursactplan_hdr')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_nursActionPlan,
                        'episno' => $request->episno_nursActionPlan,
                        'dateofadm' => $request->reg_date,
                        'op_date' => $request->op_date,
                        'operation' => $request->operation,
                        'diagnosis' => $request->diagnosis,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
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

    public function add_Treatment(Request $request){
        
        DB::beginTransaction();
        
        try {

            DB::table('nursing.nursactplan_treatment')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn,
                    'episno' => $request->episno,
                    'startdate' => $request->startdate,
                    'enddate' => $request->enddate,
                    'treatment' => $request->treatment,
                    'adduser'  => session('username'),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    // 'lastuser'  => session('username'),
                    // 'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'computerid' => session('computerid'),
                ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function edit_Treatment(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.nursactplan_treatment')
                ->where('idno','=',$request->idno)
                ->update([
                    'startdate' => $request->startdate,
                    'enddate' => $request->enddate,
                    'treatment' => $request->treatment,
                    'upduser'  => session('username'),
                    'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    // 'lastuser'  => session('username'),
                    // 'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'computerid' => session('computerid'),
                ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function del_Treatment(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $treatment = DB::table('nursing.nursactplan_treatment')
                        ->where('idno','=',$request->idno)
                        ->where('compcode','=',session('compcode'))
                        ->first();

            if($treatment->adduser !== session('username')){
                throw new \Exception("You are not authorized to delete this data.",500);
            } else{
                DB::table('nursing.nursactplan_treatment')
                    ->where('compcode','=',session('compcode'))
                    ->where('idno','=',$request->idno)
                    ->delete();
            }
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
        
    }
    
    public function add_Observation(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.nursactplan_observation')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn,
                    'episno' => $request->episno,
                    'startdate' => $request->startdate,
                    'enddate' => $request->enddate,
                    'observation' => $request->observation,
                    'adduser'  => session('username'),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    // 'lastuser'  => session('username'),
                    // 'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'computerid' => session('computerid'),
                ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function edit_Observation(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.nursactplan_observation')
                ->where('idno','=',$request->idno)
                ->update([
                    'startdate' => $request->startdate,
                    'enddate' => $request->enddate,
                    'observation' => $request->observation,
                    'upduser'  => session('username'),
                    'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    // 'lastuser'  => session('username'),
                    // 'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'computerid' => session('computerid'),
                ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function del_Observation(Request $request){
        
        DB::beginTransaction();
        
        try {

            $observation = DB::table('nursing.nursactplan_observation')
                        ->where('idno','=',$request->idno)
                        ->where('compcode','=',session('compcode'))
                        ->first();

            if($observation->adduser !== session('username')){
                throw new \Exception("You are not authorized to delete this data.",500);
            } else{
                DB::table('nursing.nursactplan_observation')
                    ->where('compcode','=',session('compcode'))
                    ->where('idno','=',$request->idno)
                    ->delete();
            }
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
        
    }
    
    public function add_Feeding(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.nursactplan_feeding')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn,
                    'episno' => $request->episno,
                    'startdate' => $request->startdate,
                    'enddate' => $request->enddate,
                    'feeding' => $request->feeding,
                    'adduser'  => session('username'),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    // 'lastuser'  => session('username'),
                    // 'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'computerid' => session('computerid'),
                ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function edit_Feeding(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.nursactplan_feeding')
                ->where('idno','=',$request->idno)
                ->update([
                    'startdate' => $request->startdate,
                    'enddate' => $request->enddate,
                    'feeding' => $request->feeding,
                    'upduser'  => session('username'),
                    'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    // 'lastuser'  => session('username'),
                    // 'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'computerid' => session('computerid'),
                ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function del_Feeding(Request $request){
        
        DB::beginTransaction();
        
        try {
            $feeding = DB::table('nursing.nursactplan_feeding')
                        ->where('idno','=',$request->idno)
                        ->where('compcode','=',session('compcode'))
                        ->first();

            if($feeding->adduser !== session('username')){
                throw new \Exception("You are not authorized to delete this data.",500);
            } else{
                DB::table('nursing.nursactplan_feeding')
                    ->where('compcode','=',session('compcode'))
                    ->where('idno','=',$request->idno)
                    ->delete();
            }
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
        
    }
    
    public function add_ImgDiag(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.nursactplan_imgdiag')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn,
                    'episno' => $request->episno,
                    'startdate' => $request->startdate,
                    // 'dateline' => Carbon::parse($request->dateline)->format('Y-m-d'),
                    'imgdiag' => $request->imgdiag,
                    'remarks' => $request->remarks,
                    'adduser'  => session('username'),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    // 'lastuser'  => session('username'),
                    // 'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'computerid' => session('computerid'),
                ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
        
    }
    
    public function edit_ImgDiag(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.nursactplan_imgdiag')
                ->where('idno','=',$request->idno)
                ->update([
                    'startdate' => $request->startdate,
                    // 'dateline' => Carbon::parse($request->dateline)->format('Y-m-d'),
                    'imgdiag' => $request->imgdiag,
                    'remarks' => $request->remarks,
                    'upduser'  => session('username'),
                    'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    // 'lastuser'  => session('username'),
                    // 'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'computerid' => session('computerid'),
                ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function del_ImgDiag(Request $request){
        
        DB::beginTransaction();
        
        try {

            $imgdiag = DB::table('nursing.nursactplan_imgdiag')
                        ->where('idno','=',$request->idno)
                        ->where('compcode','=',session('compcode'))
                        ->first();

            if($imgdiag->adduser !== session('username')){
                throw new \Exception("You are not authorized to delete this data.",500);
            } else{
                DB::table('nursing.nursactplan_imgdiag')
                    ->where('compcode','=',session('compcode'))
                    ->where('idno','=',$request->idno)
                    ->delete();
            }
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
        
    }

    public function add_BloodTrans(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.nursactplan_bloodtrans')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn,
                    'episno' => $request->episno,
                    'startdate' => $request->startdate,
                    'packcell' => $request->packcell,
                    'wholebody' => $request->wholebody,
                    'platlet' => $request->platlet,
                    'ffp' => $request->ffp,
                    'adduser'  => session('username'),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    // 'lastuser'  => session('username'),
                    // 'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'computerid' => session('computerid'),
                    'remarks' => $request->remarks,
                ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
        
    }
    
    public function edit_BloodTrans(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.nursactplan_bloodtrans')
                ->where('idno','=',$request->idno)
                ->update([
                    'startdate' => $request->startdate,
                    'packcell' => $request->packcell,
                    'wholebody' => $request->wholebody,
                    'platlet' => $request->platlet,
                    'ffp' => $request->ffp,
                    'upduser'  => session('username'),
                    'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    // 'lastuser'  => session('username'),
                    // 'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'computerid' => session('computerid'),
                    'remarks' => $request->remarks,
                ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function del_BloodTrans(Request $request){
        
        DB::beginTransaction();
        
        try {

            $bloodtrans = DB::table('nursing.nursactplan_bloodtrans')
                        ->where('idno','=',$request->idno)
                        ->where('compcode','=',session('compcode'))
                        ->first();

            if($bloodtrans->adduser !== session('username')){
                throw new \Exception("You are not authorized to delete this data.",500);
            } else{
                DB::table('nursing.nursactplan_bloodtrans')
                    ->where('compcode','=',session('compcode'))
                    ->where('idno','=',$request->idno)
                    ->delete();
            }
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
        
    }

    public function add_Exams(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.nursactplan_exam')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn,
                    'episno' => $request->episno,
                    'startdate' => $request->startdate,
                    'dateline' => $request->dateline,
                    'exam' => $request->exam,
                    'adduser'  => session('username'),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    // 'lastuser'  => session('username'),
                    // 'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'computerid' => session('computerid'),
                ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
        
    }
    
    public function edit_Exams(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.nursactplan_exam')
                ->where('idno','=',$request->idno)
                ->update([
                    'startdate' => $request->startdate,
                    'dateline' => $request->dateline,
                    'exam' => $request->exam,
                    'upduser'  => session('username'),
                    'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    // 'lastuser'  => session('username'),
                    // 'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'computerid' => session('computerid'),
                ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function del_Exams(Request $request){
        
        DB::beginTransaction();
        
        try {
            $exam = DB::table('nursing.nursactplan_exam')
                        ->where('idno','=',$request->idno)
                        ->where('compcode','=',session('compcode'))
                        ->first();

            if($exam->adduser !== session('username')){
                throw new \Exception("You are not authorized to delete this data.",500);
            } else{
                DB::table('nursing.nursactplan_exam')
                    ->where('compcode','=',session('compcode'))
                    ->where('idno','=',$request->idno)
                    ->delete();
            }
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
        
    }

    public function add_Procedure(Request $request){
        
        DB::beginTransaction();
        
        try {

            if(($request->prodType)=='artLine'){
                $ptype = 'artLine';
            }else if(($request->prodType)=='CVP'){
                $ptype = 'CVP';
            }else if(($request->prodType)=='venLine'){
                $ptype = 'venLine';
            }else if(($request->prodType)=='ETT'){
                $ptype = 'ETT';
            }else if(($request->prodType)=='CBD'){
                $ptype = 'CBD';
            }else if(($request->prodType)=='STO'){
                $ptype = 'STO';
            }else{
                $ptype = 'woundIns';
            }

            // dd($ptype);
            
            DB::table('nursing.nursactplan_procedure')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn_nursActionPlan,
                    'episno' => $request->episno_nursActionPlan,
                    'startdate' => $request->startdate,
                    'size' => $request->size,
                    'enddate' => $request->enddate,
                    'prodType' => $ptype,
                    'adduser'  => session('username'),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    // 'lastuser'  => session('username'),
                    // 'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'computerid' => session('computerid'),
                ]);
            
                // dd($request->enddate);
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
        
    }
    
    public function edit_Procedure(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.nursactplan_procedure')
                ->where('idno','=',$request->idno)
                ->update([
                    'startdate' => $request->startdate,
                    'size' => $request->size,
                    'enddate' => Carbon::parse($request->enddate)->format('Y-m-d'),
                    'upduser'  => session('username'),
                    'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    // 'lastuser'  => session('username'),
                    // 'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'computerid' => session('computerid'),
                ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function del_Procedure(Request $request){
        
        DB::beginTransaction();
        
        try {
            $procedure = DB::table('nursing.nursactplan_procedure')
                        ->where('idno','=',$request->idno)
                        ->where('compcode','=',session('compcode'))
                        ->first();

            if($procedure->adduser !== session('username')){
                throw new \Exception("You are not authorized to delete this data.",500);
            } else{
                DB::table('nursing.nursactplan_procedure')
                    ->where('compcode','=',session('compcode'))
                    ->where('idno','=',$request->idno)
                    ->delete();
            }
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
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
        
        return view('hisdb.nursingActionPlan.treatment_chart_pdfmake', compact('age','pat_mast','treatment'));
        
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
        
        return view('hisdb.nursingActionPlan.observation_chart_pdfmake', compact('age','pat_mast','observation'));
        
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
        
        return view('hisdb.nursingActionPlan.feeding_chart_pdfmake', compact('age','pat_mast','feeding'));
        
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
        
        return view('hisdb.nursingActionPlan.imgDiag_chart_pdfmake', compact('age','pat_mast','imgDiag'));
        
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
        
        return view('hisdb.nursingActionPlan.bloodTrans_chart_pdfmake', compact('age','pat_mast','bloodTrans'));
        
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
        
        return view('hisdb.nursingActionPlan.exams_chart_pdfmake', compact('age','pat_mast','exams'));
        
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
        
        return view('hisdb.nursingActionPlan.procedure_chart_pdfmake', compact('age','pat_mast','procedure','prodType'));
        
    }
}