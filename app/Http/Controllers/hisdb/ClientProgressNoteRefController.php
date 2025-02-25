<?php

namespace App\Http\Controllers\hisdb;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;
use PDF;

class ClientProgressNoteRefController extends defaultController
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
        return view('hisdb.clientprogressnote.clientprogressnoteref');
    }
    
    public function table(Request $request)
    {
        switch($request->action){
            case 'get_docalloc_clientprognoteref':
                return $this->get_docalloc_clientprognoteref($request);
            
            case 'get_datetime_clientprognoteref':
                return $this->get_datetime_clientprognoteref($request);
            
            case 'get_table_clientprognoteref':
                return $this->get_table_clientprognoteref($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function form(Request $request)
    {
        DB::enableQueryLog();
        
        switch($request->action){
            case 'save_table_clientprognoteref':
                switch($request->oper){
                    case 'add':
                        return $this->add($request);
                    case 'edit':
                        return $this->edit($request);
                    default:
                        return 'error happen..';
                }
            
            case 'get_table_clientprognoteref':
                return $this->get_table_clientprognoteref($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function add(Request $request){
        
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
            
            if($request->epistycode_clientProgNoteRef == 'OP'){
                $plan = $request->plan;
            }else if($request->epistycode_clientProgNoteRef == 'IP'){
                $plan = null;
            }
            
            DB::table('hisdb.patprogressnote')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn_clientProgNoteRef,
                    'episno' => $request->episno_clientProgNoteRef,
                    // 'datetaken' => Carbon::now("Asia/Kuala_Lumpur"),
                    'datetaken' => $request->datetaken,
                    // 'timetaken' => Carbon::now("Asia/Kuala_Lumpur"),
                    'timetaken' => $request->timetaken,
                    'progressnote' => $request->progressnote,
                    'plan' => $plan,
                    // 'doctorcode'  => $doctorcode,
                    'doctorcode'  => $request->refdoctor_clientProgNoteRef,
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
            
            $patprogressnote = DB::table('hisdb.patprogressnote')
                                ->where('mrn','=',$request->mrn_clientProgNoteRef)
                                ->where('episno','=',$request->episno_clientProgNoteRef)
                                ->where('datetaken','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->datetime_clientProgNoteRef)->format('Y-m-d'))
                                ->where('timetaken','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->datetime_clientProgNoteRef)->format('H:i:s'))
                                ->where('compcode','=',session('compcode'))
                                ->where('doctorcode','=',$request->refdoctor_clientProgNoteRef);
            
            $doctorcode_obj = DB::table('hisdb.doctor')
                            ->select('doctorcode')
                            ->where('compcode','=',session('compcode'))
                            ->where('loginid','=',session('username'));
            
            $doctorcode = null;
            if($doctorcode_obj->exists()){
                $doctorcode = $doctorcode_obj->first()->doctorcode;
            }
            
            if($request->epistycode_clientProgNoteRef == 'OP'){
                $plan = $request->plan;
            }else if($request->epistycode_clientProgNoteRef == 'IP'){
                $plan = null;
            }
            
            if($patprogressnote->exists()){
                $patprogressnote
                    ->update([
                        // 'datetaken' => $request->datetaken,
                        'timetaken' => $request->timetaken,
                        'progressnote' => $request->progressnote,
                        'plan' => $plan,
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
                        'mrn' => $request->mrn_clientProgNoteRef,
                        'episno' => $request->episno_clientProgNoteRef,
                        // 'datetaken' => Carbon::now("Asia/Kuala_Lumpur"),
                        'datetaken' => $request->datetaken,
                        // 'timetaken' => Carbon::now("Asia/Kuala_Lumpur"),
                        'timetaken' => $request->timetaken,
                        'progressnote' => $request->progressnote,
                        'plan' => $plan,
                        // 'doctorcode'  => $doctorcode,
                        'doctorcode'  => $request->refdoctor_clientProgNoteRef,
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
            $responce->mrn = $request->mrn_clientProgNoteRef;
            $responce->episno = $request->episno_clientProgNoteRef;
            $responce->datetime = $request->datetime_clientProgNoteRef;
            
            return json_encode($responce);
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function get_docalloc_clientprognoteref(Request $request){
        
        $responce = new stdClass();
        
        $docalloc_obj = DB::table('hisdb.docalloc')
                        ->select('mrn','episno','AllocNo','DoctorCode')
                        ->where('compcode','=',session('compcode'))
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno);
        
        if(!$docalloc_obj->exists()){
            $responce->data = [];
            return json_encode($responce);
        }
        
        $episode_obj = DB::table('hisdb.episode as e')
                        ->select('e.mrn','e.episno','e.admdoctor','a.AllocNo','a.DoctorCode','d.doctorname')
                        ->leftJoin('hisdb.docalloc as a', function ($join) use ($request){
                            $join = $join->on('a.mrn','=','e.mrn');
                            $join = $join->on('a.episno','=','e.episno');
                            $join = $join->on('a.compcode','=','e.compcode');
                            $join = $join->on('a.DoctorCode','!=','e.admdoctor');
                        })->leftJoin('hisdb.doctor as d', function ($join) use ($request){
                            $join = $join->on('d.doctorcode','=','a.DoctorCode');
                            $join = $join->on('d.compcode','=','a.compcode');
                        })
                        ->where('e.compcode','=',session('compcode'))
                        ->where('e.mrn','=',$request->mrn)
                        ->where('e.episno','=',$request->episno)
                        ->orderBy('a.idno','desc');
        
        if($episode_obj->exists()){
            $episode_obj = $episode_obj->get();
            
            $data = [];
            
            foreach($episode_obj as $key => $value){
                $date['mrn'] = $value->mrn;
                $date['episno'] = $value->episno;
                $date['AllocNo'] = $value->AllocNo;
                $date['doctorname'] = $value->doctorname;
                $date['DoctorCode'] = $value->DoctorCode;
                
                array_push($data,$date);
            }
            
            $responce->data = $data;
        }else{
            $responce->data = [];
        }
        
        return json_encode($responce);
        
    }
    
    public function get_datetime_clientprognoteref(Request $request){
        
        $responce = new stdClass();
        
        $patprogressnote_obj = DB::table('hisdb.patprogressnote')
                                ->select('mrn','episno','datetaken','timetaken','adduser')
                                ->where('compcode','=',session('compcode'))
                                ->where('mrn','=',$request->mrn)
                                ->where('episno','=',$request->episno)
                                ->where('doctorcode','=',$request->doctorcode);
        
        if(!$patprogressnote_obj->exists()){
            $responce->data = [];
            return json_encode($responce);
        }
        
        $episode_obj = DB::table('hisdb.episode as e')
                        ->select('e.mrn','e.episno','e.admdoctor','p.datetaken','p.timetaken','p.doctorcode','p.adduser','d.doctorname')
                        ->leftJoin('hisdb.patprogressnote as p', function ($join) use ($request){
                            $join = $join->on('p.mrn','=','e.mrn');
                            $join = $join->on('p.episno','=','e.episno');
                            $join = $join->on('p.compcode','=','e.compcode');
                            $join = $join->where('p.doctorcode','=',$request->doctorcode);
                        })->leftJoin('hisdb.doctor as d', function ($join) use ($request){
                            $join = $join->on('d.doctorcode','=','p.doctorcode');
                            $join = $join->on('d.compcode','=','p.compcode');
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
                    $date['date'] =  Carbon::createFromFormat('Y-m-d', $value->datetaken)->format('d-m-Y').' '.Carbon::createFromFormat('H:i:s', $value->timetaken)->format('h:i A');
                }else{
                    $date['date'] =  '-';
                }
                $date['mrn'] = $value->mrn;
                $date['episno'] = $value->episno;
                if(!empty($value->datetaken)){ // for sorting - easier in 24H
                    $date['recdatetime'] =  Carbon::createFromFormat('Y-m-d', $value->datetaken)->format('d-m-Y').' '.$value->timetaken;
                }else{
                    $date['recdatetime'] =  '-';
                }
                $date['adduser'] = $value->adduser;
                $date['doctorname'] = $value->doctorname;
                $date['doctorcode'] = $value->doctorcode;
                
                array_push($data,$date);
            }
            
            $responce->data = $data;
        }else{
            $responce->data = [];
        }
        
        return json_encode($responce);
        
    }
    
    public function get_table_clientprognoteref(Request $request){
        
        $responce = new stdClass();
        
        // $episode_obj = DB::table('hisdb.episode')
        //                 ->select('diagfinal')
        //                 ->where('compcode','=',session('compcode'))
        //                 ->where('mrn','=',$request->mrn)
        //                 ->where('episno','=',$request->episno);
        
        if(!empty($request->datetime) && $request->datetime != '-'){
            $patprogressnote_obj = DB::table('hisdb.patprogressnote')
                                    ->select('idno','compcode','mrn','episno','datetaken','timetaken','progressnote','plan','doctorcode','adduser','adddate','upduser','upddate','lastuser','lastupdate','computerid')
                                    ->where('compcode','=',session('compcode'))
                                    ->where('mrn','=',$request->mrn)
                                    ->where('episno','=',$request->episno)
                                    ->where('datetaken','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->datetime)->format('Y-m-d'))
                                    ->where('timetaken','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->datetime)->format('H:i:s'))
                                    ->where('doctorcode','=',$request->doctorcode);
        }
        
        // if($episode_obj->exists()){
        //     $episode_obj = $episode_obj->first();
        //     $responce->episode = $episode_obj;
        // }
        
        if(!empty($request->datetime) && $request->datetime != '-'){
            if($patprogressnote_obj->exists()){
                $patprogressnote_obj = $patprogressnote_obj->first();
                $responce->patprogressnote = $patprogressnote_obj;
            }
        }
        
        return json_encode($responce);
        
    }
    
}