<?php

namespace App\Http\Controllers\appointment;

use Illuminate\Http\Request;
use stdClass;
use App\User;
use DB;
use Carbon\Carbon;
use Auth;
use Session;
use App\Http\Controllers\defaultController;

class DoctorNoteApptController extends defaultController
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function table(Request $request)
    {
        switch($request->action){
            case 'get_table_date_curr': // for current
                return $this->get_table_date_curr($request);
            
            case 'get_table_date_past': // for past history
                return $this->get_table_date_past($request);
            
            case 'get_table_doctorNoteAppt':
                return $this->get_table_doctorNoteAppt($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function form(Request $request)
    {
        switch($request->action){
            case 'save_table_doctorNoteAppt':
                switch($request->oper){
                    case 'add':
                        return $this->add($request);
                    case 'edit':
                        return $this->edit($request);
                    default:
                        return 'error happen..';
                }
            
            case 'doctorNoteAppt_save':
                return $this->addNotes_docNoteAppt($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function index(Request $request){
        return view('appointment.doctorNoteAppt');
    }
    
    public function add(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            // DB::table('hisdb.episode')
            //     ->where('mrn','=',$request->mrn_doctorNoteAppt)
            //     ->where('episno','=',$request->episno_doctorNoteAppt)
            //     ->where('compcode','=',session('compcode'))
            //     ->update([
            //         'diagfinal' => $request->diagfinal,
            //         'lastuser'  => session('username'),
            //         'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
            //     ]);
            
            $doctorcode_obj = DB::table('hisdb.doctor')
                            ->select('doctorcode')
                            ->where('compcode','=',session('compcode'))
                            ->where('loginid','=',session('username'));
            
            $doctorcode = null;
            if($doctorcode_obj->exists()){
                $doctorcode = $doctorcode_obj->first()->doctorcode;
            }
            
            DB::table('hisdb.patprogressnote')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn_doctorNoteAppt,
                    'episno' => $request->episno_doctorNoteAppt,
                    'datetaken' => Carbon::now("Asia/Kuala_Lumpur"),
                    // 'timetaken' => Carbon::now("Asia/Kuala_Lumpur"),
                    'timetaken' => $request->timetaken,
                    'progressnote' => $request->progressnote,
                    'doctorcode'  => $doctorcode,
                    'adduser'  => session('username'),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'computerid' => session('computerid'),
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
            
            // DB::table('hisdb.episode')
            //     ->where('mrn','=',$request->mrn_doctorNoteAppt)
            //     ->where('episno','=',$request->episno_doctorNoteAppt)
            //     ->where('compcode','=',session('compcode'))
            //     ->update([
            //         'diagfinal' => $request->diagfinal,
            //         'lastuser'  => session('username'),
            //         'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
            //     ]);
            
            $patprogressnote = DB::table('hisdb.patprogressnote')
                                ->where('mrn','=',$request->mrn_doctorNoteAppt)
                                ->where('episno','=',$request->episno_doctorNoteAppt)
                                ->where('datetaken','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate_doctorNoteAppt)->format('Y-m-d'))
                                ->where('timetaken','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->recorddate_doctorNoteAppt)->format('H:i:s'))
                                ->where('compcode','=',session('compcode'));
            
            $doctorcode_obj = DB::table('hisdb.doctor')
                            ->select('doctorcode')
                            ->where('compcode','=',session('compcode'))
                            ->where('loginid','=',session('username'));
            
            $doctorcode = null;
            if($doctorcode_obj->exists()){
                $doctorcode = $doctorcode_obj->first()->doctorcode;
            }
            
            if($patprogressnote->exists()){
                $patprogressnote
                    ->update([
                        'timetaken' => $request->timetaken,
                        'progressnote' => $request->progressnote,
                        // 'doctorcode'  => $doctorcode,
                        'upduser'  => session('username'),
                        'upddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
            }else{
                DB::table('hisdb.patprogressnote')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn_doctorNoteAppt,
                        'episno' => $request->episno_doctorNoteAppt,
                        'datetaken' => Carbon::now("Asia/Kuala_Lumpur"),
                        // 'timetaken' => Carbon::now("Asia/Kuala_Lumpur"),
                        'timetaken' => $request->timetaken,
                        'progressnote' => $request->progressnote,
                        'doctorcode'  => $doctorcode,
                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'computerid' => session('computerid'),
                    ]);
            }
            
            $queries = DB::getQueryLog();
            // dump($queries);
            
            DB::commit();
            
            $responce = new stdClass();
            $responce->mrn = $request->mrn_doctorNoteAppt;
            $responce->episno = $request->episno_doctorNoteAppt;
            $responce->recorddate = $request->recorddate_doctorNoteAppt;
            
            return json_encode($responce);
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function addNotes_docNoteAppt(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $doctorcode_obj = DB::table('hisdb.doctor')
                            ->select('doctorcode')
                            ->where('compcode','=',session('compcode'))
                            ->where('loginid','=',session('username'));
            
            $doctorcode = null;
            if($doctorcode_obj->exists()){
                $doctorcode = $doctorcode_obj->first()->doctorcode;
            }
            
            // DB::table('hisdb.pathealthadd')
            //     ->insert([
            //         'compcode' => session('compcode'),
            //         'mrn' => $request->mrn,
            //         'episno' => $request->episno,
            //         'additionalnote' => $request->additionalnote,
            //         'doctorcode'  => $doctorcode,
            //         'adduser'  => session('username'),
            //         'adddate'  => Carbon::now("Asia/Kuala_Lumpur")
            //     ]);
            
            DB::table('nursing.nursaddnote')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn,
                    'episno' => $request->episno,
                    'type' => 'DOCTOR NOTE APPT',
                    'note' => $request->note,
                    'adduser'  => $doctorcode,
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur"),
                    'lastuser'  => $doctorcode,
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'computerid' => session('computerid'),
                ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
        
    }
    
    public function get_table_date_curr(Request $request){
        
        $responce = new stdClass();
        
        $patprogressnote_obj = DB::table('hisdb.patprogressnote')
                                ->select('mrn','episno','datetaken','timetaken','adduser')
                                ->where('compcode','=',session('compcode'))
                                ->where('mrn','=',$request->mrn)
                                ->where('episno','=',$request->episno);
        
        if(!$patprogressnote_obj->exists()){
            $responce->data = [];
            return json_encode($responce);
        }
        
        $episode_obj = DB::table('hisdb.episode as e')
                    ->select('e.mrn','e.episno','e.admdoctor','p.datetaken','p.timetaken','p.adduser','d.doctorname')
                    ->leftJoin('hisdb.patprogressnote as p', function ($join) use ($request){
                        $join = $join->on('p.mrn', '=', 'e.mrn');
                        $join = $join->on('p.episno', '=', 'e.episno');
                        $join = $join->on('p.compcode', '=', 'e.compcode');
                    })->leftJoin('hisdb.doctor as d', function ($join) use ($request){
                        $join = $join->on('d.doctorcode', '=', 'e.admdoctor');
                        $join = $join->on('d.compcode', '=', 'e.compcode');
                    })
                    ->where('e.compcode','=',session('compcode'))
                    ->where('e.mrn','=',$request->mrn)
                    ->where('e.episno','=',$request->episno)
                    ->orderBy('p.idno','desc');
        
        if($episode_obj->exists()){
            $episode_obj = $episode_obj->get();
            
            $data = [];
            
            foreach($episode_obj as $key => $value){
                if(!empty($value->datetaken)){
                    $date['date'] =  Carbon::createFromFormat('Y-m-d', $value->datetaken)->format('d-m-Y').' '.$value->timetaken;
                }else{
                    $date['date'] =  '-';
                }
                $date['mrn'] = $value->mrn;
                $date['episno'] = $value->episno;
                $date['adduser'] = $value->adduser;
                $date['doctorname'] = $value->doctorname;
                $date['datetaken'] = $value->datetaken;
                $date['timetaken'] = $value->timetaken;
                
                array_push($data,$date);
            }
            
            $responce->data = $data;
        }else{
            $responce->data = [];
        }
        
        return json_encode($responce);
        
    }
    
    public function get_table_date_past(Request $request){
        
        $responce = new stdClass();
        
        $episode_obj = DB::table('hisdb.episode as e')
                        ->select('e.mrn','e.episno','e.admdoctor','p.datetaken','p.timetaken','p.adduser','p.adddate','d.doctorname')
                        ->join('hisdb.patprogressnote as p', function ($join) use ($request){
                            $join = $join->on('p.mrn', '=', 'e.mrn');
                            $join = $join->on('p.episno', '=', 'e.episno');
                            $join = $join->on('p.compcode', '=', 'e.compcode');
                            $join = $join->where('p.datetaken', '!=', Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d'));
                        })->leftJoin('hisdb.doctor as d', function ($join) use ($request){
                            $join = $join->on('d.doctorcode', '=', 'e.admdoctor');
                            $join = $join->on('d.compcode', '=', 'e.compcode');
                        })
                        ->where('e.compcode','=',session('compcode'))
                        ->where('e.mrn','=',$request->mrn)
                        ->orderBy('p.datetaken','desc');
        
        if($episode_obj->exists()){
            $episode_obj = $episode_obj->get();
            
            $data = [];
            
            foreach($episode_obj as $key => $value){
                if(!empty($value->datetaken)){
                    $date['date'] =  Carbon::createFromFormat('Y-m-d', $value->datetaken)->format('d-m-Y').' '.$value->timetaken;
                }else{
                    $date['date'] =  '-';
                }
                $date['mrn'] = $value->mrn;
                $date['episno'] = $value->episno;
                if(!empty($value->adduser)){
                    $date['adduser'] = $value->adduser;
                }else{
                    $date['adduser'] =  '-';
                }
                $date['adduser'] = $value->adduser;
                $date['doctorname'] = $value->doctorname;
                $date['adddate'] = $value->adddate;
                $date['datetaken'] = $value->datetaken;
                $date['timetaken'] = $value->timetaken;
                
                array_push($data,$date);
            }
            
            $responce->data = $data;
        }else{
            $responce->data = [];
        }
        
        return json_encode($responce);
        
    }
    
    public function get_table_doctorNoteAppt(Request $request){
        
        $responce = new stdClass();
        
        $episode_obj = DB::table('hisdb.episode')
                        ->select('diagfinal')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno);
        
        $patprogressnote_obj = DB::table('hisdb.patprogressnote')
                        ->select('idno','compcode','mrn','episno','datetaken','timetaken','progressnote','plan','doctorcode','adduser','adddate','upduser','upddate','lastuser','lastupdate','computerid')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno)
                        ->where('timetaken','=',$request->timetaken);
        
        if($episode_obj->exists()){
            $episode_obj = $episode_obj->first();
            $responce->episode = $episode_obj;
        }
        
        if($patprogressnote_obj->exists()){
            $patprogressnote_obj = $patprogressnote_obj->first();
            $responce->patprogressnote = $patprogressnote_obj;
        }
        
        return json_encode($responce);
        
    }
    
}