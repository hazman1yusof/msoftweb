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
                return $this->edit($request);
            case 'del':
                return $this->defaultDel($request);
            default:
                return 'error happen..';
        }
    }

    public function save_productmaster(Request $request)
    {   
        // if(strtoupper($request->Class) == 'ASSET'){
            DB::beginTransaction();

            $table = DB::table('material.product');

            $array_insert = [
                'itemcode' => $request->itemcode,
                'description' => strtoupper($request->description),
                'groupcode' => strtoupper($request->groupcode),
                'uomcode' => 'PC',
                'productcat' => $request->productcat,
                'Class' => $request->Class,
                'unit' => session('unit'),
                'compcode' => session('compcode'),
                'adduser' => session('username'),
                'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                'recstatus' => 'ACTIVE',
                'computerid' => $request->computerid,
                'ipaddress' => $request->ipaddress,
                'cm_packqty' => $request->cm_packqty,
                'cm_druggrcode' => $request->cm_druggrcode,
                'cm_subgroup' => $request->cm_subgroup,
                'cm_stockcode' => $request->cm_stockcode,
                'cm_chgclass' => $request->cm_chgclass,
                'cm_chggroup' => $request->cm_chggroup,
                'cm_chgtype' => $request->cm_chgtype,
                'cm_invgroup' => $request->cm_invgroup,
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
        // }
    }

    public function edit(Request $request)
    {   
        // if(strtoupper($request->Class) == 'ASSET'){
            DB::beginTransaction();

            $table = DB::table('material.product')->where('idno','=',$request->idno);

            $array_insert = [
                'description' => strtoupper($request->description),
                'generic' => strtoupper($request->generic),
                'groupcode' => strtoupper($request->groupcode),
                'Class' => $request->Class,
                'unit' => session('unit'),
                'compcode' => session('compcode'),
                'subcatcode' => strtoupper($request->subcatcode),
                'pouom' => strtoupper($request->pouom),
                'suppcode' => strtoupper($request->suppcode),
                'mstore' => strtoupper($request->mstore),
                'TaxCode' => strtoupper($request->TaxCode),
                'minqty' => $request->minqty,
                'maxqty' => $request->maxqty,
                'reordlevel' => $request->reordlevel,
                'reordqty' => $request->reordqty,
                'reuse' => $request->reuse,
                'rpkitem' => $request->rpkitem,
                'tagging' => $request->tagging,
                'expdtflg' => $request->expdtflg,
                'chgflag' => $request->chgflag,
                'itemtype' => $request->itemtype,
                'cm_packqty' => $request->cm_packqty,
                'cm_druggrcode' => $request->cm_druggrcode,
                'cm_subgroup' => $request->cm_subgroup,
                'cm_stockcode' => $request->cm_stockcode,
                'cm_chgclass' => $request->cm_chgclass,
                'cm_chggroup' => $request->cm_chggroup,
                'cm_chgtype' => $request->cm_chgtype,
                'cm_invgroup' => $request->cm_invgroup,
                'adduser' => session('username'),
                'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                'recstatus' => 'ACTIVE',
                'computerid' => $request->computerid,
                'ipaddress' => $request->ipaddress,
            ];

            try {


                $table->update($array_insert);

                $responce = new stdClass();
                $responce->sql = $table->toSql();
                $responce->sql_bind = $table->getBindings();

                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                return response('Error'.$e->errorInfo[2], 500);
            }
        // }
    }
}