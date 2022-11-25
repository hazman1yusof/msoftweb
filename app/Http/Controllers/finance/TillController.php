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

    public function till_close(Request $request)
    {   

        $till = null;
        $tilldetl = null;
        $sum_cash = null;
        $sum_chq = null;
        $sum_card = null;
        $sum_bank = null;
        $sum_all = null;

        $till_ = DB::table('debtor.till')
                        ->where('compcode',session('compcode'))
                        ->where('tillstatus','O')
                        ->where('lastuser',session('username'));

        if($till_->exists()){
            $till = $till_->first();

            $tilldetl_ = DB::table('debtor.tilldetl')
                        ->where('compcode',session('compcode'))
                        ->where('cashier',$till->lastuser)
                        ->whereDate('opendate',$till->upddate);

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
                                    ->join('debtor.paymode as pm', function($join) use ($request){
                                        $join = $join->on('pm.paymode', '=', 'db.paymode')
                                                        ->where('pm.source','AR')
                                                        ->where('pm.paytype','BANK')
                                                        ->where('pm.compcode',session('compcode'));
                                    })
                                    ->sum('amount');
                    $sum_all = DB::table('debtor.dbacthdr as db')
                                    ->where('db.compcode',session('compcode'))
                                    ->where('db.tillcode',$tilldetl->tillcode)
                                    ->where('db.tillno',$tilldetl->tillno)
                                    ->sum('amount');
                }

            }
        }

        

        return view('finance.AR.till.till_close',compact('till','tilldetl','sum_cash','sum_chq','sum_card','sum_bank','sum_all'));
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
                    default:
                        return 'error happen..';
                }
            case 'use_till':
                return $this->use_till($request);
        }
    }

    public function use_till(Request $request){
        DB::beginTransaction();
        try {

            $tillno = $this->defaultSysparam('AR','TN');

            DB::table('debtor.till')
                ->where('tillcode','=',$request->tillcode)
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

    public function add(Request $request){

        DB::beginTransaction();
        try {

            $tillcode = DB::table('debtor.till')
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
                    'upddate' => Carbon::now("Asia/Kuala_Lumpur")
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
                'deldate' => Carbon::now("Asia/Kuala_Lumpur")
            ]);
    }
}
