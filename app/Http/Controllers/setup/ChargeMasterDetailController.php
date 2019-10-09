<?php

namespace App\Http\Controllers\setup;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class ChargeMasterDetailController extends defaultController
{   
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function form(Request $request)
    {   
        switch($request->oper){
            case 'add':
                return $this->add($request);

            case 'edit_all':
                return $this->edit_all($request);

            case 'del':
                return $this->del($request);

            default:
                return 'error happen..';
        }
    }

    public function add(Request $request){
        DB::beginTransaction();

        try {

            $sqlln = DB::table('hisdb.chgprice')->select('lineno_')
                        ->where('compcode','=',session('compcode'))
                        ->where('chgcode','=',$request->chgcode)
                        ->count('lineno_');

            $li=intval($sqlln)+1;

            if($request->action == 'save_table_default'){
                $effdate_chg = $request->effdate;
            }else{
                $effdate_chg = $this->turn_date($request->effdate);
            }

            DB::table('hisdb.chgprice')
                ->insert([
                    'lineno_' => $li,
                    'compcode' => session('compcode'),
                    'chgcode' => $request->chgcode,
                    'effdate' => $effdate_chg,
                    'minamt' => $request->minamt,
                    'amt1' => $request->amt1,
                    'amt2' => $request->amt2,
                    'amt3' => $request->amt3,
                    'iptax' => $request->iptax,
                    'optax' => $request->optax,
                    'maxamt' => $request->maxamt,
                    'costprice' => $request->costprice,
                    'uom' => $request->uom,
                    'units' => session('unit'),
                    'adduser' => session('username'), 
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }
    }

    public function edit_all(Request $request){
        
    }

    public function del(Request $request){
        
    }

}

