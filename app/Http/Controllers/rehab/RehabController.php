<?php

namespace App\Http\Controllers\rehab;

use Illuminate\Http\Request;
use stdClass;
use App\User;
use DB;
use Carbon\Carbon;
use Auth;
use Session;
use App\Http\Controllers\defaultController;

class RehabController extends defaultController
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function table(Request $request)
    {
        switch($request->action){
            case 'get_table_doctornote':
                return $this->get_table_doctornote($request);
            
            // transaction stuff
            case 'get_transaction_table':
                return $this->get_transaction_table($request);
            case 'get_chgcode':
                return $this->get_chgcode($request);
            case 'get_drugindcode':
                return $this->get_drugindcode($request);
            case 'get_freqcode':
                return $this->get_freqcode($request);
            case 'get_dosecode':
                return $this->get_dosecode($request);
            case 'get_inscode':
                return $this->get_inscode($request);
            
            // event stuff
            case 'doctornote_event':
                return $this->doctornote_event($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function form(Request $request)
    {
        switch($request->action){
            case 'submit_patient':
                return $this->submit_patient($request);
            
            default:
                return 'error happen..';
        }
    }
    
    public function index(Request $request){
        // dd(Auth::user());
        
        // $navbar = $this->navbar();
        
        // $emergency = DB::table('hisdb.episode')
        //             ->whereMonth('reg_date','=',now()->month)
        //             ->whereYear('reg_date','=',now()->year)
        //             ->get();
        
        // $events = $this->getEvent($emergency);
        
        // if(!empty($request->username)){
        //     $user = DB::table('users')
        //             ->where('username','=',$request->username);
        //     if($user->exists()){
        //         $user = User::where('username',$request->username);
        //         Auth::login($user->first());
        //     }
        // }
        $data_send = [];
        
        $apptresrc = DB::table('hisdb.apptresrc')
                    ->where('compcode',session('compcode'))
                    ->where('TYPE','OT')
                    ->first();
        
        $data_send['apptresrc_reqfor'] = $apptresrc->resourcecode;
        
        $ordcomtt_phar = DB::table('sysdb.sysparam')
                        ->where('compcode',session('compcode'))
                        ->where('source','=','OE')
                        ->where('trantype','=','PHAR')->first();
        $ordcomtt_disp = DB::table('sysdb.sysparam')
                        ->where('compcode',session('compcode'))
                        ->where('source','=','OE')
                        ->where('trantype','=','DISP')->first();
        $ordcomtt_rad = DB::table('sysdb.sysparam')
                        ->where('compcode',session('compcode'))
                        ->where('source','=','OE')
                        ->where('trantype','=','RAD')->first();
        $ordcomtt_lab = DB::table('sysdb.sysparam')
                        ->where('compcode',session('compcode'))
                        ->where('source','=','OE')
                        ->where('trantype','=','LAB')->first();
        $ordcomtt_phys = DB::table('sysdb.sysparam')
                        ->where('compcode',session('compcode'))
                        ->where('source','=','OE')
                        ->where('trantype','=','PHYSIOTERAPHY')->first();
        $ordcomtt_rehab = DB::table('sysdb.sysparam')
                        ->where('compcode',session('compcode'))
                        ->where('source','=','OE')
                        ->where('trantype','=','REHABILITATION')->first();
        $ordcomtt_diet = DB::table('sysdb.sysparam')
                        ->where('compcode',session('compcode'))
                        ->where('source','=','OE')
                        ->where('trantype','=','DIETATIC')->first();
        $ordcomtt_dfee = DB::table('sysdb.sysparam')
                        ->where('compcode',session('compcode'))
                        ->where('source','=','OE')
                        ->where('trantype','=','DOCTORFEES')->first();
        $ordcomtt_oth = DB::table('sysdb.sysparam')
                        ->where('compcode',session('compcode'))
                        ->where('source','=','OE')
                        ->where('trantype','=','OTH')->first();
        $ordcomtt_pkg = DB::table('sysdb.sysparam')
                        ->where('compcode',session('compcode'))
                        ->where('source','=','OE')
                        ->where('trantype','=','PKG')->first();
        
        $data_send['ordcomtt_phar'] = $ordcomtt_phar->pvalue1;
        $data_send['ordcomtt_disp'] = $ordcomtt_disp->pvalue1;
        $data_send['ordcomtt_rad'] = $ordcomtt_rad->pvalue1;
        $data_send['ordcomtt_lab'] = $ordcomtt_lab->pvalue1;
        $data_send['ordcomtt_phys'] = $ordcomtt_phys->pvalue1;
        $data_send['ordcomtt_rehab'] = $ordcomtt_rehab->pvalue1;
        $data_send['ordcomtt_diet'] = $ordcomtt_diet->pvalue1;
        $data_send['ordcomtt_dfee'] = $ordcomtt_dfee->pvalue1;
        $data_send['ordcomtt_oth'] = $ordcomtt_oth->pvalue1;
        $data_send['ordcomtt_pkg'] = $ordcomtt_pkg->pvalue1;
        
        $data_send['phardept_dflt'] = $ordcomtt_phar->pvalue2;
        $data_send['dispdept_dflt'] = $ordcomtt_phar->pvalue2;
        $data_send['labdept_dflt'] = $ordcomtt_lab->pvalue2;
        $data_send['raddept_dflt'] = $ordcomtt_rad->pvalue2;
        $data_send['physdept_dflt'] = $ordcomtt_phys->pvalue2;
        $data_send['rehabdept_dflt'] = $ordcomtt_rehab->pvalue2;
        $data_send['dietdept_dflt'] = $ordcomtt_diet->pvalue2;
        $data_send['pkgdept_dflt'] = session('deptcode');
        $data_send['othdept_dflt'] = session('deptcode');
        
        return view('rehab.rehab',$data_send);
    }
    
    public function get_table_doctornote($request){
        $table_patm = DB::table('hisdb.pat_mast') // ambil dari patmast balik
                    ->select(['episode.idno','pat_mast.CompCode','episode.MRN','episode.Episno','pat_mast.Name','pat_mast.Call_Name','pat_mast.addtype','pat_mast.Address1','pat_mast.Address2','pat_mast.Address3','pat_mast.Postcode','pat_mast.citycode','pat_mast.AreaCode','pat_mast.StateCode','pat_mast.CountryCode','pat_mast.telh','pat_mast.telhp','pat_mast.telo','pat_mast.Tel_O_Ext','pat_mast.ptel','pat_mast.ptel_hp','pat_mast.ID_Type','pat_mast.idnumber','pat_mast.Newic','pat_mast.Oldic','pat_mast.icolor','pat_mast.Sex','pat_mast.DOB','pat_mast.Religion','pat_mast.AllergyCode1','pat_mast.AllergyCode2','pat_mast.Century','pat_mast.Citizencode','pat_mast.OccupCode','pat_mast.Staffid','pat_mast.MaritalCode','pat_mast.LanguageCode','pat_mast.TitleCode','pat_mast.RaceCode','pat_mast.bloodgrp','pat_mast.Accum_chg','pat_mast.Accum_Paid','pat_mast.first_visit_date','pat_mast.Reg_Date','pat_mast.last_visit_date','pat_mast.last_episno','pat_mast.PatStatus','pat_mast.Confidential','pat_mast.Active','pat_mast.FirstIpEpisNo','pat_mast.FirstOpEpisNo','pat_mast.AddUser','pat_mast.AddDate','pat_mast.Lastupdate','pat_mast.LastUser','pat_mast.OffAdd1','pat_mast.OffAdd2','pat_mast.OffAdd3','pat_mast.OffPostcode','pat_mast.MRFolder','pat_mast.MRLoc','pat_mast.MRActive','pat_mast.OldMrn','pat_mast.NewMrn','pat_mast.Remarks','pat_mast.RelateCode','pat_mast.ChildNo','pat_mast.CorpComp','pat_mast.Email','pat_mast.Email_official','pat_mast.CurrentEpis','pat_mast.NameSndx','pat_mast.BirthPlace','pat_mast.TngID','pat_mast.PatientImage','pat_mast.pAdd1','pat_mast.pAdd2','pat_mast.pAdd3','pat_mast.pPostCode','pat_mast.DeptCode','pat_mast.DeceasedDate','pat_mast.PatientCat','pat_mast.PatType','pat_mast.PatClass','pat_mast.upduser','pat_mast.upddate','pat_mast.recstatus','pat_mast.loginid','pat_mast.pat_category','pat_mast.idnumber_exp','episode.doctorstatus','episode.reg_time','episode.payer','episode.pyrmode','episode.regdept','episode.reff_rehab','episode.reff_physio','episode.reff_diet','episode.reff_ed','episode.reff_rad','episode.stats_rehab','episode.stats_physio','episode.stats_diet','episode.episactive','episode.episstatus','episode.admdoctor','doctor.doctorname']);
        
        $table_patm = $table_patm->leftJoin('hisdb.episode', function ($join) use ($request){
                    $join = $join->on('episode.mrn','=','pat_mast.MRN');
                    $join = $join->where('episode.epistycode','=','OP');
                    // $join = $join->whereIn('episode.regdept',['A&E','PHY','XRAY','DIET']);
                    $join = $join->whereIn('episode.regdept',['PHY']);
                    // $join = $join->where(
                    //         function ($query){
                    //             return $query
                    //                     ->whereNull('episode.episstatus')
                    //                     ->orWhere('episode.episstatus','!=','C');
                    //         }
                    // );
        });
        
        $table_patm = $table_patm->leftJoin('hisdb.doctor', function ($join) use ($request){
                    $join = $join->on('doctor.doctorcode','=','episode.admdoctor');
                    $join = $join->where('doctor.compcode','=',session('compcode'));
        });
        
        $table_patm = $table_patm->where('pat_mast.compcode','=',session('compcode'))
                                ->where('episode.reg_date','=',$request->filterVal[0]);
        
        if(!empty($request->sidx)){
            $table_patm = $table_patm->orderBy($request->sidx,$request->sord);
        }else{
            $table_patm = $table_patm->orderBy('episode.reg_time','desc');
        }
        
        //////////paginate//////////
        $paginate = $table_patm->paginate($request->rows);
        
        $responce = new stdClass();
        $responce->page = $paginate->currentPage();
        $responce->total = $paginate->lastPage();
        $responce->records = $paginate->total();
        $responce->rows = $paginate->items();
        $responce->sql = $table_patm->toSql();
        $responce->sql_bind = $table_patm->getBindings();
        return json_encode($responce);
    }
    
    public function get_transaction_table($request){
        if($request->rows == null){
            $request->rows = 100;
        }
        
        $table_chgtrx = DB::table('hisdb.chargetrx as trx') // ambil dari patmast balik
                        ->select(
                            'trx.id',
                            'trx.chgcode as chg_code',
                            'trx.quantity',
                            'trx.remarks',
                            'trx.instruction as ins_code',
                            'trx.doscode as dos_code',
                            'trx.frequency as fre_code',
                            'trx.drugindicator as dru_code',
                            
                            'chgmast.description as chg_desc',
                            'instruction.description as ins_desc',
                            'dose.dosedesc as dos_desc',
                            'freq.freqdesc as fre_desc',
                            'drugindicator.drugindcode as dru_desc')
                        ->where('trx.mrn','=',$request->mrn)
                        ->where('trx.episno','=',$request->episno)
                        ->where('trx.compcode','=',session('compcode'));
        
        if($request->isudept != 'CLINIC'){
            $table_chgtrx->where('trx.isudept','=',$request->isudept);
        }
        
        $table_chgtrx = $table_chgtrx
                        ->leftJoin('hisdb.chgmast','chgmast.chgcode','=','trx.chgcode')
                        ->leftJoin('hisdb.instruction','instruction.inscode','=','trx.instruction')
                        ->leftJoin('hisdb.freq','freq.freqcode','=','trx.frequency')
                        ->leftJoin('hisdb.dose','dose.dosecode','=','trx.doscode')
                        ->leftJoin('hisdb.drugindicator','drugindicator.drugindcode','=','trx.drugindicator')
                        ->orderBy('trx.id','desc');
        
        //////////paginate//////////
        $paginate = $table_chgtrx->paginate($request->rows);
        
        $responce = new stdClass();
        $responce->page = $paginate->currentPage();
        $responce->total = $paginate->lastPage();
        $responce->records = $paginate->total();
        $responce->rows = $paginate->items();
        $responce->sql = $table_chgtrx->toSql();
        $responce->sql_bind = $table_chgtrx->getBindings();
        return json_encode($responce);
    }
    
    public function getEvent($obj){
        $events = [];
        
        for($i = 1; $i <= 31; $i++){
            $days = 0;
            $reg_date;
            foreach($obj as $key => $value){
                $day = Carbon::createFromFormat('Y-m-d',$value->reg_date);
                if($day->day == $i){
                    $reg_date = $value->reg_date;
                    $days++;
                }
            }
            if($days != 0){
                $event = new stdClass();
                $event->title = $days.' patients';
                $event->start = $reg_date;
                array_push($events, $event);
            }
        }
        
        return $events;
    }
    
    public function transaction_save(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $table = DB::table('hisdb.chargetrx');
            
            $chgmast = DB::table('hisdb.chgmast')
                        ->where('compcode','=',session('compcode'))
                        ->where('chgcode','=',$request->chg_desc)
                        ->first();
            
            if($request->oper == 'edit'){
                $table->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno)
                        ->where('id','=',$request->id);
                
                $array_edit = [
                    'chgcode' => $request->chg_desc,
                    'chggroup' =>  $chgmast->chggroup,
                    'quantity' => $request->quantity,
                    'instruction' => $request->ins_desc,
                    'doscode' => $request->dos_desc,
                    'frequency' => $request->fre_desc,
                    'drugindicator' => $request->dru_desc,
                    'remarks' => $request->remarks,
                    'lastuser' => Auth::user()->username,
                    'lastupdate' => Carbon::now("Asia/Kuala_Lumpur")
                ];
                
                $table->update($array_edit);
            }else if($request->oper == 'add'){
                $array_insert = [
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn,
                    'episno' => $request->episno,
                    'trxtype' => 'OE',
                    'trxdate' => $request->trxdate,
                    'chgcode' => $request->chg_desc,
                    'chggroup' =>  $chgmast->chggroup,
                    'instruction' => $request->ins_desc,
                    'doscode' => $request->dos_desc,
                    'frequency' => $request->fre_desc,
                    'drugindicator' => $request->dru_desc,
                    'remarks' => $request->remarks,
                    'billflag' => '0',
                    'quantity' => $request->quantity,
                    'isudept' => $request->isudept,
                    'trxtime' => Carbon::now("Asia/Kuala_Lumpur"),
                    'lastuser' => Auth::user()->username,
                    'lastupdate' => Carbon::now("Asia/Kuala_Lumpur")
                ];
                
                $table->insert($array_insert);
            }else if($request->oper == 'del'){
                $table->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno)
                        ->where('id','=',$request->id)->delete();
            }
            
            $responce = new stdClass();
            $responce->success = 'success';
            echo json_encode($responce);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response('Error'.$e, 500);
            
        }
        
    }
    
    public function submit_patient(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            $episode = DB::table('hisdb.episode')
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno)
                        ->where('reg_date','=',$request->reg_date);
            
            if($episode->exists()){
                $episode
                    ->update([
                        'doctorstatus' => 'SEEN'
                    ]);
            }else{
                throw new \Exception("Patient doesnt exists");
            }
            
            DB::commit();
            
            $responce = new stdClass();
            $responce->data = 'success';
            
            return json_encode($responce);
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
        
    }
    
    public function get_chgcode(Request $request){
        
        $data = DB::table('hisdb.chgmast')
                ->select('chgcode as code','description as description')
                ->where('compcode','=',session('compcode'))
                ->where('active','=',1);
        
        if(Session::has('chggroup')){
            $data = $data->where('chggroup','=',session('chggroup'));
        }
        
        $data = $data->orderBy('chgcode', 'ASC');
        
        if(!empty($request->search)){
            $data = $data->where('description','LIKE','%'.$request->search.'%')->first();
        }else{
            $data = $data->get();
        }
        
        $responce = new stdClass();
        $responce->data = $data;
        return json_encode($responce);
        
    }
    
    public function get_drugindcode(Request $request){
        
        $data = DB::table('hisdb.drugindicator')
                ->select('drugindcode as code','description as description');
        
        if(!empty($request->search)){
            $data = $data->where('description','LIKE','%'.$request->search.'%')->first();
        }else{
            $data = $data->get();
        }
        
        $responce = new stdClass();
        $responce->data = $data;
        return json_encode($responce);
        
    }
    
    public function get_freqcode(Request $request){
        
        $data = DB::table('hisdb.freq')
                ->select('freqcode as code','freqdesc as description')
                ->where('compcode','=',session('compcode'));
        
        if(!empty($request->search)){
            $data = $data->where('freqdesc','LIKE','%'.$request->search.'%')->first();
        }else{
            $data = $data->get();
        }
        
        $responce = new stdClass();
        $responce->data = $data;
        return json_encode($responce);
        
    }
    
    public function get_dosecode(Request $request){
        
        $data = DB::table('hisdb.dose')
                ->select('dosecode as code','dosedesc as description')
                ->where('compcode','=',session('compcode'));
        
        if(!empty($request->search)){
            $data = $data->where('dosedesc','LIKE','%'.$request->search.'%')->first();
        }else{
            $data = $data->get();
        }
        
        $responce = new stdClass();
        $responce->data = $data;
        return json_encode($responce);
        
    }
    
    public function get_inscode(Request $request){
        
        $data = DB::table('hisdb.instruction')
                ->select('inscode as code','description as description')
                ->where('compcode','=',session('compcode'));
        
        if(!empty($request->search)){
            $data = $data->where('description','LIKE','%'.$request->search.'%')->first();
        }else{
            $data = $data->get();
        }
        
        $responce = new stdClass();
        $responce->data = $data;
        return json_encode($responce);
        
    }
    
    public function doctornote_event(Request $request){
        
        $emergency = DB::table('hisdb.episode')
                    ->where('compcode','=',session('compcode'))
                    // ->whereIn('episode.regdept',['A&E','PHY','XRAY','DIET'])
                    ->whereIn('episode.regdept',['PHY'])
                    ->whereRaw(
                        "(reg_date >= ? AND reg_date <= ?)",
                        [
                            $request->start,
                            $request->end
                        ])
                        ->where('episode.epistycode','=','OP')
                        // ->whereIn('episode.episstatus',[null,'C','B'])
                        // ->whereNull('episode.episstatus')
                        // ->orWhere('episode.episstatus','!=','C')
                        ->where(
                            function ($query){
                                return $query
                                        ->whereNull('episode.episstatus')
                                        ->orWhere('episode.episstatus','!=','C');
                            }
                        )
                        ->get();
        
        return $events = $this->getEvent($emergency);
        
    }
    
}