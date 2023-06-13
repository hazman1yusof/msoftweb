<?php

namespace App\Http\Controllers\hisdb;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;
use Response;

class PatEnqController extends defaultController
{   

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request){   
        return view('hisdb.pat_enq.pat_enq');
    }

    public function table(Request $request){
        switch($request->action){
            case 'episodelist':
                return $this->episodelist($request);
            case 'loadgl':
                return $this->loadgl($request);
            case 'show_mc':
                return $this->show_mc($request);
            case 'mc_list':
                return $this->mc_list($request);
            default:
                return 'error happen..';
        }
    }

    public function form(Request $request){  
        switch($request->action){
            case 'maintable':
                return $this->maintable($request);
            case 'save_mc':
                return $this->save_mc($request);
            default:
                return 'error happen..';
        } 
    }

    public function maintable(Request $request){

        // $mrn_range = $this->mrn_range($request);
        $table_patm = DB::table('hisdb.pat_mast');

        if(!empty($request->searchCol)){
            $searchCol_array = $request->searchCol;
            $count = array_count_values($searchCol_array);

            foreach ($count as $key => $value) {
                $occur_ar = $this->index_of_occurance($key,$searchCol_array);

                $table_patm = $table_patm->orWhere(function ($table_patm) use ($request,$searchCol_array,$occur_ar) {
                    foreach ($searchCol_array as $key => $value) {
                        $found = array_search($key,$occur_ar);
                        if($found !== false){
                            $table_patm->Where($searchCol_array[$key],'like',$request->searchVal[$key]);
                        }
                    }
                });
            }
        }

        $table_patm = $table_patm
                    ->where('Active','=','1')
                    ->where('compcode','=',session('compcode'));
                    // ->whereBetween('MRN',$mrn_range);


        if(!empty($request->sort)){
            foreach ($request->sort as $key => $value) {
                $table_patm = $table_patm->orderBy($key, $value);
            }
        }else{
            $table_patm = $table_patm->orderBy('idno', 'DESC');
        }

        $request->page = $request->current;

        //////////paginate/////////
        $paginate = $table_patm->paginate($request->rowCount);

        foreach ($paginate->items() as $key => $value) {
            if($value->PatStatus==1){
                $episode = DB::table('hisdb.episode')
                            ->select(['episode.mrn','doctor.doctorname','episode.epistycode'])
                            ->leftJoin('hisdb.doctor','doctor.doctorcode','=','episode.admdoctor')
                            ->where('episode.mrn','=',$value->MRN)
                            ->where('episode.episno','=',$value->Episno)
                            ->where('episode.compcode','=',session('compcode'));


                if($episode->exists()){
                    $episode = $episode->first();
                    $value->q_epistycode = $episode->epistycode;
                    $value->q_doctorname = $episode->doctorname;
                }
            }
        }

        $responce = new stdClass();
        $responce->current = $paginate->currentPage();
        $responce->lastPage = $paginate->lastPage();
        $responce->total = $paginate->total();
        $responce->rowCount = $request->rowCount;
        $responce->rows = $paginate->items();
        $responce->sql = $table_patm->toSql();
        $responce->sql_bind = $table_patm->getBindings();

        return json_encode($responce);
    }

    public function episodelist(Request $request){

        // $mrn_range = $this->mrn_range($request);
        $table = DB::table('hisdb.episode as e')
                        ->select('e.idno','e.compcode','e.mrn','e.episno','e.admsrccode','e.epistycode','e.case_code','e.ward','e.bedtype','e.room','e.bed','e.admdoctor','e.attndoctor','e.refdoctor','e.prescribedays','e.pay_type','e.pyrmode','e.climitauthid','e.crnumber','e.depositreq','e.deposit','e.pkgcode','e.billtype','e.remarks','e.episstatus','e.episactive','e.adddate','e.adduser','e.reg_date','e.reg_time','e.dischargedate','e.dischargeuser','e.dischargetime','e.dischargedest','e.allocdoc','e.allocbed','e.allocnok','e.allocpayer','e.allocicd','e.lastupdate','e.lastuser','e.lasttime','e.procedure','e.dischargediag','e.lodgerno','e.regdept','e.diet1','e.diet2','e.diet3','e.diet4','e.diet5','e.glauthid','e.treatment','e.diagcode','e.complain','e.diagfinal','e.clinicalnote','e.conversion','e.newcaseP','e.newcaseNP','e.followupP','e.followupNP','e.bed2','e.bed3','e.bed4','e.bed5','e.bed6','e.bed7','e.bed8','e.bed9','e.bed10','e.diagprov','e.visitcase','e.PkgAutoNo','e.AgreementID','e.AdminFees','e.EDDept','e.dischargestatus','e.procode','e.treatcode','e.payer','e.doctorstatus','e.reff_rehab','e.reff_physio','e.reff_diet','e.stats_rehab','e.stats_physio','e.stats_diet','e.dry_weight','e.duration_hd','e.lastarrivaldate','e.lastarrivaltime','e.lastarrivalno','e.picdoctor','e.nurse_stat','d.doctorname as doctorname','c.description as case_desc','d.description as pay_type_desc','ad.description as admsrccode_desc','dp.description as regdept_desc');

        if(!empty($request->searchCol)){
            if(!empty($request->fixPost)){
                $searchCol_array = $this->fixPost3($request->searchCol);
            }else{
                $searchCol_array = $request->searchCol;
            }

            $count = array_count_values($searchCol_array);
            // dump($count);

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

        $table = $table->where('e.mrn','=',$request->mrn)
                        ->where('e.compcode','=',session('compcode'))
                        ->leftJoin('hisdb.doctor as d', function($join) use ($request){
                            $join = $join->on('d.doctorcode', '=', 'e.admdoctor')
                                            ->where('d.compcode','=',session('compcode'));
                        })->leftJoin('sysdb.department as dp', function($join) use ($request){
                            $join = $join->on('dp.deptcode', '=', 'e.regdept')
                                            ->where('d.compcode','=',session('compcode'));
                        })->leftJoin('hisdb.admissrc as ad', function($join) use ($request){
                            $join = $join->on('ad.admsrccode', '=', 'e.admsrccode')
                                            ->where('ad.compcode','=',session('compcode'));
                        })->leftJoin('hisdb.casetype as c', function($join) use ($request){
                            $join = $join->on('c.case_code', '=', 'e.case_code')
                                            ->where('c.compcode','=',session('compcode'));
                        })->leftJoin('debtor.debtortype as d', function($join) use ($request){
                            $join = $join->on('d.debtortycode', '=', 'e.pay_type')
                                            ->where('c.compcode','=',session('compcode'));
                        });

        if(!empty($request->sort)){
            foreach ($request->sort as $key => $value) {
                $table = $table->orderBy($key, $value);
            }
        }else{
            $table = $table->orderBy('idno', 'DESC');
        }

        //////////paginate/////////
        $paginate = $table->paginate($request->rows);

        $responce = new stdClass();
        $responce->page = $paginate->currentPage();
        $responce->total = $paginate->lastPage();
        $responce->records = $paginate->total();
        $responce->rows = $paginate->items();
        $responce->sql = $table->toSql();
        $responce->sql_bind = $table->getBindings();
        $responce->sql_query = $this->getQueries($table);

        return json_encode($responce);
    }


    public function mrn_range(Request $request){
        if($request->PatClass == 'HIS'){
            $table = DB::table('sysdb.sysparam')
                        ->where('source','=','HIS')
                        ->where('trantype','=','MRN')
                        ->first();

            return explode(',', $table->pvalue2);
        }else if($request->PatClass == 'OTC'){
            $table = DB::table('sysdb.sysparam')
                        ->where('source','=','OTC')
                        ->where('trantype','=','MRN')
                        ->first();

            return explode(',', $table->pvalue2);
        }else{
            dump('wrong patclass, choose only HIS or OTC');
        }
    }


    public function loadgl(Request $request){
        $data = DB::table('hisdb.guarantee AS g')
                    ->select('g.idno','g.compcode','g.debtorcode','g.staffid','g.relatecode','g.childno','g.refno','g.gltype','g.startdate','g.enddate','g.adduser','g.adddate','g.upduser','g.upddate','g.visitno','g.visitbal','g.medcase','g.remark','g.name','g.ourrefno','g.mrn','g.active','g.episno','g.lineno_','g.case','dm.name as debtor_name','r.Description as relate_desc','o.occupcode','o.description as occup_desc')
                    ->leftJoin('debtor.debtormast AS dm', function($join) use ($request){
                        $join = $join->on('dm.debtorcode', '=', 'g.debtorcode')
                                        ->where('dm.compcode','=',session('compcode'));
                    })->leftJoin('hisdb.relationship AS r', function($join) use ($request){
                        $join = $join->on('r.RelationShipCode', '=', 'g.relatecode')
                                        ->where('r.compcode','=',session('compcode'));
                    })->leftJoin('hisdb.pat_mast AS pm', function($join) use ($request){
                        $join = $join->on('pm.MRN', '=', 'g.mrn')
                                        ->where('pm.compcode','=',session('compcode'));
                    })->leftJoin('hisdb.occupation AS o', function($join) use ($request){
                        $join = $join->on('o.occupcode', '=', 'pm.OccupCode')
                                        ->where('o.compcode','=',session('compcode'));
                    })
                    ->where('g.mrn','=',$request->mrn)
                    ->where('g.episno','=',$request->episno)
                    ->where('g.compcode','=',session('compcode'))
                    ->first();

        $responce = new stdClass();
        $responce->data = $data;
        return json_encode($responce);

    }

    public function save_mc(Request $request){

        DB::beginTransaction();
        try {

            $idno = DB::table('hisdb.patmc')
                ->insertGetId([  
                    'compcode' => session('compcode'),
                    'datefrom' => $request->datefrom ,
                    'dateto' => $request->dateto ,
                    'mrn' => $request->mrn ,
                    'episno' => $request->episno ,
                    'patfrom' => $request->patfrom ,
                    'mccnt' => $request->mccnt ,
                    'serialno' => $request->serialno ,
                    'dateresume' => $request->dateresume ,
                    'datereexam' => $request->datereexam ,
                    'printeddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'printedby' => strtoupper(session('username')),
                    'adduser' => strtoupper(session('username')),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur")
                ]);

            DB::commit();
            $responce = new stdClass();
            $responce->res = 'SUCCESS';
            $responce->idno = $idno;
            return json_encode($responce);

        } catch (\Exception $e) {
            DB::rollback();

            $responce = new stdClass();
            $responce->res = 'ERROR';

            return response(json_encode($responce), 500);
        }
    }

    public function show_mc(Request $request){   
        $patmc = DB::table('hisdb.patmc as mc')
                        ->select('mc.idno','mc.compcode','mc.datefrom','mc.dateto','mc.dateresume','mc.datereexam','mc.mrn','mc.episno','pm.newic','mc.patfrom','mc.mccnt','mc.adduser','mc.adddate','mc.serialno','mc.printeddate','mc.printedby','pm.sex')
                        ->leftJoin('hisdb.pat_mast as pm', function($join) use ($request){
                                $join = $join->on('pm.MRN', '=', 'mc.MRN')
                                                ->where('pm.compcode','=',session('compcode'));
                            })
                        ->where('mc.idno',$request->idno)
                        ->first();

        if(empty($patmc->sex) || $patmc->sex=='M'){
            $sex='he';
        }else{
            $sex='she';
        }

        $ini_array = [
            'compcode' => $patmc->compcode,
            'datefrom' => $patmc->datefrom,
            'dateto' => $patmc->dateto,
            'dateresume' => $patmc->dateresume,
            'datereexam' => $patmc->datereexam,
            'mrn' => $patmc->mrn,
            'episno' => $patmc->episno,
            'newic' => $patmc->newic,
            'patfrom' => $patmc->patfrom,
            'mccnt' => ltrim($patmc->mccnt, '0'),
            'adduser' => $patmc->adduser,
            'adddate' => $patmc->adddate,
            'serialno' => str_pad($patmc->idno, 6, "0", STR_PAD_LEFT),
            'printeddate' => $patmc->printeddate,
            'printedby' => $patmc->printedby,
            'sex' => $sex
        ];

        if(true){
            return view('hisdb.pat_enq.mymc',compact('ini_array'));
        }else{
            abort(403, 'MC not found');
        }

    }

    public function mc_list(Request $request){
        $patmc = DB::table('hisdb.patmc')
                    ->where('compcode',session('compcode'))
                    ->where('mrn',$request->mrn)
                    ->get();

        $responce = new stdClass();
        $responce->data = $patmc;

        return json_encode($responce);
    }
}