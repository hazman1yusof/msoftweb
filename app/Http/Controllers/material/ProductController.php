<?php

namespace App\Http\Controllers\material;

use Illuminate\Http\Request;
use App\Http\Controllers\defaultController;
use stdClass;
use DB;
use Carbon\Carbon;

class ProductController extends defaultController
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
        return view('material.product.product');
    }

    public function form(Request $request)
    {  
        switch($request->oper){
            case 'add':
                if($request->action == 'save_productmaster'){
                    return $this->save_productmaster($request);
                }else{
                    return $this->defaultAdd($request);
                }
            case 'edit':
                return $this->defaultEdit($request);
            case 'del':
                return $this->defaultDel($request);
            default:
                return 'error happen..';
        }
    }

    public function save_productmaster(Request $request)
    {   
        if($request->Class == 'Asset'){
            DB::beginTransaction();

            $table = DB::table('material.product');

            $array_insert = [
                'itemcode' => $request->itemcode,
                'description' => $request->description,
                'groupcode' => $request->groupcode,
                'uomcode' => 'PC',
                'productcat' => $request->productcat,
                'Class' => $request->Class,
                'unit' => session('unit'),
                'compcode' => session('compcode'),
                'adduser' => session('username'),
                'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                'recstatus' => 'A',
                'computerid' => $request->computerid,
                'ipaddress' => $request->ipaddress,
            ];

            $er = $this->defaultAdd($request);

            if(!empty($er)){
                return $er;
            }

            try {


                $table->insert($array_insert);

                $responce = new stdClass();
                $responce->sql = $table->toSql();
                $responce->sql_bind = $table->getBindings();

                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                return response('Error'.$e->errorInfo[2], 500);
            }
        }
    }
}