<?php

namespace App\Http\Controllers\setup;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;

class BedManagementController extends defaultController
{   

    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
        $this->duplicateCode = "bednum";
    }

    public function show(Request $request)
    {   
        return view('setup.bedmanagement.bedmanagement');
    }

    public function form(Request $request)
    {  
        switch($request->oper){
            case 'add':
                return $this->add($request);
            case 'edit':
                return $this->edit($request);
            case 'del':
                return $this->del($request);
            case 'transfer_form':
                return $this->transfer_form($request);
            default:
                return 'error happen..';
        }
    }

    public function table(Request $request)
    {  
        switch($request->action){
            case 'get_table':
                return $this->get_table($request);
            
            default:
                return 'error happen..';
        }
    }

    public function get_table(Request $request){
        // $table = DB::table('hisdb.bed')
        //             ->select('compcode','bednum','bedtype','room','ward','occup','recstatus','idno','tel_ext','statistic','adduser','adddate','upduser','upddate','lastuser','lastupdate','lastcomputerid','lastipaddress')
        //             ->where('compcode','=',session('compcode'));
        
        $table = $this->defaultGetter($request);

        //////////paginate/////////
        $paginate = $table->paginate($request->rows);

        foreach ($paginate->items() as $key => $value) {
            $episode = DB::table('hisdb.episode as e')
                            ->select('e.mrn','e.episno','p.name')
                            ->leftJoin('hisdb.pat_mast AS p', function($join) use ($request){
                                $join = $join->on("e.mrn", '=', 'p.mrn');    
                                $join = $join->on('e.compcode','=','p.compcode');
                            })
                            ->where('e.compcode','=',session('compcode'))
                            ->where('e.bed','=',$value->b_bednum)
                            ->where('e.episactive','=','1')
                            ->orderBy('e.idno','DESC');

            if($episode->exists()){
                $episode_first = $episode->first();
                $value->mrn = $episode_first->q_mrn;
                $value->episno = $episode_first->q_episno;
                $value->name = $episode_first->q_name;
            }else{
                $value->q_mrn = '';
                $value->q_episno = '';
                $value->q_name = '';
            }
        }

        $responce = new stdClass();
        $responce->page = $paginate->currentPage();
        $responce->total = $paginate->lastPage();
        $responce->records = $paginate->total();
        $responce->rows = $paginate->items();
        $responce->sql = $episode->toSql();
        $responce->sql_bind = $episode->getBindings();

        return json_encode($responce);
    }

    public function truefalse($param){
        if($param == "TRUE"){ return 1;}else{return 0;}
    }   

    public function add(Request $request){

        DB::beginTransaction();
        try {

            $bednum = DB::table('hisdb.bed')
                            ->where('bednum','=',$request->b_bednum);

            if($bednum->exists()){
                throw new \Exception("RECORD DUPLICATE");
            }

            DB::table('hisdb.bed')
                ->insert([  
                    'compcode' => session('compcode'),
                    'bednum' => strtoupper($request->b_bednum),
                    'bedtype' => strtoupper($request->b_bedtype),  
                    'room' => strtoupper($request->b_room),  
                    'ward' => strtoupper($request->b_ward),
                    'tel_ext' => strtoupper($request->b_tel_ext),
                    // 'statistic' => strtoupper($request->statistic), 
                    //'occup' => 0,  
                    'occup' => strtoupper($request->b_occup),
                    'Doctor Code' => strtoupper($request->q_admdoctor),
                    // 'tel_ext' => $this->truefalse($request->tel_ext), 
                    'statistic' => $this->truefalse($request->b_statistic),
                    'recstatus' => strtoupper($request->b_recstatus),
                    'lastcomputerid' => strtoupper($request->b_lastcomputerid),
                    'lastipaddress' => strtoupper($request->b_lastipaddress),
                    'adduser' => strtoupper(session('username')),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur")
                ]);

             DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function statistic(Request $request){

        $table = DB::table('hisdb.bed')
                    ->select('compcode','bednum','occup','room','statistic')
                    ->where('compcode','=',session('compcode'))
                    ->where('statistic','=','1')
                    ->get();

        $vacant=0;
        $occupied=0;
        $housekeeping=0;
        $maintenance=0;
        $isolated=0;

        foreach ($table as $key => $value) {
            switch ($value->occup) {
                case 'OCCUPIED':
                    $occupied = $occupied + 1;
                    break;
                case 'HOUSEKEEPING':
                    $housekeeping = $housekeeping + 1;
                    break;
                case 'MAINTENANCE':
                    $maintenance = $maintenance + 1;
                    break;
                case 'ISOLATED':
                    $isolated = $isolated + 1;
                    break;
                default :
                    $vacant = $vacant + 1;
                    break;
            }
        }

        
        $responce = new stdClass();
        $responce->vacant = $vacant;
        $responce->occupied = $occupied;
        $responce->housekeeping = $housekeeping;
        $responce->maintenance = $maintenance;
        $responce->isolated = $isolated;

        return json_encode($responce);
    }

    public function edit(Request $request){
        
        DB::beginTransaction();
        try {

            DB::table('hisdb.bed')
                ->where('idno','=',$request->b_idno)
                ->update([  
                    'bedtype' => strtoupper($request->b_bedtype),  
                    'room' => strtoupper($request->b_room),  
                    'ward' => strtoupper($request->b_ward),
                    'occup' => strtoupper($request->b_occup),
                    'tel_ext' => strtoupper($request->b_tel_ext),
                    'Doctor Code' => strtoupper($request->q_admdoctor),
                    // 'tel_ext' => $this->truefalse($request->tel_ext), 
                    'statistic' => $this->truefalse($request->b_statistic),    
                    'recstatus' => strtoupper($request->b_recstatus),
                    'lastcomputerid' => strtoupper($request->b_lastcomputerid),
                    'lastipaddress' => strtoupper($request->b_lastipaddress),
                    'upduser' => strtoupper(session('username')),
                    'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                ]); 

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function del(Request $request){
        DB::beginTransaction();
        try {

            DB::table('hisdb.bed')
                ->where('idno','=',$request->b_idno)
                ->update([  
                    'recstatus' => 'D',
                    'deluser' => strtoupper(session('username')),
                    'deldate' => Carbon::now("Asia/Kuala_Lumpur")
                ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
        
    }

    public function transfer_form(Request $request){
        DB::beginTransaction();
        try {
            //1. new bed alloc
            DB::table('hisdb.bedalloc')
                ->insert([  
                    'mrn' => $request->mrn,
                    'episno' => $request->episno,
                    'name' => $request->name,
                    'astatus' => $request->trf_astatus,
                    'ward' =>  $request->trf_ward,
                    'room' =>  $request->trf_room,
                    'bednum' =>  $request->trf_bednum,
                    'asdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'astime' => Carbon::now("Asia/Kuala_Lumpur"),
                    'compcode' => session('compcode'),
                    'adduser' => strtoupper(session('username')),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur")
                ]);

            //2. edit episode
            DB::table('hisdb.episode')
                ->where('mrn','=',$request->b_mrn)
                ->where('episno','=',$request->b_episno)
                ->update([
                    'bed' =>  $request->trf_bednum,
                    'bedtype' => $request->trf_bedtype,
                    'ward' =>  $request->trf_ward,
                    'room' =>  $request->trf_room,
                ]);

            //3. edit queue
            DB::table('hisdb.episode')
                ->where('mrn','=',$request->mrn)
                ->where('episno','=',$request->episno)
                ->update([
                    'bed' =>  $request->trf_bednum,
                    'bedtype' => $request->trf_bedtype,
                    'ward' =>  $request->trf_ward,
                    'room' =>  $request->trf_room,
                ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
        
    }
}