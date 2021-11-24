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
                    $exists = DB::table('material.product')
                            ->where('compcode','=',session('compcode'))
                            ->where('itemcode','=',$request->itemcode)
                            ->where('uomcode','=',$request->uomcode)
                            ->where('unit','=',session('unit'))
                            ->exists();

                    if($exists){
                        return response('Error itemcode and uom already exists', 500);
                    }
                    return $this->add($request);
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
            ];

            $er = $this->add($request);

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
                return response($e->getMessage(), 500);
            }
        // }
    }

    public function add(Request $request)
    {   
            DB::beginTransaction();

            try {

                $array_insert = [
                    'itemcode' => $request->itemcode,
                    'uomcode' => $request->uomcode,
                    'productcat' => $request->productcat,
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
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'recstatus' => 'ACTIVE',
                    'computerid' => $request->computerid,
                    'ipaddress' => $request->ipaddress,
                ];

                DB::table('material.product')->insert($array_insert);


                $array_insert = [
                    'compcode' => session('compcode'),
                    'chgcode' => $request->itemcode,
                    'uom' => $request->uomcode,
                    'description' => strtoupper($request->description),
                    'brandname' => strtoupper($request->generic),
                    'chgclass' => $request->cm_chgclass,
                    'chggroup' => $request->cm_chggroup,
                    'chgtype' => $request->cm_chgtype,
                    'recstatus' => 'ACTIVE',
                    'packqty' => $request->cm_packqty,
                    'druggrcode' => strtoupper($request->cm_druggrcode),
                    'subgroup' => strtoupper($request->cm_subgroup),
                    'stockcode' => strtoupper($request->cm_stockcode),
                    'invgroup' => strtoupper($request->cm_invgroup),
                    'dosecode' => $request->cm_dosage,
                    'freqcode' => $request->cm_frequency,
                    'instruction' => $request->cm_instruction,

                    // 'barcode' => strtoupper($request->cm_barcode),
                    // 'constype' => strtoupper($request->cm_constype),
                    // 'invflag' => $request->cm_invflag,
                    // 'costcode' => $request->cm_costcode, 
                    // 'revcode' => $request->cm_revcode, 
                    // 'seqno' => $request->cm_seqno,

                    'overwrite' => 0, 
                    'doctorstat' => 0, 
                    'adduser' => session('username'),
                    'adddate' => Carbon::now("Asia/Kuala_Lumpur"),
                    'computerid' => $request->cm_computerid, 
                    'ipaddress' => $request->cm_ipaddress, 
                    'lastcomputerid' => strtoupper($request->cm_lastcomputerid),
                    'lastipaddress' => strtoupper($request->cm_lastipaddress),
                ];

                DB::table('hisdb.chgmast')->insert($array_insert);

                $responce = new stdClass();
                $queries = DB::getQueryLog();
                $responce->queries = $queries;
                echo json_encode($responce);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                return response($e->getMessage(), 500);
            }
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
                return response($e->getMessage(), 500);
            }
        // }
    }
}