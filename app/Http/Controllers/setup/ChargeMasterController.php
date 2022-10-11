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
                $recstatus_use = 'DEACTIVE';

                DB::table('hisdb.chgprice')
                    ->where('compcode','=',session('compcode'))
                    ->where('chgcode','=',strtoupper($request->cm_chgcode))
                    ->update([
                        'pkgstatus' => 1,
                        'lastuser' => session('username'), 
                        'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    ]);

            }else{

                DB::table('hisdb.chgprice')
                    ->where('compcode','=',session('compcode'))
                    ->where('chgcode','=',strtoupper($request->cm_chgcode))
                    ->update([
                        'pkgstatus' => 0,
                        'lastuser' => session('username'), 
                        'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    ]);

                $recstatus_use = 'ACTIVE';
            }

            DB::table('hisdb.chgmast')
                ->insert([
                    'compcode' => session('compcode'),
                    'units' => session('unit'),
                    'chgcode' => strtoupper($request->cm_chgcode),
                    'description' => strtoupper($request->cm_description),
                    'barcode' => strtoupper($request->cm_barcode),
                    'brandname' => strtoupper($request->cm_brandname),
                    'chgclass' => $request->cm_chgclass,
                    'constype' => strtoupper($request->cm_constype),
                    'chggroup' => $request->cm_chggroup,
                    'chgtype' => $request->cm_chgtype,
                    'recstatus' => $recstatus_use,
                    'uom' => $request->cm_uom,
                    'invflag' => $request->cm_invflag,
                    'packqty' => $request->cm_packqty,
                    'druggrcode' => strtoupper($request->cm_druggrcode),
                    'subgroup' => strtoupper($request->cm_subgroup),
                    'stockcode' => strtoupper($request->cm_stockcode),
                    'invgroup' => strtoupper($request->cm_invgroup),
                    'costcode' => $request->cm_costcode, 
                    'revcode' => $request->cm_revcode, 
                    'seqno' => $request->cm_seqno,
                    'overwrite' => $request->cm_overwrite, 
                    'doctorstat' => $request->cm_doctorstat, 
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'computerid' => $request->cm_computerid, 
                    'ipaddress' => $request->cm_ipaddress, 
                    'lastcomputerid' => strtoupper($request->cm_lastcomputerid),
                    'lastipaddress' => strtoupper($request->cm_lastipaddress),
                ]);

                $queries = DB::getQueryLog();

                $responce = new stdClass();
                $responce->queries = $queries;
                echo json_encode($responce);

             DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
       
    }

    public function edit(Request $request){

        DB::beginTransaction();
        DB::enableQueryLog();

        try {

            if($request->cm_chgtype == 'PKG' || $request->cm_chgtype == 'pkg'){
                $recstatus_use = 'DEACTIVE';

                DB::table('hisdb.chgprice')
                    ->where('compcode','=',session('compcode'))
                    ->where('chgcode','=',strtoupper($request->cm_chgcode))
                    ->update([
                        'pkgstatus' => 1,
                        'lastuser' => session('username'), 
                        'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    ]);

            }else{

                DB::table('hisdb.chgprice')
                    ->where('compcode','=',session('compcode'))
                    ->where('chgcode','=',strtoupper($request->cm_chgcode))
                    ->update([
                        'pkgstatus' => 0,
                        'lastuser' => session('username'), 
                        'lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
                    ]);

                $recstatus_use = 'ACTIVE';
            }

            DB::table('hisdb.chgmast')
                ->where('idno','=',$request->cm_idno)
                ->update([
                    'chgcode' => strtoupper($request->cm_chgcode),
                    'description' => strtoupper($request->cm_description),
                    'barcode' => strtoupper($request->cm_barcode),
                    'brandname' => strtoupper($request->cm_brandname),
                    'chgclass' => $request->cm_chgclass,
                    'constype' => strtoupper($request->cm_constype),
                    'chggroup' => $request->cm_chggroup,
                    'chgtype' => $request->cm_chgtype,
                    'recstatus' => $recstatus_use,
                    'uom' => strtoupper($request->cm_uom),
                    'invflag' => $request->cm_invflag,
                    'packqty' => $request->cm_packqty,
                    'druggrcode' => strtoupper($request->cm_druggrcode),
                    'subgroup' => strtoupper($request->cm_subgroup),
                    'stockcode' => strtoupper($request->cm_stockcode),
                    'invgroup' => strtoupper($request->cm_invgroup),
                    'costcode' => $request->cm_costcode, 
                    'revcode' => $request->cm_revcode, 
                    'seqno' => $request->cm_seqno,
                    'overwrite' => $request->cm_overwrite, 
                    'doctorstat' => $request->cm_doctorstat,
                    'upduser' => session('username'),
                    'upddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'lastcomputerid' => strtoupper($request->cm_lastcomputerid),
                    'lastipaddress' => strtoupper($request->cm_lastipaddress),
                ]);

                $queries = DB::getQueryLog();

                $responce = new stdClass();
                $responce->queries = $queries;
                echo json_encode($responce);

             DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
       
    }

    public function del(Request $request){

        DB::beginTransaction();
        DB::enableQueryLog();

        try {

            DB::table('hisdb.chgmast')
                ->where('idno','=',$request->idno)
                ->update([
                    'deluser' => session('username'),
                    'deldate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'recstatus' => 'DEACTIVE',
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

            return response($e->getMessage(), 500);
        }
       
    }

    public function chgpricelatest(Request $request){
        $table = DB::table('hisdb.chgmast');

        if(!empty($request->searchCol)){
            $searchCol_array = $request->searchCol;

            $count = array_count_values($searchCol_array);
            // dump($count);

            foreach ($count as $key => $value) {
                $occur_ar = $this->index_of_occurance($key,$searchCol_array);

                $table = $table->orWhere(function ($table) use ($request,$searchCol_array,$occur_ar) {
                    foreach ($searchCol_array as $key => $value) {
                        $found = array_search($key,$occur_ar);
                        if($found !== false){
                            $table->Where($searchCol_array[$key],'like',$request->searchVal[$key]);
                        }
                    }
                });
            }
        }

        if(!empty($request->searchCol2)){

            $table = $table->where(function($query) use ($request){
                $searchCol_array = $request->searchCol2;

                foreach ($searchCol_array as $key => $value) {
                    $query = $query->orWhere($searchCol_array[$key],'like',$request->searchVal2[$key]);
                }
            });
        }

        $table = $table
                ->where('recstatus','=','ACTIVE')
                ->where('compcode','=',session('compcode'));

        $paginate = $table->paginate($request->rows);

        foreach ($paginate->items() as $key => $value) {
            $chgprice = DB::table('hisdb.chgprice')
                        ->where('compcode','=',session('compcode'))
                        ->where('chgcode','=',$value->chgcode)
                        ->whereDate('effdate', '<=', Carbon::now())
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
        $responce->page = $paginate->currentPage();
        $responce->total = $paginate->lastPage();
        $responce->records = $paginate->total();
        $responce->rows = $paginate->items();
        $responce->sql = $table->toSql();
        $responce->sql_bind = $table->getBindings();
        return json_encode($responce);
    }
}