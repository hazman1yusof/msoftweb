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
            case 'get_chart':
                return $this->get_chart($request);
            
            default:
                return 'error happen..';
        }
    }

    public function get_table(Request $request){

        $table = DB::table('hisdb.bed as b')
                    ->select('b.idno','b.compcode','b.ward','b.room','b.bednum','b.bedtype','b.tel_ext','b.statistic','b.occup','b.isolate','b.baby','b.bedstatus','b.bedchgcode','b.lodchgcode','b.mealschgcode','b.otherchgcode','b.category','b.f1','b.f2','b.f3','b.f4','b.f5','b.lastuser','b.lastupdate','b.adduser','b.adddate','b.upduser','b.upddate','b.deluser','b.deldate','b.computerid','b.lastcomputerid','b.recstatus','b.mrn','b.episno','b.name','b.admdoctor','p.Sex','p.DOB','racecode.Description AS raceDesc','religion.Description AS religionDesc','occupation.description AS occupDesc','citizen.Description AS citizenDesc','areacode.Description AS areaDesc')
                    // ->leftJoin('hisdb.episode as e', function($join) use ($request){
                    //             $join = $join->where('e.mrn','=','b.mrn')
                    //                          ->where('e.episno','=','b.episno')
                    //                          ->where('e.compcode','=',session('compcode'));

                    //         })
                    ->leftJoin('hisdb.pat_mast AS p', function($join) use ($request){
                        $join = $join->on("p.mrn", '=', 'b.mrn')
                                     ->where('p.compcode','=',session('compcode'));
                    })
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
                    ->where('b.compcode','=',session('compcode'));

        /////////searching/////////
        if(!empty($request->searchCol)){
            $searchCol_array = $request->searchCol;

            $count = array_count_values($searchCol_array);

            foreach ($count as $key => $value) {
                $occur_ar = $this->index_of_occurance($key,$searchCol_array);

                $table = $table->orWhere(function ($table) use ($request,$searchCol_array,$occur_ar) {
                    foreach ($searchCol_array as $key => $value) {
                        $found = array_search($key,$occur_ar);
                        if($found !== false){
                            $table->Where($searchCol_array[$key],'like',$request->searchVal[$key]);
                        }
                    }
                });
            }
        }

        //////////ordering///////// ['expdate asc','idno desc']
        if(!empty($request->sortby)){
            $sortby_array = $request->sortby;

            foreach ($sortby_array as $key => $value) {
                $pieces = explode(" ", $sortby_array[$key]);
                $table = $table->orderBy($pieces[0], $pieces[1]);
            }
        }else if(!empty($request->sidx)){
            $pieces = explode(", ", $request->sidx .' '. $request->sord);
            if(count($pieces)==1){
                $table = $table->orderBy($request->sidx, $request->sord);
            }else{
                for ($i = sizeof($pieces)-1; $i >= 0 ; $i--) {
                    $pieces_inside = explode(" ", $pieces[$i]);
                    $table = $table->orderBy($pieces_inside[0], $pieces_inside[1]);
                }
            }
        }


        // $table = DB::table('hisdb.bed as b')
        //             ->select('b.compcode','b.bednum','b.bedtype','b.room','b.ward','b.occup','b.recstatus','b.idno','b.tel_ext','b.statistic','b.adduser','b.adddate','b.upduser','b.upddate','b.lastuser','b.lastupdate')
        //             ->where('compcode','=',session('compcode'));
        
        // // $table = $this->defaultGetter($request);

        // //////////paginate/////////
        // $paginate = $table->paginate($request->rows);

        // foreach ($paginate->items() as $key => $value) {
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

        // foreach ($paginate->items() as $key => $value) {
        //     $pat_mast_obj = DB::table('hisdb.pat_mast AS p')
        //                 ->select(['p.Sex','p.DOB','racecode.Description AS raceDesc','religion.Description AS religionDesc','occupation.description AS occupDesc','citizen.Description AS citizenDesc','areacode.Description AS areaDesc'])
        //                 ->leftJoin('hisdb.racecode', function($join) use ($request){
        //                     $join = $join->on('racecode.Code','=','p.RaceCode');
        //                     $join = $join->on('racecode.compcode','=','p.CompCode');
        //                 })
        //                 ->leftJoin('hisdb.religion', function($join) use ($request){
        //                     $join = $join->on('religion.Code','=','p.Religion');
        //                     $join = $join->on('religion.CompCode','=','p.CompCode');
        //                 })
        //                 ->leftJoin('hisdb.occupation', function($join) use ($request){
        //                     $join = $join->on('occupation.occupcode','=','p.OccupCode');
        //                     $join = $join->on('occupation.compcode','=','p.CompCode');
        //                 })
        //                 ->leftJoin('hisdb.citizen', function($join) use ($request){
        //                     $join = $join->on('citizen.Code','=','p.Citizencode');
        //                     $join = $join->on('citizen.compcode','=','p.CompCode');
        //                 })
        //                 ->leftJoin('hisdb.areacode', function($join) use ($request){
        //                     $join = $join->on('areacode.areacode','=','p.AreaCode');
        //                     $join = $join->on('areacode.compcode','=','p.CompCode');
        //                 })
        //                 ->where('p.MRN','=',$value->mrn)
        //                 ->where('p.CompCode','=',session('compcode'));

        //     if($pat_mast_obj->exists()){
        //         $pat_mast_obj_first = $pat_mast_obj->first();
        //         $value->sex = strtoupper($pat_mast_obj_first->Sex);
        //         $value->dob = $pat_mast_obj_first->DOB;
        //         $value->age = Carbon::parse($pat_mast_obj_first->DOB)->age;
        //         $value->race = strtoupper($pat_mast_obj_first->raceDesc);
        //         $value->religion = strtoupper($pat_mast_obj_first->religionDesc);
        //         $value->occupation = strtoupper($pat_mast_obj_first->occupDesc);
        //         $value->citizen = strtoupper($pat_mast_obj_first->citizenDesc);
        //         $value->area = strtoupper($pat_mast_obj_first->areaDesc);
        //     }else{
        //         $value->sex = '';
        //         $value->dob = '';
        //         $value->age = '';
        //         $value->race = '';
        //         $value->religion = '';
        //         $value->occupation = '';
        //         $value->citizen = '';
        //         $value->area = '';
        //     }       
        // }

        //////////paginate/////////
        $paginate = $table->paginate($request->rows);

        $responce = new stdClass();
        $responce->page = $paginate->currentPage();
        $responce->total = $paginate->lastPage();
        $responce->records = $paginate->total();
        $responce->rows = $paginate->items();
        $responce->sql_query = $this->getQueries($table);

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

            if(!empty($request->newic_reserve)){
                $newic_reserve = str_replace('-','', $request->newic_reserve);
            }else{
                $newic_reserve = null;
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
                    'newic' => $newic_reserve,
                    'computerid' => session('computerid'),
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

            if(!empty($request->newic_reserve)){
                $newic_reserve = str_replace('-','', $request->newic_reserve);
            }else{
                $newic_reserve = null;
            }

            $arr_upd = [  
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
                    'computerid' => session('computerid'),
                    'newic' => $newic_reserve,
                    'upduser' => strtoupper(session('username')),
                    'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                ];

            DB::table('hisdb.bed')
                ->where('idno','=',$request->idno)
                ->update($arr_upd); 

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

    public function get_chart(Request $request){
        $label = [];
        $data_occ = [];
        $data_vac = [];
        $data_main = [];

        $table = DB::table('hisdb.bed')
                ->select('compcode','bedtype','ward','bednum','occup','room','statistic','recstatus')
                ->where('compcode','=',session('compcode'))
                ->where('statistic','=','1')
                ->get();

        if($request->chart_sel=='ward'){
            $bed = DB::table('hisdb.bed')->select('ward')->distinct()->get(['ward']);
            foreach ($bed as $key => $value) {
                array_push($label,$value->ward);
            }

            foreach ($bed as $key_bed => $value_bed) {
                $value_bed->occ = 0;
                $value_bed->vac = 0;
                $value_bed->main = 0;
                foreach ($table as $key_table => $value_table) {
                    if($value_table->ward == $value_bed->ward){
                        if($value_table->occup == 'OCCUPIED'){
                            $value_bed->occ = $value_bed->occ + 1;
                        }else if($value_table->occup == 'VACANT'){
                            $value_bed->vac = $value_bed->vac + 1;
                        }else if($value_table->occup == 'MAINTENANCE'){
                            $value_bed->main = $value_bed->main + 1;
                        }
                    }
                }
            }

            foreach ($bed as $key => $value) {
                array_push($data_occ,$value->occ);
                array_push($data_vac,$value->vac);
                array_push($data_main,$value->main);
            }

        }else if($request->chart_sel=='bedtype'){
            $bed = DB::table('hisdb.bed')->select('bedtype')->distinct()->get(['bedtype']);
            foreach ($bed as $key => $value) {
                array_push($label,$value->bedtype);
            }

            foreach ($bed as $key_bed => $value_bed) {
                $value_bed->occ = 0;
                $value_bed->vac = 0;
                $value_bed->main = 0;
                foreach ($table as $key_table => $value_table) {
                    if($value_table->bedtype == $value_bed->bedtype){
                        if($value_table->occup == 'OCCUPIED'){
                            $value_bed->occ = $value_bed->occ + 1;
                        }else if($value_table->occup == 'VACANT'){
                            $value_bed->vac = $value_bed->vac + 1;
                        }else if($value_table->occup == 'MAINTENANCE'){
                            $value_bed->main = $value_bed->main + 1;
                        }
                    }
                }
            }

            foreach ($bed as $key => $value) {
                array_push($data_occ,$value->occ);
                array_push($data_vac,$value->vac);
                array_push($data_main,$value->main);
            }
        }

        $responce = new stdClass();
        $responce->label = $label;
        $responce->data_occ = $data_occ;
        $responce->data_vac = $data_vac;
        $responce->data_main = $data_main;

        return json_encode($responce);
    }
}