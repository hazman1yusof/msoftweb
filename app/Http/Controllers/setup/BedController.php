<?php

namespace App\Http\Controllers\setup;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;

class BedController extends defaultController
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
        return view('setup.bed.bed');
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
        //             ->select('compcode','bednum','bedtype','room','ward','occup','recstatus','idno','tel_ext','statistic','bedchgcode','adduser','adddate','upduser','upddate','lastuser','lastupdate','lastcomputerid','lastipaddress')
        //             ->where('compcode','=',session('compcode'));
        
        $table = $this->defaultGetter($request);

        //////////paginate/////////
        $paginate = $table->paginate($request->rows);

        // foreach ($paginate->items() as $key => $value) {
        //     //pergi ke queue , epistycode='IP or DP', deptcode='ALL'

        //     $episode = DB::table('hisdb.episode as e')
        //                     ->select('e.mrn','e.episno','p.name')
        //                     ->leftJoin('hisdb.pat_mast AS p', function($join) use ($request){
        //                         $join = $join->on("e.mrn", '=', 'p.mrn');    
        //                         $join = $join->on('e.compcode','=','p.compcode');
        //                     })
        //                     ->where('e.compcode','=',session('compcode'))
        //                     ->where('e.bed','=',$value->bednum)
        //                     ->where('e.episactive','=','1')
        //                     ->orderBy('e.idno','DESC');

        //     if($episode->exists()){
        //         $episode_first = $episode->first();
        //         $value->mrn = $episode_first->mrn;
        //         $value->episno = $episode_first->episno;
        //         $value->name = $episode_first->name;
        //     }else{
        //         $value->mrn = '';
        //         $value->episno = '';
        //         $value->name = '';
        //     }
        // }

        $responce = new stdClass();
        $responce->page = $paginate->currentPage();
        $responce->total = $paginate->lastPage();
        $responce->records = $paginate->total();
        $responce->rows = $paginate->items();
        $responce->sql = $table->toSql();
        $responce->sql_bind = $table->getBindings();

        return json_encode($responce);
    }

    public function truefalse($param){
        if($param == "TRUE"){ return 1;}else{return 0;}
    }   

    public function add(Request $request){
        
        DB::beginTransaction();
        try {

            $bednum = DB::table('hisdb.bed')
                            ->where('compcode','=',session('compcode'))
                            ->where('bednum','=',$request->bednum);

            if($bednum->exists()){
                throw new \Exception("RECORD DUPLICATE");
            }

            DB::table('hisdb.bed')
                ->insert([  
                    'compcode' => session('compcode'),
                    'bednum' => strtoupper($request->bednum),
                    'bedtype' => strtoupper($request->bedtype),  
                    'room' => strtoupper($request->room),  
                    'ward' => strtoupper($request->ward),  
                    // 'occup' => 0,
                    'occup' => strtoupper($request->occup),
                    'tel_ext' => strtoupper($request->tel_ext),
                    // 'tel_ext' => $this->truefalse($request->tel_ext),
                    'bedchgcode' => strtoupper($request->bedchgcode),
                    'statistic' => strtoupper($request->statistic), 
                    'recstatus' => 'ACTIVE',
                    'lastcomputerid' => strtoupper($request->lastcomputerid),
                    'lastipaddress' => strtoupper($request->lastipaddress),
                    'adduser' => strtoupper(session('username')),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur")
                ]);

             DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            $responce = new stdClass();
            $responce->errormsg = $e->getMessage();
            $responce->request = $_REQUEST;

            return response(json_encode($responce), 500);
        }
    }

    public function edit(Request $request){
        
        DB::beginTransaction();
        try {

            DB::table('hisdb.bed')
                ->where('idno','=',$request->idno)
                ->update([  
                    'bedtype' => strtoupper($request->bedtype),  
                    'room' => strtoupper($request->room),  
                    'ward' => strtoupper($request->ward),
                    'occup' => strtoupper($request->occup),
                    'tel_ext' => strtoupper($request->tel_ext),  
                    'bedchgcode' => strtoupper($request->bedchgcode), 
                    'statistic' => $request->statistic,    
                    // 'recstatus' => strtoupper($request->recstatus),
                    'lastcomputerid' => strtoupper($request->lastcomputerid),
                    'lastipaddress' => strtoupper($request->lastipaddress),
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
                ->where('idno','=',$request->idno)
                ->update([  
                    'recstatus' => 'DEACTIVE',
                    'deluser' => strtoupper(session('username')),
                    'deldate' => Carbon::now("Asia/Kuala_Lumpur")
                ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
        
    }
}