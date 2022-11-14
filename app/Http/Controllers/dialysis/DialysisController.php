<?php

namespace App\Http\Controllers\dialysis;

use Illuminate\Http\Request;
use stdClass;
use App\User;
use DB;
use Carbon\Carbon;
use Auth;
use Session;
use App\Http\Controllers\dialysis\Controller;

class DialysisController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){ 

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

        return view('dialysis.dialysis',compact('centers'));
    }

    public function table(Request $request)
    {   
        switch($request->action){
            
            //transaction stuff
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
            case 'get_table_patmedication_trx':
                return $this->get_table_patmedication_trx($request);
            case 'get_table_patmedication':
                return $this->get_table_patmedication($request);
            case 'get_table_addnotes':
                return $this->get_table_addnotes($request);
            case 'get_ownmed':
                return $this->get_ownmed($request);

            default:
                return 'error happen..';
        }
    }

    public function form(Request $request)
    {   
        switch($request->action){
            case 'patmedication_save':
                return $this->patmedication_save($request);
                break;

            case 'additionalnote_save':
                return $this->additionalnote_save($request);
                break;

            case 'medicationtype_change':
                return $this->medicationtype_change($request);
                break;

            case 'delete_ownmed':
                return $this->delete_ownmed($request);
                break;

            default:
                return 'error happen..';
        }
    }

    public function dialysis_event(Request $request){
        $emergency = DB::table('hisdb.episode')
                        ->whereRaw(
                          "(reg_date >= ? AND reg_date <= ?)", 
                          [
                             $request->start, 
                             $request->end
                         ])->get();
        
        $events = [];

        for ($i=1; $i <= 31; $i++) {
            $days = 0;
            $reg_date;
            foreach ($emergency as $key => $value) {
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

    public function get_data_dialysis(Request $request){

        switch ($request->action) {
            case 'get_dia_yearly':
                return $this->get_dia_yearly($request);
                break;

            case 'get_dia_monthly':
                return $this->get_dia_monthly($request);
                break;

            case 'get_dia_weekly':
                return $this->get_dia_weekly($request);
                break;

            case 'get_dia_daily':
                return $this->get_dia_daily($request);
                break;
        }
        
    }

    public function get_dia_yearly(Request $request){
        $post = [];
        if(!empty($request->year) && !empty($request->month)){

            $post = DB::table('hisdb.dialysis')
                    ->where('mrn','=',$request->mrn)
                    // ->where('episno','=',$request->episno)
                    ->whereYear('visit_date', '=', $request->year)
                    ->whereMonth('visit_date', '=', $request->month)
                    ->get();
        }

        foreach ($post as $key => $value) {
            $table_patmedication = DB::table('hisdb.patmedication as ptm') //ambil dari patmast balik
                            ->select('ptm.idno',
                                'ptm.chgcode as chg_code',
                                'chgmast.description as chg_desc',
                                'ptm.enteredby',
                                'ptm.verifiedby',
                                'ptm.qty as quantity',
                                'ptm.idno as status')

                            ->leftJoin('hisdb.chgmast', function($join) use ($request){
                                $join = $join->on('chgmast.chgcode', '=', 'ptm.chgcode')
                                                ->where('chgmast.compcode','=',session('compcode'));
                            })
                            
                            ->where('ptm.mrn' ,'=', $value->mrn)
                            // ->where('ptm.episno' ,'=', $request->episno)
                            ->whereDate('ptm.entereddate', $value->visit_date)
                            ->where('ptm.compcode','=',session('compcode'));

            if($table_patmedication->exists()){
                $value->table_patmedication =  $table_patmedication->get();
            }

        }

        $responce = new stdClass();
        $responce->data = $post;
        return json_encode($responce);
    }

    public function get_dia_monthly(Request $request){
        $post = [];
        if(!empty($request->date)){
            $carbon = new Carbon($request->date);

            $post = DB::table('hisdb.dialysis')
                    ->where('mrn','=',$request->mrn)
                    // ->where('episno','=',$request->episno)
                    ->whereYear('visit_date', '=', $carbon->year)
                    ->whereMonth('visit_date', '=', $carbon->month)
                    ->get();
        }

        foreach ($post as $key => $value) {
            $table_patmedication = DB::table('hisdb.patmedication as ptm') //ambil dari patmast balik
                            ->select('ptm.idno',
                                'ptm.chgcode as chg_code',
                                'chgmast.description as chg_desc',
                                'ptm.enteredby',
                                'ptm.verifiedby',
                                'ptm.qty as quantity',
                                'ptm.idno as status')

                            ->leftJoin('hisdb.chgmast', function($join) use ($request){
                                $join = $join->on('chgmast.chgcode', '=', 'ptm.chgcode')
                                                ->where('chgmast.compcode','=',session('compcode'));
                            })
                            
                            ->where('ptm.mrn' ,'=', $value->mrn)
                            // ->where('ptm.episno' ,'=', $request->episno)
                            ->whereDate('ptm.entereddate', $value->visit_date)
                            ->where('ptm.compcode','=',session('compcode'));

            if($table_patmedication->exists()){
                $value->table_patmedication =  $table_patmedication->get();
            }

        }

        $responce = new stdClass();
        $responce->data = $post;
        return json_encode($responce);
    }

    public function get_dia_weekly(Request $request){
        $post = [];
        if(!empty($request->datefrom)){
            $datefrom = new Carbon($request->datefrom);
            $dateto = new Carbon($request->dateto);

            $post = DB::table('hisdb.dialysis')
                    ->where('compcode',session('compcode'))
                    ->where('mrn','=',$request->mrn)
                    // ->where('episno','=',$request->episno)
                    ->whereBetween('visit_date', [$datefrom, $dateto])
                    ->take(3)
                    ->get();
        }

        foreach ($post as $key => $value) {
            $table_patmedication = DB::table('hisdb.patmedication as ptm') //ambil dari patmast balik
                            ->select('ptm.idno',
                                'ptm.chgcode as chg_code',
                                'chgmast.description as chg_desc',
                                'ptm.enteredby',
                                'ptm.verifiedby',
                                'ptm.qty as quantity',
                                'ptm.idno as status')

                            ->leftJoin('hisdb.chgmast', function($join) use ($request){
                                $join = $join->on('chgmast.chgcode', '=', 'ptm.chgcode')
                                                ->where('chgmast.compcode','=',session('compcode'));
                            })
                            
                            ->where('ptm.mrn' ,'=', $value->mrn)
                            // ->where('ptm.episno' ,'=', $request->episno)
                            ->whereDate('ptm.entereddate', $value->visit_date)
                            ->where('ptm.compcode','=',session('compcode'));

            if($table_patmedication->exists()){
                $value->table_patmedication =  $table_patmedication->get();
            }

        }

        $responce = new stdClass();
        $responce->data = $post;
        return json_encode($responce);
    }

    public function get_dia_daily(Request $request){
        $post = [];
        if(!empty($request->idno)){
            $post = DB::table('hisdb.dialysis')
                    ->where('idno','=',$request->idno)
                    ->first();
        }

        $responce = new stdClass();
        $responce->data = $post;
        return json_encode($responce);
    }

    public function save_dialysis(Request $request){

        $table = DB::table('hisdb.dialysis');
        $responce = new stdClass();
        try {

            $visit_date = new Carbon($request->visit_date_post);

            if($request->oper == 'add'){
                $array_insert = [
                    'compcode'=>session('compcode'),
                    'mrn'=>$request->mrn_post,
                    'episno'=>$request->episno_post,
                    'arrivalno'=>$request->arrivalno_post,
                    'visit_date'=>$visit_date
                ];


                $except_post = ['compcode','mrn','episno','arrivalno','visit_date','idno'];

                foreach ($_POST as $key => $value) {
                    if(!in_array($key, $except_post)){
                        if(strlen(trim($value)) > 0){
                            $array_insert[$key] = $value;
                        }else{
                            $array_update[$key] = null;
                        }
                    }
                }
        
                $idno = $table->insertGetId($array_insert);
                $responce->idno = $idno;
                $responce->arrivalno = $request->arrivalno_post;

            }else if($request->oper == 'edit'){
                if(empty($request->idno_post)){
                    throw new \Exception('Error edit because of no idno', 500);
                }

                $table->where('idno','=',$request->idno_post);

                $array_update = [];

                $except_post = ['compcode','mrn','episno','arrivalno','visit_date','idno'];

                foreach ($_POST as $key => $value) {
                    if(!in_array($key, $except_post)){
                        if(strlen(trim($value)) > 0){
                            $array_update[$key] = $value;
                        }else{
                            $array_update[$key] = null;
                        }
                    }
                }
        
                $table->update($array_update);

                $idno = $request->idno_post;
            }

            $this->check_hourly_chart($request,$idno);

            $responce->success = 'success';
            echo json_encode($responce);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }
        
    }

    public function check_hourly_chart(Request $request,$idno){
        $dialysis = DB::table('hisdb.dialysis')
                        ->where('idno','=',$request->idno)
                        ->first();

        $dialysis = (array)$dialysis;

        //check user_0
        if(
            empty($dialysis['0_tc']) &&
            empty($dialysis['0_bp']) &&
            empty($dialysis['0_pulse']) &&
            empty($dialysis['0_dh']) &&
            empty($dialysis['0_bfr']) &&
            empty($dialysis['0_vp']) &&
            empty($dialysis['0_tmp']) &&
            empty($dialysis['0_uv']) &&
            empty($dialysis['0_f'])
        ){
             $dialysis = DB::table('hisdb.dialysis')
                        ->where('idno','=',$request->idno)
                        ->update(['user_0' => null]);
        }
        //check user_1
        if(
            empty($dialysis['1_tc']) &&
            empty($dialysis['1_bp']) &&
            empty($dialysis['1_pulse']) &&
            empty($dialysis['1_dh']) &&
            empty($dialysis['1_bfr']) &&
            empty($dialysis['1_vp']) &&
            empty($dialysis['1_tmp']) &&
            empty($dialysis['1_uv']) &&
            empty($dialysis['1_f'])
        ){
             $dialysis = DB::table('hisdb.dialysis')
                        ->where('idno','=',$request->idno)
                        ->update(['user_1' => null]);
        }
        //check user_2
        if(
            empty($dialysis['2_tc']) &&
            empty($dialysis['2_bp']) &&
            empty($dialysis['2_pulse']) &&
            empty($dialysis['2_dh']) &&
            empty($dialysis['2_bfr']) &&
            empty($dialysis['2_vp']) &&
            empty($dialysis['2_tmp']) &&
            empty($dialysis['2_uv']) &&
            empty($dialysis['2_f'])
        ){
             $dialysis = DB::table('hisdb.dialysis')
                        ->where('idno','=',$request->idno)
                        ->update(['user_2' => null]);
        }
        //check user_3
        if(
            empty($dialysis['3_tc']) &&
            empty($dialysis['3_bp']) &&
            empty($dialysis['3_pulse']) &&
            empty($dialysis['3_dh']) &&
            empty($dialysis['3_bfr']) &&
            empty($dialysis['3_vp']) &&
            empty($dialysis['3_tmp']) &&
            empty($dialysis['3_uv']) &&
            empty($dialysis['3_f'])
        ){
             $dialysis = DB::table('hisdb.dialysis')
                        ->where('idno','=',$request->idno)
                        ->update(['user_3' => null]);
        }
        //check user_4
        if(
            empty($dialysis['4_tc']) &&
            empty($dialysis['4_bp']) &&
            empty($dialysis['4_pulse']) &&
            empty($dialysis['4_dh']) &&
            empty($dialysis['4_bfr']) &&
            empty($dialysis['4_vp']) &&
            empty($dialysis['4_tmp']) &&
            empty($dialysis['4_uv']) &&
            empty($dialysis['4_f'])
        ){
             $dialysis = DB::table('hisdb.dialysis')
                        ->where('idno','=',$request->idno)
                        ->update(['user_4' => null]);
        }
        //check user_5
        if(
            empty($dialysis['5_tc']) &&
            empty($dialysis['5_bp']) &&
            empty($dialysis['5_pulse']) &&
            empty($dialysis['5_dh']) &&
            empty($dialysis['5_bfr']) &&
            empty($dialysis['5_vp']) &&
            empty($dialysis['5_tmp']) &&
            empty($dialysis['5_uv']) &&
            empty($dialysis['5_f'])
        ){
             $dialysis = DB::table('hisdb.dialysis')
                        ->where('idno','=',$request->idno)
                        ->update(['user_5' => null]);
        }

    }

    public function save_dialysis_completed(Request $request){

        $table = DB::table('hisdb.dialysis');
        try {

            $chgtrx = DB::table('hisdb.chargetrx as trx')
                                ->join('hisdb.chgmast', function($join) use ($request){
                                    $join = $join->on('chgmast.chgcode', '=', 'trx.chgcode')
                                                    ->where('chgmast.compcode','=',session('compcode'))
                                                    ->whereNotNull('chgmast.dosecode');
                                })
                                ->where('trx.mrn','=',$request->mrn_post)
                                ->where('trx.episno','=',$request->episno_post)
                                ->where('trx.compcode','=',session('compcode'))
                                ->whereDate('trx.trxdate', $request->visit_date_post)
                                ->where('trx.recstatus','=',1)
                                ->where('trx.chgtype' ,'=', 'EP01')
                                ->whereNull('trx.patmedication');

            if($chgtrx->exists()){
                throw new \Exception('Please Verify all patmedication first before complete', 500);
            }


            if(empty($request->idno_post)){
                throw new \Exception('Error edit because of no idno', 500);
            }
            
            $table->where('idno','=',$request->idno_post);

            $array_update = [];

            $except_post = ['compcode','mrn','episno','arrivalno','visit_date','idno','access_placeholder'];

            foreach ($_POST as $key => $value) {
                if(!in_array($key, $except_post)){
                    if(!empty($value)){
                        $array_update[$key] = $value;
                    }
                }
            }
    
            $table->update($array_update);

            //update dialysis_episode complete
            $dialysis_episode = DB::table('hisdb.dialysis_episode')
                                    ->where('idno',$request->arrivalno_post)
                                    ->where('complete',0);

            if($dialysis_episode->exists()){
                DB::table('hisdb.dialysis_episode')
                    ->where('idno',$request->arrivalno_post)
                    ->update([
                        'complete' => 1
                    ]);
            }

            $responce = new stdClass();
            $responce->success = 'success';
            echo json_encode($responce);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
        
    }

    public function dialysis_transaction_save(Request $request){

        DB::beginTransaction();
        try {

            if($request->oper == 'edit'){

            }else if($request->oper == 'add'){

                $table = DB::table('hisdb.chargetrx');

                $chgmast = DB::table('hisdb.chgmast')
                            ->where('compcode','=',session('compcode'))
                            ->where('chgcode','=',$request->chg_desc)
                            ->first();

                $episode = DB::table('hisdb.episode')
                            ->where('compcode','=',session('compcode'))
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno)
                            ->first();
                            
                $isudept = $episode->regdept;

                if(empty($request->dialysis_episode_idno)){
                    $dialysis_episode = DB::table('hisdb.dialysis_episode')
                                            ->where('dialysis_episode.mrn',$request->mrn)
                                            ->where('dialysis_episode.episno',$request->episno)
                                            ->where('dialysis_episode.compcode','=',session('compcode'))
                                            ->whereDate('dialysis_episode.arrival_date',$request->trxdate);

                    if($dialysis_episode->exists()){
                        // throw new \Exception('Patient doesnt arrive for dialysis at date: '.Carbon::parse($request->trxdate)->format('d-m-Y'), 500);
                        $last_arrival_idno = $dialysis_episode->first()->idno;
                    }else{
                        $dialysis_episode = DB::table('hisdb.dialysis_episode')
                                            ->where('dialysis_episode.mrn',$request->mrn)
                                            ->where('dialysis_episode.episno',$request->episno)
                                            ->where('dialysis_episode.compcode','=',session('compcode'))
                                            ->orderBy('dialysis_episode.idno','DESC');

                        if($dialysis_episode->exists()){
                            $last_arrival_idno = $dialysis_episode->first()->idno;
                        }else{
                            throw new \Exception('Patient doesnt arrive yet', 500);
                        }

                    }

                }else{
                    $last_arrival_idno = $request->dialysis_episode_idno;
                }

                if($chgmast->chgtype == 'PKG'){
                    //check duplicate dialysis
                    $chgtrx = DB::table('hisdb.chargetrx')
                                ->where('mrn','=',$request->mrn)
                                ->where('episno','=',$request->episno)
                                ->where('compcode','=',session('compcode'))
                                ->where('trxdate','=', $request->trxdate)
                                ->where('recstatus','=',1)
                                ->where('chgtype','=','PKG');

                    if($chgtrx->exists()){
                        throw new \Exception('Patient already have dialysis for date: '.Carbon::parse($request->arrival_date)->format('d-m-Y'), 500);
                    }
                }else if($chgmast->chggroup == 'EP'){

                    $chgtrx = DB::table('hisdb.chargetrx')
                                ->where('mrn','=',$request->mrn)
                                ->where('episno','=',$request->episno)
                                ->where('compcode','=',session('compcode'))
                                ->where('recstatus','=',1)
                                ->where('trxdate','=', $request->trxdate)
                                ->where('chgtype','=','PKG');

                    if(!$chgtrx->exists()){
                        throw new \Exception('No dialysis for date: '.Carbon::parse($request->arrival_date)->format('d-m-Y').', Please add dialysis first!', 500);
                    }
                }

                $array_insert = [
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn,
                    'episno' => $request->episno,
                    'trxtype' => 'OE',
                    'trxdate' => $request->trxdate,
                    'chgcode' => $request->chg_desc,
                    'chggroup' =>  $chgmast->chggroup,
                    'chgtype' =>  $chgmast->chgtype,
                    'instruction' => $request->ins_desc,
                    'doscode' => $request->dos_desc,
                    'frequency' => $request->fre_desc,
                    'drugindicator' => $request->dru_desc,
                    'remarks' => $request->remarks,
                    'billflag' => '0',
                    'quantity' => $request->quantity,
                    'isudept' => $isudept,
                    'trxtime' => $request->trxtime,
                    'lastuser' => Auth::user()->username,
                    'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'recstatus' => 1
                ];

                $table->insert($array_insert);

                //check utk hd1 hd2
                $check_hd = $this->check_hd($request,$last_arrival_idno);
                if($check_hd->auto == true){

                    $chgmast = DB::table('hisdb.chgmast')
                        ->where('compcode','=',session('compcode'))
                        ->where('chgcode','=',$check_hd->chgcode)
                        ->first();

                    $array_insert = [
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'trxtype' => 'OE',
                        'trxdate' => $request->trxdate,
                        'chgcode' => $check_hd->chgcode,
                        'chggroup' => $chgmast->chggroup,
                        'chgtype' => $chgmast->chgtype,
                        'billflag' => '0',
                        'quantity' => 1,
                        'isudept' => $isudept,
                        'trxtime' => $request->trxtime,
                        'lastuser' => Auth::user()->username,
                        'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'recstatus' => 1
                    ];

                    $table->insert($array_insert);
                }

                //check utk epo3
                $check_mcr = $this->check_mcr($request,$chgmast->chgtype,$last_arrival_idno);
                if($check_mcr->auto == true){

                    $chgmast = DB::table('hisdb.chgmast')
                        ->where('compcode','=',session('compcode'))
                        ->where('chgcode','=',$check_mcr->chgcode)
                        ->first();

                    $array_insert = [
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'trxtype' => 'OE',
                        'trxdate' => $request->trxdate,
                        'chgcode' => $check_mcr->chgcode,
                        'chggroup' => $chgmast->chggroup,
                        'chgtype' => $chgmast->chgtype,
                        'billflag' => '0',
                        'quantity' => 1,
                        'isudept' => $isudept,
                        'trxtime' => $request->trxtime,
                        'lastuser' => Auth::user()->username,
                        'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'recstatus' => 1
                    ];

                    $table->insert($array_insert);
                }

                $this->updateorder($request,$last_arrival_idno);

            }else if($request->oper == 'del'){

                //cannot delete auto
                if($request->chg_desc == 'EP010005'){
                    throw new \Exception('Cannot delete non-stock micerra, delete stock micerra instead', 500);  
                }

                DB::table('hisdb.chargetrx')
                        ->where('id','=',$request->id)
                        ->update([
                            'recstatus' => 0,
                            'lastuser' => Auth::user()->username,
                            'lastupdate' => Carbon::now("Asia/Kuala_Lumpur")
                        ]);

                $chargetrx = DB::table('hisdb.chargetrx')
                                ->where('id','=',$request->id)
                                ->first();

                //check del hd
                $dialysis_pkgdtl = DB::table('hisdb.dialysis_pkgdtl')
                            ->where('pkgcode','EPO')
                            ->where('chgcode',$chargetrx->chgcode);

                if($dialysis_pkgdtl->exists()){
                    $dialysis_episode = DB::table('hisdb.dialysis_episode')
                        ->where('idno',$request->dialysis_episode_idno)
                        ->first(); 

                    if($dialysis_episode->hdstat>0){
                        $hdstillgot = DB::table('hisdb.chargetrx')
                                        ->where('mrn','=',$request->mrn)
                                        ->where('episno','=',$request->episno)
                                        ->where('chgcode',$chargetrx->chgcode)
                                        ->where('recstatus',1);

                        if(!$hdstillgot->exists()){
                            DB::table('hisdb.chargetrx')
                                ->where('mrn','=',$request->mrn)
                                ->where('episno','=',$request->episno)
                                ->where('chgcode','=',$dialysis_pkgdtl->first()->epocode)
                                ->where('recstatus',1)
                                ->update([
                                    'recstatus' => 0,
                                    'lastuser' => Auth::user()->username,
                                    'lastupdate' => Carbon::now("Asia/Kuala_Lumpur")
                                ]);

                            DB::table('hisdb.dialysis_episode')
                                ->where('idno',$request->dialysis_episode_idno)
                                ->update(['hdstat' => 0]);
                        }

                    }
                }

                //check del mcr
                $dialysis_pkgdtl = DB::table('hisdb.dialysis_pkgdtl')
                            ->where('pkgcode','MICERRA')
                            ->where('chgcode',$chargetrx->chgcode);

                if($dialysis_pkgdtl->exists()){
                    $dialysis_episode = DB::table('hisdb.dialysis_episode')
                        ->where('idno',$request->dialysis_episode_idno)
                        ->first();

                    if($dialysis_episode->mcrstat>0){
                        // $oldmcr = intval($dialysis_episode->mcrstat);
                        // $newmcr = intval($oldmcr)-1;

                        // if($newmcr<0){
                        //     $newmcr = 0;
                        // }

                        // if($newmcr == 0){
                        //     $del_array=['mcrstat' => $newmcr,'mcrtype' => ''];
                        // }else{
                        //     $del_array=['mcrstat' => $newmcr];
                        // }
                        $del_array=['mcrstat' => 0,'mcrtype' => ''];
                        DB::table('hisdb.dialysis_episode')
                            ->where('idno',$request->dialysis_episode_idno)
                            ->update($del_array);

                        DB::table('hisdb.chargetrx')
                            ->where('mrn','=',$request->mrn)
                            ->where('episno','=',$request->episno)
                            ->where('chgcode','=','EP010005')
                            ->delete();
                            // ->update([
                            //     'recstatus' => 0,
                            //     'lastuser' => Auth::user()->username,
                            //     'lastupdate' => Carbon::now("Asia/Kuala_Lumpur")
                            // ]);

                    }

                }
            }
        
            $responce = new stdClass();
            $responce->success = 'success';
            echo json_encode($responce);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function change_status(Request $request){
        try {
            $table = DB::table('hisdb.episode')
                        ->where('mrn','=',$request->mrn)
                        ->where('episno','=',$request->episno)
                        ->update([
                            'ordercomplete' => 1,
                        ]);
            

            $responce = new stdClass();
            $responce->success = 'success';
            echo json_encode($responce);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function save_epis_dialysis(Request $request){
        DB::beginTransaction();
        
        try {
            $table = DB::table('hisdb.dialysis_episode');
            if($request->oper == 'add'){

                //check if date,mrn,episno duplicate
                $dialysis_epis = DB::table('hisdb.dialysis_episode')
                                    ->where('compcode',session('compcode'))
                                    ->where('mrn',$request->mrn)
                                    ->where('episno',$request->episno)
                                    ->whereDate('arrival_date',$request->arrival_date);


                if($dialysis_epis->exists()){
                    throw new \Exception('Patient already arrive for date: '.Carbon::parse($request->arrival_date)->format('d-m-Y'), 500);
                }

                $dialysis_epis = DB::table('hisdb.dialysis_episode')
                                    ->where('compcode',session('compcode'))
                                    ->where('mrn',$request->mrn)
                                    ->where('episno',$request->episno);

                if($dialysis_epis->exists()){
                    $lineno_ = intval($dialysis_epis->max('lineno_')) + 1;

                    $dialysis_epis_latest = DB::table('hisdb.dialysis_episode')
                                    ->where('compcode',session('compcode'))
                                    ->where('mrn',$request->mrn)
                                    ->where('episno',$request->episno)
                                    ->where('lineno_',intval($dialysis_epis->max('lineno_')));

                    $mcrstat = $dialysis_epis_latest->first()->mcrstat;
                    $hdstat = $dialysis_epis_latest->first()->hdstat;
                }else{
                    $lineno_ = 1;
                    $mcrstat = 0;
                    $hdstat = 0;
                }

                $array_insert = [
                    'compcode'=>session('compcode'),
                    'mrn'=>$request->mrn,
                    'episno'=>$request->episno,
                    'lineno_'=>$lineno_,
                    'mcrstat'=>$mcrstat,
                    'hdstat'=>$hdstat,
                    'arrival_date'=>$request->arrival_date,
                    'arrival_time'=>$request->arrival_time,
                    'packagecode'=>$request->packagecode,
                    'order'=>0,
                    'complete'=>0
                ];
        
                $latest_idno = $table->insertGetId($array_insert);

                DB::table('hisdb.episode')
                        ->where('mrn',$request->mrn)
                        ->where('episno',$request->episno)
                        ->update([
                            'lastarrivalno' => $latest_idno,
                            'lastarrivaldate' => $request->arrival_date,
                            'lastarrivaltime' => $request->arrival_time,
                        ]);

            }else if($request->oper == 'autoadd'){
                //check if date,mrn,episno duplicate
                $dialysis_epis = DB::table('hisdb.dialysis_episode')
                                    ->where('compcode',session('compcode'))
                                    ->where('mrn',$request->mrn)
                                    ->where('episno',$request->episno)
                                    ->whereDate('arrival_date',Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d'));

                if(!$dialysis_epis->exists()){
                    $dialysis_epis = DB::table('hisdb.dialysis_episode')
                                    ->where('compcode',session('compcode'))
                                    ->where('mrn',$request->mrn)
                                    ->where('episno',$request->episno);

                    if($dialysis_epis->exists()){
                        $lineno_ = intval($dialysis_epis->max('lineno_')) + 1;

                        $dialysis_epis_latest = DB::table('hisdb.dialysis_episode')
                                        ->where('compcode',session('compcode'))
                                        ->where('mrn',$request->mrn)
                                        ->where('episno',$request->episno)
                                        ->where('lineno_',intval($dialysis_epis->max('lineno_')));

                        $mcrstat = $dialysis_epis_latest->first()->mcrstat;
                        $hdstat = $dialysis_epis_latest->first()->hdstat;
                        $packagecode = $dialysis_epis_latest->first()->packagecode;
                    }else{
                        $lineno_ = 1;
                        $mcrstat = 0;
                        $hdstat = 0;
                        $packagecode = 'EPO';
                    }

                    $array_insert = [
                        'compcode'=>session('compcode'),
                        'mrn'=>$request->mrn,
                        'episno'=>$request->episno,
                        'lineno_'=>$lineno_,
                        'mcrstat'=>$mcrstat,
                        'hdstat'=>$hdstat,
                        'arrival_date'=>Carbon::now("Asia/Kuala_Lumpur"),
                        'arrival_time'=>Carbon::now("Asia/Kuala_Lumpur"),
                        'packagecode'=>$packagecode,
                        'order'=>0,
                        'complete'=>0
                    ];
            
                    $latest_idno = $table->insertGetId($array_insert);

                    DB::table('hisdb.episode')
                        ->where('mrn',$request->mrn)
                        ->where('episno',$request->episno)
                        ->update([
                            'lastarrivalno' => $latest_idno,
                            'lastarrivaldate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'lastarrivaltime' => Carbon::now("Asia/Kuala_Lumpur")
                        ]);
                }

                
            }else if($request->oper == 'edit'){
                $dialysis_episode = DB::table('hisdb.dialysis_episode')
                        ->where('idno',$request->idno);

                if($dialysis_episode->exists()){
                    DB::table('hisdb.dialysis_episode')
                        ->where('idno',$request->idno)
                        ->update([
                            'packagecode' => $request->packagecode
                        ]);
                }
            }else if($request->oper == 'del'){
                $dialysis_episode = DB::table('hisdb.dialysis_episode')
                        ->where('idno',$request->idno);

                if($dialysis_episode->exists()){
                    $date = Carbon::parse($request->arrival_date);

                    $isToday = $date->isToday();
                    if($isToday){
                        $chargetrx = DB::table('hisdb.chargetrx')
                                        ->where('mrn',$request->mrn)
                                        ->where('episno',$request->episno)
                                        ->whereDate('trxdate',$date)
                                        ->where('recstatus',1);

                        if(!$chargetrx->exists()){
                            DB::table('hisdb.dialysis_episode')
                                ->where('idno',$request->idno)
                                ->delete();
                        }else{
                            throw new \Exception('Error: patient already has order entry, cant delete the record', 500); 
                        }
                        
                    }else{
                        throw new \Exception('Error: Cant delete record that has arrival date not today', 500); 
                    }


                    
                }
            }

            $responce = new stdClass();
            $responce->success = 'success';
            echo json_encode($responce);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function check_pt_mode(Request $request){

        $responce = new stdClass();

        $dialysis_b4 = DB::table('hisdb.dialysis')
                        ->select('idno','visit_date')
                        ->where('mrn',$request->mrn)
                        ->where('arrivalno','!=',$request->dialysis_episode_idno)
                        ->orderBy('idno','DESC');

        if($dialysis_b4->exists()){
            $datab4 = [];
            foreach ($dialysis_b4->get() as $key => $value) {
                $obj_ = new stdClass();
                $obj_->idno = $value->idno;
                $obj_->visit_date = Carbon::parse($value->visit_date)->format('d-m-Y');
                array_push($datab4,$obj_);
            }
            $responce->datab4 = $datab4;
        }
        
        //check dkt dialysis_episode ada order ke tak
        $dialysis_episode = DB::table('hisdb.dialysis_episode')
                            ->where('idno',$request->dialysis_episode_idno)
                            ->where('order',1);

        $other_data = $this->get_data_for_dialysis(
                                $request->mrn,
                                $request->episno,
                                $request->dialysis_episode_idno);

        $responce->other_data = $other_data;

        if($dialysis_episode->exists()){
            //check dkt dialysis ada data ke tak hari tu
            $dialysis = DB::table('hisdb.dialysis')
                            ->where('arrivalno',$request->dialysis_episode_idno);
                            
            if($dialysis->exists()){
                //populate data hari tu
                $responce->mode = 'edit';
                $responce->data = $dialysis->first();

            }else{
                //add new dialysis daily
                $responce->mode = 'add';
            }

        }else{
            //kalu xde order xboleh add dialysis daily
            $responce->mode = 'disableAll';
        }


        echo json_encode($responce); 
    }

    public function updateorder(Request $request,$last_arrival_idno){
        //update dialysis_episode charges
        $dialysis_episode = DB::table('hisdb.dialysis_episode')
                                ->where('idno',$last_arrival_idno)
                                ->where('order',0);

        if($dialysis_episode->exists()){
            DB::table('hisdb.dialysis_episode')
                ->where('idno',$last_arrival_idno)
                ->update([
                    'order' => 1
                ]);
        }
    }

    public function check_hd(Request $request,$last_arrival_idno){
        $responce = new stdClass();      

        $dialysis_episode = DB::table('hisdb.dialysis_episode')
                            ->where('idno',$last_arrival_idno)
                            ->first();  

        //check ada dlm case
        $dialysis_pkgdtl = DB::table('hisdb.dialysis_pkgdtl')
                            ->where('pkgcode','EPO')
                            ->where('chgcode',$request->chg_desc);

        if($dialysis_pkgdtl->exists()){

            if($dialysis_episode->hdstat == 0){

                DB::table('hisdb.dialysis_episode')
                    ->where('idno',$last_arrival_idno)
                    ->update([
                        'hdstat' => 1
                    ]);

                $responce->auto = true;
                $responce->chgcode = $dialysis_pkgdtl->first()->epocode;

                return $responce;

            }
        }

        $responce->auto = false;
        return $responce;

    }


    public function check_mcr(Request $request,$chgtype,$last_arrival_idno){
        $responce = new stdClass();

        $dialysis_episode = DB::table('hisdb.dialysis_episode')
                                ->where('idno',$last_arrival_idno)
                                ->first();

        $mcrstat = $dialysis_episode->mcrstat;
        $mcrtype = $dialysis_episode->mcrtype;

        if($mcrstat>0 && $chgtype=='PKG'){

            $dialysis_pkgdtl = DB::table('hisdb.dialysis_pkgdtl')
                            ->where('pkgcode','MICERRA')
                            ->where('chgcode',$mcrtype);

            if($dialysis_pkgdtl->exists()){

                if($mcrstat<=intval($dialysis_pkgdtl->first()->volume2)){
                    DB::table('hisdb.dialysis_episode')
                        ->where('idno',$last_arrival_idno)
                        ->update([
                            'mcrstat' => $mcrstat + 1
                        ]);

                    $responce->auto = true;
                    $responce->chgcode = $dialysis_pkgdtl->first()->epocode;

                    return $responce;
                }
                
            }
            
        }else if($mcrstat == 0 && $chgtype=='EP01'){

            //check ada dlm case
            $dialysis_pkgdtl = DB::table('hisdb.dialysis_pkgdtl')
                            ->where('pkgcode','MICERRA')
                            ->where('chgcode',$request->chg_desc);

            if($dialysis_pkgdtl->exists()){

                DB::table('hisdb.dialysis_episode')
                    ->where('idno',$last_arrival_idno)
                    ->update([
                        'mcrstat' => 1,
                        'mcrtype' => $request->chg_desc
                    ]);

                $responce->auto = false;
                return $responce;
                
            }

        }

        $responce->auto = false;
        return $responce;
    }

    public function get_data_for_dialysis($mrn,$episno,$dialysis_episode_idno){
        $responce = new stdClass();

        $episode = DB::table('hisdb.episode')
                    ->whereNotNull('dry_weight')
                    ->whereNotNull('duration_hd')
                    ->where('mrn',$mrn)
                    ->orderBy('idno','desc');

        if($episode->exists()){
            $responce->dry_weight = $episode->first()->dry_weight;
            $responce->duration_hd = $episode->first()->duration_hd;
        }else{
            $responce->dry_weight = '';
            $responce->duration_hd = '';
        }

        $responce->initiated_by = Auth::user()->username;
        $responce->prev_post_weight = 0;
        $responce->last_visit = '';

        if($dialysis_episode_idno == 0){
            return $responce;
        }

        $dialysis_episode = DB::table('hisdb.dialysis_episode')
                                ->where('idno',$dialysis_episode_idno)
                                ->first();

        $dialysis = DB::table('hisdb.dialysis')
                        ->where('arrivalno',$dialysis_episode_idno);

        if($dialysis->exists()){
            $dialysis = $dialysis->first();

            $responce->prev_post_weight = $dialysis->post_weight;
            $responce->last_visit = $dialysis->visit_date;

        }else{
            $dialysis = DB::table('hisdb.dialysis')
                                ->where('mrn',$dialysis_episode->mrn)
                                ->latest('visit_date');

            if($dialysis->exists()){
                $responce->prev_post_weight = $dialysis->first()->post_weight;
                $responce->last_visit = $dialysis->first()->visit_date;
            }
        }

        return $responce;             

    }

    public function verifyuser_dialysis(Request $request){
        $responce = new stdClass();

        $verify = DB::table('sysdb.users')
                    ->where('compcode',session('compcode'))
                    ->where('username',$request->username)
                    ->where('password',$request->password)
                    ->where('username','!=',session('username'));

        if($verify->exists()){
            $responce->success = 'success';
        }else{
            $responce->success = 'fail';
        }

        echo json_encode($responce); 
    }

    public function verifyuser_admin_dialysis(Request $request){
        $responce = new stdClass();

        $verify = DB::table('sysdb.users')
                    ->where('compcode',session('compcode'))
                    ->where('username',$request->username)
                    ->where('password',$request->password)
                    ->where('groupid','ADMIN');

        if($verify->exists()){
            $responce->success = 'success';
        }else{
            $responce->success = 'fail';
        }

        echo json_encode($responce); 
    }

    public function get_transaction_table($request){
        if($request->rows == null){
            $request->rows = 100;
        }

        $table_chgtrx = DB::table('hisdb.chargetrx as trx') //ambil dari patmast balik
                            ->select('trx.id',
                                'trx.trxdate',
                                'trx.trxtime',
                                'trx.chgcode as chg_code',
                                'trx.quantity',
                                'trx.remarks',
                                'trx.instruction as ins_code',
                                'trx.doscode as dos_code',
                                'trx.frequency as fre_code',
                                'trx.drugindicator as dru_code',
                                'trx.patmedication as patmedication',

                                'chgmast.description as chg_desc',
                                'instruction.description as ins_desc',
                                'dose.dosedesc as dos_desc',
                                'freq.freqdesc as fre_desc',
                                'drugindicator.drugindcode as dru_desc')

                            ->where('trx.mrn' ,'=', $request->mrn)
                            ->where('trx.episno' ,'=', $request->episno)
                            ->where('trx.recstatus' ,'=', 1)
                            ->where('trx.compcode','=',session('compcode'));

        if($request->isudept != 'CLINIC'){
            $table_chgtrx->where('trx.isudept','=',$request->isudept);
        }

        $table_chgtrx = $table_chgtrx
                            ->leftJoin('hisdb.chgmast', function($join) use ($request){
                                $join = $join->on('chgmast.chgcode', '=', 'trx.chgcode')
                                                ->where('chgmast.compcode','=',session('compcode'));
                            })
                            ->leftJoin('hisdb.instruction', function($join) use ($request){
                                $join = $join->on('instruction.inscode', '=', 'trx.instruction')
                                                ->where('instruction.compcode','=',session('compcode'));
                            })
                            ->leftJoin('hisdb.freq', function($join) use ($request){
                                $join = $join->on('freq.freqcode', '=', 'trx.frequency')
                                                ->where('freq.compcode','=',session('compcode'));
                            })
                            ->leftJoin('hisdb.dose', function($join) use ($request){
                                $join = $join->on('dose.dosecode', '=', 'trx.doscode')
                                                ->where('dose.compcode','=',session('compcode'));
                            })
                            ->leftJoin('hisdb.drugindicator', function($join) use ($request){
                                $join = $join->on('drugindicator.drugindcode', '=', 'trx.drugindicator')
                                                ->where('drugindicator.compcode','=',session('compcode'));
                            });

        if(!empty($request->sidx)){
            if($request->sidx == 'id'){
                $table_chgtrx = $table_chgtrx->orderBy('trx.id','desc');
            }else{
                $table_chgtrx = $table_chgtrx->orderBy($request->sidx, $request->sord);
            }
        }else{
            $table_chgtrx = $table_chgtrx->orderBy('trx.id','desc');
        }
                            // ->leftJoin('hisdb.chgmast','chgmast.chgcode','=','trx.chgcode')
                            // ->leftJoin('hisdb.instruction','instruction.inscode','=','trx.instruction')
                            // ->leftJoin('hisdb.freq','freq.freqcode','=','trx.frequency')
                            // ->leftJoin('hisdb.dose','dose.dosecode','=','trx.doscode')
                            // ->leftJoin('hisdb.drugindicator','drugindicator.drugindcode','=','trx.drugindicator')
                            //->orderBy('trx.id','desc');

        //////////paginate/////////
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

    public function get_chgcode(Request $request){
        $dialysis_episode = DB::table('hisdb.dialysis_episode')
                                ->where('idno',$request->arrivalno);

        if(empty($request->arrivalno)){
            $dialysis_episode = DB::table('hisdb.dialysis_episode')
                                    ->where('dialysis_episode.mrn',$request->mrn)
                                    ->where('dialysis_episode.episno',$request->episno)
                                    ->where('dialysis_episode.compcode','=',session('compcode'))
                                    ->orderBy('dialysis_episode.idno', 'DESC');
        }else{
            $dialysis_episode = DB::table('hisdb.dialysis_episode')
                                ->where('idno',$request->arrivalno);
        }

        if(!$dialysis_episode->exists()){
            throw new \Exception('Error: arrivalno doesnt exist', 500); 
        }

        $dialysis_episode = $dialysis_episode->first();


        $data = DB::table('hisdb.chgmast as cm')
                    ->select('cm.chgcode as code','cm.description as description','cm.doseqty','cm.dosecode','d.dosedesc as dosecode_','cm.freqcode','f.freqdesc as freqcode_','cm.instruction','i.description as instruction_')
                    ->leftJoin('hisdb.dose as d', function($join) use ($request){
                        $join = $join->on('d.dosecode', '=', 'cm.dosecode')
                                        ->where('d.compcode','=',session('compcode'));
                    })
                    ->leftJoin('hisdb.freq as f', function($join) use ($request){
                        $join = $join->on('f.freqcode', '=', 'cm.freqcode')
                                        ->where('f.compcode','=',session('compcode'));
                    })
                    ->leftJoin('hisdb.instruction as i', function($join) use ($request){
                        $join = $join->on('i.inscode', '=', 'cm.instruction')
                                        ->where('i.compcode','=',session('compcode'));
                    })
                    // ->leftJoin('hisdb.dose as d','d.dosecode','=','cm.dosecode')
                    // ->leftJoin('hisdb.freq as f','f.freqcode','=','cm.freqcode')
                    // ->leftJoin('hisdb.instruction as i','i.inscode','=','cm.instruction')
                    ->where('cm.auto','=',0)
                    ->whereIn('cm.chggroup',['HD','EP'])
                    ->where('cm.compcode','=',session('compcode'))
                    ->where('cm.active','=',1);

        if($dialysis_episode->packagecode == 'EPO'){
            $data = $data->where('cm.micerra','!=',1);
        }else if($dialysis_episode->mcrstat>0){
            $data = $data->where('cm.micerra','!=',1);
        }

        // if(Session::has('chggroup')){
        //     $data = $data->where('chggroup','=',session('chggroup'));
        // }

        $data = $data->orderBy('cm.chgcode', 'ASC');

        if(!empty($request->search)){
            $data = $data->where('cm.description','LIKE','%'.$request->search.'%')->first();
        }else{
            $data = $data->get();
        }
        
        $responce = new stdClass();
        $responce->data = $data;
        return json_encode($responce);
        
    }

    public function get_ownmed(Request $request){
        $data = DB::table('hisdb.chgmast as cm')
                ->select('cm.chgcode as code','cm.description as description','cm.doseqty','cm.dosecode','d.dosedesc as dosecode_','cm.freqcode','f.freqdesc as freqcode_','cm.instruction','i.description as instruction_')
                ->leftJoin('hisdb.dose as d', function($join) use ($request){
                    $join = $join->on('d.dosecode', '=', 'cm.dosecode')
                                    ->where('d.compcode','=',session('compcode'));
                })
                ->leftJoin('hisdb.freq as f', function($join) use ($request){
                    $join = $join->on('f.freqcode', '=', 'cm.freqcode')
                                    ->where('f.compcode','=',session('compcode'));
                })
                ->leftJoin('hisdb.instruction as i', function($join) use ($request){
                    $join = $join->on('i.inscode', '=', 'cm.instruction')
                                    ->where('i.compcode','=',session('compcode'));
                })
                ->where('cm.chggroup','UM')
                ->where('cm.compcode','=',session('compcode'))
                ->where('cm.active','=',1);

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
                ->select('drugindcode as code','description as description',DB::raw('null as doseqty'),DB::raw('null as dosecode'),DB::raw('null as dosecode_'),DB::raw('null as freqcode'),DB::raw('null as freqcode_'),DB::raw('null as instruction'),DB::raw('null as instruction_'));

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
                ->select('freqcode as code','freqdesc as description',DB::raw('null as doseqty'),DB::raw('null as dosecode'),DB::raw('null as dosecode_'),DB::raw('null as freqcode'),DB::raw('null as freqcode_'),DB::raw('null as instruction'),DB::raw('null as instruction_'))
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
                ->select('dosecode as code','dosedesc as description',DB::raw('null as doseqty'),DB::raw('null as dosecode'),DB::raw('null as dosecode_'),DB::raw('null as freqcode'),DB::raw('null as freqcode_'),DB::raw('null as instruction'),DB::raw('null as instruction_'))
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
                ->select('inscode as code','description as description',DB::raw('null as doseqty'),DB::raw('null as dosecode'),DB::raw('null as dosecode_'),DB::raw('null as freqcode'),DB::raw('null as freqcode_'),DB::raw('null as instruction'),DB::raw('null as instruction_'))
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

    public function get_table_patmedication_trx(Request $request){

        $table_patmedication_trx = DB::table('hisdb.chargetrx as trx') //ambil dari patmast balik
                            ->select('trx.id',
                                'trx.mrn',
                                'trx.episno',
                                'chgmast.description as chg_desc',
                                'trx.chgcode as chg_code',
                                'trx.quantity',
                                'trx.instruction as ins_code',
                                'trx.doscode as dos_code',
                                'trx.frequency as fre_code',
                                'instruction.description as ins_desc',
                                'dose.dosedesc as dos_desc',
                                'freq.freqdesc as fre_desc')


                            ->join('hisdb.chgmast', function($join) use ($request){
                                $join = $join->on('chgmast.chgcode', '=', 'trx.chgcode')
                                                ->where('chgmast.compcode','=',session('compcode'))
                                                ->whereNotNull('chgmast.dosecode');
                            })
                            ->leftJoin('hisdb.instruction', function($join) use ($request){
                                $join = $join->on('instruction.inscode', '=', 'trx.instruction')
                                                ->where('instruction.compcode','=',session('compcode'));
                            })
                            ->leftJoin('hisdb.freq', function($join) use ($request){
                                $join = $join->on('freq.freqcode', '=', 'trx.frequency')
                                                ->where('freq.compcode','=',session('compcode'));
                            })
                            ->leftJoin('hisdb.dose', function($join) use ($request){
                                $join = $join->on('dose.dosecode', '=', 'trx.doscode')
                                                ->where('dose.compcode','=',session('compcode'));
                            })

                            ->where('trx.mrn' ,'=', $request->mrn)
                            ->where('trx.episno' ,'=', $request->episno)
                            ->where('trx.compcode','=',session('compcode'))
                            ->where('trx.recstatus','=',1)
                            ->where('trx.chgtype' ,'=', 'EP01')
                            ->whereNull('trx.patmedication')
                            ->whereDate('trx.trxdate', $request->date)
                            ->orderBy('trx.id','desc');

        $responce = new stdClass();
        $responce->data = $table_patmedication_trx->get();

        return json_encode($responce);
        
    }

    public function get_table_patmedication(Request $request){

        $table_patmedication = DB::table('hisdb.patmedication as ptm') //ambil dari patmast balik
                            ->select('ptm.idno',
                                'ptm.chgcode as chg_code',
                                'chgmast.description as chg_desc',
                                'ptm.enteredby',
                                'ptm.verifiedby',
                                'instruction.description as ins_desc',
                                'dose.dosedesc as dos_desc',
                                'freq.freqdesc as fre_desc',
                                'ptm.qty as quantity',
                                'ptm.idno as status',
                                'ptm.ownmed')

                            ->leftJoin('hisdb.chgmast', function($join) use ($request){
                                $join = $join->on('chgmast.chgcode', '=', 'ptm.chgcode')
                                                ->where('chgmast.compcode','=',session('compcode'));
                            })
                            ->leftJoin('hisdb.instruction', function($join) use ($request){
                                $join = $join->on('instruction.inscode', '=', 'ptm.instruction')
                                                ->where('instruction.compcode','=',session('compcode'));
                            })
                            ->leftJoin('hisdb.freq', function($join) use ($request){
                                $join = $join->on('freq.freqcode', '=', 'ptm.freq')
                                                ->where('freq.compcode','=',session('compcode'));
                            })
                            ->leftJoin('hisdb.dose', function($join) use ($request){
                                $join = $join->on('dose.dosecode', '=', 'ptm.dose')
                                                ->where('dose.compcode','=',session('compcode'));
                            })
                            
                            ->where('ptm.mrn' ,'=', $request->mrn)
                            ->where('ptm.episno' ,'=', $request->episno)
                            ->whereDate('ptm.entereddate', $request->date)
                            ->where('ptm.compcode','=',session('compcode'))
                            ->orderBy('ptm.idno','desc');

        $responce = new stdClass();
        $responce->data = $table_patmedication->get();

        return json_encode($responce);
        
    } 

    public function patmedication_save(Request $request){
        DB::beginTransaction();

        try {
            if($request->oper == 'add'){
                $table = DB::table('hisdb.patmedication');


                $chargetrx = DB::table('hisdb.chargetrx')
                        ->where('id',$request->chgtrx_idno)
                        ->first();

                $array_insert = [
                    'compcode'=>session('compcode'),
                    'mrn'=>$request->mrn,
                    'episno'=>$request->episno,
                    'entereddate'=>Carbon::now("Asia/Kuala_Lumpur"),
                    'enteredtime'=>Carbon::now("Asia/Kuala_Lumpur"),
                    'enteredby'=>session('username'),
                    'adduser'=>session('username'),
                    'adddate'=>Carbon::now("Asia/Kuala_Lumpur"),
                    'qty'=>$chargetrx->quantity,
                    'verifiedby'=>$request->verifiedby,
                    'dose'=>$chargetrx->doscode,
                    'freq'=>$chargetrx->frequency,
                    'instruction'=>$chargetrx->instruction,
                    'chgcode'=>$chargetrx->chgcode,
                    'auditno'=>$request->chgtrx_idno
                ];
        
                $table->insert($array_insert);

                DB::table('hisdb.chargetrx')
                        ->where('id',$request->chgtrx_idno)
                        ->update([
                            'patmedication' => '1'
                        ]);

            }else if($request->oper == 'del'){

                // if(!empty($request->auditno)){
                //     DB::table('hisdb.chargetrx')
                //         ->where('id',$request->auditno)
                //         ->update([
                //             'patmedication' => null
                //         ]);

                //     $table = DB::table('hisdb.patmedication');

                //     DB::table('hisdb.patmedication')
                //         ->where('chgcode',$request->chgtrx_idno)
                //         ->delete();
                // }
                
            }else if($request->oper == 'ownmed'){

                if(empty($request->chgcode)){
                    throw new \Exception('chgcode cant be empty', 500);
                }

                $chgmast = DB::table('hisdb.chgmast')
                        ->where('compcode',session('compcode'))
                        ->where('active','1')
                        ->where('chgcode',$request->chgcode);

                if(!$chgmast->exists()){
                    throw new \Exception('chgmast xde', 500);
                }
                $chgmast = $chgmast->first();
                
                $table = DB::table('hisdb.chargeown');
                $array_insert = [
                    'compcode' => session('compcode'),
                    'mrn' => $request->mrn,
                    'episno' => $request->episno,
                    'trxtype' => 'OE',
                    'trxdate' => $request->date,
                    'chgcode' => $chgmast->chgcode,
                    'chggroup' =>  $chgmast->chggroup,
                    'chgtype' =>  $chgmast->chgtype,
                    'instruction' => $chgmast->instruction,
                    'doscode' => $chgmast->dosecode,
                    'frequency' => $chgmast->freqcode,
                    'billflag' => '0',
                    'quantity' => $request->quantity,
                    'isudept' => session('dept'),
                    'trxtime' => Carbon::now("Asia/Kuala_Lumpur"),
                    'lastuser' => session('username'),
                    'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'recstatus' => 1
                ];

                $idno_chargeown = $table->insertGetId($array_insert);

                $table = DB::table('hisdb.patmedication');
                $array_insert = [
                    'compcode'=>session('compcode'),
                    'mrn'=>$request->mrn,
                    'episno'=>$request->episno,
                    'entereddate'=> $request->date,
                    'enteredtime'=>Carbon::now("Asia/Kuala_Lumpur"),
                    'enteredby'=>session('username'),
                    'adduser'=>session('username'),
                    'adddate'=>Carbon::now("Asia/Kuala_Lumpur"),
                    'qty'=>$request->quantity,
                    'verifiedby'=>$request->verifiedby,
                    'dose'=>$chgmast->dosecode,
                    'freq'=>$chgmast->freqcode,
                    'instruction'=>$chgmast->instruction,
                    'chgcode'=>$chgmast->chgcode,
                    'auditno'=>$idno_chargeown,
                    'remarks'=>'ownmed',
                    'ownmed'=>'1'
                ];
        
                $table->insert($array_insert);
                
            }

            $responce = new stdClass();
            $responce->success = 'success';
            echo json_encode($responce);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
        
    }

    public function additionalnote_save(Request $request){

        DB::beginTransaction();

        try {
            if(empty($request->additionalnote)){
                throw new \Exception('Note cant be empty', 500);
            }
            if(empty($request->arrivalno)){
                throw new \Exception('Save patient first before making notes', 500);
            }

            DB::table('hisdb.dialysis_note')
                    ->insert([
                        'compcode' => session('compcode'),
                        'mrn' => $request->mrn,
                        'episno' => $request->episno,
                        'arrivalno' => $request->arrivalno,
                        'additionalnote' => $request->additionalnote,
                        'adduser' => session('username'),
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
        
    }

    public function medicationtype_change(Request $request){

        DB::beginTransaction();

        try {
            if(empty($request->dialysis_episode_idno)){
                throw new \Exception('Patient doesnt have episode idno', 500);
            }

            $dialysis_episode = DB::table('hisdb.dialysis_episode')
                                    ->where('idno',$request->dialysis_episode_idno);

            if(!$dialysis_episode->exists()){
                throw new \Exception('Patient doesnt have dialysis episode', 500);
            }
            
            $dialysis_episode
                    ->update([
                        'packagecode' => $request->packagecode
                    ]);

            $responce = new stdClass();
            $responce->success = 'success';
            echo json_encode($responce);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
        
    }

    public function delete_ownmed(Request $request){

        DB::beginTransaction();

        try {
            if(empty($request->idno)){
                throw new \Exception('no idno', 500);
            }

            $patmedication = DB::table('hisdb.patmedication')
                                    ->where('idno',$request->idno);

            if(!$patmedication->exists()){
                throw new \Exception('no patmedication', 500);
            }
            
            $patmedication
                    ->delete();

            $responce = new stdClass();
            $responce->success = 'success';
            echo json_encode($responce);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
        
    }

    public function get_table_addnotes(Request $request){

        $table = DB::table('hisdb.dialysis_note')
                        ->where('mrn',$request->mrn)
                        ->where('episno',$request->episno)
                        ->where('arrivalno',$request->arrivalno);

        $table = $table->orderBy($request->sidx, $request->sord);

        //////////paginate/////////
        $paginate = $table->paginate($request->rows);

        $responce = new stdClass();
        $responce->page = $paginate->currentPage();
        $responce->total = $paginate->lastPage();
        $responce->records = $paginate->total();
        $responce->rows = $paginate->items();
        $responce->sql = $table->toSql();
        $responce->sql_bind = $table->getBindings();

        return json_encode($responce);
    }

    public function mydump2($builder){
        $addSlashes = str_replace('?', "'?'", $builder->toSql());
        dump(vsprintf(str_replace('?', '%s', $addSlashes), $builder->getBindings()));
    }

    public function mydd($builder){
        $addSlashes = str_replace('?', "'?'", $builder->toSql());
        dd(vsprintf(str_replace('?', '%s', $addSlashes), $builder->getBindings()));
    }



}
