<?php

namespace App\Http\Controllers\setup;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Auth;
use Carbon\Carbon;
use DateTime;

class ChargeMasterController extends defaultController
{   

    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
        $this->duplicateCode = "chgcode";
    }

    public function show(Request $request)
    {   
        return view('setup.chargemaster.chargemaster');
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

    public function add(Request $request){

        DB::beginTransaction();
        DB::enableQueryLog();


        try {

            $duplicate = DB::table('hisdb.chgmast')->where('chgcode','=',$request->cm_chgcode);

            if($duplicate->exists()){
                throw new \Exception('chgcode already exist', 500);
            }

            if($request->cm_chgtype == 'PKG' || $request->cm_chgtype == 'pkg'){
                $recstatus_use = 'D';
            }else{
                $recstatus_use = 'A';
            }

            DB::table('hisdb.chgmast')
                ->insert([
                    'compcode' => session('compcode'),
                    'chgcode' => $request->cm_chgcode,
                    'description' => $request->cm_description,
                    'chgclass' => $request->cm_chgclass,
                    'chggroup' => $request->cm_chggroup,
                    'chgtype' => $request->cm_chgtype,
                    'uom' => $request->cm_uom,
                    'brandname' => $request->cm_brandname,
                    'barcode' => $request->cm_barcode,
                    'constype' => $request->cm_constype,
                    'invflag' => $request->cm_invflag,
                    'packqty' => $request->cm_packqty,
                    'druggrcode' => $request->cm_druggrcode,
                    'subgroup' => $request->cm_subgroup,
                    'stockcode' => $request->cm_stockcode,
                    'invgroup' => $request->cm_invgroup, 
                    'costcode' => $request->cm_costcode, 
                    'revcode' => $request->cm_revcode, 
                    'seqno' => $request->cm_seqno, 
                    'overwrite' => $request->cm_overwrite, 
                    'doctorstat' => $request->cm_doctorstat, 
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'recstatus' => $recstatus_use,
                    'computerid' => $request->cm_computerid, 
                    'ipaddress' => $request->cm_ipaddress, 
                    'lastcomputerid' => $request->cm_lastcomputerid, 
                    'lastipaddress' => $request->cm_lastipaddress, 
                ]);

                $queries = DB::getQueryLog();

                $responce = new stdClass();
                $responce->queries = $queries;
                echo json_encode($responce);

             DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }
       
    }

    public function edit(Request $request){

        DB::beginTransaction();
        DB::enableQueryLog();

        try {

            if($request->cm_chgtype == 'PKG' || $request->cm_chgtype == 'pkg'){
                $recstatus_use = 'D';
            }else{
                $recstatus_use = 'A';
            }

            DB::table('hisdb.chgmast')
                ->where('idno','=',$request->cm_idno)
                ->update([
                    'chgcode' => $request->cm_chgcode,
                    'description' => $request->cm_description,
                    'chgclass' => $request->cm_chgclass,
                    'chggroup' => $request->cm_chggroup,
                    'chgtype' => $request->cm_chgtype,
                    'uom' => $request->cm_uom,
                    'brandname' => $request->cm_brandname,
                    'barcode' => $request->cm_barcode,
                    'constype' => $request->cm_constype,
                    'invflag' => $request->cm_invflag,
                    'packqty' => $request->cm_packqty,
                    'druggrcode' => $request->cm_druggrcode,
                    'subgroup' => $request->cm_subgroup,
                    'stockcode' => $request->cm_stockcode,
                    'invgroup' => $request->cm_invgroup, 
                    'costcode' => $request->cm_costcode, 
                    'revcode' => $request->cm_revcode, 
                    'seqno' => $request->cm_seqno, 
                    'overwrite' => $request->cm_overwrite, 
                    'doctorstat' => $request->cm_doctorstat, 
                    'upduser' => session('username'),
                    'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'recstatus' => $request->cm_recstatus,
                    'lastcomputerid' => $request->cm_lastcomputerid, 
                    'lastipaddress' => $request->cm_lastipaddress, 
                ]);

                $queries = DB::getQueryLog();

                $responce = new stdClass();
                $responce->queries = $queries;
                echo json_encode($responce);

             DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }
       
    }

    public function del(Request $request){

        DB::beginTransaction();
        DB::enableQueryLog();

        try {

            DB::table('hisdb.chgmast')
                ->where('idno','=',$request->cm_idno)
                ->update([
                    'delluser' => session('username'),
                    'deldate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'recstatus' => 'D',
                    'lastcomputerid' => $request->cm_lastcomputerid, 
                    'lastipaddress' => $request->cm_lastipaddress, 
                ]);

            
            $queries = DB::getQueryLog();

            $responce = new stdClass();
            $responce->queries = $queries;
            echo json_encode($responce);

             DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response('Error'.$e, 500);
        }
       
    }

    public function chgpricelatest(Request $request){
        $chgmast = DB::table('hisdb.chgmast')
                        ->where('compcode','=',session('compcode'))
                        ->paginate($request->rows);


        foreach ($chgmast->items() as $key => $value) {
            $chgprice = DB::table('hisdb.chgprice')
                        ->where('compcode','=',session('compcode'))
                        ->where('chgcode','=',$value->chgcode)
                        ->whereDate('effdate', '<', Carbon::now())
                        ->orderBy('effdate', 'DESC');
            if($chgprice->exists()){
                $chgprice_get = $chgprice->first();
                $value->chgprice_amt1 = $chgprice_get->amt1;
                $value->chgprice_amt2 = $chgprice_get->amt2;
                $value->chgprice_amt3 = $chgprice_get->amt3;
                // $value->chgprice_iptax = $chgprice_get->iptax;
                // $value->chgprice_optax = $chgprice_get->optax;
            }else{
                $value->chgprice_amt1 = "";
                $value->chgprice_amt2 = "";
                $value->chgprice_amt3 = "";
                // $value->chgprice_iptax = "";
                // $value->chgprice_optax = "";
            }
        }

        //////////paginate/////////

        $responce = new stdClass();
        $responce->page = $chgmast->currentPage();
        $responce->total = $chgmast->lastPage();
        $responce->records = $chgmast->total();
        $responce->rows = $chgmast->items();
        return json_encode($responce);
    }
}