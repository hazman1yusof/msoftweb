<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;

class BankTransferController extends defaultController
{   

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        return view('finance.CM.bankTransfer.bankTransfer');
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

        if(!empty($request->fixPost)){
            $field = $this->fixPost2($request->field);
            $idno = substr(strstr($request->table_id,'_'),1);
        }else{
            $field = $request->field;
            $idno = $request->table_id;
        }

        $auditno = $this->recno('CM','FT');
        $pvno = $this->recno('HIS','PV');

        DB::beginTransaction();

        $table = DB::table("finance.apacthdr");
        
        $array_insert = [
            'source' => 'CM',
            'auditno' => $auditno,
            'trantype' => 'FT',
            'pvno' => $pvno,
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

            $responce = new stdClass();
            $responce->auditno = $auditno;
            $responce->idno = $idno;
            $responce->pvno = $pvno;
            echo json_encode($responce);

            // $queries = DB::getQueryLog();
            // dump($queries);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }

    }

    public function posted(Request $request){
        //1st step add cbtran -
        $prepare = "INSERT INTO finance.cbtran (compcode,bankcode,source,trantype,auditno,postdate,year,period,cheqno,amount,remarks,lastuser,lastupdate,bitype,reference,stat,refsrc,reftrantype,refauditno) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,NOW(),?,?,?,?,?,?)";

        $arrayValue = array($_SESSION['company'],$seldata['bankcode'],$seldata['source'],$seldata['trantype'],$seldata['auditno'],$seldata['actdate'],$tempobj->year,$tempobj->period,$seldata['cheqno'],-$seldata['amount'],$seldata['remarks'],$_SESSION['username'],null,'Transfer from :'. ' ' .$seldata['bankcode']  . ' ' . 'to'. ' '. $seldata[ 'payto'],'A',null,null,null);

        //$arrayValue = array($_SESSION['company'],$seldata['bankcode'],$seldata['source'],$seldata['trantype'],$seldata['auditno'],$seldata['actdate'],$tempobj->year,$tempobj->period,$seldata['cheqno'],-$seldata['amount'],$seldata['remarks'],$_SESSION['username'],null,null,'A',null,null,null);

        $this->save($prepare,$arrayValue);

        $apacthdr = DB::table('finance.apacthdr')
                        ->where('idno','=',$request->idno);

        $apacthdr_get = $apacthdr->first();

        DB::table('finance.cbtran')
            ->insert(['compcode' =>,
                        'bankcode' =>,
                        'source' =>,
                        'trantype','auditno','postdate','year','period','cheqno','amount','remarks','lastuser','lastupdate','bitype','reference','stat','refsrc','reftrantype','refauditno']);

    }
}
