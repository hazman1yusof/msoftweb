<?php

namespace App\Http\Controllers\hisdb;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;
use PDF;

class ClientProgressNoteController extends defaultController
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
        return view('hisdb.clientprogressnote.clientprogressnote');
    }
    
    public function table(Request $request)
    {
        switch($request->action){
            case 'get_datetime_clientprognote':
                return $this->get_datetime_clientprognote($request);
            
            case 'get_table_clientprognote':
                return $this->get_table_clientprognote($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function form(Request $request)
    {
        DB::enableQueryLog();
        
        switch($request->action){
            case 'save_table_clientprognote':
                switch($request->oper){
                    case 'add':
                        return $this->add($request);
                    case 'edit':
                        return $this->edit($request);
                    default:
                        return 'error happen..';
                }
            
            case 'get_table_clientprognote':
                return $this->get_table_clientprognote($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function add(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            // $doctorcode_obj = DB::table('hisdb.doctor')
            //                 ->select('doctorcode')
            //                 ->where('compcode','=',session('compcode'))
            //                 ->where('loginid','=',session('username'));
            
            // get doctorname from episode.admdoctor
            $doctorcode_obj = DB::table('hisdb.episode')
                            ->select('admdoctor')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn_clientProgNote)
                            ->where('episno','=',$request->episno_clientProgNote);
            
            $doctorcode = null;
            if($doctorcode_obj->exists()){
                // $doctorcode = $doctorcode_obj->first()->doctorcode;
                $doctorcode = $doctorcode_obj->first()->admdoctor;
            }
            
            if($request->epistycode_clientProgNote == 'OP'){
                $plan = $request->plan;
            }else if($request->epistycode_clientProgNote == 'IP'){
                $plan = null;
            }
            
            DB::table('hisdb.patprogressnote')
                ->insert([
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn_clientProgNote,
                    'episno' => $request->episno_clientProgNote,
                    // 'datetaken' => Carbon::now("Asia/Kuala_Lumpur"),
                    'datetaken' => $request->datetaken,
                    // 'timetaken' => Carbon::now("Asia/Kuala_Lumpur"),
                    'timetaken' => $request->timetaken,
                    'progressnote' => $request->progressnote,
                    'plan' => $plan,
                    // 'doctorcode'  => $doctorcode,
                    'doctorcode'  => session('username'),
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
                                ->where('mrn','=',$request->mrn_clientProgNote)
                                ->where('episno','=',$request->episno_clientProgNote)
                                ->where('datetaken','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->datetime_clientProgNote)->format('Y-m-d'))
                                ->where('timetaken','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->datetime_clientProgNote)->format('H:i:s'))
                                ->where('compcode','=',session('compcode'));
            
            // $doctorcode_obj = DB::table('hisdb.doctor')
            //                 ->select('doctorcode')
            //                 ->where('compcode','=',session('compcode'))
            //                 ->where('loginid','=',session('username'));
            
            // get doctorname from episode.admdoctor
            $doctorcode_obj = DB::table('hisdb.episode')
                            ->select('admdoctor')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn_clientProgNote)
                            ->where('episno','=',$request->episno_clientProgNote);
            
            $doctorcode = null;
            if($doctorcode_obj->exists()){
                // $doctorcode = $doctorcode_obj->first()->doctorcode;
                $doctorcode = $doctorcode_obj->first()->admdoctor;
            }
            
            if($request->epistycode_clientProgNote == 'OP'){
                $plan = $request->plan;
            }else if($request->epistycode_clientProgNote == 'IP'){
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
                        'mrn' => $request->mrn_clientProgNote,
                        'episno' => $request->episno_clientProgNote,
                        // 'datetaken' => Carbon::now("Asia/Kuala_Lumpur"),
                        'datetaken' => $request->datetaken,
                        // 'timetaken' => Carbon::now("Asia/Kuala_Lumpur"),
                        'timetaken' => $request->timetaken,
                        'progressnote' => $request->progressnote,
                        'plan' => $plan,
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
            $responce->mrn = $request->mrn_clientProgNote;
            $responce->episno = $request->episno_clientProgNote;
            $responce->datetime = $request->datetime_clientProgNote;
            
            return json_encode($responce);
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error DB rollback!'.$e, 500);
            
        }
        
    }
    
    public function get_datetime_clientprognote(Request $request){
        
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
                        ->select('e.mrn','e.episno','e.admdoctor','p.datetaken','p.timetaken','p.doctorcode','p.adduser','d.doctorname as docname','doc.doctorname')
                        ->leftJoin('hisdb.patprogressnote as p', function ($join) use ($request){
                            $join = $join->on('p.mrn','=','e.mrn');
                            $join = $join->on('p.episno','=','e.episno');
                            $join = $join->on('p.compcode','=','e.compcode');
                        })->leftJoin('hisdb.doctor as d', function ($join) use ($request){
                            $join = $join->on('d.doctorcode','=','e.admdoctor');
                            $join = $join->on('d.compcode','=','e.compcode');
                        })->leftJoin('hisdb.doctor as doc', function ($join) use ($request){
                            $join = $join->on('doc.doctorcode','=','p.doctorcode');
                            $join = $join->on('doc.compcode','=','p.compcode');
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
                
                array_push($data,$date);
            }
            
            $responce->data = $data;
        }else{
            $responce->data = [];
        }
        
        return json_encode($responce);
        
    }
    
    public function get_table_clientprognote(Request $request){
        
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
                                    ->where('timetaken','=',Carbon::createFromFormat('d-m-Y H:i:s', $request->datetime)->format('H:i:s'));
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