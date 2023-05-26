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
            default:
                return 'error happen..';
        }
    }

    public function form(Request $request){  
        switch($request->action){
            case 'maintable':
                return $this->maintable($request);
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
                        ->select('e.idno','e.compcode','e.mrn','e.episno','e.admsrccode','e.epistycode','e.case_code','e.ward','e.bedtype','e.room','e.bed','e.admdoctor','e.attndoctor','e.refdoctor','e.prescribedays','e.pay_type','e.pyrmode','e.climitauthid','e.crnumber','e.depositreq','e.deposit','e.pkgcode','e.billtype','e.remarks','e.episstatus','e.episactive','e.adddate','e.adduser','e.reg_date','e.reg_time','e.dischargedate','e.dischargeuser','e.dischargetime','e.dischargedest','e.allocdoc','e.allocbed','e.allocnok','e.allocpayer','e.allocicd','e.lastupdate','e.lastuser','e.lasttime','e.procedure','e.dischargediag','e.lodgerno','e.regdept','e.diet1','e.diet2','e.diet3','e.diet4','e.diet5','e.glauthid','e.treatment','e.diagcode','e.complain','e.diagfinal','e.clinicalnote','e.conversion','e.newcaseP','e.newcaseNP','e.followupP','e.followupNP','e.bed2','e.bed3','e.bed4','e.bed5','e.bed6','e.bed7','e.bed8','e.bed9','e.bed10','e.diagprov','e.visitcase','e.PkgAutoNo','e.AgreementID','e.AdminFees','e.EDDept','e.dischargestatus','e.procode','e.treatcode','e.payer','e.doctorstatus','e.reff_rehab','e.reff_physio','e.reff_diet','e.stats_rehab','e.stats_physio','e.stats_diet','e.dry_weight','e.duration_hd','e.lastarrivaldate','e.lastarrivaltime','e.lastarrivalno','e.picdoctor','e.nurse_stat','d.doctorname as doctorname','c.description as case_desc','d.description as pay_type_desc')
                        ->where('e.mrn','=',$request->mrn)
                        ->where('e.compcode','=',session('compcode'))
                        ->leftJoin('hisdb.doctor as d', function($join) use ($request){
                            $join = $join->on('d.doctorcode', '=', 'e.admdoctor')
                                            ->where('d.compcode','=',session('compcode'));
                        })->leftJoin('hisdb.casetype as c', function($join) use ($request){
                            $join = $join->on('c.case_code', '=', 'e.case_code')
                                            ->where('c.compcode','=',session('compcode'));
                        })->leftJoin('debtor.debtortype as d', function($join) use ($request){
                            $join = $join->on('d.debtortycode', '=', 'e.pay_type')
                                            ->where('c.compcode','=',session('compcode'));
                        });

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

}