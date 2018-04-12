<?php

namespace App\Http\Controllers\hisdb;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class EmergencyController extends defaultController
{   

    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
        $this->duplicateCode = "MRN";
    }

    public function show(Request $request)
    {   
       return view('hisdb.emergency.emergency');
    }

    public function form(Request $request)
    {   
        switch($request->oper){
            case 'add':
                return $this->add($request);
            case 'edit':
                return $this->defaultEdit($request);
            case 'del':
                return $this->defaultDel($request);
            default:
                return 'error happen..';
        }
    }

    public function add(Request $request){

        DB::beginTransaction();

        try {
            ///--- 1. check kalu MRN ada ke tak

            if($request->mrn != ""){
                //1.kalu ada cari
                $patmast_obj = DB::table('hisdb.pat_mast')
                    ->where('mrn','=',$request->mrn)
                    ->where('compcode','=',session('compcode'));
            }else{
                //2.kalu xde buat baru lepas tambah sysparam
                $sysparam_mrn = $this->defaultSysparam("HIS","MRN");

                DB::table('hisdb.pat_mast')
                    ->insert([
                        'mrn' => $sysparam_mrn,
                        'Oldic' => $request->Oldic,
                        'Name' => $request->patname,
                        'DOB' => $request->DOB,
                        'Sex'  => $request->sex,
                        'RaceCode'  => $request->race,
                        'Confidential'  => 'yes',
                        'MRFolder'  => 'yes',    
                        'Newic'   => $request->Newic,
                        'Active'  => 'yes',     
                        'CompCode'  => session('compcode'),
                        'PatStatus'  => 'yes',  
                        'Lastupdate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'LastUser'  => session('username'),   
                        'AddDate'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                        'AddUser'  => session('username') 
                    ]);

                $patmast_obj = DB::table('hisdb.pat_mast')
                    ->where('mrn','=',$sysparam_mrn)
                    ->where('compcode','=',session('compcode'));
            }
            
            $patmast_data = $patmast_obj->first();

            ///--- 2. buat episode baru
                //1. RegDept OE-ED
            $RegDept = DB::table('sysdb.sysparam')
                ->select('pvalue1')
                ->where('source','=','OE')
                ->where('trantype','=','ED')
                ->first();
                
                //2. tukar pat status dgn episno
            $patmast_obj->update(['PatStatus' => 'yes', 'EpisNo' => $patmast_data->Episno + 1]);

                //3. tambah episode
            DB::table('hisdb.episode')
                ->insert([
                    'MRN' => $patmast_data->MRN,
                    'EpisNo' => $patmast_data->Episno + 1,    
                    'EpisTyCode' => "OP",
                    'Reg_Date'  => Carbon::now("Asia/Kuala_Lumpur")->toDateString(), 
                    'Reg_Time' => Carbon::now("Asia/Kuala_Lumpur")->toDateTimeString(),  
                    'AdmSrcCode' => "WIN",
                    'Case_Code'  => "MED",
                    'PyrMode' => "CASH",   
                    'Pay_type' => $request->paytype,
                    'BillType' => $request->billtype,
                    'AdmDoctor' => $request->doctor, 
                    'CompCode' => session('compcode'),  
                    'RegDept' => $RegDept->pvalue1,   
                    'EpisActive' => 'yes',
                    'Lastupdate' => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
                    'Lastuser' => session('username'),  
                    'AddDate' => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),   
                    'AddUser' => session('username'),   
                    'EDDept' => 'yes'
                ]);

            ///--- 3. buat debtormaster
            if($request->paytype == 'PT'){
                $debtortype_data = DB::table('debtor.debtortype')
                    ->where('compcode','=',session('compcode'))
                    ->where('DebtorTyCode','=','PR')
                    ->first();

                $debtormast_obj = DB::table('debtor.debtormast')
                    ->where('compcode','=',session('compcode'))
                    ->where('debtorcode','=',$patmast_data->MRN);

                $debtormast_data = $debtormast_obj->first();

                if(!count($debtormast_data)){
                    //kalu xjumpa debtormast, buat baru
                    DB::table('debtor.debtormast')
                        ->insert([
                            'CompCode' => session('compcode'),
                            'DebtorCode' => $patmast_data->MRN,
                            'Name' => $patmast_data->Name,
                            'Address1' => $patmast_data->Address1,
                            'Address2' => $patmast_data->Address2,
                            'Address3' => $patmast_data->Address3,
                            'DebtorType' => "PR",
                            'DepCCode'  => $debtortype_data->DepCCode,
                            'DepGlAcc' => $debtortype_data->DepGlAcc,
                            'BillType' => "IP",
                            'BillTypeOP' => "OP",
                            'ActDebCCode' => $debtortype_data->ActDebCCode,
                            'ActDebGlAcc' => $debtortype_data->ActDebGlAcc,
                            'LastUser' => session('username'),
                            'LastUpdate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'RecStatus' => "A"
                        ]);
                }
            }

            ///--- 4. epispayer
            $epispayer_data = DB::table('hisdb.epispayer')
                ->where('compcode','=',session('compcode'))
                ->where('mrn','=',$patmast_data->MRN)
                ->where('Episno','=',$patmast_data->Episno + 1)
                ->first();

            if(!count($debtormast_data)){
                //kalu xjumpa epispayer, buat baru
                DB::table('hisdb.epispayer')
                ->insert([
                    'CompCode' => session('compcode'),
                    'MRN' => $patmast_data->MRN,
                    'Episno' => $patmast_data->Episno + 1,
                    'EpisTyCode' => "OP",
                    'LineNo' => '1',
                    'BillType' => $request->billtype,
                    'PayerCode' => $request->payer,
                    'Pay_Type' => $request->paytype,
                    'AddDate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'AddUser' => session('username'),
                    'Lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'LastUser' => session('username')
                ]);
            }

            ///--- 5. buat apptbook
            DB::table('hisdb.apptbook')
                ->insert([
                    'LastUpdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'ApptDate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'AddUser' => session('username'),    
                    'CompCode' => session('compcode'),
                    'Location' => 'ED',
                    'LocCode' => 'ED',
                    'ApptNo' => '',
                    'MRN' => $patmast_data->MRN,
                    'ApptTime' => Carbon::now("Asia/Kuala_Lumpur")->toDateTimeString()
                ]);

            ///---6. buat docalloc
            $docalloc_data=DB::table('hisdb.docalloc')
                ->where('compcode','=',session('compcode'))
                ->where('Mrn','=',$patmast_data->MRN)
                ->where('Episno','=',$patmast_data->Episno + 1)
                ->first();


            if(!count($docalloc_data)){
                //kalu xde docalloc buat baru
                DB::table('hisdb.docalloc')
                    ->insert([
                        'mrn' => $patmast_data->MRN,
                        'compcode' => session('compcode'),
                        'episno' => $patmast_data->Episno + 1,
                        'AStatus' => "ADMITTING",
                        'Adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'AddUser' => session('username'),
                        'Epistycode' => 'OP',
                        'Lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'LastUser' => session('username'),
                        'ASDate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'ASTime' => Carbon::now("Asia/Kuala_Lumpur")->toDateTimeString()
                    ]);

            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }
    }
}