<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use DB;
use stdClass;

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
        }
    }

    public function getdata(Request $request){

        $responce = new stdClass();
        if(empty($request->glaccount)){
            $responce->data = [];
            return json_encode($responce);
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
                        ->where('gl.postdate', '>=', $request->fromdate)
                        ->where('gl.postdate', '<=', $request->todate)
                        ->orderBy('gl.postdate', 'desc');

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

        if($gltran->auditno == 'IN'){

            $dbacthdr = DB::table('debtor.dbacthdr')
                            ->where('compcode',session('compcode'))
                            ->where('source','=','PB')
                            ->where('trantype','=','IN')
                            ->where('auditno','=',$gltran->auditno)
                            ->first();

            return './SalesOrder/showpdf?idno='.$dbacthdr->idno;
        }else if($gltran->auditno == 'DN'){

            return './DebitNote/showpdf?auditno='.$gltran->auditno;

        }else if($gltran->auditno == 'CN'){

            return './CreditNoteAR/showpdf?auditno='.$gltran->auditno;

        }else if($gltran->auditno == 'RC'){

            $dbacthdr = DB::table('debtor.dbacthdr')
                            ->where('compcode',session('compcode'))
                            ->where('source','=','PB')
                            ->where('trantype','=','RC')
                            ->where('auditno','=',$gltran->auditno)
                            ->first();

            return './receipt/showpdf?auditno='.$dbacthdr->idno;
        }else if($gltran->auditno == 'RD'){

            $dbacthdr = DB::table('debtor.dbacthdr')
                            ->where('compcode',session('compcode'))
                            ->where('source','=','PB')
                            ->where('trantype','=','RD')
                            ->where('auditno','=',$gltran->auditno)
                            ->first();

            return './receipt/showpdf?auditno='.$dbacthdr->idno;
        }else if($gltran->auditno == 'RF'){

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

        if($gltran->auditno == 'IN'){

        }else if($gltran->auditno == 'DN'){

        }else if($gltran->auditno == 'CN'){
            
        }else if($gltran->auditno == 'PV'){

            // $apacthdr = DB::table('finance.apacthdr')
            //                 ->where('compcode',session('compcode'))
            //                 ->where('source','=','AP')
            //                 ->where('trantype','=','PV')
            //                 ->where('auditno','=',$gltran->auditno)
            //                 ->first();

            return './paymentVoucher/showpdf?auditno='.$gltran->auditno.'&trantype=PV';
        }else if($gltran->auditno == 'PD'){

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

        if($gltran->auditno == 'CA'){

            // $apacthdr = DB::table('finance.apacthdr')
            //                 ->where('compcode',session('compcode'))
            //                 ->where('source','=','CM')
            //                 ->where('trantype','=','CA')
            //                 ->where('auditno','=',$gltran->auditno)
            //                 ->first();

            return './creditDebitTrans/showpdf?auditno='.$gltran->auditno;

        }else if($gltran->auditno == 'DA'){

            return './creditDebitTrans/showpdf?auditno='.$gltran->auditno;
        }else if($gltran->auditno == 'BS'){
            
        }else if($gltran->auditno == 'BD'){
            
        }else if($gltran->auditno == 'BQ'){
            
        }else if($gltran->auditno == 'FT'){
            
        }else if($gltran->auditno == 'DP'){
            
        }
    }

    public function oth($gltran){

        if($gltran->trantype == 'DO' && $gltran->auditno == 'GRN'){

            return './deliveryOrder/showpdf?recno='.$gltran->auditno;
        }else if($gltran->trantype == 'IV' && $gltran->auditno == 'GRN'){

            return './deliveryOrder/showpdf?recno='.$gltran->auditno;
        }
    }
}