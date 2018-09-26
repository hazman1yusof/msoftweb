<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;

/*class InvoiceAPController extends defaultController
{   

    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
        $this->duplicateCode = "auditno";
    }

    public function show(Request $request)
    {   
        return view('finance.AP.invoiceAP.invoiceAP');
    }

    public function form(Request $request)
    {  
        switch($request->oper){
            case 'add':
                return $this->defaultAdd($request);
            case 'edit':
                return $this->defaultEdit($request);
            case 'del':
                return $this->defaultDel($request);
            default:
                return 'error happen..';
        }
    }*/

    class InvoiceAPController extends defaultController
{   

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        return view('finance.AP.invoiceAP.invoiceAP');
    }

    public function form(Request $request)
    {   
        DB::enableQueryLog();
        switch($request->oper){
            case 'add':
                return $this->add($request);
            case 'edit':
                return $this->edit($request);
            case 'del':
                return $this->del($request);
            case 'posted':
                return $this->posted($request);
            case 'cancel':
                return $this->cancel($request);
            default:
                return 'error happen..';
        }
    }

    public function add(Request $request){

        if(!empty($request->fixPost)){
            $field = $this->fixPost2($request->field);
            $auditno = substr(strstr($request->table_id,'_'),1);
        }else{
            $field = $request->field;
            $auditno = $request->table_id;
        }

        $request_no = $this->request_no($request->source, $request->trantype);
        $suppgroup = $this->suppgroup($request->suppcode);

        DB::beginTransaction();

        $table = DB::table("finance.apacthdr");

        $array_insert = [
            'source' => 'AP',
            'auditno' => $request_no,
            'trantype' => $request->trantype,
            'suppcode' => $request->suppcode,
            'suppgroup' => $suppgroup,
            'payto' => $request->payto,
            'document' => $request->document,
            'category' => $request->category,
            'amount' => $request->amount,
            'outamount' => $request->amount,
            'remarks' => $request->remarks,
            'actdate' => $request->actdate,
            'recdate' => $request->recdate,
            'deptcode' => $request->deptcode,
            'compcode' => session('compcode'),
            'adduser' => session('username'),
            'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
            'recstatus' => 'OPEN'
        ];

        foreach ($field as $key => $value){
            $array_insert[$value] = $request[$request->field[$key]];
        }

        try {

            $idno = $table->insertGetId($array_insert);

            $totalAmount = 0;

            $responce = new stdClass();
            $responce->auditno = $request_no;
            $responce->idno = $idno;
            $responce->suppgroup = $suppgroup;
          //  $responce->totalAmount = $totalAmount;
            echo json_encode($responce);

            // $queries = DB::getQueryLog();
            // dump($queries);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }

    }

   
}