<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use Response;
use File;
use Guzzle\Http\Exception\ClientErrorResponseException;
use GuzzleHttp\Client;
use App\Exports\print_implant_patientExport;
use Maatwebsite\Excel\Facades\Excel;

class einvoiceController extends defaultController
{   

    public function __construct(){
        $this->middleware('auth');
    }

    public function show(Request $request){   
        return view('finance.GL.einvoice.einvoice');
    }

    public function show_imp(Request $request){   
        return view('finance.GL.einvoice.implant_patmast');
    }

    public function table(Request $request){   
        DB::enableQueryLog();
        switch($request->action){
            case 'maintable':
                return $this->maintable($request);
            case 'maintable_ip':
                return $this->maintable_ip($request);
            case 'acctent_sales':
                return $this->acctent_sales($request);
            case 'acctent_cost':
                return $this->acctent_cost($request);
            case 'einvoice_show':
                return $this->einvoice_show($request);
            case 'show_result':
                return $this->show_result($request);
            case 'check_verifytin':
                return $this->check_verifytin($request);
            case 'save_verifytin':
                return $this->save_verifytin($request);
            case 'login_submit':
                return $this->login_submit($request);
            case 'einvoice_submit':
                return $this->einvoice_submit($request);
            case 'einvoice_save_dm':
                return $this->einvoice_save_dm($request);
            case 'print_implant_patient':
                return $this->print_implant_patient($request);
            default:
                return 'error happen..';
        }
    }

    public function einvoice_show(Request $request){   
        $idno = $request->idno;
        if(empty($idno)){
            throw new \Exception("No dbacthdr idno");
        }

        $dbacthdr = DB::table('debtor.dbacthdr as db')
                        ->where('db.compcode',session('compcode'))
                        ->where('db.idno',$idno)
                        ->first();

        $invno = $dbacthdr->invno;

        return view('finance.GL.einvoice.einvoice_show',compact('invno'));
    }

    public function form(Request $request){   
        switch($request->action){
            case 'submit_einvoice':
                return $this->submit_einvoice($request);
            // case 'edit':
            //     return $this->defaultEdit($request);
            // case 'del':
            //     return $this->defaultDel($request);
            default:
                return 'error happen..';
        }
    }

