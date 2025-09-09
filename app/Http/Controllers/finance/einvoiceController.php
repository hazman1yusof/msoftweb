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

class einvoiceController extends defaultController
{   

    public function __construct(){
        $this->middleware('auth');
    }

    public function show(Request $request){   
        return view('finance.GL.einvoice.einvoice');
    }

    public function table(Request $request){   
        DB::enableQueryLog();
        switch($request->action){
            case 'maintable':
                return $this->maintable($request);
            case 'acctent_sales':
                return $this->acctent_sales($request);
            case 'acctent_cost':
                return $this->acctent_cost($request);
            case 'einvoice_show':
                return $this->einvoice_show($request);
            case 'show_result':
                return $this->show_result($request);
            default:
                return 'error happen..';
        }
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
        $table = DB::table('debtor.dbacthdr as db')
                        ->select('db.idno','db.compcode','db.source','db.trantype','db.auditno','db.lineno_','db.invno','db.mrn','db.episno','db.debtorcode','db.amount','db.entrydate','pm.Name','dm.name as dbname','db.LHDNSubBy','db.LHDNStatus')
                        ->leftJoin('hisdb.pat_mast as pm', function($join) use ($request){
                            $join = $join->where('pm.compcode', '=', session('compcode'));
                            $join = $join->on('pm.newmrn', '=', 'db.mrn');
                        })
                        ->leftJoin('debtor.debtormast as dm', function($join) use ($request){
                            $join = $join->where('dm.compcode', '=', session('compcode'));
                            $join = $join->on('dm.debtorcode', '=', 'db.debtorcode');
                        })
                        ->where('db.compcode',session('compcode'))
                        ->where('db.source','PB')
                        ->whereIn('db.trantype',['IN','RD'])
                        ->whereNotNull('db.deptcode')
                        ->where('db.pointofsales','0');
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

    public function populate_invoice($header,$detail){
        $inv_format = 'JSON';
        $inv_id = $header->invno;
        $inv_date = $header->billdate;
        // $inv_time = Carbon::createFromFormat('d/m/Y', $header->billdate)->format('H:i:d').'Z';
        $inv_time = '00:00:00Z';

        $cus_city = $header->city;
        $cus_postcode = $header->postcode;
        $cus_statecode = $header->statecode;
        $cus_addr1 = $header->addr1;
        $cus_addr2 = $header->addr2;
        $cus_addr3 = $header->addr3;

        $cus_name = $header->name;
        $cus_newic = $header->newic;
        $cus_tin = $header->tin;
        $cus_telhp = $header->telhp;

        $totalamount = floatval($header->totalamount);

        $filename = storage_path("json").'/example1.json';
        $file = File::get($filename);
        $json = json_decode($file);
        // dd($json);

        $json->Invoice[0]->ID[0]->_ = 'JSON-'.$inv_id;
        $json->Invoice[0]->IssueDate[0]->_ = $inv_date;
        $json->Invoice[0]->IssueTime[0]->_ = $inv_time;
        $json->Invoice[0]->AccountingCustomerParty[0]->Party[0]->PostalAddress[0]->CityName[0]->_ = $cus_city;
        $json->Invoice[0]->AccountingCustomerParty[0]->Party[0]->PostalAddress[0]->PostalZone[0]->_ = $cus_postcode;
        $json->Invoice[0]->AccountingCustomerParty[0]->Party[0]->PostalAddress[0]->CountrySubentityCode[0]->_ = $cus_statecode;
        $json->Invoice[0]->AccountingCustomerParty[0]->Party[0]->PostalAddress[0]->AddressLine[0]->Line[0]->_ = $cus_addr1;
        $json->Invoice[0]->AccountingCustomerParty[0]->Party[0]->PostalAddress[0]->AddressLine[1]->Line[0]->_ = $cus_addr2;
        $json->Invoice[0]->AccountingCustomerParty[0]->Party[0]->PostalAddress[0]->AddressLine[2]->Line[0]->_ = $cus_addr3;
        $json->Invoice[0]->AccountingCustomerParty[0]->Party[0]->PartyLegalEntity[0]->RegistrationName[0]->_ = $cus_name;
        if(!empty($cus_tin)){
            $json->Invoice[0]->AccountingCustomerParty[0]->Party[0]->PartyIdentification[0]->ID[0]->_ = $cus_tin;
        }
        if(!empty($cus_newic)){
            $json->Invoice[0]->AccountingCustomerParty[0]->Party[0]->PartyIdentification[1]->ID[0]->_ = $cus_newic;
        }
        $json->Invoice[0]->AccountingCustomerParty[0]->Party[0]->Contact[0]->Telephone[0]->_ = $cus_telhp;
        $json->Invoice[0]->LegalMonetaryTotal[0]->PayableAmount[0]->_ = $totalamount;

        $filename_detail = storage_path("json").'/example_detail.json';
        $file_detail = File::get($filename_detail);
        $json_detail_main = json_decode($file_detail);
        // dd($json_detail);

        $InvoiceLine_array = []; 
        $lineno=0;
        foreach ($detail as $key => $value) {
            $lineno++;
            $json_detail = $json_detail_main;
            $desc = $value->description;
            $price = floatval($value->totamount);

            $json_detail->ID[0]->_ = str_pad($lineno, 3, "0", STR_PAD_LEFT);
            $json_detail->LineExtensionAmount[0]->_ = $price;
            $json_detail->LineExtensionAmount[0]->currencyID = "MYR";
            $json_detail->TaxTotal[0]->TaxAmount[0]->_ = 0;
            $json_detail->TaxTotal[0]->TaxAmount[0]->currencyID = "MYR";
            $json_detail->TaxTotal[0]->TaxSubtotal[0]->TaxableAmount[0]->_ = 0;
            $json_detail->TaxTotal[0]->TaxSubtotal[0]->TaxableAmount[0]->currencyID = "MYR";
            $json_detail->TaxTotal[0]->TaxSubtotal[0]->TaxAmount[0]->_ = 0;
            $json_detail->TaxTotal[0]->TaxSubtotal[0]->TaxAmount[0]->currencyID = "MYR";
            $json_detail->TaxTotal[0]->TaxSubtotal[0]->TaxCategory[0]->ID[0]->_ = "06";
            $json_detail->TaxTotal[0]->TaxSubtotal[0]->TaxCategory[0]->TaxExemptionReason[0]->_ = "NA";
            $json_detail->TaxTotal[0]->TaxSubtotal[0]->TaxCategory[0]->TaxScheme[0]->ID[0]->_ = "OTH";
            $json_detail->TaxTotal[0]->TaxSubtotal[0]->TaxCategory[0]->TaxScheme[0]->ID[0]->schemeID = "UN/ECE 5153";
            $json_detail->TaxTotal[0]->TaxSubtotal[0]->TaxCategory[0]->TaxScheme[0]->ID[0]->schemeAgencyID = "6";
            $json_detail->Item[0]->CommodityClassification[0]->ItemClassificationCode[0]->_ = "022";
            $json_detail->Item[0]->CommodityClassification[0]->ItemClassificationCode[0]->listID = "CLASS";
            $json_detail->Item[0]->Description[0]->_ = $desc;
            $json_detail->Price[0]->PriceAmount[0]->_ = $price;
            $json_detail->Price[0]->PriceAmount[0]->currencyID = "MYR";
            $json_detail->ItemPriceExtension[0]->Amount[0]->_ = $price;
            $json_detail->ItemPriceExtension[0]->Amount[0]->currencyID = "MYR";

            array_push($InvoiceLine_array, $json_detail);
        }
        $json->Invoice[0]->InvoiceLine = $InvoiceLine_array;

        $array_insert = [
            'invno' => $header->invno,
            'inv_idno' => $header->idno,
            'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
            'json' => json_encode($json),
            'subby' => $header->subby,
        ];

        DB::table('sysdb.einvoice_log')
                ->insert($array_insert);

        // dd($json);
        return $json;
    }

    public function login_lhdn(){

        $client = new \GuzzleHttp\Client();
        $url = 'https://preprod-api.myinvois.hasil.gov.my/connect/token';
        // $url = 'https://api.myinvois.hasil.gov.my/connect/token';
        $clientId = 'c2fecea0-5984-4253-97a7-41dd1565c6e4';
        $clientSecret = '6eeb46b0-8524-4aea-b096-cc141b3b3b83';

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

    public function einvoice_show(Request $request){
        $idno = $request->idno;
        $header = DB::table('debtor.dbacthdr as db')
                    ->select('db.idno','db.invno','db.amount','db.posteddate','dm.name','dm.tinid','dm.address1','dm.address2','dm.address3','dm.postcode','dm.statecode','dm.countrycode','dm.teloffice','pm.Newic','pm.telhp')
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

        $invoice = DB::table('sysdb.einvoice_log')
                    ->where('inv_idno',$header->idno)
                    ->orderBy('idno','DESC')
                    ->first();

        $header->status = $invoice->status;
        $header->submissionUid = $invoice->submissionUid;
        $header->invoiceCodeNumber = $invoice->invoiceCodeNumber;
        $header->uuid = $invoice->uuid;
        $header->message = $invoice->message;
        $header->code = $invoice->code;
        $header->propertyPath = $invoice->propertyPath;

        $detail = DB::table('debtor.billsum as bs')
                    ->select('bs.idno','bs.chggroup','bs.uom','bs.totamount','cm.description')
                    ->leftJoin('hisdb.chgmast as cm', function($join) use ($request){
                        $join = $join->where('cm.compcode', '=', session('compcode'));
                        $join = $join->on('cm.chgcode', '=', 'bs.chggroup');
                        $join = $join->on('cm.uom', '=', 'bs.uom');
                    })
                    ->where('bs.invno',$header->invno)
                    ->where('bs.compcode',session('compcode'))
                    ->where('bs.source','PB')
                    ->where('bs.trantype','IN')
                    ->where('bs.totamount','!=','0')
                    ->where('bs.recstatus','!=','DELETE')
                    ->where('bs.recstatus','!=','CANCELLED')
                    ->get();

        return view('finance.GL.einvoice.einvoice_show',compact('header','detail'));
    }

    public function einvoice_storeDB($myresponse,$username){

        if(empty($myresponse->submissionUid) && !property_exists($myresponse,'rejectedDocuments')){
            DB::table('sysdb.einvoice_log')
                    ->insert([
                        'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                        'errormsg' => json_encode($myresponse),
                        'status' => 'ERROR'
                    ]);
        }else{
            foreach ($myresponse->rejectedDocuments as $rejectedDocument) {
                $invno = substr($rejectedDocument->invoiceCodeNumber, 5);
                DB::table('sysdb.einvoice_log')
                    ->where('inv_idno',$invno)
                    ->update([
                        'status' => 'REJECTED',
                        'submissionUid' => $myresponse->submissionUid,
                        'invoiceCodeNumber' => $rejectedDocument->invoiceCodeNumber,
                        'message' => $rejectedDocument->error->details[0]->message,
                    ]);

                DB::table('debtor.dbacthdr as db')
                            ->where('db.compcode',session('compcode'))
                            ->where('db.idno',$invno)
                            ->update([
                                'LHDNStatus' => 'REJECTED',
                                'LHDNSubID' => $myresponse->submissionUid,
                                'LHDNCodeNo' => $rejectedDocument->invoiceCodeNumber,
                                // 'LHDNDocID' => $acceptedDocument->uuid,
                                'LHDNSubBy' => $username
                            ]);
            }

            foreach ($myresponse->acceptedDocuments as $acceptedDocument) {
                $invno = substr($acceptedDocument->invoiceCodeNumber, 5);
                DB::table('sysdb.einvoice_log')
                    ->where('inv_idno',$invno)
                    ->update([
                        'status' => 'ACCEPTED',
                        'submissionUid' => $myresponse->submissionUid,
                        'invoiceCodeNumber' => $acceptedDocument->invoiceCodeNumber,
                        'uuid' => $acceptedDocument->uuid,
                    ]);

                DB::table('debtor.dbacthdr as db')
                            ->where('db.compcode',session('compcode'))
                            ->where('db.idno',$invno)
                            ->update([
                                'LHDNStatus' => 'ACCEPTED',
                                'LHDNSubID' => $myresponse->submissionUid,
                                'LHDNCodeNo' => $acceptedDocument->invoiceCodeNumber,
                                'LHDNDocID' => $acceptedDocument->uuid,
                                'LHDNSubBy' => $username
                            ]);
            }
        }
    }

    public function check_invno_exist($inv_id){
        $einvoice = DB::table('sysdb.einvoice_log')
                        ->where('inv_idno',$inv_id)
                        ->where('status','ACCEPTED');

        return $einvoice->exists();
    }

    public function show_result(Request $request){
        $idno_array = $request->idno_array;

        $einvoices = DB::table('sysdb.einvoice_log')
            ->whereIn('inv_idno',$idno_array)
            ->get();

        $einvoices = $einvoices->unique('invno');

        return view('finance.GL.einvoice.show_result',compact('einvoices'));
    }
}