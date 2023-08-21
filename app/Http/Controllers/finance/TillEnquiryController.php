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
                ->select('compcode', 'tillcode', 'tillno', 'opendate', 'opentime', 'openamt', 'closedate', 'closetime', 'cashamt', 'cardamt', 'cheqamt', 'cnamt', 'otheramt', 'refcashamt', 'refcardamt', 'refchqamt', 'actclosebal', 'reason', 'cashier', 'upddate', 'upduser', 'adddate', 'adduser', 'deldate', 'deluser', 'recstatus')
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
        
        $table = DB::table('debtor.dbacthdr')
                ->select('idno', 'compcode', 'source', 'trantype', 'auditno', 'lineno_', 'amount', 'outamount', 'recstatus', 'entrydate', 'entrytime', 'entryuser', 'reference', 'recptno', 'paymode', 'tillcode', 'tillno', 'debtortype', 'debtorcode', 'payercode', 'billdebtor', 'remark', 'mrn', 'episno', 'authno', 'expdate', 'adddate', 'adduser', 'upddate', 'upduser', 'epistype', 'cbflag', 'conversion', 'payername', 'hdrtype', 'currency', 'rate', 'unit', 'invno', 'paytype', 'bankcharges', 'RCCASHbalance', 'RCOSbalance', 'RCFinalbalance', 'PymtDescription', 'posteddate')
                ->where('compcode','=',session('compcode'))
                ->where('trantype','=','RC')
                ->where('tillno','=',$request->tillno);
        
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
        //                 ->where('compcode',session('compcode'))
        //                 ->where('tillstatus','O')
        //                 ->where('lastuser',session('username'));

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
        //     }
        // } 

        $responce = new stdClass();
        $responce->till = $till;
        $responce->tilldetl = $tilldetl;
        $responce->sum_cash = $sum_cash;
        $responce->sum_chq = $sum_chq;
        $responce->sum_card = $sum_card;
        $responce->sum_bank = $sum_bank;
        $responce->sum_all = $sum_all;

        return json_encode($responce);
        // return view('finance.AR.till.till_close',compact('till','tilldetl','sum_cash','sum_chq','sum_card','sum_bank','sum_all'));
    }
    
}