    public function maintable(Request $request){

        $unit = ($request->unit)?$request->unit:'ALL';

        $table = DB::table('debtor.dbacthdr as db')
                        ->select('db.idno','db.compcode','db.source','db.trantype','db.auditno','db.lineno_','db.invno','db.mrn','db.episno','db.debtorcode','db.amount','db.entrydate','pm.Name','dm.name as dbname','db.LHDNSubBy','db.LHDNStatus','dm.newic as dm_newic','dm.tinid','pm.newic as pm_newic','dm.debtortype','db.unit','db.pointofsales','dm.address1','dm.address2','dm.address3','dm.postcode','dm.teloffice','dm.statecode')
                        ->leftJoin('hisdb.pat_mast as pm', function($join) use ($request){
                            $join = $join->where('pm.compcode', '=', session('compcode'));
                            $join = $join->on('pm.newmrn', '=', 'db.mrn');
                        })
                        ->leftJoin('debtor.debtormast as dm', function($join) use ($request){
                            $join = $join->where('dm.compcode', '=', session('compcode'));
                            $join = $join->on('dm.debtorcode', '=', 'db.debtorcode');
                        })
                        ->where('db.recstatus','POSTED')
                        ->where('db.compcode',session('compcode'))
                        ->where('db.source','PB')
                        ->whereIn('db.trantype',['IN'])
                        ->whereNotNull('db.deptcode');

        if(strtoupper($unit) != 'ALL'){
            $table = $table->where('db.unit',$unit);
        }

                        // ->where('db.mrn','!=','0')
                        // ->where('db.episno','!=','0');

        if(!empty($request->filterCol)){
            $table = $table->where($request->filterCol[0],'=',$request->filterVal[0]);
        }
        
        if(!empty($request->searchCol)){
            if($request->searchCol[0] == 'Name'){
                $table = $table->Where(function ($table) use ($request) {
                    $table->Where('pm.'.$request->searchCol[0],'like',$request->searchVal[0]);
                });
            }else if($request->searchCol[0] == 'entrydate'){
                $table = $table->Where(function ($table) use ($request) {
                    $table->WhereDate('db.entrydate','>=',$request->datefrom);
                    $table->WhereDate('db.entrydate','<=',$request->dateto);
                });
            }else{
                $table = $table->Where(function ($table) use ($request) {
                    $table->Where('db.'.$request->searchCol[0],'like',$request->searchVal[0]);
                });
            }
        }
        
        if(!empty($request->sidx)){
            
            $pieces = explode(", ", $request->sidx .' '. $request->sord);
            
            if(count($pieces)==1){
                $table = $table->orderBy($request->sidx, $request->sord);
            }else{
                foreach ($pieces as $key => $value) {
                    $value_ = substr_replace($value,"db.",0,strpos($value,"_")+1);
                    $pieces_inside = explode(" ", $value_);
                    $table = $table->orderBy($pieces_inside[0], $pieces_inside[1]);
                }
            }
        }else{
            $table = $table->orderBy('db.idno','DESC');
        }
        
        $paginate = $table->paginate($request->rows);

        foreach ($paginate->items() as $key => $value) {
            if(!empty($value->pm_newic)){
                $value->newic = $value->pm_newic;
            }else{
                $value->newic = $value->dm_newic;
            }

            if(strtoupper($value->trantype)=='IN'){
                if($value->pointofsales == '1'){
                    $value->url = "./PointOfSales/showpdf?idno=".$value->idno;
                }else{
                    $value->url = "./SalesOrder/showpdf?idno=".$value->idno;
                }
            }else if(strtoupper($value->trantype)=='RD'){
                $value->url = "./receipt/showpdf?auditno=".$value->idno;
            }
        }
        
        //////////paginate/////////
        
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

    public function maintable_ip(Request $request){

        $table = DB::table('hisdb.pat_mast as pm')
                        ->select('pm.idno','pm.CompCode','pm.MRN','pm.Episno','pm.Name','pm.Address1','pm.Address2','pm.Address3','pm.Postcode','pm.citycode','pm.AreaCode','pm.StateCode','pm.CountryCode','pm.telh','pm.telhp','pm.Newic','pm.NewMrn')
                        ->where('pm.compcode',session('compcode'));

        if(!empty($request->filterCol)){
            $table = $table->where($request->filterCol[0],'=',$request->filterVal[0]);
        }
        
        if(!empty($request->searchCol)){
            if($request->searchCol[0] == 'Name'){
                $table = $table->Where(function ($table) use ($request) {
                    $table->Where('pm.'.$request->searchCol[0],'like',$request->searchVal[0]);
                });
            }else{
                $table = $table->Where(function ($table) use ($request) {
                    $table->Where('pm.'.$request->searchCol[0],'like',$request->searchVal[0]);
                });
            }
        }
        
        if(!empty($request->sidx)){
            
            $pieces = explode(", ", $request->sidx .' '. $request->sord);
            
            if(count($pieces)==1){
                $table = $table->orderBy($request->sidx, $request->sord);
            }else{
                foreach ($pieces as $key => $value) {
                    $value_ = substr_replace($value,"pm.",0,strpos($value,"_")+1);
                    $pieces_inside = explode(" ", $value_);
                    $table = $table->orderBy($pieces_inside[0], $pieces_inside[1]);
                }
            }
        }else{
            $table = $table->orderBy('pm.idno','DESC');
        }
        
        $paginate = $table->paginate($request->rows);
        //////////paginate/////////
        
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

    public function print_implant_patient(Request $request){
        return Excel::download(new print_implant_patientExport(), 'Patient Implant List.xlsx');
    }

    public function submit_einvoice(Request $request){

        DB::beginTransaction();
        try {

            $user = DB::table('sysdb.users')
                    ->where('compcode',session('compcode'))
                    ->where('username',$request->username)
                    ->where('password',$request->password);

            if(!$user->exists()){
                throw new \Exception("Wrong Password or username");
            }

            $all_document = [];
            foreach ($request->idno_array as $idno) {
                if($this->check_invno_exist($idno) == true){
                    continue;
                }

                $dbacthdr = DB::table('debtor.dbacthdr as db')
                            ->select('db.idno','db.invno','db.amount','dm.name','dm.tinid','dm.address1','dm.address2','dm.address3','dm.postcode','dm.statecode','pm.statecode as statecode_pm','dm.countrycode','dm.teloffice','pm.Newic','pm.telhp')
                            ->leftJoin('debtor.debtormast as dm', function($join) use ($request){
                                $join = $join->where('dm.compcode', '=', session('compcode'));
                                $join = $join->on('dm.debtorcode', '=', 'db.debtorcode');
                            })
                            ->leftJoin('hisdb.pat_mast as pm', function($join) use ($request){
                                $join = $join->where('pm.compcode', '=', session('compcode'));
                                $join = $join->on('pm.mrn', '=', 'db.mrn');
                            })
                            ->where('db.compcode',session('compcode'))
                            ->where('db.idno',$idno)
                            ->first();

                $lhdn_header = new stdClass();
                $lhdn_header->idno = $dbacthdr->idno;
                $lhdn_header->subby = $request->username;
                $lhdn_header->invno = $dbacthdr->invno;
                $lhdn_header->name = $dbacthdr->name;
                $lhdn_header->tin = $dbacthdr->tinid;
                $lhdn_header->newic = $dbacthdr->Newic;
                $lhdn_header->telhp = $dbacthdr->teloffice;
                if(empty($dbacthdr->teloffice)){
                    $lhdn_header->telhp = $dbacthdr->telhp;
                }
                $lhdn_header->postcode = $dbacthdr->postcode;
                $lhdn_header->city = $dbacthdr->address2;
                $lhdn_header->statecode = $dbacthdr->statecode;
                if(empty($dbacthdr->statecode)){
                    $lhdn_header->statecode = $dbacthdr->statecode_pm;
                }
                $lhdn_header->addr1 = $dbacthdr->address1;
                $lhdn_header->addr2 = $dbacthdr->address2;
                $lhdn_header->addr3 = $dbacthdr->address3;
                $lhdn_header->totalamount = $dbacthdr->amount;
                $lhdn_header->billdate = Carbon::now("Asia/Kuala_Lumpur")->format('Y-m-d');

                $detail = DB::table('debtor.billsum as bs')
                            ->select('bs.idno','bs.chggroup','bs.uom','bs.totamount','cm.description')
                            ->leftJoin('hisdb.chgmast as cm', function($join) use ($request){
                                $join = $join->where('cm.compcode', '=', session('compcode'));
                                $join = $join->on('cm.chgcode', '=', 'bs.chggroup');
                                $join = $join->on('cm.uom', '=', 'bs.uom');
                            })
                            ->where('bs.invno',$dbacthdr->invno)
                            ->where('bs.compcode',session('compcode'))
                            ->where('bs.source','PB')
                            ->where('bs.trantype','IN')
                            ->where('bs.totamount','!=','0')
                            ->where('bs.recstatus','!=','DELETE')
                            ->where('bs.recstatus','!=','CANCELLED')
                            ->get();

                // dump($lhdn_header);
                // dd($detail);

                $json = $this->populate_invoice($lhdn_header,$detail);
                // dd($json);

                $content = json_encode($json);
                $base64 = base64_encode($content);
                $sha256 = hash('sha256',$content);

                $document = new stdClass();
                $document->format = "JSON";
                $document->documentHash = $sha256;
                $document->codeNumber = 'JSON-'.$idno;
                $document->document = $base64;
                // dd($document);

                array_push($all_document, $document);
            }

            if(count($all_document) == 0){
                return 0;
            }

            $filename_submit = storage_path("json").'/example_submit_document.json';
            $file_submit = File::get($filename_submit);
            $json_submit = json_decode($file_submit);

            $json_submit->documents = $all_document;

            // dd($json_submit);

            $access_token = $this->login_lhdn();
            $client = new \GuzzleHttp\Client();
            $url = 'https://preprod-api.myinvois.hasil.gov.my/api/v1.0/documentsubmissions';
            // $url = 'https://api.myinvois.hasil.gov.my/api/v1.0/documentsubmissions';
            try {
                $response = $client->request('POST', $url, [
                    'body' => json_encode($json_submit),
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                        'authorization' => $access_token,

                    ],json_encode($json_submit)
                ]);

                $response_ = $response->getBody()->getContents();

                $myresponse = json_decode($response_);
                $this->einvoice_storeDB($myresponse,$request->username);

            }catch(\GuzzleHttp\Exception\RequestException $e) {
                if ($e->hasResponse()) {
                    $response = $e->getResponse();

                    $myresponse = json_decode((string) $response->getBody());
                    $this->einvoice_storeDB($myresponse,$request->username);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function acctent_sales(Request $request){
        $invno = $request->invno;
        $auditno = $request->auditno;
        $lineno_ = $request->lineno_;
        $debtorname = $request->dbname;
        if(empty($invno) || empty($lineno_)){
            abort(403, 'billno Not Exist');
        }
        $array_show = [];

        $gltran_debit = DB::table('finance.gltran as gl')
                        ->select('gl.postdate as date','gl.dracc as account','gm.description as accountname','gl.amount as debit')
                        ->leftJoin('finance.glmasref as gm', function($join) use ($request){
                            $join = $join->where('gm.compcode', '=', session('compcode'));
                            $join = $join->on('gm.glaccno', '=', 'gl.dracc');
                        })
                        ->where('gl.compcode',session('compcode'))
                        ->where('gl.auditno',$invno)
                        ->where('gl.lineno_',$lineno_)
                        ->where('gl.source','PB')
                        ->where('gl.trantype','IN')
                        ->get();

        foreach ($gltran_debit as $key => $value) {
            $value->description = $debtorname;
            $value->credit='';
            array_push($array_show,$value);
        }

        $billdet = DB::table('hisdb.billdet as bd')
                        ->select('gl.postdate as date','cm.description','gm.description as accountname','gl.cracc as account','gl.amount as credit')
                        ->leftJoin('hisdb.chgmast as cm', function($join) use ($request){
                            $join = $join->where('cm.compcode', '=', session('compcode'));
                            $join = $join->on('cm.chgcode', 'bd.chgcode');
                            $join = $join->on('cm.uom', 'bd.uom');
                        })
                        ->join('finance.gltran as gl', function($join) use ($request){
                            $join = $join->where('bd.compcode', '=', session('compcode'));
                            $join = $join->on('gl.auditno', 'bd.auditno');
                            $join = $join->on('gl.lineno_', 'bd.lineno_');
                            $join = $join->where('gl.source','OE');
                            $join = $join->where('gl.trantype','IN');
                        })
                        ->leftJoin('finance.glmasref as gm', function($join) use ($request){
                            $join = $join->where('gm.compcode', '=', session('compcode'));
                            $join = $join->on('gm.glaccno', '=', 'gl.cracc');
                        })
                        ->where('bd.compcode', '=', session('compcode'))
                        ->where('bd.invno', $invno)
                        ->where('bd.lineno_',$lineno_)
                        ->get();

        foreach ($billdet as $key => $value) {
            $value->debit='';
            array_push($array_show,$value);
        }

        $responce = new stdClass();
        $responce->page = 1;
        $responce->total = 1;
        $responce->records = count($array_show);
        $responce->rows = $array_show;
        
        return json_encode($responce);
    }

    public function acctent_cost(Request $request){
        $invno = $request->invno;
        $auditno = $request->auditno;
        $lineno_ = $request->lineno_;
        $debtorname = $request->dbname;
        if(empty($invno) || empty($lineno_)){
            abort(403, 'billno Not Exist');
        }
        $array_show = [];

        $billdet = DB::table('hisdb.billdet as bd')
                        ->select('gl.postdate as date','cm.description','gm_cr.description as accountname_cr','gm_dr.description as accountname_dr','gl.cracc as cr_account','gl.dracc as db_account','gl.amount as amount')
                        ->leftJoin('hisdb.chgmast as cm', function($join) use ($request){
                            $join = $join->where('cm.compcode', '=', session('compcode'));
                            $join = $join->on('cm.chgcode', 'bd.chgcode');
                            $join = $join->on('cm.uom', 'bd.uom');
                        })
                        ->join('finance.gltran as gl', function($join) use ($request){
                            $join = $join->where('bd.compcode', '=', session('compcode'));
                            $join = $join->on('gl.auditno', 'bd.auditno');
                            $join = $join->on('gl.lineno_', 'bd.lineno_');
                            $join = $join->where('gl.source','IV');
                            $join = $join->where('gl.trantype','DS');
                        })
                        ->leftJoin('finance.glmasref as gm_cr', function($join) use ($request){
                            $join = $join->where('gm_cr.compcode', '=', session('compcode'));
                            $join = $join->on('gm_cr.glaccno', '=', 'gl.cracc');
                        })
                        ->leftJoin('finance.glmasref as gm_dr', function($join) use ($request){
                            $join = $join->where('gm_dr.compcode', '=', session('compcode'));
                            $join = $join->on('gm_dr.glaccno', '=', 'gl.dracc');
                        })
                        ->leftJoin('material.ivdspdt as iv', function($join) use ($request){
                            $join = $join->where('iv.compcode', '=', session('compcode'));
                            $join = $join->on('iv.recno', '=', 'bd.auditno');
                        })
                        ->where('bd.compcode', '=', session('compcode'))
                        ->where('bd.invno', $invno)
                        ->where('bd.lineno_',$lineno_)
                        ->get();

        foreach ($billdet as $key => $value) {
            $obj_new = new stdClass();
            $obj_new->date = $value->date;
            $obj_new->description = $value->description;
            $obj_new->account = $value->db_account;
            $obj_new->accountname = $value->accountname_dr;
            $obj_new->credit = '';
            $obj_new->debit = $value->amount;
            array_push($array_show,$obj_new);

            $obj_new2 = new stdClass();
            $obj_new2->date = $value->date;
            $obj_new2->description = '';
            $obj_new2->account = $value->cr_account;
            $obj_new2->accountname = $value->accountname_cr;
            $obj_new2->credit = $value->amount;
            $obj_new2->debit = '';
            array_push($array_show,$obj_new2);
        }
        $responce = new stdClass();
        $responce->page = 1;
        $responce->total = 1;
        $responce->records = count($array_show);
        $responce->rows = $array_show;
        
        return json_encode($responce);
    }

    public function login_lhdn(){

        $client = new Client([
            'verify' => false,
        ]);
        $url = 'https://api.myinvois.hasil.gov.my/connect/token';
        
        $clientId = 'd3b76b30-305d-4812-8510-83df7ae016aa';//'21c26151-0a18-4d05-aca1-5688a2e4464b';
        $clientSecret = 'accd3c7a-7010-4b7a-acf4-957eedaa1a3d';//'0ed09a87-ae99-48a4-86ed-e1481230122a';

        $response = $client->request('POST', $url, [
            'headers' => ['Content-type: application/x-www-form-urlencoded'],
                'form_params' => [
                    'grant_type' => 'client_credentials',
                    'client_id' => $clientId,
                    'client_secret' => $clientSecret,
                    'scope' => 'InvoicingAPI',
                ],
                // 'timeout' => 20, // Response timeout
                // 'connect_timeout' => 20, // Connection timeout
            ]);

        $content = json_decode($response->getBody()->getContents());
        return $content->access_token;
    }

    public function check_verifytin(Request $request){

        $mykad = $request->newic;
        $debtorcode = $request->debtorcode;

        $taxpayer = DB::table('finance.taxpayer')
                        ->where('mykad',$mykad);

        if($taxpayer->exists()){
            $taxpayer = $taxpayer->first();
            $retval = $taxpayer->tin;

            echo $retval;
        }else{

            $access_token = $this->login_lhdn();

            $client = new Client([
                        'verify' => false,
                    ]);
            $url = 'https://api.myinvois.hasil.gov.my/api/v1.0/taxpayer/search/tin?idType=NRIC&idValue='.$mykad.'&taxpayerName=';
            try {
                $response = $client->request('GET', $url, [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                        'authorization' => $access_token,
                    ]
                ]);

                $response_ = $response->getBody()->getContents();

                $myresponse = json_decode($response_);

                return $this->einvoice_store_taxpayer($myresponse,$mykad,$debtorcode);

                // return view('einvoice_show',compact('myresponse','header','detail'));

            }catch(\GuzzleHttp\Exception\RequestException $e) {
                return 'notin';
                if ($e->hasResponse()) {
                    $response = $e->getResponse();

                    $myresponse = json_decode((string) $response->getBody());

                    return $myresponse;
                    // return view('einvoice_show',compact('myresponse','header','detail'));
                }
            }
        }
    }

    public function einvoice_save_dm(Request $request){
        DB::beginTransaction();
        try {

            $postcode = DB::table('hisdb.postcode')
                        ->where('compcode',session('compcode'))
                        ->where('postcode',$request->postcode_dm);

            $statecode = null;

            if($postcode->exists()){
                $postcode=$postcode->first();

                $state = DB::table('hisdb.state')
                        ->where('compcode',session('compcode'))
                        ->where('description',strtoupper($postcode->statecode));

                if($state->exists()){
                    $state = $state->first();
                    $statecode = $state->StateCode;
                }
            }else{
                throw new \Exception("Wrong Postcode, please check");
            }

            DB::table('debtor.debtormast')
                ->where('compcode',session('compcode'))
                ->where('debtorcode',$request->payercode_dm)
                ->update([
                    'address1' => $request->address1_dm,
                    'address2' => $request->address2_dm,
                    'address3' => $request->address3_dm,
                    'postcode' => $request->postcode_dm,
                    'teloffice' => $request->telhp_dm,
                    'statecode' => $statecode,
                ]);



            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function einvoice_store_taxpayer($myresponse,$mykad,$debtorcode){
        DB::table('finance.taxpayer')
                ->insert([
                    'mykad' => $mykad,
                    'tin' => $myresponse->tin
                ]);

        DB::table('debtor.debtormast')
                ->where('compcode',session('compcode'))
                ->where('debtorcode',$debtorcode)
                ->update([
                    'tinid' => $myresponse->tin
                ]);

        return $myresponse->tin;
    }

    public function save_verifytin(Request $request){
        $upd_array = [];

        if(!empty($request->tinid)){
            $upd_array['tinid'] = $request->tinid;
        }

        if(!empty($request->newic)){
            $upd_array['newic'] = $request->newic;
        }

        if(count($upd_array)>0){
            DB::table('debtor.debtormast')
                ->where('compcode',session('compcode'))
                ->where('debtorcode',$request->debtorcode)
                ->update($upd_array);
        }
    }

    public function login_submit(Request $request){

        DB::beginTransaction();
        try {

            $users = DB::table('sysdb.users')
                            ->where('compcode',session('compcode'))
                            ->where('username',$request->username)
                            ->where('password',$request->password);

            if(!$users->exists()){
                throw new \Exception("Wrong Password or username");
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function einvoice_submit(Request $request){

        DB::beginTransaction();
        try {
            $idno = $request->idno;
            if(empty($idno)){
                throw new \Exception("No dbacthdr idno");
            }

            $dbacthdr = DB::table('debtor.dbacthdr as db')
                            ->select('db.idno','db.compcode','db.source','db.trantype','db.auditno','db.lineno_','db.invno','db.mrn','db.episno','db.debtorcode','db.amount','db.entrydate','pm.Name','dm.name as dbname','db.LHDNSubBy','db.LHDNStatus','dm.newic as dm_newic','dm.tinid','pm.newic as pm_newic','dm.debtortype','db.unit','db.pointofsales','dm.address1','dm.address2','dm.address3','dm.postcode','dm.statecode','dm.teloffice')
                            ->leftJoin('hisdb.pat_mast as pm', function($join) use ($request){
                                $join = $join->where('pm.compcode', '=', session('compcode'));
                                $join = $join->on('pm.newmrn', '=', 'db.mrn');
                            })
                            ->leftJoin('debtor.debtormast as dm', function($join) use ($request){
                                $join = $join->where('dm.compcode', '=', session('compcode'));
                                $join = $join->on('dm.debtorcode', '=', 'db.debtorcode');
                            })
                            ->where('db.compcode',session('compcode'))
                            ->where('db.idno',$idno);

            if(!$dbacthdr->exists()){
                throw new \Exception("No dbacthdr data");
            }

            $dbacthdr = $dbacthdr->first();
            if(!empty($dbacthdr->pm_newic)){
                $newic = $dbacthdr->pm_newic;
            }else{
                $newic = $dbacthdr->dm_newic;
            }

            $billsum = DB::table('debtor.billsum AS b')
                    ->select('b.compcode', 'b.idno','b.invno', 'b.mrn', 'b.billno', 'b.lineno_', 'b.chgclass', 'b.chggroup', 'b.uom', 'b.quantity', 'b.amount', 'b.outamt', 'b.taxamt', 'b.unitprice', 'b.taxcode', 'b.discamt', 'b.recstatus', 'm.description')
                    ->leftJoin('hisdb.chgmast as m', function($join) use ($request){
                        $join = $join->on('b.chggroup', '=', 'm.chgcode');
                        $join = $join->on('b.uom', '=', 'm.uom');
                        $join = $join->where('m.compcode', '=', session('compcode'));
                        // $join = $join->where('m.unit', '=', session('unit'));
                    })
                    ->where('b.source','=',$dbacthdr->source)
                    ->where('b.trantype','=',$dbacthdr->trantype)
                    ->where('b.billno','=',$dbacthdr->auditno)
                    ->where('b.compcode','=',session('compcode'))
                    ->get();

            if (empty($dbacthdr->invno)) {
                throw new \Exception("No invno");
            }
            if (empty($dbacthdr->dbname)) {
                throw new \Exception("No name");
            }
            if (empty($dbacthdr->tinid)) {
                throw new \Exception("No tin");
            }else{
                if (empty($newic)) {
                    throw new \Exception("No newic");
                }
            }
            if (empty($dbacthdr->teloffice)) {
                throw new \Exception("No telhp");
            }
            if (empty($dbacthdr->postcode)) {
                throw new \Exception("No postcode");
            }
            if (empty($dbacthdr->address2)) {
                throw new \Exception("No city");
            }
            if (empty($dbacthdr->statecode)) {
                throw new \Exception("No statecode");
            }
            if (empty($dbacthdr->address1)) {
                throw new \Exception("No addr1");
            }
            if (empty($dbacthdr->address2)) {
                throw new \Exception("No addr2");
            }

            $headerData = [
                ["invno", $dbacthdr->invno ?? ""],
                ["name", $dbacthdr->dbname ?? ""],
                ["tin", $dbacthdr->tinid ?? ""],
                ["newic", $newic ?? ""],
                ["telhp", $dbacthdr->teloffice ?? ""],
                ["postcode", $dbacthdr->postcode ?? ""],
                ["city", $dbacthdr->address2 ?? ""],
                ["statecode", $dbacthdr->statecode ?? ""],
                ["addr1", $dbacthdr->address1 ?? ""],
                ["addr2", $dbacthdr->address2 ?? ""],
                ["addr3", $dbacthdr->address3 ?? ""],
                ["totalamount", $dbacthdr->amount ?? "0"],
                ["billdate", Carbon::now()->format('d/m/Y')],
                ["compcode", "medicare"],
            ];

            $details = [];
            $counter = 1;

            foreach ($billsum as $row) {
                $details["DATA" . $counter] = [
                    ["desc", $row->description ?? ""],
                    ["price", $row->amount ?? "0"],
                    ["qty", $row->quantity ?? "1"],
                ];
                $counter++;
            }

            $my_all = [
                "header" => [
                    "DATA1" => $headerData,
                ],
                "detail" => $details,
            ];

            DB::table('finance.einvoice_sentdata')
                ->insert([
                    'dbacthdr_idno' => $idno,
                    'payload' => json_encode($my_all),
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur")
                ]);

            // dd(json_encode($my_all));

            $url = "http://175.143.1.33:8080/einvoice/einvoice_post"; // your target API

            $client = new Client();

            $response = $client->post($url, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'User-Agent' => 'Mozilla/5.0 (Laravel Guzzle Client)',
                ],
                'json' => $my_all, // Guzzle will json_encode automatically
            ]);

            $body = $response->getBody()->getContents();

            if (strpos($body, "ACCEPTED") === 0) {
                // Split into parts
                $parts = explode("|", $body);

                $status = $parts[0] ?? null;
                $submissionUid = $parts[1] ?? null;
                $invoiceCodeNumber = $parts[2] ?? null;
                $uuid = $parts[3] ?? null;

                DB::table('debtor.dbacthdr as db')
                            ->where('db.compcode',session('compcode'))
                            ->where('db.idno',$idno)
                            ->update([
                                'LHDNStatus' => $status,
                                'LHDNSubID' => $submissionUid,
                                'LHDNCodeNo' => $invoiceCodeNumber,
                                'LHDNDocID' => $uuid,
                                'LHDNSubBy' => session('username'),
                            ]);
            } else if (strpos($body, "REJECTED") === 0) {
                $parts = explode("|", $body);

                $status = $parts[0] ?? null;
                $submissionUid = $parts[1] ?? null;
                $invoiceCodeNumber = $parts[2] ?? null;
                $uuid = $parts[3] ?? null;

                DB::table('debtor.dbacthdr as db')
                            ->where('db.compcode',session('compcode'))
                            ->where('db.idno',$idno)
                            ->update([
                                'LHDNStatus' => $status,
                                'LHDNSubID' => $submissionUid,
                                'LHDNCodeNo' => $invoiceCodeNumber,
                                'LHDNDocID' => $uuid,
                                'LHDNSubBy' => session('username'),
                            ]);
            } else {

            }

            DB::commit();

            return response()->json([
                "sent_data" => $body,
            ]);

        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

}