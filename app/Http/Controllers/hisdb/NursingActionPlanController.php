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
                    'dateofadm' => $request->dateofadm,
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
                        'dateofadm' => $request->dateofadm,
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
                        'dateofadm' => $request->dateofadm,
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
                    'startdate' => Carbon::parse($request->startdate)->format('Y-m-d'),
                    'enddate' => Carbon::parse($request->enddate)->format('Y-m-d'),
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
                    'startdate' => Carbon::parse($request->startdate)->format('Y-m-d'),
                    'enddate' => Carbon::parse($request->enddate)->format('Y-m-d'),
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
            
            DB::table('nursing.nursactplan_treatment')
                ->where('compcode','=',session('compcode'))
                ->where('idno','=',$request->idno)
                ->delete();
            
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
                    'startdate' => Carbon::parse($request->startdate)->format('Y-m-d'),
                    'enddate' => Carbon::parse($request->enddate)->format('Y-m-d'),
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
                    'startdate' => Carbon::parse($request->startdate)->format('Y-m-d'),
                    'enddate' => Carbon::parse($request->enddate)->format('Y-m-d'),
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
            
            DB::table('nursing.nursactplan_observation')
                ->where('compcode','=',session('compcode'))
                ->where('idno','=',$request->idno)
                ->delete();
            
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
                    'startdate' => Carbon::parse($request->startdate)->format('Y-m-d'),
                    'enddate' => Carbon::parse($request->enddate)->format('Y-m-d'),
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
                    'startdate' => Carbon::parse($request->startdate)->format('Y-m-d'),
                    'enddate' => Carbon::parse($request->enddate)->format('Y-m-d'),
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
            
            DB::table('nursing.nursactplan_feeding')
                ->where('compcode','=',session('compcode'))
                ->where('idno','=',$request->idno)
                ->delete();
            
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
                    'startdate' => Carbon::parse($request->startdate)->format('Y-m-d'),
                    'dateline' => Carbon::parse($request->dateline)->format('Y-m-d'),
                    'imgdiag' => $request->imgdiag,
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
                    'startdate' => Carbon::parse($request->startdate)->format('Y-m-d'),
                    'dateline' => Carbon::parse($request->dateline)->format('Y-m-d'),
                    'imgdiag' => $request->imgdiag,
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
            
            DB::table('nursing.nursactplan_imgdiag')
                ->where('compcode','=',session('compcode'))
                ->where('idno','=',$request->idno)
                ->delete();
            
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
                    'startdate' => Carbon::parse($request->startdate)->format('Y-m-d'),
                    'packcell' => $request->packcell,
                    'wholebody' => $request->wholebody,
                    'platlet' => $request->platlet,
                    'ffp' => $request->ffp,
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
    
    public function edit_BloodTrans(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.nursactplan_bloodtrans')
                ->where('idno','=',$request->idno)
                ->update([
                    'startdate' => Carbon::parse($request->startdate)->format('Y-m-d'),
                    'packcell' => $request->packcell,
                    'wholebody' => $request->wholebody,
                    'platlet' => $request->platlet,
                    'ffp' => $request->ffp,
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
    
    public function del_BloodTrans(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('nursing.nursactplan_bloodtrans')
                ->where('compcode','=',session('compcode'))
                ->where('idno','=',$request->idno)
                ->delete();
            
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
                    'startdate' => Carbon::parse($request->startdate)->format('Y-m-d'),
                    'dateline' => Carbon::parse($request->dateline)->format('Y-m-d'),
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
                    'startdate' => Carbon::parse($request->startdate)->format('Y-m-d'),
                    'dateline' => Carbon::parse($request->dateline)->format('Y-m-d'),
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
            
            DB::table('nursing.nursactplan_exam')
                ->where('compcode','=',session('compcode'))
                ->where('idno','=',$request->idno)
                ->delete();
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
        
    }

    public function get_table_formHeader(Request $request){
        
        $header_obj = DB::table('nursing.nursactplan_hdr')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn_nursActionPlan)
                            ->where('episno','=',$request->episno_nursActionPlan);
        
        $responce = new stdClass();
        
        if($header_obj->exists()){
            $header_obj = $header_obj->first();
            $responce->header = $header_obj;
        }
        
        return json_encode($responce);
        
    }
    
}