<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;

class TillEnquiryController extends defaultController
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function show(Request $request)
    {
        return view('finance.AR.tillenquiry.tillenquiry');
    }
    
    public function table(Request $request)
    {
        DB::enableQueryLog();
        switch($request->action){
            case 'maintable':
                return $this->maintable($request);
            case 'get_tilldetl':
                return $this->get_tilldetl($request);
            case 'get_tillclose':
                return $this->get_tillclose($request);
            default:
                return 'error happen..';
        }
    }
    
    public function form(Request $request)
    {
        switch($request->oper){
            case 'add':
                return $this->add($request);
            case 'edit':
                return $this->edit($request);
            case 'del':
                return $this->del($request);
            default:
                return 'error happen..';
        }
    }
    
    public function maintable(Request $request){
        
        $table = DB::table('debtor.tilldetl')
                ->select('idno', 'compcode', 'tillcode', 'tillno', 'opendate', 'opentime', 'openamt', 'closedate', 'closetime', 'cashamt', 'cardamt', 'cheqamt', 'cnamt', 'otheramt', 'refcashamt', 'refcardamt', 'refchqamt', 'actclosebal', 'reason', 'cashier', 'upddate', 'upduser', 'adddate', 'adduser', 'deldate', 'deluser', 'recstatus')
                ->where('compcode','=',session('compcode'));
        
        if(!empty($request->filterCol)){
            $table = $table->where($request->filterCol[0],'=',$request->filterVal[0]);
        }
        
        if(!empty($request->filterdate)){
            // $table = $table->where('db.entrydate','>=',$request->filterdate[0]);
            // $table = $table->where('db.entrydate','<=',$request->filterdate[1]);
        }
        
        if(!empty($request->searchCol)){
            // if($request->searchCol[0] == 'db_invno'){
            //     $table = $table->Where(function ($table) use ($request) {
            //             $table->Where('db.invno','like',$request->searchVal[0]);
            //     });
            // }else{
                $table = $table->Where(function ($table) use ($request) {
                        $table->Where($request->searchCol[0],'like',$request->searchVal[0]);
                });
            // }
        }
        
        if(!empty($request->sidx)){
            $pieces = explode(", ", $request->sidx .' '. $request->sord);
            
            $table = $table->orderBy($request->sidx, $request->sord);
            
            // if(count($pieces)==1){
            //     $table = $table->orderBy($request->sidx, $request->sord);
            // }else{
            //     foreach ($pieces as $key => $value) {
            //         $value_ = substr_replace($value,"db.",0,strpos($value,"_")+1);
            //         $pieces_inside = explode(" ", $value_);
            //         $table = $table->orderBy($pieces_inside[0], $pieces_inside[1]);
            //     }
            // }
        }else{
            $table = $table->orderBy('idno','DESC');
        }
        
        $paginate = $table->paginate($request->rows);
        
        ////////////paginate////////////
        
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
    
    public function get_tilldetl(Request $request){
        
        $table = DB::table('debtor.dbacthdr as dh')
                ->select('dh.idno', 'dh.compcode', 'dh.source', 'dh.trantype', 'dh.auditno', 'dh.lineno_', 'dh.amount', 'dh.outamount', 'dh.recstatus', 'dh.entrydate', 'dh.entrytime', 'dh.entryuser', 'dh.reference', 'dh.recptno', 'dh.paymode', 'dh.tillcode', 'dh.tillno', 'dh.debtorcode', 'dh.payercode', 'dh.billdebtor', 'dh.remark', 'dh.mrn', 'dh.episno', 'dh.authno', 'dh.expdate', 'dh.adddate', 'dh.adduser', 'dh.upddate', 'dh.upduser', 'dh.epistype', 'dh.cbflag', 'dh.conversion', 'dh.payername', 'dh.hdrtype', 'dh.currency', 'dh.rate', 'dh.unit', 'dh.invno', 'dh.paytype', 'dh.bankcharges', 'dh.RCCASHbalance', 'dh.RCOSbalance', 'dh.RCFinalbalance', 'dh.PymtDescription', 'dh.posteddate', 'dm.debtortype')
                ->leftJoin('debtor.debtormast as dm', function($join) use ($request){
                    $join = $join->on('dm.debtorcode', '=', 'dh.payercode')
                                ->where('dm.compcode', '=', session('compcode'));
                })
                ->where('dh.compcode','=',session('compcode'))
                // ->where('dh.trantype','=','RC')
                ->where('dh.tillno','=',$request->tillno);
        
        //////////////////////////////////////////////////////////
        if(!empty($request->sidx)){
            $pieces = explode(", ", $request->sidx .' '. $request->sord);
            
            $table = $table->orderBy($request->sidx, $request->sord);
            
            // if(count($pieces)==1){
            //     $table = $table->orderBy($request->sidx, $request->sord);
            // }else{
            //     foreach ($pieces as $key => $value) {
            //         $value_ = substr_replace($value,"dh.",0,strpos($value,"_")+1);
            //         $pieces_inside = explode(" ", $value_);
            //         $table = $table->orderBy($pieces_inside[0], $pieces_inside[1]);
            //     }
            // }
        }else{
            $table = $table->orderBy('dh.idno','DESC');
        }
        
        ////////////paginate////////////
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
    
    public function get_tillclose(Request $request){
        
        $till = null;
        $tilldetl = null;
        $sum_cash = null;
        $sum_chq = null;
        $sum_card = null;
        $sum_bank = null;
        $sum_all = null;
        
        // $till_ = DB::table('debtor.till')
        //         ->where('compcode',session('compcode'))
        //         ->where('tillstatus','O')
        //         ->where('lastuser',session('username'));
        
        // if($till_->exists()){
        //     $till = $till_->first();
            
        //     $tilldetl_ = DB::table('debtor.tilldetl')
        //                 ->where('compcode',session('compcode'))
        //                 ->where('cashier',$till->lastuser);
            
        //     if($tilldetl_->exists()){
        //         $tilldetl = $tilldetl_->first();
                
                $dbacthdr = DB::table('debtor.dbacthdr as db')
                            ->where('db.compcode',session('compcode'))
                            ->where('db.tillcode',$request->tillcode)
                            ->where('db.tillno',$request->tillno)
                            // ->where('db.hdrtype','A')
                            ->join('debtor.paymode as pm', function($join) use ($request){
                                $join = $join->on('pm.paymode', '=', 'db.paymode')
                                            ->where('pm.source','AR')
                                            ->where('pm.compcode',session('compcode'));
                            });
                
                if($dbacthdr->exists()){
                    $sum_cash = DB::table('debtor.dbacthdr as db')
                                ->where('db.compcode',session('compcode'))
                                ->where('db.tillcode',$request->tillcode)
                                ->where('db.tillno',$request->tillno)
                                ->join('debtor.paymode as pm', function($join) use ($request){
                                    $join = $join->on('pm.paymode', '=', 'db.paymode')
                                                ->where('pm.source','AR')
                                                ->where('pm.paytype','CASH')
                                                ->where('pm.compcode',session('compcode'));
                                })
                                ->sum('amount');
                    
                    $sum_chq = DB::table('debtor.dbacthdr as db')
                                ->where('db.compcode',session('compcode'))
                                ->where('db.tillcode',$request->tillcode)
                                ->where('db.tillno',$request->tillno)
                                ->join('debtor.paymode as pm', function($join) use ($request){
                                    $join = $join->on('pm.paymode', '=', 'db.paymode')
                                                ->where('pm.source','AR')
                                                ->where('pm.paytype','CHEQUE')
                                                ->where('pm.compcode',session('compcode'));
                                })
                                ->sum('amount');
                    
                    $sum_card = DB::table('debtor.dbacthdr as db')
                                ->where('db.compcode',session('compcode'))
                                ->where('db.tillcode',$request->tillcode)
                                ->where('db.tillno',$request->tillno)
                                ->join('debtor.paymode as pm', function($join) use ($request){
                                    $join = $join->on('pm.paymode', '=', 'db.paymode')
                                                ->where('pm.source','AR')
                                                ->where('pm.paytype','CARD')
                                                ->where('pm.compcode',session('compcode'));
                                })
                                ->sum('amount');
                    
                    $sum_bank = DB::table('debtor.dbacthdr as db')
                                ->where('db.compcode',session('compcode'))
                                ->where('db.tillcode',$request->tillcode)
                                ->where('db.tillno',$request->tillno)
                                ->join('debtor.paymode as pm', function($join) use ($request){
                                    $join = $join->on('pm.paymode', '=', 'db.paymode')
                                                ->where('pm.source','AR')
                                                ->where('pm.paytype','BANK')
                                                ->where('pm.compcode',session('compcode'));
                                })
                                ->sum('amount');
                    
                    $sum_all = DB::table('debtor.dbacthdr as db')
                                ->where('db.compcode',session('compcode'))
                                ->where('db.tillcode',$request->tillcode)
                                ->where('db.tillno',$request->tillno)
                                ->sum('amount');
                }
            // }
        // }
        
        $responce = new stdClass();
        $responce->till = $till;
        $responce->tilldetl = $tilldetl;
        $responce->dbacthdr = $dbacthdr;
        $responce->sum_cash = $sum_cash;
        $responce->sum_chq = $sum_chq;
        $responce->sum_card = $sum_card;
        $responce->sum_bank = $sum_bank;
        $responce->sum_all = $sum_all;
        
        return json_encode($responce);
        // return view('finance.AR.till.till_close',compact('till','tilldetl','sum_cash','sum_chq','sum_card','sum_bank','sum_all'));
        
    }
    
    public function showpdf(Request $request){
        
        $tillno = $request->tillno;
        $tillcode = $request->tillcode;
        if(empty($tillno) || empty($tillcode)){
            abort(404);
        }
        
        $tilldetl = DB::table('debtor.tilldetl')
                    ->where('compcode',session('compcode'))
                    // ->where('tillcode',$request->tillcode)
                    ->where('tillno',$request->tillno)
                    ->first();
        
        $dbacthdr = DB::table('debtor.dbacthdr as dh', 'debtor.debtormast as dm', 'debtor.debtortype as dt')
                ->select('dh.idno', 'dh.compcode', 'dh.source', 'dh.trantype', 'dh.auditno', 'dh.lineno_', 'dh.amount', 'dh.outamount', 'dh.recstatus', 'dh.entrydate', 'dh.entrytime', 'dh.entryuser', 'dh.reference', 'dh.recptno', 'dh.paymode', 'dh.tillcode', 'dh.tillno', 'dh.debtorcode', 'dh.payercode', 'dh.billdebtor', 'dh.remark', 'dh.mrn', 'dh.episno', 'dh.authno', 'dh.expdate', 'dh.adddate', 'dh.adduser', 'dh.upddate', 'dh.upduser', 'dh.epistype', 'dh.cbflag', 'dh.conversion', 'dh.payername', 'dh.hdrtype', 'dh.currency', 'dh.rate', 'dh.unit', 'dh.invno', 'dh.paytype', 'dh.bankcharges', 'dh.RCCASHbalance', 'dh.RCOSbalance', 'dh.RCFinalbalance', 'dh.PymtDescription', 'dh.posteddate', 'dm.debtortype as dm_debtortype', 'dt.description as dt_description', 'dm.name as dm_name','pm.paytype as paytype_')
                ->leftJoin('debtor.debtormast as dm', function($join) use ($request){
                    $join = $join->on('dm.debtorcode', '=', 'dh.payercode')
                                ->where('dm.compcode', '=', session('compcode'));
                })
                ->leftJoin('debtor.debtortype as dt', function($join) use ($request){
                    $join = $join->on('dt.debtortycode', '=', 'dm.debtortype')
                                ->where('dt.compcode', '=', session('compcode'));
                })
                ->join('debtor.paymode as pm', function($join) use ($request){
                    $join = $join->on('pm.paymode', '=', 'dh.paymode')
                                ->where('pm.source','AR')
                                ->where('pm.compcode',session('compcode'));
                })
                ->where('dh.compcode','=',session('compcode'))
                ->where('dh.recstatus','POSTED')
                // ->where('dh.trantype','=','RC')
                ->where('dh.tillno','=',$request->tillno)
                ->get();

                // dd($dbacthdr);
        
        // $totalAmount = $dbacthdr->sum('amount');
        
        $db_dbacthdr = DB::table('debtor.dbacthdr as db')
                    ->where('db.compcode',session('compcode'))
                    ->where('db.recstatus','POSTED')
                    // ->where('db.tillcode',$request->tillcode)
                    ->where('db.tillno',$request->tillno)
                    // ->where('db.hdrtype','A')
                    ->join('debtor.paymode as pm', function($join) use ($request){
                        $join = $join->on('pm.paymode', '=', 'db.paymode')
                                    ->where('pm.source','AR')
                                    ->where('pm.compcode',session('compcode'));
                    });
        
        if($db_dbacthdr->exists()){
            $sum_cash = DB::table('debtor.dbacthdr as db')
                        ->where('db.compcode',session('compcode'))
                        ->where('db.recstatus','POSTED')
                        // ->where('db.tillcode',$request->tillcode)
                        ->where('db.tillno',$request->tillno)
                        ->whereIn('db.trantype',['RD','RC'])
                        ->join('debtor.paymode as pm', function($join) use ($request){
                            $join = $join->on('pm.paymode', '=', 'db.paymode')
                                        ->where('pm.source','AR')
                                        ->where('pm.paytype','CASH')
                                        ->where('pm.compcode',session('compcode'));
                        })
                        ->sum('amount');
            
            $sum_chq = DB::table('debtor.dbacthdr as db')
                        ->where('db.compcode',session('compcode'))
                        ->where('db.recstatus','POSTED')
                        // ->where('db.tillcode',$request->tillcode)
                        ->where('db.tillno',$request->tillno)
                        ->whereIn('db.trantype',['RD','RC'])
                        ->join('debtor.paymode as pm', function($join) use ($request){
                            $join = $join->on('pm.paymode', '=', 'db.paymode')
                                        ->where('pm.source','AR')
                                        ->where('pm.paytype','CHEQUE')
                                        ->where('pm.compcode',session('compcode'));
                        })
                        ->sum('amount');
            
            $sum_card = DB::table('debtor.dbacthdr as db')
                        ->where('db.compcode',session('compcode'))
                        ->where('db.recstatus','POSTED')
                        // ->where('db.tillcode',$request->tillcode)
                        ->where('db.tillno',$request->tillno)
                        ->whereIn('db.trantype',['RD','RC'])
                        ->join('debtor.paymode as pm', function($join) use ($request){
                            $join = $join->on('pm.paymode', '=', 'db.paymode')
                                        ->where('pm.source','AR')
                                        ->where('pm.paytype','CARD')
                                        ->where('pm.compcode',session('compcode'));
                        })
                        ->sum('amount');
            
            $sum_bank = DB::table('debtor.dbacthdr as db')
                        ->where('db.compcode',session('compcode'))
                        ->where('db.recstatus','POSTED')
                        // ->where('db.tillcode',$request->tillcode)
                        ->where('db.tillno',$request->tillno)
                        ->whereIn('db.trantype',['RD','RC'])
                        ->join('debtor.paymode as pm', function($join) use ($request){
                            $join = $join->on('pm.paymode', '=', 'db.paymode')
                                        ->where('pm.source','AR')
                                        ->where('pm.paytype','BANK')
                                        ->where('pm.compcode',session('compcode'));
                        })
                        ->sum('amount');
            
            $sum_all = DB::table('debtor.dbacthdr as db')
                        ->where('db.compcode',session('compcode'))
                        ->where('db.recstatus','POSTED')
                        // ->where('db.tillcode',$request->tillcode)
                        ->where('db.tillno',$request->tillno)
                        ->whereIn('db.trantype',['RD','RC'])
                        ->sum('amount');

            // dump($sum_all);
            
            $sum_cash_ref = DB::table('debtor.dbacthdr as db')
                            ->where('db.compcode',session('compcode'))
                            ->where('db.recstatus','POSTED')
                            // ->where('db.tillcode',$request->tillcode)
                            ->where('db.tillno',$request->tillno)
                            ->whereIn('db.trantype',['RF'])
                            ->join('debtor.paymode as pm', function($join) use ($request){
                                $join = $join->on('pm.paymode', '=', 'db.paymode')
                                            ->where('pm.source','AR')
                                            ->where('pm.paytype','CASH')
                                            ->where('pm.compcode',session('compcode'));
                            })
                            ->sum('amount');
            
            $sum_chq_ref = DB::table('debtor.dbacthdr as db')
                            ->where('db.compcode',session('compcode'))
                            ->where('db.recstatus','POSTED')
                            // ->where('db.tillcode',$request->tillcode)
                            ->where('db.tillno',$request->tillno)
                            ->whereIn('db.trantype',['RF'])
                            ->join('debtor.paymode as pm', function($join) use ($request){
                                $join = $join->on('pm.paymode', '=', 'db.paymode')
                                            ->where('pm.source','AR')
                                            ->where('pm.paytype','CHEQUE')
                                            ->where('pm.compcode',session('compcode'));
                            })
                            ->sum('amount');
            
            $sum_card_ref = DB::table('debtor.dbacthdr as db')
                            ->where('db.compcode',session('compcode'))
                            ->where('db.recstatus','POSTED')
                            // ->where('db.tillcode',$request->tillcode)
                            ->where('db.tillno',$request->tillno)
                            ->whereIn('db.trantype',['RF'])
                            ->join('debtor.paymode as pm', function($join) use ($request){
                                $join = $join->on('pm.paymode', '=', 'db.paymode')
                                            ->where('pm.source','AR')
                                            ->where('pm.paytype','CARD')
                                            ->where('pm.compcode',session('compcode'));
                            })
                            ->sum('amount');
            
            $sum_bank_ref = DB::table('debtor.dbacthdr as db')
                            ->where('db.compcode',session('compcode'))
                            ->where('db.recstatus','POSTED')
                            // ->where('db.tillcode',$request->tillcode)
                            ->where('db.tillno',$request->tillno)
                            ->whereIn('db.trantype',['RF'])
                            ->join('debtor.paymode as pm', function($join) use ($request){
                                $join = $join->on('pm.paymode', '=', 'db.paymode')
                                            ->where('pm.source','AR')
                                            ->where('pm.paytype','BANK')
                                            ->where('pm.compcode',session('compcode'));
                            })
                            ->sum('amount');
            
            $sum_all_ref = DB::table('debtor.dbacthdr as db')
                            ->where('db.compcode',session('compcode'))
                            ->where('db.recstatus','POSTED')
                            // ->where('db.tillcode',$request->tillcode)
                            ->where('db.tillno',$request->tillno)
                            ->whereIn('db.trantype',['RF'])
                            ->sum('amount');

            // dd($sum_all_ref);
        }else{
            abort(403, 'No Activity for this till on this period of time');
        }
        
        // if ($dbacthdr->recstatus == "ACTIVE") {
        //     $title = "DRAFT";
        // } elseif ($dbacthdr->recstatus == "POSTED"){
        //     $title = "RECEIPT";
        // }

        $dbacthdr_card = $dbacthdr->where('paytype_','Card');
        // dump($dbacthdr_card);
        $dbacthdr_card_unique = $dbacthdr->where('paytype_','Card')->unique('paymode');
        // dd($dbacthdr_card);
        
        $title = "TILL ENQUIRY";
        
        $company = DB::table('sysdb.company')
                    ->where('compcode','=',session('compcode'))
                    ->first();

        $totalAmount = $sum_all - $sum_all_ref;
        $totamount_expld = explode(".", (float)$totalAmount);
        
        // $totamt_bm_rm = $this->convertNumberToWordBM($totamount_expld[0])." RINGGIT ";
        // $totamt_bm = $totamt_bm_rm." SAHAJA";
        
        // if(count($totamount_expld) > 1){
        //     $totamt_bm_sen = $this->convertNumberToWordBM($totamount_expld[1])." SEN";
        //     $totamt_bm = $totamt_bm_rm.$totamt_bm_sen." SAHAJA";
        // }
        
        $totamt_eng_rm = $this->convertNumberToWordENG($totamount_expld[0])."";
        $totamt_eng = $totamt_eng_rm." ONLY";
        
        if(count($totamount_expld) > 1){
            $totamt_eng_sen = $this->convertNumberToWordENG($totamount_expld[1])." CENT";
            $totamt_eng = $totamt_eng_rm.$totamt_eng_sen." ONLY";
        }
        
        return view('finance.AR.tillenquiry.tillenquiry_pdfmake',compact('tilldetl','dbacthdr','totalAmount','sum_cash','sum_chq','sum_card','sum_bank','sum_all','sum_cash_ref','sum_chq_ref','sum_card_ref','sum_bank_ref','sum_all_ref','title','company','totamt_eng','dbacthdr_card','dbacthdr_card_unique'));
        
        // if(empty($request->type)){
        //     $pdf = PDF::loadView('finance.AP.paymentVoucher.paymentVoucher_pdf',compact('apacthdr','apalloc','totamt_eng','company', 'title'));
        //     return $pdf->stream();
        //     return view('finance.AP.paymentVoucher.paymentVoucher_pdf',compact('apacthdr','apalloc','totamt_eng','company', 'title'));
        // } else {
        //     return view('finance.AP.paymentVoucher.paymentVoucher_pdfmake',compact('apacthdr','apalloc','totamt_eng','company', 'title'));
        // }
        
    }
    
}