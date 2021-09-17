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
            case 'ordercom_form':
                return $this->ordercom_form($request);
            case 'add_ordcom':
                return $this->add_ordcom($request);
            case 'del_ordcom':
                return $this->del_ordcom($request);
            case 'edit_all_ordcom':
                return $this->edit_all_ordcom($request);                
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
        $table = DB::table('hisdb.bed')
                    ->select('compcode','bednum','bedtype','room','ward','occup','recstatus','idno','tel_ext','statistic','adduser','adddate','upduser','upddate','lastuser','lastupdate','lastcomputerid','lastipaddress')
                    ->where('compcode','=',session('compcode'));
        
        // $table = $this->defaultGetter($request);

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
                            ->where('e.bed','=',$value->bednum)
                            ->where('e.episactive','=','1')
                            ->orderBy('e.idno','DESC');

            if($episode->exists()){
                $episode_first = $episode->first();
                $value->mrn = $episode_first->mrn;
                $value->episno = $episode_first->episno;
                $value->name = $episode_first->name;
            }else{
                $value->mrn = '';
                $value->episno = '';
                $value->name = '';
            }
        }

        foreach ($paginate->items() as $key => $value) {
            $pat_mast_obj = DB::table('hisdb.pat_mast AS p')
                        ->select(['p.Sex','p.DOB','racecode.Description AS raceDesc','religion.Description AS religionDesc','occupation.description AS occupDesc','citizen.Description AS citizenDesc','areacode.Description AS areaDesc'])
                        ->leftJoin('hisdb.racecode', function($join) use ($request){
                            $join = $join->on('racecode.Code','=','p.RaceCode');
                            $join = $join->on('racecode.compcode','=','p.CompCode');
                        })
                        ->leftJoin('hisdb.religion', function($join) use ($request){
                            $join = $join->on('religion.Code','=','p.Religion');
                            $join = $join->on('religion.CompCode','=','p.CompCode');
                        })
                        ->leftJoin('hisdb.occupation', function($join) use ($request){
                            $join = $join->on('occupation.occupcode','=','p.OccupCode');
                            $join = $join->on('occupation.compcode','=','p.CompCode');
                        })
                        ->leftJoin('hisdb.citizen', function($join) use ($request){
                            $join = $join->on('citizen.Code','=','p.Citizencode');
                            $join = $join->on('citizen.compcode','=','p.CompCode');
                        })
                        ->leftJoin('hisdb.areacode', function($join) use ($request){
                            $join = $join->on('areacode.areacode','=','p.AreaCode');
                            $join = $join->on('areacode.compcode','=','p.CompCode');
                        })
                        ->where('p.MRN','=',$value->mrn)
                        ->where('p.CompCode','=',session('compcode'));

            if($pat_mast_obj->exists()){
                $pat_mast_obj_first = $pat_mast_obj->first();
                $value->sex = strtoupper($pat_mast_obj_first->Sex);
                $value->dob = $pat_mast_obj_first->DOB;
                $value->age = Carbon::parse($pat_mast_obj_first->DOB)->age;
                $value->race = strtoupper($pat_mast_obj_first->raceDesc);
                $value->religion = strtoupper($pat_mast_obj_first->religionDesc);
                $value->occupation = strtoupper($pat_mast_obj_first->occupDesc);
                $value->citizen = strtoupper($pat_mast_obj_first->citizenDesc);
                $value->area = strtoupper($pat_mast_obj_first->areaDesc);
            }else{
                $value->sex = '';
                $value->dob = '';
                $value->age = '';
                $value->race = '';
                $value->religion = '';
                $value->occupation = '';
                $value->citizen = '';
                $value->area = '';
            }       
        }

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
                            ->where('bednum','=',$request->b_bednum);

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
                    'tel_ext' => strtoupper($request->tel_ext),
                    'statistic' => strtoupper($request->statistic),                
                    //'occup' => 0,  
                    'occup' => strtoupper($request->occup),
                    'name' => strtoupper($request->name),
                    'admdoctor' => strtoupper($request->admdoctor),
                    // 'tel_ext' => $this->truefalse($request->tel_ext), 
                    // 'statistic' => $this->truefalse($request->b_statistic),
                    'recstatus' => strtoupper($request->recstatus),
                    'lastcomputerid' => strtoupper($request->lastcomputerid),
                    'lastipaddress' => strtoupper($request->lastipaddress),
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
                    ->select('compcode','bednum','occup','room','statistic','recstatus')
                    ->where('compcode','=',session('compcode'))
                    ->where('statistic','=','1')
                    ->get();

        $vacant=0;
        $occupied=0;
        $housekeeping=0;
        $maintenance=0;
        $isolated=0;
        $active=0;
        $deactive=0;
        $reserve=0;
        $totalbed=0;

        foreach ($table as $key => $value) {
            $totalbed = $totalbed +1;
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
                case 'RESERVE':
                    $reserve = $reserve + 1;
                    break;
                default :
                    break;
            }
            switch ($value->recstatus) {
                case 'ACTIVE':
                    $active = $active + 1;
                    break;
                case 'DEACTIVE':
                    $deactive = $deactive + 1;
                    break;
            }
        }

        $vacant = $totalbed - $deactive - $maintenance - $occupied - $housekeeping - $isolated ;

        
        $responce = new stdClass();
        $responce->vacant = $vacant;
        $responce->occupied = $occupied;
        $responce->housekeeping = $housekeeping;
        $responce->maintenance = $maintenance;
        $responce->isolated = $isolated;
        $responce->active = $active;
        $responce->deactive = $deactive;
        $responce->reserve = $reserve;
        $responce->totalbed = $totalbed;


        return json_encode($responce);
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
                    'name' => strtoupper($request->name),
                    'tel_ext' => strtoupper($request->tel_ext),
                    'admdoctor' => strtoupper($request->admdoctor),
                    'tel_ext' => $request->tel_ext, 
                    'statistic' => $request->statistic,    
                    'recstatus' => strtoupper($request->recstatus),
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

            //2 edit bed
            DB::table('hisdb.bed') //curent bed
                ->where('compcode','=',session('compcode'))
                ->where('bednum','=',$request->ba_bednum)
                ->update([  
                    'occup' => 'VACANT',
                    'mrn' => NULL,
                    'episno' => NULL,
                    'name' => NULL,
                    'admdoctor' => NULL
                ]);

            DB::table('hisdb.bed') //trf bed
                ->where('compcode','=',session('compcode'))
                ->where('bednum','=',$request->trf_bednum)
                ->update([  
                    'occup' => 'OCCUPIED',
                    'mrn' => $request->mrn,
                    'episno' => $request->episno,
                    'name' => $request->name,
                    'admdoctor' => $request->admdoctor
                ]);

            //3. edit episode
            DB::table('hisdb.episode')
                ->where('mrn','=',$request->mrn)
                ->where('episno','=',$request->episno)
                ->update([
                    'bed' =>  $request->trf_bednum,
                    'bedtype' => $request->trf_bedtype,
                    'ward' =>  $request->trf_ward,
                    'room' =>  $request->trf_room,
                ]);

            //4. edit queue
            DB::table('hisdb.queue')
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

    public function ordercom_form(Request $request){
        DB::beginTransaction();
        try {
            //1. new ordercomm
            DB::table('hisdb.chargetrx')
                ->insert([  
                    'auditno' => $request->auditno,
                    'quantity' => $request->quantity,
                    'isudept' => $request->isudept,
                    'remarks' => $request->remarks,
                    'chgcode' =>  $request->chgcode,
                    'chgtype' =>  $request->chgtype,
                    'trxdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'trxtime' => Carbon::now("Asia/Kuala_Lumpur"),
                    'compcode' => session('compcode'),
                    // 'adduser' => strtoupper(session('username')),
                    // 'adddate' => Carbon::now("Asia/Kuala_Lumpur")
                ]);

            //2 edit ordercomm
            DB::table('hisdb.chargetrx') //curent bed
                ->where('compcode','=',session('compcode'))
                ->where('chgcode','=',$request->ct_chgcode)
                ->insert([  
                    'auditno' => $request->auditno,
                    'quantity' => $request->quantity,
                    'isudept' => $request->isudept,
                    'remarks' => $request->remarks,
                    'chgcode' =>  $request->chgcode,
                    'chgtype' =>  $request->chgtype,
                    'trxdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'trxtime' => Carbon::now("Asia/Kuala_Lumpur"),
                    'compcode' => session('compcode'),
                ]);

            //3. edit ordercomm
            DB::table('hisdb.chargetrx')
                ->where('compcode','=',$request->compcode)
                ->where('auditno','=',$request->auditno)
                ->insert([
                    'auditno' => $request->auditno,
                    'quantity' => $request->quantity,
                    'isudept' => $request->isudept,
                    'remarks' => $request->remarks,
                    'chgcode' =>  $request->chgcode,
                    'chgtype' =>  $request->chgtype,
                    'trxdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'trxtime' => Carbon::now("Asia/Kuala_Lumpur"),
                    'compcode' => session('compcode'),
                ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
        
    }

    public function add_ordcom(Request $request){

        DB::beginTransaction();
        try {

            $auditno = DB::table('hisdb.chargetrx')
                            ->where('auditno','=',$request->$ct_auditno);

            if($auditno->exists()){
                throw new \Exception("RECORD DUPLICATE");
            }

            DB::table('hisdb.chargetrx')
                ->insert([  
                    'compcode' => session('compcode'),
                    'trxtype' => $request->trxtype,
                    'auditno' => $request->ct_auditno,
                    'quantity' => $request->ct_quantity,
                    'isudept' => $request->ct_isudept,
                    'remarks' => $request->remct_remarksarks,
                    'billcode' => $request->billcode,
                    'doctorcode' => $request->doctorcode,
                    'chg_class' => $request->chg_class,                    
                    'chgcode' =>  $request->ct_chgcode,
                    'chgtype' =>  $request->cm_chgtype,
                    'chggroup' => $request->chggroup,
                    'dracccode' => $request->dracccode,
                    'cracccode' => $request->cracccode,
                    'trxdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'trxtime' => Carbon::now("Asia/Kuala_Lumpur"),

                    'adduser'  => session('username'),
                    'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'lastuser'  => session('username'),
                    'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                ]);

             DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }
    
    public function edit_all_ordcom(Request $request){

        DB::beginTransaction();

        try {

            foreach ($request->dataobj as $key => $value) {
                ///1. update detail
                DB::table('hisdb.chargetrx')
                    ->where('compcode','=',session('compcode'))
                    ->where('auditno','=',$value['auditno'])
                    ->update([
                        'trxtype' => $request->trxtype,
                        'quantity' => $request->ct_quantity,
                        'isudept' => $request->ct_isudept,
                        'remarks' => $request->remct_remarksarks,
                        'billcode' => $request->billcode,
                        'doctorcode' => $request->doctorcode,
                        'chg_class' => $request->chg_class,                    
                        'chgcode' =>  $request->ct_chgcode,
                        'chgtype' =>  $request->cm_chgtype,
                        'chggroup' => $request->chggroup,
                        'dracccode' => $request->dracccode,
                        'cracccode' => $request->cracccode,
                        'trxdate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'trxtime' => Carbon::now("Asia/Kuala_Lumpur"),

                        'adduser'  => session('username'),
                        'adddate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'lastuser'  => session('username'),
                        'lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    ]);
            }
         

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }

    }

    public function del_ordcom(Request $request){

        DB::beginTransaction();

        try {

            ///1. update detail
            DB::table('hisdb.chargetrx')
                ->where('compcode','=',session('compcode'))
                ->where('auditno','=',$request->auditno)
                ->update([ 
                    'recstatus' => 'DEACTIVE',
                    'deluser' => strtoupper(session('username')),
                    'deldate' => Carbon::now("Asia/Kuala_Lumpur")
                ]);

       
            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }

        
    }
}