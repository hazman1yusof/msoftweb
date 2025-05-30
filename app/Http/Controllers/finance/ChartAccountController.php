<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use stdClass;
use App\Http\Controllers\defaultController;
use DB;
use DateTime;
use Carbon\Carbon;
use Illuminate\Support\Number;

class ChartAccountController extends defaultController
{   

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {   
        return view('finance.GL.chartAccount.chartAccount');
    }

    public function table(Request $request){   
        switch($request->action){
            case 'maintable':
                return $this->maintable($request);
            case 'getdata':
                return $this->getdata($request);
            default:
                return 'error happen..';
        }
    }

    public function maintable(Request $request){

        $table = DB::table('finance.glmasdtl AS glm')
                    ->select(
                        'glm.compcode','glm.costcode','glm.glaccount','glm.year','glm.openbalance','glm.actamount1','glm.actamount2','glm.actamount3','glm.actamount4','glm.actamount5','glm.actamount6','glm.actamount7','glm.actamount8','glm.actamount9','glm.actamount10','glm.actamount11','glm.actamount12','glm.bdgamount1','glm.bdgamount2','glm.bdgamount3','glm.bdgamount4','glm.bdgamount5','glm.bdgamount6','glm.bdgamount7','glm.bdgamount8','glm.bdgamount9','glm.bdgamount10','glm.bdgamount11','glm.bdgamount12','glm.foramount1','glm.foramount2','glm.foramount3','glm.foramount4','glm.foramount5','glm.foramount6','glm.foramount7','glm.foramount8','glm.foramount9','glm.foramount10','glm.foramount11','glm.foramount12','glm.adduser','glm.adddate','glm.upduser','glm.upddate','glm.recstatus','glm.idno','cc.costcode','cc.description',

                    )
                    ->leftJoin('finance.costcenter as cc', function($join){
                        $join = $join->on('cc.costcode', '=', 'glm.costcode')
                                    ->where('cc.compcode','=',session('compcode'));
                    })
                    ->where('glm.compcode',session('compcode'))
                    ->where('glm.glaccount',$request->glaccount)
                    ->where('glm.year',$request->year);

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

    public function form(Request $request)
    {   
        switch($request->action){
            case 'save_budget':
                switch($request->oper){
                    case 'saveBudget':
                        return $this->saveBudget($request);break;
                    default:
                        return 'error happen..';
                }
            case 'chartAccount_save':
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
            default:
                    return 'error happen..';
        }
    }

    public function add(Request $request){
        DB::beginTransaction();
        try {

            $glmasdtl = DB::table('finance.glmasdtl')
                            ->where('compcode','=',session('compcode'))
                            ->where('year','=',$request->year)
                            ->where('costcode','=',$request->costcode)
                            ->where('glaccount','=',$request->glaccount);

            if($glmasdtl->exists()){
                throw new \Exception("Record Duplicate");
            }

            DB::table('finance.glmasdtl')
                ->insert([  
                    'compcode' => session('compcode'),
                    'costcode' => $request->costcode,
                    'glaccount' => $request->glaccount,
                    'year' => $request->year,
                    'recstatus' => 'ACTIVE',
                    // 'computerid' => session('computerid'),
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur")
                ]);

             DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            // $responce = new stdClass();
            // // $responce->errormsg = $e->getMessage();
            // $responce->request = $_REQUEST;

            return response($e->getMessage(), 500);
        }
    }

    public function edit(Request $request){
        
        DB::beginTransaction();
        try {

            DB::table('finance.glmasdtl')
                ->where('idno','=',$request->idno)
                ->update([  
                    'costcode' => strtoupper($request->costcode),
                    'glaccount' => $request->glaccount,
                    'year' => $request->year,
                    'recstatus' => 'ACTIVE',
                    'lastcomputerid' => session('computerid'),
                    'upduser' => session('username'),
                    'upddate' => Carbon::now("Asia/Kuala_Lumpur")
                ]); 

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

             return response($e->getMessage(), 500);
        }
    }

    public function del(Request $request){
        DB::table('finance.glmasdtl')
            ->where('idno','=',$request->idno)
            ->update([  
                'recstatus' => 'DEACTIVE',
                'deluser' => session('username'),
                'deldate' => Carbon::now("Asia/Kuala_Lumpur"),
                'computerid' => session('computerid')
            ]);
    }

    public function saveBudget(Request $request){
        //  dd('hh');
        DB::beginTransaction();
        
        try {
            DB::table('finance.glmasdtl')
                ->where('compcode',session('compcode'))
                ->where('costcode',$request->costcode)
                ->where('glaccount',$request->glaccount)
                ->where('year',$request->year)
                ->update([
                    'bdgamount1' => $request->bdgamount1,
                    'bdgamount2' => $request->bdgamount2,
                    'bdgamount3' => $request->bdgamount3,
                    'bdgamount4' => $request->bdgamount4,
                    'bdgamount5' => $request->bdgamount5,
                    'bdgamount6' => $request->bdgamount6,
                    'bdgamount7' => $request->bdgamount7,
                    'bdgamount8' => $request->bdgamount8,
                    'bdgamount9' => $request->bdgamount9,
                    'bdgamount10' => $request->bdgamount10,
                    'bdgamount11' => $request->bdgamount11,
                    'bdgamount12' => $request->bdgamount12,
                    'recstatus' => 'ACTIVE',
                    'upduser' => session('username'),
                    'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                ]);

            DB::commit();
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            return response($e->getMessage(), 500);
            
        }
        
    }
}