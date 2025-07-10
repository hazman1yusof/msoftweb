<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use DB;
use stdClass;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\acctenq_dateExport;

class acctenq_dateController extends defaultController
{   

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        return view('finance.GL.acctenq_date.acctenq_date');
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
    }

    public function table(Request $request){
        switch($request->action){
            case 'getdata';
                return $this->getdata($request);
            case 'openprint';
                return $this->openprint($request);
            case 'get_auditno_forsrc';
                return $this->get_auditno_forsrc($request);
            case 'print':
                return $this->print($request);
        }
    }

    public function getdata(Request $request){

        $responce = new stdClass();
        if(empty($request->glaccount)){
            $responce->data = [];
            return json_encode($responce);
        }

        if(!empty($request->order[0])){
            $sortid = intval($request->order[0]['column']);
            $sortdata = 'gl.'.$request->columns[$sortid]['data'];

            if($sortdata == 'gl.cramount' || $sortdata == 'gl.dramount'){
                $sortdata = 'gl.amount';
            }elseif ($sortdata == 'gl.open') {
                $sortdata = 'gl.postdate';
            }

            $sortdir = $request->order[0]['dir'];
        }else{
            $sortdata = 'gl.postdate';
            $sortdir = 'asc';
        }

        $table_ = DB::table('finance.gltran as gl')
                        ->select('gl.id','gl.source','gl.trantype','gl.auditno','gl.postdate','gl.description','gl.reference','gl.cracc','gl.dracc','gl.amount','glcr.description as acctname_cr','gldr.description as acctname_dr')
                        ->where(function($table_) use ($request){
                            $table_->orwhere('gl.dracc','=', $request->glaccount);
                            $table_->orwhere('gl.cracc','=', $request->glaccount);
                        })
                        ->leftJoin('finance.glmasref as glcr', function($join) use ($request){
                            $join = $join->on('glcr.glaccno', '=', 'gl.cracc')
                                            ->where('glcr.compcode','=',session('compcode'));
                        })
                        ->leftJoin('finance.glmasref as gldr', function($join) use ($request){
                            $join = $join->on('gldr.glaccno', '=', 'gl.dracc')
                                            ->where('gldr.compcode','=',session('compcode'));
                        })
                        ->where('gl.compcode', session('compcode'))
                        ->where('gl.postdate', '>=', $request->fromdate)
                        ->where('gl.postdate', '<=', $request->todate)
                        ->orderBy($sortdata, $sortdir);

        $count = $table_->count();
        $table = $table_
                    ->offset($request->start)
                    ->limit($request->length)->get();

        foreach ($table as $key => $value) {
            $value->open = "<i class='fa fa-folder-open-o' </i>";
            $value->print = "<i class='fa fa-print' </i>";
            if($value->dracc == $request->glaccount){
                $value->acccode = $value->cracc;
                $value->cramount = 0;
                $value->dramount = $value->amount;
                $value->acctname = $value->acctname_cr;
            }else{
                $value->acccode = $value->dracc;
                $value->cramount = $value->amount;
                $value->dramount = 0;
                $value->acctname = $value->acctname_dr;
            }

            switch ($value->source) {
                case 'OE':
                    $data = $this->oe_data($value);
                    break;
                case 'PB':
                    $data = $this->pb_data($value);
                    break;
                case 'AP':
                    $data = $this->ap_data($value);
                    break;
                case 'CM':
                    $data = $this->cm_data($value);
                    break;
                default:
                    $data = $this->oth_data($value);
                    break;
            }

            if(!empty($data)){
                $value->desc_ = $data->desc;
                $value->reference = $data->refe;
            }else{
                $value->desc_ = ' ';
            }
        }

        $responce->data = $table;
        $responce->recordsTotal = $count;
        $responce->recordsFiltered = $count;
        return json_encode($responce);
    }

    public function get_auditno_forsrc(Request $request){

        if($request->source == 'PB' && $request->trantype == 'IN'){
            $dbacthdr = DB::table('debtor.dbacthdr')
                            ->where('compcode',session('compcode'))
                            ->where('source',$request->source)
                            ->where('trantype',$request->trantype)
                            ->where('invno',$request->auditno)
                            ->first();

            $responce = new stdClass();
            $responce->dbacthdr = $dbacthdr;

            return json_encode($responce);
        }
    }

    public function openprint(Request $request){
        $gltran = DB::table('finance.gltran')
                    ->where('compcode',session('compcode'))
                    ->where('id',$request->id)
                    ->first();


        switch ($gltran->source) {
            case 'OE':
                $url = $this->oe($gltran);
                break;
            case 'PB':
                $url = $this->pb($gltran);
                break;
            case 'AP':
                $url = $this->ap($gltran);
                break;
            case 'CM':
                $url = $this->cm($gltran);
                break;
            
            default:
                $url = $this->oth($gltran);
                break;
        }

        $responce = new stdClass();
        $responce->url = $url;

        return json_encode($responce);
    }

    public function oe($gltran){
        $billsum = DB::table('debtor.billsum')
                        ->where('compcode',session('compcode'))
                        ->where('auditno',$gltran->auditno)
                        ->first();

        $dbacthdr = DB::table('debtor.dbacthdr')
                        ->where('compcode',session('compcode'))
                        ->where('source','=',$billsum->source)
                        ->where('trantype','=',$billsum->trantype)
                        ->where('auditno','=',$billsum->billno)
                        ->first();

        $url = './SalesOrder/showpdf?idno='.$dbacthdr->idno;

        return $url;
    }

    public function pb($gltran){

        if($gltran->trantype == 'IN'){

            $dbacthdr = DB::table('debtor.dbacthdr')
                            ->where('compcode',session('compcode'))
                            ->where('source','=','PB')
                            ->where('trantype','=','IN')
                            ->where('auditno','=',$gltran->auditno)
                            ->first();

            return './SalesOrder/showpdf?idno='.$dbacthdr->idno;
        }else if($gltran->trantype == 'DN'){

            return './DebitNote/showpdf?auditno='.$gltran->auditno;

        }else if($gltran->trantype == 'CN'){

            return './CreditNoteAR/showpdf?auditno='.$gltran->auditno;

        }else if($gltran->trantype == 'RC'){

            $dbacthdr = DB::table('debtor.dbacthdr')
                            ->where('compcode',session('compcode'))
                            ->where('source','=','PB')
                            ->where('trantype','=','RC')
                            ->where('auditno','=',$gltran->auditno)
                            ->first();

            return './receipt/showpdf?auditno='.$dbacthdr->idno;
        }else if($gltran->trantype == 'RD'){

            $dbacthdr = DB::table('debtor.dbacthdr')
                            ->where('compcode',session('compcode'))
                            ->where('source','=','PB')
                            ->where('trantype','=','RD')
                            ->where('auditno','=',$gltran->auditno)
                            ->first();

            return './receipt/showpdf?auditno='.$dbacthdr->idno;
        }else if($gltran->trantype == 'RF'){

            $dbacthdr = DB::table('debtor.dbacthdr')
                            ->where('compcode',session('compcode'))
                            ->where('source','=','PB')
                            ->where('trantype','=','RF')
                            ->where('auditno','=',$gltran->auditno)
                            ->first();

            return './receipt/showpdf?auditno='.$dbacthdr->idno;
        }
    }

    public function ap($gltran){

        if($gltran->trantype == 'IN'){

        }else if($gltran->trantype == 'DN'){

        }else if($gltran->trantype == 'CN'){
            
        }else if($gltran->trantype == 'PV'){

            // $apacthdr = DB::table('finance.apacthdr')
            //                 ->where('compcode',session('compcode'))
            //                 ->where('source','=','AP')
            //                 ->where('trantype','=','PV')
            //                 ->where('auditno','=',$gltran->auditno)
            //                 ->first();

            return './paymentVoucher/showpdf?auditno='.$gltran->auditno.'&trantype=PV';
        }else if($gltran->trantype == 'PD'){

            // $apacthdr = DB::table('finance.apacthdr')
            //                 ->where('compcode',session('compcode'))
            //                 ->where('source','=','AP')
            //                 ->where('trantype','=','PD')
            //                 ->where('auditno','=',$gltran->auditno)
            //                 ->first();

            return './paymentVoucher/showpdf?auditno='.$gltran->auditno.'&trantype=PD';
        }
    }

    public function cm($gltran){

        if($gltran->trantype == 'CA'){

            // $apacthdr = DB::table('finance.apacthdr')
            //                 ->where('compcode',session('compcode'))
            //                 ->where('source','=','CM')
            //                 ->where('trantype','=','CA')
            //                 ->where('auditno','=',$gltran->auditno)
            //                 ->first();

            return './creditDebitTrans/showpdf?auditno='.$gltran->auditno;

        }else if($gltran->trantype == 'DA'){

            return './creditDebitTrans/showpdf?auditno='.$gltran->auditno;
        }else if($gltran->trantype == 'BS'){
            
        }else if($gltran->trantype == 'BD'){
            
        }else if($gltran->trantype == 'BQ'){
            
        }else if($gltran->trantype == 'FT'){
            
        }else if($gltran->trantype == 'DP'){
            
        }
    }

    public function oth($gltran){

        if($gltran->source == 'DO' && $gltran->trantype == 'GRN'){

            return './deliveryOrder/showpdf?recno='.$gltran->auditno;
        }else if($gltran->source == 'IV' && $gltran->trantype == 'GRN'){

            return './deliveryOrder/showpdf?recno='.$gltran->auditno;
        }
    }

    public function oe_data($obj){
        $billsum = DB::table('debtor.billsum as bs')
                        ->select('bs.chggroup','ch.description')
                        ->leftJoin('hisdb.chgmast as ch', function($join){
                            $join = $join->on('ch.chgcode', '=', 'bs.chggroup')
                                            ->where('ch.compcode','=',session('compcode'));
                        })
                        ->where('bs.compcode',session('compcode'))
                        ->where('bs.auditno',$obj->auditno)
                        ->first();

        $responce = new stdClass();
        $responce->desc = $billsum->description;
        $responce->refe = 'INV-'.$obj->reference;

        return $responce;
    }

    public function pb_data($obj){
        $responce = new stdClass();

        if($obj->trantype == 'IN'){

            $dbacthdr = DB::table('debtor.dbacthdr as dbh')
                            ->select('dbh.payercode','dbm.name')
                            ->leftJoin('debtor.debtormast as dbm', function($join){
                                $join = $join->on('dbm.debtorcode', '=', 'dbh.payercode')
                                                ->where('dbm.compcode','=',session('compcode'));
                            })
                            ->where('dbh.compcode',session('compcode'))
                            ->where('dbh.source','=','PB')
                            ->where('dbh.trantype','=','IN')
                            ->where('dbh.auditno','=',$obj->auditno);
                            
            if($dbacthdr->exists()){
                $dbacthdr = $dbacthdr->first();
                $obj->description = $dbacthdr->payercode;
                $responce->desc = $dbacthdr->name;
            }

            $obj->reference = str_pad($obj->auditno, 7, "0", STR_PAD_LEFT);
            return $responce;

        }else if($obj->trantype == 'DN'){
            $dbacthdr = DB::table('debtor.dbacthdr as dbh')
                            ->select('dbh.payercode','dbm.name')
                            ->leftJoin('debtor.debtormast as dbm', function($join){
                                $join = $join->on('dbm.debtorcode', '=', 'dbh.payercode')
                                                ->where('dbm.compcode','=',session('compcode'));
                            })
                            ->where('dbh.compcode',session('compcode'))
                            ->where('dbh.source','=','PB')
                            ->where('dbh.trantype','=','DN')
                            ->where('dbh.auditno','=',$obj->auditno);

            if($dbacthdr->exists()){
                $dbacthdr = $dbacthdr->first();
                $obj->description = $dbacthdr->payercode;
                $responce->desc = $dbacthdr->name;
            }

            $obj->reference = 'DN-'.str_pad($obj->auditno, 7, "0", STR_PAD_LEFT);
            return $responce;

        }else if($obj->trantype == 'CN'){
            $dbacthdr = DB::table('debtor.dbacthdr as dbh')
                            ->select('dbh.payercode','dbm.name')
                            ->leftJoin('debtor.debtormast as dbm', function($join){
                                $join = $join->on('dbm.debtorcode', '=', 'dbh.payercode')
                                                ->where('dbm.compcode','=',session('compcode'));
                            })
                            ->where('dbh.compcode',session('compcode'))
                            ->where('dbh.source','=','PB')
                            ->where('dbh.trantype','=','CN')
                            ->where('dbh.auditno','=',$obj->auditno);

            if($dbacthdr->exists()){
                $dbacthdr = $dbacthdr->first();
                $obj->description = $dbacthdr->payercode;
                $responce->desc = $dbacthdr->name;
            }

            $obj->reference = 'CN-'.str_pad($obj->auditno, 7, "0", STR_PAD_LEFT);
            return $responce;

        }else if($obj->trantype == 'RC'){
            $dbacthdr = DB::table('debtor.dbacthdr as dbh')
                            ->select('dbh.payercode','dbm.name','dbh.recptno')
                            ->leftJoin('debtor.debtormast as dbm', function($join){
                                $join = $join->on('dbm.debtorcode', '=', 'dbh.payercode')
                                                ->where('dbm.compcode','=',session('compcode'));
                            })
                            ->where('dbh.compcode',session('compcode'))
                            ->where('dbh.source','=','PB')
                            ->where('dbh.trantype','=','RC')
                            ->where('dbh.auditno','=',$obj->auditno);

            if($dbacthdr->exists()){
                $dbacthdr = $dbacthdr->first();
                $obj->description = $dbacthdr->payercode;
                $responce->desc = $dbacthdr->name;
                $obj->reference = $dbacthdr->recptno;
            }

            return $responce;

        }else if($obj->trantype == 'RD'){
            $dbacthdr = DB::table('debtor.dbacthdr as dbh')
                            ->select('dbh.payercode','dbm.name','dbh.recptno')
                            ->leftJoin('debtor.debtormast as dbm', function($join){
                                $join = $join->on('dbm.debtorcode', '=', 'dbh.payercode')
                                                ->where('dbm.compcode','=',session('compcode'));
                            })
                            ->where('dbh.compcode',session('compcode'))
                            ->where('dbh.source','=','PB')
                            ->where('dbh.trantype','=','RD')
                            ->where('dbh.auditno','=',$obj->auditno);

            if($dbacthdr->exists()){
                $dbacthdr = $dbacthdr->first();
                $obj->description = $dbacthdr->payercode;
                $responce->desc = $dbacthdr->name;
                $obj->reference = $dbacthdr->recptno;
            }

            return $responce;

        }else if($obj->trantype == 'RF'){
            $dbacthdr = DB::table('debtor.dbacthdr as dbh')
                            ->select('dbh.payercode','dbm.name','dbh.recptno')
                            ->leftJoin('debtor.debtormast as dbm', function($join){
                                $join = $join->on('dbm.debtorcode', '=', 'dbh.payercode')
                                                ->where('dbm.compcode','=',session('compcode'));
                            })
                            ->where('dbh.compcode',session('compcode'))
                            ->where('dbh.source','=','PB')
                            ->where('dbh.trantype','=','RF')
                            ->where('dbh.auditno','=',$obj->auditno);

            if($dbacthdr->exists()){
                $dbacthdr = $dbacthdr->first();
                $obj->description = $dbacthdr->payercode;
                $responce->desc = $dbacthdr->name;
                $obj->reference = $dbacthdr->recptno;
            }

            return $responce;

        }
    }
    
    public function ap_data($obj){
        return 0;
    }
    
    public function cm_data($obj){
        return 0;
    }
    
    public function oth_data($obj){
        $responce = new stdClass();

        // $exp1 = explode('</br>', $obj->description);
        // $exp2 = explode(' ', $obj->reference);

        $obj->description = $obj->description;
        $responce->desc = $obj->description;
        $responce->refe = $obj->reference;

        return $responce;
    }

    public function print(Request $request){
        $glaccount = $request->glaccount;
        if(empty($glaccount)){
            abort(404);
        }
        return Excel::download(new acctenq_dateExport($request->glaccount,$request->fromdate,$request->todate), 'acctenq_dateExport.xlsx');
    }
}