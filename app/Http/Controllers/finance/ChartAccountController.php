<?php

namespace App\Http\Controllers\finance;

use Illuminate\Http\Request;
use stdClass;
use App\Http\Controllers\defaultController;
use DB;

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

    public function add(Request $request){

        DB::beginTransaction();
        try {

            // $category = DB::table('material.category')
            //                 ->where('compcode','=',session('compcode'))
            //                 ->where('catcode','=',strtoupper($request->catcode))
            //                 ->where('cattype','=',strtoupper($request->cattype))
            //                 ->where('source','=',$source);

            // if($category->exists()){
            //     throw new \Exception("Record Duplicate");
            // }

            DB::table('finance.glmasdtl')
                ->insert([  
                    'compcode' => session('compcode'),
                    'costcode' => strtoupper($request->glmasdtl_costcode),
                    'glaccount' => $request->glmasdtl_glaccount,
                    'year' => $request->glmasdtl_year,
                    'computerid' => session('computerid'),
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur")
                ]);

             DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            $responce = new stdClass();
            $responce->errormsg = $e->getMessage();
            $responce->request = $_REQUEST;

            return response(json_encode($responce), 500);
        }
    }

    public function getdata(Request $request){

        $responce = new stdClass();

        if(empty($request->costcode)){
            $responce->data = [];
            return json_encode($responce);
        }

        $table_ = DB::table('finance.gltran')
                    ->select(DB::raw("'open' as open"),'gltran.source','gltran.trantype','gltran.auditno','gltran.postdate','gltran.description','gltran.reference','gltran.cracc','gltran.dracc','gltran.amount','glcr.description as acctname_cr','gldr.description as acctname_dr','gltran.id')
                    ->leftJoin('finance.glmasref as glcr', function($join) use ($request){
                        $join = $join->on('glcr.glaccno', '=', 'gltran.cracc')
                                        ->where('glcr.compcode','=',session('compcode'));
                    })
                    ->leftJoin('finance.glmasref as gldr', function($join) use ($request){
                        $join = $join->on('gldr.glaccno', '=', 'gltran.dracc')
                                        ->where('gldr.compcode','=',session('compcode'));
                    })
                    ->where('gltran.compcode',session('compcode'))
                    ->where('gltran.year',$request->year)
                    ->where('gltran.period',$request->period)
                    ->where('gltran.crcostcode',$request->costcode)
                    ->where('gltran.cracc',$request->acc)
                    ->orWhere(function ($table) use ($request) {
                        $table
                        ->where('gltran.compcode',session('compcode'))
                        ->where('gltran.year',$request->year)
                        ->where('gltran.period',$request->period)
                        ->where('gltran.drcostcode',$request->costcode)
                        ->where('gltran.dracc',$request->acc);
                    })->orderBy('gltran.id','desc');

        $count = $table_->count();
        $table = $table_->get();

        foreach ($table as $key => $value) {
            if(strtoupper($value->cracc) == strtoupper($request->acc)){
                $value->acccode = $value->dracc;
                $value->dramount = '';
                $value->cramount = $value->amount;
                $value->acctname = $value->acctname_dr;
            }else{
                $value->acccode = $value->cracc;
                $value->dramount = $value->amount;
                $value->cramount = '';
                $value->acctname = $value->acctname_cr;
            }
        }

        // $table_cr = DB::table('finance.gltran')
        //             ->select(DB::raw("'open' as open"),DB::raw("'' as dramount"),'gltran.source','gltran.trantype','gltran.auditno','gltran.postdate','gltran.description','gltran.reference','gltran.dracc as acccode','gltran.amount as cramount','glmasref.description as acctname','gltran.id')
        //             ->leftJoin('finance.glmasref', function($join) use ($request){
        //                 $join = $join->on('glmasref.glaccno', '=', 'gltran.dracc')
        //                                 ->where('glmasref.compcode','=',session('compcode'));
        //             })
        //             ->where('gltran.compcode',session('compcode'))
        //             ->where('gltran.crcostcode',$request->costcode)
        //             ->where('gltran.cracc',$request->acc)
        //             ->where('gltran.year',$request->year)
        //             ->where('gltran.period',$request->period)
        //             ->get();

        // $table_merge = $table_dr->merge($table_cr);

        $responce->data = $table;
        $responce->recordsTotal = $count;
        $responce->recordsFiltered = $count;
        return json_encode($responce);
    }
}