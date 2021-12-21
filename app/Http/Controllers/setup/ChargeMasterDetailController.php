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
                if (!empty($request->pkg_dtl)) {
                    return $this->add_pkgdtl($request);
                }
                return $this->add($request);

            case 'edit_all':
                if (!empty($request->pkg_dtl)) {
                    return $this->edit_all_pkgdtl($request);
                }
                return $this->edit_all($request);

            case 'del':
                if (!empty($request->pkg_dtl)) {
                    return $this->del_pkgdtl($request);
                }
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

            if($request->action == 'save_table_default'){ //ada yang save from form , ada yang save inline
                $effdate_chg = $request->effdate;
            }else{
                $effdate_chg = $this->turn_date($request->effdate);
            }

            if(empty($request->autopull)){
                    $request->autopull = null;
            }

            if(empty($request->addchg)){
                $request->addchg = null;
            }

            $chgmast = DB::table('hisdb.chgmast')
                        ->where('compcode','=',session('compcode'))
                        ->where('chgcode','=',$request->chgcode);

            if(!$chgmast->exists()){
                throw new \Exception('chgmast not exist', 500);
            }

            $chgmast_chgtype = strtoupper($chgmast->first()->chgtype);

            if($chgmast_chgtype == 'PKG'){
                $pkgstatus = 1;
            }else{
                $pkgstatus = 0;
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
                    'autopull' => $request->autopull,
                    'addchg' => $request->addchg,
                    'uom' => $request->uom,
                    'pkgstatus' => $pkgstatus,
                    'recstatus' => 'ACTIVE',
                    'unit' => session('unit'),
                    'adduser' => session('username'), 
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'lastuser' => session('username'), 
                    'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function edit_all(Request $request){
        DB::beginTransaction();

        try {

            foreach ($request->dataobj as $key => $value) {
                ///1. update detail

                if( !isset($value['autopull'])){
                    $value['autopull'] = null;
                }

                if( !isset($value['addchg'])){
                    $value['addchg'] = null;
                }

                DB::table('hisdb.chgprice')
                    ->where('compcode','=',session('compcode'))
                    ->where('idno','=',$value['idno'])
                    ->update([
                        'effdate' => $this->turn_date($value['effdate']),
                        'amt1' => $value['amt1'],
                        'amt2' => $value['amt2'],
                        'amt3' => $value['amt3'],
                        'iptax' => $value['iptax'],
                        'optax' => $value['optax'],
                        'costprice' => $value['costprice'],
                        'autopull' => $value['autopull'],
                        'addchg' => $value['addchg'],
                        'lastuser' => session('username'), 
                        'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    ]);
            }
         

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }

    public function del(Request $request){
        DB::beginTransaction();

        try {

            ///1. update detail
            DB::table('hisdb.chgprice')
                ->where('compcode','=',session('compcode'))
                ->where('idno','=',$request->idno)
                ->update([
                    'deluser' => session('username'),
                    'deldate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'recstatus' => 'DEACTIVE',
                    'lastcomputerid' => $request->lastcomputerid, 
                    'lastipaddress' => $request->lastipaddress, 
                ]);

       
            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }


    public function add_pkgdtl(Request $request){
        DB::beginTransaction();

        try {

            $sqlln = DB::table('hisdb.pkgdet')->select('lineno_')
                        ->where('compcode','=',session('compcode'))
                        ->where('pkgcode','=',$request->pkgcode)
                        ->whereDate('effectdate', $request->effectdate)
                        // ->where('effectdate','=',$request->effectdate)
                        ->count('lineno_');

            $li=intval($sqlln)+1;

            DB::table('hisdb.pkgdet')
                ->insert([
                    'lineno_' => $li,
                    'compcode' => session('compcode'),
                    'pkgcode' => $request->pkgcode,
                    'effectdate' =>  $this->turn_date($request->effectdate),
                    'chgcode' => $request->chgcode,
                    'quantity' => $request->quantity,
                    'actprice1' => $request->actprice1,
                    'pkgprice1' => $request->pkgprice1,
                    'totprice1' => $request->totprice1,
                    'actprice2' => $request->actprice2,
                    'pkgprice2' => $request->pkgprice2,
                    'totprice2' => $request->totprice2,
                    'actprice3' => $request->actprice3,
                    'pkgprice3' => $request->pkgprice3,
                    'totprice3' => $request->totprice3,
                    'recstatus' => 'ACTIVE',
                    'adduser' => session('username'), 
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'lastuser' => session('username'), 
                    'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                ]);

            $this->check_to_active_chgmast($request);


            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }

    }

    public function edit_all_pkgdtl(Request $request){
        DB::beginTransaction();

        try {

            foreach ($request->dataobj as $key => $value) {
                ///1. update detail
                DB::table('hisdb.pkgdet')
                    ->where('compcode','=',session('compcode'))
                    ->where('idno','=',$value['idno'])
                    ->update([
                        'chgcode' => $value['chgcode'],
                        'quantity' => $value['quantity'],
                        'actprice1' => $value['actprice1'],
                        'pkgprice1' => $value['pkgprice1'],
                        'totprice1' => $value['totprice1'],
                        'actprice2' => $value['actprice2'],
                        'pkgprice2' => $value['pkgprice2'],
                        'totprice2' => $value['totprice2'],
                        'actprice3' => $value['actprice3'],
                        'pkgprice3' => $value['pkgprice3'],
                        'totprice3' => $value['totprice3'],
                        'lastuser' => session('username'), 
                        'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    ]);
            }


            $this->check_to_active_chgmast($request);
         

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }

    }

    public function del_pkgdtl(Request $request){
        DB::beginTransaction();

        try {

            // DB::table('hisdb.pkgdet')
            //     ->where('compcode','=',session('compcode'))
            //     ->where('idno','=',$request->idno)
            //     ->delete();

            DB::table('hisdb.pkgdet')
                ->where('compcode','=',session('compcode'))
                ->where('idno','=',$request->idno)
                ->update([
                    'deluser' => session('username'),
                    'deldate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'recstatus' => 'DEACTIVE',
                    'lastcomputerid' => $request->lastcomputerid, 
                    'lastipaddress' => $request->lastipaddress, 
                ]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }

    }

    public function check_to_active_chgmast(Request $request){
        DB::enableQueryLog();
        $pkgcode = $request->pkgcode;
        $effectdate = $this->turn_date($request->effectdate)->toDateString();

        $chgprice = DB::table('hisdb.chgprice')
                        ->where('compcode','=',session('compcode'))
                        ->where('chgcode', '=', $pkgcode)
                        ->whereDate('effdate','=', $effectdate);

        if(!$chgprice->exists()){
            throw new \Exception('chgprice not exist', 500);
        };

        $chgprice_get = $chgprice->first();

        $pkgdet = DB::table('hisdb.pkgdet')
                    ->where('compcode','=',session('compcode'))
                    ->where('pkgcode', '=', $pkgcode)
                    ->where('effectdate', '=', $effectdate);

        if(!$pkgdet->exists()){
            throw new \Exception('pkgdet not exist', 500);
        };

        $pkgdet_get = $pkgdet->get();

        $grnd_tot1=$grnd_tot2=$grnd_tot3=0;
        foreach ($pkgdet_get as $key => $value) {
            $grnd_tot1 = $grnd_tot1 + $value->totprice1;
            $grnd_tot2 = $grnd_tot2 + $value->totprice2;
            $grnd_tot3 = $grnd_tot3 + $value->totprice3;
        }

        if(
            $chgprice_get->amt1 == $grnd_tot1 &&
            $chgprice_get->amt2 == $grnd_tot2 &&
            $chgprice_get->amt3 == $grnd_tot3 
        ){
            DB::table('hisdb.chgmast')
                ->where('compcode','=',session('compcode'))
                ->where('chgcode','=',$request->pkgcode)
                ->update([
                    'recstatus' => 'ACTIVE'
                ]);
        }else{
            DB::table('hisdb.chgmast')
                ->where('compcode','=',session('compcode'))
                ->where('chgcode','=',$request->pkgcode)
                ->update([
                    'recstatus' => 'DEACTIVE'
                ]);
        }

    }

}

