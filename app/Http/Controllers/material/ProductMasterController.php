<?php

namespace App\Http\Controllers\material;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Auth;
use Carbon\Carbon;
use DateTime;

class ProductMasterController extends defaultController
{   

    var $table;
    var $duplicateCode;

    public function __construct()
    {
        $this->middleware('auth');
        $this->duplicateCode = "Code";
    }

    public function show(Request $request)
    {   
        return view('material.productMaster.productMaster');
    }

    public function form(Request $request)
    {  
        switch($request->oper){
            case 'add':
                return $this->add($request);
            case 'edit':
                return $this->defaultEdit($request);
            case 'del':
                return $this->defaultDel($request);
            default:
                return 'error happen..';
        }
    }

    public function add(Request $request){

        try {
            //check duplicate
            if(DB::table('material.productmaster')->where('itemcode','=',$request->itemcode)->count()){
                throw new \Exception($request->table_id.' '.$request[$request->table_id].' already exist', 500);
            }

            DB::beginTransaction();

            $table = DB::table('material.productmaster')
                        ->insert([
                            'itemcode' => $request->itemcode,
                            'description' =>  $request->description,
                            'groupcode' =>  $request->groupcode,
                            'productcat' =>  $request->productcat,
                            'recstatus' =>  $request->recstatus,
                            'Class' =>  $request->Class,
                            // 'unit' => session('unit'),
                            'computerid' =>  $request->computerid,
                            'lastcomputerid' =>  $request->lastcomputerid,
                            'ipaddress' =>  $request->ipaddress,
                            'lastipaddress' =>  $request->lastipaddress,
                            'compcode' => session('compcode'),
                            'adduser' => session('username'),
                            'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                            'recstatus' => 'ACTIVE'
                        ]);

            //if Class not STOCK terus buat product
            if(strtoupper($request->Class) == 'ASSET'){
                $this->save_prod_asset($request);
            }else if(strtoupper($request->Class) == 'OTHERS'){
                $this->save_prod_others($request);
            }else{
                $this->save_prod_stock($request);
            }

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

    public function save_prod_asset(Request $request){
        $table = 
            DB::table('material.product')
                ->insert([
                    'itemcode' => strtoupper($request->itemcode),
                    'description' => strtoupper($request->description),
                    'generic' => strtoupper($request->description),
                    'uomcode' => 'EA',
                    'Class' => strtoupper($request->Class),
                    'groupcode' => $request->groupcode,
                    'productcat' => $request->productcat,
                    'unit' => session('unit'),
                    'computerid' =>  $request->computerid,
                    'lastcomputerid' =>  $request->lastcomputerid,
                    'ipaddress' =>  $request->ipaddress,
                    'lastipaddress' =>  $request->lastipaddress,
                    'compcode' => session('compcode'),
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'recstatus' => 'ACTIVE'
                ]);
    }

    public function save_prod_others(Request $request){
        $table = 
            DB::table('material.product')
                ->insert([
                    'itemcode' => strtoupper($request->itemcode),
                    'description' => strtoupper($request->description),
                    'generic' => strtoupper($request->description),
                    'uomcode' => 'EA',
                    'Class' => strtoupper($request->Class),
                    'groupcode' => strtoupper($request->groupcode),
                    'productcat' => $request->productcat,
                    'reuse' => 0,
                    'rpkitem' => 0,
                    'tagging' => 0,
                    'expdtflg' => 0,
                    'chgflag' =>  0,
                    'maxqty' => 0,
                    'reordlevel' => 0,
                    'reordqty' => 0,
                    'itemtype' => 'NON-POISON',
                    'unit' => session('unit'),
                    'computerid' =>  $request->computerid,
                    'lastcomputerid' =>  $request->lastcomputerid,
                    'ipaddress' =>  $request->ipaddress,
                    'lastipaddress' =>  $request->lastipaddress,
                    'compcode' => session('compcode'),
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'recstatus' => 'ACTIVE'
                ]);
    }

    public function save_prod_stock(Request $request){
        $table = 
            DB::table('material.product')
                ->insert([
                    'itemcode' => strtoupper($request->itemcode),
                    'description' => strtoupper($request->description),
                    'groupcode' => strtoupper($request->groupcode),
                    'uomcode' => 'EA',
                    'productcat' => $request->productcat,
                    'Class' => $request->Class,
                    'unit' => session('unit'),
                    'compcode' => session('compcode'),
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'recstatus' => 'ACTIVE',
                    'computerid' => $request->computerid,
                    'ipaddress' => $request->ipaddress,
                ]);
    }
}