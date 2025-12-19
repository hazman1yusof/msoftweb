<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use DateTime;
use Carbon\Carbon;
use Auth;

class TillController extends defaultController
{   
    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
        $this->duplicateCode = "tillcode";
    }

    public function show(Request $request)
    {   
        return view('finance.AR.till.till');
    }


    public function till_close(Request $request){  
        $till = null;
        $tilldetl = null;
        $sum_cash = null;
        $sum_chq = null;
        $sum_card = null;
        $sum_bank = null;
        $sum_all = null;
        $sum_cash_ref = null;
        $sum_chq_ref = null;
        $sum_card_ref = null;
        $sum_bank_ref = null;
        $sum_all_ref = null;

        $till_ = DB::table('debtor.till')
                        ->where('compcode',session('compcode'))
                        ->where('tillstatus','O')
                        ->where('lastuser',session('username'));

        if($till_->exists()){
            $till = $till_->first();

            $tilldetl_ = DB::table('debtor.tilldetl')
                        ->where('compcode',session('compcode'))
                        ->where('cashier',$till->lastuser)
                        ->where('closedate',$request->closedate);

            if($tilldetl_->exists()){
                $tilldetl = $tilldetl_->first();

                $dbacthdr = DB::table('debtor.dbacthdr as db')
                        ->where('db.compcode',session('compcode'))
                        ->where('db.tillcode',$tilldetl->tillcode)
                        ->where('db.tillno',$tilldetl->tillno)
                        // ->where('db.hdrtype','A')
                        ->join('debtor.paymode as pm', function($join) use ($request){
                            $join = $join->on('pm.paymode', '=', 'db.paymode')
                                            ->where('pm.source','AR')
                                            ->where('pm.compcode',session('compcode'));
                        });
                
                if($dbacthdr->exists()){

                    $sum_cash = DB::table('debtor.dbacthdr as db')
                                    ->where('db.compcode',session('compcode'))
                                    ->where('db.tillcode',$tilldetl->tillcode)
                                    ->where('db.tillno',$tilldetl->tillno)
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
                                    ->where('db.tillcode',$tilldetl->tillcode)
                                    ->where('db.tillno',$tilldetl->tillno)
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
                                    ->where('db.tillcode',$tilldetl->tillcode)
                                    ->where('db.tillno',$tilldetl->tillno)
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
                                    ->where('db.tillcode',$tilldetl->tillcode)
                                    ->where('db.tillno',$tilldetl->tillno)
                                    ->whereIn('db.trantype',['RD','RC'])
                                    ->join('debtor.paymode as pm', function($join) use ($request){
                                        $join = $join->on('pm.paymode', '=', 'db.paymode')
                                                        ->where('pm.source','AR')
                                                        ->where('pm.paytype','BANK')
                                                        ->where('pm.compcode',session('compcode'));
                                    })
                                    ->sum('amount');

                    $sum_cash_ref = DB::table('debtor.dbacthdr as db')
                                    ->where('db.compcode',session('compcode'))
                                    ->where('db.tillcode',$tilldetl->tillcode)
                                    ->where('db.tillno',$tilldetl->tillno)
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
                                    ->where('db.tillcode',$tilldetl->tillcode)
                                    ->where('db.tillno',$tilldetl->tillno)
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
                                    ->where('db.tillcode',$tilldetl->tillcode)
                                    ->where('db.tillno',$tilldetl->tillno)
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
                                    ->where('db.tillcode',$tilldetl->tillcode)
                                    ->where('db.tillno',$tilldetl->tillno)
                                    ->whereIn('db.trantype',['RF'])
                                    ->join('debtor.paymode as pm', function($join) use ($request){
                                        $join = $join->on('pm.paymode', '=', 'db.paymode')
                                                        ->where('pm.source','AR')
                                                        ->where('pm.paytype','BANK')
                                                        ->where('pm.compcode',session('compcode'));
                                    })
                                    ->sum('amount');
                        
                }
            }
        } 
        return view('finance.AR.till.till_close',compact('till','tilldetl','sum_cash','sum_chq','sum_card','sum_bank','sum_cash_ref','sum_chq_ref','sum_card_ref','sum_bank_ref'));
    }

    public function form(Request $request)
    {
        
        switch($request->action){
            case 'default':
                switch($request->oper){
                    case 'add':
                        return $this->add($request);break;
                    case 'edit':
                        return $this->edit($request);break;
                    case 'del':
                        return $this->del($request);break;
                    // case 'use_till':
                    //     return $this->use_till($request);break;
                    default:
                        return 'error happen..';
                }
            
            case 'save_till':
                switch($request->oper){
                    case 'close_till':
                        return $this->close_till($request);break;
                    default:
                        return 'error happen..';
                }

            case 'use_till':
                return $this->use_till($request);break;
            
            case 'get_table_till':
                return $this->get_table_till($request);

            default:
                    return 'error happen..';
        }
    }

    public function table(Request $request)
    {   
        switch($request->action){
            case 'checkifuserlogin':
                return $this->checkifuserlogin($request);
            default:
                return 'error happen..';
        }
    }
    
    public function close_till(Request $request){
        
        DB::beginTransaction();
        
        try {
            
            // dd($request->actclosebal);
            
            // $tillno = $this->defaultSysparam('AR','TN');
            
            DB::table('debtor.till')
                ->where('compcode',session('compcode'))
                ->where('tillcode','=',$request->tillcode)
                ->update([
                    'tillstatus' => 'C',
                    // 'dept' => auth()->user()->dept,
                    // 'lastuser' => session('username'),
                    'upduser' => session('username'),
                    'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                ]);
        
            DB::table('debtor.tilldetl')
                ->where('compcode',session('compcode'))
                ->where('tillno',$request->tillno)
                ->where('tillcode',$request->tillcode)
                // ->where('closedate',$request->closedate)
                ->update([
                    // 'tillno' => $tillno,
                    'actclosebal' => $request->actclosebal,
                    'reason' => $request->reason,
                    // 'cashier' => session('username'),
                    'closedate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'closetime' => Carbon::now("Asia/Kuala_Lumpur")
                ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
        
    }

    public function use_till(Request $request){
        DB::beginTransaction();
        try {

            $till = DB::table('debtor.till')
                        ->where('compcode',session('compcode'))
                        ->where('tillcode','=',$request->tillcode)
                        ->where('tillstatus','O');

            if($till->exists()){
                throw new \Exception("Till already open by someone else");
            }

            $tillno = $this->defaultSysparam('AR','TN');

            DB::table('debtor.till')
                ->where('tillcode','=',$request->tillcode)
                ->where('compcode',session('compcode'))
                ->update([
                    'compcode' => session('compcode'), 
                    'tillstatus' => 'O', 
                    'dept' => auth()->user()->dept,
                    'upduser' => session('username'),
                    'lastuser' => session('username'),
                    'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                ]);

            DB::table('debtor.tilldetl')
                ->insert([
                    'compcode' => session('compcode'), 
                    'tillcode' => $request->tillcode,
                    'tillno' => $tillno,
                    'openamt' => $request->openamt,
                    'cashamt' => $request->openamt,
                    'cashier' => session('username'),
                    'opendate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'opentime' => Carbon::now("Asia/Kuala_Lumpur")
                ]);

             DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }
    
    public function get_table_till(Request $request){
        
        $till_obj = DB::table('debtor.till')
                ->where('compcode','=',session('compcode'))
                ->where('tillcode','=',$request->tillcode);
        
        $tilldetl_obj = DB::table('debtor.tilldetl')
                    ->where('compcode','=',session('compcode'))
                    ->where('tillno','=',$request->tillno)
                    ->where('tillcode','=',$request->tillcode);
        
        $responce = new stdClass();
        
        if($till_obj->exists()){
            $till_obj = $till_obj->first();
            $responce->till = $till_obj;
        }
        
        if($tilldetl_obj->exists()){
            $tilldetl_obj = $tilldetl_obj->first();
            $responce->tilldetl = $tilldetl_obj;
        }
        
        return json_encode($responce);
        
    }

    public function add(Request $request){

        DB::beginTransaction();
        try {

            $tillcode = DB::table('debtor.till')
                            ->where('compcode','=',session('compcode'))
                            ->where('tillcode','=',$request->tillcode);

            if($tillcode->exists()){
                throw new \Exception("Record Duplicate");
            }
            DB::table('debtor.till')
                ->insert([
                    'compcode' => session('compcode'),
                    'adduser' => session('username'), 
                    'recstatus' => 'ACTIVE',
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"), 
                    'tillcode' => $request->tillcode, 
                    'description' => $request->description, 
                    'dept' => $request->dept, 
                    'effectdate' => $request->effectdate, 
                    'defopenamt' => $request->defopenamt, 
                    'tillstatus' => $request->tillstatus, 
                    'lastrcnumber' => $request->lastrcnumber, 
                    'lastrefundno' => $request->lastrefundno, 
                    'lastcrnoteno' => $request->lastcrnoteno, 
                    'lastinnumber' => $request->lastinnumber,
                    'computerid' => session('computerid'),
                ]);

             DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function edit(Request $request){
        
        DB::beginTransaction();
        try {
            
            DB::table('debtor.till')
                ->where('tillcode','=',$request->tillcode)
                ->update([
                    'description' => $request->description, 
                    'dept' => $request->dept, 
                    'effectdate' => $request->effectdate, 
                    'defopenamt' => $request->defopenamt, 
                    'tillstatus' => $request->tillstatus, 
                    'lastrcnumber' => $request->lastrcnumber, 
                    'lastrefundno' => $request->lastrefundno, 
                    'lastcrnoteno' => $request->lastcrnoteno, 
                    'lastinnumber' => $request->lastinnumber,
                    'upduser' => strtoupper(session('username')),
                    'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'lastcomputerid' => session('computerid'),
                ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function del(Request $request){
        DB::table('debtor.till')
            ->where('tillcode','=',$request->tillcode)
            ->update([  
                'recstatus' => 'DEACTIVE',
                'deluser' => strtoupper(session('username')),
                'deldate' => Carbon::now("Asia/Kuala_Lumpur"),
                'computerid' => session('computerid')
            ]);
    }

    public function checkifuserlogin(Request $request){

        $tilldetl = DB::table('debtor.till as t')
                            ->leftJoin('debtor.tilldetl as td', function($join) use ($request){
                                $join = $join->where('td.compcode', session('compcode'));
                                $join = $join->on('td.tillcode', 't.tillcode');
                                $join = $join->on('td.cashier', 't.lastuser');
                                $join = $join->on('td.opendate', 't.upddate');
                                $join = $join->whereNull('closedate');
                            })
                            ->where('t.compcode',session('compcode'))
                            ->where('t.tillstatus','O')
                            ->where('t.lastuser',session('username'));

        // $tilldetl = DB::table('debtor.tilldetl')
        //             ->where('compcode',session('compcode'))
        //             ->where('cashier',session('username'))
        //             ->whereNull('closedate');

        $responce = new stdClass();
        $responce->rows = $tilldetl->get();
        return json_encode($responce);
    }
}