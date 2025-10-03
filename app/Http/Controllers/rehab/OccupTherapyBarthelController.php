<?php

namespace App\Http\Controllers\rehab;

use Illuminate\Http\Request;
use stdClass;
use App\User;
use DB;
use Carbon\Carbon;
use Auth;
use Session;
use App\Http\Controllers\defaultController;

class OccupTherapyBarthelController extends defaultController
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function show(Request $request)
    {
        return view('rehab.occupTherapy.occupTherapy_barthel');
    }

    public function table(Request $request)
    {
        switch($request->action){
            case 'get_table_datetimeBarthel':
                return $this->get_table_datetimeBarthel($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function form(Request $request)
    {
        DB::enableQueryLog();
        switch($request->action){
            case 'save_table_barthel':
                switch($request->oper){
                    case 'add':
                        return $this->add_barthel($request);
                    case 'edit':
                        return $this->edit_barthel($request);
                    default:
                        return 'error happen..';
                }
            
            case 'get_table_barthel':
                return $this->get_table_barthel($request);
            
            default:
                return 'error happen..';
        }
    }

    public function get_table_datetimeBarthel(Request $request){
        
        $responce = new stdClass();
        
        $barthel_obj = DB::table('hisdb.ot_barthel')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno);
        
        if($barthel_obj->exists()){
            $barthel_obj = $barthel_obj->get();
            
            $data = [];
            
            foreach($barthel_obj as $key => $value){
                if(!empty($value->dateofAssessment)){
                    $date['dateofAssessment'] =  Carbon::createFromFormat('Y-m-d', $value->dateofAssessment)->format('d-m-Y');
                }else{
                    $date['dateofAssessment'] =  '-';
                }
                $date['idno'] = $value->idno;
                $date['mrn'] = $value->mrn;
                $date['episno'] = $value->episno;
                // if(!empty($value->timeAssessment)){
                //     $date['timeAssessment'] =  Carbon::createFromFormat('H:i:s', $value->timeAssessment)->format('h:i A');
                // }else{
                //     $date['timeAssessment'] =  '-';
                // }
                
                array_push($data,$date);
            }
            
            $responce->data = $data;
        }else{
            $responce->data = [];
        }
        
        return json_encode($responce);
        
    }
    
    public function add_barthel(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            DB::table('hisdb.ot_barthel')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'dateofAssessment' => $request->dateofAssessment,
                        // 'timeAssessment' => $request->timeAssessment,
                        'chairBedTrf' => $request->chairBedTrf,
                        'ambulation' => $request->ambulation,
                        'ambulationWheelchair' => $request->ambulationWheelchair,
                        'stairClimbing' => $request->stairClimbing,
                        'toiletTrf' => $request->toiletTrf,
                        'bowelControl' => $request->bowelControl,
                        'bladderControl' => $request->bladderControl,
                        'bathing' => $request->bathing,
                        'dressing' => $request->dressing,
                        'personalHygiene' => $request->personalHygiene,
                        'feeding' => $request->feeding,
                        'tot_score' => $request->tot_score,
                        'interpretation' => $request->interpretation,
                        'prediction' => $request->prediction,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
            
            DB::commit();
            
            $responce = new stdClass();
            
            return json_encode($responce);
        
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function edit_barthel(Request $request){
        
        DB::beginTransaction();
        
        try {
        
            $barthel = DB::table('hisdb.ot_barthel')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno)
                            ->where('dateofAssessment','=',$request->dateofAssessment);

            if(!empty($request->idno_barthel)){
                DB::table('hisdb.ot_barthel')                    
                ->where('idno','=',$request->idno_barthel)
                    ->update([
                        'dateofAssessment' => $request->dateofAssessment,
                        // 'timeAssessment' => $request->timeAssessment,
                        'chairBedTrf' => $request->chairBedTrf,
                        'ambulation' => $request->ambulation,
                        'ambulationWheelchair' => $request->ambulationWheelchair,
                        'stairClimbing' => $request->stairClimbing,
                        'toiletTrf' => $request->toiletTrf,
                        'bowelControl' => $request->bowelControl,
                        'bladderControl' => $request->bladderControl,
                        'bathing' => $request->bathing,
                        'dressing' => $request->dressing,
                        'personalHygiene' => $request->personalHygiene,
                        'feeding' => $request->feeding,
                        'tot_score' => $request->tot_score,
                        'interpretation' => $request->interpretation,
                        'prediction' => $request->prediction,
                        'upduser'  => session('username'),
                        'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastcomputerid' => session('computerid'),
                    ]);
            }else{
                if($barthel->exists()){
                    return response('Date already exist.');
                }

                DB::table('hisdb.ot_barthel')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'dateofAssessment' => $request->dateofAssessment,
                        // 'timeAssessment' => $request->timeAssessment,
                        'chairBedTrf' => $request->chairBedTrf,
                        'ambulation' => $request->ambulation,
                        'ambulationWheelchair' => $request->ambulationWheelchair,
                        'stairClimbing' => $request->stairClimbing,
                        'toiletTrf' => $request->toiletTrf,
                        'bowelControl' => $request->bowelControl,
                        'bladderControl' => $request->bladderControl,
                        'bathing' => $request->bathing,
                        'dressing' => $request->dressing,
                        'personalHygiene' => $request->personalHygiene,
                        'feeding' => $request->feeding,
                        'tot_score' => $request->tot_score,
                        'interpretation' => $request->interpretation,
                        'prediction' => $request->prediction,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
            }

            $queries = DB::getQueryLog();
            
            DB::commit();

            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function get_table_barthel(Request $request){
        
        $barthel_obj = DB::table('hisdb.ot_barthel')
                        ->where('compcode','=',session('compcode'))
                        ->where('idno','=',$request->idno);
        
        $responce = new stdClass();
        
        if($barthel_obj->exists()){
            $barthel_obj = $barthel_obj->first();
            $date = Carbon::createFromFormat('Y-m-d', $barthel_obj->dateofAssessment)->format('Y-m-d');

            $responce->barthel = $barthel_obj;
            $responce->date = $date;
        }
        
        return json_encode($responce);
        
    }

    public function barthel_chart(Request $request){
        
        $mrn = $request->mrn;
        $episno = $request->episno;
        $dateofAssessment = $request->dateofAssessment;

        if(!$mrn || !$episno){
            abort(404);
        }
        
        $pat_mast = DB::table('hisdb.pat_mast as pm')
                    ->select('pm.MRN','pm.Name','pm.Newic','b.dateofAssessment')
                    ->leftjoin('hisdb.ot_barthel as b', function ($join){
                        $join = $join->on('b.mrn','=','pm.MRN');
                        // $join = $join->on('b.episno','=','pm.Episno');
                        $join = $join->where('b.compcode','=',session('compcode'));
                    })
                    ->where('pm.CompCode','=',session('compcode'))
                    ->where('pm.MRN','=',$mrn)
                    ->where('pm.Episno','=',$episno)
                    ->where('b.dateofAssessment','=',$dateofAssessment)
                    ->first();
    
        $barthel = DB::table('hisdb.ot_barthel as b')
                ->select('b.mrn','b.episno','b.dateofAssessment','b.chairBedTrf','b.ambulation','b.ambulationWheelchair','b.stairClimbing','b.toiletTrf','b.bowelControl','b.bladderControl','b.bathing','b.dressing','b.personalHygiene','b.feeding','b.tot_score','b.interpretation','b.prediction')
                ->where('b.compcode','=',session('compcode'))
                ->where('b.mrn','=',$mrn)
                ->where('b.episno','=',$episno)
                ->where('b.dateofAssessment','=',$dateofAssessment)
                ->get();
        // dd($barthel);

        $company = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();
        
        return view('rehab.occupTherapy.barthelChart_pdfmake',compact('barthel','pat_mast'));
        
    }
}