<?php

namespace App\Http\Controllers\dialysis;

use Illuminate\Http\Request;
use stdClass;
use DB;
use Carbon\Carbon;
use App\Http\Controllers\dialysis\defaultController;

class enquiryController extends defaultController
{
    //
    public function __construct(){
    }

    public function show(Request $request){   

        $centers = $this->get_maiwp_center_dept();

        if(!empty($request->changedept)){

            $department = DB::table('sysdb.department')
                            ->where('compcode', session('compcode'))
                            ->where('deptcode', $request->changedept);

            if($department->exists()){
                $request->session()->put('dept', $department->first()->deptcode);
                $request->session()->put('dept_desc', $department->first()->description);
            }
        }

        return view('dialysis.enquiry',compact('centers'));
    }

    public function table(Request $request){   
        switch($request->action){
            case 'patmast_current_patient':          // for current
                return $this->patmast_current_patient($request);

            default:
                return 'error happen..';
        }
    }

    public function patmast_current_patient(Request $request){
    	$table_patm = DB::table('hisdb.pat_mast') //ambil dari patmast balik
                            ->select(['pat_mast.idno','pat_mast.CompCode','pat_mast.MRN','pat_mast.Episno','pat_mast.Name','pat_mast.Call_Name','pat_mast.addtype','pat_mast.Address1','pat_mast.Address2','pat_mast.Address3','pat_mast.Postcode','pat_mast.citycode','pat_mast.AreaCode','pat_mast.StateCode','pat_mast.CountryCode','pat_mast.telh','pat_mast.telhp','pat_mast.telo','pat_mast.Tel_O_Ext','pat_mast.ptel','pat_mast.ptel_hp','pat_mast.ID_Type','pat_mast.idnumber','pat_mast.Newic','pat_mast.Oldic','pat_mast.icolor','pat_mast.Sex','pat_mast.DOB','pat_mast.Religion','pat_mast.AllergyCode1','pat_mast.AllergyCode2','pat_mast.Century','pat_mast.Citizencode','pat_mast.OccupCode','pat_mast.Staffid','pat_mast.MaritalCode','pat_mast.LanguageCode','pat_mast.TitleCode','pat_mast.bloodgrp','pat_mast.Accum_chg','pat_mast.Accum_Paid','pat_mast.first_visit_date','pat_mast.last_visit_date','pat_mast.last_episno','pat_mast.PatStatus','pat_mast.Confidential','pat_mast.Active','pat_mast.FirstIpEpisNo','pat_mast.FirstOpEpisNo','pat_mast.AddUser','pat_mast.AddDate','pat_mast.Lastupdate','pat_mast.LastUser','pat_mast.OffAdd1','pat_mast.OffAdd2','pat_mast.OffAdd3','pat_mast.OffPostcode','pat_mast.MRFolder','pat_mast.MRLoc','pat_mast.MRActive','pat_mast.OldMrn','pat_mast.NewMrn','pat_mast.Remarks','pat_mast.RelateCode','pat_mast.ChildNo','pat_mast.CorpComp','pat_mast.Email','pat_mast.Email_official','pat_mast.CurrentEpis','pat_mast.NameSndx','pat_mast.BirthPlace','pat_mast.TngID','pat_mast.PatientImage','pat_mast.pAdd1','pat_mast.pAdd2','pat_mast.pAdd3','pat_mast.pPostCode','pat_mast.DeptCode','pat_mast.DeceasedDate','pat_mast.PatientCat','pat_mast.PatType','pat_mast.PatClass','pat_mast.upduser','pat_mast.upddate','pat_mast.recstatus','pat_mast.loginid','pat_mast.pat_category','pat_mast.idnumber_exp','pat_mast.PatientImage','debtormast.name as payer','epispayer.pay_type','dialysis_episode.arrival_date','dialysis_episode.arrival_time','dialysis_episode.idno as arrival','dialysis_episode.complete','dialysis_episode.order','episode.nurse_stat as nurse'])
                            // ->where('pat_mast.PatStatus','=','1')
                            ->where('pat_mast.compcode','=',session('compcode'))
                            ->leftJoin('hisdb.episode', function($join) use ($request){
                                $join = $join->on('episode.mrn', '=', 'pat_mast.MRN')
				                            ->where('episode.regdept','=',session('dept'))
				                            ->where('episode.compcode','=',session('compcode'))
				                            ->where('episode.episactive','=','1');
                            });

                            $table_patm = $table_patm->leftJoin('hisdb.dialysis_episode', function($join) use ($request){
                                $join = $join->on('dialysis_episode.mrn', '=', 'episode.mrn')
                                            ->on('dialysis_episode.episno','=','episode.episno')
                                            ->on('dialysis_episode.idno','episode.lastarrivalno')
                                            ->where('dialysis_episode.compcode','=',session('compcode'));
                            });

                            $table_patm = $table_patm->leftJoin('hisdb.epispayer', function($join) use ($request){
                                $join = $join->on('epispayer.mrn', '=', 'episode.mrn')
                                            ->on('epispayer.episno','=','episode.episno')
                                            ->where('epispayer.compcode','=',session('compcode'));

                            })
                            ->leftJoin('debtor.debtormast', function($join) use ($request){
                                $join = $join->on('debtormast.debtorcode', '=', 'epispayer.payercode')
                                                ->where('debtormast.compcode','=',session('compcode'));
                            });

            if(!empty($request->searchCol) && $request->searchCol[0]!='doctor'){
                $table_patm = $table_patm->where('pat_mast.'.$request->searchCol[0],'like',$request->searchVal[0]);
            }

            if(!empty($request->sidx)){
                if($request->sidx == 'arrival_date'){
                    $table_patm = $table_patm->orderBy('dialysis_episode.arrival_time', $request->sord);
                }else{
                    $table_patm = $table_patm->orderBy($request->sidx, $request->sord);
                }
            }else{
                $table_patm = $table_patm->orderBy('pat_mast.idno', 'DESC');
            }

            $paginate_patm = $table_patm->paginate($request->rows);

            // $responce = new stdClass();
            // $responce->current = $paginate_patm->currentPage();
            // $responce->lastPage = $paginate_patm->lastPage();
            // $responce->total = $paginate_patm->total();
            // $responce->rowCount = $paginate_patm->total();
            // $responce->rows = $paginate_patm->items();
            // $responce->query = $this->getQueries($table_patm);

            $responce = new stdClass();
            $responce->page = $paginate_patm->currentPage();
            $responce->total = $paginate_patm->lastPage();
            $responce->records = $paginate_patm->total();
            $responce->rows = $paginate_patm->items();
            $responce->query = $this->getQueries($table_patm);
            
            return json_encode($responce);
    }


}